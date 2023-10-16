<html>
	<body>
		<?php
			include("FrontEnd/mydbPConnection.php");
		?>
		<div style="position: relative;PAGE-BREAK-AFTER: avoid;">
		<?php //} else {?>
		<div style="position: relative;PAGE-BREAK-AFTER: avoid;">
		<?php //}?>
		<!--table width="100%" cellpadding="0" border="0">
			<tr height="100px">
				<td align="center" valign="middle">
					<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png">
					<h1 style="font-size:25px;">Chittagong Port Authority</h1>
					<h3 style="font-size:14px;">Delivery Report (Chemical Shed)</h3>
				</td>
			</tr>
			<tr height="100px">
				<td align="center" valign="middle">
					<h5 style="font-size:12px;">
						Delivery Report for Date: <b><?php echo $from_date; ?></b> To: <b><?php echo $to_date; ?>
					</h5>
				</td>
			</tr>
			<tr>
				<td>
					<table border="1" align="center" cellspacing="0" cellpadding="0" width="100%" 
						style="border-collapse:collapse;font-size:11px">
						<tr>
							<th class="text-center">#Sl</th>
							<th class="text-center">BL No</th>
							<th class="text-center">Container No</th>
							<th class="text-center">Rotation</th>
							<th class="text-center">Total Rcv Pkg</th>
							<th class="text-center">Unit</th>
							<th class="text-center">Delivered</th>
							<th class="text-center">Balance</th>
							<th class="text-center">Total Rcv Weight</th>
							<th class="text-center">Yard/Shed</th>
							<th class="text-center">Unstuffing Date</th>
						</tr>
							<?php for($i=0;$i<count($rtnTallyList);$i++){?>
								<tr>
									<td align="center"><?php echo $i+1;?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['BL_No'];?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['cont_number'];?></td>
									<td align="center"><?php echo $rtnTallyList[$i]['import_rotation'];?></td>
									<td align="center">
										<?php
											$igmsid = $rtnTallyList[$i]['igmsupid'];
											$forIgmDtl = 0;
											$forIgmSupDtl = 0;
											$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
													WHERE igm_detail_id='$igmsid'";
											$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
											$rowTotUnits = mysqli_fetch_object($resTotUnits);
											$rowTotal= $rowTotUnits->totRcvUnit;
											if($rowTotal!=0)
											{
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){									
												$forIgmDtl++;													
												$strTotWght = "SELECT SUM(total_pack) AS totPack FROM shed_tally_info 
												WHERE rcv_unit='$rowUnits->rcvUnit' AND igm_detail_id='$igmsid'";
												$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
												$rowTotWght = mysqli_fetch_object($resTotWght);
												echo $rowTotWght->totPack;
												if($rowTotal>1 && $forIgmDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}
											else
											{
												$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
												where igm_sup_detail_id='$igmsid'";
												$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
												$rowTotUnits = mysqli_fetch_object($resTotUnits);
												$rowTotal= $rowTotUnits->totRcvUnit;
												
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_sup_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){	
												$forIgmSupDtl++;
												$strTotWght = "SELECT SUM(total_pack) AS totPack FROM shed_tally_info 
												WHERE rcv_unit='$rowUnits->rcvUnit' 
												AND igm_sup_detail_id='$igmsid'";
												$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
												$rowTotWght = mysqli_fetch_object($resTotWght);
												echo $rowTotWght->totPack;
												if($rowTotal>1 && $forIgmSupDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}												
										?>
									</td>
									<td align="center">
										<?php
											$igmsid = $rtnTallyList[$i]['igmsupid'];
											$forIgmDtl = 0;
											$forIgmSupDtl = 0;
											$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
													WHERE igm_detail_id='$igmsid'";
											$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
											$rowTotUnits = mysqli_fetch_object($resTotUnits);
											$rowTotal= $rowTotUnits->totRcvUnit;
											if($rowTotal!=0)
											{
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){									
												$forIgmDtl++;		
												echo $rowUnits->rcvUnit;
												if($rowTotal>1 && $forIgmDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}
											else
											{
												$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
												where igm_sup_detail_id='$igmsid'";
												$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
												$rowTotUnits = mysqli_fetch_object($resTotUnits);
												$rowTotal= $rowTotUnits->totRcvUnit;
												
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_sup_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);												
												while($rowUnits = mysqli_fetch_object($resUnits)){	
												$forIgmSupDtl++;
												echo $rowUnits->rcvUnit;
												if($rowTotal>1 && $forIgmSupDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}												
										?>
									</td>
									<td align="center">
										<?php
											$igmsid = $rtnTallyList[$i]['igmsupid'];
											$forIgmDtl = 0;
											$forIgmSupDtl = 0;
											$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
													WHERE igm_detail_id='$igmsid'";
											$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
											$rowTotUnits = mysqli_fetch_object($resTotUnits);
											$rowTotal= $rowTotUnits->totRcvUnit;
											if($rowTotal!=0)
											{
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){									
												$forIgmDtl++;		
												echo 0;
												if($rowTotal>1 && $forIgmDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}
											else
											{
												$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
												where igm_sup_detail_id='$igmsid'";
												$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
												$rowTotUnits = mysqli_fetch_object($resTotUnits);
												$rowTotal= $rowTotUnits->totRcvUnit;
												
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_sup_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);												
												while($rowUnits = mysqli_fetch_object($resUnits)){	
												$forIgmSupDtl++;
												echo 0;
												if($rowTotal>1 && $forIgmSupDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}												
										?>
									</td>
									<td align="center">
										<?php
											$igmsid = $rtnTallyList[$i]['igmsupid'];
											$forIgmDtl = 0;
											$forIgmSupDtl = 0;
											$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
													WHERE igm_detail_id='$igmsid'";
											$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
											$rowTotUnits = mysqli_fetch_object($resTotUnits);
											$rowTotal= $rowTotUnits->totRcvUnit;
											if($rowTotal!=0)
											{
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){									
												$forIgmDtl++;													
												$strTotWght = "SELECT SUM(total_pack) AS totPack FROM shed_tally_info 
												WHERE rcv_unit='$rowUnits->rcvUnit' AND igm_detail_id='$igmsid'";
												$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
												$rowTotWght = mysqli_fetch_object($resTotWght);
												$totReceived =  $rowTotWght->totPack;
												echo $totReceived-0;
												if($rowTotal>1 && $forIgmDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}
											else
											{
												$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
												where igm_sup_detail_id='$igmsid'";
												$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
												$rowTotUnits = mysqli_fetch_object($resTotUnits);
												$rowTotal= $rowTotUnits->totRcvUnit;
												
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_sup_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){	
												$forIgmSupDtl++;
												$strTotWght = "SELECT SUM(total_pack) AS totPack FROM shed_tally_info 
												WHERE rcv_unit='$rowUnits->rcvUnit' 
												AND igm_sup_detail_id='$igmsid'";
												$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
												$rowTotWght = mysqli_fetch_object($resTotWght);
												$totReceived = $rowTotWght->totPack;
												echo $totReceived-0;
												if($rowTotal>1 && $forIgmSupDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}												
										?>
									</td>
									<td align="center">
										<?php
											$igmsid = $rtnTallyList[$i]['igmsupid'];
											$forIgmDtl = 0;
											$forIgmSupDtl = 0;
											$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
													WHERE igm_detail_id='$igmsid'";
											$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
											$rowTotUnits = mysqli_fetch_object($resTotUnits);
											$rowTotal= $rowTotUnits->totRcvUnit;
											if($rowTotal!=0)
											{
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){									
												$forIgmDtl++;													
												$strTotWght = "SELECT SUM(weight) AS weight FROM shed_tally_info 
												WHERE rcv_unit='$rowUnits->rcvUnit' AND igm_detail_id='$igmsid'";
												$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
												$rowTotWght = mysqli_fetch_object($resTotWght);
												echo $rowTotWght->weight." TON";
												if($rowTotal>1 && $forIgmDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}
											else
											{
												$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
												where igm_sup_detail_id='$igmsid'";
												$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
												$rowTotUnits = mysqli_fetch_object($resTotUnits);
												$rowTotal= $rowTotUnits->totRcvUnit;
												
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_sup_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){	
												$forIgmSupDtl++;
												$strTotWght = "SELECT SUM(weight) AS weight FROM shed_tally_info 
												WHERE rcv_unit='$rowUnits->rcvUnit' 
												AND igm_sup_detail_id='$igmsid'";
												$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
												$rowTotWght = mysqli_fetch_object($resTotWght);
												echo $rowTotWght->weight." TON";
												if($rowTotal>1 && $forIgmSupDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}
										?>
									</td>
									<td align="center">										
										<?php
											$igmsid = $rtnTallyList[$i]['igmsupid'];
											$forIgmDtl = 0;
											$forIgmSupDtl = 0;
											$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
													WHERE igm_detail_id='$igmsid'";
											$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
											$rowTotUnits = mysqli_fetch_object($resTotUnits);
											$rowTotal= $rowTotUnits->totRcvUnit;
											if($rowTotal!=0)
											{
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){									
												$forIgmDtl++;													
												$strTotWght = "SELECT shed_yard FROM shed_tally_info 
												WHERE rcv_unit='$rowUnits->rcvUnit' AND igm_detail_id='$igmsid'";
												$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
												$rowTotWght = mysqli_fetch_object($resTotWght);
												echo $rowTotWght->shed_yard;
												if($rowTotal>1 && $forIgmDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}
											else
											{
												$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
												where igm_sup_detail_id='$igmsid'";
												$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
												$rowTotUnits = mysqli_fetch_object($resTotUnits);
												$rowTotal= $rowTotUnits->totRcvUnit;
												
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_sup_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){	
												$forIgmSupDtl++;
												$strTotWght = "SELECT shed_yard FROM shed_tally_info 
												WHERE rcv_unit='$rowUnits->rcvUnit' 
												AND igm_sup_detail_id='$igmsid'";
												$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
												$rowTotWght = mysqli_fetch_object($resTotWght);
												echo $rowTotWght->shed_yard;
												if($rowTotal>1 && $forIgmSupDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}
										?>										
									</td>
									<td align="center">
										<?php
											$igmsid = $rtnTallyList[$i]['igmsupid'];
											$forIgmDtl = 0;
											$forIgmSupDtl = 0;
											$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
													WHERE igm_detail_id='$igmsid'";
											$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
											$rowTotUnits = mysqli_fetch_object($resTotUnits);
											$rowTotal= $rowTotUnits->totRcvUnit;
											if($rowTotal!=0)
											{
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){									
												$forIgmDtl++;													
												$strTotWght = "SELECT wr_date FROM shed_tally_info 
												WHERE rcv_unit='$rowUnits->rcvUnit' AND igm_detail_id='$igmsid'";
												$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
												$rowTotWght = mysqli_fetch_object($resTotWght);
												echo $rowTotWght->wr_date;
												if($rowTotal>1 && $forIgmDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}
											else
											{
												$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
												where igm_sup_detail_id='$igmsid'";
												$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
												$rowTotUnits = mysqli_fetch_object($resTotUnits);
												$rowTotal= $rowTotUnits->totRcvUnit;
												
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_sup_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){	
												$forIgmSupDtl++;
												$strTotWght = "SELECT wr_date FROM shed_tally_info 
												WHERE rcv_unit='$rowUnits->rcvUnit' 
												AND igm_sup_detail_id='$igmsid'";
												$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
												$rowTotWght = mysqli_fetch_object($resTotWght);
												echo $rowTotWght->wr_date;
												if($rowTotal>1 && $forIgmSupDtl!=$rowTotal)
													{
														echo '<hr style="color:black;height:2px;">';
													}
												}
											}
										?>
									</td>
								</tr>
							<?php } ?>
					</table>
				</td>
			</tr>
		</table-->
		<table width="100%" cellpadding="0" border="0">
			<tr height="100px">
				<td align="center" valign="middle">
					<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png">
					<h1 style="font-size:25px;">Chittagong Port Authority</h1>
					<h3 style="font-size:14px;">Receiving Report (Chemical Shed)</h3>
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
							<th class="text-center">#Sl</th>
							<th class="text-center">BL No</th>
							<th class="text-center">Container No</th>
							<th class="text-center">Rotation</th>
							<th class="text-center">Total Rcv Pkg</th>
							<th class="text-center">Unit</th>
							<th class="text-center">Delivered</th>
							<th class="text-center">Balance</th>
							<th class="text-center">Weight</th>
							<th class="text-center">Yard/Shed</th>
							<th class="text-center">Unstuffing Date</th>
						</tr>
							<?php for($i=0;$i<count($rtnTallyList);$i++){?>
								<?php
									$igmsid = $rtnTallyList[$i]['igmsupid'];
									$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
											WHERE igm_detail_id='$igmsid'";
									$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
									$rowTotUnits = mysqli_fetch_object($resTotUnits);
									$rowTotal= $rowTotUnits->totRcvUnit;
									if($rowTotal!=0)
									{
										$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
												where igm_detail_id='$igmsid'";
										$resUnits = mysqli_query($con_cchaportdb,$strUnits);
										$rowUnits = mysqli_fetch_object($resUnits);
										$rowTotal= $rowUnits->rcvUnit;
									}
									else
									{
										$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
										where igm_sup_detail_id='$igmsid'";
										$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
										$rowTotUnits = mysqli_fetch_object($resTotUnits);
										$rowTotal= $rowTotUnits->totRcvUnit;
									}
								?>
								<tr>
									<td align="center" rowspan="<?php echo $rowTotal;?>"><?php echo $i+1;?></td>
									<td align="center" rowspan="<?php echo $rowTotal;?>"><?php echo $rtnTallyList[$i]['BL_No'];?></td>
									<td align="center" rowspan="<?php echo $rowTotal;?>"><?php echo $rtnTallyList[$i]['cont_number'];?></td>
									<td align="center" rowspan="<?php echo $rowTotal;?>">
										<?php echo $rtnTallyList[$i]['import_rotation'];?>
									</td>
									
										
											<?php
												$forIgmDtl = 0;
												$forIgmSupDtl = 0;
												
												$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
												where igm_sup_detail_id='$igmsid'";
												$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
												$rowTotUnits = mysqli_fetch_object($resTotUnits);
												$rowTotal= $rowTotUnits->totRcvUnit;
												
												$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
														where igm_sup_detail_id='$igmsid'";
												$resUnits = mysqli_query($con_cchaportdb,$strUnits);
												
												while($rowUnits = mysqli_fetch_object($resUnits)){
												$forIgmSupDtl++;
												$strTotWght = "SELECT SUM(total_pack) AS totPack,SUM(weight) AS weight,shed_yard,wr_date
												FROM shed_tally_info 
												WHERE rcv_unit='$rowUnits->rcvUnit' 
												AND igm_sup_detail_id='$igmsid'";
												$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
												$rowTotWght = mysqli_fetch_object($resTotWght);
												?>
												
												<td align="center"><?php echo $rowTotWght->totPack."<br>";?></td>
												<td align="center"><?php echo $rowUnits->rcvUnit."<br>";?></td>
												<td align="center"><?php echo "0"."<br>";?></td>
												<td align="center">
													<?php 
														$received = $rowTotWght->totPack;
														$balance = $received-0;
														echo $balance."<br>";
													?>
												</td>
												<td align="center"><?php echo $rowTotWght->weight."<br>";?></td>
												<td align="center"><?php echo $rowTotWght->shed_yard."<br>";?></td>
												<td align="center"><?php echo $rowTotWght->wr_date."<br>";?></td>
												</tr>
												<tr>
												<?php } ?>
												
								</tr>
							<?php } ?>
					</table>
				</td>
			</tr>
		</table>
		</div>
	</body>
</html>
