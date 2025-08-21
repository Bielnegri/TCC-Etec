<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="css/overlay.css">
    <link rel="stylesheet" href="css/snackbar.css">
    <link rel="stylesheet" href="css/form.css">
    <script src="js/snackbar.js" type="text/javascript"></script>
    <script src="js/commom.js" type="text/javascript"></script>
    <script src="js/index.js" type="text/javascript"></script>
</head>
<body style="overflow: hidden;">
    <!-- DIV da overlay bloqueia a tela enquanto faz o login -->
    <div id="overlay">
        <div id="textOverlay"></div>
    </div>
    <img class="logo-login" src="images/logo.png"></img>
    <!-- DIV do Formulário -->
    <div class="form-login">
        <form id="formLogin" action="" method="post">
            <!-- DIV do Título do Formulário -->
            <div class="title">Login</div>
            <!-- DIV do Input Usuario -->
            <div class="input-container">
                <input id="usuario" name="usuario" class="input" type="text" placeholder=" " />
                <div class="cut"></div>
                <label for="usuario" class="placeholder">Informe seu usuário</label>
            </div>
             <!-- DIV Input Semha -->
            <div class="input-container">
                <input id="senha" name="senha" class="input" type="password" placeholder=" " />
                <div class="cut"></div>
                <label for="senha" class="placeholder">Informe sua senha</label>
            </div>
            <div class="botoes botao-index">
                <button type="button" class="submit" id="entrar">Entrar</button>
            </div>
            <div class=input-container>
                <div class="data" id="data"></div>
            </div>
        </form>
    </div>
    <!-- DIV da snackbar para exibição da mensagen -->
    <div id="snackbar"></div>
</body>
</html>