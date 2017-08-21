<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/jquery.ui.datepicker.css" type="text/css" media="screen" />
<script>
	$('document').ready(function(){
		$('#id_date_appel_deb').datepicker();
		$('#id_date_appel_fin').datepicker();
		/*$('#id_btn_apercu').click(function(){
			var date_appel_deb = $('#id_date_appel_deb').val();
			var date_appel_fin = $('#id_date_appel_fin').val();
			var dat = date_appel_deb.split('/');
			date_appel_deb = dat[2]+'-'+dat[1]+'-'+dat[0];
			var dat = date_appel_fin.split('/');
			date_appel_fin = dat[2]+'-'+dat[1]+'-'+dat[0];
			$.post("evaluation_prime.php",
			{
				date_deb : date_appel_deb,
				date_fin : date_appel_fin
			},
			function(data) {
				$('#id_contenu_eval').html(data);
				$('#id_div_filtre_date').css('display','none');
			});
		});*/
	});
	
	function apercu()
	{
		var date_appel_deb = $('#id_date_appel_deb').val();
		var date_appel_fin = $('#id_date_appel_fin').val();
		var dat = date_appel_deb.split('/');
		date_appel_deb = dat[2]+'-'+dat[1]+'-'+dat[0];
		var dat = date_appel_fin.split('/');
		date_appel_fin = dat[2]+'-'+dat[1]+'-'+dat[0];
		$.post("evaluation_prime.php",
		{
			date_deb : date_appel_deb,
			date_fin : date_appel_fin
		},
		function(data) {
			$('#id_div_filtre_date').css('display','none');
			$('#id_contenu_eval').html(data);
			$('#id_date_appel_deb').datepicker();
			$('#id_date_appel_fin').datepicker();
			
		});
	}
</script>
<style>
	.class_table tr th{
		background: none repeat scroll 0 0 #9eb9c3;
	    font-family: Verdana;
	    font-size: 11px;
	    font-weight: bold;
	    height: 30px;
	    padding: 5px;
	    text-align: center;
	}
	.class_table tr th, .class_table tr td{
		border:1px solid #FFFFFF;
	}
	.class_table tr td{
		text-align: center;
	}
</style>
<?php
include("/var/www.cache/dgconn.inc");
$a = 0;
if(isset($_REQUEST['date_deb']) && isset($_REQUEST['date_fin']))
{
	$date_deb = $_REQUEST['date_deb'];
	$date_fin = $_REQUEST['date_fin'];
	echo getvaleur($date_deb,$date_fin);
	$a = 1;
}
function getvaleur($date_deb,$date_fin)
{
	global $conn;
$sql="select prenompersonnel,matricule_notation,count(id_notation) nombre,code,nom_client from (
select distinct n.id_notation,n.matricule_notation, p.prenompersonnel,ga.id_projet,ga.id_client,ga.id_application,gua.code,guc.nom_client
from cc_sr_notation n
inner join personnel p on p.matricule = n.matricule_notation
inner join cc_sr_indicateur_notation inot on n.id_notation = inot.id_notation
inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application
inner join gu_client guc on guc.id_client = ga.id_client
inner join gu_application gua on gua.id_application = ga.id_application
where date_entretien >= '".$date_deb."' and date_entretien <= '".$date_fin."'
order by matricule_notation ) as req1
group by prenompersonnel,matricule_notation,code,nom_client
order by matricule_notation";
$query  = pg_query($conn,$sql) or die(pg_last_error($conn));

$zHtml = '<div id="id_div_filtre_date"><span>Date d\'appel du : </span>';
$zHtml .= '<input type="text" id="id_date_appel_deb" /> au ';
$zHtml .= '<input type="text" id="id_date_appel_fin" />';
$zHtml .= '<input type="button" id="id_btn_apercu" onclick="apercu();" />';
$zHtml .= '</div>';

$str = '<table class="class_table">';
$str .= '<tr>
	<th>Pr&eacute;nom Evaluateur</th>
	<th>Matricule Evaluateur</th>
	<th>Nombre d\'&eacute;valuation</th>
	<th>Prestation</th>
	<th>Client</th>
</tr>';
while($res = pg_fetch_array($query))
{
	$str .= '<tr>
		<td>'.$res['prenompersonnel'].'</td>
		<td>'.$res['matricule_notation'].'</td>
		<td>'.$res['nombre'].'</td>
		<td>'.$res['code'].'</td>
		<td>'.$res['nom_client'].'</td>
	</tr>';
}
$str .= '</table>';
return $str;
}

if($a == 0)
{
	$zHtml = '<div id="id_div_filtre_date"><span>Date d\'appel du : </span>';
	$zHtml .= '<input type="text" id="id_date_appel_deb" /> au ';
	$zHtml .= '<input type="text" id="id_date_appel_fin" />';
	$zHtml .= '<input type="button" id="id_btn_apercu" onclick="apercu();" />';
	$zHtml .= '</div>';

	$zHtml .= '<div id="id_contenu_eval">';
	$zHtml .= '</div>';
	echo $zHtml;
}

?>