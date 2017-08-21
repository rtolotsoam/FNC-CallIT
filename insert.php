<?php
     session_start();
    include("/var/www.cache/dgconn.inc");
    $iMatricule_session = $_SESSION['matricule'];
       
$data = $_REQUEST['data'];

$zProjet = $_REQUEST['zProjet'];

$matriculeRetour = $_REQUEST['matriculeRetour'];
$zMatricule =          $_REQUEST['matricule'];
$date_entretien   =   $_REQUEST['date_entr'];
$debut_entretien  =   $_REQUEST['heure'];
$fichier          =   pg_escape_string($_REQUEST['fichier']);

$dureewav         =   $_REQUEST['dureewav'];
$matriculeRetour  =   $_REQUEST['matriculeRetour'];
$zProjet1         =   $_REQUEST['zProjet1'];
$zCommentaire_gen =   pg_escape_string($_REQUEST['commentaire_gen']);
$zObjectif        =   pg_escape_string($_REQUEST['objectif']);
$dureexp = explode(':', $dureewav);
/*************resuperer le nom du repertoire*********/
    $sql_repertoire = "SELECT nom_repertoire FROM cc_sr_projet WHERE id_projet=$zProjet1";
    $query_repertoire = pg_query($conn,$sql_repertoire);
    $rows = pg_fetch_array($query_repertoire);
    $zRepertoire = $rows['nom_repertoire'];

/************************/    

$duree_total_sec = ($dureexp[0] * 60) + $dureexp[1];
//echo "<input type='hidden' id='projet' value='$zProjet' />";

if($zRepertoire == 'showroom_rec$'){

$chemin_fichier = 'bigserver/'.$zRepertoire.'/'.$matriculeRetour.'/';
//echo "<input type='hidden' id='matriculeRetour' value='$matriculeRetour' />";
}else{
$chemin_fichier = 'bigserver/'.$zRepertoire.'/'.$zMatricule;
//echo "<input type='hidden' id='matricule' value='$zMatricule' />";
}
$sql_test = "select * FROM cc_sr_fichier WHERE nom_fichier ='$fichier'";
$query_test = pg_query($conn,$sql_test);
 if ( pg_num_rows($query_test) > 0 )
 {
    $rows = pg_fetch_array($query_test);
    $fichier = $rows['id_fichier'];
    $sql_n = "SELECT id_fichier,matricule FROM cc_sr_notation WHERE id_fichier=$fichier limit 1";
    $requete = pg_query($conn,$sql_n) or die (@pg_last_error($conn));
    $ligne = pg_fetch_assoc($requete);
        if($zMatricule != $ligne['matricule']){
              echo 'non';
              exit;
        }
 }
 else{
 $reqfichier = "INSERT  INTO cc_sr_fichier (nom_fichier,chemin_fichier,duree_conversation) values ('$fichier','$chemin_fichier',$duree_total_sec)";

$sqlfichier =  pg_query($conn,$reqfichier) or die (pg_last_error($conn));
   
    if($sqlfichier){

    $select = pg_query($conn,"SELECT id_fichier from cc_sr_fichier order by id_fichier desc limit 1");
      
                $rows = pg_fetch_array($select);
                
       
    }
    $fichier = $rows['id_fichier'];
 }
 


$data_exp = explode('||',$data);

$data_exp1 = array();
     $notation = "INSERT  INTO cc_sr_notation (matricule,date_entretien,debut_entretien,id_fichier,date_notation,duree_entretien,matricule_notation,id_projet,commentaire_general,objectif) VALUES ('$zMatricule','$date_entretien','$debut_entretien','$fichier',CURRENT_DATE,$duree_total_sec,$iMatricule_session,$zProjet1,'$zCommentaire_gen','$zObjectif')";
    $sqlnotation = pg_query($conn, $notation ) or die (pg_last_error($conn));
    if($sqlnotation){
          $selectNot = pg_query($conn,"SELECT id_notation from cc_sr_notation order by id_notation desc limit 1");
          $row = pg_fetch_array($selectNot);
     
    }
    $idNotation = $row['id_notation'];
              
            
     $data_exp = explode('||',$data);
      
      for($i=0;$i<count($data_exp)-1;$i++){
     
      
        $ligne  = explode('&&',$data_exp[$i]);
     
        $zCommentaire = pg_escape_string(utf8_decode($ligne[1]));
        $sql = "INSERT  INTO cc_sr_indicateur_notation (id_notation,id_grille,commentaire,note) VALUES('$idNotation','$ligne[2]',' $zCommentaire','$ligne[0]')";

       $sql_indic_notation = pg_query($conn,$sql); 
           
     }       
               
            if( $sql_indic_notation ){
            //echo "<h2 style='color:#0101DF;'>L'insertion est effectu&eacute;e avec succ&egrave;s</h2><br />";
              echo 'ok';
            //echo "<input type='button' id='retourAccueil' onclick='retour();' value='retour Accueil' />";
            }else{
                echo 'ko';
            //echo "<h2 style='color:#0101DF;'>Echec d'insertion</h2>";
            }
            /**if($sql_indic_notation){
            echo ("<SCRIPT LANGUAGE='JavaScript'>
        window.alert('Succesfully Updated')
        window.location.href='http://localhost/gpao/gpao2/cc_sr/';
        </SCRIPT>");
        }*/



?>
