<!DOCTYPE html>
<html>
<head>
<title>Gestion des notations</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--<script type="text/javascript" src="js/jquery-1.8.3.js"></script>-
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>-->
<script type="text/javascript" src="js/jquery.thickbox.js"></script>
<script type="text/javascript" src="js/script_notation.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>

<!--<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"></link>-->
<link rel="stylesheet" href="./css/ui.core.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.theme.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.datepicker.css" type="text/css" media="screen" />
</head>
<script type='text/javascript'>
$('document').ready(function(){
	$('#id_filtre_date_notation_deb').datepicker();
	$('#id_filtre_date_notation_fin').datepicker();
	$('#id_filtre_date_appel_deb').datepicker();
	$('#id_filtre_date_appel_fin').datepicker();
	
	/*$('#id_btn_filtre_visu').click(function(){
		
	});*/
});

function visu_btn()
{
	var filtre_cc = $('#id_filtre_matricule_cc').val();
	var filtre_evaluateur = $('#id_filtre_evaluateur').val();
	var filtre_type_traitement = $('#id_filtre_type_traitement').val();
	var filtre_fichier = $('#id_filtre_fichier').val();
	var filtre_date_notation_deb = $('#id_filtre_date_notation_deb').val();
	var filtre_date_notation_fin = $('#id_filtre_date_notation_fin').val();
	var filtre_date_appel_deb = $('#id_filtre_date_appel_deb').val();
	var filtre_date_appel_fin = $('#id_filtre_date_appel_fin').val();
	var filtre_client = $('#id_filtre_client').val();
	var filtre_prestation = $('#id_filtre_prestation').val();
	var filtre_type_appel = $('#id_filtre_type_appel').val();
	var id_note_filtre = $('#id_note_filtre').val();
	var id_valeur_note_1 = $('#id_valeur_note_1').val();
	var id_valeur_note_2 = $('#id_valeur_note_2').val();
	
	var acces_suppr = $('#id_acces_suppr').val();
	
	$('#img_loading_consultation').show();
	$.post("gestion_notation.php",
	{
		filtre_cc : filtre_cc,
		filtre_evaluateur : filtre_evaluateur,
		filtre_type_traitement : filtre_type_traitement,
		filtre_fichier : filtre_fichier,
		filtre_date_notation_deb : filtre_date_notation_deb,
		filtre_date_notation_fin : filtre_date_notation_fin,
		filtre_date_appel_deb : filtre_date_appel_deb,
		filtre_date_appel_fin : filtre_date_appel_fin,
		filtre_client : filtre_client,
		filtre_prestation : filtre_prestation,
		filtre_type_appel : filtre_type_appel,
		id_note_filtre : id_note_filtre,
		id_valeur_note_1 : id_valeur_note_1,
		id_valeur_note_2 : id_valeur_note_2,
		acces_suppr : acces_suppr,
		action : 'visualiser'
	},
	function(data) {
		var response = data.split('#*#');
		$('#contenu_all_notation').html(response[0]);
		$('#contenu_all_notation').css('height','300px');
		$('#contenu_all_notation').css('border','1px solid #D6D6D6');
		$('#contenu_all_notation').css('margin-bottom','10px');
		$('#table_all_notation').tablesorter();
		$('#img_loading_consultation').hide();
		
		$('#id_valeur_nb_filtre').html('Nombre d\'\351valuations : '+response[2]);
	});
}

function set_consultation(id_notation)
{
	$.post("gestion_notation.php",
	{
		id_notation: id_notation,
		action: 'consultation'
	},
	function(data) {
		var res = data.split('###');
		var id_client = parseInt(res[3]);
		var id_application = parseInt(res[4]);
		var id_projet = parseInt(res[2]);
		var id_tlc = parseInt(res[1]);
		var id_type_traitement = parseInt(res[5]);
		var id_fichier = decodeURIComponent(res[6]);
		
		$('#img_loading').show();
		$.post("grille_dynamique.php",
		{
			id_projet_by_filtre : id_projet,
			id_client_by_filtre : id_client,
			id_application_by_filtre : id_application,
			id_fichier_by_filtre : id_fichier,
			id_type_traitement_by_filtre : id_type_traitement,
			id_tlc_by_filtre : id_tlc
		},
		function(data) {
			$("#id_contenu_by_filtre").html(data);
			$('#img_loading').hide();
			
			$('#id_affiche_filtre').css("display","none");
			$('#id_down2').css("display","block");
			$('#id_up2').css("display","none");
		});
	});
}

