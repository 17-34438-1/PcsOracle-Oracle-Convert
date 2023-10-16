<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
 <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
		
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
                        <?php
                            if($msg != ""){
                        ?>
                            <div class="panel-body">
                                <div class="col-md-12 text-center">		
                                    <?php echo $msg; ?>
                                </div>
                            </div>
                        <?php
                            }
                        ?>

						<div class="panel-body table-responsive">
							<table  class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
								<thead>
                                    <tr class="gridDark" align="center">
										<td ><b>SL</b></td>	
										<td ><b>Rotation </b></td>						
										<td ><b>BL</b></td>
                                        <td ><b>CP No</b></td>
										<td ><b>C&F License</b></td>
										<td ><b>No. of Truck</b></td>
										<td ><b>Importer Mobile</b></td>
										<td ><b>Delivery Date</b></td>
										<td ><b>Action</b></td>

									</tr>
                                </thead>
									<?php 
                                        $id = "";
                                        $igm_sup_dtl_id = "";
										$rot_no = "";
                                        $bl_no = "";
                                        $cp_no = "";
                                        $cnf_lic_no = "";
                                        $no_of_truck = "";
                                        $importer_mobile_no = "";
                                        $deliveryDt = "";
										
										for($i=0;$i<count($rslt_query);$i++)
										{
											$id = $rslt_query[$i]['id'];
                                            $igm_sup_dtl_id = $rslt_query[$i]['igm_sup_dtl_id'];
											$rot_no = $rslt_query[$i]['rot_no'];
											$bl_no = $rslt_query[$i]['bl_no'];
											$cp_no = $rslt_query[$i]['cp_no'];
											$cnf_lic_no = $rslt_query[$i]['cnf_lic_no'];
											$no_of_truck = $rslt_query[$i]['no_of_truck'];
											$importer_mobile_no = $rslt_query[$i]['importer_mobile_no'];
											$deliveryDt = $rslt_query[$i]['deliveryDt'];
									?>
									<tr class="gridLight" align="center">

										<td><?php echo $i+1;?></td>
										<td><?php echo $rot_no;?></td>
										<td><?php echo $bl_no;?></td>
                                        <td><?php echo $cp_no;?></td>
										<td><?php echo $cnf_lic_no;?></td>
										<td><?php echo $no_of_truck;?></td>
										<td><?php echo $importer_mobile_no;?></td>
										<td><?php echo $deliveryDt;?></td>
										<td>
											<form name="tallyreport" id="tallyreport" action="<?php echo site_url("Report/lcldeliveryAssignmentEdit");?>" method="post">
												<input type="hidden" name="id" id="id" value="<?php echo $id;?>">  
                                                <input type="hidden" name="igm_sup_dtl_id" id="igm_sup_dtl_id" value="<?php echo $igm_sup_dtl_id;?>">
                                                <input type="hidden" name="rot_no" id="rot_no" value="<?php echo $rot_no;?>">
                                                <input type="hidden" name="bl_no" id="bl_no" value="<?php echo $bl_no;?>">
                                                <input type="hidden" name="cp_no" id="cp_no" value="<?php echo $cp_no;?>">
                                                <input type="hidden" name="cnf_lic_no" id="cnf_lic_no" value="<?php echo $cnf_lic_no;?>">
                                                <input type="hidden" name="no_of_truck" id="no_of_truck" value="<?php echo $no_of_truck;?>">
												<button type="submit" name="edit" class="btn btn-primary" >Edit</button>
											</form>
										</td>
									</tr>
									<?php }?>
								</table>
						</div>
					</section>
				</div>
			</div>

		 <!--</div>-->
		 </div>
		 

        </div>
       
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
    </div>
	
  </div>
</section>