<?php 
  
require_once 'PHPExcel/IOFactory.php';
include_once 'PHPExcel/Writer/Excel5.php';
include_once 'PHPExcel/Writer/Excel2007.php';
require_once 'PHPExcel.php';
require_once 'incfunc.php';
//	require_once 'moteur_.php';
//require_once 'ListeTri.php';



include("/var/www.cache/dgconn.inc");
include("/var/www.cache/siapconn.inc");
include("/var/www.cache/rhconn.inc");

include('function_synthese_dynamique.php');
include('function_dynamique.php');

ini_set('max_execution_time', 0);
ini_set('display_errors', 1);

$id_projet = 88;
$id_client=624;
$id_application=1285;
$id_type_traitement=1;
$date_deb_notation = '2014-08-04';
$date_fin_notation = '2014-08-05';

$fichier = 'export_synthese.xls';
$inputFileName = 'export_synthese/'.$fichier;
$dossier = 'export_synthese/';

if (!file_exists($inputFileName)) 
	{
	exit("Please run 14excel5.php first.\n");
}
//$ftxt_dtdeb = "2013-11-01";
//$ftxt_dtfin = "2013-11-30"; 

$ftxt_dtdeb = $_REQUEST['ftxt_dtdebES'];
$ftxt_dtfin = $_REQUEST['ftxt_dtfinES'];

$deb = explode('/',$ftxt_dtdeb);
$ftxt_dtdeb_ = $deb[2].'-'.$deb[1].'-'.$deb[0];

$fin = explode('/',$ftxt_dtfin);
$ftxt_dtfin_ = $fin[2].'-'.$fin[1].'-'.$fin[0];

$objet = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objet->load($inputFileName);
$objPHPExcel->getSheet(0);

$objWorksheet1 = $objPHPExcel->createSheet();

$mois_courant = substr($ftxt_dtdeb,3,2);
if(strlen($mois_courant) == 2) $mois = substr($mois_courant,1,1);
else $mois = $mois_courant ;


$objPHPExcel->setActiveSheetIndex(1);

$hs = "ES_".str_replace("/","",$ftxt_dtdeb)."_".str_replace("/","",$ftxt_dtfin);
$objWorksheet1->setTitle($hs);

$sheet = $objPHPExcel->getActiveSheet();

$objWorksheet1->getRowDimension(1)->setRowHeight(20);
$objWorksheet1->getRowDimension(3)->setRowHeight(25);


$objPHPExcel->getActiveSheet()->setCellValue('A3', '');
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Montant (Ar)');

$liste_personnel = array();
$liste_cc = array();
$liste_bpo = array();
$liste_oto = array();
$listeColExcel = array("1" => "A","2" => "B" ,"3" => "C" ,"4" => "D" ,"5" => "E" ,"6" => "F" ,"7" => "G" ,"8" => "H" ,"9" => "I" ,"10" => "J" ,"11" => "K" ,"12" => "L" ,"13" => "M" ,"14" => "N" ,"15" => "O" ,"16" => "P" ,"17" => "Q" ,"18" => "R" ,"19" => "S" ,"20" => "T" ,"21" => "U" ,"22" => "V" ,"23" => "W" ,"24" => "X" ,"25" => "Y" ,"26" => "Z" ,"27" => "AA" ,"28" => "AB" ,"29" => "AC" ,"30" => "AD" ,"31" => "AE" ,"32" => "AF" ,"33" => "AG" ,"34" => "AH" ,"35" => "AI" ,"36" => "AJ" ,"37" => "AK" ,"38" => "AL" ,"39" => "AM" ,"40" => "AN" ,"41" => "AO" ,"42" => "AP" ,"43" => "AQ" ,"44" => "AR","45" => "AS","46" => "AT","47" => "AU","48" => "AV","49" => "AW","50" => "AX","51" => "AY","52" => "AZ","53" => "BA","54" => "BB","55" => "BC","56" => "BD","57" => "BE","58" => "BF","59" => "BG","60" => "BH","61" => "BI","62" => "BJ","63" => "BK","64" => "BL","65" => "BM","66" => "BN","67" => "BO","68" => "BP","69" => "BQ","70" => "BR","71" => "BS","72" => "BT","73" => "BU","74" => "BV","75" => "BW","76" => "BX","77" => "BY","78" => "BZ","79" => "CA","80" => "CB","81" => "CC","82" => "CD","83" => "CE","84" => "CF","85" => "CG","86" => "CH","87" => "CI","88" => "CJ","89" => "CK","90" => "CL","91" => "CM","92" => "CN","93" => "CO","94" => "CP","95" => "CQ","96" => "CR","97" => "CS","98" => "CT","99" => "CU","100" => "CV","101" => "CW","102" => "CX","103" => "CY","104" => "CZ","105" => "DA","106" => "DB","107" => "DC","108" => "DD","109" => "DE","110" => "DF","111" => "DG","112" => "DH","113" => "DI");
$style_fill_grey = array('fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'argb' => '#DF013A'			
        )
    )
);

