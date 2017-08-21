<?php 
session_start();

include("/var/www.cache/dgconn.inc");

include('function_union.php');

require_once 'PHPExcel/IOFactory.php';
include_once 'PHPExcel/Writer/Excel5.php';
include_once 'PHPExcel/Writer/Excel2007.php';
require_once 'PHPExcel.php';
require_once 'PHPExcel/listColumnExcel.php';
include('export_style.php');

PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
// ini_set('display_startup_errors',1);
// ini_set('display_errors',1);
// error_reporting(-1); 

$fichier = 'grille.xls';
$inputFileName = 'reporting/'.$fichier;
$dossier = 'reporting/';

if (!file_exists($inputFileName)) {
	exit("Please run 14excel5.php first.\n");
}
#############################
$objet = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objet->load($inputFileName);
$iWorkSheet = 0;

$comma_separated ="";
$query_sheet = getAllSheet();
// print_r($_REQUEST);
// exit();
while($res_sheet = pg_fetch_array($query_sheet)) 
{
		$sql_strings ="";
		$sql = "";
		$sql_note ="";
		$arrayIDNoti  = array();
		$arrayNote = array();
		$arrayPonderation = array();
		$arrayNotecsi = array();
		$arrayPondClsmnt = array();
		$arrayInfotete = array();
		$arrayID =  array();
		$arrayRslt =  array();
		$arrayComm =  array();
		$tableauBord =  array();
		$resGrille = array();
		$array_all = array();
		$indiceI = 0;
		$objWorksheet1 = $objPHPExcel->createSheet($iWorkSheet);
		
		$id_type_traitement = $res_sheet['id_type_traitement'];
		$id_projet = $res_sheet['id_projet'];
		$id_application = $res_sheet['id_application'];
		$id_client      = $res_sheet['id_client'];
		$iddd_application = $res_sheet['id_application'];
		if($res_sheet['id_type_traitement'] == 1) $typTrait = 'AE';
		if($res_sheet['id_type_traitement'] == 2) $typTrait = 'AS';
		if($res_sheet['id_type_traitement'] == 3) $typTrait = 'MAIL';
		if($res_sheet['id_type_traitement'] == 4) $typTrait = 'TCHAT';
		
		$arrayGridAll  	= getIDNotationAllGrille($conn,$id_client,$id_application,$id_projet,$id_type_traitement);
		$arrayGridBuffer = array_chunk($arrayGridAll,85);
		 $nBarrayGridBuffer = count($arrayGridBuffer);
		// echo $comma_separated .= $id_application.'**'.implode(",", $arrayGridAll);
		for($ittest = 0; $ittest < $nBarrayGridBuffer; $ittest++)
		{
		$array_all = $arrayGridBuffer[$ittest];
		$sql_string_note_header = getNotenHeader($conn,$id_client,$id_application,$id_projet,$id_type_traitement,$array_all);
		$array_all = array_unique($array_all);
		list($sql_note,$sql_entete) = explode('##',$sql_string_note_header);
		list($arrayNote,$arrayPonderation,$arrayNotecsi,$arrayPondClsmnt,$arrayInfotete) = getNote($conn,$sql_note,$sql_entete);
		
		$arrayIDNoti 	= $array_all;
		
		
		$indiceI = 0;
		$objWorksheet1 = $objPHPExcel->createSheet($iWorkSheet);
		$sql 	= getAllGrille($conn,$id_client,$id_application,$id_projet,$id_type_traitement,$array_all);
		$penalite_projet = get_penalite_projet( $id_projet , $id_type_traitement);
		$arrayID = getAllIdgrille($conn,$id_client,$id_application,$id_projet,$id_type_traitement);
		$Nb = pg_num_rows(  $arrayID );
		$query  = pg_query($conn,$sql ) or die('error : getAllGrille ');
		
		while($resGrille  = pg_fetch_array($query))
			{
				if(!isset($arrayRslt[$resGrille['id_grille']][$resGrille['section']]))
				{
					$arrayRslt[$resGrille['id_grille']] = $resGrille;	
				}
			}
		
		/*#### EN TETE ##### */
		$v = 1;
		
		foreach ($arrayIDNoti as $valuenotiD) {
			
			$arrayComm = actualiserListeNotationExport($id_projet, $id_client, $id_application, $id_type_traitement,$valuenotiD);
			if($v == 1)
			{
				$v1 = 1;
				$vLigne = 3;
				
				$titleSheet = $arrayInfotete['prestation'][$valuenotiD].'-'.$typTrait;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,'Client');
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,'Prestation');
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,'Types de traitements');
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,'Evaluateur');
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"Date d'appel");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"Date notation");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"Références des communications");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"Matricule CC");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"Type d'appel");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"Numéro dossier");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"Numéro commande");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"Note");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"Note réduite");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"IS4");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
											
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"IS5");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"IS6");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne +=1 ;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"IS7");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				
				
				$v1 +=1;
				$vLigne = 3;
				$nom_client = (isset($arrayInfotete['nom_client'][$valuenotiD]))? $arrayInfotete['nom_client'][$valuenotiD] : '';
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$nom_client);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				$prestation = (isset($arrayInfotete['prestation'][$valuenotiD]))?$arrayInfotete['prestation'][$valuenotiD]:'';
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$prestation);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$typTrait);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				
				
				$iMatrNota = utf8_encode(infoPers($arrayInfotete['matricule_notation'][$valuenotiD]));
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$iMatrNota);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$iMatrNota = "";
				
				$vLigne += 1;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,date_fr($arrayInfotete['date_entretien'][$valuenotiD])."\t");
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,date_fr($arrayInfotete['date_notation'][$valuenotiD])."\t");
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$arrayInfotete['nom_fichier'][$valuenotiD]);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				$iMatr = utf8_encode(infoPers($arrayInfotete['matricule'][$valuenotiD]));
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$iMatr);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				$iTypeApp = '';
				$iTypeApp = getAppelType($valuenotiD);
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$iTypeApp); /*TYPE APPEL B*/
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$arrayInfotete['numero_dossier'][$valuenotiD]);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$iMatr = "";

				$vLigne += 1;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$arrayInfotete['numero_commande'][$valuenotiD]);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				
				
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$arrayComm[$valuenotiD]['totalg']."\t");
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$iMatr = "";
				
				$vLigne += 1;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$arrayComm[$valuenotiD]['notereduite']."\t");
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$iMatr = "";
				
				
				
				$vLigne += 1;
				if(isset($arrayInfotete['is4'][$valuenotiD])) $is4 = $arrayInfotete['is4'][$valuenotiD];
				else $is4 = 0;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$is4);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				if(isset($arrayComm[$valuenotiD]['is4'])) $is4C = $arrayComm[$valuenotiD]['is4'];
				else $is4C = 0;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$is4C);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				$is5C = (isset($arrayComm[$valuenotiD]['is5']))?$arrayComm[$valuenotiD]['is5']:0;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$arrayComm[$valuenotiD]['is5']);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$vLigne += 1;
				$is6C = (isset($arrayComm[$valuenotiD]['is6']))?$arrayComm[$valuenotiD]['is6']:0;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$is6C);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,$arrayComm[$valuenotiD]['is7']);
				$objWorksheet1->mergeCells($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						
				$vLigneLib = $vLigne;
				$val_freeze = $vLigne;
				$v1Lib = $v1;
				foreach($arrayComm[$valuenotiD]['libelle'] as $value_libelle)
				{
					$v1 = 1;
					$vLigneLib +=1;
					
					list($reel_libelle,$value_reel_libelle) = explode('####',$value_libelle);
					$reel_libelle = utf8_encode($reel_libelle);
					
					$objWorksheet1->setCellValue($listColExcel[$v1].$vLigneLib,$reel_libelle);
					$objWorksheet1->getStyle($listColExcel[$v1].$vLigneLib.':'.$listColExcel[$v1].$vLigneLib)->applyFromArray($styleArray);
					$objWorksheet1->getStyle($listColExcel[$v1].$vLigneLib.':'.$listColExcel[$v1].$vLigneLib)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					
					
					
					$objWorksheet1->setCellValue($listColExcel[$v1Lib].$vLigneLib,$value_reel_libelle);
					$objWorksheet1->mergeCells($listColExcel[$v1Lib].$vLigneLib.':'.$listColExcel[$v1Lib+2].$vLigneLib);
					$objWorksheet1->getStyle($listColExcel[$v1Lib].$vLigneLib.':'.$listColExcel[$v1Lib+2].$vLigneLib)->applyFromArray($styleArray);
					$objWorksheet1->getStyle($listColExcel[$v1Lib].$vLigneLib.':'.$listColExcel[$v1Lib+2].$vLigneLib)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				}
				$v1 = 1;
				$vLigne = $vLigneLib + 1 ;
				
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,'Critère par categorie ');
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($note_finale_style_gras);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$v1++;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,'Note');
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($note_finale_style_gras);
				
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$v1+=1;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"Commentaire");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($note_finale_style_gras);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->getColumnDimension($listColExcel[$v1])->setWidth("40");
				
				$v1+=1;
				$objWorksheet1->setCellValue($listColExcel[$v1].$vLigne,"SI");
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($note_finale_style_gras);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v1].$vLigne.':'.$listColExcel[$v1].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->getColumnDimension($listColExcel[$v1])->setWidth("3");
				$v = 5;
				
			}else
			{	
				$vLigneLib = 2;
				$vLigne = 3;
				
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$arrayInfotete['nom_client'][$valuenotiD]);/*CLIENT*/
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				
				$vLigne +=1;/*PRESTATION*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$arrayInfotete['prestation'][$valuenotiD]);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				
				$vLigne +=1;/*TYPE TTM*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$typTrait);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				
				$vLigne +=1;/*EVALUATEUR*/
				
				$iMatrNota = utf8_encode(infoPers($arrayInfotete['matricule_notation'][$valuenotiD]));
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$iMatrNota);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				$iMatrNota = "";
				
				$vLigne +=1;/*DATE APPEL*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,date_fr($arrayInfotete['date_entretien'][$valuenotiD])."\t");
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				
				$vLigne +=1;/*DATE EVALUATION*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,date_fr($arrayInfotete['date_notation'][$valuenotiD])."\t");
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				
				$vLigne +=1;/*REF COMMuN*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$arrayInfotete['nom_fichier'][$valuenotiD]);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				
				$vLigne +=1;/*MATRICULE CC*/
				
				$iMatr = utf8_encode(infoPers($arrayInfotete['matricule'][$valuenotiD]));
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$iMatr);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				$iMatr = "";
				
				$vLigne +=1;/*TYPE APPEL*/
				
				$iTypeApp = '';
				$iTypeApp = getAppelType($valuenotiD);
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$iTypeApp);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				
				$vLigne +=1;/*NUM DOSSIER*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$arrayInfotete['numero_dossier'][$valuenotiD]);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				$val_freeze = $vLigne + 1;
				
				$vLigne +=1;/*NUM COMMANDE*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$arrayInfotete['numero_commande'][$valuenotiD]);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				$val_freeze = $vLigne + 1;
				
				$vLigne +=1;/*NOTE Gle*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$arrayComm[$valuenotiD]['totalg']."\t");
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				$val_freeze = $vLigne + 1;
				
				$vLigne +=1;/*NOTE REDUITE*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$arrayComm[$valuenotiD]['notereduite']."\t");
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				$val_freeze = $vLigne + 1;
				
				$vLigne +=1;/*IS4*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$arrayComm[$valuenotiD]['is4']);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				$val_freeze = $vLigne + 1;
				
				$vLigne +=1;/*IS5*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$arrayComm[$valuenotiD]['is5']);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				$val_freeze = $vLigne + 1;
				
				$vLigne +=1;/*IS6*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$arrayComm[$valuenotiD]['is6']);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				$val_freeze = $vLigne + 1;
				
				$vLigne +=1;/*IS7*/
				
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$arrayComm[$valuenotiD]['is7']);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				
				$ivalue_tmp = $vLigneLib+1;
				foreach($arrayComm[$valuenotiD]['libelle'] as $value_libelle)
				{
					$vLigne +=1;/*ACCUEI->QUALITE SONORE*/
					list($reel_libelle,$value_reel_libelle) = explode('####',$value_libelle);
				
					$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,$value_reel_libelle);
					$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
					$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objWorksheet1->mergeCells($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne);
				}
				
				$vLigne +=1;/*TITRE : NOTE, COMMENTAIRE, SI*/
				$objWorksheet1->setCellValue($listColExcel[$v].$vLigne,'Note');
				$objWorksheet1->getColumnDimension($listColExcel[$v])->setWidth("8");
				$objWorksheet1->setCellValue($listColExcel[$v+1].$vLigne,'Commentaire');
				$objWorksheet1->getColumnDimension($listColExcel[$v+1])->setWidth("50");
				$objWorksheet1->setCellValue($listColExcel[$v+2].$vLigne,'SI');
				$objWorksheet1->getColumnDimension($listColExcel[$v+2])->setWidth("3");
				
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objWorksheet1->getStyle($listColExcel[$v].$vLigne.':'.$listColExcel[$v+2].$vLigne)->applyFromArray($note_finale_style_gras);
				$val_freeze = $vLigne + 1;
				$v+=3;
			}
			$ivalue_tmp = $vLigne;
		}

		#########bODY############
		
		for($k = 0 ; $k < $Nb ; $k++) {
			 $row = pg_fetch_array($arrayID,$k);
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
				$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_ponderation'] = array(); // Njiva
				$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['eliminatoire'] = $row['flag_eliminatoire'];	
				$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['id_grille_application'] = $row['id_grille_application'];
			   $tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_is'] = $row['flag_is'];	
			   $tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['id_repartition'] = $row['id_repartition'];	
			 }

			 if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'])) {
				$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'][$row['note']] = $row['libelle_description'];			
			 }

			 if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'])) {
				$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'][$row['libelle_description']] = $row['ponderation'];			
				$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_ponderation'][$row['libelle_description']] = isset($row['flag_ponderation']) ? $row['flag_ponderation'] : 0;	// Njiva		
			 }
			
		}
		
		
		
		$buffer = 0 ;
		$ivalue_tmp+=1;
		$iLetterCol = 1;
		$iLigne = $ivalue_tmp;
		
		foreach ($tableauBord as $key => $value) {
			foreach ($value as $key1 => $value2) {
				foreach ($value2 as $key11 => $value22) {
					foreach ($value22 as $key111 => $value222) {
						foreach ($value222['item'] as $key1111 => $value2222) {
						
						
							if($buffer !=  $arrayRslt[$key1111]['id_classement'] && $buffer != 0)
							{
							$iLetterCol = 1;
							
							$content .= '<br/>'.$buffer.'#####|####|';
							$objWorksheet1->setCellValue($listColExcel[$iLetterCol].$iLigne,$buffer_libelle_clmnt); /*TEXT CATEGORIE RESUME*/
							
							$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($styleArray);
							$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($grille_style_gras);
							$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							
								foreach ($arrayIDNoti as $valuenotiD) {
									$ponderation  = $arrayPonderation[$buffer][$valuenotiD];
									$note 		  = $arrayNote[$buffer][$valuenotiD];
									$notecsi 	  = $arrayNotecsi[$buffer][$valuenotiD];
									$key_section  = $buffer;
									if($ponderation != 0)
										$total_classement = $note/$ponderation;
									else $total_classement = 0;
									$ponderation_classemnt = $arrayPondClsmnt[$valuenotiD][$buffer];
									
									$total_classement =  get_nombre_si($notecsi,$key_section,$penalite_projet,$total_classement); 
									
									$total_classement =  $total_classement*10;
									
									if($ponderation == 0) { $total_classmnt = 0;}
									else { $total_classmnt = number_format($total_classement, 2, ',', '');}
									
									if($total_classement == 0 && $ponderation == 0)
									{
										$total_classmnt = 10;
									}
									
									$total_section = $ponderation_classement*$total_classmnt;
									
									if(isset($array_section[$valuenotiD]))
									{
										$array_section[$valuenotiD] +=$total_section;
									}
									else {
										$array_section[$valuenotiD] =$total_section;
									}
									
									if( $total_classmnt > 100) {$total_classmnt = $total_classmnt/100;}
									 if($id_client == 643 || $id_client == 642) /* && $id_type_traitement != 3) */
										{
											$total_classmnt = $total_classmnt*10;
										}
									$content .= '|'.$total_classmnt;
									
									 if($id_client == 643 || $id_client == 642 && $id_type_traitement == 3)
									 {
										 $total_classmnt = $total_classmnt/10;
									 }	
									 if($id_client == 643 || $id_client == 642 && $id_type_traitement == 1)
									 {
										 $total_classmnt = $total_classmnt*10;
									 }	
									 $total_classmnt = number_format($total_classmnt, 2, ',', '');
									$iLetterCol +=1;
									/*DEBUT NOTE RESUME CATEGORIE*/
									$objWorksheet1->setCellValue($listColExcel[$iLetterCol].$iLigne,$total_classmnt."\t");
									$objWorksheet1->mergeCells($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol+2].$iLigne);
									$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol+2].$iLigne)->applyFromArray($styleArray);
									$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($grille_style_gras);
									$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									/*FIN NOTE RESUME CATEGORIE*/
									
									$iLetterCol +=2;
									
								}
								$content .='|<br/><br/>';
								$iLetterCol = 1;
								$iLigne		+=2;
								$buffer = $arrayRslt[$key1111]['id_classement'];
								$buffer_libelle_clmnt = $arrayRslt[$key1111]['libelle_classement'];
								
							}
							else
							{
								$buffer = $arrayRslt[$key1111]['id_classement']; 
								$buffer_libelle_clmnt = $arrayRslt[$key1111]['libelle_classement'];
							}
							$ponderation_classement = $arrayRslt[$key1111]['ponderation_classement']; 
							$content .= $arrayRslt[$key1111]['id_classement'].'**'.$arrayRslt[$key1111]['libelle_classement'].'|'.$arrayRslt[$key1111]['libelle_grille'];
							
							$libelle_grille = utf8_encode($arrayRslt[$key1111]['libelle_grille']);/*LIBELLE DANS COLONNE A*/
							$objWorksheet1->setCellValue($listColExcel[$iLetterCol].$iLigne,$libelle_grille);
							$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne)->getAlignment()->setWrapText(true);
							$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($styleArray);
							$iLetterCol++; 
							$ponderation = $arrayRslt[$key1111]['id_classement'];
							$objWorksheet1->getColumnDimension('A')->setWidth('50');
							
							
							foreach ($arrayIDNoti as $valuenotiD) {
							
								$value_note 	   =  'note__'.$valuenotiD;
								$value_commentaire_si =  'commentaire_si__'.$valuenotiD;
								$value_commentaire =  'commentaire__'.$valuenotiD;
								$value_ponderation =  'flag_ponderation__'.$valuenotiD;
								$value_elimination =  'flag_eliminatoire__'.$valuenotiD;
								$value_matricule   =  'matricule__'.$valuenotiD;
								$ponderation_matr  =  'ponderation__'.$valuenotiD;	
					
								if( $arrayRslt[$key1111][$value_note] == '' ||  $arrayRslt[$key1111][$value_note] == 0 )
								{
									$note  = 0;
									$note_ = 0;
								}
								else
								{
									$note  = $arrayRslt[$key1111][$value_note];
									$note_ = $note;
								}
								
								if($arrayRslt[$key1111][$value_ponderation] == 1 &&   $arrayRslt[$key1111][$value_note] == 1 &&   $arrayRslt[$key1111][$ponderation_matr] == 0 /*&&   $arrayRslt[$key1111][$value_elimination] == 0*/)
								{
									$note  ='N';
									
								}
								
								
								
							if(isset($array_notation[$arrayRslt[$key1111]['id_classement']][$valuenotiD]))
							{
								$array_notation[$arrayRslt[$key1111]['id_classement']][$valuenotiD] = $array_notation[$arrayRslt[$key1111]['id_classement']][$valuenotiD] + $note_*$ponderation;
							}
							else
							{
								$array_notation[$arrayRslt[$key1111]['id_classement']][$valuenotiD] = $note_*$ponderation;
								
							}
							if($note !='N'){
							$note = $note;//number_format($note, 0, ',', '');
							if( $note <1)
								{
									$note = number_format($note, 1, ',', '');
								}
							else{
								$note = number_format($note, 0, ',', '');
								}
							}
							if($note > 100) {$note  = $note/100;}
							if(isset($arrayRslt[$key1111][$value_commentaire]))
								$icomment_value = utf8_encode($arrayRslt[$key1111][$value_commentaire]);
							else $icomment_value = '';
							
							$content .='|'.$note.'|'.$icomment_value;
							$objWorksheet1->setCellValue($listColExcel[$iLetterCol].$iLigne,$note."\t");
							$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($styleArray);
							
							$iLetterCol++;
							$objWorksheet1->setCellValue($listColExcel[$iLetterCol].$iLigne,$icomment_value."\t");
							$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($styleArray);
							
							$iLetterCol++;
							$objWorksheet1->setCellValue($listColExcel[$iLetterCol].$iLigne,$arrayRslt[$key1111][$value_commentaire_si]."\t");
							$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($styleArray);
							$objPHPExcel->getActiveSheet()->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
							
							$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($styleArray);
							$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($styleArray);
							$iLetterCol++;
							
							
							}
							$content .='<br/>';
							$iLetterCol = 1;
							$iLigne ++;
						}
						
					}
				}
			}
		}
		$content .= '<br/>'.$buffer.'#####|####|';
		
		$objWorksheet1->setCellValue($listColExcel[$iLetterCol].$iLigne,$arrayRslt[$key1111]['libelle_classement']); 
		$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($styleArray);
		$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($grille_style_gras);
		$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$iLetterCol = 2;
			foreach ($arrayIDNoti as $valuenotiD) {
				$ponderation  = $arrayPonderation[$buffer][$valuenotiD];
				$note 		  = $arrayNote[$buffer][$valuenotiD];
				$notecsi 	  = $arrayNotecsi[$buffer][$valuenotiD];
				$key_section  = $buffer;
				$total_classement = $note/$ponderation;
				
				$total_classement =  get_nombre_si($notecsi,$key_section,$penalite_projet,$total_classement); 
				if($ponderation == 0) { $total_classmnt = 0;}
				else { $total_classmnt = $total_classement;}
				
				$sum_ponderation = array_sum($arrayPondClsmnt[$valuenotiD]);
				$totalIS = $array_section[$valuenotiD]/$sum_ponderation; 
				$resPresta = getInfoPresta($valuenotiD);
				$idd_client = $resPresta['id_client'];
				$idd_typettmt = $resPresta['id_type_traitement'];
				
				if(($idd_client == 643 || $idd_client == 642) && $idd_typettmt != 3)
					{
						$total_classmnt = $total_classmnt*10;
					}
				if( $idd_typettmt == 3)
					{
						$total_classmnt = $total_classmnt/10;
					}
				
				if( $total_classmnt > 100) {$total_classmnt = $total_classmnt/100;}
				if($id_client == 643 || $id_client == 642) /* && $id_type_traitement != 3) */
					{
						$total_classmnt = $total_classmnt*10;
					}
				if($id_client == 643 || $id_client == 642 && $idd_typettmt == 3) 
					{
						$total_classmnt = $total_classmnt/10;
					}
					
				if(($idd_typettmt == 1 || $idd_typettmt == 2) && ($id_client != 643 || $id_client != 642))
				{
					$total_classmnt = $total_classmnt*10;
				}
				if(($idd_typettmt == 3 || $idd_typettmt == 4) && ($id_client != 643 || $id_client != 642))
				{
					$total_classmnt = $total_classmnt/10;
				}
				
				$total_classmnt = number_format($total_classmnt, 2, ',', '');
				$content .= '|'.$total_classement.'$$'.$totalIS.'$$';
				$objWorksheet1->setCellValue($listColExcel[$iLetterCol].$iLigne,$total_classmnt."\t"); //Note finale
				$objWorksheet1->mergeCells($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol+2].$iLigne);
				$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($styleArray);
				$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne.':'.$listColExcel[$iLetterCol].$iLigne)->applyFromArray($grille_style_gras);
				$objWorksheet1->getStyle($listColExcel[$iLetterCol].$iLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$iLetterCol += 3;
			}
	$iLetterCol = 2;
			
			// echo $content .='|<br/><br/>'; 
	$iLetterCol = 1;
	$iLigne ++;
	// $objWorksheet1->freezePane('B'.$val_freeze );
	$objWorksheet1->freezePane('B27');
	$objWorksheet1->getSheetView()->setZoomScale(80);
	$titleSheet = substr($titleSheet, 0, 30);
	$objWorksheet1->setTitle("$titleSheet");
	$objWorksheet1->freezePane('B'.$val_freeze );
	$iWorkSheet++;
  }
  
}
###############FILTRE####################################
$objWorksheet1 = $objPHPExcel->createSheet($iWorkSheet);
$objWorksheet1->setTitle("FILTRE UTILISE");
$objWorksheet1->setCellValue('A1','Matricule CC');
$ccfiltre = $_REQUEST['cc'];
$arrayTmpCC = array();
$arrayTmpEval = array();
$arrayEvalfitre = array();
if(isset($_REQUEST['cc']) && $_REQUEST['cc'] != 0){
$arrayCCfitre = explode(',',$_REQUEST['cc']);

	foreach( $arrayCCfitre as $valueCC)
	{
		
		array_push($arrayTmpCC, utf8_encode(infoPers($valueCC)));
	}
	$ccfiltre = implode("\n", $arrayTmpCC);
}
else $ccfiltre = 'Vide';

