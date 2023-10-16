<style>
	#table-scroll 
	{
		height:600px;
		overflow:auto;  
		margin-top:20px;
	}
</style>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
    <?php include("mydbPConnection.php"); ?>
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/viewVesselStatusSearchList'); ?>" >
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<?php echo $msg; ?>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation Number : <em>&nbsp;</em><span class="required">*</span></span>
									<input type="text" id="rot_num" name="rot_num" class="form-control login_input_text"> 
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>					
			<section class="panel">
				<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-responsive table-bordered table-striped mb-none" id="datatable-default">
						<thead>
							<tr class="primary" align="center">
								<td><b>SL.</b></td>
								<td><b>Vessel Name</b></td>							
								<td><b>Imp Rot</b></td>
								<td><b>Exp Rot</b></td>
								<td><b>Agent</b></td>
								<td><b>Status</b></td>
								<td><b>Arrival</b></td>
								<td><b>Shifting</b></td>
								<td><b>Departure</b></td>
								<td><b>Report</b></td>
							</tr>
						</thead>
						<tbody>
						<?php
						for($i=0;$i<count($rtnVesselList);$i++)
						{
							$VVD_GKEY = $rtnVesselList[$i]['VVD_GKEY'];
							
							$chkArrival = 0;
							$sql_chkArrival = "SELECT COUNT(*) AS cnt FROM doc_vsl_arrival WHERE VVD_GKEY='$VVD_GKEY'";						
							$rslt_chkArrival = mysqli_query($con_cchaportdb,$sql_chkArrival);
														
							while($row = mysqli_fetch_object($rslt_chkArrival))
							{
								$chkArrival=$row->cnt;
							}
						?>
							<tr align="center" class="info">
								<td style="color:red"><?php echo $i+1;?></td>
								<td style="display:none"><?php echo $rtnVesselList[$i]['VVD_GKEY'];?></td>
								<td>
									<?php echo "<b>".$rtnVesselList[$i]['NAME']."</b>";?>
								</td>
								<td><?php echo $rtnVesselList[$i]['IB_VYG'];?></td>
								<td><?php echo $rtnVesselList[$i]['OB_VYG'];?></td>						
								<td><?php echo $rtnVesselList[$i]['AGENT'];?></td>											
								<td 
									<?php if ($rtnVesselList[$i]['PHASE_NUM']=='20')
											{?>style="background-color:#F6D8CE"<?php } else if($rtnVesselList[$i]['PHASE_NUM']=='30'){?>style="background-color:#F78181" <?php } else if($rtnVesselList[$i]['PHASE_NUM']=='40'){?>style="background-color:#FACC2E"<?php } else if($rtnVesselList[$i]['PHASE_NUM']=='50'){?>style="background-color:#F5A9A9"<?php } else if($rtnVesselList[$i]['PHASE_NUM']=='60'){?>style="background-color:#610B0B"<?php }?>>
															
								<?php echo $rtnVesselList[$i]['PHASE_STR'];?></td>
								
								<td>
									<a href="<?php echo site_url('report/departureReportOfVessel/A/'.str_replace("/","_",$rtnVesselList[$i]['IB_VYG']))?>" target="_blank" method="POST" style="color:white">
										<u>ARRIVAL </u>
									</a>
								</td>
								<td>
									<?php if($chkArrival > 0) { ?>
										<a href="<?php echo site_url('report/shiftingListOfVessel/'.str_replace("/","_",$rtnVesselList[$i]['IB_VYG']).'/'.$rtnVesselList[$i]['VVD_GKEY'].'/'.$rtnVesselList[$i]['NAME'].'/'.$rtnVesselList[$i]['AGENT'])?>" target="_blank" method="POST" style="color:white">
											<u>SHIFTING</u>
										</a>
									<?php } ?>
								</td>
								<td>
									<?php if($chkArrival > 0) { ?>
									<a href="<?php echo site_url('report/departureReportOfVessel/D/'.str_replace("/","_",$rtnVesselList[$i]['IB_VYG']))?>" target="_blank" method="POST" style="color:white">
										<u>DEPARTURE</u>
									</a>
									<?php } ?>
								</td>
								<td>
									<a href="<?php echo site_url('Report/departureReportOfVessel/R/'.str_replace("/","_",$rtnVesselList[$i]['IB_VYG']))?>" target="_blank" method="POST" style="color:white">
										<u>VIEW</u>
									</a>
								</td>					
							</tr>
						<?php
						}
						?>
						</tbody>
					</table>
				</div>
				</div>
			</section>
		</div>
	</div>
	<?php mysqli_close($con_cchaportdb); ?>
</section>
