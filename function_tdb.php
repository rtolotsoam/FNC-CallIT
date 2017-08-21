<?php
//include("/var/www.cache/dgconn.inc");
//include('function_synthese_dynamique.php');
//include('function_dynamique.php');

function setLibelleForTitle($tab)
{
	global $conn;
	$tableau = array();
	$val = '';
	foreach($tab as $indnf)
	{
		switch ($indnf) {
			case 'is1':
				$tableau[] = 'SLA 180s NF>=80%';
				break;
			case 'is2':
				$tableau[] = 'QS NF>=90%';
				break;
			case 'is3':
				$tableau[] = 'Accès au service NF>=100%';
				break;
			case 'is4':
				$tableau[] = 'FCR NF>=60%';
				break;
			case 'is5':
				$tableau[] = 'prise en charge NF>=85%';
				break;
			case 'is5_v7':
				$tableau[] = 'prise en charge NF>=85%';
				break;
			case 'is6':
				$tableau[] = 'pertinence de la réponse NF>=85%';
				break;
			case 'is7':
				$tableau[] = 'Expérience Client NF>=80%';
				break;
			case 'is8':
				$tableau[] = 'Traitement des réclamations NF>=90%';
				break;
			case 'is9':
				$tableau[] = 'Satisfaction Clients';
				break;
			case 'SI':
				$result = fetchAllRepartition();
				while ($res_rep = pg_fetch_array($result))
				{
					$tableau[] = utf8_encode($res_rep['libelle_repartition']);
				}
				break;
			case 'vt':
				$tableau[] = 'Volume traité';
				break;
			case 'hw':
				$tableau[] = 'HW';
				break;
			case 'ip1':
				//$tableau[] = 'TO conseillers <10%';
				$tableau[] = 'TO conseillers <=8%';
				break;
			case 'ip2':
				//$tableau[] = 'Absentéisme conseillers <10%';
				$tableau[] = 'Absentéisme conseillers <=7%';
				break;
			case 'ip3':
				//$tableau[] = 'Satisfaction des conseillers';
				$tableau[] = 'Satisfaction des conseillers >=75%';
				break;
			case 'ip4':
				//$tableau[] = 'Efficacité de la formation initiale';
				$tableau[] = 'Efficacité de la formation initiale >=80%';
				break;
			case 'ip5':
				$tableau[] = 'Précision des prévisions';
				break;
			case 'ip6':
				/*$tableau[] = 'Objectif DMT';
				$tableau[] = 'DMT Réalisée';*/
				$tableau[] = 'objectif -30% ≤ DMT ≤ objectif+ 15%';
				$tableau[] = 'DMT Réalisée';
				break;
			case 'ip7':
				//$tableau[] = 'Disponibilité du service';
				$tableau[] = 'Disponibilité du service >=99%';
				break;
		}
	}
	return $tableau;
}

function getAllListTitle()
{
	//vt = Volume traité	
	// $tab    = array('vt','hw','is1','is2','x');
	$tab    = array('vt','is1','is2','is3');
	$result = get_indicateur_nf_objectif();
	
	foreach($result as $key => $val) {
		array_push($tab, $val['libelle']);
	}
	$tab[] = 'SI';
	$tab[] = 'is8';
	// $tab[] = 'is3';
	$tab[] = 'is9';
	$tab[] = 'ip1';
	$tab[] = 'ip2';
	$tab[] = 'ip3';
	$tab[] = 'ip4';
	$tab[] = 'ip5';
	$tab[] = 'ip6';
	$tab[] = 'ip7';
	
	return $tab;
}

/**
* ****************************************************************************************
* 
* @return
*/

