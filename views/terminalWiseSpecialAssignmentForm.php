<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/terminalWiseSpecialAssignment'); ?>" target="_blank" id="myform" name="myform">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Date <span class="required">*</span></span>
										<input type="date" name="date" id="date" class="form-control" value="<?php echo date('Y-m-d');?>">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Terminal <span class="required">*</span></span>
										<select class="form-control" id="terminal" name="terminal">
                                            <option value="CCT">CCT</option>
                                            <option value="NCT">NCT</option>
                                            <option value="GCB">GCB</option>
                                        </select>
									</div>												
								</div>

                                <div class="col-md-offset-4 col-md-2">
                                    <div class="radio-custom radio-success">
                                        <input type="radio" id="fileOptions" name="fileOptions" value="xl">
                                        <label for="radioExample3">Excel</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="radio-custom radio-success">
                                        <input type="radio" id="fileOptions" name="fileOptions" value="html" checked>
                                        <label for="radioExample3">HTML</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="radio-custom radio-success">
                                        <input type="radio" id="fileOptions" name="fileOptions" value="pdf" >
                                        <label for="radioExample3">PDF</label>
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