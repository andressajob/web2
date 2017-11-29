<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'header.html'; ?>
    </head>
    <body>
        <script type="text/javascript">
            function direciona()
            {
                if (document.getElementById("cliente").checked) {
                    window.open("cliente.php");
                }
                if (document.getElementById("funcionario").checked) {
                    window.open("funcionario.php");
                }
            }
        </script>
        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="jumbotron">
            <div class="container" text align = "center">
                <h2>Olá</h2>
                <p>Você está no Sistema de Estacionamento.</p>
                <p>Como você deseja se cadastrar?</p>
                <form class="navbar-form navbar-center" action="index.php" method="post">
                    <!--Cliente ou funcionario-->
                    <div class="form-group">
                        <label class="col-md-10 control-label" for="especificacao">Tipo de usuário</label>
                        <div class ="col-sm-10">
                            <input type="radio" name="especificacao" id="cliente" value="cliente" checked> Cliente<br>
                            <input type="radio" name="especificacao" id="funcionario" value="funcionario"> Funcionário<br>
                        </div>
                        <label class="col-md-10 control-label" for="cadastro"></label>
                        <div class="col-sm-10">
                            <button id="cadastro" name="cadastro" class="btn btn-primary" onclick="direciona()">Ok!</button>
                        </div>
                    </div>
                </form>
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
