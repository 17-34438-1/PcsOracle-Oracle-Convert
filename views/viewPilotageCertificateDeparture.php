<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
	<!-- start: page -->
	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php //echo $msg;?>
						</div>
					</div>
					<form name= "myForm" onsubmit="return validate();" method="post" 
						action="<?php echo base_url().'index.php/report/departureOfVesselEntry'; ?>">
						<input type="hidden" value="<?php echo $igm_mst_id; ?>" name="d_igm_mst_id"/>
						<input type="hidden" value="<?php echo $vvd_gkey; ?>" name="d_vvd_gkey"/>
						<input type="hidden" value="<?php echo $rotation; ?>" name="rotation"/>
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">VESSEL NAME</span>
									<input type="text" name="d_vsl_name" id="d_vsl_name" class="form-control" 
										placeholder="VESSEL NAME" value="<?php echo $Vessel_Name; ?>" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">CALL SIGN</span>
									<input type="text" name="d_cal_sign" id="d_cal_sign" class="form-control" 
										placeholder="CALL SIGN" value="<?php echo $radioCallSign; ?>" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">FLAG</span>
									<input type="text" name="d_vsl_flag" id="d_vsl_flag" class="form-control" 
										placeholder="COUNTRY NAME" value="<?php echo $cntry_name; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NAME OF MASTER</span>
									<input type="text" name="d_master_name" id="d_master_name" class="form-control" 
										placeholder="MASTER NAME" value="<?php echo $Name_of_Master; ?>" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">GRT</span>
									<input type="text" name="d_grt" id="d_grt" class="form-control" 
										placeholder="GROSS REGISTERED TON" value="<?php echo $gross_registered_ton; ?>" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NRT</span>
									<input type="text" name="d_nrt" id="d_nrt" class="form-control" placeholder="NET REGISTERED TON" 
										value="<?php echo $net_registered_ton; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DECK CARGO</span>
									<input type="text" name="d_deck_cargo" id="d_deck_cargo" class="form-control" 
										placeholder="DECK CARGO" value="<?php echo $Deck_cargo; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">LOA</span>
									<input type="text" name="d_loa" id="d_loa" class="form-control" placeholder="LOA" 
										value="<?php echo $loa_cm; ?>" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">MAX. FW DRAUGHT </span>
									<input type="text" name="d_max_fw" id="d_max_fw" class="form-control" value="<?php echo $draught; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NAME AND ADDRESS OF OWNERS</span>
									<input type="text" name="d_name_addr_owner" id="d_name_addr_owner" class="form-control" 
									value="<?php echo $owner_name; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">LOCAL AGENT</span>
									<input type="text" name="d_loc_agent" id="d_loc_agent" class="form-control" 
									value="<?php echo $localagent; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">PILOT NAME</span>
									<select class="form-control" name="d_name_pilot" id="d_name_pilot" required>
										<option value="">--Select--</option>
										<?php for($i=0;$i<count($pilot_list);$i++){ ?>
											<option value="<?php echo $pilot_list[$i]["login_id"];?>" 
												<?php if($pilot_list[$i]["login_id"]==$depart_pilot_login_id) { ?> selected <?php } ?>>
												<?php echo $pilot_list[$i]["u_name"];?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BOARDED AT</span>
									<input type="text" name="d_board_at" id="d_board_at" class="form-control" 
									value="<?php if($depart_pilot_on_board=="0000-00-00 00:00:00") echo ""; 
											else echo $depart_pilot_on_board; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">LEFT AT</span>
									<input type="text" name="d_left_at" id="d_left_at" class="form-control" 
									value="<?php if($depart_pilot_off_board=="0000-00-00 00:00:00") echo ""; 
											else echo $depart_pilot_off_board; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DATE</span>
									<input type="text" name="left_dt" id="left_dt" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">PILOTAGE FROM</span>
									<input type="text" name="d_pilotage_from" id="d_pilotage_from" class="form-control" 
									value="<?php if($depart_pilot_frm=="0000-00-00 00:00:00") echo ""; 
											else echo $depart_pilot_frm; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">TO</span>
									<input type="text" name="d_pilotage_to" id="d_pilotage_to" class="form-control" 
									value="<?php if($depart_pilot_to=="0000-00-00 00:00:00") echo ""; 
											else echo $depart_pilot_to; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DATE</span>
									<input type="text" name="d_pilotage_dt" id="d_pilotage_dt" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DATE OF ARRIVAL IN PORT</span>
									<input type="text" name="d_dt_arraival" id="d_dt_arraival" class="form-control" readonly
									value="<?php if($ata=="0000-00-00 00:00:00" or $ata=="0000-00-00") echo "";
												else if (date("Y-m-d", strtotime($ata)) == "1970-01-01") echo "";
												else echo date("Y-m-d", strtotime($ata)); ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DATE AND HOUR OF BERTHING</span>
									<input type="text" name="d_dt_hrs_berth" id="d_dt_hrs_berth" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DATE OF DEPARTURE</span>
									<input type="text" name="d_dt_of_depart" id="d_dt_of_depart" class="form-control" 
									value="<?php if($depart_atd=="0000-00-00 00:00:00") echo ""; else echo $depart_atd; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DEP. DRAFT(MAX)</span>
									<input type="text" name="d_dept_draft" id="d_dept_draft" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-5">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">TIME OF UNMOORING FROM</span>
									<input type="text" name="d_time_unmoor_from" id="d_time_unmoor_from" class="form-control" 
										value="<?php 
													if($depart_mooring_frm_time=="0000-00-00 00:00:00" 
																or 
														$depart_mooring_frm_time=="0000-00-00") {
															echo "";
														} 
													else echo $depart_mooring_frm_time; 
														 
												?>" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">TO</span>
									<input type="text" name="d_time_unmoor_to" id="d_time_unmoor_to" class="form-control"
									value="<?php 													
												if($depart_mooring_to_time=="0000-00-00 00:00:00" or $depart_mooring_to_time=="0000-00-00") echo ""; 
												else echo $depart_mooring_to_time; 													
											?>" 
												>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DATE</span>
									<input type="text" name="time_unmoor_dt" id="time_unmoor_dt" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">CPA TUG/TUGS(NAME)</span>
									<input type="text" name="d_cpa_tug" id="d_cpa_tug" class="form-control" 
									value="<?php echo $depart_tug_name;  ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">ASSISTANCE FROM</span>
									<input type="text" name="d_assist_from" id="d_assist_from" class="form-control" 
										value="<?php 
													if($depart_assit_frm=="0000-00-00 00:00:00" or $depart_assit_frm=="0000-00-00"){
															echo "";
														} 
													else echo $depart_assit_frm;
												?>" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">ASSISTANCE TO</span>
									<input type="text" name="d_assist_to" id="d_assist_to" class="form-control" 
										value="<?php  
													if($depart_assit_to=="0000-00-00 00:00:00" or $depart_assit_to=="0000-00-00"){
															echo "";
														} 
													else echo $depart_assit_to;
												?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">ASSISTANCE DATE</span>
									<input type="text" name="d_assist_dt" id="d_assist_dt" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">PC NO</span>
									<input type="text" name="d_pc_no" id="d_pc_no" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DATE</span>
									<input type="text" name="d_pc_dt" id="d_pc_dt" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">TONS OF CARGO ON BOARD</span>
									<input type="text" name="d_tons_of_crgo" id="d_tons_of_crgo" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md" >
									<div style="display:inline-block;margin-right:5px">
										<font class="span_width" style="display:inline-block;font-weight:bold;" align="right"> 
											MAIN ENGINES IN GOOD WORKING CONDITION ?	
										</font>
									</div>
									<div style="display:inline-block;" align="right">
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_main_engine_ok" 
												id="is_main_engine_ok" value="1" 
												<?php if($is_main_engine_ok=="1") echo "checked";?> required />
											<label class="form-check-label">YES</label>
										</div>
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_main_engine_ok" 
												id="is_main_engine_ok" value="0" 
												<?php if($is_main_engine_ok=="0") echo "checked";?> />
											<label class="form-check-label">NO</label>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md" >
									<div style="display:inline-block;margin-right:5px">
										<font class="span_width" style="display:inline-block;font-weight:bold;" align="right"> 
											TWO ANCHORS IN GOOD WORKING CONDITION ?	
										</font>
									</div>
									<div style="display:inline-block;" align="right">
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_acnchors_ok" id="is_acnchors_ok" 
											value="1" <?php if($is_acnchors_ok=="1") echo "checked";?> required />
											<label class="form-check-label">YES</label>
										</div>
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_acnchors_ok" id="is_acnchors_ok" value="0" <?php if($is_acnchors_ok=="0") echo "checked";?> />
											<label class="form-check-label">NO</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md" >
									<div style="display:inline-block;margin-right:5px">
										<font class="span_width" style="display:inline-block;font-weight:bold;" align="right"> 
											RUDDER INDICATOR IN GOOD WORKING CONDITION ?	
										</font>
									</div>
									<div style="display:inline-block;" align="right">
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_rudder_indicator_ok" 
												id="is_rudder_indicator_ok" value="1" 
												<?php if($is_rudder_indicator_ok=="1") echo "checked";?> required />
											<label class="form-check-label">YES</label>
										</div>
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_rudder_indicator_ok" 
												id="is_rudder_indicator_ok" value="0" 
												<?php if($is_rudder_indicator_ok=="0") echo "checked";?> />
											<label class="form-check-label">NO</label>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md" >
									<div style="display:inline-block;margin-right:5px">
										<font class="span_width" style="display:inline-block;font-weight:bold;" align="right"> 
											RPM INDICATOR IN GOOD WORKING CONDTION ?	
										</font>
									</div>
									<div style="display:inline-block;" align="right">
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_rpm_indicator_ok" id="is_rpm_indicator_ok" value="1" <?php if($is_rpm_indicator_ok=="1") echo "checked";?> required />
											<label class="form-check-label">YES</label>
										</div>
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_rpm_indicator_ok" id="is_rpm_indicator_ok" value="0" <?php if($is_rpm_indicator_ok=="0") echo "checked";?> />
											<label class="form-check-label">NO</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md" >
									<div style="display:inline-block;margin-right:5px">
										<font class="span_width" style="display:inline-block;font-weight:bold;" align="right"> 
											BOW THRUSTER AVAILABLE ?	
										</font>
									</div>
									<div style="display:inline-block;" align="right">
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_bow_therster_available" 
												id="is_bow_therster_available" value="1" 
												<?php if($is_bow_therster_available=="1") echo "checked";?> required />
											<label class="form-check-label">YES</label>
										</div>
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_bow_therster_available" 
												id="is_bow_therster_available" value="0" 
												<?php if($is_bow_therster_available=="0") echo "checked";?>/>
											<label class="form-check-label">NO</label>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md" >
									<div style="display:inline-block;margin-right:5px">
										<font class="span_width" style="display:inline-block;font-weight:bold;" align="right"> 
											ARE YOU COMPLYING SOLAS CONVENTION ?	
										</font>
									</div>
									<div style="display:inline-block;" align="right">
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_complying_soal_convention"
												id="is_complying_soal_convention" value="1" 
												<?php if($is_complying_soal_convention=="1") echo "checked";?> required />
											<label class="form-check-label">YES</label>
										</div>
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_complying_soal_convention" 
												id="is_complying_soal_convention" value="0" 
												<?php if($is_complying_soal_convention=="0") echo "checked";?> />
											<label class="form-check-label">NO</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md" >
									<div style="display:inline-block;margin-right:5px">
										<font class="span_width" style="display:inline-block;font-weight:bold;" align="right"> 
											IS NIGHT SHIFT ?
										</font>
									</div>
									<div style="display:inline-block;" align="right">
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_night" id="is_night" value="1" required
											<?php if($is_night=="1") { ?> checked <?php } ?> />
											<label class="form-check-label">YES</label>
										</div>
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_night" id="is_night" value="0" 
											<?php if($is_night=="0") { ?> checked <?php } ?> />
											<label class="form-check-label">NO</label>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="input-group mb-md" >
									<div style="display:inline-block;margin-right:5px">
										<font class="span_width" style="display:inline-block;font-weight:bold;" align="right"> 
											IS HOLIDAY ?
										</font>
									</div>
									<div style="display:inline-block;" align="right">
										<div class="form-check form-check-inline" style="display:inline-block;"> 
											<input class="form-check-input" type="radio" name="is_holiday" id="is_holiday" required
											value="1" <?php if($is_holiday=="1") { ?> checked <?php } ?> />
											<label class="form-check-label">YES</label>
										</div>
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_holiday" id="is_holiday" value="0" 
											<?php if($is_holiday=="0") { ?> checked <?php } ?>/>
											<label class="form-check-label">NO</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NOS. OF GOOD MOORING LINES: FORD</span>
									<input type="text" name="d_no_good_mor_line" id="d_no_good_mor_line" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NOS. OF GOOD MOORING LINES: AFT</span>
									<input type="text" name="d_aft" id="d_aft" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">STERN POWER AVAILABLE</span>
									<input type="text" name="d_st_power_avbl" id="d_st_power_avbl" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">IMMEDIATELY (SECONDS LATER)</span>
									<input type="text" name="d_immediate" id="d_immediate" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary">
									SAVE
								</button>
							</div>													
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>	
	<!-- end: page -->
</section>
</div>