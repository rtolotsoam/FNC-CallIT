<?php
      include("/var/www.cache/dgconn.inc");
	
	  $id_projet = $_REQUEST['id_projet'];
      $id_client = $_REQUEST['id_client'];
      $id_application = $_REQUEST['id_application'];
	  $id_projet =51;
	  $id_client=599;
	  $id_application=408;
	  

	     $sql_select = "SELECT cg.id_categorie_grille,cg.libelle_categorie_grille,g.id_grille,g.libelle_grille,gd.note,
		 gd.libelle_description,ga.flag_is,ga.flag_eliminatoire,c.id_classement,c.libelle_classement,c.section,ga.ponderation
,ga.flag_eliminatoire
		 FROM  cc_sr_grille_application ga
inner join  cc_sr_grille g ON g.id_grille=ga.id_grille
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application
inner join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement
inner join cc_sr_classement c on c.id_classement=cg.id_classement
where cg.id_type_traitement=1
and ga.id_projet=51
and ga.id_client=599
and ga.id_application=408
order by c.section,c.id_classement,cg.id_categorie_grille ASC,g.id_grille ASC,gd.note DESC";

      $query_select  = pg_query( $sql_select ) or die(pg_last_error());
	  $tableauBord = array();
	  $Nb = pg_num_rows(  $query_select );
		$idKTgory = 0;
	       for($k = 0 ; $k < $Nb ; $k++) {
		         $row = pg_fetch_array($query_select,$k);
				 if (!isset($tableauBord[$row['section']])){
					$tableauBord[$row['section']] = array();
				 }
				 if (!isset($tableauBord[$row['section']][$row['id_classement']])){
				 $tableauBord[$row['section']][$row['id_classement']] = array();
				 $tableauBord[$row['section']][$row['id_classement']]['libelle'] = $row['libelle_classement'];
				 $tableauBord[$row['section']][$row['id_classement']]['ktgory'] = array(); 
				 }
				 if (!isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']])) {
					$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['libelle'] = $row['libelle_categorie_grille'];
					$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'] = array(); 
				}
				if (!isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']])){
					$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['libelle'] = $row['libelle_grille'];
					$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'] = array();	
					$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'] = array();
					$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['eliminatoire'] = $row['flag_eliminatoire'];
					
				}
				if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'])) {
					$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'][$row['libelle_description']] = $row['note'];
					
									
				}
				
				if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'])) {
					$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'][$row['libelle_description']] = $row['ponderation'];
					
									
				}
				
				
				
				
		   }
    print '<pre>';
    //print_r($tableauBord);
    print '</pre>';
	$zHtml ='<style type="text/css">
			#info tr td {
			width:200px;
			}
			#info {
			font-size:10px;
			font-family:Arial;
			}
			</style>';
	
	$zHtml .= '<table border="1" style="border-collapse:collapse;" id="info">
<tr>
<td>Mle :</td><td></td><td>Date de l\'&eacute;valuation :</td><td></td>	
</tr>
<tr>		
<td>Pr&eacute;nom :</td><td></td>			<td>CLIENT:</td><td>BLT</td>
</tr>
<tr>
<td></td><td></td><td>N° de dossier :</td><td>21321321</td>
</tr>
<tr>
<td></td><td></td><td>N° de la commande :</td><td></td>	
</tr>
<tr>
<td>nb eval</td><td>2</td>
</tr>
<tr>
<td>note/10</td><td>2,4</td><td>note/10</td><td>0,0</td>
</tr>
<tr>
<td>note/100</td><td>24,2</td><td>note/100</td><td>0,0</td>
</tr>
<tr>
<td>IS4</td><td>50%</td><td>IS4</td><td>0</td>
</tr>
<tr>
<td>IS5</td><td>100%</td><td>IS5</td><td>1</td>
</tr>
<tr>
<td>IS6</td><td>50%</td><td>IS6</td><td>0</td>
</tr>
<tr>
				<td>&nbsp;&nbsp;</td>
</tr>
<tr>			
<td>accueil</td><td>1</td><td>accueil</td><td>1</td>
</tr>
<tr>
<td>diagnostic</td><td>0</td><td>diagnostic</td><td>0</td>
</tr>
<tr>
<td>traitement de la demande</td><td>2</td><td>traitement de la demande</td><td>2</td>
</tr>
<tr>
<td>conclusion</td><td>0</td><td>conclusion</td><td>0</td>
</tr>
<tr>
<td>ambiance g&eacute;n&eacute;rale</td><td>0</td><td>ambiance g&eacute;n&eacute;rale</td><td>0</td>
</tr>
</table>

<table style="padding-bottom:20px;width:100%">
<tr>
<td width="55%"></td>
<td><select>
<option>Nouveau</option>
<option>Com 1</option>
<option>Com 2</option>
<option>Com 3</option>
<option>Com 4</option>
</select></td>
<td width="30%"></td>
</tr>
</table>';

