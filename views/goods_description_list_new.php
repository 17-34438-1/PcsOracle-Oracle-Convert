<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Onion_Report.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");
		
	include('mydbPConnectionn4.php');
	include("dbOracleConnection.php");

?>
<table border="1">
	<tr>
		<th>Year</th>
		<th>Vessel Name</th>
		<th>Container No</th>
		<th>Container Size</th>		
		<th>Category</th>		
		<th>Tonage</th>
		<th>Exporter Address</th>
		<th>Description</th>
		<th>Delivery Status</th>		
	</tr>
<?php
	for($i=0;$i<count($rslt_onion_cont);$i++)
	{					
		$sql_chk_yard="SELECT id,inv_unit.category,inv_unit_fcy_visit.transit_state,inv_unit_fcy_visit.time_out
		FROM inv_unit
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE id='".$rslt_onion_cont[$i]['cont_number']."' AND inv_unit.category='IMPRT' ORDER BY inv_unit.gkey DESC LIMIT 1";
		
		$rslt_chk_yard = oci_parse($con_sparcsn4_oracle, $sql_chk_yard);

		$row_chk_yard=oci_execute($rslt_chk_yard,OCI_DEFAULT);
		$row = oci_fetch_array($rslt_chk_yard, OCI_BOTH);


		// $rslt_chk_yard=mysqli_query($con_sparcsn4,$sql_chk_yard);
		
		// $row_chk_yard=mysqli_fetch_object($rslt_chk_yard);
		
		$dlv_stat=$row_chk_yard->TRANSIT_STATE;
				
		if($dlv_stat=="S40_YARD")
		{
			$dlv_stat="YARD";			
		}
		else if($dlv_stat=="S20_INBOUND")
		{
			$dlv_stat="VESSEL";
		}
		else
		{					
			$dlv_stat=$row_chk_yard->time_out;
		}
?>
	<tr>
		<td><?php echo $rslt_onion_cont[$i]['yr']; ?></td>		
		<td><?php echo $rslt_onion_cont[$i]['Vessel_Name']; ?></td>		
		<td><?php echo $rslt_onion_cont[$i]['cont_number']; ?></td>		
		<td><?php echo $rslt_onion_cont[$i]['cont_size']; ?></td>		
		<td><?php echo $rslt_onion_cont[$i]['cat']; ?></td>		
		<td><?php echo $rslt_onion_cont[$i]['mton']; ?></td>		
		<td><?php echo $rslt_onion_cont[$i]['Exporter_address']; ?></td>		
		<td><?php echo $rslt_onion_cont[$i]['Description_of_Goods']; ?></td>					
		<td><?php echo $dlv_stat; ?></td>
	</tr>
<?php	
	}
?>
</table>