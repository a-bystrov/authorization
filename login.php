<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleLogIn.css">
    <title>Login</title>
</head>
<body>
    <?php
        error_reporting(E_ERROR);
        session_start();
        $_SESSION['passCrypt']= '1234';
        $_SESSION['methodCrypt']= 'aes128';
        $fileUsers = openssl_decrypt(file_get_contents('users.json'), $_SESSION['methodCrypt'], $_SESSION['passCrypt']);
        // $fileUsers = file_get_contents('users.json');
        $users = json_decode($fileUsers, true);
        // print_r($users);
        if(!empty($_POST)){
            $login = $_POST['login'];
            $password = hash("md2", $_POST['password']);
            if($users[$login]["block"]==true){
                echo "<p class='error'>пользователь заблокирован</p>";
            } else {
                if(array_key_exists($login, $users)){
                    if($password == $users[$login]['password']){
                        $_SESSION['login']=$login;
                        // $_SESSION['password']=$users[$login]['password'];
                        $_SESSION['password']=$_POST['password'];
                        $_SESSION['users']=$users;
                        $_SESSION['countAttempt'] = 0;
                        header('Location:user.php');
                    }
                    else {
                        echo "<p class='error'>неверный пароль</p>";
                        $_SESSION['countAttempt'] += 1;
                    }
                }
                else{
                    echo "<p class='error'>неверный логин</p>";
                }
            }
        }
    ?>
    <?php
        if(!isset($_SESSION['countAttempt'])){
            $_SESSION['countAttempt'] = 0;
        } else if($_SESSION['countAttempt']>=3){
            echo "<p class='error'>блокировка</p>";
            $_SESSION['countAttempt'] = 0;
            die;
        }
    ?>
    <div class="containerLogIn"> 
        <h1>Авторизация</h1>
        <form action="" method="POST">
            <input type="text" placeholder="Логин" name="login">
            <input type="password" placeholder="Пароль" name="password">
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>