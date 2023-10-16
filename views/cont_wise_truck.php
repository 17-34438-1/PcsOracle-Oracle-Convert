<html>
	<head>
		<script type="text/javascript">			
			function total_truck_number(val)
			{					
				var total_truck=document.getElementById("total_truck").value;
				
				var table = document.getElementById("truck_number_table");
				removeTableElement(table);
				
				//var t=1;
				
				for(var i=1;i <= total_truck;i++)
				{
					var tr = document.createElement('tr');
					
					var td1 = document.createElement('td');
					var text1 = document.createTextNode("Truck No "+i);
					td1.appendChild(text1);
					
					var td2 = document.createElement('td');
					var text2 = document.createTextNode(":");
					td2.appendChild(text2);
					
					var td3 = document.createElement('td');
					var input = document.createElement("input");
					input.type = "text";
					input.name = "truck_no_"+i;
					input.id = "truck_no_"+i;
					//input.value = "";
					input.style.width = "100px";
					td3.appendChild(input);
					
					tr.appendChild(td1);
					tr.appendChild(td2);
					tr.appendChild(td3);
					
					table.appendChild(tr);
					
					//t++;
				}
			}
			
			function removeTableElement(table)
			{
				var tblLen = table.rows.length;
				
				for(var i=tblLen;i>0;i--)
				{
					table.deleteRow(i-1);
				}				
			}			
			
			function save_action()
			{
				var container_no=document.getElementById('cont_no').value;
				
				document.getElementById("container_no").value=container_no;
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
									<!--header class="panel-heading">
										<h2 class="panel-title" align="right">
											<a href="<?php echo site_url('Controller/List') ?>">
												<button style="margin-left: 35%" class="btn btn-primary btn-sm">
													<i class="fa fa-list"></i>
												</button>
											</a>
										</h2-->								
									</header>
									<div class="form-group" align="center"><b>CONTAINER WISE TRUCK ENTRY</b></div>
									<div class="panel-body" align="center">
									<form name="cont_search_form" id="cont_search_form" method="post" action="<?php echo site_url('Report/cont_wise_truck_search') ?>">

									<div class="form-group">
										<label class="col-md-3 control-label">&nbsp;</label>
										<div class="col-md-6">		
											<div class="input-group mb-md">
												<span class="input-group-addon span_width" style="width:150px;">Container No. <span class="required">*</span></span>
												<input type="text" style="width:180px;" id="cont_no" name="cont_no" value="<?php echo $container_no; ?>" />
											</div>																						
										</div>
																		
												<div class="row">
													<div class="col-sm-12 text-center">
														<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
														<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
														<!--input type="submit" value="Save" name="save" class="login_button"-->
	
													</div>													
												</div>
												</form>
												<div class="row">
													<div class="col-sm-12 text-center">
														
													</div>
												</div>
											</div>	
											
													<br>
						<?php
						if($flag==1)
						{
						?>	
						<div style="width: 50%; height: 50%; float:left;">
							<form name="truck_wise_entry_form" id="truck_wise_entry_form" method="post" action="<?php echo site_url('Report/truck_wise_entry_action')?>">						
								<input type="hidden" id="all_truck" name="all_truck"  />
								<input type="hidden" id="bizu_gkey" name="bizu_gkey" value="<?php echo $rslt_cont_truck_n4[0]['BIZU_GKEY'] ?>" />
								<input type="hidden" id="unit_gkey" name="unit_gkey" value="<?php echo $rslt_cont_truck_n4[0]['UNIT_GKEY'] ?>" />
								<input type="hidden" id="assign_type" name="assign_type" value="<?php echo $rslt_cont_truck_n4[0]['ASSIGN_TYPE'] ?>" />
								<input type="hidden" id="container_no" name="container_no"  />
								<table>
									<tr>
										<th class="gridDark" colspan="3">Form</th>
									</tr>
									<tr>
										<td >Gate No</td>
										<td >:</td>
										<td >
											<select name="gate" id="gate">  
												<option value="">--------Select--------</option>
												<?php for($i=0; $i<count($gateList); $i++){ ?>
													
												<option value="<?php echo $gateList[$i]['ID'];?>"><?php echo $gateList[$i]['ID'];?></option>
												
												<?php } ?>
											</select>	
									    </td>
									</tr>
									<!--tr>
										<td >Container</td>
										<td >:</td>
										<td >
											<input type="text" name="cont_no" id="cont_no" style="width:200px" onblur="return get_cont_data();" />
										</td>
									</tr-->
									<tr>
										<td >Total Truck</td>
										<td >:</td>
										<td >						
												<?php $numTruck=$rslt_chk_entered_truck[0]['number_of_truck']?>
											<input type="text" name="total_truck" id="total_truck" style="width:130px" value="<?php echo $numTruck; ?>" onblur="total_truck_number()" />
										</td>
									</tr>
									<?php
									if(count($rslt_truck_number_list)>0)
									{
									?>
									<tr>
										<td align="center" colspan="3">
											<table name="truck_number_table" id="truck_number_table" >
												<?php
												for($i=0;$i<$numTruck;$i++)
												{
												?>
												<tr>
													<td>Truck No <?php echo $i+1;?> </td>
													<td>:</td>													
													<td><input type="text" name="truck_no_<?php echo $i+1;?>" id="truck_no_<?php echo $i+1;?>" value="<?php echo $rslt_truck_number_list[$i]['truck_number']; ?>" style="width:100px;" <?php if($rslt_truck_number_list[$i]['truck_number']!=""){?> readonly <?php } ?> /></td>
												</tr>
												<?php
												}
												?>
											</table>
										</td>
									</tr>	
									<?php
									}
									else
									{
									?>
									<tr>
										<td align="center" colspan="3">
											<table name="truck_number_table" id="truck_number_table" >
												<tr>
													<td></td>
												</tr>
											</table>
										</td>
									</tr>
									<?php
									}
									?>
									<tr>
										<td colspan="3" align="center">											
											<input type="submit" id="btn_save" name="btn_save" value="Save" class="login_button" onclick="return save_action();" />
										</td>
									</tr>
								</table>
							</form>							
						</div>
						<div style="width: 1%; height: 1%; float:left;">
							&nbsp;
						</div>
											
						<div style="width: 49%; height: 49%; float:right; ">
							<table width="100%" >
								<tr>
									<th class="col-md-6" colspan="3" align="center">INFORMATION</th>
								</tr>
								<tr>
									<td width="20%" >Assignment Type</td>
									<td width="2%" >:</td>
									<td  id="assign_type"><?php echo $rslt_cont_truck_n4[0]['ASSIGN_TYPE'] ?></td>
								</tr>
								<tr>
									<td width="20%" >Assignment Date</td>
									<td width="2%" >:</td>
									<td  id="assign_date"><?php echo $rslt_cont_truck_n4[0]['ASSIGN_DATE'] ?></td>
								</tr>
								<tr>
									<td width="20%" >Rotation</td>
									<td width="2%" >:</td>
									<td  id="rotation"><?php echo $rslt_cont_truck_n4[0]['ROTATION'] ?></td>
								</tr>
								<tr>
									<td width="20%" >Vessel Name</td>
									<td width="2%" >:</td>
									<td  id="vessel_name"><?php echo $rslt_cont_truck_n4[0]['VESSEL_NAME'] ?></td>
								</tr>
								<tr>
									<td width="20%" >C&F</td>
									<td width="2%" >:</td>
									<td  id="cnf"><?php echo $rslt_cont_truck_n4[0]['CNF'] ?></td>
								</tr>
								<tr>
									<td width="20%" >Pack Description</td>
									<td width="2%" >:</td>
									<td  id="Pack_Description"><?php echo $rslt_cont_truck_igm[0]['Pack_Description'] ?></td>
								</tr>
								<tr>
									<td width="20%" >Package Quantity</td>
									<td width="2%" >:</td>
									<td  id="Pack_Number"><?php echo $rslt_cont_truck_igm[0]['Pack_Number'] ?></td>
								</tr>
								<tr>
									<td width="20%" >Size</td>
									<td width="2%" >:</td>
									<td  id="cont_size"><?php echo $rslt_cont_truck_igm[0]['cont_size'] ?></td>
								</tr>
								<tr>
									<td width="20%" >Height</td>
									<td width="2%" >:</td>
									<td  id="cont_height"><?php echo $rslt_cont_truck_igm[0]['cont_height'] ?></td>
								</tr>
							</table>
						</div>	
						<?php
						}
						?>
									</div>
								</section>
						
							</div>
						</div>	
					<!-- end: page -->
				</section>
			</div>
