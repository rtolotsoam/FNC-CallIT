<?php

    require_once 'PHPExcel/IOFactory.php';
	include_once 'PHPExcel/Writer/Excel5.php';
	include_once 'PHPExcel/Writer/Excel2007.php';
	require_once 'PHPExcel.php';
	
	include("/var/www.cache/dgconn.inc");
	
	$array_month   = array ("01"=>"JANVIER","02"=>"FEVRIER","03"=>"MARS","04"=>"AVRIL","05"=>"MAI","06"=>"JUIN","07"=>"JUILLET","08"=>"AOUT","09"=>"SEPTEMBRE","10"=>"OCTOBRE","11"=>"NOVEMBRE","12"=>"DECEMBRE");
	$fichier       = 'recapitulatif_mensuel.xls';
	$inputFileName = 'reporting/'.$fichier;
	$dossier       = 'reporting/';
	
	if (!file_exists($inputFileName)) {
		exit("Please run 14excel5.php first.\n");
    }
	
	$date_deb_notation = $_REQUEST['date_deb'];
	list($annee_deb,$mois_deb,$jour_deb) = explode('-',$date_deb_notation);
    $date_fin_notation = $_REQUEST['date_fin'];
	list($annee_fin,$mois_fin,$jour_fin) = explode('-',$date_fin_notation);
	
	$listeColExcel = array("1" => "A","2" => "B" ,"3" => "C" ,"4" => "D" ,"5" => "E" ,"6" => "F" ,"7" => "G" ,"8" => "H" ,"9" => "I" ,"10" => "J" ,"11" => "K" ,"12" => "L" ,"13" => "M" ,"14" => "N" ,"15" => "O" ,"16" => "P" ,"17" => "Q" ,"18" => "R" ,"19" => "S" ,"20" => "T" ,"21" => "U" ,"22" => "V" ,"23" => "W" ,"24" => "X" ,"25" => "Y" ,"26" => "Z" ,"27" => "AA" ,"28" => "AB" ,"29" => "AC" ,"30" => "AD" ,"31" => "AE" ,"32" => "AF" ,"33" => "AG" ,"34" => "AH" ,"35" => "AI" ,"36" => "AJ" ,"37" => "AK" ,"38" => "AL" ,"39" => "AM" ,"40" => "AN" ,"41" => "AO" ,"42" => "AP" ,"43" => "AQ" ,"44" => "AR","45" => "AS","46" => "AT","47" => "AU","48" => "AV","49" => "AW","50" => "AX","51" => "AY","52" => "AZ","53" => "BA","54" => "BB","55" => "BC","56" => "BD","57" => "BE","58" => "BF","59" => "BG","60" => "BH","61" => "BI","62" => "BJ","63" => "BK","64" => "BL","65" => "BM","66" => "BN","67" => "BO","68" => "BP","69" => "BQ","70" => "BR","71" => "BS","72" => "BT","73" => "BU","74" => "BV","75" => "BW","76" => "BX","77" => "BY","78" => "BZ","79" => "CA","80" => "CB","81" => "CC","82" => "CD","83" => "CE","84" => "CF","85" => "CG","86" => "CH","87" => "CI","88" => "CJ","89" => "CK","90" => "CL","91" => "CM","92" => "CN","93" => "CO","94" => "CP","95" => "CQ","96" => "CR","97" => "CS","98" => "CT","99" => "CU","100" => "CV","101" => "CW","102" => "CX","103" => "CY","104" => "CZ","105" => "DA","106" => "DB","107" => "DC","108" => "DD","109" => "DE","110" => "DF","111" => "DG","112" => "DH","113" => "DI");
    include('export_style.php');
	
	$objet = PHPExcel_IOFactory::createReader('Excel5');
	$objPHPExcel = $objet->load($inputFileName);
	$objPHPExcel->getSheet(0);
	$objPHPExcel->setActiveSheetIndex(0);
	$objWorksheet1 = $objPHPExcel->getActiveSheet();
	$titleSheet = "Détail Evaluation";
	$objWorksheet1->setTitle($titleSheet);
	$objWorksheet1->getSheetView()->setZoomScale(80);
	$objWorksheet1->getRowDimension(3)->setRowHeight(30);
	$objWorksheet1->getRowDimension(1)->setRowHeight(20);
	
	$sql = "
		 select prenompersonnel,
				matricule_notation,
				count(id_notation) nombre,
				code,
				nom_client ,
				date_notation, 
				id_type_traitement
		from (
			select distinct n.id_notation,
				n.matricule_notation,
				p.prenompersonnel,
				ga.id_projet,
				ga.id_client,
				ga.id_application,
				gua.code,
				guc.nom_client
				,date_notation
				,tt.id_type_traitement 
			from cc_sr_notation n
				inner join personnel p on p.matricule = n.matricule_notation
				inner join cc_sr_indicateur_notation inot on n.id_notation = inot.id_notation
				inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application
				inner join cc_sr_grille g on g.id_grille = ga.id_grille
				inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
				inner join cc_sr_type_traitement tt on cg.id_type_traitement = tt.id_type_traitement
				inner join gu_client guc on guc.id_client = ga.id_client
				inner join gu_application gua on gua.id_application = ga.id_application
			where date_notation between '".$date_deb_notation."' and '".$date_fin_notation."'
			order by matricule_notation 
		) as req1
		group by prenompersonnel,matricule_notation,code,nom_client, date_notation, id_type_traitement
		order by prenompersonnel,code , date_notation ";
	
	$query  = pg_query($conn,$sql) or die(pg_last_error($conn));
	
	$col_nom             = 2;
	$col_matricule       = 1;
	$col_nbEval          = 3;
	$col_prestation      = 4;
	$col_client          = 5;
	$col_type_traitement = 6;
	$col_date_eval       = 7;
	$ligne               = 4;
	
	$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
	$objWorksheet1->setCellValue("A1",utf8_encode('EVALUATION DU '.$jour_deb.' '.$array_month[$mois_deb].' '.$annee_deb.' AU '.$jour_fin.' '.$array_month[$mois_deb].' '.$annee_fin));
	$objWorksheet1->getStyle("A1")->applyFromArray($style_ligne_date);
	
	while($res = pg_fetch_array($query)){
		$objWorksheet1->getStyle($listeColExcel[$col_matricule].$ligne)->applyFromArray($left_align);
		$objWorksheet1->getStyle($listeColExcel[$col_nbEval].$ligne)->applyFromArray($left_align);
		
		$objWorksheet1->setCellValue($listeColExcel[$col_nom].$ligne,utf8_encode($res['prenompersonnel']));
		$objWorksheet1->setCellValue($listeColExcel[$col_matricule].$ligne,utf8_encode($res['matricule_notation']));
		$objWorksheet1->setCellValue($listeColExcel[$col_nbEval].$ligne,utf8_encode($res['nombre']));
		$objWorksheet1->setCellValue($listeColExcel[$col_prestation].$ligne,utf8_encode($res['code']));
		$objWorksheet1->setCellValue($listeColExcel[$col_client].$ligne,utf8_encode($res['nom_client']));
		
		$objWorksheet1->setCellValue($listeColExcel[$col_type_traitement].$ligne,utf8_encode($traitement_abrev[$res['id_type_traitement']]));
		
		$date_eval = date_create($res['date_notation']);
		$date_eval = date_format($date_eval,'d/m/Y');
		$objWorksheet1->setCellValue($listeColExcel[$col_date_eval].$ligne,utf8_encode($date_eval));
		
		$objWorksheet1->getStyle($listeColExcel[$col_nom].$ligne)->applyFromArray($style_nom);
		$objWorksheet1->getStyle($listeColExcel[$col_matricule].$ligne)->applyFromArray($style_matricule);
		$objWorksheet1->getStyle($listeColExcel[$col_nbEval].$ligne)->applyFromArray($style_nbEval);
		$objWorksheet1->getStyle($listeColExcel[$col_prestation].$ligne)->applyFromArray($style_prestation);
		$objWorksheet1->getStyle($listeColExcel[$col_client].$ligne)->applyFromArray($style_client);
		$objWorksheet1->getStyle($listeColExcel[$col_type_traitement].$ligne)->applyFromArray($style_tt);
		$objWorksheet1->getStyle($listeColExcel[$col_date_eval].$ligne)->applyFromArray($right_align);
		$objWorksheet1->getStyle($listeColExcel[$col_date_eval].$ligne)->applyFromArray($style_date_eval);

		$ligne++;
	}
	
	$objWorksheet1->getStyle($listeColExcel[2].($ligne+1))->applyFromArray($style_total);
	$objWorksheet1->getStyle('A'.($ligne+1).':G'.($ligne+1))->applyFromArray($style_ligne_total);
	$objWorksheet1->setCellValue($listeColExcel[2].($ligne+1),'Total évaluation');
	$objWorksheet1->setCellValue($listeColExcel[3].($ligne+1),'=SUM(C4:C'.$ligne.')');
	$file = "recapitulatif_".str_replace("-","_",$date_deb_notation)."__".str_replace("-","_",$date_fin_notation);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$file.'.xls"');
	$objWriter->save('reporting/'.$file);
	readfile('reporting/'.$file);
	exit; 
	
?>