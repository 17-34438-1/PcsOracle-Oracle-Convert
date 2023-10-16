<?php if(@$_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Bearth Operator</TITLE>
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
		header("Content-Disposition: attachment; filename=Reefer_Container.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	//$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	//$con=mysql_connect("10.1.1.21", "sparcsn4","sparcsn4")or die("sparcsn4 database cannot connect"); 
	//mysql_select_db("sparcsn4")or die("cannot select DB");
	
	include("dbConection.php");
	include("dbOracleConnection.php");	
	//include("mydbPConnectionctmsmis.php");
	
	
	
	//REFER DATA UPDATE TO GIVEN DATE FROM N4 to CTMSMIS REEFERDATA TABLE.... MODIFIED BY ASIF ON 22/2/21 START 
	$maxUnitGkeyStr="SELECT MAX(unit_gkey) as max_unit_gkey FROM ctmsmis.reeferdata";
	$maxUnitGkeyQuery=mysqli_query($con_sparcsn4,$maxUnitGkeyStr);
	$maxUnitGkeyRes=mysqli_fetch_object($maxUnitGkeyQuery);
	$maxUnitGkey="";
	$maxUnitGkey=$maxUnitGkeyRes->unit_max_unit_gkey;


	$sqlQueryNew="SELECT gkey,id
	FROM inv_unit 
	WHERE  inv_unit.requires_power='1'
	AND inv_unit.gkey>'$maxUnitGkey'";
 //conn2 for n4 db and   conn for ctmsmisdb
	$resNumRowStm = oci_parse($con_sparcsn4_oracle,$sqlQueryNew);
	oci_execute($resNumRowStm);
	$result1=array();
	$numRows =oci_fetch_all($resNumRowStm, $result1, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	oci_free_statement($resNumRowStm);
	$resultNew=oci_parse($con_sparcsn4_oracle,$sqlQueryNew);
	oci_execute($resultNew);

	$gkey="";
	if($numRows>0){
		while(($row2=oci_fetch_object($resultNew))!= false){
			$gkey="";
			$id ="";
			$gkey=$row2->GKEY;
			$id = $row2->ID;
			$sql = "REPLACE INTO ctmsmis.reeferdata(unit_gkey,cont_id) values('$gkey','$id')";
			mysqli_query($con_sparcsn4, $sql);
			
		}
	}
	
	if($type=="con")
	{
		$col = "to_date(rfr_conn BETWEEN concat('$fromdate',' 00:00:00'),''yyyy-mm-dd hh24:mi,ss'') 
		AND to_date(concat('$todate',' 23:59:59'),'yyyy-mm-dd hh24:mi,ss') ";
		$head = "Power Connection Date Wise Import Reefer Container List";
	}
	
	elseif($type=="assign")
	{
		$col = "assignment_dt BETWEEN to_date(concat('$fromdate',' 00:00:00'),'yyyy-mm-dd hh24:mi,ss') 
		AND to_date(concat('$todate',' 23:59:59'),'yyyy-mm-dd hh24:mi,ss') ";
		$head = "Assignment Date Wise Import Reefer Container List";
	}
	else
	{
		$col = "discharge_time BETWEEN to_date(concat('$fromdate',' 00:00:00'),'yyyy-mm-dd hh24:mi,ss') 
		AND to_date(concat('$todate',' 23:59:59'),'yyyy-mm-dd hh24:mi,ss') ";
		//$col = "";
		$head = "Discharge Date Wise Import Reefer Container List";
		
		
	
	}
	
	if($yard_no=='All')
	{
		$yrd="";
	}
	else
	{		
		$yrd="and yard='$yard_no'";
	}
	?>
<html>
<!--title>Import Reffer Container Discharge List</title-->
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				<tr align="center">
					<td align="center" valign="middle" colspan="12" align="center"><img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>				
				<tr align="center">
					<td align="center" valign="middle" colspan="12"><font size="4"><b> Container Terminal Operator</b></font></td>
				</tr>
				
				<tr align="center">
					<td align="center" valign="middle" colspan="12"><font size="4"><b><u><?php echo $head;?></u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>

			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
		<thead>
			<tr  align="center">
				<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Size.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Height.</b></td>
				<td style="border-width:3px;border-style: double;"><b>MLO.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Vessel Name.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Rotation.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Discharge Time.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Yard Name.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Block Name.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Position.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Power Request Time.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Power Connect.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Power Disconnect.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Delivery Type.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Assignment Date.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Delivery Date.</b></td>
			</tr>
		</thead>

<?php

/*
//REFER DATA UPDATE TO GIVEN DATE FROM N4 to CTMSMIS REEFERDATA TABLE.... MODIFIED BY ASIF ON 22/2/21 START 
$sqlQuery="SELECT gkey FROM sparcsn4.inv_unit WHERE  sparcsn4.inv_unit.requires_power=1 AND  sparcsn4.inv_unit.create_time BETWEEN ('$fromdate' - interval 5 day) AND '$todate'";
//echo $sqlQuery;
$result=mysqli_query($con_sparcsn4,$sqlQuery);  //conn2 for n4 db and   conn for ctmsmisdb

 
	if($result || mysqli_num_rows($result) > 0){
		while($row2=mysqli_fetch_array($result)){
			$gkey=$row2['gkey'];
								
			$sqlQuery2="SELECT inv.gkey AS unit_gkey ,inv.id AS cont_id,
			sparcsn4.vsl_vessel_visit_details.ib_vyg AS vessel_visit,
			inv_unit_fcy_visit.flex_date03 AS rfr_conn, 
			inv_unit_fcy_visit.flex_date04 AS rfr_disconn,
			inv_unit_fcy_visit.flex_date05 AS rfr_conn2, 
			inv_unit_fcy_visit.flex_date06 AS rfr_disconn2,
			inv_unit_fcy_visit.flex_date07 AS rfr_conn3, 
			inv_unit_fcy_visit.flex_date08 AS rfr_disconn3,
			(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.ref_equip_type 
			INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey=inv.gkey) AS size,
			((SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2) FROM sparcsn4.inv_unit_equip
			INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey=inv.gkey)/10) AS height,
			sparcsn4.ref_bizunit_scoped.id AS mlo,
			(SELECT sparcsn4.srv_event.creator
			FROM sparcsn4.srv_event
			INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
			WHERE sparcsn4.srv_event.applied_to_gkey=inv.gkey AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND 
			sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey IN ('4','33')
			ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)AS creator,
			(SELECT vsl_vessels.name
			FROM sparcsn4.inv_unit
			INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
			INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			WHERE sparcsn4.inv_unit.gkey=inv.gkey) AS vsl_name,
			(SELECT 
			CASE
			WHEN SUBSTR(UCASE(creator),1,3) = 'CCT' THEN 'CCT'
			WHEN SUBSTR(UCASE(creator),1,3) = 'OFY' THEN 'OFY'
			WHEN SUBSTR(UCASE(creator),1,3) = 'GCB' THEN 'GCB'
			WHEN SUBSTR(UCASE(creator),1,3) = 'NCT' THEN 'NCT'
			ELSE SUBSTR(UCASE(inv.flex_string03),1,3)
			END) AS yard,
			(SELECT ctmsmis.cont_block(last_pos_slot,yard)) AS block,
			sparcsn4.inv_unit_fcy_visit.last_pos_slot AS last_pos,
			sparcsn4.inv_unit_fcy_visit.flex_date01 AS assignment_dt,
			sparcsn4.inv_unit_fcy_visit.time_in AS discharge_time,
			sparcsn4.inv_unit_fcy_visit.time_out AS deli_dt,
			sparcsn4.config_metafield_lov.mfdch_desc  AS deli_type
			FROM sparcsn4.inv_unit inv
			INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
			INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
			INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=inv.line_op
			LEFT JOIN sparcsn4.config_metafield_lov ON inv.flex_string01 = sparcsn4.config_metafield_lov.mfdch_value
			WHERE inv.gkey='$gkey'";
			//echo $sqlQuery2;
			
			 $result2=mysqli_query($con_sparcsn4,$sqlQuery2);  //conn2 for n4 db and   conn for ctmsmisdb
			 $rowcount2=mysqli_num_rows($result2);
	 

			while($row=mysqli_fetch_array($result2))
			{
				   
				//echo "ASIF";
		   
				$gkey=$row['unit_gkey'];
				$id=$row['cont_id'];
				$vsl_visit_dtls_ib_vyg=$row['vessel_visit'];
				$rfr_connect=$row['rfr_conn'];
				$rfr_disconnect=$row['rfr_disconn'];
				$rfr_connect2=$row['rfr_conn2'];
				$rfr_disconnect2=$row['rfr_disconn2'];
				$rfr_connect3=$row['rfr_conn3'];
				$rfr_disconnect3=$row['rfr_disconn3'];
				$size=strtoupper($row['size']);
				$height=strtoupper($row['height']);
				$mlo=strtoupper($row['mlo']);
				$creator=strtoupper($row['creator']);
				$vsl_name=strtoupper($row['vsl_name']);
				$yard=strtoupper($row['yard']);
				$block=strtoupper($row['block']);  //$block,$last_pos,$assignment_dt,$discharge_time,$deli_dt,$deli_type
				$last_pos=strtoupper($row['last_pos']);
				$assignment_dt=$row['assignment_dt'];
				$discharge_time=$row['discharge_time'];
				$deli_dt=$row['deli_dt'];
				$deli_type=strtoupper($row['deli_type']);


				$rfr_connect = !empty($rfr_connect) ? "'$rfr_connect'" : "NULL";
				$rfr_disconnect = !empty($rfr_disconnect) ? "'$rfr_disconnect'" : "NULL";
				$rfr_connect2 = !empty($rfr_connect2) ? "'$rfr_connect2'" : "NULL";
				$rfr_disconnect2 = !empty($rfr_disconnect2) ? "'$rfr_disconnect2'" : "NULL";
				$rfr_connect3 = !empty($rfr_connect3) ? "'$rfr_connect3'" : "NULL";
				$rfr_disconnect3 = !empty($rfr_disconnect3) ? "'$rfr_disconnect3'" : "NULL"; 
				$assignment_dt = !empty($assignment_dt) ? "'$assignment_dt'" : "NULL"; 
				$discharge_time = !empty($discharge_time) ? "'$discharge_time'" : "NULL"; 
				$deli_dt = !empty($deli_dt) ? "'$deli_dt'" : "NULL"; 

			   $sql = "REPLACE INTO ctmsmis.reeferdata(unit_gkey,cont_id,vessel_visit,rfr_conn,rfr_disconn,rfr_conn2,rfr_disconn2,rfr_conn3,rfr_disconn3,size,height,mlo,creator,vsl_name,yard,block,last_pos,assign_dt,discharge_time,deli_dt,deli_type) values('$gkey','$id','$vsl_visit_dtls_ib_vyg',$rfr_connect,$rfr_disconnect,$rfr_connect2,$rfr_disconnect2,$rfr_connect3,$rfr_disconnect3,'$size','$height','$mlo','$creator','$vsl_name','$yard','$block','$last_pos',$assignment_dt,$discharge_time,$deli_dt,'$deli_type')";
				//$sql = "INSERT INTO reeferdata(unit_gkey,cont_id) values('$gkey','$id')";
			  
				//$sql ="UPDATE reeferdata SET vessel_visit='$vsl_visit_dtls_ib_vyg',rfr_conn=$rfr_connect,rfr_disconn=$rfr_disconnect,rfr_conn2=$rfr_connect2,rfr_disconn2=$rfr_disconnect2,rfr_conn3=$rfr_connect3,rfr_disconn3=$rfr_disconnect3,size='$size',height='$height',mlo='$mlo',creator='$creator',vsl_name='$vsl_name',yard='$yard',block='$block',last_pos='$last_pos',assign_dt='$assignment_dt',discharge_time='$discharge_time',deli_dt=$deli_dt,deli_type='$deli_type' WHERE unit_gkey='$gkey'";
				mysqli_query($con_sparcsn4, $sql);
			}
		}		 
	}
*/

//REFER DATA UPDATE TO GIVEN DATE FROM N4 to CTMSMIS REEFERDATA TABLE.... MODIFIED BY ASIF ON 22/2/21 END 






/*  $str="SELECT * FROM ( SELECT unit_gkey, cont_id,vessel_visit,vsl_name, power_rqst_time, rfr_conn,rfr_disconn,rfr_conn2,rfr_disconn2,rfr_conn3,rfr_disconn3,size,height,mlo,creator,yard,block,last_pos,assign_dt, deli_dt, discharge_time, deli_type FROM ctmsmis.reeferdata 
WHERE DATE(ctmsmis.reeferdata.assign_dt) BETWEEN '$fromdate' AND '$todate'
ORDER BY yard
 )
 AS tmp WHERE $col $yrd"; */
 
/* $str="SELECT * FROM (SELECT reeferdata.unit_gkey,reeferdata.cont_id,sparcsn4.vsl_vessel_visit_details.ib_vyg AS vessel_visit,inv.power_rqst_time,
inv_unit_fcy_visit.flex_date03 AS rfr_conn, 
inv_unit_fcy_visit.flex_date04 AS rfr_disconn,
inv_unit_fcy_visit.flex_date05 AS rfr_conn2, 
inv_unit_fcy_visit.flex_date06 AS rfr_disconn2,
inv_unit_fcy_visit.flex_date07 AS rfr_conn3, 
inv_unit_fcy_visit.flex_date08 AS rfr_disconn3,
(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.ref_equip_type 
INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
WHERE sparcsn4.inv_unit_equip.unit_gkey=inv.gkey) AS size,
((SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2) FROM sparcsn4.inv_unit_equip
INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
WHERE sparcsn4.inv_unit_equip.unit_gkey=inv.gkey)/10) AS height,
sparcsn4.ref_bizunit_scoped.id AS mlo,
(SELECT sparcsn4.srv_event.creator
FROM sparcsn4.srv_event
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.applied_to_gkey=reeferdata.unit_gkey AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND 
sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey IN ('4','33')
ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)AS creator_temp,
(SELECT vsl_vessels.name
FROM sparcsn4.inv_unit
INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
WHERE sparcsn4.inv_unit.gkey=inv.gkey) AS vsl_name,
(SELECT 
CASE
WHEN SUBSTR(UCASE(creator_temp),1,3) = 'CCT' THEN 'CCT'
WHEN SUBSTR(UCASE(creator_temp),1,3) = 'OFY' THEN 'OFY'
WHEN SUBSTR(UCASE(creator_temp),1,3) = 'GCB' THEN 'GCB'
WHEN SUBSTR(UCASE(creator_temp),1,3) = 'NCT' THEN 'NCT'
ELSE SUBSTR(UCASE(inv.flex_string03),1,3)
END) AS yard,
(SELECT ctmsmis.cont_block(last_pos_slot,yard)) AS block,
sparcsn4.inv_unit_fcy_visit.last_pos_slot AS last_pos,
sparcsn4.inv_unit_fcy_visit.flex_date01 AS assignment_dt,
sparcsn4.inv_unit_fcy_visit.time_in AS discharge_time,
sparcsn4.inv_unit_fcy_visit.time_out AS deli_dt,
sparcsn4.config_metafield_lov.mfdch_desc  AS deli_type
FROM ctmsmis.reeferdata
INNER JOIN sparcsn4.inv_unit inv ON reeferdata.unit_gkey=inv.gkey
INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=inv.line_op
LEFT JOIN sparcsn4.config_metafield_lov ON inv.flex_string01 = sparcsn4.config_metafield_lov.mfdch_value
WHERE  ctmsmis.reeferdata.unit_gkey>'1332243' AND  DATE(sparcsn4.inv_unit_fcy_visit.flex_date01) BETWEEN '$fromdate' AND '$todate' ORDER BY yard
)AS tmp WHERE $col $yrd"; 
 */  
 
/*$str="SELECT * FROM (SELECT reeferdata.unit_gkey,reeferdata.cont_id,sparcsn4.vsl_vessel_visit_details.ib_vyg AS vessel_visit,inv.power_rqst_time,
inv_unit_fcy_visit.flex_date03 AS rfr_conn, 
inv_unit_fcy_visit.flex_date04 AS rfr_disconn,
inv_unit_fcy_visit.flex_date05 AS rfr_conn2, 
inv_unit_fcy_visit.flex_date06 AS rfr_disconn2,
inv_unit_fcy_visit.flex_date07 AS rfr_conn3, 
inv_unit_fcy_visit.flex_date08 AS rfr_disconn3,
(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.ref_equip_type 
INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
WHERE sparcsn4.inv_unit_equip.unit_gkey=inv.gkey) AS size,
((SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2) FROM sparcsn4.inv_unit_equip
INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
WHERE sparcsn4.inv_unit_equip.unit_gkey=inv.gkey)/10) AS height,
sparcsn4.ref_bizunit_scoped.id AS mlo,
(SELECT vsl_vessels.name
FROM sparcsn4.inv_unit
INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
WHERE sparcsn4.inv_unit.gkey=inv.gkey) AS vsl_name,
SUBSTR(UCASE(inv.flex_string03),1,3)
 AS yard,
(SELECT ctmsmis.cont_block(last_pos_slot,yard)) AS block,
sparcsn4.inv_unit_fcy_visit.last_pos_slot AS last_pos,
sparcsn4.inv_unit_fcy_visit.flex_date01 AS assignment_dt,
sparcsn4.inv_unit_fcy_visit.time_in AS discharge_time,
sparcsn4.inv_unit_fcy_visit.time_out AS deli_dt,
sparcsn4.config_metafield_lov.mfdch_desc  AS deli_type
FROM ctmsmis.reeferdata
INNER JOIN sparcsn4.inv_unit inv ON reeferdata.unit_gkey=inv.gkey
INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=inv.line_op
LEFT JOIN sparcsn4.config_metafield_lov ON inv.flex_string01 = sparcsn4.config_metafield_lov.mfdch_value
WHERE  ctmsmis.reeferdata.unit_gkey>'$gkey' AND  sparcsn4.inv_unit_fcy_visit.flex_date01 BETWEEN concat('$fromdate',' 00:00:00') AND concat('$todate',' 23:59:59') ORDER BY yard
)AS tmp WHERE $col $yrd";*/

 
//echo $str;
		//echo $str; 
	//break;
	$reeferDataStr="SELECT reeferdata.unit_gkey FROM ctmsmis.reeferdata where reeferdata.unit_gkey='$gkey'";
	$reeferDataQuery=mysqli_query($con_sparcsn4,$reeferDataStr);
	$totalRow=mysqli_num_rows($reeferDataQuery);
	$reeferDataRes=mysqli_fetch_object($reeferDataQuery);
	$reeferDataList="";
	$k=0;
	if($totalRow>0){
		while($reeferDataRow=mysqli_fetch_object($reeferDataQuery)){
			$unitGkey="";
			$unitGkey=$reeferDataRow->unit_gkey;
			if($k==($totalRow-1)){
			  $reeferDataList=$reeferDataList."'".$unitGkey."'";	
			}
			else{
				$reeferDataList=$reeferDataList."'".$unitGkey."',";

			}
			$k++;
		}
	}

	$str="SELECT * FROM (SELECT vsl_vessel_visit_details.ib_vyg AS vessel_visit,inv.power_rqst_time,inv.gkey
	inv_unit_fcy_visit.flex_date03 AS rfr_conn, 
	inv_unit_fcy_visit.flex_date04 AS rfr_disconn,
	inv_unit_fcy_visit.flex_date05 AS rfr_conn2, 
	inv_unit_fcy_visit.flex_date06 AS rfr_disconn2,
	inv_unit_fcy_visit.flex_date07 AS rfr_conn3, 
	inv_unit_fcy_visit.flex_date08 AS rfr_disconn3,
	(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM ref_equip_type 
	INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS siz,
	((SELECT SUBSTR(ref_equip_type.nominal_height,-2) FROM inv_unit
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	WHERE inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
	ref_bizunit_scoped.id AS mlo,
	(SELECT vsl_vessels.name
	FROM inv_unit
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey= inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	WHERE inv_unit.gkey=inv.gkey) AS vsl_name,
	SUBSTR(UPPER(inv.flex_string03),1,3)
	AS yard,
	inv_unit_fcy_visit.last_pos_slot AS last_pos,
	inv_unit_fcy_visit.flex_date01 AS assignment_dt,
	inv_unit_fcy_visit.time_in AS discharge_time,
	inv_unit_fcy_visit.time_out AS deli_dt,
	config_metafield_lov.mfdch_desc  AS deli_type
	FROM inv_unit inv
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey= inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv.line_op
	LEFT JOIN config_metafield_lov ON inv.flex_string01 = config_metafield_lov.mfdch_value
	WHERE  inv.gkey IN($reeferDataList) AND inv_unit_fcy_visit.flex_date01 
	BETWEEN to_date(concat('$fromdate',' 00:00:00'),'yyyy-mm-dd hh24:mi,ss') 
	AND to_date(concat('$todate',' 23:59:59'),'yyyy-mm-dd hh24:mi,ss') ORDER BY yard
	) tmp WHERE $col $yrd";

	$query=oci_parse($con_sparcsn4_oracle,$str);
	oci_execute($query);

	$i=0;
	$j=0;
	
	$mlo="";
	while(($row=oci_fetch_object($query))!=false){
		$key="";
		$yardNo="";
		$lastPosSlot="";
		$key=$row->GKEY;
		$yardNo=$row->YARD;
		$lastPosSlot=$row->LAST_POS;
		


		$contNoStr="SELECT reeferdata.cont_id FROM ctmsmis.reeferdata where reeferdata.unit_gkey='$key'";
		$contNoQuery=mysqli_query($con_sparcsn4,$contNoStr);
		$contNoRes=mysqli_fetch_object($contNoQuery);
		$blockNoStr="SELECT ctmsmis.cont_block('lastPosSlot','$yardNo') AS block";
		$blockNoQuery=mysqli_query($con_sparcsn4,$blockNoStr);
		$blockNoRes=mysqli_fetch_object($blockNoQuery);


		$i++;
	
		
	
?>
	<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($contNoRes->cont_id) echo $contNoRes->cont_id; else echo "&nbsp;";?></td>
		<td><?php if($row->size) echo $row->SIZ; else echo "&nbsp;";?></td>
		<td><?php if($row->HEIGHT) echo $row->HEIGHT; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<td><?php if($row->VSL_NAME) echo $row->VSL_NAME; else echo "&nbsp;";?></td>
		<td><?php if($row->VESSEL_VISIT) echo $row->VESSEL_VISIT; else echo "&nbsp;";?></td>
		<td><?php if($row->DISCHARGE_TIME) echo $row->DISCHARGE_TIME; else echo "&nbsp;";?></td>
		<td><?php if($row->YARD) echo $row->YARD; else echo "&nbsp;";?></td>
		<td><?php if($blockNoRes->block) echo $blockNoRes->block; else echo "&nbsp;";?></td>
		<td><?php if($row->LAST_POS) echo $row->LAST_POS; else echo "&nbsp;";?></td>
		<td><?php if($row->POWER_RQST_TIME) echo $row->POWER_RQST_TIME; else echo "&nbsp;";?></td>
		<td><?php if($row->RFR_CONN) echo $row->RFR_CONN; else echo "&nbsp;";?></td>
		<td><?php if($row->RFR_DISCONN) echo $row->RFR_DISCONN; else echo "&nbsp;";?></td>
		<td><?php if($row->DELI_TYPE) echo $row->DELI_TYPE; else echo "&nbsp;";?></td>
		<td><?php if($row->ASSIGNMENT_DT) echo $row->ASSIGNMENT_DT; else echo "&nbsp;";?></td>
		<td><?php if($row->DELI_DT) echo $row->DELI_DT; else echo "&nbsp;";?></td>				
	</tr>

<?php } ?>
</table>
<br />
<br />



<?php 
mysqli_close($con_sparcsn4);
if(@$_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
