<?php
require 'razorpay/Razorpay.php';

use Razorpay\Api\Api;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php'; // If using Composer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include_once './app/class/databaseConn.php';

$DatabaseCo = new DatabaseConn();

// Fetch data from query string
$meal = isset($_GET['meal']) ? $_GET['meal'] : 0;

// Get and process dates from GET request
$dates = isset($_GET['dates']) ? explode(',', $_GET['dates']) : [];
// Combine all selected dates into a readable string
$service_date = !empty($dates) ? implode(', ', $dates) : 'No dates selected';

// Count total selected dates
$total_dates = count($dates);

// Ensure total calculation is based on dates
$total_meal_amount = ($meal > 0 && $total_dates > 0) ? ($meal * $total_dates) : 0;

// Display the total calculated amount and selected dates
// echo "<h2>Meal Calculation Summary</h2>";
// echo "<p><strong>Total Selected Dates:</strong> $total_dates Days</p>";

if (!empty($dates)) {
  // echo "<p><strong>Selected Dates:</strong></p>";
  // echo "<ul>";
  foreach ($dates as $date) {
    // echo "<li>$date</li>";
  }
  // echo "</ul>";
} else {
  // echo "<p>No dates selected.</p>";
}

// echo "<p><strong>Total Meal Amount:</strong> $" . number_format($total_meal_amount, 2) . "</p>";

// Razorpay credentials
$keyId     = "rzp_live_phdyxCDur7y7mB";
$keySecret = "QCnsNY9PEXkVLdL2YTfOn65I";
// $keyId     = "rzp_test_geqUM0Pl34yBo6";
// $keySecret = "ar3OtUJd6ZRwDCvy5vWkcLI0";
// Create Razorpay API instance
$api = new Api($keyId, $keySecret);

// Process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  // ─── STEP 1: CREATE RAZORPAY ORDER ─────────────────────────────
  if ($action === 'create_order') {
    $donationAmount = floatval($_POST['total_amount'] ?? 0);
    if ($donationAmount <= 0) {
      echo json_encode(["status" => "error", "message" => "Invalid donation amount"]);
      exit;
    }

    try {
      $orderData = [
        'receipt'         => 'REC_' . rand(1000, 9999),
        'amount'          => $donationAmount * 100, // Convert to paise
        'currency'        => 'INR',
        'payment_capture' => 1
      ];
      $razorpayOrder = $api->order->create($orderData);
      echo json_encode(["status" => "success", "order_id" => $razorpayOrder['id']]);
    } catch (Exception $e) {
      echo json_encode(["status" => "error", "message" => "Error creating order: " . $e->getMessage()]);
    }
    exit;
  }

  // ─── STEP 2: COMPLETE DONATION ─────────────────────────────
  elseif ($action === 'complete_donation') {
    // Get form fields
    $donorName      = trim($_POST['name'] ?? '');
    $donorEvent     = trim($_POST['event'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $serviceDate    = trim($_POST['service_date'] ?? '');
    $dob            = trim($_POST['dob'] ?? '');
    $foodCount      = intval($_POST['meals_count'] ?? 0);
    $donationAmount = floatval($_POST['total_amount'] ?? 0);
    $comments       = trim($_POST['comments'] ?? '');
    $razorpayOrderId = $_POST['order_id'] ?? '';
    $paymentId      = $_POST['payment_id'] ?? '';

    if (empty($razorpayOrderId) || empty($paymentId)) {
      echo json_encode(["status" => "error", "message" => "Payment information missing"]);
      exit;
    }

    try {
      // Fetch payment details
      $payment = $api->payment->fetch($paymentId);
      $paymentMethod = $payment->method;
      $paymentStatus = $payment->status; // Should be "captured" for successful payments

      if ($paymentStatus !== 'captured') {
        echo json_encode(["status" => "error", "message" => "Payment not successful"]);
        exit;
      }

      // Insert donation into database
      $sql = "INSERT INTO donation 
                  (name, event, email, phone, service_date, dob, meals_count, total_amount, comments, status, payment_id, order_id, payment_method) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $DatabaseCo->dbLink->prepare($sql);
      $status = "completed";

      $stmt->bind_param(
        "ssssssidsssss",
        $donorName,
        $donorEvent,
        $email,
        $phone,
        $serviceDate,
        $dob,
        $foodCount,
        $donationAmount,
        $comments,
        $status,
        $paymentId,
        $razorpayOrderId,
        $paymentMethod
      );

      if ($stmt->execute()) {
        $response = ["status" => "success", "message" => "Donation recorded successfully"];

        echo json_encode($response);
        // Send Email Invoice
        if (sendInvoiceEmail($donorName, $email, $donationAmount, $serviceDate, $paymentMethod)) {
          $response["email"] = "Donation receipt sent successfully!";
        } else {
          $response["email_status"] = "Failed to send receipt.";
        }
      } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
      }

      $stmt->close();
    } catch (Exception $e) {
      echo json_encode(["status" => "error", "message" => "Error processing payment: " . $e->getMessage()]);
    }
    exit;
  }
}

