<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Notation</title>
-->
<!--script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script-->
<!--script type="text/javascript" src="js/script_maquette.js"></script>
<script type="text/javascript" src="./js/jquery.thickbox.js"></script>
<script type="text/javascript" src="js/script.js"></script>

<link rel="stylesheet" type="text/css" href="style_maquette.css"></link>-->
<!--<script type="text/javascript" src="js/jquery.thickbox.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>

<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/tablesorter.css" type="text/css" media="screen" />-->
<!--
</head>


<body>-->



<!--
  <div class='acc_container'>
	<div class='block'>-->

<script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>
<link rel="stylesheet" href="css/ui.datepicker.css" type="text/css" media="screen" />
<script>
	$(function(){
		$("#date_appel").datepicker();

		$('#id_ecoute_player').click(function(){
			var easycode           = $('#nom_fichier').val();
			var id_projet          = $('#idprojet').val();
			var id_type_traitement = $('#idtypetraitement').val();

			$.post("function_dynamique.php",
			{
				easycode                : easycode,
				id_projet_call          : id_projet,
				id_type_traitement_call : id_type_traitement
			},
			function(_data){
				var reponse = _data.split('#|#|#');
				var lg = reponse[0];
				var ps = reponse[1];
				var verif = parseInt(reponse[2]);
				if(verif == 0)
				{
					alert('La référence ou nom du fichier n\'est pas valide !');
					return false;
				}
				else
				{
					console.log(lg+'  '+ps+'  '+easycode);
					var lien = 'https://41.188.3.110/records/?user='+lg+'&pass='+ps+'&easycode='+easycode;
					window.open(lien);
					return false;
				}

			});
		});
	});
</script>

