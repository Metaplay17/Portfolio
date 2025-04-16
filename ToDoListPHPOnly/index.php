<?php
$to = "danin35@yandex.ru"; // Укажите email получателя
$subject = "Тестовое письмо";
$message = "Это тестовое письмо, отправленное через Yandex SMTP.";
$headers = "From: danin35@yandex.ru" . "\r\n" .
           "Reply-To: danin35@yandex.ru" . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo "Письмо успешно отправлено.";
} else {
    echo "Ошибка при отправке письма.";
}
?>