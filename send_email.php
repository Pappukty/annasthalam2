<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'vendor/autoload.php'; // If using Composer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // SMTP Server (Gmail)
    $mail->SMTPAuth = true;
    $mail->SMTPDebug  = 2; 
    $mail->Username = 'ajaygowri3011@gmail.com'; // Your Gmail ID
    $mail->Password = 'ysisrpxxtwfltlos'; // Gmail App Password (not your Gmail password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587; 

    // Email Details
    $mail->setFrom('ajaygowri3011@gmail.com', 'annasthalam');
    $mail->addAddress('ajay@limitless360.org', 'ajay'); 
    $mail->Subject = 'gmail smtp test';
    $mail->CharSet = 'UTF-8'; // Ensure proper character encoding

    $mail->Body = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Donation Receipt - Annsthalam</title>
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
                <p><strong>Name:</strong> <span class='highlight'>Ajay $donorName</span></p>
                <p><strong>Email:</strong> <span class='highlight'>ajay@gmail.com $email</span></p>
                <p><strong>Donation Amount:</strong> <span class='highlight'>&#8377;52,565 $donationAmount</span></p>
                <p><strong>Service Date:</strong> <span class='highlight'>39.8.2026$serviceDate</span></p>
                <p><strong>Payment Method:</strong> <span class='highlight'>UPI$paymentMethod</span></p>
            </div>
    
            <!-- Thank You Message -->
            <p class='thank-you'>Thank you for your generous donation! 🙏</p>
    
            <!-- Footer -->
            <p class='footer'>Your support helps Annsthalam continue its mission of serving those in need.</p>
        </div>
    
    </body>
    </html>";
    
    $mail->isHTML(true); // Set email format to HTML

    // Send Email
    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Email sending failed. Error: {$mail->ErrorInfo}";
}
?>
