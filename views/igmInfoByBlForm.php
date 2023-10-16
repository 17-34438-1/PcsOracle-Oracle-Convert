<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/igmInfoByBlAction'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BL Number(s) <span class="required">*</span></span>
									<textarea name="bl_nums" rows="4" id="bl_nums" class="form-control" placeholder="BL No"></textarea>
								</div>
								<div class="input-group mb-md">
									<b style="color:red;margin-left:5px;">Note:-- Please put BL Number(s) seperated by comma(,). No whitespace.</b>
								</div><br/>										
							</div>

							<div class="col-md-offset-4 col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="xl" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="html" >
									<label for="radioExample3">HTML</label>
								</div>
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
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