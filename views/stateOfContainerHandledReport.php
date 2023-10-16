<?php
	include_once("mydbPConnectionn4.php");
	include("dbOracleConnection.php");	
	
	 $str="select vsl_vessels.name as vsl_name,NVL(vsl_vessel_visit_details.flex_string02,NVL(vsl_vessel_visit_details.flex_string03,'')) as berth_op,
	 NVL(argo_quay.id,'') as berth,argo_carrier_visit.ata,argo_carrier_visit.atd from vsl_vessel_visit_details
	 inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	 inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	 inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	 inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
	 where vsl_vessel_visit_details.ib_vyg='$rotation'";
	
		$sql1=oci_parse($con_sparcsn4_oracle,$str);
		oci_execute($sql1);
	    $row1=oci_fetch_object($sql1);
	
	
	
	
		$sql2=oci_parse($con_sparcsn4_oracle,"SELECT agent,
		NVL(SUM(Load_Normal_20),0) AS Load_Normal_20,
		NVL(SUM(Load_diff_20),0) AS Load_diff_20,
		NVL(SUM(Load_Normal_MTY_20),0) AS Load_Normal_MTY_20,
		NVL(SUM(Load_diff_MTY_20),0) AS Load_diff_MTY_20,
		NVL(SUM(Load_Normal_40_45),0) AS Load_Normal_40_45,
		NVL(SUM(Load_diff_40_45),0) AS Load_diff_40_45,
		NVL(SUM(Load_Normal_MTY_40_45),0) AS Load_Normal_MTY_40_45,
		NVL(SUM(Load_diff_MTY_40_45),0) AS Load_diff_MTY_40_45
		FROM (
		
		
		SELECT DISTINCT inv_unit.gkey AS gkey, 
		ref_bizunit_scoped.id AS agent, 
		ref_bizunit_scoped.name  AS agent_name,
		(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20'  AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC')  THEN 1  
		ELSE NULL END) AS Load_Normal_20,
		(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC')  THEN 1  
		ELSE NULL END) AS Load_diff_20, 
		
		
		(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC') THEN 1  
		ELSE NULL END) AS Load_Normal_MTY_20, 
		
		(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20'  AND freight_kind ='MTY' AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC') THEN 1  
		ELSE NULL END) AS Load_diff_MTY_20, 
		
		
		(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >= 40  AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC')  THEN 1  
		ELSE NULL END) AS Load_Normal_40_45,
		(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >=40 AND  freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC')  THEN 1  
		ELSE NULL END) AS Load_diff_40_45, 
		
		
		
		(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >=40 AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC') THEN 1  
		ELSE NULL END) AS Load_Normal_MTY_40_45, 
		
		(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >=40 AND freight_kind ='MTY' AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC') THEN 1  
		ELSE NULL END) AS Load_diff_MTY_40_45 
		
		FROM inv_unit
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
		INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		INNER JOIN argo_carrier_visit ON  inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
		INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
		WHERE  vsl_vessel_visit_details.ib_vyg='$rotation' AND inv_unit.category='IMPRT' AND inv_unit_fcy_visit.time_in IS NOT NULL  AND ref_bizunit_scoped.id IS NOT NULL
		)  tmp GROUP BY agent");
		oci_execute($sql2);
	$row2=oci_fetch_object($sql2);
	
	
	$sql3=oci_parse($con_sparcsn4_oracle,"SELECT agent,
	NVL(SUM(Load_Normal_20),0) AS Load_Normal_20,
	NVL(SUM(Load_diff_20),0) AS Load_diff_20,
	NVL(SUM(Load_Normal_MTY_20),0) AS Load_Normal_MTY_20,
	NVL(SUM(Load_diff_MTY_20),0) AS Load_diff_MTY_20,
	NVL(SUM(Load_Normal_40_45),0) AS Load_Normal_40_45,
	NVL(SUM(Load_diff_40_45),0) AS Load_diff_40_45,
	NVL(SUM(Load_Normal_MTY_40_45),0) AS Load_Normal_MTY_40_45,
	NVL(SUM(Load_diff_MTY_40_45),0) AS Load_diff_MTY_40_45
	FROM (
	
	
	SELECT DISTINCT inv_unit.gkey AS gkey, 
	ref_bizunit_scoped.id AS agent, 
	ref_bizunit_scoped.name  AS agent_name,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20'  AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC')  THEN 1  
	ELSE NULL END) AS Load_Normal_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC')  THEN 1  
	ELSE NULL END) AS Load_diff_20, 
	
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC') THEN 1  
	ELSE NULL END) AS Load_Normal_MTY_20, 
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20'  AND freight_kind ='MTY' AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC') THEN 1  
	ELSE NULL END) AS Load_diff_MTY_20, 
	
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >= 40  AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC')  THEN 1  
	ELSE NULL END) AS Load_Normal_40_45,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >=40 AND  freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC')  THEN 1  
	ELSE NULL END) AS Load_diff_40_45, 
	
	
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >=40 AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC') THEN 1  
	ELSE NULL END) AS Load_Normal_MTY_40_45, 
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >=40 AND freight_kind ='MTY' AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC') THEN 1  
	ELSE NULL END) AS Load_diff_MTY_40_45 
	
	FROM inv_unit
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
	INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON  inv_unit_fcy_visit.actual_ob_cv=argo_carrier_visit.gkey
	INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
	WHERE  vsl_vessel_visit_details.ob_vyg='$rotation'  AND inv_unit.category='EXPRT' AND inv_unit_fcy_visit.last_pos_loctype='VESSEL' AND ref_bizunit_scoped.id IS NOT NULL
	)  tmp GROUP BY agent");
	oci_execute($sql3);
	$row3=oci_fetch_object($sql3);
	
	//Import hold container statement
