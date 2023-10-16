<?php
$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
?>
<section role="main" class="content-body">
			<header class="page-header">
				<h2><?php echo $title; ?></h2>
			</header>

			<!-- start: page -->
			<div class="row">
				
					<section class="panel">
                        <div class="panel-body">
							<div class="row">
								<div class="col-sm-12 text-center mt-md mb-md">
									<div class="ib">
										<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
										<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
									</div>
								</div>
							</div>
						</div>
						<div class="panel-body">
							
									<table class="table table-responsive table-bordered table-hover mb-none" id="datatable-default">
										<thead>
											<tr class="gridDark">
												<th class="text-center">Export Rotation No</th>
												<th class="text-center">Vessel Name</th>									
												<th class="text-center">Master Name</th>									
												<th class="text-center">Agent Code</th>									
												<th class="text-center">Agent Name</th>									
												<th class="text-center">Berth</th>									
												<th class="text-center">ATA</th>
												<th class="text-center">ATD</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											if($ediDetails) {
												$len=count($ediDetails);
												for($i=0;$i<$len;$i++){
												?>
                                            <tr>
												<td align="center"> <?php echo $ediDetails[$i]['Export_Rotation_No']; ?> </td>
												<td align="center"><?php echo $ediDetails[$i]['vsl_name']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['master_name']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['agent_code']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['agent_name']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['bearth']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['ata']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['atd']; ?></td>
											</tr>
											<?php }
											} ?>
                                        </tbody>
									</table>
						</div>
					</section>

				
			</div>
			<!-- end: page -->
		</section>