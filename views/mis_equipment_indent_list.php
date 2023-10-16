<script>
	function changeTextBox(val)
		{
			//alert(val);
			var conboDiv = document.getElementById("comboDiv");
			var inputDiv = document.getElementById("inputDiv");
			var dateDiv = document.getElementById("dateDiv");
			if(val=="entry_date")
			{
				dateDiv.style.display="inline";
				inputDiv.style.display="none";
				conboDiv.style.display="none";
			}
			else if(val=="cnf_license")
			{
				inputDiv.style.display="inline";
				conboDiv.style.display="none";
				dateDiv.style.display="none";
			}
			else
			{
				dateDiv.style.display="none";
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
				var url = "<?php echo site_url('ajaxController/getIndentYard');?>";
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
			
				var val = xmlhttp.responseText;
				
				//alert(val);
				
				var selectList=document.getElementById("searchVal");
				removeOptions(selectList);
				//alert(xmlhttp.responseText);
				var val = xmlhttp.responseText;
				var jsonData = JSON.parse(val);
				//alert(xmlhttp.responseText);
				for (var i = 0; i < jsonData.length; i++) 
				{
					var option = document.createElement('option');
					option.value = jsonData[i].id;  //value of option in backend
					option.text = jsonData[i].yard_name;	  //text of option in frontend
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
		} else 
		{
			return false;
        }		 
      }
 </script>

				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title; ?></h2>
					
						<div class="right-wrapper pull-right">
						
						</div>
					</header>

					<!-- start: page -->
						<section class="panel">
							<!--header class="panel-heading">
								<h2 class="panel-title" align="right">
									<a href="<?php echo site_url('ontroller/EntryForm') ?>">
										<button style="margin-left: 35%" class="btn btn-primary btn-sm">
											<i class="fa fa-plus"></i>
										</button>
									</a>									
								</h2>								
							</header-->
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="POST" 
									action="<?php echo site_url('misReport/mis_equipment_indent_list_Search') ?>">
									<div class="form-group">
										<label class="col-md-2 control-label">&nbsp;</label>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label">Search By :</label>
												<div class="col-md-8">
													<select name="search_by" id="search_by" class="form-control mb-md" onchange="changeTextBox(this.value);" required>
														<option value="entry_date" selected style="width:110px;">Entry Date</option>
														<option value="yard" >YARD</option>
														<option value="cnf_license">CNF LICENSE</option>
													</select>
												</div>
											</div>
											<div class="form-group" id="comboDiv" style="display:none;">
												<label class="col-md-4 control-label">Search Value</label>
												<div class="col-md-8">
													<select class="form-control mb-md" name="searchVal" id="searchVal">
														<option value="">---select---</option>
													</select>
												</div>
											</div>
											<div class="form-group" id="inputDiv" style="display:none;">
												<label class="col-md-4 control-label" for="serch_value">Search Value</label>
												<div class="col-md-8">
													<input type="text" name="searchInput" id="searchInput" class="form-control" autofocus>
												</div>
											</div>
											<div class="form-group" id="dateDiv" style="">
												<label class="col-md-4 control-label" for="serch_value">Search Value</label>
												<div class="col-md-8">
													<input type="date" name="searchDt" id="searchDt" class="form-control" autofocus>
												</div>
											</div>
										</div>										
										<div class="row">
											<div class="col-sm-12 text-center">
												<!--button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button-->
												<input type="submit" value="Search" name="View" class="mb-xs mt-xs mr-xs btn btn-success">
											</div>													
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
												
											</div>
										</div>
									</div>
									<div class="form-group">
									</div>
								</form>
								<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
									<thead>
										<tr>
											<th class="text-center">Sl</th>
											<th class="text-center">Indent Date</th>
											<th class="text-center">Cnf Name</th>
											<th class="text-center">No Of Container</th>	
											<th class="text-center">Description</th>	
											<th class="text-center">Yard</th>	
											<th class="text-center">RRC</th>	
											<th class="text-center">FLT 3T</th>	
											<th class="text-center">FLT 5T</th>	
											<th class="text-center">FLT 10T</th>	
											<th class="text-center">FLT 20T</th>	
											<th class="text-center">MC 10T</th>	
											<th class="text-center">MC 20T</th>	
											<th class="text-center">MC 30T</th>	
											<th class="text-center">MC 50T</th>	
											<th class="text-center">Edit</th>	
											<th class="text-center">Delete</th>	
										</tr>
									</thead>
									<tbody>
										<?php
											 //  loc_id, location_name, owner_id, full_name, type_id, short_name, prod_user_id, 
											//   company_name, prod_serial, prod_ip, prod_deck_id, prod_rcv_date, prod_rcv_by
												for($i=0;$i<count($list);$i++) { 				
										?>
										<tr class="gradeX">
											<td align="center"> <?php echo $i+1;?> </td>
											<td align="center"> <?php echo $list[$i]['indent_date']?> </td>
											<td align="center"> <?php echo $list[$i]['cnf_name']?> </td>
											<td align="center"> <?php echo $list[$i]['no_of_container']?> </td>
											<td align="center"> <?php echo $list[$i]['goods_description']?> </td>
											<td align="center"> <?php echo $list[$i]['yard_name']?> </td>
											<td align="center"> <?php if($list[$i]['equip_rrc']==1) echo $list[$i]['no_of_rrc']; else echo "0"; ?> </td>
											<td align="center"> <?php if($list[$i]['equip_flt_3t']==1) echo $list[$i]['no_of_flt']; else echo "0"; ?> </td>
											<td align="center"> <?php if($list[$i]['equip_flt_5t']==1) echo $list[$i]['no_of_flt']; else echo "0"; ?> </td>
											<td align="center"> <?php if($list[$i]['equip_flt_10t']==1) echo $list[$i]['no_of_flt']; else echo "0"; ?> </td>
											<td align="center"> <?php if($list[$i]['equip_flt_20t']==1) echo $list[$i]['no_of_flt']; else echo "0"; ?> </td>
										    <td align="center"> <?php if($list[$i]['equip_mc_10t']==1) echo $list[$i]['no_of_mc']; else echo "0"; ?> </td>
											<td align="center"> <?php if($list[$i]['equip_mc_20t']==1) echo $list[$i]['no_of_mc']; else echo "0"; ?> </td>
											<td align="center"> <?php if($list[$i]['equip_mc_30t']==1) echo $list[$i]['no_of_mc']; else echo "0"; ?> </td>
											<td align="center"> <?php if($list[$i]['equip_mc_50t']==1) echo $list[$i]['no_of_mc']; else echo "0"; ?> </td>
											<td align="center">
												<form action="<?php echo site_url('misReport/mis_equipment_indent_list_Edit');?>" method="POST">
													<input type="hidden" id="indentid" name="indentid" value="<?php echo $list[$i]['id'];?>">							
													<input type="submit" value="Edit"  class="mb-xs mt-xs mr-xs btn btn-success">							
												</form> 
											</td>
											<td align="center"> 
												<form action="<?php echo site_url('misReport/mis_equipment_indent_list');?>" method="POST" onsubmit="return validate();">						
													<input type="hidden" id="indentid" name="indentid" value="<?php echo $list[$i]['id'];?>">							
													<input type="submit" value="Delete" name="delete" class="mb-xs mt-xs mr-xs btn btn-danger">			 						
												</form> 
											</td>
										</tr>
										
										<?php } ?>
										<!--tr class="gradeX">
											<td align="center" colspan="17">
												<?php echo $links?>
											</td>
										</tr-->
									</tbody>
								</table>
							</div>
						</section>
						
						
						
						
					<!-- end: page -->
				</section>
			</div>