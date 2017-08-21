<?php
  include("/var/www.cache/dgconn.inc");
 $zClient = $_REQUEST['zClient'];
 

$zApplication =  "<option value=''>------selectionner------</option>";
$zCampagne =  "<option value=''>------selectionner------</option>";
 $sql_application = "SELECT * FROM  gu_application WHERE id_client = ' $zClient'";
 $query = pg_query($conn,$sql_application);
 $array_application = array();
 $sql_application_projet = " SELECT DISTINCT id_application FROM cc_sr_projet WHERE flag_duplication is null  AND archivage=1";
 $query_application = pg_query($conn,$sql_application_projet) or die(pg_last_error());
               for($i=0;$i<pg_num_rows( $query_application );$i++){
			        $lg = pg_fetch_array( $query_application ,$i);
					array_push( $array_application,$lg['id_application']);
			   }
 while($rows = pg_fetch_array($query)){
 
            if(isset($_REQUEST['test_duplication']))
			{
                    if(in_array($rows['id_application'],$array_application) && isset($_REQUEST['test_duplication']))
					{
	      $zApplication .= "<option value='{$rows['id_application']}'>{$rows['code']}-{$rows['nom_application']}</option>";
	                }
			}else{
			          $zApplication .= "<option value='{$rows['id_application']}'>{$rows['code']}-{$rows['nom_application']}</option>";
			}
    

        
 }
 
  
   $sql_campagne = "SELECT id_campagne,nom_campagne FROM  cc_sr_campagne WHERE id_client =$zClient
    ORDER BY nom_campagne";
  
 
         $queryCampagne = pg_query($conn,$sql_campagne);
	     $nb = pg_num_rows( $queryCampagne );
		 if( $nb>0 ){
		 
		      while($rowsCamp = pg_fetch_array($queryCampagne))
			  {
                $zCampagne .= "<option value='{$rowsCamp['id_campagne']}'>{$rowsCamp['nom_campagne']}</option>";
        
              }
		 }else{
               $sql_campagne = "SELECT id_campagne,nom_campagne FROM  cc_sr_campagne ORDER BY nom_campagne";
		       $queryCampagne = pg_query($conn,$sql_campagne);
			   
			   while($rowsCamp = pg_fetch_array($queryCampagne))
			   {
                $zCampagne .= "<option value='{$rowsCamp['id_campagne']}'>{$rowsCamp['nom_campagne']}</option>";
        
               }
		 }
    
		  
		   echo $zApplication.'#'.$zCampagne ;
?>