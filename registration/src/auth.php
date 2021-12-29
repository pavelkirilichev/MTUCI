<?php 
    require 'connect_db.php';

    $login = $_POST['login'];
    $password = $_POST['password'];

    $auth_sql = mysqli_query($link, "SELECT * FROM `registration` WHERE `login` = '$login' AND `password` = '$password'");
    $auth_result = mysqli_fetch_array($auth_sql);

    if ($auth_result['id']) {
        echo "Добро пожаловать, {$auth_result['name']}!";
    }
    else {
        echo "Неверный логин или пароль. <a href='../index.html'>Вернуться назад</a>";
    }
?>