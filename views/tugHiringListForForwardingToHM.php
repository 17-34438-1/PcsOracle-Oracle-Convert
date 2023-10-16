<script>
	function selectAll(state)
		{
			var tugHireCnt = document.getElementById('tugHireCnt').value;
			if(state.checked == true)
			{
				//If "All" is checked;
				for(var p=0;p<tugHireCnt;p++)
				{
					var sts = document.getElementById("idchk"+p).disabled;
					if(sts == false){
						document.getElementById("idchk"+p).checked = true;
					}
				}
			}
			else
			{
				//If "All" is not checked;
				for(var p=0;p<tugHireCnt;p++)
				{
					document.getElementById("idchk"+p).checked = false;						
				}
			}
			
			var numberOfChecked = 0;
			
			if(document.getElementById('allcheck').checked==true)
			{
				numberOfChecked = $('input:checkbox:checked').length -1;
			}
			else
			{
				numberOfChecked = $('input:checkbox:checked').length;
			}
			
			if(numberOfChecked>0){
				document.getElementById('forward').disabled = false;
			}else{
				document.getElementById('forward').disabled = true;
			}
			
			deleteRows();
			addRows(numberOfChecked);
			
		}
		
	function selectCheck(state)
		{
			var numberOfChecked = 0;
			if(document.getElementById('allcheck').checked==true)
			{
				numberOfChecked = $('input:checkbox:checked').length -1;
			}
			else
			{
				numberOfChecked = $('input:checkbox:checked').length;
			}
			//alert(numberOfChecked);
			//document.getElementById("item").innerHTML = numberOfChecked;

			if(numberOfChecked>0){
				document.getElementById('forward').disabled = false;
			}else{
				document.getElementById('forward').disabled = true;
			}

			deleteRows();
			addRows(numberOfChecked);
			
		}
		
	function deleteRows(){			
		var table = document.getElementById('summaryTable');
		var rowCount = table.rows.length;			
		for(r=rowCount;r>1;r--){				
			table.deleteRow(r-1);							
		}
	}
	
	function addRows(numberOfChecked){
		var table = document.getElementById('summaryTable');
		var rowCount = table.rows.length;
		var cellCount = table.rows[0].cells.length;			
		var tugHireCnt = document.getElementById('tugHireCnt').value;
		var totalChecked = 0;
		
		// var totalrow = table.insertRow(rowCount);
		// for(var t =0; t < cellCount; t++){
			// var totalcell = 'totalcell'+t;
			// totalcell = totalrow.insertCell(t);
			// if(t == 0){
				// totalcell.innerHTML="<b>Total<b>";
			// } else if(t == 1){
				// totalcell.innerHTML="<b>"+numberOfChecked+"<b>";
			// }
			// totalcell.style.border = "2px solid black";
		// }
		
		
		for(var p=0;p<tugHireCnt;p++)
		{
			if (document.getElementById('idchk'+p).checked)
			{
				totalChecked++;
				var selectedVesselName = document.getElementById("vesselName"+p).value;
				var selectedTotalHours = document.getElementById("totalHours"+p).value;
				var row = table.insertRow(rowCount);
				for(var r =0; r < cellCount; r++){
					var cell = 'cell'+r;
					cell = row.insertCell(r);
					if(r == 0){
						cell.innerHTML=selectedVesselName;
					} else if(r == 1){
						cell.innerHTML=selectedTotalHours;
					}
					cell.style.border = "2px solid black";
				}					
			}
		}	

		if(totalChecked > 0){				
			document.getElementById("summaryDiv").style.display = "block";				
		} else {
			document.getElementById("summaryDiv").style.display = "none"; 
		}
		
	}
</script>

