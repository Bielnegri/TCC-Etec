<?php
    session_start();
    require('conexao.php');
    require('util.php');
    if(!isset($_SESSION['login'])){
        header("Location: ../index.php?codMsg=1");
        die();
    }
    if(!isset($_POST['codigo']) || !isset($_POST['nome']) || !isset($_POST['valor']) || !isset($_POST['quantidade']) || !isset($_POST['descricao'])){
        $response = array(
            'status' => false,
            'message' => 'Campos obrigatórios nulos!'
        );
        echo json_encode($response);
        die();
    }
    if(!is_numeric($_POST['codigo'])){
        header("Location: ../listProdutos.php?codMsg=2");
        die();
    }
    $codigo=cleanCampo($_POST['codigo']);
    $nome=cleanCampo($_POST['nome']);
    $descricao=cleanCampo($_POST['descricao']);
    $valor=cleanCampo($_POST['valor']);
    $quantidade=cleanCampo($_POST['quantidade']);

    if(!is_numeric($quantidade)){
        $response = array(
            'status' => false,
            'message' => 'Campo Quantidade deve ser numérico'
        );
        echo json_encode($response);
        die();
    }
    if(!preg_match('/^[0-9]+(\.[0-9]{2})?$/',$valor)){
        $response = array(
            'status' => false,
            'message' => 'Campo Valor deve ser numérico '.$valor
        );
        echo json_encode($response);
        die();
    }

    if($codigo > 0){
        $mensagem = 6;
        $sql = "UPDATE produtos SET nome='$nome', descricao='$descricao', valor=$valor, quantidade=$quantidade WHERE codigo=$codigo";
    }
    else{
        $sql = "SELECT * FROM produtos WHERE BINARY nome='$nome'";
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
        if(mysqli_num_rows($resultado)>0){
            $response = array(
                'status' => false,
                'message' => 'Produto já cadastrado'
            );
            mysqli_close($conexao);
            echo json_encode($response);
            die();
        }
        $mensagem = 5;
        $sql = "INSERT INTO produtos(nome, descricao, valor, quantidade) VALUES('$nome','$descricao',$valor,$quantidade)";
    }
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