$totalRow=0;
$ctmsStr="SELECT unit_gkey FROM  ctmsmis.import_container_hold_shifting
WHERE ctmsmis.import_container_hold_shifting.rotation='$rotation'";
$ctmsQuery=mysqli_query($con_sparcsn4,$ctmsStr);
$totalRow=mysqli_num_rows($ctmsQuery);
$unitGkeyList="";
$i=0;
while($rowUnit = mysqli_fetch_object($ctmsQuery)){
	$unitGkey="";
	$unitGkey=$rowUnit->unit_gkey;

	if($i==$totalRow-1){
		$unitGkeyList=$unitGkeyList."'".$unitGkey."'";
	}
	else{
		$unitGkeyList=$unitGkeyList."'".$unitGkey."',";

	}
	$i++;


}
	$sql4=oci_parse($con_sparcsn4_oracle,"SELECT agent,
	NVL(SUM(Load_Normal_20),0) AS Load_Normal_20,
	NVL(SUM(Load_diff_20),0) AS Load_diff_20,
	NVL(SUM(Load_Normal_MTY_20),0) AS Load_Normal_MTY_20,
	NVL(SUM(Load_diff_MTY_20),0) AS Load_diff_MTY_20,
	NVL(SUM(Load_Normal_40_45),0) AS Load_Normal_40_45,
	NVL(SUM(Load_diff_40_45),0) AS Load_diff_40_45,
	NVL(SUM(Load_Normal_MTY_40_45),0) AS Load_Normal_MTY_40_45,
	NVL(SUM(Load_diff_MTY_40_45),0) AS Load_diff_MTY_40_45
	FROM (

	SELECT DISTINCT inv_unit_fcy_visit.unit_gkey AS gkey, 
	ref_bizunit_scoped.id AS agent, 
	ref_bizunit_scoped.name  AS agent_name,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20'  AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC')  THEN 1  
	ELSE NULL END) AS Load_Normal_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC')  THEN 1  
	ELSE NULL END) AS Load_diff_20, 


	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC') THEN 1  
	ELSE NULL END) AS Load_Normal_MTY_20, 

	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20'  AND freight_kind ='MTY' AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC') THEN 1  
	ELSE NULL END) AS Load_diff_MTY_20, 


	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >= 40  AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC')  THEN 1  
	ELSE NULL END) AS Load_Normal_40_45,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >=40 AND  freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC')  THEN 1  
	ELSE NULL END) AS Load_diff_40_45, 



	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >=40 AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('UT', 'PF', 'PC') THEN 1  
	ELSE NULL END) AS Load_Normal_MTY_40_45, 

	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) >=40 AND freight_kind ='MTY' AND ref_equip_type.iso_group  IN ('UT', 'PF', 'PC') THEN 1  
	ELSE NULL END) AS Load_diff_MTY_40_45 

	FROM inv_unit
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
	INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON  inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
	INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
	WHERE inv_unit_fcy_visit.unit_gkey IN ($unitGkeyList)  AND ref_bizunit_scoped.id IS NOT NULL
	)  tmp GROUP BY agent");

	@$row4=oci_fetch_object($sql4);
	
