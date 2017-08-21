<?php
   include("/var/www.cache/dgconn.inc");
    $id_cat = $_REQUEST['id_categorie'];
   /** $ordreCategorie = $_REQUEST['ordreCategorie'];*/
    $libelleCategorie = $_REQUEST['libelleCategorie'];
    $id_classement = $_REQUEST['id_classement'];
	$libelleCategorie = utf8_decode(pg_escape_string( $libelleCategorie ));
	
	
 	$zSqlUpdate = "UPDATE cc_sr_categorie_grille SET  libelle_categorie_grille='$libelleCategorie' ,id_classement = $id_classement WHERE id_categorie_grille=$id_cat" ;

	$queryUpdate = pg_query($conn,$zSqlUpdate) or die (pg_last_error());
	 
	  if($queryUpdate){
	      echo 1;
	  }else{
	      echo 0;
	  }

?>