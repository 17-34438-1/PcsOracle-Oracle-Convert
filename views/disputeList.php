<script>
	function chkConfirm()
	{
		if (confirm("Do you want to delete ?") == true)
			{
				return true ;
			}
		else
			{
				return false;
			}
	}
	function setValOnModal(did,vid,qty,pck,rmrks)
	{
		//alert(vid);
		//alert(qty);
		document.getElementById("disputeid").value = did; 
		document.getElementById("visitid").value = vid; 
		document.getElementById("qty").value=qty;
		document.getElementById("pack").value=pck;
		document.getElementById("remarks").value=rmrks;
		//document.getElementById("mySelect").value = "banana";
	}
</script>
<?php include("mydbPConnection.php"); ?>
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
						<!--header class="panel-heading">
							<h2 class="panel-title" align="right">
								<a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a>
							</h2>								
						</header-->
						<div class="modal fade" id="dispute" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<form action="<?php echo site_url('ShedBillController/dusputeList')?>" method="post">
										<input type="hidden" name="updateDispute" id="updateDispute" value="updateDispute">
										<input type="hidden" name="disputeid" id="disputeid">
									<div class="modal-header">
										<div class="modal-title" id="exampleModalLabel" style="color: #0dce0f; font-size:18px; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase;">
											<img style="margin-top: 10px;margin-left:42%;" src="<?php echo ASSETS_WEB_PATH?>fimg/logocpa.png" height="50px" width="50px" alt="Logo"><br/>
											<span style="margin-left:30%;">Port Community System</span>
											<button style="padding-top: 5px;" type="button" class="close" data-dismiss="modal"> X </button>
										</div>
									</div>

									<div class="modal-body">
										<div class="row">
											<div class="col-md-8 col-md-offset-2">
												<div class="input-group mb-md">
													<!--span class="input-group-addon span_width">Visit Id : </span-->
													<input type="hidden" name="visitid" id="visitid" class="form-control">
												</div>												
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Quantity : </span>
													<input type="text" name="qty" id="qty" class="form-control" required>
												</div>
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Pack : </span>
													<!--input type="text" name="pack" id="pack" class="form-control"-->
													<select class="form-control" name="pack" id="pack" required>
														<option value="">--Select--</option>
														<?php for($i=0;$i<count($packList);$i++){ ?>
															<option value="<?php echo $packList[$i]["id"];?>">
																<?php echo $packList[$i]["Pack_Unit"];?>
															</option>
														<?php } ?>
													</select>
												</div>												
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Remarks : </span>
													<input type="text" name="remarks" id="remarks" class="form-control">
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<div class="row">
											<div class="col-sm-6 text-right">
												<input type="submit" name="submit_login" class="btn btn-success" value="Submit" >
											</div>
											<div class="col-sm-6">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
									</form>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-offset-4 col-md-4">
									<?php echo $msg;?>
								</div>
							</div>
							<table class="table table-bordered mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">#Sl</th>
										<th>Visit ID</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Pack Unit</th>
										<th class="text-center">Remarks</th>
										<th class="text-center">Dispute by</th>						
										<th class="text-center">Action</th>						
										<th class="text-center">Action</th>						
									</tr>
								</thead>
								<tbody>
									<?php 
										$did = "";
										$vid = "";
										$quantity = "";
										$pack_unit = "";
										$remarks = "";
										$loadby = "";
										$disputeby = "";
										for($i=0;$i<count($disputeList);$i++){
											$did = $disputeList[$i]['id'];
											$vid = $disputeList[$i]['tr_visit_id'];
											$quantity = $disputeList[$i]['qty'];
											$pack_unit = $disputeList[$i]['pack_unit'];
											$remarks = $disputeList[$i]['remarks'];
									?>
									<tr>
										<td align="center" style="height:5%;"><?php echo $i+1;?></td>
										<td align="center" style="height:5%;"><?php echo $disputeList[$i]['tr_visit_id'];?></td>
										<td align="center" style="height:5%;"><?php echo $disputeList[$i]['qty'];?></td>
										<td align="center" style="height:5%;"><?php echo $disputeList[$i]['pckUnit'];?></td>
										<td align="center" style="height:5%;"><?php echo $disputeList[$i]['remarks'];?></td>
										<td align="center" style="height:5%;"><?php echo $disputeList[$i]['dispute_by'];?></td>
										<td align="center" style="height:5%;">
											<?php
												$sqlDisputeBy = "SELECT dispute_by FROM loading_dispute WHERE tr_visit_id='$vid'";
												$resDisputeBy = mysqli_query($con_cchaportdb,$sqlDisputeBy);
												while($rowDisputeBy = mysqli_fetch_object($resDisputeBy))
												{
													$disputeby = $rowDisputeBy->dispute_by;
												}
												if($disputeby==$login_id) { 
												
											?>											
											<input type="hidden" name="update" id="update" value="Update Dispute">
											<a data-toggle="modal" data-target="#dispute">
												<input type="submit" value="Update Dispute" class="btn btn-sm btn-primary" 
													style="height:2%;" onclick="setValOnModal(<?php echo $did; ?>,<?php echo $vid; ?>,
													'<?php echo $quantity; ?>','<?php echo $pack_unit; ?>',
														'<?php echo $remarks; ?>');">
											</a>
												<?php } ?>
										</td>
										<td align="center" style="height:5%;">
											<?php
												$sqlLoadBy = "SELECT load_by FROM do_truck_details_entry WHERE id='$vid'";
												$resLoadBy = mysqli_query($con_cchaportdb,$sqlLoadBy);
												while($row = mysqli_fetch_object($resLoadBy))
												{
													$loadby = $row->load_by;
												}
												
												if($loadby==$login_id) { 
											?>
											<form action="<?php echo site_url("ShedBillController/truckSearchByCont");?>" method="post">
												<input type="hidden" name="UpdateLoading" id="UpdateLoading" value="UpdateLoading">
												<input type="hidden" name="visitId" id="visitId" value="<?php echo $vid; ?>">
												<input type="submit" value="Update Loading" class="btn btn-sm btn-primary" 
														style="height:2%;">
											</form>
											
											<?php } ?>
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