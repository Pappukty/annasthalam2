<?php
header("Content-Type: application/json");
include './app/class/databaseConn.php';

// Ensure Razorpay SDK is included
include('razorpay/Razorpay.php');

use Razorpay\Api\Api;

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize database connection
$DatabaseCo = new DatabaseConn();

// Razorpay API credentials

$razorpayKey = "rzp_test_geqUM0Pl34yBo6";
$keySecret = "ar3OtUJd6ZRwDCvy5vWkcLI0";
// Retrieve and sanitize input data
$donorName = trim($_POST['name'] ?? '');
$donorEvent = trim($_POST['event'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$serviceDate = trim($_POST['service_date'] ?? NULL);
$dob = trim($_POST['dob'] ?? '');
$foodCount = intval($_POST['meals_count'] ?? 0);
$donationAmount = floatval($_POST['amount'] ?? 0);
$additionalComments = trim($_POST['comments'] ?? '');
$paymentId = trim($_POST['payment_id'] ?? '');
$order_id = $_POST['razorpay_order_id'];
$signature = $_POST['razorpay_signature'];
$generated_signature = hash_hmac('sha256', $order_id . "|" . $payment_id, $api_secret);
// Check if payment ID is provided
if (empty($paymentId)) {
    echo json_encode(["status" => "error", "message" => "Invalid payment ID"]);
    exit;
}

try {
    // Verify payment with Razorpay API
    $api = new Api($razorpayKey, $keySecret);
    $payment = $api->payment->fetch($paymentId);
    var_dump($payment);



    if ($generated_signature === $signature) {
        // Prepare SQL query to insert data
        $sql = "INSERT INTO donation 
                (name, event, email, phone, service_date, dob, meals_count, total_amount, comments, status, payment_id,order_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?)";

        $stmt = $DatabaseCo->dbLink->prepare($sql);

        if (!$stmt) {
            echo json_encode(["status" => "error", "message" => "Prepare failed: " . $DatabaseCo->dbLink->error]);
            exit;
        }

        $paymentStatus = "success"; // Since Razorpay confirms payment is captured

$stmt->bind_param("ssssssidsis", $donorName, $donorEvent, $email, $phone, $serviceDate, $dob, $foodCount, $donationAmount, $additionalComments, $paymentStatus, $paymentId,$order_id);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "Donation recorded successfully",
                "payment_status" => "captured",
                "payment_id" => $paymentId
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Database error: " . $stmt->error
            ];
        }

        $stmt->close();
    } else {
        $response = [
            "status" => "failed",
            "message" => "Payment not captured",
            "payment_status" => $payment->status
        ];
    }
} catch (Exception $e) {
    $response = [
        "status" => "error",
        "message" => "Payment verification failed: " . $e->getMessage()
    ];
}

$DatabaseCo->dbLink->close();
echo json_encode($response);
?>
