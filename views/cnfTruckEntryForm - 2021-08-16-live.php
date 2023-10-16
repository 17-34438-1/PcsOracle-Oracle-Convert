<script>	
	function getJettySrkrInfo()
	{
		var jsGatePass = document.getElementById('jsGatePass').value;
		
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
							
				if(jsonData.rslt_jsInfo.length>0)
				{
					var jsId = jsonData.rslt_jsInfo[0].id;
				
					var jsLicNo = jsonData.rslt_jsInfo[0].js_lic_no;
					var jsCellNo = jsonData.rslt_jsInfo[0].cell_no;
					var jsName = jsonData.rslt_jsInfo[0].js_name;
					// var jsAdress = jsonData.rslt_jsInfo[0].adress;								
					
					document.getElementById('jsId').value = jsId;
					
					document.getElementById('jsName').value = jsName;
					document.getElementById('jsLicenseNo').value = jsLicNo;
					
					jsCellNo = jsCellNo.replace("-", "");
					// alert(mobile_number);
					var mobile_length = jsCellNo.length;
					var res = jsCellNo.substr(0, 1);
					// alert(mobile_length);


					if(mobile_length == 10)
					{
						jsCellNo = "0".concat(jsCellNo);
					// alert(mobile_number);
					}
					
					document.getElementById('jsContact').value = jsCellNo;
					// document.getElementById('jsAddress').value = jsAdress;
				}
				else
				{
					alert("Jetty Sarkar Gate Pass is not valid");
				}
			}
		};
		
		var url = "<?php echo site_url('AjaxController/getJettySrkrInfo')?>?jsGatePass="+jsGatePass;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}	
	
	function getDriverInfo()
	{		
		var driverPassNo = document.getElementById('driverPassNo').value;
		
		if(driverPassNo != ""){
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
						document.getElementById('divDrAlt').style.display="none";
						driverAssistName = jsonData.rslt_driverInfo[0].driver_assist_name;
						mobile_number = jsonData.rslt_driverInfo[0].mobile_number;
						
						mobile_number = mobile_number.replace("-", "");
						// alert(mobile_number);
						var mobile_length = mobile_number.length;
						var res = mobile_number.substr(0, 1);
						// alert(mobile_length);


						if(mobile_length == 10)
						{
							mobile_number = "0".concat(mobile_number);
							// alert(mobile_number);
						}
						
						licNo = jsonData.rslt_driverInfo[0].lic_no;
						// truckNumber = jsonData.rslt_driverInfo[0].truck_number;
					}
					else
					{
						//alert("Please Assign a driver!");
						alert("Driver gate pass is invalid");
						document.getElementById('divDrAlt').style.display="block";
						document.getElementById('driverPassNo').value = "";
					}
								
					document.getElementById('driverLbl').innerHTML = driverAssistName;
					document.getElementById('driverName').value = driverAssistName;
					document.getElementById('driverMobileNumberLbl').innerHTML = mobile_number;				
					document.getElementById('truck').value = truckNumber;
					// document.getElementById('driverLicNo').value = licNo;
					
					
				}
			};
			
			var url = "<?php echo site_url('AjaxController/getDriverInfo')?>?driverPassNo="+driverPassNo;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}else{
			document.getElementById('divDrAlt').style.display="none";
		}
	}
	
	function getAssistantInfo()
	{
		var assistantPassNo = document.getElementById('assistantPassNo').value;
		
		if(assistantPassNo != ""){
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
					var licNo = "";
					var truckNumber = "";
					
					if(jsonData.rslt_driverInfo.length>0)
					{
						document.getElementById('divHeAlt').style.display = "none";
						driverAssistName = jsonData.rslt_driverInfo[0].driver_assist_name;
						mobile_number = jsonData.rslt_driverInfo[0].mobile_number;
						
						mobile_number = mobile_number.replace("-", "");
						// alert(mobile_number);
						var mobile_length = mobile_number.length;
						var res = mobile_number.substr(0, 1);
						// alert(mobile_length);


						if(mobile_length == 10)
						{
							mobile_number = "0".concat(mobile_number);
							// alert(mobile_number);
						}
						
						licNo = jsonData.rslt_driverInfo[0].lic_no;
						// truckNumber = jsonData.rslt_driverInfo[0].truck_number;
					}	
					else
					{
						// alert("Please Assign a helper!");
						alert("Assistant gate pass is invalid");
						document.getElementById('divHeAlt').style.display = "block";
						document.getElementById('assistantPassNo').value = "";
					}				
				
					document.getElementById('assistantName').value = driverAssistName;
					document.getElementById('helperMobileNumberLbl').innerHTML = mobile_number;
					document.getElementById('helperLbl').innerHTML = driverAssistName;				
				}
			};
			
			var url = "<?php echo site_url('AjaxController/getAssistantInfo')?>?driverPassNo="+assistantPassNo;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}else{
			document.getElementById('divHeAlt').style.display="none";
		}
	}
	
	function chkConfirm()
	{
		if(confirm("Do you want to save?"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function confirmPay()
	{
		if(confirm("Do you want to pay?"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function chkValidTruckInfo()
	{
		var driverPassNo = document.getElementById('driverPassNo').value;
		var assistantPassNo = document.getElementById('assistantPassNo').value;
		var regCity = document.getElementById('regCity').value;
		var regClass = document.getElementById('regClass').value;
		var truckNo = document.getElementById('truckNo').value;
		var importerMobileNo = document.getElementById('importerMobileNo').value;
		var agencyName = document.getElementById('agencyName').value;
		var agencyPhone = document.getElementById('agencyPhone').value;
		var truckSlot = document.getElementById('truckSlot').value;
		//alert(truckSlot);
		var validDriver = "";

		if(truckSlot != ""){
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
					//alert(val);
					var jsonData = JSON.parse(val);
					//alert(jsonData.rslt_validDriver[0].rtnValue);
					//alert("Length"+jsonData.rslt_validDriver.length);
					//var validDriver = "";	
					validDriver = jsonData.rslt_validDriver[0].rtnValue;
					// if(validDriver == 0){
					// 	return true;
					// }else{
					// 	alert("This driver already assigned in this slot");
					// 	return false;
					// }		
				
					//document.getElementById('assistantName').value = driverAssistName;				
				}
			};
			
			var url = "<?php echo site_url('AjaxController/chkValidDriver')?>?driverPassNo="+driverPassNo+ "&slot="+truckSlot;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}

		//alert(validDriver);

		// if(driverPassNo=="" || assistantPassNo=="" || truckNo=="" || importerMobileNo=="" || agencyName=="" || agencyPhone=="" || regCity=="" || regClass=="" || (document.getElementById("truckSlot_1").checked == false && document.getElementById("truckSlot_2").checked == false && document.getElementById("truckSlot_3").checked == false))
			
		
		var addBtn = document.getElementById('addBtn').value;
		
		// if(driverPassNo=="" || truckNo=="" || importerMobileNo=="" || agencyName=="" || agencyPhone=="" || regCity=="" || regClass=="" || (document.getElementById("truckSlot_1").checked == false && document.getElementById("truckSlot_2").checked == false && document.getElementById("truckSlot_3").checked == false))

		if(document.getElementById("truckSlot_1").checked == false && document.getElementById("truckSlot_2").checked == false && document.getElementById("truckSlot_3").checked == false)
		{
			alert("Please select a slot");
			return false;
		}
		if(driverPassNo=="")
		{
			alert("Driver pass should not be blank");
			document.getElementById('driverPassNo').focus();
			return false;
		}
		else if(truckNo=="")
		{
			alert("Truck No. should not be blank");
			document.getElementById('truckNo').focus();
			return false;
		}
		else if(regCity=="")
		{
			alert("Vehicle City should not be blank");
			document.getElementById('regCity').focus();
			return false;
		}
		else if(regClass=="")
		{
			alert("Vehicle class should not be blank");
			document.getElementById('regClass').focus();
			return false;
		}
		else
		{			// should be removed to check driver and helper pass
			if(validDriver == 0){

				if(importerMobileNo.length == 11 && agencyPhone.length == 11)				// length check done
				{
					var numbers = /^[0-9]+$/;
					
					if(importerMobileNo.match(numbers) && agencyPhone.match(numbers))		// numeric check done
					{
						if(importerMobileNo.substring(0,1)==0 && agencyPhone.substring(0,1)==0)
						{
							return true;
						}
						else
						{
							alert("Phone number(s) has no leading 0");
							return false;
						}
					}			
					else
					{			
						alert("For phone number, use only digit");			
						return false;
					}
				}
				// else				// commented because sometime they don't provide importer and agency number
				// {
					// alert("Phone number(s) does not have 11 digit");
					// return false;				
				// }
			} else{
				if(addBtn=='Add' || addBtn=='Emergency')
				{
					alert("This driver already assigned in this slot");
					return false;
				}
				else
				{
					return true;
				}
			} 
			
		}    //  should be removed to check driver & helper pass
		// return false;		
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
				document.getElementById('regHelp').style.display = "none";
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
					document.getElementById('regHelp').style.display = "block";
					document.getElementById('regHelpSpan').innerHTML = "Maximum 7 digit Allowed!";
				}
			}else{
				//alert("Not Integer");
				truck = trucklen.substr(0,len-1);
				// alert(truck);
				document.getElementById("truckNo").value = truck;
				document.getElementById('regHelp').style.display = "block";
				document.getElementById('regHelpSpan').innerHTML = "Only Number Allowed!";
			}
		}else{
			//alert("BackSpace");
		}
	}
	
	function delete_truck()
	{
		if (confirm("Do you want to detete this entry?") == true)
		{
			return true ;
		}
		else
		{
			return false;
		}
	}

	function btncng(dropValue){
		if(dropValue == 'online'){
			document.getElementById("paymentBtn").innerHTML = "Pay";
			document.getElementById("paymentBtn").value = "pay";
		}else{
			document.getElementById("paymentBtn").innerHTML = "Save";
			document.getElementById("paymentBtn").value = "save";
		}
	}
	
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
	<div class="content">
		<div class="content_resize">
						
			<!-- search start -->				
			<!--div class="row">
				<div class="col-xs-12">
					<section class="panel form-wizard" id="w4">
						<header class="panel-heading">
							<div class="panel-actions">
								<a href="#" class="fa fa-caret-down"></a>
								<a href="#" class="fa fa-times"></a>
							</div>
			
							<h2 class="panel-title">Search Form</h2>
						</header>
						<section class="panel">
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/ShedBillController/bilSearchByVerifyNumber'; ?>" name="myform" onsubmit="return(validation());" 
								style="padding:12px 20px;">
									<div class="form-group">
										<label class="col-md-3 control-label">&nbsp;</label>
										<div class="col-md-6">		
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Verify No: <span class="required">*</span></span>
												<input type="text" name="verifyNo" id="verifyNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Verify No">
											</div>												
										</div>
																					
										<div class="row">
											<div class="col-sm-12 text-center">											
												<button type="submit" name="report" id="submit" value="Search" class="mb-xs mt-xs mr-xs btn btn-success login_button">Search</button>
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
					</section>
				</div>
			</div-->										
			<!-- search end -->
				
			
			<?php 
			// if($search==1)
			// {
			?>		
			
			<div class="row">
				<div class="col-md-12">
					<div class="toggle" data-plugin-toggle data-plugin-options='{ "isAccordion": true }'>
						<section class="toggle <?php if($editVal==0  && $payForm==0){echo "active";} ?>">
							<label>Container Info</label>
							<div class="toggle-content">
								<div class="panel-body">
									<div class="col-md-12"> 
									<?php
										if($cont_blocked_status == "Blocked"){
									?>
										<div class="row">
											<div class="col-sm-12 text-center">
												<font size="4" color="red"><b>Container is Blocked!!</b></font>
											</div>
										</div>
									<?php
										}
									?>
										<div class="row">
											<div class="col-md-2">
												<b>Vessel Name</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">
												<?php echo $rtnVerifyReport[0]['vessel_name']?>
											</div>
											<div class="col-md-2">
												<b>Rotation</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">
												<?php echo $rtnVerifyReport[0]['import_rotation']?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<b>B/L No</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">
												<?php echo $rtnVerifyReport[0]['bl_no']?>
											</div>
											<div class="col-md-2">
												<b>IGM Qty</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">
												<?php echo $rtnVerifyReport[0]['Pack_Number']?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<b>IGM Unit</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">
												<?php echo $rtnVerifyReport[0]['Pack_Description']?>
											</div>
											<div class="col-md-2">
												<b>Container</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">

												<?php 
													if($cont_status!="FCL")
														echo $rtnVerifyReport[0]['cont_number'];
													else if($cont_status=="FCL")
														echo $containerSet;
												?>

											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<b>Container Size</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">
												<?php echo $rtnVerifyReport[0]['cont_size']?>
											</div>
											<div class="col-md-2">
												<b>Container Height</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">
												<?php echo $rtnVerifyReport[0]['cont_height']?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<b>Position</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">
												<?php if(isset($rslt_posYardBlock[0]['currentPos']))echo $rslt_posYardBlock[0]['currentPos']?>
											</div>
											<div class="col-md-2">
												<b>Yard</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">
												<?php if(isset($rslt_posYardBlock[0]['Yard_No']))echo $rslt_posYardBlock[0]['Yard_No']?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<b>Block</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">
												<?php if(isset($rslt_posYardBlock[0]['Block_No']))echo $rslt_posYardBlock[0]['Block_No']?>
											</div>
											<div class="col-md-2">
												<b>Verify No</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-3">
												<?php echo $rtnVerifyReport[0]['verify_number']?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<b>Goods Description</b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-9">
												<?php echo substr($rtnVerifyReport[0]['Description_of_Goods'],0,100);?>
											</div>										
										</div>
										<!-- <div class="row">
											<div class="col-md-2">
												<b><font size="4">Number of Truck</font></b>
											</div>
											<div class="col-md-1">
												<b>:</b>
											</div>
											<div class="col-md-1" style="background-color: #e7ec37">
												<b><font size="5" color="black"><?php echo $totTruck;?></font></b>
											</div>										
										</div> -->
									</div>
								</div>
							</div>
						</section>
						
						<section class="toggle <?php if($editVal==0 && $payForm==0){echo "active";} ?>">
							<label>Jetty Sircar Info</label>
							<div class="toggle-content">
								<div class="panel-body">
									<!--form class="form-horizontal" id="truckDtlForm" name="truckDtlForm" method="post" action="<?php echo site_url('ShedBillController/deliver_2'); ?>" onsubmit="return chkConfirm()"-->
									<form class="form-horizontal" id="truckDtlForm" name="truckDtlForm" method="post" action="<?php echo site_url('ShedBillController/jettySarkarEntry'); ?>" onsubmit="return chkConfirm()">
										<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
										<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
										<input type="hidden" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">
										<input type="hidden" id="assignmentType" name="assignmentType" value="<?php echo $assignmentType; ?>">										
										<input type="hidden" id="totTruck" name="totTruck" value="<?php echo $totTruck; ?>">
										<input type="hidden" id="vrfyInfoFclId" name="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>">
										
										<input type="hidden" id="jsId" name="jsId" value="">
										<div class="form-group">
											<label class="col-md-3 control-label">&nbsp;</label>
											<div class="col-md-6">
												<!--div class="input-group mb-md">
													<span class="input-group-addon span_width">Gate Pass <span class="required">*</span></span>
													<input class="form-control" type="text" id="jsGatePass" name="jsGatePass" value="" required onblur="getJettySrkrInfo()" />
												</div-->
												
												<!-- -->
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Gate Pass <span class="required">*</span></span>
													<input class="form-control" list="jsPassList" name="jsGatePass" id="jsGatePass" value="" onblur="getJettySrkrInfo()" autocomplete="off">

													<datalist id="jsPassList">
														<?php
														for($i=0;$i<count($rslt_jsInfo);$i++)
														{
														?>
														<option value="<?php echo $rslt_jsInfo[$i]['card_number']; ?>">
														<?php
														}
														?>											
													</datalist>
												</div>
												<!-- / -->
												
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Name</span>													
													<!--select class="form-control" id="jsName" name="jsName" onchange="getJettySrkrInfo()" required>
														<option value="">-- Select --</option>
														<?php
														for($jt = 0;$jt<count($rslt_jsInfo);$jt++)
														{
														?>
														<option value="<?php echo $rslt_jsInfo[$jt]['id']; ?>" <?php if($jettyEdit==1 && $jetty_sirkar_id == $rslt_jsInfo[$jt]['id']){?>selected<?php } ?>><?php echo $rslt_jsInfo[$jt]['js_name']; ?></option>
														<?php
														}
														?>
													</select-->
													<input class="form-control" type="text" id="jsName" name="jsName" value="<?php if($jettyEdit==1 && isset($agent_name)) echo $agent_name?>" readonly />
												</div>
											
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">License No</span>
													<input class="form-control" type="text" id="jsLicenseNo" name="jsLicenseNo" value="<?php if($jettyEdit==1 && isset($js_lic_no)) echo $js_lic_no?>" readonly />
												</div>			
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Contact No</span>
													<input class="form-control" type="text" id="jsContact" name="jsContact" value="<?php if($jettyEdit==1 && isset($cell_no)) echo $cell_no?>" readonly />
												</div>														
											</div>
											
											<?php
												
												$totalTruck = count($rslt_tmpTrkData);
												$totalTruckOut = 0;
												if(count($rslt_tmpTrkData)>0)
												{
													for($i=0;$i<count($rslt_tmpTrkData);$i++)
													{
														if($rslt_tmpTrkData[$i]['gate_out_status'] == 1)
														{
															$totalTruckOut++;
														}
													}
												}
					
											?>
											
											<?php
												if($cont_blocked_status == "Blocked"){
											?>
												<div class="row">
													<div class="col-sm-12 text-center">
														<font size="4" color="red"><b><?php echo $contNo; ?> Container is Blocked!!</b></font>
													</div>
												</div>
											<?php
												}else{
											?>
												<div class="row">
													<div class="col-sm-12 text-center">
														<button type="submit" value="Save" name="deliver" class="btn btn-primary" <?php if($totalTruck!=0 && $totalTruck==$totalTruckOut){echo "disabled";} ?> > Save </button>	
													</div>
												</div>
											<?php
												}
											?>
											
										</div>	
									</form>
										<?php
											if($jetty_sirkar_id!=null){
										?>
											<div class="panel-body">
												<table class="table table-bordered table-hover table-striped">
													<tr>
														<th class="text-center">Name</th>
														<th class="text-center">Gate Pass</th>
														<th class="text-center">License No</th>
														<th class="text-center">Contact No</th>
														<!-- <th class="text-center">Action</th> -->
													</tr>
													<?php
														include("mydbPConnection.php");
														$jettySarkarInfoQuery = "SELECT DISTINCT agent_name, agent_code , mobile_number  FROM vcms_vehicle_agent
														WHERE id = '$jetty_sirkar_id'";
														$rsltJettySarkar = mysqli_query($con_cchaportdb,$jettySarkarInfoQuery);
														$name="";
														$license = "";
														$contact = "";
														while($JettyResult=mysqli_fetch_object($rsltJettySarkar)){
															$name = $JettyResult->agent_name;
															$license = $JettyResult->agent_code;
															$contact = $JettyResult->mobile_number;
														}
													?>
													<tr>
														<td class="text-center"><?php echo $name; ?></td>
														<td class="text-center"><?php echo $card_number; ?></td>
														<td class="text-center"><?php echo $license; ?></td>
														<td class="text-center"><?php echo $contact; ?></td>
														<!-- <td class="text-center">
															<form class="form-horizontal" id="truckDtlEditForm" name="truckDtlEditForm" method="post" action="<?php echo site_url('ShedBillController/jettySarkarEntry'); ?>" >
																<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
																<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
																<input type="hidden" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">
																<input type="hidden" id="assignmentType" name="assignmentType" value="<?php echo $assignmentType; ?>">										
																<input type="hidden" id="totTruck" name="totTruck" value="<?php echo $totTruck; ?>">
																<input type="hidden" id="vrfyInfoFclId" name="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>">
																<input type="hidden" id="jsName" name="jsName" value="<?php echo ""; ?>">
																
																<input type="submit" name="jettyedit" id="jettyedit" value="Edit" class="btn btn-xs btn-primary"/>
															</form>
														</td> -->
													<tr>

												</table>
											</div>
										<?php
											}
										?>
								</div>
							</div>
						</section>
						
						<section class="toggle <?php if($editVal == 1 || $addVal == 1 || $payVal == 1){echo "active";} ?>">
							<label>Add Truck | Assignment Type : <?php echo $assignmentType; ?></label>
							<div class="toggle-content">
								<div class="panel-body">

									<form method="post" action="<?php echo site_url('ShedBillController/addTruckToDoDtl'); ?>" onsubmit="return chkValidTruckInfo()">
										<input type="hidden" id="vrfyInfoFclId" name="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>" />
										<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
										<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
										<input type="hidden" id="truckSlot" name="truckSlot" value="<?php echo $truckSlot?>">
										<input type="hidden" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">						
										<input type="hidden" id="assignmentType" name="assignmentType" value="<?php echo $assignmentType; ?>">						
										
										<!-- total truck allowed for container -->
										<input type="hidden" style="width:140px;" id="totTruck" name="totTruck" value="<?php echo $totTruck; ?>">
										
										<?php
										if($editVal==1)
										{
										?>
										<input type="hidden" style="width:140px;" id="editFormId" name="editFormId" value="<?php echo $rslt_trkEditInfo[0]['id']; ?>">
										<input type="hidden" style="width:140px;" id="editType" name="editType" value="<?php echo $editType; ?>">
										<?php
										}
										?>
										<div class="panel-body">
											<div class="row">												
												<div class="col-md-12" align="center">
													<?php echo $msg; ?><br>
												</div>												
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Driver Pass <span class="required">*</span></b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<div class="col-sm-7">
															<input class="form-control" list="driverPassList" name="driverPassNo" id="driverPassNo" value="<?php if($editVal==1){ echo $rslt_trkEditInfo[0]['driver_gate_pass'];  } ?>" onblur="getDriverInfo()" autocomplete="off">

															<datalist id="driverPassList">
																<?php
																for($i=0;$i<count($rslt_driverInfo);$i++)
																{
																?>
																<option value="<?php echo $rslt_driverInfo[$i]['card_number']; ?>">
																<?php
																}
																?>											
															</datalist>
															<label class="control-label" id="divDrAlt" style="display:none;"><b><font color="red">This is not a driver pass. Please enter driver pass</font></b></label>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Helper Pass</b></label>
														</div>	
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>														
														<div class="col-sm-7">
															<input class="form-control" list="assistantPassList" name="assistantPassNo" id="assistantPassNo" value="<?php if($editVal==1){ echo $rslt_trkEditInfo[0]['assistant_gate_pass'];  } ?>" onblur="getAssistantInfo()" autocomplete="off" >

															<datalist id="assistantPassList">
																<?php
																for($i=0;$i<count($rslt_helperInfo);$i++)
																{
																?>
																<option value="<?php echo $rslt_helperInfo[$i]['card_number']; ?>">
																<?php
																}
																?>											
															</datalist>
															<label class="control-label" id="divHeAlt" style="display:none;"><b><font color="red">This is not a helper pass. Please enter helper pass</font></b></label>
														</div>
													</div>
												</div>
											</div>											
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Driver Name</b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<div class="col-sm-7">
															<label class="control-label" id="driverLbl" style="color:black;"><?php if($editVal==1){ echo $rslt_trkEditInfo[0]['driver_name'];  } ?></label>
															<input type="hidden" name="driverName" id="driverName" value="<?php if($editVal==1){ echo $rslt_trkEditInfo[0]['driver_name'];  } ?>" class="form-control" >
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Helper Name</b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<div class="col-sm-7">
															<label class="control-label" id="helperLbl" style="color:black;"><?php if($editVal==1){ echo $rslt_trkEditInfo[0]['assistant_name'];  } ?></label>
															<input type="hidden" name="assistantName" id="assistantName" value="<?php if($editVal==1){ echo $rslt_trkEditInfo[0]['assistant_name'];  } ?>" class="form-control">
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Driver Phone</b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<div class="col-sm-7">
															<label class="control-label" id="driverMobileNumberLbl" style="color:black;"><?php if($editVal==1){ echo $rslt_trkEditInfo[0]['driver_mobile_number'];  } ?></label>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Helper Phone</b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<div class="col-sm-7">
															<label class="control-label" id="helperMobileNumberLbl" style="color:black;"><?php if($editVal==1){ echo $rslt_trkEditInfo[0]['helper_mobile_number'];  } ?></label>
														</div>
													</div>
												</div>
											</div>
											
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<div class="col-sm-4">
				<label class="control-label" style="color:black;"><b>Truck Reg No <span class="required">*</span></b></label>	
			</div>
			<div class="col-sm-1">
				<label class="control-label" style="color:black;"><b>:</b></label>
			</div>
			
			<?php
				if($editVal==1)
				{ 
					$truckId = $rslt_trkEditInfo[0]['truck_id'];
					$val = explode(" ",$truckId);
					$regCity = $val[0];
					$regClass = $val[1]." ".$val[2]." ".$val[3];
					$truckNo = $val[4];
				}
			?>
			
		<div class="col-sm-7">
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
						<option value="<?php echo $rowDist->value ?>" <?php if($editVal==1){if($regCity == $rowDist->value){ echo "selected";}} ?>><?php echo $rowDist->value ?></option>
						<?php } ?>
						<!--<option value="Dhaka-Metro" <?php if($editVal==1){if($regCity == "Dhaka-Metro"){ echo "selected";}} ?>>Dhaka-Metro</option>
						<option value="Rajshahi-Metro" <?php if($editVal==1){if($regCity == "Rajshahi-Metro"){ echo "selected";}} ?>>Rajshahi-Metro</option>
						<option value="Khulna-Metro" <?php if($editVal==1){if($regCity == "Khulna-Metro"){ echo "selected";}} ?>>Khulna-Metro</option>
						<option value="Rangpur-Metro" <?php if($editVal==1){if($regCity == "Rangpur-Metro"){ echo "selected";}} ?>>Rangpur-Metro</option>
						<option value="Sylhet-Metro" <?php if($editVal==1){if($regCity == "Sylhet-Metro"){ echo "selected";}} ?>>Sylhet-Metro</option>

						<option value="Jhalokhati" <?php if($editVal==1){if($regCity == "Jhalokhati"){ echo "selected";}} ?>>Jhalokhati</option>
						<option value="Barguna" <?php if($editVal==1){if($regCity == "Barguna"){ echo "selected";}} ?>>Barguna</option>
						<option value="Barisal" <?php if($editVal==1){if($regCity == "Barisal"){ echo "selected";}} ?>>Barisal</option>
						<option value="Bhola" <?php if($editVal==1){if($regCity == "Bhola"){ echo "selected";}} ?>>Bhola</option>
						<option value="Patuakhali" <?php if($editVal==1){if($regCity == "Patuakhali"){ echo "selected";}} ?>>Patuakhali</option>
						<option value="Pirojpur" <?php if($editVal==1){if($regCity == "Pirojpur"){ echo "selected";}} ?>>Pirojpur</option>

						<option value="CTG" <?php if($editVal==1){if($regCity == "CTG"){ echo "selected";}} ?>>Chittagong</option>
						<option value="Bandarban" <?php if($editVal==1){if($regCity == "Bandarban"){ echo "selected";}} ?>>Bandarban</option>
						<option value="Brahmanbaria" <?php if($editVal==1){if($regCity == "Brahmanbaria"){ echo "selected";}} ?>>Brahmanbaria</option>
						<option value="Chandpur" <?php if($editVal==1){if($regCity == "Chandpur"){ echo "selected";}} ?>>Chandpur</option>
						<option value="Cumilla" <?php if($editVal==1){if($regCity == "Cumilla"){ echo "selected";}} ?>>Cumilla</option>
						<option value="CoxsBazar" <?php if($editVal==1){if($regCity == "CoxsBazar"){ echo "selected";}} ?>>CoxsBazar</option>
						<option value="Feni" <?php if($editVal==1){if($regCity == "Feni"){ echo "selected";}} ?>>Feni</option>
						<option value="Khagrachhari" <?php if($editVal==1){if($regCity == "Khagrachhari"){ echo "selected";}} ?>>Khagrachhari</option>
						<option value="Lakshmipur" <?php if($editVal==1){if($regCity == "Lakshmipur"){ echo "selected";}} ?>>Lakshmipur</option>
						<option value="NK" <?php if($editVal==1){if($regCity == "NK"){ echo "selected";}} ?>>Noakhali</option>
						<option value="Rangamati" <?php if($editVal==1){if($regCity == "Rangamati"){ echo "selected";}} ?>>Rangamati</option>
						
						<!-- <option value="Dhaka" <?php if($editVal==1){if($regCity == "Dhaka"){ echo "selected";}} ?>>Dhaka</option> -->
						<!--<option value="Faridpur" <?php if($editVal==1){if($regCity == "Faridpur"){ echo "selected";}} ?>>Faridpur</option>
						<option value="Gazipur" <?php if($editVal==1){if($regCity == "Gazipur"){ echo "selected";}} ?>>Gazipur</option>
						<option value="Gopalganj" <?php if($editVal==1){if($regCity == "Gopalganj"){ echo "selected";}} ?>>Gopalganj</option>
						<option value="Kishoreganj" <?php if($editVal==1){if($regCity == "Kishoreganj"){ echo "selected";}} ?>>Kishoreganj</option>
						<option value="Madaripur" <?php if($editVal==1){if($regCity == "Madaripur"){ echo "selected";}} ?>>Madaripur</option>
						<option value="Manikganj" <?php if($editVal==1){if($regCity == "Manikganj"){ echo "selected";}} ?>>Manikganj</option>
						<option value="Munshiganj" <?php if($editVal==1){if($regCity == "Munshiganj"){ echo "selected";}} ?>>Munshiganj</option>
						<option value="NG" <?php if($editVal==1){if($regCity == "NG"){ echo "selected";}} ?>>Narayangonj</option>
						<option value="Narsingdi" <?php if($editVal==1){if($regCity == "Narsingdi"){ echo "selected";}} ?>>Narsingdi</option>
						<option value="Rajbari " <?php if($editVal==1){if($regCity == "Rajbari "){ echo "selected";}} ?>>Rajbari </option>
						<option value="Shariatpur" <?php if($editVal==1){if($regCity == "Shariatpur"){ echo "selected";}} ?>>Shariatpur</option>
						<option value="Tangail" <?php if($editVal==1){if($regCity == "Tangail"){ echo "selected";}} ?>>Tangail</option>

						<option value="Bagerhat" <?php if($editVal==1){if($regCity == "Bagerhat"){ echo "selected";}} ?>>Bagerhat</option> 
						<option value="Chuadanga" <?php if($editVal==1){if($regCity == "Chuadanga"){ echo "selected";}} ?>>Chuadanga</option>
						<option value="Jessore" <?php if($editVal==1){if($regCity == "Jessore"){ echo "selected";}} ?>>Jessore</option>
						<option value="Jhenaidah" <?php if($editVal==1){if($regCity == "Jhenaidah"){ echo "selected";}} ?>>Jhenaidah</option>
						<!-- <option value="Khulna" <?php if($editVal==1){if($regCity == "Khulna"){ echo "selected";}} ?>>Khulna</option> -->
						<!--<option value="Kushtia" <?php if($editVal==1){if($regCity == "Kushtia"){ echo "selected";}} ?>>Kushtia</option>
						<option value="Magura" <?php if($editVal==1){if($regCity == "Magura"){ echo "selected";}} ?>>Magura</option>
						<option value="Meherpur" <?php if($editVal==1){if($regCity == "Meherpur"){ echo "selected";}} ?>>Meherpur</option>
						<option value="Narail" <?php if($editVal==1){if($regCity == "Narail"){ echo "selected";}} ?>>Narail</option>
						<option value="Satkhira" <?php if($editVal==1){if($regCity == "Satkhira"){ echo "selected";}} ?>>Satkhira</option>

						<option value="Jamalpur" <?php if($editVal==1){if($regCity == "Jamalpur"){ echo "selected";}} ?>>Jamalpur</option>
						<option value="Mymensingh" <?php if($editVal==1){if($regCity == "Mymensingh"){ echo "selected";}} ?>>Mymensingh</option>
						<option value="Netrokona" <?php if($editVal==1){if($regCity == "Netrokona"){ echo "selected";}} ?>>Netrokona</option>
						<option value="Sherpur" <?php if($editVal==1){if($regCity == "Sherpur"){ echo "selected";}} ?>>Sherpur</option>

						<option value="Bogura" <?php if($editVal==1){if($regCity == "Bogura"){ echo "selected";}} ?>>Bogura</option>
						<option value="Joypurhat" <?php if($editVal==1){if($regCity == "Joypurhat"){ echo "selected";}} ?>>Joypurhat</option>
						<option value="Naogaon" <?php if($editVal==1){if($regCity == "Naogaon"){ echo "selected";}} ?>>Naogaon</option>
						<option value="Natore" <?php if($editVal==1){if($regCity == "Natore"){ echo "selected";}} ?>>Natore</option>
						<option value="Chapainawabganj" <?php if($editVal==1){if($regCity == "Chapainawabganj"){ echo "selected";}} ?>>Chapainawabganj</option>
						<option value="Pabna" <?php if($editVal==1){if($regCity == "Pabna"){ echo "selected";}} ?>>Pabna</option>
						<!-- <option value="Rajshahi" <?php if($editVal==1){if($regCity == "Rajshahi"){ echo "selected";}} ?>>Rajshahi</option> -->
						<!--<option value="Sirajganj" <?php if($editVal==1){if($regCity == "Sirajganj"){ echo "selected";}} ?>>Sirajganj</option>

						<option value="DP" <?php if($editVal==1){if($regCity == "DP"){ echo "selected";}} ?>>Dinajpur</option>
						<option value="Gaibandha" <?php if($editVal==1){if($regCity == "Gaibandha"){ echo "selected";}} ?>>Gaibandha</option>
						<option value="Kurigram" <?php if($editVal==1){if($regCity == "Kurigram"){ echo "selected";}} ?>>Kurigram</option>
						<option value="Lalmonirhat" <?php if($editVal==1){if($regCity == "Lalmonirhat"){ echo "selected";}} ?>>Lalmonirhat</option>
						<option value="Nilphamari" <?php if($editVal==1){if($regCity == "Nilphamari"){ echo "selected";}} ?>>Nilphamari</option>
						<option value="Panchagarh" <?php if($editVal==1){if($regCity == "Panchagarh"){ echo "selected";}} ?>>Panchagarh</option>
						<!-- <option value="Rangpur" <?php if($editVal==1){if($regCity == "Rangpur"){ echo "selected";}} ?>>Rangpur</option> -->
						<!--<option value="Thakurgaon" <?php if($editVal==1){if($regCity == "Thakurgaon"){ echo "selected";}} ?>>Thakurgaon</option>
						
						<option value="Habiganj" <?php if($editVal==1){if($regCity == "Habiganj"){ echo "selected";}} ?>>Habiganj</option>
						<option value="Moulvibazar" <?php if($editVal==1){if($regCity == "Moulvibazar"){ echo "selected";}} ?>>Moulvibazar</option>
						<option value="Sunamganj" <?php if($editVal==1){if($regCity == "Sunamganj"){ echo "selected";}} ?>>Sunamganj</option>-->
						<!-- <option value="Sylhet" <?php if($editVal==1){if($regCity == "Sylhet"){ echo "selected";}} ?>>Sylhet</option> -->

					</select>
				</div>
					<div class="col-sm-3" style="padding-right:4px;padding-left:2px;">
						<select class="form-control" id="regClass" name="regClass" style="padding:0px;">
							<option value="">----</option>
							<?php
							$strTruckChar = "SELECT * FROM truck_reg";
							$resTruckChar = mysqli_query($con_cchaportdb,$strTruckChar);
							while($rowTruckChar = mysqli_fetch_object($resTruckChar))
							{
							?>
							<option value="<?php echo $rowTruckChar->value ?>" <?php if($editVal==1){if($regClass == $rowTruckChar->value){ echo "selected";}} ?> ><?php echo $rowTruckChar->value ?></option>
							<?php } ?>
							<!--<option value="KA" <?php if($editVal==1){if($regClass == "KA"){ echo "selected";}} ?> >KA</option>
							<option value="KHA" <?php if($editVal==1){if($regClass == "KHA"){ echo "selected";}} ?> >KHA</option>
							<option value="GA" <?php if($editVal==1){if($regClass == "GA"){ echo "selected";}} ?> >GA</option>
							<option value="GHA" <?php if($editVal==1){if($regClass == "GHA"){ echo "selected";}} ?> >GHA</option>
							<option value="HA" <?php if($editVal==1){if($regClass == "HA"){ echo "selected";}} ?> >HA</option>
							<option value="TA" <?php if($editVal==1){if($regClass == "TA"){ echo "selected";}} ?> >TA</option>
							<option value="DA" <?php if($editVal==1){if($regClass == "DA"){ echo "selected";}} ?> >DA</option>
							<option value="MA" <?php if($editVal==1){if($regClass == "MA"){ echo "selected";}} ?> >MA</option>
							<option value="NA" <?php if($editVal==1){if($regClass == "NA"){ echo "selected";}} ?> >NA</option>
							<option value="U" <?php if($editVal==1){if($regClass == "U"){ echo "selected";}} ?> >U</option>
							<option value="SHA" <?php if($editVal==1){if($regClass == "SHA"){ echo "selected";}} ?> >SHA</option>-->
						</select>
					</div>
					<div class="col-sm-4" style="padding-left:0px;">
						<input style="padding:2px;" class="form-control" type="text" id="truckNo" onkeyup="getFormat(this.value);" name="truckNo" value="<?php if($editVal==1){ echo $truckNo;  } ?>">
					</div>
				</div>
				<label class="control-label" id="regHelp" style="display:none;"><b><font color="red" id="regHelpSpan"></font></b></label>
			</div>
		</div>
	</div>
												
												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4" style="padding-right:3px;">
															<label class="control-label" style="color:black;"><b>Importer's Mobile</b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>	
														<div class="col-sm-7">
															<input class="form-control" type="text" id="importerMobileNo" name="importerMobileNo" value="<?php echo $importerMobile; ?>">
														</div>
													</div>
												</div>
											</div>
											<div class="row" style="height:10px;">
												&nbsp;
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Truck Agency</b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<div class="col-sm-7">
															<input class="form-control" type="text" class="form-control" id="agencyName" name="agencyName" value="<?php if($editVal==1){ echo $rslt_trkEditInfo[0]['truck_agency_name'];  } ?>">
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Agency Phone </b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<div class="col-sm-7">
															<input class="form-control" type="text" class="form-control" id="agencyPhone" name="agencyPhone" value="<?php if($editVal==1){ echo $rslt_trkEditInfo[0]['truck_agency_phone'];  } ?>">
														</div>
													</div>
												</div>
											</div>	
											<div class="row" style="height:10px;">
												&nbsp;
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<div class="col-sm-2">
															<label class="control-label" style="color:black;"><b>Time Slot</b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<div class="col-sm-7">
															<div class="radio">
																<label style="color:black;">
																	<input type="radio" name="truckSlot" id="truckSlot_1" value="1"
																	<?php if($truckSlot!="" and $truckSlot=="1"){?>checked<?php } ?> <?php if($truckSlot=="2" or $truckSlot=="3" or $SlotCnt1>=88){?>disabled<?php } ?> >
																	<b>Slot 1 (<?php echo $sltAssignDt; ?> 08:00:00 to <?php echo $sltAssignDt; ?> 15:59:59), Vacant : <?php echo 88-$SlotCnt1; ?> of <?php echo $rslt_slotQty[0]['slot_1_qty']; ?></b> 
																</label>
															</div>
															<div class="radio">
																<label style="color:black;">
																	<input type="radio" name="truckSlot" id="truckSlot_2" value="2"
																	<?php if($truckSlot!="" and $truckSlot=="2"){?>checked<?php } ?> <?php if($truckSlot=="1" or $truckSlot=="3" or $SlotCnt2>=125){?>disabled<?php } ?>>
																	<b>Slot 2 (<?php echo $sltAssignDt; ?> 16:00:00 to <?php echo $sltAssignDt; ?> 23:59:59), Vacant : <?php echo 125-$SlotCnt2; ?> of <?php echo $rslt_slotQty[0]['slot_2_qty']; ?></b> 
																</label>
															</div>
															<div class="radio">
																<label style="color:black;">
																	<input type="radio" name="truckSlot" id="truckSlot_3" value="3"
																	<?php if($truckSlot!="" and $truckSlot=="3"){?>checked<?php } ?> <?php if($truckSlot=="1" or $truckSlot=="2" or $SlotCnt3>=37){?>disabled<?php } ?>>
																	<b>Slot 3 (<?php echo date('Y-m-d', strtotime($sltAssignDt.' +1 day')) ?> 00:00:00 to <?php echo date('Y-m-d', strtotime($sltAssignDt.' +1 day')) ?> 07:59:59), Vacant : <?php echo 37-$SlotCnt3; ?> of <?php echo $rslt_slotQty[0]['slot_3_qty']; ?></b> 
																</label>
															</div>
														</div>
													</div>
												</div>
												<!--div class="col-md-6">
													&nbsp;
												</div-->																								
											</div>
											<div class="row" style="height:10px;">
												&nbsp;
											</div>											
											<div class="row">
												<div class="col-sm-12 text-center">
													<input style="width:70px;" type="submit" class="btn btn-primary btn-xs" id="addBtn" name="addBtn" 
														value="<?php
															if($editVal==1)
															{
																echo "Update";
															}
															else
															{
																if(count($rslt_tmpTrkData)==$totTruck)
																	echo "Emergency"; 
																else if(count($rslt_tmpTrkData)<$totTruck) 
																	echo "Add";
																else
																	echo "Add";
															}																		
															?>"
															
															<?php if(is_null($jetty_sirkar_id) == 1 || $cont_blocked_status == "Blocked" || ($editVal== 0 and count($rslt_tmpTrkData)==($totTruck+1))){ ?>disabled<?php } ?> >
												</div>
											</div>

											<?php
												if($cont_blocked_status == "Blocked"){
											?>
												<div class="row">
													<div class="col-sm-12 text-center">
														<font size="4" color="red"><b><?php echo $contNo; ?> Container is Blocked!!</b></font>
													</div>
												</div>
											<?php
												}else if(is_null($jetty_sirkar_id) == 1){
											?>
											<div class="row">
												<div class="col-sm-12 text-center">
													<font color="red"><b>Add Jetty Sarkar First</b></font>
												</div>
											</div>
											<?php
												}
											?>

										</div>
									</form>

									<!-- <hr/> -->
									<div class="table-responsive">
										<table id="truckInfoTbl" class="table table-bordered  table-hover table-striped mb-none" cellspacing="2" cellpadding="2" align="left">
												<input type="hidden" id="vrfyInfoFclId" name="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>" />
																								
												<tr class="gridDark">
													<td rowspan="2" align="center"><b>Sl</b></td>
													<td rowspan="2" align="center">:</td>
													<td colspan="4" align="center"><nobr><b>Truck</b></nobr></td>
													<td colspan="2" align="center"><nobr><b>Driver</b></nobr></td>				
													<td colspan="2" align="center"><nobr><b>Assistant</b></nobr></td>
													<td colspan="3" align="center"><b>Action</b></td>		
												</tr>
												<tr class="gridDark">
													<td align="center"><nobr><b>Visit ID</b></nobr></td>
													<td align="center"><nobr><b>Reg No</b></nobr></td>
													<td align="center"><nobr><b>Status</b></nobr></td>
													<td align="center"><nobr><b>Type</b></nobr></td>
												
													<td align="center"><nobr><b>Gate Pass</b></nobr></td>
													<td align="center"><nobr><b>Name</b></nobr></td>													
													
													<td align="center"><nobr><b>Gate Pass</b></nobr></td>
													<td align="center"><nobr><b>Name</b></nobr></td>
													
													<td align="center"><nobr><b>Edit</b></nobr></td>
													<td align="center"><nobr><b>Payment</b></nobr></td>
													<td align="center"><nobr><b>Application/Pass</b></nobr></td>
												</tr>												
											
												<?php
												$totalAmtToPay = 0;
												if(count($rslt_tmpTrkData)>0)
												{												
													for($i=0;$i<count($rslt_tmpTrkData);$i++)
													{
														$payMethod = $rslt_tmpTrkData[$i]['paid_method'];	
														
														if(($rslt_tmpTrkData[$i]['emrgncy_flag'] == 0 or $rslt_tmpTrkData[$i]['emrgncy_approve_stat'] == 1) and $rslt_tmpTrkData[$i]['paid_status'] == 0)
														{
															$totalAmtToPay = $totalAmtToPay + 57.5;
														}
												?>
												<tr>
													<td align="center"><?php echo $i+1; ?></td>
													<td align="center">:</td>
													<td align="center"><?php echo $rslt_tmpTrkData[$i]['id']; ?></td>
													<td align="center"><?php echo $rslt_tmpTrkData[$i]['truck_id']; ?><br><span><font size="1"></font></span></td>
													<td align="center">
														<?php
															$btnType = "";
															$btnSt="";
															if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 0)				// regular truck
															{
																if($rslt_tmpTrkData[$i]['paid_status'] == 0)
																{	
																	echo "<font color='red'>Not Paid</font>";
																	$btnType = "Edit";
																	
																	$payBtnStat = "enable";
																	$btnSt = "Delete";																	
																}
																else if($rslt_tmpTrkData[$i]['paid_status'] == 1)
																{ 
																	echo "Paid";
																	$btnType = "Replace";
																	
																	$payBtnStat = "disable";
																}
																else if($rslt_tmpTrkData[$i]['paid_status'] == 2)
																{
																	echo "Payment Not Collected";
																	$btnType = "Edit";
																	
																	$btnSt = "Delete";
																	$payBtnStat = "disable";
																}
															}
															else if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 1)			// emergency truck
															{
																if($rslt_tmpTrkData[$i]['emrgncy_approve_stat'] == 1)
																{	
																	if($rslt_tmpTrkData[$i]['paid_status'] == 0)
																	{	
																		echo "<font color='red'>Not Paid</font>";
																		$btnType = "Edit";
																		
																		$payBtnStat = "enable";
																		$btnSt = "Delete";
																	}
																	else if($rslt_tmpTrkData[$i]['paid_status'] == 1)
																	{ 
																		echo "Paid (Approved)";
																		$btnType = "Replace";
																		
																		$payBtnStat = "disable";
																	}
																	else if($rslt_tmpTrkData[$i]['paid_status'] == 2)
																	{
																		echo "Payment Not Collected";
																		$btnType = "Edit";
																		
																		$btnSt = "Delete";
																		$payBtnStat = "disable";
																	}
																}
																else
																{ 
																	echo "<font color='red'>Not Approved</font>";
																	$btnType = "Edit";
																	
																	$btnSt = "Delete";
																	$payBtnStat = "disable";
																} 
															}
														?>
														<br>
														(Fee 57.50)
													</td>
													<td align="center">
														<?php
															if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 0)
															{																
																echo "Regular";
																
															}
															else if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 1)
															{
																echo "Emergency";
															}
														?>
													</td>													
													<td align="center"><?php echo $rslt_tmpTrkData[$i]['driver_gate_pass']; ?></td>
													<td align="center"><?php echo $rslt_tmpTrkData[$i]['driver_name']; ?></td>
													
													
													<td align="center"><?php echo $rslt_tmpTrkData[$i]['assistant_gate_pass']; ?></td>
													<td align="center"><?php echo $rslt_tmpTrkData[$i]['assistant_name']; ?></td>
													<td align="center">
														<form method="post" action="<?php echo site_url('ShedBillController/cnfTruckEntryForm'); ?>">
															<input type="hidden" id="editId" name="editId" value="<?php echo $rslt_tmpTrkData[$i]['id']; ?>" />
															<input type="hidden" id="btnType" name="btnType" value="<?php echo $btnType;?>" />
															<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
															<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">															
															<input type="hidden" style="width:140px;" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">															
															<input type="hidden" id="assignmentType" name="assignmentType" value="<?php echo $assignmentType; ?>">
															<input style="width:70px;" type="submit" class="btn btn-primary btn-xs" id="editBtn" name="editBtn" value="<?php echo $btnType;?>" <?php if($rslt_tmpTrkData[$i]['gate_out_status'] == 1){ ?>disabled<?php } ?> />
														</form>
														<br/>
														<?php if($rslt_tmpTrkData[$i]['paid_status'] == 0 || $rslt_tmpTrkData[$i]['paid_status'] == 2)
														{ ?>
														<form method="post" action="<?php echo site_url('ShedBillController/cnfTruckEntryForm'); ?>" 
														onsubmit="return(delete_truck());">
															<input type="hidden" id="delId" name="delId" value="<?php echo $rslt_tmpTrkData[$i]['id']; ?>" />
															<input type="hidden" id="btnType" name="btnType" value="<?php echo $btnType;?>" />
															<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
															<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">															
															<input type="hidden" style="width:140px;" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">															
															<input type="hidden" id="assignmentType" name="assignmentType" value="<?php echo $assignmentType; ?>">
															<input style="width:70px;" type="submit" class="btn btn-primary btn-xs" id="delBtn" name="delBtn" value="<?php echo $btnSt;?>" <?php if($rslt_tmpTrkData[$i]['gate_out_status'] == 1){ ?>disabled<?php } ?> />
														</form>		
														<?php } ?>			
														
													</td>
													<td align="center">
														<p class="m-none">
															<form method="post" action="<?php echo site_url('ShedBillController/cnfTruckPayForm'); ?>">
																<input type="hidden" id="payType" name="payType" value="singlePay"/>
																<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
																<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
																<input type="hidden" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">		
																<input type="hidden" id="assignmentType" name="assignmentType" value="<?php echo $assignmentType; ?>">
																<input type="hidden" id="truckDtlId" name="truckDtlId" value="<?php echo $rslt_tmpTrkData[$i]['id']; ?>" />
																<input type="hidden" id="payAmt" name="payAmt" value="57.5" />
																<input type="hidden" id="payMethod" name="payMethod" value="cash" />
																
																
																<input style="width:70px;<?php if($payMethod=="online"){ ?>display:none;<?php } ?>" type="submit" class="btn btn-primary btn-xs" id="payBtn" name="payBtn" 
																value="<?php 
																if($rslt_tmpTrkData[$i]['paid_status'] == 0)
																{ 
																	echo "Manual"; 
																} 
																else if($rslt_tmpTrkData[$i]['paid_status'] == 1)
																{ 
																	echo "Paid"; 
																}
																else if($rslt_tmpTrkData[$i]['paid_status'] == 2)
																{ 
																	echo "Manual Payment Pending"; 
																} 
																?>" 
																<?php if($payBtnStat=="disable"){ ?>disabled<?php }?> />
															</form>
														</p>
														<!-- <p class="m-none">
															<form action="<?php echo site_url('ShedBillController/checkoutbyOnline'); ?>" method="POST">
																<input type="hidden" id="payAmt" name="payAmt" value="57.5" />
																<input type="hidden" name="trucVisitId" id="trucVisitId" value="<?php echo $rslt_tmpTrkData[$i]['id']; ?>"/>
																<input type="hidden" id="assignmentType" name="assignmentType" value="<?php echo $assignmentType; ?>">
																<input type="hidden" id="contact" name="contact" value="<?php echo $contact; ?>">
																<input style="width:70px;<?php if($payMethod=="cash"){ ?>display:none;<?php } ?>" type="submit" class="btn btn-success btn-xs" id="payOnlineBtn" name="payOnlineBtn" value="Online" <?php if($payBtnStat=="disable"){ ?>disabled<?php }?> />
															</form>
														</p> -->
													</td>
													<td align="center">
														<p class="m-none">															
															<form method="POST" action="<?php echo site_url("ShedBillController/truckEntranceApplicationPDF") ?>" target="_blank">
																<input type="hidden" name="rot_no" id="rot_no" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>"/>
																<input type="hidden" name="cont_no" id="cont_no" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"/>
																<input type="hidden" name="trucVisitId" id="trucVisitId" value="<?php echo $rslt_tmpTrkData[$i]['id']; ?>"/>
																<input type="submit" style="width:70px;" class="btn btn-xs btn-primary" name="printPassBtn" id="printPassBtn" value="Print" <?php if($rslt_tmpTrkData[$i]['paid_status'] == 0){ ?>disabled<?php } ?> />
															</form>															
														</p>		
													</td>												
												</tr>
												<?php
													}
													?>
												<tr>
													<td align="center" colspan="7">
														<form method="post" action="<?php echo site_url('ShedBillController/cnfTruckPayForm'); ?>">
															<input type="hidden" id="payType" name="payType" value="allPay"/>
															<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
															<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
															<input type="hidden" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">	
															<input type="hidden" id="assignmentType" name="assignmentType" value="<?php echo $assignmentType; ?>">
															<input type="hidden" name="totalAmtToPay" id="totalAmtToPay" value="<?php echo $totalAmtToPay; ?>" />
															<input type="hidden" name="vrfyInfoFclId" id="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>" />
															<input style="width:70px;" type="submit" class="btn btn-primary btn-xs" id="payAllBtn" name="payAllBtn" value="Pay All" <?php if($totalAmtToPay==0){ ?>disabled<?php } ?> />
														</form>														
													</td>
													<td align="center" colspan="6">
														<form method="POST" action="<?php echo site_url("ShedBillController/printAllGatePass") ?>" target="_blank">
															<button type="submit" name="submit_login" class="btn btn-primary btn-xs">Print All</button>
														</form>
													</td>
												</tr>
												<?php
												}
												?>
										</table>
									</div>
								</div>
							</div>
						</section>
						
						<section class="toggle <?php if($payForm==1){echo "active";} ?>" id="payment">
							<label>Payment Info</label>
							<div class="toggle-content">
								<div class="panel-body">
									<form class="form-horizontal" id="paymentDtlForm" name="paymentDtlForm" method="post" action="<?php echo site_url('ShedBillController/cnfTruckPay'); ?>" onsubmit="return confirmPay()">
										<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
										<input type="hidden" id="truckDtlId" name="truckDtlId" value="<?php if(isset($payFlag) && $payFlag == "singlePay"){echo $truckDtlId;} ?>" />
										<input type="hidden" id="payType" name="payType" value="<?php if(isset($payFlag)){echo $payFlag;}?>"> 
										<input type="hidden" id="contact" name="contact" value="<?php echo @$contact; ?>">
										<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
										<input type="hidden" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">	
										<input type="hidden" id="assignmentType" name="assignmentType" value="<?php echo $assignmentType; ?>">
										<input type="hidden" id="totTruck" name="totTruck" value="<?php echo $totTruck; ?>">
										<input type="hidden" id="vrfyInfoFclId" name="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>">
										<div class="form-group">
											<label class="col-md-4 control-label">&nbsp;</label>
											<div class="col-md-4">		
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Name </span>													
													<select class="form-control" id="payMethod" name="payMethod" onchange="btncng(this.value)">
														<option value="cash" <?php if(isset($payFlag)){if($Method == "cash"){echo "selected";}} ?>>Cash</option>
														<!-- <option value="online" <?php //if(isset($payFlag)){if($Method == "Online"){echo "selected";}} ?>>Online</option> -->
													</select>
												</div>
											
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Amount</span>
													<input class="form-control" type="text" id="payAmt" name="payAmt" value="<?php if(isset($payFlag)){echo $payAmt;} ?>" readonly/>
												</div>																	
											</div>
																																	
											<?php
												if($cont_blocked_status == "Blocked"){
											?>
												<div class="row">
													<div class="col-sm-12 text-center">
														<font size="4" color="red"><b><?php echo $contNo; ?> Container is Blocked!!</b></font>
													</div>
												</div>
											<?php
												}else{
											?>

												<div class="row">
													<div class="col-sm-12 text-center">
														<button type="submit" value="save" name="payment" id="paymentBtn" class="btn btn-primary">Save</button>	
													</div>
												</div>
											<?php
												}
											?>
										</div>	
									</form>
								</div>
							</div>
						</section>
						
						
					</div>
				</div>
			</div>
			
			<!-- transport & jetty sirkar - end -->
			
			
			<!-- common information end -->
												
			<?php 
			// }
			?>	
      
      </div>
      
      <div class="clr"></div>
    </div>

  </div>
  
</section>
