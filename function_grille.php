<?php
include ("/var/www.cache/dgconn.inc") ;
/*
$data_grille = $_REQUEST['data_grille'] ? $_REQUEST['data_grille'] : 0;
$id_projet = $_REQUEST['id_projet'] ? $_REQUEST['id_projet'] : 0;
$id_client = $_REQUEST['id_client'] ? $_REQUEST['id_client'] : 0;
$id_application = $_REQUEST['id_application'] ? $_REQUEST['id_application'] : 0;
$id_type = $_REQUEST['id_type'] ? $_REQUEST['id_type'] : 4;*/

$data_grille = $_REQUEST['data_grille'];
$id_projet = $_REQUEST['id_projet'];
$id_client = $_REQUEST['id_client'];
$id_application = $_REQUEST['id_application'];
$id_type = $_REQUEST['id_type'];

if($data_grille != 0 && $id_projet != 0 && $id_client != 0 && $id_application != 0 && $id_type != 1000)
{
	//echo 'function insert';
	insertCategorie($data_grille,$id_projet,$id_client,$id_application,$id_type);
}
if($data_grille == 'empty' && $id_projet != 0 && $id_client != 0 && $id_application != 0 && $id_type != 1000) 
{
	//echo 'tafiditra ato';
	deleteCategorie($id_projet,$id_client,$id_application,$id_type);
}

if($data_grille == 'empty')
{
	echo $data_grille.'**'.$id_projet.'**'.$id_application.'**'.$id_client.'**'.$id_type;
}

/****** Selection de tous les catégories avec leurs Items *******************/
function getCategorieGrille($id_categorie,$where,$id_projet,$id_client,$id_application)
{
	global $conn;
	/*$sql = "select cc_cg.id_type_traitement,cc_cg.id_categorie_grille,libelle_categorie_grille, id_grille,libelle_grille, cc_g.ordre from cc_sr_categorie_grille cc_cg 
inner join cc_sr_grille cc_g on cc_g.id_categorie_grille = cc_cg.id_categorie_grille 
where cc_cg.id_categorie_grille = ".$id_categorie."
order by cc_cg.id_type_traitement,cc_cg.id_categorie_grille,ordre";*/
	
	/*$sql = "select cc_ga.id_grille_application,cc_cg.id_type_traitement,cc_cg.id_categorie_grille,libelle_categorie_grille, cc_g.id_grille,cc_g.libelle_grille, cc_g.ordre,
cc_ga.id_projet,cc_ga.id_client, cc_ga.id_application 
from cc_sr_categorie_grille cc_cg 
inner join cc_sr_grille cc_g on cc_g.id_categorie_grille = cc_cg.id_categorie_grille 
left join cc_sr_grille_application cc_ga on cc_g.id_grille = cc_ga.id_grille
where cc_cg.".$where." = ".$id_categorie." 
and id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application."
order by cc_cg.id_type_traitement,cc_cg.id_categorie_grille,ordre";*/
	$sql = "select principal.id_type_traitement,principal.id_categorie_grille,principal.libelle_categorie_grille,principal.id_grille,
principal.libelle_grille,principal.ordre_cg,principal.ordre_g,id_projet,id_client,id_application  
from 
(select cc_cg.id_type_traitement,cc_cg.id_categorie_grille,libelle_categorie_grille, 
cc_g.id_grille,cc_g.libelle_grille, cc_cg.ordre as ordre_cg,cc_g.ordre as ordre_g
from cc_sr_categorie_grille cc_cg 
left join cc_sr_grille cc_g on cc_g.id_categorie_grille = cc_cg.id_categorie_grille 
order by cc_cg.id_type_traitement,cc_cg.id_categorie_grille,cc_cg.ordre,cc_g.ordre) as principal
left join
(select cc_g.id_grille,
cc_ga.id_projet,cc_ga.id_client, cc_ga.id_application
from cc_sr_categorie_grille cc_cg
inner join cc_sr_grille cc_g on cc_g.id_categorie_grille = cc_cg.id_categorie_grille
inner join cc_sr_grille_application cc_ga on cc_g.id_grille = cc_ga.id_grille  
where id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application." 
order by id_type_traitement, cc_cg.id_categorie_grille, cc_g.id_grille) as besoin 
on principal.id_grille = besoin.id_grille 
where ".$where." = ".$id_categorie." order by ordre_cg, ordre_g";
	//echo $sql; exit();
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}

