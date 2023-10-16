<script>
	function validation()
	{
		// alert(document.getElementById('vslType').value);
		
		if(document.getElementById('fromDate').value=="")
		{
			alert("Please provide From Date.");
			return false;
		}
		else if(document.getElementById('toDate').value=="")
		{
			alert("Please provide To Date.");
			return false;
		}
		// else if((document.getElementById('vslTypeCV').checked==false) || (document.getElementById('vslTypeNE').checked==false))
		// {
			// alert("Please provide Vessel Type.");
			// return false;
		// }
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
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Vessel/forwardedVslSummaryReport'); ?>"
						id="vslForwardSummaryForm" name="vslForwardSummaryForm" target="_blank" onsubmit="return validation()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
										<input type="date" name="fromDate" id="fromDate" style="width:180px" class="form-control" value="<?php echo date('Y-m-d');?>">

									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
										<input type="date" name="toDate" id="toDate" style="width:180px" class="form-control" value="<?php echo date('Y-m-d');?>">
									</div>	
									
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Vessel Type <span class="required">*</span></span>
										<select style="width:180px" name="vslType" id="vslType" class="form-control">
											<option value="">--Select--</option>
											<option value="Container Vessel" >Container Vessel</option>
											<option value="Not Entering Vessell" >Not Entering Vessel</option>
										
										</select>
									</div>	
									
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Report Type <span class="required">*</span></span>
										
										<select style="width:180px" name="rptType" id="rptType" class="form-control">
											<option value="">--Select--</option>
											<option value="summary" >Summary</option>
											<option value="detail" >Detail</option>
										
										</select>
									</div>												
								</div>
													
								<!--div class="col-md-10" align="center">
									<label class="checkbox-inline">
										<input type="radio" id="vslTypeCV" name="vslType" value="Container Vessel"> Container Vessel
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="vslTypeNE" name="vslType" value="Not Entering Vessel"> Not Entering Vessel
									</label>
								</div-->
								
								<!--div class="col-md-10" align="center">
									<label class="checkbox-inline">
										<input type="radio" id="rptType" name="rptType" value="summary"> Summary
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="rptType" name="rptType" value="detail"> Detail
									</label>
								</div-->
							
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