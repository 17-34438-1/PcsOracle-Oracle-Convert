<?php
	include("mydbPConnection.php");

	$truckQuery="SELECT do_truck_details_entry.id,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,
			traffic_chk_st,traffic_chk_by,traffic_chk_time,yard_security_chk_st,yard_security_chk_by,yard_security_chk_time,
			cnf_chk_st,cnf_chk_by,cnf_chk_time,actual_delv_pack,actual_delv_unit,verify_info_fcl_id,
			load_st,import_rotation,cont_no,visit_time_slot_start,visit_time_slot_end,paid_method,paid_status,verify_other_data_id,add_truck_st,do_truck_details_entry.update_by,u_name
			FROM do_truck_details_entry
			INNER JOIN users ON users.login_id =  do_truck_details_entry.update_by
			WHERE do_truck_details_entry.id='$visit_id'";

	$rsltTruck = mysqli_query($con_cchaportdb,$truckQuery);

	$loadState = "";
	$btnLabel = "";
	$chk_status = "";
	$truckDtlId = "";
	$gate_no = "";
	$truck_id = "";
	$driver_name = "";
	$driver_gate_pass = "";
	$assistant_name = "";
	$assistant_gate_pass = "";
	$rot_no = "";
	$cont_no = "";
	$traffic_chk_by = "";
	$yard_security_chk_by = "";
	$cnf_chk_by = "";
	$visit_time_slot_start = "";
	$visit_time_slot_end = "";
	$paid_method = "";
	$paid_status = "";
	$payment_status = "";
	$actual_delv_pack = "";
	$actual_delv_unit = "";
	$verify_info_fcl_id = "";
	$verifyOtherDataId = "";
	$add_truck_st = "";
	$update_by = "";
	$u_name = "";

	while($truckResult = mysqli_fetch_object($rsltTruck)){
		$truckDtlId = $truckResult->id;
		$actual_delv_pack = $truckResult->actual_delv_pack;
		$actual_delv_unit = $truckResult->actual_delv_unit;
		$verify_info_fcl_id = $truckResult->verify_info_fcl_id;
		$verifyOtherDataId = $truckResult->verify_other_data_id;
		$gate_no = $truckResult->gate_no;
		$truck_id = $truckResult->truck_id;
		$driver_name = $truckResult->driver_name;
		$driver_gate_pass = $truckResult->driver_gate_pass;
		$assistant_name = $truckResult->assistant_name;
		$assistant_gate_pass = $truckResult->assistant_gate_pass;
		$rot_no = $truckResult->import_rotation;
		$cont_no = $truckResult->cont_no;
		$loadState = $truckResult->load_st;
		$visit_time_slot_start = $truckResult->visit_time_slot_start;
		$visit_time_slot_end = $truckResult->visit_time_slot_end;
		$paid_method = $truckResult->paid_method;
		$add_truck_st = $truckResult->add_truck_st;
		$paid_status = $truckResult->paid_status;
		$update_by = $truckResult->update_by;
		$u_name = $truckResult->u_name;
		
		if($paid_status==0)
		{
			$payment_status="Non-Paid";
		}
		else
		{
			$payment_status="Paid";
		}
		
		$traffic_chk_by = $truckResult->traffic_chk_by;
		$yard_security_chk_by = $truckResult->yard_security_chk_by;
		$cnf_chk_by = $truckResult->cnf_chk_by;
		
		if($ah=="sec")
		{
			$chk_status = $truckResult->yard_security_chk_st;
			if($chk_status==1)
			{
				$btnLabel = "Already Confirmed by ".$yard_security_chk_by." !";
			}
			else
			{
				$btnLabel = "Confirm";
			}
		}
		else if($ah=="cf")
		{
			$chk_status = $truckResult->cnf_chk_st;
			if($chk_status==1)
			{
				$btnLabel = "Already Confirmed by ".$cnf_chk_by." !";
			}
			else
			{
				$btnLabel = "Confirm";
			}
		}
		else
		{
			$chk_status = $truckResult->traffic_chk_st;
			if($chk_status==1)
			{
				$btnLabel = "Already Confirmed by ".$traffic_chk_by." !";
			}
			else
			{
				$btnLabel = "Confirm";
			}
		}
	}
	$strUnitQuery="SELECT * FROM igm_pack_unit WHERE id='$actual_delv_unit'";
	$rsltUnitQuery = mysqli_query($con_cchaportdb,$strUnitQuery);
	$delv_unit="";
	while($rowUnitQuery = mysqli_fetch_object($rsltUnitQuery)){
		$delv_unit = $rowUnitQuery->Pack_Unit;
	}
	
	$jettySarkarQuery = "";

	if(is_null($verify_info_fcl_id))
	{
		// $jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code,verify_other_data.cnf_lic_no FROM vcms_vehicle_agent 
		// INNER JOIN verify_other_data ON verify_other_data.jetty_sirkar_id = vcms_vehicle_agent.id
		// INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = verify_other_data.id
		// WHERE do_truck_details_entry.verify_other_data_id='$verifyOtherDataId'";
		
		// intakhab - 2021-07-08
		// $jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code 
		// FROM vcms_vehicle_agent 
		// INNER JOIN lcl_dlv_assignment ON lcl_dlv_assignment.jetty_sirkar_id = vcms_vehicle_agent.id
		// INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = lcl_dlv_assignment.id
		// WHERE do_truck_details_entry.verify_other_data_id='$verifyOtherDataId'";
		
		// intakhab - 2021-07-08
		$jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code,card_number,agent_photo,driver_gate_pass,
		(SELECT agent_name FROM vcms_vehicle_agent WHERE card_number=driver_gate_pass LIMIT 1) AS driver_name,
		(SELECT agent_photo FROM vcms_vehicle_agent WHERE card_number=driver_gate_pass AND agent_photo!='' LIMIT 1) AS driver_photo 
		FROM vcms_vehicle_agent 
		INNER JOIN lcl_dlv_assignment ON lcl_dlv_assignment.jetty_sirkar_id = vcms_vehicle_agent.id
		INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = lcl_dlv_assignment.id
		WHERE do_truck_details_entry.verify_other_data_id='$verifyOtherDataId'";
	}
	else if(is_null($verifyOtherDataId))
	{
		// intakhab - 2021-07-08
		// $jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code,verify_info_fcl.cnf_lic_no 
		// FROM vcms_vehicle_agent 
		// INNER JOIN verify_info_fcl ON verify_info_fcl.jetty_sirkar_id = vcms_vehicle_agent.id
		// INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_info_fcl_id = verify_info_fcl.id
		// WHERE do_truck_details_entry.verify_info_fcl_id='$verify_info_fcl_id'";
		
		// intakhab - 2021-07-08
		$jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code,card_number,agent_photo,verify_info_fcl.cnf_lic_no,
		driver_gate_pass,
		(SELECT agent_name FROM vcms_vehicle_agent WHERE card_number=driver_gate_pass) AS driver_name,
		(SELECT agent_photo FROM vcms_vehicle_agent WHERE card_number=driver_gate_pass) AS driver_photo 
		FROM vcms_vehicle_agent 
		INNER JOIN verify_info_fcl ON verify_info_fcl.jetty_sirkar_id = vcms_vehicle_agent.id 
		INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_info_fcl_id = verify_info_fcl.id 
		WHERE do_truck_details_entry.verify_info_fcl_id='$verify_info_fcl_id' AND do_truck_details_entry.id='$visit_id'";
	}
	// echo $jettySarkarQuery;return;
	$agentName = "";
	$agentCode = "";
	$jettySircarId = "";
    $jettyPhoto = "";
	
	$driverName = "";	
    $driverId = "";
    $driverPhoto = "";
	
	if($jettySarkarQuery != ""){
		$rsltJetty = mysqli_query($con_cchaportdb,$jettySarkarQuery);

		while($jettyInfo = mysqli_fetch_object($rsltJetty)){
			$agentName = $jettyInfo->agent_name;
			$agentCode = $jettyInfo->agent_code;
			$jettySircarId = $jettyInfo->card_number;
			$jettyPhoto = $jettyInfo->agent_photo;
			
			$driverName = $jettyInfo->driver_name;;	
			$driverId = $jettyInfo->driver_gate_pass;
			$driverPhoto = $jettyInfo->driver_photo;
		}
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
	include("mydbPConnectionn4.php");

	// $cnfQuery="SELECT DISTINCT a.gkey,a.id AS cont_no,k.name  AS cnf, k.id AS cnf_lic,
	// CONCAT(k.address_line1,IFNULL(k.address_line2,'')) AS cnf_addr,
	// a.gkey, a.id AS cont_no, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
	// mfdch_desc,k.sms_number,
	// (SELECT ctmsmis.cont_yard(b.last_pos_slot)) AS Yard_No, b.flex_date01,
	// (SELECT ctmsmis.cont_block(b.last_pos_slot, Yard_No)) AS Block_No,
	// (SELECT inv_unit_fcy_visit.flex_string10 FROM sparcsn4.inv_unit
	// INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.`unit_gkey`=inv_unit.`gkey`
	// WHERE inv_unit.id=a.id ORDER BY inv_unit_fcy_visit.flex_date01 DESC LIMIT 1) AS rot_no,
	// (SELECT RIGHT(nominal_length, 2) FROM sparcsn4.ref_equip_type
	// INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
	// INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
	// WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey)  AS size,
	// CAST((SELECT (RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10) FROM sparcsn4.ref_equip_type
	// INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
	// INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
	// WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey) AS DECIMAL(10,1))  AS height,
	// (SELECT sparcsn4.vsl_vessels.name FROM sparcsn4.vsl_vessels
	// INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vessel_gkey=sparcsn4.vsl_vessels.gkey
	// WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg=b.flex_string10 LIMIT 1) AS v_name
	// FROM sparcsn4.inv_unit a
	// INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
	// INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods
	// INNER JOIN vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.ib_vyg=b.flex_string10
	// LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
	// INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value
	// WHERE a.id='$cont_no' AND sparcsn4.vsl_vessel_visit_details.ib_vyg ='$rot_no'";
	include("dbOracleConnection.php");


	$cnfQuery="

		SELECT DISTINCT b.unit_gkey,a.gkey,a.id AS cont_no,k.name  AS cnf, k.id AS cnf_lic,
		CONCAT(k.address_line1,NVL(k.address_line2,'')) AS cnf_addr,
		a.gkey, a.id AS cont_no, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
		mfdch_desc,k.sms_number,b.last_pos_slot,
		(SELECT inv_unit_fcy_visit.flex_string10 FROM inv_unit
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit.id=a.id ORDER BY inv_unit_fcy_visit.flex_date01 DESC fetch first 1 rows only) AS rot_no,

		(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only
		) as siz,
		(SELECT vsl_vessels.name FROM vsl_vessels
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey
		WHERE vsl_vessel_visit_details.ib_vyg=b.flex_string10 fetch first 1 rows only) AS v_name,

		CAST(
		(select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only
		)
		AS DECIMAL(10,1)
		)  AS height


		FROM inv_unit a
		INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
		INNER JOIN inv_goods j ON j.gkey = a.goods
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.ib_vyg=b.flex_string10
		LEFT JOIN ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
		INNER JOIN config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value
		WHERE a.id='$cont_no' AND vsl_vessel_visit_details.ib_vyg ='$rot_no'	              ";

		$rsltCnf  = oci_parse($con_sparcsn4_oracle, $cnfQuery);
		oci_execute($rsltCnf);

	$cnf="";
	$cnf_addr = "";
	$cnf_lic = "";
	$Yard_No = "";
	$assignmentType="";
	$unit_gkey="";
	while(($cnfResult= oci_fetch_object($rsltCnf)) != false){

	// while($cnfResult=mysqli_fetch_object($rsltCnf)){

		$unit_gkey=$cnfResult->UNIT_GKEY;
		$cnf = $cnfResult->CNF ;
		$cnf_addr = $cnfResult->CNF_ADDR;
		$cnf_lic = $cnfResult->CNF_LIC;
		$assignmentType = $cnfResult->MFDCH_VALUE;
		$last_pos_slot=$cnfResult->LAST_POS_SLOT;

		$cnfQuery2="SELECT ctmsmis.cont_yard('$last_pos_slot') AS Yard_No";
		$rsltQuery2 = mysqli_query($con_sparcsn4,$cnfQuery2);
		$result2=mysqli_fetch_object($rsltQuery2);
		$Yard_No=$result2->Yard_No;

		$cnfQuery3="SELECT ctmsmis.cont_block('$last_pos_slot', '$Yard_No') AS Block_No";
		$rsltQuery3 = mysqli_query($con_sparcsn4,$cnfQuery3);
		$result3=mysqli_fetch_object($rsltQuery3);
		$blockNo=$result3->Block_No;


	}
?>
<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-lg-6">						
							<section class="panel">
								<!--header class="panel-heading">
									<h2 class="panel-title" align="right">
										<a href="<?php echo site_url('ControllerName/FunctionName') ?>">
											<button style="margin-left: 35%" class="btn btn-primary btn-sm">
												<i class="fa fa-list"></i>
											</button>
										</a>
									</h2>								
								</header-->
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php //echo $msg;?>
										</div>
									</div>
									<form class="form-horizontal form-bordered" method="POST" id="myform" name="myform" 
										action="<?php echo site_url("ShedBillController/getVehicleData");?>" onsubmit="return chkValidate();">
										
										<!--form class="form-horizontal form-bordered" method="POST" id="myform" name="myform" 
										action="<?php echo site_url("ShedBillController/getVehicleData");?>"-->
										<div class="form-group">
										<input type="hidden" name="chk_st" id="chk_st" value="<?php echo $chk_st;?>">
										<input type="hidden" name="chk_by" id="chk_by" value="<?php echo $chk_by;?>">
										<input type="hidden" name="chk_time" id="chk_time" value="<?php echo $chk_time;?>">
										<input type="hidden" name="ah" id="ah" value="<?php echo $ah;?>">
										<input type="hidden" name="frmType" id="frmType" value="<?php echo $frmType;?>">
											<div class="col-md-12">		
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Search By <span class="required">*</span></span>
													<select name="searchBy" id="searchBy" class="form-control login_input_text" tabindex="3">
														<option value="container">Container</option>
														<option value="bl">BL</option>
													</select>
												</div>												
											</div>
											<div class="col-md-12">		
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Search Value <span class="required">*</span></span>
													<input type="text" name="searchValue" id="searchValue" class="form-control login_input_text" tabindex="4" placeholder="Search Value" onblur="getSearchInfo()">
												</div>												
											</div>
											<div class="col-md-12">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Truck Visit ID <span class="required">*</span></span>
													<select name="visit_id" id="visit_id" class="form-control login_input_text" tabindex="5">
													
													</select>
													<!--input type="text" name="visit_id" id="visit_id" class="form-control"  value="" required-->
												</div>
											</div>									
											<div class="row">
												<div class="col-sm-12 text-center">
													<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success" tabindex="6">
														Show
													</button>
												</div>													
											</div>
											<div class="row">
												<div class="col-sm-12 text-center">
													
												</div>
											</div>
										</div>	
									</form>
								</div>
							</section>
					
						</div>
					
						<div class="col-lg-6">						
							<section class="panel">
								<!--header class="panel-heading">
									<h2 class="panel-title" align="right">
										<a href="<?php echo site_url('ControllerName/FunctionName') ?>">
											<button style="margin-left: 35%" class="btn btn-primary btn-sm">
												<i class="fa fa-list"></i>
											</button>
										</a>
									</h2>								
								</header-->
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php //echo $msg;?>
										</div>
									</div>
									<form class="form-horizontal form-bordered" method="POST" id="myform" name="myform" 
										action="<?php echo site_url("ShedBillController/getVehicleData");?>" onsubmit="return chkData();">
										<div class="form-group">
										<input type="hidden" name="chk_st" id="chk_st" value="<?php echo $chk_st;?>">
										<input type="hidden" name="chk_by" id="chk_by" value="<?php echo $chk_by;?>">
										<input type="hidden" name="chk_time" id="chk_time" value="<?php echo $chk_time;?>">
										<input type="hidden" name="ah" id="ah" value="<?php echo $ah;?>">
										<input type="hidden" name="frmType" id="frmType" value="<?php echo $frmType;?>">
											
											
											<div class="col-md-12">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Truck Visit No <span class="required">*</span></span>
													<input type="text" name="visit_id" id="truc_visit_id" class="form-control login_input_text" tabindex="1" autofocus>
													<!--input type="text" name="visit_id" id="visit_id" class="form-control"  value="" required-->
												</div>
											</div>									
											<div class="row">
												<div class="col-sm-12 text-center">
													<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success" tabindex="2">
														Show
													</button>
												</div>													
											</div>
											<div class="row">
												<div class="col-sm-12 text-center">
													
												</div>
											</div>
										</div>	
									</form>
								</div>
							</section>
					
						</div>
					</div>
					
					<section class="panel">
						<div class="panel-body">
							<div class="invoice">
								<div class="row">
									<table border="1" cellpadding="5" style="border:1px solid #000;margin-bottom:20px;" 
										align="center" width="100%">
										<tr>
											<td align="center">
												<div class="col-sm-4 col-md-3">
													<h5 class="h5 mb-sm">Truck Visit No: <b><?php echo $visit_id; ?></b></h5>
												</div>
												<div class="col-sm-4 col-md-3">
													<h5 class="h5 mb-sm">
														Assignment Type: <b><?php echo $assignmentType; ?></b>
													</h5>
												</div>
												<div class="col-sm-4 col-md-6">
													<h5 class="h5 mb-sm">
														Assignment Slot: 
														<b><?php echo $visit_time_slot_start."</b>"." to "."<b>".$visit_time_slot_end."</b>"; ?>
													</h5>
												</div>
											</td>
										</tr>
									</table>
									<div class="col-sm-6 col-md-6">
										<h4 class="h4 mt-none mb-sm text-dark"><b><u>Jetty Sircar Information </b></u></h4>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-4">1. Vessel</div>
												<div class="col-md-1">:</div>
												<div class="col-md-7"> <b><?php echo $Vessel_Name; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-4">2. Rotation</div> 
												<div class="col-md-1">:</div>
												<div class="col-md-7"> <b><?php echo $Import_Rotation_No; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-4">3. B/E No</div>
												<div class="col-md-1">:</div>
												<div class="col-md-7"> <b><?php echo $beNo; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-4">4. Shed / Yard No</div>
												<div class="col-md-1">:</div>
												<div class="col-md-7"> <b><?php echo $Yard_No; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-4">5. Jetty Sircar Name</div>
												<div class="col-md-1">:</div>
												<div class="col-md-7"> <b><?php echo $agentName; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-4">6. Jetty Sircar Lic. No.</div>
												<div class="col-md-1">:</div>
												<div class="col-md-7"> <b><?php echo $agentCode; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-4">7. Container No.</div>
												<div class="col-md-1">:</div>
												<div class="col-md-7"> <b><?php echo $cont_number; ?></b></div>
											</div>
										</h6>
										
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-4">8. C&F Name</div>
												<div class="col-md-1">:</div>
												<div class="col-md-7"> <b><?php echo $u_name; ?></b></div>
											</div>
										</h6>

										<?php
											if($verify_info_fcl_id != "")
											{
										?>

										<table class="table table-bordered">
											<tr>
												<th class="text-center"><font size="2">Container</font></th>
												<th class="text-center"><font size="2">Delivery Pack</font></th>
												<th class="text-center"><font size="2">Delivery Unit</font></th>
											</tr>
											<tr>
												<td class="text-center"><font size="2"><?php echo $cont_number; ?></font></td>
												<td class="text-center"><font size="2"><?php echo $actual_delv_pack; ?></font></td>
												<td class="text-center"><font size="2"><?php echo $delv_unit; ?></font></td>
											</tr>
											<?php
												if($add_truck_st == 1){
													$query_Add = "SELECT * FROM do_truck_details_additional_cont WHERE truck_visit_id = '$truckDtlId'";
													$query_Result = mysqli_query($con_cchaportdb,$query_Add);
													while($queryRslt = mysqli_fetch_object($query_Result)){
														$addCont = $queryRslt->cont_no;
														$addPackNum = $queryRslt->pack_num;
														$addPackUnit = $queryRslt->pack_unit;
														
														$addStrUnitQuery="SELECT * FROM igm_pack_unit WHERE id='$addPackUnit'";
														$rsltAddUnitQuery = mysqli_query($con_cchaportdb,$addStrUnitQuery);
														$addDelv_unit="";
														while($rowAddUnitQuery = mysqli_fetch_object($rsltAddUnitQuery)){
															$addDelv_unit = $rowAddUnitQuery->Pack_Unit;
														}
											?>
												<tr>
													<td class="text-center"><font size="2"><?php echo $addCont; ?></font></td>
													<td class="text-center"><font size="2"><?php echo $addPackNum; ?></font></td>
													<td class="text-center"><font size="2"><?php echo $addDelv_unit; ?></font></td>
												</tr>
											<?php
													}
												}
											?>

										</table>

										<?php
											}else{
										?>

										<table class="table table-bordered">
											<tr>
												<th class="text-center"><font size="2">BL</font></th>
												<th class="text-center"><font size="2">Delivery Pack</font></th>
												<th class="text-center"><font size="2">Delivery Unit</font></th>
											</tr>
											<tr>
												<?php

													$truck_type_query = "SELECT verify_info_fcl_id,verify_other_data_id FROM do_truck_details_entry WHERE id ='$truckDtlId'";
													$truck_type_rslt = $this->bm->dataSelectDB1($truck_type_query);
													$verify_info_fcl_id = null;
													$verify_other_data_id = null;
													for($a=0;$a<count($truck_type_rslt);$a++){
														$verify_info_fcl_id = $truck_type_rslt[$a]['verify_info_fcl_id'];
														$verify_other_data_id = $truck_type_rslt[$a]['verify_other_data_id'];
													}

													if(is_null($verify_other_data_id))
													{
														$bl_query = "SELECT BL_No 
														FROM igm_details 
														INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
														WHERE igm_details.Import_Rotation_No='$rot_no' AND igm_detail_container.cont_number='$cont_number'";
													}
													else
													{
														$bl_query = "SELECT bl_no AS BL_No FROM lcl_dlv_assignment
														INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = lcl_dlv_assignment.id
														WHERE do_truck_details_entry.id='$truckDtlId'";
													}

													$bl_data = $this->bm->dataSelectDB1($bl_query);
													$bl = "";
													for($a=0;$a<count($bl_data);$a++){
														$bl = $bl_data[$a]['BL_No'];
													}
												?>
												<td class="text-center"><font size="2"><?php echo $bl; ?></font></td>
												<td class="text-center"><font size="2"><?php echo $actual_delv_pack; ?></font></td>
												<td class="text-center"><font size="2"><?php echo $delv_unit; ?></font></td>
											</tr>
											<?php
											$add_truck_st_query = "SELECT count(*) as rtnValue FROM do_truck_details_additional_bl_lcl WHERE truck_visit_id = '$truckDtlId'";
											$add_truck_st = $this->bm->dataReturnDB1($add_truck_st_query);

												if($add_truck_st > 0){
													$query_Add = "SELECT * FROM do_truck_details_additional_bl_lcl WHERE truck_visit_id = '$truckDtlId'";
													$query_Result = mysqli_query($con_cchaportdb,$query_Add);
													while($queryRslt = mysqli_fetch_object($query_Result)){
														$bl_no = $queryRslt->bl_no;
														$addPackNum = $queryRslt->pack_num;
														$addPackUnit = $queryRslt->pack_unit;
														
														$addStrUnitQuery="SELECT * FROM igm_pack_unit WHERE id='$addPackUnit'";
														$rsltAddUnitQuery = mysqli_query($con_cchaportdb,$addStrUnitQuery);
														$addDelv_unit="";
														while($rowAddUnitQuery = mysqli_fetch_object($rsltAddUnitQuery)){
															$addDelv_unit = $rowAddUnitQuery->Pack_Unit;
														}
											?>
												<tr>
													<td class="text-center"><font size="2"><?php echo $bl_no; ?></font></td>
													<td class="text-center"><font size="2"><?php echo $addPackNum; ?></font></td>
													<td class="text-center"><font size="2"><?php echo $addDelv_unit; ?></font></td>
												</tr>
											<?php
													}
												}
											?>

										</table>

										<?php
											}
										?>

										<?php
											$orgTypeId = $this->session->userdata('org_Type_id');
											if($orgTypeId == 2 && $agentName == "")
											{
										?>
										<h6 class="h6 mt-none mb-sm">
											<div class="row text-center">
												<div class="col-md-12 text-center">
													<?php
														$data_jsInfo = null;
														$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
														FROM vcms_vehicle_agent
														INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
														WHERE agency_code = (SELECT organization_profiles.License_No FROM organization_profiles WHERE id = (SELECT org_id FROM users WHERE login_id = '$update_by')) AND agent_type = 'Jetty Sircar'";

														$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
													?>

													<input type="hidden" id="jsName" value="<?php echo $agentName;?>"/>
													
													<!-- <input type="hidden" name="trucVisitId" value="<?php echo $truckDtlId;?>"/> -->
													<div class="input-group mb-md">
														<span class="input-group-addon span_width">Jetty Sarkar </span>
														<select class="form-control" name="jsGatePass" id="jsGatePass" onchange="showJsPic(this.value)">
															<option value="">Select</option>
															<?php
																for($i=0;count($data_jsInfo)>$i;$i++){
															?>
																<option value="<?php echo $data_jsInfo[$i]['card_number']; ?>"><?php echo $data_jsInfo[$i]['js_name']." - ".$data_jsInfo[$i]['card_number']; ?></option>
															<?php
																}
															?>
														</select>
													</div>

												</div>
											</div>
										</h6>
										<?php
											}
										?>

										<h6 class="h6 mt-none mb-sm">
											<div class="row text-center">
												<div class="col-md-12 text-center">
													<img src="<?php echo '/biometricPhoto/'.$jettySircarId.'/'.$jettyPhoto; ?>" height="120px" width="120px">
													<p style="padding-top:5px;">Jetty Sircar Name:<br/><?php echo $agentName; ?></p>
												</div>
											</div>
										</h6>
									</div>
									<!--h6 class="h6 mt-none mb-sm">
										<div class="row text-center">
											<div class="col-md-12 text-center">
												<img src="<?php echo '/biometricPhoto/'.$jettySircarId.'/'.$jettyPhoto; ?>" height="120px" width="120px">
												<p style="padding-top:5px;">Jetty Sircar Name:<br/><?php echo $agentName; ?></p>
											</div>
										</div>
									</h6-->
									<div class="col-sm-6 col-md-6">
										<h4 class="h4 mt-none mb-sm text-dark"><b><u>Transport Agency Information </b></u></h4>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-6">1. Gate No</div>
												<div class="col-md-1">:</div>
												<div class="col-md-5"> <b><?php echo $gate_no; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-6">2. Truck No</div>
												<div class="col-md-1">:</div>
												<div class="col-md-5"> <b><?php echo $truck_id; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-6">3. Driver Name</div>
												<div class="col-md-1">:</div>
												<div class="col-md-5"> <b><?php echo $driver_name; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-6">4. ID Card (Driver)</div>
												<div class="col-md-1">:</div>
												<div class="col-md-5"> <b><?php echo $driver_gate_pass; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-6">5. Union Membership No. (Driver)</div>
												<div class="col-md-1">:</div>
												<div class="col-md-5"> <b><?php echo ""; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-6">6. Assistant Name</div>
												<div class="col-md-1">:</div>
												<div class="col-md-5"> <b><?php echo $assistant_name; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-6">7. ID Card (Assistant)</div>
												<div class="col-md-1">:</div>
												<div class="col-md-5"> <b><?php echo $assistant_gate_pass; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-6">8. Union Membership No. (Assistant)</div>
												<div class="col-md-1">:</div>
												<div class="col-md-5"> <b><?php echo ""; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-6">9. Transport Agency Name</div>
												<div class="col-md-1">:</div>
												<div class="col-md-5"> <b><?php echo ""; ?></b></div>
											</div>
										</h6>
										<h6 class="h6 mt-none mb-sm">
											<div class="row">
												<div class="col-md-6">10. Payment Status</div>
												<div class="col-md-1">:</div>
												<div class="col-md-5"> <b><?php echo $payment_status; ?></b></div>
											</div>
										</h6>
										<table border="1" cellpadding="5" style="border:1px solid #000;">
											<tr>
												<td style="padding:3px;padding-right:3px;">
													<h6 class="h6 mb-sm">Payment Method : <b><?php echo ucfirst($paid_method); ?></b></h6>
												</td>
											</tr>
										</table>
										<h6 class="h6 mt-none mb-sm">
											<div class="row text-center">
												<div class="col-md-12 text-center">
													<img src="<?php echo '/biometricPhoto/'.$driverId.'/'.$driverPhoto; ?>" height="120px" width="120px">
													<p style="padding-top:5px;">Driver Name:<br/><?php echo $driverName; ?></p>
												</div>
											</div>
										</h6>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									<form method="post" action="<?php echo site_url('ShedBillController/updateChkStatus'); ?>" 
										onsubmit="return validate();">
										<input type="hidden" name="visit_id" value="<?php echo $visit_id;?>">
										<!--input type="hidden" name="rot_no" value="<?php echo $rot_no;?>">
										<input type="hidden" name="cont_no" value="<?php echo $cont_no;?>"-->
										<input type="hidden" name="chk_st" value="<?php echo $chk_st;?>">
										<input type="hidden" name="chk_by" value="<?php echo $chk_by;?>">
										<input type="hidden" name="chk_time" value="<?php echo $chk_time;?>">
										<input type="hidden" name="ah" value="<?php echo $ah;?>">
										<input type="hidden" name="truckDtlId" id="truckDtlId" value="<?php echo $truckDtlId;?>">
										<input type="hidden" name="chk_status" id="chk_status" value="<?php echo $chk_status;?>">
										<input type="hidden" name="btnLabel" id="btnLabel" value="<?php echo $btnLabel;?>">
										<input type="hidden" name="ah" id="ah" value="<?php echo $ah;?>">

										<?php if($cont_status == "DO_NOT_RELEASE")
											{
										?>
											<div class="col-sm-12 text-center">
												<font size="4" color="red"><b><?php echo $cont_no; ?> Container is Blocked!!</b></font>
											</div>
										<?php	
											}else{
												if($loadState==1) { 
												 if($chk_status==0) {?>
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary">Confirm</button>
												<button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" data-toggle="modal" data-target="#disputeModal">Dispute</button>
											<?php } else { if($msg=="") { ?>
												<h5 class="h5 mt-none mb-sm" style="color:#023ea6;"><b><?php echo $btnLabel;?></b></h5>
											<?php } }?>
										<?php } else { ?>
											<h5 class="h5 mt-none mb-sm" style="color:red;"><b>Not Loaded Yet !</b></h5>
										<?php }
											} 
										?>
									</form>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									<b><?php echo $msg;?></b>
									<b><?php echo $disputeMsg;?></b>
								</div>
							</div>
						</div>
						
					</section>
			
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>
	
	<div class="modal fade" id="disputeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
		  	<div class="modal-title" id="exampleModalLabel" style="color: #0dce0f; font-size:18px; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase;">
				<img style="margin-top: 10px;margin-left:42%;" src="<?php echo ASSETS_WEB_PATH?>fimg/logocpa.png" height="50px" width="50px" alt="Logo"><br/>
					<span style="margin-left:30%;">Port Community System</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
		  </div>
		  <div class="modal-body">
			<form method="post" action="<?php echo site_url('ShedBillController/loadingDispute'); ?>" 
				onsubmit="return validateDispute();">
				<input type="hidden" name="visit_id" value="<?php echo $visit_id;?>">

				<input type="hidden" name="chk_st" value="<?php echo $chk_st;?>">
				<input type="hidden" name="chk_by" value="<?php echo $chk_by;?>">
				<input type="hidden" name="chk_time" value="<?php echo $chk_time;?>">
				<input type="hidden" name="ah" value="<?php echo $ah;?>">
				<input type="hidden" name="truckDtlId" id="truckDtlId" value="<?php echo $truckDtlId;?>">
				<input type="hidden" name="chk_status" id="chk_status" value="<?php echo $chk_status;?>">
				<input type="hidden" name="btnLabel" id="btnLabel" value="<?php echo $btnLabel;?>">
				<input type="hidden" name="ah" id="ah" value="<?php echo $ah;?>">
				
				<div class="col-md-12">		
					<div class="input-group mb-md">
						<span class="input-group-addon span_width">Load Qty <span class="required">*</span></span>
						<input type="text" name="loadQty" id="loadQty" class="form-control login_input_text" tabindex="2" value="<?php echo $actual_delv_pack; ?>" placeholder="load qty" >
					</div>												
				</div>
				
				<div class="col-md-12">		
					<div class="input-group mb-md">
						<span class="input-group-addon span_width">Pack Unit <span class="required">*</span></span>
						<select class="form-control" name="pack_unit" id="pack_unit">
						<?php
							include("mydbPConnection.php");
							$pack_id = "";
							$pack_unit = "";
							$packUnitQuery = "SELECT id,Pack_Unit AS Pack_Description FROM igm_pack_unit";
							$packResult = mysqli_query($con_cchaportdb,$packUnitQuery);
							
							while($pack = mysqli_fetch_object($packResult))
							{
								$pack_id = $pack->id;
								$pack_unit = $pack->Pack_Description;
						?>
							<option value="<?php echo $pack_id; ?>"><?php echo $pack_unit; ?></option>
						<?php
							}
						?>
					</select>
					</div>												
				</div>
				
				<div class="col-md-12">
					<div class="input-group mb-md">
						<span class="input-group-addon span_width">Remarks <span class="required">*</span></span>
						<textarea class="form-control" name="remarks" id="remarks" placeholder="remarks..."></textarea>
						<!--input type="text" name="visit_id" id="visit_id" class="form-control"  value="" required-->
					</div>
				</div>									
				<div class="row">
					<div class="col-sm-12 text-center">
						<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">
							Save
						</button>
					</div>													
				</div>
				
			</form>
		  </div>
		</div>
	  </div>
	</div>
	
<script>
	function validate()
	{
		if (confirm("Do you want to confirm?") == true)
		{
			return true ;
		}
		else
		{
			return false;
		}
	}
	
	function getSearchInfo()
	{		
		var searchBy = document.getElementById('searchBy').value;
		var searchValue = document.getElementById('searchValue').value;
		
		if (window.XMLHttpRequest) 
		{
			xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
				
		xmlhttp.onreadystatechange=function(){		 
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{							
				var val = xmlhttp.responseText;
				var jsonData = JSON.parse(val);				
				
				var id = "";
				var details = "";

				for(i=0;jsonData.searchResult.length>i;i++)
				{
					id = jsonData.searchResult[i].id;
					details = jsonData.searchResult[i].details;
					// var option = "<option value='"+id+"'>"+details+"</option>";
					// document.getElementById('visitId').innerHTML = option;

					var option = document.createElement("option");
					option.text = details;
					option.value = id;
					var select = document.getElementById("visit_id");
					select.appendChild(option);

				}
							
				// document.getElementById('driverLicNo').value = licNo;
			}
		};
		
		var url = "<?php echo site_url('AjaxController/getSearchInfo')?>?searchBy="+searchBy+"&searchValue="+searchValue;		
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}
	
	function chkValidate(){
		var searchBy = document.getElementById('searchBy').value;
		var searchValue = document.getElementById('searchValue').value;

		if(!searchBy)
		{
			alert("Please Select SearchBy...");
			return false;
		}
		else if(!searchValue)
		{
			alert("Please Assign a value...");
			return false;
		}else{
			return true;
		}
		
		return false;
	}

	function chkData(){
		var visitId = "";
		visitId = document.getElementById('truc_visit_id').value;
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