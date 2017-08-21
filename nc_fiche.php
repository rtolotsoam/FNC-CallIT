<?php

include 'function_dynamique.php';

$id_projet = $_REQUEST['id_projet'];
$id_client = $_REQUEST['id_client'];

$id_application     = $_REQUEST['id_application'];
$id_fichier         = $_REQUEST['id_fichier'];
$id_type_traitement = $_REQUEST['id_type_traitement'];
$id_tlc             = $_REQUEST['id_tlc'];
$notation_id        = $_REQUEST['notation_id'];

$idTypeAppel = $_REQUEST['idTypeAppel'];

$nom_fichier   = get_nom_fichierById($id_fichier);
$prenom_tlc    = get_prenom_personnel($id_tlc);
$libelle       = getLibelleById($id_projet, $id_client, $id_application, $id_type_traitement, $id_tlc, $id_fichier);
$tab_libelle   = explode('||', $libelle);
$data_notation = getDateNotationById($notation_id);

if (isset($notation_id) && ($notation_id != '') && ($notation_id != 0)) {
    $appelType = getByIdTypeAppel(trim($notation_id));
}

$libelle = getLibelleById($id_projet, $id_client, $id_application, $id_type_traitement, $id_tlc, $nom_fichier);

$data_notation = explode("|||", $data_notation);

$date_evaluation = isset($data_notation[0]) ? $data_notation[0] : '';
$date_traitement = isset($data_notation[3]) ? $data_notation[3] : '';

if ($date_evaluation == '') {
    $date_evaluation = date('d/m/Y');
}

if ($notation_id != 0) {
    $rws_nc = getFNCGlobalByNotation($notation_id);
    if (!empty($rws_nc)) {
        $description       = explode('>>>', $rws_nc[0]);
        $description_nc_si = isset($description[0]) ? utf8_encode($description[0]) : '';
        $exigence_nc_si    = utf8_encode($rws_nc[1]);
    } else {
        $description_nc_si = '';
        $exigence_nc_si    = '';
    }
} else {
    $description_nc_si = '';
    $exigence_nc_si    = '';
}

$zHtml = "";
$zHtml .= "<table   border=0   cellspacing='6'>";
$nom_client              = isset($tab_libelle[1]) ? $tab_libelle[1] : '';
$prestation              = isset($tab_libelle[2]) ? $tab_libelle[2] : '';
$libelle_type_traitement = isset($tab_libelle[3]) ? $tab_libelle[3] : '';
$zHtml .= "<tr>";
$zHtml .= "<td>Nom du client xxxxxxxxxxxxxxxxxxx:</td><td><input readonly value=" . $nom_client . " class='champ_nc' type ='text' id='id_client' /></td>";
$zHtml .= "</tr>";

$zHtml .= "<tr>";
$zHtml .= "<td>Prestation:</td><td><input readonly value=" . $prestation . " class='champ_nc' type ='text' id='id_prestation' /></td>";
$zHtml .= "</tr>";

