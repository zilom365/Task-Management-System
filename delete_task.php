<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = (int) $_GET['id'];

mysqli_query($conn, "DELETE FROM tasks WHERE id='$id' AND user_id='$user_id'");

header("Location: index.php");
exit();