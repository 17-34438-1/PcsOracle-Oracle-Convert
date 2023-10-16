<script>

		function validate()
		{
			var billOp = document.getElementById("billOp").value;
			//alert(billOp);
			
			if( billOp == "")
			{
				alert( "Please, Select Bill Operator." );
				document.getElementById("billOp").focus() ;
				return false;
			}
			else { 
				return true;
			}
			return false;
			
		}
		
		
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

			document.getElementById("item").innerHTML = numberOfChecked;

			if(numberOfChecked>0)
			{
				document.getElementById('forward').disabled = false;
			}else{
				document.getElementById('forward').disabled = true;
			}

			document.getElementById('filela').innerHTML = numberOfChecked;
			document.getElementById("filela").style.fontWeight = 'bold';
			document.getElementById('filela2').innerHTML = numberOfChecked;
			document.getElementById("filela2").style.fontWeight = 'bold';
		}

		function selectCheck(state)
		{
			var numberOfChecked = 0;
			
			if(document.getElementById('allcheck').checked==true)
			{
				var numberOfChecked = $('input:checkbox:checked').length -1;
			}
			else
			{
				var numberOfChecked = $('input:checkbox:checked').length;
			}
			//alert(numberOfChecked);
			document.getElementById("item").innerHTML = numberOfChecked;

			if(numberOfChecked>0){
				document.getElementById('forward').disabled = false;
			}else{
				document.getElementById('forward').disabled = true;
			}
			
			document.getElementById('filela').innerHTML = numberOfChecked;
			document.getElementById("filela").style.fontWeight = 'bold';
			document.getElementById('filela2').innerHTML = numberOfChecked;
			document.getElementById("filela2").style.fontWeight = 'bold';
		}
		
		function confirMsg(){
			if(confirm("Do you want to generate bill ?")){
				return true;
			} else {
				return false;
			}
		}
		
		/* function validate()
		{
			if(document.getElementById('masterFlag').value=="master")
			{
				if(document.getElementById('fileNo').value=="")
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
		} */

		window.onload = function()
		{

			var totAllMeasurement = 0;
			var subTotMeasurement = 0;
			var totalRot = document.getElementById('rotCount').value;
			
			for(var p=0;p<totalRot;p++)
			{
				document.getElementById("idchk"+p).checked = true;
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

			document.getElementById("item").innerHTML = numberOfChecked;

			if(numberOfChecked>0)
			{
				document.getElementById('forward').disabled = false;
			}else{
				document.getElementById('forward').disabled = true;
			}

			document.getElementById('filela').innerHTML = numberOfChecked;
			document.getElementById("filela").style.fontWeight = 'bold';
			document.getElementById('filela2').innerHTML = numberOfChecked;
			document.getElementById("filela2").style.fontWeight = 'bold';
		};
		
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

		
	<?php if($flag==1) 
	{ 
	include("dbConection.php");
	include("mydbPConnection.php");
	include("dbOracleConnection.php");	
	//if($org_Type_id=='81') { ?>

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
								<br/>হিসাব বিভাগ 
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-2">
			</div>
		</div>

			<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/vesselForwardingForAccBillAction") ?>" <?php if($section=='acc') { ?> onsubmit="return validate()" <?php }?> >
			<input type="hidden" name="letter_id" id="letter_id" value="<?php  echo $letter_id; ?>" />	
			<input type="hidden" name="fileNo" id="fileNo" value="<?php  echo $fileNo; ?>" />	
			<input type="hidden" name="filesub" id="filesub" value="<?php  echo $filesub; ?>" />	
			<input type="hidden" name="filedt" id="filedt" value="<?php  echo $filedt; ?>" />	
	
	<?php if($section=='acc') { ?>
			<table width=100%>
				<tr><td><?php echo $fileNo; ?></td></tr>
				<tr><td align="right"> তারিখ : <?php echo  $filedt; ?></td></tr>

				<tr>
					<td align="left">
					বরাবরে  <br/>
					উপ-প্রধান অর্থ ও হিসাব রক্ষণ কর্মকর্তা <br/>
					চট্টগ্রাম বন্দর কর্তৃপক্ষ <br/>
					চট্টগ্রাম । <br/>
					বিষয়: <?php echo $filesub; ?> <br/>
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
			<?php //} ?>		
			<section class="panel">
			<?php //if($org_Type_id!='81') { ?>
			<!--form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/vesselForwardingPerform") ?>"-->
			<?php //} ?>
			
			
			<div class="input-group mb-md">
				<span class="input-group-addon span_width">Bill Operator <span class="required">*</span></span>
				<select name="billOp" id="billOp" class="form-control" style="width:300px"> 
					<option value="">--SELECT--</option>
					<?php for($i=0; $i<count($billOpListRslt); $i++) { ?>
						<option value="<?php echo $billOpListRslt[$i]['login_id']; ?>"><?php echo $billOpListRslt[$i]['u_name']; ?></option>
					<?php } ?>
				</select>
			</div>
						<p align="center"><?php echo $msg; ?></p>

	<?php } ?>
				<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<input type="hidden" id="rotCount" name="rotCount" value="<?php echo count($departData);?>">
						<input type="hidden" id="fromDate" name="fromDate" value="<?php// echo $fromDate;?>">
						<input type="hidden" id="toDate" name="toDate" value="<?php //echo $toDate;?>"-->
						<thead>
							<tr>
							<?php if($section=='acc') { ?>
								<th rowspan="2" align="center">Forward All <input type="checkbox" id="allcheck" onclick="selectAllRot(this);" checked ></th>
								<?php } ?>
								<th rowspan="2" class="text-center">SL</th>
								<th rowspan="2" class="text-center"><nobr>Rotation</nobr></th>
								<th rowspan="2" class="text-center"><nobr>Arrival Time</nobr></th>
								<th rowspan="2" class="text-center"><nobr>Shift Time</nobr></th>
								<th rowspan="2" class="text-center"><nobr>Depart Time</nobr></th>
								<th rowspan="2" class="text-center"><nobr>OA Date</nobr></th>
								<th rowspan="2" class="text-center">Vessel Name</th>
								<th rowspan="2" class="text-center">Vessel Class</th>
								<th rowspan="2" class="text-center">Nationality</th>
								<th colspan="2" class="text-center">Tonage</th>								
								<!--th rowspan="2" class="text-center">Captain</th-->
								<th rowspan="2" class="text-center">Local Agent</th>
								<th rowspan="2" class="text-center">Forwarded Date</th>
								<?php

									$actionArray = array("Vatiary","Kutubdia");

									if(!in_array($action, $actionArray)){
								?>
									<th rowspan="2" class="text-center">No. of paper</th>
									<th rowspan="2" class="text-center">View</th>
								<?php
									}
								?>

						<?php if($section=='billop'){ ?>		
								<th rowspan="2" class="text-center">Bill Gererate</th>
						<?php } ?>
						<?php if($section=='billop'){ ?>	
                                 <th rowspan="2" class="text-center">Summary</th>
						<?php } ?>	 
							</tr>

							<tr>
								<th class="text-center">GT</th>
								<th class="text-center">NT</th>
							</tr>
						</thead>



					

						<?php 
						
							for($i=0;$i<count($departData);$i++)
							{																
								// $ata = $departData[$i]['ata'];
								$oa_date_dollar = $departData[$i]['oa_date_dollar'];
								/*
								$sql_dollarRate = "SELECT rate
								FROM cchaportdb.bil_currency_exchange_rates
								WHERE effective_date=DATE('$oa_date_dollar')
								ORDER BY gkey DESC
								LIMIT 1";
							
								// echo $sql_dollarRate;
								$rslt_dollarRate = mysqli_query($con_cchaportdb,$sql_dollarRate);
								$isExist = mysqli_num_rows($rslt_dollarRate);
								*/
								
								// intakhab - 2022-11-09 - dollar rate from N4
								$sql_dollarRate = "SELECT rate
								FROM billing.bil_currency_exchange_rates
								WHERE DATE(effective_date)=DATE('$oa_date_dollar')";
								// echo $sql_dollarRate;
								$rslt_dollarRate = mysqli_query($con_sparcsn4,$sql_dollarRate);
								$isExist = mysqli_num_rows($rslt_dollarRate);
								
								$vvd_gkey = $departData[$i]['vvd_gkey'];
								$depart_query = "SELECT * FROM doc_vsl_depart WHERE vvd_gkey = '$vvd_gkey'";
								$depart_data = $this->bm->dataSelectDB1($depart_query);
								// $pilot_id ="";
								$pilot_name = "";
							
							
						?>
						<tr align="center">
						<?php if($section=='acc') { ?>
							<td><input type="checkbox" name="idchk[]" id="idchk<?php echo $i;?>"  onclick="selectCheck(this);" value="<?php echo $departData[$i]['vvd_gkey']; ?>"  checked ></td>	
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
								$sql_show_current_data="SELECT pilot_name,pilot_on_board,pilot_off_board,shift_frm,shift_to,
								-- mooring_frm_time,
								-- mooring_to_time,

								CONCAT(DATE_FORMAT(mooring_frm_time,'%d/%m/%Y'),' ',TIME(mooring_frm_time)) AS mooring_frm_time,
								CONCAT(DATE_FORMAT(mooring_to_time,'%d/%m/%Y'),' ',TIME(mooring_to_time)) AS mooring_to_time,

								tug_name,assit_frm,assit_to,shift_dt,DATE(pilot_on_board) as sign_shift,aditional_tug+1 as aditional_tug 
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
							<td><?php echo $departData[$i]['oa_date'];?></td>												
																			
							<td><?php echo $departData[$i]['vsl_name'];?></td>
							<td><?php echo $departData[$i]['vsl_class'];?></td>
							<td><?php echo $departData[$i]['cntry_name'];?></td>
							<td><?php echo $departData[$i]['grt'];?></td>
							<td><?php echo $departData[$i]['nrt'];?></td>
							<!--td><?php echo $departData[$i]['loa_cm'];?></td-->
							<!--td><?php echo $pilot_name; ?></td-->
							<td><?php echo $departData[$i]['agent'];?></td>
							<td><?php if(isset($departData[$i]['forwarded_dt'])){ echo $departData[$i]['forwarded_dt']; };?></td>
							<?php
								$vvd_gkey = $departData[$i]['vvd_gkey'];

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

							<?php
								if(!in_array($action, $actionArray)){
							?>
							<td><?php echo $totalReport; ?></td>
							<td>
								<a href="<?php echo site_url('Report/departureReportOfVessel/R/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" target="_blank" method="POST">
									<span class="btn btn-warning" >Pilot Paper</span>
								</a>
							</td>
							<?php
								}
							?>	
					<?php 
							// if($section=='billop')
							// { 
						?>		
							<!--td align="center" style="height:5%;">
								<form action="<?php echo site_url("Vessel/generate");?>" method="post" target="_blank">									
									<input type="submit" value="Gererate" class="btn btn-sm btn-primary" style="height:2%;">
								</form>
							</td-->
					<?php 
						// } 
						?>		
						<?php 
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
										$chkInbound = $row_chkInbound->PHASE;
									}
									
									// if($chkInbound == "20INBOUND")		// intakhab - 30 August 2022 - for test
									if($chkInbound == "20INBOUND" or $chkInbound != "20INBOUND")
									{
								?>
									
									<?php  // if($isExist > 0){ ?>
									<?php 
									//if($departData[$i]['oa_date_dollar']!="" and $departData[$i]['oa_date_dollar']!=null and $isExist>0)
										$rotationNo=$departData[$i]['ib_vyg'];
										$sqlRotationForBillGenerate = "SELECT *
										FROM ctmsmis.qgc_container_handling
										WHERE ctmsmis.qgc_container_handling.rotation='$rotationNo'";
										// echo $sqlRotationForBillGenerate;
										// echo $sql_dollarRate;
								        $rsltOfbillGenQuery = mysqli_query($con_sparcsn4,$sqlRotationForBillGenerate);
								        $isExistBillGenerate = mysqli_num_rows($rsltOfbillGenQuery);

									// if($departData[$i]['oa_date_dollar']!="" and $departData[$i]['oa_date_dollar']!=null and $isExist>0 and $isExistBillGenerate > 0 )
										
										if($departData[$i]['oa_date_dollar']!="" and $departData[$i]['oa_date_dollar']!=null and $isExist>0)
										{
											if($departData[$i]['vsl_category'] == "CONTAINER")
											{
									?>
									
												<a class="btn btn-primary" href="<?php echo site_url('VesselBill/generateVesselsBillN4/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" style="color:white" <?php if($chkBill>0){ ?>disabled<?php } ?> onclick="return confirMsg();">
													<u>Generate Bill</u>
												</a>
									
									<!--form method="post" action="<?php echo site_url('VesselBill/generateVesselsBillN4'); ?>" onsubmit="return confirMsg()">
										<input type="hidden" name="rot" id="rot" value="<?php echo $departData[$i]['ib_vyg']; ?>" />
										<input type="hidden" name="billAta" id="billAta" value="<?php echo $ata; ?>" />
										<input type="submit" name="btnBillGenerate" id="btnBillGenerate" value="Generate Bill" class="btn btn-primary" />
									</form-->
									
									<?php 
											}
											else if($departData[$i]['vsl_category'] == "LPG")		// Bhatiari
											{
									?>
												<a class="btn btn-primary" href="<?php echo site_url('VesselBill/Generate_Bhatiari_Bill/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" style="color:white" <?php if($chkBill>0){ ?>disabled<?php } ?> onclick="return confirMsg();">
													<u>Generate Bill</u>
												</a>
									<?php
											}
											else if($departData[$i]['vsl_category'] == "LNG")		// Kutubdia	
											{
									?>
												<a class="btn btn-primary" href="<?php echo site_url('VesselBill/Generate_Kutubdia_Bill/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" style="color:white" <?php if($chkBill>0){ ?>disabled<?php } ?> onclick="return confirMsg();">
													<u>Generate Bill</u>
												</a>
									<?php
											}
										}									
										else
										{
									?>
											<a <?php if($departData[$i]['oa_date_dollar']=="" or $departData[$i]['oa_date_dollar']==null){ ?>disabled<?php } ?> class="btn btn-danger"  href="<?php echo site_url('Vessel/usdtoBdtExchangeRateform/')?>" style="color:white">
												<u>Rate Setting</u>
											</a>
									
								<?php
										}
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
							
							
							
							<?php
								if($section=='billop'){
									$rotationNoForSummary=$departData[$i]['ib_vyg'];
									$sqlRotationForSummary = "SELECT ctmsmis.qgc_container_handling.id,ctmsmis.qgc_container_handling.rotation,
									CONCAT(DATE_FORMAT(ctmsmis.qgc_container_handling.entry_at,'%d/%m/%Y'),' ',TIME(entry_at)) AS entry_at
									FROM ctmsmis.qgc_container_handling
									WHERE ctmsmis.qgc_container_handling.rotation='$rotationNoForSummary'";
									// echo $sql_dollarRate;
									$rsltOfSummaryQuery = mysqli_query($con_sparcsn4,$sqlRotationForSummary);
									$isExistSummary = mysqli_num_rows($rsltOfSummaryQuery);
									$forwadingDate = "";
									while($rowOfSummary = mysqli_fetch_object($rsltOfSummaryQuery))
									{
										$forwadingDate = $rowOfSummary->entry_at;
									} 
								?>
									<td>
										<a class="btn btn-primary" href="<?php echo site_url('Vessel/showContainerForwardedSummary/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>"  target="_blank"  <?php if($isExistSummary<=0){ ?>disabled<?php } ?> >
												<u>Summary</u>
										</a>
										<?php echo "<nobr>"."Forward Date :"."<br>".$forwadingDate."</nobr>"; ?>
											
									</td>

							<?php }?>	
							
												
						</tr>
						
						<?php 
							} 
						?>
											
					</table>	
					</div>
					<?php 
						if($section=='acc') 
						{ 
					?>
					<div class="row">
						<div class="col-sm-12 text-center">
							<!--button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Forward
							</button-->
							<p class="btn p-3 mb-2 bg-white text-dark" style="cursor:default">You have selected <label id="item" style="font-size:20px;">0</label> Item </p>
							<input type="submit" name="fwBtn" id="forward" value="Forward" class="mb-xs mt-xs mr-xs btn btn-success" disabled/>
						</div>													
					</div>
					<?php 
						}
					?>					
					 <?php if($section=='acc') { ?> 
					<table width=100%>				
						<tr>
							<td align="left">
								এতদসঙ্গে সংশ্লিষ্ট শিপিং এজেন্ট এর অনুকূলে প্রেরিত বিলের কপি অনুলিপি অত্র দপ্তরে সংরক্ষণের জন্য প্রেরণের অনুরোধ করা হলো।  <br/>
								<u>সংযুক্ত : &nbsp;&nbsp;<label id="filela" name="filela"></label> &nbsp;সেট পাইলটেজ পেপার।</u>
						</tr>
						<tr><td> <br/></td></tr>
						<!--tr>
							<td align="right">
									হারবার মাস্টার &nbsp;&nbsp;&nbsp;&nbsp; <br/>
								<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u> 
							</td>
						</tr-->
						
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