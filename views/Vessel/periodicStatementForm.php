<script>
	function validate()
	{
		if(document.getElementById('periodicFromDate').value=="")
		{
			alert("Please provide from date");
			return false;
		}
		else if(document.getElementById('periodicToDate').value=="")
		{
			alert("Please provide to date");
			return false;
		}
		else
		{
			return true;
		}			
	}
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('VesselBill/periodicStatement'); ?>"  id="periodicStatementForm" name="periodicStatementForm" target="_blank" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
									<input type="date" name="periodicFromDate" id="periodicFromDate" class="form-control" style="width:180px" />
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
									<input type="date" name="periodicToDate" id="periodicToDate" class="form-control" style="width:180px" />
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">									
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
</section>
</div>