// ─── FUNCTION TO SEND EMAIL ───────────────────────────
function sendInvoiceEmail($donorName, $email, $donationAmount, $serviceDate, $paymentMethod)
{
  $mail = new PHPMailer(true);
  // echo $donationAmount;
  try {
    // SMTP Server Settings (Gmail)
    $mail->isSMTP();
    $mail->SMTPDebug  = 0; // Change to 2 for debugging
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username = 'ajaygowri3011@gmail.com'; // Your Gmail ID
    $mail->Password = 'ysisrpxxtwfltlos'; // Gmail App Password (not your Gmail password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email Headers
    $mail->setFrom('info@chandransyuva.com', 'Chandrans Yuva Foundation');
    $mail->addAddress($email, $donorName); // Recipient

    // Email Subject & Body
    $mail->isHTML(true);
    $mail->Subject = "Annasthalam Invoice - Thank You!";
    $mail->Body = "
      <!DOCTYPE html>
      <html lang='en'>
      <head>
          <meta charset='UTF-8'>
          <meta name='viewport' content='width=device-width, initial-scale=1.0'>
          <title>Annasthalam Invoice - Thank You!</title>
          <style>
              body {
                  background-color: #f4f4f4;
                  font-family: 'Arial', sans-serif;
                  margin: 0;
                  padding: 20px;
              }
              .container {
                  max-width: 600px;
                  margin: auto;
                  background: #ffffff;
                  padding: 25px;
                  border-radius: 12px;
                  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                  border: 1px solid #e1e1e1;
                  text-align: center;
              }
              .header {
                  padding-bottom: 15px;
                  border-bottom: 3px solid #28a745;
              }
              .header h2 {
                  font-size: 32px;
                  font-weight: bold;
                  color: #28a745;
                  margin-bottom: 5px;
                  text-transform: uppercase;
                  letter-spacing: 1px;
              }
              .header p {
                  font-size: 18px;
                  color: #444;
                  font-weight: bold;
                  margin: 0;
              }
              .receipt-box {
                  text-align: left;
                  margin-top: 20px;
                  padding: 20px;
                  border-radius: 8px;
                  background: #f8f8f8;
                  box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
              }
              .receipt-box p {
                  font-size: 16px;
                  color: #333;
                  margin: 8px 0;
              }
              .highlight {
                  font-weight: bold;
                  color: #28a745;
              }
              .thank-you {
                  margin-top: 20px;
                  font-size: 20px;
                  font-weight: bold;
                  color: #28a745;
              }
              .footer {
                  font-size: 14px;
                  color: #777;
                  margin-top: 15px;
                  padding-top: 10px;
                  border-top: 1px solid #ddd;
              }
          </style>
      </head>
      <body>
      
          <div class='container'>
              <!-- Header -->
              <div class='header'>
                  <h2>ANNSTHALAM</h2>
                  <p>Donation Receipt</p>
              </div>
      
              <!-- Receipt Details -->
              <div class='receipt-box'>
                  <p><strong>Name:</strong> <span class='highlight'>$donorName</span></p>
                  <p><strong>Email:</strong> <span class='highlight'>$email</span></p>
                  <p><strong>Donation Amount:</strong> <span class='highlight'>$donationAmount</span></p>
                  <p><strong>Service Date:</strong> <span class='highlight'>$serviceDate</span></p>
                  <p><strong>Payment Method:</strong> <span class='highlight'>$paymentMethod</span></p>
              </div>
      
              <!-- Thank You Message -->
              <p class='thank-you'>Thank you for your generous donation!</p>
      
              <!-- Footer -->
              <p class='footer'>Your support helps Annsthalam continue its mission of serving those in need.</p>
          </div>
      
      </body>
      </html>";




    if ($mail->send()) {
      return true;
    } else {
      error_log("Mailer Error: " . $mail->ErrorInfo);
      return false;
    }
  } catch (Exception $e) {
    error_log("PHPMailer Exception: " . $e->getMessage());
    return false;
  }
}

?>






