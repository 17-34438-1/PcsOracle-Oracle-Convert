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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/myIGMBBReportView'; ?>" target="_blank" id="myform" name="myform">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Import Rotation No <span class="required">*</span></span>
									<input type="text" name="ddl_imp_rot_no" id="txt_login" class="form-control" placeholder="Import Rotation No">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Type of Manifest <span class="required">*</span></span>
									<select name="ddl_manifest" id="igm" class="form-control" onchange="myShowOrg();">
										<option value="" selected="selected">--------SELECT--------</option>
										<option value="BB">Break Bulk</option>
										<option value="BTS">Break Bulk TS</option>
										<option value="BNIL">Break Bulk NIL</option>
										<option value="BROB">Break Bulk ROB</option>
										<option value="BRC">Break Bulk Retention Cargo</option>
										<option value="BRC">IGM General Retention Cargo</option>
										<option value="BAMS">Arms,Ammunition and Explosiv</option>
										<option value="BPS">Provision And Store Supply</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Shipping Agent <span class="required">*</span></span>
									<div id="shipping"></div>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" id="lbl_line">MLO Code <span class="required">*</span></span>
									<div id="mlo"></div>
								</div>												
							</div>
												
							<div class="col-md-offset-4 col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="xl" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="html" >
									<label for="radioExample3">HTML</label>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="report" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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