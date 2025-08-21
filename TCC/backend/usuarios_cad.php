<?php
    session_start();
    require('conexao.php');
    require('util.php');
    if(!isset($_SESSION['login'])){
        header("Location: ../index.php?codMsg=1");
        die();
    }
    if(!isset($_POST['codigo']) || !isset($_POST['nome']) || !isset($_POST['usuario']) || !isset($_POST['senha']) || !isset($_POST['direitos'])){
        $response = array(
            'status' => false,
            'message' => 'Campos obrigatórios nulos!'
        );
        echo json_encode($response);
        die();
    }
    if(!is_numeric($_POST['codigo'])){
        header("Location: ../listUsuarios.phpcodMsg=2");
        die();
    }
    $codigo=cleanCampo($_POST['codigo']);
    $nome=cleanCampo($_POST['nome']);
    $usuario=cleanCampo($_POST['usuario']);
    $senha=cleanCampo($_POST['senha']);
    $direitos=implode(",",$_POST['direitos']);

    if($codigo > 0){
        $mensagem = 6;
        $sql = "UPDATE usuarios SET nome='$nome', senha='$senha', direitos='$direitos' WHERE codigo=$codigo";
    }
    else{
        $sql = "SELECT codigo FROM usuarios WHERE usuario='$usuario'";
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
            $response = array(
                'status' => false,
                'message' => 'Usuário já cadastrado'
            );
            mysqli_close($conexao);
            echo json_encode($response);
            die();
        }
        $mensagem = 5;
        $sql = "INSERT INTO usuarios(nome, usuario, senha, direitos) VALUES('$nome','$usuario','$senha','$direitos')";
    }
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
        $response = array(
            'status' => true,
            'message' => $mensagem
        );
        mysqli_close($conexao);
        echo json_encode($response);
    }
?>