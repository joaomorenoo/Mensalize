<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="https://191.30.24.21/cedup/ticedup124/joao/Mensalize/img/favicon.ico">
    <title>Mensalize</title>
</head>
<Style> 
    body {
        background: linear-gradient(to right, rgb(163, 212, 226) 0%, rgb(83, 107, 114) 50%, rgb(163, 212, 226) 100%);
    }
    div {
        display: flex;
        justify-content: center;
        align-content: center;
        align-items: center;
        flex-direction: column;
        animation: slideUp 0.8s forwards;
        color: rgb(27, 27, 151);;
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

</Style>
<body>
    <div>
        <h1>MUITO OBRIGADO PELO USO!</h1>
    </div>
</body>
</html>
<?php

    session_destroy();

    header('location: ../front/login.php');
?>
