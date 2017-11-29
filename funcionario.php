<?php
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
$dadosTemp2['id_estacionamento'] = '';
$dadosTemp2['nome_estacionamento'] = '';
$consulta2 = $db->query('SELECT id_estacionamento,nome_estacionamento FROM estacionamento');

if (isset($_POST['submit'])) {
    $db->begin();
    $flag = true;
    $dados[0] = $_POST['login'];
    $dados[1] = $_POST['senha'];
    $dados[2] = $_POST['email'];
    $temp = $db->execute('INSERT INTO usuario (login,senha,email) VALUES (?,?,?)', $dados);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='funcionario.php';</script>";
    }
    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cep = $_POST['cep'];
    $cidade = $_POST['id_cidade'];
    $dados = [$logradouro, $numero, $bairro, $cep, $cidade];
    $temp = $db->execute("INSERT INTO endereco (logradouro,numero,bairro,cep,id_cidade) VALUES (?,?,?,?,?)", $dados);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='funcionario.php';</script>";
    }
    $lastId = $db->lastInsertId('endereco_id_endereco_seq');
    $lastId2 = $db->lastInsertId('usuario_id_usuario_seq');
    $nome_func = $_POST['nome_funcionario'];
    $cpf = $_POST['cpf'];
    $data_nasc = $_POST['data_nascimento'];
    $salario = $_POST['salario'];
    $regime = $_POST['regime_trabalho'];
    $id_estacionamento = $_POST['id_estacionamento'];
    $dados = [$lastId2, $nome_func, $cpf, $data_nasc, $salario, $regime, $lastId, $id_estacionamento];
    $temp = $db->execute("INSERT INTO funcionario (id_funcionario,nome_funcionario,cpf,data_nascimento,salario,regime_trabalho,id_endereco,id_estacionamento) VALUES (?,?,?,?,?,?,?,?)", $dados);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='funcionario.php';</script>";
    }
    if ($flag) {
        $db->commit();
        echo"<script language='javascript' type='text/javascript'>alert('Registro cadastrado!');window.location.href='funcionario.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'header.html'; ?>
        <meta charset="UTF-8"/>
        <title>Cadastro de funcionário - Estacionamento</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <script language ="JavaScript" type="text/javascript" src="js/MascaraValidacao.js"></script>
    </head>
    <body>
        <form name ="form1" class="form-horizontal" action="funcionario.php" method="post">
            <fieldset>

                <!-- Formulario -->
                <legend id="cen">CADASTRO DE FUNCIONÁRIO</legend>
                <!-- Imput email -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="email">Email</label>  
                    <div class="col-md-4">
                        <input id="email" name="email" type="text" placeholder="seuemail@exemplo.com" class="form-control input-md" required="" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"/>
                    </div>
                </div>

                <!-- Input Usuario -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="login">Login</label>  
                    <div class="col-md-4">
                        <input id="login" name="login" type="text" placeholder="Login..." class="form-control input-md" required=""/> 
                    </div>
                </div>

                <!-- Input Senha -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="password">Senha</label>
                    <div class="col-md-4">
                        <input id="senha" name="senha" type="password" placeholder="Sua senha..." class="form-control input-md" required=""/>
                    </div>
                </div>
                <!-- Input nome -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="nome">Nome Completo</label>  
                    <div class="col-md-4">
                        <input id="nome" name="nome_funcionario" type="text" placeholder="Seu nome aqui..." class="form-control input-md" required=""/>
                    </div>
                </div>
                <!-- Input datanascimento -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="nome">Data de nascimento</label>  
                    <div class="col-md-4">
                        <input id="data" name="data_nascimento" type="date" placeholder="Data de nascimento..." class="form-control input-md" required=""/> 
                    </div>
                </div>
                <!-- Input CPF -->
                <div class="form-group">
                    <label class="col-md-4 control-label">CPF</label>  
                    <div class="col-md-4">
                        <input id="cpf" name="cpf" type="text" maxlength="11" placeholder="Seu CPF" class="form-control input-md" required=""/>
                    </div>
                </div>

                <!-- Input Salário -->
                <div class="form-group">
                    <label class="col-md-4 control-label">Salário </label>
                    <div class = "col-md-2">
                        <input type="number" min="0" class="form-control" id="salario" name="salario" placeholder="Salário">
                    </div>
                </div>
                <!--Input Regime-->
                <div class="form-group">
                    <label class="col-md-4 control-label" >Regime de trabalho (hrs/semana)</label>  
                    <div class="col-md-2">
                        <input id="regime_trabalho" name="regime_trabalho" min="30" accept=""type="number" placeholder="Regime..." class="form-control input-md" required=""/>
                    </div>
                </div>
                <!-- Input Estacionamento-->
                <div class="form-group">
                    <label class="col-md-4 control-label" >Estacionamento</label>  
                    <div class="col-md-4">
                        <select class="form-control" name ="id_estacionamento" required>
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
                <!-- Input Endereço -->
                <div class="form-group">
                    <label class="col-md-4 control-label" >Cidade</label>  
                    <div class="col-md-4">
                        <select class="form-control" name = "id_cidade" required>
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
                    <label class="col-md-4 control-label" >Logradouro</label>  
                    <div class="col-md-4">
                        <input id="logradouro" name="logradouro" type="text" placeholder="Nome da rua..." class="form-control input-md" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" >Número</label>  
                    <div class="col-md-2">
                        <input id="numero" name="numero" min="1" accept=""type="number" placeholder="Número..." class="form-control input-md" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" >CEP</label>  
                    <div class="col-md-4">
                        <input id="cep" name="cep" type="text" maxlength="8" placeholder="Número CEP..." class="form-control input-md" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" >Bairro</label>  
                    <div class="col-md-4">
                        <input id="nome" name="bairro" type="text" placeholder="Nome do bairro..." class="form-control input-md" required=""/>
                    </div>
                </div>

                <!-- Button -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="cadastro"></label>
                    <div class="col-md-4">
                        <button id="submit" name="submit" class="btn btn-primary">Ok!</button>
                    </div>
                </div>
                <div class="row" id="cen">
                    <p>
                        <a href="usuariocrud.php" class="btn btn-success">Ver, Editar ou Deletar usuários</a>
                    </p>
                </div>
                <div class="row" id="cen">
                    <p>
                        <a href="funcionacrud.php" class="btn btn-success">Ver, Editar ou Deletar funcionários</a>
                    </p>
                </div>
            </fieldset>
        </form>
        <?php include_once 'footer.html'; ?>     
    </body>
</html>
