<script>
    function validate()
      {
          	if (confirm("Are you sure!! Delete this record?") == true) {
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
				
     	
		 
		 	<!--div style="overflow:scroll;margin-bottom:5px;" class="widget responsive borders widget-table"><!-- overflow:scroll; -->
				<div class="widget-header">
					<!--h3 align="center">Product List</h3-->
				</div> <!-- .widget-header -->
				<div style="height:400px;" class="widget-content">
					<table class="table table-responsive table-bordered table-striped table-hover" id="datatable-default">
						<thead>
							<tr>
								<th>SL</th>
								<th>ProductName</th>
								<th>Category</th>
								<th>From</th>
								<th>Owner/User</th>
								<th>Received_Date</th>
								<th>Comments</th>
								<th>Received_By</th>
								<th>Edit</th>
								<th>Delete</th>									
							</tr>
						</thead>
						<tbody>	
				<?php
					$org_Type_id = $this->session->userdata('org_Type_id');
				    for($i=0;$i<count($list);$i++) { 				
					?>
					  <tr class="gridLight">
					  <td>
					   <?php echo $i+1;?>
					  </td>
					 
					<td align="center"> <nobr>
					   <?php echo $list[$i]['prod_name']?> </nobr>
					 </td>   
					 <td align="center">
					   <?php echo $list[$i]['rcv_category']?>
					 </td>   

					 <td align="center">
					   <?php echo $list[$i]['rcv_from']?>
					 </td>   

					<td align="center"><nobr>
					   <?php echo $list[$i]['owner_user']?> </nobr>
					 </td>   
					 
					 <td align="center">
					   <?php echo $list[$i]['rcv_date']?>
					 </td>  		 
					 <td align="center">
					   <?php echo $list[$i]['comments']?>
					 </td>  
					 <td align="center">
					   <?php echo $list[$i]['rcv_by']?>
					 </td>  
					 
					<td align="center">
						<form action="<?php echo site_url('Report/networkProductReceivedEdit');?>" method="POST">
							<input type="hidden" id="recvID" name="recvID" value="<?php echo $list[$i]['id'];?>">							
							<input type="submit" value="Edit"  class="mb-xs mt-xs mr-xs btn btn-success btn-sm" style="width:100%;" <?php if($org_Type_id == 84){ echo "disabled";}?> >							
						</form> 
							</td> 
				
					<td align="center"> 
						<form action="<?php echo site_url('Report/networkProductReceiveList');?>" method="POST" onsubmit="return validate();">						
							<input type="hidden" id="pid" name="pid" value="<?php echo $list[$i]['id'];?>">							
							<input type="submit" value="Del." name="delete" class="mb-xs mt-xs mr-xs btn btn-danger btn-sm" style="width:80%;" <?php if($org_Type_id == 84){ echo "disabled";}?> >			 						
						</form> 
								</td> <?php }
							?>
				</tr>
			</tbody>
		</table>
	</div> <hr><!-- .widget-content -->
</div> <!-- /widget -->	
			
				</div> <hr><!-- .widget-content -->
			</div> <!-- /widget -->	
									</div>
								</section>
						
							</div>
						</div>	
					<!-- end: page -->
				</section>
			</div>