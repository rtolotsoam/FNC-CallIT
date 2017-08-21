<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Gestion des notes</title>
<!--<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>-->

<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>

<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/script_maquette.js"></script>
<script type="text/javascript" src="js/jquery.thickbox.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery.simpletooltip-min.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>

<script src="js/chosen/chosen.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/chosen/chosen.css"></link>

<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"></link>

<link rel="stylesheet" href="./css/ui.core.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.theme.css" type="text/css" media="screen" />

<link rel="stylesheet" type="text/css" href="style_maquette.css"></link>
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/tablesorter.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.datepicker.css" type="text/css" media="screen" />

<!--<link href="css/smart_wizard.css" rel="stylesheet" type="text/css">
<link href="css/smart_tab.css" rel="stylesheet" type="text/css"></link>-->

<!--<link rel="stylesheet" type="text/css" href="css/style.css"></link>-->

</head>

 <script>
$(function() {
	$("#demo_7.tooltiplink").simpletooltip();
	$("#demo_7.clic").simpletooltip({click: true});
	$("#demo_7.delay").simpletooltip({hideDelay: 0.5});
	
	$("#id_tlc_filtre").chosen();

	/*$( "#tags" ).autocomplete({
		source: availableTags,
	});*/

	setAutocomplete(0, 0, 0);

	$('#id_select_fichier').click(function(){
		var id_projet = $('#id_campagne_filtre').val();
		var id_client = $('#id_client_filtre').val();
		var id_application = $('#id_prestation_filtre').val();
		var id_tlc = $('#id_tlc_filtre').val();
		var id_type_traitement = $('#id_type_traitement_filtre').val();
		if(id_projet == 0 || id_client == 0 || id_type_traitement == 0)
		{
			alert('Les champs client, prestation et type de traitement sont indispensables pour cette option !!');
			return false;
		}
		else if(id_type_traitement == 3 || id_type_traitement == 4)
		{
			alert('Cette option n\'est utilisable que pour les appels !');
		}
		else
		{
			tb_show("Liste des \351coutes","liste_ecoute.php?height=500&width=725&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&id_tlc="+id_tlc+"&id_type_traitement="+id_type_traitement);
		}
	});
});
</script>
<style>
ul.ui-autocomplete.ui-menu li a{
    /*color:blue;*/
    font-size: 11px;
    font-family: Verdana;
}
#btn_intermediaire_consultation:hover {
	color: #99EAF5;
}
</style>
<script>
function filtreDonnees(filtre)
{
	console.log('filtreDonnees');
	
	var id_client = $('#id_client_filtre').val();
	var id_application = $('#id_prestation_filtre').val();
	var id_projet = $('#id_campagne_filtre').val();
	var id_tlc = $('#id_tlc_filtre').val();
	var id_type_traitement = $('#id_type_traitement_filtre').val();
	var id_fichier = $('#id_fichier_filtre').val();
	var champ_filtre = filtre;
	$.post("function_filtre_dynamique.php",
	{
		id_client_filtre: id_client,
		id_application_filtre: id_application,
		id_projet_filtre: id_projet,
		id_tlc_filtre: id_tlc,
		id_type_traitement_filtre: id_type_traitement,
		id_fichier_filtre: id_fichier,
		champ_filtre: champ_filtre
	},
	function(data) {
		if(champ_filtre == 'client')
		{
			$('#id_prestation_filtre').html(data);
		}
		else if(champ_filtre == 'code')
		{
			var _data = data.split('||');
			$('#id_client_filtre').val(_data[0]);
			$('#id_campagne_filtre').val(_data[1]);
		}

	});
}

function reinitialisationDonnees()
{
	console.log('reinitialisationDonnees');
	
	$.post("function_filtre_dynamique.php",
	{
		reinitialisation : 1
	},
	function(data) {
		// projet ||| client ||| application ||| type_traitement ||| fichier
		var tab = data.split('|||');
		if(tab[0] != '')
		{
			$('#id_campagne_filtre').html(tab[0]);
		}
		if(tab[1] != '')
		{
			$('#id_client_filtre').html(tab[1]);
		}
		if(tab[2] != '')
		{
			$('#id_prestation_filtre').html(tab[2]);
		}
		
		$("#id_tlc_filtre").val(0).trigger("chosen:updated");// initialise le select chosen pour le champ "Matricule"
		
		if(tab[3] != '')
		{
			$('#id_type_traitement_filtre').html(tab[3]);
		}
		if(tab[4] != '')
		{
			$('#id_fichier_filtre').val('');
		}
		// if(tab[5] != '')
		// {
			// $('#id_tlc_filtre').val(tab[5]);
		// }
	});
}

