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
<?php 
	$Control_Panel = $this->session->userdata('Control_Panel'); 
	$org_Type_id = $this->session->userdata('org_Type_id'); 
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>	
		<div class="right-wrapper pull-right"></div>
	</header>
	<?php if($Control_Panel=="28") { ?>
		<!--div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" 
							action="<?php echo site_url('Login/organizationProfileList') ?>">
							
							<div class="form-group">
								<div class="col-md-6 col-md-offset-3">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">AIN No :</span>
										<input type="text" name="ain_no" id="ain_no" class="form-control" 
											value="<?php if(isset($ain_no)) echo $ain_no; else echo "";?>">
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
					</div>
				</section>
		
			</div>
		</div-->
	<?php } ?>

		<!-- start: Table -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">					
							<table class="table table-bordered mb-none" id="datatable-default">
								<thead>
									<tr>
										<!--th class="text-center">#Sl</th-->
										<th class="text-center">Organization Id</th>
										<th class="text-center">Organization Name</th>
										<th class="text-center">Organization Type</th>
										<th class="text-center">AIN NO</th>
										<th class="text-center">License</th>
										<?php if($org_Type_id=="28") { ?>
											<th class="text-center">Created by</th>	
											<th class="text-center">Updated by</th>	
										<?php } ?>
										<th class="text-center">Action</th>		
										
									</tr>
								</thead>
								<tbody>
									<?php 
										$j=$start;
										for($i=0;$i<count($orgProfileList);$i++){
										$j++;
									?>
									<tr>
										<!--td align="center" style="height:5%;"><?php echo $j;?></td-->
										<td align="center" style="height:5%;"><?php echo $orgProfileList[$i]['id'];?></td>
										<td align="center" style="height:5%;"><?php echo $orgProfileList[$i]['Organization_Name'];?></td>
										<td align="center" style="height:5%;"><?php echo $orgProfileList[$i]['Org_Type'];?></td>
										<td align="center" style="height:5%;">
											<nobr>
												<?php 
													if($orgProfileList[$i]['AIN_No_New']==" " or $orgProfileList[$i]['AIN_No_New']=="" 
														or $orgProfileList[$i]['AIN_No_New']==NULL){
														echo $orgProfileList[$i]['AIN_No'];
													} else {
														echo $orgProfileList[$i]['AIN_No_New'];
													}
												?>
											</nobr>
										</td>
										<td align="center" style="height:5%;"><?php echo $orgProfileList[$i]['License_No'];?></td>
										
										<?php if($Control_Panel=="28") { ?>
											<td align="center" style="height:5%;"><?php echo $orgProfileList[$i]['entered_by'];?></td>
											<td align="center" style="height:5%;"><?php echo $orgProfileList[$i]['Last_Update_By_id'];?></td>
										<?php } ?>
										
										<td align="center" style="height:5%;">
											<form action="<?php echo site_url("Login/orgProfileUpdate");?>" method="post">
												<input type="hidden" name="update" id="update" value="update">
												<input type="hidden" name="proId" id="proId" value="<?php echo $orgProfileList[$i]['id'];?>">
												<input type="submit" value="Update" class="btn btn-sm btn-primary" style="height:2%;">
												<!--button type="submit" name="report" class="mb-xs mt-xs mr-xs btn btn-primary" style="height:2%;">
													Update
												</button-->
											</form>
										</td>
										
									</tr>
									<?php } ?>
									<!------------Following row is for basic PHP pagination------------------>
									<!--tr>
										<td colspan="7" align="center"><?php echo $links; ?></td>
									</tr-->
								</tbody>
							</table>
						</div>
					</section>
			
				</div>
			</div>	
		<!-- end: Table -->
	</section>
	</div>