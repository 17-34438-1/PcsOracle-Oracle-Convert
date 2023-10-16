

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
								action="<?php echo site_url('report/doReportView/') ?>" target="_blank">
							
								<div class="form-group">
									<div class="col-md-offset-3 col-md-7" style="text-align: center">
										<div class="col-md-10">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Bill Of Entry No :</span>
												<input type="text" name="be_no" id="be_no" class="form-control" placeholder="Bill Of Entry No :" value="">
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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