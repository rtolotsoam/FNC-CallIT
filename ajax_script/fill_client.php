<?php
	session_start();
	require_once "/var/www.cache/dgconn.inc" ;
	$tProjet 	= $_REQUEST['projet_id'];
	$zProjet	= "" ;
	if ( isset( $tProjet )  )
	{
		for ( $i = 0 ; $i < count( $tProjet ) ; $i++ )
		{
			if ( $i == count( $tProjet ) - 1  )
				$zProjet .= $tProjet[$i] ;
			else
				$zProjet .= $tProjet[$i] .",";
		}
	}
	
	$sSqlApplication = "SELECT	c.id_client, c.nom_client
						FROM	gu_client c inner join cc_sr_projet p
						ON 		p.id_client = c.id_client
						WHERE	p.id_client IS NOT NULL ";
	if ( $zProjet != "" ) $sSqlApplication .= " AND p.id_projet IN  ( {$zProjet} ) ";
	$sSqlApplication .= " ORDER BY c.nom_client ASC;";
	$query = @pg_query($conn, $sSqlApplication) or die(@pg_last_error($conn));
	$row = @pg_num_rows($query);
	$zresult = "<option value='' selected>-- choix --</option>";
	for ($i = 0; $i < $row; $i ++) {
		
		$lg = @pg_fetch_array($query);
		$sAppName = $lg['id_client'];
		$sCode = $lg['nom_client'];
		$zresult .= "<option value='$sAppName'>$sCode</option>";
	}
	echo $zresult ;
?>