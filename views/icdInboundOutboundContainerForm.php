<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/icdInboundOutboundContainerReportView'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Visit Type <span class="required">*</span></span>
										<select name="visitType" id="visitType" class="form-control">
                                                <option value="">--------Select--------</option>
												<option value="inbound">INBOUND</option>
												<option value="outbound">OUTBOUND</option>
                                        </select>
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Visit ID <span class="required">*</span></span>
										<input type="text" name="vist_id" id="vist_id" class="form-control" placeholder="Visit Id">
									</div>												
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
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