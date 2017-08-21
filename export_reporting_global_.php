<?php 

require_once 'PHPExcel/IOFactory.php';
include_once 'PHPExcel/Writer/Excel5.php';
include_once 'PHPExcel/Writer/Excel2007.php';
require_once 'PHPExcel.php';
//require_once 'incfunc.php';
include("/var/www.cache/dgconn.inc");
include('connectTDB.php');
include('function_synthese_dynamique.php');
include('function_dynamique.php');
include('function_tdb.php');
//include("/var/www.cache/siapconn.inc");
//include("/var/www.cache/rhconn.inc");
ini_set('max_execution_time', 0);
set_time_limit(0);

$fichier = 'reporting_global.xls';
$inputFileName = 'reporting/'.$fichier;
$dossier = 'reporting/';

if (!file_exists($inputFileName)) {
	exit("Please run 14excel5.php first.\n");
}

function recuperer_next_vendrediJolie ($debut){ 
	$boolean = "false";
	$ann = substr($debut,0,4);
	$mois = substr($debut,5,2);
	$jour = substr($debut,8,2);
	$datetest = $ann."-".$mois."-".$jour;
	$daty = mktime( 0,0,0,date($mois)  ,date($jour) ,date($ann)    );
	$j = "4 day";
	$datetest = date('Y-m-d', strtotime($debut. $j));
	return $datetest;
}

function weekNumber( $ddate ){
  $week = date("W", strtotime($ddate));
  return $week;

}

$date_deb_notation  = $_REQUEST['date_deb'];
$date_fin_notation  = $_REQUEST['date_fin'];
//echo $date_fin_notation;exit;
$id_projet          = $_REQUEST['projet'];
$id_client          = $_REQUEST['client'];
$id_application     = $_REQUEST['application'];
$id_type_traitement = $_REQUEST['type_traitement'];
$matricule_auditeur = $_REQUEST['auditeur'];
$matricule_tlc      = $_REQUEST['tlc'];

$deb = explode('/',$date_deb_notation);
$fin = explode('/',$date_fin_notation);
$ftxt_dtdeb_ = $deb[2].'-'.$deb[1].'-'.$deb[0];
//$date_fin_notation = recuperer_next_vendrediJolie ($ftxt_dtdeb_);
$ftxt_dtfin_ = $fin[2].'-'.$fin[1].'-'.$fin[0];

//$fin = explode('/',$date_fin_notation);
//$ftxt_dtfin_ = $fin[2].'-'.$fin[1].'-'.$fin[0];

$listeColExcel = array("1" => "A","2" => "B" ,"3" => "C" ,"4" => "D" ,"5" => "E" ,"6" => "F" ,"7" => "G" ,"8" => "H" ,"9" => "I" ,"10" => "J" ,"11" => "K" ,"12" => "L" ,"13" => "M" ,"14" => "N" ,"15" => "O" ,"16" => "P" ,"17" => "Q" ,"18" => "R" ,"19" => "S" ,"20" => "T" ,"21" => "U" ,"22" => "V" ,"23" => "W" ,"24" => "X" ,"25" => "Y" ,"26" => "Z" ,"27" => "AA" ,"28" => "AB" ,"29" => "AC" ,"30" => "AD" ,"31" => "AE" ,"32" => "AF" ,"33" => "AG" ,"34" => "AH" ,"35" => "AI" ,"36" => "AJ" ,"37" => "AK" ,"38" => "AL" ,"39" => "AM" ,"40" => "AN" ,"41" => "AO" ,"42" => "AP" ,"43" => "AQ" ,"44" => "AR","45" => "AS","46" => "AT","47" => "AU","48" => "AV","49" => "AW","50" => "AX","51" => "AY","52" => "AZ","53" => "BA","54" => "BB","55" => "BC","56" => "BD","57" => "BE","58" => "BF","59" => "BG","60" => "BH","61" => "BI","62" => "BJ","63" => "BK","64" => "BL","65" => "BM","66" => "BN","67" => "BO","68" => "BP","69" => "BQ","70" => "BR","71" => "BS","72" => "BT","73" => "BU","74" => "BV","75" => "BW","76" => "BX","77" => "BY","78" => "BZ","79" => "CA","80" => "CB","81" => "CC","82" => "CD","83" => "CE","84" => "CF","85" => "CG","86" => "CH","87" => "CI","88" => "CJ","89" => "CK","90" => "CL","91" => "CM","92" => "CN","93" => "CO","94" => "CP","95" => "CQ","96" => "CR","97" => "CS","98" => "CT","99" => "CU","100" => "CV","101" => "CW","102" => "CX","103" => "CY","104" => "CZ","105" => "DA","106" => "DB","107" => "DC","108" => "DD","109" => "DE","110" => "DF","111" => "DG","112" => "DH","113" => "DI");

