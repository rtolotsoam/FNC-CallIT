<script>
	$(document).ready(function(){
		$("#slctGravite").change(function() {
            var grv = $("#slctGravite").val() ;
            var frq = $("#slctFrequence").val() ;
            //alert(grv);
            $.post(
               "new_criticite.php",
               {
                  grav_id : grv,
                  freq_id : frq
               },
               function(_result){
                  //alert(_result);
               var res = _result.split('||');
               var i = res[0] ;
               var id_grav = res[1];
               var id_freq = res[2];
               //alert(i);
               if(i == 'mineure')
                  $("#critq").css ( { "backgroundColor":"yellow"},{"color":"#000000"},{"font-weight":"bold" } );
               else if(i == 'Majeure')
                  $("#critq").css ( { "backgroundColor":"#FB9228"},{"font-weight":"bold"} );
               else //if(i == 'Critique')
                  $("#critq").css ( { "backgroundColor":"#FA1D05"},{"color":"#FFFFFF"},{"font-weight":"bold" } );/*
               else
                  $("#critq").css ( { "backgroundColor":"yellow"},{"color":"#FFFFFF"},{"font-weight":"bold" } );*/

                  $("#critq").html(res[0]);
                  $("#id_grav").val(id_grav);
                  $("#id_freq").val(id_freq);
               }
            );
         }) ;

         $("#slctFrequence").change(function() {
            var frq = $("#slctFrequence").val() ;
            var grv = $("#slctGravite").val() ;
            //alert(grv);
            $.post(
               "new_criticite.php",
               {
                  grav_id : grv,
                  freq_id : frq
               },
               function(_result){
               //alert(_result);
               var res = _result.split('||');
               var i = res[0] ;
               var id_grav = res[1];
               var id_freq = res[2];
                  if(i == 'mineure')
                     $("#critq").css ( { "backgroundColor":"yellow"},{"color":"#000000"},{"font-weight":"bold" } );
               else if(i == 'Majeure')
                  $("#critq").css ( { "backgroundColor":"#FB9228"},{"font-weight":"bold"} );
               else
                  $("#critq").css ( { "backgroundColor":"#FA1D05"},{"color":"#FFFFFF"},{"font-weight":"bold" } );
               /*else
                  $("#critq").css ( { "backgroundColor":"yellow"},{"color":"#FFFFFF"},{"font-weight":"bold" } );*/

                  $("#critq").html(res[0]);
                  $("#id_grav").val(id_grav);
                  $("#id_freq").val(id_freq);
               }
            );
         }) ;
	});
</script>
<?php

include 'function_dynamique.php';

$id_projet             = $_REQUEST['id_projet'];
$id_client             = $_REQUEST['id_client'];
$id_application        = $_REQUEST['id_application'];
$idTypeAppel           = $_REQUEST['idTypeAppel'];
$id_fichier            = $_REQUEST['id_fichier'];
$id_type_traitement    = $_REQUEST['id_type_traitement'];
$id_tlc                = $_REQUEST['id_tlc'];
$notation_id           = $_REQUEST['notation_id'];
$id_categorie          = $_REQUEST['id_categorie'];
$id_grille             = $_REQUEST['id_grille'];
$id_grille_application = $_REQUEST['id_grille_application'];
//$notation_id = $_REQUEST['notation_id'];
$test_nc_si = $_REQUEST['test_nc_si'];

$prenom_tlc    = get_prenom_personnel($id_tlc);
$lib_categorie = get_libelle_categorie($id_categorie);

$nom_fichier = get_nom_fichierById($id_fichier);

if (isset($notation_id) && ($notation_id != '') && ($notation_id != 0)) {
    $appelType = getByIdTypeAppel(trim($notation_id));
}

$libelle = getLibelleById($id_projet, $id_client, $id_application, $id_type_traitement, $id_tlc, $nom_fichier);

$tab_libelle   = explode('||', $libelle);
$data_notation = getDateNotationById($notation_id);
$data_notation = explode("|||", $data_notation);

$date_evaluation = isset($data_notation[0]) ? $data_notation[0] : '';
$date_traitement = isset($data_notation[3]) ? $data_notation[3] : '';

if ($date_evaluation == '') {
    $date_evaluation = date('d/m/Y');
}

