<html>
<head>
<title>Gestion questionnaire</title>
<link href="css/smart_tab.css" rel="stylesheet" type="text/css"></link>
<script type="text/javascript" src="js/jquery.smartTab.js"></script>

<link rel="stylesheet" type="text/css" href="style.css"></link>
<script type="text/javascript">
    $(document).ready(function(){
	
     $('#tabs').smartTab({selected: 0,autoProgress: false,stopOnFocus:false,autoHeight:false,transitionEffect:'hSlide'});  
	 
	 /************************************************/
	
	  $("#tab_categorie tbody").sortable({

    cursor: 'move',
    delay: 180,

  /**   update: function()
    {
        var rowsOrder = $(this).sortable("serialize");

       $.post("ajax_actions.php", { action:'change_rows_order', table:'categories', order:'category_order', rows_order:rowsOrder } );
    }
*/
     }).disableSelection();
	 
	 /************************************************/
	 
	 
     });
	 function tester(){alert(147);}
	 $(".stContainer").css({'width':'847px','top':'-4px','height':'567px'});
</script>
</head>
<body>
<?php

     session_start();
	 include("/var/www.cache/dgconn.inc");
	 include("function_grille_.php");
	 ?>
<div style="height:598px;overflow:auto;margin:auto;" class="categorieCl"> 
<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td valign="top">
<!-- Tab 1 -->
   <div id="tabs">
     <ul>
      <li><a href="#tabs-1">Modification<br />
               
            </a></li>
      <li><a href="#tabs-2">Ajout/Suppression<br />
                
            </a></li>
     </ul>
   
    <div id="tabs-1" class="categorieClass" style="overflow:auto;height:100%;margin-top:5px;display:block;">
        
			<?php
	$zForm_modification = "";
	$zForm_modification .= "<table border=0 id='tab_form'>";
	$zForm_modification .= "<form>";
	$zForm_modification .= "<tr>";
	$zForm_modification .= "<td align='right'>Type de traitement : </td>";	 
	$zForm_modification .= "<td>
	       <select style='height:28px;' class='select_add_modif' id='slct_traitement'   onChange='get_categorie();' >;
	          <option  value=''> --Choix type-- </option>";	
	$zForm_modification .= get_type_traitement() ;
	$zForm_modification .= "</select></td>";		          
	$zForm_modification .= "</tr>";
	$zForm_modification .= "</form>";
	$zForm_modification .= "</table>";
	$zForm_modification .= "<div style='display:none;text-align:center;' id='div_loading'><img  src='./images/wait.gif' width='30px' height='30px' /></div><br />";
     echo $zForm_modification;
	 
	 $zCorp  = "<div  id='div_corps' >";
     $zCorp .= "</div>";
	 echo $zCorp;
			?>
        </div>
     
     <div id="tabs-2" class="categorieClass" style="overflow:auto;height:100%;margin-top:5px;display:block;">
    <input type='hidden' id='input_id_categorie' /> 
			<?php
	$zForm_ajout = "";
	$zForm_ajout .= "<table border=0 id='tab_form'>";
	$zForm_ajout .= "<form>";
	$zForm_ajout .= "<tr>";
	$zForm_ajout .= "<td align='right'>Type de traitement : </td>";	 
	$zForm_ajout .= "<td>
	       <select  style='height:28px;' class='select_add_modif' id='slct_traitement_ajout'   onChange='get_categorie_ajout();' >;
	          <option  value=''> --Choix type-- </option>";	
	$zForm_ajout .= get_type_traitement() ;
	$zForm_ajout .= "</select></td>";		          
	$zForm_ajout .= "</tr>";
	$zForm_ajout .= "</form>";
	$zForm_ajout .= "</table>";
	$zForm_ajout .= "<div style='display:none;text-align:center;' id='div_loading_ajout'><img  src='./images/wait.gif' width='30px' height='30px' /></div><br />";
     echo $zForm_ajout;
	 
	  $zCorp_ajout  = "<div  id='div_corps_ajout' >";
      $zCorp_ajout .= "</div>";
	 echo $zCorp_ajout;
			?>
      </div>    
  
   
  
   </div>   

   </div>  
  </td>
</tr>
</table>
</div>
</body>
</html>