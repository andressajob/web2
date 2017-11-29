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
    $temp = $db->execute("DELETE FROM estacionamento WHERE id_estacionamento=?", $dados);
    if (!$temp) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível deletar este registro, referenciado em outra tabela.');window.location.href='estacionacrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro deletado!');window.location.href='estacionacrud.php';</script>";
    }
}
$dadosTemp['id_estacionamento'] = '';
$dadosTemp['nome_estacionamento'] = '';
$dadosTemp['cnpj'] = '';
$dadosTemp['vagas'] = '';
//atualizar
if (isset($acao) && $acao == 'atualizar') {
    $consulta = $db->query("SELECT id_estacionamento,cnpj,nome_estacionamento,vagas FROM estacionamento
                          WHERE id_estacionamento = $id");
    foreach ($consulta as $linha) {
        $dadosTemp = $linha;
    }
    $acao = 'atualizarFim';
}
if (isset($_POST['submit']) && $acao == 'atualizarFim') {
    $dados[0] = $_POST['cnpj'];
    $dados[1] = $_POST['vagas'];
    $dados[2] = $_POST['nome_estacionamento'];
    $dados[3] = $_POST['id_estacionamento'];
    $result = $db->execute("UPDATE estacionamento SET cnpj=?,vagas=?,nome_estacionamento=? WHERE id_estacionamento=?", $dados);
    if (!$result) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível atualizar este registro, tente novamente.');window.location.href='estacionacrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro atualizado.');window.location.href='estacionacrud.php';</script>";
    }
}
?>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8">
        <title>Lista de Estacionamentos</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body>
    <div class="row" id="cen">
        <form action="estacionacrud.php?acao=<?= $acao ?>&id=<?= $id ?>" class="form-horizontal" method="POST" >
            <div class="form-group">
                <div class="col-md-12">
                    <label class="col-md-2-offset control-label">Atualização de estacionamento</label>  
                </div>
            </div>
            <div class="form-group"> 
                <label class="col-md-1 control-label">ID</label>  
                <div class="col-md-1">
                    <input  class="form-control input-md" type="text" id="id_estacionamento" name="id_estacionamento" value="<?= $dadosTemp['id_estacionamento']; ?>" readonly/>
                </div>
                <label class ="col-md-1 control-label">CNPJ</label>
                <div class="col-md-2">
                    <input  class="form-control input-md" autofocus id="cnpj" type="text" name="cnpj" placeholder="CNPJ" value="<?= $dadosTemp['cnpj']; ?>" required/>
                </div>
                <label class ="col-md-1 control-label">Nome</label>
                <div class="col-md-2">
                    <input  class="form-control input-md" id="nome_estacionamento" type="text" name="nome_estacionamento" placeholder="Nome" value="<?= $dadosTemp['nome_estacionamento']; ?>" required/>
                </div>
                <label class ="col-md-1 control-label">Vagas</label>
                <div class="col-md-2">
                    <input class="form-control input-md" type="text" id="vagas" name="vagas" placeholder="Nº de vagas" value="<?= $dadosTemp['vagas']; ?>" required/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label"></label>
                <div class="col-md-10">
                    <button id="cen" name="submit" class="btn btn-primary">Salvar!</button>
                </div>
            </div>
        </form>
        <p>
            <a href="estacionamento.php" class="btn btn-success">Cadastrar estacionamento</a>
        </p>
            <legend id="cen">LISTA DE ESTACIONAMENTOS</legend>
        <table table align="center" id="cen" border="1" class="table table-hover" style="width: 500px">
            <thead>
                <tr>
                    <th id="cen">Nome</th>
                    <th id="cen">CNPJ</th>
                    <th id="cen">Número de vagas</th>
                    <th id="cen">Opção</th>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($db->query('SELECT id_estacionamento,nome_estacionamento,cnpj,vagas FROM estacionamento ORDER BY id_estacionamento DESC') as $linha) {
                    ?>
                    <tr>
                        <td>
                            <?= $linha['nome_estacionamento'] ?>
                        </td>
                        <td>
                            <?= $linha['cnpj'] ?>
                        </td>
                        <td>
                            <?= $linha['vagas'] ?>
                        </td>
                        <td>
                            <a href="?id=<?= $linha['id_estacionamento']; ?>&acao=deletar" onclick="<?= $acao = 'deletar'; ?>">Deletar</a>
                            <a href="?id=<?= $linha['id_estacionamento']; ?>&acao=atualizar" onclick="<?= $acao = 'atualizar'; ?>">Atualizar</a>
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
