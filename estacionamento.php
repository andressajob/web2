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
$dadosTemp['id_cidade'] = '';
$dadosTemp['nome_cidade'] = '';
$consulta = $db->query('SELECT * FROM cidade');
if (isset($_POST['cadastro'])) {
    $db->begin();
    $flag = false;
    $dados[0] = $_POST['logradouro'];
    $dados[1] = $_POST['numero'];
    $dados[2] = $_POST['bairro'];
    $dados[3] = $_POST['cep'];
    $dados[4] = $_POST['id_cidade'];
    $temp = $db->execute('INSERT INTO endereco (logradouro,numero,bairro,cep,id_cidade) VALUES (?,?,?,?,?)', $dados);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='estacionamento.php';</script>";
    }
    $data[0] = $_POST['cnpj'];
    $data[1] = $_POST['nome_estacionamento'];
    $data[2] = $_POST['vagas'];
    $data[3] = $db->lastInsertId('endereco_id_endereco_seq');
    $temp = $db->execute('INSERT INTO estacionamento (cnpj,nome_estacionamento,vagas,id_endereco) VALUES (?,?,?,?)', $data);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='estacionamento.php';</script>";
    }
    if ($flag) {
        $db->commit();
        echo"<script language='javascript' type='text/javascript'>alert('Registro cadastrado!');window.location.href='estacionamento.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'header.php'; ?>
        <meta charset="UTF-8"/>
        <title>Cadastro de estacionamentos - Estacionamento</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    </head>
    <body>
        <script language ="JavaScript" type="text/javascript" src="js/MascaraValidacao.js"></script>
        <form name="form2" class="form-horizontal" action="estacionamento.php" method="post">
            <fieldset>

                <!-- Formulario -->
                <legend id="cen">CADASTRO DE ESTACIONAMENTOS</legend>

                <!-- Input nome -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="estacionamento">Nome</label>  
                    <div class="col-md-4">
                        <input id="nome" name="nome_estacionamento" type="text" placeholder="Nome do estacionamento..." class="form-control input-md" required=""/>
                    </div>
                </div>

                <!-- Imput CNPJ -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="cnpj">CNPJ</label>  
                    <div class="col-md-2">
                        <input id="cor" name="cnpj" type="text" class="form-control input-md" placeholder="CNPJ" maxlength="10" required="" onkeypress="mascara(this, '########-##')"/>
                    </div>
                </div>

                <!-- Imput vagas -->
                <div class="form-group">
                    <label class="col-md-4 control-label">Nº de vagas</label>
                    <div class = "col-md-2">
                        <input id="vagas" name="vagas"type="number" min ="1" class="form-control input-md"  required="">
                    </div>
                </div>

                <!-- Imput Endereço -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="cidade">Cidade</label>  
                    <div class="col-md-4">
                        <select name = "id_cidade" required>
                            <option value="" selected>Selecione cidade...</option>
                            <?php
                            if (count($consulta)) {
                                foreach ($consulta as $linha) {
                                    ?>                                             
                                    <option value="<?php echo $linha['id_cidade']; ?>" ><?php echo $linha['nome_cidade']; ?></option> 
                                    <?php
                                }
                            }
                            ?>
                        </select>     
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="rua">Logradouro</label>  
                    <div class="col-md-4">
                        <input id="logradouro" name="logradouro" type="text" placeholder="Nome da rua..." class="form-control input-md" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="numero">Número</label>  
                    <div class="col-md-2">
                        <input id="numero" name="numero" min="1" accept=""type="number" placeholder="Número..." class="form-control input-md" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="cep">CEP</label>  
                    <div class="col-md-4">
                        <input id="cep" name="cep" type="text" placeholder="Número CEP..." class="form-control input-md" required="" maxlength="8""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="bairro">Bairro</label>  
                    <div class="col-md-4">
                        <input id="nome" name="bairro" type="text" placeholder="Nome do bairro..." class="form-control input-md" required=""/>
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
                        <a href="estacionacrud.php" class="btn btn-success">Ver, Editar ou Deletar estacionamentos</a>
                    </p>
                </div>
            </fieldset>
        </form>
        <?php include_once 'footer.html'; ?>     
    </body>
</html>
