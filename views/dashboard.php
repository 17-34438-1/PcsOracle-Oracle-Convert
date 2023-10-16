<script type="text/javascript">
	var sessionID ="<?php echo $_SESSION['org_Type_id']?>";
	if(sessionID==67 || sessionID==62 || sessionID==75){
	    setInterval(displayclock, 500);
	}

	function displayclock(){
		var time = new Date();
		var hrs = time.getHours();
		var min = time.getMinutes();
		var sec = time.getSeconds();
		var meridiem = "AM";
		//var meridiem = "PM";
		if(hrs > 12)
		{
			hrs = hrs-12;
		}
		if(hrs => 12)
		{
			meridiem = "PM";
			//meridiem = "AM";
		}else{
			meridiem = "AM";
		}
		if(hrs == 0)
		{
			hrs = 12;
		}
		if(hrs < 10)
		{
			hrs = '0' + hrs;
		}
		if(min < 10)
		{
			min = '0' + min;
		}
		if(sec < 10)
		{
			sec = '0' + sec;
		}
		document.getElementById("clock").innerHTML = hrs + ':' + min + ':' + sec + ' ' + meridiem;
	}
</script>
<?php include('mydbPConnection.php'); ?>
<?php include("mydbPConnectionn4.php"); ?>
<?php include("dbOracleConnection.php");?>

