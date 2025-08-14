<?php
    session_start();
    include('../back/conecta.php');
    include('menu.php');

    

    $trm = "SELECT * FROM turma order by curso asc";
    $queryTurmas= mysqli_query($conecta, $trm);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensalize</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    body {
        background: radial-gradient(circle, rgba(42, 123, 155, 1) 0%, rgba(2, 34, 43, 1) 100%);
    }
    .container {
        display: flex;
        height: 85vh;
        width: 85vw;
        justify-content: center;
        align-items: center;
    }
    .menu-interativo {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        background-color: #007899;
        height: 100%;
        width: 100%;
        border-radius: 15px;
        border: 2px solid white;
        box-shadow: 0 8px 20px rgba(0,0,0,0.7);
        gap: 10px;
        padding: 15px;
        animation: slideUp 0.7s ease;
        margin-top: 25px;
    }
    .top-section {
        width: 100%;
        background-color: #e4e4e4;
        border-radius: 10px;
        padding: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.6);
        border: 2px solid #313e42;
    }
    .top-section select {
        width: 100%;
        padding: 5px;
        border-radius: 8px;
        border: none;
        outline: none;
        background-color: white;
    }
    .bottom-section {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #e4e4e4;
        border-radius: 10px;
        height: 80%;
        width: 100%;
        border: 2px solid #313e42;
        box-shadow: 0 8px 15px rgba(0,0,0,0.6);
        gap: 10px;
        padding: 10px;
    }
    .bottom-section p {
        color: #00303d;
        font-weight: 600;
    }
    .src, .selc {
        background-color: #313e42;
        border: 2px solid white;
        border-radius: 10px;
        width: 48%;
        height: 95%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 6px 15px rgba(0,0,0,0.8);
    }
    .src input {
        height: 12%;
        border-radius: 8px 8px 0 0;
        border: none;
        padding: 5px 10px;
        outline: none;
        background-color: white;
    }
    #result {
        display: none;
        background-color: gray;
        border-radius: 0 0 10px 10px;
        overflow-y: auto;
        max-height: 88%;
    }
    .resultados-servidor {
        padding: 10px;
        color: white;
        cursor: pointer;
        border-bottom: 1px solid white;
    }
    .resultados-servidor:hover {
        background-color: rgb(19, 24, 26);
    }
    .selc p {
        color: white;
        padding: 5px;
        border-bottom: 1px solid white;
        text-align: center;
    }
    .alunoSelecionados {
        color: white;
        border-bottom: 1px solid white;
        padding: 8px;
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    .alunoSelecionados:hover {
        background-color: rgb(19, 24, 26);
    }
    .botoes {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        width: 100%;
        margin-top: 5px;
    }
    .submit, .reset {
        padding: 8px 20px;
        border: none;
        border-radius: 8px;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s;
        font-weight: 600;
    }
    .submit {
        background-color: #008000;
    }
    .submit:hover {
        background-color:rgb(0, 68, 0);
    }
    .reset {
        background-color:rgb(209, 52, 17);
    }
    .reset:hover {
        background-color: rgb(146, 38, 14);
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
</style>
<body id="bkg-gestao">
    <div class="container">
        <div class="menu-interativo">
            <div class="top-section">
                <select name="turmaaa" id="turmaaa">
                    <option value="">Selecione uma turma</option>
                        <?php
                            while ($querryy = mysqli_fetch_assoc($queryTurmas)) {
                                $selected = "";
                                if (isset($id_turma) && $id_turma == $querryy['id_turma']) {
                                    $selected = "selected";
                                }
                                echo "<option value='" . $querryy['id_turma'] . "' $selected>" . $querryy['curso'] . " | Ano: " .$querryy['ano'] . "/".$querryy['semestre']."</option>";
                            }
                        ?>
                </select>
            </div>
            <div class="bottom-section">
                <div style="height: 10%; margin-top: 10px;">
                    <p>Busque por seus alunos e conecte-os as suas turmas!</p>
                </div>
                <div style="display: flex; height: 90%; width: 100%; justify-content: space-around; gap: 5px;">
                    <div class="src">
                        <input type="text" placeholder=" Busque um aluno" id="inputbuscagestao" onkeyup="buscaralunosgestao()">
                        <div id="result">

                        </div>
                    </div>
                    <div class="selc" id="selc">
                        <p>Clique nos alunos para remove-los</p>
                    </div>
                </div>
            </div>
            <div class="botoes">
                <form id="formEnvio" action="../back/alunoTurma.php" method="post">
                    <input type="hidden" name="turma" id="turmaInput" value="">
                    <input type="hidden" name="alunos" id="alunosInput" value="">
                </form>
                <button class="submit" onclick="enviarVetor()">Enviar</button>
                <button class="reset" onclick="limparVetor()">Limpar</button>
            </div>
        </div>
    </div>
    <script>
        let vetorAluno = [];
        let vetorIdAluno = [];

        function buscaralunosgestao() {
            //console.log("Chamou");

            let buscagestao = document.getElementById("inputbuscagestao").value;

            //console.log("busca = " + buscagestao);

            if (buscagestao.length < 1) {
                document.getElementById("result").style.display = "none";
                return;
            }

            let xhr = new XMLHttpRequest(); 
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    //console.log("entrou no if");
                    let resultadoDiv = document.getElementById("result");
                    resultadoDiv.innerHTML = this.responseText;
                    console.log(this.responseText); 

                    if (this.responseText.trim() !== "") {
                        resultadoDiv.style.display = "block";
                    } else {
                        resultadoDiv.style.display = "none";
                    }
                }
            };

            xhr.open("GET", "../back/buscaAlunosGestao.php?busc=" + encodeURIComponent(buscagestao), true);
            xhr.send();
        }
        function cadastrarVetor(nome, id) {
            if (vetorAluno.includes(nome)) {
                alert("Aluno já adicionado.");
                return;
            }
            const turmaIdTeste = document.getElementById("turmaaa").value;
            console.log(turmaIdTeste);
            vetorAluno.push(nome);
            vetorIdAluno.push(id);

            const selecionados = document.getElementById("selc");
            const alunoNovo = document.createElement("div");
            alunoNovo.setAttribute("class", "alunoSelecionados");
            alunoNovo.textContent = nome;

            alunoNovo.addEventListener("click", function() {
                removerAlunoCompleto(nome, id, alunoNovo);
            });

            selecionados.appendChild(alunoNovo);

            console.log(vetorAluno, vetorIdAluno)
        }
        function removerAlunoCompleto(nome, id, elemento) {
            vetorAluno = vetorAluno.filter(aluno => aluno !== nome);
            vetorIdAluno = vetorIdAluno.filter(alunoId => alunoId !== id);
            elemento.remove();
            console.log(vetorAluno);
            console.log(vetorIdAluno);
        }
        function limparVetor(){
            if (vetorAluno.length != 0){
                window.location.reload()
            }
        }
    function enviarVetor() {
        const turmaId = document.getElementById("turmaaa").value;

        if (!turmaId) {
            alert("Selecione uma turma antes de enviar.");
            return;
        }

        if (vetorAluno.length == 0) {
            alert("Nenhum aluno selecionado.");
            return;
        }

        document.getElementById('turmaInput').value = turmaId;
        document.getElementById('alunosInput').value = JSON.stringify(vetorIdAluno);

        document.getElementById('formEnvio').submit();
    }
</script>

</body>
</html>
