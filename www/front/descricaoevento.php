<?php
    include('../back/conecta.php');
    include('menu.php');

    $id_evento = $_GET['id'];

    $sql = "SELECT * FROM `eventos` WHERE id_evento = '$id_evento'";
    $consultaEventos = mysqli_query($conecta, $sql);
    $exibe_evento = mysqli_fetch_assoc($consultaEventos);

    $sqlTurmas = "SELECT * FROM turma";
    $consultaTurmas = mysqli_query($conecta, $sqlTurmas);

    $sql_turmas = "SELECT t.id_turma, t.curso, t.ano, t.semestre FROM evento_turma et INNER JOIN turma t ON et.id_turma = t.id_turma WHERE et.id_evento = '$id_evento'";
    $consulta_Turmas = mysqli_query($conecta, $sql_turmas);

    $sqlMensalidades = "SELECT COUNT(*) as total FROM mensalidades WHERE id_evento = '$id_evento'";
    $resultMensalidades = mysqli_query($conecta, $sqlMensalidades);
    $rowMensalidades = mysqli_fetch_assoc($resultMensalidades);
    $mensalidadesGeradas = $rowMensalidades['total'] > 0;

    $valorTotalEvento = (float)$exibe_evento['valor_total'];

    $sqlPagamentos = "SELECT SUM(total_pagamentos) as total_pago FROM total_pago WHERE id_evento = '$id_evento'";
    $consultaPagamentos = mysqli_query($conecta, $sqlPagamentos);
    $dadosPagamento = mysqli_fetch_assoc($consultaPagamentos);
    $valorPago = (float)($dadosPagamento['total_pago'] ?? 0);
    $valorRestante = max(0, $valorTotalEvento - $valorPago);

    $sqlPorTurma = "SELECT t.curso, t.ano, t.semestre, tp.total_pagamentos FROM total_pago tp INNER JOIN turma t ON tp.id_turma = t.id_turma WHERE tp.id_evento = '$id_evento' ORDER BY t.ano DESC, t.semestre DESC";
    $resPagamentosTurma = mysqli_query($conecta, $sqlPorTurma);

    $labelsTurma = [];
    $valoresTurma = [];

    while ($linha = mysqli_fetch_assoc($resPagamentosTurma)) {
        $labelsTurma[] = $linha['curso'];
        $valoresTurma[] = (float)$linha['total_pagamentos'];
    }

    $sqlPagamentosDetalhes = "SELECT p.id_pagamento, a.nome AS nome_aluno, p.data_pagamento, p.valor_pago FROM pagamento p INNER JOIN mensalidades m ON p.id_mensalidade = m.id_mensalidade INNER JOIN alunos a ON m.id_aluno = a.id_aluno WHERE m.id_evento = '$id_evento' ORDER BY p.data_pagamento DESC";
    $resPagamentosDetalhes = mysqli_query($conecta, $sqlPagamentosDetalhes);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Evento</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="../back/descricaoevento.css" rel="stylesheet" />
</head>
<style>
    
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
.container {
    height: 90vh;
    width: 100vw;
    display: flex;
    align-items: center;
    flex-direction: column;
}
.card-aluno {
    background-color: #fdfdfd;
    color: #333;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    width: 100%;
    max-width: 1200px;
    height: 100%;
    max-height: 100px;
    padding: 2.5rem;
}

