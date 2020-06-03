<?php
    include('php/funcoes.php');

    $acao   = isset($_POST['acao'])? $_POST['acao']:'';
    $cpf    = isset($_POST['cpf'])? $_POST['cpf']:'';
    $nome   = isset($_POST['nome'])? $_POST['nome']:'';
    $checks = isset($_POST['check'])? $_POST['check']:'';

    $validaCpf = '';
    $error = '';
    $success = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
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
            $error .= "Campo Nome vazio, preencha para concluir o voto<br>";
        }
        if($cpf == '') {
            $error .= "Campo CPF vazio, preencha para concluir o voto<br>";
        }
        if($checks == '') {
            $error .= "Selecione ao menos 1 candidato para concluir seu voto";
        }
    }

    //FUNÇÃO PARA PEGAR INFORMAÇÕES PARA O GRÁFICO
    $arrayVotos = pesquisaVotos($conn);

    //SEPARANDO NOME DOS CANDIDATOS E UM ARRAY
    $arrayRes = [];
    foreach($arrayVotos as $voto){
        $arrayRes[] = $voto['nome'];
    }

    $resultado = array_count_values($arrayRes); //AGRUPANDO E CONTANDO QUANTIDADE DE CANDIDATOS REPETIDOS
?>

<html>
    <head>
        <title>Enquete 2020</title>
        <meta charset="utf-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="libs/bootstrap-4.4.1-dist/css/bootstrap.css">
        <link href="css/bootstrap.min.css" rel="stylesheet" />
        <script src="js/bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="js/config.js"></script>
        <script src="libs/Highcharts-8.1.0/code/highcharts.js"></script>
        <script src="libs/Highcharts-8.1.0/code/modules/data.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-dark bg-dark">
            <span class="navbar-text">Eleiçoes 2020</span>
        </nav>
        <hr>
        <form name="usuario" method="POST" >
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
                        <input type="text" id="cpf" name="cpf" class="form-control"  placeholder="Insira seu CPF" oninput="mascara(this, 'cpf')">
                    </div>
                    <div class="txt-center" style="float:left">
                        <button type="submit" class="btn btn-primary " id="acao" name="acao" data-toggle="modal" data-target="#modalExemplo" value="votar">Votar</button>
                    </div>
                    <div class="txt-center" style="float:right">
                        <button type="button" class="btn btn-primary txt-center" id="acao" name="acao" data-toggle="modal" data-target="#modalResult" value="resultado"> Resultados</button>
                    </div>
                </div>
                <div id="modalResult" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gráfico de Resultados</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="container"></div>
                                <table id="grafico" class="grafico">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <?php
                                                //INSERINDO NOME DOS CANDIDATOS NA TABELA
                                                foreach($resultado as $key => $value){
                                            ?>
                                            <th><?php echo $key ?></th>
                                            <?php
                                                }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Votos</th>
                                            <?php
                                                foreach($resultado as $key => $value){
                                            ?>
                                            <td align="center"><?php echo $value ?></td>
                                            <?php
                                                }
                                            ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-top-bt-20">
                <?php
                    if($success){
                ?>
                    <div class="alert alert-success">
                        <strong>Votado!</strong> Votação realizada com sucesso.
                    </div>
                <?php
                    } if($error) {
                ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
                <?php       
                    }
                ?>
            </div>
            <hr>
            <div class="main-cards " id="form-candidato">
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
                        <p class="card-text" value="<?php echo $partidoCandidato ?>"><b>Partido:&nbsp;</b>
                            <?php    echo $partidoCandidato ?>
                        </p>
                        <p class="card-text"><b>periodo de mandato:&nbsp;</b>06-2010</p>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="check[]" id="check" value="<?php echo $idCandidato ?>"> <!-- INSERINDO INPUT COM ID DE CADA CANDIDATO -->
                        </div>
                    </div>
                </div>
                <br><hr>
                <?php
                    } //FIM FOREACH
                ?>
            </div>
        </form>

        <!-- INSERINDO AS INFORMAÇÕES DO GRÁFICO EM CAMPO HIDDEN PARA BUSCAR PELO JS -->
        <input type="hidden" id="result" value="<?php echo $result ?>">
            
        </script>
        <!-- GERANDO GRÁFICO -->
        <script>
            Highcharts.chart('container', {
                data: {
                    table: 'grafico'
                },
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Enquete Vereadores'
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Quantidade de Votos'
                    }
                },
                tooltip: {
                    formatter: function () {
                        return '<b>' + this.series.name + '</b><br/>' +
                            this.point.y + ' ' + this.point.name.toLowerCase();
                    }
                }
            });
        </script>
    </body>
</html>