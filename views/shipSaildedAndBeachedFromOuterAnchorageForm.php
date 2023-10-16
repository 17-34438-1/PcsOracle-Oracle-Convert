<script>
function validate(){
	var rotation=document.getElementById("rotation").value;
	var vesselName=document.getElementById("vesselName").value;
	var arrivalDate=document.getElementById("arrivalDate").value;
	var arrivalTime=document.getElementById("arrivalTime").value;
	var departureDate=document.getElementById("departureDate").value;
	var departureTime=document.getElementById("departureTime").value;
	var agent=document.getElementById("agent").value;
	var flag=document.getElementById("flag").value;
	var grt=document.getElementById("grt").value;
	var nrt=document.getElementById("nrt").value;
	var remarks=document.getElementById("remarks").value;
	if(rotation==""){
		alert("Rotation is Empty");
		return false;
	}
	else if(vesselName==""){
		
		alert("Vessel Name is Empty");
		return false;
	}
	else if(arrivalDate==""){
		alert("Arrival Date is Empty");
		return false;
	}
	else if(arrivalTime==""){
		alert("Arrival Time is Empty");
		return false;
	}
	else if(departureDate==""){
		alert("Departure Date is Empty");
		return false;
	}
	else if(departureTime==""){
		alert("Departure Time is Empty");
		return false;
	}
	else if(agent==""){
		alert("Agent is Empty");
		return false;
	}
	else if(flag==""){
		alert("Flag is Empty");
		return false;
	}
	else if(grt==""){
		alert("GRT is Empty");
		return false;
	}
	else if(nrt==""){
		alert("NRT is Empty");
		return false;
	}
	else if(remarks==""){
		alert("Remarks is Empty");
		return false;
	}
	
}
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					
					<!--form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Vessel/saveOuterAnchorageVsl"); ?>" id="myform" name="myform" onsubmit="return validate()"-->
					<div class="row">
						<div class="col-sm-12 text-center">
						<?php echo $msg;?>
						</div>													
					</div>
					<div class="row">
						<div class="col-sm-12 text-right">
						  <form action="<?php echo site_url("Vessel/outerAnchorageVslList")?>" method="POST">
						      <button type="submit" id="submit" name="viewList" class="mb-xs mt-xs mr-xs btn btn-success login_button">View List</button>
						  </form>  
						</div>													
					</div>
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Vessel/saveOuterAnchorageVsl"); ?>" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<!--label class="col-md-2 control-label">&nbsp;</label-->
                            
							<!--div class="col-md-8"-->
							
							  <div class="col-md-6">  
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Rotation No<span class="required">*</span></span>
										<input type="text" name="rotation" class="form-control" id="rotation" value="<?php if($flagState=="edit") { echo $vslInfo[0]['imp_rot'];} ?>" placeholder="Rotation No">
									</div>
                              </div>
							  <div class="col-md-6">
                                         <div class="input-group mb-md">
										    <span class="input-group-addon span_width">Vessel Name<span class="required">*</span></span>
											<input type="text" name="vesselName"  class="form-control"  id="vesselName" value="<?php if($flagState=="edit") { echo $vslInfo[0]['vsl_name'];} ?>" placeholder="Name Of Vessel">
										</div>
							  </div>
							    <div class="col-md-6">			
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Arrival Date<span class="required">*</span></span>
										<input type="date" name="arrivalDate"  class="form-control" id="arrivalDate" value="<?php if($flagState=="edit") { echo $vslInfo[0]['date_of_arrival'];} ?>" placeholder="Date Of Arrival">
									</div>
								</div>
								<div class="col-md-6">	
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Arrival Time<span class="required">*</span></span>
										<input type="text" name="arrivalTime" class="form-control"  id="arrivalTime" value="<?php if($flagState=="edit") { echo $vslInfo[0]['time_of_arrival'];} ?>" placeholder="00:00:00">
									</div>
								</div>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Departure Date<span class="required">*</span></span>
										<input type="date" name="departureDate"  class="form-control" id="departureDate"  value="<?php if($flagState=="edit") { echo $vslInfo[0]['date_of_departure'];} ?>" placeholder="Date Of Departure">
									</div>
								</div>
								<div class="col-md-6">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Departure Time <span class="required">*</span></span>
										<input type="text" name="departureTime" class="form-control" id="departureTime" value="<?php if($flagState=="edit") { echo $vslInfo[0]['time_of_departure'];} ?>"  placeholder="00:00:00">
									</div>
								</div>
								<div class="col-md-6">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Beaching Agent<span class="required">*</span></span>
										<input type="text" name="agent"  class="form-control" id="agent" value="<?php if($flagState=="edit") { echo $vslInfo[0]['beaching_agent'];} ?>" placeholder="Beaching Agent">
									</div>
								</div>	
								<div class="col-md-6">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Flag<span class="required">*</span></span>
										<input type="text" name="flag"  class="form-control" id="flag" value="<?php if($flagState=="edit") { echo $vslInfo[0]['flag'];} ?>" placeholder="Flag">
									</div>
								</div>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Grt<span class="required">*</span></span>
										<input type="text" name="grt"  class="form-control"  id="grt" value="<?php if($flagState=="edit") { echo $vslInfo[0]['grt'];} ?>" placeholder="Grt">
									</div>
								</div>
								<div class="col-md-6">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Nrt<span class="required">*</span></span>
										<input type="text" name="nrt"  class="form-control"  id="nrt" value="<?php if($flagState=="edit") { echo $vslInfo[0]['nrt'];} ?>" placeholder="Nrt">
									</div>
								</div>
								<div class="col-md-6">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Remarks<span class="required">*</span></span>
										<input type="text" name="remarks"  class="form-control"  id="remarks" value="<?php if($flagState=="edit") { echo $vslInfo[0]['remarks'];} ?>" placeholder="Remarks">
									</div>
								</div>
								
																			
							<!--/div-->
							
							
							
																		
							<div class="row">
								<div class="col-sm-12 text-center">
								 <?php if($flagState!="edit"){ ?>
								        <button type="submit" id="submit" name="insert" class="mb-xs mt-xs mr-xs btn btn-success login_button">Insert</button>
								  <?php }  else {?>
									   <input type="hidden" name="updateId" value="<?php  echo $vslInfo[0]['id']; ?>"/>   
					                    <button type="submit" id="submit" name="update" class="mb-xs mt-xs mr-xs btn btn-success login_button">Update</button>
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