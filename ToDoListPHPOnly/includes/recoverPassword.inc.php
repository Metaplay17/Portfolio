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
    $code = checkValue($_POST["code"]);
    $new_password = checkValue($_POST["new-password"]);
    $confirm_password = checkValue($_POST["confirm-password"]);

    if (!empty($new_password) && !empty($confirm_password)) 
    {
        if ($new_password != $confirm_password) 
        { 
            header("location: ../pages/updatePassword.php?email={$email}&code={$code}&errors=Пароли не совпадают!");
            exit();
        }
        if (!ValidatePassword($new_password)) {
            header("location: ../pages/updatePassword.php?email={$email}&code={$code}&errors=Длина пароля от 6 символов, минимум одна цифра и заглавная буква");
            exit();
        }
        UpdatePassword($pdo,$email,$new_password,$code);
        header('location: ../pages/signin.php?errors=Пароль обновлён!');
        exit();
    }
    header("location: ../pages/updatePassword.php?email={$email}&code={$code}&errors=Все поля обязательны!");
    exit();
}

?>