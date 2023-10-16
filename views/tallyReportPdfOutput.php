<?php 
//echo "count : ".count($rtnCounter);
include_once("FrontEnd/mydbPConnection.php");

//for manual unstuffing date starts----------
	$strUnstuffingDt = "";							
	$lastUnstuffingDt = "";							
	$strUnstuffingDt = "SELECT id,wr_date FROM shed_tally_info 
						WHERE import_rotation='$rotation' AND cont_number='$container'
						ORDER BY id DESC LIMIT 1";								
	$resUnstuffingDt = mysqli_query($con_cchaportdb,$strUnstuffingDt);
	$numRowUnstuffingDt = mysqli_num_rows($resUnstuffingDt);
	while($rowUnstuffingDt = mysqli_fetch_object($resUnstuffingDt))
	{
		$lastUnstuffingDt = $rowUnstuffingDt->wr_date;
	}
//for manual unstuffing date ends------------

$strBerth = "SELECT DISTINCT berthOp AS rtnValue FROM shed_tally_info WHERE  shed_tally_info.import_rotation='$rotation' 
AND shed_tally_info.cont_number='$container' AND berthOp IS NOT NULL AND berthOp!='' ORDER BY shed_tally_info.id DESC LIMIT 1";
$berthOp = @$this->bm->dataReturnDb1($strBerth);

$strYardName = "SELECT DISTINCT shed_yard AS rtnValue FROM shed_tally_info WHERE shed_tally_info.import_rotation='$rotation' 
AND shed_tally_info.cont_number='$container' AND shed_yard IS NOT NULL ORDER BY shed_tally_info.id DESC LIMIT 1";
$yardName = $this->bm->dataReturnDb1($strYardName);					

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
$counter=ceil(count($rtnCounter)/10);

$totQty = 0;
$subTotQty = 0;

