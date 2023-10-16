
<?php if(@$_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Reefer Report</TITLE>
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
		header("Content-Disposition: attachment; filename=IMPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	//$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	//$con=mysql_connect("10.1.1.21", "sparcsn4","sparcsn4")or die("sparcsn4 database cannot connect"); 
	//mysql_select_db("sparcsn4")or die("cannot select DB");
	include("dbConection.php");
   include("dbOracleConnection.php");	
	//echo $todate;

	
		//$col = "date(inv_unit_fcy_visit.flex_date04) BETWEEN '$fromdate' AND '$todate'";		
		//$head = "Delivery Date Wise Import Reefer Container List";
	
	
	?>
<html>
<?php if(@$_POST['options']=='html'){ ?>
<title>Import Reefer Container Discharge List</title>
<?php } ?>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				<tr align="center">
					<td colspan="12"><font size="4"><b>CHITTAGONG PORT AUTHORITY</b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b>Reefer Position of Yard - <?php if(@$row->creator) echo $row->creator; else echo "&nbsp;";?><?php echo $yard_no;?></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b><u>Delivery Date Wise Import Reefer Container List</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b><u>DATE :</u> <?php
					echo date("d-m-Y", strtotime($fromdate)).' To '.date("d-m-Y", strtotime($todate));
					//$test1='$fromdate';
					//echo date('d-m-Y',strtotime($test1));
						?></b></font></td>
				</tr>

			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>

	<?php
		if($_POST['options'] == 'html'){
			echo "<table class='table table-bordered table-responsive table-hover table-striped mb-none'>";
		} else if($_POST['options'] == 'pdf'){
			echo "<table width='100%' border ='1' cellpadding='0' cellspacing='0' style='border-collapse:collapse;'>";
		} else if($_POST['options'] == 'xl'){
			echo "<table width='100%' border ='1' cellpadding='0' cellspacing='0'>";
		}
	?>
	<thead>
		<tr  align="center">
			<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
			<td style="border-width:3px;border-style: double;"><b>MLO.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Size.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Vessel Name.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Rotation No.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Connection 1 Date & Time.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Disconnection 1 Date & Time.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Connection 2 Date & Time.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Disconnection 2 Date & Time.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Connection 3 Date & Time.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Disconnection 3 Date & Time.</b></td>
			<!--td style="border-width:3px;border-style: double;"><b>Yard Name.</b></td-->
			<td style="border-width:3px;border-style: double;"><b>User.</b></td>
		</tr>
	</thead>

<?php

$searchVal="";
if($rfrConStat=="1"){
	$searchVal="inv_unit_fcy_visit.flex_date04";
	//$searchVal="inv_unit_fcy_visit.flex_date03";
	
}else if($rfrConStat=="2"){
	$searchVal="inv_unit_fcy_visit.flex_date06";
	//$searchVal="inv_unit_fcy_visit.flex_date04";
}else if($rfrConStat=="3"){
	$searchVal="inv_unit_fcy_visit.flex_date08";
	//$searchVal="inv_unit_fcy_visit.flex_date05";
}
	if($yard_no=='All')
	{
		$yrd="";
	}
	else
	{		
		$yrd="yard='$yard_no'";
	}
//echo "VAL : ".$searchVal;

//REFER DATA UPDATE TO GIVEN DATE FROM N4 to CTMSMIS REEFERDATA TABLE.... MODIFIED BY ASIF ON 22/2/21 START 
$maxUnitGkeyStr="SELECT MAX(unit_gkey) as max_unit_gkey FROM ctmsmis.reeferdata";
$maxUnitGkeyQuery=mysqli_query($con_sparcsn4,$maxUnitGkeyStr);
$maxUnitGkeyRes=mysqli_fetch_object($maxUnitGkeyQuery);
$maxUnitGkey="";
$maxUnitGkey=$maxUnitGkeyRes->unit_max_unit_gkey;

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






/* $sqlQuery="SELECT gkey FROM sparcsn4.inv_unit WHERE  sparcsn4.inv_unit.requires_power=1 AND  sparcsn4.inv_unit.create_time BETWEEN '$fromdate' - interval 5 day) AND '$todate'";

$result=mysqli_query($con_sparcsn4,$sqlQuery);  //conn2 for n4 db and   conn for ctmsmisdb
$rowcount=mysqli_num_rows($result);
 
	if($rowcount>0){
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
	} */


//REFER DATA UPDATE TO GIVEN DATE FROM N4 to CTMSMIS REEFERDATA TABLE.... MODIFIED BY ASIF ON 22/2/21 END 



//echo "Who I am:".$yard_no;
if($yard_no=="All"){ 
	/*  $str="SELECT * FROM (SELECT sparcsn4.ref_bizunit_scoped.id AS mlo,reeferdata.cont_id,
	(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.ref_equip_type 
	INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
	WHERE sparcsn4.inv_unit_equip.unit_gkey=reeferdata.unit_gkey) AS size,
	(SELECT vsl_vessels.name
	FROM sparcsn4.inv_unit
	INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
	INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
	WHERE sparcsn4.inv_unit.gkey=reeferdata.unit_gkey) AS vsl_name,sparcsn4.vsl_vessel_visit_details.ib_vyg AS vessel_visit,
	inv_unit_fcy_visit.flex_date03 AS rfr_conn, 
	inv_unit_fcy_visit.flex_date04 AS rfr_disconn,
	inv_unit_fcy_visit.flex_date05 AS rfr_conn2, 
	inv_unit_fcy_visit.flex_date06 AS rfr_disconn2,
	inv_unit_fcy_visit.flex_date07 AS rfr_conn3, 
	inv_unit_fcy_visit.flex_date08 AS rfr_disconn3,
	(SELECT sparcsn4.srv_event.creator
	FROM sparcsn4.srv_event
	INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
	WHERE sparcsn4.srv_event.applied_to_gkey=reeferdata.unit_gkey AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND 
	sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey IN ('4','33')
	ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)AS creator_temp,
	(SELECT 
	CASE
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'CCT' THEN 'CCT'
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'OFY' THEN 'OFY'
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'GCB' THEN 'GCB'
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'NCT' THEN 'NCT'
	ELSE SUBSTR(UCASE(inv.flex_string03),1,3)
	END) AS yard
	
	FROM ctmsmis.reeferdata
	INNER JOIN sparcsn4.inv_unit inv ON reeferdata.unit_gkey=inv.gkey
	INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
	INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
	INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=inv.line_op
	WHERE  ctmsmis.reeferdata.unit_gkey>'1332243' AND  date($searchVal) BETWEEN '$fromdate' AND '$todate') AS tmp ORDER BY rfr_disconn"; */
	
	
	
	/*$str="SELECT * FROM (SELECT sparcsn4.ref_bizunit_scoped.id AS mlo,reeferdata.cont_id,
	(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.ref_equip_type 
	INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
	WHERE sparcsn4.inv_unit_equip.unit_gkey=reeferdata.unit_gkey LIMIT 1) AS size,
	(SELECT vsl_vessels.name
	FROM sparcsn4.inv_unit
	INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
	INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
	WHERE sparcsn4.inv_unit.gkey=reeferdata.unit_gkey) AS vsl_name,sparcsn4.vsl_vessel_visit_details.ib_vyg AS vessel_visit,
	inv_unit_fcy_visit.flex_date03 AS rfr_conn, 
	inv_unit_fcy_visit.flex_date04 AS rfr_disconn,
	inv_unit_fcy_visit.flex_date05 AS rfr_conn2, 
	inv_unit_fcy_visit.flex_date06 AS rfr_disconn2,
	inv_unit_fcy_visit.flex_date07 AS rfr_conn3, 
	inv_unit_fcy_visit.flex_date08 AS rfr_disconn3,
	(SELECT sparcsn4.srv_event.creator
			FROM sparcsn4.srv_event
			INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
			WHERE sparcsn4.srv_event.applied_to_gkey= ctmsmis.reeferdata.unit_gkey  AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND 
			sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey IN ('4','33')
			ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)AS creator_temp,
	(SELECT 
	CASE
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'CCT' THEN 'CCT'
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'OFY' THEN 'OFY'
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'GCB' THEN 'GCB'
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'NCT' THEN 'NCT'
	ELSE SUBSTR(UCASE(inv.flex_string03),1,3)
	END) AS yard
	
	FROM sparcsn4.inv_unit inv
	INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=reeferdata.unit_gkey
	INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
	INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=inv.line_op
	INNER JOIN sparcsn4.srv_event ON sparcsn4.srv_event.applied_to_gkey = reeferdata.unit_gkey
	INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
	WHERE  $searchVal BETWEEN concat('$fromdate',' 00:00:00') AND concat('$todate',' 23:59:59') AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND 
	sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey IN ('4','33')) AS tmp GROUP BY cont_id ORDER BY rfr_disconn";*/


	$str="Select distinct cont_id,tb1.*
	From
	(
	SELECT tmp.*,
	(CASE
	WHEN SUBSTR(UPPER(creator_temp),1,3) = 'CCT' THEN 'CCT'
	WHEN SUBSTR(UPPER(creator_temp),1,3) = 'OFY' THEN 'OFY'
	WHEN SUBSTR(UPPER(creator_temp),1,3) = 'GCB' THEN 'GCB'
	WHEN SUBSTR(UPPER(creator_temp),1,3) = 'NCT' THEN 'NCT'
	ELSE SUBSTR(UPPER(flex_string03),1,3)
	END) AS yard
	FROM
	(
	SELECT ref_bizunit_scoped.id AS mlo,inv.id as cont_id,inv.flex_string03,
	(SELECT substr(ref_equip_type.nominal_length,2) FROM ref_equip_type 
	INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS siz,
	(SELECT vsl_vessels.name
	FROM inv_unit
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey= inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	WHERE inv_unit.gkey=inv.gkey) AS vsl_name,vsl_vessel_visit_details.ib_vyg AS vessel_visit,
	inv_unit_fcy_visit.flex_date03 AS rfr_conn, 
	inv_unit_fcy_visit.flex_date04 AS rfr_disconn,
	inv_unit_fcy_visit.flex_date05 AS rfr_conn2, 
	inv_unit_fcy_visit.flex_date06 AS rfr_disconn2,
	inv_unit_fcy_visit.flex_date07 AS rfr_conn3, 
	inv_unit_fcy_visit.flex_date08 AS rfr_disconn3,
	(SELECT srv_event.creator
	FROM srv_event
	INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
	WHERE srv_event.applied_to_gkey= inv.gkey  AND srv_event_field_changes.new_value IS NOT NULL AND 
	srv_event_field_changes.new_value LIKE'%BDT' AND srv_event.event_type_gkey IN ('4','33')
	ORDER BY srv_event.gkey DESC fetch first 1 rows only)AS creator_temp
	FROM inv_unit inv
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey= inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv.line_op
	INNER JOIN srv_event ON srv_event.applied_to_gkey = inv.gkey
	INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
	WHERE  $searchVal BETWEEN to_date(concat('$fromdate',' 00:00:00'),'yyyy-mm-dd hh24-mi-ss') 
	AND to_date(concat('$todate',' 23:59:59'),'yyyy-mm-dd hh24-mi-ss') 
	AND srv_event_field_changes.new_value IS NOT NULL 
	AND srv_event_field_changes.new_value LIKE'%BDT' AND srv_event.event_type_gkey IN ('4','33')
	)  tmp )tb1
   ORDER BY mlo,rfr_disconn";
	
	
	
	
/* 	echo $str;
	return; */
	
	
	
	// Sumon Roy,
	//	Changes query dated-13-01-2021
	/*  $str="SELECT * FROM(SELECT unit_gkey, cont_id,vessel_visit,vsl_name,rfr_conn,rfr_disconn,rfr_conn2,rfr_disconn2,rfr_conn3,rfr_disconn3,size,height,mlo,
	creator,yard,block,last_pos,assign_dt deli_dt, discharge_time, deli_type FROM ctmsmis.reeferdata 
	WHERE date($searchVal) BETWEEN '$fromdate' AND '$todate'		

		) AS tmp ORDER BY rfr_disconn"; */

		/* $query="SELECT * FROM (
		SELECT inv.id,

		sparcsn4.vsl_vessel_visit_details.ib_vyg AS vsl_visit_dtls_ib_vyg,

		inv_unit_fcy_visit.flex_date03 AS rfr_connect, 
		inv_unit_fcy_visit.flex_date04 AS rfr_disconnect,
		inv_unit_fcy_visit.flex_date05 AS rfr_connect2, 
		inv_unit_fcy_visit.flex_date06 AS rfr_disconnect2,
		inv_unit_fcy_visit.flex_date07 AS rfr_connect3, 
		inv_unit_fcy_visit.flex_date08 AS rfr_disconnect3,

		(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.ref_equip_type 
		INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
		WHERE sparcsn4.inv_unit_equip.unit_gkey=inv.gkey
		) AS size,

		sparcsn4.ref_bizunit_scoped.id AS mlo,

		(SELECT sparcsn4.srv_event.creator
		FROM sparcsn4.srv_event
		INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
		WHERE sparcsn4.srv_event.applied_to_gkey=inv.gkey AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND 
		sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey IN ('4','33')
		ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)AS creator,
		(
		SELECT vsl_vessels.name
		FROM sparcsn4.inv_unit
		INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
		INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
		WHERE sparcsn4.inv_unit.gkey=inv.gkey) AS vsl_name,
		(
		SELECT 
		CASE
		WHEN SUBSTR(UCASE(creator),1,3) = 'CCT' THEN 'CCT'
		WHEN SUBSTR(UCASE(creator),1,3) = 'GCB' THEN 'GCB'
		WHEN SUBSTR(UCASE(creator),1,3) = 'NCT' THEN 'NCT'
		ELSE SUBSTR(UCASE(inv.flex_string03),1,3)
		END

		) AS yard 
		FROM sparcsn4.inv_unit inv
		INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
		INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=inv.line_op

		WHERE date($searchVal) BETWEEN '$fromdate' AND '$todate'
		AND inv.category='IMPRT'

		) AS tmp ORDER BY rfr_disconnect"; */
					  
	}else{
		$login_id = $this->session->userdata('login_id');
		
		/*  $str="SELECT * FROM(SELECT unit_gkey, cont_id,vessel_visit,vsl_name,rfr_conn,rfr_disconn,rfr_conn2,rfr_disconn2,rfr_conn3,rfr_disconn3,size,height,mlo,
		creator,yard,block,last_pos,assign_dt deli_dt, discharge_time, deli_type FROM ctmsmis.reeferdata 
		WHERE date($searchVal)
		 ) AS tmp WHERE yard='$yard_no'order by mlo, rfr_disconnect 
		 ";  */
		 
		/* $str="SELECT * FROM (SELECT sparcsn4.ref_bizunit_scoped.id AS mlo,reeferdata.cont_id,
		(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.ref_equip_type 
		INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
		WHERE sparcsn4.inv_unit_equip.unit_gkey=reeferdata.unit_gkey) AS size,
		(SELECT vsl_vessels.name
		FROM sparcsn4.inv_unit
		INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
		INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
		WHERE sparcsn4.inv_unit.gkey=reeferdata.unit_gkey) AS vsl_name,sparcsn4.vsl_vessel_visit_details.ib_vyg AS vessel_visit,
		inv_unit_fcy_visit.flex_date03 AS rfr_conn, 
		inv_unit_fcy_visit.flex_date04 AS rfr_disconn,
		inv_unit_fcy_visit.flex_date05 AS rfr_conn2, 
		inv_unit_fcy_visit.flex_date06 AS rfr_disconn2,
		inv_unit_fcy_visit.flex_date07 AS rfr_conn3, 
		inv_unit_fcy_visit.flex_date08 AS rfr_disconn3,
		(SELECT sparcsn4.srv_event.creator
		FROM sparcsn4.srv_event
		INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
		WHERE sparcsn4.srv_event.applied_to_gkey=reeferdata.unit_gkey AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND 
		sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey IN ('4','33')
		ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)AS creator_temp,
		(SELECT 
		CASE
		WHEN SUBSTR(UCASE(creator_temp),1,3) = 'CCT' THEN 'CCT'
		WHEN SUBSTR(UCASE(creator_temp),1,3) = 'OFY' THEN 'OFY'
		WHEN SUBSTR(UCASE(creator_temp),1,3) = 'GCB' THEN 'GCB'
		WHEN SUBSTR(UCASE(creator_temp),1,3) = 'NCT' THEN 'NCT'
		ELSE SUBSTR(UCASE(inv.flex_string03),1,3)
		END) AS yard
		
		FROM ctmsmis.reeferdata
		INNER JOIN sparcsn4.inv_unit inv ON reeferdata.unit_gkey=inv.gkey
		INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
		INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=inv.line_op
		WHERE ctmsmis.reeferdata.unit_gkey>'1332243' AND date($searchVal)) AS tmp WHERE yard='$yard_no'order by mlo, rfr_disconnect";
	} */
	/*$str ="SELECT * FROM (SELECT sparcsn4.ref_bizunit_scoped.id AS mlo,inv.id as cont_id,
	(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.ref_equip_type 
	INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
	WHERE sparcsn4.inv_unit_equip.unit_gkey=inv.gkey LIMIT 1) AS size,
	(SELECT vsl_vessels.name
	FROM sparcsn4.inv_unit
	INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
	INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
	WHERE sparcsn4.inv_unit.gkey=inv.gkey) AS vsl_name,sparcsn4.vsl_vessel_visit_details.ib_vyg AS vessel_visit,
	inv_unit_fcy_visit.flex_date03 AS rfr_conn, 
	inv_unit_fcy_visit.flex_date04 AS rfr_disconn,
	inv_unit_fcy_visit.flex_date05 AS rfr_conn2, 
	inv_unit_fcy_visit.flex_date06 AS rfr_disconn2,
	inv_unit_fcy_visit.flex_date07 AS rfr_conn3, 
	inv_unit_fcy_visit.flex_date08 AS rfr_disconn3,
	(SELECT sparcsn4.srv_event.creator
			FROM sparcsn4.srv_event
			INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
			WHERE sparcsn4.srv_event.applied_to_gkey= inv.gkey  AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND 
			sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey IN ('4','33')
			ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)AS creator_temp,
	(SELECT 
	CASE
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'CCT' THEN 'CCT'
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'OFY' THEN 'OFY'
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'GCB' THEN 'GCB'
	WHEN SUBSTR(UCASE(creator_temp),1,3) = 'NCT' THEN 'NCT'
	ELSE SUBSTR(UCASE(inv.flex_string03),1,3)
	END) AS yard
	
	FROM sparcsn4.inv_unit inv
	INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
	INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey= sparcsn4.inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
	INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=inv.line_op
	INNER JOIN sparcsn4.srv_event ON sparcsn4.srv_event.applied_to_gkey = inv.gkey
	INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
	WHERE  $searchVal BETWEEN concat('$fromdate',' 00:00:00') AND concat('$todate',' 23:59:59') AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND 
sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey IN ('4','33')) AS tmp WHERE  yard='$yard_no' GROUP BY cont_id ORDER BY mlo,rfr_disconn";*/
	//echo $str;
	$str="Select distinct cont_id,tb1.*
	From
	(
	SELECT tmp.*,
	(CASE
	WHEN SUBSTR(UPPER(creator_temp),1,3) = 'CCT' THEN 'CCT'
	WHEN SUBSTR(UPPER(creator_temp),1,3) = 'OFY' THEN 'OFY'
	WHEN SUBSTR(UPPER(creator_temp),1,3) = 'GCB' THEN 'GCB'
	WHEN SUBSTR(UPPER(creator_temp),1,3) = 'NCT' THEN 'NCT'
	ELSE SUBSTR(UPPER(flex_string03),1,3)
	END) AS yard
	FROM
	(
	SELECT ref_bizunit_scoped.id AS mlo,inv.id as cont_id,inv.flex_string03,
	(SELECT substr(ref_equip_type.nominal_length,2) FROM ref_equip_type 
	INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS siz,
	(SELECT vsl_vessels.name
	FROM inv_unit
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey= inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	WHERE inv_unit.gkey=inv.gkey) AS vsl_name,vsl_vessel_visit_details.ib_vyg AS vessel_visit,
	inv_unit_fcy_visit.flex_date03 AS rfr_conn, 
	inv_unit_fcy_visit.flex_date04 AS rfr_disconn,
	inv_unit_fcy_visit.flex_date05 AS rfr_conn2, 
	inv_unit_fcy_visit.flex_date06 AS rfr_disconn2,
	inv_unit_fcy_visit.flex_date07 AS rfr_conn3, 
	inv_unit_fcy_visit.flex_date08 AS rfr_disconn3,
	(SELECT srv_event.creator
	FROM srv_event
	INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
	WHERE srv_event.applied_to_gkey= inv.gkey  AND srv_event_field_changes.new_value IS NOT NULL AND 
	srv_event_field_changes.new_value LIKE'%BDT' AND srv_event.event_type_gkey IN ('4','33')
	ORDER BY srv_event.gkey DESC fetch first 1 rows only)AS creator_temp
	FROM inv_unit inv
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey= inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv.line_op
	INNER JOIN srv_event ON srv_event.applied_to_gkey = inv.gkey
	INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
	WHERE  $searchVal BETWEEN to_date(concat('$fromdate',' 00:00:00'),'yyyy-mm-dd hh24-mi-ss') 
	AND to_date(concat('$todate',' 23:59:59'),'yyyy-mm-dd hh24-mi-ss') 
	AND srv_event_field_changes.new_value IS NOT NULL 
	AND srv_event_field_changes.new_value LIKE'%BDT' AND srv_event.event_type_gkey IN ('4','33')
	)  tmp )tb1
	WHERE  yard='$yard_no'  ORDER BY mlo,rfr_disconn";
	
	}
	$query=oci_parse($con_sparcsn4_oracle,$str);
	oci_execute($query);
	$i=0;
	$j=0;
	
	$mlo="";
	while(($row=oci_fetch_object($query))!=false){
	$i++;
	
		
	
?>

<tr align="center">
		<td align="center"><?php  echo $i;?></td>
		<td align="center"><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->CONT_ID) echo $row->CONT_ID; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->SIZ) echo $row->SIZ; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->VSL_NAME) echo $row->VSL_NAME; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->VESSEL_VISIT) echo $row->VESSEL_VISIT; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->RFR_CONN) echo $row->RFR_CONN; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->RFR_DISCONN) echo $row->RFR_DISCONN; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->RFR_CONN2) echo $row->RFR_CONN2; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->RFR_DISCONN2) echo $row->RFR_DISCONN2; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->RFR_CONN3) echo $row->RFR_CONN3; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->RFR_DISCONN3) echo $row->RFR_DISCONN3; else echo "&nbsp;";?></td>
		<!--td><?php if($row->yard) echo $row->YARD; else echo "&nbsp;";?></td-->
		<td align="center"><?php if($row->CREATOR_TEMP) echo $row->CREATOR_TEMP; else echo "&nbsp;";?></td>
	</tr>