function reinitialisationDonneesConsultation()
{
	
	$.post("gestion_notation.php",
	{
		action: 'reinitialisation'
	},
	function(data){
		$('#id_filtre_prestation').html(data);
		$('#id_filtre_matricule_cc').val(0);
		$('#id_filtre_evaluateur').val(0);
		$('#id_filtre_fichier').val(0);
		$('#id_filtre_client').val(0);
		$('#id_filtre_type_traitement').val(0);
		$('#id_filtre_date_notation_deb').val('');
		$('#id_filtre_date_notation_fin').val('');
		$('#id_filtre_date_appel_deb').val('');
		$('#id_filtre_date_appel_fin').val('');
	});
}

function suppression_notation(id_notation)
{
	$.post("gestion_notation.php",
	{
		id_notation: id_notation,
		action: 'verification'
	},
	function(response){
		var nombre = parseInt(response);
		if(nombre > 0)
		{
			if(confirm('Des données dans la FNC sont liées à cet enregistrement. Supprimer cet enregistrement pourrait engendrer des erreurs. Voulez-vous quand même supprimer ?'))
			{
				$.post("gestion_notation.php",
				{
					id_notation: id_notation,
					action: 'supprimer'
				},
				function(data){
					var reponse = parseInt(data);
					if(reponse == 1)
					{
						$('#id_contenu_by_filtre').html('');
						visu_btn();
					}
					alert('Suppression réussie ! Veuillez aviser la qualité de la suppression des données !')
				});
			}
		}
		else
		{
			if(confirm('Voulez-vous vraiment supprimer la notation ?'))
			{
				$.post("gestion_notation.php",
				{
					id_notation: id_notation,
					action: 'supprimer'
				},
				function(data){
					var reponse = parseInt(data);
					if(reponse == 1)
					{
						$('#id_contenu_by_filtre').html('');
						visu_btn();
					}
					alert('Suppression de la notation réussie !')
				});
			}
		}
		
	});
}

function setPrestaClient()
{
	var id_client = $('#id_filtre_client').val();
	$.post("gestion_notation.php",
	{
		id_client: id_client,
		action: 'prestation'
	},
	function(data){
		$('#id_filtre_prestation').html(data);
	});
}

function setClientPresta()
{
	var id_prestation = $('#id_filtre_prestation').val();
	$.post("gestion_notation.php",
	{
		id_prestation: id_prestation,
		action: 'client'
	},
	function(data){
		if(data == '0')
		{
			$('#id_filtre_type_appel').attr('disabled',true);
			$('#id_filtre_type_appel').html('<option value=0>-- Choisir ici --</option>');
			return false;
		}
		else
		{
			var rep = data.split('|||');
			$('#id_filtre_client').val(rep[0]);
			if(parseInt(rep[1]) == 0)
			{
				$('#id_filtre_type_appel').attr('disabled',true);
				$('#id_filtre_type_appel').html('<option value=0>-- Choisir ici --</option>');
			}
			else
			{
				$('#id_filtre_type_appel').attr('disabled',false);
				$('#id_filtre_type_appel').html(rep[1]);
			}
		}
	});
}

function afficheFiltreNote_consultation()
{
	var id_note = $('#id_note_filtre').val();
	var id_note_1 = $('#id_valeur_note_1').val();
	var id_note_2 = $('#id_valeur_note_2').val();
	if(id_note == 0)
	{
		$('#id_valeur_note_1').css('visibility','hidden');
		$('#id_valeur_note_2').css('visibility','hidden');
		$('#id_et').css('visibility','hidden');
	}
	else if(id_note == 2)
	{
		$('#id_valeur_note_1').css('visibility','visible');
		$('#id_valeur_note_2').css('visibility','visible');
		$('#id_valeur_note_1').val(0);
		$('#id_valeur_note_2').val(0);
		$('#id_et').css('visibility','visible');
	}
	else
	{
		$('#id_valeur_note_1').css('visibility','visible');
		$('#id_valeur_note_1').val(0);
		$('#id_valeur_note_2').css('visibility','hidden');
		$('#id_et').css('visibility','hidden');
	}
}

