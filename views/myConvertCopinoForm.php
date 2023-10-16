 <?php if(substr($_SERVER['REMOTE_ADDR'],0,7)=="192.168" or substr($_SERVER['REMOTE_ADDR'],0,4)=="10.1") { ?>
 <script type="text/javascript" src="<?php echo ASSETS_JS_PATH; ?>getagentlocal.js"> </script>
 <?php } else { ?>
 <script type="text/javascript" src="<?php echo ASSETS_JS_PATH; ?>getagent.js"> </script>
 <?php } ?>
 
 <section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/UploadExcel/convertCopinoPerformed'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<div class="row">		
								<div class="input-group mb-md">
									<?php echo @$myUpdateManifestList; ?>
								</div>												
							</div>
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Import Rotation No <span class="required">*</span></span>
									<input type="text" name="ddl_imp_rot_no" id="txt_login" class="form-control" placeholder="Rotation No">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Convert</button>
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
	<?php
		if($mystatus==2)
		{
			echo $body;
		}
	?>
</section>