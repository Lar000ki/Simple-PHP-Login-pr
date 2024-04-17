<?php 
// Устанавливаем соединение с базой данных
$conect = mysqli_connect("localhost", "root", "20Lw0aTiIYLvyZZ", "loginbase") or die(mysqli_connect_error()); 

// Проверяем, была ли отправлена форма
if (isset($_POST['submit'])) { 

    // Проверяем, чтобы все поля были заполнены
    if (!$_POST['username'] | !$_POST['pass'] | !$_POST['pass2'] ) {
        die('You did not complete all of the required fields');
    }

    // Проверяем, не используется ли уже указанное имя пользователя
    $usercheck = mysqli_real_escape_string($conect, $_POST['username']);
    $check = mysqli_query($conect, "SELECT username FROM users WHERE username = '$usercheck'") or die(mysqli_error($conect));
    $check2 = mysqli_num_rows($check);

    // Если имя пользователя уже используется, выводим сообщение об ошибке
    if ($check2 != 0) {
        die('Sorry, the username '.$_POST['username'].' is already in use.');
    }

    // Проверяем совпадение паролей
    if ($_POST['pass'] != $_POST['pass2']) {
        die('Your passwords did not match. ');
    }

    // Шифруем пароль
    $password = md5($_POST['pass']);
    $username = mysqli_real_escape_string($conect, $_POST['username']);

    // Вставляем данные в базу данных
    $insert = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    $add_member = mysqli_query($conect, $insert);

    // Выводим сообщение об успешной регистрации
?>
    <h1>Registered</h1>
    <p>Thank you, you have registered - you may now <a href="login.php">login</a>.</p>

<?php 
} else {	
    // Если форма не была отправлена, выводим форму регистрации
?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <table border="0">
            <tr><td>Username:</td><td><input type="text" name="username" maxlength="60"></td></tr>
            <tr><td>Password:</td><td><input type="password" name="pass" maxlength="10"></td></tr>
            <tr><td>Confirm Password:</td><td><input type="password" name="pass2" maxlength="10"></td></tr>
            <tr><th colspan=2><input type="submit" name="submit" value="Register"></th></tr>
        </table>
    </form>
<?php
}
// Закрываем соединение
mysqli_close($conect);
?>
