<script type="text/javascript">
	function chkBlankField()
	{
		if(document.getElementById("bb_shed_no").value=="" || document.getElementById("bb_cnf_lic_no").value=="" 
		|| document.getElementById("bb_cnf_name").value=="" || document.getElementById("bb_truck_entry_date").value=="" 
		|| document.getElementById("bb_truck_entry_from_time").value=="" || document.getElementById("bb_truck_entry_to_time").value=="" 
		|| document.getElementById("bb_truck_quantity").value=="")
		{
			alert("Please fill all the fields");
			return false
		}
		else
		{
			return true;
		}
	}
	
	function getCNFName()
	{
		var bb_cnf_lic_no=document.getElementById("bb_cnf_lic_no").value;
		
		if (window.XMLHttpRequest) 
		{
		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}	
	
		var url = "<?php echo site_url('ajaxController/getCNFName')?>?bb_cnf_lic_no="+bb_cnf_lic_no;
	//	alert(url);
		xmlhttp.onreadystatechange=stateCNFName;
		xmlhttp.open("GET",url,false);
					
		xmlhttp.send();
	}
	
	function stateCNFName()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{			
			var val = xmlhttp.responseText;	
			var jsonData = JSON.parse(val);
			
			document.bb_truck_entry_form.bb_cnf_name.value=jsonData[0].name;													
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
								
											<a href="<?php echo site_url('report/bbTruckEntryList'); ?>">DEMAND LIST</a>
									
									
						<div class="panel-body" align="center">
					<form name="bb_truck_entry_form" id="bb_truck_entry_form" action="<?php if($edit_flag==0) echo site_url("Report/bbTruckEntryFormAction"); else echo site_url("Report/bbTruckEditFormAction"); ?>" method="POST" onsubmit="return chkBlankField();" enctype="multipart/form-data">
						<table>		
							
							<tr>
								<td align="center" colspan="3">
									<?php echo $msg;?>
								</td>
							</tr>
							<tr>
								<td align="center" colspan="3" class="gridDark">
									<b>BREAK BULK TRUCK DEMAND FORM</b>
								</td>
							</tr>
							
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">Shed No:  <span class="required">*</span></span>
										<input style="width:220px" type="text" id="bb_shed_no" name="bb_shed_no" value="<?php echo $section_name; ?>" readonly />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">C&F License No:  <span class="required">*</span></span>
										<input style="width:220px" list="cnf_lic_datalist" type="text" id="bb_cnf_lic_no" name="bb_cnf_lic_no" onblur="getCNFName()"value="<?php if($edit_flag==1){ echo $rslt_edit_bb_truck[0]['cnf_lic_no']; } else { echo ""; }?>" />
										<datalist id="cnf_lic_datalist">							
										<?php
										for($i=0;$i<count($rslt_cnf_name);$i++)
										{
										?>
										<option value="<?php echo $rslt_cnf_name[$i]['id']; ?>">
										<?php
										}
										?>							
									</datalist>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">C&F Name:  <span class="required">*</span></span>
										<input style="width:220px" type="text" id="bb_cnf_name" name="bb_cnf_name" readonly value="<?php if($edit_flag==1){ echo $rslt_edit_bb_truck[0]['cnf_name']; } else { echo ""; }?>" />						
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">Date:  <span class="required">*</span></span>
									<input style="width:220px" type="date" id="bb_truck_entry_date" name="bb_truck_entry_date" value="<?php if($edit_flag==1){ echo $rslt_edit_bb_truck[0]['truck_entry_date']; } else { echo ""; }?>" />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">From Time:  <span class="required">*</span></span>
										<input style="width:220px" type="text" id="bb_truck_entry_from_time" name="bb_truck_entry_from_time" value="<?php if($edit_flag==1){ echo $rslt_edit_bb_truck[0]['truck_from_time']; } else { echo ""; }?>" /> (hh:mm)
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">To Time:  <span class="required">*</span></span>
										<input style="width:220px" type="text" id="bb_truck_entry_to_time" name="bb_truck_entry_to_time" value="<?php if($edit_flag==1){ echo $rslt_edit_bb_truck[0]['truck_to_time']; } else { echo ""; }?>" /> (hh:mm)
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">Truck Quantity:  <span class="required">*</span></span>
										<input style="width:220px" type="text" id="bb_truck_quantity" name="bb_truck_quantity" value="<?php if($edit_flag==1){ echo $rslt_edit_bb_truck[0]['truck_quantity']; } else { echo ""; }?>" />
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