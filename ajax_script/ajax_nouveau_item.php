<?php
         include("/var/www.cache/dgconn.inc");
		 
		 function test_utilisation_grille( $id_grille ){
		    global $conn;
			$sql = "SELECT * FROM cc_sr_grille_application c
			INNER JOIN cc_sr_indicateur_notation i ON c.id_grille_application=i.id_grille_application
			where c.id_grille = {$id_grille}";
			$query_grille = pg_query( $sql ) or die(pg_last_error());
			$nb_enreg = pg_num_rows( $query_grille );
			return $nb_enreg;
		 
		 }
		 $id_categorie = $_REQUEST['id_categorie'];
		 
		 $sql = "SELECT * FROM cc_sr_grille  WHERE id_categorie_grille={$id_categorie} ORDER BY ordre";
		 $query = pg_query($conn,$sql) or die (pg_last_error());
		 $nbRow = pg_num_rows( $query );
		 
		  $zHtml = "<table style=' border: 1px solid #B2C6CD;border-collapse: collapse;margin: 0 0 15px 37px;
    width: 90%;background:#FFFFFF;' border=1>
			 <thead>
				<tr style='background:#799CA6;color:#FFF;'>
				     <th  class='entete' align='center' width='6%'>Ordre</th>
				     <th  class='entete' align='center' width='88%'>Libelle</th>
					 <th  class='entete' align='center' width='6%'>Action</th>
				 </tr>
		     </thead>	
               <tbody>			 
				 ";
		              for($i = 0; $i< $nbRow ; $i++){
					    $row = pg_fetch_array($query,$i);
						
		  $zHtml .= "<tr id='tr_{$id_categorie}_{$row['id_grille']}_{$i}'>";	
		  $zHtml .= "<td style='text-align:center;'>{$row['ordre']}</td><td>{$row['libelle_grille']}</td>";	
		  $test_utilisation =  test_utilisation_grille( $row['id_grille'] );
			  
		  $zHtml .= "<td style='text-align:center;'>";
		      if( $test_utilisation == 0  ){
			     $zHtml .= "<a href='#' onclick='delete_item({$id_categorie},{$row['id_grille']},{$i})'><img width='15px' height='15px'  style='cursor:pointer' title='Supprimer' src='images/delete.png' /></a>";
			  }else{
			     $zHtml .= "<a href='#' ><img width='20px' height='20px'  style='cursor:pointer' title='grille utilis&eacute;e' src='images/interdit.png' /></a>";
			  }
			
		  
		  $zHtml .= "</td>";
		  	
		  $zHtml .= "</tr>";	
					  }
		  $zHtml .= "
		       </tbody>
		     </table>";
			 
			 echo $zHtml;


?>