$nom_fichier = get_nom_fichierById($id_fichier);
$rws_nc      = getFNCByNotationGrilleApp($id_grille_application, $notation_id);
if (!empty($rws_nc)) {
    $description       = explode('>>>', $rws_nc[0]);
    $description_nc_si = isset($description[0]) ? utf8_encode($description[0]) : '';
    $exigence_nc_si    = utf8_encode($rws_nc[1]);
    $gravite_si        = $rws_nc[2];
    $frequence_si      = $rws_nc[3];
} else {
    $description_nc_si = '';
    $exigence_nc_si    = '';
}

$zHtml = "";
//$zHtml .= 'test = '.$exigence_nc_si;
$zHtml .= "<table   border=0   cellspacing='6' style='margin: 0 20px 0 20px' >";
$nom_client              = isset($tab_libelle[1]) ? $tab_libelle[1] : '';
$prestation              = isset($tab_libelle[2]) ? $tab_libelle[2] : '';
$libelle_type_traitement = isset($tab_libelle[3]) ? $tab_libelle[3] : '';
$zHtml .= "<tr>";
$zHtml .= "<td>Nom du client yyyyyyyyyyyyyyyyyyyyyyyyy:</td><td><input readonly value='" . $nom_client . "' class='champ_nc' type ='text' id='id_client' /></td>";
$zHtml .= "</tr>";

$zHtml .= "<tr>";
$zHtml .= "<td>Prestation:</td><td><input readonly value=" . $prestation . " class='champ_nc' type ='text' id='id_prestation' /></td>";
$zHtml .= "</tr>";

if (isset($_REQUEST['notation_id']) && ($appelType != '')) {
    $zHtml .= "<tr>";
    $zHtml .= "<td>Type d'appel:</td><td><input readonly value='" . $appelType . "' class='champ_nc' type ='text' id='appel_type' /></td>";
    $zHtml .= "</tr>";
}

$zHtml .= "<tr>";
$zHtml .= "<td>Type de traitement:</td><td><input readonly value='" . $libelle_type_traitement . "' class='champ_nc' type ='text' id='type_traitement' /></td>";
$zHtml .= "</tr>";

$zHtml .= "<tr>";
$zHtml .= "<td>TLC:</td><td><input readonly value='" . $prenom_tlc . "' class='champ_nc' type='text' id='id_tlc' /></td>";
$zHtml .= "</tr>";

$zHtml .= "<tr>";
$zHtml .= "<td>Fichier:</td><td><input readonly value='" . $nom_fichier . "' class='champ_nc'  type='text' id='id_fichier' /></td>";
$zHtml .= "</tr>";

$zHtml .= "<tr>";
$zHtml .= "<td>Date de traitement:</td><td><input readonly value='" . $date_traitement . "' class='champ_nc' type='text' id='date_traitement' /></td>";
$zHtml .= "</tr>";

$zHtml .= "<tr>";
$zHtml .= "<td>Date évaluation:</td><td><input readonly value='" . $date_evaluation . "' class='champ_nc'  type='text' id='date_evaluation' /></td>";
$zHtml .= "</tr>";

$id_notat       = $_REQUEST['notation_id'];
$getInfoDossier = getInfoDossier($id_notat);

// if($inbNota > 0 )
// {
// for ($i = 0 ; $i < $inbNota ; $i++)
// {
$dossier  = '';
$commande = '';

$toInfoDossier = @pg_fetch_array($getInfoDossier, $i);
$dossier       = $toInfoDossier['numero_dossier'];
$commande      = $toInfoDossier['numero_commande'];

$zHtml .= "<tr>";
$zHtml .= "<td>Numéro de dossier: </td><td><input readonly value='" . utf8_encode($dossier) . "' class='champ_nc'  type='text' id='categorie_si' /></td>";
$zHtml .= "</tr>";
$zHtml .= "<tr>";
$zHtml .= "<td>Numéro de commande: </td><td><input readonly value='" . utf8_encode($commande) . "' class='champ_nc'  type='text' id='categorie_si' /></td>";
$zHtml .= "</tr>";
// }
// }

$zHtml .= "<tr>";
$zHtml .= "<td>Catégorie SI:</td><td><input readonly value='" . utf8_encode($lib_categorie) . "' class='champ_nc'  type='text' id='categorie_si' /></td>";
$zHtml .= "</tr>";

$zHtml .= "<tr>";
if (!empty($rws_nc)) {
    $zHtml .= "<td>Description de l'écart:</td><td><textarea readonly class='champ_nc' id='description_ecart'  cols='34' rows='5'>" . $description_nc_si . "</textarea></td>";
} else {
    $zHtml .= "<td>Description de l'écart:<span style='color:red;'>*</span></td><td><textarea id='description_ecart'  cols='34' rows='5'></textarea></td>";
}

