<head>
	<title><?php echo $title; ?></title>
</head>
<body>
	<table border="0" align="center">
		<tr>
			<td align="center"><h1><?php echo $title; ?></h1></td>
		</tr>
		<tr>
			<td align="center">
				<h4>From : <?php echo $fromDate; ?> To : <?php echo $toDate; ?></h4>
				<h4>Release By : <?php echo $login_id; ?></h4>
			</td>
		</tr>
	</table>
	
	
		<?php
			for($i=0;$i<count($rslt_dlvFromOffdockRpt);$i++)
			{								
				$rotNo = $rslt_dlvFromOffdockRpt[$i]['rot_no'];
				$blNo = $rslt_dlvFromOffdockRpt[$i]['bl_no'];
				$Pack_Number = $rslt_dlvFromOffdockRpt[$i]['Pack_Number'];
				$Pack_Description = $rslt_dlvFromOffdockRpt[$i]['Pack_Description'];
				$Description_of_Goods = $rslt_dlvFromOffdockRpt[$i]['Description_of_Goods'];
				
				$verifyNo = $rslt_dlvFromOffdockRpt[$i]['verify_no'];
				$verifyDate = $rslt_dlvFromOffdockRpt[$i]['verify_date'];
				$cpNo = $rslt_dlvFromOffdockRpt[$i]['cp_no'];
				$cpDate = $rslt_dlvFromOffdockRpt[$i]['cp_date'];
				
				$rlsBy = $rslt_dlvFromOffdockRpt[$i]['release_by'];
				$rlsDate = $rslt_dlvFromOffdockRpt[$i]['release_at'];
		?>
		<table style="border-collapse:collapse;border:1px solid black" border="1" align="center" width="80%">
		<tr>
			<td colspan="6">
				<br>
				<table width="100%">
					<tr>
						<td width="15%" align="right"><b>Rotation No</b></td>
						<td><b>:</b></td>
						<td><?php echo $rotNo; ?></td>
					</tr>
					<tr>
						<td width="15%" align="right"><b>BL No</b></td>
						<td><b>:</b></td>
						<td><?php echo $blNo; ?></td>
					</tr>
					<tr>
						<td width="15%" align="right"><b>Pack Number</b></td>
						<td><b>:</b></td>
						<td><?php echo $Pack_Number; ?></td>
					</tr>
					<tr>
						<td width="15%" align="right"><b>Pack Description</b></td>
						<td><b>:</b></td>
						<td><?php echo $Pack_Description; ?></td>
					</tr>
					<tr>
						<td width="15%" align="right"><b>Goods Description</b></td>
						<td><b>:</b></td>
						<td><?php echo $Description_of_Goods; ?></td>
					</tr>
					<tr>
						<td width="15%" align="right"><b>Verify No</b></td>
						<td><b>:</b></td>
						<td><?php echo $verifyNo; ?></td>
					</tr>
					<tr>
						<td width="15%" align="right"><b>Verify Date</b></td>
						<td><b>:</b></td>
						<td><?php echo $verifyDate; ?></td>
					</tr>
					<tr>
						<td width="15%" align="right"><b>CP No</b></td>
						<td><b>:</b></td>
						<td><?php echo $cpNo; ?></td>
					</tr>
					<tr>
						<td width="15%" align="right"><b>CP Date</b></td>
						<td><b>:</b></td>
						<td><?php echo $cpDate; ?></td>
					</tr>
					<tr>
						<td width="15%" align="right"><b>Release By</b></td>
						<td><b>:</b></td>
						<td><?php echo $rlsBy; ?></td>
					</tr>
					<tr>
						<td width="15%" align="right"><b>Release Date</b></td>
						<td><b>:</b></td>
						<td><?php echo $rlsDate; ?></td>
					</tr>
				</table>
				<br>
				<!--br>
				<?php echo "<b>Rotation No : </b>".$rotNo; ?>
				<br>
				<?php echo "<b>Bl No : </b>".$blNo; ?>
				<br>
				<?php echo "<b>Pack Number : </b>".$Pack_Number; ?>
				<br>
				<?php echo "<b>Pack Description : </b>".$Pack_Description; ?>
				<br>
				<?php echo "<b>Goods Description : </b>".$Description_of_Goods; ?>
				<br>
				<?php echo "<b>Verify No : </b>".$verifyNo; ?>
				<br>
				<?php echo "<b>Verify Date : </b>".$verifyDate; ?>
				<br>
				<?php echo "<b>CP No : </b>".$cpNo; ?>
				<br>
				<?php echo "<b>CP Date : </b>".$cpDate; ?>
				<br>				
				<br-->
			</td>
		</tr>
		<tr>
			<th>Sl</th>
			<th>Container</th>
			<th>Size</th>
			<th>Height</th>
			<th>Status</th>
			<th>Gross Weight</th>
		</tr>
			<?php
				include('mydbPConnection.php');
				
				$tbl = $rslt_dlvFromOffdockRpt[$i]['tbl'];
				
				$igmId = "";
				$sql_contInfo = "";
				
				if($tbl=="dtl")
				{
					$igmDtlId = $rslt_dlvFromOffdockRpt[$i]['igm_dtl_id'];
					
					$sql_contInfo = "SELECT id,cont_number,cont_size,cont_height,cont_status,cont_gross_weight
									FROM igm_detail_container
									WHERE igm_detail_id='$igmDtlId'";
				}
				else if($tbl=="sup")
				{
					$igmSupDtlId = $rslt_dlvFromOffdockRpt[$i]['igm_sup_dtl_id'];
					
					$sql_contInfo = "SELECT id,cont_number,cont_size,cont_height,cont_status,cont_gross_weight
									FROM igm_sup_detail_container
									WHERE igm_sup_detail_id='$igmSupDtlId'";
				}

				$rslt_contInfo = mysqli_query($con_cchaportdb,$sql_contInfo);
				
				$j=0;
				while($row_contInfo = mysqli_fetch_object($rslt_contInfo))
				{
					$j++;
			?>
					<tr>
						<td align="center"><?php echo $j; ?></td>
						<td align="center"><?php echo $row_contInfo->cont_number; ?></td>
						<td align="center"><?php echo $row_contInfo->cont_size; ?></td>
						<td align="center"><?php echo $row_contInfo->cont_height; ?></td>
						<td align="center"><?php echo $row_contInfo->cont_status; ?></td>
						<td align="center"><?php echo $row_contInfo->cont_gross_weight; ?></td>
					</tr>
				<?php
				}
				?>	
		</table>
		<br><br>
		<?php				
			}
		?>	
</body>
<?php mysqli_close($con_cchaportdb); ?>