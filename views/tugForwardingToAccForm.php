<script>
	function selectAll(state)
	{	
		var totalRow = document.getElementById('totalRow').value;
		if(state.checked == true)
		{
			//If "All" is not checked;
			for(var p=0;p<totalRow;p++)
			{
				//document.getElementById("rotchk"+p).checked = true;
				var sts = document.getElementById("idchk"+p).disabled;
				if(sts == false){
					document.getElementById("idchk"+p).checked = true;
				}

			}
			
		}
		else
		{
			//If "All" is not checked;
			for(var p=0;p<totalRow;p++)
			{
				//document.getElementById("rotchk"+p).checked = false;						
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
		
		// deleteRows();
		// addRows(numberOfChecked);
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
		var totalRot = document.getElementById('rotCount').value;
		var totalChecked = 0;
		
		var totalrow = table.insertRow(rowCount);
		for(var t =0; t < cellCount; t++){
			var totalcell = 'totalcell'+t;
			totalcell = totalrow.insertCell(t);
			if(t == 0){
				totalcell.innerHTML="<b>Total<b>";
			} else if(t == 1){
				totalcell.innerHTML="<b>"+numberOfChecked+"<b>";
			}
			totalcell.style.border = "2px solid black";
		}
		
		
		for(var p=0;p<totalRot;p++)
		{
			if (document.getElementById('idchk'+p).checked)
			{
				totalChecked++;
				var selectedRotation = document.getElementById("rotNo"+p).value;
				var selectedVessel = document.getElementById("vesselName"+p).value;
				var row = table.insertRow(rowCount);
				for(var r =0; r < cellCount; r++){
					var cell = 'cell'+r;
					cell = row.insertCell(r);
					if(r == 0){
						cell.innerHTML=selectedRotation;
					} else if(r == 1){
						cell.innerHTML=selectedVessel;
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
	
	function creatLastRow(){
		var table = document.getElementById('summaryTable');
		var rowCount = table.rows.length;
		var cellCount = table.rows[0].cells.length;
		var totalRot = document.getElementById('rotCount').value;		
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
		//previous...
		document.getElementById('filela').innerHTML = numberOfChecked;
		document.getElementById("filela").style.fontWeight = 'bold';
		document.getElementById('filela2').innerHTML = numberOfChecked;
		document.getElementById("filela2").style.fontWeight = 'bold';
	}
	
	function validate()
	{
		if(document.getElementById('masterFlag').value=="master")
		{
			if(document.getElementById('fileNo').value=="ডিসি /বিএস/বিবিধ/" || document.getElementById('fileNo').value=="" || document.getElementById('fileNo').value==null)
			{
				alert("Please provide Dispatch No.");
				return false;
			}
			else if(document.getElementById('filedt').value=="")
			{
				alert("Please provide File Date.");
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return true;
		}
	}

	function selectMonth(month)
	{
		monthValue = "";

		if(month == 01){
			monthValue = "জানুয়ারী";
		}
		else if(month == 02)
		{
			monthValue = "ফেব্রুয়ারী";
		}
		else if(month == 03)
		{
			monthValue = "মার্চ";
		}
		else if(month == 04)
		{
			monthValue = "এপ্রিল";
		}
		else if(month == 05)
		{
			monthValue = "মে";
		}
		else if(month == 06)
		{
			monthValue = "জুন";
		}
		else if(month == 07)
		{
			monthValue = "জুলাই";
		}
		else if(month == 08)
		{
			monthValue = "আগস্ট";
		}
		else if(month == 09)
		{
			monthValue = "সেপ্টেম্বর";
		}
		else if(month == 10)
		{
			monthValue = "অক্টোবর";
		}
		else if(month == 11)
		{
			monthValue = "নভেম্বর";
		}
		else if(month == 12)
		{
			monthValue = "ডিসেম্বর";
		}

		document.getElementById('month').value = month;
		document.getElementById('monthValue').value = monthValue;
		document.getElementById('monthinBody').innerHTML = monthValue;
	}
</script>
<?php include("mydbPConnectionn4.php"); ?>
<?php include("mydbPConnection.php"); ?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	
		<div class="right-wrapper pull-right">
			
		</div>
	</header>

	<!-- start: page -->
	<!--div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Month <span class="required">*</span></span>
								<select class="form-control" name="monthVal" id="monthVal" onchange="selectMonth(this.value)">
									<option value="1" <?php echo $monthinNumber == '01'?'selected':'';?>> জানুয়ারী </option>
									<option value="2" <?php echo $monthinNumber == '02'?'selected':'';?>> ফেব্রুয়ারী </option>
									<option value="3" <?php echo $monthinNumber == '03'?'selected':'';?>> মার্চ </option>
									<option value="4" <?php echo $monthinNumber == '04'?'selected':'';?>> এপ্রিল </option>
									<option value="5" <?php echo $monthinNumber == '05'?'selected':'';?>> মে </option>
									<option value="6" <?php echo $monthinNumber == '06'?'selected':'';?>> জুন </option>
									<option value="7" <?php echo $monthinNumber == '07'?'selected':'';?>> জুলাই </option>
									<option value="8" <?php echo $monthinNumber == '08'?'selected':'';?>> আগস্ট </option>
									<option value="9" <?php echo $monthinNumber == '09'?'selected':'';?>> সেপ্টেম্বর </option>
									<option value="10" <?php echo $monthinNumber == '10'?'selected':'';?>> অক্টোবর </option>
									<option value="11" <?php echo $monthinNumber == '11'?'selected':'';?>> নভেম্বর </option>
									<option value="12" <?php echo $monthinNumber == '12'?'selected':'';?>> ডিসেম্বর </option>
								</select>
							</div>								 
						</div>
					</div>	
				</div>
			</section>
		</div>
	</div-->
	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<?php if($msg != null) { ?>
				<header class="panel-heading">
					<h6 class="panel-title" align="center">
						<b><?php echo $msg;?></b>
					</h6>								
				</header>
				<?php } ?>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-1">
							<div align="right">
								<img align="center" width="150px" style="margin:0px;padding:0px;" height="90px" 
								src="<?php echo ASSETS_PATH?>images/cpa_logo.png">
							</div>
						</div>
						<div class="col-md-11">
							<div align="center">
								<h3><b>চট্টগ্রাম বন্দর কর্তৃপক্ষ</b></h3>
								<h4><b><u>নৌ বিভাগ</u></b></h4>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							&nbsp;
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div align="left">
								<p>
									বরাবরে<br/>
									প্রধান অর্থ ও হিসাব রক্ষণ কর্মকর্তা <br/>
									চট্টগ্রাম বন্দর কর্তৃপক্ষ<br/>
									চট্টগ্রাম ।<br/>
								</p>
							</div>
						</div>
						<div class="col-md-6">
							<div align="right">
								<form action="<?php echo site_url('Vessel/forwardTugHiringListToAccounts');?>" method="POST">
								<p>
									তারিখ :  <input type="date" id="forward_date" name="forward_date" width="20px" required/>
								</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div align="left">
								<p>
									<u><b> বিষয়: নৌযান ব্যবহারের তালিকা প্রেরণ প্রসঙ্গে । </b></u>
								</p>
								<p>
									উপরোক্ত বিষয়ে অত্র বিভাগের নৌযান কর্তৃক বিভিন্ন এজেন্সীর অধীনে সম্পাদিত কার্য সম্বলিত বিবরণ যথাযথ ভাবে লিপিবদ্ধ করে পোর্ট মাশুল বই নং-৯৯ এর ক্রমিক নং-৪৯০২-৪৯০৪ পরবর্তী প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত করে প্রেরণ করা হলো।
								</p>
							</div>
						</div>
					</div>
					<div class="row">
							<input type="hidden" id="totalRow" name="totalRow" value="<?php echo count($tugHiringList);?>">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<th class="text-center">
													<nobr>
														<input type="checkbox" id="allcheck" onclick="selectAll(this);">
														Forward All 
													</nobr>
												</th>
												<th class="text-center">SL</th>
												<th class="text-center">Vessel</th>
												<th class="text-center">Location</th>
												<th class="text-center">Rotation</th>
												<th class="text-center">Description</th>
												<th class="text-center">Timing</th>
												<th class="text-center">Hours</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												$sl = 0;
												for($i=0;$i<count($tugHiringList);$i++){ 
												$sl++;
												
											?>
												<tr class="gradeX">
													<td align="center">
														<input type="checkbox" name="idchk[]" id="idchk<?php echo $i;?>"  
														onclick="selectCheck(this,<?php echo $i;?>);" 
														value="<?php echo $tugHiringList[$i]['id']; ?>" >
													</td>
													<td align="center"><?php echo $i+1;?></td>
													<td align="center"><?php echo $tugHiringList[$i]['vessel_name']?></td>
													<td align="center">
														<?php 
															if($tugHiringList[$i]['location'] == "inside")
																echo "Inside Port";
															else if($tugHiringList[$i]['location'] == "outside")
																echo "Outside Port";
														?>
													</td>
													<td align="center"><?php echo $tugHiringList[$i]['rotation']?></td>
													<td align="center"><?php echo $tugHiringList[$i]['description']?></td>
													<td align="center">
														<?php 
															$id = $tugHiringList[$i]['id'];
															$sql_timing = "SELECT vehicle_name,from_date,from_time,to_date,to_time
																		FROM tug_hire_timing WHERE tug_hire_id='$id'";
															$rslt_timing = mysqli_query($con_cchaportdb,$sql_timing);
															
															while($row_timing = mysqli_fetch_object($rslt_timing))
															{
																echo "<b>".$row_timing->vehicle_name."</b> : ".date( 'm-d-Y', strtotime($row_timing->from_date) )." ".$row_timing->from_time." to ".date( 'm-d-Y', strtotime($row_timing->from_date) )." ".$row_timing->to_time."<br>";
															}
														?>
													</td>
													<td align="center">
														<?php echo $tugHiringList[$i]['total_hours'];?>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-md-12">
								<div align="center">								
									<button type="submit" id="forward" name="forward" class="btn btn-primary btn-sm" disabled>
										Forward
									</button>
								</div>
							</div>
						</form>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div align="left">
								সংযুক্তি :-
								<ul type="none">
									<li>১। নৌযান ব্যবহারের তালিকা ( ২x৩) ৬ পাতা ।</li>
									<li>২। পোর্টমাণ্ডল ৪৯০২-৪৯০৪ পাতা।</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-md-offset-8">
							<div align="center">
								<p align="center">
									হারবার মাস্টার<br/>
									<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u>
								</p>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-4">
							<div align="left">
								অনুলিপি :- 								
								<ul type="none">
									<li>১। প্রধান নিরীক্ষা কর্মকর্তা/চবক এর অবগতির জন্য ।</li>
								</ul>								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-md-offset-8">
							<div align="center">
								<p align="center">
									হারবার মাস্টার<br/>
									<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u>
								</p>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>	
	<!-- end: page -->
</section>
</div>
<?php mysqli_close($con_cchaportdb); ?>
<?php mysqli_close($con_sparcsn4); ?>

<?php 	
	function Bengali_DTN($NRS)
	{
		$englDTN = array
				('1','2','3','4','5','6','7','8','9','0',
				'Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday',
				'Sat','Sun','Mon','Tue','Wed','Thu','Fri',
				'am','pm','at','st','nd','rd','th',
				'January','February','March','April','May','June','July','August','September','October','November','December',
				'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',
				'2022','2023','2024','2025','2026','2027','2028','2029','2030');
				$bangDTN = array
				('১','২','৩','৪','৫','৬','৭','৮','৯','০',
				'শনিবার','রবিবার','সোমবার','মঙ্গলবার','বুধবার','বৃহস্পতিবার','শুক্রবার',
				'শনি','রবি','সোম','মঙ্গল','বুধ','বৃহঃ','শুক্র',
				'পূর্বাহ্ণ','অপরাহ্ণ','','','','','',
				'জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর',
				'জানু','ফেব্রু','মার্চ','এপ্রি','মে','জুন','জুলা','আগ','সেপ্টে','অক্টো','নভে','ডিসে',
				'২০২২','২০২৩','২০২৪','২০২৫','২০২৬','২০২৭','২০২৮','২০২৯','২০৩০');

				$converted = str_replace($englDTN, $bangDTN, $NRS);
				return $converted; 
	}	
?>