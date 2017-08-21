<?php
include("/var/www.cache/dgconn.inc");

if(isset($_REQUEST['donnees']))
{
	$id_projet = $_REQUEST['id_projet'];
	$id_client = $_REQUEST['id_client'];
	$id_application = $_REQUEST['id_application'];
	$id_type_traitement = $_REQUEST['id_type_traitement'];
}

function fetchAllTLCClient($id_projet,$id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc,$id_type_appel)
{
	global $conn;
	/*$sql = "select distinct matricule from(
select distinct  n.id_notation,n.matricule,n.matricule_notation,n.date_notation,ga.id_projet,ga.id_client,ga.id_application,cg.id_type_traitement
from cc_sr_notation n
inner join cc_sr_grille_application ga on ga.id_projet=n.id_projet
inner join cc_sr_grille g on g.id_grille=ga.id_grille
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
where ga.id_client=".$id_client." and ga.id_application=".$id_application."
and cg.id_type_traitement=".$id_type_traitement."
order by n.matricule
) as req order by matricule";*/
$str = '';
if($date_deb_notation != '')
{
	//$str .= " and n.date_notation >= '".$date_deb_notation."' ";
	$str .= " and n.date_entretien >= '".$date_deb_notation."' ";
}
if($date_fin_notation != '')
{
	//$str .= " and n.date_notation <= '".$date_fin_notation."' ";
	$str .= " and n.date_entretien <= '".$date_fin_notation."' ";
}
if($matricule_auditeur != 0)
{
	$str .= " and n.matricule_notation = ".$matricule_auditeur." ";
}
if($matricule_tlc != 0)
{
	$str .= " and n.matricule = ".$matricule_tlc." ";
}
if($id_type_appel != 0)
{
	$str .= " and n.id_typologie = ".$id_type_appel." ";
}

	$sql = "select distinct matricule from(
select distinct  n.id_notation,n.matricule,n.matricule_notation,n.date_notation,
ga.id_projet,ga.id_client,ga.id_application,cg.id_type_traitement,g.id_grille,cg.id_categorie_grille
from cc_sr_notation n
inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation
inner join cc_sr_grille_application ga on ga.id_grille_application= inot.id_grille_application
inner join cc_sr_grille g on g.id_grille=ga.id_grille
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
where ga.id_client=".$id_client." and ga.id_application=".$id_application."
and cg.id_type_traitement=".$id_type_traitement." ".$str."
order by n.matricule
) as req";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function fetchAllTLCNotation($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc,$id_type_appel)
{
	global $conn;
	
	$str = '';
	if($date_deb_notation != '')
	{
		//$str .= " and n.date_notation >= '".$date_deb_notation."' ";
		$str .= " and n.date_entretien >= '".$date_deb_notation."' ";
	}
	if($date_fin_notation != '')
	{
		//$str .= " and n.date_notation <= '".$date_fin_notation."' ";
		$str .= " and n.date_entretien <= '".$date_fin_notation."' ";
	}
	if($matricule_auditeur != 0)
	{
		$str .= " and n.matricule_notation = ".$matricule_auditeur." ";
	}
	if($matricule_tlc != 0)
	{
		$str .= " and n.matricule = ".$matricule_tlc." ";
	}
	if($id_type_appel != 0)
	{
		$str .= " and n.id_typologie = ".$id_type_appel." ";
	}
	if($id_client != 0 && $id_application != 0)
	{
		$str .= " and ga.id_client=".$id_client." and ga.id_application=".$id_application." ";
	}
	
	$sql = "select distinct matricule ,id_notation, id_projet, id_client, id_application, note from(
select distinct  n.id_notation,n.matricule,n.matricule_notation,n.date_notation,n.note,
ga.id_projet,ga.id_client,ga.id_application,cg.id_type_traitement,g.id_grille,cg.id_categorie_grille
from cc_sr_notation n
inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation
inner join cc_sr_grille_application ga on ga.id_grille_application= inot.id_grille_application
inner join cc_sr_grille g on g.id_grille=ga.id_grille
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
where 1=1 and 
cg.id_type_traitement=".$id_type_traitement." ".$str."
order by n.matricule
) as req order by matricule";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function fetchAllRepartition()
{
	global $conn;
	$sql = "select * from cc_sr_repartition order by ordre";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}
