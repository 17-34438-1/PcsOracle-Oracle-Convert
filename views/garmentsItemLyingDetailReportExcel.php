<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Garments_Lying_Detail_Report.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");		
?>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>					
	<tr align="center">
		<td colspan="13"><font size="4"><b><?php echo $heading;?> from the date <?php echo $garmentsFromDate; ?> to <?php echo $garmentsToDate; ?></b></font></td>
	</tr>				
</table>
<table border="1">
	<tr align="center">
		<tr align="center">
			<th><b>Sl.</b></th>
			<th><b>Vessel Name</b></th>		
			<th><b>Rot No</b></th>			
			<th><b>Container</b></th>
			<th><b>Size</b></th>		
			<th><b>Teus</b></th>
			<th><b>Lying_Days</b></th>
			<th><b>BL No</b></th>		
			<th><b>Weight</b></th>		
			<th><b>Commodity</b></th>		
			<th><b>Goods Description</b></th>		
			<th><b>Importer</b></th>		
			<th><b>Location</b></th>		
			<th><b>Terminal</b></th>	
	</tr>
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
			<td align="center"><?php echo $rslt_lyingDetail[$i]['TEUs']; ?></td>	
			<td align="center"><?php echo $rslt_lyingDetail[$i]['d']; ?></td>						
			<td align="center"><?php echo $rslt_lyingDetail[$i]['BL']; ?></td>						
			<td align="center"><?php echo $rslt_lyingDetail[$i]['weight']; ?></td>						
			<td align="center"><?php echo $rslt_lyingDetail[$i]['Commodity']; ?></td>						
			<td align="center"><?php echo $rslt_lyingDetail[$i]['Description_of_Goods']; ?></td>						
			<td align="center"><?php echo $rslt_lyingDetail[$i]['Importer']; ?></td>						
			<td align="center"><?php echo $rslt_lyingDetail[$i]['location']; ?></td>						
			<td align="center"><?php echo $rslt_lyingDetail[$i]['terminal']; ?></td>				
	</tr>
	<?php
					}	
	}
	?>
</table>