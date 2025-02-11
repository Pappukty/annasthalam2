<?php
include_once './class/databaseConn.php';
include_once './class/fileUploader.php';
$DatabaseCo = new DatabaseConn();
include_once './class/XssClean.php';
$xssClean = new xssClean();


// Enable error reporting (Disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = 1; // Static admin ID
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // Optional

    // Validate input
    if (empty($username) || empty($email)) {
        die("Username and Email are required.");
    }

    // Start update query
    $query = "UPDATE admin SET username = ?, email = ?";
    $params = [$username, $email];

    // If password is provided, update it securely
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query .= ", password = ?";
        array_push($params, $hashedPassword);
    }

    $query .= " WHERE id = ?";
    array_push($params, $id);

    // Prepare and execute statement
    $stmt = $DatabaseCo->dbLink->prepare($query);
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
    if ($stmt->execute()) {
        header('Location: profile.php'); 
    } else {
        echo "<script>alert('Error updating profile.'); window.history.back();</script>";
    }
}
?>
