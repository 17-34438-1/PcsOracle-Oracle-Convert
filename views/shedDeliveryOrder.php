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
					<!--form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/lclAssignmentCertifyList'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()" style="padding:12px 20px;"-->
					
					<form class="form-horizontal form-bordered" method="POST" name= "myForm"  action="<?php echo site_url("report/shedDeliveryOrderForm");?>" method="post" target="_blank" style="padding:12px 20px;">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Verify No <span class="required">*</span></span>
									<input type="text" name="verifyNo" id="verifyNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Rotation No">
								</div>								
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
								</div>													
							</div>						
						</div>	
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
