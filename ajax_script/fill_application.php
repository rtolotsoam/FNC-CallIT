<?php
	session_start();
	require_once "/var/www.cache/dgconn.inc" ;
	$tClient 	= $_REQUEST['client_id'];
	$zClient	= "" ;
	if ( isset( $tClient )  )
	{
		for ( $i = 0 ; $i < count( $tClient ) ; $i++ )
		{
			if ( $i == count( $tClient ) - 1  )
				$zClient .= $tClient[$i] ;
			else
				$zClient .= $tClient[$i] .",";
		}
	}
	
	$sSqlApplication = "SELECT	nom_application, code, id_application
						FROM	gu_application
						WHERE	id_application IS NOT NULL ";
	if ( $zClient != "" ) $sSqlApplication .= " AND id_client IN  ( {$zClient} ) ";
	$sSqlApplication .= " ORDER BY code ASC;";
	$query = @pg_query($conn, $sSqlApplication) or die(@pg_last_error($conn));
	$row = @pg_num_rows($query);
	$zresult = "<option value='' selected>-- choix --</option>";
	for ($i = 0; $i < $row; $i ++) {
		
		$lg = @pg_fetch_array($query);
		$sAppName = $lg['nom_application'];
		$sCode = $lg['code'];
		$id = $lg['id_application'];
		$zresult .= "<option value='$id'>$sCode - $sAppName</option>";
	}
	echo $zresult ;
?>