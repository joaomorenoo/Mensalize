<?php
    include('menu.php');
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="https://191.30.24.21/cedup/ticedup124/joao/Mensalize/img/favicon.ico">
    <title>Mensalize</title>
    <style>
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }
        body {
            background: radial-gradient(circle, rgba(42, 123, 155, 1) 0%, rgba(2, 34, 43, 1) 100%);
            overflow: hidden;
            position: relative;
            height: 100vh;
            width: 100vw;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 40px 0;
            z-index: 1;
            position: relative;
        }
        .welcome {
            width: 80%;
            background-color: rgb(0, 155, 202);
            height: 5em;
            color: white;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            border-radius: 10px;
            border: 1px solid white;
            font-size: 1.5em;
        }
        .img-box {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-top: 20px;
        }
        .img-box img {
            width: 350px;
            max-height: 40vh;
            height: 100%;
            border: 4px solid white;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            animation: subir 0.8s ease forwards;
        }
        @keyframes subir {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome">Bem-Vindo <?php echo $nome ?></div>
    </div>

    <div class="img-box">
        <img src="../img/logo.png" alt="Gráfico">
    </div>
</body>

</html>