function countGrille($id_categorie,$where,$id_projet,$id_client,$id_application)
{
	global $conn;
	$sql = "select count(id_grille) as nb_grille from (select principal.id_type_traitement,principal.id_categorie_grille,principal.libelle_categorie_grille,principal.id_grille,principal.libelle_grille,principal.ordre,id_projet,id_client,id_application  
from 
(select cc_cg.id_type_traitement,cc_cg.id_categorie_grille,libelle_categorie_grille, 
cc_g.id_grille,cc_g.libelle_grille, cc_g.ordre
from cc_sr_categorie_grille cc_cg 
left join cc_sr_grille cc_g on cc_g.id_categorie_grille = cc_cg.id_categorie_grille 
order by cc_cg.id_type_traitement,cc_cg.id_categorie_grille,ordre) as principal
left join
(select cc_g.id_grille,
cc_ga.id_projet,cc_ga.id_client, cc_ga.id_application
from cc_sr_categorie_grille cc_cg
inner join cc_sr_grille cc_g on cc_g.id_categorie_grille = cc_cg.id_categorie_grille
inner join cc_sr_grille_application cc_ga on cc_g.id_grille = cc_ga.id_grille  
where id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application." 
order by id_type_traitement, cc_cg.id_categorie_grille, cc_g.id_grille) as besoin 
on principal.id_grille = besoin.id_grille 
where ".$where." = ".$id_categorie.") as valeur_nombre where id_grille is not null";
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	$res = @pg_fetch_array($query);
	return $res['nb_grille'];
}

/******* Nombre de catégorie ****************/
function getCategorie($where, $param, $valeur)
{
	global $conn;
	$str = '';
	if($where == 1)
	{
		$str = ' where '.$param.' = '.$valeur;
	}
	$sql = "select * from cc_sr_categorie_grille ".$str." order by id_type_traitement,ordre";
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}

function countType()
{
	global $conn;
	$sql = "select count(id_type_traitement) as nb_type from cc_sr_type_traitement";
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	$result = pg_fetch_array($query);
	$nb = $result['nb_type'];
	return $nb;
}

/*****************************************/
function getTypeByProjetClient($id_projet,$id_client,$id_application)
{
	global $conn;
	$sql = "
select cct.id_type_traitement,cct.libelle_type_traitement,id_projet,nom_projet,id_application,nom_application,id_client,nom_client,date_max from cc_sr_type_traitement cct
left join
(
select id_type_traitement,libelle_type_traitement,id_projet, nom_projet,id_application, nom_application,id_client, nom_client,max(date_entretien) as date_max from (
select cc_tt.id_type_traitement, libelle_type_traitement, libelle_categorie_grille, libelle_grille, date_entretien, date_notation,cc_p.id_projet, nom_projet, code,cc_p.id_application, nom_application,cc_p.id_client, nom_client 
from cc_sr_categorie_grille cc_cg
right join cc_sr_type_traitement cc_tt on cc_tt.id_type_traitement = cc_cg.id_type_traitement 
inner join cc_sr_grille cc_g on cc_g.id_categorie_grille = cc_cg.id_categorie_grille
inner join cc_sr_indicateur_notation cc_in on cc_in.id_grille = cc_g.id_grille 
inner join cc_sr_notation cc_n on cc_n.id_notation = cc_in.id_notation 
inner join cc_sr_projet cc_p on cc_p.id_projet = cc_n.id_projet
inner join gu_application gu_a on gu_a.id_application = cc_p.id_application
inner join gu_client gu_c on gu_c.id_client = cc_p.id_client
order by cc_tt.id_type_traitement
) as principal 
where id_projet= ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application."
group by id_type_traitement,libelle_type_traitement,id_projet, nom_projet,id_application, nom_application,id_client, nom_client 
order by libelle_type_traitement
) as type 
on type.id_type_traitement = cct.id_type_traitement 
order by cct.id_type_traitement

";
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}

/********** Résumé des catégories affectés au projet / Client/ Prestation ************/
function getResumeCategorie($str, $type)
{
	global $conn;
	if($str != '')
	{
		$suite = " id_grille in (".$str.") and";
	}
	else 
	{
		$suite = " id_grille in (0) and";
	}
	$sql = "select a.id_categorie_grille, a.libelle_categorie_grille, a.id_type_traitement,a.ordre ordre_categorie_grille, b.id_grille, b.libelle_grille, b.ordre ordre_grille
from cc_sr_categorie_grille a 
inner join cc_sr_grille b 
on a.id_categorie_grille = b.id_categorie_grille 
where ".$suite." a.id_type_traitement = ".$type."
order by a.id_type_traitement, a.ordre, b.ordre";
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}

