<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    exit('All fields are required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('Invalid email address.');
}

$mail = new PHPMailer(true);

try {

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; 
    $mail->SMTPAuth   = true;
    $mail->Username   = 'yourgmail@gmail.com';
    $mail->Password   = 'your-app-password';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender
    $mail->setFrom('contact@jrcsoftware.com', 'Website Contact');
    $mail->addAddress('asanka@jrcsoftware.com');

    // Reply to visitor
    $mail->addReplyTo($email, $name);

    // Content
    $mail->isHTML(false);
    $mail->Subject = 'New Contact Form Submission';

    $mail->Body = 
"Name: $name
Email: $email

Message:
$message";

    $mail->send();

    echo "Message sent successfully.";

} catch (Exception $e) {
    http_response_code(500);
    echo "Mailer Error: {$mail->ErrorInfo}";
}
