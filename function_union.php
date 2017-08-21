<?php
include("/var/www.cache/dgconn.inc");

function fetchAll($id_projet,$id_client,$id_application, $id_notation, $id_type_traitement,$id_tlc,$id_fichier)
{
	global $conn;
	if($id_notation != 0)  //--case when inot.flag_ponderation = 1 then 0 else ga.ponderation end as ponderation,
	{
		$sql_select = "SELECT cg.id_categorie_grille,cg.libelle_categorie_grille,g.id_grille,g.libelle_grille,gd.note,
		 gd.libelle_description,ga.flag_is,ga.flag_eliminatoire,ga.id_repartition,c.id_classement,c.libelle_classement,c.section,
		 
		 ga.ponderation,inot.flag_ponderation,
ga.flag_eliminatoire, gc.ponderation_classement, gc.ponderation_section, inot.commentaire, inot.note point, inot.commentaire_si, 
inot.id_notation, cs_not.id_fichier, ga.id_grille_application
		 FROM  cc_sr_grille_application ga
inner join  cc_sr_grille g ON g.id_grille=ga.id_grille
inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application
inner join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement
inner join cc_sr_classement c on c.id_classement=cg.id_classement 
inner join cc_sr_grille_classement gc on 
(gc.id_projet = ".$id_projet." and gc.id_client = ".$id_client." and gc.id_application = ".$id_application." and gc.id_classement = c.id_classement)
left join cc_sr_indicateur_notation inot on inot.id_grille_application = ga.id_grille_application
left join cc_sr_notation cs_not on inot.id_notation = cs_not.id_notation
where cg.id_type_traitement=".$id_type_traitement." 
and ga.id_projet=".$id_projet."
and ga.id_client=".$id_client."
and ga.id_application=".$id_application." 
and cs_not.id_notation = ".$id_notation."  
order by cg.ordre,g.ordre, c.section,c.id_classement,cg.id_categorie_grille ASC,g.id_grille ASC,gd.note DESC";
		
		// echo 'sql_select => <pre>';
		// echo $sql_select;
		// echo '</pre>';
	}
	else //--case when inot.flag_ponderation = 1 then 0 else ga.ponderation end as ponderation,
	{
		$sql_select = "SELECT cg.id_categorie_grille,cg.libelle_categorie_grille,g.id_grille,g.libelle_grille,gd.note,
		 gd.libelle_description,ga.flag_is,ga.flag_eliminatoire,ga.id_repartition,c.id_classement,c.libelle_classement,c.section,
		 
		 ga.ponderation
, ga.flag_eliminatoire, gc.ponderation_classement, gc.ponderation_section, ''::character varying as commentaire, -1::integer as point, ''::character varying as commentaire_si, ga.id_grille_application
		 FROM  cc_sr_grille_application ga
left join  cc_sr_grille g ON g.id_grille=ga.id_grille
left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application
left join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement
left join cc_sr_classement c on c.id_classement=cg.id_classement 
left join cc_sr_grille_classement gc on 
(gc.id_projet = ".$id_projet." and gc.id_client = ".$id_client." and gc.id_application = ".$id_application." and gc.id_classement = c.id_classement)
where cg.id_type_traitement=".$id_type_traitement." 
and ga.id_projet=".$id_projet."
and ga.id_client=".$id_client."
and ga.id_application=".$id_application." 
order by cg.ordre,g.ordre,c.section,c.id_classement,cg.id_categorie_grille ASC,g.id_grille ASC,gd.note DESC";

	}
      $query_select  = pg_query( $sql_select ) or die(pg_last_error());
      return $query_select;
}

