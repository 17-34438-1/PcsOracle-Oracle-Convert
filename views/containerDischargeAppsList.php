<?php if($_POST['options']=='html'){?>
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
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=EXPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	
	

	
	
	$ddl_imp_rot_no_req=$_REQUEST['ddl_imp_rot_no']; 

	include("FrontEnd/dbConection.php");
	include("dbOracleConnection.php");
	

	$sql = "select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no_req'";
	$sql=oci_parse($con_sparcsn4_oracle, $sql);
	oci_execute($sql);
	
	$row=oci_fetch_object($sql);
	$vvdGkey=$row->vvd_gkey;
	
	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql);
	
	$sql1 = "select vsl_vessels.name as vsl_name,
			NVL(vsl_vessel_visit_details.flex_string02,NVL(vsl_vessel_visit_details.flex_string03,'')) as berth_op,
			NVL(argo_quay.id,'') as berth,argo_carrier_visit.ata,argo_carrier_visit.atd from vsl_vessel_visit_details
			inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
			inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
			inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
			where vsl_vessel_visit_details.vvd_gkey=$vvdGkey";
	$sql1=oci_parse($con_sparcsn4_oracle, $sql1);
	oci_execute($sql1);
	
$row1=oci_fetch_object($sql1);		

	
	?>
<html>
<title>Export Container Loading List</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				
				<tr align="center">
					<td colspan="12"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><u><?php echo $title;?></u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>

				<tr>
					<td colspan="3" align="center"><font size="4"><b> <?php  Echo $row1->vsl_name;?></b></font></td>
					<td colspan="3" align="center"><font size="4"><b>Voy: <?php  Echo $voysNo;?></b></font></td>
					<td colspan="3" align="center"><font size="4"><b>EXP ROT.: <?php  Echo $ddl_imp_rot_no;?></b></font></td>
					<td colspan="3" align="center"><font size="4"><b><?php  Echo $row1->ata;?></b></font></td>
					
				</tr>

			</table>
		
		</td>
		
	</tr>

	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr  align="center">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>ISO Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>Rotation</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
		<td style="border-width:3px;border-style: double;"><b>Status</b></td>		
		<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
		<td style="border-width:3px;border-style: double;"><b>POD</b></td>
		<td style="border-width:3px;border-style: double;"><b>Current Position</b></td>
		<td style="border-width:3px;border-style: double;"><b>Loaded Time</b></td>
		<td style="border-width:3px;border-style: double;"><b>Coming From</b></td>
		<td style="border-width:3px;border-style: double;"><b>Commodity</b></td>
		<td style="border-width:3px;border-style: double;"><b>Remarks</b></td>
		<td style="border-width:3px;border-style: double;"><b>User Id</b></td>
		<!--td style="border-width:3px;border-style: double;"><b>Action</b></td-->
		
	</tr>

<?php
$cond="";
if($search_by=="dateRange")
{
	$cond = " and date(mis_exp_unit.last_update) between '$fromdate' and '$todate' and mis_exp_unit.rotation = '$ddl_imp_rot_no'";
}
else if ($search_by=="dateTime")
{
	$frmDate = $fromdate." ".$fromTime.":00";
	$tDate = $todate." ".$toTime.":00";
	$cond = " and mis_exp_unit.last_update between '$frmDate' and '$tDate' and mis_exp_unit.rotation = '$ddl_imp_rot_no' ";
}
else if($search_by=="yard" and $ddl_imp_rot_no!="")
{
	if($fromdate=="" or $todate=="")
	{
		$cond = " and current_position = '$yard' and mis_exp_unit.rotation = '$ddl_imp_rot_no' and mis_exp_unit.vvd_gkey='$vvdGkey'";
	}
	else
	{
		$cond = " and date(mis_exp_unit.last_update) between '$fromdate' and '$todate' and current_position = '$yard' and mis_exp_unit.rotation = '$ddl_imp_rot_no' and mis_exp_unit.vvd_gkey='$vvdGkey'";
	}
}
else if($search_by=="yard" and $ddl_imp_rot_no=="")
{
	if($fromdate=="" or $todate=="")
	{
		$cond = " and current_position = '$yard'";
	}
	else
	{
		$cond = " and date(mis_exp_unit.last_update) between '$fromdate' and '$todate' and current_position = '$yard'";
	}
	
}
else if($search_by=="all")
{
	$cond = " and mis_exp_unit.rotation = '$ddl_imp_rot_no'";
}



