<?php if($_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Planned Report</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php } else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=PlannedReport.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}



?>
<style>
   table {border-collapse:collapse; table-layout:fixed; width:700px;font-size: 80%;}
   table td,th {border:solid 1px #000; width:300px; word-wrap:break-word;}
   img {padding-left:300px;}
</style>
<img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"/>

<?php 
$cond = "";
$forTitle="";

//echo $srcFor;
if($srcFor=="Date")
{
			$cond = "cast(argo_carrier_visit.ata as date) between to_date('$fromdate','yyyy-mm-dd') and to_date('$todate','yyyy-mm-dd') and NVL(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03)!='SAIF POWERTEC' order by argo_carrier_visit.ata desc";
			$forTitle="From $fromdate To $todate";
}
else
{
			$cond = "vsl_vessel_visit_details.ib_vyg='$srcRot'";
			$forTitle="Rotation No $srcRot";
}
?>
<!--<h3 align="center">Vessel Wise CTMS Container Job Done by HHT,VMT and Manually<br/>From  <?php echo $fromdate?> To <?php echo $todate?></h3>-->
<h3 align="center">Vessel Wise CTMS Container Job Done by HHT,VMT and Manually<br/><?php echo $forTitle?></h3>
<table align="center" cellpadding="2">
	<tr>
		<th style="width:25px;" rowspan="3">Sl no</th><th style="width:140px;" rowspan="3">Vessel Name</th><th style="width:65px;" rowspan="3">Arrival Date</th><th style="width:65px;" rowspan="3">Rotation</th><th style="width:60px;" rowspan="3">Berth Operator</th><th rowspan="3" style="width:50px;">Total Import</th><th colspan="5">With HHT</th><th colspan="5">Without HHT</th><th style="width:40px;" rowspan="3">Total VMT</th><th style="width:55px;" rowspan="3">Total Manually</th>
	</tr>
	<tr>
		<th colspan="2">With VMT</th><th colspan="2">Manually</th><th rowspan="2">Total</th><th colspan="2">With VMT</th><th colspan="2">Manually</th><th rowspan="2">Total</th>
	</tr>
	<tr>
		<th>Planned</th><th>Plan Changed</th><th>Planned</th><th>Plan Changed</th><th>Planned</th><th>Plan Changed</th><th>Planned</th><th>Plan Changed</th>
	</tr>
	<?php
	include("FrontEnd/dbConection.php");

	include("FrontEnd/mydbPConnection.php");
	include("dbOracleConnection.php");	

	
	$str = "select argo_carrier_visit.id,
	to_char(argo_carrier_visit.ata,'yyyy-mm-dd') as ata,vsl_vessels.name,
	vsl_vessel_visit_details.ib_vyg,
	NVL(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03) as berthop 
	from argo_carrier_visit
	inner join vsl_vessel_visit_details on vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
	inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	where ".$cond."";
	$res = oci_parse($con_sparcsn4_oracle,$str);
	oci_execute($res);
	$i=0;
	while(($row=oci_fetch_object($res)) != false)
	{
		$i++;
		$strVsl = "select argo_carrier_visit.id,srv_event.gkey,event_type_gkey,che_fetch,che_put,placed_by,applied_to_gkey,to_pos_slot 
		from srv_event 
		inner join inv_move_event on inv_move_event.mve_gkey=srv_event.gkey
		inner join argo_carrier_visit on argo_carrier_visit.gkey=inv_move_event.carrier_gkey
		where argo_carrier_visit.id='$row->ID' and event_type_gkey=18";
		$resVsl = oci_parse($con_sparcsn4_oracle,$strVsl);	
		oci_execute($resVsl);
		$bertOpPart = explode(" ",$row->BERTHOP);
		$tot=0;
		$hht_vmt_plan = 0;
		$hht_vmt_plan_chng = 0;
		$hht_man_plan = 0;
		$hht_man_plan_chng = 0;
		$oth_vmt_plan = 0;
		$oth_vmt_plan_chng = 0;
		$oth_man_plan = 0;
		$oth_man_plan_chng = 0;
		while($rowVsl=oci_fetch_object($resVsl))
		{
			$tot=$tot+1;
			$che_fetch=$rowVsl->CHE_FETCH;
			$placed_by=$rowVsl->PLACED_BY;
			$cont=$rowVsl->APPLIED_TO_GKEY;
			$to_pos_slot=$rowVsl->TO_POS_SLOT;
			$vmtPart = explode(":",$placed_by);
			$strPlan = "select pos_slot as planpos1 from ctmsmis.mis_inv_equip_planned where unit_gkey=$cont";
			$resPlan = mysqli_query($con_sparcsn4,$strPlan);
			$plan = null;
			while($rowPlan=mysqli_fetch_object($resPlan))
			{
				$plan = $rowPlan->planpos1;
			}
			
			if($che_fetch!=null)
			{
				if(end($vmtPart)=="COMPLETE_MOVE")
				{
					if (strpos($to_pos_slot, $plan) !== false)
						$hht_man_plan = $hht_man_plan+1;
					else
						$hht_man_plan_chng = $hht_man_plan_chng+1;
				}
				else
				{
					if (strpos($to_pos_slot, $plan) !== false)
						$hht_vmt_plan = $hht_vmt_plan+1;
					else
						$hht_vmt_plan_chng = $hht_vmt_plan_chng+1;
				}
			}
			else
			{
				if(end($vmtPart)=="COMPLETE_MOVE")
				{
					if (strpos($to_pos_slot, $plan) !== false)
						$oth_man_plan = $oth_man_plan+1;
					else
						$oth_man_plan_chng = $oth_man_plan_chng+1;
				}
				else
				{
					if (strpos($to_pos_slot, $plan) !== false)
						$oth_vmt_plan = $oth_vmt_plan+1;
					else
						$oth_man_plan_chng = $oth_man_plan_chng+1;
				}
			}	
			
		}
	?>
		<tr align="center">
			<td><?php echo $i;?></td>
			<td><?php echo $row->NAME;?></td>
			<td><?php echo $row->ATA;?></td>
			<td><?php echo $row->IB_VYG;?></td>
			<td><?php echo $bertOpPart[0];?></td>
			<td><?php echo $tot;?></td>
			<td><?php echo $hht_vmt_plan;?></td>
			<td><?php echo $hht_vmt_plan_chng;?></td>
			<td><?php echo $hht_man_plan;?></td>
			<td><?php echo $hht_man_plan_chng;?></td>
			<td><?php echo $hht_vmt_plan+$hht_vmt_plan_chng+$hht_man_plan+$hht_man_plan_chng;?></td>
			<td><?php echo $oth_vmt_plan;?></td>
			<td><?php echo $oth_vmt_plan_chng;?></td>
			<td><?php echo $oth_man_plan;?></td>
			<td><?php echo $oth_man_plan_chng;?></td>
			<td><?php echo $oth_vmt_plan+$oth_vmt_plan_chng+$oth_man_plan+$oth_man_plan_chng;?></td>
			<td><?php echo $hht_vmt_plan+$hht_vmt_plan_chng+$oth_vmt_plan+$oth_vmt_plan_chng;?></td>
			<td><?php echo $hht_man_plan+$hht_man_plan_chng+$oth_man_plan+$oth_man_plan_chng;?></td>
			
		</tr>
	<?php
	}
	//echo"<hr> End "
	?>
</table>

<?php 

//mysql_close($con_sparcsn4);
//mysql_close($con_cchaportdb);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
