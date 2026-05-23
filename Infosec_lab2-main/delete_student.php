<?php
include("db.php");

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);

if($id <= 0){
    header("Location: dashboard.php");
    exit();
}

// Delete the student
$result = $conn->query("DELETE FROM students WHERE id = " . $id);

header("Location: dashboard.php");
exit();
?>
