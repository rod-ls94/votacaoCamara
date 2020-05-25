<?php
    include('connectdb/connectdb.php'); //incluindo conexão ao bd

    $acao = isset($_GET['acao'])? $_GET['acao']:'';

    if(!$acao){
        $stmt = $conn->prepare("SELECT * FROM vereador ORDER BY nome"); //query para puxar vereadores

        $stmt->execute(); //executando a query

        $candidatos = $stmt->fetchall(PDO::FETCH_ASSOC); 
    }
    
    
    
?>