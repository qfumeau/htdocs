//permet de cacher les champs de recherche spécifique
$("#clickme2").click(function(){
    $("#leForm").show(0);
    $("#clickme2").hide(0);
    $("#clickme3").show(0);
});
$("#clickme3").click(function(){
    $("#leForm").hide(0);
    $("#clickme3").hide(0);
    $("#clickme2").show(0);
    //nettoie les champs quand la recherche est masquée
    for(var i =0;i<9;i++){
        $('#'+i).val('');
        console.log(i);
    }
})
//permet l'auto suggestion
$(".autoSuggest").keyup(function(){
    //récupère la valeur du champ
    var search_term = $(this).val();
    //la transmet à la page de recherche
    $.post('searchSuggest.php',{search_term:search_term},function(data){
        //ajoute le résulat de la recherche à la liste
        $('#mesResult').html(data);
        //permet de cliquer sur un items
        $('#mesResult li').click(function(){
            //ajoute le texte à l'input
            var resultVal = $(this).text();
            $('.autoSuggest').val(resultVal);
            //nettoie la liste
            $('#mesResult').html('');
        })
    })
})