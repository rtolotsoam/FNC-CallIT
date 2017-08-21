<?php 
	 session_start();
	 include("/var/www.cache/dgconn.inc");
	 $fct = $_SESSION['zFonction'];
	 
	 $tFctAuthorise		 = array('RESP RD','CHQ','AQI','DQ','DCT','DCC','RP','RESP PLATEAU','AQI','SUP','SUP CC','SUP_CC','TC','OL','CONSEILLER','FONC_MAIL','MANAGER');
	 $tFctAuthoriseInit  = array('RESP RD','AQI','DQ','DCT','DCC','RP','RESP PLATEAU','AQI','SUP','SUP CC','SUP_CC','MANAGER');
	 
	 if ( $_SESSION['matricule'] == null || $_SESSION['matricule'] == "" || !in_array( $fct, $tFctAuthorise )  )
	 {
		echo "<p style='color:red'><b>Accès non authorisé ou session expiré!</b></p>";
		exit;
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
<script type='text/javascript'>
	$(document).ready(function ()
		{
			$("#txt_date_deb").datepicker();
			$("#txt_date_fin").datepicker();
			
			do_filtre() ;
		}
	);
	
	function fill_application()
	{
		$.post("./ajax_script/fill_application.php",
		{
			client_id : $("#slct_client").val()
		},
		 function(_data){
			$("#slct_application").html( _data );
		 }
		);
	}
	
	function fill_client()
	{
		$.post("./ajax_script/fill_client.php",
		{
			projet_id : $("#slct_projet").val()
		},
		 function(_data){
			$("#slct_client").html( _data );
		 }
		);
	}
	
	function do_filtre()
	{
		if ( ( $("#txt_date_deb").val()=="" && $("#txt_date_fin").val()!=""  ) || ( $("#txt_date_deb").val()!="" && $("#txt_date_fin").val()=="" ) ||  ( $("#txt_date_deb").val() > $("#txt_date_fin").val()  ) )
		{
			alert("Plage de date incorrecte !");
		}else{
			$("#dv_liste").html("<p><img src='images/loadingAnimation.gif' />Chargement</p>");
			$.post("./ajax_script/fill_synthese.php",
			{
				datedeb : $("#txt_date_deb").val(),
				datefin : $("#txt_date_fin").val(),
				teleconseiller : $("#slct_conseiller").val(),
				auditeur : $("#slct_auditeur").val(),
				projet : $("#slct_projet").val(),
				client : $("#slct_client").val(),
				application : $("#slct_application").val(),
				typetraitement : $("#slct_type_traitement").val()
			},
			 function(_data){
				$("#dv_liste").html(_data);
				
				$("#tbl_data").tablesorter ();
			 }
			);
		}
	}
</script>
</head>

<body>
<div id="main">
  <div id="contentbg">
    <div id="contenttxtblank">
      <div id="menu">
		<?php
			$_addr = $_SERVER['REQUEST_URI'];
			$t_addr = explode('/',$_addr);
			$cur_addr = $t_addr[count($t_addr)-1];
		?>
        <ul>
         <?php
		  if ( in_array( $fct, $tFctAuthoriseInit )  )
		  {
		  ?>
		  <li><a href="filtre_dynamique.php" <?php if($cur_addr=='filtre_dynamique.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Notation</a></li>
		  <li><a href="interface.php" <?php if($cur_addr=='interface.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Projet</a></li>
          <li><a href="nb_reecoute.php" <?php if($cur_addr=='nb_reecoute.php') echo 'class="active"'; else echo 'class="menu"';?>>Suivi</a></li>
          <li class="menusap"></li>
         
          <li><a href="index.php" <?php if($cur_addr=='index.php' || $cur_addr=='' ) echo 'class="active"'; else echo 'class="menu"'; ?> >&eacute;coute</a></li>
          <li class="menusap"></li>
		   <!--<li><a href="grille.php" <?php if($cur_addr=='grille.php' || $cur_addr=='' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Notation manuelle</a></li>-->
          <!--<li class="menusap"></li>-->
          <li><a  href="synthese.php" <?php if($cur_addr=='synthese.php') echo 'class="active"'; else echo 'class="menu"';?>>Synth&egrave;ses</a></li>
          <li class="menusap"></li>
          <li><a href="indicateur_nf.php" <?php if($cur_addr=='indicateur_nf.php') echo 'class="active"'; else echo 'class="menu"';?>>Indicateurs</a></li>
          <li class="menusap"></li>
		
          <li class="menusap"></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      <div id="contentleft">
		<!--div id="morelinksheading">
          <h5>Synth&egrave;se</h5>
        </div-->
        
      </div>
      <div id="contentright">
	  
        
    </div>
  </div>
  <!-- Content -->
	
  <div class='acc_container'>
	<div class='block'>
		<fieldset>
			<table class='tbl_filtre'>
				<tbody>
					<tr>
						<th>Date d&eacute;but :</th>
						<td><input type='text' id='txt_date_deb' name='txt_date_deb' value='<?php echo date('Y-m-01'); ?>' /></td>
						<td>&nbsp;</td>
						<th  valign='top' rowspan='3'>TLC/Op&eacuterateur :</th>
						<td  valign='top' rowspan='3'>
							<select multiple name='slct_conseiller' id='slct_conseiller'>
							<?php
								$query_tc	= @pg_query( $conn, "
									SELECT * FROM personnel WHERE (fonctioncourante = 'TC' or fonctioncourante = 'CONSEILLER' or fonctioncourante = 'FONC_MAIL') AND actifpers='Active' order by prenompersonnel ASC
								" ) ;
								$zopttc = "<option value='' selected>-- choix --</option> " ;
								for ( $i = 0; $i < @pg_num_rows( $query_tc ) ; $i++ )
								{                             
									$lg_tc	= @pg_fetch_array( $query_tc ) ;
                                    $zPrenomtlc = ucfirst(strtolower($lg_tc['prenompersonnel']));
									$zopttc .= "<option value='{$lg_tc['matricule']}'>$zPrenomtlc - {$lg_tc['matricule']}</option>" ;
								}
								echo $zopttc;
							?>
							</select>
						</td>
					</tr>
					<tr>
						<th>Date fin :</th>
						<td><input type='text' id='txt_date_fin' name='txt_date_fin' value='<?php echo date('Y-m-d'); ?>' /></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<th valign='top'>Type de traitement :</th>
						<td valign='top'>
							<select name='slct_type_traitement' id='slct_type_traitement' style='height:20px;'>
							<?php
								$zopttypettt = "<option value='' selected>-- choix --</option>" ;
								$query_type_tt	= @pg_query($conn,"
									SELECT * FROM cc_sr_type_traitement ORDER BY libelle_type_traitement ASC
								");
								for ( $i = 0; $i < @pg_num_rows( $query_type_tt ) ; $i++ )
								{
									$lg_type_tt = @pg_fetch_array( $query_type_tt );
									$zopttypettt .= "<option value='{$lg_type_tt['id_type_traitement']}'>{$lg_type_tt['libelle_type_traitement']}</option>" ;
									
								}
								echo $zopttypettt;
							?>
							</select>
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<th valign='top'>Auditeur :</th>
						<td>
						<select name='slct_auditeur' multiple id='slct_auditeur'>
							<?php
								$zoptaudit = "<option value='' selected>-- choix --</option>" ;
								$query_type_tt	= @pg_query($conn,"
									SELECT 
                                            fonctioncourante, prenompersonnel, matricule 
                                        FROM 
                                            personnel 
                                        WHERE 
                                            actifpers='Active'  
                                            and \"pers_fictifMatricule\" <> 1  
                                            and fonctioncourante in ('SUP CC','RP','RESP PLATEAU','AQI','DQ','DCC','DCT','MANAGER') 
                                            and deptcourant in ('CC', 'DQ', 'DCC', 'DCT','AE1','AE2','AE3','AE4','AE5','AS1','AS2','AS3')
                                        ORDER BY 
                                            prenompersonnel ASC
								");
								for ( $i = 0; $i < @pg_num_rows( $query_type_tt ) ; $i++ )
								{                               
									$lg_type_tt = @pg_fetch_array( $query_type_tt );
                                    $zPrenompers = ucfirst(strtolower($lg_type_tt['prenompersonnel']));
									$zoptaudit .= "<option value='{$lg_type_tt['matricule']}'>$zPrenompers - {$lg_type_tt['matricule']} - {$lg_type_tt['fonctioncourante']}</option>" ;
									
								}
								echo $zoptaudit;
							?>
						</select>
						</td>
						<td>&nbsp;</td>
						<th valign='top'>Projet :</th>
						<td>
						<select name='slct_projet' multiple id='slct_projet' onchange='fill_client();'>
							<?php
								$zoptprojet = "<option value='' selected>-- choix --</option>" ;
								$query_type_tt	= @pg_query($conn,"
									SELECT * FROM cc_sr_projet ORDER BY nom_projet ASC
								");
								for ( $i = 0; $i < @pg_num_rows( $query_type_tt ) ; $i++ )
								{
									$lg_type_tt = @pg_fetch_array( $query_type_tt );
									$zoptprojet .= "<option value='{$lg_type_tt['id_projet']}'>{$lg_type_tt['nom_projet']}</option>" ;
									
								}
								echo $zoptprojet;
							?>
						</select>
						</td>
					</tr>
                    <!--
					<tr>
						<th valign='top'>Client :</th>
						<td>
						<select name='slct_client' onchange='fill_application()' multiple id='slct_client'>
							<?php
                            /*
								$zoptclient = "<option value='' selected>-- choix --</option>" ;
								$query_type_tt	= @pg_query($conn,"
									SELECT distinct gc.id_client, gc.nom_client FROM gu_client gc inner join cc_sr_projet p on p.id_client=gc.id_client order by gc.nom_client ASC ;
								");
								for ( $i = 0; $i < @pg_num_rows( $query_type_tt ) ; $i++ )
								{
									$lg_type_tt = @pg_fetch_array( $query_type_tt );
									$zoptclient .= "<option value='{$lg_type_tt['id_client']}'>{$lg_type_tt['nom_client']}</option>" ;
									
								}
								echo $zoptclient;
                                */
							?>
						</select>
						</td>
						<td style='width:50px;'>&nbsp;</td>
						<th valign='top'><Application></th>
						<td>
							<select name='slct_application' multiple id='slct_application'>
								<option value='' selected>-- choix --</option>
							</select>						
						</td>
					</tr>
                    -->
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align='right'><input type='button' name='btn_filtrer' id='btn_filtrer' value='Filtrer' onclick='do_filtre();' /></td>
					<tr>
				</tbody>
			</table>
		</fieldset>
		<div id='dv_liste'></div>
	</div>
  </div>

</body>
</html>
