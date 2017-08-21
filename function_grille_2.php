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
$str_elimin3 = $_REQUEST['str_elimin3'];
$str_ponderation3 = $_REQUEST['str_ponderation3'];
$str_flag_is = $_REQUEST['str_flag_is'];
$str_repartition = $_REQUEST['str_repartition'];

$str_elimin3 = explode(",",$str_elimin3);
$str_ponderation3 = explode(",",$str_ponderation3);
$str_flag_is = explode(",",$str_flag_is);
$str_repartition = explode(",",$str_repartition);
   // print'<pre>';
   // print_r($str_repartition);
   // print'</pre>';





if($data_grille != 0 && $id_projet != 0 && $id_client != 0 && $id_application != 0 && $id_type != 1000)
{
	//echo 'function insert';
	insertCategorie($data_grille,$id_projet,$id_client,$id_application,$id_type,$str_elimin3,$str_ponderation3,$str_flag_is,$str_repartition);
}
if($data_grille == 'empty' && $id_projet != 0 && $id_client != 0 && $id_application != 0 && $id_type != 1000) 
{
	deleteCategorie($id_projet,$id_client,$id_application,$id_type);
}
/*
if($data_grille == 'empty')
{
	echo $data_grille.'**'.$id_projet.'**'.$id_application.'**'.$id_client.'**'.$id_type;
}*/

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
principal.libelle_grille,principal.ordre_cg,principal.ordre_g,id_projet,id_client,id_application, besoin.id_grille_application 
from 
(select cc_cg.id_type_traitement,cc_cg.id_categorie_grille,libelle_categorie_grille, 
cc_g.id_grille,cc_g.libelle_grille, cc_cg.ordre as ordre_cg,cc_g.ordre as ordre_g
from cc_sr_categorie_grille cc_cg 
left join cc_sr_grille cc_g on cc_g.id_categorie_grille = cc_cg.id_categorie_grille 
order by cc_cg.id_type_traitement,cc_cg.id_categorie_grille,cc_cg.ordre,cc_g.ordre) as principal
left join
(select cc_g.id_grille,
cc_ga.id_projet,cc_ga.id_client, cc_ga.id_application, cc_ga.id_grille_application
from cc_sr_categorie_grille cc_cg
inner join cc_sr_grille cc_g on cc_g.id_categorie_grille = cc_cg.id_categorie_grille
inner join cc_sr_grille_application cc_ga on cc_g.id_grille = cc_ga.id_grille  
where id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application." 
order by id_type_traitement, cc_cg.id_categorie_grille, cc_g.id_grille) as besoin 
on principal.id_grille = besoin.id_grille 
where ".$where." = ".$id_categorie." order by ordre_cg, ordre_g";
	
	// echo "sql => ".$sql."<br/>";
	
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}