$zHtml .= "</tr>";

$zHtml .= "<tr>";
if (!empty($rws_nc)) {
    $zHtml .= "<td>Exigence client / référentiel:</td><td><textarea readonly class='champ_nc' id='exigence_client' cols='34' rows='5'>" . $exigence_nc_si . "</textarea></td>";
} else {
    $zHtml .= "<td>Exigence client / référentiel:<span style='color:red;'>*</span></td><td><textarea id='exigence_client' cols='34'  rows='5'></textarea></td>";
}

$zHtml .= "</tr>";

$zHtml .= "<tr><td>Gravité / Impact:<span style='color:red;'>*</span></td>
<td>";
$zHtml .= '<input type="text" hidden id="id_grav" value="0" />
<select id="slctGravite" name="slctGravite" class="slct_grav">
<option value="">***** fa&icirc;tes votre choix *****</option>';
$resGravite = getGravite();
$iNumGrav   = @pg_num_rows($resGravite);
$selected   = '';
for ($i = 0; $i < $iNumGrav; $i++) {
    $toGravite   = @pg_fetch_array($resGravite, $i);
    $cat_grav_id = $toGravite['id_categorie_grav'];
    $ech_grav    = $toGravite['echelle_id_grav'];
    $lib_grav    = $toGravite['libelle_gravite'];
    if ($cat_grav_id == $gravite_si) {
        $selected = 'selected';
    }

    $zHtml .= "<option $selected value='" . $cat_grav_id . "'>" . $ech_grav . "_" . utf8_encode($lib_grav) . "</option>";
}

$zHtml .= '</select>';
$zHtml .= "</td></tr>";

$zHtml .= "<tr><td>Fréquence / Probabilité d'occurrence:<span style='color:red;'>*</span></td>
<td>";
$zHtml .= '<input type="text" hidden id="id_freq" value="0" />
<select id="slctFrequence" name="slctFrequence" class="slct_grav">
<option value="">***** fa&icirc;tes votre choix *****</option>';
$resFrequence = getFrequence();
$iNumFreq     = @pg_num_rows($resFrequence);

for ($i = 0; $i < $iNumFreq; $i++) {
    $toFrequence = @pg_fetch_array($resFrequence, $i);
    $cat_freq_id = $toFrequence['id_categorie_freq'];
    $ech_freq    = $toFrequence['echelle_id_freq'];
    $lib_freq    = $toFrequence['libelle_frequence'];
    if ($cat_freq_id == $frequence_si) {
        $selected = 'selected';
    }

    $zHtml .= "<option $selected value='" . $cat_freq_id . "'>" . $ech_freq . "_" . utf8_encode($lib_freq) . "</option>";
}

$zHtml .= '</select>';
$zHtml .= "</td></tr>";

$zHtml .= "<tr><td>Criticité : </td>
<td id='critq'></td>
</tr>";

$zHtml .= "<tr>";
if (!empty($rws_nc)) {
    $zHtml .= "<td  colspan='2' align='right'>
	<input  type='button' id='annuler' class='btn_visu' value='Fermer'  onclick='tb_remove();' />
</td>";
} else {
    /*$zHtml .= "<td  colspan='2' align='right'><input class='btn_visu' onclick='insert_fnc_si(".$id_grille.",".$id_grille_application.",".$id_tlc.",".$id_projet.",".$notation_id.");'  style='float:left;margin-left:157px;'  type='button' id='open_fiche' value='Ouvrir la fiche'  /><input  type='button' id='annuler' class='btn_visu' value='annuler'  onclick='tb_remove();' /></td>";*/
    if (isset($_REQUEST['idTypeAppel']) && ($_REQUEST['idTypeAppel'] != 0)) {

        $tempAppelType = $_REQUEST['idTypeAppel'];
        $zHtml .= "<td  colspan='2' align='right'>
	<input class='btn_visu' onclick='affecter_fnc_si2(" . $id_grille . "," . $id_grille_application . "," . $tempAppelType . ")'  style='float:left;margin-left:157px;'  type='button' id='open_fiche' value='Ouvrir la fiche'  />
	<input  type='button' id='annuler' class='btn_visu' value='Annuler'  onclick='tb_remove();' />
</td>";
    } else {
        $zHtml .= "<td  colspan='2' align='right'>
	<input class='btn_visu' onclick='affecter_fnc_si(" . $id_grille . "," . $id_grille_application . ")'  style='float:left;margin-left:157px;'  type='button' id='open_fiche' value='Ouvrir la fiche'  />
	<input  type='button' id='annuler' class='btn_visu' value='Annuler'  onclick='tb_remove();' />
</td>";
    }
}

