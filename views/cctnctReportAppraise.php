<html>
	<body>
		<?php
		include("mydbPConnectionn4.php");		
		include("mydbPConnection.php");
		$assignType="";			
		$length=count($rsltNCTCCT);
		for($i=0;$i<$length;$i++)
		{ 		
		$mfdch_value = $rsltNCTCCT[$i]['mfdch_value'];
		$mfdch_desc = $rsltNCTCCT[$i]['mfdch_desc'];
		?>
		<div class="pagewidth">
			<table border="0" cellspacing="0" width="1000px">
				<thead>
					<tr>
						<td colspan="4" align="center"><img width="200px" height="60px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
					</tr>
					<tr>
						<td colspan="4" align="center"><h3>OFFICE OF THE TERMINAL MANAGER</h3></td>
					</tr>
					<tr>
						<td colspan="4" align="center"><h3>HEAD DELIVERY REGISTER REPORT OF <?php echo $terminal?></h3></td>
					</tr>
					<tr>
						<td colspan="4" align="center"><h3>Date: <?php echo $date?></h3></td>
					</tr>
					<tr>
						<td colspan="4" style="font-size:16px;"><b><?php echo "Assignment (Delivery): ".$mfdch_desc; ?></b></td>
					</tr>
				</thead>

				<?php 
				// $strAllData = "SELECT DISTINCT * FROM ctmsmis.tmp_assignment_type_new
				// WHERE mfdch_value='$mfdch_value' and Yard_No='$terminal' ORDER BY Yard_No,mfdch_value,flex_date01,line_no";
				$strAllData = "SELECT DISTINCT cf_name,cont_no,size,v_name,rot_no,bl_no FROM ctmsmis.tmp_oracle_assignment
				WHERE assignmentDate = '$date' AND mfdch_value='$mfdch_value' AND Yard_No='$terminal' ORDER BY Yard_No,mfdch_value,flex_date01,bl_no";
				$resAllData = mysqli_query($con_sparcsn4,$strAllData);
				$j=0;
				$cnf="";
				$bl="";
				$t20=0;
				$t40=0;
				$tot = 0;
				
				while($rowAllData = mysqli_fetch_object($resAllData))
				{
					$tot++;
					if($cnf!=$rowAllData->cf_name or $bl!=$rowAllData->bl_no)
					{
						$j = $j+1;
						// $cnf=$rowAllData->cf;
						// $bl=$rowAllData->line_no;
						$cnf=$rowAllData->cf_name;
						$bl=$rowAllData->bl_no;
						
					?>
					<tr>
						<td style="border:1px solid black;font-size:11px;" align="center" height="30px" width="20px"><b><?php echo $j;?></b></td>
						<td style="border:1px solid black;font-size:11px;" colspan="3" height="30px"><b><?php echo "C&F: ".$rowAllData->cf_name.", Vessel: ".$rowAllData->v_name.", Rotation: ".$rowAllData->rot_no.", BL No: ".$rowAllData->bl_no;?></b></td>
					</tr>
					<?php
					$rotation=$rowAllData->rot_no;
					$blno=$rowAllData->bl_no;
					
					$sql_stc="SELECT CONCAT(Pack_Number,' ',Pack_Description) AS stc,concat(weight,' KG') as weight FROM igm_details WHERE Import_Rotation_No='$rotation' AND replace(BL_No,'/','')='$blno'";
					
					$rslt_stc=mysqli_query($con_cchaportdb,$sql_stc);
					$num_row=mysqli_num_rows($rslt_stc);
					if($num_row==0)
					{
						$sql_stc="SELECT CONCAT(Pack_Number,' ',Pack_Description) AS stc,concat(weight,' KG') as weight FROM igm_supplimentary_detail WHERE Import_Rotation_No='$rotation' AND replace(BL_No,'/','')='$blno'";
						
						$rslt_stc=mysqli_query($con_cchaportdb,$sql_stc);
					}

					$stc = "";
					$weight = "";
					while($row=mysqli_fetch_object($rslt_stc)){
						$stc = $row->stc;
						$weight = $row->weight;
					}

					$manif = str_replace("/"," ",$rotation);
					
					$sql_be = "SELECT reg_no,reg_date FROM sad_info 
					INNER JOIN sad_item ON sad_item.sad_id = sad_info.id
					WHERE manif_num = '$manif' AND sum_declare = '$blno' LIMIT 1";

					$rslt_be=mysqli_query($con_cchaportdb,$sql_be);
					$be_no = "";
					$be_date = "";
					while($row_be=mysqli_fetch_object($rslt_be)){
						$be_no = $row_be->reg_no;
						$be_date = $row_be->reg_date;
					}

					?>
					<tr>
						<td style="border:1px solid black"></td>
						<td style="border:1px solid black;padding:0px;margin:0px;" colspan="3" height="40px">
							<table border="0" cellpadding="3" valign="top">
								<tr>
									<td width="50px"><b>B/E No:</b></td>
									<td width="200px"><?php echo $be_no; ?></td>
									<td width="80px"><b>B/E Date:</b></td>
									<td width="100px"><?php echo $be_date; ?></td>
									<td width="50px"><b>STC:</b></td>
									<td width="130px"><?php echo $stc;?></td>
								</tr>
								<tr>
									<td width="50px"><b>CP No:</b></td>
									<td width="200px">&nbsp;</td>
									<td width="80px"><b>CP Date:</b></td>
									<td width="100px">&nbsp;</td>
									<td width="50px"><b>Weight:</b></td>
									<td width="130px"><?php echo $weight;?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="border:1px solid black" align="center" height="20px">&nbsp;</td>
						<td style="border:1px solid black" align="center" width="120px" height="20px"><b>Cont No.</b></td>
						<td style="border:1px solid black" align="center" width="450px" height="20px"><b>Cart Details</b></td>
						<!--td style="border:1px solid black" align="center">From</td-->
						<td style="border:1px solid black" align="center" width="100px" height="20px"><b>Signature & Mobile</b></td>
					</tr>
					<?php
					}
					
					if($rowAllData->size==20)
						$t20 += 1;
					else
						$t40 += 1;
					?>
					
					<tr>
						<td style="border:1px solid black" align="center">&nbsp;</td>						
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->cont_no." x ".$rowAllData->size."'"; ?></td>
						<td style="border:1px solid black">
							<?php
								$cont_no = $rowAllData->cont_no;
								$query_truck = "SELECT truck_id,actual_delv_pack,Pack_Unit FROM do_truck_details_entry
								INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_entry.actual_delv_unit
								WHERE cont_no = '$cont_no'";

								$rslt_truck=mysqli_query($con_cchaportdb,$query_truck);
							?>
							
							<table width="100%" align="center" cellpadding="5px">
								<tr>
									<th>Truck ID</th>
									<th><nobr>Delivery Pack</nobr></th>
								</tr>
								<?php
									$truckId = "";
									$delv_pack = "";
									while($truckData = mysqli_fetch_object($rslt_truck)){
										$truckId = $truckData->truck_id;
										$delv_pack = $truckData->actual_delv_pack;
										$delv_unit = $truckData->Pack_Unit;
								?>
										<tr>
											<td align="center"><p style="font-family: ind_bn_1_001"><?php echo $truckId; ?></p></td>
											<td align="center"><?php echo $delv_pack." ".$delv_unit; ?></td>
										</tr>
								<?php
									}
								?>

							</table>
						</td>
						<td style="border:1px solid black"></td>
						<!--td style="border:1px solid black"><?php echo $rowAllData->slot; ?></td>
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->remarks; ?></td-->
					</tr>
				<?php
				}
				?>
					<!--tr><td colspan="5"><hr></td></tr-->
					<tr>
						<!--td>Total:</td>
						<td><?php echo $tot; ?></td>
						<td align="right">20 FT:</td>
						<td><?php echo $t20; ?></td>
						<td align="right">40 FT:</td>
						<td><?php echo $t40; ?></td>
						<td align="right">TEUS:</td>
						<td><?php echo $t20+$t40*2; ?></td-->
					</tr>
			</table>
		</div>
			<?php
			if($i==$length-1)
			{ 
			?>
				<div class="pageBreakOff"></div>
			<?php
			 }
			else if($i<$length)  
			{ 
			?>
				<div class="pageBreak"></div>
			<?php
			}
			?>
		<?php
		}
		?>
	</body>
</html>