<html>
	<head>
		<!--TITLE>BLOCKED CONTAINER LIST</TITLE-->
	    <style type="text/css">
        </style>
	</head>
	<body style="background-color: green;">
<div style="background-color: #c2b7b1;height: 100%; ">
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
					<td colspan="4"><font size="2px">1. VESSEL NAME : <u><?php echo $vlsdetails_n4_data[0]['NAME']; ?></u></font></td>
					<td colspan="3"><font size="2px">CALL SIGN : <u><?php echo $vlsdetails_n4_data[0]['RADIO_CALL_SIGN']; ?></u></font></td>
					<td colspan="3"><font size="2px">FLAG : <u><?php echo $vlsdetails_n4_data[0]['FLAG']; ?></u></font></td>
				</tr>
				<tr align="left">
					<td colspan="10"><font size="2px">2. NAME OF MASTER : <u><?php echo $vlsdetails_igm_data[0]['Name_of_Master']; ?></u></font></td>
				</tr>
				<tr align="left">
					<td colspan="5">3. GRT : <u><?php echo $vlsdetails_n4_data[0]['GROSS_REGISTERED_TON']; ?></u></td>
					<td colspan="5">NRT : <u><?php echo $vlsdetails_n4_data[0]['NET_REGISTERED_TON']; ?></u></td>
				</tr>
				<tr align="left">
					<td colspan="5">4. MAX. FRESH WATER DRAFT: <u><?php  ?></u></td>
					<td colspan="5">DECK CARGO : <u><?php echo $vlsdetails_igm_data[0]['deck_cargo']; ?></u></td>
				</tr>
				<tr align="left">
					<td colspan="10" class="lbl">5. LOCAL AGENT : <u><?php echo $vlsdetails_n4_data[0]['LOCALAGENT']; ?></u></td> 
					
				</tr>
				<tr align="left">
					<td colspan="3" align="left">6. NAME OF PILOT : <u><?php if( $vsl_cancel_data[0]['u_name'] !=""){ echo $vsl_cancel_data[0]['u_name'];}else { echo ""; }   //if(count($vsl_cancel_data)>0) { echo $vsl_cancel_data[0]['pilot_name']; } else { echo ""; } ?></u></td>
					<td colspan="4" align="left">BOARDED AT : <u><?php if(count($vsl_cancel_data)>0) { echo $vsl_cancel_data[0]['pilot_on_board']; } else { echo ""; } ?></u></td>
					<td colspan="3" align="left">LEFT AT : <u><?php if(count($vsl_cancel_data)>0) { echo $vsl_cancel_data[0]['pilot_off_board']; } else { echo ""; } ?></u></td>
				
				</tr>
				
				<tr align="left">
					<td colspan="10" >7. CANCELLED MOVEMENT FROM SEA/JETTY/MOORING NO.: <u><?php if(count($vsl_cancel_data)>0) { echo $vsl_cancel_data[0]['cancel_from']; } else {echo "";} ?></td>
				</tr>
				<tr align="left">
				    <td colspan="1" > </td>
				    <td colspan="9" >&nbsp;&nbsp;&nbsp; TO SEA/JETTY/MOORING NO.: <u><?php if(count($vsl_cancel_data)>0) { echo $vsl_cancel_data[0]['cancel_to']; } else {echo "";} ?></td>
				</tr>
				
				<tr align="left">
				    <td colspan="5" >8. CANCELLED AT   </td>
				    <td colspan="5" >HRS. ON <u><?php if(count($vsl_cancel_data)>0) { echo $vsl_cancel_data[0]['cancel_at']; } else {echo "";} ?></td>
				</tr>
				<tr align="left">
				    <td colspan="10" >REASON: <u><?php if(count($vsl_cancel_data)>0) { echo $vsl_cancel_data[0]['remarks']; } ?> </u> </td>
				</tr>
				<tr align="left">
					<td colspan="10" >9. DATE OF LAST VISIT OF THIS PORT<u><?php if(count($vsl_cancel_data)>0) { echo $vsl_cancel_data[0]['mooring_to_time']; } else {echo "";} ?></td>
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
				
				<tr><td colspan="10">&nbsp;</td></tr>
				<tr>
					<td  colspan="5" style="font-size: 15px; text-align: center;"><?php echo $vsl_cancel_data[0]['sign_cancel']; ?></td>
					<td  colspan="5" style="font-size: 15px; text-align: center;" ><?php if($rtn_vsl_arrival_info[0]['photo_base_64'] != "" || $rtn_vsl_arrival_info[0]['photo_base_64'] != null){ ?><img height="50px" width="190px" src="data:image/jpeg;charset=utf-8;base64,<?php echo $rtn_vsl_arrival_info[0]['photo_base_64']; ?>"/> <?php } ?></td>
				</tr>

				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------------</td>	
					<td colspan="5" style="font-size: 15px; text-align: center;">---------------------------------------</td>
					
				</tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;">DATE</td>	
					<td colspan="5" style="font-size: 15px; text-align: center;">MASTER</td>
					
				</tr>
				
				<tr>
					<td colspan="10">FORWARDED TO THE CHIEF FINANCE & ACCOUNTS OFFICER,PORT AUTHORITY,CHITTAGONG FOR NECESSARY ACTION</td>						
				</tr>
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr>
					<td colspan="5" style="font-size: 15px; text-align: center;"> <?php if(count($vsl_cancel_data)>0) { ?> <img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php echo $vsl_cancel_data[0]['pilot_name'];?>.png"/> <?php  } else { echo ""; } ?></td>
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
        </body>
    </html>