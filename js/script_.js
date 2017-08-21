/***************************************************/
function visu_detail( _id_fichier )
	{
		tb_show("Visualisation","visualisation_note.php?height=600&width=850&id_fichier="+_id_fichier,null);
	}
function visu_detail( _id_fichier )
{
	tb_show("Visualisation","visualisation_note.php?height=600&width=850&id_fichier="+_id_fichier,null);
}
function valider(){
    
}
/***************************************************/
function afficher(i){
     var  player =  $("#show_player"+i);
     var  $attr_play = 'images/play3.jpeg';
     var  $attr_stop = 'images/stop7.jpeg';
     var  $img  = $('#play_button'+i).find('img');
     var  $img_attr = $img.attr('src');
	 
	 var scrRecord = $("#hiddenPlayer"+i).val();

        if($img_attr  == $attr_play  ){
                  
                   $('.show_player').each(function(){
                   
                        $(this).hide();
                        $(this).find("audio")[0].pause();
                        //$(this).find("audio")[0].currentTime = 0;
                   });
                   $('.img_play').each(function(){
                   	 $(this).attr('src',$attr_play);
                   });
                   $img.attr('src', $attr_stop);
                   player.show();
				   player.find("audio").attr('src',scrRecord);
                   player.find("audio")[0].play();

        } else{
           $img.attr('src', $attr_play);
           player.hide();
           player.find("audio")[0].pause();
        }
 
}
/******************************/

function retour(){
if(confirm('Voulez vous vraiment quitter cette page ?')){
var zProjet  = $("#projet_champ").val();
var iImmatricule = $('#matricule_not').attr("name") ;




                 $.ajax({
                        url:'loadfile.php',
                        method:'get',
                        data:{iImmatricule: iImmatricule, zProjet: zProjet},
                        success: function(_data2) {
                               var retour = _data2.split('##');
                               var nombre = retour[0];
                               var content = retour[1];
                               $('#form_block').show();                    
                        $("#listeFile").html(content);
                        $("#tbl_sorter").tablesorter({sortList: [[1,1]],headers:{0:{sorter:false},2:{sorter:false}}}).tablesorterPager({
					 container: $("#pager")
					 
				});  
                    }
                   });
}

}
 /*****************************/
  function noter(i){
 $('#date_entretien').attr('disabled', 'disabled');
 $("#form_block").hide();
   var fichier = $("#play_button"+i).attr('libelle');
   var chemin_video = $("#play_button"+i).attr('title'); 
   var matricule = $("#immatricule").val();
   var zProjet = $("#projet").val();  
   var zPlay = $("#show_player"+i).html();
                  $.ajax({
                    url:'notation1.php',
                    method:'get',
                    data:{fichier:fichier,matricule:matricule,zProjet:zProjet,zPlay:zPlay},
                    success: function(_data3) {
                       $('#listeFile').html(_data3);
                    }
                   });
 
 }
 /*****************************/
 function editer(i){
 
var zFichier = $("#id_fichier"+i).val();

var zProjet = $("#projet").val();  
var matriculeShow = $("#matriculeShow").val();  


           $.ajax({
                    url:'editfile.php',
                    method:'get',
                   data:{zFichier:zFichier,zProjet:zProjet,matriculeShow:matriculeShow},
                    success: function(_data3) {
                       $("#form_block").hide();
                       $("#listeFile").html(_data3);
                    }
                   });
 }
 /*****************************/
 
 function update(){
 var zProjet = $("#projetupdate").val();
    var data = "";
    var imatricule  = $("#matricule").val();
    var matriculeShow = $("#matriculeShow").val();
    var date_entr  = $("#date_entretien").val(); 

    var duree      = $("#duree_entretien").val(); 

    var date_rest  = $("#date_restitution").val();  

  // var ind0 = $('input[type=radio][name=indicateur0]:checked')
    var iCompteur  = $("#compteur").val();
    var fichier    = $("#fichier").val();
    var comment_gen = $("#comment_general").val();
    var objectif = $("#objectif").val();

    if(date_rest =='' ){
    alert('la date de restitution est obligatoire!');
    return false;
    }
            for(var i=0;i<iCompteur;i++){         
                  var inputsId = $("#note"+i).val();
				          /** if( $("#note"+i).is(':checked') ){
						        inputsId=1;
						  }else{
						    inputsId=0;
						  }*/
                data += inputsId+"&&"+$("#id_indicateur_not"+i).val()+'&&'+$("#accueil_comment"+i).val()+'||';
 
        }
        $.ajax({
            url:'update.php',
            type:'GET',
            data :{data:data,imatricule:imatricule,date_entr:date_entr,duree:duree,date_rest:date_rest,fichier:fichier,zProjet:zProjet, bpop: $("#hpop").val(),matriculeShow:matriculeShow,comment_gen:comment_gen,objectif:objectif},
 
            success:function(_data4){
    
			if ( _data4==1 )
			{
				alert("Modification effectuée!");
				window.tb_remove();
                do_filtre();
			}else{
				$("#listeFile").html(_data4);
			}
            }
    });
 
 }
        /*********supprimer projet********/
        function suppr_projet(i){
               var iIdProjet = $("#id_projet"+i).val();

                $.ajax({
                url:'suppr_projet.php',
                type:'GET',
                data:{iIdProjet:iIdProjet},
                success:function(_datasuppr){
                          if(_datasuppr == 'ok'){
                                   alert('suppression reussie');
                                   window.location.href='interface.php';
                          } else{
                               alert('Echec de la suppression');
                          }
                }                            
            });
        
        }
		
        /**********archivage  du projet*******/
		  function archive_projet(id_application,id_client,i){
		 
               var IdProjet = $("#id_projet"+i).val();
			 
               if(confirm('Voulez vous vraiment archiver ce projet ?')){    
                  $.ajax({
                   url:'archive_projet.php',
                   type:'POST',
                   data:{IdProjet:IdProjet,id_application:id_application,id_client:id_client,type_update:'1'},
                   success:function(_datasuppr){
                          if(_datasuppr == 'ok'){
						      alert('Archivage effectué');
                                   window.location.href='interface.php';
                          } else {
                               alert('Echec d \' archivage');
                          }
                    }                            
                   });
             }
        }
        /**********edition du projet*******/
        function modif_projet(i){
        var iIdProjet = $("#id_projet"+i).val();
        
              $.ajax({
              url : 'edit_projet.php',
              type:'GET',
              dataType:'json',
              data:{iIdProjet:iIdProjet},
              success:function(_dataprojet1){
              
              //alert(_dataprojet1.id_application);
              var nom_projet = _dataprojet1.nom_projet;
			  alert(nom_projet);
              var nom_repertoire = _dataprojet1.nom_repertoire;
              var id_projet = _dataprojet1.id_projet;
              var id_application = _dataprojet1.id_application;
              var id_client = _dataprojet1.id_client;
                           $("#champ_projet").val(nom_projet);
             $('#champ_repertoire option[value="'+nom_repertoire+'"]').attr("selected", "selected");
             $('#champ_client option[value="'+id_client+'"]').attr("selected", "selected");
             $("#champ_cache").val(id_projet);
             $("#td_submit").html("<center><input type='submit' id='submit_interface' value='modifier' /></center>");
                        $.ajax({
                    url:'select_app.php',
                    type:'get',
                    data:{zClient:$('#champ_client').val()},
                     success:function(_data1){
                     console.log(_data1);
                      $("#champ_application").html(_data1);
					  //2014-01-31
					 // tb_show("Ajout de nouveau Campagne","formulaire_campagne.php?height=200&width=400");	
                    }
              });                          
             setTimeout(function() {
                 $('#champ_application option[value="'+id_application+'"]').attr("selected", "selected");
             },100);
              }
              
              })

        }
        /************essaie*********/
       
 /*****************************/
