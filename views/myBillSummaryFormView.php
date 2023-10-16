<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form id='myform' class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/myBillSummaryviews'); ?>" >
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date: <span class="required">*</span></span>
									<input type="text" style="width:150px;" id="fromdate" name="fromdate" value="<?php date("Y-m-d"); ?>" class="form-control login_input_text"/>
									<script>
										$(function() 
										{
											$( "#fromdate" ).datepicker({
											changeMonth: true,
											changeYear: true,
											dateFormat: 'yy-mm-dd', // iso format
											});
										});
									</script>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date: <span class="required">*</span></span>
									<input type="text" style="width:150px;" id="todate" name="todate" value="<?php date("Y-m-d"); ?>"/>
									<script>
										$(function() 
										{
											$( "#todate" ).datepicker({
												changeMonth: true,
												changeYear: true,
												dateFormat: 'yy-mm-dd', // iso format
											});
										});
									</script>
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Click</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
