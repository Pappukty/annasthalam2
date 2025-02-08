<?php
require './Razorpay-sdk/razorpay/Razorpay.php';

use Razorpay\Api\Api;
$api_key = "rzp_test_geqUM0Pl34yBo6";
$api_secret = "ar3OtUJd6ZRwDCvy5vWkcLI0";

$api = new Api($api_key, $api_secret);

$payment_id = $_POST['razorpay_payment_id'];
$order_id = $_POST['razorpay_order_id'];
$signature = $_POST['razorpay_signature'];

$generated_signature = hash_hmac('sha256', $order_id . "|" . $payment_id, $api_secret);

if ($generated_signature === $signature) {
    echo "Payment Verified Successfully!";
} else {
    echo "Payment Verification Failed!";
}
?>
