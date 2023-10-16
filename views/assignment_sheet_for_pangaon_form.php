<script>
	function rtnFunc(val)
	{
		var pangaon_rot=document.getElementById("pangaon_rot").value;
		
		if(pangaon_rot=="")
		{
			alert("Provide rotation");
			return false;
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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/assignment_sheet_for_pangaon_action'; ?>" target="_blank" id="myform" name="myform" onsubmit="return(rtnFunc());">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Pangaon Rotation No <span class="required">*</span></span>
									<input type="text" name="pangaon_rot" id="pangaon_rot" class="form-control" placeholder="Pangaon Rotation No">
								</div>												
							</div>
							
							<div class="col-md-offset-5 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="excel" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="html" >
									<label for="radioExample3">HTML</label>
								</div>
							</div>
																		
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success login_button">View</button>
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