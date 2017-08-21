<?php
include("/var/www.cache/dgconn.inc");

if(isset($_REQUEST['contenu']))
{
	$id_projet          = $_REQUEST['idprojet_penalite'];
	$id_client          = $_REQUEST['idprojet_penalite'];
	$id_application     = $_REQUEST['idprojet_penalite'];
	$id_type_traitement = $_REQUEST['idtypetraitement_penalite'];
	$id_classement      = $_REQUEST['idclassement_penalite'];
	echo setTableauPenalite($id_projet,$id_classement,$id_type_traitement);
}

if(isset($_REQUEST['suppression_penalite_projet']))
{
	$id_projet          = $_REQUEST['id_projet'];
	$id_type_traitement = $_REQUEST['id_type_traitement'];
	echo suppressionPenaliteProjet($id_projet,$id_type_traitement).'||'.fetchAllProjetPenaliteByProjet($id_projet,$id_type_traitement);
}

if(isset($_REQUEST['donnees']))
{
	$donnees = $_REQUEST['donnees'];
	if($donnees == 'update')
	{
		$id_projet_penalite = $_REQUEST['id_projet_penalite'];
		echo getForUpdate($id_projet_penalite);
	}
	else if($donnees == 'delete')
	{
		$id_projet_penalite = $_REQUEST['id_projet_penalite'];
		echo setDeletePenalite($id_projet_penalite);
	}
	else if($donnees == 'ajout')
	{
		$id_projet          = $_REQUEST['id_projet'];
		$id_classement      = $_REQUEST['id_classement'];
		$condition          = $_REQUEST['condition'];
		$valeur_condition   = $_REQUEST['valeur_condition'];
		$penalite           = $_REQUEST['penalite'];
		$id_projet_penalite = $_REQUEST['id_projet_penalite'];
		$id_type_traitement = $_REQUEST['id_type_traitement'];
		echo setUpdatePenalite($id_projet,$id_classement,$condition,$valeur_condition,$penalite,$id_projet_penalite,$id_type_traitement);
	}
}

if(isset($_REQUEST['verification_projet']))
{
	$id_projet          = $_REQUEST['id_projet'];
	$id_type_traitement = $_REQUEST['id_type_traitement'];
	echo fetchAllProjetPenaliteByProjet($id_projet,$id_type_traitement);
}

function setUpdatePenalite($id_projet,$id_classement,$condition,$valeur_condition,$penalite,$id_projet_penalite,$id_type_traitement)
{
	global $conn;
	if($id_projet_penalite == '' || $id_projet_penalite == 0)
	{
		$sql = "INSERT INTO cc_sr_projet_penalite(id_projet, flag_condition, valeur, penalite, id_classement,id_type_traitement) VALUES ( ".$id_projet.", ".$condition.", ".$valeur_condition.", ".$penalite.", ".$id_classement.", ".$id_type_traitement.")";
		$query  = pg_query($sql) or die(pg_last_error());
		$result = 1;
	}
	else
	{
		$sql = "UPDATE cc_sr_projet_penalite SET id_projet=".$id_projet.", flag_condition=".$condition.", valeur=".$valeur_condition.", penalite=".$penalite.", id_classement=".$id_classement." WHERE id_projet_penalite = ".$id_projet_penalite;
		$query  = pg_query($sql) or die(pg_last_error());
		$result = 2;
	}
	if($query)
	{
		return $result;
	}
}

function setDeletePenalite($id_projet_penalite)
{
	global $conn;;
	$sql = "DELETE FROM cc_sr_projet_penalite WHERE id_projet_penalite = ".$id_projet_penalite.";";
	$query  = pg_query($sql) or die(pg_last_error());
	if($query)
	{
		echo 'Suppression avec succès !';
	}
}

function getForUpdate($id_projet_penalite)
{
	global $conn;
	$sql = "select * from cc_sr_projet_penalite where id_projet_penalite = ".$id_projet_penalite;
	$query  = pg_query($sql) or die(pg_last_error());
	$result = pg_fetch_array($query);
	$flag_condition = $result['flag_condition'];
	$valeur = $result['valeur'];
	$penalite = $result['penalite'];
	return $flag_condition.'&&&'.$valeur.'&&&'.$penalite;
}

