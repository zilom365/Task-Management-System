<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tasks WHERE user_id='$user_id'");
$total_tasks = mysqli_fetch_assoc($total_result)['total'];

$completed_result = mysqli_query($conn, "SELECT COUNT(*) AS completed FROM tasks WHERE user_id='$user_id' AND status='Completed'");
$completed_tasks = mysqli_fetch_assoc($completed_result)['completed'];

$pending_result = mysqli_query($conn, "SELECT COUNT(*) AS pending FROM tasks WHERE user_id='$user_id' AND status!='Completed'");
$pending_tasks = mysqli_fetch_assoc($pending_result)['pending'];

$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $tasks = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id='$user_id' AND title LIKE '%$search%' ORDER BY due_date ASC");
} else {
    $tasks = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id='$user_id' ORDER BY due_date ASC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TaskFlow</title>
    <link rel="stylesheet" href="assets/css/style.css?v=10">
    <script src="assets/js/script.js"></script>
</head>
<body class="dashboard-page">

<div class="dashboard-layout">

    <aside class="sidebar">
        <h2 class="logo">TaskFlow</h2>
        <button class="secondary-btn full-btn" onclick="toggleDark()">🌙 Dark / Light</button>

        <nav class="sidebar-menu">
            <a href="index.php">Dashboard</a>
            <a href="#add-task">Add Task</a>
            <a href="#task-list">My Tasks</a>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="dashboard-header">
            <h1>All your work in one place.</h1>
            <p>Simple, efficient, yet attractive dashboard for managing your tasks.</p>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <h3>Total Tasks</h3>
                <p><?php echo $total_tasks; ?></p>
            </div>
            <div class="summary-card">
                <h3>Completed</h3>
                <p><?php echo $completed_tasks; ?></p>
            </div>
            <div class="summary-card">
                <h3>Pending</h3>
                <p><?php echo $pending_tasks; ?></p>
            </div>
        </div>

        <section class="content-card" id="add-task">
            <h2>Add New Task</h2>
            <form action="add_task.php" method="POST">
                <input type="text" name="title" placeholder="Task title" required>
                <textarea name="description" rows="4" placeholder="Task description"></textarea>

                <div class="form-row">
                    <select name="status" required>
                        <option value="To Do">To Do</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>

                    <select name="priority" required>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>

                    <input type="date" name="due_date" required>
                </div>

                <button type="submit" class="primary-btn">Add Task</button>
            </form>
        </section>

        <section class="content-card">
            <h2>Search Tasks</h2>
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search by title..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="primary-btn">Search</button>
            </form>
        </section>

        <section class="content-card" id="task-list">
            <h2>Your Tasks</h2>
            <div class="task-table-wrapper">
                <table class="task-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($tasks) > 0) { ?>
                        <?php while ($task = mysqli_fetch_assoc($tasks)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                <td><?php echo htmlspecialchars($task['description']); ?></td>
                                <td><?php echo htmlspecialchars($task['status']); ?></td>
                                <td><?php echo htmlspecialchars($task['priority']); ?></td>
                                <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                                <td>
                                    <a class="action-btn edit-btn" href="edit_task.php?id=<?php echo $task['id']; ?>">Edit</a>
                                    <a class="action-btn delete-btn" href="delete_task.php?id=<?php echo $task['id']; ?>" onclick="return confirm('Delete this task?');">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">No tasks found.</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

</body>
</html>