if (isset($_REQUEST['notation_id']) && ($_REQUEST['notation_id'] != 0)) {
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
// print_r($_REQUEST);

$id_notat       = $_REQUEST['notation_id'];
$getInfoDossier = getInfoDossier($id_notat);

$toInfoDossier = @pg_fetch_array($getInfoDossier, $i);
$dossier       = "";
$commande      = "";
$dossier       = $toInfoDossier['numero_dossier'];
$commande      = $toInfoDossier['numero_commande'];
$zHtml .= "<tr>";
$zHtml .= "<td>Numéro de dossier: </td><td><input readonly value='" . utf8_encode($dossier) . "' class='champ_nc'  type='text' id='categorie_si' /></td>";
$zHtml .= "</tr>";
$zHtml .= "<tr>";
$zHtml .= "<td>Numéro de commande: </td><td><input readonly value='" . utf8_encode($commande) . "' class='champ_nc'  type='text' id='categorie_si' /></td>";
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

/****************************************************/

$zHtml .= "<tr><td>Gravité / Impact:<span style='color:red;'>*</span></td>
<td>";
$zHtml .= '<input type="text" hidden id="id_grav_" value="0" />
<select id="slctGravite_" name="slctGravite_" class="slct_grav_ champ_nc">
<option value="">***** fa&icirc;tes votre choix *****</option>';
$resGravite = getGravite();
$iNumGrav   = @pg_num_rows($resGravite);

for ($i = 0; $i < $iNumGrav; $i++) {
    $toGravite   = @pg_fetch_array($resGravite, $i);
    $cat_grav_id = $toGravite['id_categorie_grav'];
    $ech_grav    = $toGravite['echelle_id_grav'];
    $lib_grav    = $toGravite['libelle_gravite'];
    $zHtml .= "<option value='" . $cat_grav_id . "'>" . $ech_grav . "_" . utf8_encode($lib_grav) . "</option>";
}

$zHtml .= '</select>';
$zHtml .= "</td></tr>";

$zHtml .= "<tr><td>Fréquence / Probabilité d'occurrence:<span style='color:red;'>*</span></td>
<td>";
$zHtml .= '<input type="text" hidden id="id_freq_" value="0" />
<select id="slctFrequence_" name="slctFrequence_" class="slct_grav_ champ_nc">
<option value="">***** fa&icirc;tes votre choix *****</option>';
$resFrequence = getFrequence();
$iNumFreq     = @pg_num_rows($resFrequence);
for ($i = 0; $i < $iNumFreq; $i++) {
    $toFrequence = @pg_fetch_array($resFrequence, $i);
    $cat_freq_id = $toFrequence['id_categorie_freq'];
    $ech_freq    = $toFrequence['echelle_id_freq'];
    $lib_freq    = $toFrequence['libelle_frequence'];
    $zHtml .= "<option value='" . $cat_freq_id . "'>" . $ech_freq . "_" . utf8_encode($lib_freq) . "</option>";
}

$zHtml .= '</select>';
$zHtml .= "</td></tr>";

$zHtml .= "<tr><td>Criticité : </td>
<td id='critq_'></td>
</tr>";

/****************************************************/

$zHtml .= "<tr>";
if (!empty($rws_nc)) {
    $zHtml .= "<td  colspan='2' align='right'>
	<input  type='button' id='annuler' class='btn_visu' value='Fermer'  onclick='tb_remove();' />
</td>";
} else {
    /*$zHtml .= "<td  colspan='2' align='right'><input class='btn_visu' onclick='insert_fnc_si(".$id_grille.",".$id_grille_application.",".$id_tlc.",".$id_projet.",".$notation_id.");'  style='float:left;margin-left:157px;'  type='button' id='open_fiche' value='Ouvrir la fiche'  /><input  type='button' id='annuler' class='btn_visu' value='annuler'  onclick='tb_remove();' /></td>";*/
    $zHtml .= "<td  colspan='2' align='right'>
<input class='btn_visu' onclick='setValeurforNC();'  style='float:left;margin-left:157px;'  type='button' id='open_fiche' value='Ouvrir la fiche'  />
<input  type='button' id='annuler' class='btn_visu' value='Annuler'  onclick='tb_remove();' /></td>";
}
/*$zHtml .= "<td  colspan='2' align='right'>
<input class='btn_visu' onclick='insert_fnc();'  style='float:left;margin-left:157px;'  type='button' id='open_fiche' value='Ouvrir la fiche'  />
<input  type='button' id='annuler' class='btn_visu' value='annuler'  onclick='tb_remove();' /></td>";*/

$zHtml .= "</tr>";

$zHtml .= "</table>";

echo utf8_decode($zHtml);

?>
<script>
$(document).ready(function(){

	var test_nc = $('#id_test_global_nc').val();
	var description_nc = $('#description_global_nc').val();
	var exigence_nc = $('#exigence_global_nc').val();


	var gravite = $('#gravite').val();
	var frequence = $('#frequence').val();
	var cat_gravite = $('#cat_gravite').val();
	var cat_frequence = $('#cat_frequence').val();
	var criticite = $('#criticite').val();
	if(test_nc == 0 && description_nc != '' && exigence_nc != '')
	{
		$('#description_ecart').val(description_nc);
		$('#exigence_client').val(exigence_nc);

	}

	if(test_nc == 0 && gravite != '' && frequence != '')
	{
	    $('#id_grav_').val(gravite);
		$('#id_freq_').val(frequence);
		$('#slctGravite_').val(gravite);
		$('#slctFrequence_').val(frequence);
		$('#critq_').html(criticite);

		if(criticite == 'mineure')
        $("#critq_").css ( { "backgroundColor":"yellow"},{"color":"#000000"},{"font-weight":"bold" } );
        else if(criticite == 'Majeure')
        $("#critq_").css ( { "backgroundColor":"#FB9228"},{"font-weight":"bold"} );
        else
        $("#critq_").css ( { "backgroundColor":"#FA1D05"},{"color":"#FFFFFF"},{"font-weight":"bold" } );

	}
		$("#slctGravite_").change(function() {
            var grv = $(this).val() ;
            var frq = $("#slctFrequence_").val() ;
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
                  $("#critq_").css ( { "backgroundColor":"yellow"},{"color":"#000000"},{"font-weight":"bold" } );
               else if(i == 'Majeure')
                  $("#critq_").css ( { "backgroundColor":"#FB9228"},{"font-weight":"bold"} );
               else //if(i == 'Critique')
                  $("#critq_").css ( { "backgroundColor":"#FA1D05"},{"color":"#FFFFFF"},{"font-weight":"bold" } );/*
               else
                  $("#critq").css ( { "backgroundColor":"yellow"},{"color":"#FFFFFF"},{"font-weight":"bold" } );*/

                  $("#critq_").html(res[0]);
                  $("#id_grav").val(id_grav);
                  $("#id_freq").val(id_freq);
               }
            );
         }) ;

		 $("#slctFrequence_").change(function() {
            var frq = $(this).val() ;
            var grv = $("#slctGravite_").val() ;
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
                     $("#critq_").css ( { "backgroundColor":"yellow"},{"color":"#000000"},{"font-weight":"bold" } );
               else if(i == 'Majeure')
                  $("#critq_").css ( { "backgroundColor":"#FB9228"},{"font-weight":"bold"} );
               else
                  $("#critq_").css ( { "backgroundColor":"#FA1D05"},{"color":"#FFFFFF"},{"font-weight":"bold" } );
               /*else
                  $("#critq").css ( { "backgroundColor":"yellow"},{"color":"#FFFFFF"},{"font-weight":"bold" } );*/

                  $("#critq_").html(res[0]);
                  $("#id_grav").val(id_grav);
                  $("#id_freq").val(id_freq);
               }
            );
         }) ;

});


