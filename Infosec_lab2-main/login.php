<?php
include("db.php");

$error = '';

if(isset($_POST['login'])){
    if(empty($_POST['username']) || empty($_POST['password'])){
        $error = "Username and password are required.";
    } else {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        // Get user from database
        $result = $conn->query("SELECT id, password FROM users WHERE username = '" . $conn->real_escape_string($username) . "' LIMIT 1");
        
        if($result && $result->num_rows > 0){
            $user = $result->fetch_assoc();
            
            // Verify password
            if(password_verify($password, $user['password'])){
                // Password correct - set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'admin';
                $_SESSION['login_time'] = time();
                $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid credentials.";
            }
        } else {
            $error = "Invalid credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Secure Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .container { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        .error { background-color: #f8d7da; color: #721c24; padding: 12px; margin: 15px 0; border-radius: 4px; border: 1px solid #f5c6cb; }
        label { display: block; margin-top: 15px; font-weight: bold; color: #333; }
        input { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        button { width: 100%; padding: 12px; margin-top: 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .info { text-align: center; color: #999; font-size: 12px; margin-top: 15px; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <h2>Secure Admin Login</h2>
    
    <?php if($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" placeholder="admin" required>
        
        <label>Password:</label>
        <input type="password" name="password" placeholder="admin123" required>
        
        <button type="submit" name="login">Login</button>
    </form>
    
    <div class="info">
        <p>Demo: admin / admin123</p>
        <p><a href="fix_admin.php">Not working? Click to reset admin account</a></p>
    </div>
</div>

</body>
</html>
