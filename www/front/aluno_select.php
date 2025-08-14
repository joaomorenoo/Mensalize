<?php
    include('menu.php');
    include('../back/conecta.php');
    session_start();

    if (isset($_GET["busca"])) {
        $_SESSION['nome'] = $_GET["busca"]; 
    }

    $nome_select = isset($_SESSION['nome']) ? $_SESSION['nome'] : '';
    $nome_select_esc = mysqli_real_escape_string($conecta, $nome_select);

    $sql_informacoes = "SELECT * FROM alunos WHERE nome = '$nome_select_esc'"; 
    $sql_consulta = mysqli_query($conecta, $sql_informacoes);
    $informacoes = mysqli_fetch_assoc($sql_consulta);

    $_SESSION['aluno_select'] = $informacoes['nome'] ?? '';

    $sql_mensalidades = "
        SELECT m.*, e.nome AS nome_evento,
            IFNULL(SUM(p.valor_pago), 0) AS total_pago,
            m.valor - IFNULL(SUM(p.valor_pago), 0) AS saldo_pendente
        FROM mensalidades m
        INNER JOIN eventos e ON m.id_evento = e.id_evento
        LEFT JOIN pagamento p ON p.id_mensalidade = m.id_mensalidade
        WHERE m.id_aluno = '{$informacoes['id_aluno']}'
        GROUP BY m.id_mensalidade
        ORDER BY m.data_pagamento ASC";
    $consulta_mensalidades = mysqli_query($conecta, $sql_mensalidades);

    $sqlPagamentosDetalhes = "
        SELECT p.id_pagamento, a.nome AS nome_aluno, p.data_pagamento, p.valor_pago
        FROM pagamento p
        INNER JOIN mensalidades m ON p.id_mensalidade = m.id_mensalidade
        INNER JOIN alunos a ON m.id_aluno = a.id_aluno
        WHERE m.id_aluno = '{$informacoes['id_aluno']}'
        ORDER BY p.data_pagamento DESC
    ";
    $resPagamentosDetalhes = mysqli_query($conecta, $sqlPagamentosDetalhes);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalhes do Aluno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .bkg-alunosel {
            display: flex;
            flex-direction: column;
            background: radial-gradient(circle, rgba(42, 123, 155, 1) 0%, rgba(2, 34, 43, 1) 100%);
            justify-content: center;
            align-items: center;
        }
        .container {
            display:flex;
            flex-direction: column;
            height: 90vh;
            width: 100vw;
        }
        .menus {
            background-color: #fdfdfd;
            height: 60%;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            border-radius: 12px;
            display: flex;
            gap: 10px;
            justify-content: space-evenly;
            align-items: center;
        }
        .card-aluno {
            background-color: #fdfdfd;
            color: #333;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 1200px;
            height: 40%;
            padding: 2.5rem;
            margin-bottom: 10px;
        }
        .card-aluno h1 {
            text-align: center;
            font-variant: small-caps;
            color: #144653;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        .card-aluno h3 {
            text-align: center;
            color: #19788f;
            margin-bottom: 2rem;
            font-weight: 500;
            font-size: 1.2rem;
        }
        .info-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 1.2rem;
        }
        .info-list h6 {
            flex: 1 1 45%;
            background-color: #e5f6fa;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 1rem;
            color: #04444e;
            border-left: 5px solid #1b7b93;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .menus-lat-esq {
            background-color: #19788f;
            height: 90%;
            width: 30%;
            border-radius: 5px;
            box-shadow: 0 6px 20px rgb(0, 0, 0);
            margin-left: 10px;
            overflow-y: auto;
            padding: 10px;
        }
        .menus-lat-dir {
            background-color: #19788f;
            height: 90%;
            width: 68%;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;
            gap: 5px;
            border-radius: 5px;
            box-shadow: 0 6px 20px rgb(0, 0, 0);
            margin-right: 10px;
            overflow-y: auto;
            padding: 10px;
        }
        .informacoes-pag {
            height: 60%;
            width: 90%;
            background-color: #e5f6fa;
            border-radius: 5px;
            box-shadow: 0 6px 20px rgb(0, 0, 0);
        }
        .logs-usuario {
            height: 90%;
            width: 100%;
            background-color: #e5f6fa;
            border-radius: 5px;
            box-shadow: 0 6px 20px rgb(0, 0, 0);
            padding: 20px;
            overflow-y: auto;
        }

        .menu-down {
            width: 100%;
        }
        .pagamentos-container {
            background: #fff;
            padding: 10px;
            border-radius: 6px;
            max-height: 100%;
            overflow-y: auto;
        }
        .pagamentos-container h3 {
            margin-bottom: 15px;
            color: #144653;
            border-bottom: 2px solid #19788f;
            padding-bottom: 5px;
        }
        .lista-pagamentos {
            max-height: 60vh;
            overflow-y: auto;
        }
        .item-pagamento {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #e5f6fa;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .info-pagamento span {
            display: block;
            font-size: 0.9rem;
            color: #04444e;
        }
        .nome-aluno {
            font-weight: 600;
        }
        .btn-imprimir {
            background-color: #19788f;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-imprimir:hover {
            background-color: #155e71;
        }
    </style>
