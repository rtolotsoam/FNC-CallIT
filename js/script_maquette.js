function do_Total(id_cat,i,id_classement,id_section,id_grille,id_grille_application,flag_penalite,penalite_projet,test_blur,id_type_traitement,id_client){ 

	//console.log('id_classement:'+id_classement+' ; id_section:'+id_section+' , id_cat:'+id_cat+' , id_grille :'+id_grille+' , id_grille_application :'+id_grille_application)
	total_cat = 0;
	var tab_max = 0;
	var x = 0;
	var arr = [];
	var Tab = [];
	var Tab1 = [];
	var note = 0;
	var k_=0;
	var note_pondere = 0;
	var somme_ponderation =0;
	var total_note = total_ponderation = 0;
	//hng
	// $('#note_'+id_grille+'_'+id_grille_application+' option').each(function() {
	//$('.par_classement_'+id_classement+' option').each(function() {
	//	arr.push($(this).val())
	//});
	//
	$('.par_classement_'+id_classement).each(function(e) {
	  
		if ( $(this).find('option:selected').text()== 'N')
		{
			$(this).parent().next().find('select').val(0);
		}			
		else
		{ 				    
			$(this).parent().next().find('select').val(1);
		}
	  
		Tab.push($(this).val());
		//console.log('name:'+$(this).attr('name')+' .par_classement_'+id_classement+' , id:'+$(this).attr('id'))
		$('#'+$(this).attr('id')+' option').each(function() {
			arr.push($(this).val())
		});

		note = $(this).val();
		flag = $(this).attr('name').split('_');

		//console.log('arr : '+arr);
		//console.log('tab : '+Math.max.apply(null, arr));
		tab_max = Math.max.apply(null, arr);

		flag_pond = $(this).parent().next().find('select').val();
		var grille = $(this).attr('id').split('_');
		id_grille = grille[1];
		
		if( note =="-1")
		{
			note=0;
		}
       
		Tab1[k_] = [parseFloat($(this).val()),flag[1]];
	  
		
		//var x = note*flag_pond;
		if(isNaN(tab_max) || tab_max == '-1')
			tab_max = 0;

		if(tab_max > 0)	
			x = (parseFloat(note)/parseFloat(tab_max))*parseFloat(flag_pond);
		// TTL
		if(id_client == 643 && (id_type_traitement == 1 || id_type_traitement)) x = x*100;
		console.log("parseFloat(note): "+parseFloat(note)+" , parseFloat(flag_pond) :"+parseFloat(flag_pond)+' , parseFloat(tab_max) :'+parseFloat(tab_max)+' ,x : '+x);

       
        note_pondere += x;
       
        somme_ponderation += parseFloat(flag_pond);
        
        total_cat += parseFloat($(this).val()); 
   
		/*************/	
		$('#note_coeff_'+id_grille).val(x.toFixed(2) );		
		/*************/	 
        k_++;
        arr = [];
        x = 0;
	});
	//console.log('somme_ponderation1:'+somme_ponderation);
      
	if(parseFloat(somme_ponderation) == 0)
	{
		if(id_client == 643)
		{
			moyenne = 100;
		}
		else
		{
			moyenne = 1;
		}
		somme_ponderation = 1;
	}
	else
	{
		moyenne = note_pondere/somme_ponderation;
	}
        
	var j_=0;
	var Tab2=[];
	var all_classement = $(".par_classement");
	all_classement.each(function(e) {
		flag = $(this).attr('name').split('_');
		Tab2[j_] = [parseFloat($(this).val()),flag[1]];
		j_++;
	});
	
	var new_array2 = [];
		$.each(Tab2, function( index, tabFils2 ) {
		new_array2.push(tabFils2);
	});
	 
	/***************************/
	var new_array = [];
		$.each(Tab1, function( index, tabFils ) {
		new_array.push(tabFils);
	});

	var test_elimin = 0;
	$.each(new_array2, function( key, val ) {     
		if( (val[0]==0 || val[0]=="-1" )  && val[1]=="1" ){
			test_elimin = 1;
		}
	});
      
	/***************************/
	/**********************Calcul SI par classement*****************************/
	var nb_si_par_classement = 0;

	//var si_par_classement = $(".si_par_classement_"+id_classement);
	var si_par_classement = $(".class_commentaire_si_"+id_classement);
	si_par_classement.each(function(){
		if($(this).val()  != ''){
			 $(this).addClass('si_active_'+id_classement);
		}else{
			 $(this).removeClass('si_active_'+id_classement);
		}
	});
	var si_active = $('.si_active_'+id_classement);


	/***************************************************/
	var base_par_classement = $("#base_par_classement_"+id_classement).val();

	if( base_par_classement == 0 ){
		$("#total_par_classement_"+id_classement).val('0');
		// $("#base_par_classement_"+id_classement).val( somme_ponderation );
		$("#classement_base_"+id_classement).val( '0');
	}else{
		if( flag_penalite == 1 )
		{
			moyenne = test_nombre_si( si_active,id_classement,penalite_projet );
		}
		//console.log('moyenne:'+moyenne+' ; base_par_classement:'+base_par_classement)
		if( moyenne <=0 ){
			moyenne = 0;
		}
		//console.log('type_traitement ='+id_type_traitement);
		if((id_type_traitement == 1 || id_type_traitement == 2) && id_client != 643) //client différent de DELAMAISON
		{
			//console.log('moyenne = '+moyenne);
			$("#total_par_classement_"+id_classement).val( (moyenne*10).toFixed(2));
			//hng activer base_par_classement_
			//TTL $("#base_par_classement_"+id_classement).val( somme_ponderation );
			//$("#classement_base_"+id_classement).val(((moyenne*10).toFixed(2)*base_par_classement).toFixed(2));
			$("#classement_base_"+id_classement).val(((moyenne*10).toFixed(2)*base_par_classement).toFixed(2));
		}
		else
		{
			// console.log("moyenne "+moyenne);
			$("#total_par_classement_"+id_classement).val( (moyenne).toFixed(2));
			//hng activer base_par_classement_
			//TTL	$("#base_par_classement_"+id_classement).val( somme_ponderation );
			$("#classement_base_"+id_classement).val((((moyenne).toFixed(2))*base_par_classement).toFixed(2));
			//console.log($("#classement_base_"+id_classement).val())

			// if(id_type_traitement == 3) {
			if(id_type_traitement == 3 || id_type_traitement == 4) {
				$("#total_par_classement_"+id_classement).val( (moyenne*10).toFixed(2));
				$("#classement_base_"+id_classement).val((((moyenne*10).toFixed(2))*base_par_classement).toFixed(2));
			}
					
		}
	}
      
	var somme_ponderation_classement =0;
	var note_pondere_classement = 0;

	// class "setion_base" => total fond et forme
	var compteur_classe = 0;	
	var classe_base = $(".classement_base");
	var pond = '';
	var tab_classe_base = [];
	var ponderations_classements = $("#totaux").val();

	var vclassements = ponderations_classements.split('||');
	for (var i1=0; i1 < vclassements.length; i1++){
		var texte  = vclassements[i1] ;					
		var vclasspond = texte.split('|');
		var vfond  = vclasspond[3] ;
					
		if (id_section == vfond){  
			var vclass  = vclasspond[0] ;
			var vnote  = vclasspond[1] ;
			pond  = vclasspond[2] ;
			tab_classe_base.push(pond);	
			console.log('.....id_classement :'+vclass+' pond '+ pond );													
		}
					
	}
	  
	$('.par_section_'+id_section).each(function(e) {
		note_classement = $(this).val();
		//console.log('note_classement:'+note_classement)
		ponderation = $(this).attr('name').split('_');
		
		//hng
		//pond = $("#base_par_classement_"+id_classement).val();
		pond = tab_classe_base[compteur_classe];
		note_pondere_classement += parseFloat(note_classement)*parseFloat(pond);
		somme_ponderation_classement += parseFloat(pond);
		
		//console.log('pond:'+pond+' * '+note_classement+' ; somme_ponderation_classement:'+somme_ponderation_classement);
		
		moyenne_classement = note_pondere_classement/somme_ponderation_classement;
		
		//hng
		if( isNaN(moyenne_classement) == true )
			moyenne_classement = 0;
		
		//console.log('note_pondere_classement:'+note_pondere_classement+' ; somme_ponderation_classement:'+somme_ponderation_classement+' ; moyenne_classement:'+moyenne_classement+' ; ponderation_name:'+ponderation)
		//total_note += moyenne_classement;
		compteur_classe = compteur_classe+1;
	});
		
	//hng
	total_note += moyenne_classement;
	total_ponderation += somme_ponderation_classement;
	
	//console.log('id_classement:'+id_classement+' note_pondere_classement:'+note_pondere_classement+' ; somme_ponderation_classement:'+somme_ponderation_classement+' ; moyenne_classement:'+moyenne_classement+' ; ponderation_'+id_section+':'+$("#ponderation_"+id_section).val()+ ' ; total_note :'+total_note+' total_ponderation:'+total_ponderation)	
		
	//if( isNaN(moyenne_classement) == true ){
	if( isNaN(total_note) == true ){
			$("#total_"+id_section).val( 0 );
			$("#produit_ponderation_"+id_section).val( 0 );
	}else{
			//$("#total_"+id_section).val( moyenne_classement.toFixed(2));
			//$("#produit_ponderation_"+id_section).val( (moyenne_classement.toFixed(2)*$("#ponderation_"+id_section).val()).toFixed(2) );
			
			$("#total_"+id_section).val( total_note.toFixed(2));
			$("#produit_ponderation_"+id_section).val( (total_note.toFixed(2)*$("#ponderation_"+id_section).val()).toFixed(2) );
	}		
		
	var somme_ponderation_section=0;    
	var par_section = $(".par_section");
	var par_produit = $(".classement_produit");
	var par_base = $(".classement_base");
	var note_pondere_section = 0;
	var id_notation = $("#id_com").val(); 
	var _note_produit = 0;
	var _note_base = 0;
	/* *** Ajouté par Njiva le 31/07/2014 ******/
	par_produit.each(function( e ){
		_note_produit += parseFloat($(this).val());
			
	}); 
	par_base.each(function( e ){
		_note_base += parseFloat($(this).val());
				
	});
	_note_produit = parseFloat(_note_produit);
	_note_base = parseFloat(_note_base);

	if(_note_base != 0)
	{
		moyenne_section = _note_produit / _note_base;
	}
	else
	{
		moyenne_section = 0;
	}
		
	/* **************************** */
	var compteur_base = 0;
	var section_base = $(".section_base");
	var tab_section_base = [];
	section_base.each(function( e ){
		var base_section  = $(this).val();
		tab_section_base.push(base_section);
	});
	   
	   
	par_section.each(function( e ){
       	
		var note_section  = $(this).val();
		var ponderation_section_ = '';
		ponderation_section = $(this).attr('name').split('_');
		 
		//hng
		//ponderation_section[2] = $('.par_classement_'+id_classement).parent().next().find('select').val();
		//ponderation_section[2] = $("#base_par_classement_"+id_classement).val();
		ponderation_section_ = tab_section_base[compteur_base];
			 
		if( ponderation_section_ ==''  ){
			ponderation_section_=0;
		}
		
		//var z = note_section*ponderation_section[2];
		var z = note_section*ponderation_section_;
		note_pondere_section += z;
		if( isNaN(ponderation_section_)==true)
		{
			moyenne_section = 0;
		}
		else
		{
			somme_ponderation_section += parseInt(ponderation_section_);
			if( somme_ponderation_section==0 )
			{
			  moyenne_section = 0;
			}
			else
			{
			  //moyenne_section = (note_pondere_section/somme_ponderation_section)*10;
			  moyenne_section = note_pondere_section/somme_ponderation_section;
			}

		}
		compteur_base = compteur_base+1;
	});
	
	// console.log("note_pondere:"+note_pondere_section+" ; somme_ponderation:"+somme_ponderation_section+" ; moyenne_section:"+moyenne_section);
			 
	if(somme_ponderation_section == 0)
	{
		$("#total_general").val( '0.00' ); 
		$("#total_reel").val( '0.00' );
		$("#total_sur_cent").val( '0.00' );
	}
	else
	{	  
		/*$("#total_general").val( (moyenne_section*10).toFixed(2) ); 
		$("#total_reel").val( (moyenne_section*10).toFixed(2) );*/
		if((id_type_traitement == 1 || id_type_traitement == 2) && id_client != 643) //client différent de DELAMAISON
		{
			$("#total_general").val((moyenne_section*10).toFixed(2)); 
			$("#total_reel").val( (moyenne_section*10).toFixed(2) );
			//$("#total_sur_cent").val( moyenne_section.toFixed(2) )
			$("#total_sur_cent").val( (moyenne_section*10).toFixed(2) );
			//$("#total_sur_dix").val( (moyenne_section.toFixed(2)/10).toFixed(2));
			$("#total_sur_dix").val( moyenne_section.toFixed(1) );
		}
		else
		{
			//console.log('moyenne_section :'+moyenne_section)
			$("#total_general").val((moyenne_section).toFixed(2)); 
			$("#total_reel").val( (moyenne_section).toFixed(2) );
			$("#total_sur_cent").val( (moyenne_section).toFixed(2) );
			$("#total_sur_dix").val( (moyenne_section/10).toFixed(1) );
			
			// if(id_type_traitement == 3) {
			if(id_type_traitement == 3 || id_type_traitement == 4) {
				$("#total_general").val((moyenne_section*10).toFixed(2)); 
				$("#total_reel").val( (moyenne_section*10).toFixed(2) );
				$("#total_sur_cent").val( (moyenne_section*10).toFixed(2) );
			}
		}
	}
		
	//console.log('test_elimin:'+test_elimin);
	//
	//if(test_elimin==0)
	//
       
	/**************/
	// if((id_type_traitement == 1 || id_type_traitement == 2 || id_type_traitement == 3) && id_client != 643) //client différent de DELAMAISON
	if((id_type_traitement == 1 || id_type_traitement == 2 || id_type_traitement == 3 || id_type_traitement == 4) && id_client != 643) //client différent de DELAMAISON
	{
		moyenne_section = moyenne_section*10;
	}
	//console.log('moyenne section ='+moyenne_section);
	if( moyenne_section <29 /*|| test_elimin == 1*/){
		 $("#td_appreciation").css({"background":"#D10E20"});
		 $("#td_appreciation").html("<span>Insuffisant</span>");
	}else if( moyenne_section >=29 && moyenne_section<74 /*&& test_elimin != 1*/){
		 $("#td_appreciation").css({"background":"#FFD722"});//passable
	  $("#td_appreciation").html("<span>Passable</span>");
	}else if( moyenne_section >=74 && moyenne_section<80 /*&& test_elimin != 1*/)
	{  
		 $("#td_appreciation").css({"background":"#FF9968"});//satisfaisant
	  $("#td_appreciation").html("<span>Satisfaisant</span>");
	//}else if(  moyenne_section >=80 && moyenne_section<100  && test_elimin != 1){
	}else if(  moyenne_section >=80  /*&& test_elimin != 1*/){
		  $("#td_appreciation").css({"background":"#3AD539"});
		$("#td_appreciation").html("<span>Excellent</span>");
	}
	  
	/**************/
	var is_class = $(".is_class");
	var somme_produit_is4 = 0;
	var somme_pond_is4= 0;		

	var somme_produit_is5 = 0;
	var somme_pond_is5= 0;	

	var somme_produit_is6 = 0;
	var somme_pond_is6= 0;

	var somme_produit_is7 = 0;
	var somme_pond_is7= 0;

	var somme_produit_is5_v7 = 0;
	var somme_pond_is5_v7= 0;



	var counter_is6 = 0;

	var id_notation = $("#id_com").val();

	tab_nf=new Array('is4','is5','is5_v7','is6','is7');
	libelle_somme_prod=new Array();
	var libelle_somme_pond=new Array(0);

	is_class.each(function(e) 
	{
		
		var is_ = $(this).val();
		var is_1 = is_.split(';');
		//console.log(is_1);
		var vis = $(this);

		$.each(tab_nf, function(index){
			
			//if( is_.contains(tab_nf[index].toUpperCase()) ){
			if( jQuery.inArray( tab_nf[index].toUpperCase(), is_1 ) >=0 ){
                                      
				var moyenne =  'moyenne_'+tab_nf[index];
				var produit_ = vis.parent().next().next().next().next().next().find('input').val();

				if (isNaN(libelle_somme_prod[tab_nf[index]])) {
					libelle_somme_prod[tab_nf[index]] = 0;
				}
				libelle_somme_prod[tab_nf[index]] += parseFloat( produit_ );

				var base_ = vis.parent().next().next().next().next().find('select').val();

				if (isNaN(libelle_somme_pond[tab_nf[index]])) {
					libelle_somme_pond[tab_nf[index]] = 0;
				}
								
				libelle_somme_pond[tab_nf[index]] += parseFloat(base_);

				moyenne = (libelle_somme_prod[tab_nf[index]]/libelle_somme_pond[tab_nf[index]]).toFixed(2);

				if(( id_type_traitement == 1 || id_type_traitement == 2) && id_client != 643) //client différent de DELAMAISON
				{
					if( libelle_somme_prod[tab_nf[index]] == libelle_somme_pond[tab_nf[index]].toFixed(2) ){
						$("#"+tab_nf[index]+"_valeur").val( 1 );
						$("#"+tab_nf[index]+"_"+id_notation).val( 1 );
													 
					}else{
													  
						$("#"+tab_nf[index]+"_valeur").val( 0 );

						$("#"+tab_nf[index]+"_"+id_notation).val( 0 );
					}
				}else{
					if( moyenne >= 100 && libelle_somme_pond[tab_nf[index]]>0){

						$("#"+tab_nf[index]+"_valeur").val( 1 );
						$("#"+tab_nf[index]+"_"+id_notation).val( 1 );

					}else{

						$("#"+tab_nf[index]+"_valeur").val( 0 );

						$("#"+tab_nf[index]+"_"+id_notation).val( 0 );
					}
				}
			} 
		});
						  
						  /**
						       if( is_.contains('IS4') ){
							      var produit_ = $(this).parent().next().next().next().next().next().find('input').val();
								  somme_produit_is4 += parseFloat( produit_ );
								  var base_ = $(this).parent().next().next().next().next().find('select').val();
								  somme_pond_is4 += parseFloat(base_);
								  moyenne_is4 = (somme_produit_is4/somme_pond_is4).toFixed(2);
								  console.log(somme_produit_is4+"is4"+somme_pond_is4.toFixed(2));
								    if(( id_type_traitement == 1 || id_type_traitement == 2) && id_client != 643) //client différent de DELAMAISON
								    {
									       if( somme_produit_is4 == somme_pond_is4.toFixed(2) ){
									         
											 
											 $("#is4_valeur").val( 1 );
											 $("#is4_"+id_notation).val( 1 );
											 
											}else{
											    console.log('test__x');
											 $("#is4_valeur").val( 0 );
											 
											 $("#is4_"+id_notation).val( 0 );
											}
									}else{
										if( moyenne_is4 >= 100 && somme_pond_is4>0){
										 
										 $("#is4_valeur").val( 1 );
										 $("#is4_"+id_notation).val( 1 );
										 
										}else{
										  
										 $("#is4_valeur").val( 0 );
										 
										 $("#is4_"+id_notation).val( 0 );
										}
									}
							       }
								
								 if( is_.contains('IS5') ){
							      var produit_ = $(this).parent().next().next().next().next().next().find('input').val();
								  somme_produit_is5 += parseFloat( produit_ );
								  var base_ = $(this).parent().next().next().next().next().find('select').val();
								  somme_pond_is5 += parseFloat(base_);
								  moyenne_is5 = (somme_produit_is5/somme_pond_is5).toFixed(2);
								     if((id_type_traitement == 1 || id_type_traitement == 2) && id_client != 643) //client différent de DELAMAISON
								     {
									       if( somme_produit_is5 == somme_pond_is5.toFixed(2) ){
									
											 $("#is5_valeur").val( 1 );
											 $("#is5_"+id_notation).val( 1 );
											 
											}else{
											   
											 $("#is5_valeur").val( 0 );
											 
											 $("#is5_"+id_notation).val( 0 );
											}
									}else{
										if( moyenne_is5 >= 100 && somme_pond_is5>0){
										
										 $("#is5_valeur").val( 1 );
										 $("#is5_"+id_notation).val( 1 );
										 
										}else{
										   
										 $("#is5_valeur").val( 0 );
										 
										 $("#is5_"+id_notation).val( 0 );
										}
									}
							    }
								
								if( is_.contains('IS6') ){
							         //var produit_ = $(this).parent().next().next().next().find('select').val();
							         var produit_ = $(this).parent().next().next().next().next().next().find('input').val();
									   /** if( produit_ >=100 ){
										      counter_is6 ++;
										}
										 $("#is6_valeur").val( counter_is6 );
										 $("#is6_"+id_notation).val( counter_is6 );*/
							/**	 somme_produit_is6 += parseFloat( produit_ );
								  var base_ = $(this).parent().next().next().next().next().find('select').val();
								  somme_pond_is6 += parseFloat(base_);
								  moyenne_is6 = (somme_produit_is6/somme_pond_is6).toFixed(2);
								 if((id_type_traitement == 1 || id_type_traitement == 2) && id_client != 643) //client différent de DELAMAISON
								 {
									 
								       if( somme_produit_is6 == somme_pond_is6.toFixed(2) ){
								
										 $("#is6_valeur").val( 1 );
										 $("#is6_"+id_notation).val( 1 );
										 
										}else{
										   
										 $("#is6_valeur").val( 0 );
										 
										 $("#is6_"+id_notation).val( 0 );
										}
									}else{
										if( moyenne_is6 >= 100 && somme_pond_is6>0){
										
										 $("#is6_valeur").val( 1 );
										 $("#is6_"+id_notation).val( 1 );
										 
										}else{
										   
										 $("#is6_valeur").val( 0 );
										 
										 $("#is6_"+id_notation).val( 0 );
										}
									}
										 
							    }
								
								if( is_.contains('IS7') ){
							      var produit_ = $(this).parent().next().next().next().next().next().find('input').val();
								  somme_produit_is7 += parseFloat( produit_ );
								  var base_ = $(this).parent().next().next().next().next().find('select').val();
								  somme_pond_is7 += parseFloat(base_);
								  moyenne_is7 = (somme_produit_is7/somme_pond_is7).toFixed(2);
								      if((id_type_traitement == 1 || id_type_traitement == 2) && id_client != 643) //client différent de DELAMAISON
								      {
									       if( somme_produit_is7 == somme_pond_is7.toFixed(2) ){
									   
											 $("#is7_valeur").val( 1 );
											 $("#is7_"+id_notation).val( 1 );
											 
											}else{
											   
											 $("#is7_valeur").val( 0 );
											 
											 $("#is7_"+id_notation).val( 0 );
											}
									}else{
										if( moyenne_is7 >= 100 && somme_pond_is7>0){
										
										 $("#is7_valeur").val( 1 );
										 $("#is7_"+id_notation).val( 1 );
										 
										}else{
										   
										 $("#is7_valeur").val( 0 );
										 
										 $("#is7_"+id_notation).val( 0 );
										}
									}
							    }
							*/
	})
				   
	/**********Actualiser Total Indicateur*************/
	actualisationTotalIndicateur();
		  
}  
   
    /*************************do_total_2*******************************/
   
   function do_Total_2(id_cat,i,id_classement,id_section,id_grille,id_grille_application){ 
  
        total_cat = 0;
      var Tab = [];
      var Tab1 = [];
      var k_=0;
      var note_pondere = 0;
      somme_ponderation =0;
    
    
      $('.par_classement_'+id_classement).each(function(e) {
		 Tab.push($(this).val());
		 var note = $(this).val();
		 flag = $(this).attr('name').split('_');
		 flag_pond = $(this).parent().next().find('select').val();
		 var grille = $(this).attr('id').split('_');
		 id_grille = grille[1];
		
		 if( note =="-1")
		 {
			 note=0;
		 }
       
      Tab1[k_] = [parseFloat($(this).val()),flag[1]];
   
      var y = note*flag_pond;
	   
        note_pondere += y;
        somme_ponderation += parseFloat(flag_pond);
        moyenne = note_pondere/somme_ponderation;
        total_cat += parseFloat($(this).val()); 
   
	
	 // console.log("2___:"+y);
			/*************/	
               	  $('#note_coeff_'+id_grille).val( y );	

				
             	
			/*************/	 
        k_++;
      });
        
      
      /***************************/
	   // var coef = $('#note_'+id_grille+'_'+id_grille_application);
	    // console.log("oo"+ coef  );
      /***************************/
                  var j_=0;
         var Tab2=[];
                  var all_classement = $(".par_classement");
               all_classement.each(function(e) {
               flag = $(this).attr('name').split('_');
               Tab2[j_] = [parseFloat($(this).val()),flag[1]];
            j_++;
            });
         
       var new_array2 = [];
    
             $.each(Tab2, function( index, tabFils2 ) {
          new_array2.push(tabFils2);
         
      });
      
         
      /***************************/
      
        var new_array = [];
    
             $.each(Tab1, function( index, tabFils ) {
          new_array.push(tabFils);
         
      });
      
      var test_elimin = 0;
         $.each(new_array2, function( key, val ) {     
         
          if( (val[0]==0 || val[0]=="-1" )  && val[1]=="1" ){
           test_elimin = 1;
          }
      });
      
      /***************************/
        
         var base_par_classement = $("#base_par_classement_"+id_classement).val();
		 
             if( somme_ponderation == 0 ){
               $("#total_par_classement_"+id_classement).val('0');
			    $("#classement_base_"+id_classement).val( '0');
             }
                  else
				  {
                  $("#total_par_classement_"+id_classement).val( moyenne.toFixed(2));
				  $("#classement_base_"+id_classement).val( (moyenne.toFixed(2)*base_par_classement).toFixed(2));
				  
				  }
				  
				  
                  
         
      
           var somme_ponderation_classement =0;
           var note_pondere_classement = 0;
         $('.par_section_'+id_section).each(function(e) {
                  note_classement = $(this).val();
                  ponderation = $(this).attr('name').split('_');
             var y = note_classement*ponderation[2];
           note_pondere_classement += y;
            somme_ponderation_classement += parseInt(ponderation[2]);
                    moyenne_classement = note_pondere_classement/somme_ponderation_classement;
         });
         
         $("#total_"+id_section).val( moyenne_classement.toFixed(2) );
         $("#produit_ponderation_"+id_section).val( (moyenne_classement.toFixed(2)*$("#ponderation_"+id_section).val()).toFixed(2) );
         
      var somme_ponderation_section=0;    
      var par_section = $(".par_section");
      var note_pondere_section = 0;
                         par_section.each(function( e ){
        var note_section  = $(this).val();
        
         ponderation_section = $(this).attr('name').split('_');
		 
		   if( ponderation_section[2] ==''  ){
		      ponderation_section[2]=0;
		   }
		 
         var z = note_section*ponderation_section[2];
         
         note_pondere_section += z;
       
         somme_ponderation_section += parseInt(ponderation_section[2]);
         moyenne_section = note_pondere_section/somme_ponderation_section;
       }); 
      
          if(somme_ponderation_section == 0){

				    $("#total_general").val( 0 ); 
		            $("#total_reel").val( 0 )
				}else{
								  
				   $("#total_general").val( moyenne_section.toFixed(2) ); 
		           $("#total_reel").val( moyenne_section.toFixed(2) );
				}
        if ( test_elimin == 1 )
        {
           $("#total_general").val( "0" ); 
        }else{
           $("#total_general").val( moyenne_section.toFixed(2) ); 
        }
        /**************/
        //console.log(moyenne_section);
        if( moyenne_section <29 || test_elimin == 1){
             $("#td_appreciation").css({"background":"#D10E20"});
             $("#td_appreciation").html("<span>Insuffisant</span>");
        }else if( moyenne_section >=29 && moyenne_section<74 && test_elimin != 1){
             $("#td_appreciation").css({"background":"#FFD722"});//passable
          $("#td_appreciation").html("<span>Passable</span>");
        }else if( moyenne_section >=74 && moyenne_section<80 && test_elimin != 1)
        {  
             $("#td_appreciation").css({"background":"#FF9968"});//satisfaisant
          $("#td_appreciation").html("<span>Satisfaisant</span>");
        //}else if(  moyenne_section >=80 && moyenne_section<100  && test_elimin != 1){
        }else if(  moyenne_section >=80  && test_elimin != 1){
              $("#td_appreciation").css({"background":"#3AD539"});
            $("#td_appreciation").html("<span>Excellent</span>");
        }
          
        /**************/
   }
   
   
   /********************************************************/
  
   
   function changeBackground( id_grille,flag_elimin ,id_grille_application,id_classement,id_cat,id_section,flag_penalite,penalite_projet,test_blur,id_type_traitement){
   var i=0;
 do_Total(id_cat,i,id_classement,id_section,id_grille,id_grille_application,flag_penalite,penalite_projet,test_blur,id_type_traitement);
    
     var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
     var flag_el = $("#test_"+id_grille).val();

	 if(flag_el == 1 && com_si.val() != '')  //Manao modification an lay soratra
     {
 
       	   com_si.removeAttr("style");
       	   com_si.attr("style","width:97%;resize:none;background:#FF7C81;");
       	   $("#test_"+id_grille).val(1);
     }
     else if(flag_el == 0 && com_si.val() != '')  //Manampy soratra
     {

       	   com_si.removeAttr("style");
       	   com_si.attr("style","width:97%;resize:none;background:#FF7C81;");
           var nb_el = parseInt($("#nb_elimin").html());
           $("#nb_elimin").html(nb_el + 1);
           $("#test_"+id_grille).val(1);
     }
     else if(flag_el == 0 && com_si.val() == '')
     {

           com_si.removeAttr("style");
           com_si.attr("style","width:97%;resize:none;");
           $("#test_"+id_grille).val(0);
     }
     else if(flag_el == 1 && com_si.val() == '')
     {

           com_si.removeAttr("style");
           com_si.attr("style","width:97%;resize:none;");
           var nb_el = parseInt($("#nb_elimin").html());
           $("#nb_elimin").html(nb_el - 1);
           $("#test_"+id_grille).val(0);
     }
   
     /*************Calcul repartition**************//* Tsilavina **/
	 
	 //var compteur = 0;
	 var id_notation = $("#id_com").val();
	 var init_val = $("#init_val_"+id_grille).val();
	 //var init_val = $("#test_"+id_grille).val();
	 var id_rep = $("#rep_"+id_grille_application).val();
	 var compteur = parseInt($("#repartition_"+id_rep).val());
	 if(init_val == 1 && com_si.val() != ''){
		$("#init_val_"+id_grille).val(1);
	 }
	 else if( com_si.val() != "" && id_rep !=0 && init_val == 0){
		$("#repartition_"+id_rep).val( compteur+1 );
		$("#init_val_"+id_grille).val(1);
		/**************Pour la table caché******************/
		$("#"+id_rep+"_"+id_notation).val( compteur+1  );
		/********************************/
	 }
	 else if (  init_val == 0 && com_si.val() == ""){
		$("#init_val_"+id_grille).val(0)
	 }
	 else if( init_val == 1 && com_si.val() == ''){
		 $("#repartition_"+id_rep).val( compteur-1 );
		 /**************Pour la table caché******************/
		$("#"+id_rep+"_"+id_notation).val( compteur-1  );
		/********************************/
		$("#init_val_"+id_grille).val(0)
	 }
	  
     actualisationTotalIndicateur();
      
   }
   
   