<script>
function enregistrement_notation_()
{
	desactivediv();

	var note = $("select.par_classement");
	var nombre_note = note.length;
	if(nombre_note == 0)
	{
		alert('Il n\'existe aucun enregistrement !');
		//hng
		reactivdiv();
		return false;
	}
	var nbnote = 1;
	var escape1 = 0;
	var escape2 = 0;
	note.each(function(){
		if($(this).val() == '' || $(this).val() == -1)
		{
			escape2 = 1;
			//hng
			reactivdiv();
			//break;
			return false;
		}
	});

	$(".classement_base").each(function(){
		if($(this).val() == '')
		{
			escape1 = 1;
			//hng
			reactivdiv();
			//break;
			return false;
		}
	});

	$(".section_base").each(function(){
		if($(this).val() == '')
		{
			escape1 = 1;
			//hng
			reactivdiv();
			//break;
			return false;
		}
	});
	var date_appel = $("#date_appel").val();
	if(date_appel == '')
	{
		escape1 = 1;
	}
	if(escape1 == 1 && escape2 ==1)
	{
		alert('Le choix d\'une note dans la colonne "Point" est obligatoire ! \n Des données indispensables manquent, veuillez les remplir !');
		//hng
		reactivdiv();
		return false;
	}
	else if(escape1 == 1)
	{
		alert('Des données indispensables manquent, veuillez les remplir !');
		//hng
		reactivdiv();
		return false;
	}
	else if(escape2 == 1)
	{
		alert('Le choix d\'une note dans la colonne "Point" est obligatoire !');
		//hng
		reactivdiv();
		return false;
	}

	var base = '';
	$(".class_base").each(function(){
		if($(this).val() == 0)
		{
			var id_base = $(this).attr('id').split('_');
			base += '|'+id_base[1];
		}
	});
	//alert(base); return false;
	var id_notation = $("#id_com").val();
	var nom_fichier = $("#nom_fichier").val();
	var id_fichier = $("#idfichier").val();
	var id_projet = $("#idprojet").val();
	var id_client = $("#idclient").val();
	var id_application = $("#idapplication").val();
	var id_type_traitement = $("#idtypetraitement").val();
	var id_tlc = $("#idtlc").val();
	var valeur_point_appui = $("#id_point_appui").val();
	var valeur_point_amelioration = $("#id_point_amelioration").val();
	var valeur_preconisation = $("#id_preconisation").val();
	var tab = new Array();
	var table = new Array();

	var numero_dossier = $("#numero_dossier").val();
	var numero_commande = $("#numero_commande").val();
	var type_appel = $("#type_appel").val();

		/*if(numero_dossier == '' || numero_commande == '' || type_appel == 0)
		{
			if(confirm('Des données n\'ont pas été saisies! \n Voulez-vous quand même continuer ?'))
			{
				console.log('ok');
			}
			else
			{
				return false;
			}
		}*/
	//console.log('id_notation:'+id_notation); return false;
		if(id_notation == 0) // Si c'est une nouvelle notation
		{

			console.log('Nouveau notation = 0');

			$('#id_enregistrer').attr('disabled','disabled');
			$('#id_enregistrer').addClass('classe_enregistrer_disable');
			$.post("function_dynamique.php",
			{
				idnotation: id_notation,
				idfichier: id_fichier,
				nomfichier: decodeURIComponent(nom_fichier),
				idprojet: id_projet,
				idtlc: id_tlc,
				dateentretien: date_appel,
				numerodossier: numero_dossier,
				numerocommande: numero_commande,
				valeurpointappui: decodeURIComponent(valeur_point_appui),
				valeurpointamelioration: decodeURIComponent(valeur_point_amelioration),
				valeurpreconisation: decodeURIComponent(valeur_preconisation),
				typeappel: type_appel
			},
			function(_data){


				//$("#id_enregistrer").attr("disabled",false);
				var reponse = _data.split('**');
				id_notation = parseInt(reponse[0]);
				id_fichier = parseInt(reponse[1]);

				update_fnc_notation( id_notation );

				$('#idfichier').val(id_fichier);

				$.each(note, function( index, tab )
				{

					var valeur = $(this).val();
					var id_note = $(this).attr("id");
					tab = id_note.split('_');
					var id_grille_application = tab[2];
					var id_grille = tab[1];
					var commentaire = $("#commentaire_"+id_grille+"_"+id_grille_application).val();
					var getCommentaire = $("#commentaire_"+id_grille+"_"+id_grille_application);
					var commentaire_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application).val();
					var getCommentaire_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
					var description_fnc_si = $("#description_fnc_si_"+id_grille+"_"+id_grille_application).val();
					var getdescription_fnc_si = $("#description_fnc_si_"+id_grille+"_"+id_grille_application);
					var exigence_fnc_si = $("#exigence_fnc_si_"+id_grille+"_"+id_grille_application).val();
					var getexigence_fnc_si = $("#exigence_fnc_si_"+id_grille+"_"+id_grille_application);
					var id_cat_fnc_si = $("#id_cat_fnc_si_"+id_grille+"_"+id_grille_application).val();
					var btn_annuler_nc = $('#annuler_nc_'+id_grille+'_'+id_grille_application);
					var btn_nc = $('#btn_nc_'+id_grille+'_'+id_grille_application);
					var btn_remove_nc = $('#remove_nc_'+id_grille+'_'+id_grille_application);
					var test_nc_si = $('#id_test_nc_si_'+id_grille+'_'+id_grille_application).val();
					var get_test_nc_si = $('#id_test_nc_si_'+id_grille+'_'+id_grille_application);
					var btn_consultation_nc_si = $('#btn_consulter_nc_si_'+id_grille+'_'+id_grille_application);
					var btn_editer_nc_si = $('#btn_editer_nc_si_'+id_grille+'_'+id_grille_application);
					var gravite_si = $('#gravite_si_'+id_grille+'_'+id_grille_application).val();
					var frequence_si = $('#frequence_si_'+id_grille+'_'+id_grille_application).val();
					var cat_grav_si = $('#cat_grav_si_'+id_grille+'_'+id_grille_application).val();
					var cat_freq_si = $('#cat_freq_si_'+id_grille+'_'+id_grille_application).val();
					console.log(gravite_si+"#"+frequence_si+"#"+cat_grav_si+"#"+cat_freq_si);

					$.post("function_dynamique.php",
					{
						idgrilleapplication: id_grille_application,
						idgrille: id_grille,
						valeurnote: valeur,
						commentaire: commentaire,
						commentairesi: commentaire_si,
						idnotation1: id_notation,
						idfichier: id_fichier,
						idprojet: id_projet,
						idclient: id_client,
						idapplication: id_application,
						base: base,
						description_fnc_si: decodeURIComponent(description_fnc_si),
						exigence_fnc_si: decodeURIComponent(exigence_fnc_si),
						matricule_tlc: id_tlc,
						id_cat_fnc_si: id_cat_fnc_si,
						idtypetraitement: id_type_traitement,
						test_nc_si: test_nc_si,
						gravite_si: gravite_si,
						frequence_si: frequence_si,
						cat_grav_si: cat_grav_si,
						cat_freq_si: cat_freq_si,
						go:1
					},
					function(_data){

						response = _data.split('#x#');


						$("#"+id_note).val(-1);
						$("#id_com").val(0);
						$('.all_section').each(function(){
							var nom_section = $(this).val();
							$(".par_section_"+nom_section).val(0);
						});
						//$(".par_section_FOND").val(0);
						//$(".par_section_FORME").val(0);
						$('.note_produit').each(function(){
							$(this).val(0);
						});
						$('.classement_produit').each(function(){
							$(this).val('0.00');
						});
						$('.section_produit').each(function(){
							$(this).val('0.00');
						});
						$(".par_section").val('0.00');
						$("#total_general").val('0.00');
						$("#total_reel").val('0.00');
						$("#nb_elimin").html(0);

						getCommentaire.val('');
						getCommentaire_si.val('');
						getdescription_fnc_si.val('');
						getexigence_fnc_si.val('');
						get_test_nc_si.val(0);
						btn_annuler_nc.css('display','none');
						btn_remove_nc.css('display','none');
						btn_consultation_nc_si.css('display','none');
						btn_editer_nc_si.css('display','none');
						btn_nc.css('display','inline');
						getCommentaire_si.removeAttr("style");
						getCommentaire_si.attr("style","width:97%;resize:none;");
						$("#id_enregistrer").val('Enregistrer');
						$("#td_appreciation").removeAttr("style");
						$("#td_appreciation").attr("style","font-weight:bold;font-size:12px;text-align:center;background:#D10E20;");
						$("#td_appreciation").html('Insuffisant');
						//$('#id_titleCom').html('Nouveau');
						//$('#id_date_notation').html('');
						$('#date_evaluation').val('');

						$(".class_base ").each(function(){
							var id_base = $(this).attr('id');
							$("#"+id_base+" option:first").attr('selected','selected');
						});

						//console.log(nbnote+'**'+nombre_note)
						
						if(nbnote == nombre_note)
						{
							

							$('.indicateur_com').val(0);
							$('.initval_test').val(0);

							var nb_eval = $("#id_nb_evaluation_total").val();
							$("#id_nb_evaluation_total").val(parseInt(nb_eval)+1);

							var test_global_nc = $('#id_test_global_nc').val();
							var description_global_nc = $('#description_global_nc').val();
							var exigence_global_nc = $('#exigence_global_nc').val();

							// Si un NC global a été saisi
							if(test_global_nc == 0 && description_global_nc != '' && exigence_global_nc != '')
							{


								$.post("function_dynamique.php",
								{
									idgrilleapplication: 0,
									idgrille: 0,
									valeurnote: 0,
									commentaire: '',
									commentairesi: '1',
									idnotation1: id_notation,
									idfichier: id_fichier,
									idprojet: id_projet,
									idclient: id_client,
									idapplication: id_application,
									base: '',
									description_fnc_si: decodeURIComponent(description_global_nc),
									exigence_fnc_si: decodeURIComponent(exigence_global_nc),
									matricule_tlc: id_tlc,
									id_cat_fnc_si: 0,
									idtypetraitement: id_type_traitement,
									test_nc_si: test_global_nc,

									gravite_si: $('#gravite').val(),
									frequence_si: $('#frequence').val(),
									cat_grav_si: $('#cat_gravite').val(),
									cat_freq_si: $('#cat_frequence').val(),

									go:1
								},
								function(_data){


									response = _data.split('#x#');

									$('#btn_global_nc').css('display','inline');
									$('#btn_global_consulter_nc').css('display','none');
									$('#btn_global_editer_nc').css('display','none');
									$('#btn_global_supprimer_nc').css('display','none');
									$('#btn_global_annuler_nc').css('display','none');
									$('#description_global_nc').val('');
									$('#exigence_global_nc').val('');
									$('#id_test_global_nc').val(0);

									// Refresh Liste Notation
									$.post("function_dynamique.php",
									{
										_id_fichier: id_fichier,
										_id_projet: id_projet,
										_id_client: id_client,
										_id_application: id_application,
										_id_type_traitement: id_type_traitement,
										_id_tlc : id_tlc,
										dateentretien: date_appel,
										numerodossier: numero_dossier,
										numerocommande: numero_commande,
										_id_notation: id_notation,
										valeurpointappui: valeur_point_appui,
										valeurpointamelioration: valeur_point_amelioration,
										valeurpreconisation: valeur_preconisation,
										typeappel: type_appel,
										refreshList:1
									},
									function(data) {


										var rep = data.split('###');
										$('#id_com').html(rep[0]);
										$('#id_div_liste_notation').html(rep[1]);
										var today = get_current_date();
										$("#date_evaluation").val(today);

										$("#id_point_appui").val('');
										$("#id_point_amelioration").val('');
										$("#id_preconisation").val('');

										actualisationTotalIndicateur();
										alert('Insertion avec succ\350s !');
										//hng
										reactivdiv();

										$('#id_enregistrer').removeAttr('disabled');
										$('#id_enregistrer').removeClass('classe_enregistrer_disable');
										
										if(response[0] == '1')
										{
										

											var matricule_tlc = id_tlc;
											var type = response[1];
											var id_prestation = response[2];
											var type_traitement = response[3];
											var nom_tlc = response[4];
											var nom_fichier = response[5];
											var date_traitement = response[6];
											var date_evaluation = response[7];
											var categorie_si = response[8];
											var description_ecart = response[9];
											var exigence_client = response[10];
											
											var reference_nc = response[11];
											var criticite_nc = response[12];

											var flag_el = $("#test_"+id_grille).val();
											var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
											test_bg_si(com_si,flag_el,id_grille,id_grille_application);

											if(criticite_nc == 'C'){

												send_email_chq( matricule_tlc,type,id_prestation,type_traitement, nom_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,id_client,reference_nc);

											}
										}
									});

								});
							}
							else
							{
								// Refresh Liste Notation
								$.post("function_dynamique.php",
								{
									_id_fichier: id_fichier,
									_id_projet: id_projet,
									_id_client: id_client,
									_id_application: id_application,
									_id_type_traitement: id_type_traitement,
									_id_tlc : id_tlc,
									dateentretien: date_appel,
									numerodossier: numero_dossier,
									numerocommande: numero_commande,
									_id_notation: id_notation,
									valeurpointappui: valeur_point_appui,
									valeurpointamelioration: valeur_point_amelioration,
									valeurpreconisation: valeur_preconisation,
									typeappel: type_appel,
									refreshList:1
								},
								function(data) {

									var rep = data.split('###');

									$('#id_com').html(rep[0]);
									$('#id_div_liste_notation').html(rep[1]);
									var today = get_current_date();
									$("#date_evaluation").val(today);

									$("#id_point_appui").val('');
									$("#id_point_amelioration").val('');
									$("#id_preconisation").val('');

									actualisationTotalIndicateur();
									alert('Insertion avec succ\350s !');
									//hng
									reactivdiv();

									$('#id_enregistrer').removeAttr('disabled');
									$('#id_enregistrer').removeClass('classe_enregistrer_disable');

									if(response[0] == '1')
									{
										var matricule_tlc = id_tlc;
										var type = response[1];
										var id_prestation = response[2];
										var type_traitement = response[3];
										var nom_tlc = response[4];
										var nom_fichier = response[5];
										var date_traitement = response[6];
										var date_evaluation = response[7];
										var categorie_si = response[8];
										var description_ecart = response[9];
										var exigence_client = response[10];
										var reference_nc = response[11];
										var criticite_nc = response[12];

										var flag_el = $("#test_"+id_grille).val();
										var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
										test_bg_si(com_si,flag_el,id_grille,id_grille_application);

										if(criticite_nc == 'C'){

											send_email_chq( matricule_tlc,type,id_prestation,type_traitement, nom_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,id_client,reference_nc);

										}else{
											
											send_email( matricule_tlc,type,id_prestation,type_traitement, nom_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,id_client,reference_nc);
										}
									}
								});
							}
						}
						else
						{
							nbnote = nbnote + 1;
							if(response[0] == '1')
							{
								var matricule_tlc = id_tlc;
								var type = response[1];
								var id_prestation = response[2];
								var type_traitement = response[3];
								var nom_tlc = response[4];
								var nom_fichier = response[5];
								var date_traitement = response[6];
								var date_evaluation = response[7];
								var categorie_si = response[8];
								var description_ecart = response[9];
								var exigence_client = response[10];
								var reference_nc = response[11];
								var criticite_nc = response[12];

								var flag_el = $("#test_"+id_grille).val();
								var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
								test_bg_si(com_si,flag_el,id_grille,id_grille_application);

								if(criticite_nc == 'C'){

									send_email_chq( matricule_tlc,type,id_prestation,type_traitement, nom_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,id_client,reference_nc);

								}else{
									
									send_email( matricule_tlc,type,id_prestation,type_traitement, nom_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,id_client,reference_nc);
								}
							}
						}
					});
				});
			});
		}
		else  // C'est une modification d'une notation déjà existante
		{
			$('#id_enregistrer').attr('disabled','disabled');
			$('#id_enregistrer').addClass('classe_enregistrer_disable');
			$.each(note, function( index, tab )
			{
				var valeur = $(this).val();
				var id_note = $(this).attr("id");
				tab = id_note.split('_');
				var id_grille_application = tab[2];
				var id_grille = tab[1];
				//table[id_grille_application] = new Array();
				//table[id_grille_application][id_grille] = valeur;    // table[id_grille_application][id_grille] = valeur
				var commentaire = $("#commentaire_"+id_grille+"_"+id_grille_application).val();
				var getCommentaire = $("#commentaire_"+id_grille+"_"+id_grille_application);
				var commentaire_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application).val();
				var getCommentaire_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
				var description_fnc_si = $("#description_fnc_si_"+id_grille+"_"+id_grille_application).val();
				var getdescription_fnc_si = $("#description_fnc_si_"+id_grille+"_"+id_grille_application);
				var exigence_fnc_si = $("#exigence_fnc_si_"+id_grille+"_"+id_grille_application).val();
				var getexigence_fnc_si = $("#exigence_fnc_si_"+id_grille+"_"+id_grille_application);
				var id_cat_fnc_si = $("#id_cat_fnc_si_"+id_grille+"_"+id_grille_application).val();
				var btn_annuler_nc = $('#annuler_nc_'+id_grille+'_'+id_grille_application);
				var btn_nc = $('#btn_nc_'+id_grille+'_'+id_grille_application);
				var btn_remove_nc = $('#remove_nc_'+id_grille+'_'+id_grille_application);
				var test_nc_si = $('#id_test_nc_si_'+id_grille+'_'+id_grille_application).val();
				var get_test_nc_si = $('#id_test_nc_si_'+id_grille+'_'+id_grille_application);
				var btn_consultation_nc_si = $('#btn_consulter_nc_si_'+id_grille+'_'+id_grille_application);
				var btn_editer_nc_si = $('#btn_editer_nc_si_'+id_grille+'_'+id_grille_application);

				var gravite_si = $('#gravite_si_'+id_grille+'_'+id_grille_application).val();
				var frequence_si = $('#frequence_si_'+id_grille+'_'+id_grille_application).val();
				var cat_grav_si = $('#cat_grav_si_'+id_grille+'_'+id_grille_application).val();
				var cat_freq_si = $('#cat_freq_si_'+id_grille+'_'+id_grille_application).val();

				console.log("COM SI:"+commentaire_si);

				$.post("function_dynamique.php",
				{
					idgrilleapplication: id_grille_application,
					idgrille: id_grille,
					valeurnote: valeur,
					commentaire: commentaire,
					commentairesi: commentaire_si,
					idnotation1: id_notation,
					idfichier: id_fichier,
					idprojet: id_projet,
					idclient: id_client,
					idapplication: id_application,
					base: base,
					description_fnc_si: decodeURIComponent(description_fnc_si),
					exigence_fnc_si: decodeURIComponent(exigence_fnc_si),
					matricule_tlc: id_tlc,
					id_cat_fnc_si: id_cat_fnc_si,
					idtypetraitement: id_type_traitement,
					test_nc_si: test_nc_si,

					gravite_si: gravite_si,
					frequence_si: frequence_si,
					cat_grav_si: cat_grav_si,
					cat_freq_si: cat_freq_si,

				typeappel: type_appel,

					go:2
				},
				function(_data){
					var response = _data.split('#x#');
					$("#"+id_note).val(-1);
					$("#id_com").val(0);
					$('.all_section').each(function(){
						var nom_section = $(this).val();
						$(".par_section_"+nom_section).val(0);
					});
					//$(".par_section_FOND").val(0);
					//$(".par_section_FORME").val(0);
					$('.note_produit').each(function(){
						$(this).val(0);
					});
					$('.classement_produit').each(function(){
						$(this).val('0.00');
					});
					$('.section_produit').each(function(){
						$(this).val('0.00');
					});
					$(".par_section").val('0.00');
					$("#total_general").val('0.00');
					$("#total_reel").val('0.00');
					$("#nb_elimin").html(0);
					getCommentaire.val('');
					getCommentaire_si.val('');
					getdescription_fnc_si.val('');
					getexigence_fnc_si.val('');
					get_test_nc_si.val(0);
					btn_annuler_nc.css('display','none');
					btn_remove_nc.css('display','none');
					btn_consultation_nc_si.css('display','none');
					btn_editer_nc_si.css('display','none');
					btn_nc.css('display','inline');
					getCommentaire_si.removeAttr("style");
					getCommentaire_si.attr("style","width:97%;resize:none;");
					$("#id_enregistrer").val('Enregistrer');
					$("#td_appreciation").removeAttr("style");
					$("#td_appreciation").attr("style","font-weight:bold;font-size:12px;text-align:center;background:#D10E20;");
					$("#td_appreciation").html('Insuffisant');
					//$('#id_titleCom').html('Nouveau');
					//$('#id_date_notation').html('');
					$('#date_evaluation').val('');

					$(".class_base ").each(function(){
						var id_base = $(this).attr('id');
						$("#"+id_base+" option:first").attr('selected','selected');
					});

					//console.log("Nombre note1 = "+nbnote+" , Nombre note2 = "+nombre_note);

					if(nbnote == nombre_note)
					{
						$('.indicateur_com').val(0);
						$('.initval_test').val(0);

						var test_global_nc = $('#id_test_global_nc').val();
						var description_global_nc = $('#description_global_nc').val();
						var exigence_global_nc = $('#exigence_global_nc').val();

						//console.log("Dans le test note1=note2");

						//console.log("test global nc ="+test_global_nc+" , description global ="+description_global_nc+" , exigence global ="+exigence_global_nc);

						// Si un NC global a été saisi
						if(test_global_nc == 0 && description_global_nc != '' && exigence_global_nc != '')
						{
							$.post("function_dynamique.php",
							{
								idgrilleapplication: 0,
								idgrille: 0,
								valeurnote: 0,
								commentaire: '',
								commentairesi: '1',
								idnotation1: id_notation,
								idfichier: id_fichier,
								idprojet: id_projet,
								idclient: id_client,
								idapplication: id_application,
								base: '',
								description_fnc_si: decodeURIComponent(description_global_nc),
								exigence_fnc_si: decodeURIComponent(exigence_global_nc),
								matricule_tlc: id_tlc,
								id_cat_fnc_si: 0,
								idtypetraitement: id_type_traitement,
								test_nc_si: test_global_nc,

								gravite_si: $('#gravite').val(),
								frequence_si: $('#frequence').val(),
								cat_grav_si: $('#cat_gravite').val(),
								cat_freq_si: $('#cat_frequence').val(),

								go:1
							},
							function(_data){


								//console.log("dans le test refresh liste notation");

								var response = _data.split('#x#');

								$('#btn_global_nc').css('display','inline');
								$('#btn_global_consulter_nc').css('display','none');
								$('#btn_global_editer_nc').css('display','none');
								$('#btn_global_supprimer_nc').css('display','none');
								$('#btn_global_annuler_nc').css('display','none');
								$('#description_global_nc').val('');
								$('#exigence_global_nc').val('');

								// Refresh Liste Notation
								$.post("function_dynamique.php",
								{
									_id_fichier: id_fichier,
									_id_projet: id_projet,
									_id_client: id_client,
									_id_application: id_application,
									_id_type_traitement: id_type_traitement,
									_id_tlc : id_tlc,
									dateentretien: date_appel,
									numerodossier: numero_dossier,
									numerocommande: numero_commande,
									_id_notation: id_notation,
									valeurpointappui: valeur_point_appui,
									valeurpointamelioration: valeur_point_amelioration,
									valeurpreconisation: valeur_preconisation,
									typeappel: type_appel,
									refreshList:1
								},
								function(data) {
									var rep = data.split('###');

									$('#id_com').html(rep[0]);
									$('#id_div_liste_notation').html(rep[1]);
									var today = get_current_date();
									$("#date_evaluation").val(today);

									$("#id_point_appui").val('');
									$("#id_point_amelioration").val('');
									$("#id_preconisation").val('');

									actualisationTotalIndicateur();
									alert('Modification avec succ\350s !');
									//hng
									reactivdiv();
									$('#id_enregistrer').removeAttr('disabled');
									$('#id_enregistrer').removeClass('classe_enregistrer_disable');

									//console.log(" valeur reponse ="+response[0]);

									if(response[0] == '1')
									{
										var matricule_tlc = id_tlc;
										var type = response[1];
										var id_prestation = response[2];
										var type_traitement = response[3];
										var nom_tlc = response[4];
										var nom_fichier = response[5];
										var date_traitement = response[6];
										var date_evaluation = response[7];
										var categorie_si = response[8];
										var description_ecart = response[9];
										var exigence_client = response[10];
										var reference_nc = response[11];
										var criticite_nc = response[12];

										var flag_el = $("#test_"+id_grille).val();
										var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
										test_bg_si(com_si,flag_el,id_grille,id_grille_application);


										if(criticite_nc == 'C'){

											send_email_chq( matricule_tlc,type,id_prestation,type_traitement, nom_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,id_client,reference_nc);

										}
									}
								});

							});
						}
						else
						{
							$('#btn_global_nc').css('display','inline');
							$('#btn_global_consulter_nc').css('display','none');
							$('#btn_global_editer_nc').css('display','none');
							$('#btn_global_supprimer_nc').css('display','none');
							$('#btn_global_annuler_nc').css('display','none');
							$('#description_global_nc').val('');
							$('#exigence_global_nc').val('');
							$('#id_test_global_nc').val(0);

							// Refresh Liste Notation
							$.post("function_dynamique.php",
							{
								_id_fichier: id_fichier,
								_id_projet: id_projet,
								_id_client: id_client,
								_id_application: id_application,
								_id_type_traitement: id_type_traitement,
								_id_tlc : id_tlc,
								dateentretien: date_appel,
								numerodossier: numero_dossier,
								numerocommande: numero_commande,
								_id_notation: id_notation,
								valeurpointappui: valeur_point_appui,
								valeurpointamelioration: valeur_point_amelioration,
								valeurpreconisation: valeur_preconisation,
								typeappel: type_appel,
								refreshList:1
							},
							function(data) {
								var rep = data.split('###');
								$('#id_com').html(rep[0]);
								$('#id_div_liste_notation').html(rep[1]);
								var today = get_current_date();
								$("#date_evaluation").val(today);

								$("#id_point_appui").val('');
								$("#id_point_amelioration").val('');
								$("#id_preconisation").val('');

								actualisationTotalIndicateur();
								alert('Modification avec succ\350s !');
								//hng
								reactivdiv();

								$('#id_enregistrer').removeAttr('disabled');
								$('#id_enregistrer').removeClass('classe_enregistrer_disable');

								if(response[0] == '1')
								{
									var matricule_tlc = id_tlc;
									var type = response[1];
									var id_prestation = response[2];
									var type_traitement = response[3];
									var nom_tlc = response[4];
									var nom_fichier = response[5];
									var date_traitement = response[6];
									var date_evaluation = response[7];
									var categorie_si = response[8];
									var description_ecart = response[9];
									var exigence_client = response[10];

									var reference_nc = response[11];
									var criticite_nc = response[12];

									var flag_el = $("#test_"+id_grille).val();
									var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
									test_bg_si(com_si,flag_el,id_grille,id_grille_application);

									if(criticite_nc == 'C'){

										send_email_chq( matricule_tlc,type,id_prestation,type_traitement, nom_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,id_client,reference_nc);

									}else{
										
										send_email( matricule_tlc,type,id_prestation,type_traitement, nom_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,id_client,reference_nc);
									}
								}
							});
						}
					}
					else
					{
						nbnote = nbnote + 1;

						if(response[0] == '1')
						{
							//console.log("dans le test");
							
							var matricule_tlc = id_tlc;
							var type = response[1];
							var id_prestation = response[2];
							var type_traitement = response[3];
							var nom_tlc = response[4];
							var nom_fichier = response[5];
							var date_traitement = response[6];
							var date_evaluation = response[7];
							var categorie_si = response[8];
							var description_ecart = response[9];
							var exigence_client = response[10];

							var reference_nc = response[11];
							var criticite_nc = response[12];

							var flag_el = $("#test_"+id_grille).val();
							var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
							test_bg_si(com_si,flag_el,id_grille,id_grille_application);

							if(criticite_nc == 'C'){

								send_email_chq( matricule_tlc,type,id_prestation,type_traitement, nom_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,id_client,reference_nc);

							}else{

								send_email( matricule_tlc,type,id_prestation,type_traitement, nom_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,id_client,reference_nc);
							}
						}
					}
				});
			});
		}
	}

	function actualisationTotalIndicateur()
	{

		var nombre_evaluation = $("#id_nb_evaluation_total").val();
		if(nombre_evaluation == 0)
		{

			return false;
		}
		else
		{
			var nb_note10  = 0;
			var nb_note100 = 0;
			var total_10   = 0;
			var total_100  = 0;

			$(".class_total").each(function(){
				var note10 = parseFloat($(this).val())/10;
				nb_note10 += parseFloat(note10);
				nb_note100 += parseFloat($(this).val());
				total_10 ++;
				total_100 ++;
			});

			$("#id_note10_total").val((nb_note10/total_10).toFixed(1));
			$("#id_note100_total").val((nb_note100/total_100).toFixed(2));

			tab_nf = new Array('is4','is5','is5_v7','is6','is7');
			$.each(tab_nf, function(index){
				var nb_is = 0;
				$(".class_"+tab_nf[index]).each(function(){
					nb_is += parseInt($(this).val());
				});
				$("#id_"+tab_nf[index]+"_total").val(((nb_is/nombre_evaluation)*100).toFixed(2));
			});

			var nombre_repartition = $('#id_nombre_repartition').val();
			for(var i=1;i<=nombre_repartition;i++)
			{
				var nb_rep = 0;
				$(".class_rep_"+i).each(function(){
					nb_rep += parseInt($(this).val());
				});
				$("#repartition_"+i+"_total").val(nb_rep);
			}
		}

	}

  function update_fnc_notation( id_notation ){
                      
	         var class_fnc  =  $(".class_fnc");
			 var i = 0;
			 var str_nc = '';
			 class_fnc.each(function(e) {

					   if(i != 0) str_nc += ',';
						str_nc += $(this).val();
						i = i+1;

						console.log(str_nc);

			 })
			         if( str_nc != ''  )
					 {
						$.post('insert_fnc_si.php',
						{
							str_nc:str_nc,
							notation_id:id_notation,
							test_update:1

						},function(_data){

						})
					 }


  }

    //hng
    function desactivediv(){
		var lediv = $('body');
		lediv.css('pointer-events', 'none');
		lediv.css('opacity', '0.5');
	}

	function reactivdiv(){
		var lediv = $('body');
		lediv.css('pointer-events', 'auto');
		lediv.css('opacity', '1');
	}

	$('#testAppel').val($('#type_appel').val());

	$("#type_appel").on('change',function(){
		$('#testAppel').val($('#type_appel').val());
		var xtest =$('#type_appel').val();
		$('#testAppel').val(xtest);
	});
