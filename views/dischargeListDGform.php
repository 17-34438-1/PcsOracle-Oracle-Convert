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
						<form class="form-horizontal form-bordered" id="myform" method="POST" action="<?php echo site_url('report/dgDischargeListPerform') ?>"
						target="_blank">
						
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Imp Rotation Number :</span>
										<input type="text" name="rotation" id="rotation" id="txt_login" class="form-control" placeholder="">
									</div>
									<div class="col-md-offset-2 col-md-2">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="xl">
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
										<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">
											Show
										</button>
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