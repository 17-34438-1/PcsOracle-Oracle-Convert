<script>

	function setBlankField(ele)
	{
		ele.value="";
		document.getElementById('driverImg').innerHTML = "";
		document.getElementById('helperImg').innerHTML = "";
	}

	function getJettySrkr()
	{
		var data = document.getElementById("assignment").value.trim();
		var datas = data.split("_");
		var rot = datas[1].replace("/","_");
		var cont = datas[0];
		var size = datas[4];
		var custom_block = datas[6];

		if(data != ""){
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
					//alert(val);
					
					//set to default start //

					document.getElementById('searchByCnfId').innerHTML = "Add";
					document.getElementById('searchByCnfId').value = "Add";

					document.getElementById("truckSlot_1").checked = false;
					document.getElementById("truckSlot_2").checked = false;
					document.getElementById("truckSlot_3").checked = false;
					document.getElementById("truckSlot_1").disabled = false;
					document.getElementById("truckSlot_2").disabled = false;
					document.getElementById("truckSlot_3").disabled = false;

					document.getElementById('jsName').innerHTML = "";

					document.getElementById('jsName').innerHTML = "";
					document.getElementById('jsNameLvl').value = "";
					document.getElementById('jsLicenseNo').innerHTML = ""; 
					document.getElementById('jsLicenseNoLvl').value = "";
					document.getElementById('jsGatePass').value = "";
					document.getElementById('importerMobileNo').value = "";
					document.getElementById('importerMobileNo').readOnly = false;
					
					// set to default ends //

					if(custom_block == "Blocked.")
					{
						document.getElementById('searchByCnfId').innerHTML = custom_block;
						document.getElementById('searchByCnfId').disabled = true;
					}
					else
					{
						if(jsonData.truckAdded.length>0)
						{
							var truck = jsonData.truckAdded;
							//alert(truck+" Truck Added!");

							if((size == 40 && truck == 3) || (size == 20 && truck == 2))
							{
								// alert("emergency Truck!");
								document.getElementById('searchByCnfId').innerHTML = "Emergency";
								document.getElementById('searchByCnfId').value = "Emergency";
								document.getElementById('searchByCnfId').disabled = false;
							}
							else if((size == 40 && truck > 3) || (size == 20 && truck > 2))
							{
								alert("All truck added!");
								document.getElementById('searchByCnfId').disabled = true;
							}
							else if((size == 40 && truck < 3) || (size == 20 && truck < 2))
							{
								//alert("add Truck");
								document.getElementById('searchByCnfId').innerHTML = "Add";
								document.getElementById('searchByCnfId').value = "Add";
								document.getElementById('searchByCnfId').disabled = false;
							}
						}
					}

					if(jsonData.slot.length>0)
					{
						var slot = jsonData.slot;

						if(slot == 1)
						{
							document.getElementById("truckSlot_1").checked = true;
							document.getElementById("truckSlot_2").disabled = true;
							document.getElementById("truckSlot_3").disabled = true;
						}
						else if(slot == 2)
						{
							document.getElementById("truckSlot_2").checked = true;
							document.getElementById("truckSlot_1").disabled = true;
							document.getElementById("truckSlot_3").disabled = true;
						}
						else if(slot == 3)
						{
							document.getElementById("truckSlot_3").checked = true;
							document.getElementById("truckSlot_1").disabled = true;
							document.getElementById("truckSlot_2").disabled = true;
						}
						else{
							document.getElementById("truckSlot_1").disabled = false;
							document.getElementById("truckSlot_2").disabled = false;
							document.getElementById("truckSlot_3").disabled = false;
						}
					}else{
						document.getElementById("truckSlot_1").disabled = false;
						document.getElementById("truckSlot_2").disabled = false;
						document.getElementById("truckSlot_3").disabled = false;
					}

					var cardNo = "";
					
					if(jsonData.card_number.length>0)
					{
                        var cardNo = jsonData.card_number;
						var mobile = jsonData.mobile;
						var jsName = jsonData.agent_name;
						var jsLicNo = jsonData.agent_code;
						document.getElementById('jsGatePass').value = cardNo;
						document.getElementById('importerMobileNo').value = mobile;
						document.getElementById('importerMobileNo').readOnly = true;
						document.getElementById('jsName').innerHTML = jsName;
						document.getElementById('jsNameLvl').value = jsName;
						document.getElementById('jsLicenseNo').innerHTML = jsLicNo; 
						document.getElementById('jsLicenseNoLvl').value = jsLicNo;
					}
					else
					{
						document.getElementById('jsGatePass').value = "";
						document.getElementById('importerMobileNo').value = "";
						document.getElementById('importerMobileNo').readOnly = false;
						document.getElementById('jsName').innerHTML = "";
						document.getElementById('jsNameLvl').value = "";
						document.getElementById('jsLicenseNo').innerHTML = ""; 
						document.getElementById('jsLicenseNoLvl').value = "";
					}

				}else{
					document.getElementById('jsGatePass').value = "";
					document.getElementById('importerMobileNo').value = "";
					document.getElementById('importerMobileNo').readOnly = false;
				}
			};
			
			var url = "<?php echo site_url('AjaxController/getJettySrkr')?>?cont="+cont+"&rot="+rot;
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}
	}

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
		
		xmlhttp.onreadystatechange=function()
		{
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
					
					// document.getElementById('jsId').value = jsId;
					
					document.getElementById('jsName').innerHTML = jsName;
					document.getElementById('jsNameLvl').value = jsName;
					document.getElementById('jsLicenseNo').innerHTML = jsLicNo; 
					document.getElementById('jsLicenseNoLvl').value = jsLicNo;
					
					// jsCellNo = jsCellNo.replace("-", "");
					// alert(mobile_number);
					// var mobile_length = jsCellNo.length;
					// var res = jsCellNo.substr(0, 1);
					// alert(mobile_length);


					// if(mobile_length == 10)
					// {
					// 	jsCellNo = "0".concat(jsCellNo);
					// // alert(mobile_number);
					// }
					
					// document.getElementById('jsContact').value = jsCellNo;
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

	function getCardNumer()
	{
		var driverPassNo = document.getElementById('driverPassNo').value;
		//alert(driverPassNo);
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
					
			xmlhttp.onreadystatechange=function()
			{		 
				if (xmlhttp.readyState==4 && xmlhttp.status==200) 
				{							
					var val = xmlhttp.responseText;
					//alert(val);

					getDriverInfo(val);			
					
				}
			};
			
			var url = "<?php echo site_url('AjaxController/getCardNuberByScan')?>?passNo="+driverPassNo;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}
		else
		{
			document.getElementById('driverName').value = ""; 
			document.getElementById('driverNamelbl').innerHTML = "";
			document.getElementById('driverMobileNumberLbl').innerHTML = "";	
			document.getElementById('driverMobileNumber').value = "";
			document.getElementById('driverImg').innerHTML = "";
		}
	}

	function getCardNumerforhelper()
	{
		var assistantPassNo = document.getElementById('assistantPassNo').value;
		//alert(driverPassNo);
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
					//alert(val);

					getAssistantInfo(val);	
					
				}
			};
			
			var url = "<?php echo site_url('AjaxController/getCardNuberByScan')?>?passNo="+assistantPassNo;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}
		else
		{
			document.getElementById('driverName').value = ""; 
			document.getElementById('driverNamelbl').innerHTML = "";
			document.getElementById('driverMobileNumberLbl').innerHTML = "";	
			document.getElementById('driverMobileNumber').value = "";
			document.getElementById('driverImg').innerHTML = "";
		}
	}

	function getDriverInfo(driverPassNo) //driverPassNo
	{		
		// var driverPassNo = document.getElementById('driverPassNo').value;
		
		if(driverPassNo.length == 13)
		{
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
						var agent_photo = "";
						var img = "";
						
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

							var agent_photo = jsonData.rslt_driverInfo[0].agent_photo;
							var agent_split = agent_photo.split(".");
							var agent_folder = agent_split[0];
							var img = "<img src='/biometricPhoto/"+agent_folder+'/'+agent_photo+"'height='120px' width='120px' align='center'>";
							
							document.getElementById('driverName').value = driverAssistName; 
							document.getElementById('driverNamelbl').innerHTML = driverAssistName;
							document.getElementById('driverMobileNumberLbl').innerHTML = mobile_number;	
							document.getElementById('driverMobileNumber').value = mobile_number;
							document.getElementById('driverImg').innerHTML = img;
							document.getElementById('driverPassNo').value = driverPassNo;

						}
						else
						{
							alert("Driver gate pass is invalid");
							document.getElementById('driverPassNo').value = "";
						}				
						
					}
				};
				
				var url = "<?php echo site_url('AjaxController/getDriverInfo')?>?driverPassNo="+driverPassNo;		
				xmlhttp.open("GET",url,false);
				xmlhttp.send();
			}
			else
			{
				document.getElementById('driverName').value = ""; 
				document.getElementById('driverNamelbl').innerHTML = "";
				document.getElementById('driverMobileNumberLbl').innerHTML = "";	
				document.getElementById('driverMobileNumber').value = "";
				document.getElementById('driverImg').innerHTML = "";
				document.getElementById('driverPassNo').value = "";
			}
		}
		else
		{
			document.getElementById('driverName').value = ""; 
			document.getElementById('driverNamelbl').innerHTML = "";
			document.getElementById('driverMobileNumberLbl').innerHTML = "";	
			document.getElementById('driverMobileNumber').value = "";
			document.getElementById('driverImg').innerHTML = "";
			document.getElementById('driverPassNo').value = "";
		}
		
	}

	function getAssistantInfo(assistantPassNo) //assistantPassNo
	{
		//var assistantPassNo = document.getElementById('assistantPassNo').value;
		
		if(assistantPassNo.length == 13)
		{
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
						var agent_photo = "";
						var img = "";
						
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

							var agent_photo = jsonData.rslt_driverInfo[0].agent_photo;
							var agent_split = agent_photo.split(".");
							var agent_folder = agent_split[0];
							var img = "<img src='/biometricPhoto/"+agent_folder+'/'+agent_photo+"'height='120px' width='120px' align='center'>";

							document.getElementById('assistantName').value = driverAssistName; 
							document.getElementById('assistantNamelbl').innerHTML = driverAssistName;
							document.getElementById('helperMobileNumberLbl').innerHTML = mobile_number;	
							document.getElementById('helperMobileNumber').value = mobile_number;
							document.getElementById('helperImg').innerHTML = img;	
							document.getElementById('assistantPassNo').value = assistantPassNo;
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
			else
			{
				document.getElementById('assistantName').value = ""; 
				document.getElementById('assistantNamelbl').innerHTML = "";
				document.getElementById('helperMobileNumberLbl').innerHTML = "";	
				document.getElementById('helperMobileNumber').value = "";
				document.getElementById('helperImg').innerHTML = "";
				document.getElementById('assistantPassNo').value = "";
			}
		}
		else
		{
			document.getElementById('assistantName').value = ""; 
			document.getElementById('assistantNamelbl').innerHTML = "";
			document.getElementById('helperMobileNumberLbl').innerHTML = "";	
			document.getElementById('helperMobileNumber').value = "";
			document.getElementById('helperImg').innerHTML = "";
			document.getElementById('assistantPassNo').value = "";
		}
	}

	function getFormat(trucklen)
	{
		var keyId = event.keyCode;
		var len = trucklen.length;
		var lastDigit = parseInt(trucklen.substr(len-1,len));
		
		//alert(typeof(lastDigit)); 
		
		if(keyId != 8){
			if(keyId != 9){
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
			}
		}else{
			//alert("BackSpace");
		}
	}

	function validate(){
		var driverId = document.getElementById("driverPassNo").value;
		var regCity = document.getElementById("regCity").value;
		var regClass = document.getElementById("regClass").value;
		var truckNo = document.getElementById("truckNo").value;
		var editType = document.getElementById("editType").value;

		if(driverId == "")
		{
			alert("Please add driver!");
			return false;
		}
		else if(regCity == "" || regClass == ""  || truckNo == "")
		{
			alert("Please add truck no!");
			return false;
		}

		if(editType == "Replace")
		{
			var prev_truckId = document.getElementById("prev_truckId").value;
			var current_truckId = regCity+" "+regClass+" "+truckNo;
			if(prev_truckId == current_truckId){
				alert("Please add new truck!!");
				return false;
			}
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
			<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('ShedBillController/fclTruckSearchforSecurity')?>" >
					<div class="form-group">
						<label class="col-md-1 control-label">&nbsp;</label>
						<div class="col-md-10">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php echo $msg; ?>
								</div>
							</div>
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">AIN No :</span>
								<!-- <input class="form-control" name="cnfAinNo" id="cnfAinNo" value="<?php //if($flag == 1){echo $ain;}?>" />
								<span class="input-group-btn">
									<button type="submit" value="Search" name="searchByCnfId" id="searchByCnf" class="btn btn-primary" > Search </button>
								</span> -->
								<?php
									$cf_ain_query = "SELECT CONCAT(AIN_No_New,' - ',Organization_Name) AS AIN_No_New
									FROM organization_profiles
									WHERE Org_Type_id = '2'";
									$cfAin = $this->bm->dataSelectDB1($cf_ain_query);
								?>

								<input class="form-control" list="cfAinList" name="cnfAin" id="cnfAin" value="<?php if($flag == 1){echo $ain;}?>" autocomplete="off" onchange="this.form.submit()" <?php if($flag != 1){ echo "autofocus";} ?> >
								<datalist id="cfAinList" >
									<?php
									for($i=0;$i<count($cfAin);$i++)
									{
									?>
									<option value="<?php echo $cfAin[$i]['AIN_No_New']; ?>">
									<?php
									}
									?>
								</datalist>
								<!--<span class="input-group-btn">
									<button type="submit" value="Search" name="searchByCnfId" id="searchByCnf" class="btn btn-primary" > Search </button>
								</span>-->
							</div>
						</div>
					</div>
				</form>

				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('ShedBillController/fclTruckEntryforSecurity')?>" id="myform" name="myform" onsubmit="return validate()">
					<input type="hidden" id="jsId" name="jsId" value="">
					<?php
						if(isset($editType)){
							$truckVisitId = "";
							for($j=0;$j<count($rslt_trkEditInfo);$j++){
								$truckVisitId = $rslt_trkEditInfo[$j]['id'];
							}
					?>
						<input type="hidden" id="truckVisitId" name="truckVisitId" value="<?php echo $truckVisitId;?>">
					<?php		
						}
					?>
					<div class="form-group">
						<label class="col-md-1 control-label">&nbsp;</label>
						<div class="col-md-10">		
							
							<?php
								if($flag == 1){
							?>
								<div class="input-group mb-md">
									<input type="hidden" id="cnfAinNo" name="cnfAinNo" value="<?php echo $ain;?>">
									<span class="input-group-addon span_width">C&F Name :</span>
									<input class="form-control" name="cnfName" id="cnfName" value="<?php if($flag == 1){echo $cf_name;}?>" readonly/>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Cont & Assignment</span>
									<select class="form-control" name="assignment" id="assignment" onblur="getJettySrkr()" autocomplete="off" <?php if(isset($editType)){ echo "disabled";} ?> <?php if($flag == 1){echo "autofocus";}?>>
										<option value="">--select--</option>
										<?php
											include("mydbPConnection.php");
											for($i=0;count($data_assignment)>$i;$i++)
											{
												$cont_no = $data_assignment[$i]['cont_no'];
												$sql_countTruck = "SELECT COUNT(*) AS rtnValue FROM do_truck_details_entry WHERE cont_no='$cont_no' AND DATE(last_update) = DATE(NOW())";
												$data_countTruck = mysqli_query($con_cchaportdb,$sql_countTruck);
												$truckAdded = null;

												while($row = mysqli_fetch_object($data_countTruck)){
													$truckAdded = $row->rtnValue;
												}

												$result = $this->bm->chkBlockedContainerforTruckEntry($cont_no,$data_assignment[$i]['rot_no'],$data_assignment[$i]['bl_no']);

												$custom_block_status = "";
												for($ij = 0; $ij<count($result);$ij++){
													$custom_block_status = $result[$ij]['custom_block_st'];
												}
												$custom_block = "";
												if($custom_block_status == "DO_NOT_RELEASE"){
													$custom_block = "Blocked.";
												}
												// echo $truckAdded;
										?>	
											<option value="<?php echo $data_assignment[$i]['cont_no']."|".$data_assignment[$i]['rot_no']."|".$data_assignment[$i]['cont_status']."|".$data_assignment[$i]['unit_gkey']."|".$data_assignment[$i]['size']."|".$data_assignment[$i]['mfdch_value']."|".$custom_block; ?>" <?php if( isset($editType) && $data_assignment[$i]['cont_no'] == $cont){echo "selected";}else if(isset($contNo) && $data_assignment[$i]['cont_no'] == $contNo){ echo "selected";}?>><?php echo $cont_no." (".$data_assignment[$i]['size']." - ".$data_assignment[$i]['mfdch_value'].")  ( ".$truckAdded." Truck/s Added) "."(Yard: {$data_assignment[$i]['Yard_No']} , Block: {$data_assignment[$i]['Block_No']}) , {$custom_block}"; ?></option>
										<?php	
											}
										?>
									</select>
									<?php
										if(isset($editType)){
									?>
										<input type="hidden" name="assignmentData" id="assignmentData" value="<?php echo $assignmentData;?>"/>
									<?php
										}
									?>
								</div>

								<!-- <div class="input-group mb-md">
									<span class="input-group-addon span_width">Jetty Sircar</span>
									<input class="form-control" list="jsPassList" name="jsGatePass" id="jsGatePass" value="<?php //if(isset($editType)){ echo $cardNumber;}else if(isset($jsGatePass)){ echo $jsGatePass;}?>" onblur="getJettySrkrInfo()" autocomplete="off">
									<datalist id="jsPassList">
										<?php
											//for($i=0;count($data_jsInfo)>$i;$i++){
										?>
											<option value="<?php //echo $data_jsInfo[$i]['card_number']; ?>"/>
										<?php
											//}
										?>
									</datalist>
								</div> -->

								<!-- <div class="row">
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Jetty Sircar Name</span>
											<span class="input-group-addon span_width" id="jsName" name="jsName"><?php //if(isset($editType)){ echo $agent_name;}?></span>
											<input type="hidden" name="jsNameLvl" id="jsNameLvl" value="<?php //if(isset($editType)){ echo $agent_name;}?>" class="form-control">
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Jetty Sircar License</span>
											<span class="input-group-addon span_width" id="jsLicenseNo" name="jsLicenseNo"><?php //if(isset($editType)){ echo $agent_code;}?></span>
											<input type="hidden" name="jsLicenseNoLvl" id="jsLicenseNoLvl" value="<?php //if(isset($editType)){ echo $agent_code;}?>" class="form-control">
										</div>
									</div>
								</div> -->
								
								<div class="row">
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Driver Pass</span>
											<!-- <input class="form-control" list="driverPassList" name="driverPassNo" id="driverPassNo" value="<?php //if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['driver_gate_pass'];}?>" oninput="getDriverInfo()" autocomplete="off" <?php //if(isset($editType) && $editType == "Replace"){ echo "readonly";} ?> >
											<datalist id="driverPassList" >
												<?php
												// for($i=0;$i<count($rslt_driverInfo);$i++)
												// {
												?>
												<option value="<?php //echo $rslt_driverInfo[$i]['card_number']; ?>">
												<?php
												// }
												?>
											</datalist> -->

											<input type="text" class="form-control" name="driverPassNo" id="driverPassNo" onfocus="setBlankField(this)" onchange="getCardNumer(this.value)">

										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Helper Pass</span>
											<!-- <input class="form-control" list="assistantPassList" name="assistantPassNo" id="assistantPassNo" value="<?php //if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['assistant_gate_pass'];}?>" oninput="getAssistantInfo()" autocomplete="off" <?php //if(isset($editType) && $editType == "Replace"){ echo "readonly";} ?>>
											<datalist id="assistantPassList">
												<?php
												// for($i=0;$i<count($rslt_helperInfo);$i++)
												// {
												?>
												<option value="<?php //echo $rslt_helperInfo[$i]['card_number']; ?>">
												<?php
												// }
												?>
											</datalist> -->

											<input type="text" class="form-control" name="assistantPassNo" id="assistantPassNo" onfocus="setBlankField(this)" onchange="getCardNumerforhelper(this.value)">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="col-md-12 input-group mb-md text-center" id="driverImg">
										
										</div>
									</div>

									<div class="col-md-6 text-center">
										<div class="col-md-12 input-group mb-md text-center" id="helperImg">
										
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Driver Name</span>
											<span class="input-group-addon span_width" id="driverNamelbl" name="driverNamelbl"><?php if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['driver_name'];}?></span>
											<input type="hidden" name="driverName" id="driverName" value="<?php if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['driver_name'];}?>" class="form-control">
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Helper Name</span>
											<span class="input-group-addon span_width" name="assistantNamelbl" id="assistantNamelbl"><?php if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['assistant_name'];}?></span>
											<input type="hidden" name="assistantName" id="assistantName" value="<?php if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['assistant_name'];}?>" class="form-control">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Driver Phone</span>
											<span class="input-group-addon span_width" name="driverMobileNumberLbl" id="driverMobileNumberLbl"><?php if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['driver_mobile_number'];}?></span>
											<input type="hidden" name="driverMobileNumber" id="driverMobileNumber" value="<?php if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['driver_mobile_number'];}?>" class="form-control">
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Helper Phone</span>
											<span class="input-group-addon span_width" name="helperMobileNumberLbl" id="helperMobileNumberLbl"><?php if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['helper_mobile_number'];}?></span>
											<input type="hidden" name="helperMobileNumber" id="helperMobileNumber" value="<?php if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['helper_mobile_number'];}?>" class="form-control">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Truck Reg No</span>
											<div>
											<?php
												if(isset($editType) && count($rslt_trkEditInfo)>0)
												{ 
													$truckId = $rslt_trkEditInfo[0]['truck_id'];
													$val = explode(" ",$truckId);
													$regCity = $val[0];
													$regClass = $val[1]." ".$val[2]." ".$val[3];
													$truckNo = $val[4];
												}
											?>
												<div class="row">
													<div class="col-sm-5" style="padding-right:2px;">
													<?php 
														if(isset($editType) && $editType == "Replace")
															{ 
													?>
															<input type="hidden" name="prev_truckId" id="prev_truckId" value="<?php echo $truckId; ?>"/>
													<?php
															}
													?>

														<select class="form-control" id="regCity" name="regCity" style="padding:0px;">
															<option value="">--Select--</option>
															<?php
															include("mydbPConnection.php");
															$strDistrict = "SELECT * FROM truck_district";
															$resDistrict = mysqli_query($con_cchaportdb,$strDistrict);
															while($rowDist = mysqli_fetch_object($resDistrict))
															{
															?>
															<option value="<?php echo $rowDist->value ?>" <?php if(isset($editType)){if($regCity == $rowDist->value){ echo "selected";}} ?>><?php echo $rowDist->value ?></option>
															<?php } ?>

														</select>
													</div>
													<div class="col-sm-3" style="padding-right:4px;padding-left:2px;">
														<input class="form-control" list="regClassList" name="regClass" id="regClass" style="padding:0px;" value="<?php if(isset($editType)){ echo $regClass;}?>">
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
														<input style="padding:2px;" class="form-control" type="text" id="truckNo" onkeyup="getFormat(this.value);" name="truckNo" value="<?php if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $truckNo;  } ?>">
													</div>
												</div>
													<!-- <label class="control-label" id="regHelp" style="display:none;"><b><font color="red" id="regHelpSpan"></font></b></label> -->
											</div>
										</div>
									</div>
																			
									<!-- <div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Importer's Mobile</span>
											<input type="text" name="importerMobileNo" id="importerMobileNo" value="<?php //if(isset($editType)){ echo $importerMobile;}?>" class="form-control">
										</div>
									</div> -->

								</div>

								<!-- <div class="row">
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Truck Agency</span>
											<input type="text" name="agencyName" id="agencyName" value="<?php //if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['truck_agency_name'];}?>" class="form-control">
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Agency Phone</span>
											<input type="text" name="agencyPhone" id="agencyPhone" value="<?php //if(isset($editType) && count($rslt_trkEditInfo)>0){ echo $rslt_trkEditInfo[0]['truck_agency_phone'];}?>" class="form-control">
										</div>
									</div>
								</div> -->

								<div class="input-group mb-md">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Time Slot</span>
										<div class="radio">
											<label style="color:black;">
												<input type="radio" name="truckSlot" id="truckSlot_1" value="1" <?php if(isset($editType) && count($rslt_trkEditInfo)>0){if($truckSlot!="" and $truckSlot=="1"){?>checked<?php } ?> <?php if($truckSlot=="2" or $truckSlot=="3"){?>disabled<?php }} ?>>
												<b>Slot 1 (<?php echo date("Y-m-d"); ?> 08:00:00 to <?php echo date("Y-m-d"); ?> 15:59:59)</b> <!--  , Vacant : 88 of 88  --> 
											</label>
										</div>
										<div class="radio">
											<label style="color:black;">
												<input type="radio" name="truckSlot" id="truckSlot_2" value="2" <?php if(isset($editType) && count($rslt_trkEditInfo)>0){if($truckSlot!="" and $truckSlot=="2"){?>checked<?php } ?> <?php if($truckSlot=="1" or $truckSlot=="3"){?>disabled<?php }} ?>>
												<b>Slot 2 (<?php echo date("Y-m-d"); ?> 16:00:00 to <?php echo date("Y-m-d"); ?> 23:59:59)</b> <!-- , Vacant : 125 of 125 --> 
											</label>
										</div>
										<div class="radio">
											<label style="color:black;">
												<input type="radio" name="truckSlot" id="truckSlot_3" value="3" <?php if(isset($editType) && count($rslt_trkEditInfo)>0){if($truckSlot!="" and $truckSlot=="3"){?>checked<?php } ?> <?php if($truckSlot=="1" or $truckSlot=="2"){?>disabled<?php }} ?>>
												<b>Slot 3 (<?php echo date('Y-m-d', strtotime(date("Y-m-d").' +1 day')) ?> 00:00:00 to <?php echo date('Y-m-d', strtotime(date("Y-m-d").' +1 day')) ?> 07:59:59)</b> <!-- , Vacant : 37 of 37 --> 
											</label>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 text-center">
										<?php
											if(isset($editType)){
										?>
											<input type="hidden" name="editType" id="editType" value="<?php echo $editType;?>"/>
										<?php
											}else{
										?>
											<input type="hidden" name="editType" id="editType" value=""/>
										<?php
											}
										?>
										<button type="submit" style="width:70px;" value="<?php if(isset($editType)){?>Update<?php }else{?>Add<?php }?>" name="searchByCnfId" id="searchByCnfId" class="btn btn-primary btn-xs" ><?php if(isset($editType)){?>Update<?php }else{?>Add<?php }?></button>
									</div>
								</div>
								

								<!-- <table>
									<tr>
										<td></td>
									</tr>
								</table> -->
							<?php		
								}
							?>
						</div>
					</div>	
				</form>

				<?php
					if($flag == 1){
				?>

				<div class="table-responsive" style="padding-top:20px;">
					<table id="truckInfoTbl" class="table table-bordered  table-hover table-striped mb-none" cellspacing="2" cellpadding="2" align="left">
																			
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
									<form method="post" action="<?php echo site_url('ShedBillController/fclTruckEntryforSecurity'); ?>">
										<input type="hidden" class="form-control" name="cnfAinNo" id="cnfAinNo" value="<?php if($flag == 1){echo $ain;}?>" />
										<input type="hidden" id="editId" name="editId" value="<?php echo $rslt_tmpTrkData[$i]['id']; ?>" />
										<input type="hidden" id="btnType" name="btnType" value="<?php echo $btnType;?>" />
										<input style="width:70px;" type="submit" class="btn btn-primary btn-xs" id="editBtn" name="editBtn" value="<?php echo $btnType;?>" <?php if($rslt_tmpTrkData[$i]['gate_out_status'] == 1){ ?>disabled<?php } ?> />
									</form>
									<br/>
									<?php if($rslt_tmpTrkData[$i]['paid_status'] == 0 || $rslt_tmpTrkData[$i]['paid_status'] == 2)
									{ ?>
									<form method="post" action="<?php echo site_url('ShedBillController/fclTruckEntryforSecurity'); ?>" 
									onsubmit="return(delete_truck());">
										<input type="hidden" class="form-control" name="cnfAinNo" id="cnfAinNo" value="<?php if($flag == 1){echo $ain;}?>" />
										<input type="hidden" id="delId" name="delId" value="<?php echo $rslt_tmpTrkData[$i]['id']; ?>" />
										<input type="hidden" id="btnType" name="btnType" value="<?php echo $btnType;?>" />
										<input style="width:70px;" type="submit" class="btn btn-primary btn-xs" id="delBtn" name="delBtn" value="<?php echo $btnSt;?>" <?php if($rslt_tmpTrkData[$i]['gate_out_status'] == 1){ ?>disabled<?php } ?> />
									</form>		
									<?php } ?>			
									
								</td>
								<td align="center">
									<p class="m-none">
										<form method="post" action="<?php echo site_url('ShedBillController/truckPayBySecurity'); ?>">
											<input type="hidden" id="truckDtlId" name="truckDtlId" value="<?php echo $rslt_tmpTrkData[$i]['id']; ?>" />
											<input type="hidden" id="payAmt" name="payAmt" value="57.5" />
											<input type="hidden" id="payMethod" name="payMethod" value="cash" />
											<input type="hidden" id="cnfAinNo" name="cnfAinNo" value="<?php echo $ain;?>" />
											<input type="hidden" id="payment" name="payment" value="payment" />
											
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
											<input type="hidden" name="rot_no" id="rot_no" value="<?php echo $rslt_tmpTrkData[$i]['import_rotation'];?>"/>
											<input type="hidden" name="cont_no" id="cont_no" value="<?php echo $rslt_tmpTrkData[$i]['cont_no'];?>"/>
											<input type="hidden" name="trucVisitId" id="trucVisitId" value="<?php echo $rslt_tmpTrkData[$i]['id']; ?>"/>
											<input type="submit" style="width:70px;" class="btn btn-xs btn-primary" name="printPassBtn" id="printPassBtn" value="Print" <?php if($rslt_tmpTrkData[$i]['paid_status'] == 0){ ?>disabled<?php } ?> />
										</form>														
									</p>
									<p class="m-none">	
										<?php
											include("dbConection.php");
											$cont_number = $rslt_tmpTrkData[$i]['cont_no'];
											$BlockQuery = "SELECT DISTINCT Block_No FROM ctmsmis.tmp_vcms_assignment WHERE cont_no = '$cont_number' ORDER BY flex_date01 LIMIT 1";

											$rsltBlock = mysqli_query($con_sparcsn4,$BlockQuery);
								
											$blockNo="";
											while($BlockResult=mysqli_fetch_object($rsltBlock)){
												$blockNo = $BlockResult->Block_No;
											}
										?>														
										<form action="http://192.168.3.30:8095/tosprint.php" method="post">
											<input type="hidden" name="VISITNO" value="<?php echo $rslt_tmpTrkData[$i]['id']; ?>">
											<input type="hidden" name="VHTYPE" value="Truck">
											<input type="hidden" name="FEE" value="57.50">
											<input type="hidden" name="VHLP" value="<?php echo $rslt_tmpTrkData[$i]['truck_id']; ?>">
											<input type="hidden" name="DRIVERNAME" value="<?php echo $rslt_tmpTrkData[$i]['driver_name']; ?>">
											<input type="hidden" name="DRIVERCARDNO" value="<?php echo $rslt_tmpTrkData[$i]['driver_gate_pass']; ?>">
											<input type="hidden" name="HELPERNAME" value="<?php echo $rslt_tmpTrkData[$i]['assistant_name']; ?>">
											<input type="hidden" name="HELPERCARDNO" value="<?php echo $rslt_tmpTrkData[$i]['assistant_gate_pass']; ?>">
											<input type="hidden" name="AGENCYNAME" value="<?php echo $cf_name; ?>">
											<input type="hidden" name="AGENCYTYPE" value="C&F Agent">
											<input type="hidden" name="AGENCYCODE" value="<?php echo $ain; ?>">
											<input type="hidden" name="VALIDTILL" value="<?php echo $validDate = date('Y-m-d H:i:s', strtotime($rslt_tmpTrkData[$i]['paid_collect_dt']. ' +1 day')); ?>">
											<input type="hidden" name="USERNAME" value="<?php echo $this->session->userdata('login_id');?>">
											<input type="hidden" name="CONTAINER" value="<?php echo $rslt_tmpTrkData[$i]['cont_no']; ?>">
											<input type="hidden" name="GATENAME" value="<?php echo $rslt_tmpTrkData[$i]['gate_no']; ?>">
											<input type="hidden" name="BLOCKNO" value="<?php echo $blockNo; ?>">
											<input type="hidden" name="VEHICLEAGENCY" value="<?php echo $rslt_tmpTrkData[$i]['truck_agency_name']; ?>">
											<input type="submit" style="width:70px;" class="btn btn-xs btn-warning" value="Biometric Print" />
										</form>
									</p>		
								</td>												
							</tr>
							<?php
								}
								?>
							<!-- <tr>
								<td align="center" colspan="7">
									<form method="post" action="<?php echo site_url('ShedBillController/cnfTruckPayForm'); ?>">
										<input type="hidden" id="payType" name="payType" value="allPay"/>
										<input type="hidden" id="contNo" name="contNo" value="<?php echo $rslt_tmpTrkData[$i]['cont_no'];?>"> 
										<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rslt_tmpTrkData[$i]['import_rotation']; ?>">
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
							</tr> -->
							<?php
							}
							?>
					</table>
				</div>
				
				<?php		
					}
				?>

			</div>
		</section>

		<?php
			if(isset($payFlag))
			{
		?>
		<section class="panel panel-primary" id="panel-1" data-portlet-item="">
			<header class="panel-heading portlet-handler">
				<h2 class="panel-title">Payment</h2>
			</header>
			<div class="panel-body">
				<form class="form-horizontal" id="paymentDtlForm" name="paymentDtlForm" method="post" action="<?php echo site_url('ShedBillController/truckPayBySecurity'); ?>" onsubmit="return confirmPay()">
					<input type="hidden" id="truckDtlId" name="truckDtlId" value="<?php if(isset($payFlag)){echo $truckDtlId;} ?>" />
					<input type="hidden" id="cnfAinNo" name="cnfAinNo" value="<?php echo $ain;?>" />
					<input type="hidden" id="payment" name="payment" value="payment" />
					<div class="form-group">
						<label class="col-md-4 control-label">&nbsp;</label>
						<div class="col-md-4">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Payment For</span>													
								<input class="form-control" type="text" id="paymentFor" name="paymentFor" value="<?php echo $truckDtlId;?>" readonly/>
							</div>

							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Method</span>													
								<select class="form-control" id="payMethod" name="payMethod" onchange="btncng(this.value)">
									<option value="cash" <?php if(isset($payFlag)){if($payMethod == "cash"){echo "selected";}} ?>>Cash</option>
									<!-- <option value="cash" <?php //if(isset($payFlag)){if($payMethod == "online"){echo "selected";}} ?>>Online</option> -->
								</select>
							</div>
						
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Amount</span>
								<input class="form-control" type="text" id="payAmt" name="payAmt" value="57.50" readonly/>
							</div>																	
						</div>
																												
						<?php
							$cont_blocked_status = "";
							if($cont_blocked_status == "Blocked")
							{
						?>
							<div class="row">
								<div class="col-sm-12 text-center">
									<font size="4" color="red"><b><?php echo $contNo; ?> Container is Blocked!!</b></font>
								</div>
							</div>
						<?php
							}
							else
							{
						?>

							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" value="save" name="paymentBtn" id="paymentBtn" class="btn btn-primary">Save</button>	
								</div>
							</div>
						<?php
							}
						?>
					</div>	
				</form>
			</div>
		</section>
		<?php
			}
		?>
	<!-- end: page -->
</section>
</div>