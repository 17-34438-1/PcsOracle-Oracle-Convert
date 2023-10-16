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
            <div class="col-md-12">
                <div class="form-group col-md-5">
                    <form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/driverOcdTruckPay") ?>" onsubmit="return chkBlankField();">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <?php echo $msg; ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-md">
                                <span class="input-group-addon span_width">Container No<span class="required">*</span></span>
                                <input type="text" name="contNo" id="contNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Container No" value="<?php echo $cont; ?>" readonly/>
								<input type="hidden" name="ain" id="ain" value="<?php echo $ain; ?>"/>
								<input type="hidden" name="cf_lic" id="cf_lic" value="<?php echo $cf_lic; ?>"/>
                            </div>
                        </div>	

                        <div class="col-md-12">		
                            <div class="input-group mb-md">
                                <span class="input-group-addon span_width">Driver Pass<span class="required">*</span></span>
                                <input type="text" name="driverPassNo" id="driverPassNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Driver Pass" onblur="getDriverInfo()" value="<?php echo $driverPass; ?>" readonly/>
                            </div>
                        </div>
                        <div class="col-md-12" style="display:block">
                            <div class="input-group mb-md">
                                <span class="input-group-addon span_width">Driver Name</span>
                                <input type="text" name="driverName" id="driverName" class="form-control login_input_text" autofocus= "autofocus" value="<?php echo $driverName; ?>" readonly/>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="input-group mb-md">
                                <span class="input-group-addon span_width">Phone No<span class="required">*</span></span>
                                <input type="text" name="phoneNo" id="phoneNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Phone No" value="<?php echo $phoneNo; ?>" readonly/>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="input-group mb-md">
                                <span class="input-group-addon span_width">Helper Pass<span class="required">*</span></span>
                                <input type="text" name="assistantPassNo" id="assistantPassNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Helper Pass" onblur="getAssistantInfo()" value="<?php echo $assistantPassNo; ?>" readonly/>
                            </div>
                        </div>
                        <div class="col-md-12" style="display:block">
                            <div class="input-group mb-md">
                                <span class="input-group-addon span_width">Helper Name</span>
                                <input type="text" name="assistantName" id="assistantName" class="form-control login_input_text" autofocus= "autofocus" value="<?php echo $assistantName; ?>" readonly/>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="input-group mb-md">
                                <span class="input-group-addon span_width">Truck No<span class="required">*</span></span>
                                <input style="padding:6px 6px;" type="text" name="truckNo" id="truckNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Truck No" value="<?php echo $truckNo; ?>" readonly/>
                            </div>
                        </div>

						<div class="col-md-12">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Truck Slot: </span>
								<div>
									<div class="radio">
										<label style="color:black;">
											<input type="radio" name="truckSlot" id="truckSlot_1" value="1" <?php if($slot == 1){ echo "checked";}?> disabled>
											<b>Slot 1</b> 
										</label>
									</div>
									<div class="radio">
										<label style="color:black;">
											<input type="radio" name="truckSlot" id="truckSlot_2" value="2" <?php if($slot == 2){ echo "checked";}?> disabled>
											<b>Slot 2</b> 
										</label>
									</div>
									<div class="radio">
										<label style="color:black;">
											<input type="radio" name="truckSlot" id="truckSlot_3" value="3" <?php if($slot == 3){ echo "checked";}?> disabled>
											<b>Slot 3</b> 
										</label>
									</div>
								</div>
							</div>
						</div>

                        <div class="row" id="applyBtn">
                            <div class="col-sm-12 text-center">
                                <button type="submit" name="btnVerify" value="ekpay" class="mb-xs mt-xs mr-xs btn btn-primary" disabled <?php if($custom_block_status == "DO_NOT_RELEASE"){echo "disabled";}?>>
                                    EkPay
                                </button>
                                <button type="submit" name="btnVerify" value="sonalypay" class="mb-xs mt-xs mr-xs btn btn-warning" <?php if($custom_block_status == "DO_NOT_RELEASE"){echo "disabled";}?>>
                                    SonaliPay
                                </button>
                            </div>
                        </div>

						<div class="col-md-12">
							<div class="input-group mb-md">
								<div>
									<div>
										<label style="color:black;">
											<b>*Slot 1 = <?php echo date('Y-m-d'); ?> 08:00:00 to 15:59:59</b> 
										</label>
									</div>
									<div>
										<label style="color:black;">
											<b>*Slot 2 = <?php echo date('Y-m-d'); ?> 16:00:00 to 23:59:59</b> 
										</label>
									</div>
									<div>
										<label style="color:black;">
											<b>*Slot 3 = <?php echo date('Y-m-d', strtotime(' +1 day')); ?> 00:00:00 to 07:59:59</b> 
										</label>
									</div>
								</div>
							</div>
						</div>

                    </form>
                </div>

                <div class="col-md-7">
					<div class="col-md-6" style="padding:0px;">
						<h3 class="col-md-12 col-form-label"><u>Truck Info</u></h3>
						<label class="col-md-12 col-form-label">Container No: <b><?php echo $cont; ?></b></label>

						<label class="col-md-12 col-form-label">Driver Pass: <b><?php echo $driverPass; ?></b></label>

						<label class="col-md-12 col-form-label">Driver Name: <b><?php echo $driverName; ?></b></label>

						<label class="col-md-12 col-form-label">Phone No: <b><?php echo $phoneNo; ?></b></label>

						<label class="col-md-12 col-form-label">Helper Pass: <b><?php echo $assistantPassNo; ?></b></label>

						<label class="col-md-12 col-form-label">Helper Name: <b><?php echo $assistantName; ?></b></label>

						<label class="col-md-12 col-form-label">Truck No: <b><?php echo $truckNo; ?></b></label>
						<label class="col-md-12 col-form-label">Truck Slot: 
							<b>
								<?php 
									if($slot == 1)
									{
										echo date('Y-m-d')." 08:00:00 to 15:59:59";
									}
									else if($slot == 2)
									{
										echo date('Y-m-d')." 16:00:00 to 23:59:59";
									}
									else if($slot == 3)
									{
										echo date('Y-m-d', strtotime(' +1 day'))." 16:00:00 to 23:59:59";
									}
								?>
							</b>
						</label>

					</div>

					<div class="col-md-6">
						<h3 class="col-md-12 col-form-label"><u>C&F Info</u></h3>
						<label class="col-md-12 col-form-label">C&F Name: <b><?php echo $cf_name; ?></b></label>
						<label class="col-md-12 col-form-label">C&F AIN: <b><?php echo $ain; ?></b></label>
					</div>

					<div class="col-md-12">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td align="center">Visit Id</td>
									<td align="center">Cont</td>
									<td align="center">Truck Id</td>
									<td align="center">Payment Status</td>
									<td align="center">Sonali Pay</td>
									<td align="center">Ekpay</td>
								</tr>
							</thead>
							<tbody>
							<?php
								$visitId = "";
								$cont = "";
								$truckId = "";
								$paid_stat = "";

								$i=0;
								while($i<count($truckRslt)){
									$visitId = $truckRslt[$i]['id'];
									$cont = $truckRslt[$i]['cont_no'];
									$rot = $truckRslt[$i]['import_rotation'];
									$truckId = $truckRslt[$i]['truck_id'];
									$paid_status = $truckRslt[$i]['paid_status'];

									// Cont_status
									$contStsQuery = "SELECT cont_status FROM(
										SELECT Import_Rotation_No,BL_No,cont_number,cont_status
										FROM igm_supplimentary_detail
										INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
										WHERE igm_supplimentary_detail.Import_Rotation_No='$rot' AND igm_sup_detail_container.cont_number='$cont'
										
										UNION
										
										SELECT Import_Rotation_No,BL_No,cont_number,cont_status
										FROM igm_details
										INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
										WHERE igm_details.Import_Rotation_No='$rot' AND igm_detail_container.cont_number='$cont'
										) AS rtnValue LIMIT 1";
	
									$contStsRslt = $this->bm->dataSelectDB1($contStsQuery);
									
									$cont_status = "";
	
									for($j=0;$j<count($contStsRslt);$j++){
										$cont_status = $contStsRslt[$j]['cont_status'];
									}
							?>
								
								<tr>
									<td align="center"><?php echo $visitId; ?></td>
									<td align="center"><?php echo $cont; ?></td>
									<td align="center"><?php echo $truckId; ?></td>
									<td class="text-center">
										<?php 
											if($paid_status == 1)
											{
												echo "<font color='green'><b>paid</b></font>";
											}
											else if($paid_status == 2)
											{
												echo "<font color='red'><b>pending</b></font>";
											}
											else if($paid_status == 0)
											{
												echo "<font color='red'><b>not paid </b></font>";
											}
										?>
									</td>
									<td>
										<?php
											if($paid_status == 0)
											{
										?>
										<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('ShedBillController/checkoutbyOnline') ?>">
											<input type="hidden" name="assignmentType" id="assignmentType" value="<?php echo $assignmentType;?>">
											<input type="hidden" name="payAmt" id="payAmt" value="57.5">
											<input type="hidden" name="payVisitId" id="payVisitId" value="<?php echo $visitId;?>">
											<input type="hidden" name="contNo" id="contNo" value="<?php echo $cont;?>">
											<input type="hidden" name="cont_status" id="cont_status" value="<?php echo $cont_status;?>">
											<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rot;?>">
											<button type="submit" name="gatewayType" value="sonali" class="btn btn-warning btn-xs"/>
												SonaliPay
											</button>
										</form>
										<?php
											}
										?>
									</td>
									
									<td>
										<?php
											if($paid_status == 0)
											{
										?>
										<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('ShedBillController/checkoutbyOnline') ?>">
											<input type="hidden" name="assignmentType" id="assignmentType" value="<?php echo $assignmentType;?>">
											<input type="hidden" name="payAmt" id="payAmt" value="57.5">
											<input type="hidden" name="payVisitId" id="payVisitId" value="<?php echo $visitId;?>">
											<input type="hidden" name="contNo" id="contNo" value="<?php echo $cont;?>">
											<input type="hidden" name="cont_status" id="cont_status" value="<?php echo $cont_status;?>">
											<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rot;?>">
											<button type="submit" name="gatewayType" value="ekpay" class="btn btn-success btn-xs" disabled/>
												EkPay
											</button>
										</form>
										<?php
											}
										?>
									</td>
								</tr>

							<?php
								$i++;
								}
							?>
							</tbody>
						</table>
					</div>
                </div>
            </div>
        </div>
    </section>
	<!-- end: page -->
</section>
</div>