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

$fichier       = 'tdb.xls';
$inputFileName = 'reporting/'.$fichier;
$dossier       = 'reporting/';

if (!file_exists($inputFileName)) {
	exit("Please run 14excel5.php first.\n");
}

function recuperer_next_vendrediJolie ($debut) { 
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

function weekNumber( $ddate ) {
	$week = date("W", strtotime($ddate));
	return $week;
}

$date_deb_notation = $_REQUEST['date_deb'];
$date_fin_notation = $_REQUEST['date_fin'];
//echo $date_fin_notation;exit;
$id_projet          = $_REQUEST['projet'];
$id_client          = $_REQUEST['client'];
$id_application     = $_REQUEST['application'];
$id_type_traitement = $_REQUEST['type_traitement'];
$matricule_auditeur = $_REQUEST['auditeur'];
$matricule_tlc      = $_REQUEST['tlc'];
$repartition        = $_REQUEST['sortie'];
//$repartition = 'mois';

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
//include('export_style.php');
/* **************************************** */
$a     = 0;
$annee = 0;
$mois  = 0;

$objet       = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objet->load($inputFileName);

$objPHPExcel->getSheet($a);
$objPHPExcel->setActiveSheetIndex($a);
$objWorksheet1 = $objPHPExcel->getActiveSheet();
if($repartition == 'total') {
	$aDateDeb    = $ftxt_dtdeb_;
	$aDateFin    = $ftxt_dtfin_;
	$aDateDebNot = $date_deb_notation;
	$aDateFinNot = $date_fin_notation;
	echo ecrire($a,$objWorksheet1,$listeColExcel,$id_projet, $id_client,$id_application,$aDateDeb,$aDateFin,$aDateDebNot,$aDateFinNot,$annee,$mois,$repartition);
} else {
	$aDates = getDatesBetween ($ftxt_dtdeb_, $ftxt_dtfin_, $repartition);
	$nb_date = count($aDates);
    if($aDates != false) 
    {
		foreach($aDates as $annee => $tab_annee)
		{
			foreach($tab_annee as $mois => $tab_mois)
			{
				$nombre_jr = count($tab_mois);
				//echo 'début = '.$tab_mois[0];echo '<br>';
				//echo 'fin = '.$tab_mois[$nombre_jr - 1];echo '<br>';echo '<br>';echo '<br>';
				$aDateDeb = $tab_mois[0];
				$aDateFin = $tab_mois[$nombre_jr - 1];

				$aDatDebNot = explode('-',$aDateDeb);
				$aDateDebNot = $aDatDebNot[2].'/'.$aDatDebNot[1].'/'.$aDatDebNot[0];
				$aDatFinNot = explode('-',$aDateFin);
				$aDateFinNot = $aDatFinNot[2].'/'.$aDatFinNot[1].'/'.$aDatFinNot[0];
					
				/**
				* 
				* @var ******************** DEBUT ******************************
				* 
				*/
				/*foreach($traitement_abrev as $key_trait => $val_trait)
				{
					$tableauPrest = setTableauSynthesePrestation($id_projet, $id_client,$id_application,$key_trait,$aDateDeb,$aDateFin,0,0,0);
					$nb_valeur = count($tableauPrest);
				}*/
				echo ecrire($a,$objWorksheet1,$listeColExcel,$id_projet, $id_client,$id_application,$aDateDeb,$aDateFin,$aDateDebNot,$aDateFinNot,$annee,$mois,$repartition);

				/**
				* **************************** FIN ************************************
				*/
				$a++;
				$objPHPExcel->createSheet();
				$objPHPExcel->getSheet($a);
				$objPHPExcel->setActiveSheetIndex($a);
				$objWorksheet1 = $objPHPExcel->getActiveSheet();
			}
		}
	}
	$aDateDeb = $ftxt_dtdeb_;
	$aDateFin = $ftxt_dtfin_;
	$aDateDebNot = $date_deb_notation;
	$aDateFinNot = $date_fin_notation;
	echo ecrire($a,$objWorksheet1,$listeColExcel,$id_projet, $id_client,$id_application,$aDateDeb,$aDateFin,$aDateDebNot,$aDateFinNot,$annee,$mois,'total');
}
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/

$file = "TDB_".$repartition."_".str_replace("-","_",$ftxt_dtdeb_)."__".str_replace("-","_",$ftxt_dtfin_).'.xls';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
$objWriter->setPreCalculateFormulas(false);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$file.'"');
$objWriter->save('reporting/'.$file);
readfile('reporting/'.$file);
exit; 

?>