$strQuery = "SELECT ctmsmis.mis_exp_unit.gkey,ctmsmis.mis_exp_unit.cont_id AS id,mis_exp_unit.isoType AS iso,
(CASE 
WHEN mis_exp_unit.cont_size= '20' AND mis_exp_unit.cont_height = '86' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '2D'
WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '4D'
WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.cont_height='96' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '4H'
WHEN mis_exp_unit.cont_size = '45' AND mis_exp_unit.cont_height='96' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '45H'
WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('RS','RT','RE') THEN '2RF'
WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.isoGroup IN ('RS','RT','RE') THEN '4RH'
WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('UT') THEN '2OT'
WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.isoGroup IN ('UT') THEN '4OT'
WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('PF','PC') THEN '2FR'
WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.isoGroup IN ('PF','PC') THEN '4FR'
WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('TN','TD','TG') THEN '2TK'
ELSE ''
END
) AS TYPE,
mis_exp_unit.rotation,
mis_exp_unit.cont_mlo AS mlo,ctmsmis.mis_exp_unit.current_position,
cont_status AS freight_kind,ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg AS weight,ctmsmis.mis_exp_unit.coming_from AS coming_from,
ctmsmis.mis_exp_unit.pod,ctmsmis.mis_exp_unit.stowage_pos,ctmsmis.mis_exp_unit.last_update,ctmsmis.mis_exp_unit.user_id
FROM ctmsmis.mis_exp_unit 
WHERE mis_exp_unit.delete_flag='0' AND mis_exp_unit.snx_type=2  ".$cond;
	
	//echo $strQuery;
	$query=mysqli_query($con_sparcsn4,$strQuery);

	$i=0;
	$j=0;
	
	$mlo="";
	$allCont = "";
	while($row=mysqli_fetch_object($query)){
	$i++;
	//$allCont = $allCont.$row->id.", ";
	$gkey="";
	$gkey=$row->gkey;
	$strQuery2="SELECT short_name FROM inv_unit
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
			INNER JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
			WHERE inv_unit_fcy_visit.transit_state='S20_INBOUND' AND inv_unit.gkey='$gkey'";

	$strQuery2Res=oci_parse($con_sparcsn4_oracle, $strQuery2);
	oci_execute($strQuery2Res);
	$results=array();
    $nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	oci_free_statement($strQuery2Res);

	$strQuery2Res=oci_parse($con_sparcsn4_oracle, $strQuery2);
	oci_execute($strQuery2Res);
	$rowShortName=oci_fetch_object($strQuery2Res);
	$short_name="";
	if($nrows>0){
		$short_name=$rowShortName->SHORT_NAME;
		$allCont = $allCont.$row->id.", ";
		
?>
<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->id) echo $row->id; else echo "&nbsp;";?></td>
		<td><?php if($row->iso) echo $row->iso; else echo "&nbsp;";?></td>
		<td><?php if($row->type) echo $row->type; else echo "&nbsp;";?></td>
		<td><?php if($row->rotation) echo $row->rotation; else echo "&nbsp;";?></td>
		<td><?php if($row->mlo) echo $row->mlo; else echo "&nbsp;";?></td>
		<td><?php if($row->freight_kind) echo $row->freight_kind; else echo "&nbsp;";?></td>
		<td><?php if($row->weight) echo $row->weight; else echo "&nbsp;";?></td>
		
		<td><?php if($row->pod) echo $row->pod; else echo "&nbsp;";?></td>
		<td><?php if($row->current_position) echo $row->current_position; else echo "&nbsp;";?></td>
		<td><?php if($row->last_update) echo $row->last_update; else echo "&nbsp;";?></td>
		
		<td><?php if($row->coming_from) echo $row->coming_from; else echo "&nbsp;";?></td>
		<td><?php if($rowShortName->SHORT_NAME) echo $short_name; else echo "&nbsp;";?></td><td><?php echo "&nbsp;";?></td>
		<td><?php if($row->user_id) echo $row->user_id;  else echo "&nbsp;";?></td>
		<!--td class="contact-delete">
			<?php if(strtoupper($this->session->userdata('login_id'))==strtoupper($row->user_id) OR strtoupper($this->session->userdata('login_id'))=='ADMIN'){?>
			<form action="<?php echo site_url('report/myContainerDelete') ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this case?');">
				<input type="hidden" name="gkey" value="<?php if($row->gkey) echo $row->gkey; ?>">
				<input type="hidden" name="ddl_imp_rot_no" value="<?php echo $ddl_imp_rot_no; ?>">
				<!--input type="hidden" name="voysNumber" value="<?php echo $voysNo; ?>">
				<input type="submit" name="submit" value="Delete">
			</form>
			<?php }?>
		</td-->	
		
				
	</tr>

<?php }
 } ?>
