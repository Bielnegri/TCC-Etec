<?php
    session_start();
    require('conexao.php');
    require('util.php');
    if(!isset($_SESSION['login'])){
        header("Location: ../index.php?codMsg=1");
        die();
    }
    if(!isset($_POST['codigoPedido']) || !isset($_POST['selectStatus'])){
        $response = array(
            'status' => false,
            'message' => 'Campos obrigatórios nulos!'
        );
        echo json_encode($response);
        die();
    }
    if(!is_numeric($_POST['codigoPedido'])){
        header("Location: ../listPedidos.php?codMsg=2");
        die();
    }
    $codigo=cleanCampo($_POST['codigoPedido']);
    $status=cleanCampo($_POST['selectStatus']);

    $sql = "SELECT status FROM pedidos WHERE codigo=$codigo";

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

    $dados = mysqli_fetch_assoc($resultado);
    
    if(($status - $dados['status']) !=1 && $status != 5){
        $response = array(
            'status' => false,
            'message' => 'Não é permitido pular ou retroceder etapas do processo.'
        );
        mysqli_close($conexao);
        echo json_encode($response);
        die();
    }

    $mensagem = 6;
    $sql = "UPDATE pedidos SET status=$status WHERE codigo=$codigo";

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
            'message' => $mensagem
        );
        mysqli_close($conexao);
        echo json_encode($response);
    }

?>