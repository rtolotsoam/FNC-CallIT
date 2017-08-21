
<?php
session_start();
include('function_synthese_dynamique.php');
include('function_dynamique.php');

/*$id_projet = $_REQUEST['id_projet'];
$id_client = $_REQUEST['id_client'];
$id_application = $_REQUEST['id_application'];
$id_type_traitement = $_REQUEST['id_type_traitement'];
$date_debut = $_REQUEST['date_debut_notation'];
$date_fin = $_REQUEST['date_fin_notation'];
$evaluateur = $_REQUEST['matricule_evaluateur'];*/

if(isset($_REQUEST['affiche']))
{
	$id_projet= isset($_REQUEST['id_projet_recap']) ? $_REQUEST['id_projet_recap'] : 0;
	$id_client= isset($_REQUEST['id_client_recap']) ? $_REQUEST['id_client_recap'] : 0;
	$id_application= isset($_REQUEST['id_application_recap']) ? $_REQUEST['id_application_recap'] : 0;
	$id_type_traitement= isset($_REQUEST['id_type_traitement_recap']) ? $_REQUEST['id_type_traitement_recap'] : 0;
	$matricule_tlc= isset($_REQUEST['id_tlc_recap']) ? $_REQUEST['id_tlc_recap'] : 0;
	$matricule_auditeur= isset($_REQUEST['matricule_auditeur_recap']) ? $_REQUEST['matricule_auditeur_recap'] : 0;
	$id_type_appel= isset($_REQUEST['id_type_appel_recap']) ? $_REQUEST['id_type_appel_recap'] : 0;
	$date_deb_notation= isset($_REQUEST['date_deb_notation_recap']) ? $_REQUEST['date_deb_notation_recap'] : '';
	$date_fin_notation= isset($_REQUEST['date_fin_notation_recap']) ? $_REQUEST['date_fin_notation_recap'] : '';
	$id_note = isset($_REQUEST['id_note']) ? $_REQUEST['id_note'] : '';
	$id_note_1 = isset($_REQUEST['id_note_1']) ? $_REQUEST['id_note_1'] : 0;
	$id_note_2 = isset($_REQUEST['id_note_2']) ? $_REQUEST['id_note_2'] : 0;
	if($date_deb_notation != '')
	{
		$dat = explode('/',$date_deb_notation);
		$date_deb_notation = $dat['2'].'-'.$dat['1'].'-'.$dat['0'];
	}
	if($date_fin_notation != '')
	{
		$dat = explode('/',$date_fin_notation);
		$date_fin_notation = $dat['2'].'-'.$dat['1'].'-'.$dat['0'];
	}
}
else
{
	echo utf8_encode("Aucune donnée n'est reçue");
	exit;
}

$tab_matricule = array();
$result = fetchAllTLCClient($id_projet,$id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc,$id_type_appel);
while($res = pg_fetch_array($result))
{
	$tab_matricule[] = $res['matricule'];
}

$tableauBord = setTableauSynthese($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc,$id_type_appel);

$tableauBord_title = setTableauSynthese($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,0,0,$id_type_appel);

$result1 = fetchAllTLCNotation($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc,$id_type_appel);
$nb_row = pg_num_rows( $result1 );
$array_test = array();
$somme_total_general = 0;
$matricule_next = 0;
for ($i=0;$i<$nb_row;$i++){
	 
	$lg = pg_fetch_array( $result1 , $i );
	if($i != $nb_row -1) $lg_next = pg_fetch_array( $result1 , $i+1 );
			
	$id_notation =  $lg['id_notation'];
	$_matricule =  $lg['matricule'];
	if($i != $nb_row -1) $matricule_next =  $lg_next['matricule'];
	
	if(empty($array_test[$_matricule]))
	{
		$array_test[$_matricule] = 1;
	}
	if(($_matricule == $matricule_next) && ($i != $nb_row -1))
	{
		$array_test[$_matricule] += 1;
	}
	
	//$str = calculTotalGeneral($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement);
	//$table_valeur = explode('||',$str); 
	$valnote = $lg['note'];
	if (!isset( $tableauBord[ $_matricule ] )){
	$tableauBord[ $_matricule ] = array();
	}
	if(isset($tableauBord[$_matricule]['note']))
	{
		//$tableauBord[$_matricule]['note'] += (float) $table_valeur[0];
		$tableauBord[$_matricule]['note'] += $valnote;
	}
	else
	{
		//$tableauBord[$_matricule]['note'] = (float) $table_valeur[0];
		$tableauBord[$_matricule]['note'] = $valnote;
	}
	//$tableauBord[$_matricule]['nbEval'] = $array_test[$_matricule] + 1;
	$tableauBord[$_matricule]['nbEval'] = $array_test[$_matricule];
	
	//echo $tableauBord[$_matricule]['note'].'***'.$tableauBord[$_matricule]['nbEval'].'<br>';		  
}
//echo json_encode($tableauBord);
/**
* 
* @var **********************T HEAD******************************************
* 
*/
$zHtml = '';

