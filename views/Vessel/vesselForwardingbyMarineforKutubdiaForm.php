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
				if(document.getElementById('fileNo').value=="ডিসি/বিএস/সিএস/অংশ-" || document.getElementById('fileNo').value=="" || document.getElementById('fileNo').value==null)
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
		
		
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Vessel/vesselForwardingbyMarineforKutubdia'); ?>"  id="myform" name="myform">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
										<input type="date" name="fromDate" id="fromDate" style="width:180px" class="form-control" value="<?php echo date('Y-m-d');?>">

									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
										<input type="date" name="toDate" id="toDate" style="width:180px" class="form-control" value="<?php echo date('Y-m-d');?>">
									</div>												
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php echo $msg;?>
									</div>
								</div>
							</div>	
						</form>
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
	<?php if($flag==1) 
	{ 
	include("dbConection.php");
	include("mydbPConnection.php");
	include("dbOracleConnection.php");
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
		
			<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/vesselForwardingPerformforKutubdia") ?>" onsubmit="return validate()">
			<input type="hidden" name="masterFlag" id="masterFlag" value="<?php echo $masterFlag; ?>" />			
			<table width=100%>
				<!--tr><td>নং : <input type="text"  style="height:25px" id="fileNo" name="fileNo" value="ডিসি /বিএস/বিবিধ/" /></td></tr-->
				<!--tr><td align="right"> তারিখ : <input type="date"  style="height:25px"  id="filedt" name="filedt" value="">&nbsp;&nbsp;&nbsp;</td></tr-->

				<tr>
					<td align="left">
					বরাবরে  <br/>
					উপ-প্রধান অর্থ ও হিসাব রক্ষণ কর্মকর্তা <br/>
					চট্টগ্রাম বন্দর কর্তৃপক্ষ <br/>
					চট্টগ্রাম । <br/>
					বিষয়: <input type="text" style="width:750px; height:25px;" id="filesub" name="filesub" value="<?php echo $fromDate." ইং হতে ".$toDate; ?> ইং মাসে বন্দর কুতুবদিয়া হতে চলিয়া গিয়াছে এরুপ জাহাজের একখানা তালিকা প্রেরণ প্রসঙ্গে । " /> <br/>
					</td>
				</tr>
				<tr><td> <br/></td></tr>
			
				 <br/>
				<tr>
					<td align="left">
					<?php echo $fromDate." ইং হতে ".$toDate; ?> ইং মাসে বন্দর কুতুবদিয়া হইতে চলিয়া যাওয়া সংক্রান্ত জাহাজের বিবরণ সম্বলিত একখানা তালিকা অর্থ ও হিসাব বিভাগ পরবর্তী ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত ও প্রেরণ করা হল। 
					</td>
				</tr>
			</table>
			<?php } ?>		
			<section class="panel">
			<?php if($org_Type_id!='81') { ?>
			<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/vesselForwardingPerformforKutubdia") ?>">
			<?php } ?>
				<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<input type="hidden" id="rotCount" name="rotCount" value="<?php echo count($departData);?>">
						<input type="hidden" id="fromDate" name="fromDate" value="<?php echo $fromDate;?>">
						<input type="hidden" id="toDate" name="toDate" value="<?php echo $toDate;?>">
						<thead>
							<tr>
							<?php 
								 $section = $this->session->userdata('section');
								// if($login_id!='acc') 
								if($section!='billop') 
								{ 
							?>
								<th rowspan="2" align="center">Forward All <input type="checkbox" id="allcheck" onclick="selectAllRot(this);"></th>
							<?php 
								} 
							?>
								<th rowspan="2" class="text-center">SL</th>
								<th rowspan="2" class="text-center">Rotation</th>
								<th rowspan="2" class="text-center">Arrival Time</th>
								<th rowspan="2" class="text-center">Shift Time</th>
								<th rowspan="2" class="text-center">Depart Time</th>
								<th rowspan="2" class="text-center">Vessel Name</th>
								<th rowspan="2" class="text-center">Vessel Class</th>
								<th rowspan="2" class="text-center">Country</th>
								<th colspan="2" class="text-center">Tonage</th>								
								<!-- <th rowspan="2" class="text-center">Captain</th> -->
								<th rowspan="2" class="text-center">Local Agent</th>
								<?php
									if($org_Type_id!='83'){
								?>
									<th rowspan="2" class="text-center">Forwarded Date</th>
								<?php
									}
								?>
								<!-- <th rowspan="2" class="text-center">No. Of Paper</th>
								<th rowspan="2" class="text-center">Status</th>
								<th rowspan="2" class="text-center">Report</th> -->
							</tr>

							<tr>
								<th class="text-center">GT</th>
								<th class="text-center">NT</th>
							</tr>
						</thead>



					

						<?php 
							for($i=0;$i<count($departData);$i++){ 
							$vvd_gkey = $departData[$i]['vvd_gkey'];


							$basic_class="";
							$notes="";
							$basic_class = $departData[$i]['basic_class'];
							$notes = $departData[$i]['notes'];
							$vsl_class="";
							if($basic_class=='CELL'){
								$vsl_class="CONTAINER";	
							}
						
							else if($basic_class=='BBULK'){
								$vsl_class="BREAK BULK";
							}
							else if($basic_class=='PSNGR'){
								$vsl_class="PESSENGER";
							}
							else if($basic_class=='UNKNOWN'){
								if( strpos( $notes, 'LPG' ) !== false) {
									$vsl_class="LPG";
								}
								else{
									if( strpos( $notes, 'KUTUBDIA' ) !== false) {
										$vsl_class="KUTUBDIA";
									}
									else{
										$vsl_class=$notes;
									}
								}
								
							}
							else{
								$vsl_class=$basic_class;
							}



							$totalReport = 0;
							$chk_depart_query = "SELECT * FROM doc_vsl_depart WHERE vvd_gkey = '$vvd_gkey'";
							$chkDepart = $this->bm->dataSelectDb1($chk_depart_query);
							if(count($chkDepart)>0){
								$totalReport++;
							}

							$chk_arrival_query = "SELECT * FROM doc_vsl_arrival WHERE vvd_gkey = '$vvd_gkey'";
							$chkArrival = $this->bm->dataSelectDb1($chk_arrival_query);
							if(count($chkArrival)>0){
								$totalReport++;
							}

							$chk_shift_query = "SELECT * FROM doc_vsl_shift WHERE vvd_gkey = '$vvd_gkey'";
							$chkShift = $this->bm->dataSelectDb1($chk_shift_query);
							if(count($chkShift)>0){
								$totalReport++;
							}


						?>
						<tr align="center">
						<?php 
							// if($login_id!='acc') 
							if($section!='billop') 
							{ 
						?>
							<td><input type="checkbox" name="idchk[]" id="idchk<?php echo $i;?>"  onclick="selectCheck(this);" value="<?php echo $departData[$i]['vvd_gkey']; ?>" 
								<?php 
									// if($igm_id_depart==0 && $igm_id_arraival==0 && $igm_id_shift==0){
									// if(count($chkDepart)==0 && count($chkArrival)==0 && count($chkShift)==0)
									// { 
									// 	echo "disabled";
									// }
								?>>
							</td>	
						<?php } ?>
							<td><?php echo $i+1;?></td>
							<td>
								<input type="hidden" class="form-control" id="rotNo<?php echo $i;?>" value="<?php echo $departData[$i]['ib_vyg']; ?>">
								<?php echo $departData[$i]['ib_vyg'];?>
							</td>
							<td><?php echo $departData[$i]['ata'];?></td>
						<?php
							$shft_time="";
							$rot=$departData[$i]['ib_vyg'];	
							$sql_shifting_one="SELECT id,Vessel_Name,Name_of_Master,grt,nrt,Deck_cargo,loa_cm,Port_of_Destination,radio_call_sign,flag 
							FROM igm_masters WHERE Import_Rotation_No='$rot' order by id desc limit 1";
							$shifting_vsl=$this->bm->dataSelectDb1($sql_shifting_one);
							if(count($shifting_vsl)>0)
							{
								$shift_igm_id=$shifting_vsl[0]['id'];
								$sql_show_current_data="SELECT pilot_name,pilot_on_board,pilot_off_board,shift_frm,shift_to,mooring_frm_time,
								mooring_to_time,tug_name,assit_frm,assit_to,shift_dt,DATE(pilot_on_board) as sign_shift,aditional_tug+1 as aditional_tug 
								FROM doc_vsl_shift WHERE igm_id='$shift_igm_id'";
								$rslt_show_current_data=$this->bm->dataSelectDb1($sql_show_current_data);
								if(count($rslt_show_current_data)>0)
								{
									$shft_time=$rslt_show_current_data[0]['mooring_frm_time'];
								}
							}	

							
							
						?>
							
							<td><?php echo $shft_time;?></td>												
							<td><?php echo $departData[$i]['atd'];?></td>												
							<td>
								<input type="hidden" class="form-control" id="vesselName<?php echo $i;?>" value="<?php echo $departData[$i]['vsl_name']; ?>">
								<?php echo $departData[$i]['vsl_name'];?>
							</td>
							<!--td><?php echo $departData[$i]['vsl_class'];?></td-->
							<td><?php echo $vsl_class;?></td>
							<td><?php echo $departData[$i]['cntry_name'];?></td>
							<td><?php echo $departData[$i]['grt'];?></td>
							<td><?php echo $departData[$i]['nrt'];?></td>
							<!--td><?php echo $departData[$i]['loa_cm'];?></td-->
							<!-- <td><?php //echo $pilot_name; ?></td> -->
							<td><?php echo $departData[$i]['agent'];?></td>
							<?php
								if($org_Type_id!='83'){
							?>
							<td><?php if(isset($departData[$i]['forwarded_dt'])){echo $departData[$i]['forwarded_dt'];};?></td>
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
									$sql_chkInbound = "SELECT argo_carrier_visit.phase
									FROM vsl_vessel_visit_details
									INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
									INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey = argo_visit_details.gkey
									WHERE ib_vyg='$ib_vyg'";
									$rslt_chkInbound = oci_parse($con_sparcsn4_oracle,$sql_chkInbound);
									oci_execute($rslt_chkInbound);
									
									$chkInbound = "";
									while(($row_chkInbound = oci_fetch_object($rslt_chkInbound))!=false)
									{
										$chkInbound = $row_chkInbound->phase;
									}
									
									if($chkInbound == "20INBOUND")
									{
								?>
									<a class="btn btn-primary"  href="<?php echo site_url('VesselBill/generateVesselsBill/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" target="_blank" style="color:white" <?php if($chkBill>0){ ?>disabled<?php } ?>>
										<u>Generate Bill</u>
									</a>
									<!--a class="btn btn-primary"  href="<?php echo site_url('VesselBill/generateVesselsBill/'.str_replace("/","_","2022/1710"))?>" target="_blank" style="color:white" <?php if($chkBill>0){ ?>disabled<?php } ?> >
										<u>Generate Bill</u>
									</a-->
								<?php
									}
									else
									{
										echo "<b>Vessel Not Inbound</b>";
									}
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
					</div>
					<br>
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
								<!--button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Forward
								</button-->
								<!--p class="btn p-3 mb-2 bg-white text-dark" style="cursor:default">
									You have selected <label id="item" style="font-size:20px;">0</label> Item 
								</p-->
								<input type="submit" name="fwBtn" id="forward" value="Forward" class="mb-xs mt-xs mr-xs btn btn-success" disabled/>
							</div>													
						</div>
					<?php } ?>					
					<?php if($org_Type_id=='81') { ?>
					<table width=100%>				
						<tr>
							<td align="left">
								<!-- এতদসঙ্গে সংশ্লিষ্ট শিপিং এজেন্ট এর অনুকূলে প্রেরিত বিলের     কপি অনুলিপি অত্র দপ্তরে সংরক্ষণের জন্য প্রেরণের অনুরোধ করা হলো।  <br/> -->
								<u>সংযুক্ত : &nbsp;&nbsp; বর্ণনামতে</u> <!--label id="filela" name="filela">0</label--> &nbsp;পাতা (০১) খানা তালিকা।
						</tr>
						<tr><td> <br/></td></tr>
						<tr>
							<td align="right">
									স্বাক্ষরিত &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>
									হারবার মাস্টার &nbsp;&nbsp;&nbsp;&nbsp; <br/>
								<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u> 
							</td>
						</tr>
						
					</table>

					<table width=100%>
						<tr>
							<td align="left" style="font-family: ind_bn_1_001">
								অনুলিপিঃ<br/>
								১। প্রধান নিরীক্ষা কর্মকর্তা / চবক বর্ণিত জাহাজের একখানা তালিকা (০১) <b>খানা</b> প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত করা হল।<br/>
								২। উদ্বর্তন নিরীক্ষা কর্মকর্তা / চবক এর অবগতির জন্য।<br/>
								৩। বার্থিং শাখা (প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য)
							</td>
						</tr>

						<tr><td> <br/><br/></td></tr>

						<tr>
							<td align="right" style="font-family: ind_bn_1_001">
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
	} ?>

</section>
</div>