$headers1 = array("Type de traitement","Code","Nom client","Volume traité","Note","Nb Eval");

/* ********** STYLE ************************ */
include('export_style.php');
/* **************************************** */

$objet       = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objet->load($inputFileName);

$i = 0;
foreach($traitement_abrev as $key_trait => $val_trait){
	$tableauPrest = setTableauSynthesePrestation($id_projet, $id_client,$id_application,$key_trait,$ftxt_dtdeb_,$ftxt_dtfin_,0,0,0);
	//$nombre_client = pg_num_rows($tableauPrest);
	
	if($i > 0){
		$objWorksheet1 = $objPHPExcel->createSheet();
	}

	$objPHPExcel->getSheet($i);
	$objPHPExcel->setActiveSheetIndex($i);
	$objWorksheet1 = $objPHPExcel->getActiveSheet();
	$titleSheet = $val_trait;
	$objWorksheet1->setTitle($titleSheet);
	$objWorksheet1->getSheetView()->setZoomScale(80);
	$objPHPExcel-> getActiveSheet()->freezePane ('G4');
	
	/* ********************************************************************** */
	/* ************* Prendre les données indispensables ********************* */
	/* ********************************************************************** */
	// $tab_matricule = array();
	// $result = fetchAllTLCClient($id_projet,$id_client,$id_application,$key_trait,$ftxt_dtdeb_,$ftxt_dtfin_,0,0,0);
	// while($res = pg_fetch_array($result)){
		// $tab_matricule[] = $res['matricule'];
	// }

	//$tableauBord = setTableauSynthese($id_projet, $id_client,$id_application,$id_type_traitement,$ftxt_dtdeb_,$ftxt_dtfin_,0,0);

	$result1 = fetchAllTLCNotation(0, 0,0,$key_trait,$ftxt_dtdeb_,$ftxt_dtfin_,0,0,0);
	$nb_row  = pg_num_rows( $result1 );
	
	$array_test          = array();
	$somme_total_general = 0;
	$nb_eval             = 0;
	$tableau_valeur      = array();
	
	for ($j=0;$j<$nb_row;$j++){
		$lg = pg_fetch_array( $result1 , $j );
		if($j != $nb_row -1) $lg_next = pg_fetch_array( $result1 , $j+1 );
				
		$id_notation    =  $lg['id_notation'];
		$id_projet      =  $lg['id_projet'];
		$id_client      =  $lg['id_client'];
		$id_application =  $lg['id_application'];

		/*$str = calculTotalGeneral($id_projet, $id_client, $id_application, $id_notation, $key_trait);
		$table_valeur = explode('||',$str); */
		$valnote = $lg['note'];

		if(isset($tableau_valeur[$key_trait][$id_projet]['note'])) {
			//$tableau_valeur[$key_trait][$id_projet]['note'] +=  $table_valeur[0];
			$tableau_valeur[$key_trait][$id_projet]['note'] += $valnote;
		}else{
			$tableau_valeur[$key_trait][$id_projet]['note'] = $valnote;
		}
		
		if(isset($tableau_valeur[$key_trait][$id_projet]['nbEval'])) $tableau_valeur[$key_trait][$id_projet]['nbEval'] += 1;	  
		else $tableau_valeur[$key_trait][$id_projet]['nbEval'] = 1;	  
	}
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
	/**
	* 
	* @var ****************** Traitement des headers *************************
	* 
	*/
	$icell     = 1;
	$num_ligne = 2;
	$objWorksheet1->getRowDimension($num_ligne)->setRowHeight(180);
	$objWorksheet1->getRowDimension($num_ligne+1)->setRowHeight(26);
	$objWorksheet1->setCellValue('A1',$date_deb_notation);
	$objWorksheet1->setCellValue('B1',$date_fin_notation);
	$objWorksheet1->getStyle('A1:B1')->applyFromArray($style_fourchette_date);
	
	if(count($tableauPrest) != 0){	
		foreach($headers1 as $val_header){
			$objWorksheet1->setCellValue($listeColExcel[$icell].($num_ligne+1),utf8_encode($val_header));
			$objWorksheet1->getStyle($listeColExcel[$icell].($num_ligne+1))->applyFromArray($style_titre);
			$objWorksheet1->getStyle($listeColExcel[$icell].($num_ligne+1))->applyFromArray($style_border);
			$objWorksheet1->getStyle($listeColExcel[$icell].($num_ligne+1))->applyFromArray($style_font);
			$objWorksheet1->getStyle($listeColExcel[$icell].($num_ligne+1))->applyFromArray($style_centre);
			if($icell == 1 || $icell == 2){
				$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(15);
			}
			if($icell == 3){
				$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(20);
			}
			$icell++;
		}
		
		/**
		* 
		* @var ********** Liste des catégories *******************
		* 
		*/
		$table_id_cat = array();
		$table_keycat = array();
		foreach($tableauPrest[$key_trait] as $key_tab => $val_tab){
			foreach($val_tab['libelle_categorie_grille'] as $_key =>$_val){
				if(!in_array($_key,$table_keycat)){
					$objWorksheet1->mergeCells($listeColExcel[$icell].$num_ligne.':'.$listeColExcel[$icell].($num_ligne+1));
					$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($_val));
					$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_rotation);
					$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_categorie);
					$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_font);
					$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_border);
					$objWorksheet1->getStyle($listeColExcel[$icell].($num_ligne+1))->applyFromArray($style_border);
					
					$icell++;
					array_push($table_keycat,$_key);
					$table_id_cat[$_key] = $_val;
				}
			}
		}
		/**
		* 
		* @var ********** Liste des IS *********************
		* 
		*/
		$list_is = array();
		$tab_critere = array();
		$tableaunf = get_indicateur_nf();
		foreach($tableaunf as $keynf => $valnf){
			/* ********* Séparateur ************* */
			$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'');
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_separateur);
			$objWorksheet1->getStyle($listeColExcel[$icell].($num_ligne+1))->applyFromArray($style_separateur);
			$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
			$icell++;
			/* ********************************** */
			
			$objWorksheet1->mergeCells($listeColExcel[$icell].$num_ligne.':'.$listeColExcel[$icell].($num_ligne+1));
			
			
			if( $valnf =='is5' || $valnf =='IS5' ){
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,"");
			}else{
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode(substr($valnf,0,3)));
			}
			
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_rotation);
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_IS);
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_font);
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_border);
			$objWorksheet1->getStyle($listeColExcel[$icell].($num_ligne+1))->applyFromArray($style_border);
			$icell++;
			array_push($list_is,$valnf);
			$tab_critere[$valnf] = array();
			$liste_critere       = array();
			foreach($tableauPrest[$key_trait] as $key_tab => $val_tab){
				if(isset($val_tab['libelle_grille'][$valnf])){
					foreach($val_tab['libelle_grille'][$valnf] as $_key => $_val){
						if(!in_array($_key,$tab_critere[$valnf])){
							$objWorksheet1->mergeCells($listeColExcel[$icell].$num_ligne.':'.$listeColExcel[$icell].($num_ligne+1));
							$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($_val));
							$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_rotation);
							$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_categorie);
							$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_font);
							$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_border);
							$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].($num_ligne+1))->applyFromArray($style_border);
							array_push($tab_critere[$valnf],$_key);
							$icell++;
						}
					}
				}
			}
		} 
		/* ********* Séparateur ************* */
		$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'');
		$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_separateur);
		$objWorksheet1->getStyle($listeColExcel[$icell].($num_ligne+1))->applyFromArray($style_separateur);
		$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
		$icell++;
		/* ********************************** */
		/**
		* 
		* @var ********** Liste des répartitions ************************
		* 
		*/
		$result_repartition = fetchAllRepartition();
		$nb_repartition     = pg_num_rows($result_repartition);
		
		while($res_rep = pg_fetch_array($result_repartition)){
			$objWorksheet1->mergeCells($listeColExcel[$icell].$num_ligne.':'.$listeColExcel[$icell].($num_ligne+1));
			$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($res_rep['libelle_repartition']));
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_rotation);
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_categorie);
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_font);
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_border);
			$objWorksheet1->getStyle($listeColExcel[$icell].($num_ligne+1))->applyFromArray($style_border);
			$icell++;
		}
		$num_ligne++;
		$icell = 1;

