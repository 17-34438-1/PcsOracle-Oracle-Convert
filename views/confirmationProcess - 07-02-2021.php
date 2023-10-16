<?php
	include("mydbPConnection.php");
	
	$truckQuery="SELECT id,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,
				traffic_chk_st,traffic_chk_by,traffic_chk_time,yard_security_chk_st,yard_security_chk_by,yard_security_chk_time,
				cnf_chk_st,cnf_chk_by,cnf_chk_time,actual_delv_pack,actual_delv_unit,verify_info_fcl_id,
				load_st,import_rotation,cont_no,visit_time_slot_start,visit_time_slot_end,paid_method,paid_status
				FROM do_truck_details_entry 
				WHERE id='$visit_id'";

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
	while($truckResult = mysqli_fetch_object($rsltTruck)){
		$truckDtlId = $truckResult->id;
		$actual_delv_pack = $truckResult->actual_delv_pack;
		$actual_delv_unit = $truckResult->actual_delv_unit;
		$verify_info_fcl_id = $truckResult->verify_info_fcl_id;
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
		$paid_status = $truckResult->paid_status;
		
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
	
	$jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code FROM vcms_vehicle_agent 
					INNER JOIN verify_info_fcl ON verify_info_fcl.jetty_sirkar_id = vcms_vehicle_agent.id
					INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_info_fcl_id = verify_info_fcl.id
					WHERE do_truck_details_entry.verify_info_fcl_id='$verify_info_fcl_id'";

					$rsltJetty = mysqli_query($con_cchaportdb,$jettySarkarQuery);

					$agentName = "";
					$agentCode = "";
					while($jettyInfo = mysqli_fetch_object($rsltJetty)){
						$agentName = $jettyInfo->agent_name;
						$agentCode = $jettyInfo->agent_code;
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
	$Yard_No = "";
	$assignmentType="";
	while($cnfResult=mysqli_fetch_object($rsltCnf)){
		$cnf = $cnfResult->cnf;
		$cnf_addr = $cnfResult->cnf_addr;
		$cnf_lic = $cnfResult->cnf_lic;
		$Yard_No = $cnfResult->Yard_No;
		$assignmentType = $cnfResult->mfdch_value;
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
								<div class="form-group">
								<input type="hidden" name="chk_st" id="chk_st" value="<?php echo $chk_st;?>">
								<input type="hidden" name="chk_by" id="chk_by" value="<?php echo $chk_by;?>">
								<input type="hidden" name="chk_time" id="chk_time" value="<?php echo $chk_time;?>">
								<input type="hidden" name="ah" id="ah" value="<?php echo $ah;?>">
								<input type="hidden" name="frmType" id="frmType" value="<?php echo $frmType;?>">
									<div class="col-md-4">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Search By <span class="required">*</span></span>
											<select name="searchBy" id="searchBy" class="form-control login_input_text" tabindex="1">
												<option value="container">Container</option>
												<option value="bl">BL</option>
											</select>
										</div>												
									</div>
									<div class="col-md-4">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Search Value <span class="required">*</span></span>
											<input type="text" name="searchValue" id="searchValue" class="form-control login_input_text" tabindex="2" placeholder="Search Value" onblur="getSearchInfo()">
										</div>												
									</div>
									<div class="col-md-4">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Truck Visit ID <span class="required">*</span></span>
											<select name="visit_id" id="visit_id" class="form-control login_input_text" tabindex="3">
											
											</select>
											<!--input type="text" name="visit_id" id="visit_id" class="form-control"  value="" required-->
										</div>
									</div>									
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">
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
					<section class="panel">
						<div class="panel-body">
							<div class="invoice">
								<div class="row">
									<table border="1" cellpadding="5" style="border:1px solid #000;margin-bottom:20px;" 
										align="center" width="92%">
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
										<h4 class="h4 mt-none mb-sm text-dark"><b><u>Jetty Sarkar Information </b></u></h4>
										<h6 class="h6 mt-none mb-sm">1. Vessel : <b><?php echo $Vessel_Name; ?></b></h6>
										<h6 class="h6 mt-none mb-sm">2. Rotation : <b><?php echo $Import_Rotation_No; ?></b></h6>
										<h6 class="h6 mt-none mb-sm">3. B/E No : <b><?php echo $beNo; ?></b></h6>
										<h6 class="h6 mt-none mb-sm">4. Shed / Yard No : <b><?php echo $Yard_No; ?></b></h6>
										<h6 class="h6 mt-none mb-sm">5. Jetty Sarkar Name : <b><?php echo $agentName; ?></b></h6>
										<h6 class="h6 mt-none mb-sm">6. Jetty Sarkar Lic. No. : <b><?php echo $agentCode; ?></b></h6>
										<h6 class="h6 mt-none mb-sm">7. Container No. : <b><?php echo $cont_number; ?></b></h6>
										<h6 class="h6 mt-none mb-sm">8. Delivery Pack. : <b><?php echo $actual_delv_pack; ?></b></h6>
										<h6 class="h6 mt-none mb-sm">9. Delivery Unit. : <b><?php echo $delv_unit; ?></b></h6>
										<!--h6 class="h6 mt-none mb-sm">10. Goods Details : <b><?php echo $description_of_Goods; ?></b></h6-->
									</div>
									<div class="col-sm-6 col-md-6">
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
										<h6 class="h6 mt-none mb-sm">10. Payment Status : <b><?php echo $payment_status; ?></b></h6>
										<table border="1" cellpadding="5" style="border:1px solid #000;">
											<tr>
												<td style="padding:3px;padding-right:3px;">
													<h6 class="h6 mb-sm">Payment Method : <b><?php echo ucfirst($paid_method); ?></b></h6>
												</td>
											</tr>
										</table>
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
										<?php if($loadState==1) { ?>
											<?php if($chk_status==0) {?>
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary">Confirm</button>
											<?php } else { if($msg=="") { ?>
												<h5 class="h5 mt-none mb-sm" style="color:#023ea6;"><b><?php echo $btnLabel;?></b></h5>
											<?php } }?>
										<?php } else { ?>
											<h5 class="h5 mt-none mb-sm" style="color:red;"><b>Not Loaded Yet !</b></h5>
										<?php } ?>
									</form>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									<b><?php echo $msg;?></b>
								</div>
							</div>
						</div>
						
					</section>
			
				</div>
			</div>	
		<!-- end: page -->
	</section>
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
</script>