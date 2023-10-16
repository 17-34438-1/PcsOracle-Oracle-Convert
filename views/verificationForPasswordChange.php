<script type="text/javascript">
 

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
										<form name= "myForm" onsubmit="return(validate());" action="<?php echo site_url("login/verify_with_otp");?>" method="post"  enctype="multipart/form-data">
											<input type="hidden" name="" value="">
					
											<div class="form-group">
												<label class="col-md-3 control-label">&nbsp;</label>
												<div class="col-md-6">		
													<div class="input-group mb-md" style="display:none;" >
                                                    <span class="input-group-addon span_width">Phone Number: </span>
                                                    <input type="hidden"  id="phone_number" class="form-control" name="phone_number" value="<?php echo $phone_number; ?>"/>
													</div>
                                                    <div class="input-group mb-md">
                                                    <span class="input-group-addon span_width">Verify Code: </span>
                                                    <input type="text"  id="varifycode" class="form-control" name="varifycode"/>
													</div>
															
													
												</div>
												
																								
												<div class="row">
													<div class="col-sm-12 text-center">
														<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
														<!--button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button-->
														<input type="submit" class="mb-xs mt-xs mr-xs btn btn-success" value="Next">
													</div>													
												</div>
												<div class="row">
													<div class="col-sm-12 text-center">
													<h5  style="color:red;"><?php echo $ptitle;?></h5>
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