<body>
	<table border="0" style="border-collapse:collapse;" align="center">
		<tr>
			<th><img align="middle"  width="210px" height="85px" src="<?php echo IMG_ROOT_PATH?>cpa_logo.png"></th>
		</tr>
		<tr>
			<th><h2>CHATTOGRAM PORT AUTHORITY</h2></th>
		</tr>
		<tr>
			<th><h3>USER TYPE WISE e-DO SUMMARY</h3></th>
		</tr>
		<tr>
			<th>
				<h4>
					FROM DATE : <?php echo date("d/m/Y", strtotime($from_date));?>, 
					TO DATE : <?php echo date("d/m/Y", strtotime($to_date));?>
				</h4>
			</th>
		</tr>
	</table>
	<table border="1" style="border-collapse:collapse;" align="center" width="80%">
		<tr>
			<th>User Type</th>
			<th>Total User</th>
			<th>e-DO Operated</th>
		</tr>
		<tr>
			<th> C&F </th>
			<th><?php echo $totalCNF;?></th>
			<th><?php echo $totalAppliedByCNF;?></th>			
		</tr>
		<tr>
			<th>Freight Forwarder</th>
			<th><?php echo $totalFF;?></th>
			<th><?php echo $totalUploadedByFF;?></th>			
		</tr>
		<tr>
			<th>Shipping Agent</th>
			<th><?php echo $totalMLO;?></th>
			<th><?php echo $totalUploadedByMLO;?></th>			
		</tr>
		<tr>
			<th>CPA</th>
			<th><?php echo $totalCPA;?></th>
			<th><?php echo $totalApprovedByCPA;?></th>
		</tr>
	</table>
</body>
