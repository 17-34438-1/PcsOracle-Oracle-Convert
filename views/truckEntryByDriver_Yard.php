<script>
	function getDriverInfo()
	{		
		var driverPassNo = document.getElementById('driverPassNo').value;
		
		if(driverPassNo != "")
		{
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
					var driverAssistName = "";
					var mobile_number = "";
					var licNo = "";
					var truckNumber = "";
					
					if(jsonData.rslt_driverInfo.length>0)
					{
						driverAssistName = jsonData.rslt_driverInfo[0].driver_assist_name;
						mobile_number = jsonData.rslt_driverInfo[0].mobile_number;
						
						mobile_number = mobile_number.replace("-", "");

						var mobile_length = mobile_number.length;
						var res = mobile_number.substr(0, 1);
						if(mobile_length == 10)
						{
							mobile_number = "0".concat(mobile_number);
						}
						
					}
					else
					{
						alert("Driver gate pass is invalid");
						document.getElementById('driverPassNo').value = "";
					}
								
					document.getElementById('driverName').value = driverAssistName; 
					// document.getElementById('driverNamelbl').innerHTML = driverAssistName;
					// document.getElementById('driverMobileNumberLbl').innerHTML = mobile_number;	
					document.getElementById('phoneNo').value = mobile_number;									
				}
			};
			
			var url = "<?php echo site_url('AjaxController/getDriverInfo')?>?driverPassNo="+driverPassNo;
			// alert(url);		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}
	}

	function getAssistantInfo()
	{
		var assistantPassNo = document.getElementById('assistantPassNo').value;
		
		if(assistantPassNo != "")
		{
			if (window.XMLHttpRequest) 
			{
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange=function()
			{		 			
				if (xmlhttp.readyState==4 && xmlhttp.status==200) 
				{							
					var val = xmlhttp.responseText;
					var jsonData = JSON.parse(val);
					
					var driverAssistName = "";
					var mobile_number = "";
					
					if(jsonData.rslt_driverInfo.length>0)
					{
						driverAssistName = jsonData.rslt_driverInfo[0].driver_assist_name;
						mobile_number = jsonData.rslt_driverInfo[0].mobile_number;
						
						mobile_number = mobile_number.replace("-", "");
						var mobile_length = mobile_number.length;
						var res = mobile_number.substr(0, 1);

						if(mobile_length == 10)
						{
							mobile_number = "0".concat(mobile_number);
						}
						document.getElementById('assistantName').value = driverAssistName; 
						// document.getElementById('assistantNamelbl').innerHTML = driverAssistName;
						// document.getElementById('helperMobileNumberLbl').innerHTML = mobile_number;	
						// document.getElementById('helperMobileNumber').value = mobile_number;	
					}	
					else
					{
						alert("Assistant gate pass is invalid");
						document.getElementById('assistantPassNo').value = "";
					}							
				}
			};
			
			var url = "<?php echo site_url('AjaxController/getAssistantInfo')?>?driverPassNo="+assistantPassNo;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}
	}

	function getFormat(trucklen)
	{
		var keyId = event.keyCode;
		var len = trucklen.length;
		var lastDigit = parseInt(trucklen.substr(len-1,len));
		
		//alert(typeof(lastDigit)); 
		
		if(keyId != 8){
			if(Number.isInteger(lastDigit)){
				//alert("Integer");
				// document.getElementById('regHelp').style.display = "none";
				if(len == 2){
					truck = trucklen+"-";
					// alert(truck);
					document.getElementById("truckNo").value = truck;
				}else if(len == 3){
					part1 = trucklen.substr(0,2);
					part2 = trucklen.substr(2,1);
					truck = part1+"-"+part2;
					//alert(truck);
					document.getElementById("truckNo").value = truck;
				}else if(len >= 8){
					truck = trucklen.substr(0,7);
					// alert(truck);
					document.getElementById("truckNo").value = truck;
					// document.getElementById('regHelp').style.display = "block";
					// document.getElementById('regHelpSpan').innerHTML = "Maximum 7 digit Allowed!";
					alert("Maximum 7 digit Allowed!");
				}
			}else{
				//alert("Not Integer");
				truck = trucklen.substr(0,len-1);
				// alert(truck);
				document.getElementById("truckNo").value = truck;
				// document.getElementById('regHelp').style.display = "block";
				// document.getElementById('regHelpSpan').innerHTML = "Only Number Allowed!";
				alert("Only Number Allowed!");
			}
		}else{
			//alert("BackSpace");
		}
	}

    
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
	<section class="panel panel-primary" id="panel-1" data-portlet-item="">
		<header class="panel-heading portlet-handler">
			<h2 class="panel-title">Information</h2>
		</header>
		<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/driverYardTruckVerify") ?>" onsubmit="return chkBlankField();">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-12 text-center">
								<?php echo $msg; ?>
							</div>
						</div>
						<div class="col-md-6 col-md-offset-3">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Container No<span class="required">*</span></span>
								<input type="text" name="contNo" id="contNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Container No" />
							</div>
						</div>	

						<div class="col-md-6 col-md-offset-3">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Driver Pass<span class="required">*</span></span>
								<input type="text" name="driverPassNo" id="driverPassNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Driver Pass" onblur="getDriverInfo()" />
							</div>
						</div>
						<div class="col-md-6 col-md-offset-3" style="display:block">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Driver Name</span>
								<input type="text" name="driverName" id="driverName" class="form-control login_input_text" autofocus= "autofocus" />
							</div>
						</div>

						<div class="col-md-6 col-md-offset-3">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Phone No</span>
								<input type="text" name="phoneNo" id="phoneNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Phone No" />
							</div>
						</div>

						<div class="col-md-6 col-md-offset-3">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Helper Pass</span>
								<input type="text" name="assistantPassNo" id="assistantPassNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Helper Pass" onblur="getAssistantInfo()" />
							</div>
						</div>
						<div class="col-md-6 col-md-offset-3" style="display:block">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Helper Name</span>
								<input type="text" name="assistantName" id="assistantName" class="form-control login_input_text" autofocus= "autofocus" />
							</div>
						</div>

						<!-- <div class="col-md-6 col-md-offset-3">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Truck No<span class="required">*</span></span>
								<input type="text" name="truckNo" id="truckNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Truck No" />
							</div>
						</div> -->

						<div class="col-md-6 col-md-offset-3">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Truck No<span class="required">*</span></span>
								<!-- <input type="text" name="truckNo" id="truckNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Truck No" /> -->
								<div>
									<div class="row">
										<div class="col-sm-5" style="padding-right:2px;">

											<select class="form-control" id="regCity" name="regCity" style="padding:0px;">
												<option value="">--Select--</option>
												<?php
												include("mydbPConnection.php");
												$strDistrict = "SELECT * FROM truck_district";
												$resDistrict = mysqli_query($con_cchaportdb,$strDistrict);
												while($rowDist = mysqli_fetch_object($resDistrict))
												{
												?>
												<option value="<?php echo $rowDist->value ?>" ><?php echo $rowDist->value ?></option>
												<?php } ?>

											</select>
										</div>
										<div class="col-sm-3" style="padding-right:4px;padding-left:2px;">
											<input class="form-control" list="regClassList" name="regClass" id="regClass" style="padding:0px;">
											<datalist id="regClassList">
											<option value="">----</option>
												<?php
												$strTruckChar = "SELECT * FROM truck_reg";
												$resTruckChar = mysqli_query($con_cchaportdb,$strTruckChar);
												while($rowTruckChar = mysqli_fetch_object($resTruckChar))
													{
												?>
												<option value="<?php echo $rowTruckChar->value ?>" ><?php echo $rowTruckChar->value ?></option>
												<?php 
													} 
												?>
											</datalist>
										</div>
										<div class="col-sm-4" style="padding-left:0px;">
											<input style="padding:2px;" class="form-control" type="text" id="truckNo" onkeyup="getFormat(this.value);" name="truckNo">
										</div>
									</div>
										<!-- <label class="control-label" id="regHelp" style="display:none;"><b><font color="red" id="regHelpSpan"></font></b></label> -->
								</div>
							</div>
						</div>

						<div class="col-md-6 col-md-offset-3">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Truck Slot: </span>
								<div>
									<div class="radio">
										<label style="color:black;">
											<input type="radio" name="truckSlot" id="truckSlot_1" value="1">
											<b>Slot 1 (<?php echo date('Y-m-d'); ?> 08:00:00 to 15:59:59)</b> 
										</label>
									</div>
									<div class="radio">
										<label style="color:black;">
											<input type="radio" name="truckSlot" id="truckSlot_2" value="2">
											<b>Slot 2 (<?php echo date('Y-m-d'); ?> 16:00:00 to 23:59:59)</b> 
										</label>
									</div>
									<div class="radio">
										<label style="color:black;">
											<input type="radio" name="truckSlot" id="truckSlot_3" value="3">
											<b>Slot 3 (<?php echo date('Y-m-d', strtotime(' +1 day')); ?> 00:00:00 to 07:59:59)</b> 
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="row" id="applyBtn">
							<div class="col-sm-12 text-center">
								<button type="submit" name="btnVerify" class="mb-xs mt-xs mr-xs btn btn-primary">
									Verify
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</section>
	<!-- end: page -->
</section>
</div>