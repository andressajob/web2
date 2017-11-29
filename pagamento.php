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
$dadosTemp['id_ticket'] = '';
$dadosTemp['placa_veiculo'] = '';

//pagar
if (isset($acao) && $acao == 'pagar') {
    $consulta = $db->query("SELECT id_ticket,placa_veiculo FROM ticket t INNER JOIN veiculo v ON t.id_veiculo=v.id_veiculo
      WHERE id_ticket = $id");
    foreach ($consulta as $linha) {
        $dadosTemp = $linha;
    }
    $acao = 'pagarFim';
}
if (isset($_POST['submit']) && $acao == 'pagarFim') {
    $db->begin();
    $flag = true;
    $dados[0] = $_POST['valor'];
    $dados[1] = $_POST['forma_pagamento'];
    $dados[2] = $_POST['id_ticket'];
    $result = $db->execute("INSERT INTO pagamento(valor,forma_pagamento,id_ticket) VALUES (?,?,?)", $dados);
    if (!$result) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Impossível pagar este ticket, tente novamente.');window.location.href='pagamento.php';</script>";
    }
    $pago = 'true';
    $temp = $db->execute("UPDATE ticket SET pago=? WHERE id_ticket=?", [$pago, $dados[2]]);
    if (!$result) {
        $db->rollback();
        $flag = false;
    }
    if ($flag) {
        $db->commit();
        echo"<script language='javascript' type='text/javascript'>alert('Ticket pago.');window.location.href='pagamento.php';</script>";
    }
}
?>  
<!DOCTYPE html>
<html>
<head>
    <?php include_once 'header.php'; ?>
    <meta charset="UTF-8"/>
    <title>Registro pagamento - Estacionamento</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
</head>
<body>
    <!-- Formulario -->
    <form class="form-horizontal" action="pagamento.php?acao=<?= $acao ?>&id=<?= $id ?>" method="POST">
        <legend id="cen">REGISTRO DE PAGAMENTO</legend>
        <fieldset>
            <div class="form-group">
                <label class="col-md-6 control-label">Pagamento</label>  
            </div>

            <div class="form-group"> 
                <label class="col-md-5 control-label">Ticket</label>  
                <div class="col-md-1">
                    <input  class="form-control input-md" type="text" id="id_ticket" name="id_ticket" value="<?= $dadosTemp['id_ticket']; ?>" readonly/>
                </div>
            </div>
            <div class ="form-group">
                <label class ="col-md-5 control-label">Placa</label>
                <div class="col-md-2">
                    <input  class="form-control input-md" id="placa_veiculo" type="text" name="placa_veiculo" placeholder="Placa" value="<?= $dadosTemp['placa_veiculo']; ?>" readonly/>
                </div>
            </div>
            <div class ="form-group">
                <label class ="col-md-5 control-label">Valor</label>
                <div class="col-md-2">
                    <input class="form-control input-md" autofocus type="text" id="valor" name="valor" placeholder="Valor" required/>
                </div>
            </div>
            <div class = "form-group">
                <label class="col-md-5 control-label">Forma de pagamento</label>
                <div class="col-md-2">
                    <select class="form-control" name = "forma_pagamento" required>
                        <option value="" selected>Selecione pagamento...</option>
                        <option value="debito">Débito</option>
                        <option value="credito">Crédito</option>
                        <option value="dinheiro">Dinheiro</option>                        
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-5  control-label"></label>
                <div class="col-md-4">
                    <button id="submit" name="submit" class="btn btn-primary">Pagar!</button>
                </div>
            </div>
        </fieldset>
    </form>
    <div class="row" id="cen">
        <table table align="center" id="cen" border="1" class="table table-hover" style="width: 500px">
            <thead>
                <tr>
                    <th id="cen">Ticket</th>
                    <th id="cen">Estacionamento</th>
                    <th id="cen">Box</th>
                    <th id="cen">Veiculo</th>
                    <th id="cen">Opção</th>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($db->query('SELECT id_ticket,nome_estacionamento,descricao,placa_veiculo FROM ticket t '
                    . 'INNER JOIN estacionamento e ON t.id_estacionamento = e.id_estacionamento '
                    . 'INNER JOIN box b ON b.id_box = t.id_box '
                    . 'INNER JOIN veiculo v ON v.id_veiculo=t.id_veiculo WHERE pago=FALSE GROUP BY id_ticket,nome_estacionamento, '
                    . 'descricao, placa_veiculo ORDER BY id_ticket DESC') as $linha) {
                        ?>
                        <tr>
                            <td>
                                <?= $linha['id_ticket'] ?>
                            </td>
                            <td>
                                <?= $linha['nome_estacionamento'] ?>
                            </td>
                            <td>
                                <?= $linha['descricao'] ?>
                            </td>
                            <td>
                                <?= $linha['placa_veiculo'] ?>
                            </td>
                            <td>
                                <a href="?id=<?= $linha['id_ticket']; ?>&acao=pagar" onclick="<?= $acao = 'pagar'; ?>">Pagar</a>
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
