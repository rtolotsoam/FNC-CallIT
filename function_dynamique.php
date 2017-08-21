<?php
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//echo 'Matricule = '.$_SESSION['matricule'];
//refreshListeCom; actualiserListeNotation;
// print_r($_REQUEST);
// exit();
include("/var/www.cache/dgconn.inc");
include('function_union.php');
/*
$id_projet = isset($_REQUEST['id_projet']) ? $_REQUEST['id_projet'] : 0;
$id_client = isset($_REQUEST['id_client']) ? $_REQUEST['id_client'] : 0;
$id_application = isset($_REQUEST['id_application']) ? $_REQUEST['id_application'] : 0;
$id_notation = isset($_REQUEST['id_notation']) ? $_REQUEST['id_notation'] : 0;
$id_type_traitement = isset($_REQUEST['id_type_traitement']) ? $_REQUEST['id_type_traitement'] : 0;*/
/*$id_projet =51;
$id_client=599;
$id_application=408;
$id_notation = 1737;*/

if(isset($_REQUEST['verif_matricule']))
{
	global $conn;
	$matricule_session = $_REQUEST['matricule_session'];
	$sql = "select * from cc_sr_droit where matricule_droit = ".$matricule_session." ";
	$query  = pg_query($sql) or die(pg_last_error());
	$nb = pg_num_rows($query);
	if($nb > 0)
	{
		$result = pg_fetch_array($query);
		echo $result['admin_droit'];
	}
	else
	{
		echo 0;
	}
}

if(isset($_REQUEST['idnotation']))
{
	$id_fichier = $_REQUEST['idfichier'];
	$nom_fichier = $_REQUEST['nomfichier'];
	$id_projet = $_REQUEST['idprojet'];
	$id_tlc = $_REQUEST['idtlc'];
	$date_entretien = $_REQUEST['dateentretien'];
	$numero_dossier = $_REQUEST['numerodossier']; 
	$numero_commande = $_REQUEST['numerocommande'];
	$valeur_point_appui = $_REQUEST['valeurpointappui'];
	$valeur_point_amelioration = $_REQUEST['valeurpointamelioration'];
	$valeur_preconisation = $_REQUEST['valeurpreconisation'];
	$type_appel = $_REQUEST['typeappel'];
	//echo insertNotation($id_fichier,$id_projet,$id_tlc,$nom_fichier,$date_entretien,$numero_dossier,$numero_commande);
	//echo insertNotation($id_fichier,$id_projet,$id_tlc,$nom_fichier,$date_entretien,$numero_dossier,$numero_commande,$valeur_point_appui,$valeur_point_amelioration,$valeur_preconisation);
	
	if($id_fichier == 0 || $id_fichier == '0')
	{
		//$sql = "insert into cc_sr_fichier (nom_fichier) values ('".pg_escape_string(utf8_decode($nom_fichier))."') returning id_fichier";
		$sql = "insert into cc_sr_fichier (nom_fichier) values ('".pg_escape_string(trim($nom_fichier))."') returning id_fichier";
		
		$query  = pg_query($sql) or die(pg_last_error());
		$result = pg_fetch_array($query);
		$id_fichier = $result['id_fichier'];
	}
	
	$matricule_notation = $_SESSION['matricule'];
	$date_ent = explode('/',$date_entretien);
	$date_entretien = $date_ent[2].'-'.$date_ent[1].'-'.$date_ent[0];
	$date_notation = date("Y-m-d");
	$debut_entretien = date("H:i");
	$sql = "INSERT INTO cc_sr_notation(
        matricule, date_entretien, duree_entretien,  
        id_fichier, date_notation, matricule_notation, debut_entretien, 
        id_projet, commentaire_general, objectif,numero_dossier,numero_commande,point_appui,point_amelioration,preconisation,id_typologie)
		VALUES (".$id_tlc.", '".$date_entretien."', 0, ".$id_fichier.", '".$date_notation."', ".$matricule_notation.", '".$debut_entretien."',".$id_projet.", '', '','".pg_escape_string($numero_dossier)."','".pg_escape_string($numero_commande)."','".pg_escape_string($valeur_point_appui)."','".pg_escape_string($valeur_point_amelioration)."','".pg_escape_string($valeur_preconisation)."',".$type_appel.") returning id_notation;";
	$query  = pg_query( $sql ) or die(pg_last_error());
	$result = pg_fetch_array($query);
	echo $result['id_notation'].'**'.$id_fichier.'**'.$sql;
}

if(isset($_REQUEST['go']))
{
	$go = $_REQUEST['go'];
	$id_grille_application = $_REQUEST['idgrilleapplication'];
	$id_grille = $_REQUEST['idgrille'];
	$valeur_note = $_REQUEST['valeurnote'];
	$commentaire = $_REQUEST['commentaire'];
	$commentaire_si = $_REQUEST['commentairesi'];
	$id_notation = $_REQUEST['idnotation1'];
	$id_fichier = $_REQUEST['idfichier'];
	$id_projet = $_REQUEST['idprojet'];
	$id_client = $_REQUEST['idclient'];
	$id_application = $_REQUEST['idapplication'];
	$base = $_REQUEST['base'];
	
	$description_fnc_si = $_REQUEST['description_fnc_si'];
	$exigence_fnc_si = $_REQUEST['exigence_fnc_si'];
	$matricule_tlc = $_REQUEST['matricule_tlc'];
	$id_cat_fnc_si = $_REQUEST['id_cat_fnc_si'];
	$id_type_traitement = $_REQUEST['idtypetraitement'];
	
	$test_nc_si = $_REQUEST['test_nc_si'];
	
	$gravite_si = $_REQUEST['gravite_si'];
	$frequence_si = $_REQUEST['frequence_si'];
	$cat_grav_si = $_REQUEST['cat_grav_si'];
	$cat_freq_si = $_REQUEST['cat_freq_si'];
	
	/**$gravite_si = 1;
	$frequence_si =5;
	$cat_grav_si = 5;
	$cat_freq_si = 1;*/
		
	if($go == 1)
	{
		echo insertIndicateurNotation($id_grille_application,$id_grille,$valeur_note,$commentaire,$commentaire_si,$id_notation,$id_fichier,$id_projet,$id_client,$id_application,$base,$description_fnc_si,$exigence_fnc_si,$matricule_tlc,$id_cat_fnc_si,$id_type_traitement,$test_nc_si,$gravite_si,$frequence_si,$cat_grav_si,$cat_freq_si);
		//echo insertIndicateurNotation($id_grille_application,$id_grille,$valeur_note,$commentaire,$commentaire_si,$id_notation,$id_fichier,$id_projet,$id_client,$id_application,$base);
	}
	else if($go == 2)
	{
		//echo updateIndicateurNotation($id_grille_application,$id_grille,$valeur_note,$commentaire,$commentaire_si,$id_notation,$id_fichier,$id_projet,$id_client,$id_application,$base);
		echo updateIndicateurNotation($id_grille_application,$id_grille,$valeur_note,$commentaire,$commentaire_si,$id_notation,$id_fichier,$id_projet,$id_client,$id_application,$base,$description_fnc_si,$exigence_fnc_si,$matricule_tlc,$id_cat_fnc_si,$id_type_traitement,$test_nc_si,$gravite_si,$frequence_si,$cat_grav_si,$cat_freq_si);
	}
}

//if(isset($id_projet) && isset($id_client) && isset($id_application) && isset($id_notation) && isset($id_type_traitement))
if(isset($_REQUEST['id_projet']) && isset($_REQUEST['id_client']) && isset($_REQUEST['id_application']) && isset($_REQUEST['id_notation']) && isset($_REQUEST['id_type_traitement']))
//if(isset($id_projet) && isset($id_client) && isset($id_application) && isset($id_notation))
{

  if(isset($_REQUEST['id_fichier']) && isset($_REQUEST['id_tlc']))
  {
      $id_fichier = $_REQUEST['id_fichier'];
      $id_tlc = $_REQUEST['id_tlc'];
  }
  else
  {
      $id_fichier = 0;
      $id_tlc = 0;
  } 
  
  	$droit_eval = isset($_REQUEST['droit_eval']) ? $_REQUEST['droit_eval'] : 0;
	echo fetchAllResults($id_projet, $id_client, $id_application, $id_notation,$id_type_traitement,$id_tlc,$id_fichier,$droit_eval);
	echo '|||'.getDateNotationById($id_notation);
  //echo '|||'.actualiserListeNotation($id_fichier,$id_projet, $id_client, $id_application, $id_type_traitement,$id_tlc)
}

if(isset($_REQUEST['refreshList']))
{
	$id_fichier = $_REQUEST['_id_fichier'];
	$id_projet = $_REQUEST['_id_projet'];
	$id_client = $_REQUEST['_id_client'];
	$id_application = $_REQUEST['_id_application'];
	$id_type_traitement = $_REQUEST['_id_type_traitement'];
	$id_tlc = $_REQUEST['_id_tlc'];
	$id_notation = $_REQUEST['_id_notation'];
	$date_entretien = $_REQUEST['dateentretien'];
	$date_ent = explode('/',$date_entretien);
	$date_entretien = $date_ent[2].'-'.$date_ent[1].'-'.$date_ent[0];
	$numero_dossier = $_REQUEST['numerodossier'];
	$numero_commande = $_REQUEST['numerocommande'];
	$valeur_point_appui = $_REQUEST['valeurpointappui'];
	$valeur_point_amelioration = $_REQUEST['valeurpointamelioration'];
	$valeur_preconisation = $_REQUEST['valeurpreconisation'];
	$type_appel = $_REQUEST['typeappel'];
	
	$sql = "update cc_sr_notation set date_entretien = '".$date_entretien."', numero_dossier = '".pg_escape_string($numero_dossier)."',numero_commande = '".pg_escape_string($numero_commande)."', point_appui = '".pg_escape_string($valeur_point_appui)."' , point_amelioration = '".pg_escape_string($valeur_point_amelioration)."' , preconisation = '".pg_escape_string($valeur_preconisation)."' , id_typologie = ".$type_appel." where id_notation = ".$id_notation;
	$query  = pg_query($sql) or die(pg_last_error());
	echo refreshListeCom($id_fichier,$id_projet, $id_client, $id_application,$id_type_traitement,$id_tlc);
	echo ' ### ';
	echo actualiserListeNotation($id_fichier,$id_projet, $id_client, $id_application, $id_type_traitement,$id_tlc);
}

if(isset($_REQUEST['test_refresh']))
{
    $id_fichier = $_REQUEST['_id_fichier'];
	$id_projet = $_REQUEST['_id_projet'];
	$id_client = $_REQUEST['_id_client'];
	$id_application = $_REQUEST['_id_application'];
	$id_type_traitement = $_REQUEST['_id_type_traitement'];
	$id_tlc = $_REQUEST['_id_tlc'];
  echo actualiserListeNotation($id_fichier,$id_projet, $id_client, $id_application, $id_type_traitement,$id_tlc);
}

if(isset($_REQUEST['test_duplicate']))
{
    $id_projet = $_REQUEST['_id_projet_'];
	$id_client= $_REQUEST['_id_client_'];
	$id_application = $_REQUEST['_id_application_'];
	
	$new_id_projet= $_REQUEST['_new_id_projet_'];
	$new_id_client = $_REQUEST['_new_id_client_'];
	$new_id_application = $_REQUEST['_new_id_application_'];
    $test_penalite = $_REQUEST['test_penalite'];
	dupliquer_grille($id_projet,$id_client,$id_application,$new_id_projet,$new_id_client,$new_id_application,$test_penalite);
}

function getDateNotationById($id_notation)
{  
	global $conn;
	$sql = "select n.date_notation,n.date_entretien,n.numero_dossier,n.numero_commande,n.id_typologie,p.matricule,p.prenompersonnel from cc_sr_notation n
    inner join  personnel p ON p.matricule=n.matricule_notation where id_notation = ".$id_notation;
	$query  = pg_query($sql) or die(pg_last_error());
	if(pg_num_rows($query) != 0)
	{
		$res = pg_fetch_array($query);
		$date_notation = explode('-',$res['date_notation']);
		list($annee_entretien,$mois_entretien,$jour_entretien) =  explode('-',$res['date_entretien']);
		$matricule_evaluateur = $res['matricule'];
		$prenom_evaluateur = $res['prenompersonnel'];
		$numero_dossier = $res['numero_dossier'];
		$numero_commande = $res['numero_commande'];
		$id_typologie = $res['id_typologie'];
		return $date_notation[2].'/'.$date_notation[1].'/'.$date_notation[0].'|||'.$matricule_evaluateur.'|||'.$prenom_evaluateur.'|||'.$jour_entretien.'/'.$mois_entretien.'/'.$annee_entretien.'|||'.utf8_decode($numero_dossier).'|||'.utf8_decode($numero_commande).'|||'.$id_typologie;
	}
	else 
	{
		return '';
	}
}

