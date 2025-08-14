<?php
    include('menu.php'); 
    include('../back/conecta.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Adicionar Usuário</title>
    <style>
        body {
            background: radial-gradient(circle, rgba(42,123,155,1) 0%, rgba(2,34,43,1) 100%);
            color: #eee;
            font-family: "Poppins", sans-serif;
            overflow-y: none;
        }
        .form-container {
            background: rgba(0,0,0,0.6);
            padding: 25px;
            border-radius: 10px;
            max-width: 400px;
            margin: 30px auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.7);
            overflow-y: none;
        }
        label {
            margin-top: 10px;
            font-weight: 600;
        }
        input.form-control {
            background-color: #fff;
            color: #000;
        }
        .form-check-label {
            color: #eee;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h3 class="text-center mb-4">Registrar Usuário</h3>

    <form method="POST" action="../back/criar_usuario.php">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" required />
        </div>

        <div class="mb-3">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14" placeholder="000.000.000-00" required />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required />
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" required />
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>
</div>

</body>
</html>
