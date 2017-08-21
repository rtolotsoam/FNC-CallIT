<?php
	 session_start();
	 $matricule_session = $_SESSION['matricule'];
	 include("/var/www.cache/dgconn.inc");
	 $tHost = $_SERVER['HTTP_HOST'];
	 $fct = $_SESSION['zFonction'];

	 $tFctAuthoriseInit = array('AQI','DQ','DCT','DCC','RP','SUP','SUP CC','SUP_CC','TC','OL','RESP PLATEAU','ACC','MANAGER','CONSEILLER','FONC_MAIL');
	 $tFctAuthorise = array('AQI','DQ','DCT','DCC','RP','SUP','RESP PLATEAU','MANAGER');
	 
	  include('gestion_droit.php');
	  //$matAdmin = array(6548,6568,5686,6211,5049,5066,5196,5377,7121,7122,628,6550,6899);
	  $matAdmin = getPersMenuProjet();
	  $matNotation = getPersMenuNotation();
	  
	  if( !isset($_SESSION['matricule']) || !in_array($_SESSION['matricule'],$matAdmin))
		{
			echo "<p style='color:red'><b>Accès non authorisé ou session expirée!</b></p>";
            header ("Refresh: 3;URL=http://".$tHost."/gpao/");
		          exit;
		}
	 
	 // if ( $_SESSION['matricule'] == null || $_SESSION['matricule'] == "" || !in_array( $fct, $tFctAuthoriseInit )  )
	 // {
		// echo "<p style='color:red'><b>Acc&eacute;s non authoris&eacute; ou session expir&eacute;!</b></p>";
		// exit;
	 // }
	 
?>
<!DOCTYPE html>
<html>
<head>
<title>Interface D'Administration</title>
                         <!--js -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="./js/jquery.tablesorter.js"></script>
<!---->
<link href="css/smart_tab.css" rel="stylesheet" type="text/css"></link>
<script type="text/javascript" src="js/jquery.smartTab.js"></script>
<!---->
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="./js/jquery.thickbox.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type='text/javascript'>
$('document').ready(function(){
$('#list_projet').tablesorter();
		
});

</script>
                        <!--css -->
<link rel="stylesheet" type="text/css" href="css/style.css"></link>
<link rel="stylesheet" href="./css/tablesorter.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="style.css"></link>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"></link>
<link rel="stylesheet" href="./css/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/style_description.css"></link>
<!--<link rel="stylesheet" href="./css/smart_tab_vertical.css" type="text/css" media="screen" />-->
</head>
<style type='text/css'>
#archive_link{
float:left;
}
#add_link{
/**margin-left:648px;*/
float:left;
}
#gest_link{

float:left;
}
.icone{
margin-top:17px;/**margin-top:38px*/
/**margin-left:15px;*/
display:block;
font-weight:bold;
text-decoration:none;
}
#nb_grille{
text-decoration:none;
}
h2{
text-align:center;
/**color:#158ADE;*/
}
#slct_traitement{
height:25px;
}
#slct_traitement_ajout{
height:25px;
}
#slct_categorie{
padding:0 0 0 5px;
height:25px;
}
#tab_form{
margin:0 0 0 2px;
}

.btn_modif_cat{  
/**float:right;*/
cursor:pointer;
display:none;
width:90px;
}

.btn_modif_item{  
/**float:right;*/
cursor:pointer;
display:none;
width:90px;
}

.cache_categorie{
display:none;
background:#fff;
border:1px  solid #ccc;
}
.cache_item{
display:none;
background:#fff;
border:1px  solid #ccc;
}
.btn_edit_item{
cursor:pointer;
width:90px;
}
.btn_edit{
cursor:pointer;
width:90px;
}
#btn_item{
width:90px;
}

.cache_indicateur{
display:none;
background:#fff;
border:1px  solid #ccc;
}
.btn_edit_indicateur{
width:90px;
}
.btn_modif_indicateur{
display:none;
width:90px;
}
.show_indicateur{
background-color:#B2C6CD;
font-weight:bold;
}
.hide_indicateur{
background:#FFFFFF;
}
#menu_gest_questionnaire{
    float: left;
    height: 30px;
    margin: 0;
    padding: 0;
    width: 828px;
}

#contenttxtblank_gest_questionnaire {
    background-image: url("images/contentbg.jpg");
    background-repeat: no-repeat;
    float: left;
    margin: 0;
    padding: 0 0 0 38px;
    width: 792px;
	
	}

