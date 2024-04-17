<?php
// Устанавливаем соединение с базой данных
$conect = mysqli_connect("localhost", "root", "20Lw0aTiIYLvyZZ", "loginbase") or die(mysqli_connect_error()); 

// Переменные для хранения сообщений об ошибках
$usernameErr = $passErr = $loginErr = $emailErr = "";

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
    if(empty($_POST['username'])){
        $usernameErr = 'Вы не заполнили имя пользователя.';
    } else {
        $username = $_POST['username'];
    }
    if(empty($_POST['pass'])){
        $passErr = 'Вы не заполнили пароль.';
    } else {
        $pass = $_POST['pass'];
    }
    if(empty($_POST['email'])){
        $emailErr = 'Вы не заполнили email.';
    } else {
        // Проверяем корректность email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $emailErr = 'Некорректный формат email.';
        } else {
            $email = $_POST['email'];
        }
    }

    // Если нет ошибок, обрабатываем данные
    if (empty($usernameErr) && empty($passErr) && empty($emailErr)) {
        // Подготавливаем данные для запроса
        $username = mysqli_real_escape_string($conect, $username);
        $pass = md5($pass);
        $email = mysqli_real_escape_string($conect, $email);

        $check = mysqli_query($conect, "SELECT * FROM users WHERE username = '$username'")or die(mysqli_error($conect));

        $check2 = mysqli_num_rows($check);
        if ($check2 == 0){
            $loginErr = 'Такого пользователя нет в нашей базе данных.';
        }

        while($info = mysqli_fetch_array($check)){
            $info['password'] = stripslashes($info['password']);

            if ($pass != $info['password']){
                $loginErr = 'Неверный пароль';
            } else { 
                // Если данные введены верно, устанавливаем cookie и перенаправляем пользователя
                setcookie("ID_your_site", $username, time() + 3600); 
                setcookie("Key_your_site", $pass, time() + 3600);
                header("Location: members.php");
            }
        }
    }
}

// Выводим форму входа
?>

<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"> 
    <table border="0"> 
        <tr><td colspan=2><h1>ВХОД</h1></td></tr> 
        <tr>
            <td>логин:</td>
            <td> 
                <input type="text" name="username" maxlength="40"> 
                <span style="color:red;"><?php echo $usernameErr; ?></span>
            </td>
        </tr> 
        <tr>
            <td>Email:</td>
            <td> 
                <input type="text" name="email" maxlength="60"> 
                <span style="color:red;"><?php echo $emailErr; ?></span>
            </td>
        </tr> 
        <tr>
            <td>пароль:</td>
            <td> 
                <input type="password" name="pass" maxlength="50"> 
                <span style="color:red;"><?php echo $passErr; ?></span>
                <span style="color:red;"><?php echo $loginErr; ?></span>
            </td>
        </tr> 
        <tr>
            <td colspan="2" align="right"> 
                <input type="submit" name="submit" value="Войти"> 
            </td>
        </tr> 
    </table> 
</form> 

<?php 
// Закрываем соединение
mysqli_close($conect);
?>