function afficheNotation()
{
	var id_client          = $('#id_client_filtre').val();
	var id_application     = $('#id_prestation_filtre').val();
	var id_projet          = $('#id_campagne_filtre').val();
	var id_tlc             = $('#id_tlc_filtre').val();
	var id_type_traitement = $('#id_type_traitement_filtre').val();
	var id_fichier         = decodeURIComponent($('#id_fichier_filtre').val());

	if(id_projet == 0 || id_client == 0 || id_application == 0 || id_tlc == 0 || id_fichier == '' || id_type_traitement == 0)
	{
		alert('Tous les champs sont obligatoires !!')
	}
	else
	{
		$('#img_loading').show();

		$.post("grille_dynamique.php",
		{
			id_projet_by_filtre          : id_projet,
			id_client_by_filtre          : id_client,
			id_application_by_filtre     : id_application,
			id_fichier_by_filtre         : id_fichier,
			id_type_traitement_by_filtre : id_type_traitement,
			id_tlc_by_filtre             : id_tlc
		},
		function(data) {
			$("#id_contenu_by_filtre").html(data);
			$('#img_loading').hide();

			$('#id_affiche_filtre').css("display","none");
			$('#id_down2').css("display","block");
			$('#id_up2').css("display","none");
		});

	}
}

function cacher_filtre()
{
	$('#id_affiche_filtre').css("display","none");
	//$('#id_contenu_by_consultation').css('display','block');
	$('#id_down2').css("display","block");
	$('#id_up2').css("display","none");
}

function afficher_filtre()
{
	$('#id_affiche_filtre').css("display","block");
	//$('#id_contenu_by_consultation').css('display','none');
	$('#id_down2').css("display","none");
	$('#id_up2').css("display","block");
}

function afficher_filtre_consultation() //afficher formulaire pour filtrer les données de notation
{
	$("#img_loading_consultation").show();
	$.ajax({
		success: function(){
			$('#id_contenu_by_consultation').css('display','block'); //contenu formulaire filtre

			$('#btn_intermediaire_consultation').css('display','none');
			$('#btn_intermediaire_notation').css('display','block');
			$("#img_loading_consultation").hide();
		}
	});
}

function afficher_filtre_notation()
{
	$("#img_loading_consultation").show();
	$.ajax({
		success: function(){
			$('#id_contenu_by_consultation').css('display','none');

			$('#btn_intermediaire_consultation').css('display','block');
			$('#btn_intermediaire_notation').css('display','none');
			$("#img_loading_consultation").hide();
		}
	});
}

function isNormalCharacter(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	console.log(charCode);
	if (charCode ==38 || charCode ==34 || charCode ==96 || charCode ==176|| charCode ==35  ) {
		return false;
	}
	return true;
 }

 function change_button(){
    $("#actualise_link").show();
	$("#redirection_link").hide();

 }
</script>

	    <?php

		      session_start();

              $matricule_session = $_SESSION['matricule'];

			$_addr = $_SERVER['REQUEST_URI'];
			$t_addr = explode('/',$_addr);
			$cur_addr = $t_addr[count($t_addr)-1];
			include('gestion_droit.php');
			//$matAdmin = array(6548,6568,5686,6211,5049,5066,5196,5377,7121,7122,628,6550,6899);
			$matAdmin = getPersMenuProjet();
	  		$matNotation = getPersMenuNotation();

			if( !isset($matricule_session) || !in_array($matricule_session,$matNotation)){
						echo "<p style='color:red;font-size:13px;'><b>Acc�s non authoris� ou session expir�e!</b>";
						echo "<a  class='no_acces_btn' onclick='change_button();' id='redirection_link' href='" .$tHost. "/gpao/' target='_blank' >Aller dans la page d'accueil</a>
						<a class='no_acces_btn' style='display:none;' id='actualise_link' href='" .$tHost. "/gpao2/cc_sr/filtre_dynamique.php' >Actualiser la page</a>
						</p>";
                      // header ("Refresh: 3;URL=http://".$tHost."/gpao/");
		          exit;
              }
		?>
		<body>
