<?php
    $conn = new PDO("mysql:dbname=pesquisa2020;host=localhost:3307","root","");

    if($conn){
        echo "<script>console.log('conexão bem sucedida')</script>";
    } else {
        die('Não foi possível conectar: ' .  $conn->errorInfo());
    }
?>
