<?php
// Удаляем куки, установив время в прошлом
$past = time() - 3600; // 3600 секунд = 1 час назад
setcookie("ID_your_site", "", $past, "/");
setcookie("Key_your_site", "", $past, "/");
// Перенаправляем на страницу входа
header("Location: login.php"); 
?>
