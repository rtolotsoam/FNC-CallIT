<?php
    include("/var/www.cache/dgconn.inc");
    $id_categorie_grille = $_REQUEST['id_categorie_grille'];
   $zSql = "SELECT id_grille,libelle_grille,ordre 
   from cc_sr_grille where id_categorie_grille=$id_categorie_grille order by ordre";
     $query = pg_query($conn,$zSql) or die (pg_last_error());	 
     $nbRow = pg_num_rows( $query );
	 
		$zHtml .="<table  class='tablesorter'  border=0 id='tab_grille2'>
	      <thead>
	        <tr>
		     <!-- <th width='10%'>Ordre</th>-->
		      <th width='60%'>Libelle</th>
		      <th width='15%'>&nbsp;</th>
	       </tr>
	     </thead>
		 <tbody>
	   ";
	   
	    for($k=0;$k<$nbRow;$k++){			
		$row = pg_fetch_array($query,$k);
		$libelleGrilleVal = str_replace("'"," ",$row['libelle_grille']);
	  $zHtml .="<tr id='row-{$row['id_grille']}'>
				  <!-- <td width='10%'><span id='span_ordre_grille2{$id_categorie_grille}_{$k}' class='span_grille2$k' >{$row['ordre']}</span><input id='cache_grille2_ordre_{$id_categorie_grille}_{$k}' type='text' size='4%' class='cache_grille2' value='{$row['ordre']}' /></td>-->
				   <td width='60%'><span id='span_libelle_grille2{$id_categorie_grille}_{$k}' class='span_grille2$k'>{$row['libelle_grille']}</span><input id='cache_grille2_libelle_{$id_categorie_grille}_{$k}' type='text'  size='84%' class='cache_grille2' value='$libelleGrilleVal' /></td>				   
                   <td align='center' width='15%'>
				   <input id='btn_edit_grille2{$id_categorie_grille}_{$k}'	 type='button' onclick='editer_grille2($k,$id_categorie_grille);' value='editer' class='btn_edit_grille' />
			       <input id='btn_modif_grille2{$id_categorie_grille}_{$k}' type='button' value='modifier' onclick='modifier_grille2({$row['id_grille']},$k,$id_categorie_grille)' class='btn_modif_grille2' />
				   </td>
				  
			</tr>";
			
			}
	  $zHtml .= "</tbody></table>";
	  echo  $zHtml;
?>