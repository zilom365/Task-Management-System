<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM tasks WHERE id='$id' AND user_id='$user_id'");
$task = mysqli_fetch_assoc($result);

if (!$task) {
    die("Task not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);

    mysqli_query($conn, "UPDATE tasks SET
        title='$title',
        description='$description',
        status='$status',
        priority='$priority',
        due_date='$due_date'
        WHERE id='$id' AND user_id='$user_id'");

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="assets/css/style.css?v=10">
</head>
<body class="dashboard-page">
<div class="dashboard-layout">
    <aside class="sidebar">
        <h2 class="logo">TaskFlow</h2>
        <nav class="sidebar-menu">
            <a href="index.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="dashboard-header">
            <h1>Edit Task</h1>
            <p>Update your task details.</p>
        </div>

        <section class="content-card">
            <form method="POST">
                <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                <textarea name="description" rows="4"><?php echo htmlspecialchars($task['description']); ?></textarea>

                <div class="form-row">
                    <select name="status" required>
                        <option value="To Do" <?php if ($task['status'] == 'To Do') echo 'selected'; ?>>To Do</option>
                        <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                        <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                    </select>

                    <select name="priority" required>
                        <option value="Low" <?php if ($task['priority'] == 'Low') echo 'selected'; ?>>Low</option>
                        <option value="Medium" <?php if ($task['priority'] == 'Medium') echo 'selected'; ?>>Medium</option>
                        <option value="High" <?php if ($task['priority'] == 'High') echo 'selected'; ?>>High</option>
                    </select>

                    <input type="date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required>
                </div>

                <button type="submit" class="primary-btn">Update Task</button>
            </form>
        </section>
    </main>
</div>
</body>
</html>