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
				
			<table cellspacing="1" cellpadding="1" align="center"  id="mytbl" style="overflow-y:scroll" >
            <form action="<?php echo site_url('Report/workstationList');?>" method="POST" >
            <tr><td colspan="12" align="center">  <span><font color="green"  size="4" style="font-weight: bold"><nobr><?php echo $tableTitle; ?></nobr></font></span> </td></tr>											 
			<tr>
				<td align="center" >
				<label for=""><nobr><b>Search By :</b></nobr><em>&nbsp;</em></label></td>
				<td>
					<select name="search_by" id="search_by" style=" font-size:12px; font-weight:bold;">
						<option value="serial" label="Serial No" selected style="width:110px;">Serial No</option>
						<option value="ip_addr" label="IP Address">IP Address</option>
					 </select>
				</td>

				<td><b><nobr> &nbsp;&nbsp;&nbsp; Search Value:</nobr></b></td>
				<td>
					<input type="text" style="width:170px" id="searchInput" name="searchInput" autofocus />
				</td>

				<td  align="center" width="70px">
					<input type="submit" value="View" name="View" class="mb-xs mt-xs mr-xs btn btn-success">
				</td>
			</tr>
			</form>
        </table>
		 <br/>
		<!--div style="height:300px;" class="widget-content"-->
			<!--table class="table table-responsive table-bordered table-striped table-hover" id="datatable-default"-->
								<table class="table table-bordered table-striped mb-none" id="datatable-default">
						<thead>

				<tr>
					
					<td align="center" style="font-size:15px; font-weight:bold;" rowspan="2">SL</td>
					<td align="center" style="font-size:15px; font-weight:bold;" rowspan="2"><nobr>Product Category<nobr></td>
					<td align="center" style="font-size:15px; font-weight:bold;" rowspan="2"><nobr>Product Name<nobr></td>        
					<td align="center" style="font-size:15px; font-weight:bold;" rowspan="2"><nobr>Serial No.<nobr></td>
					<td align="center" style="font-size:15px; font-weight:bold;" colspan="2"><nobr>Monitor Info<nobr></td>
					<td align="center" style="font-size:15px; font-weight:bold;" rowspan="2"><nobr>Location Details</nobr></td>
					<td align="center" style="font-size:15px; font-weight:bold;" rowspan="2"><nobr>IP Address</nobr></td>			
					<td align="center" style="font-size:15px; font-weight:bold;" rowspan="2"><nobr>Model/Dec</nobr></td>
					<td align="center" style="font-size:15px; font-weight:bold;" rowspan="2"><nobr>Received Date</nobr></td>
					<td align="center" style="font-size:15px; font-weight:bold;" rowspan="2"><nobr>Add</nobr></td>
				</tr>
						<tr style="height:35px;" >		
							<td align="center" style="font-size:15px; font-weight:bold;">Brand</td>
							<td align="center" style="font-size:15px; font-weight:bold;"><nobr>Serial<nobr></td>
						</tr>
				</thead>
				<tbody>


						<?php
						$org_Type_id = $this->session->userdata('org_Type_id');
						for($i=0;$i<count($list);$i++) { 				
						?>
						  <tr>
						  <td>
						   <?php echo $i+1;?>
						  </td>
						 
						<td align="center">
						   <?php echo $list[$i]['short_name']?>
						 </td>   
						 <td align="center">
						   <?php echo $list[$i]['prod_name']?>
						 </td>   

						 <td align="center">
						   <?php echo $list[$i]['prod_serial']?>
						 </td>   
						 
						<td align="center">
						   <?php echo $list[$i]['mName']?>
						</td>
						<td align="center">
						   <?php echo $list[$i]['mSerial']?>
						 </td>

						<td align="center">
						   <?php echo $list[$i]['location_details']?>
						 </td>   
						 
						 <td align="center">
						   <?php echo $list[$i]['prod_ip']?>
						 </td>  		 
						 <td align="center">
						   <?php echo $list[$i]['prod_deck_id']?>
						 </td>  
						 <td align="center">
						   <?php echo $list[$i]['prod_rcv_date']?>
						 </td>  
						 
						<td align="center">
							<form action="<?php echo site_url('Report/addWorkStationItem');?>" method="POST">
								<input type="hidden" id="product_info_id" name="product_info_id" value="<?php echo $list[$i]['id'];?>">	
								<input type="hidden" id="product_name" name="product_name" value="<?php echo $list[$i]['prod_name'];?>">	
								<input type="hidden" id="product_serial" name="product_serial" value="<?php echo $list[$i]['prod_serial'];?>">							
								<input type="hidden" id="product_model" name="product_model" value="<?php echo $list[$i]['prod_deck_id'];?>">							
								<input type="submit" value="Assign_Monitor"  class="mb-xs mt-xs mr-xs btn btn-primary btn-sm" <?php if($org_Type_id == 84){ echo "disabled";}?> >							
							</form> 
						</td> 
					
				<!--		<td align="center"> 
							<form action="<?php echo site_url('report/networkProductEntryList');?>" method="POST" onsubmit="return validate();">						
								<input type="hidden" id="pid" name="pid" value="<?php echo $list[$i]['id'];?>">							
								<input type="submit" value="Del." name="delete" class="login_button" style="width:80%;">			 						
							</form> 
						</td> 
						-->
						 <?php }?>
						</tr>
					</tbody>

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