function enregistrement_notation()
{
	//alert("Code en cours !"); return false;
	var note = $("select.par_classement");
	note.each(function(){
		if($(this).val() == '' || $(this).val() == -1)
		{
			alert('Le choix d\'une note dans la colonne "Point" est obligatoire !');
			return false;
		}
	});
	var id_notation = $("#id_com").val();
	var id_fichier = $("#idfichier").val();
	var id_projet = $("#idprojet").val();
	var id_client = $("#idclient").val();
	var id_application = $("#idapplication").val();
	var id_type_traitement = $("#idtypetraitement").val();
	var id_tlc = $("#idtlc").val();
	var tab = new Array();
	var table = new Array();
	
	if(id_notation == 0) // Si c'est une nouvelle notation
	{
		$.post("function_dynamique.php",
		{
			idnotation: id_notation,
			idfichier: id_fichier,
			idprojet: id_projet,
			idtlc: id_tlc
		},
		function(_data){
			id_notation = parseInt(_data);
			$.each(note, function( index, tab ) {
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
					go:1
				},
				function(_data){
					response = parseInt(_data);
					$("#"+id_note).val(-1);
					$("#id_com").val(0);
					$(".par_section_FOND").val(0);
					$(".par_section_FORME").val(0);
					$(".par_section").val('0.00');
					$("#total_general").val(0);
					$("#total_reel").val('0.00');
					$("#nb_elimin").html(0);
					getCommentaire.val('');
					getCommentaire_si.val('');
					getCommentaire_si.removeAttr("style");
	       	   		getCommentaire_si.attr("style","width:97%;resize:none;");
					$("#id_enregistrer").val('Enregistrer');
					$("#td_appreciation").removeAttr("style");
					$("#td_appreciation").attr("style","font-weight:bold;font-size:12px;text-align:center;background:#D10E20;");
					$("#td_appreciation").html('Insuffisant');
					//$('#id_titleCom').html('Nouveau');
					
					// Refresh Liste Notation
					$.post("function_dynamique.php",
					{
						_id_fichier: id_fichier,
						_id_projet: id_projet,
						_id_client: id_client,
						_id_application: id_application,
						_id_type_traitement: id_type_traitement,
						_id_tlc : id_tlc,
						refreshList:1
					},
					function(data) {
						$('#id_com').html(data);
					});
				});
			});			
		});
	}
	else  // C'est une modification d'une notation déjà existante
	{
		$.each(note, function( index, tab ) {
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
				go:2
			},
			function(_data){
				response = parseInt(_data);
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
				$("#total_general").val(0);
				$("#total_reel").val('0.00');
				$("#nb_elimin").html(0);
				getCommentaire.val('');
				getCommentaire_si.val('');
				getCommentaire_si.removeAttr("style");
       	   		getCommentaire_si.attr("style","width:97%;resize:none;");
				$("#id_enregistrer").val('Enregistrer');
				$("#td_appreciation").removeAttr("style");
				$("#td_appreciation").attr("style","font-weight:bold;font-size:12px;text-align:center;background:#D10E20;");
				$("#td_appreciation").html('Insuffisant');
				//$('#id_titleCom').html('Nouveau');
			});
    	});
	}
}

