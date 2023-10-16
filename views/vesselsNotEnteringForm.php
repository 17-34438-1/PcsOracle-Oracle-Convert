<script>
/*
	function validateVesselInfo(){
		if(document.getElementById("vesselId").value==""){
			alert("Vessel Id is Empty");
			return false;
		}
		else if(document.getElementById("vesselName").value==""){
			alert("Vessel Name is Empty");
			return false;

		}
		else if(document.getElementById("vesselClass").value==""){
			alert("Vessel Class is Empty");
			return false;

		}
		else if(document.getElementById("radioCallSign").value==""){
			alert("Radio Call Sign is Empty");
			return false;

		}
		else if(document.getElementById("grt").value==""){
			alert("Grt is Empty");
			return false;

		}
		else if(document.getElementById("nrt").value==""){
			alert("Nrt is Empty");
			return false;

		}
		else if(document.getElementById("flag").value==""){
			alert("Flag is Empty");
			return false;

		}
		else{
			return true;
		}
	}
	
	function validateAgentInfo(){
		if(document.getElementById("agentId").value==""){
			alert("Agent Id is Empty");
			return false;
		}
		else if(document.getElementById("agentName").value==""){
			alert("Agent Name is Empty");
			return false;

		}
	   
		else if(document.getElementById("aliAsId").value==""){
			alert("Alias Id is Empty");
			return false;

		}
		else if(document.getElementById("contractName").value==""){
			alert("Contract Name is Empty");
			return false;

		}
		else if(document.getElementById("contractAddress").value==""){
			alert("Contract Address is Empty");
			return false;

		}
		else if(document.getElementById("contractCity").value==""){
			alert("Contract City is Empty");
			return false;

		}
		else if(document.getElementById("contractEmail").value==""){
			alert("Contract Email is Empty");
			return false;

		}
		else if(document.getElementById("contractPhone").value==""){
			alert("Contract Phone is Empty");
			return false;

		}
		
		else{
			return true;
		}	  
	}
	
	function validateVslVisit()
	{
		if(document.getElementById('vslName').value=="")
		{
			alert("Vessel Name is Empty");
			return false;
		}
		else if(document.getElementById('visitGrt').value=="")
		{
			alert("GRT is Empty");
			return false;
		}
		else if(document.getElementById('visitNrt').value=="")
		{
			alert("NRT is Empty");
			return false;
		}
		else if(document.getElementById('visitFlag').value=="")
		{
			alert("Flag is Empty");
			return false;
		}
		else if(document.getElementById('rotNo').value=="")
		{
			alert("Rotation No is Empty");
			return false;
		}
		else if(document.getElementById('dateOfArrival').value=="")
		{
			alert("Date Of Arrival is Empty");
			return false;
		}
		else if(document.getElementById('timeOfArrival').value=="")
		{
			alert("Time Of Arrival is Empty");
			return false;
		}
		else if(document.getElementById('dateOfDeparture').value=="")
		{
			alert("Date Of Departure is Empty");
			return false;
		}
		else if(document.getElementById('timeOfDeparture').value=="")
		{
			alert("Time Of Departure is Empty");
			return false;
		}
		else if(document.getElementById('remarks').value=="")
		{
			alert("Remarks is Empty");
			return false;
		}
		else
		{
			return true;
		}
	}
*/	
/*	function getVslInfo()
	{
		var vslName = document.getElementById("vslName").value;
		
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
				
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{							
				var val = xmlhttp.responseText;
				var jsonData = JSON.parse(val);												
				
				document.getElementById('visitGrt').value=jsonData.grt;
				document.getElementById('visitNrt').value=jsonData.nrt;
				document.getElementById('visitFlag').value=jsonData.flag;
										
			}
		}
		var url="<?php echo site_url('Vessel/getVslInfo')?>?vslName="+vslName;
		// alert(url);
		xmlhttp.open("GET",url,false);	
		
		xmlhttp.send();	 
	}*/
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
	<div class="row">
		
		<!-- Vessel -->
		<div class="col-sm-12">
			<section class="panel">
				<div class="panel-body">
					<!--div class="row">
						<div class="col-sm-12 text-center">
							<?php // echo $msg;?>
						</div>													
					</div-->
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Vessel/saveVesselInformation'; ?>"  id="myform" onsubmit="return validateVesselInfo()">
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-9">	
								<div class="col-md-11 input-group mb-md" align="center">
								<font size='4'> Vessel Information</font>
								</div>
								<div class="col-md-11 input-group mb-md" align="center">
								  <?php echo $msg1; ?>
								</div>
								<!--div class="input-group mb-md">
									<span class="input-group-addon span_width">Vessel Id <span class="required">*</span></span>
									<input type="text" name="vesselId" id="vesselId" class="form-control" placeholder="Vessel Id">
								</div-->
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Vessel Name<span class="required">*</span></span>
									<input type="text" name="vesselName" id="vesselName" class="form-control"  placeholder="Vessel Name" value="<?php if($vesselFlag=="edit") { echo $vslInfo[0]['vsl_name'];} ?>">
								</div>
								<!--div class="input-group mb-md">
									<span class="input-group-addon span_width">Vessel Class<span class="required">*</span></span>
									<input type="text" name="vesselClass" id="vesselClass" class="form-control"  placeholder="Vessel Class">
								</div-->
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Vessel Type<span class="required">*</span></span>
									<!--select name="vslType" id="vslType" class="form-control">
										<option value="">--Select--</option>										
										
										<option value="Self Piloting" <?php if($vesselFlag=="edit"){ if($vslInfo[0]['vsl_type']=="Self Piloting"){ ?>selected<?php } } ?> >Self Piloting</option>
										<option value="Not Entering" 
										<?php if($vesselFlag=="edit"){ if($vslInfo[0]['vsl_type']=="Not Entering"){ ?>selected<?php } } ?> >Not Entering</option>
										<option value="Break Bulk" <?php if($vesselFlag=="edit"){ if($vslInfo[0]['vsl_type']=="Break Bulk"){ ?>selected<?php } } ?> >Break Bulk</option>
										<option value="Tanker" <?php if($vesselFlag=="edit"){ if($vslInfo[0]['vsl_type']=="Tanker"){ ?>selected<?php } } ?> >Tanker</option>
									</select-->
									
									<select name="vslType" id="vslType" class="form-control">
										<option value="">--Select--</option>
										<?php
										for($i=0;$i<count($rslt_vslType);$i++)
										{
										?>
										<option value="<?php echo $rslt_vslType[$i]['vsl_type']; ?>" <?php if($vesselFlag=="edit"){ if($vslInfo[0]['vsl_type']==$rslt_vslType[$i]['vsl_type']){ ?>selected<?php } } ?> ><?php echo $rslt_vslType[$i]['vsl_type']; ?></option>
										<?php
										}
										?>
										
									</select>
									
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Radio Call Sign<span class="required">*</span></span>
									<input type="text" name="radioCallSign" id="radioCallSign" class="form-control"  placeholder="Radio Call Sign" value="<?php if($vesselFlag=="edit") { echo $vslInfo[0]['radio_call_sign'];} ?>">
								</div>
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">LOA<span class="required">*</span></span>
									<input type="text" name="loa" id="loa" class="form-control"  placeholder="LOA" value="<?php if($vesselFlag=="edit") { echo $vslInfo[0]['loa'];} ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">IMO <span class="required">*</span></span>
									<input type="text" name="imo" id="imo" class="form-control"  placeholder="IMO" value="<?php if($vesselFlag=="edit") { echo $vslInfo[0]['imo'];} ?>">
								</div>
								<!--div class="input-group mb-md">
									<span class="input-group-addon span_width">Voyage No<span class="required">*</span></span>
									<input type="text" name="voyNo" id="voyNo" class="form-control"  placeholder="Voyage No" value="<?php if($vesselFlag=="edit") { echo $vslInfo[0]['voyage_no'];} ?>">
								</div-->
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">GRT<span class="required">*</span></span>
									<input type="text" name="grt" id="grt" class="form-control"  placeholder="GRT"  value="<?php if($vesselFlag=="edit") { echo $vslInfo[0]['grt'];} ?>" >
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NRT<span class="required">*</span></span>
									<input type="text" name="nrt" id="nrt" class="form-control"  placeholder="NRT"  value="<?php if($vesselFlag=="edit") { echo $vslInfo[0]['nrt'];} ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Flag<span class="required">*</span></span>
									<input type="text" name="flag" id="flag" class="form-control"  placeholder="Flag"  value="<?php if($vesselFlag=="edit") { echo $vslInfo[0]['flag'];} ?>">
								</div>
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Agent<span class="required">*</span></span>
									<!--input type="text" name="flag" id="flag" class="form-control"  placeholder="Flag"-->
									<select name="agentInfo" id="agentInfo" class="form-control">
										<option value="">- Select -</option>
										<?php
										include('mydbPConnection.php');
										
										$sql_agentInfo = "SELECT id,agent_name FROM outer_agent_info WHERE delete_flag='0'";
										
										$rslt_agentInfo = mysqli_query($con_cchaportdb,$sql_agentInfo);
										
										while($row_agentInfo = mysqli_fetch_object($rslt_agentInfo))
										{
										?>
										<option value="<?php echo $row_agentInfo->id; ?>" <?php if($vesselFlag=="edit"){if( $vslInfo[0]['agent_id']== $row_agentInfo->id ){ echo "selected";} } ?> ><?php echo $row_agentInfo->agent_name; ?></option>
										<?php
										}
										?>
									</select>
								</div>
								
							   
							   
																		
							</div>

						  
						   
						  

							<div class="row">
								<div class="col-sm-12 text-center">
								<?php if($vesselFlag!="edit"){ ?>
									<button type="submit" id="submit" name="insertVessel" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
									<?php }  else {?>
									<input type="hidden" name="updateVesselId" value="<?php  echo $vslInfo[0]['id']; ?>"/>   
					                <button type="submit" id="submit" name="updateVeessel" class="mb-xs mt-xs mr-xs btn btn-success login_button">Update</button>
									<?php }?>
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
		
		
		
		<!-- Vessel visit -->		
		<!--div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php echo $msg; ?>
						</div>													
					</div>
					<div class="row">
						<div class="col-sm-12 text-center">
							<font size='4'> Vessel Visit Information</font>
						</div>													
					</div>
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Vessel/vesselVisitAction'); ?>"  id="myform" name="myform" onsubmit="return validateVslVisit()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							
							<div class="col-md-6">										
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Vessel Name <span class="required">*</span></span>
								
									
									<select name="vslName" id="vslName" onchange = "getVslInfo()" class="form-control">
										<option value="">- Select -</option>
										<?php
										include('mydbPConnection.php');
										
										$sql_vslInfo = "SELECT vsl_name FROM outer_vsl_info";
										
										$rslt_vslInfo = mysqli_query($con_cchaportdb,$sql_vslInfo);
										
										while($row_vslInfo = mysqli_fetch_object($rslt_vslInfo))
										{
										?>
										<option value="<?php echo $row_vslInfo->vsl_name; ?>" <?php if($vesselVisitFlag=="edit"){ if($vslVisitInfo[0]['vsl_name']==$row_vslInfo->vsl_name){ echo "selected";} }?> > <?php echo $row_vslInfo->vsl_name; ?></option>
										<?php
										}
										?>
									</select>
								</div>
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">GRT <span class="required">*</span></span>
									<input type="text" name="visitGrt" id="visitGrt" class="form-control" value="" readonly>
								</div>
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NRT <span class="required">*</span></span>
									<input type="text" name="visitNrt" id="visitNrt" class="form-control" value="" readonly>
								</div>
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Flag <span class="required">*</span></span>
									<input type="text" name="visitFlag" id="visitFlag" class="form-control" value="" readonly>
								</div>
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
									<input type="text" name="rotNo" id="rotNo" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['imp_rot'];} ?>">
								</div>
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date of arrival <span class="required">*</span></span>
									<input type="date" name="dateOfArrival" id="dateOfArrival" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['date_of_arrival'];} ?>">
								</div>
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Time of arrival <span class="required">*</span></span>
									<input type="time" name="timeOfArrival" id="timeOfArrival" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['time_of_arrival'];} ?>">
								</div>	
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date of departure <span class="required">*</span></span>
									<input type="date" name="dateOfDeparture" id="dateOfDeparture" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['date_of_departure'];} ?>">
								</div>	
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Time of departure <span class="required">*</span></span>
									<input type="time" name="timeOfDeparture" id="timeOfDeparture" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['time_of_departure'];} ?>">
								</div>
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Remarks <span class="required">*</span></span>
									<input type="text" name="remarks" id="remarks" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['remarks'];} ?>">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">	
								<?php if($vesselVisitFlag!="edit"){ ?>								
									<button type="submit" name="vesselVisitInsert" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
									<?php }  else {?>
									<input type="hidden" name="vesselVisitUpdateId" value="<?php  echo $vslVisitInfo[0]['id']; ?>"/>   
					                <button type="submit" id="submit" name="vesselVisitUpdate" class="mb-xs mt-xs mr-xs btn btn-success login_button">Update</button>
									<?php }?>
								</div>													
							</div>							
						</div>	
					</form>
				</div>
			</section>
		</div-->
	</div>	
</section>
</div>
<?php mysqli_close($con_cchaportdb); ?>