function getValeurForPrestation($code,$date_deb,$date_fin)
{
	//global $table;
	$table = array();
	switch ($code) {
		case 'DGT': // Digitick
			$base_table = 'dgt_wallboard';
			break;
		case 'EPK': //EPROKOM
			$base_table = 'epk_wallboard';
			break;
		case 'DLM': // De La Maison
			$base_table = 'dlm_wallboard';
			break;
		case 'RGR': // Regiepress
			$base_table = 'rgp_wallboard';
			break;
		case 'LPI': // SDVP le Parisien SDE
			$base_table = 'sdvp_wallboard';
			break;
		case 'SGS': // Sogec CGOS
			$base_table = 'sgc_cgos_wallboard';
			break;
		case 'BOU': // Sogec RCBT
			$base_table = 'sgc_wallboard';
			break;
		case 'OUT': // Outillage Online
			$base_table = 'oo_wallboard';
			break;
		case 'PRS': // Priceminister
			$base_table = 'pm_wallboard';
			break;
		case 'ICT': // Priceminister
			$base_table = 'icp_wallboard';
			break;
		default:
			$base_table = '';
			break;
	}
	if($base_table != '')
	{
		$table = getWallboard($code,$base_table,$date_deb,$date_fin);
	}
	return $table;
}

function getWallboard($code,$base_table,$date_deb,$date_fin)
{
	global $conn_tdb;
	$table = array();
	if($code == 'DLM')
	{
		$sql_tdb = "select sum(dmc)/count(dmc) dmc, sum(acw)/count(acw) acw, 
					(sum(dmc)/count(dmc))+(sum(acw)/count(acw)) as total_seconde, 
					((sum(dmc)/count(dmc))+(sum(acw)/count(acw)))/60 as total_minute, 
					sum(calls_total_count) appel_entrant, sum(calls_handled_total_count) appel_pris, 
					 (sum(calls_handled_total_count)::float / sum(calls_total_count)::float)::float as qs
					 from (
					 
					select *
					from dlm1_wallboard where mesure_datetime in (
					select max(mesure_datetime) date_time_appel 
					from dlm1_wallboard where mesure_datetime::date >= '".$date_deb."' 
					and mesure_datetime::date <= '".$date_fin."'
					group by mesure_datetime::date
					order by mesure_datetime::date 
					) 

					union 

					select *
					from dlm2_wallboard where mesure_datetime in (
					select max(mesure_datetime) date_time_appel 
					from dlm2_wallboard where mesure_datetime::date >= '".$date_deb."' 
					and mesure_datetime::date <= '".$date_fin."'
					group by mesure_datetime::date
					order by mesure_datetime::date 
					) 

					union 

					select *
					from dlm3_wallboard where mesure_datetime in (
					select max(mesure_datetime) date_time_appel 
					from dlm3_wallboard where mesure_datetime::date >= '".$date_deb."' 
					and mesure_datetime::date <= '".$date_fin."'
					group by mesure_datetime::date
					order by mesure_datetime::date 
					) 

					order by mesure_datetime

					) as req";
	}
	elseif($code == 'SGS')
	{
		$sql_tdb = "select sum(dmc)/count(dmc) dmc, sum(acw)/count(acw) acw, 
					(sum(dmc)/count(dmc))+(sum(acw)/count(acw)) as total_seconde, 
					((sum(dmc)/count(dmc))+(sum(acw)/count(acw)))/60 as total_minute, 
					sum(calls_total_count) appel_entrant, sum(calls_handled_total_count) appel_pris, 
					 (sum(calls_handled_total_count)::float / sum(calls_total_count)::float)::float as qs
					 from (
					 
					select *
					from sgc_cgos_wallboard where mesure_datetime in (
					select max(mesure_datetime) date_time_appel 
					from sgc_cgos_wallboard where mesure_datetime::date >= '".$date_deb."' 
					and mesure_datetime::date <= '".$date_fin."'
					group by mesure_datetime::date
					order by mesure_datetime::date 
					) 

					union 

					select *
					from sgc_cgos_ddi_wallboard where mesure_datetime in (
					select max(mesure_datetime) date_time_appel 
					from sgc_cgos_ddi_wallboard where mesure_datetime::date >= '".$date_deb."' 
					and mesure_datetime::date <= '".$date_fin."'
					group by mesure_datetime::date
					order by mesure_datetime::date 
					) 

					order by mesure_datetime

					) as req";
	}
	else
	{
		$sql_tdb = "select sum(dmc)/count(dmc) dmc, sum(acw)/count(acw) acw, 
					(sum(dmc)/count(dmc))+(sum(acw)/count(acw)) as total_seconde, 
					((sum(dmc)/count(dmc))+(sum(acw)/count(acw)))/60 as total_minute, 
					sum(calls_total_count) appel_entrant, sum(calls_handled_total_count) appel_pris, 
					 (sum(calls_handled_total_count)::float / sum(calls_total_count)::float)::float as qs
					from ".$base_table." where mesure_datetime in (
					select max(mesure_datetime) date_time_appel
					from ".$base_table." where mesure_datetime::date >= '".$date_deb."' 
					and mesure_datetime::date <= '".$date_fin."' 
					group by mesure_datetime::date
					order by mesure_datetime::date 
					)";
	}
	
	$query = pg_query( $conn_tdb, $sql_tdb ) or die(pg_last_error());

	$result = pg_fetch_array($query);
	$table['sum_appel_pris'][$code] = $result['appel_pris'];
	$table['sum_appel_entrant'][$code] = $result['appel_entrant'];
	$table['qs'][$code] = $result['qs'];
	if($result['total_minute'] >= 60)
	{
		$table['heure_dmt'][$code] = (int)($result['total_minute'] / 60); // En heure
		$table['minute_dmt'][$code] = (($result['total_minute'] / 60) - $table['heure_dmt'][$code]) * 60;
		$table['seconde_dmt'][$code] = $table['minute_dmt'][$code] - (int)$table['minute_dmt'][$code];
		$table['minute_dmt'][$code] = (int)$table['minute_dmt'][$code];
		$table['seconde_dmt'][$code] = round($table['seconde_dmt'][$code] * 60);
	}
	else
	{
		$table['heure_dmt'][$code] = '00';
		$table['minute_dmt'][$code] = (int)$result['total_minute'];
		$table['seconde_dmt'][$code] = round(($result['total_minute'] - $table['minute_dmt'][$code]) * 60);
	}
	
	if(strlen($table['minute_dmt'][$code]) == 1)
	{
		$table['minute_dmt'][$code] = '0'.$table['minute_dmt'][$code];
	}
	if(strlen($table['seconde_dmt'][$code]) == 1)
	{
		$table['seconde_dmt'][$code] = '0'.$table['seconde_dmt'][$code];
	}
	return $table;
}

