<?php
    session_start();
    require('conexao.php');
    require('util.php');
    if(!isset($_SESSION['login'])){
        header("Location: ../index.php?codMsg=1");
        die();
    }

    if(!isset($_POST['campobusca']) || !isset($_POST['valorpesquisa'])){
        $response = array(
            'status' => false,
            'message' => 'Campos obrigatórios nulos!'
        );
        echo json_encode($response);
        die();
    }

    $campo=cleanCampo($_POST['campobusca']);
    $valorpesquisa=cleanCampo($_POST['valorpesquisa']);

    $sql = "SELECT codigo,nome,descricao,valor,quantidade FROM produtos WHERE quantidade>=1";
    if($campo=="codigo" && $valorpesquisa<>""){
        $sql = $sql . " AND codigo=$valorpesquisa";
    }
    if(($campo == "nome" || $campo == "cpf") && $valorpesquisa<>""){
        $sql = $sql . " AND $campo LIKE '%$valorpesquisa%'";
    }
    $sql = $sql . " ORDER BY nome";
	$resultado = @mysqli_query($conexao, $sql);

  	if (!$resultado || $resultado->num_rows == 0) {
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
        $rows = array();
        while ($row = mysqli_fetch_assoc($resultado)){
            $rows[] = $row;
        }
        $mensagem = 6;
        $response = array(
            'status' => true,
            'message' => $mensagem,
            'data' => json_encode($rows)
        );
        mysqli_close($conexao);
        echo json_encode($response);
    }
?>