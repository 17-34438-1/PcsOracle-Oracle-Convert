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
					<form class="form-horizontal form-bordered" id = "myform" method="POST" action="<?php echo site_url('report/garmentContInfoList'); ?>" target = "_blank">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Import Rotation No :<span class="required">*</span></span>
									<input type="text" name="ddl_imp_rot_no" id="ddl_imp_rot_no" class="form-control login_input_text" >
								</div>
								<div class="input-group mb-md">
									<label class="checkbox-inline">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="fileOptions" name="fileOptions" value="html"> HTML
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="fileOptions" name="fileOptions" value="pdf"> PDF
									</label>
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
