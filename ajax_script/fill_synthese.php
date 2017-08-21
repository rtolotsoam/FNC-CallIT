 <?php
	session_start();
	require_once('/var/www.cache/dgconn.inc');
	
	include ("helper.php");
	$lamatricule = $_SESSION['matricule'];
	
	$zDateDeb	= $_REQUEST['datedeb'];
	$zDateFin	= $_REQUEST['datefin'];
	
	$tTelecon	= $_REQUEST['teleconseiller'];
	$tAuditeur	= $_REQUEST['auditeur'];
	$tProjet	= $_REQUEST['projet'];
	$tClient	= $_REQUEST['client'];
	$tApplication= $_REQUEST['application'];
	
	$iTypettt	= $_REQUEST['typetraitement'];
	
	$zTelecon	= "" ;
	if ( isset( $tTelecon ) || count($tTelecon) > 0  )
	{
		for ( $i = 0 ; $i < count( $tTelecon ) ; $i++ )
		{
			if ( $i == count( $tTelecon ) - 1  )
				$zTelecon .= $tTelecon[$i] ;
			else
				$zTelecon .= $tTelecon[$i] .",";
		}
	}
	
	$zAuditeur	= "" ;
	if ( isset( $tAuditeur )  )
	{
		for ( $i = 0 ; $i < count( $tAuditeur ) ; $i++ )
		{
			if ( $i == count( $tAuditeur ) - 1  )
				$zAuditeur .= $tAuditeur[$i] ;
			else
				$zAuditeur .= $tAuditeur[$i] .",";
		}
	}
	
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
	
	$zApplication	= "" ;
	if ( isset( $tApplication )  )
	{
		for ( $i = 0 ; $i < count( $tApplication ) ; $i++ )
		{
			if ( $i == count( $tApplication ) - 1  )
				$zApplication .= $tApplication[$i] ;
			else
				$zApplication .= $tApplication[$i] .",";
		}
	}
		
	//                               FIND THE FUNCTION OF THE CURRENT USER
	// =================================================================================================
	$zresult = "";
	 $sql = "
		SELECT distinct id_type_traitement, libelle_type_traitement FROM cc_sr_type_traitement 
	";
	if ( $iTypettt != '' )
		$sql .= " WHERE id_type_traitement = $iTypettt ";
	$sql .= "
		ORDER BY libelle_type_traitement ASC
	";
	
	$query_traitement = @pg_query($conn, $sql );
	
	for ( $k = 0 ; $k < @pg_num_rows( $query_traitement ) ; $k++ )
	{
		$lg_traitement = @pg_fetch_array( $query_traitement, $k );
		
		$zSqlListe = "
				select distinct libelle_categorie_grille from cc_sr_categorie_grille where valoriser=1 order by libelle_categorie_grille ASC
			";
			
		$query_liste = @pg_query($conn, $zSqlListe) or die (@pg_last_error($conn)) ;
	
		
		$zresult .= "
		<h3>{$lg_traitement['libelle_type_traitement']}</h3>
		<table id='tbl_data' class='tablesorter' width='100%' align='center' cellspacing='1' collspan='0' rowspan='0'>
		<thead>";
		$zresult .= "  <tr>";
		$zresult .= "    <th filter='false'>Crit&egrave;res</th>";
		$zresult .= "    <th filter='false'>Moyenne</th>";
		$zresult .= "    <th filter='false'>Nombre de situations inacceptables</th>";
		$zresult .= "  </tr>";
		$zresult .= "  </thead><tbody>";
		
		$zSqlListe = "
			select distinct libelle_categorie_grille from cc_sr_categorie_grille where valoriser=1 order by libelle_categorie_grille ASC
		";
		
		$query_liste = @pg_query($conn, $zSqlListe) or die (@pg_last_error($conn)) ;
		
		$iTotal		= @pg_num_rows($query_liste);
		
		for ( $i = 0; $i < $iTotal ; $i++ )
		{
			$lg_liste	= @pg_fetch_array( $query_liste );
			$zresult 	.= "
				<tr>
					<td>{$lg_liste['libelle_categorie_grille']}</td>
					<td>".get_moyenne( $zDateDeb, $zDateFin, $zTelecon, $lg_traitement['id_type_traitement'], addslashes($lg_liste['libelle_categorie_grille']), $zAuditeur, $zProjet, $zClient, $zApplication )."/".get_barreme($lg_liste['libelle_categorie_grille'],$lg_traitement['id_type_traitement'])."</td>
					<td>".get_nb_situation_innaceptable( $zDateDeb, $zDateFin, $zTelecon, $lg_traitement['id_type_traitement'], addslashes($lg_liste['libelle_categorie_grille']) ,  $zAuditeur, $zProjet, $zClient, $zApplication )."</td>
				</tr>
			";
		}
		
		$zresult .= "
				</tbody>
			</table>
			<br />
		";
	}
	echo $zresult;
?>