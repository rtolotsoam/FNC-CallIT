<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("/var/www.cache/dgconn.inc");

if(isset($_REQUEST['champ_typo']))
{
	$id_client = $_REQUEST['id_client_typo'];
	$id_application = $_REQUEST['id_application_typo'];
	$id_projet = $_REQUEST['id_projet_typo'];
	$champ_typo = $_REQUEST['champ_typo'];
	echo setFiltreTypologie($id_projet,$id_client,$id_application,$champ_typo);
}

if(isset($_REQUEST['setTableau']))
{
	$id_client = $_REQUEST['id_client_typo'];
	$id_application = $_REQUEST['id_application_typo'];
	$id_projet = $_REQUEST['id_projet_typo'];
	echo getTypoByProjet($id_projet);
}

if(isset($_REQUEST['action']))
{
	if($_REQUEST['action']=='save')
	{
		$libelle = $_REQUEST['libelle_typo'];
		$id_typologie = $_REQUEST['id_typologie'];
		$sql = "update cc_sr_typologie set libelle_typologie='".pg_escape_string($libelle)."' where id_typologie = ".$id_typologie;	
		$query  = pg_query($sql) or die(pg_last_error());
	}
	if($_REQUEST['action']=='delete')
	{
		$id_typologie = $_REQUEST['id_typologie'];
		$id_projet = $_REQUEST['id_projet'];
		$sql = "delete from cc_sr_typologie where id_typologie = ".$id_typologie;
		$query  = pg_query($sql) or die(pg_last_error());
		echo getTypoByProjet($id_projet);
	}
	if($_REQUEST['action']=='add')
	{
		$libelle = $_REQUEST['libelle_typo'];
		$id_projet = $_REQUEST['id_projet'];
		$sql = "insert into cc_sr_typologie(id_projet,libelle_typologie) values(".$id_projet.",'".pg_escape_string($libelle)."')";
		$query  = pg_query($sql) or die(pg_last_error());
		echo getTypoByProjet($id_projet);
	}
}

function setFiltreTypologie($id_projet,$id_client,$id_application,$champ_typo)
{
	global $conn;
	if($champ_typo == 'client')
	{
		if ($id_client != 0 && $id_client != '')
		{
			$_str = " and a.id_client = ".$id_client." ";
		}
		$sql = "select id_application, code, nom_application from ( select a.id_projet, a.nom_projet, a.id_client, c.nom_client, a.id_application, b.code, 
		b.nom_application, a.archivage from cc_sr_projet a inner join gu_application b on a.id_application = b.id_application 
		inner join gu_client c on c.id_client = a.id_client 
		where  a.archivage = 1 ".$_str."
		order by a.date_modification ) as one group by id_application, code, nom_application order by code";
		$query  = pg_query($sql) or die(pg_last_error());
		$str = "<option value='0'>-- Choix --</option>";
		while($res = pg_fetch_array($query))
		{
			$str .= "<option value='".$res['id_application']."'>".$res['code']." - ".$res['nom_application']."</option>";
		}
	}
	else if($champ_typo == 'code')
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

function fetchAllProjectTypologie($variable)
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

function getTypoByProjet($id_projet)
{
	global $conn;
	$sql = "select * from cc_sr_typologie where id_projet = ".$id_projet." order by id_typologie";
	$query  = pg_query($sql) or die(pg_last_error());
	$str = '';
	$str .= '<table class="tablesorter1">
		<tr>
			<th style="text-align:center;width:80%;">Libell&eacute; typologie</th>
	 	 	<th style="text-align:center;width:20%;">Action</th>
	 	</tr>';
	while($res = pg_fetch_array($query))
	{
		$str .= '<tr>
			<td>
				<input type="text" id="id_edit_typologie_'.$res['id_typologie'].'" value="" style="display:none;width:98%;font-size:11px;font-family:verdana;margin:auto;" />
				<span id="id_libelle_typologie_'.$res['id_typologie'].'" style="padding:0 0 0 10px;">'.utf8_decode($res['libelle_typologie']).'</span>
			</td>
			<td style="text-align:center">
			<img id="id_img_edit_typo_'.$res['id_typologie'].'" src="images/edit.png" width="17px" height="17px" style="cursor:pointer;" title="Editer la typologie" onclick=editTypo('.$res['id_typologie'].') />
			<img id="id_img_save_typo_'.$res['id_typologie'].'" src="images/save2.png" width="17px" height="17px" style="cursor:pointer;display:none;" title="Sauver la typologie" onclick=saveTypo('.$res['id_typologie'].') />
			&nbsp;&nbsp;
			<img id="id_img_delete_typo_'.$res['id_typologie'].'" src="images/delete.png" width="17px" height="17px" style="cursor:pointer;" title="Supprimer la typologie" onclick=deleteTypo('.$res['id_typologie'].') />
			</td>
		</tr>';
	}
 	 $str .= '</table>';
 	 return $str;
}
?>