function getHeureTravaille($code,$date_deb,$date_fin)
{
	global $conn;
	$sql = "select  code3,  
       sum(duree) as duree  from 
(  SELECT matricule, 
	CASE
	    WHEN idcommande::text !~~ '021%'::text AND substr(idcommande::text, 1, 1) = '0'::text THEN substr(idcommande::text, 2, 3)::character varying
	    ELSE substr(idcommande,1,3)
	END AS code3,
	deptcourant, fonctioncourante,
       prenompersonnel, deb
     ,
     case when date_part('day'::text, (duree)*24)+date_part('hour'::text, (duree)) +( date_part('minutes'::text, (duree)) / 60::double precision)+ ( date_part('seconds'::text, duree) / 3600)::double precision > 15
     then 8 else 
     date_part('day'::text, (duree)*24)+date_part('hour'::text, (duree)) +( date_part('minutes'::text, (duree)) / 60::double precision)+ ( date_part('seconds'::text, duree) / 3600)::double precision 
     end as duree
  FROM duree_prod_par_matricule_dept_ca where deb >= '".$date_deb."' and deb <='".$date_fin."'
  and (fonctioncourante ='TC' or fonctioncourante ='CONSEILLER' or fonctioncourante ='FONC_MAIL')
  order by matricule
  )as res
  where code3 = '".$code."'
  group by  code3  
  order by code3";
  	$query = pg_query( $conn, $sql ) or die(pg_last_error($conn));
	$result = pg_fetch_array($query);
	//return (float)(number_format($result['duree'],2));
	return $result['duree'];
}