</script>

<script>
	function setValeurforNC()
	{

		var description_ecart = $('#description_ecart').val();
		var exigence_client = $('#exigence_client').val();

		var gravite = $('#slctGravite_').val();
		var frequence = $('#slctFrequence_').val();
		var criticite = $('#critq_').html();

		var id_grav_ = $('#id_grav_').val();
		var id_freq_ = $('#id_freq_').val();

		console.log(frequence+"+"+criticite);
		if( description_ecart == '' && exigence_client =='' && gravite == '' && frequence =='')
		{
	        $("#description_ecart").addClass("erreur");
	        $("#exigence_client").addClass("erreur");
			$("#slctGravite_").addClass("erreur");
	        $("#slctFrequence_").addClass("erreur");
			return false;
		}else if( description_ecart =='' ){
			$("#description_ecart").addClass("erreur");
			$("#exigence_client").removeClass("erreur");
			$("#slctGravite_").removeClass("erreur");
	        $("#slctFrequence_").removeClass("erreur");
			return false;
		}else if( exigence_client =='' ){
			$("#description_ecart").removeClass("erreur");
			$("#exigence_client").addClass("erreur");
			$("#slctGravite_").removeClass("erreur");
	        $("#slctFrequence_").removeClass("erreur");
			return false;
		}else if( gravite =='' ){
			$("#description_ecart").removeClass("erreur");
			$("#exigence_client").removeClass("erreur");
			$("#slctGravite_").addClass("erreur");
	        $("#slctFrequence_").removeClass("erreur");
			return false;
		}else if( frequence =='' ){
			$("#description_ecart").removeClass("erreur");
			$("#exigence_client").removeClass("erreur");
			$("#slctGravite_").removeClass("erreur");
	        $("#slctFrequence_").addClass("erreur");
			return false;
		}
		else
		{
			$('#description_global_nc').val(description_ecart);
			$('#exigence_global_nc').val(exigence_client);
			$('#id_test_global_nc').val('0');
			$('#btn_global_annuler_nc').css('display','inline');
			$('#btn_global_editer_nc').css('display','inline');
			$('#btn_global_nc').css('display','none');

			$('#gravite').val( gravite );
			$('#frequence').val( frequence );
			$('#cat_gravite').val( gravite );
			$('#cat_frequence').val( frequence );
			$('#criticite').val( criticite );

			tb_remove();
		}
	}
</script>
