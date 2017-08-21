<?php 

require_once 'PHPExcel/IOFactory.php';
include_once 'PHPExcel/Writer/Excel5.php';
include_once 'PHPExcel/Writer/Excel2007.php';
require_once 'PHPExcel.php';
//require_once 'incfunc.php';
include("/var/www.cache/dgconn.inc");
include('function_synthese_dynamique.php');
include('function_dynamique.php');
//include("/var/www.cache/siapconn.inc");
//include("/var/www.cache/rhconn.inc");
ini_set('max_execution_time', 0);
set_time_limit(0);

$fichier = 'reporting.xls';
$inputFileName = 'reporting/'.$fichier;
$dossier = 'reporting/';

if (!file_exists($inputFileName)) {
	exit("Please run 14excel5.php first.\n");
}

function recuperer_next_vendrediJolie ($debut)
{ 
	$boolean = "false";
	$ann = substr($debut,0,4);
	$mois = substr($debut,5,2);
	$jour = substr($debut,8,2);
	$datetest = $ann."-".$mois."-".$jour;
	$daty = mktime( 0,0,0,date($mois)  ,date($jour) ,date($ann));
	$j = "4 day";
	$datetest = date('Y-m-d', strtotime($debut. $j));
	return $datetest;
}

function weekNumber( $ddate )
{
  $week = date("W", strtotime($ddate));
  return $week;

}

$date_deb_notation = $_REQUEST['date_deb'];
$date_fin_notation = $_REQUEST['date_fin'];
//echo $date_fin_notation;exit;
$id_projet = $_REQUEST['projet'];
$id_client = $_REQUEST['client'];
$id_application = $_REQUEST['application'];
$id_type_traitement = $_REQUEST['type_traitement'];
$array_traitement = array("1"=>"Appel entrant","2"=>"Appel sortant","3"=>"Traitement d'Email");
$matricule_auditeur = $_REQUEST['auditeur'];
$matricule_tlc = $_REQUEST['tlc'];
 $id_type_appel = $_REQUEST['id_type_appel'];
$deb = explode('/',$date_deb_notation);
$fin = explode('/',$date_fin_notation);
$ftxt_dtdeb_ = $deb[2].'-'.$deb[1].'-'.$deb[0];
//$date_fin_notation = recuperer_next_vendrediJolie ($ftxt_dtdeb_);
$ftxt_dtfin_ = $fin[2].'-'.$fin[1].'-'.$fin[0];

//$fin = explode('/',$date_fin_notation);
//$ftxt_dtfin_ = $fin[2].'-'.$fin[1].'-'.$fin[0];

$listeColExcel = array("1" => "A","2" => "B" ,"3" => "C" ,"4" => "D" ,"5" => "E" ,"6" => "F" ,"7" => "G" ,"8" => "H" ,"9" => "I" ,"10" => "J" ,"11" => "K" ,"12" => "L" ,"13" => "M" ,"14" => "N" ,"15" => "O" ,"16" => "P" ,"17" => "Q" ,"18" => "R" ,"19" => "S" ,"20" => "T" ,"21" => "U" ,"22" => "V" ,"23" => "W" ,"24" => "X" ,"25" => "Y" ,"26" => "Z" ,"27" => "AA" ,"28" => "AB" ,"29" => "AC" ,"30" => "AD" ,"31" => "AE" ,"32" => "AF" ,"33" => "AG" ,"34" => "AH" ,"35" => "AI" ,"36" => "AJ" ,"37" => "AK" ,"38" => "AL" ,"39" => "AM" ,"40" => "AN" ,"41" => "AO" ,"42" => "AP" ,"43" => "AQ" ,"44" => "AR","45" => "AS","46" => "AT","47" => "AU","48" => "AV","49" => "AW","50" => "AX","51" => "AY","52" => "AZ","53" => "BA","54" => "BB","55" => "BC","56" => "BD","57" => "BE","58" => "BF","59" => "BG","60" => "BH","61" => "BI","62" => "BJ","63" => "BK","64" => "BL","65" => "BM","66" => "BN","67" => "BO","68" => "BP","69" => "BQ","70" => "BR","71" => "BS","72" => "BT","73" => "BU","74" => "BV","75" => "BW","76" => "BX","77" => "BY","78" => "BZ","79" => "CA","80" => "CB","81" => "CC","82" => "CD","83" => "CE","84" => "CF","85" => "CG","86" => "CH","87" => "CI","88" => "CJ","89" => "CK","90" => "CL","91" => "CM","92" => "CN","93" => "CO","94" => "CP","95" => "CQ","96" => "CR","97" => "CS","98" => "CT","99" => "CU","100" => "CV","101" => "CW","102" => "CX","103" => "CY","104" => "CZ","105" => "DA","106" => "DB","107" => "DC","108" => "DD","109" => "DE","110" => "DF","111" => "DG","112" => "DH","113" => "DI");

/* ********** TABLEAU STYLE ************************ */
include('export_style.php');
/* **************************************** */