$objWorksheet1->setCellValue('B1',$ccfiltre);
$objWorksheet1->getColumnDimension('B')->setWidth('50');
$objWorksheet1->getStyle('B1')->getAlignment()->setWrapText(true);
$objWorksheet1->getStyle('A1:B1')->applyFromArray($styleArray);
$objWorksheet1->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objWorksheet1->setCellValue('A2','Fichier');

$objWorksheet1->setCellValue('A3','Evaluateur');
if(isset($_REQUEST['eval']) && $_REQUEST['eval'] != 0){
$arrayEvalfitre = explode(',',$_REQUEST['eval']);

	foreach( $arrayEvalfitre as $valueEval)
	{
		
		array_push($arrayTmpEval, utf8_encode(infoPers($valueEval)));
	}
	$evalFiltre = implode("\n", $arrayTmpEval);
}
else $evalFiltre = 'Vide';
$objWorksheet1->setCellValue('B3',$evalFiltre);
$objWorksheet1->getStyle('B3')->getAlignment()->setWrapText(true);
$objWorksheet1->getStyle('A3:B3')->applyFromArray($styleArray);
$objWorksheet1->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objWorksheet1->setCellValue('A4',"Type d'appel");
if(isset($_REQUEST['type_appel']) && $_REQUEST['type_appel'] != "" && $_REQUEST['type_appel'] != 0)
{
	$type_appelF =  $_REQUEST['type_appel']; 
	$typeappFiltre = "";
	$array_type  = explode(",",$_REQUEST['type_appel']); 
	foreach( $array_type as $id_typo)
	{
		$typeappFiltre .=getAppelTypeByidTypo($id_typo)."\n";
	}
}
else $typeappFiltre = "Vide";
$objWorksheet1->setCellValue('B4',$typeappFiltre);
$objWorksheet1->getStyle('A4:B4')->applyFromArray($styleArray);
$objWorksheet1->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$objWorksheet1->setCellValue('A5','Nom du client');
$objWorksheet1->setCellValue('B5',$nom_client);
$objWorksheet1->getStyle('A5:B5')->applyFromArray($styleArray);

