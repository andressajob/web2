<?php
include_once "session.php";
require 'db/DB.class.php';
//inicia a conexão
$db = new DB('02080100', //usuario
        'Q2hC9PBz', //senha
        '02080100', //banco
        'webacademico.canoas.ifrs.edu.br'//servidor
);

extract($_REQUEST); //transformando os dados em variáveis
$dadosTemp['id_funcionario'] = '';
$dadosTemp['nome_funcionario'] = '';
$consulta = $db->query('SELECT id_funcionario,nome_funcionario FROM funcionario');
$dadosTemp2['id_tipo'] = '';
$dadosTemp2['descricao'] = '';
$consulta2 = $db->query('SELECT * FROM tipo');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8"/>
        <title>Cadastro de incidentes - Estacionamento</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body onload="startTime()">
    <legend id="cen">LISTA DE INCIDENTES</legend>
    <table table align="center" id="cen" border="1" class="table table-hover" style="width: 1000px">
        <thead>
            <tr>
                <th id="cen">Funcionário</th>
                <th id="cen">Data</th>
                <th id="cen">Tipo</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($db->query('SELECT f.nome_funcionario,data_incidente,t.descricao FROM incidente i '
                    . 'INNER JOIN funcionario f ON i.id_funcionario=f.id_funcionario '
                    . 'INNER JOIN tipo t ON i.id_tipo=t.id_tipo GROUP BY f.nome_funcionario,data_incidente,t.descricao '
                    . 'ORDER BY data_incidente DESC ') as $linha) { ?>
                <tr>
                    <td>
                        <?= $linha['nome_funcionario'] ?>
                    </td>
                    <td>
                        <?= $linha['data_incidente'] ?>
                    </td>
                    <td>
                        <?= $linha['descricao'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>

        </tbody>
    </table>
</div>
<?php include_once 'footer.html'; ?>     
</body>
</html>
