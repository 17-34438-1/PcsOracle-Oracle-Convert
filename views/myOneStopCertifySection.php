<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/oneStopIgmCertifyList'; ?>" target="" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									
								</div>
							</div>

							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
									<input type="text" name="ddl_imp_cont_no" id="ddl_imp_cont_no" class="form-control" placeholder="Container No">
								</div>
								<div class="input-group mb-md">
									<span class="">OR </span>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
									<input type="text" name="ddl_imp_bl_no" id="ddl_imp_bl_no" class="form-control" placeholder="BL No">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
								<?php echo @$msg; ?>
								</div>
							</div>
						</div>	
					</form>
				</div>
			</section>
		</div>
	</div>	

</section>