/*
$zHtml .= '
<table class="table_by_tlc" id="table_by_tlc_">
<thead>
	<tr>';
	$zHtml .= '<th class="class_matricule">Matricule</th>';
	$zHtml .= '<th class="class_matricule">CC</th>';
$zHtml .= '</tr></thead>';
$zHtml .= '<tbody>';  
foreach($tab_matricule as $tab)
{
	$zHtml .= '<tr>';

		$nb_eval = $tableauBord[$tab]['nbEval'];
		$note = number_format($tableauBord[$tab]['note'] / $nb_eval ,2);
		$prenom_tlc = get_prenom_personnel( $tab );
		$zHtml .= "<td class='class_tlc'>".$tab."</td>
		<td class='class_tlc'>".$prenom_tlc."</td>";
}
$zHtml .= '</tbody>';
$zHtml .= '</table>';
*/
//------------------------------------------------------------------------------------//
function filtreNote($matr,$note,$id_note,$id_note_1,$id_note_2)
{
	if($id_note != 0)
	{
		if($id_note == 1) // Egal à
		{
			if($id_note_1 == $note) return 0;
			else return 1;
		}
		if($id_note == 2) // Entre
		{
			if($note >= $id_note_1 && $note <= $id_note_2) return 0;
			else return 1;
		}
		if($id_note == 3) // Inférieur à
		{
			if($note < $id_note_1) return 0;
			else return 1;
		}
		if($id_note == 4) // Inférieur ou Egal à
		{
			if($note <= $id_note_1) return 0;
			else return 1;
		}
		if($id_note == 5) // Supérieur à
		{
			if($note > $id_note_1) return 0;
			else return 1;
		}
		if($id_note == 6) // Supérieur ou Egal à
		{
			if($note >= $id_note_1) return 0;
			else return 1;
		}
	}
	else
	{
		return 0;
	}
}
/**
* 
* @var ***************** PAR TLC ******************************
* 
*/
if(count($tableauBord) != 0)
{
$zHtml .= '
<table id="table_by_tlc" class="table_by_tlc">
<thead style="height:73px">
	<tr>';
	$zHtml .= '<th class="class_matricule first second"><span style="display:block;width:60px">Matricule</span></th>';
	$zHtml .= '<th class="class_matricule first second"><span style="display:block;width:100px">CC</span></th>';
	$zHtml .= "<th class='class_espace first second'></th>";
	$zHtml .= '<th class="class_note_eval first second"><span style="display:block;width:40px;">Note</span></th>';
	$zHtml .= '<th class="class_note_eval first second"><span style="display:block;width:60px;">Nb Evaluation</span></th>';
	$zHtml .= "<th class='class_espace first second'></th>";
$tab_categorie = array();
$list_cat = array();
foreach($tableauBord as $key => $tab)
{
	//foreach($tableauBord[$tab_matricule[0]]['libelle_categorie_grille'] as $key=>$tab)
	foreach($tableauBord[$key]['libelle_categorie_grille'] as $key_=>$tab_)
	{
		if(!in_array($key_,$tab_categorie))
		{
			//$key_ = id_categorie_grille
			$zHtml .= '<th class="class_cat second">'.$tab_.'</th>';
			array_push($tab_categorie,$key_);
			array_push($list_cat,$tab_);
		}
	}
	//break;
}

$zHtml .= "<th class='class_espace second'></th>";

/*for($i=4;$i<=7;$i++)
{*/
$tableaunf = get_indicateur_nf();
$tab_critere_is = array();
$class_cacher = 'cacher';
foreach($tableaunf as $keynf => $valnf)
{    
	if( $valnf == 'is5' || $valnf=='IS5'  )  $class_cacher = 'cacher';
	else $class_cacher = '';
	$zHtml .= '<th class="class_'.$valnf.' titreIS second '.$class_cacher.'" style="padding:0 5px;"><span style="display:block;width:50px;">'.substr($valnf,0,3).'</span></th>';
	$is = $valnf;
	$tab_critere = array();
	foreach($tableauBord_title as $key => $tab)
	{
		if(isset($tableauBord_title[$key][$is]['critere']))
		{
			foreach($tableauBord_title[$key][$is]['critere'] as $key_idcat=>$tab_val)
			{
				if(!in_array($key_idcat,$tab_critere))
				{
					$zHtml .= '<th class="class_is_titre second">'.$tab_val['libelle'].'</th>';
					array_push($tab_critere,$key_idcat);
					$tab_critere_is[$is] = 1;
				}
			}
		}
	}
	$zHtml .= "<th class='class_espace second'></th>";
}

$result_repartition = fetchAllRepartition();
while($res_rep = pg_fetch_array($result_repartition))
{
	$zHtml .= '<th class="class_repartition_categorie second">'.$res_rep['libelle_repartition'].'</th>';
}
$zHtml .= '
	</tr>
</thead>';
/**
* 
* @var ************************T BODY***********************************
* 
*/
$note_total = 0;
$valeur_note_total = 0;
$nbeval_total = 0;
$valeur_cat_total = array();
$valeur_cat_total_f = array();
$valeur_nombre_cat = array();
$valeur_nombre_total = 0;
$is_type_global_total = array();
$nb_eval_global_total = array();
$is_type_total = array();
$nb_eval_total = array();
$valeur_rep_total = array();
$nb_eval = 0;
$note = 0;

$zHtml .= '<tbody>';  
foreach($tab_matricule as $tab)
{
	$nb_eval = isset($tableauBord[$tab]['nbEval']) ? $tableauBord[$tab]['nbEval'] : 1;
	$_note = isset($tableauBord[$tab]['note']) ? $tableauBord[$tab]['note'] : 0;
	$note = number_format($_note / $nb_eval ,2);
	//$val_affiche_ligne = filtreNote($tab,$note,$id_note,$id_note_1,$id_note_2);
	$val_affiche_ligne = 0;
	if($val_affiche_ligne == 0)
	{
	$zHtml .= '<tr>';
	//$nb_eval = $tableauBord[$tab]['nbEval'];
	//$note = number_format($tableauBord[$tab]['note'] / $nb_eval ,2);
	$prenom_tlc = get_prenom_personnel( $tab );
	if($prenom_tlc == '')
	{
		$prenom_tlc = "<span style='color:#C71414'>(Inactif)</span>";
	}
	$zHtml .= "<td class='class_tlc first'>".$tab."</td>
	<td class='class_tlc first'><span style='display:block;width:100px;overflow:hidden;'>".$prenom_tlc."</span></td>
	<td class='class_espace first'></td>
	<td class='class_note first' style='text-align:center;'><span style='display:block;width:95%;'>".$note."</span></td>
	<td class='class_note first'>".$nb_eval."</td>";
	$note_total += $note * $nb_eval;
	$valeur_note_total += $note;
	$nbeval_total += $nb_eval; 
	$valeur_nombre_total += 1;
	
	$zHtml .= "<td class='class_espace first'></td>";
	/**
	* 
	* @var ******************* Par categorie ***********************
	* 
	*/
	foreach($tab_categorie as $key_cat=>$tab_cat)
	{
		if(isset($tableauBord[$tab]['categorie_grille'][$tab_cat]))
		{
			$valeur_cat = $tableauBord[$tab]['categorie_grille'][$tab_cat];
		}
		else
		{
			$valeur_cat = 0;
		}
		
		if(($id_type_traitement == 1 || $id_type_traitement == 2) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
		{
			$valeur_cat = $valeur_cat;
		}
		else
		{
			$valeur_cat = $valeur_cat / 10;
		}
		
		$zHtml .= '<td class="class_categorie">'.number_format($valeur_cat,2).'</td>';
		if(isset($valeur_cat_total[$tab_cat])) $valeur_cat_total[$tab_cat] += $valeur_cat;
		else $valeur_cat_total[$tab_cat] = $valeur_cat;
		
		if(isset($valeur_cat_total_f[$tab_cat])) $valeur_cat_total_f[$tab_cat] += number_format($valeur_cat*$nb_eval,2);
		else $valeur_cat_total_f[$tab_cat] = number_format($valeur_cat*$nb_eval,2);
		
		if(isset($valeur_nombre_cat[$tab_cat])) $valeur_nombre_cat[$tab_cat] += 1;
		else $valeur_nombre_cat[$tab_cat] = 1;
	} 
	
	////////////////////////////////////////////////////////////////
	
		$zHtml .= "<td class='class_espace'></td>";
		
	/**
	* 
	* @var ************** IS **************************
	* 
	*/
	$tab_critere = array();
	/*for($i=4;$i<=7;$i++)
	{*/
	$tableaunf = get_indicateur_nf();
	foreach($tableaunf as $keynf => $valnf)
	{
		 
	if( $valnf == 'is5' || $valnf=='IS5'  )  $class_cacher = 'cacher';
	else $class_cacher = '';
		
		
		$is = $valnf;
		if(isset($tableauBord[$tab][$is]['global']))
		{
			$valeur_is = $tableauBord[$tab][$is]['global'];
			if(isset($is_type_global_total[$is])) $is_type_global_total[$is] += $tableauBord[$tab][$is]['is_type'];
			else $is_type_global_total[$is] = $tableauBord[$tab][$is]['is_type'];
			
			if(isset($nb_eval_global_total[$is])) $nb_eval_global_total[$is] += $tableauBord[$tab][$is]['nb_eval'];
			else $nb_eval_global_total[$is] = $tableauBord[$tab][$is]['nb_eval'];
		}
		else
		{
			$valeur_is = 0;
			if(isset($is_type_global_total[$is])) $is_type_global_total[$is] += 0;
			//else $is_type_global_total[$is] = 0;
			
			if(isset($nb_eval_global_total[$is])) $nb_eval_global_total[$is] += 0;
			//else $nb_eval_global_total[$is] = 0;
		}
		if(isset($tableauBord[$tab][$is]['critere'])) $nombre_count = count($tableauBord[$tab][$is]['critere']);
		else $nombre_count = 0;
		if($nombre_count == 0)
		{
			$_val_is = 'NE';
		}
		else
		{
			$_val_is = number_format($valeur_is,0).'%';
		}
		$zHtml .= '<td class="class_is_total '.$class_cacher.'" style="padding:0 5px;">'.$_val_is.'</td>';
		
		$tab_critere[$is] = array();
		//if(empty($tableauBord[$tab][$is]['critere']) && !empty($tableauBord_title[$tab][$is]['critere']))
		if(empty($tableauBord[$tab][$is]['critere']) && isset($tab_critere_is[$is]) == 1)
		{
			$zHtml .= '<td class="class_is">NE</td>';
			array_push($tab_critere[$is],-999);
		}else
		foreach($tableauBord[$tab][$is]['critere'] as $key_idcat=>$tab_val)
		{
			if(!in_array($key_idcat,$tab_critere))
			{
				// Modifié le 16-09-2014
				/*if(isset($tab_val['valeur']) && $tab_val['valeur'] >= 0)
				{*/
					$zHtml .= '<td class="class_is">'.number_format($tab_val['valeur'],0).'%</td>';
				/*}
				else
				{
					$zHtml .= '<td class="class_is">100%</td>';
				}*/
				/** ******************************* **/
				array_push($tab_critere[$is],$key_idcat);
				
				if(isset($is_type_total[$is][$key_idcat])) $is_type_total[$is][$key_idcat] += $tab_val['is_type'];
				else $is_type_total[$is][$key_idcat] = $tab_val['is_type'];
				
				if(isset($nb_eval_total[$is][$key_idcat])) $nb_eval_total[$is][$key_idcat] += $tab_val['nb_eval'];
				else $nb_eval_total[$is][$key_idcat] = $tab_val['nb_eval'];
			}
		}
		$zHtml .= "<td class='class_espace'></td>";
	}
	//////////////////////////////////////////////////////////////////
	
	/**
	* 
	* @var ********************** Par répartition *********************
	* 
	*/
	$result_repartition = fetchAllRepartition();
	while($res_rep = pg_fetch_array($result_repartition))
	{
		if(isset($tableauBord[$tab]['repartition'][$res_rep['id_repartition']]))
		{
			$valeur_rep = $tableauBord[$tab]['repartition'][$res_rep['id_repartition']];
		}
		else
		{
			$valeur_rep = 0;
		}
		$zHtml .= '<td class="class_repartition">'.$valeur_rep.'</td>';
		if(isset($valeur_rep_total[$res_rep['id_repartition']])) $valeur_rep_total[$res_rep['id_repartition']] += $valeur_rep; 
		else $valeur_rep_total[$res_rep['id_repartition']] = $valeur_rep; 
	}
	////////////////////////////////////////////////////////////////////
	$zHtml .= '</tr>';
	}
}

$zHtml .= '</tbody>';
$zHtml .= '<tfoot>';
/**
* *********************** TOTAL **************************
*/
/*foreach($tab_matricule as $tab)
{*/
	$zHtml .= '<tr>';
	$nom_client = getNomClientById($id_client);
	$res_app = getCodePrestationById($id_application);
	$codeApp = $res_app['code'];
	$zHtml .= "
	<th class='class_matricule first' colspan='2' style='color: #c71414;font-size: 13px;'>".$codeApp." - ".$nom_client."</th>
	<th class='class_espace first'></th>
	<th class='class_note_eval first'style='text-align:center'><span style='display:block;width:95%;'>".number_format($valeur_note_total/$valeur_nombre_total,2)."</span></th>
	<th class='class_note_eval first'>".$nbeval_total."</th>";
	$name = $codeApp." - ".$nom_client;
	
	$zHtml .= "<th class='class_espace first'></th>";
	/**
	* 
	* @var ******************* Par categorie ***********************
	* 
	*/
	$list_cat_valeur = array();
	foreach($tab_categorie as $key_cat=>$tab_cat)
	{
		//$zHtml .= '<th class="class_cat">'.number_format($valeur_cat_total_f[$tab_cat]/$nbeval_total,2).'</th>';
		$zHtml .= '<th class="class_cat">'.number_format($valeur_cat_total[$tab_cat]/$valeur_nombre_cat[$tab_cat],2).'</th>';
		array_push($list_cat_valeur,(float) number_format($valeur_cat_total[$tab_cat],2));
	}
	////////////////////////////////////////////////////////////////
	
		$zHtml .= "<th class='class_espace'></th>";
		
	/**
	* 
	* @var ************** IS **************************
	* 
	*/
	/*for($i=4;$i<=7;$i++)
	{*/
	$tableaunf = get_indicateur_nf();
	foreach($tableaunf as $keynf => $valnf)
	{
		$is = $valnf;
		
		if( $valnf == 'is5' || $valnf=='IS5'  )  $class_cacher = 'cacher';
	    else $class_cacher = '';
		
		if($nb_eval_global_total[$is] != 0)
		{
			$valeur_is = ($is_type_global_total[$is] / $nb_eval_global_total[$is]) * 100;
			$valeur_is = number_format($valeur_is,0);
			/*if ($valeur_is == 100)
			{
				$valeur_is = number_format($valeur_is,0);
			}
			else
			{
				$valeur_is = number_format($valeur_is,2);
			}*/
		}
		else
		{
			$valeur_is = 0;
		}
		if(count($tab_critere[$is]) == 0)
		{
			$_val_is = 'NE';
		}
		else
		{
			$_val_is = $valeur_is.'%';
		}
		$zHtml .= '<th class="class_is'.$i.' titreIS '.$class_cacher.'" style="padding:0 5px;">'.$_val_is.'</th>';
		
		//if(empty($tab_critere[$is]))
		//if($tab_critere[$is] == -999)
		if(in_array(-999,$tab_critere[$is]))
		{
			$zHtml .= '<th class="class_is_titre">NE</th>';
		}else
		foreach($tab_critere[$is] as $key_idcat)
		{
			if($nb_eval_total != 0)
			{
				$valeur_is_cat = ($is_type_total[$is][$key_idcat] / $nb_eval_total[$is][$key_idcat]) * 100;
			}
			else
			{
				$valeur_is_cat = 0;
			}
			$zHtml .= '<th class="class_is_titre">'.number_format($valeur_is_cat,0).'%</th>';
		}
		$zHtml .= "<th class='class_espace'></th>";
	}
	/**
	* 
	* @var ********************** Par répartition *********************
	* 
	*/
	$result_repartition = fetchAllRepartition();
	while($res_rep = pg_fetch_array($result_repartition))
	{
		$zHtml .= '<th class="class_repartition_categorie">'.$valeur_rep_total[$res_rep['id_repartition']].'</th>';
	}
	$zHtml .= '</tr>';
	
//}

$zHtml .= '</tfoot>';
$zHtml .= '</table>';
}
else 
{
	//$zHtml .= utf8_decode('<center><span style="color:red;font-size:12px;">Aucune donnée</span></center>');
	$zHtml .= 1;
}

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
$zHtml .= '|||';
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
/**
* 
* @var ******************** Toutes les prestations **************************
* 
*/
$tableauPrest = setTableauSynthesePrestation($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc,$id_type_appel);

if(count($tableauPrest) != 0)
{	
	$zHtml .= '<table id="table_by_presta" class="table_by_tlc">
<thead>
	<tr>';
	//$zHtml .= '<th class="class_matricule first" rowspan="2"><span style="display:block;width:100px;">Type de traitement</span></th>';
	$zHtml .= '<th class="class_note_eval first" rowspan="2"><span style="display:block;width:200px;">Prestation</span></th>';
	$zHtml .= '<th class="class_note_eval first" rowspan="2"><span style="display:block;width:50px;">Code</span></th>';
	$zHtml .= '<th class="class_note_eval first" rowspan="2"><span style="display:block;width:70px;">Nb Evaluation</span></th>';
	
	$zHtml .= '<th class="sep first" ></th>';
	$zHtml .= '<th class="class_espace first"></th>';
	
	$list_is = array();
	//for($i=4;$i<=7;$i++)
	$tableaunf = get_indicateur_nf();
	foreach($tableaunf as $keynf => $valnf)
	{
		
		if( $valnf == 'is5' || $valnf=='IS5'  )  $class_cacher = 'cacher';
	    else $class_cacher = '';
	    
		$zHtml .= '<th class="class_is_ titreIS '.$class_cacher.'"><span style="width:100px;display:block;padding:5px;">'.substr($valnf,0,3).'</span></th>';
		array_push($list_is,$valnf);
	}

	$zHtml .= "<th class='class_espace'></th>";

	$result_repartition = fetchAllRepartition();
	$nb_repartition = pg_num_rows($result_repartition);
	$zHtml .= '<th class="class_repartition_categorie" colspan="'.$nb_repartition.'">Situations inacceptables</th>';

	$zHtml .= '</tr>';
	$zHtml .= '<tr style="height:39px;">';
	$zHtml .= '<th class="sep first"></th>';
	$zHtml .= '<th class="class_espace first"></th>';
	
	$tableaunf = get_indicateur_nf_objectif();
	foreach($tableaunf as $keynf => $valnf)
	{  
		if( $valnf['libelle'] == 'is5' || $valnf['libelle']=='IS5'  )  $class_cacher = 'cacher';
	    else $class_cacher = '';
		
		$zHtml .= '<th class="class_is_ class_repartition_categorie '.$class_cacher.'">NF>='.$valnf['objectif'].'%</th>';
	}
	/*$zHtml .= '<th class="class_is4">FCR NF>=60%</th>';
	$zHtml .= '<th class="class_is5">Prise en charge NF>=85%</th>';
	$zHtml .= '<th class="class_is6">Pertinence de la r&eacute;ponse NF>=85%</th>';
	$zHtml .= '<th class="class_is7">Exp&eacute;rience Client NF>=80%</th>';*/
	$zHtml .= '<th class="class_espace"></th>';
	while($res_rep = pg_fetch_array($result_repartition))
	{
		$zHtml .= '<th class="class_repartition_categorie"><span style="width:80px;display:block;">'.$res_rep['libelle_repartition'].'</span></th>';
	}
	$zHtml .= '</tr>';
	$zHtml .= '</thead>';
	/**
	* 
	* @var ********************** T BODY **************************
	* 
	*/
	$zHtml .= '<tbody>';
	$list_is_valeur = array();
	foreach($tableauPrest as $key_type => $tab_type)
	{
		foreach($tab_type as $key_code => $tab_code)
		{
			$list_series = array();
			$zHtml .= '<tr>';
			if($key_type == 1) $libelle_type_traitement = 'Appels entrants';
			if($key_type == 2) $libelle_type_traitement = 'Appels sortants';
			if($key_type == 3) $libelle_type_traitement = 'Traitement Mail';
			//$zHtml .= '<td class="class_tlc first">'.$libelle_type_traitement.'</td>';
			//foreach($tab_type as $key_code => $tab_code)
			//{
				$nom = $tab_code['prestation'].' - '.$tab_code['client'];
				$zHtml .= '<td class="class_note first">'.$tab_code['libelle_code'].'</td>';
				$zHtml .= '<td class="class_note first">'.$tab_code['prestation'].'</td>';
				$zHtml .= '<td class="class_note first">'.$tab_code['nb_evaluation'].'</td>';
				$zHtml .= '<td class="sep first"></td>';
				$zHtml .= '<td class="class_espace first"></td>';
				$list_ind_nf = array();
				
				//for($i=4;$i<=7;$i++)
				$tableaunf = get_indicateur_nf();
				foreach($tableaunf as $keynf => $valnf)
				{
					$is= $valnf;
					if( $valnf == 'is5' || $valnf=='IS5'  )  $class_cacher = 'cacher';
	                else $class_cacher = '';
					
					if(isset($tab_code['indicateur_nf'][$is]))
					{
						$val_is = $tab_code['indicateur_nf'][$is];
						$val_is = number_format($val_is,0).'%';
					}
					else
					{
						$val_is = 'NE';
					}
					$zHtml .= '<td class="class_is '.$class_cacher.'">'.$val_is.'</td>';
					//array_push($list_ind_nf,$val_is);
					$list_ind_nf[] = (float)$val_is;
				}
				$zHtml .= '<td class="class_espace"></td>';
				$result_repartition = fetchAllRepartition();
				while($res_rep = pg_fetch_array($result_repartition))
				{
					if(isset($tab_code['repartition'][$res_rep['id_repartition']]))
					{
						$val_rep = $tab_code['repartition'][$res_rep['id_repartition']];
					}
					else
					{
						$val_rep = 0;
					}
					$zHtml .= '<td class="class_is">'.$val_rep.'</td>';
				}
			//}
			$zHtml .= '</tr>';
			$list_series['name'] = $nom;
			$list_series['data'] = $list_ind_nf;
			$list_series['pointPlacement'] = 'on';
			$list_is_valeur[] = $list_series;
		}
		//echo json_encode($list_series);echo '<br>';
		//array_push($list_is_valeur,$list_series);
		
	}
	$zHtml .= '</tbody>';
	$zHtml .= '</table>';
}
else 
{
	//$zHtml .= utf8_decode('<center><span style="color:red;font-size:12px;">Aucune donnée</span></center>');
	$zHtml .= 1;
}
$zHtml .= '|||';
echo $zHtml;
?>
<br>
<div class="titre" style="border:0px solid red;display: block;position: relative;width: 1182px;height: 43px;margin:auto;"></div>
<div id="div_principal" style="border:0px solid red;display: block;position: relative;width: 1182px;height: 324px;margin:auto;">
		<div id="div_1" style="border:0px solid green;width: 320px;height: 76px;display: block;position: relative;float: left;overflow: hidden;background: #b4d0d0;"></div>
		<div id="div_2" style="border:0px solid green;width: 862px;height: 76px;display: block;position: relative;overflow: hidden;background:#D9F5F5;"></div>
		<!--div id="div_5" style="border: 0px solid green; height: 60px; display: block; position: relative; overflow: hidden; width: 17px;background:#D9F5F5;"></div-->
	<!---------------------------------------------------------------------------->
	<div id="div_3" style="border:0px solid blue;width: 320px;height: 300px;display: block;position: relative;float: left;overflow: hidden;background: #b4d0d0;"></div>
	<div id="div_4" style="border:0px solid blue;width: auto;height: 300px;display: block;position: relative;overflow-y: scroll;overflow-x: hidden;background:#D9F5F5;"></div>
	<!---------------------------------------------------------------------------->
	<div id="div_6" style="border:0px solid blue;width: 320px;height: 30px;display: block;position: relative;float: left;overflow: hidden;background: #b4d0d0;"></div>
	<div id="div_7" style="border:0px solid blue;width: auto;height: 46px;display: block;position: relative;overflow-x: scroll;overflow-y: hidden;background:#D9F5F5;"></div>
</div>
<!--<div id="div_principal_schema" style="border:0px solid red;display: block;position: relative;width: 1182px;height: 450px;margin:auto;"></div>-->

|||

<br>
<div class="titre" style="border:0px solid red;display: block;position: relative;width: 1182px;height: 43px;margin:auto;"></div>
<div id="div_principal0" style="border:0px solid red;display: block;position: relative;width: 1182px;height: 324px;margin:auto;">
		<div id="div_10" style="border:0px solid green;width: 366px;height: 65px;display: block;position: relative;float: left;overflow: hidden;background: #b4d0d0;"></div>
		<div id="div_20" style="border:0px solid green;width: 799px;height: 65px;display: block;position: relative;overflow: hidden;background:#D9F5F5;"></div>
		<!--div id="div_50" style="border: 0px solid green; height: 65px; display: block; position: relative; overflow: hidden; width: 17px;background:#D9F5F5;"></div-->
	<!---------------------------------------------------------------------------->
	<div id="div_30" style="border:0px solid blue;width: 366px;height: 325px;display: block;position: relative;float: left;overflow: hidden;background: #b4d0d0;"></div>
	<div id="div_40" style="border:0px solid blue;width: auto;height: 341px;display: block;position: relative;overflow: scroll;background:#D9F5F5;"></div>
</div>

|||

<?php
/*echo $name;
echo '<br>';
echo json_encode($list_cat);
echo '<br>';
echo json_encode($list_cat_valeur);*/

?>
<div id="KTner" style="min-width: 400px; width: 1182px; margin: 0 auto"></div>
<?php
$critere = array();

$critere['critere'] = $list_cat;
$critere['seuil_minimal'] = $list_cat_valeur;
//$critere['realise'] = array(5, 9, 10, 4, 4, 9);

$tailleExiger = count($critere['critere']);

if (count($critere['seuil_minimal']) != $tailleExiger ) {
die("Le nombre de critères et de données ne correspondent pas");  
}
?>
<script>
  var criteres = <?php echo json_encode($critere['critere']); ?>;
  var seuilsMinima = <?php echo json_encode($critere['seuil_minimal']); ?>;
  //var realises = <?php echo json_encode($critere['realise']); ?>;
 var graph = $(function() {

    $('#KTner').highcharts({
      chart: {
        polar: true,
        type: 'line'
      },
      title: {
        text: 'Synth\350se par CC',
        x: -80
      },
      pane: {
        size: '80%'
      },
      xAxis: {
        categories: criteres,
        tickmarkPlacement: 'on',
        lineWidth: 0
      },
      yAxis: {
        gridLineInterpolation: 'polygon',
        lineWidth: 0,
        min: 0
      },
      tooltip: {
        shared: true,
        pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
      },
      legend: {
        align: 'right',
        verticalAlign: 'top',
        y: 70,
        layout: 'vertical'
      },
      series: [{
          name: <?php echo "'" . $name . "'"; ?>, 
          data: seuilsMinima,
          pointPlacement: 'on'
        }]

    });
    
  });
  
  $(document).ajaxComplete(function(){
  	graph;
  	setTimeout(function() {
            $('tspan:contains("Highcharts.com")').hide();
        }, 2000);
  });
</script>

|||

<?php
/*echo json_encode($list_is);
echo '<br>';
echo json_encode($list_is_valeur);*/

?>
<div id="KTner1" style="min-width: 400px; width: 1182px; margin: 0 auto"></div>
<?php
$critere = array();

$critere['critere'] = $list_is;
$critere['seuil_minimal'] = $list_is_valeur;
//$critere['realise'] = array(5, 9, 10, 4, 4, 9);

?>
<script>
  var criteres = <?php echo json_encode($critere['critere']); ?>;
  var seuilsMinima = <?php echo json_encode($critere['seuil_minimal']); ?>;
  //var realises = <?php echo json_encode($critere['realise']); ?>;
 var graph1 = $(function() {

    $('#KTner1').highcharts({
      chart: {
        polar: true,
        type: 'line'
      },
      title: {
        text: 'Synth\350se pour toutes les prestations',
        x: -80
      },
      pane: {
        size: '80%'
      },
      xAxis: {
        categories: criteres,
        tickmarkPlacement: 'on',
        lineWidth: 0
      },
      yAxis: {
        gridLineInterpolation: 'polygon',
        lineWidth: 0,
        min: 0
      },
      tooltip: {
        shared: true,
        pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
      },
      legend: {
        align: 'right',
        verticalAlign: 'top',
        y: 70,
        layout: 'vertical'
      },
      series: seuilsMinima
      /*[{
          name: <?php echo "'" . $name . "'"; ?>, 
          data: seuilsMinima,
          pointPlacement: 'on'
        }]*/
    //series : [{"name":"BZC - BAZARCHIC","data":["80","40","100","80"]},{"name":"EVI - EVIOO","data":["75","25","100","100"]},{"name":"TST - WENGO","data":["100","100","100","0"]},{"name":"TSU - WENGO","data":["100","0","0","100"]}]
    });
  });
  
  $(document).ajaxComplete(function(){
  	graph1;
  });
</script>

|||
</br>
<?php
/* **************** Récapitulatif ***************************/
$result_notation = getAllNotation($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc,$id_type_appel);
$nb_notation = pg_num_rows($result_notation);

if(count($nb_notation) != 0)
{
	$zHtml = '<div class="titre" style="border:0px solid red;display: block;position: relative;width: 1182px;height: 43px;margin:auto;"></div>';
	$zHtml .= '<div id="div_principal100" style="border:0px solid red;display: block;position: relative;width: 1182px;height: 412px;margin:auto;overflow:auto;">';
	$zHtml .= '<table id="table_notation" class="table_by_tlc" style="margin: 2px auto;">
	<thead>
	<tr>';
	$zHtml .= '<th class="class_note_eval first"><span style="display:block;width:100px;">Date de traitement</span></th>';
	$zHtml .= '<th class="class_note_eval first"><span style="display:block;width:100px;">Date de notation</span></th>';
	$zHtml .= '<th class="class_note_eval first"><span style="display:block;width:200px;">Evaluateur</span></th>';
	$zHtml .= '<th class="class_note_eval first"><span style="display:block;width:200px;">CC</span></th>';
	$zHtml .= '<th class="class_note_eval first"><span style="display:block;width:50px;">Note</span></th>';
	$zHtml .= '<td class="class_espace first"></td>';
	$tableaunf = get_indicateur_nf();
	foreach($tableaunf as $keynf => $valnf)
	{
		$is = $valnf;
		if( $valnf == 'is5' || $valnf=='IS5'  )  $class_cacher = 'cacher';
        else $class_cacher = '';
		
		$zHtml .= '<th class=" first SitInaccept '.$class_cacher.'"><span style="display:block;width:50px;">'.substr($is,0,3).'</span></th>';
	}
	/*$zHtml .= '<th class="class_note_eval first"><span style="display:block;width:50px;">IS4</span></th>';
	$zHtml .= '<th class="class_note_eval first"><span style="display:block;width:50px;">IS5</span></th>';
	$zHtml .= '<th class="class_note_eval first"><span style="display:block;width:50px;">IS6</span></th>';
	$zHtml .= '<th class="class_note_eval first"><span style="display:block;width:50px;">IS7</span></th>';*/
	$zHtml .= '<td class="class_espace first"></td>';
	$result_repartition = fetchAllRepartition();
	while($res_rep = pg_fetch_array($result_repartition))
	{
		$zHtml .= '<th class=" first SitInaccept"><span style="display:block;width:70px;">'.$res_rep['libelle_repartition'].'</span></th>';
	}
	$zHtml .= '<td class="class_espace first"></td>';
	$zHtml .= '<th class=" first titreIS"><span style="display:block;width:100px;">Date entretien</span></th>';
	$zHtml .= '<th class=" first titreIS"><span style="display:block;width:200px;">R&eacute;f&eacute;rence</span></th>';
	$zHtml .= '<th class=" first titreIS"><span style="display:block;width:200px;">Dossier</span></th>';
	$zHtml .= '<th class=" first titreIS"><span style="display:block;width:200px;">Commande</span></th>';
	$zHtml .= '<th class=" first titreIS"><span style="display:block;width:200px;">Type d\'appel</span></th>';
	
	$zHtml .= '<td class="class_espace first"></td>';
	$zHtml .= '<th class=" first PointAppui"><span style="display:block;width:200px;">Points d\'appui</span></th>';
	$zHtml .= '<th class=" first PointAppui"><span style="display:block;width:200px;">Points d\'am&eacute;lioration</span></th>';
	$zHtml .= '<th class=" first PointAppui"><span style="display:block;width:200px;">Pr&eacute;conisations</span></th>';

	$zHtml .= '</tr>';
	$zHtml .= '</thead>';
	/**
	* 
	* @var ********************** T BODY **************************
	* 
	*/
	$zHtml .= '<tbody>';
	$nombre_total_ligne = 0;
	$tableau_is = array();
	while($tab_type = pg_fetch_array($result_notation))
	{
		$list_series = array();
		
		$id_notation = $tab_type['id_notation'];
		/*$str = calculTotalGeneral($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement);
		$table_valeur = explode('||',$str); 

		$note =  $table_valeur[0];*/
		$note = number_format($tab_type['note'],2);
		$val_affiche_ligne = filtreNote($tab_type['matricule'],$note,$id_note,$id_note_1,$id_note_2);
		
		$prenomPersTLC = getPrenomPersonnel($tab_type['matricule']);
		if($prenomPersTLC == '')
		{
			$prenomPersTLC = '<span style="color:#C71414">(Inactif)</span>';
		}
		$prenomPersEval = getPrenomPersonnel($tab_type['matricule_notation']);
		if($prenomPersEval == '')
		{
			$prenomPersEval = '<span style="color:#C71414">(Inactif)</span>';
		}
		if($val_affiche_ligne == 0)
		{
			$nombre_total_ligne ++;
		}
		if($val_affiche_ligne == 0)
		{
		$zHtml .= '<tr>';
		$zHtml .= '<td class="class_note first">'.$tab_type['date_entretien'].'</td>';
		$zHtml .= '<td class="class_note first">'.$tab_type['date_notation'].'</td>';
		$zHtml .= '<td class="class_note first">'.$tab_type['matricule_notation'].' - '.$prenomPersEval.'</td>';
		$zHtml .= '<td class="class_is first">'.$tab_type['matricule'].' - '.$prenomPersTLC.'</td>';
		$zHtml .= '<td class="class_is first">'.number_format($note,2).'</td>';
		$zHtml .= '<td class="class_espace first"></td>';
		
		/************** IS **********************/
		$tableau_is = getIS($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc);
		/*for($i=4;$i<=7;$i++)
		{*/
		$tableaunf = get_indicateur_nf();
		foreach($tableaunf as $keynf => $valnf)
		{
			$is = $valnf;
			
			if( $valnf == 'is5' || $valnf=='IS5'  )  $class_cacher = 'cacher';
            else $class_cacher = '';
			
			$val_is_not = isset($tableau_is[$id_notation][$is]) ? $tableau_is[$id_notation][$is] : 0;
			$zHtml .= '<td class="class_is first '.$class_cacher.'">'.$val_is_not.'</td>';
		}
		//////////////////////////////////////////////////////////////
		$zHtml .= '<td class="class_espace first"></td>';
		/************** Situation inacceptable **********************/
		$result_repartition = fetchAllRepartition();
		while($res_rep = pg_fetch_array($result_repartition))
		{
			if(isset($tableau_is[$id_notation][$res_rep['id_repartition']]))
			{
				$val_rep = $tableau_is[$id_notation][$res_rep['id_repartition']];
			}
			else
			{
				$val_rep = 0;
			}
			$zHtml .= '<td class="class_is first">'.$val_rep.'</td>';
		}
		//////////////////////////////////////////////////////////////
		$zHtml .= '<td class="class_espace first"></td>';
		$zHtml .= '<td class="class_is first">'.$tab_type['date_entretien'].'</td>';
		$zHtml .= '<td class="class_is first">'.$tab_type['nom_fichier'].'</td>';
		$zHtml .= '<td class="class_is first">'.utf8_decode($tab_type['numero_dossier']).'</td>';
		$zHtml .= '<td class="class_is first">'.utf8_decode($tab_type['numero_commande']).'</td>';
		$zHtml .= '<td class="class_is first">'.utf8_decode($tab_type['libelle_typologie']).'</td>';
		
		$zHtml .= '<td class="class_espace first"></td>';
		$zHtml .= '<td class="class_is first">'.utf8_decode($tab_type['point_appui']).'</td>';
		$zHtml .= '<td class="class_is first">'.utf8_decode($tab_type['point_amelioration']).'</td>';
		$zHtml .= '<td class="class_is first">'.utf8_decode($tab_type['preconisation']).'</td>';
		$zHtml .= '</tr>';
		}
	}
	$zHtml .= '</tbody>';
	$zHtml .= '</table>';
	$zHtml .= '</div>';
	$zHtml .= '<span style="display: block; margin: auto; font-family: Verdana; color: rgb(0, 0, 0); font-weight: bold; font-size: 11px; width: 225px;">Nombre d\'enregistrements = '.$nombre_total_ligne.'</span>';
}
else 
{
	//$zHtml .= utf8_decode('<center><span style="color:red;font-size:12px;">Aucune donnée</span></center>');
	$zHtml .= 1;
}
echo $zHtml;
?>

|||

<!--div class="titre" style="border:0px solid red;display: block;position: relative;width: 1182px;height: 43px;margin:auto;"></div--> 
<?php
//<div id="div_principal00" style="border:0px solid red;display: block;position: relative;width: 1182px;height: 324px;margin:auto;">
$_res = getClientById($id_client,$id_application,$matricule_tlc,$matricule_auditeur,$id_type_appel);
$_res = explode('||',$_res);
if($id_type_traitement == 1) $type = 'Appels entrants';
if($id_type_traitement == 2) $type = 'Appels sortants';
if($id_type_traitement == 3) $type = 'Traitement Mail';
if($id_type_traitement == 4) $type = 'Traitement Tchat';

if(($_res[2] == ' - ') || (strpos($matricule_tlc, ',') !== false)) $_nom_tlc = ' Aucun ';
else $_nom_tlc = $_res[2];

if(($_res[3] == ' - ') || (strpos($matricule_auditeur, ',') !== false)) $_nom_audit = ' Aucun ';
else $_nom_audit = $_res[3];

if(($_res[4] == '') || (strpos($id_type_appel, ',') !== false)) $lib_typ_appel = ' Aucun ';
else $lib_typ_appel = $_res[4];

$zHtml = '<table><thead><tr><td colspan="10" style="text-align:left;"><span style="padding:0 0 5px 25px;display:block;position:relative;font-family:Verdana; font-size:11px;">
<span id="id_affiche1"><b>Type de traitement : </b>'.$type.'</span><br>
<span id="id_affiche2"><b>Client : </b>'.$_res[0].'</span><br>
<span id="id_affiche3"><b>Prestation : </b>'.$_res[1].'</span>
</span></td>
<td colspan="10" style="text-align:left;"><span style="padding:0 0 5px 25px;display:block;position:relative;font-family:Verdana; font-size:11px;">
<span id="id_affiche4"><b>CC : </b>'.$_nom_tlc.'</span></br>
<span id="id_affiche5"><b>Evaluateur : </b>'.$_nom_audit.'</span></br>
<span id="id_affiche6"><b>Type d\'appel : </b>'.$lib_typ_appel.'</span>
</span></td>';
$zHtml .= '</tr></thead></table>';

$zHtml .= '|||';

$zHtml .= '
	<table style="width:35%; padding-left:20px;">
		<tr>
		<td>
			<label style="font-size: 11px; font-family: verdana;">Date du : </label>
			<input type="text" id="date_deb_export_reporting" style="width:100px;text-align:center;" />
			<label style="font-size: 11px; font-family: verdana;"> &agrave; </label> 
			<input type="text" id="date_fin_export_reporting" style="width:100px;text-align:center;" />
		</td>
		
		<td>
			<span style="font-weight:bold;font-size:11px;margin-left:10px;color:#18484f;">Synth&egrave;se</span>
		</td>
		<td>
			<img id="img_export_reporting" style="cursor: pointer;" title="Export synth&egrave;se" src="images/excel2.png" width="27" height="30" onclick="export_reporting('.$id_projet.','.$id_client.','.$id_application.','.$id_type_traitement.',\''.$matricule_tlc.'\',\''.$matricule_auditeur.'\',1,\''.$id_type_appel.'\');"/>
		</td>
		
		<!--<td>
			<span style="font-weight:bold;font-size:11px;margin-left:10px;color:#18484f;">Synth&egrave;se Macro</span>
		</td>
		<td>
			<img id="img_export_reporting_global" style="cursor: pointer;" title="Export synth&egrave;se Macro" src="images/excel2.png" width="27" height="30" onclick="export_reporting('.$id_projet.','.$id_client.','.$id_application.','.$id_type_traitement.',\''.$matricule_tlc.'\',\''.$matricule_auditeur.'\',2,0);"/>
		</td>
		
		<td>
			<span style="font-weight:bold;font-size:11px;margin-left:10px;color:#18484f;">TDB</span>
		</td>
		<td>
			<img id="img_export_tdb" style="cursor: pointer;" title="Export Tableau de Bord" src="images/excel2.png" width="27" height="30" onclick="export_reporting('.$id_projet.','.$id_client.','.$id_application.','.$id_type_traitement.','.$matricule_tlc.',\''.$matricule_auditeur.'\',3,0);"/>
		</td>
		
		<td>
			<span style="font-weight:bold;font-size:11px;margin-left:10px;color:#18484f;">TDB Hebdo</span>
		</td>
		<td>
			<img id="img_export_tdb" style="cursor: pointer;" title="Export Tableau de Bord Hebdomadaire" src="images/excel2.png" width="27" height="30" onclick="export_reporting('.$id_projet.','.$id_client.','.$id_application.','.$id_type_traitement.',\''.$matricule_tlc.'\',\''.$matricule_auditeur.'\',4,0);"/>
		</td>
		
		<td>
			<span style="font-weight:bold;font-size:11px;margin-left:10px;color:#18484f;">TDB Mensuel</span>
		</td>
		<td>
			<img id="img_export_tdb" style="cursor: pointer;" title="Export Tableau de Bord Mensuel" src="images/excel2.png" width="27" height="30" onclick="export_reporting('.$id_projet.','.$id_client.','.$id_application.','.$id_type_traitement.',\''.$matricule_tlc.'\',\''.$matricule_auditeur.'\',5,0);"/>
		</td>-->
		
		</tr>
	</table>';

echo $zHtml;
?>