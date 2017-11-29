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
    $temp = $db->execute("DELETE FROM usuario WHERE id_usuario=?", $dados);
    if (!$temp) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível deletar este registro, referenciado em outra tabela.');window.location.href='usuariocrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro deletado!');window.location.href='usuariocrud.php';</script>";
    }
}
$dadosTemp['id_usuario'] = '';
$dadosTemp['login'] = '';
$dadosTemp['email'] = '';
$dadosTemp['senha'] = '';
//atualizar
if (isset($acao) && $acao == 'atualizar') {
    $consulta = $db->query("SELECT * FROM usuario
                          WHERE id_usuario = $id");
    foreach ($consulta as $linha) {
        $dadosTemp = $linha;
    }
    $acao = 'atualizarFim';
}
if (isset($_POST['submit']) && $acao == 'atualizarFim') {
    $dados[0] = $_POST['login'];
    $dados[1] = $_POST['senha'];
    $dados[2] = $_POST['email'];
    $dados[3] = $_POST['id_usuario'];
    $result = $db->execute("UPDATE usuario SET login=?,senha=?,email=? WHERE id_usuario=?", $dados);
    if (!$result) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível atualizar este registro, tente novamente.');window.location.href='usuariocrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro atualizado.');window.location.href='usuariocrud.php';</script>";
    }
}
?>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8">
        <title>Lista de Usuários</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body>
        <div class="row" id="cen">
            <form action="usuariocrud.php?acao=<?= $acao ?>&id=<?= $id ?>" class="form-horizontal" method="POST">
                <div class="form-group">
                    <div class="col-md-10">
                        <label class="col-md-offset-2 control-label">Atualização de usuário</label>  
                    </div>
                </div>
                <div class="form-group"> 
                    <label class="col-md-1 control-label">ID</label>  
                    <div class="col-md-1">
                        <input  class="form-control input-md" type="text" id="id_usuario" name="id_usuario" value="<?= $dadosTemp['id_usuario']; ?>" readonly/>
                    </div>
                    <label class ="col-md-1 control-label">Login</label>
                    <div class="col-md-2">
                        <input  class="form-control input-md" autofocus id="login" type="text" name="login" placeholder="Login" value="<?= $dadosTemp['login']; ?>" required/>
                    </div>
                    <label class ="col-md-1 control-label">Email</label>
                    <div class="col-md-2">
                        <input class="form-control input-md" type="text" id="email" name="email" placeholder="Email" value="<?= $dadosTemp['email']; ?>" required/>
                    </div>
                    <label class ="col-md-1 control-label">Senha</label>
                    <div class="col-md-2">
                        <input class="form-control input-md" type="password" id="senha" name="senha" placeholder="Senha" value="<?= $dadosTemp['senha']; ?>" required/>
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
                <a href="cadastro.php" class="btn btn-success">Cadastrar usuário</a>
            </p>
            <legend id="cen">LISTA DE USUÁRIOS</legend>
            <table table align="center" id="cen" border="1" class="table table-hover" style="width: 500px">
                <thead>
                    <tr>
                        <th id="cen">Login</th>
                        <th id="cen">Email</th>
                        <th id="cen">Opção</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($db->query('select * from usuario ORDER BY id_usuario DESC') as $linha) { ?>
                        <tr>
                            <td>
                                <?= $linha['login'] ?>
                            </td>
                            <td>
                                <?= $linha['email'] ?>
                            </td>
                            <td>
                                <a href="?id=<?= $linha['id_usuario']; ?>&acao=deletar" onclick="<?= $acao = 'deletar'; ?>">Deletar</a>
                                <a href="?id=<?= $linha['id_usuario']; ?>&acao=atualizar" onclick="<?= $acao = 'atualizar'; ?>">Atualizar</a>
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