</table>
<br />
<br />
<?php




$sqlSummaryQuery="SELECT inv_unit.gkey
FROM  inv_unit 
INNER JOIN inv_unit_fcy_visit ON inv_unit.gkey = inv_unit_fcy_visit.unit_gkey
INNER JOIN argo_carrier_visit ON inv_unit_fcy_visit.actual_ob_cv = argo_carrier_visit.gkey
INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey 
WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND inv_unit_fcy_visit.transit_state='S20_INBOUND' 
";
$sqlSummaryQueryRes=oci_parse($con_sparcsn4_oracle, $sqlSummaryQuery);
oci_execute($sqlSummaryQueryRes);
$r=array();
$totalSummayRes = oci_fetch_all($sqlSummaryQueryRes, $r, null, null, OCI_FETCHSTATEMENT_BY_ROW);
oci_free_statement($sqlSummaryQueryRes);

$sqlSummaryQueryRes=oci_parse($con_sparcsn4_oracle, $sqlSummaryQuery);
oci_execute($sqlSummaryQueryRes);
$strGkey="";
$k=0;
$cond="";
if($totalSummayRes!=0){
	while(($sqlSummaryQueryResRow=oci_fetch_object($sqlSummaryQueryRes)) != false){
		$k++;
		$key=$sqlSummaryQueryResRow->GKEY;
		if($k==$totalSummayRes){
			$strGkey=	$strGkey. "'" .$key. "'";	
	
		}
		else {
			$strGkey=	$strGkey. "'" .$key. "'". ",";	
		}
		
	}
	$cond=" AND  mis_exp_unit.vvd_gkey IN ($strGkey) ";
	}
	else{
		$cond=" AND  mis_exp_unit.vvd_gkey IN ('') ";

}


/*while(($sqlSummaryQueryResRow=oci_fetch_object($sqlSummaryQueryRes)) != false){
	$k++;
    $key=$sqlSummaryQueryResRow->GKEY;
	if($k==$totalSummayRes){
		$strGkey=	$strGkey. "'" .$key. "'";	

	}
	else {
		$strGkey=	$strGkey. "'" .$key. "'". ",";	
    }
	
}*/


