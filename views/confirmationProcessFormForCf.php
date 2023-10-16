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
<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
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
									<?php echo $msg;?>
								</div>
							</div>
							<form class="form-horizontal form-bordered" method="POST" id="myform" name="myform" 
								action="<?php echo site_url("ShedBillController/getVehicleDataForCf");?>" onsubmit="return chkValidate();">
								
								<!--form class="form-horizontal form-bordered" method="POST" id="myform" name="myform" 
								action="<?php echo site_url("ShedBillController/getVehicleData");?>"-->
								<div class="form-group">
								<input type="hidden" name="chk_st" id="chk_st" value="<?php echo $chk_st;?>">
								<input type="hidden" name="chk_by" id="chk_by" value="<?php echo $chk_by;?>">
								<input type="hidden" name="chk_time" id="chk_time" value="<?php echo $chk_time;?>">
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
									<?php echo $msg;?>
								</div>
							</div>
							<form class="form-horizontal form-bordered" method="POST" id="myform" name="myform" 
								action="<?php echo site_url("ShedBillController/getVehicleDataForCf");?>" onsubmit="return chkData();">
								<div class="form-group">
								<input type="hidden" name="chk_st" id="chk_st" value="<?php echo $chk_st;?>">
								<input type="hidden" name="chk_by" id="chk_by" value="<?php echo $chk_by;?>">
								<input type="hidden" name="chk_time" id="chk_time" value="<?php echo $chk_time;?>">
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
		<!-- end: page -->
	</section>
	</div>