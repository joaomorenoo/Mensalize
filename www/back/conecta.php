<?php
    $hostname = 'mariadb'; 
    $username = 'root';
    $password = 'root';
    $banco = 'mensalize';

    $conecta = mysqli_connect($hostname, $username, $password, $banco);

    if(!$conecta){
        die("A conexão falhou: " . mysqli_connect_error());
    }
?>