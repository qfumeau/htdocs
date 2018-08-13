<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<?php
// on se connecte � MySQL

    $mysqli = new mysqli("dev.btobag.local", "root", "", "siren");
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    $i = 1;
    $myTab=array();
    $theFields=array(
        "id",
        "SIREN",
        "NIC",
        "L1_NORMALISEE",
        "L2_NORMALISEE",
        "L3_NORMALISEE",
        "L4_NORMALISEE",
        "L5_NORMALISEE",
        "L6_NORMALISEE",
        "L7_NORMALISEE",
        "L1_DECLAREE",
        "L2_DECLAREE",
        "L3_DECLAREE",
        "L4_DECLAREE",
        "L5_DECLAREE",
        "L6_DECLAREE",
        "L7_DECLAREE",
        "NumVoie",
        "IndRep",
        "TypVoie",
        "LibVoie",
        "CodPos",
        "Cedex",
        "RpEt",
        "LibReg",
        "DepEt",
        "ARRONET",
        "CTONET",
        "COMET",
        "LIBCOM",
        "DU",
        "TU",
        "UU",
        "EPCI",
        "TCD",
        "ZEMET",
        "SIEGE",
        "ENSEIGNE",
        "IND_PUBLIPO",
        "DIFFCOM",
        "AMINTRET",
        "NATETAB",
        "LIBNATETAB",
        "APET700",
        "LIBAPET",
        "DAPET",
        "TEFET",
        "LIBTEFET",
        "EFETCENT",
        "DEFET",
        "ORIGINE",
        "DCRET",
        "DATE_DEB_ETAT_ADM_ET",
        "ACTIVNAT",
        "LIEUACT",
        "ACTISURF",
        "SAISONAT",
        "MODET",
        "PRODET",
        "PRODPART",
        "AUXILT",
        "NOMEN_LONG",
        "SIGLE",
        "NOM",
        "PRENOM",
        "CIVILITE",
        "RNA",
        "NICSIEGE",
        "RPEN",
        "DEPCOMEN",
        "ADR_MAIL",
        "NJ",
        "LIBNJ",
        "APEN700",
        "LIBAPEN",
        "DAPEN",
        "APRM",
        "ESSEN",
        "DATEESS",
        "TEFEN",
        "LIBTEFEN",
        "EFENCENT",
        "DEFEN",
        "CATEGORIE",
        "DCREN",
        "AMINTREN",
        "MONOACT",
        "MODEN",
        "PRODEN",
        "ESAANN",
        "TCA",
        "ESAAPEN",
        "ESASEC1N",
        "ESASEC2N",
        "ESASEC3N",
        "ESASEC4N",
        "VMAJ",
        "VMAJ1",
        "VMAJ2",
        "VMAJ3",
        "DATEMAJ"  
    );
    while($i<=10) {
        $query = "select * from entreprises where id='.$i.'";
        $result = $mysqli->query($query);
        $data = $result->fetch_array();
        array_push($myTab,
        "{\"index\":{\"_index\":\"lesentreprises\",\"_type\":\"entreprises\",\"_id\":".$i."}}"
        );
        $chaine="{\"fields\":{";
        for($k=0;$k<count($theFields);$k++){
            if($k==count($theFields)-1){
                $chaine =$chaine."\"".$theFields[$k]."\":\"".$data[$k]."\"";
            }
            else{
                $chaine =$chaine."\"".$theFields[$k]."\":\"".$data[$k]."\",";
            }
            
        }
        $chaine=$chaine."}}";
        array_push($myTab,$chaine);
        //echo $data['id'].'-'.$data['SIREN'].'-'.$data['NIC'].'<br>';
        $i++;
    }
    array_push($myTab,"<br>");
    for($j=0;$j<count($myTab);$j++){
        echo '<br>'.utf8_encode($myTab[$j]);
    }
?>

 