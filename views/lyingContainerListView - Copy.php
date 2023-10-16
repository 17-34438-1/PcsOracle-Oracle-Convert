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
		header("Content-Disposition: attachment; filename=Lying_Report.xls;");
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
					<td colspan="12"><font size="4"><b> Container Lying Report</b></font></td>
				</tr>
				
			</table>
		</td>
	</tr>

	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
	</tr>
</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;" align="center">
		<tr align="center">
			<th style="border-width:3px;">Sl.</th>
			<th style="border-width:3px;">Container</th>
			<th style="border-width:3px;">Status</th>		
			<th style="border-width:3px;">Size</th>	
			<th style="border-width:3px;">Height</th>	
			<th style="border-width:3px;">Weight</th>	
			<th style="border-width:3px;">Rotation</th>	
			<th style="border-width:3px;">Vessel</th>	
			<th style="border-width:3px;">Yard</th>
			<th style="border-width:3px;">Block</th>
			<th style="border-width:3px;">BL_No</th>
			<th style="border-width:3px;">Pack_Number</th>
			<th style="border-width:3px;">Pack_Description</th>
			<th style="border-width:3px;">Notify_name</th>
			<th style="border-width:3px;">Consignee_Name</th>
			<th style="border-width:3px;">IMO</th>
			<th style="border-width:3px;">Description of Goods</th>		
			<th style="border-width:3px;">Lying Days</th>		
		</tr>
		<?php
		include("mydbPConnection.php");
		
		
	
		
		//$query=mysqli_query($con_cchaportdb,"SELECT DISTINCT igms.id AS 
	//	while($row=mysqli_fetch_object($query)){
		//	$cnumber = $row->cont_number;
		//	$rotnumber = $row->Import_Rotation_No;
			
			include("dbConection.php");
			//echo $cnumber."/".$rotnumber;
			$limitCon = "";
			if($lim=="fst")
				$limitCon = "limit 0,15000";
			else
				$limitCon = "limit 15000,16000";
			
			 $str="SELECT sparcsn4.inv_unit.id as cont_no,
			sparcsn4.inv_unit.freight_kind as cont_status, sparcsn4.inv_unit_fcy_visit.last_pos_slot,
			DATEDIFF( date(NOW()), DATE(sparcsn4.inv_unit_fcy_visit.time_in)) AS lying_days,
			(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM ref_equip_type 
			INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey
			) AS size,
			(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10 FROM ref_equip_type 
			INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey
			) AS height,
			sparcsn4.inv_unit_fcy_visit.time_in,
			(SELECT ctmsmis.cont_yard(last_pos_slot)) AS Yard_No,
			(SELECT ctmsmis.cont_block(last_pos_slot, Yard_No)) AS Block_No,
			(SELECT sparcsn4.argo_carrier_visit.cvcvd_gkey FROM sparcsn4.argo_carrier_visit WHERE sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv) AS cvcvd_gkey,
			(SELECT ib_vyg FROM sparcsn4.vsl_vessel_visit_details WHERE vvd_gkey=cvcvd_gkey) AS rotation,
			(SELECT sparcsn4.vsl_vessels.name FROM sparcsn4.vsl_vessels
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vessel_gkey=sparcsn4.vsl_vessels.gkey
			WHERE vvd_gkey=cvcvd_gkey) AS vslName
			FROM sparcsn4.inv_unit 
			INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD' AND sparcsn4.inv_unit.freight_kind!='MTY' AND sparcsn4.inv_unit.category='IMPRT' ".$limitCon;
			$nQuery = mysqli_query($con_sparcsn4,$str);
			$i=0;				
			//$n4_str=mysqli_fetch_object($n4Query);
			while($row4 = mysqli_fetch_object($nQuery)){
						//	$numrowDt = mysqli_num_rows($nQuery);
							//echo $numrowDt;
				$cont_no=$row4->cont_no;			
				$rotation=$row4->rotation;			
			//include("mydbPConnection.php");
			
			$igmQuery = mysqli_query($con_cchaportdb,"SELECT igm_details.id,cont_number,igm_details.Import_Rotation_No,igm_details.BL_No, igm_details.Pack_Number, igm_details.Pack_Description,
			igm_detail_container.cont_size, igm_detail_container.cont_height, igm_detail_container.cont_gross_weight,
			Notify_name,Consignee_name,type_of_igm,igm_details.Description_of_Goods, igm_detail_container.cont_imo,igm_detail_container.cont_un
			cont_seal_number,cont_iso_type FROM igm_details  
			INNER JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id 
			WHERE igm_details.Import_Rotation_No='$rotation' AND igm_detail_container.cont_number='$cont_no'");
			//$rowIgm=mysqli_fetch_object($igmQuery);	
			$i++;
			$cont_gross_weight = "";
			$BL_No = "";
			$Pack_Number = "";
			$Pack_Description = "";
			$Notify_name = "";
			$Consignee_name = "";
			$cont_imo = "";
			$Description_of_Goods = "";
			while($rowIgm = mysqli_fetch_object($igmQuery)){
				$cont_gross_weight = $rowIgm->cont_gross_weight;
				$BL_No = $rowIgm->BL_No;
				$Pack_Number = $rowIgm->Pack_Number;
				$Pack_Description = $rowIgm->Pack_Description;
				$Notify_name = $rowIgm->Notify_name;
				$Consignee_name = $rowIgm->Consignee_name;
				$cont_imo = $rowIgm->cont_imo;
				$Description_of_Goods = $rowIgm->Description_of_Goods;
			}	
		?>
		<tr align="center">
			<td style="border-width:3px;"><?php echo $i;?></td>
			<td style="border-width:3px;"><?php if($row4->cont_no) echo $row4->cont_no; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row4->cont_status) echo $row4->cont_status; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row4->size) echo $row4->size; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row4->height) echo $row4->height; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php echo $cont_gross_weight; ?></td>
			<td style="border-width:3px;"><?php if($row4->rotation) echo $row4->rotation; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row4->vslName) echo $row4->vslName; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row4->Yard_No) echo $row4->Yard_No; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row4->Block_No) echo $row4->Block_No; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php echo $BL_No; ?></td>
			<td style="border-width:3px;"><?php echo $Pack_Number; ?></td>
			<td style="border-width:3px;"><?php echo $Pack_Description; ?></td>
			<td style="border-width:3px;"><?php echo $Notify_name; ?></td>
			<td style="border-width:3px;"><?php echo $Consignee_name; ?></td>
			<td style="border-width:3px;"><?php echo $cont_imo; ?></td>
			<td style="border-width:3px;"><?php echo $Description_of_Goods; ?></td>
			<td style="border-width:3px;"><?php if($row4->lying_days) echo $row4->lying_days; else echo "&nbsp;";?></td>

		
		</tr>
		<?php 
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