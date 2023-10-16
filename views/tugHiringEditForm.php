<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">						
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
							
							<div class="container">
								<div class="modal fade" id="myModal">
									<div class="modal-dialog modal-block-sm">
										<div class="modal-content">
											<!-- Modal Header -->
											<div class="modal-header">
												<h4 class="modal-title">Update Tug Hire Timing</h4>
												<button type="button" class="close" data-dismiss="modal">&times;</button>
											</div>

											<div class="modal-body">
												<input type="hidden" name="row_num" id="row_num">
												<input type="hidden" name="tug_hire_timing_id" id="tug_hire_timing_id">
												<input type="hidden" name="vehicle_type_prev" id="vehicle_type_prev">
												<input type="hidden" name="vehicle_name_prev" id="vehicle_name_prev">
												<input type="hidden" name="water_supply_prev" id="water_supply_prev">
												<input type="hidden" name="from_date_prev" id="from_date_prev">
												<input type="hidden" name="from_time_prev" id="from_time_prev">
												<input type="hidden" name="to_date_prev" id="to_date_prev">
												<input type="hidden" name="to_time_prev" id="to_time_prev">
												<input type="hidden" name="vessel_name_prev" id="vessel_name_prev">
												<input type="hidden" name="call_number_prev" id="call_number_prev">
												<input type="hidden" name="hours_prev" id="hours_prev">
												<div class="form-group">
													<label class="col-md-2 control-label">&nbsp;</label>
													<div class="col-md-8">
														<div class="input-group mb-md">
															<span class="input-group-addon span_width">Burge/Tug Type</span>
															<select class="form-control" name="edit_vehicle_type" id="edit_vehicle_type" 
																onchange="getValueForEditingData(this.value)">
																	<option value="">--Select--</option>
																	<option value="Tug">Tug</option>
																	<option value="Burge">Burge</option>
															</select>
														</div>
														<div class="input-group mb-md">
															<span class="input-group-addon span_width">Burge/Tug</span>
															<select class="form-control" name="edit_vehicle_name" id="edit_vehicle_name">
																<option value="">--Select--</option>
															</select>
														</div>
														<div class="input-group mb-md">
															<span class="input-group-addon span_width">Water Supply</span>
															<input type="text" class="form-control" name="edit_water_supply" 
															id="edit_water_supply" >
														</div>
														<div class="input-group mb-md">
															<span class="input-group-addon span_width">From Date</span>
															<input type="date" class="form-control" name="edit_from_date" 
															id="edit_from_date" >
														</div>
														<div class="input-group mb-md">
															<span class="input-group-addon span_width">From Time</span>
															<input type="time" class="form-control" name="edit_from_time" 
															id="edit_from_time" >
														</div>
														<div class="input-group mb-md">
															<span class="input-group-addon span_width">To Date</span>
															<input type="date" class="form-control" name="edit_to_date" 
																id="edit_to_date" >
														</div>
														<div class="input-group mb-md">
															<span class="input-group-addon span_width">To Time</span>
															<input type="time" class="form-control" name="edit_to_time" 
															id="edit_to_time" >
														</div>
													</div>
													<div class="row">
														<div class="col-sm-12 text-center">
															<button 
																type="submit" 
																class="mb-xs mt-xs mr-xs btn btn-success"
																onclick="editTiming()"
																data-dismiss="modal"
															>
																Update
															</button>
														</div>
													</div>
													<div class="row">
														<div class="col-sm-12 text-center">
															
														</div>
													</div>
												</div>												
											</div>
											<!-- Modal footer -->
											<div class="modal-footer">
												<button type="button" class="btn btn-danger" data-dismiss="modal">
													Close
												</button>
											</div>

										</div>
									</div>
								</div>
							</div>
							
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Vessel/tugHiringEdit') ?>"
								onsubmit="return chkConfirm();">
								<input type="hidden" name="ivalue" id="ivalue" value="0">
								<input type="hidden" name="initialRows" id="initialRows" value="<?php echo count($tugHiringList)+3?>">
								<input type="hidden" name="tug_hire_id" id="tug_hire_id" value="<?php echo $tug_hire_id; ?>">
								<input type="hidden" name="vessel_name_prev" id="vessel_name_prev" value="<?php echo $vessel_name; ?>">
								<input type="hidden" name="call_number_prev" id="call_number_prev" value="<?php echo $call_number; ?>">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
										</div>
									</div>
									<div class="col-md-6">
										<!--div class="input-group mb-md">
											<span class="input-group-addon span_width">Burge/Tug Type </span>
											<select class="form-control" name="vehicle_type" id="vehicle_type" 
											onchange="getValue(this.value)" required>
												<option value="">--Select--</option>
												<option value="Tug">Tug</option>
												<option value="Burge">Burge</option>
											</select>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Burge/Tug</span>
											<select class="form-control" name="vehicle_name" id="vehicle_name" required>
												<option value="">--Select--</option>
											</select>
										</div-->
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Location</span>
											<select class="form-control" name="location" id="location" 
												onchange="changeAccessibility(this.value)" required>
												<option value="">--Select--</option>
												<option value="inside" <?php if($location=="inside") echo "selected"; ?>>
													Inside Port
												</option>
												<option value="outside" <?php if($location=="outside") echo "selected"; ?>>
													Outside Port
												</option>
											</select>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation</span>
											<input class="form-control" name="rotation" id="rotation" 
												value="<?php echo $rotation; ?>" required onblur="getVesselName()">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Vessel Name</span>
											<input class="form-control" list="vesselNameList" name="vessel_name" id="vessel_name" 
												value="<?php echo $vessel_name; ?>" required>
											<datalist id="vesselNameList">												
												<?php for($i = 0; $i<count($vesselNameList);$i++) { ?>
													<option value="<?php echo $vesselNameList[$i]["vessel_name"]?>">
												<?php } ?>										
											</datalist>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Call Number</span>
											<input type="number" class="form-control" name="call_number" id="call_number" 
												value="<?php echo $call_number; ?>" required>
										</div>	
									</div>
									<div class="col-md-6">	
										<div class="input-group mb-md">
											
												<span class="input-group-addon span_width">Shipping Agent</span>
												<select data-plugin-selectTwo class="form-control" 
													name="shipping_agent" id="shipping_agent" required>
													<option value="">--Select--</option>
													<?php for($i=0;$i<count($shippingAgentList);$i++) { ?>
													<option value="<?php echo $shippingAgentList[$i]['id']?>"
														<?php if($shippingAgentList[$i]['id'] == $shipping_agent_id) echo "selected";?>
													>
														<?php echo $shippingAgentList[$i]['Organization_Name'];?>
													</option>
													<?php } ?>
												</select>
											
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Description</span>
											<textarea name="description" id="description" rows="5" class="form-control"><?php echo $description; ?></textarea>
										</div>
									</div>
								</div>									
								<div class="row">
									<div class="table-responsive" style="margin-top: 20px; margin-bottom: 20px">
										<table class="table table-responsive table-bordered" id="item_table">
											<thead>
												<tr>
													<th style="text-align: center">Burge/Tug Type</th>
													<th style="text-align: center">Burge/Tug</th>
													<th style="text-align: center">Water Supply</th>
													<th style="text-align: center">From Date</th>
													<th style="text-align: center">From Time</th>
													<th style="text-align: center">To Date</th>
													<th style="text-align: center">To Time</th>		
													<th style="text-align: center">Hours</th>		
													<th style="text-align: center"></th>		
												</tr>
												<tr>
													<th style="text-align: center">
														<select class="form-control" name="vehicle_type" id="vehicle_type" 
														onchange="getValue(this.value)">
															<option value="">--Select--</option>
															<option value="Tug">Tug</option>
															<option value="Burge">Burge</option>
															<option value="Salvage">Salvage</option>
														</select>
													</th>
													<th style="text-align: center">
														<select class="form-control" name="vehicle_name" id="vehicle_name">
															<option value="">--Select--</option>
														</select>
													</th>
													<th style="text-align: center">
														<input type="text" class="form-control" name="water_supply" id="water_supply" disabled>
													</th>
													<th style="text-align: center">
														<input type="date" class="form-control" name="from_date" id="from_date">
													</th>
													<th style="text-align: center">
														<input type="time" class="form-control" name="from_time" id="from_time">
													</th>
													<th style="text-align: center">
														<input type="date" class="form-control" name="to_date" id="to_date">
													</th>
													<th style="text-align: center">
														<input type="time" class="form-control" name="to_time" id="to_time">
													</th>		
													<th style="text-align: center"></th>		
													<th style="text-align: center">
														<button type="button" onclick="addRow()" class="mb-xs mt-xs mr-xs btn btn-success">
															<span class="glyphicon glyphicon-plus"></span>
														</button>
													</th>		
												</tr>
											</thead>
											<?php for($i=0;$i<count($tugHiringList);$i++) { ?>
											<tr>
												<td align="center"><?php echo $tugHiringList[$i]['vehicle_type'];?></td>
												<td align="center"><?php echo $tugHiringList[$i]['vehicle_name'];?></td>
												<td align="center"><?php echo $tugHiringList[$i]['water_supply'];?></td>
												<td align="center">
													<?php echo date('d/m/Y', strtotime($tugHiringList[$i]['from_date']));?>
												</td>
												<td align="center"><?php echo $tugHiringList[$i]['from_time'];?></td>
												<td align="center"><?php echo date('d/m/Y', strtotime($tugHiringList[$i]['to_date']));?></td>
												<td align="center"><?php echo $tugHiringList[$i]['to_time'];?></td>
												<td align="center"><?php echo $tugHiringList[$i]['hours'];?></td>
												<td align="center">
													<button type="button" 
														onclick="setTugHireInfo(
															<?php echo $i+2 ?>,
															<?php echo $tugHiringList[$i]['tug_hire_timing_id']?>,
															'<?php echo $tugHiringList[$i]['vessel_name']?>',
															'<?php echo $tugHiringList[$i]['call_number']?>',
														)" 
														class="mb-xs mt-xs mr-xs btn btn-primary"
														data-toggle="modal" data-target="#myModal"
													>
														<span class="glyphicon glyphicon-edit"></span>
													</button>
													
													<button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" 
															onclick="removeTiming(
																	this,
																	<?php echo $i+2 ?>,
																	'<?php echo $tugHiringList[$i]['vessel_name']?>',
																	'<?php echo $tugHiringList[$i]['call_number']?>'
																)">
														<span class="glyphicon glyphicon-trash"></span>
													</button>
												</td>
											</tr>
											<?php } ?>
										</table>
									</div>
								</div>
								<div class="row" style="border-bottom: 1px solid silver; margin-top: 20px;padding-bottom: 20px">
									<div class="col-md-4">
										
									</div>
									<div class="col-md-4">
										
									</div>
									<div class="col-md-4">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Total Hours</span>
											<input type="text" name="total_hours" id="total_hours" 
												value="<?php echo $hours; ?>" class="form-control" readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
										<button type="submit" name="btnSub" id="btnSub" class="mb-xs mt-xs mr-xs btn btn-primary">
											SAVE
										</button>
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
	
