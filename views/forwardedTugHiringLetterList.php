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
								<!--a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a-->
							</h2>								
						</header>
						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" 
								action="<?php echo site_url('Vessel/SearchForwardedTugHiringLetterListByDate') ?>">
								
								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
										</div>
									</div>
									<div class="col-md-6 col-md-offset-3">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">From Date :</span>
											<input type="date" name="from_date" id="from_date" class="form-control"
												value="<?php if($frmType=="search") echo $from_date; ?>">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">To Date :</span>
											<input type="date" name="to_date" id="to_date" class="form-control" 
												value="<?php if($frmType=="search") echo $to_date; ?>">
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="btnSave" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											
										</div>
									</div>
								</div>	
							</form>
							<hr/>					
							<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr>
										<th style="text-align: center">SL</th>
										<th style="text-align: center">Forwarding Date</th>
										<th style="text-align: center">File No</th>
										<th style="text-align: center">Subject</th>
										<th style="text-align: center">Number of Vessel</th>
										<?php if($section=='acc') { ?>
										<th style="text-align: center">Forwarded</th>
										<th style="text-align: center">Pending</th>
										<th style="text-align: center">Forward</th>
										<?php } ?>
										<?php if($section=='billop') { ?>
										<th style="text-align: center">Forwarded</th>
										<th style="text-align: center">My Task</th>
										<th style="text-align: center">Generation List</th>
										<?php } ?>
										<th style="text-align: center">Letter</th>	
										<th style="text-align: center">Statement</th>	
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($tugHiringList);$i++){ ?>
									<tr class="gradeX">
										<td align="center"><?php echo $i+1;?></td>		
										<td align="center"><?php echo date('d/m/Y', strtotime($tugHiringList[$i]['forward_date']));?></td>
										<td align="center"><?php echo $tugHiringList[$i]['file_no'];?></td>		
										<td align="center"><?php echo "নৌযান ব্যবহারের তালিকা প্রেরণ প্রসঙ্গে । ";?></td>		
										<td align="center"><?php echo $tugHiringList[$i]['no_of_vsl'];?></td>	
										<?php if($section=='acc') { ?>
										<td align="center"><?php echo $tugHiringList[$i]['forwarded'];?></td>
										<td align="center"><?php echo $tugHiringList[$i]['pending'];?></td>
										<td align="center">
											<form action="<?php echo site_url('Vessel/SelectTugHiringListForForwardingToBillOp');?>" 
												method="POST">
												<input type="hidden" name="forwarding_id" id="forwarding_id" 
													value="<?php echo $tugHiringList[$i]['id'];?>">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-sm btn-primary">Forward</button>
											</form>
										</td>
										<?php } ?>
										<?php if($section=='billop') { ?>
										<td align="center"><?php echo $tugHiringList[$i]['forwarded'];?></td>
										<td align="center"><?php echo $tugHiringList[$i]['pending'];?></td>
										<td align="center">
											<form action="<?php echo site_url('Vessel/pendingTugHiringListForBillGeneration');?>" 
												method="POST">
												<input type="hidden" name="forwarding_id" id="forwarding_id" 
													value="<?php echo $tugHiringList[$i]['id'];?>">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-sm btn-primary">
													Generation List
												</button>
											</form>
										</td>
										<?php } ?>
										<td align="center">
											<form action="<?php echo site_url('Vessel/tugHiringForwardingLetterById');?>" method="POST" 			target="_blank">
												<input type="hidden" name="forwarding_id" id="forwarding_id" 
													value="<?php echo $tugHiringList[$i]['id'];?>">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-sm btn-success">Letter</button>
											</form>
										</td>		
										<td align="center">
											<form action="<?php echo site_url('Vessel/tugHiringForwardingStatementById');?>" method="POST"
													target="_blank">
												<input type="hidden" name="forwarding_id" id="forwarding_id" 
													value="<?php echo $tugHiringList[$i]['id'];?>">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-sm btn-primary">Statement</button>
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