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
	
	<?php if($flag==1) 
	{ 
	include("dbConection.php");
	include("mydbPConnection.php");
	if($org_Type_id=='81') { ?>

	<div class="row">	
	<div class="col-lg-12">						
		<div class="panel-body">
		
		
										
			
			<?php } ?>		
			<section class="panel">
		
				
				<div class="row">
					<div class="col-sm-12 text-center">
						<?php echo $msg;?>
					</div>
				</div>
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<input type="hidden" id="rotCount" name="rotCount" value="<?php echo count($departData);?>">
						
						<thead>
							<tr>							
								<th class="text-center">SL</th>
								<th class="text-center">Rotation</th>
								<th class="text-center">Ship</th>
								<th class="text-center">Forwarded Date</th>
								<th class="text-center">Status</th>
								<th class="text-center">Report</th>
							</tr>

							
						</thead>
						<?php 
							for($i=0;$i<count($departData);$i++)
							{ 
							
							$vvd_gkey = $departData[$i]['vvd_gkey'];
							// $depart_query = "SELECT * FROM doc_vsl_depart WHERE vvd_gkey = '$vvd_gkey'";
							// $depart_data = $this->bm->dataSelectDB1($depart_query);
							// $pilot_id ="";
							// $pilot_name = "";
							// if(count($depart_data)>0){
							// 	$pilot_id = $depart_data[0]['pilot_name'];
							// 	$pilotNameQuery = "SELECT u_name FROM users WHERE login_id = '$pilot_id'";
							// 	$pilotName_data = $this->bm->dataSelectDB1($pilotNameQuery);
							// 	if(count($pilotName_data)>0){
							// 		$pilot_name = $pilotName_data[0]['u_name'];
							// 	}
							// }
						?>
						<tr align="center">
							<?php
								$totalReport = 0;
								// $ddl_imp_rot_no = $departData[$i]['ib_vyg'];
								// $chk_depart_query = "SELECT igm_id AS igm_id  FROM igm_masters
								// INNER JOIN doc_vsl_depart ON  igm_masters.id=doc_vsl_depart.igm_id
								// WHERE Import_Rotation_No = '$ddl_imp_rot_no'";
				
								// $chkDepart = $this->bm->dataSelectDb1($chk_depart_query);
								// $igm_id_depart = 0;
								// if(count($chkDepart)>0){
								// 	$igm_id_depart=$chkDepart[0]['igm_id'];
								// }
								
								
								// $chk_arrival_query = "SELECT igm_id AS igm_id  FROM igm_masters
								// INNER JOIN doc_vsl_arrival ON  igm_masters.id=doc_vsl_arrival.igm_id
								// WHERE Import_Rotation_No = '$ddl_imp_rot_no'";
								// //return;
				
								// $chkArrival = $this->bm->dataSelectDb1($chk_arrival_query);
								
								// $igm_id_arraival = 0;
								// if(count($chkArrival)>0){
								// 	$igm_id_arraival=$chkArrival[0]['igm_id'];
								// }
								
								// $chk_shift_query = "SELECT igm_id AS igm_id  FROM igm_masters
								// INNER JOIN doc_vsl_shift ON  igm_masters.id=doc_vsl_shift.igm_id
								// WHERE Import_Rotation_No = '$ddl_imp_rot_no'";
				
								// $chkShift = $this->bm->dataSelectDb1($chk_shift_query);
								// $igm_id_shift = 0;
								// if(count($chkShift)>0){
								// 	$igm_id_shift=$chkShift[0]['igm_id'];
								// }

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
							<td><?php echo $i+1;?></td>
							<td><?php echo $departData[$i]['ib_vyg'];?></td>
							<td><?php echo $departData[$i]['vsl_name'];?></td>
							<td><?php echo $departData[$i]['forwarded_dt'];?></td>
							<td>
								<?php 
									// if($igm_id_depart==0 && $igm_id_arraival==0 && $igm_id_shift==0){
									if(count($chkDepart)==0 && count($chkArrival)==0 && count($chkShift)==0)
									{ 
										echo "<font color='red'> Disabled due to pilot handling issue </font>"; 
									} 
								?>
							</td>
							<td>
								<?php
									// if($igm_id_depart==0 && $igm_id_arraival==0 && $igm_id_shift==0){
									if(count($chkDepart)==0 && count($chkArrival)==0 && count($chkShift)==0){
								?>
									<span class="btn btn-primary" disabled>View</span>
								<?php
									}else{
								?>
									<a href="<?php echo site_url('Report/departureReportOfVessel/R/'.str_replace("/","_",$departData[$i]['ib_vyg']))?>" target="_blank" method="POST">
										<span class="btn btn-primary">View</span>
									</a>
								<?php
									}
								?>
							</td>
							
							
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
						</tr>
						
						<?php 
							} 
						?>
											
					</table>																				
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