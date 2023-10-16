<table align="center" border="1">
	<tr>
		<td align="center" colspan="5"><h2>Vessel Batch List</h2></td>
	</tr>
	<tr>
		<th>Sl</th>
		<th>Rotation No</th>
		<th>Vessel Name</th>
		<th>Vessel Class</th>
		<th>Vessel Type</th>
	</tr>
	<?php
	for($i=0;$i<count($rslt_marineVslLot_N4_List);$i++)
	{
	?>
	<tr>
		<td><?php echo $i+1; ?></td>
		<td><?php echo $rslt_marineVslLot_N4_List[$i]['impRot']; ?></td>
		<td><?php echo $rslt_marineVslLot_N4_List[$i]['vsl_name']; ?></td>
		<td><?php echo $rslt_marineVslLot_N4_List[$i]['vsl_class']; ?></td>
		<td><?php echo $rslt_marineVslLot_N4_List[$i]['vsl_type']; ?></td>		
	</tr>		
	<?php
	}
	?>
</table>