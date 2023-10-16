<HTML>
	<HEAD>
		<!--TITLE>BLOCKED CONTAINER LIST</TITLE-->
	    <style type="text/css">
        </style>
	</HEAD>
	<BODY style="background-color: green;">
			<div style="background-color: lightblue;height: 100%;">
			<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0'  >
					<tr align="center">
						<td  colspan="10" align="center">  
							<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
						</td>
					</tr>				
					<tr>
						<td  align="center" style="border:none;"><font size="5"><b><u>THE CHITTAGONG PORT AUTHORITY2</u></b></font></td>
					</tr>
					<tr>
						<td  align="center" style="border:none;"><font size="5"><b>ARRIVAL REPORT OF VESSEL AND PILOTAGE CERTIFICATE</b></font></td>	
					</tr>
			</table>
			<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0' style="line-height:20px;">	
				<tr>
					<td colspan="4" style="font-size: 15px;">1. VESSELS NAME :<u><?php echo $vlsdetails_n4_data[0]['NAME'];?></u></td>
					<td colspan="2" style="font-size: 15px;">CALL SIGN :<u><?php echo $vlsdetails_n4_data[0]['RADIO_CALL_SIGN'];?></u></td>
					<td colspan="4" style="font-size: 15px;">FLAG : <u><?php echo $vlsdetails_n4_data[0]['FLAG'];?></u></td>
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;" >2. NAME OF MASTER : <u><?php echo $vlsdetails_igm_data[0]['Name_of_Master'];?></u></td>
				</tr>
				<tr>                                
					<td colspan="4" style="font-size: 15px;">3. GRT : <u><?php echo $vlsdetails_n4_data[0]['GROSS_REGISTERED_TON'];?></u></td>
					<td colspan="2" style="font-size: 15px;">NRT : <u><?php echo $vlsdetails_n4_data[0]['NET_REGISTERED_TON'];?></u></td>
					<td colspan="4" style="font-size: 15px;">DECK CARGO : <u><?php echo $vlsdetails_igm_data[0]['Deck_cargo'];?></u></td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">4. LOA : <u><?php echo $vlsdetails_n4_data[0]['LOA_CM'];?></u></td>
					<td colspan="6" style="font-size: 15px;">MAX. FW DRAUGHT : <u><?php // echo $arrivalInfo42[0]['Vessel_Name'];?></u></td>	
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">5. NUMBER OF CREW & OFFICER INCLUSIVE MASTER : <u><?php //echo $vsl_arrival_data[0]['Vessel_Name'];?></u></td>
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">6. NAME AND ADDRESS OF OWNERS: </td>						
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px;">7. LOCAL AGENT: <u><?php echo $vlsdetails_n4_data[0]['LOCALAGENT'];?></u></td>
					<td colspan="2" style="font-size: 15px;">LAST PORT: <u><?php	echo $vlsdetails_n4_data[0]['LAST_PORT'];?></u></td>
					<td colspan="3" style="font-size: 15px; text-align: left;">NEXT PORT: <u><?php echo $vlsdetails_n4_data[0]['NEXT_PORT']; ?></u></td>	
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px;">8. NAME OF PILOT: <u><?php if($vsl_arrival_data[0]['u_name'] !=""){ echo $vsl_arrival_data[0]['u_name'];}else { echo ""; }?></u></td>
					<td colspan="3" style="font-size: 15px;">BOARDED: <u><?php echo $vsl_arrival_data[0]['pilot_on_board'];?></u></td>
					<td colspan="3" style="font-size: 15px;">LEFT: <u><?php echo $vsl_arrival_data[0]['pilot_off_board'];?></u></td>	
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px;">9. PILOTAGE FROM : <u><?php echo $vsl_arrival_data[0]['pilot_frm'];?></u></td>	
					<td colspan="5" style="font-size: 15px;">TO : <u><?php echo $vsl_arrival_data[0]['pilot_to'];?></u></td>						
				</tr>
				<tr> 
					<td colspan="5" style="font-size: 15px;">10.TIME OF MOORING FROM: <u><?php echo $vsl_arrival_data[0]['mooring_frm_time'];?></u></td>	
					<td colspan="5" style="font-size: 15px;">TO: <u><?php echo $vsl_arrival_data[0]['mooring_to_time'];?></u></td>						
				</tr>
				 <tr>
			
					<td colspan="4" style="font-size: 15px;">11.CPA TUG/TUGS(NAME): <u><?php if($vsl_arrival_data[0]['tug_name'] !="") echo $vsl_arrival_data[0]['aditional_tug']."(".$vsl_arrival_data[0]['tug_name'].")"; else echo $vsl_arrival_data[0]['aditional_tug'];?></u></td>	
					<td colspan="3" style="font-size: 15px;">ASSISTANCE FROM : <u><?php echo $vsl_arrival_data[0]['assit_frm'];?></u></td>	
					<td colspan="3" style="font-size: 15px;">TO : <u><?php echo $vsl_arrival_data[0]['assit_to'];?></u></td>						
				</tr>
				<tr> 
					<td colspan="5" style="font-size: 15px;">12.ARRIVAL AT OUTER ANCHORAGE DATE : <u><?php echo $vsl_arrival_data[0]['oa_dt'];?></u></td>		
					<td colspan="5" style="font-size: 15px;">FW. DRAFT (MAX): <u><?php // echo $vsl_arrival_data[0]['Vessel_Name']?></u></td>						
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">13.IF WORKED AS LIGHTER, NAME OF MOTHER VESSEL : <u><?php // echo $vsl_arrival_data[0]['Vessel_Name']?></u></td>						
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">14.WORKED AT OUTER ANCHORAGE / OUTAGE PORT LIMIT.(TICK)</td>						
				</tr>
				<tr>
					<td colspan="10" style="font-size: 15px;">15.DANGEROUS CARGO IF ANY :</td>						
			  </tr>
			  <tr>
					<td colspan="6" style="font-size: 15px;">16.MAIN ENGINES IN GOOD WORKING CONDITION?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($vsl_arrival_data[0]['is_main_engine_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($vsl_arrival_data[0]['is_main_engine_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				<tr>
					<td colspan="6" style="font-size: 15px;">17.TWO ANCHORS IN GOOD WORKING CONDITION?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($vsl_arrival_data[0]['is_acnchors_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($vsl_arrival_data[0]['is_acnchors_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
			
				<tr>
					<td colspan="6" style="font-size: 15px;">18.RUDDER INDICATOR IN GOOD WORKING CONDITION?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($vsl_arrival_data[0]['is_rudder_indicator_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($vsl_arrival_data[0]['is_rudder_indicator_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				<tr>
					<td colspan="6" style="font-size: 15px;">19.RPM INDICATOR IN GOOD WORKING CONDTION?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($vsl_arrival_data[0]['is_rpm_indicator_ok'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($vsl_arrival_data[0]['is_rpm_indicator_ok'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
			
				<tr>
					<td colspan="6" style="font-size: 15px;">20.BOW THRUSTER AVAILABLE?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($vsl_arrival_data[0]['is_bow_therster_available'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp;  <?php if($vsl_arrival_data[0]['is_bow_therster_available'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
				</tr>
				
				<tr>
					<td colspan="6" style="font-size: 15px;">21.ARE YOU COMPLYING SOLAS CONVENTION?</td> 
					<td colspan="4" style="font-size: 15px;"><?php if($vsl_arrival_data[0]['is_complying_soal_convention'] ==1){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;YES&nbsp; <?php if($vsl_arrival_data[0]['is_complying_soal_convention'] ==0){?><img src="<?php echo IMG_PATH?>check_mark_icon.jpg" /> <?php }else{?> <input type="checkbox" class="radio"  style="width:80px; font-size: 15px;" id="twoAnchorNo" name="twoAnchorNo" >  <?php } ?>&nbsp;NO</td>
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
					<td colspan="10" style="font-size: 15px;">24.REMARKS IF ANY : <?php echo $vsl_arrival_data[0]['remarks'];?></td>
				</tr>
				<?php if($vsl_arrival_data[0]['is_night'] ==1){?>
				<tr>
					<td colspan="10" style="font-size: 15px;"><b>NIGHT SHIFT</b> </td>
				</tr>
				<?php }?>
				<?php if($vsl_arrival_data[0]['is_holiday'] ==1){?>
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
					<td colspan="5"  style="font-size: 15px; text-align: center;"><?php echo $vsl_arrival_data[0]['sign_arraival'];  ?></td>
					<td colspan="5"  style="font-size: 15px; text-align: center;"><?php if($vsl_arrival_data[0]['photo_base_64'] != "" || $vsl_arrival_data[0]['photo_base_64'] != null){ ?><img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $vsl_arrival_data[0]['photo_base_64']; ?>"/> <?php } ?></td>
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
				
				<tr><td colspan="10">&nbsp;</td></tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;"> <?php if(count($vsl_arrival_data)>0) { ?> <img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php echo $vsl_arrival_data[0]['pilot_name'];?>.png"/> <?php  } else { echo ""; } ?></td>
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
	</BODY>
</HTML>