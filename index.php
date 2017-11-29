<?php
session_start();
require 'db/DB.class.php';
//inicia a conexão
$db = new DB('02080100', //usuario
        'Q2hC9PBz', //senha
        '02080100', //banco
        'webacademico.canoas.ifrs.edu.br'//servidor
);

//banze_jobdorneles 
extract($_POST); //transformando os dados em variáveis
if (isset($_POST['submit'])) {

    $usuario = ($_POST["login"]);
    $senha = ($_POST["senha"]);

    $result = $db->query("SELECT * from usuario where login = '$usuario' and senha = '$senha'");
    $contar = $result->fetchAll(PDO::FETCH_ASSOC);
    $db->Count = count($contar);
    //echo $db->Count;
    if ($db->Count>0) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['senha'] = $senha;
        echo "<script>location.href='indexLogada.php';</script>";
    } else {
        unset ($_SESSION['usuario']);
        unset ($_SESSION['senha']);
        echo "<script> alert('Nome de usuário não cadastrado!'); location.href='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'header.html'; ?>
    </head>
    <body>
        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="jumbotron">
            <div class="container" text align = "center">
                <h2>Olá!</h2>
                <p>Você está no Sistema de Estacionamento.</p>
                <p>Entre com seu login e senha:</p>
                <form class="navbar-form navbar-center" action="index.php" method="post">
                    <div class="form-group" method="post">
                        <input type="text" id="login" name="login" required="" placeholder="Seu login..." class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="password" id="senha" name="senha" required="" placeholder="Sua senha..." class="form-control">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Entrar!</button>
                </form>
                <A href="cadastro.php"><FONT COLOR = "#5E58FF" ><h4>Ou cadastre-se aqui.</h4></A></FONT><BR>
            </div>
        </div>
        <?php include_once 'footer.html'; ?>     


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../../dist/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
