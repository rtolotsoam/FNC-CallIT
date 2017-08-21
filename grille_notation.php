<?php
include("/var/www.cache/dgconn.inc");
//include('function_dynamique.php');
      
$id_projet = $_REQUEST['id_projet'];
$id_client = $_REQUEST['id_client'];
$id_application = $_REQUEST['id_application'];
$id_notation = $_REQUEST['id_notation'];
/*$id_projet =51;
$id_client=599;
$id_application=408;
$id_notation = 1737;*/
if(isset($id_projet) && isset($id_client) && isset($id_application) && isset($id_notation))
{
	echo fetchAllResults($id_projet, $id_client, $id_application, $id_notation);
}


function fetchAllResults($id_projet, $id_client, $id_application, $id_notation) {
$result_select = fetchAll($id_projet,$id_client,$id_application,$id_notation);
$tableauBord = array();
$Nb = pg_num_rows(  $result_select );
$idKTgory = 0;
   for($k = 0 ; $k < $Nb ; $k++) {
     $row = pg_fetch_array($result_select,$k);
     
	 if (!isset($tableauBord[$row['section']])){
		$tableauBord[$row['section']] = array();
	 }
	 
	 if (!isset($tableauBord[$row['section']][$row['id_classement']])){
	 $tableauBord[$row['section']][$row['id_classement']] = array();
	 $tableauBord[$row['section']][$row['id_classement']]['libelle'] = $row['libelle_classement'];
	 $tableauBord[$row['section']][$row['id_classement']]['ponderation_classement'] = $row['ponderation_classement']; // Njiva
	 $tableauBord[$row['section']][$row['id_classement']]['ponderation_section'] = $row['ponderation_section']; // Njiva
	 $tableauBord[$row['section']][$row['id_classement']]['ktgory'] = array(); 
	 }
	 
	 if (!isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']])) {
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['libelle'] = $row['libelle_categorie_grille'];
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'] = array(); 
	 }
	
	 if (!isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']])){
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['libelle'] = $row['libelle_grille'];
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['commentaire'] = $row['commentaire']; // Njiva
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['point'] = $row['point']; // Njiva
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['commentaire_si'] = $row['commentaire_si']; // Njiva
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'] = array();	
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'] = array();
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['eliminatoire'] = $row['flag_eliminatoire'];	
	 }
	
	 if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'])) {
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'][$row['note']] = $row['libelle_description'];			
	 }
	
	 if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'])) {
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'][$row['libelle_description']] = $row['ponderation'];			
	 }
		
   }
/*print '<pre>';
     print_r($tableauBord);
     print '</pre>';*/

$zHtml = '<table  border="1" style="border-collapse:collapse;font-family: arial;font-size: 10px;">';
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
<!--<th>Situation inacceptable</th>-->
<th>Commentaire Situation inacceptable</th>

