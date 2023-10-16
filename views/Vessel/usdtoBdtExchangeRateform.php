<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
                        
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Vessel/usdtoBdtExchangeRate'); ?>" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">From <span class="required">*</span></span>
										<input type="text" name="fromLbl" id="fromLbl" class="form-control" value="USD" readonly>
										<input type="hidden" name="from" id="from" class="form-control" value="2">
										<!--select class="form-control" name="from" disabled>											
											<?php
												for($i=0;$i<count($currencies);$i++){
											?>
												<option value="<?= $currencies[$i]['gkey']; ?>" <?php if(isset($flag)){if($currencies[$i]['gkey'] == $rslt[0]['from_currency_gkey']){ echo "selected";}}else if($currencies[$i]['id'] == "USD"){ echo "selected";} ?>><?= $currencies[$i]['id']; ?></option>
											<?php
												}
											?>
										</select-->
									</div>		

									<div class="input-group mb-md">
										<span class="input-group-addon span_width">To <span class="required">*</span></span>
										<input type="text" name="toLbl" id="toLbl" class="form-control" value="BDT" readonly>
										<input type="hidden" name="to" id="to" class="form-control" value="1">
										<!--select class="form-control" name="to" disabled>
											<?php
												for($i=0;$i<count($currencies);$i++){
											?>
												<option value="<?= $currencies[$i]['gkey']; ?>" <?php if(isset($flag)){if($currencies[$i]['gkey'] == $rslt[0]['to_currency_gkey']){ echo "selected";}}else if($currencies[$i]['id'] == "BDT"){ echo "selected";} ?>><?= $currencies[$i]['id']; ?></option>
											<?php
												}
											?>
										</select-->
									</div>	

									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Rate <span class="required">*</span></span>
										<input type="text" name="rate" id="rate" class="form-control" placeholder="rate" value="<?php if(isset($flag)){echo $rslt[0]['rate']; }?>">
									</div>

									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Effective Date (O.A. Date)<span class="required">*</span></span>
										<input type="date" name="date" id="date" class="form-control" value="<?php if(isset($flag)){echo $rslt[0]['effective_date']; } ?>">
										
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Notes <span class="required">*</span></span>
										<input type="text" name="note" id="note" class="form-control" placeholder="note" value="<?php if(isset($flag)){echo $rslt[0]['notes']; }?>">
									</div>										
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php
											if(isset($flag)){
										?>
											<input type="hidden" name="gkey" value="<?= $rslt[0]['gkey']?>">
											<input type="submit" name="store" class="mb-xs mt-xs mr-xs btn btn-success" value="Update">
										<?php
											}else{
										?>
											<input type="submit" name="store" class="mb-xs mt-xs mr-xs btn btn-success" value="Store">
										<?php
											}
										?>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php echo $msg;?>
									</div>
								</div>
							</div>	
						</form>
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>