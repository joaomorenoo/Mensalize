<?php
include('../back/conecta.php');

$id_aluno = $_POST['id_aluno'];
$id_turma = $_POST['id_turma'];
$valor = $_POST['valor_reembolso'];

$sql = "UPDATE grupo_alunos SET ativo = 0 WHERE id_turma = $id_turma AND id_aluno = $id_aluno";
mysqli_query($conecta, $sql);

$verifica_evento = "
    SELECT e.id_evento
    FROM evento_turma et
    INNER JOIN eventos e ON et.id_evento = e.id_evento
    WHERE et.id_turma = $id_turma AND e.ativo = 1
";
$res_evento = mysqli_query($conecta, $verifica_evento);

if (mysqli_num_rows($res_evento) > 0) {
    $insert = "INSERT INTO reembolsos (id_aluno, id_turma, valor) VALUES ($id_aluno, $id_turma, $valor)";
    mysqli_query($conecta, $insert);

    $update_total_pago = "
        UPDATE total_pago 
        SET total_pagamentos = total_pagamentos - $valor 
        WHERE id_turma = $id_turma
    ";
    mysqli_query($conecta, $update_total_pago);

    $update_status_mensalidade = "
        UPDATE mensalidades 
        SET ativo = 0 
        WHERE id_aluno = $id_aluno AND id_turma = $id_turma
    ";
    mysqli_query($conecta, $update_status_mensalidade);

    $sql_evento = "
        SELECT id_evento 
        FROM mensalidades 
        WHERE id_aluno = $id_aluno AND id_turma = $id_turma 
        LIMIT 1
    ";
    $result_evento = mysqli_query($conecta, $sql_evento);
    $evento = mysqli_fetch_assoc($result_evento);
    $id_evento = $evento['id_evento'];

    $sql_ativos = "
        SELECT id_mensalidade 
        FROM mensalidades 
        WHERE id_evento = $id_evento 
            AND ativo = 1 
            AND id_aluno != $id_aluno
    ";
    $result_ativos = mysqli_query($conecta, $sql_ativos);
    $quantidade = mysqli_num_rows($result_ativos);

    if ($quantidade > 0) {
       $valor_individual = $valor / $quantidade;

        while ($linha = mysqli_fetch_assoc($result_ativos)) {
            $id_mensalidade = $linha['id_mensalidade'];

            $sql_update_valor = "
                UPDATE mensalidades 
                SET valor = valor + $valor_individual 
                WHERE id_mensalidade = $id_mensalidade
            ";
            mysqli_query($conecta, $sql_update_valor);
        }
            }
}

header("Location: turmas.php?slt_turma=$id_turma");
exit;
?>