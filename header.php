<?php 
include_once "session.php";
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <script language ="JavaScript" type="text/javascript" src="js/bootstrap.min.js"></script>
    <link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css'/>
    <title>EstacionaCanoas</title>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">EstacionaCanoas</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="cadastro.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true">Cadastros <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="cliente.php">Cliente</a></li>
                            <li><a href="incidente.php">Incidente</a></li>
                            <li><a href="funcionario.php">Funcionário</a></li>
                            <li><a href="marca.php">Marca</a></li>
                            <li><a href="modelo.php">Modelo</a></li>
                            <li><a href="veiculo.php">Veículo</a></li>

                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true">Cruds <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                         <li><a href="clientecrud.php">Clientes</a></li>
                         <li><a href="funcionacrud.php">Funcionários</a></li>
                         <li><a href="marcacrud.php">Marcas</a></li>
                         <li><a href="modelocrud.php">Modelos</a></li>  
                         <li><a href="usuariocrud.php">Usuários</a></li>
                         <li><a href="veiculocrud.php">Veículos</a></li>						
                     </ul>
                 </li>
                 <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true">Registros <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="entrada_veiculo.php">Entrada veículo</a></li>
                        <li><a href="saida_veiculo.php">Saída veículo</a></li>
                        <li><a href="pagamento.php">Pagamento de Ticket</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true">Usuário <?php echo $logado ?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="logout.php">Deslogar</a></li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<script src='http://code.jquery.com/jquery-2.1.3.min.js'></script>
<script src='//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js'></script>
<script>
    $(function () {
        $('.dropdown-toggle').dropdown();
    });
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" type="text/css"  rel="stylesheet" />
<link href="css/dashboard.css" type="text/css" rel="stylesheet"/> 
<link href="css/sticky-footer-navbar.css" type=text/css" rel="stylesheet"/>  
<script src="../../assets/js/ie-emulation-modes-warning.js"></script>
</head>
