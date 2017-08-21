<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Synthèse des notes</title>
<!--<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>-->

<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>

<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/script_maquette.js"></script>
<script type="text/javascript" src="js/jquery.thickbox.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery.simpletooltip-min.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>
<script type="text/javascript" src="js/jquery.smartTab.js"></script>

<!--script src="js/library/js/jquery-1.11.1.min.js"></script-->
<script src="js/library/js/highcharts.js"></script>
<script src="js/library/js/highcharts-more.js"></script>
<script src="js/library/js/themes/grid_.js"></script>
<!--script src="js/library/js/themes/sand-signika.js"></script-->

<!--by 8120-->
<script src="js/chosen/chosen.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/chosen/chosen.css"></link>
<!--end by 8120-->

<!--<script type="text/javascript" src="js/jquery.smartWizard-2.0.js"></script>
<script type="text/javascript" src="js/jquery.smartTab.js"></script>-->

<script type="text/javascript" src="js/fixedHeader.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"></link>

<link rel="stylesheet" href="./css/ui.core.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.theme.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ui.datepicker.css" type="text/css" media="screen" />

<link rel="stylesheet" type="text/css" href="style_synthese.css"></link>
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/tablesorter.css" type="text/css" media="screen" />

<!--<link href="css/smart_wizard.css" rel="stylesheet" type="text/css">
<link href="css/smart_tab.css" rel="stylesheet" type="text/css"></link>-->

<!--<link rel="stylesheet" type="text/css" href="css/style.css"></link>-->
<link href="css/smart_tab_recap.css" rel="stylesheet" type="text/css"></link>

</head>

 <script>
$(function() {
	
	$("#txt_date_deb").datepicker();
	$("#txt_date_fin").datepicker();
	
	//by 8120
	$("#id_tlc_filtre").chosen();
	$("#slct_auditeur_filtre").chosen();
	$("#id_type_appel_filtre").chosen();
	//end by 8120
	
	$('#tabs').smartTab({selected: 0,autoProgress: false,stopOnFocus:false,autoHeight:false,transitionEffect:'hSlide'}); 	
	$(".stMain ul.tabAnchor").css("display","inline");
	/*$("#tabs-1").css('width','100%');
	$("#tabs-2").css('width','100%');*/
	$("#tabs-3").css('width','100%');
	$("#tabs-4").css('width','100%');
	$("#tabs-5").css('width','100%');
	
/*	
$("#demo_7.tooltiplink").simpletooltip();
$("#demo_7.clic").simpletooltip({click: true});
$("#demo_7.delay").simpletooltip({hideDelay: 0.5});
	/*var availableTags = [
	"ActionScript",
	"AppleScript",
	"Asp",
	"BASIC",
	"C",
	"C++",
	"Clojure",
	"COBOL",
	"ColdFusion",
	"Erlang",
	"Fortran",
	"Groovy",
	"Haskell",
	"Java",
	"JavaScript",
	"Lisp",
	"Perl",
	"PHP",
	"Python",
	"Ruby",
	"Scala",
	"Scheme",
	"Tsilavina best"
	];
	
	$( "#tags" ).autocomplete({
		source: availableTags,
	});*/
	
	//setAutocomplete(0, 0, 0);
     $("#for_recap").click(function()
	 {
	     if( $("#div_recap").css('display') == 'none'   ) 
		 {
		     $(".icon-plus").html('-');
		     $("#div_recap").slideDown( "medium", function() {
                 $("#id_up2").hide();
				
			});
		 }
		 else
		 {
		   $(".icon-plus").html('+');
		   $("#div_recap").slideUp( "medium", function() {
		          
                  if ( $("#test_visu").val() != '' )
				  {				   
			       $("#id_up2").show();				   
				  } 
			});
			

		 }
	    
	 });

	// $("#txt_date").datepicker({
		// changeMonth: true,
		// changeYear: true,
		// dateFormat: "mm/yy",/**yy-mm*/
		// showButtonPanel: true,
		// currentText: "ce mois-ci",
		// onChangeMonthYear: function (year, month, inst) {
			// $(this).val($.datepicker.formatDate('mm/yy', new Date(year, month - 1, 1)));
		// },
		// onClose: function(dateText, inst) {
			// var month = $(".ui-datepicker-month :selected").val();
			// var year = $(".ui-datepicker-year :selected").val();
			// $(this).val($.datepicker.formatDate('mm/yy', new Date(year, month, 1)));
		// }
	// });
	
	$("#txt_date").datepicker();
	$("#txt_date1").datepicker();

		/**$('#txt_date').datepicker({
		  dateFormat: 'yy-mm',
		  changeMonth: true,
		  changeYear: true,
		  showButtonPanel: true,

		  onClose: function (dateText, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).val($.datepicker.formatDate('yy-mm', new Date(year, month, 1)));
		  }
		});
      */
	   // $('#txt_date').focus(function () {
		  // $(".ui-datepicker-calendar").hide();
		  // $("#ui-datepicker-div").position({
			// my: "center top",
			// at: "center bottom",
			// of: $(this)
		  // });
		// });


	$("#affiche_btn").click(function() {
		var date_  = $("#txt_date").val();
		var date_1 = $("#txt_date1").val();
		
		date_  = date_.split("/").reverse().join("-");
		date_1 = date_1.split("/").reverse().join("-");
		
		var t_date_ = date_.split('-');
		var d = new Date(t_date_[0], t_date_[1], t_date_[2]);
		
		var t_date_1 = date_1.split('-');
		var d1 = new Date(t_date_1[0], t_date_1[1], t_date_1[2]);
		
		if(dayDiff(d,d1) < 0) {
			alert("Veuillez sp\351cifier correctement la date de recherche !");
			return 0;
		}
		
		$("#loading_img").show();
		$.post("recap_mens_eval.php",{
			date_      : date_,
			date_1     : date_1,
			test_recap : 1
		},function( data_ ){
			$(".stContainer").addClass('set_z_index');
			$(".tabAnchor").addClass('set_z_index');
			$("#loading_img").hide();
			$("#div_list").html ( data_ );
		});
	});

});//FIN READY FUNCTION
</script>
<style>
ul.ui-autocomplete.ui-menu li a{
    /*color:blue;*/
    font-size: 11px;
    font-family: Verdana;
}
</style>