<?php include("mydbPConnection.php"); ?>
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
								<a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a>
							</h2>								
						</header-->
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" 
								action="<?php echo site_url('Vessel/tugHiringSearch') ?>">
								<input type="hidden" name="ivalue" id="ivalue" value="0">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
										</div>
									</div>
									<div class="col-md-6 col-md-offset-2">										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">From Date</span>
											<input type="date" class="form-control" name="from_date" id="from_date"
											value="<?php if($frmType=="search") echo $from_date; ?>"  required>
										</div>	
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">To Date</span>
											<input type="date" class="form-control" name="to_date" id="to_date" 
											value="<?php if($frmType=="search") echo $to_date; ?>" required>
										</div>	
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">SEARCH</button>
										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="panel-body">	
							<form class="form-horizontal form-bordered" method="POST" 
								action="<?php echo site_url('Vessel/forwardTugHiringToHM') ?>">
								<div class="row">
									<div class="table-responsive" style="margin-top: 20px; margin-bottom: 20px">
										<input type="hidden" id="tugHireCnt" name="tugHireCnt" value="<?php echo count($tugHiringList);?>">
										<table border="1" class="table table-responsive table-bordered">
											<thead>
												<tr>
													<th style="text-align: center">
														<nobr>
															<input type="checkbox" id="allcheck" onclick="selectAll(this);">
															Forward All 
														</nobr>
													</th>
													<th style="text-align: center">Vessel</th>
													<th style="text-align: center">Location</th>
													<th style="text-align: center">Rotation</th>
													<th style="text-align: center">Description</th>
													<th style="text-align: center">Timing</th>	
													<th style="text-align: center">Hours</th>		
												</tr>
											</thead>
											<?php 
												for($i=0;$i<count($tugHiringList);$i++) { 
											?>
											<tr>
												<td align="center">
													<input type="checkbox" name="idchk[]" id="idchk<?php echo $i;?>"  
													onclick="selectCheck(this);" value="<?php echo $tugHiringList[$i]['id']; ?>" >
												</td>		
												<td align="center">
													<?php echo $tugHiringList[$i]['vessel_name'];?>
													<input type="hidden" class="form-control" id="vesselName<?php echo $i;?>" 
														value="<?php echo $tugHiringList[$i]['vessel_name']; ?>">
												</td>		
												<td align="center">
													<?php 
														if($tugHiringList[$i]['location'] == "inside")
															echo "Inside Port";
														else if($tugHiringList[$i]['location'] == "outside")
															echo "Outside Port";
													?>
												</td>		
												<td align="center"><?php echo $tugHiringList[$i]['rotation'];?></td>		
												<td align="center"><?php echo $tugHiringList[$i]['description'];?></td>		
												<td align="center">
													<?php 
														$id = $tugHiringList[$i]['id'];
														$sql_timing = "SELECT vehicle_name,from_date,from_time,to_date,to_time
																	FROM tug_hire_timing WHERE tug_hire_id='$id'";
														$rslt_timing = mysqli_query($con_cchaportdb,$sql_timing);
														
														while($row_timing = mysqli_fetch_object($rslt_timing))
														{
															echo "<b>".$row_timing->vehicle_name."</b> : ".date( 'd/m/Y', strtotime($row_timing->from_date) )." ".$row_timing->from_time." to ".date( 'd/m/Y', strtotime($row_timing->from_date) )." ".$row_timing->to_time."<br>";
														}
													?>
												</td>		
												<td align="center">
													<?php echo $tugHiringList[$i]['total_hours'];?>
													<input type="hidden" class="form-control" id="totalHours<?php echo $i;?>" 
														value="<?php echo $tugHiringList[$i]['total_hours']; ?>">
												</td>		
											</tr>
											<?php } ?>	
										</table>
									</div>
								</div>		
								<div class="row">
									<div class="col-sm-12 text-center">
										<button type="submit" name="forward" id="forward" class="mb-xs mt-xs mr-xs btn btn-primary" disabled>
											FORWARD
										</button>
									</div>
								</div>
							</form>
						</div>
					</section>
					<div class="row table-responsive" id="summaryDiv" style="display:none;">
						<div class="col-sm-6 col-sm-offset-3 text-center">
							<table border="2" class="table table-responsive table-striped" id="summaryTable" style="border:2px solid black;">
								<thead>
									<tr style="border-bottom:2px solid black;">
										<th class="text-center" style="border-bottom:2px solid black;border-right:2px solid black;">
											Vessel Name
										</th>
										<th class="text-center" style="border-bottom:2px solid black;border-left:2px solid black;">
											Total Hours
										</th>
									</tr>
								</thead>
								<tbody>
									<tr style="border-top:2px solid black;">
										<td class="text-center">&nbsp;</td>
										<td class="text-center">&nbsp;</td>
										<td class="text-center">&nbsp;</td>
										<td class="text-center">&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>
<?php mysqli_close($con_cchaportdb); ?>
	