<html>
    <body>
        <table width="100%" border ='0' cellpadding='0' cellspacing='0'>
            <tr bgcolor="#ffffff" align="center" height="100px">
                <td colspan="13" align="center">
                    <table border=0 width="100%">				
                        <tr>
                            <td colspan="12" align="center"><img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
                        </tr>			
                        <tr align="center">
                            <td colspan="12"><font size="4"><b><?php echo $heading;?> from the date <?php echo $garmentsFromDate; ?> to <?php echo $garmentsToDate; ?></b></font></td>
                        </tr>
						<!--tr>
                          <th colspan="4" align="right">From Date : &nbsp;&nbsp;</th>
                          <th colspan="5" align="left">&nbsp;&nbsp;<?php echo $garmentsFromDate; ?></th>
                       </tr>
                       <tr>
                          <th colspan="4" align="right">To Date : &nbsp;&nbsp;</th>
                          <th colspan="5" align="left">&nbsp;&nbsp;<?php echo $garmentsToDate; ?></th>
                      </tr-->
                       <tr align="center">
                           <th colspan="12" align="center">Lying Days More : <?php echo $lyingMoreDays; ?></th>
                           <!--th colspan="12" align="left">&nbsp;&nbsp;<?php echo $lyingMoreDays; ?></th-->
                      </tr>
						
                        <tr align="center">
                            <td colspan="12"><font size="4"><b></b></font></td>
                        </tr>				
                    </table>		
                </td>		
            </tr>		
        </table>
        <table width="80%" border ='1' cellpadding='0' cellspacing='0' align="center" style="border-collapse: collapse">
            
  

            <thead>
                <tr align="center" style="background-color:#dbb4a0;height:40px;">
                    <th><b>Sl No</b></th>
                    <th><b>Vessel Name</b></th>		
                    <th><b>Rotation No</b></th>			
                    <th><b>Container No</b></th>
                    <th><b>Size</b></th>		
                   
                    <th><b>Lying_Days</b></th>
          
                    <th><b>Weight</b></th>		
              
                    <th><b>Location</b></th>		
                    <th><b>Terminal</b></th>		
                    
                </tr>
            </thead>
            <?php
            include("dbConection.php");
            include("dbOracleConnection.php");
			$k=1;
            for($i=0;$i<count($rslt_lyingDetail);$i++)
            {
					$cont_no=$rslt_lyingDetail[$i]['cont_number'];
                    $rot_no=$rslt_lyingDetail[$i]['Import_Rotation_No'];
                    $lyingQurey="SELECT inv_unit_fcy_visit.time_out  FROM inv_unit 
					INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON  inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
					INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
					WHERE vsl_vessel_visit_details.ib_vyg='$rot_no' AND inv_unit.id='$cont_no'";
                    
                    $rsltLyingDays = $this->bm->dataSelect($lyingQurey); 
                    
	

					
					$query = oci_parse($con_sparcsn4_oracle,$lyingQurey);
					$time_out="";
					while($row = oci_fetch_object($query)){
						 $time_out = $row->TIME_OUT;
					}					
					if( $time_out==null)
					{
				
            ?>
            <tr>
                <td align="center"><?php echo $k++; ?></td>
                <td align="center"><?php echo $rslt_lyingDetail[$i]['Vessel_Name']; ?></td>		
                <td align="center"><?php echo $rslt_lyingDetail[$i]['Import_Rotation_No']; ?></td>		
                <td align="center"><?php echo $rslt_lyingDetail[$i]['cont_number']; ?></td>	
                <td align="center"><?php echo $rslt_lyingDetail[$i]['size']; ?></td>						
                <!-- <td align="center"><?php //echo $rslt_lyingDetail[$i]['TEUs']; ?></td>	 -->
                <?php
                    /*  $cont_no=$rslt_lyingDetail[$i]['cont_number'];
                    $rot_no=$rslt_lyingDetail[$i]['Import_Rotation_No'];
                    $lyingQurey="SELECT TIMESTAMPDIFF(HOUR,inv_unit_fcy_visit.time_in,NOW()) AS hr,
                    CONCAT((SELECT FLOOR(hr/24)),' Days ',(SELECT hr%24),' Hrs') AS d
                    FROM  inv_unit  
                    INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
                    INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
                    INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
                    WHERE inv_unit.id='$cont_no' and  vsl_vessel_visit_details.ib_vyg='$rot_no'";
                    
                    $rsltLyingDays = $this->bm->dataSelect($lyingQurey); 
                    
                    $query=mysql_query($strQuery);
                    $i=0;
                    $lying_days="";
                    while($row=mysql_fetch_object($query)){
                        $i++;
                        $lying_days=$row->d;
                    } */
                ?>

                
                <td align="center"><?php echo $rslt_lyingDetail[$i]['day']; ?></td>						
                <!-- <td align="center"><?php //echo $rslt_lyingDetail[$i]['BL']; ?></td>						 -->
                <td align="center"><?php echo $rslt_lyingDetail[$i]['weight']; ?></td>						
                <!-- <td align="center"><?php //echo $rslt_lyingDetail[$i]['Commodity']; ?></td>						 -->
                <!-- <td align="center"><?php //echo $rslt_lyingDetail[$i]['Description_of_Goods']; ?></td>						 -->
                <!-- <td align="center"><?php //echo $rslt_lyingDetail[$i]['Importer']; ?></td>						 -->
                <td align="center"><?php echo $rslt_lyingDetail[$i]['location']; ?></td>						
                <td align="center"><?php echo $rslt_lyingDetail[$i]['terminal']; ?></td>						
                        
            </tr>
            <?php
					}
            }
            ?>

            
        </table>
    </body>
</html>
<script type="text/javascript">
<!--
window.print();
//-->
</script>