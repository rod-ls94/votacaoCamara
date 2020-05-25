<?php
    include('php/candidatos.php');

    $acao   = isset($_POST['acao'])? $_POST['acao']:'';
    $cpf    = isset($_POST['cpf'])? $_POST['cpf']:'';
    $nome   = isset($_POST['nome'])? $_POST['nome']:'';
    $checks = isset($_POST['check'])? $_POST['check']:'';

    if($nome != '' && $cpf != ''){
        
    } else {
        $erro = "Preencha nome e CPF para concluir o seu voto";
    }

?>
<html>
    <head>
        <title>Enquete 2020</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="libs/bootstrap-4.4.1-dist/css/bootstrap.css">
    </head>
    <body>
        <h1>Vereadores 2020</h1>
        <hr>
        <form name="usuario" method="POST">
            <div class="centralizar">
                <p>Para participar da votação, por favor insira seu  nome e seu CPF</p>
                <input type="text" id="nome" name="nome" placeholder="Insira seu nome">
                <input type="text" id="cpf" name="cpf" placeholder="Insira seu CPF">
                <input type="submit" id="acao" name="acao" class="btn btn-primary" value="Votar">
            </div>
            <!-- Botão para acionar modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalExemplo">
            Abrir modal de demonstração
            </button>

            <hr>
            <div class="margens">
                <?php 
                    foreach ($candidatos as $can){ //INICIO FOREACH
                        $idCandidato      = $can['id'];
                        $nomeCandidato    = $can['nome'];
                        $partidoCandidato = $can['partido'];
                ?>
                <input type="checkbox" name="check[]" id="check" value="<?php echo $idCandidato ?>"> <!-- INSERINDO INPUT COM ID DE CADA CANDIDATO -->
                <?php 
                    echo $nomeCandidato . " - " . $partidoCandidato //EXIBINDO NOME DOS CANDIDATOS
                ?>
                <br><hr>
                <?php
                    } //FIM FOREACH
                ?>
            </div>
        </form>
    </body>
</html>