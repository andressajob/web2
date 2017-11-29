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
$dadosTemp['id_veiculo'] = '';
$dadosTemp['placa_veiculo'] = '';
$dadosTemp['id_ticket'] = '';
$dadosTemp['data_horario_entrada'] = '';
$consulta = $db->query('SELECT t.id_veiculo,t.id_ticket, placa_veiculo, data_horario_entrada FROM veiculo v INNER JOIN ticket t ON t.id_veiculo = v.id_veiculo INNER JOIN '
        . 'horario_veiculo h ON t.id_ticket = h.id_ticket AND data_horario_saida IS NULL GROUP BY t.id_veiculo,t.id_ticket,placa_veiculo,data_horario_entrada');
if (isset($_POST['registro'])) {
    $db->begin();
    $flag = true;
    $id_veiculo = $_POST['id_veiculo'];
    foreach ($consulta as $linha) {
        if ($linha['id_veiculo'] == $id_veiculo) {
            $id_ticket = $linha['id_ticket'];
        }
    }
    $dados[0] = $id_veiculo;
    $dados[1] = $id_ticket;
    $temp = $db->execute('UPDATE horario_veiculo SET data_horario_saida = now() WHERE id_veiculo=? AND id_ticket=?', $dados);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='saida_veiculo.php';</script>";
    }
    if ($flag) {
        $db->commit();
        echo"<script language='javascript' type='text/javascript'>alert('Registro cadastrado!');window.location.href='saida_veiculo.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8"/>
        <title>Registro saída - Estacionamento</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body onload="startTime()">
        <form class="form-horizontal" action="" method="post">
            <fieldset>
                <!-- Formulario -->
                <legend id="cen">REGISTRO DE SAÍDA</legend>

                <!-- Input placa -->
                <div class="form-group">
                    <label class="col-md-5 control-label" for="placa">Placa | Horário entrada</label>  
                    <div class="col-md-2">
                        <select class="form-control" name = "id_veiculo" required>
                            <option class="form-control" value="" selected>Selecione placa...</option>
                            <?php
                            if (count($consulta)) {
                                foreach ($consulta as $linha) {
                                    ?>                                             
                                    <option value="<?php echo $linha['id_veiculo']; ?>" ><?php echo $linha['placa_veiculo'] . ' | ' . $linha['data_horario_entrada'] ?></option> 
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
