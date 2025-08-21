<?php
    session_start();
    require('backend/conexao.php');
    require("backend/util.php");
    if(!isset($_SESSION['login'])){
        header("Location: index.php?codMsg=1");
        exit();
    }
    if(!isset($_GET['codigo'])){
        header("Location: listUsuarios.php?codMsg=2");
        exit();
    }
    if(!is_numeric($_GET['codigo'])){
        header("Location: listUsuarios.php?codMsg=2");
        exit();
    }
    $codigo = cleanCampo($_GET['codigo']);
    $dados = array
    (
        "codigo" => 0,
        "nome" => "",
        "usuario" => "",
        "senha" => "",
        "direitos" => []
    );
    if($codigo > 0){
        $sql = "SELECT * FROM usuarios WHERE codigo=$codigo";
        $resultado = @mysqli_query($conexao, $sql);
        if (!$resultado) {
            mysqli_close($conexao);
            header("Location: listUsuarios.php?codMsg=3");
            die();
        }
        if(mysqli_num_rows($resultado)>0){
            $dados = mysqli_fetch_array($resultado);
            $dados['direitos'] = explode(",",$dados['direitos']);
        }
        else{
            mysqli_close($conexao);
            header("Location: listUsuarios.php?codMsg=4");
            die();
        }
        mysqli_close($conexao);
    }
    
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulário de Cadastro de Usuários</title>
    <link rel="stylesheet" href="css/overlay.css">
    <link rel="stylesheet" href="css/snackbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/page.css">

    <script src="js/snackbar.js" type="text/javascript"></script>
    <script src="js/commom.js" type="text/javascript"></script>
    <script src="js/cadusuarios.js" type="text/javascript"></script>
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
        <div class="form-cad-usuarios">
            <form id="formCadUsuarios" action="" method="post">
                <input id="codigo" name="codigo" class="input" type="hidden" placeholder=" " value="<?php echo ($dados['codigo']) ? $dados['codigo'] : '0'; ?>" />
                <!-- DIV do Título do Formulário -->
                <div class="title">Cadastro de Usuários</div>
                <!-- DIV do Input Nome -->
                <div class="input-container">
                    <input id="nome" name="nome" class="input" type="text" placeholder=" " value="<?php echo $dados['nome']; ?>" />
                    <div class="cut"></div>
                    <label for="nome" class="placeholder">Informe seu nome completo</label>
                </div>
                <!-- DIV do Input Usuário -->
                <div class="input-container">
                    <input id="usuario" name="usuario" class="input" type="text" placeholder=" " value="<?php echo $dados['usuario']; ?>" />
                    <div class="cut"></div>
                    <label for="usuario" class="placeholder">Informe seu usuário</label>
                </div>
                <!-- DIV Input Senha -->
                <div class="input-container">
                    <input id="senha" name="senha" class="input" type="password" placeholder=" " value="<?php echo $dados['senha']; ?>" />
                    <div class="cut"></div>
                    <label for="senha" class="placeholder">Informe sua senha</label>
                </div>

                <div>
                    <br>
                    <span class="textobranco">Direitos</span><br>
                    <div class="row">
                        <div class="column metade">
                            <input type="checkbox" id="cli" name="direitos[]" value="CLI" <?php echo in_array("CLI", $dados['direitos']) ? "checked" : ""; ?>>
                            <label for="cli" class="textobranco">Clientes</label>
                        </div>
                        <div class="column metade">
                            <input type="checkbox" id="ped" name="direitos[]" value="PED"<?php echo in_array("PED", $dados['direitos']) ? "checked" : ""; ?>>
                            <label for="ped" class="textobranco">Pedidos</label>
                        </div>
                    </div>
                </div>
                <div>
                     <div class="row">
                        <div class="column metade">
                            <input type="checkbox" id="pro" name="direitos[]" value="PRO"<?php echo in_array("PRO", $dados['direitos']) ? "checked" : ""; ?>>
                            <label for="pro" class="textobranco">Produtos</label>
                        </div>
                        <div class="column metade">
                            <input type="checkbox" id="usu" name="direitos[]" value="USU" <?php echo in_array("USU", $dados['direitos']) ? "checked" : ""; ?>>
                            <label for="usu" class="textobranco">Usuários</label>
                        </div>
                    </div>
                </div>

                <div class="botoes">
                    <div><button type="button" class="submit" id="cadastrar">Salvar</button></div>
                    <div><button type="button" class="cancelar" id="cancelar" onClick="goPage('listUsuarios.php');">Cancelar</button></div>
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