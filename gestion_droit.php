<?php
include("/var/www.cache/dgconn.inc");
//session_start();

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'insertion')
{
	$matricule_droit = $_REQUEST['matricule_droit'];
	$eval_droit = $_REQUEST['eval_droit'];
	$admin_droit = $_REQUEST['admin_droit'];
	echo insertDroit($matricule_droit,$eval_droit,$admin_droit);
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'rafraichir')
{
	echo setTableauListeContenu();
	echo ' #*#*# ';
	echo setListePersDroit();
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'suppression')
{
	$matricule_droit = $_REQUEST['matricule_droit'];
	echo deleteDroit($matricule_droit);
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'modification')
{
	$matricule_droit = $_REQUEST['matricule_droit'];
	$eval_droit = $_REQUEST['eval_droit'];
	$admin_droit = $_REQUEST['admin_droit'];
	echo updateDroit($matricule_droit,$eval_droit,$admin_droit);
}


// Menu Notation et synthèse
function getPersMenuNotation()
{
	global $conn;
	$tableau = array();
	/*$sql = "select * from personnel 
	where fonctioncourante in ('ACC','ISI','DQ','SUP CC','CPM','RESP RD')
	and actifpers = 'Active'
	order by fonctioncourante";*/
	//$sql = "select * from cc_sr_droit where evaluation_droit = 1 or evaluation_droit = 0 or admin_droit = 1";
	$sql = "select p.matricule from personnel p where p.actifpers = 'Active'";
	$query  = pg_query($sql) or die(pg_last_error());
	while($res = pg_fetch_array($query))
	{
		//$tableau[] = $res['matricule_droit'];
		$tableau[] = $res['matricule'];
	}
	return $tableau;
}

// Menu Projet
function getPersMenuProjet()
{
	global $conn;
	$tableau = array();
	/*$sql = "select * from personnel 
	where (fonctioncourante in ('ISI','DQ','RESP RD') OR (matricule in (5049)) )
	and actifpers = 'Active'
	order by fonctioncourante";*/
	$sql = "select * from cc_sr_droit where admin_droit = 1";
	$query  = pg_query($sql) or die(pg_last_error());
	while($res = pg_fetch_array($query))
	{
		$tableau[] = $res['matricule_droit'];
	}
	return $tableau;
}

function getPersAccesNotation()
{
	global $conn;
	$tableau = array();
	/*$sql = "select * from personnel 
	where fonctioncourante in ('ACC','ISI','DQ','SUP CC','CPM','RESP RD')
	and actifpers = 'Active'
	order by fonctioncourante";*/
	//$sql = "select * from cc_sr_droit";
	$sql = "select * from personnel p 
	left join cc_sr_droit d on p.matricule = d.matricule_droit
	where p.actifpers = 'Active'";
	$query  = pg_query($sql) or die(pg_last_error());
	while($res = pg_fetch_array($query))
	{
		if(isset($res['evaluation_droit']))
		{
			$tableau[$res['matricule']] = $res['evaluation_droit'];
		}
		else
		{
			$tableau[$res['matricule']] = 0;
		}
	}
	return $tableau;
}

function getAllPersonnelDroitAcces() // Contenu du tableau
{
	global $conn;
	/*$sql = "select nompersonnel,prenompersonnel,matricule,fonctioncourante, evaluation_droit, admin_droit 
	from personnel p 
	inner join cc_sr_droit d on p.matricule = d.matricule_droit
	where fonctioncourante in ('ACC','ISI','DQ','SUP CC','CPM','RESP RD','RP','RESP PLATEAU')
	and actifpers = 'Active'
	order by fonctioncourante, matricule";*/
	/*$sql = "select distinct a.personnel_id, b.matricule,b.prenompersonnel,b.fonctioncourante,b.actifpers, c.* 
	from intranet_personnel_gpe a
inner join personnel b on b.matricule = a.personnel_id 
inner join cc_sr_droit c on c.matricule_droit = a.personnel_id
where gpe_id in (78,81,25,74,99) and b.actifpers = 'Active'
order by matricule,b.fonctioncourante";*/
	$sql = "select * from personnel p inner join cc_sr_droit d on d.matricule_droit = p.matricule 
	order by matricule";
	$query  = pg_query($sql) or die(pg_last_error());

	return $query;
}

function getAllPersonnelDroit() // Liste dans le combobox
{
	global $conn;
	/*$sql = "select * from personnel where matricule not in (select matricule_droit from cc_sr_droit) 
	and actifpers = 'Active' 
	and fonctioncourante in ('ACC','ISI','DQ','SUP CC','CPM','RESP RD','RP','RESP PLATEAU') 
	order by fonctioncourante";*/
	/*$sql = "select * from (
select distinct a.personnel_id, b.matricule,b.prenompersonnel,b.fonctioncourante,b.actifpers, c.* from intranet_personnel_gpe a
inner join personnel b on b.matricule = a.personnel_id 
left join cc_sr_droit c on c.matricule_droit = a.personnel_id
where gpe_id in (78,81,25,74,99) and b.actifpers = 'Active'
order by matricule,b.fonctioncourante
) as req1 where id_droit is null";*/
	$sql = "select * from personnel p 
	where actifpers = 'Active' and matricule not in (select matricule_droit from cc_sr_droit) 
	order by matricule";
	$query  = pg_query($sql) or die(pg_last_error());

	return $query;
}

