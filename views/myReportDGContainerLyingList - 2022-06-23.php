<?php if($_POST['fileOptions']=='html'){?>
<HTML>
	<HEAD>
		<TITLE><?php echo $title;?></TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">

        </style>
</HEAD>
<BODY>

	<?php } 
	else if($_POST['fileOptions']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=DG_Container_Delivery_Report.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	?>
<html>

<body>

<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
	<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				<?php if($_POST['fileOptions']=='html'){?>
				<tr align="center">
					<td colspan="12"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
				<?php }?>
				<tr align="center">
					<td colspan="12"><font size="4"><b>DG Container Lying Report</b></font></td>
				</tr>
				<?php
					if($sCriteria == "rotation")
					{ ?>
						<tr align="center">
							<td colspan="12"><font size="4">ROTATION : <?php echo $srotation; ?></font></td>
						</tr>
				<?php
					}
					else if($sCriteria == "date")
					{ ?>
						<tr align="center">
							<td colspan="12"><font size="4">From: <?php echo $fdate." to ".$todate; ?></font></td>
						</tr>
				<?php
					}
					else if($sCriteria == "yard")
					{ ?>
						<tr align="center">
							<td colspan="12"><font size="4">Yard: <?php echo $yard."   Block: ".$block; ?></font></td>
						</tr>
				<?php
					}
				?>
				
				
			</table>
		</td>
	</tr>

	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
	</tr>
</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;" align="center">
		<tr align="center">
			<th style="border-width:3px;">Sl No.</th>
			<th style="border-width:3px;">Vessel Name</th>
			<th style="border-width:3px;">Registration No.</th>
			<th style="border-width:3px;">Berthing Time</th>
			<th style="border-width:3px;">Container No.</th>			
			<th style="border-width:3px;">Size</th>	
			<th style="border-width:3px;">MLO</th>	
			<th style="border-width:3px;">Position</th>	
			<th style="border-width:3px;">Discharge Time</th>
			<th style="border-width:3px;">BL No.</th>
			<th style="border-width:3px;">Status</th>
			<th style="border-width:3px;">IMDG Class No</th>
			<th style="border-width:3px;">UN No</th>
			<th style="border-width:3px;">Description of Goods</th>
			<th style="border-width:3px;">Importer Name & Address</th>
			<th style="border-width:3px;">Navy Comments</th>
			<th style="border-width:3px;">Delivery Status</th>
			<th style="border-width:3px;">R/L No.</th>			
			<th style="border-width:3px;">R/L Date</th>
			<th style="border-width:3px;">OBPC No.</th>			
			<th style="border-width:3px;">OBPC Date</th>
			<th style="border-width:3px;">Remarks</th>				
		</tr>
		<?php
		include("mydbPConnection.php");
		include("dbConection.php");
		
		if($sCriteria=="rotation")
			{
				$strYard="";
				$strCrt = " yard_lying_info.rotation='$srotation' AND";
							
				$strSupCrt = " yard_lying_info.rotation='$srotation' AND";
			}
		else if($sCriteria=="date")
			{
				$strYard="";
				$strCrt = " yard_lying_info.discharge_dt BETWEEN '$fdate' and '$todate' AND";
				$strSupCrt = " yard_lying_info.discharge_dt BETWEEN '$fdate' and '$todate' AND";
			}
		else if($sCriteria=="yard")
			{
				$strCrt = "";
				$strSupCrt = "";
				$strYard="";
				if($block=='ALL')
				{
					$strYard=" WHERE Yard_No='$yard' ";
				}
				else
				{
					$strYard=" WHERE Yard_No='$yard' AND carrentPosition='$block'";
				}
			}
		else if($sCriteria=="all")
		{
			$strYard="";
			$strCrt = "";
			$strSupCrt = "";
		}

		
		 $igmStr="SELECT DISTINCT igms.id AS id,igm_detail_container.cont_number,igm_detail_container.cont_size,igms.mlocode,igms.IGM_id AS IGM_id,igms.Import_Rotation_No AS Import_Rotation_No,
		cont_status,igms.imco,cont_imo,cont_un,igms.Line_No AS Line_No,
		igms.BL_No AS BL_No,igms.Pack_Number AS Pack_Number,igms.Pack_Description AS Pack_Description,igms.Pack_Marks_Number AS Pack_Marks_Number,
		igms.Description_of_Goods AS Description_of_Goods,igms.Date_of_Entry_of_Goods AS Date_of_Entry_of_Goods,igms.weight AS weight,
		igms.weight_unit,igms.net_weight,igms.net_weight_unit, igms.Bill_of_Entry_No AS Bill_of_Entry_No,
		igms.Bill_of_Entry_Date AS Bill_of_Entry_Date,igms.No_of_Pack_Delivered AS No_of_Pack_Delivered, 
		igms.No_of_Pack_Discharged AS No_of_Pack_Discharged,igms.Remarks AS Remarks,igms.AFR AS AFR,igms.ConsigneeDesc,
		igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode, (SELECT Organization_Name FROM organization_profiles orgs
		WHERE orgs.id=igms.Submitee_Org_Id) AS Organization_Name, (SELECT Vessel_Name FROM igm_masters igm_Master WHERE igm_Master.id=igms.IGM_id)
		AS vessel_Name, imco,un,extra_remarks,navyresponse.response_details1, navyresponse.response_details2,navyresponse.secondapprovaltime,
		navyresponse.response_details3,navyresponse.thirdapprovaltime, navyresponse.hold_application,navyresponse.hold_date,navyresponse.rejected_application,
		navyresponse.rejected_date,  yard_lying_info.discharge_dt,yard_lying_info.location,
		un,extra_remarks,navyresponse.response_details1, navyresponse.response_details2,navyresponse.secondapprovaltime,
		navyresponse.response_details3,navyresponse.thirdapprovaltime, navyresponse.hold_application,navyresponse.hold_date,navyresponse.rejected_application,
		navyresponse.rejected_date, navyresponse.final_amendment,navyresponse.navy_response_to_port,
		igm_detail_container.Delivery_Status_date,igms.Notify_name
		FROM  igm_detail_container 
		INNER JOIN igm_details igms ON igm_detail_container.igm_detail_id=igms.id 
		INNER JOIN yard_lying_info ON igm_detail_container.cont_number= yard_lying_info.id  AND igms.Import_Rotation_No = yard_lying_info.rotation
		LEFT JOIN igm_navy_response navyresponse ON navyresponse.igm_details_id=igms.id
		WHERE $strCrt  igm_detail_container.Delivery_Status_date IS NULL
		AND (cont_imo <> '' OR igms.imco <> '' OR igms.un <> '') 

		UNION

		SELECT DISTINCT igms_sup.id AS id,igm_sup_detail_container.cont_number,igm_sup_detail_container.cont_size,igms_sup.mlocode,igms_sup.IGM_id AS IGM_id,igms_sup.Import_Rotation_No AS Import_Rotation_No,
		cont_status,igms_sup.imco,cont_imo,cont_un,igms_sup.Line_No AS Line_No,
		igms_sup.BL_No AS BL_No,igms_sup.Pack_Number AS Pack_Number,igms_sup.Pack_Description AS Pack_Description,igms_sup.Pack_Marks_Number AS Pack_Marks_Number,
		igms_sup.Description_of_Goods AS Description_of_Goods,igms_sup.Date_of_Entry_of_Goods AS Date_of_Entry_of_Goods,igms_sup.weight AS weight,
		igms_sup.weight_unit,igms_sup.net_weight,igms_sup.net_weight_unit, igms_sup.Bill_of_Entry_No AS Bill_of_Entry_No,
		igms_sup.Bill_of_Entry_Date AS Bill_of_Entry_Date,igms_sup.No_of_Pack_Delivered AS No_of_Pack_Delivered, 
		igms_sup.No_of_Pack_Discharged AS No_of_Pack_Discharged,igms_sup.Remarks AS Remarks,igms_sup.AFR AS AFR,igms_sup.ConsigneeDesc,
		igms_sup.NotifyDesc,igms_sup.navy_comments,igms_sup.Submitee_Org_Id,igms_sup.mlocode, (SELECT Organization_Name FROM organization_profiles orgs
		WHERE orgs.id=igms_sup.Submitee_Org_Id) AS Organization_Name, (SELECT Vessel_Name FROM igm_masters igm_Master WHERE igm_Master.id=igms_sup.IGM_id)
		AS vessel_Name, imco,un,extra_remarks,navyresponse.response_details1, navyresponse.response_details2,navyresponse.secondapprovaltime,
		navyresponse.response_details3,navyresponse.thirdapprovaltime, navyresponse.hold_application,navyresponse.hold_date,navyresponse.rejected_application,
		navyresponse.rejected_date, yard_lying_info.discharge_dt, yard_lying_info.location,
		un,extra_remarks,navyresponse.response_details1, navyresponse.response_details2,navyresponse.secondapprovaltime,
		navyresponse.response_details3,navyresponse.thirdapprovaltime, navyresponse.hold_application,navyresponse.hold_date,navyresponse.rejected_application,
		navyresponse.rejected_date, navyresponse.final_amendment,navyresponse.navy_response_to_port,
		igm_sup_detail_container.Delivery_Status_date,igms_sup.Notify_name
		FROM igm_sup_detail_container
		INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id 
		INNER JOIN igm_details igms_sup ON igms_sup.id=igm_supplimentary_detail.igm_detail_id
		INNER JOIN yard_lying_info ON igm_sup_detail_container.cont_number= yard_lying_info.id  AND igms_sup.Import_Rotation_No = yard_lying_info.rotation
		LEFT JOIN igm_navy_response navyresponse ON navyresponse.igm_details_id=igms_sup.id 
		WHERE $strSupCrt   igm_sup_detail_container.Delivery_Status_date IS NULL
		AND (cont_imo <> '' OR igms_sup.imco <> '' OR igms_sup.un <> '')";
		//return;
		$query=mysqli_query($con_cchaportdb,$igmStr);
		$i=0;
		$a = "";
		while($row=mysqli_fetch_object($query)){
			$cnumber = $row->cont_number;
			$rotnumber = $row->Import_Rotation_No;		
			
			 $naviStr="SELECT * FROM (SELECT inv_unit_fcy_visit.flex_string04 AS rl_no,inv_unit_fcy_visit.flex_string04 AS rl_date,inv_unit_fcy_visit.flex_string07 AS obpc_number,
				inv_unit_fcy_visit.flex_string08 AS obpc_date,inv_unit_fcy_visit.time_in, inv_unit_fcy_visit.time_out,
				sparcsn4.argo_carrier_visit.ata,r.id as mlo,
				 IFNULL((SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7) 
				FROM sparcsn4.srv_event INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey 
				WHERE sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND sparcsn4.srv_event.event_type_gkey IN(18,13,16) AND 
				sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND sparcsn4.srv_event_field_changes.new_value !='' AND 
				sparcsn4.srv_event_field_changes.new_value !='Y-CGP-.' AND sparcsn4.srv_event.gkey<(SELECT sparcsn4.srv_event.gkey 
				FROM sparcsn4.srv_event INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
				WHERE sparcsn4.srv_event.event_type_gkey=4 AND sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND metafield_id='unitFlexString01' AND
				new_value IS NOT NULL ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1) ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1),
				(SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7) FROM sparcsn4.srv_event 
				INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey 
				WHERE sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND sparcsn4.srv_event.event_type_gkey IN(18,13,16) 
				ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1)) AS carrentPosition, 
				
				(SELECT ctmsmis.cont_yard(carrentPosition)) AS Yard_No, 
				(SELECT ctmsmis.cont_block(carrentPosition, Yard_No)) AS Block_No
				FROM sparcsn4.inv_unit 
				INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
				INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.inv_unit_fcy_visit.actual_ib_cv=sparcsn4.argo_carrier_visit.gkey 
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
				INNER JOIN sparcsn4.ref_bizunit_scoped r ON r.gkey=inv_unit.line_op 
				INNER JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
				WHERE inv_unit.id ='$cnumber' AND vsl_vessel_visit_details.ib_vyg='$rotnumber'
				AND sparcsn4.inv_goods.destination='2591' AND sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD' AND inv_unit.category='IMPRT')
				AS tmp $strYard";
			//echo $cnumber."/".$rotnumber;
			$subQuery = mysqli_query($con_sparcsn4,$naviStr);
							
			//$rowSubQuery=mysqli_fetch_object($subQuery);
			//var_dump($rowSubQuery);
			while($rowSubQuery=mysqli_fetch_object($subQuery)){
				$i++;
		?>
		<tr align="center">
			<td style="border-width:3px;"><?php echo $i;?></td>
			<td style="border-width:3px;"><?php if($row->vessel_Name) echo $row->vessel_Name; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row->Import_Rotation_No) echo $row->Import_Rotation_No; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if(@$rowSubQuery->ata) echo $rowSubQuery->ata; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row->cont_number) echo $row->cont_number; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row->cont_size) echo $row->cont_size; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if(@$rowSubQuery->mlo) echo $rowSubQuery->mlo; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if(@$rowSubQuery->carrentPosition) echo $rowSubQuery->carrentPosition; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row->discharge_dt) echo $row->discharge_dt; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row->BL_No) echo $row->BL_No; else echo "&nbsp;";?></td>	
			<td style="border-width:3px;"><?php if($row->cont_status) echo $row->cont_status; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row->cont_imo) echo $row->cont_imo; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row->cont_un) echo $row->cont_un; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row->Description_of_Goods) echo $row->Description_of_Goods; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row->Notify_name) echo $row->Notify_name."\n".$row->NotifyDesc; else echo "&nbsp;";?></td>
			<td style="border-width:3px;">
				<?php					
					if($row->final_amendment==2)
					{
					print("Lab Com:".$row->response_details1."<br> NAIO Com:".
					$row->response_details2."<br> Hold:".$row->hold_application);
					//print($row->hold_application);
					}
					else if($row->final_amendment==3)
					{
					print("Lab Com:".$row->response_details1."<br> NAIO Com:".
					$row->response_details2."<br> Rej:".$row->rejected_application);
					
					}
					else if($row->navy_response_to_port !="" and $row->response_details3 =="")
					{		
					//print($row->navy_response_to_port);
					}
					else if($row->final_amendment==1)
					{
					print("Lab Com:".$row->response_details1."<br> NAIO Com:".
					$row->response_details2."<br> Finally:".$row->response_details3);
					
					}
					else
					{
					print("&nbsp;");
					}
				?>
			</td>
			<td style="border-width:3px;"><?php if($row->Delivery_Status_date) echo $row->Delivery_Status_date; else echo "&nbsp;";?></td>		
			<td style="border-width:3px;"><?php if(@$rowSubQuery->rl_no) echo $rowSubQuery->rl_no; else echo "&nbsp;";?></td>			
			<td style="border-width:3px;"><?php if(@$rowSubQuery->rl_date) echo $rowSubQuery->rl_date; else echo "&nbsp;";?></td>			
			<td style="border-width:3px;"><?php if(@$rowSubQuery->obpc_number) echo $rowSubQuery->obpc_number; else echo "&nbsp;";?></td>			
			<td style="border-width:3px;"><?php if(@$rowSubQuery->obpc_date) echo $rowSubQuery->obpc_date; else echo "&nbsp;";?></td>	
	
			<td style="border-width:3px;"><?php //if($row->Remarks) echo $row->Remarks; else echo "&nbsp;";?></td>			
		</tr>
		<?php }
		}
		?>
	</table>
<br />
<br />
<?php 
if($_POST['fileOptions']=='html'){?>	
	</BODY>
</HTML>
<?php }?>