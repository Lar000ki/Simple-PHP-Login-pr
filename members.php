<?php
// Устанавливаем соединение с базой данных
$servername = "localhost"; // Замените на адрес сервера базы данных
$username = "root"; // Замените на имя пользователя базы данных
$password = "20Lw0aTiIYLvyZZ"; // Замените на пароль пользователя базы данных
$dbname = "loginbase"; // Замените на имя базы данных

// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверяем наличие cookie для авторизации
if(isset($_COOKIE['ID_your_site'])){ 

 	$username = $_COOKIE['ID_your_site']; 
 	$pass = $_COOKIE['Key_your_site']; 
	
	// Выполняем запрос к базе данных для получения информации о пользователе
 	$check = $conn->query("SELECT * FROM users WHERE username = '$username'");
	
	if($check){
		$info = $check->fetch_assoc();
	
		// Проверяем пароль
 		if ($pass != $info['password']){
			header("Location: login.php"); 
 		} else {
 			// Если пароль совпадает, показываем административную область
 			echo "Ты успешно залогинился!<p>"; 
     		echo "<a href=logout.php>Logout</a>"; 
 		}
	} else {
		die("MySQL Error: " . $conn->error);
	}
} else { 
	// Если cookie не существует, перенаправляем на страницу входа
	header("Location: login.php"); 
}

// Закрываем соединение
$conn->close();
?>