/************* Nombre de categorie selectionnés par type de traitement *********/
function countCategorie($str, $type, $where, $distinct)
{
	global $conn;
	if($str != '')
	{
		$suite = " id_grille in (".$str.") and";
	}
	else 
	{
		$suite = " id_grille in (0) and";
	}
	$sql = "select count(".$distinct." a.id_categorie_grille) as nombre
from cc_sr_categorie_grille a 
inner join cc_sr_grille b 
on a.id_categorie_grille = b.id_categorie_grille 
where ".$suite." a.".$where." = ".$type;
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	$result = @pg_fetch_array($query);
	return $result[0];
}

/********************* Nombre de grille par categorie *******************************/
function countGrilleByCategorie($id_categorie)
{
	global $conn;
	$sql = "select count(a.id_grille) as nombre from cc_sr_grille a
inner join cc_sr_categorie_grille b on a.id_categorie_grille = b.id_categorie_grille 
where b.id_categorie_grille = ".$id_categorie;
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	$result = @pg_fetch_array($query);
	return $result[0];
}

/**
 * ********* Insérer les grilles corespondants aux projet/client/application ************
 */
function insertCategorie($data_grille,$id_projet,$id_client,$id_application,$id_type)
{
	global $conn;
	//$tab = array();
	$tab1 = array();
	$tab2 = array();
	$list = array();
	$str = " ";
	$date_modif = date('Y-m-d');

	$liste = explode(',',$data_grille);
	for($i=0;$i<count($liste);$i++)
	{
		$_id = explode('_',$liste[$i]);
		if ($i != 0) $str .= ',';
		$str .= $_id[0];
	}
	
	$tab1 = requeteSelect($str,$id_projet,$id_client,$id_application,0);
	
	//$date_modif = date('Y-m-d');
	//Soit Insérer les données sélectionnées qui ne sont pas encore affectées au projet
	for($k=0;$k<count($liste);$k++)
	{
		$id = explode('_',$liste[$k]); // $liste[$k] =>    idgrille_idtype
		if($id[1] ==1) $champ = 'modif_appel_entrant';
		if($id[1] ==2) $champ = 'modif_appel_sortant';
		if($id[1] ==3) $champ = 'modif_mail';
		array_push($list,$id[0]); //Prendre liste des id_grille selectionné
		//$tab[$id[0]] = $id[1]; //$tab[id_grille] = id_type
			
		if(!in_array($id[0],$tab1)) 
		{	echo 'ato';
			$query = pg_query($conn,"INSERT INTO cc_sr_grille_application(id_grille,id_application,id_projet,id_client) VALUES (".$id[0].",".$id_application.",".$id_projet.",".$id_client.")") or die (pg_last_error($conn));
			
			$query = pg_query($conn,"UPDATE cc_sr_projet SET ".$champ." = '".$date_modif."' WHERE id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application) or die (pg_last_error($conn));
		}
	}

	$tab2 = requeteSelect(0,$id_projet,$id_client,$id_application,$id_type);
	//Soit Supprimer les données déjà affectées mais dé-selectionnées 
	for($l=0;$l<count($tab2);$l++)
	{
		if(!in_array($tab2[$l],$list) || empty($list))
		{
			$query_ = pg_query($conn,"select ga.id_projet, ga.id_application, ga.id_client, ga.id_grille, cg.id_categorie_grille, cg.id_type_traitement from cc_sr_grille_application ga 
inner join cc_sr_grille g on g.id_grille = ga.id_grille 
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille 
where ga.id_grille = ".$tab2[$l]." and ga.id_projet = ".$id_projet." and ga.id_application = ".$id_application." and ga.id_client = ".$id_client." order by cg.id_type_traitement");
			$result = pg_fetch_array($query_);
			$id_type_traitement = $result['id_type_traitement'];
			if($id_type_traitement ==1) $champ_ = 'modif_appel_entrant';
			if($id_type_traitement ==2) $champ_ = 'modif_appel_sortant';
			if($id_type_traitement ==3) $champ_ = 'modif_mail';
			
			$query = pg_query($conn,"DELETE FROM cc_sr_grille_application WHERE id_grille = ".$tab2[$l]." and id_application = ".$id_application." and id_projet = ".$id_projet." and id_client = ".$id_client) or die (pg_last_error($conn));
			
			$query = pg_query($conn,"UPDATE cc_sr_projet SET ".$champ_." = '".$date_modif."' WHERE id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application) or die (pg_last_error($conn));
		}
	}
	
	
}

function requeteSelect($data_grille,$id_projet,$id_client,$id_application,$id_type)
{
	global $conn;
	$tab = array();
	if($data_grille != 0 && $data_grille != '')
	{
		$grille = "id_grille in (".$data_grille.") and ";
	}
	if($id_type != 0)
	{
		$inner = " inner join cc_sr_grille g on g.id_grille = ga.id_grille inner join cc_sr_categorie_grille cg on g.id_categorie_grille = cg.id_categorie_grille ";
		$and = " and cg.id_type_traitement = ".$id_type;
	}
	else 
	{
		$inner = " ";
		$and = " ";
	}
	$sql = "select ga.id_grille from cc_sr_grille_application ga ".$inner."
	where ".$grille." id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application." ".$and;
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	if($query)
	{
		while ($res = pg_fetch_array($query))
		{
			array_push($tab,$res['id_grille']);
		}
	} 
	return $tab;
}

function deleteCategorie($id_projet,$id_client,$id_application,$id_type)
{
	global $conn;
	$date_modif = date('Y-m-d');
	if($id_type != 0)
	{
		if($id_type ==1) $champ = 'modif_appel_entrant';
		if($id_type ==2) $champ = 'modif_appel_sortant';
		if($id_type ==3) $champ = 'modif_mail';
		$sql = "select id_grille_application from cc_sr_grille_application ga 
inner join cc_sr_grille g on g.id_grille = ga.id_grille 
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille 
where id_application = ".$id_application." and id_projet = ".$id_projet." and id_client = ".$id_client." 
and id_type_traitement = ".$id_type;
		$query = pg_query($conn,$sql) or die (pg_last_error($conn));
		if(pg_num_rows($query) != 0)
		{
			$query = pg_query($conn,"delete from cc_sr_grille_application where id_grille_application in (".$sql.")")  or die (pg_last_error($conn));
			
			$query = pg_query($conn,"UPDATE cc_sr_projet SET ".$champ." = '".$date_modif."' WHERE id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application) or die (pg_last_error($conn));
		}
	}
	elseif($id_type == 0) 
	{
		$sql = "select id_grille_application from cc_sr_grille_application ga 
inner join cc_sr_grille g on g.id_grille = ga.id_grille 
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille 
where id_application = ".$id_application." and id_projet = ".$id_projet." and id_client = ".$id_client;
		$query = pg_query($conn,$sql) or die (pg_last_error($conn));
		if(pg_num_rows($query) != 0)
		{
			$query = pg_query($conn,"delete from cc_sr_grille_application where id_grille_application in (".$sql.")")  or die (pg_last_error($conn));
			$query_ = pg_query($conn,"select id_grille from cc_sr_grille_application ga 
inner join cc_sr_grille g on g.id_grille = ga.id_grille 
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille 
where id_application = ".$id_application." and id_projet = ".$id_projet." and id_client = ".$id_client);
			while ($res = pg_fetch_array($query_))
			{
				$query_ = pg_query($conn,"select ga.id_projet, ga.id_application, ga.id_client, ga.id_grille, cg.id_categorie_grille, cg.id_type_traitement from cc_sr_grille_application ga 
inner join cc_sr_grille g on g.id_grille = ga.id_grille 
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille 
where ga.id_grille = ".$res['id_grille']." and ga.id_projet = ".$id_projet." and ga.id_application = ".$id_application." and ga.id_client = ".$id_client." order by cg.id_type_traitement");
				$result = pg_fetch_array($query_);
				$id_type_traitement = $result['id_type_traitement'];
				if($id_type_traitement ==1) $champ_ = 'modif_appel_entrant';
				if($id_type_traitement ==2) $champ_ = 'modif_appel_sortant';
				if($id_type_traitement ==3) $champ_ = 'modif_mail';
				
				$query = pg_query($conn,"UPDATE cc_sr_projet SET ".$champ_." = '".$date_modif."' WHERE id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application) or die (pg_last_error($conn));
			}
		}
	}
}
/**************************************************************************************/

?>