function _getCategorieDetailEval($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc,$id_type_appel)
{
	global $conn;
	$str = '';
	if($date_deb_notation != '')
	{
		//$str .= " and n.date_notation >= '".$date_deb_notation."' ";
		$str .= " and n.date_entretien >= '".$date_deb_notation."' ";
	}
	if($date_fin_notation != '')
	{
		//$str .= " and n.date_notation <= '".$date_fin_notation."' ";
		$str .= " and n.date_entretien <= '".$date_fin_notation."' ";
	}
	if($matricule_auditeur != 0)
	{
		$str .= " and n.matricule_notation = ".$matricule_auditeur." ";
	}
	if($matricule_tlc != 0)
	{
		$str .= " and n.matricule = ".$matricule_tlc." ";
	}
    if($id_type_appel != 0)
	{
		$str .= " and n.id_typologie = ".$id_type_appel." ";
	}
	$tableau = array();
	$sql = "
	select --((sum(ponderation*note)/sum(ponderation))*10) as somme_produit ,
	--case when sum(ponderation)=0 then 1 else((sum(ponderation*note)/sum(ponderation))*10) end as somme_produit ,
	case when sum(ponderation)=0 
	then 
		case when (id_type_traitement =3 or id_client=643) then 1000 else 10 end 
	else((sum(ponderation*note)/sum(ponderation))*10) end as somme_produit ,id_notation,
	id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille,ordre from (
		select  distinct case when inot.flag_ponderation=1 then 0 else ga.ponderation end as ponderation,inot.note as note1,
		case when (inot.note <= 100 and inot.note > 10) then (inot.note/100)::double precision else 
				case when (inot.note <= 1000 and inot.note > 100) then (inot.note/1000)::double precision else 
					case when (inot.note <= 10000 and inot.note > 1000) then (inot.note/10000)::double precision else inot.note::double precision end 
				end
			end as note,
		ga.id_application,ga.id_client,n.matricule,
		inot.id_grille_application,ga.id_grille_application,n.id_notation,cg.id_categorie_grille,
		cg.libelle_categorie_grille ,cg.ordre,cg.id_type_traitement, n.matricule_notation, n.date_notation 
		from cc_sr_categorie_grille cg 
		inner join  cc_sr_grille g on g.id_categorie_grille=cg.id_categorie_grille
		inner join cc_sr_indicateur_notation inot on inot.id_grille=g.id_grille
		inner join cc_sr_notation n on n.id_notation=inot.id_notation
		inner join cc_sr_grille_application ga on ga.id_grille_application=  inot.id_grille_application
		where ga.id_client=".$id_client." and ga.id_application=".$id_application." and cg.id_type_traitement = ".$id_type_traitement." ".$str."
		order by cg.ordre,cg.id_categorie_grille,n.id_notation, n.matricule
	) as req 
	group by ordre,id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille,id_notation,id_type_traitement
	order by ordre,id_notation,id_categorie_grille";
	$query_notes  = pg_query($sql) or die(pg_last_error());
	
	while($res_notes = pg_fetch_array($query_notes))
	{
		$tableau[$res_notes['matricule']]['categorie_grille'][$res_notes['id_categorie_grille']][$res_notes['id_notation']] = $res_notes['somme_produit'];
		$tableau[$res_notes['matricule']]['libelle_categorie_grille'][$res_notes['id_categorie_grille']] = $res_notes['libelle_categorie_grille'];
	}
	
	return $tableau;
}
function getCategorieDetailEval($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc)
{
	global $conn;
	$str = '';
	if($date_deb_notation != '')
	{
		//$str .= " and n.date_notation >= '".$date_deb_notation."' ";
		$str .= " and n.date_entretien >= '".$date_deb_notation."' ";
	}
	if($date_fin_notation != '')
	{
		//$str .= " and n.date_notation <= '".$date_fin_notation."' ";
		$str .= " and n.date_entretien <= '".$date_fin_notation."' ";
	}
	if($matricule_auditeur != 0)
	{
		$str .= " and n.matricule_notation = ".$matricule_auditeur." ";
	}
	if($matricule_tlc != 0)
	{
		$str .= " and n.matricule = ".$matricule_tlc." ";
	}

	$tableau = array();
	$sql = "
	select --((sum(ponderation*note)/sum(ponderation))*10) as somme_produit ,
	--case when sum(ponderation)=0 then 1 else((sum(ponderation*note)/sum(ponderation))*10) end as somme_produit ,
	case when sum(ponderation)=0 
	then 
		case when (id_type_traitement =3 or id_client=643) then 1000 else 10 end 
	else((sum(ponderation*note)/sum(ponderation))*10) end as somme_produit ,id_notation,
	id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille,ordre from (
		select  distinct case when inot.flag_ponderation=1 then 0 else ga.ponderation end as ponderation,inot.note as note1,
		case when (inot.note <= 100 and inot.note > 10) then (inot.note/100)::double precision else 
				case when (inot.note <= 1000 and inot.note > 100) then (inot.note/1000)::double precision else 
					case when (inot.note <= 10000 and inot.note > 1000) then (inot.note/10000)::double precision else inot.note::double precision end 
				end
			end as note,
		ga.id_application,ga.id_client,n.matricule,
		inot.id_grille_application,ga.id_grille_application,n.id_notation,cg.id_categorie_grille,
		cg.libelle_categorie_grille ,cg.ordre,cg.id_type_traitement, n.matricule_notation, n.date_notation 
		from cc_sr_categorie_grille cg 
		inner join  cc_sr_grille g on g.id_categorie_grille=cg.id_categorie_grille
		inner join cc_sr_indicateur_notation inot on inot.id_grille=g.id_grille
		inner join cc_sr_notation n on n.id_notation=inot.id_notation
		inner join cc_sr_grille_application ga on ga.id_grille_application=  inot.id_grille_application
		where ga.id_client=".$id_client." and ga.id_application=".$id_application." and cg.id_type_traitement = ".$id_type_traitement." ".$str."
		order by cg.ordre,cg.id_categorie_grille,n.id_notation, n.matricule
	) as req 
	group by ordre,id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille,id_notation,id_type_traitement
	order by ordre,id_notation,id_categorie_grille";
	$query_notes  = pg_query($sql) or die(pg_last_error());
	
	while($res_notes = pg_fetch_array($query_notes))
	{
		$tableau[$res_notes['matricule']]['categorie_grille'][$res_notes['id_categorie_grille']][$res_notes['id_notation']] = $res_notes['somme_produit'];
		$tableau[$res_notes['matricule']]['libelle_categorie_grille'][$res_notes['id_categorie_grille']] = $res_notes['libelle_categorie_grille'];
	}
	
	return $tableau;
}

