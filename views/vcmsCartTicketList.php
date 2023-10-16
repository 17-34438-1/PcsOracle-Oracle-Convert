<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/vcmsCartTicketList") ?>" >
					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Visit ID <span class="required">*</span></span>
								<input type="text" name="tr_visit_id" id="tr_visit_id" class="form-control login_input_text" autofocus= "autofocus" placeholder="Truck Visit ID">
							</div>
																		
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<?php echo $msg; ?>
							</div>													
						</div>
																		
						<div class="row">
							<div class="col-sm-12 text-center">
								<button type="submit" name="submit_login" value="visitSearch" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
							</div>													
						</div>						
					</div>	
				</form>
			</div>
		</section>
		
		<section class="panel">
			<div class="panel-body table-responsive">
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
					<thead>
						<tr>
							<th class="text-center">SL</th>
							<th class="text-center"><nobr>Visit id</nobr></th>
							<th class="text-center">Rotation</th>
							<th class="text-center">Cont. No.</th>
							<th class="text-center"><nobr>Truck ID</nobr></th>
							<th class="text-center">Gate</th>
							<th class="text-center"><nobr>Driver</nobr></th>
							<th class="text-center"><nobr>Driver Pass</nobr></th>
							<th class="text-center"><nobr>Assistant</nobr></th>
							<th class="text-center"><nobr>Asst. Pass</nobr></th>
							<th class="text-center">Cart</th>
						</tr>
					</thead>
					<?php
						include("mydbPConnection.php");
						
						if($visit_id!="")
						{
							$visitStr="  id='$visit_id'";
						}
						else
						{
							$visitStr=" load_st='1' and gate_out_status='0' AND DATE(last_update) = DATE(NOW()) ";
						}
						$login_id = $this->session->userdata("login_id");
						 $truckQuery = "SELECT id AS trucVisitId,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,
						 gate_in_status,gate_out_status,traffic_chk_st, yard_security_chk_st,cnf_chk_st,
						 (SELECT organization_profiles.License_No FROM organization_profiles 
						INNER JOIN users ON users.org_id=organization_profiles.id 
						WHERE users.login_id= do_truck_details_entry.update_by) AS cnf_lic
						 FROM do_truck_details_entry WHERE  $visitStr ORDER BY trucVisitId DESC";
						$truckResult = mysqli_query($con_cchaportdb,$truckQuery);
						
						$visit_id = "";
						$imp_rot = "";
						$cont_no = "";
						$truck_id = "";
						$gate_no = "";
						$driver_name = "";
						$driver_gate_pass = "";
						$assistant_name = "";
						$assistant_gate_pass = "";
						
						$gate_in_status = "";
						$gate_out_status = "";
						$traffic_chk_st = "";
						$yard_security_chk_st="";
						$cnf_chk_st="";
						$cnf_lic="";
						
						$i=1;
						while($row = mysqli_fetch_object($truckResult)){
							$visit_id = $row->trucVisitId;
							$imp_rot = $row->import_rotation;
							$cont_no = $row->cont_no;
							$truck_id = $row->truck_id;
							$gate_no = $row->gate_no;
							$driver_name = $row->driver_name;
							$driver_gate_pass = $row->driver_gate_pass;
							$assistant_name = $row->assistant_name;
							$assistant_gate_pass = $row->assistant_gate_pass;
							$trucVisitId = $row->trucVisitId;
							
							$gate_in_status = $row->gate_in_status;
							$gate_out_status = $row->gate_out_status;
							$traffic_chk_st = $row->traffic_chk_st;
							$yard_security_chk_st = $row->yard_security_chk_st;
							$cnf_chk_st = $row->cnf_chk_st;
							$cnf_lic = $row->cnf_lic;
					?>

					<tr>
						<td align="center"><?php echo $i; ?></td>
						<td align="center"><?php echo $visit_id; ?></td>
						<td align="center"><?php echo $imp_rot; ?></td>
						<td align="center"><?php echo $cont_no; ?></td>
						<td align="center"><?php echo $truck_id; ?></td>
						<td align="center"><?php echo $gate_no; ?></td>
						<td align="center"><?php echo $driver_name; ?></td>
						<td align="center"><?php echo $driver_gate_pass; ?></td>
						<td align="center"><?php echo $assistant_name; ?></td>
						<td align="center"><?php echo $assistant_gate_pass; ?></td>
						<td class="align-middle text-center">
						<?php

							//check custom block
							$cusBlockSts = $this->bm->chkBlockedContainer($cont_no,$visit_id);

							$cont_blocked_status = "";

							for($blksts = 0;$blksts<count($cusBlockSts);$blksts++){
								$cont_blocked_status = $cusBlockSts[$blksts]['custom_block_st'];
							}

							if($cont_blocked_status == "DO_NOT_RELEASE")
							{
							?>
								<font size="2" color="red"><b>Blocked!!</b></font>
							<?php
							}
							else
							{

								if($traffic_chk_st == '1' && $cnf_chk_st == '1') //&& $yard_security_chk_st == '1'
								{
									//if($gate_out_status == 0){  <!--Comment because history search -->	
								?>
							
							<form method="POST" action="<?php echo site_url("ShedBillController/vcmsCartChalanTicketView") ?>" target="_blank">
								<input type="hidden" name="rot_no" id="rot_no" value="<?php echo $imp_rot; ?>"/>
								<input type="hidden" name="cont_no" id="cont_no" value="<?php echo $cont_no; ?>"/>
								<input type="hidden" name="trucVisitId" id="trucVisitId" value="<?php echo $trucVisitId; ?>"/>
								<input type="hidden" class="btn btn-primary" name="cnf_lic_no" id="cnf_lic_no" value="<?php echo $cnf_lic;?>"/>
								<input type="submit"  class="btn btn-xs btn-primary" name="" id="" value="CartTicket"/>
							</form>
							
						

								<?php
									// }
								}else{

										$trafficMsg = "";
										$securityMsg = "";
										$cnfMsg = "";
										if($traffic_chk_st == 0){
											$trafficMsg = "Traffic,";
										}
										// if($yard_security_chk_st == 0){
										// 	$securityMsg = "Security,";
										// }
										if($cnf_chk_st == 0){
											$cnfMsg = "C&F,";
										}
										$msg = $trafficMsg.$cnfMsg; //$trafficMsg.$securityMsg.$cnfMsg   
										
								?>

										<font color="red" size="1"><b><?php echo substr($msg,0,-1); ?> confirmation is not done yet!!!</b></font>
							<?php
									}
								}
							?>
						</td>
					</tr>

					<?php
						$i++;
						}
					?>

				</table>
			</div>
		</section>
	</div>
</div>
</section>
</div>