$(document).ready(function(){
       $("#champ_cache").val('');
	   $('#loader').hide();
       $( "#date_restitution" ).datepicker({ dateFormat: 'yy-mm-dd' });      
        $('input[name=date_entretien_manuel]').datepicker({ dateFormat: 'yy-mm-dd' });
       $("#immatricule").change(function(){
             iImmatricule = $(this).val();
             zProjet  = $("#projet").val();
                if(iImmatricule == ""){
                //$("#search_result").html('<strong>Les Résultats seront affichés ci-dessous</strong>');
                $("#listeFile").html('');
                }else{
					$('#loader').show();
                    $.ajax({
                            url:'loadfile.php',
                            method:'get',
                           data:{iImmatricule: iImmatricule, zProjet: zProjet},
                            success: function(_data2) {                                
                               var retour = _data2.split('##');
                               var nombre = retour[0];
                               var content = retour[1];                             
                               if(nombre > 0)
                               {
                                  $('#loader').show();
                               } else{
                               $('#loader').hide();
                                }                               
                               $("#listeFile").html(content);
                              $("#tbl_sorter").tablesorter ({sortList: [[1,1]],headers:{0:{sorter:false},2:{sorter:false}}}).tablesorterPager({
					 container: $("#pager")
					 
				}); 
			       $('#loader').hide();								
                            }
                           });
                }
           
         });

            $("#projet").change(function(){
                 
                $("#listeFile").html('');
                $("#search_result").html('<strong>Les Résultats seront affichés ci-dessous</strong>');
                variable = $(this).val();
                if ( variable == '' )
                {
                    $("#immatricule").html("<option value=''>-- S&eacute;lectionner --</option>");
            $("#libelle_pers").html("TLC/Op&eacute;rateur:");
                    $("#listeFile").html('');
                     $("#search_result").html('<strong>Les Résultats seront affichés ci-dessous</strong>');
                }else{                  
                      $.ajax({
                        url: 'loaddirectory.php',
                        method: 'get',
                        data: 'variable=' + variable,
                        success: function(_data) {
                        
                                     if(variable == 45){
                                $("#libelle_pers").html("TLC/Op&eacute;rateur:");
                                     }else{
                             $("#libelle_pers").html("TLC/Op&eacute;rateur:");
                                     }
                            $("#immatricule").css('size','14px');
                            $("#immatricule").html(_data);
                            
                        }
                       });
		   
		    }
		   });
                  
           $("#matricule_not").change(function(){
                
                iMatricule = $(this).val();
              if(iMatricule == ''){
               $("#fichier").html("<option value=''>-- S&eacute;lectionner --</option>");
              }else{
                  $.ajax({
                    url: 'selectfile.php',
                    method: 'get',
                    data:'iMatricule='+iMatricule,
                    success: function(_data1) {
                    
                        $("#fichier").html(_data1);
                        
                    }
                   });
               } 
            });
            
            
        /**************AFFICHAGE DES GRILLES******************/
        $("#type_traitement").change(function(){
		
        var zTraitement = $(this).val();
        var zProjet = $("#projet_champ").val();
  
        if(zTraitement == ''){
        $("#grille_block").html('');
        }else if(zTraitement == '1'){
            $.ajax({
                    url:'grille_entr_tsila.php',
                    type:'get',
                    data:{zTraitement:zTraitement,zProjet:zProjet},
                    success:function(_data){
                    $("#grille_block").html(_data);
                    }
            })
          }else if(zTraitement == '2'){
                $.ajax({
                    url:'grille_sort_tsila.php',
                    type:'get',
                    data:{zTraitement:zTraitement,zProjet:zProjet},
                    success:function(_data){
                    $("#grille_block").html(_data);
                    }
            })

           }else{
                $.ajax({
                            url:'grille_email_tsila.php',
                            type:'get',
                            data:{zTraitement:zTraitement,zProjet:zProjet},
                            success:function(_data){
                            $("#grille_block").html(_data);
                            }
                    })

            }   
        }); 
        /*************************validation du formulaire****************************/
            $("#champ_client").change(function(){
			
              var zClient = $(this).val();
              $.ajax({
                    url:'select_app.php',
                    type:'get',
                    data:{zClient:zClient},
                     success:function(_data1){
                      $("#champ_application").html(_data1);
                    }
              });
            });
            /******************************************************/
          /**  $('#form_interface').submit(function(){
			
               var idProjet = $("#champ_cache").val();
               var zProjet     =      $("#champ_projet").val();
               var zRepertoire =      $("#champ_repertoire").val();
               var zClient     =      $("#champ_client").val();
               var zApplication =     $("#champ_application").val();
               
               $.ajax({
               url:'insert_projet.php',
                    type:'get',
                    data:{zProjet:zProjet,zClient:zClient,zRepertoire:zRepertoire,zApplication:zApplication,idProjet:idProjet},
                    success:function(_data2){
                            if ( _data2 == 'ok' ) {
								window.alert('Projet inséré !');
								window.location.href='interface.php';
								$('#interface_result').html(_data2);
                            }else if ( _data2 == 'd' )
							{
								alert("Nom de projet déjà existant !");
							}else{
                                alert("Echec de l'insertion !");
                            }
                       
                    }
               });
               return false;
               
            });*/
            /**************edit Projet**********/
            
            
            $("#typetraitement").change(function(){
   
 
                   $.ajax({
                     url:'index2_visualisation.php',
                     type:'GET',
                     data :{log_tc:$("#tc").val(),log_traitement:$(this).val()},
                         success:function(_data9){
                              $("#dv_note").html(_data9);
                        }

                    });            
            })
  
});//fin ready

