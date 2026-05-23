<?php
/**
 * Password Reset and Diagnostic Tool
 */

echo "<h1>Admin Password Fix</h1>";

$conn = new mysqli("localhost", "root", "", "infosec_lab");

if ($conn->connect_error) {
    die("❌ Cannot connect to database: " . $conn->connect_error);
}

echo "<h2>Current Admin User Status:</h2>";

// Check current user
$result = $conn->query("SELECT id, username, password FROM users WHERE username='admin'");

if($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "<p>✓ Admin user exists (ID: " . $user['id'] . ")</p>";
    echo "<p>Current password hash:</p>";
    echo "<pre style='background: #f4f4f4; padding: 10px; word-break: break-all;'>" . htmlspecialchars($user['password']) . "</pre>";
    
    // Test if current hash works
    if(password_verify("admin123", $user['password'])) {
        echo "<p style='color: green;'><strong>✓ Password hash is CORRECT for 'admin123'</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>✗ Password hash is INCORRECT - fixing now...</strong></p>";
        
        // Generate new hash
        $new_hash = password_hash("admin123", PASSWORD_BCRYPT, ['cost' => 10]);
        echo "<p>New password hash: </p>";
        echo "<pre style='background: #f4f4f4; padding: 10px; word-break: break-all;'>" . htmlspecialchars($new_hash) . "</pre>";
        
        // Update database
        $update_sql = "UPDATE users SET password = '" . $conn->real_escape_string($new_hash) . "' WHERE username='admin'";
        
        if($conn->query($update_sql)) {
            echo "<p style='color: green;'><strong>✓ Password hash updated successfully!</strong></p>";
            
            // Verify
            $result = $conn->query("SELECT password FROM users WHERE username='admin'");
            $row = $result->fetch_assoc();
            if(password_verify("admin123", $row['password'])) {
                echo "<p style='color: green;'><strong>✓ Verification successful - password is now 'admin123'</strong></p>";
            } else {
                echo "<p style='color: red;'><strong>✗ Verification failed</strong></p>";
            }
        } else {
            echo "<p style='color: red;'><strong>✗ Update failed: " . $conn->error . "</strong></p>";
        }
    }
} else {
    echo "<p style='color: red;'><strong>✗ Admin user not found - creating it now...</strong></p>";
    
    $new_hash = password_hash("admin123", PASSWORD_BCRYPT, ['cost' => 10]);
    
    // Delete existing admin if any
    $conn->query("DELETE FROM users WHERE username='admin'");
    
    // Insert new admin
    $insert_sql = "INSERT INTO users (username, password, role, is_active) VALUES ('admin', '" . $conn->real_escape_string($new_hash) . "', 'admin', 1)";
    
    if($conn->query($insert_sql)) {
        echo "<p style='color: green;'><strong>✓ Admin user created successfully!</strong></p>";
        echo "<p>Username: <strong>admin</strong></p>";
        echo "<p>Password: <strong>admin123</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>✗ Failed to create admin: " . $conn->error . "</strong></p>";
    }
}

$conn->close();

echo "<hr>";
echo "<h2>Next Step:</h2>";
echo "<p><a href='login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;'>Go back to Login and try again</a></p>";
echo "<p style='color: #999; font-size: 12px; margin-top: 20px;'>Use: <strong>admin</strong> / <strong>admin123</strong></p>";
?>
