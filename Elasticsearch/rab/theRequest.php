<?php

	//fichier elasticsearch
    require_once 'app/init.php';
	
	// on se connecte ? MySQL
    $mysqli = new mysqli("localhost", "root", "", "siren");
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	
    $i = 1;
	
    while($i<=2000) {
        $query = "SELECT * FROM entreprises WHERE id=".$i;
        $result = $mysqli->query($query);
        $data = $result->fetch_array();
		
		//indexing and sending data to elasticsearch
        $indexed = $client->index([
            'index'=>'lesentreprises',
            'type'=>'entreprise',
			'id'=>$i,
            'body'=>[
                "id"=>utf8_encode($data[0]),
                "SIREN"=>utf8_encode($data[1]),
                "NIC"=>utf8_encode($data[2]),
                "L1_NORMALISEE"=>utf8_encode($data[3]),
                "L2_NORMALISEE"=>utf8_encode($data[4]),
                "L3_NORMALISEE"=>utf8_encode($data[5]),
                "L4_NORMALISEE"=>utf8_encode($data[6]),
                "L5_NORMALISEE"=>utf8_encode($data[7]),
                "L6_NORMALISEE"=>utf8_encode($data[8]),
                "L7_NORMALISEE"=>utf8_encode($data[9]),
                "L1_DECLAREE"=>utf8_encode($data[10]),
                "L2_DECLAREE"=>utf8_encode($data[11]),
                "L3_DECLAREE"=>utf8_encode($data[12]),
                "L4_DECLAREE"=>utf8_encode($data[13]),
                "L5_DECLAREE"=>utf8_encode($data[14]),
                "L6_DECLAREE"=>utf8_encode($data[15]),
                "L7_DECLAREE"=>utf8_encode($data[16]),
                "NumVoie"=>utf8_encode($data[17]),
                "IndRep"=>utf8_encode($data[18]),
                "TypVoie"=>utf8_encode($data[19]),
                "LibVoie"=>utf8_encode($data[20]),
                "CodPos"=>utf8_encode($data[21]),
                "Cedex"=>utf8_encode($data[22]),
                "RpEt"=>utf8_encode($data[23]),
                "LibReg"=>utf8_encode($data[24]),
                "DepEt"=>utf8_encode($data[25]),
                "ARRONET"=>utf8_encode($data[26]),
                "CTONET"=>utf8_encode($data[27]),
                "COMET"=>utf8_encode($data[28]),
                "LIBCOM"=>utf8_encode($data[29]),
                "DU"=>utf8_encode($data[30]),
                "TU"=>utf8_encode($data[31]),
                "UU"=>utf8_encode($data[32]),
                "EPCI"=>utf8_encode($data[33]),
                "TCD"=>utf8_encode($data[34]),
                "ZEMET"=>utf8_encode($data[35]),
                "SIEGE"=>utf8_encode($data[36]),
                "ENSEIGNE"=>utf8_encode($data[37]),
                "IND_PUBLIPO"=>utf8_encode($data[38]),
                "DIFFCOM"=>utf8_encode($data[39]),
                "AMINTRET"=>utf8_encode($data[40]),
                "NATETAB"=>utf8_encode($data[41]),
                "LIBNATETAB"=>utf8_encode($data[42]),
                "APET700"=>utf8_encode($data[43]),
                "LIBAPET"=>utf8_encode($data[44]),
                "DAPET"=>utf8_encode($data[45]),
                "TEFET"=>utf8_encode($data[46]),
                "LIBTEFET"=>utf8_encode($data[47]),
                "EFETCENT"=>utf8_encode($data[48]),
                "DEFET"=>utf8_encode($data[49]),
                "ORIGINE"=>utf8_encode($data[50]),
                "DCRET"=>utf8_encode($data[51]),
                "DATE_DEB_ETAT_ADM_ET"=>utf8_encode($data[52]),
                "ACTIVNAT"=>utf8_encode($data[53]),
                "LIEUACT"=>utf8_encode($data[54]),
                "ACTISURF"=>utf8_encode($data[55]),
                "SAISONAT"=>utf8_encode($data[56]),
                "MODET"=>utf8_encode($data[57]),
                "PRODET"=>utf8_encode($data[58]),
                "PRODPART"=>utf8_encode($data[59]),
                "AUXILT"=>utf8_encode($data[60]),
                "NOMEN_LONG"=>utf8_encode($data[61]),
                "SIGLE"=>utf8_encode($data[62]),
                "NOM"=>utf8_encode($data[63]),
                "PRENOM"=>utf8_encode($data[64]),
                "CIVILITE"=>utf8_encode($data[65]),
                "RNA"=>utf8_encode($data[66]),
                "NICSIEGE"=>utf8_encode($data[67]),
                "RPEN"=>utf8_encode($data[68]),
                "DEPCOMEN"=>utf8_encode($data[69]),
                "ADR_MAIL"=>utf8_encode($data[70]),
                "NJ"=>utf8_encode($data[71]),
                "LIBNJ"=>utf8_encode($data[72]),
                "APEN700"=>utf8_encode($data[73]),
                "LIBAPEN"=>utf8_encode($data[74]),
                "DAPEN"=>utf8_encode($data[75]),
                "APRM"=>utf8_encode($data[76]),
                "ESSEN"=>utf8_encode($data[77]),
                "DATEESS"=>utf8_encode($data[78]),
                "TEFEN"=>utf8_encode($data[79]),
                "LIBTEFEN"=>utf8_encode($data[80]),
                "EFENCENT"=>utf8_encode($data[81]),
                "DEFEN"=>utf8_encode($data[82]),
                "CATEGORIE"=>utf8_encode($data[83]),
                "DCREN"=>utf8_encode($data[84]),
                "AMINTREN"=>utf8_encode($data[85]),
                "MONOACT"=>utf8_encode($data[86]),
                "MODEN"=>utf8_encode($data[87]),
                "PRODEN"=>utf8_encode($data[88]),
                "ESAANN"=>utf8_encode($data[89]),
                "TCA"=>utf8_encode($data[90]),
                "ESAAPEN"=>utf8_encode($data[91]),
                "ESASEC1N"=>utf8_encode($data[92]),
                "ESASEC2N"=>utf8_encode($data[93]),
                "ESASEC3N"=>utf8_encode($data[94]),
                "ESASEC4N"=>utf8_encode($data[95]),
                "VMAJ"=>utf8_encode($data[96]),
                "VMAJ1"=>utf8_encode($data[97]),
                "VMAJ2"=>utf8_encode($data[98]),
                "VMAJ3"=>utf8_encode($data[99]),
                "DATEMAJ"=>utf8_encode($data[100])
            ]
        ]);
		//echo ' '.$i.'<br>';
        $i++;
    }
	
?>