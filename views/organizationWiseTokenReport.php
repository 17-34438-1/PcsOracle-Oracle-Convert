<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>
		<?php include("mydbPConnection.php"); ?>
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
							<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">SL No</th>
										<th class="text-center">Organization Name</th>
										<th class="text-center">AIN</th>
										<th class="text-center">Total Purchased Token</th>	
										<th class="text-center">Used Token</th>	
										<th class="text-center">Remaining Token</th>		
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($org_list);$i++){ ?>
									<tr class="gradeX">
										<td align="center"><?php echo $i+1;?></td>
										<td align="center"><?php echo $org_list[$i]['Organization_Name'];?></td>
										<td align="center"><?php echo $org_list[$i]['AIN_No_New'];?></td>
										<td align="center"><?php echo $org_list[$i]['total_token'];?></td>
										<td align="center"><?php echo $org_list[$i]['total_used'];?></td>
										<td align="center"><?php echo $org_list[$i]['total_pending'];?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12 text-center">
									<a href="<?php echo site_url('EDOController/organizationWiseTokenReportPrint') ?>" target="_blank">
										<button class="btn btn-success">
											Print All
										</button>
									</a>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>