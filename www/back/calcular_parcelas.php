<?php
include('conecta.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_evento = $_POST['id_evento'];

    $sql_evento = "SELECT valor_total FROM eventos WHERE id_evento = '$id_evento'";
    $resultado_evento = mysqli_query($conecta, $sql_evento);

    if (!$resultado_evento || mysqli_num_rows($resultado_evento) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Evento não encontrado.']);
        exit;
    }

    $evento = mysqli_fetch_assoc($resultado_evento);
    $valor_total = $evento['valor_total'];

    $sql_alunos = "SELECT COUNT(DISTINCT ga.id_aluno) AS total_alunos
                   FROM grupo_alunos ga
                   INNER JOIN evento_turma et ON ga.id_turma = et.id_turma
                   WHERE et.id_evento = '$id_evento' AND ga.ativo = 1";
    $resultado_alunos = mysqli_query($conecta, $sql_alunos);
    $dados_alunos = mysqli_fetch_assoc($resultado_alunos);
    $qtd_alunos = $dados_alunos['total_alunos'];

    if ($qtd_alunos <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Nenhum aluno ativo encontrado nas turmas associadas ao evento.']);
        exit;
    }

    $valor_parcela = number_format($valor_total / $qtd_alunos, 2, '.', '');

    echo json_encode([
        'status' => 'success',
        'qtd_alunos' => $qtd_alunos,
        'valor_total' => $valor_total,
        'valor_parcela' => $valor_parcela
    ]);
}
?>
