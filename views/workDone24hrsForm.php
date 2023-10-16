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

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
										
  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/workDone24hrsList'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date <span class="required">*</span></span>
									<input type="date" name="work_date" id="work_date" class="form-control" value="<?php date("Y-m-d"); ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
									<input type="text" name="rotation_no" id="rotation_no" class="form-control" placeholder="Rotation No">
								</div>												
							</div>

							<div class="col-md-offset-3 col-md-2">
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
							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="pdf" >
									<label for="radioExample3">PDF</label>
								</div>
							</div>
																		
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success login_button">Show</button>
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