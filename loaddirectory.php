<?php
 include("/var/www.cache/dgconn.inc");
$iIdProjet = $_REQUEST['variable'];
    $sql_repertoire = "SELECT nom_repertoire FROM cc_sr_projet WHERE id_projet = $iIdProjet ";
    $query_repertoire = @pg_query($conn,$sql_repertoire) or die ( @pg_last_error($conn) );
    $rows = @pg_fetch_array($query_repertoire);
    $zRepertoire = $rows['nom_repertoire'];
	
    if($zRepertoire =='showroom_rec$'){
        echo "<option style='height: 20px;' value=''>-- S&eacute;lectionner --</option>";
    }
    else{
        echo "<option style='height: 20px;' value=''>-- S&eacute;lectionner --</option>";
    }
//$zServer = 'bigserver';
$zServer = '/mnt';
$aSousRep = array();
$iIdImmatricule = $_REQUEST['variable2'];
    if( $zRepertoire != '' ){
        if ($file2 = opendir($zServer.'/'.$zRepertoire.'/')){
            while(false !== ($fichier2 = readdir($file2))){
                if($fichier2 != ".." && $fichier2 != "."){
                
                    $sql_prenom = "SELECT prenompersonnel  FROM personnel  WHERE matricule =$fichier2";
                    $queryprenom = pg_query($conn,$sql_prenom);
                    $rws = pg_fetch_result($queryprenom,0,0);
                
                    if (is_dir ($zServer.'/'.$zRepertoire.'/'.$fichier2))
                    $aSousRep[$fichier2] = ucfirst(strtolower($fichier2)).' '.ucfirst(strtolower($rws));
                }
            }
        }
        // trie par du tableau suivant les clés
        ksort($aSousRep);
        foreach ($aSousRep as $key => $val) {
            echo "<option style='height: 20px;' value='$key'>".$val."</option>";
        }
        /***************************************************************/

    }



?>