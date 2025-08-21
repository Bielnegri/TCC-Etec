<?php
    session_start();
    require('conexao.php');
    require('util.php');
    if(!isset($_SESSION['login'])){
        header("Location: ../index.php?codMsg=1");
        die();
    }
    if(!isset($_POST['codigo']) || !isset($_POST['nome']) || !isset($_POST['cpf']) || !isset($_POST['telefone']) || !isset($_POST['rua']) || !isset($_POST['numero']) || !isset($_POST['cidade']) || !isset($_POST['estado']) || !isset($_POST['pais'])){
        $response = array(
            'status' => false,
            'message' => 'Campos obrigatórios nulos!'
        );
        echo json_encode($response);
        die();
    }
    if(!is_numeric($_POST['codigo'])){
        header("Location: ../listClientes.phpcodMsg=2");
        die();
    }
    $codigo=cleanCampo($_POST['codigo']);
    $nome=cleanCampo($_POST['nome']);
    $cpf=cleanCampo($_POST['cpf']);
    $telefone=cleanCampo($_POST['telefone']);
    $cep=cleanCampo($_POST['cep']);
    $rua=cleanCampo($_POST['rua']);
    $numero=cleanCampo($_POST['numero']);
    $complemento=cleanCampo($_POST['complemento']);
    $bairro=cleanCampo($_POST['bairro']);
    $cidade=cleanCampo($_POST['cidade']);
    $estado=cleanCampo($_POST['estado']);
    $pais=cleanCampo($_POST['pais']);
    
    //Desligando o auto commit
    @mysqli_query($conexao, "SET AUTOCOMMIT=0");
    //Inicia a transacao
    @mysqli_query($conexao, "START TRANSACTION");

    if($codigo > 0){
        $mensagem = 6;
        $sql = "UPDATE clientes SET nome='$nome', cpf='$cpf' WHERE codigo=$codigo";

        //Altera o cliente
        $resultado = @mysqli_query($conexao, $sql);
        //Se der erro dá um rollback e nem continua
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

        //Altera o Telefone
        $sql = "UPDATE telefones SET numero='$telefone' WHERE clienteId=$codigo";

        $resultado = @mysqli_query($conexao, $sql);
        //Se der erro dá um rollback e nem continua
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

        //Altera o Telefone
        $sql = "UPDATE enderecos SET cep='$cep',rua='$rua',numero='$numero',complemento='$complemento',bairro='$bairro',cidade='$cidade',estado='$estado',pais='$pais' WHERE clienteId=$codigo";

        $resultado = @mysqli_query($conexao, $sql);
        //Se der erro dá um rollback e nem continua
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

        //Deu tudo certo
        @mysqli_query($conexao, "COMMIT");
        $response = array(
            'status' => true,
            'message' => $mensagem
        );
        mysqli_close($conexao);
        echo json_encode($response);

    }
    else{
        $sql = "SELECT codigo FROM clientes WHERE cpf='$cpf'";
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
                'message' => 'Cliente já cadastrado'
            );
            mysqli_close($conexao);
            echo json_encode($response);
            die();
        }
        $mensagem = 5;
        $sql = "INSERT INTO clientes(nome, cpf) VALUES('$nome', '$cpf')";

        //Grava o cliente
        $resultado = @mysqli_query($conexao, $sql);
        //Se der erro dá um rollback e nem continua
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
        //Pega o codigo que acaba de ser incluido
        $last_id = mysqli_insert_id($conexao);

        //Gravando o telefone
        $sql = "INSERT INTO telefones(numero, clienteId) VALUES('$telefone', $last_id)";
        //Grava o telefone
        $resultado = @mysqli_query($conexao, $sql);
        //Se der erro dá um rollback e nem continua
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

        //Gravando o telefone
        $sql = "INSERT INTO enderecos(cep, rua, numero, complemento, bairro, cidade, estado, pais, clienteId) VALUES('$cep', '$rua', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$pais', $last_id)";
        //Grava o telefone
        $resultado = @mysqli_query($conexao, $sql);
        //Se der erro dá um rollback e nem continua
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
        
        //Deu tudo certo
        @mysqli_query($conexao, "COMMIT");
        $response = array(
            'status' => true,
            'message' => $mensagem
        );
        mysqli_close($conexao);
        echo json_encode($response);
    }
?>