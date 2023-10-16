
<?php 
//$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	include("FrontEnd/dbConection.php");
	include("dbOracleConnection.php");





	$sql="select inv_unit.gkey,vsl_vessel_visit_details.ib_vyg from inv_unit
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv 
INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
where vsl_vessel_visit_details.ib_vyg='$ddl_imp_rot_no'";
	$sqlVvvGkeyRes=oci_parse($con_sparcsn4_oracle,$sql);
	oci_execute($sqlVvvGkeyRes);

	$vvdGkey = "";
	$cond = "";

	while(($row = oci_fetch_object($sqlVvvGkeyRes)) != false)
	{
		$vvdGkey = $row->GKEY;
	}


	
	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql);
	//$res=mysqli_query($sql);
	
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
<title>Export Container Loading List</title>
<body>
<table width="70%" border ='0' cellpadding='0' cellspacing='0' align="center">
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				<tr align="center">
					<td colspan="13"><img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH;?>cpanew.jpg"></td>
				</tr>
				<tr align="center">
					<td colspan="13"><font size="4"><b><u>Export Upload Report</u></b></font></td>
				</tr>
				<tr>
					<td align="right"><b>Vessel:</b></td>
					<td align="left"><font size="3"><b><?php  Echo @$vsl_name;?></b></font></td>
					<td align="right"><font size="3"><b>Voy:</b></font></td>
					<td align="left"><font size="3"><b><?php  Echo $voysNo;?></b></font></td>
					<td align="right"><font size="3"><b>EXP ROT.:</b></font></td>
					<td align="left"><font size="3"><b><?php  Echo $ddl_imp_rot_no;?></b></font></td>
					<td colspan="3" align="right"><font size="4"><b>Arrival Date:</b></font></td>
					<td colspan="3" align="left"><font size="4"><b><?php  Echo @$ata;?></b></font></td>
				</tr>
			</table>
		</td>
	</tr>
	

	</table>
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
	<tr  align="center" class="gridDark">
		<td ><b>SlNo.</b></td>
		<td ><b>Container No.</b></td>
		<td ><b>ISO Type</b></td>
		<td ><b>Size</b></td>
		<td ><b>Height</b></td>
		<td ><b>MLO</b></td>
		<td ><b>Status</b></td>		
		<td ><b>Weight</b></td>
		<td ><b>POD</b></td>
		<td ><b>Stowage</b></td>
		<td ><b>Loaded Time</b></td>
		<td ><b>Seal No</b></td>
		<td ><b>Coming From</b></td>
		<td ><b>Truck No</b></td>
		<td ><b>Craine Id</b></td>
		<td ><b>Commodity</b></td>
		<td ><b>User Id</b></td>
		
	</tr>

