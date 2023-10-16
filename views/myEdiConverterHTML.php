
		<!-- start: page -->
			<section class="panel">
				<div class="panel-body">
					<div class="invoice">
						<header class="clearfix">
							<div class="row">
								<div class="col-sm-12 text-center mt-md mb-md">
									<div class="ib">
										<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
										<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">
											<?php echo $title ;?>
										</h5>
									</div>
								</div>
							</div>
							<?php if($vsl) {
										$len1=count($vsl);
										$t=0;
										for($i=0;$i<$len1;$i++) { 
										$ibVyg="";
						                
					                    $ibVyg= $vsl[$i]['IB_VYG'];
						
						                $masterQu="select master_name from ctmsmis.mis_vsl_billing_master where ctmsmis.mis_vsl_billing_master.rotation='$ibVyg'";
						                $res_master = $this->bm->dataSelectDb2($masterQu);
										
										?>
							<div class="row">
								<div class="col-md-offset-5 col-sm-6 mt-md">
									<h6 class="h6 mt-none mb-sm text-dark text-bold">Rotaion No: <?php echo $exp_no1 ; ?></h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">Vessel Name: <?php echo $vsl[$i]['VSL_NAME']; ?></h6>
									<?php if(isset($res_master[$t]['master_name'])){?>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">Master Name: <?php echo $res_master[$t]['master_name'];  ?></h6>
									<?php }else{?>
										<h6 class="h6 mt-none mb-sm text-dark text-bold">Master Name: <?php echo "";  ?></h6>
									<?php } ?>
									
									
									<h6 class="h6 mt-none mb-sm text-dark text-bold">Agent Code: <?php echo $vsl[$i]['AGENT_CODE']; ?></h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">Agent Name: <?php echo $vsl[$i]['AGENT_NAME']; ?></h6>
								</div>
							</div>
							<?php 
							$t++;
							} } ?>
						</header>
						<?php if($berth) { ?>
						<div class="panel-body">
							<table class="table table-responsive table-bordered table-hover mb-none">
								<thead>
									<tr class="gridDark">
										<th colspan="3" class="text-center">Berthing Details:</th>
									</tr>
									<tr class="gridDark">
										<th class="text-center">Berth Name</th>
										<th class="text-center">ATA</th>
										<th class="text-center">ATD</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$len=count($berth);
										for($j=0;$j<$len;$j++){ ?>
											<tr class="gradeX">
												<td align="center"> <?php echo $berth[$j]['BERTH']; ?> </td>
												<td align="center"> <?php echo $berth[$j]['ATA']; ?> </td>
												<td align="center"> <?php echo $berth[$j]['ATD']; ?> </td>
											</tr>
										<?php } ?>
								</tbody>
							</table>
						</div>
						<?php } ?>
					</div>
					<form action="<?php echo site_url('Report/EDIFileConverter');?>" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="exp_no" id="exp_no" value="<?php echo $exp_no1 ;?>" />
						<div class="form-group">
							<div class="col-md-offset-3 col-md-5">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Select Your Excel File:: </span>
									<input type="file" name="fileToUpload" id="fileToUpload" class="form-control">
								</div>											
							</div>
							<div class="col-md-2">		
								<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Upload</button>											
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								
							</div>
						</div>
					</form>
				</div>
			</section>
			<!-- end: page -->
	</div>