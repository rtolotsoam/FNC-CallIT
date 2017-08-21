<?php
   include("/var/www.cache/dgconn.inc");
    $IdIndicateur      = $_REQUEST['IdIndicateur'];
    $ordreIndicateur   = $_REQUEST['ordreIndicateur'];
    $libelleIndicateur = $_REQUEST['libelleIndicateur'];
    $pointIndicateur   = $_REQUEST['pointIndicateur'];
	$libelleIndicateur = utf8_decode(pg_escape_string( $libelleIndicateur ));
	$zSqlUpdate = "UPDATE cc_sr_indicateur SET ordre=$ordreIndicateur, libelle_indicateur='$libelleIndicateur',
	point=$pointIndicateur WHERE id_indicateur=$IdIndicateur" ;
	
     $queryUpdate = pg_query($conn,$zSqlUpdate) or die (pg_last_error());
	 
	  if($queryUpdate){
	      echo 1;
	  }else{
	      echo 0;
	  }
?>