$zHtml .= "</tr>";

$zHtml .= "</table>";

$zHtml .= "<input type='hidden' id='id_grille_sauvegarde' value='" . $id_grille . "' />";
$zHtml .= "<input type='hidden' id='id_grille_application_sauvegarde' value='" . $id_grille_application . "' />";

echo utf8_decode($zHtml);

?>
<script>
$(document).ready(function(){

	var id_grille_sauvegarde = $('#id_grille_sauvegarde').val();
	var id_grille_app_sauvegarde = $('#id_grille_application_sauvegarde').val();

	var test_nc_si = $('#id_test_nc_si_'+id_grille_sauvegarde+'_'+id_grille_app_sauvegarde).val();
	var description_nc_si = $('#description_fnc_si_'+id_grille_sauvegarde+'_'+id_grille_app_sauvegarde).val();
	var exigence_nc_si = $('#exigence_fnc_si_'+id_grille_sauvegarde+'_'+id_grille_app_sauvegarde).val();

	var id_grav = $('#gravite_si_'+id_grille_sauvegarde+'_'+id_grille_app_sauvegarde).val();
	var id_freq = $('#frequence_si_'+id_grille_sauvegarde+'_'+id_grille_app_sauvegarde).val();
	var cat_grav_si = $('#cat_grav_si_'+id_grille_sauvegarde+'_'+id_grille_app_sauvegarde).val();
	var cat_freq_si = $('#cat_freq_si_'+id_grille_sauvegarde+'_'+id_grille_app_sauvegarde).val();
	var criticite = $('#criticite_si_'+id_grille_sauvegarde+'_'+id_grille_app_sauvegarde).val();

	if(test_nc_si == 0 && description_nc_si != '' && exigence_nc_si != '')
	{
		$('#description_ecart').val(description_nc_si);
		$('#exigence_client').val(exigence_nc_si);

		$('#id_grav').val(id_grav);
		$('#id_freq').val(id_freq);
		$('#slctGravite').val( cat_grav_si );
		$('#slctFrequence').val( cat_freq_si );
		$('#critq').html( criticite );
		if(criticite == 'mineure')
             $("#critq").css ( { "backgroundColor":"yellow"},{"color":"#000000"},{"font-weight":"bold" } );
        else if(criticite == 'Majeure')
             $("#critq").css ( { "backgroundColor":"#FB9228"},{"font-weight":"bold"} );
        else
             $("#critq").css ( { "backgroundColor":"#FA1D05"},{"color":"#FFFFFF"},{"font-weight":"bold" } );
	}

});


</script>

