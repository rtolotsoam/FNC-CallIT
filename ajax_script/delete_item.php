<?php
		include("/var/www.cache/dgconn.inc");
		$id_cat = $_REQUEST['id_categorie'];
		$id_item = $_REQUEST['id_item'];

        	

		   $sql_delete_item = "DELETE FROM cc_sr_grille WHERE id_grille='{$id_item}'  AND id_categorie_grille='{$id_cat}'";
		   $query_item = pg_query($conn,$sql_delete_item) or die (pg_last_error());
		   
		    if( $query_item  ){
			     echo 1;
			}else{
			     echo 0;
			}
          

?>