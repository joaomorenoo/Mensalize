<?php
    session_start();
    include('conecta.php'); 
    include('menu.php');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="https://191.30.24.21/cedup/ticedup124/joao/Mensalize/img/favicon.ico">
    <title>Mensalize</title>
</head>
<body>
    <form method="post" action="../back/grava_aluno.php" name="cad_aluno">
        <input type="text" name="nome" placeholder="Nome">
        <input type="text" name="cpf" maxlength="14" placeholder="CPF"> 
        <input type="text" name="telefone" placeholder="Telefone">
        <input type="text" name="email" placeholder="Email">
        <input type="date" name="data_nasc" placeholder="Data de Nascimento">

        <button type="submit">Salvar</button>
        <button type="reset">Limpar</button>
    </form>
</body>
</html>