</tr>
</thead><tbody>';
$counter1 = 0;
$nbeEliminatoire = 0; 
$test = 0; // Njiva
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
	
	/************** Njiva ***************/
	$total_section = 0;
	$sum_ponderation_classement = 0;
	/************************************/
	
	$zHtml .= '<tr class="kt"> <td rowspan="' . $rowspanSection .'">'.  $sectionkey.'</td></tr>';
	foreach($section as $key=>$val){
		
		/***** Njiva **************/
		$sum_ponderation = 0;
		$total_classement = 0;
		/************************/
		
		foreach($val['ktgory'] as $key=>$tab){
			$rowSpanKt = 0;
		
			foreach($tab['item'] as $item) {
			$rowSpanKt += count($item['note']);
			}
			$zHtml .= '<tr class="kt"><td rowspan="' .$rowSpanKt . '">' .$tab['libelle'] . '</td>';          
			$counter2 = 0;
			foreach( $tab['item'] as $id_grille=>$item  ){
				$zHtml .= '<td rowspan="' . count($item['note']) . '">' .$item['libelle'] . '</td>'; 
				$commentaire = isset($item['commentaire']) ? $item['commentaire'] : ''; // Njiva
				$point = isset($item['point']) ? $item['point'] : -1; // Njiva
				$commentaire_si = isset($item['commentaire_si']) ? $item['commentaire_si'] : ''; // Njiva
				$counter3=0;
				foreach ($item['note'] as $note_=>$description){
					if ($counter3 == 0) {
					    
					$zHtml .= '<td style="text-align:center;">' . $note_ . '</td>'; // Note
					$zHtml .= '<td>'.$description.'</td>'; // Description
					
								
					/*********/
					
					if( $counter3==0){
					
					     $zHtml .= '<td rowspan="' . count($item['note']) . '">
						 <select id="note_'.$id_grille.'_'.$counter2.'" style="width:50px">
						    <option  value="-1"></option>';
							foreach ($item['note'] as $note_item=>$valeur){
								/******* Njiva ***********/
								if($note_item == $point) {
									$sel = 'selected="selected"';
								}
								else 
								{
									$sel = '';
								}
								/*************************/
							    $zHtml .= '<option value="'.$note_item.'" '.$sel.'>'.$note_item.'</option>';  //Point (Note)
							}
						$zHtml .= '</select>										 
						 </td>';
						 
						/*************** Njiva **************/
						$ponderation = $item['ponderation'][$description];
						$sum_ponderation += $ponderation;
						/************************************/
						 
					    $zHtml .= '<td rowspan="' . count($item['note']) . '"><input readonly id="base_" type="text" value="' .$item['ponderation'][$description] .'" style="text-align:center; width:50px;" /></td>'; // Base
					    $zHtml .= '<td rowspan="' . count($item['note']) . '"><textarea style="width:100%;resize:none;" rows="'.count($item['note']).'">'.$commentaire.'</textarea></td>'; // Njiva
					    if ($item['eliminatoire'] == 1 || $commentaire_si != '') {          // Njiva
							//$zHtml .= '<td style="background-color:red;" rowspan="' . count($item['note']) . '"></td>';
							$style = 'style="background-color:red;width:100%;resize:none;" rows="'.count($item['note']).'"'; // Njiva
							$nbeEliminatoire++;
							if($item['eliminatoire'] == 1)
							{
								$nbReelEliminatoire ++;
								if($point == 0 || $point == -1)
								{
									$total_general = 0;
									$test = 1;
								}
							}
						} else {
							//$zHtml .= '<td style="background-color:white;" rowspan="' . count($item['note']) . '"></td>';
							$style = 'style="width:100%;resize:none;" rows="'.count($item['note']).'"'; // Njiva
						}
						
					    $zHtml .= '<td  style="text-align:center;" rowspan="' . count($item['note']) . '"><textarea '.$style.'>'.$commentaire_si.'</textarea></td>';
					    
					    $total_classement += $point * $ponderation; // Njiva
					}
					/*********/
					
					$zHtml .= '</tr>';
					} else {
					$zHtml .= '<tr class="crit"><td style="text-align:center;">' . $note_ . '</td><td>'.$description.'</td></tr>';
					}
					$counter3 ++;
				}
				$counter2 ++;
			}
			$counter1++;
		}
		/*********** Njiva *********************/
		//echo '**********'.$total_classement.'**'.$sum_ponderation.'</br>';
		$total_classement = number_format($total_classement/$sum_ponderation,2);
		/*******************************************/
		
		/******************** Classement ***********************/ // Njiva
		$zHtml .= '<tr style="background-color:#6EA2F7">
		<td style="font-weight:bold;font-size:11px;" colspan="4">' . $val['libelle'] .'</td>
		<td>
		<input type="text" style="text-align:center; width:50px;" value="'.$total_classement.'">
		</td>
		<td>
		<input type="text" value="'.$val['ponderation_classement'].'" style="text-align:center; width:50px;">
		</td>
		<td colspan="3"></td>
		</tr>';
		$ponderation_section = $val['ponderation_section']; // Njiva
		/*******************************************************/
		
		$total_section += $total_classement * $val['ponderation_classement'];
		$sum_ponderation_classement += $val['ponderation_classement'];
	}
	/******************** Section (FOND / FORME) ***********************/
	$totalS = number_format($total_section / $sum_ponderation_classement,2);
	$zHtml .= '<tr style="background-color:#0140AF">
	<td colspan="4" style="font-weight:bold;font-size:11px;color:#FFFFFF;text-align:center;">TOTAL '. $sectionkey .'</td>
	<td>
	<input type="text" style="text-align:center; width:50px;" value="'.$totalS.'">
	</td>
	<td>
	<input type="text" style="text-align:center; width:50px;" value="'.$ponderation_section.'">
	</td>
	<td colspan="3"></td>
	</tr>';					 
	/*******************************************************************/
	$sum_general += $totalS * $ponderation_section;
	$sum_ponderation_section += $ponderation_section;
}
$zHtml .= '<tr style="background-color:#CCCCCC">
<td colspan="5" style="font-weight:bold;font-size:11px;text-align:center;">TOTAL GENERAL</td>
<td>';
if($test == 0)
{
	$total_general = number_format($sum_general / $sum_ponderation_section,2);
}
$zHtml .= '<input type="text" style="text-align:center; width:50px;" value="'.$total_general.'">';
$zHtml .= '</td>
<td colspan="4" style="font-weight:bold;font-size:12px;text-align:center;">Insuffisant</td>
</tr>
<tr style="background-color:#CCCCCC">
<td colspan="5" style="font-weight:bold;font-size:11px;color:#FFFFFF;text-align:center;"></td>
<td>
<input type="text" style="text-align:center; width:50px;" value="70">
</td>
<td colspan="4" style="background-color:red;font-weight:bold;font-size:12px;text-align:center;">' .$nbReelEliminatoire.'**'.$nbeEliminatoire. ' Situations inacceptables</td>
</tr>';				

$zHtml .= '</tbody></table>';
$zHtml .= '<div><input type="button" value="Enregistrer" title="Enregistrement" /></div>';
return $zHtml;	
}
?>