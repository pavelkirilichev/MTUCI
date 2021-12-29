<?php 
    require 'connect_db.php';

    $login = $_POST['login'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    $auth_sql = mysqli_query($link, "SELECT * FROM `registration` WHERE `login` = '$login'");
    $auth_result = mysqli_fetch_array($auth_sql);

    if ($auth_result['id']) {
        echo "Логин существует. <a href='../index.html'>Вернуться назад</a>";
    }
    else {
        mysqli_query($link, "INSERT INTO `registration` (`login`, `password`, `name`) VALUES ('$login', '$password', '$name')");
        header("Location: ../index.html");
    }
    
?>