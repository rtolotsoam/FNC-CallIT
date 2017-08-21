<?php
include("/var/www.cache/dgconn.inc");
include("/var/www.cache/siapconn.inc");
include("/var/www.cache/rhconn.inc");

$liste = array();


function nombre_conge($matricule,$abs,$date_deb,$date_fin)
{
	global $siapconn;
	$sql = 'select sum(duree)
from nb_absence_view 
where
abs_matricule = \''.$matricule.'\' 
and "abs_abstypeId" = '.$abs.'
and deb between \''.$date_deb.'\' and \''.$date_fin.'\' and fin between \''.$date_deb.'\' and \''.$date_fin.'\'';
	$result = @pg_query($siapconn,$sql) or die(pg_last_error($conn));
	$sum = @pg_fetch_array($result);
	return $sum['sum'];
	
}
/*
function salaire_base($matricule,$date_)
{
	global $conn;
	$date = explode('-',$date_);
	$mois = $date[1];
	$annee = $date[0];
	$sql = "select rh_salaire_base,rh_salaire_brute from rh_salaire where rh_matricule = ".$matricule." and rh_mois::integer = ".$mois." and rh_annee::integer = ".$annee;
	$result = @pg_query($conn,$sql) or die(pg_last_error($conn));
	$salaire_base = @pg_fetch_array($result);
	return $salaire_base;
	
}*/
function salaire_base($matricule,$date_)
{
	global $conn;
	$date = explode('-',$date_);
	$mois = $date[1];
	$annee = $date[0];
	$salaire = getSalaire1($matricule,$mois,$annee);
	return $salaire;
}

/*
function salaire_brute_12dernier_mois($matricule,$date_)
{
	global $conn;
	
	$date = explode('-',$date_);
	$mois = $date[1];
	$annee = $date[0];
	
	$mois_1 = $mois;
	$mois_fin = $mois - 1;
	if($mois_fin == 0) $mois_fin = 12;
	$annee_1 = $annee - 1;
	$annee_fin = $annee;
	$sql = "select sum(rh_salaire_brute) as rh_salaire_brute from rh_salaire where 
rh_matricule = ".$matricule." 
and ((rh_mois::integer >= ".$mois_1." and rh_annee::integer = ".$annee_1.") or (rh_mois::integer <= ".$mois_fin." and rh_annee::integer = ".$annee_fin."))";
	//exemple rh_matricule = 256 => montant_salaire_brute = 1986466 de juillet 2013 a juin 2013
	$result = @pg_query($conn,$sql) or die(pg_last_error($conn));
	$salaire_brute = @pg_fetch_array($result);
	return $salaire_brute['rh_salaire_brute'];
}*/
function salaire_brute_12dernier_mois($matricule,$date_)
{
	global $conn;
	$date = explode('-',$date_);
	$mois = $date[1];
	$annee = $date[0];
	
	$mois_1 = $mois;
	$mois_fin = $mois - 1;
	//if($mois_fin == 0) $mois_fin = 12;
	$annee_1 = $annee - 1;
	$annee_fin = $annee;
	if($mois_fin == 0) 
	{
		$mois_fin = 12;
		$annee_fin = $annee -1 ;
	}
	
	$salaire = 0;
	$salaire = getSalaireDerniersMois1($matricule,$mois_1,$annee_1);
	return $salaire;
	
}

function nb_hs($matricule,$date_deb,$date_fin)
{
	global $conn;
	$sql = "select sum(heure) as heure, sum(heure * tdbrh_valeur) as total_montant from tdbrh_hs_detail_view where matricule = ".$matricule."and date >='".$date_deb."' and date <= '".$date_fin."'";
	$result = @pg_query($conn,$sql) or die(pg_last_error($conn));
	$nb_hs = @pg_fetch_array($result);
	return $nb_hs;
}
	    