<?php } ?>
</table>
 </br>
 </br>

<table width="85%" border="1" cellpadding='0' cellspacing='0' align="center">
	<tr  align="center">
		<td colspan="3" style="border: none; font-size:20px" align="center"><b>Total Summary</b></td>
		<td colspan="5" style="border: none; font-size:16px" align="center">
			<font size="4"><b>DATE : <?php echo date("d-m-Y", strtotime($fromdate)).'  To  '.date("d-m-Y", strtotime($todate)); ?></b></font>
		</td>
	</tr>
	<tr>
		<td align="center"><b>MLO</b></td>
		<td align="center"><b>AGENT NAME</b></td>
		<td align="center"><b>20 X 8.5</b></td>
		<td align="center"><b>20 X 9.5</b></td>
		<td align="center"><b>40 X 8.5</b></td>
		<td align="center"><b>40 X 9.5</b></td>
		<!--td align="center"><b>45 X 9.5</b></td-->
		<td align="center"><b>TOTAL</b></td>
	</tr>

<?php
if($yard_no=="All")
{
	$yrd="";
}
else
{
	$yrd=" WHERE yard='$yard_no'";
}


	/*$str2="SELECT DISTINCT mlo,agent,agent_name,yard FROM (SELECT DISTINCT(r.id) AS mlo, Y.id AS agent, Y.name AS agent_name,
	(SELECT sparcsn4.srv_event.creator FROM sparcsn4.srv_event 
	INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
	WHERE sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL
	AND sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey=4
	 ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1) AS creator_name,
	(
	SELECT 
	CASE
		WHEN SUBSTR(UCASE(creator_name),1,3) = 'CCT' THEN 'CCT'
		WHEN SUBSTR(UCASE(creator_name),1,3) = 'OFY' THEN 'OFY'
		
		WHEN SUBSTR(UCASE(creator_name),1,3) = 'GCB' THEN 'GCB'
		WHEN SUBSTR(UCASE(creator_name),1,3) = 'NCT' THEN 'NCT'
		ELSE SUBSTR(UCASE(inv_unit.flex_string03),1,3)
	END

	) AS yard 
	FROM sparcsn4.inv_unit
	INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey

	INNER JOIN  ( sparcsn4.ref_bizunit_scoped r  
	LEFT JOIN ( sparcsn4.ref_agent_representation X  
	LEFT JOIN sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
	ON r.gkey=X.bzu_gkey)  ON r.gkey = sparcsn4.inv_unit.line_op
	WHERE $searchVal BETWEEN concat('$fromdate',' 00:00:00') AND concat('$todate',' 23:59:59') AND sparcsn4.inv_unit.category='IMPRT') AS tmp
	$yrd order by mlo asc";*/
	
	/*$str2="SELECT * FROM ( 
	SELECT DISTINCT(r.id) AS mlo, Y.id AS agent, Y.name AS agent_name,yard
	FROM 
	(
	SELECT unit_gkey,
	(SELECT line_op FROM sparcsn4.inv_unit WHERE gkey=unit_gkey) AS line_op,
	(SELECT sparcsn4.srv_event.creator FROM sparcsn4.srv_event 
	INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
	WHERE sparcsn4.srv_event.applied_to_gkey=unit_gkey AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL
	AND sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey=4
	 ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1) AS creator_name,
	 (
	SELECT 
	CASE
		WHEN SUBSTR(UCASE(creator_name),1,3) = 'CCT' THEN 'CCT'
		WHEN SUBSTR(UCASE(creator_name),1,3) = 'OFY' THEN 'OFY'
		
		WHEN SUBSTR(UCASE(creator_name),1,3) = 'GCB' THEN 'GCB'
		WHEN SUBSTR(UCASE(creator_name),1,3) = 'NCT' THEN 'NCT'
		ELSE SUBSTR(UCASE((SELECT flex_string03 FROM sparcsn4.inv_unit WHERE gkey=unit_gkey)),1,3)
	END

	) AS yard 
	FROM sparcsn4.inv_unit_fcy_visit WHERE $searchVal BETWEEN concat('$fromdate',' 00:00:00') AND concat('$todate',' 23:59:59') 
	) AS tmp 
	INNER JOIN  ( sparcsn4.ref_bizunit_scoped r  
	LEFT JOIN ( sparcsn4.ref_agent_representation X  
	LEFT JOIN sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
	ON r.gkey=X.bzu_gkey)  ON r.gkey = tmp.line_op
	) AS fnl $yrd ORDER BY mlo ASC";*/
	//echo $str2;
	$str2="SELECT * FROM ( 
		SELECT DISTINCT(r.id) AS mlo, Y.id AS agent, Y.name AS agent_name,tmp.*,
		
		(CASE
		WHEN SUBSTR(UPPER(creator_name),1,3) = 'CCT' THEN 'CCT'
		WHEN SUBSTR(UPPER(creator_name),1,3) = 'OFY' THEN 'OFY'
		
		WHEN SUBSTR(UPPER(creator_name),1,3) = 'GCB' THEN 'GCB'
		WHEN SUBSTR(UPPER(creator_name),1,3) = 'NCT' THEN 'NCT'
		ELSE SUBSTR(UPPER((SELECT flex_string03 FROM inv_unit WHERE gkey=tmp.unit_gkey)),1,3)
		END
		) AS yard 
		FROM 
		(
		SELECT unit_gkey,
		(SELECT line_op FROM inv_unit WHERE gkey=unit_gkey) AS line_op,
		(SELECT srv_event.creator FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
		WHERE srv_event.applied_to_gkey=unit_gkey AND srv_event_field_changes.new_value IS NOT NULL
		AND srv_event_field_changes.new_value LIKE'%BDT' AND srv_event.event_type_gkey=4
		ORDER BY srv_event.gkey DESC fetch first 1 rows only) AS creator_name
		
		FROM inv_unit_fcy_visit 
		WHERE $searchVal  BETWEEN to_date(concat('$fromdate',' 00:00:00'),'yyyy-mm-dd hh24-mi-ss') 
		AND to_date(concat('$todate',' 23:59:59'),'yyyy-mm-dd hh24-mi-ss') 
		)  tmp 
		INNER JOIN  ( ref_bizunit_scoped r  
		LEFT JOIN ( ref_agent_representation X  
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
		ON r.gkey=X.bzu_gkey)  ON r.gkey = tmp.line_op
		)  fnl $yrd ORDER BY mlo ASC";
	$mlo_query_2=oci_parse($con_sparcsn4_oracle,$str2);
	oci_execute($mlo_query_2);
	$mlo_2=0;	
	$reff_20_86=0;
	$reff_20_96=0;
	$reff_40_86=0;
	$reff_40_86=0;
	$reff_40_96=0;
	$reff_45_96=0;
	$tot=0;
	while(($mlo_row_2=oci_fetch_object($mlo_query_2))!= false){
	$mlo_2++;

  /*$st="SELECT 
IFNULL(SUM(20_86),0) AS reff_20_86,
IFNULL(SUM(20_96),0) AS reff_20_96, 
IFNULL(SUM(40_86),0) AS reff_40_86,
IFNULL(SUM(40_96),0) AS reff_40_96,
IFNULL(SUM(20_86_mty),0) AS reff_20_86_mty, 
IFNULL(SUM(20_96_mty),0) AS reff_20_96_mty,
IFNULL(SUM(40_86_mty),0) AS reff_40_86_mty, 
IFNULL(SUM(40_96_mty),0) AS reff_40_96_mty 
FROM (

SELECT (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) = '20' 
AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind IN ('FCL','LCL') 
THEN 1 ELSE NULL END) AS 20_86, 
(CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) = '20' 
AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)>'86' AND freight_kind IN ('FCL','LCL') 
THEN 1 ELSE NULL END) AS 20_96,
(CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) > '20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' 
AND freight_kind IN ('FCL','LCL') THEN 1 ELSE NULL END) AS 40_86,
 
(CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) > '20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)>'86'
AND freight_kind IN ('FCL','LCL') THEN 1 ELSE NULL END) AS 40_96,

 
(CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) = '20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' 
AND freight_kind='MTY' THEN 1 ELSE NULL END) AS 20_86_mty,
 (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) = '20' 
AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)>'86' AND freight_kind='MTY' THEN 1 ELSE NULL END) AS 20_96_mty,
(CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) > '20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86'
AND freight_kind='MTY' THEN 1 ELSE NULL END) AS 40_86_mty, 
(CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) > '20'
AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)>'86' AND freight_kind='MTY' THEN 1 ELSE NULL END) AS 40_96_mty, 


(CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)=20 AND freight_kind IN ('FCL','LCL') 
THEN 1 ELSE (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)>20 AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS onboard_LD_tues,
(CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)=20 AND freight_kind='MTY' THEN 1
ELSE (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)>20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS onboard_MT_tues,
(SELECT sparcsn4.srv_event.creator FROM sparcsn4.srv_event 
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL
AND sparcsn4.srv_event_field_changes.new_value LIKE'%BDT' AND sparcsn4.srv_event.event_type_gkey=4
 ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1) AS creator_name,
(
SELECT 
CASE
    WHEN SUBSTR(UCASE(creator_name),1,3) = 'CCT' THEN 'CCT'
	WHEN SUBSTR(UCASE(creator_name),1,3) = 'OFY' THEN 'OFY'
    WHEN SUBSTR(UCASE(creator_name),1,3) = 'GCB' THEN 'GCB'
    WHEN SUBSTR(UCASE(creator_name),1,3) = 'NCT' THEN 'NCT'
    ELSE SUBSTR(UCASE(inv_unit.flex_string03),1,3)
END

) AS yard 
FROM sparcsn4.inv_unit
INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit.gkey=sparcsn4.inv_unit_fcy_visit.unit_gkey 

INNER JOIN  ( sparcsn4.ref_bizunit_scoped r  
LEFT JOIN ( sparcsn4.ref_agent_representation X  
LEFT JOIN sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
ON r.gkey=X.bzu_gkey)  ON r.gkey = sparcsn4.inv_unit.line_op
WHERE $searchVal BETWEEN concat('$fromdate',' 00:00:00') AND concat('$todate',' 23:59:59') AND sparcsn4.inv_unit.category='IMPRT'
AND r.id ='$mlo_row_2->mlo' 
) AS tmp $yrd";*/

$strSummary="SELECT 
NVL(SUM(a_20_86),0) AS reff_20_86,
NVL(SUM(a_20_96),0) AS reff_20_96, 
NVL(SUM(a_40_86),0) AS reff_40_86,
NVL(SUM(a_40_96),0) AS reff_40_96,
NVL(SUM(a_20_86_mty),0) AS reff_20_86_mty, 
NVL(SUM(a_20_96_mty),0) AS reff_20_96_mty,
NVL(SUM(a_40_86_mty),0) AS reff_40_86_mty, 
NVL(SUM(a_40_96_mty),0) AS reff_40_96_mty 
FROM (
SELECT * FROM (
SELECT tmp.*,
(CASE
WHEN SUBSTR(UPPER(creator_name),1,3) = 'CCT' THEN 'CCT'
WHEN SUBSTR(UPPER(creator_name),1,3) = 'OFY' THEN 'OFY'
WHEN SUBSTR(UPPER(creator_name),1,3) = 'GCB' THEN 'GCB'
WHEN SUBSTR(UPPER(creator_name),1,3) = 'NCT' THEN 'NCT'
ELSE SUBSTR(UPPER(flex_string03),1,3)
END) AS yard 

FROM (
SELECT inv_unit.flex_string03, (CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' 
AND SUBSTR(ref_equip_type.nominal_height,-2)='86' AND freight_kind IN ('FCL','LCL') 
THEN 1 ELSE NULL END) AS a_20_86, 
(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' 
AND SUBSTR(ref_equip_type.nominal_height,-2)>'86' AND freight_kind IN ('FCL','LCL') 
THEN 1 ELSE NULL END) AS a_20_96,
(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) > '20' AND SUBSTR(ref_equip_type.nominal_height,-2)='86' 
AND freight_kind IN ('FCL','LCL') THEN 1 ELSE NULL END) AS a_40_86,

(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) > '20' AND SUBSTR(ref_equip_type.nominal_height,-2)>'86'
AND freight_kind IN ('FCL','LCL') THEN 1 ELSE NULL END) AS a_40_96,


(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2)='86' 
AND freight_kind='MTY' THEN 1 ELSE NULL END) AS a_20_86_mty,
(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' 
AND SUBSTR(ref_equip_type.nominal_height,2)>'86' AND freight_kind='MTY' THEN 1 ELSE NULL END) AS a_20_96_mty,
(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) > '20' AND SUBSTR(ref_equip_type.nominal_height,-2)='86'
AND freight_kind='MTY' THEN 1 ELSE NULL END) AS a_40_86_mty, 
(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) > '20'
AND SUBSTR(ref_equip_type.nominal_height,-2)>'86' AND freight_kind='MTY' THEN 1 ELSE NULL END) AS a_40_96_mty, 