<div id="main">
  <div id="contentbg">
    <div id="contenttxtblank">
	   <div id="menu">
        <ul>
<?php
if( isset($matricule_session) && in_array($matricule_session,$matNotation))
 {
?>
		   <li><a href="filtre_dynamique.php" <?php if($cur_addr=='filtre_dynamique.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Notation</a></li>
       <li><a href="filtre_dynamique_exportation.php" <?php if($cur_addr=='filtre_dynamique_exportation.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Export-Notation</a></li>
<?php
 }

if( isset($matricule_session) && in_array($matricule_session,$matAdmin))
 {
?>
		  <li><a href="interface.php" <?php if($cur_addr=='interface.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Projet</a></li>
<?php
 }
?>

          <!--<li><a href="nb_reecoute.php" <?php if($cur_addr=='nb_reecoute.php') echo 'class="active"'; else echo 'class="menu"';?>>Suivi</a></li>
          <li class="menusap"></li>-->
          <?php
		// if ( in_array( $fct, $tFctAuthoriseInit )  )
		 // {
		  ?>
         <!-- <li><a href="index.php" <?php if($cur_addr=='index.php' || $cur_addr=='' ) echo 'class="active"'; else echo 'class="menu"'; ?> >&eacute;coute</a></li>
          <li class="menusap"></li>-->
<?php
if( isset($matricule_session) && in_array($matricule_session,$matNotation))
 {
?>
          <li><a  href="recap_synthese.php" <?php if($cur_addr=='recap_synthese.php') echo 'class="active"'; else echo 'class="menu"';?>>Synth&egrave;ses</a></li>
<?php
 }
?>
          <li class="menusap"></li>
          <!--<li><a href="indicateur_nf.php" <?php if($cur_addr=='indicateur_nf.php') echo 'class="active"'; else echo 'class="menu"';?>>Indicateurs</a></li>
          <li class="menusap"></li>-->

          <li class="menusap"></li>
          <?php
		 // }
		  ?>
        </ul>
       </div>
	  </div>
	 </div>

<div class='acc_container'>
<div class='block' id="id_affiche_filtre">
<?php
include('function_filtre_dynamique.php');
?>
<div style="border:0px solid red;width:68%;margin:auto;display:block;">
	<table style="font-size:11px;font-family:Verdana;text-align:left;">
		<tr>
			<th>Nom du client :<span style="color:red">*</span></th>
			<td>
				<select class="class_select" id="id_client_filtre" onchange="filtreDonnees('client');">
					<?php
						echo '<option value="0">-- Choix --</option>';
						$result_client = fetchAllProject('client');
						$tab_client    = array();
						while ($res_client = pg_fetch_array($result_client))
						{
							if(!in_array($res_client['id_client'],$tab_client))
							{
								echo '<option value="'.$res_client['id_client'].'">'.$res_client['nom_client'].'</option>';
								array_push($tab_client,$res_client['id_client']);
							}
						}
					?>
				</select>
			</td>
		</tr>

		<tr>
			<th>Prestation :<span style="color:red">*</span></th>
			<td>
				<select class="class_select" id="id_prestation_filtre" onchange="filtreDonnees('code');">
					<?php
						echo '<option value="0">-- Choix --</option>';
						$result_presta = fetchAllProject('application');
						while ($res_presta = pg_fetch_array($result_presta))
						{
							echo '<option value="'.$res_presta['id_application'].'">'.$res_presta['code'].' - '.$res_presta['nom_application'].'</option>';
						}
					?>
				</select>
			</td>
		</tr>
		<!--<tr>
			<th>Campagne : </th>
			<td>-->
				<select class="class_select" id="id_campagne_filtre" hidden>
					<?php
						echo '<option value="0">-- Choix --</option>';
						$result_project = fetchAllProject('projet');
						while ($res_projet = pg_fetch_array($result_project))
						{
							echo '<option value="'.$res_projet['id_projet'].'">'.$res_projet['nom_projet'].'</option>';
						}
					?>
				</select>
			<!--
			</td>
		</tr>-->

		<tr>
			<th>Matricule :<span style="color:red">*</span></th>
			<td>
				<select class="class_select" id="id_tlc_filtre">
					<?php
						echo '<option value="0">-- Choix --</option>';
						$result_tlc = fetchAllTLC();
						while ($res_tlc = pg_fetch_array($result_tlc))
						{
							echo '<option value="'.$res_tlc['matricule'].'">'.$res_tlc['matricule'].' - '.$res_tlc['prenompersonnel'].' - ( '.$res_tlc['fonctioncourante'].' )</option>';
						}
					?>
				</select>
			</td>
		</tr>

		<tr>
			<th>Type de traitement :<span style="color:red">*</span></th>
			<td>
				<select class="class_select" id="id_type_traitement_filtre">
					<?php
						echo '<option value="0">-- Choix --</option>';
						$result_type = fetchAllTypeTraitement();
						while ($res_type = pg_fetch_array($result_type))
						{
							// echo '<option value="'.$res_type['id_type_traitement'].'">'.$res_type['libelle_type_traitement'].'</option>';
							echo '<option value="'.$res_type['id_type_traitement'].'">'.$res_type['libelle_type_traitement'].'</option>';
						}
					?>
				</select>
			</td>
		</tr>

		<tr>
			<th><label for="tags">Fichier: </label><span style="color:red">*</span></th>
			<td>
				<!--<select class="class_select" id="id_fichier_filtre" onchange="filtreDonnees();">-->
				<input type="text" id="idfichierfiltre" hidden />
				<input type="text" onkeypress="return isNormalCharacter(event);"  placeholder="Fichier" id="id_fichier_filtre" style="width:348px;margin:0 0 0 3px" />
				<?php
				/*
				echo '<option value="0">-- Choix --</option>';
				$result_fichier = fetchAllFichierInit();
				while ($res_fichier = pg_fetch_array($result_fichier))
				{
					echo '<option value="'.$res_fichier['id_fichier'].'">'.$res_fichier['nom_fichier'].'</option>';
				}
				*/
				?>
				<!--</select>-->
			</td>
			<td>
				<img id="id_select_fichier" src="images/choose3.png" width="20px" height="20px" title="Voir les enregistrements" style="cursor:pointer" />
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center;">
				<div style="display:block;margin:auto;width:53%;">
					<input class="btn_visu" type="button" value="Visualiser" title="Visualiser" onclick="afficheNotation();" style="display:block;position:relative;float:left;" />
					<span style="display:block;position:relative;float:left;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
					<input class="btn_visu" type="button" value="R�initialisation" title="R�initialisation du filtre" onclick="reinitialisationDonnees();" style="display:block;position:relative;float:left;" />
				</div>
			</td>
		</tr>
	</table>
</div>

    </div>

  </div>

 </div>

<div style="width: 100%;" id="id_up_down">
	<center>
		<img id="id_up2"  style="display:none;cursor: pointer;" src="images/up2.png" width="30" height="25" onclick="cacher_filtre();"/>
		<img id="id_down2"  style="display:none;cursor: pointer;" src="images/down2.png" width="30" height="25" onclick="afficher_filtre();"/>
	</center>
</div>

<!--<input type="hidden" id="id_verif_filtre" value="filtrer" />-->
<input type="button" class="btn_visu" style="width:200px;display:block;background:#444444;color:#c5c5c5;padding:2px;" value="Afficher la consultation" id="btn_intermediaire_consultation" onclick="afficher_filtre_consultation();" />
<input type="button" class="btn_visu" style="width:200px;display:none;background:#444444;color:#99EAF5;padding:2px;" value="Cacher la consultation" id="btn_intermediaire_notation" onclick="afficher_filtre_notation();" />

<div>
	<center> <img id="img_loading_consultation"  style="display:none;" src="images/wait.gif" width="30" height="30"/> </center>
</div>

<div id="id_contenu_by_consultation" style="display:none">
	<?php
		include('notation.php'); //charger contenu formulaire pou filtre
	?>
</div>
<center> <img id="img_loading"  style="display:none;" src="images/wait.gif" width="30" height="30"/> </center>
<div id="id_contenu_by_filtre">

</div>

</body>
</html>


<?php
//----------------------------------- save acces module ---------------------------------
	include("../access_modules_count/enrg_acces_module.php") ;
	data_acces("Suivi de r�-�coute des appels|CalliT|Suivi de r�-�coute", "GPAO", $_SERVER['PHP_SELF']);
//----------------------------------------------------------------------------
?>
