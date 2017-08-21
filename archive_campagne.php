<?php
     session_start();
	 include("/var/www.cache/dgconn.inc");
$sql_projet = "  SELECT p.id_projet,p.nom_projet,p.nom_repertoire,c.nom_client,c.id_client,ap.nom_application,ap.code,p.date_modification::date,ap.id_application  FROM gu_application ap
                 INNER JOIN  cc_sr_projet p  ON ap.id_application = p.id_application 
                 INNER JOIN gu_client c ON c.id_client = p.id_client  
				 WHERE p.archivage =0  ORDER  BY p.date_modification DESC,p.nom_projet ASC" ;
$query_projet = @pg_query($conn,$sql_projet) or die (@pg_last_error($conn));
$zresultHtml2 ="";
$zresultHtml2 .= "
<script type='text/javascript'>
$('#list_archive').tablesorter();
</script>
<table id='list_archive' class='tablesorter'>";
$zresultHtml2 .= "<thead>
<tr>
<th>Projet</th>
<th>Client</th>
<th>Prestation</th>
<th>Application</th>
<th>Date de suppression</th>
<th>Action</th>
</tr></thead>";
$i = 0;     
     $nbLigne = pg_num_rows( $query_projet );
	 if($nbLigne>0){
	   while($rows_projet = @pg_fetch_array($query_projet))
			{
			       $date_modif = date_create($rows_projet['date_modification']);
			       $date_modif = date_format($date_modif, "d/m/Y");
                   $zRepertoire = substr($rows_projet['nom_repertoire'],0,strlen($rows_projet['nom_repertoire'])-5);
                   $zresultHtml2 .= "<tr align='center'>
                    <td>{$rows_projet['nom_projet']}</td>
                    <td>{$rows_projet['nom_client']}</td>
                    <td>{$rows_projet['code']}</td>
                    <td>{$rows_projet['nom_application']}</td>
                    <td>{$date_modif}</td>
                    <td width='72px'>
					<input type='hidden' class='id_projet_' id='id_projet_$i'  name='id_projet_$i' value='{$rows_projet['id_projet']}' />		
					 <a href='#' id='btn_archive' title='Restaurer' onClick ='restaurer_campagne({$rows_projet['id_client']},{$rows_projet['id_application']},{$i});' ><img src='images/img_restaurer.jpg' width='20px' height='20px'  alt=''supprimer /></a>					
					 </td>
                </tr>
              ";
                 $i++;                            
            }
	 }
        else{
		 $zresultHtml2 .= "
		             <tr>
                        <td  align='center' colspan='6'><font color='red'>Archive  vide</font></td>					 
					 </tr>
		 ";
		
		}
$zresultHtml2 .= "</table>";
echo $zresultHtml2;

?>