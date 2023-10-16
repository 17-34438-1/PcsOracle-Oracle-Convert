<script type="text/javascript">
  
	function validate()
	{
		var jsName=document.getElementById('jsName').value;
        var jsLicenseNo=document.getElementById('jsLicenseNo').value;
        var jsContact=document.getElementById('jsContact').value;
        var jsAddress=document.getElementById('jsAddress').value;
             
        if(jsName==""|| jsName==" ")
        {
            alert("Please! Provide Name.");
            document.getElementById('jsName').focus();
            return false;
        }
        else if(jsLicenseNo==""|| jsLicenseNo==" ")
        {
            alert("Please! Provide License Number.");
            document.getElementById('jsLicenseNo').focus();
            return false;
        }
         else if(jsContact==""|| jsContact==" ")
        {
            alert("Please! Provide Contact Number.");
            document.getElementById('jsContact').focus();
            return false;
        }
		else if(jsAddress==""|| jsAddress==" ")
        {
            alert("Please! Provide Address.");
            document.getElementById('jsAddress').focus();
            return false;
        }
		else {
			return true;
		}
	}	
	
</script
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
									<header class="panel-heading">
										<h2 class="panel-title" align="right">
											<a href="<?php echo site_url('Controller/List') ?>">
												<button style="margin-left: 35%" class="btn btn-primary btn-sm">
													<i class="fa fa-list"></i>
												</button>
											</a>
										</h2>								
									</header>
									<div class="panel-body" align="center">
										<form name= "myForm" onsubmit="return(validate());" action="<?php echo site_url("report/jettySarkarEntryFormPerform");?>" method="post"  enctype="multipart/form-data">
											<input type="hidden" name="" value="">
					
											<div class="form-group">
												<label class="col-md-3 control-label">&nbsp;</label>
												<div class="col-md-6">
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;"><?php echo $msg; ?></span>
													</div>												
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">Jetty Sarkar Name <span class="required">*</span></span>
														<input type="text" style="width:150px;" id="jsName" name="jsName" <?php if($editFlag==1){ ?> value="<?php echo $jttySr[0]['js_name']; }?>" >
													</div>
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">Jetty Sarkar License No <span class="required">*</span></span>
														<input type="text" style="width:150px;"  id="jsLicenseNo " name="jsLicenseNo" <?php if($editFlag==1){ ?> value="<?php echo $jttySr[0]['js_lic_no'] ; } ?>">
													</div>			
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">Contact No <span class="required">*</span></span>
														<input type="text" style="width:150px;" id="jsContact" name="jsContact"   value="<?php if($editFlag==1){ echo $jttySr[0]['cell_no'] ; } ?>" >
													</div>		
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">Address <span class="required">*</span></span>
														<input type="text" style="width:150px;" id="jsAddress" name="jsAddress" value="<?php if($editFlag==1){echo $jttySr[0]['adress'] ; } ?>"  >
													</div>		
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">Upload Signature  <span class="required">*</span></span>
														<input type="file" style="width:150px;" name="sign" id="sign" />
													</div>			
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">Upload License Copy <span class="required">*</span></span>
														<input type="file" style="width:150px;" name="license_img" id="license_img" />
													</div>	
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">License Validity Date <span class="required">*</span></span>
														<input type="date" style="width:150px;" name="license_val_dt" id="license_val_dt" value="<?php if($editFlag==1){echo $jttySr[0]['lic_val_dt'] ; } ?>" />
													</div>
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">Upload Photo <span class="required">*</span></span>
														<input type="file" style="width:150px;" name="photo_img" id="photo_img" />
													</div>	
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">Upload Gate Pass <span class="required">*</span></span>
														<input type="file" style="width:150px;" name="gate_pass_img" id="gate_pass_img" />
													</div>
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">Gate Pass Validity Date <span class="required">*</span></span>
														<input type="date" style="width:150px;" name="gate_pass_val_dt" id="gate_pass_val_dt" value="<?php if($editFlag==1){echo $jttySr[0]['gate_pass_val_dt'] ; } ?>" />
													</div>	
													
												</div>
																								
												<div class="row">
													<div class="col-sm-12 text-center">
														<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
														<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
														<!--input type="submit" value="Save" name="save" class="login_button"-->
											
														<input type="hidden" name="updateFlag" value="<?php echo $updateFlag;?>">	
														<input type="hidden" name="jettyId" value="<?php echo $jettyId;?>">	
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
					<!-- end: page -->
				</section>
			</div>