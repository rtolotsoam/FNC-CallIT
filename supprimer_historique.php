<?php
 $id_historique = $_REQUEST['id_historique'];
 include("/var/www.cache/dgconn.inc");
 
        $sql_supprimer = "DELETE FROM cc_sr_historique WHERE id_historique = $id_historique ";
	    $query = pg_query($conn,$sql_supprimer) or die (pg_last_error());
		
		 if($query){
		     echo 1;
		 }
?>