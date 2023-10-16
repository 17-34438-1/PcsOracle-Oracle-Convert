<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" action="<?php echo site_url('report/viewVesselStatusPangaonSearchList') ?>">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation Number</span>
											<input type="text" id="rot_num" name="rot_num" class="form-control" value="" placeholder="rotation Number"/>
										</div>


									</div>									
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="save" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											
										</div>
									</div>
								</div>	
							</form>
                            
                            <hr/>
														
							<table class="table table-bordered table-striped mb-none" id="datatable-default">
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
						?>
							<tr align="center" class="info">
								<td style="color:red"><?php echo $i+1;?></td>
								<td style="display:none"><?php echo $rtnVesselList[$i]['vvd_gkey'];?></td>
								<td>
									<?php echo "<b>".$rtnVesselList[$i]['name']."</b>";?>
								</td>
								<td><?php echo $rtnVesselList[$i]['ib_vyg'];?></td>
								<td><?php echo $rtnVesselList[$i]['ob_vyg'];?></td>						
								<td><?php echo $rtnVesselList[$i]['agent'];?></td>											
								<td 
									<?php if ($rtnVesselList[$i]['phase_num']=='20')
											{?>style="background-color:#F6D8CE"<?php } else if($rtnVesselList[$i]['phase_num']=='30'){?>style="background-color:#F78181" <?php } else if($rtnVesselList[$i]['phase_num']=='40'){?>style="background-color:#FACC2E"<?php } else if($rtnVesselList[$i]['phase_num']=='50'){?>style="background-color:#F5A9A9"<?php } else if($rtnVesselList[$i]['phase_num']=='60'){?>style="background-color:#610B0B"<?php }?>>
															
								<?php echo $rtnVesselList[$i]['phase_str'];?></td>
								
								<td>
									<a href="<?php echo site_url('report/departureReportOfVessel/A/'.str_replace("/","_",$rtnVesselList[$i]['ib_vyg']))?>" target="_blank" method="POST" style="color:white">
										<u>ARRIVAL</u>
									</a>
								</td>
								<td>
									<a href="<?php echo site_url('report/departureReportOfVessel/S/'.str_replace("/","_",$rtnVesselList[$i]['ib_vyg']))?>" target="_blank" method="POST" style="color:white">
										<u>SHIFTING</u>
									</a>
								</td>
								<td>
									<a href="<?php echo site_url('report/departureReportOfVessel/D/'.str_replace("/","_",$rtnVesselList[$i]['ib_vyg']))?>" target="_blank" method="POST" style="color:white">
										<u>DEPARTURE</u>
									</a>
								</td>
								<td>
									<a href="<?php echo site_url('report/departureReportOfVesselPangaon/R/'.str_replace("/","_",$rtnVesselList[$i]['ib_vyg']))?>" target="_blank" method="POST" style="color:white">
										<u>View</u>
									</a>
								</td>					
							</tr>
						<?php
						}
						?>
						</tbody>
					</table>
							
							
						</div>
					</section>
			
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>