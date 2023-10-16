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
				var licNo = "";
				var truckNumber = "";
				
				if(jsonData.rslt_driverInfo.length>0)
				{
					driverAssistName = jsonData.rslt_driverInfo[0].driver_assist_name;
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
				var licNo = "";
				var truckNumber = "";
				
				if(jsonData.rslt_driverInfo.length>0)
				{
					driverAssistName = jsonData.rslt_driverInfo[0].driver_assist_name;
					licNo = jsonData.rslt_driverInfo[0].lic_no;
					// truckNumber = jsonData.rslt_driverInfo[0].truck_number;
				}	
				else
				{
					alert("Please Assign a helper!");
					document.getElementById('assistantPassNo').value = "";
				}				
			
				document.getElementById('assistantName').value = driverAssistName;
				document.getElementById('helperLbl').innerHTML = driverAssistName;
				//document.getElementById('assistantTruckNo').value = truckNumber;
				//document.getElementById('assistantLicNo').value = licNo;
			}
		};
		
		var url = "<?php echo site_url('AjaxController/getAssistantInfo')?>?driverPassNo="+assistantPassNo;		
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}
	
	var trkSl = 0;
	function addTruckToTmp()
	{								
		var emrgncyTrk = document.getElementById('addBtn').value;
		alert(emrgncyTrk);
		// return false;
		var vrfyInfoFclId = document.getElementById('vrfyInfoFclId').value;
		
		var truckNo = document.getElementById('truck').value;
		//var pkQty = document.getElementById('pkQty').value;
		//var gateNo = document.getElementById('gateNo').value;
		var driverName = document.getElementById('driverName').value;
		var driverPassNo = document.getElementById('driverPassNo').value;
		var assistantName = document.getElementById('assistantName').value;
		var assistantPassNo = document.getElementById('assistantPassNo').value;
		
		if (window.XMLHttpRequest) 
		{
			xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange=function(){	
			// alert("alert");		
			var val = xmlhttp.responseText;
			// alert(val);	
			var jsonData = JSON.parse(val);
			// alert("jsonData.stat "+jsonData.stat);
			if(jsonData.stat == true)
			{				
				document.getElementById('truck').value = "";
				//document.getElementById('pkQty').value = "";
				//document.getElementById('gateNo').value = "";
				document.getElementById('driverName').value = "";
				document.getElementById('driverPassNo').value = "";
				document.getElementById('assistantName').value = "";
				document.getElementById('assistantPassNo').value = "";	
				clearTableRow();
				AddTableRow(jsonData);				
			}				
		};
		
		var url = "<?php echo site_url('ajaxController/addTruckToTmp')?>?vrfyInfoFclId="+vrfyInfoFclId+"&truckNo="+truckNo+"&driverName="+driverName+"&driverPassNo="+driverPassNo+"&assistantName="+assistantName+"&assistantPassNo="+assistantPassNo+"&emrgncyTrk="+emrgncyTrk;
		// alert(url);
		// return false;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}
	
	function clearTableRow()
	{
		var table = document.getElementById("truckInfoTbl");
		var tblLen = table.rows.length;
		
		for(var i=tblLen-1;i>2;i--)
		{
			document.getElementById("truckInfoTbl").deleteRow(i);
		}
	}
	
	function deleteTableRow(id)
	{
		// alert(id);
		if(confirm("Delete this entry?"))
		{
			var vrfyInfoFclId = document.getElementById('vrfyInfoFclId').value;
			// alert("vrfyInfoFclId "+vrfyInfoFclId);
			if (window.XMLHttpRequest) 
			{
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange=function(){
				var val = xmlhttp.responseText;
				var jsonData = JSON.parse(val);
			
				if(jsonData.stat == true)
				{
					clearTableRow();
					AddTableRow(jsonData);
					
					
				}
				else
				{
					alert("Delete is not successful");
				}
			};
			
			var url = "<?php echo site_url('AjaxController/deleteTmpData')?>?id="+id+"&vrfyInfoFclId="+vrfyInfoFclId;	
				
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}	
	}
	
	function AddTableRow(jsonData)
	{		
		// if(jsonData.rslt_tmpRsltSet.length == document.getElementById('totTruck').value)
		// {
			// // alert("none");
			// document.getElementById('truckAddRow').style.display = "none"; 
		// }
		// else
		// {
			// // alert("block");
			// document.getElementById('truckAddRow').style.display = "table-row";
		// }
		
		if(jsonData.rslt_tmpRsltSet.length == document.getElementById('totTruck').value)
		{
			if(document.getElementById('addBtn').value == "Emergency")
				document.getElementById('addBtn').value= "Add";
			else
				document.getElementById('addBtn').value= "Emergency";
			
			alert("add after change : "+document.getElementById('addBtn').value);
			document.getElementById('truckAddRow').style.display = "table-row";
		}
		else if(jsonData.rslt_tmpRsltSet.length == (parseInt(document.getElementById('totTruck').value)+1))
		{
			// alert("block");
			document.getElementById('truckAddRow').style.display = "none"; 
		}	

		var trkSl = 0;
		document.getElementById("paymentAmt").value = 0;
		for(var j=0;j<jsonData.rslt_tmpRsltSet.length;j++)
		{
			trkSl = trkSl+1;
			var visitId = jsonData.rslt_tmpRsltSet[j].id;
			// alert("visitId : "+visitId);
			var truckNo = jsonData.rslt_tmpRsltSet[j].truck_id;
			// var pkQty = jsonData.rslt_tmpRsltSet[j].pkg_qty;
			// var gateNo = jsonData.rslt_tmpRsltSet[j].gate_no;
			var driverName = jsonData.rslt_tmpRsltSet[j].driver_name;
			var driverPassNo = jsonData.rslt_tmpRsltSet[j].driver_gate_pass;
			var assistantName = jsonData.rslt_tmpRsltSet[j].assistant_name;
			var assistantPassNo = jsonData.rslt_tmpRsltSet[j].assistant_gate_pass;
			
			var payment = jsonData.rslt_tmpRsltSet[j].pay;
			
			if(payment == "no")
			{
				var preamt = parseFloat(document.getElementById("paymentAmt").value);
				var curamt = preamt + 57.50;
				
				document.getElementById("paymentAmt").value=curamt;
			}
			
			var table = document.getElementById("truckInfoTbl");
			var tr = document.createElement('tr');
			
			var td1 = document.createElement('td');
			var td2 = document.createElement('td');
			// var td3 = document.createElement('td');
			// var td4 = document.createElement('td');
			var td5 = document.createElement('td');
			var td6 = document.createElement('td');
			var td7 = document.createElement('td');
			var td8 = document.createElement('td');
			var td9 = document.createElement('td');
			var td10 = document.createElement('td');
			
			td1.style.textAlign = "center";
			td2.style.textAlign = "center";
			// td3.style.textAlign = "center";
			// td4.style.textAlign = "center";
			td5.style.textAlign = "center";
			td6.style.textAlign = "center";
			td7.style.textAlign = "center";
			td8.style.textAlign = "center";
			td9.style.textAlign = "center";
			td10.style.textAlign = "center";
			
			td1.innerHTML = trkSl;
			td2.innerHTML = ":";
			// td3.innerHTML = pkQty;
			// td4.innerHTML = gateNo;
			td5.innerHTML = driverPassNo;
			td6.innerHTML = driverName;
			td7.innerHTML = truckNo;
			td8.innerHTML = assistantPassNo;
			td9.innerHTML = assistantName;		
			
			if(payment == "no")
			{
				var input = document.createElement("input");
				input.type = "button";			
				input.id = "deleteBtn_"+(j+1);
				input.name = "deleteBtn_"+(j+1);			
				input.setAttribute('onclick', 'deleteTableRow('+visitId+')');					
				input.value="DELETE";			
				input.classList.add("btn");
				input.classList.add("btn-primary");
				input.classList.add("btn-xs");
				input.style.width = "70px";
				td10.appendChild(input);									
			}
			else
			{
				// td10.innerHTML = "Payment Done";
				td10.innerHTML = "Replace";
			}
			
			tr.appendChild(td1);
			tr.appendChild(td2);
			// tr.appendChild(td3);
			// tr.appendChild(td4);
			tr.appendChild(td5);
			tr.appendChild(td6);
			tr.appendChild(td7);
			tr.appendChild(td8);
			tr.appendChild(td9);
			tr.appendChild(td10);
			
			table.appendChild(tr);
			
			
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
				<div class="col-md-7">
					<section class="panel form-wizard" id="w4">
						<header class="panel-heading">
							<!--div class="panel-actions">
								<a href="#" class="fa fa-caret-down"></a>
								<a href="#" class="fa fa-times"></a>
							</div-->
			
							<h2 class="panel-title">Container Info</h2>
						</header>
						<section class="panel">
							<div class="panel-body">
								<div class="col-md-12"> 
									<div class="row">
										<div class="col-md-3">
										Vessel Name:
										</div>
										<div class="col-md-3">
										<?php echo $rtnVerifyReport[0]['vessel_name']?>
										</div>
										<div class="col-md-3">
										Rotation:
										</div>
										<div class="col-md-3">
										<?php echo $rtnVerifyReport[0]['import_rotation']?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
										B/L No:
										</div>
										<div class="col-md-3">
										<?php echo $rtnVerifyReport[0]['bl_no']?>
										</div>
										<div class="col-md-3">
										IGM Qty:
										</div>
										<div class="col-md-3">
										<?php echo $rtnVerifyReport[0]['Pack_Number']?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
										IGM Unit:
										</div>
										<div class="col-md-3">
										<?php echo $rtnVerifyReport[0]['Pack_Description']?>
										</div>
										<div class="col-md-3">
										Container:
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
										<div class="col-md-3">
										Position:
										</div>
										<div class="col-md-3">
										<?php if(isset($rslt_posYardBlock[0]['currentPos']))echo $rslt_posYardBlock[0]['currentPos']?>
										</div>
										<div class="col-md-3">
										Yard:
										</div>
										<div class="col-md-3">
										<?php if(isset($rslt_posYardBlock[0]['Yard_No']))echo $rslt_posYardBlock[0]['Yard_No']?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
										Block:
										</div>
										<div class="col-md-3">
										<?php if(isset($rslt_posYardBlock[0]['Block_No']))echo $rslt_posYardBlock[0]['Block_No']?>
										</div>
										<div class="col-md-3">
										Verify No:
										</div>
										<div class="col-md-3">
										<?php echo $rtnVerifyReport[0]['verify_number']?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
										Goods Description:
										</div>
										<div class="col-md-9">
										<?php echo $rtnVerifyReport[0]['Description_of_Goods']?>
										</div>										
									</div>
									<div class="row">
										<div class="col-md-3">
										Number of Truck:
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
				<div class="col-md-5">
				<section class="panel">
					<header class="panel-heading">
						<h2 class="panel-title">Transport Info</h2>						
					</header>
					<form method="post" action="<?php echo site_url('ShedBillController/addTruckToTmp'); ?>">
						<input type="hidden" id="vrfyInfoFclId" name="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>" />
						<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
						<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
						<input type="hidden" id="fclFlagValue" name="fclFlagValue" value="<?php echo $fclFlagValue; ?>">
						<input type="hidden" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">

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
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">Driver Pass</label>
										<input type="text" name="driverPassNo" id="driverPassNo" onblur="getDriverInfo()" class="form-control" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['driver_gate_pass']; ?>">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">Deiver Name:</label>
										<label class="control-label" id="driverLbl"><?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['driver_name'] ?></label>
										<input type="hidden" name="driverName" id="driverName" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['driver_name'] ?>" class="form-control" >
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">Helper Pass</label>
										<input type="text" name="assistantPassNo" id="assistantPassNo" onblur="getAssistantInfo()" class="form-control" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['assistant_gate_pass'] ?>">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">Helper Name:</label>
										<label class="control-label" id="helperLbl"><?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['assistant_name'] ?></label>
										<input type="hidden" name="assistantName" id="assistantName" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['assistant_name'] ?>" class="form-control">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">Truck ID</label>									
										<input style="width:150px;" type="text" id="truckNo" name="truckNo" class="form-control" value="<?php if(count($rslt_trkEditInfo))echo $rslt_trkEditInfo[0]['truck_id'] ?>">
									</div>
								</div>
								
							</div>
						</div>
						<footer class="panel-footer">
							
							<input style="width:70px;" type="submit" class="btn btn-primary btn-xs" id="addBtn"  name="addBtn" value="<?php if(count($rslt_tmpTrkData)==$totTruck){?> Emergency <?php } else{?> Add <?php } ?> " <?php if($emrgncyFlag == 1){?> disabled <?php } ?> >
						</footer>
					</form>
					
				</section>
			</div>
			</div>
			<!-- common information end -->
				

			<!-- jetty sarkar, driver, helper form start -->
			<div class="row">
				<div class="col-xs-12">
					<section class="panel form-wizard" id="w4">
						<header class="panel-heading">
							<div class="panel-actions">
								<a href="#" class="fa fa-caret-down"></a>
								<a href="#" class="fa fa-times"></a>
							</div>
			
							<h2 class="panel-title">Form Wizard</h2>
						</header>
						<div class="panel-body">
							<div class="wizard-progress wizard-progress-lg">
								<div class="steps-progress">
									<div class="progress-indicator"></div>
								</div>
								<ul class="wizard-steps">
									<li class="active">
										<a href="#w4-transport" data-toggle="tab"><span>1</span>Transport</a>
									</li>
									<li>
										<a href="#w4-js" data-toggle="tab"><span>2</span>Jetty Sarkar</a>
									</li>	
									<li>
										<a href="#w4-confirm" data-toggle="tab"><span>3</span>Confirm</a>
									</li>									
									<li>
										<a href="#w4-payment" data-toggle="tab"><span>4</span>Payment</a>
									</li>									
								</ul>
							</div>
			
							<!--form class="form-horizontal" id="truckDtlForm" name="truckDtlForm" method="post" action="<?php echo site_url('ShedBillController/deliver_2'); ?>" onsubmit="return chkConfirm()">
							
								<input type="hidden" style="width:140px;" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
								<input type="hidden" style="width:140px;" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
								<input type="hidden" style="width:140px;" id="fclFlagValue" name="fclFlagValue" value="<?php echo $fclFlagValue; ?>">
								
								
								<input type="hidden" style="width:140px;" id="totTruck" name="totTruck" value="<?php echo $totTruck; ?>"-->
								
								<?php 
								// if(count($rslt_tmpTrkData)==$totTruck)
								// {
								?>
								<!--input type="text" style="width:140px;" id="emrgncyTrk" name="emrgncyTrk" value="emrgncyTrk"-->
								<?php
								// }
								?>
								<div class="tab-content">
									
									<!-- Driver -->
									<div id="w4-transport" class="tab-pane active">
										<div class="form-group">
											<table id="truckInfoTbl" class="table table-bordered table-responsive table-hover table-striped mb-none" cellspacing="2" cellpadding="2" align="left">
												<input type="hidden" id="vrfyInfoFclId" name="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>" />
																								
												<tr class="gridDark">
													<td rowspan="2" align="center"><b>Serial</b></td>
													<td rowspan="2" align="center">:</td>
													<td colspan="5" align="center"><nobr><b>Driver</b></nobr></td>				
													<td colspan="2" align="center"><nobr><b>Assistant</b></nobr></td>
													<td rowspan="2" align="center"><b>Action</b></td>		
												</tr>
												<tr class="gridDark">
													<td align="center"><nobr><b>Gate Pass</b></nobr></td>
													<td align="center"><nobr><b>Name</b></nobr></td>
													<td align="center"><nobr><b>Truck ID</b></nobr></td>
													<td align="center"><nobr><b>Truck Status</b></nobr></td>
													<td align="center"><nobr><b>Type</b></nobr></td>
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
													<td align="center">:</td>													
													<td align="center"><?php echo $rslt_tmpTrkData[$i]['driver_gate_pass']; ?></td>
													<td align="center"><?php echo $rslt_tmpTrkData[$i]['driver_name']; ?></td>
													<td align="center"><?php echo $rslt_tmpTrkData[$i]['truck_id']; ?></td>
													<td align="center">
														<?php
															if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 0)
															{
																if($rslt_tmpTrkData[$i]['paid_status'] == 0)
																{	
																	echo "Not Paid";
																}
																else
																{ 
																	echo "Paid";
																} 
															}
															else if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 1)
															{
																if($rslt_tmpTrkData[$i]['emrgncy_approve_stat'] == 1)
																{	
																	if($rslt_tmpTrkData[$i]['paid_status'] == 0)
																	{	
																		echo "Not Paid";
																	}
																	else
																	{ 
																		echo "Paid";
																	}
																}
																else
																{ 
																	echo "Not Approved";
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
															<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
															<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
															<input type="hidden" id="fclFlagValue" name="fclFlagValue" value="<?php echo $fclFlagValue; ?>">
															<input type="hidden" style="width:140px;" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">															
															
															<input style="width:70px;" type="submit" class="btn btn-primary btn-xs" id="editBtn" name="editBtn" value="Edit" >
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
									
									
									<!-- Jetty Sarkar -->
									<div id="w4-js" class="tab-pane">
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
													<span class="input-group-addon span_width" style="width:250px;">Name <span class="required">*</span></span>													
													<select class="form-control" id="jsName" name="jsName" onchange="getJettySrkrInfo()">
														<option value="">-- Select --</option>
														<?php
														for($jt = 0;$jt<count($rslt_jsInfo);$jt++)
														{
														?>
														<option value="<?php echo $rslt_jsInfo[$jt]['id']; ?>"><?php echo $rslt_jsInfo[$jt]['js_name']; ?></option>
														<?php
														}
														?>
													</select>
												</div>
												
												<div class="input-group mb-md">
													<span class="input-group-addon span_width" style="width:250px;">License No <span class="required">*</span></span>
													<input type="text" style="width:150px;"  id="jsLicenseNo" name="jsLicenseNo" value="" readonly required />
												</div>			
												<div class="input-group mb-md">
													<span class="input-group-addon span_width" style="width:250px;">Contact No <span class="required">*</span></span>
													<input type="text" style="width:150px;" id="jsContact" name="jsContact" value="" readonly required />
												</div>		
												<!--div class="input-group mb-md">
													<span class="input-group-addon span_width" style="width:250px;">Address <span class="required">*</span></span>
													<input type="text" style="width:150px;" id="jsAddress" name="jsAddress" value="" readonly required />
												</div-->		
											</div>
																																		
											<div class="row">
												<div class="col-sm-12 text-center">
													
												</div>
											</div>
										</div>	
									</div>
									
									<!-- Confirm -->
									<div id="w4-confirm" class="tab-pane">										
										<div class="form-group">
											<label class="col-md-3 control-label">&nbsp;</label>
											<div class="col-md-6">		
												<div class="input-group mb-md">
													<?php
													if($emrgncyFlag == 1)
													{
													?>
													<button type="submit" value="Save" name="deliver" class="btn btn-primary"> Save	</button>
													
													<?php
													}
													else if($cntPaid==$noOfTruckAssign)
													{
													?>
													<font color='green'><b>All Truck Assigned</b></font>
													<?php
													}
													else
													{
													?>
													<button type="submit" value="Save" name="deliver" class="btn btn-primary"> Save	</button>
													<?php
													}
													?>
												</div>
											</div>
										</div>											
									</div>
									</form>
									<!-- Payment -->
									<div id="w4-payment" class="tab-pane">
										<form method="post" action="<?php echo site_url('ShedBillController/truckPayment'); ?>">
										<input type="hidden" id="vrfyInfoFclId" name="vrfyInfoFclId" value="<?php echo $vrfyInfoFclId; ?>" />
										<input type="hidden" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
										<input type="hidden" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>">
										<input type="hidden" id="fclFlagValue" name="fclFlagValue" value="<?php echo $fclFlagValue; ?>">
										<input type="hidden" id="cont_status" name="cont_status" value="<?php echo $cont_status; ?>">
										<div class="form-group">
											<label class="col-md-3 control-label">&nbsp;</label>
											<div class="col-md-6">		
												<div class="input-group mb-md">
													<span class="input-group-addon span_width" style="width:250px;">Payment Method <span class="required">*</span></span>													
													<select class="form-control" id="paymentMethod" name="paymentMethod">
														<option value="">-- Select --</option>
														<option value="cash">Cash</option>
														<option value="visa">Visa Card</option>
														<option value="master">Master Card</option>
													</select>
												</div>
																								
												<div class="input-group mb-md">
													<span class="input-group-addon span_width" style="width:250px;">Amount <span class="required">*</span></span>		
													<input style="width:130px;" type="text" id="paymentAmt" name="paymentAmt" value="<?php echo $paymentAmt; ?>">
												</div>		
											</div>
																																		
											<div class="row">
												<div class="col-sm-12 text-center">
													<input type="submit" value = "Pay" class="btn btn-primary" />
												</div>
											</div>
										</div>	
										</form>
									</div>
									
																											
								</div>
							<!--/form-->
						</div>
						<div class="panel-footer">
							<ul class="pager">
								<li class="previous disabled">
									<a><i class="fa fa-angle-left"></i> Previous</a>
								</li>
								<li class="finish hidden pull-right">
									<a>Finish</a>
								</li>
								<li class="next">
									<a>Next <i class="fa fa-angle-right"></i></a>
								</li>
							</ul>
						</div>
					</section>
				</div>
			</div>
			<!-- jetty sarkar, driver, helper form end -->
			
		
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
