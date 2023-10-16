<script>
	function cngCollectBy(method){
		if(method == "online")
		{
			document.getElementById("collectBy").disabled = true;
		}
		else
		{
			document.getElementById("collectBy").disabled = false;
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
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("GateController/gateCollectionReport"); ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">From Date<span class="required">*</span></span>
										<input type="date" name="fromDate" id="fromDate" class="form-control" value="<?php echo date("Y-m-d"); ?>" required>
									</div>

                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">To Date<span class="required">*</span></span>
                                        <input type="date" name="toDate" id="toDate" class="form-control" value="<?php echo date("Y-m-d"); ?>" required>
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Payment Method <span class="required">*</span></span>
										<select name="payMethod" id="payMethod" class="form-control" onchange="cngCollectBy(this.value)">
                                            <option value="">-- All --</option>
											<option value="cash">Cash</option>											
											<option value="online">Online</option>											
                                        </select>
									</div>

									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Collected By <span class="required">*</span></span>
										<select name="collectBy" id="collectBy" class="form-control">
                                            <option value="">-- Select --</option>
											<option value="gate1counter">Gate 1 Counter</option>
											<option value="gate2counter">Gate 2 Counter</option>
                                            <option value="gate3counter">Gate 3 Counter</option>
											<option value="gate5counter">Gate 5 Counter</option>
											<option value="yardcounter">Yard Counter</option>
											<option value="cparcounter">CPAR Counter</option>
											<option value="cct2counter">CCT 2 Counter</option>
											<option value="nct1counter">NCT 1 Counter</option>
											<option value="nct2counter">NCT 2 Counter</option>
											<option value="nct3counter">NCT 3 Counter</option>
											<option value="ofycounter">OFY Counter</option>
											<option value="shed13counter">SHED 13 Counter</option>
											<option value="Shed12counter">SHED 12 Counter</option>
											<option value="Shed09counter">Shed 9 Counter</option>
                                        </select>
									</div>

                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Slot <span class="required">*</span></span>
										<div class="col-md-4">
                                            <div class="radio-custom radio-success">
                                                <input type="radio" id="slot" name="slot" value="slot 1">
                                                <label for="radioExample3">Slot 1</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="radio-custom radio-success">
                                                <input type="radio" id="slot" name="slot" value="slot 2">
                                                <label for="radioExample3">Sot 2</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="radio-custom radio-success">
                                                <input type="radio" id="slot" name="slot" value="slot 3" >
                                                <label for="radioExample3">Slot 3</label>
                                            </div>
                                        </div>
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