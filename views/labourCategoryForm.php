<script>
	function validation(){
		if (confirm("Do you want to detete this Category?") == true){
			return true;
		}else{
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
								action="<?php echo site_url('report/labourCategoryInsert') ?>">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
										</div>
									</div>
									<div class="col-md-offset-3 col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Category Name :</span>
											<input type="text" name="category" id="category" class="form-control" 
												value="<?php if(isset($_POST['edit'])){echo $catInfoById[0]['CategoryName'];} ?>" required>
											<input type="hidden" name="cngKey" id="cngKey" value="<?php if(isset($_POST['edit'])){echo $catInfoById[0]['CategoryID'];} ?>"/>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Remarks :</span>												
											<textarea name="remarks" id="remarks" rows="2" class="form-control" required> 
												<?php if(isset($_POST['edit'])){echo $catInfoById[0]['Remarks'];} ?>
											</textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php if(!isset($_POST['edit'])){?>
											<button type="submit" name="save" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
											<?php }else if(isset($_POST['edit'])){ ?>
											<button type="submit" name="update" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Update</button>
											<?php } ?>
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
										<th class="text-center">Category Name</th>
										<th class="text-center">Remarks</th>
										<th class="text-center">Action</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($catInf);$i++){ ?>
									<tr class="gradeX">
										<td align="center"><?php echo $i+1;?></td>
										<td align="center"><?php echo $catInf[$i]['CategoryName']; ?></td>
										<td align="center"><?php echo $catInf[$i]['Remarks']; ?></td>
										<td align="center">
											<form action="<?php echo site_url('report/labourCategoryAction');?>" method="POST">
												<input type="hidden" name="key" id="key" value="<?php echo $catInf[$i]['CategoryID']; ?>">
												<button type="submit" name="edit" id="edit" class="mb-xs mt-xs mr-xs btn btn-success">Edit</button>
											</form>
										</td>
										<td align="center">
											<form action="<?php echo site_url('report/labourCategoryAction');?>" method="POST"  onsubmit="return validation()">
												<input type="hidden" name="key" id="key" value="<?php echo $catInf[$i]['CategoryID']; ?>">
												<button type="submit" name="delete" id="delete" 
													class="mb-xs mt-xs mr-xs btn btn-danger">
													Delete
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