function setNotationCom(id_projet, id_client, id_application, id_type_traitement,id_tlc,id_fichier,droit_eval)
{
    actualise_liste();
    var s_matricule = $("#s_matricule").val();
	
	$('#img_loading_grille').show();
	var id_notation = $("#id_com").val();
	$.post("function_dynamique.php",
	{
		id_projet : id_projet,
		id_client : id_client,
		id_application : id_application,
		id_notation : id_notation,
		id_type_traitement : id_type_traitement,
		id_tlc:id_tlc,
		id_fichier:id_fichier,
		droit_eval:droit_eval
	},
	function(data)
	{
     	var _data = data.split('|||');
     	var contenu = _data[0].split('#**#**#');
     	
     	//console.log('type de l\'appel'+_data[7]);
     	//console.log(contenu[2]+'***'+contenu[3]+'***'+contenu[4]);
     	$("#contenu_notation").html(contenu[0]);
     	$("#id_div_bouton_save").html(contenu[1]);
     	
     	if(id_notation == 0)
		{
			$("#id_enregistrer").val('Enregistrer');
			$("#id_point_appui").val('');
			$("#id_point_amelioration").val('');
			$("#id_preconisation").val('');
		}
		else
		{
			$("#id_enregistrer").val('Modifier');
			$("#id_point_appui").val(contenu[2]);
     		$("#id_point_amelioration").val(contenu[3]);
     		$("#id_preconisation").val(contenu[4]);
		}
		var titleCom = $('#id_com option:selected').text();
		//$('#id_titleCom').html(titleCom);
	    $('#identifiant_com').html(titleCom);
         
	    if( titleCom=='Nouveau' )
	    {
			_data[2] ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			_data[3] ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			$('#date_evaluation').val( get_current_date );
		}
		else
		{
			// matricule_session != matricule_evaluateur
			if( s_matricule != _data[2] )
			{
				$.post("function_dynamique.php",
				{
					matricule_session: s_matricule,
					verif_matricule: 1
				},
				function(data)
				{
					var verif = parseInt(data);
					console.log('vérification : '+ verif);
					if(verif == 1)
					{
						$('#id_enregistrer').css('display','inline');
					}
					else
					{
						//console.log("zto2:"+s_matricule+"##"+_data[2]);
						//$("#id_div_btn_save").attr('hidden',true);			   
						$('#id_enregistrer').css('display','none');
						$(".btn_visu_nc").attr('disabled',true).addClass('classe_disable');			   		
						$('.btn_consult').attr('disabled',false).removeClass('classe_disable');
						
						var id_test_global = $('#id_test_global_nc').val();
						if(id_test_global == 1)
						{
							$('#btn_global_consulter_nc').css('display','inline');
							$('#btn_global_supprimer_nc').css('display','none');
							$('#btn_global_annuler_nc').css('display','none');
							$('#btn_global_editer_nc').css('display','none');
							$('#btn_global_nc').css('display','none');
						}
						else
						{
							$('#btn_global_consulter_nc').css('display','none');
							$('#btn_global_supprimer_nc').css('display','none');
							$('#btn_global_annuler_nc').css('display','none');
							$('#btn_global_editer_nc').css('display','none');
							$('#btn_global_nc').css('display','none');
						}
					}
				});
				
			}
			else
			{
				//$("#id_div_btn_save").attr('hidden',false);
				$('#id_enregistrer').css('display','inline');
			}
			$('#date_evaluation').val(_data[1]);
		}
		$('#identifiant_evaluateur').html(_data[2]+"&nbsp;&nbsp;&nbsp;"+_data[3]);
			
		//$('#id_date_notation').html(_data[1]);
		
		$("#date_appel").val( _data[4] ) ;
		$("#numero_dossier").val( _data[5] );
		$("#numero_commande").val( _data[6] );
		//$("#type_appel").val( _data[7] );
		//'option:selected').text()
		//$("#list option[value='2']").text()
		$('#img_loading_grille').hide();
	});
		
	/****************Recuperer valeur du tableau***************/
	var id_com = $("#id_com").val();
	var total_par_com = $("#total_"+id_com).val();
	$("#total_sur_cent").val( total_par_com );

	var class_is_com = $(".class_is_"+id_com);
	var class_rep_com = $(".class_rep_"+id_com);

	if( id_com != 0 ){
		$("#total_sur_dix").val( (total_par_com/10).toFixed(1) );
		class_is_com.each(function()
		{
			var val_is = $(this).val();
			var id_ = $(this).attr('id').split('_');
			
		          if( id_.length == 2 ){
				        id_is = id_[0];
				  }else{
				     id_is = id_[0]+'_'+id_[1];
				  }
			$("#"+id_is+"_valeur").val( val_is  );	
	    });
	}
	else{
	   //$(".is_class").val( 0 );			
	   $(".indicateur_com").val( 0 );	
	   
	   $("#total_sur_dix").val( '0.0' );
	   $("#total_sur_cent").val( '0.00' );
	}
	   
	class_rep_com.each(function()
	{
		var val_rep = $(this).val();
		var id_ = $(this).attr('id').split('_');
	
		$("#repartition_"+id_[0]).val( val_rep  );	
	});
	//actualisationTotalIndicateur();  
}

