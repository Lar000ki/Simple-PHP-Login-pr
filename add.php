<?php 
// Устанавливаем соединение с базой данных
$conect = mysqli_connect("localhost", "root", "20Lw0aTiIYLvyZZ", "loginbase") or die(mysqli_connect_error()); 

// Переменные для хранения сообщений об ошибках
$usernameErr = $passErr = $pass2Err = $emailErr = "";

// Проверяем, была ли отправлена форма
if (isset($_POST['submit'])) { 

    // Проверяем, чтобы все поля были заполнены
    if (empty($_POST['username'])) {
        $usernameErr = "Введите логин";
    }
    if (empty($_POST['pass'])) {
        $passErr = "Введите пароль";
    }
    if (empty($_POST['pass2'])) {
        $pass2Err = "Введите подтверждение пароля";
    }
    if (empty($_POST['email'])) {
        $emailErr = "Введите email";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Некорректный формат email";
    }

    // Проверяем, не используется ли уже указанное имя пользователя
    $usercheck = mysqli_real_escape_string($conect, $_POST['username']);
    $check = mysqli_query($conect, "SELECT username FROM users WHERE username = '$usercheck'") or die(mysqli_error($conect));
    $check2 = mysqli_num_rows($check);

    // Если имя пользователя уже используется, выводим сообщение об ошибке
    if ($check2 != 0) {
        $usernameErr = 'Имя пользователя уже используется';
    }

    // Проверяем совпадение паролей
    if ($_POST['pass'] != $_POST['pass2']) {
        $pass2Err = 'Пароли не совпадают';
    }

    // Если нет ошибок, регистрируем пользователя
    if (empty($usernameErr) && empty($passErr) && empty($pass2Err) && empty($emailErr)) {
        // Шифруем пароль
        $password = md5($_POST['pass']);
        $username = mysqli_real_escape_string($conect, $_POST['username']);
        $email = mysqli_real_escape_string($conect, $_POST['email']);

        // Вставляем данные в базу данных
        $insert = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        $add_member = mysqli_query($conect, $insert);

        // Выводим сообщение об успешной регистрации
        ?>
        <h1>Registered</h1>
        <p>Thank you, you have registered - you may now <a href="login.php">login</a>.</p>
        <?php 
    }
}
// Закрываем соединение
mysqli_close($conect);
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <table border="0">
        <tr>
            <td>Логин:</td>
            <td><input type="text" name="username" maxlength="60" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>"></td>
            <td><span style="color:red;"><?php echo $usernameErr; ?></span></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><input type="text" name="email" maxlength="60" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"></td>
            <td><span style="color:red;"><?php echo $emailErr; ?></span></td>
        </tr>
        <tr>
            <td>Пароль:</td>
            <td><input type="password" name="pass" maxlength="10"></td>
            <td><span style="color:red;"><?php echo $passErr; ?></span></td>
        </tr>
        <tr>
            <td>Подтверждение пароля:</td>
            <td><input type="password" name="pass2" maxlength="10"></td>
            <td><span style="color:red;"><?php echo $pass2Err; ?></span></td>
        </tr>
        <tr>
            <th colspan=2><input type="submit" name="submit" value="Зарегистрироваться"></th>
        </tr>
    </table>
</form>
