<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Garments_Delivery_Summary_Report.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");		
?>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>					
	<tr align="center">
		<td colspan="5"><font size="4"><b><?php echo $heading;?> from the date <?php echo $garmentsFromDate; ?> to <?php echo $garmentsToDate; ?></b></font></td>
	</tr>				
</table>
<table border="1">
	<tr align="center">
		<th><b>Sl.</b></th>
		<th><b>Vessel Name</b></th>		
		<th><b>Rot No</b></th>			
		<th><b>Total Box</b></th>
		<th><b>Total Teus</b></th>		
	</tr>
<?php
	$grossTotalBox=0;
	$grossTotalTeus=0;
	for($i=0;$i<count($rslt_deliverySummary);$i++)
	{
	?>
	<tr>
		<td align="center"><?php echo $i+1; ?></td>
		<td align="center"><?php echo $rslt_deliverySummary[$i]['Vessel_Name']; ?></td>		
		<td align="center"><?php echo $rslt_deliverySummary[$i]['Import_Rotation_No']; ?></td>		
		<td align="center"><?php echo $rslt_deliverySummary[$i]['Box']; ?></td>		
		<td align="center"><?php echo $rslt_deliverySummary[$i]['TEUs']; ?></td>						
	</tr>
	<?php
		$grossTotalBox=$grossTotalBox+$rslt_deliverySummary[$i]['Box'];
		$grossTotalTeus=$grossTotalTeus+$rslt_deliverySummary[$i]['TEUs'];
	}
	?>
	<tr align="center">
		<td colspan=3 align="center"><b>Total</b></td>
		<td align="center"><?php echo $grossTotalBox; ?></td>
		<td align="center"><?php echo $grossTotalTeus; ?></td>
	</tr>
</table>