<script>
function dayDiff(d1, d2){
	d1 = d1.getTime() / 86400000;
	d2 = d2.getTime() / 86400000;
	return new Number(d2 - d1).toFixed(0);
}

function fnscroll(){

//$('#div_2').scrollLeft($('#div_4').scrollLeft($('#div_7').scrollLeft()));
$('#div_2').scrollLeft($('#div_7').scrollLeft());
$('#div_4').scrollLeft($('#div_7').scrollLeft());

$('#div_3').scrollTop($('#div_4').scrollTop());

$('#div_20').scrollLeft($('#div_40').scrollLeft());
$('#div_30').scrollTop($('#div_40').scrollTop());

}

function filtreDonnees(filtre)
{
	var id_client = $('#id_client_filtre').val();
	var id_application = $('#id_prestation_filtre').val();
	var id_projet = $('#id_campagne_filtre').val();
	var id_tlc = $('#id_tlc_filtre').val();
	var id_type_traitement = $('#id_type_traitement_filtre').val();
	var id_fichier = $('#id_fichier_filtre').val();
	var champ_filtre = filtre;
	$.post("function_recap_synthese.php",
	{
		id_client_filtre: id_client,
		id_application_filtre: id_application,
		id_projet_filtre: id_projet,
		id_tlc_filtre: id_tlc,
		id_type_traitement_filtre: id_type_traitement,
		id_fichier_filtre: id_fichier,
		champ_filtre_recap: champ_filtre
	},
	function(data) {
		if(champ_filtre == 'client')
		{
			$('#id_prestation_filtre').html(data);
			/*$('#id_type_appel_filtre').attr('disabled',true);
			$('#id_type_appel_filtre').html('<option value=0>-- Choix --</option>');*/
			$('#id_type_appel_filtre').prop('disabled', true).trigger("chosen:updated");
		}
		else if(champ_filtre == 'code')
		{
			if(data == '0')
			{
				/*$('#id_type_appel_filtre').attr('disabled',true);
				$('#id_type_appel_filtre').html('<option value=0>-- Choix --</option>');*/
				$('#id_type_appel_filtre').prop('disabled', true).trigger("chosen:updated");
			}
			else
			{
				var _data = data.split('||');
				$('#id_client_filtre').val(_data[0]);
				$('#id_campagne_filtre').val(_data[1]);
				if(parseInt(_data[2]) == 0)
				{
					/*$('#id_type_appel_filtre').attr('disabled',true);
					$('#id_type_appel_filtre').html('<option value=0>-- Choix --</option>');*/
					$('#id_type_appel_filtre').prop('disabled', true).trigger("chosen:updated");
				}
				else
				{
					//$('#id_type_appel_filtre').attr('disabled',false);
					$('#id_type_appel_filtre').html(_data[2]);
					$('#id_type_appel_filtre').prop('disabled', false).trigger("chosen:updated");
				}	
			}
		}
	});
}

function reinitialisationDonnees()
{
	$.post("function_recap_synthese.php",
	{
		reinitialisation : 1
	},
	function(data) {
		// projet ||| client ||| application ||| type_traitement ||| fichier
		var tab = data.split('|||');
		if(tab[0] != '')
		{
			$('#id_campagne_filtre').html(tab[0]);
		}
		if(tab[1] != '')
		{
			$('#id_client_filtre').html(tab[1]);
		}
		if(tab[2] != '')
		{
			$('#id_prestation_filtre').html(tab[2]);
		}
		if(tab[3] != '')
		{
			$('#id_type_traitement_filtre').html(tab[3]);
		}
		if(tab[4] != '')
		{
			$('#id_fichier_filtre').val('');
		}
		if(tab[5] != '')
		{
			$('#id_tlc_filtre').val(tab[5]);
		}
		$("#slct_auditeur_filtre").val(0);
		var today = new Date();
		$("#txt_date_deb").val('');
		$("#txt_date_fin").val('');
	});
}

function afficheNotation()
{
	var id_client = $('#id_client_filtre').val();//alert('client:'+id_client);
	var id_application = $('#id_prestation_filtre').val();
	var id_projet = $('#id_campagne_filtre').val();
	var id_tlc = rep_chosen_nb($('#id_tlc_filtre').val());//alert('cc:'+id_tlc);
	var matricule_auditeur = rep_chosen_nb($('#slct_auditeur_filtre').val());//alert('audit:'+matricule_auditeur);
	var id_type_traitement = $('#id_type_traitement_filtre').val();
	var date_deb_notation = $('#txt_date_deb').val();
	var date_fin_notation = $('#txt_date_fin').val();
	var id_type_appel = rep_chosen_nb($('#id_type_appel_filtre').val());//alert('type_app:'+id_type_appel);
	var id_note = $('#id_note_filtre').val();
	var id_note_1 = $('#id_valeur_note_1').val();
	var id_note_2 = $('#id_valeur_note_2').val();
	//var id_fichier = decodeURIComponent($('#id_fichier_filtre').val());
	//var idfichier = $('#idfichierfiltre').val();
	//if(id_projet == 0 || id_client == 0 || id_application == 0 || id_tlc == 0 || id_fichier == '' || id_type_traitement == 0)
	$("#div_recap").hide();
	if(id_projet == 0 || id_client == 0 || id_application == 0 || id_type_traitement == 0)
	{
		alert('Veuillez remplir les champs obligatoires !!');
		return false;
	}
	else
	{
		$('#img_loading').show();
		$.post("synthese_dynamique_.php",
		{
			id_projet_recap : id_projet,
			id_client_recap : id_client,
			id_application_recap : id_application,
			id_type_traitement_recap : id_type_traitement,
			id_tlc_recap : id_tlc,
			matricule_auditeur_recap : matricule_auditeur,
			date_deb_notation_recap : date_deb_notation,
			date_fin_notation_recap : date_fin_notation,
			id_type_appel_recap : id_type_appel,
			id_note : id_note,
			id_note_1 : id_note_1,
			id_note_2 : id_note_2,
			affiche: 1
		},
		function(data) {
			var reponse = data.split('|||');
			$("#id_contenu_by_filtre").css("display","block");
			$("#tabs-1").html(reponse[0]); // reponse[0] tableau sans fixeheader par TLC
			$("#tabs-2").html(reponse[1]); // reponse[1] tableau sans fixeheader pour tous les prestations
			$("#tabs-3").html(reponse[2]); // reponse[2] tableau avec fixeheader par TLC
			$("#tabs-4").html(reponse[3]); // reponse[3] tableau avec fixeheader pour tous les prestations
			//$("#tabs-3").html(reponse[2]+reponse[4]); // reponse[4] highchart par TLC
			//$("#tabs-4").html(reponse[3]+reponse[5]); // reponse[5] highchart pour tous les prestations
			$("#tabs-5").html(reponse[6]); // reponse[6] Tableau récapitulatif des notations
			
			$('.titre').html(reponse[7]); //Titre récapitulatif pour chaque onglet
			$('#id_div_export_reporting').html(reponse[8]); //Champ export excel
			$('#tabs-4 .titre #id_affiche2').attr('hidden','hidden');
			$('#tabs-4 .titre #id_affiche3').attr('hidden','hidden');
			
			/**$('#date_deb_export_reporting').datepicker({
			  constrainInput: true,   // prevent letters in the input field
			  //minDate: new Date(),    // prevent selection of date older than today
			  //showOn: 'button',       // Show a button next to the text-field
			  autoSize: true,         // automatically resize the input field 
			  altFormat: 'yy-mm-dd',  // Date Format used
			  //beforeShowDay: $.datepicker.noWeekends,     // Disable selection of weekends
			  beforeShowDay: function(date)
			       { return [(date.getDay() == 1), ""]; },
			  firstDay: 1 // Start with Monday
			});
			*/
			$('#date_deb_export_reporting').datepicker();
			$('#date_fin_export_reporting').datepicker();
			setstylefortable(reponse);
			
			var resizeTimer;
			clearTimeout(resizeTimer);
    		resizeTimer = setTimeout(doSomething, 100);

			$('#img_loading').hide();
			
			$('#id_affiche_filtre').css("display","none");
			$('#id_div_export_reporting').css("display","block");
			$('#id_down2').css("display","block");
			$('#id_up2').css("display","none");
			$('#accordion').hide();
			$("#test_visu").val(1);
			$(".icon-plus").html('+');
		});
		
	}
}