$objWorksheet1->setCellValue('A6','Prestation');
$objWorksheet1->setCellValue('B6',$prestation);
$objWorksheet1->getStyle('A6:B6')->applyFromArray($styleArray);

$objWorksheet1->setCellValue('A7','Type de traitement');
$objWorksheet1->setCellValue('B7',$typTrait);
$objWorksheet1->getStyle('A7:B7')->applyFromArray($styleArray);

$objWorksheet1->setCellValue('A8','Date de notation');
if(isset( $_REQUEST['dt_notation1']) && isset($_REQUEST['dt_notation2']) && $_REQUEST['dt_notation2'] !='')
{
	$dateNotationFiltre = "entre ".date_fr($_REQUEST['dt_notation1'])." et ".date_fr($_REQUEST['dt_notation1']);
}else {$dateNotationFiltre ="Vide";}
$objWorksheet1->setCellValue('B8',$dateNotationFiltre);
$objWorksheet1->getStyle('A8:B8')->applyFromArray($styleArray);

$objWorksheet1->setCellValue('A9',"Date d'appel");
if(isset( $_REQUEST['dt_appel1']) && isset($_REQUEST['dt_appel2']) && $_REQUEST['dt_appel2']!= '')
{
	$dateAppelFiltre = "entre ".date_fr($_REQUEST['dt_appel1'])." et ".date_fr($_REQUEST['dt_appel2']);
}else {$dateAppelFiltre ="Vide";}
$objWorksheet1->setCellValue('B9',$dateAppelFiltre);
$objWorksheet1->getStyle('A9:B9')->applyFromArray($styleArray);

$objWorksheet1->setCellValue('A10',"Note");
$noteFiltre = $_REQUEST['id_note_filtre'];
$noteF1 =  $_REQUEST['id_valeur_note_1'];
$noteF2 =  $_REQUEST['id_valeur_note_2'];

	$clause_note = "Vide";

	if( $noteFiltre == 1 )
	{
		$clause_note =" note égale ".$noteF1;
	}

	if( $noteFiltre == 2 )
	{
		$clause_note =" note  entre ".$noteF1."  et ".$noteF2;
	}

	if( $noteFiltre == 3 )
	{
		$clause_note =" note strictement inférieure à ".$noteF1;
	}
	if( $noteFiltre == 4 )
	{
		$clause_note =" note inférieure ou égale à ".$noteF1;
	}
	if( $noteFiltre == 5 )
	{
		$clause_note =" strictement supérieure à ".$noteF1;
	}

	if( $noteFiltre == 6 )
	{
		$clause_note =" note supérieure ou égale à ".$noteF1;
	}

	$clause_note = $clause_note;

$objWorksheet1->setCellValue('B10',$clause_note);
$objWorksheet1->getStyle('A10:B10')->applyFromArray($styleArray);


$objWorksheet1->getColumnDimension($listColExcel[1])->setWidth("70");
$objWorksheet1->getStyle('A1:A10')->applyFromArray($styleArray);
#######################Filtre##############################



$objWorksheet1->getSheetView()->setZoomScale(80);

$file = "Export_grille ".$titleSheet." ".date("d-m-Y His").'.xls';

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
$objWriter->setPreCalculateFormulas(false);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$file.'"');
$objWriter->save('reporting/'.$file);
readfile('reporting/'.$file); 
// echo $comma_separated ;
exit; 


############################Function#####################################
function getAppelTypeByidTypo($id_typologie){
	$sql =" select distinct libelle_typologie,id_typologie from cc_sr_typologie where id_typologie =".$id_typologie;
	$query  = pg_query($sql) or die('getAppelTypeByidTypo');
	$res  = pg_fetch_array( $query, 0 );
	return $res['libelle_typologie'];
}