<?php




    
	// $strQuery = " SELECT ctmsmis.mis_exp_unit.gkey,CONCAT(SUBSTRING(ctmsmis.mis_exp_unit.cont_id,1,4),SUBSTRING(ctmsmis.mis_exp_unit.cont_id,5)) AS id, last_update,isoType,
	// mis_exp_unit.cont_mlo,ctmsmis.mis_exp_unit.craine_id,ctmsmis.mis_exp_unit.seal_no,cont_status AS freight_kind,
	// ctmsmis.mis_exp_unit.coming_from AS coming_from,ctmsmis.mis_exp_unit.pod,ctmsmis.mis_exp_unit.stowage_pos, 
	// ctmsmis.mis_exp_unit.user_id,ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg AS weight,ctmsmis.mis_exp_unit.truck_no
	// FROM ctmsmis.mis_exp_unit 
  	// WHERE mis_exp_unit.vvd_gkey='$vvdGkey' AND mis_exp_unit.preAddStat='0' AND snx_type=0 AND mis_exp_unit.delete_flag='0'
  	// ".$cond;
	//echo $strQuery;
	 $strQuery = " 
	SELECT inv_unit.gkey,inv_unit.id AS cont_id,inv_unit.projected_pod_gkey,inv_unit_fcy_visit.transit_state,SUBSTR(ref_equip_type.nominal_length,-2, LENGTH( ref_equip_type.nominal_length)) AS siz, SUBSTR(ref_equip_type.nominal_height, -2, LENGTH( ref_equip_type.nominal_height)) AS height,
	ref_bizunit_scoped.name AS cont_mlo,inv_unit.seal_nbr1 AS sealno,
	ref_equip_type.id AS iso,ref_bizunit_scoped.id AS mlo,inv_unit.freight_kind,inv_unit.goods_and_ctr_wt_kg AS weight,
	ref_commodity.short_name AS commodity,inv_unit.remark, inv_unit_fcy_visit.ARRIVE_POS_SLOT as stowage_pos, inv_unit_fcy_visit.LAST_POS_NAME,
	argo_carrier_visit.id AS truck,
	 REF_ROUTING_POINT.ID as pod, inv_unit_fcy_visit.LAST_POS_LOCTYPE AS coming_frm
	
	FROM inv_unit
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv 
	INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey 
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
	INNER JOIN ref_bizunit_scoped  ON inv_unit.line_op = ref_bizunit_scoped.gkey 
	INNER JOIN REF_ROUTING_POINT ON INV_UNIT.POD1_GKEY = REF_ROUTING_POINT.GKEY
	
	LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods 
	LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey 
	INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
	WHERE inv_unit.gkey='$vvdGkey'";



	$query=oci_parse($con_sparcsn4_oracle, $strQuery);
	oci_execute($query);

	$i=0;
	$j=0;
	$k=0;
	
	$vvd_gkey="";
	while(($row=oci_fetch_object($query)) !=false){ 
	$i++;

?>
<?php
	if($row->MLO!=$row->CONT_MLO){
		if($k>0){
		?>
		<tr bgcolor="#aaffff" valign="center"><td colspan="3"><font size="4"><b>&nbsp;&nbsp;Total Container (<?php echo ""; ?>):</b></font></td><td  colspan="17">&nbsp;&nbsp;<font size="4"><b><?php  echo $k;?></b></font></td></tr>
		<?php
		}
		?>
		<tr bgcolor="#F0F4CA" valign="center"><td  colspan="17">&nbsp;&nbsp;<font size="4"><b><?php  if($row->CONT_MLO) echo "(".$row->CONT_MLO.") "; else echo "&nbsp;"; ?></b></font></td></tr>
		
		<?php
		
		
		$k=1;
		
	}else{
		$k++;
		
	}
	//$yard=$row->current_position;
	$mlo=$row->CONT_MLO;
	
	
?>
	<tr align="center">
		<td><?php  	echo $k;?></td>
		<td><?php 	if($row->CONT_ID) echo $row->CONT_ID; else echo "&nbsp;";?></td>
		<td><?php 	 echo "&nbsp;";?></td>
		<td><?php 	echo $row->SIZ;?></td>
          <td><?php echo $row->HEIGHT; ?></td>
		<td><?php 	if($row->CONT_MLO) echo $row->CONT_MLO; else echo "&nbsp;";?></td>
		<td><?php 	if($row->FREIGHT_KIND) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
		<td><?php 	if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
		<td><?php 	if($row->POD) echo $row->POD; else echo "&nbsp;";?></td>
			<td><?php  echo "&nbsp;";?></td>
		<td><?php 	 echo "&nbsp;";?></td>
		<td><?php 	 echo "&nbsp;";?></td>
		<td><?php 	echo "&nbsp;";?></td>
		<td><?php 	echo "&nbsp;";?></td>
		<td><?php  echo "&nbsp;";?></td>
		<td width="80px">&nbsp;</td>
		<td><?php  echo "&nbsp;";?></td>
	</tr>

<?php } ?>
</table>
<br />

<?php 
//mysql_close($con_sparcsn4);
//if($_POST['options']=='html'){
?>	
	</BODY>
</HTML>
<?php oci_close($con_sparcsn4_oracle); ?>
<?php //}?>
