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
    $db->begin();
    $flag = true;
    $dados[0] = $id;
    $temp = $db->execute("DELETE FROM marca WHERE id_marca=?", $dados);
    if (!$temp) {
        $db->rollback();
        $flag = FALSE;
        echo"<script language='javascript' type='text/javascript'>alert('Impossível deletar este registro, referenciado em outra tabela.');window.location.href='marcacrud.php';</script>";
    }
    if ($flag) {
        $db->commit();
        echo"<script language='javascript' type='text/javascript'>alert('Registro deletado!');window.location.href='marcacrud.php';</script>";
    }
}
$dadosTemp['id_marca'] = '';
$dadosTemp['nome_marca'] = '';
//atualizar
if (isset($acao) && $acao == 'atualizar') {
    $consulta = $db->query("SELECT * FROM marca
                          WHERE id_marca = $id");
    foreach ($consulta as $linha) {
        $dadosTemp = $linha;
    }
    $acao = 'atualizarFim';
}
if (isset($_POST['submit']) && $acao == 'atualizarFim') {
    $dados[0] = $_POST['nome_marca'];
    $dados[1] = $_POST['id_marca'];
    $result = $db->execute("UPDATE marca SET nome_marca=? WHERE id_marca=?", $dados);
    if (!$result) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível atualizar este registro, tente novamente.');window.location.href='marcacrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro atualizado.');window.location.href='marcacrud.php';</script>";
    }
}
?>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8">
        <title>Lista de Marcas</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body>
        <div class="row" id="cen">
            <form action="marcacrud.php?acao=<?= $acao ?>&id=<?= $id ?>" class="form-horizontal" method="POST">
                <div class="form-group">
                    <div class="col-md-10">
                        <label class="col-md-offset-2 control-label">Atualização de marca</label>  
                    </div>
                </div>
                <div class="form-group"> 
                    <label class="col-md-3 control-label">ID</label>  
                    <div class="col-md-1">
                        <input  class="form-control input-md" type="text" id="id_marca" name="id_marca" value="<?= $dadosTemp['id_marca']; ?>" readonly/>
                    </div>
                    <label class ="col-md-1 control-label">Nome da Marca</label>
                    <div class="col-md-2">
                        <input  class="form-control input-md" autofocus id="nome_marca" type="text" name="nome_marca" placeholder="Marca" value="<?= $dadosTemp['nome_marca']; ?>" required/>
                    </div>
                    <div class="col-md-1">
                        <button id="submit" name="submit" class="btn btn-primary">Salvar!</button>
                    </div>
                </div>
            </form>
            <p>
                <a href="marca.php" class="btn btn-success">Cadastrar marca</a>
            </p>
            <legend id="cen">LISTA DE MARCAS</legend>
            <table table align="center" id="cen" border="1" class="table table-hover" style="width: 500px">
                <thead>
                    <tr>
                        <th id="cen">Nome</th>
                        <th id="cen">Opção</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($db->query('SELECT * from marca ORDER BY id_marca DESC') as $linha) { ?>
                        <tr>
                            <td>
                                <?= $linha['nome_marca'] ?>
                            </td>
                            <td>
                                <a href="?id=<?= $linha['id_marca']; ?>&acao=deletar" onclick="<?= $acao = 'deletar'; ?>">Deletar</a>
                                <a href="?id=<?= $linha['id_marca']; ?>&acao=atualizar" onclick="<?= $acao = 'atualizar'; ?>">Atualizar</a>
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
