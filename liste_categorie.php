
<?php
    include("/var/www.cache/dgconn.inc");
	
	
    $IdTraitement = $_REQUEST['IdTraitement'];
	     
		 function test_utilisation_categorie( $id_categorie ){
		       global $conn;
			   $sql = "select  distinct ga.id_grille_application from cc_sr_grille g

			inner join  cc_sr_grille_application ga ON g.id_grille=ga.id_grille
			inner join cc_sr_indicateur_notation i on i.id_grille_application = ga.id_grille_application
			where g.id_categorie_grille={$id_categorie}";
              $query = pg_query($conn,$sql) or die( pg_last_error()); 
			  $nb_row = pg_num_rows( $query  );
		     return  $nb_row;
		 }
	
	$sql_max = "SELECT MAX(id_categorie_grille) FROM cc_sr_categorie_grille";
	$query_max = pg_query($conn,$sql_max) or die (pg_last_error());
	$max_id = pg_fetch_row( $query_max );
	    
    $zSql = "select id_categorie_grille,libelle_categorie_grille,ordre from cc_sr_categorie_grille
    where id_type_traitement= $IdTraitement  order by ordre";
     $query = pg_query($conn,$zSql) or die (pg_last_error());	 
     $nbRow = pg_num_rows( $query );
	 
		$zHtml .="<input type='hidden' id='last_id' value='{$max_id[0]}' />
		<input type='hidden' id='test_id' value='' /> 
		<input type='hidden' id='current_id' value='' /> 
		<table onload='return tester();' class='tablesorter'  border=0 id='liste_categorie'>
	      <thead>
	        <tr>
		      <!--<th width='10%'>Ordre</th>-->
		      <th width='60%'>Libelle</th>
		      <th width='15%'>
			  <input type='hidden' value='{$IdTraitement}' id='id_type_traitement'/>
			  <input type='hidden' value='{$nbRow}' id='ordre_categorie_grille'/>
			  <input type='button' onclick='Add({$nbRow},{$max_id[0]});'  id='btnAddd' value='ajouter'  class='btn_add_grille btn_submit' /></th>
	       </tr>
	     </thead>
		 <tbody>
	   ";
	   
	    for($k=0;$k<$nbRow;$k++){			
		   $row = pg_fetch_array($query,$k);
		   $libelle_cat = str_replace(" ","_",$row['libelle_categorie_grille']);

		   $libelleCategorieVal = str_replace("'"," ",$row['libelle_categorie_grille']);
	       $showCategorie = "<a onclick='affiche_grille(".$row['id_categorie_grille'].",$k );' href='javascript:void(0)' title='Afficher les grilles' style='text-decoration:none;font-weight:bold;font-size:20px' id='categorie_".$row['id_categorie_grille']."' class='showIndicateurPlus'>+</a> ";
		   $zHtml .="<tr id='tr_{$k}'>
				
				   <input id='champ_ordre_{$k}' type='hidden' size='13%' value='{$row['ordre']}' />
				   <input id='champ_idGrille_{$k}' type='hidden' size='13%' value='{$row['id_categorie_grille']}' />
				   <td class='td_categorie td_categorie_{$k}' id='td_categorie_{$k}' width='60%'>

				   <span id='span_libelle_grille_{$k}' class='span_grille$k'>{$row['libelle_categorie_grille']}</span>
				   <input id='cache_grille_libelle_{$k}' type='text'  size='119%' class='cache_grille' value='$libelleCategorieVal' />
				   </td>				  
                   <td  class='td_categorie_{$k}' align='center' width='15%'>
				  <!-- <input id='btn_edit_grille_{$k}'	 type='button' onclick='editer_grille($k);' value='editer' class='btn_edit_grille' />-->
			       <input id='btn_modif_grille_{$k}' type='button' value='modifier' onclick='modifier_categorie_grille({$row['id_categorie_grille']},$k)' class='btn_modif_grille' />";
				   $test_use_categorie = test_utilisation_categorie( $row['id_categorie_grille'] );
				       if( $test_use_categorie == 0 ){
					    $zHtml .= "<a href='#' onclick='delete_categorie({$row['id_categorie_grille']},{$k},0);'><img title='Supprimer' src='images/delete.png' style='margin-left:60px;width:20px;height:20px;cursor:pointer' /></a>";
					   }else{
					     $zHtml .= "<a href='#'><img title='Cat&eacute;gorie utilis&eacute;' src='images/interdit.png' style='margin-left:60px;width:20px;height:20px;cursor:pointer' /></a>";
					   }
				  
				   
				 $zHtml .= '<a href="#"><img title="Afficher les crit&egrave;res" src="images/afficher.png" style="margin-left:5px;width:20px;height:20px;cursor:pointer"  onclick="charge_nouveau_item('.$row['id_categorie_grille'].',\''.utf8_decode($row['libelle_categorie_grille']).'\','.$k.')"/></a>
				   </td>				  
			      </tr>';
			
			}
	      $zHtml .= "</tbody></table>";
		  $zHtml .= "<div   style='display:none;background:#cde1e8;border:1px solid #B2C6CD' id='div_item'>
		  <img src='images/fermer2.png' onclick='fermer_div_item();'  title='fermer' style='cursor:pointer;float:right;height:23px;width:23px;'/>
		  ";
		  
		  $zHtml .= "<h2><span   id='lib_categorie'></span><br /><img  src='images/loadingAnimation.gif'  id='img_loading' style='display:none;width:28px;height:9px;'/></h2>
           <br />		  
		 ";
		 // $zHtml .= "<img  src='images/loadingAnimation.gif'  style='width:28px;height:9px;'/>";
		  $zHtml .= "<table style='margin:auto;' border=0>
		                    <tr><td><input   id='imput_item' style='background:#FFF;font-style:italic;color:#333333;border:none;font-size:13px;' size='76px' type='text' placeholder='Inserer un nouveau crit&egrave;re ici' /></td>
							<td align='right'>   <p><input type='button' onclick='Add_item();' value='Inserer' id='btn_add_item' style='cursor:pointer' class='btn_submit'></p></td>
							</tr>							
		            </table>";
         /** $zHtml .= "<p style='margin:0 0 0 748px;'><input  class='btn_submit' style='cursor:pointer' type='button'  id='btn_add_item' value='Inserer' onclick='Add_item();' /></p>";*/
		  $zHtml .=  "<div  id='new_item' ></div>";          
		  $zHtml .= "</div>";
	  echo  $zHtml ;
		?>