function fetchAllClassement($id_projet,$id_client,$id_application, $id_notation, $id_type_traitement,$id_tlc,$id_fichier)
{
	global $conn;
	if($id_notation != 0)  //--case when inot.flag_ponderation = 1 then 0 else ga.ponderation end as ponderation,
	{
		$sql_select = "SELECT distinct c.id_classement,c.libelle_classement, c.section,ponderation_classement
		 FROM  cc_sr_grille_application ga
		inner join  cc_sr_grille g ON g.id_grille=ga.id_grille
		inner join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application
		inner join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement
		inner join cc_sr_classement c on c.id_classement=cg.id_classement 
		inner join cc_sr_grille_classement gc on 
		(gc.id_projet = ".$id_projet." and gc.id_client = ".$id_client." and gc.id_application = ".$id_application." and gc.id_classement = c.id_classement)
		left join cc_sr_indicateur_notation inot on inot.id_grille_application = ga.id_grille_application
		left join cc_sr_notation cs_not on inot.id_notation = cs_not.id_notation
		where cg.id_type_traitement=".$id_type_traitement." 
		and ga.id_projet=".$id_projet."
		and ga.id_client=".$id_client."
		and ga.id_application=".$id_application." 
		and cs_not.id_notation = ".$id_notation."  
		order by c.section,c.id_classement ASC";
	}
	else //--case when inot.flag_ponderation = 1 then 0 else ga.ponderation end as ponderation,
	{
		$sql_select = "SELECT distinct c.id_classement,c.libelle_classement, c.section,ponderation_classement
		 FROM  cc_sr_grille_application ga
		left join  cc_sr_grille g ON g.id_grille=ga.id_grille
		left join cc_sr_categorie_grille cg on cg.id_categorie_grille=g.id_categorie_grille
		left join cc_sr_grille_description gd on gd.id_grille_application=ga.id_grille_application
		left join cc_sr_type_traitement tt on tt.id_type_traitement=cg.id_type_traitement
		left join cc_sr_classement c on c.id_classement=cg.id_classement 
		left join cc_sr_grille_classement gc on 
		(gc.id_projet = ".$id_projet." and gc.id_client = ".$id_client." and gc.id_application = ".$id_application." and gc.id_classement = c.id_classement)
		where cg.id_type_traitement=".$id_type_traitement." 
		and ga.id_projet=".$id_projet."
		and ga.id_client=".$id_client."
		and ga.id_application=".$id_application." 
		 order by c.section,c.id_classement ASC";

	}
	
	//echo '<pre>'.print_r($sql_select).'</pre>';
      $query_select  = pg_query( $sql_select ) or die(pg_last_error());
      return $query_select;
}


