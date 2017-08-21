<?php
include("/var/www.cache/dgconn.inc");

    $sql="SELECT * FROM cc_sr_classement";

	$query = pg_query($conn,$sql) or die (pg_last_error());	
 
    $nbRow = pg_num_rows( $query );
	
	$zSelect = "<select id='slct_classement'  style='height:22px;width:171px;margin-left:5px;background:#FFF;'>";
	$zSelect .= "<option  value='0'>--Classement--</option>";
	 for($k=0;$k<$nbRow;$k++){
	   $row = pg_fetch_array($query,$k);	
       	$zSelect  .= "<option   value={$row['id_classement']}>{$row['libelle_classement']}</option>"; 
	 }
	 $zSelect .= "<select>";
	 
	 echo $zSelect;


?>