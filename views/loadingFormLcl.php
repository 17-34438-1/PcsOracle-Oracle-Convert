<script>
	function getSearchInfo()
	{		
		var select = document.getElementById("visitId");
		var length = select.options.length;
		for (i = length-1; i >= 0; i--) {
		select.options[i] = null;
		}
		
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
					var select = document.getElementById("visitId");
					select.appendChild(option);

				}
							
				// document.getElementById('driverLicNo').value = licNo;
			}
		};
		
		var url = "<?php echo site_url('AjaxController/getSearchInfo')?>?searchBy="+searchBy+"&searchValue="+searchValue;		
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}

	function validate()
	{
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
	
	function validateById()
	{
		var searchValue = "";
		searchValue = document.getElementById('searchVal').value;
		searchValue = searchValue.trim();
		if(searchValue == "" || searchValue == null)
		{
			alert("Please Enter Valid Truck Visit No");
			return false;
		}else{
			return true;
		}
		
		return false;
	}

	function chkCont()
	{
		var cont = document.getElementById('cont').value.trim();
		var qty = document.getElementById('load_qty').value.trim();
		//alert(cont+qty);
		if(cont == "" || cont == null){
			alert("Please select Container!!");
			return false;
		}else if(qty == "" || qty == null){
			alert("Please fill load quantity!!");
			return false;
		}
		//return false;
	}

	// function chkbl()
	// {
	// 	var bl = document.getElementById('bl').value.trim();
	// 	var qty = document.getElementById('load_qty').value.trim();
	// 	//alert(cont+qty);
	// 	if(bl == "" || bl == null){
	// 		alert("Please select BL!!");
	// 		return false;
	// 	}else if(qty == "" || qty == null){
	// 		alert("Please fill load quantity!!");
	// 		return false;
	// 	}
	// 	//return false;
	// }

	function validateLoad()
	{
		var max_qty = document.getElementById('max_qty').value.trim();
		var actual_qty = document.getElementById('actual_qty').value.trim();
		var bl = document.getElementById('assignment').value.trim();

		if(bl == ""){
			alert("Please select assignment!");
			return false;
		}
		else if(actual_qty>0)
		{
			if(actual_qty>max_qty)
			{
				alert("Quantity can't be greater than "+max_qty);
				//return false;
			}
		}else{
			alert("Quantity must be greater than 0");
			//return false;
		}
	}

</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				
				<div class="col-md-6">
					<header class="panel-heading">
						<h2 class="panel-title">Entry Option</h2>
					</header>
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("LCL/searchforLoadingLcl") ?>" onsubmit="return validate();">

							<div class="form-group">
								<div>
									<!-- <label class="col-md-1 control-label">&nbsp;</label> -->

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
											<span class="input-group-addon span_width">Truck Visit Id. <span class="required">*</span></span>
											<select name="visitId" id="visitId" class="form-control login_input_text" tabindex="5">
											</select>
										</div>												
									</div>
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success" tabindex="6">Search</button>
									</div>													
								</div>						
							</div>	
						</form>
					</div>
				</div>
				
				<div class="col-md-6">
					<header class="panel-heading">
						<h2 class="panel-title">Scan Option</h2>
					</header>
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("LCL/searchforLoadingLcl") ?>" onsubmit="return validateById();">

							<div class="form-group">
								<div>
									<!-- <label class="col-md-1 control-label">&nbsp;</label> -->

									<div class="col-md-12">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Truck Visit No. <span class="required">*</span></span>
											<input type="text" name="visitId" id="searchVal" class="form-control login_input_text" tabindex="1" placeholder="Truck Visit No" autofocus>
										</div>												
									</div>	
								</div>
								
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php if(isset($msg3)){ echo $msg3;}?>
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
				</div>
			</section>
		</div>
	</div>
	
	<?php
		if($flag == 1){
			include("mydbPConnection.php");
			
			$truckByContQuery = "SELECT do_truck_details_entry.id, vcms_vehicle_agent.card_number, lcl_dlv_assignment.bl_no, verify_info_fcl_id,verify_other_data_id,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,load_st,gate_in_status,gate_out_status,actual_delv_pack,cont_no,import_rotation,actual_delv_unit,is_part_bl,update_by
			FROM do_truck_details_entry 
			LEFT JOIN lcl_dlv_assignment ON lcl_dlv_assignment.id = do_truck_details_entry.verify_other_data_id
			LEFT JOIN vcms_vehicle_agent ON lcl_dlv_assignment.jetty_sirkar_id = vcms_vehicle_agent.id
			WHERE do_truck_details_entry.id = '$visitId'";
			$truckResult = mysqli_query($con_cchaportdb,$truckByContQuery);
			
			$id="";
			$jsId = "";
			$verify_info_fcl_id = "";
			$verify_other_data_id = "";
			$truck_id = "";
			$gate_no = "";
			$cont_no = "";
			$import_rotation = "";
			$driver_name = "";
			$driver_gate_pass = "";
			$assistant_name = "";
			$assistant_gate_pass = "";
			$load_st = "";
			$gate_in_status = "";
			$gate_out_status = "";
			$delv_pack = "";
			$delv_unit = "";
			$i=1;
			$partBl = 0;
			$bl = "";
			$update_by = "";

			while($row = mysqli_fetch_object($truckResult)){
				$id = $row->id;
				$jsId = $row->card_number;
				$verify_info_fcl_id = $row->verify_info_fcl_id;
				$verify_other_data_id = $row->verify_other_data_id;
				$truck_id = $row->truck_id;
				$gate_no = $row->gate_no;
				$cont_no = $row->cont_no;
				$import_rotation = $row->import_rotation;
				$driver_name = $row->driver_name;
				$driver_gate_pass = $row->driver_gate_pass;
				$assistant_name = $row->assistant_name;
				$assistant_gate_pass = $row->assistant_gate_pass;
				$load_st = $row->load_st;
				$gate_in_status = $row->gate_in_status;
				$gate_out_status = $row->gate_out_status;
				$delv_pack = $row->actual_delv_pack;
				$delv_unit = $row->actual_delv_unit;
				$partBl = $row->is_part_bl;
				$bl = $row->bl_no;
				$update_by = $row->update_by;
			}


			$packQuery = "SELECT CONCAT(Pack_Number,' ',Pack_Description) AS Pack, Pack_Number , Pack_Description
			FROM igm_details 
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id = igm_details.id
			WHERE cont_number = '$cont_no'";
			$packResult = mysqli_query($con_cchaportdb,$packQuery);
			$pack = "";
			$pack_number = "";
			$pack_desc = "";
			while($packrow = mysqli_fetch_object($packResult)){
				$pack = $packrow->Pack;
				$pack_number = $packrow->Pack_Number;
				$pack_desc = $packrow->Pack_Description;
			}
			// echo $pack_number;
			// echo $pack_desc;

			// Master BL
			// For GETTING Master BL*-----------------------------------------------------
		/*	$masterblQuery = "SELECT igm_details.BL_No AS BL_NO
			FROM igm_detail_container
			INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$import_rotation' AND igm_detail_container.cont_number='$cont_no'
			UNION
			SELECT igm_supplimentary_detail.master_BL_No AS BL_NO
			FROM igm_sup_detail_container
			INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$import_rotation' AND igm_sup_detail_container.cont_number='$cont_no'";

			$masterblResult = mysqli_query($con_cchaportdb,$masterblQuery);

			$masterBl = "";

			while($masterblRow = mysqli_fetch_object($masterblResult))
			{
				$masterBl = $masterblRow->BL_NO;
			}*/

			// BL 

			/*
			if($partBl == 0)
			{
				$blQuery = "SELECT igm_details.BL_No AS BL_NO
				FROM igm_detail_container
				INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$import_rotation' AND igm_detail_container.cont_number='$cont_no'
				UNION
				SELECT igm_supplimentary_detail.master_BL_No AS BL_NO
				FROM igm_sup_detail_container
				INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE igm_supplimentary_detail.Import_Rotation_No='$import_rotation' AND igm_sup_detail_container.cont_number='$cont_no'";

				$blResult = mysqli_query($con_cchaportdb,$blQuery);

				$bl = "";

				while($blRow = mysqli_fetch_object($blResult))
				{
					$bl.= $blRow->BL_NO." , ";
				}
			}
			else
			{
				// $blQuery = "SELECT BL_NO FROM igm_sup_detail_container 
				// INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
				// WHERE cont_number='$cont_no' AND Import_Rotation_No='$import_rotation' AND cont_status='FCL/PART'";

				$blQuery = "SELECT BL_NO FROM igm_sup_detail_container 
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
				WHERE cont_number='$cont_no' AND Import_Rotation_No='$import_rotation'
				UNION
				SELECT igm_details.BL_No AS BL_NO
				FROM igm_detail_container
				INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$import_rotation' AND igm_detail_container.cont_number='$cont_no'";
			}

			$blResult = mysqli_query($con_cchaportdb,$blQuery);

			$bl = "";

			while($blRow = mysqli_fetch_object($blResult))
			{
				$bl.= $blRow->BL_NO." , ";
			}

			$bl = substr($bl,0,-2)
			*/
		?>


	<div class="row">
		<div class="col-md-12">
			<div class="col-lg-6">						
				
					<header class="panel-heading">
						<!--div class="panel-actions">
							<a href="#" class="fa fa-caret-down"></a>
							<a href="#" class="fa fa-times"></a>
						</div-->

						<h2 class="panel-title">Information</h2>
						<!--p class="panel-subtitle">Customize the graphs as much as you want, there are so many options and features to display information using JSOFT Admin Template.</p-->
					</header>
					<div class="panel-body">
						<div class="col-md-5">
							<label>BL No</label>									
						</div>
						<div class="col-md-1">
							 : 									
						</div>
						<div class="col-md-6">
							<label>&nbsp;<?php echo $bl; ?></label>									
						</div>
					
						<div class="col-md-5">
							<label>Container No</label>									
						</div>
						<div class="col-md-1">
							 : 									
						</div>
						<div class="col-md-6">
							<label>&nbsp;<?php echo $cont_no; ?></label>
							<label>
							</label>
						</div>
						
						<div class="col-md-5">
							<label>Gate No</label/>									
						</div>
						<div class="col-md-1">
							 : 									
						</div>
						<div class="col-md-6">
							<label>&nbsp;<?php echo $gate_no; ?></label>									
						</div>

						<div class="col-md-5">
							<label>Truck ID</label>									
						</div>
						<div class="col-md-1">
							: 									
						</div>
						<div class="col-md-6">
							<label>&nbsp;<?php echo $truck_id; ?></label>									
						</div>
						
						<div class="col-md-5">
							<label>Driver Name</label>									
						</div>
						<div class="col-md-1">
							 : 									
						</div>
						<div class="col-md-6">
							<label>&nbsp;<?php echo $driver_name; ?></label>									
						</div>
						
						<div class="col-md-5">
							<label>Driver Gate Pass</label>									
						</div>
						<div class="col-md-1">
							 : 									
						</div>
						<div class="col-md-6">
							<label>&nbsp;<?php echo $driver_gate_pass; ?></label>									
						</div>
						
						<div class="col-md-5">
							<label>Assistant Name</label>									
						</div>
						<div class="col-md-1">
							 : 									
						</div>
						<div class="col-md-6">
							<label>&nbsp;<?php echo $assistant_name; ?></label>									
						</div>
						
						<div class="col-md-5">
							<label>Assistant Gate Pass</label>									
						</div>
						<div class="col-md-1">
							 : 									
						</div>
						<div class="col-md-6">
							<label>&nbsp;<?php echo $assistant_gate_pass; ?></label>									
						</div>
						
						<!-- <div class="col-md-6">
							<label>Request Qty : </label>									
						</div>
						<div class="col-md-6">
							<label>&nbsp;<?php echo $delv_pack; ?></label>									
						</div> -->
					</div>
			</div>

			<?php
				// if($partBl == 0){
			?>
			<div class="col-lg-6">
				<header class="panel-heading">

					<h2 class="panel-title">User Action</h2>

				</header>
				<div class="panel-body">
					<form method="POST" action="<?php echo site_url("LCL/truckLoadStsCngLcl") ?>" onsubmit="return validateLoad();">
						<?php if($disputeFlag=="1"){ ?>
							<div class="form-group">								
								<div class="row">
									<div class="col-md-3 col-md-offset-2 text-right">
										<nobr>Disputed Qty</nobr>									
									</div>
									<div class="col-md-1">
										:
									</div>
									<div class="col-md-4">
										<b><?php echo $resDispute[0]['qty']." ".$resDispute[0]['pckUnit']; ?></b>
									</div>
								</div>								
							</div>

							<div class="form-group">								
								<div class="row">
									<div class="col-md-3 col-md-offset-2 text-right">
										<nobr>Remarks</nobr>									
									</div>
									<div class="col-md-1">
										:
									</div>
									<div class="col-md-4">
										<b><?php echo $resDispute[0]['remarks']; ?></b>
									</div>
								</div>								
							</div>
						<?php } ?>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-md-offset-1 text-right">
									<nobr>Jetty Sircar Gate Pass</nobr>									
								</div>
								<div class="col-md-1">
									:
								</div>
								<div class="col-md-5">
									<?php
										$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
										FROM vcms_vehicle_agent
										INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
										WHERE agent_type = 'Jetty Sircar'";
					
										$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
									?>
									<input type="text" list="jsPassList" name="jsPass" id="jsPass" class="form-control" size="4" value="<?php if($jsId!=""){echo $jsId;} ?>"/>
									<datalist id="jsPassList">
										<?php
											for($i=0;count($data_jsInfo)>$i;$i++){
										?>
											<option value="<?php echo $data_jsInfo[$i]['card_number']; ?>"/>
										<?php
											}
										?>
									</datalist>					
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-md-offset-1 text-right">
									<nobr>Reg & BL</nobr>									
								</div>
								<div class="col-md-1">
									:
								</div>
								<div class="col-md-5">
									<?php
										$sql_lic = "SELECT License_No FROM organization_profiles 
										INNER JOIN users ON users.org_id = organization_profiles.id
										WHERE login_id = '$update_by'";
							
										$data_lic = $this->bm->dataSelectDB1($sql_lic);
										$org_license = "";
										for($i=0;$i<count($data_lic);$i++){
											$org_license = $data_lic[$i]['License_No'];
										}
							
										$cnfLic = explode("/", $org_license);
										$cnfLic_firstpart = $cnfLic[0];
										$cnfLic_firstpart = ltrim($cnfLic_firstpart, '0');

										$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
										igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
										oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
										IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc,(SELECT shed_yard FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_yard
										FROM oracle_nts_data
										INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
										INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
										WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
										AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
										
										UNION 
										
										SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
										oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
										IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc,(SELECT shed_yard FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_yard
										FROM oracle_nts_data
										INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
										INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
										WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
										AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";
					
										$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);

										// if(empty($data_assignment))
										// {
										// 	if(substr($cnfLic[0], 0, 1)=='0' )
										// 	{
										// 		$cnfLic_firstpart = substr($cnfLic[0], 1);
										// 	}
										// 	else if (substr($cnfLic[0], 0, 2)=='00' )
										// 	{
										// 		$cnfLic_firstpart = substr($cnfLic[0], 2);
										// 	}
										// 	/* if(strlen($cnfLic[0])==1)
										// 		$cnfLic_firstpart = "000".$cnfLic[0];
										// 	else if(strlen($cnfLic[0])==2)
										// 		$cnfLic_firstpart = "00".$cnfLic[0];
										// 	else if(strlen($cnfLic[0])==3)
										// 		$cnfLic_firstpart = "0".$cnfLic[0];
										// 	else if(strlen($cnfLic[0])==4)
										// 		$cnfLic_firstpart = "".$cnfLic[0]; */
												
												
										// 	$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
										// 	igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
										// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
										// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc,(SELECT shed_yard FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_yard
										// 	FROM oracle_nts_data
										// 	INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
										// 	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
										// 	WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
										// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
											
										// 	UNION 
											
										// 	SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
										// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
										// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc,(SELECT shed_yard FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_yard
										// 	FROM oracle_nts_data
										// 	INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
										// 	INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
										// 	WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
										// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";
										// 	$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
										// }
									?>
									<select class="form-control" id="assignment" name="assignment">
										<?php
											for($i=0;count($data_assignment)>$i;$i++){
												$assignCont = $data_assignment[$i]['cont_number'];
												$sql_countTruck = "SELECT COUNT(*) AS rtnValue FROM do_truck_details_entry WHERE cont_no='$assignCont' AND DATE(last_update) = DATE(NOW())";
												$data_countTruck = mysqli_query($con_cchaportdb,$sql_countTruck);
												$truckAdded = null;

												while($row = mysqli_fetch_object($data_countTruck)){
													$truckAdded = $row->rtnValue;
												}

												$igm_type = $data_assignment[$i]['igm_type'];

												$igm_id = "";
												if($igm_type == "sup_dtl"){
													$igm_id = $data_assignment[$i]['igm_sup_detail_id'];
												}else if($igm_type == "dtl"){
													$igm_id = $data_assignment[$i]['igm_detail_id'];
												}

												if($igm_type == "sup_dtl"){
													$igm_type = "sup";
												}

												$result = $this->bm->chkBlockedContainerforTruckEntry($assignCont,$data_assignment[$i]['imp_rot_no'],$data_assignment[$i]['bl_no']);

												$custom_block_status = "";
												for($ij = 0; $ij<count($result);$ij++){
													$custom_block_status = $result[$ij]['custom_block_st'];
												}
												$custom_block = "";
												if($custom_block_status == "DO_NOT_RELEASE"){
													$custom_block = "Blocked.";
												}

												$assignRot = $data_assignment[$i]['imp_rot_no'];
												$assignContSts = $data_assignment[$i]['cont_status'];
												$assignBl = $data_assignment[$i]['bl_no'];
												$assignVerifyNo = $data_assignment[$i]['verify_no'];
												$assignCp = $data_assignment[$i]['cp_no'];
												$assignContSize = $data_assignment[$i]['cont_size'];

										?>
											<option value="<?php echo $data_assignment[$i]['shed_yard']."|".$data_assignment[$i]['cont_number']."|".$data_assignment[$i]['imp_rot_no']."|".$data_assignment[$i]['cont_status']."|".$data_assignment[$i]['bl_no']."|".$igm_type."|".$igm_id."|".$data_assignment[$i]['verify_no']."|".$data_assignment[$i]['cp_no']."|".$data_assignment[$i]['cont_size']."|".$custom_block; ?>" <?php if(isset($cont_no) && $data_assignment[$i]['cont_number'] == $cont_no){ echo "selected";}?>><?php echo $data_assignment[$i]['bl_no']." - ".$data_assignment[$i]['imp_rot_no']."  ( ".$truckAdded." Truck/s Added) "."(Shed: {$data_assignment[$i]['shed_loc']}) , {$custom_block}"; ?></option>
										<?php
											}
										?>
									</select>
									<input type="hidden" name="cfLoginId" value="<?php echo $update_by;?>"/>					
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3 col-md-offset-2 text-right">
									<nobr>Load Qty</nobr>									
								</div>
								<div class="col-md-1">
									:
								</div>
								<div class="col-md-4">
									<input type="text" name="actual_qty" id="actual_qty" class="form-control" size="4" value="<?php echo $delv_pack; ?>" placeholder="0.0"/>	
									<input type="hidden" name="max_qty" id="max_qty" value ="<?php echo $pack_number;?>"/>						
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3 col-md-offset-2 text-right">
									<label>Pack Unit</label>									
								</div>
								<div class="col-md-1">
									:
								</div>
								
								<div class="col-md-4">
									<select class="form-control" name="pack_unit" id="pack_unit">
										<?php
											$pack_id = "";
											$pack_unit = "";
											$packUnitQuery = "SELECT id,Pack_Unit AS Pack_Description FROM igm_pack_unit";
											$packResult = mysqli_query($con_cchaportdb,$packUnitQuery);
											
											while($pack = mysqli_fetch_object($packResult))
											{
												$pack_id = $pack->id;
												$pack_unit = $pack->Pack_Description;
										?>
											<option value="<?php echo $pack_id; ?>" <?php if($delv_unit == $pack_id){echo "selected";} ?>><?php echo $pack_unit; ?></option>
										<?php
											}
										?>
									</select>					
								</div>
							</div>	
						</div>
						
						<?php
							if($cont_status == "DO_NOT_RELEASE")
							{
						?>
								<div class="col-sm-12 text-center">
									<font size="4" color="red"><b><?php echo $cont_no; ?> Container is Blocked!!</b></font>
								</div>
						<?php
							}
							else if($gate_out_status == 1)
							{
						?>
							<div class="row">
								<div class="col-sm-12 text-center">
									<font color="red" size="3"><b>Truck already gate Out!</b></font>
								</div>													
							</div>
						<?php
							}
							else
							{
								if($gate_in_status==1){
									if($load_st == 0){
						?>
								<div class="row">
									<div class="col-sm-12 text-center">
										<input type="hidden" name="id" id="id" value="<?php echo $visitId; ?>"/>
										<button type="submit" name="load" class="mb-xs mt-xs mr-xs btn btn-success">Load</button>
									</div>													
								</div>
						<?php
								}else{
						?>
								<div class="row">
									<div class="col-sm-12 text-center">
										<input type="hidden" name="id" id="id" value="<?php echo $visitId; ?>"/>
										<button type="submit" name="update" class="mb-xs mt-xs mr-xs btn btn-success">Update</button>
									</div>													
								</div>
						<?php
								}
							}else{
						?>
								<div class="row">
									<div class="col-sm-12 text-center">
										<font color="red" size="3"><b>Get in not done!</b></font>
									</div>													
								</div>
						<?php
								}
							}
						?>
					</form>
				</div>
			</div>
		</div>
	</div>
			<?php
				// }
				// else
				// {
			?>
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-6">
				&nbsp;
			</div>

			<div class="col-lg-6">
				<header class="panel-heading">
					<h2 class="panel-title">Part BL</h2>
				</header>
				<div class="panel-body">
				<form method="POST" action="<?php echo site_url("LCL/additionalBL") ?>">  <!--  onsubmit="return chkCont();"  -->
						
						<table class="table table-bordered table-striped table-hover" style="background-color:#fff;">
							<tr>
								<th class="text-center"><nobr>BL</nobr></th>
								<th class="text-center"><nobr>Load Qty</nobr></th>
								<th class="text-center"><nobr>Pack unit</nobr></th>
								<th class="text-center"><nobr>Action</nobr></th>
							</tr>

							<tr>
								<td>
									<select class="form-control" name="bl" id="bl">
										<option value="">--select--</option>
										<?php
											
											// $bl_query = "SELECT bl_no FROM do_truck_details_additional_bl_lcl
											// INNER JOIN do_truck_details_entry ON do_truck_details_entry.id = do_truck_details_additional_bl_lcl.truck_visit_id
											// WHERE truck_visit_id = '$id'";

											// $blResult = mysqli_query($con_cchaportdb,$bl_query);

											// $blNo = "";
											// while($blRow = mysqli_fetch_object($blResult)){
											// 	$blNo .= "'".$blRow->bl_no."'";
											// 	if(!is_null($blNo) || $blNo != ""){
											// 		$blNo .= ", ";
											// 	}
											// }

											// $total_bl = "''";
											// if($blNo != ""){
											// 	$total_bl =  substr($blNo, 0, -2);
											// }

											// $cnfLicQuery = "SELECT License_No FROM organization_profiles WHERE id = (SELECT org_id FROM users WHERE login_id = '$update_by')";
											// $cnfLicRslt = mysqli_query($con_cchaportdb,$cnfLicQuery);
											// $cnfLic = "";
											// while($cnfLicRow = mysqli_fetch_object($cnfLicRslt)){
											// 	$cnfLic = $cnfLicRow->License_No;
											// }

											// $bl_query = "SELECT bl_no FROM do_truck_details_additional_bl_lcl
											// INNER JOIN do_truck_details_entry ON do_truck_details_entry.id = do_truck_details_additional_bl_lcl.truck_visit_id
											// WHERE truck_visit_id = '$id'";

											// $blResult = mysqli_query($con_cchaportdb,$bl_query);

											// $blNo = "";
											// while($blRow = mysqli_fetch_object($blResult)){
											// 	$blNo .= "'".$blRow->bl_no."'";
											// 	if(!is_null($blNo) || $blNo != ""){
											// 		$blNo .= ", ";
											// 	}
											// }

											// $total_bl = "''";
											// if($blNo != ""){
											// 	$total_bl =  substr($blNo, 0, -2);
											// }

											// $section = $this->session->userdata('section');

											// $sectionShortNameQuery = "SELECT short_name FROM users_section_detail WHERE id = '$section'";
											// $sectionShortNameResult = $this->bm->dataSelectDB1($sectionShortNameQuery);
											// $sectionShortName = "";
											// for($ss=0;$ss<count($sectionShortNameResult);$ss++){
											// 	$sectionShortName = $sectionShortNameResult[$ss]['short_name'];
											// }

											// $cnfLicEx = explode("/", $cnfLic);
											// $cnfLic_firstpart = $cnfLicEx[0];

											// $additional_bl_query = "SELECT oracle_nts_data.bl_no,shed_yard FROM oracle_nts_data 
											// INNER JOIN igm_details ON igm_details.id = oracle_nts_data.igm_detail_id
											// INNER JOIN shed_tally_info ON shed_tally_info.igm_detail_id = igm_details.id
											// WHERE cnf_lno like '$cnfLic_firstpart%' AND shed_tally_info.shed_yard = '$sectionShortName' AND oracle_nts_data.bl_no NOT IN ($total_bl)
											
											// UNION
											
											// SELECT oracle_nts_data.bl_no,shed_yard FROM oracle_nts_data 
											// INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id = oracle_nts_data.igm_sup_detail_id
											// INNER JOIN shed_tally_info ON igm_supplimentary_detail.id = shed_tally_info.igm_sup_detail_id
											// WHERE cnf_lno like '$cnfLic_firstpart%' AND shed_tally_info.shed_yard = '$sectionShortName' AND oracle_nts_data.bl_no NOT IN ($total_bl) ORDER BY bl_no";

											// // return;
											// $blResult = mysqli_query($con_cchaportdb,$additional_bl_query);

											// $addBl = "";
											// print_r($blResult);
											// if(empty($blResult))
											// {
											// 	if(substr($cnfLic_firstpart[0], 0, 1)=='0' )
											// 	{
											// 		$cnfLic_firstpart = substr($cnfLic_firstpart[0], 1);
											// 	}
											// 	else if (substr($cnfLic_firstpart[0], 0, 2)=='00' )
											// 	{
											// 		$cnfLic_firstpart = substr($cnfLic_firstpart[0], 2);
											// 	}
												
											// 	echo $additional_bl_query = "SELECT oracle_nts_data.bl_no,shed_yard FROM oracle_nts_data 
											// 	INNER JOIN igm_details ON igm_details.id = oracle_nts_data.igm_detail_id
											// 	INNER JOIN shed_tally_info ON shed_tally_info.igm_detail_id = igm_details.id
											// 	WHERE cnf_lno like '$cnfLic_firstpart%' AND shed_tally_info.shed_yard = '$sectionShortName' AND oracle_nts_data.bl_no NOT IN ($total_bl)
												
											// 	UNION
												
											// 	SELECT oracle_nts_data.bl_no,shed_yard FROM oracle_nts_data 
											// 	INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id = oracle_nts_data.igm_sup_detail_id
											// 	INNER JOIN shed_tally_info ON igm_supplimentary_detail.id = shed_tally_info.igm_sup_detail_id
											// 	WHERE cnf_lno like '$cnfLic_firstpart%' AND shed_tally_info.shed_yard = '$sectionShortName' AND oracle_nts_data.bl_no NOT IN ($total_bl) ORDER BY bl_no";

											// 	// return;
											// 	$blResult = mysqli_query($con_cchaportdb,$additional_bl_query);
											
											// }											
								
											// while($blRow = mysqli_fetch_object($blResult))
											// {
											// 	$addBl = $blRow->bl_no;
										?>
											<!-- <option value="<?php //echo $addBl; ?>"><?php //echo $addBl; ?></option> -->
										<?php
											// }
										?>

										<?php

											for($i=0;count($data_assignment)>$i;$i++){
											$assignCont = $data_assignment[$i]['cont_number'];

											$sql_countTruck = "SELECT COUNT(*) AS rtnValue FROM do_truck_details_entry WHERE cont_no='$assignCont' AND DATE(last_update) = DATE(NOW())";
											$data_countTruck = mysqli_query($con_cchaportdb,$sql_countTruck);
											$truckAdded = null;

											while($row = mysqli_fetch_object($data_countTruck)){
												$truckAdded = $row->rtnValue;
											}

											$igm_type = $data_assignment[$i]['igm_type'];

											$igm_id = "";
											if($igm_type == "sup_dtl"){
												$igm_id = $data_assignment[$i]['igm_sup_detail_id'];
											}else if($igm_type == "dtl"){
												$igm_id = $data_assignment[$i]['igm_detail_id'];
											}

											if($igm_type == "sup_dtl"){
												$igm_type = "sup";
											}

											$result = $this->bm->chkBlockedContainerforTruckEntry($assignCont,$data_assignment[$i]['imp_rot_no'],$data_assignment[$i]['bl_no']);

											$custom_block_status = "";
											for($ij = 0; $ij<count($result);$ij++){
												$custom_block_status = $result[$ij]['custom_block_st'];
											}
											$custom_block = "";
											if($custom_block_status == "DO_NOT_RELEASE"){
												$custom_block = "Blocked.";
											}

											$assignPartRot = $data_assignment[$i]['imp_rot_no'];
											$assignPartBl = $data_assignment[$i]['bl_no'];


										?>
										<!-- <option value="<?php //echo $assignPartBl; ?>"><?php //echo $assignPartBl; ?></option> -->

										<option value="<?php echo $assignPartBl; ?>" > <?php echo $assignPartBl." - ".$assignPartRot."  ( ".$truckAdded." Truck/s Added) "."(Shed: {$data_assignment[$i]['shed_loc']}) , {$custom_block}"; ?></option>

										<?php
											}
										?>
									</select>
								</td>
									
								<td>
									<input type="text" name="load_qty" id="load_qty" class="form-control" size="4" value="" placeholder="0.0"/>
								</td>

								<td>
									<select class="form-control" name="pack_unit" id="pack_unit">
										<?php
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
								</td>

								<td>
									<?php
										if($cont_status == "DO_NOT_RELEASE")
										{
									?>
											<div class="col-sm-12 text-center">
												<font size="4" color="red"><b><?php echo $cont_no; ?> Container is Blocked!!</b></font>
											</div>
									<?php
										}
										else
										{
											if($gate_in_status==1)
											{
									?>
												<input type="hidden" name="id" id="id" value="<?php echo $id;?>"/>
												<input type="hidden" name="visitId" id="visitId" value="<?php echo $visitId; ?>"/>
												<button type="submit" name="btn" class="btn btn-success" value="add" onclick = "return chkbl();" <?php if($gate_out_status == 1){echo "disabled";} ?>>Add</button>
									<?php
						
											}
											else
											{
									?>
												<font color="red" size="3"><b>Get in not done!</b></font>	
									<?php
											}
										}
									?>
								</td>
							</tr>

							<?php
								$query_data = "SELECT do_truck_details_additional_bl_lcl.id,bl_no,pack_num,igm_pack_unit.Pack_Unit FROM do_truck_details_additional_bl_lcl
								INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_additional_bl_lcl.pack_unit
								WHERE truck_visit_id = '$visitId'";
								$dataResult = mysqli_query($con_cchaportdb,$query_data);
								
								$addBlId = "";
								$addBl = "";
								$qty = "";
								$unit = "";
								$z = 0;
								while($data = mysqli_fetch_object($dataResult))
								{
									$addBlId = $data->id;
									$addBl = $data->bl_no;
									$qty = $data->pack_num;
									$unit = $data->Pack_Unit;
									$z++;
							?>
							<tr>
								<td class="text-center"><?php echo $addBl;?></td>
								<td class="text-center"><?php echo $qty;?></td>
								<td class="text-center"><?php echo $unit;?></td>
								<td class="text-center">
									<input type="hidden" name="totalAddedBl" id="totalAddedBl" value="<?php echo $z;?>"/>
									<input type="hidden" name="id" id="id" value="<?php echo $addBlId;?>"/>
									<input type="hidden" name="visitId" id="visitId" value="<?php echo $visitId; ?>"/>
									<button type="submit" name="btn" class="btn btn-danger" value="delete" onclick="return confirm('Are you sure?');">Delete</button>
								</td>
							</tr>
							<?php
								}
							?>

						</table>
					</form>
				</div>
			</div>
		</div>
			<?php
				// }
			?>
		</div>
	</div>
	
	<!-- <div class="row">
		<div class="col-md-12">
			<div class="col-lg-6">
				&nbsp;
			</div>

			<div class="col-lg-6">
				<header class="panel-heading">

					<h2 class="panel-title">Additional Container</h2>

				</header>
				<div class="panel-body">
					<form method="POST" action="<?php echo site_url("LCL/additionalTruck") ?>"> 
						
						<table class="table table-bordered table-striped table-hover" style="background-color:#fff;">
							<tr>
								<th class="text-center"><nobr>Container</nobr></th>
								<th class="text-center"><nobr>Load Qty</nobr></th>
								<th class="text-center"><nobr>Pack unit</nobr></th>
								<th class="text-center"><nobr>Action</nobr></th>
							</tr>

							<tr>
								<td>
									<select class="form-control" name="cont" id="cont">
										<option value="">--select--</option>
										<?php

											// $cont_query = "SELECT cont_no FROM do_truck_details_entry 
											// WHERE do_truck_details_entry.id  = '$id'
											// UNION
											// SELECT do_truck_details_additional_cont_lcl.cont_no FROM do_truck_details_entry 
											// INNER JOIN do_truck_details_additional_cont_lcl ON do_truck_details_additional_cont_lcl.truck_visit_id = do_truck_details_entry.id
											// WHERE do_truck_details_entry.id  = '$id'";

											// $contResult = mysqli_query($con_cchaportdb,$cont_query);

											// $contNo = "";
											// while($contRow = mysqli_fetch_object($contResult)){
											// 	$contNo .= "'".$contRow->cont_no."'";
											// 	if(!is_null($contNo) || $contNo != ""){
											// 		$contNo .= ", ";
											// 	}
											// }
											
											// $total_cont = "''";
											// if($contNo != ""){
											// 	$total_cont =  substr($contNo, 0, -2);
											// }

											// $queryCont = "SELECT igm_detail_container.cont_number
											// FROM igm_detail_container
											// INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
											// WHERE igm_details.Import_Rotation_No='$import_rotation' AND igm_details.BL_No='$bl' AND igm_detail_container.cont_number NOT IN ($total_cont)
											// UNION
											// SELECT igm_sup_detail_container.cont_number
											// FROM igm_sup_detail_container
											// INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
											// WHERE igm_supplimentary_detail.Import_Rotation_No='$import_rotation' AND igm_supplimentary_detail.BL_No='$bl' AND igm_sup_detail_container.cont_number NOT IN ($total_cont)";
											// $contResult = mysqli_query($con_cchaportdb,$queryCont);

											// $cont = "";

											// while($contRow = mysqli_fetch_object($contResult)){
											// 	$cont = $contRow->cont_number;
										?>
											<option value="<?php //echo $cont; ?>"><?php //echo $cont; ?></option>
										<?php
											// }

										?>
									</select>
								</td>
									
								<td>
									<input type="text" name="load_qty" id="load_qty" class="form-control" size="4" value="" placeholder="0.0"/>
								</td>

								<td>
									<select class="form-control" name="pack_unit" id="pack_unit">
										<?php
											// $pack_id = "";
											// $pack_unit = "";
											// $packUnitQuery = "SELECT id,Pack_Unit AS Pack_Description FROM igm_pack_unit";
											// $packResult = mysqli_query($con_cchaportdb,$packUnitQuery);
											
											// while($pack = mysqli_fetch_object($packResult))
											// {
											// 	$pack_id = $pack->id;
											// 	$pack_unit = $pack->Pack_Description;
										?>
											<option value="<?php //echo $pack_id; ?>"><?php //echo $pack_unit; ?></option>
										<?php
											// }
										?>
									</select>
								</td>

								<td>
									<?php
										// if($cont_status == "Blocked")
										// {
									?>
											<div class="col-sm-12 text-center">
												<font size="4" color="red"><b><?php //echo $cont_no; ?> Container is Blocked!!</b></font>
											</div>
									<?php
										// }
										// else
										// {
											//if($gate_in_status==1){
									?>
												<input type="hidden" name="id" id="id" value="<?php //echo $id;?>"/>
												<input type="hidden" name="visitId" id="visitId" value="<?php //echo $visitId; ?>"/>
												<button type="submit" name="btn" class="btn btn-success" value="add" onclick = "return chkbl();" <?php //if($gate_out_status == 1){echo "disabled";} ?>>Add</button>
									<?php
						
											// }
											// else
											// {
									?>
												<font color="red" size="3"><b>Get in not done!</b></font>	
									<?php
											// }
										// }
									?>
								</td>
							</tr>

							<?php
								// $query_data = "SELECT do_truck_details_additional_cont_lcl.id,cont_no,pack_num,igm_pack_unit.Pack_Unit FROM do_truck_details_additional_cont_lcl
								// INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_additional_cont_lcl.pack_unit
								// WHERE truck_visit_id = '$visitId'";
								// $dataResult = mysqli_query($con_cchaportdb,$query_data);
								
								// $id = "";
								// $addCont = "";
								// $qty = "";
								// $unit = "";
								// $z = 0;
								// while($data = mysqli_fetch_object($dataResult))
								// {
								// 	$id = $data->id;
								// 	$addCont = $data->cont_no;
								// 	$qty = $data->pack_num;
								// 	$unit = $data->Pack_Unit;
								// 	$z++;
							?>
							<tr>
								<td class="text-center"><?php //echo $addCont;?></td>
								<td class="text-center"><?php //echo $qty;?></td>
								<td class="text-center"><?php //echo $unit;?></td>
								<td class="text-center">
									<input type="hidden" name="totalAddedCont" id="totalAddedCont" value="<?php echo $z;?>"/>
									<input type="hidden" name="id" id="id" value="<?php echo $id;?>"/>
									<input type="hidden" name="visitId" id="visitId" value="<?php echo $visitId; ?>"/>
									<button type="submit" name="btn" class="btn btn-danger" value="delete" onclick="return confirm('Are you sure?');" <?php if($gate_out_status == 1){echo "disabled";} ?>>Delete</button>
								</td>
							</tr>
							<?php
								// }
							?>

						</table>
					</form>
				</div>
			</div>
		</div>

	</div> -->
	<?php
		}
	?>
</section>
</div>
