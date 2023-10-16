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

		window.onload = function()
	{
		var totAllMeasurement = 0;
		var subTotMeasurement = 0;
		var totalRot = document.getElementById('rotCount').value;
		
		for(var p=0;p<totalRot;p++)
		{
			if(document.getElementById("idchk"+p).disabled == false)
			{
				document.getElementById("idchk"+p).checked = true;
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

	function confirMsg()
	{
		if(confirm("Do you want to generate bill ?")){
			return true;
		} else {
			return false;
		}
	}
		
	
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
	<?php if($flag==1) 
	{ 
	include("dbConection.php");
	include("mydbPConnection.php");
	include(APPPATH.'views/dbOracleConnection.php');
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

			<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/vesselForwardingForAccBillAction_NotEntering") ?>" <?php if($section=='acc') { ?> onsubmit="return validate()" <?php }?> >
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
								<th rowspan="2" align="center"><nobr>Forward All <input type="checkbox" id="allcheck" onclick="selectAllRot(this);" checked ></nobr></th>
								<?php } ?>
								<th rowspan="2" class="text-center">SL</th>
								<th rowspan="2" class="text-center"><nobr>Rotation</nobr></th>
								<th rowspan="2" class="text-center"><nobr>Arrival Time</nobr></th>
								<th rowspan="2" class="text-center"><nobr>Shift Time</nobr></th>
								<th rowspan="2" class="text-center"><nobr>Depart Time</nobr></th>
								<th rowspan="2" class="text-center">Ship</th>
								<th rowspan="2" class="text-center">Nationality</th>
								<th colspan="2" class="text-center">Tonage</th>								
								<th rowspan="2" class="text-center">Captain</th>
								<th rowspan="2" class="text-center">Local Agent</th>
								<th rowspan="2" class="text-center">Forwarded Date</th>
								<!--th rowspan="2" class="text-center">View</th-->
							<?php if($section=='billop'){ ?>
								<th rowspan="2" class="text-center">Bill Gererate</th>
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
								$sql_offPortArr = "SELECT off_port_arr
								FROM vsl_vessel_visit_details
								WHERE ib_vyg='".$departData[$i]['ib_vyg']."'";


								$rslt_offPortArr=oci_parse($con_sparcsn4_oracle,$sql_offPortArr);

								oci_execute($sql1);
								$offPortArr = "";
								while(($row_offPortArr=oci_fetch_object($sql1)) !=false)
								{
									$offPortArr = $row_offPortArr->OFF_PORT_ARR;
								}

								// $rslt_offPortArr = mysqli_query($con_sparcsn4,$sql_offPortArr);
								
								// $offPortArr = "";
								// while($row_offPortArr = mysqli_fetch_object($rslt_offPortArr))
								// {
								// 	$offPortArr = $row_offPortArr->off_port_arr;
								// }

								
								$ata = $departData[$i]['ata'];
								
								$sql_dollarRate = "SELECT rate
								FROM cchaportdb.bil_currency_exchange_rates
								WHERE effective_date=DATE('$offPortArr')
								ORDER BY gkey DESC
								LIMIT 1";
							
								// echo $sql_dollarRate;
								$rslt_dollarRate = mysqli_query($con_cchaportdb,$sql_dollarRate);
								$isExist = mysqli_num_rows($rslt_dollarRate);
								
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
						<?php if($section=='acc') { ?>
							<td><input type="checkbox" name="idchk[]" id="idchk<?php echo $i;?>"  onclick="selectCheck(this);" value="<?php echo $departData[$i]['vs_id']; ?>"  checked ></td>	
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
							<td><?php echo $departData[$i]['name'];?></td>
							<td><?php if(isset($departData[$i]['forwarded_dt'])){ echo $departData[$i]['forwarded_dt']; };?></td>
							<!--td style="background-color:#6490ec">
								<a href="<?php echo site_url('Report/departureReportOfVessel/R/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" target="_blank" method="POST" style="color:white">
									<u>View</u>
								</a>
							</td-->
					<?php 
						// if($section=='billop')
						// { 
					?>	
							<!--td align="center" style="height:5%;">
								<form action="<?php echo site_url("VesselBill/generateVesselsBillNotEntering");?>" method="post" target="_blank">
									<input type="hidden" name="rot" value="<?php echo $departData[$i]['ib_vyg']; ?>" class="btn btn-sm btn-primary" style="height:2%;">
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
									if($isExist > 0)
									{
								?>
									<a class="btn btn-primary"  href="<?php echo site_url('VesselBill/generateVesselsBillNotEntering/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" style="color:white" <?php if($chkBill>0){ ?>disabled<?php } ?> onclick="return confirMsg();">
										Generate Bill
									</a>	
								<?php	
									}
									else
									{
								?>
									<a class="btn btn-danger"  href="<?php echo site_url('Vessel/usdtoBdtExchangeRateform/')?>" style="color:white">
										<u>Rate Setting</u>
									</a>
								<?php
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
					<?php 
						if($section=='acc') 
						{ 
					?>
					<div class="row">
						<div class="col-sm-12 text-center">
							<!--button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Forward
							</button-->
							<p class="btn p-3 mb-2 bg-white text-dark" style="cursor:default">You have selected <label id="item" style="font-size:20px;">0</label> Item </p>
							<input type="submit" name="fwBtn" id="forward" value="Forward" class="mb-xs mt-xs mr-xs btn btn-success"/>
						</div>													
					</div>
					<?php 
						}
					?>					
					 <?php if($section=='acc') { ?> 
					<table width=100%>				
						<tr>
							<td align="left">
								এতদসঙ্গে সংশ্লিষ্ট শিপিং এজেন্ট এর অনুকূলে প্রেরিত বিলের     কপি অনুলিপি অত্র দপ্তরে সংরক্ষণের জন্য প্রেরণের অনুরোধ করা হলো।  <br/>
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