$controle = getDonneesForExport($id_projet,$id_client,$id_application,$ftxt_dtdeb_,$ftxt_dtfin_,$id_type_traitement,$id_type_appel);
$nombre_evaluateur = pg_num_rows($controle);
$matricule_evaluateur = array();
$prenom_evaluateur = array();
$nb_eval = 0;
while($result = pg_fetch_array($controle))
{
	$matricule_evaluateur[] = $result['matricule_notation'];
	$prenom_evaluateur[] = $result['prenompersonnel'];
	$nom_client = $result['nom_client'];
	$code = $result['code'];
}

$objet = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objet->load($inputFileName);
$objPHPExcel->getSheet(0);
$objPHPExcel->setActiveSheetIndex(0);
$objWorksheet1 = $objPHPExcel->getActiveSheet();
$titleSheet = "Détail Evaluation";
$objWorksheet1->setTitle($titleSheet);
$objWorksheet1->getSheetView()->setZoomScale(80);
$objWorksheet1->getRowDimension(4)->setRowHeight(30);
$deb_rep = 18;
$result_repartition = fetchAllRepartition();

while($res_rep = pg_fetch_array($result_repartition))
{
	$objWorksheet1->setCellValue($listeColExcel[$deb_rep].'4',utf8_encode($res_rep['libelle_repartition']));
	$objWorksheet1->getStyle($listeColExcel[$deb_rep].'4')->applyFromArray($style_centre);
	$objWorksheet1->getColumnDimension($listeColExcel[$deb_rep])->setWidth(20);
	$deb_rep++;
}

$tab_categorie = array();
$list_cat = array();
//$tableauBord_detail = getCategorieDetailEval($id_projet, $id_client,$id_application,$id_type_traitement,$ftxt_dtdeb_,$ftxt_dtfin_,0,0);
$tableauBord_detail = _getCategorieDetailEval($id_projet, $id_client,$id_application,$id_type_traitement,$ftxt_dtdeb_,$ftxt_dtfin_,0,0,$id_type_appel);

foreach($tableauBord_detail as $key => $tab)
{
	foreach($tableauBord_detail[$key]['libelle_categorie_grille'] as $key_=>$tab_)
	{
		if(!in_array($key_,$tab_categorie))
		{
			//array_push($headers1,$tab_);
			//$objPHPExcel->getActiveSheet()->mergeCells($listeColExcel[$deb_rep].'4:'.$listeColExcel[$deb_rep].'3');
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$deb_rep].'4',utf8_encode($tab_));
			$objWorksheet1->getStyle($listeColExcel[$deb_rep].'4')->applyFromArray($style_detail);
			$objWorksheet1->getStyle($listeColExcel[$deb_rep].'4')->applyFromArray($style_centre);
			$objWorksheet1->getStyle($listeColExcel[$deb_rep].'4')->applyFromArray($style_border_categorie);
			$objWorksheet1->getColumnDimension($listeColExcel[$deb_rep])->setWidth(30);
			array_push($tab_categorie,$key_);
			array_push($list_cat,$tab_);
			
			$deb_rep++;
		}
	}
}

$result_notation = getAllNotation($id_projet, $id_client,$id_application,$id_type_traitement,$ftxt_dtdeb_,$ftxt_dtfin_,0,0,$id_type_appel);
$nb_notation = pg_num_rows($result_notation);
$objWorksheet1->mergeCells('B1:G1');
$objWorksheet1->setCellValue('B1', 'BILAN '.$nom_client.' '.$array_traitement[$id_type_traitement]);//.' S_'.weekNumber($ftxt_dtdeb_)
$Ligne = 5;
$Ligne_deb = 5;
$cc=13;

