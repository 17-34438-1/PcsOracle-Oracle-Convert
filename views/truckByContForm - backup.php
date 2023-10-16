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
			
			$truckByContQuery = "SELECT id,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,load_st,gate_in_status,actual_delv_pack,cont_no FROM do_truck_details_entry WHERE id = '$visitId'";
			$truckResult = mysqli_query($con_cchaportdb,$truckByContQuery);
			
			
			
			$id="";
			$truck_id = "";
			$gate_no = "";
			$cont_no = "";
			$driver_name = "";
			$driver_gate_pass = "";
			$assistant_name = "";
			$assistant_gate_pass = "";
			$load_st = "";
			$gate_in_status = "";
			$delv_pack = "";
			$i=1;
			while($row = mysqli_fetch_object($truckResult)){
				$id = $row->id;
				$truck_id = $row->truck_id;
				$gate_no = $row->gate_no;
				$cont_no = $row->cont_no;
				$driver_name = $row->driver_name;
				$driver_gate_pass = $row->driver_gate_pass;
				$assistant_name = $row->assistant_name;
				$assistant_gate_pass = $row->assistant_gate_pass;
				$load_st = $row->load_st;
				$gate_in_status = $row->gate_in_status;
				$delv_pack = $row->actual_delv_pack;
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
							<label>Container No</label>									
						</div>
						<div class="col-md-1">
							 : 									
						</div>
						<div class="col-md-6">
							<label>&nbsp;<?php echo $cont_no; ?></label>									
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
						<form method="POST" action="<?php echo site_url("ShedBillController/truckLoadStsCng") ?>" onsubmit="return confirm('Are You Sure? ');">
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
									<div class="col-md-3 col-md-offset-2 text-right">
										<nobr>Load Qty</nobr>									
									</div>
									<div class="col-md-1">
										:
									</div>
									<div class="col-md-4">
										<input type="text" name="actual_qty" id="actual_qty" class="form-control" size="4" value="<?php echo $delv_pack; ?>" placeholder="0.0"/>							
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
												<option value="<?php echo $pack_id; ?>"><?php echo $pack_unit; ?></option>
											<?php
												}
											?>
										</select>					
									</div>
								</div>	
							</div>
							
							<?php
								if($cont_status == "Blocked")
								{
							?>
									<div class="col-sm-12 text-center">
										<font size="4" color="red"><b><?php echo $cont_no; ?> Container is Blocked!!</b></font>
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
											<font color="green" size="3"><b>Loaded!</b></font>
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
			<?php
				}
			?>
		</div>
	</div>
</section>
</div>