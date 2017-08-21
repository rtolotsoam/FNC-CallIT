<?php 
	session_start();
	
	include("/var/www.cache/dgconn.inc");
	$iIdFichier	= $_REQUEST['id_fichier'];
	$iAuditeur = $_REQUEST['id_auditeur'];
	function get_nb_situation_innaceptable ( $_id_fichier )
	{
		global $conn;
		
		$query = @pg_query($conn,"
			SELECT distinct n.id_notation FROM cc_sr_notation n
				INNER JOIN cc_sr_indicateur_notation csin on csin.id_notation = n.id_notation
				INNER JOIN cc_sr_grille g on g.id_grille = csin.id_grille
				INNER JOIN cc_sr_indicateur i on i.id_indicateur = g.id_grille
				INNER JOIN cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
				INNER JOIN personnel p on p.matricule = n.matricule
				INNER JOIN cc_sr_fichier f on f.id_fichier = n.id_fichier
			WHERE n.id_fichier={$_id_fichier} AND i.point = 0
		") or die (@pg_last_error($conn));
		return @pg_num_rows($query);
	}
	
	function get_num_rows($_id_categorie_grille, $_id_fichier)
	{
		global $conn;
	
		$query = @pg_query($conn,"
			SELECT n.id_notation FROM cc_sr_notation n
				INNER JOIN cc_sr_indicateur_notation csin on csin.id_notation = n.id_notation
				INNER JOIN cc_sr_grille g on g.id_grille = csin.id_grille
				INNER JOIN cc_sr_indicateur i on i.id_indicateur = g.id_grille
				INNER JOIN cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
				INNER JOIN personnel p on p.matricule = n.matricule
				INNER JOIN cc_sr_fichier f on f.id_fichier = n.id_fichier
			WHERE n.id_fichier='{$_id_fichier}' AND cg.id_categorie_grille='{$_id_categorie_grille}'
		") or die (@pg_last_error($conn));
		return @pg_num_rows($query);
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Automatisation DQ</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="./css/tablesorter.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.core.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.theme.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.datepicker.css" type="text/css" media="screen" />

<script type ="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="./js/jquery.tablesorter.js"></script>
<script type ="text/javascript" src="./js/ui.datepicker.js"></script>

</head>

<body>
<div id="main">
  <div id="contentbg">
    <div id="contenttxtblank" style='background: none;'>
      <div id="contentleft">
        <div id="topbuttonsblank">
          
        </div>
        
      </div>
      <div id="contentright">
	  
    </div>
  </div>
  <!-- Content -->
	
  <div class='acc_container' style='border: none;'>
	<div class='block'>
		<?php
		$tclassement = array();
		$iClassement = 0 ;
		$zhtml_note = "" ;
		
		$iNbSituationInacceptable = get_nb_situation_innaceptable( $iIdFichier );
		if ( $iNbSituationInacceptable == 0 )
			$iRetraitPt =  0 ;
		else if ( $iNbSituationInacceptable == 1 )
			$iRetraitPt =  0 - 30 ;
		else
			$iRetraitPt =  0 - 50 ;
		
		$query_note = @pg_query($conn,"
			SELECT n.*, p.pseudopers, f.nom_fichier, f.id_fichier, f.chemin_fichier, p.matricule, i.libelle_indicateur, g.libelle_grille, cg.id_categorie_grille,  cg.libelle_categorie_grille, tt.libelle_type_traitement FROM cc_sr_notation n
				INNER JOIN cc_sr_indicateur_notation csin on csin.id_notation = n.id_notation
				INNER JOIN cc_sr_grille g on g.id_grille = csin.id_grille
				INNER JOIN cc_sr_indicateur i on i.id_indicateur = g.id_grille
				INNER JOIN cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
				INNER JOIN cc_sr_type_traitement tt on tt.id_type_traitement = cg.id_type_traitement
				INNER JOIN personnel p on p.matricule = n.matricule
				INNER JOIN cc_sr_fichier f on f.id_fichier = n.id_fichier
			WHERE f.id_fichier = {$iIdFichier}  ORDER BY i.ordre ASC
		") or die (@pg_last_error($conn));
		$lg_note0	= @pg_fetch_array( $query_note, 0 );
			$chemin_fichier = $lg_note0['chemin_fichier'];
			$tChemin_fichier= explode("/", $chemin_fichier);
			$tChemin_fichier_sansrec = substr($tChemin_fichier[1],0,strlen($tChemin_fichier[1])-5);
			
			$zhtml_note .= "
				<h2></h2>
				<div class='block_notation_visu'>
				<center>
				<table>
					<tr>
						<th align='right'>Projet:</th>
						<td>$tChemin_fichier_sansrec</td>
					</tr>
					<tr>
						<th align='right'>Type de traitement:</th>
						<td>{$lg_note0['libelle_type_traitement']}</td>
					</tr>
					<tr>
						<th align='right'>Mle :</th>
						<td>{$lg_note0['matricule']}</td>
					</tr>
					<tr>
						<th align='right'>Pr&eacute;nom :</th>
						<td>{$lg_note0['pseudopers']}</td>
					</tr>
					<tr>
						<th align='right'>Date de l'entretien t&eacute;l&eacute;phonique :</th>
						<td>{$lg_note0['date_entretien']}</td>
					</tr>
					<tr>
						<th align='right'>Dur&eacute;e de l'entretien t&eacute;l&eacute;phonique :</th>
						<td>{$lg_note0['duree_entretien']}</td>
					</tr>
                    <tr>
						<th align='right'>Heure de l'entretien t&eacute;l&eacute;phonique :</th>
						<td>{$lg_note0['debut_entretien']}</td>
					</tr>
					<tr>
						<th align='right'>Date de la restitution :</th>
						<td>{$lg_note0['date_restitution']}</td>
					</tr>
					<tr>
						<th align='right'>lien :</th>
						<td>{$lg_note0['nom_fichier']}</td>
					</tr>
					<tr>
						<th align='right'>&nbsp;</th>
						<td>&nbsp;</td>
					</tr>
				</table>
				</center>
				</div>
				
				
				
			" ;
			
			$query_cat	=	@pg_query( $conn,"
				SELECT distinct cg.id_categorie_grille, cg.libelle_categorie_grille, cg.classement, cg.ordre FROM cc_sr_notation n
					INNER JOIN cc_sr_indicateur_notation csin on csin.id_notation = n.id_notation
					INNER JOIN cc_sr_grille g on g.id_grille = csin.id_grille
					INNER JOIN cc_sr_indicateur i on i.id_indicateur = g.id_grille
					INNER JOIN cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
					INNER JOIN cc_sr_type_traitement tt on tt.id_type_traitement = cg.id_type_traitement
					INNER JOIN personnel p on p.matricule = n.matricule
					INNER JOIN cc_sr_fichier f on f.id_fichier = n.id_fichier
				WHERE f.id_fichier = {$iIdFichier} and n.matricule_notation= $iAuditeur ORDER BY cg.ordre ASC
			" );
			
			for ( $iCat = 0 ; $iCat < @pg_num_rows( $query_cat ) ; $iCat++ )
			{
				$tcat = array(19,20,21);
				$lg_cat		 = @pg_fetch_array( $query_cat, $iCat ) ;
				$lg_cat1	 = @pg_fetch_array( $query_cat, $iCat + 1 ) ;
				$lib_categorie = in_array( $lg_cat['id_categorie_grille'], $tcat ) ? "" : $lg_cat['libelle_categorie_grille'] ;
				if ( in_array( $lg_cat['classement'], $tclassement ))
				{
					$zhtml_classement = "";
				}else{
					$zhtml_classement = "
						<table style='margin-top: 20px;' class='tablesorter'>
						<thead>
						<tr>
							<th colspan='2'>&nbsp;&nbsp;{$lg_cat['classement']}</th>
						</tr>
						</thead>
						</table>
						<table  class='tablesorter' style='margin-top: -20px; margin-bottom: -10px;'>
						<thead>
							<tr>
								<th width='200px'>Cat&eacute;gorie</th>
								<th width='180px'>Grille</th>
								<!--<th width='120px'>Indicateur</th>-->
								<th width='30px'> Point</th>
								<th width='200px'> Commentaire</th>
							</tr>
						</thead>
						</table>
					";
					$tclassement[$iClassement] = $lg_cat['classement'] ;
					$iClassement++ ;
				}
				$iTotalPt = 0 ;
				$class_odd = $iCat % 2 == 0 ? "odd" : "" ;
				$zhtml_note .= "
					$zhtml_classement
				<table class='tablesorter'>
					<tbody>
					<tr class='$class_odd'>
						<td width='242px'>&nbsp;&nbsp;<b>{$lib_categorie}</b></td>
						<td>
							<table>
						";
							$query_grille = @pg_query($conn,"
							SELECT distinct g.libelle_grille, g.id_grille, csin.note as point,  csin.commentaire, g.ordre FROM cc_sr_notation n
								INNER JOIN cc_sr_indicateur_notation csin on csin.id_notation = n.id_notation
								INNER JOIN cc_sr_grille g on g.id_grille = csin.id_grille
								INNER JOIN cc_sr_indicateur i on i.id_grille = g.id_grille
								INNER JOIN cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
								INNER JOIN cc_sr_type_traitement tt on tt.id_type_traitement = cg.id_type_traitement
								INNER JOIN personnel p on p.matricule = n.matricule
								INNER JOIN cc_sr_fichier f on f.id_fichier = n.id_fichier
							WHERE f.id_fichier = {$iIdFichier} and n.matricule_notation = $iAuditeur AND cg.id_categorie_grille={$lg_cat['id_categorie_grille']} ORDER BY g.ordre ASC
							") or die ( @pg_last_error($conn) );
							
							for ( $iGrille = 0; $iGrille < @pg_num_rows ( $query_grille ) ; $iGrille++ )
							{
								$lg_grille	= @pg_fetch_array( $query_grille , $iGrille);
								$com = ($lg_grille['commentaire']);
								$iTotalPt += $lg_grille['point'] ;
								$zhtml_note .= "
									<tr>
										<td width='208px'>{$lg_grille['libelle_grille']}</td>
										<!--<td width='120px'>{$lg_grille['libelle_indicateur']}</td>-->
										<td align='center' width='38px'>{$lg_grille['point']}</td>
										<td align='center' width='200px'>{$com}</td>
									</tr>
								";
							}
				$zhtml_note .= "
				</table></td>
								</tr>
								<tr class='$class_odd'>
									<td>Total</td>
									<td align='right'>
										<table>
											<tr>
												<td align='center'><b>{$iTotalPt}</b></td>
												<td style='width: 220px;'>&nbsp;&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>
				";
				$iTotal_Gen += $iTotalPt ;
				$iTotalPt = 0 ;
			}
		$iTotalApresRetrait = $iTotal_Gen + $iRetraitPt ;
		$zAppreciation = $iTotalApresRetrait >= 50 ? "Correct" : "Insuffisant" ;
		$zhtml_note .= "
			</tbody>
				<tr>
					<td style='background-color: #ff3237 ;color: #FFF;'><b>TOTAL avant prise en compte des p&eacute;nalit&eacute;s</b></td>
					<td align='right' style='background-color: #ff3237 ;color: #FFF;'>
						<table>
							<tr>
								<th align='center'>{$iTotal_Gen}</th>
								<th width='240px;'>&nbsp;</th>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><b>Prise en compte des p&eacute;nalit&eacute;s</b></td>
					<td>
						<table>
							<tr>
								<td>Nombre de situations inacceptable rencontrées</td>
								<td>Retrais de points appliquées</td>
							</tr>
							<tr>
								<td align='right'>{$iNbSituationInacceptable}</td>
								<td align='right'>{$iRetraitPt}</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style='background-color: #ff3237 ; color: #FFF;'><b>TOTAL avant prise en compte des p&eacute;nalit&eacute;s</b></td>
					<td align='right' style='background-color: #ff3237 ; color: #FFF;'>
						<table>
							<tr>
								<td style='background-color: #ff3237 ; color: #FFF;'><b>{$iTotalApresRetrait}</b></td>
								<td style='width: 240px;background-color: #ff3237 ; color: #FFF;'><b>{$zAppreciation}</b></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		";
		echo $zhtml_note ;
		
	  ?>
        
	</div>
  </div>

</body>
</html>
