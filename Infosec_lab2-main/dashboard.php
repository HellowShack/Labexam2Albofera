<?php
include("db.php");

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Student Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .container { max-width: 1000px; margin: 20px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .user-info { background-color: #e8f4f8; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .nav-links { margin: 20px 0; }
        .nav-links a { background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; margin-right: 10px; border-radius: 4px; display: inline-block; }
        .nav-links a:hover { background-color: #0056b3; }
        .logout { background-color: #dc3545; }
        .logout:hover { background-color: #c82333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        tr:hover { background-color: #f9f9f9; }
        .delete-btn { background-color: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .delete-btn:hover { background-color: #c82333; }
    </style>
</head>
<body>

<div class="container">
    <h1>Student Management System</h1>
    
    <div class="user-info">
        <strong>Welcome, <?php echo $username; ?>!</strong>
    </div>
    
    <div class="nav-links">
        <a href="add_student.php">+ Add New Student</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <h2>Student List</h2>

    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT id, student_id, fullname, email FROM students ORDER BY created_at DESC");
            
            if($result && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td><a href='delete_student.php?id=" . htmlspecialchars($row['id']) . "' class='delete-btn' onclick='return confirm(\"Delete this student?\")'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align: center;'>No students found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
