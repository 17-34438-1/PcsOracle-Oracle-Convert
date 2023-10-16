<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
  <div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" 
							action="<?php echo base_url().'index.php/Report/dischargeListForMLOreport'; ?>" target="_blank" 
							id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">From Date: <span class="required">*</span></span>
										<input type="date" name="fromDate" id="fromDate" class="form-control" placeholder="Rotation No" value="<?php date("Y-m-d"); ?>" required>
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">To Date: <span class="required">*</span></span>
										<input type="date" name="toDate" id="toDate" class="form-control" placeholder="Container No" 
										value="<?php date("Y-m-d"); ?>" required>
									</div>
									<div class="col-md-offset-2 col-md-3">
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
									<div class="col-md-2">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="pdf" >
											<label for="radioExample3">PDF</label>
										</div>
									</div>
									<!--div class="input-group mb-md">
										<label class="radio-inline"><input type="radio" name="options" id="options" value="xl">EXCEL</label>
										<label class="radio-inline"><input type="radio" name="options" id="options" value="html">HTML</label>
										<label class="radio-inline"><input type="radio" name="options" id="options" value="pdf" checked>PDF</label>
									</div-->											
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
								
						</form>
					</div>
				</section>
			</div>
		</div>

</section>