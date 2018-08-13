<!doctype html>
<html>
    <?php
        require 'composants/header.php';
        //tableau nature établissement
        $natetab = array(
            'Personne morale',
            'Artisan-commerçant',
            'Commerçant',
            'Artisant',
            'Profession libérale',
            'Exploitant agricole',
            'Autre entreprenneur individuel'
        );
        //tableau des tranches de CA
        $tcaTable=array(
            "<0.5 million €",
            "0.5 à 1 million €",
            "1 à 2 millions €",
            "2 à 5 millions €",
            "5 à 10 millions €",
            "10 à 20 millions €",
            "20 à 50 millions €",
            "50 à 100 millions €",
            "100 à 200 millions €",
            ">200 millions €"
        );
        $id=$_GET['id'];
        //récupère les informations d'un id
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
        //controle la présence d'un résultat et le met en forme
        if($query['hits']['total']>=1){
            $results=$query['hits']['hits'];
            $info = $results[0]['_source'];
    ?>
    <head>
        <title>Resultat | ES</title>
    </head>
    <body>
        <h1>Informations entreprise : <font color="#17a2b8"><?=$info['NOMEN_LONG']?></font></h1>
        <br><br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <h3 id="ferme1">Informations générales de l'entreprise</h3>
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
                                            $info['ADR_MAIL']="Non renseignée";
                                        }
                                        echo $info['ADR_MAIL'];
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <h3 id="ferme3">Informations complémentaires entreprise</h3>
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
                                                echo "Non renseigné";
                                            }
                                            else{
                                                echo $tcaTable[intval($info['TCA'])];
                                            }
                                            
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
                                    <td>Code postal du siège de l'entreprise</td>
                                    <td>
                                        <?php
                                        echo $info['DEPCOMEN'];
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
            <div class="row">
                <div class="col-6">
                    <h3 id="ferme2">Informations sur le responsable</h3>
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
                    <br><br><br>
                </div>
                <div class="col-6">
                    <?php
                        if(count($_GET)>1){
                    ?>
                    <h3>Historique mises à jour</h3>
                    <br>
                    <?php
                        for($i=1;$i<count($_GET);$i++){
                            $id=$_GET['id'.$i];
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
                                $nom = $results[0]['_source']['NOMEN_LONG'];
                                $maDate = explode("-",$results[0]['_source']['DATEMAJ'] );
                                $year = $maDate[0];
                                $month=$maDate[1];
                                $day = $maDate[2][0]."".$maDate[2][1];
                                $maj=$day."/".$month."/".$year;
                            }
                            ?>
                            <a href="result.php?id=<?=$id?>"><?=$nom?> mis à jour le : <?=$maj?></a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>   
    </body>
    <?php
        }
        else{
    ?>
    <!-- Body affiché sur l'id passé en get n'existe pas -->
    <body>
        <h2>Erreur : </h2>
        <h2>L'id n'existe pas</h2>
    </body>
    <?php
        }
        require 'composants/footer.php';
    ?>
</html>
