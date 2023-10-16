<script>

function myshowCnfEquipmentDemand()
{
	
	rotation = document.getElementById('rotation').value;
	rotation = rotation.replace("/","_");
	bl_no = document.getElementById('bl_no').value;

	if (window.XMLHttpRequest) 
	{
		xmlhttp=new XMLHttpRequest();
	} 
	else 
	{  
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	var url = "<?php echo site_url('AjaxController/getEquipmentDemand')?>?rotation="+rotation+"&bl_no="+bl_no;
	//alert(url);
	//alert(url);
	xmlhttp.open("GET",url,false);
	xmlhttp.onreadystatechange=stateChangedEquipmentDemand;
	xmlhttp.send();
}




function stateChangedEquipmentDemand() 
{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{	
		var table = document.getElementById("tableData");
		table.innerHTML = "";

		var vsl_name="";
		var pkg_descripton1="";
		var unit_pkg1="";
		var pkg_descripton2="";
		var con_40=0;
		var con_20=0;
		var cont_number="";
		var container_no="";
		var container_size="";
		var be_no = "";	
		var pos = "";	
		var yard = "";	

		var val = xmlhttp.responseText;
		var jsonData = JSON.parse(val);
		// alert(val);
		// alert("jsonData:"+jsonData.cnfEquipmentDemand.length);

		var total_pkg_weight_value = 0;
		var cont_height="";
		var cont_status="";

		vsl_name= jsonData.cnfEquipmentDemand[0].Vessel_Name;
		pkg_descripton1= jsonData.cnfEquipmentDemand[0].Pack_Description;
		unit_pkg1= jsonData.cnfEquipmentDemand[0].Pack_Number;
		be_no= jsonData.cnfEquipmentDemand[0].be_no;
		pkg_descripton2=pkg_descripton1+unit_pkg1;

		// removeTableData();

		j=0;
		for(i=0;i<jsonData.cnfEquipmentDemand.length;i++)
		{

			total_pkg_weight_value = parseFloat(total_pkg_weight_value) + parseFloat(jsonData.cnfEquipmentDemand[i].Cont_gross_weight);
			
			container_no = jsonData.cnfEquipmentDemand[i].cont_number;
			cont_height = jsonData.cnfEquipmentDemand[i].cont_height;
			container_size = jsonData.cnfEquipmentDemand[i].cont_size;
			pos = jsonData.cnfEquipmentDemand[i].pos;
			yard = jsonData.cnfEquipmentDemand[i].yard_No;

			if(container_size=='20'){
				con_20++;
			}
			else if(container_size=='40'){
				con_40++;
			}

			

			cont_status=jsonData.cnfEquipmentDemand[i].cont_status;
			
			var rowCount = table.rows.length;
			if(rowCount == 0)
			{
				var rowCount = table.rows.length;
				var row = table.insertRow(rowCount);
				row.insertCell().innerHTML = "Container";
				row.insertCell().innerHTML = "Size";
				row.insertCell().innerHTML = "Height";
				row.insertCell().innerHTML = "Cont Status";
				row.insertCell().innerHTML = "Position";
				row.insertCell().innerHTML = "Yard/Shed";
				rowCount++;
			}

			var row = table.insertRow(rowCount);
			
			row.insertCell().innerHTML = container_no;
			row.insertCell().innerHTML = container_size;
			row.insertCell().innerHTML = cont_height;
			row.insertCell().innerHTML = cont_status;
			row.insertCell().innerHTML = pos;	
			row.insertCell().innerHTML = yard;	
			
		}

		document.getElementById("total_pkg_weight").value=total_pkg_weight_value;
		document.getElementById("vessel_name").value=vsl_name;
		document.getElementById("pkg_descripton").value=pkg_descripton2;
		document.getElementById("unit_pkg").value=unit_pkg1;
		document.getElementById("cont_40").value=con_40;
		document.getElementById("cont_20").value=con_20;
		document.getElementById("c_no").value=container_no;
		document.getElementById("be_no").value=be_no;
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
						<div class="panel-body">
							<?php
								if(!is_null($this->session->flashdata('success'))){
									echo $this->session->flashdata('success');
								}

								if(!is_null($this->session->flashdata('error'))){
									echo $this->session->flashdata('error');
								}
							?>
						</div>
						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" 
								action="<?php echo site_url('Report/cnfEquipmentDemandSubmit') ?>" >
								<div class="form-group">
									<table class="table table-bordered mb-none" style="width:100%">
										<tr>
											<td align="center"  style="background-color:#006bff45;width:13%;"><b>Demand Date</b></td>
											<td align="center"><b>:</b></td>
											<td>
												<input type="date" class="form-control" id="demand_date"  name="demand_date" style="width:200px;" value="<?php echo date('Y-m-d');?>"/>
											</td>
											<td align="center" style="background-color:#006bff45;"><b>Demand Type</b></td>
											<td align="center"><b>:</b></td>
											<td>
												<select name="demand_type" id="demand_type" maxlength="50" style="width:275px;" class="form-control">
													<option value="">----Select----</option>
													<option value="apprise">APPRISE</option>
													<option value="delivery">DELIVERY</option>												
												</select>
											</td>
										</tr>
									</table>
								</div>	
								
								<div class="form-group">
									<table  class="table table-bordered mb-none" style="width:100%">
										<tr>
											<td align="center" style="background-color:#006bff45;width:13%;"><b>Rotation</b></td>
											<td align="center"><b>:</b></td>
											<td><input type="text" class="form-control" id="rotation"  name="rotation" style="width:200px;" /></td>
											
											<td align="center" style="background-color:#006bff45;"><b>BL No</b></td>
											<td align="center"><b>:</b></td>
											<td><input type="text" class="form-control" id="bl_no"  name="bl_no" style="width:200px;" onblur="myshowCnfEquipmentDemand();"/></td>
										</tr>
										<tr>
											<td align="center" style="background-color:#006bff45;"><b>Vessel Name</b></td>
											<td align="center"><b>:</b></td>
											<td><input type="text" class="form-control"  name="vessel_name" id="vessel_name" style="width:200px;"/></td>
											
											<td align="center" style="background-color:#006bff45;"><b>Shed/Yard No:</b></td>
											<td align="center"><b>:</b></td>
										
											<td>
												<input type="text" class="form-control" id="shed"  name="shed" style="width:200px;" />
											</td>	
										</tr>

										<tr>
											<td align="center" style="background-color:#006bff45;"><b>BE No</b></td>
											<td align="center"><b>:</b></td>
											<td><input type="text" class="form-control" id="be_no" name="be_no" style="width:200px;"/></td>
										</tr>

										<tr>
											<td align="center" style="background-color:#006bff45;"><b>STC</b></td>
											<td align="center"><b>:</b></td>
											<td><input type="text" class="form-control"  id="pkg_descripton"  name="pkg_descripton" style="width:200px;" /></td>

											<td align="center" style="background-color:#006bff45;"><b>Total Pkg Weight</b></td>
											<td align="center"><b>:</b></td>
											<td><input type="text" class="form-control" id="total_pkg_weight" name="total_pkg_weight" style="width:200px;" /></td>	
										</tr>

										<tr>
											<td align="center" style="background-color:#006bff45;"><b>Unit Pkg</b></td>
											<td align="center"><b>:</b></td>
											<td><input type="text" class="form-control" id="unit_pkg" name="unit_pkg" style="width:200px;"/></td>
											<td align="center" style="background-color:#006bff45;"><b>C No</b></td>
											<td align="center"><b>:</b></td>
											<td><input type="text" class="form-control"  id="c_no"  name="c_no" style="width:200px;"/></td>	
											<td><input type="hidden" class="form-control"  id="c_no1"  name="c_no1" style="width:200px;"/></td>
										</tr>


										<tr>
											<td align="center" style="background-color:#006bff45;"><b>Cont 40"</b></td>
											<td align="center"><b>:</b></td>
											<td><input type="text" class="form-control"  id="cont_40"  name="cont_40" style="width:50px;"/></td>	
											<td align="center" style="background-color:#006bff45;"><b>Cont 20"</b></td>
											<td align="center"><b>:</b></td>
											<td><input type="text" class="form-control"  id="cont_20"  name="cont_20" style="width:50px;"/></td>	
										</tr>

										<tr>
											<td align="center" style="background-color:#006bff45;"><b>Hyster(TON)</b></td>
											<td align="center"><b>:</b></td>
											<td>
												<input type="checkbox" class="case" name="hyster_3t" value="1"/>3 TON
												<input type="checkbox" class="case" name="hyster_5t" value="1"/>5 TON
												<input type="checkbox" class="case" name="hyster_10t" value="1"/>10 TON
											</td>
											<td align="center" style="background-color:#006bff45;"><b>Mobile Crane(TON)</b></td>
											<td align="center"><b>:</b></td>
											<td><input type="checkbox" name="mbl_10t" value="1"/>10 TON
												<input type="checkbox" name="mbl_20t" value="1"/>20 TON
												<input type="checkbox" name="mbl_30t" value="1"/>30 TON
												<input type="checkbox" name="mbl_50t" value="1"/>50 TON
											</td>
										</tr>
									</table>
								
								</div>	
								<div class="form-group">
										<table class="table table-bordered table-striped table-hover" style="background-color:#006bff45;" id="tableData" width="100%">
											<!-- <tr>
												<td style="background-color:#006bff45;"><b>Container</b></td>
												<td style="background-color:#006bff45;"><b>Size</b></td>
												<td style="background-color:#006bff45;"><b>Height</b></td>
												<td style="background-color:#006bff45;"><b>Cont Status</b></td>
												<td style="background-color:#006bff45;"><b>Position</b></td>
												<td style="background-color:#006bff45;"><b>Yard/Shed</b></td>
											</tr> -->
										</table>
								</div>	
										
								<div class="row">
									<div class="col-sm-12 text-center">
										<button type="submit" id="submit" name="report" class="mb-xs mt-xs mr-xs btn btn-success">Apply</button>
									</div>													
								</div>
							</form>
						</div>
								
					</section>
				</div>
			</div>	



		

		<!-- end: page -->
	</section>
	</div>