function fetchAllClientForPenalite($variable)
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

function suppressionPenaliteProjet($id_projet,$id_type_traitement)
{
	global $conn;
	$sql = "delete FROM cc_sr_projet_penalite where id_projet = ".$id_projet." and id_type_traitement = ".$id_type_traitement;
	$query  = pg_query($sql) or die(pg_last_error());
	if($query)
	{
		return 1;
	}
	else
	{
		return -1;
	}
}

function getAllClassementForPenalite_($id_projet)
{
	global $conn;
	$sql = "select * from cc_sr_projet_penalite where id_projet = ".$id_projet;
	$query  = pg_query($sql) or die(pg_last_error());
    return $query;
}

function getAllClassementForPenalite()
{
	global $conn;
	$sql = "select * from cc_sr_classement order by section, ordre";
	$query  = pg_query($sql) or die(pg_last_error());
    return $query;
}

function setTableauPenalite_()
{
	$result_table = getAllClassementForPenalite();
	$str = '<table>
	<tr>
		<th>Classement</th>
		<th>Condition</th>
		<th>Nombre situation Inacceptable</th>
		<th>Pénalité</th>
	</tr>
	';
	while ($res = pg_fetch_array($result_table))
	{
		$str .= '<tr>
		<td>'.$res['libelle_classement'].'</td>
		<td>
			<select>
				<option value="0">Egal à</option>
				<option value="1">Inférieur à</option>
				<option value="2">Supérieur à</option>
				<option value="3">Inférieur ou égal à</option>
				<option value="4">Supérieur ou égal à</option>
			</select>
		</td>
		<td><input type="text" id="id_nb_si_'.$res['id_classement'].'" /></td>
		<td><input type="text" id="id_valeur_penalite_'.$res['id_classement'].'" /></td>
		</tr>';
	}
	$str .= '</table>';
	$str .= '<div style="width:100px;display:block;position:relative;margin:10px 0 0 84%">
 	 <input type="button" id="id_save_penalite" value="Enregistrer" class="btn_enreg" onclick="save_penalite();" />
 	 </div>';
	echo $str;
}

