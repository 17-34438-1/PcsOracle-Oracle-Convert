<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Rice_Report_2019.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");
		
	include('FrontEnd/mydbPConnectionn4.php');

?>
<table border="1">
	<tr>
		<th>Year</th>
		<th>Import Rotation No</th>
		<th>Vessel Name</th>
		<th>Container No</th>
		<th>Container Size</th>		
		<th>Category</th>		
		<th>Tonage</th>
		<th>Exporter Address</th>
		<th>Description</th>		
	</tr>
<?php
	for($i=0;$i<count($rslt_rice_cont);$i++)
	{							
?>
	<tr>
		<td><?php echo $rslt_rice_cont[$i]['yr']; ?></td>		
		<td><?php echo $rslt_rice_cont[$i]['Import_Rotation_No']; ?></td>		
		<td><?php echo $rslt_rice_cont[$i]['Vessel_Name']; ?></td>		
		<td><?php echo $rslt_rice_cont[$i]['cont_number']; ?></td>		
		<td><?php echo $rslt_rice_cont[$i]['cont_size']; ?></td>		
		<td><?php echo $rslt_rice_cont[$i]['cat']; ?></td>		
		<td><?php echo $rslt_rice_cont[$i]['mton']; ?></td>		
		<td><?php echo $rslt_rice_cont[$i]['Exporter_address']; ?></td>		
		<td><?php echo $rslt_rice_cont[$i]['Description_of_Goods']; ?></td>							
	</tr>
<?php	
	}
?>
</table>