<script>
	function chkConfirm()
	{
		if (confirm("Do you want to delete ?") == true)
			{
				return true ;
			}
		else
			{
				return false;
			}
	}
</script>
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
						<header class="panel-heading">
							<h2 class="panel-title" align="right">
								<a href="<?php echo site_url('report/departureReportOfVessel/S/'.str_replace("/","_",$rotation)."/".$vvd_gkey."/".$agent) ?>">
									<button style="margin-left: 35%" class="btn btn-success btn-sm">
										ADD <i class="fa fa-plus"></i>
									</button>
								</a>
							</h2>								
						</header>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<div align="center">
										<h4><?php echo $msg;?></h4>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4"><div align="left">ROTATION : <b><?php echo $rotation; ?></b></div></div>
								<div class="col-md-4"><div align="center">VESSEL NAME : <b><?php echo $vessel_name; ?></b></div></div>
								<div class="col-md-4"><div align="right">AGENT : <b><?php echo $agent; ?></b></div></div>
							</div>
						</div>
						<div class="panel-body">					
							<table class="table table-bordered mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center"><nobr>#Sl</nobr></th>
										<th class="text-center"><nobr>Pilot Name</nobr></th>
										<th class="text-center"><nobr>Pilot On Board</nobr></th>
										<th class="text-center"><nobr>Pilot Off Board</nobr></th>
										<th class="text-center"><nobr>Shifted From</nobr></th>
										<th class="text-center"><nobr>Shifted To</nobr></th>						
										<th class="text-center"><nobr>Mooring Time (from)</nobr></th>						
										<th class="text-center"><nobr>Mooring Time (To)</nobr></th>						
										<th class="text-center"><nobr>Action</nobr></th>						
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($shiftingList);$i++){?>
									<tr>
										<td align="center" style="height:5%;"><?php echo $i+1;?></td>
										<td align="center" style="height:5%;"><nobr><?php echo $shiftingList[$i]['pilot_name'];?></nobr></td>
										<td align="center" style="height:5%;">
											<nobr>
												<?php 
													if($shiftingList[$i]['pilot_on_board'] != "0000-00-00 00:00:00"){
														echo $shiftingList[$i]['pilot_on_board'];
													}													
												?>
											</nobr>
										</td>
										<td align="center" style="height:5%;">
											<nobr>
												<?php 
													if($shiftingList[$i]['pilot_off_board'] != "0000-00-00 00:00:00"){
														echo $shiftingList[$i]['pilot_off_board'];
													}
												?>
											</nobr>
										</td>
										<td align="center" style="height:5%;"><nobr><?php echo $shiftingList[$i]['shift_frm'];?></nobr></td>
										<td align="center" style="height:5%;"><nobr><?php echo $shiftingList[$i]['shift_to'];?></nobr></td>
										<td align="center" style="height:5%;">
											<nobr>
												<?php 
													if($shiftingList[$i]['mooring_frm_time'] != "0000-00-00 00:00:00"){
														echo $shiftingList[$i]['mooring_frm_time'];
													}
												?>
											</nobr>
										</td>
										<td align="center" style="height:5%;">
											<nobr>
												<?php 
													if($shiftingList[$i]['mooring_to_time'] != "0000-00-00 00:00:00"){
														echo $shiftingList[$i]['mooring_to_time'];
													}
												?>
											</nobr>
										</td>
										<td style="height:5%;">
											<form action="<?php echo site_url("report/editShiftingInformation");?>" method="post">
												<input type="hidden" name="shifting_id" id="shifting_id" 
													value="<?php echo $shiftingList[$i]['id'];?>">
												<input type="hidden" name="rotation" id="rotation" value="<?php echo $rotation;?>">
												<input type="hidden" name="vvd_gkey" id="vvd_gkey" value="<?php echo $vvd_gkey;?>">
												<input type="hidden" name="agent" id="agent" value="<?php echo $agent;?>">
												<input type="submit" value="EDIT" class="btn btn-sm btn-primary" style="height:2%;">
											</form>
										</td>										
									</tr>
									<?php } ?>
								</tbody>
							</table>
							
							
						</div>
					</section>
			
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>