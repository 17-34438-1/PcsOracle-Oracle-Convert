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
							<form class="form-horizontal form-bordered" id="myform" method="POST" action="<?php echo site_url('report/equipmentAssignToLabourInsert') ?>">
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
												<option value="<?php echo $labourInfo[$i]['LaborDetailsID']; ?>" <?php if(isset($_POST['edit']) && $labEqipById[0]['laborDtlid'] == $labourInfo[$i]['LaborDetailsID']){ echo "selected"; }else{ echo ""; } ?> ><?php echo $labourInfo[$i]['LaborID']; ?></option>
												<?php } ?>
											</select>
										</div>

                                        <div class="input-group mb-md">
											<span class="input-group-addon span_width">Equipment</span>
											<select  id="equipment" name="equipment" class="form-control" required >
                                                <option value="">--Select--</option>
                                                <?php 
                                                for($i=0; $i<count($equipInfo); $i++)
                                                {  ?> 
                                                <option value="<?php echo $equipInfo[$i]['equipmentid']; ?>" <?php if(isset($_POST['edit']) && $labEqipById[0]['equipmentid'] == $equipInfo[$i]['equipmentid']){ echo "selected"; }else{ echo ""; } ?> ><?php echo $equipInfo[$i]['equipmentid']; ?></option>
                                                <?php } ?>
                                            </select>
										</div>

										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Shift</span>
											<select id="shift" name="shift" class="form-control" value="" required>
											<option value="" <?php if(!isset($_POST['edit'])){ echo "selected";}else{ echo ""; } ?> >--Select--</option>
											<option value="A" <?php if(isset($_POST['edit']) && $labEqipById[0]['labor_shift'] == 'A'){ echo "selected"; }else{ echo ""; } ?> >A</option>
											<option value="B" <?php if(isset($_POST['edit']) && $labEqipById[0]['labor_shift'] == 'B'){ echo "selected"; }else{ echo ""; } ?> >B</option>
											<option value="C" <?php if(isset($_POST['edit']) && $labEqipById[0]['labor_shift'] == 'C'){ echo "selected"; }else{ echo ""; } ?> >C</option>
										</select>
										</div>
                                        
                                        <div class="input-group mb-md">
											<span class="input-group-addon span_width">Start Date</span>
                                            <input type="date" name="fromDate" id="fromDate" class="form-control" value="<?php if(isset($_POST['edit'])){ echo $labEqipById[0]['sdate'];}else{date("Y-m-d");} ?>" required/>
										    <input type="hidden" name="cngKey" id="cngKey" value="<?php if(isset($_POST['edit'])){echo $labEqipById[0]['id'];} ?>"/> 
										</div>

                                        <div class="input-group mb-md">
											<span class="input-group-addon span_width">Start Date</span>
                                            <input type="date" name="toDate" id="toDate" class="form-control" value="<?php if(isset($_POST['edit'])){ echo $labEqipById[0]['edate'];}else{date("Y-m-d");} ?>" required/>
										</div>


									</div>									
									<div class="row">
										<div class="col-sm-12 text-center">
                                            <?php 
                                                if(isset($_POST['edit'])){  ?>
                                                <button type="submit" name="update" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Update</button>
                                                <?php }else{ ?>
                                                <button type="submit" name="save" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
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
										<th class="text-center">Labour ID</th>
										<th class="text-center">Equipment</th>
										<th class="text-center">Shift</th>
										<th class="text-center">Start Date</th>
										<th class="text-center">End Date</th>
										<th class="text-center">Action</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($labEqip);$i++){ ?>
									<tr class="gradeX">
										<td align="center"><?php echo $i+1;?></td>
										<td align="center"><?php echo $labEqip[$i]['LaborID']; ?></td>
										<td align="center"><?php echo $labEqip[$i]['equipmentid']; ?></td>
										<td align="center"><?php echo $labEqip[$i]['labor_shift']; ?></td>
										<td align="center"><?php echo $labEqip[$i]['sdate']; ?></td>
										<td align="center"><?php echo $labEqip[$i]['edate']; ?></td>
										<td align="center">
											<form method="post" action="<?php echo base_url().'index.php/report/equipmentAssignToLabourAction'; ?>">
												<input type="submit" width="70px" class="btn btn-danger" name="edit" id="edit" value="Edit"/>
												<input type="hidden" name="key" id="key" value="<?php echo $labEqip[$i]['id']; ?>"/>
											</form>
										</td>
										<td align="center">
											<form method="post" action="<?php echo base_url().'index.php/report/equipmentAssignToLabourAction'; ?>">
												<input type="submit" width="70px" class="btn btn-success" name="delete" onclick="return validation()" 
												id="delete" value="Delete"/>
												<input type="hidden" name="key" id="key" value="<?php echo $labEqip[$i]['id']; ?>"/>
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