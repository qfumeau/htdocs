<?php
    
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
   
    $test=array();
    //évite les recherches vides duent à une suppression de texte dans un input
    $champPlein=false;
    for($i=0;$i<10;$i++){
        if(isset($_GET[''.$i])&&!empty($_GET[''.$i])){
            $champPlein=true;
        }
    }
    if($champPlein)
    {
        for($i=0;$i<10;$i++){
            if($_GET[''.$i]!=""){
                array_push($test,''.$champTab[$i].'"'.$_GET[''.$i].'"');
            }
        }
        $json='{
            "query" : {
                "bool" :{
                    "must": 
                        [';
                            for($i=0;$i<count($test);$i++){
                                if($i!=count($test)-1){
                                    $json = $json.'
                                    {"match" : {'. $test[$i].'}},';
                                }
                                else{
                                    $json = $json.'
                                    {"match" : {'.$test[$i].'}}';
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
        $pays = $_GET['0'];
                    $cat=$_GET['1'];
                    $query = $client->search($params);
        
       if($query['hits']['total']>=1){
           $results=$query['hits']['hits'];
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
        <form action="index.php" method="get" autocomplete="off" class="monForm">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Recherche générale" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Chercher</button>
                </div>
            </div>
        </form>
       
        <button type="button" class="btn btn-info" id="clickme2">Utiliser la recherche spécifique ↓</button>
        <button type="button" class="btn btn-info" id="clickme3">Masquer la recherche spécifique ↑</button>
        <div id="leForm" class="fromSpec">
            <br>
            <form action="index.php" id="searchSpec" method="get" autocomplete="off" class="monForm">
                <div class="container-fluid">
                    <div class='row'>
                        <label>
                            Nom entreprise
                            <br>
                            <input class="autoSuggest" type="text" name="0" id="0">
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
                    echo '<br><br><div class="count">10+ résultat(s)</div>';
                }
                else{
                    echo '<br><br><div class="count">'.count($results).' résultat(s)</div>';
                }
                
        ?>
        <br><br>
        <div class="container-fluid" id="results">
            <?php
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
                        <a href="result.php?id=<?=$results[''.$i]['_id']?>">Nom : <?= $results[''.$i]['_source']['NOMEN_LONG']?></a>
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
                        <a href="result.php?id=<?=$results[''.$i]['_id']?>">Nom : <?= $results[''.$i]['_source']['NOMEN_LONG']?></a>
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
                        <a href="result.php?id=<?=$results[''.$i]['_id']?>">Nom : <?= $results[''.$i]['_source']['NOMEN_LONG']?></a>
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