</script>
<?php
session_start();
include 'function_dynamique.php';

$matricule_session = $_SESSION['matricule'];
$fct               = $_SESSION['zFonction'];

$tFctAuthoriseInit = array('AQI', 'DQ', 'DCT', 'DCC', 'RP', 'AQI', 'SUP', 'SUP CC', 'SUP_CC', 'TC', 'OL', 'RESP PLATEAU', 'ACC', 'CONSEILLER', 'FONC_MAIL', 'MANAGER');
$tFctAuthorise     = array('AQI', 'DQ', 'DCT', 'DCC', 'RP', 'AQI', 'SUP', 'RESP PLATEAU', 'MANAGER');

include 'gestion_droit.php';

$matAdmin    = getPersMenuProjet();
$matNotation = getPersMenuNotation();

$tab_droit_eval = getPersAccesNotation();
echo "<span style='display:none;'>" . $matricule_session . "</span>";
echo "<input type='hidden'  value=" . $matricule_session . "  id='s_matricule' />";
echo "<input type='hidden'  value=" . $tab_droit_eval[$matricule_session] . "  id='s_matricule_droit_eval' />";
$droit_eval = $tab_droit_eval[$matricule_session];
/*
id_projet_by_filtre : id_projet,
id_client_by_filtre : id_client,
id_application_by_filtre : id_application,
id_fichier_by_filtre : id_fichier,
id_type_traitement_by_filtre : id_type_traitement,
id_tlc_by_filtre : id_tlc
 */

