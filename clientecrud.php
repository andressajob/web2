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
if (isset($acao) && $acao == 'deletar') {
    $dados[0] = $id;
    $temp = $db->execute("DELETE FROM cliente WHERE id_cliente=?", $dados);
    if (!$temp) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível deletar este registro, referenciado em outra tabela.');window.location.href='clientecrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro deletado!');window.location.href='clientecrud.php';</script>";
    }
}
$dadosTemp2['id_tipo'] = '';
$dadosTemp2['descricao'] = '';
$consulta2 = $db->query("SELECT * FROM tipo GROUP BY id_tipo, descricao HAVING (id_tipo < 3)");
$dadosTemp['id_cliente'] = '';
$dadosTemp['nome_cliente'] = '';
$dadosTemp['cpf'] = '';
$dadosTemp['contato'] = '';
$dadosTemp['id_tipo'] = '';
//atualizar
if (isset($acao) && $acao == 'atualizar') {
    $consulta = $db->query("SELECT id_cliente,nome_cliente,cpf,contato FROM cliente
                          WHERE id_cliente = $id");
    foreach ($consulta as $linha) {
        $dadosTemp = $linha;
    }
    $acao = 'atualizarFim';
}
if (isset($_POST['submit']) && $acao == 'atualizarFim') {
    $dados[0] = $_POST['nome_cliente'];
    $dados[1] = $_POST['cpf'];
    $dados[2] = $_POST['contato'];
    $dados[3] = $_POST['id_tipo'];
    $dados[4] = $_POST['id_cliente'];
    $result = $db->execute("UPDATE cliente SET nome_cliente=?,cpf=?,contato=?,id_tipo=? WHERE id_cliente=?", $dados);
    if (!$result) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível atualizar este registro, tente novamente.');window.location.href='clientecrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro atualizado.');window.location.href='clientecrud.php';</script>";
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Lista de Clientes</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <script language ="JavaScript" type="text/javascript" src="js/MascaraValidacao.js"></script>
        <?php include_once 'header.php'; ?>
    </head>
    <body>
        <div class="row" id="cen">
            <form action="clientecrud.php?acao=<?= $acao ?>&id=<?= $id ?>" class="form-horizontal" method="POST">
                <div class="form-group">
                    <div class="col-md-10">
                        <label class="col-md-offset-2 control-label">Atualização de cliente</label>  
                    </div>
                </div>
                <div class="form-group"> 
                    <label class="col-md-1 control-label">ID</label>  
                    <div class="col-md-1">
                        <input  class="form-control input-md" type="text" id="id_cliente" name="id_cliente" value="<?= $dadosTemp['id_cliente']; ?>" readonly/>
                    </div>
                    <label class ="col-md-1 control-label">Nome</label>
                    <div class="col-md-2">
                        <input  class="form-control input-md" autofocus id="nome_cliente" type="text" name="nome_cliente" placeholder="Nome" value="<?= $dadosTemp['nome_cliente']; ?>" required/>
                    </div>
                    <label class ="col-md-1 control-label">CPF</label>
                    <div class="col-md-2">
                        <input class="form-control input-md" type="text" id="cpf" maxlength="14" name="cpf" placeholder="CPF" value="<?= $dadosTemp['cpf']; ?>" required="" onkeypress="mascara(this, '###.###.###-##')"/>
                    </div>
                    <label class ="col-md-1 control-label">Contato</label>
                    <div class="col-md-2">
                        <input class="form-control input-md" type="text" id="contato" maxlength="13" name="contato" placeholder="Contato" value="<?= $dadosTemp['contato']; ?>" required="" onkeypress="mascara(this, '## #####-####')"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class ="col-md-5 control-label">Tipo</label>
                    <div class="col-md-2">
                        <select class="form-control" name = "id_tipo" required>
                            <option class="form-control" value="" selected>Selecione tipo...</option>
                            <?php
                            if (count($consulta2)) {
                                foreach ($consulta2 as $linha2) {
                                    ?>                                             
                                    <option value="<?php echo $linha2['id_tipo']; ?>" ><?php echo $linha2['descricao']; ?></option> 
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
                <a href="cliente.php" class="btn btn-success">Cadastrar cliente</a>
            </p>
            <legend id="cen">LISTA DE CLIENTES</legend>
            <table table align="center" id="cen" border="1" class="table table-hover" style="width: 1000px">
                <thead>
                    <tr>
                        <th id="cen">Nome</th>
                        <th id="cen">CPF</th>
                        <th id="cen">Contato</th>
                        <th id="cen">Tipo</th>               
                        <th id="cen">Opção</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($db->query('SELECT id_cliente,nome_cliente, cpf, contato, descricao FROM cliente c INNER JOIN
            tipo t ON c.id_tipo=t.id_tipo GROUP BY id_cliente,nome_cliente,cpf,contato,descricao ORDER BY id_cliente DESC') as $linha) { ?>
                        <tr>
                            <td>
                                <?= $linha['nome_cliente'] ?>
                            </td>
                            <td>
                                <?= $linha['cpf'] ?>
                            </td>
                            <td>
                                <?= $linha['contato'] ?>

                            </td>                    
                            <td>
                                <?= $linha['descricao'] ?>

                            </td>
                            <td>
                                <a href="?id=<?= $linha['id_cliente']; ?>&acao=deletar" onclick="<?= $acao = 'deletar'; ?>">Deletar</a>
                                <a href="?id=<?= $linha['id_cliente']; ?>&acao=atualizar" onclick="<?= $acao = 'atualizar'; ?>">Atualizar</a>
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
