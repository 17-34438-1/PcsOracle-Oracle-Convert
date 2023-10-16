		<script type="text/javascript">
		//	function truck_entry_validation()
			function truck_entry_validation()
			{
				if( document.truck_entry_form.cnf_name.value == "" )
				{
					alert( "Please provide C&F name!" );
					document.truck_entry_form.cnf_name.focus();
					return false;
				}
				else if( document.truck_entry_form.container_no.value == "" )
				{
					alert( "Please provide Container No.!" );
					document.truck_entry_form.container_no.focus();
					return false;
				}
				else if( document.truck_entry_form.assignment_type.value == "" )
				{
					alert( "Please provide Assignment Type!" );
					document.truck_entry_form.assignment_type.focus();
					return false;
				}
				/*else if( document.truck_entry_form.delivery_time_slot.value == "" )
				{
					alert( "Please provide Delivery Slot!" );
					document.truck_entry_form.delivery_time_slot.focus();
					return false;
				}*/
				else if( document.truck_entry_form.jetty_sarkar.value == "" )
				{
					alert( "Please provide Jetty Sarkar!" );
					document.truck_entry_form.jetty_sarkar.focus();
					return false;
				}
				/*else if( document.truck_entry_form.total_truck.value == "" )
				{
					alert( "Please provide Total Truck Number.!" );
					document.truck_entry_form.total_truck.focus();
					return false;
				}
				else if( document.truck_entry_form.be_no.value == "" )
				{
					alert( "Please provide B/E No.!" );
					document.truck_entry_form.be_no.focus();
					return false;
				}*/
				// else if( document.truck_entry_form.truck_no.value == "" )
				// {
					// alert( "Please provide Truck No.!" );
					// document.truck_entry_form.truck_no.focus() ;
					// return false;
				// }
				else
				{
					truck_data();
				}
				return true ;
			}
			
			function truck_data()
			{
				var total_truck=document.getElementById("total_truck").value;
				
				var truck_no_id="";
				var truck_no="";
				var all_truck="";
				
				var t=1;
				
				for(var i=0;i<total_truck;i++)
				{
					truck_no_id="truck_no_"+t;
					
					truck_no=document.getElementById(truck_no_id).value;
					
					all_truck=all_truck.concat(truck_no);
					all_truck=all_truck.concat(",");
					t++;
				}
			
				var n = all_truck.length;
			
				all_truck = all_truck.substring(0,n-1);
				alert(all_truck);
				document.getElementById("all_truck").value=all_truck;
			}
		
			function get_assignment_dlvtime()
			{
				//alert(document.getElementById("container_no").value);
				if (window.XMLHttpRequest) 
				{
				  xmlhttp=new XMLHttpRequest();
				} 
				else 
				{  
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				
				var container_no=document.getElementById('container_no').value;
				
				var url = "<?php echo site_url('ajaxController/get_assignment_dlvtime')?>?container_no="+container_no;
				
				xmlhttp.onreadystatechange=rtn_assignment_dlvtime;
				xmlhttp.open("GET",url,false);
							
				xmlhttp.send();
			}
			
			function rtn_assignment_dlvtime()
			{			
				if (xmlhttp.readyState==4 && xmlhttp.status==200) 
				{
					var val = xmlhttp.responseText;
				
					var jsonData = JSON.parse(val);
						//alert(jsonData);
					document.getElementById("unit_gkey").value=jsonData[0].gkey;
					document.getElementById("assignment_type").value=jsonData[0].assign_type;
					document.getElementById("delivery_time_slot").value=jsonData[0].dlv_time_slot;					
				}
			}
			
			function total_truck_number()
			{
				var total_truck=document.getElementById("total_truck").value;
				
				var table = document.getElementById("truck_number_table");
				removeTableElement(table);
				
				var t=1;
				
				for(var i=0;i < total_truck;i++)
				{
					var tr = document.createElement('tr');
					
					var td1 = document.createElement('td');
					var text1 = document.createTextNode("Truck No "+t);
					td1.appendChild(text1);
					
					var td2 = document.createElement('td');
					var text2 = document.createTextNode(":");
					td2.appendChild(text2);
					
					var td3 = document.createElement('td');
					var input = document.createElement("input");
					input.type = "text";
					input.name = "truck_no_"+t;
					input.id = "truck_no_"+t;
					//input.value = "";
					input.style.width = "100px";
					td3.appendChild(input);
					
					tr.appendChild(td1);
					tr.appendChild(td2);
					tr.appendChild(td3);
					
					table.appendChild(tr);
					
					t++;
				}
			}
			
			function removeTableElement(table)
			{
				var tblLen = table.rows.length;
				
				for(var i=tblLen;i>1;i--)
				{
					table.deleteRow(i-1);
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
									<header class="panel-heading">
										<h2 class="panel-title" align="right">
											<a href="<?php echo site_url('Controller/List') ?>">
												<button style="margin-left: 35%" class="btn btn-primary btn-sm">
													<i class="fa fa-list"></i>
												</button>
											</a>
										</h2>								
									</header>
						<div class="panel-body" align="center">
					<form name="truck_entry_form" id="truck_entry_form" action="<?php echo site_url("Report/truck_entry_data"); ?>" method="POST" onsubmit="return truck_entry_validation();" enctype="multipart/form-data">
		
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" style="width:250px;">C&F Name <span class="required">*</span>
											<input id="cnf_name" name="cnf_name" type="text" style="width:150px;"  value="<?php echo $rslt_cnf_info[0]['u_name'];?>" readonly /><font color="red" size="4"><b>&nbsp;*</b></font>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" style="width:250px;">Container No. <span class="required">*</span>
											<input id="container_no" name="container_no" type="text"  style="width:150px;" value="<?php if($eflag==1){ echo $rslt_edit_truck[0]['cont_id']; } ?>" onblur="get_assignment_dlvtime()" /><font color="red" size="4"><b>&nbsp;*</b></font>
											<input id="unit_gkey" name="unit_gkey" value="<?php if($eflag==1){ echo $rslt_edit_truck[0]['unit_gkey']; } ?>" type="hidden" />
											<input id="table_id" name="table_id" value="<?php if($eflag==1){ echo $rslt_edit_truck[0]['id']; }?>" type="hidden" />
										</div>			
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" style="width:250px;">Assignment Type <span class="required">*</span>
											<input id="assignment_type" name="assignment_type" type="text" style="width:150px;" value="<?php if($eflag==1){ echo $rslt_edit_truck[0]['assign_type']; } ?>" readonly /><font color="red" size="4"><b>&nbsp;*</b></font>
										</div>		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" style="width:290px;">Jetty Sarkar <span class="required">*</span>
											<select id="jetty_sarkar" name="jetty_sarkar" style="width:150px;">
													<option value="">--Select--</option>
													<?php
													include('dbConection.php');
													
													$sql_jetty_sarkar="SELECT id,js_name FROM ctmsmis.mis_jetty_sirkar WHERE n4_bizu_gkey='$n4_bizu_gkey'";
														//echo 		$sql_jetty_sarkar;		
													$rslt_jetty_sarkar=mysqli_query($sql_jetty_sarkar);
													
													while($row_jetty_sarkar=mysqli_fetch_object($con_sparcsn4,$rslt_jetty_sarkar))
													{
														$jetty_sarkar_id=$row_jetty_sarkar->id;
														$jetty_sarkar_name=$row_jetty_sarkar->js_name;
													?>
														<option value="<?php echo $jetty_sarkar_id; ?>"><?php echo $jetty_sarkar_name; ?></option>
													<?php
													}
													?>
												</select><font color="red" size="4"><b>&nbsp;*</b></font>
										</div>	
										<?php
										if($is_edit==1)
										{
											$truck_id_all=$rslt_edit_truck[0]['truck_id'];
											$cnt=substr_count($truck_id_all,",");
										}
										?>										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" style="width:250px;">Total Truck  <span class="required">*</span>
											<input id="all_truck" name="all_truck" type="hidden" />
											<input id="total_truck" name="total_truck" type="text" style="width:150px;" value="<?php if($is_edit==1) { echo $cnt+1; } ?>" onblur="total_truck_number()" /><font color="red" size="4"><b>&nbsp;*</b></font>
										</div>	
										<?php
										if($is_edit==1)
										{
											$truck_id=$rslt_edit_truck[0]['truck_id'];
										?>	
										<table id="truck_number_table" name="truck_number_table">
										<?php
										$start=0;
										for($i=0;$i<($cnt+1);$i++)
										{
											$no=$i+1;
											$name="truck_no_".($i+1);
											$id="truck_no_".($i+1);
												
											$position=strpos($truck_id,",");	
											if($position==0)
												$truck_no_single=$truck_id;
											else
												$truck_no_single=substr($truck_id,$start,$position);
										?>
										
											<div class="input-group mb-md">
												<span class="input-group-addon span_width" style="width:250px;">Truck No <?php echo $no; ?> <span class="required">*</span>
												<input name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo $truck_no_single; ?>" />
											</div>	
											<!--tr>
												<td>Truck No <?php echo $no; ?></td>
												<td>:</td>
												<td><input name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo $truck_no_single; ?>" /></td>
											</tr-->
										<?php	
										if($position==0)
										{
											$start_next=0;
										}
										else
										{
											$start_next=$position+1;
										}
										$truck_id=substr($truck_id,$start_next);
										}
										?>
										</table>
									<?php } else { ?>	
										<table id="truck_number_table" name="truck_number_table">
											<tr>
												<td></td>
											</tr>
										</table>
										<?php } ?>										
										
									</div>
																					
									<div class="row">
										<div class="col-sm-12 text-center">
											<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
											<button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
											
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											
										</div>
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