$id_projet          = $_REQUEST['id_projet_by_filtre'];
$id_client          = $_REQUEST['id_client_by_filtre'];
$id_application     = $_REQUEST['id_application_by_filtre'];
$id_fichier         = $_REQUEST['id_fichier_by_filtre'];
$id_type_traitement = $_REQUEST['id_type_traitement_by_filtre'];
$id_tlc             = $_REQUEST['id_tlc_by_filtre'];

/*
$id_projet = 46;
$id_client = 706;
$id_application = 914;
$id_fichier = 1877;
$id_type_traitement = 1;
 */

$result_nom_application = getDescByApplication($id_application);
$res_nom_app            = pg_fetch_array($result_nom_application);
$nom_application        = $res_nom_app['nom_application'];

$libelle     = getLibelleById($id_projet, $id_client, $id_application, $id_type_traitement, $id_tlc, $id_fichier);
$tab_libelle = explode('||', $libelle);
//<input type="hidden" id="idprojet" value="'.$id_projet.'" /><input type="hidden" id="nom_projet" value="'.$tab_libelle[0].'" /><b>Campagne</b> : '.$tab_libelle[0].' </br>
$nom_fichier         = $id_fichier;
$id_fichier          = $tab_libelle[5];
$nom_type_traitement = $tab_libelle[3];
echo '<input type="hidden" id="id_fnc" />  <table width="100%">';
echo ' <tr>
<td style="width:70%;">
<div style="font-family:Verdana;font-size:13px;margin: 5px;">';
$res_pers = getNomPrenomEvaluateur($matricule_session);
echo '<b>Evaluateur actuel </b> : ' . $res_pers['matricule'] . ' - ' . $res_pers['nompersonnel'] . ' ' . $res_pers['prenompersonnel'] . '</br>

