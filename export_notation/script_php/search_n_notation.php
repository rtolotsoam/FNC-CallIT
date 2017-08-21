<?php
session_start();
include("/var/www.cache/dgconn.inc");
global $conn;
$clause = "";
$date_notation_deb = "";
$date_notation_fin = "";
$date_app_deb = "";
$date_app_fin = "";
$cc  = "";
$eval  = "";

$id_client = $_REQUEST['id_client'];
$id_prestation = $_REQUEST['presta'];
$id_type_ttmnt = $_REQUEST['t_traitement'];

$date_notation_deb = $_REQUEST['dt_notation1'];
$date_notation_fin = $_REQUEST['dt_notation2'];
$date_app_deb = $_REQUEST['dt_appel1'];
$date_app_fin = $_REQUEST['dt_appel2'];
$clause_matr_eval ="";
$clause_matr_cc = "";
$clause_date_notation = "";
$clause_note = "";
$clause_type_app = "";
$id_note_filtre = $_REQUEST['id_note_filtre'];
$note1 =  $_REQUEST['id_valeur_note_1'];
$note2 =  $_REQUEST['id_valeur_note_2'];
// #########
if(!isset( $_SESSION['matricule']))
	exit('Session GPAO vide');

$cc =  trim(implode(",",$_REQUEST['cc']));
$eval =  trim(implode(",",$_REQUEST['eval'])); 

if(isset($_REQUEST['type_appel']) && $_REQUEST['type_appel'] != "" && $_REQUEST['type_appel'] != 0)
{
	$type_appel =  trim(implode(",",$_REQUEST['type_appel'])); 
	$clause_type_app = " and n.id_typologie in(".$type_appel.") ";// print_r($_REQUEST);
}

if($cc !='' and $cc !='0')
{
	$clause_matr_cc = " and n.matricule in(".$cc.") ";
} 

if($cc !='' and $cc !='0')
{
	$clause_matr_cc = " and n.matricule in(".$cc.") ";
} 
if($eval !='' and $eval !='0')
{
	$clause_matr_eval = " and n.matricule_notation in(".$eval.") ";
} 
if($date_notation_deb !="" && $date_notation_fin !="")
{
	$clause_date_notation = " and n.date_notation >= '".$date_notation_deb."' and n.date_notation <= '".$date_notation_fin."'  ";
}
if($date_app_deb !="" && $date_app_fin !="")
{
	$clause_date_app = " and n.date_entretien >= '".$date_app_deb."' and n.date_entretien <= '".$date_app_fin."'  ";
}

if( $id_note_filtre == 1 )
{
	$clause_note =" and n.note = ".$note1;
}

if( $id_note_filtre == 2 )
{
	$clause_note =" and n.note >= ".$note1." and n.note <= ".$note2;
}

if( $id_note_filtre == 3 )
{
	$clause_note =" and n.note < ".$note1;
}
if( $id_note_filtre == 4 )
{
	$clause_note =" and n.note <= ".$note1;
}
if( $id_note_filtre == 5 )
{
	$clause_note =" and n.note > ".$note1;
}

if( $id_note_filtre == 6 )
{
	$clause_note =" and n.note >= ".$note1;
}
$sql_seek ="
select distinct ga.id_projet, ga.id_client, ga.id_application, cg.id_type_traitement,n.id_notation,n.matricule,n.matricule_notation,n.note
from cc_sr_notation n 
	inner join cc_sr_fichier f on f.id_fichier = n.id_fichier 
	inner join cc_sr_indicateur_notation inot on inot.id_notation = n.id_notation 
	inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application 
	inner join cc_sr_grille g on g.id_grille = ga.id_grille 
	inner join cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
	".$clause_date_notation." 
	".$clause_date_app." 
	where 
	ga.id_client = ".$id_client." 
	".$clause_matr_cc." 
	".$clause_note." 
	".$clause_matr_eval." 
	".$clause_type_app." 
	and cg.id_type_traitement = ".$id_type_ttmnt." and ga.id_application = ".$id_prestation;
	
	// echo '<pre>'.$sql_seek.'</pre>';
	// exit();
$query  = pg_query($conn,$sql_seek ) or die('error : sql_seek ');
$Nb = pg_num_rows($query);
 // echo 700;
 echo $Nb;
?>