function afficheFiltreNote()
{
	var id_note = $('#id_note_filtre').val();
	var id_note_1 = $('#id_valeur_note_1').val();
	var id_note_2 = $('#id_valeur_note_2').val();
	if(id_note == 0)
	{
		$('#id_valeur_note_1').css('visibility','hidden');
		$('#id_valeur_note_2').css('visibility','hidden');
		$('#id_et').css('visibility','hidden');
	}
	else if(id_note == 2)
	{
		$('#id_valeur_note_1').css('visibility','visible');
		$('#id_valeur_note_2').css('visibility','visible');
		$('#id_et').css('visibility','visible');
	}
	else
	{
		$('#id_valeur_note_1').css('visibility','visible');
		$('#id_valeur_note_2').css('visibility','hidden');
		$('#id_et').css('visibility','hidden');
	}
}

function cacher_filtre()
{
	$('#id_affiche_filtre').css("display","none");
	$('#id_div_export_reporting').css("display","block");
	$('#id_down2').css("display","block");
	$('#id_up2').css("display","none");
	$('#accordion').hide();
}

function afficher_filtre()
{
	$('#id_affiche_filtre').css("display","block");
	$('#id_div_export_reporting').css("display","none");
	$('#id_down2').css("display","none");
	$('#id_up2').css("display","block");
	$('#accordion').show();
}

//On suppose que la date entrée a été validée auparavant
//au format dd/mm/yyyy
function getDate(strDate){	  
	day = strDate.substring(0,2);
	month = strDate.substring(3,5);
	year = strDate.substring(6,10);
	d = new Date();
	d.setDate(day);
	d.setMonth(month);
	d.setFullYear(year); 
	return d;  
}

//Retourne:
//   0 si date_1=date_2
//   1 si date_1>date_2
//  -1 si date_1<date_2	  
function compare(date_1, date_2){
	diff = date_1.getTime()-date_2.getTime();
	return (diff==0?diff:diff/Math.abs(diff));
}

//by 8120

function exportation_direct(){
	var id_projet = $('#id_campagne_filtre').val();
	var id_client = $('#id_client_filtre').val();
	var id_application = $('#id_prestation_filtre').val();
	var id_type_traitement = $('#id_type_traitement_filtre').val();
	var matricule_tlc = /*0;*/rep_chosen_nb($('#id_tlc_filtre').val());
	var matricule_auditeur = /*0;*/rep_chosen_nb($('#slct_auditeur_filtre').val());
	var verif = 1;
	var id_type_appel = /*0;*/rep_chosen_nb($('#id_type_appel_filtre').val());
	if(id_client == 0 || id_application == 0 || id_type_traitement == 0){
		alert("Veuillez remplir les champs obligatoires!!");
	}else{
		export_reporting(id_projet,id_client,id_application,id_type_traitement,matricule_tlc,matricule_auditeur,verif,id_type_appel);
	}
}
//end by 8120

