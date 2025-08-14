<?php
    session_start();
    include ('../back/conecta.php');

    if (isset($_SESSION['erro_login'])) {
        $erroClasse = "erro";
        $logoErro = "logoErro";
    } else {
        $erroClasse = "";
        $logoErro = "";
    }   
    session_destroy();
?>
<!DOCTYPE html>
<html lang="PT-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensalize</title>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="login.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(to right, rgb(163, 212, 226) 0%, rgb(83, 107, 114) 50%, rgb(163, 212, 226) 100%);
        }
        .container {
            display: flex;
            justify-content: center;
            align-content: center;
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
        .conteudo {
            background-color: #313e42;
            height: 500px;
            width: 300px;
            border: 1px solid rgb(54, 64, 66);
            margin: 100px;
            border-radius: 10px;
            box-shadow: 0px 1px 5px 5px rgb(33, 40, 41);
            animation: slideUp 0.8s forwards;
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-items: center;
            margin: auto;
            margin-top: 40px;
        }
        .logo {
            border-radius: 10px;
            box-shadow: 0px 1px 3px 3px rgb(0, 155, 202);
            margin-top: 20px;
            margin-bottom: 60px;
        }
        .logoErro {
            box-shadow: 0px 1px 3px 3px rgb(197, 103, 103);
        }
        .post {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 20px;
        }
        .input {
            border-radius: 5px;
            box-shadow: 0px 1px 3px 3px rgb(32, 41, 44);
            height: 35px;
            justify-items: center;
            margin: 10px;
            padding: 5px;
            width: 80%;
        }   
        .erro {
            border: 2px solid rgb(105, 55, 55);
        }
        .acesso { 
            background-color: rgb(29, 66, 29);
            color: white;
            width: 90px;
            height: 30px;
            border: 1px solid rgb(83, 107, 114);
            border-radius: 6px;
            gap: 60px;
        }
        .acesso:hover {
            background-color: rgb(39, 90, 39);
        }
        .forget {
            color: rgb(133, 153, 133);
            text-decoration: none;
        }
    </style>
    <script>
        function formatar(mascara, documento) {
            var i = documento.value.length;
            var saida = mascara.substring(0,1);
            var texto = mascara.substring(i)
            if(texto.substring(0,1) != saida){
                documento.value += texto.substring(0,1);
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="conteudo">
            <img src="../img/logo.png" width="150px" height="150px" class="logo <?= $logoErro ?>" alt="logo">
            <?php if ($erroClasse != "") { ?>
                <div style="color: rgb(133, 153, 133); text-decoration:none;">
                    Usuário ou senha incorretos
                </div>
            <?php } ?>
            <form method="post" action="../back/valida.php" class="post">
                <input type="text" name="cpf" placeholder=" CPF" maxlength="14" 
                    onKeyPress="formatar('###.###.###-##', this)" 
                    oninput="this.value = this.value.replace(/[^0-9.\-]/g, '');" 
                    required autocomplete="off" class="input <?= $erroClasse ?>">
                
                <input type="password" name="senha" placeholder=" Senha" class="input <?= $erroClasse ?>">

                <button type="submit" class="acesso">Acessar</button>
            </form>
        </div>
    </div>
</body>
</html>
