 <script type="text/javascript">
   
			function validate()
			{
				if( document.myForm.fromdate.value == "" )
				{
					alert( "Please provide fromdate!" );
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
		<h2><?php echo $title;?></h2>
	</header>
  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" onsubmit="return(validate());" method="POST" action="<?php echo base_url().'index.php/Report/wireHouseReportDatewise'; ?>" target="_blank" id="myform" name="myform">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From: <span class="required">*</span></span>
									<input type="date" name="fromdate" id="fromdate" class="form-control" value="<?php date("Y-m-d"); ?>">
									<script>

									</script>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To: <span class="required">*</span></span>
									<input type="date" name="todate" id="todate" class="form-control" value="<?php date("Y-m-d"); ?>">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success login_button">Show</button>
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