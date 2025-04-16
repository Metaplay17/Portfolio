<?php

    function getEmailPrefix($email) {
        // Разделяем email по символу @
        $parts = explode('@', $email);

        // Если символ @ найден, возвращаем первую часть
        if (count($parts) > 1) {
            return $parts[0];
        }

    // Если символ @ не найден, возвращаем весь email или пустую строку
    return $email; // или return ''; в зависимости от требований
}

    function isEmptySignIn($email, $password) {
        if (empty($email) || empty($password)) {
            return true;
        }
        return false;
    }

    function checkValue($value) {
        return htmlentities(htmlspecialchars(strip_tags($value)));
    }

    function ValidateEmail($email) 
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return true; 
        }
        return false;
    }
    function ValidatePassword($password) {
        if (preg_match("/^(?=.*[A-Z])(?=.*\d).{6,}$/", $password)) {
            return true;
        } return false;
    }

    function IsEmptySignUp($name, $lastName, $email, $gender, $password, $confirmPassword) 
    {
        if (empty($name) || empty($lastName || empty($email) || empty($gender) || empty($password) || empty($confirmPassword))) {
            return true;
        }
        return false;
    }

    function IsUserExists($pdo, $email) {
        $sql = "SELECT * FROM users WHERE email=:email";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(["email" => $email]);
        $user = $stmt -> fetch();
        return $user != null;
    }

    function CreateUserDb($pdo, $name, $last_name, $email, $password) {
        $sql = "INSERT INTO users (name, last_name, email, password, active, login, code) 
            VALUES (:name, :last_name, :email, :password, 0, :login, :code)";

        $stmt = $pdo -> prepare($sql);
        $code = rand(100000, 999999);
        $stmt -> execute([
            'name' => $name,
            'last_name' => $last_name,
            'email'=> $email,
            'login' => getEmailPrefix($email),
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'code' => $code
            ]);
        SendActivationMail($email, $code);
    }

    function AuthUser($pdo, $email, $password) {
        $sql = "SELECT * FROM users WHERE email=:email OR login=:login";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(["email" => $email, "login" => getEmailPrefix($email)]);
        $user = $stmt -> fetch();

        if ($user == null || !password_verify($password, $user["password"]) || $user["active"] != 1) {
            return false;
        }
        return true;
    }

    function ActivateUser($pdo, $email, $usercode) {
        $sql1 = "SELECT code FROM users WHERE email=:email";
        $stmt = $pdo -> prepare($sql1);
        $stmt -> execute(["email" => $email]);
        $code = $stmt -> fetch()["code"];
        if ($code == $usercode) 
        {
            $sql = "UPDATE users SET active = 1 WHERE email=:email";
            $stmt = $pdo -> prepare($sql);
            $stmt -> execute(["email" => $email]);
            return true;
        }
        return false;
    }

    function SendActivationMail($email, $code) {
        $to = $email; // Укажите email получателя
        $subject = "Активация аккаунта";
        $message = "Для активации аккаунта перейдите по ссылке: http://localhost/ToDoList/includes/activation.inc.php?email={$email}&code={$code}";
        $headers = "From: danin35@yandex.ru" . "\r\n" .
                "Reply-To: danin35@yandex.ru" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            echo "Письмо успешно отправлено.";
        } else {
            echo "Ошибка при отправке письма.";
        }
    }

    function SendRecoverPasswordEmail($pdo, $email) {
        $code = rand(100000, 999999);
        $sql = "UPDATE users SET code = :code WHERE email=:email";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(["code" => $code, "email" => $email]);

        $to = $email; // Укажите email получателя
        $subject = "Обновление пароля";
        $message = "Для обновления пароля перейдите по ссылке: http://localhost/ToDoList/pages/updatePassword.php?email={$email}&code={$code}";
        $headers = "From: danin35@yandex.ru" . "\r\n" .
                "Reply-To: danin35@yandex.ru" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            echo "Письмо успешно отправлено.";
        } else {
            echo "Ошибка при отправке письма.";
        }
    }

    function UpdatePassword($pdo, $email, $new_password, $usercode) {
        $sql1 = "SELECT code FROM users WHERE email=:email";
        $stmt = $pdo -> prepare($sql1);
        $stmt -> execute(["email" => $email]);
        $code = $stmt -> fetch()["code"];
        if ($code != $usercode) 
        {
            return false;
        }
        $sql = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(["password" => password_hash($new_password, PASSWORD_BCRYPT), "email" => $email]);
        return true;
    }

    function IsUserActive($pdo, $email) {
        $sql1 = "SELECT active FROM users WHERE email=:email";
        $stmt = $pdo -> prepare($sql1);
        $stmt -> execute(["email" => $email]);
        $active = $stmt -> fetch()["active"];
        if ($active == 1) {
            return true;
        }
        return false;
    }
// С сессии нельзя попасть на signIn, signUp, resetPassword
// Без сессии нельзя попасть на ToDoList

    function GetUsernameByID($pdo, $user_id) {
        $sql1 = "SELECT login FROM users WHERE id=:id";
        $stmt = $pdo -> prepare($sql1);
        $stmt -> execute(["id" => $user_id]);
        $login = $stmt -> fetch()["login"];
        return $login;
    }

    function GetIdByEmail($pdo, $email) {
        $sql1 = "SELECT id FROM users WHERE email=:email";
        $stmt = $pdo -> prepare($sql1);
        $stmt -> execute(["email" => $email]);
        $id = $stmt -> fetch()["id"];
        return $id;
    }

    function AddTask($pdo, $user_id, $name, $description) 
    {
        $sql = "INSERT INTO tasks (user_id, name, description, completed) 
            VALUES (:user_id, :name, :description, 0)";

        $stmt = $pdo -> prepare($sql);
        $stmt -> execute([
            'user_id' => $user_id,
            'name' => $name,
            'description' => $description,
            ]);
    }

    function SwitchCompleteTask($pdo, $task_id) {
        $sqlCheck = "SELECT completed FROM tasks WHERE id = :task_id";
        $stmt = $pdo -> prepare($sqlCheck);
        $stmt -> execute(["task_id" => $task_id]);
        $status = $stmt -> fetch();
        if ($status["completed"] == 1) 
        {
            $sql = "UPDATE tasks SET completed = 0 WHERE id = :task_id";
            $stmt = $pdo -> prepare($sql);
            $stmt -> execute(["task_id" => $task_id]);
        }
        else 
        {
            $sql = "UPDATE tasks SET completed = 1 WHERE id = :task_id";
            $stmt = $pdo -> prepare($sql);
            $stmt -> execute(["task_id" => $task_id]);
        }

    }

    function UpdateTask($pdo, $task_id, $name, $description) {
        $sql = "UPDATE tasks SET name = :name, description = :description WHERE id = :task_id";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(["task_id" => $task_id, "name" => $name, "description" => $description]);
    }

    function DeleteTask($pdo, $task_id) {
        $sql = "DELETE FROM tasks WHERE id = :task_id";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(["task_id" => $task_id]);
    }

    function GetTasks($pdo, $user_id) {
        $sql = "SELECT * FROM tasks WHERE user_id = :user_id";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(["user_id" => $user_id]);
        $tasks = $stmt -> fetchAll();
        return $tasks;
    }

?>