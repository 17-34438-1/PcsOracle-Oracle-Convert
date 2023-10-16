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
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/equipmentEntryFormPerform') ?>">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Workshop Zone</span>
												<select name="zone" id="zone" class="form-control" onchange="showValue(this.value)">
													<option value="">--Select--</option>
													<?php if($editFlag==1){?> 
													<option value="<?php echo $select_result[0]['workshop_zone']; ?>" selected="true"><?php echo $select_result[0]['workshop_zone']; ?></option>
													<?php }  ?> 
													<option value="AB">AB</option>
													<option value="C">C</option>
													<option value="D">D</option>	
													<option value="PICT">PICT</option>	
												</select> 
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Equipment</span>
												<select name="equipment" id="equipment" class="form-control" onchange="showValue(this.value)">
													<option value="">--Select--</option>
													<?php if($editFlag==1){?> 
													<option value="<?php echo $select_result[0]['equipment']; ?>" selected="true"><?php echo $select_result[0]['equipment']; ?></option>
													<?php }  ?> 
													<option value="SC">SC</option>
													<option value="QGC">QGC</option>
													<option value="RTG">RTG</option>
													<option value="MHC">MHC</option>
													<option value="RMG">RMG</option>
													<option value="RST Loaded 45 Ton">RST Loaded 45 Ton</option>
													<option value="FLT 42 Ton">FLT 42 Ton</option>
													<option value="FLT 16 Ton">FLT 16 Ton</option>
													<option value="RST 7 Ton">RST 7 Ton</option>
													<option value="FLT 10 Ton">FLT 10 Ton</option>
													<option value="CM">CM</option>  
												</select> 
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Total Equipment</span>
												<input type="text" name="total_equip" id="total_equip" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['equip_num']; ?>" <?php }?>>
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Supply</span>
												<input type="text" name="total_supply" id="total_supply" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['equip_supply']; ?>" <?php }?> >
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Out of Order</span>
												<input type="text" name="nonOperational" id="nonOperational" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['non_operational']; ?>" <?php }?>>
												<input type="hidden" id="equipID" name="equipID" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['id']; }?>">
											</div>
										</div>
										<div class="col-sm-12 text-center">
											 <?php 
											if($editFlag==1)
											{
											?>
											<!--button type="submit" name="update" class="mb-xs mt-xs mr-xs btn btn-primary">Update</button-->
											<input class="mb-xs mt-xs mr-xs btn btn-primary" name="update" type="submit" value="UPDATE" >
											<?php 
											} 
											else
											{ 
											?>
											<!--button type="submit" name="save" class="mb-xs mt-xs mr-xs btn btn-primary">Save</button-->
											<input class="mb-xs mt-xs mr-xs btn btn-success" name="save" type="submit" value="SAVE" > 
											<?php 
											} 
											?>
											<input name="go_to_dashboard" id="go_to_dashboard" type="submit" value="Go To Dashboard" class="mb-xs mt-xs mr-xs btn btn-warning" />
										</div>													
									</div>
									<div class="form-group">
									</div>
								</form>
								<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
									<thead>
										<tr>
											<th class="text-center">Sl</th>
											<th class="text-center">Workshop</th>
											<th class="text-center">Equipment</th>
											<th class="text-center">Total Equipment</th>
											<th class="text-center">Supply</th>	
											<th class="text-center">Out of Order</th>	
											<th class="text-center">Edit</th>	
											<th class="text-center">Delete</th>	
										</tr>
									</thead>
									<tbody>
										<?php for($i=0;$i<count($result);$i++) { ?>
										<tr class="gradeX">
											<td align="center"> <?php echo $i+1;?> </td>
											<td align="center"> <?php echo $result[$i]['workshop_zone']?> </td>
											<td align="center"> <?php echo $result[$i]['equipment']?> </td>
											<td align="center"> <?php echo $result[$i]['equip_num']?> </td>
											<td align="center"> <?php echo $result[$i]['equip_supply']?> </td>
											<td align="center"> <?php echo $result[$i]['non_operational']?> </td>
											<td align="center">
												<form action="<?php echo site_url('report/equipmentEntryFormEdit');?>" method="POST">
													<input type="hidden" id="eqiID" name="eqiID" value="<?php echo $result[$i]['id'];?>">							
													<input type="submit" value="Edit"  class="mb-xs mt-xs mr-xs btn btn-success">							
												</form>
											</td>
											<td align="center">
												<form action="<?php echo site_url('report/equipmentEntryForm');?>" method="POST" onsubmit="return validate();">
													<input type="hidden" id="eid" name="eid" value="<?php echo $result[$i]['id'];?>">							
													<input type="submit" value="Delete" name="delete" class="mb-xs mt-xs mr-xs btn btn-danger">
												</form>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</section>
						
						
						
						
					<!-- end: page -->
				</section>
			</div>