<script>
	function affecter_fnc_si(id_grille,id_grille_application)
	{
		var description_ecart = $('#description_ecart').val();
		var exigence_client = $('#exigence_client').val();
		var slctGravite = $('#slctGravite').val();
		var slctFrequence = $('#slctFrequence').val();
		var id_grav = $('#id_grav').val();
		var id_freq = $('#id_freq').val();
		var criticite = $('#critq').html();

		if( description_ecart == '' && exigence_client =='' && slctGravite == '' && slctFrequence =='')
		{
	        $("#description_ecart").addClass("erreur");
	        $("#exigence_client").addClass("erreur");
	        $("#slctGravite").addClass("erreur");
	        $("#slctFrequence").addClass("erreur");
			return false;
		}else if( description_ecart =='' ){
			$("#description_ecart").addClass("erreur");
			$("#exigence_client").removeClass("erreur");
			$("#slctGravite").removeClass("erreur");
	        $("#slctFrequence").removeClass("erreur");
			return false;
		}else if( exigence_client =='' ){
			$("#description_ecart").removeClass("erreur");
			$("#exigence_client").addClass("erreur");
			$("#slctGravite").removeClass("erreur");
	        $("#slctFrequence").removeClass("erreur");
			return false;
		}else if( slctGravite =='' ){
			$("#description_ecart").removeClass("erreur");
			$("#exigence_client").removeClass("erreur");
			$("#slctGravite").addClass("erreur");
	        $("#slctFrequence").removeClass("erreur");
			return false;
		}else if( slctFrequence =='' ){
			$("#description_ecart").removeClass("erreur");
			$("#exigence_client").removeClass("erreur");
			$("#slctGravite").removeClass("erreur");
	        $("#slctFrequence").addClass("erreur");
			return false;
		}
		else
		{
			$('#description_fnc_si_'+id_grille+'_'+id_grille_application).val(description_ecart);
			$('#exigence_fnc_si_'+id_grille+'_'+id_grille_application).val(exigence_client);
			$('#commentaire_si_'+id_grille+'_'+id_grille_application).val('1');

			$('#gravite_si_'+id_grille+'_'+id_grille_application).val(id_grav);
			$('#frequence_si_'+id_grille+'_'+id_grille_application).val(id_freq);
			$('#cat_grav_si_'+id_grille+'_'+id_grille_application).val(slctGravite);
			$('#cat_freq_si_'+id_grille+'_'+id_grille_application).val(slctFrequence);
			$('#criticite_si_'+id_grille+'_'+id_grille_application).val(criticite);

			//$("#test_"+id_grille).val(1);
			$('#annuler_nc_'+id_grille+'_'+id_grille_application).css('display','inline');
			$('#btn_editer_nc_si_'+id_grille+'_'+id_grille_application).css('display','inline');
			$('#btn_nc_'+id_grille+'_'+id_grille_application).css('display','none');

			var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
			var flag_el = $("#test_"+id_grille).val();
			test_bg_si(com_si,flag_el,id_grille,id_grille_application);

			tb_remove();
		}

	}

   function affecter_fnc_si2(id_grille,id_grille_application,typeA)
	{
		var description_ecart = $('#description_ecart').val();
		var exigence_client = $('#exigence_client').val();
		var slctGravite = $('#slctGravite').val();
		var slctFrequence = $('#slctFrequence').val();
		var id_grav = $('#id_grav').val();
		var id_freq = $('#id_freq').val();
		var criticite = $('#critq').html();

		if( description_ecart == '' && exigence_client =='' && slctGravite == '' && slctFrequence =='')
		{
	        $("#description_ecart").addClass("erreur");
	        $("#exigence_client").addClass("erreur");
	        $("#slctGravite").addClass("erreur");
	        $("#slctFrequence").addClass("erreur");
			return false;
		}else if( description_ecart =='' ){
			$("#description_ecart").addClass("erreur");
			$("#exigence_client").removeClass("erreur");
			$("#slctGravite").removeClass("erreur");
	        $("#slctFrequence").removeClass("erreur");
			return false;
		}else if( exigence_client =='' ){
			$("#description_ecart").removeClass("erreur");
			$("#exigence_client").addClass("erreur");
			$("#slctGravite").removeClass("erreur");
	        $("#slctFrequence").removeClass("erreur");
			return false;
		}else if( slctGravite =='' ){
			$("#description_ecart").removeClass("erreur");
			$("#exigence_client").removeClass("erreur");
			$("#slctGravite").addClass("erreur");
	        $("#slctFrequence").removeClass("erreur");
			return false;
		}else if( slctFrequence =='' ){
			$("#description_ecart").removeClass("erreur");
			$("#exigence_client").removeClass("erreur");
			$("#slctGravite").removeClass("erreur");
	        $("#slctFrequence").addClass("erreur");
			return false;
		}
		else
		{
			$('#description_fnc_si_'+id_grille+'_'+id_grille_application).val(description_ecart);
			$('#exigence_fnc_si_'+id_grille+'_'+id_grille_application).val(exigence_client);
			$('#commentaire_si_'+id_grille+'_'+id_grille_application).val('1');

			$('#gravite_si_'+id_grille+'_'+id_grille_application).val(id_grav);
			$('#frequence_si_'+id_grille+'_'+id_grille_application).val(id_freq);
			$('#cat_grav_si_'+id_grille+'_'+id_grille_application).val(slctGravite);
			$('#cat_freq_si_'+id_grille+'_'+id_grille_application).val(slctFrequence);
			$('#criticite_si_'+id_grille+'_'+id_grille_application).val(criticite);

			//$("#test_"+id_grille).val(1);
			$('#annuler_nc_'+id_grille+'_'+id_grille_application).css('display','inline');
			$('#btn_editer_nc_si_'+id_grille+'_'+id_grille_application).css('display','inline');
			$('#btn_nc_'+id_grille+'_'+id_grille_application).css('display','none');

			var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
			var flag_el = $("#test_"+id_grille).val();
			test_bg_si(com_si,flag_el,id_grille,id_grille_application);

			tb_remove();
		}

	}
</script>