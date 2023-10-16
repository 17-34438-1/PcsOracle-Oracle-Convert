<?php if(@$_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Bearth Operator Report</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php } 
	else if(@$_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=EXPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	include("dbConection.php");
	include("dbOracleConnection.php");
	

	$query1 = oci_parse($con_sparcsn4_oracle,"select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	oci_execute($query1);
	


	$vvdGkey ="";
	
	while(($row=oci_fetch_object($query1)) !=false)
	{
		$vvdGkey=$row->VVD_GKEY;
		
	}

	// $sql=oci_parse($con_sparcsn4_oracle,"select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	// oci_execute($query1);
	// $row=oci_fetch_object($sql);
	// $vvdGkey=$row->vvd_gkey;
	
	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql);
	
	$query2 = oci_parse($con_sparcsn4_oracle,"select vsl_vessels.name as vsl_name,
	NVL(vsl_vessel_visit_details.flex_string02,NVL(vsl_vessel_visit_details.flex_string03,'')) as berth_op, 
	NVL(argo_quay.id,'') as berth,argo_carrier_visit.ata,argo_carrier_visit.atd from vsl_vessel_visit_details
	inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
	where vsl_vessel_visit_details.vvd_gkey='$vvdGkey'");
	oci_execute($query2);
	$vsl_name="";
	$ata="";
	while(($row1=oci_fetch_object($query2)) !=false)
	{
		$vsl_name=$row1->VSL_NAME;
		$ata=$row1->ATA;
		
	}
	?>
<html>
<title>Export Container Not Found List</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<!--tr align="center">
					<td colspan="12"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
				</tr-->
				<?php
				if($_POST['options']=='html')
				{
				?>
					<tr>
						<td colspan="12" align="center"><img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
					</tr>
				<?php
				}
				else
				{
				?>
					<tr align="center">
						<td colspan="12"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
					</tr>
				<?php
				}
				?>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><u>Export Container Not Found Report</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>

				<tr>
					<td colspan="3" align="center"><font size="4"><b> <?php  Echo $vsl_name;?></b></font></td>
					<td colspan="3" align="center"><font size="4"><b>Voy: <?php  Echo $voysNo;?></b></font></td>
					<td colspan="3" align="center"><font size="4"><b>EXP ROT.: <?php  Echo $ddl_imp_rot_no;?></b></font></td>
					<td colspan="3" align="center"><font size="4"><b><?php  Echo $ata;?></b></font></td>
					
				</tr>

			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<?php
		if($_POST['options'] == 'xl'){
			echo "<table width='100%' border ='1' cellpadding='0' cellspacing='0'>";
		}else{
			echo "<table class='table table-bordered table-responsive table-hover table-striped mb-none'>";
		}
	?>
	
	<tr  align="center">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Rotation No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
		<td style="border-width:3px;border-style: double;"><b>Status</b></td>		
		<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
		<td style="border-width:3px;border-style: double;"><b>Yard</b></td>
		<td style="border-width:3px;border-style: double;"><b>Current Position</b></td>
		<td style="border-width:3px;border-style: double;"><b>Discharge date</b></td>
		<td style="border-width:3px;border-style: double;"><b>Assignment date</b></td>
		<td style="border-width:3px;border-style: double;"><b>POD</b></td>
		<td style="border-width:3px;border-style: double;"><b>Stowage</b></td>
		<td style="border-width:3px;border-style: double;"><b>Coming From</b></td>
		<td style="border-width:3px;border-style: double;"><b>Commodity</b></td>
		<td style="border-width:3px;border-style: double;"><b>Remarks</b></td>
		<td style="border-width:3px;border-style: double;"><b>User Id</b></td>
		
	</tr>

<?php
	

	$query2 = oci_parse($con_sparcsn4_oracle,"select vsl_vessels.name as vsl_name,
	NVL(vsl_vessel_visit_details.flex_string02,NVL(vsl_vessel_visit_details.flex_string03,'')) as berth_op, 
	NVL(argo_quay.id,'') as berth,argo_carrier_visit.ata,argo_carrier_visit.atd from vsl_vessel_visit_details
	inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
	where vsl_vessel_visit_details.vvd_gkey='$vvdGkey'");
	oci_execute($query2);

	$vsl_name="";
	$ata="";
	while(($row1=oci_fetch_object($query2)) !=false)
	{
		$vsl_name=$row1->VSL_NAME;
		$ata=$row1->ATA;
		
	}

	
// 	$query=oci_parse($con_sparcsn4_oracle,"


// 	select * from(
// 	SELECT inv_unit.id AS id,
// 	inv_unit_fcy_visit.time_in AS fcy_time_in,
// 	inv_unit_fcy_visit.transit_state AS fcy_transit_state,

// 	ref_equip_type.id AS iso,ref_bizunit_scoped.id AS mlo, 
					
// 	inv_unit.freight_kind,
   
// 	srv_event.placed_time,
	
// 	ref_commodity.short_name
// 	FROM inv_unit 
// 	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
// 	INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
// 	INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
// 	INNER JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey 
// 		   INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
// INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	
// 	INNER JOIN srv_event ON srv_event.applied_to_gkey=inv_unit.gkey
// 	WHERE srv_event.placed_time   between to_date(concat('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS' ) and to_date(concat('$todate',' 20:00:00'),'YYYY-MM-DD HH24-MI-SS') 
   
// 	)  tbl WHERE fcy_transit_state NOT IN ('S60_LOADED','S70_DEPARTED')
// ");



    $query=oci_parse($con_sparcsn4_oracle,"

	
select * from(
SELECT inv_unit.id AS id,
inv_unit_fcy_visit.time_in AS fcy_time_in,
inv_unit_fcy_visit.transit_state AS fcy_transit_state,
inv_unit_fcy_visit.ARRIVE_POS_SLOT as stowage_pos,inv_unit_fcy_visit.last_pos_slot, inv_unit_fcy_visit.LAST_POS_LOCTYPE AS coming_from ,
ref_equip_type.id AS iso,ref_bizunit_scoped.id AS mlo, REF_ROUTING_POINT.ID as pod,vsl_vessel_visit_details.vvd_gkey,vsl_vessel_visit_details.ib_vyg as rot,
inv_unit.freight_kind,
inv_unit.goods_and_ctr_wt_kg as weight,
srv_event.placed_time,
inv_unit_fcy_visit.flex_date01 AS assignmentdate ,
ref_commodity.short_name
FROM inv_unit 
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
INNER JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey 
INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
INNER JOIN REF_ROUTING_POINT ON REF_ROUTING_POINT.GKEY=INV_UNIT.POD1_GKEY
INNER JOIN srv_event ON srv_event.applied_to_gkey=inv_unit.gkey
WHERE to_char(srv_event.placed_time,'YYYY-MM-DD')   between '$fromdate' and '$todate' or vsl_vessel_visit_details.vvd_gkey='$vvdGkey'
)  tbl WHERE fcy_transit_state NOT IN ('S60_LOADED','S70_DEPARTED')
");
	$i=0;
	$j=0;
	
	$mlo="";

	oci_execute($query);
	while (($row = oci_fetch_object($query)) != false){

	$i++;
	
		
	
?>

<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
		<td><?php if($row->ROT) echo $row->ROT; else echo "&nbsp;";?></td>
		<td><?php if($row->ISO) echo $row->ISO; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<td><?php if($row->FREIGHT_KIND) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
		<td><?php if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
		<td><?php if($row->yard) echo $row->yard; else echo "&nbsp;";?></td>
		<td><?php if($row->LAST_POS_SLOT) echo $row->LAST_POS_SLOT; else echo "&nbsp;";?></td>
		<td><?php if($row->FCY_TIME_IN) echo $row->FCY_TIME_IN; else echo "&nbsp;";?></td>
		<td><?php if($row->ASSIGNMENTDATE) echo $row->ASSIGNMENTDATE; else echo "&nbsp;";?></td>
		
		
		<td><?php if($row->POD) echo $row->POD; else echo "&nbsp;";?></td>
		<td><?php if($row->STOWAGE_POS) echo $row->STOWAGE_POS; else echo "&nbsp;";?></td>
		
		<td><?php if($row->COMING_FROM) echo $row->COMING_FROM; else echo "&nbsp;";?></td>
		<td><?php if($row->SHORT_NAME) echo $row->SHORT_NAME; else echo "&nbsp;";?></td>
		<td><?php echo "&nbsp;";?></td>
		<td><?php echo "&nbsp;";?></td>
		
				
	</tr>
<?php } ?>
</table>

<?php 
mysqli_close($con_sparcsn4);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
