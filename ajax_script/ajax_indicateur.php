<?php
    include("/var/www.cache/dgconn.inc");
    $id_item = $_REQUEST['id_item'];
   $zSql = "SELECT id_indicateur,libelle_indicateur,ordre ,point
   from cc_sr_indicateur where id_grille=$id_item order by ordre";
     $query = pg_query($conn,$zSql) or die (pg_last_error());	 
     $nbRow = pg_num_rows( $query );
	 
		$zHtml .="<table  class='tablesorter'  border=0 id='tab_indicateur'>
	      <thead>
	        <tr>
		      <th width='10%'>Ordre</th>
		      <th width='60%'>Libelle</th>
		      <th width='15%'>point</th>
		      <th width='15%'>&nbsp;</th>
	       </tr>
	     </thead>
		 <tbody>
	   ";
	   
	    for($k=0;$k<$nbRow;$k++){			
		$row = pg_fetch_array($query,$k);
		$libelleIndicateurVal = str_replace("'"," ",$row['libelle_indicateur']);
	  $zHtml .="<tr>
				   <td width='10%'><span id='span_ordre_indicateur{$id_item}_{$k}' class='span_indicateur$k' >{$row['ordre']}</span><input id='cache_indicateur_ordre_{$id_item}_{$k}' type='text' size='4%' class='cache_indicateur' value='{$row['ordre']}' /></td>
				   <td width='60%'><span id='span_libelle_indicateur{$id_item}_{$k}' class='span_indicateur$k'>{$row['libelle_indicateur']}</span><input id='cache_indicateur_libelle_{$id_item}_{$k}' type='text'  size='84%' class='cache_indicateur' value='$libelleIndicateurVal' /></td>
				   <td width='15%'><span id='span_point_indicateur{$id_item}_{$k}' class='span_indicateur$k'>{$row['point']}</span><input id='cache_indicateur_point_{$id_item}_{$k}' type='text'  size='10%' class='cache_indicateur' value='{$row['point']}' /></td>
                   <td align='center' width='15%'>
				   <input id='btn_edit_indicateur{$id_item}_{$k}'	 type='button' onclick='editer_indicateur($k,$id_item);' value='editer' class='btn_edit_indicateur' />
			       <input id='btn_modif_indicateur{$id_item}_{$k}' type='button' value='modifier' onclick='modifier_indicateur({$row['id_indicateur']},$k,$id_item)' class='btn_modif_indicateur' />
				   </td>
				  
			</tr>";
			
			}
	  $zHtml .= "</tbody></table>";
	  echo  $zHtml;
?>