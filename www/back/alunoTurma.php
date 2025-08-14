<?php
include('conecta.php');

if (isset($_POST['turma']) && isset($_POST['alunos'])) {
    $turma = $_POST['turma'];
    $alunos = json_decode($_POST['alunos'], true);

    foreach ($alunos as $id_aluno) {
        $sql = "INSERT INTO grupo_alunos (id_turma, id_aluno, ativo) VALUES ('$turma', '$id_aluno', '1')";
        mysqli_query($conecta, $sql);
    }

} else {
    echo "Dados incompletos.";
}
?>
<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensalize</title>
</head>
<style>
    body {
        margin: 0;
        height: 100vh;
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .checkmark {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #4caf50;
        animation: pop 0.5s ease;
    }

    .checkmark::after {
        content: '✔';
        font-size: 60px;
        color: white;
        opacity: 0;
        transform: scale(0.5);
        animation: appear 0.5s ease forwards 0.3s;
    }

    @keyframes pop {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        80% {
            transform: scale(1.1);
            opacity: 1;
        }
        100% {
            transform: scale(1);
        }
    }

    @keyframes appear {
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
</head>
<body style="background: radial-gradient(circle, rgba(42, 123, 155, 1) 0%, rgba(2, 34, 43, 1) 100%);">
    <div class="checkmark"></div>
    <script>
    setTimeout(() => {
        window.location.href = '../front/gestao.php';
    }, 2000);
</script>
</body>
</html>


