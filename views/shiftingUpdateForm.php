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
						action="<?php echo base_url().'index.php/report/shifting_update'; ?>">
						<input type="hidden" name="shift_igm_id" id="shift_igm_id" value="<?php echo $igm_mst_id; ?>"/>
						<input type="hidden" name="shift_vvd_gkey" id="shift_vvd_gkey" value="<?php echo $vvd_gkey; ?>"/>
						<input type="hidden" name="shift_rotation" id="shift_rotation" value="<?php echo $shift_rotation; ?>"/>
						<input type="hidden" name="shift_id" id="shift_id" value="<?php echo $shifting_id; ?>"/>
						<input type="hidden" name="agent" id="agent" value="<?php echo $agent; ?>"/>
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">VESSEL NAME</span>
									<input type="text" name="shift_vsl_name" id="shift_vsl_name" class="form-control" 
										placeholder="VESSEL NAME" value="<?php echo $Vessel_Name; ?>" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">CALL SIGN</span>
									<input type="text" name="shift_cal_sign" id="shift_cal_sign" class="form-control" 
										placeholder="CALL SIGN" value="<?php echo $radio_call_sign; ?>" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">FLAG</span>
									<input type="text" name="shift_vsl_flag" id="shift_vsl_flag" class="form-control" 
										placeholder="VESSEL NAME" value="<?php echo $cntry_name; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NAME OF MASTER</span>
									<input type="text" name="shift_master_name" id="shift_master_name" class="form-control" 
										placeholder="MASTER NAME" value="<?php echo $Name_of_Master; ?>" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">GRT</span>
									<input type="text" name="shift_grt" id="shift_grt" class="form-control" 
										placeholder="GROSS REGISTERED TON" value="<?php echo $gross_registered_ton; ?>" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NRT</span>
									<input type="text" name="shift_nrt" id="shift_nrt" class="form-control" placeholder="NET REGISTERED TON" 
										value="<?php echo $net_registered_ton; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DECK CARGO</span>
									<input type="text" name="shift_deck_cargo" id="shift_deck_cargo" class="form-control" 
										placeholder="DECK CARGO" value="<?php echo $Deck_cargo; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">LOA</span>
									<input type="text" name="shift_loa" id="shift_loa" class="form-control" placeholder="LOA" 
										value="<?php echo $loa_cm; ?>" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">MAX. FW DRAUGHT </span>
									<input type="text" name="shift_max" id="shift_max" class="form-control" value="<?php echo $draught; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NAME AND ADDRESS OF OWNERS</span>
									<input type="text" name="shift_address_of_owners" id="shift_address_of_owners" class="form-control" value="<?php echo $owner_name; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">LOCAL AGENT</span>
									<input type="text" name="localAgent" id="localAgent" class="form-control" 
									value="<?php echo $localagent; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">PILOT NAME</span>
									<select class="form-control" name="shift_name_of_pilot" id="shift_name_of_pilot" required>
										<option value="">--Select--</option>
										<?php for($i=0;$i<count($pilot_list);$i++){ ?>
											<option value="<?php echo $pilot_list[$i]["login_id"];?>" 
												<?php if($pilot_list[$i]["login_id"]==$pilot_login) { ?> selected <?php } ?>>
												<?php echo $pilot_list[$i]["u_name"];?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BOARDED AT</span>
									<input type="text" name="shift_boarded_at" id="shift_boarded_at" class="form-control" 
									value="<?php if($crnt_pilot_on_board=="0000-00-00 00:00:00") echo ""; 
											else echo $crnt_pilot_on_board; ?>">
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">LEFT AT</span>
									<input type="text" name="shift_left_at" id="shift_left_at" class="form-control" 
									value="<?php if($crnt_pilot_off_board=="0000-00-00 00:00:00") echo ""; 
											else echo $crnt_pilot_off_board; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">SHIFTED/SWUNG FROM</span>
									<input type="text" name="shift_shifted_from" id="shift_shifted_from" class="form-control" 
									value="<?php if($crnt_shift_frm=="0000-00-00 00:00:00") echo ""; 
											else echo $crnt_shift_frm; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">SHIFTED/SWUNG TO</span>
									<input type="text" name="shift_shifted_to" id="shift_shifted_to" class="form-control" 
									value="<?php if($crnt_shift_to=="0000-00-00 00:00:00") echo ""; 
											else echo $crnt_shift_to; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">TIME OF MOORING/UNMOORING FROM</span>
									<input type="text" name="shift_mooring_unmooring_from" id="shift_mooring_unmooring_from" 
										class="form-control" 
										value="<?php if($crnt_mooring_frm_time=="0000-00-00 00:00:00") echo ""; 
											else echo $crnt_mooring_frm_time; ?>">
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">TO</span>
									<input type="text" name="shift_mooring_unmooring_to" id="shift_mooring_unmooring_to" 
										class="form-control" 
										value="<?php if($crnt_mooring_to_time=="0000-00-00 00:00:00") echo ""; 
											else echo $crnt_mooring_to_time; ?>">
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DATE</span>
									<input type="text" name="shift_mooring_unmooring_date" id="shift_mooring_unmooring_date" readonly
										class="form-control" 
										value="<?php if($crnt_shift_dt=="0000-00-00 00:00:00") echo ""; 
											else echo $crnt_shift_dt; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">CPA TUG/TUGS(NAME)</span>
									<input type="text" name="shift_cpa_tug" id="shift_cpa_tug" 
										class="form-control" value="<?php echo $crnt_tug_name; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">ASSISTANCE FROM</span>
									<input type="text" name="shift_assistance_from" id="shift_assistance_from" class="form-control" 
										value="<?php if($crnt_assit_frm=="0000-00-00 00:00:00") echo ""; 
														else echo $crnt_assit_frm; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">ASSISTANCE TO</span>
									<input type="text" name="shift_assistance_to" id="shift_assistance_to" class="form-control" 
										value="<?php if($crnt_assit_to=="0000-00-00 00:00:00") echo ""; 
														else echo $crnt_assit_to; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">ASSISTANCE DATE</span>
									<input type="text" name="shift_assistance_date" id="shift_assistance_date" class="form-control" readonly
										value="<?php if($crnt_shift_dt=="0000-00-00 00:00:00") echo ""; 
														else echo $crnt_shift_dt; ?>">
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
									<input type="text" name="shift_no_of_good_mooring" id="shift_no_of_good_mooring" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NOS. OF GOOD MOORING LINES: AFT</span>
									<input type="text" name="shift_aft" id="shift_aft" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">STERN POWER AVAILABLE</span>
									<input type="text" name="shift_stern_power" id="shift_stern_power" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">IMMEDIATELY (SECONDS LATER)</span>
									<input type="text" name="shift_immediately" id="shift_immediately" class="form-control" readonly>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12 text-center">
								<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary">
									SAVE ALL SHIFTING INFORMATION
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