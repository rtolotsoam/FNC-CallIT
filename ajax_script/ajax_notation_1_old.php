<link href="css/smart_wizard.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="style.css"></link>

<style>


.styled-select {
  
	-moz-appearance: none;
    background: none repeat scroll 0 0 #49C0F0;
    border: medium none;
    border-radius: 4px;
    box-shadow: 0 3px 0 #CCCCCC, 0 -1px #FFFFFF inset;
    color: #FFFFFF;
    cursor: pointer;
    display: inline-block;
    float: right;
    height: 20px;
    margin: 0;
    outline: medium none;
    padding: 3px;
    width:100%;
	height:20px;
       }

.styled-select option {
  /*margin: 0px;*/   
  padding: 0px;
  width: 126px;
  background-color: #FFFFFF;
  margin-right: -25px;
}

.styled-select selected{
  margin: 0px;
  padding: 0px;
  width: 115px;
  background-color: #FFFFFF;
}

.styled-select option:hover {
  /*border-top: 1px solid #44e;
  border-bottom: 1px solid #44e;
  padding: 1px 5px;*/     
  /*font-weight: bold;*/
  background-color: #FFFFFF;
  color: #799CA6;
} 
</style>

<script type="text/javascript">
    $(document).ready(function(){
    	// Smart Wizard
    	//$('#tabs').smartTab({selected: 0,autoProgress: false,stopOnFocus:false,autoHeight:false,transitionEffect:'hSlide'}); 	
    	$('#tabs1').smartTab({selected: 0,autoProgress: false,stopOnFocus:false,autoHeight:false,transitionEffect:'hSlide'});    
	});
</script>
<?php
include ("/var/www.cache/dgconn.inc") ;
include('../function_grille_2.php');

$data_eliminatoireTab = array();
echo $data_grille       = $_REQUEST['data_grille'];
$data_eliminatoire = $_REQUEST['data_eliminatoire'];
		 if( $data_eliminatoire !='' ){
		    $data_eliminatoireTab = explode(",",$data_eliminatoire);
		 }


$id_type = $_REQUEST['id_type'];
$id_projet = $_REQUEST['id_projet_notation'];
$id_application = $_REQUEST['id_application_notation'];
$id_client = $_REQUEST['id_client_notation'];
//echo $data_grille.'**';
?>