function majoration_($matricule,$date_deb,$date_fin)
{
	global $conn;
	$sql = "select sum(hn40) as hn40 ,sum(hn50) as hn50,sum(hn100) as hn100,sum(maj30) as maj30,sum(maj50) as maj50 from tdbrh_majore_detail_view where matricule =".$matricule." and date >= '".$date_deb."' and date <= '".$date_fin."' group by matricule";
	$result = @pg_query($conn,$sql) or die(pg_last_error($conn));
	$majoration = @pg_fetch_array($result);
	return $majoration;
}

function index_($date_deb,$date_fin)
{
	global $liste;
	//getListePersonnel();
	//$date_deb = '2013-11-23';
	//$date_fin = '2013-12-22';
	$val = liste_($date_deb,$date_fin);
	return $val;
}

function getSalaire1 ($matricule, $mois_deb, $annee_deb){
include_once("cryptage.php");

	global $rhconn;
	$salaire = array();
	if ($mois_deb < 10 ) $mois_deb = 1*$mois_deb;
	 $mois_deb_m_1 = $mois_deb - 1 ;
	 
	 if ($mois_deb_m_1 == 0 || $mois_deb_m_1 == '00') {
		$mois_deb_m_1 = 12;
		$annee_deb_m_1 = $annee_deb -1;
	 }else $annee_deb_m_1 = $annee_deb; 
	 
	  $mois_deb_m_2 = $mois_deb_m_1 - 1 ;
	  if ($mois_deb_m_2 == 0 || $mois_deb_m_2 == '00') {
		$mois_deb_m_2 = 12;
		$annee_deb_m_2 = $annee_deb -1;
	 }else {$annee_deb_m_2 = $annee_deb; 
			if ($mois_deb_m_2 == 11) {
				$annee_deb_m_2 = $annee_deb-1;
			}
	}
	
	$csalaire = 0;
	$csalairebrute = 0;
	
	$query_salaire = "
		SELECT distinct RHS3.rh_salaire_base as csalaire_m ,RHS2.rh_salaire_base as csalaire_m_1, 
			coalesce(RHS1.rh_salaire_base,'MA') as csalaire_m_2,
			RHS3.rh_salaire_brute as csalairebrute_m ,RHS2.rh_salaire_brute as csalairebrute_m_1, 
			coalesce(RHS1.rh_salaire_brute,'MA') as csalairebrute_m_2
		FROM rh_salaire RHS
		LEFT JOIN rh_salaire RHS1 ON RHS1.rh_matricule = RHS.rh_matricule 
			AND RHS1.rh_mois = '".$mois_deb_m_2."' AND RHS1.rh_annee = '".$annee_deb_m_2."'
		LEFT JOIN rh_salaire RHS2 ON RHS2.rh_matricule = RHS.rh_matricule 
			AND RHS2.rh_mois = '".$mois_deb_m_1."' AND RHS2.rh_annee = '".$annee_deb_m_1."'
		LEFT JOIN rh_salaire RHS3 ON RHS3.rh_matricule = RHS.rh_matricule 
			AND RHS3.rh_mois='".$mois_deb."' AND RHS3.rh_annee='".$annee_deb."'
		WHERE RHS.rh_matricule ='".$matricule."'
	";
	
	$res_salaire = pg_query ($rhconn,$query_salaire) or die("3/".$query_salaire);
	$result_salaire['csalaire'] = @pg_fetch_array ($res_salaire, 0) ;

	$csalaire_m = $result_salaire['csalaire'][0]; 
	$csalaire_m_1 = $result_salaire['csalaire'][1]; 
	$csalaire_m_2 = $result_salaire['csalaire'][2]; 
	$csalairebrute_m = $result_salaire['csalaire'][3]; 
	$csalairebrute_m_1 = $result_salaire['csalaire'][4]; 
	$csalairebrute_m_2 = $result_salaire['csalaire'][5]; 
	
	
	if ($csalaire_m != '') {
		$csalaire = $csalaire_m;  

	}else {  
		if ($csalaire_m_1 != '') { 
			    $csalaire = $csalaire_m_1;  
		}else{
				$csalaire = $csalaire_m_2; 
			}	
		
	}
	if ($csalairebrute_m != '') {
		$csalairebrute = $csalairebrute_m;  

	}else {  
		if ($csalairebrute_m_1 != '') { 
			    $csalairebrute = $csalairebrute_m_1;  
		}else{
				$csalairebrute = $csalairebrute_m_2; 
			}	
		
	}
	$salaire["salaire_base"] = f_decode64_wd($csalaire) ;
	$salaire["salaire_brute"] = f_decode64_wd($csalairebrute) ;
	return $salaire ;
}



