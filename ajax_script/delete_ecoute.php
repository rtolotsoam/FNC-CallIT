<?php
	session_start();
	require_once('/var/www.cache/dgconn.inc');
	
	$idFichier	= $_REQUEST['id_fichier'];
	$iMatriculeNotation = $_REQUEST['matricule_notation'];
	
	$query_notation = @pg_query($conn,"
		SELECT id_notation FROM cc_sr_notation WHERE id_fichier = $idFichier and matricule_notation = $iMatriculeNotation
	");
	
	for ( $i = 0 ; $i < @pg_num_rows ( $query_notation ) ; $i++ )
	{
		$lg_notation = @pg_fetch_array( $query_notation );
		
		$query_delete_indicateur_notation = @pg_query($conn,"
			DELETE FROM cc_sr_indicateur_notation WHERE id_notation={$lg_notation['id_notation']}
		");
		
		$query_delete_notation			  = @pg_query($conn,"
			DELETE FROM cc_sr_notation WHERE id_fichier=$idFichier and matricule_notation = $iMatriculeNotation
		");
		
	}
	echo 1 ;
	
?>