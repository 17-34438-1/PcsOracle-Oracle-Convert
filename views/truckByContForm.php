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

	function validate(){
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
	
	function validateById(){
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

	function chkCont(){
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

	function validateLoad()
	{
		var cont = document.getElementById('contLoad').value.trim();

		if(cont == ""){
			alert("Please select assignment!");
			return false;
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
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/truckSearchByCont") ?>" onsubmit="return validate();">

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
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/truckSearchByCont") ?>" onsubmit="return validateById();">

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
										<?php if(isset($msg3)){ echo $msg3;};?>
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
			
			$truckByContQuery = "SELECT do_truck_details_entry.id,truck_id,gate_no,vcms_vehicle_agent.card_number,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,load_st,gate_in_status,gate_out_status,actual_delv_pack,cont_no,import_rotation,actual_delv_unit FROM do_truck_details_entry 
			LEFT JOIN verify_info_fcl ON verify_info_fcl.id = do_truck_details_entry.verify_info_fcl_id
			LEFT JOIN vcms_vehicle_agent ON verify_info_fcl.jetty_sirkar_id = vcms_vehicle_agent.id
			WHERE do_truck_details_entry.id = '$visitId'";
			$truckResult = mysqli_query($con_cchaportdb,$truckByContQuery);
			
			$id="";
			$jsId = "";
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

			while($row = mysqli_fetch_object($truckResult)){
				$id = $row->id;
				$jsId = $row->card_number;
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
			}


			$packQuery = "SELECT CONCAT(Pack_Number,' ',Pack_Description) AS Pack_Number 
			FROM igm_details 
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id = igm_details.id
			WHERE cont_number = '$cont_no'";
			$packResult = mysqli_query($con_cchaportdb,$packQuery);
			$pack = "";
			while($packrow = mysqli_fetch_object($packResult)){
				$pack = $packrow->Pack_Number;
			}

			$blQuery = "SELECT igm_details.BL_No AS master_BL_No,igm_details.Import_Rotation_No,igm_detail_container.cont_number
			FROM igm_detail_container
			INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$import_rotation' AND igm_detail_container.cont_number='$cont_no'
			UNION
			SELECT igm_supplimentary_detail.master_BL_No,igm_supplimentary_detail.Import_Rotation_No,igm_sup_detail_container.cont_number
			FROM igm_sup_detail_container
			INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$import_rotation' AND igm_sup_detail_container.cont_number='$cont_no'";

			$blResult = mysqli_query($con_cchaportdb,$blQuery);

			$bl = "";

			while($blRow = mysqli_fetch_object($blResult)){
				$bl = $blRow->master_BL_No;
			}
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


			<div class="col-lg-6">
				<header class="panel-heading">
					<!--div class="panel-actions">
						<a href="#" class="fa fa-caret-down"></a>
						<a href="#" class="fa fa-times"></a>
					</div-->

					<h2 class="panel-title">User Action</h2>
					<!--p class="panel-subtitle">Customize the graphs as much as you want, there are so many options and features to display information using JSOFT Admin Template.</p-->
				</header>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php echo $msg; ?>
						</div>
					</div>
					<form method="POST" action="<?php echo site_url("ShedBillController/truckLoadStsCng") ?>" onsubmit="return validateLoad();">
						<!--div class="form-group">
							<div class="row">
								<div class="col-md-3 col-md-offset-3">
									<label>IGM Qty : </label>									
								</div>
								<div class="col-md-4">
									<label>&nbsp;<?php echo $pack; ?></label>		
								</div>
							</div>
						</div-->
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
									<nobr>Container</nobr>									
								</div>
								<div class="col-md-1">
									:
								</div>
								<div class="col-md-5">
									<select id="contLoad" name="contLoad" class="form-control">
										<option value="">--select--</option>
										<?php
											for($i=0;count($resGetContByCF)>$i;$i++){
										?>
											<option value="<?php echo $resGetContByCF[$i]['cont_no']; ?>" <?php if($cont_no == $resGetContByCF[$i]['cont_no']){ echo 'selected'; } ?>><?php echo $resGetContByCF[$i]['cont_no']." - ".$resGetContByCF[$i]['mfdch_value']; ?></option>
										<?php
											}
										?>
									</select>					
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
									<input type="text" name="actual_qty" id="actual_qty" class="form-control" size="4" value="<?php if($assignment_type=="OCD") echo "1.0"; else echo $delv_pack; ?>" placeholder="0.0"/>							
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
											
											if($assignment_type=="OCD")
											{
												$packUnitQuery = "SELECT id,Pack_Unit AS Pack_Description 
																	FROM igm_pack_unit
																	where Pack_Unit='Container'";
											}
											else
											{
												$packUnitQuery = "SELECT id,Pack_Unit AS Pack_Description FROM igm_pack_unit";
											}
											
											$packResult = mysqli_query($con_cchaportdb,$packUnitQuery);
											
											while($pack = mysqli_fetch_object($packResult))
											{
												$pack_id = $pack->id;
												$pack_unit = $pack->Pack_Description;
										?>
											<?php if($assignment_type=="OCD") { ?>
												<option value="<?php echo $pack_id; ?>"><?php echo $pack_unit; ?></option>
											<?php } else { ?>
												<option value="<?php echo $pack_id; ?>" <?php if($delv_unit == $pack_id){echo "selected";} ?>><?php echo $pack_unit; ?></option>
											<?php } ?>
										<?php } ?>
									</select>					
								</div>
							</div>	
						</div>
						
						<?php
							// if($cont_status == "DO_NOT_RELEASE")
							// {
						?>
								<!-- <div class="col-sm-12 text-center">
									<font size="4" color="red"><b><?php //echo $cont_no; ?> Container is Blocked!!</b></font>
								</div> -->
						<?php
							// }
							// else 
							if($gate_out_status == 1){
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
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Load</button>
									</div>													
								</div>
						<?php
								}else{
						?>
								<div class="row">
								<div class="col-sm-12 text-center">
										<!-- <font color="green" size="3"><b>Loaded!</b></font> -->
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

		<div class="col-md-12">
			<div class="col-lg-6">						
				
			</div>


			<div class="col-lg-6">
				<header class="panel-heading">

					<h2 class="panel-title">Additional Container</h2>

				</header>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php if(isset($msg1)){ echo $msg1; }?>
						</div>													
					</div>
					<form method="POST" action="<?php echo site_url("ShedBillController/additionalTruck") ?>">  <!--  onsubmit="return chkCont();"  -->
						
						<!-- <?php if($disputeFlag=="1"){ ?>
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
						<?php } ?> -->
						
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
											// SELECT do_truck_details_additional_cont.cont_no FROM do_truck_details_entry 
											// INNER JOIN do_truck_details_additional_cont ON do_truck_details_additional_cont.truck_visit_id = do_truck_details_entry.id
											// WHERE do_truck_details_entry.id  = '$id'";

											// $contResult = mysqli_query($con_cchaportdb,$cont_query);

											// $contNo = "";
											// while($contRow = mysqli_fetch_object($contResult)){
											// 	$contNo .= "'".$contRow->cont_no."'";
											// 	if(!is_null($contNo) || $contNo != ""){
											// 		$contNo .= ", ";
											// 	}
											// }
											// $total_cont =  substr($contNo, 0, -2);
											

											// $queryCont = "SELECT igm_detail_container.cont_number
											// FROM igm_detail_container
											// INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
											// WHERE igm_details.Import_Rotation_No='$import_rotation' AND igm_details.BL_No='$bl' AND igm_detail_container.cont_number NOT IN ($total_cont)
											// UNION
											// SELECT igm_sup_detail_container.cont_number
											// FROM igm_sup_detail_container
											// INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
											// WHERE igm_supplimentary_detail.Import_Rotation_No='$import_rotation' AND igm_supplimentary_detail.master_BL_No='$bl' AND igm_sup_detail_container.cont_number NOT IN ($total_cont)";
											// $contResult = mysqli_query($con_cchaportdb,$queryCont);

											// $cont = "";
								
											// while($contRow = mysqli_fetch_object($contResult)){
											// 	$cont = $contRow->cont_number;
										?>
											<!-- <option value="<?php //echo $cont; ?>"><?php echo $cont; ?></option> -->
										<?php
											// }

										?>

										<?php
											for($i=0;count($resGetContByCF)>$i;$i++){
										?>
											<option value="<?php echo $resGetContByCF[$i]['cont_no']; ?>" <?php if($cont_no == $resGetContByCF[$i]['cont_no']){ echo 'selected'; } ?>><?php echo $resGetContByCF[$i]['cont_no']." - ".$resGetContByCF[$i]['mfdch_value']; ?></option>
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
										// if($cont_status == "DO_NOT_RELEASE")
										// {
									?>
											<!-- <div class="col-sm-12 text-center">
												<font size="4" color="red"><b><?php //echo $cont_no; ?> Container is Blocked!!</b></font>
											</div> -->
									<?php
										// }
										// else
										// {
											if($gate_in_status==1){
									?>
												<input type="hidden" name="id" id="id" value="<?php echo $id;?>"/>
												<input type="hidden" name="visitId" id="visitId" value="<?php echo $visitId; ?>"/>
												<button type="submit" name="btn" class="btn btn-success" value="add" onclick = "return chkCont();" <?php if($gate_out_status == 1){echo "disabled";} ?>>Add</button>
									<?php
						
											}
											else
											{
									?>
												<font color="red" size="3"><b>Get in not done!</b></font>	
									<?php
											}
										// }
									?>
								</td>
							</tr>

							<?php
								$query_data = "SELECT do_truck_details_additional_cont.id,cont_no,pack_num,igm_pack_unit.Pack_Unit FROM do_truck_details_additional_cont 
								INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_additional_cont.pack_unit
								WHERE truck_visit_id = '$visitId'";
								$dataResult = mysqli_query($con_cchaportdb,$query_data);
								
								$id = "";
								$addCont = "";
								$qty = "";
								$unit = "";
								$z = 0;
								while($data = mysqli_fetch_object($dataResult))
								{
									$id = $data->id;
									$addCont = $data->cont_no;
									$qty = $data->pack_num;
									$unit = $data->Pack_Unit;
									$z++;
							?>
							<tr>
								<td class="text-center"><?php echo $addCont;?></td>
								<td class="text-center"><?php echo $qty;?></td>
								<td class="text-center"><?php echo $unit;?></td>
								<td class="text-center">
									<input type="hidden" name="totalAddedCont" id="totalAddedCont" value="<?php echo $z;?>"/>
									<input type="hidden" name="id" id="id" value="<?php echo $id;?>"/>
									<input type="hidden" name="visitId" id="visitId" value="<?php echo $visitId; ?>"/>
									<button type="submit" name="btn" class="btn btn-danger" value="delete" onclick="return confirm('Are you sure?');" <?php if($gate_out_status == 1){echo "disabled";} ?>>Delete</button>
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

	</div>
	<?php
		}
	?>
</section>
</div>
