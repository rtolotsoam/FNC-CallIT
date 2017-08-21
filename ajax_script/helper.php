 <?php
	
	function get_nb_ecoute( $_id_fichier )
	{
		global $conn;
		
		$query 	= @pg_query($conn,"
			SELECT count('X') as nbecoute FROM cc_sr_notation WHERE id_fichier = $_id_fichier 
		") or die (@pg_last_error($conn));
		
		$lg 	= @pg_fetch_array( $query );
		return $lg['nbecoute'];
	}
	
	function get_taux_a_valoriser( $critere, $type_traitement )
	{
		if ( $type_traitement == 3 )
		{
			switch ($critere)
			{
				case "l'accueil du client" :
				return 2;
				break;
				
				case "la conclusion du contact" :
				return "0";
				break;
				
				case "la decouverte des attentes client" :
				return 2;
				break;
				
				case "la pertinence de la réponse" :
				return 50;
				break;
				
				case "la prise de congé" :
				return 3;
				break;
				
				default :
				return 60;
				break;
			}

		}else{
			switch ($critere)
			{
				case "l'accueil du client" :
				return 8;
				break;
				
				case "la conclusion du contact" :
				return 12;
				break;
				
				case "la decouverte des attentes client" :
				return 16;
				break;
				
				case "la pertinence de la réponse" :
				return 16;
				break;
				
				case "la prise de congé" :
				return 8;
				break;
				
				default :
				return 20;
				break;
			}
		}
	}
	function get_taux($date_deb, $date_fin, $teleconseiller, $type_traitement, $critere, $auditeur, $projet, $client, $application )
	{
		global $conn;
		
		$iTauxValorise = get_taux_a_valoriser( $critere, $type_traitement );
		
		$sql 	= "
			SELECT
				sum(csin.note) as pttotal FROM cc_sr_notation n
			INNER JOIN cc_sr_indicateur_notation csin on csin.id_notation = n.id_notation
			INNER JOIN cc_sr_grille g on g.id_grille = csin.id_grille
			INNER JOIN cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
			INNER JOIN cc_sr_type_traitement cstt on cstt.id_type_traitement = cg.id_type_traitement
			INNER JOIN personnel p on p.matricule = n.matricule
			INNER JOIN personnel p2 on p2.matricule = n.matricule_notation
			INNER JOIN cc_sr_fichier f on f.id_fichier = n.id_fichier
			INNER JOIN cc_sr_projet pr on pr.id_projet = n.id_projet
			INNER JOIN gu_client cli on cli.id_client = pr.id_client
			INNER JOIN gu_application app on app.id_application = pr.id_application
				WHERE n.id_notation IS NOT NULL
		";
		
		if ( $date_deb != '' && $date_fin != '' )
			$sql .= "
				AND n.date_notation between '$date_deb' and '$date_fin'
			";
			
		
		if ( $teleconseiller != '' )
		{
			$sql .= "
				AND n.matricule in ( $teleconseiller )
			";
		}
		
		if ( $type_traitement != '' )
			$sql .= "
				AND	cstt.id_type_traitement = $type_traitement
			";
		if ( $auditeur != '' )
		{
			$sql .= "
				AND p2.matricule in ( $auditeur )
			";
		}
		
		if ( $projet != '' )
		{
			$sql .= "
				AND pr.id_projet in ( $projet )
			";
		}
		
		if ( $client != '' )
		{
			$sql .= "
				AND cli.id_client in ( $client )
			";
		}
		
		if ( $application != '' )
		{
			$sql .= "
				AND app.id_application in ( $application )
			";
		}
		
		if ( $critere != '' )		
			$critere = pg_escape_string( $critere );
            
			$sql .= "
				AND	cg.libelle_categorie_grille = '$critere'
			";
		//echo $sql."<br />";
		$query 	= @pg_query( $conn, $sql ) or die(@pg_last_error($conn)) ;
		$iCountGood = 0 ;
		for ( $j = 0; $j < @pg_num_rows( $query ) ; $j++ )
		{
			$lg		= @pg_fetch_array( $query );
			//echo "detail=".$lg['pttotal']."<br />";
			if ( $lg['pttotal'] >= $iTauxValorise )
				$iCountGood++;
		}
		//echo "Good=".$iCountGood;
		
		//$nb_valorise = $lg['nb_taux_valorise'];
		
		$sql1 	= "
			SELECT
				sum(csin.note) as pttotal FROM cc_sr_notation n
			INNER JOIN cc_sr_indicateur_notation csin on csin.id_notation = n.id_notation
			INNER JOIN cc_sr_grille g on g.id_grille = csin.id_grille
			INNER JOIN cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
			INNER JOIN cc_sr_type_traitement cstt on cstt.id_type_traitement = cg.id_type_traitement
			INNER JOIN personnel p on p.matricule = n.matricule
			INNER JOIN personnel p2 on p2.matricule = n.matricule_notation
			INNER JOIN cc_sr_fichier f on f.id_fichier = n.id_fichier
			INNER JOIN cc_sr_projet pr on pr.id_projet = n.id_projet
			INNER JOIN gu_client cli on cli.id_client = pr.id_client
			INNER JOIN gu_application app on app.id_application = pr.id_application
				WHERE n.id_notation IS NOT NULL
			AND 
				csin.note >= $iTauxValorise
		";
		
		if ( $date_deb != '' && $date_fin != '' )
			$sql1 .= "
				AND n.date_notation between '$date_deb' and '$date_fin'
			";
			
		
		if ( $teleconseiller != '' )
		{
			$sql1 .= "
				AND n.matricule in ( $teleconseiller )
			";
		}
		
		if ( $auditeur != '' )
		{
			$sql1 .= "
				AND p2.matricule in ( $auditeur )
			";
		}
		
		if ( $projet != '' )
		{
			$sql1 .= "
				AND pr.id_projet in ( $projet )
			";
		}
		
		if ( $client != '' )
		{
			$sql1 .= "
				AND cli.id_client in ( $client )
			";
		}
		
		if ( $application != '' )
		{
			$sql1 .= "
				AND app.id_application in ( $application )
			";
		}
		
		if ( $type_traitement != '' )
			$sql1 .= "
				AND	cstt.id_type_traitement = $type_traitement
			";
		
		if ( $critere != '' )	
			$critere = pg_escape_string( $critere );
			$sql1 .= "
				AND	cg.libelle_categorie_grille = '$critere'
			";
		//echo $sql1."<br />";
		$query1 	= @pg_query( $conn, $sql1 )  ;
		
		for ( $k = 0; $k < @pg_num_rows( $query1 ) ; $k++ )
		{
			$lg1		= @pg_fetch_array( $query1 );
		}
		//echo "total=".$k;
		//$nb_total = $lg['nb_total'];
		
		return ($k == 0) ? 0 : round ( $iCountGood * 100 / $k ,2 );
		
		
	}
	
	function get_barreme( $critere, $type_traitement )
	{
		if ( $type_traitement == 3 )
		{
			switch ($critere)
			{
				case "l'accueil du client" :
				return "3";
				break;
				
				case "la conclusion du contact" :
				return "0";
				break;
				
				case "la decouverte des attentes client" :
				return "3";
				break;
				
				case "la pertinence de la réponse" :
				return "60";
				break;
				
				case "la prise de congé" :
				return "4";
				break;
				
				default :
				return "10";
				break;
			}

		}else{
			switch ($critere)
			{
				case "l'accueil du client" :
				return "10";
				break;
				
				case "la conclusion du contact" :
				return "15";
				break;
				
				case "la decouverte des attentes client" :
				return "20";
				break;
				
				case "la pertinence de la réponse" :
				return "20";
				break;
				
				case "la prise de congé" :
				return "20";
				break;
				
				default :
				return "10";
				break;
			}
		}
		
	}
	
	function get_nb_situation_innaceptable ( $date_deb, $date_fin, $teleconseiller, $type_traitement, $critere, $auditeur, $projet, $client, $application )
	{
		global $conn;
		
		$sql 	= "
			SELECT
				count('X') as nb_situation FROM cc_sr_notation n
			INNER JOIN cc_sr_indicateur_notation csin on csin.id_notation = n.id_notation
			INNER JOIN cc_sr_grille g on g.id_grille = csin.id_grille
			INNER JOIN cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
			INNER JOIN cc_sr_type_traitement cstt on cstt.id_type_traitement = cg.id_type_traitement
			INNER JOIN personnel p on p.matricule = n.matricule
			INNER JOIN personnel p2 on p2.matricule = n.matricule_notation
			INNER JOIN cc_sr_fichier f on f.id_fichier = n.id_fichier
			INNER JOIN cc_sr_projet pr on pr.id_projet = n.id_projet
			INNER JOIN gu_client cli on cli.id_client = pr.id_client
			INNER JOIN gu_application app on app.id_application = pr.id_application
				WHERE n.id_notation IS NOT NULL
				AND csin.note=0
		";
		
		if ( $date_deb != '' && $date_fin != '' )
			$sql .= "
				AND n.date_notation between '$date_deb' and '$date_fin'
			";
			
		
		if ( $teleconseiller != '' )
		{
			$sql .= "
				AND csn.matricule in ( $teleconseiller )
			";
		}
		
		if ( $auditeur != '' )
		{
			$sql .= "
				AND p2.matricule in ( $auditeur )
			";
		}
		
		if ( $projet != '' )
		{
			$sql .= "
				AND pr.id_projet in ( $projet )
			";
		}
		
		if ( $client != '' )
		{
			$sql .= "
				AND cli.id_client in ( $client )
			";
		}
		
		if ( $application != '' )
		{
			$sql .= "
				AND app.id_application in ( $application )
			";
		}
		
		if ( $type_traitement != '' )
			$sql .= "
				AND	cstt.id_type_traitement = $type_traitement
			";
		
		if ( $critere != '' )		
			$sql .= "
				AND	cg.libelle_categorie_grille = '$critere'
			";
		//echo $sql."<br />";
		$query 	= @pg_query( $conn, $sql )  ;
		
		$lg		= @pg_fetch_array( $query );
		
		return round ( $lg['nb_situation'], 2 ) ;
		
	}
	
	function get_moyenne( $date_deb, $date_fin, $teleconseiller, $type_traitement, $critere, $auditeur, $projet, $client, $application )
	{
		global $conn;
		
		$sql 	= "
			SELECT
				avg(csin.note) as moyenne FROM cc_sr_notation n
			INNER JOIN cc_sr_indicateur_notation csin on csin.id_notation = n.id_notation
			INNER JOIN cc_sr_grille g on g.id_grille = csin.id_grille
			INNER JOIN cc_sr_categorie_grille cg on cg.id_categorie_grille = g.id_categorie_grille
			INNER JOIN cc_sr_type_traitement cstt on cstt.id_type_traitement = cg.id_type_traitement
			INNER JOIN personnel p on p.matricule = n.matricule
			INNER JOIN personnel p2 on p2.matricule = n.matricule_notation
			INNER JOIN cc_sr_fichier f on f.id_fichier = n.id_fichier
			INNER JOIN cc_sr_projet pr on pr.id_projet = n.id_projet
			INNER JOIN gu_client cli on cli.id_client = pr.id_client
			INNER JOIN gu_application app on app.id_application = pr.id_application
				WHERE n.id_notation IS NOT NULL
		";
		
		if ( $date_deb != '' && $date_fin != '' )
			$sql .= "
				AND n.date_notation between '$date_deb' and '$date_fin'
			";
			
		
		if ( $teleconseiller != '' )
		{
			$sql .= "
				AND p.matricule in ( $teleconseiller )
			";
		}
		
		if ( $auditeur != '' )
		{
			$sql .= "
				AND p2.matricule in ( $auditeur )
			";
		}
		
		if ( $projet != '' )
		{
			$sql .= "
				AND pr.id_projet in ( $projet )
			";
		}
		
		if ( $client != '' )
		{
			$sql .= "
				AND cli.id_client in ( $client )
			";
		}
		
		if ( $application != '' )
		{
			$sql .= "
				AND app.id_application in ( $application )
			";
		}
		
		if ( $type_traitement != '' )
			$sql .= "
				AND	cstt.id_type_traitement = $type_traitement
			";
		
		if ( $critere != '' )		
			$sql .= "
				AND	cg.libelle_categorie_grille = '$critere'
			";
		//echo $sql."<br />";
		$query 	= @pg_query( $conn, $sql )  ;
		
		$lg		= @pg_fetch_array( $query );
		
		return round ( $lg['moyenne'], 2 ) ;
		
	}
	
	function get_nb_note ( $date_deb, $date_fin, $teleconseiller, $type_traitement, $critere )
	{
		global $conn;
		
		$sql 	= "
			SELECT
				count('X') as nb_note
			FROM
				cc_sr_indicateur_notation crin
			INNER JOIN 
				cc_sr_notation csn ON csn.id_notation = crin.id_notation
			INNER JOIN 
				cc_sr_indicateur csi ON csi.id_indicateur = crin.id_indicateur
			INNER JOIN
				cc_sr_grille csg ON csg.id_grille = csi.id_grille
			INNER JOIN
				cc_sr_categorie_grille cscg ON cscg.id_categorie_grille = csg.id_categorie_grille
			INNER JOIN
				cc_sr_type_traitement cstt ON cstt.id_type_traitement = cscg.id_type_traitement
			WHERE 
				csn.id_notation is not null
		";
		
		if ( $date_deb != '' && $date_fin != '' )
			$sql .= "
				AND csn.date_notation between '$date_deb' and '$date_fin'
			";
			
		$zTelecon	= "" ;
		if ( isset( $teleconseiller )  )
		{
			for ( $i = 0 ; $i < count( $teleconseiller ) ; $i++ )
			{
				if ( $i == count( $teleconseiller ) - 1  )
					$zTelecon .= $teleconseiller[$i] ;
				else
					$zTelecon .= $teleconseiller[$i] .",";
			}
			
			$sql .= "
				AND csn.matricule in ( $zTelecon )
			";
		}
		
		if ( $type_traitement != '' )
			$sql .= "
				AND	cstt.id_type_traitement = type_traitement
			";
		
		if ( $critere != '' )		
			$sql .= "
				AND	cscg.libelle_categorie_grille = '$critere'
			";
		
		$query 	= @pg_query( $conn, $sql );
		
		$lg		= @pg_fetch_array( $query );
		
		return $lg['nb_note'] ;
	}
	
	function get_nb_situation_acceptable( $date_deb, $date_fin, $teleconseiller, $type_traitement, $critere )
	{
		global $conn;
	}
	
	function get_point_by_indicateur( $id_indicateur )
	{
		global $conn;
		
		$query 	= @pg_query($conn,"
			SELECT point FROM cc_sr_indicateur WHERE id_indicateur = $id_indicateur
		");
		
		$lg 	= @pg_fetch_array( $query );
		return $lg['point'];
		
	}
	
?>