$idim = 0;
$nbdateSem = 0;
$icell= 1;
$date_deb_ = date_create($date_deb);
$date_fin_ = date_create($date_fin);



$objPHPExcel-> getActiveSheet()->freezePane ('B4'); 
$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(80);

$objPHPExcel->getActiveSheet()->mergeCells('C1:J1');

$objPHPExcel->getActiveSheet()->setCellValue('C1', $titreExtraction);
$styleArray = array( 'font' => array( 'bold' => true) ); 
$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);

//========================================== les colonnes du fichier ========================================================

$styleArrayTitle = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID, 
		'color'=>array( 'rgb'=>'ECECEC')
	),
	'font' => array(
		'bold' => true,
		'size' => 10,
		'name' => 'Arial',
		'color' => array('rgb'=> 'D51731')
	),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => '000000'), // C96D6A
		),
	)
);
/**
$objPHPExcel->getActiveSheet()->mergeCells('C2:AB2');	
$objPHPExcel->getActiveSheet()->setCellValue('C2','Informations personnels');	
$objPHPExcel->getActiveSheet()->getStyle('C2:AB2')->applyFromArray($styleArrayTitle);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->mergeCells('AN2:AO2');	
$objPHPExcel->getActiveSheet()->setCellValue('AN2',utf8_encode('Congé'));
$objPHPExcel->getActiveSheet()->getStyle('AN2:AO2')->applyFromArray($styleArrayTitle);
$objPHPExcel->getActiveSheet()->getStyle('AN2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->mergeCells('AP2:AR2');	
$objPHPExcel->getActiveSheet()->setCellValue('AP2',utf8_encode('Indemnité'));	
$objPHPExcel->getActiveSheet()->getStyle('AP2:AR2')->applyFromArray($styleArrayTitle);
$objPHPExcel->getActiveSheet()->getStyle('AP2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->mergeCells('AS2:BJ2');	
$objPHPExcel->getActiveSheet()->setCellValue('AS2','Prime');	
$objPHPExcel->getActiveSheet()->getStyle('AS2:BJ2')->applyFromArray($styleArrayTitle);
$objPHPExcel->getActiveSheet()->getStyle('AS2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->mergeCells('BK2:BU2');	
$objPHPExcel->getActiveSheet()->setCellValue('BK2',utf8_encode('Nombre d\'heures supplémentaires'));	
$objPHPExcel->getActiveSheet()->getStyle('BK2:BU2')->applyFromArray($styleArrayTitle);
$objPHPExcel->getActiveSheet()->getStyle('BK2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->mergeCells('BX2:BZ2');	
$objPHPExcel->getActiveSheet()->setCellValue('BX2','Majoration de nuit');	
$objPHPExcel->getActiveSheet()->getStyle('BX2:BZ2')->applyFromArray($styleArrayTitle);
$objPHPExcel->getActiveSheet()->getStyle('BX2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->mergeCells('CA2:CG2');	
$objPHPExcel->getActiveSheet()->setCellValue('CA2',utf8_encode('Heures majorées'));	
$objPHPExcel->getActiveSheet()->getStyle('CA2:CG2')->applyFromArray($styleArrayTitle);
$objPHPExcel->getActiveSheet()->getStyle('CA2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

*/

//Style des colonnes titres
$styleArrayHeaders1 = array(
	'font' => array(
		'bold'  => true,
		'color' => array('rgb' => PHPExcel_Style_Color::COLOR_WHITE)
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		'wrap'   	 => true
	),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => 'C96D6A'),
		),
	)
);

      $left = array(
	        'alignment'=>array(
            'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
			'wrap'=>true			
			)
			
			);


$headers1 = array("CC","Note","IS4","IS5","IS6","IS7","Accueil","Diagnostic","Traitement de la demande","Conclusion","Ambiance générale","Point d'appui","Point d'amelioration","Préconisation");

