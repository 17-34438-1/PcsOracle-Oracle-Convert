<script>
	function changeTextBox(val)
		{
			//alert(val);
			var conboDiv = document.getElementById("conboDiv");
			var inputDiv = document.getElementById("inputDiv");
			if(val=="serial" || val=="product" || val=="ip_addr")
			{
				inputDiv.style.display="inline";
				conboDiv.style.display="none";
			}
			else
			{
				inputDiv.style.display="none";
				conboDiv.style.display="inline";
				
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				} 
				else 
				{  
					// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				var url = "<?php echo site_url('ajaxController/getComboValForNetworkList');?>?colName="+val;
				//alert(url);
				xmlhttp.onreadystatechange=stateChangeSearchComboVal;
				xmlhttp.open("GET",url,false);
				xmlhttp.send();
			}
			
		}
		
	function stateChangeSearchComboVal()
		{
			//alert(xmlhttp.responseText);
           if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{
			var selectList=document.getElementById("searchVal");
			removeOptions(selectList);
				//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
				//alert(xmlhttp.responseText);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].id;
				option.text = jsonData[i].detl;
				selectList.appendChild(option);
                                
			}
                    }
		}
		
    function removeOptions(selectbox)
	{
	var i;
	for(i=selectbox.options.length-1;i>=1;i--)
            {
		selectbox.remove(i);
            }
	}

    function validate()
      {
        if (confirm("Are you sure!! Delete this record?") == true) {
		   return true ;
	} else {
		 return false;
            }		 
      }
      
       function checked()
      {
          	if (confirm("Are you sure! you checked this record!!") == true) {
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
				
         <form action="<?php echo site_url('Report/networkProductEntryListBySearch');?>" method="POST" >
					<table border="0" width="300px" align="center">
					<tr><td colspan="4" align="center"><span><font color="green"  size="4" style="font-weight: bold"><nobr><?php echo $tableTitle; ?></nobr></font></span> </td></tr>
						<tr>
				<td align="left" >
				<label for=""><nobr><b>Search By :</b></nobr><em>&nbsp;</em></label></td>

						<td>
							<select name="search_by" id="search_by" onchange="changeTextBox(this.value);">
								<option value="serial" label="Serial No" selected style="width:110px;">Serial No</option>
								<option value="category" label="Product Category">Product Category</option>
								<option value="product" label="Product Name">Product Name</option>
								<option value="location" label="Location">Location</option>
								<!--<option value="serial" label="Serial No">Serial No</option>-->
								<option value="user" label="Updated By">Updated By</option>
								<option value="ip_addr" label="IP Address">IP Address</option>
							 </select>
				</td>

				<td><b><nobr>&nbsp;&nbsp; &nbsp; Search Value:</nobr></b></td>
				<td>
					<div id="conboDiv" style="display:none;">
						<select name="searchVal" id="searchVal" style="width:170px">
						<option value="">---select---</option>
						</select>
					</div>
					<div id="inputDiv" style="">
						<input type="text" style="width:170px" id="searchInput" name="searchInput" autofocus />
					</div>
				</td>

				<td align="center" width="70px">
						<input type="submit" value="View" name="View" class="mb-xs mt-xs mr-xs btn btn-success">
				</td>
			</tr>
			</table>
		 </form>	
		 
		 	<!--div style="overflow:scroll;margin-bottom:5px;" class="widget responsive borders widget-table"><!-- overflow:scroll; -->
				<div class="widget-header">
					<!--h3 align="center">Product List</h3-->
				</div> <!-- .widget-header -->
				<div style="height:400px;" class="widget-content">
					<table class="table table-responsive table-bordered table-striped table-hover" id="datatable-default">
						<thead>
							<tr>
								<th>SL</th>
								<th>Product_Cat.</th>
								<th>Product_Name</th>
								<th>Serial.</th>
								<th>IMEI</th>
								<th>Loc_Details</th>
								<th>IP_Address</th>
								<th>Model/Dec</th>
								<th>Rec_Date</th>
								<th>Check</th>
								<th>Edit</th>
								<th>Delete</th>									
							</tr>
						</thead>
						<tbody>	
				<?php
				$org_Type_id = $this->session->userdata('org_Type_id');
				for($i=0;$i<count($list);$i++) { 				
				?>							
				<tr <?php if($list[$i]['checkStatus']==1){ ?> 
						style="background-color:#f9e79f" <?php } else ?> class="gridLight">
					<td> <?php echo $i+1;?></td>					 
					<td><?php echo $list[$i]['short_name']?></td>   
					<td><?php echo $list[$i]['prod_name']?></td>   
					<td><?php echo $list[$i]['prod_serial']?></td>    
					<td><?php echo $list[$i]['imei_number']?></td>
					<td><?php echo $list[$i]['location_details']?></td>   					 
					<td><?php echo $list[$i]['prod_ip']?> </td>  		 
					<td><?php echo $list[$i]['prod_deck_id']?></td>  
					<td align="center"><?php echo $list[$i]['prod_rcv_date']?></td>  
					<td>
						<form action="<?php echo site_url('Report/networkProductCkecked');?>" method="POST" onsubmit="return checked();">
							<input type="hidden" id="chkId" name="chkId" value="<?php echo $list[$i]['id'];?>">							
							<input type="submit" value="Check"  class="mb-xs mt-xs mr-xs btn btn-primary btn-sm" style="width:100%;" <?php if($org_Type_id == 84){ echo "disabled";}?> >							
						</form> 
					</td> 					 
					<td>
						<form action="<?php echo site_url('Report/networkProductListEdit');?>" method="POST">
							<input type="hidden" id="prodructID" name="prodructID" value="<?php echo $list[$i]['id'];?>">							
							<input type="submit" value="Edit"  class="mb-xs mt-xs mr-xs btn btn-success btn-sm" style="width:100%;" <?php if($org_Type_id == 84){ echo "disabled";}?> >							
						</form> 
					</td> 
					
					<td> 
						<form action="<?php echo site_url('Report/networkProductEntryList');?>" method="POST" onsubmit="return validate();">						
							<input type="hidden" id="pid" name="pid" value="<?php echo $list[$i]['id'];?>">							
							<input type="submit" value="Del." name="delete" class="mb-xs mt-xs mr-xs btn btn-danger btn-sm" style="width:80%;" <?php if($org_Type_id == 84){ echo "disabled";}?>>			 						
						</form> 
					</td> 
				</tr>
				<?php
				}
				?>
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