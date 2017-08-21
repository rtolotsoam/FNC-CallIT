<?php
include("/var/www.cache/dgconn.inc");
include('conn_mssqlserver.php');

if(isset($_REQUEST['date_debut_call']) && isset($_REQUEST['date_fin_call']) && isset($_REQUEST['ct_table']))
{
	$date_deb_call = $_REQUEST['date_debut_call'];
	$date_fin_call = $_REQUEST['date_fin_call'];
	$ct_table = $_REQUEST['ct_table'];
	$id_projet_call = $_REQUEST['id_projet_call'];
	$id_client_call = $_REQUEST['id_client_call'];
	$id_application_call = $_REQUEST['id_application_call'];
	$id_type_traitement_call = $_REQUEST['id_type_traitement_call'];
	$id_tlc_call = $_REQUEST['id_tlc_call'];
	
	$date_deb = explode('/',$date_deb_call);
	$date_deb = $date_deb[2].'-'.$date_deb[1].'-'.$date_deb[0];
	$date_fin = explode('/',$date_fin_call);
	$date_fin = $date_fin[2].'-'.$date_fin[1].'-'.$date_fin[0];
	
	echo getALLCall($date_deb,$date_fin,$ct_table,$id_projet_call,$id_client_call,$id_application_call,$id_type_traitement_call,$id_tlc_call);
}

function getProjetCall($id_projet,$id_client,$id_application,$id_tlc)
{
	global $conn;
	$sql = "select p.id_projet, p.nom_projet, p.id_client,guc.nom_client, 
	p.id_application, gua.code, gua.nom_application 
	from cc_sr_projet p
	inner join gu_application gua on gua.id_application = p.id_application
	inner join gu_client guc on guc.id_client = p.id_client
	where p.id_projet = ".$id_projet." and p.id_client = ".$id_client." and p.id_application = ".$id_application;
	$query = pg_query( $conn, $sql ) or die(pg_last_error());
	$table = pg_fetch_array($query);
	
	$sql = "select matricule, prenompersonnel from personnel where matricule = ".$id_tlc;
	$query = pg_query( $conn, $sql ) or die(pg_last_error());
	$tab = pg_fetch_array($query);
	$table['matricule'] = $tab['matricule'];
	$table['prenom'] = $tab['prenompersonnel'];
	return $table;
}

function getDonneesCall($ct_table,$date_deb,$date_fin,$id_tlc_call,$login,$mdp)
{
	global $link;
	$table = array();
	$_ctTab = explode(';',$ct_table);
	$nb_ctTab = count($_ctTab);
	for($j=0;$j<$nb_ctTab;$j++)
	{
		$add_sql = " ";
		if($id_tlc_call != 0)
		{
			$add_sql = " AND agentname LIKE '".$id_tlc_call."%'";
		}
		$sql = "SELECT * from ".$_ctTab[$j]." WHERE CAST(date_of_call AS DATETIME) >= '".$date_deb." 00:00:00' AND CAST(date_of_call AS DATETIME) <= '".$date_fin." 23:59:59'".$add_sql ;
		//echo '<br>';
		$result = mssql_query($sql ,$link) or die('Erreur');
		$nb_rows = mssql_num_rows($result);
		if($nb_rows != 0)
		{
			while($res = mssql_fetch_array($result))
			{
				$easycode = $res['easycode'];
				$typ = array();
				$typ = explode('_',$res['typecall']);
				$typecall = '';
				for($i=1;$i<count($typ);$i++)
				{
					$typecall .= $typ[$i].' ';
				}
				$date_call = $res['date_of_call'];
				list($mat_agent,$agent) = explode('_',$res['agentname']);
				$table['date_call'][] = $date_call;
				$table['easycode'][] = $easycode;
				$table['mat_agent'][] = $mat_agent;
				$table['agent'][] = $agent;
				$table['typecall'][] = $typecall;
				$table['duree'][] = $res['duration'];
				$table['login'][] = $login[$j];
				$table['mdp'][] = $mdp[$j];
			}
		}
	}
	return $table;
}

