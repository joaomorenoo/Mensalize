<?php
session_start();
include('../back/conecta.php'); 
include('menu.php');

$id_turma = isset($_GET['slt_turma']) ? $_GET['slt_turma'] : null;

$turmas = "SELECT * FROM turma order by curso asc";
$lst_turmas = mysqli_query($conecta, $turmas);

$consulta = null;
$consulta_inativos = null;

if ($id_turma) {
    $sql_query = "SELECT alunos.id_aluno, alunos.nome, alunos.cpf, alunos.telefone, grupo_alunos.id_turma 
                  FROM grupo_alunos 
                  INNER JOIN alunos ON grupo_alunos.id_aluno = alunos.id_aluno 
                  WHERE grupo_alunos.id_turma = '$id_turma' AND grupo_alunos.ativo = 1";
    $consulta = mysqli_query($conecta, $sql_query);

    $sql_query_inativos = "SELECT alunos.id_aluno, alunos.nome, alunos.cpf, alunos.telefone, grupo_alunos.id_turma 
                  FROM grupo_alunos 
                  INNER JOIN alunos ON grupo_alunos.id_aluno = alunos.id_aluno 
                  WHERE grupo_alunos.id_turma = '$id_turma' AND grupo_alunos.ativo = 0";
    $consulta_inativos = mysqli_query($conecta, $sql_query_inativos); // Aqui foi corrigido
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="http://191.30.24.21/cedup/ticedup124/joao/Mensalize/img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Mensalize</title>
    <style>
        body {
            background: radial-gradient(circle, rgba(42, 123, 155, 1) 0%, rgba(2, 34, 43, 1) 100%);
            min-height: 100vh;
            font-family: Arial, sans-serif;
            color: white;
        }
        #turmas { 
            background-color: rgb(0, 25, 32);
            border: 1px solid white;
        }
        .menu-sup-turmas {
            display: flex;
            width: 100%;
            background-color: rgb(0, 155, 202);
            height: 3em;
            align-items: center;
            padding: 0 2%;
            gap: 10px;
        }

        .menu-sup-turmas select,
        .menu-sup-turmas button {
            border-radius: 4px;
            padding: 4px 10px;
            border: none;
        }

        #add {
            background-color: rgb(0, 59, 77);
            color: white;
        }

        #add:hover {
            background-color: rgb(29, 125, 155);
        }

        table {
            width: 90%;
            margin: 2em auto;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            color: black;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        thead {
            background-color: #007899;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 14px;
        }

        .acoes {
            display: flex;
            gap: 10px;
        }

        .no-selection-message {
            display: none;
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: rgb(1, 38, 49);
            border: 1px solid rgb(85, 0, 0);
            color: white;
            font-size: 18px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .show-message {
            display: block;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(61, 61, 61, 0.51);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            color: black;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            text-align: center;
            height: 100%;
            max-height: 98vh;
        }

        .modal-form {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-top: 10px;
            text-align: left;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            padding: 8px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .form-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .form-buttons .btn {
            padding: 8px 15px;
            font-weight: bold;
        }

        .close-button {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-top: 15px;
            cursor: pointer;
        }

        .close-button:hover {
            background-color: #c82333;
        }

        .alert-message {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<form class="menu-sup-turmas" method="get">
    <select name="slt_turma" class="form-select" style="width: 400px;">
        <option value="">Selecione uma turma</option>
        <?php
        while ($lista = mysqli_fetch_assoc($lst_turmas)) {
            $selected = ($id_turma == $lista['id_turma']) ? "selected" : "";
            echo "<option value='" . $lista['id_turma'] . "' $selected>" . $lista['curso'] . " | Ano: " .$lista['ano'] . "/".$lista['semestre']."</option>";
        }
        ?>
    </select>
    <button type="submit" class="btn btn-success">Buscar</button>
    <button id="add" type="button" class="btn" onclick="openModal()">Adicionar</button>
</form>

<?php if (!$id_turma): ?>
    <div class="no-selection-message show-message">
        Nenhuma turma selecionada. Por favor, escolha uma turma para visualizar os alunos.
    </div>
<?php endif; ?>

<?php if ($consulta && mysqli_num_rows($consulta) > 0): ?>
    <table>
        <thead>
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Telefone</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php
            while ($linha = mysqli_fetch_assoc($consulta)) {
                $id_aluno = $linha['id_aluno'];
                echo "<tr>";
                echo "<td>" . $linha['nome'] . "</td>";
                echo "<td>" . $linha['cpf'] . "</td>";
                echo "<td>" . $linha['telefone'] . "</td>";
                echo '<td class="acoes">
                        <button type="button" class="btn btn-outline-secondary" onclick="location.href=\'aluno_select.php?busca=' . urlencode($linha['nome']) . '\'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                            </svg>
                        </button>
                            <button type="button" class="btn btn-outline-secondary" title="Desativar" onclick="abrirModalReembolso('.$id_aluno.', '.$id_turma.', \''.$linha['nome'].'\')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-person-x" viewBox="0 0 16 16">
                                        <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
                                        <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708"/>
                                    </svg>
                            </button>
                        </form>
                    </td>';
            }
            while ($linha_inativos = mysqli_fetch_assoc($consulta_inativos)) {
                echo "<tr style='background-color: #fe8c74;'>";
                echo "<td>" . $linha_inativos['nome'] . "</td>";
                echo "<td>" . $linha_inativos['cpf'] . "</td>";
                echo "<td>" . $linha_inativos['telefone'] . "</td>";
                echo "<td> Inativo </td>";

            }   
        ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="modal-overlay" id="modal">
    <div class="modal-content">
        <h2>Adicionar Turma</h2>
        <hr>
        <form id="formTurma" class="modal-form">
            <div class="form-group">
                <label for="nome">Nome da Turma</label>
                <input type="text" name="nome" id="nome" required>
            </div>
            

            <div class="form-group">
                <label for="turno">Turno</label>
                <select name="turno" id="turno" required>
                    <option value="">Selecione o turno</option>
                    <option value="Matutino">Matutino</option>
                    <option value="Vespertino">Vespertino</option>
                    <option value="Noturno">Noturno</option>
                </select>
            </div>

            <div class="form-group">
                <label for="curso">Curso</label>
                <input type="text" name="curso" id="curso" required>
            </div>

            <div class="form-group">
                <label for="semestre">Semestre</label>
                <select name="semestre" id="semestre" required>
                    <option value="">Selecione o semestre</option>
                    <option value="1">1º</option>
                    <option value="2">2º</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ano">Ano</label>
                <input type="number" name="ano" id="ano" required>
            </div>

            <div class="form-buttons">
                <button type="reset" class="btn btn-secondary">Limpar</button>
                <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
            <div id="mensagem" class="alert-message"></div>
            <button class="close-button" onclick="closeModal()">Fechar</button>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
            document.getElementById('formTurma').reset();
            document.getElementById('mensagem').textContent = '';
            window.location.reload();
        }

        $('#formTurma').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '../back/grava_turma.php',
                data: $(this).serialize(),
                success: function (response) {
                    $('#mensagem').text('Turma adicionada com sucesso!').css('color', 'green');
                    $('#formTurma')[0].reset();
                },
                error: function () {
                    $('#mensagem').text('Erro ao adicionar turma.').css('color', 'red');
                }
            });
        });
        function abrirModalReembolso(idAluno, idTurma, nomeAluno) {
            document.getElementById('modalReembolso').style.display = 'flex';
            document.getElementById('reembolso_id_aluno').value = idAluno;
            document.getElementById('reembolso_id_turma').value = idTurma;
            document.getElementById('nomeAluno').innerText = "Aluno: " + nomeAluno;
        }

        function fecharModalReembolso() {
            document.getElementById('modalReembolso').style.display = 'none';
        }
    </script>
    <div class="modal-overlay" id="modalReembolso" style="display: none;">
        <div class="modal-content">
            <h2>Reembolso de aluno</h2>
            <p id="nomeAluno"></p>
            <form id="formReembolso" method="POST" action="remover.php">
                <input type="hidden" name="id_aluno" id="reembolso_id_aluno">
                <input type="hidden" name="id_turma" id="reembolso_id_turma">

                <div class="form-group">
                    <label for="valor_reembolso">Valor a ser devolvido (R$)</label>
                    <input type="number" step="0.01" name="valor_reembolso" id="valor_reembolso" required class="form-control">
                </div>

                <div class="form-buttons mt-3">
                    <button type="submit" class="btn btn-success">Confirmar</button>
                    <button type="button" class="btn btn-danger" onclick="fecharModalReembolso()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
