<script>
	function getVslInfo()
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
                document.getElementById('visitedVesselId').value=jsonData.id;
				document.getElementById('agent_name').value=jsonData.agent_name;		
				document.getElementById('vslType').value=jsonData.vsl_type;	
				
				if(document.getElementById('vslType').value=="Self Piloting" || document.getElementById('vslType').value=="")
				{
					document.getElementById('rotNo').disabled=true;
				}
				// else if(document.getElementById('vslType').value=="Not Entering")
				else
				{
					document.getElementById('rotNo').disabled=false;
				}
			}
		}
		var url="<?php echo site_url('Vessel/getVslInfo')?>?vslName="+vslName;
        
		// alert(url);
		xmlhttp.open("GET",url,false);	
		
		xmlhttp.send();	 
	}
	
	// function blockRotation()
	// {
		// if(document.getElementById('vslType').value=="Private" || document.getElementById('vslType').value=="")
		// {
			// document.getElementById('rotNo').disabled=true;
		// }
		// else if(document.getElementById('vslType').value=="Not Entering")
		// {
			// document.getElementById('rotNo').disabled=false;
		// }
	// }
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
	<div class="row">
    <div class="col-lg-12">						
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
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<input type="hidden" name="visitedVesselId" id="visitedVesselId" value=""/>   
									<span class="input-group-addon span_width">Vessel Name <span class="required">*</span></span>
									<!--input type="text" name="vslName" id="vslName" class="form-control" value="" onblur = "getVslInfo()"-->
									
									<?php
									if($vesselVisitFlag=="edit")
									{
									?>
									<input type="text" name="editVslName" id="editVslName" class="form-control" value="<?php echo $vslVisitInfo[0]['vsl_name']; ?>" readonly />   
									<?php
									}
									else
									{
									?>
									<select name="vslName" id="vslName" onchange = "getVslInfo()" class="form-control">
										<option value="">- Select -</option>
										<?php
										include('mydbPConnection.php');
										
										$sql_vslInfo = "SELECT vsl_name FROM outer_vsl_info WHERE delete_flag='0'";
										
										$rslt_vslInfo = mysqli_query($con_cchaportdb,$sql_vslInfo);
										
										while($row_vslInfo = mysqli_fetch_object($rslt_vslInfo))
										{
										?>
										<option value="<?php echo $row_vslInfo->vsl_name; ?>" <?php if($vesselVisitFlag=="edit"){ if($vslVisitInfo[0]['vsl_name']==$row_vslInfo->vsl_name){ echo "selected";} }?> > <?php echo $row_vslInfo->vsl_name; ?></option>
										<?php
										}
										?>
									</select>
									<?php
									}
									?>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Vessel Type <span class="required">*</span></span>
									<input type="text" name="vslType" id="vslType" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['vsl_type'];} ?>" readonly>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
									<input type="text" name="rotNo" id="rotNo" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['imp_rot'];} ?>" enable>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">GRT <span class="required">*</span></span>
									<input type="text" name="visitGrt" id="visitGrt" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['grt'];} ?>" readonly>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Voyage No <span class="required">*</span></span>
									<input type="text" name="voyage_no" id="voyage_no" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['voyage_no'];} ?>">
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NRT <span class="required">*</span></span>
									<input type="text" name="visitNrt" id="visitNrt" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['nrt'];} ?>" readonly>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date of arrival <span class="required">*</span></span>
									<input type="date" name="dateOfArrival" id="dateOfArrival" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['date_of_arrival'];} ?>">
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Flag <span class="required">*</span></span>
									<input type="text" name="visitFlag" id="visitFlag" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['flag'];} ?>" readonly>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Time of arrival <span class="required">*</span></span>
									<!--input type="time" name="timeOfArrival" id="timeOfArrival" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['time_of_arrival'];} ?>"-->
									
										<select name="ataHH" id="ataHH" class="form-control" style="width:100px">
											<option value="">Hour</option>
											<?php
											for($i=0;$i<24;$i++)
											{
												if(strlen($i) == 1)
													$hr = "0".$i;
												else
													$hr = $i;
											?>
											<option value="<?php echo $hr; ?>"
											<?php
											if($vesselVisitFlag=="edit")
											{
												if($vslVisitInfo[0]['ataHH'] == $hr)
												{													
											?>selected<?php
												}
											}
											?>											
											><?php echo $hr; ?> </option>
											<?php
											}
											?>
										</select>
										<select name="ataMM" id="ataMM" class="form-control" style="width:100px">
											<option value="">Minute</option>
											<?php
											for($j=0;$j<60;$j++)
											{
												if(strlen($j) == 1)
													$min = "0".$j;
												else
													$min = $j;
											?>
											<option value="<?php echo $min; ?>"
											<?php
											if($vesselVisitFlag=="edit")
											{
												if($vslVisitInfo[0]['ataMM'] == $min)
												{													
											?>selected<?php
												}
											}
											?>											
											><?php echo $min; ?></option>
											<?php
											}
											?>
										</select>
									
								</div>
							</div>

							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Agent <span class="required">*</span></span>
									<input type="text" name="agent_name" id="agent_name" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['agent_name'];} ?>" readonly>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date of departure <span class="required">*</span></span>
									<input type="date" name="dateOfDeparture" id="dateOfDeparture" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['date_of_departure'];} ?>">
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Remarks <span class="required">*</span></span>
									<input type="text" name="remarks" id="remarks" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['remarks'];} ?>">
								</div>
							</div>										
							
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Time of departure <span class="required">*</span></span>
									<!--input type="time" name="timeOfDeparture" id="timeOfDeparture" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['time_of_departure'];} ?>"-->
									<select name="atdHH" id="atdHH" class="form-control" style="width:100px">
										<option value="">Hour</option>
										<?php
										for($i=0;$i<24;$i++)
										{
											if(strlen($i) == 1)
												$hr = "0".$i;
											else
												$hr = $i;
										?>
										<option value="<?php echo $hr; ?>"
										<?php
										if($vesselVisitFlag=="edit")
										{
											if($vslVisitInfo[0]['atdHH'] == $hr)
											{													
										?>selected<?php
											}
										}
										?>										
										><?php echo $hr; ?></option>
										<?php
										}
										?>
									</select>
									
									<select name="atdMM" id="atdMM" class="form-control" style="width:100px">
										<option value="">Minute</option>
										<?php
										for($j=0;$j<60;$j++)
										{
											if(strlen($j) == 1)
												$min = "0".$j;
											else
												$min = $j;
										?>
										<option value="<?php echo $min; ?>"
										<?php
											if($vesselVisitFlag=="edit")
											{
												if($vslVisitInfo[0]['atdMM'] == $min)
												{													
											?>selected<?php
												}
											}
											?>											
										><?php echo $min; ?></option>
										<?php
										}
										?>
									</select>
								</div>
							</div>
							
							<!--div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Voyage No <span class="required">*</span></span>
									<input type="text" name="voyage_no" id="voyage_no" class="form-control" value="<?php if($vesselVisitFlag=="edit") { echo $vslVisitInfo[0]['voyage_no'];} ?>">
								</div>
							</div-->
							
							
																																			
							<!-- // -->							
																			
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
		</div> 
    </div>	
</section>
</div>   