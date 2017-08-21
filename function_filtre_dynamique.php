<?php
include("/var/www.cache/dgconn.inc");
/*if(isset($_REQUEST['id_projet_filtre']) || isset($_REQUEST['id_client_filtre']) || isset($_REQUEST['id_application_filtre']) || isset($_REQUEST['id_type_traitement_filtre']) || isset($_REQUEST['id_fichier_filtre']))
{
	$id_projet = isset($_REQUEST['id_projet_filtre']) ? $_REQUEST['id_projet_filtre'] : 0;
	$id_client = isset($_REQUEST['id_client_filtre']) ? $_REQUEST['id_client_filtre'] : 0;
	$id_application = isset($_REQUEST['id_application_filtre']) ? $_REQUEST['id_application_filtre'] : 0;
	$id_type_traitement = isset($_REQUEST['id_type_traitement_filtre']) ? $_REQUEST['id_type_traitement_filtre'] : 0;
	$id_fichier = isset($_REQUEST['id_fichier_filtre']) ? $_REQUEST['id_fichier_filtre'] : 0;
	echo setFiltre($id_projet,$id_client,$id_application,$id_type_traitement,$id_fichier);
}*/

if(isset($_REQUEST['id_client_filtre']) || isset($_REQUEST['id_application_filtre']) || isset($_REQUEST['id_projet_filtre']) || isset($_REQUEST['id_tlc_filtre']) || isset($_REQUEST['id_type_traitement_filtre']) || isset($_REQUEST['id_fichier_filtre']) || isset($_REQUEST['champ_filtre']))
{
	$id_client = isset($_REQUEST['id_client_filtre']) ? $_REQUEST['id_client_filtre'] : 0;
	$id_application = isset($_REQUEST['id_application_filtre']) ? $_REQUEST['id_application_filtre'] : 0;
	$id_projet = isset($_REQUEST['id_projet_filtre']) ? $_REQUEST['id_projet_filtre'] : 0;
	$id_tlc = isset($_REQUEST['id_tlc_filtre']) ? $_REQUEST['id_tlc_filtre'] : 0;
	$id_type_traitement = isset($_REQUEST['id_type_traitement_filtre']) ? $_REQUEST['id_type_traitement_filtre'] : 0;
	$id_fichier = isset($_REQUEST['id_fichier_filtre']) ? $_REQUEST['id_fichier_filtre'] : 0;
	$champ_filtre = isset($_REQUEST['champ_filtre']) ? $_REQUEST['champ_filtre'] : 0;
	echo setFiltre($id_client,$id_application,$id_projet,$id_tlc,$id_type_traitement,$id_fichier,$champ_filtre);
}

if(isset($_REQUEST['reinitialisation']))
{
	echo reinitialiserFiltre();
}

if(isset($_REQUEST['fichier']))
{
	$fichier = $_REQUEST['fichier'];
	//$idfichier = $_REQUEST['idfichier'];
	//echo insertNewFile($fichier,$idfichier);
	echo insertNewFile($fichier);
}

if(isset($_REQUEST['id_projet_auto']) || isset($_REQUEST['id_client_auto']) || isset($_REQUEST['id_application_auto']))
{
	$id_projet = $_REQUEST['id_projet_auto'];
	$id_client = $_REQUEST['id_client_auto'];
	$id_application = $_REQUEST['id_application_auto'];
	echo getAllFichier($id_projet,$client,$application);
}

function fetchAllTypeTraitement()
{
	global $conn;
	$sql = "select * from cc_sr_type_traitement order by id_type_traitement";
	$query  = pg_query($sql) or die(pg_last_error());
    return $query;
}

function fetchAllProject($variable)
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