function getALLCall($date_deb,$date_fin,$ct_table,$id_projet_call,$id_client_call,$id_application_call,$id_type_traitement_call,$id_tlc_call)
{
	$ctTab = getAllCampaign($id_type_traitement_call,$id_projet_call);
	$login = $ctTab[$id_type_traitement_call][$id_projet_call]['login'];
	$mdp = $ctTab[$id_type_traitement_call][$id_projet_call]['mdp'];
	$tab_ct = getDonneesCall($ct_table,$date_deb,$date_fin,$id_tlc_call,$login,$mdp);
	if(empty($tab_ct))
	{
		$zHtml = '<span style="font-size:12px;font-family:Verdana;padding:20px;font-weight:bold;">Aucun enregistrement !</span>';
		$zHtml .= '||||0';
	}
	else
	{
		$zHtml = '<table class="class_donnee_contenu">';
		$zHtml .= '<thead>';
		$zHtml .= '<tr>';
		$zHtml .= '<th>Date appel</th>';
		$zHtml .= '<th>EasyCode</th>';
		$zHtml .= '<th>Matricule Agent</th>';
		$zHtml .= '<th>Nom Agent</th>';
		$zHtml .= '<th>Type call</th>';
		$zHtml .= '<th>Dur&eacute;e appel<br>(en hh:mn)</th>';
		$zHtml .= '<th>Action</th>';
		$zHtml .= '</tr>';
		$zHtml .= '</thead>';
		$zHtml .= '<tbody>';
		for($i=0;$i<count($tab_ct['easycode']);$i++)
		{
			$zHtml .= '<tr>';
			$zHtml .= '<td>'.$tab_ct['date_call'][$i].'</td>';
			$zHtml .= '<td>'.$tab_ct['easycode'][$i].'</td>';
			$zHtml .= '<td>'.$tab_ct['mat_agent'][$i].'</td>';
			$zHtml .= '<td>'.$tab_ct['agent'][$i].'</td>';
			$zHtml .= '<td>'.$tab_ct['typecall'][$i].'</td>';
			$zHtml .= '<td>'.$tab_ct['duree'][$i].'</td>';
			$zHtml .= '<td><input type="hidden" id="easycode_'.$i.'" value="'.$tab_ct['easycode'][$i].'" />
			<input type="hidden" id="mat_agent_'.$i.'" value="'.$tab_ct['mat_agent'][$i].'" />
			<img src="images/select_3.png" width="17px" height="17px" style="cursor:pointer" title="S&eacute;lectionner" onclick="openEcoute(\''.$tab_ct['login'][$i].'\',\''.$tab_ct['mdp'][$i].'\','.$tab_ct['easycode'][$i].','.$i.')" /></td>';
			//$zHtml .= '<td><a target="_blank" href="https://41.188.3.110/records/?user='.$login.'&pass='.$mdp.'&easycode='.$tab_ct['easycode'][$i].'" class="lien_ecoute" ><img src="images/select_3.png" width="17px" height="17px" style="cursor:pointer" title="S&eacute;lectionner"/></a></td>';
			$zHtml .= '</tr>';
		}
		$zHtml .= '</tbody>';
		$zHtml .= '</table>';
		$zHtml .= '||||'.count($tab_ct['easycode']);
	}
	return $zHtml;
}

function getAllCampaign($id_type_traitement,$id_projet)
{
	global $conn;
	$sql = "select * from cc_sr_campaign where flag_type = ".$id_type_traitement." and id_projet = ".$id_projet." and ct_table != '' and login != '' and password != ''";
	$result = pg_query( $conn, $sql ) or die(pg_last_error());
	$table = array();
	while ($res = pg_fetch_array($result))
	{
		$id_campaign = $res['id_campaign'];
		$id_type_traitement = $res['flag_type'];
		$id_projet = $res['id_projet'];
		$cLogin = $res['login'];
		$cPass = $res['password'];
		$ct_table = $res['ct_table'];
		$campaign = $res['nom_campaign'];
		if($id_type_traitement != '' && $id_projet != '' && $cLogin != '' && $cPass != '' && $ct_table != '' )
		{
			$table[$id_type_traitement][$id_projet]['table'][] = 'ct_'.$ct_table;
			$table[$id_type_traitement][$id_projet]['login'][] = $cLogin;
			$table[$id_type_traitement][$id_projet]['mdp'][] = $cPass;
		}
	}
	return $table;
}
?>