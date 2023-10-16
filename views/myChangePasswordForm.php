<script type="text/javascript">
  function checkPass()
{
    //Store the password field objects into variables ...
    var new_password = document.getElementById('new_password');
    var confirm_password = document.getElementById('confirm_password');
    //Store the Confimation Message Object ...
    var message = document.getElementById('confirmMessage');
    //Set the colors we will be using ...
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    //Compare the values in the password field 
    //and the confirmation field
    if(new_password.value == confirm_password.value){
        //The passwords match. 
        //Set the color to the good color and inform
        //the user that they have entered the correct password 
        confirm_password.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.innerHTML = "Passwords Match!"
    }else{
        //The passwords do not match.
        //Set the color to the bad color and
        //notify the user.
        confirm_password.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Passwords Not Match!"
    }
}  

   </script>
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
									
									<div class="panel-body" align="center">
										<form name= "myForm" onsubmit="return(validate());" action="<?php echo site_url("login/myPasswordChangeUpdateForm");?>" method="post"  enctype="multipart/form-data">
											<input type="hidden" name="" value="">
					
											<div class="form-group">
												<label class="col-md-3 control-label">&nbsp;</label>
												<div class="col-md-6">		
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">User Name: <span class="required">*</span></span>
														<input type="text" name="user_name"  id="user_name" class="form-control" value="<?php echo $login_id;?>" style="width:130px;" readonly>
													</div>
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">Current Password: <span class="required">*</span></span>
														<input type="password" name="old_password" id="old_password" class="form-control" style="width:130px;" > 
													</div>			
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">New Password:<span class="required">*</span></span>
														<input type="password" name="new_password" id="new_password" value="" class="form-control" style="width:130px;">
													</div>		
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">Confirm Password: <span class="required">*</span></span>
														<input type="password" name="confirm_password" id="confirm_password" class="form-control" style="width:130px;"onkeyup="checkPass(); return false;">														
													</div>			
													
												</div>
												<div class="row">
													<div class="col-sm-12 text-center">
														<span id="confirmMessage" class="confirmMessage">&nbsp;</span>
													</div>													
												</div>
																								
												<div class="row">
													<div class="col-sm-12 text-center">
														<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
														<!--button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button-->
														<input type="submit" class="mb-xs mt-xs mr-xs btn btn-success" value="Update">
													</div>													
												</div>
												<div class="row">
													<div class="col-sm-12 text-center">
													<h5><?php echo $ptitle;?></h5>
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