<?php
		include("/var/www.cache/dgconn.inc");
		$id_cat = $_REQUEST['id_categorie'];

           $sql_delete_categorie = "DELETE FROM cc_sr_categorie_grille WHERE id_categorie_grille ={$id_cat }";
	       $query_cat = pg_query($conn,$sql_delete_categorie) or die (pg_last_error());	

		   $sql_delete_item = "DELETE FROM cc_sr_grille WHERE id_categorie_grille='{$id_cat }'";
		   $query_item = pg_query($conn,$sql_delete_item) or die (pg_last_error());
		   
		    if( $query_cat && $query_item  ){
			     echo 1;
			}else{
			     echo 0;
			}
          

?>