for($k=0;$k< $nb_notation;$k++)
{
	$icell0 = 1;
	$objWorksheet1->getRowDimension($Ligne)->setRowHeight(72);
	/*$objWorksheet1->getStyle('K'.$Ligne.':M'.$Ligne)->applyFromArray($left);
	$objWorksheet1->getStyle('E'.$Ligne)->applyFromArray($right);
	$objWorksheet1->getStyle('F'.$Ligne.':J'.$Ligne)->applyFromArray($style_centre);
	$objWorksheet1->getStyle('A'.$Ligne.':D'.$Ligne)->applyFromArray($style_centre);*/
	
	$lg = pg_fetch_array($result_notation,$k);
	
	$id_notation = $lg['id_notation'];
	/*$str = calculTotalGeneral($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement);
	$table_valeur = explode('||',$str); 
	$note =  $table_valeur[0];*/
	
	$note = number_format($lg['note'],2);
	$prenom_cc = getPrenomPersonnel($lg['matricule']);
	$test_inactif = 0;
	if($prenom_cc == '')
	{
		$prenom_cc = 'Inactif';
		$test_inactif = 1;
	}
	$tableau_is = getIS($id_projet, $id_client,$id_application,$id_type_traitement,$ftxt_dtdeb_,$ftxt_dtfin_,0,0);
	$result_repartition = fetchAllRepartition();
	
	$debut_center_deb = $listeColExcel[$icell0];
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne,$lg['matricule_notation']);$icell0++;
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne,$lg['matricule']);$icell0++;
	if($test_inactif == 1)
	{
		$objWorksheet1->getStyle($listeColExcel[$icell0].$Ligne)->applyFromArray($style_font_inactif);
	}
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne,utf8_encode($prenom_cc));$icell0++;
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne,$lg['nom_fichier']);$icell0++;
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne,$lg['libelle_typologie']);$icell0++;
	$date_eval = date_create($lg['date_notation']);
	$date_eval = date_format($date_eval,'d/m/Y');
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne,$date_eval);$icell0++;
	$date_entret = date_create($lg['date_entretien']);
	$date_entret = date_format($date_entret,'d/m/Y');
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne,$date_entret);$icell0++;
	$deb_moyenne = $icell0;
	$deb_moy = $icell0;
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne, number_format($note,1));$icell0++;
	$deb_note = $icell0;
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne, 1);$icell0++;
	$debut_center_fin = $listeColExcel[$icell0-1];
	$objWorksheet1->getStyle($debut_center_deb.$Ligne.':'.$debut_center_fin.$Ligne)->applyFromArray($style_centre);
	//$col = 9;
	/*for($i=4;$i<=7;$i++)
	{*/
	$tableaunf = get_indicateur_nf();
	foreach($tableaunf as $keynf => $valnf)
	{
		$is = $valnf;
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell0].$Ligne)->applyFromArray($style_centre);
		if(isset($tableau_is[$id_notation][$is]))
		{
			if( $is =='is5' || $is =='IS5' )
			{
				$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne, "");
			}
			else
			{
				$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne, $tableau_is[$id_notation][$is]);
			}
			
		}
		else
		{
			$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne, 0);
		}
		
		$icell0++;
	}
	
	//hng
	if(substr($lg['point_appui'],0,1) == "=")
		$point_appui = str_replace("=","'=",$lg['point_appui']);
	else $point_appui = $lg['point_appui'];
	
	if(substr($lg['point_amelioration'],0,1) == "=")
		$point_amelioration = str_replace("=","'=",$lg['point_amelioration']);
	else $point_amelioration = $lg['point_amelioration'];
	
	if(substr($lg['preconisation'],0,1) == "=")
		$preconisation = str_replace("=","'=",$lg['preconisation']);
	else $preconisation = $lg['preconisation'];
		
	$objWorksheet1->getStyle($listeColExcel[$icell0].$Ligne)->applyFromArray($left);
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne, $point_appui);$icell0++;
	$objWorksheet1->getStyle($listeColExcel[$icell0].$Ligne)->applyFromArray($left);
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne, $point_amelioration);$icell0++;
	$objWorksheet1->getStyle($listeColExcel[$icell0].$Ligne)->applyFromArray($left);
	$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne,$preconisation);$icell0++;
	
    $col_rep_deb = $icell0;
    $col_rep_fin = $icell0;
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
		$objWorksheet1->getStyle($listeColExcel[$icell0].$Ligne)->applyFromArray($style_centre);
		$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne, $val_rep);$icell0++;
		$col_rep_fin++;
	}
	
	/**
	* 
	* @var ******************* Par categorie ***********************
	* 
	*/
	$col_cat_deb = $icell0;
    $col_cat_fin = $icell0;
	foreach($tab_categorie as $key_cat=>$tab_cat)
	{
		if(isset($tableauBord_detail[$lg['matricule']]['categorie_grille'][$tab_cat][$id_notation]))
		{
			$valeur_cat = $tableauBord_detail[$lg['matricule']]['categorie_grille'][$tab_cat][$id_notation];
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
		//$objWorksheet1->getStyle($listeColExcel[$icell0].$Ligne)->applyFromArray($style_contenu_categorie);
		$objWorksheet1->getStyle($listeColExcel[$icell0].$Ligne)->applyFromArray($style_centre);
		$objWorksheet1->setCellValue($listeColExcel[$icell0].$Ligne,number_format($valeur_cat,1));$icell0++;
		if(isset($valeur_cat_total[$tab_cat])) $valeur_cat_total[$tab_cat] += $valeur_cat;
		else $valeur_cat_total[$tab_cat] = $valeur_cat;
		
		if(isset($valeur_cat_total_f[$tab_cat])) $valeur_cat_total_f[$tab_cat] += number_format($valeur_cat*$nb_eval,2);
		else $valeur_cat_total_f[$tab_cat] = number_format($valeur_cat*$nb_eval,2);
		
		$col_cat_fin++;
	}
	
	$Ligne++;		
}
//$objWorksheet1->setCellValue($listeColExcel[6].$Ligne, '=IF(('.$sm['Performances'][$j].')>0,AVERAGE(F5:F'.$Ligne.'),0)');
$objWorksheet1->setCellValue($listeColExcel[$deb_moyenne].$Ligne,'=AVERAGE('.$listeColExcel[$deb_moyenne].$Ligne_deb.':'.$listeColExcel[$deb_moyenne].($Ligne-1).')');$deb_moyenne++;
$objWorksheet1->setCellValue($listeColExcel[$deb_moyenne].$Ligne,'=SUM('.$listeColExcel[$deb_moyenne].$Ligne_deb.':'.$listeColExcel[$deb_moyenne].($Ligne-1).')');$deb_moyenne++;
$objWorksheet1->setCellValue($listeColExcel[$deb_moyenne].$Ligne,'=SUM('.$listeColExcel[$deb_moyenne].$Ligne_deb.':'.$listeColExcel[$deb_moyenne].($Ligne-1).')/'.$listeColExcel[$deb_note].$Ligne);$deb_moyenne++;
$objWorksheet1->setCellValue($listeColExcel[$deb_moyenne].$Ligne,'');$deb_moyenne++;
$objWorksheet1->setCellValue($listeColExcel[$deb_moyenne].$Ligne,'=SUM('.$listeColExcel[$deb_moyenne].$Ligne_deb.':'.$listeColExcel[$deb_moyenne].($Ligne-1).')/'.$listeColExcel[$deb_note].$Ligne);$deb_moyenne++;
$objWorksheet1->setCellValue($listeColExcel[$deb_moyenne].$Ligne,'=SUM('.$listeColExcel[$deb_moyenne].$Ligne_deb.':'.$listeColExcel[$deb_moyenne].($Ligne-1).')/'.$listeColExcel[$deb_note].$Ligne);$deb_moyenne++;
$objWorksheet1->setCellValue($listeColExcel[$deb_moyenne].$Ligne,'=SUM('.$listeColExcel[$deb_moyenne].$Ligne_deb.':'.$listeColExcel[$deb_moyenne].($Ligne-1).')/'.$listeColExcel[$deb_note].$Ligne);
for($y=$deb_moy;$y<=$deb_moyenne;$y++)
{
	$objWorksheet1->getStyle($listeColExcel[$y].$Ligne)->applyFromArray($bordergras);
	$objWorksheet1->getStyle($listeColExcel[$y].$Ligne)->applyFromArray($style_centre);
	$objWorksheet1->getStyle($listeColExcel[$y].$Ligne)->getNumberFormat()->setFormatCode('#,##0.00');
}
for($x = $col_rep_deb;$x<=($col_rep_fin-1);$x++)
{
	$objWorksheet1->getStyle($listeColExcel[$x].$Ligne.':'.$listeColExcel[$x].$Ligne)->applyFromArray($bordergras);
	$objWorksheet1->getStyle($listeColExcel[$x].$Ligne.':'.$listeColExcel[$x].$Ligne)->applyFromArray($style_centre);
	$objWorksheet1->setCellValue($listeColExcel[$x].$Ligne,'=SUM('.$listeColExcel[$x].$Ligne_deb.':'.$listeColExcel[$x].($Ligne-1).')');
}
for($x = $col_cat_deb;$x<=($col_cat_fin-1);$x++)
{
	$objWorksheet1->getStyle($listeColExcel[$x].$Ligne.':'.$listeColExcel[$x].$Ligne)->applyFromArray($bordergras);
	$objWorksheet1->getStyle($listeColExcel[$x].$Ligne.':'.$listeColExcel[$x].$Ligne)->applyFromArray($style_centre);
	$objWorksheet1->getStyle($listeColExcel[$x].$Ligne)->getNumberFormat()->setFormatCode('#,##0.00');
	$objWorksheet1->setCellValue($listeColExcel[$x].$Ligne,'=AVERAGE('.$listeColExcel[$x].$Ligne_deb.':'.$listeColExcel[$x].($Ligne-1).')');
}

