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
					<h1 style="font-size:25px;">Chittagong Port Authority</h1>
					<h3 style="font-size:20px;">Tally Report</h3>
					<h4> Date: <b><?php echo $from_date; ?></b> To: <b><?php echo $to_date; ?></h4>
				</td>
			</tr>
			
			<tr>
				<td>
					<table border="1" align="center" cellspacing="0" cellpadding="0" width="80%" style="border-collapse:collapse;font-size:11px">
						
						<tr>
							<th class="text-center">Sl</th>
							<th class="text-center">BL</th>
							<th class="text-center">Rotation</th>
							<th class="text-center">Marks</th>
							<th class="text-center">Unstuff Date</th>
							<th class="text-center">Pack Unit</th>
							<th class="text-center">Yard/Shed</th>			
							<th class="text-center">Total pkg</th>
							<th class="text-center">Dlv Unit</th>
							<th class="text-center">Balance</th>
						</tr>
						
					<?php
						/* $tot_rcv_pack=0;
						$tot_loc_first=0;
						$tot_flt_pack=0;
						$tot_flt_pack_loc=0;
						$grand_tot=0;
						$grand_weight=0; */
						
						//print_r($rtnTallyList);
								
						for($i=0;$i<count($rtnTallyList);$i++){
							/* $rotation = $rtnTallyList[$i]['import_rotation'];
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
									$grand_weight = $grand_weight + $row_igm->cont_gross_weight; */
	
							?>									
								<tr>
									<td align="center"><?php echo $i+1;?></td>
									<td align="center"><?php if($rtnTallyList[$i]['BL_No']) echo $rtnTallyList[$i]['BL_No']; else echo ''; ?></td>
									<td align="center"><?php if($rtnTallyList[$i]['import_rotation']) echo $rtnTallyList[$i]['import_rotation']; ?></td>
									<td align="center"><?php if($rtnTallyList[$i]['marks']) echo $rtnTallyList[$i]['marks']; else echo ''; ?></td>
									<td align="center"><?php if($rtnTallyList[$i]['wr_date']) echo $rtnTallyList[$i]['wr_date']; else echo ''; ?></td>
									<td align="center"><?php if($rtnTallyList[$i]['rcv_unit']) echo $rtnTallyList[$i]['rcv_unit']; else echo ''; ?></td>
									<td align="center"><?php if($rtnTallyList[$i]['shed_yard']) echo $rtnTallyList[$i]['shed_yard']; else echo '';?></td>
									<td align="center"><?php if($rtnTallyList[$i]['total_pack']) echo $rtnTallyList[$i]['total_pack']; else echo ''; ?></td>
									<td align="center"></td>
									<td align="center"><?php if($rtnTallyList[$i]['total_pack']) echo $rtnTallyList[$i]['total_pack']; else echo '';?></td>
		
								</tr>
							<?php } ?>
								<!--tr>
									<th colspan="7" align="center">Total</th>
									<th align="center"><?php echo $tot_rcv_pack; ?></th>
									<th align="center"><?php echo $tot_loc_first; ?></th>
									<th align="center"><?php echo $tot_flt_pack; ?></th>
									<th align="center"><?php echo $tot_flt_pack_loc; ?></th>
									<th align="center"><?php echo $grand_tot; ?></th>
									<th align="center"><?php echo $grand_weight; ?></th>
									<th align="center"></th>
									<th align="center"></th>
								</tr-->
					</table>
				</td>
			</tr>
		</table>
		</div>
	</body>
</html>
<script>
window.print();
</script>