function setTableauSynthese($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc,$id_type_appel)
{
	global $conn;
	
	$str = '';
	if($date_deb_notation != '')
	{
		//$str .= " and n.date_notation >= '".$date_deb_notation."' ";
		$str .= " and n.date_entretien >= '".$date_deb_notation."' ";
	}
	if($date_fin_notation != '')
	{
		//$str .= " and n.date_notation <= '".$date_fin_notation."' ";
		$str .= " and n.date_entretien <= '".$date_fin_notation."' ";
	}
	if($matricule_auditeur != 0)
	{
		$str .= " and n.matricule_notation = ".$matricule_auditeur." ";
	}
	if($matricule_tlc != 0)
	{
		$str .= " and n.matricule = ".$matricule_tlc." ";
	}
	if($id_type_appel != 0)
	{
		$str .= " and n.id_typologie = ".$id_type_appel." ";
	}

	$tableau = array();
	/**
	* *********** Note par catégorie ***************
	*/
	/*$sql = "select sum(somme_produit)/count(id_categorie_grille) as som
,id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille 
from(
	select --((sum(ponderation*note)/sum(ponderation))*10) as somme_produit ,
	case when sum(ponderation)=0 then 0 else((sum(ponderation*note)/sum(ponderation))*10) end as somme_produit ,
	id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille from (
		select  distinct case when inot.flag_ponderation=1 then 0 else ga.ponderation end as ponderation,inot.note, 
		ga.id_application,ga.id_client,n.matricule,
		inot.id_grille_application,ga.id_grille_application,n.id_notation,cg.id_categorie_grille,
		cg.libelle_categorie_grille ,cg.id_type_traitement, n.matricule_notation, n.date_notation 
		from cc_sr_categorie_grille cg 
		inner join  cc_sr_grille g on g.id_categorie_grille=cg.id_categorie_grille
		inner join cc_sr_indicateur_notation inot on inot.id_grille=g.id_grille
		inner join cc_sr_notation n on n.id_notation=inot.id_notation
		inner join cc_sr_grille_application ga on ga.id_grille_application=  inot.id_grille_application
		where ga.id_client=".$id_client." and ga.id_application=".$id_application." and cg.id_type_traitement = ".$id_type_traitement." ".$str."
		order by cg.id_categorie_grille,n.id_notation, n.matricule
	) as req 
	group by id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille,id_notation
	order by id_notation,id_categorie_grille
) as req1 group by id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille
order by matricule";*/
	$sql = "select sum(somme_produit)/count(id_categorie_grille) as som
,id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille 
from(
	select --((sum(ponderation*note)/sum(ponderation))*10) as somme_produit ,
	--case when sum(ponderation)=0 then 1 else((sum(ponderation*note)/sum(ponderation))*10) end as somme_produit ,
	case when sum(ponderation)=0 
	then 
		case when (id_type_traitement =3 or id_client=643) then 1000 else 10 end 
	else((sum(ponderation*note)/sum(ponderation))*10) end as somme_produit ,
	id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille,ordre from (
		select  distinct case when inot.flag_ponderation=1 then 0 else ga.ponderation end as ponderation,inot.note as note1,inot.note as note,
	/*case when (inot.note <= 100 and inot.note > 10) then (inot.note/100)::double precision else 
				case when (inot.note <= 1000 and inot.note > 100) then (inot.note/1000)::double precision else 
					case when (inot.note <= 10000 and inot.note > 1000) then (inot.note/10000)::double precision else inot.note::double precision end 
				end
			end as note,*/	
		ga.id_application,ga.id_client,n.matricule,
		inot.id_grille_application,ga.id_grille_application,n.id_notation,cg.id_categorie_grille,
		cg.libelle_categorie_grille ,cg.ordre,cg.id_type_traitement, n.matricule_notation, n.date_notation 
		from cc_sr_categorie_grille cg 
		inner join  cc_sr_grille g on g.id_categorie_grille=cg.id_categorie_grille
		inner join cc_sr_indicateur_notation inot on inot.id_grille=g.id_grille
		inner join cc_sr_notation n on n.id_notation=inot.id_notation
		inner join cc_sr_grille_application ga on ga.id_grille_application=  inot.id_grille_application
		where ga.id_client=".$id_client." and ga.id_application=".$id_application." and cg.id_type_traitement = ".$id_type_traitement." ".$str."
		order by cg.ordre,cg.id_categorie_grille,n.id_notation, n.matricule
	) as req 
	group by ordre,id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille,id_notation,id_type_traitement
	order by ordre,id_notation,id_categorie_grille
) as req1 
group by ordre,id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille
order by ordre,matricule";

	$query_notes  = pg_query($sql) or die(pg_last_error());
	
	while($res_notes = pg_fetch_array($query_notes))
	{
		$tableau[$res_notes['matricule']]['categorie_grille'][$res_notes['id_categorie_grille']] = $res_notes['som'];
		$tableau[$res_notes['matricule']]['libelle_categorie_grille'][$res_notes['id_categorie_grille']] = $res_notes['libelle_categorie_grille'];
	}
	
	/**
	* 
	* @var ************Indicateur NF 345 ******************
	* 
	*/
	/*for($i=4;$i<=7;$i++)
	{*/
	$tableaunf = get_indicateur_nf();
	foreach($tableaunf as $keynf => $valnf)
	{
		/*$sql = "select sum(is_type1) as is_type1
	,sum(is_type3) as is_type3,matricule,count(id_notation) as nbeval
	, ((sum(is_type1)::float/count(id_notation))*100) as percent_type1
	, ((sum(is_type3)::float/count(id_notation))*100) as percent_type3
	 from (
	  select sum(note*pond)/sum(pond) as somme_prod
	 ,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
	 ,case when ((sum(note*pond)/sum(pond))<100) then 0 else 1 end as is_type3
	 ,matricule,id_notation from (
		select a.id_notation,a.id_grille,a.note,a.flag_ponderation , b.ponderation,
		case when flag_ponderation = 1 then 0 else ponderation end as pond, flag_is,n.matricule, n.matricule_notation, n.date_notation
		from cc_sr_indicateur_notation a 
		inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application
		inner join cc_sr_notation n on n.id_notation=a.id_notation
		left join cc_sr_grille g on g.id_grille=b.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		WHERE 
		b.id_projet=".$id_projet." and flag_is ilike '%IS".$i."%' and cg.id_type_traitement=".$id_type_traitement." ".$str."
		ORDER BY n.id_notation
		) as req1   group by id_notation,matricule
		  order by matricule,id_notation
	) as req2 
	group by matricule";*/
	$sql = "select sum(is_type1) as is_type1
	,sum(is_type3) as is_type3,matricule,count(id_notation) as nbeval
	, ((sum(is_type1)::float/count(id_notation))*100) as percent_type1
	, ((sum(is_type3)::float/count(id_notation))*100) as percent_type3
	, id_client
	 from (
	  select --sum(note*pond)/sum(pond) as somme_prod
	  case when sum(pond) = 0 then 0 else sum(note*pond)/sum(pond) end as somme_prod
	 ,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
	 ,case when sum(pond) = 0 then 1 else case when ((sum(note1*pond)/sum(pond))<100) then 0 else 1 end end as is_type3
	 ,matricule,id_notation,id_client from (
		select b.id_client,a.id_notation,a.id_grille,a.note as note1
		,case when (a.note <= 100 and a.note > 10) then (a.note/100)::double precision else 
			case when (a.note <= 1000 and a.note > 100) then (a.note/1000)::double precision else 
				case when (a.note <= 10000 and a.note > 1000) then (a.note/10000)::double precision else a.note::double precision end 
			end
		end as note
		,a.flag_ponderation , b.ponderation,
		case when flag_ponderation = 1 then 0 else ponderation end as pond, flag_is,n.matricule, n.matricule_notation, n.date_notation
		from cc_sr_indicateur_notation a 
		inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application
		inner join cc_sr_notation n on n.id_notation=a.id_notation
		left join cc_sr_grille g on g.id_grille=b.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		WHERE 
		b.id_projet=".$id_projet." and (flag_is ilike '%".$valnf.";%' or flag_is ilike '%".$valnf."') and cg.id_type_traitement=".$id_type_traitement." ".$str."
		ORDER BY n.id_notation
		) as req1   group by id_notation,matricule,id_client
		  order by matricule,id_notation
	) as req2 
	group by matricule,id_client";
	//echo '<pre>';print_r($sql);echo '</pre>';
	$is = $valnf;
		$query_nf345  = pg_query($sql) or die($sql.pg_last_error());
		while($res_nf = pg_fetch_array($query_nf345))
		{
			if(($id_type_traitement == 1 || $id_type_traitement == 2) && $res_nf['id_client'] != 643) //client différent de DELAMAISON
			{
				$tableau[$res_nf['matricule']][$is]['global'] = $res_nf['percent_type1'];
				$tableau[$res_nf['matricule']][$is]['is_type'] = $res_nf['is_type1'];
				$tableau[$res_nf['matricule']][$is]['nb_eval'] = $res_nf['nbeval'];
			}
			else
			{
				$tableau[$res_nf['matricule']][$is]['global'] = $res_nf['percent_type3'];
				$tableau[$res_nf['matricule']][$is]['is_type'] = $res_nf['is_type3'];
				$tableau[$res_nf['matricule']][$is]['nb_eval'] = $res_nf['nbeval'];
			}
		}
		
	/*$sql = "select sum(is_type1) as is_type1
,sum(is_type3) as is_type3,matricule,count(id_notation) as nbeval
, ((sum(is_type1)::float/count(id_notation))*100) as percent_type1
, ((sum(is_type3)::float/count(id_notation))*100) as percent_type3,
id_categorie_grille, libelle_categorie_grille
 from (
	 select --sum(note*pond)/sum(pond) as somme_prod
	 --,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
	 --,case when ((sum(note*pond)/sum(pond))<100) then 0 else 1 end as is_type3
	 case when sum(pond) = 0 then 0 else sum(note*pond)/sum(pond) end as somme_prod
	 ,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
	 ,case when sum(pond) = 0 then 0 else case when ((sum(note*pond)/sum(pond))<100) then 0 else 1 end end as is_type3
	 ,matricule,id_notation,id_categorie_grille, libelle_categorie_grille from (
		select a.id_notation,a.id_grille,cg.id_categorie_grille,cg.libelle_categorie_grille,a.note,
		a.flag_ponderation , b.ponderation,
		case when flag_ponderation = 1 then 0 else ponderation end as pond, flag_is,n.matricule,n.matricule_notation, n.date_notation
		from cc_sr_indicateur_notation a 
		inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application
		inner join cc_sr_notation n on n.id_notation=a.id_notation
		left join cc_sr_grille g on g.id_grille=b.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		WHERE 
		b.id_projet=".$id_projet." and flag_is ilike '%IS".$i."%' and cg.id_type_traitement=".$id_type_traitement." ".$str."
		ORDER BY n.id_notation, cg.id_categorie_grille
		) as req1   
	group by id_notation,matricule,id_categorie_grille,libelle_categorie_grille
	order by matricule,id_notation,id_categorie_grille,libelle_categorie_grille
	) as req2 
group by matricule,id_categorie_grille,libelle_categorie_grille 
order by matricule";*/
	$sql = "select sum(is_type1) as is_type1
,sum(is_type3) as is_type3,matricule,count(id_notation) as nbeval
, ((sum(is_type1)::float/count(id_notation))*100) as percent_type1
, ((sum(is_type3)::float/count(id_notation))*100) as percent_type3,
id_categorie_grille, libelle_categorie_grille, id_grille, libelle_grille,id_client
 from (
	 select --sum(note*pond)/sum(pond) as somme_prod
	 --,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
	 --,case when ((sum(note*pond)/sum(pond))<100) then 0 else 1 end as is_type3
	 case when sum(pond) = 0 then 0 else sum(note*pond)/sum(pond) end as somme_prod
	 ,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
	 ,case when sum(pond) = 0 then 1 else case when ((sum(note1*pond)/sum(pond))<100) then 0 else 1 end end as is_type3
	 ,matricule,id_notation,id_categorie_grille, libelle_categorie_grille, id_grille, libelle_grille ,id_client
	 from (
		select b.id_client,a.id_notation,cg.id_categorie_grille,cg.libelle_categorie_grille,g.id_grille,g.libelle_grille,a.note as note1
		,case when (a.note <= 100 and a.note > 10) then (a.note/100)::double precision else 
			case when (a.note <= 1000 and a.note > 100) then (a.note/1000)::double precision else 
				case when (a.note <= 10000 and a.note > 1000) then (a.note/10000)::double precision else a.note::double precision end 
			end
		end as note
		,a.flag_ponderation , b.ponderation,
		case when flag_ponderation = 1 then 0 else ponderation end as pond, flag_is,n.matricule,n.matricule_notation, n.date_notation
		from cc_sr_indicateur_notation a 
		inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application
		inner join cc_sr_notation n on n.id_notation=a.id_notation
		left join cc_sr_grille g on g.id_grille=b.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		WHERE 
		b.id_projet=".$id_projet." and (flag_is ilike '%".$valnf.";%' or flag_is ilike '%".$valnf."') and cg.id_type_traitement=".$id_type_traitement." ".$str."
		ORDER BY n.id_notation, cg.id_categorie_grille
		) as req1   
	group by id_notation,matricule,id_categorie_grille,libelle_categorie_grille,id_grille,libelle_grille,id_client
	order by matricule,id_notation,id_categorie_grille,libelle_categorie_grille,id_grille,libelle_grille,id_client
	) as req2 
group by matricule,id_categorie_grille,libelle_categorie_grille, id_grille, libelle_grille,id_client
order by matricule";
//echo '<pre>';print_r($sql);echo '</pre>';
$is = $valnf;
		$query_nf345  = pg_query($sql) or die(pg_last_error());
		while($res_nf = pg_fetch_array($query_nf345))
		{
			if(($id_type_traitement == 1 || $id_type_traitement == 2) && $res_nf['id_client'] != 643) //client différent de DELAMAISON
			{
				$tableau[$res_nf['matricule']][$is]['critere'][$res_nf['id_grille']]['valeur'] = $res_nf['percent_type1'];
				$tableau[$res_nf['matricule']][$is]['critere'][$res_nf['id_grille']]['is_type'] = $res_nf['is_type1'];
				$tableau[$res_nf['matricule']][$is]['critere'][$res_nf['id_grille']]['nb_eval'] = $res_nf['nbeval'];
				//$tableau[$res_nf['matricule']][$is]['critere'][$res_nf['id_categorie_grille']]['libelle'] = $res_nf['libelle_categorie_grille'];
				$tableau[$res_nf['matricule']][$is]['critere'][$res_nf['id_grille']]['libelle'] = $res_nf['libelle_grille'];
			}
			else
			{
				$tableau[$res_nf['matricule']][$is]['critere'][$res_nf['id_grille']]['valeur'] = $res_nf['percent_type3'];
				$tableau[$res_nf['matricule']][$is]['critere'][$res_nf['id_grille']]['is_type'] = $res_nf['is_type3'];
				$tableau[$res_nf['matricule']][$is]['critere'][$res_nf['id_grille']]['nb_eval'] = $res_nf['nbeval'];
				//$tableau[$res_nf['matricule']][$is]['critere'][$res_nf['id_categorie_grille']]['libelle'] = $res_nf['libelle_categorie_grille'];
				$tableau[$res_nf['matricule']][$is]['critere'][$res_nf['id_grille']]['libelle'] = $res_nf['libelle_grille'];
			}
		}
		
	}
	
	/**
	* 
	* @var *********** Situation inacceptable *********************
	* 
	*/
	$sql = "select sum(nb_csi),matricule,id_repartition from(
	select count(commentaire_si) as nb_csi ,id_notation,matricule,id_repartition from (
		select a.commentaire_si ,n.id_notation,n.matricule,b.id_repartition,n.matricule_notation, n.date_notation
		from cc_sr_indicateur_notation a 
		inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application 
		inner join cc_sr_notation n on n.id_notation = a.id_notation
		left join cc_sr_grille g on g.id_grille=b.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		where b.id_client = ".$id_client."
		and b.id_application = ".$id_application."
		and a.commentaire_si != ''  
		and cg.id_type_traitement=".$id_type_traitement." ".$str."
		order by n.matricule,n.id_notation,b.id_repartition ) as req1
	group by id_notation,matricule,id_repartition
	order by matricule,id_notation,id_repartition
) as req2 
group by matricule,id_repartition 
order by matricule,id_repartition";
	$query_si  = pg_query($sql) or die(pg_last_error());
	while($res_si = pg_fetch_array($query_si))
	{
		$tableau[$res_si['matricule']]['repartition'][$res_si['id_repartition']] = $res_si['sum'];
	}
	
	//echo json_encode($tableau);
	return $tableau;
}

