<link href="css/smart_wizard.css" rel="stylesheet" type="text/css">

<?php
include ("/var/www.cache/dgconn.inc") ;
include('../function_grille_2.php');


$data_grille = $_REQUEST['data_grille'];
$id_type = $_REQUEST['id_type'];

//$grille = explode(',',$data_grille);



$str = '
<table border="1" style="height:auto;">
<tr>
	<th style="width:30%">CATEGORIES</th>
	<th style="width:50%">QUESTIONNAIRES</th>
	<th style="width:20%">NOTATION</th>
</tr>';

if($id_type == 0) 
{
	$count = 3;
	$deb = 1;
}
else 
{
	$count = $id_type;
	$deb = $id_type;
}

$nb_quest = array();
$nb_cat = array();
for($i=$deb;$i<=$count;$i++)
{
	if($i == 1) {$type = 'Appels entrants';}
	if($i == 2) {$type = 'Appels sortants';}
	if($i == 3) {$type = 'Traitement de mails';}
	
	 $str .= '<tr>
		<th colspan="3" style="text-align:center">'.$type.'</th>
	</tr>';
	 
	$tab_cat = array();
	//$resultat = getResumeCategorie($data_grille, $i);
	
	$result = getResumeCategorie($data_grille, $i);
	$nb_cat[$i] = countCategorie($data_grille, $i,'id_type_traitement','distinct');
	$next = 0;
	$n = 0;
	
	while ($res = @pg_fetch_assoc($result))
	{
		 if($next != $res['id_categorie_grille'])
		 {
		 	$n = 0;
		 	$str .= '<tbody><tr>';
		 	$nb = countCategorie($data_grille, $res['id_categorie_grille'],'id_categorie_grille',' ');
		 	$nb_grille = countGrilleByCategorie($res['id_categorie_grille']);
		 	$str .= '<th rowspan="'.$nb.'" style="border:2px solid #B2C6CD;">'.$res['libelle_categorie_grille'].'</th>';
		 	$next = $res['id_categorie_grille'];
		 }
		 else 
		 {
		 	$str .= '<tr>';
		 }
		 $str .= '<td>'.$res['libelle_grille'].'</td>';
		 $str .= '<td>';
		 $str .= '<select class="styled-select" id="id_note_grille_'.$res['id_grille'].'" style="height:16px;width:100%;">
			      <option value="1">2 possibilités (0 / 1)</option>
			      <option value="2">3 possibilités (0 / 0,5 / 1)</option>
			      </select>';
		 $str .= '</td>';
		 $str .= '</tr>';
		 $n++;
		 if($n == $nb) $str .= '</tbody>';
		 $nb_quest[$i]++;
	}
}


$str .= '</table>';

echo $str.' | ';

for($i=1;$i<=3;$i++)
{
	if($nb_cat[$i]) echo $nb_cat[$i]; else echo '0';
	echo ' | ';
}

for($i=1;$i<=3;$i++)
{
	if($nb_quest[$i]) echo $nb_quest[$i]; else echo '0';
	if($i<3) echo ' | ';
}

?>