/***********************************************************************************************/
/***********************************************************************************************/
/***********************************************************************************************/

function admin_grille(id_projet,id_client,id_application,id_type)
{

   var nom_projet = $("#nom_projet").val();
   var nom_client = $("#nom_client").val();
   var nom_application = $("#nom_application").val();
   nom_projet = nom_projet.split(" ");
   nom_client = nom_client.split(" ");
   nom_application = nom_application.split(" ");
  
    tb_show("G\351rer les grilles du client","wizard_3.php?height=700&width=742&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&nom_projet="+nom_projet+"&nom_client="+nom_client+"&nom_application="+nom_application+"&id_type="+id_type);
   }
   
function gerer_grille(id_client,id_application,_i)
{
	var IdProjet = $("#id_projet"+_i).val();
	var nom_projet = $("#nom_projet").val();
	var nom_client = $("#nom_client").val();
	var nomApplication = $("#nom_application").val();
	var nomProjet = nom_projet.split(" ");
	var nomClient = nom_client.split(" ");
	
	tb_show("G\351rer les grilles","admin_type.php?height=300&width=850&nomProjet="+nomProjet+"&nomClient="+nomClient+"&nomApplication="+nomApplication+"&id_projet="+IdProjet+"&id_client="+id_client+"&id_application="+id_application);
}

