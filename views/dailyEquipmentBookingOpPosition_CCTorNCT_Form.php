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
	
	function chkSearchInput()
	{				
		if(document.getElementById('opDate').value=="")
		{
			alert("Please provide date.");
			return false;
		}
		else if(document.getElementById('opShift').value=="")
		{
			alert("Please provide shift.");
			return false;
		}
		else
		{
			return true;
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
						
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/dailyEquipmentBookingOpPosition_CCTorNCT_Action') ?>">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Date</span>
													<input type="date" name="eqpdate" id="date" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['date']; ?>" <?php }?>>
												</div>
										</div>
										<div class="col-md-7 col-md-offset-3">
											<div class="row">
												<div class=" col-md-6">
													<div class="input-group mb-md">
														<span class="input-group-addon span_width">Terminal</span>
														<select name="terminal" id="terminal">	
														<option value="">----Select----</option>
													
														<option value="CCT"  <?php if($editFlag==1){ if($select_result[0]['terminal']=="CCT"){?> selected="true" <?php } } ?>>CCT</option>
														<option value="NCT" <?php if($editFlag==1){ if($select_result[0]['terminal']=="NCT"){?> selected="true" <?php } } ?>>NCT</option>
														</select>	
													</div>
												</div>
												<div class="col-md-6">
													<div class="input-group mb-md">
														<span class="input-group-addon">Shift</span>
														<select name="shift" id="shift">  
														<option value="">--------Select--------</option>
													<!-- 													
														<option value="Day"  <?php if($editFlag==1){ if($select_result[0]['shift']=="Day"){?> selected="true" <?php } } ?>>Day</option>
														<option value="Night" <?php if($editFlag==1){ if($select_result[0]['shift']=="Night"){?> selected="true" <?php } } ?>>Night</option>
													-->
														<?php if($editFlag==1){?> 
														<option value="<?php echo $select_result[0]['shift']; ?>" selected="true">Day</option>
														<option value="<?php echo $select_result[0]['shift']; ?>" selected="true">Night</option>

														<?php }  ?>	
													</select>	
													</div>											
												</div>
												
												
											</div>
										</div>

										<div class="col-md-offset-3 col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">EQUIPMENT NO </span>
													<input type="text" name="eguip_no" id="eguip_no" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['eguip_no']; ?>" <?php }?>>
												</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">OPERATOR NAME </span>
													<input type="text" name="op_name" id="op_name" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['op_name']; ?>" <?php }?>>
												</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">YARD </span>
													<input type="text" name="yard_name" id="yard_name" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['yard']; ?>" <?php }?>>
													<input type="hidden" id="equipID" name="equipID" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['id']; }?>">

												</div>
										</div>
			
										<div class="col-sm-12 text-center">
											 <?php 
											if($editFlag==1)
											{
											?>
											
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
										</div>													
									</div>
									
								</form>
							</div>
							<br>
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/dailyEquipmentBookingOpPositionSearchCCTandNCT') ?>" onSubmit="return chkSearchInput()">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Date</span>
												<input type="date" name="opDate" id="opDate" class="form-control" >
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Shift No</span>
												<select name="opShift" id="opShift">
													<option value="">---select---</option>
													<option value="All">All</option>
                                                    <option value="Day">Day</option>
													<option value="Night">Night</option>												
												</select>	
											</div>
										</div>
										<div class="col-sm-12 text-center">											
											<input class="mb-xs mt-xs mr-xs btn btn-success" name="View" type="submit" value="Search" >
										</div>
									</div>
									
								</form>
								<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
									<thead>
										<tr>
											<th class="text-center">Sl</th>
											<th class="text-center">Shift</th>
											<th class="text-center">Equipment</th>
											<th class="text-center">Operator Name</th>
											<th class="text-center">Terminal</th>
											<th class="text-center">Yard</th>	
											<th class="text-center">Date</th>	
											<th class="text-center">Edit</th>	
											<th class="text-center">Delete</th>	
										</tr>
									</thead>
									<tbody>
										<?php for($i=0;$i<count($bookingEquiList);$i++) { ?>
										<tr class="gradeX">
											<td align="center"> <?php echo $i+1;?> </td>
											<td align="center"> <?php echo $bookingEquiList[$i]['shift']?> </td>
											<td align="center"> <?php echo $bookingEquiList[$i]['eguip_no']?> </td>
											<td align="center"> <?php echo $bookingEquiList[$i]['op_name']?> </td>
											<td align="center"> <?php echo $bookingEquiList[$i]['terminal']?> </td>
											<td align="center"> <?php echo $bookingEquiList[$i]['yard']?> </td>
											<td align="center"> <?php echo $bookingEquiList[$i]['date']?> </td>
											<td align="center">
												<form action="<?php echo site_url('report/dailyEquipmentBookingOpPosition_CCTorNCTEdit');?>" method="POST">
													<input type="hidden" id="eqiID" name="eqiID" value="<?php echo $bookingEquiList[$i]['id'];?>">							
													<input type="submit" value="Edit"  class="mb-xs mt-xs mr-xs btn btn-success">							
												</form>
											</td>
											<td align="center">
												<form action="<?php echo site_url('report/dailyEquipmentBookingOpPosition_CCTorNCT_Delete');?>" method="POST" onsubmit="return validate();">
													<input type="hidden" id="eid" name="eid" value="<?php echo $bookingEquiList[$i]['id'];?>">							
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