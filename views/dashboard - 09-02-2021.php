<?php if($_SESSION['org_Type_id']==67){ ?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Dashboard</h2>
	</header>

	<div class="row">
		<?php 
		include("mydbPConnection.php");
		$truckToGetInQuery = "SELECT COUNT(do_truck_details_entry.truck_id) as rtnValue
		FROM do_truck_details_entry
		INNER JOIN verify_info_fcl ON verify_info_fcl.verify_number=do_truck_details_entry.verify_number
		LEFT JOIN do_information ON do_information.verify_no=do_truck_details_entry.verify_number
		WHERE gate_in_status = '0' AND DATE(do_truck_details_entry.last_update)=DATE(NOW())";

		$truckToGetInRslt = mysqli_query($con_cchaportdb,$truckToGetInQuery);
		$truckToGetIn = 0;
		while($getInRslt = mysqli_fetch_object($truckToGetInRslt)){
			$truckToGetIn = $getInRslt->rtnValue;
		}

		$truckInsidePortQuery = "SELECT COUNT(do_truck_details_entry.truck_id) AS rtnValue
		FROM do_truck_details_entry
		INNER JOIN verify_info_fcl ON verify_info_fcl.verify_number=do_truck_details_entry.verify_number
		LEFT JOIN do_information ON do_information.verify_no=do_truck_details_entry.verify_number
		WHERE gate_in_status = '1' AND gate_out_status='0' AND DATE(do_truck_details_entry.last_update)=DATE(NOW())";

		$truckInsidePortRslt = mysqli_query($con_cchaportdb,$truckInsidePortQuery);
		$truckInsidePort = 0;
		while($insideRslt = mysqli_fetch_object($truckInsidePortRslt)){
			$truckInsidePort = $insideRslt->rtnValue;
		}

		$truckOutsidePortQuery = "SELECT COUNT(do_truck_details_entry.truck_id) AS rtnValue
		FROM do_truck_details_entry
		INNER JOIN verify_info_fcl ON verify_info_fcl.verify_number=do_truck_details_entry.verify_number
		LEFT JOIN do_information ON do_information.verify_no=do_truck_details_entry.verify_number
		WHERE gate_out_status='1' AND DATE(do_truck_details_entry.last_update)=DATE(NOW())";

		$truckOutsidePortRslt = mysqli_query($con_cchaportdb,$truckOutsidePortQuery);
		$truckOutsidePort = 0;
		while($outsideRslt = mysqli_fetch_object($truckOutsidePortRslt)){
			$truckOutsidePort = $outsideRslt->rtnValue;
		}

		$totalTruck = $truckToGetIn+$truckInsidePort+$truckOutsidePort;
		
	?>
		<div class="col-md-6 col-lg-12 col-xl-6">
			<div class="row">
				<div class="col-md-12 col-lg-6 col-xl-6">
					<section class="panel panel-featured-left panel-featured-primary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-default" style="background-color:white;">
										<i class="fa fa-truck" style="color:red;"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Total Truck</h4>
										<div class="info">
											<strong class="amount"><a href="<?php echo site_url('Report/truckReport/total'); ?>"><?php echo $totalTruck;?></a></strong>
											<span class="text-primary"></span>
										</div>
									</div>
									<div class="summary-footer">
										<a href="#" class="text-muted text-uppercase"></a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="col-md-12 col-lg-6 col-xl-6">
					<section class="panel panel-featured-left panel-featured-secondary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-default" style="background-color:white;">
										<i class="fa fa-truck" style="color:#bfbc02;"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Truck to Gate in Pending</h4>
										<div class="info">
											<strong class="amount">
												<a href="<?php echo site_url('Report/truckReport/gateIn'); ?>">
													<?php echo $truckToGetIn;?>
												</a>
											</strong>
										</div>
									</div>
									<div class="summary-footer">
										<a class="text-muted text-uppercase"></a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="col-md-12 col-lg-6 col-xl-6">
					<section class="panel panel-featured-left panel-featured-tertiary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-default" style="background-color:white;">
										<i class="fa fa-truck" style="color:#81db58;"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Truck Working Inside</h4>
										<div class="info">
											<strong class="amount"><a href="<?php echo site_url('Report/truckReport/insidePort'); ?>"><?php echo $truckInsidePort;?></a></strong>
										</div>
									</div>
									<div class="summary-footer">
										<a class="text-muted text-uppercase"></a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="col-md-12 col-lg-6 col-xl-6">
					<section class="panel panel-featured-left panel-featured-quartenary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-default" style="background-color:white;">
										<i class="fa fa-truck" style="color:#077322;"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Truck Gate out</h4>
										<div class="info">
											<strong class="amount"><a href="<?php echo site_url('Report/truckReport/gateOut'); ?>"><?php echo $truckOutsidePort;?></a></strong>
										</div>
									</div>
									<div class="summary-footer">
										<a class="text-muted text-uppercase"></a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			&nbsp;
		</div>
		<div class="col-md-8">
			<section class="panel">
				<div class="panel-body" style="background-color:white;">
	
					<!-- Flot: Bars -->
					<div class="chart chart-md" id="flotBars"></div>
					<script type="text/javascript">
	
						var flotBarsData = [
							["Total Truck", <?php echo $totalTruck; ?>],
							["Truck Working Inside", <?php echo $truckInsidePort; ?>],
							["Truck To Gate In Pending", <?php echo $truckToGetIn;?>],
							["Gate Out", <?php echo $truckOutsidePort;?>]
						];
	
						// See: assets/javascripts/ui-elements/examples.charts.js for more settings.
	
					</script>
	
				</div>
			</section>
		</div>	
	
	</div>
</section>
<?php }?>
<?php if($_SESSION['org_Type_id']==2){ ?>

	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Dashboard</h2>
		</header>
	 <div class="content">
		<div class="content_resize">
			<div class="row">
				<div class="col-md-12">
					<div class="toggle" data-plugin-toggle data-plugin-options='{ "isAccordion": true }'>
						<section class="toggle active">
							<label>Assignment List</label>
							<div class="toggle-content">
								<div class="panel-body">
										<div id="table-scroll" class="panel-body table-responsive">
											<?php if(count($rslt_assignmentList)==0) { ?>
												<div class="row">
													<div class="col-md-12">
														<p>
															<h4 align="center"><b>You have no pending assignment.</b></h4>
														</p>
													</div>
												</div>
											<?php } else { ?>
											<!-- <div class="row">
												<div class="col-md-12">
													<p>
														<h4 align="center"><b>Today's Assignment List</b></h4>
													</p>
												</div>
											</div> -->
											<table class="table table-bordered table-responsive table-hover table-striped mb-none">
												<?php
												if($msg != "")
												{
												?>
												<tr>
													<td colspan="13" align="center"><?php echo $msg; ?></td>
												</tr>
												<?php
												}
												?>
												<tr class="gridDark" align="center">												
													<td align="center"><b>SL</b></td>
													<td align="center"><b>Container</b></td>
													<td align="center"><b>Size</b></td>
													<td align="center"><b>Height</b></td>	
													<td align="center"><b>Status</b></td>	
													<td align="center"><b>Rotation</b></td>
													<td align="center"><b>BL</b></td>
													<td align="center"><b>Assignment Type</b></td>
													<td align="center"><b>Assignment Date</b></td>
													
													<!--th>B/E</th>
													<th>Verify No</th>
													<th>DO Form</th-->
													<?php
													if($org_Type_id==2 or $org_Type_id==3)
													{
													?>
													<!--td align="center"><b>Truck Qty</b></td>
													<td align="center"><b>Action</b></td-->
													<td align="center"><b>Truck Detail</b></td>
													<?php
													}
													if($org_Type_id==3)
													{
													?>
													<td align="center"><b>Remarks</b></td>
													<?php } 
													if($org_Type_id==62)
													{
													?>
													
													<td align="center"><b>Action</b></td>
													<?php } ?>
													<td align="center"><b>Custom Status</b></td>
												</tr>
												<?php 
												
												include('mydbPConnection.php');
												$blNo="";
												$beNo="";
												$verifyNo="";
												$truckQty=0;
												
												for($i=0;$i<count($rslt_assignmentList);$i++)
												{
													$contstatus=$rslt_assignmentList[$i]['cont_status'];

													// bl - start
													$sql_blNo="SELECT BL_no,Bill_of_Entry_No 
													FROM igm_details 
													INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
													WHERE Import_Rotation_No='".$rslt_assignmentList[$i]['rot_no']."' AND cont_number='".$rslt_assignmentList[$i]['cont_no']."'";
													
													$rslt_blNo=mysqli_query($con_cchaportdb,$sql_blNo);
													$beNo="";
													while($row_blNo=mysqli_fetch_object($rslt_blNo))
													{
														$blNo=$row_blNo->BL_no;
														$beNo=$row_blNo->Bill_of_Entry_No;
													}
													// bl - end
													
													// be info - start						
													
													$sql_beInfo = "SELECT office_code,reg_no,reg_date
													FROM sad_info						
													WHERE reg_no='$beNo'";
													$rslt_beInfo=mysqli_query($con_cchaportdb,$sql_beInfo);
													
													$office_code="";							
													$reg_date = "";

													while($row_beInfo=mysqli_fetch_object($rslt_beInfo))
													{
														$office_code=$row_beInfo->office_code;							
														$reg_date=$row_beInfo->reg_date;
													}						
													// be info - end
													
													// truck - start
													if($contstatus=="FCL")
													{
														// $sql_verifyNo = "SELECT verify_number,no_of_truck FROM verify_info_fcl WHERE be_no='$beNo'";		
														
														// $rslt_verifyNo = mysqli_query($con_cchaportdb,$sql_verifyNo);
														
														// while($row_verifyNo = mysqli_fetch_object($rslt_verifyNo))
														// {
															// $verifyNo = $row_verifyNo->verify_number;
															// $truckQty = $row_verifyNo->no_of_truck;								
														// }
														
														$sql_igmDtlContId = "SELECT igm_detail_container.id
														FROM igm_details
														INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
														WHERE Import_Rotation_No='".$rslt_assignmentList[$i]['rot_no']."' AND cont_number='".$rslt_assignmentList[$i]['cont_no']."'";
													// echo "<br>";	
													// echo "<br>";	
														
														$rslt_igmDtlContId = mysqli_query($con_cchaportdb,$sql_igmDtlContId);
														
														$igmDtlContId = "";
														while($row_igmDtlContId = mysqli_fetch_object($rslt_igmDtlContId))
														{
															$igmDtlContId = $row_igmDtlContId->id;
														}
														
														$sql_qtyTruck = "SELECT no_of_truck FROM verify_info_fcl WHERE igm_detail_cont_id='$igmDtlContId'";
														$rslt_qtyTruck = mysqli_query($con_cchaportdb,$sql_qtyTruck);
														// echo "<br>";
														// echo "<br>";
														$truckQty = "";
														while($row_qtyTruck = mysqli_fetch_object($rslt_qtyTruck))
														{
															$truckQty = $row_qtyTruck->no_of_truck;
														}

														
													}
													else if($contstatus=="LCL")
													{
														$sql_verifyNo = "SELECT shed_tally_info.id,shed_tally_info.verify_number,verify_other_data.shed_tally_id,verify_other_data.no_of_truck
														FROM verify_other_data
														INNER JOIN shed_tally_info ON verify_other_data.shed_tally_id=shed_tally_info.id  
														WHERE import_rotation='".$rslt_assignmentList[$i]['rot_no']."' AND cont_number='".$rslt_assignmentList[$i]['cont_no']."' 
														LIMIT 1";
														
														$rslt_verifyNo = mysqli_query($con_cchaportdb,$sql_verifyNo);
														
														while($row_verifyNo = mysqli_fetch_object($rslt_verifyNo))
														{
															$verifyNo = $row_verifyNo->verify_number;
															$truckQty = $row_verifyNo->no_of_truck;
														}
													}
													// truck - end
													
													// do - start
													// $sql_chkBE = "SELECT COUNT(Bill_of_Entry_No) AS cnt
													// FROM igm_details
													// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
													// WHERE Import_Rotation_No='".$rslt_assignmentList[$i]['rot_no']."' AND cont_number='".$rslt_assignmentList[$i]['cont_no']."'";
													// $rslt_chkBE = mysql_query($sql_chkBE);
													
													// while($row_chkBE = mysql_fetch_object($rslt_chkBE))
													// {
														// $cntBE = $row_chkBE->cnt;
													// }
													
													$sql_doImg = "SELECT do_image_loc FROM do_image WHERE be_no = '$beNo'";
													$rslt_doImg = mysqli_query($con_cchaportdb,$sql_doImg);
													$do_image_loc="";
													while($row_doImg = mysqli_fetch_object($rslt_doImg))
													{
														$do_image_loc = $row_doImg->do_image_loc;
													}
													// do - end
												?>
												<tr class="gridLight" align="center">
													<!--td style="color:red"><?php echo $rtnContainerList[$i]['tally_sheet_number'];?></td>
													<td id="contTdId_<?php echo $i; ?>"><?php echo $rtnContainerList[$i]['cont_number'];?></td>						
													<td id="rotTdId_<?php echo $i; ?>"><?php echo $rtnContainerList[$i]['import_rotation'];?></td>
													<td><?php echo $rtnContainerList[$i]['rcv_pack'];?></td>
													<td><?php echo $rtnContainerList[$i]['flt_pack'];?></td>
													<td><?php echo $rtnContainerList[$i]['loc_first'];?></td>
													<td><?php echo $rtnContainerList[$i]['shed_loc'];?></td>
													<td><?php echo $rtnContainerList[$i]['shed_yard'];?></td>
													<td><?php echo $rtnContainerList[$i]['wr_date'];?></td-->
													
													
													<td><?php echo $i+1; ?></td>
													<td><?php echo $rslt_assignmentList[$i]['cont_no']; ?></td>
													<td><?php echo $rslt_assignmentList[$i]['size']; ?></td>
													<td><?php echo $rslt_assignmentList[$i]['height']; ?></td>
													<td><?php echo $rslt_assignmentList[$i]['cont_status']; ?></td>
													<td><?php echo $rslt_assignmentList[$i]['rot_no']; ?></td>
													<td><?php echo $blNo; ?></td>
													<td><?php echo $rslt_assignmentList[$i]['mfdch_value']; ?></td>
													<td><?php echo $rslt_assignmentList[$i]['assignmentDate']; ?></td>
													<!--td>
														<a href="<?php echo site_url('Report/xml_conversion_action/1/'.$office_code.'/'.$beNo.'/'.$reg_date); ?>" target="_blank"><?php echo $beNo; ?></a>
													</td>
													<td>
														<a href="<?php echo site_url('ShedBillController/bilSearchByVerifyNumber/'.$verifyNo); ?>" target="_blank"><?php echo $verifyNo; ?></a>							
													</td>
													<td>
														<?php
														if($do_image_loc!="")
														{	// show uploaded document
														?>
														<a href="<?php echo BASE_PATH."resources/do_image/".$do_image_loc; ?>" class="login_button" target="_blank">DO</a>
														<?php
														}
														?>
													</td-->
													<?php
													if($org_Type_id == 2 or $org_Type_id == 3)
													{
													?>
										
													<td>
															<!--input type="hidden" name="verifyNo" id="verifyNo" value="<?php echo $verifyNo; ?>" /-->
														
														<!--form name="truckEntryForm" id="truckEntryForm" method="post" action="<?php echo site_url('ShedBillController/bilSearchByVerifyNumber'); ?>"-->		
														<form name="truckEntryForm" id="truckEntryForm" method="post" action="<?php echo site_url('ShedBillController/cnfTruckEntryForm'); ?>">								
														
												
															<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rslt_assignmentList[$i]['rot_no']; ?>" />
															<input type="hidden" name="contNo" id="contNo" value="<?php echo $rslt_assignmentList[$i]['cont_no']; ?>" />
															<input type="hidden" name="cont_status" id="cont_status" value="<?php echo $rslt_assignmentList[$i]['cont_status']; ?>" />
															<input type="hidden" name="assignmentType" id="assignmentType" value="<?php echo $rslt_assignmentList[$i]['mfdch_value']; ?>" />
															<input style="width:100px" type="submit" name="truckEntry" id="truckEntry" value="Truck Entry" 
																class="btn btn-xs btn-primary" />
														</form>						
													</td>
													<?php
													}
													if($org_Type_id==3)
													{
													
													?>
													<td>
													<?php 
													$cont_no=$rslt_assignmentList[$i]['cont_no'];
													$rot_no=$rslt_assignmentList[$i]['rot_no'];
													$custom_remarks=$rslt_assignmentList[$i]['custom_remarks'];
													?>
														<!--input type="hidden" name="verifyNo" id="verifyNo" value="<?php echo $verifyNo; ?>" /-->
														<!--form name="truckEntryForm" id="truckEntryForm" method="post" action="<?php echo site_url('ShedBillController/bilSearchByVerifyNumber'); ?>"-->								
															<input type="hidden" name="rotNoRemarks" id="rotNoRemarks" value="<?php echo $rot_no; ?>" />
															<input type="hidden" name="contNoRemarks" id="contNoRemarks" value="<?php echo $cont_no; ?>" />
															<input type="hidden" name="cont_status" id="cont_status" value="<?php echo $rslt_assignmentList[$i]['cont_status']; ?>" />
															<input style="width:100px" type="submit"  value="Remarks" class="btn btn-xs btn-primary"  
																onclick="upload_remarks('<?php echo $cont_no;?>','<?php echo $rot_no;?>')" />
													</td>
													<?php } 
														if($org_Type_id==62) { 
													?>
													<td>
														<?php
															include('mydbPConnection.php');
															$thisRot = $rslt_assignmentList[$i]['rot_no'];								
															$thisCont = $rslt_assignmentList[$i]['cont_no'];								
															$keepDownSt = "";
															$chkKeepDownSt="SELECT verify_info_fcl.keepdown_st 
															FROM igm_detail_container 
															LEFT JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
															LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id
															LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
															LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
															LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
															WHERE igm_details.Import_Rotation_No='$thisRot' AND igm_detail_container.cont_number='$thisCont'";
															
															$resKeepDownSt=mysqli_query($con_cchaportdb,$chkKeepDownSt);								
															while($getKeepDownSt=mysqli_fetch_object($resKeepDownSt))
															{
																$keepDownSt=$getKeepDownSt->keepdown_st;
															}
															if($keepDownSt!=1){								
														?>
														<form method="post" action="<?php echo site_url('ShedBillController/updateKeepDownStatus'); ?>" >
															<input type="hidden" name="changeState" id="changeState" value="changeState" />
															<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rslt_assignmentList[$i]['rot_no']; ?>" />
															<input type="hidden" name="contNo" id="contNo" value="<?php echo $rslt_assignmentList[$i]['cont_no']; ?>" />
															<input type="hidden" name="cont_status" id="cont_status" value="<?php echo $rslt_assignmentList[$i]['cont_status']; ?>" />								
															<input type="submit" name="keepDown" id="keepDown" value="KeepDown" 
																class="btn btn-xs btn-primary"/>
														</form>
															<?php } else { ?>
																<h6 class="h6 mt-none mb-sm" style="color:red;"><b>Keep Down Done !</b></h6>
															<?php } ?>
													</td>
														<?php } ?>
													<td>
														<?php 	$custom_remarks=$rslt_assignmentList[$i]['custom_remarks'];
																if($custom_remarks!=null){ ?> <input style="width:70px" type="submit" class="btn btn-xs btn-danger" value="Blocked"  /> <?php } else {?> <input style="width:70px" type="submit" class="btn btn-xs btn-success" value="Open"  /> <?php } ?>
													</td>
													
												</tr>
												<?php }?>
											</table>
											<?php }?>
										</div>
									</div>
							</div>
						</section>
						
						<section class="toggle">
							<label>Truck List</label>
							<div class="toggle-content">
								<div class="panel-body">
										<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
											<thead>
												<tr>
													<th>Visit ID</th>
													<th>Rotation</th>
													<th>Cont. No.</th>
													<th>Truck ID</th>
													<th>Gate</th>
													<th>Driver Name</th>
													<th>Driver Gate Pass</th>
													<th>Assistant Name</th>
													<th>Assistant Gate Pass</th>
													<th>Token</th>
												</tr>
											</thead>
											<?php
												include("mydbPConnection.php");
												$login_id = $this->session->userdata("login_id");
												// if($login_id == 'devcf'){
												// 	$truckQuery = "SELECT id AS trucVisitId,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass FROM do_truck_details_entry WHERE gate_in_status=0 AND DATE(last_update) = DATE(NOW()) ORDER BY trucVisitId ASC";
												// }else{
												// 	$truckQuery = "SELECT id AS trucVisitId,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass FROM do_truck_details_entry WHERE gate_in_status=0 AND DATE(last_update) = DATE(NOW()) AND update_by = '$login_id' ORDER BY trucVisitId ASC";
												// }

												$truckQuery = "SELECT id AS trucVisitId,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass FROM do_truck_details_entry WHERE gate_in_status=0 AND update_by = '$login_id'";
												
												$truckResult = mysqli_query($con_cchaportdb,$truckQuery);
												
												$imp_rot = "";
												$cont_no = "";
												$truck_id = "";
												$gate_no = "";
												$driver_name = "";
												$driver_gate_pass = "";
												$assistant_name = "";
												$assistant_gate_pass = "";
												$trucVisitId = "";
												$i=0;
												while($row = mysqli_fetch_object($truckResult)){
													$imp_rot = $row->import_rotation;
													$cont_no = $row->cont_no;
													$truck_id = $row->truck_id;
													$gate_no = $row->gate_no;
													$driver_name = $row->driver_name;
													$driver_gate_pass = $row->driver_gate_pass;
													$assistant_name = $row->assistant_name;
													$assistant_gate_pass = $row->assistant_gate_pass;
													$trucVisitId = $row->trucVisitId;
													$i++;
											?>
											
											<tr>
												<td><?php echo $trucVisitId; ?></td>
												<td><?php echo $imp_rot; ?></td>
												<td><?php echo $cont_no; ?></td>
												<td><?php echo $truck_id; ?></td>
												<td><?php echo $gate_no; ?></td>
												<td><?php echo $driver_name; ?></td>
												<td><?php echo $driver_gate_pass; ?></td>
												<td><?php echo $assistant_name; ?></td>
												<td><?php echo $assistant_gate_pass; ?></td>
												<td>
													<form method="POST" action="<?php echo site_url("ShedBillController/truckEntranceApplicationPDF") ?>" target="_blank">
														<input type="hidden" name="rot_no" id="rot_no" value="<?php echo $imp_rot; ?>"/>
														<input type="hidden" name="cont_no" id="cont_no" value="<?php echo $cont_no; ?>"/>
														<input type="hidden" name="trucVisitId" id="trucVisitId" value="<?php echo $trucVisitId; ?>"/>
														<input type="submit" class="btn btn-xs btn-primary" name="" id="" value="Print"/>
													</form>
												</td>
											</tr>
											
											<?php
												}
											?>

										</table>
									</div>
							</div>
						</section>
					</div>
				</div>
			</div>
			
		  </div>
		  <div class="clr"></div>
		</div>
	  </div>
	</section>
<?php } else { ?>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Dashboard</h2>
		</header>
	</section>
<?php } ?>