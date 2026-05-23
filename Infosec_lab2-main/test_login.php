<?php
/**
 * Test Login Script - Debug Database and Authentication
 */

echo "<h2>Login Test Diagnostic</h2>";

// Test 1: Database Connection
echo "<h3>1. Testing Database Connection</h3>";
$conn = new mysqli("localhost", "root", "", "infosec_lab");

if ($conn->connect_error) {
    echo "<p style='color: red;'><strong>❌ Database Connection FAILED</strong></p>";
    echo "<p>Error: " . $conn->connect_error . "</p>";
    die();
} else {
    echo "<p style='color: green;'><strong>✓ Database Connection SUCCESS</strong></p>";
}

// Test 2: Check Users Table
echo "<h3>2. Checking Users Table</h3>";
$result = $conn->query("SELECT COUNT(*) as count FROM users");
if ($result) {
    $row = $result->fetch_assoc();
    echo "<p style='color: green;'><strong>✓ Users table exists with " . $row['count'] . " user(s)</strong></p>";
} else {
    echo "<p style='color: red;'><strong>❌ Users table query failed</strong></p>";
    echo "<p>Error: " . $conn->error . "</p>";
}

// Test 3: Get Admin User
echo "<h3>3. Checking Admin User</h3>";
$stmt = $conn->prepare("SELECT id, username, password, is_active FROM users WHERE username = ?");
$username = "admin";
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    echo "<p style='color: green;'><strong>✓ Admin user found</strong></p>";
    echo "<pre>";
    echo "Username: " . htmlspecialchars($user['username']) . "\n";
    echo "ID: " . htmlspecialchars($user['id']) . "\n";
    echo "Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
    echo "Password Hash: " . htmlspecialchars(substr($user['password'], 0, 30)) . "...\n";
    echo "</pre>";
    
    // Test 4: Verify Password
    echo "<h3>4. Testing Password Verification</h3>";
    $password = "admin123";
    
    if (password_verify($password, $user['password'])) {
        echo "<p style='color: green;'><strong>✓ Password 'admin123' is CORRECT</strong></p>";
        echo "<p>You should be able to login now!</p>";
    } else {
        echo "<p style='color: red;'><strong>❌ Password verification FAILED</strong></p>";
        echo "<p>The password 'admin123' does not match the stored hash.</p>";
        echo "<p>You need to regenerate the password hash.</p>";
        echo "<p>Use the password hashing utility at: <a href='hash_password.php'>hash_password.php</a></p>";
    }
} else {
    echo "<p style='color: red;'><strong>❌ Admin user not found</strong></p>";
    echo "<p>Please verify the database was imported correctly.</p>";
}

$conn->close();

echo "<hr>";
echo "<p><a href='login.php'>Back to Login</a></p>";
?>
