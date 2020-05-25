<?php
    include('../connectdb/connectdb.php'); //incluindo conexão ao bd

    $stmt = $conn->prepare("SELECT * FROM vereador ORDER BY nome"); //query para puxar vereadores

    $stmt->execute(); //executando a query

    $candidatos = $stmt->fetchall(PDO::FETCH_ASSOC); //

    exit (json_encode($candidatos));
    
?>