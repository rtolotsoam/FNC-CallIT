<html>
<head>
<title>Formulaire de campagne</title>
</head>
<body>
<?php
include("/var/www.cache/dgconn.inc");
include("function_grille_.php");
include("function_filtre_dynamique.php");

    
$iIdProjet         = $_REQUEST['id_projet'];
$idClient          = $_REQUEST['id_client'];
$idApplication     = $_REQUEST['id_application'];
$nomProjet = str_replace(","," ",$_REQUEST['nom_projet']);
$nomClient= str_replace(","," ",$_REQUEST['nom_client']);
$nomApplication= str_replace(","," ",$_REQUEST['nom_application']);

     
// $iIdProjet         = 51;
// $idClient          = 599;
// $idApplication     = 408;
// $campagne_easycode = 6;
 
$nomProjet = str_replace(","," ",$_REQUEST['nom_projet']);

 
/**$zresultHtml = "<div style='margin:0px 0 -25px 1px'>
   <center>
   <p><b>Client: </b>".$nomClient."</p>
   <p><b>Application: </b>".$nomApplication."</p>
   <p style='color:#158ade;'><b>Duplication vers</b></p>
 </center></div>";
 */
 $zresultHtml .= "<div style='background:#e6eeee;border:1px solid #b2c6cd;margin:0 0 -25px -13px;width: 238px;'>

   <ul>
        <li><span style='font-weight:bold;margin-right:10px;color:#799ca6;'>Client:</span>&nbsp;".$nomClient."</li>
        <li><span style='font-weight:bold;margin-right:10px;color:#799ca6;'>Application:</span>&nbsp;".$nomApplication."</li>
   </ul>
    <p style='color:#799ca6;margin-left: 5px;'><b>Duplication vers</b></p>

 </div>";
$zresultHtml .="";
$zresultHtml .= "<form  id='form_duplication'>";
$zresultHtml .= "<table style='margin: 20px 0 0 67px;' cellspacing='10' border=0 id='table_duplication'>";
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
$zresultHtml .= "<tr>";
$zresultHtml .= "<td align='right'><strong>Client:</strong></td>";
$zresultHtml .= "<td>&nbsp;<select class='champ_select' style='height:20px;margin:0 0 0 -3px;width:197px;' id='champ_client'  onchange ='fill_application_2();' name='champ_client'>";
$zresultHtml .= "<option value=''>------selectionner------</option>";

$sql_client = "SELECT p.id_projet,p.id_client,c.nom_client FROM cc_sr_projet p INNER JOIN gu_client c ON  p.id_client=c.id_client WHERE p.id_projet <> ".$iIdProjet." AND p.flag_duplication IS NULL AND archivage = 1 ORDER BY c.nom_client";

//$result_client = pg_query($conn,$sql_client); 
$selected = 'selected';
$tab_client = array();
$result_client = pg_query($conn,$sql_client) or die(pg_last_error());
while ($res_client = pg_fetch_array($result_client))
{
	if(!in_array($res_client['id_client'],$tab_client))
	{
		$zresultHtml .= '<option value="'.$res_client['id_client'].'_'.$res_client['id_projet'].'">'.$res_client['nom_client'].'</option>';
		array_push($tab_client,$res_client['id_client']);
	}
}

$zresultHtml .= "</select></td>";
$zresultHtml .= "</tr>";
/****************************/
$zresultHtml .= "<tr>";
$zresultHtml .= "<td align='right'><strong>Prestation:</strong></td>";
$zresultHtml .= "<td><select class='champ_select' style='height:20px;width:197px;' id='champ_application'  name='champ_application' onChange='load_project();'>";

   $zresultHtml .= "<option value=''>------selectionner------</option>";
 
$zresultHtml .= "</select></td>";
$zresultHtml .= "</tr>";
$zresultHtml .= "<tr>";
$zresultHtml .= "<td align='right'><input type='checkbox' id='dupliquer_penalite' /></td><td>Dupliquer les p&eacute;nalit&eacute;s</td>";
$zresultHtml .= "</tr>";
$zresultHtml .= "<tr>";
$zresultHtml .= "<td>&nbsp;</td>";
$zresultHtml .= "<td id='td_dupliquer' align='right'>
<input  onClick = 'dupliquer_grille(".$iIdProjet.",".$idClient.",".$idApplication.")' type='button' id='submit_duplication' class='btn_visu' value='Dupliquer' />
            </td>";
$zresultHtml .= "</tr>";
$zresultHtml .= "</table>";
$zresultHtml .= "</form>";
echo $zresultHtml;

?>
</body>
</html>