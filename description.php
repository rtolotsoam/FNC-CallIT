<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Gestion des notes</title>
<!--<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>

<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"></link>-->
<link rel="stylesheet" type="text/css" href="css/style_description.css"></link>

</head>
<script>

function setDescription(id_grille_application)
{
	$.post("function_description.php",
	     {
	        data_description_grille : id_grille_application
	     },
	     function(_data){
	     	$('#id_description').html(_data);
	     }
	);
}

function ajout_desc(id_grille_application)
{
	
    var note = $("#id_note").val();
	var test = !isNaN(parseFloat(note)) && isFinite(note);
	
	if (test == false){
		var test1 = !isNaN(note);
		if (test1 == false) return false;
	}
	
	if( note=='' ){
	   $("#id_note").css('border','1px solid red');
	   return false;
	}
	var donnees = $('#id_form_desc').serialize();
	$.post("function_description.php",
	     {
	        data_ajout : decodeURIComponent(donnees),
	        id_grille_app: id_grille_application
	     },
	     function(_data){
	     	$('#id_description').html(_data);
	     }
	);
}

function nouveau_desc()
{
	$("#id_ajouter_grille").val('Ajouter');
	$("#id_grille_description").val('');
	$("#id_note").val('');
	$("#id_desc").val('');
}

function setUpdate(id_grille_description)
{
	$("#id_ajouter_grille").val('Modifier');
	$.post("function_description.php",
	     {
	        id_update_desc: id_grille_description
	     },
	     function(_data){
	     	/*$res['id_grille_description']."|||".$res['id_grille_application']."|||".$res['note']."|||".$res['libelle_description']*/
	     	var data = _data.split('|||');
	     	$("#id_grille_description").val(id_grille_description);
	     	$("#id_note").val(parseFloat(data[2]));
	     	$("#id_desc").val(data[3]);
	     }
	);
}

function setDelete(id_grille_description,id_grille_application)
{
	if(confirm('Voulez-vous vraiment supprimer la description?'))
	{
		$.post("function_description.php",
	    {
	        id_delete_desc: id_grille_description,
	        id_recup_desc: id_grille_application
	    },
	    function(_data){
	     	$('#id_description').html(_data);
	    }
		);
	}
}


function isNumber(evt) { 
    evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	
    if ((charCode > 31 && charCode!=46 ) && (charCode < 48 || charCode > 57) ) {
        return false;
    }
    return true;
	
	
}
</script>

<body>
<?php
include('function_description.php');
$id_projet = $_REQUEST['id_projet'];
$id_client = $_REQUEST['id_client'];
$id_application = $_REQUEST['id_application'];

/*$id_application = 408; 
$id_projet = 51;
$id_client = 599;
*/
$nom_projet = $_REQUEST['nom_projet'] ? $_REQUEST['nom_projet'] : 'Nom projet';
$nom_client = $_REQUEST['nom_client'] ? $_REQUEST['nom_client'] : 'Nom client';
$nom_application = $_REQUEST['nom_application'] ? $_REQUEST['nom_application'] : 'Nom application';
$nom_projet = trim(str_replace(","," ",$nom_projet));
$nom_client = trim(str_replace(","," ",$nom_client));
$nom_application = trim($nom_application);

// $nom_projet = 'Nom du projet';
// $nom_client = 'Nom du client';
// $nom_application = 'Nom de l\'application';

?>
<div id="div_contenu">

<div id="id_menu_description" style="border:1px solid #000000">
<?php
$result = getCategorieItem($id_projet,$id_application,$id_client);
if(pg_num_rows($result) == 0)
{
	echo '<div style="margin:100% auto;display:block;width:75%;"><span style="color:red;font-weight:bold;font-family:verdana;font-size:13px;">Aucun critère pour ce projet</span></div>';
}
$cat_grille = 0;
$nouveau = 0;
$type_grille = 0;
$nouveau_type = 0;
echo '<table id="table_description" border="1">';
while($res1 = pg_fetch_array($result))
{
	if($res1['id_type_traitement'] != $type_grille)
	{
		$type_grille = $res1['id_type_traitement'];
		$nouveau_type = 1;
	}
	else 
	{
		$nouveau_type = 0;
	}
	if($res1['id_categorie_grille'] != $cat_grille)
	{
		$cat_grille = $res1['id_categorie_grille'];
		$nouveau = 1;
	}
	else 
	{
		$nouveau = 0;
	}
	
	if($nouveau_type == 1)
	{
		if($type_grille == 1)
		{
			$titre_type = 'Appels entrants';
		}
		else if($type_grille == 2)
		{
			$titre_type = 'Appels sortants';
		}
		else 
		{
			$titre_type = 'Traitement de mails';
		}
		echo '<tr>
		<th class="titre_type_traitement">'.$titre_type.'</th>
		</tr>';
	}
	
	if($nouveau == 1)
	{
		echo '<tr>
		<th class="titre_th">'.$res1['libelle_categorie_grille'].'</th>
		</tr>';
		echo '<tr>
		<td class="class_td" onclick="setDescription('.$res1['id_grille_application'].');"><span>'.$res1['libelle_grille'].'</span></td>
		</tr>';
	}
	else
	{
		echo '<tr>
		<td class="class_td" onclick="setDescription('.$res1['id_grille_application'].');"><span>'.$res1['libelle_grille'].'</span></td>
		</tr>';
	}
	
}
?>
</table>
</div>
<div id="contenu_desc">
	<div id="div_titre" style="border:1px solid #000000">
	<table id="ttable">
	<tr><td><p></p></td><td class="titre"><?php //echo $nom_projet; ?></td></tr>
	<tr><td>Nom Client :</td><td class="titre"><?php echo $nom_client; ?></td></tr>
	<tr><td>Prestation :</td><td class="titre"><?php echo $nom_application; ?></td></tr>
	</table>
	</div>
	
	<div id="id_description" style="border:1px solid #000000">
	
	</div>
</div>

</div>

</body>
</html>