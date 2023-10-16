<HTML>
	<HEAD>
		<!--TITLE>BLOCKED CONTAINER LIST</TITLE-->
	    <style type="text/css">
        </style>
	</HEAD>
	<BODY style="background-color: green;">
		<?php if($activity_for=="incoming"){ ?>
			<div style="background-color: lightblue;height: 100%;">
			<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0'  >
					<tr align="center">
						<td  colspan="10" align="center">  
							<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
						</td>
					</tr>				
					<tr>
						<td  align="center" style="border:none;"><font size="5"><b><u>THE CHITTAGONG PORT AUTHORITY</u></b></font></td>
					</tr>
					<tr>
						<td  align="center" style="border:none;"><font size="5"><b>ARRIVAL REPORT OF VESSEL AND PILOTAGE CERTIFICATE</b></font></td>	
					</tr>
			</table>
			<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0' style="line-height:20px;">	
				<tr>
					<td colspan="4" style="font-size: 15px;">1. VESSELS NAME :<u><?php echo $rtnVesselDetails_igm[0]['Vessel_Name'];?></u></td>
					<td colspan="2" style="font-size: 15px;">CALL SIGN :<u><?php echo $rtnVesselDetails_n4[0]['radio_call_sign'];?></u></td>
					<td colspan="4" style="font-size: 15px;">FLAG : <u><?php echo $rtnVesselDetails_n4[0]['cntry_name'];?></u></td>
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;" >2. NAME OF MASTER : <u><?php echo $rtnVesselDetails_igm[0]['Name_of_Master'];?></u></td>
				</tr>
				<tr>                                
					<td colspan="4" style="font-size: 15px;">3. GRT : <u><?php echo $rtnVesselDetails_n4[0]['gross_registered_ton'];?></u></td>
					<td colspan="2" style="font-size: 15px;">NRT : <u><?php echo $rtnVesselDetails_n4[0]['net_registered_ton'];?></u></td>
					<td colspan="4" style="font-size: 15px;">DECK CARGO : <u><?php echo $rtnVesselDetails_igm[0]['Deck_cargo'];?></u></td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">4. LOA : <u><?php echo $rtnVesselDetails_n4[0]['loa_cm'];?></u></td>
					<td colspan="6" style="font-size: 15px;">MAX. FW DRAUGHT : <u><?php // echo $arrivalInfo42[0]['Vessel_Name'];?></u></td>	
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">5. NUMBER OF CREW & OFFICER INCLUSIVE MASTER : <u><?php echo $rtn_vsl_arrival_info[0]['Vessel_Name'];?></u></td>
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">6. NAME AND ADDRESS OF OWNERS: <u><?php echo $rtnVesselDetails_igm[0]['Vessel_Name'];?></u></td>						
				</tr>
				<tr>
					<td colspan="3" style="font-size: 15px;">7. LOCAL AGENT : <u><?php echo $rtnVesselDetails_n4[0]['localagent'];?></u></td>
					<td colspan="3" style="font-size: 15px;">LAST PORT : <u><?php // echo $arrivalInfo42[0]['Vessel_Name']?></u></td>
					<td colspan="4" style="font-size: 15px;">NEXT PORT : <u><?php // echo $arrivalInfo42[0]['Vessel_Name']?></u></td>	
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">8. NAME OF PILOT : <u><?php echo $pilot_name;?></u></td>
					<td colspan="3" style="font-size: 15px;">BOARDED : <u><?php echo $rtn_vsl_arrival_info[0]['pilot_on_board'];?></u></td>
					<td colspan="3" style="font-size: 15px;">LEFT : <u><?php echo $rtn_vsl_arrival_info[0]['pilot_off_board'];?></u></td>	
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px;">9. PILOTAGE FROM : <u><?php echo $rtn_vsl_arrival_info[0]['pilot_frm'];?></u></td>	
					<td colspan="5" style="font-size: 15px;">TO : <u><?php echo $rtn_vsl_arrival_info[0]['pilot_to'];?></u></td>						
				</tr>
				<tr> 
					<td colspan="5" style="font-size: 15px;">10.TIME OF MOORING FROM: <u><?php echo $rtn_vsl_arrival_info[0]['mooring_frm_time'];?></u></td>	
					<td colspan="5" style="font-size: 15px;">TO: <u><?php echo $rtn_vsl_arrival_info[0]['mooring_to_time'];?></u></td>						
				</tr>
				 <tr>
			
					<td colspan="4" style="font-size: 15px;">11.CPA TUG/TUGS(NAME): <u><?php echo $rtn_vsl_arrival_info[0]['aditional_tug'];?></u></td>	
					<td colspan="3" style="font-size: 15px;">ASSISTANCE FROM : <u><?php echo $rtn_vsl_arrival_info[0]['assit_frm'];?></u></td>	
					<td colspan="3" style="font-size: 15px;">TO : <u><?php echo $rtn_vsl_arrival_info[0]['assit_to'];?></u></td>						
				</tr>
				<tr> 
					<td colspan="5" style="font-size: 15px;">12.ARRIVAL AT OUTER ANCHORAGE DATE : <u><?php echo $rtn_vsl_arrival_info[0]['oa_dt'];?></u></td>		
					<td colspan="5" style="font-size: 15px;">FW. DRAFT (MAX): <u><?php // echo $rtn_vsl_arrival_info[0]['Vessel_Name']?></u></td>						
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">13.IF WORKED AS LIGHTER, NAME OF MOTHER VESSEL : <u><?php // echo $rtn_vsl_arrival_info[0]['Vessel_Name']?></u></td>						
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">14.WORKED AT OUTER ANCHORAGE / OUTAGE PORT LIMIT.(TICK)</td>						
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">15.DANGEROUS CARGO IF ANY :</td>						
			  </tr>
			  <tr>
					<td colspan="6" style="font-size: 15px;">16.MAIN ENGINES IN GOOD WORKING CONDITION?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($rtn_vsl_arrival_info[0]['is_main_engine_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($rtn_vsl_arrival_info[0]['is_main_engine_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				<tr>
					<td colspan="6" style="font-size: 15px;">17.TWO ANCHORS IN GOOD WORKING CONDITION?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($rtn_vsl_arrival_info[0]['is_acnchors_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($rtn_vsl_arrival_info[0]['is_acnchors_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
			
				<tr>
					<td colspan="6" style="font-size: 15px;">18.RUDDER INDICATOR IN GOOD WORKING CONDITION?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($rtn_vsl_arrival_info[0]['is_rudder_indicator_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($rtn_vsl_arrival_info[0]['is_rudder_indicator_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				<tr>
					<td colspan="6" style="font-size: 15px;">19.RPM INDICATOR IN GOOD WORKING CONDTION?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($rtn_vsl_arrival_info[0]['is_rpm_indicator_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($rtn_vsl_arrival_info[0]['is_rpm_indicator_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
			
				<tr>
					<td colspan="6" style="font-size: 15px;">20.BOW THRUSTER AVAILABLE?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($rtn_vsl_arrival_info[0]['is_bow_therster_available'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($rtn_vsl_arrival_info[0]['is_bow_therster_available'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				
				<tr>
					<td colspan="6" style="font-size: 15px;">21.ARE YOU COMPLYING SOLAS CONVENTION?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($rtn_vsl_arrival_info[0]['is_complying_soal_convention'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp; <?php if($rtn_vsl_arrival_info[0]['is_complying_soal_convention'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				
				<tr>
					<td colspan="6" style="font-size: 15px;">22.NOS OF GOOD MOORING LINES: FORD: </td>	
					<td colspan="4" style="font-size: 15px;">AFT :</td>			
				</tr>			
				<tr>
					<td colspan="4" style="font-size: 15px;">23.STERN POWER AVAILABLE :</td>	
					<td colspan="3" style="font-size: 15px;">IMMEDIATELY :  </td>	
					<td colspan="3" style="font-size: 15px;">SECS. LATER</td>	
					
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">24.REMARKS IF ANY :</td>
				</tr>
				<?php if($rtn_vsl_arrival_info[0]['is_night'] ==1){?>
				<tr>
					<td colspan="10" style="font-size: 15px;"><b>NIGHT SHIFT</b> </td>
				</tr>
				<?php }?>
				<?php if($rtn_vsl_arrival_info[0]['is_holiday'] ==1){?>
				<tr>
					<td colspan="10" style="font-size: 15px;"><b>HOLIDAY</b></td>
				</tr>
				<?php }?>
				
				
				<tr>
					<td colspan="10">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px; text-align: left;">CERTIFIED THAT THE ABOVE PARTICULARS ARE CORRECT AND CHARGES THEREOF WILL BE PAID BY US/LOCAL AGENT INCLUSIVE OF OTHER PORT CHARGES.</td>						
				</tr>
				<tr>
					<td colspan="5"  style="font-size: 15px; text-align: center;"><?php echo date('Y-m-d', strtotime($rtn_vsl_arrival_info[0]['pilot_off_board']) ?> </td>
					<td colspan="5"  style="font-size: 15px; text-align: center;"><?php if($rtn_vsl_arrival_info[0]['photo_base_64'] != "" || $rtn_vsl_arrival_info[0]['photo_base_64'] != null){ ?><img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $rtn_vsl_arrival_info[0]['photo_base_64']; ?>"/> <?php } ?></td>
				</tr>

				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------</td>	
					<td colspan="5" style="font-size: 15px; text-align: center;">--------------------------------</td>		
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;">DATE</td>	
					<td colspan="5" style="font-size: 15px; text-align: center;">MASTER</td>	
				</tr>

				<tr>
					<td colspan="10" style="font-size: 15px; text-align: left;">FORWARDED TO THE CHIEF FINANCE & ACCOUNTS OFFICER,PORT AUTHORITY CHITTAGONG FOR NECESSARY ACTION.</td>						
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;"> <?php if(count($rtn_vsl_arrival_info)>0) { ?> <img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php echo $rtn_vsl_arrival_info[0]['pilot_name'];?>.png"/> <?php  } else { echo ""; } ?></td>
					<td colspan="5" style="font-size: 15px; text-align: center;">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------------</td>	
					<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------------</td>
				</tr>
				<tr>
					<td  colspan="5" style="font-size: 15px; text-align: center;" >AHM/PILOT</td>	
					<td  colspan="5" style="font-size: 15px; text-align: center;">DEPUTY CONSERVATOR/HARBOUR MASTER</td>
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;"><u>CHITTAGONG PORT AUTHORITY</u></td>	
					<td colspan="5" style="font-size: 15px; text-align: center;"><u>CHITTAGONG PORT AUTHORITY</u></td>	
				</tr>
			</table>
		</div>
		<?php }else if ($activity_for=="shifting") { ?>
			<div style="background-color: #eaf9a7;height: 100%;">
				<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0'>
					<tr align="center">
						<td  colspan="10" align="center">  
							<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
						</td>
					</tr>
					
					<tr align="center">
						<td  colspan="10" align="center" style="border:none;"><font size="5"><b><u>THE CHITTAGONG PORT AUTHORITY2</u></b></font></td>
					</tr>
					<tr align="center">
						<td colspan="10"  align="center" style="border:none;"><font size="5"><b>PILOTAGE CERTIFICATE FOR SHIFTING</b></font></td>
					</tr>
					
				</table>
				<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0' style="line-height:20px; ">	
					<tr>
						<td colspan="4" style="font-size: 15px;">1. VESSEL NAME : <u><?php echo $rtnVesselDetails_igm[0]['Vessel_Name']; ?></u></td>
						<td colspan="3" style="font-size: 15px;">CALL SIGN : <u><?php echo $rtnVesselDetails_n4[0]['radio_call_sign']; ?></u></td>
						<td colspan="3" style="font-size: 15px;">FLAG : <u><?php echo $rtnVesselDetails_n4[0]['flag']; ?></u></td>
					</tr>
					<tr>
						<td colspan="10"  style="font-size: 15px;">2. NAME OF MASTER : <u><?php echo $rtnVesselDetails_igm[0]['Name_of_Master']; ?></u></td>
					</tr>
					<tr>
						<td colspan="4"  style="font-size: 15px;" >3. GRT : <u><?php echo $rtnVesselDetails_n4[0]['gross_registered_ton']; ?></u></td>
						<td colspan="3"  style="font-size: 15px;">NRT : <u><?php echo $rtnVesselDetails_n4[0]['net_registered_ton']; ?></u></td>
						<td colspan="3"  style="font-size: 15px;">DECK CARGO : <u><?php echo $rtnVesselDetails_igm[0]['Deck_cargo']; ?></u></td>
					</tr>
					<tr>
						<td colspan="4"  style="font-size: 15px;">4. LOA : <u><?php echo $rtnVesselDetails_n4[0]['loa_cm']; ?></u></td>
						<td colspan="6"  style="font-size: 15px;">MAX FW DRAUGHT : <u><?php echo $rtnVesselDetails_n4[0]['beam_cm']; ?></u></td>

					</tr>
					<tr>
						<td colspan="10"  style="font-size: 15px;">5. NAME AND ADDRESS OF OWNERS :</td>
					</tr>
					<tr>
						<td colspan="10"  style="font-size: 15px;">6. LOCAL AGENT : <u><?php echo $rtnVesselDetails_n4[0]['localagent']; ?></u></td> 
					</tr>
					<tr>
						<td colspan="4"  style="font-size: 15px;">7. NAME OF PILOT : <u><?php if($pilot_name !=""){ echo $pilot_name;}else { echo ""; } ?></u></td>
						<td colspan="3"  style="font-size: 15px;">BOARDED AT : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['pilot_on_board']; } else { echo ""; } ?></u></td>
						<td colspan="2"  style="font-size: 15px;">LEFT AT : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['pilot_off_board']; } else { echo ""; } ?></u></td>
						<td colspan="1"  style="font-size: 15px;">DT :</td>
					</tr>
					<tr>
						<td colspan="4"  style="font-size: 15px;">8. SHIFTED/SWUNG FROM : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['shift_frm']; } else {echo "";} ?></u></td>
						<td colspan="6"  style="font-size: 15px;">   TO : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['shift_to']; } else {echo "";} ?></u></td>
					</tr>
					<tr>
						<td colspan="4"  style="font-size: 15px;" >9. TIME OF MOORING/UNMOORING FROM : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['mooring_to_time']; } else {echo "";} ?></u></td>
						<td colspan="3"  style="font-size: 15px;">TO : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['mooring_frm_time']; } else {echo "";} ?></u></td>
						<td colspan="3"  style="font-size: 15px;">DT : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['shift_dt']; } else {echo "";} ?></u></td>
					</tr>
					<tr>
						<td colspan="4"  style="font-size: 15px;">10. CPA TUG/TUGS(NAME) : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['aditional_tug']; } else {echo "";} ?></u></td>
						<td colspan="3"  style="font-size: 15px;">ASSISTANCE FROM : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['assit_frm']; } else {echo "";} ?></u></td>
						<td colspan="2"  style="font-size: 15px;">TO : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['assit_to']; } else {echo "";} ?></u></td>
						<td colspan="1"  style="font-size: 15px;">DT : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['shift_dt']; } else {echo "";} ?></u></td>
					</tr>
					
				
					<tr>
						<td  colspan="6" style="font-size: 15px;">11.MAIN ENGINES IN GOOD WORKING CONDITION?</td> 
						<td colspan="4" style="font-size: 15px;"><?php if($rslt_show_current_data[0]['is_main_engine_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($rslt_show_current_data[0]['is_main_engine_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				
					</tr>
					<tr>
						<td colspan="6" style="font-size: 15px;">12.TWO ANCHORS IN GOOD WORKING CONDITION?</td> 
						<td colspan="4" style="font-size: 15px;"><?php if($rslt_show_current_data[0]['is_acnchors_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($rslt_show_current_data[0]['is_acnchors_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
					</tr>
					
					<tr>
						<td colspan="6" style="font-size: 15px;">13.RUDDER INDICATOR IN GOOD WORKING CONDITION?</td> 
						<td colspan="4" style="font-size: 15px;"><?php if($rslt_show_current_data[0]['is_rudder_indicator_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp; <?php if($rslt_show_current_data[0]['is_rudder_indicator_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
					</tr>
					
					<tr>
						<td colspan="6" style="font-size: 15px;">14.RPM INDICATOR IN GOOD WORKING CONDITION?</td> 
						<td colspan="4" style="font-size: 15px;"><?php if($rslt_show_current_data[0]['is_rpm_indicator_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($rslt_show_current_data[0]['is_rpm_indicator_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
					</tr>
					<tr>
						<td colspan="6" style="font-size: 15px;">15.BOW THURSTER AVAILABLE?</td> 
						<td colspan="4" style="font-size: 15px;"><?php if($rslt_show_current_data[0]['is_bow_therster_available'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($rslt_show_current_data[0]['is_bow_therster_available'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
					</tr>
					<tr>
						<td colspan="6" style="font-size: 15px;">16.ARE YOU COMPLYING SOLAS CONVENTION?</td> 
						<td colspan="4" style="font-size: 15px;"><?php if($rslt_show_current_data[0]['is_complying_soal_convention'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($rslt_show_current_data[0]['is_complying_soal_convention'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
					</tr>
					<tr >
						<td colspan="6" style="font-size: 15px;">17. NOS. OF GOOD MOORING LINES: FORD : </td>
						<td colspan="4" style="font-size: 15px;">AFT : </td>
					</tr>
					<tr>
						<td colspan="4" style="font-size: 15px;" >18. STERN POWER AVAILABLE :</td>
						<td colspan="3" style="font-size: 15px;">IMMEDIATELY :</td>
						<td colspan="3" style="font-size: 15px;">SECS.LATER</td>						
					</tr>
					<tr>
						<td colspan="10" style="font-size: 15px;">19. REMARKS IF ANY :</td>
					</tr>
					<?php if($rslt_show_current_data[0]['is_night'] ==1){?>
					<tr>
						<td colspan="5"  style="font-size: 15px;"><b>NIGHT SHIFT</b> <?php // echo $rtn_vsl_arrival_info[0]['Vessel_Name']?></td>
					</tr>
					<?php }?>
					<?php if($rslt_show_current_data[0]['is_holiday'] ==1){?>
					<tr>
						<td colspan="5"  style="font-size: 15px;"><b>HOLIDAY</b> <?php // echo $rtn_vsl_arrival_info[0]['Vessel_Name']?></td>
					</tr>
					<?php }?>
					
					<!--ASIF START -->
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr>
						<td colspan="10" style="font-size: 15px; text-align: left;">CERTIFIED THAT THE ABOVE PARTICULARS ARE CORRECT AND CHARGES THEREOF WILL BE PAID BY US/LOCAL AGENT INCLUSIVE OF OTHER PORT CHARGES.</td>						
					</tr>
					
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr><td colspan="4">&nbsp;</td></tr>
					
					<tr>
						<td colspan="4">&nbsp;</td>
						<td colspan="6" style="font-size: 15px;" ><?php if($rtn_vsl_arrival_info[0]['photo_base_64'] != "" || $rtn_vsl_arrival_info[0]['photo_base_64'] != null){ ?><img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $rtn_vsl_arrival_info[0]['photo_base_64']; ?>"/> <?php } ?></td>
					</tr>

					<tr>
						<td colspan="5" style="font-size: 15px; text-align: center;"><u><?php echo $rtn_vsl_arrival_info[0]['sign_arraival']; ?></u></td>	
					</tr>
					<tr>
						<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------</td>	
						<td colspan="5" style="font-size: 15px; text-align: center;">--------------------------------</td>		
					</tr>
					<tr>
						<td colspan="5" style="font-size: 15px; text-align: center;">DATE</td>	
						<td colspan="5" style="font-size: 15px; text-align: center;">MASTER</td>	
					</tr>
					<tr><td colspan="5">&nbsp;</td></tr>
					<tr>
						<td colspan="10" style="font-size: 15px; text-align: left;">FORWARDED TO THE CHIEF FINANCE & ACCOUNTS OFFICER,PORT AUTHORITY CHITTAGONG FOR NECESSARY ACTION.</td>						
					</tr>
					<tr><td colspan="5">&nbsp;</td></tr>
					<tr><td colspan="5">&nbsp;</td></tr>
					<tr>
						<td colspan="5" style="font-size: 15px; text-align: center;"> <?php if(count($rslt_show_current_data)>0) { ?> <img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php echo $rslt_show_current_data[0]['pilot_name'];?>.png"/> <?php  } else { echo ""; } ?></td>
						<td colspan="5" style="font-size: 15px; text-align: center;">&nbsp;</td>
						
					</tr>
					<tr>
						<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------------</td>	
						<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------------</td>
						
					</tr>
					<tr>
						<td  colspan="5" style="font-size: 15px; text-align: center;" >AHM/PILOT</td>	
						<td  colspan="5" style="font-size: 15px; text-align: center;">DEPUTY CONSERVATOR/HARBOUR MASTER</td>
					</tr>
					<tr>
						<td colspan="5" style="font-size: 15px; text-align: center;"><u>CHITTAGONG PORT AUTHORITY</u></td>	
						<td colspan="5" style="font-size: 15px; text-align: center;"><u>CHITTAGONG PORT AUTHORITY</u></td>	
					</tr>
					<!-- ASIF END -->
				</table>
			</div>
		<?php }else if ($activity_for=="cancel") { ?>
			<div style="background-color:: #c2b7b1;height: 100%; ">
			<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0' style="line-height:20px;">
				<tr align="center">
					<td  colspan="10" align="center">  
						<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
					</td>
				</tr>
				
				<tr align="center">
					<td  colspan="10" align="center" style="border:none;"><font size="5"><b><u>THE CHITTAGONG PORT AUTHORITY2</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="10"  align="center" style="border:none;"><font size="5"><b>PILOTAGE CERTIFICATE FOR CANCELLATION OF MOVEMENT</b></font></td>
				</tr>
				<tr><td colspan="10">&nbsp;</td></tr>
				<tr><td colspan="10">&nbsp;</td></tr>
				<tr><td colspan="10">&nbsp;</td></tr>
				<tr><td colspan="10">&nbsp;</td></tr>
				
				<tr align="left">
					<td colspan="4"><font size="2px">1. VESSEL NAME : <u><?php echo $rtnVesselDetails_igm[0]['vsl_name']; ?></u></font></td>
					<td colspan="3"><font size="2px">CALL SIGN : <u><?php echo $rtnVesselDetails_n4[0]['radio_call_sign']; ?></u></font></td>
					<td colspan="3"><font size="2px">FLAG : <u><?php echo $rtnVesselDetails_igm[0]['flag']; ?></u></font></td>
				</tr>
				<tr align="left">
					<td colspan="10"><font size="2px">2. NAME OF MASTER : <u><?php echo $rtnVesselDetails_igm[0]['Name_of_Master']; ?></u></font></td>
				</tr>
				<tr align="left">
					<td colspan="5">3. GRT : <u><?php echo $rtnVesselDetails_n4[0]['gross_registered_ton']; ?></u></td>
					<td colspan="5">NRT : <u><?php echo $rtnVesselDetails_n4[0]['net_registered_ton']; ?></u></td>
				</tr>
				<tr align="left">
					<td colspan="5">4. MAX. FRESH WATER DRAFT: <u><?php  ?></u></td>
					<td colspan="5">DECK CARGO : <u><?php echo $rtnVesselDetails_igm[0]['deck_cargo']; ?></u></td>
				</tr>
				<tr align="left">
					<td colspan="10" class="lbl">5. LOCAL AGENT : <u><?php echo $rtnVesselDetails_n4[0]['localagent']; ?></u></td> 
					
				</tr>
				<tr align="left">
					<td colspan="3" align="left">6. NAME OF PILOT : <u><?php if( $rslt_show_current_data[0]['pilot_name'] !=""){ echo $rslt_show_current_data[0]['pilot_name'];}else { echo ""; }   //if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['pilot_name']; } else { echo ""; } ?></u></td>
					<td colspan="4" align="left">BOARDED AT : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['pilot_on_board']; } else { echo ""; } ?></u></td>
					<td colspan="3" align="left">LEFT AT : <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['pilot_off_board']; } else { echo ""; } ?></u></td>
				
				</tr>
				
				<tr align="left">
					<td colspan="10" >7. CANCELLED MOVEMENT FROM SEA/JETTY/MOORING NO.: <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['cancel_from']; } else {echo "";} ?></td>
				</tr>
				<tr align="left">
				    <td colspan="1" > </td>
				    <td colspan="9" >&nbsp;&nbsp;&nbsp; TO SEA/JETTY/MOORING NO.: <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['cancel_to']; } else {echo "";} ?></td>
				</tr>
				
				<tr align="left">
				    <td colspan="5" >8. CANCELLED AT   </td>
				    <td colspan="5" >HRS. ON <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['cancel_at']; } else {echo "";} ?></td>
				</tr>
				<tr align="left">
				    <td colspan="10" >REASON: <u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['remarks']; } ?> </u> </td>
				</tr>
				<tr align="left">
					<td colspan="10" >9. DATE OF LAST VISIT OF THIS PORT<u><?php if(count($rslt_show_current_data)>0) { echo $rslt_show_current_data[0]['mooring_to_time']; } else {echo "";} ?></td>
				</tr>
				<tr align="left">
	    			   
			
				    <td colspan="10" >10. AS A RESULT OF THE ABOVE,THE FOLLOWING ARRANGEMENT MADE WERE ALSO CANCELLED (PARTICULARS TO BE PROVIDED BY THE DY.CONSERVATOR'S OFFICE.  </td>
				</tr>				
				
				<tr align="left">
					<td colspan="4" class="lbl">A. PORT AUTHORITY TUG/TUGS(NAME)</u></td>
					<td colspan="2" align="center">FROM</td>
					<td colspan="2" align="center">HRS. TO </td>
					<td colspan="2" align="center">HRS.</td>
				
				</tr>
				<tr align="left">
					<td colspan="4" class="lbl">B. LAUNCHES(NAME)</u></td>
					<td colspan="2" align="center">FROM</td>
					<td colspan="2" align="center">HRS. TO </td>
					<td colspan="2" align="center">HRS.</td>
				
				</tr>
				<tr align="left">
					<td colspan="4" class="lbl">C. HAWSER BOATS</u></td>
					<td colspan="2" align="center">FROM</td>
					<td colspan="2" align="center">HRS. TO </td>
					<td colspan="2" align="center">HRS.</td>
				
				</tr>
				<tr align="left">
					<td colspan="4" class="lbl">D. MOORING GANGS</u></td>
					<td colspan="2" align="center">FROM</td>
					<td colspan="2" align="center">HRS. TO </td>
					<td colspan="2" align="center">HRS.</td>
				
				</tr>
				
				<!--ASIF START -->
				<tr><td colspan="4">&nbsp;</td></tr>
				<tr><td colspan="4">&nbsp;</td></tr>
				<tr align="left">
					<td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;CERTIFIED THAT THE ABOVE PARTICULARS ARE CORRECT AND CHARGES THERE OF WILL BE PAID BY US/LOCAL AGENT INCLUSIVE OF OTHER PORT CHARGES</td>						
				</tr>
				
				<tr><td colspan="4">&nbsp;</td></tr>
				<tr>
					<td colspan="5">&nbsp;</td>
					<td colspan="5" ><?php if($rtn_vsl_arrival_info[0]['photo_base_64'] != "" || $rtn_vsl_arrival_info[0]['photo_base_64'] != null){ ?><img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $rtn_vsl_arrival_info[0]['photo_base_64']; ?>"/> <?php } ?></td>
				</tr>

				<tr>
					<td colspan="4"  align="center">----------------------</td>	
					<td colspan="2"  align="center" ></td>
					<td colspan="4"  align="center">------------------------------------------------------------</td>		
				</tr>
				<tr>
					<td colspan="4"  align="center">DATE</td>	
					<td colspan="2"  align="center" ></td>
					<td colspan="4"  align="center">MASTER</td>	
				</tr>

				<tr align="left">
					<td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FORWARDED TO THE CHIEF FINANCE & ACCOUNTS OFFICER,PORT AUTHORITY,CHITTAGONG FOR NECESSARY ACTION</td>						
				</tr>
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;"> <?php if(count($rslt_show_current_data)>0) { ?> <img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php echo $rslt_show_current_data[0]['pilot_name'];?>.png"/> <?php  } else { echo ""; } ?></td>
					<td colspan="5" style="font-size: 15px; text-align: center;">&nbsp;</td>
					
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------------</td>	
					<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------------</td>
					
				</tr>
				<tr>
					<td  colspan="5" style="font-size: 15px; text-align: center;" >AHM/PILOT</td>	
					<td  colspan="5" style="font-size: 15px; text-align: center;">DEPUTY CONSERVATOR/HARBOUR MASTER</td>
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;"><u>CHITTAGONG PORT AUTHORITY</u></td>	
					<td colspan="5" style="font-size: 15px; text-align: center;"><u>CHITTAGONG PORT AUTHORITY</u></td>	
				</tr>
				<!-- ASIF END -->
				
				
			</table>
		</div>
		<?php }else { ?>
			<div style="background-color:: #E6E45A; height: 100%">
			<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0' style="line-height:20px;">
				<tr align="center">
					<td  colspan="10" align="center">  
						<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
					</td>
				</tr>
				<tr align="center">
					<td  colspan="10" align="center" style="border:none;"><font size="5"><b><u>THE CHITTAGONG PORT AUTHORITY</u></b></font></td>	
				</tr>
				<tr align="center">
					<td colspan="10"  align="center" style="border:none;"><font size="5"><b>DEPARTURE REPORT OF VESSEL <BR> AND</b></font></td>
				</tr>
				<tr align="center">
					<td colspan="10" align="center" style="border:none;"> <font size="5"><b><u>PILOTAGE CERTIFICATE</u></b></font> </td>
				</tr>
			</table>
			<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0' style="line-height:15px; ">	
				<tr>
					<td colspan="4"  style="font-size: 15px;">&nbsp;1.VESSEL NAME:<u><?php echo $rtnVesselDetails_igm[0]['Vessel_Name']; ?></u></td>
					<td colspan="3" style="font-size: 15px;">CALL SIGN : <u><?php echo $rtnVesselDetails_n4[0]['radio_call_sign']; ?></u></td>
					<td colspan="3" style="font-size: 15px;">FLAG : <u><?php echo $rtnVesselDetails_n4[0]['flag']; ?></u></td>
				</tr>
				<tr >
					<td colspan="10" style="font-size: 15px;">&nbsp;2.NAME OF MASTER : <u><?php echo $rtnVesselDetails_igm[0]['Name_of_Master']; ?></u></td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">&nbsp;3.GRT : <u><?php echo $rtnVesselDetails_n4[0]['gross_registered_ton']; ?></u></td>
					<td colspan="3" style="font-size: 15px;">NRT : <u><?php echo $rtnVesselDetails_n4[0]['net_registered_ton']; ?></u></td>
					<td colspan="3" style="font-size: 15px;">DECK CARGO : <u><?php echo $rtnVesselDetails_igm[0]['Deck_cargo']; ?></u></td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">&nbsp;4.LOA : <u><?php echo $rtnVesselDetails_n4[0]['loa_cm']; ?></u></td>
					<td colspan="6" style="font-size: 15px;" >MAX FW DRAUGHT : <u><?php echo $rtnVesselDetails_n4[0]['beam_cm']; ?></u></td>
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">&nbsp;5.NAME AND ADDRESS OF OWNERS:</td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">&nbsp;6.LOCAL AGENT : <u><?php echo $rtnVesselDetails_n4[0]['localagent']; ?></u></td>
					<td colspan="3" style="font-size: 15px;">LAST PORT : </td>
					<td colspan="3" style="font-size: 15px;">NEXT PORT : <u><?php echo $rtnVesselDetails_igm[0]['Port_of_Destination']; ?></u></td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">&nbsp;7.NAME OF PILOT : <u><?php  if($pilot_name !=""){ echo $pilot_name;} else { echo ""; }?></u></td>
					<td colspan="3" style="font-size: 15px;">BOARDED AT : <u><?php if($chk_igm_id>0) { echo $rtnVesselDetails_depart[0]['pilot_on_board']; }?></u></td>
					<td colspan="3" style="font-size: 15px;">LEFT AT : <u><?php if($chk_igm_id>0) { echo $rtnVesselDetails_depart[0]['pilot_off_board']; }  ?></u></td>
				
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">&nbsp;8.PILOTAGE FROM : <u><?php if($chk_igm_id>0) { echo $rtnVesselDetails_depart[0]['pilot_frm']; }  ?></u></td>
					<td colspan="6" style="font-size: 15px;">TO : <u><?php if($chk_igm_id>0) { echo $rtnVesselDetails_depart[0]['pilot_to']; }?></u></td>
					</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">&nbsp;9.DATE OF ARRAIVAL IN PORT : <u><?php if($chk_igm_id>0) { echo $ata; }?></u></td>
					<td colspan="6" style="font-size: 15px;">DATE AND HOUR OF BERTHING : </td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">10.DATE OF DEPARTURE : <u><?php if($chk_igm_id>0) { echo $rtnVesselDetails_depart[0]['atd']; }?></u></td>
					<td colspan="6" style="font-size: 15px;">DEP.DRAFT(MAX) : </td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">11.TIME OF UNMOORING FROM : <u><?php if($chk_igm_id>0) { echo $rtnVesselDetails_depart[0]['mooring_frm_time']; }?></u></td>
					<td colspan="6" style="font-size: 15px;">TO : <u><?php if($chk_igm_id>0) { echo $rtnVesselDetails_depart[0]['mooring_to_time']; } ?></u></td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">12.CPA TUG/TUGS(NAME) : <u><?php if($chk_igm_id>0) { echo $rtnVesselDetails_depart[0]['aditional_tug']; } ?></u></td>
					<td colspan="4" style="font-size: 15px;">ASSISTANCE FROM : <u><?php if($chk_igm_id>0) { echo $rtnVesselDetails_depart[0]['assit_frm']; }?></u></td>
					<td colspan="2" style="font-size: 15px;">TO : <u><?php if($chk_igm_id>0) { echo $rtnVesselDetails_depart[0]['assit_to']; }?></u></td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">13.PC NO :</td>
					<td colspan="6" style="font-size: 15px;">DT : </td>
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">14.TONS OF CARGO ON BOARD : </td>
				</tr>
			</table>
			<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0'  style="line-height:15px;">	
				<tr>
					<td colspan="5" style="font-size: 15px;">15.MAIN ENGINES IN GOOD WORKING CONDITION?</td> 
					<td colspan="5" style="font-size: 15px;"><?php if($rtnVesselDetails_depart[0]['is_main_engine_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php if($rtnVesselDetails_depart[0]['is_main_engine_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px;">16.TWO ANCHORS IN GOOD WORKING CONDITION?</td> 
					<td colspan="5" style="font-size: 15px;"><?php if($rtnVesselDetails_depart[0]['is_acnchors_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php if($rtnVesselDetails_depart[0]['is_acnchors_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px;">17.RUDDER INDICATOR IN GOOD WORKING CONDITION?</td> 
					<td colspan="5" style="font-size: 15px;"><?php if($rtnVesselDetails_depart[0]['is_rudder_indicator_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php if($rtnVesselDetails_depart[0]['is_rudder_indicator_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				<tr>
					<td  colspan="5" style="font-size: 15px;">18.RPM INDICATOR IN GOOD WORKING CONDITION?</td> 
					<td colspan="5" style="font-size: 15px;"><?php if($rtnVesselDetails_depart[0]['is_rpm_indicator_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php if($rtnVesselDetails_depart[0]['is_rpm_indicator_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				<tr>
					<td  colspan="5" style="font-size: 15px;">19.BOW THURSTER AVAILABLE?</td> 
					<td colspan="5" style="font-size: 15px;"><?php if($rtnVesselDetails_depart[0]['is_bow_therster_available'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php if($rtnVesselDetails_depart[0]['is_bow_therster_available'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				<tr>
					<td  colspan="5" style="font-size: 15px;">20.ARE YOU COMPLYING SOLAS CONVENTION?</td> 
					<td colspan="5" style="font-size: 15px;"><?php if($rtnVesselDetails_depart[0]['is_complying_soal_convention'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php if($rtnVesselDetails_depart[0]['is_complying_soal_convention'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				<tr>
					<td colspan="5"  style="font-size: 15px;">21.NOS. OF GOOD MOORING LINES:FORD :</td>
					<td colspan="5"  style="font-size: 15px;">AFT : </td>
				</tr>
				<tr>
					<td colspan="4"  style="font-size: 15px; text-align:left;">IMMEDIATELY : </td>
					<td colspan="4"  style="font-size: 15px; text-align:left;">SECS.LATER</td>						
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">23.REMARKS IF ANY : </td>
				</tr>
				<?php if($rtnVesselDetails_depart[0]['is_night'] ==1){?>
				<tr>
					<td align="left" colspan="2"  style="width:470px;"><b>NIGHT SHIFT</b></td>
				</tr>
				<?php }?>
				<?php if($rtnVesselDetails_depart[0]['is_holiday'] ==1){?>
				<tr>
					<td align="left" colspan="2"  style="width:470px;"><b>HOLIDAY</b></td>
				</tr>
				<?php }?>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan="10" style="font-size: 15px; text-align:center;">CERTIFIED THAT THE ABOVE PARTICULARS ARE CORRECT AND CHARGES THEREOF WILL BE PAID BY US/LOCAL AGENT INCLUSIVE OF OTHER PORT CHARGES.</td>						
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align:center;"><?php echo $rtnVesselDetails_depart[0]['sign_arraival']; ?></td>	
					<td colspan="5" style="font-size: 15px; text-align:center;"><?php if($rtnVesselDetails_depart[0]['photo_base_64'] != "" || $rtnVesselDetails_depart[0]['photo_base_64'] != null){ ?><img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $rtnVesselDetails_depart[0]['photo_base_64']; ?>"/> <?php } ?></td>
				</tr>
				<tr>
					<td colspan="5"  style="font-size: 15px; text-align:center;">-----------------------------------</td>	
					<td colspan="5"  style="font-size: 15px; text-align:center;">-----------------------------------</td>		
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align:center;">DATE</td>	
					<td colspan="5" style="font-size: 15px; text-align:center;">MASTER</td>	
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan="10" style="font-size: 15px; text-align:center;">FORWARDED TO THE CHIEF FINANCE & ACCOUNTS OFFICER,PORT AUTHORITY CHITTAGONG FOR NECESSARY ACTION.</td>						
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;"> <?php if(count($rtnVesselDetails_depart)>0) { ?> <img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php echo $rtnVesselDetails_depart[0]['pilot_name'];?>.png"/> <?php  } else { echo ""; } ?></td>
					<td colspan="5" style="font-size: 15px; text-align: center;">&nbsp;</td>
					
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------------</td>	
					<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------------</td>
					
				</tr>
				<tr>
					<td  colspan="5" style="font-size: 15px; text-align: center;" >AHM/PILOT</td>	
					<td  colspan="5" style="font-size: 15px; text-align: center;">DEPUTY CONSERVATOR/HARBOUR MASTER</td>
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;"><u>CHITTAGONG PORT AUTHORITY</u></td>	
					<td colspan="5" style="font-size: 15px; text-align: center;"><u>CHITTAGONG PORT AUTHORITY</u></td>	
				</tr>
			</table>
		</div>
		<?php } ?>
	</BODY>
</HTML>