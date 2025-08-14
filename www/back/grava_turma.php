<?php
    include('conecta.php');

    $nome = $_POST['nome'];
    $turno = $_POST['turno'];
    $curso = $_POST['curso'];
    $semestre = $_POST['semestre'];
    $ano = $_POST['ano'];
    $ativo = 1;

    $insert = "insert into turma (turma, turno, curso, semestre, ano, ativo) values ('$nome', '$turno', '$curso', '$semestre', '$ano', '$ativo')";


    if ($nome != "" && $curso != ""){
        mysqli_query($conecta, $insert) or die ("Não foi possivel realizar o cadastro, tente novamente mais tarde");
        header('location: ../front/menu.php');
    } else {
        header('location: ../front/menu.php');
    }



?>