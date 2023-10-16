<script language="JavaScript">
    function validate()
    {
        if( document.myform.rotation.value == "" )
        {
        alert( "Please provide Rotation Number!" );
        document.myform.rotation.focus() ;
        return false;
        }
        return true;
    }
	function toggle_removal_status(state,ival)
    {	
		if(state.checked == true)
		{
			document.getElementById("removal_status"+ival).value="1";
		}
		else
		{
			document.getElementById("removal_status"+ival).value="0";
		}
        return false;
    }
	function confirmMsg()
	{
		if (confirm("Do you want to save ?") == true)
			{
				return true ;
			}
		else
			{
				return false;
			}
	}
</script>
<?php include("mydbPConnection.php");?>
<?php include("mydbPConnectionn4.php");?>
<?php include("dbOracleConnection.php");?>


<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

    <div class="row">
        <div class="col-lg-12">						
            <section class="panel">
                <div class="panel-body">
                    <form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/AuctionHandOverReportForm')?>" id="myform" name="myform" onsubmit="return validate()">
						<div class="row">
							<div class="col-sm-12 text-center">
								<?php echo $msg; ?>
							</div>
						</div>
						<div class="form-group">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="row">
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Rotation No :</span>
                                        <input class="form-control" name="rotation" id="rotation" value="<?php if($flag == 1){ echo $rotation;} ?>">
                                        <span class="input-group-btn">
                                            <button type="submit" value="Search" name="action" id="action" class="btn btn-primary"> Search </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>	
                    </form>
                </div>
            </section>

            <?php if($flag == 1){ ?>
            <section class="panel">
                <div class="panel-body" style="padding:0px;">
					<div class="table-responsive">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/AuctionHandOverReportForm')?>" target="<?php if($save_flag=="0") echo "_self"; else echo "_blank";?>" >
                    <table class="table table-bordered">
					
                        <tr>
                            <th colspan="4" class="text-center">CHITTAGONG PORT AUTHORITY</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-center">(Auction Handover)</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-center">COPY TO BE RETURNED TO PARENT SHED</th>
                        </tr>
                        <tr>
                            <th class="text-center">ROT No: <?php echo $rotation; ?></th>
                            <th class="text-center">ARRIVAL DATE : <?php echo $arrival_date; ?></th>
                            <th class="text-center">C/L DATE : <?php echo $cl_date; ?></th>
                            <th class="text-center">
								UNIT NO: <select class="form-control" class="unit" id="unit" name="unit" required>
											<option value="">--Select--</option>
											<option value="U1" <?php if($save_flag=="1" and $unit=="U1") { echo "selected";} ?>>
												U1
											</option>
											<option value="U2" <?php if($save_flag=="1" and $unit=="U2") { echo "selected";} ?>>
												U2
											</option>
											<option value="U3" <?php if($save_flag=="1" and $unit=="U3") { echo "selected";} ?>>
												U3
											</option>
											<option value="U4" <?php if($save_flag=="1" and $unit=="U4") { echo "selected";} ?>>
												U4
											</option>
											<option value="U5" <?php if($save_flag=="1" and $unit=="U5") { echo "selected";} ?>>
												U5
											</option>
											<option value="U6" <?php if($save_flag=="1" and $unit=="U6") { echo "selected";} ?>>
												U6
											</option>
											<option value="U7" <?php if($save_flag=="1" and $unit=="U7") { echo "selected";} ?>>
												U7
											</option>
											<option value="U8" <?php if($save_flag=="1" and $unit=="U8") { echo "selected";} ?>>
												U8
											</option>
											<option value="U9" <?php if($save_flag=="1" and $unit=="U9") { echo "selected";} ?>>
												U9
											</option>
											<option value="U10" <?php if($save_flag=="1" and $unit=="U10") { echo "selected";} ?>>
												U10
											</option>
											<option value="U11" <?php if($save_flag=="1" and $unit=="U11") { echo "selected";} ?>>
												U11
											</option>
											<option value="U12" <?php if($save_flag=="1" and $unit=="U12") { echo "selected";} ?>>
												U12
											</option>  
										</select>
							</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-center">The cargo of  <?php echo $v_name; ?>  Ex. Shed <b> OneStop</b>--transferred to shed No.----------/AUCTION in wagon No. By paper</th>
                        </tr>
                    </table>
					</div>
				</div>
            </section>
			<section class="panel">
                <div class="panel-body" style="padding:0px;">
                    <div class="table-responsive">						
						<table class="table table-bordered mb-none">
							<tr>
								<th rowspan='2' class="text-center">SL No</th>
								<th rowspan='2' class="text-center">Master BL</th>
								<th rowspan='2' class="text-center">House BL</th>
								<th rowspan='2' class="text-center">Marks</th>
								<th rowspan='2' width="200px" class="text-center">Goods Description</th>
								<th rowspan='2' class="text-center">Importer</th>
								<th rowspan='2' class="text-center">Quantity(STC)</th>
								<th rowspan='2' class="text-center">Agent</th>
								<th rowspan='2' class="text-center">RL_No<br/>RL_Date</th>
								<!--th class="text-center">Importer Address</th-->
								
								<th colspan='5' class="text-center">Container Detail</th>
								<th colspan='2' class="text-center">Custom Part</th>
								<!--th class="text-center">RL Date</th-->
								
								<th rowspan='2' class="text-center">Action</th>
								<th rowspan='2' class="text-center">Remarks</th>
							</tr>
							<tr>
								<th class="text-center">Container No</th>
								<th class="text-center">Size</th>
								<th class="text-center">Height</th>
								<th class="text-center">Status</th>
								<th class="text-center">Location</th>
								<th class="text-center">OBPC No</th>
								<th class="text-center">OBPC Date</th>
							</tr>							
							<?php								
								$j = 0;								
								for($i=0;$i<count($resultBL);$i++)
								{
									$blNo = $resultBL[$i]['BL_No'];
									 /*$queryIgmDtls = "SELECT igm_detail_container.cont_number,igm_details.Description_of_Goods,igm_details.Pack_Marks_Number,igm_details.BL_No AS master_bl, igm_supplimentary_detail.BL_No AS house_bl,
									igm_detail_container.cont_status,igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Notify_name, igm_details.Notify_address, 
									igm_detail_container.cont_imo,igm_detail_container.cont_un, igm_details.Pack_Number, igm_details.Pack_Description,igm_detail_container.cont_gross_weight  
									FROM cchaportdb.igm_details 
									INNER JOIN cchaportdb.igm_detail_container ON cchaportdb.igm_detail_container.igm_detail_id=cchaportdb.igm_details.id 
									LEFT JOIN cchaportdb.igm_supplimentary_detail ON cchaportdb.igm_details.id=cchaportdb.igm_supplimentary_detail.igm_sup_detail_id
									WHERE igm_details.BL_No='$blNo' AND igm_detail_container.cont_number IN ($allCont)
									
									UNION
									
									SELECT igm_sup_detail_container.cont_number,igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Pack_Marks_Number, igm_details.BL_No AS master_bl, igm_supplimentary_detail.BL_No AS house_bl,
									igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_height,igm_supplimentary_detail.Notify_name,
									igm_supplimentary_detail.Notify_address, igm_sup_detail_container.cont_imo,igm_sup_detail_container.cont_un, igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description, igm_sup_detail_container.cont_gross_weight
									FROM cchaportdb.igm_supplimentary_detail 
									INNER JOIN cchaportdb.igm_sup_detail_container ON cchaportdb.igm_sup_detail_container.igm_sup_detail_id=cchaportdb.igm_supplimentary_detail.id
									LEFT JOIN cchaportdb.igm_details ON cchaportdb.igm_supplimentary_detail.igm_detail_id=cchaportdb.igm_details.id 
									WHERE igm_supplimentary_detail.BL_No='$blNo' AND igm_sup_detail_container.cont_number IN ($allCont)";*/
									/* $queryIgmDtls = "SELECT igm_detail_container.cont_number,igm_details.Description_of_Goods,igm_details.Pack_Marks_Number,igm_details.BL_No AS master_bl, igm_supplimentary_detail.BL_No AS house_bl,
									igm_detail_container.cont_status,igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Notify_name, igm_details.Notify_address, 
									igm_detail_container.cont_imo,igm_detail_container.cont_un, igm_details.Pack_Number, igm_details.Pack_Description,igm_detail_container.cont_gross_weight  
									FROM cchaportdb.igm_details 
									INNER JOIN cchaportdb.igm_detail_container ON cchaportdb.igm_detail_container.igm_detail_id=cchaportdb.igm_details.id 
									LEFT JOIN cchaportdb.igm_supplimentary_detail ON cchaportdb.igm_details.id=cchaportdb.igm_supplimentary_detail.igm_sup_detail_id
									WHERE igm_details.BL_No='$blNo' AND igm_detail_container.cont_number IN ($allCont)
									
									UNION
									
									SELECT igm_sup_detail_container.cont_number,igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Pack_Marks_Number, igm_details.BL_No AS master_bl, igm_supplimentary_detail.BL_No AS house_bl,
									igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_height,igm_supplimentary_detail.Notify_name,
									igm_supplimentary_detail.Notify_address, igm_sup_detail_container.cont_imo,igm_sup_detail_container.cont_un, igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description, igm_sup_detail_container.cont_gross_weight
									FROM cchaportdb.igm_supplimentary_detail 
									INNER JOIN cchaportdb.igm_sup_detail_container ON cchaportdb.igm_sup_detail_container.igm_sup_detail_id=cchaportdb.igm_supplimentary_detail.id
									LEFT JOIN cchaportdb.igm_details ON cchaportdb.igm_supplimentary_detail.igm_detail_id=cchaportdb.igm_details.id 
									WHERE igm_supplimentary_detail.BL_No='$blNo' AND igm_sup_detail_container.cont_number IN ($allCont)";*/
									$queryIgmDtls = "SELECT igm_detail_container.cont_number,igm_details.Description_of_Goods,igm_details.Pack_Marks_Number,igm_details.BL_No AS master_bl, igm_supplimentary_detail.BL_No AS house_bl,
									igm_detail_container.cont_status,igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Notify_name, igm_details.Notify_address, 
									igm_detail_container.cont_imo,igm_detail_container.cont_un, igm_details.Pack_Number, igm_details.Pack_Description,igm_detail_container.cont_gross_weight  
									FROM cchaportdb.igm_details 
									INNER JOIN cchaportdb.igm_detail_container ON cchaportdb.igm_detail_container.igm_detail_id=cchaportdb.igm_details.id 
									LEFT JOIN cchaportdb.igm_supplimentary_detail ON cchaportdb.igm_details.id=cchaportdb.igm_supplimentary_detail.igm_sup_detail_id
									WHERE igm_details.BL_No='$blNo' AND igm_detail_container.cont_number IN ($allCont)
									
									UNION
									
									SELECT igm_sup_detail_container.cont_number,igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Pack_Marks_Number, igm_details.BL_No AS master_bl, igm_supplimentary_detail.BL_No AS house_bl,
									igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_height,igm_supplimentary_detail.Notify_name,
									igm_supplimentary_detail.Notify_address, igm_sup_detail_container.cont_imo,igm_sup_detail_container.cont_un, igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description, igm_sup_detail_container.cont_gross_weight
									FROM cchaportdb.igm_supplimentary_detail 
									INNER JOIN cchaportdb.igm_sup_detail_container ON cchaportdb.igm_sup_detail_container.igm_sup_detail_id=cchaportdb.igm_supplimentary_detail.id
									LEFT JOIN cchaportdb.igm_details ON cchaportdb.igm_supplimentary_detail.igm_detail_id=cchaportdb.igm_details.id 
									WHERE igm_supplimentary_detail.BL_No='$blNo' AND igm_sup_detail_container.cont_number IN ($allCont)";
								
									$resultIgmDtls = $this->bm->dataSelectDb1($queryIgmDtls);
									$resIgmDtls=mysqli_query($con_cchaportdb,$queryIgmDtls);
									$cntContainers = mysqli_num_rows($resIgmDtls);
									
                                       //echo "<pre>";
									   //print_r($resIgmDtls);
									   //echo "</pre>";
									
									$cont_status = "";
									$pack_Marks_Number = "";
									$description_of_Goods = "";
									$notify_name = "";
									$Pack_Number = "";
									$Pack_Description = "";
									$cont_gross_weight = "";
									$master_bl = "";
									$house_bl = "";
									
									while($rowIgmDtls = mysqli_fetch_object($resIgmDtls)){
									    $cont_status = $rowIgmDtls->cont_status;
										$pack_Marks_Number = $rowIgmDtls->Pack_Marks_Number;
										$description_of_Goods = $rowIgmDtls->Description_of_Goods;
										$notify_name = $rowIgmDtls->Notify_name;
										$Notify_address = $rowIgmDtls->Notify_address;
										$Pack_Number = $rowIgmDtls->Pack_Number;
										$Pack_Description = $rowIgmDtls->Pack_Description;
										$house_bl = $rowIgmDtls->house_bl;
										$master_bl = $rowIgmDtls->master_bl;
									}
									
									$rl_no = "";
									$rl_date = "";
									$queryHandoverData = "SELECT rl_no,rl_date FROM auction_handover WHERE bl_no='$blNo' and rotation_no='$rotation'";
									$resHandoverData=mysqli_query($con_cchaportdb,$queryHandoverData);
									while($rowHandoverData = mysqli_fetch_object($resHandoverData)){
										$rl_no = $rowHandoverData->rl_no;
										$rl_date = $rowHandoverData->rl_date;
									}
									
									$j = $i+1;
							?>
								<tr>
									<td class="text-center" rowspan="<?php echo $cntContainers; ?>"><?php echo $i+1; ?></td>
									<td class="text-center" rowspan="<?php echo $cntContainers; ?>">
										<input type="hidden" class="form-control" name="<?php echo "cntContainers".$j;?>" id="cntContainers"
											value="<?php echo $cntContainers; ?>">
										<input type="hidden" class="form-control" name="<?php echo "blNo".$j;?>" id="blNo" value="<?php echo $master_bl; ?>">
										<?php echo $master_bl; ?>
										
									</td>
									<td class="text-center" rowspan="<?php echo $cntContainers; ?>">
										<input type="hidden" class="form-control" name="<?php echo "cntContainers".$j;?>" id="cntContainers"
											value="<?php echo $cntContainers; ?>">
										<input type="hidden" class="form-control" name="<?php echo "house_bl".$j;?>" id="house_bl" value="<?php echo $house_bl; ?>">
										<?php echo $house_bl; ?>
										
									</td>
									<td class="text-center" rowspan="<?php echo $cntContainers; ?>">
										<!--input type="hidden" class="form-control" name="<?php echo "pack_Marks_Number".$j;?>" id="pack_Marks_Number" value="<?php echo $pack_Marks_Number; ?>"-->
										<?php echo substr($pack_Marks_Number,0,50); ?>
									</td>
									<td class="text-center" rowspan="<?php echo $cntContainers; ?>">
										<!--input type="hidden" class="form-control" name="<?php echo "description_of_Goods".$j;?>" id="description_of_Goods" value="<?php echo $description_of_Goods; ?>"-->
										<?php echo substr($description_of_Goods,0,50); ?>
									</td>
									<td class="text-center" rowspan="<?php echo $cntContainers; ?>">
										<!--input type="hidden" class="form-control" name="<?php echo "notify_name".$j;?>" id="notify_name" value="<?php echo $notify_name; ?>"-->
										<?php echo $notify_name; ?>
									</td>
									<td class="text-center" rowspan="<?php echo $cntContainers; ?>">
										<!--input type="hidden" class="form-control" name="<?php echo "Pack_Number".$j;?>" id="Pack_Number" value="<?php echo $Pack_Number; ?>" -->
										<?php echo $Pack_Number." ".$Pack_Description; ?>
									</td>
									<td class="text-center" rowspan="<?php echo $cntContainers; ?>">
										<input type="hidden" class="form-control" name="<?php echo "agent".$j;?>" id="agent" value="<?php echo $agent; ?>">
										<?php echo $agent; ?>
									</td> 
									<td class="text-center" rowspan="<?php echo $cntContainers; ?>">
										<?php 
											if($save_flag==1){
												echo $rl_no. "\r\n".$rl_date ; 
											} else {
												echo "" ;
											}
										?>
									</td>
									<td class="text-center">
										<?php 
											$inv_unit_gkey = "";
											$last_pos_slot = "";
											$obpc_number = "";
											$obpc_date = "";
											$first_cont = "";
											$first_cont = $resultIgmDtls[0]['cont_number'];
											/*$queryFirstContDtls = "SELECT sparcsn4.inv_unit.gkey,sparcsn4.inv_unit.id,sparcsn4.inv_unit_fcy_visit.last_pos_slot,
											sparcsn4.inv_unit_fcy_visit.flex_string07 AS obpc_number,sparcsn4.inv_unit_fcy_visit.flex_string08 AS obpc_date,
											sparcsn4.inv_unit.goods_and_ctr_wt_kg AS weight,sparcsn4.ref_equip_type.id as type
											FROM sparcsn4.inv_unit
											INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
											INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
											INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
											INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
											INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
											INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
											WHERE sparcsn4.inv_unit.id='$first_cont' AND sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotation'";
											$resFirstContDtls=mysqli_query($con_sparcsn4,$queryFirstContDtls);*/
											 $queryFirstContDtls="SELECT inv_unit.gkey,inv_unit.id,inv_unit_fcy_visit.last_pos_slot,
											inv_unit_fcy_visit.flex_string07 AS obpc_number,inv_unit_fcy_visit.flex_string08 AS obpc_date,
											inv_unit.goods_and_ctr_wt_kg AS weight,ref_equip_type.id AS TYPE
											FROM inv_unit
											INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
											INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
											INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
											INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
											INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
											WHERE inv_unit.id='$first_cont' AND vsl_vessel_visit_details.ib_vyg='$rotation'";
											$resFirstContDtls = oci_parse($con_sparcsn4_oracle, $queryFirstContDtls);
											oci_execute($resFirstContDtls);

											// echo '<pre>';
		                                    // print_r($resFirstContDtls);
		                                    // echo '</pre>';
											//while($rowFirstContDtls = mysqli_fetch_object($resFirstContDtls))
											
											while(($rowFirstContDtls=oci_fetch_object($resFirstContDtls))!=false)
											{
												$inv_unit_gkey = $rowFirstContDtls->GKEY;
												$last_pos_slot = $rowFirstContDtls->LAST_POS_SLOT;
												$obpc_number = $rowFirstContDtls->OBPC_NUMBER;
												$obpc_date = $rowFirstContDtls->OBPC_DATE;
												$weight = $rowFirstContDtls->WEIGHT;
												$type = $rowFirstContDtls->TYPE;
												
											}
											echo $first_cont; 
										?>
										<input type="hidden" class="form-control" name="<?php echo "inv_unit_gkey".$j."0";?>" id="inv_unit_gkey" 
											value="<?php echo $inv_unit_gkey; ?>">
										<!--input type="hidden" class="form-control" name="<?php echo "weight".$j."0";?>" id="weight" 
											value="<?php echo $weight; ?>">
										<input type="hidden" class="form-control" name="<?php echo "type".$j."0";?>" id="type" 
											value="<?php echo $type; ?>"-->
										<input type="hidden" class="form-control" name="<?php echo "cont".$j."0";?>" id="<?php echo "cont".$j."0";?>" 
											value="<?php echo $first_cont; ?>">
											
										
									</td>
									<td class="text-center">
										<input type="hidden" class="form-control" name="<?php echo "size".$j."0";?>" id="size" 
											value="<?php echo $resultIgmDtls[0]['cont_size']; ?>">
										<?php echo $resultIgmDtls[0]['cont_size']; ?>
									</td>
									<td class="text-center">
										<input type="hidden" class="form-control" name="<?php echo "height".$j."0";?>" id="height" 
											value="<?php echo $resultIgmDtls[0]['cont_height']; ?>">
										<?php echo $resultIgmDtls[0]['cont_height']; ?>
									</td>
									<td class="text-center">
										<!--input type="hidden" class="form-control" name="<?php echo "cont_status".$j."0";?>" id="cont_status" 
											value="<?php echo  $resultIgmDtls[0]['cont_status']; ?>"-->
										<?php echo $resultIgmDtls[0]['cont_status'];?>
									</td>
									<td class="text-center">
										<input type="hidden" class="form-control" name="<?php echo "last_pos_slot".$j."0";?>" id="last_pos_slot" value="<?php echo $last_pos_slot; ?>">
										<?php echo $last_pos_slot; ?>
										
									</td>
									<td class="text-center">
										<input type="hidden" class="form-control" name="<?php echo "obpc_number".$j."0";?>" id="obpc_number" 
											value="<?php echo $obpc_number; ?>">
										<?php echo $obpc_number; ?>
									</td>
									<td class="text-center">
										<input type="hidden" class="form-control" name="<?php echo "obpc_date".$j."0";?>" id="obpc_date" 
											value="<?php $obpc_date; ?>">
										<?php if($obpc_date=="0000-00-00") echo "&nbsp;"; else echo $obpc_date; ?>
									</td>
									<td class="text-center">
										<div class="checkbox-custom">
											<input type="checkbox" onclick="toggle_removal_status(this,<?php echo $j."0"?>);">
											<label for="checkboxExample1">Remove</label>
										</div>
										<br>
										<input type="hidden" class="form-control" name="<?php echo "removal_status".$j."0";?>" 
											id="<?php echo "removal_status".$j."0";?>" value="0" style="width:100px;">
									</td>
									<td class="text-center" rowspan="<?php echo $cntContainers; ?>">
										<input type="text" class="form-control" name="<?php echo "remarks".$j;?>" id="remarks" 
											value="<?php if(isset($remarks)) echo $remarks; else echo ""; ?>" style="width:150px;"/>
									</td>
								</tr>
								<?php 
									if($cntContainers>1) {
									for($n=1; $n<count($resultIgmDtls); $n++)
										{
										$cont = $resultIgmDtls[$n]['cont_number'];
										$size = $resultIgmDtls[$n]['cont_size'];
										$height = $resultIgmDtls[$n]['cont_height'];
										$cont_status = $resultIgmDtls[$n]['cont_status'];
									
										
										$inv_unit_gkey = "";
										$last_pos_slot = "";
										$obpc_number = "";
										$obpc_date = "";
										
										/*$queryContDtls = "SELECT sparcsn4.inv_unit.gkey,sparcsn4.inv_unit.id,sparcsn4.inv_unit_fcy_visit.last_pos_slot,
										sparcsn4.inv_unit_fcy_visit.flex_string07 AS obpc_number,sparcsn4.inv_unit_fcy_visit.flex_string08 AS obpc_date,
										sparcsn4.inv_unit.goods_and_ctr_wt_kg AS weight,sparcsn4.ref_equip_type.id as type
										FROM sparcsn4.inv_unit
										INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
										INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
										INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
										INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
										INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
										INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
										WHERE sparcsn4.inv_unit.id='$cont'  AND sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotation'";*/
										 $queryContDtls="SELECT inv_unit.gkey,inv_unit.id,inv_unit_fcy_visit.last_pos_slot,
										inv_unit_fcy_visit.flex_string07 AS obpc_number,inv_unit_fcy_visit.flex_string08 AS obpc_date,
										inv_unit.goods_and_ctr_wt_kg AS weight,ref_equip_type.id AS TYPE
										FROM inv_unit
										INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
										INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
										INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
										INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
										INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
										WHERE inv_unit.id='$cont'  AND vsl_vessel_visit_details.ib_vyg='$rotation'";
										//$resContDtls=mysqli_query($con_sparcsn4,$queryContDtls);
										$resContDtls = oci_parse($con_sparcsn4_oracle, $queryContDtls);
										oci_execute($resContDtls);
										//while($rowContDtls = mysqli_fetch_object($resContDtls))
										$inv_unit_gkey="";
									    $last_pos_slot ="";
										$obpc_number="";
										$obpc_date="";
										$weight ="";
										$type="";
										while(($rowContDtls=oci_fetch_object($resContDtls))!=false)
										{
											$inv_unit_gkey = $rowContDtls->GKEY;
											$last_pos_slot = $rowContDtls->LAST_POS_SLOT;
											$obpc_number = $rowContDtls->OBPC_NUMBER;
											$obpc_date = $rowContDtls->OBPC_DATE;
											$weight = $rowContDtls->WEIGHT;
											$type = $rowContDtls->TYPE;
										}
								  ?>
								
								<tr>
									<td  align="center" >
										<input type="hidden" class="form-control" name="<?php echo "inv_unit_gkey".$j.$n;?>" id="inv_unit_gkey" 
											value="<?php echo $inv_unit_gkey; ?>">
										<!--input type="hidden" class="form-control" name="<?php echo "weight".$j.$n;?>" id="weight" 
											value="<?php echo $weight; ?>">
										<input type="hidden" class="form-control" name="<?php echo "type".$j.$n;?>" id="type" 
											value="<?php echo $type; ?>"-->
										<input type="hidden" class="form-control" name="<?php echo "cont".$j.$n;?>" id="<?php echo "cont".$j.$n;?>" 
											value="<?php echo $cont; ?>">
										<?php echo $cont; ?>
									</td>
									<td  align="center" >
										<input type="hidden" class="form-control" name="<?php echo "size".$j.$n;?>" id="size" 
											value="<?php echo $size; ?>">
										<?php echo $size; ?>
									</td>
									<td  align="center" >
										<input type="hidden" class="form-control" name="<?php echo "height".$j.$n;?>" id="height" 
											value="<?php echo $height; ?>">
										<?php echo $height; ?>
									</td>
									<td  align="center" >
										<!--input type="hidden" class="form-control" name="<?php echo "cont_status".$j.$n;?>" id="cont_status" 
											value="<?php echo $cont_status; ?>"-->
										<?php echo $cont_status; ?>
									</td>
									<td  align="center" >
										<!--input type="hidden" class="form-control" name="<?php echo "last_pos_slot".$j.$n;?>" id="last_pos_slot" 
											value="<?php echo $last_pos_slot; ?>"-->
										<?php echo $last_pos_slot; ?>
									</td>
									<td  align="center" >
										<!--input type="hidden" class="form-control" name="<?php echo "obpc_number".$j.$n;?>" id="obpc_number" 
											value="<?php echo $obpc_number; ?>"-->
										<?php echo $obpc_number; ?>
									</td>
									<td  align="center" >
										<!--input type="hidden" class="form-control" name="<?php echo "obpc_date".$j.$n;?>" id="obpc_date" 
											value="<?php $obpc_date; ?>"-->
										<?php echo $obpc_date; ?>
									</td>
									<td class="text-center">
										<div class="checkbox-custom">
											<input type="checkbox" onclick="toggle_removal_status(this,<?php echo $j.$n;?>);">
											<label for="checkboxExample1">Remove</label>
										</div>
										<br>
										<input type="hidden" class="form-control" name="<?php echo "removal_status".$j.$n;?>" 
											id="<?php echo "removal_status".$j.$n;?>" value="0" style="width:100px;">
									</td>
								</tr>
								
							<?php }
							}  } ?>
							<tr>
								<td colspan="16" class="text-center">	
									<input type="hidden" class="form-control" name="cntResult" id="cntResult" value="<?php echo $cntResult; ?>">
									<input type="hidden" class="form-control" name="rotation" id="rotation" value="<?php echo $rotation; ?>">										
									<input type="hidden" class="form-control" name="arrival_date" id="arrival_date" value="<?php echo $arrival_date; ?>">										
									<input type="hidden" class="form-control" name="cl_date" id="cl_date" value="<?php echo $cl_date; ?>">										
									<input type="hidden" class="form-control" name="vessel_name" id="vessel_name" value="<?php echo $v_name; ?>">										
									<?php if($save_flag=="0") { ?>
										<input type="hidden" class="form-control" value="save" name="action" id="action" />
										<button type="submit" class="btn btn-success" onclick="return confirmMsg();">Save</button>	
									<?php } else { ?>
										<input type="hidden" class="form-control"  value="print" name="action" id="action" />
										<button type="submit" class="btn btn-primary">View</button>	
									<?php } ?>
								</td>
							</tr>
						</table>
					</form>
                    </div>
                </div>
            </section>
            <?php } ?>
        </div>
    </div>
	<?php mysqli_close($con_sparcsn4); ?>
	<?php mysqli_close($con_cchaportdb); ?>
	<?php oci_close($con_sparcsn4_oracle); ?>
</section>
</div>
