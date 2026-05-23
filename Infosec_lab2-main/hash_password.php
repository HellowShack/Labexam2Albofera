<?php
/**
 * Password Hash Utility
 * Use this script to generate secure password hashes for user creation
 * 
 * Usage: Access this file via browser and enter password to hash
 */

// Only allow access from localhost in production
if($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== 'localhost') {
    die("Access denied. This utility can only be accessed from localhost.");
}

$hashed_password = '';
$plain_password = '';
$error = '';

if(isset($_POST['generate_hash'])){
    if(empty($_POST['password'])){
        $error = "Please enter a password to hash.";
    } else {
        $plain_password = $_POST['password'];
        
        // Validate password strength
        if(strlen($plain_password) < 8){
            $error = "Password must be at least 8 characters long.";
        } else {
            // Generate bcrypt hash with cost factor 10
            $hashed_password = password_hash($plain_password, PASSWORD_BCRYPT, ['cost' => 10]);
        }
    }
}

if(isset($_POST['verify_hash'])){
    if(empty($_POST['plain_password']) || empty($_POST['hashed_password'])){
        $error = "Please enter both password and hash to verify.";
    } else {
        $plain = $_POST['plain_password'];
        $hash = $_POST['hashed_password'];
        
        if(password_verify($plain, $hash)){
            $hashed_password = "✓ Password hash is VALID";
        } else {
            $error = "✗ Password does NOT match the hash.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Hash Utility</title>
    <style>
        * { font-family: Arial, sans-serif; }
        body { margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        h2 { color: #666; margin-top: 30px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .section { margin-bottom: 30px; }
        label { display: block; margin-top: 15px; font-weight: bold; color: #333; }
        input[type="password"], input[type="text"], textarea { 
            width: 100%; 
            padding: 10px; 
            margin-top: 5px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            font-family: monospace;
            box-sizing: border-box;
        }
        textarea { min-height: 100px; }
        button { 
            background-color: #007bff; 
            color: white; 
            padding: 10px 20px; 
            margin-top: 10px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 14px;
        }
        button:hover { background-color: #0056b3; }
        .result { 
            margin-top: 15px; 
            padding: 15px; 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
            border-radius: 4px; 
        }
        .error { 
            margin-top: 15px; 
            padding: 15px; 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
            border-radius: 4px; 
        }
        .info { 
            background-color: #e2e3e5; 
            color: #383d41; 
            padding: 15px; 
            border-radius: 4px; 
            margin: 15px 0;
        }
        code { background-color: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        .warning { color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>

<div class="container">
    <h1>🔐 Password Hash Utility</h1>
    
    <div class="warning">
        <strong>⚠️ Security Notice:</strong> This utility should only be accessible from localhost. 
        Never expose this file on a public server.
    </div>

    <!-- Section 1: Generate Hash -->
    <h2>1. Generate Password Hash</h2>
    <div class="section">
        <p>Enter a plain text password to generate a secure bcrypt hash. This hash can be stored in the database.</p>
        
        <form method="POST">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter password (min 8 chars)" required>
            
            <button type="submit" name="generate_hash">Generate Hash</button>
        </form>
        
        <?php if($error): ?>
            <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        
        <?php if($hashed_password && isset($_POST['generate_hash'])): ?>
            <div class="result">
                <strong>Generated Hash:</strong><br>
                <textarea readonly><?php echo htmlspecialchars($hashed_password, ENT_QUOTES, 'UTF-8'); ?></textarea>
                <p><small>Copy this hash to store in the database for user: <strong><?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'); ?></strong></small></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Section 2: Verify Hash -->
    <h2>2. Verify Password Against Hash</h2>
    <div class="section">
        <p>Enter a plain text password and its hash to verify if they match.</p>
        
        <form method="POST">
            <label for="plain_password">Plain Password:</label>
            <input type="password" id="plain_password" name="plain_password" placeholder="Enter plain password">
            
            <label for="hashed_password">Hash (bcrypt):</label>
            <textarea id="hashed_password" name="hashed_password" placeholder="Paste the bcrypt hash here"></textarea>
            
            <button type="submit" name="verify_hash">Verify Hash</button>
        </form>
        
        <?php if(isset($_POST['verify_hash'])): ?>
            <?php if(strpos($hashed_password, '✓') !== false): ?>
                <div class="result"><?php echo $hashed_password; ?></div>
            <?php elseif($error): ?>
                <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Section 3: SQL Insert Template -->
    <h2>3. SQL Insert Template</h2>
    <div class="section">
        <p>Use this template to insert a new user into the database:</p>
        
        <div class="info">
            <strong>SQL Command:</strong><br>
            <code>INSERT INTO users (username, password, role, is_active) VALUES ('admin', 'PASTE_HASH_HERE', 'admin', 1);</code>
        </div>
    </div>

    <!-- Section 4: Database Configuration -->
    <h2>4. Database Setup Commands</h2>
    <div class="section">
        <p>Use these SQL commands to update user password in existing database:</p>
        
        <div class="info">
            <strong>Update existing user password:</strong><br>
            <code>UPDATE users SET password='PASTE_HASH_HERE' WHERE username='admin';</code>
        </div>
    </div>

    <!-- Section 5: Password Requirements -->
    <h2>5. Password Requirements</h2>
    <div class="section">
        <ul>
            <li><strong>Minimum length:</strong> 8 characters</li>
            <li><strong>Recommended:</strong> Mix of uppercase, lowercase, numbers, and special characters</li>
            <li><strong>Hash algorithm:</strong> bcrypt (PASSWORD_BCRYPT)</li>
            <li><strong>Cost factor:</strong> 10 (adjustable for performance)</li>
        </ul>
        
        <p><strong>Example strong passwords:</strong></p>
        <ul>
            <li>MyP@ssw0rd2024</li>
            <li>SecureAd#min99</li>
            <li>C0mplexP@ss!234</li>
        </ul>
    </div>

    <!-- Section 6: Implementation Guide -->
    <h2>6. Implementation in Code</h2>
    <div class="section">
        <p><strong>Hashing a password during registration:</strong></p>
        <pre style="background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto;">
$password = $_POST['password'];
$hashed = password_hash($password, PASSWORD_BCRYPT);
// Store $hashed in database
        </pre>
        
        <p><strong>Verifying a password during login:</strong></p>
        <pre style="background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto;">
$user = /* fetch from database */;
if(password_verify($_POST['password'], $user['password'])) {
    // Login successful
} else {
    // Login failed
}
        </pre>
    </div>

    <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">
    
    <p style="color: #999; font-size: 12px; text-align: center;">
        Password Hashing Utility | Created for InfoSec Lab | Never commit this file to version control
    </p>
</div>

</body>
</html>
