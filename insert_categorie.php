<?php
	include("/var/www.cache/dgconn.inc");
    $libelle_categorie  = utf8_decode($_REQUEST['libelle_categorie']);
    $id_type_traitement = $_REQUEST['id_type_traitement'];
    $ordreCategorie     = $_REQUEST['ordre'];
    $id_classement      = $_REQUEST['id_classement'];
    $libelle_categorie  = pg_escape_string($libelle_categorie  );
	
	$sqlMaxId   = "SELECT id_categorie_grille FROM cc_sr_categorie_grille  ORDER BY id_categorie_grille DESC LIMIT 1" ;
	$queryMaxId = pg_query($conn,$sqlMaxId) or die (pg_last_error());
	$MaxId      = pg_fetch_row( $queryMaxId );
	
	$id_categorie_grille = $MaxId[0]+1;
	$ordre =  $ordreCategorie+1;
	 
	$zSqlInsert = "INSERT INTO cc_sr_categorie_grille (id_categorie_grille,libelle_categorie_grille,id_type_traitement,ordre,id_classement)
	  VALUES ($id_categorie_grille,'$libelle_categorie',$id_type_traitement,$ordre,$id_classement) " ;
	  
	$queryInsert = pg_query($conn,$zSqlInsert) or die (pg_last_error());
	 
	if( $queryInsert ){
		echo 1;
	}else{
		echo 0;
	}
?>