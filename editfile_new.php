
<?php
    include("/var/www.cache/dgconn.inc");
    $zFichier = $_REQUEST['zFichier'];

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
		echo '<script type="text/javascript" src="js/script.js"></script>';
	}

    echo "
		<center><h2>Edition de Fichier</h2>
		<input type='hidden' id='hpop' name='hpop' value='$bPop' />
	";
    
    $editefile = pg_query($conn,"SELECT DISTINCT fi.chemin_fichier,typ.id_type_traitement,
    typ.libelle_type_traitement,cg.libelle_categorie_grille,no.matricule,no.date_entretien,no.duree_entretien,no.date_restitution,fi.nom_fichier,fi.id_fichier,fi.chemin_fichier,g.libelle_grille,g.id_grille,ind.id_indicateur,ind.libelle_indicateur,ind.point,ino.id_indicateur_notation,ino.commentaire
    FROM cc_sr_type_traitement typ LEFT JOIN cc_sr_categorie_grille cg ON typ.id_type_traitement = cg.id_type_traitement
    LEFT JOIN  cc_sr_grille g ON cg.id_categorie_grille = g.id_categorie_grille 
    LEFT JOIN  cc_sr_indicateur ind   ON g.id_grille = ind.id_grille
    LEFT JOIN  cc_sr_indicateur_notation ino  ON ino.id_grille= g.id_grille
    LEFT JOIN  cc_sr_notation no ON no.id_notation = ino.id_notation
    LEFT JOIN  cc_sr_fichier fi ON no.id_fichier = fi.id_fichier WHERE fi.nom_fichier ='$zFichier'  ");
   
  $rws = pg_fetch_array($editefile); 
  $chemin = $rws['chemin_fichier'];
  $zProjet = substr($chemin,10,15);
  //$query3 = pg_query($conn,"SELECT max(point) AS max FROM cc_sr_indicateur WHERE id_grille = '$id_grille' ");
  $iIdindicateur = $rws['id_indicateur'];
  $iTraitement = $rws['id_type_traitement'];
  $zTraitement = $rws['libelle_type_traitement'];
  $iMatricule =  $rws['matricule'];
  $dDate_entretien =  $rws['date_entretien'];
  $iDuree =  $rws['duree_entretien'];
  $dDate_restitution =  $rws['date_restitution'];
  echo "<input type='hidden' value='$zProjet' id='projetupdate'/>";
     $zHtmlResult1 = "";
     $zHtmlResult1 .= "<table>";
     $zHtmlResult1 .= "<tr><td>Projet:</td>";  
     $zHtmlResult1 .="<td><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px' id='projet' name='projet' value='$zProjet'/></td></tr>";
     $zHtmlResult1 .= "<tr><td>Type de traitement</td>";  
     $zHtmlResult1 .="<td><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px' id='type_traitement' name='type_traitement' value='$zTraitement'/></td></tr>";
     $zHtmlResult1 .= "<tr><td>Matricule</td>";  
     $zHtmlResult1 .="<td><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px' id='matricule' name='type_traitement' value='$iMatricule'/></td></tr>";
     $zHtmlResult1 .= "<tr>
          <td>Date de l'entretien</td> 
          <td><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px' value='$dDate_entretien' id='date_entretien' name='date_entretien' /></td>        
          </tr>";
     $zHtmlResult1 .= "<tr>
     <td>Dur&eacute;e de l'entretien</td> 
     <td><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px'  value='$iDuree' id='duree_entretien' name='duree_entretien' /></td>        
          </tr>";
     $zHtmlResult1 .= "<tr>
     <td>Date de restitution</td> 
     <td><input class='input_edit' type='text' size='26px'  readonly='readonly' onfocus='this.blur();' class='calendrier' value='$dDate_restitution' id='date_restitution' name='date_restitution' /></td>        
     </tr>";
     $zHtmlResult1 .= "<tr>
     <td>Fichiers:</td> 
     <td><input class='input_edit' type='text' readonly='readonly' onfocus='this.blur();' size='26px'  class='fichier' id='fichier' name='fichier' value='$zFichier'  /></td> 
     </tr>";
    $zHtmlResult1 .= "</table>";
     echo "<div class ='block_notation'>";
     echo $zHtmlResult1;
     echo "<br />";
     echo "<audio src='$chemin$zFichier'  controls />";
     echo "</div>";
     echo "<br />";
     $zFichier = $rws['nom_fichier'];
   $query = pg_query($conn,"SELECT DISTINCT cg.libelle_categorie_grille,g.id_grille,g.libelle_grille,g.ordre,cc_sr_indicateur.id_indicateur,indnot.id_indicateur_notation,indnot.commentaire,indnot.note FROM
    cc_sr_type_traitement as t 
    INNER JOIN cc_sr_categorie_grille as cg ON t.id_type_traitement = cg.id_type_traitement 
    INNER JOIN cc_sr_grille as g ON cg.id_categorie_grille = g.id_categorie_grille 
    INNER JOIN  cc_sr_indicateur  ON g.id_grille = cc_sr_indicateur.id_grille 
    INNER JOIN cc_sr_indicateur_notation indnot ON indnot.id_grille = g.id_grille
    INNER JOIN cc_sr_notation note ON note.id_notation = indnot.id_notation
    INNER JOIN  cc_sr_fichier fi ON fi.id_fichier = note.id_fichier
    where t.id_type_traitement= $iTraitement  AND fi.nom_fichier='$zFichier' ORDER BY g.ordre");
     echo "<center>";
    $nb =pg_num_rows($query);
    $zresultHtml = "<input type='hidden' name='compteur' id='compteur' value='$nb' />";

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
    while ($rows = pg_fetch_array($query)) {
                if($i%2 == 0){
                      $color = '#E6EEEE';
                   }else{
                      $color = '#fff';
                 }
                 $note = $rows['note']; 

                     $id_grille = $rows['id_grille'];   

         $query2 = pg_query($conn,"SELECT DISTINCT id_indicateur,libelle_indicateur,point from cc_sr_indicateur WHERE id_grille= '$id_grille' ");
       $query3 = pg_query($conn,"SELECT max(point) AS max FROM cc_sr_indicateur WHERE id_grille = '$id_grille' ");

    $id_indnot = $rows['id_indicateur_notation'];
    $id_indic = $rows['id_indicateur'];
    
                 /**********************/
                $categ='';// 
                $grille='';
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
               if($rows['libelle_grille'] != $categprecedent2){///
                     $grille = $rows['libelle_grille'] ;

                 }
                 else
                 {
                      $grille ='';

                 }
                 $categprecedent2 = $rows['libelle_grille'];//
               
               /**********************/
    
    $zresultHtml .= "<tr style='background-color:$color;'>";
    $zresultHtml .= "<td valign='middle' style='font-weight:bold;background:#ccc;text-transform: uppercase;'>$categ </td>";
    $zresultHtml .= "<td style='font-weight:bold;' ><input type='hidden' id='id_indicateur$i' value=' $id_indic'/><input type='hidden' id='id_indicateur_not$i' value=' $id_indnot'/>$grille</td>";
    /******************/
        $ayant_libelle = array();
		 $libelles = array();
		 $note_max = pg_fetch_result($query3,0,0);
         while( $rows2 = pg_fetch_array($query2)){
		 	$ayant_libelle[] = $rows2['point'];
		 	$libelles[$rows2['point']] = $rows2['libelle_indicateur'];
		 }
    /******************/
    $zresultHtml .= "<td>";
      
      $zresultHtml .=  "<select name='note$i' id='note$i' class='select_note' >";	 //
		 for ($nm = 0 ; $nm <= $note_max; $nm++) {
		 $labelle = '';
		 	if (in_array($nm, $ayant_libelle)) {
				$labelle = " - " . $libelles[$nm];
			}
            $selected = '';
            if($nm == $note){
                $selected = 'selected = "selected"';
            }
		 $zresultHtml .= "<option value = $nm $selected>$nm$labelle</option>";
			
		 }
  
      $zresultHtml .=  "</select>";    //
       $zresultHtml .= "</td>";
      
         $com = utf8_decode($rows['commentaire']);
    $zresultHtml .= "<td><textarea cols='25' rows='10' id='accueil_comment$i' name='accueil_comment$i'>{$com}</textarea>";
    $zresultHtml .="</td>";
    $zresultHtml .= "</tr>";
    $i++;
 }
       $zresultHtml .= "<tr>
       <td><input type='button' onClick='update();' id='btn_valider' name='btn_update' value='mettre &agrave; jour'/></td>
       <td>";
	   
	   if ( $bPop != 1 )
	   {
		$zresultHtml .="<input id='btn_retour' onClick='retour();' type='button' value='retour a la selection'/>";
	   }
	   $zresultHtml .="
	   </td>
       </tr>";
      echo ($zresultHtml) ;
      $zresultHtml .= "</form>";
      $zresultHtml .= "</table>";
      echo "</center>";
?>
</body>
</html>