$result_notation = getAllNotation($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc);
$nb_notation = pg_num_rows($result_notation);

$array_date = array(1,2,3,4,5);
for ($c = 0; $c<count($headers1); $c++)
{  
	 /** if(in_array($c,$array_date)){
	  $objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'3','Date');
	  }else{*/
	  $objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$icell].'3',$headers1[$c]);
	  //}
	
	$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$icell].'3')->applyFromArray($styleArrayHeaders1);
	$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(15);
	$icell++;
}
  


$lg_pers = 4;
$deb_total = $lg_pers;
$icell_ = 3;
$t = 3;
$i = 0;
$nb = 3;
$init_ = "";
$objWorksheet1->getColumnDimension('A')->setWidth(35);
$objWorksheet1->getColumnDimension('B')->setWidth(10);
$objWorksheet1->getColumnDimension('C')->setWidth(10);
$objWorksheet1->getColumnDimension('D')->setWidth(10);
$objWorksheet1->getColumnDimension('E')->setWidth(10);
$objWorksheet1->getColumnDimension('F')->setWidth(10);
$objWorksheet1->getColumnDimension('G')->setWidth(20);
$objWorksheet1->getColumnDimension('H')->setWidth(20);
$objWorksheet1->getColumnDimension('I')->setWidth(20);
$objWorksheet1->getColumnDimension('J')->setWidth(20);
$objWorksheet1->getColumnDimension('K')->setWidth(20);
$objWorksheet1->getColumnDimension('L')->setWidth(41);
$objWorksheet1->getColumnDimension('M')->setWidth(40);
$objWorksheet1->getColumnDimension('N')->setWidth(40);
$objWorksheet1->getColumnDimension('T')->setWidth(18);
$objWorksheet1->getColumnDimension('AP')->setWidth(18);
$objWorksheet1->getColumnDimension('AZ')->setWidth(17);
$objWorksheet1->getColumnDimension('BD')->setWidth(20);
$objWorksheet1->getColumnDimension('BJ')->setWidth(21);
$objWorksheet1->getColumnDimension('BK')->setWidth(18);
$objWorksheet1->getColumnDimension('CF')->setWidth(18);
$objWorksheet1->getColumnDimension('CW')->setWidth(20);


    $Ligne = 4;
	$cc=12;

	for($k=0;$k< $nb_notation;$k++){
	     
		 
		
	
	     $objPHPExcel->getActiveSheet()->getStyle('A'.$Ligne.':R'.$Ligne)->applyFromArray($left);
		 
	     $lg = pg_fetch_array($result_notation,$k);
		 
		 $id_notation = $lg['id_notation'];
		 $str = calculTotalGeneral($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement);
		 
		 $table_valeur = explode('||',$str); 
		 $note =  $table_valeur[0];
		 $prenom_cc = getPrenomPersonnel($lg['matricule']);
		 $tableau_is = getIS($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc);
		 $result_repartition = fetchAllRepartition();
		 
	     $objPHPExcel->getActiveSheet()->setCellValue('A'.$Ligne,$lg['matricule'].'_'.$prenom_cc);
	     $objPHPExcel->getActiveSheet()->setCellValue('B'.$Ligne, $note);
		 $col = 3;
		    for($i=4;$i<=7;$i++)
				{
					$is = 'IS'.$i;
					$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$col].$Ligne, $tableau_is[$id_notation][$is]);
					$col++;
				}
	    $col_rep = 7;
		
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
			$objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$col_rep].$Ligne, $val_rep);
			
			$col_rep++;
		}
	          
	
		
		  
		  $objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$cc].$Ligne, $lg['point_appui']);
		  $objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$cc+1].$Ligne, $lg['point_amelioration']);
		  $objPHPExcel->getActiveSheet()->setCellValue($listeColExcel[$cc+2].$Ligne, $lg['preconisation']);

		  $Ligne++;		
	}

$datahs = array();

$ii = (count($tabDate)*6);
$i2 = 1;