/*function fetchAllPersDroit()
{
	global $conn;
	$tableau = array();
	$sql = "select nompersonnel,prenompersonnel,matricule,fonctioncourante, evaluation_droit, admin_droit 
	from personnel p 
	left join cc_sr_droit d on p.matricule = d.matricule_droit
	where fonctioncourante in ('ACC','ISI','DQ','SUP CC','CPM','RESP RD')
	and actifpers = 'Active'
	order by fonctioncourante";
	$query  = pg_query($sql) or die(pg_last_error());
	/*while($res = pg_fetch_array($query))
	{
		$tableau['matricule'] = $res['matricule'];
		$tableau['nom'] = $res['nompersonnel'];
		$tableau['prenom'] = $res['prenompersonnel'];
		$tableau['fonction'] = $res['fonctioncourante'];
		$tableau['evaluation_droit'] = $res['evaluation_droit'];
		$tableau['admin_droit'] = $res['admin_droit'];
	}
	return $tableau;*/
	/*return $query;
}*/

function setTableauListeContenu()
{
	global $conn;
	
	$zHtml = '';
	$zHtml .= '<table id="id_table_droit"><thead>';
	$zHtml .= '<tr>';
	$zHtml .= '<th width="100px">Matricule</th>';
	$zHtml .= '<th width="300px">Pr&eacute;nom</th>';
	$zHtml .= '<th width="150px">Fonction</th>';
	$zHtml .= '<th width="50px">Eval</th>';
	$zHtml .= '<th width="50px">Admin</th>';
	$zHtml .= '<th width="50px">Action</th>';
	$zHtml .= '</tr></thead><tbody>';
	$result = getAllPersonnelDroitAcces();
	while($res = pg_fetch_array($result))
	{
		$zHtml .= '<tr>';
		$zHtml .= '<td style="text-align:center">'.$res['matricule'].'</td>';
		$zHtml .= '<td>'.$res['prenompersonnel'].'</td>';
		$zHtml .= '<td style="text-align:center">'.$res['fonctioncourante'].'</td>';
		$zHtml .= '<td style="text-align:center">';
		if ($res['evaluation_droit'] == 1)
		{
			$droit = 'checked="checked"';
		}
		else
		{
			$droit = '';
		}
		$zHtml .= '<input type="checkbox" '.$droit.' class="eval_droit" id="evaluation_droit_'.$res['matricule'].'" name="evaluation_droit" onclick="modification_droit('.$res['matricule'].')" />';
		$zHtml .= '</td>';
		$zHtml .= '<td style="text-align:center">';
		if ($res['admin_droit'] == 1)
		{
			$droit = 'checked="checked"';
		}
		else
		{
			$droit = '';
		}
		$zHtml .= '<input type="checkbox" '.$droit.' class="admin_droit" id="administration_droit_'.$res['matricule'].'" name="administration_droit" onclick="modification_droit('.$res['matricule'].')" />';

		$zHtml .= '</td>';
		$zHtml .= '<td style="text-align:center;">
		<img style="cursor:pointer;" src="images/delete.png" height="17px" width="17px" onclick="supprimer_droit('.$res['matricule'].');" />';
		$zHtml .= '</td>';
		$zHtml .= '</tr>';
	}
	$zHtml .= '</tbody></table>';
	
	return $zHtml;
}

function setListePersDroit()
{
	global $conn;
	$zHtml = '';
	$zHtml.= '<option value="0">-- Choix --</option>';
	$result = getAllPersonnelDroit();
	while($res = pg_fetch_array($result))
	{
		$zHtml .= '<option value="'.$res['matricule'].'">'.$res['matricule'].' - '.$res['prenompersonnel'].' ('.$res['fonctioncourante'].')</option>';
	}
	return $zHtml;
}

function insertDroit($matricule_droit,$eval_droit,$admin_droit)
{
	global $conn;
	$sql = "insert into cc_sr_droit (matricule_droit,evaluation_droit,admin_droit) values
	(".$matricule_droit.",".$eval_droit.",".$admin_droit.")";
	$query  = pg_query($sql) or die(pg_last_error());
	if($query)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

function deleteDroit($matricule_droit)
{
	global $conn;
	$sql = "DELETE FROM cc_sr_droit
	WHERE matricule_droit = ".$matricule_droit.";";
	$query  = pg_query($sql) or die(pg_last_error());
	if($query)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

function updateDroit($matricule_droit,$eval_droit,$admin_droit)
{
	global $conn;
	$sql = "UPDATE cc_sr_droit
	SET evaluation_droit=".$eval_droit.", admin_droit=".$admin_droit."
	WHERE matricule_droit = ".$matricule_droit.";";
	$query  = pg_query($sql) or die(pg_last_error());
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