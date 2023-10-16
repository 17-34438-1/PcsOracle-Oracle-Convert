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
									<form name="location_form" id="location_form" onsubmit="return(validate());" action="<?php echo site_url("Report/location_save");?>" method="post">
					<input type="hidden" id="location_id" name="location_id" value="<?php  if($flag==1){ echo $rslt_location_info[0]['id']; } ?>" />
					<table>		
						
						<tr>
							<td align="center" colspan="3">
								<?php echo $msg;?>
							</td>
						</tr>
						
						
						<tr>
							<td>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" style="width:170px;">Location Name :   <span class="required">*</span></span>
									<input type="text" style="width:200px;" name="location_name" id="location_name" value="<?php  if($flag==1){ echo $rslt_location_info[0]['location_name']; } ?>"  />
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