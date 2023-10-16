<script>	
	function getSelectedInfo(val)
	{
		// alert("val : "+val);
		var rotNo = document.getElementById('rot_'+val).innerHTML;
		var contNo = document.getElementById('cont_'+val).innerHTML;
		var assignType = document.getElementById('assignType_'+val).innerHTML;
		var size = document.getElementById('size_'+val).innerHTML;
		var block = document.getElementById('block_'+val).innerHTML;
		
		document.getElementById('selectedInfoTable').style.display="block";
		
		document.getElementById('sRot').innerHTML = rotNo;
		document.getElementById('sCont').innerHTML = contNo;
		document.getElementById('sAssignType').innerHTML = assignType;
		document.getElementById('sSize').innerHTML = size;
		document.getElementById('sLoc').innerHTML = block;
	}		
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
	<div class="content">
		<div class="content_resize">
			<?php 
			// if($search==1)
			// {
			?>		
			
			<div class="row">
				<div class="col-md-12">
					<div class="toggle" >
						<label>C&F Info</label>							
						<div class="panel-body table-responsive">
							<div class="panel-body">			
								<form class="form-horizontal" id="searchByCnfIdForm" name="searchByCnfIdForm" method="post" action="<?php echo site_url('ShedBillController/truckEntryByDSForm'); ?>" onsubmit="return chkConfirm()">
																		
									<div class="form-group">
										
										<div class="col-md-2">																
											&nbsp;&nbsp;												
										</div>
										<div class="col-md-6">																
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">AIN No : <span class="required">*</span></span>
												<input class="form-control" name="cnfAinNo" id="cnfAinNo" required />
												<span class="input-group-btn">
												<button type="submit" value="Search" name="searchByCnfId" id="searchByCnfId" class="btn btn-primary" > Search </button>
											</span>
											</div>													
										</div>
										
									</div>	
								</form>
							</div>
						</div>						
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="toggle" >
						<label>Assignment List</label>							
						<div class="panel-body table-responsive">							
							<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
								<tr>
									<td align="center"><b>SL</b></td>
									<td align="center"><b>Rotation</b></td>
									<td align="center"><b>Container</b></td>
									<td align="center"><b>Assignment Type</b></td>
									<td align="center"><b>Size</b></td>									
									<td align="center"><b>Block</b></td>
									<td align="center"><b>Assignment Date</b></td>
									<td align="center"><b>Action</b></td>
								</tr>
								<?php
								for($i=0;$i<count($rslt_assignmentList);$i++)
								{
								?>
								<tr>
									<td id="sl_<?php echo $i; ?>" align="center"><b><?php echo $i+1; ?></b></td>
									<td id="rot_<?php echo $i; ?>" align="center"><b><?php echo $rslt_assignmentList[$i]['rot_no']; ?></b></td>
									<td id="cont_<?php echo $i; ?>" align="center"><b><?php echo $rslt_assignmentList[$i]['cont_no']; ?></b></td>
									<td id="assignType_<?php echo $i; ?>" align="center"><b><?php echo $rslt_assignmentList[$i]['mfdch_value']; ?></b></td>
									<td id="size_<?php echo $i; ?>" align="center"><b><?php echo $rslt_assignmentList[$i]['size']; ?></b></td>
									<td id="block_<?php echo $i; ?>" align="center"><b><?php echo $rslt_assignmentList[$i]['Block_No']; ?></b></td>
									<td id="assignDt_<?php echo $i; ?>" align="center"><b><?php echo $rslt_assignmentList[$i]['assignmentDate']; ?></b></td>
									<td id="action_<?php echo $i; ?>" align="center">
										<input style="width:100px" type="button" name="selectCont_<?php echo $i; ?>" id="selectCont_<?php echo $i; ?>"
										value="Select" class="btn btn-xs btn-primary" onclick="getSelectedInfo(<?php echo $i; ?>)" />										
									</td>
								</tr>
								<?php
								}
								?>								
							</table>
							<br>
							<div align="center">														
								<table id="selectedInfoTable" style="display:none" class="table mb-none">
									<tbody>
										<tr>
											<td colspan="3">Selected Container</td>
										</tr>
										<tr>
											<td>Rotation</td>
											<td>:</td>
											<td id="sRot"></td>
										</tr>
										<tr>
											<td>Container</td>
											<td>:</td>
											<td id="sCont"></td>
										</tr>
										<tr>
											<td>Size</td>
											<td>:</td>
											<td id="sSize"></td>
										</tr>
										<tr>
											<td>Location</td>
											<td>:</td>
											<td id="sLoc"></td>
										</tr>
										<tr>
											<td>Assignment Type</td>
											<td>:</td>
											<td id="sAssignType"></td>
										</tr>
									</tbody>
								</table>
								<table class="table mb-none">									
									<tbody>
										<tr>
											<td>1</td>
											<td>Mark</td>
											<td>Otto</td>
											<td>@mdo</td>
										</tr>
										<tr>
											<td>2</td>
											<td>Jacob</td>
											<td>Thornton</td>
											<td>@fat</td>
										</tr>
										<tr>
											<td>3</td>
											<td>Larry</td>
											<td>the Bird</td>
											<td>@twitter</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			
			
			<!-- common information end -->
												
			<?php 
			// }
			?>	
      
			</div>
      
			<div class="clr"></div>
		</div>

	</div>
  
</section>
