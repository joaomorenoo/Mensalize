<?php
include('conecta.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_turma = $_POST['id_turma'] ?? '';
    $id_evento = $_POST['id_evento'] ?? '';

    if (empty($id_turma) || empty($id_evento)) {
        echo json_encode(['status' => 'error', 'message' => 'Dados incompletos.']);
        exit;
    }

    $id_turma = mysqli_real_escape_string($conecta, $id_turma);
    $id_evento = mysqli_real_escape_string($conecta, $id_evento);

    $sqlCheck = "SELECT 1 FROM evento_turma WHERE id_turma = '$id_turma' AND id_evento = '$id_evento' LIMIT 1";
    $resultCheck = mysqli_query($conecta, $sqlCheck);

    if (!$resultCheck) {
        echo json_encode(['status' => 'error', 'message' => 'Erro na consulta ao banco.']);
        exit;
    }

    if (mysqli_num_rows($resultCheck) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Turma já está associada a este evento.']);
        exit;
    }

    $sqlInsert = "INSERT INTO evento_turma (id_turma, id_evento) VALUES ('$id_turma', '$id_evento')";
    if (mysqli_query($conecta, $sqlInsert)) {
        echo json_encode(['status' => 'success', 'message' => 'Turma adicionada com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar turma: ' . mysqli_error($conecta)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido.']);
}
