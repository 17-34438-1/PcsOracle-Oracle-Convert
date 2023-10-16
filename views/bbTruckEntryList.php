<script type="text/javascript">	
	function del_entry()
	{
		if (confirm("Do you want to detete this entry?") == true)
		{
			return true ;
		}
		else
		{
			return false;
		}
	}



function changeTextBox(v)
{
		var fromdate = document.getElementById("fromdate");
		var shedNo = document.getElementById("shedNo");
		if(v=="dateRange")
		{
			shedNo.value=null;
			fromdate.disabled=false;
			shedNo.disabled=true;
		
		}	
		else if(v=="shedNo")
		{
			fromdate.value=null;
			fromdate.disabled=true;
			shedNo.disabled=false;	
		}
		else if(v=="Shed_Date")
		{
			fromdate.disabled=false;
			shedNo.disabled=false;	
			fromdate.value=null;
			shedNo.value=null;
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
			       <form name= "myForm"  action="<?php echo site_url("Report/bbTruckEntryListSearch");?>" method="post">
   
				<table border="0" width="600px" align="center">					 
					<tr>
						<td align="left" >
						<label for=""><font color='red'></font><nobr>Search By :<em>&nbsp;</em></nobr></label></td>
						<td>
								<select name="search_by" id="search_by" class="" onchange="changeTextBox(this.value);">
									<option value="" label="search_by" selected style="width:110px;">---Select-------</option>
									<option value="shedNo" label="ShedNo">Shed No</option>														
									<option value="dateRange" label="DateRange" >Date</option>	
									<option value="Shed_Date" label="Shed & Date" >Shed & Date</option>	
								</select>

						</td>
						<td><label for=""><font color='red'></font><nobr>Date:<em>&nbsp;</em></nobr></label></td>

							<td>
							 <input type="date" style="width:130px;" id="fromdate" name="fromdate" value="<?php date("Y-m-d"); ?>" disabled />
							</td>
								
						<td align="left" >
						<label for=""><font color='red'></font>Shed:<em>&nbsp;</em></label></td>
						<td>
								<select name="shedNo" id="shedNo" class=""; disabled>
									<option value="" label="Shed_No" selected style="width:80px;">---Select-------</option>
									<option value="Shed 1" label="Shed 1" >Shed 1</option>																																				
									<option value="Shed 2" label="Shed 2" >Shed 2</option>																																				
									<option value="Shed 3" label="Shed 3" >Shed 3</option>																																				
									<option value="Shed 4" label="Shed 4" >Shed 4</option>																																				
									<option value="Shed 5" label="Shed 5" >Shed 5</option>																																				
									<option value="Shed 6" label="Shed 6" >Shed 6</option>																																				
								</select>

						</td>

						<td align="center" width="80px">
							<input type="submit" value="View" name="View" class="mb-xs mt-xs mr-xs btn btn-success">
						</td>

						<td colspan="2" align="center" width="80px">
							<input type="submit" value="Print" name="Print"  formtarget="_blank" class="mb-xs mt-xs mr-xs btn btn-success">
						</td>
					
					</tr>

				</table>
				
				</form>	
				<div class="widget-header">
					<h3 align="center">BREAK BULK TRUCK DEMAND LIST</h3>
				</div> <!-- .widget-header -->
				<div style="height:600px;" class="widget-content">

					
					<table class="table table-responsive table-bordered table-striped table-hover" id="datatable-default">
					<?php if($this->session->userdata('Control_Panel')!=67&& $this->session->userdata('org_Type_id')!=67)
					{ ?>
					
						<tr>
							<th colspan="9" align="right"><a href="<?php echo site_url('Report/bbTruckEntryForm'); ?>">ADD NEW</a></th>
						</tr>
						<?php } ?>				
						<tr>
							<th colspan="9"><?php echo $msg; ?></th>
						</tr>
						<tr>
							<th class="gridDark" align="center">Sl</th>
							<th class="gridDark" align="center">Shed No</th>
							<th class="gridDark" align="center">CNF Name</th>
							<th class="gridDark" align="center">Entry Date</th>
							<th class="gridDark" align="center">From</th>
							<th class="gridDark" align="center">To</th>
							<th class="gridDark" align="center">Truck Quantity</th>
					<?php if($this->session->userdata('Control_Panel')!=67 && $this->session->userdata('org_Type_id')!=67){?>			
							<th class="gridDark" align="center">Action</th>
							<th class="gridDark" align="center">Action</th>
					<?php } ?>		
						</tr>
						<?php
						
						for($i=0;$i<count($rslt_truckEntryList);$i++)
						{							
						?>
						<tr>
							<td class="gridLight" width="10px" align="center">
								<?php echo $i+1; ?>
							</td>							
							<td class="gridLight" width="200px" align="center">
								<?php echo $rslt_truckEntryList[$i]['shed_no']; ?>
							</td>
							<td class="gridLight" width="200px" align="center">
								<?php echo $rslt_truckEntryList[$i]['cnf_name']; ?>
							</td>
							<td class="gridLight" width="200px" align="center">
								<?php echo $rslt_truckEntryList[$i]['truck_entry_date']; ?>
							</td>
							<td class="gridLight" width="200px" align="center">
								<?php echo $rslt_truckEntryList[$i]['truck_from_time']; ?>
							</td>
							<td class="gridLight" width="200px" align="center">
								<?php echo $rslt_truckEntryList[$i]['truck_to_time']; ?>
							</td>
							<td class="gridLight" width="10px" align="center">
								<?php echo $rslt_truckEntryList[$i]['truck_quantity']; ?>
							</td>
					<?php if($this->session->userdata('Control_Panel')!=67&& $this->session->userdata('org_Type_id')!=67){?>

							<td class="gridLight">
								<form name="editBBTruckEntry" id="editBBTruckEntry" action="<?php echo site_url('Report/editBBTruckEntry');?>" method="post">
									<input type="hidden" name="bb_id_edit" id="bb_id_edit" value="<?php echo $rslt_truckEntryList[$i]['id'] ;?>">
									<input type="submit" value="Edit" name="edit" class="mb-xs mt-xs mr-xs btn btn-success">
								</form>
							</td>
							<td class="gridLight">
								<form name="deleteBBTruckEntry" id="deleteBBTruckEntry" action="<?php echo site_url("Report/deleteBBTruckEntry");?>" onsubmit="return(del_entry());" method="post">
									<input type="hidden" name="bb_id_delete" id="bb_id_delete" value="<?php echo $rslt_truckEntryList[$i]['id'] ;?>">
									<input type="submit" value="Delete" name="delete" class="mb-xs mt-xs mr-xs btn btn-success">

								</form>
							</td>
						</tr>
						<?php } ?>				
						
						<?php
						}
						?>							
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