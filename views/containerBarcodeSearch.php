<script type="text/javascript">  
	function validate()
	{
		if(document.cont_form.cont_no.value == "")
		{
			alert("Please provide Contaoner Number!");
			document.cont_form.cont_no.focus();
			return false;
		}
		return true ;
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
								
									<div class="form-group" align="center"><b>BARCODE GENERATOR </b></div>
									<div class="panel-body" align="center">
										<form name="cont_form" id="location_form" onsubmit="return(validate());" action="<?php echo site_url("Report/continerBarcodeGeneratePerform");?>" target="_blank" method="post">

											<div class="form-group">
												<label class="col-md-3 control-label">&nbsp;</label>
												<div class="col-md-6">		
													<div class="input-group mb-md">
														<span class="input-group-addon span_width" style="width:150px;">Container No. : <span class="required">*</span>
															<input type="text" style="width:250px;" id="cont_no" name="cont_no" />
													</div>
												</div>
												
																								
												<div class="row">
													<div class="col-sm-12 text-center">
														<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
														<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
														<!--input type="submit" value="Save" name="save" class="login_button"-->
	
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