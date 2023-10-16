<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
	<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form action="<?php echo site_url('uploadExcel/upload_copern_copino');?>" method="POST" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<?php echo $msg; ?>
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">ROTATION </span>
										<input type="text" name="rotation" id="rotation" class="form-control" required>
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">UPLOAD COPARN </span>
										<input type="file" name="file" id="file" class="form-control" required>
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">UPLOAD COPINO </span>
										<input type="file" name="copinofile" id="copinofile" class="form-control" required>
									</div>											
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Save & Upload</button>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									
								</div>
							</div>
						</form>
					</div>
				</section>
			</div>
		</div>
</section>