.info-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 0.2rem;
}
.info-list h6 {
    flex: 1 1 30%;
    background-color: #e5f6fa;
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 1rem;
    color: #04444e;
    border-left: 5px solid #1b7b93;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
@media (max-width: 576px) {
    .info-list h6 {
        flex: 1 1 100%;
    }
}
.menus {
    display: flex;
    justify-content: space-evenly;
    align-items: center;
    flex-direction: row;
    height: 80vh;
    max-width: 1200px;
    width: 100%;
    margin-top: 10px;
    background-color: rgb(248, 254, 255);
    border-radius: 5px;
}
.left {
    background-color: #19788f;
    height: 90%;
    width: 30%;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    color: white;
    padding: 10px;
    overflow-y: auto;
}
.right {
    background-color: #19788f;
    height: 90%;
    width: 65%;
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
    align-items: center;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    overflow: hidden;
}
.menu-top {
    display: flex;
    align-items: center;
    gap: 10px;
    height: 60%;
    width: 90%;
    background-color: #e5f6fa;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    border-radius: 5px;
}
.menu-down {
    height: 35%;
    width: 90%;
    background-color: #e5f6fa;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    border-radius: 5px;
}
.acoes-financeiro {
    width: 20%;
    height: 90%;
    background-color: white;
    margin-left: 15px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 10px;
}
.acoes-financeiro button {
    padding: 8px 12px;
    background-color: #19788f;
    border: none;
    color: white;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 90%;
    height: 20%;
}
.acoes-financeiro button:hover {
    background-color: #155e71;
}
.graph {
    display: flex;
    width: 75%;
    height: 90%;
    background-color: white;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    justify-content: space-around;
    padding: 10px;
}
.pizza {
    margin-bottom: 10px;
    width: 40%;
}
.barra {
    margin-bottom: 10px;
    width: 59%;
    height: 100%;
}
.pagamentos-container {
    flex: 80%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    height: 100%;
}
.pagamentos-container h3{
    margin-left: 10px;
    margin-top: auto;
}
.lista-pagamentos {
    overflow-y: auto;
    max-height: 180px;
    border: 1px solid #19788f;
    background-color: white;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    color: #155e71;
}
.item-pagamento {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    padding: 6px 8px;
    border-bottom: 1px solid #ddd;
}
.info-pagamento span {
    margin-right: 15px;
    font-weight: 500;
}
.btn-imprimir {
    padding: 6px 12px;
    background-color: #19788f;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(25, 120, 143, 0.4);
    font-weight: 600;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
}
.btn-imprimir:hover {
    background-color: #155e71;
}
.modal {
    display: none;
    position: fixed;
    z-index: 999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.7);
}
.modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border-radius: 10px;
    width: 50%;
    box-shadow: 0 6px 20px rgba(0,0,0,0.5);
}
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
.close:hover,
.close:focus {
    color: black;
}
.turmas-modal {
    background-color: #e5f6fa;
    padding: 10px;
    border-radius: 8px;
}
.turmas-modal select {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #19788f;
}
.turmas-modal button {
    background-color: #19788f;
    color: white;
    border: none;
    padding: 12px 20px;
    font-size: 1rem;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 10px;
    width: 100%;
    box-shadow: 0 4px 8px rgba(25, 120, 143, 0.4);
}
.turmas-modal button:hover {
    background-color: #155e71;
    box-shadow: 0 6px 12px rgba(21, 94, 113, 0.6);
}
#modal2 .modal-content,
#modal4 .modal-content {
    padding: 25px;
    max-width: 500px;
}
#modal2 h2,
#modal4 h2 {
    margin-bottom: 15px;
    color: #144653;
    text-align: center;
}
#modal2 label,
#modal4 label {
    font-weight: 600;
    color: #155e71;
    display: block;
    margin-bottom: 4px;
}
#modal2 input,
#modal2 textarea,
#modal4 input {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #19788f;
    margin-bottom: 12px;
    font-size: 1rem;
    background-color: #f9f9f9;
    color: #333;
    transition: border-color 0.3s;
}
#modal2 input:focus,
#modal2 textarea:focus,
#modal4 input:focus {
    border-color: #155e71;
    outline: none;
    background-color: #fff;
}
#modal2 button[type="submit"],
#modal4 button[type="submit"],
#modal3 button[type="submit"] {
    background-color: #19788f;
    color: white;
    border: none;
    padding: 12px;
    font-size: 1rem;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 8px rgba(25, 120, 143, 0.4);
}
#modal2 button[type="submit"]:hover,
#modal4 button[type="submit"]:hover,
#modal3 button[type="submit"]:hover {
    background-color: #155e71;
    box-shadow: 0 6px 12px rgba(21, 94, 113, 0.6);
}
#modal2 .close,
#modal4 .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
#modal2 .close:hover,
#modal2 .close:focus,
#modal4 .close:hover,
#modal4 .close:focus {
    color: black;
}
.acoes-financeiro button:disabled {
    background-color: #d3d3d3;
    color: #7a7a7a;
    cursor: not-allowed;
    box-shadow: none;
    border: 1px solid #999;
}

