<?php
require 'db/DB.class.php';
//inicia a conexão
$db = new DB('02080100', //usuario
        'Q2hC9PBz', //senha
        '02080100', //banco
        'webacademico.canoas.ifrs.edu.br'//servidor
);

extract($_REQUEST); //transformando os dados em variáveis
$dadosTemp['id_cidade'] = '';
$dadosTemp['nome_cidade'] = '';
$consulta = $db->query('SELECT * FROM cidade');

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
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='cliente.php';</script>";
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
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='cliente.php';</script>";
    }
    $id = $db->lastInsertId('usuario_id_usuario_seq');
    $endereco = $db->lastInsertId('endereco_id_endereco_seq');
    $nome = $_POST['nome_cliente'];
    $cpf = $_POST['cpf'];
    $contato = $_POST['contato'];
    $tipo = $_POST['id_tipo'];
    $data = [$id, $nome, $cpf, $contato, $endereco, $tipo];
    $temp = $db->execute('INSERT INTO cliente (id_cliente,nome_cliente,cpf,contato,id_endereco,id_tipo) VALUES (?,?,?,?,?,?)', $data);
    if (!$temp) {
        $db->rollback();
        $flag = false;
        echo"<script language='javascript' type='text/javascript'>alert('Não foi possível cadastrar este registro, tente novamente.');window.location.href='cliente.php';</script>";
    }
    if ($flag) {
        $db->commit();
        echo"<script language='javascript' type='text/javascript'>alert('Registro cadastrado com sucesso!');window.location.href='cliente.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'header.html'; ?>
        <meta charset="UTF-8"/>
        <title>Cadastro de cliente - Estacionamento</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <script language ="JavaScript" type="text/javascript" src="js/MascaraValidacao.js"></script>
    </head>
    <body>
        <form name="form3" class="form-horizontal" action="cliente.php" method="post">
            <fieldset>

                <!-- Formulario -->
                <legend id="cen">CADASTRO DE CLIENTE</legend>
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
                <!-- Input nome-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="nome">Nome Completo</label>  
                    <div class="col-md-4">
                        <input id="nome_cliente" name="nome_cliente" type="text" placeholder="Seu nome aqui..." class="form-control input-md" required=""/>
                    </div>
                </div>

                <!-- Imput CPF -->
                <div class="form-group">
                    <label class="col-md-4 control-label">CPF</label>  
                    <div class="col-md-4">
                        <input id="cpf" name="cpf" type="text" maxlength="11" placeholder="Seu CPF" class="form-control input-md" required=""/>
                    </div>
                </div>

                <!-- Imput Telefone -->
                <div class="form-group">
                    <label class="col-md-4 control-label">Contato</label>  
                    <div class="col-md-4">
                        <input id="contato" name="contato" type="text" maxlength="11" placeholder="Seu telefone" class="form-control input-md" required=""/>
                    </div>
                </div

                <!-- Tipo Cliente -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="especificacao">Tipo de cliente</label>
                    <div class ="col-md-4">
                        <input type="radio" name="id_tipo" id="id_tipo" value="1" checked>Mensalista<br>
                        <input type="radio" name="id_tipo" id="id_tipo" value="2">Avulso<br>
                    </div>
                </div>

                <!-- Imput Endereço -->
                <div class="form-group">    
                    <label class="col-md-4 control-label" >Cidade</label>  
					<div class="col-md-2">
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
                    <label class="col-md-4 control-label" for="rua">Logradouro</label>  
                    <div class="col-md-4">
                        <input id="rua" name="logradouro" type="text" placeholder="Nome da rua..." class="form-control input-md" required=""/>
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
                        <input id="cep" name="cep" type="text" maxlength="9" placeholder="Número CEP..." class="form-control input-md" required=""/>
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
                        <button id="submit" name="submit" class="btn btn-primary">Cadastrar!</button>
                    </div>
                </div>
                <div class="row" id="cen">
                    <p>
                        <a href="usuariocrud.php" class="btn btn-success">Ver, Editar ou Deletar usuários</a>
                    </p>
                </div>
            </fieldset>
            <div class="row" id="cen">
                <p>
                    <a href="clientecrud.php" class="btn btn-success">Ver, Editar ou Deletar clientes</a>
                </p>
            </div>
        </form>

        <?php include_once 'footer.html'; ?>     
    </body>
</html>
