<script>
    function validate()
    {
        var contNo = document.getElementById('cont').value.trim();

		if(contNo == "" || contNo == null){
			alert("Field can not be empty!");
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
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/contWiseGatePassPrint") ?>" onsubmit="return validate();">
					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Container No. <span class="required">*</span></span>
								<input type="text" name="cont" id="cont" class="form-control login_input_text" tabindex="1" placeholder="Container No." autofocus>
							</div>												
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<?php echo $msg; ?>
							</div>													
						</div>
																		
						<div class="row">
							<div class="col-sm-12 text-center">
								<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
								<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success" tabindex="2">Search</button>
							</div>													
						</div>						
					</div>	
				</form>
			</div>
		</section>

		<?php 
			if($flag == 1){
		?>
		<section class="panel">
			<div class="panel-body">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th class="text-center">Visit Id</th>
							<th class="text-center">Container No</th>
							<th class="text-center">Rotation No</th>
							<th class="text-center">Truck Id</th>
							<th class="text-center">Driver Name</th>
							<th class="text-center">Payment Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$visitId = "";
							$cont = "";
							$rot = "";
							$truckId = "";
							$driver = "";
							$paid_status = "";

							$i = 0;
							while($i<count($truckResult))
							{
								$visitId = $truckResult[$i]['id'];
								$cont = $truckResult[$i]['cont_no'];
								$rot = $truckResult[$i]['import_rotation'];
								$truckId = $truckResult[$i]['truck_id'];
								$driver = $truckResult[$i]['driver_name'];
								$paid_status = $truckResult[$i]['paid_status'];

								$contStsQuery = "SELECT cont_status FROM(
									SELECT Import_Rotation_No,BL_No,cont_number,cont_status
									FROM igm_supplimentary_detail
									INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
									WHERE igm_supplimentary_detail.Import_Rotation_No='$rot' AND igm_sup_detail_container.cont_number='$cont'
									
									UNION
									
									SELECT Import_Rotation_No,BL_No,cont_number,cont_status
									FROM igm_details
									INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
									WHERE igm_details.Import_Rotation_No='$rot' AND igm_detail_container.cont_number='$cont'
									) AS rtnValue LIMIT 1";

								$contStsRslt = $this->bm->dataSelectDB1($contStsQuery);
								
								$cont_status = "";

								for($j=0;$j<count($contStsRslt);$j++){
									$cont_status = $contStsRslt[$j]['cont_status'];
								}
						?>

						<tr>
							<td class="text-center"><?php echo $visitId;?></td>
							<td class="text-center"><?php echo $cont;?></td>
							<td class="text-center"><?php echo $rot;?></td>
							<td class="text-center"><?php echo $truckId;?></td>
							<td class="text-center"><?php echo $driver;?></td>
							<td class="text-center">
								<?php 
									if($paid_status == 1){
										echo "<font color='green'>paid</font>";
									}else if($paid_status == 0){
								?>

									<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/checkoutbyOnline") ?>">
										<input type="hidden" name="assignmentType" id="assignmentType" value="<?php echo $assignmentType;?>">
										<input type="hidden" name="payAmt" id="payAmt" value="57.5">
										<input type="hidden" name="payVisitId" id="payVisitId" value="<?php echo $visitId;?>">
										<input type="hidden" name="contNo" id="contNo" value="<?php echo $cont;?>">
										<input type="hidden" name="cont_status" id="cont_status" value="<?php echo $cont_status;?>">
										<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rot;?>">
										<font color='red'><b>Not Paid </b></font>
										<button type="submit" name="gatewayType" value="sonali" class="btn btn-warning btn-xs"/>
											Sonaly pay
										</button>

										<button type="submit" name="gatewayType" value="sonali" class="btn btn-primary btn-xs" disabled/>
											Ekpay
										</button>
									</form>

									<!-- <form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/checkoutbyOnline") ?>">
										<input type="hidden" name="assignmentType" id="assignmentType" value="<?php echo $assignmentType;?>">
										<input type="hidden" name="payAmt" id="payAmt" value="57.5">
										<input type="hidden" name="payVisitId" id="payVisitId" value="<?php echo $visitId;?>">
										<input type="hidden" name="contNo" id="contNo" value="<?php echo $cont;?>">
										<input type="hidden" name="cont_status" id="cont_status" value="<?php echo $cont_status;?>">
										<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rot;?>">
										<button type="submit" name="gatewayType" value="sonali" class="btn btn-primary btn-xs" disabled/>
											Ekpay
										</button>
									</form> -->

								<?php
									}
								?>
							</td>
							<td class="text-center"><a href="<?php echo site_url('ShedBillController/gatePassforDriver');?>?visitId=<?php echo $visitId; ?>" target="_blank"><b>Print</b></a></td>
						</tr>

						<?php
							$i++;
							}

							if($i == 0){
						?>
						
						<tr>
							<td colspan="7" class="text-center"><font size='4' color='red'>No Data Found!</font></td>
						</tr>

						<?php
							}
						?>

					</tbody>
				</table>
			</div>
		</section>

		<?php 
			}
		?>
	</div>
</div>
</section>
</div>