<?php require 'composants/header.php';?>
<!doctype html>
<html>
    <head>
        <title>Statistiques | ES</title>
    </head>
    <body>
        <h1>Page de statistiques</h1>
        <h2>/!\ Page en construction /!\</h2>
        <br><br>
        <div class="accordion" id="accordionExample">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                    <button class="btn" id="btnColl" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        <h3 id="ferme1">Catégories d'entreprises <i class="fas fa-angle-down"/></i></h3>
                        <h3 id="ouvert1">Catégories d'entreprises <i class="fas fa-angle-up"></i></h3>
                    </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <iframe src="http://localhost:5601/app/kibana#/visualize/edit/ca0a3d30-6b04-11e8-b803-eda707ee635a?embed=true&_g=()" height="600" width="800"></iframe>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                    <button class="btn" id="btnColl" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h3 id="ferme2">Nombre salariés <i class="fas fa-angle-down"/></i></h3>
                        <h3 id="ouvert2">Nombre salariés <i class="fas fa-angle-up"></i></h3>
                    </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div class="card-body">
                        <iframe onerror="alert('fail')" src="http://localhost:5601/app/kibana#/visualize/edit/f6787210-6b04-11e8-b803-eda707ee635a?embed=true&_g=()" height="600" width="800"></iframe>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                    <button class="btn" id="btnColl" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <h3 id="ferme3">Tranches CA <i class="fas fa-angle-down"/></i></h3>
                        <h3 id="ouvert3">Tranches CA <i class="fas fa-angle-up"></i></h3>
                    </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                    <div class="card-body">
                        <iframe src="http://localhost:5601/app/kibana#/visualize/edit/16891050-6b05-11e8-b803-eda707ee635a?embed=true&_g=()" height="600" width="800"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php require 'composants/footer.php';?>
</html>

<script>
    $("#ferme1").click(function(){
            $("#ferme1").hide(0);
            $("#ouvert1").show(0);
            $("#ferme2").show(0);
            $("#ouvert2").hide(0);
            $("#ferme3").show(0);
            $("#ouvert3").hide(0)
    });
    $("#ouvert1").click(function(){
            $("#ferme1").show(0);
            $("#ouvert1").hide(0);
            
    });
    $("#ferme2").click(function(){
            $("#ferme1").show(0);
            $("#ouvert1").hide(0);
            $("#ferme2").hide(0);
            $("#ouvert2").show(0);
            $("#ferme3").show(0);
            $("#ouvert3").hide(0);
    });
    $("#ouvert2").click(function(){
            $("#ferme2").show(0);
            $("#ouvert2").hide(0);
    });
    $("#ferme3").click(function(){
        $("#ferme1").show(0);
            $("#ouvert1").hide(0);
            $("#ferme2").show(0);
            $("#ouvert2").hide(0);
            $("#ferme3").hide(0);
            $("#ouvert3").show(0);
    });
    $("#ouvert3").click(function(){
            $("#ferme3").show(0);
            $("#ouvert3").hide(0);
    })
</script>