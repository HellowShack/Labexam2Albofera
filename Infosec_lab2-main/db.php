<?php
// Database Connection with Security Headers
session_start();

// Set security headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("Content-Security-Policy: default-src 'self'");

// Database connection using mysqli
$conn = new mysqli("localhost", "root", "", "infosec_lab");

// Check connection with error reporting
if ($conn->connect_error) {
    die("Database connection error. Please contact administrator.");
}

// Set character set to prevent encoding attacks
$conn->set_charset("utf8mb4");
?>
