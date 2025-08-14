<?php
    include('conecta.php');

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $data_termino = $_POST['data_termino'];
    $valor_total = $_POST['valor_total'];

    $sql = "insert into eventos (nome, descricao, data_termino, valor_total) values ('$nome', '$descricao', '$data_termino', '$valor_total')";
    mysqli_query($conecta, $sql);

    header('location:../front/eventos.php');
?>