<?php if(@$_POST['options']=='html'){?>
	<?php } 
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=OffDock_Removal_Position.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	include("dbConection.php");
	include("dbOracleConnection.php");		
	?>
<html>
<title>Export Container Loading List</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				
				<!--tr align="center">
					<td colspan="12"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
				</tr-->
				<tr>
					<td colspan="12" align="center"><img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><?php echo $title;?></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>

				<tr>
					<td colspan="3" align="center" style="padding-left:240px;"><font size="4"><b> Shift : <?php echo $search_shift." (TIME From ".$fromTime." - To ".$toTime." )";?></b></font></td>
					<td colspan="3" align="center"><font size="4"><b> Date : <?php echo $search_by;?></b></font></td>
				</tr>

			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
	<tr  align="center">
		<td style="border-width:3px;border-style: double;"><b>Sl.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Size</b></td>
		<td style="border-width:3px;border-style: double;"><b>Seal No</b></td>
		<td style="border-width:3px;border-style: double;"><b>Vessel Name</b></td>		
		<td style="border-width:3px;border-style: double;"><b>Rot No</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
		<td style="border-width:3px;border-style: double;"><b>Depot</b></td>
		<td style="border-width:3px;border-style: double;"><b>Entered Yard</b></td>
		<td style="border-width:3px;border-style: double;"><b>Exited Yard</b></td>
		<td style="border-width:3px;border-style: double;"><b>Gate No</b></td>
		<td style="border-width:3px;border-style: double;"><b>User</b></td>

	</tr>

<?php
$cond = "";
$status=0;
if($terminal=="GCB")
{
	if($search_yard=="all")
	{
		$cond = " where yard_no='$terminal'";
		$status=0;
	}
	else
	{
		$cond = " where yard_no='$terminal' and block_no='$search_yard'";
		$status=1;
	}
	
}
else
{
	$cond = " where yard_no='$terminal'";
}
/*if($search_yard=='all')
{
	$cond = "";
}
else
{
	$cond = " where block_no='$search_yard'";
}*/
	$strQuery = "select * from 
	( SELECT inv_unit.id as container, inv_unit.seal_nbr1,inv_unit.remark, 
	vsl_vessels.name,vsl_vessel_visit_details.ib_vyg, inv_goods.destination,entered_yard,exited_yard,
	(select id from road_gates where gkey= road_truck_visit_details.gate_gkey) as GateNo, 
	 (SELECT substr(srv_event_field_changes.prior_value,7) FROM srv_event 
	INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
	WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey 
	IN(22) ORDER BY srv_event.gkey DESC fetch first 1 rows only) AS slot, inv_unit_fcy_visit.time_out, 
	(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type
	INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
	INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey 
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only) as siz,
	ref_bizunit_scoped.id as mlo,road_truck_visit_details.creator 
	FROM inv_unit INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv 
	INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey 
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
	inner join inv_goods on inv_goods.gkey=inv_unit.goods 
	inner join ref_bizunit_scoped on ref_bizunit_scoped.gkey=inv_unit.line_op
	inner join road_truck_transactions on road_truck_transactions.unit_gkey=inv_unit.gkey
	inner join road_truck_visit_details on road_truck_visit_details.tvdtls_gkey=road_truck_transactions.truck_visit_gkey
	WHERE inv_unit_fcy_visit.time_load between to_date('".$search_by." ".$fromTime."','yyyy-mm-dd hh24-mi-ss') and 
	to_date('".$search_by." ".$toTime."','yyyy-mm-dd hh24-mi-ss') 
	and inv_goods.destination not in('2591','2592','BDCGP')
	and inv_goods.destination is not null and road_truck_transactions.status !='CANCEL'
	)  tmp 
	";
	$strQueryRes=oci_parse($con_sparcsn4_oracle,$strQuery);
	oci_execute($strQueryRes);
	$i=0;
	$mlo="";
	$totCont = "";
	while(($row=oci_fetch_object($strQuery)) !=false){
		$viewStatus=0;
		$slot="";
		$destination="";
		$offdock="";
		$slot=$row->SLOT;
		$destination=$row->DESTINATION;
		$yardNo="";
		$blockNo="";
		if($status==0){
		$strQuery1="SELECT ctmsmis.cont_yard('$slot') AS yard_no";
		$query1=mysqli_query($con_sparcsn4,$strQuery1);
		$strQuery1Row=mysqli_fetch_object($query1);	
		$yardNo=$strQuery1Row->yard_no;
		if($yardNo==$terminal){
			$viewStatus=1;

		}
		else{
			$viewStatus=0;

		}

		}
		else{
			$strQuery1="SELECT ctmsmis.cont_yard('$slot') AS yard_no";
	
			$query1=mysqli_query($con_sparcsn4,$strQuery1);
			$strQuery1Row=mysqli_fetch_object($query1);	
			$yardNo=$strQuery1Row->yard_no;
	
			$strQuery2="SELECT ctmsmis.cont_block('$slot','$yardNo') AS block_no";
		
			$query2=mysqli_query($con_sparcsn4,$strQuery2);	
			$strQuery2Row=mysqli_fetch_object($query2);	
			$blockNo=$strQuery2Row->block_no;
			if($yardNo==$terminal && $blockNo==$search_yard){

				$viewStatus=1;
			}

			else{

				$viewStatus=0;

			}

		}
		$strQuery3="select ctmsmis.offdoc.code from ctmsmis.offdoc where ctmsmis.offdoc.id='$destination'";

			$query3=mysqli_query($con_sparcsn4,$strQuery3);	
			$strQuery3Row=mysqli_fetch_object($query3);	
			$offdock=$strQuery3Row->code;

		
    if($viewStatus==1){

	$i++;
	$totCont = $totCont.$row->container.", ";
		
	
?>
<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->CONTAINER) echo $row->CONTAINER; else echo "&nbsp;";?></td>
		<td><?php if($row->SIZ) echo $row->SIZ; else echo "&nbsp;";?></td>
		<td><?php if($row->SEAL_NBR1) echo $row->SEAL_NBR1; else echo "&nbsp;";?></td>
		<td><?php if($row->NAME) echo $row->NAME; else echo "&nbsp;";?></td>
		<td><?php if($row->IB_VYG) echo $row->IB_VYG; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<td><?php if($offdock) echo $offdock; else echo "&nbsp;";?></td>		
		<td><?php if($row->ENTERED_YARD) echo $row->ENTERED_YARD; else echo "&nbsp;";?></td>		
		<td><?php if($row->EXITED_YARD ) echo $row->EXITED_YARD; else echo "&nbsp;";?></td>		
		<td><?php if($row->GATENO) echo $row->GATENO; else echo "&nbsp;";?></td>		
		<td><?php if($row->CREATOR) echo $row->CREATOR; else echo "&nbsp;";?></td>		
	</tr>
<?php } } ?>
</table>
<br />
<br />
<?php if(@$_POST['options']=='html'){?>
<table border="1">
<tr>
	<?php echo $totCont; ?>									
</tr>

<table>
<?php } ?>
<?php 
mysqli_close($con_sparcsn4);
