<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link rel="stylesheet" href="../css/todolist/todolist.css">
</head>
<body>
    <?php
    session_start();
    if(isset($_SESSION["id"])) 
    {
        require_once "../includes/functions.inc.php";
        require_once "../includes/dbConnect.inc.php";

        $user_id = $_SESSION["id"];
        $username = GetUsernameByID($pdo, $user_id);
    }
    else 
    {
        header('location: ../pages/signin.php');
    }
    ?>
    <header>
        <div></div> <!-- Empty div for balance -->
        <div class="user-section">
            <span class="username"><?php echo $username ?></span>
            <a href="../includes/signOut.inc.php">
                <button class="sign-out-btn">Sign Out</button>
            </a>
        </div>
    </header>

    <main>
        <form class="add-task-form" method="POST" action="../includes/todolist.inc.php">
            <h2 class="form-title">Add New Task</h2>
            <div class="form-group">
                <label for="task-title">Title</label>
                <input type="text" id="task-title" name="name" placeholder="Enter task title" required>
            </div>
            <div class="form-group">
                <label for="task-description">Description</label>
                <textarea id="task-description" name="description" placeholder="Enter task description"></textarea>
            </div>
            <button type="submit" name="action" value="addTask" class="add-btn">Add Task</button>
        </form>

        <div class="tasks-container">
            <?php 
            require_once "../includes/functions.inc.php";
            require_once "../includes/dbConnect.inc.php";
            $tasks = GetTasks($pdo, $user_id);
            if (!empty($tasks)): 
            ?>
            <?php foreach ($tasks as $task): ?>
                <form class="task" method="POST" action="../includes/todolist.inc.php">
                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>"> <!-- MUSTHAVE -->
                    
                    <div class="task-header">
                        <div class="status-icon <?= $task['completed'] ? 'completed' : '' ?>">
                            <?= $task['completed'] ? '✓' : '!' ?>
                        </div>
                        <h3>Task Status</h3>
                    </div>
                    
                    <div class="form-group">
                        <input type="text" name="name" value="<?= htmlspecialchars($task['name']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <textarea name="description"><?= htmlspecialchars($task['description']) ?></textarea>
                    </div>
                    
                    <div class="task-actions">
                        <button type="submit" name="action" value="done" class="task-btn <?= $task['completed'] ? 'undo-btn' : 'done-btn' ?>">
                            <?= $task['completed'] ? 'Undo' : 'Done' ?>
                        </button>
                        <button type="submit" name="action" value="save" class="task-btn save-btn">Save</button>
                        <button type="submit" name="action" value="delete" class="task-btn delete-btn">Delete</button>
                    </div>
                </form>
            <?php endforeach; ?>
            <?php else: ?>
                <p class="no-tasks">No tasks found. Add your first task!</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>