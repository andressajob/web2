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
    $temp = $db->execute("DELETE FROM funcionario WHERE id_funcionario=?", $dados);
    if (!$temp) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível deletar este registro, referenciado em outra tabela.');window.location.href='funcionacrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro deletado!');window.location.href='funcionacrud.php';</script>";
    }
}
$dadosTemp['id_funcionario'] = '';
$dadosTemp['nome_funcionario'] = '';
$dadosTemp['cpf'] = '';
$dadosTemp['salario'] = '';
$dadosTemp['data_nascimento'] = '';
//atualizar
if (isset($acao) && $acao == 'atualizar') {
    $consulta = $db->query("SELECT id_funcionario,nome_funcionario,cpf,salario,data_nascimento FROM funcionario
                          WHERE id_funcionario = $id");
    foreach ($consulta as $linha) {
        $dadosTemp = $linha;
    }
    $acao = 'atualizarFim';
}
if (isset($_POST['submit']) && $acao == 'atualizarFim') {
    $dados[0] = $_POST['nome_funcionario'];
    $dados[1] = $_POST['cpf'];
    $dados[2] = $_POST['salario'];
    $dados[3] = $_POST['data_nascimento'];
    $dados[4] = $_POST['id_funcionario'];
    $result = $db->execute("UPDATE funcionario SET nome_funcionario=?,cpf=?,salario=?,data_nascimento=? WHERE id_funcionario=?", $dados);
    if (!$result) {
        echo"<script language='javascript' type='text/javascript'>alert('Impossível atualizar este registro, tente novamente.');window.location.href='funcionacrud.php';</script>";
    } else {
        echo"<script language='javascript' type='text/javascript'>alert('Registro atualizado.');window.location.href='funcionacrud.php';</script>";
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Lista de funcionarios</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <script language ="JavaScript" type="text/javascript" src="js/MascaraValidacao.js"></script>
        <?php include_once 'header.php'; ?>

    </head>
    <body>
        <div class="row" id="cen">
            <form action="funcionacrud.php?acao=<?= $acao ?>&id=<?= $id ?>" class="form-horizontal" method="POST">
                <div class="form-group">
                    <div class="col-md-10">
                        <label class="col-md-offset-2 control-label">Atualização de Funcionário</label>  
                    </div>
                </div>
                <div class="form-group"> 
                    <label class="col-md-1 control-label">ID</label>  
                    <div class="col-md-1">
                        <input  class="form-control input-md" type="text" id="id_funcionario" name="id_funcionario" value="<?= $dadosTemp['id_funcionario']; ?>" readonly/>
                    </div>
                    <label class ="col-md-1 control-label">Nome</label>
                    <div class="col-md-2">
                        <input  class="form-control input-md" autofocus id="nome_funcionario" type="text" name="nome_funcionario" placeholder="Nome" value="<?= $dadosTemp['nome_funcionario']; ?>" required/>
                    </div>
                    <label class ="col-md-1 control-label">CPF</label>
                    <div class="col-md-2">
                        <input class="form-control input-md" type="text" id="cpf" maxlength="14" name="cpf" placeholder="CPF" value="<?= $dadosTemp['cpf']; ?>" required="" />
                    </div>
                    <label class ="col-md-1 control-label">Salário (R$)</label>
                    <div class="col-md-2">
                        <input class="form-control input-md" type="number" min="1" id="salario" name="salario" placeholder="Salário" value="<?= $dadosTemp['salario']; ?>" required="" "/>
                    </div>
                </div>
                <div class="form-group">
                    <label class ="col-md-5 control-label">Data de Nascimento</label>
                    <div class="col-md-2">
                        <input class="form-control input-md" type="date" id="data" name="data_nascimento" placeholder="Data Nascimento" value="<?= $dadosTemp['data_nascimento']; ?>" required="" />
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
                <a href="funcionario.php" class="btn btn-success">Cadastrar funcionário</a>
            </p>
            <legend id="cen">LISTA DE FUNCIONÁRIOS</legend>
            <table table align="center" id="cen" border="1" class="table table-hover" style="width: 1000px">
                <thead>
                    <tr>
                        <th id="cen">Nome</th>
                        <th id="cen">CPF</th>
                        <th id="cen">Salário</th>
                        <th id="cen">Data Nascimento</th>                
                        <th id="cen">Opção</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($db->query('SELECT id_funcionario, nome_funcionario, cpf, salario, data_nascimento FROM funcionario ORDER BY id_funcionario DESC') as $linha) { ?>
                        <tr>
                            <td>
                                <?= $linha['nome_funcionario'] ?>
                            </td>
                            <td>
                                <?= $linha['cpf'] ?>
                            </td>
                            <td>
                                <?= $linha['salario'] ?>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($linha['data_nascimento'])) ?>
                            </td>
                            <td>
                                <a href="?id=<?= $linha['id_funcionario']; ?>&acao=deletar" onclick="<?= $acao = 'deletar'; ?>">Deletar</a>
                                <a href="?id=<?= $linha['id_funcionario']; ?>&acao=atualizar" onclick="<?= $acao = 'atualizar'; ?>">Atualizar</a>
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