/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
$etape = 0;
$set_header = 0;
$note_total = 0;
$nbeval_total = 0;
$valeur_cat_total = array();
$valeur_cat_total_f = array();
$is_type_global_total = array();
$nb_eval_global_total = array();
$is_type_total = array();
$nb_eval_total = array();
$valeur_rep_total = array();

for($z=1;$z<=$nombre_evaluateur;$z++)
{
	if($etape == 1)
	{
		$w = $nombre_evaluateur+1;
		$prenom = 'EVAL_TOTAL';
		$matricule_eval = 0;
	}
	else
	{
		$w = $z;
		$prenom = $prenom_evaluateur[$z-1];
		$matricule_eval = $matricule_evaluateur[$z-1];
	}
	if($set_header == 0)
	{
		$objWorksheet1 = $objPHPExcel->createSheet();
	}
	$objPHPExcel->getSheet($w);
	$objPHPExcel->setActiveSheetIndex($w);

	$objWorksheet1 = $objPHPExcel->getActiveSheet();
	$prenom = substr($prenom,0,26);
	$titleSheet = "ACC_".$prenom;
	$objWorksheet1->setTitle($titleSheet);

	$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(80);
	$objWorksheet1->getRowDimension(2)->setRowHeight(180);
	if($etape == 1)
	{
		$objPHPExcel-> getActiveSheet()->freezePane ('F4'); 
	}
	else
	{
		$objPHPExcel-> getActiveSheet()->freezePane ('E4');
	}
	 
	/* ********************************************************************** */
	/* ************* Prendre les données indispensables ********************* */
	/* ********************************************************************** */
	$tab_matricule = array();
	$result = fetchAllTLCClient($id_projet,$id_client,$id_application,$id_type_traitement,$ftxt_dtdeb_,$ftxt_dtfin_,$matricule_evaluateur[$z-1],0,$id_type_appel);
	while($res = pg_fetch_array($result))
	{
		$tab_matricule[] = $res['matricule'];
	}

	$tableauBord = setTableauSynthese($id_projet, $id_client,$id_application,$id_type_traitement,$ftxt_dtdeb_,$ftxt_dtfin_,$matricule_evaluateur[$z-1],0,0,$id_type_appel);
  
	$tableauBord_title = setTableauSynthese($id_projet, $id_client,$id_application,$id_type_traitement,$ftxt_dtdeb_,$ftxt_dtfin_,0,0,0,$id_type_appel);

	$result1 = fetchAllTLCNotation($id_projet, $id_client,$id_application,$id_type_traitement,$ftxt_dtdeb_,$ftxt_dtfin_,$matricule_evaluateur[$z-1],0,0,$id_type_appel);
	$nb_row = pg_num_rows( $result1 );
	$array_test = array();
	$somme_total_general=0;
	$nb_eval = 0;
	for ($i=0;$i<$nb_row;$i++)
	{	
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
	}
	/* ********************************************************************** */
	/* ************* Traitement des headers ********************************* */
	/* ********************************************************************** */
	
if($set_header == 0)
{
	$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
	$objPHPExcel->getActiveSheet()->setCellValue('A1',$date_deb_notation.'  au  '.$date_fin_notation);
	$objWorksheet1->getStyle('A1')->applyFromArray($style_fourchette_date_simple);
	$headers1 = array("Matricule","CC","Note","Nb Eval");
	$icell = 1;
	if($etape == 1)
	{
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'3',utf8_encode('Eval.'));
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_titre);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_border);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_font);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_centre);
		
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_separateur);
		$icell++;
	}
	for ($c = 0; $c<count($headers1); $c++)
	{
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'3',utf8_encode($headers1[$c]));
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_titre);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_border);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_font);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_centre);
		
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_separateur);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_centre);
		
		if(($icell == 1 && $etape == 0) || ($icell == 2 && $etape == 1))
		{
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'2',utf8_encode($traitement_abrev[$id_type_traitement]));
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_grand_titre);
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_separateur);
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_centre);
		}
		if(($icell == 2 && $etape == 0) || ($icell == 3 && $etape == 1))
		{
			$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(20);
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'2',utf8_encode($nom_client));
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_grand_titre);
		}
		if(($icell == 3 && $etape == 0) || ($icell == 4 && $etape == 1))
		{
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_grand_titre_note);
		}
		if(($icell == 4 && $etape == 0) || ($icell == 5 && $etape == 1))
		{
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_grand_titre_eval);
		}
		$icell++;

	}

	$tab_categorie = array();
	$list_cat = array();
	foreach($tableauBord as $key => $tab)
	{
		foreach($tableauBord[$key]['libelle_categorie_grille'] as $key_=>$tab_)
		{
			if(!in_array($key_,$tab_categorie))
			{
				//array_push($headers1,$tab_);
				$objPHPExcel->getActiveSheet()->mergeCells($listeColExcel[$icell].'2:'.$listeColExcel[$icell].'3');
				$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'2',utf8_encode($tab_));
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_rotation);
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_categorie);
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_font);
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_border);
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_border);
				array_push($tab_categorie,$key_);
				array_push($list_cat,$tab_);
				
				$icell++;
			}
		}
	}
	
	/*for($i=4;$i<=7;$i++)
	{*/
	//$tableaunf = get_indicateur_nf();
	$tableaunf = get_indicateur_nf_objectif();
	$tab_critere_is = array();
	foreach($tableaunf as $keynf => $valnf)
	{
		$is = $valnf['libelle'];
		//array_push($headers1,$is);
		
		/* ********* Séparateur ************* */
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'2','');
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_separateur);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_separateur);
		$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
		$icell++;
		/* ********************************** */
		
	
		
		
		/*if($i == 4) $pourcentage = '60%';
		if($i == 5) $pourcentage = '85%';
		if($i == 6) $pourcentage = '85%';
		if($i == 7) $pourcentage = '80%';*/
		//$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'3',utf8_encode($pourcentage));
		
		if( $is =='is5' || $is =='IS5' )
		{
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'2','');
			
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'3','');
		}
		else
		{			
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'2',utf8_encode(substr($is,0,3)));
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'3',utf8_encode($valnf['objectif']).'%');
		} 
		
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_detail);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_rotation);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_IS);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_font);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_border);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_border);
		$icell++;
		
		$tab_critere = array();
		$verif_detail = 0;
		$num_cell_deb = $icell;
		foreach($tableauBord_title as $key => $tab)
		{
			foreach($tableauBord_title[$key][$is]['critere'] as $key_idcat=>$tab_val)
			{
				if(!in_array($key_idcat,$tab_critere))
				{
					//array_push($headers1,$tab_val['libelle']);
					$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'2',utf8_encode($tab_val['libelle']));
					$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_rotation);
					$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_categorie);
					$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_font);
					$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_border);
					$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_border);
					
					array_push($tab_critere,$key_idcat);
					//$tab_critere_is[$is] = 1;
					$tab_critere_is[$is][] = $key_idcat;
					
					$icell++;
					$verif_detail = 1;
				}
			}
		}
		
		$num_cell_fin = $icell - 1;
		if($verif_detail == 1)
		{
			$objPHPExcel->getActiveSheet()->mergeCells($listeColExcel[$num_cell_deb].'3:'.$listeColExcel[$num_cell_fin].'3');
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$num_cell_deb].'3',utf8_encode('DETAILS'));
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$num_cell_deb].'3')->applyFromArray($style_detail);
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$num_cell_deb].'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	}
	
	/* ********* Séparateur ************* */
	$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'2','');
	$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_separateur);
	$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_separateur);
	$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
	$icell++;
	/* ********************************** */
	
	$result_repartition = fetchAllRepartition();
	$dep_rep = $icell;
	while($res_rep = pg_fetch_array($result_repartition))
	{
		//array_push($headers1,$res_rep['libelle_repartition']);
		//$objPHPExcel->getActiveSheet()->mergeCells($listeColExcel[$icell].'2:'.$listeColExcel[$icell].'3');
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'2',utf8_encode($res_rep['libelle_repartition']));
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_rotation);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_categorie);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_font);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'2')->applyFromArray($style_border);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($style_border);
		$icell++;
	}
	$objPHPExcel->getActiveSheet()->mergeCells($listeColExcel[$dep_rep].'3:'.$listeColExcel[$icell-1].'3');
	$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$dep_rep].'3',utf8_encode('Situations Inacceptables'));
	$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$dep_rep].'3')->applyFromArray($style_detail);
	$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$dep_rep].'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
}
	/** *************************************************************** **/
	/** ***********************CONTENU********************************* **/
	/** *************************************************************** **/
	$critere_title = array();
	if($set_header == 0)
	{
		$num_ligne = 4;
	}
	foreach($tab_matricule as $tab)
	{
		$icell = 1;
		if($etape == 1)
		{
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_centre);
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($matricule_evaluateur[$z-1]));$icell++;
		}
		$nb_eval = $tableauBord[$tab]['nbEval'];
		$note = number_format($tableauBord[$tab]['note'] / $nb_eval ,1);
		//$note = $tableauBord[$tab]['note'] / $nb_eval;
		$prenom_tlc = get_prenom_personnel( $tab );
		$test_inactif = 0;
		if($prenom_tlc == '')
		{
			$prenom_tlc = 'Inactif';
			$test_inactif = 1;
		}
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_matricule);
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_centre);
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($tab));$icell++;
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_CC);
		if($test_inactif == 1)
		{
			$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_font_inactif);
		}
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($prenom_tlc));$icell++;
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_note);
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($note));$icell++;
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_note);
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,utf8_encode($nb_eval));$icell++;

		$note_total += $note * $nb_eval;
		$nbeval_total += $nb_eval; 

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
			
			if(($id_type_traitement == 1 || $id_type_traitement == 2) && ($id_client != 643 && $id_client != 642 )) //client différent de DELAMAISON
			{
				$valeur_cat = $valeur_cat;
			}
			else
			{
				$valeur_cat = $valeur_cat / 10;
			}
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_contenu_categorie);
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,number_format($valeur_cat,1));$icell++;
			if(isset($valeur_cat_total[$tab_cat])) $valeur_cat_total[$tab_cat] += $valeur_cat;
			else $valeur_cat_total[$tab_cat] = $valeur_cat;
			
			if(isset($valeur_cat_total_f[$tab_cat])) $valeur_cat_total_f[$tab_cat] += number_format($valeur_cat*$nb_eval,2);
			else $valeur_cat_total_f[$tab_cat] = number_format($valeur_cat*$nb_eval,2);
		}

		////////////////////////////////////////////////////////////////
		
		/* ********* Séparateur ************* */
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'');
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_separateur);
		$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
		$icell++;
		/* ********************************** */
			
		/**
		* 
		* @var ************** IS **************************
		* 
		*/
		
		$tab_critere = array();
		//$critere_title = array();
		/*for($i=4;$i<=7;$i++)
		{*/
		$tableaunf = get_indicateur_nf();
		foreach($tableaunf as $keynf => $valnf)
		{
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
				if(isset($nb_eval_global_total[$is])) $nb_eval_global_total[$is] += 0;
			}
			
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_contenu_IS);
			
			/*if($is == 'is5_v7' && $matricule_eval == 0) {
				echo $tab.'***'.$is.'***'.json_encode($tableauBord[$tab][$is]['critere']).'</br>';
			}*/
			//if(count($tableauBord[$tab][$is]['critere']) == 0)
			if(empty($tableauBord[$tab][$is]['critere']))
			{
				if( $is =='is5' || $is =='IS5' )
					{
						$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'');$icell++;
					}else
					{
						$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'NE');$icell++;
					}
				
				if($critere_title[$is][$matricule_eval] != 1) 
				{
					$critere_title[$is][$matricule_eval] = 0;
				}
			}
			else
			{
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
				
					if( $is =='is5' || $is =='IS5' )
					{
						$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,"");$icell++;
					}
				    else
				    {
					   $objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$valeur_is/100);$icell++;
				    }
				
				$critere_title[$is][$matricule_eval] = 1;
			}
			/* ************************************************** */
			/* ************************************************** */
			$tab_critere[$is] = array();
			if(empty($tableauBord[$tab][$is]['critere']) && !empty($tab_critere_is[$is]))
			{
				foreach($tab_critere_is[$is] as $key_ => $val)
				{
					$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_contenu_detail);
					$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'NE');$icell++;
					array_push($tab_critere[$is],$val);
				}
				
			}else
			foreach($tab_critere_is[$is] as $keyy => $vall)
			{
				$accept = 0;
				foreach($tableauBord[$tab][$is]['critere'] as $key_idcat=>$tab_val)
				{
					if($vall == $key_idcat)
					{
						if(!in_array($key_idcat,$tab_critere))
						{
							$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_contenu_detail);
							$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
							// Modifié le 15/09/2014
							if(isset($tab_val['valeur']) &&  $tab_val['valeur'] >= 0)
							{
								$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$tab_val['valeur']/100);$icell++;
							}
							else
							{
								$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'NE');$icell++;
							}
							/* *********************** */
							array_push($tab_critere[$is],$key_idcat);
							
							if(isset($is_type_total[$is][$key_idcat]))
							{
								$is_type_total[$is][$key_idcat] += $tab_val['is_type'];
								$nb_eval_total[$is][$key_idcat] += $tab_val['nb_eval'];
							}
							else
							{
								$is_type_total[$is][$key_idcat] = $tab_val['is_type'];
								$nb_eval_total[$is][$key_idcat] = $tab_val['nb_eval'];
							}
							
							$accept = 1;
						}
					}
				}
				if($accept == 0)
				{
					$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_contenu_detail);
					$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'NE');$icell++;
					array_push($tab_critere[$is],$vall);
				}
			}
			
			/* ********* Séparateur ************* */
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'');
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_separateur);
			$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
			$icell++;
			/* ********************************** */
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
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_contenu_categorie);
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$valeur_rep);$icell++;
			if(isset($valeur_rep_total[$res_rep['id_repartition']])) $valeur_rep_total[$res_rep['id_repartition']] += $valeur_rep; 
			else $valeur_rep_total[$res_rep['id_repartition']] = $valeur_rep; 
		}

		////////////////////////////////////////////////////////////////////
		$num_ligne ++;
	}

	if($etape == 1)
	{
		$set_header = 1; // Le titre ne devrait être écrit qu'une seule fois
	}
	
	/** ************************************************************* **/
	/** ***********************TOTAL********************************* **/
	/** ************************************************************* **/

