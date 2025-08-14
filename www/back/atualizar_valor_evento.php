<?php
include('conecta.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_evento = $_POST['id_evento'];
    $valor_extra = floatval($_POST['valor_extra']);

    if ($valor_extra <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Valor deve ser maior que zero.']);
        exit;
    }

    $updateEvento = "UPDATE eventos SET valor_total = valor_total + $valor_extra WHERE id_evento = $id_evento";
    mysqli_query($conecta, $updateEvento);

    $sqlQtd = "SELECT COUNT(*) as total FROM mensalidades WHERE id_evento = $id_evento and ativo = 1";
    $resQtd = mysqli_query($conecta, $sqlQtd);
    $qtd = mysqli_fetch_assoc($resQtd)['total'];

    if ($qtd > 0) {
        $valorPorParcela = $valor_extra / $qtd;

        $sqlUpdateMensalidades = "
            UPDATE mensalidades 
            SET valor = valor + $valorPorParcela
            WHERE id_evento = $id_evento and ativo = 1
        ";
        mysqli_query($conecta, $sqlUpdateMensalidades);
    }

    echo json_encode(['status' => 'success', 'message' => 'Valor adicionado ao evento com sucesso!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Requisição inválida.']);
}
