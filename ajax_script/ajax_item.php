<?php
    include("/var/www.cache/dgconn.inc");
    $idCategorie = $_REQUEST['idCategorie'];
    $libelleCategorie = $_REQUEST['libelleCategorie'];
    $libelleCategorieVal = str_replace("'"," ",$libelleCategorie);

	   //recuperation de l'ordre du categorie
	   $zSqlOrdreCategorie = "select ordre from cc_sr_categorie_grille where id_categorie_grille =$idCategorie";
	   $queryOrdreCategorie = pg_query($conn,$zSqlOrdreCategorie) or die (pg_last_error());
	   $rowOrdre = pg_fetch_row($queryOrdreCategorie,0);
	
	   
      $zSql = "select id_grille,libelle_grille,ordre from cc_sr_grille
    where id_categorie_grille= $idCategorie  order by ordre";
	
	$query = pg_query($conn,$zSql) or die (pg_last_error());	 
    $nbRow = pg_num_rows( $query );
	$zHtml = "<h3>Categorie</h3>";
	$zHtml .="<table  class='tablesorter'  border=0 id='tab_categorie'>
	      <thead>
	        <tr>
		      <th width='10%'>Ordre</th>
		      <th width='75%'>Libelle</th>
		      <th width='15%'>&nbsp;</th>
	       </tr>
	     </thead>
	   ";
	$zHtml .="<tr>
		       <td width='10%'><span id='span_ordre_cat' class='span_categorie'>$rowOrdre[0]</span><input  id='val_ordre_categorie' type='text' size='13%' class='cache_categorie' value='$rowOrdre[0]' /></td>
		       <td width='75%'><span id='span_libelle_cat' class='span_categorie'>$libelleCategorie</span><input id='val_libelle_categorie' type='text' size='146%' class='cache_categorie' value='$libelleCategorieVal' /></td>
		       <td align='center' width='15%'>
			   <input type='button' value='editer' onclick='editer_categorie();' class='btn_edit' />
			   <input type='button' value='modifier' onclick='modifier_categorie( $idCategorie );' class='btn_modif_cat' />
			   </td>
		  </tr>";	 
	$zHtml .="</table>";
	$zHtml .= "<h3>Items</h3>";
	$zHtml .="<table class='tablesorter' id='tab_item'>
	  <thead>
	    <tr>
		  <th>Ordre</th><th>Libelle</th><th>&nbsp;</th>
	    </tr>
	  </thead>
 	";
	            for($i=0;$i<$nbRow;$i++){
				
				  $row = pg_fetch_array($query,$i);
				  $libelleGrilleVal = str_replace("'"," ",$row['libelle_grille']);
				  $libelleGrille  = utf8_encode( $row['libelle_grille'] );
				  $showIndicateur = "<a onclick='affiche_indicateur(".$row['id_grille']." );' href='javascript:void(0)' title='Indicateurs' style='text-decoration:none;font-weight:bold;font-size:20px' id='item_".$row['id_grille']."' class='showIndicateurPlus'>+</a> ";
				  $zHtml .= "<tr>
				                 <td width='10%'><span id='span_ordre$i' class='span_item$i' >{$row['ordre']}</span><input id='cache_item_ordre$i' type='text' size='13%' class='cache_item' value='{$row['ordre']}' /></td>
				                 <td id='td_".$row['id_grille']."'  width='75%'>
								 <span style='float:left;margin-right:4px;'>$showIndicateur</span>
								 <span id='span_libelle$i' class='span_item$i'>{$libelleGrille}</span><input id='cache_item_libelle$i' type='text'  size='146%' class='cache_item' value='$libelleGrilleVal' />
															
									<div style='display:none;width:100%'  id='dv_".$row['id_grille']."'></div>
								
								 </td>
								 <td align='center' width='15%'>
								    <input id='btn_edit_item$i'	 type='button' onclick='editer_item($i);' value='editer' class='btn_edit_item' />
								    <input id='btn_modif_item$i' type='button' value='modifier' onclick='modifier_item({$row['id_grille']},$i)' class='btn_modif_item' />
								 </td>
				            </tr>";
				  /** $zHtml .= "<tr style='display:none;' id='tr_".$row['id_grille']."' >			                    
								<td colspan='6' style='background-color:#B2C6CD' >
									<div style='display:none;'  id='dv_".$row['id_grille']."'></div>
								</td>
							</tr>";
				  */
				}
	$zHtml .= "</table>";
	echo   utf8_decode($zHtml);
?>