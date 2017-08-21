<?php
include("/var/www.cache/dgconn.inc");

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'prestation'){
	$id_client = $_REQUEST['id_client'];
	if($id_client==0){
		echo getAllPrestationForFiltre();
	}else {
		echo getValeurPresta($id_client);
	}
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'client'){
	$id_prestation = $_REQUEST['id_prestation'];
	if($id_prestation == 0 || $id_prestation == '0'){
		echo '0';
	}else{
		echo getValeurClient($id_prestation);
	}
}

function getAllCCForFiltre(){
	global $conn;
	$sql = "select distinct p.matricule,p.prenompersonnel,p.fonctioncourante from cc_sr_notation n
	inner join cc_sr_indicateur_notation inot on n.id_notation = inot.id_notation
	inner join personnel p on p.matricule = n.matricule
	where inot.id_grille_application is not null
	order by p.matricule";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getAllEvalForFiltre(){
	global $conn;
	$sql = "select distinct p.matricule,p.prenompersonnel,p.fonctioncourante from cc_sr_notation n
	inner join cc_sr_indicateur_notation inot on n.id_notation = inot.id_notation
	inner join personnel p on p.matricule = n.matricule_notation
	where inot.id_grille_application is not null
	order by p.matricule";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getAllFichierForFiltre(){
	global $conn;
	$sql = "select distinct f.id_fichier,f.nom_fichier from cc_sr_notation n
	inner join cc_sr_fichier f on f.id_fichier = n.id_fichier
	inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation
	where inot.id_grille_application is not null";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getAllTypeForFiltre(){
	global $conn;
	// $sql = "select * from cc_sr_type_traitement order by id_type_traitement";
	$sql = "select * from cc_sr_type_traitement_temp order by id_type_traitement_temp";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getAllClientForFiltre(){
	global $conn;
	$sql = "select distinct guc.id_client, guc.nom_client from cc_sr_projet p
	inner join gu_application gua on gua.id_application = p.id_application
	inner join gu_client guc on guc.id_client = gua.id_client
	where p.archivage = 1
	order by nom_client";
	$query  = pg_query($sql) or die(pg_last_error());
	return $query;
}

function getAllPrestationForFiltre(){
	global $conn;
	$sql = "select gua.id_application, gua.code, gua.nom_application from cc_sr_projet p
	inner join gu_application gua on gua.id_application = p.id_application
	inner join gu_client guc on guc.id_client = gua.id_client
	where p.archivage = 1
	order by code";
	$query  = pg_query($sql) or die(pg_last_error());
	$str = '<option value="0">-- Choisir ici --</option>';
	while($res = pg_fetch_array($query)){
		$str .= '<option value="'.$res['id_application'].'">'.$res['code'].' - '.$res['nom_application'].'</option>';
	}
	return $str;
}

function getValeurPresta($id_client){
	global $conn;
	$sql = "select gua.id_application, gua.code, gua.nom_application from cc_sr_projet p
	inner join gu_application gua on gua.id_application = p.id_application
	inner join gu_client guc on guc.id_client = gua.id_client
	where p.archivage = 1
	and p.id_client = ".$id_client."
	order by code";
	$query  = pg_query($sql) or die(pg_last_error());
	$str = '<option value="0">-- Choisir ici --</option>';
	while($res = pg_fetch_array($query)){
		$str .= '<option value="'.$res['id_application'].'">'.$res['code'].' - '.$res['nom_application'].'</option>';
	}
	return $str;
}

function getValeurClient($id_prestation){
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
	if(pg_num_rows($query) > 0){
		$zHtml .= '|||<option value=0>-- Choisir ici --</option>';
		while($res = pg_fetch_array($query)){
			$zHtml .= '<option value='.$res['id_typologie'].'>'.utf8_decode($res['libelle_typologie']).'</option>';
		}
	}else{
		$zHtml .= '|||0';
	}
	echo $zHtml;
}
