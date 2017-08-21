<?php
   include("/var/www.cache/dgconn.inc");
    $IdGrille2 = $_REQUEST['Idgrille2'];
   /** $ordreGrille2 = $_REQUEST['ordregrille2'];*/
    $libelleGrille2 = $_REQUEST['libellegrille2'];
	 $libelleGrille2 = utf8_decode(pg_escape_string( $libelleGrille2 ));
	
	 $zSqlUpdate = "UPDATE cc_sr_grille SET libelle_grille='$libelleGrille2' WHERE id_grille=$IdGrille2" ;
   
	 $queryUpdate = pg_query($conn,$zSqlUpdate) or die (pg_last_error());
	 
	  if($queryUpdate){
	      echo 1;
	  }else{
	      echo 0;
	  }
?>