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
						action="<?php echo site_url("report/saveArrivalReportVesselandPilotageCertificateInfo");?>" >
						<input type="hidden" id="igmId" name="igmId" value="<?php echo $igm_mst_id; ?>" >
						<input type="text" id="vvdGkey" name="vvdGkey" value="<?php echo $vvd_gkey?>" >
						<input type="hidden" id="rotation" name="rotation" value="<?php echo $rotation;?>" >
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">VESSEL NAME</span>
									<input type="text" name="vesselName" id="vesselName" class="form-control" placeholder="AIN Number" 
										value="<?php echo $Vessel_Name; ?>" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">CALL SIGN</span>
									<input type="text" name="callsign" id="callsign" class="form-control" placeholder="CALL SIGN" 
										value="<?php echo $radio_call_sign; ?>" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">FLAG</span>
									<input type="text" name="flag" id="flag" class="form-control" placeholder="COUNTRY NAME" 
										value="<?php echo $cntry_name; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NAME OF MASTER</span>
									<input type="text" name="masterName" id="masterName" class="form-control" placeholder="MASTER NAME" 
										value="<?php echo $Name_of_Master; ?>" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">GRT</span>
									<input type="text" name="grt" id="grt" class="form-control" placeholder="GROSS REGISTERED TON" 
										value="<?php echo $gross_registered_ton; ?>" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NRT</span>
									<input type="text" name="nrt" id="nrt" class="form-control" placeholder="NET REGISTERED TON" 
										value="<?php echo $net_registered_ton; ?>" readonly>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DECK CARGO</span>
									<input type="text" name="deckCargo" id="deckCargo" class="form-control" placeholder="DECK CARGO" 
										value="<?php echo $Deck_cargo; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">LOA</span>
									<input type="text" name="loa" id="loa" class="form-control" placeholder="LOA" 
										value="<?php echo $loa_cm; ?>" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">MAX. FW DRAUGHT </span>
									<input type="text" name="maxFWdraught" id="maxFWdraught" class="form-control" 
									value="<?php echo $draught; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-5">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NUMBER OF CREW & OFFICER INCLUSIVE MASTER</span>
									<input type="text" name="crewNumber" id="crewNumber" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-7">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NAME AND ADDRESS OF OWNERS</span>
									<input type="text" name="ownerInfo" id="ownerInfo" class="form-control" 
										value="<?php echo $owner_name; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">LOCAL AGENT</span>
									<input type="text" name="localAgent" id="localAgent" class="form-control" 
									value="<?php echo $localagent; ?>" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">LAST PORT</span>
									<input type="text" name="lastPort" id="lastPort" class="form-control" 
									value="<?php echo $last_port_doc_vsl_info; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NEXT PORT</span>
									<input type="text" name="nextPort" id="nextPort" class="form-control" 
									value="<?php echo $next_port_doc_vsl_info; ?>">
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">PILOT NAME</span>
									<select class="form-control" name="pilotName" id="pilotName" required>
										<option value="">--Select--</option>
										<?php for($i=0;$i<count($pilot_list);$i++){ ?>
											<option value="<?php echo $pilot_list[$i]["login_id"];?>" 
												<?php if($pilot_list[$i]["login_id"]==$pilot_login) { ?> selected <?php } ?>>
												<?php echo $pilot_list[$i]["u_name"];?>
											</option>
										<?php } ?>
									</select>
									<!--input type="text" name="pilotName" id="pilotName" class="form-control" 
									value="<?php echo $pilot_name; ?>"-->
									
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BOARDED</span>
									<input type="text" name="boardedTime" id="boardedTime" class="form-control" 
									value="<?php if($pilot_on_board=="0000-00-00 00:00:00") echo ""; else echo $pilot_on_board; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">LEFT</span>
									<input type="text" name="leftTime" id="leftTime" class="form-control" 
									value="<?php if($pilot_off_board=="0000-00-00 00:00:00") echo ""; else echo $pilot_off_board; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">PILOTAGE FROM</span>
									<input type="text" name="pilotageFrom" id="pilotageFrom" class="form-control" 
									value="<?php echo $pilot_frm; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">PILOTAGE TO</span>
									<input type="text" name="pilotageTo" id="pilotageTo" class="form-control" 
									value="<?php echo $pilot_to; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">FW. DRAFT (MAX)</span>
									<input type="text" name="fwDraftMax" id="fwDraftMax" class="form-control" readonly>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">MOORING TIME (FROM)</span>
									<input type="text" name="mooringTimeFrm" id="mooringTimeFrm" class="form-control" 
									value="<?php if($mooring_frm_time=="0000-00-00 00:00:00") echo ""; else echo $mooring_frm_time; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">MOORING TIME (TO)</span>
									<input type="text" name="mooringTimeTo" id="mooringTimeTo" class="form-control" 
									value="<?php if($mooring_to_time=="0000-00-00 00:00:00") echo ""; else echo $mooring_to_time; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">CPA TUG/TUGS(NAME)</span>
									<input type="text" name="cpaTugName" id="cpaTugName" class="form-control" 
									value="<?php echo $tug_name; ?>">
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">ASSISTANCE FROM</span>
									<input type="text" name="cpaTugAssisFrm" id="cpaTugAssisFrm" class="form-control" 
									value="<?php if($assit_frm=="0000-00-00 00:00:00") echo ""; else echo $assit_frm?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">ASSISTANCE TO</span>
									<input type="text" name="cpaTugAssisTo" id="cpaTugAssisTo" class="form-control" 
									value="<?php if($assit_to=="0000-00-00 00:00:00") echo ""; else echo $assit_to; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">ARRIVAL AT OUTER ANCHORAGE DATE</span>
									<input type="text" name="arrivalOuterAnchorageDate" id="arrivalOuterAnchorageDate" class="form-control" 
									value="<?php if($oa_dt=="0000-00-00 00:00:00") echo ""; else echo $oa_dt; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">IF WORKED AS LIGHTER, NAME OF MOTHER VESSEL</span>
									<input type="text" name="motherVesselName" id="motherVesselName" class="form-control" readonly>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">WORKED AT OUTER ANCHORAGE /OUTAGE PORT LIMIT(TICK)</span>
									<input type="text" name="anchoaragePortLimit" id="anchoaragePortLimit" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">DANGEROUS CARGO (IF ANY)</span>
									<input type="text" name="dangerCargo" id="dangerCargo" class="form-control" readonly>
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
											<input class="form-check-input" type="radio" name="is_night" id="is_night" value="1" 
											<?php if($is_night=="1") echo "checked";?> required />
											<label class="form-check-label">YES</label>
										</div>
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_night" id="is_night" value="0" 
											<?php if($is_night=="0") echo "checked";?> />
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
											<input class="form-check-input" type="radio" name="is_holiday" id="is_holiday" 
											value="1" <?php if($is_holiday=="1") echo "checked";?> required />
											<label class="form-check-label">YES</label>
										</div>
										<div class="form-check form-check-inline" style="display:inline-block;">
											<input class="form-check-input" type="radio" name="is_holiday" id="is_holiday" value="0" 
											<?php if($is_holiday=="0") echo "checked";?>/>
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
									<input type="text" name="nosGoodMooringLinesFord" id="nosGoodMooringLinesFord" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">NOS. OF GOOD MOORING LINES: AFT</span>
									<input type="text" name="nosGoodMooringLinesAFT" id="nosGoodMooringLinesAFT" class="form-control" 
									readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">STERN POWER AVAILABLE</span>
									<input type="text" name="sternPowerAvailable" id="sternPowerAvailable" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">IMMEDIATELY</span>
									<input type="text" name="sternImmediately" id="sternImmediately" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">REMARKS IF ANY</span>
									<input type="text" name="arrivalRemarks" id="arrivalRemarks" class="form-control"
									value="<?php echo $remarks; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">ARRIVAL DATE</span>
									<input type="text" name="arrivalDate" id="arrivalDate" class="form-control" 
									value="<?php echo date("Y-m-d", strtotime($mooring_frm_time));?>" readonly>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12 text-center">
								<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary">
									SAVE ARRIVAL AND PILOTAGE INFO
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