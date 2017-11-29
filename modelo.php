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
$dadosTemp['id_marca'] = '';
if (isset($_POST['cadastro'])) {
    $db->begin();
    $flag = true;
    $nome_marca = $_POST['nome_marca'];
    $string = "SELECT id_marca from marca where nome_marca ILIKE '$nome_marca'";
    $pesq = $db->queryAsArray($string);
    if (!$pesq) {
        $dadoMarca = array($nome_marca);
        $op = $db->execute("INSERT INTO marca (nome_marca) values (?)",$dadoMarca);
        if (!$op) {
            $db->rollback();
        }
        $marca = $db->lastInsertId('marca_id_marca_seq');
    } else {
        foreach ($pesq as $linha) {
            $marca = $linha['id_marca'];
            echo $marca;
        }

    }
    $dados[0] = $_POST['nome_modelo'];
    $dados[1] = $_POST['ano_modelo'];
    $dados[2] = $marca;
    $temp = $db->execute("INSERT INTO modelo (nome_modelo,ano_modelo,id_marca) VALUES (?,?,?)", $dados);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='modelo.php';</script>";
    }
    if ($flag) {
        $db->commit();
        echo"<script language='javascript' type='text/javascript'>alert('Registro cadastrado!');window.location.href='modelo.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8"/>
        <title>Cadastro de modelos - Estacionamento</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body>
        <script language ="JavaScript" type="text/javascript" src="MascaraValidacao.js"></script>
        <form name="form2" class="form-horizontal" action="modelo.php" method="post">
            <fieldset>

                <!-- Formulario -->
                <legend id="cen">CADASTRO DE MODELOS</legend>

                <!-- Input nome -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="placa">Nome</label>  
                    <div class="col-md-4">
                        <input id="nome_modelo" name="nome_modelo" type="text" placeholder="Nome do modelo..." class="form-control input-md" required=""/>
                    </div>
                </div>
                <!--Input marca-->
                <div class = "form-group">
                    <label class="col-md-4 control-label" for="id_marca">Marca</label>
                    <div class="col-md-4">
						<input id="nome_marca" name="nome_marca" type="text" placeholder="Nome da marca..." class="form-control input-md" required=""/>
                    </div>
                </div>
                <!--Input ano-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for ="ano_modelo">Ano</label>  
                    <div class="col-md-2">
                        <input id="ano_modelo" name="ano_modelo" min="1940" accept=""type="number" placeholder="Ano..." class="form-control input-md" required=""/>
                    </div>
                </div>
                <!-- Button -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="cadastro"></label>
                    <div class="col-md-4">
                        <button id="cadastro" name="cadastro" class="btn btn-primary">Ok!</button>
                    </div>
                </div>
                <div class="row" id="cen">
                    <p>
                        <a href="modelocrud.php" class="btn btn-success">Ver, Editar ou Deletar modelos</a>
                    </p>
                </div>
            </fieldset>
        </form>
        <?php include_once 'footer.html'; ?>     
    </body>
</html>
