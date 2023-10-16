<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/movementReport'); ?>" target="_blank" id="myform" name="myform">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
										<input type="date" name="fromDate" id="fromDate" style="width:180px" class="form-control" value="<?php echo date('Y-m-d');?>">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
										<input type="date" name="toDate" id="toDate" style="width:180px" class="form-control" value="<?php echo date('Y-m-d');?>">
									</div>												
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php echo $msg;?>
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