function do_insert()
{
          
             var   zProjet1 = $("#projet_champ").val();
 
             var data = "";

             var matricule  = $("#matricule_not").val();   

             var date_entr  = $("#date_entretien").val(); 

             var heure      = $("#duree_entretien").val(); 

             var matriculeRetour = $("#matriculeRetour").val();
             var iCompteur  = $("#compteur").val();

             var fichier    = $("#fichier").val();  

             var dureewav =  $("#duree").val();
             var commentaire_gen = $("#comment_general").val();
             var objectif = $("#objectif").val();
            var bError = 0;             
             for(j=0;j<iCompteur;j++){
                   if($("#accueil_comment"+j).val()==''){
                         bError=1;
                   }
             }
             if ( zProjet1 =='') 
             {
                alert('Merci de choisir un projet !');
                $("#projet_champ").css("border-color","red");
                return 0;
             }else{
                $("#projet_champ").css("border-color","black");
             }
             
             if (matricule =='')
             {
                alert('Merci de choisir un téléconseiller !');
                $("#matricule_not").css("border-color","red");
                return 0;
             }else{
                 $("#matricule_not").css("border-color","black");
             }
             
             if( date_entr =='' )
             {
                 alert("Merci de spécifier la date de l'entretien !");
                $("#date_entretien").css("border-color","red");
                return 0;
             }else{
                 $("#date_entretien").css("border-color","black");
             }
             
             if ( fichier =='' )
             {
                 alert("Merci de saisir le libellé !");
                 $("#fichier").css("border-color","red");
                return 0;
             }else{
                 $("#fichier").css("border-color","black");
             }
              if ( commentaire_gen =='' )
             {
                 alert("Merci de remplir le commentaire général !");
                 $("#comment_general").css("border-color","red");
                return 0;
             }else{
                 $("#comment_general").css("border-color","black");
             }
             if ( objectif =='' )
             {
                 alert("Merci de préciser l'objectif !");
                 $("#objectif").css("border-color","red");
                return 0;
             }else{
                 $("#objectif").css("border-color","black");
             }
             if(bError ==1)
             {
                alert('Merci de remplir les commentaires !');
             }else{
                $("#test_submit").val('1');
				/*************2014-01-10****************/
				
				/*****************************/
                for(var i=0;i<iCompteur;i++){
				        var type =  $("#note"+i).attr('type');
						
                        var inputsId = $("#note"+i).val();
						 
				           if( type=='checkbox' && $("#note"+i).is(':checked') ){
						        inputsId=1;
						   }else if ( type=='checkbox' && !$("#note"+i).is(':checked') ) {
						     inputsId=0;
						   }
						   console.log(i+'#'+inputsId);
                       data += inputsId+"&&"+$("#accueil_comment"+i).val()+"&&"+$("#grille"+i).val()+'||';
                      
                  }
               
                   $.ajax({
                     url:'insert.php',
                     type:'GET',
                     data :{data:data,matricule:matricule,date_entr:date_entr,heure:heure,fichier:fichier,matriculeRetour:matriculeRetour,dureewav:dureewav,zProjet1:zProjet1,commentaire_gen:commentaire_gen,objectif:objectif},
                    success:function(_data8){
                         if(_data8 == 'non'){
            alert('ce fichier n \'appartient pas au matricule '+matricule); 
                                                                
                         }
                        else if(_data8 == 'ok'){
                          alert("Notation enregistrée!");
                          window.location.href = 'index.php';
                          //$("#listeFile").html(_data8);
                         }else{
                           alert("Echec de Notation");
                        }
                     
                    
                    }
                 });
             }

        }
		
		function affiche_archive(){

	     
		tb_show("Campagnes archivés","archive_campagne.php?height=300&width=750");
		
		}
		
		function restaurer_campagne(id_client,id_application,i){
	
		      var IdProjet = $("#id_projet_"+i).val();
		
		      $.ajax({
                url:'archive_projet.php',
                type:'POST',
                data:{IdProjet:IdProjet,id_client:id_client,id_application:id_application,type_update:'0'},
                success:function(_datasuppr){
                          if(_datasuppr == 'ok'){
						      alert('Restauration effectuée');
                                   window.location.href='interface.php';
                          } else{
                               alert('Echec d \' archvage');
                          }
                }                            
            });
		
		}
		
		
		function gerer_grille(id_client,id_application,_i){
		       var IdProjet = $("#id_projet"+_i).val();
		       var nomProjet = $("#nom_projet"+_i).val();
		       var nomClient = $("#nom_client"+_i).val();
		       var nomApplication = $("#nom_application"+_i).val();
			 var nomProjet = nomProjet.split(" ");
			 var nomClient = nomClient.split(" ");

		     tb_show("Gérer les grilles","admin_type.php?height=300&width=850&nomProjet="+nomProjet+"&nomClient="+nomClient+"&nomApplication="+nomApplication+"&id_projet="+IdProjet+"&id_client="+id_client+"&id_application="+id_application);
		}
		
		function affiche_ajout_formulaire()
		{
		  
            tb_show("Ajout de nouveau Campagne","formulaire_campagne.php?height=200&width=479&iIdProjet=0");					
		}
		
		function fill_application(){
		
		 var zClient = $("#champ_client").val();
		 
              $.ajax({
                    url:'select_app.php',
                    type:'get',
                    data:{zClient:zClient},
                     success:function(_data1){
					     _data1 = _data1.split('#');
						 var optApplication = _data1[0];
						 var optCampagne    = _data1[1];
                      $("#champ_application").html( optApplication );
                      $("#champ_campagne").html( optCampagne );
                    }
              });
		
		}
        function insert_projet(){
		
               var idProjet    = $("#champ_cache").val();
               var zProjet     =      $("#champ_projet").val();
               var zCampagne =      $("#champ_campagne").val();
               var zClient     =      $("#champ_client").val();
               var zApplication=     $("#champ_application").val();
               
               $.ajax({
               url:'insert_projet.php',
                    type:'get',
                    data:{zProjet:zProjet,zClient:zClient,zCampagne:zCampagne,zApplication:zApplication,idProjet:idProjet},
                    success:function(_data2){
                            if ( _data2 == 'ok' ) {
								window.alert('Projet inséré !');
								window.location.href='interface.php';
								$('#interface_result').html(_data2);
                            }else if ( _data2 == 'd' )
							{
								alert("Nom de projet déjà existant !");
							}else{
                                alert("Echec de l'insertion !");
                            }
                       
                    }
               });
               return false;		
		}

   function gestion_questionnaire(){

tb_show("Administration de Campagne","gest_questionnaire.php?height=600&width=850");
   }
   
   function get_categorie(){
       var idTraitement = $("#slct_traitement").val();
	             if( idTraitement=='' ){
				     $("#div_corps").hide();
				     return false;
				 }
				 else{
				 $("#div_corps").show();
				 $("#div_loading").show();
	     /**$.post('ajax_script/ajax_type_traitement.php',(changer)*/
	     $.post('ajax_script/ajax_categorie.php',
		  {
		    IdTraitement:idTraitement,
		  },function (_data){
			   $("#div_loading").hide();
		       $("#tabs-1").css('width','100%');
			   
		       $("#div_corps").html(_data);
			   /**$('#tab_categorie').tablesorter();*/
			   
			   $("#tab_categorie tbody").sortable({

						cursor: 'move',
						delay: 180,
                        update: function()
						{
							var rowsOrder = $(this).sortable("serialize");
                                  console.log(rowsOrder);
						   $.post("update_ordre_categorie.php", { action:'change_rows_order', table:'tab_categorie', order:'category_order', rows_order:rowsOrder } );
						}
					 
				}).enableSelection();
			   
		    }
		  );
		} 
   }
   function affiche_item(){
                 var Categorie = $("#slct_categorie").val();
				 
				      Categorie = Categorie.split('#');
					  idCategorie = Categorie[0];
					  libelleCategorie = Categorie[1];
				
					  

				  $.post('ajax_script/ajax_item.php',
		  {
		    idCategorie     :idCategorie,
			libelleCategorie:libelleCategorie,
		  },function (_data){
		     
		       $("#div_corps").html(_data);
			   $('#tab_item').tablesorter();
		    }
		  );
				 
   }
   
   function editer_categorie( id_categorie ){
      $(".span_categorie").hide();
      $(".btn_edit").hide();
      $(".cache_categorie").show();
	  $(".btn_modif_cat").show();
   }
    function editer_item(_i){

      $(".span_item"+_i).hide();
	   $("#btn_edit_item"+_i).hide();
      $("#cache_item_ordre"+_i).show();
      $("#cache_item_libelle"+_i).show();
      $("#btn_modif_item"+_i).show();
	  
   }
   
   function modifier_item( IdGrille ,_i)
   {
        
       var ordreItem = $("#cache_item_ordre"+_i).val();

       var libelleItem = $("#cache_item_libelle"+_i).val();

        $.post('ajax_script/update_item.php',
		  {
		    IdGrille:IdGrille,
			ordreItem:ordreItem,
			libelleItem:libelleItem
		  },function (_data){
		          $("#cache_item_ordre"+_i).hide();
                  $("#cache_item_libelle"+_i).hide();
				  $("#btn_modif_item"+_i).hide();
				  
				   $(".span_item"+_i).show();
	               $("#btn_edit_item"+_i).show();
				   
				   $("#span_ordre"+_i).html( ordreItem );
				   $("#span_libelle"+_i).html( libelleItem );
				   
		         if(_data == 1){
				        console.log('ok');
				 }else{
				        alert('Erreur de modification!');
				 }
		      
		    }
		  );
		 
   }
   
   function modifier_categorie( id_cat ){
       /**var ordreCategorie = $("#val_ordre_categorie").val();*/
       var libelleCategorie = $("#val_libelle_categorie").val();
        $.post('ajax_script/update_categorie.php',
		  {
		    id_cat:id_cat,
		    /**ordreCategorie:ordreCategorie,*/
			libelleCategorie:libelleCategorie
		  },function (_data){
		      
				     $(".span_categorie").show();
                     $(".btn_edit").show();
					 $(".cache_categorie").hide();
	                 $(".btn_modif_cat").hide();
					 $("#span_ordre_cat").html( ordreCategorie );
					 $("#span_libelle_cat").html( libelleCategorie );
		         if(_data == 1){
				        console.log('ok');
				 }else{
				        alert('Erreur de modification!');
				 }
		      
		    }
		  );
	 
   }
   
   function admin_grille(id_projet,id_client,id_application,id_type){

   var nom_projet = $("#nom_projet").val();
   var nom_client = $("#nom_client").val();
   var nom_application = $("#nom_application").val();
   nom_projet = nom_projet.split(" ");
   nom_client = nom_client.split(" ");
   nom_application = nom_application.split(" ");
    tb_show("Gérer les grilles du client","wizard_2.php?height=700&width=742&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&nom_projet="+nom_projet+"&nom_client="+nom_client+"&nom_application="+nom_application+"&id_type="+id_type);
   }
   
   function affiche_indicateur( id_item ){
            var val = $("#item_"+id_item).html();
			if( val == '+' ){
			  $("#item_"+id_item).removeClass("showIndicateurPlus").addClass("showChildMoins").html("-").attr("title","cacher");
              $("#dv_"+id_item).css({"margin-bottom":"-14px"});
		      //$("#td_"+id_item).css({"background":"#B2C6CD","font-weight":"bold"});
			
		      $.post('ajax_script/ajax_indicateur.php',
		       {
		         id_item:id_item
		       },function (_data){
			     $("#td_"+id_item).addClass("show_indicateur");
			     $("#td_"+id_item).css({"background":"#B2C6CD"});
		         $("#tr_"+id_item).show();
                 $("#dv_"+id_item).html( _data ).show(); 
		     
		         });
			}else{
			$("#item_"+id_item).removeClass("showChildMoins").addClass("showChildPlus").html('+').attr("title","Indicateurs");	
			$("#tr_"+id_item).hide();
            $("#dv_"+id_item).hide()
			$("#td_"+id_item).removeClass("show_indicateur");
			$("#td_"+id_item).css({"background":"#FFFFFF"});
			}
         
   }
   
       function editer_indicateur(_i,id_item){
            
         $(".span_indicateur"+_i).hide();
	     $("#btn_edit_indicateur"+id_item+"_"+_i).hide();
		 
         $("#cache_indicateur_ordre_"+id_item+"_"+_i).show();
         $("#cache_indicateur_libelle_"+id_item+"_"+_i).show();
         $("#cache_indicateur_point_"+id_item+"_"+_i).show();
          
		  $("#btn_modif_indicateur"+id_item+"_"+_i).show();
       }
	   
	   
	   function modifier_indicateur( IdIndicateur ,_i,id_item)
   {

       var ordreIndicateur = $("#cache_indicateur_ordre_"+id_item+"_"+_i).val();

       var libelleIndicateur = $("#cache_indicateur_libelle_"+id_item+"_"+_i).val();
       var pointIndicateur = $("#cache_indicateur_point_"+id_item+"_"+_i).val();

        $.post('ajax_script/update_indicateur.php',
		  {
		    IdIndicateur:IdIndicateur,
			ordreIndicateur:ordreIndicateur,
			libelleIndicateur:libelleIndicateur,
			pointIndicateur:pointIndicateur
		  },function (_data){
		  
		          $("#cache_indicateur_ordre_"+id_item+"_"+_i).hide();
                  $("#cache_indicateur_libelle_"+id_item+"_"+_i).hide();
				  $("#cache_indicateur_point_"+id_item+"_"+_i).hide();
				  $("#btn_modif_indicateur"+id_item+"_"+_i).hide();
				  
				   $(".span_indicateur"+_i).show();
	               $("#btn_edit_indicateur"+id_item+"_"+_i).show();
				   
				   $("#span_ordre_indicateur"+id_item+"_"+_i).html( ordreIndicateur );
				   $("#span_libelle_indicateur"+id_item+"_"+_i).html( libelleIndicateur );
				   $("#span_point_indicateur"+id_item+"_"+_i).html( pointIndicateur );
				   
		         if(_data == 1){
				        console.log('ok');
				 }else{
				        alert('Erreur de modification!');
				 }
		      
		    }
		  );
	
   }
   
    function affiche_form_update(){
		       $("#tab_form_ajout").hide();
		       $("#tab_form").show();  
		 }
		 
		  function affiche_form_add(){
		    $("#tab_form_ajout").show();
		    $("#tab_form").hide();			
		 }
		 
    function editer_grille(_i){

      $(".span_grille"+_i).hide();
      $(".span_grille"+_i).hide();
	  
	   $("#btn_edit_grille_"+_i).hide();
      $("#cache_grille_ordre_"+_i).show();
      $("#cache_grille_libelle_"+_i).show();
      $("#btn_modif_grille_"+_i).show();
	  
   }
   
   function modifier_categorie_grille( id_categorie , _i){
          var ordreCategorie = $("#cache_grille_ordre_"+_i).val();

          var libelleCategorie = $("#cache_grille_libelle_"+_i).val();

        $.post('ajax_script/update_categorie.php',
		  {
		    id_categorie:id_categorie,
			ordreCategorie:ordreCategorie,
			libelleCategorie:libelleCategorie
		  },function (_data){
		          $("#cache_grille_ordre_"+_i).hide();
                  $("#cache_grille_libelle_"+_i).hide();
				  $("#btn_modif_grille_"+_i).hide();
				  
				   $(".span_grille"+_i).show();
	               $("#btn_edit_grille_"+_i).show();
				   
				   $("#span_ordre_grille_"+_i).html( ordreCategorie );
				   $("#span_libelle_grille_"+_i).html( libelleCategorie );
				   
		         if(_data == 1){
				        console.log('ok');
				 }else{
				        alert('Erreur de modification!');
				 }
		      
		    }
		  );
   }
   
   function affiche_grille( id_categorie_grille,_k ){
            
            var val = $("#categorie_"+id_categorie_grille).html();
		         
			if( val == '+' ){
			  $("#categorie_"+id_categorie_grille).removeClass("showIndicateurPlus").addClass("showChildMoins").html("-").attr("title","cacher");
              $("#dv_"+id_categorie_grille).css({"margin-bottom":"-14px"});
		     
			  
			$("#td_categorie_"+_k).append("<img class='loading' src='./images/wait.gif' width='20px' height='20px' style='float:right' />");
		      $.post('ajax_script/ajax_item2.php',
		       {
		         id_categorie_grille:id_categorie_grille
		       },function (_data){
			  
			    $(".loading").remove();
				 $("#td_categorie_"+_k).css({"background":"#B2C6CD"});
			     $("#td_categorie_"+_k).addClass('set_bold');
			     $("#td_"+id_categorie_grille).addClass("show_indicateur");
			     $("#td_"+id_categorie_grille).css({"background":"#B2C6CD"});
		         $("#tr_"+id_categorie_grille).show();
                 $("#dv_"+id_categorie_grille).html( _data ).show(); 
				 
				 
				   $("#tab_grille2 tbody").sortable({

						cursor: 'move',
						delay: 180,
                   update: function()
						{
							var rowsOrder = $(this).sortable("serialize");
                                  console.log(rowsOrder);
						   $.post("update_ordre_item.php", { action:'change_rows_order', table:'tab_categorie', order:'category_order', rows_order:rowsOrder } );
						}
					
				}).enableSelection();
		     
		         });
			}else{
			$("#categorie_"+id_categorie_grille).removeClass("showChildMoins").addClass("showChildPlus").html('+').attr("title","Indicateurs");	
			$("#tr_"+id_categorie_grille).hide();
            $("#dv_"+id_categorie_grille).hide()
			$("#td_"+id_categorie_grille).removeClass("show_indicateur");
			$("#td_"+id_categorie_grille).css({"background":"#FFFFFF"});
			$("#td_categorie_"+_k).css({"background":"#FFFFFF"});
			$("#td_categorie_"+_k).removeClass('set_bold');
			}       
   }
   
   function editer_grille2(_i,id_grille){
            
         $(".span_grille2"+_i).hide();
	     $("#btn_edit_grille2"+id_grille+"_"+_i).hide();
		 
         $("#cache_grille2_ordre_"+id_grille+"_"+_i).show();
         $("#cache_grille2_libelle_"+id_grille+"_"+_i).show();
     
          
		  $("#btn_modif_grille2"+id_grille+"_"+_i).show();
       }
	   
	   
	 function modifier_grille2( Idgrille2 ,_i,id_categorie)
   {

     //  var ordregrille2 = $("#cache_grille2_ordre_"+id_categorie+"_"+_i).val();

       var libellegrille2 = $("#cache_grille2_libelle_"+id_categorie+"_"+_i).val();
     

        $.post('ajax_script/update_grille2.php',
		  {
		    Idgrille2:Idgrille2,
			/**ordregrille2:ordregrille2,*/
			libellegrille2:libellegrille2,			
		  },function (_data){
		  
		          $("#cache_grille2_ordre_"+id_categorie+"_"+_i).hide();
                  $("#cache_grille2_libelle_"+id_categorie+"_"+_i).hide();
				  $("#cache_grille2_point_"+id_categorie+"_"+_i).hide();
				  $("#btn_modif_grille2"+id_categorie+"_"+_i).hide();
				  
				   $(".span_grille2"+_i).show();
	               $("#btn_edit_grille2"+id_categorie+"_"+_i).show();
				   
				   /**$("#span_ordre_grille2"+id_categorie+"_"+_i).html( ordregrille2 );*/
				   $("#span_libelle_grille2"+id_categorie+"_"+_i).html( libellegrille2 );
				   
				   
		         if(_data == 1){
				        console.log('ok');
				 }else{
				        alert('Erreur de modification!');
				 }
		      
		    }
		  );
	
   }
	  function openGrille( id_type ){
	 
			
		   tb_show("Gérer les grilles de notation","grille_notation_.php");
      }   
		

