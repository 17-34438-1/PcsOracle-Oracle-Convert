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
                        <!--div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/myEDIDetailSearch') ?>">
								<input type="hidden" name="frmType" value="">
								<div class="form-group">
									<div class="col-md-offset-3 col-md-6">	
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Search by</span>
											<select name="SearchCriteria" id="SearchCriteria" class="form-control" required>
													<option value="">---Search by---</option>														
													<option value="container_no">Container No</option>														
													<option value="iso_code">ISO Code</option>														
													<option value="line_op">Line Operator</option>														
													<option value="status">Container Status</option>														
													<option value="seal">Container Seal</option>														
													<option value="imdg">IMDG</option>														
													<option value="unno">UNNO</option>														
													<option value="loasd_port">Load Port</option>														
													<option value="discharge_port">Discharge Port</option>														
													<option value="st">Stowage</option>														
													<option value="Export_Rotation_No">ALL</option>														
											</select>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" for="w4-designation_id">Value</span>
											<input type="text" name="Searchdata" id="SearchID" class="form-control">
											
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
										</div>
									</div>
								</div>	
							</form>
						</div-->
						<div class="panel-body">
							
									<table class="table table-responsive table-bordered table-hover mb-none" id="datatable-default">
										<thead>
											<tr class="gridDark">
												<th class="text-center">Export Rotation No</th>
												<th class="text-center">Container No</th>									
												<th class="text-center">ISO Code</th>									
												<th class="text-center">Line Op</th>									
												<th class="text-center">Status</th>									
												<th class="text-center">Weight</th>									
												<th class="text-center">Seal</th>
												<th class="text-center">IMDG</th>
												<th class="text-center">UNNO</th>
												<th class="text-center">Temparature</th>
												<th class="text-center">Load Port</th>
												<th class="text-center">Discharge Port</th>
												<th class="text-center">Stowage</th>
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
												<td align="center"><?php echo $ediDetails[$i]['container_no']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['iso_code']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['line_op']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['status']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['weight']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['seal']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['imdg']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['unno']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['temp']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['loasd_port']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['discharge_port']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['stowage']; ?></td>
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