function fetchAllTLC_old()
{
	global $conn;
    $sql = "SELECT  matricule, prenompersonnel FROM personnel WHERE actifpers='Active'  AND (fonctioncourante ='TC' or fonctioncourante = 'CONSEILLER' or fonctioncourante = 'FONC_MAIL' or fonctioncourante ='".utf8_decode('COMM SéD')."') OR matricule in (5779,5883)order by matricule ASC";
	$query  = pg_query($sql) or die(pg_last_error());
    return $query;
}

function fetchAllTLC()
{
	global $conn;
    $sql = " SELECT  distinct matricule, 
				prenompersonnel ,
				fonctioncourante,
				deptcourant
			FROM personnel 
			WHERE actifpers='Active'
				and \"pers_fictifMatricule\" <> 1
				and matricule not in (20)
			order by fonctioncourante, matricule ASC ";
	$query  = pg_query($sql) or die(pg_last_error());
    return $query;
}

function fetchAllFichier($id_projet,$id_client,$id_application)
{
	global $conn;
	$sql = "select a.id_fichier, b.nom_fichier, d.id_projet, d.id_client, d.id_application 
from cc_sr_notation a 
inner join cc_sr_fichier b on a.id_fichier = b.id_fichier 
left join cc_sr_indicateur_notation c on c.id_notation = a.id_notation
left join cc_sr_grille_application d on d.id_grille_application = c.id_grille_application
where d.id_projet = ".$id_projet." or d.id_client = ".$id_client." or d.id_application = ".$id_application."
group by a.id_fichier, b.nom_fichier, d.id_projet, d.id_client, d.id_application 
order by a.id_fichier";
	$query  = pg_query($sql) or die(pg_last_error());
    return $query;
}

function fetchAllFichierInit()
{
	global $conn;
	$sql = "select * from cc_sr_fichier ";
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
	}
	return $str;
}

