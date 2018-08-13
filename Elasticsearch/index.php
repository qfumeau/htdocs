<?php
    $tabDate= array();
    $chaineGet="";
    require_once 'app/init.php';
    //tableau de correspondance entre la données natetab et la liste des modalités 
    //Aller voir 'https://www.sirene.fr/sirene/public/variable/syr-natetab' en cas de doute
    $natetab = array(
        'Personne morale',
        'Artisan-commerçant',
        'Commerçant',
        'Artisant',
        'Profession libérale',
        'Exploitant agricole',
        'Autre entreprenneur individuel'
    );
    //tableau utilisé pour mettre en forme la requète de recherche spécifique
    $champTab=array(
        '"NOMEN_LONG" : ',
        '"SIGLE" : ',
        '"SIREN" : ',
        '"RpEt" : ',
        '"CATEGORIE" : ',
        '"NOM" : ',
        '"PRENOM" : ',
        '"ADR_MAIL" : ',
        '"LIBAPEN" : ',
        '"CODPOS" : ',
        
    );
   
    $tabRequest=array();
    //évite les recherches vides duent à une suppression de texte dans un input
    $champPlein=false;
    for($i=0;$i<10;$i++){
        if(isset($_GET[''.$i])&&!empty($_GET[''.$i])){
            $champPlein=true;
        }
    }

    if($champPlein)
    {
        //permet d'associer chaque champ à sa donnée ex : "NOMEN_LONG":"le_nom_saisi"
        for($i=0;$i<10;$i++){
            if($_GET[''.$i]!=""){
                array_push($tabRequest,''.$champTab[$i].'"'.$_GET[''.$i].'"');
            }
        }
        //permet d'envoyer les données à Elasticsearch en bouclant sur celles-ci
        $json='{
            "query" : {
                "bool" :{
                    "must": 
                        [';
                            for($i=0;$i<count($tabRequest);$i++){
                                if($i!=count($tabRequest)-1){
                                    $json = $json.'
                                    {"match" : {'. $tabRequest[$i].'}},';
                                }
                                else{
                                    $json = $json.'
                                    {"match" : {'.$tabRequest[$i].'}}';
                                }
                            }
                            $json=$json.'
                        ]
                }
            }
        }';
        $params=[
            'body'=>$json
        ];
        $query = $client->search($params);
        $sirenQuery=false;
        //controle si la requête est vide et reformate le tableau de sortie
        if($query['hits']['total']>=1){
            $results=$query['hits']['hits'];
            if(!empty($_GET['2'])||!empty($_GET['0'])){
                $sirenQuery=true;
                for($j=0;$j<count($results);$j++){
                    $monId=$results[$j]['_id'];
                    $maDate = explode("-",$results[$j]['_source']['DATEMAJ'] );
                    $year = $maDate[0];
                    $month=$maDate[1];
                    $day = $maDate[2][0]."".$maDate[2][1];
                    $maDateEnvoie = array(
                        'year'=>$year,
                        'month'=>$month,
                        'day'=>$day,
                        'id'=>$monId
                    );
                    array_push($tabDate,$maDateEnvoie);
                }
                for($j=0;$j<count($tabDate);$j++){
                    for($k=0;$k<(count($tabDate)-$i);$k++){
                        if(intval($tabDate[$k]['year'])<intval($tabDate[$k+1]['year'])){
                            $temp = $tabDate[$k+1];
                            $tabDate[$k+1]=$tabDate[$k];
                            $tabDate[$k]=$temp;
                        }
                    }
                }
                for($j=1;$j<count($tabDate);$j++){
                    $chaineGet=$chaineGet."&id".$j."=".$tabDate[$j]['id'];
                }
            }
        } 
    }
    else{
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            //permet d'avoir tous les resultats contenant la chaine recherchée
            $params=[
                'body'=>[
                    'query'=>[
                        'multi_match'=>[
                            'query'=>$search,
                            'type'=>'phrase', //évite les recherche liée aux mot de laison
                        ]
                    ]
                ]
            ];
            $query = $client->search($params);
            //controle la présene du résultat et le met en forme
            if($query['hits']['total']>=1){
                $results=$query['hits']['hits'];
            } 
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <?php require 'composants/header.php';?>
        <title>Chercher | ES</title>
    </head>
	<body>
        <h1>Recherche dans la base sirene</h1>
        <br><br>
        <!-- Formulaire de recherche générale -->
        <form action="index.php" method="get" autocomplete="off" class="monForm">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Recherche générale" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Chercher</button>
                </div>
            </div>
        </form>
        <!-- Boutons pour afficher/masquer la recherche spécifique -->
        <button type="button" class="btn btn-info" id="clickme2">Utiliser la recherche spécifique ↓</button>
        <button type="button" class="btn btn-info" id="clickme3">Masquer la recherche spécifique ↑</button>
        <div id="leForm" class="fromSpec">
            <br>
            <!-- Formulaire de recherche spécifique -->
            <form action="index.php" id="searchSpec" method="get" autocomplete="off" class="monForm">
                <div class="container-fluid">
                    <div class='row'>
                        <label>
                            Nom entreprise
                            <br>
                            <input class="autoSuggest" type="text" name="0" id="0">
                            <!-- Affichage des résultats de la recherche prédictive -->
                            <div class="dropdown">
                                <ul class="list-group" id="mesResult">
                                </ul>
                            </div>
                        </label>
                        <br>
                        <label>
                            Sigle
                            <br>
                            <input type="text" name="1" id="1">
                        </label>
                        <br>
                        <label>
                            N° SIREN
                            <br>
                            <input type="text" name="2" id="2">
                        </label>
                        <br>
                        <label>
                            Région
                            <br>
                            <input type="text" placeholder="ex : 33" name="3" id="3">
                        </label>
                        <br>
                        <label>
                            Catégorie
                            <br>
                            <input type="text" placeholder="ex : pme" name="4" id="4">
                        </label>
                        <br>
                    </div>
                    <div class="row">
                        <label>
                            Nom responsable
                            <br>
                            <input type="text" name="5" id="5">
                        </label>
                        <br>
                        <label>
                            Prénom responsable
                            <br>
                            <input type="text" name="6" id="6">
                        </label>
                        <br>
                        <label>
                            Adresse mail entreprise
                            <br>
                            <input type="text" name="7" id="7">
                        </label>
                        <br>
                        <label>
                            Activité entreprise
                            <br>
                            <input type="text" name="8" id="8">
                        </label>
                        <br>
                        <label>
                            Code postal
                            <br>
                            <input type="text" name="9" id="9">
                            </label>
                        <br>
                        <label>
                            <br>
                            <input id="advSearch" type="submit" value="Chercher" class='btn btn-secondary'>
                        <label>
                    </div>
                </div>
            </form>
        </div>
        <?php
            if(isset($results)){
                $compte=0;
                if(count($results)>=10){
                    echo '<br><br><div class="count">10+ résultats</div>';
                }
                else{
                    if($sirenQuery){echo '<br><br><div class="count">1 résultat</div>';}
                    else{echo '<br><br><div class="count">'.count($results).' résultat(s)</div>';}
                }
                
        ?>
        <br><br>
        <!-- Affichage des résultats sur 2 lignes de 5 cases maximum -->
        <div class="container-fluid" id="results">
            <?php
                if($sirenQuery){
                    
                    for($i=0;$i<count($results);$i++){
                        if($tabDate[0]['id']==$results[$i]['_id']){
                            $interm=$results[0];
                            $results[0]=$results[$i];
                            $results[$i]=$interm;
                        }
                    }
                    for($i=0;$i<count($tabDate);$i++){
                        //évite les dépassements car les valeurs originelles 0,1,2,3,5,6,9
                        if($results[''.$i]['_source']['NATETAB']=="9"){
                            $results[''.$i]['_source']['NATETAB']="6";
                        }
                        if($results[''.$i]['_source']['NATETAB']=="5"){
                            $results[''.$i]['_source']['NATETAB']="4";
                        }
                    }
            ?>
            <div class="row">
                <div class="col-2">
                    <a href="result.php?id=<?=$results[0]['_id'].$chaineGet?>"><?= $results[0]['_source']['NOMEN_LONG']?></a>
                    <div class="siren">N° Siren : <?= $results[0]['_source']['SIREN']?></div>
                    <div class="adresse">Adresse : <?= $results[0]['_source']['L4_NORMALISEE']?></div>
                    <div class="laRegion">Région : <?= $results[0]['_source']['LibReg']?></div>
                    <div class="laCat">Catégorie : <?= $results[0]['_source']['CATEGORIE']?></div>
                    <div class="natureEt">Nature activité : <?= $natetab[intval($results[0]['_source']['NATETAB'])]?></div>
                    <div class="siege">Siège activité : 
                        <?php 
                            if($results[0]['_source']['SIEGE']=="1"){
                                echo "Oui";
                            }
                            else{
                                echo "Non";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php
                }
                else{
                    if(count($results)>5){
            ?>
            <div class="row">
                <?php
                    for($i=0;$i<5;$i++){
                        $compte++;
                        //évite les dépassements car les valeurs originelles 0,1,2,3,5,6,9
                        if($results[''.$i]['_source']['NATETAB']=="9"){
                            $results[''.$i]['_source']['NATETAB']="6";
                        }
                        if($results[''.$i]['_source']['NATETAB']=="5"){
                            $results[''.$i]['_source']['NATETAB']="4";
                        }
                ?>
                <br><br>
                <div class="col-2">
                        <a href="result.php?id=<?=$results[''.$i]['_id']?>"><?= $results[''.$i]['_source']['NOMEN_LONG']?></a>
                        <div class="siren">N° Siren : <?= $results[''.$i]['_source']['SIREN']?></div>
                        <div class="adresse">Adresse : <?= $results[''.$i]['_source']['L4_NORMALISEE']?></div>
                        <div class="laRegion">Région : <?= $results[''.$i]['_source']['LibReg']?></div>
                        <div class="laCat">Catégorie : <?= $results[''.$i]['_source']['CATEGORIE']?></div>
                        <div class="natureEt">Nature activité : <?= $natetab[intval($results[''.$i]['_source']['NATETAB'])]?></div>
                        <div class="siege">Siège activité : 
                            <?php 
                                if($results[''.$i]['_source']['SIEGE']=="1"){
                                    echo "Oui";
                                }
                                else{
                                    echo "Non";
                                }
                            ?>
                        </div>
                </div>
                <?php
                    }
                ?>
            </div>
            <br><br>
            <div class="row">
                <?php 
                    for($i=5;$i<count($results);$i++){
                        if($results[''.$i]['_source']['NATETAB']=="9"){
                            $results[''.$i]['_source']['NATETAB']="6";
                        }
                        if($results[''.$i]['_source']['NATETAB']=="5"){
                            $results[''.$i]['_source']['NATETAB']="4";
                        }
                ?>
                <div class="col-2">
                        <a href="result.php?id=<?=$results[''.$i]['_id']?>"><?= $results[''.$i]['_source']['NOMEN_LONG']?></a>
                        <div class="siren">N° Siren : <?= $results[''.$i]['_source']['SIREN']?></div>
                        <div class="adresse">Adresse : <?= $results[''.$i]['_source']['L4_NORMALISEE']?></div>
                        <div class="laRegion">Région : <?= $results[''.$i]['_source']['LibReg']?></div>
                        <div class="laCat">Catégorie : <?= $results[''.$i]['_source']['CATEGORIE']?></div>
                        <div class="natureEt">Nature activité : <?= $natetab[intval($results[''.$i]['_source']['NATETAB'])]?></div>
                        <div class="siege">Siège activité : 
                            <?php 
                                if($results[''.$i]['_source']['SIEGE']=="1"){
                                    echo "Oui";
                                }
                                else{
                                    echo "Non";
                                }
                            ?>
                        </div>
                </div>
                <?php
                    }
                ?>
            </div>
            <?php
                }
                else{
            ?>
            <div class="row">
                <?php
                    for($i=0;$i<count($results);$i++){
                        $compte++;
                        //évite les dépassements car les valeurs originelles 0,1,2,3,5,6,9
                        if($results[''.$i]['_source']['NATETAB']=="9"){
                            $results[''.$i]['_source']['NATETAB']="6";
                        }
                        if($results[''.$i]['_source']['NATETAB']=="5"){
                            $results[''.$i]['_source']['NATETAB']="4";
                        }
                ?>
                <br><br>
                <div class="col-2">
                    <div class="result">
                        <a href="result.php?id=<?=$results[''.$i]['_id']?>"><?= $results[''.$i]['_source']['NOMEN_LONG']?></a>
                        <div class="siren">N° Siren : <?= $results[''.$i]['_source']['SIREN']?></div>
                        <div class="adresse">Adresse : <?= $results[''.$i]['_source']['L4_NORMALISEE']?></div>
                        <div class="laRegion">Région : <?= $results[''.$i]['_source']['LibReg']?></div>
                        <div class="laCat">Catégorie : <?= $results[''.$i]['_source']['CATEGORIE']?></div>
                        <div class="natureEt">Nature activité : <?= $natetab[intval($results[''.$i]['_source']['NATETAB'])]?></div>
                        <div class="siege">Siège activité : 
                            <?php 
                                if($results[''.$i]['_source']['SIEGE']=="1"){
                                    echo "Oui";
                                }
                                else{
                                    echo "Non";
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
            <?php
                }
            }
            ?>
        </div>
        <?php
            echo '<br><br>';
            }
            else{
                if(isset($_GET['0'])&&!empty($_GET['0'])){
                    echo '<br><br><div class="noResult">Pas de résultat</div>';
                }
                else{
                    if(isset($_GET['search'])&&!empty($_GET['search'])){
                        echo '<br><br><div class="noResult">Pas de résultat</div>';
                    }
                }
            }
        ?>
    </body>
    <?php require 'composants/footer.php' ?>
</html>

<script src="js/functions.js"></script>