<?php
    session_start();
    require('conexao.php');
    require('util.php');

    if(!isset($_SESSION['login'])){
        header("Location: ../index.php?codMsg=1");
        die();
    }
    if(!isset($_POST['codigo'])){
        $response = array(
            'status' => false,
            'message' => 'Campos obrigatórios nulos!'
        );
        echo json_encode($response);
        die();
    }
    if(!is_numeric($_POST['codigo'])){
        $response = array(
            'status' => false,
            'message' => 'Campo inválido!'
        );
        echo json_encode($response);
        die();
    }
    $codigo=cleanCampo($_POST['codigo']);

    //Desligando o auto commit
    @mysqli_query($conexao, "SET AUTOCOMMIT=0");
    //Inicia a transacao
    @mysqli_query($conexao, "START TRANSACTION");
        
    //Verificando os items do Pedido para devolver ao estoque
    $sql = "SELECT codigo,produto,quantidade FROM itemspedido WHERE pedido=$codigo";
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
    }else{
        while($dados = $resultado->fetch_assoc()){
            $codigoItemPedido = $dados['codigo'];
            $codigoProduto = $dados['produto'];
            $quantidade = $dados['quantidade'];
    
            $sql = "DELETE FROM itemspedido WHERE codigo= $codigoItemPedido";
            $sqlProduto = "UPDATE produtos SET quantidade=quantidade+$quantidade WHERE codigo=$codigoProduto";
            
            //Devolvo os items para o estoque
            $resultado1 = @mysqli_query($conexao, $sqlProduto);
    
            if (!$resultado1) {
                $response = array(
                    'status' => false,
                    'message' => 3
                );
                @mysqli_query($conexao, "ROLLBACK");
                mysqli_close($conexao);
                echo json_encode($response);
                die();
            }
            
            //Apagando o item do Pedido
            $resultado2 = @mysqli_query($conexao, $sql);
    
            if (!$resultado2) {
                $response = array(
                    'status' => false,
                    'message' => 3
                );
                @mysqli_query($conexao, "ROLLBACK");
                mysqli_close($conexao);
                echo json_encode($response);
                die();
            }
        }
    } 
    
    //Apagando o Pedido
    $sql = "DELETE FROM pedidos WHERE codigo=$codigo";

    $resultado = @mysqli_query($conexao, $sql);

    if (!$resultado) {
        $response = array(
            'status' => false,
            'message' => 3
        );
        @mysqli_query($conexao, "ROLLBACK");
        mysqli_close($conexao);
        echo json_encode($response);
        die();
    }

    $response = array(
        'status' => true,
        'message' => 7
    );
    @mysqli_query($conexao, "COMMIT");
    mysqli_close($conexao);
    echo json_encode($response);
?>