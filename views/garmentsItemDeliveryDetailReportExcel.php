<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Garments_Delivery_Detail_Report.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");		
?>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>					
	<tr align="center">
		<td colspan="11"><font size="4"><b><?php echo $heading;?> from the date <?php echo $garmentsFromDate; ?> to <?php echo $garmentsToDate; ?></b></font></td>
	</tr>				
</table>
<table border="1">
	<tr align="center">
			<th><b>Sl.</b></th>
			<th><b>Vessel Name</b></th>		
			<th><b>Rot No</b></th>
			<th><b>Container</b></th>
			
			<th><b>Size</b></th>		
			<th><b>Teus</b></th>				
			<th><b>BL No</b></th>		
			<th><b>Weight</b></th>		
			<th><b>Commodity</b></th>
			<th><b>Location</b></th>
			<th><b>DeliveryTime</b></th>
			
			<th><b>Goods Description</b></th>		
			<th><b>Importer</b></th>			
	
		</tr>
		<?php
		include("mydbPConnectionn4.php"); 
		include("dbOracleConnection.php");

		for($i=0;$i<count($rslt_deliverDetail);$i++)
		{
		?>
		<tr>
			<td align="center"><?php echo $i+1; ?></td>
			<td align="center"><?php echo $rslt_deliverDetail[$i]['Vessel_Name']; ?></td>		
			<td align="center"><?php echo $rslt_deliverDetail[$i]['Import_Rotation_No']; ?></td>		
			<td align="center"><?php echo $rslt_deliverDetail[$i]['cont_number']; ?></td>
			<td align="center"><?php echo $rslt_deliverDetail[$i]['size']; ?></td>						
			<td align="center"><?php echo $rslt_deliverDetail[$i]['TEUs']; ?></td>				
			<td align="center"><?php echo $rslt_deliverDetail[$i]['BL']; ?></td>						
			<td align="center"><?php echo $rslt_deliverDetail[$i]['weight']; ?></td>						
			<td align="center"><?php echo $rslt_deliverDetail[$i]['Commodity']; ?></td>	
			<?php
				$cont_no=$rslt_deliverDetail[$i]['cont_number'];
				$rot_no=$rslt_deliverDetail[$i]['Import_Rotation_No'];
				
	

				$lyingQurey = "            
				SELECT inv_unit_fcy_visit.time_out,
						NVL((SELECT substr(srv_event_field_changes.new_value,7)
						FROM srv_event 
						INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
						WHERE srv_event.event_type_gkey IN(18,13,16)
						AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !='' AND 
						srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey <
						(SELECT srv_event.gkey
						FROM srv_event INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
						WHERE srv_event.event_type_gkey=4 AND metafield_id='unitFlexString01'
						AND new_value IS NOT NULL ORDER BY srv_event_field_changes.gkey DESC FETCH FIRST 1 ROWS ONLY) ORDER BY srv_event.gkey 
						DESC FETCH FIRST 1 ROWS ONLY),(SELECT substr(srv_event_field_changes.new_value,7) FROM srv_event 
						INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
						WHERE  srv_event.event_type_gkey IN(18,13,16) 
						ORDER BY srv_event_field_changes.gkey DESC FETCH FIRST 1 ROWS ONLY)) AS carrentPosition
		
					FROM  inv_unit  
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
					WHERE inv_unit.id='$cont_no' AND vsl_vessel_visit_details.ib_vyg='$rot_no'";
	
				
				$query = oci_parse($con_sparcsn4_oracle,$lyingQurey);

				$delv_time="";
				$position="";

				while($row = oci_fetch_object($query)!=false){
					 $delv_time = $row->TIME_OUT;
					 $position = $row->CARRENTPOSITION;
				}	

				
		
			?>			
			<td align="center"><?php echo $position; ?></td>						
			<td align="center"><?php echo $delv_time; ?></td>						
			<td align="center"><?php echo $rslt_deliverDetail[$i]['Description_of_Goods']; ?></td>						
			<td align="center"><?php echo $rslt_deliverDetail[$i]['Description_of_Goods']; ?></td>						
			<td align="center"><?php echo $rslt_deliverDetail[$i]['Importer']; ?></td>						
					
		</tr>
	<?php		
	}
	?>
</table>