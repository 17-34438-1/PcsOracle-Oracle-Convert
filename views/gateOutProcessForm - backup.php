<script>
	function getSearchInfo()
	{		

		var select = document.getElementById("visitId");
		var length = select.options.length;
		for (i = length-1; i >= 0; i--) {
		select.options[i] = null;
		}

		var searchBy = document.getElementById('searchBy').value;
		var searchValue = document.getElementById('searchValue').value;
		
		if (window.XMLHttpRequest) 
		{
			xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
				
		xmlhttp.onreadystatechange=function(){		 
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{							
				var val = xmlhttp.responseText;
				var jsonData = JSON.parse(val);				
				
				var id = "";
				var details = "";

				for(i=0;jsonData.searchResult.length>i;i++)
				{
					id = jsonData.searchResult[i].id;
					details = jsonData.searchResult[i].details;
					// var option = "<option value='"+id+"'>"+details+"</option>";
					// document.getElementById('visitId').innerHTML = option;

					var option = document.createElement("option");
					option.text = details;
					option.value = id;
					var select = document.getElementById("visitId");
					select.appendChild(option);

				}
							
				// document.getElementById('driverLicNo').value = licNo;
			}
		};
		
		var url = "<?php echo site_url('AjaxController/getSearchInfo')?>?searchBy="+searchBy+"&searchValue="+searchValue;		
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}

	function validate(){
		var searchBy = document.getElementById('searchBy').value;
		var searchValue = document.getElementById('searchValue').value;

		if(!searchBy)
		{
			alert("Please Select SearchBy...");
			return false;
		}
		else if(!searchValue)
		{
			alert("Please Assign a value...");
			return false;
		}else{
			return true;
		}
		
		return false;
	}

</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/gateOutDataSearch") ?>" onsubmit="return validate();">

					<div class="form-group">
						<div>
							<!-- <label class="col-md-1 control-label">&nbsp;</label> -->

							<div class="col-md-4">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search By <span class="required">*</span></span>
									<select name="searchBy" id="searchBy" class="form-control login_input_text" tabindex="1">
										<option value="container">Container</option>
										<option value="bl">BL</option>
									</select>
								</div>												
							</div>

							<div class="col-md-4">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search Value <span class="required">*</span></span>
									<input type="text" name="searchValue" id="searchValue" class="form-control login_input_text" tabindex="2" placeholder="Search Value" onblur="getSearchInfo()">
								</div>												
							</div>

							<div class="col-md-4">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Truck Visit Id. <span class="required">*</span></span>
									<select name="visitId" id="visitId" class="form-control login_input_text" tabindex="3">
									</select>
								</div>												
							</div>
						</div>
																		
						<div class="row">
							<div class="col-sm-12 text-center">
								<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
								<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
							</div>													
						</div>						
					</div>	
				</form>
			
            <?php
				if($flag == 1)
				{
					include("mydbPConnection.php");
					$truckByContQuery = "SELECT * FROM do_truck_details_entry WHERE id = '$visitId'";
					$truckResult = mysqli_query($con_cchaportdb,$truckByContQuery);
					
					$id="";
					$rot_no = "";
					$cont_no = "";
					$truck_id = "";
					$gate_no = "";
					$driver_name = "";
					$driver_gate_pass = "";
					$assistant_name = "";
					$assistant_gate_pass = "";
                    $gate_in_status = "";
                    $gate_out_status = "";
                    $traffic_chk_st = "";
					$yard_security_chk_st = "";
					$cnf_chk_st = "";
					$verify_info_fcl_id = "";
					$verifyOtherDataId = "";
					$i=1;
					$driverImg = "";
					$helperImg = "";
					
					$gate_out_time = "";
					while($row = mysqli_fetch_object($truckResult))
					{
						$id = $row->id;
						$rot_no = $row->import_rotation;
						$cont_no = $row->cont_no;
						$truck_id = $row->truck_id;
						$gate_no = $row->gate_no;
						$driver_name = $row->driver_name;
						$driver_gate_pass = $row->driver_gate_pass;
						$assistant_name = $row->assistant_name;
						$assistant_gate_pass = $row->assistant_gate_pass;
                        $gate_in_status = $row->gate_in_status;
                        $gate_out_status = $row->gate_out_status;
                        $traffic_chk_st = $row->traffic_chk_st;
						$yard_security_chk_st = $row->yard_security_chk_st;
						$cnf_chk_st = $row->cnf_chk_st;
						$verify_info_fcl_id = $row->verify_info_fcl_id;
						$verifyOtherDataId = $row->verify_other_data_id;
						
						$gate_out_time = $row->gate_out_time;
						
						$sql_driverImg = "SELECT nid_number,agent_photo
						FROM vcms_vehicle_agent
						WHERE card_number='$driver_gate_pass'";
						$rslt_driverImg = mysqli_query($con_cchaportdb,$sql_driverImg);
						
						while($row_driverImg = mysqli_fetch_object($rslt_driverImg))
						{							
							$driverImg = "vcmsImage/".$row_driverImg->nid_number."/".$row_driverImg->agent_photo;							
						}
						
						$sql_helperImg = "SELECT nid_number,agent_photo
						FROM vcms_vehicle_agent
						WHERE card_number='$assistant_gate_pass'";
						$rslt_helperImg = mysqli_query($con_cchaportdb,$sql_helperImg);
						
						while($row_helperImg = mysqli_fetch_object($rslt_helperImg))
						{							
							$helperImg = "vcmsImage/".$row_helperImg->nid_number."/".$row_helperImg->agent_photo;
						}
					}

					$igmQuery="SELECT  igm_detail_container.cont_number, cont_seal_number,cont_status,cont_height,cont_iso_type, Description_of_Goods,Vessel_Name,Name_of_Master,igm_masters.Import_Rotation_No,truck_no_by,Bill_of_Entry_No,
					igm_details.Notify_name,igm_details.Notify_address
					FROM  igm_details
					INNER JOIN igm_masters ON  igm_masters.id=igm_details.IGM_id
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					LEFT JOIN verify_info_fcl ON igm_detail_container.igm_detail_id=verify_info_fcl.igm_detail_cont_id
					LEFT JOIN  shed_tally_info ON shed_tally_info.igm_detail_id=igm_details.id
					LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
					WHERE igm_details.Import_Rotation_No='$rot_no' AND igm_detail_container.cont_number='$cont_no'";

					$rsltIgm = mysqli_query($con_cchaportdb,$igmQuery);
					$beNo = "";
					$Vessel_Name="";
					$Import_Rotation_No = "";
					$description_of_Goods = "";
					$cont_number = "";
					$Notify_name = "";
					$Notify_address = "";
					while($igmResult = mysqli_fetch_object($rsltIgm)){
						$Vessel_Name = $igmResult->Vessel_Name;
						$Import_Rotation_No = $igmResult->Import_Rotation_No;
						$description_of_Goods = $igmResult->Description_of_Goods;
						$cont_number = $igmResult->cont_number;
						$beNo = $igmResult->Bill_of_Entry_No;
						$Notify_name = $igmResult->Notify_name;
						$Notify_address = $igmResult->Notify_address;
					}

					if(is_null($verify_info_fcl_id)){
						// $jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code,verify_other_data.cnf_lic_no FROM vcms_vehicle_agent 
						// INNER JOIN verify_other_data ON verify_other_data.jetty_sirkar_id = vcms_vehicle_agent.id
						// INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = verify_other_data.id
						// WHERE do_truck_details_entry.verify_other_data_id='$verifyOtherDataId'";
						$jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code FROM vcms_vehicle_agent 
						INNER JOIN lcl_dlv_assignment ON lcl_dlv_assignment.jetty_sirkar_id = vcms_vehicle_agent.id
						INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = lcl_dlv_assignment.id
						WHERE do_truck_details_entry.verify_other_data_id='$verifyOtherDataId'";
					}else if(is_null($verifyOtherDataId)){
						$jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code,verify_info_fcl.cnf_lic_no FROM vcms_vehicle_agent 
						INNER JOIN verify_info_fcl ON verify_info_fcl.jetty_sirkar_id = vcms_vehicle_agent.id
						INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_info_fcl_id = verify_info_fcl.id
						WHERE do_truck_details_entry.verify_info_fcl_id='$verify_info_fcl_id'";
					}

					$rsltJetty = mysqli_query($con_cchaportdb,$jettySarkarQuery);

					$agentName = "";
					$agentCode = "";
					$cnf_lic_no = "";
					while($jettyInfo = mysqli_fetch_object($rsltJetty)){
						$agentName = $jettyInfo->agent_name;
						$agentCode = $jettyInfo->agent_code;
						$cnf_lic_no = $jettyInfo->cnf_lic_no;
					}

					include("dbConection.php");

					$cnfQuery="SELECT DISTINCT a.gkey,a.id AS cont_no,k.name  AS cnf, k.id AS cnf_lic,
					CONCAT(k.address_line1,IFNULL(k.address_line2,'')) AS cnf_addr,
					a.gkey, a.id AS cont_no, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
					mfdch_desc, 
					(SELECT ctmsmis.cont_yard(b.last_pos_slot)) AS Yard_No, b.flex_date01,
					(SELECT ctmsmis.cont_block(b.last_pos_slot, Yard_No)) AS Block_No,
					(SELECT inv_unit_fcy_visit.flex_string10 FROM sparcsn4.inv_unit
					INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.`unit_gkey`=inv_unit.`gkey`
					WHERE inv_unit.id=a.id ORDER BY inv_unit_fcy_visit.flex_date01 DESC LIMIT 1) AS rot_no,
					(SELECT RIGHT(nominal_length, 2) FROM sparcsn4.ref_equip_type
					INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
					INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
					WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey)  AS size,
					CAST((SELECT (RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10) FROM sparcsn4.ref_equip_type
					INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
					INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
					WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey) AS DECIMAL(10,1))  AS height,
					(SELECT sparcsn4.vsl_vessels.name FROM sparcsn4.vsl_vessels
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vessel_gkey=sparcsn4.vsl_vessels.gkey
					WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg=b.flex_string10 LIMIT 1) AS v_name
					FROM sparcsn4.inv_unit a
					INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
					INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods
					INNER JOIN vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.ib_vyg=b.flex_string10
					LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
					INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value
					WHERE a.id='$cont_no' AND sparcsn4.vsl_vessel_visit_details.ib_vyg ='$rot_no'";
		
					$rsltCnf = mysqli_query($con_sparcsn4,$cnfQuery);
					$cnf="";
					$cnf_addr = "";
					$cnf_lic = "";
					$Yard_No = "";
					$assignmentType="";
					while($cnfResult=mysqli_fetch_object($rsltCnf)){
						$cnf = $cnfResult->cnf;
						$cnf_addr = $cnfResult->cnf_addr;
						$cnf_lic = $cnfResult->cnf_lic;
						$Yard_No = $cnfResult->Yard_No;
						$assignmentType = $cnfResult->mfdch_value;
					}

					$YardQuery = "SELECT DISTINCT Yard_No
					FROM ctmsmis.tmp_vcms_assignment  
					WHERE cont_no = '$cont_no' AND rot_no = '$rot_no'";

					$rsltYard = mysqli_query($con_sparcsn4,$YardQuery);

					$yardNo="";
					while($yardResult=mysqli_fetch_object($rsltYard)){
						$yardNo = $yardResult->Yard_No;
					}
            ?>

			<hr/>
				<div class="invoice">
					<div class="row">
						<div class="col-sm-5 col-md-5">
							<h4 class="h4 mt-none mb-sm text-dark"><b><u>Jetty Sarkar Information </b></u></h4>
							<h6 class="h6 mt-none mb-sm">1. Vessel : <b><?php echo $Vessel_Name; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">2. Rotation : <b><?php echo $Import_Rotation_No; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">3. B/E No : <b><?php echo $beNo; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">4. Shed / Yard No : <b><?php echo $yardNo; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">5. Jetty Sarkar Name : <b><?php echo $agentName; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">6. Jetty Sarkar Lic. No. : <b><?php echo $agentCode; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">7. Container No. : <b><?php echo $cont_number; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">8. Goods Details : <b><?php echo $description_of_Goods; ?></b></h6>
						</div>
						<div class="col-sm-4 col-md-4">
							<h4 class="h4 mt-none mb-sm text-dark"><b><u>Transport Agency Information </b></u></h4>
							<h6 class="h6 mt-none mb-sm">1. Gate No : <b><?php echo $gate_no; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">2. Truck No : <b><?php echo $truck_id; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">3. Driver Name : <b><?php echo $driver_name; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">4. ID Card (Driver) : <b><?php echo $driver_gate_pass; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">5. Union Membership No. (Driver) : <b><?php echo ""; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">6. Assistant Name : <b><?php echo $assistant_name; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">7. ID Card (Assistant) : <b><?php echo $assistant_gate_pass; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">8. Union Membership No. (Assistant) : <b><?php echo ""; ?></b></h6>
							<h6 class="h6 mt-none mb-sm">9. Transport Agency Name : <b><?php echo ""; ?></b></h6>
						</div>
						<div class="col-sm-3 col-md-3">
							<img src="<?php echo $driverPhoto; ?>" height="120px" width="120px">
						</div>
						
						<div class="col-sm-3 col-md-3">
							<img src="<?php echo $helperPhoto; ?>" height="120px" width="120px">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php
								if($cont_status == "Blocked"){
							?>
								<div class="col-sm-12 text-center">
									<font size="4" color="red"><b><?php echo $cont_no; ?> Container is Blocked!!</b></font>
								</div>
							<?php
							}
							else
							{
                            if($traffic_chk_st == 1 && $yard_security_chk_st == 1 && $cnf_chk_st == 1){
								if($gate_out_status == 0){
							?>
							<form method="POST" action="<?php echo site_url("ShedBillController/gateOutStsCng") ?>" onsubmit="return confirm('Are You Sure? ');">
								<input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
								<button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary">Gate Out</button>
							</form>
							<?php
								}else if($gate_out_status == 1){
									if($gateFlag == 0){
							?>
								
									<font size="4" color="red"><b></b><?php echo "You have Already used this ticket To Gate Out at: ". $gate_out_time;?></font>
								
								<?php
									}else if($gateFlag == 1){
								?>
									<font size="4" color="red"><b></b><?php echo "Gate out Completed!!!";?></font>
								<?php
									}
								}
							}
							else if($traffic_chk_st == 0)
							{
							?>
								
									<font size="4" color="red"><b></b><?php echo "Traffic have not confirmed yet";?></font>
								
							<?php
							}else if($cnf_chk_st == 0){
							?>
						
								<font size="4" color="red"><b></b><?php echo "C&F have not confirmed yet";?></font>
							
							<?php
							}else if($yard_security_chk_st == 0){
							?>
							
								<font size="4" color="red"><b></b><?php echo "Security have not confirmed yet";?></font>

							<?php
                                
                            }else{

									//$trafficMsg = "";
								//	$securityMsg = "";
									//$cnfMsg = "";
							//		if($traffic_chk_st == 0){
								//		$trafficMsg = "Traffic,";
									//}
							//		if($yard_security_chk_st == 0){
								//		$securityMsg = "Security,";
									//}
								//	if($cnf_chk_st == 0){
									//	$cnfMsg = "C&F,";
							//		}

								//	$msg = $trafficMsg.$securityMsg.$cnfMsg;
									
							?>

									<font size='4' color="red"> Please try again after your processing done! </font>
							<?php
							}}
							?>

						</div>
						
						<!--div class="col-sm-12 text-center">
							<?php
                            if($traffic_chk_st == 1 && $yard_security_chk_st == 1 && $cnf_chk_st == 1){
								if($gate_out_status == 0){
							?>
							<form method="POST" action="<?php echo site_url("ShedBillController/gateOutStsCng") ?>" onsubmit="return confirm('Are You Sure? ');">
								<input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
								<button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary">Gate Out</button>
							</form>
							<?php
								}else{
							?>
								<div class="col-sm-6 text-right">
									<font color="green"><b>Gate out Completed!!!</b></font>
								</div>
								<div class="col-sm-6 text-left">
									<form action="<?php echo site_url("GateController/chalanVCMS") ?>" method="post" target="_blank">
										<input type="hidden" class="btn btn-primary" name="cnf_lic_no" id="cnf_lic_no" 
											value="<?php echo $cnf_lic_no;?>"/>
											
										<input type="hidden" class="btn btn-primary" name="visitId" id="visitId" 
											value="<?php echo $visitId;?>"/>
										
											
										<input type="submit" class="btn btn-primary" name="" id="" value="Challan"/>
									</form>
								</div>
							<?php
                                }
                            }else{

									$trafficMsg = "";
									$securityMsg = "";
									$cnfMsg = "";
									if($traffic_chk_st == 0){
										$trafficMsg = "Traffic,";
									}
									if($yard_security_chk_st == 0){
										$securityMsg = "Security,";
									}
									if($cnf_chk_st == 0){
										$cnfMsg = "C&F,";
									}

									$msg = $trafficMsg.$securityMsg.$cnfMsg;
									
							?>

									<font color="red"><b><?php echo substr($msg,0,-1); ?> confirmation is not done yet!!!</b></font>
							<?php
								}
							?>

						</div-->						
					</div>
				</div>
			</div>
            <?php
                }
            ?>
		</section>
	</div>
</div>
</section>
</div>