function getSalaireDerniersMois1 ($matricule, $mois_deb, $annee_deb){
	global $rhconn;
	include_once("cryptage.php");
	
	$mois_1 = $mois_deb;
	$mois_fin = $mois_deb - 1;
	if($mois_fin == 0) $mois_fin = 12;
	$annee_1 = $annee_deb - 1;
	$annee_fin = $annee_deb;
	
	$csalairebrute_ = 0;
	$csalairebrute = 0;
	$salaire = 0;
	
	$query_salaire = "
		SELECT distinct coalesce(rh_salaire_brute,'MA') as csalairebrute
		FROM rh_salaire 
		WHERE rh_matricule ='".$matricule."' and ((rh_mois::integer >= ".$mois_1." and rh_annee::integer = ".$annee_1.") or (rh_mois::integer <= ".$mois_fin." and rh_annee::integer = ".$annee_fin."))
	";
	
	$res_salaire = pg_query ($rhconn,$query_salaire) or die("4/".$query_salaire);
	 while ($lg = @pg_fetch_array ($res_salaire))
		{													
			$csalairebrute_ = $lg["csalairebrute"] ; 
			$csalairebrute = f_decode64_wd($csalairebrute_) ;
			$salaire += $csalairebrute;
			$i++;
		}
	return  $salaire ;
}

function getPrime_tri($date_deb,$date_fin)
{
	global $conn;
	
	$date = explode('-',$date_deb);
	$mois = (int) $date[1];
	$annee = (int) $date[0];
	
	$mois_deb = $mois;
	$mois_fin = $mois + 1;
	
	$annee_deb = $annee;
	$annee_fin = $annee;
	
	if($mois_fin == 13) 
	{
		$mois_fin = 1;
		$annee_fin = $annee + 1;
	}
	
	$sql = "select * from 
	(
		select *, 
		extract ('YEAR' from prime_datedeb) as anneedeb,
		extract ('YEAR' from prime_datefin) as anneefin,
		extract ('MONTH' from prime_datedeb) as moisdeb,
		extract ('MONTH' from prime_datefin) as moisfin
		from grh_prime 
	) as one 
	where moisdeb = ".$mois_deb." and moisfin = ".$mois_fin." and anneedeb = ".$annee_deb." and anneefin = ".$annee_fin." order by prime_matricule";
	$result = @pg_query($conn,$sql) or die(pg_last_error($conn));
	
	$tab = array();
	while($arr = pg_fetch_array($result))
	{
		$tab[$arr['prime_matricule']][$arr['prime_categorie']] = $arr['prime_montant'];
	}
	
	return $tab;
}

