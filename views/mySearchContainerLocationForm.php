	<script>
			
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
									<header class="panel-heading">
																	
									</header>
									<div class="panel-body">
										<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/mySearchContainerLocation') ?>" target="_blank">
											<input type="hidden" name="" value="">
											<input type="hidden" name="" value="">
											<div class="form-group">
												<label class="col-md-3 control-label">&nbsp;</label>
												<div class="col-md-6">		
													
													<div class="input-group mb-md">
														<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
														<input type="text" name="containerLocation" id="containerLocation" class="form-control" placeholder="Container No">
													</div>												
												</div>
																								
												<div class="row">
													<div class="col-sm-12 text-center">
														<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
														<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
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