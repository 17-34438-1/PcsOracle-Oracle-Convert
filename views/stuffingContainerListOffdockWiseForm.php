<script type="text/javascript">
    function validate()
	{
		if(document.stuffingContainerOffdockWiseSearchForm.stuffing_date_offdock.value=="")
		{
			alert( "Please provide a date!" );
			document.stuffingContainerOffdockWiseSearchForm.stuffing_date_offdock.focus() ;
			return false;
		}
	}
</script> 

	<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title" align="right">
								<!--a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a-->
							</h2>								
						</header>
						<div class="panel-body">
							<form name= "stuffingContainerOffdockWiseSearchForm" id="stuffingContainerOffdockWiseSearchForm" class="form-horizontal form-bordered" method="POST" 
								onsubmit="return(validate());"
								action="<?php echo site_url('report/stuffingContainerListPerform') ?>" target="_blank">
								<input type="hidden" id="login_id_offdock" name="login_id_offdock" value="<?php echo $login_id; ?>" />
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Stuffing Date:</span>
											<input type="date" name="stuffing_date_offdock" id="stuffing_date_offdock" class="form-control" value="">
										</div>
									</div>
									<div class="col-md-4">
									</div>
									<div class="col-md-3">
										<div class="radio-custom radio-success">
											<input type="radio" id="option" name="option" value="pdf">
											<label>PDF</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="radio-custom radio-success">
											<input type="radio" id="option" name="option" value="html" checked>
											<label>HTML</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
									</div>
									<div class="col-sm-offset-3 col-sm-6 text-center">
										<?php
										if($ctime==$lowerLimit)
										{ 
										?>
										<div style="font-size:20px;color:red;">
											<marquee hspace="1"><b>Delete facility will be closed after <?php echo $diff; ?> minutes for today.</b></marquee>
										</div>
										<?php
										}
										else if($ctime>=$upperLimit)
										{ 
										?>
											<div style="font-size:20px;color:red;">
												<marquee hspace="1"><b>Delete facility is closed for today.</b></marquee>
											</div>
										<?php
										}
										?>
									</div>									
								</div>
							</form>
						</div>
					</section>
			
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>