<?php
session_start();
if(isset($_SESSION["id"])) 
{
    header('location: ../pages/todolist.php');
}

if(isset($_POST)) {
    require_once "functions.inc.php";
    require_once "dbConnect.inc.php";

    // $sql = "SELECT * FROM users;";
    // $stmt = $pdo -> query($sql);

    // while ($row = $stmt -> fetch()) {
    //     echo $row["email"]."<br>";
    // }

    // $sql2 = "UPDATE users SET active=0 WHERE id=1";
    // $stmt2 = $pdo -> exec($sql2);
    // echo $stmt2;


    // $sql3 = "SELECT * FROM users WHERE email=:email";
    // $stmt3 = $pdo -> prepare($sql3);
    // $stmt3 -> execute(["email" => "den@mail.ru"]);
    // $user = $stmt3 -> fetch();

    // echo $user["name"];

    $email = checkValue($_POST["email"]); // name в html
    $password = checkValue($_POST["password"]);

    if (!IsUserActive($pdo, $email)) {
        header('location: ../pages/signin.php?errors=Активируйте аккаунт!');
        exit();
    }

    if (isEmptySignIn($email, $password)) {
        header('location: ../pages/signin.php?errors=Все поля обязательны');
        exit();
    }
    // if (!ValidateEmail($email)) {
    //     header('location: ../pages/signin.php?errors=Введите корректный email!');
    //     exit();
    // }

    if (AuthUser($pdo, $email, $password)) {
        session_start();
        $_SESSION["id"] = GetIdByEmail($pdo, $email);
        header('location: ../pages/todolist.php');
    }
    else {
        header('location: ../pages/signin.php?errors=Не найдена комбинация логина и пароля');
        exit();
    }
}

?>