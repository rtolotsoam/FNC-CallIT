<?php

   
	function get_type_traitement(){
	   global $conn;
	 
	     $zSql = "  SELECT id_type_traitement,libelle_type_traitement FROM cc_sr_type_traitement 
		 ORDER BY libelle_type_traitement ";
		 
		
		
		 $query = pg_query($conn,$zSql) or die (pg_last_error());
		
		 
		 $nbRow = pg_num_rows( $query );
		  $zHtml ='';
		    for($i=0;$i<$nbRow;$i++){
			   $row = pg_fetch_array($query,$i);
			   //$zHtml .= "<input id='libelle_categorie' type='hidden' value='{$row['libelle_type_traitement']}' />";
			   $zHtml .= "<option value='{$row['id_type_traitement']}' >{$row['libelle_type_traitement']}</option>";			
			}
			return $zHtml;
		 
	}
	
 /**	function set_historique($id_projet){
	 global $conn;
	echo $id_projet;die;
	 $sq_insert = "INSERT INTO cc_sr_historique (id_projet,id_application,id_client,date_modification) VALUES ()";

	   $query = pg_query($conn,$sq_insert) or die (pg_last_error());
	   
	}*/
	
	
	function set_historique($id_projet,$idClient,$zApplication,$datecourant,$zMatricule,$flag){
	     global $conn;
	    
	 
	   $sq_insert = "INSERT INTO cc_sr_historique (id_projet,id_application,id_client,date_modification,matricule_modification,flag) VALUES ($id_projet,$zApplication,$idClient,'$datecourant',$zMatricule,$flag)";
            
	   $query = pg_query($conn,$sq_insert) or die (pg_last_error());
	
	}
	
	
	function get_nom_campagne( $id_campagne ){
	     global $conn;
	     $sql = "
        SELECT  nom_campagne FROM cc_sr_campagne WHERE id_campagne=$id_campagne";
		
		$query = pg_query($conn,$sql) or die (pg_last_error());
		$row = pg_fetch_row( $query );
	
		 return $row[0];
	}
	
	function get_nom_personnel( $iMatricule ){
	  global $conn;
	  $sql_pesonnel = "SELECT prenompersonnel,fonctioncourante FROM personnel WHERE matricule=$iMatricule";

	   $query = pg_query($conn,$sql_pesonnel) or die (pg_last_error());
	   $row = pg_fetch_row( $query );
	   
	     return $row[0].' - '.$row[1];
	}
	
	
	


