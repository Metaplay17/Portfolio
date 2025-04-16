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
    if (ActivateUser($pdo, $email, $code)) 
    {
        header('location: ../pages/signin.php?errors=Аккаунт успешно активирован!');
        exit();
    }
    header("location: ../pages/signin.php?errors=Неверный код активации!");
    exit();
}
?>