function admin_note(id_projet,id_client,id_application)
{				 
	var nom_projet = $("#nom_projet").val();
	var nom_client = $("#nom_client").val();
	var nom_application = $("#nom_application").val();
	
	tb_show("G\351rer les notes des grilles","description.php?height=605&width=787&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&nom_projet="+encodeURIComponent(nom_projet)+"&nom_client="+encodeURIComponent(nom_client)+"&nom_application="+encodeURIComponent(nom_application));
}

function admin_classement(id_projet,id_client,id_application)
{				 
	var nom_projet = $("#nom_projet").val();
	var nom_client = $("#nom_client").val();
	var nom_application = $("#nom_application").val();
	
	tb_show("G\351rer les classements et leur pond\351ration","classement.php?height=520&width=733&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&nom_projet="+encodeURIComponent(nom_projet)+"&nom_client="+encodeURIComponent(nom_client)+"&nom_application="+encodeURIComponent(nom_application));
}

function gestion_questionnaire(){
	tb_show("Administration de Campagne","gest_questionnaire.php?height=600&width=850");
}

/***********************************************************************************************/
/***********************************************************************************************/
/***********************************************************************************************/

function setAutocomplete(id_projet, id_client, id_application)
{
	$.post("function_filtre_dynamique.php",
	{
		id_projet_auto:id_projet,
		id_client_auto:id_client,
		id_application_auto:id_application
	},
	function(data) {
		var availableTags = data;
		//console.log(availableTags);
		$( "#id_fichier_filtre" ).autocomplete({
			source: availableTags,
			select: function( event, ui ) {
				$( "#id_fichier_filtre" ).val( ui.item.label );
				//$("#idfichierfiltre").prop('value', ui.item.actor);
				return false;
			}
		})
	},'json');
	
}

