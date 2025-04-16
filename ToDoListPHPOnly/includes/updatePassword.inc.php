<?php
session_start();
if(isset($_SESSION["id"])) 
{
    header('location: ../pages/todolist.php');
}
if (isset($_GET["email"]) && isset($_GET["code"])) 
{
    require_once "functions.inc.php";
    require_once "dbConnect.inc.php";

    $email = $_GET["email"];
    $code = $_GET["code"];
    $password = $_GET["password"];
    if (UpdatePassword($pdo, $email, $password, $code)) 
    {
        header('location: ../pages/signin.php?errors=Пароль обновлён!');
        exit();
    }
    header("location: ../pages/updatePassword.php?errors=Неверный код активации!");
    exit();
}
?>