function calculTotalGeneral($id_projet, $id_client, $id_application, $id_notation, $id_type_traitement)
{
	$result_select = fetchAll($id_projet,$id_client,$id_application,$id_notation,$id_type_traitement,$id_tlc=0,$id_fichier=0);
	$tableauBord   = array();
	
	$Nb       = pg_num_rows(  $result_select );
	/***/
	// echo 'Nb => '.$Nb.'</br>';
	/***/
	
	$idKTgory = 0;
	
	$penalite_projet = get_penalite_projet( $id_projet , $id_type_traitement);
	
	for($k = 0 ; $k < $Nb ; $k++) {
		$row = pg_fetch_array($result_select,$k);

		if (!isset($tableauBord[$row['section']])){
			$tableauBord[$row['section']] = array();
		}
		
		if (!isset($tableauBord[$row['section']][$row['id_classement']])){
			$tableauBord[$row['section']][$row['id_classement']]                           = array();
			$tableauBord[$row['section']][$row['id_classement']]['libelle']                = $row['libelle_classement'];
			$tableauBord[$row['section']][$row['id_classement']]['ponderation_classement'] = $row['ponderation_classement']; // Njiva
			$tableauBord[$row['section']][$row['id_classement']]['ponderation_section']    = $row['ponderation_section']; // Njiva
			$tableauBord[$row['section']][$row['id_classement']]['ktgory']                 = array(); 
		}

		if (!isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']])) {
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['libelle'] = $row['libelle_categorie_grille'];
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item']    = array(); 
		}

		if (!isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']])){
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['libelle']               = $row['libelle_grille'];
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['commentaire']           = $row['commentaire']; // Njiva
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['point']                 = $row['point']; // Njiva
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['commentaire_si']        = $row['commentaire_si']; // Njiva
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note']                  = array();	
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation']           = array();
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_ponderation']      = array(); // Njiva
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['eliminatoire']          = $row['flag_eliminatoire'];	
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['id_grille_application'] = $row['id_grille_application'];
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_is']               = $row['flag_is'];		
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['id_repartition']        = $row['id_repartition'];		
		}
		
		if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'])) {
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['note'][$row['note']] = $row['libelle_description'];			
		}

		if (isset($tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'])) {
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['ponderation'][$row['libelle_description']]      = $row['ponderation'];			
			$tableauBord[$row['section']][$row['id_classement']]['ktgory'][$row['id_categorie_grille']]['item'][$row['id_grille']]['flag_ponderation'][$row['libelle_description']] = $row['flag_ponderation'];	// Njiva		
		}
	}
	
	$counter1           = 0;
	$nbeEliminatoire    = 0; 
	$test               = 0; // Njiva
	$str_indicateur     = '';
	$_sum_total_base    = 0;
	$_sum_total_produit = 0;
	
	// echo '<pre> tableauBord => ';
	// print_r($tableauBord);
	// echo '</pre></br>';
	
	foreach($tableauBord as $sectionkey=>$section){
		$rowspanSection = 0;
		foreach($section as $key=>$val){
			$rowspanSection++;
			foreach($val['ktgory'] as $key1=>$tab){
				foreach($tab['item'] as $item) {
					$rowspanSection += count($item['note']);
				}
			}
			$rowspanSection++;
		}
		
		$rowspanSection +=2; 
		
		/************** Njiva ***************/
		$total_section = 0;
		$sum_ponderation_classement = 0;
		/************************************/
		
		foreach($section as $key_section=>$val){
			/***** Njiva **************/
			$sum_ponderation  = 0;
			$total_classement = 0;
			$_total_base      = 0;
			/************************/
			$count_si = 0;
			
			foreach($val['ktgory'] as $key=>$tab){
				$rowSpanKt = 0;
			
				foreach($tab['item'] as $item) {
					$rowSpanKt += count($item['note']);
				}
				
				$nb_total_ligne = $rowSpanKt;      
				$nb_test        = 1;   
				$counter2       = 0;
				$a              = 1;
				
				foreach( $tab['item'] as $id_grille=>$item  ){
					$commentaire    = isset($item['commentaire']) ? $item['commentaire'] : ''; // Njiva
					$point          = isset($item['point']) ? $item['point'] : -1; // Njiva
					$commentaire_si = isset($item['commentaire_si']) ? $item['commentaire_si'] : ''; // Njiva
					
					$counter3 = 0;
					$nb_note  = count($item['note']);
					$nb_n     = 1;
					
					$_indicateur            = $item['flag_is'];
					$_id_grille_application = $item['id_grille_application'];
					$_id_repartition        = $item['id_repartition'];
					$str_indicateur        .= '&'.$_id_grille_application.'|'.$_indicateur.'|'.$_id_repartition;
					
					foreach ($item['note'] as $note_=>$description){
						if( $counter3==0){
							// echo '$item[flag_ponderation][$description] => '.$item['flag_ponderation'][$description].'****** ';
							if($item['flag_ponderation'][$description] == 1){
								$item['ponderation'][$description] = 0;
							}
							/*************** Njiva **************/
							$ponderation      = $item['ponderation'][$description];
							$sum_ponderation += $ponderation;
							
							// echo 'ponderation => '.$ponderation.'xxx ';
							// echo 'sum_ponderation => '.$sum_ponderation.'xxxx ';
							
							/************************************/
							$_total_base      += $ponderation;
							$produit_base_note = $point * $item['ponderation'][$description];
							if( $produit_base_note < 0  ){
								$produit_base_note =0;
							}
		
							if($item['eliminatoire'] == 1){
								if(isset($nbReelEliminatoire)) $nbReelEliminatoire ++;
								else $nbReelEliminatoire = 1;
								
								if($point == 0 || $point == -1){
									$total_general = '0.00';
									$test = 1;
								}
							}
							
							if ($commentaire_si != '') { // Njiva
								$count_si = $count_si+1;
								$nbeEliminatoire++;
								$test_comment = 1;
							} else {
								$test_comment = 0;
							}
							
							$total_classement += $point * $ponderation; // Njiva
							// echo '$point * $ponderation => '.$point.' * '.$ponderation.'xxxx ';
						}
						$counter3 ++;
						$nb_n ++;
					}
					$counter2 ++;
					$a++;
				}
				$counter1++;
			}
			/*********** Njiva *********************/
			// echo 'sum_ponderation => '.$sum_ponderation.'xxxx ';
			if($sum_ponderation == 0){
				if($id_client == 643 || $id_client == 642 ){
					$total_classement = 100;
				}else{
					$total_classement = 1;
				}
				$sum_ponderation = 1;
			}else{
				$total_classement = number_format($total_classement/$sum_ponderation,2);
			}
			
			$total_classement = get_nombre_si($count_si,$key_section,$penalite_projet,$total_classement);
			if($total_classement <= -1)
			{
				$total_classement = 0;
			}
			/*******************************************/
			// echo 'total_classement => '.$total_classement.'xxxx ';
			/* Ajouté le 31/07/2014 */
			if(($id_type_traitement == 1 || $id_type_traitement == 2) && ($id_client != 642 && $id_client != 643)) //client différent de DELAMAISON
			{
				$total_classement = $total_classement * 10;
			}
			/* ************* */
			/******************** Classement ***********************/ // Njiva
			$valeur_ponderation_classement  = $_total_base;
			$produit_base_moyenneClassement = $total_classement * $val['ponderation_classement']; //'. $val['libelle'].'
			//$produit_base_moyenneClassement = $total_classement * $valeur_ponderation_classement;
			
			$ponderation_section = $val['ponderation_section']; // Njiva
			/*******************************************************/
			
			$total_section += $total_classement * $val['ponderation_classement'];
			//$total_section += $total_classement * $valeur_ponderation_classement;
			$sum_ponderation_classement += $val['ponderation_classement'];
			//$sum_ponderation_classement += $valeur_ponderation_classement;
			
			$_sum_total_base += $val['ponderation_classement'];
			//$_sum_total_base += $valeur_ponderation_classement;
			$_sum_total_produit += $produit_base_moyenneClassement;
		}
		/******************** Section (FOND / FORME) ***********************/
		if($sum_ponderation_classement == 0)
		{
			$totalS = 0;
		}
		else
		{
			$totalS = number_format($total_section / $sum_ponderation_classement,2);
			if($totalS == -1)
			{
				$totalS = 0;
			}
			elseif (is_nan($totalS))
			{
				$totalS = 0;
			}
		}
		
		/***********SECTION************ point / base / note * Coeff *************************/
		$valeur_ponderation_section = $ponderation_section;
		if($ponderation_section == '' || $ponderation_section == 0)
		//if($ponderation_section == '')
		{
			$valeur_ponderation_section = 0;
		}
		$produit_section = $totalS * $ponderation_section;
		//echo $totalS.'*'.$ponderation_section.'<br>';
						 
		/******************************************************************************/
		if(isset($sum_general)) {
			$sum_general += $totalS * $ponderation_section;
		}else {
			$sum_general = $totalS * $ponderation_section;
		}
		
		if(isset($sum_ponderation_section)) $sum_ponderation_section += $ponderation_section;
		else $sum_ponderation_section = $ponderation_section;
	}
	
		//$totalG = number_format($sum_general / $sum_ponderation_section,2);
	$totalG = number_format($_sum_total_produit / $_sum_total_base,2);
	if (is_nan($totalG))
	{
		$totalG = '0.00';
	}
	if($test == 0)
	{
		// echo '$total_general = number_format('.$sum_general.' / '.$sum_ponderation_section.',4)'; 
		$total_general = number_format($sum_general / $sum_ponderation_section,4);
		//$total_general = number_format($_sum_total_produit / $_sum_total_base,2);
		if (is_nan($total_general))
		{
			$total_general = '0.00';
		}
	}
	// echo "xxx => ".$total_general.'||'.$nbeEliminatoire.'||'.$str_indicateur;
	return $total_general.'||'.$nbeEliminatoire.'||'.$str_indicateur;
}

