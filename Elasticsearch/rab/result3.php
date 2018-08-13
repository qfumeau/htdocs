<!doctype html>
<html>
    <?php
        
        require 'header.php';
        $natetab = array(
            'Personne morale',
            'Artisan-commerçant',
            'Commerçant',
            'Artisant',
            'Profession libérale',
            'Exploitant agricole',
            'Autre entreprenneur individuel'
        );
        $id=$_GET['id'];
        $query = $client->search([
            'body'=>[
                'query'=>[
                    'bool'=>[
                        'must'=>[
                            'match'=>['_id'=>$id]
                        ]
                    ]
                ]
            ]
        ]);
        if($query['hits']['total']>=1){
            $results=$query['hits']['hits'];
            $info = $results[0]['_source'];
    ?>
    <head>
        <title>Resultat | ES</title>
    </head>
    <body>
        <h1>Informations entreprise : <br><?=$info['NOMEN_LONG']?></h1>
        <br><br>
        <div class="accordion" id="accordionExample">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                    <button class="btn" id="btnColl" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        <h3 id="ferme1">Informations générales de l'entreprise <i class="fas fa-angle-down"/></i></h3>
                        <h3 id="ouvert1">Informations générales de l'entreprise <i class="fas fa-angle-up"></i></h3>
                    </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse_show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                    <table id="t1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Champ</th>
                                <th scope="col">Valeur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nom entreprise</td>
                                <td><?=$info['NOMEN_LONG']?></td>
                            </tr>
                            <tr>
                                <td>SIREN</td>
                                <td><?=$info['SIREN']?></td>
                            </tr>
                            <tr>
                                <td>Addresse (normalisée)</td>
                                <td><?=$info['L4_NORMALISEE']?></td>
                            </tr>
                            <tr>
                                <td>Numéro de la voie</td>
                                <td>
                                <?php
                                    if($info['NumVoie']==""){
                                        $info['NumVoie']="Non renseingée";
                                    }
                                    echo $info['NumVoie'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Code postal, ville (normalisée)</td>
                                <td><?=$info['L6_NORMALISEE']?></td>
                            </tr>
                            <tr>
                                <td>Pays (normalisé)</td>
                                <td><?=$info['L7_NORMALISEE']?></td>
                            </tr>
                            <tr>
                                <td>Addresse (déclarée)</td>
                                <td><?=$info['L4_DECLAREE']?></td>
                            </tr>
                            <tr>
                                <td>Code postal, ville (déclarée)</td>
                                <td><?=$info['L6_DECLAREE']?></td>
                            </tr>
                            <tr>
                                <td>Région</td>
                                <td><?=$info['LibReg']?></td>
                            </tr>
                            <tr>
                                <td>Pays (déclaré)</td>
                                <td>
                                <?php 
                                    if($info['L7_DECLAREE']==""){
                                        $info['L7_DECLAREE']=$info['L7_NORMALISEE'];
                                    }
                                    echo $info['L7_DECLAREE'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Adresse mail</td>
                                <td>
                                <?php
                                    //contrôle si le champ est vide ou non pour ne pas afficher un string vide 
                                        if($info['ADR_MAIL']==""){
                                            $info['ADR_MAIL']="Non renseigné";
                                        }
                                        echo $info['ADR_MAIL'];
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                    <button class="btn" id="btnColl" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h3 id="ferme2">Informations sur le responsable <i class="fas fa-angle-down"/></i></h3>
                        <h3 id="ouvert2">Informations sur le responsable <i class="fas fa-angle-up"></i></h3>
                    </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div class="card-body">
                    <table id="t2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">
                                Champ
                                </th>
                                <th scope="col">
                                Valeur
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nom</td>
                                <td>
                                <?php
                                    //contrôle si le champ est vide ou non pour ne pas afficher un string vide 
                                        if($info['NOM']==""){
                                            $info['NOM']="Non renseigné";
                                        }
                                        echo $info['NOM'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Prénom</td>
                                <td>
                                <?php
                                    //contrôle si le champ est vide ou non pour ne pas afficher un string vide 
                                        if($info['PRENOM']==""){
                                            $info['PRENOM']="Non renseigné";
                                        }
                                        echo $info['PRENOM'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Civilité</td>
                                <td>
                                <?php
                                    //contrôle si le champ est vide ou non pour ne pas afficher un string vide 
                                        if($info['CIVILITE']==""){
                                            $info['CIVILITE']="Non renseigné";
                                        }
                                        else{
                                            //dans sirene 1 est pour homme
                                            if($info['CIVILITE']=="1"){
                                                $info['CIVILITE']="H";
                                            }
                                            else{
                                                $info['CIVILITE']="F";
                                            }
                                        }
                                        echo $info['CIVILITE'];
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                    <button class="btn" id="btnColl" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <h3 id="ferme3">Informations complémentaires entreprise <i class="fas fa-angle-down"/></i></h3>
                        <h3 id="ouvert3">Informations complémentaires entreprise <i class="fas fa-angle-up"></i></h3>
                    </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                    <div class="card-body">
                    <table id="t3" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">
                                Champ
                                </th>
                                <th scope="col">
                                Valeur
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Activité principale de l'entreprise</td>
                                <td>
                                <?php
                                    //contrôle si le champ est vide ou non pour ne pas afficher un string vide 
                                        if($info['LIBAPEN']==""){
                                            $info['LIBAPEN']="Non renseigné";
                                        }
                                        echo $info['LIBAPEN'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Catégorie d'entreprise</td>
                                <td>
                                <?php
                                    //contrôle si le champ est vide ou non pour ne pas afficher un string vide 
                                        if($info['CATEGORIE']==""){
                                            $info['CATEGORIE']="Non renseigné";
                                        }
                                        echo $info['CATEGORIE'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Saisonat</td>
                                <td>
                                <?php
                                    //contrôle si le champ est vide ou non pour ne pas afficher un string vide 
                                        if($info['SAISONAT']=="NR"){
                                            $info['SAISONAT']="Non renseigné";
                                        }
                                        else{
                                            if($info['SAISONAT']=="P"){
                                                $info['SAISONAT']="Permanente";
                                            }
                                            else{
                                                $info['SAISONAT']="Saisonnière";
                                            }
                                        }
                                        echo $info['SAISONAT'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Tranche de chiffre d'affaire</td>
                                <td>
                                <?php
                                    //contrôle si le champ est vide ou non pour ne pas afficher un string vide 
                                        if($info['TCA']==""){
                                            $info['TCA']="Non renseigné";
                                        }
                                        echo $info['TCA'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Siège activité (oui/non)</td>
                                <td>
                                <?php
                                    //dans sirene, 1 correspond à "est le siège de l'activité"
                                        if($info['SIEGE']=="1"){
                                            $info['SIEGE']="Oui";
                                        }
                                        else{
                                            $info['SIEGE']="Non";
                                        }
                                        echo $info['SIEGE'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Région siège entreprise</td>
                                <td>
                                <?=$info['RPEN']?>
                                </td>
                            </tr>
                            <tr>
                                <td>Département et commune siège entreprise</td>
                                <td>
                                <?php
                                    echo 'Département : '.$info['DEPCOMEN'][0].'<br>Commune : '.$info['DEPCOMEN'][1];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Nature établissement</td>
                                <td>
                                <?php
                                    //évite les dépassements car les valeurs originelles 0,1,2,3,5,6,9
                                    if($info['NATETAB']=="9"){
                                    $info['NATETAB']="6";
                                    }
                                    if($info['NATETAB']=="5"){
                                    $info['NATETAB']="4";
                                    }
                                    echo $natetab[intval($info['NATETAB'])];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Effectif salariés à la centaine près</td>
                                <td>
                                <?php
                                    //contrôle si le champ est vide ou non pour ne pas afficher un string vide 
                                        if($info['EFETCENT']==""){
                                            $info['EFETCENT']="Non renseigné";
                                        }
                                        echo $info['EFETCENT'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Date début activité</td>
                                <td>
                                <?php
                                    //contrôle si le champ est vide ou non pour ne pas afficher un string vide 
                                        if($info['DEFET']==""){
                                            $info['DEFET']="Non renseigné";
                                        }
                                        echo $info['DEFET'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Nb établissement(s)</td>
                                <td>
                                <?php
                                    //si APET700 est égale à APEN700, alors l'entreprise n'a qu'une activité, elle en a plusieurs sinon
                                        if($info['APET700']==$info['APEN700']){
                                            echo 'Un seul établissement';
                                        }
                                        else{
                                            echo 'Plusieurs établissements';
                                        }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php
        }
        else{
    ?>
    <body>
        <h2>Erreur : </h2>
        <h2>L'id n'existe pas</h2>
    </body>
    <?php
        }
        require 'footer.php';
    ?>
</html>

<script>
    var cache = true;
    $("#ferme1").click(function(){
            $("#ferme1").hide("slow");
            $("#ouvert1").show("slow");
            $("#ferme2").show("slow");
            $("#ouvert2").hide("slow");
            $("#ferme3").show("slow");
            $("#ouvert3").hide("slow");
    });
    $("#ouvert1").click(function(){
            $("#ferme1").show("slow");
            $("#ouvert1").hide("slow");
            
    });
    $("#ferme2").click(function(){
            $("#ferme1").show("slow");
            $("#ouvert1").hide("slow");
            $("#ferme2").hide("slow");
            $("#ouvert2").show("slow");
            $("#ferme3").show("slow");
            $("#ouvert3").hide("slow");
    });
    $("#ouvert2").click(function(){
            $("#ferme2").show("slow");
            $("#ouvert2").hide("slow");
    });
    $("#ferme3").click(function(){
        $("#ferme1").show("slow");
            $("#ouvert1").hide("slow");
            $("#ferme2").show("slow");
            $("#ouvert2").hide("slow");
            $("#ferme3").hide("slow");
            $("#ouvert3").show("slow");
    });
    $("#ouvert3").click(function(){
            $("#ferme3").show("slow");
            $("#ouvert3").hide("slow");
    })
</script>