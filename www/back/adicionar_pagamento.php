<?php
include('conecta.php');

$id_mensalidade = $_POST['id_mensalidade'] ?? '';
$valor_pago = $_POST['valor_pago'] ?? '';
$forma_pag = $_POST['forma_pag'] ?? '';

if (!$id_mensalidade || !$valor_pago || !$forma_pag) {
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos.']);
    exit;
}

$sql_saldo = "SELECT (m.valor - IFNULL(SUM(p.valor_pago), 0)) AS saldo_pendente
    FROM mensalidades m
    LEFT JOIN pagamento p ON m.id_mensalidade = p.id_mensalidade
    WHERE m.id_mensalidade = '$id_mensalidade'
    GROUP BY m.id_mensalidade";

$result = mysqli_query($conecta, $sql_saldo);
if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao consultar saldo pendente: ' . mysqli_error($conecta)]);
    exit;
}
$dado = mysqli_fetch_assoc($result);

if (!$dado) {
    echo json_encode(['status' => 'error', 'message' => 'Mensalidade não encontrada.']);
    exit;
}

$saldo_pendente = $dado['saldo_pendente'];

if ($valor_pago > $saldo_pendente) {
    echo json_encode(['status' => 'error', 'message' => 'Valor excede o saldo pendente.']);
    exit;
}

$sql = "INSERT INTO pagamento (id_mensalidade, valor_pago, data_pagamento, forma_pagamento) 
        VALUES ('$id_mensalidade', '$valor_pago', NOW(), '$forma_pag')";

if (mysqli_query($conecta, $sql)) {
    $sql_info = "SELECT m.id_evento, ga.id_turma
                 FROM mensalidades m
                 JOIN grupo_alunos ga ON m.id_aluno = ga.id_aluno AND ga.ativo = 1
                 WHERE m.id_mensalidade = '$id_mensalidade'
                 LIMIT 1";

    $result_info = mysqli_query($conecta, $sql_info);
    if (!$result_info) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar turma e evento: ' . mysqli_error($conecta)]);
        exit;
    }
    $info = mysqli_fetch_assoc($result_info);

    $id_turma = $info['id_turma'] ?? null;
    $id_evento = $info['id_evento'] ?? null;

    if (!$id_turma || !$id_evento) {
        echo json_encode(['status' => 'error', 'message' => 'id_turma ou id_evento não encontrados.']);
        exit;
    }

    $check_turma = mysqli_query($conecta, "SELECT 1 FROM turma WHERE id_turma = '$id_turma' LIMIT 1");
    if (!$check_turma || mysqli_num_rows($check_turma) === 0) {
        echo json_encode(['status' => 'error', 'message' => "id_turma '$id_turma' não existe em turma."]);
        exit;
    }

    $check_evento = mysqli_query($conecta, "SELECT 1 FROM eventos WHERE id_evento = '$id_evento' LIMIT 1");
    if (!$check_evento || mysqli_num_rows($check_evento) === 0) {
        echo json_encode(['status' => 'error', 'message' => "id_evento '$id_evento' não existe em eventos."]);
        exit;
    }

    $sql_verifica = "SELECT total_pagamentos 
                     FROM total_pago 
                     WHERE id_turma = '$id_turma' AND id_evento = '$id_evento'";
    $result_verifica = mysqli_query($conecta, $sql_verifica);

    if (!$result_verifica) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao verificar total_pago: ' . mysqli_error($conecta)]);
        exit;
    }

    if (mysqli_num_rows($result_verifica) > 0) {
        $sql_update = "UPDATE total_pago 
                       SET total_pagamentos = total_pagamentos + $valor_pago 
                       WHERE id_turma = '$id_turma' AND id_evento = '$id_evento'";
        if (!mysqli_query($conecta, $sql_update)) {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar total_pago: ' . mysqli_error($conecta)]);
            exit;
        }
    } else {
        $sql_insert = "INSERT INTO total_pago (id_turma, id_evento, total_pagamentos) 
                       VALUES ('$id_turma', '$id_evento', '$valor_pago')";
        if (!mysqli_query($conecta, $sql_insert)) {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao inserir em total_pago: ' . mysqli_error($conecta)]);
            exit;
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Pagamento registrado com sucesso.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar pagamento: ' . mysqli_error($conecta)]);
}
?>