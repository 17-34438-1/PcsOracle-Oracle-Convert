<script type="text/javascript">  
	function validate()
	{
		if(document.location_form.location_name.value == "")
		{
			alert("Please provide location name!");
			document.location_form.location_name.focus();
			return false;
		}
		return true ;
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
							
								
								
					<div class="panel-body" align="center">
					<form name= "product_user" id="product_user" onsubmit="return(validate());" action="<?php echo site_url("Report/product_user_save");?>"  method="post">
						<input type="hidden" id="user_id" name="user_id" value="<?php if($flag==1) { echo $rslt_user_info[0]['id']; } ?>" />
					<table>		
						
						<tr>
							<td align="center" colspan="3">
								<?php echo $msg;?>
							</td>
						</tr>
						
						
						<tr>
							<td>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" style="width:170px;">Company Name  :   <span class="required">*</span></span>
									<input type="text" style="width:200px;" id="company_name" name="company_name" value="<?php if($flag==1) { echo $rslt_user_info[0]['company_name']; } ?>" />
								</div>
							</td>
						</tr>
						
											
						<tr>
							<td colspan="3" align="center" >
							<div class="row">
								<div class="col-sm-12 text-center">
								<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
								</div>													
								</div>     
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
					</table>
				</form>			

				</div>
			</section>
		
			</div>
		</div>	
	<!-- end: page -->
	</section>
</div>