<?php
include_once "session.php";
require 'db/DB.class.php';
//inicia a conexão
$db = new DB('02080100', //usuario
        'Q2hC9PBz', //senha
        '02080100', //banco
        'webacademico.canoas.ifrs.edu.br'//servidor
);

extract($_POST); //transformando os dados em variáveis
if (isset($_POST['cadastro'])) {
    $db->begin();
    $flag = true;
    $dados[0] = $_POST['nome_marca'];
    $temp = $db->execute("INSERT INTO marca (nome_marca) VALUES (?)", $dados);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='marca.php';</script>";
    }
    if ($flag) {
        $db->commit();
        echo"<script language='javascript' type='text/javascript'>alert('Registro cadastrado!');window.location.href='marca.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8"/>
        <title>Cadastro de marcas - Estacionamento</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body>
        <script language ="JavaScript" type="text/javascript" src="MascaraValidacao.js"></script>
        <form name="form2" class="form-horizontal" action="marca.php" method="post">
            <fieldset>

                <!-- Formulario -->
                <legend id="cen">CADASTRO DE MARCAS</legend>

                <!-- Input nome -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="placa">Nome</label>  
                    <div class="col-md-4">
                        <input id="nome_marca" name="nome_marca" type="text" placeholder="Nome da marca..." class="form-control input-md" required=""/>
                    </div>
                </div>

                <!-- Button -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="cadastro"></label>
                    <div class="col-md-4">
                        <button id="cadastro" name="cadastro" class="btn btn-primary">Cadastre!</button>
                    </div>
                </div>
                <div class="row" id="cen">
                    <p>
                        <a href="marcacrud.php" class="btn btn-success">Ver, Editar ou Deletar marcas</a>
                    </p>
                </div>
            </fieldset>
        </form>
        <?php include_once 'footer.html'; ?>     
    </body>
</html>
