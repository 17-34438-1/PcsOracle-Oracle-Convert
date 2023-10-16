<script type="text/javascript">
	function validate()
	{
		if( document.myForm.date.value == "" )
		{
			alert( "Please provide date!" );
			document.myForm.fromdate.focus() ;
			return false;
		}

		if( document.myForm.todate.value == "" )
		{
			alert( "Please provide todate!" );
			document.myForm.todate.focus() ;
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
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal form-bordered" name= "myForm" onsubmit="return(validate());" action="<?php echo site_url("report/last24HrPositionFormPerform");?>" target="_blank" method="post">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">										
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date <span class="required">*</span></span>									
									<input type="date" id="date" name="date" class="form-control login_input_text" />
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Unit <span class="required">*</span></span>
									<select name="unit" id="unit" >
										<option value="">---Select---</option>
										<option value="UNIT-1">Unit-1</option> 
										<option value="UNIT-2">Unit-2</option> 
										<option value="UNIT-3">Unit-3</option> 
										<option value="UNIT-4">Unit-4</option> 
										<option value="UNIT-5">Unit-5</option> 
										<option value="NCY">NCY</option> 
										<option value="ICD">ICD</option> 
								   </select>
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
