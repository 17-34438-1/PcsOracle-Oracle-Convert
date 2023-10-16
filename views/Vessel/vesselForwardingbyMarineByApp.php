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
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Vessel/vesselForwardingbyAPP'); ?>"  id="myform" name="myform">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
										<input type="date" name="fromDate" id="fromDate" style="width:250px" class="form-control" value="<?php echo date('Y-m-d');?>">

									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
										<input type="date" name="toDate" id="toDate" style="width:250px" class="form-control" value="<?php echo date('Y-m-d');?>">
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
		
			<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/vesselAppForwardingPerform") ?>">							
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
			<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/vesselAppForwardingPerform") ?>">
			<?php } ?>
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<thead>
							<tr>
								<?php if($login_id!='acc') { ?>
									<th align="center"><nobr>Forward All <input type="checkbox" id="allcheck" onclick="selectAllRot(this);"></nobr></th>
								<?php } ?>
									<th class="text-center">SL</th>
									<th class="text-center">Ship</th>
									<th class="text-center">Ship</th>
									<th class="text-center">Reg No</th>
									<th class="text-center"><nobr>Arrival Time</nobr></th>
									<th class="text-center"><nobr>Shift Time</nobr></th>
									<th class="text-center"><nobr>Depart Time</nobr></th>
									<th class="text-center">Master</th>
									<th class="text-center">Report</th>
								<?php if($login_id=='acc') { ?>
									<th class="text-center"><nobr>Bill</th>
								<?php } ?>
							</tr>
						</thead>

						<tbody>
						<?php 
							for($i=0;$i<count($departData);$i++){ 
						/* 	$vvd_gkey = $departData[$i]['vvd_gkey'];
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
							}  */
							
						?>
						<tr align="center">
						<?php if($login_id!='acc') { ?>
							<td><input type="checkbox" name="idchk[]" id="idchk<?php echo $i;?>"  onclick="selectCheck(this);" value="<?php echo $departData[$i]['vvd_gkey']; ?>" ></td>	
						<?php } ?>
							<td><?php echo $i+1;?></td>
							<td><?php echo $departData[$i]['vsl_name'];?></td>
							<td><?php echo $departData[$i]['vvd_gkey'];?></td>
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
							<td><?php echo $departData[$i]['pilot_name'];?></td>
							<td>
								<?php
									$ddl_imp_rot_no = $departData[$i]['ib_vyg'];
									$chk_depart_query = "SELECT igm_id AS igm_id  FROM igm_masters
									INNER JOIN doc_vsl_depart ON  igm_masters.id=doc_vsl_depart.igm_id
									WHERE Import_Rotation_No = '$ddl_imp_rot_no'";
					
									$chkDepart = $this->bm->dataSelectDb1($chk_depart_query);
									$igm_id_depart = 0;
									if(count($chkDepart)>0){
										$igm_id_depart=$chkDepart[0]['igm_id'];
									}
									
									
									$chk_arrival_query = "SELECT igm_id AS igm_id  FROM igm_masters
									INNER JOIN doc_vsl_arrival ON  igm_masters.id=doc_vsl_arrival.igm_id
									WHERE Import_Rotation_No = '$ddl_imp_rot_no'";
									//return;
					
									$chkArrival = $this->bm->dataSelectDb1($chk_arrival_query);
									
									$igm_id_arraival = 0;
									if(count($chkArrival)>0){
										$igm_id_arraival=$chkArrival[0]['igm_id'];
									}
									
									$chk_shift_query = "SELECT igm_id AS igm_id  FROM igm_masters
									INNER JOIN doc_vsl_shift ON  igm_masters.id=doc_vsl_shift.igm_id
									WHERE Import_Rotation_No = '$ddl_imp_rot_no'";
					
									$chkShift = $this->bm->dataSelectDb1($chk_shift_query);
									$igm_id_shift = 0;
									if(count($chkShift)>0){
										$igm_id_shift=$chkShift[0]['igm_id'];
									}

									if($igm_id_depart==0 && $igm_id_arraival==0 && $igm_id_shift==0){
								?>
									<span class="btn btn-primary" disabled>View</span>
								<?php
									}else{
								?>
									<a href="<?php echo site_url('Report/departureReportOfVessel/R/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" target="_blank" method="POST">
										<span class="btn btn-primary" <?php if($igm_id_depart==0 && $igm_id_arraival==0 && $igm_id_shift==0){ echo "disabled";}?>>View</span>
									</a>
								<?php
									}
								?>
							</td>	
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
									$sql_chkInbound = "SELECT sparcsn4.argo_carrier_visit.phase
									FROM sparcsn4.vsl_vessel_visit_details
									INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
									INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.cvcvd_gkey = sparcsn4.argo_visit_details.gkey
									WHERE ib_vyg='$ib_vyg'";
									$rslt_chkInbound = mysqli_query($con_sparcsn4,$sql_chkInbound);
									
									$chkInbound = "";
									while($row_chkInbound = mysqli_fetch_object($rslt_chkInbound))
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
						</tbody>
					</table>
				</div>
					<?php 
						if($login_id!='acc') 
						{ 
					?>
					<div class="row">
						<div class="col-sm-12 text-center">
							<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Forward
							</button>
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