/***********************************************************************************************/
/***********************************************************************************************/
/***********************************************************************************************/

//function actualiser_grille(id_projet, id_client, id_application, id_notation, id_type_traitement, id_fichier, id_tlc)
function actualiser_grille(id_projet, id_client, id_application, id_notation, id_type_traitement,id_tlc,id_fichier,droit_eval)
{

    actualise_liste();
    //actualisationTotalIndicateur();
		
    $('#img_loading_grille').show();
   
	
	$.post("function_dynamique.php",
	{
		id_projet: id_projet,
		id_client: id_client,
		id_application: id_application,
		id_notation: id_notation,
		id_type_traitement: id_type_traitement,
		id_tlc:id_tlc,
		id_fichier:id_fichier,
		droit_eval:droit_eval
	},
	function(data) {
		var _data = data.split('|||');
		var contenu = _data[0].split('#**#**#');
     	
     	$("#contenu_notation").html(contenu[0]);
     	$("#id_div_bouton_save").html(contenu[1]);
     	/*$("#id_point_appui").val('');
     	$("#id_point_amelioration").val('');
     	$("#id_preconisation").val('');*/
     	$("#id_point_appui").val(contenu[2]);
     	$("#id_point_amelioration").val(contenu[3]);
     	$("#id_preconisation").val(contenu[4]);
     	if(id_notation == 0)
		{
			$("#id_enregistrer").val('Enregistrer');
		}
		else
		{
			$("#id_enregistrer").val('Modifier');
		}
		$('#id_com').val('0');
		//var titleCom = $('#id_com option:selected').text();
		//$('#id_titleCom').html(titleCom);
		//$('#id_titleCom').html('Nouveau');
		//$('#id_date_notation').html(_data[1]);
		
		
		$('#img_loading_grille').hide();
		var today = get_current_date();
		if( _data[1]=='' ){
		  $('#date_evaluation').val( today );
		}else{
		  $('#date_evaluation').val( _data[1] );
		}
	});
	
	 /**************mise à zero des champs**********/
		   //$(".is_class").val( 0 );			
		   $(".indicateur_com").val( 0 );	
		   $("#total_sur_dix").val( '0.0' );
		   $("#total_sur_cent").val( '0.00' );
}

   function get_current_date(){
        var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(dd<10) {
			dd='0'+dd
		} 

		if(mm<10) {
			mm='0'+mm
		} 

		today = dd+'/'+mm+'/'+yyyy;
		return today;
   }
   
   function test_nombre_si( si_active,id_classement,penalite_projet ){
   /**val[0]=id_classement,val[1]=flag_condition,val[2]=valeur,val[3]=penalite,*/
      var condition = '';
      $.each(penalite_projet, function(key, val) {
	    if(val[1]==0  && id_classement == val[0]){

		   condition='==';
		     if( si_active.length == val[2]  && id_classement == val[0]){

			         moyenne = moyenne-val[3]; 
			 }
		}else if( val[1]==1  && id_classement == val[0] ){

		     if( si_active.length < val[2] ){
			         moyenne = moyenne-val[3]; 
			 }
		}else if( val[1]==2  && id_classement == val[0] ){

		    if( si_active.length > val[2] ){
			//console.log('3__x');
			         moyenne = moyenne-val[3]; 
			 }
		}else if( val[1]==3  && id_classement == val[0] ){

		    if( si_active.length <= val[2] ){
			//console.log(si_active.length+"##"+val[2]);
			         moyenne = moyenne-val[3]; 
			 }
		}else if( val[1]==4  && id_classement == val[0] ){

		    if( si_active.length >= val[2] ){
			         moyenne = moyenne-val[3]; 
			 }
		}
		
		// console.log(condition+"##"+val[2]+"##"+val[3]+"<br>");
	                
	  
	  })
              // if( si_active.length == 1 ){
					        // moyenne_ = moyenne_-30;
					   // }else if( si_active.length > 1  ){
					        // moyenne_ = moyenne_-50; 
					   // }
					    return  moyenne ;
   }
   
   
function admin_penalite(id_projet,id_client,id_application,id_type_traitement)
{
	var nom_projet = $("#nom_projet").val();
	var nom_client = $("#nom_client").val();
	var nom_application = $("#nom_application").val();
	
	tb_show("G\351rer les pénalités","penalite.php?height=492&width=787&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&id_type_traitement="+id_type_traitement+"&nom_projet="+encodeURIComponent(nom_projet)+"&nom_client="+encodeURIComponent(nom_client)+"&nom_application="+encodeURIComponent(nom_application));
   }
   
function get_information(){
   //console.log('123');
   $("#demo_7").simpletooltip({ click: true, hideOnLeave: false });
}
   
   
function actualise_liste(){
    var id_fichier = $("#idfichier").val();
	var id_projet = $("#idprojet").val();
	var id_client = $("#idclient").val();
	var id_application = $("#idapplication").val();
	var id_type_traitement = $("#idtypetraitement").val();
	var id_tlc = $("#idtlc").val();
	
    // Refresh Liste Notation
	$.post("function_dynamique.php",
	{
		_id_fichier: id_fichier,
		_id_projet: id_projet,
		_id_client: id_client,
		_id_application: id_application,
		_id_type_traitement: id_type_traitement,
		_id_tlc : id_tlc,					
		test_refresh:1
	},
	function(data) {
	    $('#id_div_liste_notation').html( data );
		actualisationTotalIndicateur();  
	});
}
   
function admin_duplication(id_projet,id_client,id_application)
{
   var nom_projet = $("#nom_projet").val();
   var nom_client = $("#nom_client").val();
   var nom_application = $("#nom_application").val();
   //console.log(nom_projet+"##"+nom_client+"##"+nom_application)
    tb_show("Duplication de la grille","duplication.php?height=250&width=479&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&nom_projet="+encodeURIComponent(nom_projet)+"&nom_client="+encodeURIComponent(nom_client)+"&nom_application="+encodeURIComponent(nom_application));
// tb_show("Duplication de la grille","duplication.php?height=492&width=400&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&nom_projet="+encodeURIComponent(nom_projet)+"&nom_client="+encodeURIComponent(nom_client)+"&nom_application="+encodeURIComponent(nom_application));
}