function getIDNotationAllGrille($conn,$id_client,$id_application,$id_projet,$id_type_traitement)
{
	 global $iconn;
  $iarrayRsltSheet = array();
  
  $idate_notation_deb = "";
  $idate_notation_fin = "";
  $idate_app_deb = "";
  $idate_app_fin = "";
  $icc  = "";
  $ieval  = "";
  $i = 0;
  // print_r($_REQUEST);
  $iid_client = $_REQUEST['id_client'];
  $iid_prestation = $_REQUEST['presta'];
  $iid_type_ttmnt = $_REQUEST['t_traitement'];

  $idate_notation_deb = $_REQUEST['dt_notation1'];
  $idate_notation_fin = $_REQUEST['dt_notation2'];
  $idate_app_deb = $_REQUEST['dt_appel1'];
  $idate_app_fin = $_REQUEST['dt_appel2'];
  $iclause_matr_eval ="";
  $iclause_matr_cc = "";
  $iclause_date_notation = "";
  
  $clause_note = "";

$id_note_filtre = $_REQUEST['id_note_filtre'];
$note1 =  $_REQUEST['id_valeur_note_1'];
$note2 =  $_REQUEST['id_valeur_note_2'];

  
  if(!isset( $_SESSION['matricule']))
    exit('Session GPAO vide');
  
  $icc = $_REQUEST['cc'];
  $ieval = $_REQUEST['eval'];
  
  if($icc !='' and $icc !='0')
  {
    $iclause_matr_ccs = " and n.matricule in(".$icc.") ";
  } 
  if($ieval !='' and $ieval !='0')
  {
    $iclause_matr_evals = " and n.matricule_notation in(".$ieval.") ";
  } 
  if($idate_notation_deb !="" && $idate_notation_fin !="")
  {
    $iclause_date_notation = " and n.date_notation >= '".$idate_notation_deb."' and n.date_notation <= '".$idate_notation_fin."'  ";
  }
  if($idate_app_deb !="" && $idate_app_fin !="")
  {
    $iclause_date_app = " and n.date_entretien >= '".$idate_app_deb."' and n.date_entretien <= '".$idate_app_fin."'  ";
  }
	if( $id_note_filtre == 1 )
	{
		$clause_note =" and n.note = ".$note1;
	}

	if( $id_note_filtre == 2 )
	{
		$clause_note =" and n.note >= ".$note1." and n.note <= ".$note2;
	}

	if( $id_note_filtre == 3 )
	{
		$clause_note =" and n.note < ".$note1;
	}
	if( $id_note_filtre == 4 )
	{
		$clause_note =" and n.note <= ".$note1;
	}
	if( $id_note_filtre == 5 )
	{
		$clause_note =" and n.note > ".$note1;
	}

	if( $id_note_filtre == 6 )
	{
		$clause_note =" and n.note >= ".$note1;
	}
	
	if(isset($_REQUEST['type_appel']) && $_REQUEST['type_appel'] != 0)
	{
		// $type_appel =  trim(implode(",",$_REQUEST['type_appel'])); 
		$type_appel =  $_REQUEST['type_appel']; 
		$clause_type_app = " and n.id_typologie in(".$type_appel.") "; 
	}

  $isql_seek ="
  select distinct ga.id_projet, ga.id_client, ga.id_application, cg.id_type_traitement,n.id_notation,n.note
  from cc_sr_notation n 
    inner join cc_sr_fichier f on f.id_fichier = n.id_fichier 
    inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation 
    inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application 
    inner join cc_sr_grille g on g.id_grille = ga.id_grille 
    inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
    ".$iclause_date_notation." 
    ".$iclause_date_app." 
    where 
    ga.id_client = ".$iid_client." 
    ".$iclause_matr_ccs." 
    ".$iclause_matr_evals." 
    ".$clause_note." 
    ".$clause_type_app." 
    and cg.id_type_traitement = ".$iid_type_ttmnt." and ga.id_application = ".$iid_prestation;
    // echo '<pre>'.$isql_seek.'</pre>'; exit();
    $iquery  = pg_query($isql_seek) or die('getIDNotationAllGrille');
    while($resNot = pg_fetch_array($iquery))
	{
		$array_all[$i] = $resNot['id_notation'];
		$i++;
	}
	return $array_all;
}

function getAllGrille($conn,$id_client,$id_application,$id_projet,$id_type_traitement,$array_all)
{
	// $array_all  	= getIDNotationAllGrille($conn,$id_client,$id_application,$id_projet,$id_type_traitement);
	$iLimit = 0;
	// $limitPg = 5;
	$select    		= "";
	$from 			= "";
	$leftjoin 		= "";
	$sql 			= "";
	$sql_note  		="";

	$select 	   .= "
			select deb.id_grille,deb.id_categorie_grille,
			deb.ponderation,deb.ponderation_classement,
			deb.id_repartition,deb.id_classement,deb.section
			,deb.libelle_classement,deb.libelle_categorie_grille,deb.libelle_grille,deb.ordre, deb.cgordre,
	";
	
	$j 				= 0 ;
	/* if($limitPg <= 100)
	{
		$iLimit = 0;
	}
	else {
		$iLimit = $limitPg - 99 ;
	}
	for($iL = $iLimit; $iL < $limitPg; $iL++)
	{
		$value = $array_all[$iL]; */
	foreach ($array_all  as $value) {
	$leftjoin 	.= "
	
										 left join  (
					SELECT distinct
						
						g.id_grille,
						inot.note as note_, 
						ga.flag_is,
						ga.flag_eliminatoire,
						inot.flag_ponderation, 
						inot.commentaire, 
						inot.commentaire_si
						
						
					FROM cc_sr_grille_application ga 
						inner join cc_sr_grille g ON g.id_grille=ga.id_grille 
						inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille 
						left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application 
						inner join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement 
						inner join cc_sr_classement c on c.id_classement=cg.id_classement 
						inner join cc_sr_grille_classement gc on gc.id_classement = c.id_classement
						left join cc_sr_indicateur_notation inot on inot.id_grille_application = ga.id_grille_application 
						left join cc_sr_notation cs_not on inot.id_notation = cs_not.id_notation 
					where 
						 cs_not.id_notation  = ".$value." 
					) as note__".$value."  on note__".$value.".id_grille = deb.id_grille
				";

		if( $j == 0)
			{
				$select 	   .= "
					
					
					note__".$value.".note_ as note__".$value.",
					note__".$value.".flag_is as flag_is__".$value.",
					note__".$value.".flag_eliminatoire as flag_eliminatoire__".$value.",
					note__".$value.".flag_ponderation as flag_ponderation__".$value.",
					note__".$value.".commentaire as commentaire__".$value.",
					case when note__".$value.".flag_ponderation = 1 then 0 else deb.ponderation end as ponderation__".$value.",
					note__".$value.".commentaire_si as commentaire_si__".$value.",
					case when (note__".$value.".commentaire_si is not null and note__".$value.".commentaire_si  <> '') then 1 else 0 end as note_csi__".$value."
					";

				$from .="
					from (SELECT distinct 
						g.ordre,cg.ordre as cgordre,
						g.id_grille ,
						cg.id_categorie_grille,
						ga.ponderation,
						gc.ponderation_classement, 
						ga.id_repartition as id_repartition,
						c.id_classement,
						gc.ponderation_section,
						c.section,
						c.libelle_classement,
						cg.libelle_categorie_grille,
						g.libelle_grille
					FROM cc_sr_grille_application ga 
						inner join cc_sr_grille g ON g.id_grille=ga.id_grille 
						inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
						left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application 
						inner join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement
						inner join cc_sr_classement c on c.id_classement=cg.id_classement 
						inner join cc_sr_grille_classement gc on gc.id_classement = c.id_classement  
						left join cc_sr_indicateur_notation inot on inot.id_grille_application = ga.id_grille_application
						left join cc_sr_notation cs_not on inot.id_notation = cs_not.id_notation 
					where 
						 cs_not.id_notation = ".$value." 
					) as deb";
				
				$sql_entete ="
				select cs_not.id_notation,g_uc.nom_client,sr_projet.nom_projet prestation,cs_not.date_entretien,
					cs_not.date_notation,
					cs_not.matricule as matricule,
					cs_not.matricule_notation as matricule_notation, cs_not.numero_commande,
					cs_not.numero_dossier,sr_fichier.nom_fichier
					from cc_sr_notation cs_not
					inner join cc_sr_projet sr_projet on sr_projet.id_projet = cs_not.id_projet
					inner join gu_client  g_uc on g_uc.id_client = sr_projet.id_client
					inner join cc_sr_fichier sr_fichier on sr_fichier.id_fichier = cs_not.id_fichier
					where cs_not.id_notation  = ".$value;
					
				$sql_note = " 
					  select deb.id_grille,deb.id_categorie_grille,
	deb.ponderation_classement,
	deb.id_repartition,deb.id_classement,deb.section
	,deb.libelle_classement,deb.libelle_categorie_grille,deb.libelle_grille,deb.ordre, deb.cgordre,id_notation,deb.ponderation,
	case when commentaire_si is not null and commentaire_si<>'' then 1 else 0 end as note_csi,note,deb.flag_eliminatoire,deb.flag_ponderation,'||'::text sep,
	deb.date_entretien,deb.date_notation,
	deb.matricule,deb.matricule_notation,
	deb.numero_commande,deb.numero_dossier,deb.prestation,deb.nom_client,deb.type_appel,deb.nom_fichier
	
			from (SELECT distinct 
				g.ordre,cg.ordre as cgordre,
				g.id_grille ,
				cg.id_categorie_grille,
				ga.ponderation,
				gc.ponderation_classement, 
				ga.id_repartition as id_repartition,
				c.id_classement,
				gc.ponderation_section,
				c.section,
				c.libelle_classement,
				cg.libelle_categorie_grille,
				g.libelle_grille,
				cs_not.id_notation,
				inot.note,
				inot.commentaire_si,
				ga.flag_eliminatoire,
				inot.flag_ponderation,
				cs_not.id_projet,
				cs_not.date_entretien,
				cs_not.date_notation,
				cs_not.matricule as matricule,
				cs_not.matricule_notation as matricule_notation,
				cs_not.numero_commande,
				cs_not.numero_dossier,
				srprojet.nom_projet prestation,
				guc.nom_client,
				srtypo.libelle_typologie type_appel,
				srfichier.nom_fichier

				
			FROM cc_sr_grille_application ga 
				inner join cc_sr_grille g ON g.id_grille=ga.id_grille 
				inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille 
				
				left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application
				inner join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement 
				inner join cc_sr_classement c on c.id_classement=cg.id_classement 
				inner join cc_sr_grille_classement gc on gc.id_classement = c.id_classement 
				left join cc_sr_indicateur_notation inot on inot.id_grille_application = ga.id_grille_application 
				left join cc_sr_notation cs_not on inot.id_notation = cs_not.id_notation
				inner join cc_sr_projet srprojet on srprojet.id_projet = cs_not.id_projet

				inner join gu_client  guc on guc.id_client = srprojet.id_client
				inner join cc_sr_typologie srtypo on srtypo.id_typologie = cs_not.id_typologie
				inner join cc_sr_fichier srfichier on srfichier.id_fichier = cs_not.id_fichier
				inner join personnel on personnel.matricule = cs_not.matricule
 
			where 
			cs_not.id_notation = ".$value." 
			) as deb 
			
					 ";
			}
		else
			{
				$select 	   .= ",
					
					note__".$value.".note_ as note__".$value.",
					note__".$value.".flag_is as flag_is__".$value.",
					note__".$value.".flag_eliminatoire as flag_eliminatoire__".$value.",
					note__".$value.".flag_ponderation as flag_ponderation__".$value.",
					note__".$value.".commentaire as commentaire__".$value.",
					case when note__".$value.".flag_ponderation = 1 then 0 else deb.ponderation end as ponderation__".$value.",
					note__".$value.".commentaire_si as commentaire_si__".$value.",
					case when (note__".$value.".commentaire_si is not null and note__".$value.".commentaire_si  <> '') then 1 else 0 end as note_csi__".$value."
					";
				
				$sql_entete .="
				 union 
				select cs_not.id_notation,g_uc.nom_client,sr_projet.nom_projet prestation,cs_not.date_entretien,
					cs_not.date_notation,
					cs_not.matricule as matricule,
					cs_not.matricule_notation as matricule_notation, cs_not.numero_commande,
					cs_not.numero_dossier,sr_fichier.nom_fichier
					from cc_sr_notation cs_not
					inner join cc_sr_projet sr_projet on sr_projet.id_projet = cs_not.id_projet
					inner join gu_client  g_uc on g_uc.id_client = sr_projet.id_client
					inner join cc_sr_fichier sr_fichier on sr_fichier.id_fichier = cs_not.id_fichier
					where cs_not.id_notation  = ".$value;
					
				$sql_note .= " union  
					select deb.id_grille,deb.id_categorie_grille,
	deb.ponderation_classement,
	deb.id_repartition,deb.id_classement,deb.section
	,deb.libelle_classement,deb.libelle_categorie_grille,deb.libelle_grille,deb.ordre, deb.cgordre,id_notation,deb.ponderation,
	case when commentaire_si is not null and commentaire_si<>'' then 1 else 0 end as note_csi,note,deb.flag_eliminatoire,deb.flag_ponderation,'||'::text sep,
	deb.date_entretien,deb.date_notation,
	deb.matricule,deb.matricule_notation,
	deb.numero_commande,deb.numero_dossier,deb.prestation,deb.nom_client,deb.type_appel,deb.nom_fichier
	
			from (SELECT distinct 
				g.ordre,cg.ordre as cgordre,
				g.id_grille ,
				cg.id_categorie_grille,
				ga.ponderation,
				gc.ponderation_classement, 
				ga.id_repartition as id_repartition,
				c.id_classement,
				gc.ponderation_section,
				c.section,
				c.libelle_classement,
				cg.libelle_categorie_grille,
				g.libelle_grille,
				cs_not.id_notation,
				inot.note,
				inot.commentaire_si,
				ga.flag_eliminatoire,
				inot.flag_ponderation,
				cs_not.id_projet,
				cs_not.date_entretien,
				cs_not.date_notation,
				cs_not.matricule as matricule,
				cs_not.matricule_notation as matricule_notation,
				cs_not.numero_commande,
				cs_not.numero_dossier,
				srprojet.nom_projet prestation,
				guc.nom_client,
				srtypo.libelle_typologie type_appel,
				srfichier.nom_fichier

				
			FROM cc_sr_grille_application ga 
				inner join cc_sr_grille g ON g.id_grille=ga.id_grille 
				inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille 
				
				left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application 
				inner join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement 
				inner join cc_sr_classement c on c.id_classement=cg.id_classement 
				inner join cc_sr_grille_classement gc on gc.id_classement = c.id_classement 
				left join cc_sr_indicateur_notation inot on inot.id_grille_application = ga.id_grille_application 
				left join cc_sr_notation cs_not on inot.id_notation = cs_not.id_notation
				inner join cc_sr_projet srprojet on srprojet.id_projet = cs_not.id_projet

				inner join gu_client  guc on guc.id_client = srprojet.id_client
				inner join cc_sr_typologie srtypo on srtypo.id_typologie = cs_not.id_typologie
				inner join cc_sr_fichier srfichier on srfichier.id_fichier = cs_not.id_fichier
				inner join personnel on personnel.matricule = cs_not.matricule
 
			where 
				cs_not.id_notation = ".$value."  
			) as deb ";
			}
		$j++;

		
	}

	//$order = ' order by deb.section,deb.id_repartition,deb.id_categorie_grille,deb.id_classement,deb.cgordre,deb.ordre,deb.id_grille';
	$order 	   = ' order by deb.section asc, deb.id_grille';
	//$sql_note .= ' order by id_notation,id_classement';
	$sql    =  $select.' '.$from.' '.$leftjoin.' '.$order;
	
	 // echo '<pre>'.$sql.'</pre>';
	// echo '@@@@@@@@@@@@@@@@@@@@@.@'; 
	// return $sql.'##'.$sql_note.'##'.$sql_entete;
	return $sql;
}
/*
$query  = pg_query($conn,$sql ) or die('error : getAllIdgrille ');
$arrayRslt =  array();
$k = 0;
while($res = pg_fetch_array($query))
	{
		
		if(!in_array($res['id_grille'], $arrayRslt)){
			$arrayRslt[$i] = $res['id_grille'];
			$i++;
		}
	}*/
