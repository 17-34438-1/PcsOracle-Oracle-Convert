<script>
	function rtnFunc(val)
	{
		
		if(val=="rotation")
		{
			document.getElementById("fromdate").disabled=true;
			document.getElementById("fromTime").disabled=true;
			document.getElementById("todate").disabled=true;
			document.getElementById("toTime").disabled=true;
			document.getElementById("ddl_imp_rot_no").disabled=false;
		}		
		else if(val=="date")
		{
			document.getElementById("fromdate").disabled=false;
			document.getElementById("fromTime").disabled=false;
			document.getElementById("todate").disabled=false;
			document.getElementById("toTime").disabled=false;
			document.getElementById("ddl_imp_rot_no").disabled=true;
		}
		else
		{
			document.getElementById("fromdate").disabled=true;
			document.getElementById("fromTime").disabled=true;
			document.getElementById("todate").disabled=true;
			document.getElementById("toTime").disabled=true;
			document.getElementById("ddl_imp_rot_no").disabled=false;
		}
	}
</script>

	<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>


			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title" align="right">
							
							</h2>								
						</header>
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" id="myform"
								action="<?php echo site_url('report/chkExportLoadedContainerMloWise') ?>" target="_blank">
							
								<div class="form-group">
									<div class="col-md-offset-2 col-md-8" style="text-align: center">
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Rotation No:</span>
												<input type="text" name="ddl_imp_rot_no" id="ddl_imp_rot_no" class="form-control" placeholder="Rotation No:" value="">
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-offset-2 col-md-4" style="text-align: center">
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">From Date</span>
												<input type="date" name="fromdate" id="fromdate" class="form-control" value="">
											</div>
										</div>
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">To Date</span>
												<input type="date" name="todate" id="todate" class="form-control" value="" tabindex="3">
											</div>
										</div>
									</div>
									<div class="col-md-4" style="text-align: center">
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">From (24 Hrs)</span>
												<input type="text" name="fromTime" id="fromTime" data-plugin-timepicker class="form-control" data-plugin-options='{ "showMeridian": false }'>
											</div>
										</div>
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">To (24 Hrs)</span>
												<input type="text" name="toTime" id="toTime" data-plugin-timepicker class="form-control" data-plugin-options='{ "showMeridian": false }'>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
									</div>
									<div class="col-md-3">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="xl" checked>
											<label for="radioExample3">Excel</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="html" >
											<label for="radioExample3">HTML</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-offset-4 col-sm-2 text-center">
										<button type="submit" name="detail" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary">Detail</button>
									</div>
									<div class="col-sm-2 text-center">
										<button type="submit" name="summary" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary">Summary</button>
									</div>	
								</div>
							</form>
						</div>
					</section>
				</div>
			</div>	
	
	</section>
	</div>