function getDatesBetween ($dStart, $dEnd, $rep) {
	//affichage des dates au format français.
	$aDates = array();
	setlocale(LC_TIME,"fr_FR");
    $iStart = strtotime ($dStart);
    $iEnd = strtotime ($dEnd);
    if (false === $iStart || false === $iEnd) {
        return false;
    }
    $aStart = explode ('-', $dStart);
    $aEnd = explode ('-', $dEnd);
    if (count ($aStart) !== 3 || count ($aEnd) !== 3) {
        return false;
    }
    if (false === checkdate ($aStart[1], $aStart[2], $aStart[0]) || false === checkdate ($aEnd[1], $aEnd[2], $aEnd[0]) || $iEnd <= $iStart) {
        return false;
    }
    for ($i = $iStart; $i < $iEnd + 86400; $i = strtotime ('+1 day', $i) ) {
        //$sDate = strftime ('%B**%A**%W**%d-%m-%Y', $i);
        $sDateToArr = strftime ('%Y-%m-%d', $i);
        $sYear = strftime ('%Y', $i);
        //$sMonth = strftime ('%m', $i);
        $sMonth = strftime ('%B', $i);
        $sDate = strftime ('%d', $i);
        $sNumWeek = strftime ('%W', $i);
        $sNomJr = strftime ('%A', $i);
        $sNomMois = strftime ('%B', $i);
        //$sYear = substr ($sDateToArr, 0, 4);
        //$sMonth = substr ($sDateToArr, 5, 2);
        if($rep == 'semaine')
        {
			//$aDates[$sYear][$sMonth][$sNumWeek][] = $sDateToArr.$sNomJr;
			$val_numW = (int)($sNumWeek) + 1;
			$val_numY = (int)($sYear);
			if($val_numW == 53)
			{
				$sNumWeek = 1;
				$sYear = $val_numY + 1;
			}
			else
			{
				$sNumWeek = $val_numW;
			}
			$aDates[$sYear][$sNumWeek][] = $sDateToArr;
		}
        else if($rep == 'mois')
        {
			$aDates[$sYear][$sMonth][] = $sDateToArr; 
		}
    }
    if (isset ($aDates) && !empty ($aDates)) {
        return $aDates;
    } else {
        return false;
    }
}

function __getWallboard($code,$base_table,$date_deb,$date_fin)
{
	global $conn_tdb;
	$table = array();
	$sql_tdb = "select sum(appel_entrant)::integer sum_appel_entrant,sum(appel_pris)::integer sum_appel_pris,
	(sum(appel_pris)::float/sum(appel_entrant)::float)::float as qs 
	from (
	select max(qs) qs,mesure_datetime::date date_appel, max(calls_total_count) appel_entrant, max(calls_handled_total_count) appel_pris 
	from ".$base_table." 
	where mesure_datetime::date >= '".$date_deb."' and mesure_datetime::date <= '".$date_fin."'
	group by mesure_datetime::date
	order by mesure_datetime::date ) as req1 ";
	$query = pg_query( $conn_tdb, $sql_tdb ) or die(pg_last_error());

	$result = pg_fetch_array($query);
	$table['sum_appel_pris'][$code] = $result['sum_appel_pris'];
	$table['sum_appel_entrant'][$code] = $result['sum_appel_entrant'];
	$table['qs'][$code] = $result['qs'];
	return $table;
}

function _getWallboard($code,$base_table,$date_deb,$date_fin)
{
	global $conn_tdb;
	global $table;
	$sql_tdb = "select max(qs) qs,mesure_datetime::date date_appel, 
	max(calls_total_count) appel_entrant, max(calls_handled_total_count) appel_pris 
	from ".$base_table." 
	where mesure_datetime::date >= '".$date_deb."' and mesure_datetime::date < '".$date_fin."'
	group by mesure_datetime::date";
	$query = pg_query( $conn_tdb, $sql_tdb ) or die(pg_last_error());

	$table['appel_pris'] = 0;
	$table['appel_entrant'] = 0;
	while($result = pg_fetch_array($query))
	{
		$table['appel_pris'][$code] += $result['appel_pris'];
		$table['appel_entrant'][$code] += $result['appel_entrant'];
	}
	$table['qs'][$code] = $table['appel_pris'] / $table['appel_entrant'] * 100;
	return $table;
}