//function insertNotation($id_fichier,$id_projet,$id_tlc,$nom_fichier,$date_entretien,$numero_dossier,$numero_commande)
function insertNotation($id_fichier,$id_projet,$id_tlc,$nom_fichier,$date_entretien,$numero_dossier,$numero_commande,$valeur_point_appui,$valeur_point_amelioration,$valeur_preconisation)
{
	global $conn;
	
	if($id_fichier == 0 || $id_fichier == '0')
	{
		//$sql = "insert into cc_sr_fichier (nom_fichier) values ('".pg_escape_string(utf8_decode($nom_fichier))."') returning id_fichier";
		$sql = "insert into cc_sr_fichier (nom_fichier) values ('".pg_escape_string(trim($nom_fichier))."') returning id_fichier";
		
		$query  = pg_query($sql) or die(pg_last_error());
		$result = pg_fetch_array($query);
		$id_fichier = $result['id_fichier'];
	}
	
	$matricule_notation = $_SESSION['matricule'];
	$date_ent = explode('/',$date_entretien);
	$date_entretien = $date_ent[2].'-'.$date_ent[1].'-'.$date_ent[0];
	$date_notation = date("Y-m-d");
	$debut_entretien = date("H:i");
	$sql = "INSERT INTO cc_sr_notation(
        matricule, date_entretien, duree_entretien,  
        id_fichier, date_notation, matricule_notation, debut_entretien, 
        id_projet, commentaire_general, objectif,numero_dossier,numero_commande,point_appui,point_amelioration,preconisation)
		VALUES (".$id_tlc.", '".$date_entretien."', 0, ".$id_fichier.", '".$date_notation."', ".$matricule_notation.", '".$debut_entretien."',".$id_projet.", '', '','".pg_escape_string($numero_dossier)."','".pg_escape_string($numero_commande)."','".pg_escape_string($valeur_point_appui)."','".pg_escape_string($valeur_point_amelioration)."','".pg_escape_string($valeur_preconisation)."') returning id_notation;";
	$query  = pg_query( $sql ) or die(pg_last_error());
	$result = pg_fetch_array($query);
	return $result['id_notation'].'**'.$id_fichier.'**'.$sql;
}

function insertIndicateurNotation($id_grille_application,$id_grille,$valeur_note,$commentaire,$commentaire_si,$id_notation,$id_fichier,$id_projet,$id_client,$id_application,$base,$description_fnc_si,$exigence_fnc_si,$matricule_tlc,$id_cat_fnc_si,$id_type_traitement,$test_nc_si,$gravite_si,$frequence_si,$cat_grav_si,$cat_freq_si)
//function insertIndicateurNotation($id_grille_application,$id_grille,$valeur_note,$commentaire,$commentaire_si,$id_notation,$id_fichier,$id_projet,$id_client,$id_application,$base)
{
	global $conn;
   $idApelTyp = getIdAppelType ($id_notation);
if($id_grille_application != 0 && $id_grille != 0)
{
	$base = explode('|',$base);
	$flag_ponderation = 0;
	if(in_array($id_grille,$base))
	{
		$flag_ponderation = 1;
	}
	
	$matricule_notation = $_SESSION['matricule'];
	$date_notation = date("Y-m-d");
	$debut_entretien = date("H:i");
	
	$sql = "INSERT INTO cc_sr_indicateur_notation(
            id_notation, id_grille, commentaire, 
            note, id_grille_application, commentaire_si, flag_ponderation)
    		VALUES (".$id_notation.", ".$id_grille.", '".pg_escape_string(utf8_decode($commentaire))."', ".$valeur_note.", ".$id_grille_application.", '".pg_escape_string(utf8_decode($commentaire_si))."', ".$flag_ponderation.");";
	$query_select  = pg_query( $sql ) or die(pg_last_error());
	/*if($query_select)
    {
    	return 1;
    }
    else 
    {
    	return 0;
    }*/
}
	
if($commentaire_si != '' && $test_nc_si == 0)
{
	/* ******************* GET VALEUR *************************/
	$prenom_tlc = get_prenom_personnel( $matricule_tlc );
	if($id_cat_fnc_si != 0)
	{
		$lib_categorie = get_libelle_categorie( $id_cat_fnc_si );
	}
	else
	{
		$lib_categorie = ' ';
	}
	$libelle = getLibelleById($id_projet,$id_client,$id_application,$id_type_traitement,$matricule_tlc,$id_fichier);
	$tab_libelle = explode('||',$libelle);
	$data_notation = getDateNotationById($id_notation);
	$data_notation  = explode("|||",$data_notation);

	$date_evaluation = $data_notation[0];

	if( $date_evaluation == '' ){
	  $date_evaluation = date('d/m/Y');    
	}

	$nom_fichier = get_nom_fichierById($id_fichier);
	/* ********************************************************/

    $zClientName = $tab_libelle[1];
    $id_prestation = $tab_libelle[2];
    $type_traitement = $tab_libelle[3];
    $id_tlc = $prenom_tlc;
    $id_fichier = $nom_fichier;
    $date_traitement = $data_notation[3];
    $date_evaluation = $date_evaluation;
    $description_ecart = utf8_decode($description_fnc_si);
    $zExigence = pg_escape_string(utf8_decode($exigence_fnc_si));
    $categorie_si = $lib_categorie;
	//$id_grille_application =  $_REQUEST['id_grille_application'];
	//$notation_id =  $_REQUEST['notation_id'];
	
    $zRef = "NC_".strtoupper($id_prestation)."_".date("ymd");
    $sqlRef = "select * from nc_fiche where fnc_ref ilike '".$zRef."%'";
    $oRef = @pg_query ($conn, $sqlRef) ;
    $iNbRef = @pg_num_rows($oRef);
    if($iNbRef != 0) $zRef = $zRef.'('.$iNbRef.')';
    
	$zCreationDate = date("Y-m-d");
	$zCreationHour = date("H:i:s");
	$zType = "interne";
	$id_tlc_fnc = str_replace(" ","_",$id_tlc )."_".$matricule_tlc;
	if($id_cat_fnc_si != 0)
	{
		$zMotif = pg_escape_string($description_ecart.' >>> CC:'. $id_tlc_fnc.'  Ref:'.$id_fichier.'  Appel:'.$date_traitement.'  Evaluation:'.$date_evaluation.'  Catégorie SI:'. $categorie_si);	
	}
	else
	{
		$zMotif = pg_escape_string($description_ecart.' >>> CC :'. $id_tlc_fnc.'  Ref:'.$id_fichier.'  Appel:'.$date_traitement.'  Evaluation:'.$date_evaluation);	
	}
	$zTraitStatut =  "en attente" ;	
	$iCreateur = $_SESSION["matricule"] ;
	$zValide = "false" ;
	$iVersion =  "1";
	$zACStatut = "";
	$zANCStatut = "";
	$autre = "";
	
    $zSqlInsertFNC = "INSERT INTO nc_fiche (";
	if (!empty ($id_prestation)) $zSqlInsertFNC .= "fnc_code, ";
	$zSqlInsertFNC .= "fnc_ref, ";
	$zSqlInsertFNC .= "fnc_comm, \"fnc_creationDate\", fnc_type, fnc_motif, fnc_exigence, fnc_statut, fnc_createur, fnc_valide, fnc_client, fnc_version, \"fnc_creationHour\", \"fnc_actionCStatut\", \"fnc_actionNCStatut\", fnc_autre_cplmnt,fnc_id_grille_application,fnc_id_notation,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id,id_cc_sr_typo) VALUES (";
	if (!empty($id_prestation)) 
	{
	   if( $id_prestation == 'RDT' )
		{
		  $id_prestation = $id_prestation.'001';
		}
	    $zSqlInsertFNC .= "'{$id_prestation}', ";
	}
	$zSqlInsertFNC .= "'{$zRef}', ";
	$zSqlInsertFNC .= "'{$zComm}', '{$zCreationDate}', '{$zType}', '{$zMotif}', '{$zExigence}', '{$zTraitStatut}',  '{$iCreateur}', '{$zValide}', '{$zClientName}', '{$iVersion}', '{$zCreationHour}', '{$zACStatut}', '{$zANCStatut}','{$autre}','{$id_grille_application}','{$id_notation}','{$gravite_si}','{$frequence_si}','{$cat_freq_si}','{$cat_grav_si}',{$idApelTyp}) returning fnc_id" ;
	
  
	$oInsertFNC = @pg_query ($conn, $zSqlInsertFNC) ;
	$fnc_id = pg_fetch_result($oInsertFNC, 0, 'fnc_id');
	if( $oInsertFNC )
	{


		// Selection d'id pour la gravité
	  	$sqlGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav = '$gravite_si' " ;
	   
	   $resGrv = @pg_query ($conn,$sqlGrv) or die (pg_last_error($conn));
	   $arGrv = @pg_fetch_array($resGrv) ;
	   $id_grav = $arGrv['echelle_id_grav'] ;
	   
	   // Selection d'id pour la fréquence
	   $sqlFrq = "SELECT id_categorie_freq, echelle_id_freq FROM nc_frequence_categorie WHERE id_categorie_freq = '$frequence_si' " ;
	   $resFrq = @pg_query ($conn,$sqlFrq) or die (pg_last_error($conn)) ;
	   $arFrq = @pg_fetch_array($resFrq) ;
	   $id_freq = $arFrq['echelle_id_freq'] ;
	   
	   //echo $id_grav."-".$id_freq ;
	   
	   
	   // Affichage criticité
	   if ($id_grav == 1)
	      $criticite = "m" ;
	   elseif ($id_grav == 2 && $id_freq <= 2)
	      $criticite = "m" ;
	   elseif ($id_grav == 2 && $id_freq >= 3)
	      $criticite = "M" ;
	   elseif ($id_grav == 3 && $id_freq < 4)
	      $criticite = "M" ;
	   elseif ($id_grav == 3 && $id_freq >= 4)   
	      $criticite = "C" ;
	   elseif($id_grav >= 4)
	      $criticite = "C" ;

	   //return '1#'.$fnc_id;	
	   return '1#x#'.$zClientName.'#x#'.$id_prestation.'#x#'.$type_traitement.'#x#'.$id_tlc.'#x#'.$id_fichier.'#x#'.$date_traitement.'#x#'.$date_evaluation.'#x#'.$categorie_si.'#x#'.$description_ecart.'#x#'.$zExigence.'#x#'.$zRef.'#x#'.$criticite.'#x#';		   
	}
	else
	{
	   return '2#x#';	
	}
}		
else
{
    return '0#x#';
}

}