$sqlSummery=mysqli_query($con_sparcsn4,"SELECT gkey,
IFNULL(SUM(onboard_LD_20),0) AS onboard_LD_20,
IFNULL(SUM(onboard_LD_40),0) AS onboard_LD_40,
IFNULL(SUM(onboard_MT_20),0) AS onboard_MT_20,
IFNULL(SUM(onboard_MT_40),0) AS onboard_MT_40,
IFNULL(SUM(onboard_LD_tues),0) AS onboard_LD_tues,
IFNULL(SUM(onboard_MT_tues),0) AS onboard_MT_tues
 FROM (
SELECT DISTINCT ctmsmis.mis_exp_unit.gkey AS gkey,
(CASE WHEN mis_exp_unit.cont_size = '20' AND cont_status IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS onboard_LD_20, 
(CASE WHEN mis_exp_unit.cont_size > '20' AND cont_status IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS onboard_LD_40,
(CASE WHEN mis_exp_unit.cont_size = '20' AND cont_status ='MTY'  THEN 1  
ELSE NULL END) AS onboard_MT_20, 
(CASE WHEN mis_exp_unit.cont_size > '20' AND cont_status ='MTY'  THEN 1  
ELSE NULL END) AS onboard_MT_40, 
(CASE WHEN mis_exp_unit.cont_size=20 AND cont_status IN ('FCL','LCL') THEN 1 
ELSE (CASE WHEN mis_exp_unit.cont_size>20 AND cont_status IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS onboard_LD_tues, 
(CASE WHEN mis_exp_unit.cont_size=20 AND cont_status='MTY' THEN 1 
ELSE (CASE WHEN mis_exp_unit.cont_size>20 AND cont_status='MTY' THEN 2 ELSE NULL END) END) AS onboard_MT_tues

FROM ctmsmis.mis_exp_unit
WHERE mis_exp_unit.preAddStat='0' AND mis_exp_unit.snx_type=2  $cond
) AS tmp");
$rowSummery=mysqli_fetch_object($sqlSummery);


$sqlSummery2="select 
NVL(SUM(balance_LD_20),0) AS balance_LD_20,
NVL(SUM(balance_LD_40),0) AS balance_LD_40,
NVL(SUM(balance_MT_20),0) AS balance_MT_20,
NVL(SUM(balance_MT_40),0) AS balance_MT_40,
NVL(SUM(balance_LD_tues),0) AS balance_LD_tues,
NVL(SUM(balance_MT_tues),0) AS balance_MT_tues

 from (
select distinct inv_unit.gkey as gkey,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind in ('FCL','LCL')  THEN 1  
ELSE NULL END) AS balance_LD_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind in ('FCL','LCL')  THEN 1  
ELSE NULL END) AS balance_LD_40,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind ='MTY'  THEN 1  
ELSE NULL END) AS balance_MT_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind ='MTY'  THEN 1  
ELSE NULL END) AS balance_MT_40, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2)='20' AND freight_kind in ('FCL','LCL') THEN 1 
ELSE (CASE WHEN substr(ref_equip_type.nominal_length,-2)>'20' AND freight_kind in ('FCL','LCL') THEN 2 ELSE NULL END) END) AS balance_LD_tues, 
(CASE WHEN substr(ref_equip_type.nominal_length,2)='20' AND freight_kind='MTY' THEN 1 
ELSE (CASE WHEN substr(ref_equip_type.nominal_length,2)>'20' AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS balance_MT_tues

FROM inv_unit
inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
inner join argo_carrier_visit on argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
inner join ref_equipment on ref_equipment.gkey=inv_unit.eq_gkey
inner join ref_equip_type on ref_equip_type.gkey=ref_equipment.eqtyp_gkey
where argo_carrier_visit.cvcvd_gkey='$vvdGkey' and category='EXPRT' and mis_exp_unit.snx_type=2 and  transit_state not in ('S60_LOADED','S70_DEPARTED','S99_RETIRED')
)";




$sqlSummery2Res=oci_parse($con_sparcsn4_oracle, $sqlSummery2);
	oci_execute($sqlSummery2Res);
	$rowSummery2=oci_fetch_object($sqlSummery2Res);

?>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr><td colspan="11" align="center"><font size="4"><b><u>Summery Report</u></b></font></td></tr>
<tr><td colspan="11" align="center"><font size="4"><b>&nbsp;</b></font></td></tr>
</table>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan="6" align="center">TOTAL ONBOARD</td>
		<td colspan="6" align="center">BALANCE TO LOAD</td>
	</tr>
	<tr>
		<td colspan="2" align="center">LADEN</td>
		<td colspan="2" align="center">EMPTY</td>
		<td colspan="2" align="center">TUES</td>
		<td colspan="2" align="center">LADEN</td>
		<td colspan="2" align="center">EMPTY</td>
		<td colspan="2" align="center">TUES</td>
		
	</tr>
	<tr>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">LD</td>
		<td align="center">MT</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">LD</td>
		<td align="center">MT</td>
	</tr>
	<tr>
	
		<td align="center"><?php if($rowSummery->onboard_LD_20) echo $rowSummery->onboard_LD_20; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($rowSummery->onboard_LD_40) echo $rowSummery->onboard_LD_40; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($rowSummery->onboard_MT_20) echo $rowSummery->onboard_MT_20; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($rowSummery->onboard_MT_40) echo $rowSummery->onboard_MT_40; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($rowSummery->onboard_LD_tues) echo $rowSummery->onboard_LD_tues; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($rowSummery->onboard_MT_tues) echo $rowSummery->onboard_MT_tues; else echo "&nbsp;"; ?></td>
	
		<td align="center"><?php if($rowSummery2->BALANCE_LD_20 ) echo $rowSummery2->BALANCE_LD_20; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($rowSummery2->BALANCE_LD_40 ) echo $rowSummery2->BALANCE_LD_40; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($rowSummery2->BALANCE_MT_20) echo $rowSummery2->BALANCE_MT_20; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($rowSummery2->BALANCE_MT_40) echo $rowSummery2->BALANCE_MT_40; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($rowSummery2->BALANCE_LD_tues) echo $rowSummery2->BALANCE_LD_tues; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($rowSummery2->BALANCE_MT_tues) echo $rowSummery2->BALANCE_MT_tues; else echo "&nbsp;"; ?></td>
		
	</tr>
	<tr>
	<td colspan="12" align="center">
		<table>
			<tr>
				<b><u>Container's</u></b></br>
			</tr>
				<?php 
			
				?>
			<tr>
				<?php echo $allCont; ?>									
			</tr>
				<?php //}?>
			</tbody>
		</table>
	</td>
</tr>
</table>
<?php 
//mysql_close($con_sparcsn4);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
