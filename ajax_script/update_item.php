<?php
   include("/var/www.cache/dgconn.inc");
    $IdGrille = $_REQUEST['IdGrille'];
    $ordreItem = $_REQUEST['ordreItem'];
    $libelleItem = $_REQUEST['libelleItem'];
	$libelleItem = utf8_decode(pg_escape_string( $libelleItem ));
	 $zSqlUpdate = "UPDATE cc_sr_grille SET ordre=$ordreItem, libelle_grille='$libelleItem' WHERE id_grille=$IdGrille" ;
     $queryUpdate = pg_query($conn,$zSqlUpdate) or die (pg_last_error());
	 
	  if($queryUpdate){
	      echo 1;
	  }else{
	      echo 0;
	  }
?>