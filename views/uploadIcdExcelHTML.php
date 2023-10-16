<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
	
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal form-bordered" action="<?php echo site_url('uploadExcel/uploadIcdExcelPerform');?>" method="POST" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<?php echo $msg; ?>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Browse Excel File<span class="required">*</span></span>
									<input type="file" name="file" id="file" class="form-control login_input_text" >
								</div>								
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit" class="mb-xs mt-xs mr-xs btn btn-success">Upload</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
