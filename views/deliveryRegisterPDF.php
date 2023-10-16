<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<table width="100%" cellpadding="0" border="0">
			<tr height="100px">
				<td align="center" valign="middle">
					<h1 style="font-size:25px;">CHITTAGONG PORT AUTHORITY</h1>
				</td>
			</tr>
			<tr height="100px">
				<td align="center" valign="middle">
					<h6 style="font-size:12px;">
						DELIVERY REGISTER
					</h6>
				</td>
			</tr>
			<tr height="100px">
				<td align="center" valign="middle">
					<h6 style="font-size:12px;">
						From : <?php echo $fromDate; ?>, To : <?php echo $toDate; ?>
					</h6>
				</td>
			</tr>
			<tr>
				<td>
					<table border="1" align="center" cellspacing="0" cellpadding="0" width="200%" 
						style="border-collapse:collapse;font-size:11px">
						<tr>
							<th>Sl</th>
							<th>Truck No</th>
							<th>Manifested Marks</th>
							<th>Cart Ticket Marks</th>
							<th>Cart Ticket Quality</th>
							<th>Closing Balance</th>
							<th>B.E & R.O No</th>
							<th>Shed No</th>
							<th>Name of Ship</th>
							<th>Name of Clearing Firm</th>
							<th>Time Passed Out</th>
							<th>Signature of Inspector Gate Sergent</th>
							<th>Signature of Jetty Sarcar with J.S.L No</th>
							<th>Customs Verify Nos & Date</th>
							<th>Remarks</th>
						</tr>
						<tr>
							<?php for($x=1;$x<=15;$x++) { ?>
							<td align="center"><?php echo $x; ?></td>
							<?php } ?>
						</tr>
						<?php for($i=0;$i<count($resQuery);$i++){ ?>
						<tr>
							<td align="center"><?php echo $i+1;?></td>
							<td align="center"><p style="font-family: ind_bn_1_001"><?php echo $resQuery[$i]['truck_id']; ?></p></td>
							<td align="center"><?php echo $resQuery[$i]['manifestMarks']; ?></td>							
							<td align="center"> </td>
							<td align="center"> </td>
							<td align="center"> </td>
							<td align="center"> </td>
							<td align="center"> </td>
							<td align="center"><?php echo $resQuery[$i]['nameofship']; ?></td>
							<td align="center"><?php echo $resQuery[$i]['cnfName']; ?></td>
							<td align="center"> </td>
							<td align="center"> </td>
							<td align="center"> </td>
							<td align="center"> </td>
							<td align="center"> </td>
						</tr>
						<?php } ?>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>