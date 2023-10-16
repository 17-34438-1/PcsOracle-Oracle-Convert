<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=edoContainerReportExcel.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<!--table width="100%" style="border-collapse: collapse;" align="center"-->
<table width="100%" style="border:1px solid black;border-collapse:collapse;" align="center">
	  <thead>
		
		<tr bgcolor="#ffffff" height="50px">
			<th style="border:1px solid black;border-collapse:collapse;" align="center" colspan="12"><font size="7"><b><?php echo $orgNAME; ?></b></font></th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th style="border:1px solid black;border-collapse:collapse;" align="center" colspan="12"><font size="6"><b>Container Report</b></font></th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th style="border:1px solid black;border-collapse:collapse;" align="center" colspan="12"><font size="6"><b>From Date : <?php echo $from_date; ?> To Date : <?php echo $to_date; ?></b></font></th>
		</tr>
		<tr>			
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">#Sl</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Bl NO</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">CONTAINER NO </th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">TYPE SIZE </th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">HEIGHT </th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">ISO </th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">FEEDER VESSEL</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">IMP ROTATION</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">ISSUE DATE</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">VALID UPTO DATE</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">REMARKS</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">CNF NAME</th>
		</tr>
		</thead>		
		
		<?php for($i=0; $i < count($edoVerificationList); $i++) { ?>
				<tr border ='1' cellpadding='0' cellspacing='0' style="font-size:12px;  border-collapse: collapse;">
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center" >
					<?php echo $i+1;?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center" >
					<?php echo $edoVerificationList[$i]['bl_no'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center" >
					<?php echo $edoVerificationList[$i]['cont_number'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $edoVerificationList[$i]['cont_size'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $edoVerificationList[$i]['cont_height'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center" >
					<?php echo $edoVerificationList[$i]['cont_iso_type'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $edoVerificationList[$i]['Vessel_Name'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $edoVerificationList[$i]['imp_rot'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $edoVerificationList[$i]['upload_date'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $edoVerificationList[$i]['valid_upto_dt'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $edoVerificationList[$i]['remarks'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center" >
					<?php echo $edoVerificationList[$i]['CF'];?>
						
					</td>
				</tr>
		<?php } ?>				
	</table>