function getAllIdgrille($conn,$id_client,$id_application,$id_projet,$id_type_traitement){
	$sql ="
					
SELECT cg.id_categorie_grille,cg.libelle_categorie_grille,g.id_grille,g.libelle_grille,gd.note,
		 gd.libelle_description,ga.flag_is,ga.flag_eliminatoire,ga.id_repartition,c.id_classement,c.libelle_classement,c.section,
		 
		 ga.ponderation
, ga.flag_eliminatoire, gc.ponderation_classement, gc.ponderation_section, ''::character varying as commentaire, -1::integer as point, ''::character varying as commentaire_si, ga.id_grille_application
		 FROM  cc_sr_grille_application ga
left join  cc_sr_grille g ON g.id_grille=ga.id_grille
left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application
left join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement
left join cc_sr_classement c on c.id_classement=cg.id_classement 
left join cc_sr_grille_classement gc on 
(gc.id_projet = ".$id_projet."   and gc.id_client = ".$id_client."  and gc.id_application = ".$id_application."  and gc.id_classement = c.id_classement)
where cg.id_type_traitement=".$id_type_traitement ."
and ga.id_projet=".$id_projet."  
and ga.id_client=".$id_client." 
and ga.id_application=".$id_application."  
order by cg.ordre,g.ordre,c.section,c.id_classement,cg.id_categorie_grille ASC,g.id_grille ASC,gd.note DESC
	";
	// echo '<pre>'.$sql.'</pre>';
	$query  = pg_query($conn,$sql ) or die('error : getAllIdgrille ');
	return $query;
}
function getNote($conn,$sql_note,$sql_entete){
	
	// echo '<pre>'.$sql_note.'</pre>';
	// echo '<br/><br/><br/>#############"<br/><br/>';
	$arrayRslt = array();
	$arrayPonderation = array();
	$arrayNotecsi = array();
	$arrayPonderattionClsmnt = array();
	$arrayInfotete = array();
	
	$query  = pg_query($conn,$sql_note ) or die('error : getNote ');
	$queryHeader  = pg_query($conn,$sql_entete ) or die('error : sql_entete ');
	
	while($resNote = pg_fetch_array($query))
	{
		
		$note 					= $resNote['note'];
		$ponderation 			= $resNote['ponderation'];
		$note_csi 				= $resNote['note_csi'];
		$flag_ponderation 		= $resNote['flag_ponderation'];
		$ponderation_classement = $resNote['ponderation_classement'];
		
		
		
		if ($note == 1) {
			// $note = 0;
			// $ponderation = 0;
		}
		if($flag_ponderation == 1 && $note != 0) {$note = 0; $ponderation = 0;}
		
		if(isset($arrayRslt[$resNote['id_classement']][$resNote['id_notation']])){
			$arrayRslt[$resNote['id_classement']][$resNote['id_notation']] = $arrayRslt[$resNote['id_classement']][$resNote['id_notation']] + $note*$ponderation;
			$arrayPonderation[$resNote['id_classement']][$resNote['id_notation']] += $ponderation;
			$arrayNotecsi[$resNote['id_classement']][$resNote['id_notation']] += $note_csi;
			$arrayPonderattionClsmnt[$resNote['id_notation']][$resNote['id_classement']] = $ponderation_classement;
		}
		else{
			$arrayRslt[$resNote['id_classement']][$resNote['id_notation']] = $note*$ponderation;
			$arrayPonderation[$resNote['id_classement']][$resNote['id_notation']] = $ponderation;
			$arrayNotecsi[$resNote['id_classement']][$resNote['id_notation']] = $note_csi;
		}
		
	}
	while($resHeader = pg_fetch_array($queryHeader))
	{
		$arrayInfotete['matricule'][$resHeader['id_notation']] = $resHeader['matricule'];
		$arrayInfotete['matricule_notation'][$resHeader['id_notation']] = 	$resHeader['matricule_notation'];
		$arrayInfotete['numero_commande'][$resHeader['id_notation']] = $resHeader['numero_commande'];
		$arrayInfotete['date_entretien'][$resHeader['id_notation']] = $resHeader['date_entretien'];
		$arrayInfotete['date_notation'][$resHeader['id_notation']] = $resHeader['date_notation'];
		$arrayInfotete['numero_dossier'][$resHeader['id_notation']] = $resHeader['numero_dossier'];
		$arrayInfotete['prestation'][$resHeader['id_notation']] =$resHeader['prestation'];
		$arrayInfotete['nom_client'][$resHeader['id_notation']] = $resHeader['nom_client'];
		$arrayInfotete['nom_fichier'][$resHeader['id_notation']] = $resHeader['nom_fichier'];
	}
	return array($arrayRslt,$arrayPonderation,$arrayNotecsi,$arrayPonderattionClsmnt,$arrayInfotete);
}

