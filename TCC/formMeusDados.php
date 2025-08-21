<?php
    session_start();
    if(!isset($_SESSION['login'])){
        header("Location: index.php?codMsg=1");
        exit();
    }
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulário para alterar meus dados</title>
    <link rel="stylesheet" href="css/overlay.css">
    <link rel="stylesheet" href="css/snackbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/page.css">

    <script src="js/snackbar.js" type="text/javascript"></script>
    <script src="js/commom.js" type="text/javascript"></script>
    <script src="js/meusdados.js" type="text/javascript"></script>
</head>
<body>
    <!-- DIV Cabeçalho -->
    <header class="header">
        <div class="texto"><img class="logo" src="images/logo.png"></img></div>
        <div class="icon icon-menu" id="logout" onClick="goPage('menu.php');">
            <svg fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10v11h6v-7h6v7h6v-11L12,3z"></path>
            </svg>
            <span>Menu</span> 
        </div>
        <div class="icon icon-sair" id="logout" onClick="goPage('backend/logout.php');">
            <svg fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"></path>
            </svg>
            <span>Sair</span> 
        </div>
    </header>
    <!-- DIV do Corpo/Meio da página -->
    <main class="main">
        <!-- DIV da overlay bloqueia a tela enquanto faz alguma ação -->
        <div id="overlay">
            <div id="textOverlay"></div>
        </div>
        <!-- DIV do Formulário -->
        <div class="form-cad-meusdados">
            <form id="formMeusDados" method="post">
                <!-- DIV do Título do Formulário -->
                <div class="title">Alterar Meus Dados</div>
                <!-- DIV do Input Nome -->
                <div class="input-container">
                    <input id="nome" name="nome" class="input" type="text" placeholder=" " value="<?php echo $_SESSION['nome'] ?>"/>
                    <div class="cut"></div>
                    <label for="nome" class="placeholder">Informe seu nome completo</label>
                </div>
                <!-- DIV Input Senha -->
                <div class="input-container">
                    <input id="senha" name="senha" class="input" type="password" placeholder=" " />
                    <div class="cut"></div>
                    <label for="senha" class="placeholder">Informe sua nova senha</label>
                </div>
                <div class="botoes">
                    <div><button type="button" class="submit" id="alterar">Alterar</button></div>
                    <div><button type="button" class="cancelar" id="cancelar" onClick="goPage('menu.php');">Cancelar</button></div>
                </div>
            </form>
        </div>
    </main>
    <!-- DIV do rodapé -->
    <footer class="footer">
        <div>Sistema de Cadastro de Produtos - Etec de Vila Formosa - Trabalho de Conclusão de Curso</div>
    </footer>
    <!-- DIV da snackbar para exibição da mensagem -->
    <div id="snackbar"></div>
</body>
</hmtl>