<script type="text/javascript">
	function chkBlankField()
	{
		if(document.getElementById("securityContNo").value=="" )
		{
			alert("Please fill container No");
			return false
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
					
						<div class="right-wrapper pull-right">
							
						</div>
					</header>

					<!-- start: page -->
						<div class="row">
							<div class="col-lg-12">						
								<section class="panel">
					
									<div class="form-group" align="center"><b>CONTAINER DELIVERY STATUS CHECK </b></div>
									<div class="panel-body" align="center">
										<form name="securityContainerForm" id="securityContainerForm" action="<?php echo site_url("Report/contDlvStatusCheckAction"); ?>" method="POST" onsubmit="return chkBlankField();">						
											<div class="form-group">
												<label class="col-md-3 control-label">&nbsp;</label>
												<div class="col-md-6">		
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:150px;">Container No <span class="required">*</span></span>
														<input type="text" style="width:150px;" id="securityContNo" name="securityContNo" value=""/>
													</div>
												
													
												</div>
												
																								
												<div class="row">
													<div class="col-sm-12 text-center">
														<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
														<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
														<!--input type="submit" value="Save" name="save" class="login_button"-->
	
													</div>													
												</div>
											
												</form>	
												<div class="row">
													<div class="col-sm-12 text-center">
														<?php echo $msg; ?>
	
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