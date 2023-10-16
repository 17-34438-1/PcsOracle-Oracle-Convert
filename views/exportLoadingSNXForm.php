<script>
	function chkConfirm()
	{
		var rot = document.getElementById("rot_no").value;
		if (confirm("Do you want to proceed ?") == true)
			{
				document.getElementById("rot_no_for_final").value = document.getElementById("rot_no").value;
				document.getElementById("rot_no_imp_discharge").value = document.getElementById("rot_no").value;
				return true ;
			}
		else
			{
				return false;
			}
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
							<form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data" 
								onsubmit="return chkConfirm();" action="<?php echo site_url("Report/exportLoadingSNX");?>">
								<div class="form-group">
									<input type="hidden" name="frmType" id="frmType" value="<?php echo $frmType;?>">
									<div class="col-md-6 col-md-offset-3">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												ROTATION NO <span class="required">*</span>
											</span>
											<input type="text" name="rot_no" id="rot_no" class="form-control" required>
										</div>									
									</div>
									<div class="row">
										<div class="col-md-6 col-sm-12 text-right">
											<button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary">
												<input type="hidden" name="prepare_btn" id="prepare_btn" value="prepare">
												INBOUND EXPORT SNX (PREPARE)
											</button>
										</div>
										</form>
										<form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data" 
											onsubmit="return chkConfirm();" action="<?php echo site_url("Report/exportLoadingSNX");?>">
											<div class="col-md-6 col-sm-12 text-left">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">
													<input type="hidden" name="final_btn" id="final_btn" value="final">
													<input type="hidden" name="rot_no_for_final" id="rot_no_for_final" required>
													INBOUND EXPORT SNX (FINAL)
												</button>
											</div>
										</form>
										<!--form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data" 
											onsubmit="return chkConfirm();" action="<?php echo site_url("Report/exportLoadingSNX");?>">
											<div class="col-md-4 col-sm-12 text-left">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary">
													<input type="hidden" name="imp_disc_btn" id="imp_disc_btn" value="final">
													<input type="hidden" name="rot_no_imp_discharge" id="rot_no_imp_discharge" required>
													IMPORT DISCHARGE SNX 
												</button>
											</div>
										</form-->
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											
										</div>
									</div>
								</div>	
							
						</div>
					</section>
			
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>