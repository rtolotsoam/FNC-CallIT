<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include("/var/www.cache/dgconn.inc");
include('function_union.php');

$date_deb = '2014-11-14'; 
$date_fin = '2014-11-14';

$sql = "select distinct n.id_notation,matricule, matricule_notation,date_entretien,date_notation,n.note,nom_fichier, 
ga.id_projet, ga.id_client,ga.id_application,cg.id_type_traitement 
from cc_sr_notation n
inner join cc_sr_fichier f on f.id_fichier = n.id_fichier
inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation
inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application 
inner join cc_sr_grille g on g.id_grille = ga.id_grille
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
where n.date_notation >= '".$date_deb."' and n.date_notation <= '".$date_fin."'
--and n.id_notation in (5185,5186,5187,5188,5189,5190,5191,5192,5193,5194,5195,5196)
order by n.id_notation";
$query  = pg_query($sql) or die(pg_last_error());
$i = 0;
while($res = pg_fetch_array($query))
{
	$id_projet = $res['id_projet'];
	$id_client = $res['id_client'];
	$id_application = $res['id_application'];
	$id_notation = $res['id_notation'];
	$id_type_traitement = $res['id_type_traitement'];
	$str = calculTotalGeneral($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement);
	$table_valeur = explode('||',$str); 
	$note =  (float)$table_valeur[0];
	
	/*$sql = "update cc_sr_notation set note = ".$note." where id_notation = ".$id_notation;
	$query  = pg_query($conn,$sql);
	*/
	$retour = update($note,$id_notation);
	echo ++$i.' --- '.$id_notation.' -------- '.$note.' ---  ---Efféctué</br>';
	//flush();
}
function update($note,$id_notation)
{
	global $conn;
	//$sql = "update cc_sr_notation set note = ".$note." where id_notation = ".$id_notation;
	//$query  = pg_query($sql) or die(pg_last_error());
	
	return 1;
}
?>