<?php
    session_start();
    require('conexao.php');
    require('util.php');

    if(!isset($_SESSION['login'])){
        header("Location: ../index.php?codMsg=1");
        die();
    }
    if(!isset($_POST['codigo']) || !isset($_POST['data']) || !isset($_POST['entrega']) || !isset($_POST['cliente']) || !isset($_POST['codvendedor']) || !isset($_POST['formapagamento']) || !isset($_POST['valorprodutos']) || !isset($_POST['descontos']) || !isset($_POST['total']) || !isset($_POST['itemsPedido'])){
        $response = array(
            'status' => false,
            'message' => 'Campos obrigatórios nulos!'
        );
        echo json_encode($response);
        die();
    }
    if(!is_numeric($_POST['codigo'])){
        header("Location: ../listPedidos.phpcodMsg=2");
        die();
    }
    $codigo=cleanCampo($_POST['codigo']);
    $data=cleanCampo($_POST['data']);
    $entrega=cleanCampo($_POST['entrega']);
    $cliente=cleanCampo($_POST['cliente']);
    $codvendedor=cleanCampo($_POST['codvendedor']);
    $formapagamento=cleanCampo($_POST['formapagamento']);
    $valorprodutos=cleanCampo($_POST['valorprodutos']);
    $descontos=cleanCampo($_POST['descontos']);
    $total=cleanCampo($_POST['total']);
    
    //Decodificando a Json string com os items do pedido
    $itemsPedido = json_decode($_POST['itemsPedido'], true);

    //Desligando o auto commit
    @mysqli_query($conexao, "SET AUTOCOMMIT=0");
    //Inicia a transacao
    @mysqli_query($conexao, "START TRANSACTION");
    
    //Codigo maior que zero entao é uma alteracao
    if($codigo > 0){
        //Trabalhando os itens do Pedido para verificar o estoque
        foreach($itemsPedido as $itemPedido){
            $codProduto = $itemPedido['codigoProduto'];
            $quantidade = $itemPedido['quantidade'];
            $quantidadeOriginal = $itemPedido['quantidadeOriginal'];
            $task = $itemPedido['task'];

            //Na alteração só consulta o estoque no caso da quantidade nova
            //ser maior que a quantidade original
            if($quantidade>$quantidadeOriginal && $task !== "excluir")
            {
                //E tambem so faz a conferencia da quantidade a mais
                //do pedido original
                if($task == "incluir"){
                    $quantidadeConsultar = $quantidade;
                }else{
                    $quantidadeConsultar = $quantidade-$quantidadeOriginal;
                }
                //Verificando o Estoque do Produto
                $sql = "SELECT codigo,nome,quantidade FROM produtos WHERE codigo=$codProduto FOR UPDATE";
                $resultado = @mysqli_query($conexao, $sql);
                if (!$resultado) {
                    @mysqli_query($conexao, "ROLLBACK");
                    $mensagem = 3;
                    $response = array(
                        'status' => false,
                        'message' => $mensagem
                    );
                    mysqli_close($conexao);
                    echo json_encode($response);
                    die();
                } 
                if(mysqli_num_rows($resultado)>0){
                    $dados = mysqli_fetch_array($resultado);

                    if($quantidadeConsultar>$dados['quantidade']){
                        @mysqli_query($conexao, "ROLLBACK");
                        $response = array(
                            'status' => false,
                            'message' => '<b>Não há a quantidade desejada do produto</b><br> cod.: '.$dados['codigo'].'-'.$dados['nome'].' em estoque.<br>A quantidade em estoque é: '.$dados['quantidade'],
                            'quantidade' => $dados['quantidade']

                        );
                        mysqli_close($conexao);
                        echo json_encode($response);
                        die();
                    }
                }
            }  
        }

        //Altera os dados do pedido
        $mensagem = 6;
        $sql = "UPDATE pedidos SET datapedido='$data', entrega='$entrega', cliente=$cliente, vendedor=$codvendedor, formapagamento=$formapagamento, valortotalprodutos=$valorprodutos, valortotaldesconto=$descontos, valortotalpedido=$total WHERE codigo=$codigo";
        //Altera o pedido
        $resultado = @mysqli_query($conexao, $sql);
        //Se der erro dá um rollback e nem continua
        if (!$resultado) {
            @mysqli_query($conexao, "ROLLBACK");
            $mensagem = 3;
            $response = array(
                'status' => false,
                'message' => $mensagem
            );
            mysqli_close($conexao);
            echo json_encode($response);
            die();
        }

         //Trabalhando os itens do Pedido de acordo com a task
         foreach($itemsPedido as $itemPedido){
            $codigoPedido = $itemPedido['codigo'];
            $codProduto = $itemPedido['codigoProduto'];
            $quantidade = $itemPedido['quantidade'];
            $valorUnitario = $itemPedido['valorUnitario'];
            $valorProdutos = $itemPedido['valorProdutos'];
            $porcentagemDesconto = $itemPedido['porcentagemDesconto'];
            $valorDesconto = $itemPedido['valorDesconto'];
            $valorTotal = $itemPedido['valorTotal'];
            $task = $itemPedido['task'];
            $quantidadeOriginal = $itemPedido['quantidadeOriginal'];

            switch ($task) {
                case "alterar":
                    $sql = "UPDATE itemspedido SET produto=$codProduto, quantidade=$quantidade, valorunitario=$valorUnitario, valorprodutos=$valorProdutos, porcentagemdesconto=$porcentagemDesconto, valordesconto=$valorDesconto, valortotal=$valorTotal WHERE codigo=$codigoPedido";
                    //ser maior que a quantidade original
                    //Precisa tirar do estoque o que foi pedido a mais
                    //do poduto original
                    if($quantidade>$quantidadeOriginal)
                    {
                        $quantidadeBaixar = $quantidade-$quantidadeOriginal;
                        $sqlProduto = "UPDATE produtos SET quantidade=quantidade-$quantidadeBaixar WHERE codigo=$codProduto";
                    }elseif($quantidade<$quantidadeOriginal){
                        //A quantidade e menor do que no pedido original entao
                        //precisa devolver a quantidade diminuida para o estoque
                        $quantidadeBaixar = $quantidadeOriginal-$quantidade;
                        $sqlProduto = "UPDATE produtos SET quantidade=quantidade+$quantidadeBaixar WHERE codigo=$codProduto";

                    }else{
                        $sqlProduto = "";
                    }
                    break;
                case "excluir":
                    $sql = "DELETE FROM itemspedido WHERE codigo=$codigoPedido";
                    $sqlProduto = "UPDATE produtos SET quantidade=quantidade+$quantidadeOriginal WHERE codigo=$codProduto";
                    break;
                default:
                    $sql = "INSERT INTO itemspedido(pedido, produto, quantidade, valorunitario, valorprodutos, porcentagemdesconto, valordesconto, valortotal) VALUES($codigo, $codProduto, $quantidade, $valorUnitario, $valorProdutos, $porcentagemDesconto, $valorDesconto, $valorTotal)";
                    $sqlProduto = "UPDATE produtos SET quantidade=quantidade-$quantidade WHERE codigo=$codProduto";
            }            

            if($sqlProduto !==""){
                //Altera a quantidade do Produto
                $resultado = @mysqli_query($conexao, $sqlProduto);
                //Se der erro dá um rollback e nem continua
                if (!$resultado) {
                    @mysqli_query($conexao, "ROLLBACK");
                    $mensagem = 3;
                    $response = array(
                        'status' => false,
                        'message' => $mensagem
                    );
                    mysqli_close($conexao);
                    echo json_encode($response);
                    die();
                }
            }

            //Altera/Exclui/Inclui o item do pedido
            $resultado = @mysqli_query($conexao, $sql);
            //Se der erro dá um rollback e nem continua
            if (!$resultado) {
                @mysqli_query($conexao, "ROLLBACK");
                $mensagem = 3;
                $response = array(
                    'status' => false,
                    'message' => $mensagem
                );
                mysqli_close($conexao);
                echo json_encode($response);
                die();
            } 
        }

        //Deu tudo certo
        @mysqli_query($conexao, "COMMIT");
        $response = array(
            'status' => true,
            'message' => $mensagem
        );
        mysqli_close($conexao);
        echo json_encode($response);

    }
    else{
        //Trabalhando os itens do Pedido para verificar o estoque
        foreach($itemsPedido as $itemPedido){
            $codProduto = $itemPedido['codigoProduto'];
            $quantidade = $itemPedido['quantidade'];
            $quantidadeOriginal = $itemPedido['quantidadeOriginal'];
            $task = $itemPedido['task'];

            //Verificando o Estoque do Produto
            $sql = "SELECT codigo,nome,quantidade FROM produtos WHERE codigo=$codProduto FOR UPDATE";
            $resultado = @mysqli_query($conexao, $sql);
            if (!$resultado) {
                @mysqli_query($conexao, "ROLLBACK");
                $mensagem = 3;
                $response = array(
                    'status' => false,
                    'message' => $mensagem
                );
                mysqli_close($conexao);
                echo json_encode($response);
                die();
            } 
            if(mysqli_num_rows($resultado)>0){
                $dados = mysqli_fetch_array($resultado);

                if($quantidade>$dados['quantidade']){
                    @mysqli_query($conexao, "ROLLBACK");
                    $response = array(
                        'status' => false,
                        'message' => '<b>Não há a quantidade desejada do produto</b><br> cod.: '.$dados['codigo'].'-'.$dados['nome'].' em estoque.<br>A quantidade em estoque é: '.$dados['quantidade'],
                        'quantidade' => $dados['quantidade']

                    );
                    mysqli_close($conexao);
                    echo json_encode($response);
                    die();
                }
            }
        }
            
        //Inserindo o Pedido
        $mensagem = 5;
        $sql = "INSERT INTO pedidos(datapedido, entrega, cliente, vendedor, formapagamento, valortotalprodutos, valortotaldesconto, valortotalpedido) VALUES('$data', '$entrega', $cliente, $codvendedor, $formapagamento, $valorprodutos, $descontos, $total)";
        
        //Grava o pedido
        $resultado = @mysqli_query($conexao, $sql);
        //Se der erro dá um rollback e nem continua
        if (!$resultado) {
            @mysqli_query($conexao, "ROLLBACK");
            $mensagem = 3;
            $response = array(
                'status' => false,
                'message' => $mensagem
            );
            mysqli_close($conexao);
            echo json_encode($response);
            die();
        } 
        //Pega o codigo que acaba de ser incluido
        $last_id = mysqli_insert_id($conexao);

        //Cadastrando o itens do Pedido
        foreach($itemsPedido as $itemPedido){
            $codProduto = $itemPedido['codigoProduto'];
            $quantidade = $itemPedido['quantidade'];
            $valorUnitario = $itemPedido['valorUnitario'];
            $valorProdutos = $itemPedido['valorProdutos'];
            $porcentagemDesconto = $itemPedido['porcentagemDesconto'];
            $valorDesconto = $itemPedido['valorDesconto'];
            $valorTotal = $itemPedido['valorTotal'];

            //Gravando o item do pedido
            $sql = "INSERT INTO itemspedido(pedido, produto, quantidade, valorunitario, valorprodutos, porcentagemdesconto, valordesconto, valortotal) VALUES($last_id, $codProduto, $quantidade, $valorUnitario, $valorProdutos, $porcentagemDesconto, $valorDesconto, $valorTotal)";
            $sqlProduto = "UPDATE produtos SET quantidade=quantidade-$quantidade WHERE codigo=$codProduto";
        
            //Altera a quantidade do Produto
            $resultado = @mysqli_query($conexao, $sqlProduto);
            //Se der erro dá um rollback e nem continua
            if (!$resultado) {
                @mysqli_query($conexao, "ROLLBACK");
                $mensagem = 3;
                $response = array(
                    'status' => false,
                    'message' => $mensagem
                );
                mysqli_close($conexao);
                echo json_encode($response);
                die();
            }
            
            //Inclui o item do pedido
            $resultado = @mysqli_query($conexao, $sql);
            //Se der erro dá um rollback e nem continua
            if (!$resultado) {
                @mysqli_query($conexao, "ROLLBACK");
                $mensagem = 3;
                $response = array(
                    'status' => false,
                    'message' => $mensagem
                );
                mysqli_close($conexao);
                echo json_encode($response);
                die();
            } 
        }
       
        //Deu tudo certo
        @mysqli_query($conexao, "COMMIT");
        $response = array(
            'status' => true,
            'message' => $mensagem
        );
        mysqli_close($conexao);
        echo json_encode($response);
    }
?>