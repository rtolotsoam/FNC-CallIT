<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Wizard</title>
<link href="css/smart_wizard.css" rel="stylesheet" type="text/css">
<link href="css/smart_tab.css" rel="stylesheet" type="text/css"></link>


<!--
<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
-->
<script type="text/javascript" src="js/jquery.smartWizard-2.0.js"></script>
<script type="text/javascript" src="js/jquery.smartTab.js"></script>
<!--
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery.thickbox.js"></script>

<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"></link>
<link rel="stylesheet" type="text/css" href="css/style.css"></link>
<link rel="stylesheet" href="./css/tablesorter.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.core.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.theme.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="style.css"></link>-->


<script type="text/javascript">
    $(document).ready(function(){
	
	//$('.flag_is').find('option:selected').css('background', 'red');
	      
    	// Smart Wizard
    	$('#tabs').smartTab({selected: 0,autoProgress: false,stopOnFocus:false,autoHeight:false,transitionEffect:'hSlide'}); 	
    	$('#tabs1').smartTab({selected: 0,autoProgress: false,stopOnFocus:false,autoHeight:false,transitionEffect:'hSlide'}); 
  		$('#wizard').smartWizard();
  		/*$('#tab1').smartTab("#tabs-2", {disabled: true});*/
  		/*$('#tabs').trigger ("goto_tab", 0); */

      /*function onFinishCallback(){
        $('#wizard').smartWizard('showMessage','Finish Clicked');
      } */  

 /**$.each($('select'), function(i,v) {

    theElement = $(v);
    theID = theElement.attr('id');
   
   

    theElement.css('background-color', 'green');
    theElement.css('color', 'white');


    $('#'+theID).find('option').css('background-color', 'white');
    $('#'+theID).find('option').css('color', 'black');

  

    $('#'+theID).find('option:selected').css('background-color', 'green');
    $('#'+theID).find('option:selected').css('color', 'white');
       });
	   
	   */
   
   /***********************/
   


 
   /***********************/
	});
	


function getValeur()
{
          
  


	var inputs        = $("input[name='checkbox']:checked");
	var inputs_elimin = $("input[name='input_elimin']:checked");
	      
	var id_type = $('#id_type').val();
	
	var id_projet = $('#id_projet').val();
	var id_application = $('#id_application').val();
	var id_client = $('#id_client').val();
	var str = '';
	var str_elimint = '';
	var k=0;
	inputs_elimin.each(function(){
	       if(k != 0) str_elimint += ',';
		   
		var id = $(this).attr('id').split('_');
        str_elimint += id[3];
        k++;   
	});
	
	var i = 0;
	inputs.each(function(){
		
		if(i != 0) str += ',';
		var id = $(this).attr('id').split('_');
        str += id[0];
        i = i+1; 
    });
    
	var list_elimin = $("input[name='check_elimin']:checked");
	var kk = 0;
	var str_elimin2 = '';

	list_elimin.each(function()
	{
	       if(kk != 0) str_elimin2 += ',';
		var id = $(this).attr('id').split('_');
        str_elimin2 += id[3];
        kk++;   
	});

	
	var list_ponderation = $("input.ponderation");
	 str_ponderation ='';
	 var x=0;
	
	list_ponderation.each(function()
	{	  
	     if(x != 0) str_ponderation += ','; 
		 var id = $(this).attr('id').split('_');
	
         str_ponderation += id[1]+'#'+$(this).val();		 
        x++;   
	});
      console.log( "par la: "+str_ponderation );
    $.post("ajax_script/ajax_grille.php",
	     {
	        data_grille : str,
	        id_type : id_type,
			str_elimin2:str_elimin2,
			str_ponderation:str_ponderation
	     },
	     function(_data){
		 
		  
	     	var list = _data.split('|');
	        $('#table_resume').html(list[0]);
	        
	        $('#E_cat_nb').html(list[1]);
	        $('#S_cat_nb').html(list[2]);
	        $('#T_cat_nb').html(list[3]);
	        var tot_cat = parseInt(list[1])+parseInt(list[2])+parseInt(list[3]);
	        $('#total_cat_nb').html(tot_cat);
	        
	        $('#E_quest_nb').html(list[4]);
	        $('#S_quest_nb').html(list[5]);
	        $('#T_quest_nb').html(list[6]);
	        var tot_quest = parseInt(list[4])+parseInt(list[5])+parseInt(list[6]);
	        $('#total_quest_nb').html(tot_quest);
	     }
     );
     // console.log("str:"+str+"elimin:"+str_elimint);
     if(str != ($('#flag_test').val())  || str_elimint != $('#flag_test_elimin').val()  || str =='')
     {	
	 
	
	     $.post("ajax_script/ajax_notation_1.php",
		     {
			    
		        data_grille : str,
		        id_type : id_type,
		        id_projet_notation : id_projet,
		        id_application_notation : id_application,
		        id_client_notation : id_client,
				data_eliminatoire:str_elimint
		     },
		     function(_data){
		     	//console.log(_data);
				
				
		     	var list = _data.split('|');
		        $('#table_notation').html(list[0]);
		        
		        $('#flag_test').val(str);
		        $('#flag_test_elimin').val(str_elimint);
		     	
		     }
	     );
     }
}

