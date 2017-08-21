
    <?php
	session_start();
	
    include("/var/www.cache/dgconn.inc");
	
	$t_fichier_note = array();
	
	//http://gpao/gpao2/recordings/wengo/6258/eyeBeamRecording_130131_162128.wav
	
	//$zServer = 'bigserver';
	$zServer = '/mnt';
	
	$query_check_fichier = @pg_query( $conn, " 
		SELECT distinct f.nom_fichier FROM cc_sr_fichier f inner join cc_sr_notation n on n.id_fichier = f.id_fichier WHERE n.matricule_notation= {$_SESSION['matricule']} 
	" );
	
	for ( $i = 0 ; $i < @pg_num_rows( $query_check_fichier ) ; $i++ )
	{
		$lg_check_fichier 	= @pg_fetch_array( $query_check_fichier, $i );
		$t_fichier_note[$i]	= $lg_check_fichier['nom_fichier'];
	}
	
    $iIdImmatricule = $_REQUEST['iImmatricule'];
    
    $iIdProjet = $_REQUEST['zProjet'];  
/*************resuperer le nom du repertoire*********/
    $sql_repertoire = "SELECT nom_repertoire FROM cc_sr_projet WHERE id_projet = $iIdProjet ";
    $query_repertoire = @pg_query($conn,$sql_repertoire);
    $rows = @pg_fetch_array($query_repertoire);
    $zRepertoire = $rows['nom_repertoire'];
 
/************************/    
    $zresult .=  
"<input type='hidden' id='matriculeShow' value='$iIdImmatricule' />";    
    $zresult .=  
 "</div>";
    $zresult .=  
 "<table id='tbl_sorter' class='tablesorter' width='700px'>";
    $zresult .=  
 " <thead><tr style='text-align:center;font-weight:bold;'><th filter='false'>Fichiers</th><th>Date</th><!--<th>&nbsp;&nbsp&nbsp;&nbspStatut&nbsp;&nbsp;&nbsp;&nbsp</th>--><th>Action</th></tr></thead>";
        
    if($dossier = opendir($zServer."/".$zRepertoire."/".$iIdImmatricule."/"))
    {

      $listFile = array();
        $i = 0 ;
        $color = "#fff";
        
        while( ( false !== ($fichier = readdir($dossier))  ) && !(in_array( $fichier , $t_fichier_note )) )
        {	
                if($i%2 == 0){
                      $color = '#E6EEEE';
                }else{
                      $color = '#fff';
                }
                    
            if($fichier != '.' && $fichier != '..' && $fichier != 'exemple.php' )
            { 
                      $dDate = array();
                     /*********************Extraction de la date  du fichier***************************/ 
                     $date = explode('_',$fichier);

                     $time = substr($date[2],0,strlen($date[2])-4);

                     $yy = substr($date[1],0,2);

                     $mm = substr($date[1],2,2);

                     $dd = substr($date[1],4,2);
                   
                     array_push($dDate,'20'.$yy,$mm,$dd);
                     $dDate  = implode('-',$dDate );
                     /************************************************/ 
                     /* $selectNot = pg_query($conn,"SELECT fi.id_fichier FROM cc_sr_fichier fi INNER JOIN cc_sr_notation note 
                             ON fi.id_fichier=note.id_fichier 
                             WHERE fi.nom_fichier = '$fichier' ");*/
                    
                    $zresult .=  
 "<tr style='background-color:$color;'>";
                    /************************************************/
                    
							
                           /*  $nb =pg_num_rows($selectNot);
							 $lg_fichier = @pg_fetch_array( $selectNot );
                             if($nb ==0){*/
                              $zresult .=  
 "<td class='cellule1'><input type='hidden' value='$iIdProjet' id='id_projet$i' />".$fichier."&nbsp;&nbsp;&nbsp;&nbsp;
                                        <p class='show_player'  style='margin-right:5px;width:500px;' id='show_player$i' >
                                           <!--<audio  src='recordings/$zRepertoire/$iIdImmatricule/$fichier'  controls />-->
										   
										   <input type='hidden' value='recordings/$zRepertoire/$iIdImmatricule/$fichier' id='hiddenPlayer$i'/>
										   <audio  id='audioFile$i' src=''  controls />
                                        </p>
                                       </td>"; 
                                 $zresult .=  
 "<td style='text-align:center;'>$dDate</td>";
                                  /**$zresult .=  
 "<td>&nbsp;&nbsp;&nbsp</td>";*/

                                  $zresult .=  
 "<td class='cellule' style='text-align:center;'><a id='play_button$i' libelle='$fichier' title ='mediaplayer.swf?file=recordings/$zRepertoire/$iIdImmatricule/$fichier' href='#' onClick='afficher($i);'><img src='images/play3.jpeg' width='30px' height='30px' alt='lecteur' class='img_play' id='img_play$i' /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' id='btn_noter' title='noter' onClick='noter($i);' class='link_btn' ><img src='images/modif.jpeg' alt='noter' width='30px' height='30px' /></a></td>"; 
                                  
                            // }             
                    
                    /************************************************/
                   
                      //$zresult .=  
 "<td><embed src='mediaplayer.swf?file=bigserver/$iIdProjet/$iIdImmatricule/$fichier' width='100px' height='120px'style='margin-left:5px;border:1px solid #ccc;' name='Présentation vidéo de la balise Embed' alt='Présentation vidéo non disponible' width='250' height='220'></td>.";
                    
                    $zresult .=  
 "</tr>";      
           
                    
            } 
            $i++ ;
        } 
         
        closedir($dossier);
     

    }
    $zresult .=  
"</table>";
    $nombre = ($i-2);
    if($nombre > 0){
    $zresult .=  
 "<p><strong>Fichiers trouv&eacute;s : </strong><span style='color:red;'>".$nombre."</span></p>";   
    }else{
    $zresult .=  
 "<p  style='color:red;'><strong>R&eacute;pertoire vide</strong></p>";
    }
  $zresult .= "
			</tbody>
		</table>
		<div id='pager'>
		
		<form>
			<table>
			<tr>
			<td><img src='./images/first.png' class='first'></td>
			<td><img src='./images/prev.png' class='prev'></td>
			<td><input class='pagedisplay' type='text' readonly style='border:none; background-color: #FFF; text-align:center;font-weight: bold; width: 50px;'></td>
			<td><img src='./images/next.png' class='next'></td>
			<td><img src='./images/last.png' class='last'></td>
			<td><select class='pagesize'>
				<option selected='selected' value='10'>10</option>
				<option value='20'>20</option>
				<option value='30'>30</option>
				<option value='40'>40</option>
			</select></td>
			</tr>
			</table>
		</form>

		</div>
		
	";
 
echo $nombre."##".$zresult;     
    ?>
 