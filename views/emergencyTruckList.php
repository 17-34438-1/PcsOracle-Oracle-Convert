<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
					<thead>
						<tr>
							<th>SL</th>
							<th>Truck ID</th>
							<th>Gate No</th>
							<th>Driver Name</th>
							<th>Driver Gate Pass</th>
							<th>Assistant Name</th>
							<th>Assistant Gate Pass</th>
							<th>Action</th>
						</tr>
					</thead>
					<?php
						include("mydbPConnection.php");
						$login_id = $this->session->userdata("login_id");

						$emergencyTruckQuery = "SELECT id,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,emrgncy_approve_stat 
						FROM do_truck_details_entry 
						WHERE emrgncy_flag='1'";
						
						$emergencyTruckResult = mysqli_query($con_cchaportdb,$emergencyTruckQuery);
						
                        $id = "";
						$truck_id = "";
						$gate_no = "";
						$driver_name = "";
						$driver_gate_pass = "";
						$assistant_name = "";
                        $assistant_gate_pass = "";
                        $emrgncy_approve_stat = "";
						$i=0;
						while($row = mysqli_fetch_object($emergencyTruckResult))
						{
                            $id = $row->id;
							$truck_id = $row->truck_id;
							$gate_no = $row->gate_no;
							$driver_name = $row->driver_name;
							$driver_gate_pass = $row->driver_gate_pass;
							$assistant_name = $row->assistant_name;
                            $assistant_gate_pass = $row->assistant_gate_pass;
                            $emrgncy_approve_stat = $row->emrgncy_approve_stat;
							$i++;
					?>
					
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $truck_id; ?></td>
						<td><?php echo $gate_no; ?></td>
						<td><?php echo $driver_name; ?></td>
						<td><?php echo $driver_gate_pass; ?></td>
						<td><?php echo $assistant_name; ?></td>
						<td><?php echo $assistant_gate_pass; ?></td>
						<td>
                            <?php
                                if($emrgncy_approve_stat == 0)
								{
                            ?>
                                <form method="POST" action="<?php echo site_url("ShedBillController/emergencyTruckApprove") ?>">
                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
                                    <input type="submit" class="btn btn-xs btn-primary" name="" id="" value="Approve"/>
                                </form>
                            <?php
                                }
								else
								{
                            ?>
                                <font color="#0088cc"><b>Approved!</b></font>
                            <?php
                                }
                            ?>
						</td>
					</tr>
					
					<?php
						}
					?>
				</table>

			</div>
		</section>
	</div>
</div>
</section>
</div>