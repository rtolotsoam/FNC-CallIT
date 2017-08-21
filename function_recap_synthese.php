<?php
include("/var/www.cache/dgconn.inc");

if(isset($_REQUEST['champ_filtre_recap']))
{
	$id_client = isset($_REQUEST['id_client_filtre']) ? $_REQUEST['id_client_filtre'] : 0;
	$id_application = isset($_REQUEST['id_application_filtre']) ? $_REQUEST['id_application_filtre'] : 0;
	$id_projet = isset($_REQUEST['id_projet_filtre']) ? $_REQUEST['id_projet_filtre'] : 0;
	$id_tlc = isset($_REQUEST['id_tlc_filtre']) ? $_REQUEST['id_tlc_filtre'] : 0;
	$id_type_traitement = isset($_REQUEST['id_type_traitement_filtre']) ? $_REQUEST['id_type_traitement_filtre'] : 0;
	$id_fichier = isset($_REQUEST['id_fichier_filtre']) ? $_REQUEST['id_fichier_filtre'] : 0;
	$champ_filtre = isset($_REQUEST['champ_filtre_recap']) ? $_REQUEST['champ_filtre_recap'] : 0;
	if(($id_application == 0 || $id_application == '0') && $champ_filtre == 'code')
	{
		echo '0';
	}
	else
	{
		echo setFiltre($id_client,$id_application,$id_projet,$id_tlc,$id_type_traitement,$id_fichier,$champ_filtre);
	}
}

if(isset($_REQUEST['reinitialisation']))
{
	echo reinitialiserFiltre();
}


function fetchAllProject_recap($variable)
{
	global $conn;
	$sql = "select a.id_projet, a.nom_projet, a.id_client, c.nom_client, a.id_application, b.code, b.nom_application from cc_sr_projet a 
inner join gu_application b on a.id_application = b.id_application 
inner join gu_client c on c.id_client = a.id_client 
where a.archivage = 1 
order by a.date_modification";
	if($variable == 'client')
	{
		$str = "select id_client,nom_client from ( ".$sql." ) as one group by id_client,nom_client order by nom_client";
	}
	else if($variable == 'application')
	{
		$str = "select id_application, code, nom_application from ( ".$sql." ) as one group by id_application, code, nom_application order by code";
	}
	else 
	{
		$str = $sql;
	}
	$query  = pg_query($str) or die(pg_last_error());
    return $query;
}

function fetchAllTLC_recap()
{
	global $conn;
	$sql = "SELECT  matricule, prenompersonnel FROM personnel WHERE actifpers='Active' AND (fonctioncourante ='TC' or fonctioncourante ='CONSEILLER' or fonctioncourante ='FONC_MAIL') order by matricule ASC";
	$query  = pg_query($sql) or die(pg_last_error());
    return $query;
}

function fetchAllTLC_recap_()
{
	global $conn;
	$sql = "SELECT distinct p.matricule, p.prenompersonnel, p.fonctioncourante, p.actifpers 
	FROM cc_sr_notation n 
	inner join personnel p on n.matricule = p.matricule
	inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation
	WHERE 
	inot.id_grille_application is not null
	--fonctioncourante ='TC' 
	--AND p.actifpers='Active'
	order by matricule ASC"; 
	$query  = pg_query($sql) or die(pg_last_error());
    return $query;
}

function fetchAllTypeTraitement_recap()
{
	global $conn;
	$sql = "select * from cc_sr_type_traitement order by id_type_traitement";
	$query  = pg_query($sql) or die(pg_last_error());
    return $query;
}

function setFiltre($id_client,$id_application,$id_projet,$id_tlc,$id_type_traitement,$id_fichier,$champ_filtre)
{
	global $conn;
	if($champ_filtre == 'client')
	{
		$sql = "select id_application, code, nom_application from ( select a.id_projet, a.nom_projet, a.id_client, c.nom_client, a.id_application, b.code, 
		b.nom_application, a.archivage from cc_sr_projet a inner join gu_application b on a.id_application = b.id_application 
		inner join gu_client c on c.id_client = a.id_client 
		where a.id_client = ".$id_client." and a.archivage = 1 
		order by a.date_modification ) as one group by id_application, code, nom_application order by code";
		$query  = pg_query($sql) or die(pg_last_error());
		$str = "<option value='0'>-- Choix --</option>";
		while($res = pg_fetch_array($query))
		{
			$str .= "<option value='".$res['id_application']."'>".$res['code']." - ".$res['nom_application']."</option>";
		}
	}
	else if($champ_filtre == 'code')
	{
		$sql = "select a.id_projet, a.nom_projet, a.id_client, c.nom_client, a.id_application, b.code, 
		b.nom_application, a.archivage from cc_sr_projet a inner join gu_application b on a.id_application = b.id_application 
		inner join gu_client c on c.id_client = a.id_client 
		where a.id_application = ".$id_application." and a.archivage = 1 
		order by a.date_modification";
		$query  = pg_query($sql) or die(pg_last_error());
		$res = pg_fetch_array($query);
		$str = $res['id_client']."||".$res['id_projet'];
		
		$sql_typologie = "select * from cc_sr_typologie where id_projet = ".$res['id_projet'];
		$query  = pg_query($sql_typologie) or die(pg_last_error());
		if(pg_num_rows($query) > 0)
		{
			$str .= '||';//'||<option value=0>-- Choix --</option>';
			while($res = pg_fetch_array($query))
			{
				$str .= '<option value='.$res['id_typologie'].'>'.$res['libelle_typologie'].'</option>';
			}
		}
		else
		{
			$str .= '||0';
		}
	}
	return $str;
}

