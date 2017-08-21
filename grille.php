<?php 
	session_start();
	 include("/var/www.cache/dgconn.inc");
	 $fct = $_SESSION['zFonction'];
	    $tHost = $_SERVER['HTTP_HOST'];
	 $tFctAuthorise	= array('ISI','AQI','DQ','DCT','DCC','RP','RESP PLATEAU','AQI','SUP','SUP CC','SUP_CC','TC','OL');
	 $tFctAuthoriseInit  = array('ISI','RESP RD','AQI','DQ','DCT','DCC','RP','RESP PLATEAU','AQI','SUP','SUP CC','SUP_CC');
	 
	 if ( $_SESSION['matricule'] == null || $_SESSION['matricule'] == "" || !in_array( $fct, $tFctAuthoriseInit)  )
	 {
		echo "<p style='color:red'><b>Acc&eacute;s non authoris&eacute; ou session expir&eacute;!</b></p>";
         header ("Refresh: 3;URL=http://".$tHost."/gpao/");
		exit;
	 }
?>
<!DOCTYPE   html>
<html>
<head><title>Grille</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
<script type='text/javascript'>
$(function(){
$('#duree_entretien').timepicker();

$("#projet_champ").change(function(){
   if( $(this).val() != '' )  $('#type_traitement').removeAttr('disabled');
   else $('#type_traitement').attr('disabled',true);
});

});

function do_Total(i){
/*********/

var totalUnitPrice = 0;
var totalPoint = 0;
var inputs = $("input[name='note']:checked");

	/**inputs.each(function(){
	 totalPoint = totalPoint+parseInt($(this).val());		
	});

      $("#display_total span").html(totalPoint);
   */


$('.select_note').each(function(i) {

    totalUnitPrice += parseFloat($(this).val());
    console.log(totalUnitPrice);
    $("#display_total span").html(totalUnitPrice);
  });
  
  
/*********/



}

/**$(window).bind('beforeunload', function() {
	     if($("#test_submit").val() =="" )
            {
                return true;
            }
})
 */
     
        window.onbeforeunload = function() {
           
                  if($("#test_submit").val() =="" )
            {
                return 'Cette page demande de confirmer sa fermeture ; des données saisies pourraient ne pas être enregistrées.';
            }
            
        }