</script>
<style>
	.table_contenu_consultation {
		border-collapse: collapse;
	}
	.table_contenu_consultation thead tr th {
		/*color: #446f7b;*/
		color: #FFFFFF;
		font-family: Verdana;
		font-size : 11px;
		/*background: #a0c1c9;*/
		background: #000000;
		height: 25px;
		border: 1px solid #FFFFFF;
		text-align:center;
		padding: 0 10px 0 10px;
	}
	.table_contenu_consultation tbody tr td {
		border: 1px solid #000000;
		text-align:center;
		padding: 0 10px 0 10px;
		font-family: Verdana;
		font-size : 11px;
	}
	#contenu_all_notation
	{
		display: block;
		width: 98%;
		/*height: 300px;*/
		overflow:auto;
		margin: auto;
	}
	#table_filtre_notation {
		border: 1px solid #d6d6d6;
		font-family: Verdana;
		font-size : 11px;
		font-weight:bold;
		margin: auto;
		padding: 10px;
		width:100%;
	}
	#table_filtre_notation tr td span {
		text-align: left;
		padding: 5px;
		display: block;
	}
	#table_filtre_notation tr td select {
		width: 200px;
	}
	#table_filtre_notation tr td select option {
		width: 200px;
	}
	#table_filtre_notation tr td input{
		width: 85px;
	}
	#table_filtre_notation tr td input.btn_visu {
		width: 120px;
	}
	#contenu_filtre_notation {
		display: block;
		text-align: center;
		width:828px;
		margin: auto auto 10px;
	}
</style>
<?php
include('gestion_notation.php');
$matricule_session = $_SESSION['matricule'];
$matAdmin = getPersMenuProjet();
$matNotation = getPersMenuNotation();
if( isset($matricule_session) && in_array($matricule_session,$matAdmin))
{
	$setSuppression = 1;
}
else
{
	$setSuppression = 0;
}

