<?php
    session_start();
    $chave = $_SESSION['chave'] ?? null;
    if ($chave != "printer") {
        session_destroy();
        header('location: login.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Tabela de Firmwares</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            padding: 20px;
        }
        header h1 {
            text-align: center;
            color: #333;
            margin-bottom: 40px;
        }
        .tabela-wrapper {
            display: flex;
            flex-direction: column;
            gap: 30px;
            align-items: center;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            max-width: 1000px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        caption {
            background-color: #3f51b5;
            color: white;
            font-size: 1.2em;
            padding: 10px;
            font-weight: bold;
        }
        thead {
            background-color: #f1f1f1;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-size: 0.95em;
        }
        th {
            font-weight: 600;
            color: #333;
        }
        td a {
            color: #007BFF;
            text-decoration: none;
        }
        td a:hover {
            text-decoration: underline;
        }
        .novo {
            color: red;
            font-weight: bold;
            margin-right: 5px;
        }
        @media screen and (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                display: none;
            }
            td {
                position: relative;
                padding-left: 50%;
                text-align: left;
            }
            td::before {
                position: absolute;
                top: 12px;
                left: 12px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
                color: #555;
            }
            tbody td:nth-child(1)::before { content: "MODELO"; }
            tbody td:nth-child(2)::before { content: "VERSÃO"; }
            tbody td:nth-child(3)::before { content: "DATA"; }
            tbody td:nth-child(4)::before { content: "LINK"; }
        }
    </style>
</head>
<body>
    <header>
        <h1>TABELAS DE FIRMWARES</h1>
    </header>

    <div class="tabela-wrapper">

        <table>
            <caption>Kyocera ECOSYS</caption>
            <thead>
                <tr>
                    <th>MODELO</th>
                    <th>VERSÃO</th>
                    <th>DATA</th>
                    <th>LINK</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>M2040dn</td>
                    <td>2N1_2000.005.002</td>
                    <td>2022-11-10</td>
                    <td><a href="firmwares/2N1_2000.005.002.fls">Download</a></td>
                </tr>
                <tr>
                    <td>FS-4200DN</td>
                    <td>2T4_2000.001.529</td>
                    <td>2018-06-26</td>
                    <td><a href="firmwares/2T4_2000.001.529.fls">Download</a></td>
                </tr>
            </tbody>
        </table>

        <table>
            <caption>Kyocera TASKalfa</caption>
            <thead>
                <tr>
                    <th>MODELO</th>
                    <th>VERSÃO</th>
                    <th>DATA</th>
                    <th>LINK</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="novo">Novo!</span> 3253/2553ci</td>
                    <td>G00_5F00.010.003</td>
                    <td>2024-01-16</td>
                    <td><a href="firmwares/G00_5F00.010.003.fls">Download</a></td>
                </tr>
                <tr>
                    <td>5052/4052ci</td>
                    <td>4F0_1000.001.539</td>
                    <td>2022-11-15</td>
                    <td><a href="firmwares/4F0_1000.001.539.fls">Download</a></td>
                </tr>
                <tr>
                    <td>3511i</td>
                    <td>3M0_2F00.001.003</td>
                    <td>2023-07-05</td>
                    <td><a href="firmwares/3M0_2F00.001.003.fls">Download</a></td>
                </tr>
            </tbody>
        </table>

        <table>
            <caption>Ricoh</caption>
            <thead>
                <tr>
                    <th>MODELO</th>
                    <th>VERSÃO</th>
                    <th>DATA</th>
                    <th>LINK</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="novo">Novo!</span> MP C2011SP</td>
                    <td>1.07</td>
                    <td>2024-04-08</td>
                    <td><a href="firmwares/C2011_107.bin">Download</a></td>
                </tr>
                <tr>
                    <td><span class="novo">Novo!</span> MP 301</td>
                    <td>2.13</td>
                    <td>2024-04-08</td>
                    <td><a href="firmwares/MP301_213.bin">Download</a></td>
                </tr>
                <tr>
                    <td><span class="novo">Novo!</span> MP 501</td>
                    <td>1.17</td>
                    <td>2024-04-08</td>
                    <td><a href="firmwares/MP501_117.bin">Download</a></td>
                </tr>
            </tbody>
        </table>

        <table>
            <caption>Lexmark</caption>
            <thead>
                <tr>
                    <th>MODELO</th>
                    <th>VERSÃO</th>
                    <th>DATA</th>
                    <th>LINK</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>MS810</td>
                    <td>LHS41.CM.P049</td>
                    <td>2020-11-05</td>
                    <td><a href="firmwares/Lexmark_MS810_LHS41.CM.P049.fls">Download</a></td>
                </tr>
            </tbody>
        </table>

    </div>
</body>
</html>
