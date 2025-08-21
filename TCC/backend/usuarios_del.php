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

    //Verificando se existe pedido para o usuario
    //Se tiver nao pode excluir
    $sql = "SELECT codigo FROM pedidos WHERE vendedor=$codigo";
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
            'message' => 'Não pode ser excluído, existe pedido para esse Usuário/Vendedor.'
        );
        mysqli_close($conexao);
        echo json_encode($response);
        die();
    }


       
    $sql = "DELETE FROM usuarios WHERE codigo=$codigo";

    $resultado = @mysqli_query($conexao, $sql);

    if (!$resultado) {
        $response = array(
            'status' => false,
            'message' => 3
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