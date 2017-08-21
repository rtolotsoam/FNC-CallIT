<html>
<head>
<title>Gestion du classement des questionnaires</title>

<!--script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script-->
<script type="text/javascript" src="js/jquery.smartTab.js"></script>

<link href="css/smart_tab.css" rel="stylesheet" type="text/css"></link>
<link rel="stylesheet" type="text/css" href="css/style_classement.css"></link>
<!--<link rel="stylesheet" type="text/css" href="css/tablesorter.css"></link>-->
<?php
include('function_classement.php');
?>
<script type="text/javascript">
    $(document).ready(function(){
	     $('#tabs').smartTab({selected: 0,autoProgress: false,stopOnFocus:false,autoHeight:false,transitionEffect:'hSlide'});  
	     
	     $("#id_tablesorter tbody").sortable({
	
				cursor: 'move',
				delay: 180,
	            update: function()
				{
					var rowsOrder = $(this).sortable("serialize");
	                      console.log(rowsOrder);
				   $.post("function_classement.php", 
				   { 
				   		action:'change_order_classement', 
				   		rows_order:rowsOrder 
				   });
				}
		 }).enableSelection();
    });
    
    function updateClassement(id)
    {
    	$("#id_btn_enregistrer").val('Modifier');
    	$.post("function_classement.php",
	     {
	     	action : 'edition',
	        id_classement : id
	     },
	     function(data){
	     	var reponse = data.split('||');
	     	$("#id_classement").val(reponse[0]);
	    	$("#id_section").val(reponse[1]);
	    	$("#id_update").val(reponse[2]);
	     });
    }
    
    function deleteClassement(id)
    {
    	if(confirm('Cette action entraîne la suppression de tous les pondérations affectées à ce classement. \n Voulez vous vraiment supprimer ?'))
    	{
	    	$.post("function_classement.php",
		     {
		        action : 'suppression',
		        id_classement : id
		     },
		     function(data){
		     	var reponse = data.split('|||');
		     	alert(reponse[0]);
		     	$("#id_div_tablesorter").html(reponse[1]);
		     	$("#id_tablesorter tbody").sortable({
					cursor: 'move',
					delay: 180,
		            update: function()
					{
						var rowsOrder = $(this).sortable("serialize");
		                      console.log(rowsOrder);
					   $.post("function_classement.php", 
					   { 
					   		action:'change_order_classement', 
					   		rows_order:rowsOrder 
					   });
					}
			 	}).enableSelection();
			 	setTableauClassPond();
		     });
    	}
    }
    
    function setNewClassement()
    {
    	$("#id_btn_enregistrer").val('Enregistrer');
    	$("#id_classement").val('');
    	$("#id_section").val('');
    	$("#id_update").val('');
    	$("#id_classement").css('border','1px solid #A5ACB2');
    	$("#id_section").css('border','1px solid #A5ACB2');
    }
    
    function saveClassement()
    {
    	var nom_classement = $("#id_classement").val();
    	var nom_section = $("#id_section").val();

	    if( nom_classement == '' ){
		   $("#id_classement").css('border','2px solid red');
		   return false;
		}
		
		if( nom_section == '' ){
		   $("#id_section").css('border','2px solid red');
		   return false;
		}
		
    	var id_classement = $("#id_update").val();
    	$.post("function_classement.php",
	     {
	        nom_classement : nom_classement,
	        nom_section : nom_section,
	        id_classement : id_classement
	     },
	     function(data){
	     	var reponse = data.split('|||');
	     	alert(reponse[0]);
	     	$("#id_div_tablesorter").html(reponse[1]);
    	
	     	$("#id_tablesorter tbody").sortable({
				cursor: 'move',
				delay: 180,
	            update: function()
				{
					var rowsOrder = $(this).sortable("serialize");
	                      console.log(rowsOrder);
				   $.post("function_classement.php", 
				   { 
				   		action:'change_order_classement', 
				   		rows_order:rowsOrder 
				   });
				}
		 	}).enableSelection();
		 	
		 	setNewClassement();
		 	setTableauClassPond();
	     }
     );
    }
    
    function filtreDonnees1(filtre)
	{
		var id_client = $('#id_nom_client_class').val();
		var id_application = $('#id_prestation_class').val();
		var id_projet = $('#id_projet_class').val();
		var champ_class = filtre;
		$.post("function_classement.php",
		{
			id_client_class: id_client,
			id_application_class: id_application,
			id_projet_class: id_projet,
			champ_class: champ_class
		},
		function(data) {
			if(champ_class == 'client')
			{
				$('#id_prestation_class').html(data);
				setTableauClassPond();
			}
			else if(champ_class == 'code')
			{
				var _data = data.split('||');
				$('#id_nom_client_class').val(_data[0]);
				$('#id_projet_class').val(_data[1]);
				
				setTableauClassPond();
			}
		});
	}
	
	function setTableauClassPond()
	{
		var id_client = $('#id_nom_client_class').val();
		var id_application = $('#id_prestation_class').val();
		var id_projet = $('#id_projet_class').val();
		
		$.post("function_classement.php",
		{
			id_client_class: id_client,
			id_application_class: id_application,
			id_projet_class: id_projet,
			setTableau: 1
		},
		function(data) {
				
			$("#id_div2_tablesorter").html(data);

		});
	}
	
	function save_ponderation()
	{
		var id_client = $('#id_nom_client_class').val();
		var id_application = $('#id_prestation_class').val();
		var id_projet = $('#id_projet_class').val();
		
		var section_ = 0;
		var ponderation_ = 0;
		var str_sct = '';
		$(".class_section").each(function() {
			var id_section_ = $(this).attr('id');
			var tab = id_section_.split('_');
			section_ = tab[1];
			ponderation_ = $(this).val();
			if(ponderation_ == '')
			{
				ponderation_ = 0;
			}
			str_sct += '||'+section_+'_'+ponderation_; 
		});
		
		var str = '';
		$(".class_input").each(function() {
			var id_classement = $(this).attr('id');
			var tab = id_classement.split('_');
			id_classement = tab[1];
			var section = tab[2];
			var ponderation = $(this).val();
			if(ponderation == '')
			{
				ponderation = 0;
			}
			str += '||'+id_classement+'_'+ponderation+'_'+section;
		});
		$.post("function_classement.php",
		{
			data : str,
			data_sct : str_sct,
			id_projet : id_projet,
			id_client : id_client,
			id_application : id_application,
			action: 'save_ponderation_classement'
		},
		function(data) {
			alert(data);
			setTableauClassPond();
		});
	}
	
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
     }