function setValeur()
{
	var inputs = $("input[name='checkbox']:checked");
	//var inputs_notation = $("input[name='note_grille']");
	var inputs_notation = $("select[name='note_grille'] option:selected");
	
	var str = '';
	var str1 = '';
	
	var i = 0;
	inputs.each(function(){
		
		if(i != 0) str += ',';
        str += $(this).attr('id');
        i = i+1; 
    });
    
    var j = 0;

    
    if(str == '') 
    {
    	str = 'empty';
    }
    
  
    
    var id_projet = $('#id_projet').val();
    var id_client = $('#id_client').val();
    var id_application = $('#id_application').val();
    var id_type = $('#id_type').val();
    //alert (id_type);
    var nomProjet = $('#nom_projet').val().split(" ");
    var nomClient = $('#nom_client').val().split(" ");
    var nomApplication = $('#nom_application').val().split(" ");
    
	var list_elimin = $("input[name='check_elimin']:checked");
	var jj = 0;
	var str_elimin3 = '';
	list_elimin.each(function()
	{
	       if(jj != 0) str_elimin3 += ',';
		var id = $(this).attr('id').split('_');
        str_elimin3 += id[3];
        jj++;   
	});
 
 
	var list_ponderation = $("input.ponderation");
	 str_ponderation3 ='';
	 var x=0;
	
	list_ponderation.each(function()
	{	  
	     if(x != 0) str_ponderation3 += ','; 
		 var id = $(this).attr('id').split('_');
	
         str_ponderation3 += id[1]+'#'+parseFloat($(this).val());		 
        x++;   
	});
      
	  
     var flag_is = $("select.flag_is");
	 var str_flag_is = '';
	 var y=0;
	 	flag_is.each(function()
	{	  
	     if(y != 0) str_flag_is += ','; 
		 var id = $(this).attr('id').split('_');
	     /*********************/
		 var z = $(this).val();
		 var str_ = '';
		   var counter = 0;
		    if (z !== null) {
		       $.each(z, function( index, value ) {
			   
			           if(value != 0){
					   if( counter != 0)   str_ += ';'; 
						   
						  str_ += value;
						  counter ++;
					   }
                          
               });
   
			   }else{
			     str_ = z;
			   }
	     /*********************/
         str_flag_is += id[2]+'#'+str_;		 
        y++;   
	});
	   
	   /*****************Repartition*********************/
	   
	  var repartition = $("select.repartition");
	  var str_repartition = '';
	  var count=0;
	 	repartition.each(function()
	{	  
	     if(count != 0) str_repartition += ','; 
		 var id_ = $(this).attr('id').split('_');
	     /*********************/
		 var z_ = $(this).val();
		 // var str_1 = '';
		   // var counter_ = 0;
		    // if (z_ !== null) {
		       // $.each(z_, function( index, value ) {
			   
			           // if(value != 0){
					   // if( counter_ != 0)   str_1 += '_'; 
						   
						  // str_1 += value;
						  // counter_ ++;
					   // }
                          
               // });
   
			   // }else{
			     // str_1 = z_;
			   // }
	   
         str_repartition += id_[1]+'#'+z_;		 
        count++;   
	});
	      console.log("parici:"+str_repartition);
	   /***************Fin repartition***********************/
    $.post("function_grille_2.php",
	     {
	        data_grille : str,
	        id_projet : id_projet,
	        id_client : id_client,
	        id_application : id_application,
	        id_type : id_type,
			str_elimin3:str_elimin3,
			str_ponderation3:str_ponderation3,
			str_flag_is:str_flag_is,
			str_repartition:str_repartition
	     },
	     function(_data){
	     	tb_show("Gerer les grille","admin_type.php?height=300&width=850&nomProjet="+nomProjet+"&nomClient="+nomClient+"&nomApplication="+nomApplication+"&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application);
	     	//$('#tab1').show();
	     	//alert ('insert');
	     }
	);
}

function setCheck(i)
{
	/*if($('#appel_'+i).prop( "checked" ))
	{*/
		var inputs = $("input[name='checkbox']");
		inputs.each(function(){
			var id = $(this).attr('id').split('_');
	        if (id[1] == i)
	        {
	        	//$(this).attr('checked','checked');
	        	var check = $('#appel_'+i).prop( "checked" );
	        	$(this).prop('checked',check);
	        }
	    });
	//}
	
	var inputs_ = $("input[name='checkbox_categorie']");
	inputs_.each(function(){
		var id = $(this).attr('id').split('_');
        if (id[1] == i)
        {
        	var check = $('#appel_'+i).prop( "checked" );
        	$(this).prop('checked',check);
        	
        }
    });
    $('#cat_appel_'+i).prop('checked',$('#appel_'+i).prop( "checked" ));
}

function setCheckCat(i)
{
	var inputs = $("input[name='checkbox_categorie']");
	inputs.each(function(){
		var id = $(this).attr('id').split('_');
        if (id[1] == i)
        {
        	var check = $('#cat_appel_'+i).prop( "checked" );
        	$(this).prop('checked',check);
        }
    });
    
    var inputs_ = $("input[name='checkbox']");
	inputs_.each(function(){
		var id = $(this).attr('id').split('_');
        if (id[1] == i)
        {
        	//$(this).attr('checked','checked');
        	var check = $('#cat_appel_'+i).prop( "checked" );
        	$(this).prop('checked',check);
        }
    });
    $('#appel_'+i).prop('checked',$('#cat_appel_'+i).prop( "checked" ));
}

function setCheckGrille(id_categorie_grille,i)
{
	var inputs = $("input[name='checkbox']");
	inputs.each(function(){
		var id = $(this).attr('id').split('_');
		if ((id[1] == i) && (id[2] == id_categorie_grille))
		{
			var check = $('#'+id_categorie_grille+'_'+i).prop( "checked" );
			$(this).prop('checked',check);
		}
	});
	
}

function setCheckCategorieGrille(id_grille,i,id_categorie_grille,nb)
{
	var inputs = $("input[name='checkbox']");
	var value = false;
	var a = 0;
	inputs.each(function(){
		var id = $(this).attr('id').split('_');
		if ((id[1] == i) && (id[2] == id_categorie_grille))
		{
			//var check = $('#'+id_categorie_grille+'_'+i).prop( "checked" );
			//$(this).prop('checked',check);
			/*var check = $('#'+id_grille+'_'+i+'_'+id_categorie_grille).prop( "checked" );
			if(check)
			{
				value = check;
			}
			else
			{
				value = check;
				return false;
			}*/
			var value = $(this).prop("checked");
			if(value) a = a+1;
			
			
		}
	});
	//alert (a);
	if(a == nb)
	{
		$('#'+id_categorie_grille+'_'+i).prop("checked",true);
	}
	else
	{
		$('#'+id_categorie_grille+'_'+i).prop("checked",false);
	}
}

</script>
</head>



<?php
include('function_grille_2.php');
$id_projet = $_REQUEST['id_projet'];
$id_client = $_REQUEST['id_client'];
$id_application = $_REQUEST['id_application'];

$nom_projet = $_REQUEST['nom_projet'] ? $_REQUEST['nom_projet'] : 'Nom projet';
$nom_client = $_REQUEST['nom_client'] ? $_REQUEST['nom_client'] : 'Nom client';
$nom_application = $_REQUEST['nom_application'] ? $_REQUEST['nom_application'] : 'Nom application';
$nom_projet = trim(str_replace(","," ",$nom_projet));
$nom_client = trim(str_replace(","," ",$nom_client));
$nom_application = trim($nom_application);
/*
$id_projet = 51;
$id_client = 599;
$id_application = 408;
*/
$id_type = $_REQUEST['id_type'];
//$id_type = 1;
?>

<body>

<input type="hidden" id="flag_test" />
<input type="hidden" id="flag_test_elimin" />

<table align="center" border="0" cellpadding="0" cellspacing="0">

<tr><td> 

<!-- Smart Wizard -->

  		<div id="wizard" class="swMain">
  			<ul>
  				<li><a href="#step-1">
                <span class="stepDesc">
                   Etape 1
                </span>
            	</a></li>

  				<li><a href="#step-2">
                <span class="stepDesc">
                   Etape 2
                </span>
            	</a></li>
            	
            	<li><a href="#step-3">
                <span class="stepDesc">
                   Etape 3
                </span>
            	</a></li>
  			</ul>
  			
  			<!---------------- Stokage éléments sélectionnés ------------------>
  			
  			<!----------------------------------------------------->
		
<!----------------------------------------------------->
<!------------------ STEP 1 --------------------------->
  		<div id="step-1">	

            <h2 class="StepTitle">Sélection des questions</h2>
           
	        <!--------- Titre ----------------->
			<table style="width:60%;text-align:left;">
			
			  <tbody>
			    <tr>
			     <th style="width:20%">Projet :</th>
			     <td><input type="hidden" id="id_projet" value="<?php echo $id_projet; ?>" /><input type="hidden" id="nom_projet" value="<?php echo $nom_projet; ?>" /><?php echo $nom_projet; ?></td>
			    </tr>
			    <tr>
			     <th style="width:20%">Client :</th>
			     <td><input type="hidden" id="id_client" value="<?php echo $id_client; ?>" /><input type="hidden" id="nom_client" value="<?php echo $nom_client; ?>" /><?php echo $nom_client; ?></td>
			    </tr>
			    <tr>
			     <th style="width:20%">Prestation :</th>
			     <td><input type="hidden" id="id_application" value="<?php echo $id_application; ?>" /><input type="hidden" id="nom_application" value="<?php echo $nom_application; ?>" /><?php echo $nom_application; ?></td>
			    </tr>
			  </tbody>
			  
			</table>
			</br>
			<!--------- FIN Titre -----------------> 
			<!--------- Liste Catégorie / Items ----------------->
			<div style="height:521px;overflow:auto;margin:auto;" class="categorieCl">
			<table align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
			  <td valign="top">

			  		<div id="tabs">
			  		<?php
			  		    echo '<ul>';
			  			if($id_type == 0)
			  			{
			  				$nombre_type = countType();
			  				$deb = 1;
			  				$nom_type = '';
			  				echo '<li><a href="#tabs-1">Appels entrants<br /></a></li>
				  				<li><a href="#tabs-2">Appels sortants<br /></a></li>
				  				<li><a href="#tabs-3">Traitement des Mails<br /></a></li>
				  				<li><a href="#tabs-4">Traitement des Tchats<br /></a></li>';
			  			}
			  			else 
			  			{
			  				$nombre_type = $id_type;
			  				$deb = $id_type;
			  				if($id_type == 1) $nom_type = 'Appels entrants';
				  			elseif($id_type == 2) $nom_type = 'Appels sortants';
				  			elseif($id_type == 3) $nom_type = 'Traitement des Mails';
				  			elseif($id_type == 4) $nom_type = 'Traitement des Tchats';
				  			echo '<li class="selector"><a href="#tabs-1">'.$nom_type.'<br /></a></li>';
			  			}
			  			echo '</ul>';
			  			
			  			/*echo '<ul>';
			  			if($id_type == 0)
			  			{
				  			echo '<li><a href="#tabs-1">Appels entrants<br /></a></li>
				  				<li><a href="#tabs-2">Appels sortants<br /></a></li>
				  				<li><a href="#tabs-3">Traitement des Mails<br /></a></li>';
			  			}
			  			else 
			  			{
			  				echo '<li><a href="#tabs-1">'.$nom_type.'<br /></a></li>';
			  			}
			  			echo '</ul>';*/
			  			$j = 1;
			  			for($a=$deb;$a<=$nombre_type;$a++)
			  			{
				            if($a == 1) {
				            	$E_resultat = countGrille($a,'id_type_traitement',$id_projet,$id_client,$id_application);
				            	$E_result = $E_resultat['nb_grille'];
					      	    $result11 = getCategorie(1,'id_type_traitement',$a);
					      	    $E_result_cat = @pg_num_rows($result11);
				            }
				      	    
				            elseif($a == 2) {
					      	    $S_resultat = countGrille($a,'id_type_traitement',$id_projet,$id_client,$id_application);
					      	    $S_result = $S_resultat['nb_grille'];
					      	    $result22 = getCategorie(1,'id_type_traitement',$a);
					      	    $S_result_cat = @pg_num_rows($result22);
				            }
				      	  
				            elseif($a == 3) {
					      	    $T_resultat = countGrille($a,'id_type_traitement',$id_projet,$id_client,$id_application);
					      	    $T_result = $T_resultat['nb_grille'];
					      	    $result33 = getCategorie(1,'id_type_traitement',$a);
					      	    $T_result_cat = @pg_num_rows($result33);
				            }
							
							elseif($a == 4) {
					      	    $Tc_resultat = countGrille($a,'id_type_traitement',$id_projet,$id_client,$id_application);
					      	    $Tc_result = $Tc_resultat['nb_grille'];
					      	    $result44 = getCategorie(1,'id_type_traitement',$a);
					      	    $Tc_result_cat = @pg_num_rows($result44);
				            }
						// Step 1
			  			echo '<div id="tabs-'.$j.'" class="categorie" style="overflow:auto;height:490px;margin-top:5px;width:100%;display:block;">';
						echo '<table>
						  <thead>
						    <tr style="text-align:center;">
						     <th style="width:30%">CATEGORIES</th>
						     <th style="width:7%" style="text-align:center;"><input type="checkbox" name="MainCatCheckbox" id="cat_appel_'.$a.'" onclick="setCheckCat('.$a.');" /></th>
						     <th style="width:50%">ITEMS</th>
						     <th style="width:7%" style="text-align:center;"><input type="checkbox" name="MainCheckbox" id="appel_'.$a.'" onclick="setCheck('.$a.');" /></th>
						    </tr>
						  </thead>';
						$result = getCategorie(1,'id_type_traitement',$a);
					  	$type_cat = $a;
						while($res = @pg_fetch_assoc($result))
				  		{  
						  $id_categorie = $res['id_categorie_grille'];
					      $result_item = getCategorieGrille($id_categorie,'id_categorie_grille',$id_projet,$id_client,$id_application);
					      $nombre_item = countGrille($id_categorie,'id_categorie_grille',$id_projet,$id_client,$id_application);
					      //$nb = @pg_num_rows($result_item);
					      $nb = $nombre_item['nb_grille'];
					      $nb_projet = $nombre_item['nb_projet'];
					      $i = 1;
					  	  
					      if($nb != 0)
					      {
						      echo '<tbody>';
						      
						      while ($res_qry = @pg_fetch_assoc($result_item))
						      {
							      echo '<tr>';
							      if ($i == 1) {
							      	 echo '<th rowspan="'.$nb.'" style="text-align:left" id="cg_'.$res_qry['id_categorie_grille'].'">'.$res['libelle_categorie_grille'].'</th>';
							      }
							      
							      if($res_qry['id_projet'] == $id_projet && $res_qry['id_client'] == $id_client && $res_qry['id_application'] == $id_application)
							      {
							      	  $checked = 'checked';
							      }
							      else 
							      {
							      	  $checked = '';
							      }
							      
							      if($res_qry['id_grille'] == '') 
							      {
							      	  $id_grille = $res_qry['id_categorie_grille'].'_0';
							      	  $checked = 'hidden';
							      	  $libelle_grille = '<span style="color:red;font-weight:bold;">Aucun item</span>';
							      	  $id_categorie_grille = $res_qry['id_categorie_grille'];
							      }
							      else 
							      {
							      	  $id_grille = $res_qry['id_grille'];
							      	  $libelle_grille = $res_qry['libelle_grille'];
							      	  $id_categorie_grille = $res_qry['id_categorie_grille'];
							      }
							      
							      if($nb == $nb_projet)
							      {
							      	  $checked_cat = 'checked';
							      }
							      else 
							      {
							      	  $checked_cat = '';
							      }
							      
							      if ($i == 1) {
							      	 echo '<th rowspan="'.$nb.'" style="text-align:center"><input type="checkbox" name="checkbox_categorie" id="'.$id_categorie_grille.'_'.$a.'" '.$checked_cat.' onclick="setCheckGrille('.$id_categorie_grille.','.$a.')" /></th>';
							      }
							      echo '
								     <td id="g_'.$id_grille.'">'.$libelle_grille.'</td>
								     <td style="text-align:center;"><input type="checkbox" name="checkbox" id="'.$id_grille.'_'.$a.'_'.$id_categorie_grille.'" '.$checked.' onclick="setCheckCategorieGrille('.$id_grille.','.$a.','.$id_categorie_grille.','.$nb.')" />';
							      echo '</td>';
								  echo '</tr>';
								  $i++;
								  //$type_cat = $res_qry['id_type_traitement'];
						      }
						      
						      echo '</tbody>';
						      
					      }
				  		}
						
						echo '</table>
			        	</div>';
						$j++;
			  			}
			        	?>
			  	</div>  	
			  </td>
			</tr>
			</table>  
			</div>
			<!--------- Fin Liste ----------------->

        </div>
        
<!------------------FIN  STEP 1 ----------------------->
<!----------------------------------------------------->
<!------------------ STEP 2 --------------------------->
		<div id="step-2">
		<h2 class="StepTitle">Choix de notation pour les questionnaires sélectionnés</h2>	
		<!--------- Titre ----------------->
			<table style="width:60%;text-align:left;">
			
			  <tbody>
			    <tr>
			     <th style="width:20%">Projet :</th>
			     <td><input type="hidden" id="id_projet" value="<?php echo $id_projet; ?>" /><input type="hidden" id="nom_projet" value="<?php echo $nom_projet; ?>" /><?php echo $nom_projet; ?></td>
			    </tr>
			    <tr>
			     <th style="width:20%">Client :</th>
			     <td><input type="hidden" id="id_client" value="<?php echo $id_client; ?>" /><input type="hidden" id="nom_client" value="<?php echo $nom_client; ?>" /><?php echo $nom_client; ?></td>
			    </tr>
			    <tr>
			     <th style="width:20%">Prestation :</th>
			     <td><input type="hidden" id="id_application" value="<?php echo $id_application; ?>" /><input type="hidden" id="nom_application" value="<?php echo $nom_application; ?>" /><?php echo $nom_application; ?></td>
			    </tr>
			  </tbody>
			  
			</table>
			</br>
		<!--------- FIN Titre -----------------> 
		<!--------- Liste Catégorie / Items ----------------->
			<!--<div style="height:581px;overflow:auto;margin:auto;" class="categorieCl">-->
			<div style="height:521px;overflow:auto;margin:auto;" id="table_notation" class="categorieCl">
<!--		<table align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
			  <td valign="top">

			  		<div id="tabs1">
			  		<?php
/*			  		    echo '<ul>';
			  			if($id_type == 0)
			  			{
			  				$nombre_type = countType();
			  				$deb = 1;
			  				$nom_type = '';
			  				echo '<li><a href="#tabsa-1">Appels entrants<br /></a></li>
				  				<li><a href="#tabsa-2">Appels sortants<br /></a></li>
				  				<li><a href="#tabsa-3">Traitement des Mails<br /></a></li>';
			  			}
			  			else 
			  			{
			  				$nombre_type = $id_type;
			  				$deb = $id_type;
			  				if($id_type == 1) $nom_type = 'Appels entrants';
				  			elseif($id_type == 2) $nom_type = 'Appels sortants';
				  			elseif($id_type == 3) $nom_type = 'Traitement des Mails';
				  			echo '<li class="selector"><a href="#tabsa-1">'.$nom_type.'<br /></a></li>';
			  			}
			  			echo '</ul>';
			  			
			  			/*echo '<ul>';
			  			if($id_type == 0)
			  			{
				  			echo '<li><a href="#tabs-1">Appels entrants<br /></a></li>
				  				<li><a href="#tabs-2">Appels sortants<br /></a></li>
				  				<li><a href="#tabs-3">Traitement des Mails<br /></a></li>';
			  			}
			  			else 
			  			{
			  				echo '<li><a href="#tabs-1">'.$nom_type.'<br /></a></li>';
			  			}
			  			echo '</ul>';*/
/*			  			$j = 1;
			  			for($a=$deb;$a<=$nombre_type;$a++)
			  			{
				            if($a == 1) {
				            	$E_resultat = countGrille($a,'id_type_traitement',$id_projet,$id_client,$id_application);
				            	$E_result = $E_resultat['nb_grille'];
					      	    $result11 = getCategorie(1,'id_type_traitement',$a);
					      	    $E_result_cat = @pg_num_rows($result11);
				            }
				      	    
				            elseif($a == 2) {
					      	    $S_resultat = countGrille($a,'id_type_traitement',$id_projet,$id_client,$id_application);
					      	    $S_result = $S_resultat['nb_grille'];
					      	    $result22 = getCategorie(1,'id_type_traitement',$a);
					      	    $S_result_cat = @pg_num_rows($result22);
				            }
				      	  
				            elseif($a == 3) {
					      	    $T_resultat = countGrille($a,'id_type_traitement',$id_projet,$id_client,$id_application);
					      	    $T_result = $T_resultat['nb_grille'];
					      	    $result33 = getCategorie(1,'id_type_traitement',$a);
					      	    $T_result_cat = @pg_num_rows($result33);
				            }
						// Step 1
			  			echo '<div id="tabsa-'.$j.'" class="categorie" style="overflow:auto;height:490px;margin-top:5px;width:100%;display:block;">';
						echo '<table>
						  <thead>
						    <tr style="text-align:center;">
						     <th style="width:28%">CATEGORIES</th>
						     <th style="width:50%">ITEMS</th>
						     <th style="width:22%" style="text-align:center;">Nombre choix de notation</th>
						    </tr>
						  </thead>';
						$result = getCategorie(1,'id_type_traitement',$a);
					  	$type_cat = $a;
						while($res = @pg_fetch_assoc($result))
				  		{ 
						  $id_categorie = $res['id_categorie_grille'];
					      $result_item = getCategorieGrille($id_categorie,'id_categorie_grille',$id_projet,$id_client,$id_application);
					      $nombre_item = countGrille($id_categorie,'id_categorie_grille',$id_projet,$id_client,$id_application);
					      //$nb = @pg_num_rows($result_item);
					      $nb = $nombre_item['nb_grille'];
					      $nb_projet = $nombre_item['nb_projet'];
					      $i = 1;
					  	  
					      if($nb != 0)
					      {
						      echo '<tbody>';
						      
						      while ($res_qry = @pg_fetch_assoc($result_item))
						      {
							      echo '<tr>';
							      if ($i == 1) {
							      	 echo '<th rowspan="'.$nb.'" style="text-align:left" id="cgrille_'.$res_qry['id_categorie_grille'].'">'.$res['libelle_categorie_grille'].'</th>';
							      }
							      
							      if($res_qry['id_projet'] == $id_projet && $res_qry['id_client'] == $id_client && $res_qry['id_application'] == $id_application)
							      {
							      	  $checked = 'checked';
							      }
							      else 
							      {
							      	  $checked = '';
							      }
							      
							      if($res_qry['id_grille'] == '') 
							      {
							      	  $id_grille = $res_qry['id_categorie_grille'].'_0';
							      	  $checked = 'hidden';
							      	  $libelle_grille = '<span style="color:red;font-weight:bold;">Aucun item</span>';
							      	  $id_categorie_grille = $res_qry['id_categorie_grille'];
							      }
							      else 
							      {
							      	  $id_grille = $res_qry['id_grille'];
							      	  $libelle_grille = $res_qry['libelle_grille'];
							      	  $id_categorie_grille = $res_qry['id_categorie_grille'];
							      }
							      
							      if($nb == $nb_projet)
							      {
							      	  $checked_cat = 'checked';
							      }
							      else 
							      {
							      	  $checked_cat = '';
							      }
							      
							      echo '
								     <td id="grille_'.$id_grille.'">'.$libelle_grille.'</td>
								     <td style="text-align:center;">';
							      echo '<select class="styled-select" id="id_note_grille_'.$id_grille.'" style="height:16px;width:100%;">
							      <option value="1">2 possibilités (0 / 1)</option>
							      <option value="2">3 possibilités (0 / 0,5 / 1)</option>
							      </select>';
							      echo '</td>';
								  echo '</tr>';
								  $i++;
								  //$type_cat = $res_qry['id_type_traitement'];
						      }
						      
						      echo '</tbody>';
						      
					      }
				  		}
						
						echo '</table>
			        	</div>';
						$j++;
			  			}
*/			        	?>
			  	</div>  	
			  </td>
			</tr>
			</table>  
-->			</div>
			<!--------- Fin Liste ----------------->

		</div>
<!------------------FIN  STEP 2 ----------------------->
<!----------------------------------------------------->
<!------------------ STEP 3 --------------------------->

  		<div id="step-3">
  			

            <h2 class="StepTitle">Résumé de la grille</h2>	

            <div class="categorie">
            <table border="1" >
            <tr>
            	<th style="width:31%"><input type="hidden" value="<?php echo $id_type; ?>" id="id_type" />TYPE</th>
            	<th style="width:33%">CATEGORIES</th>
            	<th style="width:33%">QUESTIONNAIRES</th>
            </tr>
            <?php 
            if($id_type == 0 || $id_type == 1) 
            {
            	?>
            <tr>
            	<th style="text-align:center">Appels Entrantsqqqq</th>
            	<td style="text-align:center;font:bold 12px sans-serif;"><span id="E_cat_nb">--</span>&nbsp;/&nbsp;<span id="E_tcat_nb"><?php echo $E_result_cat; ?></span></td>
            	<td style="text-align:center;font:bold 12px sans-serif;"><span id="E_quest_nb">--</span>&nbsp;/&nbsp;<span id="E_tquest_nb"><?php echo $E_result; ?></span></td>
            </tr>
            <?php
            }
            if($id_type == 0 || $id_type == 2) 
            {
            ?>
            <tr>
            	<th style="text-align:center">Appels Sortants</th>
            	<td style="text-align:center;font:bold 12px sans-serif;"><span id="S_cat_nb">--</span>&nbsp;/&nbsp;<span id="S_tcat_nb"><?php echo $S_result_cat; ?></span></td>
            	<td style="text-align:center;font:bold 12px sans-serif;"><span id="S_quest_nb">--</span>&nbsp;/&nbsp;<span id="S_tquest_nb"><?php echo $S_result; ?></span></td>
            </tr>
            <?php
            }
            if($id_type == 0 || $id_type == 3)
            {
            ?>
            <tr>
            	<th style="text-align:center">Traitement des mails</th>
            	<td style="text-align:center;font:bold 12px sans-serif;"><span id="T_cat_nb">--</span>&nbsp;/&nbsp;<span id="T_tcat_nb"><?php echo $T_result_cat; ?></span></td>
            	<td style="text-align:center;font:bold 12px sans-serif;"><span id="T_quest_nb">--</span>&nbsp;/&nbsp;<span id="T_tquest_nb"><?php echo $T_result; ?></span></td>
            </tr>
            <?php
            }
			if($id_type == 0 || $id_type == 4){ ?>
				<tr>
					<th style="text-align:center">Traitement des tchats</th>
					<td style="text-align:center;font:bold 12px sans-serif;"><span id="Tc_cat_nb">--</span>&nbsp;/&nbsp;<span id="Tc_tcat_nb"><?php echo $Tc_result_cat; ?></span></td>
					<td style="text-align:center;font:bold 12px sans-serif;"><span id="Tc_quest_nb">--</span>&nbsp;/&nbsp;<span id="Tc_tquest_nb"><?php echo $Tc_result; ?></span></td>
				</tr>
				<?php 
			}
			
            	$total_cat    = $E_result_cat + $S_result_cat + $T_result_cat + $Tc_result_cat;
            	$total_grille = $E_result + $S_result + $T_result + $Tc_result;
            ?>
            <tr>
            	<th style="text-align:center">Total</th>
            	<th style="text-align:center;font:bold 12px sans-serif;color:red;"><span id="total_cat_nb">--</span>&nbsp;/&nbsp;<span id="total_tcat_nb"><?php echo $total_cat; ?></span></th>
            	<th style="text-align:center;font:bold 12px sans-serif;color:red;"><span id="total_quest_nb">--</span>&nbsp;/&nbsp;<span id="total_tquest_nb"><?php echo $total_grille; ?></span></th>
            </tr>
            
            </table>
            <!------------------------------------------------------------------->
            <!------------------------------------------------------------------->
            <?php 
            if($id_type == 0) $height = 440; 
            else $height = 492;
            ?>
            <div id="table_resume" class="categorie" style="height:<?php echo $height; ?>px;overflow:auto;margin-top:10px;"></div>
            </div>         

        </div>                         

<!------------------FIN  STEP 3 ----------------------->

  		</div>

<!-- End SmartWizard Content -->  		

</td></tr>

</table>

    		

</body>

</html>

