<html>
	<body>
		<table width="100%" cellpadding="0" border="0">
			<tr height="100px">
				<td align="center" valign="middle">
					<h1 style="font-size:25px;">Date Wise Token Distribution</h1>
				</td>
			</tr>
			<tr height="100px">
				<td align="center" valign="middle">
					<h6 style="font-size:12px;">
						From: <?php echo $fromDate; ?> To: <?php echo $toDate; ?>
					</h6>
				</td>
			</tr>
			<tr>
				<td>
					<table border="1" align="center" cellspacing="0" cellpadding="0" width="120%" 
						style="border-collapse:collapse;font-size:11px">
						<tr>
							<th>#Sl</th>
							<th>Organization Name</th>
							<th>AIN No</th>
							<th>Quantity</th>
						</tr>
						<?php 
							$totalQty = 0;
							for($i=0;$i<count($rsltTokenCount);$i++){
							$totalQty = $totalQty+$rsltTokenCount[$i]['Quantity'];
						?>
						<tr>
							<td align="center"><?php echo $i+1;?></td>
							<td align="center"><?php echo $rsltTokenCount[$i]['ff_name']; ?></td>
							<td align="center"><?php echo $rsltTokenCount[$i]['ff_ain']; ?></td>
							<td align="center"> <?php echo $rsltTokenCount[$i]['Quantity']; ?> </td>
						</tr>
						<?php } ?>
						<tr>
                            <td align="center" colspan="3"><b>Total</b></td>
                            <td align="center"><?php echo  $totalQty; ?></td>
                        </tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>