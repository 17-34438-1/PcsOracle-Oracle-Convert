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
		
				<?php
					if(!is_null($this->session->flashdata('success'))){
						echo $this->session->flashdata('success');
					}

					if(!is_null($this->session->flashdata('error'))){
						echo $this->session->flashdata('error');
					}
				?>
				
				<div class="row">
					<div class="col-sm-12 text-center">
						<?php echo $msg;?>
					</div>
				</div>
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<input type="hidden" id="rotCount" name="rotCount" value="<?php echo count($departData);?>">
						
						<thead>
							<tr>
							
								<th rowspan="2" class="text-center">SL</th>
								<th rowspan="2" class="text-center">Vessel Name</th>
								<th rowspan="2" class="text-center"><nobr>Arrival Time</nobr></th>								
								<th rowspan="2" class="text-center"><nobr>Depart Time</nobr></th>
								<th rowspan="2" class="text-center"><nobr>Rotation</nobr></th>
								<th rowspan="2" class="text-center">Country</th>
								<th colspan="2" class="text-center">Tonage</th>
								
								<th rowspan="2" class="text-center">Local Agent</th>
								<th rowspan="2" class="text-center">Remarks</th>
								<th rowspan="2" class="text-center">Forwarded Date</th>									

								
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
						
							<td><?php echo $i+1;?></td>
							<td><?php echo $departData[$i]['vsl_name'];?></td>
							<td><?php echo $departData[$i]['ata'];?></td>
							<td><?php echo $departData[$i]['atd'];?></td>												
							<td><?php echo $departData[$i]['ib_vyg'];?></td>
							<td><?php echo $departData[$i]['cntry_name'];?></td>
							<td><?php echo $departData[$i]['grt'];?></td>
							<td><?php echo $departData[$i]['nrt'];?></td>
							<!--td><?php echo $departData[$i]['loa_cm'];?></td-->
							
							<td><?php echo $departData[$i]['agent'];?></td>
							<td><?php echo $departData[$i]['forward_remarks'];?></td>
							<td><?php echo $departData[$i]['forwarded_dt'];?></td>
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
								
						<?php 
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