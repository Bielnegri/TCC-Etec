<?php
    session_start();
    require('backend/conexao.php');
    require("backend/util.php");
    if(!isset($_SESSION['login'])){
        header("Location: index.php?codMsg=1");
        exit();
    }
    if(!isset($_GET['codigo'])){
        header("Location: listClientes.php?codMsg=2");
        exit();
    }
    if(!is_numeric($_GET['codigo'])){
        header("Location: listClientes.php?codMsg=2");
        exit();
    }
    $codigo = cleanCampo($_GET['codigo']);
    $dados = array
    (
        "codigo" => 0,
        "nome" => "",
        "cpf" => "",
        "telefone" => "",
        "pais" => "",
        "estado" => "",
        "cidade" => "",
        "bairro" => "",
        "rua" => "",
        "numero" => "",
        "complemento" => "",
        "cep" => ""
    );
    if($codigo > 0){
        $sql = "SELECT clientes.codigo,clientes.nome,clientes.cpf,telefones.numero AS 'telefone',enderecos.cep,enderecos.rua,enderecos.numero,enderecos.complemento,enderecos.bairro,enderecos.cidade,enderecos.estado,enderecos.pais FROM clientes LEFT JOIN telefones ON clientes.codigo=telefones.clienteId LEFT JOIN enderecos ON clientes.codigo=enderecos.clienteId WHERE clientes.codigo=$codigo";
        $resultado = @mysqli_query($conexao, $sql);
        if (!$resultado) {
            mysqli_close($conexao);
            header("Location: listClientes.php?codMsg=3");
            die();
        }
        if(mysqli_num_rows($resultado)>0){
            $dados = mysqli_fetch_array($resultado);
        }
        else{
            mysqli_close($conexao);
            header("Location: listClientes.php?codMsg=4");
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
    <title>Formulário de Cadastro de Clientes</title>
    <link rel="stylesheet" href="css/overlay.css">
    <link rel="stylesheet" href="css/snackbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/page.css">

    <script src="js/snackbar.js" type="text/javascript"></script>
    <script src="js/commom.js" type="text/javascript"></script>
    <script src="js/cadclientes.js" type="text/javascript"></script>
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
        <div class="form-cad-clientes">
            <form id="formCadClientes" action="" method="post">
            <input id="codigo" name="codigo" class="input" type="hidden" placeholder=" " value="<?php echo ($dados['codigo']) ? $dados['codigo'] : '0'; ?>" />
                <!-- DIV do Título do Formulário -->
                <div class="title">Cadastro de Cliente</div>
                <!-- DIV do Input Nome -->
                <div class="input-container">
                    <input id="nome" name="nome" class="input" type="text" placeholder=" " value="<?php echo $dados['nome']; ?>"/>
                    <div class="cut cut-cliente"></div>
                    <label for="nome" class="placeholder">Informe o Nome do cliente</label>
                </div>
                <!-- DIV Input CPF -->
                <div class="input-container">
                    <input id="cpf" name="cpf" onkeyup="mascararCpf(event);" onblur="verificarCpf(event);" class="input" type="text" placeholder=" " maxlength="14" value="<?php echo $dados['cpf']; ?>"/>
                    <div class="cut cut-cliente"></div>
                    <label for="cpf" class="placeholder">Informe o CPF do cliente</label>
                </div>
                <!-- DIV Input Telefone -->
                <div class="input-container">
                    <input id="telefone" name="telefone" onkeyup="mascararTelefoneCelular(event);" class="input" type="text" placeholder=" " maxlength="15" value="<?php echo $dados['telefone']; ?>"/>
                    <div class="cut cut-cliente"></div>
                    <label for="telefone" class="placeholder">Informe o Telefone do cliente</label>
                </div>
                <!-- DIV Input CEP -->
                <div class="input-container">
                    <input id="cep" name="cep" onkeyup="mascararCep(event);" class="input" type="text" placeholder=" "  maxlength="9" value="<?php echo $dados['cep']; ?>"/>
                    <div class="cut cut-cliente"></div>
                    <label for="cep" class="placeholder">Informe o CEP do cliente</label>
                </div>
                <!-- DIV Input Rua -->
                <div class="input-container">
                    <input id="rua" name="rua" class="input" type="text" placeholder=" " value="<?php echo $dados['rua']; ?>"/>
                    <div class="cut cut-cliente"></div>
                    <label for="rua" class="placeholder">Informe o Nome da Rua do cliente</label>
                </div>
                <!-- DIV Input Número -->
                <div class="input-container">
                    <input id="numero" name="numero" onkeypress="return fnAllowNumbersAndDotKey(this, event, false);" class="input" type="text" placeholder=" " value="<?php echo $dados['numero']; ?>"/>
                    <div class="cut cut-cliente"></div>
                    <label for="numero" class="placeholder">Informe o Número do Endereço do cliente</label>
                </div>
                <!-- DIV Input Complemento -->
                <div class="input-container">
                    <input id="complemento" name="complemento" class="input" type="text" placeholder=" " value="<?php echo $dados['complemento']; ?>"/>
                    <div class="cut cut-cliente"></div>
                    <label for="complemento" class="placeholder">Informe o Complemento do cliente</label>
                </div>
                <!-- DIV Input Bairro -->
                <div class="input-container">
                    <input id="bairro" name="bairro" class="input" type="text" placeholder=" " value="<?php echo $dados['bairro']; ?>"/>
                    <div class="cut cut-cliente"></div>
                    <label for="bairro" class="placeholder">Informe o Bairro do cliente</label>
                </div>
                <!-- DIV Input Cidade -->
                <div class="input-container">
                    <input id="cidade" name="cidade" class="input" type="text" placeholder=" " value="<?php echo $dados['cidade']; ?>"/>
                    <div class="cut cut-cliente"></div>
                    <label for="cidade" class="placeholder">Informe o Cidade do cliente</label>
                </div>
                <!-- DIV Input Estado -->
                <div class="input-container">
                    <select id="estado" name="estado" class="input">
                        <option value="">Selecione o Estado</option>
                        <option value="AC" <?php echo $dados['estado']=="AC" ? "selected" : ""; ?> >Acre</option>
                        <option value="AL" <?php echo $dados['estado']=="AL" ? "selected" : ""; ?> >Alagoas</option>
                        <option value="AP" <?php echo $dados['estado']=="AP" ? "selected" : ""; ?> >Amapá</option>
                        <option value="AM" <?php echo $dados['estado']=="AM" ? "selected" : ""; ?> >Amazonas</option>
                        <option value="BA" <?php echo $dados['estado']=="BA" ? "selected" : ""; ?> >Bahia</option>
                        <option value="CE" <?php echo $dados['estado']=="CE" ? "selected" : ""; ?> >Ceará</option>
                        <option value="DF" <?php echo $dados['estado']=="DF" ? "selected" : ""; ?> >Distrito Federal</option>
                        <option value="ES" <?php echo $dados['estado']=="ES" ? "selected" : ""; ?> >Espírito Santo</option>
                        <option value="GO" <?php echo $dados['estado']=="GO" ? "selected" : ""; ?> >Goiás</option>
                        <option value="MA" <?php echo $dados['estado']=="MA" ? "selected" : ""; ?> >Maranhão</option>
                        <option value="MT" <?php echo $dados['estado']=="MT" ? "selected" : ""; ?> >Mato Grosso</option>
                        <option value="MS" <?php echo $dados['estado']=="MS" ? "selected" : ""; ?> >Mato Grosso do Sul</option>
                        <option value="MG" <?php echo $dados['estado']=="MG" ? "selected" : ""; ?> >Minas Gerais</option>
                        <option value="PA" <?php echo $dados['estado']=="PA" ? "selected" : ""; ?> >Pará</option>
                        <option value="PB" <?php echo $dados['estado']=="PB" ? "selected" : ""; ?> >Paraíba</option>
                        <option value="PR" <?php echo $dados['estado']=="PR" ? "selected" : ""; ?> >Paraná</option>
                        <option value="PE" <?php echo $dados['estado']=="PE" ? "selected" : ""; ?> >Pernambuco</option>
                        <option value="PI" <?php echo $dados['estado']=="PI" ? "selected" : ""; ?> >Piauí</option>
                        <option value="RJ" <?php echo $dados['estado']=="RJ" ? "selected" : ""; ?> >Rio de Janeiro</option>
                        <option value="RN" <?php echo $dados['estado']=="RN" ? "selected" : ""; ?> >Rio Grande do Norte</option>
                        <option value="RS" <?php echo $dados['estado']=="RS" ? "selected" : ""; ?> >Rio Grande do Sul</option>
                        <option value="RO" <?php echo $dados['estado']=="RO" ? "selected" : ""; ?> >Rondônia</option>
                        <option value="RR" <?php echo $dados['estado']=="RR" ? "selected" : ""; ?> >Roraima</option>
                        <option value="SC" <?php echo $dados['estado']=="SC" ? "selected" : ""; ?> >Santa Catarina</option>
                        <option value="SP" <?php echo $dados['estado']=="SP" ? "selected" : ""; ?> >São Paulo</option>
                        <option value="SE" <?php echo $dados['estado']=="SE" ? "selected" : ""; ?> >Sergipe</option>
                        <option value="TO" <?php echo $dados['estado']=="TO" ? "selected" : ""; ?> >Tocantins</option>
                    </select>
                    <div class="cut cut-cliente"></div>
                    <label for="estado" class="placeholder">Informe o Estado do cliente</label>
                </div>
                <!-- DIV Input País -->
                <div class="input-container">
                    <input id="pais" name="pais" class="input" type="text" placeholder=" " value="<?php echo $dados['pais']; ?>"/>
                    <div class="cut cut-cliente"></div>
                    <label for="pais" class="placeholder">Informe o País do cliente</label>
                </div>         
                <div class="botoes">
                    <div><button type="button" class="submit" id="cadastrar">Salvar</button></div>
                    <div><button type="button" class="cancelar" id="cancelar" onClick="goPage('listClientes.php');">Cancelar</button></div>
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