<script type="text/javascript">

	function validate()
	{
		if (confirm("Do you want to upload this file?") == true)
		{			
			return true;
		}
		else{
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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/uploadExcel/readObpc'; ?>" enctype="multipart/form-data" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<div class="row">														
							</div>
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Obpc Upload <span class="required">*</span></span>
									<input type="file" name="obpc" id="obpc" class="form-control" required>
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php echo @$msg; ?>
								</div>
							</div>
						</div>	
					</form>
				</div>
			</section>
		</div>
	</div>
</section>