$zHtml .= '<table  border="1" style="border-collapse:collapse;font-family: arial;font-size: 10px;">';
$zHtml .= '<thead>
<tr style="font-size:11px; font-weight:bold;" class="head">
<th></th>
<th>Categorie</th>
<th>Crit&egrave;re</th>
<th>Note</th>
<th>Description</th>
<th>Point</th>
<th>Base</th>
<th>Commentaires</th>
<th>Situation inacceptable</th>
<th>Commentaire Situation inacceptable</th>

</tr>
</thead><tbody>';
                     $counter1 = 0;
					 $nbeEliminatoire = 0; 
				foreach($tableauBord as $sectionkey=>$section){
					$rowspanSection = 0;
					foreach($section as $key=>$val){
					$rowspanSection++;
					   foreach($val['ktgory'] as $key=>$tab){						
							foreach($tab['item'] as $item) {
								$rowspanSection += count($item['note']);
							}
						}
					 }
					 
					$rowspanSection +=2; 
				
					$zHtml .= '<tr class="kt"> <td rowspan="' . $rowspanSection .'">'.  $sectionkey.'</td></tr>';
                     foreach($section as $key=>$val){
					   foreach($val['ktgory'] as $key=>$tab){
						$rowSpanKt = 0;
						
					 foreach($tab['item'] as $item) {
						$rowSpanKt += count($item['note']);
					 }
					     $zHtml .= '<tr class="kt"><td rowspan="' .$rowSpanKt . '">' .$tab['libelle'] . '</td>';          
								   $counter2 = 0;
								   foreach( $tab['item'] as $id_grille=>$item  ){
								    $zHtml .= '<td rowspan="' . count($item['note']) . '">' .$item['libelle'] . '</td>'; 
										$counter3=0;
										foreach ($item['note'] as $key=>$note){
										if ($key == 0) {
										    
										$zHtml .= '<td style="text-align:center;">' . $note . '</td><td>'.$key.'</td>';
										
										
										/*********/
										
										if( $counter3==0){
										
										     $zHtml .= '<td rowspan="' . count($item['note']) . '">
											 <select id="note_'.$id_grille.'_'.$counter2.'">
											    <option  value="-1"> --Choix--</option>';
												foreach ($item['note'] as $key=>$note){
												     $zHtml .= '<option value="'.$note.'">'.$note.'</option>';
												}
											$zHtml .= '</select>										 
											 </td>';
										     $zHtml .= '<td rowspan="' . count($item['note']) . '"><input readonly id="base_" type="text" value="' .$item['ponderation'][$key] .'" style="text-align:center; width:50px;" /></td>';
										     $zHtml .= '<td rowspan="' . count($item['note']) . '"><textarea></textarea></td>';
										    if ($item['eliminatoire'] == 1) {
												$zHtml .= '<td style="background-color:red;" rowspan="' . count($item['note']) . '"></td>';
												$nbeEliminatoire++;
											} else {
												$zHtml .= '<td style="background-color:white;" rowspan="' . count($item['note']) . '"></td>';
											}
											
										     $zHtml .= '<td  style="text-align:center;" rowspan="' . count($item['note']) . '"><textarea></textarea></td>';
										}
										/*********/
										
										$zHtml .= '</tr>';
										} else {
										$zHtml .= '<tr class="crit"><td style="text-align:center;">' . $note . '</td><td>'.$key.'</td></tr>';
										}
										$counter3 ++;
										}
										$counter2 ++;
								   }
								 $counter1++;  
					 }
					 
					 $zHtml .= '<tr style="background-color:#6EA2F7">
<td style="font-weight:bold;font-size:11px;" colspan="4"> ' . $val['libelle'] .'</td>
<td>
<input type="text" style="text-align:center; width:50px;">
</td>
<td>
<input type="text" value="" style="text-align:center; width:50px;">
</td>
<td colspan="3"></td>
</tr>'

;
					 
					 }
$zHtml .= '<tr style="background-color:#0140AF">
<td colspan="4" style="font-weight:bold;font-size:11px;color:#FFFFFF;text-align:center;">TOTAL '. $sectionkey .'</td>
<td>
<input type="text" style="text-align:center; width:50px;">
</td>
<td>
<input type="text" style="text-align:center; width:50px;" value="">
</td>
<td colspan="3"></td>
</tr>';					 
				}
$zHtml .= '<tr style="background-color:#CCCCCC">
<td colspan="5" style="font-weight:bold;font-size:11px;text-align:center;">TOTAL GENERAL</td>
<td>
<input type="text" style="text-align:center; width:50px;" value="0">
</td>
<td colspan="4" style="font-weight:bold;font-size:12px;text-align:center;">Insuffisant</td>
</tr>
<tr style="background-color:#CCCCCC">
<td colspan="5" style="font-weight:bold;font-size:11px;color:#FFFFFF;text-align:center;"></td>
<td>
<input type="text" style="text-align:center; width:50px;" value="70">
</td>
<td colspan="4" style="background-color:red;font-weight:bold;font-size:12px;text-align:center;">' . $nbeEliminatoire. ' Situations inacceptables</td>
</tr>';				
				
$zHtml .= '</tbody></table>';
		echo 	$zHtml;		 
					 
?>