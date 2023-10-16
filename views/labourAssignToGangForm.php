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
						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" onsubmit="return validate();"
								action="<?php echo site_url('report/labourAssignToGangInsert') ?>">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<?php
												echo $msg;
											?>
										</div>
										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">LABOUR ID</span>
											<select  id="labourId" name="labourId" class="form-control" required >
												<option value="">--Select--</option>
												<?php 
												for($i=0; $i<count($labourInfo); $i++)
												{  ?> 
												<option value="<?php echo $labourInfo[$i]['LaborDetailsID']; ?>" ><?php echo $labourInfo[$i]['LaborID']; ?></option>
												<?php } ?>
											</select>
										</div>
										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">GANG ID</span>
											<select id="gangId" name="gangId" class="form-control" required >
												<option value="">--Select--</option>
												<?php 
												for($i=0; $i<count($gangList); $i++)
												{  ?> 
												<option value="<?php echo $gangList[$i]['GangInformationID']; ?>" ><?php echo $gangList[$i]['GangID']; ?></option>
												<?php } ?>

											
											</select>
										</div>
										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">START DATE</span>
											<input type="date" name="sDate" id="sDate" class="form-control" value="<?php date("Y-m-d");?>"/>
										</div>
									</div>	
									
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="save" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
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