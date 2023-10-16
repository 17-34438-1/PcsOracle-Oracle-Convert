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
							<a href="<?php echo site_url('report/labourInfoEntryLasherList') ?>">
								<button style="margin-left: 35%" class="btn btn-primary btn-sm">
									<i class="fa fa-list"> </i>
								</button>
							</a>
						</h2>								
					</header>
					<div class="panel-body">
						<form class="form-horizontal form-bordered" name= "myForm" id="myform" method="POST" action="<?php echo site_url('report/labourInfoEntryLasherPerform') ?>" onsubmit="return(validation());">
						
							<div class="form-group">
								<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
										</div>
									</div>
								<div class="col-md-6">
									<div class="input-group mb-md">	
										<input type="hidden" class="read" style="width:150px;"  id="labor_dtl_id" name="labor_dtl_id"   <?php if($editFlag==1){?> value="<?php echo $select_result[0]['LaborDetailsID']; ?>" <?php }?> >
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">LABOUR ID :</span>
										<input type="text" name="labor_id" id="labor_id" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['LaborID']; ?>" <?php }?>>
									</div>
									
									<div class="input-group mb-md">														
										<span class="input-group-addon span_width">CATEGORY :</span>
										<select name="category" id="category" class="form-control" onchange="showValue(this.value)">
											<option value="">--Select--</option>
											<?php if($editFlag==1){?> 
											<option value="<?php echo $select_result[0]['CategoryID']; ?>" selected="true">
												<?php echo $select_result[0]['CategoryName']; ?>
											</option>
											<?php } for($i=0; $i<count($cateList); $i++) { ?> 
											 <option value="<?php echo $cateList[$i]['CategoryID']; ?>" ><?php echo $cateList[$i]['CategoryName']; ?></option>
											<?php } ?>
										</select>														
									</div>
									
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">SKILLS :</span>
										<input type="text" name="skills" id="skills" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['Skilled']; ?>" <?php }?>>
									</div>
									
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">PRESENT ADDRESS :</span>
										<textarea name="pres_addr" id="pres_addr" rows="3" class="form-control" placeholder="Address..."> 
											<?php if($editFlag==1){ echo $select_result[0]['PresentAddress'];  }?>
										</textarea>
									</div>
									
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">DATE OF BIRTH :</span>
										<input type="date" name="date_of_berth" id="date_of_berth" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['DateofBirth']; ?>" <?php }?>>
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">DATE OF JOINING :</span>
										<input type="date" name="date_of_join" id="date_of_join" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['DateofJoining']; ?>" <?php }?>>
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">CONTACT NO :</span>
										<input type="text" name="contact_no" id="contact_no" class="form-control" <?php if($editFlag==1){?> value="<?php echo $select_result[0]['ContactNumber']; ?>" <?php }?>>
									</div>
									
								</div>
								
								
								<div class="col-md-6">
									<div class="input-group mb-md">											
										<input type="hidden" class="read" style="width:150px;"  id="prev_gang_id" name="prev_gang_id"   <?php if($editFlag==1){?> value="<?php echo $select_result[0]['GangInformationID']; ?>" <?php }?> >
									</div>
									
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">LABOUR NAME :</span>
										<input type="text" name="labor_name" id="labor_name" class="form-control"  <?php if($editFlag==1){?> value="<?php echo $select_result[0]['LaborName']; ?>" <?php }?>>
									</div>
									
									<div class="input-group mb-md">														
										<span class="input-group-addon span_width">GANG ID:</span>
										<select name="gang" id="gang" class="form-control" onchange="showValue(this.value)">
											<option value="">--Select--</option>
											<?php if($editFlag==1){?> 
											<option value="<?php echo $select_result[0]['GangInformationID']; ?>" selected="true">
												<?php echo $select_result[0]['GangID']; ?>
											</option>
											<?php } for($i=0; $i<count($gangList); $i++) {  ?> 
											<option value="<?php echo $gangList[$i]['GangInformationID']; ?>" ><?php echo $gangList[$i]['GangID']; ?></option>
											<?php } ?>
										</select>														
									</div>
									
									<div class="input-group mb-md">														
										<span class="input-group-addon span_width">TERMINAL OPERATOR:</span>
										<select name="terminal_op" id="terminal_op" class="form-control" onchange="showValue(this.value)">
											<option value="">--Select--</option>
											<?php if($editFlag==1){?> 
											<option value="<?php echo $select_result[0]['berthname']; ?>" selected="true">
												<?php echo $select_result[0]['berthname']; ?>
											</option>
											<?php } for($i=0; $i<count($berthOpList); $i++) { ?> 
											 <option value="<?php echo $berthOpList[$i]['Organization_Name']; ?>" >
												<?php echo $berthOpList[$i]['Organization_Name']; ?>
											</option>
											<?php } ?>
										</select>														
									</div>
									
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">PERMANENT ADDRESS :</span>
										<textarea name="per_addr" id="per_addr" rows="3" class="form-control" placeholder="Address..."> 
											<?php if($editFlag==1){ echo $select_result[0]['PermanentAddress'];  } ?>
										</textarea>
									</div>
									
									
									
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">REMARKS :</span>
										<textarea name="remarks" id="remarks" rows="5" class="form-control" placeholder="Address..."> 
											<?php if($editFlag==1){ echo $select_result[0]['Remarks'];  }?>
										</textarea>
									</div>
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php if($editFlag==1) { ?>
											<button type="submit" name="update" class="mb-xs mt-xs mr-xs btn btn-success">UPDATE</button>
										<?php } else { ?>
											<button type="submit" name="save" class="mb-xs mt-xs mr-xs btn btn-success">SAVE</button>
										<?php } ?> 
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
		</div>	
	<!-- end: page -->
</section>
</div>