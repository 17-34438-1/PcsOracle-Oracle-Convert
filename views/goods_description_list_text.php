<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Goods_Report_".ucfirst($searchText).".xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");
		
	include('mydbPConnectionn4.php');
	include("dbOracleConnection.php");
?>
<table border="1">
	<tr>
		<th>Sl</th>
		<th>Import Rotation No</th>
		<th>Vessel Name</th>
		<th>Container No</th>
		<th>Container Size</th>		
		<th>Container Height</th>		
		<th>Teus</th>		
		<th>Commodity Description</th>		
		<th>Category</th>		
		<th>Tonage</th>
		<th>Importer Name</th>
		<th>Importer Address</th>
		<th>Exporter Name</th>
		<th>Exporter Address</th>
		<th>Description</th>
		<!--th>Delivery Status</th-->		
	</tr>
<?php
	$yardCond = "";
	
	if($options=="YES")
	{
		$yardCond = " AND inv_unit_fcy_visit.transit_state='S40_YARD' ";
	}
	
	$sl = 0;
	$total_teus = 0;
	
	for($i=0;$i<count($goodsResult);$i++)
	{					
		$cont_no = $goodsResult[$i]['cont_number'];
		
		$sql_chk_yard="SELECT id,inv_unit.category,inv_unit_fcy_visit.transit_state,inv_unit_fcy_visit.time_out
		FROM inv_unit
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE id='".$cont_no."' AND inv_unit.category='IMPRT'".$yardCond." 
		ORDER BY inv_unit.gkey DESC LIMIT 1";
		
		$rslt_chk_yard=oci_parse($con_sparcsn4_oracle,$sql_chk_yard);
		$row=oci_execute($result);
		$results=array();
		$nrows = oci_fetch_all($rslt_chk_yard, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		oci_free_statement($strQuery2Res);
		$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
		//$rslt_chk_yard=mysqli_query($con_sparcsn4,$sql_chk_yard);
		
		if($nrows>0)
		{
			$sl++;
			$total_teus = $total_teus + $goodsResult[$i]['teus'];
			
			// while($row_chk_yard=mysqli_fetch_object($rslt_chk_yard))
			while(($row_chk_yard = oci_fetch_object($total_teus)) != false)
			{
				$category = $row_chk_yard->CATEGORY;
			}
		
		// $dlv_stat=$row_chk_yard->transit_state;
				
		// if($dlv_stat=="S40_YARD")
		// {
			// $dlv_stat="YARD";			
		// }
		// else if($dlv_stat=="S20_INBOUND")
		// {
			// $dlv_stat="VESSEL";
		// }
		// else
		// {					
			// $dlv_stat=$row_chk_yard->time_out;
		// }
?>
	<tr>
		<td><?php echo $sl; ?></td>		
		<td><?php echo $goodsResult[$i]['Import_Rotation_No']; ?></td>		
		<td><?php echo $goodsResult[$i]['Vessel_Name']; ?></td>		
		<td><?php echo $goodsResult[$i]['cont_number']; ?></td>		
		<td><?php echo $goodsResult[$i]['cont_size']; ?></td>		
		<td><?php echo $goodsResult[$i]['cont_height']; ?></td>		
		<td><?php echo $goodsResult[$i]['teus']; ?></td>		
		<td><?php echo $goodsResult[$i]['commudity_desc']; ?></td>		
		<td><?php echo $category; ?></td>		
		<td><?php echo $goodsResult[$i]['tonage']; ?></td>		
		<td><?php echo $goodsResult[$i]['Notify_name']; ?></td>		
		<td><?php echo $goodsResult[$i]['NotifyDesc']; ?></td>		
		<td><?php echo $goodsResult[$i]['Exporter_name']; ?></td>		
		<td><?php echo $goodsResult[$i]['Exporter_address']; ?></td>		
		<td><?php echo $goodsResult[$i]['Description_of_Goods']; ?></td>					
	</tr>
<?php	
		}
	}
?>
</table>
<br>
<br>
<br>
<table border="1">
	<tr>
		<td>Total Box</td>
		<td><?php echo $sl; ?></td>
	</tr>
	<tr>
		<td>Total Teus</td>
		<td><?php echo $total_teus; ?></td>
	</tr>
</table>