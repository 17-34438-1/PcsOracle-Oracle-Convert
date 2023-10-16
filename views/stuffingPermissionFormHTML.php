<!--link rel="stylesheet" href="<?php echo CSS_PATH; ?>jquery.timepicker.min.css" type="text/css"/-->
<!--script type="text/javascript" src="<?php echo JS_PATH; ?>jquery.timepicker.min.js"></script-->
<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>stylesheets/jquery.timepicker.min.css" />
<script src="<?php echo ASSETS_PATH; ?>javascripts/jquery.timepicker.min.js"></script>

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
								<!--a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a-->
							<!--/h2>								
						</header-->
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/stuffingPermissionPerform') ?>" onsubmit="return(validate());">
							
								<div class="form-group">
									<div class="col-md-offset-3 col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Offdock Name:</span>
											<select name="offdock" id="offdock" class="form-control">
												<option value="">--SELECT--</option>
												<?php
												include("FrontEnd/mydbPConnectionn4.php");
												$sql_offdock_list="select id,name from ctmsmis.offdoc";
												$rslt_offdock_list=mysqli_query($con_sparcsn4,$sql_offdock_list);
												while($offdock_list=mysqli_fetch_object($rslt_offdock_list))
												{
												?>
													<option value="<?php echo $offdock_list->id; ?>"><?php echo $offdock_list->name; ?></option>
												<?php
												}
												?>
											</select> 
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Date:</span>
											<input type="date" name="stuffing_date" id="stuffing_date" value="<?php echo $toDate; ?>" class="form-control" readonly>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Time:</span>
											<input type="text" name="time" id="time" class="form-control">
											<script>
												$('#time').timepicker({ timeFormat: 'HH:mm:ss' });
											</script>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
										<!--input type="submit" value="Search" class="mb-xs mt-xs mr-xs btn btn-success"/-->
									</div>													
								</div>
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<?php echo $msg;?>
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