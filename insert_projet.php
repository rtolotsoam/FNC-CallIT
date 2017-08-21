<?php
 include("/var/www.cache/dgconn.inc");
$idProjet = $_REQUEST ['idProjet'];
$zProjet =  utf8_decode ( $_REQUEST ['zProjet'] );
$zClient =  $_REQUEST ['zClient'];
$zCampagne = $_REQUEST ['zCampagne'];
    if( $zCampagne=='' ){
	    $zCampagne=0;
	}
$zApplication = utf8_encode( $_REQUEST ['zApplication'] );
// echo $idProjet.'##'.$zProjet.'##'.$zClient.'##'.$zRepertoire.'##'.$zApplication;

   if($idProjet == ''){
		$sql_check = @pg_query( $conn," SELECT * FROM cc_sr_projet WHERE id_client = '$zClient'  AND id_application = '$zApplication'  " );
		if ( @pg_num_rows($sql_check) > 0  )
		{
			echo "d";
			exit;
		}
         $sql_projet = "INSERT INTO cc_sr_projet (nom_projet,campagne_easycode,id_client,id_application,archivage) VALUES('$zProjet','$zCampagne','$zClient','$zApplication',1)";
         
		 $query = pg_query($conn,$sql_projet) or die (pg_last_error());
           

            if($query){
               echo "ok";
            }else{
            echo "ko";
            }
   }  
    else{
        
$sql_update = "UPDATE cc_sr_projet SET nom_projet = '$zProjet', nom_repertoire = '$zRepertoire', id_client =$zClient,id_application =$zApplication 
           where id_projet=$idProjet";
        $query_update = @pg_query($conn,$sql_update);
        
         if($query_update){
                 echo 'ok';
              /** echo ("<SCRIPT LANGUAGE='JavaScript'>
                    window.alert('Modification reussie')
                    window.location.href='interface.php';
                    </SCRIPT>");*/
            }else{
            echo 'ko';
            //echo "Echec de Modification";
            }

   }
     
?>