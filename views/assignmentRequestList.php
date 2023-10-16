<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">

                    <table class="table table-bordered table-hover table-stripped" id="datatable-default" style="padding:2px;">
                        <caption class="text-center"><font size="4">ASSIGNMENT REQUEST LIST</font></caption>
                        
                        <thead>
                            <tr>
                                <th align="center">Sl</th>
                                <th align="center">Container</th>
                                <th align="center">Reg No.</th>
                                <th align="center">Assignment Type</th>
                                <th align="center">Container Status</th>
                                <th align="center">No. of Truck</th>
                                <th align="center">Yard/Shed</th>
                                <th align="center">Position</th>
                                <th align="center">Size</th>
                                <th align="center">Height</th>
                                <th align="center">Discharge Time</th>
                                <th align="center">Dest.</th>
                                <th align="center">Vessel Name</th>
                                <th align="center">Master BL</th>
                                <th align="center">Equipments</th>
                                <th align="center">Action</th>
                            </tr>
                        </thead>

                        <?php
                            include("mydbPConnection.php"); //cchaportdb
                            include("dbConection.php");	
                            include("mydbPConnectionctmsmis.php");//ctmsmis

                            $id = "";
                            $cont = "";
                            $rot = "";
                            $type = "";
                            $noOfTruck = "";
                            $contType = "";
                            for($i=0;count($requestRslt)>$i;$i++)
                            {
                                $id = $requestRslt[$i]['id'];
                                $cont = $requestRslt[$i]['cont_number'];
                                $rot = $requestRslt[$i]['rotation'];
                                $noOfTruck = $requestRslt[$i]['no_of_truck'];
                                $type = $requestRslt[$i]['mfdch_value'];
                                $contType = $requestRslt[$i]['cont_status'];

                                $sqlBl="select BL_No,igm_details.Import_Rotation_No from igm_details inner join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id where cont_number='$cont' order by igm_detail_container.id desc limit 1";

                                $blRslt=mysqli_query($con_cchaportdb,$sqlBl);
                                $blRslt=mysqli_fetch_object($blRslt);

                                $bl = "";
                                if(count($blRslt)>0){
                                    $bl = $blRslt->BL_No;
                                }
                                
                                $sqlContainer="select igm_details.id,cont_number,igm_details.Import_Rotation_No,(select Vessel_Name from igm_masters 
                                where igm_masters.id=igm_details.IGM_id) as vsl_name,igm_details.BL_No,
                                cont_size,cont_height,off_dock_id,
                                (select Organization_Name from organization_profiles where organization_profiles.id=igm_detail_container.off_dock_id) as offdock_name,
                                cont_status,cont_seal_number,cont_iso_type from igm_detail_container 
                                inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
                                where igm_details.BL_No='$bl' and igm_details.Import_Rotation_No='$rot'
                                union
                                select igm_details.id,cont_number,igm_details.Import_Rotation_No,(select Vessel_Name from igm_masters 
                                where igm_masters.id=igm_supplimentary_detail.igm_master_id) as vsl_name,igm_details.BL_No,
                                cont_size,cont_height,off_dock_id,
                                (select Organization_Name from organization_profiles where organization_profiles.id=igm_sup_detail_container.off_dock_id) as offdock_name,
                                cont_status,cont_seal_number,cont_iso_type from igm_sup_detail_container 
                                inner join igm_supplimentary_detail on igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id 
                                inner join igm_details on igm_details.id=igm_supplimentary_detail.igm_detail_id
                                where igm_supplimentary_detail.BL_No='$bl' and igm_details.Import_Rotation_No='$rot'
                                ";
                                $sqlContainer=mysqli_query($con_cchaportdb,$sqlContainer);
                                $containerRslt=mysqli_fetch_object($sqlContainer);
                                $size = "";
                                $height = "";
                                $vsl_name = "";
                                $off_dock_id = "";
					
                                if(count($containerRslt)>0){
                                    $size = $containerRslt->cont_size;
                                    $height = $containerRslt->cont_height;
                                    $vsl_name = $containerRslt->vsl_name;
                                    $off_dock_id = $containerRslt->off_dock_id;
                                }
								
																
								//previous - mysql
                                // $strYardPositon = "select fcy_time_in,fcy_last_pos_slot,fcy_position_name,yard,fcy_time_out,(select ctmsmis.cont_block(fcy_last_pos_slot,yard)) as block,time_move from (
                                // select time_in as fcy_time_in,last_pos_slot as fcy_last_pos_slot,
								// last_pos_name as fcy_position_name,ctmsmis.cont_yard(last_pos_slot) as yard,
								// time_out as fcy_time_out,time_move 
                                // from inv_unit a
                                    // inner join 
                                // inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=a.gkey
                                        // inner join argo_carrier_visit h ON h.gkey = inv_unit_fcy_visit.actual_ib_cv
                                        // inner join
                                    // argo_visit_details i ON h.cvcvd_gkey = i.gkey
                                        // inner join
                                    // vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey where ib_vyg='$rot' and a.id='$cont'
                                // ) as  tmp";
								
								//new - oralce
								include("dbOracleConnection.php");
								$strYardPositon = "SELECT fcy_time_in,fcy_last_pos_slot,fcy_position_name,fcy_time_out,time_move
									FROM (
										SELECT time_in AS fcy_time_in,last_pos_slot AS fcy_last_pos_slot,last_pos_name AS fcy_position_name,
										time_out AS fcy_time_out,time_move
										FROM inv_unit a
										INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=a.gkey
										INNER JOIN argo_carrier_visit h ON h.gkey = inv_unit_fcy_visit.actual_ib_cv
										INNER JOIN argo_visit_details i ON h.cvcvd_gkey = i.gkey
										INNER JOIN vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey 
										where ib_vyg='$rot' and a.id='$cont'
									)  tmp";
								$sqlYardPosition=oci_parse($con_sparcsn4_oracle,$strYardPositon);
								oci_execute($sqlYardPosition,OCI_DEFAULT);
								
                                //$rtnYardPosition=mysqli_fetch_object($sqlYardPosition);
								
                               
                                $fcy_time_in = "";
                                $fcy_last_pos_slot = "";
                                $fcy_position_name = "";
								$block = "";
								$yard = "";
								while (($rtnYardPosition = oci_fetch_object($sqlYardPosition)) != false){
									$fcy_time_in = $rtnYardPosition->FCY_TIME_IN;
                                    $fcy_last_pos_slot = $rtnYardPosition->FCY_LAST_POS_SLOT;
                                    $fcy_position_name = $rtnYardPosition->FCY_POSITION_NAME;
																		
									$queryYard = "SELECT ctmsmis.cont_yard('$fcy_last_pos_slot') AS Yard_No";
									$resultYard = mysqli_query($con_ctmsmis,$queryYard);
									
									while($resYard = mysqli_fetch_object($resultYard)){
										$yard = $resYard->Yard_No;										
									}
									
									$queryBlock = "SELECT ctmsmis.cont_block('$fcy_last_pos_slot','$yard') AS Block_No";
									$resultBlock = mysqli_query($con_ctmsmis,$queryBlock);
									while($resBlock = mysqli_fetch_object($resultBlock)){
										$block = $resBlock->Block_No;										
									}
									
								}
								oci_close($con_sparcsn4_oracle);                                
                        ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $cont; ?></td>
                            <td><?php echo $rot; ?></td>
                            <td><?php echo $type; ?></td>
                            <td><?php echo $contType; ?></td>
                            <td><?php echo $noOfTruck; ?></td>
                            <td><?php if($yard != "" && $block != ""){echo $yard.", ".$block;}  ?></td>
                            <td>
								<?php 
									if($fcy_time_in==""){
										print($fcy_last_pos_slot."<font color='blue' size='2'><i> On_Vessel</i></font>");
									} else if($fcy_last_pos_slot=="" or strtoupper($fcy_last_pos_slot)=="TIP"){ 
										print($fcy_last_pos_slot." ".$fcy_position_name);
									} else {
										print($fcy_last_pos_slot);
									} 
								?>
							</td>
                            <td><?php print($size); ?></td>
                            <td><?php print($height); ?></td>
                            <td><?php print($fcy_time_in); ?></td>
                            <td><?php echo $off_dock_id; ?></td>
                            <td><?php echo $vsl_name; ?></td>
                            <td><?php echo $bl; ?></td>
                            <td>
                                <?php 
                                    $equipment_Query = "SELECT equipment_name FROM used_equipment 
                                    INNER JOIN equip_for_assignment ON equip_for_assignment.equip_id = used_equipment.equipment_id
                                    WHERE equip_for_assignment.assign_id = '$id'";
                                    $equipment_Rslt = mysqli_query($con_cchaportdb,$equipment_Query);
                                    while($equipments = mysqli_fetch_object($equipment_Rslt))
                                    {
                                        echo "<nobr>".$equipments->equipment_name."</nobr><br>";
                                    }
                                ?>
                            </td>
                            <td align="center">
                                <form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/assignmentEquipmentFormEdit"); ?>">
                                    <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
                                    <input type="submit" class="btn btn-primary" name="edit" id="edit" value="Edit"/>
                                </form>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>