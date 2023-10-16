<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header> 

  	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/myExportContainerNotFoundReportView'; ?>" target="_blank" id="myform" name="myform">
					<div class="form-group">
						<label class="col-md-2 control-label">&nbsp;</label>
						<div class="col-md-8">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Import Rotation No <span class="required">*</span></span>
								<input type="text" name="ddl_imp_rot_no" id="ddl_imp_rot_no" class="form-control" placeholder="Import Rotation No">
							</div>
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
								<input type="date" name="fromdate" id="fromdate" class="form-control" value="<?php date("Y-m-d"); ?>">
							</div>
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
								<input type="date" name="todate" id="todate" class="form-control" value="<?php date("Y-m-d"); ?>">
							</div>											
						</div>
						
						<div class="col-md-offset-4 col-md-3">
							<div class="radio-custom radio-success">
								<input type="radio" id="options" name="options" value="xl">
								<label for="radioExample3">Excel</label>
							</div>
						</div>
						<div class="col-md-3">
							<div class="radio-custom radio-success">
								<input type="radio" id="options" name="options" value="html" checked>
								<label for="radioExample3">HTML</label>
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
								
							</div>
						</div>
					</div>	
				</form>
			</div>
		</section>
	</div>

</section>