function setTableauSynthesePrestation($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc,$id_type_appel)
{
	global $conn;
	$str = '';
	if($date_deb_notation != '')
	{
		//$str .= " and n.date_notation >= '".$date_deb_notation."' ";
		$str .= " and n.date_entretien >= '".$date_deb_notation."' ";
	}
	if($date_fin_notation != '')
	{
		//$str .= " and n.date_notation <= '".$date_fin_notation."' ";
		$str .= " and n.date_entretien <= '".$date_fin_notation."' ";
	}
	if($matricule_auditeur != 0)
	{
		$str .= " and n.matricule_notation = ".$matricule_auditeur." ";
	}
	if($matricule_tlc != 0)
	{
		$str .= " and n.matricule = ".$matricule_tlc." ";
	}
	if($id_type_appel != 0)
	{
		$str .= " and n.id_typologie = ".$id_type_appel." ";
	}
	
	$tableau = array();
	
			/*$sql = "select case when id_type_traitement = 3 then (sum(somme_produit)/count(id_categorie_grille))/10 else sum(somme_produit)/count(id_categorie_grille) end som
		,id_application,id_client,id_projet,id_categorie_grille,libelle_categorie_grille,code,id_type_traitement 
		from(
			select --((sum(ponderation*note)/sum(ponderation))*10) as somme_produit ,
			case when sum(ponderation)=0 then 0 else((sum(ponderation*note)/sum(ponderation))*10) end as somme_produit ,
			id_application,id_client,id_projet,matricule,id_categorie_grille,libelle_categorie_grille,ordre,code, id_type_traitement from (
				select  distinct case when inot.flag_ponderation=1 then 0 else ga.ponderation end as ponderation,inot.note, 
				ga.id_application,ga.id_client,ga.id_projet,n.matricule,
				inot.id_grille_application,ga.id_grille_application,n.id_notation,cg.id_categorie_grille,
				cg.libelle_categorie_grille ,cg.ordre,cg.id_type_traitement, n.matricule_notation, n.date_notation, gua.code 
				from cc_sr_categorie_grille cg 
				inner join  cc_sr_grille g on g.id_categorie_grille=cg.id_categorie_grille
				inner join cc_sr_indicateur_notation inot on inot.id_grille=g.id_grille
				inner join cc_sr_notation n on n.id_notation=inot.id_notation
				inner join cc_sr_grille_application ga on ga.id_grille_application=  inot.id_grille_application
				inner join gu_application gua on gua.id_application = ga.id_application
				where 1=1 
				--and ga.id_client=".$id_client." and ga.id_application=".$id_application." 
				and cg.id_type_traitement = ".$id_type_traitement." ".$str."
				order by cg.ordre,cg.id_categorie_grille,n.id_notation, n.matricule
			) as req 
			group by ordre,id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille,id_notation,id_projet,code, id_type_traitement
			order by ordre,id_notation,id_categorie_grille
		) as req1 
		group by ordre,id_application,id_client,id_categorie_grille,libelle_categorie_grille,id_projet,code,id_type_traitement
		order by id_projet,ordre";*/
		
		$sql ="SELECT ( (sum(som)/count(id_categorie_grille))) as som,id_application,id_client,id_projet,id_categorie_grille,libelle_categorie_grille,code,id_type_traitement
		from (
		SELECT case when id_type_traitement = 3 then (sum(somme_produit)/count(id_categorie_grille))/10 else sum(somme_produit)/count(id_categorie_grille) end som
		,id_application,id_client,id_projet,id_categorie_grille,libelle_categorie_grille,code,id_type_traitement ,mat,ordre
		from(
			SELECT matricule as mat, 
			case when sum(ponderation)=0 then 0 else((sum(ponderation*note)/sum(ponderation))*10) end as somme_produit ,
			id_application,id_client,id_projet,matricule,id_categorie_grille,libelle_categorie_grille,ordre,code, id_type_traitement from (
				SELECT  distinct case when inot.flag_ponderation=1 then 0 else ga.ponderation end as ponderation,inot.note as note1
				,inot.note,
				ga.id_application,ga.id_client,ga.id_projet,n.matricule,
				inot.id_grille_application,ga.id_grille_application,n.id_notation,cg.id_categorie_grille,
				cg.libelle_categorie_grille ,cg.ordre,cg.id_type_traitement, n.matricule_notation, n.date_notation, gua.code 
				from cc_sr_categorie_grille cg 
				inner join  cc_sr_grille g on g.id_categorie_grille=cg.id_categorie_grille
				inner join cc_sr_indicateur_notation inot on inot.id_grille=g.id_grille
				inner join cc_sr_notation n on n.id_notation=inot.id_notation
				inner join cc_sr_grille_application ga on ga.id_grille_application=  inot.id_grille_application
				inner join gu_application gua on gua.id_application = ga.id_application
				where 1=1 
				--and ga.id_client=".$id_client." and ga.id_application=".$id_application." 
				and cg.id_type_traitement = ".$id_type_traitement." ".$str."
				order by cg.ordre,cg.id_categorie_grille,n.id_notation, n.matricule
			) as req 
			group by ordre,id_application,id_client,matricule,id_categorie_grille,libelle_categorie_grille,id_notation,id_projet,code, id_type_traitement
			order by ordre,id_notation,id_categorie_grille
		) as req1
		group by mat,ordre,id_application,id_client,id_categorie_grille,libelle_categorie_grille,id_projet,code,id_type_traitement
		order by id_projet,ordre
		) as res
		group by id_application,id_client,id_projet,ordre,id_categorie_grille,libelle_categorie_grille,code,id_type_traitement,ordre
		order by id_projet,ordre
			";
	
	$query_notes  = pg_query($sql) or die(pg_last_error());
	
	while($res_notes = pg_fetch_array($query_notes))
	{
		$tableau[$id_type_traitement][$res_notes['code']]['som_by_id_projet'][$res_notes['id_categorie_grille']] = $res_notes['som'];
		$tableau[$id_type_traitement][$res_notes['code']]['libelle_categorie_grille'][$res_notes['id_categorie_grille']] = $res_notes['libelle_categorie_grille'];
	}
	$sql = "select count(commentaire_si) as nb_csi ,id_application,code,nom_application,id_repartition,id_type_traitement 
	from (
		select a.commentaire_si ,n.id_notation,n.matricule,b.id_repartition,n.matricule_notation, n.date_notation
		,b.id_application, gua.code, gua.nom_application, cg.id_type_traitement
		from cc_sr_indicateur_notation a 
		inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application 
		inner join cc_sr_notation n on n.id_notation = a.id_notation
		left join cc_sr_grille g on g.id_grille=b.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		left join gu_application gua on gua.id_application = b.id_application
		where 1=1 
		--and b.id_client = ".$id_client."
		--and b.id_application = ".$id_application."
		and a.commentaire_si != ''  
		and cg.id_type_traitement=".$id_type_traitement." ".$str."
		order by n.matricule,n.id_notation,b.id_repartition 
	) as req1
	group by id_application,code,nom_application,id_repartition,id_type_traitement
	order by code,nom_application,id_repartition";
	$query_si  = pg_query($sql) or die(pg_last_error());
	while($res_si = pg_fetch_array($query_si))
	{
		$tableau[$res_si['id_type_traitement']][$res_si['code']]['repartition'][$res_si['id_repartition']] = $res_si['nb_csi'];
	}
	
	//--and (flag_is ilike '%".$valnf.";%' or flag_is ilike '%".$valnf."') 
	$sql = "select sum(is_type1) as is_type1
,sum(is_type3) as is_type3,
code,nom_application,id_application,id_type_traitement,nom_client,
count(id_notation) as nbeval
, ((sum(is_type1)::float/count(id_notation))*100) as percent_type1
, ((sum(is_type3)::float/count(id_notation))*100) as percent_type3, id_projet, id_client
from (
	  select --sum(note*pond)/sum(pond) as somme_prod
	  case when sum(pond) = 0 then 0 else sum(note*pond)/sum(pond) end as somme_prod
	 ,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
	 ,case when sum(pond) = 0 then 1 else case when ((sum(note*pond)/sum(pond))<100) then 0 else 1 end end as is_type3
	 ,id_application,code,nom_application,id_notation,id_type_traitement,nom_client,id_projet,id_client from (
		select b.id_projet,b.id_client,a.id_notation,a.id_grille,a.note,a.flag_ponderation , b.ponderation, cg.id_type_traitement,b.id_application,gua.code,gua.nom_application,
		case when flag_ponderation = 1 then 0 else ponderation end as pond, flag_is,n.matricule, n.matricule_notation, n.date_notation,guc.nom_client
		from cc_sr_indicateur_notation a 
		inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application
		inner join cc_sr_notation n on n.id_notation=a.id_notation
		left join cc_sr_grille g on g.id_grille=b.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		left join gu_application gua on gua.id_application = b.id_application
		left join gu_client guc on guc.id_client = gua.id_client
		WHERE 1=1 
		--and b.id_projet=".$id_projet." 
		
		and cg.id_type_traitement=".$id_type_traitement." ".$str."
		ORDER BY n.id_notation
		) as req1   
	group by code,nom_application,id_application,id_notation,id_type_traitement,nom_client,id_projet,id_client
	order by code,nom_application,id_application,id_notation,id_type_traitement,nom_client,id_projet,id_client
	) as req2 
	group by code,nom_application,id_application,id_type_traitement,nom_client,id_projet,id_client";
	$query  = pg_query($sql) or die($sql.pg_last_error());
	while($res_nf = pg_fetch_array($query))
	{
		$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['libelle_code'] = $res_nf['nom_application'];
		$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['nb_evaluation'] = $res_nf['nbeval'];
		$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['prestation'] = $res_nf['code'];
		$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['client'] = $res_nf['nom_client'];
		$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['id_client'] = $res_nf['id_client'];
		$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['id_projet'] = $res_nf['id_projet'];
	}
	
	/*for($i=4;$i<=7;$i++)
	{*/
	$tableaunf = get_indicateur_nf();
	foreach($tableaunf as $keynf => $valnf)
	{
	
	/* COMMENTER PAR TTL*/
	/*	$sql = "select sum(is_type1) as is_type1
,sum(is_type3) as is_type3,
code,nom_application,id_application,id_type_traitement,nom_client,
count(id_notation) as nbeval
, ((sum(is_type1)::float/count(id_notation))*100) as percent_type1
, ((sum(is_type3)::float/count(id_notation))*100) as percent_type3, id_projet, id_client
from (
	  select --sum(note*pond)/sum(pond) as somme_prod
	  case when sum(pond) = 0 then 0 else sum(note*pond)/sum(pond) end as somme_prod
	 ,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
	 ,case when sum(pond) = 0 then 1 else case when ((sum(note*pond)/sum(pond))<100) then 0 else 1 end end as is_type3
	 ,id_application,code,nom_application,id_notation,id_type_traitement,nom_client,id_projet,id_client from (
		select b.id_projet,b.id_client,a.id_notation,a.id_grille,a.note,a.flag_ponderation , b.ponderation, cg.id_type_traitement,b.id_application,gua.code,gua.nom_application,
		case when flag_ponderation = 1 then 0 else ponderation end as pond, flag_is,n.matricule, n.matricule_notation, n.date_notation,guc.nom_client
		from cc_sr_indicateur_notation a 
		inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application
		inner join cc_sr_notation n on n.id_notation=a.id_notation
		left join cc_sr_grille g on g.id_grille=b.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		left join gu_application gua on gua.id_application = b.id_application
		left join gu_client guc on guc.id_client = gua.id_client
		WHERE 1=1 
		--and b.id_projet=".$id_projet." 
		and (flag_is ilike '%".$valnf.";%' or flag_is ilike '%".$valnf."') 
		and cg.id_type_traitement=".$id_type_traitement." ".$str."
		ORDER BY n.id_notation
		) as req1   
	group by code,nom_application,id_application,id_notation,id_type_traitement,nom_client,id_projet,id_client
	order by code,nom_application,id_application,id_notation,id_type_traitement,nom_client,id_projet,id_client
	) as req2 
	group by code,nom_application,id_application,id_type_traitement,nom_client,id_projet,id_client";
	*/
	
	$sql = "
		select 
		sum(is_type1) as is_type1
		,sum(is_type3) as is_type3,
		code,nom_application,id_application,id_type_traitement,nom_client,
		count(id_notation) as nbeval
		, ((sum(is_type1)::float/count(id_notation))*100) as percent_type1
		, ((sum(is_type3)::float/count(id_notation))*100) as percent_type3, id_projet, id_client
		
		from (
			  select matricule,
			 case when sum(pond) = 0 then 0 else sum(note*pond)/sum(pond) end as somme_prod
			 ,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
			 ,case when sum(pond) = 0 then 1 else case when ((sum(note1*pond)/sum(pond))<100) then 0 else 1 end end as is_type3
			 ,id_application,code,nom_application,id_notation,id_type_traitement,nom_client,id_projet,id_client from (
				select b.id_projet,b.id_client,a.id_notation,a.id_grille,a.note as note1
				,case when (a.note <= 100 and a.note > 10) then (a.note/100)::double precision else 
					case when (a.note <= 1000 and a.note > 100) then (a.note/1000)::double precision else 
						case when (a.note <= 10000 and a.note > 1000) then (a.note/10000)::double precision else a.note::double precision end 
					end
				end as note,
				a.flag_ponderation , b.ponderation, cg.id_type_traitement,
				b.id_application,gua.code,gua.nom_application,
				case when flag_ponderation = 1 then 0 else ponderation end as pond,
				 flag_is,n.matricule, n.matricule_notation, n.date_notation,guc.nom_client
				from cc_sr_indicateur_notation a 
				inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application
				inner join cc_sr_notation n on n.id_notation=a.id_notation
				left join cc_sr_grille g on g.id_grille=b.id_grille
				left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
				left join gu_application gua on gua.id_application = b.id_application
				left join gu_client guc on guc.id_client = gua.id_client
				WHERE 1=1 /*and b.id_projet=169*/
				and (flag_is ilike '%".$valnf.";%' or flag_is ilike '%".$valnf."') 
				and cg.id_type_traitement=".$id_type_traitement." ".$str."  
				ORDER BY matricule
				) as req1   
				group by matricule,code,nom_application,id_application,id_notation,id_type_traitement,nom_client,id_projet,id_client
	order by matricule,code,nom_application,id_application,id_notation,id_type_traitement,nom_client,id_projet,id_client



	) as req2 
	group by code,id_client,nom_application,id_application,id_type_traitement,nom_client,id_projet
	order by code,id_client,nom_application,id_application,id_type_traitement,nom_client,id_projet

";
	
		//$is = 'IS'.$i;
		$is = $valnf;
		$query_nf345  = pg_query($sql) or die(pg_last_error());
		while($res_nf = pg_fetch_array($query_nf345))
		{
			if(($id_type_traitement == 1 || $id_type_traitement == 2) && $res_nf['id_client'] != 643) //client différent de DELAMAISON
			{
				$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['indicateur_nf'][$is] = $res_nf['percent_type1'];
			}
			else
			{
				$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['indicateur_nf'][$is] = $res_nf['percent_type3'];
			}
			/*$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['libelle_code'] = $res_nf['nom_application'];
			$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['nb_evaluation'] = $res_nf['nbeval'];
			$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['prestation'] = $res_nf['code'];
			$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['client'] = $res_nf['nom_client'];
			$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['id_client'] = $res_nf['id_client'];
			$tableau[$res_nf['id_type_traitement']][$res_nf['code']]['id_projet'] = $res_nf['id_projet'];*/
		}
		
		$sql = "select sum(is_type1) as is_type1
,sum(is_type3) as is_type3,count(id_notation) as nbeval
, ((sum(is_type1)::float/count(id_notation))*100) as percent_type1
, ((sum(is_type3)::float/count(id_notation))*100) as percent_type3,
id_categorie_grille, libelle_categorie_grille, id_grille, libelle_grille,code,id_client
 from (
	 /*select --sum(note*pond)/sum(pond) as somme_prod
	 --,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
	 --,case when ((sum(note*pond)/sum(pond))<100) then 0 else 1 end as is_type3*/
	 select case when sum(pond) = 0 then 0 else sum(note*pond)/sum(pond) end as somme_prod
	 ,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
	 ,case when sum(pond) = 0 then 1 else case when ((sum(note1*pond)/sum(pond))<100) then 0 else 1 end end as is_type3
	 ,matricule,id_notation,id_categorie_grille, libelle_categorie_grille, id_grille, libelle_grille, code, id_client 
	 from (
		select b.id_client,a.id_notation,cg.id_categorie_grille,cg.libelle_categorie_grille,g.id_grille,g.libelle_grille,a.note as note1,
		case when (a.note <= 100 and a.note > 10) then (a.note/100)::double precision else 
			case when (a.note <= 1000 and a.note > 100) then (a.note/1000)::double precision else 
				case when (a.note <= 10000 and a.note > 1000) then (a.note/10000)::double precision else a.note::double precision end 
			end
		end as note,
		a.flag_ponderation , b.ponderation, gua.code,
		case when flag_ponderation = 1 then 0 else ponderation end as pond, flag_is,n.matricule,n.matricule_notation, n.date_notation
		from cc_sr_indicateur_notation a 
		inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application
		inner join cc_sr_notation n on n.id_notation=a.id_notation
		left join cc_sr_grille g on g.id_grille=b.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		left join gu_application gua on gua.id_application = b.id_application
		WHERE 1=1 
		/*and b.id_projet=".$id_projet." */
		and (flag_is ilike '%".$valnf.";%' or flag_is ilike '%".$valnf."') and cg.id_type_traitement=".$id_type_traitement." ".$str."
		ORDER BY n.id_notation, cg.id_categorie_grille
		) as req1   
	group by id_notation,matricule,id_categorie_grille,libelle_categorie_grille,id_grille,libelle_grille,code,id_client
	order by matricule,id_notation,id_categorie_grille,libelle_categorie_grille,id_grille,libelle_grille,code,id_client
	) as req2 
group by id_categorie_grille,libelle_categorie_grille, id_grille, libelle_grille,code,id_client
order by id_grille";
		$is = $valnf;
		$query_nf345  = pg_query($sql) or die(pg_last_error());
		while($res_nf = pg_fetch_array($query_nf345))
		{
			if(($id_type_traitement == 1 || $id_type_traitement == 2) && $res_nf['id_client'] != 643) //client différent de DELAMAISON
			{
				$tableau[$id_type_traitement][$res_nf['code']]['detail_nf'][$is][$res_nf['id_grille']] = $res_nf['percent_type1'];
			}
			else
			{
				$tableau[$id_type_traitement][$res_nf['code']]['detail_nf'][$is][$res_nf['id_grille']] = $res_nf['percent_type3'];
			}
			$tableau[$id_type_traitement][$res_nf['code']]['libelle_grille'][$is][$res_nf['id_grille']] = $res_nf['libelle_grille'];
		}
		
	}
	return $tableau;
}

