<script>	
	function getJettySrkrInfo()
	{
		var jsId = document.getElementById('jsName').value;
		
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
							
				var jsLicNo = jsonData.rslt_jsInfo[0].js_lic_no;
				var jsCellNo = jsonData.rslt_jsInfo[0].cell_no;
				var jsAdress = jsonData.rslt_jsInfo[0].adress;								
				
				document.getElementById('jsLicenseNo').value = jsLicNo;
				document.getElementById('jsContact').value = jsCellNo;
				document.getElementById('jsAddress').value = jsAdress;
			}
		};
		
		var url = "<?php echo site_url('AjaxController/getJettySrkrInfo')?>?jsId="+jsId;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}	
	
	function getDriverInfo()
	{		
		var driverPassNo = document.getElementById('driverPassNo').value;
		
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
					var mobile_number = mobile_number.replace("-", "");
					// alert(mobile_number);
					var mobile_length = mobile_number.length;
					var res = mobile_number.substr(0, 1);
					// alert(mobile_length);


					if(mobile_length == 10){
						mobile_number = "0".concat(mobile_number);
						// alert(mobile_number);
					}
					// else{
					// 	mobile_number = "+88".concat(mobile_number);
					// 	// alert(mobile_number);
					// }

					licNo = jsonData.rslt_driverInfo[0].lic_no;
					// truckNumber = jsonData.rslt_driverInfo[0].truck_number;
				}
				else
				{
					alert("Please Assign a driver!");
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
	}
	
	function getAssistantInfo()
	{
		var assistantPassNo = document.getElementById('assistantPassNo').value;
				
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
					driverAssistName = jsonData.rslt_driverInfo[0].driver_assist_name;
					mobile_number = jsonData.rslt_driverInfo[0].mobile_number;

					var mobile_length = mobile_number.length;
					var res = mobile_number.substr(0, 1);
					// alert(mobile_length);


					if(mobile_length == 10){
						mobile_number = "0".concat(mobile_number);
						// alert(mobile_number);
					}
					// else{
					// 	mobile_number = "".concat(mobile_number);
					// 	// alert(mobile_number);
					// }

					licNo = jsonData.rslt_driverInfo[0].lic_no;
					// truckNumber = jsonData.rslt_driverInfo[0].truck_number;
				}	
				else
				{
					alert("Please Assign a helper!");
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
	}
	
	// var trkSl = 0;
	// function addTruckToTmp()
	// {								
		// var emrgncyTrk = document.getElementById('addBtn').value;
		// alert(emrgncyTrk);
		// // return false;
		// var vrfyInfoFclId = document.getElementById('vrfyInfoFclId').value;
		
		// var truckNo = document.getElementById('truck').value;
		// //var pkQty = document.getElementById('pkQty').value;
		// //var gateNo = document.getElementById('gateNo').value;
		// var driverName = document.getElementById('driverName').value;
		// var driverPassNo = document.getElementById('driverPassNo').value;
		// var assistantName = document.getElementById('assistantName').value;
		// var assistantPassNo = document.getElementById('assistantPassNo').value;
		
		// if (window.XMLHttpRequest) 
		// {
			// xmlhttp=new XMLHttpRequest();
		// } 
		// else 
		// {  
			// xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		// }
		
		// xmlhttp.onreadystatechange=function(){	
			// // alert("alert");		
			// var val = xmlhttp.responseText;
			// // alert(val);	
			// var jsonData = JSON.parse(val);
			// // alert("jsonData.stat "+jsonData.stat);
			// if(jsonData.stat == true)
			// {				
				// document.getElementById('truck').value = "";
				// //document.getElementById('pkQty').value = "";
				// //document.getElementById('gateNo').value = "";
				// document.getElementById('driverName').value = "";
				// document.getElementById('driverPassNo').value = "";
				// document.getElementById('assistantName').value = "";
				// document.getElementById('assistantPassNo').value = "";	
				// clearTableRow();
				// AddTableRow(jsonData);				
			// }				
		// };
		
		// var url = "<?php echo site_url('ajaxController/addTruckToTmp')?>?vrfyInfoFclId="+vrfyInfoFclId+"&truckNo="+truckNo+"&driverName="+driverName+"&driverPassNo="+driverPassNo+"&assistantName="+assistantName+"&assistantPassNo="+assistantPassNo+"&emrgncyTrk="+emrgncyTrk;
		// // alert(url);
		// // return false;
		// xmlhttp.open("GET",url,false);
		// xmlhttp.send();
	// }
	
	// function clearTableRow()
	// {
		// var table = document.getElementById("truckInfoTbl");
		// var tblLen = table.rows.length;
		
		// for(var i=tblLen-1;i>2;i--)
		// {
			// document.getElementById("truckInfoTbl").deleteRow(i);
		// }
	// }
	
	// function deleteTableRow(id)
	// {
		// // alert(id);
		// if(confirm("Delete this entry?"))
		// {
			// var vrfyInfoFclId = document.getElementById('vrfyInfoFclId').value;
			// // alert("vrfyInfoFclId "+vrfyInfoFclId);
			// if (window.XMLHttpRequest) 
			// {
				// xmlhttp=new XMLHttpRequest();
			// } 
			// else 
			// {  
				// xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			// }
			
			// xmlhttp.onreadystatechange=function(){
				// var val = xmlhttp.responseText;
				// var jsonData = JSON.parse(val);
			
				// if(jsonData.stat == true)
				// {
					// clearTableRow();
					// AddTableRow(jsonData);
					
					
				// }
				// else
				// {
					// alert("Delete is not successful");
				// }
			// };
			
			// var url = "<?php echo site_url('AjaxController/deleteTmpData')?>?id="+id+"&vrfyInfoFclId="+vrfyInfoFclId;	
				
			// xmlhttp.open("GET",url,false);
			// xmlhttp.send();
		// }	
	// }
	
	// function AddTableRow(jsonData)
	// {		
		// // if(jsonData.rslt_tmpRsltSet.length == document.getElementById('totTruck').value)
		// // {
			// // // alert("none");
			// // document.getElementById('truckAddRow').style.display = "none"; 
		// // }
		// // else
		// // {
			// // // alert("block");
			// // document.getElementById('truckAddRow').style.display = "table-row";
		// // }
		
		// if(jsonData.rslt_tmpRsltSet.length == document.getElementById('totTruck').value)
		// {
			// if(document.getElementById('addBtn').value == "Emergency")
				// document.getElementById('addBtn').value= "Add";
			// else
				// document.getElementById('addBtn').value= "Emergency";
			
			// alert("add after change : "+document.getElementById('addBtn').value);
			// document.getElementById('truckAddRow').style.display = "table-row";
		// }
		// else if(jsonData.rslt_tmpRsltSet.length == (parseInt(document.getElementById('totTruck').value)+1))
		// {
			// // alert("block");
			// document.getElementById('truckAddRow').style.display = "none"; 
		// }	

		// var trkSl = 0;
		// document.getElementById("paymentAmt").value = 0;
		// for(var j=0;j<jsonData.rslt_tmpRsltSet.length;j++)
		// {
			// trkSl = trkSl+1;
			// var visitId = jsonData.rslt_tmpRsltSet[j].id;
			// // alert("visitId : "+visitId);
			// var truckNo = jsonData.rslt_tmpRsltSet[j].truck_id;
			// // var pkQty = jsonData.rslt_tmpRsltSet[j].pkg_qty;
			// // var gateNo = jsonData.rslt_tmpRsltSet[j].gate_no;
			// var driverName = jsonData.rslt_tmpRsltSet[j].driver_name;
			// var driverPassNo = jsonData.rslt_tmpRsltSet[j].driver_gate_pass;
			// var assistantName = jsonData.rslt_tmpRsltSet[j].assistant_name;
			// var assistantPassNo = jsonData.rslt_tmpRsltSet[j].assistant_gate_pass;
			
			// var payment = jsonData.rslt_tmpRsltSet[j].pay;
			
			// if(payment == "no")
			// {
				// var preamt = parseFloat(document.getElementById("paymentAmt").value);
				// var curamt = preamt + 57.50;
				
				// document.getElementById("paymentAmt").value=curamt;
			// }
			
			// var table = document.getElementById("truckInfoTbl");
			// var tr = document.createElement('tr');
			
			// var td1 = document.createElement('td');
			// var td2 = document.createElement('td');
			// // var td3 = document.createElement('td');
			// // var td4 = document.createElement('td');
			// var td5 = document.createElement('td');
			// var td6 = document.createElement('td');
			// var td7 = document.createElement('td');
			// var td8 = document.createElement('td');
			// var td9 = document.createElement('td');
			// var td10 = document.createElement('td');
			
			// td1.style.textAlign = "center";
			// td2.style.textAlign = "center";
			// // td3.style.textAlign = "center";
			// // td4.style.textAlign = "center";
			// td5.style.textAlign = "center";
			// td6.style.textAlign = "center";
			// td7.style.textAlign = "center";
			// td8.style.textAlign = "center";
			// td9.style.textAlign = "center";
			// td10.style.textAlign = "center";
			
			// td1.innerHTML = trkSl;
			// td2.innerHTML = ":";
			// // td3.innerHTML = pkQty;
			// // td4.innerHTML = gateNo;
			// td5.innerHTML = driverPassNo;
			// td6.innerHTML = driverName;
			// td7.innerHTML = truckNo;
			// td8.innerHTML = assistantPassNo;
			// td9.innerHTML = assistantName;		
			
			// if(payment == "no")
			// {
				// var input = document.createElement("input");
				// input.type = "button";			
				// input.id = "deleteBtn_"+(j+1);
				// input.name = "deleteBtn_"+(j+1);			
				// input.setAttribute('onclick', 'deleteTableRow('+visitId+')');					
				// input.value="DELETE";			
				// input.classList.add("btn");
				// input.classList.add("btn-primary");
				// input.classList.add("btn-xs");
				// input.style.width = "70px";
				// td10.appendChild(input);									
			// }
			// else
			// {
				// // td10.innerHTML = "Payment Done";
				// td10.innerHTML = "Replace";
			// }
			
			// tr.appendChild(td1);
			// tr.appendChild(td2);
			// // tr.appendChild(td3);
			// // tr.appendChild(td4);
			// tr.appendChild(td5);
			// tr.appendChild(td6);
			// tr.appendChild(td7);
			// tr.appendChild(td8);
			// tr.appendChild(td9);
			// tr.appendChild(td10);
			
			// table.appendChild(tr);
			
			
		// }			
	// }
	
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
			<!-- common information start -->
				
			<div class="row">
				<div class="col-md-12">
					<section class="panel form-wizard" id="w4">
						<header class="panel-heading">
							<div class="panel-actions">
								<a href="#" class="fa fa-caret-down"></a>
								<!-- <a href="#" class="fa fa-times"></a> -->
							</div>
			
							<h2 class="panel-title">Container Info</h2>
						</header>
						<section class="panel">
							<div class="panel-body">
								<div class="col-md-12"> 
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
											<?php echo $rtnVerifyReport[0]['Description_of_Goods']?>
										</div>										
									</div>
									<div class="row">
										<div class="col-md-2">
											<b>Number of Truck</b>
										</div>
										<div class="col-md-1">
											<b>:</b>
										</div>
										<div class="col-md-9">
											<?php echo $totTruck;?>
										</div>										
									</div>
									
									
									<!--table class="table table-bordered table-responsive table-hover table-striped mb-none" cellspacing="2" cellpadding="2" align="left"  id="mytbl">
									<?php 
									if(@$msgFlag==1)
									{
									?>				
										<tr align="center">
											<td colspan="9">
												<font color="green"><nobr><?php echo $msg;  ?></nobr></font>
											</td>
									<?php 
									} 
									else if(@$msgFlag==2)
									{
									?>
											<td colspan="9">
												<font color="red">
													<nobr><?php echo $msg;  ?></nobr>
												</font>
											</td>
										</tr>
									<?php 
									}
									?>
										<tr class="gridDark">
											<td><nobr>Vessel Name</nobr></td>	
											<td>:</td>	
											<td><nobr><?php echo $rtnVerifyReport[0]['vessel_name']?></nobr></td>
											<td>Rotation</td>	
											<td>:</td>	
											<td><?php echo $rtnVerifyReport[0]['import_rotation']?></td>			
											<td>B/L No</td>	
											<td>:</td>	
											<td><nobr><?php echo $rtnVerifyReport[0]['bl_no']?></nobr></td>
										</tr>	
										<tr class="gridDark">
											<td><nobr>IGM Quantity</nobr></td>	
											<td>:</td>	
											<td><?php echo $rtnVerifyReport[0]['Pack_Number']?></td>
											<td>IGM Unit</td>	
											<td>:</td>	
											<td><?php echo $rtnVerifyReport[0]['Pack_Description']?></td>
											<td>Container</td>	
											<td>:</td>	
											<td>
												<?php 
													if($cont_status!="FCL")
														echo $rtnVerifyReport[0]['cont_number'];
													else if($cont_status=="FCL")
														echo $containerSet;
												?>
											</td>
										</tr>
										<tr class="gridDark">
											<td><nobr>Current Position</nobr></td>	
											<td>:</td>	
											<td><?php echo $rslt_posYardBlock[0]['currentPos']?></td>
											<td>Yard</td>	
											<td>:</td>	
											<td><?php echo $rslt_posYardBlock[0]['Yard_No']?></td>
											<td>Block</td>	
											<td>:</td>	
											<td><?php echo $rslt_posYardBlock[0]['Block_No']?></td>
										</tr-->	
										<!--tr class="gridDark">
											<?php  if($fclFlagValue != 1){ ?>
											<td><nobr>Recieved Quantity</nobr></td>	
											<td>:</td>	
											<td><?php echo $rtnVerifyReport[0]['rcv_pack']?></td>
											<td>Recieved Unit</td>	
											<td>:</td>	
											<td><?php echo $rtnVerifyReport[0]['rcv_unit']?></td>
											<?php } ?>
											<td><nobr>Payment Status</nobr></td>	
											<td>:</td>	
											<td><nobr><?php if($rtnVerifyReport[0]['paid_status']=='Paid')
												{?>
												<font color='light green'><?php echo $rtnVerifyReport[0]['paid_status']?></font>
												<?php } if( $rtnVerifyReport[0]['paid_status']=='Not Paid')
												{?>
												<font color='red'><?php echo $rtnVerifyReport[0]['paid_status']?></font>
												<?php }?>
											</nobr></td>
										</tr-->
										<!--tr class="gridDark"> 	 
											<td>Goods Description</td>	
											<td>:</td>	
											<td colspan="7" style="font-size:11px"><?php echo $rtnVerifyReport[0]['Description_of_Goods']?></td>	
										</tr>
										<tr class="gridDark"> 
											<td>Verify No</td>	
											<td>:</td>	
											<td><?php echo $rtnVerifyReport[0]['verify_number']?></td>	
											<td><nobr>Number of Truck</nobr></td>	
											<td>:</td>				
											<td colspan="4"><?php echo $rtnVerifyReport[0]['no_of_truck'];?></td>	
											<input type="hidden" style="" id="numTruc" name="numTruc"  value="<?php echo $rtnVerifyReport[0]['no_of_truck']?>" readonly>												
										</tr>
									</table-->
								</div>
							</div>
						</section>
					</section>
				</div>
			</div>
		

			<div class="row">
				<div class="col-md-12">
					<div class="toggle" data-plugin-toggle>

						<!--  Transport Starts  -->
						<section class="toggle active">
							<label>Transport</label>
							<div class="toggle-content">
								<div class="panel-body">

									<form method="post" action="<?php echo site_url('ShedBillController/addTruckToTmp'); ?>">
										<input type="hidden" id="vrfyInfoFclId" name="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>" />
										<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
										<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
										<input type="hidden" id="fclFlagValue" name="fclFlagValue" value="<?php echo $fclFlagValue; ?>">
										<input type="hidden" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">						
										<input type="hidden" id="frmType" name="frmType" value="<?php echo $frmType; ?>">

										<!-- total truck allowed for container -->
										<input type="hidden" style="width:140px;" id="totTruck" name="totTruck" value="<?php echo $totTruck; ?>">
										
										<?php
										if(count($rslt_trkEditInfo)>0)
										{
										?>
										<input type="hidden" style="width:140px;" id="editFormId" name="editFormId" value="<?php echo $rslt_trkEditInfo[0]['id']; ?>">
										<?php
										}
										?>
										<div class="panel-body">							
											<?php
											if($frmType=="Replace")
											{
											?>	
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label" style="color:black;"><b>Prev Driver: <?php echo $rslt_trkEditInfo[0]['driver_name'];?></b></label>
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label" style="color:black;"><b>Driver Pass: <?php echo $rslt_trkEditInfo[0]['driver_gate_pass'];?></b></label>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label" style="color:black;"><b>Prev Helper: <?php echo $rslt_trkEditInfo[0]['assistant_name'];?></b></label>
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label" style="color:black;"><b>Helper Pass: <?php echo $rslt_trkEditInfo[0]['assistant_gate_pass'];?></b></label>
													</div>
												</div>
											</div>
												
											<?php
											}
											?>

											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Driver Pass</b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<!--input type="text" name="driverPassNo" id="driverPassNo" onblur="getDriverInfo()" class="form-control" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['driver_gate_pass']; ?>"-->
														<div class="col-sm-7">
														<input list="driverPassList" class="form-control" name="driverPassNo" id="driverPassNo" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['driver_gate_pass']; ?>" onblur="getDriverInfo()" autocomplete="off">

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
														<!--input type="text" name="assistantPassNo" id="assistantPassNo" onblur="getAssistantInfo()" class="form-control" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['assistant_gate_pass'] ?>"-->
														<div class="col-sm-7">
														<input list="assistantPassList" class="form-control" name="assistantPassNo" id="assistantPassNo" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['assistant_gate_pass']; ?>" onblur="getAssistantInfo()" autocomplete="off">

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
															<label class="control-label" style="color:black;" id="driverLbl"><?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['driver_name'] ?></label>
															<input type="hidden" name="driverName" id="driverName" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['driver_name'] ?>" class="form-control" >
														
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
															<label class="control-label" style="color:black;" id="helperLbl"><?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['assistant_name'] ?></label>
															<input type="hidden" name="assistantName" id="assistantName" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['assistant_name'] ?>" class="form-control">
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
															<label class="control-label" style="color:black;" id="driverMobileNumberLbl"><?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['driver_mobile_number'] ?></label>
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
														<label class="control-label" style="color:black;" id="helperMobileNumberLbl"><?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['helper_mobile_number'] ?></label>
														</div>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Truck ID</b></label>	
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>	
														</div>
														<div class="col-sm-7">
														<input type="text" class="form-control" id="truckNo" name="truckNo" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['truck_id'] ?>">
														</div>
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Importer Mobile</b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<div class="col-sm-7">
														<input type="text" class="form-control" id="importerMobileNo" name="importerMobileNo" value="<?php echo $importerMobile; ?>">
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
															<label class="control-label" style="color:black;"><b>Agency Name</b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<div class="col-sm-7">
															<input type="text" class="form-control" id="agencyName" name="agencyName" value="">
														</div>
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<div class="col-sm-4">
															<label class="control-label" style="color:black;"><b>Agency Phone</b></label>
														</div>
														<div class="col-sm-1">
															<label class="control-label" style="color:black;"><b>:</b></label>
														</div>
														<div class="col-sm-7">
															<input type="text" class="form-control" id="agencyPhone" name="agencyPhone" value="">
														</div>
													</div>
												</div>
											</div>
											
											<div class="row" style="height:10px;">
												&nbsp;
											</div>
											

											<div class="row">
												<div class="col-sm-12 text-center">
													<input style="width:70px;" type="submit" class="btn btn-primary btn-xs" id="addBtn"  name="addBtn" value="<?php if(count($rslt_tmpTrkData)==$totTruck){?> Emergency <?php } else{?> Add <?php } ?> " <?php if($emrgncyFlag == 1){?> disabled <?php } ?> >
												</div>
											</div>
										</div>
										
									</form>

									<!-- <hr/> -->
									
									<table id="truckInfoTbl" class="table table-bordered table-responsive table-hover table-striped mb-none" cellspacing="2" cellpadding="2" align="left">
										<input type="hidden" id="vrfyInfoFclId" name="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>" />
																						
										<tr class="gridDark">
											<td rowspan="2" align="center"><b>Serial</b></td>
											<!-- <td rowspan="2" align="center">:</td> -->
											<td rowspan="2" align="center"><nobr><b>Truck Status</b></nobr></td>
											<td rowspan="2" align="center"><nobr><b>Type</b></nobr></td>
											<td colspan="3" align="center"><nobr><b>Driver</b></nobr></td>				
											<td colspan="2" align="center"><nobr><b>Assistant</b></nobr></td>
											<td rowspan="2" align="center"><b>Action</b></td>		
										</tr>
										<tr class="gridDark">
											<td align="center"><nobr><b>Gate Pass</b></nobr></td>
											<td align="center"><nobr><b>Name</b></nobr></td>
											<td align="center"><nobr><b>Truck ID</b></nobr></td>
											
											<td align="center"><nobr><b>Gate Pass</b></nobr></td>
											<td align="center"><nobr><b>Name</b></nobr></td>
										</tr>												
									
										<?php
										if(count($rslt_tmpTrkData)>0)
										{
											for($i=0;$i<count($rslt_tmpTrkData);$i++)
											{
										?>
										<tr>
											<td align="center"><?php echo $i+1; ?></td>
											<!-- <td align="center">:</td>	 -->
											<td align="center">
												<?php
													$btnType = "";
													if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 0)
													{
														if($rslt_tmpTrkData[$i]['paid_status'] == 0)
														{	
															echo "Not Paid";
															$btnType = "Edit";
														}
														else
														{ 
															echo "Paid";
															$btnType = "Replace";
														} 
													}
													else if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 1)
													{
														if($rslt_tmpTrkData[$i]['emrgncy_approve_stat'] == 1)
														{	
															if($rslt_tmpTrkData[$i]['paid_status'] == 0)
															{	
																echo "Not Paid (Approved)";
																$btnType = "Edit";
															}
															else
															{ 
																echo "Paid (Approved)";
																$btnType = "Replace";
															}
														}
														else
														{ 
															echo "Not Approved";
															$btnType = "Edit";
														} 
													}
												?>
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
											<td align="center"><?php echo $rslt_tmpTrkData[$i]['truck_id']; ?></td>
											
											<td align="center"><?php echo $rslt_tmpTrkData[$i]['assistant_gate_pass']; ?></td>
											<td align="center"><?php echo $rslt_tmpTrkData[$i]['assistant_name']; ?></td>
											<td align="center">
												<?php
												// if($rslt_tmpTrkData[$i]['pay']=="no")
												// if($rslt_tmpTrkData[$i]['paid_status']==0)
												// {
												?>
												<!--input style="width:70px;" type="button" class="btn btn-primary btn-xs" id="deleteBtn"  name="deleteBtn" value="Delete" onclick="deleteTableRow(<?php echo $rslt_tmpTrkData[$i]['id']; ?>)"-->
												
												<form method="post" action="<?php echo site_url('ShedBillController/bilSearchByVerifyNumber'); ?>">
													<input type="hidden" id="editId" name="editId" value="<?php echo $rslt_tmpTrkData[$i]['id']; ?>" />
													<input type="hidden" id="btnType" name="btnType" value="<?php echo $btnType;?>" />
													<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
													<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
													<input type="hidden" id="fclFlagValue" name="fclFlagValue" value="<?php echo $fclFlagValue; ?>">
													<input type="hidden" style="width:140px;" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">															
													
													
													<input style="width:70px;" type="submit" class="btn btn-primary btn-xs" id="editBtn" name="editBtn" value="<?php echo $btnType;?>" >
												</form>
												<?php
												// }
												// // else if($rslt_tmpTrkData[$i]['pay']=="yes")
												// else if($rslt_tmpTrkData[$i]['paid_status']==1)
												// {
													// // echo "Payment Done";
													// echo "Replace";
												// }
												// else
												// {
												?>														
												<!--input style="width:70px;" type="button" class="btn btn-primary btn-xs" id="editBtn" name="editBtn" value="Edit" /-->
												<?php
												// }
												?>
											</td>
										</tr>
										<?php
											}
										}
										?>
									</table>

								</div>
							</div>
						</section>
						<!--  Transport Ends  -->
						
						<!--  Jetty Sarkar Starts  -->
						<section class="toggle">
							<label>Jetty Sarkar</label>
							<div class="toggle-content">
								<div class="panel-body">
									<form class="form-horizontal" id="truckDtlForm" name="truckDtlForm" method="post" action="<?php echo site_url('ShedBillController/deliver_2'); ?>" onsubmit="return chkConfirm()">
										<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
										<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
										<input type="hidden" id="fclFlagValue" name="fclFlagValue" value="<?php echo $fclFlagValue; ?>">
										<input type="hidden" id="totTruck" name="totTruck" value="<?php echo $totTruck; ?>">
										<input type="hidden" id="vrfyInfoFclId" name="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>">
										<div class="form-group">
											<label class="col-md-3 control-label">&nbsp;</label>
											<div class="col-md-6">		
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Name <span class="required">*</span></span>													
													<select class="form-control" id="jsName" name="jsName" onchange="getJettySrkrInfo()" required>
														<option value="">-- Select --</option>
														<?php
														for($jt = 0;$jt<count($rslt_jsInfo);$jt++)
														{
														?>
														<option value="<?php echo $rslt_jsInfo[$jt]['id']; ?>" <?php if($jetty_sirkar_id == $rslt_jsInfo[$jt]['id']){?>selected<?php } ?>><?php echo $rslt_jsInfo[$jt]['js_name']; ?></option>
														<?php
														}
														?>
													</select>
												</div>
												
												<div class="input-group mb-md">
													<span class="input-group-addon span_width" >License No <span class="required">*</span></span>
													<input type="text" class="form-control"  id="jsLicenseNo" name="jsLicenseNo" value="<?php if(isset($js_lic_no)) echo $js_lic_no?>" readonly required />
												</div>			
												<div class="input-group mb-md">
													<span class="input-group-addon span_width" >Contact No <span class="required">*</span></span>
													<input type="text" class="form-control" id="jsContact" name="jsContact" value="<?php if(isset($cell_no)) echo $cell_no?>" readonly required />
												</div>													
											</div>
																																		
											<div class="row">
												<div class="col-sm-12 text-center">
													<button type="submit" value="Save" name="deliver" class="btn btn-primary"> Save	</button>	
												</div>
											</div>
										</div>
										</form>
										<?php
											if($jetty_sirkar_id!=null){
										?>
											<div class="panel-body">
												<table class="table table-bordered table-hover table-striped">
													<tr>
														<th class="text-center">Name</th>
														<th class="text-center">License No</th>
														<th class="text-center">Contact No</th>
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
														<td class="text-center"><?php echo $license; ?></td>
														<td class="text-center"><?php echo $contact; ?></td>
													<tr>

												</table>
											</div>
										<?php
											}
										?>
								</div>
							</div>
						</section>
						<!--  Jetty Sarkar Ends  -->

					</div>
				</div>
			</div>
		
			<!--div class="img">
				
				<form name= "deliverForm"  onsubmit="return(validate());" action="<?php echo site_url('ShedBillController/deliver_2');?>"  method="POST" onload="" >
					<input type="hidden" style="width:140px;" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
					<input type="hidden" style="width:140px;" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>"> 
					<input type="hidden" style="width:140px;" id="verifyNo" name="verifyNo" value="<?php echo $rtnVerifyReport[0]['verify_number']?>"> 
					<input type="hidden" style="width:140px;" id="fclFlagValue" name="fclFlagValue" value="<?php echo $fclFlagValue; ?>">
					<?php 
						if($tblFlag==1) 
						{	
					?>
				  
							<?php  
							if($doShowFlag==1) 
							{
							?>
							<div class="col-md-6">
								<table class="table table-bordered table-responsive table-hover table-striped mb-none" cellspacing="2" cellpadding="2" align="left"  id="mytbl" style="margin-left:20px;">												
								</table>
							<?php		 
							}
							?>
					<?php 
						}
					?>
							</div>
		
				</form>
	 
			</div-->
			<?php 
			// }
			?>	
      
      </div>
      
      <div class="clr"></div>
    </div>

  </div>
  
</section>
