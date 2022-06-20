<?php
    include('connectdb/connectdb.php'); //incluindo conexão ao bd

    $stmtCandidato = $conn->prepare("SELECT * FROM vereador ORDER BY nome"); //query para puxar vereadores
    $stmtCandidato->execute(); //executando a query

    $candidatos = $stmtCandidato->fetchall(PDO::FETCH_ASSOC);

    //FUNÇÃO PARA VALIDAR O CPF DIGITADO
    function validaCpf($cpf) {
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    //FUNÇÃO PARA INSERIR DADOS DO USUARIO
    function insertUser($nome, $cpf, $checks, $conn){
        //CONSULTANDO SE O CPF JA CONSTA NA BASE
        $stmtUser = $conn->prepare("SELECT id FROM usuario WHERE cpf=:CPF");
        $stmtUser->bindParam(":CPF", $cpf);
        $stmtUser->execute();
        
        $verificaCPF = $stmtUser->fetchall(PDO::FETCH_ASSOC);

        if(!$verificaCPF){
            //INSREINDO OS DADOS DO USUÁRIO SE AINDA NÃO HOUVER DADOS NO CADASTRO
            $stmtInsertUser = $conn->prepare("INSERT INTO usuario (nome,cpf) VALUES (:NOME, :CPF)");
            $stmtInsertUser->bindParam(":NOME", $nome);
            $stmtInsertUser->bindParam(":CPF", $cpf);

            $stmtInsertUser->execute(); //EXECUTANDO QUERY InsertUser

            //PEGANDO O ID DO USUARIO INSERIDO
            $stmtIdUser = $conn->prepare("SELECT id FROM usuario WHERE cpf=:CPF");
            $stmtIdUser->bindParam(":CPF", $cpf);

            $stmtIdUser->execute(); //EXECUTANDO QUERY IdUser

            $idUser = $stmtIdUser->fetchall(PDO::FETCH_ASSOC);

            return $idUser;
        } else {
            $erro = "CPF ja votou";
            return $erro;
        }
    }

    //FUNÇÃO PARA REALIZAR O VOTO
    function insertVoto($idUser, $checks, $conn){
        $id = $idUser[0]['id'];

        //INSERINDO VOTO
        foreach ($checks as $chk) {
            $idCandidato = $chk;

            $stmtInsertVoto = $conn->prepare("INSERT INTO votos (id_vereador,id_usuario) VALUES (:VEREADOR, :USUARIO)");
            $stmtInsertVoto->bindParam(":VEREADOR", $idCandidato);
            $stmtInsertVoto->bindParam(":USUARIO", $id);

            $stmtInsertVoto->execute(); //EXECUTANDO QUERY InserVoto
        }
        //CONFIRMANDO SE O VOTO FOI REALIZADO
        $stmtIdUserVoto = $conn->prepare("SELECT id_usuario FROM votos WHERE id_usuario=:ID");
        $stmtIdUserVoto->bindParam(":ID", $id);
        $stmtIdUserVoto->execute();

        $idUser = $stmtIdUserVoto->fetchall(PDO::FETCH_ASSOC);
        if($idUser){
            return true;
        } else {
            return "Erro ao inserir voto";
        }
    }

    function pesquisaVotos($conn){
        $stmtVotos = $conn->prepare("SELECT ver.nome FROM votos vt INNER JOIN vereador ver ON ver.id = vt.id_vereador ORDER BY id_usuario");
        $stmtVotos->execute();

        $array = $stmtVotos->fetchall(PDO::FETCH_ASSOC);

        return $array;
    }
    
?>