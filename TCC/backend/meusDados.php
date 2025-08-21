<?php
    session_start();
    require('conexao.php');
    require('util.php');
    if(!isset($_SESSION['login'])){
        header("Location: ../index.php?codMsg=1");
        die();
    }
    if(!isset($_SESSION['codUser'])){
        header("Location: ../formMeusDados.php?codMsg=2");
        die();
    }
    $codigo=$_SESSION['codUser'];
    $nome=cleanCampo($_POST['nome']);
    $senha=cleanCampo($_POST['senha']);
    $sql = "UPDATE usuarios SET nome='$nome', senha='$senha' WHERE codigo=$codigo";
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
    else{
        $mensagem = 6;
        $response = array(
            'status' => true,
            'message' => $mensagem
        );
        mysqli_close($conexao);
        echo json_encode($response);
    }
?>