function actualiserListeNotationExport($id_projet, $id_client, $id_application, $id_type_traitement,$idnotation)
{

	
	
	$result_com = getNotationCom($idnotation);
	list($id_tlc,$nom_fichier,$id_fichier) = explode('##',getNomFichier($idnotation));
	$id_notation = $idnotation;	 
	
	$somme_total_general = 0;
	$somme_note10 = 0;
	$str = '';
	$table_valeur = array();
	$table_ind = array();
	$tab_ind = array();
	$tab = array();
	$tab_rep = array();
	$moyenne_is4 = array();
	$moyenne_is5 = array();
	$moyenne_is6 = array();
	$moyenne_is7 = array();
	$moyenne_is5_v7 = array();
	$_repartition = array();
	$_result_rep = array();
	$_r = array();
	
	

		/* print_r($res_com);
		echo '<br/>'; */
		$test_poff = 0;
		$str = calculTotalGeneralExportGrille($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement);
		$table_valeur = explode('||',$str); // total_general || nb_eliminatoire || &id_grille_application|IS4_IS6|repartition 
		$test_poff = $table_valeur[3];
		if(($id_type_traitement == 1 || $id_type_traitement == 2) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
		{
			//$somme_note10 += ($table_valeur[0]*100) / 10;
			$somme_note10 += $table_valeur[0];
		
			//$somme_total_general += $table_valeur[0]*100;
			$somme_total_general += $table_valeur[0]*10;
			
			//$totalG[$id_notation] = $table_valeur[0]*100;
			$totalG[$id_notation] = $table_valeur[0]*10;
			
		}
		else
		{
			$somme_note10 += $table_valeur[0] / 10;
			$somme_total_general += $table_valeur[0];
			$totalG[$id_notation] = $table_valeur[0];
			
		}
		
		$valeur_indicateur = explode('&',$table_valeur[2]); // &1|IS4_IS6&2|IS4&17|IS5&12|IS4&11|IS7&13|IS6&19|IS7&25|IS4&7|IS6 
		for($i=1;$i<count($valeur_indicateur);$i++) 
		{
			$table_ind = explode('|',$valeur_indicateur[$i]);
			$tab_ind = explode(';',$table_ind[1]);
			for($j=0;$j<count($tab_ind);$j++)
			{
				$tab[$tab_ind[$j]][] = $table_ind[0];
			}
			$tab_rep[$table_ind[2]][] = $table_ind[0]; // $tab_rep[id_repartition][] = id_grille_application
		}
	
		/************** IS4 **********************/
		$result_is4 = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,'IS4');
		$ponderation_is4 = 0;
		$produit_somme_is4 = 0;
		$nandalo_is4=0;
		while($res_is4 = pg_fetch_array($result_is4))
		{
			$ponderation_is4 += $res_is4['pond'];
			$produit_somme_is4 += $res_is4['note'] * $res_is4['pond'];
			$nandalo_is4=1;
		}
		
		if(( $id_type_traitement == 1 || $id_type_traitement == 2 ) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
		{		   
			if($produit_somme_is4 == $ponderation_is4 && $nandalo_is4==1) $moyenne_is4[$id_notation] = 1;
			else $moyenne_is4[$id_notation] = 0;
		}
		else
		{
			if($ponderation_is4 != 0)
			{
				if(($produit_somme_is4 / $ponderation_is4)<100) $moyenne_is4[$id_notation] = 0;
	        	else $moyenne_is4[$id_notation] = 1;
			}
	        else $moyenne_is4[$id_notation] = 0;
	    }
	    
		/*if(($produit_somme_is4 / $ponderation_is4)<100) $moyenne_is4[$id_notation] = 0;
		else $moyenne_is4[$id_notation] = 1;*/
		
		/************** IS5 **********************/
		$result_is5 = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,'IS5');
		$ponderation_is5 = 0;
		$produit_somme_is5 = 0;
		$nandalo_is5=0;
		while($res_is5 = pg_fetch_array($result_is5))
		{
			$ponderation_is5 += $res_is5['pond'];
			$produit_somme_is5 += $res_is5['note'] * $res_is5['pond'];
			$nandalo_is5=1;
		}
		
		if(( $id_type_traitement == 1 || $id_type_traitement == 2 ) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
		{		   
		   if($produit_somme_is5 == $ponderation_is5 && $nandalo_is5==1) $moyenne_is5[$id_notation] = 1;
	       else $moyenne_is5[$id_notation] = 0;
		}
		else
		{
			if($ponderation_is5 != 0)
			{
				if(($produit_somme_is5 / $ponderation_is5)<100) $moyenne_is5[$id_notation] = 0;
	        	else $moyenne_is5[$id_notation] = 1;
			}
	        else $moyenne_is5[$id_notation] = 0;
	    }
	    
		/*if(($produit_somme_is5 / $ponderation_is5)<100) $moyenne_is5[$id_notation] = 0;
		else $moyenne_is5[$id_notation] = 1;*/
		
		/************** IS6 **********************/
		
		$result_is6 = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,'IS6');
		$ponderation_is6 = 0;
		$produit_somme_is6 = 0;
		$nandalo_is6=0;
		
		while($res_is6 = pg_fetch_array($result_is6))
		{
			$ponderation_is6 += $res_is6['pond'];
			$produit_somme_is6 += $res_is6['note'] * $res_is6['pond'];
			$nandalo_is6=1;
		}
	
	    if(( $id_type_traitement == 1 || $id_type_traitement == 2 ) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
	    {		   
		   if($produit_somme_is6 == $ponderation_is6 && $nandalo_is6==1) $moyenne_is6[$id_notation] = 1;
	       else $moyenne_is6[$id_notation] = 0;
		}
		else
		{
			if($ponderation_is6 != 0)
			{
				if(($produit_somme_is6 / $ponderation_is6)<100) $moyenne_is6[$id_notation] = 0;
	        	else $moyenne_is6[$id_notation] = 1;
			}
	        else $moyenne_is6[$id_notation] = 0;
	    }
		
		/************** IS7 **********************/
		$result_is7 = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,'IS7');
		$ponderation_is7 = 0;
		$produit_somme_is7 = 0;
		$nandalo_is7=0;
		while($res_is7 = pg_fetch_array($result_is7))
		{
			$ponderation_is7 += $res_is7['pond'];
			$produit_somme_is7 += $res_is7['note'] * $res_is7['pond'];
			$nandalo_is7=1;
		}
		
		if(( $id_type_traitement == 1 || $id_type_traitement == 2 ) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
		{		   
		   if($produit_somme_is7 == $ponderation_is7  && $nandalo_is7==1) $moyenne_is7[$id_notation] = 1;
	       else $moyenne_is7[$id_notation] = 0;
		}
		else
		{
			if($ponderation_is7 != 0)
			{
				if(($produit_somme_is7 / $ponderation_is7)<100) $moyenne_is7[$id_notation] = 0;
	        	else $moyenne_is7[$id_notation] = 1;
			}
	        else $moyenne_is7[$id_notation] = 0;
	    }
	    
		/************** IS5_V7 **********************/
		$result_is5_v7 = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,'IS5_V7');
		$ponderation_is5_v7 = 0;
		$produit_somme_is5_v7 = 0;
		$nandalo_is5_v7=0;
		while($res_is5_v7 = pg_fetch_array($result_is5_v7))
		{
			$ponderation_is5_v7 += $res_is5_v7['pond'];
			$produit_somme_is5_v7 += $res_is5_v7['note'] * $res_is5_v7['pond'];
			$nandalo_is5_v7=1;
		}
		
		if( $id_type_traitement == 1 || $id_type_traitement == 2 && ($id_client != 643 && $id_client != 642))
		{		   
			if($produit_somme_is5_v7 == $ponderation_is5_v7 && $nandalo_is5_v7==1 ) $moyenne_is5_v7[$id_notation] = 1;
			else $moyenne_is5_v7[$id_notation] = 0;
		}
		else
		{
			if($ponderation_is5_v7 != 0)
			{
				if(($produit_somme_is5_v7 / $ponderation_is5_v7)<100) $moyenne_is5_v7[$id_notation] = 0;
	        	else $moyenne_is5_v7[$id_notation] = 1;
			}
	        else $moyenne_is5_v7[$id_notation] = 0; 
	    }
		
		/*if(($produit_somme_is7 / $ponderation_is7)<100) $moyenne_is7[$id_notation] = 0;
		else $moyenne_is7[$id_notation] = 1;*/		
		/************* Repartition ***********************/
		// print_r($tab_rep);
		$result_rep = getCalculRep($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab_rep);
		foreach($result_rep as $_val)
		{
			for($a=1;$a<=count($_val);$a++)
			{
				if(isset($_r[$a])) $_r[$a] += $_val[$a];
				else $_r[$a] = $_val[$a];
			}
		}
	

	/*********************************************************************************/	
	/*********************************************************************************/	

	// echo $idnotation = 61816;
	// $res_com = getNotationCom($idnotation);	
	// $table_com = array();
	/* while ($_res = pg_fetch_array($res_com))
	{ */
		$val = $idnotation;
	/* } */
	$ttlG = $totalG[$val];
	if( $ttlG > 10) $ttlG = $ttlG/10;
	$noteR = $test_poff;
	// if( $totalG[$val] >10) {$ttlG = $totalG[$val]/10; }
	$ttlG = number_format($ttlG, 1, ',', ' ');
	$noteR = $ttlG;
	if( $test_poff == 1)
	{
		$ttlG = 0;
	}
	
	$arrayComm[$val]['totalg'] = $ttlG;
	$arrayComm[$val]['notereduite'] = $noteR;
	$arrayComm[$val]['is4'] = $moyenne_is4[$val];
	$arrayComm[$val]['is5'] = $moyenne_is5[$val];
	$arrayComm[$val]['is6'] = $moyenne_is6[$val];
	$arrayComm[$val]['is7'] = $moyenne_is7[$val];
	$arrayComm[$val]['is5_v7'] = $moyenne_is5_v7[$val];
	
	$repartition = getAllRepartition();
	$nb_rep_ = pg_num_rows($repartition);
	
	$repartition = getAllRepartition();
	$j = 0;
	while ($res_rep = pg_fetch_array($repartition))
	{
		$result_rep = getCalculRep($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$val,$id_tlc,$tab_rep);
		$libelle = $res_rep['libelle_repartition'].'####'.$result_rep[$val][$res_rep['id_repartition']];
		$arrayComm[$val]['libelle'][$j] = $libelle;
		$j++;
	}
	
	/*echo '
	</table>';*/
	return $arrayComm;
}
function getNotationCom($idnotation)
{
	global $conn;
	$sql = "select distinct c.id_notation, e.id_type_traitement, c.id_typologie
from cc_sr_grille_application a inner join cc_sr_indicateur_notation b on a.id_grille_application = b.id_grille_application 
inner join cc_sr_notation c on c.id_notation = b.id_notation 
left join cc_sr_grille d on d.id_grille = a.id_grille 
left join cc_sr_categorie_grille e on e.id_categorie_grille = d.id_categorie_grille
	where c.id_notation = ".$idnotation."
	order by c.id_notation";
	/*--and c.id_notation = ".$id_notation ;*/
	// echo $sql; exit;
	// echo '<pre>'.$sql.'</pre>';
	$query  = pg_query( $sql ) or die(pg_last_error());
    return $query;
}
function calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,$_is)
{ 
	global $conn;
	$nombre_is = 0;
	if(isset($tab[$_is])) $nombre_is = $tab[$_is];
	$is = '';
	for($i=0;$i<count($nombre_is);$i++)
	{
		$is .= isset($tab[$_is][$i]) ? $tab[$_is][$i] : 0;
		if($i != (count($nombre_is) - 1))
		{
			$is .= ',';
		}
	}
	if($is == '') $is = 0;
$sql_IS = "select a.id_notation,a.id_grille,a.note,a.flag_ponderation , b.ponderation,
case when flag_ponderation = 1 then 0 else ponderation end as pond, flag_is
from cc_sr_indicateur_notation a 
inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application 
where a.id_grille_application in (".$is.") 
and a.id_notation = ".$id_notation;
 

	//if($_is == 'IS6') echo $sql_IS;
	$query  = pg_query( $sql_IS ) or die(pg_last_error());
	return $query;
}
function getCalculRep($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab_rep)
{
	global $conn;
	$sql = "select * from cc_sr_repartition order by ordre";
	$query  = pg_query( $sql ) or die(pg_last_error());
	$table = array(); 
	// print_r($tab_rep);
	while ($res_rep = pg_fetch_array($query))
	{
		$id_repartition = $res_rep['id_repartition'];
		if(!empty($tab_rep[$id_repartition]))
		{
			$rep = '';
			for($i=0;$i<count($tab_rep[$id_repartition]);$i++)
			{
				$rep .= $tab_rep[$id_repartition][$i];
				if($i != (count($tab_rep[$id_repartition]) - 1))
				{
					$rep .= ',';
				}
			}
			$_query = repartition($id_client,$id_projet,$id_application,$id_notation,$id_fichier,$rep);
			$resultat = pg_fetch_array($_query);
			$table[$id_notation][$id_repartition] = $resultat['nb_csi'];
		}
		//if(empty($table[$id_notation][$id_repartition]))
		else
		{
			$table[$id_notation][$id_repartition] = 0;
		}
	}
	// print_r($table);
	return $table;
}
function getAllRepartition()
{
	global $conn;
	$sql = "select * from cc_sr_repartition order by ordre";
	$query  = pg_query( $sql ) or die(pg_last_error());
	return $query;
}
function repartition($id_client,$id_projet,$id_application,$id_notation,$id_fichier,$rep)
{
	global $conn;
	$sql = "select count(a.commentaire_si) as nb_csi from cc_sr_indicateur_notation a 
	inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application 
	inner join cc_sr_notation c on c.id_notation = a.id_notation
	where b.id_client = ".$id_client." and b.id_projet = ".$id_projet." and b.id_application = ".$id_application." 
	and a.id_notation = ".$id_notation." and c.id_fichier = ".$id_fichier." and b.id_grille_application in (".$rep.") 
	and a.commentaire_si != ''";
	// echo '<pre>'.$sql.'</pre>';
	$query  = pg_query( $sql ) or die(pg_last_error());
	return $query;
}
function getNomFichier($idnotation)
{
	$sql ="
	SELECT matricule,
	srfichier.nom_fichier,srfichier.id_fichier
	FROM cc_sr_notation srnot 
	inner join cc_sr_fichier srfichier on srfichier.id_fichier = srnot.id_fichier
	where srnot.id_notation =".$idnotation."
	";
	$query  = pg_query( $sql ) or die('getNomFichier');
	$res	= pg_fetch_array( $query, 0 );
	return $value  = $res['matricule'].'##'.$res['nom_fichier'].'##'.$res['id_fichier'];
}
function date_fr($date)
{
	list($annee,$mois,$jour)=explode("-",$date);
	return $jour.'/'.$mois.'/'.$annee;
}

