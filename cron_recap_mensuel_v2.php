<?php
	
	/**
	
		$month = '2011-01-20';
		$timestamp = strtotime ("-1 month",strtotime ($month));
		$nextMonth  =  date("Y-m-d",$timestamp);
	
	*/
	
	require_once 'PHPExcel/IOFactory.php';
	include_once 'PHPExcel/Writer/Excel5.php';
	include_once 'PHPExcel/Writer/Excel2007.php';
	require_once 'PHPExcel.php';
  
	
	$array_month = array ("01"=>"JANVIER","02"=>"FEVRIER","03"=>"MARS","04"=>"AVRIL",
	"05"=>"MAI","06"=>"JUIN","07"=>"JUILLET","08"=>"AOUT","09"=>"SEPTEMBRE"
	,"10"=>"OCTOBRE","11"=>"NOVEMBRE","12"=>"DECEMBRE");
	$fichier = 'recapitulatif_mensuel.xls';
	$inputFileName = 'reporting/'.$fichier;
	$dossier = 'reporting/';
	
	if (!file_exists($inputFileName)) 
	{
	  exit("Please run 14excel5.php first.\n");
    }
	
	require 'lib_mail/class.phpmailer.php';
    include("/var/www.cache/dgconn.inc");
    $array_month = array ("01"=>"JANVIER","02"=>"FEVRIER","03"=>"MARS","04"=>"AVRIL",
	"05"=>"MAIS","06"=>"JUIN","07"=>"JUILLET","08"=>"AOUT","09"=>"SEPTEMBRE"
	,"10"=>"OCTOBRE","11"=>"NOVEMBRE","12"=>"DECEMBRE");
	$current_date = date('Y-m-d');

	list($current_year,$current_month,$current_day) = explode("-",$current_date);
	if( $current_day == '04' )
	{
   // $current_date = "2015-02-01";
	

	$date_deb = date('Y-m-d', strtotime(date('Y-m')." -1 month"));
	$date_fin = finDuMois( $date_deb );  
	list($previous_year,$previous_month,$previous_day) = explode("-",$date_fin);
	
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
	
	/**********************Contenue de l'Excel******************************/
	
	$sql="select prenompersonnel,matricule_notation,count(id_notation) nombre,code,nom_client from (
	select distinct n.id_notation,n.matricule_notation, p.prenompersonnel,ga.id_projet,ga.id_client,ga.id_application,gua.code,guc.nom_client
	from cc_sr_notation n
	inner join personnel p on p.matricule = n.matricule_notation
	inner join cc_sr_indicateur_notation inot on n.id_notation = inot.id_notation
	inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application
	inner join gu_client guc on guc.id_client = ga.id_client
	inner join gu_application gua on gua.id_application = ga.id_application
	where date_entretien >= '".$date_deb."' and date_entretien <= '".$date_fin."'
	order by matricule_notation ) as req1
	group by prenompersonnel,matricule_notation,code,nom_client
	order by prenompersonnel,code ASC";
	$query  = pg_query($conn,$sql) or die(pg_last_error($conn));
	$col_nom = 2;
	$col_matricule = 1;
	$col_nbEval = 3;
	$col_prestation = 4;
	$col_client = 5;
	$ligne = 4;
	$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
	$objWorksheet1->setCellValue("A1",utf8_encode('EVALUATION '.$array_month[$previous_month].' '.$previous_year));
	$objWorksheet1->getStyle("A1")->applyFromArray($style_ligne_date);
	$nom_precedent = '';
	$matricule_precedent = '';
	while($res = pg_fetch_array($query))
	{
	  $objWorksheet1->getStyle($listeColExcel[$col_matricule].$ligne)->applyFromArray($left_align);
	  $objWorksheet1->getStyle($listeColExcel[$col_nbEval].$ligne)->applyFromArray($left_align);
	 if( $res['prenompersonnel'] == $nom_precedent)
	 {
	    $objWorksheet1->setCellValue($listeColExcel[$col_nom].$ligne,'--');
	 }
	 else
	 {
	   
	    $objWorksheet1->setCellValue($listeColExcel[$col_nom].$ligne,utf8_encode($res['prenompersonnel']));
	 }
	if( $res['matricule_notation'] == $matricule_precedent)
	{
	  $objWorksheet1->setCellValue($listeColExcel[$col_matricule].$ligne,'--');
	  
	}
	else
	{
	  $objWorksheet1->setCellValue($listeColExcel[$col_matricule].$ligne,utf8_encode($res['matricule_notation']));
	  
	}
	$objWorksheet1->setCellValue($listeColExcel[$col_nbEval].$ligne,utf8_encode($res['nombre']));
	$objWorksheet1->setCellValue($listeColExcel[$col_prestation].$ligne,utf8_encode($res['code']));
	$objWorksheet1->setCellValue($listeColExcel[$col_client].$ligne,utf8_encode($res['nom_client']));
	
	$objWorksheet1->getStyle($listeColExcel[$col_nom].$ligne)->applyFromArray($style_nom);
	$objWorksheet1->getStyle($listeColExcel[$col_matricule].$ligne)->applyFromArray($style_matricule);
	$objWorksheet1->getStyle($listeColExcel[$col_nbEval].$ligne)->applyFromArray($style_nbEval);
	$objWorksheet1->getStyle($listeColExcel[$col_prestation].$ligne)->applyFromArray($style_prestation);
	$objWorksheet1->getStyle($listeColExcel[$col_client].$ligne)->applyFromArray($style_client);
	// $objWorksheet1->getColumnDimension($listeColExcel[$col_nom])->setWidth(60);
    $nom_precedent = $res['prenompersonnel'];
	$matricule_precedent = $res['matricule_notation'];
	$ligne++;
	
	}
	$objWorksheet1->getStyle($listeColExcel[2].($ligne+1))->applyFromArray($style_total);
	$objWorksheet1->getStyle('A'.($ligne+1).':E'.($ligne+1))->applyFromArray($style_ligne_total);
	$objWorksheet1->setCellValue($listeColExcel[2].($ligne+1),'Total évaluation');
	$objWorksheet1->setCellValue($listeColExcel[3].($ligne+1),'=SUM(C4:C'.$ligne.')');
	
	/****************************************************/
	    
	
	
	$mail = new PHPmailer();
	$mail->IsHTML(true);
	$mail->From     = "doNotReply@vivetic.mg"; // votre adresse
	//$mail->FromName = 'NF345'; // votre nom
	$mail->FromName = utf8_decode("NF345"); // votre nom
	$mail->Subject  = utf8_decode("NF 345 - Récapitulatif d'évaluation"); // sujet de votre message

	$header_mail = "<p>Bonjour ,</p>
	<p>Ci-apr&egrave;s, et en attach&eacute;, la liste r&eacute;capitulative du nombre d'&eacute;valuations effectu&eacute;es par &eacute;valuateur pour le mois de <b> ".$array_month[$previous_month]." ".$previous_year."</b>.</p>
	";
	$message = getvaleur($date_deb,$date_fin);
	
	$file = "recapitulatif_".str_replace("-","_",$date_deb)."__".str_replace("-","_",$date_fin).".xls";
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
	$objWriter->save('reporting/'.$file);
	
	$footer_mail = "<br /><div style='padding-bottom: 15px;'>Cordialement,
	<br/> <span class='link' ></span><br/> 

	<img src='cid:logo_vvt' />
	</div>";
	$mail->AddAddress("ando.randriamananjo@vivetic.mg", "Ando"); 	
	//$mail->AddAddress("tsilavina.si@vivetic.mg", "Tsilavina"); 	

	$mail->AddCC('tsilavina.si@vivetic.mg', 'Tsilavina');
	
    $mail->Body=$header_mail.$message.$footer_mail;
	$mail->AddEmbeddedImage('lib_mail/img/logo_vivetic_mail.png', 'logo_vvt');
	///in boucle ra mis fichier bdb o attachena 
    
	/* *********** Attachement Fichier ****************** */
		$directory = "reporting/";
		$target_path = $directory .basename($file);
		
		if(file_exists($target_path)) 
	    $mail->AddAttachment($target_path); 
		else 
	    echo $target_path.' inexistant';  
		
	/******************************************************/
	
	if(!$mail->Send()){ // on teste la fonction Send() -> envoyer 
	  echo $mail->ErrorInfo; //Affiche le message d'erreur 
	}
	else{      
	   echo 'Mail envoy&eacute; avec succ&egrave;s';
	}

	unset($mail);
				
   }
  /** else
   {
       echo "Aujourd'hui n'est pas le d&eacute;but du mois";
   }
   */
		
	
	function getvaleur($date_deb,$date_fin)
   {
	    global $conn;
		$sql="select prenompersonnel,matricule_notation,count(id_notation) nombre,code,nom_client from (
		select distinct n.id_notation,n.matricule_notation, p.prenompersonnel,ga.id_projet,ga.id_client,ga.id_application,gua.code,guc.nom_client
		from cc_sr_notation n
		inner join personnel p on p.matricule = n.matricule_notation
		inner join cc_sr_indicateur_notation inot on n.id_notation = inot.id_notation
		inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application
		inner join gu_client guc on guc.id_client = ga.id_client
		inner join gu_application gua on gua.id_application = ga.id_application
		where date_entretien >= '".$date_deb."' and date_entretien <= '".$date_fin."'
		order by matricule_notation ) as req1
		group by prenompersonnel,matricule_notation,code,nom_client
		order by prenompersonnel,code ASC";
		$query  = pg_query($conn,$sql) or die(pg_last_error($conn));

		$zHtml = '<div id="id_div_filtre_date"><span>Date d\'appel du : </span>';
		$zHtml .= '<input type="text" id="id_date_appel_deb" /> au ';
		$zHtml .= '<input type="text" id="id_date_appel_fin" />';
		$zHtml .= '<input type="button" id="id_btn_apercu" onclick="apercu();" />';
		$zHtml .= '</div>';
		$str = "";
				
				 
		$str .= '<table id="tab_liste" class="class_table">
		
		<thead class="fixedHeader">';
		$str .= '<tr  style="height:26px;">
		    <th style="background:#000;color:#FFF;font-weight:bold;font-size:11px;">Matricule Evaluateur</th>
			<th  style="background:#000;color:#FFF;font-weight:bold;font-size:11px;width:200px;">Pr&eacute;nom Evaluateur</th>			
			<th style="background:#000;color:#FFF;font-weight:bold;font-size:11px;">Nombre d\'&eacute;valuation</th>
			<th style="background:#000;color:#FFF;font-weight:bold;font-size:11px;">Prestation</th>
			<th style="background:#000;color:#FFF;font-weight:bold;font-size:11px;">Client</th>
		</tr>
		</thead>';
		$i=0;
		$color='#c2dbe0';
		$total_eval = 0 ;
		$nom_precedent = '';
	    $matricule_precedent = '';
		while($res = pg_fetch_array($query))
		{
			if($i%2==0)  $color='#f8f8f8';
			else $color='#c2dbe0';
			$total_eval += (float) $res['nombre'];
			$str .= '<tr>';
			    if( $res['matricule_notation'] == $matricule_precedent)
				{
				 $str .= '<td align="center" style="background:'.$color.';border:none; ">--</td>';
				}
				else
				{
				 $str .= '<td align="center" style="background:'.$color.';border:none; ">'.$res['matricule_notation'].'</td>';
				}
			    if( $res['prenompersonnel'] == $nom_precedent)
				 {
				 $str .= '<td style="background:'.$color.';border:none; ">--</td>';
				 }
				 else
				 {
				 $str .= '<td style="background:'.$color.';border:none; ">'.utf8_encode( $res['prenompersonnel'] ).'</td>';
				 }
							
			  $str .= '<td  align="center"  style="background:'.$color.';border:none; ">'.$res['nombre'].'</td>
				<td  align="center"  style="background:'.$color.';border:none; ">'.$res['code'].'</td>
				<td  align="center" style="background:'.$color.' ;border:none;">'.$res['nom_client'].'</td>
			</tr>';
			$nom_precedent = $res['prenompersonnel'];
	        $matricule_precedent = $res['matricule_notation'];
			$i++;
		}
		$str .= '</table>';
		$str .= "			 
					  <table border=1 class='class_table'  width='59%' height='30px;' >
						 <thead class='fixedHeader'>
						   <tr style='background:#000' class='alternateRow'>
							 <th width='50%' colspan='5'><span style='color:#FFF;font-size:12px;'>Total évaluation:</span><span style='color:red;font-size:12px;margin-left:5px;'>".$total_eval."</span></th>
							 
						   
						</tr>
					 </thead>
					</table>
				   ";
		return utf8_decode( $str );
		}

	function finDuMois ($date)
	{  
	  $date_f = $date;
		list($year,$month,$day) = explode('-', $date);
		$date_f = $year."-".$month."-31";
				if (checkdate($month,"31",$year) == false) {
					$date_f = $year."-".$month."-30";
					if (checkdate($month,"30",$year) == false) {
						$date_f = $year."-".$month."-29";
						if (checkdate($month,"29",$year) == false) {
							$date_f = $year."-".$month."-28";
						}
					}
				}
		return $date_f ;
	} 

	?>