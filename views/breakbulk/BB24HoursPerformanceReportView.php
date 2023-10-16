
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<!-- <header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header> -->
				<div class="panel-body">					
					<form class="form-horizontal form-bordered" name= "performanceReport" onsubmit="return(validate());" action="<?php echo site_url("breakbulk/BBReportController/PerformanceReportPdf");?>" target="_blank" method="post">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width"> Date :<span class="required">*</span></span>
									<input type="date" style="width:200px;" id="todate" name="todate"   value = "<?php echo date('Y-m-d'); ?>" class="form-control login_input_text" required/>
								</div>													
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show Report</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
