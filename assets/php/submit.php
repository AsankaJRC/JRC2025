<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed");
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$message = trim($_POST["message"] ?? "");

// Basic validation
if ($name === "" || $email === "" || $message === "") {
    exit("All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Invalid email address.");
}

// Sanitise
$safeName = htmlspecialchars($name, ENT_QUOTES, "UTF-8");
$safeMessage = htmlspecialchars($message, ENT_QUOTES, "UTF-8");

// Email example
$to = "admin@example.com";
$subject = "New contact form submission";
$body = "Name: $safeName\nEmail: $email\n\nMessage:\n$safeMessage";
$headers = "From: no-reply@example.com\r\nReply-To: $email";

mail($to, $subject, $body, $headers);

// Success response
echo "Thanks. Your message has been sent.";
