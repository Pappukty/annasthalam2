<?php
// Database credentials
$host = 'localhost';
$dbname = 'donation';
$user = 'root';
$password = '';

// Establish connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get data from POST request
$name = $_POST['name'] ?? '';
$amount = $_POST['amount'] ?? '';

// Validate inputs
if (empty($name) || empty($amount)) {
    echo json_encode(['message' => 'All fields are required.']);
    exit;
}

try {
    // Insert into payments table
    $stmt = $pdo->prepare("INSERT INTO payments (name, amount) VALUES (:name, :amount)");
    $stmt->execute([':name' => $name, ':amount' => $amount]);
    echo json_encode(['message' => 'Donation successfully submitted.']);
    // Redirect with member_id
    header("Location:thank_you.html");
} catch (PDOException $e) {
    echo json_encode(['message' => 'Error inserting data: ' . $e->getMessage()]);
}
?>
