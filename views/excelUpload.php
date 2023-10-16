<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
	<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form action="<?php echo site_url('uploadExcel/upload');?>" method="POST" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<?php echo $msg; ?>
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Browse Excel File: </span>
										<input type="file" name="file" id="file" class="form-control">
									</div>											
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Upload</button>
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