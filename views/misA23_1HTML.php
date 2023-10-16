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
					<form class="form-horizontal form-bordered" id = "myform" method="POST" action="<?php echo site_url('misReport/A23_1Report'); ?>" target="_blank">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">										
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date <span class="required">*</span></span>									
									<input type="date" id="fromdate" name="fromdate" class="form-control login_input_text"/>
								</div>
								<div class="input-group mb-md">
									<div style="align:center">
										<label class="checkbox-inline">
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										</label>
										<label class="checkbox-inline">
											<input type="radio" id="options" name="options" value="xl"> Excel
										</label>

										<label class="checkbox-inline">
											<input type="radio" id="options" name="options" value="html"> HTML
										</label>	
									</div>
									
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
