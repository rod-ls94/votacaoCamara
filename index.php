<?php
    include('php/funcoes.php');

    $acao   = isset($_POST['acao'])? $_POST['acao']:'';
    $cpf    = isset($_POST['cpf'])? $_POST['cpf']:'';
    $nome   = isset($_POST['nome'])? $_POST['nome']:'';
    $checks = isset($_POST['check'])? $_POST['check']:'';

    if($cpf) $validaCpf = validaCpf($cpf); //VERIFICANDO SE O CPF É VÁLIDO

    if($validaCpf === false) {
        $error = "CPF inválido, por favor preencha um CPF válido para concluir o voto<br>";
    }

    if($nome && $validaCpf && $checks) {
        $returnUser = insertuser($nome, $cpf, $checks, $conn); //INSERINDO DADOS DO USUARIO E RETORNANDO SEU ID
        
        if(is_array($returnUser)) {
            $returnVoto = insertVoto($returnUser, $checks, $conn); //INSERINDO VOTO E CONFIRMANDO SE FOI REALIZADO O VOTO
            if($returnVoto === true){
                $success = 'Voto computado!';
            } else {
                $error = $returnVoto;
            }
        } else {
            $error = $returnUser;
        }
    }

    if($nome == '') {
        $error .= "Campo Nome Vazio, preencha para concluir o voto<br>";
    }
    if($cpf == '') {
        $error .= "Campo CPF, preencha para concluir o voto<br>";
    }
    if($checks == '') {
        $error .= "Selecione ao menos 1 candidato para concluir seu voto";
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
        <div>
            <?php
                if($success){
                    echo json_encode(utf8_encode($success));
                } else {
                    echo json_encode(utf8_encode($error));
                }
            ?>
        </div>
    </body>
</html>