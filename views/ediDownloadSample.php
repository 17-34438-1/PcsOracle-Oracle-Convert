<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
  <div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form action="<?php echo site_url('uploadExcel/ediDownloadSampleView');?>" method="POST" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Import Rotation No: </span>
										<input type="text" name="rotNo" id="rotNo" class="form-control">
									</div>											
								</div>
							</div>
							<div class="col-md-offset-2 col-md-3"  style="visibility:hidden" >
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="xl" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Download</button>
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