<?php
   session_start();
  $zMatricule = $_SESSION['matricule'];
  include("/var/www.cache/dgconn.inc");
  include("function_grille_.php");

	
    
  $idProjet = $_REQUEST['IdProjet']; 

  $idApplication = $_REQUEST['id_application'];
  $idClient = $_REQUEST['id_client'];
  
  $zApplication = $_REQUEST ['zApplication'];
  $type_update = $_REQUEST ['type_update'];
  $datecourant = date('Y-m-d');
      
     if( $type_update == 1 ){
	 set_historique( $idProjet, $idClient, $idApplication, $datecourant,$zMatricule,0);
	   $sql_update = "UPDATE cc_sr_projet SET archivage = 0 ,date_modification='$datecourant'
           where id_projet=$idProjet";
	 }else{
	    
		   set_historique( $idProjet, $idClient, $idApplication,$datecourant,$zMatricule ,1);
	    $sql_update = "UPDATE cc_sr_projet SET archivage = 1 ,date_modification='$datecourant'
           where id_projet=$idProjet";
	 }
     
        
		$query_update = @pg_query($conn,$sql_update);
        
         if($query_update){
                 echo 'ok';

            }else{
            echo 'ko';
          
            }

   
   

?>