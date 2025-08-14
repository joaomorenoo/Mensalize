<?php
include('../back/conecta.php');

$id_aluno = $_GET['id_aluno'];
$id_turma = $_GET['id_turma'];
$id_evento = $_GET['id_evento'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor_reembolso = floatval($_POST['valor_reembolso']);

    $sql_insert = "INSERT INTO reembolsos (id_aluno, id_turma, id_evento, valor) 
                   VALUES ($id_aluno, $id_turma, $id_evento, $valor_reembolso)";
    if (!mysqli_query($conecta, $sql_insert)) {
        echo "Erro ao registrar reembolso: " . mysqli_error($conecta);
        exit;
    }

    $sql_update = "UPDATE total_pago 
                   SET total_pagamentos = total_pagamentos - $valor_reembolso 
                   WHERE id_aluno = $id_aluno AND id_turma = $id_turma AND id_evento = $id_evento";

    if (!mysqli_query($conecta, $sql_update)) {
        echo "Erro ao atualizar total_pago: " . mysqli_error($conecta);
        exit;
    }

    $sql_desativar = "UPDATE grupo_alunos SET ativo = 0 WHERE id_turma = $id_turma AND id_aluno = $id_aluno";
    if (!mysqli_query($conecta, $sql_desativar)) {
        echo "Erro ao desativar aluno: " . mysqli_error($conecta);
        exit;
    }

    header("Location: turmas.php?slt_turma=$id_turma");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Reembolso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white p-4">
    <div class="container">
        <h3>Informe o valor a ser reembolsado ao aluno</h3>
        <form method="post">
            <div class="mb-3">
                <label for="valor_reembolso" class="form-label">Valor (R$)</label>
                <input type="number" step="0.01" name="valor_reembolso" id="valor_reembolso" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Confirmar Reembolso</button>
            <a href="turmas.php?slt_turma=<?= $id_turma ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
