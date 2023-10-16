<script>
	function calculateTotal(amount)
	{
		var vatAddedAmt = (amount*15)/100;
		var totalAmt = parseFloat(vatAddedAmt)+parseFloat(amount);
		document.getElementById("vat_amount").value=vatAddedAmt;
		document.getElementById("total_amount").value=totalAmt;
	}
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
				
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php echo $msg;?>
								</div>
							</div>
							<form class="form-horizontal form-bordered" method="POST" id="myform" name="myform"
								action="<?php echo site_url("GateController/feesRegistrtaionEntry");?>">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Vehicle Type <span class="required">*</span></span>
											<select class="form-control" name="vehicle_type" id="vehicle_type" required>
												<option value="">--Select--</option>
												<option value="car">Car</option>
												<option value="truck">Truck</option>
												<option value="container">Container</option>
											</select>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Net Amount <span class="required">*</span></span>
											<input type="text" name="fee_amount" id="fee_amount" class="form-control" 
												onblur="calculateTotal(this.value)" placeholder="Fee Amount" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">VAT Amount</span>
											<input type="text" name="vat_amount" id="vat_amount" class="form-control" 
												value="0" placeholder="Total Amount" readonly required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Total </span>
											<input type="text" name="total_amount" id="total_amount" class="form-control" 
												value="0" placeholder="Total Amount" readonly required>
										</div>
									</div>									
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save
											</button>
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