function export_reporting(id_projet,id_client,id_application,id_type_traitement,matricule_tlc,matricule_auditeur,verif,id_type_appel)
{
	//alert('Export excel en cours de développement !!');
	var date_deb = $('#date_deb_export_reporting').val();
	if(date_deb == undefined || !verifie_datefr(date_deb)){
		date_deb = $('#txt_date_deb').val();
	}
	var date_fin = $('#date_fin_export_reporting').val();
	if(date_fin == undefined || !verifie_datefr(date_fin)){
		date_fin = $('#txt_date_fin').val();
	}
	var arr_date_deb = date_deb.split('/');
	var arr_date_fin = date_fin.split('/');
	var compare_date_deb = new Date(arr_date_deb[2]+'/'+arr_date_deb[1]+'/'+arr_date_deb[0]);
	var compare_date_fin = new Date(arr_date_fin[2]+'/'+arr_date_fin[1]+'/'+arr_date_fin[0]);
	//alert(arr_date_deb[2]+'-'+arr_date_deb[1]+'-'+arr_date_deb[0]+' , '+compare_date_fin)
	     if(date_deb == '' && date_fin == ''){
		      alert("Veuillez renseigner les deux dates");
			  return false;
		 }else if( date_deb=='' ){
		      alert("Veuillez renseigner la date debut");
			  return false;
		 }else if( date_fin=='' ){
		      alert("Veuillez renseigner la date fin");	
			  return false;
		 }else {  //if(date_deb > date_fin){
		 	  //alert(compare(getDate(date_fin),getDate(date_deb)));
		 	//  var diff = compare_date(getDate(date_fin),getDate(date_deb));
		 	  var diff = compare_date(compare_date_deb,compare_date_fin);
		 	  //alert(diff.day+' , '+compare_date_deb+' , '+compare_date_fin);
			  if(diff.day < 0)
			  {
			  		alert("Chevauchement des dates");	
			  		return false;
			  }
			  /*else
			  {
			  		alert(diff+"OK");	
			  		return false;
			  }*/
		 }
	var id_projet = id_projet;
	var id_client = id_client;
	var id_application = id_application;
	var id_type_traitement = id_type_traitement;
	var matricule_tlc = matricule_tlc;
	var matricule_auditeur = matricule_auditeur;
	var id_type_appel = id_type_appel;
	//window.location('export_reporting.php?date_deb='+date_deb+'date_fin='+date_deb+'projet='+id_projet+'client='+id_client+'application='+id_application+'type_traitement='+id_type_traitement+'tlc='+matricule_tlc+'auditeur='+matricule_auditeur);
	if(verif == 1)
	{
	window.location.href = 'export_reporting.php?date_deb='+date_deb+'&date_fin='+date_fin+'&projet='+id_projet+'&client='+id_client+'&application='+id_application+'&type_traitement='+id_type_traitement+'&tlc='+matricule_tlc+'&auditeur='+matricule_auditeur+'&id_type_appel='+id_type_appel;
	}else if(verif == 2){
		//http://192.168.10.24/gpao2/cc_sr_v7/
		//alert('En cours de développement !');
	   	window.location.href = 'export_reporting_global_.php?date_deb='+date_deb+'&date_fin='+date_fin+'&projet='+id_projet+'&client='+id_client+'&application='+id_application+'&type_traitement='+id_type_traitement+'&tlc='+matricule_tlc+'&auditeur='+matricule_auditeur;
	}
	else if(verif == 3){
		//alert('En cours de développement !');
	   	window.location.href = 'export_tdb.php?date_deb='+date_deb+'&date_fin='+date_fin+'&projet='+id_projet+'&client='+id_client+'&application='+id_application+'&type_traitement='+id_type_traitement+'&tlc='+matricule_tlc+'&auditeur='+matricule_auditeur+'&sortie=total';
	}
	else if(verif == 4){
		//alert('En cours de développement !');
	   	window.location.href = 'export_tdb.php?date_deb='+date_deb+'&date_fin='+date_fin+'&projet='+id_projet+'&client='+id_client+'&application='+id_application+'&type_traitement='+id_type_traitement+'&tlc='+matricule_tlc+'&auditeur='+matricule_auditeur+'&sortie=semaine';
	}
	else if(verif == 5){
		//alert('En cours de développement !');
	   	window.location.href = 'export_tdb.php?date_deb='+date_deb+'&date_fin='+date_fin+'&projet='+id_projet+'&client='+id_client+'&application='+id_application+'&type_traitement='+id_type_traitement+'&tlc='+matricule_tlc+'&auditeur='+matricule_auditeur+'&id_type_appel='+id_type_appel+'&sortie=mois';
	}
}

//hng
function compare_date(date1, date2){
    var diff = {}                           // Initialisation du retour
    var tmp = date2 - date1;
 
    tmp = Math.floor(tmp/1000);             // Nombre de secondes entre les 2 dates
    diff.sec = tmp % 60;                    // Extraction du nombre de secondes
 
    tmp = Math.floor((tmp-diff.sec)/60);    // Nombre de minutes (partie entière)
    diff.min = tmp % 60;                    // Extraction du nombre de minutes
 
    tmp = Math.floor((tmp-diff.min)/60);    // Nombre d'heures (entières)
    diff.hour = tmp % 24;                   // Extraction du nombre d'heures
     
    tmp = Math.floor((tmp-diff.hour)/24);   // Nombre de jours restants
    diff.day = tmp;
     
    return diff;
}

