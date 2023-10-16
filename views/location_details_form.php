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
					<form name="location_form" id="location_form" onsubmit="return(validate());" action="<?php echo site_url("Report/location_details_save");?>" method="post">
						<input type="hidden" id="location_id" name="location_id" value="<?php if($flag==1){ echo $rslt_location_info[0]['id']; } ?>" />
						<input class="read" type="hidden"  id="loc_dt_id" name="loc_dt_id"  <?php if($editFlag==1){?> value="<?php echo $loc_list[0]['loc_dtl_id']; }?>">
						
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
										<select  id="location" name="location" style="width:215px;" value=""  >
											<option value="">--Select--</option>
												  <?php if($editFlag==1){?> 
													<option value="<?php echo $loc_list[0]['locId']; ?>" selected="true"><?php echo $loc_list[0]['location_name']; ?></option>
												   <?php }  ?>    
													
													<?php
													for($i=0; $i<count($location_list); $i++){ ?>
												<option value="<?php echo $location_list[$i]['id']; ?>"><?php echo $location_list[$i]['location_name']; ?></option>
													   <?php } ?>
										</select>	 				
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" style="width:170px;">Location Details:   <span class="required">*</span>
									<textarea type="text" style="width:250px; height: 70px" rows="6" name="location_detail" id="location_detail" value=""><?php if($editFlag==1){ echo $loc_list[0]['location_details']; }?> </textarea>

								</div>
							</td>
						</tr>
						
											
						<tr>
							<td colspan="3" align="center" >
							<div class="row">
								<div class="col-sm-12 text-center">
								<?php if($editFlag==1){ ?>
										<input class="mb-xs mt-xs mr-xs btn btn-success"  name="update" type="submit"  value="UPDATE" > 
										<?php } else{?>
										 <input class="mb-xs mt-xs mr-xs btn btn-success"  name="save" type="submit"  value="SAVE" > 
										<?php } ?>  
								
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