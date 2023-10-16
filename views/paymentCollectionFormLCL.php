<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("LCL/paymentDataSearch") ?>">

					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Truck Visit No. <span class="required">*</span></span>
								<input type="text" name="trucVisitId" id="trucVisitId" class="form-control login_input_text" tabindex="1" placeholder="Truck Visit No." autofocus>
							</div>												
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<?php echo $msg; ?>
							</div>													
						</div>
																		
						<div class="row">
							<div class="col-sm-12 text-center">
								<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
								<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success" tabindex="2">Search</button>
							</div>													
						</div>						
					</div>	
				</form>
			</div>
		</section>
            
			<?php
			if($flag == 1){

			include("mydbPConnection.php");
			$truckQuery="SELECT id,verify_info_fcl_id,verify_other_data_id,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,visit_time_slot_start,visit_time_slot_end,paid_status, paid_method, paid_amt,paid_collect_dt,paid_collect_by,collect_gate_no
			FROM cchaportdb.do_truck_details_entry  
			WHERE id='$trucVisitId'";

			$rsltTruck = mysqli_query($con_cchaportdb,$truckQuery);
			
			$id = "";
			$rot_no = "";
			$cont_no = "";
			$gate_no = "";
			$truck_id = "";
			$driver_name = "";
			$driver_gate_pass = "";
			$assistant_name = "";
			$assistant_gate_pass = "";
			$visit_time_slot_start = "";
			$visit_time_slot_end = "";
			$method = "";
			$paid_status = "";
			$paid_amt = "";
			$paid_collect_dt = "";
			$paid_collect_by = "";
			$verify_info_fcl_id = "";
			$collect_gate_no = "";
			$verifyOtherDataId = "";
			$i = 0;
			while($truckResult = mysqli_fetch_object($rsltTruck)){
				$i++;
				$id = $truckResult->id;
				$rot_no = $truckResult->import_rotation;
				$cont_no = $truckResult->cont_no;
				$gate_no = $truckResult->gate_no;
				$truck_id = $truckResult->truck_id;
				$driver_name = $truckResult->driver_name;
				$driver_gate_pass = $truckResult->driver_gate_pass;
				$assistant_name = $truckResult->assistant_name;
				$assistant_gate_pass = $truckResult->assistant_gate_pass;
				$visit_time_slot_start = $truckResult->visit_time_slot_start;
				$visit_time_slot_end = $truckResult->visit_time_slot_end;
				$method = $truckResult->paid_method;
				$paid_status = $truckResult->paid_status;
				$paid_amt = $truckResult->paid_amt;
				$paid_collect_dt = $truckResult->paid_collect_dt;
				$paid_collect_by = $truckResult->paid_collect_by;
				$verify_info_fcl_id = $truckResult->verify_info_fcl_id;
				$collect_gate_no = $truckResult->collect_gate_no;
				$verifyOtherDataId = $truckResult->verify_other_data_id;
			}

			$igmQuery="SELECT  igm_detail_container.cont_number, cont_seal_number,cont_status,cont_height,cont_iso_type, Description_of_Goods,Vessel_Name,Name_of_Master,igm_masters.Import_Rotation_No,truck_no_by,Bill_of_Entry_No
			FROM  igm_details
			INNER JOIN igm_masters ON  igm_masters.id=igm_details.IGM_id
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN verify_info_fcl ON igm_detail_container.igm_detail_id=verify_info_fcl.igm_detail_cont_id
			LEFT JOIN  shed_tally_info ON shed_tally_info.igm_detail_id=igm_details.id
			LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
			WHERE igm_details.Import_Rotation_No='$rot_no' AND igm_detail_container.cont_number='$cont_no'";

			$rsltIgm = mysqli_query($con_cchaportdb,$igmQuery);
			$beNo = "";
			$Vessel_Name="";
			$Import_Rotation_No = "";
			$description_of_Goods = "";
			$cont_number = "";
			while($igmResult = mysqli_fetch_object($rsltIgm)){
				$Vessel_Name = $igmResult->Vessel_Name;
				$Import_Rotation_No = $igmResult->Import_Rotation_No;
				$description_of_Goods = $igmResult->Description_of_Goods;
				$cont_number = $igmResult->cont_number;
				$beNo = $igmResult->Bill_of_Entry_No;
			}

			$jettySarkarQuery = "";

			if(is_null($verify_info_fcl_id)){
				// $jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code FROM vcms_vehicle_agent 
				// INNER JOIN verify_other_data ON verify_other_data.jetty_sirkar_id = vcms_vehicle_agent.id
				// INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = verify_other_data.id
				// WHERE do_truck_details_entry.verify_other_data_id='$verifyOtherDataId'";
				$jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code FROM vcms_vehicle_agent 
				INNER JOIN lcl_dlv_assignment ON lcl_dlv_assignment.jetty_sirkar_id = vcms_vehicle_agent.id
				INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = lcl_dlv_assignment.id
				WHERE do_truck_details_entry.verify_other_data_id='$verifyOtherDataId'";
			}else if(is_null($verifyOtherDataId)){
				$jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code FROM vcms_vehicle_agent 
				INNER JOIN verify_info_fcl ON verify_info_fcl.jetty_sirkar_id = vcms_vehicle_agent.id
				INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_info_fcl_id = verify_info_fcl.id
				WHERE do_truck_details_entry.verify_info_fcl_id='$verify_info_fcl_id'";
			}

			$agentName = "";
			$agentCode = "";

			if($jettySarkarQuery != ""){
				$rsltJetty = mysqli_query($con_cchaportdb,$jettySarkarQuery);

				while($jettyInfo = mysqli_fetch_object($rsltJetty)){
					$agentName = $jettyInfo->agent_name;
					$agentCode = $jettyInfo->agent_code;
				}
			}

			include("dbConection.php");

					$cnfQuery="SELECT DISTINCT a.gkey,a.id AS cont_no,k.name  AS cnf, k.id AS cnf_lic,
					CONCAT(k.address_line1,IFNULL(k.address_line2,'')) AS cnf_addr,
					a.gkey, a.id AS cont_no, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
					mfdch_desc, 
					(SELECT ctmsmis.cont_yard(b.last_pos_slot)) AS Yard_No					
					FROM sparcsn4.inv_unit a
					INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
					INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods
					INNER JOIN vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.ib_vyg=b.flex_string10
					LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
					INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value
					WHERE a.id='$cont_no' AND sparcsn4.vsl_vessel_visit_details.ib_vyg ='$rot_no'";

			$rsltCnf = mysqli_query($con_sparcsn4,$cnfQuery);
			$cnf="";
			$cnf_addr = "";
			$cnf_lic = "";
			$assignmentType="";
			while($cnfResult=mysqli_fetch_object($rsltCnf)){
				$cnf = $cnfResult->cnf;
				$cnf_addr = $cnfResult->cnf_addr;
				$cnf_lic = $cnfResult->cnf_lic;
				$assignmentType = $cnfResult->mfdch_value;
			}

			$YardQuery = "SELECT DISTINCT Yard_No
			FROM ctmsmis.tmp_vcms_assignment  
			WHERE cont_no = '$cont_no' AND rot_no = '$rot_no'";

			$rsltYard = mysqli_query($con_sparcsn4,$YardQuery);

			$yardNo="";
			while($yardResult=mysqli_fetch_object($rsltYard)){
				$yardNo = $yardResult->Yard_No;
			}

		?>

		<?php
			if($i>0){
		?>
		<section class="panel" style="background-color:#fff;">
			<div class="panel-body" style="background-color:#fff;">
		<?php
				if($paid_status==1){
		?>
		<?php
			}else{
		?>
			<h2 align="center"><?php echo $cnf; ?></h2>
			<h4 align="center"><?php echo $cnf_addr; ?></h4>
			<h4 align="center">C&F LICENSE NO: <?php echo $cnf_lic; ?></h4>
		<?php
			}
		?>
		<!-- <div class="panel-body"> -->
		<table border="0" align="center" width="100%" style="background-color:#fff;">
			<tr>
				<?php
					if($paid_status==1){
				?>
					<td colspan="3" align="left">&nbsp;</td>
				<?php
					}else{
				?>
					<td colspan="3" align="left">To,</td>
				<?php
					}
				?>
			</tr>
			<tr>
				<?php
					if($paid_status==1){
				?>
					<td width="30%">&nbsp;</td>
				<?php
					}else{
				?>
					<td align="left">Gate Sergeant</td>
				<?php
					}
				?>
				<?php
					if($paid_status==1){
				?>
					<td align="center" style="font-size:16px;"><b><u>Vehicle Pass (1 day)</u></b></td>
				<?php
					}else{
				?>
					<td align="center" style="font-size:16px;"><b><u>Application for Vehicle Gate Pass</u></b></td>
				<?php
					}
				?>

				<td align="right">Date: <?php echo DATE("Y-m-d"); ?></td>
			</tr>

			<?php
				if($paid_status==0){
			?>
				<tr>
					<td colspan="3" align="left">Sir,</td>
				</tr>
				<tr>
					<td colspan="3" align="left">You are requested to issue an entry pass for transportation of goods in the described TRUCK / LONG VEHICLE / COVERED VAN / TRAILER inside jetty.</td>
				</tr>
			
			<?php
				}
			?>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" width="100%">
					<table border="0" style="border:1px solid #000;" align="center" width="100%">
						<tr>
							<td align="right" width="100px;"><nobr>Truck Visit No.: </nobr></td>
							<th align="left">&nbsp;<b><nobr><?php echo $trucVisitId;?></nobr></b></th>
							<td align="right" width="130px;">Assignment Type: </td>
							<td align="left">&nbsp;<b> <?php echo $assignmentType; ?> </b></td>
							<td align="right">From: </td>
							<th align="left" width="140px;">&nbsp;<b><?php echo $visit_time_slot_start; ?></b></th>
							<td align="right">To: </td>
							<th align="left" width="140px;">&nbsp;<b><?php echo $visit_time_slot_end; ?></b></th>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
		</table>

		<table border="0" align="center" width="100%" style="background-color:#fff;">
			<tr>
				<th colspan="3" style="font-size:15px;" align="left"><u>Jetty Sarkar Information</u></th>
				<th colspan="3" style="font-size:15px;" align="left"><u>Transport Agency Information</u></th>
			</tr>
			<tr>
				<td>1. Vessel</td>
				<td>:</td>
				<th align="left"><?php echo $Vessel_Name; ?></th>
				<td>1. Gate No</td>
				<td>:</td>
				<th align="left"><?php if($collect_gate_no!=''){echo $collect_gate_no;}else{ echo "3,4,5"; } ?></th>
			</tr>
			
			<tr>
				<td>2. Rotation</td>
				<td>:</td>
				<th align="left"><?php echo $Import_Rotation_No; ?></th>
				<td>2. Truck No</td>
				<td>:</td>
				<th align="left"><?php echo $truck_id; ?></th>
			</tr>
			
			<tr>
				<td>3. B/E No</td>
				<td>:</td>
				<th align="left"><?php echo $beNo; ?></th>
				<td>3. Driver Name</td>
				<td>:</td>
				<th align="left"><?php echo $driver_name; ?></th>
			</tr>
			
			<tr>
				<td>4. Shed / Yard No</td>
				<td>:</td>
				<th align="left"><?php echo $yardNo; ?></th>
				<td>4. ID Card (Driver)</td>
				<td>:</td>
				<th align="left"><?php echo $driver_gate_pass; ?></th>
				
			</tr>
			
			<tr>
				<td>5. Jetty Sarkar Name</td>
				<td>:</td>
				<th align="left"><?php echo $agentName; ?></th>
				<td>5. Union Membership No. (Driver)</td>
				<td>:</td>
				<th align="left">&nbsp;</th>
			</tr>
			
			<tr>
				<td>6. Jetty Sarkar Lic. No.</td>
				<td>:</td>
				<th align="left"><?php echo $agentCode; ?></th>
				<td>6. Assistant Name</td>
				<td>:</td>
				<th align="left"><?php echo $assistant_name; ?></th>
			</tr>
			
			<tr>
				<td>7. Container No.</td>
				<td>:</td>
				<th align="left"><?php echo $cont_number; ?></th>
				<td>7. ID Card (Assistant)</td>
				<td>:</td>
				<th align="left"><?php echo $assistant_gate_pass; ?></th>
			</tr>

			<tr>
				<td rowspan="2">8. Goods Details</td>
				<td rowspan="2">:</td>
				<th align="left" rowspan="2"><?php echo substr($description_of_Goods,0,50);?></th>
				<td>8. Union Membership No. (Assistant)</td>
				<td>:</td>
				<th align="left">&nbsp;</th>
			</tr>
			
			<tr>
				<td>9. Transport Agency Name</td>
				<td>:</td>
				<th align="left">&nbsp;</th>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
				<td colspan="3">
					<table border="0" cellpadding="5" style="border:1px solid #000;margin-right:20px;" align="right" >
						<tr>
							<td align="left">Fees</td>
							<td>:</td>
							<th align="left"><b><?php echo $paid_amt;?></b></th> 
						</tr>
						<tr>
							<td align="left">Payment Mathod</td>
							<td>:</td>
							<th align="left"><b><?php echo ucfirst($method);?></b></th> 
						</tr>
						<tr>
							<td align="left">Payment Status</td>
							<td>:</td>
							<th align="left"><b><?php if($paid_status==1){echo "Paid";}else{ echo "Not Paid";} ?></b></th>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<!-- </div> -->
		
					<div class="panel-body" style="background-color:#fff;">
						<?php
							if($cont_status == "DO_NOT_RELEASE"){
						?>
								<div class="col-sm-12 text-center">
									<font size="4" color="red"><b><?php echo $cont_no; ?> Container is Blocked!!</b></font>
								</div>
						<?php
							}
							else
							{
                            	if($paid_status == 2){
                        ?>
								<div class="col-sm-12 text-center">
									<form method="POST" action="<?php echo site_url("LCL/paymentStsCng") ?>" onsubmit="return confirm('Are You Sure?');">
										<input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
										<input type="hidden" name="gate_no" id="gate_no" value="<?php echo $gate_no; ?>"/>
										<input type="hidden" name="driverPass" id="driverPass" value="<?php echo $driver_gate_pass; ?>"/>
										<input type="hidden" name="helperPass" id="helperPass" value="<?php echo $assistant_gate_pass; ?>"/>
										<button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary">Collect</button>
									</form>
								</div>
                        <?php
                            	}else if($paid_status == 0){
						?>
								<div class="col-sm-12 text-center">
									<font size="4" color="red"><b>Not Paid Yet!</b></font>
								</div>
						<?php
								}else{
                        ?>
							
								<div class="col-sm-6 text-right">
									<font color="green"><b>Payment Collected!</b></font>
								</div>
								<div class="col-sm-6 text-left">
									<form action="<?php echo site_url('LCL/vehicleGatePass');?>" method="POST" 
										target="_blank">
										<input type="hidden" name="trucVisitId" id ="trucVisitId" value="<?php echo $trucVisitId;?>"/>
										<input type="hidden" name="cont_no" id ="cont_no" value="<?php echo $cont_number;?>"/>
										<input type="hidden" name="rot_no" id ="rot_no" value="<?php echo $Import_Rotation_No;?>"/>
										<input type="submit" class="btn btn-primary" name="submit" id="submit" value="Print"/>
									</form>
								</div>
                        <?php
                            	}
							}
                        ?>
					</div>
				</div>
			</div>
		</section>
		<?php
			}else{
		?>
			<!-- <section class="panel">
				<div class="panel-body"> -->
					<div class="row">
						<div class="text-center">
							<font size="3"color="red">Truck Visit No. <b><?php echo $trucVisitId; ?></b> Doesn't Exist!!</font>
						</div>
					</div>
				<!-- </div>
			</section> -->
		<?php	
				}

			}
		?>
		
	</div>
</div>
</section>
</div>