function countGrille($id_categorie,$where,$id_projet,$id_client,$id_application)
{
	global $conn;
	$sql = "select count(id_grille) as nb_grille, count(id_projet) as nb_projet from (select principal.id_type_traitement,principal.id_categorie_grille,principal.libelle_categorie_grille,principal.id_grille,principal.libelle_grille,principal.ordre,id_projet,id_client,id_application  
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
	return $res;
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
	$nb     = $result['nb_type'];
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
function insertCategorie($data_grille,$id_projet,$id_client,$id_application,$id_type,$str_elimin3,$str_ponderation3,$str_flag_is,$str_repartition)
{
     
	global $conn;

	
	  
	
	
	$array_grille_pond = array();
	 for($k_=0;$k_<count( $str_ponderation3 );$k_++)
	 {
	     $_pond = explode('#',$str_ponderation3[$k_]);
		 $array_grille_pond[$_pond[0]]= $_pond[1];		 
	 }
        
    
	$array_grille_flag_is = array();
     for($x_=0;$x_<count( $str_flag_is );$x_++)
	 {
	     $_flag = explode('#',$str_flag_is[$x_]);
		 $array_grille_flag_is[$_flag[0]]= $_flag[1];		 
	 }
	/**********Repartition************/
	$array_grille_repartition = array();
     for($y_=0;$y_<count( $str_repartition );$y_++)
	 {
	     $_flag = explode('#',$str_repartition[$y_]);
		 $array_grille_repartition[$_flag[0]]= $_flag[1];		 
	 }
	 
	/**********************/
	    // print '<pre>';
	    // print_r($array_grille_repartition);
	    // print '</pre>';
	
	$tab1 = array();
	$tab2 = array();
	$list = array();
	$str = " ";
	$date_modif = date('Y-m-d');
	$flag_elimin = 0;

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
			
		          if(in_array($id[0],$str_elimin3))
					{
					   $flag_elimin = 1;
					}else{
					   $flag_elimin = 0;
					}	
			
			
			  $_ponderation = $array_grille_pond[$id[0]];
			  $_flag_is = $array_grille_flag_is[$id[0]];
			  $_repartition = $array_grille_repartition[$id[0]];
					if($_ponderation =='NaN' || $_ponderation==0){
					   $_ponderation =1;
					}
		if(!in_array($id[0],$tab1)) 
		{	
		
			
			     
			
			$query = pg_query($conn,"INSERT INTO cc_sr_grille_application(id_grille,id_application,id_projet,id_client,flag_eliminatoire,ponderation,flag_is,id_repartition) VALUES (".$id[0].",".$id_application.",".$id_projet.",".$id_client.",".$flag_elimin.",".$_ponderation.",'".$_flag_is."','".$_repartition."')") or die (pg_last_error($conn));
			$sql_update_projet = "UPDATE cc_sr_projet SET flag_duplication=".$id_projet." , ".$champ." = '".$date_modif."' WHERE id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application;
			
			$query = pg_query($conn,$sql_update_projet) or die (pg_last_error($conn));
		}
		else 
		{
			
		
			//echo "flag_notation = ".$flag." WHERE id_grille = ".$id[0].",id_application = ".$id_application.",id_projet = ".$id_projet.",id_client = ".$id_client.'</br>';
			
			       
					$sql_update = "UPDATE cc_sr_grille_application SET flag_eliminatoire=".$flag_elimin.",ponderation=".$_ponderation .",flag_is='".$_flag_is ."',id_repartition='".$_repartition ."' WHERE id_grille = ".$id[0]." and id_application = ".$id_application." and id_projet = ".$id_projet." and id_client = ".$id_client;
		
			$query = pg_query($conn,$sql_update);
			
			$sql_update_projet = "UPDATE cc_sr_projet SET flag_duplication=".$id_projet.",  ".$champ." = '".$date_modif."' WHERE id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application;
		
		
			$query = pg_query($conn,$sql_update_projet) or die (pg_last_error($conn));
		}
	}

	$tab2 = requeteSelect(0,$id_projet,$id_client,$id_application,$id_type);
	
	//Soit Supprimer les données déjà affectées mais dé-selectionnées 
	for($l=0;$l<count($tab2);$l++)
	{
		if(!in_array($tab2[$l],$list) || empty($list))
		{
		      
		    $sql_ = "select ga.id_projet, ga.id_application, ga.id_client, ga.id_grille, cg.id_categorie_grille, cg.id_type_traitement from cc_sr_grille_application ga 
inner join cc_sr_grille g on g.id_grille = ga.id_grille 
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille 
where ga.id_grille = ".$tab2[$l]." and ga.id_projet = ".$id_projet." and ga.id_application = ".$id_application." and ga.id_client = ".$id_client." order by cg.id_type_traitement";

			$query_ = pg_query($conn,$sql_);
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
/**************************************************************************************/
/**************************************************************************************/
/******* reprise des categories sélectionnées avec les grilles correspondants ****************/
function getCategorieGrille_notation($where, $param, $valeur, $where1, $param1, $valeur1, $liste,$id_projet,$id_client,$id_application)
{
	
	
	

	global $conn;
	
	$str = '';
	$str1 = '';
	if($where == 1)
	{
		$str = ' AND '.$param.' = '.$valeur;
	}
	if($where1 == 1)
	{
		$str1 = ' AND '.$param1.' = '.$valeur1;
	}
	if($liste == '')
	{
		$list_grille = 0;
	}
	else 
	{
		$list_grille = $liste;
	}
	
	$sql_test = "select * from cc_sr_grille_application where id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application;
	$query_test = pg_query($conn,$sql_test) or die (pg_last_error($conn));
	
	
	
	// if(pg_num_rows($query_test) == 0)
	// {
		// $sql = "select a.id_categorie_grille, a.libelle_categorie_grille, a.id_type_traitement,
// b.id_grille, b.libelle_grille , 0::integer as flag_eliminatoire,0::integer as ponderation,''::character varying as flag_is 
// from cc_sr_categorie_grille a 
// inner join cc_sr_grille b on a.id_categorie_grille = b.id_categorie_grille 
// where 1=1 ";
 		// $sql .=  " AND b.id_grille in (".$list_grille.") ".$str." ".$str1." order by a.ordre, b.ordre";
	// }
	// else 
	// {
		// $sql = 'select a.id_categorie_grille, a.libelle_categorie_grille, a.id_type_traitement,
		// b.id_grille, b.libelle_grille ,c.flag_eliminatoire,c.ponderation,c.flag_is
		// from cc_sr_categorie_grille a 
		// inner join cc_sr_grille b on a.id_categorie_grille = b.id_categorie_grille
		// left join cc_sr_grille_application c on c.id_grille=b.id_grille
		// where 1=1 ';
		// $sql .= 'AND c.id_projet= '.$id_projet.' AND c.id_client= '.$id_client.'  AND c.id_application= '.$id_application.' ';
		// $sql .=  ' AND b.id_grille in ('.$list_grille.') '.$str.' '.$str1.' order by a.ordre, b.ordre';
	// }
	
	$sql_ = "select one.id_categorie_grille, one.libelle_categorie_grille, one.id_type_traitement,
one.id_grille, one.libelle_grille,c.flag_eliminatoire,c.ponderation,c.flag_is ,c.id_repartition 
from
(
select a.id_categorie_grille, a.libelle_categorie_grille, a.id_type_traitement,
b.id_grille, b.libelle_grille ,a.ordre as ordre1,b.ordre as ordre2
from cc_sr_categorie_grille a 
inner join cc_sr_grille b on a.id_categorie_grille = b.id_categorie_grille 
where 1=1 
AND b.id_grille in (".$list_grille.") ".$str." ".$str1."
) as one ";
$sql_  .= "left join 
cc_sr_grille_application c on c.id_grille=one.id_grille   ";
$sql_ .= " AND c.id_projet= ".$id_projet." AND c.id_client= ".$id_client."  AND c.id_application= ".$id_application." ";
$sql_ .= " ORDER BY one.ordre1,one.ordre2";
	
	
	//echo $sql; exit;
	$query = pg_query($conn,$sql_) or die (pg_last_error($conn));
	return $query;
}
/********** Liste categorie séléctionnées *****************/
function getCategorie_notation($where,$param, $valeur,$liste)
{
	global $conn;
	$str = '';
	if($where == 1)
	{
		$str = ' and '.$param.' = '.$valeur;
	}
	if($liste == '')
	{
		$list_grille = 0;
	}
	else 
	{
		$list_grille = $liste;
	}
	
	$sql = 'select distinct a.id_categorie_grille, a.libelle_categorie_grille, a.id_type_traitement, a.ordre
from cc_sr_categorie_grille a 
inner join cc_sr_grille b on a.id_categorie_grille = b.id_categorie_grille
where b.id_grille in ('.$list_grille.') '.$str.' order by a.ordre';
	//echo $sql; exit();
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}

/********************* valeur initial pour chaque combobox ***************/
function getValeurSelect_notation($id_grille, $id_projet, $id_application, $id_client)
{
	global $conn;
	$sql = 'select flag_notation from cc_sr_grille_application where id_grille = '.$id_grille.' and id_projet = '.$id_projet.' and id_application = '.$id_application.' and id_client = '.$id_client;
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	$result = pg_fetch_array($query);
	return $result['flag_notation'];
}
/*************************Calcul des moyennes******************************/
    function get_everage($id_categorie_grille,$id_type_traitement){
	   global $conn;
	
	
	}
	
	function getCategorieGrilleDesc($id_categorie,$where,$id_projet,$id_client,$id_application)
{
	global $conn;
	$sql = "select principal.id_type_traitement,principal.id_categorie_grille,principal.libelle_categorie_grille,principal.id_grille,
principal.libelle_grille,principal.ordre_cg,principal.ordre_g,id_projet,id_client,id_application, besoin.id_grille_application 
from 
(select cc_cg.id_type_traitement,cc_cg.id_categorie_grille,libelle_categorie_grille, 
cc_g.id_grille,cc_g.libelle_grille, cc_cg.ordre as ordre_cg,cc_g.ordre as ordre_g
from cc_sr_categorie_grille cc_cg 
left join cc_sr_grille cc_g on cc_g.id_categorie_grille = cc_cg.id_categorie_grille 
order by cc_cg.id_type_traitement,cc_cg.id_categorie_grille,cc_cg.ordre,cc_g.ordre) as principal
inner join
(select cc_g.id_grille,
cc_ga.id_projet,cc_ga.id_client, cc_ga.id_application, cc_ga.id_grille_application
from cc_sr_categorie_grille cc_cg
inner join cc_sr_grille cc_g on cc_g.id_categorie_grille = cc_cg.id_categorie_grille
inner join cc_sr_grille_application cc_ga on cc_g.id_grille = cc_ga.id_grille  
where id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application." 
order by id_type_traitement, cc_cg.id_categorie_grille, cc_g.id_grille) as besoin 
on principal.id_grille = besoin.id_grille 
where ".$where." = ".$id_categorie." order by ordre_cg, ordre_g";

	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}
	
	function getGrilleDescription( $id_grille_application ){
	     global $conn;
		 $sql = "SELECT * FROM cc_sr_grille_description gd ";
		 $sql .= "LEFT JOIN cc_sr_grille_application ga ON ga.id_grille_application=gd.id_grille_application ";
		 $sql .= "LEFT JOIN cc_sr_grille g ON g.id_grille=ga.id_grille ";
		 $sql .= "WHERE ga.id_grille_application = {$id_grille_application}";
		 //$sql .= "AND ga.id_grille = {$id_grille}";
		 $sql .= " ORDER BY note DESC";
		  // ECHO $sql.'<br>';
		 $query = pg_query($conn,$sql) or die (pg_last_error($conn));
		 
		 return $query;

	}
	
	
	function getCountDescription(  $id_grille_application )
	{
	      global $conn;
	     $sql ="SELECT count(*) FROM cc_sr_grille_description gd 
				LEFT JOIN cc_sr_grille_application ga ON 
				ga.id_grille_application=gd.id_grille_application 
				LEFT JOIN cc_sr_grille g ON g.id_grille=ga.id_grille 
				WHERE ga.id_grille_application = {$id_grille_application} ";
		
				$result = pg_query($conn,$sql) or die (pg_last_error($conn));
		        $count = pg_num_rows($result);
		        return $count;
	   }
	   
	   function get_repartition(){
	     global $conn;
		 $array_repartition = array();
		 $sql_repartition = "SELECT id_repartition,libelle_repartition FROM cc_sr_repartition ORDER BY libelle_repartition";
		
	    $query_repartition = pg_query( $conn , $sql_repartition ) or die (pg_last_error( $conn ));
		
		   for($i=0;$i<pg_num_rows( $query_repartition  );$i++){
		            
		        $rows = pg_fetch_array(  $query_repartition , $i  );
			
				$array_repartition[$rows['id_repartition']] =  $rows['libelle_repartition'];
		   }
		     return  $array_repartition;

	    }
?>