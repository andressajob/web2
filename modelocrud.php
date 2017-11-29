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
$dadosTemp2['id_marca'] = '';
$dadosTemp2['nome_marca'] = '';
$consulta2 = $db->query('SELECT * FROM marca');
//deletar
if (isset($id) && $acao == 'deletar') {
    $dados[0] = $id;
    $temp = $db->execute("DELETE FROM modelo WHERE id_modelo=?", $dados);
    if (!$temp) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível deletar este registro, referenciado em outra tabela.');window.location.href='modelocrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro deletado!');window.location.href='modelocrud.php';</script>";
    }
}
$dadosTemp['id_modelo'] = '';
$dadosTemp['nome_modelo'] = '';
$dadosTemp['ano_modelo'] = '';
//atualizar
if (isset($acao) && $acao == 'atualizar') {
    $consulta = $db->query("SELECT id_modelo, nome_modelo,ano_modelo FROM modelo
                          WHERE id_modelo = $id");
    foreach ($consulta as $linha) {
        $dadosTemp = $linha;
    }
    $acao = 'atualizarFim';
}
if (isset($_POST['submit']) && $acao == 'atualizarFim') {
    $dados[0] = $_POST['nome_modelo'];
    $dados[1] = $_POST['ano_modelo'];
    $dados[2] = $_POST['id_marca'];
    $dados[3] = $_POST['id_modelo'];
    $result = $db->execute("UPDATE modelo SET nome_modelo=?,ano_modelo=?,id_marca=? WHERE id_modelo=?", $dados);
    if (!$result) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível atualizar este registro, tente novamente.');window.location.href='modelocrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro atualizado.');window.location.href='modelocrud.php';</script>";
    }
}
?>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8">
        <title>Lista de Modelos</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body>
        <div class="row" id="cen">
            <form action="modelocrud.php?acao=<?= $acao ?>&id=<?= $id ?>" class="form-horizontal" method="POST">
                <div class="form-group">
                    <div class="col-md-10">
                        <label class="col-md-offset-2 control-label">Atualização de modelo</label>  
                    </div>
                </div>
                <div class ="form-group">
                    <label class="col-md-1 control-label">ID</label>  
                    <div class="col-md-1">
                        <input  class="form-control input-md" type="text" id="id_modelo" name="id_modelo" value="<?= $dadosTemp['id_modelo']; ?>" readonly/>
                    </div>
                    <label class ="col-md-1 control-label">Nome</label>
                    <div class="col-md-2">
                        <input  class="form-control input-md" autofocus id="nome_modelo" type="text" name="nome_modelo" placeholder="Modelo" value="<?= $dadosTemp['nome_modelo']; ?>" required/>
                    </div>
                    <label class ="col-md-1 control-label">Ano</label>
                    <div class="col-md-2">
                        <input  class="form-control input-md" id="ano_modelo" type="text" name="ano_modelo" placeholder="Ano" value="<?= $dadosTemp['ano_modelo']; ?>" required/>
                    </div>
                    <label class="col-md-1 control-label">Marca</label>
                    <div class="col-md-2">
                        <select class="form-control" name = "id_marca" required>
                            <option value="" selected>Selecione marca...</option>
                            <?php
                            if (count($consulta2)) {
                                foreach ($consulta2 as $linha2) {
                                    ?>                                             
                                    <option value="<?php echo $linha2['id_marca']; ?>" ><?php echo $linha2['nome_marca']; ?></option> 
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
                <a href="modelo.php" class="btn btn-success">Cadastar modelo</a>
            </p>
            <legend id="cen">LISTA DE MODELOS</legend>
            <table table align="center" id="cen" border="1" class="table table-hover" style="width: 500px">
                <thead>
                    <tr>
                        <th id="cen">Nome</th>
                        <th id="cen">Ano</th>
                        <th id="cen">Marca</th>
                        <th id="cen">Opção</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    foreach ($db->query('SELECT id_modelo,nome_modelo,ano_modelo,nome_marca FROM modelo mo '
                            . 'INNER JOIN marca ma ON mo.id_marca = ma.id_marca ORDER BY id_modelo DESC') as $linha) {
                        ?>
                        <tr>
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
                                <a href="?id=<?= $linha['id_modelo']; ?>&acao=deletar" onclick="<?= $acao = 'deletar'; ?>">Deletar</a>
                                <a href="?id=<?= $linha['id_modelo']; ?>&acao=atualizar" onclick="<?= $acao = 'atualizar'; ?>">Atualizar</a>
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
