<?php 
	 session_start();
	 include("/var/www.cache/dgconn.inc");
	 $fct = $_SESSION['zFonction'];
	 
	 $tFctAuthorise		 = array('RESP RD','CHQ','AQI','DQ','DCT','DCC','RP','RESP PLATEAU','AQI','SUP','SUP CC','SUP_CC','TC','OL');
	 $tFctAuthoriseInit  = array('RESP RD','AQI','DQ','DCT','DCC','RP','RESP PLATEAU','AQI','SUP','SUP CC','SUP_CC');
	 
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
<link href="./css/style_synthese.css" rel="stylesheet" type="text/css" />
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
			
			//do_filtre() ;
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
	    var projet = $("#slct_projet").val();
	    var client = $("#slct_client").val();
	    var auditeur = $("#slct_auditeur").val();
	    var application =$("#slct_application").val();
		if ( ( $("#txt_date_deb").val()=="" && $("#txt_date_fin").val()!=""  ) || ( $("#txt_date_deb").val()!="" && $("#txt_date_fin").val()=="" ) ||  ( $("#txt_date_deb").val() > $("#txt_date_fin").val()  ) )
		{
			alert("Plage de date incorrecte !");
		}else{
			$("#dv_liste").html("<p><img src='images/loadingAnimation.gif' />Chargement</p>");
			$.post("synthese_dynamique.php",
			{
				datedeb : $("#txt_date_deb").val(),
				datefin : $("#txt_date_fin").val(),
				auditeur : auditeur,
				id_projet : 75,
				client : 22,
				application : 5,
				typetraitement : $("#slct_type_traitement").val()
			},
			 function(_data){
				$("#dv_liste").html(_data);
				
				
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
			<table border ='1' width='700px' class='tbl_filtre'>
				<tbody>
					<tr>
						<th>Date d&eacute;but :</th>
						<td><input type='text' id='txt_date_deb' name='txt_date_deb' value='<?php echo date('Y-m-01'); ?>' /></td>
						<td>&nbsp;</td>
						<th  valign='top' rowspan='3'>&nbsp;</th>
						<td  valign='top' rowspan='3'>&nbsp;</td>
							
						
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
						<select name='slct_auditeur'  id='slct_auditeur'>
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
                                            and fonctioncourante in ('SUP CC','RP','RESP PLATEAU','AQI','DQ','DCC','DCT') 
                                            and deptcourant in ('CC', 'DQ', 'DCC', 'DCT')
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
						<select name='slct_projet'  id='slct_projet' onchange='fill_client();'>
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
		<div id='dv_liste' style='display:none1;'></div>
	</div>
  </div>

</body>
</html>
