<?php
    include("/var/www.cache/dgconn.inc");
    $zProjet = $_REQUEST['zProjet'];
   
   $query = pg_query($conn,"SELECT DISTINCT cg.libelle_categorie_grille, g.id_grille,g.libelle_grille,g.ordre FROM
    cc_sr_type_traitement as t INNER JOIN cc_sr_categorie_grille as cg ON t.id_type_traitement = cg.id_type_traitement INNER JOIN cc_sr_grille as g ON 
    cg.id_categorie_grille = g.id_categorie_grille INNER JOIN  cc_sr_indicateur  ON g.id_grille = cc_sr_indicateur.id_grille where t.id_type_traitement=3 ORDER BY g.ordre");
     echo "<center>";
     echo "<h1>Grille pour L'Email</h1>";
    $nb =pg_num_rows($query);
    $zresultHtml = "<form class ='grille_entrant'  >";
    echo "<input type='hidden' value='$zProjet' id='projet' /> ";
    $zresultHtml .= "<input type='hidden' name='compteur' id='compteur' value='$nb' />";
    $zresultHtml .= "<table class='tablesorter' width='700px'>";
    $zresultHtml .= "<thead><tr class='tab_rows'>";
    $zresultHtml .= "<th>Categorie</th>";
    $zresultHtml .= "<th>Items</th>";
    $zresultHtml .= "<th>Indicateur</th>";
    $zresultHtml .= "<th>Commentaire</th>";
    $zresultHtml .= "</tr></thead>";
    $i=0;
      $categprecedent ='';
    while ($rows = pg_fetch_array($query)) {
                if($i%2 == 0){
                      $color = '#58D3F7';
                }else{
                      $color = '#fff';
                }
    $id_grille = $rows['id_grille'];
    $query2 = pg_query($conn,"SELECT id_indicateur,libelle_indicateur,point,ordre from cc_sr_indicateur WHERE id_grille= '$id_grille' ORDER BY ordre");
	$query3 = pg_query($conn,"SELECT max(point) AS max FROM cc_sr_indicateur WHERE id_grille = '$id_grille' ");
   /**********************/
    $categ='';
     if($rows['libelle_categorie_grille'] != $categprecedent){
         $categ=$rows['libelle_categorie_grille'] ;
         $class_tr ="new_categ";
     }
     else
     {
          $categ ='';
          $class_tr = "old_categ";
     }
     $categprecedent = $rows['libelle_categorie_grille'];
   /**********************/
    $zresultHtml .= "<tr class='$class_tr' style='background-color:$color;'>";
    $zresultHtml .= "<td valign='middle' style='font-weight:bold;background:#ccc;text-transform: uppercase;'>$categ </td>";
    $zresultHtml .= "<input type='hidden' id='grille$i' value='{$rows['id_grille']}' />";
    $zresultHtml .= "<td valign='middle' style='font-weight:bold;' >{$rows['libelle_grille']}</td>";
    $zresultHtml .= "<td>";
         //$zresultHtml .= "<table width='300px'  style='border:none;' class='tablesorter' border='0'>";


         $ayant_libelle = array();
		 $libelles = array();
		 $note_max = pg_fetch_result($query3,0,0);
         while( $rows2 = pg_fetch_array($query2)){
		 	$ayant_libelle[] = $rows2['point'];
		 	$libelles[$rows2['point']] = $rows2['libelle_indicateur'];
		 }
	  $zresultHtml .=  "<select name='note$i' id='note$i' onChange='do_Total($i);' class='select_note' >";	 
		 for ($nm = 0 ; $nm <= $note_max; $nm++) {
		 $labelle = '';
		 	if (in_array($nm, $ayant_libelle)) {
				$labelle = " - " . $libelles[$nm];
			}
		 $zresultHtml .= "<option value = $nm >$nm</option>";
			
		 }
        //$zresultHtml .= "<tr>";     
      $zresultHtml .=  "</select>";        
         //$zresultHtml .= "</table>"; 
    $zresultHtml .= "<td><textarea cols='25' class ='accueil_comment' rows='2' id='accueil_comment$i' name='accueil_comment$i' required='required'></textarea>";
    $zresultHtml .="</td>";
    $zresultHtml .= "</tr>";
    $i++;
 }
       $zresultHtml .= "<tr>     
        <td colspan='4' style='background-color:#E6EEEE;'  id='display_total'><strong>Total:&nbsp;&nbsp;<span></span></strong></td>
       </tr>"; 
      $zresultHtml .= "</form>";
      $zresultHtml .= "</table>";
      $zresultHtml .= 
      "<table  cellspacing='10px'>
      <td valign='top'><strong>Commentaire g&eacute;n&eacute;ral :</strong></td>
      <td><textarea id='comment_general' rows='10' cols='25' ></textarea></td>
      <td valign='top'><strong>Objectif :</strong></td>
      <td><textarea id='objectif'rows='10' cols='25' ></textarea></td>
      </tr>
      <tr>
      <td colspan='2' align='right'><input type='button'  id='btn_valider' name='btn_valider' value='Valider' onclick='do_insert()'/></td>
      <td colspan='2'><input id='btn_retour' class='retour_notation' onClick='retour();' type='button' value='Retour &agrave la s&eacute;lection'/></td>
      </tr>
      </table>
      ";
       echo $zresultHtml;
      echo "</center>";
?>