/**********/
</script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"></link>
<link rel="stylesheet" type="text/css" href="css/style.css"></link>
<link rel="stylesheet" href="./css/tablesorter.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.core.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.theme.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.datepicker.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="style.css"></link>
</head>
<body>
<div id="main">
  <div id="contentbg">
    <div id="contenttxtblank">
      <div id="menu">
	    <?php
			$_addr = $_SERVER['REQUEST_URI'];
			$t_addr = explode('/',$_addr);
			$cur_addr = $t_addr[count($t_addr)-1];
		?>
        <ul>
        <?php
		  if ( in_array( $fct, $tFctAuthoriseInit )  )
		  {
		  ?>
		 <li><a href="interface.php" <?php if($cur_addr=='interface.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Projet</a></li>
         <!--<li><a href="grille.php" <?php if($cur_addr=='grille.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Notation Manuelle</a></li>-->
		  <li><a href="nb_reecoute.php" <?php if($cur_addr=='nb_reecoute.php') echo 'class="active"'; else echo 'class="menu"';?>>Suivi</a></li>
          <li class="menusap"></li>
		  <li><a href="index.php" <?php if($cur_addr=='index.php' || $cur_addr=='' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Ecoute</a></li>
          <li class="menusap"></li>
          <li><a  href="synthese.php" <?php if($cur_addr=='synthese.php') echo 'class="active"'; else echo 'class="menu"';?>>Synth&egrave;ses</a></li>
          <li class="menusap"></li>
          <li><a href="indicateur_nf.php" <?php if($cur_addr=='indicateur_nf.php') echo 'class="active"'; else echo 'class="menu"';?>>Indicateurs</a></li>
          <li class="menusap"></li>
          <?php
          }
          ?>
        </ul>
      </div>
      <div id="contentleft">
		<!--div id="morelinksheading">
          <h5>R&eacute;-&eacute;coute</h5>
        </div-->
        
      </div>
      <div id="contentright">
		
        
    </div>
  </div>
  <!-- Content -->
	
  <div class='acc_container'>
	<div class='block'>	
			<center>
			<fieldset>

<?php
   include("/var/www.cache/dgconn.inc");
   
  
    $zresultHtml1 = "";
  
    //$zresultHtml1 .= "<form id='formvalidation'>";
    $zresultHtml1 .= "<form class = 'grille_entrant'>";
     $zresultHtml1 .= "<input type='hidden' id='test_submit'/>";
    $zresultHtml1 .= "<table  cellspacing='7px'>";
    $zresultHtml1 .= "<tr>";
    $zresultHtml1 .= "<td ><strong>Campagne:</strong></td>";
    $zresultHtml1 .= "<td>&nbsp;<select style='width:341px;' id='projet_champ' name='champ_projet'>";
    $zresultHtml1 .= "<option value = ''>-- S&eacute;lectionner --</option>";

    $query_projet = pg_query($conn,"SELECT * FROM cc_sr_projet ORDER BY nom_projet"); 
     while ($rows = pg_fetch_array($query_projet)) {
     $zresultHtml1 .= "<option style='height: 20px;' value={$rows['id_projet']}>{$rows['nom_projet']}</option>";
     }
    $zresultHtml1 .= "</select></td>";
    $zresultHtml1 .= "</tr>";
    /**********************************/
    $zresultHtml1 .= "<tr>";
    $zresultHtml1 .= "<td>Type de traitement</td> ";
    $zresultHtml1 .= "<td>&nbsp;<select style='height: 20px;width:341px;'  id='type_traitement' disabled  name='type_traitement'>";
    $zresultHtml1 .= "<option value=''>-- S&eacute;lectionner --</option>";  
    $result_traitement = pg_query($conn,"SELECT * FROM cc_sr_type_traitement ORDER BY libelle_type_traitement"); 
    while ($rows = pg_fetch_array($result_traitement)) {
          $zresultHtml1 .= "<option style='height: 20px;' value={$rows['id_type_traitement']}>{$rows['libelle_type_traitement']}</option>";
        }    
    $zresultHtml1 .= "</select></td></tr>";
    /*********************************************/
        $zresultHtml1 .= "<tr>";
        $zresultHtml1 .= "<td>Matricule-Prenom</td> ";
        $zresultHtml1 .= "<td>&nbsp;<select style='height: 20px;width:341px;'  id='matricule_not' class='input_notation' name='matricule_not'>";
        $zresultHtml1 .= "<option value=''>-- S&eacute;lectionner --</option>";  
        $result_personne = pg_query($conn,"SELECT  matricule, prenompersonnel FROM personnel WHERE actifpers='Active'  AND fonctioncourante ='TC' order by matricule ASC"); 
        while ($rows1 = pg_fetch_array($result_personne)) {
        $zPrenom = strtolower($rows1['prenompersonnel']);
              $zresultHtml1 .= "<option style='height: 20px;' value={$rows1['matricule']}>{$rows1['matricule']}  $zPrenom</option>";
            }    
        $zresultHtml1 .= "</select></td></tr>";
    
      
    /*********************************************/
    
    
    $zresultHtml1 .= "<tr>
          <td>Date de l'&eacute;v&eacute;nement</td> 
          <td><input type='text' style='margin-left:3px;' class='input_notation'  size='26px'  id='date_entretien' name='date_entretien_manuel' required='required'/></td>        
          </tr>";
    $zresultHtml1 .= "<tr>
          <td>Heure de l'&eacute;v&eacute;nement</td> 
          <td><input type='text' style='margin-left:3px;' class='input_notation' size='26px'    id='duree_entretien' name='duree_entretien' /></td>        
          </tr>";  
 
    $zresultHtml1 .= "<tr>
            <td>Libell&eacute;:</td> 
            <td><input type='text' style='margin-left:3px;' class='input_notation'   size='26px'   id='fichier' name='fichier'  /></td> 
        </tr>";
    $zresultHtml1 .= "</table>";

     echo "<div class = 'block_notation'>";
     echo $zresultHtml1;
     echo "</div>";
     
     echo "<div id='grille_block'></div>";
    
?>
</center>
</body>
</html>