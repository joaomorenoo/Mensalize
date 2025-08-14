<?php
    session_start();
    $nome = $_SESSION['nome_usuario'];
    $chave = $_SESSION['chave'];

    $letra = substr($nome, 0, 1);

    if ($nome == "" || $chave != "11092002") {
        session_destroy();
        header('location: login.php');
    };
?>
<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <title>Mensalize</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        .menu-sup {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: space-between;
            background-color: #313e42;
            padding: 10px;
            gap: 90px;

        }

        .group-bottons {
            display: flex;
            gap: 10px;
        }

        .botton {
            width: 100px;
            height: 30px;
            border-radius: 2px;
            border-bottom-right-radius: 15px;
            background-color: rgb(0, 155, 202);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 20px;
            flex-shrink: 1;
        }

        .botton:hover {
            background-color: rgb(0, 59, 77);
        }

        .src-geral {
            text-align: center;
            position: relative;
            max-width: 300px;
            width: 100%;
            flex-grow: 1;
            border-radius: 10px;
        }

        #busca {
            background-color: rgb(255, 255, 255);
            color: black;
        }

        .src-geral input {
            width: 100%;
            height: 30px;
            border-radius: 3px;
            padding-left: 10px;
            border: none;
        }

        .user {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgb(0, 59, 77);
            border-radius: 50%;
            cursor: pointer;
            width: 39px;
            height: 39px;
        }

        .user:hover {
            background-color: rgb(0, 103, 134);
        }

        @media (max-width: 600px) {
            .menu-sup {
                height: 75px;
                flex-direction: column;
                align-items: center;
                padding: 5px;
                gap: 5px;
            }

            .group-bottons {
                width: 100%;
                justify-content: center;
                order: 1;
            }

            .src {
                width: 100%;
                order: 2;
                text-align: center;
            }

            .user {
                display: none;
            }
        }

        #resultado {
            position: absolute;
            background:rgb(255, 255, 255);
            border: 1px solid black;
            width: 100%;
            max-width: 300px;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            top: 100%;
            left: 0;
            border-top: none;
            text-decoration: none;
            color: black;
        }

        #resultado p {
            padding: 10px;
            margin: 0;
            border-bottom: 1px solid black;
            text-decoration: none;
        }

        #resultado p:hover {
            background: rgb(208, 244, 255);
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="menu-sup">
        <div class="group-bottons">
            <a href="turmas.php"><button class="botton" id="turmas">Turmas</button></a>
            <a href="alunos.php"><button class="botton" id="alunos">Alunos</button></a>
            <a href="gestao.php"><button class="botton" id="gestao">Gestão</button></a>
            <a href="eventos.php"><button class="botton" id="eventos">Eventos</button></a>
        </div>
        <div class="src-geral">
            <input type="text" id="busca" autocomplete="off" placeholder="Busque um aluno" onkeyup="buscarAlunos()">
            <div id="resultado"></div>
        </div>
        <!-- <div class="user">
            <a href="adicionar_usuario.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" fill="white" class="bi bi-person-fill-add" viewBox="0 0 16 16">
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
                </svg>
            </a>
        </div> -->
        <div class="user">
            <a href="../back/sair.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" fill="white" class="bi bi-box-arrow-left"
                    viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                    <path fill-rule="evenodd"
                        d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                </svg>
            </a>
        </div>
    </div>

    <script>
        function buscarAlunos() {
            let query = document.getElementById("busca").value;

            if (query.length < 1) {
                document.getElementById("resultado").style.display = "none";
                return;
            }

            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    let resultadoDiv = document.getElementById("resultado");
                    resultadoDiv.innerHTML = this.responseText;
                    resultadoDiv.style.display = this.responseText.trim() ? "block" : "none";
                }
            };
            xhr.open("GET", "../back/buscar_alunos.php?q=" + query, true);
            xhr.send();
        }
    </script>
</body>

</html>