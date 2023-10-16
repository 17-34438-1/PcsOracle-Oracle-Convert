<script>
		function selectAllRot(state)
		{
			
			var totAllMeasurement = 0;
			var subTotMeasurement = 0;
			var totalRot = document.getElementById('rotCount').value;
			if(state.checked == true)
			{
				//If "All" is not checked;
				for(var p=0;p<totalRot;p++)
				{
					//document.getElementById("rotchk"+p).checked = true;
					var sts = document.getElementById("idchk"+p).disabled;
					if(sts == false){
						document.getElementById("idchk"+p).checked = true;
					}

					//subTotMeasurement = parseFloat(document.getElementById("rotchk"+p).value);
					//totAllMeasurement = parseFloat(totAllMeasurement)+parseFloat(subTotMeasurement);
				}
				
			}
			else
			{
				//If "All" is not checked;
				for(var p=0;p<totalRot;p++)
				{
					//document.getElementById("rotchk"+p).checked = false;						
					document.getElementById("idchk"+p).checked = false;						
				}
				// Following line is commented because from now on measurement will not change by clicking on chkbox.....
				// document.getElementById("measurement").value = 0;
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

            alert(monthValue);

        }

</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
    <?php
        $year = date("Y");
        $month = date("F");
        $monthinNumber = date("m");
        //$dateData = Bengali_DTN($month).'/'.Bengali_DTN($year);
    ?>
	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									

                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Month <span class="required">*</span></span>
										<select class="form-control" name="month" id="month" onchange="selectMonth(this.value)">
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
						<!-- </form> -->
					</div>
				</section>
			</div>
        </div>	
	<!-- end: page -->
	<?php if($flag==1) 
	{ 
	include("dbConection.php");
	include("mydbPConnection.php");
	if($org_Type_id=='81') { ?>

	<div class="row">	
	<div class="col-lg-12">						
		<div class="panel-body">
		<div class="form-group">
			<div class="col-md-1">
				<div class="input-group mb-md">
					<img align="center" width="150px" style="margin:0px;padding:0px;" height="90px" src="<?php echo ASSETS_PATH?>images/cpa_logo.png">

				</div>
			</div>
			<div class="col-md-9">
				<table align="center">
					<tr>
						<td align="center"><font size=4><u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u></font>
								<br/><u>নৌ বিভাগ</u> 
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		
			<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/vslNotEnteringForwardingPerform") ?>" onsubmit="return validate()">
			<input type="hidden" name="masterFlag" id="masterFlag" value="<?php echo $masterFlag; ?>" />								
			<table width=100%>
				
				<tr>
					<td align="left">
					বরাবরে  <br/>
					উপ-প্রধান অর্থ ও হিসাব রক্ষণ কর্মকর্তা <br/>
					চট্টগ্রাম বন্দর কর্তৃপক্ষ <br/>
					চট্টগ্রাম । <br/>
					বিষয়:  
                    
                    <input type="text" style="width:700px; height:25px;" id="filesub" name="filesub" value="<?php '/'.Bengali_DTN($year); ?> ইং মাসে বন্দর বহিঃনোঙ্গর হতে সেইল করেছে এরুপ জাহাজের একখানা তালিকা প্রেরণ প্রসঙ্গে।" readonly /> <br/>
					</td>
				</tr>
				<tr><td><br/></td></tr>
			
				 <br/>
				<tr>
					
					<td align="left">
						<?php echo $Bengali_DTN($month).'/'.Bengali_DTN($year); ?> ইং মাসে বন্দর বহিঃনোঙ্গর হইতে চলিয়া যাওয়া সংক্রান্ত জাহাজের বিবরণ সম্বলিত চার খানা তালিকা অর্থ ও হিসাব বিভাগ পরবর্তী ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত ও প্রেরণ করা হল।
					</td>
				</tr>
			</table>
			<?php } ?>		
			<section class="panel">
			<?php if($org_Type_id!='81') { ?>
			<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/vslNotEnteringForwardingPerform") ?>">
			<?php } ?>
				
				<div class="row">
					<div class="col-sm-12 text-center">
						<?php echo $msg;?>
					</div>
				</div>
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<input type="hidden" id="rotCount" name="rotCount" value="<?php echo count($departData);?>">
						
						<thead>
							<tr>
							<?php 
								// if($login_id!='acc') 
								if($section!='billop') 
								{ 
							?>
								<th rowspan="2" align="center"><nobr>Forward All <input type="checkbox" id="allcheck" onclick="selectAllRot(this);"></nobr></th>
							<?php 
								} 
							?>
								<th rowspan="2" class="text-center">SL</th>
								<th rowspan="2" class="text-center"><nobr>Rotation</nobr></th>
								<th rowspan="2" class="text-center"><nobr>Arrival Time</nobr></th>
								
								<th rowspan="2" class="text-center"><nobr>Depart Time</nobr></th>
								<th rowspan="2" class="text-center">Vessel Name</th>
								<th rowspan="2" class="text-center">Country</th>
								<th colspan="2" class="text-center">Tonage</th>
								
								<th rowspan="2" class="text-center">Local Agent</th>
							<?php
								if($org_Type_id!=83)
								{
							?>	
								<th rowspan="2" class="text-center">Forwarded Date</th>								
							<?php 
								} 
							?>
							<?php 
								// if($login_id=='acc') 
								if($section=='billop') 
								{ 
							?>
								<th rowspan="2" class="text-center"><nobr>Bill</th>
							<?php 
								} 
							?>
							</tr>

							<tr>
								<th class="text-center">GT</th>
								<th class="text-center">NT</th>
							</tr>
						</thead>
						<?php 
							for($i=0;$i<count($departData);$i++)
							{ 
							
						?>
						<tr align="center">
						<?php 
							if($section!='billop') 
							{ 
						?>
							<td><input type="checkbox" name="idchk[]" id="idchk<?php echo $i;?>"  onclick="selectCheck(this);" value="<?php echo $departData[$i]['outer_vvi_id']; ?>" ></td>	
						<?php 
							} 
						?>
							<td><?php echo $i+1;?></td>
							<td>
								<input type="hidden" class="form-control" id="rotNo<?php echo $i;?>" value="<?php echo $departData[$i]['ib_vyg']; ?>">
								<?php echo $departData[$i]['ib_vyg'];?></td>
							<td><?php echo $departData[$i]['ata'];?></td>
							<td><?php echo $departData[$i]['atd'];?></td>												
							<td>
								<input type="hidden" class="form-control" id="vesselName<?php echo $i;?>" value="<?php echo $departData[$i]['vsl_name']; ?>">	
								<?php echo $departData[$i]['vsl_name'];?></td>
							<td><?php echo $departData[$i]['cntry_name'];?></td>
							<td><?php echo $departData[$i]['grt'];?></td>
							<td><?php echo $departData[$i]['nrt'];?></td>
							<!--td><?php echo $departData[$i]['loa_cm'];?></td-->
							
							<td><?php echo $departData[$i]['name'];?></td>
							<?php
							if($org_Type_id!=83)
							{
							?>	
							<td><?php echo $departData[$i]['forwarded_dt'];?></td>
							<?php 
							} 
							?>
						<?php 
							// if($login_id=='acc') 
							if($section=='billop') 
							{
								$ib_vyg = $departData[$i]['ib_vyg'];
								// $ib_vyg = "2022/1710";
								
								$sql_chkBill = "SELECT COUNT(*) AS cnt FROM ctmsmis.mis_vsl_billing_detail_test WHERE rotation='$ib_vyg'";
								$rslt_chkBill = mysqli_query($con_sparcsn4,$sql_chkBill);
								
								$chkBill = 0;
								while($row_chkBill = mysqli_fetch_object($rslt_chkBill))
								{
									$chkBill = $row_chkBill->cnt;
								}
							?>
							<td>
								<?php
								if($chkBill>0)
								{
								?>
									Bill Generated
								<?php
								}
								else
								{
								?>
									<a class="btn btn-primary"  href="<?php echo site_url('VesselBill/generateVesselsBillNotEntering/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" style="color:white" <?php if($chkBill>0){ ?>disabled<?php } ?>>
										Generate Bill
									</a>									
								<?php
								}
								?>																
							</td>	
						<?php 
							} 
							?>			
						</tr>
						
						<?php 
							} 
						?>
											
					</table>	
					
					<?php 
						// if($login_id!='acc') 
						if($section!='billop') 
						{ 
					?>

					<div class="row" id="summaryDiv" style="display:none;">
						<div class="col-sm-6 col-sm-offset-3 text-center">
							<table border="2" class="table table-responsive table-striped" id="summaryTable" style="border:2px solid black;">
								<thead>
									<tr style="border-bottom:2px solid black;">
										<th class="text-center" style="border-bottom:2px solid black;border-right:2px solid black;">Rotation</th>
										<th class="text-center" style="border-bottom:2px solid black;border-left:2px solid black;">Vessel Name</th>
									</tr>
								</thead>
								<tbody>
									<tr style="border-top:2px solid black;">
										<td class="text-center">&nbsp;</td>
										<td class="text-center">&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12 text-center">
							
							<input type="button" name="fwBtnOuter" id="forward" value="Forward" class="mb-xs mt-xs mr-xs btn btn-success" data-toggle="modal" data-target="#remarks" disabled/>
						</div>													
					</div>

					<div class="modal fade" id="remarks" tabindex="-1" role="dialog" aria-labelledby="remarksLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
							<div class="modal-body">
								<div class="form-group">
									<label for="exampleInputEmail1">Remarks</label>
									<input type="text" class="form-control" id="remarks" name="remarks" placeholder="Enter remarks">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success">Forward</button>
							</div>
							</div>
						</div>
					</div>
					
					<?php 
						}
					?>
					<?php if($org_Type_id=='81') { ?>
					<table width=100%>				
						<tr>
							<td align="left">
								<u>সংযুক্ত : &nbsp;&nbsp; বর্ণনামতে</u> 
						</tr>
						<tr><td> <br/></td></tr>
						<tr>
							<td align="right">
									হারবার মাস্টার &nbsp;&nbsp;&nbsp;&nbsp; <br/>
								<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u> 
							</td>
						</tr>
						
					</table>

					<table width=100%>
						<tr>
							<td align="left" style="font-family: ind_bn_1_001">
								অনুলিপিঃ<br/>
								১। প্রধান নিরীক্ষা কর্মকর্তা / চবক বর্ণিত জাহাজের একখানা তালিকা চার খানা প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত করা হল।<br/>
								২। উদ্বর্তন নিরীক্ষা কর্মকর্তা / চবক এর অবগতির জন্য।<br/>
								৩। বার্থিং শাখা (প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য)
							</td>
						</tr>

						<tr><td> <br/><br/></td></tr>

						<tr>
							<td align="right" style="font-family: ind_bn_1_001">
								স্বাক্ষরিত &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>
								হারবার মাস্টার &nbsp;&nbsp;&nbsp;&nbsp; <br/>
								<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u> 
							</td>
						</tr>
					</table>
					<?php } ?>
				</form>
			</section>
		</div>
	</div>
</div>
	<?php 
		mysqli_close($con_sparcsn4);
		mysqli_close($con_cchaportdb);
	} 
	
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

</section>
</div>