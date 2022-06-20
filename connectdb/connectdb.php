<?php
    $conn = new PDO("mysql:dbname=pesquisa2020;host=172.17.0.1","root","criasoft123");

    if($conn){
        //echo "<script>console.log('conexão bem sucedida')</script>";
    } else {
        die('Não foi possível conectar: ' .  $conn->errorInfo());
    }
?>