for ($i1 = 0; $i1 <= count($headers1) ;$i1++ )
{
	/**$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$i2].'3')->applyFromArray(array('fill'=>array( 'type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color'=>array( 'rgb'=>'CCCCCC'))));
*/
	if ($i2<=14){
	    
		$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$i2].'3')->applyFromArray(array('fill'=>array( 'type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color'=>array( 'rgb'=>'D8D8D8'))));
		    if($i2==12){
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$i2].'3')->applyFromArray(array('fill'=>array( 'type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color'=>array( 'rgb'=>'92D050'))));
			}
			elseif($i2==13){
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$i2].'3')->applyFromArray(array('fill'=>array( 'type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color'=>array( 'rgb'=>'FF0000'))));
			}
			elseif($i2==14){
			$objPHPExcel->getActiveSheet()->getStyle($listeColExcel[$i2].'3')->applyFromArray(array('fill'=>array( 'type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color'=>array( 'rgb'=>'60497B'))));
			}
	}

	$i2++;
}

$styleFont = $objPHPExcel->getActiveSheet()->getStyle('A3')->getBorders()->applyFromArray(
array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '808080'))));

/*****************************************************************************/
// Feuille 1
/*****************************************************************************/

$styleArrayMatr = array(
	'fill' => array( 
		'type'=>PHPExcel_Style_Fill::FILL_SOLID, 
		'color'=>array( 'rgb'=>'ECECEC')
		),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => '969696'),
		)
	)
);

$styleArrayTotal = array(
	'fill' => array( 
		'type'=>PHPExcel_Style_Fill::FILL_SOLID, 
		'color'=>array( 'rgb'=>'FFA84D')
		),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => 'C96D6A'),
		)
	),
	'font' => array(
		'bold'  => true,
	)
);

$nbpers = 1;

$nb_matr = count($liste_personnel);
$lig = 0;
$col = 1;
$_tab = array();
//$save = 0;
$file = "etatsalaire_".str_replace("-","_",$ftxt_dtdeb_)."__".str_replace("-","_",$ftxt_dtfin_).'.xls';

$fin_total = $lg_pers;

$cpt_batch = 4+count($liste_personnel);

$cpt_lig_total = 4+count($liste_personnel);



/*****************************************************************************/
// Fin Feuille 1
/*****************************************************************************/


/***************************************************************************************
 ** TRI
 ***************************************************************************************/

 $objWorksheet = $objPHPExcel->createSheet();

$title = "TRI_".str_replace("/","",$ftxt_dtdeb)."_".str_replace("/","",$ftxt_dtfin);
$objWorksheet->setTitle($title);

$objPHPExcel->setActiveSheetIndex(2);
$sheet1 = $objPHPExcel->getActiveSheet();

$objPHPExcel-> getActiveSheet()->freezePane ('I3'); 
$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(80);



//Titre des colonnes
$styleArrayHeaders = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'wrap'   	 => true
	),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => 'E5B9B7'),
		)
	),
	'fill' => array(
		'type'  =>PHPExcel_Style_Fill::FILL_SOLID,
		'color' =>array('rgb'=>'666699')
		),
	'font' => array(
		'color' => array('rgb' => PHPExcel_Style_Color::COLOR_WHITE)
	)
);

$styleArrayBu = array(
	'fill' => array(
		'type'  =>PHPExcel_Style_Fill::FILL_SOLID,
		'color' =>array('rgb'=>'993366') 
	),
	'font' => array(
		'bold'  => true,
		'color' => array('rgb' => PHPExcel_Style_Color::COLOR_WHITE)
	)
);

$styleArrayDept = array(
	'fill' => array(
		'type'  =>PHPExcel_Style_Fill::FILL_SOLID,
		'color' =>array('rgb'=>'E5B9B7')
	),
	'font' => array(
		'bold'  => true,
	)
);
/**
$styleArrayUp = array(
	'fill' => array(
		'type'  =>PHPExcel_Style_Fill::FILL_SOLID,
		'color' =>array('rgb'=>'969696') 
	),
	'font' => array(
		'bold'  => true,
	)
);
$styleArrayFct = array(
	'fill' => array(
		'type'  =>PHPExcel_Style_Fill::FILL_SOLID,
		'color' =>array('rgb'=>'ECECEC')  
	),
	'font' => array(
		'bold'  => true,
	),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => '969696'),
		)
	),
);
$styleArrayCodir = array(
	'fill' => array( 
		'type'=>PHPExcel_Style_Fill::FILL_SOLID, 
		'color'=>array( 'rgb'=>'ECECEC')
		),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => '969696'),
		)
	),
);

$objWorksheet->getRowDimension('2')->setRowHeight(40);
*/






$objPHPExcel->removeSheetByIndex(0);
$objPHPExcel->setActiveSheetIndex(0);



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$file.'"');
$objWriter->save('export_synthese/'.$file);
readfile('export_synthese/'.$file);
exit; 



?>