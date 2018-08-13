<?php
    //cette page permet d'avoir les résultats d'une recherche prédictive de Elasticsearch et envoie les données 
    //à la page index.php par l'ajax
    require_once 'app/init.php';
    //controle la présence d'une recherche non nulle
    if(isset($_POST['search_term'])&&!empty($_POST['search_term'])){
        $search = $_POST['search_term'];
        $params=[
            'body'=>[
                'suggest'=>[
                    'b-suggest'=>[
                        'text'=>$search,
                        'completion'=>[
                            'field'=>'NOMEN_LONG',
                            'skip_duplicates'=>true
                        ]
                    ]
                ]
            ]
        ];
        //pécifie l'index où se trouve les données à parcourir pour ES
        $params['index']='b';
        $query = $client->search($params);
        $arrayRes = array();
        //récupère les données le tableau à une structure propre à ES, pour plus d'infos :
        //(https://www.elastic.co/guide/en/elasticsearch/reference/current/search-suggesters-completion.html)
        for($i=0;$i<count($query['suggest']['b-suggest'][0]['options']);$i++){
            $chaine = $query['suggest']['b-suggest'][0]['options'][$i]['text'];
                //on affiche chaque ligne pour qu'elle soit récupérée par jquery
                echo ' <li class="list-group-item"><div class="cutText">',$chaine,'</div></li>';
        }
    }
?>