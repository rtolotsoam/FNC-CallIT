
<?php
    include("/var/www.cache/dgconn.inc");
    $zFichier = $_REQUEST['zFichier'];
   
    $zMatriculeNotation = $_REQUEST['zmatricule'];
    $zProjet = $_REQUEST['zProjet'];
     
    $matriculeShow = $_REQUEST['matriculeShow'];
	$bPop = $_REQUEST['pop'];
    
    
     if($zProjet == 'showroom_rec$'){
            echo"<input type='hidden' value='$matriculeShow' id='matriculeRetour' />";
     }
       else{
            echo"<input type='hidden' value='$matriculeShow' id='matriculeShow' />";
     }
     
    
	if ($bPop == 1)
	{
		echo '<link rel="stylesheet" type="text/css" href="css/style.css"></link>';
		echo '<script type="text/javascript" src="js/jquery-1.8.3.js"></script>';
		echo '<script type="text/javascript" src="js/jquery-ui.js"></script>';
               echo '<script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>';
		echo '<script type="text/javascript" src="js/script.js"></script>';
	}

    echo "
		<center><h2>Edition de Fichier</h2>
		<input type='hidden' id='hpop' name='hpop' value='$bPop' />
	";
    
    $editefile = pg_query($conn,"SELECT DISTINCT fi.chemin_fichier,typ.id_type_traitement,
    typ.libelle_type_traitement,cg.libelle_categorie_grille,no.matricule,no.date_entretien,no.duree_entretien,no.date_restitution,no.debut_entretien,no.commentaire_general,no.objectif,fi.nom_fichier,fi.id_fichier,fi.chemin_fichier,g.libelle_grille,g.id_grille,ind.id_indicateur,ind.libelle_indicateur,ind.point,ino.id_indicateur_notation,ino.commentaire,p.nom_projet
    FROM cc_sr_type_traitement typ LEFT JOIN cc_sr_categorie_grille cg ON typ.id_type_traitement = cg.id_type_traitement
    LEFT JOIN  cc_sr_grille g ON cg.id_categorie_grille = g.id_categorie_grille 
    LEFT JOIN  cc_sr_indicateur ind   ON g.id_grille = ind.id_grille
    LEFT JOIN  cc_sr_indicateur_notation ino  ON ino.id_grille= g.id_grille
    LEFT JOIN  cc_sr_notation no ON no.id_notation = ino.id_notation
    LEFT JOIN  cc_sr_fichier fi ON no.id_fichier = fi.id_fichier 
    LEFT JOIN  cc_sr_projet p ON p.id_projet = no.id_projet
    WHERE fi.id_fichier ='$zFichier' and no.matricule_notation = $zMatriculeNotation  ");
   
  $rws = pg_fetch_array($editefile); 
  $chemin = $rws['chemin_fichier'];
  $zProjet = substr($chemin,10,15);
  $nomProjet = $rws['nom_projet'];
  $iIdindicateur = $rws['id_indicateur'];
  $iTraitement = $rws['id_type_traitement'];
  $zTraitement = $rws['libelle_type_traitement'];
  $iMatricule =  $rws['matricule'];
  $dDate_entretien =  $rws['date_entretien'];
  $iDuree =  $rws['duree_entretien'];
  $iHeure_entretien = $rws['debut_entretien'];
  $dDate_restitution =  $rws['date_restitution'];
  $zComment_gen = $rws['commentaire_general'];
  $zObjectif = $rws['objectif'];
  echo "<input type='hidden' value='$nomProjet' id='projetupdate'/>";
  
     $zHtmlResult1 = "";
     $zHtmlResult1 .= "<table>";
     $zHtmlResult1 .= "<tr><td>Projet:</td>";  
     $zHtmlResult1 .="<td><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px' id='projet' name='projet' value='$nomProjet'/></td></tr>";
     $zHtmlResult1 .= "<tr><td>Type de traitement</td>";  
     $zHtmlResult1 .="<td><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px' id='type_traitement' name='type_traitement' value='$zTraitement'/></td></tr>";
     $zHtmlResult1 .= "<tr><td>Matricule</td>";  
     $zHtmlResult1 .="<td><input type='hidden' id='matricule' value='$zMatriculeNotation' /><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px' id='matricule_' name='type_traitement' value='$iMatricule'/></td></tr>";
     $zHtmlResult1 .= "<tr>
          <td>Date de l'entretien</td> 
          <td><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px' value='$dDate_entretien' id='date_entretien' name='date_entretien' /></td>        
          </tr>";
     $zHtmlResult1 .= "<tr>
     <td>Heure de l'entretien</td> 
     <td><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px'  value='$iHeure_entretien' id='duree_entretien' name='duree_entretien' /></td>        
          </tr>";
     $zHtmlResult1 .= "<tr>
     <td>Date de restitution</td> 
     <td><input  class='input_edit' type='text' size='26px'   value='$dDate_restitution' id='date_restitution' name='date_restitution' /></td>        
     </tr>";
     $zFichier = $rws['nom_fichier'];
     $zHtmlResult1 .= "<tr>
     <td>Fichiers:</td> 
     <td><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px'  class='fichier' id='fichier' name='fichier' value='$zFichier'  /></td> 
     </tr>";
    $zHtmlResult1 .= "</table>";
     echo "<div class ='block_notation'>";
     echo $zHtmlResult1;
     echo "<p id='btn_haut' style='margin:12px 0 0 -586px;' ><input type='button' onClick='update();' id='btn_update' name='btn_update' value='Mettre &agrave; jour'/></p>";
     echo "<br />";
     $dir_audio = $chemin.'/'.$zFichier;

     if(file_exists($dir_audio)){
                 echo "<p><audio src='$dir_audio'  controls /></p>";
    }
     echo "</div>";
     echo "<br /><br /><br /><br />";
     //$zFichier = $rws['nom_fichier'];
        $sql = "SELECT DISTINCT cg.libelle_categorie_grille,g.id_grille,g.libelle_grille,cg.ordre,g.ordre,indnot.id_indicateur_notation,indnot.commentaire,indnot.note,ga.flag_notation FROM
    cc_sr_type_traitement as t 
    INNER JOIN cc_sr_categorie_grille as cg ON t.id_type_traitement = cg.id_type_traitement 
    INNER JOIN cc_sr_grille as g ON cg.id_categorie_grille = g.id_categorie_grille 
	INNER JOIN cc_sr_grille_application ga ON g.id_grille=ga.id_grille
    INNER JOIN cc_sr_indicateur_notation indnot ON indnot.id_grille = g.id_grille
    INNER JOIN cc_sr_notation note ON note.id_notation = indnot.id_notation
    INNER JOIN  cc_sr_fichier fi ON fi.id_fichier = note.id_fichier
    where t.id_type_traitement= $iTraitement  
	AND ga.flag_notation::character varying <>''
	AND fi.nom_fichier='$zFichier' 
	AND note.matricule_notation = $zMatriculeNotation 
	ORDER BY cg.ordre,g.ordre";
   $query = pg_query($conn,$sql);
     echo "<center>";
    $nb =pg_num_rows($query);
    $zresultHtml .="<form id='form_update'>";
    $zresultHtml .= "<input type='hidden' name='compteur' id='compteur' value='$nb' />";

    $zresultHtml .= "<table class='tablesorter'  width='800px'>";
    $zresultHtml .= "<thead><tr class='tab_rows'>";
    $zresultHtml .= "<th>Categorie</th>";
    $zresultHtml .= "<th>Items</th>";
    $zresultHtml .= "<th>Indicateur</th>";
    $zresultHtml .= "<th>Commentaire</th>";
    $zresultHtml .= "</tr></thead>";
    $i=0;
     $categprecedent ='';
     $categprecedent2 ='';
	  $note_total = 0;
    while ($rows = pg_fetch_array($query)) {
                if($i%2 == 0){
                      $color = '#E6EEEE';
                   }else{
                      $color = '#fff';
                 }
                 $note = $rows['note']; 

                     $id_grille = $rows['id_grille'];   
         $note_total = $note_total+$note;
         $query2 = pg_query($conn,"SELECT DISTINCT id_indicateur,libelle_indicateur,point from cc_sr_indicateur WHERE id_grille= '$id_grille' ");
       $query3 = pg_query($conn,"SELECT max(point) AS max FROM cc_sr_indicateur WHERE id_grille = '$id_grille' ");

    $id_indnot = $rows['id_indicateur_notation'];
    $id_indic = $rows['id_indicateur'];
    
                 /**********************/
                $categ='';// 
                 if($rows['libelle_categorie_grille'] != $categprecedent){
                     $categ=$rows['libelle_categorie_grille'] ;
                     $class_tr ="new_categ";
                 }
                 else
                 {
                      $categ ='';
                      $class_tr = "old_categ";
                 }
                 $categprecedent = $rows['libelle_categorie_grille'];//
               /**********************/
               
               
               /**********************/
    
    $zresultHtml .= "<tr style='background-color:$color;'>";
    $zresultHtml .= "<td valign='middle' style='font-weight:bold;background:#ccc;text-transform: uppercase;'>$categ </td>";
    $zresultHtml .= "<td style='font-weight:bold;' ><input type='hidden' id='id_indicateur$i' value='$id_indic'/><input type='hidden' id='id_indicateur_not$i' value='$id_indnot'/>{$rows['libelle_grille']}</td>";
    /******************/
         $ayant_libelle = array();
		 $libelles = array();
		 $note_max = pg_fetch_result($query3,0,0);
         $selected='selected';
    /******************/
    $zresultHtml .= "<td>";
      
      $zresultHtml .=  "<select onChange='do_Total($i);' name='note$i' id='note$i' class='select_note' >";	 
		      while( $rows2 = pg_fetch_array( $query2 )){
			      $point = $rows2['point'];
		 	      $ayant_libelle[] = $rows2['point'];
		 	      $libelles[$rows2['point']] = $rows2['libelle_indicateur'];
				  if( $rows['flag_notation']==1 ){
						   
					   if( $point==$note ){
					        $zresultHtml .= "<option  $selected value =$point>$point</option>";
					   }else{
					      $zresultHtml .= "<option value =$point>$point</option>";
					   }
				     
			     }else{
				          if( $point!=0.5  ){
						  
						     if( $point==$note ){
					           $zresultHtml .= "<option  $selected value =$point>$point</option>";
					         }else{
					           $zresultHtml .= "<option value =$point>$point</option>";
					         }
						          
						  }
				 
				 }
					  /** if( $point==$note ){
					        $zresultHtml .= "<option  $selected value =$point>$point</option>";
					   }else{
					      $zresultHtml .= "<option value =$point>$point</option>";
					   }*/
				     
				  
		       }
		/** for ($nm = 0 ; $nm <= $note_max; $nm++) {
		 $labelle = '';
		 	if (in_array($nm, $ayant_libelle)) {
				$labelle = " - " . $libelles[$nm];
			}
            $selected = '';
			
            if($nm == $note){
			$note_total = $note_total+$note;
                $selected = 'selected = "selected"';
              if($note==1) { 
			   
                $zresultHtml1 .= "<p style='padding:20px 0 0 26px'><input checked type='checkbox'  onClick='do_Total($i);' value='1'  name='note' id='note$i' /></p>";
              }
               else 
	          { $zresultHtml1 .= "<p style='padding:20px 0 0 26px'><input  type='checkbox' onClick='do_Total($i);'  value='1'  name='note' id='note$i' /></p>";
              }
            }
		 $zresultHtml1 .= "<option value = $nm $selected>$nm$labelle</option>";
	     
	}*/
  
      $zresultHtml .=  "</select>";    //
       $zresultHtml .= "</td>";
      
         $com = utf8_decode($rows['commentaire']);
    $zresultHtml .= "<td><textarea cols='25' rows='2' class='accueil_comment' id='accueil_comment$i' name='accueil_comment$i'>{$com}</textarea>";
    $zresultHtml .="</td>";
    $zresultHtml .= "</tr>";
    $i++;
 } 
      $zresultHtml .= "<tr>     
        <td colspan='4' style='background-color:#E6EEEE;'  id='display_total'><strong>Total:&nbsp;&nbsp;<span>$note_total</span></strong></td>
       </tr>"; 

	   if ( $bPop != 1 )
	   {
		$zresultHtml .="<input id='btn_retour' onClick='retour();' type='button' value='retour a la selection'/>";
	   }
	   $zresultHtml .="
	   </td>
       </tr>";
    
      $zresultHtml .= "</form>";
      $zresultHtml .= "</table>";
      $zresultHtml .= 
      "<table  cellspacing='10px'>
      <td valign='top'><strong>Commentaire g&eacute;n&eacute;rale :</strong></td>
      <td><textarea id='comment_general' rows='10' cols='25' >$zComment_gen</textarea></td>
      <td valign='top'><strong>Objectif :</strong></td>
      <td><textarea id='objectif'rows='10' cols='25' >$zObjectif</textarea></td>
      </tr>
      <tr>
      <td><input type='button' onClick='update();' id='btn_update' name='btn_update' value='Mettre &agrave; jour'/></td>
      <td>&nbsp;</td>
      </tr>
      </table>
      ";
        echo ($zresultHtml) ;
      echo "</center>";
?>
</body>
</html>