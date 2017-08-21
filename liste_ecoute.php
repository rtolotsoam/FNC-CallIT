<!--<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"></link>
<link rel="stylesheet" href="css/tablesorter.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.datepicker.css" type="text/css" media="screen" />-->
<script>
	$(function(){
		$('#id_btn_appel').click(function(){
			var val_tab = $('#id_ct_table').val();
			var date_debut_call = $('#id_date_deb_appel').val();
			var date_fin_call = $('#id_date_fin_appel').val();
			if(date_debut_call == '' || date_fin_call == '')
			{
				alert('Les dates d\'appels sont indispensables !');
				return false;
			}
			else if(val_tab == '')
			{
				$('#id_donnee_contenu').html('<span style="font-size:12px;font-family:Verdana;padding:20px;font-weight:bold;">Aucun enregistrement !</span>');
				return false;
			}
			else
			{
				$('#img_loading_fichier').show();
				$.post("function_liste_ecoute.php",
				{
					ct_table: $('#id_ct_table').val(),
					date_debut_call: $('#id_date_deb_appel').val(),
					date_fin_call: $('#id_date_fin_appel').val(),
					id_projet_call: $('#id_projet_call').val(),
					id_client_call: $('#id_client_call').val(),
					id_application_call: $('#id_application_call').val(),
					id_type_traitement_call: $('#id_type_traitement_call').val(),
					id_tlc_call: $('#id_tlc_call').val()
				},
				function(_data){
					var reponse = _data.split('||||');
					$('#id_donnee_contenu').html(reponse[0]);
					$('.class_donnee_contenu').tablesorter();
					$('#img_loading_fichier').hide();
					$('#id_nb_enrg').html(reponse[1]+' enregistrement(s)');
				});
			}
		});
		
		$('#id_date_deb_appel').datepicker();
		$('#id_date_fin_appel').datepicker();
		
		
		$('.lien_ecoute').each(function(){
			$(this).click(function(){
				page = ($(this).attr("href"));
				
				jQuery.ajax({
				url: page,
				dataType: "html",
				success: function (data, textStatus, rawRequest) {
					$("#id_div_page_ecoute").html('test');
				},
				error: function (rawRequest, textStatus, errorThrow) {
					$("#id_div_page_ecoute").html("Erreur de chargement...");
				}
				});
			});
		});
	});
	
	function openEcoute(lg,ps,easycode,i)
	{
		var easycode = $('#easycode_'+i).val();
		var mat_agent = $('#mat_agent_'+i).val();
		var lien = 'https://41.188.3.110/records/?user='+lg+'&pass='+ps+'&easycode='+easycode;
		window.open(lien); 
		//$("#id_div_page_ecoute").html('test');
		$("#id_tlc_filtre").val(mat_agent);
		$("#id_fichier_filtre").val(easycode);
		tb_remove();
		return false;
	}
</script>
<style>
	#id_donnee_contenu {
		display:block;
		border:0px solid blue;
		margin:10px 0;
		width: 720px;
		height: 400px;
		overflow: auto;
		border: 1px solid #487790;
	}
	#id_donnee_contenu .class_donnee_contenu {
		font-family: Verdana;
		font-size: 11px;
		border: 1px solid #487790;
		border-collapse: collapse;
		text-align: center;
		width: 100%;
	}
	#id_donnee_contenu .class_donnee_contenu thead tr th {
		border: 1px solid #487790;
		background: #B2C6CD;
		font-weight: bold;
		padding: 5px;
	}
	#id_donnee_contenu .class_donnee_contenu tbody tr td {
		border: 1px solid #487790;	
		padding: 3px;
	}
	#id_date_contenu {
		display:block;
		border:0px solid red;
	}
	#id_date_contenu table {
		font-family: Verdana;
		font-size: 11px;
		padding: 10px;
	}
	#id_apercu_donnee {
		border: 1px solid #487790;
	    display: block;
	    float: left;
	    font-family: Verdana;
	    font-size: 11px;
	    padding: 10px;
	    margin: 10px 0;
	}
	#id_apercu_donnee span {
		font-weight: bold;
	}
</style>
<?php

include ('function_liste_ecoute.php');

/*
$id_projet = 84;
$id_client = 1006;
$id_application = 1850;
$id_type_traitement = 1;
*/
$id_projet = $_REQUEST['id_projet'];
$id_client = $_REQUEST['id_client'];
$id_application = $_REQUEST['id_application'];
$id_type_traitement = $_REQUEST['id_type_traitement'];
$id_tlc = $_REQUEST['id_tlc'];

$tab_donnees = getProjetCall($id_projet,$id_client,$id_application,$id_tlc);

$zHtml = '';
$zHtml .= '<div id="id_apercu_donnee">
<input type="hidden" id="id_projet_call" value="'.$id_projet.'"/>
<input type="hidden" id="id_client_call" value="'.$id_client.'"/>
<input type="hidden" id="id_application_call" value="'.$id_application.'"/>
<input type="hidden" id="id_type_traitement_call" value="'.$id_type_traitement.'"/>
<input type="hidden" id="id_tlc_call" value="'.$id_tlc.'"/>
<span>Client : </span>'.$tab_donnees['nom_client'].'</br>';
if($id_tlc != 0)
{
	$zHtml .= '<span>Prestation : </span>'.$tab_donnees['code'].'</br>';
	$zHtml .= '<span>CC : </span>'.$tab_donnees['matricule'].' - '.$tab_donnees['prenom'].'</div>';
}
else
{
	$zHtml .= '<span>Prestation : </span>'.$tab_donnees['code'].'</br>';
	$zHtml .= '<span>CC : </span>Tous les CC</div>';
}

$ct_table = array();
$ct_table = getAllCampaign($id_type_traitement,$id_projet);
$ct_tab = $ct_table[$id_type_traitement][$id_projet]['table'];
$nb_tab = count($ct_tab);
$str = '';
for($i=0;$i<count($ct_tab);$i++)
{
	$str .= $ct_tab[$i];
	if($i<($nb_tab-1))
	{
		$str .= ';';
	}
}
$zHtml .= '<div id="id_date_contenu">
<input type="hidden" value="'.$str.'" id="id_ct_table" />
	<table><tr>
	<td>Date appel:</td>
	<td><input type="text" id="id_date_deb_appel" /></td>
	<td>au:</td>
	<td><input type="text" id="id_date_fin_appel" /></td>
	<td><img id="id_btn_appel" src="images/search_1.png" width="20px" height="20px" title="Visualiser" style="cursor:pointer" /></td>
	</tr></table>
	<center><img id="img_loading_fichier"  style="display:none;" src="images/wait.gif" width="30" height="30"/></center>
	<span id="id_nb_enrg" style="font-family:Verdana;font-size:11px;font-weight:bold;padding:10px;"></span>
</div>';

$zHtml .= '<div id="id_donnee_contenu">';
$zHtml .= '</div>';

//$zHtml .= '<div id="id_div_page_ecoute" style="border:1px solid red;display:block;width:100px;height:100px;">';
//$zHtml .= '</div>';

echo $zHtml;

?>