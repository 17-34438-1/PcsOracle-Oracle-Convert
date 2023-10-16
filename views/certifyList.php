<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		</header>
			<?php
				if(!is_null($this->session->flashdata('success'))){
					echo $this->session->flashdata('success');
				}

				if(!is_null($this->session->flashdata('error'))){
					echo $this->session->flashdata('error');
				}

				$org_Type_id =$this->session->userdata('org_Type_id');
				$section = $this->session->userdata('section');
			?>
				<div class="panel-body">
					<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<thead>
							<tr>
								<th class="text-center">Sl</th>
								<th class="text-center">Rotation</th>
								<th class="text-center">BL No</th>
								<th class="text-center">Container Status</th>
								<th class="text-center">C&F Name</th>
								<th class="text-center">Appraise Date</th>
								<th class="text-center">Appraise By</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
                            <?php
                                for($i=0;$i<count($certify);$i++)
                                {
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i+1; ?></td>
                                    <td class="text-center"><?php echo $certify[$i]['rotation_no']; ?></td>
                                    <td class="text-center"><?php echo $certify[$i]['bl_no']; ?></td>
                                    <td class="text-center"><?php echo $certify[$i]['container_status']; ?></td>
                                    <td class="text-center"><?php echo $certify[$i]['cnf_name']; ?></td>
                                    <td class="text-center"><?php echo $certify[$i]['last_update']; ?></td>
                                    <td class="text-center"><?php echo $certify[$i]['update_by']; ?></td>
                                    <td class="text-center">
										<div class="col-md-12">
											<div class="col-md-6">
												<form method="post" action="<?php echo site_url("ReleaseOrderController/lclAssignmentCertifyList"); ?>">
													<input type="hidden" name="ddl_imp_rot_no" value="<?php echo $certify[$i]['rotation_no']; ?>">
													<input type="hidden" name="ddl_bl_no" value="<?php echo $certify[$i]['bl_no']; ?>">
													<input type="hidden" name="action" value="edit">
													<button type="submit" class="btn btn-warning btn-xs">Edit</button>
												</form>
											</div>

											<div class="col-md-6">
												<form method="post" action="<?php echo site_url("ReleaseOrderController/CertifyDelete"); ?>">
													<input type="hidden" name="id" value="<?php echo $certify[$i]['id']; ?>">
													<input type="hidden" name="ddl_imp_rot_no" value="<?php echo $certify[$i]['rotation_no']; ?>">
													<input type="hidden" name="ddl_bl_no" value="<?php echo $certify[$i]['bl_no']; ?>">
													<input type="hidden" name="status" value="<?php echo $certify[$i]['container_status']; ?>">
													<button type="submit" class="btn btn-danger btn-xs">Delete</button>
												</form>
											</div>
										</div>
									</td>
                                </tr>
                            <?php
                                }
                            ?>
						</tbody>
					</table>
				</div>
			</section>
	</section>
</div>