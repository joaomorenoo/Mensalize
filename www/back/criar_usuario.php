<?php
session_start();
include('conecta.php');

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$senha = md5($_POST['senha']);
$ativo = 1;


if ($nome != "" && $cpf != "" && $email != "" && $senha != "") {

    $verifica = "SELECT id_usuario FROM usuario WHERE cpf = '$cpf'";
    $resultado = mysqli_query($conecta, $verifica);

    if (mysqli_num_rows($resultado) > 0) {
        echo "Já existe um usuário cadastrado com este CPF. Você será redireciomado";
        echo "<script>
                setTimeout(function() {
                    window.location.href = '../front/home.php';
                }, 3000);
            </script>";
        exit;
    } else {
        $sql = "INSERT INTO usuario (nome, cpf, email, senha, ativo) 
                VALUES ('$nome', '$cpf', '$email', '$senha', '$ativo')";

        if (mysqli_query($conecta, $sql)) {
            header('Location: ../front/home.php');
            exit;
        } else {
            echo "Erro ao registrar usuário: " . mysqli_error($conecta);
        }
    }
} else {
    echo "Preencha todos os campos obrigatórios.";
}
?>
