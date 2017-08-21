<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include("/var/www.cache/dgconn.inc");
include('function_union.php');

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'supprimer')
{
	$id_notation = $_REQUEST['id_notation'];
	echo supprimer_notation($id_notation);
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'verification')
{
	$id_notation = $_REQUEST['id_notation'];
	echo verifFiche($id_notation);
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'prestation')
{
	$id_client = $_REQUEST['id_client'];
	echo getValeurPresta($id_client);
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'client')
{
	$id_prestation = $_REQUEST['id_prestation'];
	if($id_prestation == 0 || $id_prestation == '0')
	{
		echo '0';
	}
	else
	{
		echo getValeurClient($id_prestation);
	}	
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'reinitialisation')
{
	echo getAllPrestationForFiltre();
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'consultation')
{
	$id_notation = $_REQUEST['id_notation'];
	echo getAllValeurForNotation($id_notation);
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'visualiser')
{
	$tableau = array();
	$tableau['filtre_cc'] = $_REQUEST['filtre_cc'];
	$tableau['filtre_evaluateur'] = $_REQUEST['filtre_evaluateur'];
	$tableau['filtre_fichier'] = $_REQUEST['filtre_fichier'];
	
	$val = $_REQUEST['filtre_date_notation_deb'];
	if($val != '')
	{
		$val = explode('/',$val);
		$filtre_date_notation_deb = $val[2].'-'.$val[1].'-'.$val[0];
		$tableau['filtre_date_notation_deb'] = $filtre_date_notation_deb;
	}
	else
	{
		$tableau['filtre_date_notation_deb'] = '';
	}
	
	$val = $_REQUEST['filtre_date_notation_fin'];
	if($val != '')
	{
		$val = explode('/',$val);
		$filtre_date_notation_fin = $val[2].'-'.$val[1].'-'.$val[0];
		$tableau['filtre_date_notation_fin'] = $filtre_date_notation_fin;
	}
	else
	{
		$tableau['filtre_date_notation_fin'] = '';
	}
	
	$val = $_REQUEST['filtre_date_appel_deb'];
	if($val != '')
	{
		$val = explode('/',$val);
		$filtre_date_appel_deb = $val[2].'-'.$val[1].'-'.$val[0];
		$tableau['filtre_date_appel_deb'] = $filtre_date_appel_deb;
	}
	else
	{
		$tableau['filtre_date_appel_deb'] = '';
	}
	
	$val = $_REQUEST['filtre_date_appel_fin'];
	if($val != '')
	{
		$val = explode('/',$val);
		$filtre_date_appel_fin = $val[2].'-'.$val[1].'-'.$val[0];
		$tableau['filtre_date_appel_fin'] = $filtre_date_appel_fin;
	}
	else
	{
		$tableau['filtre_date_appel_fin'] = '';
	}
	
	$tableau['filtre_type_traitement'] = $_REQUEST['filtre_type_traitement'];
	$tableau['filtre_client'] = $_REQUEST['filtre_client'];
	$tableau['filtre_prestation'] = $_REQUEST['filtre_prestation'];
	$tableau['filtre_type_appel'] = $_REQUEST['filtre_type_appel'];
	$tableau['id_note_filtre'] = $_REQUEST['id_note_filtre'];
	$tableau['id_valeur_note_1'] = $_REQUEST['id_valeur_note_1'];
	$tableau['id_valeur_note_2'] = $_REQUEST['id_valeur_note_2'];
	
	$tableau['acces_suppr'] = $_REQUEST['acces_suppr'];
	echo setTableauAllNotation($tableau);
}

function filtreNoteConsultation($matr,$note,$id_note,$id_note_1,$id_note_2)
{
	if($id_note != 0)
	{
		if($id_note == 1) // Egal à
		{
			if($id_note_1 == $note) return 0;
			else return 1;
		}
		if($id_note == 2) // Entre
		{
			if($note >= $id_note_1 && $note <= $id_note_2) return 0;
			else return 1;
		}
		if($id_note == 3) // Inférieur à
		{
			if($note < $id_note_1) return 0;
			else return 1;
		}
		if($id_note == 4) // Inférieur ou Egal à
		{
			if($note <= $id_note_1) return 0;
			else return 1;
		}
		if($id_note == 5) // Supérieur à
		{
			if($note > $id_note_1) return 0;
			else return 1;
		}
		if($id_note == 6) // Supérieur ou Egal à
		{
			if($note >= $id_note_1) return 0;
			else return 1;
		}
	}
	else
	{
		return 0;
	}
}

