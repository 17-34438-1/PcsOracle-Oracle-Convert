<?php include("mydbPConnectionn4.php");
include("dbOracleConnection.php");
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
                    <table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
                        <thead>
                            <tr class="gridDark">
                                <th class="text-center">#Sl</th>
                                <th class="text-center">Rotation</th>									
                                <th class="text-center">Vessel name</th>
                                <th class="text-center">Service Date</th>		
                                <th class="text-center">Start time</th>								
                                <th class="text-center">Start date</th>	
                                <th class="text-center">Status</th>	
                                <th class="text-center">Placed Time</th>	
								<?php 
								if($this->session->userdata('org_Type_id')==87){
									?>
                                <th class="text-center">Action</th>	
								<?php } ?>								
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $tbl = "";
                                
                           
										
										
							      for($i=0;$i<count($result);$i++){
                                        $rotation=$result[$i]['rotation'];
                                        $query="SELECT *  FROM vsl_vessel_visit_details 
                                        INNER JOIN srv_event ON srv_event.applied_to_gkey=vsl_vessel_visit_details.vvd_gkey
                                        WHERE ib_vyg='$rotation' AND srv_event.event_type_gkey=213";

                                        $queryresult = oci_parse($con_sparcsn4_oracle,$query);
                                        oci_execute($queryresult); 
                                        $res=array();
                                        $countResult = oci_fetch_all($queryresult, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                                        oci_free_statement($queryresult);
                                        $queryresult = oci_parse($con_sparcsn4_oracle,$query);
                                        oci_execute($queryresult); 
                                        $placeTime="";
                                       
                                        if($countResult>0){

                                        while(($row1 = oci_fetch_object($queryresult))!= false)
                                        {
                                            $placeTime=$row1->PLACED_TIME;
                                    
                                        }

                                          

                                        }
                                    ?>
									<tr>
										<td align="center"><?php echo $i+1;  ?></td>
										<td align="center"><?php echo $result[$i]['rotation'];  ?></td>
										<td align="center"><?php echo $result[$i]['vessel_name'];  ?></td>
                                        <td align="center"><?php echo $result[$i]['service_date'];?></td>
										<td align="center"><?php echo $result[$i]['start_time'];  ?></td>
										<td align="center"><?php echo $result[$i]['start_date'];  ?></td>
										 <td align="center">Forwarded</td>
										<td align="center"><?php echo $placeTime; ?></td>
										
										
										<?php 
										if($this->session->userdata('org_Type_id')==87){
										if($result[$i]['director_aprv_st']==0){
											?>
								
								       <td align="center" style="height:5%;">
											<form action="<?php echo site_url("Vessel/updateHotWorkDemand");?>" method="post">
												<input type="hidden" name="update" id="update" value="update">
												<input type="hidden" name="rotation" id="rotation" value="<?php echo $result[$i]['rotation'];?>">
												<input type="submit" value="Update" class="btn btn-sm btn-primary" style="height:2%;">
											</form>
										</td>
								<?php }else{ ?>
								 <td align="center" style="height:5%;">
		
												<input type="submit" value="Update" class="btn btn-sm btn-primary disabled" style="height:2%;">
					
										</td>
								<?php } 
										}
								?>

								
										
									</tr>
                                 <?php } ?>
                           
                        </tbody>
                    </table>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>