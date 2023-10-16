		
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
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold"><?php echo $title;?></h5>
									</div>
								</div>
							</div>
						</header>
						<div class="panel-body">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr class="gridDark">
										<th class="text-center">Sl</th>
										<th class="text-center">Rotation</th>									
										<th class="text-center">Agent</th>									
										<th class="text-center">Container No.</th>									
										<th class="text-center">MLO</th>									
										<th class="text-center">ISO Type</th>									
										<th class="text-center">Size</th>										
										<th class="text-center">Height</th>										
										<th class="text-center">Form</th>										
										<th class="text-center">Present State</th>										
									</tr>
								</thead>
								<tbody>
									<?php 
										include("FrontEnd/dbConection.php");
										$contStr = "";
										for($i=0;$i<count($preAddContList);$i++) {
									?>
											<tr class="gradeX">
												<td align="center"> <?php echo $i+1; ?> </td>
												<td align="center"><?php echo $preAddContList[$i]['rotation']; ?></td>
												<td align="center"><?php echo $preAddContList[$i]['agent']; ?></td>
												<td align="center"><?php echo $preAddContList[$i]['cont_id']; ?></td>
												<td align="center"><?php echo $preAddContList[$i]['cont_mlo']; ?></td>
												<td align="center"><?php echo $preAddContList[$i]['isoType']; ?></td>
												<td align="center"><?php echo $preAddContList[$i]['cont_size']; ?></td>
												<td align="center"><?php echo $preAddContList[$i]['cont_height']; ?></td>
												<td align="center"><?php echo $preAddContList[$i]['transOp']; ?></td>
												<td align="center">
													<?php 
														if($i+1==10)
															$contStr = $contStr.$preAddContList[$i]['cont_id'].",<br>";
														else
															$contStr = $contStr.$preAddContList[$i]['cont_id'].", ";
														$cont =$preAddContList[$i]['cont_id'];
														$str = "select sparcsn4.inv_unit_fcy_visit.transit_state from sparcsn4.inv_unit 
														inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
														where sparcsn4.inv_unit.id='$cont' order by sparcsn4.inv_unit_fcy_visit.gkey";
														//echo $str;
														$result = mysqli_query($con_sparcsn4,$str);
														$trans = "";
														while($row = mysqli_fetch_object($result))
														{
															$trans = $row->transit_state;
														}
														$transPart = explode("_", $trans);
														echo $transPart[1]; 
													?>
												</td>
											</tr>
									<?php } ?>
											<tr class="gradeX">
												<td colspan="10" align="center"><?php echo $contStr;?></td>
											</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</section>
			<!-- end: page -->
	</div>

