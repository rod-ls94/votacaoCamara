<?php
    $conn = new PDO("mysql:dbname=pesquisa2020;host=localhost","root","criasoft123");

    if($conn){
        //echo "<script>console.log('conexão bem sucedida')</script>";
    } else {
        die('Não foi possível conectar: ' .  $conn->errorInfo());
    }
?>