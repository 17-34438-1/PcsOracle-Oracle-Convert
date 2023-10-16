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
						<form class="form-horizontal form-bordered" id="myform" name="myform" onsubmit="return validate()" method="POST" action="<?php echo site_url('report/cirReportView') ?>"
						target="_blank">
						
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Date:</span>
										<input type="date" name="fromdate1" id="fromdate1" class="form-control" value="<?php echo date("yy/m/d"); ?>">
									</div>
									<div class="input-group mb-md">														
										<span class="input-group-addon span_width">Type:</span>
										<select name="yard_no1" id="yard_no1" class="form-control" required>
											<option value="" selected>---Select---</option>
											<option value="IMPRT" label="IMPRT">IMPRT</option>
											<option value="EXPRT" label="EXPRT">EXPRT</option>
										</select>														
									</div>
									
									<div class="col-md-offset-2 col-md-2">
										<div class="radio-custom radio-success">
											<input type="radio" id="fileOptions" name="fileOptions" value="xl">
											<label for="radioExample3">Excel</label>
										</div>
									</div>
									<div class="col-md-offset-3 col-md-3">
										<div class="radio-custom radio-success">
											<input type="radio" id="fileOptions" name="fileOptions" value="html" checked>
											<label for="radioExample3">PDF</label>
										</div>
									</div>
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
					</div>
				</section>
		
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>