function infoPers($matr)
	{
		global $conn;
		// $matr = 9420;
		$sql = "
			select matricule || ' - '|| prenompersonnel || ' (' || fonctioncourante || ')' as infopers from personnel where matricule = ".$matr;
		$query  = pg_query($sql) or die('InfoPers');
		// echo '<pre>'.$sql.'</pre>'
		$res	= pg_fetch_array( $query, 0 );
		return $res["infopers"];
	}
	
function getAllSheet()
{
global $conn;
$arrayRsltSheet = array();
$iSheet = 0;
$date_notation_deb = "";
$date_notation_fin = "";
$date_app_deb = "";
$date_app_fin = "";
$cc  = "";
$eval  = "";
// print_r($_REQUEST);
$id_client = $_REQUEST['id_client'];
$id_prestation = $_REQUEST['presta'];
$id_type_ttmnt = $_REQUEST['t_traitement'];

$date_notation_deb = $_REQUEST['dt_notation1'];
$date_notation_fin = $_REQUEST['dt_notation2'];
$date_app_deb = $_REQUEST['dt_appel1'];
$date_app_fin = $_REQUEST['dt_appel2'];
$clause_matr_eval ="";
$clause_matr_cc = "";
$clause_date_notation = "";

$clause_noteA = "";

$id_note_filtre1 = $_REQUEST['id_note_filtre'];
$note11 =  $_REQUEST['id_valeur_note_1'];
$note22 =  $_REQUEST['id_valeur_note_2'];

// #########
if(!isset($_SESSION['matricule']))
	exit('Session GPAO vide');
// echo $cc =  trim(implode(",",$_REQUEST['cc']));
// $eval =  trim(implode(",",$_REQUEST['eval']));
// print_r($_REQUEST['cc']);
$cc = $_REQUEST['cc'];
$eval = $_REQUEST['eval'];
// echo $_REQUEST['type_appel'];
// print_r($_REQUEST);

if(isset($_REQUEST['type_appel']) && $_REQUEST['type_appel'] != 0)
{
	// $type_appelS =  trim(implode(",",$_REQUEST['type_appel'])); 
	$type_appelS =  $_REQUEST['type_appel']; 
	$clause_type_appS = " and n.id_typologie in(".$type_appelS.") ";
}

if($cc !='' and $cc !='0')
{
	$clause_matr_ccs = " and n.matricule in(".$cc.") ";
} 
if($eval !='' and $eval !='0')
{
	$clause_matr_evals = " and n.matricule_notation in(".$eval.") ";
} 
if($date_notation_deb !="" && $date_notation_fin !="")
{
	$clause_date_notation = " and n.date_notation >= '".$date_notation_deb."' and n.date_notation <= '".$date_notation_fin."'  ";
}
if($date_app_deb !="" && $date_app_fin !="")
{
	$clause_date_app = " and n.date_entretien >= '".$date_app_deb."' and n.date_entretien <= '".$date_app_fin."'  ";
}
if( $id_note_filtre1 == 1 )
{
	$clause_noteA =" and n.note = ".$note11;
}

if( $id_note_filtre1 == 2 )
{
	$clause_noteA =" and n.note >= ".$note11." and n.note <= ".$note22;
}

if( $id_note_filtre1 == 3 )
{
	$clause_noteA =" and n.note < ".$note11;
}
if( $id_note_filtre1 == 4 )
{
	$clause_noteA =" and n.note <= ".$note11;
}
if( $id_note_filtre1 == 5 )
{
	$clause_noteA =" and n.note > ".$note11;
}

if( $id_note_filtre1 == 6 )
{
	$clause_noteA =" and n.note >= ".$note11;
}

$sql_seek ="
select distinct ga.id_projet, ga.id_client, ga.id_application, cg.id_type_traitement
from cc_sr_notation n 
	inner join cc_sr_fichier f on f.id_fichier = n.id_fichier 
	inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation 
	inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application 
	inner join cc_sr_grille g on g.id_grille = ga.id_grille 
	inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
	".$clause_date_notation." 
	".$clause_date_app." 
	where 
	ga.id_client = ".$id_client." 
	".$clause_matr_ccs." 
	".$clause_matr_evals." 
	".$clause_noteA." 
	".$clause_type_appS." 
	and cg.id_type_traitement = ".$id_type_ttmnt." and ga.id_application = ".$id_prestation;
	// echo '<pre>'.$sql_seek.'</pre>'; exit();
	$query  = pg_query($sql_seek) or die('getAllSheet');
	return $query;	
}

  function getNotenHeader($conn,$id_client,$id_application,$id_projet,$id_type_traitement,$array_all)
  {
      $sql_note     ="";
      $sql_entete   = "";
      $j        = 0 ;

      foreach ($array_all as $value) 
      {
        if( $j == 0)
          {
            $sql_entete ="
            select cs_not.id_notation,g_uc.nom_client,sr_projet.nom_projet prestation,cs_not.date_entretien,
              cs_not.date_notation,
              cs_not.matricule as matricule,
              cs_not.matricule_notation as matricule_notation, cs_not.numero_commande,
              cs_not.numero_dossier,sr_fichier.nom_fichier
              from cc_sr_notation cs_not
              inner join cc_sr_projet sr_projet on sr_projet.id_projet = cs_not.id_projet
              inner join gu_client  g_uc on g_uc.id_client = sr_projet.id_client
              inner join cc_sr_fichier sr_fichier on sr_fichier.id_fichier = cs_not.id_fichier
              where cs_not.id_notation  = ".$value;
              
            $sql_note = " 
                select deb.id_grille,deb.id_categorie_grille,
      deb.ponderation_classement,
      deb.id_repartition,deb.id_classement,deb.section
      ,deb.libelle_classement,deb.libelle_categorie_grille,deb.libelle_grille,deb.ordre, deb.cgordre,id_notation,deb.ponderation,
      case when commentaire_si is not null and commentaire_si<>'' then 1 else 0 end as note_csi,note,deb.flag_eliminatoire,deb.flag_ponderation,'||'::text sep,
      deb.date_entretien,deb.date_notation,
      deb.matricule,deb.matricule_notation,
      deb.numero_commande,deb.numero_dossier,deb.prestation,deb.nom_client,deb.type_appel,deb.nom_fichier
      
          from (SELECT distinct 
            g.ordre,cg.ordre as cgordre,
            g.id_grille ,
            cg.id_categorie_grille,
            ga.ponderation,
            gc.ponderation_classement, 
            ga.id_repartition as id_repartition,
            c.id_classement,
            gc.ponderation_section,
            c.section,
            c.libelle_classement,
            cg.libelle_categorie_grille,
            g.libelle_grille,
            cs_not.id_notation,
            inot.note,
            inot.commentaire_si,
            ga.flag_eliminatoire,
            inot.flag_ponderation,
            cs_not.id_projet,
            cs_not.date_entretien,
            cs_not.date_notation,
            cs_not.matricule as matricule,
            cs_not.matricule_notation as matricule_notation,
            cs_not.numero_commande,
            cs_not.numero_dossier,
            srprojet.nom_projet prestation,
            guc.nom_client,
            srtypo.libelle_typologie type_appel,
            srfichier.nom_fichier

            
          FROM cc_sr_grille_application ga 
            inner join cc_sr_grille g ON g.id_grille=ga.id_grille 
            inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille 
            
            left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application 
            inner join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement 
            inner join cc_sr_classement c on c.id_classement=cg.id_classement 
            inner join cc_sr_grille_classement gc on gc.id_classement = c.id_classement
            left join cc_sr_indicateur_notation inot on inot.id_grille_application = ga.id_grille_application 
            left join cc_sr_notation cs_not on inot.id_notation = cs_not.id_notation
            inner join cc_sr_projet srprojet on srprojet.id_projet = cs_not.id_projet

            inner join gu_client  guc on guc.id_client = srprojet.id_client
            inner join cc_sr_typologie srtypo on srtypo.id_typologie = cs_not.id_typologie
            inner join cc_sr_fichier srfichier on srfichier.id_fichier = cs_not.id_fichier
            inner join personnel on personnel.matricule = cs_not.matricule
     
          where 
             cs_not.id_notation = ".$value." 
          ) as deb 
          
               ";
          }
        else
          {
            
            
            $sql_entete .="
             union 
            select cs_not.id_notation,g_uc.nom_client,sr_projet.nom_projet prestation,cs_not.date_entretien,
              cs_not.date_notation,
              cs_not.matricule as matricule,
              cs_not.matricule_notation as matricule_notation, cs_not.numero_commande,
              cs_not.numero_dossier,sr_fichier.nom_fichier
              from cc_sr_notation cs_not
              inner join cc_sr_projet sr_projet on sr_projet.id_projet = cs_not.id_projet
              inner join gu_client  g_uc on g_uc.id_client = sr_projet.id_client
              inner join cc_sr_fichier sr_fichier on sr_fichier.id_fichier = cs_not.id_fichier
              where cs_not.id_notation  = ".$value;
              
            $sql_note .= " union  
              select deb.id_grille,deb.id_categorie_grille,
      deb.ponderation_classement,
      deb.id_repartition,deb.id_classement,deb.section
      ,deb.libelle_classement,deb.libelle_categorie_grille,deb.libelle_grille,deb.ordre, deb.cgordre,id_notation,deb.ponderation,
      case when commentaire_si is not null and commentaire_si<>'' then 1 else 0 end as note_csi,note,deb.flag_eliminatoire,deb.flag_ponderation,'||'::text sep,
      deb.date_entretien,deb.date_notation,
      deb.matricule,deb.matricule_notation,
      deb.numero_commande,deb.numero_dossier,deb.prestation,deb.nom_client,deb.type_appel,deb.nom_fichier
      
          from (SELECT distinct 
            g.ordre,cg.ordre as cgordre,
            g.id_grille ,
            cg.id_categorie_grille,
            ga.ponderation,
            gc.ponderation_classement, 
            ga.id_repartition as id_repartition,
            c.id_classement,
            gc.ponderation_section,
            c.section,
            c.libelle_classement,
            cg.libelle_categorie_grille,
            g.libelle_grille,
            cs_not.id_notation,
            inot.note,
            inot.commentaire_si,
            ga.flag_eliminatoire,
            inot.flag_ponderation,
            cs_not.id_projet,
            cs_not.date_entretien,
            cs_not.date_notation,
            cs_not.matricule as matricule,
            cs_not.matricule_notation as matricule_notation,
            cs_not.numero_commande,
            cs_not.numero_dossier,
            srprojet.nom_projet prestation,
            guc.nom_client,
            srtypo.libelle_typologie type_appel,
            srfichier.nom_fichier

            
          FROM cc_sr_grille_application ga 
            inner join cc_sr_grille g ON g.id_grille=ga.id_grille 
            inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille 
            
            left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application  
            inner join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement 
            inner join cc_sr_classement c on c.id_classement=cg.id_classement 
            inner join cc_sr_grille_classement gc on gc.id_classement = c.id_classement 
            left join cc_sr_indicateur_notation inot on inot.id_grille_application = ga.id_grille_application
            left join cc_sr_notation cs_not on inot.id_notation = cs_not.id_notation
            inner join cc_sr_projet srprojet on srprojet.id_projet = cs_not.id_projet

            inner join gu_client  guc on guc.id_client = srprojet.id_client
            inner join cc_sr_typologie srtypo on srtypo.id_typologie = cs_not.id_typologie
            inner join cc_sr_fichier srfichier on srfichier.id_fichier = cs_not.id_fichier
            inner join personnel on personnel.matricule = cs_not.matricule
     
          where 
             cs_not.id_notation = ".$value."  
          ) as deb ";
          }
        $j++;

        
      }

      return $sql_note.'##'.$sql_entete;
}

