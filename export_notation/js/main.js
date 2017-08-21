$('document').ready(function(){
	$('.nb').keypress(function(e){
		if(((e.charCode<48)||(e.charCode>57))&&(e.charCode!=0)){
			e.preventDefault();
			e.stopPropagation();
		}
	});
	$('.nb').change(function(e){
		if($(this).val()==''){
			$(this).val('0');
		}
	});
	$('.not_editable').keypress(function(e){
		e.preventDefault();
		e.stopPropagation();
	});
  $('.not_editable').change(function(e){
    if (!verifie_datefr($(this).val()) && $(this).val()!='') {
      $(this).val('');
    }
  });
  $('#bt_reinit').click(function(){
	  window.location.replace("filtre_dynamique_exportation.php");
});
  //$("#id_filtre_matricule_cc").chosen();
	//$("#id_filtre_fichier").chosen();
	//$("#id_filtre_evaluateur").chosen();
  //$("#id_filtre_type_appel").chosen();
  $("#id_btn_filtre_visu").click(exportation);
});

function exportation(){
  var matr_cc = $("#id_filtre_matricule_cc").val() == null ? 0 : $("#id_filtre_matricule_cc").val();
  var matr_evaluateur = $("#id_filtre_evaluateur").val() == null ? 0 : $("#id_filtre_evaluateur").val();
  var fichier = $("#id_filtre_fichier").val() == null ? 0 : $("#id_filtre_fichier").val();
  var type_appel = $("#id_filtre_type_appel").val() == null ? 0 : $("#id_filtre_type_appel").val();
  var client = $("#id_filtre_client").val() == null ? 0 : $("#id_filtre_client").val();
 
  var prestation = $("#id_filtre_prestation").val() == null ? 0 : $("#id_filtre_prestation").val();
  var type_traitement = $("#id_filtre_type_traitement").val() == null ? 0 : $("#id_filtre_type_traitement").val();
  var dt_notation1 = transforme_date($("#id_filtre_date_notation_deb").val());
  var dt_notation2 = transforme_date($("#id_filtre_date_notation_fin").val());
  var dt_appel1 = transforme_date($("#id_filtre_date_appel_deb").val());
  var dt_appel2 = transforme_date($("#id_filtre_date_appel_fin").val());
  dt_notation1 = dt_notation2 == '' ? '' : dt_notation1;
  dt_notation2 = dt_notation1 == '' ? '' : dt_notation2;
  dt_appel1 = dt_appel2 == '' ? '' : dt_appel1;
  dt_appel2 = dt_appel1 == '' ? '' : dt_appel2;
  
   if( client == 0)
	  {
			$("#id_filtre_client").focus();
			alert("Merci de choisir un client.");
			return 0;
	  }
	if( prestation == 0)
	  {
			$("#id_filtre_prestation").focus();
			alert("Merci de choisir une prestation.");
			return 0;
	  }
	
	if( type_traitement == 0)
	  {
			$("#id_filtre_type_traitement").focus();
			alert("Merci de choisir un type de traitement.");
			return 0;
	  }
  
  if( dt_notation1 =='' && dt_appel2 == '' && dt_appel1 === '' &&  dt_appel2 =='' )
  {
	  $("#id_filtre_date_notation_deb").focus();
	  alert("Merci d'affiner votre filtre en ajoutant une date (appel et/ou notation)");
	  return 0;
  }
  
  var noteChmp = $("#id_note_filtre").val();
  if( noteChmp == 3)
  {
	  if($("#id_valeur_note_1").val() == 0 )
	  {
		  alert("Changer la veleur de votre note (note > 0).");
		  $("#id_valeur_note_1").focus();
	  }
  }
  
  var id_note_filtre = $('#id_note_filtre').val();
  var id_valeur_note_1 = $('#id_valeur_note_1').val();
  var id_valeur_note_2 = $('#id_valeur_note_2').val();
  
  if (!verif_coherance_date(dt_notation1, dt_notation2, dt_appel1, dt_appel2)) {
	    $.ajax({
             type: 'POST',
             url: 'export_notation/script_php/search_n_notation.php',
            data : {
				 cc:matr_cc,
				 eval:matr_evaluateur,
				 fichier:fichier,
				 presta:prestation,
				 t_traitement:type_traitement,
				 dt_notation1:dt_notation1,
				 dt_notation2:dt_notation2, 
				 dt_appel1:dt_appel1,
				 dt_appel2:dt_appel2,
				 id_client:client,
				 type_appel:type_appel,
				 id_note_filtre:id_note_filtre,
				 id_valeur_note_1:id_valeur_note_1,
				 id_valeur_note_2:id_valeur_note_2
			 },
            success: function(data)
			 {
				  if(data == 0)
				  {
					  alert("Aucun r\xE9sultat ne correspond \xE0 votre crit\xE8re.");
					  return false;
				  }else if(data > 600)
				  {
					  var exportation = confirm("La r\xE9cup\xE9ration de la grille va prendre beaucoup de temps.\n Voulez-vous continuer l'exportation de vos donn\xE9es ?");
					  if (exportation == true) 
						 {
							 window.location = "grid_export.php?cc="+matr_cc+"&eval="+matr_evaluateur+"&fichier="+fichier+"&presta="+prestation+"&t_traitement="+type_traitement+"&dt_notation1="+dt_notation1+"&dt_notation2="+dt_notation2+"&dt_appel1="+dt_appel1+"&dt_appel2="+dt_appel2+"&id_client="+client+"&id_note_filtre="+id_note_filtre+"&id_valeur_note_1="+id_valeur_note_1+"&id_valeur_note_2="+id_valeur_note_2+"&type_appel="+type_appel;
						}
					 else { return false;}
				 }else if(data > 0){
				  window.location = "grid_export.php?cc="+matr_cc+"&eval="+matr_evaluateur+"&fichier="+fichier+"&presta="+prestation+"&t_traitement="+type_traitement+"&dt_notation1="+dt_notation1+"&dt_notation2="+dt_notation2+"&dt_appel1="+dt_appel1+"&dt_appel2="+dt_appel2+"&id_client="+client+"&id_note_filtre="+id_note_filtre+"&id_valeur_note_1="+id_valeur_note_1+"&id_valeur_note_2="+id_valeur_note_2+"&type_appel="+type_appel;
				  }
			 },
             error: function() {
             alert('La requête n\'a pas abouti'); }
           });  
   
  }
}

