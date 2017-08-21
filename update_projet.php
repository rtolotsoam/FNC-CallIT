<?php
 include("/var/www.cache/dgconn.inc");
$idProjet = $_REQUEST ['id_projet'];
$zProjet =  $_REQUEST ['nom_projet'];
$idClient =  $_REQUEST ['id_client'];
$idCampagne = $_REQUEST ['nom_campagne'];
$id_application = $_REQUEST ['id_application'];


   
   
 $sql_update = "UPDATE cc_sr_projet SET nom_projet = '$zProjet',    campagne_easycode = '$idCampagne', id_client =$idClient,id_application =$id_application 
           WHERE id_projet=$idProjet";
	   
		
        $query_update = @pg_query($conn,$sql_update) or die(pg_last_error());
        
         if( $query_update ){
                 echo 'ok';  
            }
			else
			{
               echo 'ko';            
            }

 
     
?>