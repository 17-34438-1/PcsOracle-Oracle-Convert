<html>
	<body>
		<?php
			include("FrontEnd/mydbPConnection.php");
		?>
		<div style="position: relative;PAGE-BREAK-AFTER: avoid;">
		<?php //} else {?>
		<div style="position: relative;PAGE-BREAK-AFTER: avoid;">
		<?php //}?>
		
		<table width="100%" cellpadding="0" border="0">
			<tr height="100px">
				<td align="center" valign="middle">
					<img style="width:70px; height:70px;" src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png">
					<h1 style="font-size:18px;">Chittagong Port Authority</h1>
					<h3 style="font-size:14px;">Tally Report</h3>
				</td>
			</tr>
			<tr height="100px">
				<td align="center" valign="middle">
					<h5 style="font-size:12px;">
						Receiving Report for Date: <b><?php echo $from_date; ?></b> To: <b><?php echo $to_date; ?>
					</h5>
				</td>
			</tr>
			<tr>
				<td>
					<table border="1" align="center" cellspacing="0" cellpadding="0" width="100%" 
						style="border-collapse:collapse;font-size:11px">
						
						<tr>
							<th rowspan='2' class="text-center">#Sl</th>
							<th rowspan='2' class="text-center">Tally Sheet No</th>
							<th rowspan='2' class="text-center">Physical Tally</th>
							<th rowspan='2' class="text-center">Container No</th>
							<th rowspan='2' class="text-center">Size</th>
							<th rowspan='2' class="text-center">Tues</th>
							<th rowspan='2' class="text-center">Rotation</th>
							<th class="text-center">W/R House</th>
							<th class="text-center">Loc Fast</th>
							<th class="text-center">W/R House</th>
							<th class="text-center">Loc Fast</th>
							<th rowspan='2' class="text-center">Total pkg of tally</th>
							<th rowspan='2' class="text-center">Total Weight</th>
							<th rowspan='2' class="text-center">Yard/Shed</th>
							<th rowspan='2' class="text-center">Unstuffing Date</th>
						
							<!--th  class="text-center">#Sl</th>
							<th class="text-center">Tally Sheet No</th>
							<th class="text-center">Physical Tally</th>
							<th class="text-center">Container No</th>
							<th class="text-center">Size</th>
							<th class="text-center">Tues</th>
							<th class="text-center">Rotation</th>
							<th class="text-center">W/R House</th>
							<th class="text-center">Loc Fast</th>
							<th class="text-center">W/R House</th>
							<th class="text-center">Loc Fast</th>
							<th class="text-center">Total pkg of tally</th>
							<th class="text-center">Total Weight</th>
							<th class="text-center">Yard/Shed</th>
							<th class="text-center">Unstuffing Date</th-->
						</tr>
						<tr>
							<th colspan="2" class="text-center">Goods Condition </th>
							<th colspan="2" class="text-center">Fault Condition</th>

							
						</tr>
					<?php
						$tot_rcv_pack=0;
						$tot_loc_first=0;
						$tot_flt_pack=0;
						$tot_flt_pack_loc=0;
						$grand_tot=0;
						$grand_weight=0;
								
						for($i=0;$i<count($rtnTallyList);$i++){
							$rotation = $rtnTallyList[$i]['import_rotation'];
							$container = $rtnTallyList[$i]['cont_number'];
									
						$strIgm = "SELECT igm_detail_container.cont_gross_weight, igm_detail_container.cont_size, IF(cont_size=1, '1', '2') AS teus  FROM igm_detail_container INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
						WHERE igm_details.Import_Rotation_No='$rotation'
						AND igm_detail_container.cont_number='$container' 
						UNION
						SELECT igm_sup_detail_container.cont_gross_weight, igm_sup_detail_container.cont_size, IF(cont_size=1, '1', '2') AS teus 
						FROM igm_sup_detail_container 
						INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
						INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
						WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' 
						AND igm_sup_detail_container.cont_number='$container'  LIMIT 1 ";
						$res_igm = mysqli_query($con_cchaportdb,$strIgm);
						$row_igm = mysqli_fetch_object($res_igm);

									$tot_rcv_pack = $tot_rcv_pack + $rtnTallyList[$i]['rcv_pack'];
									$tot_loc_first = $tot_loc_first + $rtnTallyList[$i]['loc_first'];
									$tot_flt_pack = $tot_flt_pack + $rtnTallyList[$i]['flt_pack'];
									$tot_flt_pack_loc = $tot_flt_pack_loc + $rtnTallyList[$i]['flt_pack_loc'];
									$grand_tot = $grand_tot + $rtnTallyList[$i]['tot_pkg'];
									$grand_weight = $grand_weight + $row_igm->cont_gross_weight;
									
							?>									
								<tr>
									<td align="center"><?php echo $i+1;?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['tally_sheet_number'];?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['physical_tally_sheet_no'];?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['cont_number'];?></td>
									<td align="center"><?php echo $row_igm->cont_size; ?></td>
									<td align="center"><?php echo $row_igm->teus; ?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['import_rotation'];?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['rcv_pack'];?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['loc_first'];?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['flt_pack'];?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['flt_pack_loc'];?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['tot_pkg'];?></td>
									<td align="center"><?php echo $row_igm->cont_gross_weight; ?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['shed_yard'];?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['wr_date'];?></td>		
								</tr>
							<?php } ?>
								<tr>
									<th colspan="7" align="center">Total</th>
									<th align="center"><?php echo $tot_rcv_pack; ?></th>
									<th align="center"><?php echo $tot_loc_first; ?></th>
									<th align="center"><?php echo $tot_flt_pack; ?></th>
									<th align="center"><?php echo $tot_flt_pack_loc; ?></th>
									<th align="center"><?php echo $grand_tot; ?></th>
									<th align="center"><?php echo $grand_weight; ?></th>
									<th align="center"></th>
									<th align="center"></th>
								</tr>
					</table>
				</td>
			</tr>
		</table>
		</div>
	</body>
</html>