/**********************Modification de projet*******************************/		
   function modif_projet2(i,id_client,id_application){

            var iIdProjet = $("#id_projet"+i).val();
            var nom_projet =  $("#nom_projet"+i).val();
            var libelle_application =  $("#libelle_application"+i).val();
            var campagne_easycode =  $("#campagne_easycode"+i).val();
                 
	        nom_projet = nom_projet.split(" ");
	        libelle_application = libelle_application.split(" ");
            tb_show("Modification de Campagne","formulaire_campagne.php?height=200&width=479&iIdProjet="+iIdProjet+"&idClient="+id_client+"&idApplication="+id_application+"&nomProjet="+nom_projet+"&libelle_application="+libelle_application+"&campagne_easycode="+campagne_easycode);	     

        }
		
		function update_projet( id_projet ){
		      
		       var nom_projet       = $("#champ_projet").val();
		       var nom_campagne = $("#champ_campagne").val();
		       var id_client     = $("#champ_client").val();
		       var id_application     = $("#champ_application").val();
			
			  $.post('update_projet.php',
			  {
			  id_projet:id_projet,
			  nom_projet:nom_projet,
			  nom_campagne:nom_campagne,
			  id_client:id_client,
			  id_application:id_application
			  },function( _data ){
			          if( _data == 'ok' ){
					       alert("Campagne modifié");
						   window.location.href='interface.php';
					  }else{
					       alert("Echec de modification");
					  }
			  });
			  
		 return false;	 
			
		}
		
		
		function affiche_historique( id_projet,nom_projet,nom_client,nom_application , i ){
		 
     tb_show("Historique","affiche_historique.php?height=400&width=600&id_projet="+id_projet+"&nom_projet="+nom_projet+"&nom_client="+nom_client+"&nom_application="+nom_application);		
	
		}
		
		function supprimer_historique( id_historique, _k ){
		     if(confirm('Voulez vous vraiment supprimer cette ligne ?')){
		     $.post('supprimer_historique.php',
			 {
			 id_historique:id_historique
			 },function( _data ){
			        if( _data==1 ){
					   $("#row_"+_k).fadeOut('faste');					   
					  
					}
				 
			 });
			 
			 
			 }
		}
		
  function get_categorie_ajout(){
   var idTraitement = $("#slct_traitement_ajout").val();
            if(idTraitement==''){
			      $("#div_corps_ajout").hide();
				  return false;
			}
			else{
				 $.post('liste_categorie.php',
				  {
					IdTraitement:idTraitement,
				  },function ( _data ){
					  /****************************/
					  $("#div_corps_ajout").show();
					  /****************************/
					   $("#div_corps_ajout").html(_data);
					   
					}
				  );
            }
  }
		/**************************************************/
		
		function Add( nb_row,max_id1 ){
	       
		    var td_categorie =  $(".td_categorie");
            var nb_categorie = td_categorie.length;
            //$("#last_id").val( (max_id+1) );
			var max_id = $("#last_id").val();
		    $('#btnAddd').attr('disabled',true);
			$("#liste_categorie tbody").append(
			"<tr id='tr_"+(nb_categorie)+"'>"+
			"<td class='td_categorie' id='td_test_"+(nb_categorie+1)+"'><input  id='test' size='119%' type='text'/></td>"	+			
			"<td id='td_submit_"+(nb_categorie+1)+"'><input id='btn_add_categorie'	 type='button' onclick='Save("+nb_categorie+","+max_id+");' value='ajouter' class='btn_save_grille btn_submit' /></td>"+
			"</tr>");
		   /** $("#btnAddd").attr("onClick","Add("+(max_id+1)+")");*/
		   
			/**$(".btn_save_grille").bind("click", Save);     
			$(".btnDelete").bind("click", Delete);*/
			charge_classement((nb_categorie+1));
			
         };

		 function Save( nb_categorie,max_id_cat ){
		 
	 
		  /**	var par = $(this).parent().parent(); //tr
		 
		  var tdName = par.children("td:nth-child(1)");
			var tdNameVal = tdName.children("input[type=text]").val();	
			var tdButtons = par.children("td:nth-child(2)");
		*/
		       $("#last_id").val( (max_id_cat+1) );
			   var td_new_categorie = $("#td_test_"+(nb_categorie+1));	  
		       var ordre = $("#ordre_categorie_grille").val();
			   var libelle_categorie = $("#test").val() ;
			        if( libelle_categorie =='' ){
						$("#test").css({"border": "1px solid red"});
						return false;
					}
			   
			   var id_type_traitement =  $("#id_type_traitement").val() ;
			   var last_id = parseInt($("#last_id").val());
		
			   $.post('insert_categorie.php',
				{
				ordre:ordre,
				libelle_categorie:libelle_categorie,
				id_type_traitement:id_type_traitement
				},function( _response ){
				         $("#td_test_"+(nb_categorie+1)).addClass('class_test');
				         $("#td_submit_"+(nb_categorie+1)).addClass('class_test');
				         $("#btn_add_categorie").replaceWith( "<img title='supprimers' onclick='delete_categorie("+(max_id_cat+1)+","+nb_categorie+",1)' class='img_suppr' id='img_suppr_"+(nb_categorie+1)+"' style='width:20px;height:20px;margin:0 0 0 72px;cursor:pointer' src='images/supprimer.png' />" );
				         td_new_categorie.html( libelle_categorie );
						 $("#div_item").slideDown('medium');
						 $("#lib_categorie").html( libelle_categorie  );
						 $("#input_id_categorie").val( _response );
						 charge_nouveau_item( (max_id_cat+1),'nouveau');
						 
						 
						 
				});
				console.log(123);
		
		     
		 
			/**tdName.html(tdName.children("input[type=text]").val());
		
		
			tdButtons.html("<img src='images/delete.png' class='btnDelete'/><input id='btn_add_categorie'	 type='button' onclick='Save();' value='editer' class='btn_edit_grille' />");
		
			$(".btnEdit").bind("click", Edit);
			$(".btnDelete").bind("click", Delete);
			 */
        };

		function Edit(){
		    var par = $(this).parent().parent(); //tr
		    var tdName = par.children("td:nth-child(1)");
		    
		    var tdButtons = par.children("td:nth-child(2)");
		 
		    tdName.html("<input type='text' id='txtName' value='"+tdName.html()+"'/>");
		    tdButtons.html("<img src='images/disk.png' class='btnSave'/>");
		 
		    $(".btnSave").bind("click", Save);
		    $(".btnEdit").bind("click", Edit);
		    $(".btnDelete").bind("click", Delete);
		};

		function Delete(){
			var par = $(this).parent().parent(); //tr
			par.remove();
        }; 

                function Add_item(  )
				{
				     $("#p_active_input").fadeIn('medium');
				     
					 var libelle_item = $("#imput_item").val();
					 var id_cat = $("#last_id").val();
					            // if( id_cat=='' ){
								  // id_cat = cat_id;
								// }
					     if( libelle_item == '' || libelle_item=='Inserer le critere ici'){
							 $("#imput_item").css({"border": "1px solid red"});
							 return false;
						 }else{
						 
							     $.post('ajax_script/insert_item.php',
								  {
									  id_cat:id_cat,
									  libelle_item:libelle_item
								  },function ( _data ){	
										/**$("#imput_item").attr('disabled',true);*/
										$("#imput_item").css({"border": "none"});
										$("#imput_item").prop('value','');
										  charge_nouveau_item(id_cat,'nouveau');
								  });				
					}
	
				}
				
				
				
				function Active_input( _test )
				{
                   			if( _test==0 ){
							   $("#imput_item").attr('disabled',false);
					           $("#imput_item").attr('value','');
							}else if( _test==1 ){
							     $("#imput_item").attr('value','');
							}
				     
				}
				
						
				
				  function fermer_div_item()
				  {       if(confirm('Voulez vous vraiment fermer ce bloque?'))
				       {
					      /** $(".img_suppr").fadeOut('Fast');*/
				           $("#div_item").fadeOut('Fast');	
                           $('#btnAddd').prop( "disabled", false );	
                              					   
					   }
				  }
				  
				  function delete_categorie( id_categorie , k_, type)
				  {
				      if( confirm("Vouler vous vraiment supprimer?") )
					  {
					  
					         if ( type==1 ){
						       $("#div_item").fadeOut('Fast');
						    }
							 $.post('ajax_script/delete_categorie.php',
										  {
											  id_categorie:id_categorie,
											
										  },function ( _data ){	
												if(_data==1){
													$("#tr_"+k_).fadeOut('faste');	
												}else{
													alert("Echec de la suppression");
												}
							});
						  
						  }
				     
				  }
				  
				 function admin_note(id_projet,id_client,id_application)
				 {				 
				           var nom_projet = $("#nom_projet").val();
						   var nom_client = $("#nom_client").val();
						   var nom_application = $("#nom_application").val();
						  
				 tb_show("Gérer les notes des grilles","description.php?height=700&width=787&id_projet="+id_projet+"&id_client="+id_client+"&id_application="+id_application+"&nom_projet="+encodeURIComponent(nom_projet)+"&nom_client="+encodeURIComponent(nom_client)+"&nom_application="+encodeURIComponent(nom_application));
				 }
				 
				 
				 function charge_nouveau_item( id_categorie,type ){
				 
				            
				          $("#div_item").show();
				          $("#img_loading").show();
						  
						      if( type != 'nouveau' ){
							 
							      $("#lib_categorie").html( type );
								  $("#last_id").val( id_categorie );
							  }
						  $.post("ajax_script/ajax_nouveau_item.php",
						  {
						  id_categorie:id_categorie
						  },function(  _data )
						  {           $("#img_loading").hide();
						              $("#new_item").html( _data );
						  })
				 }
				 
				 function delete_item(id_categorie,id_item,k_){
				  
				  
				  
				       $.post("ajax_script/delete_item.php",
						  {
						  id_categorie:id_categorie,
						  id_item:id_item
						  },function( _data )
						  {
						         if( _data==1 ){
									$("#tr_"+id_categorie+"_"+id_item+"_"+k_).fadeOut('Fast');
								}								 
						  })
				 
				 }
				 
				 function charge_classement( last_td ){
				    $.post("ajax_script/ajax_classement.php",
						  {
						 
						  },function( _data )
						  {
						       								 
						  })
				 }
