<script>
	function validate()
		{
			//alert("OK");
			if( document.myform.work_date.value == "" )
			 {
				alert( "Please provide Date!" );
				document.myform.work_date.focus() ;
				return false;
			 }
			 else if( document.myform.rotation_no.value == "" )
			 {
				alert( "Please provide Rotation No!" );
				document.myform.rotation_no.focus() ;
				return false;
			 }
			 else{
				 return( true );
			 }
		}
</script>
 <?php //if($_POST['options']=='html'){?>
 	<?php  
		// } else if($_POST['options']=='xl'){
		//header("Content-type: application/octet-stream");
		//header("Content-Disposition: attachment; filename=Export_Container_Gate_In.xls;");
		//header("Content-Type: application/ms-excel");
		//header("Pragma: no-cache");
		//header("Expires: 0"); }
	?>

	<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title" align="right">
								<!--a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a-->
							</h2>								
						</header>
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST"
								action="<?php echo site_url('report/exportContainerGateInList/') ?>" target="_blank" onsubmit="return validate()">
							
								<div class="form-group">
									<div class="col-md-offset-3 col-md-6" style="text-align: center">
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Rotation</span>
												<input type="text" name="rotation_no" id="rotation_no" class="form-control" placeholder="Enter Rotation no:" value="" required>
											</div>
										</div>
									</div>
									<div class="col-md-offset-4 col-md-3">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="xl" checked>
											<label for="radioExample3">Excel</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="html" >
											<label for="radioExample3">HTML</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
									</div>													
								</div>
							</form>
						</div>
					</section>
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>