#menu_gest_questionnaire ul {
    display: block;
    float: left;
    height: 30px;
    margin: 0 0 0 -50px;
    padding: 0 0 0 27px;
    width: 154px;
}



	
	#menu_gest_questionnaire ul li {
    display: block;
    float: left;
    height: 30px;
    margin: 0;
    padding: 0;
}
#menu_gest_questionnaire ul li a.menu
	{
		height:22px;
		float: left;
		margin:0px;
		padding:5px 11px 0 11px;
		font-family: "Trebuchet MS";
		font-size:11px;
		font-weight:bold;
		color:#c5c5c5;
		text-align:center;
		text-decoration:none;
		text-transform:uppercase;
	}
	
	#tab_form_ajout{
	   display:none;
	   margin:0 0 0 200px;
	}
	#table_interface{
	margin:20px 0 0 28px;
	}
	.cache_grille{
	   display:none;
	   background:#fff;
       border:1px  solid #ccc;
	}
	.cache_categorie{
	   background:#fff;
       border:1px  solid #ccc;
	}
	.btn_modif_grille{
	 display:none;
     width:90px;
	 margin:0 0 0 40px;
	 
	}
	.btn_edit_grille{
	   width:90px;
	   margin:0 0 0 40px;
	}
	.cache_grille2{
	 display:none;
	   background:#fff;
       border:1px  solid #ccc;
	}
	.btn_modif_grille2{
	 display:none;
     width:90px;
	 margin:0 0 0 40px;
	
	}
	.set_bold{
	font-weight:bold;
	}
	#modifier_projet{
	   display:none;
	}
	.hide_button{
	   display:none;
	}
	.show_button{
	   display:none;
	}
	.libelle{
	float:right;
	margin-top:10px;
	margin-left:5px;
	color:#158ADE;
	}
	#id_ajout_quest{
	float:left;
	cursor:pointer;
	margin-right:15px;
	}
	.libelle_ajout_questionnaire{
	 color: #158ADE;
    /** float: right;*/
     margin-top: 4px;
	}
	
     .btn_add_grille{
	   width:90px;
	   margin:0 0 0 40px;
	}
	
	/**********************************/
	   div:first-of-type div.up {
        visibility: hidden;
        clear:both;
    }
    div:last-of-type div.down {
        visibility: hidden;
 
    }
    div.main {
        clear:both;
    }
    .up {
        float: left;
    }
    .down {
        float: left;
    }
	.move{
	   cursor:move;
	}
	
	.btn_save_grille{	
	   margin: 0 0 0 40px;
       width: 90px;
}
	}