</script>
</head>
<body>
<?php
if(isset($_REQUEST['id_projet']) && isset($_REQUEST['id_client']) && isset($_REQUEST['id_application']))
{
	$id_projet = $_REQUEST['id_projet'];
	$id_client = $_REQUEST['id_client'];
	$id_application = $_REQUEST['id_application'];
}
/*
$id_projet = 51;
$id_client = 599;
$id_application = 408;
*/
?>
<div style="height:auto;overflow:auto;margin:auto;"> 
<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top">
<!-- Tab 1 -->
   <div id="tabs">
     <ul>
      <li><a href="#tabs-1" style="width:200px;">Gestion des classements<br />
               
            </a></li>
      <li><a href="#tabs-2" style="width:200px;">Ponderation par campagne<br />
                
            </a></li>
     </ul>
   
     <div id="tabs-1" class="categorieClass" style="display: block;height: 94%;left: 0;margin: 15px auto;overflow: auto;width: 730px;">
        <table style="padding-left:15px;font-family:verdana;font-size:11px;font-weight:bold">
        <tr>
        <td>Nom du classement : </td>
        <td>Section : </td>
        </tr>
        <tr>
        <td><input type="hidden" id="id_update"/><input type="text" id="id_classement" style="width:350px;font-size:11px;font-family:verdana;" /></td>
        <td><select id="id_section" style="font-size:11px;font-family:verdana;">
        <option value="">-- Choix --</option> 
        <option value="FOND">FOND</option> 
        <option value="FORME">FORME</option> 
        </select></td>
        <td><input type="button" value="Enregistrer" id="id_btn_enregistrer" class="btn_enreg" onclick="saveClassement();" /></td>
        <td><input type="button" value="Nouveau" id="id_btn_nouveau" class="btn_enreg" onclick="setNewClassement();" /></td>
        </tr>
        </table>
		
        <div id="id_div_tablesorter">
	        <?php
	 		echo setTableauClassement();
	 		?>
        </div>
     </div>
     
     <!--------------------------------------------------------------------------------------------------->
     <!--------------------------------------------------------------------------------------------------->
     <!--------------------------------------------------------------------------------------------------->
     
     <div id="tabs-2" class="categorieClass" style="display: block;height: 94%;left: 0;margin: 15px auto;overflow: auto;width: 730px;">
 	 <table class="class_table_div2">
 	 <tr>
 	 <th>Nom du client : </th>
 	 <td>
 	 <input type="hidden" id="id_projet_class" value="<?php echo $id_projet; ?>" />
 	 <select id="id_nom_client_class" style="width:350px;font-size:11px;font-family:verdana;" onchange="filtreDonnees1('client');">
 	 <?php
		echo '<option value="0">-- Choix --</option>';
		$result_client = fetchAllProject1('client');
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
 	 </select></td>
 	 </tr>
 	 <tr>
 	 <th>Prestation : </th>
 	 <td><select id="id_prestation_class" style="width:350px;font-size:11px;font-family:verdana;" onchange="filtreDonnees1('code');">
 	 <?php
		echo '<option value="0">-- Choix --</option>';
		$result_presta = fetchAllProject1('application');
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
 	 </select></td>
 	 </tr>
 	 </table>
 	 
 	 <div id="id_div2_tablesorter">
 	 <?php
 	 	 echo getClassPondByProjet($id_projet,$id_client,$id_application)
 	 ?>
 	 </div>
 	 
 	 <div style="width:100px;display:block;position:relative;margin:10px 0 0 84%">
 	 <input type="button" id="id_save_class_pond" value="Enregistrer" class="btn_enreg" onclick="save_ponderation();" />
 	 </div>
 	 
     </div>    
  
   </div>   

   </div>  
</td>
</tr>
</table>
</div>

</body>
</html>
