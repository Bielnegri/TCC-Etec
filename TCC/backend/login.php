<?php
    session_start();
    require('conexao.php');
    require('util.php');
    if(!isset($_POST['usuario']) || !isset($_POST['senha'])){
        $response = array(
            'status' => false,
            'message' => 'Campos obrigatórios nulos!'
        );
        echo json_encode($response);
        die();
    }
    $usuario=cleanCampo($_POST['usuario']);
    $senha=cleanCampo($_POST['senha']);
    // O BINARY é para fazer a comparação em case sensitivy
    // Esta comentado pois no MySql da Etec o BINARY não funciona
    // $sql = "SELECT * FROM usuarios WHERE BINARY usuario='$usuario' AND BINARY senha='$senha'";
    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND senha='$senha'";
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
    if(mysqli_num_rows($resultado)>0)
    {
        $dados = mysqli_fetch_array($resultado);
        $_SESSION['codUser'] = $dados['codigo'];
        $_SESSION['login'] = $usuario;
        $_SESSION['nome'] = $dados['nome'];
        $_SESSION['direitos'] = explode(",",$dados['direitos']);
        $response = array(
            'status' => true,
            'message' => 'Sucesso'
        );
	}else{
        $response = array(
            'status' => false,
            'message' => 'Dados de acesso inválidos'
        );
    }
	mysqli_close($conexao);
    echo json_encode($response);
?>