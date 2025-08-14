<?php
include('conecta.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id_evento']) || empty($_POST['id_evento'])) {
        echo json_encode(['status' => 'error', 'message' => 'ID do evento não foi enviado.']);
        exit;
    }

    $id_evento = mysqli_real_escape_string($conecta, $_POST['id_evento']);

    $sql_evento = "SELECT valor_total FROM eventos WHERE id_evento = '$id_evento'";
    $resultado_evento = mysqli_query($conecta, $sql_evento);

    if (!$resultado_evento || mysqli_num_rows($resultado_evento) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Evento não encontrado.']);
        exit;
    }

    $evento = mysqli_fetch_assoc($resultado_evento);
    $valor_total = (float)$evento['valor_total'];

    $sql_alunos = "
        SELECT DISTINCT ga.id_aluno, ga.id_turma
        FROM grupo_alunos ga
        INNER JOIN evento_turma et ON ga.id_turma = et.id_turma
        WHERE et.id_evento = '$id_evento' AND ga.ativo = 1
    ";
    $resultado_alunos = mysqli_query($conecta, $sql_alunos);

    if (!$resultado_alunos) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar alunos ativos.']);
        exit;
    }

    $qtd_alunos = mysqli_num_rows($resultado_alunos);

    if ($qtd_alunos <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Nenhum aluno ativo encontrado nas turmas associadas.']);
        exit;
    }

    $verifica = mysqli_query($conecta, "SELECT COUNT(*) as total FROM mensalidades WHERE id_evento = '$id_evento'");
    $check = mysqli_fetch_assoc($verifica);
    if ($check['total'] > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Mensalidades já foram geradas para este evento.']);
        exit;
    }

    $valor_parcela = round($valor_total / $qtd_alunos, 2);


    while ($aluno = mysqli_fetch_assoc($resultado_alunos)) {
        $id_aluno = $aluno['id_aluno'];
        $id_turma = $aluno['id_turma'];

        $sql_insert = "
            INSERT INTO mensalidades 
                (id_aluno, id_evento, valor, data_pagamento, data_alteracao, status, id_turma, ativo)
            VALUES 
                ('$id_aluno', '$id_evento', '$valor_parcela', NULL, NOW(), 'pendente', '$id_turma', 1)
        ";
        
        if (!mysqli_query($conecta, $sql_insert)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Erro ao inserir mensalidade: ' . mysqli_error($conecta)
            ]);
            exit;
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Mensalidades geradas com sucesso para alunos ativos.'
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Requisição inválida.']);
}
?>