function getAllDonneesForExport($indnf,$id_type_traitement,$date_deb_notation,$date_fin_notation)
{
	global $conn;
	$sql = "select sum(is_type1) as is_type1
,sum(is_type3) as is_type3,
code,nom_application,id_application,id_type_traitement,nom_client,
count(id_notation) as nbeval
, ((sum(is_type1)::float/count(id_notation))*100) as percent_type1
, ((sum(is_type3)::float/count(id_notation))*100) as percent_type3
, id_projet, flag_is,id_grille
from (
	  select --sum(note*pond)/sum(pond) as somme_prod
	  case when sum(pond) = 0 then 0 else sum(note*pond)/sum(pond) end as somme_prod
	 ,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
	 ,case when sum(pond) = 0 then 1 else case when ((sum(note1*pond)/sum(pond))<100) then 0 else 1 end end as is_type3
	 ,id_application,code,nom_application,id_notation,id_type_traitement,nom_client,id_projet,flag_is,id_grille from (
		select b.id_projet,b.id_grille,a.id_notation,a.note as note1,
		case when (a.note <= 100 and a.note > 10) then (a.note/100)::double precision else 
			case when (a.note <= 1000 and a.note > 100) then (a.note/1000)::double precision else 
				case when (a.note <= 10000 and a.note > 1000) then (a.note/10000)::double precision else a.note::double precision end 
			end
		end as note,a.flag_ponderation , b.ponderation, cg.id_type_traitement,b.id_application,gua.code,gua.nom_application,
		case when flag_ponderation = 1 then 0 else ponderation end as pond, flag_is,n.matricule, n.matricule_notation, n.date_notation,guc.nom_client
		from cc_sr_indicateur_notation a 
		inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application
		inner join cc_sr_notation n on n.id_notation=a.id_notation
		left join cc_sr_grille g on g.id_grille=b.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		left join gu_application gua on gua.id_application = b.id_application
		left join gu_client guc on guc.id_client = gua.id_client
		WHERE 1=1 
		--and b.id_projet=".$id_projet." 
		and (flag_is ilike '%".$indnf.";%' or flag_is ilike '%".$indnf."')
		and cg.id_type_traitement=".$id_type_traitement." 
		--and n.date_notation >= '".$date_deb_notation."' and n.date_notation <= '".$date_fin_notation."'
		and n.date_entretien >= '".$date_deb_notation."' and n.date_entretien <= '".$date_fin_notation."'
		ORDER BY n.id_notation
		) as req1   
	group by code,nom_application,id_application,id_notation,id_type_traitement,nom_client,id_projet,flag_is,id_grille
	order by code,nom_application,id_application,id_notation,id_type_traitement,nom_client,id_projet,flag_is,id_grille
	) as req2 
	group by code,nom_application,id_application,id_type_traitement,nom_client,id_projet,flag_is,id_grille
	order by code";
}

