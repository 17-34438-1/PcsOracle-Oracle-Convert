<script>
	function validate()
      {
		  //alert("OK");
		if( document.myform.rotation.value == "" )
         {
            alert( "Please provide Rotation!" );
            document.myform.rotation.focus() ;
            return false;
         }
         return( true );
      }
</script>

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
						<!--header class="panel-heading">
							<h2 class="panel-title" align="right">
								<a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a>
							</h2>								
						</header-->
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" name="myform" id="myform"
								action="<?php echo site_url('report/containerSearchByRotationAppsList') ?>" target="_blank">
							
								<div class="form-group">
									<div class="col-md-offset-2 col-md-7">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation:</span>
											<input type="text" name="rotation" id="rotation" class="form-control" value="">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-2">
									</div>
									<div class="col-md-3">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="xl">
											<label for="radioExample3">Excel</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="html" checked>
											<label for="radioExample3">HTML</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="pdf" >
											<label for="radioExample3">PDF</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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