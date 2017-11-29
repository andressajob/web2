<?php
include_once "session.php";
require 'db/DB.class.php';
//inicia a conexão
$db = new DB('02080100', //usuario
        'Q2hC9PBz', //senha
        '02080100', //banco
        'webacademico.canoas.ifrs.edu.br'//servidor
);

extract($_REQUEST); //transformando os dados em variáveis
$dadosTemp['id_funcionario'] = '';
$dadosTemp['nome_funcionario'] = '';
$consulta = $db->query('SELECT id_funcionario,nome_funcionario FROM funcionario');
$dadosTemp2['id_tipo'] = '';
$dadosTemp2['descricao'] = '';
$consulta2 = $db->query('SELECT * FROM tipo');

if (isset($_POST['cadastro'])) {
    $db->begin();
    $flag = true;
    $dados[0] = $_POST['id_funcionario'];
    $dados[1] = $_POST['id_tipo'];
    $temp = $db->execute("INSERT INTO incidente (id_funcionario,data_incidente,id_tipo) VALUES (?,now(),?)", $dados);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='incidente.php';</script>";
    }
    if ($flag) {
        $db->commit();
        echo"<script language='javascript' type='text/javascript'>alert('Registro cadastrado!');window.location.href='incidente.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8"/>
        <title>Cadastro de incidentes - Estacionamento</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body onload="startTime()">
        <script language ="JavaScript" type="text/javascript" src="MascaraValidacao.js"></script>
        <form name="form2" class="form-horizontal" action="incidente.php" method="post">
            <fieldset>

                <!-- Formulario -->
                <legend id="cen">CADASTRO DE INCIDENTES</legend>

                <!-- Input nome -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="placa">Funcionário responsável</label>  
                    <div class="col-md-4">
                        <select class="form-control" name = "id_funcionario" required>
                            <option value="" selected>Selecione funcionário...</option>
                            <?php
                            if (count($consulta)) {
                                foreach ($consulta as $linha) {
                                    ?>                                             
                                    <option value="<?php echo $linha['id_funcionario']; ?>" ><?php echo $linha['nome_funcionario']; ?></option> 
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- Input Horário -->
                <div class="form-group">
                    <label class="col-md-4 control-label" >Horário</label>  
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
                <div class="form-group">
                    <label class="col-md-4 control-label">Tipo de incidente</label>  
                    <div class="col-md-4">
                        <select class="form-control" name = "id_tipo" required>
                            <option value="" selected>Selecione o tipo...</option>
                            <?php
                            $contador=0;
                            if (count($consulta2)) {
                                foreach ($consulta2 as $linha2) {
                                    $contador++;
                                    if ($contador>2){
                                    ?>                                             
                                    <option value="<?php echo $linha2['id_tipo']; ?>" ><?php echo $linha2['descricao']; ?></option> 
                                    <?php
                                    }
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
                        <button id="cadastro" name="cadastro" class="btn btn-primary">Cadastre!</button>
                    </div>
                </div>
                <div class="row" id="cen">
                    <p>
                        <a href="incidentecrud.php" class="btn btn-success">Ver, Editar ou Deletar incidentes</a>
                    </p>
                </div>
            </fieldset>
        </form>
        <?php include_once 'footer.html'; ?>     
    </body>
</html>
