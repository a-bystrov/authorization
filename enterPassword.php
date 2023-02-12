<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleEnterPassword.css">
    <title>Password</title>
</head>
<body>
    <h1>Введите пароль для расшифровки</h1>
    <form action="" method="POST">
        <input type="password" placeholder="Пароль" name="passwordForDecrypt">
        <button type="submit">Ввод</button>
    </form>
    <?php
        if(isset($_POST["passwordForDecrypt"])){
            if(($_POST["passwordForDecrypt"])=='1234'){
                if(openssl_decrypt(file_get_contents('users.json'), 'aes128', '1234')){
                    header('Location:login.php');
                } else {
                    echo "<p class='error'>Ошибка расшифровки файла!</p>";
                }
            }
            else {
                echo "<p class='error'>Неверный пароль</p>";
            }
        }
    ?>
</body>
</html>