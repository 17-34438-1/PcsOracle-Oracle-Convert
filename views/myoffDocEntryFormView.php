<script type="text/javascript">       
  function validate()
   {   

		if(document.offDocEntryForm.offdoc_id.value == "" )
		{
			alert( "Please provide Offdoc ID!" );
			document.offDocEntryForm.offdoc_id.focus() ;
			return false;
		}
                else if(document.offDocEntryForm.offdoc_code.value == "" )
		{
			alert( "Please provide offdoc code!" );
			document.offDocEntryForm.offdoc_code.focus() ;
			return false;
		}
		else if( document.offDocEntryForm.offdoc_name.value == "" )
		{
			alert( "Please provide offdoc name!" );
			document.offDocEntryForm.offdoc_name.focus() ;
			return false;
		}
  
		return true ;
   }
</script> 
 
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title; ?></h2>
	
		<div class="right-wrapper pull-right">
		
		</div>
	</header>

	<!-- start: page -->
		<section class="panel">
			<header class="panel-heading">
				<!--h2 class="panel-title" align="right">
					<a href="<?php echo site_url('misReport/mis_equipment_indent_list') ?>">
						<button style="margin-left: 35%" class="btn btn-primary btn-sm">
							<i class="fa fa-list"></i> GO TO INDENT LIST
						</button>
					</a>									
				</h2-->
			</header>
			<div class="panel-body">
				<form class="form-horizontal form-bordered" name= "offDocEntryForm" id="offDocEntryForm" method="POST" onsubmit="return(validate());"
					action="<?php echo site_url('report/myoffDocEntryFormPerform') ?>" target="_blank">
					<div class="form-group">
						
						<div class="col-md-offset-3 col-md-6">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Offdoc ID</span>
								<input type="text" name="offdoc_id" id="offdoc_id" class="form-control" value="">
							</div>
						</div>
						<div class="col-md-offset-3 col-md-6">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Offdoc Code</span>
								<input type="text" name="offdoc_code" id="offdoc_code" class="form-control" value="">
							</div>
						</div>
						<div class="col-md-offset-3 col-md-6">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Offdoc Name</span>
								<input type="text" name="offdoc_name" id="offdoc_name" class="form-control" value="">
							</div>
						</div>
						
						<div class="col-sm-12 text-center">
							<!--button class="mb-xs mt-xs mr-xs btn btn-success" type="submit">Save</button-->
							<input type="submit" value="Save" class="mb-xs mt-xs mr-xs btn btn-success"/> 
						</div>													
					</div>
					<div class="form-group">
					</div>
				</form>
			</div>
		</section>
	<!-- end: page -->
</section>
</div>