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
								<!--a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a-->
							</h2>								
						</header>
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" 
								action="<?php echo base_url().'index.php/ControllerName/FunctionName'; ?>" 
									onsubmit="return validate()">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Token No <span class="required">*</span></span>
											<input type="text" name="ddl_imp_rot_no" id="ddl_imp_rot_no" class="form-control" 
												placeholder="Token No" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
											<input type="text" name="ddl_cont_no" id="ddl_cont_no" class="form-control" 
												placeholder="Rotation No" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Date <span class="required">*</span></span>
											<input type="date" name="ddl_cont_no" id="ddl_cont_no" class="form-control" 
												placeholder="Rotation No" required>
										</div>
									</div>									
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
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