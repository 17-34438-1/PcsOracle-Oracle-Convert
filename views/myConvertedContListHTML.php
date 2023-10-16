		
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
										<th class="text-center">Category</th>										
										<th class="text-center">Present State</th>										
									</tr>
								</thead>
								<tbody>
									<?php 
										include("FrontEnd/dbConection.php");
										$str ="select vvd_gkey,rotation,agent,cont_id,cont_mlo,isoType,cont_size,cont_height,transOp
										from ctmsmis.mis_exp_unit_preadv_req 
										where preAddStat=1 and date(last_update)=date(now()) and vvd_gkey=$vvdGkey";
										$res =mysqli_query($con_sparcsn4,$str);
										$contStr = "";
										$i=0;
										$j=0;
										$no= 0;
										while($row=mysqli_fetch_object($res)) 
										{
											$cont =$row->cont_id;
											$strTrans = "select sparcsn4.inv_unit_fcy_visit.transit_state,sparcsn4.inv_unit.category from sparcsn4.inv_unit 
											inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
											where sparcsn4.inv_unit.id='$cont' order by sparcsn4.inv_unit_fcy_visit.gkey";
											$resultTrans = mysqli_query($con_sparcsn4,$strTrans);
											$trans = "";
											$cat="";
											while($rowTrans = mysqli_fetch_object($resultTrans))
												{
													$trans = $rowTrans->transit_state;
													$cat = $rowTrans->category;
												}
											$transPart = explode("_", $trans);
											if($trans=="S40_YARD" or $trans=="S50_ECOUT")
											{
												$no = 1;
												$i=$i+1;
											}
											else if($cat=="EXPRT" and ($trans=="S60_LOADED" or $trans=="S70_DEPARTED" or $trans=="S99_RETIRED"))
											{
												$no = 1;
												$i=$i+1;
											}
											else if(($cat=="IMPRT" or $cat=="STRGE") and $trans=="S20_INBOUND")
											{
												$no = 1;
												$i=$i+1;
											}
											else
											{
												if($i==10)
													$contStr = $contStr.$row->cont_id.",<br>";
												else
													$contStr = $contStr.$row->cont_id.", ";
													
												$no = 0;
												$j=$j+1;
											}
											if($no==0)
											{
									?>
											<tr class="gradeX">
												<td align="center"> <?php echo $j; ?> </td>
												<td align="center"><?php echo $row->rotation; ?></td>
												<td align="center"><?php echo $row->agent; ?></td>
												<td align="center"><?php echo $row->cont_id; ?></td>
												<td align="center"><?php echo $row->cont_mlo; ?></td>
												<td align="center"><?php echo $row->isoType; ?></td>
												<td align="center"><?php echo $row->cont_size; ?></td>
												<td align="center"><?php echo $row->cont_height; ?></td>
												<td align="center"><?php echo $row->transOp; ?></td>
												<td align="center"> <?php echo $cat; ?> </td>
												<td align="center"> <?php echo $transPart[1]; ?> </td>
											</tr>
										<?php } } ?>
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