?>	
	

	<table width="100%" style="border-collapse: collapse;"  align="center"  >
	
		
     <thead>
   
        <tr>			
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"class="text-center" colspan="17"><img src="<?php echo IMG_PATH?>cpanew.jpg"></th>
		</tr>
			
		</tr>   
		<tr>			
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center"class="text-center" colspan="17"> OFFICE OF THE TERMINAL MANAGER<br><br></th>
			
		</tr>
		
		<tr>			
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="17" >STATEMENT OF CONTAINER HANDLED<br> DT 26/11/2022 </th>
		</tr>
		</thead>	
		<tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="4"><b>Vessel Name</b></td>	
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="13"><?php echo $row1->VSL_NAME; ?></td>			
		 </tr>
         <tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="4" ><b>Imp.Rot.No</b></td>	
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="13"><?php echo $rotation; ?></td>			
		 </tr>
         <tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="4" ><b>Exp.Rot.No</b></td>	
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="13"><?php echo $rotation; ?></td>			
		 </tr>
         <tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="4" ><b>Arrival Date</b></td>	
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="13"><?php echo $row1->ATA; ?></td>			
		 </tr>
         <tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="4" ><b>Sailing Date</b></td>	
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="13"><?php echo $row1->ATD; ?></td>			
		 </tr>
         <tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="4" ><b>Berth No</b></td>	
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="13"><?php echo $row1->BERTH; ?></td>			
		 </tr>
         <tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="4" ><b>Shipping Agent</b></td>	
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="left" class="text-center" colspan="13"><?php echo $row2->AGENT_NAME." ( ".$row2->AGENT.")"; ?></td>			
		 </tr>
   
	</table>
	<br/>
	<br/>
	<table width="100%" style="border-collapse: collapse;" align="center" >
	
		
     <thead>
   
		<!--tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan=""><font size="6"><b>Import Summary Report</b></font></th>
		</tr-->
		
		<tr>			
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"  ></th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="4" >20' LOAD </th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="4" >20' EMPTY </th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="4" >40'/45 LOAD </th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="4" >40'/45 Empty </th>
			
		</tr>
		</thead>	
		<tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"  ></td>			
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" ><b>JETTY SIDE</b></td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" ><b>OVER SIDE<b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" ><b>JETTY SIDE</b></td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" ><b>OVER SIDE<b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" ><b>JETTY SIDE</b></td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" ><b>OVER SIDE<b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" ><b>JETTY SIDE</b></td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" ><b>OVER SIDE<b></td>
         </tr>
		<tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>			
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>NORMAL</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>F/R,O/T,S/O</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>NORMAL</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>F/R,O/T,S/O</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>NORMAL</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>F/R,O/T,S/O</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>NORMAL</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>F/R,O/T,S/O</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>NORMAL</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>F/R,O/T,S/O</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>NORMAL</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>F/R,O/T,S/O</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>NORMAL</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>F/R,O/T,S/O</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>NORMAL</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>F/R,O/T,S/O</b></td>

		</tr>
		<tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>IMPORT</b></td>			
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row2->LOAD_NORMAL_20 + @$row4->LOAD_NORMAL_20)>0) echo $row2->LOAD_NORMAL_20 + @$row4->LOAD_NORMAL_20 ; ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row2->LOAD_DIFF_20 + @$row4->LOAD_DIFF_20)>0) echo $row2->LOAD_DIFF_20 + @$row4->LOAD_DIFF_20; ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php  if(($row2->LOAD_NORMAL_MTY_20  + @$row4->LOAD_NORMAL_MTY_20)>0) echo $row2->LOAD_NORMAL_MTY_20 + @$row4->LOAD_NORMAL_MTY_20; ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php  if(($row2->LOAD_DIFF_MTY_20 + @$row4->LOAD_DIFF_MTY_20)>0) echo $row2->LOAD_DIFF_MTY_20 + @$row4->LOAD_DIFF_MTY_20;  ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row2->LOAD_NORMAL_40_45  + @$row4->LOAD_NORMAL_40_45)>0) echo $row2->LOAD_NORMAL_40_45 + @$row4->LOAD_NORMAL_40_45 ; ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row2->LOAD_DIFF_40_45 + @$row4->LOAD_DIFF_40_45)>0) echo $row2->LOAD_DIFF_40_45 + @$row4->LOAD_DIFF_40_45 ;  ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row2->LOAD_NORMAL_MTY_40_45 + @$row4->LOAD_NORMAL_MTY_40_45)>0) echo $row2->LOAD_NORMAL_MTY_40_45 + @$row4->LOAD_NORMAL_MTY_40_45 ;  ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row2->LOAD_DIFF_MTY_40_45 + @$row4->LOAD_DIFF_MTY_40_45)>0) echo $row2->LOAD_DIFF_MTY_40_45 + @$row4->LOAD_DIFF_MTY_40_45 ;  ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>

		</tr>
        <tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><b>EXPORT</b></td>			
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row3->LOAD_NORMAL_20 + @$row4->LOAD_NORMAL_20)>0) echo $row3->LOAD_NORMAL_20 + @$row4->LOAD_NORMAL_20 ; ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row3->LOAD_DIFF_20 + @$row4->LOAD_DIFF_20)>0) echo $row3->LOAD_DIFF_20 + @$row4->LOAD_DIFF_20 ;  ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row3->LOAD_NORMAL_MTY_20 + @$row4->LOAD_NORMAL_MTY_20)>0) echo $row3->LOAD_NORMAL_MTY_20 + @$row4->LOAD_NORMAL_MTY_20 ; ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row3->LOAD_DIFF_MTY_20 + @$row4->LOAD_DIFF_MTY_20)>0) echo $row3->LOAD_DIFF_MTY_20 + @$row4->LOAD_DIFF_MTY_20 ;  ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row3->LOAD_NORMAL_40_45 + @$row4->LOAD_NORMAL_40_45)>0) echo $row3->LOAD_NORMAL_40_45 + @$row4->LOAD_NORMAL_40_45 ;   ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row3->LOAD_DIFF_40_45 + @$row4->LOAD_DIFF_40_45)>0) echo $row3->LOAD_DIFF_40_45 + @$row4->LOAD_DIFF_40_45 ; ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row3->LOAD_NORMAL_MTY_40_45 + @$row4->LOAD_NORMAL_MTY_40_45)>0) echo $row3->LOAD_NORMAL_MTY_40_45 + @$row4->LOAD_NORMAL_MTY_40_45 ;  ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ><?php if(($row3->LOAD_DIFF_MTY_40_45 + @$row4->LOAD_DIFF_MTY_40_45)>0) echo $row3->LOAD_DIFF_MTY_40_45 + @$row4->LOAD_DIFF_MTY_40_45 ;  ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" ></td>


		</tr>
        <tr>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b>TOTAL</b></td>			
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b><?php //if(($row2->Load_Normal_20 + $row3->Load_Normal_20 + @$row4->Load_Normal_20 )>0) 
				echo $row2->LOAD_NORMAL_20 + $row3->LOAD_NORMAL_20 + @$row4->LOAD_NORMAL_20; ?> </b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b><?php // if(($row2->Load_diff_20 + $row3->Load_diff_20 + @$row4->Load_diff_20 )>0) 
				echo $row2->LOAD_DIFF_20 + $row3->LOAD_DIFF_20 + @$row4->LOAD_DIFF_20;  ?></b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b>0</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b>0</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b><?php //if(($row2->Load_Normal_MTY_20 + $row3->Load_Normal_MTY_20 + @$row4->Load_Normal_MTY_20 )>0)  
				echo $row2->LOAD_NORMAL_MTY_20 + $row3->LOAD_NORMAL_MTY_20 + @$row4->LOAD_NORMAL_MTY_20; ?></b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b><?php //if(($row2->Load_diff_MTY_20 + $row3->Load_diff_MTY_20 + @$row4->Load_diff_MTY_20 )>0)  
				echo $row2->LOAD_DIFF_MTY_20 + $row3->LOAD_DIFF_MTY_20 + @$row4->LOAD_DIFF_MTY_20;   ?></b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b>0</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b>0</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b><?php //if(($row2->Load_Normal_40_45 + $row3->Load_Normal_40_45 + @$row4->Load_Normal_40_45 )>0) 
				echo $row2->LOAD_NORMAL_40_45 + $row3->LOAD_NORMAL_40_45 + @$row4->LOAD_NORMAL_40_45; ?></b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b><?php //if(($row2->Load_diff_40_45 + $row3->Load_diff_40_45 + @$row4->Load_diff_40_45 )>0)  
				echo $row2->LOAD_DIFF_40_45 + $row3->LOAD_DIFF_40_45 + @$row4->LOAD_DIFF_40_45; ?></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b>0</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b>0</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b><?php //if(($row2->Load_Normal_MTY_40_45 + $row3->Load_Normal_MTY_40_45 + @$row4->Load_Normal_MTY_40_45 )>0)  
				echo $row2->LOAD_NORMAL_MTY_40_45 + $row3->LOAD_NORMAL_MTY_40_45 + @$row4->LOAD_NORMAL_MTY_40_45;  ?></b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b><?php //if(($row2->Load_diff_MTY_40_45 + $row3->Load_diff_MTY_40_45 + @$row4->Load_diff_MTY_40_45 )>0)  
				echo $row2->LOAD_DIFF_MTY_40_45 + $row3->LOAD_DIFF_MTY_40_45 + @$row4->LOAD_DIFF_MTY_40_45;  ?></b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b>0</b></td>
            <td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center"><b>0</b></td>

		</tr>
		
		<?php 
			 $str_note="SELECT  
				(CASE 
				WHEN  cont_size=20 THEN cont_size 
				WHEN cont_size=40 THEN cont_size 
				ELSE  cont_size END) AS cont_size,
				COUNT(*) AS num 
				FROM ctmsmis.import_container_hold_shifting 
				WHERE  ctmsmis.import_container_hold_shifting.rotation='$rotation'
				GROUP BY cont_size";
	
				$sql_note=mysqli_query($con_sparcsn4,$str_note);
				//$row_note=mysqli_fetch_object($sql_note);
				
				
		?>
		
		<table width="100%" border="0" align="center" >
			<tr >			
				<th align="left" class="text-center" colspan="3"> REMARKS: ALL WORK DONE BY GANTRY CRANE</th>			
			</tr>
			
			<tr>			
				<td align="left" class="text-center"  colspan="3"> N.B.- </td>			
			</tr>
		<?php
				$i=0;
				while ($row_note=mysqli_fetch_object($sql_note))
				{
					$i++;
				?>
			<tr>			
				<td align="left"  colspan="3"> ( <?php echo $row_note->num.' X '.$row_note->cont_size; ?> ' ) </td>			
			</tr>
		<?php } ?>
			<tr>			
				<td align="left"  colspan="3"> CONTAINERS DISCHARGED ON TRAILER & LOADED FOR BAY TO BAY SHIFTING </td>			
			</tr>
			
			
			<tr>			
				<td align="left" class="text-center"  colspan="3"><br/> </td>			
			</tr>
			
			<tr>			
				<td align="left" >N.B.-   &nbsp;&nbsp;&nbsp;&nbsp; F/R = FLAT RACK</td>			
				<td align="left"  ></td>			
				<td align="right" > TRAFFIC INSPECTOR/INCHARGE</td>			
			</tr>
			<tr>			
				<td align="left" > &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  O/T = OPEN TOP</td>			
				<td align="left"  ></td>			
				<td align="right" > CONTAINER BILLING SECTION</td>			
			</tr>
			<tr>			
				<td align="left"  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; S/O= SIDE OPEN</td>			
				<td align="left"  ></td>			
				<td align="right" > TERMINAL MANAGER'S OFFICE</td>			
			</tr>
			<tr>			
	
				<td   colspan="3" align="right"> <u>CHITTAGONG PORT AUTHORITY</u></td>			
			</tr>
       
		</table>
