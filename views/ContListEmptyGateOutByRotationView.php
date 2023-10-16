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
						<form class="form-horizontal form-bordered" id="myform" method="POST" action="<?php echo site_url('report/contListEmptyGateOutByRotationDownloadView') ?>"
						target="_blank">
						
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">										
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">From Date:</span>
										<input type="date" name="fromDt" id="fromDt" class="form-control" value="<?php echo date("yy/m/d"); ?>">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">To Date:</span>
										<input type="date" name="toDt" id="toDt" class="form-control" value="<?php echo date("yy/m/d"); ?>">
									</div>
									<div class="col-md-offset-2 col-md-2">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="xl" checked>
											<label for="radioExample3">Excel</label>
										</div>
									</div>
									<div class="col-md-offset-3 col-md-3">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="html">
											<label for="radioExample3">HTML</label>
										</div>
									</div>
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
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