<?php
   include("/var/www.cache/dgconn.inc");
      
    
     $id_categorie = $_REQUEST['id_cat'];
     $libelle_grille = $_REQUEST['libelle_item'];
     $libelle_grille = utf8_decode( pg_escape_string( $libelle_grille ) );
   
	$sql = "SELECT * FROM cc_sr_grille WHERE id_categorie_grille='{$id_categorie}' " ;
	$query = pg_query($conn,$sql) or die (pg_last_error());
	$nb_ligne = pg_num_rows( $query  );

		    $sql_max_grille = "SELECT max(id_grille) FROM cc_sr_grille " ;
			$queryMaxId = pg_query($conn,$sql_max_grille) or die (pg_last_error());
			$MaxId = pg_fetch_row( $queryMaxId );
			$id_grille = $MaxId[0]+1;
		
		    $sql_insert =  "INSERT INTO cc_sr_grille (id_grille,libelle_grille,id_categorie_grille,ordre)" ;
			$sql_insert .=" VALUES ($id_grille,'$libelle_grille',$id_categorie";
			     if( $nb_ligne==0 ){
				   $sql_insert .= ",1)";
				 }
				 else{
				    $sql_max_ordre = "SELECT MAX(ordre) FROM cc_sr_grille WHERE id_categorie_grille='{$id_categorie}' ";
					$query_maxe_ordre = pg_query($conn,$sql_max_ordre) or die (pg_last_error());
					$max_ordre = pg_fetch_row( $query_maxe_ordre );
					$new_ordre = $max_ordre[0]+1;
					$sql_insert .= ",{$new_ordre})";
				 }
			
			$query_insert = pg_query($conn,$sql_insert) or die (pg_last_error());
			  
		
		 
	    
	
	     


?>