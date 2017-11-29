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

if (isset($_POST['registro'])) {
    $db->begin();
    $flag = true;
    $id_veiculo = $_POST['id_veiculo'];
    $dados[0] = $_POST['id_box'];
    $dados[1] = $_POST['id_estacionamento'];
    $dados[2] = 'false';
    $dados[3] = $id_veiculo;
    $temp = $db->execute('INSERT INTO ticket (id_box,id_estacionamento,pago,id_veiculo) VALUES (?,?,?,?)', $dados);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='entrada_veiculo.php';</script>";
    }
    $lastId = $db->lastInsertId('ticket_id_ticket_seq');
    $temp = $db->execute('INSERT INTO horario_veiculo (id_veiculo,id_ticket,data_horario_entrada) VALUES (?,?,now())', [$id_veiculo, $lastId]);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='entrada_veiculo.php';</script>";
    }
    if ($flag) {
        $db->commit();
        echo"<script language='javascript' type='text/javascript'>alert('Registro cadastrado!');window.location.href='entrada_veiculo.php';</script>";
    }
}
$dadosTemp['id_box'] = '';
$dadosTemp['descricao'] = '';
$consulta = $db->query('SELECT * FROM box');
$dadosTemp2['id_estacionamento'] = '';
$dadosTemp2['nome_estacionamento'] = '';
$consulta2 = $db->query('SELECT id_estacionamento,nome_estacionamento FROM estacionamento');
$dadosTemp3['id_veiculo'] = '';
$dadosTemp3['placa_veiculo'] = '';
$consulta3 = $db->query('SELECT id_veiculo, placa_veiculo FROM veiculo');
?>

<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8"/>
        <title>Registro entrada - Estacionamento</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body onload="startTime()">
        <form class="form-horizontal" action="entrada_veiculo.php" method="post">
            <fieldset>
                <!-- Formulario -->
                <legend id="cen">REGISTRO DE ENTRADA</legend>

                <!-- Input placa -->
                <div class="form-group">
                    <label class="col-md-5 control-label" for="placa">Placa</label>  
                    <div class="col-md-2">
                        <select class="form-control" name = "id_veiculo" required>
                            <option value="" selected>Selecione placa...</option>
                            <?php
                            if (count($consulta3)) {
                                foreach ($consulta3 as $linha3) {
                                    ?>                                             
                                    <option value="<?php echo $linha3['id_veiculo']; ?>" ><?php echo $linha3['placa_veiculo']; ?></option> 
                                    <?php
                                }
                            }
                            ?>
                        </select>                 
					</div>
                </div>

                <!-- Input Horário -->
                <div class="form-group">
                    <label class="col-md-5 control-label" >Horário</label>  
                    <div id="txt" name ="data_horario_entrada" class="col-md-4">
                        <script>
                            function startTime() {
                                var today = new Date();
                                var h = today.getHours();
                                var m = today.getMinutes();
                                var s = today.getSeconds();
                                // add a zero in front of numbers<10
                                m = checkTime(m);
                                s = checkTime(s);
                                dia = today.getDate();
                                mes = today.getMonth() + 1;
                                ano = today.getFullYear();
                                dia = checkTime(dia);
                                mes = checkTime(mes);
                                document.getElementById("txt").innerHTML = dia + "/" + mes + "/" + ano + " - " + h + ":" + m + ":" + s;
                                var t = setTimeout(function () {
                                    startTime()
                                }, 500);
                            }

                            function checkTime(i) {
                                if (i < 10) {
                                    i = "0" + i;
                                }
                                return i;
                            }
                        </script>
                    </div>
                </div>
                <!--Input Estacionamento-->
                <div class="form-group">
                    <label class="col-md-5 control-label" for="estacionamento">Estacionamento</label>  
                    <div class="col-md-2">
                        <select class="form-control" name = "id_estacionamento" required>
                            <?php
                            if (count($consulta2)) {
                                foreach ($consulta2 as $linha2) {
                                    ?>                                             
                                    <option value="<?php echo $linha2['id_estacionamento']; ?>" ><?php echo $linha2['nome_estacionamento']; ?></option> 
                                    <?php
                                }
                            }
                            ?>
                        </select>                     
                    </div>
                </div>              
                <!-- Input box -->
                <div class="form-group">
                    <label class="col-md-5 control-label" for="placa">Box</label>  
                    <div class="col-md-2">
                        <select class="form-control" name = "id_box" required>
                            <option value="" selected>Selecione box...</option>
                            <?php
                            if (count($consulta)) {
                                foreach ($consulta as $linha) {
                                    ?>                                             
                                    <option value="<?php echo $linha['id_box']; ?>" ><?php echo $linha['descricao']; ?></option> 
                                    <?php
                                }
                            }
                            ?>
                        </select>                     
                    </div>
                </div>              
                <!-- Button -->
                <div class="form-group">
                    <label class="col-md-5 control-label" for="registro"></label>
                    <div class="col-md-4">
                        <button id="registro" name="registro" class="btn btn-primary">Registrar!</button>
                    </div>
                </div>

            </fieldset>
        </form>
        <?php include_once 'footer.html'; ?>     
    </body>
</html>
