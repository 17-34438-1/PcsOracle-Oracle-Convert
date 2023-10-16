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
								action="<?php echo site_url('report/yardListViewSearchList') ?>">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">TERMINAL :</span>
											<input type="text" name="rot_num" id="rot_num" class="form-control" 
												value="<?php if($listType=="search"){echo $terminalName;} else{echo "";}?>" required>
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
										<th class="text-center">SL</th>
										<th class="text-center">Terminal</th>
										<th class="text-center">Block</th>
										<th class="text-center">Block(CPA)</th>	
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($rtnVesselList);$i++){ ?>
									<tr class="gradeX">
										<td align="center" style="color:red"><?php echo $i+1;?></td>
										<td align="center"><?php echo $rtnVesselList[$i]['terminal'];?></td>
										<td align="center"><?php echo $rtnVesselList[$i]['block'];?></td>
										<td align="center"><?php echo $rtnVesselList[$i]['block_cpa'];?></td>
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