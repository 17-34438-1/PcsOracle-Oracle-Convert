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
					document.getElementById("idchk"+p).checked = true;

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
			
			
				if(document.getElementById('allcheck').checked==true)
				{
					var numberOfChecked = $('input:checkbox:checked').length -1;
				}
				else
				{
					var numberOfChecked = $('input:checkbox:checked').length;
				}
				//alert(numberOfChecked);
				document.getElementById('filela').innerHTML = numberOfChecked;
				document.getElementById("filela").style.fontWeight = 'bold';
				document.getElementById('filela2').innerHTML = numberOfChecked;
				document.getElementById("filela2").style.fontWeight = 'bold';
		}

		function selectCheck(state)
		{
				if(document.getElementById('allcheck').checked==true)
				{
					var numberOfChecked = $('input:checkbox:checked').length -1;
				}
				else
				{
					var numberOfChecked = $('input:checkbox:checked').length;
				}
				//alert(numberOfChecked);
				document.getElementById('filela').innerHTML = numberOfChecked;
				document.getElementById("filela").style.fontWeight = 'bold';
				document.getElementById('filela2').innerHTML = numberOfChecked;
				document.getElementById("filela2").style.fontWeight = 'bold';
		}
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<!--div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Vessel/outerAnchorageForwarding'); ?>"  id="myform" name="myform">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
										<input type="date" name="fromDate" id="fromDate" class="form-control" value="<?php echo date('Y-m-d');?>">

									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
										<input type="date" name="toDate" id="toDate" class="form-control" value="<?php echo date('Y-m-d');?>">
									</div>												
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">										
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
		</div-->	
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
						<td align="center"><font size=4>চট্টগ্রাম বন্দর কর্তৃপক্ষ</font>
								<br/>নৌ বিভাগ 
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		
			<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/vesselForwardingPerform") ?>">							
			<table width=100%>
				<tr><td>নং : <input type="text"  style="height:25px" id="fileNo" name="fileNo" value="ডিসি /বিএস/বিবিধ/" /></td></tr>
				<tr><td align="right"> তারিখ : <input type="date"  style="height:25px"  id="filedt" name="filedt" value="">&nbsp;&nbsp;&nbsp;</td></tr>

				<tr>
					<td align="left">
					বরাবরে  <br/>
					উপ-প্রধান অর্থ ও হিসাব রক্ষণ কর্মকর্তা <br/>
					চট্টগ্রাম বন্দর কর্তৃপক্ষ <br/>
					চট্টগ্রাম । <br/>
					বিষয়: <input type="text" style="width:500px; height:25px;" id="filesub" name="filesub" value="বাণিজ্যিক জাহাজের পাইলট পেপার প্রেরণ প্রসঙ্গে । " /> <br/>
					</td>
				</tr>
				<tr><td> <br/></td></tr>
			
				 <br/>
				<tr>
					<td align="left">
						উপরোক্ত বিষয়ে জানানো যাচ্ছে যে , অত্র বিভাগের বার্থিং শাখায় জমাকৃত বাণিজ্যিক জাহাজের  &nbsp;<label id="filela2" name="filela2"></label> &nbsp; টি পাইলটিং এর আগমন নির্গমন  পেপার পরবর্তী প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত করে প্রেরণ করা হল। 
					</td>
				</tr>
			</table>
			<?php } ?>		
			<section class="panel">
			<?php if($org_Type_id!='81') { ?>
			<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/vesselForwardingPerform") ?>">
			<?php } ?>
				
				<div class="row">
					<div class="col-sm-12 text-center">
						<?php echo $msg;?>
					</div>
				</div>
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<input type="hidden" id="rotCount" name="rotCount" value="<?php echo count($departData);?>">
						<!--input type="hidden" id="fromDate" name="fromDate" value="<?php echo $fromDate;?>">
						<input type="hidden" id="toDate" name="toDate" value="<?php echo $toDate;?>"-->
						<thead>
							<tr>
							<?php if($login_id!='acc') { ?>
								<th rowspan="2" align="center"><nobr>Forward All <input type="checkbox" id="allcheck" onclick="selectAllRot(this);"></nobr></th>
								<?php } ?>
								<th rowspan="2" class="text-center">SL</th>
								<th rowspan="2" class="text-center"><nobr>Rotation</nobr></th>
								<th rowspan="2" class="text-center"><nobr>Arrival Time</nobr></th>
								<th rowspan="2" class="text-center"><nobr>Shift Time</nobr></th>
								<!--th rowspan="2">Shifting Date</th-->
								<th rowspan="2" class="text-center"><nobr>Depart Time</nobr></th>
								<th rowspan="2" class="text-center">Ship</th>
								<th rowspan="2" class="text-center">Nationality</th>
								<th colspan="2" class="text-center">Tonage</th>
								<!--th rowspan="2">Length</th-->
								<th rowspan="2" class="text-center">Captain</th>
								<th rowspan="2" class="text-center">Local Agent</th>
								<!--th rowspan="2" class="text-center">Report</th-->

								<?php if($login_id=='acc') { ?>
								<th rowspan="2" class="text-center"><nobr>Bill</th>
								<?php } ?>
							</tr>

							<tr>
								<th class="text-center">GT</th>
								<th class="text-center">NT</th>
							</tr>
						</thead>



					

						<?php 
							for($i=0;$i<count($departData);$i++){ 
							$vvd_gkey = $departData[$i]['vvd_gkey'];
							$depart_query = "SELECT * FROM doc_vsl_depart WHERE vvd_gkey = '$vvd_gkey'";
							$depart_data = $this->bm->dataSelectDB1($depart_query);
							$pilot_id ="";
							$pilot_name = "";
							if(count($depart_data)>0){
								$pilot_id = $depart_data[0]['pilot_name'];
								$pilotNameQuery = "SELECT u_name FROM users WHERE login_id = '$pilot_id'";
								$pilotName_data = $this->bm->dataSelectDB1($pilotNameQuery);
								if(count($pilotName_data)>0){
									$pilot_name = $pilotName_data[0]['u_name'];
								}
							}
							
						?>
						<tr align="center">
						<?php if($login_id!='acc') { ?>
							<td><input type="checkbox" name="idchk[]" id="idchk<?php echo $i;?>"  onclick="selectCheck(this);" value="<?php echo $departData[$i]['vvd_gkey']; ?>" ></td>	
						<?php } ?>
							<td><?php echo $i+1;?></td>
							<td><?php echo $departData[$i]['ib_vyg'];?></td>
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
							<td><?php echo $departData[$i]['vsl_name'];?></td>
							<td><?php echo $departData[$i]['cntry_name'];?></td>
							<td><?php echo $departData[$i]['grt'];?></td>
							<td><?php echo $departData[$i]['nrt'];?></td>
							<!--td><?php echo $departData[$i]['loa_cm'];?></td-->
							<td><?php echo $pilot_name; ?></td>
							<td><?php echo $departData[$i]['agent'];?></td>
							<!--td style="background-color:#6490ec">
								<a href="<?php echo site_url('Report/departureReportOfVessel/R/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" target="_blank" method="POST" style="color:white">
									<u>View</u>
								</a>
							</td-->	
						<?php 
							if($login_id=='acc') 
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
										$chkInbound = $row_chkInbound->PHASE;
									}
									
									$sql_chkBerth = "SELECT COUNT(*) AS rtnValue
									FROM vsl_vessel_visit_details
									INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey = vsl_vessel_visit_details.vvd_gkey
									WHERE vsl_vessel_visit_details.ib_vyg='$ib_vyg'";	
									$rslt_chkBerth = oci_parse($con_sparcsn4_oracle,$sql_chkBerth);
									oci_execute($rslt_chkBerth);
									
									$chkBerth = 0;
									
									while(($row_chkBerth = oci_fetch_object($rslt_chkBerth))!=false)
									{
										$chkBerth = $row_chkBerth->RTNVALUE;
									}
																		
									if($chkInbound == "20INBOUND" and $chkBerth == 0)
									{
								?>
									<a class="btn btn-primary"  href="<?php echo site_url('VesselBill/generateVesselsBill/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" style="color:white" <?php if($chkBill>0){ ?>disabled<?php } ?>>
										Generate Bill
									</a>									
								<?php
									}
									else
									{
										echo "<b>Not validated for bill</b>";
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
					
					<?php 
						if($login_id!='acc') 
						{ 
					?>
					<div class="row">
						<div class="col-sm-12 text-center">
							<!--button type="submit" name="forwardOuterAnchorage" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Forward
							</button-->
							<input type="submit" name="fwBtnOuter" id="submit" value="Forward" class="mb-xs mt-xs mr-xs btn btn-success"/>
						</div>													
					</div>
					<?php 
						}
					?>
					<?php if($org_Type_id=='81') { ?>
					<table width=100%>				
						<tr>
							<td align="left">
								এতদসঙ্গে সংশ্লিষ্ট শিপিং এজেন্ট এর অনুকূলে প্রেরিত বিলের     কপি অনুলিপি অত্র দপ্তরে সংরক্ষণের জন্য প্রেরণের অনুরোধ করা হলো।  <br/>
								<u>সংযুক্ত : &nbsp;&nbsp;<label id="filela" name="filela"></label> &nbsp;সেট পাইলটেজ পেপার।</u>
						</tr>
						<tr><td> <br/></td></tr>
						<tr>
							<td align="right">
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