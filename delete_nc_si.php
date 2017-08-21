<?php
    include("/var/www.cache/dgconn.inc");
    if(isset($_REQUEST['delete_nc']))
    {
		$id_notation = $_REQUEST['id_notation'];
		$sql_delete = "DELETE FROM  nc_fiche WHERE fnc_id_grille_application=0 AND fnc_id_notation=".$id_notation;
		$query_delete = pg_query($conn,$sql_delete) or die(pg_last_error());
		
		if($query_delete){
		    echo 1;
		}else{
		   echo 0;
		}
	}
    else
    {
		$fnc_id = $_REQUEST['fnc_id'];
	    $id_grille_application = $_REQUEST['id_grille_application'];
	    $notation_id = $_REQUEST['notation_id'];
	    
	    $sql_delete = "DELETE FROM  nc_fiche WHERE fnc_id={$fnc_id} AND fnc_id_grille_application={$id_grille_application}";
		  
		$sql_update = "UPDATE cc_sr_indicateur_notation SET commentaire_si='' WHERE id_grille_application={$id_grille_application} AND id_notation={$notation_id}";
		$query_update = pg_query($conn,$sql_update) or die(pg_last_error());
		
		$query_delete = pg_query($conn,$sql_delete) or die(pg_last_error());
	    if(($query_delete && $query_update) || $fnc_id == 0){
		    echo 1;
		}else{
		   echo 0;
		}
	}
    

?>