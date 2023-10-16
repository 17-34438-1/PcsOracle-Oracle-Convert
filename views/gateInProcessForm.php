<script>
	function chkField(){
		var visitId = "";
		visitId = document.getElementById("gate_pass").value;
		visitId = visitId.trim();
		//alert(visitId);
		if(visitId == "" || visitId == null){
			alert("Truck Visit Id Please...");
			return false;
		}else{
			return true;
		}
		//return false;
	}
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/gateInDataSearch") ?>" onsubmit="return chkField();">

					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Truck Visit No. <span class="required">*</span></span>
								<input type="text" name="gate_pass" id="gate_pass" class="form-control login_input_text" tabindex="1" placeholder="Truck Visit No.">
							</div>												
						</div>
						<!--div class="row">
							<div class="col-sm-12 text-center">
								<?php echo $msg; ?>
							</div>													
						</div-->
																		
						<div class="row">
							<div class="col-sm-12 text-center">
								<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
								<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
							</div>													
						</div>						
					</div>	
				</form>
			
            <?php
				if($flag == 1)
				{
					include("mydbPConnection.php");
					// $truckByContQuery = "SELECT * FROM do_truck_details_entry WHERE id = '$gate_pass'";
					$truckByContQuery = "SELECT *,IF(NOW()>visit_time_slot_start AND NOW()<=visit_time_slot_end,1,IF(NOW()>visit_time_slot_end,0,2)) AS inst FROM do_truck_details_entry WHERE id = '$gate_pass'";;
					$truckResult = mysqli_query($con_cchaportdb,$truckByContQuery);
					
					$id="";
					$rot_no = "";
					$cont_no = "";
					$truck_id = "";
					$gate_no = "";
					$driver_name = "";
					$driver_gate_pass = "";
					$assistant_name = "";
					$assistant_gate_pass = "";
					$visit_time_slot_start = "";
					$visit_time_slot_end = "";
					$gate_in_status = "";
					$paid_status = "";
					$paid_amt = "";
					$paid_method = "";
					$verify_info_fcl_id = "";
					$verifyOtherDataId = "";
					$i=1;
					$driverImg = "";
					$helperImg = "";
					
					$gate_out_status = "";
					$gate_in_time = "";
					$gate_out_time = "";
					$dateinouttimej = "";
					
					while($row = mysqli_fetch_object($truckResult))
					{
						$id = $row->id;
						$rot_no = $row->import_rotation;
						$cont_no = $row->cont_no;
						$truck_id = $row->truck_id;
						$gate_no = $row->gate_no;
						$driver_name = $row->driver_name;
						$driver_gate_pass = $row->driver_gate_pass;
						$assistant_name = $row->assistant_name;
						$assistant_gate_pass = $row->assistant_gate_pass;
						$visit_time_slot_start = $row->visit_time_slot_start;
						$visit_time_slot_end = $row->visit_time_slot_end;
						$gate_in_status = $row->gate_in_status;
						$paid_status = $row->paid_status;
						$paid_amt = $row->paid_amt;
						$paid_method = $row->paid_method;
						$verify_info_fcl_id = $row->verify_info_fcl_id;
						$verifyOtherDataId = $row->verify_other_data_id;
						
						$gate_in_time = $row->gate_in_time;
						$gate_out_time = $row->gate_out_time;
						$gate_out_status = $row->gate_out_status;
						$dateinouttimej = $row->inst;
						
						$sql_driverImg = "SELECT nid_number,agent_photo
						FROM vcms_vehicle_agent
						WHERE card_number='$driver_gate_pass' ORDER BY agent_photo DESC LIMIT 1";
						$rslt_driverImg = mysqli_query($con_cchaportdb,$sql_driverImg);
						
						$driverImg = "";
						while($row_driverImg = mysqli_fetch_object($rslt_driverImg))
						{							
							$driverImg = $row_driverImg->agent_photo;							
						}
						
						$sql_helperImg = "SELECT nid_number,agent_photo
						FROM vcms_vehicle_agent
						WHERE card_number='$assistant_gate_pass' ORDER BY agent_photo DESC LIMIT 1";
						$rslt_helperImg = mysqli_query($con_cchaportdb,$sql_helperImg);
						
						$helperImg = "";
						while($row_helperImg = mysqli_fetch_object($rslt_helperImg))
						{							
							$helperImg = $row_helperImg->agent_photo;
						}
					}
//echo $driverImg;
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
						// $jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code,verify_other_data.cnf_lic_no FROM vcms_vehicle_agent 
						// INNER JOIN verify_other_data ON verify_other_data.jetty_sirkar_id = vcms_vehicle_agent.id
						// INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = verify_other_data.id
						// WHERE do_truck_details_entry.verify_other_data_id='$verifyOtherDataId'";
						$jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code FROM vcms_vehicle_agent 
						INNER JOIN lcl_dlv_assignment ON lcl_dlv_assignment.jetty_sirkar_id = vcms_vehicle_agent.id
						INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = lcl_dlv_assignment.id
						WHERE do_truck_details_entry.verify_other_data_id='$verifyOtherDataId'";
					}else if(is_null($verifyOtherDataId)){
						$jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code,verify_info_fcl.cnf_lic_no FROM vcms_vehicle_agent 
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

					include("mydbPConnectionn4.php");
					include("dbOracleConnection.php");

					




                   $cnfQuery="SELECT DISTINCT a.gkey,a.id AS cont_no,k.name  AS cnf, k.id AS cnf_lic,
				   CONCAT(k.address_line1,NVL(k.address_line2,'')) AS cnf_addr,
				   a.gkey, a.id AS cont_no, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
				   mfdch_desc, 
				   b.flex_date01,b.last_pos_slot,
				   (SELECT inv_unit_fcy_visit.flex_string10 FROM inv_unit
				   INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				   WHERE inv_unit.id=a.id ORDER BY inv_unit_fcy_visit.flex_date01 DESC fetch first 1 rows only) AS rot_no, 
				   (SELECT SUBSTR(nominal_length, -2) FROM ref_equip_type
				   INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey = ref_equip_type.gkey
				   INNER JOIN inv_unit ON inv_unit.eq_gkey = ref_equipment.gkey
				   INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				   WHERE inv_unit_fcy_visit.unit_gkey = a.gkey fetch first 1 rows only)  AS siz,
				   CAST(
				   (SELECT (SUBSTR(ref_equip_type.nominal_height,-2)/10) FROM ref_equip_type
				   INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey = ref_equip_type.gkey
				   INNER JOIN inv_unit ON inv_unit.eq_gkey = ref_equipment.gkey
				   INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				   WHERE inv_unit_fcy_visit.unit_gkey = a.gkey fetch first 1 rows only) AS DECIMAL(10,1)
				   ) 
				   AS height,
				   (SELECT vsl_vessels.name FROM vsl_vessels
				   INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey
				   WHERE vsl_vessel_visit_details.ib_vyg=b.flex_string10 fetch first 1 rows only) AS v_name
				   
				   
				   FROM inv_unit a
				   INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
				   INNER JOIN inv_goods j ON j.gkey = a.goods
				   INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.ib_vyg=b.flex_string10
				   LEFT JOIN ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
				   INNER JOIN config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value
				   WHERE a.id='$cont_no' AND vsl_vessel_visit_details.ib_vyg ='$rot_no'";
					
					$rsltCnf= oci_parse($con_sparcsn4_oracle,$cnfQuery);
					oci_execute($rsltCnf);
					$cnf="";
					$cnf_addr = "";
					$cnf_lic = "";
					$Yard_No = "";
					$assignmentType="";
					
					while(($cnfResult=oci_fetch_object($rsltCnf))!=false)
					{

					
						$cnf = $cnfResult->CNF;
						$cnf_addr = $cnfResult->CNF_ADDR ;
						$cnf_lic = $cnfResult->CNF_LIC;
						$last_pos_slot=$cnfResult->LAST_POS_SLOT;
					    $assignmentType = $cnfResult->MFDCH_VALUE;
						$cnfQuery2="SELECT ctmsmis.cont_yard('$last_pos_slot') AS Yard_No";
						$rsltCnf2 = mysqli_query($con_sparcsn4,$cnfQuery2);
						while($cnfResult2Row=mysqli_fetch_object($rsltCnf2)){
							 $Yard_No=$cnfResult2Row->Yard_No;
						}	
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

			<hr/>
				<div class="invoice">
					<div class="row">
						<table border="0" cellpadding="5" style="border:1px solid #000;margin-bottom:20px;" 
							align="center" width="80%">

							<tr>
								<td align="right" width="100px;"><nobr>Truck Visit No.: </nobr></td>
								<th align="left">&nbsp;<b><nobr><?php echo $gate_pass;?></nobr></b></th>
								<td align="right" >Assignment Type: </td>
								<td align="left">&nbsp;<b><?php echo $assignmentType; ?> </b></td>
								<td align="right">From: </td>
								<th align="left" width="140px;">&nbsp;<b><?php echo $visit_time_slot_start; ?></b></th>
								<td align="right">To: </td>
								<th align="left" width="140px;">&nbsp;<b><?php echo $visit_time_slot_end; ?></b></th>
							</tr>
						</table>

						<div class="col-sm-5 col-md-5">
							<h4 class="h4 mt-none mb-sm text-dark"><b><u>Jetty Sarkar Information </b></u></h4>
							<h6 class="h6 mt-none mb-sm">1. Vessel : <b><?php echo $Vessel_Name; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">2. Rotation : <b><?php echo $Import_Rotation_No; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">3. B/E No : <b><?php echo $beNo; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">4. Shed / Yard No : <b><?php echo $yardNo; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">5. Jetty Sarkar Name : <b><?php echo $agentName; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">6. Jetty Sarkar Lic. No. : <b><?php echo $agentCode; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">7. Container No. : <b><?php echo $cont_number; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">8. Goods Details : <b><?php echo $description_of_Goods; ?></b></h6>
						</div>

						<div class="col-sm-4 col-md-4">
							<h4 class="h4 mt-none mb-sm text-dark"><b><u>Transport Agency Information </b></u></h4>
							<h6 class="h6 mt-none mb-sm">1. Gate No : <b><?php echo $gate_no; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">2. Truck No : <b><?php echo $truck_id; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">3. Driver Name : <b><?php echo $driver_name; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">4. ID Card (Driver) : <b><?php echo $driver_gate_pass; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">5. Union Membership No. (Driver) : <b><?php echo ""; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">6. Assistant Name : <b><?php echo $assistant_name; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">7. ID Card (Assistant) : <b><?php echo $assistant_gate_pass; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">8. Union Membership No. (Assistant) : <b><?php echo ""; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">9. Transport Agency Name : <b><?php echo ""; ?></b></h6>
						</div>

						<div class="col-sm-3 col-md-3">
							<img src="<?php echo '/biometricPhoto/'.$driver_gate_pass.'/'.$driverImg; ?>" height="120px" width="120px">
						</div>
						
						<div class="col-sm-3 col-md-3">
							<img src="<?php echo '/biometricPhoto/'.$assistant_gate_pass.'/'.$helperImg; ?>" height="120px" width="120px">
						</div>

						<table border="0" cellpadding="5" style="border:1px solid #000;margin-bottom:20px;" 
							align="center" width="80%">

							<tr>
								<td align="right" width="100px;"><nobr>Payment Status: </nobr></td>
								<th align="left">&nbsp;<b><nobr><?php if($paid_status==1){echo "<font color='green'>Paid</font>";}else{ echo "<font color='red'>Not Paid</font>";} ?></nobr></b></th>
								<td align="right" >Payment Amount: </td>
								<td align="left">&nbsp;<b><?php echo $paid_amt; ?> </b></td>
								<td align="right">Payment Method: </td>
								<th align="left" width="140px;">&nbsp;<b><?php echo $paid_method; ?></b></th>

							</tr>
						</table>

					</div>
					<div class="row">

						<div class="col-sm-12 text-center">
							<?php
							// $cont_status = "RELEASE";
							if($cont_status == "DO_NOT_RELEASE")
							{
								?>
								<div class="col-sm-12 text-center">
									<font size="4" color="red"><b><?php echo $cont_no; ?> Container is Blocked by Custom!!</b></font>
								</div>
							<?php
							}
							else
							{
								if($paid_status == 1 and $gate_in_status == 1 and $gate_out_status == 1)
								{
							?>
								<font size="4" color="green"><b>You already used this ticket to Gate in at: <?php echo $gate_in_time?> and Gate out at <?php echo $gate_out_time; ?> In Gate No: <?php echo $gate_no; ?></b></font>
							<?php
								}
								else if($paid_status == 1 and $gate_in_status == 1)
								{
									if($gateFlag == 0){
							?>
								<font size="4" color="green"><b>You already used this ticket to Gate in at: <?php echo $gate_in_time?> In Gate No: <?php echo $gate_no; ?></b></font>
							
							<?php
								}else if($gateFlag == 1){
							?>
								<font size="4" color="green"><b></b><?php echo "Gate in Completed!!!";?></font>
							<?php
									}
								}
								else if($paid_status != 1)
								{
							?>
								<font size="4" color="red"><b>Your payment is not Paid</b></font>
							<?php
								// }
								// else if(!($gate_no == "Gate 3" or $gate_no == "Gate 5"))
								// {
							?>
								<!-- <font size="4" color="red"><b>Your Assigned Gate is: Gate 3 & gate 5 But you are In <?php //echo $gate_no; ?></b></font> -->
							<?php
								}
								else if($dateinouttimej == 0)
								{
							?>
								<font size="4" color="red"><b>Your have missed your time slot which was: <?php echo $visit_time_slot_start; ?> to <?php echo $visit_time_slot_end; ?></b></font>
								<form method="POST" action="<?php echo site_url("ShedBillController/gateInStsCng") ?>"  onsubmit="return confirm('Are You Sure?');">
									<input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
									<input type="hidden" name="gate_pass" id="gate_pass" value="<?php echo $gate_pass; ?>" />
									<button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary" <?php if($paid_status == 0){ echo "disabled"; } ?>>Gate In</button>
								</form>
							<?php
								}
								else if($dateinouttimej == 2)
								{
							?>
								<font size="4" color="red"><b>Your time  slot will start from: <?php echo $visit_time_slot_start; ?> to <?php echo $visit_time_slot_end; ?></b></font>
								<form method="POST" action="<?php echo site_url("ShedBillController/gateInStsCng") ?>"  onsubmit="return confirm('Are You Sure?');">
									<input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
									<input type="hidden" name="gate_pass" id="gate_pass" value="<?php echo $gate_pass; ?>" />
									<button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary" <?php if($paid_status == 0){ echo "disabled"; } ?>>Gate In</button>
								</form>
							<?php
								}
								else if($paid_status == 1)
								{
							?>
								<form method="POST" action="<?php echo site_url("ShedBillController/gateInStsCng") ?>"  onsubmit="return confirm('Are You Sure?');">
									<input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
									<input type="hidden" name="gate_pass" id="gate_pass" value="<?php echo $gate_pass; ?>" />
									<button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary" <?php if($paid_status == 0){ echo "disabled"; } ?>>Gate In</button>
								</form>
							<?php
								}
								else
								{
							?>
								<font size="4" color="red"><b>Please try again after your payment done or You are using wrong format</b></font>
							<?php
							}}
							?>
						</div>													

						<!--div class="col-sm-12 text-center">
							<?php
								if($gate_in_status == 0){
							?>
							<form method="POST" action="<?php echo site_url("ShedBillController/gateInStsCng") ?>"  onsubmit="return confirm('Are You Sure?');">
								<input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
								<input type="hidden" name="gate_pass" id="gate_pass" value="<?php echo $gate_pass; ?>" />
								<button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary" <?php if($paid_status == 0){ echo "disabled"; } ?>>Gate In</button>
							</form>
							<?php
								}else if($gate_in_status == 1){
							?>
							<font color="green"><b>Gate In Process Done</b></font>
							<?php
								}
							?>
						</div-->													
					</div>
				</div>
			</div>
            <?php
                }
			
                oci_close($con_sparcsn4_oracle);
				mysql_close(con_cchaportdb);
            ?>
		</section>
	</div>
</div>
</section>
</div>