function export_reporting_direct(id_projet,id_client,id_application,id_type_traitement,matricule_tlc,matricule_auditeur,verif)
{
	//alert('Export excel en cours de développement !!');
	var date_deb = $('#txt_date_deb').val();
	var date_fin = $('#txt_date_fin').val();
	var tab_date_deb = date_deb.split('/');
	var tab_date_fin = date_fin.split('/');
	var date_deb_compare = tab_date_deb[2]+'-'+tab_date_deb[1]+'-'+tab_date_deb[0];
	var date_fin_compare = tab_date_fin[2]+'-'+tab_date_fin[1]+'-'+tab_date_fin[0];
//	alert(date_deb_compare+"##"+date_fin_compare);
	     if(date_deb == '' && date_fin == ''){
		      alert("Veuillez renseigner les deux dates");
			  return false;
		 }else if( date_deb=='' ){
		      alert("Veuillez renseigner la date debut");
			  return false;
		 }else if( date_fin=='' ){
		      alert("Veuillez renseigner la date fin");	
			  return false;
		 }else {  //if(date_deb > date_fin){
		 	  //alert(compare(getDate(date_fin),getDate(date_deb)));
		 	  var diff = compare(getDate(date_fin_compare),getDate(date_deb_compare));
			  /**if(diff < 0)
			  {
			  		alert("Chevauchement des dates");	
			  		return false;
			  }*/
			  if( (new Date(date_deb_compare).getTime() > new Date(date_fin_compare).getTime()))
				{
				  alert("Chevauchement des dates");	
			  		return false;
				}
			  /*else
			  {
			  		alert(diff+"OK");	
			  		return false;
			  }*/
		 }
	var id_projet = $('#id_campagne_filtre').val();
	var id_client = $('#id_client_filtre').val();
	var id_application = $('#id_prestation_filtre').val();
	var id_type_traitement = $('#id_type_traitement_filtre').val();
	var matricule_tlc = $('#id_tlc_filtre').val();
	var matricule_auditeur = $('#slct_auditeur_filtre').val();
	
	if(verif == 1)
	{
	window.location.href = 'export_reporting.php?date_deb='+date_deb+'&date_fin='+date_fin+'&projet='+id_projet+'&client='+id_client+'&application='+id_application+'&type_traitement='+id_type_traitement+'&tlc='+matricule_tlc+'&auditeur='+matricule_auditeur;
	}else if(verif == 2){
		//http://192.168.10.24/gpao2/cc_sr_v7/
		//alert('En cours de développement !');
	   	window.location.href = 'export_reporting_global_.php?date_deb='+date_deb+'&date_fin='+date_fin+'&projet='+id_projet+'&client='+id_client+'&application='+id_application+'&type_traitement='+id_type_traitement+'&tlc='+matricule_tlc+'&auditeur='+matricule_auditeur;
	}
	else if(verif == 3){
		//alert('En cours de développement !');
	   	window.location.href = 'export_tdb.php?date_deb='+date_deb+'&date_fin='+date_fin+'&projet='+id_projet+'&client='+id_client+'&application='+id_application+'&type_traitement='+id_type_traitement+'&tlc='+matricule_tlc+'&auditeur='+matricule_auditeur+'&sortie=total';
	}
	else if(verif == 4){
		//alert('En cours de développement !');
	   	window.location.href = 'export_tdb.php?date_deb='+date_deb+'&date_fin='+date_fin+'&projet='+id_projet+'&client='+id_client+'&application='+id_application+'&type_traitement='+id_type_traitement+'&tlc='+matricule_tlc+'&auditeur='+matricule_auditeur+'&sortie=semaine';
	}
	else if(verif == 5){
		//alert('En cours de développement !');
	   	window.location.href = 'export_tdb.php?date_deb='+date_deb+'&date_fin='+date_fin+'&projet='+id_projet+'&client='+id_client+'&application='+id_application+'&type_traitement='+id_type_traitement+'&tlc='+matricule_tlc+'&auditeur='+matricule_auditeur+'&sortie=mois';
	}
}

function setstylefortable(reponse)
{
	//Synthese par TLC
	$('#div_7').bind('scroll', fnscroll);
	$('#div_4').bind('scroll', fnscroll);
	if(parseInt(reponse[0]) != 1)
	{
		$("#div_1").html(reponse[0]);
		$("#div_2").html(reponse[0]);
		$("#div_3").html(reponse[0]);
		$("#div_4").html(reponse[0]);
		$("#div_6").html(reponse[0]);
		$("#div_7").html(reponse[0]);
		//$("#div_6 table thead tr").attr('hidden','hidden');
		$("#div_3 table tfoot").attr('hidden','hidden');
		$("#div_6 table tbody").attr('hidden','hidden');
		//$("#div_7 table thead tr").attr('hidden','hidden');
		//$("#div_4 table tbody tr td.class_is_total span").css('width','48px');
		$("#div_4 table tfoot").attr('hidden','hidden');
		$("#div_2 table tfoot").attr('hidden','hidden');
		$("#div_7 table tbody").attr('hidden','hidden');
	}
	else
	{
		var afficher = '<center><span style="color:red;font-size:12px;">Aucune donnée</span></center>';
		$("#div_3").html(afficher);
	}
	
	$('#div_2 .first').css('display','none');
	$('#div_1 .table_by_tlc').css('margin','2px auto');
	$('#div_2 .table_by_tlc').css('margin','2px auto');
	$('#div_3 .table_by_tlc').css('margin-top','-74px');
	$('#div_4 .table_by_tlc').css('margin-top','-74px');
	$('#div_4 .table_by_tlc').css('margin-left','-330px');
	$('#div_6 .table_by_tlc').css('margin-top','-73px');
	$('#div_7 .table_by_tlc').css('margin-top','-73px');
	$('#div_7 .table_by_tlc').css('margin-left','-330px');
	
	//Synthese par Prestation
	$('#div_40').bind('scroll', fnscroll);
	if(parseInt(reponse[1]) != 1)
	{
		$("#div_10").html(reponse[1]);
		$("#div_20").html(reponse[1]);
		$("#div_30").html(reponse[1]);
		$("#div_40").html(reponse[1]);
	}
	else
	{
		var afficher = '<center><span style="color:red;font-size:12px;">Aucune donnée</span></center>';
		$("#div_30").html(afficher);
	}
	
	$('#div_20 .first').css('display','none');
	$('#div_10 .table_by_tlc').css('margin','2px auto');
	$('#div_20 .table_by_tlc').css('margin','2px auto');
	$('#div_30 .table_by_tlc').css('margin-top','-64px');
	$('#div_40 .table_by_tlc').css('margin-top','-64px');
	$('#div_40 .table_by_tlc').css('margin-left','-366px');
}

function isNormalCharacter(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	console.log(charCode);
	if (charCode ==38 || charCode ==34 || charCode ==96 || charCode ==176|| charCode ==35  ) {
		return false;
	}
	return true;
 }
 
 /*function doSomething() {
   // alert("I'm done resizing for the moment");
  console.log($(window).width());
  var longueur = $(window).width() - 30;
  $('#id_contenu_by_filtre').css('width',longueur+'px');
  $('#id_contenu_by_filtre').css('margin','auto');
  $('#tabs').css('width',longueur+'px');
  $('#stContainer').css('width',longueur+'px');
  
  $("#div_principal").css('width',longueur+'px');
  $("#div_principal").css('margin','auto');
  $("#div_2").css('width','auto');
  $("#div_principal0").css('width',longueur+'px');
  $("#div_principal0").css('margin','auto');
  $("#div_20").css('width','auto');*/
  
  function doSomething() {
	  var longueur = $(window).width() - 30;
	  $('#id_contenu_by_filtre').css('width',longueur+'px');
	  $('#id_contenu_by_filtre').css('margin','auto');
	  $('#tabs').css('width',longueur+'px');
	  $('#stContainer').css('width',longueur+'px');
	  
	  $("#div_principal").css('margin','auto 20px');
	  $("#div_principal").css('width',(longueur-(20*2))+'px');
	  $("#div_2").css('width','auto');
	  
	  $("#div_principal0").css('margin','auto 20px');
	  $("#div_principal0").css('width',(longueur-(20*2))+'px');
	  
	  var l10 = $("#div_10").width();
	  var lp0 = $("#div_principal0").width();
	  $("#div_20").css('width',(lp0-l10-16)+'px');
  };

