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

    //Verificando se existe pedido para o produto
    //Se tiver nao pode excluir
    $sql = "SELECT codigo FROM itemspedido WHERE produto=$codigo";
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
        @mysqli_query($conexao, "ROLLBACK");
        $response = array(
            'status' => false,
            'message' => 'Não pode ser excluído, existe pedido para esse Produto.'
        );
        mysqli_close($conexao);
        echo json_encode($response);
        die();
    }
      
    $sql = "DELETE FROM produtos WHERE codigo=$codigo";

    $resultado = @mysqli_query($conexao, $sql);

    if (!$resultado) {
        $response = array(
            'status' => false,
            'message' => 'Problema conexão com o bando de dados'
        );
        mysqli_close($conexao);
        echo json_encode($response);
        die();
    } 
    else{
        $response = array(
            'status' => true,
            'message' => 7
        );
        mysqli_close($conexao);
        echo json_encode($response);
    }
?>