<?php if($_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Bearth Operator Report</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">

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
	$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 
	include("dbConection.php");
	include("dbOracleConnection.php");





$sql="select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'";

$sqlVvvGkeyRes=oci_parse($con_sparcsn4_oracle,$sql);
oci_execute($sqlVvvGkeyRes);

$vvdGkey = "";
$cond = "";

while(($row = oci_fetch_object($sqlVvvGkeyRes)) != false)
{
	$vvdGkey = $row->VVD_GKEY;
}

$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
$res=mysqli_query($con_sparcsn4,$sql);

$sql1="SELECT vsl_vessels.name,COALESCE(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03) AS berthop,COALESCE(argo_quay.id,'') AS berth,
argo_carrier_visit.ata,argo_carrier_visit.atd FROM vsl_vessel_visit_details
INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
INNER JOIN argo_quay ON argo_quay.gkey=vsl_vessel_berthings.quay
WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";

$resSql1 = oci_parse($con_sparcsn4_oracle, $sql1);
oci_execute($resSql1);
$vsl_name = "";
$ata = "";
while(($row1=oci_fetch_object($resSql1)) !=false)
{
	$vsl_name = $row1->NAME;
	$ata = $row1->ATA ;
}

	
	?>
<html>
<title>Export Container Block List</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
		
				<?php
				if($_POST['options']=='html')
				{
				?>
					<tr>
						<td colspan="12" align="center"><img width="250px" height="80px" src="<?php echo ASSETS_WEB_PATH?>fimg/cpanew.jpg"></td>
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
					<td colspan="12"><font size="4"><b><u>Export Container Block Report</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>
				<tr>
					<td colspan="3" align="center"><font size="4"> <b> <?php  Echo $vsl_name;?></b></font></td>
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
	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr  align="center">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
		<td style="border-width:3px;border-style: double;"><b>Status</b></td>		
		<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
		<td style="border-width:3px;border-style: double;"><b>POD</b></td>
		<td style="border-width:3px;border-style: double;"><b>Stowage</b></td>
		<td style="border-width:3px;border-style: double;"><b>Coming From</b></td>
		<td style="border-width:3px;border-style: double;"><b>Commodity</b></td>
		<td style="border-width:3px;border-style: double;"><b>Remarks</b></td>
		<td style="border-width:3px;border-style: double;"><b>User Id</b></td>
		
	</tr>

<?php
	
	


	$query="




	SELECT inv_unit.id,ref_bizunit_scoped.id AS mlo,ref_bizunit_scoped.name AS mlo_name,inv_unit.id AS contNo,ref_equip_type.id AS iso,
	inv_unit.freight_kind AS contStatus,inv_unit.goods_and_ctr_wt_kg AS weight,inv_unit.remark AS remarks,ref_commodity.short_name AS commodity
	FROM vsl_vessel_visit_details 
	LEFT JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	LEFT JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ob_cv = argo_carrier_visit.gkey
	LEFT JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	INNER JOIN inv_unit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	LEFT JOIN ref_bizunit_scoped  ON inv_unit.line_op = ref_bizunit_scoped.gkey 
	LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
	LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
	INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'
	

	
	";

	
	// WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'




	$stid = oci_parse($con_sparcsn4_oracle, $query);
	oci_execute($stid);
	

	$i=0;
	$j=0;
	
	$mlo="";
	$cont_id="";
	while (($row = oci_fetch_object($stid)) != false){

		$cont_id=$stid[$i]['ID'];


		$query="
		
		SELECT ctmsmis.mis_exp_unit.cont_id,ctmsmis.mis_exp_unit.pod,ctmsmis.mis_exp_unit.stowage_pos,ctmsmis.mis_exp_unit.user_id,ctmsmis.mis_exp_unit.coming_from
		FROM ctmsmis.mis_exp_unit 
		WHERE ctmsmis.mis_exp_unit.cont_id='$cont_id'
				
		";

	$i++;
	// $row=mysqli_query($con_sparcsn4,$query);

		
	
?>
<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->CONTNO) echo $row->CONTNO; else echo "&nbsp;";?></td>
		<td><?php if($row->ISO) echo $row->ISO; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<td><?php if($row->contStatus) echo $row->contStatus; else echo "&nbsp;";?></td>
		<td><?php if($row->weight) echo $row->weight; else echo "&nbsp;";?></td>
		<td><?php if($row->pod) echo $row->pod; else echo "&nbsp;";?></td>
		<td><?php if($row->stowage_pos) echo $row->stowage_pos; else echo "&nbsp;";?></td>
		<td><?php if($row->coming_from) echo $row->coming_from; else echo "&nbsp;";?></td>
		<td><?php if($row->commodity) echo $row->commodity; else echo "&nbsp;";?></td>
		<td><?php if($row->remarks) echo $row->remarks; else echo "&nbsp;";?></td>
		<td><?php if($row->user_id) echo $row->user_id; else echo "&nbsp;";?></td>
				
	</tr>

<?php }




?>
</table>

<?php 
//mysql_close($con_sparcsn4);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
