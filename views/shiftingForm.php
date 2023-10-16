 <style>
 #table-scroll {
  height:600px;
  width:850px;
  overflow:auto;  
  margin-top:20px;
}
table{
	//border: 1px solid black;
    table-layout: fixed;
    width: 100%;
}

th, td {
	//border: 1px solid black;
}
.special_column { width: 20px; }

input[type="text"], textarea {

  background-color : #F7EEC0; 

}
 </style>
<script>
	function checkCond(n) 
    {
		var shift_good_cond_yes = document.getElementById('shift_good_cond_yes');
		var shift_good_cond_no = document.getElementById('shift_good_cond_no');
		
		var shift_two_anchors_yes = document.getElementById('shift_two_anchors_yes');
		var shift_two_anchors_no = document.getElementById('shift_two_anchors_no');
		
		var shift_rudded_indicator_yes = document.getElementById('shift_rudded_indicator_yes');
		var shift_rudded_indicator_no = document.getElementById('shift_rudded_indicator_no');
		
		var shift_rpm_indicator_yes = document.getElementById('shift_rpm_indicator_yes');
		var shift_rpm_indicator_no = document.getElementById('shift_rpm_indicator_no');
		
		var shift_bow_thurster_yes = document.getElementById('shift_bow_thurster_yes');
		var shift_bow_thurster_no = document.getElementById('shift_bow_thurster_no');
		
		var shift_solas_convention_yes = document.getElementById('shift_solas_convention_yes');
		var shift_solas_convention_no = document.getElementById('shift_solas_convention_no');
	  
		if(n === 0)
		{  
			shift_good_cond_yes.checked = true;
			shift_good_cond_no.checked = false;  
		}	
		else if (n === 1) 
		{   
			shift_good_cond_yes.checked = false;
			shift_good_cond_no.checked = true;  
		}
		else if(n === 2)
		{  
			shift_two_anchors_yes.checked = true;  
			shift_two_anchors_no.checked = false;
		}
		else if (n === 3) 
		{   
			shift_two_anchors_yes.checked = false;  
			shift_two_anchors_no.checked = true;
		}
		else if(n === 4)
		{  
			shift_rudded_indicator_yes.checked = true;  
			shift_rudded_indicator_no.checked = false;
		}
		else if (n === 5) 
		{   
			shift_rudded_indicator_yes.checked = false;  
			shift_rudded_indicator_no.checked = true;
		}		
		else if(n === 6)
		{  
			shift_rpm_indicator_yes.checked = true;  
			shift_rpm_indicator_no.checked = false;
		}
		else if (n === 7) 
		{   
			shift_rpm_indicator_yes.checked = false;  
			shift_rpm_indicator_no.checked = true;
		}
		else if(n === 8)
		{  
			shift_bow_thurster_yes.checked = true;  
			shift_bow_thurster_no.checked = false;
		}
		else if (n === 9) 
		{   
			shift_bow_thurster_yes.checked = false;  
			shift_bow_thurster_no.checked = true;
		}
		else if(n === 10)
		{  
			shift_solas_convention_yes.checked = true;  
			shift_solas_convention_no.checked = false;
		}
		else if (n === 11) 
		{   
			shift_solas_convention_yes.checked = false;  
			shift_solas_convention_no.checked = true;
		}
    }
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/report/shifting_insert'; ?>" target="_blank" id="shifting_form" name="shifting_form" onsubmit="return validate()">
						<div align="center"><?php echo @$msg;?></div>
						</div>
							<table border="0" style="color:black">
									<tr align="center">
										<td colspan="10"> <b><font color="black">THE CHITTAGONG PORT AUTHORITY</font></b> </td>
									</tr>
									<tr align="center">
										<td colspan="10"> DEPARTURE REPORT OF VESSEL </BR> AND </td>
									</tr>
									<tr align="center">
										<td colspan="10"> PILOTAGE CERTIFICATE FOR SHIFTING <br><font color="blue"><?php echo @$insert_update_msg; ?></font></td>
									</tr>
									<tr align="left">
										<input type="hidden" name="shift_igm_id" id="shift_igm_id" value="<?php echo @$rtnVesselDetails_igm[0]['id']; ?>"/>
										<input type="hidden" name="shift_vvd_gkey" id="shift_vvd_gkey" value="<?php echo @$rtnVesselDetails_n4[0]['VVD_GKEY']; ?>"/>
										<input type="hidden" name="shift_rotation" id="shift_rotation" value="<?php echo @$shift_rotation; ?>"/>
										<td colspan="2">1. VESSEL NAME</td>
										<td colspan="2"><input type="text" name="shift_vsl_name" id="shift_vsl_name" style="width: 99%;" value="<?php echo @$rtnVesselDetails_igm[0]['Vessel_Name']; ?>"/></td>
										<td align="right">CALL SIGN</td>
										<td colspan="2"><input type="text" name="shift_cal_sign" id="shift_cal_sign" style="width: 99%;" value="<?php echo @$rtnVesselDetails_n4[0]['RADIO_CALL_SIGN']; ?>"/></td>
										<td align="right">FLAG</td>
										<td colspan="2"><input type="text" name="shift_vsl_flag" id="shift_vsl_flag" style="width: 99%;" value="<?php echo @$rtnVesselDetails_n4[0]['FLAG']; ?>"/></td>
									</tr>
									<tr align="left">
										<td colspan="2">2. NAME OF MASTER</td>
										<td colspan="8"><input type="text" name="shift_master_name" id="shift_master_name" value="<?php echo @$rtnVesselDetails_igm[0]['Name_of_Master']; ?>"/></td>
									</tr>
									<tr align="left">
										<td>3. GRT</td>
										<td colspan="2"><input type="text" name="shift_grt" id="shift_grt" style="width: 99%;" value="<?php echo @$rtnVesselDetails_n4[0]['GROSS_REGISTERED_TON']; ?>"/></td>
										<td align="right">   NRT</td>
										<td colspan="2"><input type="text" name="shift_nrt" id="shift_nrt" style="width: 99%;" value="<?php echo @$rtnVesselDetails_n4[0]['NET_REGISTERED_TON']; ?>"/></td>
										<td colspan="2" align="right">   DECK CARGO</td>
										<td colspan="2"><input type="text" name="shift_deck_cargo" id="shift_deck_cargo" value="<?php echo @$rtnVesselDetails_igm[0]['Deck_cargo']; ?>" style="width: 99%;"/></td>
									</tr>
										<td>4. LOA</td>
										<td colspan="3"><input type="text" name="shift_loa" id="shift_loa" style="width: 99%;" value="<?php echo @$rtnVesselDetails_n4[0]['LOA_CM']; ?>"/></td>
										<td colspan="3" align="right">  MAX FW DRAUGHT</td>
										<td colspan="3"><input type="text" name="shift_max" id="shift_max" style="width: 99%;"/></td>
									</tr>
									<tr align="left">
										<td colspan="4">5. NAME AND ADDRESS OF OWNERS</td>
										<td colspan="6"><input type="text" name="shift_address_of_owners" id="shift_address_of_owners" style="width: 99%;"/></td>
									</tr>
									<tr align="left">
										<td colspan="2">6. LOCAL AGENT</td>
										<td colspan="8"><input type="text" name="shift_local_agent" id="shift_local_agent" style="width: 99%;" value="<?php echo @$rtnVesselDetails_n4[0]['LOCALAGENT']; ?>"/></td>
									</tr>
									<tr align="left">
										<td colspan="2">7. NAME OF PILOT</td>
										<td><input type="text" name="shift_name_of_pilot" id="shift_name_of_pilot" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['pilot_name'] ?>"/></td>
										<td colspan="2" align="right">   BOARDED AT</td>
										<td><input type="text" name="shift_boarded_at" id="shift_boarded_at" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['pilot_on_board'] ?>"/></td>
										<td align="right">   LEFT AT</td>
										<td><input type="text" name="shift_left_at" id="shift_left_at" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['pilot_off_board'] ?>"/></td>
									</tr>
									<tr align="left">
										<td colspan="2">8. SHIFTED/SWUNG FROM</td>
										<td colspan="2"><input type="text" name="shift_shifted_from" id="shift_shifted_from" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['shift_frm'] ?>"/></td>
										<td align="right">   TO</td>
										<td colspan="2"><input type="text" name="shift_shifted_to" id="shift_shifted_to" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['shift_to'] ?>"/></td>
									</tr>
									<tr align="left">
										<td colspan="4">9. TIME OF MOORING/UNMOORING FROM</td>
										<td colspan="1"><input type="text" name="shift_mooring_unmooring_from" id="shift_mooring_unmooring_from" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['mooring_frm_time'] ?>"/></td>
										<td align="right">    TO</td>
										<td colspan="1"><input type="text" name="shift_mooring_unmooring_to" id="shift_mooring_unmooring_to" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['mooring_to_time'] ?>"/></td>
										<td align="right">    DT</td>
										<td colspan="2"><input type="text" name="shift_mooring_unmooring_date" id="shift_mooring_unmooring_date" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['shift_dt'] ?>"/></td>
										<script>
											$(function() {
											$( "#shift_mooring_unmooring_date" ).datepicker({
												changeMonth: true,
												changeYear: true,
												dateFormat: 'yy-mm-dd', // iso format
											});
											});
										</script>
									</tr>
									<tr align="left">
										<td colspan="2">10. CPA TUG/TUGS(NAME)</td>
										<td><input type="text" name="shift_cpa_tug" id="shift_cpa_tug" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['tug_name'] ?>"/></td>
										<td colspan="2" align="right">    ASSISTANCE FROM</td>
										<td><input type="text" name="shift_assistance_from" id="shift_assistance_from" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['assit_frm'] ?>"/></td>
										<td align="right">    TO</td>
										<td><input type="text" name="shift_assistance_to" id="shift_assistance_to" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['assit_to'] ?>"/></td>
										<td align="right">    DT</td>
										<td><input type="text" name="shift_assistance_date" id="shift_assistance_date" style="width: 99%;" value="<?php echo @$rslt_show_current_data[0]['shift_dt'] ?>"/></td>
										<script>
											$(function() {
											$( "#shift_assistance_date" ).datepicker({
												changeMonth: true,
												changeYear: true,
												dateFormat: 'yy-mm-dd', // iso format
											});
											});
										</script>
									</tr>
									<tr align="left">
										<td colspan="6">11. MAIN ENGINES IN GOOD WORKING CONDITION?</td>
										<td colspan="1">
											<input onclick="checkCond(0)" type="checkbox" class="radio" value="1" name="shift_good_cond_yes" id="shift_good_cond_yes" style="width: 99%;" checked/>
										</td>
										<td>
											YES
										</td>
										<td colspan="1">
											<input onclick="checkCond(1)" type="checkbox" class="radio" value="1" name="shift_good_cond_no" id="shift_good_cond_no" style="width: 99%;"/>
										</td>
										<td>
											NO
										</td>
									</tr>
									<tr align="left">
										<td colspan="6">12. TWO ANCHORS IN GOOD WORKING CONDITION?</td>
										<td colspan="1">
											<input onclick="checkCond(2)" type="checkbox" class="radio" value="1" name="shift_two_anchors_yes" id="shift_two_anchors_yes" style="width: 99%;" checked/>
										</td>
										<td>
											YES
										</td>
										<td colspan="1">
											<input onclick="checkCond(3)" type="checkbox" class="radio" value="1" name="shift_two_anchors_no" id="shift_two_anchors_no" style="width: 99%;"/>
										</td>
										<td>
											NO
										</td>
									</tr>
									<tr align="left">
										<td colspan="6">13. RUDDER INDICATOR IN GOOD WORKING CONDITION?</td>
										<td colspan="1">
											<input onclick="checkCond(4)" type="checkbox" class="radio" value="1" name="shift_rudded_indicator_yes" id="shift_rudded_indicator_yes" style="width: 99%;" checked/>
										</td>
										<td>
											YES
										</td>
										<td colspan="1">
											<input onclick="checkCond(5)" type="checkbox" class="radio" value="1" name="shift_rudded_indicator_no" id="shift_rudded_indicator_no" style="width: 99%;"/>
										</td>
										<td>
											NO
										</td>
									</tr>
									<tr align="left">
										<td colspan="6">14. RPM INDICATOR IN GOOD WORKING CONDITION?</td>
										<td colspan="1">
											<input onclick="checkCond(6)" type="checkbox" class="radio" value="1" name="shift_rpm_indicator_yes" id="shift_rpm_indicator_yes" style="width: 99%;" checked/>
										</td>
										<td>
											YES
										</td>
										<td colspan="1">
											<input onclick="checkCond(7)" type="checkbox" class="radio" value="1" name="shift_rpm_indicator_no" id="shift_rpm_indicator_no" style="width: 99%;"/>
										</td>
										<td>
											NO
										</td>
									</tr>
									<tr align="left">
										<td colspan="6">15. BOW THURSTER AVAILABLE?</td>
										<td colspan="1">
											<input onclick="checkCond(8)" type="checkbox" class="radio" value="1" name="shift_bow_thurster_yes" id="shift_bow_thurster_yes" style="width: 99%;" checked/>
										</td>
										<td>
											YES
										</td>
										<td colspan="1">
											<input onclick="checkCond(9)" type="checkbox" class="radio" value="1" name="shift_bow_thurster_no" id="shift_bow_thurster_no" style="width: 99%;"/>
										</td>
										<td>
											NO
										</td>
									</tr>
									<tr align="left">
										<td colspan="6">16. ARE YOU COMPLYING SOLAS CONVENTION?</td>
										<td colspan="1">
											<input onclick="checkCond(10)" type="checkbox" class="radio" value="1" name="shift_solas_convention_yes" id="shift_solas_convention_yes" style="width: 99%;" checked/>
										</td>
										<td>
											YES
										</td>
										<td colspan="1">
											<input onclick="checkCond(11)" type="checkbox" class="radio" value="1" name="shift_solas_convention_no" id="shift_solas_convention_no" style="width: 99%;"/>
										</td>
										<td>
											NO
										</td>
									</tr>
									<tr align="left">
										<td colspan="5">17. NOS. OF GOOD MOORING LINES: FORD</td>
										<td colspan="2"><input type="text" name="shift_no_of_good_mooring" id="shift_no_of_good_mooring" style="width: 99%;"/></td>
										<td>AFT</td>
										<td colspan="2"><input type="text" name="shift_aft" id="shift_aft" style="width: 99%;"/></td>
									</tr>
									<tr align="left">
										<td colspan="4">18. STERN POWER AVAILABLE:</td>
										<td colspan="1"><input type="text" name="shift_stern_power" id="shift_stern_power" style="width: 99%;"/></td>
										<td colspan="2"> IMMEDIATELY</td>
										<td colspan="1"><input type="text" name="shift_immediately" id="shift_immediately" style="width: 99%;"/></td>
										<td colspan="2"> SECS.LATER</td>						
									</tr>
									<tr>
										<td colspan="10">&nbsp;</td>
									</tr>
									<tr colspan="10" align="center">
										<td colspan="10"><input class="login_button" style="width: 20%;" name="shift_saveBtn" id="shift_saveBtn" type="submit" value="Save All Shifting Information"/> </td>
									</tr>
									<!--tr colspan="10" align="center">
										<td colspan="10">
											<a href="<?php echo site_url('report/shifting_pdf/'.str_replace("/","_",$shift_rotation))?>" target="_blank" method="POST">
												PDF
											</a>
										</td>
									</tr-->
							</table>

						</form>
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>