function get_penalite_projet( $id_projet , $id_type_traitement)
{
    global $conn;
	$array_penalite_ = array();
    $sql = "
	select pp.id_projet_penalite,pp.id_projet,pp.flag_condition,pp.valeur,pp.penalite,pp.id_classement from cc_sr_projet p 
	inner join cc_sr_projet_penalite pp on p.id_projet = pp.id_projet
	inner join cc_sr_classement  c on c.id_classement = pp.id_classement
	where pp.id_projet = {$id_projet } 
	AND pp.id_type_traitement= {$id_type_traitement}
	ORDER BY pp.id_projet_penalite";
	$query = pg_query( $conn , $sql ) or die( pg_last_error($conn) );
	     for($k=0;$k< pg_num_rows( $query);$k++ )
		 {
		   $rows = pg_fetch_array( $query , $k); 
		   $array_penalite_[$rows['id_projet_penalite']][] = $rows['id_classement'];
		   $array_penalite_[$rows['id_projet_penalite']][] = $rows['flag_condition'];
	       $array_penalite_[$rows['id_projet_penalite']][] = $rows['valeur'];
	       $array_penalite_[$rows['id_projet_penalite']][] = $rows['penalite'];
			
		 
		 }
	return $array_penalite_;
}

function get_nombre_si($count_si,$key_section,$penalite_projet,$total_classement){
   // echo  'xx'.$total_classement.'<br>';
   foreach($penalite_projet as $id_projet_penalite=>$tab_penalite){
        if( $tab_penalite[1] ==0 && $key_section == $tab_penalite[0]){
		
		           if( $count_si == $tab_penalite[2]  && $key_section == $tab_penalite[0]){
			               $total_classement =  $total_classement-$tab_penalite[3];
			        
			      }
		  }elseif($tab_penalite[1] == 1 && $key_section == $tab_penalite[0]){
		            if( $count_si < $tab_penalite[2]){						 
			              $total_classement =  $total_classement-$tab_penalite[3];
			       }
		  }elseif($tab_penalite[1] == 2 && $key_section == $tab_penalite[0]){
		             if( $count_si > $tab_penalite[2]){
			 			$total_classement =  $total_classement-$tab_penalite[3];	        
			        }
		  }elseif($tab_penalite[1] == 3 && $key_section == $tab_penalite[0]){
		           if( $count_si <= $tab_penalite[2]){						 
			           $total_classement =  $total_classement-$tab_penalite[3];
			       }
		  }elseif($tab_penalite[1] == 4 && $key_section == $tab_penalite[0]){
		           if( $count_si >= $tab_penalite[2]){					 
			           $total_classement =  $total_classement-$tab_penalite[3];
			       }
		  }
		
      
   }
   
   return $total_classement;
}

?>