<?php
include ("/var/www.cache/dgconn.inc");

if(isset($_REQUEST['data_description_grille']))
{
	$id_grille_application = $_REQUEST['data_description_grille'];
	echo set_interface_description($id_grille_application);
}

if(isset($_REQUEST['data_ajout']) && isset($_REQUEST['id_grille_app']))
{
	$id_grille_application = $_REQUEST['id_grille_app'];
	$data = $_REQUEST['data_ajout'];
	insertData($data,$id_grille_application);
	echo set_interface_description($id_grille_application);
}

if(isset($_REQUEST['id_update_desc']))
{
	$id_grille_description = $_REQUEST['id_update_desc'];
	echo getDescriptionById($id_grille_description);
}

if(isset($_REQUEST['id_delete_desc']) && isset($_REQUEST['id_recup_desc']))
{
	$id_grille_description = $_REQUEST['id_delete_desc'];
	$id_grille_application = $_REQUEST['id_recup_desc'];
	deleteData($id_grille_description);
	echo set_interface_description($id_grille_application);
}


function getCategorieItem($id_projet,$id_application,$id_client) {
	global $conn;
	$sql = "select id_grille_application, a.id_grille, libelle_grille, c.id_categorie_grille,libelle_categorie_grille,c.id_type_traitement 
from cc_sr_grille_application a inner join cc_sr_grille b on a.id_grille = b.id_grille 
inner join cc_sr_categorie_grille c on c.id_categorie_grille = b.id_categorie_grille
where id_application = ".$id_application." and id_projet = ".$id_projet." and id_client = ".$id_client." 
order by c.id_type_traitement, c.ordre, b.ordre, c.id_categorie_grille";
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}

function getCategorie($id_grille_application) {
	global $conn;
	$sql = "select * from cc_sr_grille_application a 
	inner join cc_sr_grille b on a.id_grille = b.id_grille 
	inner join cc_sr_categorie_grille c on b.id_categorie_grille = c.id_categorie_grille
	where id_grille_application = ".$id_grille_application;
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}

function getDescription($id_grille_application) {
	global $conn;
	$sql = "select * from cc_sr_grille_description 
	where id_grille_application = ".$id_grille_application." order by note desc";
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}

function getDescriptionById($id_grille_description) {
	global $conn;
	$sql = "select * from cc_sr_grille_description 
	where id_grille_description = ".$id_grille_description;
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	
	$res = pg_fetch_array($query);
	$str = $res['id_grille_description']."|||".$res['id_grille_application']."|||".$res['note']."|||".$res['libelle_description'];
	return $str;
	
}

function set_interface_description($id_grille_application)
{
	$result = getCategorie($id_grille_application);
	$res = pg_fetch_array($result);
	$str = "";
	
	$str = "<table id='id_titre_desc' border=1>
	<tr>
	<th class='titre_th' style='color:#CC0000;'>".$res['libelle_categorie_grille']."</th>
	</tr>
	<tr>
	<td class='titre_td' style='color:#195A92;'>".$res['libelle_grille']."</td>
	</tr>
	</table>";
	
	$str .= '</br>';
	
	$str .= "<form id='id_form_desc'><table id='id_titre_table_desc'>";
	$str .= "<tr>
	<td><input type='hidden' id='id_grille_description' name='id_grille_description' />
	<input onkeypress='return isNumber(event)' type='text' placeholder='Note' id='id_note' name='note' style='margin: -21px -35px 3px 0;padding: 5px;width: 50px;' /></td>
	<td><textarea placeholder='Saisir la description' style='width:100%' id='id_desc' name='description'></textarea></td>
	</tr>
	<tr>
	<td></td>
	<td><center><input  type='button' value='Ajouter' id='id_ajouter_grille' onclick='ajout_desc(".$id_grille_application.")' />
	<input type='button' value='Nouveau' id='id_nouveau_grille' onclick='nouveau_desc()' /></center></td>
	</tr>";
	$str .= "</table></form>";
	
	$str .= "</br></br>";
	
	$str .= "<table border=1 id='id_table_desc'>
	<tr>
	<th width='15%'>Note</th>
	<th>Description</th>
	<th></th>
	</tr>";
	
	$result1 = getDescription($id_grille_application);
	while($res1 = pg_fetch_array($result1))
	{
		$str .= "<tr>";
		$str .= "<td style='text-align:center'>".$res1['note']."</td>";
		$str .= "<td>".$res1['libelle_description']."</td>";
		$str .= "<td width='10%'><span style='margin:auto'><center>
		<img src='images/edit.png' title='Editer' width='15px' height='15px' style='cursor:pointer' onclick='setUpdate(".$res1['id_grille_description'].")'/>
		<img src='images/delete.png' title='Supprimer' width='15px' height='15px' style='cursor:pointer' onclick='setDelete(".$res1['id_grille_description'].",".$id_grille_application.")' />
		</center></span></td>";
		$str .= "</tr>";
	}
	
	$str .= "</table>";
	
	return $str;
}


function insertData($data,$id_grille_application)
{
	global $conn;
	$tab = array();
	$tab = explode('&',$data);
	$table = array();
	for($i=0;$i<count($tab);$i++)
	{
		list($key,$val) = explode('=',$tab[$i]);
		$table[$key] = utf8_decode(trim(str_replace('+',' ',$val)));
	}
	if($table['id_grille_description'] == '')
	{
		$sql = "INSERT INTO cc_sr_grille_description(id_grille_application, note, libelle_description)
	    VALUES (".$id_grille_application.",".$table['note'].",'".pg_escape_string($table['description'])."');";
	}
	else 
	{
		$sql = "UPDATE cc_sr_grille_description
   SET id_grille_application=".$id_grille_application.", note=".$table['note'].", libelle_description='".pg_escape_string($table['description'])."'
 WHERE id_grille_description = ".$table['id_grille_description'].";";
	}
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}

function deleteData($id_grille_description)
{
	global $conn;
	$sql = "DELETE FROM cc_sr_grille_description
 WHERE id_grille_description = ".$id_grille_description.";";
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}


?>