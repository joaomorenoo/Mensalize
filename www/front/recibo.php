<?php
session_start();
include('../back/conecta.php');

if (!isset($_GET['id_pagamento'])) {
    echo "ID do pagamento não informado.";
    exit;
}

$id_pagamento = intval($_GET['id_pagamento']);

$sql = "
    SELECT 
        p.id_pagamento,
        p.data_pagamento,
        p.valor_pago,
        p.forma_pagamento,
        a.nome AS nome_aluno,
        e.nome AS nome_evento,
        e.descricao AS descricao_evento,
        t.curso,
        t.ano,
        t.semestre
    FROM pagamento p
    INNER JOIN mensalidades m ON p.id_mensalidade = m.id_mensalidade
    INNER JOIN alunos a ON m.id_aluno = a.id_aluno
    INNER JOIN eventos e ON m.id_evento = e.id_evento
    LEFT JOIN turma t ON m.id_turma = t.id_turma
    WHERE p.id_pagamento = $id_pagamento
";

$resultado = mysqli_query($conecta, $sql);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    echo "Pagamento não encontrado.";
    exit;
}

$dados = mysqli_fetch_assoc($resultado);
$usuario_recebedor = $_SESSION['nome_usuario'] ?? 'Não identificado';
$data_pagamento = date('d/m/Y H:i', strtotime($dados['data_pagamento']));
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" type="image/x-icon" href="http://191.30.24.21/cedup/ticedup124/joao/Mensalize/img/favicon.ico">
    <meta charset="UTF-8">
    <title>Recibo de Pagamento</title>
    <style>
        body {
            font-family: "Georgia", serif;
            background-color: #f9f9f9;
            padding: 40px;
        }

        .recibo-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 40px;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            font-size: 24px;
            text-transform: uppercase;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .info p {
            margin: 10px 0;
            font-size: 16px;
        }

        .assinaturas {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .assinatura {
            width: 45%;
            text-align: center;
        }

        .assinatura .linha {
            margin-top: 50px;
            border-top: 1px dashed #000;
            height: 1px;
        }

        .assinatura span {
            display: block;
            margin-top: 8px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #555;
        }

        @media print {
            .btn-voltar {
                display: none;
            }
        }

        .btn-voltar {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="recibo-container">
        <h1>Recibo de Pagamento</h1>

        <div class="info">
            <p><strong>Recebemos de:</strong> <?php echo htmlspecialchars($dados['nome_aluno']); ?></p>
            <p><strong>Evento:</strong> <?php echo htmlspecialchars($dados['nome_evento']); ?></p>
            <p><strong>Descrição do Evento:</strong> <?php echo htmlspecialchars($dados['descricao_evento']); ?></p>
            <p><strong>Turma:</strong> <?php echo htmlspecialchars($dados['curso'] . " - " . $dados['ano'] . "/" . $dados['semestre']); ?></p>
            <p><strong>Valor pago:</strong> R$ <?php echo number_format($dados['valor_pago'], 2, ',', '.'); ?></p>
            <p><strong>Forma de pagamento:</strong> <?php echo htmlspecialchars($dados['forma_pagamento']); ?></p>
            <p><strong>Data do pagamento:</strong> <?php echo $data_pagamento; ?></p>
            <p><strong>Recebido por:</strong> <?php echo htmlspecialchars($usuario_recebedor); ?></p>
        </div>

        <div class="assinaturas">
            <div class="assinatura">
                <div class="linha"></div>
                <span>Assinatura do Pagador</span>
            </div>
            <div class="assinatura">
                <div class="linha"></div>
                <span>Assinatura do Recebedor</span>
            </div>
        </div>

        <div class="footer">
            Documento gerado eletronicamente em <?php echo date('d/m/Y \à\s H:i'); ?>
        </div>
    </div>

</body>
</html>
