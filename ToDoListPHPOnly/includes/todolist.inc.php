<?php
    session_start();
    if(isset($_SESSION["id"])) 
    {
        require_once "../includes/functions.inc.php";
        require_once "../includes/dbConnect.inc.php";

        $user_id = $_SESSION["id"];
    }
    else 
    {
        header('location: ../pages/signin.php');
    }

    if(isset($_POST)) {
        require_once "functions.inc.php";
        require_once "dbConnect.inc.php";

        $type = $_POST["action"];

        if ($type == "addTask") 
        {
            $name = $_POST["name"];
            $description = $_POST["description"];
            AddTask($pdo, $user_id, $name, $description);
        }

        else if ($type == "save") 
        {
            $name = $_POST["name"];
            $description = $_POST["description"];
            $task_id = $_POST["task_id"];
            UpdateTask($pdo, $task_id, $name, $description);
        }
        else if ($type == "done") 
        {
            $task_id = $_POST["task_id"];
            SwitchCompleteTask($pdo, $task_id);
        }
        else if ($type == "delete") 
        {
            $task_id = $_POST["task_id"];
            DeleteTask($pdo, $task_id);
        }
    }
    header('location: ../pages/todolist.php');
?>