</head>
<body class="bkg-alunosel">
    <div class="container">
        <div class="card-aluno">
            <h1><?php echo htmlspecialchars($nome_select); ?></h1>
            <div class="info-list">
                <?php if ($informacoes): ?>
                    <h6>CPF: <?php echo htmlspecialchars($informacoes['cpf']); ?></h6>
                    <h6>Email: <?php echo htmlspecialchars($informacoes['email']); ?></h6>
                    <h6>Telefone: <?php echo htmlspecialchars($informacoes['telefone']); ?></h6>
                    <h6>Data de Nascimento: <?php echo date('d/m/Y', strtotime($informacoes['data_nasc'])); ?></h6>
                <?php else: ?>
                    <h6 style="flex: 1 1 100%; text-align: center;">Informações não encontradas.</h6>
                <?php endif; ?>
            </div>
        </div>

        <div class="menus">
            <div class="menus-lat-esq">
                <h5 style="color: white; text-align:center; margin-top:10px;">Mensalidades</h5>
                <ul style="list-style: none; padding: 10px; color: white;">
                    <?php if(mysqli_num_rows($consulta_mensalidades) > 0): ?>
                        <?php while($mensalidade = mysqli_fetch_assoc($consulta_mensalidades)): ?>
                            <li style="margin-bottom: 10px; background-color: #155e71; padding: 8px; border-radius: 6px;">
                                <strong><?php echo htmlspecialchars($mensalidade['nome_evento']); ?></strong><br>
                                Valor: R$ <?php echo number_format($mensalidade['valor'], 2, ',', '.'); ?><br>
                                Pago: R$ <?php echo number_format($mensalidade['total_pago'], 2, ',', '.'); ?><br>
                                Saldo: R$ <?php echo number_format($mensalidade['saldo_pendente'], 2, ',', '.'); ?><br>
                                Status: <?php echo ucfirst($mensalidade['status']); ?><br>
                                <?php if ($mensalidade['saldo_pendente'] > 0 && $mensalidade['ativo'] == 1): ?>
                                    <button 
                                        onclick="abrirModalPagamento(
                                            <?php echo $mensalidade['id_mensalidade']; ?>, 
                                            <?php echo $mensalidade['saldo_pendente']; ?>)" 
                                        style="margin-top:5px; padding:5px 10px; border:none; background-color:#e5f6fa; color:#155e71; border-radius:5px; cursor:pointer;">
                                        Adicionar Pagamento
                                    </button>
                                <?php elseif ($mensalidade['ativo'] == 0): ?>
                                    <span style="color: gray;">Mensalidade Inativa</span>
                                <?php else: ?>
                                    <span style="color:lightgreen;">Pago</span>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>Sem mensalidades cadastradas.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="menus-lat-dir">
                <div class="logs-usuario">
                    <div class="menu-down">
                        <div class="pagamentos-container">
                            <h3>Pagamentos Realizados</h3>
                            <div class="lista-pagamentos" id="listaPagamentos" tabindex="0">
                            <?php if (mysqli_num_rows($resPagamentosDetalhes) > 0): ?>
                                <?php while ($pag = mysqli_fetch_assoc($resPagamentosDetalhes)): 
                                    $data = $pag['data_pagamento'];
                                    $dataFormatada = ($data && $data !== '0000-00-00 00:00:00') ? date('d/m/Y H:i', strtotime($data)) : 'Data inválida';
                                ?>
                                    <div class="item-pagamento">
                                        <div class="info-pagamento">
                                            <span class="nome-aluno"><?php echo htmlspecialchars($pag['nome_aluno']); ?></span>
                                            <span class="data-pagamento"><?php echo $dataFormatada; ?></span>
                                            <span class="valor-pago">R$ <?php echo number_format($pag['valor_pago'], 2, ',', '.'); ?></span>
                                        </div>
                                        <button class="btn-imprimir" onclick="window.open('recibo.php?id_pagamento=<?php echo $pag['id_pagamento']; ?>', '_blank')">Imprimir</button>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>Nenhum pagamento registrado para este aluno.</p>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalPagamento" style="display:none; position:fixed; z-index:999; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.7);">
        <div style="background:white; margin:10% auto; padding:20px; border-radius:10px; width:400px; position:relative;">
            <span style="position:absolute; top:10px; right:20px; cursor:pointer; color:red;" onclick="fecharModalPagamento()">&times;</span>
            <h4 style="text-align:center;">Adicionar Pagamento</h4>
            <form id="formPagamento">
                <input type="hidden" id="idMensalidade" name="idMensalidade">
                <label>Valor do pagamento:</label>
                <input type="number" step="0.01" min="0.01" name="valor_pago" id="valor_pago" class="form-control" required style="border: 1px solid black"><br>
                <label>Forma do pagamento:</label>
                <select name="forma_pag" id="forma_pag" class="form-control" required style="border: 1px solid black">
                    <option value="">Selecione o método de pagamento</option>
                    <option value="pix">PIX</option>
                    <option value="dinheiro">Dinheiro</option>
                    <option value="credito">Crédito</option>
                    <option value="debito">Débito</option>
                </select>
                <br>
                <button type="submit" class="btn btn-success w-100">Confirmar Pagamento</button>
            </form>
        </div>
    </div>

    <script>
        function abrirModalPagamento(idMensalidade, saldoPendente) {
            document.getElementById('idMensalidade').value = idMensalidade;
            const campoValor = document.getElementById('valor_pago');
            campoValor.value = '';
            campoValor.max = saldoPendente;
            campoValor.placeholder = `Máximo: R$ ${saldoPendente.toFixed(2)}`;
            document.getElementById('modalPagamento').style.display = 'block';
        }

        function fecharModalPagamento() {
            document.getElementById('modalPagamento').style.display = 'none';
        }

        document.getElementById('formPagamento').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const idMensalidade = document.getElementById('idMensalidade').value;
            const valorPago = parseFloat(document.getElementById('valor_pago').value);
            const forma_pag = document.getElementById('forma_pag').value;
            const maxPermitido = parseFloat(document.getElementById('valor_pago').max);

            if (valorPago > maxPermitido) {
                alert(`O valor não pode ser maior que o saldo pendente de R$ ${maxPermitido.toFixed(2)}`);
                return;
            }

            fetch('../back/adicionar_pagamento.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id_mensalidade=${idMensalidade}&valor_pago=${valorPago}&forma_pag=${forma_pag}`
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    fecharModalPagamento();
                    window.location.reload();
                }
            })
            .catch(error => {
                alert('Erro ao processar pagamento.');
                console.error('Erro:', error);
            });
        });
    </script>
</body>
</html>
