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
							<header class="panel-heading">
								<h6 class="panel-title" align="center">
									<?php echo $msg; ?>								
								</h6>								
							</header>
							<div class="panel-body">
								<!--div class="row">
									<div class="col-sm-12 text-center mt-md mb-md">
										<div class="ib">
											<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
											<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
										</div>
									</div>
								</div-->
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('uploadExcel/equipmentHandlingDemandFormPerform') ?>">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Yard</span>
												<select name="yard" id="yard" class="form-control">
													<?php if($editFlag==1){?> 
														<option value="<?php echo $select_result[0]['yard']; ?>" selected="true">
															<?php echo $select_result[0]['yard']; ?>
														</option>
													<?php }  ?> 
													<option value="">--Select--</option>
													 <option value="JR">JR</option>
													 <option value="AB">AB</option>
													 <option value="DREFFER">DREFFER</option>
													 <option value="Y7">Y7</option>
													 <option value="SCY">SCY</option>
													 <option value="Y1,Y2,YMN">Y1,Y2,YMN</option>
													 <option value="Y3">Y3</option>
													 <option value="Y5">Y5</option>
													 <option value="Y6,Y6X">Y6</option>
													 <option value="Y8B">Y8B</option>
													 <option value="BAPX1">BAPX1</option>
													 <option value="BX2">BX2</option>
													 <option value="BAPX3">BAPX3</option>
													 <option value="Y9,Y10">Y9,Y10</option>
													 <option value="Y11">Y11</option>
													 <option value="NCY">NCY</option>                                                                         
													 <option value="CCT">CCT</option>                                                                         
													 <option value="NCT">NCT</option>                                                                         
													 <option value="ICD">ICD</option>                                                                         
													 <option value="NOFCY">NOFCY</option>					  
												</select> 
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Equipment Type</span>
												<select name="equipment" id="equipment" class="form-control" onchange="showValue(this.value)">
													 <?php if($editFlag==1){?> 
														<option value="<?php echo $select_result[0]['equip_type']; ?>" selected="true">
															<?php echo $select_result[0]['equip_type']; ?>
														</option>
													<?php }  ?> 
													<option value="">--Select--</option>                                                
													<option value="QGC">QGC</option>
													<option value="RTG">RTG</option>
													<option value="MHC">MHC</option>
													<option value="RMG">RMG</option>
													<option value="SC">SC</option>
													<option value="RST 45 Ton(L)">RST 45 Ton(L)</option>
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
												<span class="input-group-addon span_width">Demand</span>
												<input type="number" name="demand" id="demand" class="form-control" placeholder="Demand" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['equip_demand']; ?>" <?php }?>>
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<input type="hidden" name="eqiID" id="eqiID" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['id']; ?>" <?php }?>>
											</div>
										</div>
										<div class="col-sm-12 text-center">
											<?php if($editFlag==1){?>
											<button type="submit" name="update" class="mb-xs mt-xs mr-xs btn btn-primary">Update</button>
											 <?php } else{ ?>
											<button type="submit" name="save" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
											<?php } ?> 
										</div>													
									</div>
								</form>
								<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
									<thead>
										<tr>
											<th class="text-center">SL</th>
											<th class="text-center">Yard</th>
											<th class="text-center">Equipment</th>
											<th class="text-center">Demand</th>	
											<th class="text-center">Edit</th>	
											<th class="text-center">Delete</th>	
										</tr>
									</thead>
									<tbody>
										<?php 
											for($i=0;$i<count($result);$i++) {
										?>
										<tr class="gradeX">
											<td align="center"> <?php echo $i+1;?></td>
											<td align="center"> <?php echo $result[$i]['yard']?> </td>
											<td align="center"> <?php echo $result[$i]['equip_type']?> </td>
											<td align="center"> <?php echo $result[$i]['equip_demand']?> </td>
											<td align="center">
												<form action="<?php echo site_url('uploadExcel/equipmentHandlingDemandFormEdit');?>" method="POST">
														<input type="hidden" id="eqiID" name="eqiID" value="<?php echo $result[$i]['id'];?>">							
														<input type="submit" value="Edit"  class="mb-xs mt-xs mr-xs btn btn-success" style="width:100%;">							
												</form> 
											</td>
											<td align="center">
												<form action="<?php echo site_url('uploadExcel/equipmentHandlingDemandForm');?>" method="POST" onsubmit="return validate();">						
														<input type="hidden" id="eid" name="eid" value="<?php echo $result[$i]['id'];?>">							
														<input type="submit" value="Del." name="delete" class="mb-xs mt-xs mr-xs btn btn-danger" style="width:100%;">			 						
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