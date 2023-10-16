
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
											<a href="<?php echo site_url('Controller/List') ?>">
												<button style="margin-left: 35%" class="btn btn-primary btn-sm">
													<i class="fa fa-list"></i>
												</button>
											</a>
										</h2>								
									</header-->
				
								<!-- <div class="form-group" align="center"><b>INWARD & OUTWARD CONTAINER REGISTER </b></div> -->
									<div class="panel-body" align="center">
									
					
				<div class="form-group">
					<label class="col-md-3 control-label">&nbsp;</label>
					<!--div class="col-md-6">		
						<div class="input-group mb-md">
							<span class="input-group-addon span_width" style="width:150px;">From Date <span class="required">*</span></span>
								<input type="date" style="width:250px;" id="fromdate" name="fromdate" value="<?php date("Y-m-d"); ?>"/>
						</div>
					
						
					</div-->
					<form name= "myForm" onsubmit="return(validation());" action="<?php echo site_url("gateController/containerRegisterInRegisterView");?>" target="_blank" method="post">

					<div class="col-md-12">		
						<div class="input-group mb-md">
							
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" class="form-control">DATE :<span class="required">*</span></span>
									<input type="date" class="form-control read" id="date" name="date" value="<?php date("Y-m-d"); ?>">

								</div>	
							</div>								
					
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" class="form-control">GATE NO:<span class="required">*</span></span>
									<select name="gate" class="form-control" id="gate" >  
										<option value="">--------Select--------</option>
										<?php for($i=0; $i<count($gateList); $i++){ ?>
											
										<option value="<?php echo $gateList[$i]['GKEY'];?>"><?php echo $gateList[$i]['ID'];?></option>
										
										<?php } ?>
									</select>	

								</div>	
							</div>
									
						</div>
						
							
						</div>
																		
						<div class="row">
							<div class="col-sm-12 text-center">
								<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
								<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								<!--input type="submit" value="Save" name="save" class="login_button"-->

							</div>													
						</div>
						</form>
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
