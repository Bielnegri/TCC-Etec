<?php
    session_start();
    require('backend/conexao.php');
    require('backend/util.php');
    if(!isset($_SESSION['login'])){
        header("Location: index.php?codMsg=1");
        exit();
    }

    if(!isset($_GET['codMsg']) || $_GET['codMsg']<>3){
        $sql = "SELECT pedidos.codigo,pedidos.datapedido,clientes.nome,pedidos.valortotalprodutos,pedidos.valortotaldesconto,pedidos.valortotalpedido,pedidos.status FROM pedidos LEFT JOIN clientes ON pedidos.cliente=clientes.codigo ORDER BY pedidos.codigo";
        $resultado = @mysqli_query($conexao, $sql);
        if (!$resultado) {
            mysqli_close($conexao);
            header("Location: listPedidos.php?codMsg=3");
            die();
        } 
    }else{
        $resultado = [];
    }
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista de Pedidos</title>
    <link rel="stylesheet" href="css/overlay.css">
    <link rel="stylesheet" href="css/snackbar.css">
    <link rel="stylesheet" href="css/list.css">
    <link rel="stylesheet" href="css/page.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/dialog.css">

    <script src="js/snackbar.js" type="text/javascript"></script>
    <script src="js/commom.js" type="text/javascript"></script>
    <script src="js/listagens.js" type="text/javascript"></script>
    <script src="js/listpedidos.js" type="text/javascript"></script>
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
        <!-- Dialog Excluir -->
        <div class="overlay" id="dialog-excluir">
            <div class="popup">
                <p id="dialog-text">Deseja excluir esse registro ?</p>
                <div class="text-right">
                <button class="dialog-btn-confirm" id="confirm">Confirmar</button>
                <button class="dialog-btn-cancel" id="cancel">Cancelar</button>
                </div>
            </div>
        </div>
         <!-- Dialog Status Pedido -->
         <div class="overlay" id="dialog-status-pedido">
            <div class="popup">
                <div>Status do Pedido</div>
                <!-- Formulário de Status -->
                <form id="formStatusPedido" action="" method="post">
                    <input type="hidden" name="codigoPedido" id="codigoPedido"/>
                    <div class="dialog-input-container">
                        <select id="selectStatus" name="selectStatus" class="dialog-input">
                            <option value="">Selecione o status do pedido</option>
                            <option value="1">Finalizado</option>
                            <option value="2">Pago</option>
                            <option value="3">Despachado</option>
                            <option value="4">Entregue</option>
                            <option value="5">Cancelado</option>
                        </select>
                    </div>
                </form>
                <div>
                    <button class="dialog-btn-confirm" id="confirmStatus">Confirmar</button>
                    <button class="dialog-btn-cancel" id="cancelaStatus">Cancelar</button>
                </div>
            </div>
        </div>
        <!-- Tabela Listagem -->
        <table class="tablePedido">
            <thead>
                <!-- Barra Titulo -->
                <tr>
                    <td class="barTitulo barTituloPedido">Lista de Pedidos</td>
                </tr>
                <!-- Barra Botão Incluir -->
                <tr>
                    <td class="barButton barButtonPedido">
                        <button type="button" class="btnAdd" onClick="goPage('formCadPedido.php?codigo=0');"><i class="fa fa-plus"></i> Incluir</button>
                    </td>
                </tr>
                <!-- Cabeçalho Colunas -->
                <tr>
                    <th>Código</th>
                    <th>Data</th>
                    <th>Nome</th>
                    <th>Produtos</th>
                    <th>Descontos</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sem registros -->
                <?php 
                
                if(mysqli_num_rows($resultado)<=0)
                {
                ?>
                    <tr>
                        <td class="regBranco regBrancoPedido">
                            Nenhum registro encontrado.
                        </td>
                    </tr>
                <?php
                }else{
                    while($dados = $resultado->fetch_assoc()){
                ?>
                        <!-- Registros -->
                        <tr>
                            <td class="textRight"><?php echo $dados['codigo']; ?></td>
                            <td class="textCenter"><?php echo date("d/m/Y",strtotime($dados['datapedido'])); ?></td>
                            <td><?php echo $dados['nome']; ?></td>
                            <td class="textRight"><?php echo $dados['valortotalprodutos']; ?></td>
                            <td class="textRight"><?php echo $dados['valortotaldesconto']; ?></td>
                            <td class="textRight"><?php echo $dados['valortotalpedido']; ?></td>
                            <td class="textCenter"><?php echo getStatus($dados['status']); ?></td>
                            <td class="td-action">
                                <button type="button" class="btnEdit" title="Editar" onClick="goPage('formCadPedido.php?codigo=<?php echo $dados['codigo']; ?>');"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btnDel" title="Excluir" onClick="showDialogExclusao(dialogExclusao,'Tem certeza que deseja excluir esse registro?','<?php echo $dados['codigo']; ?>','backend/pedidos_del.php','listPedidos.php');" <?php if($dados['status']>0) echo "disabled"?>><i class="fa fa-trash"></i></button>
                                <button type="button" class="btnStatus" title="Status" onClick="showDialogStatus(dialogStatusPedido,'<?php echo $dados['codigo']; ?>','<?php echo $dados['status']; ?>','backend/status_pedido.php','');" <?php if($dados['status']>=4) echo "disabled"?>><i class="fa fa-clipboard"></i></button>
                            </td>
                        </tr>
                <?php
                    }
                }
                mysqli_close($conexao);
                ?>
            </tbody>            
        </table>
    </main>
    <!-- DIV do rodapé -->
    <footer class="footer">
        <div>Sistema de Cadastro de Produtos - Etec de Vila Formosa - Trabalho de Conclusão de Curso</div>
    </footer>
    <!-- DIV da snackbar para exibição da mensagen -->
    <div id="snackbar"></div>
</body>
</hmtl>