//function updateIndicateurNotation($id_grille_application,$id_grille,$valeur_note,$commentaire,$commentaire_si,$id_notation,$id_fichier,$id_projet,$id_client,$id_application,$base)
function updateIndicateurNotation($id_grille_application,$id_grille,$valeur_note,$commentaire,$commentaire_si,$id_notation,$id_fichier,$id_projet,$id_client,$id_application,$base,$description_fnc_si,$exigence_fnc_si,$matricule_tlc,$id_cat_fnc_si,$id_type_traitement,$test_nc_si,$gravite_si,$frequence_si,$cat_grav_si,$cat_freq_si)
{
	global $conn;
	$base = explode('|',$base);
	$flag_ponderation = 0;
	if(in_array($id_grille,$base))
	{
		$flag_ponderation = 1;
	}
	
	$matricule_notation = $_SESSION['matricule'];
	$date_notation = date("Y-m-d");
	$debut_entretien = date("H:i");
	
	$sql = "UPDATE cc_sr_indicateur_notation
   SET commentaire='".pg_escape_string(utf8_decode($commentaire))."', 
       note=".$valeur_note.", commentaire_si='".pg_escape_string(utf8_decode($commentaire_si))."' , flag_ponderation = ".$flag_ponderation."
 WHERE id_notation = ".$id_notation." and id_grille = ".$id_grille." and id_grille_application = ".$id_grille_application.";";
	$query_select  = pg_query( $sql ) or die(pg_last_error());
    /*if($query_select)
    {
    	return 1;
    }
    else 
    {
    	return 0;
    }*/
    if($commentaire_si != '' && $test_nc_si == 0)
	{
		/* ******************* GET VALEUR *************************/
		$prenom_tlc = get_prenom_personnel( $matricule_tlc );
		$lib_categorie = get_libelle_categorie( $id_cat_fnc_si );

		$libelle = getLibelleById($id_projet,$id_client,$id_application,$id_type_traitement,$matricule_tlc,$id_fichier);
		$tab_libelle = explode('||',$libelle);
		$data_notation = getDateNotationById($id_notation);
		$data_notation  = explode("|||",$data_notation);

		$date_evaluation = $data_notation[0];

		if( $date_evaluation == '' ){
		  $date_evaluation = date('d/m/Y');    
		}
      
      $idAppelType = "";
      
     
		$nom_fichier = get_nom_fichierById($id_fichier);
		/* ********************************************************/

	    $zClientName = $tab_libelle[1];
	    $id_prestation = $tab_libelle[2];
	    $type_traitement = $tab_libelle[3];
	    $id_tlc = $prenom_tlc;
	    $id_fichier = $nom_fichier;
	    $date_traitement = $data_notation[3];
	    $date_evaluation = $date_evaluation;
	    $description_ecart = utf8_decode($description_fnc_si);
	    $zExigence = pg_escape_string(utf8_decode($exigence_fnc_si));
	    $categorie_si = $lib_categorie;
		//$id_grille_application =  $_REQUEST['id_grille_application'];
		//$notation_id =  $_REQUEST['notation_id'];
		
       $idApelTyp = getIdAppelType ($id_notation);
       
       
	    $zRef = "NC_".strtoupper($id_prestation)."_".date("ymd");
	    $sqlRef = "select * from nc_fiche where fnc_ref ilike '".$zRef."%'";
	    $oRef = @pg_query ($conn, $sqlRef) ;
	    $iNbRef = @pg_num_rows($oRef);
	    if($iNbRef != 0) $zRef = $zRef.'('.$iNbRef.')';
    
		$zCreationDate = date("Y-m-d");
		$zCreationHour = date("H:i:s");
		$zType = "interne";
		$zMotif = pg_escape_string($description_ecart.' >>> CC:'. $id_tlc.'  Ref:'.$id_fichier.'  Appel:'.$date_traitement.'  Evaluation:'.$date_evaluation.'  Catégorie SI:'.$categorie_si);	
		$zTraitStatut =  "en attente" ;	
		$iCreateur = $_SESSION["matricule"] ;
		$zValide = "false" ;
		$iVersion =  "1";
		$zACStatut = "";
		$zANCStatut = "";
      
       // echo 'xxx';
		// echo $bTypeAppe = $_REQUEST['typeappel'];
      // echo 'xxx';
      
	    $zSqlInsertFNC = "INSERT INTO nc_fiche (";
		if (!empty ($id_prestation)) 
		  {
		   $zSqlInsertFNC .= "fnc_code, ";
		  }
		$zSqlInsertFNC .= "fnc_ref, ";
		$zSqlInsertFNC .= "fnc_comm, \"fnc_creationDate\", fnc_type, fnc_motif, fnc_exigence, fnc_statut, fnc_createur, fnc_valide, fnc_client, fnc_version, \"fnc_creationHour\", \"fnc_actionCStatut\", \"fnc_actionNCStatut\", fnc_autre_cplmnt,fnc_id_grille_application,fnc_id_notation,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id,id_cc_sr_typo) VALUES (";
		if (!empty($id_prestation)) 
		{ 
		    if( $id_prestation == 'RDT' )
			{
			  $id_prestation = $id_prestation.'001';
			}
		  $zSqlInsertFNC .= "'{$id_prestation}', ";
		}
		$zSqlInsertFNC .= "'{$zRef}', ";
		$zSqlInsertFNC .= "'{$zComm}', '{$zCreationDate}', '{$zType}', '{$zMotif}', '{$zExigence}', '{$zTraitStatut}',  '{$iCreateur}', '{$zValide}', '{$zClientName}', '{$iVersion}', '{$zCreationHour}', '{$zACStatut}', '{$zANCStatut}','{$autre}','{$id_grille_application}','{$id_notation}','{$gravite_si}','{$frequence_si}','{$cat_freq_si}','{$cat_grav_si}',{$idApelTyp}) returning fnc_id" ;
	  
		$oInsertFNC = @pg_query ($conn, $zSqlInsertFNC) ;
		$fnc_id = pg_fetch_result($oInsertFNC, 0, 'fnc_id');
		if( $oInsertFNC )
		{

			// Selection d'id pour la gravité
		  	$sqlGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav = '$gravite_si' " ;
		   
		   $resGrv = @pg_query ($conn,$sqlGrv) or die (pg_last_error($conn));
		   $arGrv = @pg_fetch_array($resGrv) ;
		   $id_grav = $arGrv['echelle_id_grav'] ;
		   
		   // Selection d'id pour la fréquence
		   $sqlFrq = "SELECT id_categorie_freq, echelle_id_freq FROM nc_frequence_categorie WHERE id_categorie_freq = '$frequence_si' " ;
		   $resFrq = @pg_query ($conn,$sqlFrq) or die (pg_last_error($conn)) ;
		   $arFrq = @pg_fetch_array($resFrq) ;
		   $id_freq = $arFrq['echelle_id_freq'] ;
		   
		   //echo $id_grav."-".$id_freq ;
		   
		   
		   // Affichage criticité
		   if ($id_grav == 1)
		      $criticite = "m" ;
		   elseif ($id_grav == 2 && $id_freq <= 2)
		      $criticite = "m" ;
		   elseif ($id_grav == 2 && $id_freq >= 3)
		      $criticite = "M" ;
		   elseif ($id_grav == 3 && $id_freq < 4)
		      $criticite = "M" ;
		   elseif ($id_grav == 3 && $id_freq >= 4)   
		      $criticite = "C" ;
		   elseif($id_grav >= 4)
		      $criticite = "C" ;


		   //return '1#'.$fnc_id;	
		   return '1#x#'.$zClientName.'#x#'.$id_prestation.'#x#'.$type_traitement.'#x#'.$id_tlc.'#x#'.$id_fichier.'#x#'.$date_traitement.'#x#'.$date_evaluation.'#x#'.$categorie_si.'#x#'.$description_ecart.'#x#'.$zExigence.'#x#'.$zRef.'#x#'.$criticite.'#x#';		   
		}
		else
		{
		   return '2#x#';
		}
	}		
	else
	{
	    return '0#x#';
	}
}

function getIdAppelType ($id_not)
{
 $b_ = 0;
 $sqlidAppelType = "select id_typologie from cc_sr_notation where id_notation = ".$id_not;
 $query  = pg_query( $sqlidAppelType ) or die(pg_last_error());
 $b_nb_res = pg_num_rows($query);
 if($b_nb_res > 0)
 {
   $b_res = pg_fetch_array($query);
   return  $b_res['id_typologie'];  
 }
 else
   return $b_;
 
 
}
function refreshListeCom($id_fichier,$id_projet, $id_client, $id_application,$id_type_traitement,$id_tlc)
{
	$iCom = 1;
	$zHtml = '';
	$result_com = getNotationCom($id_fichier,$id_projet, $id_client, $id_application,$id_type_traitement,$id_tlc);
	$zHtml .= '<option value="0">Nouveau</option>';
	while ($res_com = pg_fetch_array($result_com))
	{
		$zHtml .= '<option value="'.$res_com['id_notation'].'">Com '.$iCom.'</option>';
		$iCom ++;
	}
	return $zHtml;
}

/*
function getNotationCom($id_notation)
{
	$sql = "select * from cc_sr_notation a inner join cc_sr_indicateur_notation b on a.id_notation = b.id_notation 
	where a.id_notation = ".$id_notation;
	$query  = pg_query( $sql ) or die(pg_last_error());
    return $query_select;
}*/

//function getNotationCom($id_fichier, $id_projet, $id_client, $id_application, $id_notation)
function getNotationCom($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_tlc)
{
	global $conn;
	$sql = "select distinct c.id_notation, e.id_type_traitement, c.id_typologie
from cc_sr_grille_application a inner join cc_sr_indicateur_notation b on a.id_grille_application = b.id_grille_application 
inner join cc_sr_notation c on c.id_notation = b.id_notation 
left join cc_sr_grille d on d.id_grille = a.id_grille 
left join cc_sr_categorie_grille e on e.id_categorie_grille = d.id_categorie_grille
	where a.id_projet = ".$id_projet." and a.id_client = ".$id_client." and a.id_application = ".$id_application." and c.id_fichier = ".$id_fichier." and e.id_type_traitement = ".$id_type_traitement." and c.matricule = ".$id_tlc." 
	order by c.id_notation";
	/*--and c.id_notation = ".$id_notation ;*/
	//echo $sql; exit;
	$query  = pg_query( $sql ) or die(pg_last_error());
    return $query;
}

function fetchAllResults($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement,$id_tlc,$id_fichier,$droit_eval) 
{

$result_select = fetchAll($id_projet,$id_client,$id_application,$id_notation,$id_type_traitement,$id_tlc,$id_fichier);
$tableauBord = array();
$Nb = pg_num_rows(  $result_select );
$idKTgory = 0;
$flag_penalite   = get_flag_penalite( $id_projet );
$penalite_projet = get_penalite_projet( $id_projet , $id_type_traitement);

		    // print'<pre>';
		    // print_r($penalite_projet);
		    // print'</pre>';
			
			    
	
   for($k = 0 ; $k < $Nb ; $k++) {
     $row = pg_fetch_array($result_select,$k);
 // echo '<pre>';print_r($tableauBord[$row['section']]);echo '</pre>';
	 if (!isset($tableauBord[$row['section']])){
		$tableauBord[$row['section']] = array();
	 }
	 
	 if (!isset($tableauBord[$row['section']][$row['id_classement']])){
	 $tableauBord[$row['section']][$row['id_classement']] = array();
	 $tableauBord[$row['section']][$row['id_classement']]['libelle'] = $row['libelle_classement'];
	 $tableauBord[$row['section']][$row['id_classement']]['ponderation_classement'] = $row['ponderation_classement']; // Njiva
	 $tableauBord[$row['section']][$row['id_classement']]['ponderation_section'] = $row['ponderation_section']; // Njiva
	 $tableauBord[$row['section']][$row['id_classement']]['ktgory'] = array(); 
	 }
	 
	 if (!isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']])) {
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['libelle'] = $row['libelle_categorie_grille'];
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'] = array(); 
	 }
	
	 if (!isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']])){
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['libelle'] = $row['libelle_grille'];
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['commentaire'] = $row['commentaire']; // Njiva
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['point'] = $row['point']; // Njiva
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['commentaire_si'] = $row['commentaire_si']; // Njiva
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'] = array();	
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'] = array();
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_ponderation'] = array(); // Njiva
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['eliminatoire'] = $row['flag_eliminatoire'];	
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['id_grille_application'] = $row['id_grille_application'];
       $tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_is'] = $row['flag_is'];	
       $tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['id_repartition'] = $row['id_repartition'];	
	 }
	
	 if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'])) {
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'][$row['note']] = $row['libelle_description'];			
	 }
	
	 if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'])) {
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'][$row['libelle_description']] = $row['ponderation'];			
		$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_ponderation'][$row['libelle_description']] = isset($row['flag_ponderation']) ? $row['flag_ponderation'] : 0;	// Njiva		
	 }
		
   }
    // print '<pre>';
    // print_r($tableauBord);
    // print '</pre>';

$zHtml ='<style type="text/css">
			#info tr td {
			/*width:200px;*/
			}
			#info tr td.separation {
				width:10px;
			}
			#info {
			font-size:10px;
			font-family:Arial;
			/**border:1px solid #000000;*/
			}
			.td_section_forme{
			   background:#814111;
			   color:#FFFFFF;
			}
			.td_section_fond{
			   background:#485B6A;
			   color:#FFFFFF;			 
			}
			
			.btn_enregistrer {
			    color:#08233e;
			    font:2.4em Futura, ‘Century Gothic’, AppleGothic, sans-serif;
			    font-size:11px;
			    margin: 4px 0;
    			padding: 6px 23px;
			    /*background:url(overlay.png) repeat-x center #ffcc00;*/
			    /*background-color:rgba(255,204,0,1);*/
			    background-color:#CCCCCC;
			    border:1px solid #000000;
			    -moz-border-radius:10px;
			    -webkit-border-radius:10px;
			    border-radius:10px;
			    border-bottom:1px solid #9f9f9f;
			    -moz-box-shadow:inset 0 1px 0 rgba(255,255,255,0.5);
			    -webkit-box-shadow:inset 0 1px 0 rgba(255,255,255,0.5);
			    box-shadow:inset 0 1px 0 rgba(255,255,255,0.5);
			    cursor:pointer;
			    display:block;
			    width:100%;
			    font-weight:bold;
			}
			.btn_enregistrer:hover {
			    /*background-color:rgba(255,204,0,0.8);*/
			    background-color:#E0E9F5;
			}
            ul li{
			    display:flex;	
                margin-left:5px;				
			}
			ul li a{
			    text-decoration:none;
			}
			ul li a span:hover{
			    color:#0000FF;		
			}
		.libelle {
         color: #158ADE;
		 }
		
			</style>';

$zHtml .= '<div id="img_loading_grille" style="display: none; width: 100%; position: fixed;top:300px;">
<center><img src="images/wait.gif" width="30px" height="30px" />';
                  if( $flag_penalite == 1 ){
				  /**$zHtml .='<div style="width:272px;margin-bottom:10px;margin-left:953px;" >
				     <img  src="images/warning2.jpg" width="30px" height="30px" /><span style="color:#ff7c81;float:right;margin-top:11px;font-size:17px;">Cette grille contient des pénalités</span></div>';*/
				  }
$zHtml .='</center></div>';
         
$zHtml .=' <table  border="1" style="border-collapse:collapse;font-family: arial;font-size: 10px;width:100%" id="id_table_kt">';
$zHtml .= '<thead>
<tr style="font-size:11px; font-weight:bold;background:#000;color:#FFF;" class="head">
<th style="width:30px;"></th>
<th>Categorie</th>
<th>Crit&egrave;re</th>
<th>Note</th>
<th>Description</th>
<th style="width:54px;">Point</th>
<th style="width:54px;">Base</th>
<th style="width:54px;">Note<br>x Coeff</th>
<th>Commentaires</th>
<th>Situation inacceptable</th>
<!--<th>Commentaire Situation inacceptable</th>-->

