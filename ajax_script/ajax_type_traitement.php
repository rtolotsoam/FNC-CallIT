<?php
 include("/var/www.cache/dgconn.inc");
 $IdTraitement = $_REQUEST['IdTraitement'];

    $zSql = "select id_categorie_grille,libelle_categorie_grille from cc_sr_categorie_grille
    where id_type_traitement= $IdTraitement  order by ordre";
          $query = pg_query($conn,$zSql) or die (pg_last_error());	 
		  $nbRow = pg_num_rows( $query );
		 $zHtml ="";
		  for($i=0;$i<$nbRow;$i++){
			   $row = pg_fetch_row($query,$i);
			   $id_categorie = $row[0];
			   $libelle_categorie = $row[1] ;

			   $zHtml .= '<option value="'.$id_categorie.'#'.$libelle_categorie.'">'.$libelle_categorie.'</option>';			
			}
			echo   $zHtml;
		 
	
	
 	
?>