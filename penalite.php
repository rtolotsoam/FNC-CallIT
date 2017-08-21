<html>
<head>
<title>Gestion des pénalités</title>

<!--script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script-->
<script type="text/javascript" src="js/script_penalite.js"></script>

<link rel="stylesheet" type="text/css" href="css/style_penalite.css"></link>
</head>
<body>
<?php
include('function_penalite.php');
$id_projet          = $_REQUEST['id_projet'];
$id_client          = $_REQUEST['id_client'];
$id_application     = $_REQUEST['id_application'];
$id_type_traitement = $_REQUEST['id_type_traitement'];

if(empty($id_projet)) $id_projet = 53;
if(empty($id_client)) $id_client = 602;
if(empty($id_application)) $id_application = 927;

?>
<div id="id_div_contenu_total">
	<div id="id_div_gauche">
	<table id="table_liste_classement" border="1">
	<tr style="border-bottom: 1px solid white;"><th>Liste des classements</th></tr>
	<?php
	$result_class = getAllClassementForPenalite();
	$section = '';
	while ($res_class = pg_fetch_array($result_class))
	{
		if($section != $res_class['section'])
		{
			$section = $res_class['section'];
			echo '<tr><th>'.$section.'</th></tr>';
		}
		echo '<tr><td onclick="setTableauPenalite('.$res_class['id_classement'].');"><span>'.$res_class['libelle_classement'].'</span></td></tr>';
	}
	?>
	</table>
	</div>

	<div id="id_div_droite">
		<div id="id_div_penalite">
		<table>
		<tr>
		 	 <th>Nom du client : </th>
		 	 <td>
			 	 <input type="hidden" id="id_projet_penalite" value="<?php echo $id_projet; ?>" />
			 	 <select id="id_nom_client_penalite" style="width:350px;font-size:11px;font-family:verdana;" onchange="filtreDonneesPenalite('client');">
			 	 <?php
					echo '<option value="0">-- Choix --</option>';
					$result_client = fetchAllClientForPenalite('client');
					$tab_client = array();
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
							echo '<option value="'.$res_client['id_client'].'" '.$selected.'>'.$res_client['nom_client'].'</option>';
							array_push($tab_client,$res_client['id_client']);
						}
					}
				 ?>
			 	 </select>
		 	 </td>
		 </tr>
		 
		 <tr>
		 	 <th>Prestation : </th>
		 	 <td>
			 	 <select id="id_prestation_penalite" style="width:350px;font-size:11px;font-family:verdana;" onchange="filtreDonneesPenalite('code');">
			 	 <?php
					echo '<option value="0">-- Choix --</option>';
					$result_presta = fetchAllClientForPenalite('application');
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
						echo '<option value="'.$res_presta['id_application'].'" '.$selected.'>'.$res_presta['code'].' - '.$res_presta['nom_application'].'</option>';
					}
				 ?>
			 	 </select>
		 	 </td>
		 </tr>
		 
		 <tr>
		 	 <th>Type de traitement : </th>
		 	 <td>
			 	 <select id="id_type_traitement_penalite" style="width:350px;font-size:11px;font-family:verdana;" onchange="filtreDonneesPenalite('typetraitement');">
			 	 <?php
					echo '<option value="0">-- Choix --</option>';
					$result_type = fetchAllTypeTraitement();
					while ($res_type = pg_fetch_array($result_type))
					{
						if($res_type['id_type_traitement'] == $id_type_traitement)
						{
							$selected = 'selected="selected"';
						}
						else 
						{
							$selected = '';
						}
						echo '<option value="'.$res_type['id_type_traitement'].'" '.$selected.'>'.$res_type['libelle_type_traitement'].'</option>';
					}
				 ?>
			 	 </select>
		 	 </td>
		 </tr>
		 
		 </table>
		 </div>
		 
		 <div id="id_div_contenu_penalite">
		 	
		 </div>
		 
		 <div id="id_div_suppr_penalite">
		 	<?php
		 	$result_prj = fetchAllProjetPenaliteByProjet($id_projet,$id_type_traitement);
		 	echo $result_prj;
		 	?>
		 </div>
		 
	</div>
</div>
</body>
</html>
