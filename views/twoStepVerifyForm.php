
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
										<form name= "myForm" onsubmit="return(validate());" action="<?php echo site_url("login/UpdatePhoneNumber");?>" method="post"  enctype="multipart/form-data">
											<input type="hidden" name="" value="">
					
											<div class="form-group">
											<label class="col-md-3 control-label">&nbsp;</label>
												<div class="col-md-6">	
													
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:250px;">User Name: <span class="required">*</span></span>
														<input type="text" name="user_name"  id="user_name" class="form-control" value="<?php echo $login_id;?>" style="width:130px;" readonly>
													</div>
	                                            
													
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:270px;">Two Step Verification: <span class="required">*</span></span>
														<!-- <input type="password" name="confirm_password" id="confirm_password" class="form-control" style="width:130px;"onkeyup="checkPass(); return false;">		
														-->
														
														<select name="two_step_verification" required>
															<
															<option value="">--Select--</option>
															<option value="1"<?php if($select_status=='1'){ ?> selected="true"<?php } ?>>Yes</option>
															<option value="0"<?php if($select_status=='0'){ ?> selected="true"<?php } ?>>No</option>

														</select>
														<!--input type="checkbox" name="verifyEnableState" id="chkPassport" style="width:130px;"  onclick="myFunction()"-->
													</div>
	                                                 
	                                                	
													<!-- <div class="form-group" style="display:none;" id="textvalue">
												
													<span class="input-group-addon span_width" style="width:250px;">Phone Number: <span class="required">*</span></span>
													<input type="text" name="phone_number" id="phone_number" pattern="[0-9]+" title="numbers only, 11 digit" class="form-control" placeholder="Phone Number" minlength="10"  style="width:160px;" maxlength="10" onkeyup="fetchData();"/>
													</div>	 -->
													

													<div class="input-group mb-md"  id="textvalue">
													<span class="input-group-addon span_width" style="width:220px;">Phone Number: <span class="required">*</span></span>
														<!-- <label class="col-md-4 control-label">Phone Number(+880):</label> -->
													<input type="text" name="phone_number" id="phone_number" value="<?php echo $phone_number;?>" pattern="[0-9]+" title="numbers only, 11 digit" class="form-control" placeholder="01xxxxxxxxx" minlength="11"  style="width:160px;" maxlength="11"  onkeyup="fetchData();" required/>
														
													<!-- <div class="col-md-8">
														<input type="text" name="phone_number" id="phone_number" pattern="[0-9]+" title="numbers only, 10 digit" class="form-control" placeholder="Phone Number" minlength="10"  style="width:160px;" maxlength="10" onblur="fetchData();"/>
														</div> -->
													</div>
													
														
													<div class="form-group" style="display:none;" id="changevalue" >								
													<input type="button" class="mb-xs mt-xs mr-xs btn btn-success"id="verify_button"name="verify" value="Verify" onclick="verifyNumber();">
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
													<h3><?php echo $msg;?></h3>
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