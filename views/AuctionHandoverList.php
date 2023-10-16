<script>
	function chkDate()
		{
			var fdate = document.getElementById("from_date").value;
			var tdate = document.getElementById("to_date").value;
			if(fdate==tdate)
			{
				return true;
			}
			else if(fdate < tdate)
			{
				return true;
			}
			else if(fdate > tdate)
			{
				alert("Wrong combination of date !");
				return false;
			}
		}
		
			
	function getBLlist(rot_no) 
	{	
		document.getElementById("imp_rot").value=rot_no;
		let rot = rot_no.replace("/", "_");	
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
		xmlhttp.onreadystatechange=stateCheckBLlist;
		xmlhttp.open("GET","<?php echo site_url('AjaxController/getBLlist')?>?rot_no="+rot,false);
		xmlhttp.send();	
		return xmlhttp.onreadystatechange();		
	}

	function stateCheckBLlist()
	{
		//alert("ddfd");
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{				
			var selectList=document.getElementById("bL");
			removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);			
			document.getElementById("rl_no").value ="";
			document.getElementById("rl_dt").value="";
			document.getElementById("handover_id").value="";


			for (var i = 0; i < jsonData.length; i++) 
			{
				//var  sl = jsonData[i].sl;
				var option = document.createElement('option');
				option.value = jsonData[i].bl_no;  //value of option in backend
				option.text = jsonData[i].bl_no;	  //text of option in frontend
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
	
	
	function getRLinfo(bl)
	{		
		// alert(yard);
		let rot_no=document.getElementById("imp_rot").value;
		let rot = rot_no.replace("/", "_");	
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeRLInfo;
		xmlhttp.open("GET","<?php echo site_url('AjaxController/getRLinfo')?>?bl="+bl+"&rot_no="+rot,false);
					
		xmlhttp.send();
	}
	
	
	function stateChangeRLInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			//alert(jsonData.length);
			document.getElementById("rl_no").value ="";
			document.getElementById("rl_dt").value="";
			document.getElementById("handover_id").value="";
			document.getElementById("bl_typ").value="";

			document.getElementById("rl_no").value = jsonData[0].rl_no;  
			document.getElementById("rl_dt").value = jsonData[0].rl_date;	   
			document.getElementById("handover_id").value = jsonData[0].id;	   
			document.getElementById("bl_typ").value = jsonData[0].bl_type;	   
									
		}
	}  	
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>	
		<div class="right-wrapper pull-right"></div>
	</header>
	<!-- start: Table -->
	<div class="row">
		<div class="col-lg-12">	
			<section class="panel">
				<!--header class="panel-heading">
					<h2 class="panel-title" align="right">
						<a href="<?php echo site_url('ControllerName/FunctionName') ?>">
							<button style="margin-left: 35%" class="btn btn-primary btn-sm">
								<i class="fa fa-list"></i>
							</button>
						</a>
					</h2>								
				</header-->
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST"
						action="<?php echo site_url('Report/AuctionHandOverReportList') ?>" onsubmit="return chkDate();">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php  if(isset($msg)) {echo $msg;} ?>
								</div>
							</div>
							<div class="col-md-6 col-md-offset-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Unit <span class="required">*</span></span>
									<select class="form-control" name="unit" id="unit" required>
										<option value="">--Select--</option>
											<option value="U1" <?php if($search=="1" and $unit=="U1") { echo "selected";} ?>>
												U1
											</option>
											<option value="U2" <?php if($search=="1" and $unit=="U2") { echo "selected";} ?>>
												U2
											</option>
											<option value="U3" <?php if($search=="1" and $unit=="U3") { echo "selected";} ?>>
												U3
											</option>
											<option value="U4" <?php if($search=="1" and $unit=="U4") { echo "selected";} ?>>
												U4
											</option>
											<option value="U5" <?php if($search=="1" and $unit=="U5") { echo "selected";} ?>>
												U5
											</option>
											<option value="U6" <?php if($search=="1" and $unit=="U6") { echo "selected";} ?>>
												U6
											</option>
											<option value="U7" <?php if($search=="1" and $unit=="U7") { echo "selected";} ?>>
												U7
											</option>
											<option value="U8" <?php if($search=="1" and $unit=="U8") { echo "selected";} ?>>
												U8
											</option>
											<option value="U9" <?php if($search=="1" and $unit=="U9") { echo "selected";} ?>>
												U9
											</option>
											<option value="U10" <?php if($search=="1" and $unit=="U10") { echo "selected";} ?>>
												U10
											</option>
											<option value="U11" <?php if($search=="1" and $unit=="U11") { echo "selected";} ?>>
												U11
											</option>
											<option value="U12" <?php if($search=="1" and $unit=="U12") { echo "selected";} ?>>
												U12
											</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
									<input type="date" name="from_date" id="from_date" class="form-control" 
										value="<?php if($search=="1") echo $from_date; else echo "";?>" required>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
									<input type="date" name="to_date" id="to_date" class="form-control" 
										value="<?php if($search=="1") echo $to_date; else echo "";?>" required>
								</div>
							</div>						
							<div class="row">
								<div class="col-sm-12 text-center">
									<input type="hidden" name="search" id="search" class="form-control" value="<?php echo $search; ?>" required>
									<button type="submit" name="btnSearch" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
			<section class="panel">
			
						<div class="container">
					<div class="modal fade" id="opbcModal">
						<div class="modal-dialog modal-block-sm">
							<div class="modal-content">
								<!-- Modal Header -->
								<div class="modal-header">
									<h4 class="modal-title" align="center">OPBC Form</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>

								<div class="modal-body">
									<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/auctionOpbcDataSave") ?>" onsubmit>
										<input type="hidden" class="form-control" id="formType" name="formType" 
											value="<?php echo $formType; ?>">
										<input type="hidden" class="form-control" id="demand_id" name="demand_id" 
											value="<?php echo $demand_id; ?>">
										<div class="form-group">
											<div class="col-md-12">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
													<input type="text" name="imp_rot" id="imp_rot" class="form-control" 
													tabindex="1" required readonly>
												</div>
												<div class="input-group mb-md">							
													<span class="input-group-addon span_width">BL <span class="required">*</span></span>
														<select name="bL" id="bL" class="form-control"  onchange="getRLinfo(this.value)" >
															<option value="ALL">---Select---</option>																						
														</select>
												</div>	
												
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">R/L No<span class="required">*</span></span>
													<input type="text" name="rl_no" id="rl_no" class="form-control"   required readonly>
												</div>
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">R/L Date <span class="required">*</span></span>
													<input type="date" name="rl_dt" id="rl_dt" class="form-control" required readonly>						 
												</div>			
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">OPBC No <span class="required">*</span></span>
													<input type="text" name="opdc_no" id="opdc_no" class="form-control"   required>
													<input type="hidden" name="handover_id" id="handover_id" >
													<input type="hidden" name="bl_typ" id="bl_typ" >
												</div>
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">OPBC Date <span class="required">*</span></span>
													<input type="date" name="opdc_dt" id="opdc_dt" class="form-control login_input_text" required>						 
												</div>						
									
												
											</div>
											<div class="row" id="applyBtn">
												<div class="col-sm-6 text-right">
													<button type="submit" class="btn btn-primary btn-sm"  onclick="return confirm('So, you are saving correct OPBC information. do you want to proceed?')">
														Save
													</button>
												</div>
												<div class="col-sm-6 text-left">
													<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
														Close
													</button>
												</div>
											</div>
										</div>
									</form>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php  if(isset($msgUpdt)) {echo $msgUpdt;} ?>
						</div>
					</div>
				<div class="panel-body">					
					<table class="table table-bordered mb-none" id="datatable-default">
						<thead>
							<tr>
								<th class="text-center">#Sl</th>
								<th class="text-center">Rotation No</th>
								<th class="text-center">Arrival Date</th>
								<th class="text-center">C/L Date</th>
								<th class="text-center">Unit</th>
								<th class="text-center">Action</th>					
							</tr>
						</thead>
						<tbody>
							<?php for($i=0;$i<count($auction_handover_List);$i++){ 
									$imp_rot="";
									$imp_rot=$auction_handover_List[$i]['rotation_no'];
							?>
							<tr>
								<td align="center"><?php echo $i+1;?></td>
								<td align="center"><?php echo $auction_handover_List[$i]['rotation_no'];?></td>
								<td align="center"><?php echo $auction_handover_List[$i]['arrival_date'];?></td>
								<td align="center"><?php echo $auction_handover_List[$i]['cl_date'];?></td>
								<td align="center"><?php echo $auction_handover_List[$i]['unit'];?></td>
								<td align="center">
									<div class="row">
										<div class="col-sm-6 text-right">
											<form action="<?php echo site_url("Report/AuctionHandOverReportForm");?>" method="post" target="_blank">
												<input type="hidden" class="form-control" name="rotation" id="rotation" value="<?php echo $auction_handover_List[$i]['rotation_no']; ?>">
												<input type="hidden" class="form-control" name="action" id="action" value="print">
												<button type="submit" class="btn btn-success">View</button>	
											</form>
										</div>
										<div class="col-sm-6 text-left">
											<button type="text" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#opbcModal" onclick="getBLlist('<?php echo $imp_rot;?>')">OPBC</button>
										</div>
									</div>	
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