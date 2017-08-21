<?php
include ("/var/www.cache/dgconn.inc");

function getCategorie($id_type,$id_application,$id_projet,$id_client)
{
	global $conn;
	/*$sql = "select a.id_grille_application, a.id_application, a.id_projet, a.id_client, b.id_grille, b.libelle_grille, 
c.id_categorie_grille, c.libelle_categorie_grille, --d.id_grille_description, d.note, d.libelle_description,
e.id_classement, e.libelle_classement, e.section 
from cc_sr_grille_application a 
inner join cc_sr_grille b on a.id_grille = b.id_grille 
inner join cc_sr_categorie_grille c on b.id_categorie_grille = c.id_categorie_grille 
--left join cc_sr_grille_description d on a.id_grille_application = d.id_grille_application 
left join cc_sr_classement e on e.id_classement = c.id_classement
where id_type_traitement = ".$id_type;*/
	
	$sql = "select a.id_grille_application, a.id_application, a.id_projet, a.id_client, 
b.id_grille, b.libelle_grille, b.ordre ordre_grille, 
c.id_categorie_grille, c.libelle_categorie_grille, c.ordre ordre_categorie_grille, 
--d.id_grille_description, d.note, d.libelle_description,
e.id_classement, e.libelle_classement, e.section,
f.note, f.commentaire,
g.matricule_notation, g.debut_entretien, g.date_notation, g.id_fichier, g.date_entretien

from cc_sr_grille_application a 
inner join cc_sr_grille b on a.id_grille = b.id_grille 
inner join cc_sr_categorie_grille c on b.id_categorie_grille = c.id_categorie_grille 
--left join cc_sr_grille_description d on a.id_grille_application = d.id_grille_application 
left join cc_sr_classement e on e.id_classement = c.id_classement 
left join cc_sr_indicateur_notation f on f.id_grille_application = a.id_grille_application 
left join cc_sr_notation g on g.id_notation = f.id_notation

where id_type_traitement = ".$id_type." and a.id_application = ".$id_application." and a.id_projet = ".$id_projet." and a.id_client = ".$id_client." 
order by section, id_classement, ordre_categorie_grille, ordre_grille";
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
	//return $sql;
}

function getDescriptionByApp($id_grille_application)
{
	$sql = "select * from cc_sr_grille_description ";
	$query = pg_query($conn,$sql) or die (pg_last_error($conn));
	return $query;
}

function getAll($id_type,$id_application,$id_projet,$id_client)
{
	$str = "";
	$str .= '<table border="1" id="id_table_principale">
		<tr>
		<th>Critère</th>
		<th width="15%">Item</th>
		<th>Note</th>
		<th width="30%">Description</th>
		<th>Note</th>
		<th>Base</th>
		<th>Commentaires</th>
		<th>Situation inacceptable</th>
		<th>Commentaire Situation inacceptable</th>
		</tr>
		</table>';
	
	$result = getCategorie($id_type,$id_application,$id_projet,$id_client);
	$table = array();
	$section = '';
	$id_classement = 0;
	$id_categorie_grille = 0;
	$id_grille = 0;
	while($res = pg_fetch_array($result))
	{
		if($section != $res['section'])
		{
			$section = $res['section'];
		}
		if($id_classement != $res['id_classement'])
		{
			$id_classement = $res['id_classement'];
		}
		if($id_categorie_grille != $res['id_categorie_grille'])
		{
			$id_categorie_grille = $res['id_categorie_grille'];
			$nb_categorie_grille ++;
		}
		if($id_grille != $res['id_grille'])
		{
			$id_grille = $res['id_grille'];
		}
		
		/* Difficult Algorithm */
		
	}
	
}

?>