function liste_($date_deb,$date_fin)
{
	global $conn;
	global $liste;
	
	$table_prime = getPrime_tri($date_deb,$date_fin);

	$sql= "select * from tdbrh_personnel_view where  matricule < 9000 and tmp_embauche <= '".$date_fin."' order by matricule,fonctioncourante asc";
	$result = @pg_query($conn,$sql) or die(pg_last_error($conn));
	
	$bu = array('CODIR','RH','BPO','RC','GC','VIDE');
	$rh = array('ADMIN PERS');
	//$bpo = array('BATCH','BPO');
	$bpo = array();
	$rc = array('CC');
	//$gc = array('1 TO 1');
	$gc = array();
	$vide = array('DAF','CDI','COM MK','QUALITE','INFO PROD','DT','INFO MAINT','METHODES','POLE RH','DQ','INFO SI','TWO','ACCUEIL','INFO DEV PHP','DP','MBE','DCT','LOGISTIQUE','AGC','DG','DRH');
	$tete = array(5603,5898,5025,5179,2281,866,1296);
	
	$up_rh = array();
	$up_bpo = array();
	$up_rc = array();
	$up_gc = array();
	$up_ = array();
	
	$matr_rh = array();
	$matr_bpo = array();
	$matr_rc = array();
	$matr_gc = array();
	$matr_ = array();
	
	$i = 1;
	while ($res = @pg_fetch_assoc($result))
	{
		
		$dept = trim($res['deptparent']);
		$up = trim($res['deptcourant']);
		$matr = $res['matricule'];
		
		if((strpos($dept,'BATCH') !== false || strpos($dept,'BPO') !== false) && (!in_array($dept,$bpo)))
		{
			array_push($bpo,$dept);
		}
		if((strpos($dept,'1 TO 1') !== false) && (!in_array($dept,$gc)))
		{
			array_push($gc,$dept);
		}
		
		if($key = array_search($matr,$tete))
		{
			$k = 0;
		}
		else 
		{
			//if($key_rh = array_search($dept,$rh))
			if(in_array($dept,$rh))
			{
				$k = 1;
				if(!in_array($up,$up_rh))
				{
					array_push($up_rh,$up);
					
				}
				array_push($matr_rh,$matr);
			}
			//if($key_bpo = array_search($dept,$bpo))
			elseif(in_array($dept,$bpo))
			{
				$k = 2;
				if(!in_array($up,$up_bpo))
				{
					array_push($up_bpo,$up);
					
				}
				array_push($matr_bpo,$matr);
			}
			//if($key_rc = array_search($dept,$rc))
			elseif(in_array($dept,$rc))
			{
				$k = 3;
				if(!in_array($up,$up_rc))
				{
					array_push($up_rc,$up);
					
				}
				array_push($matr_rc,$matr);
			}
			//if($key_gc = array_search($dept,$gc))
			elseif(in_array($dept,$gc))
			{
				$k = 4;
				if(!in_array($up,$up_gc))
				{
					array_push($up_gc,$up);
					
				}
				array_push($matr_gc,$matr);
			}
			else 
			{
				$k = 5;
				if(!in_array($up,$up_))
				{
					array_push($up_,$up);
					
				}
				array_push($matr_,$matr);
			}
		}
		
		$nb_conge_alc = nombre_conge($matr,1,$date_deb,$date_fin);
		$nb_conge_ads = nombre_conge($matr,10,$date_deb,$date_fin);
		$nb_conge_maternite = nombre_conge($matr,3,$date_deb,$date_fin);
		$nb_trav_sb = 30 - $nb_conge_alc - $nb_conge_ads - $nb_conge_maternite;
		$nb_jour_travaille = $nb_trav_sb + $nb_conge_alc + $nb_conge_maternite;
		$nb_heure = $nb_jour_travaille * 173.33 / 30;
		
		$sb_ = salaire_base($matr,$date_deb);
		$sb = $sb_['salaire_base'];
		$sal_brute = $sb_['salaire_brute'];
		$sb_mensuel = $sb * $nb_trav_sb / 30;
		$demi_sal_maternite = ($sb * $nb_conge_maternite / 30) /2;
		$regularisation_sb = 0;
		$montant_sb = $sb_mensuel + $demi_sal_maternite + $regularisation_sb;
		
		//$nb_conge = $nb_conge_alc + $nb_conge_ads + $nb_conge_maternite;
		$nb_conge = $nb_conge_alc;
		$montant_salaire_brute = salaire_brute_12dernier_mois($matr,$date_deb);
		$alloc_conge = ($nb_conge * $montant_salaire_brute) / 24;
		//echo '('.$nb_conge.'*'.$montant_salaire_brute.') / 24 = '.$alloc_conge;
		
		
		$nb = nb_hs($matr,$date_deb,$date_fin);
		$nb_HS = $nb['heure'];
		$total_montant = $nb['total_montant']; 
		
		$majoration = majoration_($matr,$date_deb,$date_fin);
		$HNmaj40 	= ($sb / 173.33) * $majoration['hn40'];
		$HNmaj50    = ($sb / 173.33) * $majoration['hn50'];
		$HNmaj100   = ($sb / 173.33) * $majoration['hn100'];
		$maj30 	    = ($sb / 173.33) * $majoration['maj30'];
		$maj50      = ($sb / 173.33) * $majoration['maj50'];
		
		if($k == 0)
		{
			$codir[$matr][1] = $bu[$k];//'bu'
			$codir[$matr][2] = $res['nom_dir'];//'direction'
			$codir[$matr][3] = $dept;//'dept'
			$codir[$matr][4] = $up;//'up'
			$codir[$matr][5] = $res['fonctioncourante'];//'fct'
			$codir[$matr][6] = $res['equipecourante'];//'eqp'
			$codir[$matr][7] = $res['matricule'];//'matricule'
			$codir[$matr][8] = utf8_encode($res['prenompersonnel']);//'prenom'
			$codir[$matr][9] = 1;//'effectif'
			$codir[$matr][10] = $res['niveaupers'];//'niveaupers'
			/*$embauche = date_create($res['tmp_embauche']);
			$codir[$matr][10] = date_format($embauche, 'd/m/Y');//'date_embauche'*/
			
			if($res["tmp_embauche"] == '' || $res["tmp_embauche"] == null || $res["tmp_embauche"] == '0000-00-00')
			{
				$codir[$matr][11] = '';
			}
			else 
			{
				$tmp_embauche = date_create($res["tmp_embauche"]);
				$codir[$matr][11] = date_format($tmp_embauche,'d/m/Y');
			}
			
			if($res["tmp_debauche"] == '' || $res["tmp_debauche"] == null || $res["tmp_debauche"] == '0000-00-00')
			{
				$codir[$matr][12] = '';
			}
			else 
			{
				$tmp_embauche = date_create($res["tmp_debauche"]);
				$codir[$matr][12] = date_format($tmp_embauche,'d/m/Y');
			}
			
			/*$debauche = date_create($res['tmp_debauche']);
			$codir[$matr][11] = date_format($debauche, 'd/m/Y');//'date_debauche'*/
			
			$codir[$matr][13] = $nb_trav_sb ? $nb_trav_sb : 0;//Nb jour trav SB
			$codir[$matr][14] = $nb_conge_alc ? $nb_conge_alc : 0;//Nb jour trav ALC
			$codir[$matr][15] = $nb_conge_maternite ? $nb_conge_maternite : 0;//Nb jour congé maternité
			$codir[$matr][16] = $nb_jour_travaille ? $nb_jour_travaille : 0;//Nb jours Travail
			$codir[$matr][17] = $nb_heure ? $nb_heure : 0;//Nb heure
			$codir[$matr][18] = $sb ? $sb : 0;//Salaire de base
			$codir[$matr][19] = $sb_mensuel;//Salaire de base mensuel
			$codir[$matr][20] = $demi_sal_maternite;//Demis-salaire maternité
			$codir[$matr][21] = $regularisation_sb;//Regularisation salaire de base
			$codir[$matr][22] = $montant_sb ? $montant_sb : 0;//MONTANT SB
			$codir[$matr][23] = $alloc_conge;//Allocation de congé
			$codir[$matr][24] = 0;//CONGES PAYES
			$codir[$matr][25] = $table_prime[$matr]['PRC'] ? $table_prime[$matr]['PRC'] : 0;//PRIME DE RECONNAISSANCE
			$codir[$matr][26] = 0;//Indemnité de transport
			$codir[$matr][27] = $table_prime[$matr]['PRP'] ? $table_prime[$matr]['PRP'] : 0;//Prime de performance
			$codir[$matr][28] = $table_prime[$matr]['PFI'] ? $table_prime[$matr]['PFI'] : 0;//Prime de fidélisation
			$codir[$matr][29] = 0;//Complément prime de fidélisation
			$codir[$matr][30] = $table_prime[$matr]['PRV'] ? $table_prime[$matr]['PRV'] : 0;//Prime variable
			$codir[$matr][31] = $table_prime[$matr]['PRO'] ? $table_prime[$matr]['PRO'] : 0;//Prime atteinte objectifs
			$codir[$matr][32] = $table_prime[$matr]['PRF'] ? $table_prime[$matr]['PRF'] : 0;//Prime de fonction
			$codir[$matr][33] = $nb_HS ? $nb_HS : 0;//Nb HS
			$codir[$matr][34] = $total_montant ? $total_montant : 0;//TOTAL HS
			$codir[$matr][35] = 0;//Autres
			$codir[$matr][36] = 0;//Vérification
			$codir[$matr][37] = 0;//Salaire brut prime de fidélisation
			$codir[$matr][38] = 0;//vide
			$codir[$matr][39] = $sal_brute ? $sal_brute : 0;//Salaire brut paie
			
			$liste[$matr][40] = 0;//Total salaire brut paie
			$liste[$matr][41] = 0;//Vide
			$liste[$matr][42] = 0;//Prime APM
			$liste[$matr][43] = $table_prime[$matr]['PAS'] ? $table_prime[$matr]['PAS'] : 0;//Prime assiduité
			$liste[$matr][44] = 0;//Prime spéciale centre de contact
			$liste[$matr][45] = $table_prime[$matr]['PSP'] ? $table_prime[$matr]['PSP'] : 0;//Prime spéciale
			$liste[$matr][46] = $table_prime[$matr]['PCA'] ? $table_prime[$matr]['PCA'] : 0;//Prime de caisse
			$liste[$matr][47] = $table_prime[$matr]['PEX'] ? $table_prime[$matr]['PEX'] : 0;//Prime exceptionnelle
			$liste[$matr][48] = 0;//Prime DEV PHP
			$liste[$matr][49] = $table_prime[$matr]['PAD'] ? $table_prime[$matr]['PAD'] : 0;//Prime spéciale ADLP
			$liste[$matr][50] = $table_prime[$matr]['PAP'] ? $table_prime[$matr]['PAP'] : 0;//Prime audio/prestation
			$liste[$matr][51] = 0;//Bonus VIVETIC
			$liste[$matr][52] = $table_prime[$matr]['BNC'] ? $table_prime[$matr]['BNC'] : 0;//Bonus client
			$liste[$matr][53] = $HNmaj40 ? $HNmaj40 : 0;//Heures normales majorées 40%
			$liste[$matr][54] = $HNmaj50 ? $HNmaj50 : 0;//Heures normales majorées 50%
			$liste[$matr][55] = $HNmaj100 ? $HNmaj100 : 0;//Heures normales majorées 100%
			$liste[$matr][56] = 0;//Indemnité de licenciement
			$liste[$matr][57] = 0;//fonds de fidélisation PHP
			$liste[$matr][58] = 0;//Préavis positif
			$liste[$matr][59] = $maj30 ? $maj30 : 0;//Maj 30%	
			$liste[$matr][60] = $maj50;//Maj 50%
			$liste[$matr][61] = 0;//regul indemnité
			$liste[$matr][62] = $table_prime[$matr]['RPR'] ? $table_prime[$matr]['RPR'] : 0;//Régul prime
			$liste[$matr][63] = 0;//régul  prime variable
			$liste[$matr][64] = 0;//Régul Heures normales majorées
			$liste[$matr][65] = 0;//Regul BONUS
			$liste[$matr][66] = 0;//REGUL MAJ NUIT
			$liste[$matr][67] = 0;//TOTAL
		}
		else 
		{
			$liste[$matr][1] = $bu[$k];//'bu'
			$liste[$matr][2] = $res['nom_dir'];//'direction'
			$liste[$matr][3] = $dept;//'dept'
			$liste[$matr][4] = $up;//'up'
			$liste[$matr][5] = $res['fonctioncourante'];//'fct'
			$liste[$matr][6] = $res['equipecourante'];//'eqp'
			$liste[$matr][7] = $res['matricule'];//'matricule'
			$liste[$matr][8] = utf8_encode($res['prenompersonnel']);//'prenom'
			$liste[$matr][9] = 1;//'effectif'
			$liste[$matr][10] = $res['niveaupers'];//'niveaupers'
			
			/*$embauche = date_create($res['tmp_embauche']);
			$liste[$matr][10] = date_format($embauche, 'd/m/Y');//'date_embauche'*/
			
			if($res["tmp_embauche"] == '' || $res["tmp_embauche"] == null || $res["tmp_embauche"] == '0000-00-00')
			{
				$liste[$matr][11] = '';
			}
			else 
			{
				$tmp_embauche = date_create($res["tmp_embauche"]);
				$liste[$matr][11] = date_format($tmp_embauche,'d/m/Y');
			}
			
			if($res["tmp_debauche"] == '' || $res["tmp_debauche"] == null || $res["tmp_debauche"] == '0000-00-00')
			{
				$liste[$matr][12] = '';
			}
			else 
			{
				$tmp_embauche = date_create($res["tmp_debauche"]);
				$liste[$matr][12] = date_format($tmp_embauche,'d/m/Y');
			}
			
			
			/*$debauche = date_create($res['tmp_debauche']);
			$liste[$matr][11] = date_format($debauche, 'd/m/Y');//'date_debauche'*/
			
			$liste[$matr][13] = $nb_trav_sb ? $nb_trav_sb : 0;//Nb jour trav SB
			$liste[$matr][14] = $nb_conge_alc ? $nb_conge_alc : 0;//Nb jour trav ALC
			$liste[$matr][15] = $nb_conge_maternite ? $nb_conge_maternite : 0;//Nb jour congé maternité
			$liste[$matr][16] = $nb_jour_travaille ? $nb_jour_travaille : 0;//Nb jours Travail
			$liste[$matr][17] = $nb_heure ? $nb_heure : 0;//Nb heure
			$liste[$matr][18] = $sb ? $sb : 0;//Salaire de base
			$liste[$matr][19] = $sb_mensuel;//Salaire de base mensuel
			$liste[$matr][20] = $demi_sal_maternite;//Demis-salaire maternité
			$liste[$matr][21] = $regularisation_sb;//Regularisation salaire de base
			$liste[$matr][22] = $montant_sb ? $montant_sb : 0;//MONTANT SB
			$liste[$matr][23] = $alloc_conge;//Allocation de congé
			$liste[$matr][24] = 0;//CONGES PAYES

			$liste[$matr][25] = $table_prime[$matr]['PRC'] ? $table_prime[$matr]['PRC'] : 0;//PRIME DE RECONNAISSANCE
			$liste[$matr][26] = 0;//Indemnité de transport
			$liste[$matr][27] = $table_prime[$matr]['PRP'] ? $table_prime[$matr]['PRP'] : 0;//Prime de performance
			$liste[$matr][28] = $table_prime[$matr]['PFI'] ? $table_prime[$matr]['PFI'] : 0;//Prime de fidélisation
			$liste[$matr][29] = 0;//Complément prime de fidélisation
			$liste[$matr][30] = $table_prime[$matr]['PRV'] ? $table_prime[$matr]['PRV'] : 0;//Prime variable
			$liste[$matr][31] = $table_prime[$matr]['PRO'] ? $table_prime[$matr]['PRO'] : 0;//Prime atteinte objectifs
			$liste[$matr][32] = $table_prime[$matr]['PRF'] ? $table_prime[$matr]['PRF'] : 0;//Prime de fonction
			$liste[$matr][33] = $nb_HS ? $nb_HS : 0;//Nb HS
			$liste[$matr][34] = $total_montant ? $total_montant : 0;//TOTAL HS
			$liste[$matr][35] = 0;//Autres
			$liste[$matr][36] = 0;//Vérification
			$liste[$matr][37] = 0;//Salaire brut prime de fidélisation
			$liste[$matr][38] = 0;//Vide
			$liste[$matr][39] = $sal_brute != '' ? $sal_brute : 0;//Salaire brut paie
			
			$liste[$matr][40] = 0;//Total salaire brut paie
			$liste[$matr][41] = 0;//Vide
			$liste[$matr][42] = 0;//Prime APM
			$liste[$matr][43] = $table_prime[$matr]['PAS'] ? $table_prime[$matr]['PAS'] : 0;//Prime assiduité
			$liste[$matr][44] = 0;//Prime spéciale centre de contact
			$liste[$matr][45] = $table_prime[$matr]['PSP'] ? $table_prime[$matr]['PSP'] : 0;//Prime spéciale
			$liste[$matr][46] = $table_prime[$matr]['PCA'] ? $table_prime[$matr]['PCA'] : 0;//Prime de caisse
			$liste[$matr][47] = $table_prime[$matr]['PEX'] ? $table_prime[$matr]['PEX'] : 0;//Prime exceptionnelle
			$liste[$matr][48] = 0;//Prime DEV PHP
			$liste[$matr][49] = $table_prime[$matr]['PAD'] ? $table_prime[$matr]['PAD'] : 0;//Prime spéciale ADLP
			$liste[$matr][50] = $table_prime[$matr]['PAP'] ? $table_prime[$matr]['PAP'] : 0;//Prime audio/prestation
			$liste[$matr][51] = 0;//Bonus VIVETIC
			$liste[$matr][52] = $table_prime[$matr]['BNC'] ? $table_prime[$matr]['BNC'] : 0;//Bonus client
			$liste[$matr][53] = $HNmaj40 ? $HNmaj40 : 0;//Heures normales majorées 40%
			$liste[$matr][54] = $HNmaj50 ? $HNmaj50 : 0;//Heures normales majorées 50%
			$liste[$matr][55] = $HNmaj100;//Heures normales majorées 100%
			$liste[$matr][56] = 0;//Indemnité de licenciement
			$liste[$matr][57] = 0;//fonds de fidélisation PHP
			$liste[$matr][58] = 0;//Préavis positif
			$liste[$matr][59] = $maj30 ? $maj30 : 0;//Maj 30%	
			$liste[$matr][60] = $maj50 ? $maj50 : 0;//Maj 50%
			$liste[$matr][61] = 0;//regul indemnité
			$liste[$matr][62] = $table_prime[$matr]['RPR'] ? $table_prime[$matr]['RPR'] : 0;//Régul prime
			$liste[$matr][63] = 0;//régul  prime variable
			$liste[$matr][64] = 0;//Régul Heures normales majorées
			$liste[$matr][65] = 0;//Regul BONUS
			$liste[$matr][66] = 0;//REGUL MAJ NUIT
			$liste[$matr][67] = 0;//TOTAL
		}

		
		/*if ($i < 100)
		{
			$i++;
		}
		else
		{
			break;
		}*/
		$i++;
	
	}

	return json_encode($bu).' || '.json_encode($rh).' | '.json_encode($bpo).' | '.json_encode($rc).' | '.json_encode($gc).' | '.json_encode($vide).' || '.json_encode($up_rh).' | '.json_encode($up_bpo).' | '.json_encode($up_rc).' | '.json_encode($up_gc).' | '.json_encode($up_).' || '.json_encode($matr_rh).' | '.json_encode($matr_bpo).' | '.json_encode($matr_rc).' | '.json_encode($matr_gc).' | '.json_encode($matr_).' || '.json_encode($codir).' || '.json_encode($liste);
}
//$a = index_('2013-10-21','2013-11-17');
//echo $a;exit;
?>