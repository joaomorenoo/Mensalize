<?php
include('conecta.php');

$id_evento = $_POST['id_evento'] ?? '';

if (!$id_evento) {
    echo json_encode(['status' => 'error', 'message' => 'ID do evento não informado.']);
    exit;
}

$sql_evento = "SELECT valor_total FROM eventos WHERE id_evento = '$id_evento'";
$result_evento = mysqli_query($conecta, $sql_evento);
$evento = mysqli_fetch_assoc($result_evento);

if (!$evento) {
    echo json_encode(['status' => 'error', 'message' => 'Evento não encontrado.']);
    exit;
}

$sql_alunos = "
    SELECT ga.id_aluno 
    FROM grupo_alunos ga
    INNER JOIN evento_turma et ON et.id_turma = ga.id_turma
    WHERE et.id_evento = '$id_evento' AND ga.ativo = 1
";
$result_alunos = mysqli_query($conecta, $sql_alunos);
$alunos_ativos = [];

while ($row = mysqli_fetch_assoc($result_alunos)) {
    $alunos_ativos[] = $row['id_aluno'];
}

$qtd_ativos = count($alunos_ativos);

if ($qtd_ativos == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Nenhum aluno ativo encontrado.']);
    exit;
}

$valor_total = $evento['valor_total'];
$valor_parcela = $valor_total / $qtd_ativos;

foreach ($alunos_ativos as $id_aluno) {
    $update = "
        UPDATE mensalidades 
        SET valor = '$valor_parcela' 
        WHERE id_evento = '$id_evento' AND id_aluno = '$id_aluno'
    ";
    mysqli_query($conecta, $update);
}

echo json_encode([
    'status' => 'success',
    'message' => "Evento atualizado com sucesso. Total de alunos ativos: $qtd_ativos. Valor por aluno: R$ " . number_format($valor_parcela, 2, ',', '.')
]);
?>
