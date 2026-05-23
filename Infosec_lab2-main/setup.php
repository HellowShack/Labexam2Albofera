<?php
/**
 * Setup Script - Creates/Fixes Database Schema
 * Access this once: http://localhost/infosec_lab2-main/setup.php
 */

$conn = new mysqli("localhost", "root", "", "");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h1>Database Setup</h1>";

// Step 1: Create database
echo "<h2>Step 1: Creating Database...</h2>";
$sql = "CREATE DATABASE IF NOT EXISTS infosec_lab";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Database created/exists</p>";
} else {
    echo "<p style='color: red;'>✗ Error: " . $conn->error . "</p>";
}

// Connect to the infosec_lab database
$conn->select_db("infosec_lab");

// Step 2: Drop old tables if they exist
echo "<h2>Step 2: Preparing Tables...</h2>";
$tables = ['audit_log', 'students', 'courses', 'users'];
foreach($tables as $table) {
    $conn->query("DROP TABLE IF EXISTS `$table`");
    echo "<p>✓ Dropped $table if it existed</p>";
}

// Step 3: Create users table
echo "<h2>Step 3: Creating Tables...</h2>";
$sql = "CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `is_active` int(11) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Users table created</p>";
} else {
    echo "<p style='color: red;'>✗ Error: " . $conn->error . "</p>";
}

// Step 4: Create courses table
$sql = "CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_code` varchar(50) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_description` varchar(255),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `course_code` (`course_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Courses table created</p>";
} else {
    echo "<p style='color: red;'>✗ Error: " . $conn->error . "</p>";
}

// Step 5: Create students table
$sql = "CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enrollment_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`),
  UNIQUE KEY `email` (`email`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `students_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Students table created</p>";
} else {
    echo "<p style='color: red;'>✗ Error: " . $conn->error . "</p>";
}

// Step 6: Create audit_log table
$sql = "CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50),
  `entity_id` int(11),
  `changes` longtext,
  `ip_address` varchar(45),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Audit log table created</p>";
} else {
    echo "<p style='color: red;'>✗ Error: " . $conn->error . "</p>";
}

// Step 7: Insert admin user
echo "<h2>Step 4: Inserting Admin User...</h2>";
$sql = "INSERT INTO `users` (`id`, `username`, `password`, `role`, `is_active`) VALUES
(1, 'admin', '\$2y\$10\$eImiTXuWVxfaHNYY8KwkCOYDlH8z7IVS0ZeYQ2wrIWFexIiUm92Fm', 'admin', 1)";

if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Admin user inserted (username: admin, password: admin123)</p>";
} else {
    echo "<p style='color: red;'>✗ Error: " . $conn->error . "</p>";
}

// Step 8: Insert sample course
echo "<h2>Step 5: Inserting Sample Course...</h2>";
$sql = "INSERT INTO `courses` (`course_id`, `course_code`, `course_name`, `course_description`) VALUES
(1, 'CS101', 'Introduction to Computer Science', 'Fundamentals of computer science and programming')";

if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Sample course inserted</p>";
} else {
    echo "<p style='color: red;'>✗ Error: " . $conn->error . "</p>";
}

$conn->close();

echo "<h2 style='color: green;'>✓ Setup Complete!</h2>";
echo "<p><strong>You can now login with:</strong></p>";
echo "<ul>";
echo "<li><strong>Username:</strong> admin</li>";
echo "<li><strong>Password:</strong> admin123</li>";
echo "</ul>";
echo "<p><a href='login.php' style='font-size: 18px; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Go to Login</a></p>";
?>
