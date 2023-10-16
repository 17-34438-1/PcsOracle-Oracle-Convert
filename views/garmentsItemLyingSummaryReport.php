<title><?php echo $title; ?></title>
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
					<tr align="center">
						<td colspan="12"><font size="4"><b></b></font></td>
					</tr>				
				</table>		
			</td>		
		</tr>		
	</table>
	<table width="80%" border ='1' cellpadding='0' cellspacing='0' align="center" style="border-collapse: collapse">
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
		for($i=0;$i<count($rslt_lyingSummary);$i++)
		{
		?>
		<tr>
			<td align="center"><?php echo $i+1; ?></td>
			<td align="center"><?php echo $rslt_lyingSummary[$i]['Vessel_Name']; ?></td>		
			<td align="center"><?php echo $rslt_lyingSummary[$i]['Import_Rotation_No']; ?></td>		
			<td align="center"><?php echo $rslt_lyingSummary[$i]['Box']; ?></td>		
			<td align="center"><?php echo $rslt_lyingSummary[$i]['TEUs']; ?></td>						
		</tr>
		<?php
			$grossTotalBox=$grossTotalBox+$rslt_lyingSummary[$i]['Box'];
			$grossTotalTeus=$grossTotalTeus+$rslt_lyingSummary[$i]['TEUs'];
		}
		?>
		<tr align="center">
			<td colspan=3 align="center"><b>Total</b></td>
			<td align="center"><?php echo $grossTotalBox; ?></td>
			<td align="center"><?php echo $grossTotalTeus; ?></td>
		</tr>
	</table>
</body>