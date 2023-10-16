<script>
	
	function validate(){
		var rot_no = document.getElementById("rot_no").value;
		var bl_no = document.getElementById("bl_no").value;		
		if(rot_no=="")
		{
			alert("Please select rotation number");
			return false;
		}
		else if(bl_no=="")
		{
			alert("Please select BL number");
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function cnfrmDelete() 
	{
	  if(confirm("Do you want to Delete ?"))
	  {
		  return true;
	  }
	  else
	  {
		  return false;
	  }
	}
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<div class="panel-body">
			<form class="form-horizontal form-bordered" method="POST" 
				action="<?php echo site_url("EDOController/tokenDistributionSearch") ?>" onsubmit="return validate();">
				<div class="form-group">
					<label class="col-md-3 control-label">&nbsp;</label>
					<div class="col-md-6">
						<?php if($org_Type_id=="73") { ?>
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">FF Name <span class="required">*</span></span>
							<select class="form-control" name="ff_ain" id="ff_ain" required>
								<option value="all">--All--</option>
								<?php for($i=0;$i<count($ffList);$i++){ ?>
									<option value="<?php echo $ffList[$i]["AIN_No_New"];?>" 
										<?php if($frmType=="search" and $ff_ain!="all" and $ffList[$i]["id"]==$searchByFF[0]["id"]) { ?> selected <?php } ?>>
										<?php echo $ffList[$i]["Organization_Name"];?>
									</option>
								<?php } ?>
							</select>
						</div>
						<?php } ?>
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Status <span class="required">*</span></span>
							<select class="form-control" name="search_criteria" id="search_criteria" required>
								<option value="all">--All--</option>
								<option value="used" <?php if($frmType=="search" and $search_criteria=="used") { ?> selected <?php } ?>>
									Used
								</option>
								<option value="balance" <?php if($frmType=="search" and $search_criteria=="balance") { ?> selected <?php } ?>>
									Balance
								</option>
							</select>
						</div>
					</div>									
					<div class="row" id="applyBtn">
						<div class="col-sm-12 text-center">
							<button type="submit" name="btnApply" class="mb-xs mt-xs mr-xs btn btn-primary">
								Search
							</button>
						</div>													
					</div>
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php echo $msg; ?>
						</div>													
					</div>						
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row">
	<?php include("mydbPConnection.php"); ?>
	<div class="col-lg-12">						
		<div class="panel-body">
			<section class="panel">
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
					<thead>
						<tr>
							<th>#Sl</th>
							<th class="text-center">Organization</th>
							<th class="text-center">Token Number</th>
							<th class="text-center">Status</th>
							<th class="text-center" <?php if($frmType=="search" and $search_criteria=="balance"){?> style="display:none;" <?php } ?>>
								Reg No
							</th>
							<th class="text-center" <?php if($frmType=="search" and $search_criteria=="balance"){?> style="display:none;" <?php } ?>>
								BL
							</th>
							<th class="text-center" <?php if($frmType=="search" and $search_criteria=="balance"){?> style="display:none;" <?php } ?>>
								Action
							</th>
						</tr>
					</thead>
					
					<tbody>
					<?php 
						for($i=0;$i<count($tokenList);$i++){ 
						$status = "";
						$queryRotBL = "";
						$edoid = "";
						$rot = "";
						$bl = "";
						$blType = "";
						$submitted_by = "";
						$uploadId = "";
						if($tokenList[$i]['used_st']==0)
						{
							$status = "Balance";
						}
						else
						{
							$status = "Used";
							$edoid = $tokenList[$i]['edo_id'];
							$queryEdoData = "SELECT token_distribution.edo_id,edo_application_by_cf.* 
							FROM token_distribution
							INNER JOIN edo_application_by_cf ON token_distribution.edo_id = edo_application_by_cf.id
							WHERE edo_id='$edoid'";
							$resEdoData = mysqli_query($con_cchaportdb,$queryEdoData);
							while($rowEdoData = mysqli_fetch_object($resEdoData)){
								$rot = $rowEdoData->rotation;
								$bl = $rowEdoData->bl;
								$blType = $rowEdoData->bl_type;
								$submitted_by = $rowEdoData->sumitted_by;
							}
							$queryEdoUploadData = "SELECT * FROM shed_mlo_do_info 
													WHERE imp_rot='$rot' AND bl_no='$bl' AND edo_id='$edoid'";
							$resEdoUploadData = mysqli_query($con_cchaportdb,$queryEdoUploadData);
							while($rowEdoUploadData = mysqli_fetch_object($resEdoUploadData)){
								$uploadId = $rowEdoUploadData->id;
							}
						}
					?>
						<tr>
							<td align="center"><?php echo $i+1; ?></td>	
							<td align="center"><?php echo $tokenList[$i]['ff_name']; ?></td>						
							<td align="center"><?php echo $tokenList[$i]['token_number']; ?></td>						
							<td align="center"> <?php echo $status; ?> </td>								
							<td align="center" <?php if($frmType=="search" and $search_criteria=="balance"){?> style="display:none;" <?php } ?>> 
								<?php echo $rot ; ?> 
							</td>								
							<td align="center" <?php if($frmType=="search" and $search_criteria=="balance"){?> style="display:none;" <?php } ?>> 
								<?php echo $bl; ?> 
							</td>								
							<td align="center" <?php if($frmType=="search" and $search_criteria=="balance"){?> style="display:none;" <?php } ?>>
								<?php if($tokenList[$i]['used_st']==1) { ?>
								<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST"> <!--shedDOPDF-->
									<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
									<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rot; ?>"/>
									<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
									<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $blType; ?>"/>
									<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $submitted_by; ?>"/>
									<input class="btn btn-xs btn-primary" type="submit" value="View DO"/>
								</form>
								<?php } ?>
							</td>						
						</tr>
					<?php } ?>
					</tbody>
					
				</table>			
			</section>
		</div>
	</div>
</div>
</section>
</div>