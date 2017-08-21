<?php 

require_once 'PHPExcel/IOFactory.php';
include_once 'PHPExcel/Writer/Excel5.php';
include_once 'PHPExcel/Writer/Excel2007.php';
require_once 'PHPExcel.php';
//require_once 'incfunc.php';
include("/var/www.cache/dgconn.inc");
//include('function_synthese_dynamique.php');
//include('function_dynamique.php');
//include("/var/www.cache/siapconn.inc");
//include("/var/www.cache/rhconn.inc");
//ini_set('max_execution_time', 0);
//set_time_limit(0);

$fichier = 'evaluation.xls';
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

function _getInfoCC($date_notation_deb,$date_notation_fin,$id_type_traitement,$non_eval)
{
	global $conn;
	$str = "";
	if($non_eval == 1)
	{
		$str .= " and req2.nombre is null ";
	}
	elseif($non_eval == 0)
	{
		$str .= " and req2.id_type_traitement = ".$id_type_traitement." ";
	}
	else
	{
		$str .= "";
	}
	$sql = "select req3.matricule, req1.nompersonnel, req3.prenompersonnel, req3.fonctioncourante, req2.nombre, req1.actifpers,req2.id_projet
,req2.code,req3.code3,req2.nom_application,req2.id_type_traitement,req2.id_application, req2.id_client,req3.duree
from (
	SELECT  matricule, nompersonnel,prenompersonnel,fonctioncourante,actifpers 
	FROM personnel WHERE actifpers='Active'  AND fonctioncourante ='TC' order by matricule ASC 
) as req1
left join (
	select n.matricule, count(distinct n.id_notation) as nombre, p.nompersonnel, p.prenompersonnel, 
	p.fonctioncourante,ga.id_projet, ga.id_client, ga.id_application,gua.code, gua.nom_application,
	guc.nom_client, cg.id_type_traitement
	from cc_sr_notation n 
	inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation
	inner join personnel p on p.matricule = n.matricule
	inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application
	inner join gu_application gua on gua.id_application = ga.id_application
	inner join gu_client guc on guc.id_client = ga.id_client
	inner join cc_sr_grille g on ga.id_grille = g.id_grille
	inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
	where inot.id_grille_application is not null 
	--and n.date_notation >= '".$date_notation_deb."' and n.date_notation <= '".$date_notation_fin."'
	and n.date_entretien >= '".$date_notation_deb."' and n.date_entretien <= '".$date_notation_fin."'
	group by n.matricule, p.nompersonnel, p.prenompersonnel, p.fonctioncourante,n.id_projet,
	ga.id_projet, ga.id_client, ga.id_application,gua.code, gua.nom_application,guc.nom_client,cg.id_type_traitement 
	order by n.matricule
) as req2 on req1.matricule = req2.matricule
right join
(
	select  matricule, code3, deptcourant, fonctioncourante,
	       prenompersonnel,sum(duree) as duree  from 
	(  SELECT matricule, substr(idcommande,1,3) as code3, deptcourant, fonctioncourante,
	       prenompersonnel, deb
	     ,
	     case when date_part('day'::text, (duree)*24)+date_part('hour'::text, (duree)) +( date_part('minutes'::text, (duree)) / 60::double precision)+ ( date_part('seconds'::text, duree) / 3600)::double precision > 15
	     then 8 else 
	     date_part('day'::text, (duree)*24)+date_part('hour'::text, (duree)) +( date_part('minutes'::text, (duree)) / 60::double precision)+ ( date_part('seconds'::text, duree) / 3600)::double precision 
	     end as duree
	  FROM duree_prod_par_matricule_dept_ca
	  where deb >= '".$date_notation_deb."' and deb <='".$date_notation_fin."'
	  and fonctioncourante = 'TC'
	  order by matricule
	  )as res
	  group by  matricule, code3, deptcourant, fonctioncourante,
	       prenompersonnel
	order by matricule
) as req3 on (req1.matricule = req3.matricule and req2.code = req3.code3)
where 1=1 
".$str."
order by req3.code3,req3.matricule,req2.id_type_traitement
";
	$query = pg_query($conn,$sql) or die (pg_last_error());
	return $query;
}

