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
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Vessel/tugHiring') ?>"
								onsubmit="return chkConfirm();">
								<input type="hidden" name="ivalue" id="ivalue" value="0">
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
												<option value="inside">Inside Port</option>
												<option value="outside">Outside Port</option>
											</select>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation</span>
											<input class="form-control" name="rotation" id="rotation" required disabled 
												onblur="getVesselName()">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Vessel Name</span>
											<input class="form-control" list="vesselNameList" name="vessel_name" id="vessel_name" required disabled>
											<datalist id="vesselNameList">												
												<?php for($i = 0; $i<count($vesselNameList);$i++) { ?>
													<option value="<?php echo $vesselNameList[$i]["vessel_name"]?>">
												<?php } ?>										
											</datalist>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Call Number</span>
											<input type="number" class="form-control" name="call_number" id="call_number" disabled required>
										</div>	
									</div>
									<div class="col-md-6">	
										<div class="input-group mb-md">
											
												<span class="input-group-addon span_width">Shipping Agent</span>
												<select data-plugin-selectTwo class="form-control" 
													name="shipping_agent" id="shipping_agent" required>
													<option value="">--Select--</option>
													<?php for($i=0;$i<count($shippingAgentList);$i++) { ?>
													<option value="<?php echo $shippingAgentList[$i]['id']?>">
														<?php echo $shippingAgentList[$i]['Organization_Name'];?>
													</option>
													<?php } ?>
												</select>
											
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Description</span>
											<textarea name="description" id="description" rows="5" class="form-control" disabled></textarea>
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
													<th style="text-align: center">Water Supply (M. Ton)</th>
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
											<input type="text" name="total_hours" id="total_hours" value="0" class="form-control" readonly>
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
			
			var url = "<?php echo site_url('Vessel/insertTempTugHiringData');?>?fromDate="+fromDate+"&fromTime="+fromTime+"&toDate="+toDate+"&toTime="+toTime+"&totalHours="+totalHours+"&vehicleType="+vehicleType+"&vehicleName="+vehicleName+"&vesselName="+vesselName+"&callNumber="+callNumber+"&waterSupply="+waterSupply;
			//alert(url);
			xmlhttp.onreadystatechange=stateChangePopulateTable;
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
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
		
		var url = "<?php echo site_url('Vessel/deleteTempTugHiringData');?>?id="+idnum+"&vesselName="+vesselName+"&callNumber="+callNumber;
		//alert(url);
		xmlhttp.onreadystatechange=stateChangePopulateTable;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}
	
	function stateChangePopulateTable()
	{
		//alert(xmlhttp.responseText);
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var itemTable = document.getElementById("item_table")
				for(var k = 2;k<itemTable.rows.length;){
				itemTable.deleteRow(k);
			}
			
			//var selectList=document.getElementById("dept");
			//removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			// alert(xmlhttp.responseText);
			
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />
<!--script type="text/javascript">
	$(function () {
		$("#from_date").datepicker({
			dateFormat: 'dd/mm/yy',
			autoclose: true
		});
	});
	$(function () {
		$("#to_date").datepicker({
			dateFormat: 'dd/mm/yy',
			autoclose: true
		});
	});
</script-->