function gestion_typologie(id_projet,id_client,id_application){
	tb_show("Gestion des typologies","typologie.php?height=500&width=750&id_projet_typo="+id_projet+"&id_client_typo="+id_client+"&id_application_typo="+id_application);
}
   
      function dupliquer_grille(id_projet,id_client,id_application){
          var test_penalite = 0;
          if($("#dupliquer_penalite").is(':checked')){
		           test_penalite=1;
		  }
             var new_id_client_projet = $('#champ_client').val().split('_');
			 var new_id_application = $('#champ_application').val();
			  if(new_id_application=="" && new_id_client_projet==""){
			        $('#champ_application').css("border-color","red");
					$('#champ_client').css("border-color","red");
					return false;
			   }
			   else if( new_id_client_projet==""  ){
			       $('#champ_client').css("border-color","red");
				   $('#champ_application').css("border-color","#000");
				   return false;
			   }
			    else if( new_id_application==""  ){
			       $('#champ_application').css("border-color","red");
				    $('#champ_client').css("border-color","#000");
				   return false;
			   }
		       var new_id_client = new_id_client_projet[0];
		       var new_id_projet = new_id_client_projet[1];
			   
              var new_id_application = $('#champ_application').val();
       	  $.post("function_dynamique.php",
					{
				
						_id_projet_: id_projet,
						_id_client_: id_client,
						_id_application_: id_application,
                          _new_id_projet_:new_id_projet,
                         _new_id_client_:new_id_client,
                         _new_id_application_: new_id_application,						 
						 test_duplicate:1,
						 test_penalite:test_penalite
					},
					function(_data) {
					      if( _data==1 ){
						         alert("Duplication efféctué");
								 tb_remove() ;
						  }else{
						          alert("Erreur de duplication");
						  }
					});
   }

function create_nc(id_grille,flag_elimin ,id_grille_application,id_classement,id_cat,id_section,flag_penalite,penalite_projet,test_blur,id_type_traitement,id_projet,id_client,id_application,id_tlc,id_fichier,notation_id){
	var i=0;
	//do_Total(id_cat,i,id_classement,id_section,id_grille,id_grille_application,flag_penalite,penalite_projet,test_blur,id_type_traitement);
	
     var idTypeAppel = $("#testAppel").val();
     // alert(idTypeAppel);
    tb_show("Creation de FNC","nc_fiche.php?height=620&width=450&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&id_tlc="+id_tlc+"&id_fichier="+id_fichier+"&id_type_traitement="+id_type_traitement+"&notation_id="+notation_id+'&idTypeAppel='+idTypeAppel);   
   
}

function annuler_global_nc()
{
	$('#id_test_global_nc').val(0);
	$('#description_global_nc').val('');
	$('#exigence_global_nc').val('');
	
	$('#btn_global_nc').css('display','inline');
	$('#btn_global_consulter_nc').css('display','none');
	$('#btn_global_editer_nc').css('display','none');
	$('#btn_global_supprimer_nc').css('display','none');
	$('#btn_global_annuler_nc').css('display','none');
}

   
   function create_nc_si(id_grille,flag_elimin ,id_grille_application,id_classement,id_cat,id_section,flag_penalite,penalite_projet,test_blur,id_type_traitement,id_projet,id_client,id_application,id_tlc,id_fichier,notation_id)
   {
	   var i=0;
      // alert('1');
      var idTypeAppel = $("#testAppel").val();
      
do_Total(id_cat,i,id_classement,id_section,id_grille,id_grille_application,flag_penalite,penalite_projet,test_blur,id_type_traitement);
		
	   var test_nc_si = $('#id_test_nc_si_'+id_grille+'_'+id_grille_application).val();
	   var description_nc_si = $('#description_fnc_si_'+id_grille+'_'+id_grille_application).val();
	   var exigence_nc_si = $('#exigence_fnc_si_'+id_grille+'_'+id_grille_application).val();
	   if(test_nc_si == 0 && description_nc_si != '' && exigence_nc_si != '')
	   {
			tb_show("<b>SITUATION INACCEPTABLE</b>","nc_fiche_si.php?height=620&width=500&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&id_tlc="+id_tlc+"&id_fichier="+id_fichier+"&id_type_traitement="+id_type_traitement+"&notation_id="+notation_id+"&id_categorie="+id_cat+"&id_grille="+id_grille+"&id_grille_application="+id_grille_application+"&test_nc_si="+test_nc_si+'&idTypeAppel='+idTypeAppel);
	   }
	   else
	   {
	   		tb_show("<b>SITUATION INACCEPTABLE</b>","nc_fiche_si.php?height=620&width=500&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&id_tlc="+id_tlc+"&id_fichier="+id_fichier+"&id_type_traitement="+id_type_traitement+"&notation_id="+notation_id+"&id_categorie="+id_cat+"&id_grille="+id_grille+"&id_grille_application="+id_grille_application+"&test_nc_si="+test_nc_si+'&idTypeAppel='+idTypeAppel);
	   }
   }
   
   function insert_fnc(){

       var type = $("#id_client").val();
       var id_prestation = $("#id_prestation").val();
	   var type_traitement = $("#type_traitement").val();
	   var id_tlc = $("#id_tlc").val();
	   var id_fichier = $("#id_fichier").val();
	   var date_traitement = $("#date_traitement").val();
	   var date_evaluation = $("#date_evaluation").val();
	   var description_ecart =  $("#description_ecart").val();
	   var exigence_client =  $("#exigence_client").val();
	   
	   
       if( description_ecart == '' && exigence_client ==''){
	            $("#description_ecart").addClass("erreur");
	            $("#exigence_client").addClass("erreur");
				return false;
	   }else if( description_ecart =='' ){
	             $("#description_ecart").addClass("erreur");
	             $("#exigence_client").removeClass("erreur");
				 return false;
	   }else if( exigence_client =='' ){
	             $("#description_ecart").removeClass("erreur");
	             $("#exigence_client").addClass("erreur");
		   return false;
	   }
	   
	   
	$.post("insert_fnc.php",
	{
		type: type,
		id_prestation: id_prestation,
		type_traitement: type_traitement,
		id_tlc : id_tlc,
		id_fichier:id_fichier,
		date_traitement:date_traitement,
		date_evaluation:date_evaluation,
		description_ecart:description_ecart,
		exigence_client:exigence_client
	},
	function( data ) {
		if( data == 1 ){
		      alert("fiche ouverte");
			
			  tb_remove();
		}else{
		  alert("erreur lors de l'ouverture de la FNC");
	 }
		
	});  
   }

 function insert_fnc_si(id_grille,id_grille_application,matricule_tlc,id_projet,notation_id){

                       var type = $("#id_client").val();
                       var id_prestation = $("#id_prestation").val();
					   var type_traitement = $("#type_traitement").val();
					   var id_tlc = $("#id_tlc").val();
					   var id_fichier = $("#id_fichier").val();
					   var date_traitement = $("#date_traitement").val();
					   var date_evaluation = $("#date_evaluation").val();
					   var description_ecart =  $("#description_ecart").val();
					   var exigence_client =  $("#exigence_client").val();
					   var categorie_si =  $("#categorie_si").val();
					   
					   if( description_ecart == '' && exigence_client ==''){
					            $("#description_ecart").addClass("erreur");
					            $("#exigence_client").addClass("erreur");
								return false;
					   }else if( description_ecart =='' ){
					             $("#description_ecart").addClass("erreur");
					             $("#exigence_client").removeClass("erreur");
								 return false;
					   }else if( exigence_client =='' ){
					             $("#description_ecart").removeClass("erreur");
					             $("#exigence_client").addClass("erreur");
						   return false;
					   }
     
					$.post("insert_fnc_si.php",
					{
						type: encodeURIComponent(type),
						id_prestation: id_prestation,
						type_traitement: type_traitement,
						id_tlc : id_tlc,
						id_fichier:id_fichier,
						date_traitement:date_traitement,
						date_evaluation:date_evaluation,
						description_ecart:decodeURIComponent(description_ecart),
						exigence_client:decodeURIComponent(exigence_client),
						categorie_si:categorie_si,
						id_grille_application:id_grille_application,
						notation_id:notation_id
					},
					function( _data ) {
					    var data = _data.split('#');
					         if( data[0] == 1 ){
							          alert("fiche ouverte");

									  //$("#id_fnc").after("<input class='class_fnc' id='fnc_"+data[1]+"' type='text' value="+data[1]+" />");
									  
									  var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
									 $('#btn_nc_'+id_grille+'_'+id_grille_application).after("<input type='button' class='btn_visu_nc' id='remove_nc_"+id_grille+"_"+id_grille_application+"' value='Annuler' onclick='annuler_nc_si("+data[1]+","+id_grille+","+id_grille_application+")' />");
									 $('#btn_nc_'+id_grille+'_'+id_grille_application).hide();
									 
									  com_si.val('1');
                                      var flag_el = $("#test_"+id_grille).val();
									 test_bg_si(com_si,flag_el,id_grille,id_grille_application);
									 send_email( matricule_tlc,type,id_prestation,type_traitement, id_tlc,id_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client);
									  tb_remove();
							 }else{
							
							      alert("erreur lors de l'ouverture de la FNC");
								     
							 }
						
					});  
   }

   
