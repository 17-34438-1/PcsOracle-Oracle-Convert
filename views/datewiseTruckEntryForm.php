<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("GateController/datewiseTruckEntryReport"); ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
										<input type="date" name="fromDate" id="fromDate" class="form-control" value="<?php echo date("Y-m-d");?>">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
										<input type="date" name="toDate" id="toDate" class="form-control" value="<?php echo date("Y-m-d");?>">
									</div>												
								</div>

                                <div class="col-md-offset-4 col-md-2">
                                    <div class="radio-custom radio-success">
                                        <input type="radio" id="options" name="options" value="summary" checked>
                                        <label for="radioExample3">Summary</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="radio-custom radio-success">
                                        <input type="radio" id="options" name="options" value="details">
                                        <label for="radioExample3">Details</label>
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
	<!-- end: page -->
</section>
</div>