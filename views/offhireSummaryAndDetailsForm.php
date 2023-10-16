
   <script>
	function rtnFunc(val)
	{
	//	document.getElementById("offhire_date").disabled=true;
		if(document.offhireSummaryAndDetailsForm.offhire_date.value=="")
		{
			alert( "Please provide date!" );
			document.offhireSummaryAndDetailsForm.offhire_date.focus() ;
			return false;
		}
	}
  </script>


<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/offhireSummaryAndDetailsView'; ?>" target="_blank" id="myform" name="myform" onsubmit="return(rtnFunc());">
					<div class="form-group">
						<label class="col-md-2 control-label">&nbsp;</label>
						<div class="col-md-8">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Search Date <span class="required">*</span></span>
								<input type="date" name="offhire_date" id="offhire_date" class="form-control" value="<?php date("Y-m-d"); ?>">
							</div>											
						</div>

						<div class="col-md-offset-3 col-md-2">
							<div class="radio-custom radio-success">
								<input type="radio" id="options" name="options" value="xl">
								<label for="radioExample3">Excel</label>
							</div>
						</div>
						<div class="col-md-2">
							<div class="radio-custom radio-success">
								<input type="radio" id="options" name="options" value="html" checked>
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
								<button type="submit" name="submit" value="Details" class="mb-xs mt-xs mr-xs btn btn-success">Details</button>
								<button type="submit" name="submit" value="Summary" class="mb-xs mt-xs mr-xs btn btn-success">Summary</button>
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

</section>