<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleUser.css">
    <title>User</title>
</head>
<body>
    <div>
        <details>
            <summary>Справка</summary>
            <details>
                <summary>О программе</summary>
                <p>Автор: Быстров Антон ИДБ-19-10</p>
                <p>Индивидуальное задание: (3)Наличие букв и цифр</p>
            </details>
        </details>
    </div>
    <?php
        error_reporting(E_ERROR);
        session_start();
        $login = $_SESSION['login'];
        $password = $_SESSION['password'];
        $users = $_SESSION['users'];

        if(isset($_POST["firstEnterPassword"]) and isset($_POST["repeatedFirstEnterPassword"])){
            if($_POST["firstEnterPassword"]==$_POST["repeatedFirstEnterPassword"]){
                $users[$login]['password']=hash("md2", $_POST["firstEnterPassword"]);
                // file_put_contents('users.json', json_encode($users));
                file_put_contents('users.json', openssl_encrypt(json_encode($users), $_SESSION['methodCrypt'], $_SESSION['passCrypt']));
                // $_SESSION['password']=hash("md2", $_POST["firstEnterPassword"]);
                $_SESSION['password']=$_POST["firstEnterPassword"];
                $_SESSION['users']=$users;
                header('Location:login.php');
            } else {
                echo "<p class='error'>пароли не совпадают</p>";
            }
        } 
        
        $pttrn = ($users[$login]["limit"])? "^(?=.*\d)(?=.*[a-zA-Z])(?!.*\s).*" : "^.+";
        
        if($_SESSION['password']==""){
            echo "
                <h3>Установите пароль</h3>
                <form action='' method='POST'>
                    <input pattern='{$pttrn}' type='password' placeholder='Введите пароль' name='firstEnterPassword' title='Пароль должен содержать буквы и цифры'>
                    <input type='password' placeholder='Повторите пароль' name='repeatedFirstEnterPassword'>
                    <button type='submit'>Установить</button>
                </form>";
            die;
        } else if($users[$_SESSION['login']]["limit"]==true && preg_match("^(?=.*\d)(?=.*[a-zA-Z])(?!.*\s).*^", $_SESSION['password'])==0){
            echo "
                <h3>Установите новый пароль</h3>
                <form action='' method='POST'>
                    <input pattern='{$pttrn}' type='password' placeholder='Введите пароль' name='firstEnterPassword' title='Пароль должен содержать буквы и цифры'>
                    <input type='password' placeholder='Повторите пароль' name='repeatedFirstEnterPassword'>
                    <button type='submit'>Установить</button>
                </form>";
            die;
        }
        
    ?>
        <form action="" method="POST">
            <h3>Смена пароля</h3>
            <input type="password" placeholder="Старый пароль" name="oldPassword">
            <input pattern="<?php echo $pttrn?>" title="Пароль должен содержать буквы и цифры" type="password" placeholder="Новый пароль" name="newPassword">
            <input pattern="<?php echo $pttrn?>" title="Пароль должен содержать буквы и цифры" type="password" placeholder="Повторите пароль" name="repeatedNewPassword">
            <button type="submit">Сменить пароль</button>
        </form>
        
    <?php
        if(isset($_POST["oldPassword"]) and isset($_POST["newPassword"])){
            if($_POST["oldPassword"]==$password){
                if($_POST["newPassword"]==$_POST["repeatedNewPassword"]){
                    $users[$login]['password']=hash("md2", $_POST["newPassword"]);
                    // file_put_contents('users.json', json_encode($users));
                    file_put_contents('users.json', openssl_encrypt(json_encode($users), $_SESSION['methodCrypt'], $_SESSION['passCrypt']));
                    // $_SESSION['password']=hash("md2", $_POST["newPassword"]);
                    $_SESSION['password']=$_POST["newPassword"];
                    $_SESSION['users']=$users;
                } else {
                    echo "<p class='error'>пароли не совпадают</p>";
                }
            } else{
                echo "<p class='error'>неверный старый пароль</p>";
            }
        }

        if($login=='admin'){
            echo('
                <h3>Добавление/Редактирование пользователя</h3>
                <form action="" method="POST" class="formAddUser">
                    <input type="text" placeholder="Логин" name="newUserLogin">
                    <input type="checkbox" name="block" value=true><p>блокировка</p>
                    <input type="checkbox" name="limit" value=true><p>ограничение пароля</p>
                    <button type="submit">Добавить/Отредактировать</button>
                </form> 
                <h3>Информация о пользователях</h3>
                <div class="formInfoUsers">
                    <button class="btnAllUsers">Просмотр</button>
                </div>
                '
            );
        }

        if(isset($_POST["newUserLogin"])){
            $block=false;
            $limit=false;
            if(isset($_POST["block"])){
                $block=true;
            }
            if(isset($_POST["limit"])){
                $limit=true;
            }
            if(array_key_exists($_POST["newUserLogin"], $users)){
                $users[$_POST["newUserLogin"]]["block"] = $block;
                $users[$_POST["newUserLogin"]]["limit"] = $limit;
            } else {
                $users += [$_POST["newUserLogin"] => ["password"=>hash("md2", ""),"block"=>$block,"limit"=>$limit]];
            }
            $_SESSION['users']=$users;
            file_put_contents('users.json', openssl_encrypt(json_encode($users), $_SESSION['methodCrypt'], $_SESSION['passCrypt']));
            // file_put_contents('users.json', json_encode($users));
        }
     ?>
     
     <button class="btnLogOut">Выйти</button>
    <script>
        const btnLogOut = document.querySelector('.btnLogOut')
        const btnAllUsers = document.querySelector('.btnAllUsers')
        const passwordForEncrypt = document.querySelector('.passwordForEncrypt')

        btnLogOut.addEventListener('click',()=>{
            window.location.href = 'login.php';
        })

        btnAllUsers.addEventListener('click',()=>{
            window.open('usersList.php');
        })
    </script>        
</body>
</html>