function getInfoCC($date_notation_deb,$date_notation_fin,$id_type_traitement,$non_eval)
{
	global $conn;
	$str = "";
	if($non_eval == 1)
	{
		$str .= " and req2.nombre is null ";
	}
	//--and n.date_notation >= '".$date_notation_deb."' and n.date_notation <= '".$date_notation_fin."'
	$sql = "select req3.matricule, req1.nompersonnel, req3.prenompersonnel, req3.fonctioncourante, req2.nombre, req1.actifpers,req2.id_projet
,req2.code,req3.code3,req2.nom_application,req2.id_application, req2.id_client,req3.duree
from (
	SELECT  matricule, nompersonnel,prenompersonnel,fonctioncourante,actifpers 
	FROM personnel WHERE actifpers='Active'  AND fonctioncourante ='TC' order by matricule ASC 
) as req1
left join (
	select n.matricule, count(distinct n.id_notation) as nombre, p.nompersonnel, p.prenompersonnel, 
	p.fonctioncourante,ga.id_projet, ga.id_client, ga.id_application,gua.code, gua.nom_application,
	guc.nom_client
	from cc_sr_notation n 
	inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation
	inner join personnel p on p.matricule = n.matricule
	inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application
	inner join gu_application gua on gua.id_application = ga.id_application
	inner join gu_client guc on guc.id_client = ga.id_client
	inner join cc_sr_grille g on ga.id_grille = g.id_grille
	inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
	where inot.id_grille_application is not null 
	and n.date_entretien >= '".$date_notation_deb."' and n.date_entretien <= '".$date_notation_fin."'
	group by n.matricule, p.nompersonnel, p.prenompersonnel, p.fonctioncourante,n.id_projet,
	ga.id_projet, ga.id_client, ga.id_application,gua.code, gua.nom_application,guc.nom_client 
	order by n.matricule
) as req2 on req1.matricule = req2.matricule
right join
(
	select  matricule, code3, deptcourant, fonctioncourante,
	       prenompersonnel,sum(duree) as duree  from 
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
	  FROM duree_prod_par_matricule_dept_ca
	  where deb >= '".$date_notation_deb."' and deb <='".$date_notation_fin."'
	  and fonctioncourante = 'TC'
	  order by matricule
	  )as res
	  group by  matricule, code3, deptcourant, fonctioncourante,
	       prenompersonnel
	order by matricule
) as req3 on (req1.matricule = req3.matricule and req2.code = req3.code3)
where 1=1 
".$str."
order by req3.code3,req3.matricule
";

	$query = pg_query($conn,$sql) or die (pg_last_error());
	return $query;
}

function derniere_semaine($monday)
{
	$tab = array();
	$jr_1     = "-1 day";
	$date_fin = date('Y-m-d', strtotime($monday. $jr_1));
	$jr_7   = "-7 day";
	$date_deb = date('Y-m-d', strtotime($monday. $jr_7));
	$tab['debut'] = $date_deb;
	$tab['fin'] = $date_fin;
	return $tab;
}

function getmailSup()
{
	global $conn;
	$sql = "select matricule,prenompersonnel,emailpers from personnel where fonctioncourante in ('DQ') and actifpers = 'Active' limit 1";
	$query = pg_query($conn,$sql) or die (pg_last_error());
	$result = pg_fetch_array($query);
	$table = array();
	$table['matricule'] = $result['matricule'];
	$table['prenom'] = $result['prenompersonnel'];
	$table['email'] = $result['emailpers'];
	return $table;
}

$listeColExcel = array("1" => "A","2" => "B" ,"3" => "C" ,"4" => "D" ,"5" => "E" ,"6" => "F" ,"7" => "G" ,"8" => "H" ,"9" => "I" ,"10" => "J" ,"11" => "K" ,"12" => "L" ,"13" => "M" ,"14" => "N" ,"15" => "O" ,"16" => "P" ,"17" => "Q" ,"18" => "R" ,"19" => "S" ,"20" => "T" ,"21" => "U" ,"22" => "V" ,"23" => "W" ,"24" => "X" ,"25" => "Y" ,"26" => "Z" ,"27" => "AA" ,"28" => "AB" ,"29" => "AC" ,"30" => "AD" ,"31" => "AE" ,"32" => "AF" ,"33" => "AG" ,"34" => "AH" ,"35" => "AI" ,"36" => "AJ" ,"37" => "AK" ,"38" => "AL" ,"39" => "AM" ,"40" => "AN" ,"41" => "AO" ,"42" => "AP" ,"43" => "AQ" ,"44" => "AR","45" => "AS","46" => "AT","47" => "AU","48" => "AV","49" => "AW","50" => "AX","51" => "AY","52" => "AZ","53" => "BA","54" => "BB","55" => "BC","56" => "BD","57" => "BE","58" => "BF","59" => "BG","60" => "BH","61" => "BI","62" => "BJ","63" => "BK","64" => "BL","65" => "BM","66" => "BN","67" => "BO","68" => "BP","69" => "BQ","70" => "BR","71" => "BS","72" => "BT","73" => "BU","74" => "BV","75" => "BW","76" => "BX","77" => "BY","78" => "BZ","79" => "CA","80" => "CB","81" => "CC","82" => "CD","83" => "CE","84" => "CF","85" => "CG","86" => "CH","87" => "CI","88" => "CJ","89" => "CK","90" => "CL","91" => "CM","92" => "CN","93" => "CO","94" => "CP","95" => "CQ","96" => "CR","97" => "CS","98" => "CT","99" => "CU","100" => "CV","101" => "CW","102" => "CX","103" => "CY","104" => "CZ","105" => "DA","106" => "DB","107" => "DC","108" => "DD","109" => "DE","110" => "DF","111" => "DG","112" => "DH","113" => "DI");


