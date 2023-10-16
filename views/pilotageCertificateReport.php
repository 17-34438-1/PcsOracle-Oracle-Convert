
<HTML>
	<HEAD>
		<!--TITLE>BLOCKED CONTAINER LIST</TITLE-->
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
	</HEAD>
	<BODY>
		
		<?php 
			if($igm_id_arraival>0)
			{
				for($a=0; $a<count($rtn_vsl_arrival_info); $a++)
				{
		?>
		<div style="background-color: lightblue;height:1050px;">
				<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0' >
		
					   <tr>
							<td  align="center" style="border:none;"><font size="5"><b><u>THE CHITTAGONG PORT AUTHORITY</u></b></font></td>
					   </tr>
					   <tr>
							<td  align="center" style="border:none;"><font size="5"><b>ARRIVAL REPORT OF VESSEL AND PILOTAGE CERTIFICATE</b></font></td>	
					   </tr>
				</table>
				<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0' >
							<tr>
								<td align="left">1.</td>
								<td align="left" style="width:200px; font-size: 12px;">VESSEL NAME :   <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									$vsl=$rtnVesselDetails_igm[0]['Vessel_Name']; 
									if( $vsl==""){ 
										if($rsltn4ForVsl != ""){
											echo $rsltn4ForVsl;
										} else {
											echo $vsl_name_doc_vsl_info;
										}										 
									} else echo $vsl; 
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
								<td align="left" style="width:200px; font-size: 12px;">CALL SIGN :<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $radioCallSign; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
								<td align="left" style="width:200px; font-size: 12px;">FLAG : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($flagValue != ""){
										echo $flagValue;
									} else {
										echo $flag_doc_vsl_info;
									}									 
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
							</tr>
							<tr>
								<td align="left">2.</td>
								<td align="left" colspan="3" style="width:700px; font-size: 12px;" >NAME OF MASTER : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<?php 
										if($rtnVesselDetails_igm[0]['Name_of_Master'] != ""){
											echo $rtnVesselDetails_igm[0]['Name_of_Master'];
										} else {
											echo $master_name_doc_vsl_info;
										}
										
									?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
							</tr>
							<tr>                                
								<td align="left">3.</td>
								<td align="left" style="width:200px; font-size: 12px;">GRT : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($grtValue != ""){
										echo $grtValue;
									} else {
										echo $grt_doc_vsl_info;
									}
									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
								<td align="left" style="width:200px; font-size: 12px;">NRT : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($nrtValue != ""){
										echo $nrtValue;
									} else {
										echo $nrt_doc_vsl_info;
									}
									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
								<td align="left" style="width:160px; font-size: 12px;">DECK CARGO : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($rtnVesselDetails_igm[0]['Deck_cargo'] != ""){
										echo $rtnVesselDetails_igm[0]['Deck_cargo'];
									} else {
										echo $deck_cargo_doc_vsl_info;
									}
									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
							</tr>
							<tr>
								<td align="left">4.</td>
								<td align="left" style="width:250px; font-size: 12px;">LOA : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($loaCm != ""){
										echo $loaCm;
									} else {
										echo $loa_doc_vsl_info;
									}									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
								<td align="left" colspan="2" style="width:300px; font-size: 12px;">MAX. FW DRAUGHT : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $draught_arrival; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>	
							</tr>
							<tr>
								<td align="left">5.</td>
								<td align="left" colspan="3" style="width:500px; font-size: 12px;">NUMBER OF CREW & OFFICER INCLUSIVE MASTER : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rtn_vsl_arrival_info[$a]['Vessel_Name']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
							</tr>
							<tr>
								<td align="left">6.</td>
								<td align="left" colspan="3" style="width:500px;font-size: 12px;" >NAME AND ADDRESS OF OWNERS: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rtnVesselDetails_igm[0]['Vessel_Name']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>						
							</tr>
							<tr>
								<td align="left">7.</td>
								<td align="left" style="width:200px; font-size: 12px;">LOCAL AGENT : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($rtnVesselDetails_n4[0]['localagent'] != ""){
										echo $rtnVesselDetails_n4[0]['localagent'];
									} else {
										echo $local_agent_doc_vsl_info;
									}									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
								<td align="left" style="width:200px; font-size: 12px;">LAST PORT : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $last_port_doc_vsl_info; ?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
								<td align="left" style="width:145px; font-size: 12px;">NEXT PORT : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $next_port_doc_vsl_info;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>	
							</tr>
							<tr>
								<td align="left">8.</td>
								<td align="left" style="width:200px; font-size: 12px;">NAME OF PILOT : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<?php 
										if($rtn_vsl_arrival_info[$a]['pilot_name'] != ""){
											echo $rtn_vsl_arrival_info[$a]['pilot_name'];
										} else {
											echo $pilot_name_arrival;
										}										
									?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
								<td align="left" style="width:200px; font-size: 12px;">BOARDED : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($rtn_vsl_arrival_info[$a]['pilot_on_board'] != ""){
										echo $rtn_vsl_arrival_info[$a]['pilot_on_board'];
									} else {
										echo $pilot_on_board_arrival;
									}
									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>
								<td align="left" style="width:200px; font-size: 12px;">LEFT : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($rtn_vsl_arrival_info[$a]['pilot_off_board'] != ""){
										echo $rtn_vsl_arrival_info[$a]['pilot_off_board'];
									} else {
										echo $pilot_off_board_arrival;
									}									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>	
							</tr>
							<tr>
								<td align="left">9.</td>
								<td align="left" style="width:200px; font-size: 12px;">PILOTAGE FROM : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($rtn_vsl_arrival_info[$a]['pilot_frm'] != ""){
										echo $rtn_vsl_arrival_info[$a]['pilot_frm'];
									} else {
										echo $pilot_frm_arrival;
									}
									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>	
								<td align="left" style="width:200px; font-size: 12px;">TO : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($rtn_vsl_arrival_info[$a]['pilot_to'] != ""){
										echo $pilot_to_arrival;
									} else {
										echo $rtn_vsl_arrival_info[$a]['pilot_to'];
									}									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>						
							</tr>
							<tr> 
								<td align="left">10.</td>
								<td align="left" style="width:170px; font-size: 12px;">TIME OF MOORING FROM: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($rtn_vsl_arrival_info[$a]['mooring_frm_time'] != ""){
										echo $rtn_vsl_arrival_info[$a]['mooring_frm_time'];
									} else {
										echo $mooring_frm_time_arrival;
									}									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>	
								<td align="left" style="width:200px; font-size: 12px;">TO: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($rtn_vsl_arrival_info[$a]['mooring_to_time'] != ""){
										echo $rtn_vsl_arrival_info[$a]['mooring_to_time'];
									} else {
										echo $mooring_to_time_arrival;
									}									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>						
							</tr>
							 <tr>
								<td align="left">11.</td>
								<td align="left" style="width:150px; font-size: 12px;">CPA TUG/TUGS(NAME): <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($rtn_vsl_arrival_info[$a]['tug_name'] != ""){
										echo $rtn_vsl_arrival_info[$a]['tug_name'];
									} else {
										echo $tug_name_arrival;
									}									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>	
								<td align="left" style="width:220px; font-size: 12px;">ASSISTANCE FROM : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($rtn_vsl_arrival_info[$a]['assit_frm'] == "0000-00-00 00:00:00" 
													or 
										$rtn_vsl_arrival_info[$a]['assit_frm'] == ""){
											
										if($assit_frm_arrival == "0000-00-00 00:00:00"){
											echo "";
										} else {
											echo $assit_frm_arrival;
										}
										
									} else {
										echo $rtn_vsl_arrival_info[$a]['assit_frm'];
									}									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;</u></td>	
								<td align="left" style="width:150px; font-size: 12px;">TO : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									
									if($rtn_vsl_arrival_info[$a]['assit_to'] == "0000-00-00 00:00:00" 
													or 
										$rtn_vsl_arrival_info[$a]['assit_to'] == ""){
											
										if($assit_to_arrival == "0000-00-00 00:00:00"){
											echo "";
										} else {
											echo $assit_to_arrival;
										}
										
									} else {
										echo $rtn_vsl_arrival_info[$a]['assit_to'];
									}									
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>						
							</tr>
							<tr> 
								<td align="left">12.</td>
								<td align="left" style="width:100px; font-size: 12px;">ARRIVAL AT OUTER ANCHORAGE DATE : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php 
									if($rtn_vsl_arrival_info[$a]['oa_dt'] == "0000-00-00 00:00:00" 
													or 
										$rtn_vsl_arrival_info[$a]['oa_dt'] == ""){
											
										if($oa_dt_arrival == "0000-00-00 00:00:00"){
											echo "";
										} else {
											echo $oa_dt_arrival;
										}
										
									} else {
										echo $rtn_vsl_arrival_info[$a]['oa_dt'];
									}
								?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>		
								<td align="left" colspan="2" style="width:300px; font-size: 12px;">FW. DRAFT (MAX): <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php // echo $rtn_vsl_arrival_info[$a]['Vessel_Name']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>						
							</tr>
							<tr>
								<td align="left">13.</td>
								<td align="left" style="width:400px; font-size: 12px;">IF WORKED AS LIGHTER, NAME OF MOTHER VESSEL : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php // echo $rtn_vsl_arrival_info[$a]['Vessel_Name']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>						
							</tr>
							<tr>
								<td align="left">14.</td> 
								<td align="left"colspan="3" style="width:400px; font-size: 12px;">WORKED AT OUTER ANCHORAGE / OUTAGE PORT LIMIT.(TICK)</td>						
							</tr>
							<tr>
								<td align="left">15.</td>
								<td align="left" colspan="3" style="width:400px; font-size: 12px;">DANGEROUS CARGO IF ANY : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php // echo $rtn_vsl_arrival_info[$a]['Vessel_Name']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>						
						  </tr>
					</table>
									
					<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0' >
								  <tr>
									<td align="left">16.</td>
									<td  align="left" style="width:300px; font-size: 12px;">MAIN ENGINES IN GOOD WORKING CONDITION?</td> YES
								   <td align="left" style="font-size: 12px;">  &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
								   <?php if($is_main_engine_ok_arrival=="1") { ?>
								   <img src="<?php echo IMG_PATH?>check-box.png" />
								   <?php } else { ?>
								   <img src="<?php echo IMG_PATH?>unchecked.png" />
								   <?php }?>
								   &nbsp;YES  &nbsp; &nbsp; &nbsp; &nbsp;
									<?php if($is_main_engine_ok_arrival=="0") { ?>
								   <img src="<?php echo IMG_PATH?>check-box.png" />
								   <?php } else { ?>
								   <img src="<?php echo IMG_PATH?>unchecked.png" />
								   <?php }?>
										 NO </td>
								  </tr>
							<tr>
								<td align="left">17.</td>
								<td  align="left">TWO ANCHORS IN GOOD WORKING CONDITION?</td> 
								<td align="left" style="font-size: 12px;">  &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
									<?php if($is_acnchors_ok_arrival=="1") { ?>
								   <img src="<?php echo IMG_PATH?>check-box.png" />
								   <?php } else { ?>
								   <img src="<?php echo IMG_PATH?>unchecked.png" />
								   <?php }?>
								   &nbsp;YES  &nbsp; &nbsp; &nbsp; &nbsp;
									<?php if($is_acnchors_ok_arrival=="0") { ?>
								   <img src="<?php echo IMG_PATH?>check-box.png" />
								   <?php } else { ?>
								   <img src="<?php echo IMG_PATH?>unchecked.png" />
								   <?php }?>
										 NO </td>
								  </tr>
							 </tr>
							 <tr>
								<td align="left">18.</td> 
								<td  align="left">RUDDER INDICATOR IN GOOD WORKING CONDITION?</td> 
								<td align="left" style="font-size: 12px;">  &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
								<?php if($is_rudder_indicator_ok_arrival=="1") { ?>
							   <img src="<?php echo IMG_PATH?>check-box.png" />
							   <?php } else { ?>
							   <img src="<?php echo IMG_PATH?>unchecked.png" />
							   <?php }?>
								   &nbsp;YES  &nbsp; &nbsp; &nbsp; &nbsp;
									<?php if($is_rudder_indicator_ok_arrival=="0") { ?>
								   <img src="<?php echo IMG_PATH?>check-box.png" />
								   <?php } else { ?>
								   <img src="<?php echo IMG_PATH?>unchecked.png" />
								   <?php }?>
										 NO </td>
								  </tr>
							 </tr>
							 <tr>
									<td align="left">19.</td>
									<td  align="left">RPM INDICATOR IN GOOD WORKING CONDTION?</td> 
								   <td align="left" style="font-size: 12px;">  &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
								   <?php if($is_rpm_indicator_ok_arrival=="1") { ?>
								   <img src="<?php echo IMG_PATH?>check-box.png" />
								   <?php } else { ?>
								   <img src="<?php echo IMG_PATH?>unchecked.png" />
								   <?php }?>
								   &nbsp;YES  &nbsp; &nbsp; &nbsp; &nbsp;
									<?php if($is_rpm_indicator_ok_arrival=="0") { ?>
								   <img src="<?php echo IMG_PATH?>check-box.png" />
								   <?php } else { ?>
								   <img src="<?php echo IMG_PATH?>unchecked.png" />
								   <?php }?>
										 NO </td>
								  </tr>
							 </tr>
							 <tr>
								<td align="left">20.</td>
								<td  align="left">BOW THRUSTER AVAILABLE?</td> 
								<td align="left" style="font-size: 12px;">  &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
								<?php if($is_bow_therster_available_arrival=="1") { ?>
								   <img src="<?php echo IMG_PATH?>check-box.png" />
								   <?php } else { ?>
								   <img src="<?php echo IMG_PATH?>unchecked.png" />
								   <?php }?>
								   &nbsp;YES  &nbsp; &nbsp; &nbsp; &nbsp;
									<?php if($is_bow_therster_available_arrival=="0") { ?>
								   <img src="<?php echo IMG_PATH?>check-box.png" />
								   <?php } else { ?>
								   <img src="<?php echo IMG_PATH?>unchecked.png" />
								   <?php }?>
										 NO </td>
								  </tr>
							 </tr>
							<tr>
								<td align="left">21.</td>
								<td  align="left">ARE YOU COMPLYING SOLAS CONVENTION?</td> 
								<td align="left" style="font-size: 12px;">  &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
								<?php if($is_complying_soal_convention_arrival=="1") { ?>
							   <img src="<?php echo IMG_PATH?>check-box.png" />
							   <?php } else { ?>
							   <img src="<?php echo IMG_PATH?>unchecked.png" />
							   <?php }?>
								   &nbsp;YES  &nbsp; &nbsp; &nbsp; &nbsp;
									<?php if($is_complying_soal_convention_arrival=="0") { ?>
								   <img src="<?php echo IMG_PATH?>check-box.png" />
								   <?php } else { ?>
								   <img src="<?php echo IMG_PATH?>unchecked.png" />
								   <?php }?>
										 NO </td>
								  </tr>
							</tr>
							<tr>
							   <td align="left">23.</td>
							<td align="left" style="width:250px; font-size: 12px;">NOS OF GOOD MOORING LINES: FORD:  <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>	
								<td align="left" style="width:250px; font-size: 12px;">AFT : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>			
							</tr>			
							<tr>
								<td align="left">23.</td>
							<td align="left" style="width:250px; font-size: 12px;">STERN POWER AVAILABLE :  <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></td>	
								<td align="left" style="width:250px; font-size: 12px;">IMMEDIATELY : <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> SECS. LATER</td>												
							</tr>
						<tr>
							<td align="left">24.</td>
							<td align="left" colspan="2"  style="width:470px;">REMARKS IF ANY : <?php echo $remarks_arrival?>
							</td>
						</tr>
						<?php
						if($rtn_vsl_arrival_info[$a]['is_night']==1)
						{
						?>
						<tr align="left">
							<td colspan="10" class="lbl"><b>NIGHT SHIFT</b></td>
						</tr>					
						<?php
						}
						if($rtn_vsl_arrival_info[$a]['is_holiday']==1)
						{
						?>
						<tr align="left">
							<td colspan="10" class="lbl"><b>HOLIDAY</b></td>
						</tr>	
						<?php
						}
						?>
						<tr align="left">
							<td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CERTIFIED THAT THE ABOVE PARTICULARS ARE CORRECT AND CHARGES THEREOF</td>						
						</tr>
						<tr align="left">
							<td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WILL BE PAID BY US/LOCAL AGENT INCLUSIVE OF OTHER PORT CHARGES.</td>						
						</tr>
						<tr><td colspan="4">&nbsp;</td></tr>
						<tr><td colspan="4">&nbsp;</td>
						<td colspan="4" >
							<?php 
								if($rtn_vsl_arrival_info[$a]['photo_base_64'] != "" || $rtn_vsl_arrival_info[$a]['photo_base_64'] != null){ ?><img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $rtn_vsl_arrival_info[$a]['photo_base_64']; ?>"/> <?php } ?></td>
						</tr>

						<tr>
						<td colspan="4" >
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<u>
								&nbsp;&nbsp;&nbsp;&nbsp;<?php if($rtn_vsl_arrival_info[$a]['sign_arraival']!=""){echo $rtn_vsl_arrival_info[$a]['sign_arraival'];} else {echo $rtn_vsl_arrival_info[$a]['mooring_frm_time'];} ?>&nbsp;&nbsp;&nbsp;&nbsp;
								
							</u>
						</td>	
						<!--td colspan="4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "2018-09-26";?>&nbsp;&nbsp;&nbsp;&nbsp;</u></td-->
						<td colspan="4">------------------------------------------------------------</td>		
						</tr>
						<tr>
						<td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DATE</td>	
						<td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MASTER</td>	
						</tr>

						<tr align="left">
							<td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FORWARDED TO THE CHIEF FINANCE & ACCOUNTS OFFICER,PORT AUTHORITY</td>						
						</tr>
						<tr align="left">
							<td colspan="4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CHITTAGONG FOR NECESSARY ACTION.</td>						
						</tr>
						<tr><td colspan="4">&nbsp;</td></tr>
								<tr><td colspan="4">&nbsp;</td></tr>
						<tr>
									<td colspan="4" >  <?php if(count($rtn_vsl_arrival_info)>0) { ?> <img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php echo @$rtn_vsl_arrival_info[$a]['pilot_login'];?>.png"/> <?php  } else { echo ""; } ?></td>	
									<td colspan="4" ></td>
									<td colspan="1"></td>
						</tr>
						<tr>
									<td colspan="4" >---------------------------------------------</td>	
									<td colspan="4" >-------------------------------------------------------------------------</td>
									<td colspan="1"></td>
						</tr>
						<tr>
				<!--                   <td colspan="1"></td>-->
						<td  colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AHM/PILOT</td>	
						<td  colspan="4">DEPUTY CONSERVATOR/HARBOUR MASTER</td>
										
						</tr>
						<tr>
						<td colspan="4">&nbsp;&nbsp;&nbsp;<u>CHITTAGONG PORT AUTHORITY</u></td>	
						<td colspan="4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>CHITTAGONG PORT AUTHORITY</u></td>	
						
								</tr>
						


				 </table>
		</div>
		<?php 
				}
			} 
		if ($igm_id_shift>0) 
		{
			$sql_shifting="SELECT  doc_vsl_info.vsl_name AS Vessel_Name, doc_vsl_info.master_name AS Name_of_Master,
			doc_vsl_info.grt,doc_vsl_info.nrt,doc_vsl_info.Deck_cargo, doc_vsl_info.loa AS loa_cm,
				doc_vsl_info.next_port AS  Port_of_Destination,doc_vsl_info.flag,doc_vsl_info.local_agent,
				(SELECT u_name FROM users WHERE users.login_id=pilot_name) AS pilot_name,				
				doc_vsl_shift.id,doc_vsl_shift.pilot_on_board,doc_vsl_shift.pilot_off_board,doc_vsl_shift.shift_frm,doc_vsl_shift.shift_to,
				doc_vsl_shift.mooring_frm_time,doc_vsl_shift.mooring_to_time,doc_vsl_shift.cancel_from,doc_vsl_shift.cancel_to,
				doc_vsl_shift.cancel_at,doc_vsl_shift.tug_name,doc_vsl_shift.assit_frm,doc_vsl_shift.assit_to,doc_vsl_shift.shift_dt,
				doc_vsl_shift.aditional_pilot,doc_vsl_shift.aditional_tug,doc_vsl_shift.remarks,doc_vsl_shift.berth,doc_vsl_shift.draught,
				doc_vsl_shift.photo_base_64,doc_vsl_shift.is_main_engine_ok,doc_vsl_shift.is_acnchors_ok,
				doc_vsl_shift.is_rudder_indicator_ok,doc_vsl_shift.is_rpm_indicator_ok,doc_vsl_shift.is_bow_therster_available,
				doc_vsl_shift.is_complying_soal_convention,doc_vsl_shift.is_night,doc_vsl_shift.is_holiday,doc_vsl_shift.final_submit,
				doc_vsl_shift.date_modified 
				FROM doc_vsl_shift 
				INNER JOIN doc_vsl_info ON doc_vsl_shift.vvd_gkey=doc_vsl_info.vvd_gkey 
				WHERE doc_vsl_shift.vvd_gkey='$getVvdGkey'";
			
			$rtnVesselDetails_shifting=$this->bm->dataSelectDb1($sql_shifting);
			for($v=0; $v<count($rtnVesselDetails_shifting); $v++)
			{
				//$rtnVesselDetails_shifting[$v]['']
			
	
		?>
		<div style="page-break-after: always;background: #eaf9a7; height:1050px;">
			<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0' >
				<!--tr align="center">
					<td colspan="10" align="center"> <b><u>CHITTAGONG PORT AUTHORITY</u></b> </td>
				</tr-->
				<tr align="center">
					<td  colspan="10" align="center" style="border:none;"><font size="5"><b><u>THE CHITTAGONG PORT AUTHORITY</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="10"  align="center" style="border:none;"><font size="5"><b>PILOTAGE CERTIFICATE FOR SHIFTING</b></font></td>
				</tr>
				<tr align="left">
					<td colspan="4"><font size="2px">1. VESSEL NAME : <u>
						<?php 
							$vsl=$rtnVesselDetails_igm[0]['Vessel_Name']; 
							if( $vsl==""){ 
								echo $rsltn4ForVsl; 
							} 
							else echo $vsl;
						?>
						</u></font></td>
					<td colspan="3"><font size="2px">CALL SIGN : <u><?php echo $radioCallSign; ?></u></font></td>
					<td colspan="3"><font size="2px">FLAG : <u><?php echo $flagValue; ?></u></font></td>
				</tr>
				<tr align="left">
					<td colspan="10"><font size="2px">2. NAME OF MASTER : <u>
						<?php 
							if($rtnVesselDetails_igm[0]['Name_of_Master'] != ""){
								echo $rtnVesselDetails_igm[0]['Name_of_Master'];
							} else {
								echo $master_name_doc_vsl_info;
							}
							 
						?>
					</u></font></td>
				</tr>
				<tr align="left">
					<td colspan="4">3. GRT : <u><?php if($grtValue!="" ) { echo $grtValue; } else {echo $grt_doc_vsl_info;} ?></u></td>
					<td colspan="3">NRT : <u><?php if($nrtValue!="" ) { echo $nrtValue; } else {echo $nrt_doc_vsl_info;} ?></u></td>
					<td colspan="3">
						DECK CARGO : 
						<u>
							<?php 
								echo $rtnVesselDetails_shifting[$v]['Deck_cargo'];							
							?>
						</u>
					</td>
				</tr>
				<tr align="left">
					<td colspan="4">4. LOA : <u><?php if($loaCm != "") {echo $loaCm;} else {echo $loa_doc_vsl_info;} ?></u></td>
					<td colspan="6" class="lbl">MAX FW DRAUGHT : <u><?php echo $rtnVesselDetails_shifting[$v]['draught']; ?></u></td>

				</tr>
				<tr align="left">
					<td colspan="10" class="lbl">5. NAME AND ADDRESS OF OWNERS : -------------------------------------------------------------</td>
				</tr>
				<tr align="left">
					<td colspan="10" class="lbl">6. LOCAL AGENT : <u>
						<?php 
							if($localAgent != ""){
								echo $localAgent; 
							} else {
								echo $local_agent_doc_vsl_info; 
							}
							
						?>
					</u></td> 
					
				</tr>
				<tr align="left">
					<td colspan="4" class="lbl">7. NAME OF PILOT : 
						<u>
							<?php 
								echo $rtnVesselDetails_shifting[$v]['pilot_name']; 
							?>
						</u>
					</td>
					<td colspan="3" align="right">BOARDED AT : <u>
						<?php 
							if($rtnVesselDetails_shifting[$v]['pilot_on_board'] != "0000-00-00 00:00:00"){
								echo $rtnVesselDetails_shifting[$v]['pilot_on_board'];
							} else {
								echo "";
							}
							
						?>
						</u></td>
					<td colspan="2" align="right">LEFT AT : <u>
						<?php 
							if($rtnVesselDetails_shifting[$v]['pilot_off_board'] != "0000-00-00 00:00:00"){
								echo $rtnVesselDetails_shifting[$v]['pilot_off_board'];
							} else {
								echo "";
							}
						?>
					</u></td>
					<td colspan="1" align="right">DT : -----------</td>
				</tr>
				<tr align="left">
					<td colspan="4">8. SHIFTED/SWUNG FROM : <u>
						<?php 
							echo $rtnVesselDetails_shifting[$v]['shift_frm'];
						?>
						</u>
						</td>
					<td colspan="6" align="right">   TO : <u>
						<?php 
							echo $rtnVesselDetails_shifting[$v]['shift_to'];
						?>
						</u></td>
				</tr>
				<tr align="left">
					<td colspan="4" >9. TIME OF MOORING/UNMOORING FROM : <u>
						<?php 
							if($rtnVesselDetails_shifting[$v]['mooring_frm_time'] != "0000-00-00 00:00:00"){
								echo $rtnVesselDetails_shifting[$v]['mooring_frm_time'];
							} else {
								echo "";
							}
						?>
						</u></td>
					<td colspan="3">TO : <u>
						<?php 
							if($rtnVesselDetails_shifting[$v]['mooring_to_time'] != "0000-00-00 00:00:00"){
								echo $rtnVesselDetails_shifting[$v]['mooring_to_time'];
							} else {
								echo "";
							}
						?>
						</u></td>
					<td colspan="3">DT : <u>
						<?php 
							if($rtnVesselDetails_shifting[$v]['shift_dt'] != "0000-00-00 00:00:00"){
								echo $rtnVesselDetails_shifting[$v]['shift_dt'];
							} else {
								echo "";
							}	
						?>
						</u></td>
				</tr>
				<tr align="left">
					<td colspan="4">10. CPA TUG/TUGS(NAME) : <u>
						<?php 
							echo $rtnVesselDetails_shifting[$v]['tug_name'];								 
						?>
					</u></td>
					<!--td colspan="4">10. CPA TUG/TUGS(NAME) : <u><?php  echo "1"; ?></u></td-->
					<td colspan="3">ASSISTANCE FROM : <u>
						<?php 
							if($rtnVesselDetails_shifting[$v]['assit_frm'] != "0000-00-00 00:00:00"){
								echo $rtnVesselDetails_shifting[$v]['assit_frm'];
							} else {
								echo "";
							}
						?>
					</u></td>
					<td colspan="2">TO : <u>
						<?php 
							if($rtnVesselDetails_shifting[$v]['assit_to'] != "0000-00-00 00:00:00"){
								echo $rtnVesselDetails_shifting[$v]['assit_to'];
							} else {
								echo "";
							}							
						?>
						</u></td>
					<td colspan="1">DT : <u>
						<?php 
							if($rtnVesselDetails_shifting[$v]['shift_dt'] != "0000-00-00 00:00:00"){
								echo $rtnVesselDetails_shifting[$v]['shift_dt'];
							} else {
								echo "";
							} 
						?>
						</u></td>
				</tr>
				<tr align="left">
					<td colspan="6">11. MAIN ENGINES IN GOOD WORKING CONDITION?</td> 
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_main_engine_ok']=="1") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?> 
						YES
						<!--input type="checkbox" class="radio" value="1" name="shift_good_cond_yes" id="shift_good_cond_yes" checked /-->
					</td>
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_main_engine_ok']=="0") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?> NO
					</td>
				</tr>
				<tr align="left">
					<td colspan="6">12. TWO ANCHORS IN GOOD WORKING CONDITION?</td>
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_acnchors_ok']=="1") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?>  YES
						<!--input type="checkbox" class="radio" value="1" name="shift_two_anchors_yes" id="shift_two_anchors_yes" checked /-->
					</td>
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_acnchors_ok']=="0") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?> NO
					</td>
				</tr>
				<tr align="left">
					<td colspan="6">13. RUDDER INDICATOR IN GOOD WORKING CONDITION?</td>
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_rudder_indicator_ok']=="1") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?> YES
						<!--input type="checkbox" class="radio" value="1" name="shift_rudded_indicator_yes" id="shift_rudded_indicator_yes" checked /-->
					</td>
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_rudder_indicator_ok']=="0") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?> NO
					</td>
				</tr>
				<tr align="left">
					<td colspan="6">14. RPM INDICATOR IN GOOD WORKING CONDITION?</td>
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_rpm_indicator_ok']=="1") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?> YES
						<!--input type="checkbox" class="radio" value="1" name="shift_rpm_indicator_yes" id="shift_rpm_indicator_yes" /-->
					</td>
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_rpm_indicator_ok']=="0") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?> NO
					</td>
				</tr>
				<tr align="left">
					<td colspan="6">15. BOW THURSTER AVAILABLE?</td>
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_bow_therster_available']=="1") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?> YES
						<!--input type="checkbox" class="radio" value="1" name="shift_bow_thurster_yes" id="shift_bow_thurster_yes" /-->
					</td>
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_bow_therster_available']=="0") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?> NO
					</td>

				</tr>
				<tr align="left">
					<td colspan="6">16. ARE YOU COMPLYING SOLAS CONVENTION?</td>
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_complying_soal_convention']=="1") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?> YES
						<!--input type="checkbox" class="radio" value="1" name="shift_solas_convention_yes" id="shift_solas_convention_yes" /-->
					</td>
					<td colspan="2">
						<?php if($rtnVesselDetails_shifting[$v]['is_complying_soal_convention']=="0") { ?>
					   <img src="<?php echo IMG_PATH?>check-box.png" />
					   <?php } else { ?>
					   <img src="<?php echo IMG_PATH?>unchecked.png" />
					   <?php }?> NO
					</td>
				</tr>
				<tr align="left">
					<td colspan="8" >17. NOS. OF GOOD MOORING LINES: FORD : -------------------------------------</td>
					<td colspan="2">AFT : -----------------------------------</td>
				</tr>
				<tr align="left">
					<td colspan="6">18. STERN POWER AVAILABLE : -----------</td>
					<td colspan="2">IMMEDIATELY : -----------</td>
					<td colspan="2">SECS.LATER</td>						
				</tr>
				<tr align="left">
					<td colspan="10" class="lbl">
						19. REMARKS IF ANY :- 
						<?php echo $rtnVesselDetails_shifting[$v]['remarks']; ?>
					</td>
				</tr>
				
				<?php
				if($rtnVesselDetails_shifting[$v]['is_night']==1)
				{
				?>
				<tr align="left">
					<td colspan="10" class="lbl"><b>NIGHT SHIFT</b></td>
				</tr>					
				<?php
				}
				if($rtnVesselDetails_shifting[$v]['is_holiday']==1)
				{
				?>
				<tr align="left">
					<td colspan="10" class="lbl"><b>HOLIDAY</b></td>
				</tr>	
				<?php
				}
				?>
				
				<tr align="left">
					<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CERTIFIED THAT THE ABOVE PARTICULARS ARE CORRECT AND CHARGES THEREOF</td>						
				</tr>
				<tr align="left">
					<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WILL BE PAID BY US/LOCAL AGENT INCLUSIVE OF OTHER PORT CHARGES.</td>						
				</tr>
				<tr><td colspan="10" class="lbl"></td></tr>
				<tr><td colspan="10" class="lbl"></td></tr>
				<tr><td colspan="10" class="lbl"></td></tr>
				<tr>
					<!--td colspan="4" class="lbl">
						<?php if($rtnVesselDetails_shifting[$v]['photo_base_64'] != "" || $rtnVesselDetails_shifting[$v]['photo_base_64'] != null){ ?>
							<img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $rslt_show_current_data[0]['photo_base_64']; ?>"/>
						<?php } ?>
					</td-->	
					
					<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<u>
						<?php 
							if($rtnVesselDetails_shifting[$v]['mooring_frm_time'] != "0000-00-00 00:00:00"){
								echo date("Y-m-d", strtotime($rtnVesselDetails_shifting[$v]['mooring_frm_time']));
							} else {
								echo "";
							}
						?>
						</u>
					</td>	
					<td colspan="6" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---------------------------------------------</td>	
				</tr>
				<tr>
					<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DATE</td>	
					<td colspan="6" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MASTER</td>	
				</tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr align="left">
						<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FORWARDED TO THE CHIEF FINANCE & ACCOUNTS OFFICER,PORT AUTHORITY</td>						
					</tr>
					<tr align="left">
						<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CHITTAGONG FOR NECESSARY ACTION.</td>						
					</tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr>
						<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php if(count($rtnVesselDetails_shifting)>0) { ?> <img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php echo @$rtnVesselDetails_shifting[$v]['pilot_login'];?>.png"/> <?php  } else { echo ""; } ?></td>	
						<td colspan="6" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--------------------</td>	
						<td colspan="6" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-----------------------</td>
					</tr>
					<tr>
						<td colspan="4" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AHM/PILOT</td>	
						<td colspan="6" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPUTY CONSERVATOR/HARBOUR MASTER</td>						
					</tr>
					<tr>
						<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>CHITTAGONG PORT AUTHORITY</u></td>	
						<td colspan="6" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>CHITTAGONG PORT AUTHORITY</u></td>	
					</tr>
			</table>
		</div>
		<?php } } if ($igm_id_depart>0) 
		{
			for($d=0; $d<count($rtnVesselDetails_depart); $d++)
			{
		?>
		<div style="background: #E6E45A; font-family: Times New Roman;font-size: 10px; height:1050px;">
			<table class="tbl" width="100%" border ='0' cellpadding='0' cellspacing='0' >
				<tr align="center">
					<td  colspan="10" align="center" style="border:none;"><font size="5"><b><u>THE CHITTAGONG PORT AUTHORITY</u></b></font></td>	
				</tr>
				<tr align="center">
					<td colspan="10" align="center" style="border:none;"> 
						<font size="5"><b><u>DEPARTURE REPORT OF VESSEL AND PILOTAGE CERTIFICATE</u></b></font> 
					</td>
				</tr>
				<!--tr align="center">
					<td colspan="10"  align="center" style="border:none;"><font size="5"><b>DEPARTURE REPORT OF VESSEL <BR> AND</b></font></td>
				</tr>
				<tr align="center">
					<td colspan="10" align="center" style="border:none;"> <font size="5"><b><u>PILOTAGE CERTIFICATE</u></b></font> </td>
				</tr-->
				<tr align="left">
					
					<td colspan="4">
						1.&nbsp;&nbsp;&nbsp;VESSEL NAME : 
						<u><?php if($vesselName!="") { echo $vesselName; } else { echo $vsl_name_doc_vsl_info; } ?></u>
					</td>
					<td colspan="3">CALL SIGN : <u><?php echo $radioCallSign; ?></u></td>
					<td colspan="3">FLAG : <u><?php if($flagValue != "") { echo $flagValue; } else { echo $flag_doc_vsl_info; } ?></u></td>
				</tr>
				<tr align="left">
					<td colspan="10" class="lbl">2.&nbsp;&nbsp;&nbsp;NAME OF MASTER : 
						<u>
							<?php 
								if($rtnVesselDetails_igm[0]['Name_of_Master'] != ""){
									echo $rtnVesselDetails_igm[0]['Name_of_Master'];
								} else {
									echo $master_name_doc_vsl_info;
								}								 
							?>
						</u>
					</td>
				</tr>
				<tr align="left">
						<td colspan="3">3.&nbsp;&nbsp;&nbsp;GRT : <u>
							<?php if($grtValue != "") { echo $grtValue; } else { echo $grt_doc_vsl_info; } ?>							
						</u></td>
						<td colspan="3">NRT : <u><?php if($nrtValue != "") { echo $nrtValue; } else { echo $nrt_doc_vsl_info; } ?></u></td>
						<td colspan="4">DECK CARGO : <u>
						<?php 
							if($rtnVesselDetails_igm[0]['Deck_cargo'] != "") {echo $rtnVesselDetails_igm[0]['Deck_cargo'];} 
							else { echo $deck_cargo_doc_vsl_info; }  
						?>
						</u></td>
				</tr>
				<tr align="left">
						<td colspan="4">
							4.&nbsp;&nbsp;&nbsp;LOA : 
							<u><?php if($loaCm != "") { echo $loaCm; } else { echo $loa_doc_vsl_info; } ?></u>
						</td>
						<td colspan="6">MAX FW DRAUGHT : <u>
							<?php echo $draught_depart;  ?>
						</u></td>
				</tr>
				<tr align="left">
						<td colspan="4" class="lbl">5.&nbsp;&nbsp;&nbsp;NAME AND ADDRESS OF OWNERS</td>
						<td colspan="6">-------------------------------------------------------------</td>
				</tr>
				<tr align="left">
						<td colspan="4">6.&nbsp;&nbsp;&nbsp;LOCAL AGENT : <u>
							<?php if($localAgent != "") { echo $localAgent; } else { echo $local_agent_doc_vsl_info; }  ?>
						</u></td>
						<td colspan="3">LAST PORT : <u><?php echo $last_port_doc_vsl_info; ?></u></td>
						<td colspan="3">
							NEXT PORT : 
							<u>
								<?php 
									if($rtnVesselDetails_igm[0]['Port_of_Destination'] != "") { 
										echo $rtnVesselDetails_igm[0]['Port_of_Destination']; 
									} else {
										echo $next_port_doc_vsl_info;
									} 
								?>
							</u>
						</td>
				</tr>
				<tr align="left">
						<td colspan="3">
							7.&nbsp;&nbsp;&nbsp;NAME OF PILOT : <u>
								<?php 
									if($chk_igm_id>0) { echo $rtnVesselDetails_depart[$d]['pilot_name']; } 
									else { echo $pilot_name_depart; } 
								?>
								</u>
						</td>
						<td colspan="2">
							BOARDED AT : <u>
								<?php 
									if($chk_igm_id>0) { 
										if($rtnVesselDetails_depart[$d]['pilot_on_board'] == "0000-00-00 00:00:00" 
														or 
											$rtnVesselDetails_depart[$d]['pilot_on_board'] == ""){
												
											if($pilot_on_board_depart == "0000-00-00 00:00:00"){
												echo "";
											} else {
												echo $pilot_on_board_depart;
											}											
										} else {
											echo $rtnVesselDetails_depart[$d]['pilot_on_board'];
										} 
									} 
									else { 
										if($pilot_on_board_depart == "0000-00-00 00:00:00"){
											echo "";
										} else {
											echo $pilot_on_board_depart;
										}
									} 
								?>
								</u>
						</td>
						<td colspan="3">LEFT AT : <u>
							<?php 
								if($chk_igm_id>0) { 
									if($rtnVesselDetails_depart[$d]['pilot_off_board'] == "0000-00-00 00:00:00" 
														or 
											$rtnVesselDetails_depart[$d]['pilot_off_board'] == ""){
												
											if($pilot_off_board_depart == "0000-00-00 00:00:00"){
												echo "";
											} else {
												echo $pilot_off_board_depart;
											}											
										} else {
											echo $rtnVesselDetails_depart[$d]['pilot_off_board'];
										}
								} else { 
									if($pilot_off_board_depart == "0000-00-00 00:00:00"){
										echo "";
									} else {
										echo $pilot_off_board_depart;
									}
								} 
							?>
							</u></td>
						<td colspan="2">DT : -----------</td>

					</tr>
					<tr align="left">
						<td colspan="4" >8.&nbsp;&nbsp;&nbsp;PILOTAGE FROM : <u>
							<?php 
								if($chk_igm_id>0) { 
									if($rtnVesselDetails_depart[$d]['pilot_frm'] == "0000-00-00 00:00:00" 
													or 
										$rtnVesselDetails_depart[$d]['pilot_frm'] == ""){
											
										if($pilot_frm_depart == "0000-00-00 00:00:00"){
											echo "";
										} else {
											echo $pilot_frm_depart;
										}											
									} else {
										echo $rtnVesselDetails_depart[$d]['pilot_frm'];
									}
								} else { 
									if($pilot_frm_depart == "0000-00-00 00:00:00"){
										echo "";
									} else {
										echo $pilot_frm_depart;
									}
								} 
							?>
							</u></td>
						<td colspan="3">TO : <u>
							<?php 
								if($chk_igm_id>0) { 
									if($rtnVesselDetails_depart[$d]['pilot_to'] == "0000-00-00 00:00:00" 
													or 
										$rtnVesselDetails_depart[$d]['pilot_to'] == ""){
											
										if($pilot_to_depart == "0000-00-00 00:00:00"){
											echo "";
										} else {
											echo $pilot_to_depart;
										}											
									} else {
										echo $rtnVesselDetails_depart[$d]['pilot_to'];
									}
								} else { 
									if($pilot_to_depart == "0000-00-00 00:00:00"){
										echo "";
									} else {
										echo $pilot_to_depart;
									}
								} 
							?>
							</u></td>
						<td colspan="3">DT : -----------------</td>
					</tr>
					<tr align="left">
						<td colspan="4">9.&nbsp;&nbsp;&nbsp;DATE OF ARRAIVAL IN PORT : <u><?php if($chk_igm_id>0) { echo $ata; } else { echo "----------------"; } ?></u></td>
						<td colspan="6">DATE AND HOUR OF BERTHING : --------------------------</td>
					</tr>
					<tr align="left">
						<td colspan="4" >10. DATE OF DEPARTURE : <u>
							<?php 
								if($chk_igm_id>0) {
									if($rtnVesselDetails_depart[$d]['atd'] == "0000-00-00 00:00:00" 
													or 
										$rtnVesselDetails_depart[$d]['atd'] == ""){
											
										if($atd_depart == "0000-00-00 00:00:00"){
											echo "";
										} else {
											echo $atd_depart;
										}											
									} else {
										echo $rtnVesselDetails_depart[$d]['atd'];
									}
								} else { 
									if($atd_depart == "0000-00-00 00:00:00"){
										echo "";
									} else {
										echo $atd_depart;
									} 
								} 
							?>
							</u></td>
						<td colspan="6" >DEP.DRAFT(MAX) : ------------------</td>
					</tr>
					<tr align="left">
						<td colspan="4">11. TIME OF UNMOORING FROM : <u>
							<?php 
								if($chk_igm_id>0) {
									if($rtnVesselDetails_depart[$d]['mooring_frm_time'] == "0000-00-00 00:00:00" 
													or 
										$rtnVesselDetails_depart[$d]['mooring_frm_time'] == ""){
											
										if($mooring_frm_time_depart == "0000-00-00 00:00:00"){
											echo "";
										} else {
											echo $mooring_frm_time_depart;
										}											
									} else {
										echo $rtnVesselDetails_depart[$d]['mooring_frm_time'];
									}
								} else { 
									if($mooring_frm_time_depart == "0000-00-00 00:00:00"){
										echo "";
									} else {
										echo $mooring_frm_time_depart;
									} 
								} 
							?>
							</u></td>
						<td colspan="3">TO : <u>
							<?php 
								if($chk_igm_id>0) { 
									if($rtnVesselDetails_depart[$d]['mooring_to_time'] == "0000-00-00 00:00:00" 
													or 
										$rtnVesselDetails_depart[$d]['mooring_to_time'] == ""){
											
										if($mooring_to_time_depart == "0000-00-00 00:00:00"){
											echo "";
										} else {
											echo $mooring_to_time_depart;
										}											
									} else {
										echo $rtnVesselDetails_depart[$d]['mooring_to_time'];
									}
								} else { 
									if($mooring_to_time_depart == "0000-00-00 00:00:00"){
										echo "";
									} else {
										echo $mooring_to_time_depart;
									}
								} 
							?>
								</u></td>
						<td colspan="3">DT : ---------------------------------</td>
					</tr>
					<tr align="left">
						<td colspan="3" class="lbl">12. CPA TUG/TUGS(NAME) : <u>
							<?php 
								if($chk_igm_id>0) {
									if($rtnVesselDetails_depart[$d]['tug_name'] == ""){
											
										if($tug_name_depart == ""){
											echo "";
										} else {
											echo $tug_name_depart;
										}											
									} else {
										echo $rtnVesselDetails_depart[$d]['tug_name'];
									} 
								} else { 
									if($tug_name_depart == ""){
										echo "";
									} else {
										echo $tug_name_depart;
									} 
								} 
							?>
						</u></td>
						<!--td colspan="3" class="lbl">12. CPA TUG/TUGS(NAME) : <u><?php echo "1"; ?></u></td-->
						<td colspan="3" class="lbl">ASSISTANCE FROM : <u>
							<?php 
								if($chk_igm_id>0) { 
									if($rtnVesselDetails_depart[$d]['assit_frm'] == "0000-00-00 00:00:00" 
													or 
										$rtnVesselDetails_depart[$d]['assit_frm'] == ""){
											
										if($assit_frm_depart == "0000-00-00 00:00:00"){
											echo "";
										} else {
											echo $assit_frm_depart;
										}											
									} else {
										echo $rtnVesselDetails_depart[$d]['assit_frm'];
									} 
								} else { 
									if($assit_frm_depart == "0000-00-00 00:00:00"){
										echo "";
									} else {
										echo $assit_frm_depart;
									}
								} 
							?>
						</u></td>
						<td colspan="2" >TO : <u>
							<?php 
								if($chk_igm_id>0) {
									if($rtnVesselDetails_depart[$d]['assit_to'] == "0000-00-00 00:00:00" 
													or 
										$rtnVesselDetails_depart[$d]['assit_to'] == ""){
											
										if($assit_to_depart == "0000-00-00 00:00:00"){
											echo "";
										} else {
											echo $assit_to_depart;
										}											
									} else {
										echo $rtnVesselDetails_depart[$d]['assit_to'];
									}
								} else { 
									if($assit_to_depart == "0000-00-00 00:00:00"){
										echo "";
									} else {
										echo $assit_to_depart;
									} 
								} 
							?>
						</u></td>
						<td colspan="2" >DT : -------------------------</td>
					</tr>
					<tr align="left">
						<td colspan="4" class="lbl">13. PC NO : -----------------------------</td>
						<td colspan="6" class="lbl">DT : ----------------------------------</td>
					</tr>
					<tr align="left">
						<td colspan="10">14. TONS OF CARGO ON BOARD : ------------------------------------------------</td>
					</tr>
					<tr align="left">
						<td colspan="6" class="lbl">15. MAIN ENGINES IN GOOD WORKING CONDITION?</td>
						<td colspan="2">
							<?php if($is_main_engine_ok_depart=="1") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> YES
						</td>
						<td colspan="2">
							<?php if($is_main_engine_ok_depart=="0") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> NO
						</td>

					</tr>
					<tr align="left">
						<td colspan="6" class="lbl">16. TWO ANCHORS IN GOOD WORKING CONDITION?</td>
						<td colspan="2">
							<?php if($is_acnchors_ok_depart=="1") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> YES
						</td>
						<td colspan="2">
							<?php if($is_acnchors_ok_depart=="0") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> NO
						</td>

					</tr>
					<tr align="left">
						<td colspan="6" class="lbl">17. RUDDER INDICATOR IN GOOD WORKING CONDITION?</td>
						<td colspan="2">
							<?php if($is_rudder_indicator_ok_depart=="1") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> YES
						</td>
						<td colspan="2">
							<?php if($is_rudder_indicator_ok_depart=="0") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> NO
						</td>

					</tr>
					<tr align="left">
						<td colspan="6" class="lbl">18. RPM INDICATOR IN GOOD WORKING CONDITION?</td>
						<td colspan="2">
							<?php if($is_rpm_indicator_ok_depart=="1") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> YES
						</td>
						<td colspan="2">
							<?php if($is_rpm_indicator_ok_depart=="0") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> NO
						</td>

					</tr>
					<tr align="left">
						<td colspan="6" class="lbl">19. BOW THURSTER AVAILABLE?</td>
						<td colspan="2">
							<?php if($is_bow_therster_available_depart=="1") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> YES
						</td>
						<td colspan="2">
							<?php if($is_bow_therster_available_depart=="0") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> NO
						</td>

					</tr>
					<tr align="left">
						<td colspan="6" class="lbl">20. ARE YOU COMPLYING SOLAS CONVENTION?</td>
						<td colspan="2">
							<?php if($is_complying_soal_convention_depart=="1") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> YES
						</td>
						<td colspan="2">
							<?php if($is_complying_soal_convention_depart=="0") { ?>
						   <img src="<?php echo IMG_PATH?>check-box.png" />
						   <?php } else { ?>
						   <img src="<?php echo IMG_PATH?>unchecked.png" />
						   <?php }?> NO
						</td>

					</tr>
					<tr align="left">
						<td colspan="4">21. NOS. OF GOOD MOORING LINES:FORD : ------</td>
						<td colspan="6">AFT : ------</td>
					</tr>
					<tr align="left">
						<td colspan="4" class="lbl">22. STERN POWER AVAILABLE : -----------------</td>
						<td colspan="3" class="lbl">IMMEDIATELY : ---------------</td>
						<td colspan="3" class="lbl">SECS.LATER</td>						
					</tr>
					<tr align="left">
						<td colspan="10" class="lbl">23. REMARKS IF ANY :-<?php echo $remarks_depart; ?></td>
					</tr>
					
					<?php
					if($rtnVesselDetails_depart[$d]['is_night']==1)
					{
					?>
					<tr align="left">
						<td colspan="10" class="lbl"><b>NIGHT SHIFT</b></td>
					</tr>					
					<?php
					}
					if($rtnVesselDetails_depart[$d]['is_holiday']==1)
					{
					?>
					<tr align="left">
						<td colspan="10" class="lbl"><b>HOLIDAY</b></td>
					</tr>	
					<?php
					}
					?>
					
					<tr align="left">
						<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CERTIFIED THAT THE ABOVE PARTICULARS ARE CORRECT AND CHARGES THEREOF</td>						
					</tr>
					<tr align="left">
						<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WILL BE PAID BY US/LOCAL AGENT INCLUSIVE OF OTHER PORT CHARGES.</td>						
					</tr>
					<!-- -->
					<tr><td colspan="4">&nbsp;</td></tr>
						<tr><td colspan="4">&nbsp;</td>
						<td colspan="4" >
							<?php 
								if($rtnVesselDetails_depart[$d]['photo_base_64'] != "" || $rtnVesselDetails_depart[$d]['photo_base_64'] != null){ ?><img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $rtnVesselDetails_depart[$d]['photo_base_64']; ?>"/> <?php } ?></td>
						</tr>

						<tr>
						<td colspan="4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<u>&nbsp;&nbsp;&nbsp;&nbsp;
								<?php if($rtnVesselDetails_depart[$d]['sign_arraival']!=""){echo $rtnVesselDetails_depart[$d]['sign_arraival'];} else 
								{echo date("Y-m-d", strtotime($rtnVesselDetails_depart[$d]['mooring_to_time']));}  ?>&nbsp;&nbsp;&nbsp;&nbsp;
							</u>
						</td>	
						<!--td colspan="4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "2018-09-26";?>&nbsp;&nbsp;&nbsp;&nbsp;</u></td-->
						<td colspan="4">------------------------------------------------------------</td>		
						</tr>
					<!-- -->
					<!--tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr>
						<td colspan="4" class="lbl"><?php 
								if($rtnVesselDetails_depart[$d]['photo_base_64'] != "" || $rtnVesselDetails_depart[$d]['photo_base_64'] != null){ ?><img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $rtnVesselDetails_depart[$d]['photo_base_64']; ?>"/> <?php } ?></td>	
						
						<td colspan="6" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---------------------------------------------</td>	
					</tr-->
					<tr>
						<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DATE</td>	
						<td colspan="6" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MASTER</td>	
					</tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr align="left">
						<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FORWARDED TO THE CHIEF FINANCE & ACCOUNTS OFFICER,PORT AUTHORITY</td>						
					</tr>
					<tr align="left">
						<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CHITTAGONG FOR NECESSARY ACTION.</td>						
					</tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr>
						<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php if(count($rtnVesselDetails_depart)>0) { ?> <img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php echo @$rtnVesselDetails_depart[$d]['pilot_login'];?>.png"/> <?php  } else { echo ""; } ?></td>	
						<td colspan="6" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--------------------</td>	
						<td colspan="6" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-----------------------</td>
					</tr>
					<tr>
						<td colspan="4" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AHM/PILOT</td>	
						<td colspan="6" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPUTY CONSERVATOR/HARBOUR MASTER</td>						
					</tr>
					<tr>
						<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>CHITTAGONG PORT AUTHORITY</u></td>	
						<td colspan="6" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>CHITTAGONG PORT AUTHORITY</u></td>	
					</tr>
			</table>
		</div>
		<?php 
		} } if (isset($igm_id_cancel) && $igm_id_cancel>0) 
		{
			for($d=0; $d<count($rtnVesselDetails_cancel); $d++)
			{
		?>
			<div style="background: #FCBDF2; font-family: Times New Roman;font-size: 10px; height:100%; padding-top:15px;">
			<table class="tbl" width="100%" border ='' cellpadding='0' cellspacing='0' >
				<tr>
					<td colspan="10" align="center">
						<table width="100%" border ='0' cellpadding='0' cellspacing='0' >
							<tr>
								<td rowspan="3" align="center" style="border:none;"><img src="<?php echo IMG_PATH?>cpa_logo.png" height="70px"></td>
								<td align="center" style="border:none;"><font size="5"><b>(To be returned duly filled in and endorsed in triplicate)</b></font></td>
								<td rowspan="3" align="center" style="border:none;">
									<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
										<tr>
											<td style="border-bottom:1px solid black;"><font size="5">M-65</font></td>
											<td rowspan="2"><font size="5">/12</font></td>
										</tr>
										<tr>
											<td><font size="5">C.P.A</font></td>
										</tr>
									</table>
								</td>	
							</tr>
							<tr>
								<td align="center" style="border:none;"><font size="7"><b>THE CHITTAGONG PORT AUTHORITY</b></font></td>	
							</tr>
							<tr>
								<td align="center" style="border-bottom:1px solid black;"> 
									<font size="5"><b>PILOTAGE CERTIFICATE FOR CANCELLATION OF MOVEMENT</b></font> 
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr align="left">
					<td colspan="4">
						1.&nbsp;&nbsp;&nbsp;NAME OF VESSEL : <u><?php echo $vesselName; ?></u>
					</td>
					<td colspan="3">CALL SIGN : <u><?php echo $radioCallSign; ?></u></td>
					<td colspan="3">FLAG : <u><?php echo $flagValue; ?></u></td>
				</tr>
				<tr align="left">
					<td colspan="10" class="lbl">2.&nbsp;&nbsp;&nbsp;NAME OF MASTER : <u><?php echo $rtnVesselDetails_igm[0]['Name_of_Master']; ?></u></td>
				</tr>
				<tr align="left">
						<td colspan="5">3.&nbsp;&nbsp;&nbsp;GRT : <u><?php echo $grtValue; ?></u></td>
						<td colspan="5">NRT : <u><?php echo $nrtValue; ?></u></td>
				</tr>
				<tr align="left">
						<td colspan="5">4.&nbsp;&nbsp;&nbsp;Max Fresh Water Draft : <u><?php echo $beamCm; ?></u></td>
						<td colspan="5">DECK CARGO : <u><?php echo $rtnVesselDetails_igm[0]['Deck_cargo']; ?></u></td>
				</tr>
				<tr align="left">
						<td colspan="10" class="lbl">5.&nbsp;&nbsp;&nbsp;NAME OF LOCAL AGENT : <u><?php echo $localAgent; ?></td>
				</tr>
				<!-- <tr align="left">
						<td colspan="4">6.&nbsp;&nbsp;&nbsp;LOCAL AGENT : <u><?php //echo $localAgent; ?></u></td>
						<td colspan="3">LAST PORT : -----------</td>
						<td colspan="3">NEXT PORT : <u><?php //echo $rtnVesselDetails_igm[0]['Port_of_Destination']; ?></u></td>
				</tr> -->
				<tr align="left">
						<td colspan="4">
							6.&nbsp;&nbsp;&nbsp;NAME OF PILOT : <u><?php if($chk_igm_id>0) { echo $rtnVesselDetails_cancel[$d]['pilot_name']; } else { echo "---------"; } ?></u>
						</td>
						<td colspan="3">
							BOARDED : 
							<u>
								<?php 
									if($chk_igm_id>0) { 
										if(
											$rtnVesselDetails_cancel[$d]['pilot_on_board']=="0000-00-00 00:00:00" 
															or 
											$rtnVesselDetails_cancel[$d]['pilot_on_board']=="0000-00-00" 
										){
											echo "";
										}
										else {
											echo $rtnVesselDetails_cancel[$d]['pilot_on_board'];
										}										 
									} else { echo "--------"; } 
								?>
							</u>
						</td>
						<td colspan="3">
							LEFT : 
							<u>
								<?php 
									if($chk_igm_id>0) { 
										if(
											$rtnVesselDetails_cancel[$d]['pilot_off_board']=="0000-00-00 00:00:00" 
															or 
											$rtnVesselDetails_cancel[$d]['pilot_off_board']=="0000-00-00" 
										){
											echo "";
										}
										else {
											echo $rtnVesselDetails_cancel[$d]['pilot_off_board'];
										}										 
									} 
									else { echo "--------"; } 
								?>
							</u>
						</td>
						<!-- <td colspan="2">DT : -----------</td> -->

					</tr>
					<tr align="left">
						<td colspan="10" >
							7.&nbsp;&nbsp;&nbsp;CANCELLED MOVEMENT FROM SEA/JETTY/MOORING NO. : 
							<u>
								<?php 
									echo $rtnVesselDetails_cancel[$d]['cancel_from']; 
								?>
							</u>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
						<td colspan="8">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TO SEA/JETTY/MOORING NO. : 
							<u>
								<?php 
									echo $rtnVesselDetails_cancel[$d]['cancel_to']; 
								?>
							</u> 
						</td>
					</tr>
					<tr align="left">
						<td colspan="5">
							8.&nbsp;&nbsp;&nbsp;CANCELLED AT : 
							<u>
								<?php 
									echo $rtnVesselDetails_cancel[$d]['cancel_at_time'];  
								?>
							</u>
						</td>
						<td colspan="5">HRS. ON : 
							<u>
								<?php 
									echo $rtnVesselDetails_cancel[$d]['cancel_at_date'];  
								?>
							</u>
						</td>
					</tr>
					<tr>
						<td colspan="10">&nbsp;</td>	
					</tr>
					<tr>
						<td colspan="10" style="padding-left:20px;">
							REASON : <u><?= $rtnVesselDetails_cancel[$d]['remarks']; ?></u>
						</td>	
					</tr>
					<!--<tr align="left">
						<td colspan="6" style="padding-left:25px;">
							(a) &nbsp;&nbsp;WHETHER APPROPRIATE PORT AUTHORITY INFORMED OF THE CANCELLATION
						</td>
						<td colspan="2">
							<img src="<?php //echo IMG_PATH?>check_mark_icon.jpg" /> YES
						</td>
						<td colspan="2">
							<input type="checkbox" class="radio" id="goodCondNo" name="d_goodCondNo" onclick="checkCond(0)" style="width: 99%;"/> NO
						</td>
					</tr>
					<tr align="left">
						<td colspan="6" style="padding-left:25px;">
							(b) &nbsp;&nbsp;WHETHER MOVEMENT CANCELLED AFTER THE PILOT BOARDED THE VESSEL
						</td>
						<td colspan="2">
							<img src="<?php //echo IMG_PATH?>check_mark_icon.jpg" /> YES
						</td>
						<td colspan="2">
							<input type="checkbox" class="radio" id="goodCondNo" name="d_goodCondNo" onclick="checkCond(0)" style="width: 99%;"/> NO
						</td>
					</tr>-->
					<tr>
						<td colspan="10">&nbsp;</td>	
					</tr>

					<tr align="left">
						<td colspan="10" >
							9. AS A RESULT OF THE ABOVE, THE FOLLOWING ARRANGEMENT MADE WERE ALSO CANCELLED<br/>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(PARTICULARS TO BE PROVIDED BY THE DY. CONSERVATOR's OFFICE.)
						</td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>	
					</tr>
					<tr align="left">
						<td colspan="4">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(a) &nbsp;&nbsp;PORT AUTHORITY TUG/TUGs(NAME) : 
						</td>
						<td colspan="2" align="left">
							FROM : <u></u>
						</td>
						<td colspan="2" align="left">
							HRS. TO : <u></u>
						</td>
						<td colspan="2" align="center">
							HRS.
						</td>
					</tr>
					<tr align="left">
						<td colspan="4">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(b) &nbsp;&nbsp;LAUNCHES(NAME) : 
						</td>
						<td colspan="2" align="left">
							FROM : <u></u>
						</td>
						<td colspan="2" align="left">
							HRS. TO : <u></u>
						</td>
						<td colspan="2" align="center">
							HRS.
						</td>
					</tr>
					<tr align="left">
						<td colspan="4">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(d) &nbsp;&nbsp;HAWSER BOATS : 
						</td>
						<td colspan="2" align="left">
							NOS FROM : <u></u>
						</td>
						<td colspan="2" align="left">
							HRS. TO : <u></u>
						</td>
						<td colspan="2" align="center">
							HRS.
						</td>
					</tr>
					<tr align="left">
						<td colspan="4">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(f) &nbsp;&nbsp;MOORING GANGS : 
						</td>
						<td colspan="2" align="left">
							NOS FROM : <u></u>
						</td>
						<td colspan="2" align="left">
							HRS. TO : <u></u>
						</td>
						<td colspan="2" align="center">
							HRS.
						</td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>	
					</tr>

					
					
					<?php
					if($rtnVesselDetails_cancel[$d]['is_night']==1)
					{
					?>
					<tr align="left">
						<td colspan="10" class="lbl"><b>NIGHT SHIFT</b></td>
					</tr>					
					<?php
					}
					if($rtnVesselDetails_cancel[$d]['is_holiday']==1)
					{
					?>
					<tr align="left">
						<td colspan="10" class="lbl"><b>HOLIDAY</b></td>
					</tr>	
					<?php
					}
					?>
					
					<tr align="left">
						<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CERTIFIED THAT THE ABOVE PARTICULARS ARE CORRECT AND CHARGES THERE OF</td>						
					</tr>
					<tr align="left">
						<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WILL BE PAID BY US/LOCAL AGENT INCLUSIVE OF OTHER PORT CHARGES.</td>						
					</tr>
					<!-- -->
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
						<td colspan="4" >
						<?php 
							if($rtnVesselDetails_cancel[$d]['photo_base_64'] != "" || $rtnVesselDetails_cancel[$d]['photo_base_64'] != null){ ?><img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $rtnVesselDetails_cancel[$d]['photo_base_64']; ?>"/> <?php } ?>
						</td>
					</tr>

					<tr>
						<td colspan="4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span style="border-bottom:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;
								<?php if($rtnVesselDetails_cancel[$d]['sign_cancel']!=""){echo $rtnVesselDetails_cancel[$d]['sign_cancel'];} else 
								{echo date("Y-m-d", strtotime($rtnVesselDetails_cancel[$d]['mooring_to_time']));}  ?>&nbsp;&nbsp;&nbsp;&nbsp;
							</span>
						</td>	
						<!--td colspan="4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "2018-09-26";?>&nbsp;&nbsp;&nbsp;&nbsp;</u></td-->
						<td colspan="6" align="center">--------------------------</td>		
					</tr>
					<!-- -->
					<!--tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr>
						<td colspan="4" class="lbl">
						<?php 
							if($rtnVesselDetails_cancel[$d]['photo_base_64'] != "" || $rtnVesselDetails_cancel[$d]['photo_base_64'] != null){ ?><img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $rtnVesselDetails_cancel[$d]['photo_base_64']; ?>"/> <?php } 
						?>
						</td>	
						
						<td colspan="6" align="center" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---------------------------------------------</td>	
					</tr-->
					<tr>
						<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DATE</td>	
						<td colspan="6" align="center" class="lbl">MASTER</td>	
					</tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr align="left">
						<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FORWARDED TO THE CHIEF FINANCE & ACCOUNTS OFFICERS,PORT AUTHORITY,</td>						
					</tr>
					<tr align="left">
						<td colspan="10" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CHITTAGONG FOR NECESSARY ACTION.</td>						
					</tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr><td colspan="10" class="lbl"></td></tr>
					<tr>
						<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp; <?php if(count($rtnVesselDetails_cancel)>0) { ?> <img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php echo @$rtnVesselDetails_cancel[$d]['pilot_login'];?>.png"/> <?php  } else { echo ""; } ?></td>	
						<td colspan="6" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--------------------------------------------------------</td>	
						<td colspan="6" align="center" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;------------------------------------------------------------------------</td>
					</tr>
					<tr>
						<td colspan="4" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AHM/PILOT</td>	
						<td colspan="6" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPUTY CONSERVATOR/HARBOUR MASTER</td>						
					</tr>
					<tr>
						<td colspan="4" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="border-bottom:1px solid black;">CHITTAGONG PORT AUTHORITY</span></td>	
						<td colspan="6" align="center" class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="border-bottom:1px solid black;">CHITTAGONG PORT AUTHORITY</span></td>	
					</tr>
			</table>
		</div>
		<?php
			}
		}
		?>
	</BODY>
</HTML>