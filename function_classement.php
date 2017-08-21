<?php
include("/var/www.cache/dgconn.inc");

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 'change_order_classement')
	{
		$rows_order = $_REQUEST['rows_order'];
		update_rows($rows_order);
	}
	if($action == 'edition')
	{
		$id_classement = $_REQUEST['id_classement'];
		echo getClassementById($id_classement);
	}
	if($action == 'suppression')
	{
		$id_classement = $_REQUEST['id_classement'];
		echo deleteClassementById($id_classement).'|||'.setTableauClassement();
	}
	if($action == 'save_ponderation_classement')
	{
		$data = $_REQUEST['data'];
		$data_sct = $_REQUEST['data_sct'];
		$id_projet = $_REQUEST['id_projet'];
		$id_client = $_REQUEST['id_client'];
		$id_application = $_REQUEST['id_application'];
		echo savePonderation($data,$data_sct,$id_projet,$id_client,$id_application);
	}
}

if(isset($_REQUEST['nom_classement']) && isset($_REQUEST['nom_section']))
{
	$nom_classement = $_REQUEST['nom_classement'];
	$nom_section = $_REQUEST['nom_section'];
	$id_classement = $_REQUEST['id_classement'];
	if($id_classement == '' || $id_classement == 0)
	{
		echo insertClassement($nom_classement,$nom_section).'|||'.setTableauClassement();
	}
	else 
	{
		echo updateClassement($nom_classement,$nom_section,$id_classement).'|||'.setTableauClassement();
	}
}

if(isset($_REQUEST['champ_class']))
{
	$id_client = $_REQUEST['id_client_class'];
	$id_application = $_REQUEST['id_application_class'];
	$id_projet = $_REQUEST['id_projet_class'];
	$champ_class = $_REQUEST['champ_class'];
	echo setFiltreClassement($id_projet,$id_client,$id_application,$champ_class);
}

if(isset($_REQUEST['setTableau']))
{
	$id_client = $_REQUEST['id_client_class'];
	$id_application = $_REQUEST['id_application_class'];
	$id_projet = $_REQUEST['id_projet_class'];
	echo getClassPondByProjet($id_projet,$id_client,$id_application);
}


function deleteClassementById($id_classement)
{
	global $conn;
	$sql = "delete from cc_sr_classement where id_classement = ".$id_classement;
	$result = pg_query($conn,$sql) or die (pg_last_error());
	
	$sql = "delete from cc_sr_grille_classement where id_classement = ".$id_classement;
	$result = pg_query($conn,$sql) or die (pg_last_error());
	
	if($result)
	{
		return 'Suppression avec succès !';
	}
}

function getClassementById($id_classement)
{
	global $conn;
	$sql = "select * from cc_sr_classement where id_classement = ".$id_classement;
	$result = pg_query($conn,$sql) or die (pg_last_error());
	$res = pg_fetch_array($result);
	return $res['libelle_classement'].'||'.$res['section'].'||'.$res['id_classement'];
}

function insertClassement($nom_classement,$nom_section)
{
	global $conn;
	$sql = "select max(ordre) as max from cc_sr_classement";
	$result = pg_query($conn,$sql) or die (pg_last_error());
	$max = pg_fetch_array($result);
	$max_suiv = $max['max'] + 1;
	$sql = "insert into cc_sr_classement(libelle_classement,section,ordre) values ('".$nom_classement."','".$nom_section."',".$max_suiv.")";
	$result = pg_query($conn,$sql) or die (pg_last_error());
	if($result)
	{
		return 'Insertion avec succès';
	}
}

function updateClassement($nom_classement,$nom_section,$id_classement)
{
	global $conn;
	$sql = "UPDATE  cc_sr_classement SET libelle_classement = '".pg_escape_string($nom_classement)."', section = '".$nom_section."' WHERE id_classement = ".$id_classement;
	$result = pg_query($conn,$sql) or die (pg_last_error());
	if($result)
	{
		return 'Modification avec succès';
	}
}

function update_rows($rows_order)
{
	global $conn;
	$rows_order = explode('&', $rows_order);
    for($i=1;$i<=count($rows_order);$i++){
	      $tab2 = explode('=', $rows_order[$i-1]);
		
		  $sql = "UPDATE  cc_sr_classement SET ordre = ".$i." WHERE id_classement = ".$tab2[1];
		  $result_update = pg_query($conn,$sql) or die (pg_last_error());
	}
}
    
