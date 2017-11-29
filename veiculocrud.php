<?php
include_once "session.php";
require 'db/DB.class.php';
//inicia a conexão
$db = new DB('02080100', //usuario
        'Q2hC9PBz', //senha
        '02080100', //banco
        'webacademico.canoas.ifrs.edu.br'//servidor
);

extract($_REQUEST); //transformando os dados enviados em variaveis
//deletar
if (isset($id) && $acao == 'deletar') {
    $dados[0] = $id;
    $temp = $db->execute("DELETE FROM veiculo WHERE id_veiculo=?", $dados);
    if (!$temp) {
        echo"<script language='javascript' type='text/javascript'>alert('Registro deletado!');window.location.href='veiculocrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível deletar este registro, referenciado em outra tabela.');window.location.href='veiculocrud.php';</script>";
    }
}
$dadosTemp['id_veiculo'] = '';
$dadosTemp['placa_veiculo'] = '';
$dadosTemp['cor'] = '';
$dadosTemp['id_modelo'] = '';
//atualizar
if (isset($acao) && $acao == 'atualizar') {
    $consulta = $db->query("SELECT id_veiculo,placa_veiculo,cor,id_modelo FROM veiculo
                          WHERE id_veiculo = $id");
    foreach ($consulta as $linha) {
        $dadosTemp = $linha;
    }
    $acao = 'atualizarFim';
}
if (isset($_POST['submit']) && $acao == 'atualizarFim') {
    $dados[0] = $_POST['placa_veiculo'];
    $dados[1] = $_POST['cor'];
    $dados[2] = $_POST['id_modelo'];
    $dados[3] = $_POST['id_veiculo'];
    $result = $db->execute("UPDATE veiculo SET placa_veiculo=?,cor=?,id_modelo=? WHERE id_veiculo=?", $dados);
    if (!$result) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível atualizar este registro, tente novamente.');window.location.href='veiculocrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro atualizado.');window.location.href='veiculocrud.php';</script>";
    }
}
$dadosTemp['id_marca'] = '';
$dadosTemp['nome_marca'] = '';
$consulta = $db->query('SELECT * FROM marca');
$dadosTemp2['id_modelo'] = '';
$dadosTemp2['nome_modelo'] = '';
$dadosTemp2['ano_modelo'] = '';
$consulta2 = $db->query('SELECT id_modelo,nome_modelo,ano_modelo FROM modelo');
?>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8">
        <title>Lista de Veículos</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <?php include_once 'header.html'; ?>
    </head>
    <body>
        <div class="row" id="cen">
            <form action="veiculocrud.php?acao=<?= $acao ?>&id=<?= $id ?>" class="form-horizontal" method="POST">
                <div class="form-group">
                    <div class="col-md-10">
                        <label class="col-md-offset-2 control-label">Atualização de veículo</label>  
                    </div>
                </div>
                <div class="form-group"> 
                    <label class="col-md-1 control-label">ID</label>  
                    <div class="col-md-1">
                        <input  class="form-control input-md" type="text" id="id_veiculo" name="id_veiculo" value="<?= $dadosTemp['id_veiculo']; ?>" readonly/>
                    </div>
                    <label class ="col-md-1 control-label">Placa</label>
                    <div class="col-md-2">
                        <input  class="form-control input-md" autofocus id="placa_veiculo" type="text" name="placa_veiculo" placeholder="Placa" value="<?= $dadosTemp['placa_veiculo']; ?>" required/>
                    </div>
                    <label class ="col-md-1 control-label">Cor</label>
                    <div class="col-md-2">
                        <input class="form-control input-md" type="text" id="cor" name="cor" placeholder="Cor" value="<?= $dadosTemp['cor']; ?>" required/>
                    </div>
                    <label class="col-md-1 control-label">Modelo</label>
                    <div class="col-md-2">
                        <select class="form-control" name = "id_modelo" required>
                            <option value="" selected>Selecione modelo...</option>
                            <?php
                            if (count($consulta2)) {
                                foreach ($consulta2 as $linha2) {
                                    ?>                                             
                                    <option value="<?php echo $linha2['id_modelo']; ?>" ><?php
                                        echo $linha2['nome_modelo'];
                                        echo ' ' . $linha2['ano_modelo'];
                                        ?></option> 
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label"></label>
                    <div class="col-md-10">
                        <button id="submit" name="submit" class="btn btn-primary">Salvar!</button>
                    </div>
                </div>
            </form>
            <p>
                <a href="veiculo.php" class="btn btn-success">Cadastrar veículo</a>
            </p>
            <legend id="cen">LISTA DE VEICULOS</legend>
            <table table align="center" id="cen" border="1" class="table table-hover" style="width: 1000px">
                <thead>
                    <tr>
                        <th id="cen">Placa</th>
                        <th id="cen">Cor</th>
                        <th id="cen">Modelo</th>
                        <th id="cen">Ano</th>
                        <th id="cen">Marca</th>
                        <th id="cen">Opção</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    foreach ($db->query('SELECT id_veiculo,placa_veiculo,cor,nome_modelo,ano_modelo,nome_marca FROM veiculo v '
                            . 'INNER JOIN modelo mo ON v.id_modelo = mo.id_modelo '
                            . 'INNER JOIN marca ma ON ma.id_marca = mo.id_marca ORDER BY id_veiculo DESC') as $linha) {
                        ?>
                        <tr>
                            <td>
                                <?= $linha['placa_veiculo'] ?>
                            </td>
                            <td>
                                <?= $linha['cor'] ?>
                            </td>
                            <td>
                                <?= $linha['nome_modelo'] ?>
                            </td>
                            <td>
                                <?= $linha['ano_modelo'] ?>
                            </td>
                            <td>
                                <?= $linha['nome_marca'] ?>
                            </td>
                            <td>
                                <a href="?id=<?= $linha['id_veiculo']; ?>&acao=deletar" onclick="<?= $acao = 'deletar'; ?>">Deletar</a>
                                <a href="?id=<?= $linha['id_veiculo']; ?>&acao=atualizar" onclick="<?= $acao = 'atualizar'; ?>">Atualizar</a>
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
