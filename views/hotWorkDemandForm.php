<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<?php
						if(!is_null($this->session->flashdata('success'))){
							echo $this->session->flashdata('success');
						}

						if(!is_null($this->session->flashdata('error'))){
							echo $this->session->flashdata('error');
						}
					?>

					<div class="panel-body">
						<!-- <form class="form-horizontal form-bordered" method="POST" action="<?php //echo base_url('Vesssel/hotWorkDemand'); ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()"> -->
                        <?= form_open();?>
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Vessel Name <span class="required">*</span></span>
										<input type="text" name="vsl_name" id="vsl_name" class="form-control" placeholder="vessel name">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
										<input type="text" name="rotation" id="voy_no" class="form-control" placeholder="rotation ">
									</div>	
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Bearthing Hour <span class="required">*</span></span>
										<input type="text" name="berth_hr" id="berth_hr" class="form-control" placeholder="bearthing no">
									</div>
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Bearthing Date <span class="required">*</span></span>
										<input type="date" name="berth_date" id="berth_date" class="form-control" value="<?php echo date('Y-m-d');?>">
									</div>											
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit" class="mb-xs mt-xs mr-xs btn btn-success">Demand</button>
									</div>													
								</div>
							</div>
                        <?= form_close();?>	
						<!-- </form> -->
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>