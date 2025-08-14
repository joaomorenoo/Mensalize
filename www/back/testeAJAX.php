<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <button onclick="acao()">Aperte</button>
    <div id="div"></div>
</body>
<script>
    function acao() {
        var xhr = new XMLHttpRequest();
        console.log(xhr); 

        // Quando a resposta do servidor estiver pronta, substitui o conteúdo da div
        xhr.onload = function() {
            document.getElementById("div").innerHTML = this.responseText;
        };

        // Abre uma requisição GET para menu.php, passando o parâmetro nometeste=joao
        // xhr.open(verbo, URL, async(por padrão em TRUE))
        xhr.open("GET", "../front/menu.php?nometeste=joao", true);
        xhr.send();
    }

    // Para acessar na página enviada no PHP:
    // $variavel = $_GET["nometeste"];

    // Exemplo de array que será convertido em JSON
    // let testeJson = [19, "testeJSON", 10, 9, 1];
    // console.log(testeJson); 

    // Converte o array em uma string no formato JSON
    // let jsonTeste = JSON.stringify(testeJson);
    // console.log(jsonTeste);

    // Armazena a string JSON no localStorage com a chave "JSONteste"
    // localStorage.setItem("JSONteste", jsonTeste);

    // Recupera do localStorage o valor armazenado na chave "JSONteste"
    // let jsonRecebido = localStorage.getItem("JSONteste");
    // console.log(jsonRecebido);

    // Converte a string JSON de volta para um array JavaScript
    // let valor = JSON.parse(jsonRecebido);
    // console.log(valor);

</script>
</html>