function verif_coherance_date(dtn1, dtn2, dta1, dta2){
  var msg_err = "Erreur:";
  var have_err = false;
  if (dtn1 != '') {
    if(dtn1 > dtn2){
      have_err = true;
      msg_err += "\n-L'intervale de la \"date notation\" est incoh\u00e9rente!";
    }
  }else{
    $("#id_filtre_date_notation_deb").val('');
    $("#id_filtre_date_notation_fin").val('');
  }
  if (dta1 != '') {
    if (dta1 > dta2) {
      have_err = true;
      msg_err += "\n-L'intervale de la \"date d'appel\" est incoh\u00e9rente!"
    }
  }else {
    $("#id_filtre_date_appel_deb").val('');
    $("#id_filtre_date_appel_fin").val('');
  }
  if(have_err){
    alert(msg_err);
  }
  return have_err;
}

function setPrestaClient(){
	var id_client = $('#id_filtre_client').val();
	$.post("export_notation/script_php/load_list.php",{
		id_client: id_client,
		action: 'prestation'
	},function(data){
		$('#id_filtre_prestation').html(data);
	});
  if (id_client == 0) {
    $('#id_filtre_type_appel').attr('disabled',true);
    $('#id_filtre_type_appel').html('<option value=0>-- Choisir ici --</option>');
  }
}

function setClientPresta(){
	var id_prestation = $('#id_filtre_prestation').val();
	$.post("export_notation/script_php/load_list.php",{
		id_prestation: id_prestation,
		action: 'client'
	},function(data){
		if(data == '0'){
			$('#id_filtre_type_appel').attr('disabled',true);
			$('#id_filtre_type_appel').html('<option value=0>-- Choisir ici --</option>');
			return false;
		}
		else{
			var rep = data.split('|||');
			$('#id_filtre_client').val(rep[0]);
			if(parseInt(rep[1]) == 0){
				$('#id_filtre_type_appel').attr('disabled',true);
				$('#id_filtre_type_appel').html('<option value=0>-- Choisir ici --</option>');
			}else{
				$('#id_filtre_type_appel').attr('disabled',false);
				$('#id_filtre_type_appel').html(rep[1]);
			}
		}
	});
}

function transforme_date(dt){
  if(dt != ''){
    var tbdt = $.trim(dt).split('/');
    return tbdt[2]+'-'+tbdt[1]+'-'+tbdt[0];
  }else{
    return "";
  }
}

function verifie_datefr(val){
	rep = false;
	var reg = /^\d{2}\/\d{2}\/\d{4}$/;
	if(reg.test($.trim(val))){
		rep = true;
	}
	return rep;
}
function afficheFiltreNote_consultation(){
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
		$('#id_valeur_note_1').val(0);
		$('#id_valeur_note_2').val(0);
		$('#id_et').css('visibility','visible');
	}
	else if(id_note == 3)
	{
		$('#id_valeur_note_1').css('visibility','visible');
		$('#id_valeur_note_1').val(1);
		$('#id_valeur_note_2').css('visibility','hidden');
		$('#id_et').css('visibility','hidden');
	}
	else
	{
		$('#id_valeur_note_1').css('visibility','visible');
		$('#id_valeur_note_1').val(0);
		$('#id_valeur_note_2').css('visibility','hidden');
		$('#id_et').css('visibility','hidden');
	}
}