function fetchAllFichierInit_recap()
{
	global $conn;
	$sql = "select * from cc_sr_fichier ";
	$query  = pg_query($sql) or die(pg_last_error());
    return $query;
}

function reinitialiserFiltre()
{
	global $conn;
	
	// Nom Projet
	$str_projet = '<option value="0">-- Choix --</option>';
	$result_project = fetchAllProject_recap('projet');
	while ($res_projet = pg_fetch_array($result_project))
	{
		$str_projet .='<option value="'.$res_projet['id_projet'].'">'.$res_projet['nom_projet'].'</option>';
	}
	
	// Nom client
	$str_client = '<option value="0">-- Choix --</option>';
	$result_client = fetchAllProject_recap('client');
	while ($res_client = pg_fetch_array($result_client))
	{
		$str_client .= '<option value="'.$res_client['id_client'].'">'.$res_client['nom_client'].'</option>';
	}
	
	// Code Apllication
	$str_application = '<option value="0">-- Choix --</option>';
	$result_presta = fetchAllProject_recap('application');
	while ($res_presta = pg_fetch_array($result_presta))
	{
		$str_application .= '<option value="'.$res_presta['id_application'].'">'.$res_presta['code'].' - '.$res_presta['nom_application'].'</option>';
	}
	
	// Fichier
	$str_fichier = '<option value="0">-- Choix --</option>';
	$result_fichier = fetchAllFichierInit_recap();
	while ($res_fichier = pg_fetch_array($result_fichier))
	{
		$str_fichier .= '<option value="'.$res_fichier['id_fichier'].'">'.$res_fichier['nom_fichier'].'</option>';
	}
	
	// Type Traitement
	$str_type_traitement = '<option value="0">-- Choix --</option>';
	$result_type = fetchAllTypeTraitement_recap();
	while ($res_type = pg_fetch_array($result_type))
	{
		$str_type_traitement .= '<option value="'.$res_type['id_type_traitement'].'">'.$res_type['libelle_type_traitement'].'</option>';
	}
	
	// TLC
	$str_tlc = '';//'<option value="0">-- Choix --</option>';
	$result_tlc = fetchAllTLC_recap();
	while ($res_tlc = pg_fetch_array($result_tlc))
	{
		$str_tlc .= '<option value="'.$res_tlc['matricule'].'">'.$res_tlc['matricule'].' - '.$res_tlc['prenompersonnel'].'</option>';
	}
	
	// projet ||| client ||| application ||| type_traitement ||| fichier ||| tlc
	return $str_projet.'|||'.$str_client.'|||'.$str_application.'|||'.$str_type_traitement.'|||'.$str_fichier.'|||'.$str_tlc;
}

function fetchAllAuditeur_recap()
{
	global $conn;
	$sql = "SELECT 
        fonctioncourante, prenompersonnel, matricule 
    FROM 
        personnel 
    WHERE 
        actifpers='Active'  
        and \"pers_fictifMatricule\" <> 1  
        and fonctioncourante in ('SUP CC','RP','RESP PLATEAU','AQI','DQ','DCC','DCT','MANAGER') 
        and deptcourant in ('AE1','AE2','AE3','AE4','AE5','AS1','AS2','AS3','CC', 'DQ', 'DCC', 'DCT')
        or ( matricule in (6548,6568,5686,6211,5049,5066,5196,5377,7121,7122,628,10162, 9831, 9299,4928,9300,8624,8740,9441,9444,9970,10134,9902) and actifpers='Active' )
    ORDER BY 
        matricule ASC";
    $query  = pg_query($sql) or die(pg_last_error());
    return $query;
}

//add by 8120
function fetchAllAuditeur_recap_evaluateur(){
	global $conn;
	$sql = "select fonctioncourante, prenompersonnel, matricule from personnel p inner join cc_sr_droit d on d.matricule_droit = p.matricule where evaluation_droit=1 order by matricule";
	$query  = pg_query($sql) or die(pg_last_error());
    return $query;
}
//end 8120 adding

?>