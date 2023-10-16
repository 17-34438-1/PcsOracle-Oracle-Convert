 <?php
	include("FrontEnd/dbConection.php");
 ?>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title; ?></h2>
					
						<div class="right-wrapper pull-right">
						
						</div>
					</header>

					<!-- start: page -->
						<section class="panel">
							<!--header class="panel-heading">
								<h2 class="panel-title" align="right">
									<a href="<?php echo site_url('ontroller/EntryForm') ?>">
										<button style="margin-left: 35%" class="btn btn-primary btn-sm">
											<i class="fa fa-plus"></i>
										</button>
									</a>									
								</h2>								
							</header-->
							<div class="panel-body">
									<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('uploadExcel/updateEquipmentList') ?>">
									<div class="form-group">
										
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Equipment</span>
												<input type="text" name="search" id="search" class="form-control" >
											</div>
										</div>
										<div class="col-sm-12 text-center">
											<button type="submit" name="Equipment" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary">Search</button>
										</div>													
									</div>
									<div class="form-group">
									</div>
								</form>
								<table class="table table-bordered table-hover table-striped mb-none">
									<thead>
										<tr>
											<th class="text-center">Sl</th>
											<th class="text-center">Equipment</th>
											<th class="text-center">Description</th>
											<th class="text-center">Capacity</th>	
											<th class="text-center">Action</th>	
										</tr>
									</thead>
									<tbody>
										<?php 
											$j=$start;
											for($i=0;$i<count($equipmentList);$i++) { 
											$j++;
											$equip=$equipmentList[$i]['equipement'];
											$strequip = "select distinct description,capacity from ctmsmis.mis_equip_detail where equipment='$equip'";
											$resequip=mysqli_query($con_sparcsn4,$strequip);
											$des = "";
											$cap = "";
											while($rowequip=mysqli_fetch_object($resequip))
											{
												$des = $rowequip->description;
												$cap = $rowequip->capacity;
											}
										?>
										<form action="<?php echo site_url('uploadExcel/updateEquipmentPerform');?>" method="POST">
										<input type="hidden" value="<?php echo $equipmentList[$i]['equipement'];?>"  name="myval" style="width:80px">
										<tr class="gradeX">
											<td align="center"><?php  echo $j; ?></td>
											<td align="center">
												<?php if($equipmentList[$i]['equipement']) echo $equipmentList[$i]['equipement']; else echo "&nbsp;"; ?>
											</td>
											<td align="center">
												<input type="text" name="descValue" value="<?php echo $des;?>" class="form-control">
											</td>
											<td align="center">
												<input type="text" name="capacity" value="<?php echo $cap;?>" class="form-control">
											</td>
											<td align="center">
												<input type="submit" name="submit" value="Update" class="mb-xs mt-xs mr-xs btn btn-success">
											</td>
										</tr>
										</form>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</section>
						
						
						
						
					<!-- end: page -->
				</section>
			</div>