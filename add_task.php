<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$title = mysqli_real_escape_string($conn, $_POST['title']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$status = mysqli_real_escape_string($conn, $_POST['status']);
$priority = mysqli_real_escape_string($conn, $_POST['priority']);
$due_date = mysqli_real_escape_string($conn, $_POST['due_date']);

mysqli_query($conn, "INSERT INTO tasks (user_id, title, description, status, priority, due_date)
VALUES ('$user_id', '$title', '$description', '$status', '$priority', '$due_date')");

header("Location: index.php");
exit();