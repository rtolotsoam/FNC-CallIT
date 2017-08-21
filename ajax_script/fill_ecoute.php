<?php
	session_start();
	require_once('/var/www.cache/dgconn.inc');
	include ("helper.php");
	$tFctAuthoriseInit  = array('AQI','DQ','DCT','DCC','RP','AQI','RESP PLATEAU');
	$lamatricule = $_SESSION['matricule'];
	$fct		 = $_SESSION['zFonction'];
	$zDateDeb	 = $_REQUEST['datedeb'];
	$zDateFin	 = $_REQUEST['datefin'];
    $zDateRest  = $_REQUEST['daterest'];
	
	$tTelecon	= $_REQUEST['teleconseiller'];
	$tAuditeur	= $_REQUEST['auditeur'];
	$tProjet	= $_REQUEST['projet'];
	$tClient	= $_REQUEST['client'];
	$tApplication= $_REQUEST['application'];
	
	$iTypettt	= $_REQUEST['typetraitement'];
	
	$zTelecon	= "" ;
	if ( isset( $tTelecon )  )
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
	
	
	
	
	$zSqlListe = "
		SELECT distinct n.date_notation, n.matricule_notation, p.prenompersonnel, n.date_restitution, p2.prenompersonnel as auditeur, p2.fonctioncourante, f.nom_fichier, f.id_fichier, p.matricule ,pr.nom_projet FROM cc_sr_notation n
			INNER JOIN cc_sr_indicateur_notation csin on csin.id_notation = n.id_notation
			INNER JOIN cc_sr_grille g on g.id_grille = csin.id_grille
			INNER JOIN cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
			INNER JOIN personnel p on p.matricule = n.matricule
			INNER JOIN personnel p2 on p2.matricule = n.matricule_notation
			INNER JOIN cc_sr_fichier f on f.id_fichier = n.id_fichier
			INNER JOIN cc_sr_projet pr on pr.id_projet = n.id_projet
			INNER JOIN gu_client cli on cli.id_client = pr.id_client
		WHERE n.id_notation IS NOT NULL
	";
		
	if (  $zDateDeb != "" && $zDateFin != ""  ) 
		$zSqlListe .= " AND date_notation between '$zDateDeb' AND '$zDateFin' " ;
		
	if ( $zTelecon != "" )
	{
		$zSqlListe .= " AND p.matricule in ($zTelecon) " ;
	}
	if($zDateRest != "")
    {
    $zSqlListe .= " AND n.date_restitution = '$zDateRest' " ;
    }
	if ( $fct == 'CONSEILLER' || $fct == 'FONC_MAIL' || $fct == 'OL' || $fct=='TC')
			$zSqlListe .= " AND p.matricule = {$_SESSION['matricule']} ";
		
	if ( $zAuditeur )
		$zSqlListe .= " AND p2.matricule in ($zAuditeur) " ;
	
	if ( $fct == 'MANAGER' || $fct == 'SUP' )
		$zSqlListe .= " AND p2.matricule = {$_SESSION['matricule']} " ;
		
	if ( $zProjet )
		$zSqlListe .= " AND pr.id_projet in ($zProjet) " ;
	
	if ( $zClient )
		$zSqlListe .= " AND cli.id_client in ($zClient) " ;
		
	if ( $zApplication )
		$zSqlListe .= " AND app.id_application in ($zApplication) " ;
		
	if ( $iTypettt != "" )
		$zSqlListe .= " AND cg.id_type_traitement = $iTypettt " ;
	
	
		
	$zSqlListe .= " ORDER BY p.matricule ASC " ;
	//echo $zSqlListe;
	$zSqlListe0 = "
		SELECT 
			*
		FROM 
			cc_sr_notation
	";
	$query_liste0= @pg_query($conn, $zSqlListe0) or die (@pg_last_error($conn)) ;
	$query_liste = @pg_query($conn, $zSqlListe) or die (@pg_last_error($conn)) ;
    $nbRow_find = @pg_num_rows($query_liste);
	
	$iTotal		= @pg_num_rows($query_liste0);
	
		$zresult = "
			<table id='tbl_data0' class='tablesorter' width='100%' align='center' cellspacing='1' collspan='0' rowspan='0'>
				<tr>
					<th colspan='3'>&nbsp;&nbsp;Nombre d'&eacute;coutes touv&eacute;es / Nombre total :</th>
					<th align='right'><span style='font-weight: bold; color:red;font-size:14px;'>{$nbRow_find } / {$iTotal}</span>&nbsp;&nbsp;</th>
				</tr>
			</table>
		";
		$zresult .= "
		<table id='tbl_data' class='tablesorter' width='100%' align='center' cellspacing='1' collspan='0' rowspan='0'>
		<thead>";
		$zresult .= "  <tr>";
        $zresult .= "    <th filter='false'>Projet</th>";
		$zresult .= "    <th filter='false'>TLC/Op&eacuterateur</th>";
		$zresult .= "    <th filter='false'>Fichier</th>";
		$zresult .= "    <th filter='false'>Date &eacute;coute</th>";
		$zresult .= "    <th filter='false'>Date restitution</th>";
		$zresult .= "    <th filter='false'>Auditeur</th>";
		$zresult .= "    <th filter='false'>Nb &eacute;coutes</th>";
		$zresult .= "    <th filter='false'>Actions</th>";
		$zresult .= "  </tr>";
		$zresult .= "  </thead><tbody>";
		
		for ( $i = 0; $i < $nbRow_find ; $i++ )
		{
			$lg_liste	 = @pg_fetch_array( $query_liste );
			$nb_ecoute	 = get_nb_ecoute( $lg_liste['id_fichier'] );
			$znomfichier = addslashes ($lg_liste['nom_fichier']);
			$zresult 	.= "
				<tr>
                    <td>{$lg_liste['nom_projet']}</td>
					<td>{$lg_liste['prenompersonnel']} - {$lg_liste['matricule']}</td>				
					<td width='20%'><p style='width:250px;overflow:hidden;'>{$lg_liste['nom_fichier']}</p></td>
					<td align='center'><p  style='width:90px;'>{$lg_liste['date_notation']}</p></td>
					<td>{$lg_liste['date_restitution']}</td>
					<td>{$lg_liste['auditeur']} - {$lg_liste['matricule_notation']} - {$lg_liste['fonctioncourante']}</td>
					<td>{$nb_ecoute}</td>
					<td nowrap><a href='#' title='visualiser' onclick='visu_note({$lg_liste['id_fichier']}, {$lg_liste['matricule_notation']})' ><img width='25px' src='images/visualiser.png' alt='visualiser' /></a>";
					if ( $fct != 'FONC_MAIL' &&  $fct != 'CONSEILLER' &&  $fct != 'OL' && $fct != 'TC' )
					{ if($lamatricule == $lg_liste['matricule_notation'] ||in_array($fct,$tFctAuthoriseInit)){
						$zresult	.= "&nbsp;&nbsp;<a href='#' title='editer'  onclick='update_note({$lg_liste['id_fichier']}, {$lg_liste['matricule_notation']})'><img width='25px' src='images/modifier.png' alt='editer' /></a>&nbsp;&nbsp;";
                        }
					}
					if ( $fct != 'FONC_MAIL' &&  $fct != 'CONSEILLER'  &&  $fct != 'MANAGER' &&  $fct != 'OL' && $fct != 'TC' && $fct != 'SUP' && $fct != 'SUP CC' && $fct != 'SUP_CC' )
					{
                     
                        $zresult	.= "<a href='#' title='supprimer' onclick='do_delete({$lg_liste['id_fichier']}, {$lg_liste['matricule_notation']})' ><img width='25px' src='images/supprimer.png' alt='supprimer'  /></a>";
					
                    }
			$zresult .= "</td>
				</tr>
			";
		}
	//<input type='button' value='Modifier' onclick='update_note(\"{$lg_liste['nom_fichier']}\")' /><input type='button' value='Visualiser' onclick='visu_note({$lg_liste['id_fichier']})' /><input type='button' value='Supprimer'  />
	$zresult .= "
			</tbody>
		</table>
		<div id='pager'>
		
		<form>
			<table>
			<tr>
			<td><img src='./images/first.png' class='first'></td>
			<td><img src='./images/prev.png' class='prev'></td>
			<td><input class='pagedisplay' type='text' readonly style='border:none; background-color: #FFF; text-align:center;font-weight: bold; width: 50px;'></td>
			<td><img src='./images/next.png' class='next'></td>
			<td><img src='./images/last.png' class='last'></td>
			<td><select class='pagesize'>
				<option selected='selected' value='10'>10</option>
				<option value='20'>20</option>
				<option value='30'>30</option>
				<option value='40'>40</option>
			</select></td>
			</tr>
			</table>
		</form>

		</div>
		
	";
	echo $zresult;
?>