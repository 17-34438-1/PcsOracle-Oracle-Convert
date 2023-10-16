<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/IgmViewController/downloadBLPerform'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">
								<div class="col-sm-12 text-center">
									Please enter following information.
								</div>													
							</div>
							<div class="col-md-6">									
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Import Rotation No <span class="required">*</span></span>
									<input type="text" name="txt_imp_rot1" id="txt_imp_rot1" class="form-control" placeholder="Import Rotation No">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
									<input type="text" name="txt_bl" id="txt_bl" class="form-control" placeholder="BL No">
								</div>											
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" id="submit" name="Download" class="mb-xs mt-xs mr-xs btn btn-success">OK</button>
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


</section>