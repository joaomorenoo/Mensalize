<?php
session_start();
include('conecta.php');

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$data_nasc = $_POST['data_nasc'];
$status = 1;

$verifica = "SELECT id_aluno FROM alunos WHERE cpf = '$cpf'";
$resultado = mysqli_query($conecta, $verifica);

if (mysqli_num_rows($resultado) > 0) {
    echo "cpf_existente";
    exit;
} else {
    if ($nome != "" && $cpf != "") {
    $insert = "INSERT INTO alunos (nome, cpf, telefone, email, data_nasc, ativo) 
               VALUES ('$nome', '$cpf', '$telefone', '$email', '$data_nasc', '$status')";

    if (mysqli_query($conecta, $insert)) {
        echo "ok";
    } else {
        echo "erro";
    }
} else {
    echo "incompleto";
}
}


?>
