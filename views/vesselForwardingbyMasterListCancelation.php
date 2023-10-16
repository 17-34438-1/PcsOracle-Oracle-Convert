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
<?php $Control_Panel = $this->session->userdata('Control_Panel'); ?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>	
		<div class="right-wrapper pull-right"></div>
	</header>

			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Vessel/searchCancellationVslFromLetter'); ?>"  id="myform" name="myform">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Import Rotation : <span class="required">*</span></span>
											<input type="text" name="searchRotation" id="searchRotation" style="width:180px" class="form-control" value="">

										</div>
									</div>
																					
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
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
			
		<!-- start: Table -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">					
							<table class="table table-bordered mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">SL</th>
										<th class="text-center">File Date</th>
										<th class="text-center">File No</th>
										<th class="text-center">File Subject</th>
										<th class="text-center">VesselNum</th>																		
									<?php if($section=='acc') { ?>
										<th class="text-center">  Forwarding </th>
									<?php } else if($section=='billop') { ?>
										<th>My Task <?php } ?></th>
									<?php  if($section=='acc') { ?>
										<th class="text-center">Pending </th>	
									<?php } ?>	
										<th class="text-center">Letter</th>	
									<?php if($section=='acc' || $section=='billop') { ?>	
										<th class="text-center">Action</th>	
									<?php } ?>
										<th class="text-center">Statement</th>
										
									</tr>
								</thead>
								<tbody>
									<?php 
										for($i=0;$i<count($letterList);$i++){
									?>
									<tr>
										<td align="center" style="height:5%;"><?php echo $i+1;?></td>
										<td align="center" style="height:5%;"><?php echo $letterList[$i]['file_dt'];?></td>
										<td align="center" style="height:5%;"><?php echo $letterList[$i]['file_no'];?></td>
										<td align="center" style="height:5%;"><?php echo $letterList[$i]['file_sub'];?></td>										
										<td align="center" style="height:5%;"><?php echo $letterList[$i]['no_vsl'];?></td>
										

									<?php if($section=='acc' ) { ?>
										<td align="center" style="height:5%;"><?php echo $letterList[$i]['no_vsl']- $letterList[$i]['pending_no'];?></td>
									<?php } else if($section=='billop') { ?>
										<td align="center" style="height:5%;"><?php echo $letterList[$i]['pending_no'];?></td>

									<?php } ?>
									<?php if($section=='acc') { ?>	
										<td align="center" style="height:5%;"><?php echo $letterList[$i]['pending_no'];?></td>
										<?php } ?>
										<td align="center" style="height:5%;">
											<form action="<?php echo site_url("Vessel/vesselForwardingLetter_Cancelation");?>" method="post" target="_blank">
												<input type="hidden" name="letter_id" id="letter_id" value="<?php echo $letterList[$i]['letter_no'];?>">
												<input type="hidden" name="file_dt" id="file_dt" value="<?php echo $letterList[$i]['file_dt'];?>">
												<input type="hidden" name="file_sub" id="file_sub" value="<?php echo $letterList[$i]['file_sub'];?>">
												<input type="hidden" name="file_no" id="file_no" value="<?php echo $letterList[$i]['file_no'];?>">
												<input type="hidden" name="no_vsl" id="no_vsl" value="<?php echo $letterList[$i]['no_vsl'];?>">
												<input type="submit" value="ViewLetter" class="btn btn-sm btn-primary" style="height:2%;">
											</form>
										</td>
										<?php if($section=='acc' ) { ?>	
										<td align="center" style="height:5%;">
											<form action="<?php echo site_url("Vessel/vesselForwardingForAcc_Cancelation");?>" method="post">
												<input type="hidden" name="letter_id" id="letter_id" value="<?php echo $letterList[$i]['letter_no'];?>">
												<input type="hidden" name="file_dt" id="file_dt" value="<?php echo $letterList[$i]['file_dt'];?>">
												<input type="hidden" name="file_sub" id="file_sub" value="<?php echo $letterList[$i]['file_sub'];?>">
												<input type="hidden" name="file_no" id="file_no" value="<?php echo $letterList[$i]['file_no'];?>">
												<input type="hidden" name="no_vsl" id="no_vsl" value="<?php echo $letterList[$i]['no_vsl'];?>">
												<input type="submit" value="Forward" class="btn btn-sm btn-primary" style="height:2%;">
											</form>
										</td>
										<?php } ?>	
										
										<?php if($section=='billop') { ?>	
										<td align="center" style="height:5%;">
											<form action="<?php echo site_url("Vessel/vesselForwardingForAcc_Cancelation");?>" method="post">
												<input type="hidden" name="letter_id" id="letter_id" value="<?php echo $letterList[$i]['letter_no'];?>">
												<input type="hidden" name="file_dt" id="file_dt" value="<?php echo $letterList[$i]['file_dt'];?>">
												<input type="hidden" name="file_sub" id="file_sub" value="<?php echo $letterList[$i]['file_sub'];?>">
												<input type="hidden" name="file_no" id="file_no" value="<?php echo $letterList[$i]['file_no'];?>">
												<input type="hidden" name="no_vsl" id="no_vsl" value="<?php echo $letterList[$i]['no_vsl'];?>">
												<input type="submit" value="Gerneration List" class="btn btn-sm btn-primary" style="height:2%;">
											</form>
										</td>
										<?php } ?>
										
										<td align="center" style="height:5%;">
											<form action="<?php echo site_url("Vessel/vesselForwardingLetter_Cancelation/stmt");?>" method="post" target="_blank">
												<input type="hidden" name="letter_id" id="letter_id" value="<?php echo $letterList[$i]['letter_no'];?>">
												<input type="hidden" name="file_dt" id="file_dt" value="<?php echo $letterList[$i]['file_dt'];?>">
												<input type="hidden" name="file_sub" id="file_sub" value="<?php echo $letterList[$i]['file_sub'];?>">
												<input type="hidden" name="file_no" id="file_no" value="<?php echo $letterList[$i]['file_no'];?>">
												<input type="hidden" name="no_vsl" id="no_vsl" value="<?php echo $letterList[$i]['no_vsl'];?>">
												<input type="submit" value="Statement" class="btn btn-sm btn-primary" style="height:2%;">
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
		<!-- end: Table -->
	</section>
	</div>