function getLimit($nbIdnotification)
{
  $arrayLimit = array($nbIdnotification);
  if( $nbIdnotification > 100)
    
    {
      $j = 0;
      $valueI = $nbIdnotification/100;
      $indiceI = intval($valueI);
      $valueTestIndice = $nbIdnotification - $indiceI*100;
      for($i = $indiceI; $i > 0; $i--)
      {
        $arrayLimit[$j] = $i*100;
        $j +=1;
      }
      if( $valueTestIndice > 0)
      {
        
        $indiceI =+1;
        $j +=1;
        $arrayLimit[$j] = $nbIdnotification;
        
      }
      asort($arrayLimit);
    }
    return $arrayLimit;
}
function getInfoPresta($iDnotation)  
{
	$sql_presta = " 
			select distinct ga.id_projet, ga.id_client, ga.id_application, cg.id_type_traitement
			from cc_sr_notation n 
			inner join cc_sr_fichier f on f.id_fichier = n.id_fichier 
			inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation 
			inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application 
			inner join cc_sr_grille g on g.id_grille = ga.id_grille 
			inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
			where n.id_notation =".$iDnotation; 
		$query_presta  = pg_query($sql_presta) or die('getInfoPresta');
		$res_presta	= pg_fetch_array( $query_presta, 0 );
		return $res_presta;
}

function getAppelType($idnotation){
  $sql ="
      select distinct c.id_typologie,
case when libelle_typologie is null or libelle_typologie ='' then '' else libelle_typologie end as libelle_typologie
from cc_sr_grille_application a inner join cc_sr_indicateur_notation b on a.id_grille_application = b.id_grille_application 
inner join cc_sr_notation c on c.id_notation = b.id_notation 
left join cc_sr_grille d on d.id_grille = a.id_grille 
left join cc_sr_categorie_grille e on e.id_categorie_grille = d.id_categorie_grille
inner join cc_sr_typologie typo on typo.id_typologie = c.id_typologie
  where c.id_notation =".$idnotation;
$query  = pg_query($sql) or die('getAppelType');
$res  = pg_fetch_array( $query, 0 );
  return $res["libelle_typologie"];
}
function calculTotalGeneralExportGrille($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement)
{ 
	$result_select = fetchAll($id_projet,$id_client,$id_application,$id_notation,$id_type_traitement,$id_tlc=0,$id_fichier=0);
	$tableauBord = array();
	$Nb = pg_num_rows(  $result_select );
	$idKTgory = 0;
	$penalite_projet = get_penalite_projet( $id_projet , $id_type_traitement);
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
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_ponderation'] = array(); // Njiva
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['eliminatoire'] = $row['flag_eliminatoire'];	
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['id_grille_application'] = $row['id_grille_application'];
        $tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_is'] = $row['flag_is'];		
        $tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['id_repartition'] = $row['id_repartition'];		
	 }
	
	 if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'])) {
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'][$row['note']] = $row['libelle_description'];			
	 }
	
	 if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'])) {
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'][$row['libelle_description']] = $row['ponderation'];			
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_ponderation'][$row['libelle_description']] = $row['flag_ponderation'];	// Njiva		
	 }
		
   }

$counter1 = 0;
$nbeEliminatoire = 0; 
$test = 0; // Njiva
$str_indicateur = '';
$_sum_total_base = 0;
$_sum_total_produit = 0;
foreach($tableauBord as $sectionkey=>$section){
	$rowspanSection = 0;
	foreach($section as $key=>$val){
		$rowspanSection++;
		foreach($val['ktgory'] as $key1=>$tab){
			foreach($tab['item'] as $item) {
				$rowspanSection += count($item['note']);
			}
		}
		$rowspanSection++;
	}
	
	$rowspanSection +=2; 
	//$rowspanSection +=2; // Pour les titres
	
	/************** Njiva ***************/
	$total_section = 0;
	$sum_ponderation_classement = 0;
	/************************************/
        foreach($section as $key_section=>$val){
		
		/***** Njiva **************/
		$sum_ponderation = 0;
		$total_classement = 0;
		$_total_base = 0;
		/************************/
		$count_si = 0;
		foreach($val['ktgory'] as $key=>$tab){
			$rowSpanKt = 0;
		
			foreach($tab['item'] as $item) {
			$rowSpanKt += count($item['note']);
			}
			$nb_total_ligne = $rowSpanKt;      
			$nb_test = 1;   
			$counter2 = 0;
			$a = 1;
			//$count_si = 0;
			foreach( $tab['item'] as $id_grille=>$item  ){
 
				$commentaire = isset($item['commentaire']) ? $item['commentaire'] : ''; // Njiva
				$point = isset($item['point']) ? $item['point'] : -1; // Njiva
				$commentaire_si = isset($item['commentaire_si']) ? $item['commentaire_si'] : ''; // Njiva
				$counter3=0;
				$nb_note = count($item['note']);
				$nb_n = 1;
				$_indicateur = $item['flag_is'];
				$_id_grille_application = $item['id_grille_application'];
				$_id_repartition = $item['id_repartition'];
				$str_indicateur .= '&'.$_id_grille_application.'|'.$_indicateur.'|'.$_id_repartition;
				
				foreach ($item['note'] as $note_=>$description){

					if( $counter3==0){

						if($item['flag_ponderation'][$description] == 1)
						{
							$item['ponderation'][$description] = 0;
						}
						/*************** Njiva **************/
						$ponderation = $item['ponderation'][$description];
						$sum_ponderation += $ponderation;
						/************************************/
						$_total_base += $ponderation;
						$produit_base_note = $point * $item['ponderation'][$description];
						     if( $produit_base_note < 0  ){
							    $produit_base_note =0;
							 }
	
					    if($item['eliminatoire'] == 1)
						{
							if(isset($nbReelEliminatoire)) $nbReelEliminatoire ++;
							else $nbReelEliminatoire = 1;
							if($point == 0 || $point == -1)
							{
								$test = 1;
							}
						}
					    if ($commentaire_si != '') { // Njiva
						    $count_si = $count_si+1;
							$nbeEliminatoire++;
							$test_comment = 1;
						} else {
							$test_comment = 0;
						}
					    
					    $total_classement += $point * $ponderation; // Njiva
						
					}
				
					$counter3 ++;
					$nb_n ++;
				}
				$counter2 ++;
				$a++;
			}
			$counter1++;
		}
		/*********** Njiva *********************/
		//if($key_section == 17) echo 'test'.$sum_ponderation;
		if($sum_ponderation == 0)
        {
        	if($id_client == 643 || $id_client == 642 )
        	{
				$total_classement = 100;
			}
			else
			{
				$total_classement = 1;
			}
			$sum_ponderation = 1;
		}
		else
		{
			$total_classement = number_format($total_classement/$sum_ponderation,2);
		}
		//$total_classement = number_format($total_classement/$sum_ponderation,2);
		$total_classement= get_nombre_si($count_si,$key_section,$penalite_projet,$total_classement);
		if($total_classement <= -1)
		{
			$total_classement = 0;
		}
		/*******************************************/
		
		/* Ajouté le 31/07/2014 */
	if(($id_type_traitement == 1 || $id_type_traitement == 2) && ($id_client != 642 && $id_client != 643)) //client différent de DELAMAISON
		{
			$total_classement = $total_classement * 10;
		}
		/* ************* */
		/******************** Classement ***********************/ // Njiva
		$valeur_ponderation_classement = $_total_base;
		$produit_base_moyenneClassement = $total_classement * $val['ponderation_classement']; //'. $val['libelle'].'
		//$produit_base_moyenneClassement = $total_classement * $valeur_ponderation_classement;
		
		$ponderation_section = $val['ponderation_section']; // Njiva
		/*******************************************************/
		
		$total_section += $total_classement * $val['ponderation_classement'];
		//$total_section += $total_classement * $valeur_ponderation_classement;
		$sum_ponderation_classement += $val['ponderation_classement'];
		//$sum_ponderation_classement += $valeur_ponderation_classement;
		
		$_sum_total_base += $val['ponderation_classement'];
		//$_sum_total_base += $valeur_ponderation_classement;
		$_sum_total_produit += $produit_base_moyenneClassement;
	}
	/******************** Section (FOND / FORME) ***********************/
	if($sum_ponderation_classement == 0)
	{
		$totalS = 0;
	}
	else
	{
		$totalS = number_format($total_section / $sum_ponderation_classement,2);
		if($totalS == -1)
		{
			$totalS = 0;
		}
		elseif (is_nan($totalS))
		{
			$totalS = 0;
		}
	}

	/***********SECTION************ point / base / note * Coeff *************************/
	$valeur_ponderation_section = $ponderation_section;
	if($ponderation_section == '' || $ponderation_section == 0)
	//if($ponderation_section == '')
	{
		$valeur_ponderation_section = 0;
	}
	$produit_section = $totalS * $ponderation_section;
	//echo $totalS.'*'.$ponderation_section.'<br>';
					 
	/******************************************************************************/
	if(isset($sum_general)) $sum_general += $totalS * $ponderation_section;
	else $sum_general = $totalS * $ponderation_section;
	if(isset($sum_ponderation_section)) $sum_ponderation_section += $ponderation_section;
	else $sum_ponderation_section = $ponderation_section;
}

//$totalG = number_format($sum_general / $sum_ponderation_section,2);
$totalG = $_sum_total_produit / $_sum_total_base;
if (is_nan($totalG))
{
	$totalG = '0.00';
}
if($test == 0)
{
	$total_general = number_format($sum_general / $sum_ponderation_section,4);
	
    //$total_general = number_format($_sum_total_produit / $_sum_total_base,2);
	if (is_nan($total_general))
	{
		$total_general = '0.00';
	}
}
if($totalG > 10) $totalG = $totalG/10;
return $totalG.'||'.$nbeEliminatoire.'||'.$str_indicateur.'||'.$test;
}

 
?> 