<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Annasthalam</title>
  <!-- Include AOS Library (For Animations) -->

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="./image/logo.jpg" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <!-- Swiper CSS -->


  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <!-- toster -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <!-- FancyBox CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
  <style>
    .hero {
      background: url('banner.jpg') no-repeat center center/cover;
      height: 100vh;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .meal-card img {
      height: 320px;
      object-fit: cover;
    }

    .meal-card {
      height: 100%;
    }
  </style>
  <style>
    /* Hero Section */
    .hero {
      background: url('./image/banner-2.JPG') no-repeat center center/cover;
      background-attachment: fixed;
      height: 90vh;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      position: relative;
      padding: 20px;
    }

    /* Semi-Transparent Overlay for Readability */
    .hero::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1;
    }

    /* Ensure text stays on top */
    .hero .container {
      position: relative;
      z-index: 2;
    }

    /* Dynamic Heading & Text Sizing */
    .hero h1 {
      font-size: clamp(32px, 5vw, 64px);
      font-weight: bold;
      margin-bottom: 15px;
    }

    .hero p {
      font-size: clamp(16px, 2vw, 24px);
      font-weight: 500;
    }

    /* Button Styling */
    .hero .btn {
      font-size: clamp(14px, 1.5vw, 18px);
      padding: 12px 24px;
      border-radius: 5px;
      background-color: #007bff;
      border: none;
      color: white;
      transition: background 0.3s ease-in-out;
    }

    .hero .btn:hover {
      background-color: #0056b3;
    }

    /* Lead Text */
    .lead {
      font-size: clamp(16px, 2vw, 20px);
      font-weight: 500;
    }

    /* Heading Style */
    h2 {
      font-family: "Roboto", Sans-serif;
      font-size: clamp(28px, 4vw, 40px);
      font-weight: 700;
      line-height: 1.2em;
      letter-spacing: 1px;
      color: #000000;
    }

    /* Tablet (768px and below) */
    @media (max-width: 768px) {
      .hero {
        height: 100vh;
        background-size: cover;
        padding: 15px;
      }

      .hero h1 {
        font-size: 18px;
      }

      .hero p {
        font-size: 14px;
      }

      .hero .btn {
        font-size: 1rem;
        padding: 10px 20px;
      }
    }

    /* Mobile (480px and below) */
    @media (max-width: 480px) {
      .hero {
        height: 100vh;
        padding: 8px;
      }

      .hero h1 {
        font-size: 17px;
        margin-top: 20px !important;
      }

      .hero p {
        font-size: 13px;
        text-align: justify;
      }

      .hero .btn {
        font-size: 1rem;
        padding: 5px 12px;
      }

      .banner-title {
        font-size: 13px;
       
      }
      .lead {
      font-size: 16px;
      font-weight: 400;
    }

    }
  </style>
  <style>
    .form-container {
      max-width: 900px;
      margin: 2rem auto;

      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-heading {
      text-align: center;
      margin-bottom: 1.5rem;
      font-weight: bold;
    }

    .form-label {
      font-size: 17px;
      font-weight: 500;
    }

    @media (max-width: 480px) {
      .form-heading {
      text-align: center;
      font-size: 18px;
      margin-bottom: 1.5rem;
      font-weight: bold;
    }
    }
  </style>
  <style>
    .carousel-container {
      max-width: 350px;
      /* Adjust width */
      margin: auto;
      position: relative;
      overflow: hidden;
      height: 380px;
      /* Adjust based on content height */
    }

    .carousel-inner {
      display: flex;
      flex-direction: column;
      transition: transform 1s ease-in-out;
    }

    .carousel-item {
      min-height: 140px;
      /* Adjust height */
      margin: 1rem;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .cause-card {
      width: 100%;
      border-radius: 10px;
      overflow: hidden;
      border: 1px solid #ddd;
      padding: 20px;
      background: #fff;
      text-align: center;
    }

    .cause-card img {
      width: 80px;
      height: 80px;
      border-radius: 5px;
    }

    .cause-info {
      display: flex;
      align-items: center;
      gap: 10px;
      justify-content: center;
    }

    .cause-text {
      font-size: 14px;
      flex-grow: 1;
    }
  </style>
  <style>
    /* General Styles */
    .stats-box {
      background-color: #ddd;
      color: black;
      padding: 10px;
      text-align: center;
      border-radius: 5px;
      font-size: clamp(14px, 1.2vw, 18px);
      /* Responsive font */
    }

    .mission-title {
      font-weight: bold;
      color: black;

    }

    .donate-btn {
      background-color: #17a2b8;
      border: none;
      padding: 12px 20px;
      font-size: clamp(14px, 1.5vw, 18px);
      /* Responsive button text */
      transition: background 0.3s ease-in-out;
    }

    .donate-btn:hover {
      background-color: #138496;
    }

    /* Responsive Image Gallery */
    .image-gallery img,
    .col-6 img {
      width: 100%;
      height: auto;
      max-height: 450px;
      /* Limits image height */
      object-fit: cover;
      border-radius: 5px;
    }

    /* Responsive Content */
    .content {
      font-size: clamp(14px, 1.2vw, 17px);
      line-height: 1.8;
      padding: 10px;
    }

    /* Tablet (768px and below) */
    @media (max-width: 768px) {
      .stats-box {
        padding: 8px;
        font-size: 16px;
      }

      .mission-title {
        font-size: 20px;
      }

      .donate-btn {
        padding: 10px 18px;
        font-size: 16px;
      }

      .content {
        font-size: 15px;
        line-height: 1.6;
      }

      .image-gallery img,
      .col-6 img {
        max-height: 250px;
        /* Adjust height for tablets */
      }
    }

    /* Mobile (480px and below) */
    @media (max-width: 480px) {
      .stats-box {
        padding: 6px;
        font-size: 14px;
      }

      .mission-title {
        font-size: 18px;
      }

      .donate-btn {
        width: 100%;
        padding: 10px;
        font-size: 15px;
      }

      .content {
        font-size: 14px;
        line-height: 1.5;
      }

      .image-gallery img,
      .col-6 img {
        max-height: 200px;
        /* Smaller images for small screens */
      }
    }
  </style>
  <!-- Custom CSS -->
  <style>
    .stats-box {
      background: linear-gradient(135deg, #6e8efb, #a777e3);
      color: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease-in-out;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100%;
      /* Ensures equal height */
      min-height: 120px;
      /* Prevents shrinkage */
    }

    .stats-box:hover {
      transform: scale(1.05);
    }

    .stat-number {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 5px;
    }

    p {
      font-size: 1.1rem;
      font-weight: 500;
      margin: 0;
    }
  </style>
  <style>
    .carousel-banner {
      width: 100%;
      /* height: 400px; */
      overflow: hidden;
    }

    .owl-carousel img {
      width: 100%;
      height: auto;
      object-fit: cover;
    }

    /* Optional: Style the text if you add captions */
    .carousel-caption {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-size: 2rem;
    }
  </style>
  <style>
    /* Success message background color */
    .toast-success {
      background-color: #28a745;
      /* Green */
    }

    /* Error message background color */
    .toast-error {
      background-color: #dc3545;
      /* Red */
    }

    /* Customize the Toastr position and other styles if needed */
    .toast {
      font-size: 16px;
      /* Set font size */
      padding: 15px;
      /* Set padding for toast message */
    }
  </style>
  <!-- charousal  -->
  <style>
    .carousel-gallery {
      margin-top: 50px;
      overflow: hidden;
    }

    .swiper-container {
      width: 100%;
      overflow: hidden;
    }

    .swiper-wrapper {
      display: flex;
      transition: transform 0.3s ease;
    }

    .swiper-slide {
      position: relative;
      width: 100%;
      flex: 0 0 auto;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .image {
      width: 100%;
      height: 250px;
      background-size: cover;
      background-position: center;
      transition: transform 0.3s ease;
    }

    .swiper-slide:hover .image {
      transform: scale(1.05);
    }

    .swiper-pagination {
      position: relative;
      margin-top: 20px;
    }
  </style>
  <!-- thankyou -->
  <style>
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      /* Dark semi-transparent overlay */
      display: none;
      /* Initially hidden */
      z-index: 1040;
      /* Behind the modal */
    }

    #thankYouModal {
      display: none;
      /* Hide modal initially */
      z-index: 1050;
      /* Ensure it's above the overlay */
    }
  </style>

  <!-- header -->
  <style>
    .top-bar {
      background: #006cbe;
      color: white;
      padding: 15px 15px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    /* Links */
    .top-bar a {
      color: white;
      text-decoration: none;
      font-size: 17px;
      font-weight: 550;
      margin-right: 15px;
      display: inline-flex;
      align-items: center;
    }

    .top-bar i {
      font-size: 16px;
      color: #6EC1E4;
      margin-right: 5px;
    }

    /* Rolling Text */
    .rolling-container {
      flex-grow: 1;
      text-align: center;
      overflow: hidden;
      white-space: nowrap;
      position: relative;
      max-width: 50%;
    }

    .rolling-text {
      font-size: 17px;
      display: inline-block;
      animation: rollText 10s linear infinite;
    }

    @keyframes rollText {
      from {
        transform: translateX(100%);
      }

      to {
        transform: translateX(-100%);
      }
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
      .top-bar {
        flex-direction: column;
        text-align: center;
      }

      .rolling-container {
        max-width: 100%;
        margin-top: 5px;
      }

      .top-bar div {
        width: 100%;
        margin: 5px 0;
      }

      .top-bar a {
        display: block;
        margin: 5px 0;
      }
    }

    .header-logo img {
      height: 50px;
    }

    .navbar-nav .nav-item {
      padding: 0 10px;
    }

    .nav-link {
      color: black;
      font-weight: 500;
    }

    .new-badge {
      background: #00c09d;
      color: white;
      font-size: 12px;
      padding: 2px 5px;
      border-radius: 3px;
      margin-left: 5px;
    }

    .btn-member {
      font-size: 14px;
      font-weight: bold;
      line-height: 1em;
      letter-spacing: 1px;
      color: #ffffff;
      border-radius: 30px;
      box-shadow: 0px 0px 0px 0px rgb(0 0 0 / 10%);
      padding: 12px 25px 12px 25px;
      border-radius: 25px;
      background-color: transparent;
      background-image: linear-gradient(180deg, #0e336a 0%, #164996 100%);
    }

    .carousel-item {
      height: 300px;
      /* background: #ddd; */
      color: black;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      font-weight: bold;
    }

    .navbar-toggler {
      order: 1;
      border: none;
      outline: none;
    }

    .navbar-collapse {
      order: 2;
      justify-content: flex-end;
    }

    .navbar-nav {}

    .carousel {
      background-image: url(../image/imges17.webp);
    }

    .nav-link {
      padding: 0px 7px;
      font-size: 16px;
      font-weight: normal;
      line-height: 1em;
      letter-spacing: 1px;
      color: #000000;
    }

    /* Mobile-specific styles */
    @media (max-width: 991px) {
      .navbar-collapse {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: black;
        z-index: 1000;
        padding: 10px 0;
      }

      .navbar-nav .nav-link {
        color: white !important;
        font-size: 24px;
      }

      .navbar-toggler-icon {
        background-image: none;
        font-size: 24px;
        color: white;
      }
    }

    .collapse {
      padding: 30px 0px;
      /* filter: brightness(100%) contrast(100%) saturate(0%) blur(0px) hue-rotate(0deg); */
    }
  </style>
  <style>
    /* Dropdown Menu */
    .dropdown-menu {
      background: rgba(0, 0, 0, 0.8);
      /* Semi-transparent black */
      border: none;
      padding: 10px;
      border-radius: 5px;
    }

    .dropdown-item {
      color: white;
      font-size: 16px;
      padding: 10px;
      transition: background 0.3s ease;
    }

    .dropdown-item:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    /* Navbar Transparency */
    .navbar {
      background: rgba(255, 255, 255, 0.9);
      /* Semi-transparent white */
      transition: background 0.3s ease-in-out;
    }

    .navbar:hover {
      background: rgba(255, 255, 255, 1);
      /* Full opacity on hover */
    }
  </style>

   <!-- footer -->
    
  
   <style>
      /* Footer Styling */
      .footer {
        background: #002147;
        color: white;
        padding: 40px 0;
      }
      .footer a {
        color: white;
        text-decoration: none;
      }
      .footer a:hover {
        text-decoration: underline;
      }
      .newsletter input {
        width: 70%;
        padding: 10px;
        border: none;
        border-radius: 5px;
      }
      .newsletter button {
        padding: 10px 20px;
        background: #00d084;
        color: white;
        border: none;
        border-radius: 5px;
      }
      .social-icons a {
        margin: 0 10px;
        font-size: 24px;
      }
      .supporters img {
        width: 100px;
        height: 100px;
        margin: 5px;
      }
      .footer-content {
        font-weight: normal;
        font-size: 14px;
        line-height: 2em;
        letter-spacing: 1px;
      }
      .list-unstyled {
        display: flex;
        min-height: 1px;
        position: relative;
        gap: 2rem;
      }
      .list-unstyled li {
        margin-top: 20px;
        margin-bottom: 20px;
      }
      .newsletter input {
        text-align: left;
        width: 100%;
        color: #A4A4A4 !important;
        background-color: #FFFFFF21;
        border: none;
        border-radius: 0px;
        padding: 10px 20px;
      }
      .newsletter button {
        background-color: #00baa3;
        border-width: 1px;
        border-color: #00baa3;
        border-radius: 30px;
        border-style: solid;
        padding: 10px 30px;
        margin-left: 30px;
        -webkit-appearance: none;
        color: #ffffff;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
      }
      .copy {
        color: #164A9A;
        font-size: inherit;
        font-weight: 700;
        line-height: inherit;
      }

      /* Responsive Styles */
      @media screen and (max-width: 1024px) {
        /* Tablet View */
        .list-unstyled {
          flex-wrap: wrap;
          gap: 1rem;
        }
        .newsletter {
          text-align: center;
        }
        .newsletter input {
          width: 80%;
        }
        .newsletter button {
          margin-left: 0;
          margin-top: 10px;
        }
        .supporters img {
          width: 80px;
          height: 80px;
        }
      }

      @media screen and (max-width: 768px) {
        /* Mobile View */
        .footer {
          padding: 30px 10px;
          text-align: center;
        }
        .list-unstyled {
          flex-direction: column;
          align-items: start;
        }
        .newsletter input {
          width: 100%;
        }
        .newsletter button {
          width: 100%;
          padding: 12px;
        }
        .social-icons a {
          font-size: 20px;
          margin: 5px;
        }
        .supporters img {
          width: 60px;
          height: 60px;
        }
        .footer-logo{
          width: 200px;
          height: 100px;
        }
        .SUPPORTERS{
         margin-top: 10px;
        }
      }
      .card-link {
      text-decoration: none;
      color: inherit;
      display: block;
      position: relative;
  }
    </style>


</head>

<body>
<?php
    // Fetch all data from cab_tour_packages table
    $select = "SELECT * FROM business_setting";

    $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);

    // Check if the query returns any rows
    if (mysqli_num_rows($SQL_STATEMENT) > 0) {
        while ($Row = mysqli_fetch_assoc($SQL_STATEMENT)) {

    ?>
<div class="top-bar">
    <div>
      <a href="https://chandransyuva.com/index.php/causes/isr/">
        <i class="fa-solid fa-id-card"></i> Become Member
      </a>
      <a href="https://www.google.com/maps/place/Chandrans+Yuva+Foundation/">
        <i class="fa-solid fa-map-marker-alt"></i> Get Direction
      </a>
    </div>

    <div class="rolling-container">
      <div class="rolling-text"> We are donating food daily in our Head Office - Coimbatore </div>
    </div>

    <div>
      <a href="mailto:info@chandransyuva.com">
        <i class="fa fa-envelope"></i> <?php echo $Row['email']; ?>
      </a>
      <a href="tel:+91<?php echo $Row['phone_number']; ?>">
        <i class="fa fa-phone"></i> +91 <?php echo $Row['phone_number']; ?>
      </a>
    </div>
  </div>
  <?php
        }
    } else {
        echo "";
    }
    ?>

  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <a
      class="navbar-brand header-logo d-flex justify-content-start align-items-start ms-4"
      href="https://chandransyuva.com/index.php/causes/isr/">
      <img src="./image/imges1.webp" alt="Logo" />
    </a>
    <div class="container">
      <a href="https://chandransyuva.com/index.php/causes/isr/" class="btn btn-member d-lg-none d-sm-block">Become ISR Member</a>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav">
        <i class="fa fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="https://chandransyuva.com/">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="https://chandransyuva.com/index.php/nd-donation/causes-01/">Causes</a></li>
            <li class="nav-item">
              <a class="nav-link" href="https://chandransyuva.com/index.php/about-02/"
                >About <span class="new-badge">NEW</span></a
              >
            </li>
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="yearbookDropdown"
                role="button"
                data-bs-toggle="dropdown"
              >
                Year Book
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Annual-Report-CYF-2018-2019.pdf">2017 – 2018</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Annual-Report-CYF-2018-2019.pdf">2018 – 2019</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Yuva-Annual-Report-CYF-2019-2020.pdf">2019 – 2020</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Year-Book-CYF-2020-2021.pdf">2020 – 2021</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="NewsletterDropdown"
                role="button"
                data-bs-toggle="dropdown"
              >
                Newsletter
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Year-Book-CYF-2020-2021.pdf">2021 – May</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Cyf-newsletter-sep2021.pdf">2021 – september</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/CYF-E-news-letter22.pdf">2022 – March</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/News-Letter.pdf">2022 – Autg-Out</a></li>
              </ul>
            </li>

            <li class="nav-item"><a class="nav-link" href="https://chandransyuva.com/index.php/blog/">Blog</a></li>
            <li class="nav-item">
              <a class="nav-link" href="https://chandransyuva.com/index.php/gallery-pages/gallery-01/"
                >Gallery <span class="new-badge">NEW</span></a
              >
            </li>
            <li class="nav-item"><a class="nav-link" href="https://chandransyuva.com/index.php/events/category/event/">Events</a></li>
            <li class="nav-item"><a class="nav-link" href="https://chandransyuva.com/index.php/contact-pages/contact-02/">Contact</a></li>
          </ul>
          <a href="https://chandransyuva.com/index.php/causes/isr/" class="btn btn-member d-none d-lg-block"
            >Become ISR Member</a
          >
        </div>
    </div>
  </nav>
  <header class="hero">
    <div class="container text-center my-5">
      <h1 class="display-4 fw-bold banner-title">SUPPORT THOSE IN NEED</h1>
      <p class="lead ">Your contribution today is a catalyst for impact. Donate now and be part of the journey towards creating positive transformations and brighter tomorrows. Every contribution, no matter the size, makes a meaningful difference. Join us in building a future where every act of giving sparks lasting change</p>
      <a href="#donation-options" class="btn btn-primary btn-lg mt-3">Donate Now</a>
    </div>
  </header>
  <div class="container my-5">
    <div class="row">
      <div class="col-md-6">
        <h2 class="mission-title">MAKE AN IMPACT TODAY</h2>
        <p style="text-align: justify;" class="content">
          Your support provides food, shelter, and education to those in need, transforming lives with every contribution. Join our mission to create a better future for underprivileged communities. With your generosity, we can bring hope and relief to countless individuals. Be the reason someone smiles today donate now and be a part of something great. Every meal, every blanket, and every helping hand can change a life for the better. Your kindness and support help us reach more people in need. We believe in the power of collective effort together, we can make a difference. Providing access to education and healthcare is at the heart of our mission. Stand with us in bringing hope and relief to those facing difficult times. Your generosity fuels our commitment to serving humanity with love and care. Every contribution counts, bringing us closer to our goal. Together, we can make the world a better place for those in need
        </p>


        <!-- Stats Section -->
        <div class="row g-3 my-3 mt-3">
        <?php
                // Fetch all data from temples table with limit and offset
                $select = "SELECT * FROM `counter` ORDER BY index_id DESC";
                $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);

                // Check if any rows are returned
                if (mysqli_num_rows($SQL_STATEMENT) > 0) {
                  while ($Row = mysqli_fetch_assoc($SQL_STATEMENT)) {

                    $title = $Row['title'];
                    $count = $Row['count'];
                  
                ?>
          <div class="col-6 col-md-3 d-flex">
            <div class="stats-box w-100 h-100 text-center">
              <h3 class="stat-number" data-target="<?php echo $count?>">0</h3>
              <p><?php echo $title?></p>
            </div>
          </div>
                 <!-- End listing card -->
                 <?php
                  }
                } else {
                  echo "<p class='text-center'>No temples found.</p>";
                }
                ?>

       
        </div>



        <!-- <button class="btn btn-primary donate-btn mt-3 py-3 px-4" >Donate Now</button> -->
      </div>
      <div class="col-md-6">
        <div class="row g-2">
          <div class="col-12 image-gallery ">
            <img src="./image/imges25.jpg" class="img-fluid rounded" alt="Helping hands">
          </div>
          <div class="col-6">
            <img src="./image/donation-img.JPG" class="img-fluid rounded" alt="Old man reading">
          </div>
          <div class="col-6">
            <img src="./image/girl.jpg" class="img-fluid rounded" alt="Man sitting">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">

    <div class="row">
      <div class="col-12 col-md-8">
        <div class="form-container shadow-lg p-3 mb-5 bg-body rounded" id="donation-options">
          <h2 class="form-heading">DONATE FOR A SPECIAL DAY</h2>
          <form action="donation.php" id="donationForm" method="POST">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label for="donorName" class="form-label">Donor Name <span style="color:#dc3545">*</span></label>
                <input type="text" class="form-control py-3" id="donorName" name="name" placeholder="Donor Name" required>
              </div>
              <div class="col-12 col-md-6">
                <label for="donorEvent" class="form-label">Donor Event <span style="color:#dc3545">*</span></label>
                <select class="form-select p-3" id="donorEvent" name="event" required>
                  <option value="">Select Event</option>

                  <!-- 🎉 Personal Events -->
                  <option value="birthday">Birthday</option>
                  <option value="anniversary">Anniversary</option>
                  <option value="wedding">Wedding</option>
                  <option value="engagement">Engagement</option>
                  <option value="housewarming">Housewarming (Griha Pravesh)</option>
                  <option value="baby-shower">Baby Shower (Godh Bharai)</option>
                  <option value="retirement">Retirement</option>
                  <option value="naamkaran">Naamkaran (Baby Naming Ceremony)</option>
                  <option value="menstruation-ceremony">Menstruation Ceremony (Ritu Kala Samskara)</option>

                  <option value="haldi">Haldi Ceremony</option>
                  <option value="mehendi">Mehendi Function</option>
                  <option value="diwali">Diwali</option>
                  <option value="holi">Holi</option>
                  <option value="christmas">Christmas</option>
                  <option value="pongal">Pongal</option>
                  <option value="raksha-bandhan">Raksha Bandhan</option>
                  <option value="Others">Others</option>
                </select>
              </div>



              <div class="col-12 col-md-6">
                <label for="emailAddress" class="form-label">Email Address <span style="color:#dc3545">*</span></label>
                <input type="email" class="form-control py-3" name="email" id="emailAddress" placeholder="Donor Email" required>
              </div>
              <div class="col-12 col-md-6">
                <label for="phone" class="form-label">Phone <span style="color:#dc3545">*</span></label>
                <div class="input-group">
                  <span class="input-group-text py-3">🇮🇳</span>
                  <input type="tel" class="form-control py-3" id="phone" maxlength="10" name="phone" placeholder="Donor Mob No" required>
                </div>
              </div>

              <div class="col-12 col-md-6">
                <label for="serviceDate" class="form-label">Service Date</label>
                <input type="text" class="form-control py-3" id="service_date" name="service_date" value="<?php echo $service_date; ?>" readonly>
              </div>
              <div class="col-12 col-md-6">
                <label for="dob" class="form-label">Date of Birth <span style="color:#dc3545">*</span></label>
                <input type="date" class="form-control py-3" name="dob" id="dob" required>
              </div>

              <div class="col-12 col-md-6">
                <label for="meals_count" class="form-label">Meals Count</label>
                <select class="form-select p-3" id="meals_count" name="meals_count" disabled>
                  <option value="50" <?php echo $meal == 1 ? 'selected' : ''; ?>>50 Meals</option>
                  <option value="100" <?php echo $meal == 2 ? 'selected' : ''; ?>>100 Meals</option>
                  <option value="150" <?php echo $meal == 3 ? 'selected' : ''; ?>>150 Meals</option>
                </select>
                <input type="hidden" id="meals_count" name="meals_count" value="<?php echo $meal == 1 ? '50' : ($meal == 2 ? '100' : ($meal == 3 ? '150' : '')); ?>">
              </div>
              <div class="col-12 col-md-6">
                <label for="donationAmount" class="form-label">Total Amount <span style="color:#dc3545">*</span></label>
                <input type="number" class="form-control py-3" name="total_amount" value="<?php echo $total_meal_amount; ?>" id="donationAmount" placeholder="Enter Donation Amount" required readonly>
              </div>

              <div class="col-12">
                <label for="additionalComments" class="form-label">Additional Comments</label>
                <textarea class="form-control" id="comments" placeholder="Add a Comment About the Event" name="comments" rows="4"></textarea>
              </div>
            </div>

            <div class="mt-4 text-center">
              <input type="submit" id="payWithRazorpay" name="pay" class="btn btn-primary py-3 px-5"></input>
            </div>
          </form>

        </div>
      </div>



      <div class="col-12 col-md-4">
        <div class="container mt-4" style="background-color: #F6F4F1;">
          <h4 class="text-center m-4 p-3">Recent Donor</h4>

          <!-- Auto Vertical Carousel -->
          <div class="carousel-container">
            <div id="verticalCarousel" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-inner">
                <?php
                // Fetch all data from temples table with limit and offset
                $select = "SELECT * FROM `donation` ORDER BY index_id DESC";
                $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);

                // Check if any rows are returned
                if (mysqli_num_rows($SQL_STATEMENT) > 0) {
                  while ($Row = mysqli_fetch_assoc($SQL_STATEMENT)) {

                    $name = $Row['name'];
                    $amount = $Row['total_amount'];
                    $event = $Row['event'];
                    $comments = $Row['comments'];
                    // $title = $Row['title'];
                ?>
                    <!-- Slide 1 -->
                    <div class="carousel-item active">
                      <div class="cause-card">
                        <div class="cause-info">

                          <div class="cause-text">
                            <p style="font-size: 20px;"> <strong><?php echo $name ?></strong></p>

                            <br>
                            <span style="font-size: 19px;color:#17a2b8">₹ <?php echo $amount ?> /<span style="color:#000000;"><?php echo $event ?></span> </span>
                            <br>
                            <span><?php echo $comments ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End listing card -->
                <?php
                  }
                } else {
                  echo "<p class='text-center'>No temples found.</p>";
                }
                ?>


              </div>
            </div>
          </div>

          <div class="container mt-5">
            <div class="owl-carousel owl-theme carousel-banner">
              <?php
              // Fetch all data from temples table with limit and offset
              $select = "SELECT * FROM `poster` ORDER BY index_id DESC";
              $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);

              // Check if any rows are returned
              if (mysqli_num_rows($SQL_STATEMENT) > 0) {
                while ($Row = mysqli_fetch_assoc($SQL_STATEMENT)) {
                  $image = $Row['image'];
                  // $title = $Row['title'];
              ?>
                  <div class="item">
                    <img src="./app/uploads/poster/<?php echo $image; ?>" alt="Banner 1" class="img-fluid">
                  </div>
                  <!-- End listing card -->
              <?php
                }
              } else {
                echo "<p class='text-center'>No temples found.</p>";
              }
              ?>

            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
  </div>
  </section>
  <section>

    <div class="container mb-3">
      <!-- <div class="title text-center my-5">
        <h1 class="display-4 text-danger">Responsive Carousel Gallery</h1>
    </div> -->

      <div class="carousel-gallery">
        <div class="swiper-container">
          <div class="swiper-wrapper">
            <!-- Example Image Slides -->
            <?php
            // Fetch all data from temples table with limit and offset
            $select = "SELECT * FROM `gallery` ORDER BY index_id DESC";
            $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);

            // Check if any rows are returned
            if (mysqli_num_rows($SQL_STATEMENT) > 0) {
              while ($Row = mysqli_fetch_assoc($SQL_STATEMENT)) {
                $gallery_image = $Row['gallery_image'];
                // $title = $Row['title'];
            ?>
                <div class="swiper-slide">
                  <a href="./app/uploads/gallery/<?php echo $gallery_image; ?>" data-fancybox="gallery">
                    <div class="image" style="background-image: url('./app/uploads/gallery/<?php echo $gallery_image; ?>');"></div>
                  </a>
                </div>
                <!-- End listing card -->
            <?php
              }
            } else {
              echo "<p class='text-center'>No temples found.</p>";
            }
            ?>

          </div>

          <!-- Pagination -->
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
  </section>
  <!-- Dark Overlay Background -->
  <div id="modalOverlay" class="modal-overlay"></div>

  <!-- Thank You Modal -->
  <div id="thankYouModal" class="modal fade" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center p-4">
        <img src="./image/paymet.png" alt="Payment Success" class="mb-3" style="width: 100px;">
        <h1 class="text-success">Payment Successful!</h1>
        <p>Your payment has been processed successfully. Thank you for your support!</p>
      </div>
    </div>
  </div>


  <footer class="footer">
    <div class="container">
      <div class="row ">
        <div class="col-md-6 text-start">
          <img
            src="./image/footer-logo.png"
            alt="Logo"

            class="footer-logo"
            width="290px" ;
            height="100px" ; />

        </div>

        <div class="col-md-6 text-start">
    <h5 class="SUPPORTERS">OUR SUPPORTERS</h5>
    <div class="supporters d-flex flex-wrap justify-content-center">
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com1.png" class="img-fluid" alt="Karpagam University">
        </div>
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com2.png" class="img-fluid" alt="Sri Krishna Institutions">
        </div>
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com3.png" class="img-fluid" alt="Sankara Institutions">
        </div>
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com4.png" class="img-fluid" alt="SSVM World School">
        </div>
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com5.png" class="img-fluid" alt="Charity Foundation">
        </div>
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com6.png" class="img-fluid" alt="Rathinam Group">
        </div>
    </div>