$objet = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objet->load($inputFileName);




$objPHPExcel->getSheet(0);
$objPHPExcel->setActiveSheetIndex(0);

$objWorksheet1 = $objPHPExcel->getActiveSheet();

$titleSheet = 'Eval-Semaine';
$objPHPExcel->getActiveSheet()->setTitle($titleSheet);
$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(80);
$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(35);
$objPHPExcel-> getActiveSheet()->freezePane ('A4');
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
$weekMondayTime = date('Y-m-d', strtotime('Monday this week'));
$semaine = derniere_semaine($weekMondayTime);

$monday = $semaine['debut'];
$sunday = $semaine['fin'];

$deb = explode('-',$monday);
$set_monday = $deb[2].'/'.$deb[1].'/'.$deb[0];

$fin = explode('-',$sunday);
$set_sunday = $fin[2].'/'.$fin[1].'/'.$fin[0];
$num_semaine = date("W", strtotime($monday));

setTableau($monday,$sunday,$set_monday,$set_sunday,$objWorksheet1,$listeColExcel,$num_semaine);

$objWorksheet1 = $objPHPExcel->createSheet();
$objPHPExcel->getSheet(1);
$objPHPExcel->setActiveSheetIndex(1);
$objWorksheet1 = $objPHPExcel->getActiveSheet();
$titleSheet = "Eval-Mois";
$objWorksheet1->setTitle($titleSheet);
$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(80);
$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(35);
$objPHPExcel-> getActiveSheet()->freezePane ('A4');

$dat = explode('-',$monday);
$deb_annee = $dat[0];
$deb_mois = $dat[1];

setTableau(date($deb_annee.'-'.$deb_mois.'-01'),$sunday,date('01/'.$deb_mois.'/'.$deb_annee),$set_sunday,$objWorksheet1,$listeColExcel,0);

function setTableau($monday,$sunday,$set_monday,$set_sunday,$objWorksheet1,$listeColExcel,$num_semaine)
{
	/* ********** TABLEAU STYLE ************************ */
	include('export_style.php');
	/* **************************************** */
	/* ********** ENTETE ****************** */
	$objWorksheet1->setCellValue('A1',$set_monday);
	$objWorksheet1->setCellValue('B1',$set_sunday);
	$objWorksheet1->getStyle('A1:B1')->applyFromArray($style_detail_eval_date);
	if($num_semaine != 0)
	{
		$objWorksheet1->mergeCells('A2:B2');
		$objWorksheet1->getStyle('A2')->applyFromArray($style_detail_eval_date);
		$objWorksheet1->setCellValue('A2','Semaine '.$num_semaine);
	}
	$icell = 1;
	$Ligne = 3;
	$debut_center_deb = $listeColExcel[$icell];
	$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(15);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,'Prestation');$icell++;
	$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(15);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,'Matricule CC');$icell++;
	$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(50);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,'Prénom CC');$icell++;
	$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(10);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,'Fonction');$icell++;
	/*$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(15);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,'Traitement');$icell++;*/
	$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(15);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,'Temps passé (heures)');$icell++;
	$objWorksheet1->getColumnDimension($listeColExcel[$icell])->setWidth(15);
	$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,'Nb d\'évaluation');$icell++;
	$debut_center_fin = $listeColExcel[$icell-1];
	$objWorksheet1->getStyle($debut_center_deb.$Ligne.':'.$debut_center_fin.$Ligne)->applyFromArray($style_centre);
	$objWorksheet1->getStyle($debut_center_deb.$Ligne.':'.$debut_center_fin.$Ligne)->applyFromArray($style_titre);
	$objWorksheet1->getStyle($debut_center_deb.$Ligne.':'.$debut_center_fin.$Ligne)->applyFromArray($style_border);
	$objWorksheet1->getStyle($debut_center_deb.$Ligne.':'.$debut_center_fin.$Ligne)->applyFromArray($style_font_eval);

	/* **************************************/
	/* ********** CONTENU ***************** */
	$icell = 1;
	$Ligne++;
	$resultat = getInfoCC($monday,$sunday,0,2);

	while($res = pg_fetch_array($resultat))
	{
		if($res['nombre'] == '')
		{
			$nombre = 0;
		}
		else
		{
			$nombre = $res['nombre'];
		}
		$debut_center_deb = $listeColExcel[$icell];
		$objWorksheet1->getStyle($listeColExcel[$icell].$Ligne)->applyFromArray($style_centre);
		$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,$res['code3']);$icell++;
		$objWorksheet1->getStyle($listeColExcel[$icell].$Ligne)->applyFromArray($style_centre);
		$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,$res['matricule']);$icell++;
		$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,utf8_encode($res['prenompersonnel']));$icell++;
		$objWorksheet1->getStyle($listeColExcel[$icell].$Ligne)->applyFromArray($style_centre);
		$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,$res['fonctioncourante']);$icell++;
		/*$objWorksheet1->getStyle($listeColExcel[$icell].$Ligne)->applyFromArray($style_centre);
		$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,$traitement_abrev[$res['id_type_traitement']]);$icell++;*/
		$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,number_format($res['duree'],2));$icell++;
		$objWorksheet1->getStyle($listeColExcel[$icell].$Ligne)->applyFromArray($style_centre);
		$objWorksheet1->setCellValue($listeColExcel[$icell].$Ligne,$nombre);
		$debut_center_fin = $listeColExcel[$icell];
		$objWorksheet1->getStyle($debut_center_deb.$Ligne.':'.$debut_center_fin.$Ligne)->applyFromArray($style_contenu_detail_eval);
		if($nombre == 0)
		{
			$objWorksheet1->getStyle($debut_center_deb.$Ligne.':'.$debut_center_fin.$Ligne)->applyFromArray($style_detail_eval);
		}
		$icell = 1;
		$Ligne++;
	}
}
/* **************************************/
/* ************************************************************** **/
/* ************************************************************** **/
/* ************************************************************** **/
$message = '
<style>
	.class_div_contenu {
		font-family:Verdana;
		font-size:11px;
		display:block;
		position:relative;
		margin: 0 15px 0 15px;
	}
	b {
		/*color: #091c7b;*/
		font-family:Verdana;
		font-size:13px;
	}
	#id_div_corps_mail{
		font-family: Arial;
		font-size:12px;
	}
