<div align ="center" style="margin:100px;">
	<table width="100%" style="border-collapse: collapse;" align="center">
	  <thead>
		<tr height="100px">
			<th align="center" colspan="10">
				<h2><img align="middle"  width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"></h2>
			</th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="10"><font size="7"><b>Chittagong Port Authority</b></font></th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="10"><font size="6"><b>EDO Verification Report</b></font></th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="10"><font size="6"><b>From Date : <?php echo $from_date; ?> To Date : <?php echo $to_date; ?></b></font></th>
		</tr>
		<tr>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>SL.</b></th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Rotation</b></th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>BL</b></th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>C&F</b></th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>MLO</b></th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b><nobr>FF</nobr></b></th>
			<?php if($search_type == "2") { ?>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b><nobr>Status</nobr></b></th>
			<?php } ?>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b><nobr>Applied at</nobr></b></th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Forwarded at</b></th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Uploaded at</b></th>
			<?php //if($search_type == "1" or $search_type == "2") { ?>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Verified by</b></th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Verified at</b></th>
			<?php //} ?>
		</tr>
		</thead>		
		<?php include("mydbPConnection.php"); ?>
		<?php 
			$total_uploaded_edo = 0;
			$total_approved_edo = 0;
			$total_unapproved_edo = 0;
			for($i=0;$i<count($edoVerificationList);$i++) { 
			$total_uploaded_edo = $total_uploaded_edo +1;
			
		?>
			<tr border ='1' cellpadding='0' cellspacing='0' style="font-size:15px;  border-collapse: collapse;">
				<td  style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $i+1;?>
				</td>
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $edoVerificationList[$i]['imp_rot']?>
				</td>
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $edoVerificationList[$i]['bl_no']?>
				</td>
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $edoVerificationList[$i]['CF']?>
				</td>	
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php 
						$mlo_org_id = $edoVerificationList[$i]['mlo'];
						$mloname = "";
						$strMloName = "SELECT organization_profiles.Organization_Name AS mlo
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.mlo=organization_profiles.id
						WHERE edo_application_by_cf.mlo='$mlo_org_id'";
						$resMloName = mysqli_query($con_cchaportdb,$strMloName);
						while($rowMLO = mysqli_fetch_object($resMloName)){
							$mloname = $rowMLO->mlo;
						}
						echo $mloname;
					?>
				</td>	
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php 
						$ff_id = $edoVerificationList[$i]['ff_org_id'];
						$ff = "";
						$strffName = "SELECT organization_profiles.Organization_Name AS ffName
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.ff_org_id=organization_profiles.id
						WHERE edo_application_by_cf.ff_org_id='$ff_id'";
						$resffName = mysqli_query($con_cchaportdb,$strffName);
						while($rowFF = mysqli_fetch_object($resffName)){
							$ff = $rowFF->ffName;
						}
						echo $ff;
					?>
				</td>		
				<?php if($search_type == "2") { ?>
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php 
						$check_st = $edoVerificationList[$i]['check_st'];
						if($check_st == "0"){
							echo "Not Approved";
							$total_unapproved_edo = $total_unapproved_edo + 1;
						} else {
							echo "Approved";
							$total_approved_edo = $total_approved_edo + 1;
						} 
					?>
				</td>
				<?php } ?>
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $edoVerificationList[$i]['entry_time']?>
				</td>
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $edoVerificationList[$i]['ff_clearance_time']?>
				</td>
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $edoVerificationList[$i]['upload_time']?>
				</td>
				<?php// if($search_type == "1" or $search_type == "2") { ?>
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $edoVerificationList[$i]['cpa_checked_by_name']?>
				</td>
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $edoVerificationList[$i]['cpa_check_time']?>
				</td>
				<?php //} ?>				
			</tr>
		<?php } ?>
	</table>
	<?php if($search_type == "2") { ?>
	<table border="1" width="100%" style="border-collapse: collapse;" align="center">
		<thead>
			<tr height="100px">
				<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Total</b></th>
				<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Verified</b></th>
				<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"><b>Verification Pending</b></th>
			</tr>
			<tr border ='1' cellpadding='0' cellspacing='0' style="font-size:15px;  border-collapse: collapse;">
				<td  style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $total_uploaded_edo; ?>
				</td>
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $total_approved_edo; ?>
				</td>
				<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center">
					<?php echo $total_unapproved_edo; ?>
				</td>
			</tr>
		</thead>
	</table>
	<?php } ?>
</div>
<?php if($page_flag=="html") { ?>
<script>
	window.print();
</script>
<?php } ?>