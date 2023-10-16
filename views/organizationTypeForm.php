<script>  
	function validate()
	{ 		
		if( document.myForm.orgType.value =="" )
		{
			alert( "Please! Provide Organisation Name." );
			document.myForm.orgType.focus() ;
			return false;
		}	
		else if(document.myForm.type_description.value =="")
		{
			alert( "Please!Provide Organisation Description." );
			document.myForm.type_description.focus() ;
			return false;
		}							
		else
			return true;
	}   	
 </script>
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
					<form name="myForm" onsubmit="return validate();" class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/organizationTypeEntryForm'); ?>" >
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<a href="<?php echo site_url('report/organizationTypeList') ?>">BACK TO ORGANIZATION TYPE LIST</a>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Organization Type: <span class="required">*</span></span>
									<input type="text" style="width:150px;" id="org_type" name="org_type" <?php if($editFlag==1){ ?> value="<?php echo $formList[0]['Org_Type'];  ?>"<?php } ?> class="form-control login_input_text">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Type Description <span class="required">*</span></span>									
									<input type="textarea" style="width:150px;" id="type_description" name="type_description" <?php if($editFlag==1){ ?> value="<?php echo $formList[0]['Type_description'];  ?>"<?php } ?> class="form-control login_input_text">
								</div>
								<div class="input-group mb-md">
									<input type="hidden" id="org_id" name="org_id" <?php if($editFlag==1){ ?> value="<?php echo $formList[0]['id'];  ?>"<?php } ?> class="form-control login_input_text">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">																	
									<?php 
									if($editFlag==1)
									{
									?>										
										<input type="submit" name="update" value="Update" class="mb-xs mt-xs mr-xs btn btn-success" />
									<?php 
									} 
									else
									{
									?>
										<input type="submit" name="save" value="Save" class="mb-xs mt-xs mr-xs btn btn-success" />
									<?php 
									} 
									?> 
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
