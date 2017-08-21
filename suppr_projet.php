<?php
 include("/var/www.cache/dgconn.inc");
$iIdProjet = $_REQUEST['iIdProjet'];
$sql_suppr = "DELETE FROM cc_sr_projet WHERE id_projet =$iIdProjet "; 
$query_suppr = @pg_query($conn,$sql_suppr) or die (@pg_last_error($conn));
    if($query_suppr){
      echo "ok";
    }
    else{
      echo "ko";

    }

?>