//function ecrire($a,$objWorksheet1,$listeColExcel,$traitement_abrev,$id_projet, $id_client,$id_application,$key_trait,$aDateDeb,$aDateFin,$aDateDebNot,$aDateFinNot,$annee,$mois,$repartition)
function ecrire($a,$objWorksheet1,$listeColExcel,$id_projet, $id_client,$id_application,$aDateDeb,$aDateFin,$aDateDebNot,$aDateFinNot,$annee,$mois,$repartition)
{
	/* ********** STYLE ************************ */
	include('export_style.php');
	/* **************************************** */

	/*$objPHPExcel->getSheet($a);
	$objPHPExcel->setActiveSheetIndex($a);*/
	
	if($repartition == 'mois'){
		$titleSheet = utf8_encode($annee.' - '.$mois);
	}else if($repartition == 'semaine'){
		$titleSheet = utf8_encode($annee.' - Sem '.$mois);
	}else if($repartition == 'total'){
		$titleSheet = utf8_encode('GLOBAL');
	}
	//echo '<pre>'.print_r($objWorksheet1).'</pre>'; echo '<br>rrrrrrrrrrrrr';
	$objWorksheet1->setTitle($titleSheet);
	$objWorksheet1->getSheetView()->setZoomScale(80);
	$objWorksheet1->freezePane ('E5');
//echo '<br><br>XXXXXXXXXXXXXXXXXXXXXX<br><br>';  exit;
	$icell     = 4;
	$num_ligne = 3;
	/**
	* 
	* @var ******************** Afficher les titres *********************************
	* 
	*/
	$result_repartition = fetchAllRepartition();
	$nb_repartition     = pg_num_rows($result_repartition);
	$table              = getAllListTitle();
	// echo '<pre>';
	// print_r($table);
	// echo '</pre>';
	// exit;
	foreach($table as $val_titre) {
		if ($val_titre != 'vt'){
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_categorie);
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_font);
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_centre);
		}
		
		if($val_titre == 'SI')
		{
			$objWorksheet1->mergeCells($listeColExcel[$icell].$num_ligne.':'.$listeColExcel[$icell+$nb_repartition-1].$num_ligne);
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne.':'.$listeColExcel[$icell+$nb_repartition-1].$num_ligne)->applyFromArray($style_border);
			$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$val_titre);
			// echo $listeColExcel[$icell].$num_ligne.' '.$val_titre.' 1<br/>';
			$icell++;
			$icell = $icell+$nb_repartition-1;
		}
		else if($val_titre == 'ip6')
		{
			$objWorksheet1->mergeCells($listeColExcel[$icell].$num_ligne.':'.$listeColExcel[$icell+1].$num_ligne);
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne.':'.$listeColExcel[$icell+1].$num_ligne)->applyFromArray($style_border);
			$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$val_titre);
			// echo $listeColExcel[$icell].$num_ligne.' '.$val_titre.' 2<br/>';
			$icell = $icell+2;
		}
		else
		{
			if ($val_titre != 'vt'){
				$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_border);
			}
		
			// if($val_titre == 'vt' || $val_titre == 'x' || $val_titre == 'hw')
			if($val_titre == 'vt'){
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,"");
			}else{
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,substr($val_titre,0,3));
			}
			
			// echo $listeColExcel[$icell].$num_ligne.' '.$val_titre.' 3<br/>';
			$icell++;
			
		}
	}
	$num_ligne++;
	$icell = 4;
	/**
	* 
	* @var ******************* Afficher les sous-titres *****************************
	* 
	*/
	$soustable = setLibelleForTitle($table);
	foreach($soustable as $val_soustitre)
	{
		$objWorksheet1->getRowDimension($num_ligne)->setRowHeight(60);
		$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(13);
		$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_categorie);
		$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_font);
		$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_border);
		$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_centre);
		$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$val_soustitre);$icell++;
	}

	$num_ligne++;
	$tab_typetrait = array();
	foreach($traitement_abrev as $key_trait => $val_trait)
	{	
		/* ********************************************************************** */
		/* ************* Prendre les données indispensables ********************* */
		/* ********************************************************************** */
		$tableauPrest = setTableauSynthesePrestation($id_projet, $id_client,$id_application,$key_trait,$aDateDeb,$aDateFin,0,0,0);
		$nb_valeur = count($tableauPrest);
		
		// ********* Mettre la fourchette de date *************************** //
		$objWorksheet1->getColumnDimension('A1')->setWidth(10);
		$objWorksheet1->setCellValue('B4',$aDateDebNot.' au '.$aDateFinNot);
		$objWorksheet1->getStyle('B4')->applyFromArray($style_fourchette_date);
		$objWorksheet1->getStyle('B4')->applyFromArray($style_centre);
		// ****************************************************************** //
		
		if($nb_valeur != 0)
		{
		$result1 = fetchAllTLCNotation(0, 0,0,$key_trait,$aDateDeb,$aDateFin,0,0,0);
		$nb_row = pg_num_rows( $result1 );
		$array_test = array();
		$somme_total_general=0;
		$nb_eval = 0;
		$tableau_valeur = array();
		for ($j=0;$j<$nb_row;$j++)
		{	
			$lg = pg_fetch_array( $result1 , $j );
			if($j != $nb_row -1) $lg_next = pg_fetch_array( $result1 , $j+1 );
					
			$id_notation =  $lg['id_notation'];
			$id_projet =  $lg['id_projet'];
			$id_client =  $lg['id_client'];
			$id_application =  $lg['id_application'];

			/*$str = calculTotalGeneral($id_projet, $id_client, $id_application, $id_notation, $key_trait);
			$table_valeur = explode('||',$str); */
			$valnote = $lg['note'];

			if(isset($tableau_valeur[$key_trait][$id_projet]['note'])) 
			{
				//$tableau_valeur[$key_trait][$id_projet]['note'] +=  $table_valeur[0];
				$tableau_valeur[$key_trait][$id_projet]['note'] += $valnote;
			}
			else
			{
				$tableau_valeur[$key_trait][$id_projet]['note'] = $valnote;
			}
			if(isset($tableau_valeur[$key_trait][$id_projet]['nbEval'])) $tableau_valeur[$key_trait][$id_projet]['nbEval'] += 1;	  
			else $tableau_valeur[$key_trait][$id_projet]['nbEval'] = 1;	  
		}
		/* ************************************************************** **/
		/* ************************************************************** **/
		/* ************************************************************** **/
		
		$icell = 2;
		$sauv_num_ligne = $num_ligne;
		
		foreach($tableauPrest as $key_type => $tab_type)
		{
			foreach($tab_type as $key_code => $tab_code)
			{
				$col = 1;
				$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
				$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(25);
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$tab_code['client']);$icell++;
				$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
				$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_centre);
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$key_code);$icell++;
				foreach($table as $val_titre)
				{
					$is = $val_titre;
					if($is == 'SI')
					{
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
							if(isset($repartition_total[$res_rep['id_repartition']])) $repartition_total[$res_rep['id_repartition']] += $val_rep;
							else $repartition_total[$res_rep['id_repartition']] = $val_rep;
							$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_contenu_categorie);
							$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$val_rep);
							$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_centre);
							$icell++;
						}
					}
					// else if($is == 'is4' || $is == 'is5' || $is == 'is5_v7' || $is == 'is6' || $is == 'is7')
					else if($is == 'is4' || $is == 'is5_v7' || $is == 'is6' || $is == 'is7')
					{
						$tab_critere[$is] = array();
						$liste_critere = array();
						if(isset($tab_code['libelle_grille'][$is]))
						{
							foreach($tab_code['libelle_grille'][$is] as $_key => $_val)
							{
								if(!in_array($_key,$tab_critere[$is]))
								{
									array_push($tab_critere[$is],$_key);
								}
							}
						}

						if(count($tab_critere[$is]) == 0)
						{
							$val_is = 'NE';
						}
						else if(isset($tab_code['indicateur_nf'][$is]))
						{
							$val_is = $tab_code['indicateur_nf'][$is];
						}
						else
						{
							$val_is = 'NE';
						}
						$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_contenu_IS);
						$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($tdb_style_gras);
						if($val_is != 'NE')
						{
							//$is_total[$valnf] += $val_is * $nb_eval;
							//$tot[$valnf] += $nb_eval;
							$v = $val_is /100;
							// if(($v < 0.60 && $is == 'is4') || ($v < 0.85 && $is == 'is5') || ($v < 0.85 && $is == 'is5_v7') || ($v < 0.85 && $is == 'is6') || ($v < 0.80 && $is == 'is7'))
							if(($v < 0.60 && $is == 'is4') || ($v < 0.85 && $is == 'is5_v7') || ($v < 0.85 && $is == 'is6') || ($v < 0.80 && $is == 'is7'))
							{
								$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($tdb_style_eval);
							}
							$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
							
							/*if( $is =='is5' || $is =='IS5' )
							{
								$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,"");
							}
							else
							{*/
							$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$val_is/100);
							// }
							
						}
						else
						{
							//$is_total[$valnf] = $val_is;
							
							/*if( $is =='is5' || $is =='IS5' )
							{
								$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,"");
							}
							else
							{*/
							$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$val_is);
							// }
							
							
						}

						$icell++;
					}
					else if($is == 'vt' || $is == 'is2' || $is == 'ip6')
					{
						if($val_trait == 'AE')
						{
							$_table = getValeurForPrestation($key_code,$aDateDeb,$aDateFin);
							$volume = '';
							$qs = '';
							$dmt_realise = '';
							$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($tdb_style_type);
							// Volume traité pour Appel entrant
							if($is == 'vt') 
							{
								if(isset($_table['sum_appel_pris'][$key_code]))
								{
									$volume = $_table['sum_appel_pris'][$key_code];
								}
								$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$volume);
							}
							
							// QS pour appel entrant
							if($is == 'is2') 
							{
								if(isset($_table['qs'][$key_code]))
								{
									$qs = $_table['qs'][$key_code];
								}
								if($qs < 0.90 && $qs != '')
								{
									$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($tdb_style_eval);
								}
								$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
								$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$qs);
							}
							if($is == 'ip6')
							{
								if($col == 1) // Objectif DMT
								{
									$col++; 
									$icell++; 
								}
								if($col == 2) // DMT réalisé
								{
									if(isset($_table['minute_dmt'][$key_code]))
									{
										$dmt_realise = $_table['heure_dmt'][$key_code].':'.$_table['minute_dmt'][$key_code].':'.$_table['seconde_dmt'][$key_code];
									}
									$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_right);
									$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($tdb_style_type);
									$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_minute_seconde );
									$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$dmt_realise);
								}
							}
						}
						else if($val_trait == 'AS') // Volume traité et QS pour Appel sortant
						{
							$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($tdb_style_type);
							if($is == 'is2' || $is == 'vt')
							{
								$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
							}
							if($is == 'ip6')
							{
								if($col == 1) // Objectif DMT
								{
									$col++; 
									$icell++; 
								}
								if($col == 2) // DMT réalisé
								{
									$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
								}
							}
							
						}
						else if(($val_trait == 'MAIL') || ($val_trait == 'TCHAT'))
						{
							$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($tdb_style_type);
							if($is == 'ip6')
							{
								if($col == 1) // Objectif DMT
								{
									$col++; 
									$icell++; 
								}
								if($col == 2) // DMT réalisé
								{
									$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($tdb_style_type);
								}
							}
							// QS pour Mail
							if($is == 'is2') 
							{
								$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
							}
						}
						$icell++;
					}
					else 
					{
						$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($tdb_style_type);
						$icell++;
					}
				}
				$_icell = $icell;
				$num_ligne++;
				$icell = 2;
			}
		}
		$objWorksheet1->mergeCells($listeColExcel[1].$sauv_num_ligne.':'.$listeColExcel[1].($num_ligne-1));
		$objWorksheet1->setCellValue($listeColExcel[1].$sauv_num_ligne,$val_trait);
		$objWorksheet1->getStyle($listeColExcel[1].$sauv_num_ligne)->applyFromArray($style_matricule);
		$objWorksheet1->getStyle($listeColExcel[1].$sauv_num_ligne)->applyFromArray($style_centre);
		$tab_typetrait[$val_trait]['debut'] = $sauv_num_ligne;
		$tab_typetrait[$val_trait]['fin'] = $num_ligne-1;
		}
		/* ************** Séparateur *******************************/
		$objWorksheet1->getRowDimension($num_ligne)->setRowHeight(3);
		for($i=1;$i<$_icell;$i++)
		{
			$objWorksheet1->getStyle($listeColExcel[$i].$num_ligne)->applyFromArray($tdb_style_separateur);
		}
		$num_ligne++;
		/***********************************************************/
		
	}
	$icell = 4;
	$icell_vt = 4;
	$deb_ligne = 5;
	$fin_ligne = $num_ligne-2;
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sum('.$listeColExcel[$icell].$deb_ligne.':'.$listeColExcel[$icell].$fin_ligne.')');
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sumproduct('.$listeColExcel[$icell].$tab_typetrait['AE']['debut'].':'.$listeColExcel[$icell].$tab_typetrait['AE']['fin'].';'.$listeColExcel[$icell_vt].$tab_typetrait['AE']['debut'].':'.$listeColExcel[$icell_vt].$tab_typetrait['AE']['fin'].')/sum('.$listeColExcel[$icell_vt].$tab_typetrait['AE']['debut'].':'.$listeColExcel[$icell_vt].$tab_typetrait['AE']['fin'].')');
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sumproduct('.$listeColExcel[$icell].$tab_typetrait['AE']['debut'].':'.$listeColExcel[$icell].$tab_typetrait['AE']['fin'].';'.$listeColExcel[$icell_vt].$tab_typetrait['AE']['debut'].':'.$listeColExcel[$icell_vt].$tab_typetrait['AE']['fin'].')/sum('.$listeColExcel[$icell_vt].$tab_typetrait['AE']['debut'].':'.$listeColExcel[$icell_vt].$tab_typetrait['AE']['fin'].')');
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sumproduct('.$listeColExcel[$icell].$tab_typetrait['AE']['debut'].':'.$listeColExcel[$icell].$tab_typetrait['AE']['fin'].';'.$listeColExcel[$icell_vt].$tab_typetrait['AE']['debut'].':'.$listeColExcel[$icell_vt].$tab_typetrait['AE']['fin'].')/sum('.$listeColExcel[$icell_vt].$tab_typetrait['AE']['debut'].':'.$listeColExcel[$icell_vt].$tab_typetrait['AE']['fin'].')');
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sumproduct('.$listeColExcel[$icell].$deb_ligne.':'.$listeColExcel[$icell].$fin_ligne.';'.$listeColExcel[$icell_vt].$deb_ligne.':'.$listeColExcel[$icell_vt].$fin_ligne.')/sum('.$listeColExcel[$icell_vt].$deb_ligne.':'.$listeColExcel[$icell_vt].$fin_ligne.')');
	$icell++;
	
	// $objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
	// $objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	// $objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,"");
	// $icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sumproduct('.$listeColExcel[$icell].$deb_ligne.':'.$listeColExcel[$icell].$fin_ligne.';'.$listeColExcel[$icell_vt].$deb_ligne.':'.$listeColExcel[$icell_vt].$fin_ligne.')/sum('.$listeColExcel[$icell_vt].$deb_ligne.':'.$listeColExcel[$icell_vt].$fin_ligne.')');
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sumproduct('.$listeColExcel[$icell].$deb_ligne.':'.$listeColExcel[$icell].$fin_ligne.';'.$listeColExcel[$icell_vt].$deb_ligne.':'.$listeColExcel[$icell_vt].$fin_ligne.')/sum('.$listeColExcel[$icell_vt].$deb_ligne.':'.$listeColExcel[$icell_vt].$fin_ligne.')');
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sumproduct('.$listeColExcel[$icell].$deb_ligne.':'.$listeColExcel[$icell].$fin_ligne.';'.$listeColExcel[$icell_vt].$deb_ligne.':'.$listeColExcel[$icell_vt].$fin_ligne.')/sum('.$listeColExcel[$icell_vt].$deb_ligne.':'.$listeColExcel[$icell_vt].$fin_ligne.')');
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sum('.$listeColExcel[$icell].$deb_ligne.':'.$listeColExcel[$icell].$fin_ligne.')');
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sum('.$listeColExcel[$icell].$deb_ligne.':'.$listeColExcel[$icell].$fin_ligne.')');
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sum('.$listeColExcel[$icell].$deb_ligne.':'.$listeColExcel[$icell].$fin_ligne.')');
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sum('.$listeColExcel[$icell].$deb_ligne.':'.$listeColExcel[$icell].$fin_ligne.')');
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=sum('.$listeColExcel[$icell].$deb_ligne.':'.$listeColExcel[$icell].$fin_ligne.')');
	$icell++;
	
}

?>