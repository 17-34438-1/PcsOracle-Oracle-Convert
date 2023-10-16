<html>
	<head>
		<title>RELEASE ORDER FOR DELEVERY</title>
		<style>
			@media print {
				@page { margin: 0.5cm; }
				body { margin: 1.6cm; }
			}
		</style>
		<style>
			.p1 {
  				font-family: "Times New Roman", Times, serif;
			}
	
			.font_size{
				font-size:12px;
			}
		</style>
		<style type="text/css" media="print">
		@page {
			size: auto;  /*  auto is the initial value */
			margin-top: 0;  /*  this affects the margin in the printer settings */
			margin-left: 0;  /*  this affects the margin in the printer settings */
			margin-right: 0;  /*  this affects the margin in the printer settings */
		}
		</style>
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
			<tr>				
				<td width="20%">
					<img width="125px" height="80px" src="<?php echo IMG_PATH?>cpa_logo.png">
				</td>
				<td colspan="3" style="padding-left:80px" width="70%">
					<h2>CHITTAGONG PORT AUTHORITY</h2>
				</td>
				<td align="right" width="20%">
					<table style="border:1px solid black">
						<tr style="font-size:11px;">
							<td>BL/NO- </td>
							<td><b><?php echo  $rtnContainerList[$i]['BL_No']?></b></td>
						</tr>
						<tr style="font-size:11px;">
							<td>X/NO- </td>
							<td><b><?php echo  $rtnContainerList[$i]['exit_note_number']?></b></td>
						</tr>
						<tr style="font-size:11px;">
							<td>VERIFY NO- &nbsp; </font> </td>
							<td><b><?php echo  $rtnContainerList[$i]['verify_number']?></b></td>
						</tr>
						<tr style="font-size:11px;">
							<td>BILL NO- &nbsp; </font> </td>
							<td><b><?php echo  $rtnContainerList[$i]['master_bill_no']?></b></td>
						</tr>
						<tr style="font-size:11px;">
							<td>PRO- &nbsp; </font> </td>
							<td><b>44945</b></td>
						</tr>
					</table>
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
				<td align="center" width="20%" class="font_size">
					VESSEL : <u><?php if(isset($rtnContainerList[$i]['Vessel_Name'])) {echo $rtnContainerList[$i]['Vessel_Name'];} else echo ""; ?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					REG NO : <u><?php if(isset($rtnContainerList[$i]['Import_Rotation_No'])) echo  $rtnContainerList[$i]['Import_Rotation_No']?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					BL NO : <u><?php if(isset($rtnContainerList[$i]['BL_No'])) echo $rtnContainerList[$i]['BL_No']?></u>
				</td>
				<td align="center" width="15%" class="font_size">
					BE NO : <u><?php echo $rtnContainerList[$i]['be_no']?></u> <!-- OF <u><?php// echo  $rtnContainerList[$i]['cnf_name']?></u> CONTAINER NO: <u><?php //echo  $rtnContainerList[$i]['cont_number']?> --></u>
				</td>				
				<td align="center" width="25%" class="font_size">
					CONSIGNEE : <u><?php if(isset($rtnContainerList[0]['Notify_name'])) echo $rtnContainerList[0]['Notify_name']?></u>
				</td>				
			</tr>
		</table>
		
		</br>
		
		<table border="1" width="100%" cellpadding="0" style="border-collapse:collapse" class="tblFont">
			<tr bgcolor="">
				<th align="center" rowspan="2">Marks & Number</th>
				<th align="center" rowspan="2">Quantity</th>
				<th align="center" rowspan="2">Unit</th>
				<th align="center" rowspan="2">Description</th>
				<th align="center" rowspan="2">Weight</th>
				<th align="center" rowspan="2">Measurement</th>				
				<th align="center" colspan="2">Landing Charges</th>
				<th align="center" rowspan="2">Date of Delivery</th>
				<th align="center" rowspan="2">Quantity applied for delivery</th>
				<th align="center" rowspan="2">Consignee's Signature & License No.</th>
				<th align="center" rowspan="2">S.O's Signature</th>
				<th align="center" colspan="2">Quantity Passed out</th>
				<th align="center" rowspan="2">Balance Due</th>
				<th align="center" rowspan="2">Signature of G.S. and Date</th>
			</tr>
			<tr bgcolor="">
				<th>Taka</th>
				<th>Ps.</th>
				<th>Figure</th>
				<th>In words</th>
			</tr>
			<!--tr height="150px" bgcolor=""-->
			<tr class="font_size">
				<td align="center"><?php echo  $rtnContainerList[$i]['Pack_Marks_Number']; ?></td>
				<td align="center"><?php echo  $rtnContainerList[$i]['Pack_Number']; ?></td>
				<td align="center"><?php echo  $rtnContainerList[$i]['rcv_unit']; ?></td>
				<td align="center"><?php echo  $rtnContainerList[$i]['Description_of_Goods']; ?></td>
				<td align="center"><?php echo  $rtnContainerList[$i]['cont_weight']; ?></td>
				<td align="center"><?php echo  $rtnContainerList[$i]['Volume_in_cubic_meters']; ?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="center">
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
				<td align="center">
					<?php if($rtnContainerList[$i]['actual_delv_pack']==null){ echo "75.0"; } else{ echo  $rtnContainerList[$i]['actual_delv_pack']; }?>
				</td>
				<td align="center">
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
		
		<!--table width="100%" cellpadding="0" border="0" class="font_size"-->
		<table width="100%" cellpadding="0" border="0">
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
				<td align="left" colspan="12" align="left" >
					&nbsp;
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
				<td align="left" colspan="4" style="width:33%">
					Address :  <br>Certified that the particulars of the consignment noted here in correct. Date -----------------                                    
				</td>
				<td align="left" colspan="4" style="width:33%">
					Wrong mark/No Mark Repairing application. Certified that the Consignment has been passed out of the Customs control in full/in part. Detain No ----------------- Date -----------------
				</td>
				<td align="right" colspan="4" style="width:34%">
					Certified that the particulars of the filled on ------------------------ and attached herewith for ------------------------ pkgs. Imp/ ------------------------
				</td>
			</tr>
		</table>
		</br>
		<!--table width="100%" class="font_size"-->
		<table width="100%">
			<tr>
				<td colspan="6">&nbsp;</td>
			</tr>
			<tr>
				<td>Signed By : <?php echo $verifyBy?></td>
				<!--td>&nbsp;</td-->
				<td>Signed By : </td>
				<!--td>&nbsp;</td-->
				<td>Signed By : </td>
				<!--td>&nbsp;</td-->
				<td>Signed By : </td>
				<!--td>&nbsp;</td-->
				<td>Signed By : <?php echo $verifyBy?></td>
				<!--td>&nbsp;</td-->
				<td>Signed By : </td>
				<!--td>&nbsp;</td-->
			</tr>
			<tr>
				<td>Designation : Manifest Clerk</td>
				<!--td>&nbsp;</td-->
				<td>Designation : TI</td>
				<!--td>&nbsp;</td-->
				<td>Designation : Manifest Clerk</td>
				<!--td>&nbsp;</td-->
				<td>Designation : TI</td>
				<!--td>&nbsp;</td-->
				<td>Designation : Manifest Clerk</td>
				<!--td>&nbsp;</td-->
				<td>Designation : TI</td>
				<!--td>&nbsp;</td-->
			</tr>
			<tr>
				<td>Date & Time : <?php echo $verifyTime; ?></td>
				<!--td>&nbsp;</td-->
				<td>Date & Time : </td>
				<!--td>&nbsp;</td-->
				<td>Date & Time : </td>
				<!--td>&nbsp;</td-->
				<td>Date & Time : </td>
				<!--td>&nbsp;</td-->
				<td>Date & Time : <?php echo $verifyTime; ?></td>
				<!--td>&nbsp;</td-->
				<td>Date & Time : </td>
				<!--td>&nbsp;</td-->
			</tr>
			<tr>
				<td colspan="6">&nbsp;</td>
			</tr>
			<!--tr>
				<td colspan="2">Certified that the particulars of the consignment noted here in correct. Date -----------------</td>
				<td colspan="2">Filled on ------------------------ and attached herewith for ------------------------ pkgs. Imp/ ------------------------</td>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="6">&nbsp;</td>
			</tr>
			<td align="left" colspan="6">
				Certified that the Consignment has been passed out of the Customs control in full/in part. Detain No ----------------- Date -----------------
			</td-->
			<tr>
				<td colspan="6">&nbsp;</td>
			</tr>
		</table>
	</div>
		
		<div style="page-break-after: always;"></div>
		<!--/br>
		</br>
		</br-->
	<!--div class="portrait"-->
	
	<!-- Second page -->
	
	<div class="pageBreak">		
		<table width="100%" cellpadding="0" border="0" class="tblFont">					
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td width="20%">
					<img width="125px" height="80px" src="<?php echo IMG_PATH?>cpa_logo.png">
				</td>
				<td colspan="3" style="padding-left:80px" width="70%">
					<h2>CHITTAGONG PORT AUTHORITY</h2>
				</td>
				<td align="right" width="20%">
					PRO : 44945<?php  if($rtnContainerList[$i]['pr_number']){ ?><u><?php  echo $rtnContainerList[$i]['pr_number']; ?></u><?php   } ?>
				</td>
			</tr>
			<tr>
				<td colspan="5" align="center" valign="middle">	
					<h3>RELEASE ORDER FOR DELEVERY (CASH) C.P NO <u>
					<?php 
					$cpnoview = "";
					$cpnodate = "";
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
					
					include('mydbPConnection.php');
					$sql_cpNoNts = "SELECT cp_no,DATE(cp_date) AS cp_date
					FROM oracle_nts_data
					WHERE oracle_nts_data.imp_rot_no='".$rtnContainerList[0]['Import_Rotation_No']."' AND oracle_nts_data.bl_no='".$rtnContainerList[0]['BL_No']."'";
					$rslt_cpNoNts = mysqli_query($con_cchaportdb,$sql_cpNoNts);
					while($row_cpNoNts = mysqli_fetch_object($rslt_cpNoNts))
					{
						$cpnoview = $row_cpNoNts->cp_no;
						$cpnodate = $row_cpNoNts->cp_date;
					}
					
					
					?></u> OF <u><?php if(isset($rtnContainerList[$i]['cp_date'])) echo $rtnContainerList[$i]['cp_date']; ?></u></h3>
				</td>
			</tr>
			<tr>
				<td align="center" width="20%" class="font_size">
					VESSEL : <u><?php if(isset($rtnContainerList[$i]['Vessel_Name'])) {echo $rtnContainerList[$i]['Vessel_Name'];} else echo ""; ?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					REG NO : <u><?php if(isset($rtnContainerList[$i]['Import_Rotation_No'])) echo  $rtnContainerList[$i]['Import_Rotation_No']?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					BL NO : <u><?php if(isset($rtnContainerList[$i]['BL_No'])) echo $rtnContainerList[$i]['BL_No']?></u>
				</td>
				<td align="center" width="15%" class="font_size">
					BE NO : <u><?php echo $rtnContainerList[$i]['be_no']?></u> <!-- OF <u><?php// echo  $rtnContainerList[$i]['cnf_name']?></u> CONTAINER NO: <u><?php //echo  $rtnContainerList[$i]['cont_number']?> --></u>
				</td>				
				<td align="center" width="25%" class="font_size">
					CONSIGNEE : <u><?php if(isset($rtnContainerList[0]['Notify_name'])) echo $rtnContainerList[0]['Notify_name']?></u>
				</td>				
			</tr>
		</table>
		<table border="0" width="100%">
			<tr>
				<td width="49%" valign="top">
					<table border="0" style="border-collapse:collapse;" width="100%">
						<tr>
							<td colspan="3">
								<u><b>Apprising/Repacking Application</b></u>
							</td>
						</tr>
						<tr>
							<td colspan="3">
								Received in good order and condition the following packages for Apprising/Repacking
							</td>					
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<th style="border:1px solid black" colspan="1" valign="top">
								Mark & Number
							</th>
							<th style="border:1px solid black" colspan="2" valign="top">
								Description
							</th>
						</tr>
						<tr>
							<td style="border:1px solid black" class="font_size" style="border:1" colspan="1" align="center" valign="top"><font size="2px"><?php echo  substr($rtnContainerList[$i]['Pack_Marks_Number'],0,30)?></font></td>
							<td style="border:1px solid black" class="font_size" style="border:1" colspan="2" align="center" valign="top"><font size="2px"><?php echo  substr($rtnContainerList[$i]['Description_of_Goods'],0,10)?></font></td>
						</tr>
						<tr valign="bottom">
							<td colspan="2" align="left" style="font-size:12px">
								Custom's Representative
							</td>	
							<td align="right" style="font-size:12px">
								<br><br>Consignee's Representative<br>License No.
							</td>							
						</tr>
						<tr valign="bottom">
							<td colspan="2" align="left" style="font-size:12px">
								Date & Time
							</td>	
							<td align="right" style="font-size:12px">
								Date & Time
							</td>							
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr valign="bottom">
							<td colspan="2" align="left" style="font-size:12px">
								Sr.S.O.
							</td>	
							<td align="right" style="font-size:12px">
								ASI
							</td>							
						</tr>	
						<tr valign="bottom">
							<td colspan="2" align="left" style="font-size:12px">
								Date & Time
							</td>	
							<td align="right" style="font-size:12px">
								Date & Time
							</td>							
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>						
					</table>
				</td>
				<td width="1%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td valign="top"  width="50%">
					<table border="0" width="100%" style="border-collapse:collapse;">
						<!-- survey - start -->
						<tr>
							<th colspan="2" align="center">
								<u><font size="3">Survey</font></u>
							</th>
						</tr>
						<tr>
							<td colspan="2">
								Received the following packages for survey -
							</td>
						</tr>
						<tr>
							<td colspan="2">
								&nbsp;
							</td>
						</tr>
						<tr>
							<th style="border:1px solid black;" valign="top">
								Mark & Number
							</th>
							<th style="border:1px solid black;" valign="top">
								Description
							</th>
						</tr>						
						<tr>
							<td style="border:1px solid black;" class="font_size" style="border:1" align="center" valign="top"><font size="2px"><?php echo  substr($rtnContainerList[$i]['Pack_Marks_Number'],0,30)?></font></td>
							<td style="border:1px solid black;" class="font_size" style="border:1" align="center" valign="top"><font size="2px"><?php echo  substr($rtnContainerList[$i]['Description_of_Goods'],0,10)?></font></td>
						</tr>
						<!--tr height="10px" valign="bottom"-->
						<tr>
							<td align="left" style="font-size:12px">
								Surveyor-Customs-S.O.
							</td>	
							<td align="right" style="font-size:12px">
								<br><br>Consignee's Representative License No.
							</td>							
						</tr>	
						<!--tr valign="bottom"-->
						<tr>
							<td align="left" style="font-size:12px">
								Date & Time
							</td>	
							<td align="right" style="font-size:12px">
								Date & Time
							</td>							
						</tr>
						<tr valign="bottom">							
							<td align="left" style="font-size:12px">
								<br>Lockfast Clerk
							</td>	
							<td align="right" style="font-size:12px">
								<!-- ASI -->
							</td>
						</tr>			
						<tr valign="bottom">
							<td align="left" style="font-size:12px">
								Date & Time
							</td>	
							<td align="right">
								<!-- Date -->
							</td>							
						</tr>								
						<tr>
							<td colspan="2">
								&nbsp;
							</td>					
						</tr>
						<tr>
							<td colspan="2" align="left" style="font-size:12px">
								N.B. - No alteration by the consignee will be accepted unless the same is countersigned <nobr>by the Sr.Shed Officer.</nobr>
							</td>
						</tr>
						<tr>
							<td colspan="2"><?php //echo "PREPARED BY : ".$login_id;?></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<!-- survey - end -->
																		
						<tr valign="top" align="center">
							<td colspan="2">
								<u><b>RELEASE ORDER</b></u>
							</td>
						</tr>
						<tr>
							<td>CP No. : <b><?php  echo $cpnoview;?></b></td>
							<!--td>Date : <b><?php echo $rtnContainerList[$i]['cp_date']; ?></b></td-->
							<td>Date : <b><?php echo $cpnodate; ?></b></td>
						</tr>
						<!--tr>
							<td colspan="2">&nbsp;</td>
						</tr-->
						<!--tr height="10px" valign="bottom">
							<td colspan="2">Manifest Page No. : <b>79126</b></td>
						</tr>
						<tr>
							<td colspan="2">Quantity : <b><?php echo  $rtnContainerList[$i]['Pack_Number']?></b></td>
						</tr-->
						<tr>
							<td>Manifest Page No. : <b>79126</b></td>						
							<td>Quantity : <b><?php echo  $rtnContainerList[$i]['Pack_Number']?></b></td>
						</tr>
						<!--tr height="30px" valign="bottom">
							<td colspan="2">CFS/Shed/Yard : <b><?php if($rtnContainerList[$i]['cont_status']=="LCL"){ echo  $rtnContainerList[$i]['shed_yard']; }else{ echo $yard_No; }?></b></td>
						</tr>
						<tr height="30px" valign="bottom">
							<td colspan="2">Location : <b><?php if($rtnContainerList[$i]['cont_status']=="LCL"){ echo  $rtnContainerList[$i]['shed_loc']; }else{ echo $pos; } ?><b></td>
						</tr-->
						<tr>
							<td>CFS/Shed/Yard : 
								<b>
								<?php 
									$yard_No = "";
									$pos = "";
									if($rtnContainerList[$i]['cont_status']=="LCL")
									{ 
										echo  $rtnContainerList[$i]['shed_yard']; 
									}
									else
									{
										
										include('mydbPConnectionn4.php');
										include("dbOracleConnection.php");
										
										$sql_n4Position="SELECT inv_unit.id,freight_kind,last_pos_slot
										FROM inv_unit
										INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
										WHERE inv_unit.id='".$rtnContainerList[$i]['cont_number']."'
										ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS only";
										$rslt_n4Position= oci_parse($con_sparcsn4_oracle, $sql_n4Position);
									    oci_execute($rslt_n4Position);
										
									

										while(($row_pos= oci_fetch_object($rslt_n4Position)) != false)
										{
											
										$pos = $row_pos->LAST_POS_SLOT;
										$last_pos_slot=$row_pos->LAST_POS_SLOT;
										
										$strQuerypos2="SELECT ctmsmis.cont_yard('$last_pos_slot') AS Yard_No";
										$strQuery3Res = mysqli_query($con_sparcsn4,$strQuerypos2);
										$row2 = mysqli_fetch_object($strQuery3Res);
										$yard_No = $row2->Yard_No;
										}
										echo $yard_No; 
									}
								?>
								</b>
							</td>						
							<td>Location : <b><?php if($rtnContainerList[$i]['cont_status']=="LCL"){ echo  $rtnContainerList[$i]['shed_loc']; }else{ echo $pos; } ?><b></td>
						</tr>
						<tr>
							<td>
								Vessel Name : <b><?php echo $rtnContainerList[0]['Vessel_Name']; ?></b>
							</td>
							<td>Arrival Date : <b>2021-10-15</b></td>
						</tr>
						<!--tr>
							<td colspan="2">Voyage : <b><?php echo $rtnContainerList[$i]['Voy_No']?></b></td>
						</tr>						
						<tr>
							<td colspan="2">Consignee : <b><?php echo $rtnContainerList[$i]['Notify_name']?></b></td>
						</tr-->
						<tr>
							<td>Voyage : <b><?php echo $rtnContainerList[$i]['Voy_No']?></b></td>						
							<td>Consignee : <b><?php echo $rtnContainerList[$i]['Notify_name']?></b></td>
						</tr>
						<tr height="50px" valign="bottom">
							<td colspan="2">Address : <b><?php echo $rtnContainerList[$i]['Notify_address']?></b></td>
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
			<!--tr>
				<td colspan="2" align="center">Page 2 of 4</td>
			</tr-->
		</table>
		<?php //echo "PREPARED BY : ".$login_id;?>
	</div>
	<!-- Third page -->
	<div class="pageBreak">		
		<table width="100%" cellpadding="0" border="0" class="tblFont">					
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td width="20%">
					<img width="125px" height="80px" src="<?php echo IMG_PATH?>cpa_logo.png">
				</td>
				<td colspan="3" style="padding-left:80px" width="70%">
					<h2>CHITTAGONG PORT AUTHORITY</h2>
				</td>
				<td align="right" width="20%">
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
				<td align="center" width="20%" class="font_size">
					VESSEL : <u><?php if(isset($rtnContainerList[$i]['Vessel_Name'])) {echo $rtnContainerList[$i]['Vessel_Name'];} else echo ""; ?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					REG NO : <u><?php if(isset($rtnContainerList[$i]['Import_Rotation_No'])) echo  $rtnContainerList[$i]['Import_Rotation_No']?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					BL NO : <u><?php if(isset($rtnContainerList[$i]['BL_No'])) echo $rtnContainerList[$i]['BL_No']?></u>
				</td>
				<td align="center" width="15%" class="font_size">
					BE NO : <u><?php echo $rtnContainerList[$i]['be_no']?></u> <!-- OF <u><?php// echo  $rtnContainerList[$i]['cnf_name']?></u> CONTAINER NO: <u><?php //echo  $rtnContainerList[$i]['cont_number']?> --></u>
				</td>				
				<td align="center" width="25%" class="font_size">
					CONSIGNEE : <u><?php if(isset($rtnContainerList[0]['Notify_name'])) echo $rtnContainerList[0]['Notify_name']?></u>
				</td>				
			</tr>
		</table>
		<br>
		<table border="1" width="100%" style="border-collapse:collapse">
			<tr>
				<th>Sl</th>
				<th>Container</th>
				<th>Size</th>
				<th>Height</th>
				<th>ISO Group</th>
				<!--th>Type</th-->
				<th>Status</th>
				<th>Dist Dt</th>
				<th>CL Date</th>
				<th>Location</th>
				<th>MLO Status</th>
				<th>FF Status</th>
			</tr>
			<?php
			include('dbConection.php');
			include("dbOracleConnection.php");
			for($k=0;$k<count($rtnContainerList);$k++)
			{
				$contISOType = $rtnContainerList[$k]['cont_iso_type'];
				
				$sql_n4ISOType = "SELECT iso_group FROM ref_equip_type WHERE id='$contISOType'";
			
				$rslt_n4ISOType = oci_parse($con_sparcsn4_oracle, $sql_n4ISOType);
				oci_execute($rslt_n4ISOType);
				
				

				while(($row_n4ISOType= oci_fetch_object($rslt_n4ISOType)) != false)
				{
					
					$n4ISOType = $row_n4ISOType->ISO_GROUP;
				}
			?>
			<tr>
				<td align="center"><?php echo $k+1; ?></td>
				<td align="center"><?php echo $rtnContainerList[$k]['cont_number']; ?></td>
				<td align="center"><?php echo $rtnContainerList[$k]['cont_size']; ?></td>
				<td align="center"><?php echo $rtnContainerList[$k]['cont_height']; ?></td>
				<td align="center"><?php echo $n4ISOType; ?></td>
				<!--td align="center"><?php echo $rtnContainerList[$k]['cont_iso_type']; ?></td-->
				<!--td align="center"><?php echo $rtnContainerList[$k]['cont_type']; ?></td-->
				<td align="center"><?php echo $rtnContainerList[$k]['cont_status']; ?></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"><?php echo $rtnContainerList[$k]['shed_yard']; ?></td>
				<td align="center"></td>				
				<td align="center"></td>
			</tr>
			<?php
			}
			?>
			<!--tr>
				<td colspan="10">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="10">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="10">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="10">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="8">&nbsp;</td>
				<td colspan="2" align="left">In-Charge : TI</td>
			</tr>
			<tr>
				<td colspan="8">&nbsp;</td>
				<td colspan="2" align="left">Certify Section</td>
			</tr>
			<tr>
				<td colspan="8">&nbsp;</td>
				<td colspan="2" align="left">One Stop, CPA</td>
			</tr-->
		</table>
		<br>
		<table border="0" width="100%" style="border-collapse:collapse">
			<tr>				
				<td colspan="9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>				
				<td colspan="9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>				
				<td colspan="9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>				
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td align="left"><?php echo $certifyBy; ?></td>
			</tr>
			<tr>				
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td align="left">In-Charge/TI</td>
			</tr>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>				
				<td align="left">Certify Section</td>
			</tr>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>				
				<td align="left">One Stop, CPA</td>
			</tr>
			</tr>
		</table>
		
		<div style="page-break-after: always;"></div>
		
	</div>
	<!-- Fourth page -->
	<div class="pageBreakOff">
		<table width="100%" cellpadding="0" border="0" class="tblFont">					
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td width="20%">
					<img width="125px" height="80px" src="<?php echo IMG_PATH?>cpa_logo.png">
				</td>
				<td colspan="3" style="padding-left:80px" width="70%">
					<h2>CHITTAGONG PORT AUTHORITY</h2>
				</td>
				<td align="right" width="20%">
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
				<td align="center" width="20%" class="font_size">
					VESSEL : <u><?php if(isset($rtnContainerList[$i]['Vessel_Name'])) {echo $rtnContainerList[$i]['Vessel_Name'];} else echo ""; ?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					REG NO : <u><?php if(isset($rtnContainerList[$i]['Import_Rotation_No'])) echo  $rtnContainerList[$i]['Import_Rotation_No']?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					BL NO : <u><?php if(isset($rtnContainerList[$i]['BL_No'])) echo $rtnContainerList[$i]['BL_No']?></u>
				</td>
				<td align="center" width="15%" class="font_size">
					BE NO : <u><?php echo $rtnContainerList[$i]['be_no']?></u> <!-- OF <u><?php// echo  $rtnContainerList[$i]['cnf_name']?></u> CONTAINER NO: <u><?php //echo  $rtnContainerList[$i]['cont_number']?> --></u>
				</td>				
				<td align="center" width="25%" class="font_size">
					CONSIGNEE : <u><?php if(isset($rtnContainerList[0]['Notify_name'])) echo $rtnContainerList[0]['Notify_name']?></u>
				</td>				
			</tr>
		</table>
		<!--table style="border:1px solid black">
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
		</table-->
		</br>
		<div class="right">
			<table border="1" style="border-collapse:collapse">
				<tr ><td align="center" colspan="8"><b>PARTICULARS OF DELIVERY BY SHIPMENT</b></td></tr>
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
				<!--tr>
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
				</tr-->
				<tr>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
				</tr>
			</table>
			<br><br>
			<table width="100%" border="1" style="border-collapse:collapse">
				<tr>
					<td align="center" colspan="8"><b>PARTICULARS OF DELIVERY BY RAIL</b></td>
				</tr>
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
				<!--tr>
					<td class="cellheight" align="center">2021-10-24</td>
					<td class="cellheight" align="center">1684</td>
					<td class="cellheight" align="center"><?php echo  $rtnContainerList[$i]['Description_of_Goods']; ?></td>
					<td class="cellheight" align="center">40</td>
					<td class="cellheight" align="center">2021-10-20</td>
					<td class="cellheight" align="center">45</td>
					<td class="cellheight" align="center">50</td>
					<td class="cellheight" align="center"></td>
				</tr-->
				<tr>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
					<td class="cellheight" align="center">&nbsp;</td>
				</tr>
			</table>
			<br><br>
			<table width="100%" border="1" style="border-collapse:collapse">
				<tr>
					<td colspan="8" align="center">
						<b>BILL RECEIVE INFORMATION</b></br>
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
		<br>
		<table width="100%" border="0">	
			<tr>				
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td align="left"><?php echo "BILL PREPARED BY : ";?></td>
			</tr>
			<tr height="10px" valign="bottom">
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
				<td>&nbsp;</td>
				<td align="center">--------------------------</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="center">--------------------------</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td  align="center">--------------------------</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="center">--------------------------</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td align="center">Head Delivery</td>				
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="center">TI/CFS/Yard</td>				
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="center">CC/ONESTOP</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="center">TI/ONESTOP</td> 				
				<td>&nbsp;</td>
			</tr>

			<tr valign="bottom">
				<td>&nbsp;</td>
				<td align="center">Date & Time</td>				
				<td>&nbsp;</td>
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
		<!--table border="0" width="100%">
			<tr>
				<td colspan="10" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="10" height="260px" valign="bottom" align="center">Page 4 of 4</td>
			</tr>
		</table-->
	</div>
		</div>
		<?php
		// }
		?>
		<script>
			window.print();
		</script>
	</body>		
</html>
<?php mysqli_close($con_sparcsn4); ?>
<?php 
 oci_free_statement($rslt_n4ISOType);
 oci_free_statement($rslt_n4Position); 
 oci_close($con_sparcsn4_oracle);
 ?>
