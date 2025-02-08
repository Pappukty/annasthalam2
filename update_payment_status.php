<?php
require 'razorpay/Razorpay.php';
use Razorpay\Api\Api;
include_once './app/class/databaseConn.php';
$DatabaseCo = new DatabaseConn();

// Razorpay credentials
$keyId = "rzp_test_geqUM0Pl34yBo6";
$keySecret = "ar3OtUJd6ZRwDCvy5vWkcLI0";
$api = new Api($keyId, $keySecret);

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['order_id']) && !empty($data['payment_id']) && !empty($data['signature'])) {
    try {
        $attributes = [
            'razorpay_order_id' => $data['order_id'],
            'razorpay_payment_id' => $data['payment_id'],
            'razorpay_signature' => $data['signature']
        ];

        $api->utility->verifyPaymentSignature($attributes);

        $sql = "UPDATE donation SET status = 'completed', payment_id = ? WHERE order_id = ?";
        $stmt = $DatabaseCo->dbLink->prepare($sql);
        $stmt->bind_param("ss", $data['payment_id'], $data['order_id']);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Payment updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database update failed"]);
        }
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Verification failed: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid payment data"]);
}
?>