<input type="hidden" id="idprojet" value="' . $id_projet . '" /><input type="hidden" id="nom_projet" value="' . $tab_libelle[0] . '" />

<input type="hidden" id="idtlc" value="' . $id_tlc . '" /><input type="hidden" id="nom_tlc" value="' . $tab_libelle[4] . '" /><b>Matricule à évaluer</b> : ' . $tab_libelle[4] . ' </br>

<input type="hidden" id="idclient" value="' . $id_client . '" /><input type="hidden" id="nom_client" value="' . $tab_libelle[1] . '" /><b>Nom client</b> : ' . $tab_libelle[1] . ' </br>

<input type="hidden" id="idapplication" value="' . $id_application . '" /><input type="hidden" id="nom_application" value="' . $tab_libelle[2] . '" /><b>Prestation</b> : ' . $tab_libelle[2] . ' - ' . $nom_application . ' </br>

<input type="hidden" id="idtypetraitement" value="' . $id_type_traitement . '" /><b>Type de traitement</b> : ' . $tab_libelle[3] . ' </br>

<input type="hidden" id="idfichier" value="' . $id_fichier . '" /><input type="hidden" id="nom_fichier" value="' . utf8_decode($nom_fichier) . '" /><b>Nom du fichier</b> : <span style="color:#0060B4;">' . utf8_decode($nom_fichier) . '</span> </br>
</div></td>';

if (isset($matricule_session) && in_array($matricule_session, $matAdmin)) {

    echo "<td style='width:30%;'><div style='display: block;font-family: verdana;font-size: 10px;left: 46%;position: relative;width: 200px;'>
            <ul>
	            <li>
					<a title='Gestion questionnaire' onclick='gestion_questionnaire();' id='gest_link' class='icone'>
						<img width='20px' height='20px' alt='Questionnaires' src='images/Options_48x48.png' style='display:block;position:relative;float:left' />
						<span class='libelle'>Gestion de questionnaires</span>
					</a>
	            </li>
	            <li>
					<a title='Gestion des grilles' onclick='admin_grille(" . $id_projet . "," . $id_client . "," . $id_application . ",0);' id='id_ajout_questionnaire' class='icone'>
						<img width='20px' height='20px' alt='Grilles' src='images/Wizard_48x48.png' style='display:block;position:relative;float:left' />
						<span class='libelle'>Gestion des grilles</span>
					</a>
	            </li>
	            <li>
					<a title='Gestion des notes' onclick='admin_note(" . $id_projet . "," . $id_client . "," . $id_application . ");' id='id_ajout_note_questionnaire' class='icone'>
						<img width='20px' height='20px' alt='Notes' src='images/img_note.jpg' style='display:block;position:relative;float:left' />
						<span class='libelle'>Gestion des notes</span>
					</a>
	            </li>
	            <li>
					<a title='Gestion des classements et leur pondération' onclick='admin_classement(" . $id_projet . "," . $id_client . "," . $id_application . ");' id='id_gestion_classement' class='icone'>
						<img width='20px' height='20px' alt='Notes' src='images/classement.png' style='display:block;position:relative;float:left' />
						<span class='libelle'>Gestion des classements</span>
					</a>
	            </li>
				 <li>
					<a title='Gestion des penalites' onclick='admin_penalite(" . $id_projet . "," . $id_client . "," . $id_application . "," . $id_type_traitement . ");' id='id_gestion_classement' class='icone'>
						<img width='20px' height='20px' alt='Notes' src='images/penalite.jpeg' style='display:block;position:relative;float:left' />
						<span class='libelle'>Gestion des pénalités</span>
					</a>
	            </li>
				<li>
					<a title='Dupliquer grille' onclick='admin_duplication(" . $id_projet . "," . $id_client . "," . $id_application . ",-1);' id='id_gestion_classement' class='icone'>
						<img width='20px' height='20px' alt='Notes' src='images/dupliquer_2.jpg' style='display:block;position:relative;float:left' />
						<span class='libelle'>Duplication des grilles</span>
					</a>
	            </li>
	            <li>
					<a title='Gestion des typologies' onclick='gestion_typologie(" . $id_projet . "," . $id_client . "," . $id_application . ",-1);' id='id_gestion_typologie' class='icone'>
						<img width='20px' height='20px' alt='Notes' src='images/typologie1.png' style='display:block;position:relative;float:left' />
						<span class='libelle' style='margin: 7px 0px 0px 5px;'>Gestion des typologies</span>
					</a>
	            </li>

		   </ul>
     </div></td>";

}

echo "</tr>";
echo '</table>';

echo '<div style="font-family:Verdana;font-size:13px;margin: 5px;">
<table border="0" style="border-collapse:collapse;width:100%;" id="info">';
/*echo '<!--<tr>
<td class="titre_label">&nbsp;</td><td>&nbsp;</td>
<td class="titre_label">&nbsp;</td><td>&nbsp;</td>
</tr>

<tr>
<td class="titre_label">Date de l\'évaluation</td><td></td>
<td class="titre_label">Client</td><td></td>
</tr>

<tr>
<td class="titre_label">&nbsp;</td><td>&nbsp;</td>
<td class="titre_label">N° de dossier :</td><td><input type="text" id="num_dossier" /></td>
</tr>-->';*/

/****** Nombre d'évaluation ************/
$result_com    = getNotationCom($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement, $id_tlc);
$nb_eval_total = pg_num_rows($result_com);

if ($nb_eval_total > 0) {
    $res          = pg_fetch_array($result_com, 0);
    $id_typologie = $res['id_typologie'];
    if ($id_typologie == '') {
        $id_typologie        = 0;
        $type_appel_readonly = "";
    }
    if ($id_typologie > 0) {
        $type_appel_readonly = "disabled";
    }
} else {
    $type_appel_readonly = "";
}