/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
		/**
		* 
		* @var ********************** VALEUR DU CONTENU **************************
		* 
		*/
		$num_ligne++;
		$list_is_valeur     = array();
		$nb_eval_total      = 0;
		$note_total         = 0;
		$val_cat_total      = array();
		$is_total           = array();
		$is_detail_total    = array();
		$repartition_total  = array();
		$valeur_note_global = 0;
		$volume_total       = 0;
		foreach($tableauPrest as $key_type => $tab_type){
			foreach($tab_type as $key_code => $tab_code){
				$list_series = array();
				if($key_type == 1) $libelle_type_traitement = 'AE';
				if($key_type == 2) $libelle_type_traitement = 'AS';
				if($key_type == 3) $libelle_type_traitement = 'Mail';
				if($key_type == 4) $libelle_type_traitement = 'Tchat';
				
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_centre);
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($libelle_type_traitement));
				$icell++;
				
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_centre);
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($tab_code['prestation']));
				$icell++;
				
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_CC);
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($tab_code['client']));
				$icell++;
				
				// ici volume traité
				$_eval_test = $tableau_valeur[$key_type][$tab_code['id_projet']]['nbEval'];
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_note);
				if($key_type == 1){
					$_table = getValeurForPrestation($key_code,$ftxt_dtdeb_,$ftxt_dtfin_);
					if(isset($_table['sum_appel_pris'][$key_code])){
						$volume = $_table['sum_appel_pris'][$key_code];
						$volume_total += $volume;
						$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$volume);
					}
				}
				//$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($_eval_test));
				$icell++;
				
				$note_projet = $tableau_valeur[$key_type][$tab_code['id_projet']]['note'];
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_note);
				$valeur_note_global = $note_projet/$_eval_test;
				if(($key_type == 1 || $key_type == 2) && ($valeur_note_global > 10)){
					$valeur_note_global = $valeur_note_global / 10;
				}
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,number_format($valeur_note_global,1));
				$icell++;
				
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_note);
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($tab_code['nb_evaluation']));
				$icell++;
				
				$nb_eval = $tab_code['nb_evaluation'];
				$nb_eval_total += $nb_eval;
				//$note_total += ($note_projet/$_eval_test) * $nb_eval;
				$note_total += $valeur_note_global * $nb_eval;
				/**
				* 
				* @var ******************* Par categorie ***********************
				* 
				*/
				//if($key_type == 1 && $tab_code['id_client'] == 643) {echo 'ato ';}
				$tableau_init_cat = array();
				foreach($table_id_cat as $key_idcat => $val_idcat){
					if(isset($tab_code['som_by_id_projet'][$key_idcat])){
						$val_tab = number_format($tab_code['som_by_id_projet'][$key_idcat],2);
						if(($key_type == 1 || $key_type == 2) && ( $tab_code['id_client'] == 643 || $tab_code['id_client'] == 642)){
							$val_tab = number_format($tab_code['som_by_id_projet'][$key_idcat]/10,2);
						}
					}else{
						$val_tab = 'NE';
					}
					$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->setFormatCode('#,##0.0');
					$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_contenu_categorie);
					
					//commenter par haingo
					//$objWorksheet1->getStyle('B'.$listeColExcel[$icell].$num_ligne)->applyFromArray($font_calcul);
					
					$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$val_tab);
					
					// $product =  '=PRODUCT(F'.$num_ligne.';'.$listeColExcel[$icell].$num_ligne.')';
					/*if( $val_tab =='NE' ){
					      $objWorksheet1->setCellValue('B'.$listeColExcel[$icell].$num_ligne,'NE');
					   }
					else{
					   $objWorksheet1->setCellValue('B'.$listeColExcel[$icell].$num_ligne,$product);
					}*/
					
					$icell++;
					if($val_tab != 'NE'){
						if(isset($val_cat_total[$key_idcat])) $val_cat_total[$key_idcat] += $val_tab * $nb_eval;
						else $val_cat_total[$key_idcat] = $val_tab * $nb_eval;
						/*if($id_client == 643)
						{
							$val_cat_total[$key_idcat] += $val_tab * $nb_eval / 10;
						}*/
					}
				}
				
				/**
				* 
				* @var ******************* Par IS ***********************
				* 
				*/
				//echo json_encode($tab_critere['is5_v7']); exit;
				$tableaunf = get_indicateur_nf();
				foreach($tableaunf as $keynf => $valnf){
					/* ********* Séparateur ************* */
					$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'');
					$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_separateur);
					$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
					$icell++;
					/* ********************************** */
					
					$is= $valnf;
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
					if($val_is != 'NE')
					{
						if(isset($is_total[$valnf])) $is_total[$valnf] += $val_is * $nb_eval;
						else $is_total[$valnf] = $val_is * $nb_eval;
						//$tot[$valnf] += $nb_eval;
						$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
						
						if( $valnf =='is5' || $valnf =='IS5' )
						{
							$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,"");
						}
						else
						{
							$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$val_is/100);
						}
						
					}
					else
					{
						//$is_total[$valnf] = $val_is; 
						if(isset($is_total[$valnf])) $is_total[$valnf] += 0; 
						
						if( $valnf =='is5' || $valnf =='IS5' )
						{
							$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,"");
						}
						else
						{
							$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$val_is);
						}
						
					}

					$icell++;
					
					foreach($tab_critere[$valnf] as $_key => $_val)
					{
						if(isset($tab_code['detail_nf'][$valnf][$_val]))
						{
							$valeur = $tab_code['detail_nf'][$valnf][$_val]/100;
						}
						else
						{
							$valeur = 'NE';
						}
						if($valeur != 'NE')
						{
							if(isset($is_detail_total[$valnf][$_val])) $is_detail_total[$valnf][$_val] += $valeur * $nb_eval;
							else $is_detail_total[$valnf][$_val] = $valeur * $nb_eval;
							//$det_total[$valnf][$_val] += $nb_eval;
						}
						$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_contenu_detail);
						$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
						$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$valeur);$icell++;
					}
				}
				/* ********* Séparateur ************* */
				$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'');
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_separateur);
				$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
				$icell++;
				/* ********************************** */
				
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
					$icell++;
				}
				$num_ligne++;
				$icell = 1;
			}
		}	
		$i++;
	}
	$num_ligne++;
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
	/**
	* ******************TOTAL *****************************
	*/
	$icell_ = $icell + 2;
	$objWorksheet1->mergeCells($listeColExcel[$icell].$num_ligne.':'.$listeColExcel[$icell_].$num_ligne);
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_titre);
	$objWorksheet1->getStyle($listeColExcel[$icell+1].$num_ligne)->applyFromArray($style_total_titre);
	$objWorksheet1->getStyle($listeColExcel[$icell_].$num_ligne)->applyFromArray($style_total_titre);
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_centre);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'VIVETIC');
	$icell=$icell_+1;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_note);
	// mettre ici Volume traité 
	if($volume_total != 0){
		$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$volume_total);
	}
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_note);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,number_format($note_total/$nb_eval_total,1));
	$icell++;
	
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_note);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$nb_eval_total);
	$icell++;
	
	// Total catégorie ******************************
	foreach($table_keycat as $_key => $_val){
		$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->setFormatCode('#,##0.0');
		// $valeur = $val_cat_total[$_val] / $nb_eval_total;
		$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_categorie);
		// $objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,number_format($valeur,2));
		$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,'=AVERAGE('.$listeColExcel[$icell].'4:'.$listeColExcel[$icell].($num_ligne-2).')');
		
		//$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne, '=SUM(B'.$listeColExcel[$icell].'4:B'.$listeColExcel[$icell].($num_ligne-2).')/F'.$num_ligne);
		
		$icell++;
	}
	
	// Total Indicateur NF *****************************
	$tableaunf = get_indicateur_nf();
	foreach($tableaunf as $keynf => $valnf)
	{
		/* ********* Séparateur ************* */
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'');
		$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
		$icell++;
		/* ********************************** */
		$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_categorie);
		//if($is_total[$valnf] == 'NE')

		if(empty($is_total[$valnf]) || $is_total[$valnf] == 0)
		{
			//$valeur = $is_total[$valnf];
			$valeur = 'NE';
			
			if( $valnf =='is5' || $valnf =='IS5' )
			{
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,"");$icell++;
			}else
			{
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$valeur);$icell++;
			}
			
		}
		else
		{
			//if(isset($is_total[$valnf])) $valeur = $is_total[$valnf] / $nb_eval_total;
			$valeur = $is_total[$valnf] / $nb_eval_total;
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
			
			if( $valnf =='is5' || $valnf =='IS5' )
			{
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,"");$icell++;
			}else
			{
				$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$valeur/100);$icell++;
			}
			
			
		}
		
		foreach($tab_critere[$valnf] as $_key => $_val)
		{
			$valeur_detail = $is_detail_total[$valnf][$_val] / $nb_eval_total;
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_detail);
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
			$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$valeur_detail);$icell++;
			
		}
	}
	/* ********* Séparateur ************* */
	$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'');
	$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
	$icell++;
	/* ********************************** */
	$result_repartition = fetchAllRepartition();
	while($res_rep = pg_fetch_array($result_repartition)){
		$id_repartition = $res_rep['id_repartition'];
		$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_categorie);
		$objWorksheet1->setCellValue($listeColExcel[$icell].$num_ligne,$repartition_total[$id_repartition]);
		$icell++;
	}
}
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/

$file = "reporting_global_".str_replace("-","_",$ftxt_dtdeb_)."__".str_replace("-","_",$ftxt_dtfin_).'.xls';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
$objWriter->setPreCalculateFormulas(false);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$file.'"');
$objWriter->save('reporting/'.$file);
readfile('reporting/'.$file);
exit; 

?>