<?php
	
	/**
	
		$month = '2011-01-20';
		$timestamp = strtotime ("-1 month",strtotime ($month));
		$nextMonth  =  date("Y-m-d",$timestamp);
	
	*/
	require 'lib_mail/class.phpmailer.php';
    include("/var/www.cache/dgconn.inc");
    $array_month = array ("01"=>"JANVIER","02"=>"FEVRIER","03"=>"MARS","04"=>"AVRIL",
	"05"=>"MAIS","06"=>"JUIN","07"=>"JUILLET","08"=>"AOUT","09"=>"SEPTEMBRE"
	,"10"=>"OCTOBRE","11"=>"NOVEMBRE","12"=>"DECEMBRE");
	$current_date = date('Y-m-d');
    $current_date = "2015-02-01";
	list($current_year,$current_month,$current_day) = explode("-",$current_date);

	
	    
		if( $current_day == '01'  )
	{
	
	$destinataire = 'ando.randriamananjo@vivetic.mg';
	$destinataire = 'tsilavina.si@vivetic.mg';
	$date_deb = date('Y-m-d', strtotime(date('Y-m')." -1 month"));
	$date_fin = finDuMois( $date_deb );  
	list($previous_year,$previous_month,$previous_day) = explode("-",$date_fin);
	$mail = new PHPmailer();
	$mail->IsHTML(true);
	$mail->From     = "doNotReply@vivetic.mg"; // votre adresse
	//$mail->FromName = 'NF345'; // votre nom
	$mail->FromName = utf8_decode("NF345"); // votre nom
	$mail->Subject  = utf8_decode("NF 345 - Récapitulatif d'évaluation"); // sujet de votre message

	$header_mail = "<p>Bonjour ,</p>
	<p>Ci-apr&egrave;s la liste r&eacute;capitulative du nombre d'&eacute;valuations effectu&eacute;es par &eacute;valuateur mois du <b> ".$array_month[$previous_month]." ".$previous_year."</b>.</p>
	";
	$message = getvaleur($date_deb,$date_fin);
	$footer_mail = "<br /><div style='padding-bottom: 15px;'>Cordialement,
	<br/> <span class='link' ></span><br/> 

	<img src='cid:logo_vvt' />
	</div>";
	//$mail->AddAddress($destinataire, "Ando"); 	
	$mail->AddAddress($destinataire, "Tsilavina"); 	
    $mail->Body=$header_mail.$message.$footer_mail;
	$mail->AddEmbeddedImage('lib_mail/img/logo_vivetic_mail.png', 'logo_vvt');
	///in boucle ra mis fichier bdb o attachena 
    
	if(!$mail->Send()){ // on teste la fonction Send() -> envoyer 
	  echo $mail->ErrorInfo; //Affiche le message d'erreur 
	}
	else{      
	   echo 'Mail envoy&eacute; avec succ&egrave;s';
	}

	unset($mail);
				
   }
   /**else
   {
       echo "Aujourd'hui n'est pas le d&eacute;but du mois";
   }*/
		
	
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
		order by matricule_notation";
		$query  = pg_query($conn,$sql) or die(pg_last_error($conn));

		$zHtml = '<div id="id_div_filtre_date"><span>Date d\'appel du : </span>';
		$zHtml .= '<input type="text" id="id_date_appel_deb" /> au ';
		$zHtml .= '<input type="text" id="id_date_appel_fin" />';
		$zHtml .= '<input type="button" id="id_btn_apercu" onclick="apercu();" />';
		$zHtml .= '</div>';
		$str = "";
				
				 
		$str .= '<table id="tab_liste" class="class_table">
		
		<thead class="fixedHeader">';
		$str .= '<tr  style="height:28px;">
			<th  style="background:#000;color:#FFF;font-weight:bold;font-size:12px;width:200px;">Pr&eacute;nom Evaluateur</th>
			<th style="background:#000;color:#FFF;font-weight:bold;font-size:12px;">Matricule Evaluateur</th>
			<th style="background:#000;color:#FFF;font-weight:bold;font-size:12px;">Nombre d\'&eacute;valuation</th>
			<th style="background:#000;color:#FFF;font-weight:bold;font-size:12px;">Prestation</th>
			<th style="background:#000;color:#FFF;font-weight:bold;font-size:12px;">Client</th>
		</tr>
		</thead>';
		$i=0;
		$color='#FFFFFF';
		$total_eval = 0 ;
		while($res = pg_fetch_array($query))
		{
			if($i%2==0)  $color='#FFDBB7';
			else $color='#FFFFFF';
			$total_eval += (float) $res['nombre'];
			$str .= '<tr>
				<td style="background:'.$color.';border:none; ">'.( $res['prenompersonnel'] ).'</td>
				<td align="center" style="background:'.$color.';border:none; ">'.$res['matricule_notation'].'</td>
				<td  align="center"  style="background:'.$color.';border:none; ">'.$res['nombre'].'</td>
				<td  align="center"  style="background:'.$color.';border:none; ">'.$res['code'].'</td>
				<td  align="center" style="background:'.$color.' ;border:none;">'.$res['nom_client'].'</td>
			</tr>';
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