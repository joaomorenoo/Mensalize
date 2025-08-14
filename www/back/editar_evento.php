<?php
include('conecta.php');

$id_evento = $_POST['id_evento'] ?? null;
$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$data_termino = $_POST['data_termino'] ?? null;
$valor_total = $_POST['valor_total'] ?? null;

header('Content-Type: application/json');

if (!$id_evento || !$nome === null) {
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos.']);
    exit;
}

$nome = mysqli_real_escape_string($conecta, $nome);
$descricao = mysqli_real_escape_string($conecta, $descricao);
$data_termino = $data_termino ? "'$data_termino'" : "NULL";

$sql = "UPDATE eventos SET 
    nome = '$nome',
    descricao = '$descricao',
    data_termino = $data_termino
    WHERE id_evento = $id_evento";

if (mysqli_query($conecta, $sql)) {
    header('location: ../front/descricaoevento.php?id=' . $id_evento);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar evento.']);
}
?>
