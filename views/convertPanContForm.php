 <?php if(substr($_SERVER['REMOTE_ADDR'],0,7)=="192.168" or substr($_SERVER['REMOTE_ADDR'],0,4)=="10.1") { ?>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>getagentlocal.js"> </script>
 <?php } else { ?>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>getagent.js"> </script>
 <?php } ?> 
 
 
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title; ?></h2>
	
		<div class="right-wrapper pull-right">
		
		</div>
	</header>

	<!-- start: page -->
	<section class="panel">
		<header class="panel-heading">
			<!--h2 class="panel-title" align="right">
				<a href="<?php echo site_url('misReport/mis_equipment_indent_list') ?>">
					<button style="margin-left: 35%" class="btn btn-primary btn-sm">
						<i class="fa fa-list"></i> GO TO INDENT LIST
					</button>
				</a>									
			</h2-->
			<?php echo $msg; ?>
		</header>
		<div class="panel-body">
			<form class="form-horizontal form-bordered" method="POST" id="myForm"
				action="<?php echo site_url('uploadExcel/convertPandContPerformed') ?>">
				<div class="form-group">
					<div class="col-md-offset-3 col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Visit Id:</span>
							<input type="text" name="visit" id="txt_login" class="form-control">
						</div>
					</div>
					<div class="col-sm-12 text-center">
						<!--button class="mb-xs mt-xs mr-xs btn btn-success">Convert</button-->
						<input type="submit" value="Convert" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success"/>
					</div>													
				</div>
				<div class="form-group">
					<div class="col-md-offset-3 col-md-6">
						<div class="input-group mb-md">
							<?php
								if($mystatus==2)
								{
									echo $body;
								}
							?>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<!-- end: page -->
</section>
</div>