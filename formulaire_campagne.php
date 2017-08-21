<html>
<head>
<title>Formulaire de campagne</title>
</head>
<body>
<?php
include("/var/www.cache/dgconn.inc");
include("function_grille_.php");

    
$iIdProjet         = $_REQUEST['iIdProjet'];
$idClient          = $_REQUEST['idClient'];
$idApplication     = $_REQUEST['idApplication'];
$campagne_easycode = $_REQUEST['campagne_easycode'];

 

$libelle_application = str_replace(","," ",$_REQUEST['libelle_application']);
$nomProjet = str_replace(","," ",$_REQUEST['nomProjet']);

      if( $iIdProjet!= 0 ){
	 
	  echo  "<script type='text/javascript'>                
               $('#submit_interface').hide();
			   $('#modifier_projet').show();
			 
		               
             </script>";
			
	  }else{
	      echo  "<script type='text/javascript'>                
               $('#submit_interface').show();
			    $('#modifier_projet').hide();
             </script>";
	  }
 
//$zHtmlResult = "";
if( $iIdProjet != 0  ){
$zresultHtml .= "<form onsubmit='return update_projet( $iIdProjet );' id='form_modif_interface'>";
}else{
$zresultHtml .= "<form onsubmit='return insert_projet();' id='form_interface'>";
}

$zresultHtml .= "<table cellspacing='10' border=0 id='table_interface'>";
$zresultHtml .= "<input type='hidden' id='champ_cache' value='' />";
$zresultHtml .= "<tr>";
//$zresultHtml .= "<td  align='right'><strong>Projet:</strong></td>";
if( $nomProjet != "" ){
$zresultHtml .= "<td><input type='hidden' name='champ_projet' value='$nomProjet' id='champ_projet' required='required'/></td>";
}else{
$zresultHtml .= "<td><input type='hidden' name='champ_projet'  id='champ_projet' required='required'/></td>";
}
$zresultHtml .= "</tr>";
$location ="bigserver/";
/**$zresultHtml .= "<tr>";
$zresultHtml .= "<td><strong>R&eacute;pertoire:</strong></td>";
$zresultHtml .= "<td><select style='height:20px;' id='champ_repertoire'  name='champ_repertoire'>";
$zresultHtml .= "<option value=''>------selectionner------</option>";
     if ($file= opendir($location)){ 
			while(false !==($fichier = readdir($file))){
				  if($fichier !=".." && $fichier != "."){

                  $fichier_sans_rec = substr($fichier,0,strlen($fichier)-5);
				

				$zresultHtml .= "<option style='height: 20px;' value='$fichier'>".$fichier."</option>";

				}

		   }
		}
$zresultHtml .= "</select></td>";
$zresultHtml .= "</tr>";
/****************************/
$zresultHtml .= "<tr>";
$zresultHtml .= "<td align='right'><strong>Client:</strong></td>";
$zresultHtml .= "<td>&nbsp;<select class='champ_select' style='height:20px;margin:0 0 0 -3px;' id='champ_client' required='required' onchange ='fill_application();' name='champ_client'>";
$zresultHtml .= "<option value=''>------selectionner------</option>";

$sql_client = "select id_client ,nom_client from gu_client where id_client in (
select max(id_client)  from gu_client  group by nom_client)
order by nom_client";
/**$sql_client = "select c.id_client,c.nom_client from gu_client c
inner join ca_client_cegid_gpao cegid on c.id_client=cegid.id_gu_client order by id_client asc";*/
$result_client = pg_query($conn,$sql_client); 
$selected = 'selected';
 while ($rows = pg_fetch_array($result_client)) {
 
          if( $idClient == $rows['id_client'] ){
		    $zresultHtml .= "<option $selected style='height: 20px;' value={$rows['id_client']}>{$rows['nom_client']}</option>";
		  }else{
             $zresultHtml .= "<option style='height: 20px;' value={$rows['id_client']}>{$rows['nom_client']}</option>";
          }
 }
$zresultHtml .= "</select></td>";
$zresultHtml .= "</tr>";
/****************************/
$zresultHtml .= "<tr>";
$zresultHtml .= "<td align='right'><strong>Prestation:</strong></td>";
$zresultHtml .= "<td><select class='champ_select' style='height:20px;' id='champ_application' required='required' name='champ_application' onChange='load_project();'>";
 if( $idApplication !='' ){
 $zresultHtml .= "<option value='$idApplication'>$libelle_application</option>";
 }else{
   $zresultHtml .= "<option value=''>------selectionner------</option>";
  }
$zresultHtml .= "</select></td>";
$zresultHtml .= "</tr>";

$zresultHtml .= "<tr>";
$zresultHtml .= "<td align='right'><strong>Campagne easyPhone:</strong></td>";
$zresultHtml .= "<td><select style='height:20px;' id='champ_campagne'  name='champ_campagne'>";
     if( $iIdProjet != 0 && $campagne_easycode != ''){
	    $nom_campagne = get_nom_campagne( $campagne_easycode );
	    $zresultHtml .= "<option value='{$campagne_easycode}'>{$nom_campagne}</option>";
	 }else{
$zresultHtml .= "<option value=''>------selectionner------</option>";
     }
$zresultHtml .= "
                </select>
				</td>
				<tr>";

$zresultHtml .= "<tr>";
$zresultHtml .= "<td>&nbsp;</td>";
$zresultHtml .= "<td id='td_submit' align='right'>
<input  type='submit' class='btn_visu' id='submit_interface' value='Inserer projet' />
<input   type='submit'   class='btn_visu' id='modifier_projet'  value='Modifier projet' />
            </td>";
$zresultHtml .= "</tr>";
$zresultHtml .= "</table>";
$zresultHtml .= "</form>";
echo $zresultHtml;

?>
</body>
</html>