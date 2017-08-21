<?php
	include("/var/www.cache/dgconn.inc");
	
	if( isset($_REQUEST['test_recap']) ){
		// $date_deb = $_REQUEST['date_'].'-'.'01';
		// $date_fin = finDuMois( $date_deb );
		
		$date_deb = $_REQUEST['date_'];
		$date_fin = $_REQUEST['date_1'];
		
		echo getvaleur($date_deb,$date_fin);
	}
  
	function getvaleur($date_deb,$date_fin){
		global $conn;
		$sql = " 
			select prenompersonnel,
				matricule_notation,
				count(id_notation) nombre,
				code,
				nom_client 
			from (
				select distinct n.id_notation,
					n.matricule_notation,
					p.prenompersonnel,
					ga.id_projet,
					ga.id_client,
					ga.id_application,
					gua.code,
					guc.nom_client
				from cc_sr_notation n
					inner join personnel p on p.matricule = n.matricule_notation
					inner join cc_sr_indicateur_notation inot on n.id_notation = inot.id_notation
					inner join cc_sr_grille_application ga on ga.id_grille_application = inot.id_grille_application
					inner join gu_client guc on guc.id_client = ga.id_client
					inner join gu_application gua on gua.id_application = ga.id_application
				/*where date_entretien >= '".$date_deb."' and date_entretien <= '".$date_fin."'*/
				where date_notation between '".$date_deb."' and '".$date_fin."'
				order by matricule_notation 
			) as req1
			group by prenompersonnel,matricule_notation,code,nom_client
			/**order by matricule_notation*/
			order by prenompersonnel,code ";
			
		$query  = pg_query($conn,$sql) or die(pg_last_error($conn));
		$nb_ = pg_num_rows( $query );
		$zHtml = '<div id="id_div_filtre_date"><span>Date d\'appel du : </span>';
		$zHtml .= '<input type="text" id="id_date_appel_deb" /> au ';
		$zHtml .= '<input type="text" id="id_date_appel_fin" />';
		$zHtml .= '<input type="button" id="id_btn_apercu" onclick="apercu();" />';
		$zHtml .= '</div>';
		$str = "
			 <script type='text/javascript'>
								$('#tab_liste').fixheadertable({
				
									 height  : 350
								 });
			 </script>";
		if( $nb_ == 0 ){
			$str .='<p style="text-align:center;color:red;font-size:14px;">Aucune &eacute;valuation pour cette p&eacute;riode</p>';	
		} else{		 
			$str .= '<table id="tab_liste" class="class_table">
	
				<thead class="fixedHeader">';
			$str .= '<tr  style="height:26px;">
				<th style="background:#000;color:#FFF;font-weight:bold;font-size:11px;">Matricule Evaluateur</th>
				<th  style="background:#000;color:#FFF;font-weight:bold;font-size:11px;width:200px;">Pr&eacute;nom Evaluateur</th>
		
				<th style="background:#000;color:#FFF;font-weight:bold;font-size:11px;">Nombre d\'&eacute;valuation</th>
				<th style="background:#000;color:#FFF;font-weight:bold;font-size:11px;">Prestation</th>
				<th style="background:#000;color:#FFF;font-weight:bold;font-size:11px;">Client</th>
			</tr>
			</thead>';
			$i=0;
			//$color='#F8F8F8';
			$total_eval          = 0 ;
			$nom_precedent       = '';
			$matricule_precedent = '';
			$rwspan              = 0;
			
			while($res = pg_fetch_array($query)){
				// if($i%2==0)  $color='#C2DBE0';
				// else $color='#F8F8F8';
				$total_eval += (float) $res['nombre'];
				$str .= '<tr>';
		 
				if( $res['matricule_notation'] == $matricule_precedent){//$color='#F8F8F8';
					$str .= '<td align="center" style="background:#EAF2DD;border:none;color:#838280; ">--</td>';;
				}else{
					$str .= '<td align="center" style="background:#EAF2DD;border:none; ">'.$res['matricule_notation'].'</td>';
				}
				if( $res['prenompersonnel'] == $nom_precedent){//$color='#F8F8F8';
					$str .= '<td align="center" style="background:#F2DDDC;border:none;color:#838280;width:180px; ">--</td>';
				}else{//$color='#C2DBE0';
					$str .= '<td style="background:#F2DDDC;border:none;width:180px; ">'.( $res['prenompersonnel'] ).'</td>';
				}

				$str .= '<td  align="center"  style="background:#E6E0EC;border:none; ">'.$res['nombre'].'</td>
				<td  align="center"  style="background:#DBEEF4;border:none; ">'.$res['code'].'</td>
				<td  align="center" style="background:#FCE9DA ;border:none;">'.$res['nom_client'].'</td>
				</tr>';
				$nom_precedent = $res['prenompersonnel'];
				$matricule_precedent = $res['matricule_notation'];
				$i++;
			}
			$str .= '</table>';
			$str .= "			 
			      <table border=1 class='class_table'  width='100%' height='30px;' >
			         <thead class='fixedHeader' width='100%'>
				       <tr style='background:#000' class='alternateRow'>
					     <th width='50%' colspan='5'><span style='color:#FFF;font-size:12px;'>Total &eacute;valuation:</span><span style='color:red;font-size:12px;margin-left:5px;'>".$total_eval."</span></th>
					     
					   
					</tr>
			     </thead>
				</table>
			   ";
		}			   
		return $str;
	}
  
	function finDuMois ($date){  
		$date_f = $date;
		list($year,$month,$day) = explode('-', $date);
		$date_f = $year."-".$month."-31";
		if (checkdate($month,"31",$year) == false) {
			$date_f = $year."-".$month."-30";
			if (checkdate($month,"30",$year) == false) {
				$date_f = $year."-".$month."-29";
				if (checkdate($month,"29",$year) == false) {
					$date_f = $year."-".$month."-28";
				}
			}
		}
		return $date_f ;
	} 
  
?>