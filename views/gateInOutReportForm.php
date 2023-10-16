<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<section class="panel">
            <div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/gateInOutReport") ?>">

					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Date <span class="required">*</span></span>
								<input type="date" name="date" id="date" class="form-control login_input_text" tabindex="1" value="<?php echo DATE("Y-m-d");?>">
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
								<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
							</div>													
						</div>						
					</div>	
				</form>
			</div>

            <?php
                if($flag == 1){
            ?>
			<div class="panel-body">
				<table class="table table-bordered table-striped table-responsive">
					<tr> 
						<td colspan="7" align="center" style="font-size:16px;"><b>Truck Gate in out Report<br/>On Date: <?php echo $date; ?></b></td>
					</tr>
					<tr>
						<th>SL</th>
						<th>Gate No</th>
						<th>Total</th>
						<th>In</th>
						<th>Out</th>
						<th>Inside Port</th>
						<th>Gate in Pending</th>
					</tr>

					<?php
						include("mydbPConnection.php");
						$gateQuery = "SELECT DISTINCT(gate_no) AS gtNo FROM do_truck_details_entry";
						$gateResult = mysqli_query($con_cchaportdb,$gateQuery);
                        
						$gate = "";
						$i=0;
						$totalGateIn = 0;
						$totalGateOut = 0;
						
						$truckGrandTotal = 0;
						$grandTotalPending = 0;
						while($row = mysqli_fetch_object($gateResult)){
							$gate = $row->gtNo;
							$i++;

							$dataQuery="SELECT
							(SELECT COUNT(*) 
							FROM do_truck_details_entry WHERE gate_no='$gate' AND date(last_update)='$date') AS totalTruck,
							(SELECT COUNT(*) 
							FROM do_truck_details_entry WHERE gate_in_status= '1' AND gate_no='$gate' AND DATE(gate_in_time) = '$date') AS gateIn,
							(SELECT COUNT(*) 
							FROM do_truck_details_entry WHERE gate_in_status= '1' AND gate_out_status='1' AND gate_no='$gate' AND DATE(gate_out_time) = '$date') AS gateOut,
							(SELECT COUNT(*) 
							FROM do_truck_details_entry WHERE gate_in_status= '0' AND gate_no='$gate' AND DATE(last_update) = '$date') AS gateInPending";

							$dataResult = mysqli_query($con_cchaportdb,$dataQuery);
							
							$gateIn = "";
							$gateOut ="";
							$inside = "";
							while($dataRow = mysqli_fetch_object($dataResult)){
								$totalTruck = $dataRow->totalTruck;
								$gateIn = $dataRow->gateIn;
								$gateOut = $dataRow->gateOut;
								$gateInPending = $dataRow->gateInPending;
								
								$inside = $gateIn - $gateOut;
								$totalGateIn+=$gateIn;
								$totalGateOut+=$gateOut;
								
								$truckGrandTotal+=$totalTruck;
								$grandTotalPending+=$gateInPending;
							}

					?>

					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $gate; ?></td>
						<td><?php echo $totalTruck; ?></td>
						<td><?php echo $gateIn; ?></td>
						<td><?php echo $gateOut; ?></td>
						<td><?php echo $inside; ?></td>
						<td><?php echo $gateInPending; ?></td>
					</tr>

					<?php
						}
					?>

					<tr>
						<th class="text-center" colspan="2">Total</th>
						<td><?php echo $truckGrandTotal;?></td>
						<td><?php echo $totalGateIn;?></td>
						<td><?php echo $totalGateOut;?></td>
						<td><?php echo $totalGateIn - $totalGateOut ;?></td>
						<td><?php echo $grandTotalPending ;?></td>
					</tr>

				</table>

				<div class="text-right mr-lg">
					<form method="POST" action="<?php echo site_url("ShedBillController/gateInOutReport") ?>" target="_blank">
						<input type="hidden" name="date" id="date" value="<?php echo $date; ?>"/>
						<input type="hidden" name="printFlag" id="printFlag" value="printFlag"/>
						<button type="submit" class="btn btn-success"><i class="fa fa-print"></i> Print</button>
					</form>
				</div>
			</div>
            <?php
                }
            ?>
		</section>
	</div>
</div>
</section>
</div>