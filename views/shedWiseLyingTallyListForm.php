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
								<a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a>
							</h2>								
						</header-->
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" 
								action="<?php echo base_url().'index.php/ShedBillController/shedWiseLyingTallyListSearch'; ?>" 
								target="_blank" id="myform" name="myform" onsubmit="return validate()">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">From Date : <span class="required">*</span></span>
											<input type="date" name="from_date" id="from_date" class="form-control" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">To Date : <span class="required">*</span></span>
											<input type="date" name="to_date" id="to_date" class="form-control" required>
										</div>
									<?php if($this->session->userdata('login_id')=='admin') { ?>	
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Shed : <span class="required">*</span></span>
											 <select name="shed_no" id="shed_no"; style="width:300px">  
												<option value="">--Select---</option>
												<option value="CFS/NCT">CFS/NCT</option> 
												<option value="CFS/CCT">CFS/CCT</option> 
												<option value="13 Shed">13 Shed</option> 
												<option value="12 Shed">12 Shed</option> 
												<option value="9 Shed">9 Shed</option> 
												<option value="8 Shed">8 Shed</option> 
												<option value="7 Shed">7 Shed</option> 
												<option value="6 Shed">6 Shed</option> 
												<option value="N Shed">N Shed</option> 
												<option value="dshed">D Shed</option> 
												<option value="pshed">P Shed</option> 	
											</select>
										</div>
								<?php } ?>		
									</div>	
															
									
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											
										</div>
									</div>
								</div>	
							</form>
							<hr/>	
						</div>
					</section>
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>