function getAllValeurForNotation($id_notation)
{
	global $conn;
	$sql = "select distinct n.id_notation,n.matricule, ga.id_projet, ga.id_client, ga.id_application, cg.id_type_traitement, f.nom_fichier, f.id_fichier from cc_sr_notation n 
inner join cc_sr_fichier f on f.id_fichier = n.id_fichier 
inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation 
inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application 
inner join cc_sr_grille g on g.id_grille = ga.id_grille 
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
where n.id_notation = ".$id_notation;	
	$query  = pg_query($sql) or die(pg_last_error());
	$result = pg_fetch_array($query);
	// id_notation(0) ### matricule(1) ### id_projet(2) ### id_client(3) ### id_application(4) ### id_type_traitement(5) ### nom_fichier(6) ### id_fichier(7)
	return $result['id_notation'].'###'.$result['matricule'].'###'.$result['id_projet'].'###'.$result['id_client'].'###'.$result['id_application'].'###'.$result['id_type_traitement'].'###'.utf8_decode($result['nom_fichier']).'###'.$result['id_fichier'];
}

function fetchAllForGestionNotation($tableau)
{
	global $conn;
	
	$str = '';
	if($tableau['filtre_cc'] != 0)
	{
		$str .= " and n.matricule = ".$tableau['filtre_cc'];
	}
	if($tableau['filtre_evaluateur'] != 0)
	{
		$str .= " and n.matricule_notation = ".$tableau['filtre_evaluateur'];
	}
	if($tableau['filtre_type_traitement'] != 0)
	{
		$str .= " and cg.id_type_traitement = ".$tableau['filtre_type_traitement'];
	}
	if($tableau['filtre_fichier'] != 0)
	{
		$str .= " and n.id_fichier = ".$tableau['filtre_fichier'];
	}
	if($tableau['filtre_date_notation_deb'] != '')
	{
		$str .= " and n.date_notation >= '".$tableau['filtre_date_notation_deb']."'";
	}
	if($tableau['filtre_date_notation_fin'] != '')
	{
		$str .= " and n.date_notation <= '".$tableau['filtre_date_notation_fin']."'";
	}
	if($tableau['filtre_date_appel_deb'] != '')
	{
		$str .= " and n.date_entretien >= '".$tableau['filtre_date_appel_deb']."'";
	}
	if($tableau['filtre_date_appel_fin'] != '')
	{
		$str .= " and n.date_entretien <= '".$tableau['filtre_date_appel_fin']."'";
	}
	if($tableau['filtre_client'] != 0 )
	{
		$str .= " and ga.id_client = ".$tableau['filtre_client'];
	}
	if($tableau['filtre_prestation'] != 0 )
	{
		$sql = "select p.id_projet from cc_sr_projet p where p.id_client = ".$tableau['filtre_client']." and p.id_application = ".$tableau['filtre_prestation']." and p.archivage = 1 limit 1";
		$query  = pg_query($sql) or die(pg_last_error());
		$res = pg_fetch_array($query);
		$id_projet = $res['id_projet'];
	
		$str .= " and n.id_projet = ".$id_projet;
	}
	if($tableau['filtre_type_appel'] != 0)
	{
		$str .= " and n.id_typologie = ".$tableau['filtre_type_appel'];
	}
	$sql = "select distinct n.*,
		f.nom_fichier,
		p.nompersonnel,
		p.prenompersonnel,
		p.fonctioncourante,
		prj.nom_projet,
		ga.id_client,
		ga.id_application,
		cg.id_type_traitement 
	from cc_sr_notation n 
		inner join cc_sr_indicateur_notation inot on n.id_notation = inot.id_notation 
		inner join cc_sr_fichier f on f.id_fichier = n.id_fichier 
		inner join personnel p on p.matricule = n.matricule 
		inner join cc_sr_projet prj on prj.id_projet = n.id_projet 
		inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application
		inner join cc_sr_grille g on g.id_grille = ga.id_grille
		inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
	where inot.id_grille_application is not null 
		and prj.archivage = 1
		".$str."
	order by date_notation";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getAllProjetForFiltre()
{
	global $conn;
	$sql = "select * from cc_sr_projet where archivage = 1 order by nom_projet";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getAllFichierForFiltre()
{
	global $conn;
	$sql = "select distinct f.id_fichier,f.nom_fichier from cc_sr_notation n 
	inner join cc_sr_fichier f on f.id_fichier = n.id_fichier 
	inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation
	where inot.id_grille_application is not null";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getAllCCForFiltre()
{
	global $conn;
	$sql = "select distinct p.matricule,p.prenompersonnel,p.fonctioncourante from cc_sr_notation n 
	inner join cc_sr_indicateur_notation inot on n.id_notation = inot.id_notation 
	inner join personnel p on p.matricule = n.matricule
	where inot.id_grille_application is not null 
	order by p.matricule";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getAllTypeForFiltre()
{
	global $conn;
	$sql = "select * from cc_sr_type_traitement order by id_type_traitement";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getAllClientForFiltre()
{
	global $conn;
	$sql = "select distinct guc.id_client, guc.nom_client from cc_sr_projet p 
	inner join gu_application gua on gua.id_application = p.id_application
	inner join gu_client guc on guc.id_client = gua.id_client 
	where p.archivage = 1
	order by nom_client";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getAllPrestationForFiltre()
{
	global $conn;
	$sql = "select gua.id_application, gua.code, gua.nom_application from cc_sr_projet p 
	inner join gu_application gua on gua.id_application = p.id_application
	inner join gu_client guc on guc.id_client = gua.id_client 
	where p.archivage = 1
	order by code";
	$query  = pg_query($sql) or die(pg_last_error());
	$str = '<option value="0">-- Choisir ici --</option>';
	while($res = pg_fetch_array($query))
	{
		$str .= '<option value="'.$res['id_application'].'">'.$res['code'].' - '.$res['nom_application'].'</option>';
	}
	return $str;
}

function getAllEvalForFiltre()
{
	global $conn;
	$sql = "select distinct p.matricule,p.prenompersonnel,p.fonctioncourante from cc_sr_notation n 
	inner join cc_sr_indicateur_notation inot on n.id_notation = inot.id_notation 
	inner join personnel p on p.matricule = n.matricule_notation
	where inot.id_grille_application is not null 
	order by p.matricule";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getValeurPresta($id_client)
{
	global $conn;
	$sql = "select gua.id_application, gua.code, gua.nom_application from cc_sr_projet p 
	inner join gu_application gua on gua.id_application = p.id_application
	inner join gu_client guc on guc.id_client = gua.id_client 
	where p.archivage = 1 
	and p.id_client = ".$id_client."
	order by code";
	$query  = pg_query($sql) or die(pg_last_error());
	$str = '<option value="0">-- Choisir ici --</option>';
	while($res = pg_fetch_array($query))
	{
		$str .= '<option value="'.$res['id_application'].'">'.$res['code'].' - '.$res['nom_application'].'</option>';
	}
	return $str;
}

function getValeurClient($id_prestation)
{
	global $conn;
	$sql = "select distinct guc.id_client, guc.nom_client, p.id_projet 
	from cc_sr_projet p 
	inner join gu_application gua on gua.id_application = p.id_application
	inner join gu_client guc on guc.id_client = gua.id_client 
	where p.archivage = 1 
	and p.id_application = ".$id_prestation."
	order by nom_client limit 1";
	$query  = pg_query($sql) or die(pg_last_error());
	$result = pg_fetch_array($query);
	$id_client = $result['id_client'];
	$id_projet = $result['id_projet'];
	
	$zHtml = $id_client;
	
	$sql_typologie = "select * from cc_sr_typologie
	where id_projet = ".$id_projet;
	$query  = pg_query($sql_typologie) or die(pg_last_error());
	if(pg_num_rows($query) > 0)
	{
		$zHtml .= '|||<option value=0>-- Choisir ici --</option>';
		while($res = pg_fetch_array($query))
		{
			$zHtml .= '<option value='.$res['id_typologie'].'>'.utf8_decode($res['libelle_typologie']).'</option>';
		}
	}
	else
	{
		$zHtml .= '|||0';
	}
	
	echo $zHtml;
}

function setTableauAllNotation($tableau)
{
	$str = '<table class="table_contenu_consultation">
	<thead>
		<tr>
			<th width="50px">Matricule</th>
			<th width="150px">Fichier</th>
			<th width="200px">Prestation - Client</th>
			<th width="50px">Date d\'appel</th>
			<th width="50px">Date de notation</th>
			<th width="50px">Evaluateur</th>
			<th width="100px">Numéro dossier</th>
			<th width="100px">Numéro commande</th>
			<th width="20px">Action</th>
			<th width="10px"></th>
		</tr>
	</thead></table>';
	$zHtml = '<center><table id="table_all_notation" class="table_contenu_consultation" style="width:100%">';
	$zHtml .= '
	<thead>
		<tr>
			<th width="3%">Type</th>
			<th width="15%">Matricule</th>
			<th width="4%">Note/10</th>
			<th width="10%">Fichier</th>
			<th width="15%">Prestation - Client</th>
			<th width="8%">Date d\'appel</th>
			<th width="8%">Date de notation</th>
			<th width="7%">Evaluateur</th>
			<th width="10%">Numéro dossier</th>
			<th width="10%">Numéro commande</th>
			<th width="10%">Action</th>
		</tr>
	</thead>';
	$zHtml    .= '<tbody>';
	$acces     = $tableau['acces_suppr'];
	$id_note   = $tableau['id_note_filtre'];
	$id_note_1 = $tableau['id_valeur_note_1'];
	$id_note_2 = $tableau['id_valeur_note_2'];
	$result    = fetchAllForGestionNotation($tableau);
	//$nb_enrg = pg_num_rows($result);
	$nb_enrg = 0;
	while ($res = pg_fetch_array($result))
	{
		$id_notation = $res['id_notation'];
		$id_type_traitement = $res['id_type_traitement'];
		$id_application = $res['id_application'];
		$id_client = $res['id_client'];
		$id_projet = $res['id_projet'];
		/*$str = calculTotalGeneral($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement);
		$table_valeur = explode('||',$str); 
		$note =  $table_valeur[0];*/
		if(($id_type_traitement == 1 || $id_type_traitement == 2) && $id_client != 643) //client différent de DELAMAISON
		{
			$note = number_format($res['note'],2);
		}
		else
		{
			$note = number_format($res['note']/10,2);
		}
		$val_affiche_ligne = filtreNoteConsultation($res['matricule'],$note,$id_note,$id_note_1,$id_note_2);
		
		if($val_affiche_ligne == 0)
		{
			if($res['id_type_traitement'] == 1) $typTrait = 'AE';
			if($res['id_type_traitement'] == 2) $typTrait = 'AS';
			if($res['id_type_traitement'] == 3) $typTrait = 'MAIL';
			if($res['id_type_traitement'] == 4) $typTrait = 'TCHAT';
			$zHtml .= '<tr>
			<td>'.$typTrait.'</td>
			<td>'.$res['matricule'].' - '.$res['prenompersonnel'].' ( '.$res['fonctioncourante'].' )</td>
			<td>'.$note.'</td>
			<td>'.utf8_decode($res['nom_fichier']).'</td>
			<td>'.$res['nom_projet'].'</td>
			<td>'.$res['date_entretien'].'</td>
			<td>'.$res['date_notation'].'</td>
			<td>'.$res['matricule_notation'].'</td>
			<td>'.$res['numero_dossier'].'</td>
			<td>'.$res['numero_commande'].'</td>
			<td style="text-align:center">
			<img src="images/consultation1.png" style="cursor:pointer;" height="16px" width="15px" onclick="set_consultation('.$res['id_notation'].')" />';
			if($acces == 1)
			{
				$zHtml .= '&nbsp;&nbsp;';
				$zHtml .= '<img src="images/delete.png" style="cursor:pointer;" height="16px" width="16px" onclick="suppression_notation('.$res['id_notation'].')" />';
			}
			
			$zHtml .= '</td>
			</tr>';
			$nb_enrg ++;
		}
	}
	
	$zHtml .= '</tbody>
	</table></center>';
	
	return $zHtml.'#*#'.$str.'#*#'.$nb_enrg;
}

function supprimer_notation($id_notation)
{
	global $conn;
	$sql = "delete from cc_sr_indicateur_notation where id_notation = ".$id_notation;
	$query  = pg_query($sql) or die(pg_last_error());
	$sql1 = "delete from cc_sr_notation where id_notation = ".$id_notation;
	$query1  = pg_query($sql1) or die(pg_last_error());
	$sql2 = "delete from nc_fiche where fnc_id_notation = ".$id_notation;
	$query2  = pg_query($sql2) or die(pg_last_error());
	if($query)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

function verifFiche($id_notation)
{
	global $conn;
	$sql = "select * from nc_fiche where fnc_id_notation = ".$id_notation;
	$query  = pg_query($sql) or die(pg_last_error());
	$nombre = pg_num_rows($query);
	return $nombre;
}

function supprimer_notation_nc_fiche($id_notation)
{
	global $conn;
	$sql = "delete from nc_fiche where fnc_id_notation = ".$id_notation;
	//$query  = pg_query($sql) or die(pg_last_error());
	if($query)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

?>