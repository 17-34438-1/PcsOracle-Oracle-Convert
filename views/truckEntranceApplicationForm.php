<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<!-- <section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/truckEntranceApplicationPDF") ?>" target="_blank">

					<div class="form-group">
						<div class="col-md-12">
							<div class="col-md-4">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
									<input type="text" name="rot_no" id="rot_no" class="form-control login_input_text" autofocus= "autofocus" placeholder="Rotation No">
								</div>
							</div>

							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Container <span class="required">*</span></span>
									<input type="text" name="cont_no" id="cont_no" class="form-control login_input_text" tabindex="1" placeholder="Container No">
								</div>												
							</div>

							<div class="col-md-4">
								<div class="input-group mb-md">
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
								</div>												
							</div>

							
						</div>

						
																		
												
					</div>	
				</form>
			</div>
		</section> -->

		<section class="panel">
			<div class="panel-body">
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
					<thead>
						<tr>
							<th rowspan="2"><nobr>Visit ID</nobr></th>
							<th rowspan="2">Rotation</th>
							<th rowspan="2">Cont. No.</th>
							<th rowspan="2"><nobr>Truck ID</nobr></th>
							<th rowspan="2">Gate</th>
							<th rowspan="2">Driver</th>
							<th rowspan="2">D.GatePass</th>
							<th rowspan="2">Assistant</th>
							<th rowspan="2">Ass.GatePass</th>
							<th rowspan="2"><nobr>Gate In Status</nobr></th>
							<th colspan="3" class="text-center">Loading Confirmation</th>
							<th rowspan="2">Token</th>
						</tr>
						<tr>
							<th> Traffic </th>
							<th> Security </th>
							<th> C&F </th>
						</tr>
					</thead>
					<?php
						include("mydbPConnection.php");
						$login_id = $this->session->userdata("login_id");
						// if($login_id == 'devcf'){
						// 	$truckQuery = "SELECT id AS trucVisitId,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass FROM do_truck_details_entry WHERE gate_in_status=0 AND DATE(last_update) = DATE(NOW()) ORDER BY trucVisitId ASC";
						// }else{
						// 	$truckQuery = "SELECT id AS trucVisitId,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass FROM do_truck_details_entry WHERE gate_in_status=0 AND DATE(last_update) = DATE(NOW()) AND update_by = '$login_id' ORDER BY trucVisitId ASC";
						// }

						$truckQuery = "SELECT id AS trucVisitId,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,cnf_chk_st,yard_security_chk_st,traffic_chk_st,gate_in_status,paid_status,load_st,gate_in_time,traffic_chk_time,cnf_chk_time,yard_security_chk_time FROM do_truck_details_entry WHERE gate_out_status=0 AND update_by = '$login_id'";
						
						$truckResult = mysqli_query($con_cchaportdb,$truckQuery);
						
						$imp_rot = "";
						$cont_no = "";
						$truck_id = "";
						$gate_no = "";
						$driver_name = "";
						$driver_gate_pass = "";
						$assistant_name = "";
						$assistant_gate_pass = "";
						$gate_in_status = ""; 
						$paid_status = ""; 
						$load_st = "";
						$trafficConfirm = "";
						$securityConfirm = "";
						$CFConfirm = "";
						$trucVisitId = "";
						$gate_in_time = "";
						$traffic_chk_time= "";
						$cnf_chk_time = "";
						$securityConfirmTime = "";
						$i=0;
						while($row = mysqli_fetch_object($truckResult)){
							$imp_rot = $row->import_rotation;
							$cont_no = $row->cont_no;
							$truck_id = $row->truck_id;
							$gate_no = $row->gate_no;
							$driver_name = $row->driver_name;
							$driver_gate_pass = $row->driver_gate_pass;
							$assistant_name = $row->assistant_name;
							$assistant_gate_pass = $row->assistant_gate_pass;
							$gate_in_status = $row->gate_in_status; 
							$paid_status = $row->paid_status; 
							$load_st = $row->load_st;
							$trafficConfirm = $row->traffic_chk_st;;
							$securityConfirm = $row->yard_security_chk_st;;
							$CFConfirm = $row->cnf_chk_st;;
							$trucVisitId = $row->trucVisitId;
							$gate_in_time = $row->gate_in_time;
							$traffic_chk_time = $row->traffic_chk_time;
							$cnf_chk_time = $row->cnf_chk_time;
							$securityConfirmTime = $row->yard_security_chk_time;
							$i++;
					?>
					
					<tr>
						<td class="text-center" ><?php echo $trucVisitId; ?></td>
						<td class="text-center" ><?php echo $imp_rot; ?></td>
						<td class="text-center" ><?php echo $cont_no; ?></td>
						<td class="text-center" ><?php echo $truck_id; ?></td>
						<td class="text-center" ><?php echo $gate_no; ?></td>
						<td class="text-center" style="font-size:10px;"><nobr><?php echo $driver_name; ?></nobr></td>
						<td class="text-center" ><?php echo $driver_gate_pass; ?></td>
						<td class="text-center" style="font-size:10px;"><nobr><?php echo $assistant_name; ?></nobr></td>
						<td class="text-center"><?php echo $assistant_gate_pass; ?></td>
						<td class="text-center">
							<nobr>
							<?php 
								
								if($gate_in_status == 1){
									echo $gate_in_time;
								}else{
									echo "No";
								}
							?>
							</nobr>
						</td>
						<td class="text-center"><nobr><?php if($trafficConfirm == 1){ echo $traffic_chk_time;}else{ echo "No";} ?></nobr></td>
						<td class="text-center"><nobr><?php if($securityConfirm == 1){ echo $securityConfirmTime;}else{ echo "No";} ?></nobr></td>
						<td class="text-center"><nobr><?php if($CFConfirm == 1){ echo $cnf_chk_time;}else{ echo "No";} ?></nobr></td>
						<td class="text-center">
							<form method="POST" action="<?php echo site_url("ShedBillController/truckEntranceApplicationPDF") ?>" target="_blank">
								<input type="hidden" name="rot_no" id="rot_no" value="<?php echo $imp_rot; ?>"/>
								<input type="hidden" name="cont_no" id="cont_no" value="<?php echo $cont_no; ?>"/>
								<input type="hidden" name="trucVisitId" id="trucVisitId" value="<?php echo $trucVisitId; ?>"/>
								<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" name="" id="" value="Print"/>
							</form>
						</td>
					</tr>
					
					<?php
						}
					?>

				</table>

				<div class="row">
					<div class="col-sm-12 text-center">
						<form method="POST" action="<?php echo site_url("ShedBillController/printAllGatePass") ?>" target="_blank">
							<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-primary">Print All</button>
						</form>
					</div>													
				</div>
			</div>
		</section>
	</div>
</div>
</section>
</div>