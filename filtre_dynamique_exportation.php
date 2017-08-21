<!DOCTYPE html>
<html>
  <head>
    <script type="text/javascript" src="js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script>

    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="js/script_maquette.js"></script>
    <script type="text/javascript" src="js/jquery.thickbox.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="js/jquery.simpletooltip-min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>

    <script src="js/chosen/chosen.jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="js/chosen/chosen.css"></link>

    <!--<script type="text/javascript" src="js/jquery.smartWizard-2.0.js"></script>
    <script type="text/javascript" src="js/jquery.smartTab.js"></script>-->

    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css"></link>

    <link rel="stylesheet" href="./css/ui.core.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/ui.theme.css" type="text/css" media="screen" />

    <link rel="stylesheet" type="text/css" href="style_maquette.css"></link>
    <link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/tablesorter.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./css/ui.datepicker.css" type="text/css" media="screen" />

    <script type='text/javascript'>
      $('document').ready(function(){
        $('#id_filtre_date_notation_deb').datepicker();
        $('#id_filtre_date_notation_fin').datepicker();
        $('#id_filtre_date_appel_deb').datepicker();
        $('#id_filtre_date_appel_fin').datepicker();
      });
    </script>

    <style>
    	.table_contenu_consultation {
    		border-collapse: collapse;
    	}
    	.table_contenu_consultation thead tr th {
    		/*color: #446f7b;*/
    		color: #FFFFFF;
    		font-family: Verdana;
    		font-size : 11px;
    		/*background: #a0c1c9;*/
    		background: #000000;
    		height: 25px;
    		border: 1px solid #FFFFFF;
    		text-align:center;
    		padding: 0 10px 0 10px;
    	}
    	.table_contenu_consultation tbody tr td {
    		border: 1px solid #000000;
    		text-align:center;
    		padding: 0 10px 0 10px;
    		font-family: Verdana;
    		font-size : 11px;
    	}
    	#contenu_all_notation
    	{
    		display: block;
    		width: 98%;
    		/*height: 300px;*/
    		overflow:auto;
    		margin: auto;
    	}
    	#table_filtre_notation {
    		//border: 1px solid #d6d6d6;
    		font-family: Verdana;
    		font-size : 11px;
    		font-weight:bold;
    		margin: auto;
    		//padding: 5px;
    		width:100%;
    	}
    	#table_filtre_notation tr td .lbl_ttl {
    		text-align: right;
    		padding: 5px;
    		display: block;
    	}
    	#table_filtre_notation tr td select {
    		//width: 200%;
    	}
    	#table_filtre_notation tr td select option {
    		//width: 200px;
        //font-weight: normal;
    	}
    	#table_filtre_notation tr td input{
    		width: 85px;
    	}
    	#table_filtre_notation tr td input.btn_visu {
    		width: 120px;
    	}
    	#contenu_filtre_notation {
    		display: block;
    		text-align: center;
    		width:828px;
    		margin: auto auto 5px;
    	}
      .acc_container{
        width: 99%;
        min-width: 950px;
      }
    </style>

  </head>
  <?php
    session_start();
    $matricule_session = $_SESSION['matricule'];
    $_addr = $_SERVER['REQUEST_URI'];
    $t_addr = explode('/',$_addr);
    $cur_addr = $t_addr[count($t_addr)-1];
    include('gestion_droit.php');
  //$matAdmin = array(6548,6568,5686,6211,5049,5066,5196,5377,7121,7122,628,6550,6899);
    $matAdmin = getPersMenuProjet();
    $matNotation = getPersMenuNotation();
    if( !isset($matricule_session) || !in_array($matricule_session,$matNotation)){
      echo "<p style='color:red;font-size:13px;'><b>Acc&egrave;s non authoris&eacute; ou session expir&eacute;e!</b>";
      echo "<a  class='no_acces_btn' onclick='change_button();' id='redirection_link' href='" .$tHost. "/' target='_blank' >Aller dans la page d'accueil</a><a class='no_acces_btn' style='display:none;' id='actualise_link' href='" .$tHost. "/intranet_light/modules/cc_sr/filtre_dynamique_exportation.php' >Actualiser la page</a></p>";
      // header ("Refresh: 3;URL=http://".$tHost."/gpao/");
      exit;
    }
  ?>
  <body>
    <div id="main">
      <div id="contentbg">
        <div id="contenttxtblank">
          <div id="menu">
            <ul>
              <?php
                if( isset($matricule_session) && in_array($matricule_session,$matNotation)){
              ?>
              <li class="menusap"></li>
              <li><a href="filtre_dynamique.php" <?php if($cur_addr=='filtre_dynamique.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Notation</a></li>
              <li class="menusap"></li>
              <li><a href="filtre_dynamique_exportation.php" <?php if($cur_addr=='filtre_dynamique_exportation.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Export-Notation</a></li>
              <li class="menusap"></li>
              <?php }
                if( isset($matricule_session) && in_array($matricule_session,$matAdmin)){
              ?>
              <li class="menusap"></li>
              <li><a href="interface.php" <?php if($cur_addr=='interface.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Projet</a></li>
              <li class="menusap"></li>
              <?php } ?>
              <!--<li><a href="nb_reecoute.php" <?php if($cur_addr=='nb_reecoute.php') echo 'class="active"'; else echo 'class="menu"';?>>Suivi</a></li>
              <li class="menusap"></li>-->
              <?php
          		  // if ( in_array( $fct, $tFctAuthoriseInit )  ){
        		  ?>
              <!-- <li><a href="index.php" <?php if($cur_addr=='index.php' || $cur_addr=='' ) echo 'class="active"'; else echo 'class="menu"'; ?> >&eacute;coute</a></li>
              <li class="menusap"></li>-->
              <?php
                if( isset($matricule_session) && in_array($matricule_session,$matNotation)){
              ?>
              <li><a  href="recap_synthese.php" <?php if($cur_addr=='recap_synthese.php') echo 'class="active"'; else echo 'class="menu"';?>>Synth&egrave;ses</a></li>
              <?php } ?>
              <li class="menusap"></li>
              <!--<li><a href="indicateur_nf.php" <?php if($cur_addr=='indicateur_nf.php') echo 'class="active"'; else echo 'class="menu"';?>>Indicateurs</a></li>
              <li class="menusap"></li>-->
              <?php // } ?>
            </ul>
          </div>
        </div>
      </div>

    </div>
    <div class='acc_container' style="overflow:visible;">
      <div class='block' id="id_affiche_filtre" style="/*height:282px;*/">
        <?php include('export_notation/index.php'); ?>
      </div>
    </div>
  </body>
</html>
