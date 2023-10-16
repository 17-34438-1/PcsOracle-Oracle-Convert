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
						<!--header class="panel-heading">
							<h2 class="panel-title" align="right">
								<a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a>
							</h2>								
						</header-->
						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" action="<?php echo site_url('report/HolidayEntry') ?>">
								<input type="hidden" class="form-control" id="formType" name="formType" value="<?php echo $formType; ?>">
								<input type="hidden" class="form-control" id="holiday_declaration_id" name="holiday_declaration_id"
									value="<?php if($formType=="edit"){ echo $holiday_declaration_id;} else{echo "";} ?>">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
										</div>
									</div>
									<div class="col-md-6 col-md-offset-3">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Holiday Name :</span>
											<select class="form-control" name="holi_list_id" id="holi_list_id" required>
												<option value="">--Select--</option>
												<?php for($c=0;$c<count($holidayNameList);$c++){ ?>
													<option value="<?php echo $holidayNameList[$c]["id"];?>" 
														<?php if($formType=="edit" and $holiday_list_id==$holidayNameList[$c]["id"]) { ?> selected <?php } ?>>
														<?php echo $holidayNameList[$c]["holiday_name"];?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">From Date :</span>
											<input type="date" name="start_date" id="start_date" class="form-control"
												value="<?php if($formType=="edit") { echo $fromDate; } else { echo ""; } ?>" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">To Date :</span>
											<input type="date" name="end_date" id="end_date" class="form-control" 
												value="<?php if($formType=="edit") { echo $startDate; } else { echo ""; } ?>" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Remarks :</span>												
											<textarea name="holi_description" id="holi_description" rows="5" class="form-control"><?php if($formType=="edit") echo $holidayRemarks; ?></textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="btnSave" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
										</div>													
									</div>
								</div>	
							</form>
						</div>
					</section>
					<!--section class="panel">
						<div class="panel-body">
							<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">SL No</th>
										<th class="text-center">Holiday Name</th>
										<th class="text-center">From Date</th>
										<th class="text-center">To Date</th>
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($holidayList);$i++){ ?>
									<tr class="gradeX">
										<td align="center"><?php echo $i+1;?></td>
										<td align="center"><?php echo $holidayList[$i]['holiday_name']?></td>
										<td align="center"><?php echo $holidayList[$i]['start_date']?></td>
										<td align="center"><?php echo $holidayList[$i]['end_date']?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</section-->
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>