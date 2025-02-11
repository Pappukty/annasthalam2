<?php
include_once './class/databaseConn.php';
include_once './class/fileUploader.php';
$DatabaseCo = new DatabaseConn();
include_once './class/XssClean.php';
$xssClean = new xssClean();


error_reporting(1);
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $email = $xssClean->clean_input($_POST['email']);
    $phone_number = $xssClean->clean_input($_POST['phone_number']);
    $address = $xssClean->clean_input(trim(str_replace(["\n", "\r\n"], ' ', $_POST['address'])));
    $fb_link = $xssClean->clean_input($_POST['fb_link']);
    $instagram = $xssClean->clean_input($_POST['instagram']);
 

    // Check if a record already exists with the id = 1
    $query = "SELECT COUNT(*) as count FROM business_setting WHERE index_id = 1";
    $result = $DatabaseCo->dbLink->query($query);

    if ($result === false) {
        $_SESSION['error_message'] = 'Database query failed';
        header('Location: business_setting.php'); // Redirect to the settings page
        exit;
    }

    $row = $result->fetch_assoc();
    $operation = '';

    if ($row['count'] > 0) {
        // Record exists, perform an UPDATE
        $stmt = $DatabaseCo->dbLink->prepare("UPDATE business_setting SET email = ?, phone_number = ?, address = ?, fb_link = ?, instagram = ? WHERE index_id = 1");
        $stmt->bind_param('sssss', $email, $phone_number, $address, $fb_link, $instagram);
        $operation = 'update';
    } else {
        // Record does not exist, perform an INSERT
        $stmt = $DatabaseCo->dbLink->prepare("INSERT INTO business_setting (email, phone_number, address, fb_link, instagram) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $email, $phone_number, $address, $fb_link, $instagram);
        $operation = 'insert';
    }

    // Execute the statement and check if it was successful
    if ($stmt->execute()) {
        // Set a success message based on the operation
        $_SESSION['success_message'] = " successfully " . ($operation === 'update' ? "updated" : "inserted") . "!";
    } else {
        // Set an error message if the operation failed
        $_SESSION['error_message'] = "Failed to " . ($operation === 'update' ? "update" : "insert") . " data: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Redirect to the settings page after operation
    header('Location: setting.php'); // Ensure redirection occurs after processing
    exit;
}


?>