</div>
        <div class="col-md-6 text-start">

          <p class="footer-content">
            Charity is not just an action; it's a shared commitment to
            uplifting those in need, creating a symphony of hope that
            resonates across every corner of our global community.
          </p>
          <ul class="list-unstyled ">
            <div class="footer-list">
              <li>🔵 Choose your favourite cause</li>
              <li>🔴 Be a part of our cause</li>
            </div>
            <div class="footer-list">
              <li>🟡 Register to our website!</li>
              <li>🟢 Stay tuned about cause</li>
            </div>
          </ul>

        </div>
        <div class="col-md-6 text-start mt-4">
          <h5>NEWSLETTER</h5>
          <div class="newsletter d-flex flex-column flex-md-row align-items-center">
            <input type="email" placeholder="Email" class="form-control me-md-2" />
            <button class="send">SEND</button>
          </div>
        </div>


      </div>


    </div>

  </footer>

  <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center py-3" style="background-color: #E7E7E7;
    ;">

    <div class="ms-5">
      <p class="mb-0 copy">
        Chandrans Yuva Foundation &copy; 2017 - 2024. Powered by Limitless 360
      </p>
    </div>
    <?php
    // Fetch all data from cab_tour_packages table
    $select = "SELECT * FROM business_setting";

    $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);

    // Check if the query returns any rows
    if (mysqli_num_rows($SQL_STATEMENT) > 0) {
        while ($Row = mysqli_fetch_assoc($SQL_STATEMENT)) {

    ?>
      <div class="social-icons mt-2 mt-md-0 me-5">
          <a href="<?php echo $Row['fb_link']; ?>" class="me-3"><i class="fab fa-facebook"></i></a>
          <a href="<?php echo $Row['instagram']; ?>"><i class="fab fa-instagram"></i></a>
      </div>
   
      <?php
        }
    } else {
        echo "";
    }
    ?>


  </div>

  <!-- ✅ Razorpay Payment Script -->
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script>
    // Global variable to save form data for later use
    let formDataGlobal = null;
    let paymentMethod = "";

    // Intercept the form submission
    document.getElementById('donationForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const form = e.target;
      let formData = new FormData(form);
      formDataGlobal = formData;
      formData.append("action", "create_order");

      fetch('donation.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === "success") {
            let orderId = data.order_id;
            paymentMethod = data.method;

            let options = {
              "key": "<?php echo $keyId; ?>",
              "name": "Annasthalam",
              "image": "./image/logo.jpg",
              "order_id": orderId,
              "handler": function(response) {
                let completeData = new FormData();
                completeData.append("action", "complete_donation");
                console.log(completeData);
                for (let pair of formDataGlobal.entries()) {
                  if (pair[0] !== "action") {
                    completeData.append(pair[0], pair[1]);
                  }
                }
                completeData.append("order_id", orderId);
                completeData.append("payment_id", response.razorpay_payment_id);
                completeData.append("payment_method", paymentMethod);

                fetch('donation.php', {
                    method: 'POST',
                    body: completeData
                  })
                  .then(resp => resp.json())
                  .then(respData => {
                    if (respData.status === "success") {
                      // Show Thank You Modal and Overlay
                      document.getElementById('modalOverlay').style.display = 'block';
                      document.getElementById('thankYouModal').style.display = 'block';
                      document.getElementById('thankYouModal').classList.add('show');

                      setTimeout(function() {
                        window.location.href = "index.php";
                      }, 3000);
                    } else {
                      alert("Error: " + respData.message);
                    }
                  })
                  .catch(error => console.error("Error:", error));
              },
              "theme": {
                "color": "#2ac5f1"
              }
            };
            var rzp = new Razorpay(options);
            rzp.open();
          } else {
            alert("Error: " + data.message);
          }
        })
        .catch(error => console.error("Error:", error));
    });
  </script>





  <script>
    let currentIndex = 0;
    const items = document.querySelectorAll('.carousel-item');
    const totalItems = items.length;
    const carouselInner = document.querySelector('.carousel-inner');

    function updateCarousel() {
      const offset = -currentIndex * 140; // Adjust based on item height
      carouselInner.style.transform = `translateY(${offset}px)`;
    }

    function autoScroll() {
      currentIndex = (currentIndex + 1) % totalItems; // Loop back to first
      updateCarousel();
    }

    // Auto-scroll every 3 seconds
    setInterval(autoScroll, 3000);
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const counters = document.querySelectorAll(".stat-number");

      const startCounter = (counter) => {
        const target = +counter.getAttribute("data-target");
        let current = 0;
        const increment = target / 100; // Adjust speed

        const updateCounter = () => {
          current += increment;
          counter.innerText = Math.ceil(current);
          if (current < target) {
            requestAnimationFrame(updateCounter);
          } else {
            counter.innerText = target; // Ensure exact target
          }
        };
        updateCounter();
      };

      // Intersection Observer to trigger animation on scroll
      const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            startCounter(entry.target);
            observer.unobserve(entry.target); // Runs only once
          }
        });
      });

      counters.forEach((counter) => observer.observe(counter));
    });
  </script>

  <script>
    $(document).ready(function() {
      $(".owl-carousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: true,
        autoplayTimeout: 3000,
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          1000: {
            items: 1
          }
        }
      });
    });
  </script>

  <!-- Razorpay JS -->

 









  <script>
    $(document).ready(function() {
      var swiper = new Swiper('.swiper-container', {
        effect: 'slide',
        speed: 900,
        slidesPerView: 7, // 7 images visible at once
        spaceBetween: 10, // Adjust space between slides
        loop: true, // Loop enabled
        autoplay: {
          delay: 5000,
          disableOnInteraction: false
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
        breakpoints: {
          320: {
            slidesPerView: 1,
            spaceBetween: 5
          },
          425: {
            slidesPerView: 2,
            spaceBetween: 10
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 15
          },
          1024: {
            slidesPerView: 4,
            spaceBetween: 20
          },
          1280: {
            slidesPerView: 5,
            spaceBetween: 25
          },
          1600: {
            slidesPerView: 6,
            spaceBetween: 30
          },
          1920: {
            slidesPerView: 7,
            spaceBetween: 30
          }
        }
      });

      $('[data-fancybox="gallery"]').fancybox();
    });
  </script>




  <!-- Razorpay SDK -->
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- jQuery and FancyBox JS -->
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
  <!-- toaster -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>