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
						<td colspan="12"><font size="4"><b><?php echo $title;?> from the date <?php echo $fromDate; ?> to <?php echo $toDate; ?></b></font></td>
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
			<th><b>Entry Date</b></th>		
			<th><b>Container</b></th>			
			<th><b>Truck</b></th>
			<th><b>C&F</b></th>		
		</tr>
		<?php
		$totalCont=0;
		$totalTruck=0;
        $totalCf=0;
		for($i=0;$i<count($summaryRslt);$i++)
		{
		?>
		<tr>
			<td align="center"><?php echo $i+1; ?></td>
			<td align="center"><?php echo $summaryRslt[$i]['entryDt']; ?></td>		
			<td align="center"><?php echo $summaryRslt[$i]['cont']; ?></td>		
			<td align="center"><?php echo $summaryRslt[$i]['truck']; ?></td>		
			<td align="center"><?php echo $summaryRslt[$i]['cnf']; ?></td>						
		</tr>
		<?php
			$totalCont=$totalCont+$summaryRslt[$i]['cont'];
			$totalTruck=$totalTruck+$summaryRslt[$i]['truck'];
            $totalCf=$totalCf+$summaryRslt[$i]['cnf'];
		}
		?>
		<tr align="center">
			<td colspan="2" align="center"><b>Total</b></td>
			<td align="center"><?php echo $totalCont; ?></td>
            <td align="center"><?php echo $totalTruck; ?></td>
			<td align="center"><?php echo $totalCf; ?></td>
		</tr>
	</table>
</body>