<?php
session_start();
include 'conecta.php';

$bbc = $_GET["busc"];

if ($bbc != "") {
    $sql = "SELECT * FROM alunos WHERE nome LIKE '%$bbc%'";
    $result = mysqli_query($conecta, $sql);

    if (mysqli_num_rows($result) > 0) {
        $cont = 1;
        while ($abc = mysqli_fetch_assoc($result)) {
            if ($cont % 2 == 1) {
                $id = "escuro";
            } else {
                $id = "claro";
            }

            echo "<div class='resultados-servidor $id' onClick='cadastrarVetor(\"{$abc['nome']}\",\"{$abc['id_aluno']}\")'>" . htmlspecialchars($abc['nome']) . "</div>";

            $cont++;
        }
    } else {
        echo "<p>Nenhum aluno encontrado</p>";
    }
}
?>