function recap_mens_eval(){
	tb_show("Recapitulatif mensuel","recap_mens_eval.php?height=300&width=1000");
}

function export_recap_mensuel_direct(){
	var t_deb = $("#txt_date").val();
	var deb   = t_deb.split("/").reverse().join("-");
	var t_fin = $("#txt_date1").val();
	var fin   = t_fin.split("/").reverse().join("-");
	
	var t_date_ = deb.split('-');
	var d = new Date(t_date_[0], t_date_[1], t_date_[2]);

	var t_date_1 = fin.split('-');
	var d1 = new Date(t_date_1[0], t_date_1[1], t_date_1[2]);

	if(dayDiff(d,d1) < 0) {
		alert("Veuillez sp\351cifier correctement la date de recherche !");
		return 0;
	}
	
	export_recap_mensuel(deb,fin);
}

function export_recap_mensuel(date_deb,date_fin){
	window.location.href = 'export_recap_mensuel.php?date_deb='+date_deb+'&date_fin='+date_fin;
}

var resizeTimer;
$(window).resize(function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(doSomething, 100);
});

//by 8120
function rep_chosen_nb(valeur){
	valeur = valeur == null ? 0 : valeur;
	var reponse = '';
	if(valeur != 0){
		var n = valeur.length;
        for (var i = 0; i < n; i++) {
            reponse += valeur[i];
            if ((i + 1) < n) {
                reponse += ',';
            }
        }
	}else{
		reponse = valeur;
	}
	return reponse;
}

function rep_chosen(valeur){
	valeur = valeur == null ? 0 : valeur;
	var reponse = '';
	if(valeur != 0){
		var n = valeur.length;
        for (var i = 0; i < n; i++) {
            reponse += "'" + valeur[i] + "'";
            if ((i + 1) < n) {
                reponse += ',';
            }
        }
	}else{
		reponse = valeur;
	}
	return reponse;
}

function verifie_datefr(val){
	rep = false;
	var reg = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
	if(reg.test($.trim(val))){
		rep = true;
	}
	return rep;
}
//end by 8120
 
</script>
<body>
<div id="main">
  <div id="contentbg">
    <div id="contenttxtblank">
	   <div id="menu">
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
		?>
        <ul>
