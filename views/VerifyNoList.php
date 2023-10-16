<script>
	function validate()
		{
			if (confirm("Do you want to detete this Gang Information?") == true)
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
							<table class="table table-bordered table-hover table-striped table-condensed mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">SL No</th>
										<th class="text-center">Rotation</th>
										<th class="text-center">Container</th>
										<th class="text-center">BL</th>
										<th class="text-center">Verify No</th>	
										<th class="text-center">Action</th>	
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($verifyNoList);$i++){ ?>
									<tr class="gradeX">
										<td align="center"><?php echo $i+1;?></td>
										<td align="center"><?php echo $verifyNoList[$i]['rotation']?></td>
										<td align="center"><?php echo $verifyNoList[$i]['cont_number']?></td>
										<td align="center"><?php echo $verifyNoList[$i]['bl_no']?></td>
										<td align="center"><?php echo $verifyNoList[$i]['verify_number']?></td>
										<td align="center">
											<form action="<?php echo site_url('ShedBillController/billGenerationForm');?>" method="POST">
												<input type="hidden" name="verify_number" id="verify_number" value="<?php echo $verifyNoList[$i]['verify_number'];?>">
												<input type="hidden" name="rotation" id="rotation" value="<?php echo $verifyNoList[$i]['rotation'];?>">
												<input type="hidden" name="cont_number" id="cont_number" value="<?php echo $verifyNoList[$i]['cont_number'];?>">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success btn-xs">Bill Generation</button>
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