function setFiltre_($id_projet,$id_client,$id_application,$id_type_traitement,$id_fichier)
{
	global $conn;
	
	$str = '';
	$sql = "select a.id_projet, a.nom_projet, a.id_client, c.nom_client, a.id_application, b.code, b.nom_application from cc_sr_projet a inner join gu_application b on a.id_application = b.id_application 
inner join gu_client c on c.id_client = a.id_client 
order by a.date_modification";
	if($id_projet != 0)
	{
		$str .= " and id_projet = ".$id_projet;
	}
	if($id_client != 0)
	{
		$str .= " and id_client = ".$id_client;
	}
	if($id_application != 0)
	{
		$str .= " and id_application = ".$id_application;
	}
	/*if($id_type_traitement != 0)
	{
		$str .= " and id_type_traitement = ".$id_type_traitement;
		$sql = "select a.id_projet, a.nom_projet, a.id_client, c.nom_client, a.id_application, 
b.code, b.nom_application, f.id_type_traitement, g.libelle_type_traitement
from cc_sr_projet a 
inner join gu_application b on a.id_application = b.id_application 
inner join gu_client c on c.id_client = a.id_client 
left join cc_sr_grille_application d on d.id_projet = a.id_projet
left join cc_sr_grille e on e.id_grille = d.id_grille 
left join cc_sr_categorie_grille f on f.id_categorie_grille = e.id_categorie_grille 
left join cc_sr_type_traitement g on g.id_type_traitement = f.id_type_traitement
group by a.id_projet, a.nom_projet, a.id_client, c.id_client, c.nom_client, a.id_application, 
b.id_application, b.code, b.nom_application, f.id_type_traitement,g.libelle_type_traitement
order by a.id_projet";
	}*/
	
	$sql_select = "select * from (".$sql.") as one where 1=1 ".$str;
	$query  = pg_query($sql_select) or die(pg_last_error());
	
	while($res = pg_fetch_array($query))
	{
		/*$table[$res['id_projet']][] = $res['nom_projet'];
		$table[$res['id_application']][] = $res['code'];
		$table[$res['id_client']][] = $res['nom_client'];
		$table[$res['id_type_traitement']][] = $res['libelle_type_traitement'];*/
		$table_projet[$res['id_projet']][] = $res['nom_projet'];
		//$table['id_projet'][] = $res['id_projet'];
		$table_application[$res['id_application']][] = $res['code'];
		//$table['id_application'][] = $res['id_application'];
		$table_client[$res['id_client']][] = $res['nom_client'];
		//$table['id_client'][] = $res['id_client'];
		//$table_typetraitement[$res['id_type_traitement']][] = $res['libelle_type_traitement'];
		//$table['id_type_traitement'][] = $res['id_type_traitement'];
	}
	//var_dump($table_projet);
	
	$tab_projet = array();
	$str_projet = '';
	if($id_projet == 0)
	{
		$str_projet .= "<option value='0'>-- Choix --</option>";
		foreach($table_projet as $key=>$val)
		{
			foreach($val as $_val)
			{
				if(!in_array($key,$tab_projet))
				{
					$str_projet .= "<option value='".$key."'>".$_val."</option>";
					array_push($tab_projet,$key);
				}
			}
		}
	}
	
	$tab_client = array();
	$str_client = '';
	if($id_client == 0)
	{
		$str_client .= "<option value='0'>-- Choix --</option>";
		foreach($table_client as $key=>$val)
		{
			foreach($val as $_val)
			{
				if(!in_array($key,$tab_client))
				{
					$str_client .= "<option value='".$key."'>".$_val."</option>";
					array_push($tab_client,$key);
				}
			}
		}
	}
	
	$tab_application = array();
	$str_application = '';
	if($id_application == 0)
	{
		$str_application .= "<option value='0'>-- Choix --</option>";
		foreach($table_application as $key=>$val)
		{
			foreach($val as $_val)
			{
				if(!in_array($key,$tab_application))
				{
					$str_application .= "<option value='".$key."'>".$_val."</option>";
					array_push($tab_application,$key);
				}
			}
		}
	}
	
	/*$str_type_traitement = '';
	if($id_type_traitement == 0)
	{
		$str_type_traitement .= "<option value='0'>-- Choix --</option>";
		foreach($table_type_traitement as $key=>$val)
		{
			foreach($val as $_val)
			{
				$str_type_traitement .= "<option value='".$key."'>".$_val."</option>";
			}
		}
	}*/
	
	if($id_type_traitement == 0)
	{
		// Type Traitement
		$str_type_traitement = '<option value="0">-- Choix --</option>';
		$result_type = fetchAllTypeTraitement();
		while ($res_type = pg_fetch_array($result_type))
		{
			$str_type_traitement .= '<option value="'.$res_type['id_type_traitement'].'">'.$res_type['libelle_type_traitement'].'</option>';
		}
	}
	
	if($id_fichier == 0)
	{
		$result_fichier = fetchAllFichier($id_projet,$id_client,$id_application);
		if(pg_num_rows($result_fichier) != 0)
		{
			$str_fichier = "<option value='0'>-- Choix --</option>";
			while($res_fichier = pg_fetch_array($result_fichier))
			{
				$str_fichier .= "<option value='".$res_fichier['id_fichier']."'>".$res_fichier['nom_fichier']."</option>";
			}
		}
	}
	// projet ||| client ||| application ||| type_traitement ||| fichier
	return $str_projet.'|||'.$str_client.'|||'.$str_application.'|||'.$str_type_traitement.'|||'.$str_fichier;
	
}

