<link href="css/smart_wizard.css" rel="stylesheet" type="text/css">

<?php
include ("/var/www.cache/dgconn.inc") ;
include('../function_grille_2.php');


$data_grille     = $_REQUEST['data_grille'];
$id_type         = $_REQUEST['id_type'];
$str_elimin2     = $_REQUEST['str_elimin2'];
$str_ponderation = $_REQUEST['str_ponderation'];

$str_ponderation = explode(',',$str_ponderation);
  
   
  
  	 $array_grille_pond = array();
	 for($k_=0;$k_<count( $str_ponderation );$k_++)
	 {
	     $_pond = explode('#',$str_ponderation[$k_]);
		 $array_grille_pond[$_pond[0]]= $_pond[1];		 
	 }
	 
	
		

$str_elimin2 = explode(',',$str_elimin2);
//$grille = explode(',',$data_grille);


$str = '
<table border="1" style="height:auto;">
<tr>
	<th style="width:34%">CATEGORIES</th>
	<th style="width:5%">NOMBRE</th>
	<th style="width:65%">QUESTIONNAIRES</th>
	<th style="width:65%"><span style="float:right">Pond&eacute;ration</span></th>
</tr>';

if($id_type == 0) 
{
	// $count = 3;
	$count = 4;
	$deb   = 1;
}
else 
{
	$count = $id_type;
	$deb   = $id_type;
}

$nb_quest = array();
$nb_cat   = array();
for($i=$deb;$i<=$count;$i++)
{
	if($i == 1) {$type = 'Appels entrants';}
	if($i == 2) {$type = 'Appels sortants';}
	if($i == 3) {$type = 'Traitement de mails';}
	if($i == 4) {$type = 'Traitement de tchats';}
	
	$str .= '<tr>
		<th style="background:#799CA6;color:#FFFFFF;" colspan="2" style="text-align:center">'.$type.'</th>
		<th style="background:#799CA6;color:#FFFFFF;">&nbsp;</th>
		<th style="background:#799CA6;">&nbsp;</th>
	</tr>';
	 
	$tab_cat = array();
	//$resultat = getResumeCategorie($data_grille, $i);
	
	$result     = getResumeCategorie($data_grille, $i);
	$nb_cat[$i] = countCategorie($data_grille, $i,'id_type_traitement','distinct');
	$next       = 0;
	$n          = 0;
	
	while ($res = @pg_fetch_assoc($result))
	{
		if($next != $res['id_categorie_grille']){
		 	$n = 0;
		 	$str .= '<tbody><tr>';
		 	$nb = countCategorie($data_grille, $res['id_categorie_grille'],'id_categorie_grille',' ');
		 	$nb_grille = countGrilleByCategorie($res['id_categorie_grille']);
		 	$str .= '<th rowspan="'.$nb.'" style="border:2px solid #B2C6CD;">'.$res['libelle_categorie_grille'].'</th>';
		 	$str .= '<th rowspan="'.$nb.'" style="text-align:center;">'.$nb.' / '.$nb_grille.'</th>';
		 	$next = $res['id_categorie_grille'];
		}else {
		 	$str .= '<tr>';
		}
		$str .= '<td>';
		  
		$str .= $res['libelle_grille'];
		 
		if(in_array($res['id_grille'],$str_elimin2)){
			$str .= '&nbsp;&nbsp;<span style="color:red">(Note eliminatoire)</span>';
		}
		 
		if( $array_grille_pond[$res['id_grille']]==0 || $array_grille_pond[$res['id_grille']]==''  ){
			$array_grille_pond[$res['id_grille']]=1;
		}
		$str .='</td>';
		$str .= "<td><center><span  style='color:#666666;'>".$array_grille_pond[$res['id_grille']]."</span></center></td>";
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