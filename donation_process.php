<?php
header("Content-Type: application/json");
include_once './app/class/databaseConn.php';
include_once './app/class/fileUploader.php';

$DatabaseCo = new DatabaseConn();

// Retrieve and sanitize input data
$donorName = $_POST['name'] ?? '';
$donorEvent = $_POST['event'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$serviceDate = $_POST['service_date'] ?? NULL;
$dob = $_POST['dob'] ?? '';
$foodCount = $_POST['meals_count'] ?? 0;
$donationAmount = $_POST['amount'] ?? 0;
$additionalComments = $_POST['comments'] ?? '';

// Prepare SQL query to insert data
$sql = "INSERT INTO donation (name, event, email, phone, service_date, dob, meals_count, total_amount, comments) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $DatabaseCo->dbLink->prepare($sql);
$stmt->bind_param("ssssssids", $donorName, $donorEvent, $email, $phone, $serviceDate, $dob, $foodCount, $donationAmount, $additionalComments);

$response = [];

if ($stmt->execute()) {
    $response = [
        "status" => "success",
        "message" => "Data inserted successfully"
    ];
} else {
    $response = [
        "status" => "error",
        "message" => "Database error: " . $DatabaseCo->dbLink->error
    ];
}

$stmt->close();
$DatabaseCo->dbLink->close();

// Send response as JSON
echo json_encode($response);
?>
