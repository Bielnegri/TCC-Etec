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
    <title>Menu</title>
    <link rel="stylesheet" href="css/snackbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/page.css">
    <link rel="stylesheet" href="css/menu.css">

    <script src="js/snackbar.js" type="text/javascript"></script>
    <script src="js/commom.js" type="text/javascript"></script>
</head>
<body>
    <!-- DIV Cabeçalho -->
    <header class="header">
        <div class="texto"><img class="logo" src="images/logo.png"></img></div>
        <div class="icon icon-sair" id="logout" onClick="goPage('backend/logout.php');">
            <svg fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"></path>
            </svg>
            <span>Sair</span> 
        </div>
    </header>
    <!-- DIV do Corpo/Meio da página -->
    <main class="main">
        <!-- Card de Clientes -->
        <?php if(in_array("CLI", $_SESSION['direitos'])) { ?>
            <div class="card">
                <!-- Titulo do Card -->
                <div class="title">Clientes</div>
                <!-- Icone do Card -->
                <div class="icon">
                    <svg fill="none" stroke="#555" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                    </svg> 
                </div>
                <!-- Descrição do itens no Card -->
                <div class="features">
                    <ul class="no-bullets">
                        <li>Consultar</li>
                        <li>Incluir</li>
                        <li>Alterar</li>
                        <li>Excluir</li>
                    </ul>
                </div><!--/features-->
                <!-- Link para acesso ao modulo -->
                <a href="#" class="btn" onClick="goPage('listClientes.php');">Acessar</a>
            </div>
        <?php } ?>
        <!-- Card de Pedidos -->
        <?php if(in_array("PED", $_SESSION['direitos'])) { ?>
            <div class="card">
                <!-- Titulo do Card -->
                <div class="title">Pedidos</div>
                <!-- Icone do Card -->
                <div class="icon">
                    <svg fill="none" stroke="#555" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1"></path>
                    </svg>
                </div>
                <!-- Descrição do itens no Card -->
                <div class="features">
                    <ul class="no-bullets">
                        <li>Consultar</li>
                        <li>Incluir</li>
                        <li>Alterar</li>
                        <li>Excluir</li>
                    </ul>
                </div><!--/features-->
                <!-- Link para acesso ao modulo -->
                <a href="#" class="btn" onClick="goPage('listPedidos.php');">Acessar</a>
            </div>
        <?php } ?>
        <!-- Card de Produtos -->
        <?php if(in_array("PRO", $_SESSION['direitos'])) { ?>
            <div class="card">
                <!-- Titulo do Card -->
                <div class="title">Produtos</div>
                <!-- Icone do Card -->
                <div class="icon">
                    <svg fill="none" stroke="#555" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path>
                    </svg>
                </div>
                <!-- Descrição do itens no Card -->
                <div class="features">
                    <ul class="no-bullets">
                        <li>Consultar</li>
                        <li>Incluir</li>
                        <li>Alterar</li>
                        <li>Excluir</li>
                    </ul>
                </div>
                <!-- Link para acesso ao modulo -->
                <a href="#" class="btn" onClick="goPage('listProdutos.php');">Acessar</a>
            </div>
        <?php } ?>
        <!-- Card de Usuários -->
        <?php if(in_array("USU", $_SESSION['direitos'])) { ?>
            <div class="card">
                <!-- Titulo do Card -->
                <div class="title">Usuários</div>
                <!-- Icone do Card -->
                <div class="icon">
                    <svg fill="none" stroke="#555" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                    </svg>
                </div>
                <!-- Descrição do itens no Card -->
                <div class="features">
                    <ul class="no-bullets">
                        <li>Consultar</li>
                        <li>Incluir</li>
                        <li>Alterar</li>
                        <li>Excluir</li>
                    </ul>
                </div>
                <!-- Link para acesso ao modulo -->
                <a href="#" class="btn" onClick="goPage('listUsuarios.php');">Acessar</a>
            </div>
        <?php } ?>    
        <!-- Card de Meus Dados -->
        <div class="card">
            <!-- Titulo do Card -->
            <div class="title">Meus Dados</div>
            <!-- Icone do Card -->
            <div class="icon">
                <svg fill="none" stroke="#555" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                </svg>  
            </div>
            <!-- Descrição do itens no Card -->
            <div class="features">
                <ul class="no-bullets">
                    <li>Troca de Senha</li>
                    <li>&nbsp;</li>
                    <li>&nbsp;</li>
                    <li>&nbsp;</li>
                </ul>
            </div><!--/features-->
            <!-- Link para acesso ao modulo -->
            <a href="#" class="btn" onClick="goPage('formMeusDados.php');">Acessar</a>
        </div>
        <!--/card-->
    </main>
    <!-- DIV do rodapé -->
    <footer class="footer">
        <div>Sistema de Cadastro de Produtos - Etec de Vila Formosa - Trabalho de Conclusão de Curso</div>
    </footer>
    <!-- DIV da snackbar para exibição da mensagem -->
    <div id="snackbar"></div>
</body>
</hmtl>