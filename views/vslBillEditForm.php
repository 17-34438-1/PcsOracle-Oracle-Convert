<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('VesselBill/vslBillEdit'); ?>" id="myform" name="myform" onsubmit="return validate()">
                        	<input type="hidden" name="draft" value="<?php echo $draft;?>"/>
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Previous Bill No <span class="required">*</span></span>
										<input type="text" name="prevBill" id="prevBill" class="form-control" value="<?php echo $billNo;?>" readonly>
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">New Bill No <span class="required">*</span></span>
										<input type="text" name="newBill" id="newBill" class="form-control" placeholder="New Bill No">
									</div>												
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Update</button>
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