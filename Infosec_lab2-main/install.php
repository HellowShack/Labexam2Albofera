<?php
/**
 * Complete Diagnostic and Setup Tool
 * This will identify and fix all database issues
 */

echo "<pre style='background: #f4f4f4; padding: 20px; border-radius: 5px; font-family: monospace;'>";

// Test 1: Connection
echo "=== TESTING DATABASE CONNECTION ===\n\n";
$conn = @new mysqli("localhost", "root", "");
if ($conn->connect_error) {
    die("❌ FATAL: Cannot connect to MySQL\nError: " . $conn->connect_error . "\n\nMake sure MySQL is running!");
}
echo "✓ Connected to MySQL server\n\n";

// Test 2: Database existence
echo "=== CHECKING DATABASE ===\n\n";
$result = $conn->query("SHOW DATABASES LIKE 'infosec_lab'");
if($result && $result->num_rows > 0) {
    echo "✓ Database 'infosec_lab' exists\n";
    $conn->select_db("infosec_lab");
} else {
    echo "⚠ Database 'infosec_lab' does not exist - Creating it...\n";
    $conn->query("CREATE DATABASE infosec_lab DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $conn->select_db("infosec_lab");
    echo "✓ Database created\n";
}
echo "\n";

// Test 3: Check tables
echo "=== CHECKING TABLES ===\n\n";
$result = $conn->query("SHOW TABLES");
$tables = [];
while($row = $result->fetch_array(MYSQLI_NUM)) {
    $tables[] = $row[0];
}

if(in_array('users', $tables)) {
    echo "✓ Users table exists\n";
    $result = $conn->query("SHOW COLUMNS FROM users");
    $columns = [];
    while($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    echo "  Columns: " . implode(", ", $columns) . "\n";
} else {
    echo "✗ Users table does NOT exist\n";
}

if(in_array('courses', $tables)) {
    echo "✓ Courses table exists\n";
} else {
    echo "✗ Courses table does NOT exist\n";
}

if(in_array('students', $tables)) {
    echo "✓ Students table exists\n";
} else {
    echo "✗ Students table does NOT exist\n";
}

if(in_array('audit_log', $tables)) {
    echo "✓ Audit log table exists\n";
} else {
    echo "✗ Audit log table does NOT exist\n";
}
echo "\n";

// Now rebuild everything from scratch
echo "=== REBUILDING DATABASE ===\n\n";

// Drop all tables
$tables_to_drop = ['audit_log', 'students', 'courses', 'users'];
foreach($tables_to_drop as $table) {
    $conn->query("DROP TABLE IF EXISTS `$table`");
}
echo "✓ Dropped existing tables\n";

// Create users table
$sql = "CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'user',
  is_active INT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_login TIMESTAMP NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if($conn->query($sql)) {
    echo "✓ Created users table\n";
} else {
    echo "✗ Failed to create users table: " . $conn->error . "\n";
}

// Create courses table
$sql = "CREATE TABLE courses (
  course_id INT AUTO_INCREMENT PRIMARY KEY,
  course_code VARCHAR(50) NOT NULL UNIQUE,
  course_name VARCHAR(100) NOT NULL,
  course_description VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if($conn->query($sql)) {
    echo "✓ Created courses table\n";
} else {
    echo "✗ Failed to create courses table: " . $conn->error . "\n";
}

// Create students table
$sql = "CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id VARCHAR(50) NOT NULL UNIQUE,
  fullname VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  course_id INT NOT NULL,
  enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY course_id (course_id),
  CONSTRAINT fk_course FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE RESTRICT
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if($conn->query($sql)) {
    echo "✓ Created students table\n";
} else {
    echo "✗ Failed to create students table: " . $conn->error . "\n";
}

// Create audit_log table
$sql = "CREATE TABLE audit_log (
  log_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  action VARCHAR(100) NOT NULL,
  entity_type VARCHAR(50),
  entity_id INT,
  changes LONGTEXT,
  ip_address VARCHAR(45),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY user_id (user_id),
  KEY created_at (created_at),
  CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if($conn->query($sql)) {
    echo "✓ Created audit_log table\n";
} else {
    echo "✗ Failed to create audit_log table: " . $conn->error . "\n";
}

echo "\n";

// Insert data
echo "=== INSERTING DATA ===\n\n";

// Insert admin user with bcrypt hash of "admin123"
$sql = "INSERT INTO users (username, password, role, is_active) VALUES ('admin', '\$2y\$10\$eImiTXuWVxfaHNYY8KwkCOYDlH8z7IVS0ZeYQ2wrIWFexIiUm92Fm', 'admin', 1)";
if($conn->query($sql)) {
    echo "✓ Inserted admin user\n";
} else {
    echo "✗ Failed to insert admin user: " . $conn->error . "\n";
}

// Insert sample course
$sql = "INSERT INTO courses (course_code, course_name, course_description) VALUES ('CS101', 'Introduction to Computer Science', 'Fundamentals of computer science and programming')";
if($conn->query($sql)) {
    echo "✓ Inserted sample course\n";
} else {
    echo "✗ Failed to insert sample course: " . $conn->error . "\n";
}

echo "\n";

// Verify
echo "=== FINAL VERIFICATION ===\n\n";

$result = $conn->query("SELECT id, username, role, is_active FROM users WHERE username='admin'");
if($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "✓ Admin user verified:\n";
    echo "  ID: " . $user['id'] . "\n";
    echo "  Username: " . $user['username'] . "\n";
    echo "  Role: " . $user['role'] . "\n";
    echo "  Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
} else {
    echo "✗ Admin user not found\n";
}

echo "\n";

$result = $conn->query("SELECT * FROM courses LIMIT 1");
if($result && $result->num_rows > 0) {
    echo "✓ Sample course verified\n";
} else {
    echo "✗ Sample course not found\n";
}

$conn->close();

echo "\n";
echo "====================================\n";
echo "✓ SETUP COMPLETE!\n";
echo "====================================\n\n";
echo "Login Credentials:\n";
echo "  Username: admin\n";
echo "  Password: admin123\n\n";
echo "<a href='login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>→ Go to Login Page</a>\n";

echo "</pre>";
?>
