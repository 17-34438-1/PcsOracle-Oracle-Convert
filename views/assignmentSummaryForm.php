<script type="text/javascript">
	function chkBlankField()
	{
		if(document.getElementById("assignment_date").value=="" )
		{
			alert("Please fill assignment_date");
			return false
		}
		else
		{
			return true;
		}
	}
</script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>AdvancedCalender.js"></script>


<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/assignment_summary_action'; ?>" target="_blank" id="fcl_cont_dlv_assignment_summary_form" name="fcl_cont_dlv_assignment_summary_form" onsubmit="return chkBlankField();">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Assignment Date <span class="required">*</span></span>
									<input type="date" name="assignment_date" id="assignment_date" class="form-control" value="<?php date("Y-m-d"); ?>">
								</div>												
							</div>

							<div class="col-md-offset-4 col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="html" checked>
									<label for="radioExample3">HTML</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="pdf" >
									<label for="radioExample3">PDF</label>
								</div>
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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