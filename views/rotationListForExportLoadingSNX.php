<script>
	function chkConfirm()
	{
		if (confirm("Do you want to proceed ?") == true)
			{
				return true ;
			}
		else
			{
				return false;
			}
	}
	
	function setValueForPrepare(rot_no){
		document.getElementById("rot_no").value=rot_no;
		var rot = rot_no.replace("/", "_");
		//alert(rot);
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
		var url = "<?php echo site_url('Report/getVisitIdByRotation');?>?rot="+rot;
		//alert(url);
		xmlhttp.onreadystatechange=stateChangeVisitIdForPreparation;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}
	function stateChangeVisitIdForPreparation()
	{
		//alert(xmlhttp.responseText);
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
		  var selectList=document.getElementById("visit_id_prepare");
		  removeOptions(selectList);
		  //alert(xmlhttp.responseText);
		  var val = xmlhttp.responseText;
		  var jsonData = JSON.parse(val);
		  //alert(xmlhttp.responseText);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].VISIT_ID;
				option.text = jsonData[i].VISIT_ID;
				selectList.appendChild(option);
				//alert(jsonData[i].product_name);
			}
		}
	}
	
	
	function setValueForFinal(rot_no){
		document.getElementById("rot_no_for_final").value=rot_no;
		var rot = rot_no.replace("/", "_");
		//alert(rot);
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
		var url = "<?php echo site_url('Report/getVisitIdByRotation');?>?rot="+rot;
		//alert(url);
		xmlhttp.onreadystatechange=stateChangeVisitIdForFinal;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}
	function stateChangeVisitIdForFinal()
	{
		//alert(xmlhttp.responseText);
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
		  var selectList=document.getElementById("visit_id_final");
		  removeOptions(selectList);
		  //alert(xmlhttp.responseText);
		  var val = xmlhttp.responseText;
		  var jsonData = JSON.parse(val);
		  //alert(xmlhttp.responseText);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].VISIT_ID;
				option.text = jsonData[i].VISIT_ID;
				selectList.appendChild(option);
				//alert(jsonData[i].product_name);
			}
		}
	}
	function removeOptions(selectbox)
	{
		var i;
		for(i=selectbox.options.length-1;i>=1;i--)
		{
			//selectbox.remove(i);
			selectbox.children[i].remove();
		}
	}