function reinitialiserFiltre()
{
	global $conn;
	
	// Nom Projet
	$str_projet = '<option value="0">-- Choix --</option>';
	$result_project = fetchAllProject('projet');
	while ($res_projet = pg_fetch_array($result_project))
	{
		$str_projet .='<option value="'.$res_projet['id_projet'].'">'.$res_projet['nom_projet'].'</option>';
	}
	
	// Nom client
	$str_client = '<option value="0">-- Choix --</option>';
	$result_client = fetchAllProject('client');
	while ($res_client = pg_fetch_array($result_client))
	{
		$str_client .= '<option value="'.$res_client['id_client'].'">'.$res_client['nom_client'].'</option>';
	}
	
	// Code Apllication
	$str_application = '<option value="0">-- Choix --</option>';
	$result_presta = fetchAllProject('application');
	while ($res_presta = pg_fetch_array($result_presta))
	{
		$str_application .= '<option value="'.$res_presta['id_application'].'">'.$res_presta['code'].' - '.$res_presta['nom_application'].'</option>';
	}
	
	// Fichier
	$str_fichier = '<option value="0">-- Choix --</option>';
	$result_fichier = fetchAllFichierInit();
	while ($res_fichier = pg_fetch_array($result_fichier))
	{
		$str_fichier .= '<option value="'.$res_fichier['id_fichier'].'">'.$res_fichier['nom_fichier'].'</option>';
	}
	
	// Type Traitement
	$str_type_traitement = '<option value="0">-- Choix --</option>';
	$result_type = fetchAllTypeTraitement();
	while ($res_type = pg_fetch_array($result_type))
	{
		$str_type_traitement .= '<option value="'.$res_type['id_type_traitement'].'">'.$res_type['libelle_type_traitement'].'</option>';
	}
	
	// TLC
	$str_tlc = '<option value="0">-- Choix --</option>';
	$result_tlc = fetchAllTLC();
	while ($res_tlc = pg_fetch_array($result_tlc))
	{
		$str_tlc .= '<option value="'.$res_tlc['matricule'].'">'.$res_tlc['matricule'].' - '.$res_tlc['prenompersonnel'].'</option>';
	}
	
	// projet ||| client ||| application ||| type_traitement ||| fichier
	return $str_projet.'|||'.$str_client.'|||'.$str_application.'|||'.$str_type_traitement.'|||'.$str_fichier.'|||'.$str_tlc;
}

//function insertNewFile($fichier,$idfichier)
function insertNewFile($fichier)
{
	global $conn;
	$fichier = trim($fichier);
	$sql_verif = "select * from cc_sr_fichier where nom_fichier = '".$fichier."'";
	$query  = pg_query($sql) or die(pg_last_error());
	if(pg_num_rows($query) == 0)
	{
		$sql = "insert into cc_sr_fichier (nom_fichier) values ('".$fichier."') returning id_fichier";
		$query  = pg_query($sql) or die(pg_last_error());
		$result = pg_fetch_array($query);
		return $result['id_fichier'];
	}
	else 
	{
		$result = pg_fetch_array($query);
		return $result['id_fichier'];
	}
}

function getAllFichier($id_projet,$client,$application)
{
	global $conn;
	if($id_projet != 0 || $id_client != 0 || $id_application != 0)
	{
		$where = " where d.id_projet = ".$id_projet." or d.id_client = ".$id_client." or d.id_application = ".$id_application." ";
	}
	else 
	{
		$where = "";
	}
	$sql = "select a.id_fichier, b.nom_fichier, d.id_projet, d.id_client, d.id_application 
from cc_sr_notation a 
inner join cc_sr_fichier b on a.id_fichier = b.id_fichier 
left join cc_sr_indicateur_notation c on c.id_notation = a.id_notation
left join cc_sr_grille_application d on d.id_grille_application = c.id_grille_application 
".$where." 
group by a.id_fichier, b.nom_fichier, d.id_projet, d.id_client, d.id_application 
order by a.id_fichier";
	$query  = pg_query($sql) or die(pg_last_error());
	
	$table = array();
	$tab = array();
	$nom_fichier = array();
	while($res = pg_fetch_array($query))
	{
		$table['label'] = $res['nom_fichier'];
		$table['actor'] = $res['id_fichier'];
		array_push($nom_fichier,$table['nom_fichier']);
		array_push($tab,$table);
		
		
	}
	return json_encode($tab);
	//return json_encode($nom_fichier);
}


?>