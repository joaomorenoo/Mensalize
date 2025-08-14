<?php
session_start();

include 'conecta.php';

$q = isset($_GET["q"]) ? mysqli_real_escape_string($conecta, $_GET["q"]) : "";
// echo "teste = " . $q;

if ($q !== "") {
    $sql = "SELECT nome FROM alunos WHERE nome LIKE '%$q%'";
    $result = mysqli_query($conecta, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p><a style='text-decoration:none; color: black; font-size: 20px' href='../front/aluno_select.php?busca=".$row["nome"]."'>" . $row["nome"] . "</a></p>";
        }
    } else {
        echo "<p>Nenhum aluno encontrado</p>";
    }
}

mysqli_close($conecta);
?>
