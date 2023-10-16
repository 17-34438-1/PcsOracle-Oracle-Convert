<script language="JavaScript">
   
</script>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title;?></h2>
					
						<div class="right-wrapper pull-right">
						
						</div>
					</header>

					<!-- start: page -->
						<section class="panel">
							<!--header class="panel-heading">
								<h2 class="panel-title" align="right">
									<a href="<?php echo site_url('/') ?>">
										<button style="margin-left: 35%" class="btn btn-primary btn-sm">
											<i class="fa fa-plus"></i>
										</button>
									</a>									
								</h2>								
							</header-->
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="post" 
								action="<?php echo site_url("report/monthlyYardWiseContainerHandlingView");?>" 
								id="myform" name="myform" onsubmit="return validate()" target="_blank">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">	
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">From Date:</span>
												<input type="date" id="fromDate" name="fromDate" class="form-control" />
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">	
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">To Date:</span>
												<input type="date" id="toDate" name="toDate" class="form-control" />
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
												<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
												<button type="submit" name="View" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
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
						
						
						
						
					<!-- end: page -->
				</section>
			</div>