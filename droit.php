<!DOCTYPE html>
<html>
<head>
<title>Gestion des droits</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.thickbox.js"></script>-->
<script type='text/javascript'>
$('document').ready(function(){
	$('#id_table_droit').tablesorter();
});

function inserer_droit()
{
	var matricule_droit = $('#matricule_droit').val();
	var eval_droit = $('#evaluation_droit').prop('checked');
	var admin_droit = $('#administration_droit').prop('checked');
	if(eval_droit == true)
	{
		eval_droit = 1;
	}
	else
	{
		eval_droit = 0;
	}
	if(admin_droit == true)
	{
		admin_droit = 1;
	}
	else
	{
		admin_droit = 0;
	}
	//alert(matricule_droit+'***'+eval_droit+'***'+admin_droit);return false;
	$.post('gestion_droit.php',
	{
		matricule_droit:matricule_droit,
		eval_droit:eval_droit,
		admin_droit:admin_droit,
		action:'insertion'
	},function(_data){
		if(parseInt(_data) == 1)
		{
			alert('Insertion effectu\351e !');
			rafraichirListe();
		}
		else
		{
			alert('Erreur lors de l\'insertion !');
		}
	});
}

function supprimer_droit(matricule_droit)
{
	if(confirm('Voulez-vous vraiment supprimer le matricule '+matricule_droit+' de la liste ?'))
	{
		$.post('gestion_droit.php',
		{
			matricule_droit:matricule_droit,
			action:'suppression'
		},function(_data){
			if(parseInt(_data) == 1){
				alert('Suppression effectu\351e !');
				rafraichirListe();
			}
			else
			{
				alert('Erreur de suppression !');
			}
			
		});
	}
}

function modification_droit(matricule_droit)
{
	var eval_droit = $('#evaluation_droit_'+matricule_droit).prop('checked');
	var admin_droit = $('#administration_droit_'+matricule_droit).prop('checked');
	//alert(matricule_droit+'***'+eval_droit+'***'+admin_droit);
	if(eval_droit == true)
	{
		eval_droit = 1;
	}
	else
	{
		eval_droit = 0;
	}
	if(admin_droit == true)
	{
		admin_droit = 1;
	}
	else
	{
		admin_droit = 0;
	}
	$.post('gestion_droit.php',
	{
		matricule_droit:matricule_droit,
		eval_droit:eval_droit,
		admin_droit:admin_droit,
		action:'modification'
	},function(_data){
		if(parseInt(_data) == 1){
			alert('Modification effectu\351e !');
		}
		else
		{
			alert('Erreur de modification !');
		}
	});
}

function rafraichirListe()
{
	$.post('gestion_droit.php',
	{
		action:'rafraichir'
	},function(data){
		var table = data.split('#*#*#');
		$('#id_div_contenu_droit').html(table[0]);
		$('#matricule_droit').html(table[1]);
		$('#id_table_droit').tablesorter();
		$('#matricule_droit').val(0);
		$('#evaluation_droit').attr('checked',false);
		$('#administration_droit').attr('checked',false);
	});
}
</script>
</head>
<style>
	#id_div_droit {
		display: block;
		margin-bottom: 30px;
	}
	
	#id_div_contenu_droit {
		display: block;
	    height: 500px;
	    overflow-y: auto;
	    width: 700px;
	    border: 1px solid #000000;
	}
	
	#id_table_droit,#id_liste_ajouter {
		border-collapse: collapse;
	}
	#id_table_droit thead tr th, #id_table_droit tbody tr td {
		border:1px solid #000000;
	}
	#id_table_droit thead tr th,#id_liste_ajouter tr th {
		color: #446f7b;
		font-family: Verdana;
		font-size : 12px;
		background: #a0c1c9;
		height: 25px;
	}
	#id_liste_ajouter tr th,#id_liste_ajouter tr td {
		border: 1px solid #000000;
		text-align:center;
		padding: 0 10px 0 10px;
	}
	#matricule_droit {
		height: 20px;
	}
</style>
<body>
<?php
include("/var/www.cache/dgconn.inc");
include('gestion_droit.php');


$zHtml = '<div id="id_div_droit">';
$zHtml .= '<table id="id_liste_ajouter">
	<tr>
	<th>Matricule</th>
	<th>Evaluation</th>
	<th>Admin</th>
	</tr>
	
	<tr>
	<td id="id_td_matricule_droit"><select id="matricule_droit">';
$zHtml .= setListePersDroit();
$zHtml .= '</select></td>
		<td><input type="checkbox" class="eval_droit" name="evaluation_droit" id="evaluation_droit" /></td>
		<td><input type="checkbox" class="admin_droit" name="administration_droit" id="administration_droit" /></td>
		<td style="border:0 none;"><input type="button" value="Ins&eacute;rer" onclick="inserer_droit();" /></td>
	</tr>
</table>';
$zHtml .= '</div>';

$zHtml .= '<div id="id_div_contenu_droit">';

$zHtml .= setTableauListeContenu();

$zHtml .= '</div>';
echo $zHtml;


?>
</body>
</html>