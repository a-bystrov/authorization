<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UsersList</title>
</head>
<body>
    <?php
        session_start();
        // $fileUsers = file_get_contents('users.json');
        $fileUsers = openssl_decrypt(file_get_contents('users.json'), $_SESSION['methodCrypt'], $_SESSION['passCrypt']);
        $users = json_decode($fileUsers, true);
        foreach ($users as $key => $value) {
            $password=$value["password"];
            $block=$value["block"] ? "true" : "false";
            $limit=$value["limit"] ? "true" : "false";
            echo("{$key} (пароль: {$password} | блокировка: {$block} | ограничение: {$limit}) <br/>");
        }
    ?>
</body>
</html>