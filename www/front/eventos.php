<?php
    include('menu.php');
    include('../back/conecta.php');

?>
<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="http://191.30.24.21/cedup/ticedup124/joao/Mensalize/img/favicon.ico">
    <title>Mensalize</title>
</head>
<style>
    * {
        margin: 0px;
        padding: 0px;
        box-sizing: border-box;
    }
    #eventos {
        background-color: rgb(0, 25, 32);
        border: 1px solid white;
    }
    #bgk-eventos {
        background: radial-gradient(circle, rgba(42, 123, 155, 1) 0%, rgba(2, 34, 43, 1) 100%);
    }
    .container {
        display: flex;
        height: 90vh;
        width: 100vw;
        gap: 10px;
        justify-content: center;
        align-items: center;
        animation: slideUp 0.8s forwards;
    }
    .left {
        height: 90%;
        width: 30%;
        background-color: white;
        border: 2px solid #313e42;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.67);
        border-radius: 5px;
        text-align: center;
        overflow-y: auto;

    }
    .right {
        height: 90%;
        width: 60%;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .novo-evento {
        background-color: white;
        border: 2px solid #313e42;
        border-radius: 5px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.67);
        height: 50%;
        width: 100%;
        text-align: center;
        overflow-y: auto;
    }

    .novo-evento form p {
        margin: 5px 0;
        font-weight: bold;
        color: #313e42;
    }

    .novo-evento input {
        width: 80%;
        padding: 5px;
        border: 1px solid #313e42;
        border-radius: 5px;
    }

    .novo-evento button {
        border: none;
        background-color: rgb(38, 163, 0);
        height: 30px;
        width: 80px;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }
    #selecionadoAtivo:hover {
        text-decoration: underline;
    }

    .novo-evento button:hover {
        background-color: rgb(27, 114, 0);
    }
    .eventos-off {
        background-color: white;
        border: 2px solid #313e42;
        border-radius: 5px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.67);
        height: 50%;
        width: 100%;
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .inativos{
        background-color:rgb(187, 187, 187);
        width: 80%;
        height: 70%;
        align-self: center;
        border-radius: 5px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.67);
        overflow-y: auto;
    }
    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    .submit {
        border: none;
        background-color: #008000;
        height: 30px;
        width: 80px;
        color: white;
        border-radius: 5px;
    }
    .submit:hover{
        background-color: rgb(0, 68, 0);
    }
    #reset {
        border: none;
        background-color: rgb(209, 52, 17);
        height: 30px;
        width: 80px;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }
    #reset:hover {
        background-color: rgb(146, 38, 14);
    }
    .link-evento {
        text-decoration: none;
        color: black;
    }
    .link-evento:hover {
        text-decoration: underline;
        color: black; 
    }

</style>
<body id="bgk-eventos">
    <div class="container">
       <div class="left">
            <div class="ativos">
                <p style="background-color: #313e42; color: white;">EVENTOS ATIVOS</p>
                <?php
                    $sqlEventos = "SELECT * FROM eventos WHERE data_termino >= CURDATE()";
                    $consultaEventos = mysqli_query($conecta, $sqlEventos);
                    
                    while ($eventosAtivos = mysqli_fetch_assoc($consultaEventos)) {
                        echo "<a href='descricaoevento.php?id=" . $eventosAtivos['id_evento'] . "' style='text-decoration: none; color: inherit;'>";
                        echo "<div class='eventos-encontrados' id='selecionadoAtivo'><strong>" . $eventosAtivos['nome'] . "</strong></div>";
                        echo "<div class='eventos-encontrados'>Término: " . date('d/m/Y', strtotime($eventosAtivos['data_termino'])) . "</div>";
                        echo "<hr>";
                        echo "</a>";
                    }                    
                ?>
            </div>
        </div>
        <div class="right">
            <div class="novo-evento">
                <p style="background-color: #313e42; color: white;">CADASTRAR NOVO EVENTO</p>
                <form action="../back/novoEvento.php" method="post">
                     <p for="nome">Nome do Evento</p>
                     <input type="text" id="nome" name="nome" required><br>
                     <p for="descricao">Descrição</p>
                     <input type="text" id="descricao" name="descricao" required><br>
                     <p for="nome">Data Término do Evento</p>
                     <input type="date" id="data_termino" name="data_termino" required><br>
                     <p for="valor_total">Valor Total</p>
                     <input type="number" id="valor_total" name="valor_total" required onchange="setTwoNumberDecimal" step="0.05" value="0.00"/><br><br>

                    <button type="submit">Enviar</button>
                    <button type="reset" id="reset">Limpar</button>
                    <br><br>
                </form>
            </div>
            <div class="eventos-off">
                <div>
                    <p style="background-color: #313e42; color: white;">EVENTOS INATIVOS</p>
                    <form method="POST">
                        <label>Data Inicial</label>
                        <input type="date" name="data_inicial" required>
                        <label>Data Final</label>
                        <input type="date" name="data_final" required>
                        <button class="submit" type="submit">Filtrar</button>
                    </form>
                </div>
                <div class="inativos">
                <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['data_inicial']) && isset($_POST['data_final'])) {
                        $dataInicial = $_POST['data_inicial'];
                        $dataFinal = $_POST['data_final'];

                        $sqlInativos = "SELECT * FROM eventos 
                                        WHERE data_termino < CURDATE()
                                        AND data_termino BETWEEN '$dataInicial' AND '$dataFinal'";

                        $resultadoInativos = mysqli_query($conecta, $sqlInativos);

                        if (mysqli_num_rows($resultadoInativos) > 0) {
                            while ($evento = mysqli_fetch_assoc($resultadoInativos)) {
                                echo "<div class='eventos-encontrados'>";
                                echo "<a class='link-evento' href='descricaoevento.php?id=" . $evento['id_evento'] . "'>";
                                echo "<strong>" . htmlspecialchars($evento['nome']) . "</strong><br>";
                                echo "<small>" . htmlspecialchars($evento['descricao']) . "</small><br>";
                                echo "<em>Encerrado em: " . date('d/m/Y', strtotime($evento['data_termino'])) . "</em>";
                                echo "</a>";
                                echo "</div><hr>";
                            }
                        } else {
                            echo "<p>Nenhum evento inativo encontrado no período selecionado.</p>";
                        }
                    } else {
                        echo "<p>Selecione um intervalo de datas para visualizar eventos inativos.</p>";
                    }
                ?>

                </div>
            </div>
        </div>
    </div>
</body>
</html>
