<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('uploadExcel/last24hrPerformancePdfUpload'); ?>" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<?php echo $msg; ?>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" style="width:220px;">PERFORMANCE DATE : <em>&nbsp;</em><span class="required">*</span></span>
									<input type="date" style="width:160px;" id="perform_date" name="perform_date" value="" class="form-control login_input_text"/>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">MANUAL REPORT UPLOAD  :<em>&nbsp;</em><span class="required">*</span></span>
									<input type="file" style="width:250px;" id="manual_file" name="manual_file" value="" class="form-control login_input_text"/>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">CTMS REPORT UPLOAD  :<em>&nbsp;</em><span class="required">*</span></span>
									<input type="file" style="width:250px;" name="ctms_file" id="ctms_file" value="" class="form-control login_input_text"/>
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Upload</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
