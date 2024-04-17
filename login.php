<?php
// Устанавливаем соединение с базой данных
$conect = mysqli_connect("localhost", "root", "20Lw0aTiIYLvyZZ", "loginbase") or die(mysqli_connect_error()); 

// Проверяем наличие cookie для авторизации
if(isset($_COOKIE['ID_your_site'])){ 
 	$username = $_COOKIE['ID_your_site']; 
 	$pass = $_COOKIE['Key_your_site'];
 	$check = mysqli_query($conect, "SELECT * FROM users WHERE username = '$username'")or die(mysqli_error($conect));

 	while($info = mysqli_fetch_array($check)){
 		if ($pass != $info['password']){
 			header("Location: login.php");
 		} else {
 			header("Location: members.php");
 		}
 	}
}

// Если форма входа была отправлена
if (isset($_POST['submit'])) {
	// Проверяем заполнение формы
 	if(!$_POST['username']){
 		die('Вы не заполнили имя пользователя.');
 	}
 	if(!$_POST['pass']){
 		die('Вы не заполнили пароль.');
 	}

 	// Подготавливаем данные для запроса
 	$username = mysqli_real_escape_string($conect, $_POST['username']);
 	$pass = md5($_POST['pass']);

 	// Выполняем запрос к базе данных
 	$check = mysqli_query($conect, "SELECT * FROM users WHERE username = '$username'")or die(mysqli_error($conect));

 	// Проверяем наличие пользователя в базе данных
 	$check2 = mysqli_num_rows($check);
 	if ($check2 == 0){
		die('Такого пользователя нет в нашей базе данных.<br /><br />Если ты думаешь, что это неправильно <a href="login.php">попробуй еще раз</a>.');
	}

	while($info = mysqli_fetch_array($check)){
	 	$info['password'] = stripslashes($info['password']);

	 	// Проверяем совпадение паролей
	 	if ($pass != $info['password']){
	 		die('Неверный пароль <a href="login.php">попробуй еще раз</a>.');
	 	} else { 
			// Если данные введены верно, устанавливаем cookie и перенаправляем пользователя
			setcookie("ID_your_site", $username, time() + 3600); 
			setcookie("Key_your_site", $pass, time() + 3600);
			header("Location: members.php");
		}
	}
} else { // Выводим форму входа, если пользователь не аутентифицирован
?>

 <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"> 

 <table border="0"> 

 <tr><td colspan=2><h1>Login</h1></td></tr> 

 <tr><td>Username:</td><td> 

 <input type="text" name="username" maxlength="40"> 

 </td></tr> 

 <tr><td>Password:</td><td> 

 <input type="password" name="pass" maxlength="50"> 

 </td></tr> 

 <tr><td colspan="2" align="right"> 

 <input type="submit" name="submit" value="Login"> 

 </td></tr> 

 </table> 

 </form> 

 <?php 
}
// Закрываем соединение
mysqli_close($conect);
?>