/****** Note sur 100 *******************/
$result_com          = getNotationCom($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement, $id_tlc);
$somme_total_general = 0;
$somme_note10        = 0;
$str                 = '';
$table_valeur        = array();
$table_ind           = array();
$tab_ind             = array();
$tab                 = array();
$tab_rep             = array();
$moyenne_is4         = array();
$moyenne_is5         = array();
$moyenne_is6         = array();
$moyenne_is7         = array();
$moyenne_is5_v7      = array();
$_repartition        = array();
$_result_rep         = array();
$_r                  = array();
while ($res_com = pg_fetch_array($result_com)) {
    $id_notation  = $res_com['id_notation'];
    $str          = calculTotalGeneral($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement);
    $table_valeur = explode('||', $str); // total_general || nb_eliminatoire || &id_grille_application|IS4_IS6|repartition

    if (($id_type_traitement == 1 || $id_type_traitement == 2) && $id_client != 643) //client différent de DELAMAISON
    {
        $somme_note10 += $table_valeur[0];
        $somme_total_general += $table_valeur[0] * 10;
        $totalG[$id_notation] = $table_valeur[0] * 10;
    } else {
        $somme_note10 += $table_valeur[0] / 10;
        $somme_total_general += $table_valeur[0];
        $totalG[$id_notation] = $table_valeur[0];
    }

    $valeur_indicateur = explode('&', $table_valeur[2]); // &1|IS4_IS6&2|IS4&17|IS5&12|IS4&11|IS7&13|IS6&19|IS7&25|IS4&7|IS6
    for ($i = 1; $i < count($valeur_indicateur); $i++) {
        $table_ind = explode('|', $valeur_indicateur[$i]);
        $tab_ind   = explode(';', $table_ind[1]);
        for ($j = 0; $j < count($tab_ind); $j++) {
            $tab[$tab_ind[$j]][] = $table_ind[0];
        }
        $tab_rep[$table_ind[2]][] = $table_ind[0]; // $tab_rep[id_repartition][] = id_grille_application
    }

    /************** IS4 **********************/
    $result_is4        = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement, $id_notation, $id_tlc, $tab, 'IS4');
    $ponderation_is4   = 0;
    $produit_somme_is4 = 0;
    $nandalo_is4       = 0;
    while ($res_is4 = pg_fetch_array($result_is4)) {
        $ponderation_is4 += $res_is4['pond'];
        $produit_somme_is4 += $res_is4['note'] * $res_is4['pond'];
        $nandalo_is4 = 1;
    }

    if (($id_type_traitement == 1 || $id_type_traitement == 2) && $id_client != 643) //client différent de DELAMAISON
    {
        if ($produit_somme_is4 == $ponderation_is4 && $nandalo_is4 == 1) {
            $moyenne_is4[$id_notation] = 1;
        } else {
            $moyenne_is4[$id_notation] = 0;
        }

    } else {
        if ($ponderation_is4 != 0) {
            if (($produit_somme_is4 / $ponderation_is4) < 100) {
                $moyenne_is4[$id_notation] = 0;
            } else {
                $moyenne_is4[$id_notation] = 1;
            }

        } else {
            $moyenne_is4[$id_notation] = 0;
        }

    }

    /************** IS5 **********************/
    $result_is5        = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement, $id_notation, $id_tlc, $tab, 'IS5');
    $ponderation_is5   = 0;
    $produit_somme_is5 = 0;
    $nandalo_is5       = 0;
    while ($res_is5 = pg_fetch_array($result_is5)) {
        $ponderation_is5 += $res_is5['pond'];
        $produit_somme_is5 += $res_is5['note'] * $res_is5['pond'];
        $nandalo_is5 = 1;
    }

    if (($id_type_traitement == 1 || $id_type_traitement == 2) && $id_client != 643) //client différent de DELAMAISON
    {
        if ($produit_somme_is5 == $ponderation_is5 && $nandalo_is5 == 1) {
            $moyenne_is5[$id_notation] = 1;
        } else {
            $moyenne_is5[$id_notation] = 0;
        }

    } else {
        if ($ponderation_is5 != 0) {
            if (($produit_somme_is5 / $ponderation_is5) < 100) {
                $moyenne_is5[$id_notation] = 0;
            } else {
                $moyenne_is5[$id_notation] = 1;
            }

        } else {
            $moyenne_is5[$id_notation] = 0;
        }

    }

    /************** IS6 **********************/
    $result_is6        = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement, $id_notation, $id_tlc, $tab, 'IS6');
    $ponderation_is6   = 0;
    $produit_somme_is6 = 0;
    $_note_is6         = 0;
    $nandalo_is6       = 0;
    while ($res_is6 = pg_fetch_array($result_is6)) {
        $ponderation_is6 += $res_is6['pond'];
        $produit_somme_is6 += $res_is6['note'] * $res_is6['pond'];
        $_note_is6 += $res_is6['note'];
        $nandalo_is6 = 1;
    }

    if (($id_type_traitement == 1 || $id_type_traitement == 2) && $id_client != 643) //client différent de DELAMAISON
    {
        if ($produit_somme_is6 == $ponderation_is6 && $nandalo_is6 == 1) {
            $moyenne_is6[$id_notation] = 1;
        } else {
            $moyenne_is6[$id_notation] = 0;
        }

    } else {
        if ($ponderation_is6 != 0) {
            if (($produit_somme_is6 / $ponderation_is6) < 100) {
                $moyenne_is6[$id_notation] = 0;
            } else {
                $moyenne_is6[$id_notation] = 1;
            }

        } else {
            $moyenne_is6[$id_notation] = 0;
        }

    }

    /************** IS7 **********************/
    $result_is7        = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement, $id_notation, $id_tlc, $tab, 'IS7');
    $ponderation_is7   = 0;
    $produit_somme_is7 = 0;
    $nandalo_is7       = 0;
    while ($res_is7 = pg_fetch_array($result_is7)) {
        $ponderation_is7 += $res_is7['pond'];
        $produit_somme_is7 += $res_is7['note'] * $res_is7['pond'];
        $nandalo_is7 = 1;
    }

    if (($id_type_traitement == 1 || $id_type_traitement == 2) && $id_client != 643) //client différent de DELAMAISON
    {
        if ($produit_somme_is7 == $ponderation_is7 && $nandalo_is7 == 1) {
            $moyenne_is7[$id_notation] = 1;
        } else {
            $moyenne_is7[$id_notation] = 0;
        }

    } else {
        if ($ponderation_is7 != 0) {
            if (($produit_somme_is7 / $ponderation_is7) < 100) {
                $moyenne_is7[$id_notation] = 0;
            } else {
                $moyenne_is7[$id_notation] = 1;
            }

        } else {
            $moyenne_is7[$id_notation] = 0;
        }

    }

    /************** IS5_v7 **********************/
    $result_is5_v7        = calculIS($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement, $id_notation, $id_tlc, $tab, 'IS5_V7');
    $ponderation_is5_v7   = 0;
    $produit_somme_is5_v7 = 0;
    $nandalo_is5_v7       = 0;
    while ($res_is5_v7 = pg_fetch_array($result_is5_v7)) {
        $ponderation_is5_v7 += $res_is5_v7['pond'];
        $produit_somme_is5_v7 += $res_is5_v7['note'] * $res_is5_v7['pond'];
        $nandalo_is5_v7 = 1;
    }

    if ($id_type_traitement == 1 || $id_type_traitement == 2 && $id_client != 643) //client différent de DELAMAISON
    {
        if ($produit_somme_is5_v7 == $ponderation_is5_v7 && $nandalo_is5_v7 == 1) {
            $moyenne_is5_v7[$id_notation] = 1;
        } else {
            $moyenne_is5_v7[$id_notation] = 0;
        }

    } else {
        if ($ponderation_is5_v7 != 0) {
            if (($produit_somme_is5_v7 / $ponderation_is5_v7) < 100) {
                $moyenne_is5_v7[$id_notation] = 0;
            } else {
                $moyenne_is5_v7[$id_notation] = 1;
            }

        } else {
            $moyenne_is5_v7[$id_notation] = 0;
        }

    }

    /************* Repartition ***********************/
    $result_rep = getCalculRep($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement, $id_notation, $id_tlc, $tab_rep);
    foreach ($result_rep as $_val) {
        for ($a = 1; $a <= count($_val); $a++) {
            if (isset($_r[$a])) {
                $_r[$a] += $_val[$a];
            } else {
                $_r[$a] = $_val[$a];
            }

        }
    }
}

/*********************** IS4 *************************/
$somme_is4_total = 0;
foreach ($moyenne_is4 as $val) {
    $somme_is4_total += $val;
}
if ($nb_eval_total == 0) {
    $is4_total = 0;
} else {
    $is4_total = ($somme_is4_total / $nb_eval_total) * 100;
}

$is4_total = number_format($is4_total, 2);

/*********************** IS5 ************************/
$somme_is5_total = 0;
foreach ($moyenne_is5 as $val) {
    $somme_is5_total += $val;
}

if ($nb_eval_total == 0) {
    $is5_total = 0;
} else {
    $is5_total = ($somme_is5_total / $nb_eval_total) * 100;
}

$is5_total = number_format($is5_total, 2);

/*********************** IS6 ************************/
$somme_is6_total = 0;
foreach ($moyenne_is6 as $val) {
    $somme_is6_total += $val;
}

if ($nb_eval_total == 0) {
    $is6_total = 0;
} else {
    $is6_total = ($somme_is6_total / $nb_eval_total) * 100;
}

$is6_total = number_format($is6_total, 2);

/*********************** IS7 ************************/
$somme_is7_total = 0;
foreach ($moyenne_is7 as $val) {
    $somme_is7_total += $val;
}

if ($nb_eval_total == 0) {
    $is7_total = 0;
} else {
    $is7_total = ($somme_is7_total / $nb_eval_total) * 100;
}

$is7_total = number_format($is7_total, 2);

/*********************** IS5_V7 ************************/
$somme_is5_v7_total = 0;
foreach ($moyenne_is5_v7 as $val) {
    $somme_is5_v7_total += $val;

}

