<script>
    function validate()
      {
        if (confirm("Are you sure!! Delete this record?") == true) {
		   return true ;
	} else {
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
								<form class="form-horizontal form-bordered" method="POST" name="myForm"
									action="<?php echo site_url('report/cargoHandlingEquipmentRemarksPerform') ?>">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Equipment Type</span>
												<select name="equip_type" id="equip_type" class="form-control" onchange="showValue(this.value)">
													<option value="">--Select--</option>
													<?php if($editFlag==1){?> 
													<option value="<?php echo $select_result[0]['equi_type']; ?>" selected="true"><?php echo $select_result[0]['equi_type']; ?></option>
												    <?php }  ?> 
													
													<option value="Crane 50 Ton">Crane 50 Ton</option>
													<option value="Crane 30 Ton">Crane 30 Ton</option>
													<option value="Crane 20 Ton">Crane 20 Ton</option>
													<option value="Crane 10 Ton">Crane 10 Ton</option>
													<option value="FLT 20 Ton">FLT 20 Ton</option>
													<option value="FLT 10 Ton">FLT 10 Ton</option>
													<option value="FLT 05 Ton">FLT 05 Ton</option>
													<option value="FLT 03 Ton">FLT 03 Ton</option>
													<option value="FLT 1.5 Ton">FLT 1.5 Ton</option>
													<option value="RRC 05 Ton">RRC 05 Ton</option>
													<option value="Tractor 25 Ton">Tractor 25 Ton</option>
													<option value="Heavy Trailer 25 Ton">Heavy Trailer 25 Ton</option>
													<option value="Light Trailer 06 Ton">Light Trailer 06 Ton</option>
													<option value="Car Carrier">Car Carrier</option>
													<option value="Tele Handler 04 Ton">Tele Handler 04 Ton</option>
													<option value="Pipe Handler 45 Ton">Pipe Handler 45 Ton</option>
													<option value="Automatic Weighting & Bagging Machine">Automatic Weighting & Bagging Machine</option>
													<option value="Pneumatic Conveyor">Pneumatic Conveyor</option>
													<option value="Variable Reach Stacker 16 Ton">Variable Reach Stacker 16 Ton</option> 		  
												</select> 
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Remarks</span>
												<textarea name="remarks" id="remarks"  rows="3" class="form-control">
													<?php if($editFlag==1){ echo $select_result[0]['remarks']; }?>
												</textarea>
											</div>
										</div>
										
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<input type="hidden" id="equipID" name="equipID" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['id']; }?>">
											</div>
										</div>
										<div class="col-sm-12 text-center">
											<?php if($editFlag==1) { ?>
												<input class="mb-xs mt-xs mr-xs btn btn-warning" name="update" type="submit" value="UPDATE" > 
											<?php } else { ?>
												<input class="mb-xs mt-xs mr-xs btn btn-success" name="save" type="submit" value="SAVE" > 
											<?php } ?> 
										</div>													
									</div>
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<?php echo $msg; ?>
											</div>
										</div>
									</div>
									<div class="form-group">
									</div>
								</form>
								<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
									<thead>
										<tr>
											<th class="text-center">Sl</th>
											<th class="text-center">Type of Equipment</th>
											<th class="text-center">Remarks</th>	
											<th class="text-center">Edit</th>	
											<th class="text-center">Delete</th>
										</tr>
									</thead>
									<tbody>
										<?php
											//  loc_id, location_name, owner_id, full_name, type_id, short_name, prod_user_id, 
											//   company_name, prod_serial, prod_ip, prod_deck_id, prod_rcv_date, prod_rcv_by
											for($i=0;$i<count($result);$i++) { 				
										?>
										<tr class="gradeX">
											<td align="center"> <?php echo $i+1;?> </td>
											<td align="center"> <?php echo $result[$i]['equi_type']?> </td>
											<td align="center">  <?php echo $result[$i]['remarks']?> </td>
											<td align="center">
												<form action="<?php echo site_url('report/cargoHandlingEquipmentRemarksEdit');?>" method="POST">
													<input type="hidden" id="eqiID" name="eqiID" value="<?php echo $result[$i]['id'];?>">							
													<input type="submit" value="Edit"  class="mb-xs mt-xs mr-xs btn btn-warning">							
												</form>
											</td>
											<td align="center"> 
												<form action="<?php echo site_url('report/cargoHandlingEquipmentRemarks');?>" method="POST" onsubmit="return validate();">						
													<input type="hidden" id="eid" name="eid" value="<?php echo $result[$i]['id'];?>">							
													<input type="submit" value="Del." name="delete" class="mb-xs mt-xs mr-xs btn btn-danger">			 						
												</form>  
											</td>
										</tr>
										
										<?php } ?>
										<!--tr class="gradeX">
											<td align="center" colspan="17">
												<?php echo $links?>
											</td>
										</tr-->
									</tbody>
								</table>
							</div>
						</section>
						
						
						
						
					<!-- end: page -->
				</section>
			</div>