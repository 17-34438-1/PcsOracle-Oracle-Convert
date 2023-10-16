<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/assignmentRequestSearchByDateReport"); ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<div class="row">
								
							</div>

							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date <span class="required">*</span></span>
									<input type="date" name="date" id="date" class="form-control" value="<?php echo date("Y-m-d");?>">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php if(isset($msg)){echo $msg;} ?>
								</div>
							</div>
						</div>	
					</form>
				</div>
			</section>
		</div>
	</div>	

</section>