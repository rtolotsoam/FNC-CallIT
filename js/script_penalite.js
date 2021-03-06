
function filtreDonneesPenalite(filtre)
	{
		var id_client = $('#id_nom_client_penalite').val();
		var id_application = $('#id_prestation_penalite').val();
		var id_projet = $('#id_projet_penalite').val();
		var id_type_traitement = $('#id_type_traitement_penalite').val();
		//var id_classement = $('#id_classement_penalite').val();
		var champ_class = filtre;
		$.post("function_classement.php",
		{
			id_client_class: id_client,
			id_application_class: id_application,
			id_projet_class: id_projet,
			id_type_traitement_class: id_type_traitement,
			champ_class: champ_class
		},
		function(data) {
			if(champ_class == 'client')
			{
				$('#id_prestation_penalite').html(data);
				
				$("#id_div_contenu_penalite").html('');
			}
			else if(champ_class == 'code')
			{
				var _data = data.split('||');
				$('#id_nom_client_penalite').val(_data[0]);
				$('#id_projet_penalite').val(_data[1]);
				
				$("#id_div_contenu_penalite").html('');
				
				var id_projet = _data[1];
				if(id_projet == '') 
				{
					id_projet = 0;
				}
				
				$.post("function_penalite.php",
				{
					id_projet : id_projet,
					id_type_traitement: id_type_traitement,
					verification_projet : 1
				},
				function(data_) {
					$("#id_div_suppr_penalite").html(data_);
				});
			}
			else if(champ_class == 'typetraitement')
			{
				$("#id_div_contenu_penalite").html('');
				id_projet = $('#id_projet_penalite').val();
				console.log('id_projet'+id_projet);
				console.log('id_type_traitement'+id_type_traitement);
				if(id_projet == '') 
				{
					id_projet = 0;
				}
				$.post("function_penalite.php",
				{
					id_projet : id_projet,
					id_type_traitement: id_type_traitement,
					verification_projet : 1
				},
				function(data_) {
					$("#id_div_suppr_penalite").html(data_);
				});
			}

			/*if(id_client != '' && id_application != '' && id_projet != '' && id_client != 0 && id_application != 0 && id_projet != 0 )
			{
				$("#id_div_contenu_penalite").html('');
			}*/
			
			
		});
	}
	
	function setTableauClassPond()
	{
		var id_client = $('#id_nom_client_class').val();
		var id_application = $('#id_prestation_class').val();
		var id_projet = $('#id_projet_class').val();
		
		$.post("function_classement.php",
		{
			id_client_class: id_client,
			id_application_class: id_application,
			id_projet_class: id_projet,
			setTableau: 1
		},
		function(data) {
				
			$("#id_div2_tablesorter").html(data);

		});
	}
	
	function setTableauPenalite(id_classement)
	{
		var id_client = $('#id_nom_client_penalite').val();
		var id_application = $('#id_prestation_penalite').val();
		var id_projet = $('#id_projet_penalite').val();
		var id_type_traitement = $('#id_type_traitement_penalite').val();
		var escape = 0;
		if(id_client == '' || id_client == 0 || id_application == '' || id_application == 0 || id_projet == '' || id_projet == 0 || id_type_traitement == '' || id_type_traitement == 0)
		{
			escape = 1;
		}
		if(escape == 1)
		{
			alert('Les champs du filtre sont obligatoires !');
			return false;
		}
		$.post("function_penalite.php",
		{
			idclient_penalite: id_client,
			idapplication_penalite: id_application,
			idprojet_penalite: id_projet,
			idtypetraitement_penalite: id_type_traitement,
			idclassement_penalite: id_classement,
			contenu: 1
		},
		function(data) {
			$("#id_div_contenu_penalite").html(data);
		});
	}
	
	function suppression_penalite_projet()
	{
		var id_projet = $("#id_projet_penalite").val();
		var id_type_traitement = $("#id_type_traitement_penalite").val();
		if(confirm('Voulez-vous vraiment supprimer toutes les p\351nalit\351s pour ce projet ?'))
		{
			$.post("function_penalite.php",
			{
				id_projet: id_projet,
				id_type_traitement: id_type_traitement,
				suppression_penalite_projet: 1
			},
			function(response) {
				var data = response.split('||');
				if(parseInt(data[0]) == 1)
				{
					alert('Suppression de toutes les p\351nalit\351s du projet avec succ\350s !');
					$("#id_div_contenu_penalite").html('');
					$("#id_div_suppr_penalite").html(data[1]);
				}
				else
				{
					alert('Une erreur s\'est produite lors de la suppression !');
				}
				
			});
		}
	}
	
	function setUpdatePenalite(id_projet_penalite)
	{
		var id_type_traitement = $('#id_type_traitement_penalite').val();
		$.post("function_penalite.php",
		{
			id_projet_penalite: id_projet_penalite,
			id_type_traitement : id_type_traitement,
			donnees: 'update'
		},
		function(data) {
			var rep = data.split('&&&');
			$("#id_condition").val(parseInt(rep[0]));
			$("#id_valeur_condition").val(parseInt(rep[1]));
			$("#id_penalite").val(parseFloat(rep[2]));
			$("#idprojetpenalite").val(id_projet_penalite);
			$("#id_ajouter_penalite").val('Modifier');
			
		});
	}
	
	function nouveau_penalite()
	{
		$("#id_condition").val(0);
		$("#id_valeur_condition").val('');
		$("#id_penalite").val('');
		$("#idprojetpenalite").val('');
		$("#id_ajouter_penalite").val('Ajouter');
	}
	
	function ajout_penalite(id_projet,id_classement)
	{
		var condition = $("#id_condition").val();
		var valeur_condition = $("#id_valeur_condition").val();
		var penalite = $("#id_penalite").val();
		var id_projet_penalite = $("#idprojetpenalite").val();
		var id_type_traitement = $('#id_type_traitement_penalite').val();
		var escape = 0;
		if(valeur_condition == '' || valeur_condition == 0 || penalite == '' || penalite == 0)
		{
			escape = 1;
		}
		if(escape == 1)
		{
			alert('Les champs vides ou valeurs à 0 ne sont pas acceptés!');
			return false;
		}
		$.post("function_penalite.php",
		{
			id_projet: id_projet,
			id_classement: id_classement,
			condition : condition,
			valeur_condition : valeur_condition,
			penalite : penalite,
			id_projet_penalite : id_projet_penalite,
			id_type_traitement: id_type_traitement,
			donnees: 'ajout'
		},
		function(data) {
			if(parseInt(data) == 1)
			{
				alert('Insertion avec succ\350s !');
			}
			else
			{
				alert('Modification avec succ\350s !');
			}
			setTableauPenalite(id_classement);
			$.post("function_penalite.php",
				{
					id_projet : id_projet,
					id_type_traitement : id_type_traitement,
					verification_projet : 1
				},
				function(data_) {
					$("#id_div_suppr_penalite").html(data_);
				});
		});
	}
	
	function setDeletePenalite(id_projet_penalite,id_classement)
	{
		var id_type_traitement = $('#id_type_traitement_penalite').val();
		$.post("function_penalite.php",
		{
			id_projet_penalite : id_projet_penalite,
			id_type_traitement: id_type_traitement,
			donnees: 'delete'
		},
		function(data) {
			alert(data);
			setTableauPenalite(id_classement)
		});
	}
	
	function isNumber(evt) {
	    evt = (evt) ? evt : window.event;
	    var charCode = (evt.which) ? evt.which : evt.keyCode;
	    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
	        return false;
	    }
	    return true;
	}