function getNomClientById($id_client)
{
	global $conn;
	$sql = "select nom_client from gu_client where id_client = ".$id_client;
	$query  = pg_query($sql) or die(pg_last_error());
	$result = pg_fetch_array($query);
	return $result['nom_client'];
}

function getCodePrestationById($id_application)
{
	global $conn;
	$sql = "select code,nom_application from gu_application where id_application = ".$id_application;
	$query  = pg_query($sql) or die(pg_last_error());
	$result = pg_fetch_array($query);
	return $result;
}


function getAllNotation($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc,$id_type_appel)
{
	global $conn;
	$str = '';
	if($date_deb_notation != '')
	{
		//$str .= " and n.date_notation >= '".$date_deb_notation."' ";
		$str .= " and n.date_entretien >= '".$date_deb_notation."' ";
	}
	if($date_fin_notation != '')
	{
		//$str .= " and n.date_notation <= '".$date_fin_notation."' ";
		$str .= " and n.date_entretien <= '".$date_fin_notation."' ";
	}
	if($matricule_auditeur != 0)
	{
		$str .= " and n.matricule_notation = ".$matricule_auditeur." ";
	}
	if($matricule_tlc != 0)
	{
		$str .= " and n.matricule = ".$matricule_tlc." ";
	}
	if($id_type_appel != 0)
	{
		$str .= " and n.id_typologie = ".$id_type_appel." ";
	}
	$sql = "select distinct n.matricule, n.date_entretien, n.date_notation, n.matricule_notation, 
n.numero_dossier, n.numero_commande,typ.id_typologie,typ.libelle_typologie, f.nom_fichier, ga.id_projet, ga.id_client, ga.id_application,
guc.nom_client, gua.nom_application, gua.code, cg.id_type_traitement,n.id_notation,n.point_appui,n.point_amelioration,n.preconisation,n.note
from cc_sr_notation n 
inner join cc_sr_fichier f on f.id_fichier = n.id_fichier
inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation 
inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application
inner join gu_application gua on gua.id_application = ga.id_application
inner join gu_client guc on guc.id_client = ga.id_client 
inner join cc_sr_grille g on g.id_grille = ga.id_grille
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille 
left join cc_sr_typologie typ on typ.id_typologie = n.id_typologie
where 1=1 
and ga.id_client = ".$id_client." 
and ga.id_application = ".$id_application." 
and cg.id_type_traitement = ".$id_type_traitement." ".$str." order by n.date_notation desc";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getPrenomPersonnel($_matricule)
{
	global $conn;
	$sql = "select * from personnel where matricule = ".$_matricule." and actifpers = 'Active'";
	$query  = pg_query($sql) or die(pg_last_error());
	$result = pg_fetch_array($query);
	return $result['prenompersonnel'];
}

function getClientById($id_client,$id_application,$matricule_tlc,$matricule_auditeur,$id_type_appel)
{
	global $conn;
	$sql = "select * from gu_client guc where guc.id_client = ".$id_client;
	$query  = pg_query($sql) or die(pg_last_error());
	$result = pg_fetch_array($query);
	
	$sql1 = "select * from gu_application gua where gua.id_application = ".$id_application;
	$query1  = pg_query($sql1) or die(pg_last_error());
	$result1 = pg_fetch_array($query1);
	
	$sql2 = "select * from personnel p where p.matricule = ".$matricule_tlc;
	$query2  = pg_query($sql2) or die(pg_last_error());
	$result2 = pg_fetch_array($query2);
	
	$sql3 = "select * from personnel p where p.matricule = ".$matricule_auditeur;
	$query3  = pg_query($sql3) or die(pg_last_error());
	$result3 = pg_fetch_array($query3);
	
	$sql4 = "select * from cc_sr_typologie t where t.id_typologie = ".$id_type_appel;
	$query4  = pg_query($sql4) or die(pg_last_error());
	$result4 = pg_fetch_array($query4);
	
	return $result['nom_client'].'||'.$result1['code'].' - '.$result1['nom_application'].'||'.$result2['matricule'].' - '.$result2['prenompersonnel'].'||'.$result3['matricule'].' - '.$result3['prenompersonnel'].'||'.$result4['libelle_typologie'];
}

function getIS($id_projet, $id_client,$id_application,$id_type_traitement,$date_deb_notation,$date_fin_notation,$matricule_auditeur,$matricule_tlc)
{
	global $conn;
	
	$str = '';
	if($date_deb_notation != '')
	{
		//$str .= " and n.date_notation >= '".$date_deb_notation."' ";
		$str .= " and n.date_entretien >= '".$date_deb_notation."' ";
	}
	if($date_fin_notation != '')
	{
		//$str .= " and n.date_notation <= '".$date_fin_notation."' ";
		$str .= " and n.date_entretien <= '".$date_fin_notation."' ";
	}
	if($matricule_auditeur != 0)
	{
		$str .= " and n.matricule_notation = ".$matricule_auditeur." ";
	}
	if($matricule_tlc != 0)
	{
		$str .= " and n.matricule = ".$matricule_tlc." ";
	}
	/**
	* 
	* @var *********************** Indicateur NF *****************************
	* 
	*/
	/*for($i=4;$i<=7;$i++)
	{*/
	$tableaunf = get_indicateur_nf();
	foreach($tableaunf as $keynf => $valnf)
	{
		$sql = "select --sum(note*pond)/sum(pond) as somme_prod
		  case when sum(pond) = 0 then 0 else sum(note*pond)/sum(pond) end as somme_prod
		 ,case when (sum(note*pond) = sum(pond)) then 1 else 0 end as is_type1
		 ,case when sum(pond) = 0 then 1 else case when ((sum(note1*pond)/sum(pond))<100) then 0 else 1 end end as is_type3
		 ,matricule,id_notation,id_client from (
			select b.id_client,a.id_notation,a.id_grille,a.note as note1
			,case when (a.note <= 100 and a.note > 10) then (a.note/100)::double precision else 
				case when (a.note <= 1000 and a.note > 100) then (a.note/1000)::double precision else 
					case when (a.note <= 10000 and a.note > 1000) then (a.note/10000)::double precision else a.note::double precision end 
				end
			end as note
			,a.flag_ponderation , b.ponderation,
			case when flag_ponderation = 1 then 0 else ponderation end as pond, flag_is,n.matricule, n.matricule_notation, n.date_notation
			from cc_sr_indicateur_notation a 
			inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application
			inner join cc_sr_notation n on n.id_notation=a.id_notation
			left join cc_sr_grille g on g.id_grille=b.id_grille
			left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
			WHERE 
			b.id_projet=".$id_projet." and (flag_is ilike '%".$valnf.";%' or flag_is ilike '%".$valnf."') and cg.id_type_traitement=".$id_type_traitement." ".$str."
			ORDER BY n.id_notation
			) as req1   group by id_notation,matricule,id_client
			  order by matricule,id_notation";
		$is = $valnf;
		//echo $sql.'<br><br>';
		$query_nf345  = pg_query($sql) or die(pg_last_error());
		while($res_nf = pg_fetch_array($query_nf345))
		{
			if(($id_type_traitement == 1 || $id_type_traitement == 2) && $res_nf['id_client'] != 643) //client différent de DELAMAISON
			{
				$tableau[$res_nf['id_notation']][$is] = $res_nf['is_type1'];
			}
			else
			{
				$tableau[$res_nf['id_notation']][$is] = $res_nf['is_type3'];
			}
		}
	}
	
	/**
	* 
	* @var **************** Situation inacceptable **********************************
	* 
	*/
	$sql = "select count(commentaire_si) as nb_csi ,id_notation,matricule,id_repartition from (
		select a.commentaire_si ,n.id_notation,n.matricule,b.id_repartition,n.matricule_notation, n.date_notation
		from cc_sr_indicateur_notation a 
		inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application 
		inner join cc_sr_notation n on n.id_notation = a.id_notation
		left join cc_sr_grille g on g.id_grille=b.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		where b.id_client = ".$id_client."
		and b.id_application = ".$id_application."
		and a.commentaire_si != ''  
		and cg.id_type_traitement=".$id_type_traitement." ".$str."
		order by n.matricule,n.id_notation,b.id_repartition ) as req1
	group by id_notation,matricule,id_repartition
	order by matricule,id_notation,id_repartition";
	$query_si  = pg_query($sql) or die(pg_last_error());
	while($res_si = pg_fetch_array($query_si))
	{
		$tableau[$res_si['id_notation']][$res_si['id_repartition']] = $res_si['nb_csi'];
	}
	return $tableau;
}

