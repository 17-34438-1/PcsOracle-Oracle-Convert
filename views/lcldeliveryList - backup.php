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
										<!--td><b>View Appraisement</b></td-->
										<!-- <td ><b>Tally Sheet No</b></td> -->
										<td ><b>Container No</b></td>	
										<td ><b>Bl No</b></td>						
										<td ><b>Rotation</b></td>
                                        <td ><b>Unit</b></td>
										<td ><b>Rcv Pkg</b></td>
										<td ><b>Fault Pack</b></td>
										<td ><b>Loc Fast</b></td>
										<td ><b>Position</b></td>
										<td ><b>Yard/Shed</b></td>
										<td ><b>Unstuffing Date</b></td>
										<td ><b>Report</b></td>
										<!-- <td ><b>Tally Confirm</b></td> -->
									</tr>
                                </thead>
									<?php 
									for($i=0;$i<count($rtnContainerList);$i++)
									{
										$rot = $rtnContainerList[$i]['import_rotation'];
										$cont = $rtnContainerList[$i]['cont_number'];
									?>
									<tr class="gridLight" align="center">
									
										<!--td align="center"> 
											<form action="<?php echo site_url('report/appraisementCertifyList/'.str_replace("/","_",$rtnContainerList[$i]['BL_NO']).'/'.str_replace("/","_",$rtnContainerList[$i]['rotation']))?>" target="_blank" method="POST">						
												<input type="submit" value="View"  class="login_button" style="width:100%;">							
											</form> 
										</td--> 
										<!-- <td style="color:red"><?php echo $rtnContainerList[$i]['tally_sheet_number'];?></td> -->
										<td id="contTdId_<?php echo $i; ?>"><?php echo $rtnContainerList[$i]['cont_number'];?></td>
										<td><?php echo $rtnContainerList[$i]['BL_No'];?></td>
										<td id="rotTdId_<?php echo $i; ?>"><?php echo $rtnContainerList[$i]['import_rotation'];?></td>
                                        <td id="rotTdId_<?php echo $i; ?>"><?php echo $rtnContainerList[$i]['weight_unit'];?></td>
										<td><?php echo $rtnContainerList[$i]['rcv_pack'];?></td>
										<td><?php echo $rtnContainerList[$i]['flt_pack'];?></td>
										<td><?php echo $rtnContainerList[$i]['loc_first'];?></td>
										<td><?php echo $rtnContainerList[$i]['shed_loc'];?></td>
										<td><?php echo $rtnContainerList[$i]['shed_yard'];?></td>
										<td><?php echo $rtnContainerList[$i]['wr_date'];?></td>
                                        <?php
                                            if($org_Type_id == 59){
                                        ?>
										<td>
											<form name="tallyreport" id="tallyreport" action="<?php echo site_url("Report/viewDeliveryInfo");?>" method="post">
												<input type="hidden" name="rotation" id="rotation" value="<?php echo $rtnContainerList[$i]['import_rotation'];?>">
												<input type="hidden" name="blNo" id="blNo" value="<?php echo $rtnContainerList[$i]['BL_No'];?>">
                                                <input type="hidden" name="igm_sup_dtl_id" id="igm_sup_dtl_id" value="<?php echo $rtnContainerList[$i]['igm_sup_detail_id'];?>">
												<button type="submit" name="report" class="login_button btn btn-primary" >Delivery Info Entry</button>
											</form>
										</td>
                                        <?php
                                            }else if($org_Type_id == 2){
                                        ?>
                                        <td>
                                            <form name="tallyreport" id="tallyreport" action="<?php echo site_url("ShedBillController/cnfTruckEntryLCL");?>" method="post">
                                                <input type="hidden" name="cont_status" id="cont_status" value="LCL" />
									            <input type="hidden" name="search" id="search" value="search" />
												<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rtnContainerList[$i]['import_rotation'];?>">
												<input type="hidden" name="blNo" id="blNo" value="<?php echo $rtnContainerList[$i]['BL_No'];?>">
												<button type="submit" name="report" value="Search" class="login_button btn btn-primary" >Truck Entry</button>
											</form>
                                        </td>
                                        <?php
                                            }
                                        ?>
										
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