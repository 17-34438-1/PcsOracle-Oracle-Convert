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
							<th>Visit ID</th>
							<th>Rotation</th>
							<th>Cont. No.</th>
							<th>Truck ID</th>
							<th>Gate</th>
							<th>Driver Name</th>
							<th>Driver Gate Pass</th>
							<th>Assistant Name</th>
							<th>Assistant Gate Pass</th>
							<th>Token</th>
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

						$truckQuery = "SELECT id AS trucVisitId,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass FROM do_truck_details_entry WHERE gate_in_status=0 AND update_by = '$login_id'";
						
						$truckResult = mysqli_query($con_cchaportdb,$truckQuery);
						
						$imp_rot = "";
						$cont_no = "";
						$truck_id = "";
						$gate_no = "";
						$driver_name = "";
						$driver_gate_pass = "";
						$assistant_name = "";
						$assistant_gate_pass = "";
						$trucVisitId = "";
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
							$trucVisitId = $row->trucVisitId;
							$i++;
					?>
					
					<tr>
						<td><?php echo $trucVisitId; ?></td>
						<td><?php echo $imp_rot; ?></td>
						<td><?php echo $cont_no; ?></td>
						<td><?php echo $truck_id; ?></td>
						<td><?php echo $gate_no; ?></td>
						<td><?php echo $driver_name; ?></td>
						<td><?php echo $driver_gate_pass; ?></td>
						<td><?php echo $assistant_name; ?></td>
						<td><?php echo $assistant_gate_pass; ?></td>
						<td>
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