function getDonneesForExport($id_projet,$id_client,$id_application,$ftxt_dtdeb_,$ftxt_dtfin_,$id_type_traitement,$id_type_appel)
{
	global $conn;
      if($id_type_appel != 0)
	{
		$str .= " n.id_typologie = ".$id_type_appel." and ";
	}
	$sql = "select distinct ga.id_projet, ga.id_client, guc.nom_client, ga.id_application, gua.code, n.matricule_notation, p.prenompersonnel,cg.id_type_traitement  
	from cc_sr_grille_application ga 
inner join cc_sr_indicateur_notation inot on inot.id_grille_application = ga.id_grille_application
inner join cc_sr_notation n on n.id_notation = inot.id_notation
inner join personnel p on p.matricule = n.matricule_notation 
inner join gu_client guc on guc.id_client = ga.id_client 
inner join gu_application gua on gua.id_application = ga.id_application
inner join cc_sr_grille g on g.id_grille= ga.id_grille
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille= g.id_categorie_grille
where ".$str." ga.id_projet = ".$id_projet." and ga.id_client = ".$id_client." and ga.id_application = ".$id_application
." and cg.id_type_traitement = ".$id_type_traitement." 
--and date_notation >= '".$ftxt_dtdeb_."' and date_notation <= '".$ftxt_dtfin_." 
and date_entretien >= '".$ftxt_dtdeb_."' and date_entretien <= '".$ftxt_dtfin_."'";
	$query = pg_query($sql) or die(pg_last_error());
	return $query;
}

?>
