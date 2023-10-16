<script>
	function validate()
	{
		if (confirm("Are you sure!! Delete this record?") == true) {
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
									<th class="text-center">SL</th>	
									<th class="text-center">Labour ID</th>	
									<th class="text-center">Labour Name</th>	
									<th class="text-center">Category</th>	
									<th class="text-center">Gang</th>	
									<th class="text-center">Skills</th>	
									<th class="text-center">Terminal Op</th>	
									<th class="text-center">Pre. Address</th>	
									<th class="text-center">Per. Address</th>	
									<th class="text-center">Dt. of Berth</th>	
									<th class="text-center">Dt. of Joining</th>	
									<th class="text-center">Contact No</th>	
									<th class="text-center">Remarks</th>	
									<th class="text-center">Edit</th>	
									<th class="text-center">Delete</th>	
								</tr>
							</thead>
							<tbody>
								<?php for($i=0;$i<count($laborInfoList);$i++){ ?>
								<tr class="gradeX">
									<td align="center"><?php echo $i+1;?></td>
									<td align="center"><?php echo $laborInfoList[$i]['LaborID']?></td>
									<td align="center"><?php echo $laborInfoList[$i]['LaborName']?></td>
									<td align="center"><?php echo $laborInfoList[$i]['CategoryName']?></td>
									<td align="center"><?php echo $laborInfoList[$i]['GangID']?></td>
									<td align="center"><?php echo $laborInfoList[$i]['Skilled']?></td>
									<td align="center"><?php echo $laborInfoList[$i]['berthname']?></td>
									<td align="center"><?php echo $laborInfoList[$i]['PresentAddress']?></td>
									<td align="center"><?php echo $laborInfoList[$i]['PermanentAddress']?></td>
									<td align="center"><?php echo $laborInfoList[$i]['DateofBirth']?></td>
									<td align="center"><?php echo $laborInfoList[$i]['DateofJoining']?></td>
									<td align="center"><?php echo $laborInfoList[$i]['ContactNumber']?></td>
									<td align="center"><?php echo $laborInfoList[$i]['Remarks']?></td>
									<td align="center">
										<form action="<?php echo site_url('report/labourInfoEntryLasherEdit');?>" method="POST">
											<input type="hidden" id="laborId" name="laborId" value="<?php echo $laborInfoList[$i]['LaborDetailsID'];?>">							
											<!--input type="submit" value="Edit"  class="login_button" style="width:100%;"-->
											<button type="submit" name="Edit" class="mb-xs mt-xs mr-xs btn btn-success">Edit</button>
										</form>
									</td>
									<td align="center">
										<form action="<?php echo site_url('report/labourInfoEntryLasherList');?>" method="POST" onsubmit="return validate();">
											<input type="hidden" id="laborId" name="laborId" value="<?php echo $laborInfoList[$i]['LaborDetailsID'];?>">							
											<!--input type="submit" value="Del." name="delete" class="login_button" style="width:100%;"-->
											<button type="submit" name="Del." class="mb-xs mt-xs mr-xs btn btn-danger">Del.</button>
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