if($set_header == 0 || ($etape == 1 && $z == $nombre_evaluateur))
{
	$num_ligne ++;
	$nom_client = getNomClientById($id_client);
	$res_app = getCodePrestationById($id_application);
	$codeApp = $res_app['code'];
	$nom_type = $traitement_abrev[$id_type_traitement];
	$icell = 1;
	if($etape == 1)
	{
		$icell_ = $icell+2;
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell+1].$num_ligne)->applyFromArray($style_total_titre);
		//$icell++;
	}
	else
	{
		$icell_ = $icell+1;
	}
	$objWorksheet1->getRowDimension($num_ligne)->setRowHeight(25);
	$objPHPExcel->getActiveSheet()->mergeCells($listeColExcel[$icell].$num_ligne.':'.$listeColExcel[$icell_].$num_ligne);
	$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_titre);
	$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell_].$num_ligne)->applyFromArray($style_total_titre);
	$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_centre);
	$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$nom_type.' - '.$nom_client);
	$icell=$icell_+1;
	$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_note);
	$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->setFormatCode('#,##0.0');
	$val_total_note = '=AVERAGE('.$listeColExcel[$icell].'4:'.$listeColExcel[$icell].($num_ligne-2).')';
	$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$val_total_note);
	//$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,number_format($note_total/$nbeval_total,1));
	if($etape == 0)
	{
		//$objPHPExcel->getActiveSheet()->setCellValue('C2',number_format($note_total/$nbeval_total,2));
		$objWorksheet1->getStyle('C2')->getNumberFormat()->setFormatCode('#,##0.0');
		$objPHPExcel->getActiveSheet()->setCellValue('C2',$val_total_note);
	}
	else if($etape == 1)
	{
		//$objPHPExcel->getActiveSheet()->setCellValue('D2',number_format($note_total/$nbeval_total,2));
		$objWorksheet1->getStyle('D2')->getNumberFormat()->setFormatCode('#,##0.0');
		$objPHPExcel->getActiveSheet()->setCellValue('D2',$val_total_note);
	}
	$icell++;
	$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_note);
	$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$nbeval_total);
	if($etape == 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('D2',$nbeval_total);
	}
	else if($etape == 1)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('E2',$nbeval_total);
	}
	
	$icell++;

	$name = $codeApp." - ".$nom_client;
	
	/**
	* 
	* @var ******************* Par categorie ***********************
	* 
	*/
	$list_cat_valeur = array();
	foreach($tab_categorie as $key_cat=>$tab_cat)
	{
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_categorie);
		
		$val_total_cat = '=AVERAGE('.$listeColExcel[$icell].'4:'.$listeColExcel[$icell].($num_ligne-2).')';
		$objWorksheet1->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->setFormatCode('#,##0.0');
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$val_total_cat);$icell++;
		array_push($list_cat_valeur,(float) number_format($valeur_cat_total[$tab_cat],2));
		
		/*$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,number_format($valeur_cat_total_f[$tab_cat]/$nbeval_total,1));$icell++;
		array_push($list_cat_valeur,(float) number_format($valeur_cat_total[$tab_cat],2));*/
	}
	////////////////////////////////////////////////////////////////
	
	/* ********* Séparateur ************* */
	$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'');
	$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
	$icell++;
	/* ********************************** */
		
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
		if($nb_eval_global_total[$is] != 0)
		{
			$valeur_is = ($is_type_global_total[$is] / $nb_eval_global_total[$is]) * 100;
			if ($valeur_is == 100)
			{
				$valeur_is = number_format($valeur_is,0);
			}
			else
			{
				$valeur_is = number_format($valeur_is,2);
			}
		}
		else
		{
			$valeur_is = 0.00;
		}
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_categorie);

		if(is_null($tab_critere[$is][0]) || ($critere_title[$is][$matricule_eval] == 0 && $matricule_eval != 0))
		{
			if( $is =='is5' || $is =='IS5' )
			{
				$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'');$icell++;
			}else
			{
				$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'NE');$icell++;
			}
			
		}
		else
		{
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
			if( $is =='is5' || $is =='IS5' )
			{
				$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,"");$icell++;
			}else
			{
				$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$valeur_is/100);$icell++;
			}
			
		}
		if(is_null($tab_critere[$is][0]) && !empty($tab_critere_is[$is]))
		{
			foreach($tab_critere_is[$is] as $key_ => $val)
			{
				$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_detail);
				$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'NE');$icell++;
			}
		}else
		foreach($tab_critere[$is] as $key_idcat)
		{
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_detail);
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->getNumberFormat()->applyFromArray( $style_percent );
			if($nb_eval_total[$is][$key_idcat] != 0)
			{
				$valeur_is_cat = ($is_type_total[$is][$key_idcat] / $nb_eval_total[$is][$key_idcat]) * 100;
				$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$valeur_is_cat/100);
			}
			else if(empty($nb_eval_total[$is][$key_idcat]))
			{
				//$valeur_is_cat = 100;
				$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'NE');
			}
			else
			{
				$valeur_is_cat = 0;
				$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$valeur_is_cat/100);
			}	
		
			//$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$valeur_is_cat/100);
			$icell++;
		}
		/* ********* Séparateur ************* */
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,'');
		$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(2);
		$icell++;
		/* ********************************** */
	}

	/**
	* 
	* @var ********************** Par répartition *********************
	* 
	*/
	$result_repartition = fetchAllRepartition();
	while($res_rep = pg_fetch_array($result_repartition))
	{
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].$num_ligne)->applyFromArray($style_total_categorie);
		$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].$num_ligne,$valeur_rep_total[$res_rep['id_repartition']]);$icell++;
	}
}	
	/** ******************************************************** **/
	/** ******************************************************** **/
	/** ******************************************************** **/
	if($set_header == 0)
	{
		$note_total = 0;
		$nbeval_total = 0;
		$valeur_cat_total = array();
		$valeur_cat_total_f = array();
		$is_type_global_total = array();
		$nb_eval_global_total = array();
		$is_type_total = array();
		$nb_eval_total = array();
		$valeur_rep_total = array();
	}
	
	if($z == $nombre_evaluateur && $etape == 0)
	{
		$z = 0;
		$etape = 1;
	}
	
}

$file = "reporting_".str_replace("-","_",$ftxt_dtdeb_)."__".str_replace("-","_",$ftxt_dtfin_).'.xls';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$file.'"');
$objWriter->save('reporting/'.$file);
readfile('reporting/'.$file);
exit; 

?>