</tr>
</thead><tbody>';
$counter1 = 0;
$nbeEliminatoire = 0; 
$test = 0; // Njiva
$_sum_total_base = 0; // Njiva 31/07/2014
$_sum_total_produit = 0; // Njiva 31/07/2014
$totaux = '';
//echo '<pre>';print_r($tableauBord);echo '</pre>';
foreach($tableauBord as $sectionkey=>$section){
	$rowspanSection = 0;
	foreach($section as $key=>$val){
		$rowspanSection++;
		foreach($val['ktgory'] as $key1=>$tab){
			foreach($tab['item'] as $item) {
				$rowspanSection += count($item['note']);
			}
		}
		$rowspanSection++;
	}
	
	$rowspanSection +=2; 
	//$rowspanSection +=2; // Pour les titres
	
	/************** Njiva ***************/
	$total_section = 0;
	$sum_ponderation_classement = 0;
	$sum_produit_base_moyenneClassement = 0;
	/************************************/
	$zHtml .= '<tr class="section"> <td class="td_section_'.strtolower($sectionkey).'" rowspan="' . $rowspanSection .'" style="border:none;">
	<span class="section_span">'.$sectionkey.'</span></td></tr>';
//echo '<pre>';print_r($section);echo '</pre>';
        foreach($section as $key_section=>$val){
		
		/***** Njiva **************/
		$sum_ponderation = 0;
		$total_classement = 0;
		$_total_base = 0;
		/************************/
		
		$zHtml .= '<tr style="background-color:#399ACC;"><td style="font-weight:bold;font-size:12px;text-align:right;height:25px;color:#FFFFFF;border:1px solid #000000;border-right:none;" colspan="4">'. $val['libelle'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		$zHtml .= '<td style="font-weight:bold;font-size:12px;text-align:center;height:25px;border-left:none;" colspan="5"></td></tr>';
		$count_si = 0;	
		foreach($val['ktgory'] as $key=>$tab){
			$rowSpanKt = 0;
		
			foreach($tab['item'] as $item) {
				$rowSpanKt += count($item['note']);
			}
     				//$zHtml .= '<tr class="section bordure-top-first">';
     				$zHtml .= '<tr>';
/* Catégorie */     $zHtml .= '<td class="bordure-top-first" rowspan="' .$rowSpanKt . '">' .$tab['libelle'] . '</td>'; 
			$nb_total_ligne = $rowSpanKt;      
			$nb_test = 1;   
			$counter2 = 0;
			$a = 1;
			//$count_si = 0;
			
			
			foreach( $tab['item'] as $id_grille=>$item  ){
				if($a != 1)
				{
					$cl = 'class="bordure-top"';
				}
				else 
				{
					$cl = 'class="bordure-top-first"';
				}
/* Critère */	$zHtml .= '<td '.$cl.' rowspan="' . count($item['note']) . '">
<input type="hidden"  class="is_class" id="is_'.$item['id_grille_application'].'" value="'.$item['flag_is'].'" />' .$item['libelle'] . '</td>';				//$item['id_grille_application'].'**'. 				

				$commentaire = isset($item['commentaire']) ? $item['commentaire'] : ''; // Njiva
				$point = isset($item['point']) ? $item['point'] : -1; // Njiva
				$commentaire_si = isset($item['commentaire_si']) ? $item['commentaire_si'] : ''; // Njiva
				$counter3=0;
				$nb_note = count($item['note']);
				$nb_n = 1;
				
				foreach ($item['note'] as $note_=>$description){
					//if(($nb_n == $nb_note) && ($nb_test != $nb_total_ligne))
					if($nb_n == $nb_note)
					{
						$cl1 = 'class="bordure-bottom"';
						if($nb_test == $nb_total_ligne)
						{
							$cl1 = 'class="bordure-bottom1"';
						}
					}
					else 
					{
						$cl1 = '';
					}
					$nb_test++;
					if ($counter3 == 0) {
						
						$border = '';
						$_champ = '';
					    if($note_ == '' || $note_ == 0)
					    {
					    	$border = 'border:1px solid red;';
					    	$_champ = '<span style="color:red">Aucune</span>';
					    }
/* Note */			    $zHtml .= '<td style="text-align:center;'.$border.'">' . $_champ . $note_ . '</td>'; // Note

						$border = '';
						$_champ = '';
					    if($description == '')
					    {
					    	$border = 'style="text-align:center;border:1px solid red;"';
					    	$_champ = '<span style="color:red">Aucune</span>';
					    }
/* Description */	$zHtml .= '<td '.$border.' rowspan="1">'.$_champ.$description.'</td>'; // Description
			
					/*********/
					$dis = '';
					if($_champ != '') $dis = "disabled";
					$tab_penalite_classement = array();
					$test_sel = 0;
					
					if( $counter3==0){
			
/* Note(Point) */      
$zHtml .= '<td rowspan="' . count($item['note']) . '">
						    <select '.$dis.' style="width:54px" name="note'.$counter2.'_'.$item['eliminatoire'].'_'.$item['ponderation'][$description].'" class="par_classement par_classement_'.$key_section.'" id="note_'.$id_grille.'_'.$item['id_grille_application'].'"  onChange=do_Total('.$key.','.$counter2.','.$key_section.',\''.$sectionkey.'\','.$id_grille.','.$item['id_grille_application'].','.$flag_penalite.','.json_encode($penalite_projet).',0,'.$id_type_traitement.','.$id_client.') >
						    <option  value="-1"></option>';
							foreach ($item['note'] as $note_item=>$valeur){
								/******* Njiva ***********/
								if($note_item == $point && $item['flag_ponderation'][$description] != 1 && $id_notation != 0) {
									$sel = 'selected="selected"';
									$test_sel = 1;
								}
								else 
								{
									$sel = '';
								}
								/*************************/
							    $zHtml .= '<option value="'.$note_item.'" '.$sel.'>'.$note_item.'</option>';  //Point (Note)
							}
							//if($test_sel == 0 && $id_notation != 0)
							if($item['flag_ponderation'][$description] == 1 && $id_notation != 0)
							{
								$sel = 'selected="selected"';
							}
							else
							{
								$sel = '';
							}
							$zHtml .= '<option value="1" '.$sel.'>N</option>';  //Point (Note) (Non evaluable)
							
						$zHtml .= '</select>										 
						 </td>';
						 
						
						$border = '';
						/*if($item['ponderation'][$description] == '' || $item['ponderation'][$description] == 0)
						{
							$border = "border:1px solid red;";
						}*/
						$selected_0 = '';
						if($item['flag_ponderation'][$description] == 1)
						{
							$selected_0 = "selected='selected'";
						}
						else
						{
							$_total_base += $item['ponderation'][$description];
						}
/* Base */			    $zHtml .= '<td '.$cl.' rowspan="'.count($item['note']).'">
						<!--<input readonly id="base_" type="text" value="' .$item['ponderation'][$description] .'" style="text-align:center; width:50px;" />-->
						<select class="class_base" readonly id="base_'.$id_grille.'" style="text-align:center; width:54px;'.$border.'" onChange="do_Total('.$key.','.$counter2.','.$key_section.',\''.$sectionkey.'\','.$id_grille.','.$item['id_grille_application'].','.$flag_penalite.','.json_encode($penalite_projet).',0,'.$id_type_traitement.','.$id_client.')">
						<option value="'.$item['ponderation'][$description].'">'.$item['ponderation'][$description] .'</option>
						<option value="0" '.$selected_0.'>N</option>
						<!--<option value="N" '.$selected_0.'>N</option>-->
						</select>
						</td>';

						if($item['flag_ponderation'][$description] == 1)
						{
							$item['ponderation'][$description] = 0;
						}
						/*************** Njiva **************/
						$ponderation = $item['ponderation'][$description];
						$sum_ponderation += $ponderation;
						/************************************/
						
						$produit_base_note = $point*$item['ponderation'][$description];
						     if( $produit_base_note < 0  ){
							    $produit_base_note =0;
							 }
						
						//hng	 
						// if($id_type_traitement == 3)
						if($id_type_traitement == 3 || $id_type_traitement == 4)
							$produit_base_note = $produit_base_note / 100;
						
/* Note * Coeff */		$zHtml .= '<td '.$cl.' align="center" rowspan="' . count($item['note']) . '"><input class="note_produit" id="note_coeff_'.$id_grille.'" type="text" style="text-align:center; width:50px;" value="'.$produit_base_note .'" readonly/></td>';
	/*Commentaire*/	    $zHtml .= '<td '.$cl.' rowspan="' . count($item['note']) . '" ><textarea name="commentaire" id="commentaire_'.$id_grille.'_'.$item['id_grille_application'].'" style="width:96%;resize:none;" rows="'.count($item['note']).'">'.$commentaire.'</textarea></td>'; // Njiva
					    if($item['eliminatoire'] == 1)
						{
							if(isset($nbReelEliminatoire)) $nbReelEliminatoire ++;
							else $nbReelEliminatoire = 1;
							if($point == 0 || $point == -1)
							{
								$total_general = '0.00';
								//$total_general_10 = '0.00';
								$test = 1;
							}
						}
					    if ($commentaire_si != '') { // Njiva
							$style = 'style="background:#FF7C81;width:96%;resize:none;" rows="'.count($item['note']).'"'; // Njiva
							$nbeEliminatoire++;
							$test_comment = 1;
						} else {
							$style = 'style="width:96%;resize:none;" rows="'.count($item['note']).'"'; // Njiva
							$test_comment = 0;
						}
/*Commentaire SI*/	    $zHtml .= '<td '.$cl.' style="text-align:center;" rowspan="' . count($item['note']) . '">';
 $zHtml .=  '<input type="hidden" class="initval_test" value="'.$test_comment.'" id="test_'.$id_grille.'" />';
 /**
 $zHtml .=  '<textarea class="si_par_classement_'.$key_section.'"  name="commentaire_si" id="commentaire_si_'.$id_grille.'_'.$item['id_grille_application'].'" '.$style.'  onblur=changeBackground('.$id_grille.','.$item['eliminatoire'].','.$item['id_grille_application'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.')>'.$commentaire_si.'</textarea>';
 */
   
$zHtml .= '<input type="hidden" id="commentaire_si_'.$id_grille.'_'.$item['id_grille_application'].'" class="class_commentaire_si_'.$key_section.'" value="'.$commentaire_si.'" />';
$zHtml .= '<input type="hidden" id="id_cat_fnc_si_'.$id_grille.'_'.$item['id_grille_application'].'" value="'.$key.'" />';
$zHtml .= '<textarea hidden id="description_fnc_si_'.$id_grille.'_'.$item['id_grille_application'].'"></textarea>';
$zHtml .= '<textarea hidden id="exigence_fnc_si_'.$id_grille.'_'.$item['id_grille_application'].'"></textarea>';
$zHtml .= '<input hidden id="gravite_si_'.$id_grille.'_'.$item['id_grille_application'].'" />';
$zHtml .= '<input hidden id="frequence_si_'.$id_grille.'_'.$item['id_grille_application'].'" />';
$zHtml .= '<input hidden id="cat_grav_si_'.$id_grille.'_'.$item['id_grille_application'].'" />';
$zHtml .= '<input hidden id="cat_freq_si_'.$id_grille.'_'.$item['id_grille_application'].'" />';
$zHtml .= '<input hidden id="criticite_si_'.$id_grille.'_'.$item['id_grille_application'].'" />';

   $fnc_id = test_nc_fiche( $item['id_grille_application'],$id_notation);
   list($fnc_id,$id_grille_application,$notation_id)  = explode('#',$fnc_id);
  // echo $fnc_id.'#'.$notation_id.'#'.$commentaire_si.'<br>';
  
	if($droit_eval == 0)
	{
		//$(".btn_visu_nc").attr('disabled',true).css({"background":"#CCC","color":"#F0F0F0","border-color":"#CCCCCC"});
		$droit = 'disabled = disabled';
		$style_droit = 'background:#CCCCCC;color:#F0F0F0;border-color:#CCCCCC';
	}
	else
	{
		$droit = '';
		$style_droit = 'background:#E0E9F5;';
	}
	if( ($fnc_id != '' && $notation_id != '') || $commentaire_si !='' )
	{
	     if($fnc_id == ''){
		    $fnc_id = 0;
		 }
      
		 $zHtml .= '<input style="display:none;'.$style_droit.'" '.$droit.' class="si_par_classement_'.$key_section.' btn_visu_nc" type="button" id="btn_nc_'.$id_grille.'_'.$item['id_grille_application'].'" name="commentaire_si" value="Créer" onclick=create_nc_si('.$id_grille.','.$item['eliminatoire'].','.$item['id_grille_application'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.') />';
		 
		 $zHtml .= '<input style="display:inline;background:#E0E9F5;" type="button" value="Consulter" class="btn_visu_nc btn_consult" id="btn_consulter_nc_si_'.$id_grille.'_'.$item['id_grille_application'].'" onclick=create_nc_si('.$id_grille.','.$item['eliminatoire'].','.$item['id_grille_application'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.') />';
		 
		 $zHtml .= '<input style="display:none;'.$style_droit.'" '.$droit.' class="si_par_classement_'.$key_section.' btn_visu_nc" type="button" id="btn_editer_nc_si_'.$id_grille.'_'.$item['id_grille_application'].'" name="commentaire_si" value="Editer" onclick=create_nc_si('.$id_grille.','.$item['eliminatoire'].','.$item['id_grille_application'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.') />';
		 
		 $zHtml .= '<input style="display:inline;'.$style_droit.'" '.$droit.' class="btn_visu_nc" type="button" value="Supprimer" id="remove_nc_'.$id_grille.'_'.$item['id_grille_application'].'" onclick =annuler_nc_si('.$fnc_id.','.$id_grille.','.$item['id_grille_application'].','.$item['eliminatoire'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.') />';
		 
		 $zHtml .= '<input value="Annuler" type="button" class="btn_visu_nc" style="display:none;'.$style_droit.'" id="annuler_nc_'.$id_grille.'_'.$item['id_grille_application'].'" onclick=effacer_nc_si('.$id_grille.','.$item['id_grille_application'].') />';
		 $zHtml .= '<input type="hidden" value="1" id="id_test_nc_si_'.$id_grille.'_'.$item['id_grille_application'].'"/>';
		 
	}  
	else
	{
		 //$zHtml .= '<input style="display:none;'.$style_droit.'" '.$droit.' class="btn_visu_nc" type="button" value="Annuler" id="remove_nc_'.$id_grille.'_'.$item['id_grille_application'].'" onclick =annuler_nc_si('.$fnc_id.','.$id_grille.','.$item['id_grille_application'].','.$item['eliminatoire'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.') />';
		 
		 $zHtml .= '<input style="display:inline;'.$style_droit.'" '.$droit.' class="si_par_classement_'.$key_section.' btn_visu_nc" type="button" id="btn_nc_'.$id_grille.'_'.$item['id_grille_application'].'" name="commentaire_si" value="Créer" onclick=create_nc_si('.$id_grille.','.$item['eliminatoire'].','.$item['id_grille_application'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.') />';
		 
		 $zHtml .= '<input style="display:none;'.$style_droit.'" '.$droit.' class="si_par_classement_'.$key_section.' btn_visu_nc" type="button" id="btn_editer_nc_si_'.$id_grille.'_'.$item['id_grille_application'].'" name="commentaire_si" value="Editer" onclick=create_nc_si('.$id_grille.','.$item['eliminatoire'].','.$item['id_grille_application'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.') />';
		 
		 $zHtml .= '<input value="Annuler" type="button" class="btn_visu_nc" style="display:none;'.$style_droit.'" id="annuler_nc_'.$id_grille.'_'.$item['id_grille_application'].'" onclick=effacer_nc_si('.$id_grille.','.$item['id_grille_application'].') />';
		 
		 $zHtml .= '<input type="hidden" value="0" id="id_test_nc_si_'.$id_grille.'_'.$item['id_grille_application'].'"/>';
	}

$zHtml .=  '<input class="repartition_class" type="hidden" id="rep_'.$item['id_grille_application'].'" value="'.$item['id_repartition'].'"  />
<input type="hidden" class="initval_test" id="init_val_'.$id_grille.'" value="'.$test_comment.'"/>
</td>';

/**
 $zHtml .= '<td>';
 $zHtml .= '<input class="si_par_classement_'.$key_section.'" type="button" id="btn_nc" name="commentaire_si" id="commentaire_si_'.$id_grille.'_'.$item['id_grille_application'].'"value="NC" onclick=create_nc('.$id_grille.','.$item['eliminatoire'].','.$item['id_grille_application'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.')>

<input class="si_par_classement_'.$key_section.'" type="button" id="btn_nc" name="commentaire_si" id="commentaire_si_'.$id_grille.'_'.$item['id_grille_application'].'"value="SI" onclick=create_nc_si('.$id_grille.','.$item['eliminatoire'].','.$item['id_grille_application'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.')>';
$zHtml .= '</td>';
*/
					    
					       $total_classement += $point * $ponderation; // Njiva
						   if( $commentaire_si != '' ){
						      $count_si  = $count_si+1;
						   //echo $key_section.'ixi:'.$commentaire_si.'<br>';
						     $tab_penalite_classement[$key_section][]=$commentaire_si;
							 //echo $key_section.'##'.$commentaire_si.'<br>';
						   }
						 
					}
					/*********/
					
					$zHtml .= '</tr>';
					} 
					else 
					{
						$zHtml .= '<tr class="crit">
						<td '.$cl1.' style="text-align:center;">' . $note_ . '</td>
						<td '.$cl1.'>'.$description.'</td></tr>';
					}
					$counter3 ++;
					$nb_n ++;
				}
				$counter2 ++;
				$a++;
			}
			$counter1++;
		}   

		/*********** Njiva *********************/
		
		//if($key_section == 17) echo 'aaa'.$sum_ponderation;
		if($sum_ponderation == 0)
        {
        	if($id_client == 643 || $id_client == 642)
        	{
				$total_classement = 100;
			}
			else
			{
				$total_classement = 1;
			}
			$sum_ponderation = 1;
		}
		else
		{
			// TTL : ne pas arrondir qu'à la fin => dans le total géneral
			// $total_classement = number_format($total_classement/$sum_ponderation,2);
			$total_classement = $total_classement/$sum_ponderation;
		}
		
		//$total_classement = number_format($total_classement/$sum_ponderation,2);
		/***********30-Juin-2014**************/
	    $total_classement =  get_nombre_si($count_si,$key_section,$penalite_projet,$total_classement); 
	
		/*************************/
		if($total_classement <= -1)
		{
			$total_classement = 0;
		}
		/*******************************************/
		
		/******************** Classement ***********************/ // Njiva
		$border = '';
		$back = 'background:#DCEDE9;';
		//$back = 'background:#ACCCE3;';
	  $valeur_ponderation_classement = $val['ponderation_classement']; //si on prend les valeurs dans cc_sr_grille_classement
		//$valeur_ponderation_classement = $_total_base; // si on totalise les valeurs des ponderations
		//if($val['ponderation_classement'] == '' || $val['ponderation_classement'] == 0)
		//if($valeur_ponderation_classement == '' || $valeur_ponderation_classement == 0)
		if($valeur_ponderation_classement == '') 
		{
			//$border = "border:1px solid red;";
			//$back = "background:#FE8F8F;";
			//$back = "background:#FF5858;";
			$valeur_ponderation_classement = 0;
		}
		/* Ajouté le 31/07/2014 */ 
		if(($id_type_traitement == 1 || $id_type_traitement == 2) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
		{
			$total_classement = $total_classement * 10;
		}
		/* ************* */
		$produit_base_moyenneClassement = $total_classement * $valeur_ponderation_classement; //#6EA2F7  #ADEEF4 #DCEDE9 //'. $val['libelle'].'
		//hng
		// if($id_type_traitement == 3)
		if($id_type_traitement == 3 || $id_type_traitement == 4)
		{
			/*if($item['flag_ponderation'][$description] == 1 && $id_notation != 0)
			$total_classement = $total_classement*10;
			else*/
			$total_classement = $total_classement / 10;
			$produit_base_moyenneClassement = $produit_base_moyenneClassement /10;
		}
		

		if ($totaux == '')
			 $totaux .= $key_section.'|'.number_format($total_classement,2).'|'.$valeur_ponderation_classement.'|'.$sectionkey;
		else $totaux .= '||'.$key_section.'|'.number_format($total_classement,2).'|'.$valeur_ponderation_classement.'|'.$sectionkey;
		
		$zHtml .= '<tr style="background-color:#A2BCC1;">
		<td style="font-weight:bold;font-size:11px;text-align:right;color:#FFFFFF;" colspan="4">TOTAL '.$val['libelle'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		
		<td><input type="hidden" class="all_section" value="'.$sectionkey.'" />
		<input name="classement_pond_'.$valeur_ponderation_classement/*$val['ponderation_classement']*/.'" readonly class="par_section_'.$sectionkey.'" id="total_par_classement_'.$key_section.'" type="text" style="text-align:center; width:50px;background:#DCEDE9;" value="'.number_format($total_classement,2).'">
		</td>

		<td style="'.$border.'">
		<input class="classement_base" id="base_par_classement_'.$key_section .'" readonly type="text" value="'.$valeur_ponderation_classement.'" style="text-align:center; width:50px;'.$back.'">
		</td>
		
		<td align="center"><input class="classement_produit" id="classement_base_'.$key_section.'" readonly type="text" style="text-align:center; width:50px;background:#DCEDE9;" value="'.number_format($produit_base_moyenneClassement,2).'" /></td>
		<td colspan="2"></td>
		</tr>';
		
		//$zHtml .= '<tr><td colspan="9"></td></tr>';
		
		$ponderation_section = $val['ponderation_section']; // Njiva
		/*******************************************************/
		
		$total_section += $total_classement * $val['ponderation_classement'];
		//$total_section += $total_classement * $valeur_ponderation_classement;
		$sum_ponderation_classement += $val['ponderation_classement'];
		//$sum_ponderation_classement += $valeur_ponderation_classement;
		$sum_produit_base_moyenneClassement += $produit_base_moyenneClassement;
		
		//$_sum_total_base += $val['ponderation_classement'];
		$_sum_total_base += $valeur_ponderation_classement;
		$_sum_total_produit += $produit_base_moyenneClassement;
	}
	/******************** Section (FOND / FORME) ***********************/
	$totalS = number_format($total_section / $sum_ponderation_classement,2);
	if($totalS == -1)
	{
		$totalS = 0;
	}
	elseif (is_nan($totalS))
	{
		$totalS = 0;
	}
	/*****tsilavina******/
	  //$color='#0140AF';
	  $color='#814111';
	  $back1 = 'background:#F2E6DD;';
	  $back = 'background:#F2E6DD;';
	        if( $sectionkey =='FOND' ){
			    //$color='#01A9B4';
			    //$color='#019197';
			    //$color='#006193'; //Bleu
			    $color='#485B6A';
			    $back1 = 'background:#CCCCCC;';
			    $back = 'background:#CCCCCC;';
			}
	/***********/
	/***********SECTION************ point / base / note * Coeff *************************/
	$border = '';
	//$back = 'background:#ACCCE3;';
	//$back = 'background:#F2E6DD;';
	$valeur_ponderation_section = $ponderation_section;
	if($ponderation_section == '' || $ponderation_section == 0)
	//if($ponderation_section == '')
	{
		//$border = "border:1px solid red;";
		//$back = "background:#FE8F8F;"; // alerte
		//$back = "background:#FF5858;"; // alerte
		//$valeur_ponderation_section = 0;
		$valeur_ponderation_section = 1; // Modif le 04/07/2014
	}
	$produit_section = $totalS * $ponderation_section;
	$zHtml .= '<tr style="background-color:'.$color.'">
	<td colspan="4" style="font-weight:bold;font-size:11px;color:#FFFFFF;text-align:right;border:none">TOTAL '. $sectionkey .' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	
	<td>
	<input name="par_section_'.$ponderation_section.'" class="par_section" readonly id="total_'. $sectionkey .'" type="text" style="text-align:center; width:50px;'.$back1.'font-weight:bold;" value="'.$totalS.'">
	</td>
	
	<td style="'.$border.'">
	<input class="section_base" readonly type="text" style="text-align:center;font-weight:bold;width:50px;'.$back.'" id="ponderation_'.$sectionkey.'" value="'.$valeur_ponderation_section.'">
	</td>
	
	<td>
	<input readonly type="text" class="section_produit" style="text-align:center;font-weight:bold;width:50px;'.$back1.'" id="produit_ponderation_'.$sectionkey.'" value="'.$produit_section.'">
	</td>
	
	<td colspan="2"></td>
	</tr>';					 
	/******************************************************************************/
	if(isset($sum_general)) $sum_general += $totalS * $ponderation_section;
	else $sum_general = $totalS * $ponderation_section;
	if(isset($sum_ponderation_section)) $sum_ponderation_section += $ponderation_section;
	else $sum_ponderation_section = $ponderation_section;
	
}
$zHtml .= '<tr style="background-color:#289696">
<td colspan="5" style="font-weight:bold;font-size:12px;text-align:right;color:#FFFFFF">TOTAL GENERAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
//$totalG = number_format($_sum_total_produit / $_sum_total_base,2);
//$totalG = number_format(($sum_produit_base_moyenneClassement / $sum_ponderation_classement)*10,2);
//$totalG = number_format(($sum_general / $sum_ponderation_section)*10,2);
if(isset($sum_general) && isset($sum_ponderation_section) != 0)
{
	$totalG = $sum_general / $sum_ponderation_section;
}
else
{
	$totalG = '0.00';
}
//$totalG_10 = number_format(($sum_general / $sum_ponderation_section)*10,2);
if (is_nan($totalG))
{
	$totalG = '0.00';
	//$totalG_10 = '0.00';
}
//$totalG = $totalG * 100;
if($test == 0)
{
	if(isset($sum_general) && isset($sum_ponderation_section))
	{
		//$total_general = number_format(($sum_general / $sum_ponderation_section)*10,2);
		if($sum_ponderation_section == 0)
		{
			$total_general = '0.00';
		}
		else
		{
			$total_general = $sum_general / $sum_ponderation_section;
		}
		//$total_general = number_format(($sum_produit_base_moyenneClassement / $sum_ponderation_classement)*10,2);
		//$total_general = number_format($_sum_total_produit / $_sum_total_base,2);
		//$total_general_10 = number_format(($sum_general / $sum_ponderation_section)*10,2);
	}
	else
	{
		$total_general = '0.00';
	}
	if (is_nan($total_general))
	{
		$total_general = '0.00';
		//$total_general_10 = '0.00';
	}
}

//hng  || $id_type_traitement == 3
// if(($id_type_traitement == 1 || $id_type_traitement == 2 || $id_type_traitement == 3) && $id_client != 643 && ($id_client != 642)) //client différent de DELAMAISON
if(($id_type_traitement == 1 || $id_type_traitement == 2 || $id_type_traitement == 3 || $id_type_traitement == 4) && $id_client != 643 && ($id_client != 642)) //client différent de DELAMAISON
{
	$total_general = number_format(($total_general * 10),2);
	$totalG = number_format(($totalG * 10),2);
}
else
{
	$total_general = number_format($total_general,2);
	$totalG = number_format($totalG,2);
}
/* Ajouté le 29/07/2014 **************************/
//$total_general = $total_general * 100;
/****************************************************/
$zHtml .= '<td><input id="total_general" type="text" style="text-align:center; width:50px;background:#4DBBBB;font-weight:bold;" value="'.$total_general.'"></td>';
//$zHtml .= '<td><input id="total_general_10" type="text" style="text-align:center; width:50px;background:#4DBBBB;font-weight:bold;" value="'.$total_general_10.'"></td>';

//echo 'total general = '.$total_general;
if($total_general < 29)
{
	$affiche = 'Insuffisant';
	$couleur = "background:#D10E20;";
}
else if($total_general >= 29 && $total_general < 74)
{
	$affiche = 'Passable';
	$couleur = "background:#FFD722;";
}
else if($total_general >= 74 && $total_general < 80)
{
	$affiche = 'Satisfaisant';
	$couleur = "background:#FF9968;";
}
else if($total_general >= 80)
{
	$affiche = 'Excellent';
	$couleur = "background:#3AD539;";
}
$zHtml .= '
<td id="td_appreciation" colspan="4" style="font-weight:bold;font-size:12px;text-align:center;'.$couleur.'">'.$affiche.'</td>
</tr>
<tr style="background-color:#CCCCCC">
<td colspan="5" style="font-weight:bold;font-size:11px;color:#FFFFFF;text-align:center;"></td>
<td>
<input id="total_reel" type="text" style="text-align:center; width:50px;background:#CCCCCC;" value="'.$totalG.'">
</td>';

//$zHtml .= '<td><input id="total_reel_10" type="text" style="text-align:center; width:50px;background:#CCCCCC;" value="'.$totalG_10.'"></td>';

$zHtml .= '<td colspan="4" style="background-color:#FF7C81;font-weight:bold;font-size:12px;text-align:center;"><span id="nb_elimin">' /*.$nbReelEliminatoire.'**'*/.$nbeEliminatoire. '</span> Situations inacceptables</td>
</tr>';				

$zHtml .= '</tbody></table>#**#**#';
if($droit_eval == 0)
{
	$droit = 'display:none;';
}
else
{
	$droit = 'display:inline;';
}

$result_global_nc = getGlobalNCByNotation($id_notation);
$nombre_global_nc = pg_num_rows($result_global_nc);
if($nombre_global_nc != 0 && $id_notation != 0)
{
	$test_global = 1;
	if($droit_eval == 0)
	{
		$suppression = "display:none;";
	}
	else
	{
		$suppression = "display:inline;";
	}
	$consultation = "display:inline;";
	$creation = "display:none;";
	$edition = "display:none;";
	$annulation = "display:none;";
}
else
{
	$test_global = 0;
	$suppression = "display:none;";
	$consultation = "display:none;";
	if($droit_eval == 0)
	{
		$creation = "display:none;";
	}
	else
	{
		$creation = "display:inline;";
	}
	$edition = "display:none;";
	$annulation = "display:none;";
}
$key_section = 0;
$id_grille = 0;
$item['eliminatoire'] = 0;
$item['id_grille_application'] = 0;
$key = 0;
$sectionkey = 0;
$zHtml .= '<div style="left:80%;position:absolute;width:222px;" id="id_div_btn_save" >';
$zHtml .= '<table width="100%" style="text-align:right;"><tr><td>';
$zHtml .= '<input style="'.$creation.'background:#A2BCC1;width:83px;" class="si_par_classement_'.$key_section.' btn_enregistrer" type="button" name="commentaire_si" id="btn_global_nc" value="NC" onclick=create_nc('.$id_grille.','.$item['eliminatoire'].','.$item['id_grille_application'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.')>

<input style="'.$consultation.'background:#A2BCC1;width:83px;" class="si_par_classement_'.$key_section.' btn_enregistrer" type="button" name="commentaire_si" id="btn_global_consulter_nc" value="Consulter NC" onclick=create_nc('.$id_grille.','.$item['eliminatoire'].','.$item['id_grille_application'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.')>

<input style="'.$edition.'background:#A2BCC1;width:83px;" class="si_par_classement_'.$key_section.' btn_enregistrer" type="button" name="commentaire_si" id="btn_global_editer_nc" value="Editer NC" onclick=create_nc('.$id_grille.','.$item['eliminatoire'].','.$item['id_grille_application'].','.$key_section.','.$key.',\''.$sectionkey.'\','.$flag_penalite.','.json_encode($penalite_projet).',1,'.$id_type_traitement.','.$id_projet.','.$id_client.','.$id_application.','.$id_tlc.','.$id_fichier.','.$id_notation.')>

<input style="'.$suppression.'background:#A2BCC1;width:83px;" class="si_par_classement_'.$key_section.' btn_enregistrer" type="button" name="commentaire_si" id="btn_global_supprimer_nc" value="Supprimer NC" onclick=supprimer_global_nc('.$id_notation.')>

<input type="button" name="commentaire_si" style="'.$annulation.'background:#A2BCC1;width:83px;" class="si_par_classement_'.$key_section.' btn_enregistrer" id="btn_global_annuler_nc" value="Annuler NC" onclick=annuler_global_nc(); />';

$zHtml .= '<input type="hidden" id="id_test_global_nc" value="'.$test_global.'" />';
$zHtml .= '<textarea hidden id="description_global_nc"></textarea>';
$zHtml .= '<textarea hidden id="exigence_global_nc"></textarea>';

$zHtml .= '<textarea hidden id="gravite"></textarea>';
$zHtml .= '<textarea hidden id="frequence"></textarea>';
$zHtml .= '<textarea hidden id="cat_gravite"></textarea>';
$zHtml .= '<textarea hidden id="cat_frequence"></textarea>';
$zHtml .= '<input hidden id="criticite"  />';

$zHtml .= '</td></tr><tr><td>';

$zHtml .= '<input style="width: 100px;'.$droit.'" type="button" id="id_enregistrer" class="btn_enregistrer" value="Enregistrer" title="Enregistrement" onclick="enregistrement_notation_();" />';

$zHtml .= '</td></tr>
<input type="hidden" id="totaux" value="'.$totaux.'"/>
</table>';
$zHtml .= '</div>';
$zHtml .= getAppuiAmeliorationPrecobyID($id_notation);
return $zHtml;	
}

function getGlobalNCByNotation($id_notation)
{
	global $conn;
	$sql = "select * from nc_fiche where fnc_id_notation = ".$id_notation." and fnc_id_grille_application = 0";
	$query  = pg_query( $sql ) or die(pg_last_error());
	return $query;
}

function getAppuiAmeliorationPrecobyID($id_notation)
{
	global $conn;
	$sql = "select point_appui, point_amelioration, preconisation from cc_sr_notation where id_notation = ".$id_notation;
	$query  = pg_query( $sql ) or die(pg_last_error());
	$result = pg_fetch_array($query);
	$point_appui = $result['point_appui'];
	$point_amelioration = $result['point_amelioration'];
	$preconisation = $result['preconisation'];
	return '#**#**#'.utf8_decode($point_appui).'#**#**#'.utf8_decode($point_amelioration).'#**#**#'.utf8_decode($preconisation);
}

function getLibelleById($id_projet,$id_client,$id_application,$id_type_traitement,$id_tlc,$nom_fichier)
{
	global $conn;
	$sql_projet = "select nom_projet from cc_sr_projet where id_projet = ".$id_projet;
	$query  = pg_query( $sql_projet ) or die(pg_last_error());
    $result_projet = pg_fetch_array($query);
    $nom_projet = $result_projet['nom_projet'];
    
    $sql_client = "select nom_client from gu_client where id_client = ".$id_client;
	$query  = pg_query( $sql_client ) or die(pg_last_error());
    $result_client = pg_fetch_array($query);
    $nom_client = $result_client['nom_client'];
    
    $sql_application = "select code from gu_application where id_application = ".$id_application;
	$query  = pg_query( $sql_application ) or die(pg_last_error());
    $result_application = pg_fetch_array($query);
    $nom_application = $result_application['code'];
    
    $sql_type_traitement = "select libelle_type_traitement from cc_sr_type_traitement where id_type_traitement = ".$id_type_traitement;
	$query  = pg_query( $sql_type_traitement ) or die(pg_last_error());
    $result_type_traitement = pg_fetch_array($query);
    $nom_type_traitement = $result_type_traitement['libelle_type_traitement'];
    
    // $sql_tlc = "SELECT  matricule, prenompersonnel FROM personnel WHERE actifpers='Active'  AND (fonctioncourante ='TC' or fonctioncourante ='CONSEILLER' or fonctioncourante ='FONC_MAIL') 
    // AND matricule = ".$id_tlc." order by matricule ASC";
	
	$sql_tlc = " SELECT  matricule, 
					prenompersonnel,
					fonctioncourante
				FROM personnel 
				WHERE actifpers = 'Active' 
					AND matricule = ".$id_tlc." 
				order by matricule ASC ";
	
	$query  = pg_query($sql_tlc) or die(pg_last_error());
	$result_tlc = pg_fetch_array($query);
    $nom_tlc = $result_tlc['matricule']." - ".$result_tlc['prenompersonnel']." ( ".$result_tlc['fonctioncourante']." )";
    
    //$sql_fichier = "select id_fichier, nom_fichier from cc_sr_fichier where id_fichier = ".$id_fichier;
    $sql_fichier = "select id_fichier, nom_fichier from cc_sr_fichier where nom_fichier = '".pg_escape_string(trim($nom_fichier))."'";
	//echo $sql_fichier;
	$query  = pg_query( $sql_fichier ) or die(pg_last_error());
	if(pg_num_rows($query) != 0)
	{
		while($result_fichier = pg_fetch_array($query))
		{
			//$nom_fichier = $result_fichier['nom_fichier'];
	    	$id_fichier_ = $result_fichier['id_fichier'];
		}
	}
	else 
	{
		$id_fichier_ = 0;
	}
    
    return $nom_projet.'||'.$nom_client.'||'.$nom_application.'||'.$nom_type_traitement.'||'.$nom_tlc.'||'.$id_fichier_;
}

function getDescByApplication($id_application)
{
	global $conn;
	$sql_projet = "select nom_application from gu_application where id_application = ".$id_application;
	$query  = pg_query( $sql_projet ) or die(pg_last_error());
	return $query;
}

function getAllRepartition()
{
	global $conn;
	$sql = "select * from cc_sr_repartition order by ordre";
	$query  = pg_query( $sql ) or die(pg_last_error());
	return $query;
}

function calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,$_is)
{ 
	global $conn;
	$nombre_is = 0;
	if(isset($tab[$_is])) $nombre_is = $tab[$_is];
	$is = '';
	for($i=0;$i<count($nombre_is);$i++)
	{
		$is .= isset($tab[$_is][$i]) ? $tab[$_is][$i] : 0;
		if($i != (count($nombre_is) - 1))
		{
			$is .= ',';
		}
	}
	if($is == '') $is = 0;
$sql_IS = "select a.id_notation,a.id_grille,a.note,a.flag_ponderation , b.ponderation,
case when flag_ponderation = 1 then 0 else ponderation end as pond, flag_is
from cc_sr_indicateur_notation a 
inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application 
where a.id_grille_application in (".$is.") 
and a.id_notation = ".$id_notation;
 

	//if($_is == 'IS6') echo $sql_IS;
	$query  = pg_query( $sql_IS ) or die(pg_last_error());
	return $query;
}

function getCalculRep($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab_rep)
{
	global $conn;
	$sql = "select * from cc_sr_repartition order by ordre";
	$query  = pg_query( $sql ) or die(pg_last_error());
	$table = array(); 
	while ($res_rep = pg_fetch_array($query))
	{
		$id_repartition = $res_rep['id_repartition'];
		if(!empty($tab_rep[$id_repartition]))
		{
			$rep = '';
			for($i=0;$i<count($tab_rep[$id_repartition]);$i++)
			{
				$rep .= $tab_rep[$id_repartition][$i];
				if($i != (count($tab_rep[$id_repartition]) - 1))
				{
					$rep .= ',';
				}
			}
			$_query = repartition($id_client,$id_projet,$id_application,$id_notation,$id_fichier,$rep);
			$resultat = pg_fetch_array($_query);
			$table[$id_notation][$id_repartition] = $resultat['nb_csi'];
		}
		//if(empty($table[$id_notation][$id_repartition]))
		else
		{
			$table[$id_notation][$id_repartition] = 0;
		}
	}
	return $table;
}

function repartition($id_client,$id_projet,$id_application,$id_notation,$id_fichier,$rep)
{
	global $conn;
	$sql = "select count(a.commentaire_si) as nb_csi from cc_sr_indicateur_notation a 
	inner join cc_sr_grille_application b on a.id_grille_application = b.id_grille_application 
	inner join cc_sr_notation c on c.id_notation = a.id_notation
	where b.id_client = ".$id_client." and b.id_projet = ".$id_projet." and b.id_application = ".$id_application." 
	and a.id_notation = ".$id_notation." and c.id_fichier = ".$id_fichier." and b.id_grille_application in (".$rep.") 
	and a.commentaire_si != ''";
	$query  = pg_query( $sql ) or die(pg_last_error());
	return $query;
}

function getNotationCom_parIs( $id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_tlc){
    	global $conn;
		$arrayCom = array();
	$sql = "select distinct c.id_notation, e.id_type_traitement,a.flag_is
from cc_sr_grille_application a inner join cc_sr_indicateur_notation b on a.id_grille_application = b.id_grille_application 
inner join cc_sr_notation c on c.id_notation = b.id_notation 
left join cc_sr_grille d on d.id_grille = a.id_grille 
left join cc_sr_categorie_grille e on e.id_categorie_grille = d.id_categorie_grille
	where a.id_projet = ".$id_projet." and a.id_client = ".$id_client." and a.id_application = ".$id_application." and c.id_fichier = ".$id_fichier." and e.id_type_traitement = ".$id_type_traitement." and c.matricule = ".$id_tlc." 
	order by c.id_notation";
	/*--and c.id_notation = ".$id_notation ;*/
	//echo $sql; exit;
	$query  = pg_query( $sql ) or die(pg_last_error());
	  for($i=0;$i<pg_num_rows($query);$i++){
	     $lg = pg_fetch_array($query,$i);
		   
			
		 $arrayCom[$lg['flag_is']][$i] = $lg['id_notation'];
	  
	  }
    return $arrayCom;
}

function updateNote($note,$id_notation)
{
	global $conn;
	$sql = "update cc_sr_notation set note = ".$note." where id_notation = ".$id_notation;
	$query  = pg_query( $sql ) or die(pg_last_error());
	return 1;
}

function actualiserListeNotation($id_fichier,$id_projet, $id_client, $id_application, $id_type_traitement,$id_tlc)
{
	$result_com = getNotationCom($id_fichier,$id_projet, $id_client, $id_application, $id_type_traitement,$id_tlc);
	$somme_total_general = 0;
	$somme_note10 = 0;
	$str = '';
	$table_valeur = array();
	$table_ind = array();
	$tab_ind = array();
	$tab = array();
	$tab_rep = array();
	$moyenne_is4 = array();
	$moyenne_is5 = array();
	$moyenne_is6 = array();
	$moyenne_is7 = array();
	$moyenne_is5_v7 = array();
	$_repartition = array();
	$_result_rep = array();
	$_r = array();
	while ($res_com = pg_fetch_array($result_com))
	{
		$id_notation = $res_com['id_notation'];
		$str = calculTotalGeneral($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement);
		$table_valeur = explode('||',$str); // total_general || nb_eliminatoire || &id_grille_application|IS4_IS6|repartition 
		
		$note =  (float)$table_valeur[0];
		$retour = updateNote($note,$id_notation);
		
		if(($id_type_traitement == 1 || $id_type_traitement == 2) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
		{
			//$somme_note10 += ($table_valeur[0]*100) / 10;
			$somme_note10 += $table_valeur[0];
		
			//$somme_total_general += $table_valeur[0]*100;
			$somme_total_general += $table_valeur[0]*10;
			
			//$totalG[$id_notation] = $table_valeur[0]*100;
			$totalG[$id_notation] = $table_valeur[0]*10;
		}
		else
		{
			$somme_note10 += $table_valeur[0] / 10;
			$somme_total_general += $table_valeur[0];
			$totalG[$id_notation] = $table_valeur[0];
		}
		
		$valeur_indicateur = explode('&',$table_valeur[2]); // &1|IS4_IS6&2|IS4&17|IS5&12|IS4&11|IS7&13|IS6&19|IS7&25|IS4&7|IS6 
		for($i=1;$i<count($valeur_indicateur);$i++) 
		{
			$table_ind = explode('|',$valeur_indicateur[$i]);
			$tab_ind = explode(';',$table_ind[1]);
			for($j=0;$j<count($tab_ind);$j++)
			{
				$tab[$tab_ind[$j]][] = $table_ind[0];
			}
			$tab_rep[$table_ind[2]][] = $table_ind[0]; // $tab_rep[id_repartition][] = id_grille_application
		}
	
		/************** IS4 **********************/
		$result_is4 = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,'IS4');
		$ponderation_is4 = 0;
		$produit_somme_is4 = 0;
		$nandalo_is4=0;
		while($res_is4 = pg_fetch_array($result_is4))
		{
			$ponderation_is4 += $res_is4['pond'];
			$produit_somme_is4 += $res_is4['note'] * $res_is4['pond'];
			$nandalo_is4=1;
		}
		
		if(( $id_type_traitement == 1 || $id_type_traitement == 2 ) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
		{		   
			if($produit_somme_is4 == $ponderation_is4 && $nandalo_is4==1) $moyenne_is4[$id_notation] = 1;
			else $moyenne_is4[$id_notation] = 0;
		}
		else
		{
			if($ponderation_is4 != 0)
			{
				if(($produit_somme_is4 / $ponderation_is4)<100) $moyenne_is4[$id_notation] = 0;
	        	else $moyenne_is4[$id_notation] = 1;
			}
	        else $moyenne_is4[$id_notation] = 0;
	    }
	    
		/*if(($produit_somme_is4 / $ponderation_is4)<100) $moyenne_is4[$id_notation] = 0;
		else $moyenne_is4[$id_notation] = 1;*/
		
		/************** IS5 **********************/
		$result_is5 = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,'IS5');
		$ponderation_is5 = 0;
		$produit_somme_is5 = 0;
		$nandalo_is5=0;
		while($res_is5 = pg_fetch_array($result_is5))
		{
			$ponderation_is5 += $res_is5['pond'];
			$produit_somme_is5 += $res_is5['note'] * $res_is5['pond'];
			$nandalo_is5=1;
		}
		
		if(( $id_type_traitement == 1 || $id_type_traitement == 2 ) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
		{		   
		   if($produit_somme_is5 == $ponderation_is5 && $nandalo_is5==1) $moyenne_is5[$id_notation] = 1;
	       else $moyenne_is5[$id_notation] = 0;
		}
		else
		{
			if($ponderation_is5 != 0)
			{
				if(($produit_somme_is5 / $ponderation_is5)<100) $moyenne_is5[$id_notation] = 0;
	        	else $moyenne_is5[$id_notation] = 1;
			}
	        else $moyenne_is5[$id_notation] = 0;
	    }
	    
		/*if(($produit_somme_is5 / $ponderation_is5)<100) $moyenne_is5[$id_notation] = 0;
		else $moyenne_is5[$id_notation] = 1;*/
		
		/************** IS6 **********************/
		$result_is6 = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,'IS6');
		$ponderation_is6 = 0;
		$produit_somme_is6 = 0;
		$nandalo_is6=0;
		
		while($res_is6 = pg_fetch_array($result_is6))
		{
			$ponderation_is6 += $res_is6['pond'];
			$produit_somme_is6 += $res_is6['note'] * $res_is6['pond'];
			$nandalo_is6=1;
		}
	
	    if(( $id_type_traitement == 1 || $id_type_traitement == 2 ) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
	    {		   
		   if($produit_somme_is6 == $ponderation_is6 && $nandalo_is6==1) $moyenne_is6[$id_notation] = 1;
	       else $moyenne_is6[$id_notation] = 0;
		}
		else
		{
			if($ponderation_is6 != 0)
			{
				if(($produit_somme_is6 / $ponderation_is6)<100) $moyenne_is6[$id_notation] = 0;
	        	else $moyenne_is6[$id_notation] = 1;
			}
	        else $moyenne_is6[$id_notation] = 0;
	    }
		
		/************** IS7 **********************/
		$result_is7 = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,'IS7');
		$ponderation_is7 = 0;
		$produit_somme_is7 = 0;
		$nandalo_is7=0;
		while($res_is7 = pg_fetch_array($result_is7))
		{
			$ponderation_is7 += $res_is7['pond'];
			$produit_somme_is7 += $res_is7['note'] * $res_is7['pond'];
			$nandalo_is7=1;
		}
		
		if(( $id_type_traitement == 1 || $id_type_traitement == 2 ) && ($id_client != 643 && $id_client != 642)) //client différent de DELAMAISON
		{		   
		   if($produit_somme_is7 == $ponderation_is7  && $nandalo_is7==1) $moyenne_is7[$id_notation] = 1;
	       else $moyenne_is7[$id_notation] = 0;
		}
		else
		{
			if($ponderation_is7 != 0)
			{
				if(($produit_somme_is7 / $ponderation_is7)<100) $moyenne_is7[$id_notation] = 0;
	        	else $moyenne_is7[$id_notation] = 1;
			}
	        else $moyenne_is7[$id_notation] = 0;
	    }
	    
		/************** IS5_V7 **********************/
		$result_is5_v7 = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab,'IS5_V7');
		$ponderation_is5_v7 = 0;
		$produit_somme_is5_v7 = 0;
		$nandalo_is5_v7=0;
		while($res_is5_v7 = pg_fetch_array($result_is5_v7))
		{
			$ponderation_is5_v7 += $res_is5_v7['pond'];
			$produit_somme_is5_v7 += $res_is5_v7['note'] * $res_is5_v7['pond'];
			$nandalo_is5_v7=1;
		}
		
		if( $id_type_traitement == 1 || $id_type_traitement == 2 && ($id_client != 643 && $id_client != 642))
		{		   
			if($produit_somme_is5_v7 == $ponderation_is5_v7 && $nandalo_is5_v7==1 ) $moyenne_is5_v7[$id_notation] = 1;
			else $moyenne_is5_v7[$id_notation] = 0;
		}
		else
		{
			if($ponderation_is5_v7 != 0)
			{
				if(($produit_somme_is5_v7 / $ponderation_is5_v7)<100) $moyenne_is5_v7[$id_notation] = 0;
	        	else $moyenne_is5_v7[$id_notation] = 1;
			}
	        else $moyenne_is5_v7[$id_notation] = 0; 
	    }
		
		/*if(($produit_somme_is7 / $ponderation_is7)<100) $moyenne_is7[$id_notation] = 0;
		else $moyenne_is7[$id_notation] = 1;*/		
		/************* Repartition ***********************/
		$result_rep = getCalculRep($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$id_notation,$id_tlc,$tab_rep);
		foreach($result_rep as $_val)
		{
			for($a=1;$a<=count($_val);$a++)
			{
				if(isset($_r[$a])) $_r[$a] += $_val[$a];
				else $_r[$a] = $_val[$a];
			}
		}
	}

	/*********************************************************************************/	
	/*********************************************************************************/	
	$res_com = getNotationCom($id_fichier,$id_projet, $id_client, $id_application, $id_type_traitement,$id_tlc);
	$table_com = array();
	while ($_res = pg_fetch_array($res_com))
	{
		$table_com[] = $_res['id_notation'];
	}
	
	$repartition = getAllRepartition();
	$nb_rep_ = pg_num_rows($repartition);
	
	echo '
	<label>Nombre de répartition : </label><input type="text" size="5" id="id_nombre_repartition" value="'.$nb_rep_.'" />
	<table>';
	echo '<tr><td>Total</td><td>';
	foreach($table_com as $val)
	{
		echo '<input type="text" size="5" id="total_'.$val.'" class="class_total" value="'.number_format($totalG[$val],2).'" />';
	}
	echo '</td></tr>';
	
	echo '<tr><td>IS4</td><td>';
	foreach($table_com as $val)
	{
		echo '<input type="text" size="5" id="is4_'.$val.'" class="class_is_'.$val.' class_is4" value="'.$moyenne_is4[$val].'" />';
	}
	echo '</td></tr>';
	
	echo '<tr><td>IS5</td><td>';
	foreach($table_com as $val)
	{
		echo '<input type="text" size="5" id="is5_'.$val.'" class="class_is_'.$val.' class_is5" value="'.$moyenne_is5[$val].'" />';
	}
	echo '</td></tr>';
	
	echo '<tr><td>IS6</td><td>';
	foreach($table_com as $val)
	{
		echo '<input type="text" size="5" id="is6_'.$val.'" class="class_is_'.$val.' class_is6" value="'.$moyenne_is6[$val].'" />';
	}
	echo '</td></tr>';
	
	
	echo '<tr><td>IS7</td><td>';
	foreach($table_com as $val)
	{
		echo '<input type="text" size="5" id="is7_'.$val.'" class="class_is_'.$val.' class_is7" value="'.$moyenne_is7[$val].'"/>';
	}
	echo '</td></tr>';
	
	
	echo '<tr><td>IS5_V7</td><td>';
	foreach($table_com as $val)
	{
		echo '<input type="text" size="5" id="is5_v7_'.$val.'" class="class_is_'.$val.' class_is5_v7" value="'.$moyenne_is5_v7[$val].'"/>';
	}
	echo '</td></tr>';
	
	$repartition = getAllRepartition();
	while ($res_rep = pg_fetch_array($repartition))
	{
		echo '<tr><td>'.$res_rep['libelle_repartition'].'</td><td>';
		foreach($table_com as $val)
		{
			$result_rep = getCalculRep($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement,$val,$id_tlc,$tab_rep);
			echo '<input type="text" class="class_rep_'.$val.' class_rep_'.$res_rep['id_repartition'].'" size="5" id="'.$res_rep['id_repartition'].'_'.$val.'" value="'.$result_rep[$val][$res_rep['id_repartition']].'"/>';
		}
		echo '</td></tr>';
	}
	
	echo '
	</table>';
}

function get_flag_penalite( $id_projet ){
  global $conn;
  $sql = "SELECT flag_penalite FROM cc_sr_projet WHERE id_projet = {$id_projet} ";
  $query =  pg_query($conn,$sql) or die(pg_last_error());
  $rows = pg_fetch_row(  $query );
  return $rows[0];
 
}

function get_liste_penalite( $id_projet  ){
    global $conn;
	$array_penalite = array();
	$sql = "SELECT id_projet,flag_condition,valeur,penalite FROM cc_sr_projet_penalite WHERE id_projet = {$id_projet} ";
	$query = pg_query( $conn , $sql ) or die (pg_last_error());
	
	for($i=0;$i<pg_num_rows( $query );$i++){
	       $lg = pg_fetch_array( $query , $i);
		   $array_penalite['flagCondition'] = $lg['flag_condition'];
		   $array_penalite['valeur'] = $lg['valeur'];
		   $array_penalite['penalite'] = $lg['penalite'];
	}
	return $array_penalite ;
}
	   
	   /*****************************/

function get_prenom_personnel( $_matricule )
{
	global $conn;
	$sql_tlc = "SELECT  prenompersonnel FROM personnel WHERE actifpers='Active'  
	AND matricule = ".$_matricule." order by matricule ASC";
	$query = pg_query( $conn, $sql_tlc ) or die(pg_last_error());
	$result = pg_fetch_row( $query );
	return $result[0];
}
	 
function dupliquer_grille($id_projet,$id_client,$id_application,$new_projet,$new_client,$new_application,$test_penalite)
{
     global $conn;
	 $new_projet = get_projet_par_application( $new_client,$new_application );
	
	 if( $test_penalite==1 )
	 {
		           	   		  
	$sql_0 =  "INSERT INTO cc_sr_projet_penalite(
    id_projet, flag_condition, valeur,penalite,id_classement)
    SELECT ".$new_projet." as id_projet, flag_condition, valeur,penalite,id_classement
    FROM cc_sr_projet_penalite
    where id_projet = ".$id_projet." returning id_projet_penalite";
	$query_0 = pg_query($conn,$sql_0) or die (pg_last_error());
	$penalite_id = pg_fetch_result($query_0, 0, 'id_projet_penalite');
				if($penalite_id !='' || $penalite_id !=0){
				   $sql_update_penalite = "UPDATE cc_sr_projet SET flag_penalite=1 WHERE id_projet=".$new_projet." ";
				   $query_update_penalite = pg_query($conn,$sql_update_penalite)or die (pg_last_error());
				}
		  }
		 
		 $sql_1 = "INSERT INTO cc_sr_grille_application(
            id_grille, id_application, id_projet, 
            id_client, flag_notation, flag_eliminatoire, ponderation, flag_is, 
            id_repartition)
  SELECT id_grille, ".$new_application." as id_application, ".$new_projet." as id_projet, 
       ".$new_client." as id_client, flag_notation, flag_eliminatoire, ponderation, flag_is, 
       id_repartition
  FROM cc_sr_grille_application
  where id_projet = ".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application.";";
  
        $sql_2 = "INSERT INTO cc_sr_grille_classement(
            id_projet, id_client, id_application, id_classement, 
            ponderation_classement, ponderation_section)
    select ".$new_projet." as id_projet,".$new_client." as id_client,".$new_application."  as id_application,id_classement,ponderation_classement, ponderation_section 
from cc_sr_grille_classement where id_projet =".$id_projet." and id_client = ".$id_client." and id_application = ".$id_application."";
       
	       $query_1 = pg_query($conn,$sql_1) or die (pg_last_error());
	       $query_2 = pg_query($conn,$sql_2) or die (pg_last_error());
	       
	if(($query_1 && $query_2) || ($query_0 && $query_1 && $query_2 )){
	$sql_update = "UPDATE cc_sr_projet set flag_duplication =".$id_projet." WHERE id_projet=".$new_projet." ";
	
	    $sql = "SELECT id_grille_application FROM cc_sr_grille_application WHERE id_projet = ".$new_projet."  AND id_client=".$new_client." AND id_application=".$new_application." ";
		          $query = pg_query($conn,$sql) or die(pg_last_error());
				  $nb_row = pg_num_rows( $query  );
				  $lg = pg_fetch_array($query);
				  
				   $sql_3 = "INSERT INTO cc_sr_grille_description(
                id_grille_application, note, libelle_description)
					select req2.id_grille_application,req4.note,req4.libelle_description from
(
	select id_grille_application as id_grille_application_old, id_grille from cc_sr_grille_application where id_projet =".$id_projet."
) as req1
inner join 
(
	select id_grille_application,id_grille from cc_sr_grille_application where id_projet = ".$new_projet."
) as req2
on req1.id_grille = req2.id_grille 
inner join 
(
	select * from cc_sr_grille_description where id_grille_application in 
	(
		select id_grille_application from cc_sr_grille_application where id_projet = ".$id_projet."
	)
) as req4
on req1.id_grille_application_old = req4.id_grille_application
order by req4.id_grille_application";
	$query_3 = pg_query($conn,$sql_3) or die (pg_last_error());
    $query_update = pg_query($conn,$sql_update) or die (pg_last_error());
	               
			         echo   1 ;
			   }else{
			         echo  0;
			   }
	 }
	 
function get_projet_par_application($new_client,$new_application){
	global $conn;
	$sql = "SELECT id_projet FROM cc_sr_projet WHERE id_client ={$new_client} AND id_application={$new_application} ";
	$query =  pg_query( $conn,$sql ) or die (pg_last_error());
	$rws = pg_fetch_row( $query );
	return $rws[0];
}

function getNomPrenomEvaluateur($matricule_eval)
{
	global $conn;
	$sql = "select * from personnel where matricule = ".$matricule_eval;
	$query =  pg_query( $conn,$sql ) or die (pg_last_error());
	$result = pg_fetch_array($query);
	return $result;
}

function get_indicateur_nf(){
    global $conn;
	$array_inf = array();
	  $sql = "SELECT * FROM cc_sr_indicateur_nf ORDER BY libelle_inf ASC" ;
	 /* $sql = "SELECT id_inf,libelle_inf as i,  case when (libelle_inf = 'is5') then '' else 
			case when (libelle_inf = 'is5_v7')  then 'is5' else libelle_inf  end end as libelle_inf, 
			objectif_nf 
		FROM cc_sr_indicateur_nf ORDER BY i ASC" ;*/
	  
      $query = pg_query( $conn , $sql ) or die (pg_last_error( $conn ));
	 for($k=0;$k<pg_num_rows( $query  );$k++){
	    $rws = pg_fetch_array(  $query , $k  );
		$array_inf[$rws['id_inf']] = $rws['libelle_inf'];
	 }
	 return $array_inf;
}


function get_indicateur_nf_objectif(){
    global $conn;
	$array_obj = array();
	$sql = " SELECT * FROM cc_sr_indicateur_nf 
			where libelle_inf != 'is5'
		ORDER BY libelle_inf ASC " ;
	$query = pg_query( $conn , $sql ) or die (pg_last_error( $conn ));
	for($k=0;$k<pg_num_rows( $query  );$k++){
	    $rws = pg_fetch_array(  $query , $k  );
		$array_obj[$rws['id_inf']]['objectif'] = $rws['objectif_nf'];
		$array_obj[$rws['id_inf']]['libelle']  = $rws['libelle_inf'];
	}
	return $array_obj;
}

function get_indicateur_nf_objectif_is5(){
	global $conn;
	$array_obj = array();
	$sql = " SELECT * FROM cc_sr_indicateur_nf 
		ORDER BY libelle_inf ASC " ;
	$query = pg_query( $conn , $sql ) or die (pg_last_error( $conn ));
	for($k=0;$k<pg_num_rows( $query  );$k++){
	    $rws = pg_fetch_array(  $query , $k  );
		$array_obj[$rws['id_inf']]['objectif'] = $rws['objectif_nf'];
		$array_obj[$rws['id_inf']]['libelle']  = $rws['libelle_inf'];
	}
	return $array_obj;
}

function test_nc_fiche( $id_grille_application,$id_notation ){

 global $conn;

	  $sql = "SELECT fnc_id,fnc_id_grille_application,fnc_id_notation FROM nc_fiche WHERE fnc_id_grille_application = {$id_grille_application} AND fnc_id_notation={$id_notation}" ;
      $query = pg_query( $conn , $sql ) or die (pg_last_error( $conn ));
	  $rws = pg_fetch_row( $query );
	
	      return $rws[0].'#'.$rws[1].'#'.$rws[2];

}

function get_libelle_categorie( $id_categorie )
{  
    global $conn;
	$sql = "SELECT libelle_categorie_grille FROM cc_sr_categorie_grille	 WHERE id_categorie_grille='{$id_categorie}'";
	$query = pg_query($conn,$sql) or die(pg_last_error());
	$rws = pg_fetch_row( $query );
	return $rws[0];

}

function get_nom_fichierById($id_fichier)
{
    global $conn;
	$sql = "SELECT nom_fichier FROM cc_sr_fichier WHERE id_fichier='{$id_fichier}'";
	$query = pg_query($conn,$sql) or die(pg_last_error());
	$rws = pg_fetch_row( $query );
	return $rws[0];

}

function getFNCByNotationGrilleApp($id_grille_application,$id_notation)
{
	global $conn;
	$sql = "select fnc_motif,fnc_exigence,fnc_gravite_id,fnc_frequence_id from nc_fiche where fnc_id_grille_application = ".$id_grille_application." and fnc_id_notation = ".$id_notation;
	$query = pg_query($conn,$sql) or die(pg_last_error());
	$rws = pg_fetch_row( $query );
	//$description = explode('#*#',$rws[0]);
	//$rws[0] = $description[0];
	return $rws;
}

function getFNCGlobalByNotation($id_notation)
{
	global $conn;
	$sql = "select fnc_motif,fnc_exigence from nc_fiche where fnc_id_grille_application = 0 and fnc_id_notation = ".$id_notation;
	$query = pg_query($conn,$sql) or die(pg_last_error());
	$rws = pg_fetch_row( $query );
	//$description = explode('#*#',$rws[0]);
	//$rws[0] = $description[0];
	return $rws;
}

include('conn_mssqlserver.php');
if(isset($_REQUEST['easycode']))
{
	$easycode = $_REQUEST['easycode'];
	$id_projet = $_REQUEST['id_projet_call'];
	$id_type_traitement = $_REQUEST['id_type_traitement_call'];
	
	$ct_table = getTableCall($id_projet,$id_type_traitement);
	$nb_easy = verifEasyCode($easycode,$ct_table);
	
	echo getCampaignByProjet($id_projet,$id_type_traitement,$nb_easy);
}

function getTableCall($id_projet,$id_type_traitement)
{
	global $conn;
	$sql = "select ct_table from cc_sr_campaign where id_projet = ".$id_projet." and flag_type = ".$id_type_traitement;
	$result = pg_query($conn,$sql) or die(pg_last_error());
	$nb_lig = pg_num_rows($result);
	$table = array();
	while($res = pg_fetch_array($result))
	{
		$table[] = 'ct_'.$res['ct_table'];
	}
	return $table;
}
function verifEasyCode($easycode,$ct_table)
{
	global $link;
	$nb = 0;
	for($i=0;$i<count($ct_table);$i++)
	{
		$sql = "SELECT * from ".$ct_table[$i]." WHERE easycode LIKE '".$easycode."'" ;
		$result = mssql_query($sql ,$link) or die('Erreur');
		$nb = mssql_num_rows($result);
		if($nb > 0)
		{
			break;
		}
	}
	return $nb;
}
function getCampaignByProjet($id_projet,$id_type_traitement,$nb_easy)
{
	global $conn;
	$sql = "select * from cc_sr_campaign where id_projet = ".$id_projet." and flag_type = ".$id_type_traitement;
	$result = pg_query($conn,$sql) or die(pg_last_error());
	$table = array();
	$table['verif'] = 0;
	while($res = pg_fetch_array($result))
	{
		$table['login'] = $res['login'];
		$table['mdp'] = $res['password'];
	}
	if($nb_easy > 0)
	{
		$table['verif'] = 1;
	}
	return $table['login'].'#|#|#'.$table['mdp'].'#|#|#'.$table['verif'];
}

function getTypologieByProjet($id_projet)
{
	global $conn;
	$sql = "select * from cc_sr_typologie where id_projet = ".$id_projet;
	$result = pg_query($conn,$sql) or die(pg_last_error());
	return $result;
}

function getGravite()
{
	global $conn;
	$sql = "SELECT id_categorie_grav,echelle_id_grav,libelle_gravite FROM nc_gravite_categorie ORDER BY id_categorie_grav";
	$result = pg_query($conn,$sql) or die(pg_last_error());
	return $result;
}

function getFrequence()
{
	global $conn;
	$sql = "SELECT id_categorie_freq,echelle_id_freq,libelle_frequence FROM nc_frequence_categorie ORDER BY id_categorie_freq";
	$result = pg_query($conn,$sql) or die(pg_last_error());
	return $result;
}

function getByIdTypeAppel($id)
{
   global $conn;
   // id_notation 
   $sql = "select libelle_typologie from cc_sr_typologie where id_typologie = (select id_typologie from cc_sr_notation where id_notation =".$id.")";
   $query  = pg_query($sql) or die(pg_last_error());
   $result = pg_fetch_array($query);
   return $result['libelle_typologie'];

}

function getInfoDossier($id_notation)
{
   global $conn;
   $sql = " SELECT distinct numero_dossier,numero_commande  FROM cc_sr_notation where id_notation = ".$id_notation;
   $result = pg_query($conn,$sql) or die(pg_last_error());
   return $result;
}
?>