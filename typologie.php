<html>
<head>
<title>Gestion des typologies</title>

<!--script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script-->
<link rel="stylesheet" type="text/css" href="css/style_classement.css"></link>

<script>
	$(document).ready(function(){
		$('#id_btn_ajout_typo').click(function(){
			var libelle_ajout = $('#id_ajout_typologie').val();
			var id_projet = $('#id_projet_typo').val();
			$.post("gestion_typologie.php",
			{
				libelle_typo : libelle_ajout,
				id_projet: id_projet,
				action: 'add'
			},
			function(data) {
				$('#id_ajout_typologie').val('');
				$("#id_divtypo_tablesorter").html(data);
				alert('Insertion avec succ\350s !');
			});
		});
	});
	
	function filtreDonneesTypo(filtre)
	{
		var id_client = $('#id_nom_client_typo').val();
		var id_application = $('#id_prestation_typo').val();
		var id_projet = $('#id_projet_typo').val();
		var champ_typo = filtre;
		$.post("gestion_typologie.php",
		{
			id_client_typo: id_client,
			id_application_typo: id_application,
			id_projet_typo: id_projet,
			champ_typo: champ_typo
		},
		function(data) {
			if(champ_typo == 'client')
			{
				$('#id_prestation_typo').html(data);
				//setTableauTypologie();
			}
			else if(champ_typo == 'code')
			{
				var _data = data.split('||');
				$('#id_nom_client_typo').val(_data[0]);
				$('#id_projet_typo').val(_data[1]);
				
				setTableauTypologie();
			}
		});
	}
	
	function setTableauTypologie()
	{
		var id_client = $('#id_nom_client_typo').val();
		var id_application = $('#id_prestation_typo').val();
		var id_projet = $('#id_projet_typo').val();
		
		$.post("gestion_typologie.php",
		{
			id_client_typo: id_client,
			id_application_typo: id_application,
			id_projet_typo: id_projet,
			setTableau: 1
		},
		function(data) {
			$("#id_divtypo_tablesorter").html(data);
			$("#id_div_ajout_typo").css('display','block');
		});
	}
	
	function editTypo(id_typo)
	{
		$('#id_libelle_typologie_'+id_typo).css('display','none');
		$('#id_edit_typologie_'+id_typo).css('display','block');
		$('#id_img_save_typo_'+id_typo).removeAttr('style');
		$('#id_img_save_typo_'+id_typo).css('cursor','pointer');
		$('#id_img_edit_typo_'+id_typo).css('display','none');
		$('#id_edit_typologie_'+id_typo).val($('#id_libelle_typologie_'+id_typo).html());
	}
	
	function saveTypo(id_typo)
	{
		var valeur_edit = $('#id_edit_typologie_'+id_typo).val();
		$.post("gestion_typologie.php",
		{
			libelle_typo : valeur_edit,
			id_typologie : id_typo,
			action: 'save'
		},
		function(data) {
			$('#id_edit_typologie_'+id_typo).css('display','none');
			$('#id_libelle_typologie_'+id_typo).removeAttr('style');
			$('#id_libelle_typologie_'+id_typo).css('padding','0px 0px 0px 10px');
			$('#id_libelle_typologie_'+id_typo).html(valeur_edit);
			$('#id_img_edit_typo_'+id_typo).removeAttr('style');
			$('#id_img_edit_typo_'+id_typo).css('cursor','pointer');
			$('#id_img_save_typo_'+id_typo).css('display','none');
			alert('Modification avec succ\350s !');
		});
	}
	
	function deleteTypo(id_typo)
	{
		var id_projet = $('#id_projet_typo').val();
		if(confirm('Voulez-vous vraiment supprimer la typologie?'))
		{
			$.post("gestion_typologie.php",
			{
				id_typologie : id_typo,
				id_projet: id_projet,
				action: 'delete'
			},
			function(data) {
				$("#id_divtypo_tablesorter").html(data);
				alert('Suppression avec succ\350s !');
			});
		}
	}
	
	
</script>
</head>
<body>
<?php
include('gestion_typologie.php');

if(isset($_REQUEST['id_projet_typo']))
{
	$id_projet = $_REQUEST['id_projet_typo'];
	$id_client = $_REQUEST['id_client_typo'];
	$id_application = $_REQUEST['id_application_typo'];
}
else
{
	$id_projet = 0;
	$id_client = 0;
	$id_application = 0;
}

$zHtml = '<div>';
$zHtml .= '<table class="class_table_div2">
	<tr><th>Nom du client : </th>
	<td>
	<input type="hidden" id="id_projet_typo" value="'.$id_projet.'" />
 	<select id="id_nom_client_typo" style="width:350px;font-size:11px;font-family:verdana;height:20px;" onchange="filtreDonneesTypo(\'client\');">
 	<option value="0">-- Choix --</option>';
		$result_client = fetchAllProjectTypologie('client');
		$tab_client = array();
		//$res_client = array();
		while ($res_client = pg_fetch_array($result_client))
		{
			if(!in_array($res_client['id_client'],$tab_client))
			{
				if($res_client['id_client'] == $id_client)
				{
					$selected = 'selected="selected"';
				}
				else 
				{
					$selected = '';
				}
				$zHtml .= '<option value="'.$res_client['id_client'].'" '.$selected.'>'.$res_client['nom_client'].'</option>';
				array_push($tab_client,$res_client['id_client']);
			}
		}
$zHtml .= '</select>
	</td>
	</tr>
	<tr>
	<th>Prestation : </th>
	<td>
	<select id="id_prestation_typo" style="width:350px;font-size:11px;font-family:verdana;height:20px;" onchange="filtreDonneesTypo(\'code\');">
	<option value="0">-- Choix --</option>';
		$result_presta = fetchAllProjectTypologie('application');
		while ($res_presta = pg_fetch_array($result_presta))
		{
			if($res_presta['id_application'] == $id_application)
			{
				$selected = 'selected="selected"';
			}
			else 
			{
				$selected = '';
			}
			$zHtml .= '<option value="'.$res_presta['id_application'].'" '.$selected.'>'.$res_presta['code'].' - '.$res_presta['nom_application'].'</option>';
		}	 
$zHtml .= '</select>
	</td></tr>
</table>';
$zHtml .= '</div>';
$afficher_div_ajout = '';
if($id_projet == 0 && $id_client == 0 && $id_application == 0)
{
	$afficher_div_ajout = 'style="display:none;"';
}
$zHtml .= '<div id="id_div_ajout_typo" '.$afficher_div_ajout.'>';
$zHtml .= '<table class="class_table_div2">
	<tr>
	<th>Ajouter un nouveau libell&eacute; : </th>
	<td><input type="text" style="width:350px;font-size:11px;font-family:verdana;" id="id_ajout_typologie" /></td>
	<td><input type="button" id="id_btn_ajout_typo" class="btn_enreg" value="Ajouter" /></td>
	</tr>
	</table>';
$zHtml .= '</div>';

$zHtml .= '<div id="id_divtypo_tablesorter" style="height: 375px;overflow: auto">';
if($id_projet != 0 && $id_client != 0 && $id_application != 0)
{
	$zHtml .= getTypoByProjet($id_projet);
}
$zHtml .= '</div>';
echo $zHtml;
?>
</body>
</html>