<?php
/**
 * COMPLETE RESET - Creates brand new admin account
 */

$conn = new mysqli("localhost", "root", "", "infosec_lab");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

echo "<h1>Complete Reset - Creating Admin Account</h1>";

// Step 1: Generate new password hash
$password = "admin123";
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "<p>Password: <strong>admin123</strong></p>";
echo "<p>New Hash: <code>" . $hash . "</code></p>";

// Step 2: Delete old admin
$result = $conn->query("DELETE FROM users WHERE username = 'admin'");
echo "<p>✓ Deleted old admin user (if existed)</p>";

// Step 3: Insert new admin with the new hash
$query = "INSERT INTO users (username, password, role, is_active) VALUES ('admin', '" . $conn->real_escape_string($hash) . "', 'admin', 1)";

if ($conn->query($query)) {
    echo "<p style='color: green;'><strong>✓ New admin user created!</strong></p>";
} else {
    echo "<p style='color: red;'><strong>✗ Error inserting admin: " . $conn->error . "</strong></p>";
    die();
}

// Step 4: Verify it works
$result = $conn->query("SELECT id, password FROM users WHERE username = 'admin'");
$user = $result->fetch_assoc();

if (password_verify($password, $user['password'])) {
    echo "<p style='color: green;'><strong>✓ Password verification SUCCESS!</strong></p>";
    echo "<p>You can now login with:</p>";
    echo "<ul>";
    echo "<li>Username: <strong>admin</strong></li>";
    echo "<li>Password: <strong>admin123</strong></li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'><strong>✗ Password verification FAILED</strong></p>";
}

$conn->close();

echo "<hr>";
echo "<p><a href='login.php' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>→ Go to Login</a></p>";
?>
