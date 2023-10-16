<html>
	<head>
		<!--title>RELEASE ORDER FOR DELEVERY</title>
		<style>
			@media print {
				@page { margin: 0.5cm; }
				body { margin: 1.6cm; }
			}
		</style-->
		<!-- <style>
			.p1 {
  				font-family: "Times New Roman", Times, serif;
			}

		</style> -->
	</head>
	
	<body>
		<?php 
		// for($i=0;$i<count($rtnContainerList);$i++)
		// {	
		$i=0;	
		?>
	<div id="borderDiv">
		<!--div class="portrait"-->
		<div class="pageBreak">
		<table width="100%" cellpadding="0" border="0" class="tblFont">
			<tr >
				<td colspan="5" align="center" valign="middle">
					<img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg">
				</td>
			</tr>
			<tr>
				<td colspan="5" style="margin-right50px:" align="right">
					PRO : 44945<?php  if($rtnContainerList[$i]['pr_number']){ ?><u><?php  echo $rtnContainerList[$i]['pr_number']; ?></u><?php   } ?>
				</td>
				
			</tr>
			<tr>
				<td colspan="5" align="center" valign="middle">	
					<h3>RELEASE ORDER FOR DELEVERY (CASH) C.P NO <u>
					<?php 
					if(count($rtnContainerList))
					{
						$cpbankcode=$rtnContainerList[$i]['cp_bank_code'];
						$cpno=$rtnContainerList[$i]['cp_no'];
						$cpyear=$rtnContainerList[$i]['cp_year'];
						$cpunit=$rtnContainerList[$i]['cp_unit'];
						$num_length = strlen($cpno);
					
						if($num_length == 4) 
						{
							$newcpno=$cpno;
						} 
						else if($num_length == 3)
						{
							$newcpno="0"."$cpno";
						}
						else if($num_length == 2)
						{
							$newcpno="00"."$cpno";
						}
						else if($num_length == 1)
						{
							$newcpno="000"."$cpno";
						}
						if($cpbankcode!=""&&$cpno!="")
						{
							echo $cpnoview=$cpbankcode.$cpunit."/".$cpyear."-"."$newcpno";
						}
					}
					?></u> OF <u><?php if(isset($rtnContainerList[$i]['cp_date'])) echo $rtnContainerList[$i]['cp_date']; ?></u></h3>
				</td>
			</tr>
			<tr>
				<td align="center" >
					VESSEL : <u><?php if(isset($rtnContainerList[$i]['Vessel_Name'])) {echo $rtnContainerList[$i]['Vessel_Name'];} else echo ""; ?></u>
				</td>
				<td align="center" >
					REG NO : <u><?php if(isset($rtnContainerList[$i]['Import_Rotation_No'])) echo  $rtnContainerList[$i]['Import_Rotation_No']?></u>
				</td>
				<td align="center">
					BL NO : <u><?php if(isset($rtnContainerList[$i]['BL_No'])) echo $rtnContainerList[$i]['BL_No']?></u>
				</td>
				<td align="center">
					BE NO : <u><?php echo $rtnContainerList[$i]['be_no']?></u> <!-- OF <u><?php// echo  $rtnContainerList[$i]['cnf_name']?></u> CONTAINER NO: <u><?php //echo  $rtnContainerList[$i]['cont_number']?> --></u>
				</td>				
				<td align="center">
					CONSIGNEE : <u><?php if(isset($rtnContainerList[0]['Notify_name'])) echo $rtnContainerList[0]['Notify_name']?></u>
				</td>				
			</tr>
		</table>
		
		<!--/br-->
		
		<table border="1" width="100%" cellpadding="0"  class="tblFont">
			<tr bgcolor="">
				<td align="center" rowspan="2">Marks & Number</td>
				<td align="center" rowspan="2">Quantity</td>
				<td align="center" rowspan="2">Unit</td>
				<td align="center" rowspan="2">Description</td>
				<td align="center" rowspan="2">Weight</td>
				<td align="center" rowspan="2">Measurement</td>				
				<td align="center" colspan="2">Landing Charges</td>
				<td align="center" rowspan="2">Date of Delivery</td>
				<td align="center" rowspan="2">Quantity applied for delivery</td>
				<td align="center" rowspan="2">Consignee's Signature & License No.</td>
				<td align="center" rowspan="2">S.O's Signature</td>
				<td align="center" colspan="2">Quantity Passed out</td>
				<td align="center" rowspan="2">Balance Due</td>
				<td align="center" rowspan="2">Signature of G.S. and Date</td>
			</tr>
			<tr bgcolor="">
				<td>Taka</td>
				<td>Ps.</td>
				<td>Figure</td>
				<td>In words</td>
			</tr>
			<!--tr height="150px" bgcolor=""-->
			<tr>
				<td><?php echo  $rtnContainerList[$i]['Pack_Marks_Number']; ?></td>
				<td><?php echo  $rtnContainerList[$i]['Pack_Number']; ?></td>
				<td><?php echo  $rtnContainerList[$i]['rcv_unit']; ?></td>
				<td><?php echo  $rtnContainerList[$i]['Description_of_Goods']; ?></td>
				<td><?php echo  $rtnContainerList[$i]['cont_weight']; ?></td>
				<td><?php echo  $rtnContainerList[$i]['Volume_in_cubic_meters']; ?>
					<?php 
						// include("dbConection.php");
						// for($j=0;$j<count($rtnContainerList);$j++)
						// {
							// $strQuerypos="SELECT sparcsn4.inv_unit_fcy_visit.last_pos_slot AS pos,
							// (SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No
							// FROM sparcsn4.inv_unit
							// INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
							// INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
							// INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
							// WHERE sparcsn4.inv_unit.id='".$rtnContainerList[$j]['cont_number']."' AND ib_vyg='".$rtnContainerList[$i]['Import_Rotation_No']."'";
							// //return;

							// $strPosForFcl = mysqli_query($con_sparcsn4,$strQuerypos);

							// $pos="";
							// $yard_No="";

							// while($row=mysqli_fetch_object($strPosForFcl))
							// {
								// $pos = $row->pos;
								// $yard_No = $row->Yard_No;
							// }
							
							
							// echo $rtnContainerList[$j]['cont_number']."-".$rtnContainerList[$i]['cont_size']."*".$rtnContainerList[$i]['cont_height']." ".$pos."-".$yard_No;
							// echo "<br><br>";														
						// }
						// echo  $rtnContainerList[$i]['cont_size']." * ".$rtnContainerList[$i]['cont_height']; 
					?>												
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<?php 
						if($rtnContainerList[$i]['gate_out_time']==null or $rtnContainerList[$i]['gate_out_time']=="")
						{
							echo "2021-10-19 14:15:29";
						}
						else
						{
							echo $rtnContainerList[$i]['gate_out_time'];
						} 
					?>
				</td>
				<td>
					<?php if($rtnContainerList[$i]['actual_delv_pack']==null){ echo "75.0"; } else{ echo  $rtnContainerList[$i]['actual_delv_pack']; }?>
				</td>
				<td>
					<?php if(isset($rtnContainerList[$i]['Notify_name'])) echo $rtnContainerList[$i]['Notify_name']."<br>".$rtnContainerList[$i]['Notify_code']?>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		
		</br>
		
		<table width="100%" cellpadding="0" border="0" class="tblFont">
			<tr>
				<td align="left" colspan="4" align="left" >
					Total(in words)
				</td>
				<td colspan="4">
				</td>
				<td colspan="4" align="right" >
					N.B. - No alteration of any particular entered herein will be made by the consignee.
				</td>
			</tr>
			<tr>
				<td align="left" colspan="4">
					Name : 
				</td>
				<td  colspan="4">
					 
				</td>
				<td colspan="4">
				</td>
			</tr>
			<tr>
				<td align="left" colspan="4">
					Signature of the consignee :
				</td>
				<td  colspan="4">
					 
				</td>
				<td colspan="4">
				</td>
			</tr>
			<tr>
				<td align="left" colspan="4" style="width:430px;">
					Address :                                      
				</td>
				<td align="left" colspan="4" >
					Wrong mark/No Mark Repairing application
				</td>
				<td align="right" colspan="4">
					Certified that the particulars of the
				</td>
			</tr>
		</table>
		</br>		
		<table width="100%" cellpadding="0" border="0" class="tblFont">
			<tr>
				<td align="left">
					Certified that the Consignment has been passed out of the Customs control in full/in part. Detain No ----------------- Date -----------------
				</td>
			</tr>
			
			<tr>
				<td align="left">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="left">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="left">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="left" >
					<table width="70%" border="0">
						<tr>
							<td width="30%">Signed By : </td>
							<td width="30%">Signed By : </td>
						</tr>
						<tr>
							<td width="30%">Designation : Shed Officer</td>
							<td width="30%">Designation : Manifest Clerk</td>
						</tr>
						<tr>
							<td width="30%">Date & Time : </td>
							<td width="30%">Date & Time : </td>
						</tr>
					</table> 
				</td>
			</tr>
			<tr>
				<td align="left">
					Certified that the particulars of the consignment noted here in correct. Date -----------------
				</td>
			</tr>
			<tr>
				<td align="left">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="left">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="left">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="left" >
					<table width="70%" border="0">
						<tr>
							<td width="30%">Signed By : </td>
							<td width="30%">Signed By : </td>
						</tr>
						<tr>
							<td width="30%">Designation : Shed Officer</td>
							<td width="30%">Designation : Manifest Clerk</td>
						</tr>
						<tr>
							<td width="30%">Date & Time : </td>
							<td width="30%">Date & Time : </td>
						</tr>
					</table> 
				</td>
			</tr>
			<tr>
				<td align="left">
					Filled on ------------------------ and attached herewith for ------------------------ pkgs. Imp/ ------------------------
				</td>
			</tr>
			<tr>
				<td align="left">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="left">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="left">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="left" >
					<table width="70%" border="0">
						<tr>
							<td width="30%">Signed By : </td>
							<td width="30%">Signed By : </td>
						</tr>
						<tr>
							<td width="30%">Designation : Shed Officer</td>
							<td width="30%">Designation : Manifest Clerk</td>
						</tr>
						<tr>
							<td width="30%">Date & Time : </td>
							<td width="30%">Date & Time : </td>
						</tr>
					</table> 
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="center">Page 1 of 4</td>
			</tr>
		</table>	
	</div>
		
		<!--/br>
		</br>
		</br-->
	<!--div class="portrait"-->
	<!-- Second page -->
	<div class="pageBreak">
		<table width="100%" cellpadding="0" border="0" class="p1">
			<tr height="100px">
				<td colspan="5" align="center" valign="middle">
					<img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg">
				</td>
			</tr>
			<tr>
				<td colspan="5" style="margin-right50px:" align="right">
					PRO : 44945<?php  if($rtnContainerList[$i]['pr_number']){ ?><u><?php  echo $rtnContainerList[$i]['pr_number']; ?></u><?php   } ?>
				</td>
				
			</tr>
			<tr height="100px">
				<td colspan="5" align="center" valign="middle">	
					<h3>RELEASE ORDER FOR DELEVERY (CASH) C.P NO <u>
					<?php 
					if(count($rtnContainerList))
					{
						$cpbankcode=$rtnContainerList[$i]['cp_bank_code'];
						$cpno=$rtnContainerList[$i]['cp_no'];
						$cpyear=$rtnContainerList[$i]['cp_year'];
						$cpunit=$rtnContainerList[$i]['cp_unit'];
						$num_length = strlen($cpno);
					
						if($num_length == 4) 
						{
							$newcpno=$cpno;
						} 
						else if($num_length == 3)
						{
							$newcpno="0"."$cpno";
						}
						else if($num_length == 2)
						{
							$newcpno="00"."$cpno";
						}
						else if($num_length == 1)
						{
							$newcpno="000"."$cpno";
						}
						if($cpbankcode!=""&&$cpno!="")
						{
							echo $cpnoview=$cpbankcode.$cpunit."/".$cpyear."-"."$newcpno";
						}
					}
					?></u> OF <u><?php if(isset($rtnContainerList[$i]['cp_date'])) echo $rtnContainerList[$i]['cp_date']; ?></u></h3>
				</td>
			</tr>
			<tr>
				<td align="center" >
					VESSEL : <u><?php if(isset($rtnContainerList[$i]['Vessel_Name'])) {echo $rtnContainerList[$i]['Vessel_Name'];} else echo ""; ?></u>
				</td>
				<td align="center" >
					REG NO : <u><?php if(isset($rtnContainerList[$i]['Import_Rotation_No'])) echo  $rtnContainerList[$i]['Import_Rotation_No']?></u>
				</td>
				<td align="center">
					BL NO : <u><?php if(isset($rtnContainerList[$i]['BL_No'])) echo $rtnContainerList[$i]['BL_No']?></u>
				</td>
				<td align="center">
					BE NO : <u><?php echo $rtnContainerList[$i]['be_no']?></u> <!-- OF <u><?php// echo  $rtnContainerList[$i]['cnf_name']?></u> CONTAINER NO: <u><?php //echo  $rtnContainerList[$i]['cont_number']?> --></u>
				</td>				
				<td align="center">
					CONSIGNEE : <u><?php if(isset($rtnContainerList[0]['Notify_name'])) echo $rtnContainerList[0]['Notify_name']?></u>
				</td>				
			</tr>
		</table>
		<table border="0">
			<tr>
				<td>
					<table border="0">
						<tr>
							<td colspan="3">
								<u><b>Apprising/Repacking Application</b></u>
							</td>
						</tr>
						<tr>
							<td colspan="3">
								Received in good order and condition the following <br>packages for Apprising/Repacking
							</td>					
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<th style="border:1" colspan="1" valign="top">
								Mark & Number
							</th>
							<th style="border:1" colspan="2" valign="top">
								Description
							</th>
						</tr>
						<tr>
							<td style="border:1" colspan="1" align="center" valign="top"><font size="2px"><?php echo  substr($rtnContainerList[$i]['Pack_Marks_Number'],0,30)?></font></td>
							<td style="border:1" colspan="2" align="center" valign="top"><font size="2px"><?php echo  substr($rtnContainerList[$i]['Description_of_Goods'],0,10)?></font></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr height="100px" valign="bottom">
							<td colspan="2" align="left">
								Custom's Representative
							</td>	
							<td align="right">
								Consignee's Representative<br>License No.
							</td>							
						</tr>
						<tr height="100px" valign="bottom">
							<td colspan="2" align="left">
								Date & Time
							</td>	
							<td align="right">
								Date & Time
							</td>							
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr height="100px" valign="bottom">
							<td colspan="2" align="left">
								Sr.S.O.
							</td>	
							<td align="right">
								ASI
							</td>							
						</tr>	
						<tr height="100px" valign="bottom">
							<td colspan="2" align="left">
								Date & Time
							</td>	
							<td align="right">
								Date & Time
							</td>							
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>					
						
						<tr>
							<th colspan="3" align="center">
								<u><font size="5">Survey</font></u>
							</th>
						</tr>
						<tr>
							<td colspan="3">
								Received the following packages for survey -
							</td>
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<th style="border:1" colspan="1" valign="top">
								Mark & Number
							</th>
							<th style="border:1" colspan="2" valign="top">
								Description
							</th>
						</tr>
						<tr>
							<td style="border:1" colspan="1" align="center" valign="top"><font size="2px"><?php echo  substr($rtnContainerList[$i]['Pack_Marks_Number'],0,30)?></font></td>
							<td style="border:1" colspan="2" align="center" valign="top"><font size="2px"><?php echo  substr($rtnContainerList[$i]['Description_of_Goods'],0,10)?></font></td>
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr height="100px" valign="bottom">
							<td colspan="2" align="left">
								Surveyor-Customs-S.O.
							</td>	
							<td align="right">
								Consignee's Representative License No.
							</td>							
						</tr>	
						<tr height="100px" valign="bottom">
							<td colspan="2" align="left">
								Date & Time
							</td>	
							<td align="right">
								Date & Time
							</td>							
						</tr>						
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>												
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr height="100px" valign="bottom">							
							<td align="left">
								Lockfast Clerk
							</td>	
							<td colspan="2" align="right">
								<!-- ASI -->
							</td>
						</tr>			
						<tr height="100px" valign="bottom">
							<td colspan="2" align="left">
								Date & Time
							</td>	
							<td align="right">
								<!-- Date -->
							</td>							
						</tr>								
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>						
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<td colspan="3" align="left">
								N.B. - No alteration by the consignee will be accepted unless the same is countersigned <nobr>by the Sr.Shed Officer.</nobr>
							</td>
						</tr>
						<tr>
							<td colspan="3"><?php //echo "PREPARED BY : ".$login_id;?></td>
						</tr>
					</table>
				</td>
				<td valign="top">
					<table border="0">
						<tr>
							<td align="center" colspan="2">
								<!-- <table style="border:1px solid black;padding:2px;"> -->
								<table border="0">
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td style="border-bottom:1px solid black;border-style: dashed;" colspan="3"></td>
									</tr>
									<tr>
										<td style="padding:1px;">TI</td>
										<td style="padding:1px;">:</td>
										<td style="padding:1px;">Unit 1</td>
									</tr>
									<tr>
										<td style="padding:1px;">Onestop</td>
										<td style="padding:1px;">:</td>
										<td style="padding:1px;">M Shed</td>
									</tr>
									<tr>
										<td style="padding:1px;" colspan="3">Chittagong Port Authority</td>			
									</tr>									
								</table>
							</td>
						</tr>
						<tr valign="top" align="center">
							<td colspan="2">
								<u><b>RELEASE ORDER</b></u>
							<td>
						</tr>
						<tr>
							<td>CP No. : <b><?php  echo $cpnoview;?></b></td>
							<td>Date : <b><?php echo $rtnContainerList[$i]['cp_date']; ?></b></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr height="10px" valign="bottom">
							<td colspan="2">Manifest Page No. : <b>79126</b></td>
						</tr>
						<tr>
							<td colspan="2">Quantity : <b><?php echo  $rtnContainerList[$i]['Pack_Number']?></b></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>							
						</tr>
						<tr height="30px" valign="bottom">
							<td colspan="2">CFS/Shed/Yard : <b><?php if($rtnContainerList[$i]['cont_status']=="LCL"){ echo  $rtnContainerList[$i]['shed_yard']; }else{ echo $yard_No; }?></b></td>
						</tr>
						<tr height="30px" valign="bottom">
							<td colspan="2">Location : <b><?php if($rtnContainerList[$i]['cont_status']=="LCL"){ echo  $rtnContainerList[$i]['shed_loc']; }else{ echo $pos; } ?><b></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td>
								Vessel Name : <b><?php echo $rtnContainerList[0]['Vessel_Name']; ?></b>
							</td>
							<td>Arrival Date : <b>2021-10-15</b></td>
						</tr>
						<tr>
							<td colspan="2">Voyage : <b><?php echo $rtnContainerList[$i]['Voy_No']?></b></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">Consignee : <b><?php echo $rtnContainerList[$i]['Notify_name']?></b></td>
							
						</tr>
						<tr height="50px" valign="bottom">
							<td colspan="2">Address : <b><?php echo $rtnContainerList[$i]['Notify_address']?></b></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td>Reg. No. : <b><?php echo $rtnContainerList[$i]['Import_Rotation_No']?></b></td>
							<td>Line No. : <b><?php echo $rtnContainerList[$i]['Line_No']?></b></td>
						</tr>
						<tr>
							<td>Bill of Entry No. : <b><?php echo $rtnContainerList[$i]['be_no']?></b></td>
							<td>of <b><?php echo $rtnContainerList[$i]['be_date']?></b></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="center">Page 2 of 4</td>
			</tr>
		</table>
		<?php //echo "PREPARED BY : ".$login_id;?>
	</div>
	<!-- Third page -->
	<div class="pageBreak">
	</br>
		<table width="100%" cellpadding="0" border="0">
			<tr height="100px">
				<td colspan="5" align="center" valign="middle">
					<img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg">
				</td>
			</tr>
			<tr>
				<td colspan="5" style="margin-right50px:" align="right">
					PRO : 44945<?php  if($rtnContainerList[$i]['pr_number']){ ?><u><?php  echo $rtnContainerList[$i]['pr_number']; ?></u><?php   } ?>
				</td>
				
			</tr>
			<tr height="100px">
				<td colspan="5" align="center" valign="middle">	
					<h3>RELEASE ORDER FOR DELEVERY (CASH) C.P NO <u>
					<?php 
					if(count($rtnContainerList))
					{
						$cpbankcode=$rtnContainerList[$i]['cp_bank_code'];
						$cpno=$rtnContainerList[$i]['cp_no'];
						$cpyear=$rtnContainerList[$i]['cp_year'];
						$cpunit=$rtnContainerList[$i]['cp_unit'];
						$num_length = strlen($cpno);
					
						if($num_length == 4) 
						{
							$newcpno=$cpno;
						} 
						else if($num_length == 3)
						{
							$newcpno="0"."$cpno";
						}
						else if($num_length == 2)
						{
							$newcpno="00"."$cpno";
						}
						else if($num_length == 1)
						{
							$newcpno="000"."$cpno";
						}
						if($cpbankcode!=""&&$cpno!="")
						{
							echo $cpnoview=$cpbankcode.$cpunit."/".$cpyear."-"."$newcpno";
						}
					}
					?></u> OF <u><?php if(isset($rtnContainerList[$i]['cp_date'])) echo $rtnContainerList[$i]['cp_date']; ?></u></h3>
				</td>
			</tr>
			<tr>
				<td align="center" >
					VESSEL : <u><?php if(isset($rtnContainerList[$i]['Vessel_Name'])) {echo $rtnContainerList[$i]['Vessel_Name'];} else echo ""; ?></u>
				</td>
				<td align="center" >
					REG NO : <u><?php if(isset($rtnContainerList[$i]['Import_Rotation_No'])) echo  $rtnContainerList[$i]['Import_Rotation_No']?></u>
				</td>
				<td align="center">
					BL NO : <u><?php if(isset($rtnContainerList[$i]['BL_No'])) echo $rtnContainerList[$i]['BL_No']?></u>
				</td>
				<td align="center">
					BE NO : <u><?php echo $rtnContainerList[$i]['be_no']?></u> <!-- OF <u><?php// echo  $rtnContainerList[$i]['cnf_name']?></u> CONTAINER NO: <u><?php //echo  $rtnContainerList[$i]['cont_number']?> --></u>
				</td>				
				<td align="center">
					CONSIGNEE : <u><?php if(isset($rtnContainerList[0]['Notify_name'])) echo $rtnContainerList[0]['Notify_name']?></u>
				</td>				
			</tr>
		</table>
		<table border="1" width="100%">
			<tr>
				<th>Sl</th>
				<th>Container</th>
				<th>Size</th>
				<th>Height</th>
				<th>ISO Group</th>
				<th>Dist Dt</th>
				<th>CL Date</th>
				<th>Location</th>
				<th>MLO Status</th>
				<th>FF Status</th>
			</tr>
			<?php
			for($k=0;$k<count($rtnContainerList);$k++)
			{
			?>
			<tr>
				<td align="center"><?php echo $k+1; ?></td>
				<td align="center"><?php echo $rtnContainerList[$k]['cont_number'];; ?></td>
				<td align="center"><?php echo $rtnContainerList[$k]['cont_size'];; ?></td>
				<td align="center"><?php echo $rtnContainerList[$k]['cont_height'];; ?></td>
				<td align="center"><?php echo $rtnContainerList[$k]['cont_iso_type'];; ?></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"><?php echo $rtnContainerList[$k]['shed_yard'];; ?></td>
				<td align="center"></td>				
				<td align="center"></td>
			</tr>
			<?php
			}
			?>			
		</table>
	
		<table border="0" width="100%">
			<tr>
				<td colspan="10" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="10" height="800px" valign="bottom" align="center">Page 3 of 4</td>
			</tr>
		</table>
	</div>
	<!-- Fourth page -->
	<div class="pageBreakOff">
		<table width="100%" cellpadding="0" border="0">
			<tr height="100px">
				<td colspan="5" align="center" valign="middle">
					<img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg">
				</td>
			</tr>
			<tr>
				<td colspan="5" style="margin-right50px:" align="right">
					PRO : 44945<?php  if($rtnContainerList[$i]['pr_number']){ ?><u><?php  echo $rtnContainerList[$i]['pr_number']; ?></u><?php   } ?>
				</td>
				
			</tr>
			<tr height="100px">
				<td colspan="5" align="center" valign="middle">	
					<h3>RELEASE ORDER FOR DELEVERY (CASH) C.P NO <u>
					<?php 
					if(count($rtnContainerList))
					{
						$cpbankcode=$rtnContainerList[$i]['cp_bank_code'];
						$cpno=$rtnContainerList[$i]['cp_no'];
						$cpyear=$rtnContainerList[$i]['cp_year'];
						$cpunit=$rtnContainerList[$i]['cp_unit'];
						$num_length = strlen($cpno);
					
						if($num_length == 4) 
						{
							$newcpno=$cpno;
						} 
						else if($num_length == 3)
						{
							$newcpno="0"."$cpno";
						}
						else if($num_length == 2)
						{
							$newcpno="00"."$cpno";
						}
						else if($num_length == 1)
						{
							$newcpno="000"."$cpno";
						}
						if($cpbankcode!=""&&$cpno!="")
						{
							echo $cpnoview=$cpbankcode.$cpunit."/".$cpyear."-"."$newcpno";
						}
					}
					?></u> OF <u><?php if(isset($rtnContainerList[$i]['cp_date'])) echo $rtnContainerList[$i]['cp_date']; ?></u></h3>
				</td>
			</tr>
			<tr>
				<td align="center" >
					VESSEL : <u><?php if(isset($rtnContainerList[$i]['Vessel_Name'])) {echo $rtnContainerList[$i]['Vessel_Name'];} else echo ""; ?></u>
				</td>
				<td align="center" >
					REG NO : <u><?php if(isset($rtnContainerList[$i]['Import_Rotation_No'])) echo  $rtnContainerList[$i]['Import_Rotation_No']?></u>
				</td>
				<td align="center">
					BL NO : <u><?php if(isset($rtnContainerList[$i]['BL_No'])) echo $rtnContainerList[$i]['BL_No']?></u>
				</td>
				<td align="center">
					BE NO : <u><?php echo $rtnContainerList[$i]['be_no']?></u> <!-- OF <u><?php// echo  $rtnContainerList[$i]['cnf_name']?></u> CONTAINER NO: <u><?php //echo  $rtnContainerList[$i]['cont_number']?> --></u>
				</td>				
				<td align="center">
					CONSIGNEE : <u><?php if(isset($rtnContainerList[0]['Notify_name'])) echo $rtnContainerList[0]['Notify_name']?></u>
				</td>				
			</tr>
		</table>
		<table style="border:1px solid black">
			<tr>
				<td style="border-top:1px solid black;">BL/NO- </td>
				<td><b><?php echo  $rtnContainerList[$i]['BL_No']?></b></td>
			</tr>
			<tr>
				<td>X/NO- </td>
				<td><b><?php echo  $rtnContainerList[$i]['exit_note_number']?></b></td>
			</tr>
			<tr>
				<td>VERIFY NO- &nbsp; </font> </td>
				<td><b><?php echo  $rtnContainerList[$i]['verify_number']?></b></td>
			</tr>
			<tr>
				<td>BILL NO- &nbsp; </font> </td>
				<td><b><?php echo  $rtnContainerList[$i]['master_bill_no']?></b></td>
			</tr>
		</table>
		</br>
		<div class="right">
			<table border="1">
				<tr ><td align="center" colspan="8"><b>PARTICULARS OF DELIVERY BY RESHIPMENT</b></td></tr>
				<tr>
					<th>
						Date 
					</th>
					<th>
						Quantity applied for reshipment
					</th>
					<th>
						Consignee's Signature and License No. 
					</th>
					<th>
						Customs allow order 
					</th>
					<th>
						S.O's Signature 
					</th>
					<th>
						Quantity reshipped
					</th>
					<th>
						Total Quantity (Running) 
					</th>
					<th>
						Signature of the shipping Clerk 
					</th>
				</tr>
				<tr>
					<td class="cellheight" align="center">2021-10-24</td>
					<td class="cellheight" align="center">45</td>
					<td class="cellheight" align="center">
						<?php if(isset($rtnContainerList[$i]['Notify_name'])) echo $rtnContainerList[$i]['Notify_name']."<br>".$rtnContainerList[$i]['Notify_code']?>					
					</td>
					<td class="cellheight" align="center">40</td>
					<td class="cellheight" align="center"></td>
					<td class="cellheight" align="center">40</td>
					<td class="cellheight" align="center">40</td>
					<td class="cellheight" align="center"></td>
				</tr>
				<tr><td align="center" colspan="8"><b>PARTICULARS OF DELIVERY BY RAIL</b></td></tr>
				<tr>
					<th>
						Date Note of accepted
					</th>
					<th>
						F/Note No.
					</th>
					<th>
						Description
					</th>
					<th>
						Quantity accepted
					</th>
					<th>
						Date of Loading
					</th>
					<th>
						Quantity Loaded
					</th>
					<th>
						Total Quantity
					</th>
					<th>
						Signature of the Shed Officer
					</th>
				</tr>
				<tr>
					<td class="cellheight" align="center">2021-10-24</td>
					<td class="cellheight" align="center">1684</td>
					<td class="cellheight" align="center"><?php echo  $rtnContainerList[$i]['Description_of_Goods']; ?></td>
					<td class="cellheight" align="center">40</td>
					<td class="cellheight" align="center">2021-10-20</td>
					<td class="cellheight" align="center">45</td>
					<td class="cellheight" align="center">50</td>
					<td class="cellheight" align="center"></td>
				</tr>
				<tr>
					<td colspan="8" align="center">
						<b><u>Bill Receive Information</u></b></br>
					</td>	
				</tr>
				<tr>
					<td colspan="8" align="center">
						<?php 
						// if($verify_number!=0)							
						if($verify_num!="")						
						{
							for($j=0;$j<count($rtnBillRcvInfo);$j++)
							{							
								echo $rtnBillRcvInfo[$j]['description'].",";							
							}
						}
						else
						{
							echo "&nbsp;";
						}
						?>										
					</td>
				</tr>
				<tr>
					<td colspan="8" align="Center">
						<?php 
						// if($verify_number!=0) 
						if($verify_num!="") 						
						{ 
						?>
						<b> 
							<?php  
								if(isset($rtnBillList[0]['paid_status'])) 
									echo $rtnBillList[0]['paid_status'];
							?> 
							upto :
							<?php 
								if(isset($rtnContainerList[0]['bill_date'])) 
									echo $rtnContainerList[0]['bill_date']; 
							?> 
							&nbsp;&nbsp;&nbsp; 
							Vide CP No. &nbsp;:
							<?php 
								if(isset($cpnoview)) 
									echo $cpnoview;
							?> &nbsp;&nbsp; Bill No - &nbsp; 
							<?php 
								if(isset($rtnContainerList[0]['master_bill_no'])) 
									echo $rtnContainerList[0]['master_bill_no']; 
							?>
						</b>
						<?php 
						}  
						else 
						{ 
						?>
							&nbsp;
						<?php 
						} 
						?>
					</td>
				</tr>				
			</table>
		</div>
		
		<table width="100%" border="0">	
			<tr>				
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td align="left"><?php echo "BILL PREPARED BY : ";?></td>
			</tr>
			<tr height="100px" valign="bottom">
				<td colspan="2">
					&nbsp;
				</td>					
			</tr>
			<tr height="100px" valign="bottom">
				<td>
					&nbsp;
				</td>	
				<td align="left">
					Date & Time
				</td>
			</tr>
		</table>
		<table width="100%" border="0">	
			<tr>				
				<td colspan="9">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="9">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="9">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="9">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="9">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="border-top:1px solid black;border-style: dashed;" align="center">TI/CFS/Yard</td>				
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style="border-top:1px solid black;border-style: dashed;" align="center">CC/ONESTOP</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style="border-top:1px solid black;border-style: dashed;" align="center">TI/ONESTOP</td> 				
				<td>&nbsp;</td>
			</tr>
			<tr height="100px" valign="bottom">
				<td colspan="9">&nbsp;</td>				
			</tr>
			<tr height="100px" valign="bottom">
				<td>&nbsp;</td>
				<td align="center">Date & Time</td>				
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="center">Date & Time</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="center">Date & Time</td> 				
				<td>&nbsp;</td>
			</tr>
		</table>
		<table border="0" width="100%">
			<tr>
				<td colspan="10" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="10" height="260px" valign="bottom" align="center">Page 4 of 4</td>
			</tr>
		</table>
	</div>
		</div>
		<?php
		// }
		?>
		<!--script>
			window.print();
		</script-->
	</body>		
</html>
			