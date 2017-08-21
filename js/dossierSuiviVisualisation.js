// JavaScript Document

function afficheDossierSuiviVisualisation ()
		{
			var zMessage = "" ;
			$('#txtDateDeb').css ({backgroundColor:'#FFFFFF'}) ;
			$('#txtDateFin').css ({backgroundColor:'#FFFFFF'}) ;
			// Test si les champs sont remplis convenablement
			if (($('#txtDateDeb').val() == "") && ($('#txtDateFin').val() != "")) 
			{
				zMessage += "Veuillez renseigner la Date de d\351but \n" ;
				$('#txtDateDeb').css ({backgroundColor:'#FFFFCC'}) ;
			}
			if (($('#txtDateFin').val() == "") && ($('#txtDateDeb').val() != "")) 
			{
				zMessage += "Veuillez renseigner la Date de d\351but \n" ;
				$('#txtDateFin').css ({backgroundColor:'#FFFFCC'}) ;
			}
			if (zMessage != "")
			{
				alert (zMessage) ;
			}	
			else
			{
				document.frmAfficheDossierVisualialisation.action = "dossierSuiviVisualisation.php?&iAffiche=1" ;
				document.frmAfficheDossierVisualialisation.submit () ;
			}
		}
		
		// Export vers Excel de la table
		function exportDossierVisualisation ()
		{
			var zNom = navigator.appName ;
 
			if (zNom != 'Microsoft Internet Explorer') 
			{
			 alert ('Export possible sur Internet Explorer seulement') ;
		 
			 return false ;
			}  
		 
			var oExcel ; // Application Excel
			var oExcelSheet ; // Feuille de calcul
			var oWkBooks ;
			var cols ; // Nombre de colonnes du tableau
			var j=0 ;
		 
			oExcel = new ActiveXObject ('Excel.Application') ;
			oWkBooks = oExcel.Workbooks.Add ;
			oExcelSheet = oWkBooks.Worksheets (1) ;
		 
			oExcelSheet.Activate () ;
		 
			if  (tabDossierVisual.tagName != 'TABLE') 
			{
			 alert ('L\'export vers Excel ne fonctionne qu\'avec un tableau.') ;
			 return false ;
			}
			//alert () ;
			cols = Math.ceil (tabDossierVisual.cells.length / tabDossierVisual.rows.length) - 0 ;
		 
			for  (var i = 0 ; i < tabDossierVisual.cells.length ; i ++)
			{
			 //if ((i+1)%cols != 0)
			 //{
			  var c, r ;
			  j++ ;
			  if (j>cols)
			  {
			   // alert (j+' '+cols) ;
			   i+= 0 ;
			   j = 0 ;
			  }
		 
			  r = Math.ceil ( (i+1) / cols) ; //lignes en cours
			  c=  (i+1)- ( (r-1)*cols) //colonnes en cours
		 
			  //En tête de colonnes
			  if  (tabDossierVisual.cells (i).tagName == 'TH') 
			  {
			   oExcel.ActiveSheet.Cells (r,c).Font.Bold = true ;
			   // oExcel.ActiveSheet.Cells (r,c).Font.Color = 16777215 ;
			   oExcel.ActiveSheet.Cells (r,c).Interior.Color = 14474460 ; //gris
			   //oExcel.ActiveSheet.Cells (r,c).Interior.Color = &CCA52D ;
			  }
			  /*
			  else
			  {
			   if (r%2 != 0)
			   {
				oExcel.ActiveSheet.Cells (r,c).Interior.Color = 11790326 ;
			   }
			   else
			   {
				oExcel.ActiveSheet.Cells (r,c).Interior.Color = "Red" ;
			   }
			  }
			  */
			  
		 
			  // Texte en gras
			  if  (tabDossierVisual.cells (i).childNodes.length > 0 && tabDossierVisual.cells (i).childNodes (0).tagName == "B")
			   oExcel.ActiveSheet.Cells (r,c).Font.Bold = true ;
		 
			  // Rempli le contenu
			  
			  oExcel.ActiveSheet.Cells (r,c).Value = tabDossierVisual.cells (i).innerText ;
			 //}  
			}
		 
			oExcelSheet.Application.Visible = true ;
				
		}
						
		/*function modifDossier (_id)
		{
			document.location = "http://192.168.10.5";
			//document.frmAfficheDossierSuiviInsert.action = "dossierSuiviInsert.php?&iMod=1&idFTps=" + _id ;
			//document.frmAfficheDossierSuiviInsert.submit () ;
		}*/
		
		function delDossier (_id)
		{
			if (confirm ("Etes-vous sûre de vouloir supprimer les informations ?"))
			{
				
				document.frmAfficheDossierVisualialisation.action = "dossierSuiviVisualisation.php?&iSuppr=1&idFTps=" + _id ;	
				document.frmAfficheDossierVisualialisation.submit () ;	
			}
		}