<table align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
			  <td valign="top">

			  		<div id="tabs1">
			  		<?php
			  		    echo '<ul>';
			  			if($id_type == 0)
			  			{
			  				$nombre_type = countType();
			  				$deb = 1;
			  				$nom_type = '';
			  				echo '<li><a href="#tabsa-1">Appels entrants<br /></a></li>
				  				<li><a href="#tabsa-2">Appels sortants<br /></a></li>
				  				<li><a href="#tabsa-3">Traitement des Mails<br /></a></li>';
			  			}
			  			else 
			  			{
			  				$nombre_type = $id_type;
			  				$deb = $id_type;
			  				if($id_type == 1) $nom_type = 'Appels entrants';
				  			elseif($id_type == 2) $nom_type = 'Appels sortants';
				  			elseif($id_type == 3) $nom_type = 'Traitement des Mails';
				  			echo '<li class="selector"><a href="#tabsa-1">'.$nom_type.'<br /></a></li>';
			  			}
			  			echo '</ul>';
			  			
			  			$j = 1;
			  			for($a=$deb;$a<=$nombre_type;$a++)
			  			{
				            if($a == 1) {
				            	$E_resultat = countGrille($a,'id_type_traitement',$id_projet,$id_client,$id_application);
				            	$E_result = $E_resultat['nb_grille'];
					      	    $result11 = getCategorie(1,'id_type_traitement',$a);
					      	    $E_result_cat = @pg_num_rows($result11);
				            }
				      	    
				            elseif($a == 2) {
					      	    $S_resultat = countGrille($a,'id_type_traitement',$id_projet,$id_client,$id_application);
					      	    $S_result = $S_resultat['nb_grille'];
					      	    $result22 = getCategorie(1,'id_type_traitement',$a);
					      	    $S_result_cat = @pg_num_rows($result22);
				            }
				      	  
				            elseif($a == 3) {
					      	    $T_resultat = countGrille($a,'id_type_traitement',$id_projet,$id_client,$id_application);
					      	    $T_result = $T_resultat['nb_grille'];
					      	    $result33 = getCategorie(1,'id_type_traitement',$a);
					      	    $T_result_cat = @pg_num_rows($result33);
				            }
						// Step 1
			  			echo '<div id="tabsa-'.$j.'" class="categorie" style="overflow:auto;height:490px;margin-top:5px;width:100%;display:block;">';
						echo '<table>
						  <thead>
						    <tr style="text-align:center;">
						     <th style="width:28%">CATEGORIES</th>
						     <th style="width:40%">ITEMS</th>
						     <th style="width:10%">Note Elim.</th>
						     <th style="width:22%" style="text-align:center;">Nombre choix de notation</th>
						    </tr>
						  </thead>';
						//$result = getCategorie(1,'id_type_traitement',$a);
						$result = getCategorie_notation(1,'id_type_traitement',$a,$data_grille);
					  	$type_cat = $a;
						while($res = @pg_fetch_assoc($result))
				  		{ 
						  $id_categorie = $res['id_categorie_grille'];
					      //$result_item = getCategorieGrille($id_categorie,'id_categorie_grille',$id_projet,$id_client,$id_application);
					      $result_item = getCategorieGrille_notation(1,'id_type_traitement',$a,1,'a.id_categorie_grille',$id_categorie,$data_grille);
					      $nb = @pg_num_rows($result_item);
					      //$nombre_item = countGrille($id_categorie,'id_categorie_grille',$id_projet,$id_client,$id_application);
					      //$nb = @pg_num_rows($result_item);
					      //$nb = $nombre_item['nb_grille'];
					      //$nb_projet = $nombre_item['nb_projet'];
					      $i = 1;
					  	  $is_checked = '';
					      if($nb != 0)
					      {
						      echo '<tbody>';
						      
						      while ($res_qry = @pg_fetch_assoc($result_item))
						      {
							      echo '<tr>';
							      if ($i == 1) {
							      	 echo '<th rowspan="'.$nb.'" style="text-align:left" id="cgrille_'.$res_qry['id_categorie_grille'].'">'.$res['libelle_categorie_grille'].'</th>';
							      }
							      
							      if($res_qry['id_grille'] == '') 
							      {
							      	  $id_grille = $res_qry['id_categorie_grille'].'_0';
							      	  $checked = 'hidden';
							      	  $libelle_grille = '<span style="color:red;font-weight:bold;">Aucun item</span>';
							      	  $id_categorie_grille = $res_qry['id_categorie_grille'];
							      }
							      else 
							      {
							      	  $id_grille = $res_qry['id_grille'];
							      	  $libelle_grille = $res_qry['libelle_grille'];
							      	  $id_categorie_grille = $res_qry['id_categorie_grille'];
							      }
								  
								     if( $res_qry['flag_eliminatoire'] == 1 ){
									    $is_checked = 'checked';
									 }else{
									    $is_checked = '';
									 }
							      
							       echo '<td id="grille_'.$id_grille.'">';							     
								   echo $libelle_grille;
								   
								   echo '<select class="ponderation"  id="ponderation_'.$id_grille.'" name="ponderation_'.$id_grille.'">';
								         echo '<option  class="select_option" value="1">Pond&eacute;ration</option>';
								         for ($x = 2;$x<=100;$x++)
										 {
										         if( $res_qry['ponderation']== $x ){
												 echo '<option  selected class="select_option" value="'.$x.'">'.$x.'</option>';
												 }else{
												 echo '<option  class="select_option" value="'.$x.'">'.$x.'</option>';
												 }
										     
										 }
								  
								   echo '</select>';
								   echo '</td>';
								   echo  '<td style="text-align:center;"><input  '.$is_checked.' name="check_elimin" type="checkbox" id="check_elimin_'.$id_categorie_grille .'_'.$id_grille.'_'.$i.'"  /></td>
								     <td style="text-align:center;">';
							      $flag_notation = getValeurSelect_notation($id_grille, $id_projet, $id_application, $id_client);
							      if($flag_notation == 1)
							      {
							      	  $selected1 = '';
							      	  $selected2 = 'selected';
							      }
							      else 
							      {
							      	  $selected1 = 'selected';
							      	  $selected2 = '';
							      }
							      echo '<select class="styled-select" name="note_grille" id="id_note_grille_'.$id_grille.'" >
							      <option value="'.$id_grille.'_0" '.$selected1.'>2 possibilités (0 / 1)</option>
							      <option value="'.$id_grille.'_1" '.$selected2.'>3 possibilités (0 / 0,5 / 1)</option>
							      </select>';
							      echo '</td>';
								  echo '</tr>';
								  $i++;
								  //$type_cat = $res_qry['id_type_traitement'];
						      }
						      
						      echo '</tbody>';
						      
					      }
				  		}
						
						echo '</table>
			        	</div>';
						$j++;
			  			}
			        	?>
			  	</div>  	
			  </td>
			</tr>
			</table>  
			
			<?php
			echo ' | 1'
			?>