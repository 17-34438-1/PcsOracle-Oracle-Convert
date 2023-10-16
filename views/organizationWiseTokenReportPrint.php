<body>	
	<?php 
		include("mydbPConnection.php");
		$i=1; 
		for($p=1;$p<=$totalPage;$p++) {
		$limitStartingFrom = ($p-1)*40;
	?>
	
	<div <?php if($p<$totalPage) { ?> style="page-break-after: always" <?php } ?> >
		<table border="0" align="center" width="100%" style="border-collapse:collapse;">
			<tr>
				<td align="center" width="70%">
					<h1>CHATTOGRAM PORT AUTHORITY</h1>
					<h2>ORGANIZATION WISE TOKEN REPORT</h2>
				</td>				
			</tr>	
		</table>
		<?php 
				
			$sqlReport = "SELECT Organization_Name,AIN_No,AIN_No_New,
			(SELECT COUNT(*) FROM token_distribution WHERE token_distribution.ff_ain=organization_profiles.AIN_No_New) 
			AS total_token,
			(SELECT COUNT(*) FROM token_distribution WHERE token_distribution.ff_ain=organization_profiles.AIN_No_New 
			AND token_distribution.used_st='1') AS total_used,
			(SELECT COUNT(*) FROM token_distribution WHERE token_distribution.ff_ain=organization_profiles.AIN_No_New 
			AND token_distribution.used_st='0') AS total_pending 
			FROM organization_profiles WHERE organization_profiles.Org_Type_id='4' 
			limit $limitStartingFrom,40";				
				
			$resReport = mysqli_query($con_cchaportdb,$sqlReport);
				
				
		?>
		<table border="1" style="border-collapse:collapse;margin-top:12px;" align="center" width="100%">
			
			<tr>
				<th class="text-center"><h2>SL No</h2></th>
				<th class="text-center"><h2>Organization Name</h2></th>
				<th class="text-center"><h2>AIN</h2></th>
				<th class="text-center"><h2>Total Purchased Token</h2></th>		
				<th class="text-center"><h2>Used Token</h2></th>
				<th class="text-center"><h2>Remaining Token</h2></th>
			</tr>				
			<?php while($rtnReport=mysqli_fetch_object($resReport)) { ?>
			<tr>
				<td align="center" style="font-size: 10pt;"><?php echo $i++; ?></td>
				<td align="center" style="font-size: 10pt;"><?php echo $rtnReport->Organization_Name;?></td>
				<td align="center" style="font-size: 10pt;"><?php echo $rtnReport->AIN_No_New;?></nobr></td>
				<td align="center" style="font-size: 10pt;"><?php echo $rtnReport->total_token;?></td>
				<td align="center" style="font-size: 10pt;"><?php echo $rtnReport->total_used;?></td>
				<td align="center" style="font-size: 10pt;"><?php echo $rtnReport->total_pending;?></td>
			</tr>			
			<?php } ?>
		</table>		
	</div>
	<?php } ?>
</body>