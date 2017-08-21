
<!DOCTYPE   html>
<html>
<head><title>Grille</title>
<!--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="js/jquery-ui-sliderAccess.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>


<link rel="stylesheet" media="all" type="text/css" href="css/jquery-ui.css" />
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="./js/jquery.thickbox.js"></script>

<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"></link>
<link rel="stylesheet" type="text/css" href="css/style.css"></link>
<link rel="stylesheet" href="./css/tablesorter.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.core.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.theme.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.datepicker.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/thickbox.css" type="text/css" media="screen" />-->
<link rel="stylesheet" type="text/css" href="style.css"></link>

<?php
include('function_grille.php')
?>

</head>
<style>

/*
.button {
    border-radius: 5px;
    border: 1px solid #B2C6CD;
    /*margin: 3px;*/
    /*color: #FFFFFF !important;*/
    /*cursor: pointer;
    display: inline-block;
    font-weight: bold;
    overflow: visible;
    position: relative;
    text-decoration: none !important;
    width: auto;
    height: 25px;
}

.button:hover {
	border-radius: 5px;
    border: 1px;
    margin: 3px;
    background-color: #E6EEEE;
    cursor: pointer;
}
*/
</style>

<script type='text/javascript'>
$('document').ready(function(){
$('#table_body').tablesorter();
});

</script>
<body>
<?php
//id
 $id_projet      = $_REQUEST['id_projet'];
 $id_client      = $_REQUEST['id_client'];
 $id_application = $_REQUEST['id_application'];
//nom
 $nom_projet      = $_REQUEST['nomProjet'];
 $nom_client      = $_REQUEST['nomClient'];
 $nom_application = $_REQUEST['nomApplication'];
 
 $nom_projet      = str_replace(',',' ',$nom_projet);
 $nom_client      = str_replace(',',' ',$nom_client);
 $nom_application = str_replace(',',' ',$nom_application);
 //

/**$id_projet = 51;
$id_client = 599;
$id_application = 408;*/
?>

<div id="main">
<div id="contentbg">

<div class='acc_container'>
	<div class='block'>	
	    <!--------- Titre ----------------->
		<table class="table_title" border="0">
		    <!--<tr>
		     <th style="width:30%">Projet:</th>
		     <td>  <?php echo $nom_projet;   ?> </td>
		    </tr>-->
		    <tr>
		     <th style="width:30%">Client:</th>
		     <td>  <?php echo $nom_client;   ?> </td>
		    </tr>
		    <tr>
		     <th style="width:30%">Prestation:</th>
		     <td>  <?php echo $nom_application;   ?> </td>
		    </tr>
		</table>

		<!--------- FIN Titre ----------------->
		 <input type='hidden'  id='nom_projet'  value="<?php  echo $nom_projet; ?>" />
		 <input type='hidden'  id='nom_client'  value="<?php echo $nom_client;  ?>" />
		 <input type='hidden'  id='nom_application'  value="<?php echo $nom_application;  ?>" />
		<div style="border:1px solid #fff;margin-bottom:30px;" >
		<a class='icone' id="id_ajout_quest"  onClick='admin_grille(<?php echo $id_projet ?>,<?php echo $id_client ?>,<?php echo $id_application ?>,0);' ><img src="images/Wizard_48x48.png" alt="Ajouter" title="Ajout questionnaires" width="25px" height="25px" /><span style='font-size:12px;' class='libelle_ajout_questionnaire'>Ajout questionnaires</span></a>
		<a class='icone' id="id_ajout_note"  onClick='admin_note(<?php echo $id_projet ?>,<?php echo $id_client ?>,<?php echo $id_application ?>);'><img src="images/img_note.jpg" alt="Ajouter" title="Ajout questionnaires" width="25px" height="25px" /><span style='font-size:12px;' class='libelle_ajout_questionnaire'>Gestion des notes</span></a>
		</div>
		
		<fieldset style="border:0px">
		
		<!--------- Liste Catégorie / Items ----------------->
		<div>
		<table class="tablesorter" id="table_body" style="text-align:center">
		
		  <thead>
		    <tr>
		     <th style="width:55%">Type</th>
		     <!--th style="width:15%">Date dernier entretien avec le client</th-->
		     <th style="width:15%">Date dernière modification</th>
		     <th style="width:15%">Action</th>
		    </tr>
		  </thead>
		
		  <body>
		  <?php
			  $result = getTypeByProjetClient($id_projet,$id_client,$id_application);
			  $nb = pg_num_rows($result);
			  $i = 1;
			  while($res = @pg_fetch_assoc($result))
			  {
			  	  //for($i=1;$i<=3;$i++)
			  	  //{
			  	    $id_type = $res['id_type_traitement'];
			  	  	if($id_type == 1) {$type = 'Appels entrants'; $select = 'modif_appel_entrant';}
			  	  	elseif($id_type == 2) {$type = 'Appels sortants'; $select = 'modif_appel_sortant';}
			  	  	elseif($id_type == 3) {$type = 'Traitements de mails'; $select = 'modif_mail';}
			  	  	elseif($id_type == 4) {$type = 'Traitements de tchats'; $select = 'modif_tchat';}
			  	  	
				      echo '<tr>';
				      echo '<td style="text-align:center">'.$type.'</td>';
				      
					  $query = pg_query($conn,"select ".$select." from cc_sr_projet WHERE id_application = ".$id_application." and id_projet = ".$id_projet." and id_client = ".$id_client) or die (pg_last_error($conn));
					  $res = pg_fetch_array($query);
					  if($res[0] == '') {
					  	  $date_appel = '';
					  }
					  else {
					  	  $date_appel = date_create($res[0]);
					  	  $date_appel = date_format($date_appel,"d/m/Y");
					  }
					  
					  echo '<td style="text-align:center">'.$date_appel.'</td>';
					  echo '<td style="text-align:center">';
					  echo '<img src="images/supprimer.png" alt="Supprimer" title="Supprimer les appels" id="id_suppr_appel_'.$id_type.'" width="15px" height="15px" />&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="admin_grille('.$id_projet.','.$id_client.','.$id_application.','.$id_type.')" href="#"><img src="images/modifier.png" alt="Modifier" title="Modifier les appels" id="id_modif_appel_'.$id_type.'" width="15px" height="15px" /></a>'; 
					  /******* Lien pour grille de notation *********************/
					  /*&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="openGrille('.$id_type.')" href="#"><img src="images/idea.jpg" alt="Noter" title="Attribution note" id="id_note_appel_'.$id_type.'" width="15px" height="15px" /></a>*/
					  echo '</td>';
					  echo '</tr>';
			  	  	
			  	  //}
			  	  $i++;
			  }
		  ?>
		</body>
		</table>
		</div>
		<!--------- Fin Liste ----------------->
		</fieldset>
	</div>
</div>

</div>
</div>

</body>
</html>

<?php



?>