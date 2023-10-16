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
							<form class="form-horizontal form-bordered" method="POST" 
								action="<?php echo base_url().'index.php/PShedController/pShedTallyFormList'; ?>" target="" 
									id="myform" name="myform" onsubmit="return validate()">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
											<input type="text" name="ddl_imp_rot_no" id="ddl_imp_rot_no" class="form-control" 
												placeholder="Rotation No" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
											<input type="text" name="ddl_cont_no" id="ddl_cont_no" class="form-control" 
												placeholder="Rotation No" required>
										</div>
									</div>									
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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
										<th class="text-center">#Sl</th>
										<th class="text-center">Tally Sheet No</th>
										<th class="text-center">Container No</th>
										<th class="text-center">Rotation</th>
										<th class="text-center">Total Rcv Pkg</th>
										<th class="text-center">Total Rcv Weight</th>
										<th class="text-center">Yard/Shed</th>
										<th class="text-center">Unstuffing Date</th>
										<th class="text-center">Report</th>							
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($rtnContainerList);$i++){?>
									<tr>
										<td align="center"><?php echo $i+1;?></td>
										<td align="center" style="color:red"><?php echo $rtnContainerList[$i]['tally_sheet_number'];?></td>
										<td align="center"><?php echo $rtnContainerList[$i]['cont_number'];?></td>
										<!--td><?php echo $rtnContainerList[$i]['BL_NO'];?></td-->
										<td align="center"><?php echo $rtnContainerList[$i]['import_rotation'];?></td>
										<td align="center"><?php echo $rtnContainerList[$i]['total_pack'];?></td>
										<td align="center"><?php echo $rtnContainerList[$i]['weight'];?></td>
										<td align="center"><?php echo $rtnContainerList[$i]['shed_yard'];?></td>
										<td align="center"><?php echo $rtnContainerList[$i]['wr_date'];?></td>
										<td align="center">
											<form name="tallyreport" id="tallyreport" target="_blank" 
												action="<?php echo site_url("PShedController/pShedTallyReportPdf");?>" method="post">
												<input type="hidden" name="rotation" id="rotation" 
													value="<?php echo $rtnContainerList[$i]['import_rotation'];?>">
												<input type="hidden" name="container" id="container" 
													value="<?php echo $rtnContainerList[$i]['cont_number'];?>">
												<button type="submit" name="report" class="login_button mb-xs mt-xs mr-xs btn btn-primary">
													Report
												</button>
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