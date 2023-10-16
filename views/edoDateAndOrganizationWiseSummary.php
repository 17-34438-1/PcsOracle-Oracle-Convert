<body>
	<?php include("mydbPConnection.php"); ?>
	<?php include("mydbPConnectionn4.php"); ?>
	<table border="0" align="center" width="90%" style="border-collapse:collapse;">
		<tr>
			<td align="center">
				<img align="center" width="160px" style="margin:0px;padding:0px;" height="90px" src="<?php echo ASSETS_PATH?>images/cpa_logo.png">
			</td>
		</tr>
		<tr>
			<td align="center">
				<font size="6"><strong>Chattogram Port Authority</strong></font><br>
				<font size="5"><strong>Date And Organization Wise EDO Summary</strong></font><br>		
				<font size="5"><strong>From Date : <?php echo $from_date; ?> To Date : <?php echo $to_date; ?></strong></font><br>		
			</td>
		</tr>
	</table>
	
		
		<?php 
			$begin = new DateTime($from_date);
			$end   = new DateTime($to_date);
			$c = 0;
			$grand_total_uploaded = 0;
			$grand_total_approved = 0;
			$grand_total_not_approved  = 0;
			
			for($j = $begin; $j <= $end; $j->modify('+1 day')){
				$c++;				
				$i = $j->format("Y-m-d");
		?>
			<table border="1" align="center" width="95%" style="border-collapse:collapse;">
				<thead>
					<tr bgcolor="#ffffff" height="50px">
						<th align="left" colspan="5"><font size="6"><b> Date : <?php echo $i; ?></b></font></th>
					</tr>
					<tr>
						<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Sl</b></th>
						<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Organization Name</b></th>
						<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Uploaded</b></th>
						<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Approved</b></th>
						<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b><nobr>Pending</nobr></b></th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$do_date = "";
						$org_name = "";
						$tot = "";
						$approve = "";
						$notApprove = "";
						$sql = "SELECT do_date,org_name,SUM(tot) AS tot,SUM(approve) AS approve,SUM(notApprove) AS notApprove
						FROM (
						SELECT shed_mlo_do_info.do_date,IFNULL(ff_org_id,mlo) AS org, 
						(SELECT Organization_Name FROM organization_profiles WHERE id=org) AS org_name,
						1 AS tot,IF(check_st=1,1,0) AS approve,IF(check_st=0,1,0) AS notApprove
						FROM shed_mlo_do_info
						INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
						WHERE shed_mlo_do_info.do_date BETWEEN '$i' AND '$i'
						) AS tbl GROUP BY do_date,org_name ORDER BY do_date,org_name";

						$rslt=mysqli_query($con_cchaportdb,$sql);
						$cnt = 0;
						$sub_total_uploaded = 0;
						$sub_total_approved = 0;
						$sub_total_not_approved = 0;
						while($row=mysqli_fetch_object($rslt)){ 
						$cnt++;
						$do_date = $row->do_date;
						$org_name = $row->org_name;
						$tot = $row->tot;
						$approve = $row->approve;
						$notApprove = $row->notApprove;
						
						$sub_total_uploaded = $sub_total_uploaded + $tot;
						$sub_total_approved = $sub_total_approved + $approve;
						$sub_total_not_approved = $sub_total_not_approved + $notApprove;
					?>
					<tr>
						<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><?php echo $cnt;?></td>
						<td style="border:1px solid black; font-size:15px;  border-collapse: collapse; padding-left:5px;" align="left">
							<?php echo $org_name; ?>
						</td>
						<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><?php echo $tot; ?></td>	
						<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><?php echo $approve; ?></td>	
						<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><?php echo $notApprove; ?></td>
					</tr>
					<?php } ?>
					<tr>
						<th align="center" colspan="2"><font size="6"><b> Total</b></font></th>
						<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
							<b>
								<?php 
									echo $sub_total_uploaded; 
									$grand_total_uploaded = $grand_total_uploaded+$sub_total_uploaded;
								?>
							</b>
						</td>	
						<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
							<b>
								<?php 
									echo $sub_total_approved; 
									$grand_total_approved = $grand_total_approved+$sub_total_approved;
								?>
							</b>
						</td>	
						<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
							<b>
								<?php 
									echo $sub_total_not_approved; 
									$grand_total_not_approved = $grand_total_not_approved+$sub_total_not_approved;
								?>
							</b>
						</td>
					</tr>
					<tr>
						<th align="center" colspan="2"><font size="6"><b>Grand Total</b></font></th>
						<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
							<b><?php echo $grand_total_uploaded; ?></b>
						</td>	
						<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
							<b> <?php echo $grand_total_approved; ?> </b>
						</td>	
						<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
							<b><?php echo $grand_total_not_approved; ?></b>
						</td>
					</tr>
				</tbody>				
			</table>
		<?php } ?>
		<!--table border="1" align="center" width="95%" style="border-collapse:collapse;">
			<thead>
				<tr>
					<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Total Uploaded</b></th>
					<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Total Approved</b></th>
					<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Total Unapproved</b></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
						<b><?php echo $grand_total_uploaded; ?></b>
					</td>
					<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
						<b><?php echo $grand_total_approved; ?></b>
					</td>
					<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
						<b><?php echo $grand_total_not_approved; ?></b>
					</td>
				</tr>
			</tbody>
		</table-->
	
	<?php mysqli_close($con_sparcsn4); ?>
	<?php mysqli_close($con_cchaportdb); ?>
	<!--script>
		window.print();
	</script-->
</body>