function test_bg_si(com_si,flag_el,id_grille,id_grille_application)
{
    if(flag_el == 1 && com_si.val() != '')  //Manao modification an lay soratra
     {
 
       	   com_si.removeAttr("style");
       	   com_si.attr("style","width:97%;resize:none;background:#FF7C81;");
       	   $("#test_"+id_grille).val(1);
     }
     else if(flag_el == 0 && com_si.val() != '')  //Manampy soratra
     {

       	   com_si.removeAttr("style");
       	   com_si.attr("style","width:97%;resize:none;background:#FF7C81;");
           var nb_el = parseInt($("#nb_elimin").html());
           $("#nb_elimin").html(nb_el + 1);
           $("#test_"+id_grille).val(1);
     }
     else if(flag_el == 0 && com_si.val() == '')
     {

           com_si.removeAttr("style");
           com_si.attr("style","width:97%;resize:none;");
           $("#test_"+id_grille).val(0);
     }
     else if(flag_el == 1 && com_si.val() == '')
     {

           com_si.removeAttr("style");
           com_si.attr("style","width:97%;resize:none;");
           var nb_el = parseInt($("#nb_elimin").html());
           $("#nb_elimin").html(nb_el - 1);
           $("#test_"+id_grille).val(0);
     }
	 
	  /*************Calcul repartition**************//* Tsilavina **/
	 
	 //var compteur = 0;
	 var id_notation = $("#id_com").val();
	 var init_val = $("#init_val_"+id_grille).val();
	 //var init_val = $("#test_"+id_grille).val();
	 var id_rep = $("#rep_"+id_grille_application).val();
	 var compteur = parseInt($("#repartition_"+id_rep).val());
	 if(init_val == 1 && com_si.val() != ''){
		$("#init_val_"+id_grille).val(1);
	 }
	 else if( com_si.val() != "" && id_rep !=0 && init_val == 0){
		$("#repartition_"+id_rep).val( compteur+1 );
		$("#init_val_"+id_grille).val(1);
		/**************Pour la table caché******************/
		$("#"+id_rep+"_"+id_notation).val( compteur+1  );
		/********************************/
	 }
	 else if (  init_val == 0 && com_si.val() == ""){
		$("#init_val_"+id_grille).val(0)
	 }
	 else if( init_val == 1 && com_si.val() == ''){
		 $("#repartition_"+id_rep).val( compteur-1 );
		 /**************Pour la table caché******************/
		$("#"+id_rep+"_"+id_notation).val( compteur-1  );
		/********************************/
		$("#init_val_"+id_grille).val(0)
	 }
	  
     actualisationTotalIndicateur();

}

function effacer_nc_si(id_grille,id_grille_application)
{
	$('#btn_nc_'+id_grille+'_'+id_grille_application).css('display','inline');
	$('#annuler_nc_'+id_grille+'_'+id_grille_application).css('display','none');
	$('#btn_editer_nc_si_'+id_grille+'_'+id_grille_application).css('display','none');
	$('#description_fnc_si_'+id_grille+'_'+id_grille_application).val('');
	$('#exigence_fnc_si_'+id_grille+'_'+id_grille_application).val('');
	$('#commentaire_si_'+id_grille+'_'+id_grille_application).val('');
	//$("#test_"+id_grille).val(0);
	
	var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
	var flag_el = $("#test_"+id_grille).val();
	test_bg_si(com_si,flag_el,id_grille,id_grille_application);
}

function supprimer_global_nc(id_notation)
{
	if(confirm('Voulez-vous vraiment supprimer la Non Conformité ?'))
	{
		$.post('delete_nc_si.php',
		{
			delete_nc:1,
			id_notation:id_notation
		},function(_data){
			if(_data==1)
			{
				$('#btn_global_nc').css('display','inline');
				$('#btn_global_consulter_nc').css('display','none');
				$('#btn_global_editer_nc').css('display','none');
				$('#btn_global_supprimer_nc').css('display','none');
				$('#btn_global_annuler_nc').css('display','none');
				$('#id_test_global_nc').val(0);
				alert('Non conformité annulée');
			}
			else
			{
				alert('Erreur lors de l\'annulation');
			}
		});
	}
}

function annuler_nc_si(fnc_id,id_grille,id_grille_application,flag_elimin ,id_classement,id_cat,id_section,flag_penalite,penalite_projet,test_blur,id_type_traitement,id_projet,id_client,id_application,id_tlc,id_fichier,notation_id){
	if( !notation_id){
	   notation_id=0;
	}
	//console.log("eto:"+notation_id);
	var com_si = $("#commentaire_si_"+id_grille+"_"+id_grille_application);
	var test_nc_si = $("#id_test_nc_si_"+id_grille+"_"+id_grille_application);
	var btn_consulter_nc_si = $("#btn_consulter_nc_si_"+id_grille+"_"+id_grille_application);
	var btn_editer_nc_si = $("#btn_editer_nc_si_"+id_grille+"_"+id_grille_application);
	var flag_el = $("#test_"+id_grille).val();
	
	if(confirm('Voulez-vous vraiment supprimer la Non Conformité ?'))
	{
		$.post('delete_nc_si.php',
		{
			fnc_id:fnc_id,
			id_grille_application:id_grille_application,
			notation_id:notation_id
		},function(_data){
		         if(_data==1){
					  alert('Non conformité annulée');
					   $('#btn_nc_'+id_grille+'_'+id_grille_application).show();
					   $('#remove_nc_'+id_grille+'_'+id_grille_application).remove();
					 
					   /**$('#remove_nc_'+id_grille+'_'+id_grille_application).replaceWith("<input type='button' style='display:inline;background:#E0E9F5;' name='commentaire_si' value='Valider' id='btn_nc_"+id_grille+"_"+id_grille_application+"' class='si_par_classement_"+id_classement+" btn_visu_nc'  onclick='create_nc_si("+id_grille+","+flag_elimin+","+id_grille_application+","+id_classement+","+id_cat+","+id_section+","+flag_penalite+","+penalite_projet+","+test_blur+","+id_type_traitement+","+id_projet+","+id_client+","+id_application+","+id_tlc+","+id_fichier+","+notation_id+")' />");
					   */
					   com_si.val('');				   
					   test_bg_si(com_si,flag_el,id_grille,id_grille_application);
					   test_nc_si.val(0);
					   btn_consulter_nc_si.css('display','none');
					   btn_editer_nc_si.css('display','none');
				 }else{
					  alert('Erreur lors de l\'annulation');
				 }
		});
	}
}

function send_email( matricule_tlc,id_client,id_prestation,type_traitement, id_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,idclient,ref_nc )
{
	$.post('nc_mail.php',{
		matricule_tlc:matricule_tlc,
		id_client:decodeURIComponent(id_client),
		id_prestation:id_prestation,
		type_traitement:type_traitement,
		id_tlc:id_tlc,
		id_fichier:nom_fichier,
		date_traitement:date_traitement,
		date_evaluation:date_evaluation,
		categorie_si:categorie_si,
		description_ecart:description_ecart,
		exigence_client:exigence_client,
		ref_nc:ref_nc,
		idclient:idclient
	},
	function(_data){
		console.log('_data = '+_data);
		alert("Un email a été envoyé  au superviseur du Téléconseiller");
	});
}


function send_email_chq( matricule_tlc,id_client,id_prestation,type_traitement, id_tlc,nom_fichier,date_traitement,date_evaluation,categorie_si,description_ecart,exigence_client,idclient,ref_nc )
{
	$.post('nc_mail_chq.php',{
		matricule_tlc:matricule_tlc,
		id_client:decodeURIComponent(id_client),
		id_prestation:id_prestation,
		type_traitement:type_traitement,
		id_tlc:id_tlc,
		id_fichier:nom_fichier,
		date_traitement:date_traitement,
		date_evaluation:date_evaluation,
		categorie_si:categorie_si,
		description_ecart:description_ecart,
		exigence_client:exigence_client,
		ref_nc:ref_nc,
		idclient:idclient
	},
	function(_data){
		console.log('_data = '+_data);
		alert("Un email a été envoyé  au superviseur du Téléconseiller");
	});
}
