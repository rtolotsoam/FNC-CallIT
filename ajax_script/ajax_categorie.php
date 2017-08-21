<?php
    include("/var/www.cache/dgconn.inc");
	
	  function test_utilisation_categorie( $id_categorie_grille ){
	       global $conn;
		   $sql   = " SELECT * FROM cc_sr_categorie_grille cg INNER JOIN  cc_sr_grille g
                   ON cg.id_categorie_grille = g.id_categorie_grille
				   INNER JOIN cc_sr_grille_application ga ON ga.id_grille=g.id_grille 
				   WHERE ";
		   $query = pg_query( $sql   ) or die(pg_last_error());
		           
	  }
    $IdTraitement = $_REQUEST['IdTraitement'];
    $zSql = "select id_categorie_grille,libelle_categorie_grille,id_classement,ordre from cc_sr_categorie_grille
    where id_type_traitement= $IdTraitement  order by ordre";
     $query = pg_query($conn,$zSql) or die (pg_last_error());	 
     $nbRow = pg_num_rows( $query );
	 
		$zHtml .="<table onload='return tester();' class='tablesorter'  border=0 id='tab_categorie'>
	      <thead>
	        <tr>
		      <!--<th width='10%'>Ordre</th>-->
		      <th width='60%'>Libelle(Pour modifier l'ordre d&eacute;placer les lignes)</th>
		      <th width='15%'>&nbsp;</th>
	       </tr>
	     </thead>
		 <tbody>
	   ";
	   
	    for($k=0;$k<$nbRow;$k++){			
		   $row = pg_fetch_array($query,$k);
		   $zCategorieGrille = utf8_encode( $row['libelle_categorie_grille'] );
		   $libelleCategorieVal = str_replace("'"," ",$row['libelle_categorie_grille']);
	       $showCategorie = "<a onclick='affiche_grille(".$row['id_categorie_grille'].",$k );' href='javascript:void(0)' title='Afficher les grilles' style='text-decoration:none;font-weight:bold;font-size:20px' id='categorie_".$row['id_categorie_grille']."' class='showIndicateurPlus'>+</a> ";
		   $sql_classement = "SELECT id_classement,libelle_classement FROM cc_sr_classement ORDER BY libelle_classement";
		   $query_classement = pg_query(  $sql_classement ) or die(pg_last_error());
		   $zHtml .="<tr class='move' id='row-{$row['id_categorie_grille']}'>
				 <!--<td width='10%'><!--<p class='up' onclick='get_up();'><img src='http://www.skilledmonster.com/wp-content/uploads/2012/07/up_arrow.png'></p>
				   <p class='down' onclick='get_down();'><img src='http://www.skilledmonster.com/wp-content/uploads/2012/07/down_arrow.png'></p>
				   <span id='span_ordre_grille_{$k}' class='span_grille$k' >{$row['ordre']}</span><input id='cache_grille_ordre_{$k}' type='text' size='13%' class='cache_grille' value='{$row['ordre']}' /></td>-->
				   <input id='champ_ordre_{$k}' type='hidden' size='13%' value='{$row['ordre']}' />
				   <input id='champ_idGrille_{$k}' type='hidden' size='13%' value='{$row['id_categorie_grille']}' />
				   <td id='td_categorie_{$k}' width='60%'>
				   <span style='float:left;margin-right:4px;'>$showCategorie</span>
				   <span id='span_libelle_grille_{$k}' class='span_grille$k'>{$zCategorieGrille}</span>
				   <input id='cache_grille_libelle_{$k}' type='text'  size='97%' class='cache_grille' value='$libelleCategorieVal' />
				   <select  class='cache_grille' id='cache_grille_classement_{$k}' style='height:20px;float:right;'>
				       <option value='0'> --Choix-- </option>";
				       for($i=0;$i<pg_num_rows( $query_classement );$i++)
					   {
					             $lg = pg_fetch_array( $query_classement, $i );
								     if( $row['id_classement']  == $lg['id_classement'] ){
									   $zHtml .= "<option selected value='{$lg['id_classement']}'>{$lg['libelle_classement']}</option>";
									 }else{
								      $zHtml .= "<option value='{$lg['id_classement']}'>{$lg['libelle_classement']}</option>";
								     }
					   }
					   
				   $zHtml .= "</select>
				   <div style='display:none;width:100%'  id='dv_".$row['id_categorie_grille']."'></div>
				   </td>				  
                   <td align='center' width='15%'>
				   <input id='btn_edit_grille_{$k}'	 type='button' onclick='editer_grille($k);' value='editer' class='btn_edit_grille' />
			       <input id='btn_modif_grille_{$k}' type='button' value='modifier' onclick='modifier_categorie_grille({$row['id_categorie_grille']},$k)' class='btn_modif_grille' />
				   </td>
				  
			      </tr>";
			
			}
	      $zHtml .= "</tbody></table>";
	  echo  utf8_decode( $zHtml );
?>