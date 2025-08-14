<?php
    session_start();
    include('../back/conecta.php'); 
    include('menu.php');

    $sql_query = "SELECT * FROM alunos ORDER BY nome ASC";
    $consulta = mysqli_query($conecta, $sql_query);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="https://191.30.24.21/cedup/ticedup124/joao/Mensalize/img/favicon.ico">
    <title>Mensalize</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }
        #alunos {
            background-color: rgb(0, 25, 32);
            border: 1px solid white;
        }
        #bkg-alunos {
            background: radial-gradient(circle, rgba(42, 123, 155, 1) 0%, rgba(2, 34, 43, 1) 100%);
            min-height: 100vh;
        }

        .menu-bar {
            display: flex;
            height: 3em;
            justify-content: flex-start;
            align-items: center;
            padding-left: 2%;
            background-color: rgb(0, 155, 202);
        }

        .menu-button button {
            background-color: rgb(0, 59, 77);
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .menu-button button {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .menu-button button:hover {
            background-color: rgb(29, 125, 155);
        }

        .text-num {
            margin-left: auto;
            padding-right: 3%;
            color: white;
            font-weight: bold;
        }

        #tabela-alunos {
            width: 90%;
            margin: 2em auto;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        #tabela-alunos th, #tabela-alunos td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        #tabela-alunos thead {
            background-color: #007899;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 14px;
        }

        .acoes {
            display: flex;
            gap: 10px;
        }

        td button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            transition: transform 0.2s;
        }

        td button:hover {
            transform: scale(1.1);
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
            gap: 5px;
        }

        .modal-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
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
            align-self: flex-start;
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
<body id="bkg-alunos">
    <div class="menu-bar">
        <div class="menu-button">
            <button onclick="openModal()";>Adicionar</button>
        </div>
        <div class="text-num">
            <?php echo mysqli_num_rows($consulta) . " ALUNOS REGISTRADOS"; ?>
        </div>
    </div>

    <table id="tabela-alunos">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while ($linha = mysqli_fetch_assoc($consulta)) {
                    echo "<tr>";
                    echo "<td>" . $linha['nome'] . "</td>";
                    echo "<td>" . $linha['cpf'] . "</td>";
                    echo '<td class="acoes">';
                    echo '<button type="button" class="btn btn-outline-secondary" onclick="location.href=\'aluno_select.php?busca=' . urlencode($linha['nome']) . '\'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                            </svg>
                          </button>';
                    echo '<button type="button" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                                <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z"/>
                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zM11 4.5A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                            </svg>
                          </button>';
                    echo '</td>';
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
    <div class="modal-overlay" id="modal">
    <div class="modal-content">
        <h2>Adicionar Novo Aluno</h2>
        <hr>
            <form id="formAluno" name="cad_aluno">
                <div class="form-group">
                    <label for="nome">Nome do Aluno</label>
                    <input type="text" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" placeholder=" CPF" maxlength="14" 
                    onKeyPress="formatar('###.###.###-##', this)" 
                    oninput="this.value = this.value.replace(/[^0-9.\-]/g, '');" 
                    required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" placeholder="47900000000" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" required>
                </div>
                <div class="form-group">
                    <label for="data_nasc">Data de Nascimento</label>
                    <input type="date" name="data_nasc" placeholder="Data de Nascimento" required>
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
            document.getElementById('formAluno').reset();
            document.getElementById('mensagem').textContent = '';
            window.location.reload();
        }
        $('#formAluno').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '../back/grava_aluno.php',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.trim() === "ok") {
                        $('#mensagem').text('Aluno cadastrado com sucesso!').css('color', 'green');
                        $('#formAluno')[0].reset();
                    } else if (response.trim() === "cpf_existente") {
                        $('#mensagem').text('Já existe um aluno com este CPF.').css('color', 'orange');
                    } else if (response.trim() === "incompleto") {
                        $('#mensagem').text('Preencha os campos obrigatórios.').css('color', 'red');
                    } else {
                        $('#mensagem').text('Erro ao cadastrar aluno.').css('color', 'red');
                    }
                },
                error: function () {
                    $('#mensagem').text('Erro ao cadastrar aluno.').css('color', 'red');
                }
            });
        });
        function formatar(mascara, documento) {
            var i = documento.value.length;
            var saida = mascara.substring(0,1);
            var texto = mascara.substring(i)
            if(texto.substring(0,1) != saida){
                documento.value += texto.substring(0,1);
            }
        }
    </script>
</body>
</html>