function setTableauPenalite($id_projet,$id_classement,$id_type_traitement)
{
	/**
	* 
	* 
		<option value="0">Egal à</option>
		<option value="1">Inférieur à</option>
		<option value="2">Supérieur à</option>
		<option value="3">Inférieur ou égal à</option>
		<option value="4">Supérieur ou égal à</option>
	* 
	*/
	global $conn;
	$sql_classement = "select * from cc_sr_classement where id_classement = ".$id_classement;
	$query_classement  = pg_query($sql_classement) or die(pg_last_error());
	$result_classement = pg_fetch_array($query_classement);
	$libelle_classement = $result_classement['libelle_classement'];
	
	$sql = "select * from cc_sr_projet_penalite where id_projet = ".$id_projet." and id_classement = ".$id_classement." and id_type_traitement = ".$id_type_traitement." order by id_projet_penalite";
	$query  = pg_query($sql) or die(pg_last_error());
	
	$str = '';
	$str .= '
		<div class="div_titre_classement"><span>'.$libelle_classement.'</span></div>
		<div class="div_table_new">
			<table id="id_table_new_penalite" class="class_table_contenu">
				<thead>
				<tr><th>Condition</th><th>Valeur Pénalité</th></tr>
				</thead>
				<tbody>
				<tr>
					<td class="td_contenu">
						<input type="hidden" name="id_projet_penalite" id="idprojetpenalite" value="">
						<select name="condition" id="id_condition">
							<option value="0">Egal </option>
							<option value="1">Inférieur </option>
							<option value="2">Supérieur </option>
							<option value="3">Inférieur ou égal </option>
							<option value="4">Supérieur ou égal </option>
						</select>
						à
						<input type="number" name="valeur_condition" id="id_valeur_condition" placeholder="Valeur" onkeypress="return isNumber(event)">
					</td>

					<td class="td_contenu">
						<input type="number" id="id_penalite" name="penalite" placeholder="Pénalité" style="width: 100px;" onkeypress="return isNumber(event)" />
					</td>
				</tr>
					
				<tr>
					<td colspan="2"><center><input class="class_btn" type="button" onclick="ajout_penalite('.$id_projet.','.$id_classement.')" id="id_ajouter_penalite" value="Ajouter">
					<input class="class_btn" type="button" onclick="nouveau_penalite()" id="id_nouveau_penalite" value="Nouveau"></center></td>
				</tr>
				</tbody>
			</table>
		</div>';
	$str .= '<div class="div_table_contenu">
			<table id="id_table_contenu_penalite" class="class_table_contenu">
				<thead>
				<tr><th>Condition</th><th>Valeur Pénalité</th><th>Action</th></tr>
				</thead>
				<tbody>
				';
		while($res_pen = pg_fetch_array($query))
		{
			if($res_pen['flag_condition'] == 0) $condition = 'Egal à '.$res_pen['valeur'];
			if($res_pen['flag_condition'] == 1) $condition = 'Inférieur à '.$res_pen['valeur'];
			if($res_pen['flag_condition'] == 2) $condition = 'Supérieur à '.$res_pen['valeur'];
			if($res_pen['flag_condition'] == 3) $condition = 'Inférieur ou égal à '.$res_pen['valeur'];
			if($res_pen['flag_condition'] == 4) $condition = 'Supérieur ou égal à '.$res_pen['valeur'];
			$str .= '<tr>
					<td class="td_contenu">
						<input type="hidden" id="id_projet_penalite_contenu" value="'.$res_pen['id_projet_penalite'].'" />
						<span>'.$condition.'</span>
					</td>

					<td class="td_contenu">
						<span>'.$res_pen['penalite'].'</span>
					</td>
					
					<td class="td_contenu">
					<span style="margin:auto">
					<center>
					<img width="15px" height="15px" onclick="setUpdatePenalite('.$res_pen['id_projet_penalite'].')" style="cursor:pointer" title="Editer" src="images/edit.png">
					<img width="15px" height="15px" onclick="setDeletePenalite('.$res_pen['id_projet_penalite'].','.$id_classement.')" style="cursor:pointer" title="Supprimer" src="images/delete.png">
					</center>
					</span></td>
					</tr>';
		}
	$str .= '</tbody>
			</table>
		</div>';
	return $str;
}

function fetchAllProjetPenaliteByProjet($id_projet, $id_type_traitement)
{
	global $conn;
	$sql = "select * from cc_sr_projet_penalite where id_projet = ".$id_projet." and id_type_traitement = ".$id_type_traitement;
	$query  = pg_query($sql) or die(pg_last_error());
	
	$nombre = pg_num_rows($query);
	$str = '<center>';
 	if($nombre == 0)
 	{
		$str .= '<input disabled type="button" class="class_btn_" value="Aucune pénalité pour ce projet" name="suppression_penalite" style="margin:5px;" />';
		$sql = 'update cc_sr_projet set flag_penalite = 0 where id_projet = '.$id_projet;
		$query  = pg_query($sql) or die(pg_last_error());
	}
	else
	{
		$str .= '<input type="button" class="class_btn" value="Supprimer la pénalité pour ce projet" name="suppression_penalite" title="Suppression de la pénalité pour le client en cours" style="margin:5px;" onclick="suppression_penalite_projet();" />';
		$sql = 'update cc_sr_projet set flag_penalite = 1 where id_projet = '.$id_projet;
		$query  = pg_query($sql) or die(pg_last_error());
	}
	$str .= '</center>';
	
	return $str;
}

function fetchAllTypeTraitement()
{
	global $conn;
	$sql = "select * from cc_sr_type_traitement ORDER BY id_type_traitement";
	$query  = pg_query($sql) or die(pg_last_error());
	
	return $query;
}

?>