<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/replaceTruckGateOut") ?>">

					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Truck Visit No. <span class="required">*</span></span>
								<input type="text" name="truckVisitId" id="truckVisitId" class="form-control login_input_text" tabindex="1" placeholder="Truck Visit No.">
							</div>												
						</div>
						<!--div class="row">
							<div class="col-sm-12 text-center">
								<?php echo $msg; ?>
							</div>													
						</div-->
																		
						<div class="row">
							<div class="col-sm-12 text-center">
								<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
								<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
							</div>													
						</div>						
					</div>	
				</form>
            </div>
        </section>
			
            <?php
            if($flag == 1){
                   
                $gate_in_status = "";
                if(count($truck)>0 && count($gateInSts)>0){
                    $gate_in_status = $gateInSts[0]['gate_in_status'];
                }
                
				if(count($truck)>0 && $gate_in_status == 1)
				{
                    $paid_amt = "";
                    $paid_method = "";
                    $driver_name = "";
                    $driver_gate_pass = "";
                    $assistant_gate_pass = "";
                    $assistant_name = "";
                    $truck_id = "";
                    $gate_out_sts = "";

                    if(count($truck)>0){
                        $paid_amt = $truck[0]['paid_amt'];
                        $paid_method = $truck[0]['paid_method'];
                        $driver_name = $truck[0]['driver_name'];
                        $driver_gate_pass = $truck[0]['driver_gate_pass'];
                        $assistant_name = $truck[0]['assistant_name'];
                        $assistant_gate_pass = $truck[0]['assistant_gate_pass'];
                        $truck_id = $truck[0]['truck_id'];
                        $gate_out_sts = $truck[0]['gate_out_status'];
                    }
					
            ?>

		<section class="panel">
            <div class="panel-body">
				<div class="invoice">
					<div class="row">
						<div class="col-sm-12 col-md-12">
                            <h4 class="h4 mt-none mb-sm text-dark"><b><u>Transport Agency Information</b></u></h4>
                            <h6 class="h6 mt-none mb-sm">1. Truck Visit No : <b><?php echo $truckVisitId; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">2. Truck No : <b><?php echo $truck_id; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">3. Driver Name : <b><?php echo $driver_name; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">4. ID Card (Driver) : <b><?php echo $driver_gate_pass; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">5. Assistant Name : <b><?php echo $assistant_name; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">6. ID Card (Assistant) : <b><?php echo $assistant_gate_pass; ?></b></h6>

						</div>

					</div>

                    <div class="row">

						<div class="col-sm-12 text-center">
							<?php
                                if($gate_out_sts == 1){
                            ?>
                                <font color="green"><b>Gate out Process Done!</b></font>

                            <?php
								}else if($gate_in_status == 1){
							?>
                                <form method="POST" action="<?php echo site_url("ShedBillController/replaceTruckGateOutsts") ?>"  onsubmit="return confirm('Are You Sure?');">
                                    <input type="hidden" name="truckVisitId" id="truckVisitId" value="<?php echo $truckVisitId; ?>"/>
                                    <button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary">Gate out</button>
                                </form>
							<?php
								}
							?>
						</div>													
					</div>

				</div>
			</div>
            </div>
        </section>
            <?php
                }else if(count($truck)>0 && $gate_in_status == 0){
            ?>
                <section class="panel">
                    <div class="panel-body">
                        <div class="invoice">
                            <div class="row text-center">
                                <font color="red" size="4">Gate In Not Done Yet!</font>
                            </div>
                        </div>
                    </div>
                </section>
            <?php
                }else{
            ?>
                <section class="panel">
                    <div class="panel-body">
                        <div class="invoice">
                            <div class="row text-center">
                                <font color="red" size="4">No Data Found!</font>
                            </div>
                        </div>
                    </div>
                </section>
            <?php
                }
            }
            ?>
		</section>
	</div>
</div>
</section>
</div>