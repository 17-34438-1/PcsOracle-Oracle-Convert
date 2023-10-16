<?php 
	//echo "count : ".count($rtnCounter);
	include_once("FrontEnd/mydbPConnection.php");

	$strBerth = " SELECT DISTINCT berthOp AS rtnValue FROM shed_tally_info WHERE  shed_tally_info.import_rotation='$rotation' 
	AND shed_tally_info.cont_number='$container' AND berthOp IS NOT NULL AND berthOp!='' ORDER BY shed_tally_info.id DESC LIMIT 1";
	@$berthOp = $this->bm->dataReturnDb1($strBerth);

	$strYardName = "SELECT DISTINCT shed_yard AS rtnValue FROM shed_tally_info WHERE shed_tally_info.import_rotation='$rotation' 
	AND shed_tally_info.cont_number='$container' AND shed_yard IS NOT NULL ORDER BY shed_tally_info.id DESC LIMIT 1";
	@$yardName = $this->bm->dataReturnDb1($strYardName);	

	$physicalTallySheetNumber = "";	
	$strPhyTally = "SELECT DISTINCT id,physical_tally_sheet_no
				FROM shed_tally_info 
				WHERE import_rotation='$rotation' AND cont_number='$container'
				ORDER BY id DESC LIMIT 1";
	$resPhyTally = mysqli_query($con_cchaportdb,$strPhyTally);
	while($rowPhyTally = mysqli_fetch_object($resPhyTally))
	{
		$physicalTallySheetNumber = $rowPhyTally->physical_tally_sheet_no;
	}
	$total_1=0;
	$total_2=0;
	$total=0;
	$counter=ceil(count($rtnCounter)/6);

	$totQty = 0;
	$subTotQty = 0;

	for($j=0;$j<$counter;$j++)
	{ 
?>

<html>
	<body>
	<?php if($j<$counter-1) {?>
		<div style="position: relative;PAGE-BREAK-AFTER: always;">
		<?php } else {?>
		<div style="position: relative;PAGE-BREAK-AFTER: avoid;">
		<?php }?>
		<table width="100%" cellpadding="0" border="0">
			<tr height="100px">
				<td align="center" valign="middle">
					<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" height=50 width=50 >
					<h1 style="font-size:18px;">Chittagong Port Authority</h1>
					<h3 style="font-size:12px;">Unstuffing Tally of Container</h3>
				</td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="0" align="center">
						<tr>
							<td width="35%" style="font-size:11px;">
								Vessel Name: <?php echo $rtninfo[0]['Vessel_Name'];?>
							</td>
							<td width="27%" style="font-size:11px;">
								Rot No : <?php echo $rotation;?>
							</td>
							<td width="37%" align="left" style="border:1px solid black;font-size:12px;" >
								<!--div style="border:1px solid black; width:250px">Tally Sheet No: <b><?php echo $section."-".$rtninfo[0]['tls_no'];?></b></div-->
								
								T.S.N.: <b><?php $subtsn = $j+1; echo $rtninfo[0]['tally_sheet_number']."-".$subtsn;?></b>
							</td>
						</tr>
						<tr>
							<td style="font-size:11px;">
								Shipping Agent : <?php echo $rtninfo[0]['mlocode'];?>
							</td>
							<td style="font-size:11px;">
								Arrival Date : <?php echo $rsltBerth[0]['ata']?>
							</td>
							<td style="font-size:11px;">
								Shed No: <?php echo @$yardName;?>
							</td>							
						</tr>
						<tr>
							<!--td style="font-size:11px;">
								Handling Contractor : <?php echo $rsltBerth[0]['berthOp']?>
							</td-->
							<td style="font-size:11px;">
								Container No : <?php echo strtoupper($container);?>
							</td>
							<td style="font-size:11px;">
								Size: <?php echo $rtninfo[0]['cont_size'];?>
							</td>
							<td style="font-size:11px;">
								Seal No : <?php echo $rtninfo[0]['cont_seal_number'];?>
							</td>
							
							<!--td style="font-size:11px;">
								Shift/Date : <?php echo $rtninfo[0]['shift_name'];?>
							</td-->
							<!--td>
								Arrival Date : <?php echo $rsltBerth[0]['ata']?>
							</td-->
						</tr>
						<tr>
							<!--td colspan="3" style="font-size:12px;">
								Freight Forwarder : <?php echo $rtninfo[1]['Notify_name'];?>
							</td-->
							<!--td>
								Size: <?php echo $rtninfo[0]['cont_size'];?>
							</td-->
							<!--td>
								Shift/Date : <?php echo $rtninfo[0]['shift_name'];?>
							</td-->
						</tr>
						<tr>
							<td style="font-size:11px;">
								
							</td>
							<td style="font-size:11px;">
								
							</td>
							
						</tr>
						<!--tr>
							<td>
								Seal No : <?php echo $rtninfo[0]['cont_seal_number'];?>
							</td>
						</tr-->
						<tr>
							<!--td style="font-size:11px;">
								Shed No : <?php echo @$rtninfo[0]['shed_yard'];?>
							</td-->
							<td style="font-size:11px;">
								Berth Op : <?php echo @$berthOp;?>
							</td>
							<td style="font-size:11px;">
								Status : LCL <?php ?>
							</td>
							<td style="font-size:11px;">
								Unstuffing Date : <?php echo @$rtninfo[0]['wr_date'];?> 
							</td>
							
						</tr>
						<tr>
							<td colspan="2" style="font-size:11px;">
								Remarks if any :
							</td>
							<td style="font-size:11px;">
								Physical Tally : <?php echo $physicalTallySheetNumber;?>
							</td>
						</tr>
						<tr>
							<td align="center" colspan="3" style="font-size:10px;">
								<p>(State of contents of the unstuffed pkgs unkown to CPA & CPA is not responsible for content)</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table border="1" align="center" width="100%">
						<tr>
							<th>Sl No.</th>
							<!--th>Vessel Name</th>
							<th>REG No</th>
							<th>Container</th>
							<th>Unstuffing Date</th-->
							<th>Marks</th>							
							<th>Description</th>
							<th>BL No</th>
							<th>IMDG</th>
							<th>QNTY.</th>
							<th>Unit</th>
							<th>Weight</th>
							<th>Storage Area</th>
						</tr>
						<?php 
							$i=1;
							if($j==0)
								$init=0;
							else
								$init=$init+6;							
							
							$sqltallyreport = "SELECT * FROM ( SELECT shed_tally_info.igm_detail_id AS id,BL_No AS master_BL_No, Description_of_Goods,
							shed_tally_info.import_rotation AS Import_Rotation_No,BL_No,igm_detail_container.cont_number,
							cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
							FROM shed_tally_info
							LEFT JOIN igm_details ON igm_details.id=shed_tally_info.igm_detail_id
							LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
							WHERE shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$container' 
							AND shed_tally_info.igm_detail_id IS NOT NULL
							) tbl 
							union all
							select * from 
							(SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,
							Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,
							Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
							FROM igm_supplimentary_detail 
							LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
							WHERE Import_Rotation_No='$rotation' AND cont_number='$container'
							) tbl1
							union
							select * from (SELECT shed_tally_info.igm_sup_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,
							shed_tally_info.cont_number, cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,
							NotifyDesc FROM shed_tally_info 
							LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
							LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
							WHERE shed_tally_info.import_rotation='$rotation' and shed_tally_info.cont_number='$container' and BL_NO is null
							)tbl2 LIMIT $init,6";
							$restallyreport = mysqli_query($con_cchaportdb,$sqltallyreport);
							while($rtntallyreport=mysqli_fetch_object($restallyreport)) {
								
								$strrcv = "select sum(rcv_pack) as rcv_pack,sum(flt_pack) as flt_pack,sum(flt_pack_loc) as flt_pack_loc,sum(loc_first) as loc_first,sum(total_pack) as total_pack,rcv_unit,shed_yard,shed_loc,
								remarks,imdg,sum(weight) as weight 
								from shed_tally_info 
								where igm_sup_detail_id='$rtntallyreport->id' AND update_by='pshed' 
								group by rcv_unit,shed_loc
								order by id";
								$resrcv = mysqli_query($con_cchaportdb,$strrcv);
								$totRow = mysqli_num_rows($resrcv);
								$imdgVal="";
								// $strrcv1 = "select sum(rcv_pack) as rcv_pack,sum(flt_pack) as flt_pack,sum(flt_pack_loc) as flt_pack_loc,sum(loc_first) as loc_first,sum(total_pack) as total_pack,rcv_unit,shed_yard,
								// shed_loc,remarks,imdg,sum(weight) as weight							
								// from shed_tally_info 
								// where igm_sup_detail_id='$rtntallyreport->id' AND update_by='pshed'
								// group by rcv_unit,shed_loc
								// order by id  limit 0,1";
								$strrcv1 = "select * from shed_tally_info where igm_sup_detail_id='$rtntallyreport->id' AND 
								shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$container'
								union 
								select * from shed_tally_info where igm_detail_id='$rtntallyreport->id'
								AND shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$container'";
								$resrcv1 = mysqli_query($con_cchaportdb,$strrcv1);
								while($rowrcv1 = mysqli_fetch_object($resrcv1))
								{
									$imdgVal = $rowrcv1->imdg;
								}
								
								$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
												WHERE igm_detail_id='$rtntallyreport->id'";
								$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
								$rowTotUnits = mysqli_fetch_object($resTotUnits);
								$rowTotal= $rowTotUnits->totRcvUnit;
								if($rowTotal!=0)
								{
									$rowTotal = $rowTotal;
									// $strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit FROM shed_tally_info 
											// where igm_detail_id='$rtntallyreport->id'";
									// $resUnits = mysqli_query($con_cchaportdb,$strUnits);
									// $rowUnits = mysqli_fetch_object($resUnits);
									// $rowTotal= $rowUnits->rcvUnit;
								}
								else
								{
									$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
									where igm_sup_detail_id='$rtntallyreport->id'";
									$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
									$rowTotUnits = mysqli_fetch_object($resTotUnits);
									$rowTotal= $rowTotUnits->totRcvUnit;
								}
						?>
						<tr>
							<td align="center" rowspan="<?php echo $rowTotal; ?>"><?php echo $i;?></td>
							<td align="center" rowspan="<?php echo $rowTotal; ?>">
								<?php
									$marksVal = "";
									// $strMarks = "select distinct(actual_marks) as actual_marks from shed_tally_info 
									// where shed_tally_info.igm_sup_detail_id='$rtntallyreport->id' and shed_tally_info.update_by='pshed'";
									$strMarks = "select distinct(actual_marks) as actual_marks from shed_tally_info 
															where igm_sup_detail_id='".$rtntallyreport->id."' 
															AND shed_tally_info.import_rotation='$rotation' 
															AND shed_tally_info.cont_number='$container'
															union 
															select distinct(actual_marks) as actual_marks from shed_tally_info 
															where igm_detail_id='".$rtntallyreport->id."'
															AND shed_tally_info.import_rotation='$rotation' 
															AND shed_tally_info.cont_number='$container'";
									$resMarks = mysqli_query($con_cchaportdb,$strMarks);
									$rowMarks = mysqli_fetch_object($resMarks);
									if(isset($rowMarks->actual_marks))
										{
											echo substr($rowMarks->actual_marks, 0, 50);
										}
									else
										{
											echo substr($rtntallyreport->Pack_Marks_Number, 0, 50);
										}
								?>
							</td>
							<td align="center" rowspan="<?php echo $rowTotal; ?>">
								<?php echo substr($rtntallyreport->Description_of_Goods, 0, 30);?>
							</td>
							<td align="center" rowspan="<?php echo $rowTotal; ?>">
								<?php echo substr($rtntallyreport->BL_No, 0, 30);?>
							</td>
							<td align="center" rowspan="<?php echo $rowTotal; ?>"><?php echo $imdgVal;?></td>
							<?php
								$forIgmDtl = 0;
								$forIgmSupDtl = 0;
								
								$strTotUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totRcvUnit FROM shed_tally_info 
								where igm_sup_detail_id='$rtntallyreport->id'";
								$resTotUnits = mysqli_query($con_cchaportdb,$strTotUnits);
								$rowTotUnits = mysqli_fetch_object($resTotUnits);
								$rowTotal= $rowTotUnits->totRcvUnit;
								
								$strUnits = "SELECT DISTINCT(rcv_unit) AS rcvUnit 
											FROM shed_tally_info where igm_sup_detail_id='$rtntallyreport->id'
											UNION
											SELECT DISTINCT(rcv_unit) AS rcvUnit 
											FROM shed_tally_info WHERE igm_detail_id='$rtntallyreport->id'";
								$resUnits = mysqli_query($con_cchaportdb,$strUnits);
								while($rowUnits = mysqli_fetch_object($resUnits)){
											$forIgmSupDtl++;
							?>
							<td align="center" style="font-size:9px;">
								<?php 
									$totPackPerUnit = "";
									$strTotPack = "SELECT SUM(total_pack) AS totPack FROM shed_tally_info 
									WHERE rcv_unit='$rowUnits->rcvUnit' AND igm_sup_detail_id='$rtntallyreport->id'";											
									$resTotPack = mysqli_query($con_cchaportdb,$strTotPack);
									while($rowTotPack = mysqli_fetch_object($resTotPack)){
										$totPackPerUnit = $rowTotPack->totPack;
									}
									if($totPackPerUnit=="")
									{
										$strTotPack = "SELECT SUM(total_pack) AS totPack FROM shed_tally_info 
										WHERE rcv_unit='$rowUnits->rcvUnit' AND igm_detail_id='$rtntallyreport->id'";
										$resTotPack = mysqli_query($con_cchaportdb,$strTotPack);
										while($rowTotPack = mysqli_fetch_object($resTotPack)){
											$totPackPerUnit = $rowTotPack->totPack;
										}
									}
									echo $totPackPerUnit;
									 $subTotQty = $totPackPerUnit;
									 $totQty = $totQty+$subTotQty;
								?>
							</td>
							<td align="center" style="font-size:9px;"><?php echo $rowUnits->rcvUnit."<br>";?></td>
							<td align="center" style="font-size:9px;">
								<?php 
									$totWeightPerUnit = "";
									$strTotWght = "SELECT SUM(weight) AS weight FROM shed_tally_info 
									WHERE rcv_unit='$rowUnits->rcvUnit' AND igm_sup_detail_id='$rtntallyreport->id'";											
									$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
									while($rowTotWght = mysqli_fetch_object($resTotWght)){
										$totWeightPerUnit = $rowTotWght->weight;
									}
									if($totWeightPerUnit=="")
									{
										$strTotWght = "SELECT SUM(weight) AS weight FROM shed_tally_info 
										WHERE rcv_unit='$rowUnits->rcvUnit' AND igm_detail_id='$rtntallyreport->id'";
										$resTotWght = mysqli_query($con_cchaportdb,$strTotWght);
										while($rowTotWght = mysqli_fetch_object($resTotWght)){
											$totWeightPerUnit = $rowTotWght->weight;
										}
									}
									echo $totWeightPerUnit;
								?>
							</td>
							<td align="center" style="font-size:9px;">
								<?php 
									$shedLocPerUnit = "";
									$strShedLoc = "SELECT shed_loc FROM shed_tally_info 
									WHERE rcv_unit='$rowUnits->rcvUnit' AND igm_sup_detail_id='$rtntallyreport->id'";											
									$resShedLoc = mysqli_query($con_cchaportdb,$strShedLoc);
									while($rowShedLoc = mysqli_fetch_object($resShedLoc)){
										$shedLocPerUnit = $rowShedLoc->shed_loc;
									}
									if($shedLocPerUnit=="")
									{
										$strShedLoc = "SELECT shed_loc FROM shed_tally_info 
										WHERE rcv_unit='$rowUnits->rcvUnit' AND igm_detail_id='$rtntallyreport->id'";
										$resShedLoc = mysqli_query($con_cchaportdb,$strShedLoc);
										while($rowShedLoc = mysqli_fetch_object($resShedLoc)){
											$shedLocPerUnit = $rowShedLoc->shed_loc;
										}
									}
									echo $shedLocPerUnit;
									//echo $rowTotWght->shed_loc;
								?>
							</td>
								</tr>
							<tr>
							<?php }  
							// $strTotalUnits = "SELECT COUNT(DISTINCT(rcv_unit)) AS totalUnit FROM shed_tally_info 
									// where igm_sup_detail_id='$rtntallyreport->id'";
							// $resTotalUnits = mysqli_query($con_cchaportdb,$strTotalUnits);
							// $totalUnitsRow = mysqli_fetch_object($resTotalUnits);
							// $totalUnits = $totalUnitsRow->totalUnit;
							$strTotalUnits = "SELECT DISTINCT(rcv_unit) AS totalUnit FROM shed_tally_info 
									where igm_sup_detail_id='$rtntallyreport->id'
									UNION
									SELECT DISTINCT(rcv_unit) AS totalUnit FROM shed_tally_info 
									where igm_detail_id='$rtntallyreport->id'";
							$resTotalUnits = mysqli_query($con_cchaportdb,$strTotalUnits);
							$totalUnitsRow = mysqli_fetch_object($resTotalUnits);
							$totalUnits = count($totalUnitsRow);
							if($totalUnits==0) { ?>
								<td align="center"></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="center"></td>
								</tr>
							<tr>
							<?php } ?>
						</tr>
						<?php 
							$i++; 
							}							
						if($j==$counter-1) { ?>
								<tr>
									<td colspan="5" align="center"><font style="border:0px solid;"><b>Total</b></font></td>
									<td align="center"><?php echo $totQty;?></td>
									<td colspan="3"></td>
								</tr>
						<?php } ?>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width="100%">
						<tr>
							<td>
								Berth Operator
							</td>
							<td>
								Freight Forwarder
							</td>
							<td>
								CPA
							</td>
						</tr>
						<tr>
							<td valign="middle">
								<table>
									<tr>
										<td>
											Signature :
										</td>
										<td>
											<img id="sig_security" src="<?php echo IMG_PATH.'Signature/'.$signature_path_berth; ?>" height=50 width=50 />
										</td>
									</tr>
								</table>
								 
							</td>
							<td>
								<table>
									<tr>
										<td>
											Signature :
										</td>
										<td>
											<img id="sig_security" src="<?php echo IMG_PATH.'Signature/'.$signature_path_freight; ?>" height=50 width=50 />
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table>
									<tr>
										<td>
											Signature :
										</td>
										<td>
											<img id="sig_security" src="<?php echo IMG_PATH.'Signature/'.$signature_path_cpa; ?>" height=50 width=50 />
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								Name :
							</td>
							<td>
								N :
							</td>
							<td>
								N :
							</td>
						</tr>
						<tr>
							<td>
								Designation :
							</td>
							<td>
								D :
							</td>
							<td>
								D :
							</td>
						</tr>
						<tr>
							
									<td>
										Tally Supervisor Signature: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                
									</td>
									<td>
										Name :  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                            
									</td>
									<td>
										Designation : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</td>
								</tr>
						
						<tr>
							<td colspan="3">
								<table>
									<tr>
										<td>
											Note: 
										</td>
										<td>
											1. For remarks
										</td>
									</tr>
									<tr>
										<td>
										</td>
										<td>
											(a) The type of defects of containers such as dented, outward damage, door defective etc.
										</td>
									</tr>
									<tr>
										<td>
										</td>
										<td>
											(b) The type of defects of lock and seal such as broken, missing etc, and mention of survey or any other action in this connection.
										</td>
									</tr>
									<tr>
										<td>
										</td>
										<td>
											(c) The type of Containers other than the usual General purpose Container such as flats, half heights open top etc. to be indicated as observed prior to opening of the containers for unstuffing.
										</td>
									</tr>
									<tr>
										<td>
										</td>
										<td>
											2. Proper remarks should be given for qualified packages and in case the contents of the pkgs, is suspected to be effected survey should be conducted with the Steamer Agent, no special cargo to be received with remarks but without survey.
										</td>
									</tr>
									<tr>
										<td>
										</td>
										<td>
											3. In case unstuffing is not complete then container is to be locked and sealed jointly with the Steamer Agent.
										</td>
									</tr>
								</table>	
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
		</div>
	</body>
</html>

<?php 
}
?>