for($j=0;$j<$counter;$j++)
{ 
?>
<html>
	<body>
	<?php if($j<$counter-1) { ?>
		<div style="position: relative;PAGE-BREAK-AFTER: always;">
		<?php } else {?>
		<div style="position: relative;PAGE-BREAK-AFTER: avoid;">
		<?php }?>
		<table width="100%" cellpadding="0" border="0">
			<tr height="100px">
				<td align="center" valign="middle">
					<img height="70px" src="<?php echo IMG_PATH;?>cpa_logo.png">
					<h1 style="font-size:20px;">Chittagong Port Authority</h1>
					<h3 style="font-size:13px;">Unstuffing Tally of Container</h3>
				</td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="0" align="center">
						<tr>
							<td width="35%" style="font-size:12px;">
								Vessel Name: <?php echo $rtninfo[0]['Vessel_Name'];?>
							</td>
							<td width="27%" style="font-size:12px;">
								Rot No : <?php echo $rotation;?>
							</td>
							<td width="37%" align="left" style="border:1px solid black;font-size:12px;" >
								<!--div style="border:1px solid black; width:250px">Tally Sheet No: <b><?php echo $section."-".$rtninfo[0]['tls_no'];?></b></div-->
								
								T.S.N.: <b><?php $subtsn = $j+1; echo $rtninfo[0]['tally_sheet_number']."-".$subtsn;?></b>
							</td>
						</tr>
						<tr>
							<!--td style="font-size:12px;">
								Shipping Agent : <?php echo $rtninfo[0]['mlocode'];?>
							</td-->
							
							<td style="font-size:12px;">
								Arrival Date : <?php echo $rsltBerth[0]['ATA']?>
							</td>
							<td style="font-size:12px;">
								Shed No : <?php echo @$yardName;?>
							</td>
						</tr>
						<tr>
							<!--td style="font-size:12px;">
								Handling Contractor : <?php echo $rsltBerth[0]['BERTHOP']?>
							</td-->
							<td style="font-size:12px;">
								Container No : <?php echo strtoupper($container);?>
							</td>
							<td style="font-size:12px;">
								Size: <?php echo $container_size;?>
							</td>
							<td style="font-size:12px;">
								Seal No : <?php echo $seal_no;?>
							</td>
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
							
							<td style="font-size:12px;">
								Berth Op : <?php echo $berthOp;?>
							</td>
							<td style="font-size:12px;">
								Unstuff Date : <?php if($numRowUnstuffingDt==0) echo ""; else echo $lastUnstuffingDt;?> 
							</td>
							<td style="font-size:12px;">
								Physical Tally NO : <?php echo $physicalTallySheetNumber;?>
							</td>
						</tr>
						<!--tr>
							<td>
								Seal No : <?php echo $rtninfo[0]['cont_seal_number'];?>
							</td>
						</tr-->
						<tr>
							<td colspan="2" style="font-size:12px;">
								Remarks if any :
							</td>
							
						</tr>
						<tr>
							<td align="center" colspan="3" style="font-size:12px;">
								<p>(State of contents of the unstuffed pkgs unkown to CPA & CPA is not responsible for content)</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table border="1" align="center" cellspacing="0" cellpadding="0" width="100%" style="font-size:11px">
						<tr>
							<th rowspan="2">Sl No.</th>
							<th rowspan="2">BL No</th>
							<th rowspan="2">Marks</th>							
							<th rowspan="2">Description</th>
							<th colspan="2">Good Condition</th>
							<th colspan="2">Broken Pkgs.<br>conditions and Nos.</th>
							<!--th rowspan="2">Broken Pkgs.<br>conditions and Nos.</th-->
							<th rowspan="2">Cargo Location</th>
							<th rowspan="2">Total No of Pkgs</th>
							<th rowspan="2">Rcv Unit</th>							
							<th rowspan="2">Shift</th>
							<th rowspan="2">Remarks</th>
						</tr>
						<tr>
							<th>W/R House</th>
							<th>Loc Fast</th>
							<th>W/R House</th>
							<th>Loc Fast</th>
						</tr>
						
							<?php
								include_once("mydbPConnection.php");
							//	for($i=0;$i<count($rtntallyreport);$i++) { 
							//$init=0;
							if($j==0)
								$init=0;
							else
								$init=$init+10;
								//echo $init;
							 $sqltallyreport = "SELECT * FROM ( SELECT shed_tally_info.igm_detail_id AS id,BL_No AS master_BL_No, Description_of_Goods,
							shed_tally_info.import_rotation AS Import_Rotation_No,BL_No,igm_detail_container.cont_number,
							cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
							FROM shed_tally_info
							LEFT JOIN igm_details ON igm_details.id=shed_tally_info.igm_detail_id
							LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
							WHERE shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$container' 
							AND shed_tally_info.igm_detail_id IS NOT NULL
							) tbl1 
							
							union all
							select * from (SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,
							cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
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
							WHERE shed_tally_info.import_rotation='$rotation' and shed_tally_info.cont_number='$container'
							AND shed_tally_info.igm_sup_detail_id IS NOT NULL and BL_NO is null
							)tbl1 LIMIT $init,10";
							//echo $sqltallyreport;
							$restallyreport = mysqli_query($con_cchaportdb,$sqltallyreport);
						
							$i=1;
							//	for($i=0;$i<5;$i++) { 
							while($rtntallyreport=mysqli_fetch_object($restallyreport)) { 
							
								$strrcv = "SELECT * FROM(SELECT shed_tally_info.id, SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack,SUM(flt_pack_loc) AS flt_pack_loc,SUM(loc_first) AS loc_first,SUM(total_pack) AS total_pack,rcv_unit,shed_loc,remarks FROM shed_tally_info 
								WHERE igm_sup_detail_id='$rtntallyreport->id' AND import_rotation='$rotation' AND cont_number='$container'  GROUP BY rcv_unit,shed_loc
								UNION ALL 
								SELECT shed_tally_info.id, SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack,SUM(flt_pack_loc) AS flt_pack_loc,SUM(loc_first) AS loc_first,SUM(total_pack) AS total_pack,rcv_unit,shed_loc,remarks FROM shed_tally_info 
								WHERE igm_detail_id='$rtntallyreport->id' AND import_rotation='$rotation' AND cont_number='$container'  GROUP BY rcv_unit,shed_loc) 
								AS tbl ORDER BY id";
								$resrcv = mysqli_query($con_cchaportdb,$strrcv);
								$totRow = mysqli_num_rows($resrcv);
								
								$strrcv1 = "SELECT * FROM (
								SELECT shed_tally_info.id, SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack,SUM(flt_pack_loc) AS flt_pack_loc,SUM(loc_first) AS loc_first, SUM(total_pack) AS total_pack,rcv_unit,shed_loc,remarks,shift_name FROM shed_tally_info 
								WHERE igm_sup_detail_id='$rtntallyreport->id' AND import_rotation='$rotation' AND cont_number='$container' GROUP BY rcv_unit,shed_loc

								UNION ALL 

								SELECT shed_tally_info.id, SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack,SUM(flt_pack_loc) AS flt_pack_loc,SUM(loc_first) AS loc_first, SUM(total_pack) AS total_pack,rcv_unit,shed_loc,remarks, shift_name FROM shed_tally_info 
								WHERE igm_detail_id='$rtntallyreport->id' AND import_rotation='$rotation' AND cont_number='$container' GROUP BY rcv_unit,shed_loc )
								 AS tbl
								ORDER BY id  LIMIT 0,1";
								$resrcv1 = mysqli_query($con_cchaportdb,$strrcv1);
							?>
								<tr>									
									<td align="center" <?php if($totRow>1) { ?>rowspan="<?php echo $totRow; ?>" <?php } ?>>
										<?php echo $i++;?>
									</td>
									<!--td align="center">
										<?php //echo substr($rtntallyreport->Pack_Marks_Number, 0, 50);?>
									</td-->
									<td style="font-size:9px;" align="center" <?php if($totRow>1) { ?>rowspan="<?php echo $totRow; ?>" <?php } ?>>
										<?php echo $rtntallyreport->BL_No;?>
									</td>
									<td style="font-size:9px;" align="center" <?php if($totRow>1) { ?>rowspan="<?php echo $totRow; ?>" <?php } ?>>
										<?php 
											 $strMarks = "SELECT DISTINCT(actual_marks) AS actual_marks FROM shed_tally_info 
											WHERE igm_detail_id='$rtntallyreport->id' AND import_rotation='$rotation' AND cont_number='$container'
											UNION 
											select distinct(actual_marks) as actual_marks from shed_tally_info 
											where igm_sup_detail_id='$rtntallyreport->id' AND import_rotation='$rotation' AND cont_number='$container'";
											$resMarks = mysqli_query($con_cchaportdb,$strMarks);
											$rowMarks = mysqli_fetch_object($resMarks);
										?>
										<?php 
											if($rowMarks!=null)
												if($rowMarks->actual_marks!="" or $rowMarks->actual_marks!=null) 
													echo $rowMarks->actual_marks; 
												else 
													echo substr($rtntallyreport->Pack_Marks_Number, 0, 50);?>
									</td>											
									<td style="font-size:9px;" align="center" <?php if($totRow>1) { ?>rowspan="<?php echo $totRow; ?>" <?php } ?>>
										<?php echo substr($rtntallyreport->Description_of_Goods, 0, 30);?>
									</td> 
									<?php 
									// $rowrcv1 = mysqli_fetch_object($resrcv1);
									
									$rcv_pack = "";
									$loc_first = "";
									$flt_pack = "";
									$flt_pack_loc = "";
									$shed_loc = "";
									$total_pack = "";
									$rcv_unit = "";
									$remarks = "";
									$shift_name = "";
									while($rowrcv1 = mysqli_fetch_object($resrcv1)){
										$rcv_pack = $rowrcv1->rcv_pack;
										$loc_first = $rowrcv1->loc_first;
										$flt_pack = $rowrcv1->flt_pack;
										$flt_pack_loc = $rowrcv1->flt_pack_loc;
										$shed_loc = $rowrcv1->shed_loc;
										$total_pack = $rowrcv1->total_pack;
										$rcv_unit = $rowrcv1->rcv_unit;
										$remarks = $rowrcv1->remarks;
										$shift_name = $rowrcv1->shift_name;
										
										$subTotQty = $rowrcv1->total_pack;
										$totQty = $totQty+$subTotQty;
									}
									
									?>
										<td style="font-size:9px;" align="center">
											<?php echo $rcv_pack;?>
										</td>
										<td style="font-size:9px;" align="center">
											<?php echo $loc_first;?>
										</td>	
										<td style="font-size:9px;" align="center">
											<?php echo $flt_pack;?>
										</td>
										<td style="font-size:9px;" align="center">
											<?php echo $flt_pack_loc;?>
										</td>
										<td style="font-size:9px;" align="center">
											<?php 
												if ($loc_first>0 && $rcv_pack>0)
													echo "L/F, ".$shed_loc; 
												else if($loc_first>0 && $rcv_pack==0)
													echo "L/F";
												else if($loc_first==0 && $rcv_pack>0)
													echo $shed_loc;
											?>
											
										</td>	
										<td style="font-size:9px;" align="center">
											<?php echo $total_pack;?>
										</td>
										<td style="font-size:9px;" align="center">
											<?php echo $rcv_unit;?>
										</td>
										<td style="font-size:9px;" align="center">
											<?php echo $shift_name;?>
										</td>
										<td style="font-size:9px;" align="center">
											<?php echo $remarks;?>
										</td>
										
									<?php
									//}
									?>
								</tr>
								<?php 
								if($totRow>1)
								{
								$lim = $totRow-1;
								$strrcv2 = "SELECT * FROM (SELECT shed_tally_info.id, SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack,SUM(flt_pack_loc) AS flt_pack_loc,SUM(loc_first) AS loc_first,total_pack,rcv_unit,shed_loc,remarks,shift_name 
								FROM shed_tally_info 
								WHERE igm_sup_detail_id='$rtntallyreport->id'
								AND import_rotation='$rotation' AND cont_number='$container' GROUP BY rcv_unit,shed_loc

								UNION ALL 

								SELECT shed_tally_info.id, SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack,SUM(flt_pack_loc) AS flt_pack_loc,SUM(loc_first) AS loc_first,total_pack,rcv_unit,shed_loc,remarks,shift_name 
								FROM shed_tally_info 
								WHERE igm_detail_id='$rtntallyreport->id'
								AND import_rotation='$rotation' AND cont_number='$container' GROUP BY rcv_unit,shed_loc)
								AS tbl 
								ORDER BY id limit 1,$lim";
								
							
								$resrcv2 = mysqli_query($con_cchaportdb,$strrcv2);
								
								$rcv_pack = "";
								$loc_first = "";
								$flt_pack = "";
								$flt_pack_loc = "";
								$shed_loc = "";
								$total_pack = "";
								$rcv_unit = "";
								$remarks = "";
								$shift_name = "";
								while($rowrcv2 = mysqli_fetch_object($resrcv2))
								{
									$rcv_pack = $rowrcv2->rcv_pack;
									$loc_first = $rowrcv2->loc_first;
									$flt_pack = $rowrcv2->flt_pack;
									$flt_pack_loc = $rowrcv2->flt_pack_loc;
									$shed_loc = $rowrcv2->shed_loc;
									$total_pack = $rowrcv2->total_pack;
									$rcv_unit = $rowrcv2->rcv_unit;
									$remarks = $rowrcv2->remarks;
									$shift_name = $rowrcv2->shift_name;
									
									$totQty = $totQty+$total_pack;
								?>
								<tr>
									<td style="font-size:9px;" align="center">
										<?php echo $rcv_pack;?>
									</td>
									<td style="font-size:9px;" align="center">
										<?php echo $loc_first;?>
									</td>	
									<td style="font-size:9px;" align="center">
										<?php echo $flt_pack;?>
									</td>
									<td style="font-size:9px;" align="center">
											<?php echo $flt_pack_loc;?>
									</td>
									<td style="font-size:9px;" align="center">
										<?php 
										if ($loc_first>0 && $rcv_pack>0)
											echo "L/F, ".$shed_loc; 
										else if($loc_first>0 && $rcv_pack==0)
											echo "L/F";
										else if($loc_first==0 && $rcv_pack>0)
											echo $shed_loc;?>
									</td>	
									<td style="font-size:9px;" align="center">
										<?php echo $total_pack;?>
									</td>
									<td style="font-size:9px;" align="center">
										<?php echo $rcv_unit;?>
									</td>									
									<td style="font-size:9px;" align="center">
										<?php echo $shift_name;?>
									</td>
									<td style="font-size:9px;" align="center">
										<?php echo $remarks;?>
									</td>
								</tr>								
								<?php
									$total_2=$total_2+$total_pack;
								}
								}
								$total_1=$total_1+$total_pack;
								?>
								
							<?php
							}
							//	$init=$init+5;
							if($j==$counter-1)
							{
							?>
							
								<tr>
									<td colspan="9"></td>
									<!--td align="center"><?php echo $total_1+$total_2;?></td-->
									<td align="center"><?php echo $totQty;?></td>
									<td colspan="3"></td>
								</tr>
							<?php
							}
							?>
							
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width="100%">
						<tr>
							<td>
								Berth Operator<hr>
							</td>
							<td>
								Freight Forwarder<hr>
							</td>
							<td>
								CPA<hr>
							</td>
						</tr>
						<?php
						$berth_exchange_done_status = 0;
						$berth_exchange_done_by = "";
						$berth_exchange_done_at = "";
						$ff_exchange_done_status = 0;
						$ff_exchange_done_by = "";
						$ff_exchange_done_at = "";
						$cpa_exchange_done_status = 0;
						$cpa_exchange_done_by = "";
						$cpa_exchange_done_at = "";
						for($i=0;$i<count($rsltSig);$i++)
						{
							$berth_exchange_done_status=$rsltSig[$i]['berth_exchange_done_status'];
							$berth_exchange_done_by=$rsltSig[$i]['berth_exchange_done_by'];
							$berth_exchange_done_at=$rsltSig[$i]['berth_exchange_done_at'];
							$ff_exchange_done_status=$rsltSig[$i]['ff_exchange_done_status'];
							$ff_exchange_done_by=$rsltSig[$i]['ff_exchange_done_by'];
							$ff_exchange_done_at=$rsltSig[$i]['ff_exchange_done_at'];
							$cpa_exchange_done_status=$rsltSig[$i]['cpa_exchange_done_status'];
							$cpa_exchange_done_by=$rsltSig[$i]['cpa_exchange_done_by'];
							$cpa_exchange_done_at=$rsltSig[$i]['cpa_exchange_done_at'];
						}
						?>
						<tr>
							<td valign="middle">
								<table>
									<tr>
										<td>
											Confirmed By :
										</td>
										<td>
											<?php echo $berth_exchange_done_by; ?>
											<!--img id="sig_security" src="<?php echo IMG_PATH.'Signature/'.$signature_path_berth; ?>" height=50 width=50 /-->
										</td>
									</tr>
									<tr>
										<td>
											Confirmed At :
										</td>
										<td>
											<?php echo $berth_exchange_done_at; ?>
											<!--img id="sig_security" src="<?php echo IMG_PATH.'Signature/'.$signature_path_berth; ?>" height=50 width=50 /-->
										</td>
									</tr>
								</table>
								 
							</td>
							<td>
								<table>
									<tr>
										<td>
											Confirmed By :
										</td>
										<td>
											<?php echo $ff_exchange_done_by; ?>
											<!--img id="sig_security" src="<?php echo IMG_PATH.'Signature/'.$signature_path_berth; ?>" height=50 width=50 /-->
										</td>
									</tr>
									<tr>
										<td>
											Confirmed At :
										</td>
										<td>
											<?php echo $ff_exchange_done_at; ?>
											<!--img id="sig_security" src="<?php echo IMG_PATH.'Signature/'.$signature_path_berth; ?>" height=50 width=50 /-->
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table>
									<tr>
										<td>
											Confirmed By :
										</td>
									
										<td>
											<?php echo $cpa_exchange_done_by; ?>
											<!--img id="sig_security" src="<?php echo IMG_PATH.'Signature/'.$signature_path_berth; ?>" height=50 width=50 /-->
										</td>
									</tr>
									<tr>
										<td>
											Confirmed At :
										</td>
										<td>
											<?php echo $cpa_exchange_done_at; ?>
											<!--img id="sig_security" src="<?php echo IMG_PATH.'Signature/'.$signature_path_berth; ?>" height=50 width=50 /-->
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
							<td colspan="3">
							<table>
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
							</table>
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
