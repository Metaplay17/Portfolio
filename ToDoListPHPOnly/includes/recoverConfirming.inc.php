<?php
session_start();
if(isset($_SESSION["id"])) 
{
    header('location: ../pages/todolist.php');
}
if(isset($_POST)) {
    require_once "functions.inc.php";
    require_once "dbConnect.inc.php";

    $email = checkValue($_POST["email"]); // name в html


    if (!ValidateEmail($email)) {
        header('location: ../pages/recover-password.php?errors=Введите корректный email!');
        exit();
    }
    SendRecoverPasswordEmail($pdo,$email);
    header('location: ../pages/signin.php?errors=Письмо с подтверждением сброса отправлено!');
    exit();
}

?>