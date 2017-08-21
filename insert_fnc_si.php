

<?php
    session_start();
	include('function_dynamique.php');
	
	
	       if(isset($_REQUEST['test_update'])){
		              $str_nc = $_REQUEST['str_nc'];
		              $notation_id = $_REQUEST['notation_id'];
					  
					$sql_update = "UPDATE nc_fiche SET fnc_id_notation={$notation_id}  WHERE fnc_id IN({$str_nc}) ";
					$query_update = pg_query( $conn , $sql_update ) or die(pg_last_error());
		   
		   }else{
	   
    $zClientName = $_REQUEST['type'];
    $id_prestation = $_REQUEST['id_prestation'];
    $type_traitement = $_REQUEST['type_traitement'];
    $id_tlc = $_REQUEST['id_tlc'];
    $id_fichier = $_REQUEST['id_fichier'];
    $date_traitement = $_REQUEST['date_traitement'];
    $date_evaluation = $_REQUEST['date_evaluation'];
    $description_ecart = $_REQUEST['description_ecart'];
    $zExigence = $_REQUEST['exigence_client'];
    $categorie_si = $_REQUEST['categorie_si'];
	$id_grille_application =  $_REQUEST['id_grille_application'];
	$notation_id =  $_REQUEST['notation_id'];
	
	$zRef = "NC_".strtoupper($id_prestation)."_".date("ymd");
	$zCreationDate = date("Y-m-d");
	$zCreationHour = date("H:i:s");
	$zType = "interne";
	$zMotif = $description_ecart.'  '. $id_tlc.'_'.$id_fichier.'_'.$date_traitement.'_'.$date_evaluation.'_'. $categorie_si;	
	$zTraitStatut =  "en attente" ;	
	$iCreateur = $_SESSION["matricule"] ;
	$zValide = "false" ;
	$iVersion =  "1";
	$zACStatut = "";
	$zANCStatut = "";
	
	
	
	    $zSqlInsertFNC = "INSERT INTO nc_fiche (";
		if (!empty ($id_prestation)) $zSqlInsertFNC .= "fnc_code, ";
		$zSqlInsertFNC .= "fnc_ref, ";
		$zSqlInsertFNC .= "fnc_comm, \"fnc_creationDate\", fnc_type, fnc_motif, fnc_exigence, fnc_statut, fnc_createur, fnc_valide, fnc_client, fnc_version, \"fnc_creationHour\", \"fnc_actionCStatut\", \"fnc_actionNCStatut\", fnc_autre_cplmnt,fnc_id_grille_application,fnc_id_notation) VALUES (";
		if (!empty($id_prestation)) $zSqlInsertFNC .= "'{$id_prestation}', ";
		$zSqlInsertFNC .= "'{$zRef}', ";
		$zSqlInsertFNC .= "'{$zComm}', '{$zCreationDate}', '{$zType}', '{$zMotif}', '{$zExigence}', '{$zTraitStatut}',  '{$iCreateur}', '{$zValide}', '{$zClientName}', '{$iVersion}', '{$zCreationHour}', '{$zACStatut}', '{$zANCStatut}','{$autre}','{$id_grille_application}','{$notation_id}') returning fnc_id" ;
      
		$oInsertFNC = @pg_query ($conn, $zSqlInsertFNC) ;
		$fnc_id = pg_fetch_result($oInsertFNC, 0, 'fnc_id');
		if( $oInsertFNC )
		{
		   echo '1#'.$fnc_id;			   
		}
		else
		{
		   echo 0;	
		}
			   
	}

?>