</style>
<body style="background: radial-gradient(circle, rgba(42, 123, 155, 1) 0%, rgba(2, 34, 43, 1) 100%);">
    <div class="container">
        <div class="card-aluno">
            <div class="info-list">
                <?php if ($exibe_evento): ?>
                    <h6><strong>Nome: </strong><?php echo htmlspecialchars($exibe_evento['nome']); ?></h6>
                    <h6><strong>Data do término: </strong><?php echo date('d/m/Y', strtotime($exibe_evento['data_termino'])); ?></h6>
                    <h6><strong>Valor: </strong>R$ <?php echo number_format($exibe_evento['valor_total'], 2, ',', '.'); ?></h6>
                <?php else: ?>
                    <h6 style="flex: 1 1 100%; text-align: center;">Informações não encontradas.</h6>
                <?php endif; ?>
            </div>
        </div>

        <div class="menus">
            <div class="left">
                <h3>Turmas do Evento</h3>
                <ul>
                    <?php if(mysqli_num_rows($consulta_Turmas) > 0): ?>
                        <?php while($turma_adicionadas = mysqli_fetch_assoc($consulta_Turmas)): ?>
                            <li><?php echo htmlspecialchars($turma_adicionadas['curso'] . " | Ano: " . $turma_adicionadas['ano'] . "/" . $turma_adicionadas['semestre']); ?></li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>Nenhuma turma associada.</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="right">
                <div class="menu-top">
                    <div class="acoes-financeiro">
                        <button id="openModal1">Adicionar</button>
                        <button id="openModal2">Editar</button>
                        <button id="openModal3" <?php echo ($mensalidadesGeradas ? 'disabled' : ''); ?>>Gerar</button>
                        <button id="openModal4">Atualizar</button>
                    </div>
                    <div class="graph">
                        <div class="pizza">
                            <canvas id="graficoPizza"></canvas>
                        </div>
                        <div class="barra">
                            <canvas id="graficoBarra"></canvas>
                        </div>
                    </div>
                </div>
                <div class="menu-down">
                    <div class="pagamentos-container">
                        <div class="lista-pagamentos" id="listaPagamentos">
                            <?php
                            if (mysqli_num_rows($resPagamentosDetalhes) > 0):
                                mysqli_data_seek($resPagamentosDetalhes, 0);
                                while ($pag = mysqli_fetch_assoc($resPagamentosDetalhes)):
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
                            <?php
                                endwhile;
                            else:
                            ?>
                                <p>Nenhum pagamento registrado para este evento.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal1" class="modal">
        <div class="modal-content">
            <span class="close" data-modal="modal1">&times;</span>
            <h2>Adicionar Turma</h2>
            <div class="turmas-modal">
                <label for="turmas-modal">Selecione uma turma:</label>
                <select name="turmas-modal" id="turmas-modal">
                    <option value="">Selecione uma turma</option>
                    <?php
                    $resSelectTurmas = mysqli_query($conecta, "SELECT * FROM turma order by curso asc");
                    while ($turma = mysqli_fetch_assoc($resSelectTurmas)) {
                        echo '<option value="'. $turma['id_turma'] .'">'. htmlspecialchars($turma['curso'] . " | Ano: " . $turma['ano'] . "/" . $turma['semestre']) .'</option>';
                    }
                    ?>
                </select>
                <button onclick="adicionarTurma()">Adicionar Turma</button>
            </div>
        </div>
    </div>

    <div id="modal2" class="modal">
        <div class="modal-content">
            <span class="close" data-modal="modal2">&times;</span>
            <h2>Editar Evento</h2>
            <form id="formEditarEvento" method="post" action="../back/editar_evento.php">
                <input type="hidden" name="id_evento" value="<?php echo $id_evento; ?>">
                <label for="nomeEvento">Nome</label>
                <input type="text" id="nomeEvento" name="nome" value="<?php echo htmlspecialchars($exibe_evento['nome']); ?>" required>
                <label for="descricaoEvento">Descrição</label>
                <textarea id="descricaoEvento" name="descricao" required><?php echo htmlspecialchars($exibe_evento['descricao']); ?></textarea>
                <label for="dataTerminoEvento">Data de término</label>
                <input type="date" id="dataTerminoEvento" name="data_termino" value="<?php echo $exibe_evento['data_termino']; ?>" required>
                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>

    <div id="modal3" class="modal">
        <div class="modal-content">
            <span class="close" data-modal="modal3">&times;</span>
            <h2>Gerar Mensalidades</h2>
            <p>Você tem certeza que deseja gerar as mensalidades para este evento? Isso não poderá ser desfeito.</p>
            <div id="infoParcelas" style="margin-top: 15px;"></div>
            <button id="btnGerarMensalidades">Confirmar</button>
        </div>
    </div>

    <div id="modal4" class="modal">
        <div class="modal-content">
            <span class="close" data-modal="modal4">&times;</span>
            <h2>Adicionar Valor ao Evento</h2>
            <form id="formAtualizarValor">
                <label for="valor_extra">Valor a adicionar (R$):</label>
                <input type="number" step="0.01" id="valor_extra" name="valor_extra" required>
                <button type="submit">Atualizar Evento</button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const valorPago = <?php echo json_encode($valorPago); ?>;
            const valorRestante = <?php echo json_encode($valorRestante); ?>;
            const valoresTurma = <?php echo json_encode($valoresTurma); ?>;
            const labelsTurma = <?php echo json_encode($labelsTurma); ?>;

            const ctxPizza = document.getElementById('graficoPizza').getContext('2d');
            new Chart(ctxPizza, {
                type: 'pie',
                data: {
                    labels: ['Valor Pago', 'Valor Restante'],
                    datasets: [{
                        data: [valorPago, valorRestante],
                        backgroundColor: ['rgba(40, 167, 69, 0.7)', 'rgba(220, 53, 69, 0.7)'],
                        borderColor: ['rgba(40, 167, 69, 1)', 'rgba(220, 53, 69, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Progresso Financeiro do Evento',
                            font: { size: 18 }
                        }
                    }
                }
            });

            const ctxBarra = document.getElementById('graficoBarra').getContext('2d');
            new Chart(ctxBarra, {
                type: 'line',
                data: {
                    labels: labelsTurma,
                    datasets: [{
                        label: 'Valor Pago por Turma (R$)',
                        data: valoresTurma,
                        backgroundColor: 'rgba(25, 120, 143, 0.7)',
                        borderColor: 'rgba(25, 120, 143, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Contribuição Financeira por Turma',
                            font: { size: 18 }
                        }
                    }
                }
            });

            ['1', '2', '3', '4'].forEach(num => {
                const btn = document.getElementById(`openModal${num}`);
                const modal = document.getElementById(`modal${num}`);
                const span = modal.querySelector('.close');

                if (btn && modal && span) {
                    btn.onclick = () => modal.style.display = "block";
                    span.onclick = () => modal.style.display = "none";
                }

                window.onclick = function (event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                };
            });

            document.getElementById('openModal3').addEventListener('click', function () {
                const modal3 = document.getElementById('modal3');
                const infoParcelas = document.getElementById('infoParcelas');
                modal3.style.display = "block";
                infoParcelas.innerHTML = '<p>Calculando...</p>';

                const idEvento = "<?php echo $id_evento; ?>";

                fetch('../back/calcular_parcelas.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id_evento=${idEvento}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        infoParcelas.innerHTML = `
                            <p><strong>Quantidade de alunos:</strong> ${data.qtd_alunos}</p>
                            <p><strong>Valor total do evento:</strong> R$ ${parseFloat(data.valor_total).toFixed(2).replace('.', ',')}</p>
                            <p><strong>Valor por aluno:</strong> R$ ${parseFloat(data.valor_parcela).toFixed(2).replace('.', ',')}</p>
                        `;
                    } else {
                        infoParcelas.innerHTML = `<p style="color: red;">${data.message}</p>`;
                    }
                })
                .catch(error => {
                    infoParcelas.innerHTML = `<p style="color: red;">Erro ao calcular.</p>`;
                    //console.error('Erro:', error);
                });
            });

            document.getElementById('btnGerarMensalidades').addEventListener('click', () => {
                const idEvento = "<?php echo $id_evento; ?>";

                fetch('../back/gerar_parcelas.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id_evento=${idEvento}`
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === 'success') {
                        document.getElementById('modal3').style.display = "none";
                        window.location.reload();
                    }
                })
                .catch(() => alert("Erro ao gerar mensalidades."));
            });

            window.adicionarTurma = function () {
                const select = document.getElementById('turmas-modal');
                const idTurma = select.value;
                const idEvento = "<?php echo $id_evento; ?>";

                if (!idTurma) {
                    alert("Por favor, selecione uma turma.");
                    return;
                }

                fetch('../back/adicionar_turma_evento.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id_turma=${idTurma}&id_evento=${idEvento}`
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === 'success') {
                        document.getElementById('modal1').style.display = "none";
                        window.location.reload();
                    }
                })
                .catch(() => alert("Erro ao adicionar turma."));
            };
            document.getElementById('formAtualizarValor').addEventListener('submit', function (e) {
                e.preventDefault();

                const valorExtra = document.getElementById('valor_extra').value;
                const idEvento = "<?php echo $id_evento; ?>";

                fetch('../back/atualizar_valor_evento.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id_evento=${idEvento}&valor_extra=${valorExtra}`
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === 'success') {
                        document.getElementById('modal4').style.display = "none";
                        window.location.reload();
                    }
                })
                .catch(error => {
                    alert("Erro ao atualizar valor do evento.");
                    console.error('Erro:', error);
                });
            });
        });
    </script>
</body>
</html>