function fetchAllClassement()
{
	global $conn;
	$sql = "select * from cc_sr_classement order by ordre";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function setTableauClassement()
{
	global $conn;
	$str = '';
	$str .= '<table id="id_tablesorter" class="tablesorter">
        <thead>
        <tr>
        <th width="70%">Classement (Faire glisser les lignes pour modifier l\'ordre)</th>
        <th width="20%">Section</th>
        <th width="10%"></th>
        </tr>
        </thead>
        
        <tbody>';

        $result = fetchAllClassement();
        while ($res = pg_fetch_array($result)) {
        	$id_classement = $res['id_classement'];
        	
        	$sql = "select * from cc_sr_categorie_grille where id_classement = ".$id_classement;
        	$result_cat_grille = pg_query($conn,$sql) or die (pg_last_error());

	        $str .= '<tr id="row-'.$res['id_classement'].'">
	        <td>'.$res['libelle_classement'].'</td>
	        <td>'.$res['section'].'</td>
	        <td><span style="display:block;width:40px;margin:auto;">
	        <img src="images/edit.png" width="15px" height="15px" id="id_edit_classement_'.$res['id_classement'].'" style="cursor:pointer;" onclick="updateClassement('.$res['id_classement'].')" title="Editer le classement" />&nbsp;&nbsp;';
	        if(pg_num_rows($result_cat_grille) == 0)
	        {
	        	$str .= '<img src="images/delete.png" width="15px" height="15px" id="id_delete_classement_'.$res['id_classement'].'" style="cursor:pointer;" onclick="deleteClassement('.$res['id_classement'].')" title="Supprimer le classement" />';
	        }
	        else
	        {
				$str .= '<img src="images/no_entry.png" width="15px" height="15px" id="id_delete_class_'.$res['id_classement'].'" title="Ce classement ne peut pas être effacé" />';
			}
	        $str .= '</span></td>
	        </tr>';
        }

    $str .= '</tbody>
        </table>';
    
    return $str;
}

function fetchAllProject1($variable)
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

function setFiltreClassement($id_projet,$id_client,$id_application,$champ_class)
{
	global $conn;
	if($champ_class == 'client')
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
	else if($champ_class == 'code')
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

function fetchAllClassementPonderation($id_projet, $id_client, $id_application)
{
	global $conn;
	/*$sql = "select a.id_classement idclassement, b.id_classement all_idclassement, a.id_projet, a.id_client, a.id_application, ponderation_classement, ponderation_section,
libelle_classement, section, b.ordre from cc_sr_grille_classement a 
right join cc_sr_classement b 
on a.id_classement = b.id_classement 
where (id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application.") or (id_projet is null)
order by section, ordre";*/
	$sql = "select a.id_classement idclassement, b.id_classement all_idclassement, a.id_projet, a.id_client, a.id_application, 
ponderation_classement, ponderation_section, libelle_classement, section, b.ordre from 
( select * from cc_sr_classement ) as b 
left join 
( select * from cc_sr_grille_classement 
where id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application." ) as a
on a.id_classement = b.id_classement 
order by section, ordre";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function fetchAllClassPond()
{
	global $conn;
	$sql = "select 0::integer as idclassement, id_classement all_idclassement, 0::integer as id_projet, 0::integer as id_client, 
0::integer as id_application, 0::double precision as ponderation_classement, 0::double precision as ponderation_section,
libelle_classement, section, ordre 
from cc_sr_classement 
order by section, ordre";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function verifClassement($id_projet, $id_client, $id_application)
{
	global $conn;
	if($id_projet == '')
	{
		$id_projet = 0;
	}
	$sql = "select * from cc_sr_grille_classement where id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application;
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getClassPondByProjet($id_projet,$id_client,$id_application)
{
	 $result_verif = verifClassement($id_projet, $id_client, $id_application);
 	 if(pg_num_rows($result_verif) == 0)
 	 {
 	 	 $result = fetchAllClassPond();
 	 }
 	 else 
 	 {
 	 	 $result = fetchAllClassementPonderation($id_projet, $id_client, $id_application);
 	 }

 	 $table = array();
 	 $table_section = array();
 	 $tablecl = array();
 	 while($res = pg_fetch_array($result))
 	 {
 	 	 if(!in_array($res['section'],$table_section)) 
 	 	 {
 	 	 	 if($res['ponderation_section'] != '')
 	 	 	 {
 	 	 	 	 $table[$res['section']] = $res['ponderation_section'];
 	 	 	 }
 	 	 }
 	 	 if($res['ponderation_classement'] == '')
 	 	 {
 	 	 	 $pnd_classement = 0;
 	 	 }
 	 	 else 
 	 	 {
 	 	 	$pnd_classement = $res['ponderation_classement'];
 	 	 }
 	 	 $tablecl[$res['section']][$res['all_idclassement']]['ponderation'] = $pnd_classement;
 	 	 $tablecl[$res['section']][$res['all_idclassement']]['libelle'] = $res['libelle_classement'];
 	 }
 	 $str = '';
 	 $str .= '<table class="tablesorter1">
 	 <tr>
 	 <th style="text-align:center;">Classement</th>
 	 <th style="text-align:center;">Pondération</th>
 	 </tr>';
 	 foreach ($tablecl as $key1 => $val1)
 	 {
 	 	 if($table[$key1] == 0 || $table[$key1] == '')
 	 	 {
 	 	 	 $style = 'style="border:1px solid red;width:100px;text-align:center;"';
 	 	 	 $valeur = '';
 	 	 }
 	 	 else 
 	 	 {
 	 	 	 $style = 'style="width:100px;text-align:center;"';
 	 	 	 $valeur = $table[$key1];
 	 	 }
 	 	 $str .= '<tr style="background:#799CA6;">
 	 	 <th style="width:75%"><span style="font-size:12px;text-align:center;display:block;color:#FFFFFF;">'.$key1.'</span></th>
 	 	 <th style="width:25%"><center><input type="text" class="class_section" id="id_'.$key1.'" '.$style.' value="'.$valeur.'" /></center></th>
 	 	 </tr>';
 	 	 
 	 	 foreach ($val1 as $key2 => $val2)
 	 	 {
 	 	 	 if($val2['ponderation'] == 0 || $val2['ponderation'] == '')
	 	 	 {
	 	 	 	 $style1 = 'style="border:1px solid red;width:100px;text-align:center;"';
	 	 	 	 $valeur1 = '';
	 	 	 }
	 	 	 else 
	 	 	 {
	 	 	 	 $style1 = 'style="width:100px;text-align:center;"';
	 	 	 	 $valeur1 = $val2['ponderation'];
	 	 	 }
 	 	 	 $str .= '<tr>
	 	 	 <td style="width:75%"><input type="hidden" id="id_class_pond" value="'.$key2.'" /><span style="margin-left:15px">'.$val2['libelle'].'</span></td>
	 	 	 <td style="width:25%"><center><input type="text" onkeypress="return isNumber(event);" class="class_input" id="id_'.$key2.'_'.$key1.'" value="'.$valeur1.'" '.$style1.' /></center></td>
	 	 	 </tr>';
 	 	 }
 	 }
 	 $str .= '</table>';
 	 return $str;
}

function savePonderation($data,$data_sct,$id_projet,$id_client,$id_application)
{
	global $conn;
	$tab_section = array();
	$tab_sct = explode('||',$data_sct);
	for($i=1;$i<count($tab_sct);$i++)
	{
		$t_sct = explode('_',$tab_sct[$i]);
		$tab_section[$t_sct[0]] = $t_sct[1];
	}
	
	$echap = 0;
	$tab = explode('||',$data);
	for($i=1;$i<count($tab);$i++)
	{
		$table = explode('_',$tab[$i]);
		$id_classement = $table[0];
		$ponderation = $table[1];
		$section = $table[2];
		
		$sql = "select * from cc_sr_grille_classement where id_classement = ".$id_classement." and id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application;
		$query  = pg_query($sql) or die(pg_last_error());
		
		if(pg_num_rows($query) == 0)
		{
			/*if($ponderation != 0) 
			{*/
				$sql = "INSERT INTO cc_sr_grille_classement( id_projet, id_client, id_application, id_classement, ponderation_classement, ponderation_section) VALUES (".$id_projet.", ".$id_client.", ".$id_application.", ".$id_classement.", ".$ponderation.", ".$tab_section[$section].");";
				$query  = pg_query($sql) or die(pg_last_error());
			/*}
			else 
			{
				$echap = 1;
			}*/
		}
		else 
		{
			$sql = "UPDATE cc_sr_grille_classement SET ponderation_classement=".$ponderation.", ponderation_section=".$tab_section[$section]." WHERE id_projet=".$id_projet." and id_client=".$id_client." and id_application=".$id_application." and id_classement=".$id_classement.";";
			$query  = pg_query($sql) or die(pg_last_error());
		}
		
		if($query)
		{
			$valid = 1;
		}
		else 
		{
			$valid = 0;
			break;
		}
		
	}
	if($valid == 1)
	{
		$str = 'Enregistrement avec succès !';
	}
	else
	{
		$str = 'Une erreur s\'est produite lors de l\'enregistrement !';
	}
	/*if($echap == 1)
	{
		$str .= '\n La pondération de chaque section(FOND/FORME) devra être suivi d\'au moins \n une pondération d\'un classement qu\'elle contient !';
	}*/
	
	return $str;
}

?>