</style>
<div id="id_div_corps_mail" class="class_div_contenu_">
	Bonjour,
	&nbsp;&nbsp;&nbsp;&nbsp;<p>En attach&eacute;, la liste des &eacute;valuations des CC suivant les dates de traitement du  <b>'.$set_monday.'</b>  au  <b>'.$set_sunday.' (Semaine '.$num_semaine.') </b></p>
</div>';
$message .= "<br /><div>Cordialement,<br/> <span class='link' ></span><br/> 
<img src='cid:logo_vvt' />
</div>";

$file = "evaluation_".str_replace("-","_",$monday)."__".str_replace("-","_",$sunday).'.xls';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 

//$objWriter = PHPExcel_IOFactory::createWriter($objWorksheet1, 'Excel5'); 
//header('Content-Type: application/vnd.ms-excel');
//header('Content-Disposition: attachment;filename="'.$file.'"');
$objWriter->save('FichierExcel/'.$file);
//readfile('FichierExcel/'.$file);
//exit;

require 'lib_mail/class.phpmailer.php';
$mail = new PHPmailer();
$mail->IsHTML(true);
$mail->FROM  = 'doNotReply@vivetic.mg'; // votre adresse
$mail->FromName ='[VIVETIC] Liste Evaluation'; // votre nom
$mail->Subject  = utf8_decode('Liste des évaluations CC'); // sujet de votre message

$tab_sup = getmailSup();
$mail->AddAddress($tab_sup['email'], $tab_sup['prenom']); 	// adresse du destinataire		
$mail->AddAddress('sc_qualite@vivetic.mg', 'sc_qualite'); 	// adresse du destinataire		
//$mail->AddAddress('njivaniaina@vivetic.mg', 'Njivaniaina'); 	// adresse du destinataire		

$mail->AddCC('si@vivetic.mg', 'SI'); // adresse en copie SI
//$mail->AddCC('tantely.si@vivetic.mg', 'Tantely'); 	// adresse en copie		
//$mail->AddCC('tsilavina.si@vivetic.mg', 'Tsilavina'); 	// adresse en copie

$mail->Body = utf8_encode($message);
$mail->AddEmbeddedImage('lib_mail/img/logo_vivetic_mail.png', 'logo_vvt');

/* *********** Attachement Fichier ****************** */
$directory = "FichierExcel/";
$target_path = $directory .basename($file);

if(file_exists($target_path)) 
	$mail->AddAttachment($target_path); 
else 
    echo $target_path.' inexistant';  
/* ************************************************** */

if(!$mail->Send()){ // on teste la fonction Send() -> envoyer 
   	$mail->ErrorInfo; //Affiche le message d'erreur 
}


unset($mail);

?>