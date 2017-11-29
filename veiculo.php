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
    $dados[0] = $_POST['placa_veiculo'];
    $dados[1] = $_POST['cor'];
    $dados[2] = $_POST['id_modelo'];
    $dados[3] = $_POST['id_cliente'];
    $temp = $db->execute('INSERT INTO veiculo (placa_veiculo,cor,id_modelo,id_cliente) VALUES (?,?,?,?)', $dados);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='veiculo.php';</script>";
    }
    if ($flag) {
        $db->commit();
        echo"<script language='javascript' type='text/javascript'>alert('Registro cadastrado!');window.location.href='veiculo.php';</script>";
    }
}

$dadosTemp['id_modelo'] = '';
$dadosTemp['nome_modelo'] = '';
$dadosTemp['ano_modelo'] = '';
$consulta = $db->query('SELECT id_modelo,nome_modelo,ano_modelo FROM modelo');
$dadosTemp2['id_cliente'] = '';
$dadosTemp2['nome_cliente'] = '';
$consulta2 = $db->query('SELECT id_cliente,nome_cliente FROM cliente');
?>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8"/>
        <title>Cadastro de veículos - Estacionamento</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body>
        <script language ="JavaScript" type="text/javascript" src="MascaraValidacao.js"></script>
        <form class="form-horizontal" action="" method="post">
            <fieldset>

                <!-- Formulario -->
                <legend id="cen">CADASTRO DE VEÍCULOS</legend>

                <!--Input dono-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="placa">Proprietário</label>  
                    <div class="col-md-2">
                        <select class="form-control" name = "id_cliente" required>
                            <option value="" selected>Selecione proprietário...</option>
                            <?php
                            if (count($consulta2)) {
                                foreach ($consulta2 as $linha2) {
                                    ?>                                             
                                    <option value="<?php echo $linha2['id_cliente']; ?>" ><?php echo $linha2['nome_cliente']; ?></option> 
                                    <?php
                                }
                            }
                            ?>
                        </select> 
                    </div>
                </div>
                <!-- Input placa -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="placa">Placa</label>  
                    <div class="col-md-2">
                        <input id="placa" maxlength="8" name="placa_veiculo" type="text" placeholder="XXX-0000" class="form-control input-md" required="" onkeypress="mascara(this, '###-####')"/>
                    </div>
                </div>

                <!-- Imput cor -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="cor">Cor</label>  
                    <div class="col-md-2">
                        <input id="cor" name="cor" type="text" class="form-control input-md" placeholder="Cor..." required=""/>
                    </div>
                </div>

                <!-- Imput modelo -->
                <div class="form-group">
                    <label class="col-md-4 control-label">Modelo</label>
                    <div class = "col-md-2">
                        <select class="form-control" name = "id_modelo" required>
                            <option value="" selected>Selecione modelo...</option>
                            <?php
                            if (count($consulta)) {
                                foreach ($consulta as $linha) {
                                    ?>                                             
                                    <option value="<?php echo $linha['id_modelo']; ?>" ><?php
                                        echo $linha['nome_modelo'];
                                        echo ' ' . $linha['ano_modelo'];
                                        ?></option> 
                                    <?php
                                }
                            }
                            ?>
                        </select> 
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
                        <a href="veiculocrud.php" class="btn btn-success">Ver, Editar ou Deletar veículos</a>
                    </p>
                </div>
            </fieldset>
        </form>
<?php include_once 'footer.html'; ?>     
    </body>
</html>
