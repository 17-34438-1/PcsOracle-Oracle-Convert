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
								action="<?php echo site_url('report/GangEntry') ?>">
								<input type="hidden" class="form-control" id="frmType" name="frmType" value="<?php echo $frmType; ?>">
								<input type="hidden" class="form-control" id="gangByID" name="gangByID"
									value="<?php if($frmType=="edit"){ echo $editGangInfo[0]['GangInformationID'];} else{echo "";} ?>">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Gang ID :</span>
											<input type="text" name="gangID" id="gangID" class="form-control" 
												value="<?php if($frmType=="edit"){ echo $editGangInfo[0]['GangID'];} else{echo "";} ?>">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Gang Name :</span>
											<input type="text" name="gangName" id="gangName" class="form-control" 
												value="<?php if($frmType=="edit"){ echo $editGangInfo[0]['GangName'];} else{echo "";} ?>">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Required Labour :</span>
											<input type="text" name="rqrdLbr" id="rqrdLbr" class="form-control" 
												value="<?php if($frmType=="edit"){ echo $editGangInfo[0]['RequiredLabor'];} else{echo "";} ?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Remarks :</span>												
											<textarea name="gangRemarks" id="gangRemarks" rows="5" class="form-control"> 
												<?php if($frmType=="edit"){ echo $editGangInfo[0]['Remarks'];} else{echo "";} ?>
											</textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="btnSave" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
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
										<th class="text-center">SL No</th>
										<th class="text-center">Gang ID</th>
										<th class="text-center">Gang Name</th>
										<th class="text-center">Required Labour</th>	
										<th class="text-center">Remarks</th>	
										<th class="text-center">Action</th>	
										<th class="text-center">Action</th>	
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($rtnGangList);$i++){ ?>
									<tr class="gradeX">
										<td align="center"><?php echo $i+1;?></td>
										<td align="center"><?php echo $rtnGangList[$i]['GangID']?></td>
										<td align="center"><?php echo $rtnGangList[$i]['GangName']?></td>
										<td align="center"><?php echo $rtnGangList[$i]['RequiredLabor']?></td>
										<td align="center"><?php echo $rtnGangList[$i]['Remarks']?></td>
										<td align="center">
											<form action="<?php echo site_url('report/GangUpdate');?>" method="POST">
												<input type="hidden" name="editID" id="editID" value="<?php echo $rtnGangList[$i]['GangInformationID'];?>">
												<button type="submit" name="btnEdit" id="btnEdit" class="mb-xs mt-xs mr-xs btn btn-success">Edit</button>
											</form>
										</td>
										<td align="center">
											<form action="<?php echo site_url('report/GangDelete');?>" method="POST" onsubmit="return validate();">
												<input type="hidden" name="deleteID" id="deleteID" value="<?php echo $rtnGangList[$i]['GangInformationID'];?>">
												<button type="submit" name="btnDelete" id="btnDelete" class="mb-xs mt-xs mr-xs btn btn-danger">Delete</button>
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