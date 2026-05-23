<?php
include("db.php");

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if(isset($_POST['add'])){
    $student_id = isset($_POST['student_id']) ? trim($_POST['student_id']) : '';
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    if(empty($student_id) || empty($fullname) || empty($email)){
        $error = "All fields are required.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Invalid email format.";
    } else {
        // Check if student_id already exists
        $check = $conn->query("SELECT id FROM students WHERE student_id = '" . $conn->real_escape_string($student_id) . "' LIMIT 1");
        
        if($check && $check->num_rows > 0){
            $error = "Student ID already exists.";
        } else {
            // Insert student
            $sql = "INSERT INTO students (student_id, fullname, email, course_id) VALUES ('" . 
                   $conn->real_escape_string($student_id) . "', '" . 
                   $conn->real_escape_string($fullname) . "', '" . 
                   $conn->real_escape_string($email) . "', 1)";
            
            if($conn->query($sql)){
                $success = "Student added successfully!";
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Error adding student: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .container { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .error { background-color: #f8d7da; color: #721c24; padding: 12px; margin: 15px 0; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; padding: 12px; margin: 15px 0; border-radius: 4px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #28a745; color: white; padding: 12px 30px; margin-top: 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    <h2>Add New Student</h2>
    
    <?php if($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" placeholder="STU001" required>
        
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" placeholder="John Doe" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="student@example.com" required>
        
        <button type="submit" name="add">Add Student</button>
    </form>
</div>

</body>
</html>
