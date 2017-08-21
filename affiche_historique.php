<?php
     session_start();
	 include("/var/www.cache/dgconn.inc");
	 include("function_grille_.php");
	 $id_projet = $_REQUEST['id_projet'];
	 $nom_projet = $_REQUEST['nom_projet'];
	 $nom_client = $_REQUEST['nom_client'];
	 $nom_application = $_REQUEST['nom_application'];
     $sql_historique = "SELECT h.id_historique,p.nom_projet,c.nom_client,ap.nom_application,ap.code,h.date_modification::date,h.matricule_modification,h.flag FROM gu_application ap

                 INNER JOIN  cc_sr_projet p  ON ap.id_application = p.id_application 
                 INNER JOIN gu_client c ON c.id_client = p.id_client
                 INNER JOIN cc_sr_historique h on h.id_projet=p.id_projet
				 WHERE p.id_projet=$id_projet
				 ORDER  BY h.date_modification DESC";
				
	$query_historique = pg_query($conn,$sql_historique) or die (@pg_last_error($conn));
	$zTitle = pg_fetch_row( $query_historique );
	
	$zresultHtml ="<table border='0'>
		    <tr>
		     <th style='width:30%'>Projet:</th>
		     <td>$zTitle[1]</td>
		    </tr>
		    <tr>
		     <th style='width:30%'>Client:</th>
		     <td>$zTitle[2]</td>
		    </tr>
		    <tr>
		     <th style='width:30%'>Prestation:</th>
		     <td>$zTitle[3]</</td>
		    </tr>
		</table>";
	$zresultHtml .= "
<script type='text/javascript'>
$('#list_historique').tablesorter();
</script>
<table id='list_historique' class='tablesorter'>";
$zresultHtml .= "<thead>
<tr align='center'>
<th  width='140px;'>Date de modification</th>
<th>Modifi&eacute; par</th>
<th>Flag</th>
<th>Action</th>

</tr></thead><tbody>";

     $i = 0;     
     $nbLigne = pg_num_rows( $query_historique );
	 $action = '';
	 if($nbLigne>0){
	    	   while($rows_historique = pg_fetch_array($query_historique))
			{
			   $infoPresonnel = get_nom_personnel( $rows_historique['matricule_modification'] );
			       if( $rows_historique['flag'] == 1)  $action='Restauration';
				   else               $action='Archivage';
				   
			       $date_modif = date_create($rows_historique['date_modification']);
			       $date_modif = date_format($date_modif, "d/m/Y");
                   $zRepertoire = substr($rows_historique['nom_repertoire'],0,strlen($rows_historique['nom_repertoire'])-5);
                   $zresultHtml .= "<tr id='row_$i' align='center'>
                    <td>{$date_modif}</td>               
                    <td>{$infoPresonnel}</td>               
                    <td>{$action}</td>               
                    <td><a title='Supprimer' href='#' id='suppr_historique' onclick='supprimer_historique({$rows_historique['id_historique']},{$i});'><img src='images/suppr.jpeg' width='25px' height='25px' /></a></td>               
                </tr>
              ";
                 $i++;                            
            }
	} else{
		 $zresultHtml .= "
		             <tr>
                        <td  align='center' colspan='8'><font color='red'>Historique  vide</font></td>					 
					 </tr>
		 ";
		
		}
$zresultHtml .= "
          <tbody>
		</table>";
		
		echo $zresultHtml;
?>