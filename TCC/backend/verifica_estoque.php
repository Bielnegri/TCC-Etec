<?php
    session_start();
    require('conexao.php');
    require('util.php');

    if(!isset($_SESSION['login'])){
        header("Location: ../index.php?codMsg=1");
        die();
    }
    if(!isset($_POST['codProduto']) || !isset($_POST['quantidade'])){
        $response = array(
            'status' => false,
            'message' => 'Campos obrigatórios nulos!'
        );
        echo json_encode($response);
        die();
    }
    if(!is_numeric($_POST['codProduto'])){
        $response = array(
            'status' => false,
            'message' => 'Campo Código Produto inválido!'
        );
        echo json_encode($response);
        die();
    }
    if(!is_numeric($_POST['quantidade'])){
        $response = array(
            'status' => false,
            'message' => 'Campo Quantidade inválido!'
        );
        echo json_encode($response);
        die();
    }
    $codigo=cleanCampo($_POST['codProduto']); 
    $quantidade=cleanCampo($_POST['quantidade']);

    $sql = "SELECT codigo,nome,quantidade FROM produtos WHERE codigo=$codigo";
    $resultado = @mysqli_query($conexao, $sql);
    if (!$resultado) {
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
            $response = array(
                'status' => false,
                'message' => '<b>Não há a quantidade desejada do produto</b><br> cod.: '.$dados['codigo'].'-'.$dados['nome'].' em estoque.<br>A quantidade em estoque é: '.$dados['quantidade'],
                'quantidade' => $dados['quantidade']

            );
            mysqli_close($conexao);
            echo json_encode($response);
            die();
        }
        $response = array(
            'status' => true,
            'message' => 'Tem no estoque!'
        );
        mysqli_close($conexao);
        echo json_encode($response);
        die();
    }   
?>