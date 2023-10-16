
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
									
				
								<!-- <div class="form-group" align="center"><b>GATE WISE INWARD OUTWARD CONTAINER REGISTER REPORT </b></div> -->
									<div class="panel-body" align="center">
									
					
				<div class="form-group">
					<label class="col-md-3 control-label">&nbsp;</label>
					<!--div class="col-md-6">		
						<div class="input-group mb-md">
							<span class="input-group-addon span_width" style="width:150px;">From Date <span class="required">*</span></span>
								<input type="date" style="width:250px;" id="fromdate" name="fromdate" value="<?php date("Y-m-d"); ?>"/>
						</div>
					
						
					</div-->
					<form name= "myForm" onsubmit="return(validation());" action="<?php echo site_url("GateController/gateWiseContainerRegisterView");?>" target="_blank" method="post">

						<div class="col-md-12">		
							<div class="input-group mb-md col-md-12">
								</div class="row">
									<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" >DATE :<span class="required">*</span></span>
											<input type="date" class="form-control"  id="date" name="date" >

										</div>	
									</div>								

									<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" >REGISTER TYPE:<span class="required">*</span></span>
											<select name="registerType" class="form-control" id="registerType">  
												<option value="">--------Select--------</option>
												<option value="inward">INWARD</option>
												<option value="outward">OUTWARD</option>
												
											</select>	

										</div>	
									</div>

									<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" class="form-control">GATE NO:<span class="required">*</span></span>
											<select name="gate" class="form-control" id="gate">  
												<option value="">--------Select--------</option>
												<?php for($i=0; $i<count($gateList); $i++){ ?>
													
												<option value="<?php echo $gateList[$i]['GKEY'];?>"><?php echo $gateList[$i]['ID'];?></option>
												
												<?php } ?>
											</select>	

										</div>	
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-4">
									</div>

									<div class="col-md-2">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="xl" checked>
											<label for="radioExample3">Excel</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="html" >
											<label for="radioExample3">HTML</label>
										</div>
									</div>
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
