<?php
$dbHost = "localhost"; // Хост базы данных
$dbUsername = "root"; // Имя пользователя
$dbPassword = ""; // Пароль
$dbName = "mydb"; // Имя базы данных

$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8"; // Data Source Name

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Данные в формате ассоциативного массива
    PDO::ATTR_EMULATE_PREPARES => false, // запросы подготавливаются на СУБД
];

try {
    $pdo = new PDO($dsn, $dbUsername, $dbPassword, $options);

} catch (PDOException $e) {
    die("ERROR: ". $e->getMessage());
}


?>