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
    <form method="post" action="../back/grava_turma.php" name="cad_turma">
        <input type="text" name="nome" placeholder="Nome da turma">
        <select name="turno">
            <option value="n/a">Selecione o turno</option>
            <option value="Matutino">Matutino</option>
            <option value="Vespertino">Vespertino</option>
            <option value="Noturno">Noturno</option>
        </select>
        <input type="text" name="curso" placeholder="Curso">
        <select name="semestre">
            <option value="n/a">Selecione o semestre</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
        <input type="text" name="ano" placeholder="Ano">
        <button type="submit">Salvar</button>
        <button type="reset">Limpar</button>
    </form>
</body>
</html>