</style>
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
if( isset($matricule_session) && in_array($matricule_session,$matNotation))
 {
?>
		 <li><a href="filtre_dynamique.php" <?php if($cur_addr=='filtre_dynamique.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Notation</a></li>
<?php
 } 

if( isset($matricule_session) && in_array($matricule_session,$matAdmin))
 {
?>	
         <li><a href="interface.php" <?php if($cur_addr=='interface.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Projet</a></li>
<?php
 } 
?>
		 <!--<li><a href="nb_reecoute.php" <?php if($cur_addr=='nb_reecoute.php') echo 'class="active"'; else echo 'class="menu"';?>>Suivi</a></li>
          <li class="menusap"></li>-->
		 <!--<li><a href="index.php" <?php if($cur_addr=='index.php' || $cur_addr=='' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Ecoute</a></li>
          <li class="menusap"></li>-->
<?php
if( isset($matricule_session) && in_array($matricule_session,$matNotation))
 {
?> 
          <li><a  href="recap_synthese.php" <?php if($cur_addr=='recap_synthese.php') echo 'class="active"'; else echo 'class="menu"';?>>Synth&egrave;ses</a></li>
<?php
 } 
?>
          <li class="menusap"></li>
<!--<li><a href="indicateur_nf.php" <?php if($cur_addr=='indicateur_nf.php') echo 'class="active"'; else echo 'class="menu"';?>>Indicateurs</a></li>
          <li class="menusap"></li>-->
          
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
			<h2> Gestion de campagne </h2>
<?php
   include("/var/www.cache/dgconn.inc");
$zHtmlResult = "";
$zresultHtml .= "<form id='form_interface'>";
$zresultHtml .= "<table border=0 id='table_interface'>";
$zresultHtml .= "<input type='hidden' id='champ_cache' value='' />";
$zresultHtml .= "<tr>";
$zresultHtml .= "<td><strong>Projet:</strong></td>";
$zresultHtml .= "<td><input type='text' name='champ_projet' value='' id='champ_projet' required='required'/></td>";
$zresultHtml .= "</tr>";
/****************************/
$location ="bigserver/";
$zresultHtml .= "<tr>";
$zresultHtml .= "<td><strong>Repertoire:</strong></td>";
$zresultHtml .= "<td>&nbsp;<select style='height:20px;' id='champ_repertoire' required='required' name='champ_repertoire'>";
$zresultHtml .= "<option value=''>------selectionner------</option>";
     if ($file= opendir($location)){ 
			while(false !==($fichier = readdir($file))){
				  if($fichier !=".." && $fichier != "."){

                  $fichier_sans_rec = substr($fichier,0,strlen($fichier)-5);
				//$zresultHtml .= "<option style='height: 25px;font-size:14px;' value='$fichier'>".$fichier_sans_rec."</option>";

				$zresultHtml .= "<option style='height: 20px;' value='$fichier'>".$fichier."</option>";

				}

		   }
		}
$zresultHtml .= "</select></td>";
$zresultHtml .= "</tr>";
/****************************/
$zresultHtml .= "<tr>";
$zresultHtml .= "<td ><strong>Client:</strong></td>";
$zresultHtml .= "<td>&nbsp;<select style='height:20px;' id='champ_client' required='required' name='champ_client'>";
$zresultHtml .= "<option value=''>------selectionner------</option>";

$sql_client = "select a.id_client, a.nom_client, b.id_application, b.code from gu_client a 
inner join gu_application b on a.id_client = b.id_application 
order by nom_client asc";
/**$sql_client = "select c.id_client,c.nom_client from gu_client c
inner join ca_client_cegid_gpao cegid on c.id_client=cegid.id_gu_client order by id_client asc";*/
$result_client = pg_query($conn,$sql_client); 
 while ($rows = pg_fetch_array($result_client)) {
 $zresultHtml .= "<option style='height: 20px;' value={$rows['id_client']}>{$rows['nom_client']}</option>";
 }
$zresultHtml .= "</select></td>";
$zresultHtml .= "</tr>";
/****************************/
$zresultHtml .= "<tr>";
$zresultHtml .= "<td><strong>Application:</strong></td>";
$zresultHtml .= "<td>&nbsp;<select style='height:20px;' id='champ_application' required='required' name='champ_application'>";
$zresultHtml .= "<option value=''>------selectionner------</option>";
$zresultHtml .= "</select></td>";
$zresultHtml .= "</tr>";
$zresultHtml .= "<tr>";
$zresultHtml .= "<td>&nbsp;</td>";
$zresultHtml .= "<td id='td_submit' align='right'><input type='submit' id='submit_interface' value='inserer projet' /></td>";
$zresultHtml .= "</tr>";
$zresultHtml .= "</table>";
$zresultHtml .= "</form>";
echo "<div id='interface_projet' class='interface_projet' >";
//echo $zresultHtml;
echo "</div>";
echo "<div id='interface_result'></div>";
/*******************Liste des projet*********************/
$sql_projet = "  SELECT p.id_projet,p.nom_projet,p.nom_repertoire,c.nom_client,c.id_client,ap.nom_application,p.id_application,ap.code,p.date_modification::date,p.campagne_easycode  FROM gu_application ap
                 INNER JOIN  cc_sr_projet p  ON ap.id_application = p.id_application 
                 INNER JOIN gu_client c ON c.id_client = p.id_client  
				 WHERE p.archivage =1  ORDER BY p.nom_projet,c.nom_client,ap.nom_application" ;
$query_projet = @pg_query($conn,$sql_projet) or die (@pg_last_error($conn));
$zresultHtml2 ="";
$zresultHtml2 .= "
<div id='div_img'  >
  <a class='icone' id='add_link' href='#' onClick='affiche_ajout_formulaire();' title='Ajouter Campagne'>
   <img src='images/img_ajout.jpg'  width='30px' height='30px'/>
   <span id='libelle_ajout' class='libelle'>Ajout</span>
  </a>
  <a class='icone' id='archive_link' href='#' onClick='affiche_archive();' title='Campagnes archiv&eacute;s'>
   <img src='images/Database_48x48.png'  width='20px' height='20px' style='margin:7px 0 0 15px;'/>
   <span id='libelle_archive' class='libelle'>Archive</span>
  </a>
  <a class='icone' id='gest_link' href='#' onClick='gestion_questionnaire();' title='Gestion questionnaire'>
   <img src='images/parametre1.png'  width='20px' height='20px' style='margin:7px 0 0 15px;'/>
  <span id='libelle_gestion_questionnaire' class='libelle'>Gestion questionnaire</span>
  </a>
  <a class='icone' id='gest_link' href='#' onClick='gestion_droit_acces();' title='Gestion des droits'>
   <img src='images/accessibility.png'  width='20px' height='20px' style='margin:7px 0 0 15px;'/>
  <span id='libelle_gestion_droit' class='libelle'>Gestion des droits</span>
  </a>
  <a class='icone' id='gest_link' href='#' onClick='gestion_typologie();' title='Gestion des typologies'>
   <img src='images/typologie1.png'  width='20px' height='20px' style='margin:7px 0 0 15px;'/>
  <span id='libelle_gestion_typologie' class='libelle'>Gestion des typologies</span>
  </a>
</div>";

$zresultHtml2 .= "<table id='list_projet' class='tablesorter'>";
$zresultHtml2 .= "<thead>
<tr>
<th align='center'>Projet&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</th>
<th align='center'>Client</th>
<th align='center'>Prestation&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;</</th>
<th align='center'>Application</th>
<th align='center'>Date de modification</th>
<th align='center'>Grille du client</th>
<th align='center'>Action&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp</th>
</tr></thead>";
$i = 0;
            while($rows_projet = @pg_fetch_array($query_projet)){
              $zRepertoire = substr($rows_projet['nom_repertoire'],0,strlen($rows_projet['nom_repertoire'])-5);
			  $date_modif = date_create($rows_projet['date_modification']);
			  $date_modif = date_format($date_modif, "d/m/Y");
              $zresultHtml2 .= "<tr align='center'>
                    <td>{$rows_projet['nom_projet']}</td>
                    <td>{$rows_projet['nom_client']}</td>
                    <td>{$rows_projet['code']}</td>
                    <td>{$rows_projet['nom_application']}</td>
                    <td>{$date_modif}</td>
                    <td><a href='#'   title='Gestion des grilles'  onclick='gerer_grille({$rows_projet['id_client']},{$rows_projet['id_application']},$i);' id='nb_grille'><img src='images/Table_48x48.png' width='20px' height='20px'  alt='nombre grille' /></a>&nbsp;<a href='#'  title='Duplication' onclick='admin_duplication_projet({$rows_projet['id_client']},{$rows_projet['id_application']},$i);' id='btn_penalite'><img src='images/dupliquer_2.jpg' width='20px' height='20px'  alt='nombre grille' /></a></td>
                    <td width='72px'>
					<input type='hidden' class='id_projet' id='id_projet$i'  name='id_projet$i' value='{$rows_projet['id_projet']}' />
					<input type='hidden' class='nom_projet' id='nom_projet$i'  name='nom_projet$i' value='{$rows_projet['nom_projet']}' />
					<input type='hidden' class='nom_client' id='nom_client$i'  name='nom_client$i' value='{$rows_projet['nom_client']}' />
					<input type='hidden' class='nom_application' id='nom_application$i'  name='nom_application$i' value='{$rows_projet['code']}' />
					<input type='hidden' class='libelle_application' id='libelle_application$i'  name='libelle_application$i' value='{$rows_projet['nom_application']}' />
					
					<input type='hidden' class='campagne_easycode' id='campagne_easycode$i'  name='campagne_easycode$i' value='{$rows_projet['campagne_easycode']}' />
					
					 <a href='#' id='btn_archive' title='Archiver' onClick ='archive_projet({$rows_projet['id_application']},{$rows_projet['id_client']},{$i});' ><img src='images/Database_48x48.png' width='20px' height='20px'  alt=''supprimer /></a>
					 &nbsp<a href='#' id='btn_modif' onClick ='modif_projet2($i,{$rows_projet['id_client']},{$rows_projet['id_application']});' title='Modifier'><img src='images/Edit_48x48.png' width='20px' height='20px'  alt='modifier' /></a>
					 &nbsp<a href='#' id='btn_historique' onClick ='affiche_historique({$rows_projet['id_projet']},{$i});' title='Historique'><img src='images/img_historique3.jpg' width='20px' height='20px'  alt='modifier' /></a>
					 </td>
                </tr>
              ";
                 $i++;                            
            }
$zresultHtml2 .= "</table>";
echo $zresultHtml2;
?>
</fieldset>
</center>
</div>
</body>
</html>