<?php
if( isset($matricule_session) && in_array($matricule_session,$matNotation))
 {
?>
		   <li><a href="filtre_dynamique.php" <?php if($cur_addr=='filtre_dynamique.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Notation</a></li>
		    <li class="menusap"></li>
            <li><a href="filtre_dynamique_exportation.php" <?php if($cur_addr=='filtre_dynamique_exportation.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Export-Notation</a></li>
            <li class="menusap"></li>
		   
<?php
 } 

if( isset($matricule_session) && in_array($matricule_session,$matAdmin))
 {
?>		   
		  <li><a href="interface.php" <?php if($cur_addr=='interface.php' ) echo 'class="active"'; else echo 'class="menu"'; ?> >Projet</a></li>
<?php
  }
?>
          <!--<li><a href="nb_reecoute.php" <?php if($cur_addr=='nb_reecoute.php') echo 'class="active"'; else echo 'class="menu"';?>>Suivi</a></li>-->
          <li class="menusap"></li>
          <?php
		 /*if ( in_array( $fct, $tFctAuthoriseInit )  )
		  {*/
		  ?>
          <!--<li><a href="index.php" <?php if($cur_addr=='index.php' || $cur_addr=='' ) echo 'class="active"'; else echo 'class="menu"'; ?> >&eacute;coute</a></li>-->
          <li class="menusap"></li>
<?php
if( isset($matricule_session) && in_array($matricule_session,$matNotation))
 {
?>
          <li><a  href="recap_synthese.php" <?php if($cur_addr=='recap_synthese.php') echo 'class="active"'; else echo 'class="menu"';?>>Synth&egrave;ses</a></li>
<?php
 } 
?>
          <li class="menusap"></li>
          <!--<li><a href="indicateur_nf.php" <?php if($cur_addr=='indicateur_nf.php') echo 'class="active"'; else echo 'class="menu"';?>>Indicateurs</a></li>-->
          <li class="menusap"></li>
		  
          <li class="menusap"></li>
          <?php
		  //}
		  ?>
        </ul>
       </div>
	  </div>
	 </div>  
	 
<div class='acc_container' style="overflow:visible;">
<div class='block' id="id_affiche_filtre" style="/*height:282px;*/">
<!--<a href="#" class="recap_button" onclick="recap_mens_eval();">recapitulatif</a>-->
<?php
include('function_recap_synthese.php');
/*$id_projet = 51;
$id_client = 599;
$id_application = 408;
$id_fichier = 1877;
$id_type_traitement = 1;*/
?>
<div style="border:0px solid red;width:64%;margin:auto;display:block;">

<table style="font-size:11px;font-family:Verdana;text-align:left;">
<!------------------------------------------------------------>
<tr>
<th>Date d&eacute;but appel :<span class="requise">*</span></th>
<td><input type="text" id="txt_date_deb" name="txt_date_deb" style="width:344px;margin:0 0 0 3px" value="<?php echo date('01/m/Y'); ?>" /></td>
</tr>
<!------------------------------------------------------------>
<tr>
<th>Date fin appel :<span class="requise">*</span></th>
<td><input type="text" id="txt_date_fin" name="txt_date_fin" style="width:344px;margin:0 0 0 3px" value="<?php echo date('d/m/Y'); ?>" /></td>
</tr>
<!------------------------------------------------------------>
<tr>
	<td colspan="2">
	<div style="display: block; margin: 5px auto; position: relative; width: 80%;">
			<span style="color: #18484f;display: block;float: left;font-family: Tahoma;font-size: 10px;font-weight: bold;margin: 7px 0;">Synthèse Macro</span>

			<img id="img_export_reporting_global" style="cursor: pointer;display: block;float: left;margin-right: 15px;" title="Export synth&egrave;se Macro" src="images/excel2.png" width="23" height="26" onclick="export_reporting_direct(0,0,0,0,0,0,2);"/>

			<span style="color: #18484f;display: block;float: left;font-family: Tahoma;font-size: 10px;font-weight: bold;margin: 7px 0;">TDB</span>

			<img id="img_export_tdb" style="cursor: pointer;display: block;float: left;margin-right: 15px;" title="Export Tableau de Bord" src="images/excel2.png" width="23" height="26" onclick="export_reporting_direct(0,0,0,0,0,0,3);"/>

			<span style="color: #18484f;display: block;float: left;font-family: Tahoma;font-size: 10px;font-weight: bold;margin: 7px 0;">TDB Hebdo</span>

			<img id="img_export_tdb" style="cursor: pointer;display: block;float: left;margin-right: 15px;" title="Export Tableau de Bord Hebdomadaire" src="images/excel2.png" width="23" height="26" onclick="export_reporting_direct(0,0,0,0,0,0,4);"/>

			<span style="color: #18484f;display: block;float: left;font-family: Tahoma;font-size: 10px;font-weight: bold;margin: 7px 0;">TDB Mensuel</span>

			<img id="img_export_tdb" style="cursor: pointer;display: block;float: left;margin-right: 15px;" title="Export Tableau de Bord Mensuel" src="images/excel2.png" width="23" height="26" onclick="export_reporting_direct(0,0,0,0,0,0,5);"/>
			
	</div>
	</td>
</tr>		
<!------------------------------------------------------------>		
<tr>
<th>Nom du client :<span class="requise">*</span> </th>
<td>
<select class="class_select" id="id_client_filtre" onchange="filtreDonnees('client');">
<?php
echo '<option value="0">-- Choix --</option>';
$result_client = fetchAllProject_recap('client');
$tab_client = array();
while ($res_client = pg_fetch_array($result_client))
{
	if(!in_array($res_client['id_client'],$tab_client))
	{
		echo '<option value="'.$res_client['id_client'].'">'.$res_client['nom_client'].'</option>';
		array_push($tab_client,$res_client['id_client']);
	}
}
?>
</select>
</td>
</tr>
<!------------------------------------------------------------>
<tr>
<th>Prestation :<span class="requise">*</span></th>
<td>
<select class="class_select" id="id_prestation_filtre" onchange="filtreDonnees('code');">
<?php
echo '<option value="0">-- Choix --</option>';
$result_presta = fetchAllProject_recap('application');
while ($res_presta = pg_fetch_array($result_presta))
{
	echo '<option value="'.$res_presta['id_application'].'">'.$res_presta['code'].' - '.$res_presta['nom_application'].'</option>';
}
?>
</select>
</td>
</tr>
<!------------------------------------------------------------>
<!--<tr>
<th>Campagne : </th>
<td>-->
<select class="class_select" id="id_campagne_filtre" hidden>
<?php
echo '<option value="0">-- Choix --</option>';
$result_project = fetchAllProject_recap('projet');
while ($res_projet = pg_fetch_array($result_project))
{
	echo '<option value="'.$res_projet['id_projet'].'">'.$res_projet['nom_projet'].'</option>';
}
?>
</select>
<!--
</td>
</tr>-->
<!------------------------------------------------------------>
<tr>
<th>Type de traitement :<span class="requise">*</span></th>
<td>
<select class="class_select" id="id_type_traitement_filtre">
<?php
echo '<option value="0">-- Choix --</option>';
$result_type = fetchAllTypeTraitement_recap();
while ($res_type = pg_fetch_array($result_type))
{
	echo '<option value="'.$res_type['id_type_traitement'].'">'.$res_type['libelle_type_traitement'].'</option>';
}
?>
</select>
</td>
</tr>
<!------------------------------------------------------------>
<tr>
<th>Matricule : </th>
<td>
<select multiple class="class_select" id="id_tlc_filtre">
<?php
//echo '<option value="0">-- Choix --</option>';
//$result_tlc = fetchAllTLC_recap();
$result_tlc = fetchAllTLC_recap_();
while ($res_tlc = pg_fetch_array($result_tlc))
{
	$fct = $res_tlc['fonctioncourante'];
	if($res_tlc['actifpers'] == 'Partie')
	{		
		$prenom = '(Inactif)';
		echo '<option class="class_select_option" value="'.$res_tlc['matricule'].'">'.$res_tlc['matricule'].' - '.$prenom.' ('.$fct.')</option>';
	}
	else
	{
		$prenom = $res_tlc['prenompersonnel'];
		echo '<option value="'.$res_tlc['matricule'].'">'.$res_tlc['matricule'].' - '.$prenom.' ('.$fct.')</option>';
	}
}
?>
</select>
</td>
</tr>
<!------------------------------------------------------------>
<tr>
	<th>Auditeur :</th>
	<td>
	<select multiple class="class_select" name='slct_auditeur'  id='slct_auditeur_filtre'>
		<?php
			//$zoptaudit = "<option value='0'>-- Choix --</option>" ;
			$zoptaudit = "";
			//$zoptaudit .= "<option value='6211'>6211 - Mamy Tsilavina</option>" ;
			//$zoptaudit .= "<option value='6568'>6568 - Njivaniaina</option>" ;
			//$result_audit = fetchAllAuditeur_recap();
			$result_audit = fetchAllAuditeur_recap_evaluateur();
			while ($res_audit = pg_fetch_array($result_audit))
			{
                $zPrenompers = ucfirst(strtolower($res_audit['prenompersonnel']));
				$zoptaudit .= "<option value='".$res_audit['matricule']."'>".$res_audit['matricule']." - ".$zPrenompers." - ".$res_audit['fonctioncourante']."</option>" ;
				
			}
			echo $zoptaudit;
		?>
	</select>
	</td>
</tr>

<!------------------------------------------------------------>
<tr>
<th>Type d'appel :</th>
<td>
<select multiple class="class_select" id="id_type_appel_filtre" disabled>
<?php
//echo '<option value="0">-- Choix --</option>';
?>
</select>
</td>
</tr>
<!------------------------------------------------------------>
<!--<tr>
<th>Note :</th>
<td>
<select style="background-color: #efefef;display: block;float: left;font-size: 10px;margin: 3px;position: relative;width: 125px;" id="id_note_filtre" onchange=afficheFiltreNote();>
<option value=0>-- Choix --</option>
<option value=1>Egal à</option>
<option value=2>Entre</option>
<option value=3>Inférieur à</option>
<option value=4>Inférieur ou Egal à</option>
<option value=5>Supérieur à</option>
<option value=6>Supérieur ou Egal à</option>
</select>
<div style="border: 0px solid #000000;display: block;float: left;height: 17px;margin: 3px;position: relative;width: 216px;" id="id_div_note_filtre">
	<input type="text" id="id_valeur_note_1" style="visibility:hidden;background-color: #efefef;font-size: 10px;height: 15px;text-align: center;width: 94px;" value=0 />
	<span id="id_et" style="visibility:hidden;"> et </span>
	<input type="text" id="id_valeur_note_2" style="visibility:hidden;background-color: #efefef;font-size: 10px;height: 15px;text-align: center;width: 94px;" value=0 />
</div>
</td>
</tr>-->
<!--
<tr>
<th><label for="tags">Fichier: </label></th>
<td>
<input type="text" id="idfichierfiltre" hidden />
<input type="text" onkeypress="return isNormalCharacter(event);"  placeholder="Fichier" id="id_fichier_filtre" style="width:344px;margin:0 0 0 3px" />-->
<?php
/*
echo '<option value="0">-- Choix --</option>';
$result_fichier = fetchAllFichierInit_recap();
while ($res_fichier = pg_fetch_array($result_fichier))
{
	echo '<option value="'.$res_fichier['id_fichier'].'">'.$res_fichier['nom_fichier'].'</option>';
}
*/
?>
<!--
</td>
</tr>-->
<!------------------------------------------------------------>

<tr>
<td colspan="2" style="text-align:center;">
	<div style="display:block;margin:auto;width:100%;">
		<input class="btn_visu" type="button" value="Réinitialisation" title="Réinitialisation du filtre" onclick="reinitialisationDonnees();" style="display:block;position:relative;float:right;" />
		<span style="display:block;position:relative;float:right;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<input id="btn_exp_dir" class="btn_visu" type="button" value="Exporter" title="Exporter directement le resultat" onclick="exportation_direct();" style="display:block;position:relative;float:right;" />
		<span style="display:block;position:relative;float:right;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<input class="btn_visu" type="button" value="Visualiser" title="Visualiser" onclick="afficheNotation();" style="display:block;position:relative;float:right;" />
	</div>
</td>
</tr>
<!------------------------------------------------------------>
</table>
</div>

    </div>
	
  </div>


<!------------------------------------------------------->
<input type="hidden" id="test_visu" />
<div id="accordion">
	<a href="#" class="recap_button_eval" id="for_recap">r&eacute;capitulatif mensuel par &eacute;valuateur</a>
	<p class="icon-plus toggle-icon">+</p>
	<div  id="div_recap" style="border:1px solid  #D6D6D6;height:450px;width:100%;padding:auto;display:none;">
		<br />
		<div style="background:#EFEFEF;width:55%;height:62px;margin:auto;border-radius:5px;padding:0px 0px 36px 0px;border:1px solid #D6D6D6;">
			<table border=0 style="margin:auto;border-spacing: 0px 15px;">
				<tr>
					<td>
						<span style="font-family:Trebuchet MS;font-size:12px;margin:12px;padding: 0px 6px 0px 0px;">Date d'&eacute;valuation du :</span>
						<input style="border:1px solid #D6D6D6;border-radius:8px;padding-left:5px;height:23px;max-width:95px;" type="text" id="txt_date" value = "<?php echo date('d/m/Y') ?>" />
					</td>
					<td>
						<span style="font-family:Trebuchet MS;font-size:12px;margin:12px;padding: 0px 6px 0px 0px;">au :</span>
						<input style="border:1px solid #D6D6D6;border-radius:8px;padding-left:5px;height:23px;max-width:95px;" type="text" id="txt_date1" value = "<?php echo date('d/m/Y') ?>" />
					</td>
					<!--td><a href="#" id="affiche_btn" class="recap_button" />Afficher</td>
					<td><a href="#" id="affiche_btn_export" class="recap_button" onclick="export_recap_mensuel_direct();" />Exporter</td-->
				</tr>
				<tr>
					<td><a href="#" id="affiche_btn" class="recap_button" />Afficher</td>
					<td><a href="#" id="affiche_btn_export" class="recap_button" onclick="export_recap_mensuel_direct();" />Exporter</td>
				</tr>
			</table>
		</div><br />
		<img id="loading_img" src="images/ajax-loader_4.gif" width="30px" height="30px" style="display:none;margin-left:398px;" />
		<br />
		<div id="div_list" ></div>
	</div>
</div>
<!-------------------------------------------------------->


</div>

<div style="width: 100%;" id="id_up_down">
	<center>
		<img id="id_up2"  class="up_down" style="display:none;cursor: pointer;" src="images/up2.png" width="30" height="25" onclick="cacher_filtre();"/>
		<img id="id_down2"   class="up_down" style="display:none;cursor: pointer;" src="images/down2.png" width="30" height="25" onclick="afficher_filtre();"/>
	</center>
</div>

<center><img id="img_loading"  style="display:none;" src="images/wait.gif" width="30" height="30"/></center>
<div id="id_contenu_by_filtre" style="display: none"> 
<div style="width: 100%;display: none; margin: 0 0 15px;background-color: #b1c6cb;" id="id_div_export_reporting">

</div>

<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%">
<tr>
  <td valign="top">

  		<div id="tabs">
  		<ul style="z-index:1;">
  			<!--<li><a href="#tabs-1" style="display: none;">Synthèse par TLC<br /></a></li>
  			<li><a href="#tabs-2" style="display: none;">Synthèse des prestations<br /></a></li>-->
  			<li><a href="#tabs-3">Par CC<br /></a></li>
  			<li><a href="#tabs-4">Toutes les prestations<br /></a></li>
  			<li><a href="#tabs-5">Récapitulatif des notations<br /></a></li>
  		</ul>
  		<!--<div id="tabs-1" style="display: none;"></div>
  		<div id="tabs-2" style="display: none;"></div>-->
  		<div id="tabs-3"></div>
  		<div id="tabs-4"></div>
  		<div id="tabs-5"></div>
  		</div>  	
  </td>
</tr>
</table>
</div>

</body>
</html>