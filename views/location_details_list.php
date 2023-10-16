<script>
    function validate()
      {
          if (confirm("Are you sure! Delete this record?") == true) {
		   return true ;
	} else {
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
									
			<div class="panel-body" align="center">
			<div style="overflow:scroll;margin-bottom:5px;"class="widget responsive borders widget-table"><!-- overflow:scroll; -->
				
			<table>              
			<!--tr><td align="center"><h2><span><font color="green"  size="4" style="font-weight: bold"><nobr><?php echo $tableTitle; ?></nobr></font></span> </h2></td></tr-->
            <form action="<?php echo site_url('Report/location_details_list');?>" method="POST">	
             <tr>
				<td align="center" >
				<label for=""><nobr><b>Location Name:</b></nobr><em>&nbsp;</em></label></td>

                <td>
                    <select  id="location" name="location"  value="" onchange="showInfo(this.value)" >
                            <option value="">--Select--</option>
                            <?php if($editFlag==1){?> 
                            <option value="" >--Select--</option>
                           <?php } else ?> 
                            <?php
                            for($i=0; $i<count($location_list); $i++){ ?>
                                <option value="<?php echo $location_list[$i]['id']; ?>"><?php echo $location_list[$i]['location_name']; ?></option>
                           <?php } ?>
                    </select>
				</td>  
				<td  align="left">
					<input type="submit" value="View" name="search" class="mb-xs mt-xs mr-xs btn btn-success">
				</td>
				</tr>
			</form>
        
                 <tr><td>&nbsp;</td></tr>
			</table>
		 <br/>
				<div style="height:600px;" class="widget-content">
					<table class="table table-responsive table-bordered table-striped table-hover" id="datatable-default">
						<tr>
							<td style="font-size:20px" align="center">Sl.</td>
							<td style="font-size:20px" align="center">Location Name</td>
							<td style="font-size:20px" align="center">Location Details</td>
							<td style="font-size:20px" align="center">Edit</td>
							<td style="font-size:20px" align="center">Delete</td>
							
						</tr>
						<?php
						  $org_Type_id = $this->session->userdata('org_Type_id');
						  for($i=0;$i<count($loc_list);$i++) { 				
							?>
						  <tr class="gridLight">
							<td align="center">
						   <?php echo $i+1;?>
						  </td>
							 
							<td align="center"><nobr>
							   <?php echo $loc_list[$i]['location_name']?></nobr>
							 </td>   
							 <td align="center">
							   <?php echo $loc_list[$i]['location_details']?>
							 </td>   
							<td align="center">
								<form action="<?php echo site_url('Report/location_details_list_edit');?>" method="POST">
									<input type="hidden" id="location_dtl_id" name="location_dtl_id" value="<?php echo $loc_list[$i]['loc_dtl_id'];?>">							
									<input type="submit" value="Edit"  class="mb-xs mt-xs mr-xs btn btn-success" style="width:100%;" <?php if($org_Type_id == 84){ echo "disabled";}?>>							
								</form> 
							</td> 
						
							<td align="center"> 
								<form action="<?php echo site_url('Report/location_details_list');?>" method="POST" onsubmit="return validate();">						
									<input type="hidden" id="lid" name="lid" value="<?php echo $loc_list[$i]['loc_dtl_id'];?>">							
									<input type="submit" value="Del." name="delete" class="mb-xs mt-xs mr-xs btn btn-danger" style="width:80%;" disabled <?php //if($org_Type_id == 84){ echo "disabled";}?> > 			 						
								</form> 
							</td> 
					</tr>
			 <?php } ?>
				</table>
			</div> <hr><!-- .widget-content -->
		</div> <!-- /widget -->	
				</div>
			</section>
	
		</div>
	</div>	
<!-- end: page -->
</section>
</div>