if ($nb_eval_total == 0) {
    $is5_v7_total = 0;
} else {
    $is5_v7_total = ($somme_is5_v7_total / $nb_eval_total) * 100;
}

$is5_v7_total = number_format($is5_v7_total, 2);

/*************** Note sur 100 *****************************************/
if ($nb_eval_total == 0) {
    $notesur100 = "0.00";
} else {
    $notesur100 = $somme_total_general / $nb_eval_total;
}

/****** Note sur 10 ********************/
if ($nb_eval_total == 0) {
    $notesur10 = "0.00";
} else {
    $notesur10 = $somme_note10 / $nb_eval_total;
}

echo '
<tr style="height: 32px; ">
  <td align="center" colspan="2" style="background:#000;color:#fff;font-weight:bold;font-size:12px;border-right:1px solid #fff;">INDICATEUR TOTAL</td>
  <td class="separation">&nbsp;</td>
  <td align="center" colspan="2" style="background:#000;color:#fff;font-size:12px;font-weight:bold;"><span id="identifiant_evaluateur" style="float:left;margin-left:10px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="identifiant_com" style="float:right;margin-right:46px;color:red;"></span></td>
  <td class="separation">&nbsp;</td>
  <td align="center" colspan="2" style="background:#000;color:#fff;font-size:12px;font-weight:bold;"><span>' . $nom_type_traitement . '</span></td>
</tr>

<tr>
<td class="titre_label indicateur" style="color:#CE0000;">Nombre d\' évaluation</td>
<td class="indicateur"><input readonly style="color:#CE0000;" type="text" class="indicateur_total" id="id_nb_evaluation_total" value="' . $nb_eval_total . '" size="10"/></td>
 <td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com"></td><td class="indicateur_par_com"></td>
<td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com"></td><td class="indicateur_par_com"></td>
</tr>

<tr>
<td class="titre_label indicateur">Note / 10</td>
<td class="indicateur"><input readonly type="text" class="indicateur_total" id="id_note10_total" value="' . number_format($notesur10, 1) . '" size="10" /></td>
 <td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com">Note/10</td class="indicateur_par_com"><td class="indicateur_par_com"><input readonly  class="indicateur_com" id="total_sur_dix" type="text" size="10" value="0.00"/></td>
<td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com"></td><td class="indicateur_par_com"></td>
</tr>

<tr>
<td class="titre_label indicateur">Note / 100</td>
<td class="indicateur"><input readonly type="text" class="indicateur_total" id="id_note100_total" value="' . number_format($notesur100, 2) . '" size="10" /></td>
 <td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com">Note/100</td><td class="indicateur_par_com"><input readonly class="indicateur_com" id="total_sur_cent" type="text" size="10" value="0.00"/></td>
<td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com"></td><td class="indicateur_par_com"></td>
</tr>

<tr>
<td class="titre_label indicateur">IS4</td><td class="indicateur"><input readonly type="text" class="indicateur_total" id="id_is4_total" value="' . $is4_total . '" size="10"/><span class="percent">%</span></td>
 <td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com">IS4</td><td class="indicateur_par_com"><input readonly class="is_class indicateur_com" id="is4_valeur" type="text" size="10" value="0" /> </td>
<td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com">Date de l\'évaluation :<span style="color:red">*</span></td><td style="padding-bottom:3px" class="indicateur_par_com"><input class="dossier_evaluation" readonly id="date_evaluation" type="text" value="' . date("d/m/Y") . '" /><span id="id_date_notation"></span></td>
</tr>

<tr>
<td class="titre_label indicateur"><span class="cacher">IS5</span></td><td class="indicateur"><input readonly type="hidden" class="indicateur_total" id="id_is5_total" value="' . $is5_total . '"  size="10"/><span class="percent cacher">%</span></td>
 <td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com"><span class="cacher">IS5</span></td><td class="indicateur_par_com"><input readonly class="is_class indicateur_com" id="is5_valeur" type="hidden" size="10" value="0"/> </td>
<td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com">Date de l\'appel :<span style="color:red">*</span></td><td style="padding-bottom:3px" class="indicateur_par_com"><input class="dossier_evaluation" id="date_appel" type="text" /></td>
</tr>


<tr>
<td class="titre_label indicateur">IS5</td><td class="indicateur"><input readonly type="text" class="indicateur_total" id="id_is5_v7_total" value="' . $is5_v7_total . '"  size="10"/><span class="percent">%</span></td>
 <td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com">IS5</td><td class="indicateur_par_com"><input readonly class="is_class indicateur_com" id="is5_v7_valeur" type="text" size="10" value="0"/> </td>
<td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com">Numéro de dossier :</td><td style="padding-bottom:3px" class="indicateur_par_com"><input class="dossier_evaluation" id="numero_dossier" type="text" /></td>
</tr>

<tr>
<td class="titre_label indicateur">IS6</td><td class="indicateur"><input readonly type="text" class="indicateur_total" id="id_is6_total" value="' . $is6_total . '"  size="10"  /><span class="percent">%</span></td>
 <td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com">IS6</td><td class="indicateur_par_com"><input readonly class="is_class indicateur_com" id="is6_valeur" type="text" size="10" value="0"/> </td>
<td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com">Numéro de commande :</td><td style="padding-bottom:3px" class="indicateur_par_com"><input class="dossier_evaluation" id="numero_commande" type="text" /></td>
</tr>

<tr>
<td class="titre_label indicateur">IS7</td><td class="indicateur"><input readonly type="text" class="indicateur_total" id="id_is7_total" value="' . $is7_total . '"  size="10"  /><span class="percent">%</span></td>
 <td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com">IS7</td><td class="indicateur_par_com"><input readonly class="is_class indicateur_com" id="is7_valeur" type="text" size="10" value="0"/> </td>
<td class="separation">&nbsp;</td>
<td class="titre_label indicateur_par_com">Numéro de référence :<span style="color:red">*</span></td><td style="padding-bottom:3px" class="indicateur_par_com"><input class="dossier_evaluation" readonly id="numero_reference" type="text" value="' . utf8_decode($nom_fichier) . '" /></td>
</tr>

<tr>
	<td class="indicateur">&nbsp;&nbsp;</td>
	<td class="indicateur">&nbsp;&nbsp;</td>
	<td class="separation">&nbsp;</td>
	<td class="indicateur_par_com">&nbsp;&nbsp;</td>
	<td class="indicateur_par_com">&nbsp;&nbsp;</td>
	<td class="separation">&nbsp;</td>
	<td class="titre_label indicateur_par_com">Type de l\'appel :</td><td style="padding-bottom:3px" class="indicateur_par_com">
	<select style="color: #0060b4;font-size: 12px;width: 92%;" id="type_appel" ' . $type_appel_readonly . ' >
	<option value=0>-- Choix --</option>';
$res_typo = getTypologieByProjet($id_projet);
while ($rs_typo = pg_fetch_array($res_typo)) {
    if ($id_typologie == $rs_typo['id_typologie']) {
        $select_type = 'selected';
    } else {
        $select_type = '';
    }
    echo '<option value=' . $rs_typo['id_typologie'] . ' ' . $select_type . '>' . utf8_decode($rs_typo['libelle_typologie']) . '</option>';
}
echo '</select></td>
</tr>';
$repartition = getAllRepartition();
$nb_rep_     = pg_num_rows($repartition);
$val_r       = 0;
while ($res_rep = pg_fetch_array($repartition)) {
    if (isset($_r[$res_rep['id_repartition']])) {
        if ($_r[$res_rep['id_repartition']] == '') {
            $_r[$res_rep['id_repartition']] = 0;
            $val_r                          = $_r[$res_rep['id_repartition']];
        } else {
            $val_r = $_r[$res_rep['id_repartition']];
        }
    }
    echo '<tr>
	<td class="titre_label indicateur">' . $res_rep['libelle_repartition'] . '</td>
	<td class="indicateur"><input readonly type="text" class="indicateur_total" id="repartition_' . $res_rep['id_repartition'] . '_total" value="' . $val_r . '"  size="10" /></td>
	 <td class="separation">&nbsp;</td>
	<td class="titre_label indicateur_par_com">' . $res_rep['libelle_repartition'] . '</td>
	<td class="indicateur_par_com"><input readonly class="indicateur_com" type="text" id="repartition_' . $res_rep['id_repartition'] . '" value="0" size="10"/></td>
	<td class="separation">&nbsp;</td>
	<td class="titre_label indicateur_par_com"></td><td class="indicateur_par_com"></td>
	</tr>';
}
echo '</table></div>';
/***************************************************************************************************/
/***************************************************************************************************/
$res_com   = getNotationCom($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement, $id_tlc);
$table_com = array();
while ($_res = pg_fetch_array($res_com)) {
    $table_com[] = $_res['id_notation'];
}
if ($_SESSION['matricule'] == 6211) {
    print '<pre>';
    print_r($table_com);
    print '</pre>';
}

