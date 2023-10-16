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
			<th style="border-width:3px;">Port.Limit.Dt</th>
			<th style="border-width:3px;">Berthing.Dt</th>
			<th style="border-width:3px;">Discharge.Dt</th>
			<th style="border-width:3px;">Last_move.Dt</th>
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
		
		
	
		

			
			include("dbConection.php");
			include("dbOracleConnection.php");
			
			
			$limitCon = "";
			if($lim=="fst")
			
				
			   $limitCon = "OFFSET 0 ROWS FETCH NEXT 15000 ROWS ONLY";
				
			else
			
				$limitCon = "OFFSET 15000 ROWS FETCH NEXT 16000 ROWS ONLY";
			
		
			 $str="select tbl2.*,
			 (SELECT flex_date07 FROM vsl_vessel_visit_details WHERE vvd_gkey=cvcvd_gkey) AS outer_dt,
			 (SELECT ib_vyg FROM vsl_vessel_visit_details WHERE vvd_gkey=cvcvd_gkey) AS rotation,
			 (SELECT vsl_vessels.name FROM vsl_vessels
			 INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey
			 WHERE vvd_gkey=cvcvd_gkey) AS vslName
			 from(
			 select tbl.*
			 from(
			 SELECT inv_unit.id as cont_no,
			 inv_unit.freight_kind as cont_status, inv_unit_fcy_visit.last_pos_slot,
			 extract(day from CURRENT_DATE-inv_unit_fcy_visit.time_in)AS lying_days,
			 inv_unit_fcy_visit.time_in,
			 (SELECT argo_carrier_visit.ata FROM argo_carrier_visit WHERE argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv) AS ata,
			 (SELECT argo_carrier_visit.cvcvd_gkey FROM argo_carrier_visit WHERE argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv) AS cvcvd_gkey,
			 inv_unit_fcy_visit.time_move
			 
			 FROM inv_unit 
			 INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			 WHERE inv_unit_fcy_visit.transit_state='S40_YARD' AND inv_unit.freight_kind!='MTY' AND inv_unit.category='IMPRT' $limitCon
			 ) tbl
			 )tbl2 
			 ";
			
			$nQuery = oci_parse($con_sparcsn4_oracle, $str);
            oci_execute($nQuery);
			$i=0;				

			
			while(($row4=oci_fetch_object($nQuery))!=false)
			{
				
				$cont_no=$row4->CONT_NO;			
				$rotation=$row4->ROTATION;	
				$last_pos_slot=$row4->LAST_POS_SLOT;
				$str1="SELECT ctmsmis.cont_yard('$last_pos_slot') AS Yard_No";		
				$nQuery1 = mysqli_query($con_sparcsn4,$str1);
				$yardNo="";
				while($row5 = mysqli_fetch_object($nQuery1)){
					$yardNo=$row5->Yard_No;
				}
			    
				


				 $str2="SELECT ctmsmis.cont_block('$last_pos_slot','$yardNo') AS Block_No";		
				$nQuery2 = mysqli_query($con_sparcsn4,$str2);
				$block_No="";
				while($row6 = mysqli_fetch_object($nQuery2)){
					$block_No=$row6->Block_No;
				}
			    
			
			  $igmQuery = mysqli_query($con_cchaportdb,"SELECT igm_details.id,cont_number,igm_details.Import_Rotation_No,igm_details.BL_No, igm_details.Pack_Number, igm_details.Pack_Description,
			   igm_detail_container.cont_size, igm_detail_container.cont_height, igm_detail_container.cont_gross_weight,
			   Notify_name,Consignee_name,type_of_igm,igm_details.Description_of_Goods, igm_detail_container.cont_imo,igm_detail_container.cont_un
			   cont_seal_number,cont_iso_type FROM igm_details  
			   INNER JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id 
			   WHERE igm_details.Import_Rotation_No='$rotation' AND igm_detail_container.cont_number='$cont_no'");
			   	
			   $i++;
			    $cont_gross_weight = "";
			    $BL_No = "";
			    $cont_size = "";
			    $cont_height = "";
			    $Pack_Number = "";
			    $Pack_Description = "";
			    $Notify_name = "";
			    $Consignee_name = "";
			    $cont_imo = "";
			    $Description_of_Goods = "";
			 while($rowIgm = mysqli_fetch_object($igmQuery))
			 {
				$cont_gross_weight = $rowIgm->cont_gross_weight;
				$cont_size = $rowIgm->cont_size;
				$cont_height = $rowIgm->cont_height;
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
			<td style="border-width:3px;"><?php if($row4->CONT_NO) echo $row4->CONT_NO; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row4->CONT_STATUS) echo $row4->CONT_STATUS; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php echo $cont_size; ?></td>
			<td style="border-width:3px;"><?php echo $cont_height; ?></td>
			<td style="border-width:3px;"><?php echo $cont_gross_weight; ?></td>
			<td style="border-width:3px;"><?php if($row4->ROTATION) echo $row4->ROTATION; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row4->VSLNAME) echo $row4->VSLNAME; else echo "&nbsp;";?></td>
			 <td style="border-width:3px;"><?php if($yardNo) echo $yardNo; else echo "&nbsp;";?></td> 
			  <td style="border-width:3px;"><?php if($block_No) echo $block_No; else echo "&nbsp;";?></td> 
			<td style="border-width:3px;"><?php echo $BL_No; ?></td>
			<td style="border-width:3px;"><?php if($row4->OUTER_DT) echo $row4->OUTER_DT; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row4->ATA) echo $row4->ATA; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row4->TIME_IN) echo $row4->TIME_IN; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php if($row4->TIME_MOVE) echo $row4->TIME_MOVE; else echo "&nbsp;";?></td>
			<td style="border-width:3px;"><?php echo $Pack_Number; ?></td>
			<td style="border-width:3px;"><?php echo $Pack_Description; ?></td>
			<td style="border-width:3px;"><?php echo $Notify_name; ?></td>
			<td style="border-width:3px;"><?php echo $Consignee_name; ?></td>
			<td style="border-width:3px;"><?php echo $cont_imo; ?></td>
			<td style="border-width:3px;"><?php echo $Description_of_Goods; ?></td>
			<td style="border-width:3px;"><?php if($row4->LYING_DAYS) echo $row4->LYING_DAYS; else echo "&nbsp;";?></td>

		
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
