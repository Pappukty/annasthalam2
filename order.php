<?php
// Include Razorpay SDK (if using Composer)
require 'vendor/autoload.php'; // Razorpay SDK (Install via Composer)

use Razorpay\Api\Api;

$api_key = "rzp_test_geqUM0Pl34yBo6"; // Replace with your test/live key
$api_secret = "ar3OtUJd6ZRwDCvy5vWkcLI0";

$api = new Api($api_key, $api_secret);

// Order Data
$orderData = [
    'receipt'         => 'order_' . time(),
    'amount'          => 100 * 100, // Amount in paise (INR 100)
    'currency'        => 'INR',
    'payment_capture' => 1 // Auto-capture
];

try {
    // Create the order
    $order = $api->order->create($orderData);
    echo json_encode([
        'order_id' => $order['id'],
        'amount' => $orderData['amount']
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

