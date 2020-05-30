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
        <link rel="stylesheet" type="text/css" href="css/styles.css">

    </head>
    <body>

            <nav class="navbar navbar-dark bg-dark">
                 <span class="navbar-text">Eleiçoes 2020</span>
            </nav>

                    <hr>
  <form name="usuario" method="POST">
            <!-- new-form -->
  <div class="card user-info">
  <div class="card-body ">
  <div class="form-group">
        <p>Para participar da votação, por favor insira seu  nome e seu CPF</p>
         <label for="nomeInput">Nome </label>
           <input type="text" id="nome" name="nome" class="form-control"  placeholder="Insira seu nome"  aria-describedby="emailHelp">
    </div>
     <div class="form-group">
       <label for="cpfUsuario">CPF</label>
      <input type="text" id="cpf" name="cpf" class="form-control"  placeholder="Insira seu CPF">
  </div>
    <div class="txt-center">
          <button type="submit" class="btn btn-primary "  id="acao" name="acao" data-toggle="modal" data-target="#modalExemplo">Enviar</button>
    </div>
   </div>
   </div>
        <hr>

         
            <div class="main-cards">
                <?php 
                    foreach ($candidatos as $can){ //INICIO FOREACH
                        $idCandidato      = $can['id'];
                        $nomeCandidato    = $can['nome'];
                        $partidoCandidato = $can['partido'];
                ?>
                <div class="card">
                 <div class="card-body">
                    <h5 class="card-title"  value="<?php echo $nomeCandidato ?>">
                         <?php    echo $nomeCandidato ?>
                    </h5>
                    <img src="assets/unavailable-image.jpg" class="card-img-top" alt="...">
                    <p class="card-text" value="<?php echo $partidoCandidato ?>"><b>Partido:&nbsp;</b>
                        <?php    echo $partidoCandidato ?>
                    </p>
                    <p class="card-text"><b>periodo de mandato:&nbsp;</b>06-2010</p>
                    <!-- <div class="custom-control custom-checkbox"> -->
                <input type="checkbox" name="check[]" id="check" value="<?php echo $idCandidato ?>"> <!-- INSERINDO INPUT COM ID DE CADA CANDIDATO -->
                    <!-- <label class="custom-control-label" for="check">selecionar candidato</label> -->
                <!-- </div> -->
                
                </div>
                </div>
                <!-- <input type="checkbox" name="check[]" id="check" value="<?php echo $idCandidato ?>"> INSERINDO INPUT COM ID DE CADA CANDIDATO -->
               <!-- Default checked -->
                
                <br><hr>
                <?php
                    } //FIM FOREACH
                ?>
            </div>
        </form>
    </body>
</html><html>
    <head>
        <title>Enquete 2020</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="libs/bootstrap-4.4.1-dist/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/styles.css">

    </head>
    <body>

            <nav class="navbar navbar-dark bg-dark">
                 <span class="navbar-text">Eleiçoes 2020</span>
            </nav>

                    <hr>
  <form name="usuario" method="POST">
            <!-- new-form -->
  <div class="card user-info">
  <div class="card-body ">
  <div class="form-group">
        <p>Para participar da votação, por favor insira seu  nome e seu CPF</p>
         <label for="nomeInput">Nome </label>
           <input type="text" id="nome" name="nome" class="form-control"  placeholder="Insira seu nome"  aria-describedby="emailHelp">
    </div>
     <div class="form-group">
       <label for="cpfUsuario">CPF</label>
      <input type="text" id="cpf" name="cpf" class="form-control"  placeholder="Insira seu CPF">
  </div>
    <div class="txt-center">
          <button type="submit" class="btn btn-primary "  id="acao" name="acao" data-toggle="modal" data-target="#modalExemplo">Enviar</button>
    </div>
   </div>
   </div>
        <hr>

         
            <div class="main-cards">
                <?php 
                    foreach ($candidatos as $can){ //INICIO FOREACH
                        $idCandidato      = $can['id'];
                        $nomeCandidato    = $can['nome'];
                        $partidoCandidato = $can['partido'];
                ?>
                <div class="card">
                 <div class="card-body">
                    <h5 class="card-title"  value="<?php echo $nomeCandidato ?>">
                         <?php    echo $nomeCandidato ?>
                    </h5>
                    <img src="assets/unavailable-image.jpg" class="card-img-top" alt="...">
                    <p class="card-text" value="<?php echo $partidoCandidato ?>"><b>Partido:&nbsp;</b>
                        <?php    echo $partidoCandidato ?>
                    </p>
                    <p class="card-text"><b>periodo de mandato:&nbsp;</b>06-2010</p>
                    <!-- <div class="custom-control custom-checkbox"> -->
                <input type="checkbox" name="check[]" id="check" value="<?php echo $idCandidato ?>"> <!-- INSERINDO INPUT COM ID DE CADA CANDIDATO -->
                    <!-- <label class="custom-control-label" for="check">selecionar candidato</label> -->
                <!-- </div> -->
                
                </div>
                </div>
                <!-- <input type="checkbox" name="check[]" id="check" value="<?php echo $idCandidato ?>"> INSERINDO INPUT COM ID DE CADA CANDIDATO -->
               <!-- Default checked -->
                
                <br><hr>
                <?php
                    } //FIM FOREACH
                ?>
            </div>
        </form>
    </body>
</html>