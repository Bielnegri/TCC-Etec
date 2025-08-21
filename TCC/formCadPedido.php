<?php
    session_start();
    require('backend/conexao.php');
    require("backend/util.php");
    if(!isset($_SESSION['login'])){
        header("Location: index.php?codMsg=1");
        exit();
    }
    if(!isset($_GET['codigo'])){
        header("Location: listPedidos.php?codMsg=2");
        exit();
    }
    if(!is_numeric($_GET['codigo'])){
        header("Location: listPedidos.php?codMsg=2");
        exit();
    }
    $codigo = cleanCampo($_GET['codigo']);
    $dados = array
    (
        "codigo" => 0,
        "datapedido" => date("Y-m-d"),
        "entrega" => date("Y-m-d"),
        "cliente" => 0,
        "nome" => "",
        "cpf" => "",
        "telefone" => "",
        "cep" => "",
        "rua" => "",
        "numero" => "",
        "complemento" => "",
        "bairro" => "",
        "cidade" => "",
        "estado" => "",
        "codvendedor" => $_SESSION['codUser'],
        "vendedor" => $_SESSION['nome'],
        "formapagamento" => 1,
        "valortotalprodutos" => 0.00,
        "valortotaldesconto" => 0.00,
        "valortotalpedido" => 0.00,
        "status" => 0
    );
    if($codigo > 0){
        $sql = "SELECT pedidos.codigo,pedidos.datapedido,pedidos.entrega,pedidos.formapagamento,pedidos.valortotalprodutos,pedidos.valortotaldesconto,pedidos.valortotalpedido,pedidos.status,clientes.codigo AS 'cliente',clientes.nome,clientes.cpf,telefones.numero AS 'telefone',enderecos.cep,enderecos.rua,enderecos.numero,enderecos.complemento,enderecos.bairro,enderecos.cidade,enderecos.estado,usuarios.codigo AS 'codvendedor',usuarios.nome AS 'vendedor' FROM pedidos LEFT JOIN clientes ON pedidos.cliente=clientes.codigo LEFT JOIN telefones ON clientes.codigo=telefones.clienteId LEFT JOIN enderecos ON clientes.codigo=enderecos.clienteId LEFT JOIN usuarios ON pedidos.vendedor=usuarios.codigo WHERE pedidos.codigo=$codigo";
        $resultado = @mysqli_query($conexao, $sql);
        if (!$resultado) {
            mysqli_close($conexao);
            header("Location: listPedidos.php?codMsg=3");
            die();
        }
        if(mysqli_num_rows($resultado)>0){
            $dados = mysqli_fetch_array($resultado);
        }
        else{
            mysqli_close($conexao);
            header("Location: listPedidos.php?codMsg=4");
            die();
        }
        $sqlItensPedido = "SELECT itemspedido.codigo,produtos.codigo AS 'codproduto',produtos.nome,itemspedido.valorunitario,itemspedido.quantidade,itemspedido.valorprodutos,itemspedido.porcentagemdesconto,itemspedido.valordesconto,itemspedido.valortotal FROM itemspedido LEFT JOIN produtos ON itemspedido.produto=produtos.codigo WHERE itemspedido.pedido=$codigo";
        $resultadoItensPedido = @mysqli_query($conexao, $sqlItensPedido);
        if (!$resultadoItensPedido) {
            mysqli_close($conexao);
            header("Location: listPedidos.php?codMsg=2");
            die();
        }
    }
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulário de Cadastro de Pedidos</title>
    <link rel="stylesheet" href="css/overlay.css">
    <link rel="stylesheet" href="css/snackbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/page.css">
    <link rel="stylesheet" href="css/dialog.css">
    <link rel="stylesheet" href="css/list.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">

    <script src="js/snackbar.js" type="text/javascript"></script>
    <script src="js/commom.js" type="text/javascript"></script>
    <script src="js/cadpedidos.js" type="text/javascript"></script>
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
        <!-- Dialog Pesquisa Cliente -->
        <div class="overlay" id="dialog-pesquisa-cliente">
            <div class="popup-searchcliente">
                <!-- Tabela Listagem -->
                <table class="tableSearchCliente">
                    <thead>
                        <!-- Barra Titulo -->
                        <tr>
                            <td class="barTitulo barTituloSearchCliente">Pesquisa Clientes<i id="closeClienteSearch" class="ico-close" role="img"></i></td>
                        </tr>
                        <!-- Barra Botão Incluir -->
                        <tr>
                            <td class="barButton barButtonSearchCliente">
                                <form id="formPesquisaCliente" action="" method="post">
                                    <div class="row">
                                        <div class="column campo">
                                            <div class="input-container-search">
                                                <select id="campobusca" name="campobusca" class="input input-search" placeholder=" " >
                                                    <option value="codigo">Código</option>
                                                    <option value="nome">Nome</option>
                                                    <option value="cpf">CPF</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column valorpesquisa">
                                            <div class="input-container-search">
                                                <input id="valorpesquisa" name="valorpesquisa" class="input input-search" type="text" placeholder=" " />
                                            </div>
                                        </div>
                                        <div class="column botao-pesquisa">
                                            <div class="input-container-search">
                                                <button type="button" class="input input-botao" id="pesquisarCliente">Pesquisar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <!-- Cabeçalho Colunas -->
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                        </tr>
                    </thead>
                    <!-- Colunas -->
                    <tbody id="tbodyClienteSearch">
                       
                    </tbody>            
                </table>
            </div>
        </div>
        <!-- Dialog Pesquisa Produtos -->
        <div class="overlay" id="dialog-pesquisa-produto">
            <div class="popup-searchcliente">
                <!-- Tabela Listagem -->
                <table class="tableSearchProduto">
                    <thead>
                        <!-- Barra Titulo -->
                        <tr>
                            <td class="barTitulo barTituloSearchProduto">Pesquisa Produto<i id="closeProdutoSearch" class="ico-close" role="img"></i></td>
                        </tr>
                        <!-- Barra Botão Incluir -->
                        <tr>
                            <td class="barButton barButtonSearchProduto">
                                <form id="formPesquisaProduto" action="" method="post">
                                    <div class="row">
                                        <div class="column campo">
                                            <div class="input-container-search">
                                                <select id="campobusca" name="campobusca" class="input input-search" placeholder=" " >
                                                    <option value="codigo">Código</option>
                                                    <option value="nome">Nome</option>
                                                    <option value="descricao">Descrição</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column valorpesquisa">
                                            <div class="input-container-search">
                                                <input id="valorpesquisa" name="valorpesquisa" class="input input-search" type="text" placeholder=" " />
                                            </div>
                                        </div>
                                        <div class="column botao-pesquisa">
                                            <div class="input-container-search">
                                                <button type="button" class="input input-botao" id="pesquisarProduto">Pesquisar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <!-- Cabeçalho Colunas -->
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Quantidade</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <!-- Colunas -->
                    <tbody id="tbodyProdutoSearch">
                       
                    </tbody>            
                </table>
            </div>
        </div>
        <!-- DIV do Formulário -->
        <div class="form-cad-pedidos">
            <form id="formCadPedidos" action="" method="post">
                <!-- DIV do Título do Formulário -->
                <div class="title">Cadastro do Pedido</div>
                <!-- Primeira Linha 3 colunas -->
                <div class="row">
                    <div class="column codigo">
                        <!-- DIV do Input Cod.Pedido -->
                        <div class="input-container">
                            <input id="codigo" name="codigo" class="input input-direita" type="text" placeholder=" " value="<?php echo $dados['codigo']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="codigo" class="placeholder">Nr.Pedido</label>
                        </div>
                    </div>
                    <div class="column datapedido">
                        <!-- DIV do Input Data Pedido -->
                        <div class="input-container">
                            <input id="data" name="data" class="input" type="date" placeholder=" " value="<?php echo $dados['datapedido']; ?>"/>
                            <div class="cut cut-pedido"></div>
                            <label for="data" class="placeholder">Data</label>
                        </div>
                    </div>
                    <div class="column entrega">
                        <!-- DIV do Input Data Entrega -->
                        <div class="input-container">
                            <input id="entrega" name="entrega" class="input" type="date" placeholder=" " onfocus="this.setAttribute('min', document.getElementById('data').value);" value="<?php echo $dados['entrega']; ?>"/>
                            <div class="cut cut-pedido"></div>
                            <label for="entrega" class="placeholder">Entrega</label>
                        </div>
                    </div>
                </div>
                <!-- Segunda Linha 2 colunas -->
                <div class="row">
                    <div class="column cliente">
                        <!-- DIV do Input Cod.Cliente -->
                        <div class="input-container">
                            <input id="cliente" name="cliente" class="input input-direita icon-rtl" type="text" placeholder=" " value="<?php echo $dados['cliente']; ?>" readonly <?php if($dados['status']>0) echo "disabled"?>/>
                            <div class="cut cut-pedido"></div>
                            <label for="cliente" class="placeholder">Código do cliente</label>
                        </div>
                    </div>
                    <div class="column nome">
                        <!-- DIV do Input Nome Cliente -->
                        <div class="input-container">
                            <input id="nome" name="nome" class="input" type="text" placeholder=" " value="<?php echo $dados['nome']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="nome" class="placeholder">Nome do cliente</label>
                        </div>
                    </div>
                </div>
                <!-- Terceira Linha 3 colunas -->
                <div class="row">
                    <div class="column cpf">
                        <!-- DIV do Input CPF Cliente -->
                        <div class="input-container">
                            <input id="cpf" name="cpf" class="input" type="text" placeholder=" " value="<?php echo $dados['cpf']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="cpf" class="placeholder">CPF do cliente</label>
                        </div>
                    </div>
                    <div class="column telefone">
                        <!-- DIV do Input Telefone Cliente -->
                        <div class="input-container">
                            <input id="telefone" name="telefone" class="input" type="text" placeholder=" " value="<?php echo $dados['telefone']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="telefone" class="placeholder">Telefone do cliente</label>
                        </div>
                    </div>
                    <div class="column cep">
                        <!-- DIV do Input CEP Cliente -->
                        <div class="input-container">
                            <input id="cep" name="cep" class="input" type="text" placeholder=" " value="<?php echo $dados['cep']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="cpf" class="placeholder">CEP do cliente</label>
                        </div>
                    </div>
                </div>
                <!-- Quarta Linha 2 colunas -->
                <div class="row">
                    <div class="column rua">
                        <!-- DIV do Input Rua Cliente -->
                        <div class="input-container">
                            <input id="rua" name="rua" class="input" type="text" placeholder=" " value="<?php echo $dados['rua']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="rua" class="placeholder">Rua do cliente</label>
                        </div>
                    </div>
                    <div class="column numero">
                        <!-- DIV do Input Numero Rua Cliente -->
                        <div class="input-container">
                            <input id="numero" name="numero" class="input input-direita" type="text" placeholder=" " value="<?php echo $dados['numero']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="numero" class="placeholder">Número do cliente</label>
                        </div>
                    </div>
                </div>
                <!-- Quinta Linha 4 colunas -->
                <div class="row">
                    <div class="column complemento">
                        <!-- DIV do Input Complemento Rua Cliente -->
                        <div class="input-container">
                            <input id="complemento" name="complemento" class="input" type="text" placeholder=" " value="<?php echo $dados['complemento']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="complemento" class="placeholder">Complemento</label>
                        </div>
                    </div>
                    <div class="column bairro">
                        <!-- DIV do Input Bairro Cliente -->
                        <div class="input-container">
                            <input id="bairro" name="bairro" class="input" type="text" placeholder=" " value="<?php echo $dados['bairro']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="bairro" class="placeholder">Bairro do cliente</label>
                        </div>
                    </div>
                    <div class="column cidade">
                        <!-- DIV do Input Cidade Cliente -->
                        <div class="input-container">
                            <input id="cidade" name="cidade" class="input" type="text" placeholder=" " value="<?php echo $dados['cidade']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="cidade" class="placeholder">Cidade cliente</label>
                        </div>
                    </div>
                    <div class="column uf">
                        <!-- DIV do Input UF Cliente -->
                        <div class="input-container">
                            <input id="uf" name="uf" class="input" type="text" placeholder=" " value="<?php echo $dados['estado']; ?>" readonly/>
                            <div class="cut cut-pedido-uf"></div>
                            <label for="uf" class="placeholder">UF</label>
                        </div>
                    </div>
                </div>
                <!-- Sexta Linha 2 colunas -->
                <div class="row">
                    <div class="column codvendedor">
                        <!-- DIV do Input Cod.Vendedor -->
                        <div class="input-container">
                            <input id="codvendedor" name="codvendedor" class="input input-direita" type="text" placeholder=" " value="<?php echo $dados['codvendedor']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="codvendedor" class="placeholder">Código vendedor</label>
                        </div>
                    </div>
                    <div class="column vendedor">
                        <!-- DIV do Input Nome Vendedor -->
                        <div class="input-container">
                            <input id="vendedor" name="vendedor" class="input" type="text" placeholder=" " value="<?php echo $dados['vendedor'] ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="vendedor" class="placeholder">Nome vendedor</label>
                        </div>
                    </div>
                </div>
                <!-- Setima Linha 4 colunas -->
                <div class="row">
                    <div class="column formapagamento">
                        <!-- DIV do Input Forma Pagamento -->
                        <div class="input-container">
                            <select id="formapagamento" name="formapagamento" class="input" placeholder=" " value="<?php echo $dados['formapagamento']; ?>" >
                                <option value="1" <?php echo $dados['formapagamento']==1 ? "selected" : ""; ?> >Dinheiro</option>
                                <option value="2" <?php echo $dados['formapagamento']==2 ? "selected" : ""; ?> >Débito</option>
                                <option value="3" <?php echo $dados['formapagamento']==3 ? "selected" : ""; ?> >Crédito</option>
                                <option value="4" <?php echo $dados['formapagamento']==4 ? "selected" : ""; ?> >PIX</option>
                            </select>                           
                            <div class="cut cut-pedido"></div>
                            <label for="formapagamento" class="placeholder">Forma Pagamento</label>
                        </div>
                    </div>
                    <div class="column valorprodutos">
                        <!-- DIV do Input Valor do Produtos -->
                        <div class="input-container">
                            <input id="valorprodutos" name="valorprodutos" class="input input-direita" type="text" placeholder=" " value="<?php echo $dados['valortotalprodutos']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="valorprodutos" class="placeholder">R$ Produtos</label>
                        </div>
                    </div>
                    <div class="column descontos">
                        <!-- DIV do Input Descontos -->
                        <div class="input-container">
                            <input id="descontos" name="descontos" class="input input-direita" type="text" placeholder=" " value="<?php echo $dados['valortotaldesconto']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="descontos" class="placeholder">R$ Descontos</label>
                        </div>
                    </div>
                    <div class="column total">
                        <!-- DIV do Input Valor Total Pedido -->
                        <div class="input-container">
                            <input id="total" name="total" class="input input-direita" type="text" placeholder=" " value="<?php echo $dados['valortotalpedido']; ?>" readonly/>
                            <div class="cut cut-pedido"></div>
                            <label for="total" class="placeholder">R$ Total</label>
                        </div>
                    </div>
                </div>
                <!-- DIV Tabela Itens Produtos para ter a rolagem -->
                <div class="table-wrapper">
                    <!-- Tabela Listagem -->
                    <table class="tableItemsPedido">
                        <thead>
                            <!-- Barra Botão Incluir -->
                            <tr>
                                <td class="barButton barButtonItemsPedido">
                                    <button type="button" id="btnItemsPedido" class="btnItems" <?php if($dados['status']>0) echo "disabled"?>><i class="fa fa-plus"></i> Incluir Produtos</button>
                                </td>
                            </tr>
                            <!-- Cabeçalho Colunas -->
                            <tr>
                                <th>Cod.</th>
                                <th>Produto</th>
                                <th>Unit.$</th>
                                <th>Quant.</th>
                                <th>Total P.</th>
                                <th>Desc.%</th>
                                <th>Desc.$</th>
                                <th>Total $</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tbodyItemsPedido">

                        <?php
                            //Na Alteracao monta os itens do pedido vindos do banco de dados
                            if($dados['codigo']>0){
                                while($item = $resultadoItensPedido->fetch_assoc()){
                        ?>
                                    <!-- Registros -->
                                    <tr>
                                        <td class="textRight"><?php echo $item['codproduto']; ?></td>
                                        <td><?php echo $item['nome']; ?></td>
                                        <td class="textRight"><?php echo $item['valorunitario']; ?></td>
                                        <td ><input type="text" onkeypress="return fnAllowNumbersAndDotKey(this, event, false);" onchange="verificaEstoque(this);" value="<?php echo $item['quantidade']; ?>" onkeyup="calculaDesconto(this);" /></td>
                                        <td class="textRight"><?php echo $item['valorprodutos']; ?></td>
                                        <td ><input type="text" onkeypress="return fnAllowNumbersAndDotKey(this, event, true);" value="<?php echo $item['porcentagemdesconto']; ?>" onkeyup="calculaDesconto(this);" /></td>
                                        <td class="textRight textRed"><?php echo $item['valordesconto']; ?></td>
                                        <td class="textRight textBold textBlue"><?php echo $item['valortotal']; ?></td>
                                        <td class="textCenter"><i class="ico-excluir" role="img" onclick="deleteRow(this, this.parentNode.parentNode.parentNode);"></i></td>
                                        <td class="hidden"><?php echo $item['codigo']; ?></td>
                                        <td class="hidden">alterar</td>
                                        <td class="hidden"><?php echo $item['quantidade']; ?></td>
                                    </tr>
                        <?php
                                }
                            }
                            mysqli_close($conexao);
                        ?>
                        
                        </tbody>            
                    </table>
                </div>
                <!-- Botoes do Form -->    
                <div class="botoes-pedidos">
                    <div><button type="button" class="submit" id="cadastrar" <?php if($dados['status']>0) echo "disabled"?>>Salvar</button></div>
                    <div><button type="button" class="cancelar" id="cancelar" onClick="goPage('listPedidos.php');">Cancelar</button></div>
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