/*  ***  Tableau à cacher *** **************/
//if($_SESSION['matricule'] == 6568 || $_SESSION['matricule'] == 6211)
if ($_SESSION['matricule'] == 6211) {
    echo '<div id="id_div_liste_notation" style="display:block">';
} else {
    echo '<div id="id_div_liste_notation" style="display:none">';
}
echo '<label>Nombre de répartition : </label><input type="text" size="5" id="id_nombre_repartition" value="' . $nb_rep_ . '" />
<table>';
echo '<tr><td>Total</td><td>';
foreach ($table_com as $val) {
    echo '<input type="text" size="5" id="total_' . $val . '" class="class_total" value="' . number_format($totalG[$val], 2) . '" />';
}
echo '</td></tr>';

echo '<tr><td>IS4</td><td>';
foreach ($table_com as $val) {
    echo '<input type="text" size="5" id="is4_' . $val . '" class="class_is_' . $val . ' class_is4" value="' . $moyenne_is4[$val] . '" />';
}
echo '</td></tr>';

echo '<tr><td>IS5</td><td>';
foreach ($table_com as $val) {
    echo '<input type="text" size="5" id="is5_' . $val . '" class="class_is_' . $val . ' class_is5" value="' . $moyenne_is5[$val] . '" />';
}
echo '</td></tr>';

echo '<tr><td>IS6</td><td>';
foreach ($table_com as $val) {
    echo '<input type="text" size="5" id="is6_' . $val . '" class="class_is_' . $val . ' class_is6" value="' . $moyenne_is6[$val] . '" />';
}
echo '</td></tr>';

echo '<tr><td>IS7</td><td>';
foreach ($table_com as $val) {
    echo '<input type="text" size="5" id="is7_' . $val . '" class="class_is_' . $val . ' class_is7" value="' . $moyenne_is7[$val] . '"/>';
}
echo '</td></tr>';

echo '<tr><td>IS5_V7</td><td>';
foreach ($table_com as $val) {
    echo '<input type="text" size="5" id="is5_v7_' . $val . '" class="class_is_' . $val . ' class_is5_v7" value="' . $moyenne_is5_v7[$val] . '"/>';
}
echo '</td></tr>';

$repartition = getAllRepartition();
while ($res_rep = pg_fetch_array($repartition)) {
    echo '<tr><td>' . $res_rep['libelle_repartition'] . '</td><td>';
    foreach ($table_com as $val) {
        $result_rep = getCalculRep($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement, $val, $id_tlc, $tab_rep);
        echo '<input type="text" class="class_rep_' . $val . ' class_rep_' . $res_rep['id_repartition'] . '" size="5" id="' . $res_rep['id_repartition'] . '_' . $val . '" value="' . $result_rep[$val][$res_rep['id_repartition']] . '"/>';
    }
    echo '</td></tr>';
}

echo '
</table>
</div>';
/* ***** Fin tableau ************************/

/*********************************************************************************************************/
/*********************************************************************************************************/
$zHtml = '<style type="text/css">
		#info tr td {
		width:200px;
		}
		#info {
		font-size:10px;
		font-family:Arial;
		}


		.tooltip{width:227px;margin:0;padding:5px;color:#399ACC;background:#fff;border:5px solid #ccc;}
		.tooltip p{margin:0;/**text-align:justify;*/font-size:12px;}

		</style>';

/****************** Njiva ******************************************/
//$zHtml .= '<input type="hidden" id="idfichier" value="'.$id_fichier.'" />';
$flag_penalite = get_flag_penalite($id_projet);
$zHtml .= '<table  style="padding-bottom:20px;width:100%;margin:10px 0 -20px -3px;">
<tr>

<td width="5%">
<select style="height:20px;width:90px;" id="id_com" onchange="setNotationCom(' . $id_projet . ',' . $id_client . ',' . $id_application . ',' . $id_type_traitement . ',' . $id_tlc . ',' . $id_fichier . ',' . $droit_eval . ');">
<option value="0">Nouveau</option>';
$iCom       = 1;
$result_com = getNotationCom($id_fichier, $id_projet, $id_client, $id_application, $id_type_traitement, $id_tlc);
while ($res_com = pg_fetch_array($result_com)) {
    $zHtml .= '<option value="' . $res_com['id_notation'] . '">Com ' . $iCom . '</option>';
    $iCom++;
}

$zHtml .= '</select></td>
<td width="5%">
<a title="Actualisation de la grille" onclick="actualiser_grille(' . $id_projet . ',' . $id_client . ',' . $id_application . ',0,' . $id_type_traitement . ',' . $id_tlc . ',' . $id_fichier . ',' . $droit_eval . ');" id="id_actualiser_grille" class="icone">
<img width="20px" height="20px" alt="Actualisation" src="images/refresh.png" style="display:block;position:relative;float:left;cursor:pointer;" />
</a>';
if ($id_type_traitement == 1 || $id_type_traitement == 2) {
    $zHtml .= '<img width="20px" height="20px" alt="Ecouter" src="images/player3.png" style="display:block;position:relative;float:left;cursor:pointer;margin: 0 0 0 10px;" title="Ecouter l\'enregistrement" id="id_ecoute_player" />';
}
$zHtml .= '</td>
<td width="20%" style="color:#0060B4;font-family:Verdana;font-size:11px;font-weight:bold;"></td>
<td width="30%"></td>
<td width="16%" id="id_titleCom" style="text-align:center;font-family:Verdana;">';
if ($flag_penalite == 1) {
    $zHtml .= '<a  title="Information pénalité" onclick="get_information();" style="cursor:pointer" id="demo_7" href="#tooltip_3">
				     <img   src="images/information_1.jpeg" width="30px" height="30px" /></a><!--<span style="color:#ce0000;float:right;margin-top:11px;font-size:12px;">Cette grille contient des pénalités</span>-->';
}
$zHtml .= '<div style="display:none;position:absolute;background:#fff;border:1px solid #399ACC;left:1010px;top:680px;" id="tooltip_3" class="tooltip">
 <a style="float:right;margin-top:-5px;font-color:#399ACC;" href="#" rel="close"><img title="Fermer" src="images/fermer.jpg" width="25px;height=25px;"/></a>
				<h2>Information</h2>
				<p>Cette grille contient des pénalités</p>
				</div>';
$zHtml .= '</td>
</tr>
</table>';

/*******Tableau caché********/

/**
$zHtml .= '<table  border="1">';
$zHtml .= '<tr>';
$zHtml .= '<td>Total</td>';
$zHtml .= '<td>';
$zHtml .= '</td>';
$zHtml .= '</tr>';

$zHtml .= '<tr>';
$zHtml .= '<td>IS4</td>';
$zHtml .= '<td>';
$zHtml .= '</td>';

$zHtml .= '<tr>';
$zHtml .= '<td>IS5</td>';
$zHtml .= '<td>';
$zHtml .= '</td>';

$zHtml .= '<tr>';
$zHtml .= '<td>IS6</td>';
$zHtml .= '<td>';
$zHtml .= '</td>';

$zHtml .= '<tr>';
$zHtml .= '<td>IS7</td>';
$zHtml .= '<td>';
$zHtml .= '</td>';

$zHtml .= '</tr>';
$zHtml .= '</table>';*/
/***************/
/*******************************************************************/

$zHtml .= '<div id="contenu_notation" style="overflow-y: auto; height: 450px; border: 1px solid #668791;position:relative;display:block;" >';
$zHtml .= " <input type='hidden' id='testAppel'>";
$results = fetchAllResults($id_projet, $id_client, $id_application, 0, $id_type_traitement, $id_tlc, $id_fichier, $droit_eval);
$res     = explode('#**#**#', $results);
$zHtml .= $res[0];
$zHtml .= '</div>';
$zHtml .= '<div id="id_div_bouton_save">' . $res[1] . '</div>';
$zHtml .= '<div id="id_appui_amelioration_preco">
	<textarea id="id_point_appui" placeholder="Points d\'appui" style="resize:none;width:300px;height:70px;">' . $res[2] . '</textarea>
	<textarea id="id_point_amelioration" placeholder="Points d\'amélioration" style="resize:none;width:300px;height:70px;">' . $res[3] . '</textarea>
	<textarea id="id_preconisation" placeholder="Préconisations" style="resize:none;width:300px;height:70px;">' . $res[4] . '</textarea>
</div>';
echo $zHtml;
?>
 <!--   </div>
  </div>
 </div>

</body>

</html>-->

