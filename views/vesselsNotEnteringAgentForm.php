<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
	<div class="row">
       <div class="col-sm-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Vessel/saveAgentInfromation'; ?>" id="myform" onsubmit="return validateAgentInfo()" >
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-9">	
								<div class="col-md-11 input-group mb-md" align="center">
								<font size='4'>  Agent Information</font>
								   
								</div>
								<div class="col-md-11 input-group mb-md" align="center">
									<?php echo $msg2;?> 
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Agent Code <span class="required">*</span></span>
									<input type="text" name="agentCode" id="agentCode" class="form-control" placeholder="Agents short name by latter" value="<?php if($agentFlag=="edit") { echo $agentInfo[0]['agent_code'];} ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Agent Name<span class="required">*</span></span>
									<input type="text" name="agentName" id="agentName" class="form-control"  placeholder="Agent Name" value="<?php if($agentFlag=="edit") { echo $agentInfo[0]['agent_name'];} ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Alias Id<span class="required">*</span></span>
									<input type="text" name="aliAsId" id="aliAsId" class="form-control"  placeholder="Agents Billing Code by no as 345676" value="<?php if($agentFlag=="edit") { echo $agentInfo[0]['alias_id'];} ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Contact Name<span class="required">*</span></span>
									<input type="text" name="contractName" id="contractName" class="form-control"  placeholder="Contact Name" value="<?php if($agentFlag=="edit") { echo $agentInfo[0]['contact_name'];} ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Contact Address<span class="required">*</span></span>
									<input type="text" name="contractAddress" id="contractAddress" class="form-control"  placeholder="Contact Address" value="<?php if($agentFlag=="edit") { echo $agentInfo[0]['contact_address'];} ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Contact City<span class="required">*</span></span>
									<input type="text" name="contractCity" id="contractCity" class="form-control"  placeholder="Contact City" value="<?php if($agentFlag=="edit") { echo $agentInfo[0]['contact_city'];} ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Contact Email<span class="required">*</span></span>
									<input type="text" name="contractEmail" id="contractEmail" class="form-control"  placeholder="Contact Email" value="<?php if($agentFlag=="edit") { echo $agentInfo[0]['contact_email'];} ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Contact Country<span class="required">*</span></span>
									<input type="text" name="contractCountry" id="contractCountry" class="form-control"  placeholder="Contact Country" value="<?php if($agentFlag=="edit") { echo $agentInfo[0]['contact_country'];} ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Contact Phone<span class="required">*</span></span>
									<input type="text" name="contractPhone" id="contractPhone" class="form-control"  placeholder="Contact Phone" value="<?php if($agentFlag=="edit") { echo $agentInfo[0]['contact_phone'];} ?>">
								</div>
							   
							   
							  
																			
							</div>

						 

							<div class="row">
								<div class="col-sm-12 text-center">
								<?php if($agentFlag!="edit"){ ?>
									<button type="submit" id="submit" name="insertAgent" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
									<?php }  else {?>
									<input type="hidden" name="updateAgentId" value="<?php  echo $agentInfo[0]['id']; ?>"/>   
					                <button type="submit" id="submit" name="updateAgent" class="mb-xs mt-xs mr-xs btn btn-success login_button">Update</button>
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
    </div>	
</section>
</div>    