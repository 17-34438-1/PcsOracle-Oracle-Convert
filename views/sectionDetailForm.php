<script>
  
 function validate()
        { 
			if( document.myForm.orgType.value =="" )
					{
						alert( "Please! Select Organisation." );
						document.myForm.orgType.focus() ;
						return false;
					}
			else if(document.myForm.shortName.value =="")
					{
						alert( "Please! Provide Short Name." );
						document.myForm.shortName.focus() ;
						return false;
					}	
			else if( document.myForm.fullName.value =="")
					{
						alert( "Please! Provide Full Name." );
						document.myForm.fullName.focus() ;
						return false;
					}
			else
					return true;
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
								<?php echo $msg; ?>
							</header>
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="POST" name="myForm" onsubmit="return validate();"
									action="<?php echo site_url('menuDesignController/sectionDetailsFormPerform') ?>" target="_self">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Org Type:</span>
												<select name="orgType" id="orgType" class="form-control" tabindex="12">
													<option value="">--Select--</option>                                                
													<?php
														include("FrontEnd/mydbPConnection.php");
														$sql_org_list="select * from tbl_org_types";
														$result_org_list=mysqli_query($con_cchaportdb,$sql_org_list);
														while($orgList=mysqli_fetch_object($result_org_list))
														{
														?>
															<option value="<?php echo $orgList->id; ?>"><?php echo $orgList->Org_Type; ?></option>
														<?php
														}
													?>		  
												</select> 
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Short Name:</span>
												<input type="text" name="shortName" id="shortName" class="form-control">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Full Name:</span>
												<input type="text" name="fullName" id="fullName" class="form-control">
											</div>
										</div>
										<div class="col-sm-12 text-center">
											<button class="mb-xs mt-xs mr-xs btn btn-success">Submit</button>
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