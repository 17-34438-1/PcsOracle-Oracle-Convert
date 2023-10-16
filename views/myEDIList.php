<?php
	$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
	$login_id=$this->session->userdata('login_id');
?>
<section role="main" class="content-body">
			<header class="page-header">
				<h2><?php echo $title; ?></h2>
			</header>

			<!-- start: page -->
			<div class="row">
				
					<section class="panel">
                        <div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/myEDIListSearch') ?>">
								<input type="hidden" name="frmType" value="">
								<div class="form-group">
									<div class="col-md-offset-3 col-md-6">	
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Search by</span>
											<select name="SearchCriteria" id="SearchCriteria" class="form-control" required>
													<option value="">---Search by---</option>														
													<option value="Export_Rotation_No">Export Rotation No</option>														
													<option value="vsl_name">Vessel Name</option>														
													<option value="voys_no">Voyage No</option>														
													<option value="call_sign">Call Sign</option>														
													<option value="load_port">Load Port</option>														
													<option value="next_port">Next Port</option>														
													<option value="date">Date</option>														
													<option value="create_time">Create Date</option>														
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
						</div>
						<div class="panel-body">
							
									<table class="table table-responsive table-bordered table-hover mb-none" id="datatable-default">
										<thead>
											<tr class="gridDark">
												<th class="text-center">Export Rotation No</th>
												<th class="text-center">Voys</th>									
												<th class="text-center">Call Sign</th>									
												<th class="text-center">Vessel Name</th>									
												<th class="text-center">Date</th>									
												<th class="text-center">Load Port</th>									
												<th class="text-center">Next Port</th>
												<th class="text-center">Create Time</th>
												<th class="text-center">Berth Details</th>
												<th class="text-center">View Details</th>
												<th class="text-center">Download Edi</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											if($ediDetails) {
												$len=count($ediDetails);
												for($i=0;$i<$len;$i++){
													$filename=$login_id."_".str_replace("/","",$ediDetails[$i]['Export_Rotation_No'].".edi");
													// $converted_EDI='http://'.$_SERVER['SERVER_ADDR']."/pcs/resources/uploadfile/xml/".$filename;
													$converted_EDI="http://".$_SERVER['SERVER_NAME']."/pcs/resources/uploadfile/xml/".$filename;
													
												?>
                                            <tr>
												<td align="center"> <?php echo $ediDetails[$i]['Export_Rotation_No']; ?> </td>
												<td align="center"><?php echo $ediDetails[$i]['voys_no']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['call_sign']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['vsl_name']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['date']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['load_port']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['next_port']; ?></td>
												<td align="center"><?php echo $ediDetails[$i]['create_time']; ?></td>
												<td align="center">
													<a href="<?php echo site_url('report/viewBerthDetails/'.str_replace("/","",$ediDetails[$i]['Export_Rotation_No'])) ?>">
														<button type="button" class="btn btn-primary btn-sm"><b><i class="fa fa-eye"></i> View</b></button>
													</a>
												</td>
												<td align="center">
													<a href="<?php echo site_url('report/viewEDIDetails/'.str_replace("/","",$ediDetails[$i]['Export_Rotation_No'])) ?>">
														<button type="button" class="btn btn-success btn-sm"><b><i class="fa fa-eye"></i> View</b></button>
													</a>
												</td>
												<td align="center">
													<a href="<?php echo $converted_EDI; ?>" download target='_blank'>
														<button type="button" class="btn btn-warning btn-sm"><b><i class="fa fa-download"></i> Download</b></button>
													</a>
												</td>
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