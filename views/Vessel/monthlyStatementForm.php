<script>
	function validate()
	{
		if(document.getElementById('monthlyStatementMonth').value=="")
		{
			alert("Please provide month");
			return false;
		}
		else if(document.getElementById('monthlyStatementYear').value=="")
		{
			alert("Please provide year");
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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('VesselBill/monthlyStatement'); ?>"  id="monthlyStatementForm" name="monthlyStatementForm" target="_blank" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Month <span class="required">*</span></span>
									<select name="monthlyStatementMonth" id="monthlyStatementMonth" class="form-control" style="width:180px">
										<option value="">Select</option>
										<option value="1">January</option>
										<option value="2">February</option>
										<option value="3">March</option>
										<option value="4">April</option>
										<option value="5">May</option>
										<option value="6">June</option>
										<option value="7">July</option>
										<option value="8">August</option>
										<option value="9">September</option>
										<option value="10">October</option>
										<option value="11">November</option>
										<option value="12">December</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Year <span class="required">*</span></span>									
									<select name="monthlyStatementYear" id="monthlyStatementYear" class="form-control" style="width:180px">
										<option value="">Select</option>
										<option value="2014">2014</option>
										<option value="2015">2015</option>
										<option value="2016">2016</option>
										<option value="2017">2017</option>
										<option value="2018">2018</option>
										<option value="2019">2019</option>
										<option value="2020">2020</option>
										<option value="2021">2021</option>
										<option value="2022">2022</option>
									</select>
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
</section>
</div>