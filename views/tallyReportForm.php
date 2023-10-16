 <script type="text/javascript">
   
			function validate()
			{
				if( document.myForm.rotation.value == "" )
				{
					alert( "Please provide rotation no!" );
					document.myForm.rotation.focus() ;
					return false;
				}
				
				if( document.myForm.container.value == "" )
				{
					alert( "Please provide container no!" );
					document.myForm.container.focus() ;
					return false;
				}
				return true ;
			}
</script> 
 
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" name="myform" method="POST" action="<?php echo base_url().'index.php/ShedBillController/tallyReport'; ?>" onsubmit="return(validate());" target="_blank" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Rotation: <span class="required">*</span></span>
										<input type="text" name="rotation" id="rotation" class="form-control" placeholder="Rotation No">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Container: <span class="required">*</span></span>
										<input type="text" name="container" id="container" class="form-control" placeholder="Container No">
									</div>												
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
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