<?php if($_SESSION['org_Type_id']==67 || $_SESSION['org_Type_id']==62 || $_SESSION['org_Type_id']==75)
{ ?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Dashboard</h2>
	</header>
	<h4 class="mt-none"><b>Date & Time : <?php echo date("d-m-Y"); ?> <span id="clock"> </span></b></h4>
	<div class="row">
		<?php 
			// include("mydbPConnection.php");
			
			$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
			$date = $dt->format('Y-m-d');
			//echo $dateTime = $dt->format('Y-m-d H:i:s');
			$meridiemVal = $dt->format('A');
			
			$fromDtTime = "";
			$toDtTime = "";
			
			$todayDt = "";
			$strTodayDt = "SELECT DATE(NOW()) AS tdt";
			$resTodayDt = mysqli_query($con_cchaportdb,$strTodayDt);
			while($dtRes = mysqli_fetch_object($resTodayDt)){
				$todayDt = $dtRes->tdt;
			}
			
			$tomorrowDt = "";
			$strTomorrowDt = "select (CURDATE() + INTERVAL 1 DAY) as tomorrowDt";
			$resTomorrowDt = mysqli_query($con_cchaportdb,$strTomorrowDt);
			while($tomorrowDtRes = mysqli_fetch_object($resTomorrowDt)){
				$tomorrowDt = $tomorrowDtRes->tomorrowDt;
			}
			
			$prevDt = "";
			$strPrevDt = "select (CURDATE() - INTERVAL 1 DAY) as previousDt";
			$resPrevDt = mysqli_query($con_cchaportdb,$strPrevDt);
			while($prevDtRes = mysqli_fetch_object($resPrevDt)){
				$prevDt = $prevDtRes->previousDt;
			}
			
			$currentHour = 0;
			$strCurHour = "select hour(now()) as curHour";
			$resCurHour = mysqli_query($con_cchaportdb,$strCurHour);
			while($curHourRes = mysqli_fetch_object($resCurHour)){
				$currentHour = $curHourRes->curHour;
			}
			
			$dateCondition = "";
			if($meridiemVal=="AM" && ($currentHour>=00 && $currentHour<=07))
			{
				$fromDtTime = $prevDt." 08:00:00";
				$toDtTime = $todayDt." 07:59:59";
				$dateCondition = " (do_truck_details_entry.last_update BETWEEN '$fromDtTime' and '$toDtTime')";
			}
			else
			{
				$fromDtTime = $todayDt." 08:00:00";
				$toDtTime = $tomorrowDt." 07:59:59";
				$dateCondition = " (do_truck_details_entry.last_update BETWEEN '$fromDtTime' and '$toDtTime')";
			}
			
			/*$truckToGetInQuery = "SELECT COUNT(do_truck_details_entry.truck_id) as rtnValue
			FROM do_truck_details_entry
			INNER JOIN verify_info_fcl ON verify_info_fcl.verify_number=do_truck_details_entry.verify_number
			LEFT JOIN do_information ON do_information.verify_no=do_truck_details_entry.verify_number
			WHERE gate_in_status = '0' AND DATE(do_truck_details_entry.last_update)=DATE(NOW())";
			*/
			
			$truckToGetInQuery = "SELECT COUNT(do_truck_details_entry.truck_id) as rtnValue
			FROM do_truck_details_entry
			WHERE gate_in_status = '0' AND (paid_status = '1' OR paid_status = '2') AND".$dateCondition; 
			/*DATE(do_truck_details_entry.last_update)=DATE(NOW())";*/

			$truckToGetInRslt = mysqli_query($con_cchaportdb,$truckToGetInQuery);
			$truckToGetIn = 0;
			while($getInRslt = mysqli_fetch_object($truckToGetInRslt)){
				$truckToGetIn = $getInRslt->rtnValue;
			}

			//Truck Paid Query
			$truckPaidQuery = "SELECT COUNT(do_truck_details_entry.truck_id) as rtnValue
			FROM do_truck_details_entry
			WHERE gate_in_status = '0' AND paid_status = '1' AND".$dateCondition; 
			/*DATE(do_truck_details_entry.last_update)=DATE(NOW())";*/

			$truckPaidRslt = mysqli_query($con_cchaportdb,$truckPaidQuery);

			$truckPaid = 0;
			while($paidRslt = mysqli_fetch_object($truckPaidRslt)){
				$truckPaid = $paidRslt->rtnValue;
			}

			//Truck Not Paid Query

			$truckNotPaidQuery = "SELECT COUNT(do_truck_details_entry.truck_id) as rtnValue
			FROM do_truck_details_entry
			WHERE gate_in_status = '0' AND paid_status = '2' AND".$dateCondition; 
			/*DATE(do_truck_details_entry.last_update)=DATE(NOW())";*/

			$truckNotPaidRslt = mysqli_query($con_cchaportdb,$truckNotPaidQuery);

			$truckNotPaid = 0;
			while($NotpaidRslt = mysqli_fetch_object($truckNotPaidRslt)){
				$truckNotPaid = $NotpaidRslt->rtnValue;
			}

			/*$truckInsidePortQuery = "SELECT COUNT(do_truck_details_entry.truck_id) AS rtnValue
			FROM do_truck_details_entry
			INNER JOIN verify_info_fcl ON verify_info_fcl.verify_number=do_truck_details_entry.verify_number
			LEFT JOIN do_information ON do_information.verify_no=do_truck_details_entry.verify_number
			WHERE gate_in_status = '1' AND gate_out_status='0' AND DATE(do_truck_details_entry.last_update)=DATE(NOW())";
			*/
			$truckInsidePortQuery = "SELECT COUNT(do_truck_details_entry.truck_id) AS rtnValue
			FROM do_truck_details_entry
			WHERE gate_in_status = '1' AND gate_out_status='0' AND".$dateCondition; 
			/*DATE(do_truck_details_entry.last_update)=DATE(NOW())";*/

			$truckInsidePortRslt = mysqli_query($con_cchaportdb,$truckInsidePortQuery);
			$truckInsidePort = 0;
			while($insideRslt = mysqli_fetch_object($truckInsidePortRslt)){
				$truckInsidePort = $insideRslt->rtnValue;
			}

			/*$truckOutsidePortQuery = "SELECT COUNT(do_truck_details_entry.truck_id) AS rtnValue
			FROM do_truck_details_entry
			INNER JOIN verify_info_fcl ON verify_info_fcl.verify_number=do_truck_details_entry.verify_number
			LEFT JOIN do_information ON do_information.verify_no=do_truck_details_entry.verify_number
			WHERE gate_out_status='1' AND DATE(do_truck_details_entry.last_update)=DATE(NOW())";
			*/
			$truckOutsidePortQuery = "SELECT COUNT(do_truck_details_entry.truck_id) AS rtnValue
			FROM do_truck_details_entry
			WHERE gate_out_status='1' AND (do_truck_details_entry.gate_out_time BETWEEN '$fromDtTime' and '$toDtTime')"; 
			/*DATE(do_truck_details_entry.last_update)=DATE(NOW())";*/

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
												<a href="<?php echo site_url('Report/truckReportPending/'.$truckPaid.'/'.$truckNotPaid); ?>">
													<?php echo $truckNotPaid + $truckPaid;?>
												</a>
												<!-- <font size = '4'>
													<a href="<?php //echo site_url('Report/truckReport/paid'); ?>">
														<?php //echo "Paid : ".$truckPaid;?>
													</a>
												</font>
												<br>
												<font size = '4'>
													<a href="<?php //echo site_url('Report/truckReport/notpaid'); ?>">
														<?php //echo "Unpaid : ".$truckNotPaid;?>
													</a>
												</font> -->
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

<?php } else if($_SESSION['org_Type_id']==2){ ?>

	<!-- C&F starts here -->

	<!-- <section role="main" class="content-body">
		<header class="page-header">
			<h2>Dashboard</h2>
		</header>
	 <div class="content">
		<div class="content_resize">
			<div class="row">
				<div class="col-md-12 text-center">
					<video width="640" height="320" controls>
						<source src="<?php //echo ASSETS_PATH;?>video/c&f_tutorial.mp4" type="video/mp4">
						
						Your browser does not support the video tag.
					</video>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="toggle" data-plugin-toggle data-plugin-options='{ "isAccordion": true }'>
					<label><b>Please complete your online Gate pass application for truck/covered van entry for goods delivery from NCY yard. TOS(CTMS),CPA</b></label>
						<section class="toggle active">
							<label>Assignment List</label>
							<div class="toggle-content">
								<div class="panel-body table-responsive">
									<div class="panel-body">			
										<form class="form-horizontal" id="truckDtlForm" name="truckDtlForm" method="post" action="<?php //echo site_url('Report/assignmentListForm_2'); ?>" onsubmit="return chkConfirm()">
																				
											<div class="form-group">
												
												<div class="col-md-2">																
													&nbsp;&nbsp;												
												</div>
												<div class="col-md-6">																
													<div class="input-group mb-md">
														<span class="input-group-addon span_width">Container <span class="required">*</span></span>
														<input class="form-control" name="contSearch" id="contSearch" required />
														<span class="input-group-btn">
														<button type="submit" value="Search" name="search" class="btn btn-primary" > Search </button>
													</span>
													</div>													
												</div>
												
											</div>	
										</form>
									</div>
								</div>
								<br>
								<div class="panel-body">
									<div id="table-scroll" class="panel-body table-responsive">
										<?php //if(count($rslt_assignmentList)==0) { ?>
											<div class="row">
												<div class="col-md-12">
													<p>
														<h4 align="center"><b>You have no pending assignment.</b></h4>
													</p>
												</div>
											</div>
										<?php //} else { ?>

										   -- inactive --
										<div class="row">
											<div class="col-md-12">
												<p>
													<h4 align="center"><b>Today's Assignment List</b></h4>
												</p>
											</div>
										</div> 
											-- inactive --

										<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
											<?php
											// if($msg != "")
											// {
											?>
											<tr>
												<td colspan="13" align="center"><?php echo $msg; ?></td>
											</tr>
											<?php
											// }
											?>
											<thead>
												<tr class="gridDark" align="center">												
													<td align="center"><b>SL</b></td>
													<td align="center"><b>Container</b></td>
													<td align="center"><b>Size</b></td>
													<td align="center"><b>Height</b></td>	
													<td align="center"><b>Status</b></td>	
													<td align="center"><b>Rotation</b></td>
													<td align="center"><b>BL</b></td>
													<td align="center"><b>Block</b></td>
													<td align="center"><b>Assignment Type</b></td>
													<td align="center"><b>Assignment Date</b></td>
													
													-- inactive --

													<th>B/E</th>
													<th>Verify No</th>
													<th>DO Form</th>

													-- inactive --

													<?php
													// if($org_Type_id==2 or $org_Type_id==3)
													// {
													?>
													-- inactive --

													<td align="center"><b>Truck Qty</b></td>
													<td align="center"><b>Action</b></td>

													-- inactive --

													<td align="center"><b>Truck Detail</b></td>
													<?php
													//}
													// if($org_Type_id==3)
													// {
													?>
													<td align="center"><b>Remarks</b></td>
													<?php //} 
													// if($org_Type_id==62)
													// {
													?>
													
													<td align="center"><b>Action</b></td>
													<?php //} ?>
													<td align="center"><b>Status</b></td>
												</tr>
											</thead>
											<?php 
											
											// include('mydbPConnection.php');
											// $blNo="";
											// $beNo="";
											// $verifyNo="";
											// $truckQty=0;
											
											// for($i=0;$i<count($rslt_assignmentList);$i++)
											// {
												// $contstatus=$rslt_assignmentList[$i]['cont_status'];

												// bl - start
												// $sql_blNo="SELECT BL_no,Bill_of_Entry_No 
												// FROM igm_details 
												// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
												// WHERE Import_Rotation_No='".$rslt_assignmentList[$i]['rot_no']."' AND cont_number='".$rslt_assignmentList[$i]['cont_no']."'";
												
												// $rslt_blNo=mysqli_query($con_cchaportdb,$sql_blNo);
												// $beNo="";
												// while($row_blNo=mysqli_fetch_object($rslt_blNo))
												// {
												// 	$blNo=$row_blNo->BL_no;
												// 	$beNo=$row_blNo->Bill_of_Entry_No;
												// }
												// bl - end
												
												// be info - start						
												
												// $sql_beInfo = "SELECT office_code,reg_no,reg_date
												// FROM sad_info						
												// WHERE reg_no='$beNo'";
												// $rslt_beInfo=mysqli_query($con_cchaportdb,$sql_beInfo);
												
												// $office_code="";							
												// $reg_date = "";

												// while($row_beInfo=mysqli_fetch_object($rslt_beInfo))
												// {
												// 	$office_code=$row_beInfo->office_code;							
												// 	$reg_date=$row_beInfo->reg_date;
												// }						
												// be info - end
												
												// truck - start
												// if($contstatus=="FCL")
												// {
													// $sql_verifyNo = "SELECT verify_number,no_of_truck FROM verify_info_fcl WHERE be_no='$beNo'";		
													
													// $rslt_verifyNo = mysqli_query($con_cchaportdb,$sql_verifyNo);
													
													// while($row_verifyNo = mysqli_fetch_object($rslt_verifyNo))
													// {
														// $verifyNo = $row_verifyNo->verify_number;
														// $truckQty = $row_verifyNo->no_of_truck;								
													// }
													
													// $sql_igmDtlContId = "SELECT igm_detail_container.id
													// FROM igm_details
													// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
													// WHERE Import_Rotation_No='".$rslt_assignmentList[$i]['rot_no']."' AND cont_number='".$rslt_assignmentList[$i]['cont_no']."'";
												// echo "<br>";	
												// echo "<br>";	
													
													// $rslt_igmDtlContId = mysqli_query($con_cchaportdb,$sql_igmDtlContId);
													
													// $igmDtlContId = "";
													// while($row_igmDtlContId = mysqli_fetch_object($rslt_igmDtlContId))
													// {
													// 	$igmDtlContId = $row_igmDtlContId->id;
													// }
													
													// $sql_qtyTruck = "SELECT no_of_truck FROM verify_info_fcl WHERE igm_detail_cont_id='$igmDtlContId'";
													// $rslt_qtyTruck = mysqli_query($con_cchaportdb,$sql_qtyTruck);
													// echo "<br>";
													// echo "<br>";
													// $truckQty = "";
													// while($row_qtyTruck = mysqli_fetch_object($rslt_qtyTruck))
													// {
													// 	$truckQty = $row_qtyTruck->no_of_truck;
													// }

													
												// }
												// else if($contstatus=="LCL")
												// {
													// $sql_verifyNo = "SELECT shed_tally_info.id,shed_tally_info.verify_number,verify_other_data.shed_tally_id,verify_other_data.no_of_truck
													// FROM verify_other_data
													// INNER JOIN shed_tally_info ON verify_other_data.shed_tally_id=shed_tally_info.id  
													// WHERE import_rotation='".$rslt_assignmentList[$i]['rot_no']."' AND cont_number='".$rslt_assignmentList[$i]['cont_no']."' 
													// LIMIT 1";
													
													// $rslt_verifyNo = mysqli_query($con_cchaportdb,$sql_verifyNo);
													
													// while($row_verifyNo = mysqli_fetch_object($rslt_verifyNo))
													// {
													// 	$verifyNo = $row_verifyNo->verify_number;
													// 	$truckQty = $row_verifyNo->no_of_truck;
													// }
												//}
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
												
												// $sql_doImg = "SELECT do_image_loc FROM do_image WHERE be_no = '$beNo'";
												// $rslt_doImg = mysqli_query($con_cchaportdb,$sql_doImg);
												// $do_image_loc="";
												// while($row_doImg = mysqli_fetch_object($rslt_doImg))
												// {
												// 	$do_image_loc = $row_doImg->do_image_loc;
												// }
												// do - end
											?>
											<tr class="gridLight" align="center">
												-- inactive --
												<td style="color:red"><?php //echo $rtnContainerList[$i]['tally_sheet_number'];?></td>
												<td id="contTdId_<?php //echo $i; ?>"><?php //echo $rtnContainerList[$i]['cont_number'];?></td>						
												<td id="rotTdId_<?php //echo $i; ?>"><?php //echo $rtnContainerList[$i]['import_rotation'];?></td>
												<td><?php //echo $rtnContainerList[$i]['rcv_pack'];?></td>
												<td><?php //echo $rtnContainerList[$i]['flt_pack'];?></td>
												<td><?php //echo $rtnContainerList[$i]['loc_first'];?></td>
												<td><?php //echo $rtnContainerList[$i]['shed_loc'];?></td>
												<td><?php //echo $rtnContainerList[$i]['shed_yard'];?></td>
												<td><?php //echo $rtnContainerList[$i]['wr_date'];?></td>
												-- inactive --
												
												
												<td><?php //echo $i+1; ?></td>
												<td><?php //echo $rslt_assignmentList[$i]['cont_no']; ?></td>
												<td><?php //echo $rslt_assignmentList[$i]['size']; ?></td>
												<td><?php //echo $rslt_assignmentList[$i]['height']; ?></td>
												<td><?php //echo $rslt_assignmentList[$i]['cont_status']; ?></td>
												<td><?php //echo $rslt_assignmentList[$i]['rot_no']; ?></td>
												<td><?php //echo $blNo; ?></td>
												<td><?php //echo $rslt_assignmentList[$i]['Block_No']; ?></td>
												<td><?php //echo $rslt_assignmentList[$i]['mfdch_value']; ?></td>
												<td><?php //echo $rslt_assignmentList[$i]['assignmentDate']; ?></td>
												
												-- inactive --
												<td>
													<a href="<?php //echo site_url('Report/xml_conversion_action/1/'.$office_code.'/'.$beNo.'/'.$reg_date); ?>" target="_blank"><?php echo $beNo; ?></a>
												</td>
												<td>
													<a href="<?php //echo site_url('ShedBillController/bilSearchByVerifyNumber/'.$verifyNo); ?>" target="_blank"><?php echo $verifyNo; ?></a>							
												</td>
												<td>
													<?php
													//if($do_image_loc!="")
													//{	// show uploaded document
													?>
													<a href="<?php //echo BASE_PATH."resources/do_image/".$do_image_loc; ?>" class="login_button" target="_blank">DO</a>
													<?php
													//}
													?>
												</td>
												-- inactive --

												<?php
												// $cont_no=$rslt_assignmentList[$i]['cont_no'];
												// $rot_no=$rslt_assignmentList[$i]['rot_no'];

												// $result = $this->bm->chkBlockedContainerforTruckEntry($cont_no,$rot_no,$blNo);

												// $custom_block_status = "";
												// for($ij = 0; $ij<count($result);$ij++){
												// 	$custom_block_status = $result[$ij]['custom_block_st'];
												// }

												// if($org_Type_id == 2 or $org_Type_id == 3)
												// {
												?>
									
												<td>
													-- inactive --
													<input type="hidden" name="verifyNo" id="verifyNo" value="<?php //echo $verifyNo; ?>" />
													
													<form name="truckEntryForm" id="truckEntryForm" method="post" action="<?php //echo site_url('ShedBillController/bilSearchByVerifyNumber'); ?>">	

													-- inactive --

													<form name="truckEntryForm" id="truckEntryForm" method="post" action="<?php //echo site_url('ShedBillController/cnfTruckEntryForm'); ?>">								
													
											
														<input type="hidden" name="rotNo" id="rotNo" value="<?php //echo $rot_no; ?>" />
														<input type="hidden" name="contNo" id="contNo" value="<?php //echo $cont_no; ?>" />
														<input type="hidden" name="cont_status" id="cont_status" value="<?php //echo $rslt_assignmentList[$i]['cont_status']; ?>" />
														<input type="hidden" name="unit_gkey" id="unit_gkey" value="<?php //echo $rslt_assignmentList[$i]['unit_gkey']; ?>" />
														<input type="hidden" name="assignmentType" id="assignmentType" value="<?php //echo $rslt_assignmentList[$i]['mfdch_value']; ?>" />
														<input style="width:100px" type="submit" name="truckEntry" id="truckEntry" value="Truck Entry" 
															class="btn btn-xs btn-primary" <?php //if($custom_block_status == "DO_NOT_RELEASE"){echo "disabled";}?>/>
													</form>						
												</td>
												<?php
												//}
												// if($org_Type_id==3)
												// {
												
												?>
												<td>
												<?php 
													// $custom_remarks=$rslt_assignmentList[$i]['custom_remarks'];
												?>

													-- inactive --
													<input type="hidden" name="verifyNo" id="verifyNo" value="<?php //echo $verifyNo; ?>" />

													<form name="truckEntryForm" id="truckEntryForm" method="post" action="<?php //echo site_url('ShedBillController/bilSearchByVerifyNumber'); ?>">	
													-- inactive --

														<input type="hidden" name="rotNoRemarks" id="rotNoRemarks" value="<?php //echo $rot_no; ?>" />
														<input type="hidden" name="contNoRemarks" id="contNoRemarks" value="<?php //echo $cont_no; ?>" />
														<input type="hidden" name="cont_status" id="cont_status" value="<?php //echo $rslt_assignmentList[$i]['cont_status']; ?>" />
														<input style="width:100px" type="submit"  value="Remarks" class="btn btn-xs btn-primary"  
															onclick="upload_remarks('<?php //echo $cont_no;?>','<?php echo $rot_no;?>')" />
												</td>
												<?php //} 
													//if($org_Type_id==62) { 
												?>
												<td>
													<?php
														// include('mydbPConnection.php');
														// $thisRot = $rslt_assignmentList[$i]['rot_no'];								
														// $thisCont = $rslt_assignmentList[$i]['cont_no'];								
														// $keepDownSt = "";
														// $chkKeepDownSt="SELECT verify_info_fcl.keepdown_st 
														// FROM igm_detail_container 
														// LEFT JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
														// LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id
														// LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
														// LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
														// LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
														// WHERE igm_details.Import_Rotation_No='$thisRot' AND igm_detail_container.cont_number='$thisCont'";
														
														// $resKeepDownSt=mysqli_query($con_cchaportdb,$chkKeepDownSt);								
														// while($getKeepDownSt=mysqli_fetch_object($resKeepDownSt))
														// {
															// $keepDownSt=$getKeepDownSt->keepdown_st;
														//}
														//if($keepDownSt!=1){								
													?>
													<form method="post" action="<?php echo site_url('ShedBillController/updateKeepDownStatus'); ?>" >
														<input type="hidden" name="changeState" id="changeState" value="changeState" />
														<input type="hidden" name="rotNo" id="rotNo" value="<?php //echo $rslt_assignmentList[$i]['rot_no']; ?>" />
														<input type="hidden" name="contNo" id="contNo" value="<?php //echo $rslt_assignmentList[$i]['cont_no']; ?>" />
														<input type="hidden" name="cont_status" id="cont_status" value="<?php //echo $rslt_assignmentList[$i]['cont_status']; ?>" />								
														<input type="submit" name="keepDown" id="keepDown" value="KeepDown" 
															class="btn btn-xs btn-primary"/>
													</form>
														<?php //} else { ?>
															<h6 class="h6 mt-none mb-sm" style="color:red;"><b>Keep Down Done !</b></h6>
														<?php //} ?>
												</td>
													<?php //} ?>
												<td>
													<?php 	
															//$custom_remarks=$rslt_assignmentList[$i]['custom_remarks'];
															// if($custom_block_status=="DO_NOT_RELEASE"){ ?> <input style="width:70px" type="submit" class="btn btn-xs btn-danger" value="Blocked"  /> <?php //} else {?> <input style="width:70px" type="submit" class="btn btn-xs btn-success" value="Open"  /> <?php //} ?>
												</td>
												
											</tr>
											<?php //}?>
										</table>
										<?php //}?>
									</div>
								</div>
							</div>
						</section>
						
						<section class="toggle">
							<label>Truck List</label>
							<div class="toggle-content">
								<div class="panel-body table-responsive">
										<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
											<thead>
												<tr>
													<th rowspan="2"><nobr>Visit ID</nobr></th>
													<th rowspan="2">Rotation</th>
													<th rowspan="2">Cont. No.</th>
													<th rowspan="2"><nobr>Truck ID</nobr></th>
													<th rowspan="2">Gate</th>
													<th rowspan="2">Driver</th>
													<th rowspan="2">D.GatePass</th>
													<th rowspan="2">Assistant</th>
													<th rowspan="2">Ass.GatePass</th>
													<th rowspan="2"><nobr>Gate In Status</nobr></th>
													<th colspan="3" class="text-center">Confirmation</th>
													<th rowspan="2">Token</th>
												</tr>
												<tr>
													<th> Traffic </th>
													<th> Security </th>
													<th> C&F </th>
												</tr>
											</thead>
											<?php
												// include("mydbPConnection.php");
												//$login_id = $this->session->userdata("login_id");
												// if($login_id == 'devcf'){
												// 	$truckQuery = "SELECT id AS trucVisitId,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass FROM do_truck_details_entry WHERE gate_in_status=0 AND DATE(last_update) = DATE(NOW()) ORDER BY trucVisitId ASC";
												// }else{
												// 	$truckQuery = "SELECT id AS trucVisitId,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass FROM do_truck_details_entry WHERE gate_in_status=0 AND DATE(last_update) = DATE(NOW()) AND update_by = '$login_id' ORDER BY trucVisitId ASC";
												// }

												// $truckQuery = "SELECT id AS trucVisitId,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,cnf_chk_st,yard_security_chk_st,traffic_chk_st,gate_in_status,paid_status,load_st FROM do_truck_details_entry WHERE gate_out_status=0 AND update_by = '$login_id'";
												
												// $truckResult = mysqli_query($con_cchaportdb,$truckQuery);
												
												// $imp_rot = "";
												// $cont_no = "";
												// $truck_id = "";
												// $gate_no = "";
												// $driver_name = "";
												// $driver_gate_pass = "";
												// $assistant_name = "";
												// $assistant_gate_pass = "";
												// $gate_in_status = ""; 
												// $paid_status = ""; 
												// $load_st = "";
												// $trafficConfirm = "";
												// $securityConfirm = "";
												// $CFConfirm = "";
												// $trucVisitId = "";
												// $i=0;
												// while($row = mysqli_fetch_object($truckResult)){
												// 	$imp_rot = $row->import_rotation;
												// 	$cont_no = $row->cont_no;
												// 	$truck_id = $row->truck_id;
												// 	$gate_no = $row->gate_no;
												// 	$driver_name = $row->driver_name;
												// 	$driver_gate_pass = $row->driver_gate_pass;
												// 	$assistant_name = $row->assistant_name;
												// 	$assistant_gate_pass = $row->assistant_gate_pass;
												// 	$gate_in_status = $row->gate_in_status; 
												// 	$paid_status = $row->paid_status; 
												// 	$load_st = $row->load_st;
												// 	$trafficConfirm = $row->traffic_chk_st;;
												// 	$securityConfirm = $row->yard_security_chk_st;;
												// 	$CFConfirm = $row->cnf_chk_st;;
												// 	$trucVisitId = $row->trucVisitId;
												// 	$i++;
											?>
											
											<tr>
												<td class="text-center" ><?php //echo $trucVisitId; ?></td>
												<td class="text-center" ><?php //echo $imp_rot; ?></td>
												<td class="text-center" ><?php //echo $cont_no; ?></td>
												<td class="text-center" ><?php //echo $truck_id; ?></td>
												<td class="text-center" ><?php //echo $gate_no; ?></td>
												<td class="text-center" style="font-size:10px;"><nobr><?php //echo $driver_name; ?></nobr></td>
												<td class="text-center" ><?php //echo $driver_gate_pass; ?></td>
												<td class="text-center" style="font-size:10px;"><nobr><?php //echo $assistant_name; ?></nobr></td>
												<td class="text-center"><?php //echo $assistant_gate_pass; ?></td>
												<td class="text-center">
													<nobr>
													<?php 
														
														// if($gate_in_status == 1){
														// 	echo "Yes";
														// }else{
														// 	echo "No";
														// }
													?>
													</nobr>
												</td>
												<td class="text-center"><?php //if($trafficConfirm == 1){ echo "Yes";}else{ echo "No";} ?></td>
												<td class="text-center"><?php //if($securityConfirm == 1){ echo "Yes";}else{ echo "No";} ?></td>
												<td class="text-center"><?php //if($CFConfirm == 1){ echo "Yes";}else{ echo "No";} ?></td>
												<td class="text-center">
													<form method="POST" action="<?php //echo site_url("ShedBillController/truckEntranceApplicationPDF") ?>" target="_blank">
														<input type="hidden" name="rot_no" id="rot_no" value="<?php //echo $imp_rot; ?>"/>
														<input type="hidden" name="cont_no" id="cont_no" value="<?php //echo $cont_no; ?>"/>
														<input type="hidden" name="trucVisitId" id="trucVisitId" value="<?php //echo $trucVisitId; ?>"/>
														<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" name="" id="" value="Print"/>
													</form>
												</td>
											</tr>
											
											<?php
												// }
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
	</section> -->

	<!-- C&F ends here -->

	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Dashboard</h2>
		</header>

		<div class="row">

			<img src="<?php echo  ASSETS_WEB_PATH?>fimg/dashboard.jpeg" height="85%" width="95%" alt="Banner" class="img-rounded img-responsive" >

		</div>

<?php } else if($_SESSION['org_Type_id']==4) { ?>

	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Dashboard</h2>
		</header>
		<div class="row">
		<?php 
			// include("mydbPConnection.php"); 
			$org_id =$this->session->userdata('org_id');
			
			$ff_ain = "";
			$totalToken = 0;
			$totalUsedToken = 0;
			$totalBalanceToken = 0;
			$totalPendingDO = 0;
			$strAin="SELECT AIN_No_New FROM organization_profiles WHERE id='$org_id'";
			$resAin = mysqli_query($con_cchaportdb,$strAin);
			while($rowAin = mysqli_fetch_object($resAin)){
				$ff_ain = $rowAin->AIN_No_New;
			}

			//Total Distributed Token
			$strTotToken="SELECT COUNT(*) AS totToken FROM token_distribution WHERE ff_ain='$ff_ain'";
			$resTotToken = mysqli_query($con_cchaportdb,$strTotToken);
			while($rowTotToken = mysqli_fetch_object($resTotToken)){
				$totalToken = $rowTotToken->totToken;
			}

			//Total Used Token
			$strTotUsedToken="SELECT COUNT(*) AS totUsedToken FROM token_distribution WHERE ff_ain='$ff_ain' AND used_st='1'";
			$resTotUsedToken = mysqli_query($con_cchaportdb,$strTotUsedToken);
			while($rowTotUsedToken = mysqli_fetch_object($resTotUsedToken)){
				$totalUsedToken = $rowTotUsedToken->totUsedToken;
			}

			//Total Balance Token
			$strTotBalanceToken="SELECT COUNT(*) AS totBalanceToken FROM token_distribution WHERE ff_ain='$ff_ain' AND used_st='0'";
			$resTotBalanceToken = mysqli_query($con_cchaportdb,$strTotBalanceToken);
			while($rowTotBalanceToken = mysqli_fetch_object($resTotBalanceToken)){
				$totalBalanceToken = $rowTotBalanceToken->totBalanceToken;
			}

			//Total DO Pending
			$strTotPendingDO="SELECT COUNT(*) AS totPendingDO FROM edo_application_by_cf 
			WHERE edo_application_by_cf.igm_type='GM' AND edo_application_by_cf.bl_type='HB' 
			AND edo_application_by_cf.ff_org_id='$org_id' AND edo_application_by_cf.ff_stat='1' 
			AND edo_application_by_cf.do_upload_st='0'
			AND edo_application_by_cf.rejection_st='0'";
			$resTotPendingDO = mysqli_query($con_cchaportdb,$strTotPendingDO);
			while($rowTotPendingDO = mysqli_fetch_object($resTotPendingDO)){
				$totalPendingDO = $rowTotPendingDO->totPendingDO;
			}
			
		?>
		
		<div class="col-md-6 col-lg-12 col-xl-6">
			<div class="row">
				<div class="col-md-12 col-lg-6 col-xl-6">
					<section class="panel panel-featured-left panel-featured-primary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-primary">
										<i class="fa fa-life-ring"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Total Balance Token <?php //echo $ff_ain; ?></h4>
										<div class="info">
											<strong class="amount"><?php echo $totalBalanceToken;?></strong>
											<span class="text-primary"></span>
										</div>
									</div>
									<div class="summary-footer">
										<a href="<?php echo site_url('ShedBillController/tokenDistributionSearch/balance'); ?>" class="text-muted text-uppercase">
											<b>View All</b>
										</a>
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
									<div class="summary-icon bg-quartenary">
										<i class="fa fa-unsorted"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Total Pending DO Application</h4>
										<div class="info">
											<strong class="amount"><?php echo $totalPendingDO;?></strong>
										</div>
									</div>
									<div class="summary-footer">										
										<a href="<?php echo site_url('ShedBillController/pendingDOList/'.$ff_ain); ?>" class="text-muted text-uppercase">
											<b>View All</b>
										</a>										
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
	</section>

	<?php } else if(@$_SESSION['org_Type_id']==74) { ?>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Dashboard</h2>
		</header>
		<div class="row">

		<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/vesselDetailsShow'); ?>" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Date <span class="required">*</span></span>
										<input type="date" name="date" id="date" class="form-control" value="<?php echo date("Y-m-d");?>">
									</div>												
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php //echo $msg;?>
									</div>
								</div>
							</div>	
						</form>
					</div>
				</section>
			</div>

		<?php 
			// include("mydbPConnectionn4.php"); 
			if(isset($vesselDate)){
				$date = $vesselDate;
			}else{
				$date = date("Y-m-d");
			}

			$strQuery = "select 
			(CASE
			when SUBSTR(berth,1)='G' then '1'
			else
			(CASE 
			when SUBSTR(berth,1)='C' then '2'
			else '3'
			END)
			END) as sl, tbl2.*  
			from(
			SELECT * FROM 
			(
			SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name, vsl_vessel_visit_details.ib_vyg,
			NVL(vsl_vessel_visit_details.flex_string03,vsl_vessel_visit_details.flex_string02) AS berthop,
			(SELECT argo_quay.id FROM argo_quay
			INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.quay=argo_quay.gkey
			WHERE vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
			ORDER BY vsl_vessel_berthings.ata DESC fetch first 1 rows only) AS berth,argo_carrier_visit.ata,argo_visit_details.etd
			
			
			FROM argo_carrier_visit
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
			INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
			WHERE argo_carrier_visit.phase IN ('30ARRIVED','40WORKING') AND ref_country.cntry_code!='BD'
			UNION ALL
			SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name, vsl_vessel_visit_details.ib_vyg,
			NVL(vsl_vessel_visit_details.flex_string03,vsl_vessel_visit_details.flex_string02) AS berthop,
			(SELECT argo_quay.id FROM argo_quay
			INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.quay=argo_quay.gkey
			WHERE vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
			ORDER BY vsl_vessel_berthings.ata DESC fetch first 1 rows only) AS berth,argo_carrier_visit.ata,argo_visit_details.etd
			
			FROM argo_carrier_visit
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
			INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
			WHERE argo_carrier_visit.phase IN ('30ARRIVED','40WORKING') AND ref_country.cntry_code='BD' AND vsl_vessels.notes='BD'
			) tbl)tbl2 WHERE berth IS NOT NULL ORDER BY sl,berth";
			$query=oci_parse($con_sparcsn4_oracle,$strQuery);
			oci_execute($query);
			$i=0;
			
		?>
		<div class="col-md-12 col-lg-12 col-xl-12">
			<div class="row">
				<div align="right" style="padding-right:25px;">
					<h4 class="mt-none"><b><?php echo date("d-m-Y"); ?> <span id="clock"> </span></b></h4>
				</div>

				<div align="center" style="margin-bottom:10px;">
					<u style="font-size:24px;color:green;"><b>Working Vessel</b></u>
				</div>
				<?php
					while(($row=oci_fetch_object($query))!=false){
						$i++;
						$sqlGetTotImportCont="SELECT COUNT(inv_unit.id) AS tot_cont_import
						FROM inv_unit 
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit.declrd_ib_cv
						WHERE argo_carrier_visit.cvcvd_gkey=$row->VVD_GKEY";
						
						$queryTotImportCont=oci_parse($con_sparcsn4_oracle,$sqlGetTotImportCont);
						oci_execute($queryTotImportCont);
						$rowTotImportCont=oci_fetch_object($queryTotImportCont);
						
						$sqlGetTotImportTeus="SELECT SUM(
							(CASE 
							when siz>=40 then 2
							else 1
							END)
							) AS teus
							FROM (
							SELECT inv_unit.id AS tot_cont_import,
							(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit
							INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
							INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
							WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only
							)  AS siz
							
							FROM inv_unit 
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
							INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit.declrd_ib_cv
							WHERE argo_carrier_visit.cvcvd_gkey='$row->VVD_GKEY') tmp";
							
							$queryTotImportTeus=oci_parse($con_sparcsn4_oracle,$sqlGetTotImportTeus);
							oci_execute($queryTotImportTeus);
							$rowTotImportTeus=oci_fetch_object($queryTotImportTeus);
							
						
						
						$sqlGetTotImportDischargeCont="SELECT COUNT(inv_unit.id) AS tot_discharge_cont
						FROM inv_unit 
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit.declrd_ib_cv
						WHERE inv_unit.category='IMPRT' AND argo_carrier_visit.cvcvd_gkey='$row->VVD_GKEY' AND time_in IS NOT NULL";
						$queryTotImportDischargeCont=oci_parse($con_sparcsn4_oracle,$sqlGetTotImportDischargeCont);
						oci_execute($queryTotImportDischargeCont);
						$rowTotImportDischargeCont=oci_fetch_object($queryTotImportDischargeCont);
						
						$sqlGetTotExportCont="SELECT COUNT(inv_unit.id) AS tot_exp_cont
						FROM inv_unit
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
						INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
						INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
						WHERE vsl_vessel_visit_details.vvd_gkey= '$row->VVD_GKEY'";
						$queryTotExportCont=oci_parse($con_sparcsn4_oracle,$sqlGetTotExportCont);
						oci_execute($queryTotExportCont);
						$rowTotExportCont=oci_fetch_object($queryTotExportCont);
						
						$sqlGetTotExportLoadedCont="SELECT ctmsmis.mis_exp_unit.cont_id AS exp_loaded_cont,gkey
						FROM ctmsmis.mis_exp_unit 
						WHERE mis_exp_unit.vvd_gkey='$row->VVD_GKEY' AND mis_exp_unit.preAddStat='0' 
						 AND mis_exp_unit.delete_flag='0'";
						$queryTotExportLoadedCont=mysqli_query($con_sparcsn4,$sqlGetTotExportLoadedCont);
						//$rowTotExportLoadedCont=mysqli_fetch_object($queryTotExportLoadedCont);
						$exp_loaded_cont_count=0;
						while($loaded_cont_row=mysqli_fetch_object($queryTotExportLoadedCont)){
							$n4QueryNum=0;
							$loadedGkey="";
							$loadedGkey=$loaded_cont_row->gkey;
							$n4Str="select * from inv_unit where inv_unit.gkey='$loadedGkey' and inv_unit.category='EXPRT'";
							$n4Query=oci_parse($con_sparcsn4_oracle,$n4Str);
							oci_execute($n4Query);
							$results=array();
							$n4QueryNum =oci_fetch_all($n4Query, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
							oci_free_statement($n4Query);
							if($n4QueryNum>0){
								$exp_loaded_cont_count++;


							}



						}
				?>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
					<section class="panel panel-featured-left panel-featured-primary">
						<div class="panel-body">
							<div class="block">
								<div align="left"><font size="5"><b>Berth: <?php echo  $row->BERTH; ?></b></font></div>
								<div align="left"><b>Vessel:</b> <?php echo  $row->NAME; ?></div>
								<div align="left"><b>Rotation:</b> <?php echo  $row->IB_VYG; ?></div>
								<div align="left"><b>Berth Operator:</b> <?php echo  $row->BERTHOP; ?></div>
								<div align="left"><b>Berthed On:</b> <?php echo  $row->ATA; ?></div>
								<div align="left"><b>Sailed On(E):</b> <?php echo  $row->ETD; ?></div>
								<div align="left"><b>Total Import Container:</b> <?php echo  $rowTotImportCont->TOT_CONT_IMPORT."(Box),  ".$rowTotImportTeus->TEUS."(TEUs)"; ?></div>
								<div align="left"><b>Discharge Container:</b> <?php echo  $rowTotImportDischargeCont->TOT_DISCHARGE_CONT; ?></div>
								<div align="left"><b>Balance:</b> <?php echo  $rowTotImportCont->TOT_CONT_IMPORT - $rowTotImportDischargeCont->TOT_DISCHARGE_CONT;  ?></div>
								<div align="left"><b>Total Export Container:</b> <?php echo  $rowTotExportCont->TOT_EXP_CONT; ?></div>
								<div align="left"><b>Loaded On Board:</b> <?php echo  	$exp_loaded_cont_count; ?></div>
								<div align="left"><b>Balance To Be Shipped:</b> <?php echo  $rowTotExportCont->TOT_EXP_CONT-$exp_loaded_cont_count; ?></div>
							</div>
						</div>
					</section>
				</div>

				<?php
					}
				?>

			</div>

			<div class="clearfix"></div>
			<div align="center">
				<div><font size="5"><b>Total Vessel:<?php echo $i;?></b></font></div>
			</div>
			<div class="clearfix"></div>
			<hr/>
			<div align="center" style="margin-bottom:10px;">
				<u style="font-size: 24px;color:green"><b>Incoming Vessel</b></u>
			</div>


			<?php 
			// include("mydbPConnectionn4.php"); 
			$strQuery = "select 

			(CASE
			WHEN SUBSTR(berth,1)='G' then '1'
			else
			(case
			WHEN SUBSTR(berth,1)='c' then '2'
			else '3'
			end)
			END)AS sl,
			tbl2.*
			from(
			SELECT * FROM
			(SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name, vsl_vessel_visit_details.ib_vyg,
			NVL(vsl_vessel_visit_details.flex_string03,vsl_vessel_visit_details.flex_string02) AS berthop,
			(SELECT argo_quay.id FROM argo_quay
			INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.quay=argo_quay.gkey
			WHERE vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
			ORDER BY vsl_vessel_berthings.ata DESC Fetch FIRST 1 Rows only) AS berth,argo_visit_details.eta,argo_visit_details.etd
			
			
			FROM argo_carrier_visit
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
			INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
			WHERE argo_carrier_visit.phase='20INBOUND' AND
			cast(argo_visit_details.eta as date) BETWEEN to_date('$date','yyyy-mm-dd') AND to_date('$date','yyyy-mm-dd')+1 AND ref_country.cntry_code!='BD'
			ORDER BY argo_carrier_visit.phase,vsl_vessels.name)  tbl)tbl2 ORDER BY eta,berth";
			$query1=oci_parse($con_sparcsn4_oracle,$strQuery);
			oci_execute($query1);
			$j=0;
			
		?>
		<div class="col-md-12 col-lg-12 col-xl-12">
			<div class="row">
				<?php
					while(($row1=oci_fetch_object($query1))!=false){
						$j++;
						$sqlGetTotImportCont="SELECT COUNT(inv_unit.id) AS tot_cont_import
						FROM inv_unit 
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit.declrd_ib_cv
						WHERE argo_carrier_visit.cvcvd_gkey='$row1->VVD_GKEY'";
	   
						$queryTotImportCont1=oci_parse($con_sparcsn4_oracle,$sqlGetTotImportCont);
						oci_execute($queryTotImportCont1);
						$rowTotImportCont=oci_fetch_object($queryTotImportCont1);
	   
	   
						$sqlGetTotImportDischargeCont="SELECT COUNT(inv_unit.id) AS tot_discharge_cont
						FROM inv_unit 
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit.declrd_ib_cv
						WHERE inv_unit.category='IMPRT' AND argo_carrier_visit.cvcvd_gkey='$row1->VVD_GKEY' AND time_in IS NOT NULL";
						$queryTotImportDischargeCont1=oci_parse($con_sparcsn4_oracle,$sqlGetTotImportDischargeCont);
						oci_execute($queryTotImportDischargeCont1);
						$rowTotImportDischargeCont=oci_fetch_object($queryTotImportDischargeCont1);
	   
						$sqlGetTotExportCont="SELECT COUNT(inv_unit.id) AS tot_exp_cont
						FROM inv_unit
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
						INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
						INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
						WHERE vsl_vessel_visit_details.vvd_gkey= '$row1->VVD_GKEY'";
						$queryTotExportCont1=oci_parse($con_sparcsn4_oracle,$sqlGetTotExportCont);
						oci_execute($queryTotExportCont1);
						$rowTotExportCont=oci_fetch_object($queryTotExportCont1);
	   
						$sqlGetTotExportLoadedCont="SELECT ctmsmis.mis_exp_unit.cont_id AS  exp_loaded_cont,gkey
						FROM ctmsmis.mis_exp_unit
						WHERE mis_exp_unit.vvd_gkey='$row1->VVD_GKEY' AND mis_exp_unit.preAddStat='0' 
						 AND mis_exp_unit.delete_flag='0'";
						$queryTotExportLoadedCont=mysqli_query($con_sparcsn4,$sqlGetTotExportLoadedCont);
						//$rowTotExportLoadedCont=mysqli_fetch_object($queryTotExportLoadedCont);
						$exp_loaded_cont_count1=0;
						while($loaded_cont_row1=mysqli_fetch_object($queryTotExportLoadedCont)){
							$n4QueryNum1=0;
							$loadedGkey="";
							$loadedGkey=$loaded_cont_row1->gkey;
							$n4Str="select * from inv_unit where inv_unit.gkey='$loadedGkey' and inv_unit.category='EXPRT'";
							$n4Query1=oci_parse($con_sparcsn4_oracle,$n4Str);
							oci_execute($n4Query1);
							$results=array();
							$n4QueryNum =oci_fetch_all($n4Query1, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
							oci_free_statement($n4Query1);
							if($n4QueryNum1>0){
								$exp_loaded_cont_count1++;


							}



						}
				?>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
					<section class="panel panel-featured-left panel-featured-primary">
						<div class="panel-body">
							<div class="block">
								<div align="left"><font size="5"><b>Berth: <?php echo  $row1->BERTH; ?></b></font></div>
								<div align="left"><b>Vessel:</b> <?php echo  $row1->NAME; ?></div>
								<div align="left"><b>Rotation:</b> <?php echo  $row1->IB_VYG; ?></div>
								<div align="left"><b>Berth Operator:</b> <?php echo  $row1->BERTHOP; ?></div>
								<div align="left"><b>Berthed On(E):</b> <?php echo  $row1->ETA; ?></div>
								<div align="left"><b>Sailed On(E):</b> <?php echo  $row1->ETD; ?></div>
								<div align="left"><b>Total Import Container:</b> <?php echo  $rowTotImportCont->TOT_CONT_IMPORT; ?></div>
								<div align="left"><b>Discharge Container:</b> <?php echo  $rowTotImportDischargeCont->TOT_DISCHARGE_CONT ; ?></div>
								<div align="left"><b>Balance:</b> <?php echo  $rowTotImportCont->TOT_CONT_IMPORT - $rowTotImportDischargeCont->TOT_DISCHARGE_CONT; ?></div>
								<div align="left"><b>Total Export Container:</b> <?php echo  $rowTotExportCont->TOT_EXP_CONT; ?></div>
								<div align="left"><b>Loaded On Board:</b> <?php echo  $exp_loaded_cont_count1; ?></div>
								<div align="left"><b>Balance To Be Shipped:</b> <?php echo  $rowTotExportCont->TOT_EXP_CONT-$exp_loaded_cont_count1; ?></div>
							</div>
						</div>
					</section>
				</div>

				<?php
					}
				?>

				</div>

			</div>

			<div class="clearfix"></div>
			<div align="center">
				<div><font size="5"><b>Total Vessel:<?php echo $j;?></b></font></div>
			</div>
			<div class="clearfix"></div>
			<hr/>
			<div align="center" style="margin-bottom:10px;">
				<u style="font-size: 24px;color:green"><b>Outgoing Vessel</b></u>
			</div>


			<?php 
			// include("mydbPConnectionn4.php"); 
			$strQuery = "select 
			(CASE 
			when SUBSTR(berth,1)='G' then '1'
			else
			(CASE
			when SUBSTR(berth,1)='C' then '2'
			else
			'3'
			END)
			END)AS sl,
			tbl2.*
			from(
			SELECT * FROM (
			SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name, vsl_vessel_visit_details.ib_vyg,
			NVL(vsl_vessel_visit_details.flex_string03,vsl_vessel_visit_details.flex_string02) AS berthop,
			(SELECT argo_quay.id FROM argo_quay
			INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.quay=argo_quay.gkey
			WHERE vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
			ORDER BY vsl_vessel_berthings.ata DESC fetch first 1 rows only) AS berth,argo_visit_details.eta,argo_visit_details.etd
			
			FROM argo_carrier_visit
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
			INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
			WHERE Cast(argo_visit_details.etd as date)=to_date('$date','yyyy-mm-dd') AND ref_country.cntry_code!='BD'
			union all 
			SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name, vsl_vessel_visit_details.ib_vyg,
			NVL(vsl_vessel_visit_details.flex_string03,vsl_vessel_visit_details.flex_string02) AS berthop,
			(SELECT argo_quay.id FROM argo_quay
			INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.quay=argo_quay.gkey
			WHERE vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
			ORDER BY vsl_vessel_berthings.ata DESC  fetch first 1 rows only) AS berth,argo_visit_details.eta,argo_visit_details.etd
			
			FROM argo_carrier_visit
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
			INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
			WHERE Cast(argo_visit_details.etd as date)=to_date('$date','yyyy-mm-dd') AND ref_country.cntry_code='BD' and vsl_vessels.notes='BD'
			) tbl )tbl2 ORDER BY eta,berth";
			$query2=oci_parse($con_sparcsn4_oracle,$strQuery);
			oci_execute($query2);
			$j=0;
			
		?>
		<div class="col-md-12 col-lg-12 col-xl-12">
			<div class="row">
				<?php
					while($row2=oci_fetch_object($query2)){
						$j++;
						$sqlGetTotImportCont="SELECT COUNT(inv_unit.id) AS tot_cont_import
						FROM inv_unit 
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit.declrd_ib_cv
						WHERE argo_carrier_visit.cvcvd_gkey='$row2->VVD_GKEY'";
	   
						$queryTotImportCont2=oci_parse($con_sparcsn4_oracle,$sqlGetTotImportCont);
						oci_execute($queryTotImportCont2);
						$rowTotImportCont=oci_fetch_object($queryTotImportCont2);
						
						$sqlGetTotImportTeus="SELECT 
						(CASE
						WHEN siz>=40 THEN '2'
						else
						'1'
						END) AS teus 
						FROM (
						SELECT inv_unit.id AS tot_cont_import,
						(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit
						INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
						INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
						WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey FETCH FIRST 1 ROWS ONLY
						)  AS siz
						
						FROM inv_unit 
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit.declrd_ib_cv
						WHERE argo_carrier_visit.cvcvd_gkey='$row2->VVD_GKEY')  tmp";
							
							$queryTotImportTeus2=oci_parse($con_sparcsn4_oracle,$sqlGetTotImportTeus);
							oci_execute($queryTotImportTeus2);
							$rowTotImportTeus=oci_fetch_object($queryTotImportTeus2);
	   
						$sqlGetTotImportDischargeCont="SELECT COUNT(inv_unit.id) AS tot_discharge_cont
						FROM inv_unit 
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit.declrd_ib_cv
						WHERE inv_unit.category='IMPRT' AND argo_carrier_visit.cvcvd_gkey='$row2->VVD_GKEY' AND time_in IS NOT NULL";
						$queryTotImportDischargeCont2=oci_parse($con_sparcsn4_oracle,$sqlGetTotImportDischargeCont);
						oci_execute($queryTotImportDischargeCont2);
						$rowTotImportDischargeCont=oci_fetch_object($queryTotImportDischargeCont2);
	   
						$sqlGetTotExportCont="SELECT COUNT(inv_unit.id) AS tot_exp_cont
						FROM inv_unit
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
						INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
						INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
						WHERE vsl_vessel_visit_details.vvd_gkey= '$row2->VVD_GKEY'";
						$queryTotExportCont2=oci_parse($con_sparcsn4_oracle,$sqlGetTotExportCont);
						oci_execute($queryTotExportCont2);
						$rowTotExportCont=oci_fetch_object($queryTotExportCont2);
	   
						$sqlGetTotExportLoadedCont="SELECT ctmsmis.mis_exp_unit.cont_id AS exp_loaded_cont,gkey
						FROM ctmsmis.mis_exp_unit
						
						WHERE mis_exp_unit.vvd_gkey='$row2->VVD_GKEY' AND mis_exp_unit.preAddStat='0' 
						AND mis_exp_unit.delete_flag='0'";
						$queryTotExportLoadedCont=mysqli_query($con_sparcsn4,$sqlGetTotExportLoadedCont);
						//$rowTotExportLoadedCont=mysqli_fetch_object($queryTotExportLoadedCont);

						$exp_loaded_cont_count2=0;
						while($loaded_cont_row2=mysqli_fetch_object($queryTotExportLoadedCont)){
							$n4QueryNum1=0;
							$loadedGkey="";
							$loadedGkey=$loaded_cont_row2->gkey;
							$n4Str="select * from inv_unit where inv_unit.gkey='$loadedGkey' and inv_unit.category='EXPRT'";
							$n4Query2=oci_parse($con_sparcsn4_oracle,$n4Str);
							oci_execute($n4Query2);
							$results=array();
							$n4QueryNum =oci_fetch_all($n4Query2, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
							oci_free_statement($n4Query2);
							if($n4QueryNum1>0){
								$exp_loaded_cont_count2++;


							}



						}
				?>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
					<section class="panel panel-featured-left panel-featured-primary">
						<div class="panel-body">
							<div class="block">
								<div align="left"><font size="5"><b>Berth: <?php echo  $row2->BERTH; ?></b></font></div>
								<div align="left"><b>Vessel:</b> <?php echo  $row2->NAME; ?></div>
								<div align="left"><b>Rotation:</b> <?php echo  $row->IB_VYG; ?></div>
								<div align="left"><b>Berth Operator:</b> <?php echo  $row2->BERTHOP; ?></div>
								<div align="left"><b>Berthed On(E):</b> <?php echo  $row2->ETA; ?></div>
								<div align="left"><b>Sailed On(E):</b> <?php echo  $row2->ETD; ?></div>
								<div align="left"><b>Total Import Container:</b> <?php echo  $rowTotImportCont->TOT_CONT_IMPORT.'(Box),  '.$rowTotImportTeus->TEUS.'(TEUs)'; ?></div>	
								<div align="left"><b>Discharge Container:</b> <?php echo  $rowTotImportDischargeCont->TOT_DISCHARGE_CONT; ?></div>
								<div align="left"><b>Balance:</b> <?php echo  $rowTotImportCont->TOT_CONT_IMPORT - $rowTotImportDischargeCont->TOT_DISCHARGE_CONT; ?></div>
								<div align="left"><b>Total Export Container:</b> <?php echo  $rowTotExportCont->TOT_EXP_CONT; ?></div>
								<div align="left"><b>Loaded On Board:</b> <?php echo $exp_loaded_cont_count2; ?></div>
								<div align="left"><b>Balance To Be Shipped:</b> <?php echo  $rowTotExportCont->TOT_EXP_CONT-$exp_loaded_cont_count2; ?></div>
							</div>
						</div>
					</section>
				</div>

				<?php
					}
				?>

				</div>

			</div>
			<div class="clearfix"></div>
			<div align="center">
				<div><font size="5"><b>Total Vessel:<?php echo $j;?></b></font></div>
			</div>
			<hr/>
		</div>
	</div>
	</section>
	
<?php } 
	else if(@$_SESSION['org_Type_id']==1)
	{ 
	?>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Dashboard</h2>
		</header>
		
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body" align="center">
						<img  width="800px" height="400px" src="<?php echo BASE_PATH?>resources/MLO_Notice_to_update_agent_code.PNG" >
					</div>
				</section>
			</div>
		</div>
	</section>
<?php	
	}	
	else 
	{ ?>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Dashboard</h2>
		</header>
	</section>
<?php } ?>
<?php mysqli_close($con_cchaportdb); ?>
<?php mysqli_close($con_sparcsn4); ?>