(CASE WHEN SUBSTR(ref_equip_type.nominal_length,2)=20 AND freight_kind IN ('FCL','LCL') 
THEN 1 ELSE (CASE WHEN SUBSTR(ref_equip_type.nominal_length,2)>20 AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS onboard_LD_tues,
(CASE WHEN SUBSTR(ref_equip_type.nominal_length,2)=20 AND freight_kind='MTY' THEN 1
ELSE (CASE WHEN SUBSTR(ref_equip_type.nominal_length,2)>20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS onboard_MT_tues,
(SELECT srv_event.creator FROM srv_event 
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event_field_changes.new_value IS NOT NULL
AND srv_event_field_changes.new_value LIKE'%BDT' AND srv_event.event_type_gkey=4
ORDER BY srv_event.gkey DESC fetch first 1 rows only) AS creator_name

FROM inv_unit

INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
INNER JOIN inv_unit_fcy_visit ON inv_unit.gkey=inv_unit_fcy_visit.unit_gkey 

INNER JOIN  ( ref_bizunit_scoped r  
LEFT JOIN ( ref_agent_representation X  
LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
ON r.gkey=X.bzu_gkey)  ON r.gkey = inv_unit.line_op
WHERE $searchVal BETWEEN to_date(concat('$fromdate',' 00:00:00'),'yyyy-mm-dd hh24-mi-ss') 
AND to_date(concat('$todate',' 23:59:59'),'yyyy-mm-dd hh24-mi-ss')  
AND inv_unit.category='IMPRT'
AND r.id ='$mlo_row_2->MLO' 
)  tmp  ) tbl $yrd )tbl1";

//echo $st;
$summary_query_2=oci_parse($con_sparcsn4_oracle,$strSummary);
oci_execute($summary_query_2);
$sum=0;	

while(($summary_row_2=oci_fetch_object($summary_query_2))!=false){
$sum++;

?>

	<tr>
		<td align="center"><?php echo $mlo_row_2->MLO; ?></td>
		<td align="center"><?php echo $mlo_row_2->AGENT.'-'.$mlo_row_2->AGENT_NAME; ?></td>
		<td align="center"><?php $reff_20_86+=$summary_row_2->REFF_20_86; echo $summary_row_2->REFF_20_86; ?></td>
		<td align="center"><?php $reff_20_96+=$summary_row_2->REFF_20_96; echo $summary_row_2->REFF_20_86; ?></td>
		<td align="center"><?php $reff_40_86+=$summary_row_2->REFF_40_86; echo $summary_row_2->REFF_40_86; ?></td>
		<td align="center"><?php $reff_40_96+=$summary_row_2->REFF_40_96; echo $summary_row_2->REFF_40_96; ?></td>
		<!--td align="center"><?php $REFF_45_96+=$summary_row_2->REFF_45_96; echo $summary_row_2->REFF_45_96; ?></td-->
		<td align="center"><?php $tot+=$summary_row_2->REFF_20_86 + $summary_row_2->REFF_20_96 + $summary_row_2->REFF_40_86 + $summary_row_2->REFF_40_96 ;  echo $summary_row_2->REFF_20_86 + $summary_row_2->REFF_20_96 + $summary_row_2->REFF_40_86 + $summary_row_2->REFF_40_96 ; ?></td>
	</tr>
	

	
	<?php } ?>

	<?php } ?>
	<tr>
		<td align="center" colspan="2"><b>Total:</b></td>
		<td align="center"><?php echo $reff_20_86; ?></td>
		<td align="center"><?php echo $reff_20_96; ?></td>
		<td align="center"><?php echo $reff_40_86; ?></td>
		<td align="center"><?php echo $reff_40_96; ?></td>
		<!--td align="center"><?php echo $reff_45_96; ?></td-->
		<td align="center"><?php echo $tot; ?></td>
	</tr>

</table>
 <?php
		if($_POST['options'] == 'html'){
			echo "<table align='center' style='width:70%;' class='table table-bordered table-responsive table-hover table-striped mb-none'>";
		}else if($_POST['options'] == 'xl'){
			echo "<table width='70%' border='1' cellpadding='0' cellspacing='0' align='center'>";
		}
	?>

	

</table>


<br />
<br />
<table width="10%" border="0"  align="right">
	
	<tr>
		
		<td  style="border: none; font-size:20px"><b>----------------------------------</b></td>
	</tr>	
	<tr>	
		<?php if ($yard_no=='NCT'){ ?>
		<td><b>Sub-Asstt. Engineer(E)/NCT</b></td>
		<?php } else if ($yard_no=='CCT'){ ?>
		<td><b>Sub-Asstt. Engineer(E)/NCT</b></td>
		<?php } else { ?>
		<td><b>Sr. Sub-Asstt. Engineer(E)/CGD</b></td>
		<?php } ?>
	</tr>
</table>


<?php 
mysqli_close($con_sparcsn4);
if(@$_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