echo '<body>';
echo '<input type="hidden" id="id_acces_suppr" value="'.$setSuppression.'" />';
echo '<div id="contenu_filtre_notation">
	<table id="table_filtre_notation">
		<tr>
			<td><span>Matricule :</span></td>
			<td>
			<select id="id_filtre_matricule_cc" class="class_select">
				<option value="0">-- Choisir ici --</option>';
				$result_cc = getAllCCForFiltre();
				while($res_cc = pg_fetch_array($result_cc))
				{
					echo '<option value="'.$res_cc['matricule'].'">'.$res_cc['matricule'].' - '.$res_cc['prenompersonnel'].' ('.$res_cc['fonctioncourante'].')</option>';
				}
		echo '</select>
			</td>
			
			
			<td><span>Evaluateur :</span></td>
			<td>
			<select id="id_filtre_evaluateur" class="class_select">
				<option value="0">-- Choisir ici --</option>';
				$result_eval = getAllEvalForFiltre();
				while($res_eval = pg_fetch_array($result_eval))
				{
					echo '<option value="'.$res_eval['matricule'].'">'.$res_eval['matricule'].' - '.$res_eval['prenompersonnel'].' ('.$res_eval['fonctioncourante'].')</option>';
				}
		echo '</select>
			</td>
		</tr>
			
		<tr>
			<td><span>Fichier :</span></td>
			<td>
			<select id="id_filtre_fichier" class="class_select">
				<option value="0">-- Choisir ici --</option>';
				$result_fichier = getAllFichierForFiltre();
				while($res_fichier = pg_fetch_array($result_fichier))
				{
					echo '<option value="'.$res_fichier['id_fichier'].'">'.$res_fichier['nom_fichier'].'</option>';
				}
		echo '</select>
			</td>
			
			<td><span>Type de traitement :</span></td>
			<td>
			<select id="id_filtre_type_traitement" class="class_select">
				<option value="0">-- Choisir ici --</option>';
				$result_type = getAllTypeForFiltre();
				while($res_type = pg_fetch_array($result_type))
				{
					echo '<option value="'.$res_type['id_type_traitement'].'">'.$res_type['libelle_type_traitement'].'</option>';
				}
		echo '</select>
			</td>
		</tr>
		
		<tr>
			<td><span>Nom du client :</span></td>
			<td>
			<select id="id_filtre_client" class="class_select" onchange="setPrestaClient();">
				<option value="0">-- Choisir ici --</option>';
				$result_client = getAllClientForFiltre();
				while($res_client = pg_fetch_array($result_client))
				{
					echo '<option value="'.$res_client['id_client'].'">'.$res_client['nom_client'].'</option>';
				}
		echo '</select>
			</td>
			
			<td><span>Prestation :</span></td>
			<td>
			<select id="id_filtre_prestation" class="class_select" onchange="setClientPresta();">';
				/*<option value="0">-- Choisir ici --</option>';
				$result_prest = getAllPrestationForFiltre();
				while($res_prest = pg_fetch_array($result_prest))
				{
					echo '<option value="'.$res_prest['id_application'].'">'.$res_prest['code'].' - '.$res_prest['nom_application'].'</option>';
				} */
				echo getAllPrestationForFiltre();
		echo '</select>
			</td>
		</tr>
		
		<tr>
			<td><span>Date notation :</span></td>
			<td><input type="text" id="id_filtre_date_notation_deb" /> au <input type="text" id="id_filtre_date_notation_fin" /></td>
			
			
			<td><span>Date appel :</span></td>
			<td><input type="text" id="id_filtre_date_appel_deb" /> au <input type="text" id="id_filtre_date_appel_fin" /></td>
			
		</tr>
		<tr>
			<td><span>Type d\'appel :</span></td>
			<td><select id="id_filtre_type_appel" class="class_select" disabled>
			<option value=0>-- Choisir ici --</option>
			</select></td>
			<td><span style="display:block;float:left">Note  </span>
			<select style="background-color: #efefef;display: block;float: left;font-size: 10px;margin: 3px;position: relative;width: 125px;" id="id_note_filtre" onchange=afficheFiltreNote_consultation();>
				<option value=0>-- Choix --</option>
				<option value=1>Egal à</option>
				<option value=2>Entre</option>
				<option value=3>Inférieur à</option>
				<option value=4>Inférieur ou Egal à</option>
				<option value=5>Supérieur à</option>
				<option value=6>Supérieur ou Egal à</option>
			</select>
			</td>
			<td><input type="text" id="id_valeur_note_1" style="float: left;font-size: 10px;height: 15px;margin: 0 0 0 18px;text-align: center;visibility: hidden;width: 85px;" value=0 />
			<span id="id_et" style="visibility:hidden;float: left;"> et </span>
			<input type="text" id="id_valeur_note_2" style="font-size: 10px;height: 15px;margin: 0 0 0 -18px;text-align: center;visibility: hidden;width: 85px;" value=0 /></td>
			
			<!--<td>
				<div style="border: 0px solid #000000;display: block;float: left;height: 17px;margin: 3px;position: relative;width: 216px;" id="id_div_note_filtre">
					<input type="text" id="id_valeur_note_1" style="visibility:hidden;background-color: #efefef;font-size: 10px;height: 15px;text-align: center;width: 94px;" value=0 />
					<span id="id_et" style="visibility:hidden;"> et </span>
					<input type="text" id="id_valeur_note_2" style="visibility:hidden;background-color: #efefef;font-size: 10px;height: 15px;text-align: center;width: 94px;" value=0 />
				</div>
			</td>-->
			
		</tr>
		<tr>
			<td colspan="6" style="text-align:center;padding:10px;">
			<div style="display:block;margin:auto;width:35%;position:relative;">
				<input type="button" style="display:block;position:relative;float:left" class="btn_visu" value="Rechercher" id="id_btn_filtre_visu" onclick=visu_btn(); />
				<span style="display:block;position:relative;float:left;">&nbsp;&nbsp;</span>
				<input type="button" style="display:block;position:relative;float:left;" onclick="reinitialisationDonneesConsultation();" title="Réinitialisation du filtre" value="Réinitialisation" class="btn_visu">
			</div>
			
			</td>
		</tr>
		<tr>
			<td colspan="4"><span id="id_valeur_nb_filtre" style="text-align:center;display:block;margin: -10px auto;color:#00219b;"></span></td>
		</tr>
	</table>
	<center><img id="img_loading_consultation"  style="display:none;" src="images/wait.gif" width="30" height="30"/></center>
</div>';

echo '<div id="contenu_all_notation">';
echo '</div>';

echo '</body>
</html>';
?>