<script>
	var ival = 0;
	function getValue(type)
	{
		document.getElementById("from_date").value="";
		document.getElementById("from_time").value="";
		document.getElementById("to_date").value="";
		document.getElementById("to_time").value="";
			
		if(type == "Tug")
		{
			var selectList=document.getElementById("vehicle_name");
			removeOptions(selectList);			  
			for (var i = 1; i <= 12; i++) 
			{
				var option = document.createElement('option');
				option.value = "K-"+i;
				option.text = "K-"+i;
				selectList.appendChild(option);
			}
			document.getElementById("water_supply").disabled = false;
			document.getElementById("water_supply").value = 0;
			document.getElementById("water_supply").disabled = true;
		}
		else if(type == "Burge")
		{
			var selectList=document.getElementById("vehicle_name");
			removeOptions(selectList);		
			const burges = ["MOSHAK", "JHARNA", "FOUARA", "JALPARI"]; 
			for (var i = 0; i < burges.length; i++) 
			{
				var option = document.createElement('option');
				option.value = burges[i];
				option.text = burges[i];
				selectList.appendChild(option);
			}
			document.getElementById("water_supply").disabled = false;
		}
		else if(type == "Salvage")
		{
			var selectList=document.getElementById("vehicle_name");
			removeOptions(selectList);		
			const salvagingVsls = ["B.L.V Lusai", "B.L.V Ali"]; 
			for (var i = 0; i < salvagingVsls.length; i++) 
			{
				var option = document.createElement('option');
				option.value = salvagingVsls[i];
				option.text = salvagingVsls[i];
				selectList.appendChild(option);
			}
			document.getElementById("water_supply").disabled = false;
		}
		else
		{
			var selectList=document.getElementById("vehicle_name");
			removeOptions(selectList);
			document.getElementById("water_supply").disabled = false;
			document.getElementById("water_supply").value = 0;
			document.getElementById("water_supply").disabled = true;
		}
			
	}
		
	function removeOptions(selectbox)
	{
		var i;
		for(i=selectbox.options.length-1;i>=1;i--)
		{
			//selectbox.remove(i);
			selectbox.children[i].remove();
		}
	}
	
	function addRow()
	{	
		if (confirm("Do you want to add ?") == true)
		{
			ival++;
			document.getElementById("ivalue").value=ival;
			
			var location = document.getElementById("location").value;
			var vehicleType = document.getElementById("vehicle_type").value;
			var vehicleName = document.getElementById("vehicle_name").value;
			var vesselName = document.getElementById("vessel_name").value;
			var callNumber = document.getElementById("call_number").value;	
			var fromDate = document.getElementById("from_date").value;
			var fromTime = document.getElementById("from_time").value;
			var toDate = document.getElementById("to_date").value;
			var toTime = document.getElementById("to_time").value;
			var shippingAgent = document.getElementById("shipping_agent").value;
			var waterSupply = 0;
			if(vehicleType == "Burge"){
				waterSupply = document.getElementById("water_supply").value;
			}
			
			
			if(vehicleType == "Burge" && waterSupply < 0)
			{
				alert("Water supply can not be less than 0");
			}
			else if(location == "" || location == null)
			{
				alert("Please Select Location");
			}
			else if(vehicleType == "" || vehicleType == null)
			{
				alert("Please Enter Burge/Tug Type");
			}
			else if(vehicleName == "" || vehicleName == null)
			{
				alert("Please Enter Burge/Tug Name");
			}
			else if(vesselName == "" || vesselName == null)
			{
				alert("Please Enter Vessel Name");
			}
			else if(callNumber == "" || callNumber == null)
			{
				alert("Please Enter Call Number");
			}
			else if(fromDate == "" || fromDate == null)
			{
				alert("Please Enter Starting Date");
			}
			else if(fromTime == "" || fromTime == null)
			{
				alert("Please Enter Starting Time");
			}
			else if(toDate == "" || toDate == null)
			{
				alert("Please Enter Ending Date");
			}
			else if(toTime == "" || toTime == null)
			{
				alert("Please Enter Ending Time");
			}
			else if(shippingAgent == "" || shippingAgent == null)
			{
				alert("Please Select Shipping Agent");
			}
			else
			{
				//calculating hours starts
				var starting = fromDate+" "+fromTime;
				var ending = toDate+" "+toTime;			
				startingDateTime = new Date(starting);
				endingDateTime = new Date(ending);
				var diff =(endingDateTime.getTime() - startingDateTime.getTime()) / 1000;
				diff /= (60 * 60);
				var totalHours = Math.abs(Math.ceil(diff));
				//calculating hours ends
				
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				} 
				else 
				{  
					// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}		
				var url = "<?php echo site_url('Vessel/insertTempTugHiringDataForEditing');?>?fromDate="+fromDate+"&fromTime="+fromTime+"&toDate="+toDate+"&toTime="+toTime+"&totalHours="+totalHours+"&vehicleType="+vehicleType+"&vehicleName="+vehicleName+"&vesselName="+vesselName+"&callNumber="+callNumber+"&waterSupply="+waterSupply;
				//alert(url);
				xmlhttp.onreadystatechange=stateChangePopulateTable;
				xmlhttp.open("GET",url,false);
				xmlhttp.send();
			}
		}
		
		
		
	}
	
	function removeRow(idnum)
	{
		ival--;
		document.getElementById("ivalue").value=ival;
		
		var vesselName = document.getElementById("vessel_name").value;
		var callNumber = document.getElementById("call_number").value;	
		
		if (window.XMLHttpRequest) 
		{
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = "<?php echo site_url('Vessel/deleteTempTugHiringDataForEditing');?>?id="+idnum+"&vesselName="+vesselName+"&callNumber="+callNumber;
		//alert(url);		
		xmlhttp.onreadystatechange=stateChangePopulateTable;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}
	
	function stateChangePopulateTable()
	{		
		//alert(xmlhttp.responseText);
		var initial_rows = document.getElementById("initialRows").value;
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var itemTable = document.getElementById("item_table");
			for(var k = initial_rows-1;k<itemTable.rows.length;){
				itemTable.deleteRow(k);
			}
			
			//var selectList=document.getElementById("dept");
			//removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			
			var idnum = "";
			var fdate = "";
			var tdate = "";
			var ftime = "";
			var ttime = "";
			var hrs = "";
			var wSupply = 0;
			
			for (var i = 0; i < jsonData.length; i++) 
			{
				idnum = jsonData[i].id;
				vType = jsonData[i].vehicle_type;
				vName = jsonData[i].vehicle_name;
				fdate = jsonData[i].from_date;
				tdate = jsonData[i].from_time;
				ftime = jsonData[i].to_date;
				ttime = jsonData[i].to_time;
				wSupply = jsonData[i].water_supply;
				hrs = jsonData[i].hours;
				
				var html='';
				html += '<tr>';
				html += '<td style="text-align: center">'+vType+'</td>';
				html += '<td style="text-align: center">'+vName+'</td>';
				html += '<td style="text-align: center">'+wSupply+'</td>';
				html += '<td style="text-align: center">'+fdate+'</td>';
				html += '<td style="text-align: center">'+tdate+'</td>';
				html += '<td style="text-align: center">'+ftime+'</td>';
				html += '<td style="text-align: center">'+ttime+'</td>';
				html += '<td style="text-align: center">'+hrs+'</td>';
				html += '<td style="text-align: center"><button type="button" name="remove" name="remove" onclick="removeRow('+idnum+')" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span> </button></td></tr>'
				$('#item_table').append(html);
			}
			calculateTotalHours();
			
			document.getElementById("vehicle_type").value="";
			document.getElementById("vehicle_name").value="";
			document.getElementById("water_supply").value="";
			document.getElementById("from_date").value="";
			document.getElementById("from_time").value="";
			document.getElementById("to_date").value="";
			document.getElementById("to_time").value="";
		}
	}
	
	
	function calculateTotalHours()
	{
		var total_hours = 0;
		
		var table = document.getElementById("item_table");
		
		for (var r = 2, n = table.rows.length; r < n; r++) {
			var hours = table.rows[r].cells[7].innerHTML;	
			total_hours = parseInt(total_hours) + parseInt(hours);			
		}
		
		document.getElementById("total_hours").value = total_hours;
	}
	
	function changeAccessibility(location)
	{
		if(location == "inside")
		{			
			document.getElementById("rotation").disabled = false;
			document.getElementById("vessel_name").disabled = true;
			document.getElementById("call_number").disabled = false;
			document.getElementById("description").disabled = false;
		}
		else if(location == "outside")
		{
			document.getElementById("rotation").disabled = true;
			document.getElementById("vessel_name").disabled = false;
			document.getElementById("call_number").disabled = false;
			document.getElementById("description").disabled = false;
		}
		else
		{
			document.getElementById("rotation").disabled = true;
			document.getElementById("vessel_name").disabled = true;
			document.getElementById("call_number").disabled = true;
			document.getElementById("description").disabled = true;
		}
		
		document.getElementById("rotation").value = "";
		document.getElementById("vessel_name").value = "";
		document.getElementById("call_number").value = "";
		document.getElementById("description").value = "";
	}
	
	function getVesselName()
	{
		var rotation = document.getElementById("rotation").value;
		var location = document.getElementById("location").value;
		if(location == "inside")
		{
			if(rotation == "")
			{
				alert("Please Enter Rotation No");
			}
			else
			{
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				} 
				else 
				{  
					// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				
				var url = "<?php echo site_url('Vessel/getVesselNameByRotation');?>?rotation="+rotation;
				//alert(url);
				xmlhttp.onreadystatechange=setVesselName;
				xmlhttp.open("GET",url,false);
				xmlhttp.send();
			}
		}		
	}
	function setVesselName()
	{
		//alert(xmlhttp.responseText);
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			
			var vessel_name = "";
			
			for (var i = 0; i < jsonData.length; i++) 
			{
				vessel_name = jsonData[i].VESSEL_NAME;				
			}
			
			document.getElementById("vessel_name").value=vessel_name;
		}
	}
	
	function setTugHireInfo(row_num,tug_hire_timing_id,vessel_name,call_number)
	{
		var table = document.getElementById("item_table");
		var vehicle_type = table.rows[row_num].cells[0].innerHTML;
		var vehicle_name = table.rows[row_num].cells[1].innerHTML;
		var water_supply = table.rows[row_num].cells[2].innerHTML;
		var from_date = table.rows[row_num].cells[3].innerHTML;
		var from_time = table.rows[row_num].cells[4].innerHTML;
		var to_date = table.rows[row_num].cells[5].innerHTML;
		var to_time = table.rows[row_num].cells[6].innerHTML;
		var hours = table.rows[row_num].cells[7].innerHTML;
		
		document.getElementById("vehicle_type_prev").value = vehicle_type;
		document.getElementById("vehicle_name_prev").value = vehicle_name;
		document.getElementById("water_supply_prev").value = water_supply;
		document.getElementById("from_date_prev").value = from_date;
		document.getElementById("from_time_prev").value = from_time;
		document.getElementById("to_date_prev").value = to_date;
		document.getElementById("to_time_prev").value = to_time;
		document.getElementById("hours_prev").value = hours;
		document.getElementById("vessel_name_prev").value = vessel_name;
		document.getElementById("call_number_prev").value = call_number;
		
		
		document.getElementById("row_num").value = row_num;
		document.getElementById("tug_hire_timing_id").value = tug_hire_timing_id;
		
		// Selecting Vehicle Type Starts...
		var selected_vehicle_type_index = 0;
		if(vehicle_type == "Tug")
		{
			selected_vehicle_type_index = 1;
		}
		else if(vehicle_type == "Burge")
		{
			selected_vehicle_type_index = 2;
		}
		else
		{
			selected_vehicle_type_index = 0;
		}
		document.getElementById("edit_vehicle_type").options.selectedIndex = selected_vehicle_type_index;
		//Selecting Vehicle Type Ends...
		
		//Selection Vehicle Name Starts...
		if(vehicle_type == "Tug")
		{
			var selectList=document.getElementById("edit_vehicle_name");
			removeOptions(selectList);			  
			for (var i = 1; i <= 12; i++) 
			{
				var option = document.createElement('option');
				option.value = "K-"+i;
				option.text = "K-"+i;
				selectList.appendChild(option);
			}
			var selected_vehicle_name_index = vehicle_name.substring(2, 4);
			document.getElementById("edit_vehicle_name").options.selectedIndex = selected_vehicle_name_index;
		}
		else if(vehicle_type == "Burge")
		{
			var selectList=document.getElementById("edit_vehicle_name");
			removeOptions(selectList);		
			const burges = ["MOSHAK", "JHARNA", "FOUARA", "JALPARI"]; 
			for (var i = 0; i < burges.length; i++) 
			{
				var option = document.createElement('option');
				option.value = burges[i];
				option.text = burges[i];
				selectList.appendChild(option);
				
				if(vehicle_name == burges[i])
				{
					document.getElementById("edit_vehicle_name").options.selectedIndex = i+1;
				}
			}
		}
		//Selecting Vehicle Name Ends...
		
		document.getElementById("edit_water_supply").value = water_supply;
			
		document.getElementById("edit_from_date").value = from_date.trim().substring(6, 10)
														+"-"+
														from_date.trim().substring(3, 5)
														+"-"+ 
														from_date.trim().substring(0, 2);
		
		
		document.getElementById("edit_from_time").value = from_time;
		
		document.getElementById("edit_to_date").value = to_date.trim().substring(6, 10)
														+"-"+
														to_date.trim().substring(3, 5)
														+"-"+ 
														to_date.trim().substring(0, 2);
		
		document.getElementById("edit_to_time").value = to_time;
	}
	
	function editTiming()
	{
		var row_num = document.getElementById("row_num").value;
		var edit_vehicle_type = document.getElementById("edit_vehicle_type").value;
		var edit_vehicle_name = document.getElementById("edit_vehicle_name").value;
		var edit_water_supply = document.getElementById("edit_water_supply").value;
		var edit_from_date = document.getElementById("edit_from_date").value;
		var edit_from_time = document.getElementById("edit_from_time").value;
		var edit_to_date = document.getElementById("edit_to_date").value;
		var edit_to_time = document.getElementById("edit_to_time").value;
		
		var vehicle_type_prev = document.getElementById("vehicle_type_prev").value;
		var vehicle_name_prev = document.getElementById("vehicle_name_prev").value;
		var water_supply_prev = document.getElementById("water_supply_prev").value;
		var from_date_prev = document.getElementById("from_date_prev").value;
		var from_time_prev = document.getElementById("from_time_prev").value;
		var to_date_prev = document.getElementById("to_date_prev").value;
		var to_time_prev = document.getElementById("to_time_prev").value;
		var vessel_name_prev = document.getElementById("vessel_name_prev").value;
		var call_number_prev = document.getElementById("call_number_prev").value;
		var hours_prev = document.getElementById("hours_prev").value;
				
		if(edit_vehicle_type == "Burge" && edit_water_supply < 0)
		{
			alert("Water supply can not be less than 0");
		}
		else if(edit_vehicle_type == "" || edit_vehicle_type == null)
		{
			alert("Please Enter Burge/Tug Type");
		}
		else if(edit_vehicle_name == "" || edit_vehicle_name == null)
		{
			alert("Please Enter Burge/Tug Name");
		}
		else if(edit_from_date == "" || edit_from_date == null)
		{
			alert("Please Enter Starting Date");
		}
		else if(edit_from_time == "" || edit_from_time == null)
		{
			alert("Please Enter Starting Time");
		}
		else if(edit_to_date == "" || edit_to_date == null)
		{
			alert("Please Enter Starting Time");
		}
		else if(edit_to_time == "" || edit_to_time == null)
		{
			alert("Please Enter Ending Date");
		}
		else
		{
			var table = document.getElementById("item_table");
			table.rows[row_num].cells[0].innerHTML = edit_vehicle_type;
			table.rows[row_num].cells[1].innerHTML = edit_vehicle_name;
			if(edit_vehicle_type == "Burge") {
				table.rows[row_num].cells[2].innerHTML = edit_water_supply;
			} else {
				table.rows[row_num].cells[2].innerHTML = 0;
			}
			
			table.rows[row_num].cells[3].innerHTML = edit_from_date.trim().substring(8, 10)
														+"/"+
													edit_from_date.trim().substring(5, 7)
														+"/"+ 
													edit_from_date.trim().substring(0, 4);
													
			table.rows[row_num].cells[4].innerHTML = edit_from_time;
			
			table.rows[row_num].cells[5].innerHTML = edit_to_date.trim().substring(8, 10)
														+"/"+
													edit_to_date.trim().substring(5, 7)
														+"/"+ 
													edit_to_date.trim().substring(0, 4);
													
			table.rows[row_num].cells[6].innerHTML = edit_to_time;
		
			from_date_prev = from_date_prev.trim().substring(6, 10)
							+"-"+
							from_date_prev.trim().substring(3, 5)
							+"-"+ 
							from_date_prev.trim().substring(0, 2);
							
			to_date_prev = to_date_prev.trim().substring(6, 10)
							+"-"+
							to_date_prev.trim().substring(3, 5)
							+"-"+ 
							to_date_prev.trim().substring(0, 2);
		
			
			//calculating hours starts
			var starting = edit_from_date+" "+edit_from_time;
			var ending = edit_to_date+" "+edit_to_time;			
			startingDateTime = new Date(starting);
			endingDateTime = new Date(ending);
			var diff =(endingDateTime.getTime() - startingDateTime.getTime()) / 1000;
			diff /= (60 * 60);
			var totalHours = Math.abs(Math.ceil(diff));
			table.rows[row_num].cells[7].innerHTML = totalHours;
			//calculating hours ends
			
			if (window.XMLHttpRequest) 
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			var url = "<?php echo site_url('Vessel/updateTempTugHiringData');?>?edit_vehicle_type="+edit_vehicle_type+"&edit_vehicle_name="+edit_vehicle_name+"&edit_water_supply="+edit_water_supply+"&edit_from_date="+edit_from_date+"&edit_from_time="+edit_from_time+"&edit_to_date="+edit_to_date+"&edit_to_time="+edit_to_time+"&edit_hours="+totalHours+"&vehicle_type_prev="+vehicle_type_prev+"&vehicle_name_prev="+vehicle_name_prev+"&water_supply_prev="+water_supply_prev+"&from_date_prev="+from_date_prev+"&from_time_prev="+from_time_prev+"&to_date_prev="+to_date_prev+"&to_time_prev="+to_time_prev+"&vessel_name_prev="+vessel_name_prev+"&call_number_prev="+call_number_prev+"&hours_prev="+hours_prev;
			//alert(url);		
			xmlhttp.onreadystatechange=stateChangeUpdateTiming;
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
			
			document.getElementById("vehicle_type_prev").value = edit_vehicle_type;
			document.getElementById("vehicle_name_prev").value = edit_vehicle_name;
			document.getElementById("water_supply_prev").value = edit_water_supply;
			document.getElementById("from_date_prev").value = edit_from_date;
			document.getElementById("from_time_prev").value = edit_from_time;
			document.getElementById("to_date_prev").value = edit_to_date;
			document.getElementById("to_time_prev").value = edit_to_time;
			document.getElementById("vessel_name_prev").value = vessel_name;
			document.getElementById("call_number_prev").value = call_number;
			document.getElementById("hours_prev").value = edit_hours;
			
			calculateTotalHours();
		}
	}
	
	function stateChangeUpdateTiming()
	{
		//alert(xmlhttp.responseText);
		console.log("Update Timing");
	}
	
	function getValueForEditingData(type)
	{
			
		if(type == "Tug")
		{
			var selectList=document.getElementById("edit_vehicle_name");
			removeOptions(selectList);			  
			for (var i = 1; i <= 12; i++) 
			{
				var option = document.createElement('option');
				option.value = "K-"+i;
				option.text = "K-"+i;
				selectList.appendChild(option);
			}
			document.getElementById("edit_water_supply").disabled = false;
			document.getElementById("edit_water_supply").value = 0;
			document.getElementById("edit_water_supply").disabled = true;
		}
		else if(type == "Burge")
		{
			var selectList=document.getElementById("edit_vehicle_name");
			removeOptions(selectList);		
			const burges = ["MOSHAK", "JHARNA", "FOUARA", "JALPARI"]; 
			for (var i = 0; i < burges.length; i++) 
			{
				var option = document.createElement('option');
				option.value = burges[i];
				option.text = burges[i];
				selectList.appendChild(option);
			}
			document.getElementById("edit_water_supply").disabled = false;
		}
		else
		{
			var selectList=document.getElementById("vehicle_name");
			removeOptions(selectList);
			document.getElementById("water_supply").disabled = false;
			document.getElementById("water_supply").value = 0;
			document.getElementById("water_supply").disabled = true;
		}
			
	}
	
	function removeTiming(tbl,row_num,vessel_name,call_number)
	{
		if(confirm("Do you want to delete ?"))
		{
			var table = document.getElementById("item_table");
			var vehicle_type = table.rows[row_num].cells[0].innerHTML;
			var vehicle_name = table.rows[row_num].cells[1].innerHTML;
			var water_supply = table.rows[row_num].cells[2].innerHTML;
			var from_date = table.rows[row_num].cells[3].innerHTML;
			var from_time = table.rows[row_num].cells[4].innerHTML;
			var to_date = table.rows[row_num].cells[5].innerHTML;
			var to_time = table.rows[row_num].cells[6].innerHTML;
			var hours = table.rows[row_num].cells[7].innerHTML;
			
			//alert("..." + from_date);
			
			from_date = from_date.trim().substring(6, 10)
						+"-"+
						from_date.trim().substring(3, 5)
						+"-"+ 
						from_date.trim().substring(0, 2);
							
			to_date = to_date.trim().substring(6, 10)
						+"-"+
						to_date.trim().substring(3, 5)
						+"-"+ 
						to_date.trim().substring(0, 2);
			
			if (window.XMLHttpRequest) 
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			//...............
			var row_index = tbl.parentNode.parentNode.rowIndex;
			//alert(tbl.rowIndex);
			
			//var row_index = tbl.rowIndex;
			
			document.getElementById("item_table").deleteRow(row_index); 
			
			var initial_rows = document.getElementById("initialRows").value;
			initial_rows = initial_rows-1;
			document.getElementById("initialRows").value = initial_rows;
			
			calculateTotalHours();
			//...............
			
			var url = "<?php echo site_url('Vessel/deleteTempTugHiringDataForRemoving');?>?vessel_name="+vessel_name+"&call_number="+call_number+"&vehicle_type="+vehicle_type+"&vehicle_name="+vehicle_name+"&water_supply="+water_supply+"&from_date="+from_date+"&from_time="+from_time+"&to_date="+to_date+"&to_time="+to_time+"&hours="+hours;
			//alert(url);		
			xmlhttp.onreadystatechange=stateChangeRemoveTiming;
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
			
			
			
		}
		
	}
	
	function stateChangeRemoveTiming()
	{
		//alert(xmlhttp.responseText);
		console.log("Timing Removed from Helper");
	}
	
	function chkConfirm()
	{
		if (confirm("Do you want to save ?") == true)
			{
				document.getElementById("rotation").disabled = false;
				document.getElementById("vessel_name").disabled = false;
				document.getElementById("call_number").disabled = false;
				document.getElementById("description").disabled = false;
				
				return true ;
			}
		else
			{
				return false;
			}		
	}
</script>
