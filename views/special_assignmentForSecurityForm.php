 <script>
 function validate()
{
	if( document.myform.date.value == "" )
	{
		alert( "Please provide Date!" );
		document.myform.date.focus() ;
		return false;
	}
	return( true );
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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/special_assignmentForSecurity'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php echo @$myUpdateManifestList; ?>
								</div>
							</div>

							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DATE <span class="required">*</span></span>
									<input type="date" name="date" id="date" class="form-control" placeholder="Date" required >
								</div>
								<script>
											  $(function() {
												$( "#date" ).datepicker({
													changeMonth: true,
													changeYear: true,
													dateFormat: 'yy-mm-dd', // iso format
												});
											});
									</script>
																			
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
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