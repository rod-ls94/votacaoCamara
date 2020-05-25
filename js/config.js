var id;
var nome;
var partido;
var html;
var retorn;

$(document).ready(function (){
    $.get('php/candidatos.php', function(retorno){
        console.log(retorno);
        $("#teste").html(html);
    });
});