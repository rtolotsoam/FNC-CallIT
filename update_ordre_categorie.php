<?php
 include("/var/www.cache/dgconn.inc");
  $action = $_REQUEST['action'];
  $table = $_REQUEST['table'];
  $order = $_REQUEST['order'];
  $rows_order = $_REQUEST['rows_order'];
    $rows_order = explode('&', $rows_order);
	
	    for($i=1;$i<=count($rows_order);$i++){
		      $tab2 =explode('=', $rows_order[$i-1]);
			
			  $sql = "UPDATE  cc_sr_categorie_grille SET ordre=".$i." WHERE id_categorie_grille=".$tab2[1];
			  $result_update = pg_query($conn,$sql) or die (pg_last_error()); 
		}
 
 
?>