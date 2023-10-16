<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
	
	 <div class="row">
		 <div class="col-lg-12">						
			<section class="panel" >
				<div class="panel-body">
				
				       <form action="<?php echo site_url('Report/pangaonEdiConverter');?>" method="POST" enctype="multipart/form-data">
						<!--input type="hidden" name="exp_no" id="exp_no" value="<?php //echo $exp_no1 ;?>" /-->
						
						<div class="form-group">
						       <div class="col-md-offset-2 col-md-7">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Export Rotation: </span>
										<input type="text" name="exp_no" id="exp_no" class="form-control" required>
									</div>											
								</div>
							<div class="col-md-offset-2 col-md-7">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Select Your Excel File:: </span>
									<input type="file" name="fileToUpload" id="fileToUpload" class="form-control" required>
								</div>											
							</div>
							<div class="col-md-2">		
								<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Upload</button>											
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<?php echo $message;?>
							</div>
						</div>
					</form>
							
				</div>
				
			</section>
		 </div>
	 <div>
 

</section>
