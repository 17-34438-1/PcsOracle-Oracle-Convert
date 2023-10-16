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
				<td width="15%">
					<img width="125px" height="80px" src="<?php echo IMG_PATH?>cpa_logo.png">
				</td>

				<td colspan="3" style="padding-left:80px;" align="center" width="70%">
					<h2>CHITTAGONG PORT AUTHORITY</h2>
					<h4>RELEASE ORDER FOR DELEVERY (CASH) C.P NO <u><?php echo $rslt_headerData[0]['cp_no']; ?></u> OF <u><?php echo $rslt_headerData[0]['cp_date']; ?></u></h4>
				</td>

				<td align="center" width="15%">
					<table style="border:1px solid black">
						<tr style="font-size:11px;">
							<td>BL/NO- </td>
							<td><b><nobr><?php echo $bl_no; ?></nobr></b></td>
						</tr>
						<tr style="font-size:11px;">
							<td>X/NO- </td>
							<td><b><nobr><?php echo $exitNoteNo; ?></nobr></b></td>
						</tr>
						<tr style="font-size:11px;">
							<td>VERIFY NO- &nbsp; </font> </td>
							<td><b><nobr><?php echo $rslt_headerData[0]['verify_no']; ?></nobr></b></td>
						</tr>
						<tr style="font-size:11px;">
							<td>BILL NO- &nbsp; </font> </td>
							<td><b><nobr><?php echo $billNo; ?></nobr></b></td>
						</tr>
						<tr style="font-size:11px;">
							<td>PRO- &nbsp; </font> </td>
							<td><b><nobr><?php echo $pro; ?></nobr></b></td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- <tr>
				<td colspan="5" align="center" valign="middle">	
					<h3>RELEASE ORDER FOR DELEVERY (CASH) C.P NO <u><?php //echo $rslt_headerData[0]['cp_no']; ?></u> OF <u><?php //echo $rslt_headerData[0]['cp_date']; ?></u></h3>
				</td>
			</tr> -->
		</table>

		<table width="100%" cellpadding="0" border="0" class="tblFont">
			<tr>
				<td align="center" width="20%" class="font_size">
					VESSEL : <u><?php echo $rslt_headerData[0]['Vessel_Name']; ?></u>
				</td>
				<td align="center" width="10%" class="font_size">
					REG NO : <u><?php echo $rslt_headerData[0]['Import_Rotation_No']; ?></u>
				</td>
				<td align="center" width="15%" class="font_size">
					BL NO : <u><?php echo $rslt_headerData[0]['BL_No']; ?></u>
				</td>
				<td align="center" width="15%" class="font_size">
					BE NO : <u><?php echo $rslt_headerData[0]['Bill_of_Entry_No']; ?></u>
				</td>
				<td align="center" width="15%" class="font_size">
					BE DATE : <u><?php echo $rslt_headerData[0]['Bill_of_Entry_Date']; ?></u>
				</td>				
				<td align="center" width="25%" class="font_size">
					<!-- CONSIGNEE : <u><?php //echo $rslt_headerData[0]['Notify_name']; ?></u> -->
					CONSIGNEE : <u><?php echo $cnfName; ?></u>
				</td>					
			</tr>
		</table>
		
		</br>
		
		<table border="1" width="100%" cellpadding="0" style="border-collapse:collapse" class="tblFont">
			<tr bgcolor="">
				<th align="center" rowspan="2" style="font-size:14px;">Marks & Number</th>
				<th align="center" rowspan="2" style="font-size:14px;">Quantity</th>
				<th align="center" rowspan="2" style="font-size:14px;">Unit</th>
				<th align="center" rowspan="2" style="font-size:14px;">Description</th>
				<th align="center" rowspan="2" style="font-size:14px;">Weight</th>
				<th align="center" rowspan="2" style="font-size:14px;">Measu<br/>rement</th>				
				<th align="center" colspan="2" style="font-size:14px;">Landing Charges</th>
				<th align="center" rowspan="2" style="font-size:14px;">Date of Delivery</th>
				<th align="center" rowspan="2" style="font-size:14px;">Quantity applied for delivery</th>
				<th align="center" rowspan="2" style="font-size:14px;">Consignee's Signature & License No.</th>
				<th align="center" rowspan="2" style="font-size:14px;">S.O's Signature</th>
				<th align="center" colspan="2" style="font-size:14px;">Quantity Passed out</th>
				<th align="center" rowspan="2" style="font-size:14px;">Balance Dues</th>
				<th align="center" rowspan="2" style="font-size:14px;">Signature of G.S. and Date</th>
			</tr>
			<tr bgcolor="">
				<th style="font-size:14px;">Taka</th>
				<th style="font-size:14px;">Ps.</th>
				<th style="font-size:14px;">Figure</th>
				<th style="font-size:14px;">In words</th>
			</tr>
			<!--tr height="150px" bgcolor=""-->
			<tr class="font_size">
				<td align="center"><?php echo  $rslt_roData[0]['Pack_Marks_Number']; ?></td>
				<td align="center"><?php echo  $rslt_roData[0]['Pack_Number']; ?></td>
				<td align="center"><?php echo  $rslt_roData[0]['Pack_Description']; ?></td>
				<td align="center" style="font-size:11px;"><?php echo  substr($rslt_roData[0]['Description_of_Goods'],0,80); ?></td>
				<!--td align="center"><?php echo  $rslt_roData[0]['weight']; ?></td-->
				<td align="center"><?php echo  $weight; ?></td>
				<td align="center"><?php echo  $rslt_roData[0]['Volume_in_cubic_meters']; ?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="center">
					<?php echo $rslt_roData[$i]['gate_out_time']; ?>
				</td>
				<td align="center">
					<?php echo $rslt_roData[0]['delv_quantity']; ?>
				</td>
				<td align="center" style="font-size:11px;">
					<?php echo $cnfName." (".$cnfLic.")";  //$rslt_roData[0]['signAndLic']; ?>
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
		<table width="100%" cellpadding="10" border="0">
			<tr>
				<td align="left" colspan="4" align="left" style="font-size:14px;">
					Total(in words)
				</td>
				<td colspan="2">
				</td>
				<td colspan="6" align="right" style="font-size:14px;">
					<nobr>N.B. - No alteration of any particular entered herein will be made by the consignee.</nobr>
				</td>
			</tr>
			<!-- <tr>
				<td align="left" colspan="12" align="left" >
					&nbsp;
				</td>
			</tr> -->
			<tr>
				<td align="left" colspan="12" style="font-size:13px;">
					C&F Name : <?php echo $cnfName." (".$cnfLic.")"; ?> <br/>
					<?php
						$cnfInfoQuery = "SELECT CONCAT(Address_1,' ',Address_2) AS address,Telephone_No_Land,Cell_No_1,email FROM users WHERE login_id = '$submitBy'";
						$cnfInfoRslt = $this->bm->dataselectDB1($cnfInfoQuery);
						$cnfAddress="";
						if(count($cnfInfoRslt)>0)
						{
							$cnfAddress=$cnfInfoRslt[0]['address'];
						}
					?>
					Address : <?php echo $cnfAddress; //$rslt_roData[0]['Notify_address']; ?> <br><br>
				</td>
				<!-- <td  colspan="4">
					 
				</td>
				<td colspan="4">
				</td> -->
			</tr>
		
			<tr>
				<td align="left" colspan="6" style="width:33%;font-size:13px;">
					Signature of the Jetty Sircar : <?php echo $submitBy; ?> 
					<br/>
					Name : <?php
								if(count($agent_name_rslt)>0)
								{
									echo $agent_name_rslt[0]['agent_name']." (".$agent_name_rslt[0]['nid_number'].")";
								}  
							?> 
					<br/>
					Certified that the consignment has been passed Customs Control in full/in part <br/>
					No. ..........................<br/>
					Date ............................                                    
				</td>
				<td align="left" colspan="3" style="width:33%;font-size:14px;">
					Wrong mark/No Mark Repairing application.<br/>
					filled on ..................................... and attached here with<br/>
					for .................................... Pkgs.<br/>
					Imp/................................... <br/>
					Date ..............................
				</td>
				<td align="left" colspan="3" style="width:34%;font-size:14px;">
					Certified that the particulars of the consignment noted here in are correct.<br/>
					Date ..............................
				</td>
			</tr>
		</table>
		</br>
		<!--table width="100%" class="font_size"-->
		<table width="100%">
			<!-- <tr>
				<td colspan="6">&nbsp;</td>
			</tr> -->
			<tr>
				<td>Signed By : <?php echo $verifyBy?></td>
				<td>&nbsp;</td>
				<!-- <td>Signed By : </td> -->
				<td>&nbsp;</td>
				<!-- <td>Signed By : </td> -->
				<td>&nbsp;</td>
				<!-- <td>Signed By : </td> -->
				<td>&nbsp;</td>
				<!-- <td>Signed By : <?php //echo $verifyBy?></td> -->
				<td>&nbsp;</td>
				<td>Signed By : </td>
				<!-- <td>&nbsp;</td> -->
			</tr>
			<tr>
				<td>Designation : Manifest Clerk</td>
				<td>&nbsp;</td>
				<!-- <td>Designation : TI</td> -->
				<td>&nbsp;</td>
				<!-- <td>Designation : Manifest Clerk</td> -->
				<td>&nbsp;</td>
				<!-- <td>Designation : TI</td> -->
				<td>&nbsp;</td>
				<!-- <td>Designation : Manifest Clerk</td> -->
				<td>&nbsp;</td>
				<td>Designation : TI (One Stop)</td>
				<!-- <td>&nbsp;</td> -->
			</tr>
			<tr>
				<td>Date & Time : <?php echo $verifyTime; ?></td>
				<td>&nbsp;</td>
				<!-- <td>Date & Time : </td> -->
				<td>&nbsp;</td>
				<!-- <td>Date & Time : </td> -->
				<td>&nbsp;</td>
				<!-- <td>Date & Time : </td> -->
				<td>&nbsp;</td>
				<!-- <td>Date & Time : <?php echo $verifyTime; ?></td> -->
				<td>&nbsp;</td>
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
				<td width="20%" rowspan="2">
					<img width="125px" height="80px" src="<?php echo IMG_PATH?>cpa_logo.png">
				</td>
				<td colspan="3" align="center" width="70%">
					<h2>CHITTAGONG PORT AUTHORITY</h2>
				</td>
				<td align="right" width="20%" rowspan="2">
					PRO : <?php echo $pro; ?>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center" valign="middle">	
					<h4>RELEASE ORDER FOR DELEVERY (CASH) C.P NO <u><?php echo $rslt_headerData[0]['cp_no']; ?></u> OF <u><?php echo $rslt_headerData[0]['cp_date']; ?></u></h4>
				</td>
			</tr>
			<tr>
				<td align="center" width="20%" class="font_size">
					VESSEL : <u><?php echo $rslt_headerData[0]['Vessel_Name']; ?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					REG NO : <u><?php echo $rslt_headerData[0]['Import_Rotation_No']; ?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					BL NO : <u><?php echo $rslt_headerData[0]['BL_No']; ?></u>
				</td>
				<td align="center" width="15%" class="font_size">
					BE NO : <u><?php echo $rslt_headerData[0]['Bill_of_Entry_No']; ?></u>
				</td>				
				<td align="center" width="25%" class="font_size">
					<!-- CONSIGNEE : <u><?php //echo $rslt_headerData[0]['Notify_name']; ?></u> -->
					CONSIGNEE : <u><?php echo $cnfName; ?></u>
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
							<th style="border:1px solid black;font-size:14px;" colspan="1" valign="top">
								Mark & Number
							</th>
							<th style="border:1px solid black;font-size:14px;" colspan="2" valign="top">
								Description
							</th>
						</tr>
						<tr>
							<td style="border:1px solid black" class="font_size" style="border:1" colspan="1" align="center" valign="top"><font size="2px"><?php echo  substr($rslt_roData[$i]['Pack_Marks_Number'],0,30)?></font></td>
							<td style="border:1px solid black" class="font_size" style="border:1" colspan="2" align="center" valign="top"><font size="2px"><?php echo  substr($rslt_roData[$i]['Description_of_Goods'],0,10)?></font></td>
						</tr>

						<?php
							if($contStatus == "FCL")
							{
								$query = "SELECT * 
								FROM appraisement_info_fcl 
								INNER JOIN used_equipment ON used_equipment.equipment_id = appraisement_info_fcl.equipment_id WHERE rotation='$imp_rot' AND BL_NO='$bl_no'";
							}
							else
							{
								$query = "SELECT * 
								FROM appraisement_info
								INNER JOIN used_equipment ON used_equipment.equipment_id = appraisement_info.
								
								equipment_id  
								WHERE rotation='$imp_rot' AND BL_NO='$bl_no'";
							}
							//echo $query;return;
							$result = $this->bm->dataselectdb1($query);
							

							$customs_appraise = null;
							$usedEquip = null;
							$carpainterUse = null;
							$hostingCharge = null;
							$extraMovement = null;
							$scaleFor = null;

							if(count($result)>0)
							{
								$customs_appraise = $result[0]['custom_appraiser']." - ".$result[0]['custom_appraiser_mobile'];
								$usedEquip = $result[0]['equipment_name'];
								$carpainterUse = $result[0]['carpainter_use'];
								$hostingCharge = $result[0]['hosting_charge'];
								$extraMovement = $result[0]['extra_movement'];
								$scaleFor = $result[0]['scale_for'];
							}
						?>

						<!--tr>
							<th align = "left">Customs Appraiser </th>
							<td>:</td>
							<td><?= $customs_appraise; ?></td>
						</tr-->
						<!--tr>
							<th align = "left">Used Equipment </th>
							<td>:</td>
							<td><?= $usedEquip; ?></td>
						</tr-->
						<!--tr>
							<th align = "left">Carpainter Use </th>
							<td>:</td>
							<td><?= $carpainterUse; ?></td>
						</tr-->
						<!--tr>
							<th align = "left">Hosting Charge </th>
							<td>:</td>
							<td><?= $hostingCharge; ?></td>
						</tr-->
						<!--tr>
							<th align = "left">Extra Movement </th>
							<td>:</td>
							<td><?= $extraMovement; ?></td>
						</tr-->
						<!--tr>
							<th align = "left">Scale For </th>
							<td>:</td>
							<td><?= $scaleFor; ?></td>
						</tr-->
						
						<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						</tr>

						<tr >
							<td valign="top" colspan="3">
							<table border="1" width="100%" style="border-collapse:collapse;margin-top: 25px;">
								<tr>
									<th align = "left" style="font-size:14px;">Item Name</th>
									<th align = "left" style="font-size:14px;">Phase 1 </th>
									<th align = "left" style="font-size:14px;">Phase 2 </th>
									<th align = "left" style="font-size:14px;">Phase 3 </th>
									<th align = "left" style="font-size:14px;">Phase 4 </th>

								</tr>
								<tr>
									<th align = "left" style="font-size:14px;">Customs Appraiser</th>
									<td align = "left"><?php echo $customs_appraise; ?> </td>
									<td align = "left"> </td>
									<td align = "left"> </td>
									<td align = "left"> </td>
								</tr>
								<tr>
									<th align = "left" style="font-size:14px;">Used Equipment</th>
									<td align = "left"><?php echo $usedEquip; ?> </td>
									<td align = "left"> </td>
									<td align = "left"> </td>
									<td align = "left"> </td>
								</tr>
								<tr>
									
									<th align = "left" style="font-size:14px;">Carpainter Use</th>
									<td align = "left"><?php echo $carpainterUse; ?> </td>
									<td align = "left"> </td>
									<td align = "left"> </td>
									<td align = "left"> </td>
								</tr>
								<tr>
									
									<th align = "left" style="font-size:14px;">Hosting Charge</th>
									<td align = "left"><?php echo $hostingCharge; ?> </td>
									<td align = "left"> </td>
									<td align = "left"> </td>
									<td align = "left"> </td>
								</tr>
								<tr>
									
									<th align = "left" style="font-size:14px;">Extra Movement</th>
									<td align = "left"><?php echo $extraMovement; ?></td>
									<td align = "left"> </td>
									<td align = "left"> </td>
									<td align = "left"> </td>
									
								</tr>
								<tr>
									
									<th align = "left" style="font-size:14px;">Scale For</th>
									<td align = "left"><?php echo $scaleFor; ?></td>
									<td align = "left"> </td>
									<td align = "left"> </td>
									<td align = "left"> </td>
									
								</tr>
						
							</table>
							</td>
						</tr>
						
						<tr valign="bottom">
							<td colspan="2" align="left" style="font-size:13px">
								Custom's Representative
							</td>	
							<td align="right" style="font-size:13px">
								<br><br>Consignee's Representative<br>License No.
							</td>							
						</tr>
						<tr valign="bottom">
							<td colspan="2" align="left" style="font-size:13px">
								Date & Time
							</td>	
							<td align="right" style="font-size:13px">
								Date & Time
							</td>							
						</tr>
						<tr>
							<td colspan="3">
								&nbsp;
							</td>					
						</tr>
						<tr valign="bottom">
							<td colspan="2" align="left" style="font-size:13px">
								Sr.S.O.
							</td>	
							<td align="right" style="font-size:13px">
								ASI
							</td>							
						</tr>	
						<tr valign="bottom">
							<td colspan="2" align="left" style="font-size:13px">
								Date & Time
							</td>	
							<td align="right" style="font-size:13px">
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
							<th style="border:1px solid black;font-size:14px;" valign="top">
								Mark & Number
							</th>
							<th style="border:1px solid black;font-size:14px;" valign="top">
								Description
							</th>
						</tr>						
						<tr>
							<td style="border:1px solid black;" class="font_size" style="border:1" align="center" valign="top"><font size="2px"><?php echo  substr($rslt_roData[$i]['Pack_Marks_Number'],0,30)?></font></td>
							<td style="border:1px solid black;" class="font_size" style="border:1" align="center" valign="top"><font size="2px"><?php echo  substr($rslt_roData[$i]['Description_of_Goods'],0,10)?></font></td>
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
																		
						
					</table>
				</td>
			</tr>
			<!--tr>
				<td colspan="2" align="center">Page 2 of 4</td>
			</tr-->
		</table>

		<?php //echo "PREPARED BY : ".$login_id;?>
	</div>

	<div style="page-break-after: always;"></div>


	<!-- Third page -->
	<div  class="pageBreak">		
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
				<td width="20%" rowspan="2">
					<img width="125px" height="80px" src="<?php echo IMG_PATH?>cpa_logo.png">
				</td>
				<td colspan="3" align="center" width="70%">
					<h2>CHITTAGONG PORT AUTHORITY</h2>
				</td>
				<td align="right" width="20%" rowspan="2">
					PRO : <?php echo $pro; ?>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center" valign="middle">	
					<h4>RELEASE ORDER FOR DELEVERY (CASH) C.P NO <u><?php echo $rslt_headerData[0]['cp_no']; ?></u> OF <u><?php echo $rslt_headerData[0]['cp_date']; ?></u></h4>
				</td>
			</tr>
			<tr>
				<td align="center" width="20%" class="font_size">
					VESSEL : <u><?php echo $rslt_headerData[0]['Vessel_Name']; ?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					REG NO : <u><?php echo $rslt_headerData[0]['Import_Rotation_No']; ?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					BL NO : <u><?php echo $rslt_headerData[0]['BL_No']; ?></u>
				</td>
				<td align="center" width="15%" class="font_size">
					BE NO : <u><?php echo $rslt_headerData[0]['Bill_of_Entry_No']; ?></u>
				</td>				
				<td align="center" width="25%" class="font_size">
					<!-- CONSIGNEE : <u><?php //echo $rslt_headerData[0]['Notify_name']; ?></u> -->
					CONSIGNEE : <u><?php echo $cnfName; ?></u>
				</td>				
			</tr>
		</table>
		<br>
		<table border="1" width="100%" style="border-collapse:collapse">
			<tr>
				<th style="font-size:14px;">Sl</th>
				<th style="font-size:14px;">Container</th>
				<th style="font-size:14px;">Size</th>
				<th style="font-size:14px;">Height</th>
				<th style="font-size:14px;">ISO Group</th>
				<!--th>Type</th-->
				<th style="font-size:14px;">Status</th>
				<th style="font-size:14px;">Dist Dt</th>
				<th style="font-size:14px;">CL Date</th>
				<th style="font-size:14px;">Location</th>
				<th style="font-size:14px;">MLO Status</th>
				<th style="font-size:14px;">FF Status</th>
				<th style="font-size:14px;">Destination</th>
			</tr>
			<?php
			include('dbConection.php');
			include('mydbPConnection.php');
			for($k=0;$k<count($rslt_roData);$k++)
			{
				$contISOType = $rslt_roData[$k]['cont_iso_type'];
				
				$sql_n4ISOType = "SELECT iso_group FROM ref_equip_type WHERE id='$contISOType'";
				$rslt_n4ISOType = mysqli_query($con_sparcsn4,$sql_n4ISOType);
				
				while($row_n4ISOType=mysqli_fetch_object($rslt_n4ISOType))
				{
					$n4ISOType = $row_n4ISOType->iso_group;
				}
				
				// location - start
				$contMain = $rslt_roData[$k]['cont_number'];
				$rotMain = $rslt_headerData[0]['Import_Rotation_No'];
				$igm_id = $rslt_headerData[0]['id'];
				if($contStatus=="FCL")
				{ 
					$strQuerypos="SELECT sparcsn4.inv_unit_fcy_visit.last_pos_slot AS pos,
					(SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No
					FROM sparcsn4.inv_unit
					INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
					INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					WHERE sparcsn4.inv_unit.id='$contMain' AND ib_vyg='$rotMain'";
					$strPosForFcl = mysqli_query($con_sparcsn4,$strQuerypos);
				}
				else if($contStatus=="FCL/PART")
				{ 
					$strQuerypos="SELECT sparcsn4.inv_unit_fcy_visit.last_pos_slot AS pos,
					(SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No
					FROM sparcsn4.inv_unit
					INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
					INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					WHERE sparcsn4.inv_unit.id='$contMain' AND ib_vyg='$rotMain'";
					$strPosForFcl = mysqli_query($con_sparcsn4,$strQuerypos);
				}
				else if($contStatus=="LCL")
				{

					$strQuerypos = "SELECT shed_loc AS pos,shed_yard AS Yard_No 
					FROM shed_tally_info 
					WHERE import_rotation = '$rotMain' AND cont_number = '$contMain' AND 
					(igm_sup_detail_id='$igm_id' OR igm_detail_id='$igm_id')
					ORDER BY id DESC LIMIT 1";
					$strPosForFcl = mysqli_query($con_cchaportdb,$strQuerypos);
				}
				
				//$strPosForFcl = mysqli_query($con_sparcsn4,$strQuerypos);
				
				$pos="";
				$yard_No="";

				if(!is_bool($strPosForFcl)){
					while($row=mysqli_fetch_object($strPosForFcl))
					{
						$pos = $row->pos;
						$yard_No = $row->Yard_No;
					}	
				}		
				// location - end
			?>
			<tr>
				<td align="center"><?php echo $k+1; ?></td>
				<td align="center"><?php echo $rslt_roData[$k]['cont_number']; ?></td>
				<td align="center"><?php echo $rslt_roData[$k]['cont_size']; ?></td>
				<td align="center"><?php echo $rslt_roData[$k]['cont_height']; ?></td>
				<td align="center"><?php echo $n4ISOType; ?></td>
				<!--td align="center"><?php echo $rslt_roData[$k]['cont_iso_type']; ?></td-->
				<!--td align="center"><?php echo $rslt_roData[$k]['cont_type']; ?></td-->
				<td align="center"><?php echo $rslt_roData[$k]['cont_status']; ?></td>
				<td align="center"></td>
				<td align="center"></td>
				<!--td align="center"><?php echo $rslt_roData[$k]['shed_yard']; ?></td-->
				<td align="center"><?php if($contStatus=="LCL"){ echo  $rslt_roData[$i]['shed_loc']; }else{ echo $pos; } ?></td>
				<td align="center"></td>				
				<td align="center"></td>
				<td align="center"><?php echo $rslt_roData[$k]['off_dock_id']; ?></td>
			</tr>
			<?php
			}
			?>			
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
				<td width="20%" rowspan="2">
					<img width="125px" height="80px" src="<?php echo IMG_PATH?>cpa_logo.png">
				</td>
				<td colspan="3" align="center" width="70%">
					<h2>CHITTAGONG PORT AUTHORITY</h2>
				</td>
				<td align="right" width="20%" rowspan="2">
					PRO : <?php echo $pro; ?>
				</td>	
			</tr>
			<tr>
				<td colspan="3" align="center" valign="middle">	
					<h4>RELEASE ORDER FOR DELEVERY (CASH) C.P NO <u><?php echo $rslt_headerData[0]['cp_no']; ?></u> OF <u><?php echo $rslt_headerData[0]['cp_date']; ?></u></h4>
				</td>
			</tr>
			<tr>
				<td align="center" width="20%" class="font_size">
					VESSEL : <u><?php echo $rslt_headerData[0]['Vessel_Name']; ?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					REG NO : <u><?php echo $rslt_headerData[0]['Import_Rotation_No']; ?></u>
				</td>
				<td align="center" width="20%" class="font_size">
					BL NO : <u><?php echo $rslt_headerData[0]['BL_No']; ?></u>
				</td>
				<td align="center" width="15%" class="font_size">
					BE NO : <u><?php echo $rslt_headerData[0]['Bill_of_Entry_No']; ?></u>
				</td>				
				<td align="center" width="25%" class="font_size">
					<!-- CONSIGNEE : <u><?php echo $rslt_headerData[0]['Notify_name']; ?></u> -->
					CONSIGNEE : <u><?php echo $cnfName; ?></u>
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
			<table width="100%" cellpadding="0" border="0" class="tblFont">					
				<tr>
					<td width="40%" style="vertical-align:text-top">
						<table width="100%" border="1" style="border-collapse:collapse">
							<tr valign="top" align="center">
								<td colspan="2" style="font-size:14px;">
									<u><b>RELEASE ORDER</b></u>
								</td>
							</tr>
							<tr>
								<td>CP No. : <b><?php echo $rslt_headerData[0]['cp_no']; ?></b></td>
								<td>Date : <b><?php echo $rslt_headerData[0]['cp_date']; ?></b></td>
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
								<td>Quantity : <b><?php echo  $rslt_roData[0]['Pack_Number']?></b></td>
							</tr>						
							<tr>
								<td>CFS/Shed/Yard : 
									<b>
									<?php 
										$yard_No = "";
										$pos = "";
										if($contStatus=="LCL")
										{ 
											echo  $rslt_roData[$i]['shed_yard']; 
										}
										else
										{										
											include('mydbPConnectionn4.php');
											$sql_n4Position = "SELECT inv_unit.id,freight_kind,last_pos_slot,(SELECT ctmsmis.cont_yard(last_pos_slot)) AS Yard_No
											FROM inv_unit
											INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
											WHERE inv_unit.id='".$rslt_roData[0]['cont_number']."'
											ORDER BY inv_unit.gkey DESC LIMIT 1";
											$rslt_n4Position = mysqli_query($con_sparcsn4,$sql_n4Position);
											
											while($row_pos = mysqli_fetch_object($rslt_n4Position))
											{
												$yard_No = $row_pos->Yard_No;
												$pos = $row_pos->last_pos_slot;
											}
											echo $yard_No; 
										}
									?>
									</b>
								</td>						
								<td>Location : <b><?php if($contStatus=="LCL"){ echo  $rslt_roData[$i]['shed_loc']; }else{ echo $pos; } ?><b></td>
							</tr>
							<tr>
								<td>
									Vessel Name : <b><?php echo $rslt_roData[0]['Vessel_Name']; ?></b>
								</td>
								<td>Arrival Date : <b>2021-10-15</b></td>
							</tr>						
							<tr>
								<td>Voyage : <b><?php echo $rslt_roData[$i]['Voy_No']?></b></td>						
								<td>Consignee : <b><font size="2"><?php echo $cnfName; //$rslt_roData[$i]['Notify_name']?></font></b></td>
							</tr>
							<tr height="50px" valign="bottom">
								<td colspan="2">Address : <b><font size="2"><?php echo $cnfAddress; //$rslt_roData[$i]['Notify_address']?></font></b></td>
							</tr>
							<tr>
								<td>Reg. No. : <b><?php echo $rslt_roData[$i]['Import_Rotation_No']?></b></td>
								<td>Line No. : <b><?php echo $rslt_roData[$i]['Line_No']?></b></td>
							</tr>
							<tr>
								<td>Bill of Entry No. : <b><?php echo $rslt_roData[$i]['be_no']?></b></td>
								<td>of <b><?php echo $rslt_roData[$i]['be_date']?></b></td>
							</tr>
						</table>
					</td>

					<td width="2%">&nbsp;</td>
					
					<td width="58%">
						<table border="1" style="border-collapse:collapse">
							<tr><td align="center" colspan="8" style="font-size:14px;"><b>PARTICULARS OF DELIVERY BY SHIPMENT</b></td></tr>
							<tr>
								<th style="font-size:14px;">Date</th>
								<th style="font-size:14px;">Quantity applied for reshipment</th>
								<th style="font-size:14px;">Consignee's Signature and License No.</th>
								<th style="font-size:14px;">Customs allow order</th>
								<th style="font-size:14px;">S.O's Signature</th>
								<th style="font-size:14px;">Quantity reshipped</th>
								<th style="font-size:14px;">Total Quantity (Running)</th>
								<th style="font-size:14px;">Signature of the shipping Clerk</th>
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
								<td align="center" colspan="8" style="font-size:14px;"><b>PARTICULARS OF DELIVERY BY RAIL</b></td>
							</tr>
							<tr>
								<th style="font-size:14px;">Date Note of accepted</th>
								<th style="font-size:14px;">F/Note No.</th>
								<th style="font-size:14px;">Description</th>
								<th style="font-size:14px;">Quantity accepted</th>
								<th style="font-size:14px;">Date of Loading</th>
								<th style="font-size:14px;">Quantity Loaded</th>
								<th style="font-size:14px;">Total Quantity</th>
								<th style="font-size:14px;">Signature of the Shed Officer</th>
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
								<td colspan="8" align="center" style="font-size:14px;">
									<b>BILL RECEIVE INFORMATION</b></br>
								</td>	
							</tr>
							<tr>
								<td colspan="8" align="center">
									<?php 
									// if($verify_number!=0)							
									if($rslt_headerData[0]['verify_no']!="")						
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
									if($rslt_headerData[0]['verify_no']!="") 						
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
											echo $bill_date;
										?> 
										&nbsp;&nbsp;&nbsp; 
										Vide CP No. &nbsp;:	<?php echo $cpNo; ?>
										<?php 
											// if(isset($cpnoview)) echo $cpnoview;
											echo $rslt_headerData[0]['cp_no'];
										?> &nbsp;&nbsp; Bill No - &nbsp; 
										<?php 
											if(isset($rtnContainerList[0]['master_bill_no'])) 
												echo $rtnContainerList[0]['master_bill_no']; 
											echo $billNo;
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
					</td>
				</tr>
			</table>

			
		</div>
		<br>
		<table width="100%" border="0">	
			<!-- <tr>				
				<td colspan="2">&nbsp;</td>
			</tr> -->
			<tr>
				<td>&nbsp;</td>
				<td align="left"><?php echo "BILL PREPARED BY : ".$billPreparedBy;?></td>
			</tr>
			<tr height="10px" valign="bottom">
				<td>
					&nbsp;
				</td>	
				<td align="left">
					Date & Time: <?php echo $billPreparedTime;?>
				</td>
			</tr>
		</table>
		<table width="100%" border="0">				
			<!-- <tr>
				<td colspan="9">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="9">&nbsp;</td>
			</tr> -->
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