</script>
<?php 
	$Control_Panel = $this->session->userdata('Control_Panel'); 
	$org_Type_id = $this->session->userdata('org_Type_id'); 
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>	
		<div class="right-wrapper pull-right"></div>
	</header>
		<!-- start: Table -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">
						
							<div class="container">
								<div class="modal fade" id="formPrepare">
									<div class="modal-dialog modal-block-sm">
										<div class="modal-content">
											<!-- Modal Header -->
											<div class="modal-header">
												<h4 class="modal-title">DOWNLOAD SNX (PREPARE)</h4>
												<button type="button" class="close" data-dismiss="modal">&times;</button>
											</div>

											<div class="modal-body">
												<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/exportLoadingSNX')?>">
													<div class="form-group">
														<div class="row">
															<label class="col-md-2 control-label">&nbsp;</label>
															<div class="col-md-8">
																<div class="input-group mb-md">
																	<span class="input-group-addon span_width">Rotation</span>
																	<input type="text" class="form-control" name="rot_no" id="rot_no" readonly required>
																</div>
															</div>
														</div>
														<div class="row">
															<label class="col-md-2 control-label">&nbsp;</label>
															<div class="col-md-8">
																<div class="input-group mb-md">
																	<span class="input-group-addon span_width">Visit ID</span>
																		<select name="visit_id_prepare" id="visit_id_prepare" 
																		class="form-control" required>
																			<option value="">--Select Visit ID--</option>
																		</select>
																</div>
															</div>
														</div>
														<div class="row">
															<label class="col-md-2 control-label">&nbsp;</label>
															<div class="col-md-8">
																<div class="input-group mb-md">
																	<span class="input-group-addon span_width">Date</span>
																	<input type="date" class="form-control" name="rot_date" id="rot_date" required>
																</div>
															</div>
														</div>
														<div class="row">
															<label class="col-md-2 control-label">&nbsp;</label>
															<div class="col-md-8">
																<div class="input-group mb-md">
																	<span class="input-group-addon span_width">Time</span>
																	<input type="text" name="rot_time" id="rot_time" data-plugin-masked-input data-input-mask="99:99:99" placeholder="__:__:____" class="form-control" required>
																</div>
															</div>
														</div>
														
														
														<div class="row">
															<div class="col-sm-12 text-center">
																<input type="hidden" name="prepare_btn" id="prepare_btn" value="prepare">
																<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">
																	DOWNLOAD
																</button>
															</div>
														</div>
														<div class="row">
															<div class="col-sm-12 text-center">
																
															</div>
														</div>
													</div>
												</form>
											</div>
											<!-- Modal footer -->
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											</div>

										</div>
									</div>
								</div>
							</div>
							
							<div class="container">
								<div class="modal fade" id="formFinal">
									<div class="modal-dialog modal-block-sm">
										<div class="modal-content">
											<!-- Modal Header -->
											<div class="modal-header">
												<h4 class="modal-title">DOWNLOAD SNX (FINAL)</h4>
												<button type="button" class="close" data-dismiss="modal">&times;</button>
											</div>

											<div class="modal-body">
												<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/exportLoadingSNX')?>">
													<div class="form-group">
														<div class="row">
															<label class="col-md-2 control-label">&nbsp;</label>
															<div class="col-md-8">
																<div class="input-group mb-md">
																	<span class="input-group-addon span_width">Rotation</span>
																	<input type="text" class="form-control" name="rot_no_for_final" id="rot_no_for_final" readonly required>
																</div>
															</div>
														</div>
														<div class="row">
															<label class="col-md-2 control-label">&nbsp;</label>
															<div class="col-md-8">
																<div class="input-group mb-md">
																	<span class="input-group-addon span_width">Visit ID</span>
																		<select name="visit_id_final" id="visit_id_final" class="form-control" required>
																			<option value="">--Select Visit ID--</option>
																		</select>
																</div>
															</div>
														</div>
														<div class="row">
															<label class="col-md-2 control-label">&nbsp;</label>
															<div class="col-md-8">
																<div class="input-group mb-md">
																	<span class="input-group-addon span_width">Date</span>
																	<input type="date" class="form-control" name="rot_date" id="rot_date" required>
																</div>
															</div>
														</div>
														<div class="row">
															<label class="col-md-2 control-label">&nbsp;</label>
															<div class="col-md-8">
																<div class="input-group mb-md">
																	<span class="input-group-addon span_width">Time</span>
																	<input type="text" name="rot_time" id="rot_time" data-plugin-masked-input data-input-mask="99:99:99" 
																	placeholder="__:__:____" class="form-control" required>
																</div>
															</div>
														</div>
														
														
														<div class="row">
															<div class="col-sm-12 text-center">
																<input type="hidden" name="final_btn" id="final_btn" value="final">
																<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">
																	DOWNLOAD
																</button>
															</div>
														</div>
														<div class="row">
															<div class="col-sm-12 text-center">
																
															</div>
														</div>
													</div>
												</form>
											</div>
											<!-- Modal footer -->
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											</div>

										</div>
									</div>
								</div>
							</div>
						
							<div align="center"><b><?php echo $msg;?></b></div>
							<table class="table table-bordered table-striped mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">Rotation No</th>
										<th class="text-center">Total Container</th>
										<th class="text-center">Vessel Name</th>
										<th class="text-center">Action</th>
										<th class="text-center">Action</th>
										<th class="text-center">Action</th>
										
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($rotList);$i++){ ?>
									<tr>
										<td align="center"><?php echo $rotList[$i]['rotation'];?></td>
										<td align="center"><?php echo $rotList[$i]['total_cont'];?></td>
										<td align="center"><?php echo $rotList[$i]['vessel_name'];?></td>
																				
										<td align="center" style="height:5%;">
										
											<input type="button" onclick="setValueForPrepare('<?php echo $rotList[$i]['rotation'];?>');"
											value="PREPARE (SNX)" class="btn btn-sm btn-success" 
											data-toggle="modal" data-target="#formPrepare"/>
											
											<!--form action="<?php echo site_url("Report/exportLoadingSNX");?>" method="post">
												<input type="hidden" name="rot_no" id="rot_no" 
													value="<?php echo $rotList[$i]['rotation'];?>">
												<input type="hidden" name="prepare_btn" id="prepare_btn" value="prepare">
												<input type="submit" value="PREPARE (SNX)" class="btn btn-sm btn-success">
											</form-->
										</td>
										<td align="center" style="height:5%;">
											
											<input type="button" onclick="setValueForFinal('<?php echo $rotList[$i]['rotation'];?>');"
											value="FINAL (SNX)" class="btn btn-sm btn-success" data-toggle="modal" data-target="#formFinal"/>
											
											<!--form action="<?php echo site_url("Report/exportLoadingSNX");?>" method="post">
												<input type="hidden" name="rot_no_for_final" id="rot_no_for_final" 
													value="<?php echo $rotList[$i]['rotation'];?>">
												<input type="hidden" name="final_btn" id="final_btn" value="final">
												<input type="submit" value="FINAL (SNX)" class="btn btn-sm btn-success">
											</form-->
											
										</td>	
										<td align="center" style="height:5%;">
											<form action="<?php echo site_url("Report/updateSnxTypeByRotaion");?>" method="post" 
												onsubmit="return chkConfirm();">
												<input type="hidden" name="update_rot" id="update_rot" 
													value="<?php echo $rotList[$i]['rotation'];?>">
												<input type="submit" value="DONE" class="btn btn-sm btn-success">
											</form>
										</td>
									</tr>
									<?php } ?>									
								</tbody>
							</table>
						</div>
					</section>
			
				</div>
			</div>	
		<!-- end: Table -->
	</section>
	</div>