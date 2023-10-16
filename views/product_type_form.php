<script type="text/javascript">
	function validate()
	{
		if(document.product_type.short_name.value == "")
		{
			alert("Please provide short name!");
			document.product_type.short_name.focus();
			return false;
		}
		if(document.product_type.description.value == "")
		{
			alert("Please provide description!");
			document.product_type.description.focus();
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
					<form name= "product_type" id="product_type" onsubmit="return(validate());" action="<?php echo site_url("Report/product_type_save");?>"  method="post">
						<input type="hidden" id="product_id" name="product_id" value="<?php if($flag==1){ echo $rslt_product_info[0]['id']; }?>" />

						<table>		
							
							<tr>
								<td align="center" colspan="3">
									<?php echo $msg;?>
								</td>
							</tr>
							
							
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">Short Name :   <span class="required">*</span></span>
										<input type="text" style="width:200px;" id="short_name" name="short_name" value="<?php if($flag==1){ echo $rslt_product_info[0]['short_name']; } ?>" />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:150px;">Description :   <span class="required">*</span></span>
										<textarea id="description" name="description" style="width:300px;" rows="5"><?php if($flag==1){ echo $rslt_product_info[0]['product_desc']; } ?></textarea> 										
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