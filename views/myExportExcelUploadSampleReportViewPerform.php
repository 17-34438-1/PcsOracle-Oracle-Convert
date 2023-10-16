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




	$cond="";

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
    <title>Export Container Loading List</title>

    <body>
        <table width="100%" border='0' cellpadding='0' cellspacing='0'>
            <tr bgcolor="#ffffff" align="center" height="100px">
                <td colspan="13" align="center">
                    <table border=0 width="100%">
                        <tr>
                            <td align="right"><b>Vessel:</b></td>
                            <td align="left">
                                <font size="4"><b><?php  Echo $vsl_name;?></b></font>
                            </td>
                            <td align="right">
                                <font size="4"><b>Voy:</b></font>
                            </td>
                            <td align="left">
                                <font size="4"><b><?php  Echo $voysNo;?></b></font>
                            </td>
                            <td align="right">
                                <font size="4"><b>EXP ROT.:</b></font>
                            </td>
                            <td align="left">
                                <font size="4"><b><?php Echo $ddl_imp_rot_no;?></b></font>
                            </td>
                            <td colspan="3" align="right">
                                <font size="4"><b>Arrival Date:</b></font>
                            </td>
                            <td colspan="3" align="left">
                                <font size="4"><b><?php  Echo $ata;?></b></font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border='1' cellpadding='0' cellspacing='0'>
            <tr align="center">
                <td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
                <td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
                <td style="border-width:3px;border-style: double;"><b>ISO Type</b></td>
                <td style="border-width:3px;border-style: double;"><b>Size</b></td>
                <td style="border-width:3px;border-style: double;"><b>Height</b></td>
                <td style="border-width:3px;border-style: double;"><b>MLO</b></td>
                <td style="border-width:3px;border-style: double;"><b>Status</b></td>
                <td style="border-width:3px;border-style: double;"><b>Weight</b></td>
                <td style="border-width:3px;border-style: double;"><b>POD</b></td>
                <td style="border-width:3px;border-style: double;"><b>Stowage</b></td>
                <td style="border-width:3px;border-style: double;"><b>Loaded Time</b></td>
                <td style="border-width:3px;border-style: double;"><b>Seal No</b></td>
                <td style="border-width:3px;border-style: double;"><b>Coming From</b></td>
                <td style="border-width:3px;border-style: double;"><b>Truck No</b></td>
                <td style="border-width:3px;border-style: double;"><b>Craine Id</b></td>
                <td style="border-width:3px;border-style: double;"><b>Commodity</b></td>
                <td style="border-width:3px;border-style: double;"><b>Shift</b></td>
                <td style="border-width:3px;border-style: double;"><b>Date</b></td>
                <td style="border-width:3px;border-style: double;"><b>User Id</b></td>
            </tr>
            <?php
	$cond="";


	// $strQuery = "SELECT ctmsmis.mis_exp_unit.gkey,CONCAT(SUBSTRING(ctmsmis.mis_exp_unit.cont_id,1,4),SUBSTRING(ctmsmis.mis_exp_unit.cont_id,5)) AS id, 
	// mis_exp_unit.cont_mlo AS mlo,ctmsmis.mis_exp_unit.craine_id,ctmsmis.mis_exp_unit.seal_no,cont_status AS freight_kind,
	// ctmsmis.mis_exp_unit.coming_from AS coming_from,ctmsmis.mis_exp_unit.pod,ctmsmis.mis_exp_unit.stowage_pos, 
	// ctmsmis.mis_exp_unit.user_id,ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg AS goods_and_ctr_wt_kg,ctmsmis.mis_exp_unit.truck_no
	// FROM ctmsmis.mis_exp_unit 
  	// WHERE mis_exp_unit.vvd_gkey='$vvdGkey' AND mis_exp_unit.preAddStat='0' AND snx_type=0 AND mis_exp_unit.delete_flag='0'
  	// ".$cond;
	$strQuery = " SELECT inv_unit.gkey,inv_unit.id,inv_unit.projected_pod_gkey,inv_unit_fcy_visit.transit_state,SUBSTR(ref_equip_type.nominal_length,-2, LENGTH( ref_equip_type.nominal_length)) AS cont_size, SUBSTR(ref_equip_type.nominal_height, -2, LENGTH( ref_equip_type.nominal_height)) AS height,
    ref_bizunit_scoped.name AS mlo_name,inv_unit.seal_nbr1 AS seal_no,vsl_vessel_visit_details.vvd_gkey,
    ref_equip_type.id AS iso,ref_bizunit_scoped.id AS mlo,inv_unit.freight_kind,inv_unit.goods_and_ctr_wt_kg,
    ref_commodity.short_name AS commodity,inv_unit.remark as remarks, inv_unit_fcy_visit.ARRIVE_POS_SLOT as stowage_pos, inv_unit_fcy_visit.LAST_POS_NAME,
    argo_carrier_visit.id AS truck_no,(select srv_event.placed_time from srv_event where srv_event.applied_to_gkey=inv_unit.gkey and event_type_gkey IN(31488)
    ORDER BY srv_event.gkey DESC fetch first 1 rows only) as last_update,
    REF_ROUTING_POINT.ID as pod, inv_unit_fcy_visit.LAST_POS_LOCTYPE AS coming_from
    
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
    WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
	//echo $strQuery;

    $strQuery2Res=oci_parse($con_sparcsn4_oracle, $strQuery);
	oci_execute($strQuery2Res);
    $i=0;
	$j=0;
	
	$mlo="";
	while(($row=oci_fetch_object($strQuery2Res)) !=false){

    $i++;

	// $query=mysqli_query($con_sparcsn4,$strQuery);

	// $i=0;
	// $j=0;
	
	// $mlo="";
	// $vvd_gkey="";
	// while($row=mysqli_fetch_object($query)){

	// $vvd_gkey=$row->gkey;

	// $strQuery2=" 
	// SELECT inv_unit.gkey,SUBSTR(ref_equip_type.nominal_length,-2, LENGTH( ref_equip_type.nominal_length)) AS cont_size, SUBSTR(ref_equip_type.nominal_height, -2, LENGTH( ref_equip_type.nominal_height)) AS height,
	// ref_bizunit_scoped.name AS mlo_name
	// FROM inv_unit
	// INNER JOIN ref_bizunit_scoped  ON inv_unit.line_op = ref_bizunit_scoped.gkey 
	// LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods 
	// LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey 
	// INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
	// INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	// INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
	// WHERE inv_unit.gkey='$vvd_gkey'";


 	// $strQuery2Res=oci_parse($con_sparcsn4_oracle, $strQuery2);
	// oci_execute($strQuery2Res);
	// $size="";
    // $height="";
	// while(($row2=oci_fetch_object($strQuery2Res)) !=false)
	// {
	// 	$size = $row2->CONT_SIZ;
	// 	$height=$row2->HEIGHT;
	
	// }


	


	?>
            <tr align="center">
                <td><?php  	echo $i;?></td>
                <td><?php 	if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
                <td><?php 	if($row->ISOTYPE) echo $row->ISOTYPE; else echo "&nbsp;";?></td>
                <td><?php if($row->CONT_SIZE) echo $row->CONT_SIZE; else echo "&nbsp;";	?></td>
                <td><?php if($row->HEIGHT) echo $row->HEIGHT; else echo "&nbsp;";	 echo $height; ?></td>
                <td><?php 	if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
                <td><?php 	if($row->FREIGHT_KIND) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
                <td><?php  if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
                <td><?php 	if($row->POD) echo $row->POD; else echo "&nbsp;";?></td>
                <td><?php 	if($row->STOWAGE_POS) echo $row->STOWAGE_POS; else echo "&nbsp;"; ?>
                </td>
                <td><?php	if($row->LAST_UPDATE) echo $row->LAST_UPDATE; else echo "&nbsp;";?></td>
                <td><?php 	if($row->SEAL_NO) echo $row->SEAL_NO; else echo "&nbsp;";?></td>
                <td><?php 	if($row->COMING_FROM) echo $row->COMING_FROM; else echo "&nbsp;";?></td>
                <td><?php 	 echo "&nbsp;";?></td>
                <td><?php 	echo "&nbsp;";?></td>
                <td><?php 	 echo "&nbsp;";?></td>
                <td width="80px">&nbsp;</td>
                <td width="80px">&nbsp;</td>
                <td><?php  echo "&nbsp;";?></td>
            </tr>
            <?php } ?>
        </table>
        <br />

        <?php 
if($_POST['options']=='html'){?>
    </BODY>

    </HTML>
    <?php }?>