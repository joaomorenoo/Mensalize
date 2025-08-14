<?php
    session_start();
    include('conecta.php');

    $cpf = $_POST['cpf'];
    $senha = md5($_POST['senha']);

    $sql_login = "SELECT * FROM usuario WHERE cpf = '$cpf' AND senha = '$senha' AND ativo = 1";
    $consulta = mysqli_query($conecta, $sql_login);
    $cont_login = mysqli_num_rows($consulta);

    if ($cont_login >= 1) {
        if ($cpf == "000.000.000-00"){ 
            $_SESSION['chave'] = "printer";
            header("Location: ../front/firmware.php");  
        } else {
            $usuario = mysqli_fetch_assoc($consulta);
            $_SESSION['nome_usuario'] = $usuario['nome']; 
            $_SESSION['chave'] = "11092002";
            header("Location: ../front/home.php");
        }

    } else {
        $_SESSION['erro_login'] = "CPF ou senha inválidos!";
        header("Location: ../front/login.php");
    };
?>