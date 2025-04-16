<?php
session_start();
if(isset($_SESSION["id"])) 
{
    header('location: ../pages/todolist.php');
}

if(isset($_POST)) {
    require_once "functions.inc.php";
    require_once "dbConnect.inc.php";

    $name = checkValue($_POST["name"]);
    $lastName = checkValue($_POST["lastname"]);
    $email = checkValue($_POST["email"]); // name в html
    $gender = checkValue($_POST["gender"]);
    $password = checkValue($_POST["password"]);
    $confirmPassword = checkValue($_POST["confirm-password"]);

    if (isEmptySignUp($name, $lastName, $email, $gender, $password, $confirmPassword)) {
        header('location: ../pages/signup.php?errors=Все поля обязательны');
        exit();
    }
    
    if (!ValidateEmail($email)) {
        header('location: ../pages/signup.php?errors=Введите корректный email!');
        exit();
    }
    
    if (!ValidatePassword($password)) {
        header('location: ../pages/signup.php?errors=Длина пароля от 6 символов, минимум одна цифра и заглавная буква');
        exit();
    }
    
    if ($password != $confirmPassword) {
        header('location: ../pages/signup.php?errors=Пароли не совпадают');
        exit();
    }

    if (IsUserExists($pdo, $email)) {
        header('location: ../pages/signup.php?errors=Пользователь с таким email уже существует');
        exit();
    }

    CreateUserDb($pdo, $name, $lastName, $email, $password);
    header('location: ../pages/signin.php?errors=Успешная регистрация, активируйте аккаунт');
    exit();
}

?>