<?php

	putenv('TZ=Asia/Dhaka');
	
	include("dbConection.php");
	/* $sql=mysqli_query($con_sparcsn4,"select vvd_gkey from sparcsn4.vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	$row=mysqli_fetch_object($sql);
	$vvdGkey=$row->vvd_gkey; */
	
	include("dbOracleConnection.php");
					
			
	$get_vvd_gkey_query = "select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'";
	$get_vvd_gkey_query_result = oci_parse($con_sparcsn4_oracle, $get_vvd_gkey_query);
	oci_execute($get_vvd_gkey_query_result);
	$vvdGkey ="";
	if(($vvd_gkey_row= oci_fetch_object($get_vvd_gkey_query_result)) != false){
		$vvdGkey = $vvd_gkey_row->VVD_GKEY;
	}
	
	
	
	
	
	
	
	
	$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 
	$rot = str_replace('/','-',$ddl_imp_rot_no);
	$myFile = "ExportCommodity-".$rot.".xml";
	
	if(file_exists($_SERVER['DOCUMENT_ROOT']."/myportpanel/resources/Commodity/".$myFile))
	{
			unlink($_SERVER['DOCUMENT_ROOT']."/myportpanel/resources/Commodity/".$myFile);
	}
	
	$fh= fopen($_SERVER['DOCUMENT_ROOT']."/myportpanel/resources/Commodity/".$myFile , 'a');




	
	 // $query=mysqli_query($con_sparcsn4,"SELECT sparcsn4.inv_unit.id AS contNo,CASE WHEN sparcsn4.inv_unit.category ='EXPRT' THEN 'EXPORT' ELSE NULL END AS category,
	 // sparcsn4.inv_unit_fcy_visit.transit_state,sparcsn4.inv_unit.freight_kind,
	 // (SELECT ctmsmis.mis_inv_unit.mlo FROM ctmsmis.mis_inv_unit WHERE ctmsmis.mis_inv_unit.gkey=sparcsn4.inv_unit.gkey) AS mlo,
	 // sparcsn4.argo_carrier_visit.id AS vslId,
	 // ctmsmis.mis_exp_unit_preadv_req.commodity_code,
	 
	 // (SELECT ctmsmis.commudity_detail.commudity_desc FROM ctmsmis.commudity_detail WHERE ctmsmis.commudity_detail.commudity_code=ctmsmis.mis_exp_unit_preadv_req.commodity_code) AS commudity_desc
	 // FROM sparcsn4.inv_unit
	 // INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
	 // INNER JOIN ctmsmis.mis_exp_unit_preadv_req ON ctmsmis.mis_exp_unit_preadv_req.gkey=sparcsn4.inv_unit.gkey
	 // INNER JOIN sparcsn4.argo_carrier_visit ON (argo_carrier_visit.gkey=inv_unit.cv_gkey OR argo_carrier_visit.gkey=inv_unit.declrd_ib_cv)
	 // INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
		// WHERE sparcsn4.vsl_vessel_visit_details.vvd_gkey='$vvdGkey'");
		


		
	$get_inv_unit_and_related_data_query = "SELECT inv_unit.id AS contNo,CASE WHEN inv_unit.category ='EXPRT' THEN 'EXPORT' ELSE NULL END AS category,
 inv_unit_fcy_visit.transit_state,inv_unit.freight_kind,ref_bizunit_scoped.id AS mlo
 FROM inv_unit
 LEFT JOIN ref_bizunit_scoped  ON inv_unit.line_op= ref_bizunit_scoped.gkey
 INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
 INNER JOIN argo_carrier_visit ON (argo_carrier_visit.gkey=inv_unit.cv_gkey OR argo_carrier_visit.gkey=inv_unit.declrd_ib_cv)
 INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
 WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
	$get_inv_unit_and_related_data_query_result = oci_parse($con_sparcsn4_oracle, $get_inv_unit_and_related_data_query);
	oci_execute($get_vvd_gkey_query_result);



 
	$title = "<?xml version='1.0' encoding='UTF-8'?>\n";
	fwrite($fh, $title);
	$headingstart = '<argo:snx xmlns:argo="http://www.navis.com/argo" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.navis.com/argo snx.xsd">'."\n";
	fwrite($fh, $headingstart);

	while(@$row=mysqli_fetch_object($query)){

	$contDatastart	='<unit update-mode="REPLACE"  id="'.$row->contNo.'" category="EXPORT" restow="NONE" transit-state="INBOUND" freight-kind="'.$row->freight_kind.'" line="'.$row->mlo.'" unique-key="'.$row->contNo.'">'."\n";
	fwrite($fh, $contDatastart);

	$routing = '<routing pod-1="CGP" pod-1-name="Bangladesh" destination="2591" origin="'.$row->freight_kind.'">'."\n";
	fwrite($fh, $routing);
	$carrier1 = '<carrier direction="IB" qualifier="DECLARED" mode="TRUCK" />'."\n";
	fwrite($fh, $carrier1);
	$carrier2 = '<carrier direction="IB" qualifier="ACTUAL" mode="TRUCK" />'."\n";
	fwrite($fh, $carrier2);
	$carrier3 = '<carrier direction="OB" qualifier="DECLARED" mode="VESSEL" id="'.$row->vsl_id.'" />'."\n";
	fwrite($fh, $carrier3);
	$carrier4 = '<carrier direction="OB" qualifier="ACTUAL" mode="VESSEL" id="'.$row->vsl_id.'" />'."\n";
	fwrite($fh, $carrier4);
	$routingend = "</routing>\n";
	fwrite($fh, $routingend);

	$commodity = '<contents commodity-id="'.$row->commodity_code.'" commodity-name="'.$row->commudity_desc.'"  />'."\n";
	fwrite($fh, $commodity);
	$contDataend = "</unit>\n";
	fwrite($fh, $contDataend);
	}
	$headingEnd = "</argo:snx>\n";
	fwrite($fh, $headingEnd);
	fclose($fh);

if (file_exists($_SERVER['DOCUMENT_ROOT']."/myportpanel/resources/Commodity/".$myFile))
 {
  $file = $_SERVER['DOCUMENT_ROOT']."/myportpanel/resources/Commodity/".$myFile;
  $fp = fopen($file, 'rb');
  $myFile = str_replace(' ','-',$myFile);
  header("Content-Type: application/octet-stream");
  header("Content-Disposition: attachment; filename=".basename($myFile));
  header("Content-Length: ". filesize($file ));
  fpassthru($fp);
  fclose($fp);
  exit;
 }
 else
 {
  echo "File does not exists";
 }
	

mysqli_close($con_sparcsn4);
	
 
?>

