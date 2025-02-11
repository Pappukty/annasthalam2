<?php
require 'razorpay/Razorpay.php';
use Razorpay\Api\Api;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
error_reporting(2);
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include_once './app/class/databaseConn.php';

$DatabaseCo = new DatabaseConn();

// Fetch data from query string
$meal = isset($_GET['meal']) ? $_GET['meal'] : 'Not specified';
$date = isset($_GET['dates']) ? $_GET['dates'] : 'Not specified';

// Razorpay credentials
$keyId     = "rzp_test_geqUM0Pl34yBo6";
$keySecret = "ar3OtUJd6ZRwDCvy5vWkcLI0";

// Create Razorpay API instance
$api = new Api($keyId, $keySecret);

// Process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ─── STEP 1: Create Razorpay Order ─────────────────────────────
    if ($action === 'create_order') {
        $donationAmount = floatval($_POST['total_amount'] ?? 0);
        if ($donationAmount <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid donation amount"]);
            exit;
        }
        try {
            $orderData = [
                'receipt'         => rand(1000, 9999),
                'amount'          => $donationAmount * 100, // amount in paise
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

    // ─── STEP 2: Complete Donation ─────────────────────────────
    elseif ($action === 'complete_donation') {
        // Get form fields
        $donorName     = trim($_POST['name'] ?? '');
        $donorEvent    = trim($_POST['event'] ?? '');
        $email         = trim($_POST['email'] ?? '');
        $phone         = trim($_POST['phone'] ?? '');
        $serviceDate   = trim($_POST['service_date'] ?? '');
        $dob           = trim($_POST['dob'] ?? '');
        $foodCount     = trim($_POST['meals_count'] ?? '');
        $donationAmount = floatval($_POST['total_amount'] ?? 0);
        $comments      = trim($_POST['comments'] ?? '');
        $razorpayOrderId = $_POST['order_id'] ?? '';
        $paymentId     = $_POST['payment_id'] ?? '';

        if (empty($razorpayOrderId) || empty($paymentId)) {
            echo json_encode(["status" => "error", "message" => "Payment information missing"]);
            exit;
        }

        // Fetch payment details
        $payment = $api->payment->fetch($paymentId);
        $paymentMethod = $payment->method;

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

            // Send Email Invoice
            if (sendInvoiceEmail($donorName, $email, $donationAmount, $serviceDate, $paymentMethod)) {
                $response["email"] = "Donation receipt sent successfully!";
            } else {
                $response["email_status"] = "Failed to send receipt.";
            }

            echo json_encode($response);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
        }

        $stmt->close();
        exit;
    }
}

// ─── FUNCTION TO SEND EMAIL ───────────────────────────
function sendInvoiceEmail($donorName, $email, $donationAmount, $serviceDate, $paymentMethod) {
  $mail = new PHPMailer(true);

  try {
      // SMTP Server Settings (Gmail)
      $mail->isSMTP();
      $mail->SMTPDebug  = 0; // Change to 2 for debugging
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'sukuappdev@gmail.com';
      $mail->Password   = 'xachpebwrcpcleun'; // Use App Password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port       = 587; // Use 587 for TLS

      // Email Headers
      $mail->setFrom('sukuappdev@gmail.com', 'Donation Team');
      $mail->addAddress($email, $donorName); // Recipient

      // Email Subject & Body
      $mail->isHTML(true);
      $mail->Subject = "Donation Invoice - Thank You!";
      $mail->Body    = "
      <html>
      <head>
          <title>Donation Receipt</title>
          <style>
              body { font-family: Arial, sans-serif; }
              .container { width: 80%; margin: auto; padding: 20px; border: 1px solid #ddd; }
              h2 { color: #28a745; }
              p { font-size: 16px; }
          </style>
      </head>
      <body>
          <div class='container'>
              <h2>Donation Receipt</h2>
              <p><strong>Name:</strong> $donorName</p>
              <p><strong>Email:</strong> $email</p>
              <p><strong>Donation Amount:</strong> ₹$donationAmount</p>
              <p><strong>Service Date:</strong> $serviceDate</p>
              <p><strong>Payment Method:</strong> $paymentMethod</p>
              <p>Thank you for your generous donation!</p>
          </div>
      </body>
      </html>
";

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
    .hero {
      background: url('./image/banner-2.JPG') no-repeat center center/cover;
      background-position: center;
      /* Ensures background image stays centered */
      background-attachment: fixed;
      /* Keeps the background fixed while scrolling */
      height: 90vh;
      /* Full viewport height */
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      position: relative;
    }

    /* Optional: Add a semi-transparent overlay for better text visibility */
    .hero::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      /* Black overlay with 50% opacity */
      z-index: 1;
    }

    /* Ensure text stays on top of the overlay */
    .hero .container {
      position: relative;
      z-index: 2;
    }

    /* Make the heading and paragraph more responsive */
    @media (max-width: 767px) {
      .hero {
        background-size: cover;
        /* Ensure image covers smaller screens */
      }

      .hero h1 {
        font-size: 2.5rem;
        /* Smaller font size on mobile */
      }

      .hero p {
        font-size: 1rem;
        /* Adjust paragraph text size */
      }

      .hero .btn {
        font-size: 1rem;
        /* Slightly smaller button */
      }
    }

    @media (max-width: 991px) {
      .hero {
        background-size: cover;
        /* Ensures it covers the tablet screen without distortion */
      }

      .hero h1 {
        font-size: 3rem;
        /* Adjust heading size on tablets */
      }

      .hero p {
        font-size: 1.1rem;
        /* Adjust paragraph text size */
      }
    }

    .lead {
      font-size: 18px;
      font-weight: 500;
    }

    h2 {
      font-family: "Roboto", Sans-serif;
      font-size: 40px;
      font-weight: 700;
      line-height: 1em;
      letter-spacing: 1px;
      color: #000000;
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
    .stats-box {
      background-color: #ddd;
      color: black;
      padding: 10px;
      text-align: center;
      border-radius: 5px;
    }

    .mission-title {
      font-weight: bold;
      color: black;
    }

    .donate-btn {
      background-color: #17a2b8;
      border: none;
    }

    .donate-btn:hover {
      background-color: #138496;
    }

    .image-gallery img {
      width: 100%;
      height: auto;
      object-fit: cover;
    }

    .image-gallery img,
    .col-6 img {
      height: 330px;
      /* Adjust to your desired height */
      object-fit: cover;
      /* Ensures the image covers the area without distorting */
    }

    .content {
      font-size: 17px;
      line-height: 32px;
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

</head>

<body>
  <header class="hero">
    <div class="container text-center my-5">
      <h1 class="display-4 fw-bold">SUPPORT THOSE IN NEED</h1>
      <p class="lead fs-5">Your contribution today is a catalyst for impact. Donate now and be part of the journey towards creating positive transformations and brighter tomorrows. Every contribution, no matter the size, makes a meaningful difference. Join us in building a future where every act of giving sparks lasting change</p>
      <a href="#donation-options" class="btn btn-primary btn-lg mt-3">Donate Now</a>
    </div>
  </header>
  <div class="container my-5">
    <div class="row">
      <div class="col-md-6">
        <h2 class="mission-title">MAKE AN IMPACT TODAY</h2>
        <p style="text-align: justify;" class="content">
          Your support provides food, shelter, and education to those in need. Every contribution makes a difference in transforming lives.<br>
          Join our mission to create a better future for underprivileged communities.<br>
          With your generosity, we can bring hope and relief to countless individuals.<br>
          Be the reason someone smiles today. Donate now and be a part of something great.<br>
          Every meal, every blanket, every helping hand can change a life for the better.<br>
          Help us reach more people in need with your kindness and support.<br>
          We believe in the power of collective effort. Together, we can make a difference.<br>
          Providing access to education and healthcare is at the heart of our mission.<br>
          Stand with us in bringing hope and relief to those facing difficult times.<br>
          Your generosity fuels our commitment to serving humanity with love and care.<br>
          Every contribution counts and brings us closer to our goal.<br>
          Together, we can make the world a better place for those in need.
        </p>


        <!-- Stats Section -->
        <div class="row g-3 my-3 mt-3">
          <div class="col-6 col-md-3 d-flex">
            <div class="stats-box w-100 h-100 text-center">
              <h3 class="stat-number" data-target="135">0</h3>
              <p>Meals Donated</p>
            </div>
          </div>
          <div class="col-6 col-md-3 d-flex">
            <div class="stats-box w-100 h-100 text-center">
              <h3 class="stat-number" data-target="19">0</h3>
              <p>Supported Shelters</p>
            </div>
          </div>
          <div class="col-6 col-md-3 d-flex">
            <div class="stats-box w-100 h-100 text-center">
              <h3 class="stat-number" data-target="90">0</h3>
              <p>Helping Hands</p> <!-- Changed from Volunteers -->
            </div>
          </div>
          <div class="col-6 col-md-3 d-flex">
            <div class="stats-box w-100 h-100 text-center">
              <h3 class="stat-number" data-target="30">0</h3>
              <p>Successful Campaigns</p>
            </div>
          </div>
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
                <label for="donorName" class="form-label">Donor Name *</label>
                <input type="text" class="form-control py-3" id="donorName" name="name" placeholder="Donor Name" required>
              </div>
              <div class="col-12 col-md-6">
                <label for="donorEvent" class="form-label">Donor Event *</label>
                <select class="form-select p-3" id="donorEvent" name="event" required>
                  <option value="">Select Event</option>
                  <option value="food drive">Food Drive 2025</option>
                  <option value="winter clothes">Winter Clothes Donation</option>
                  <option value="school supplies">Back-to-School Supplies Campaign</option>
                  <option value="community feeding">Community Feeding Program</option>
                  <option value="medical aid">Medical Aid Fundraiser</option>
                </select>
              </div>

              <div class="col-12 col-md-6">
                <label for="emailAddress" class="form-label">Email Address *</label>
                <input type="email" class="form-control py-3" name="email" id="emailAddress" placeholder="Donor Email" required>
              </div>
              <div class="col-12 col-md-6">
                <label for="phone" class="form-label">Phone *</label>
                <div class="input-group">
                  <span class="input-group-text py-3">🇮🇳</span>
                  <input type="tel" class="form-control py-3" id="phone" maxlength="10" name="phone" placeholder="Donor Mob No" required>
                </div>
              </div>

              <div class="col-12 col-md-6">
                <label for="serviceDate" class="form-label">Service Date</label>
                <input type="date" class="form-control py-3" id="service_date" name="service_date" value="<?php echo $date; ?>" readonly>
              </div>
              <div class="col-12 col-md-6">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control py-3" name="dob" id="dob" required>
              </div>

              <div class="col-12 col-md-6">
                <label for="meals_count" class="form-label">Meals Count</label>
                <select class="form-select p-3" id="meals_count" name="meals_count" disabled>
                  <option value="50" <?php echo $meal == 1750 ? 'selected' : ''; ?>>50 Meals</option>
                  <option value="100" <?php echo $meal == 3700 ? 'selected' : ''; ?>>100 Meals</option>
                  <option value="150" <?php echo $meal == 5000 ? 'selected' : ''; ?>>150 Meals</option>
                </select>
                <input type="hidden" id="meals_count" name="meals_count" value="<?php echo $meal == 1750 ? '50' : ($meal == 3700 ? '100' : ($meal == 5000 ? '150' : '')); ?>">
              </div>
              <div class="col-12 col-md-6">
                <label for="donationAmount" class="form-label">Total Amount *</label>
                <input type="number" class="form-control py-3" name="total_amount" value="<?php echo $meal; ?>" id="donationAmount" placeholder="Enter Donation Amount" required readonly>
              </div>

              <div class="col-12">
                <label for="additionalComments" class="form-label">Additional Comments</label>
                <textarea class="form-control" id="comments" name="comments" rows="4"></textarea>
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
          <h4 class="text-center m-4 p-3">Recommend Causes</h4>

          <!-- Auto Vertical Carousel -->
          <div class="carousel-container">
            <div id="verticalCarousel" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                  <div class="cause-card">
                    <div class="cause-info">
                      <img src="./image/imges01.jpg" alt="Cause" class="img-fluid">
                      <div class="cause-text">
                        <strong>Virtual Cake Cutting</strong><br>
                        <span>₹ 4000 / celebration</span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Slide 2 -->
                <div class="carousel-item">
                  <div class="cause-card">
                    <div class="cause-info">
                      <img src="./image/imges07.jpeg" alt="Cause" class="img-fluid">
                      <div class="cause-text">
                        <strong>Sponsor a Birthday Cake</strong><br>
                        <span>₹ 1500 - 20 Children</span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Slide 3 -->
                <div class="carousel-item">
                  <div class="cause-card">
                    <div class="cause-info">
                      <img src="./image/imges20.webp" alt="Cause" class="img-fluid">
                      <div class="cause-text">
                        <strong><a href="#" class="text-primary">Feed a homeless person Thaali</a></strong><br>
                        <span>₹ 60 / Person</span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Slide 4 -->
                <div class="carousel-item">
                  <div class="cause-card">
                    <div class="cause-info">
                      <img src="./image/imges18.jpg" alt="Cause" class="img-fluid">
                      <div class="cause-text">
                        <strong>Feed a Homeless Person</strong><br>
                        <span>₹ 25 / Person</span>
                      </div>
                    </div>
                  </div>
                </div>
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
  <footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Donation Page. All rights reserved.</p>
  </footer>

  <!-- ✅ Razorpay Payment Script -->
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script>
    // Global variable to save form data for later use
    let formDataGlobal = null;
    // Global variable to save the payment method returned by the server
    let paymentMethod = "";

    // Intercept the form submission
    document.getElementById('donationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        let formData = new FormData(form);
        // Save form data globally to use later in the payment success handler
        formDataGlobal = formData;
        // Append an action flag for order creation
        formData.append("action", "create_order");

        // Step 1: Create Razorpay order by sending form data via AJAX
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
                                        toastr.options = {
                                            closeButton: true,
                                            progressBar: true,
                                            showMethod: 'fadeIn',
                                            hideMethod: 'fadeOut',
                                            timeOut: 2000,
                                        };

                                        toastr.success('Payment successful!');

                                        setTimeout(function() {
                                            location.reload(); // Reload after 2 seconds
                                        }, 2000); 
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

  <?php
  $razorpayKey = "rzp_test_geqUM0Pl34yBo6"; // Replace with your Razorpay key
  ?>









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