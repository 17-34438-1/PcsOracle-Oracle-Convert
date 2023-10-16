
<?php
	include_once("mydbPConnectionn4.php");
	include("dbOracleConnection.php");
	$str="select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'";
	
	$sql=oci_parse($con_sparcsn4_oracle,$str);
	oci_execute($sql);
	$results=array();
	$rowNum =oci_fetch_all($sql, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	
	oci_free_statement($sql);
	
	$sql = oci_parse($con_sparcsn4_oracle,$str);
	oci_execute($sql);
	$row=oci_fetch_object($sql);
	
	if($rowNum>0){
	$vvdGkey=$row->VVD_GKEY;
	
		//$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	//$res=mysqli_query($con_sparcsn4,$sql);
	
	$sql1=oci_parse($con_sparcsn4_oracle,"select vsl_vessels.name as vsl_name,NVL(vsl_vessel_visit_details.flex_string02,NVL(vsl_vessel_visit_details.flex_string03,'')) as berth_op,
	NVL(argo_quay.id,'') as berth,argo_carrier_visit.ata,argo_carrier_visit.atd from vsl_vessel_visit_details
	inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
	where vsl_vessel_visit_details.vvd_gkey='$vvdGkey'");
	oci_execute($sql1);
	$vsl_name="";
	$arriveTime="";
	$sailedTime="";
	$berthTime="";
	while(($row1=oci_fetch_object($sql1))!=false){
		$vsl_name=$row1->VSL_NAME;
		$arriveTime=$row1->ATA;
		$sailedTime=$row1->ATD;
		$berthTime=$row1->BERTH;
	}
	oci_free_statement($sql1);
	
	?>
<html>


<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr  align="center" height="100px">
		<!--td width="30%" align="centre"><font size="5"><b> CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td-->
		<!--td align="center"><img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td-->
		<td width="70%" align="center">
			<table border=0 width="100%">
				<tr>
					<td colspan="11" align="center"><font size="4"><b> <u>MLO WISE DISCHARGING SUMMARY</u></b></font></td>
					
				</tr>
				<tr>
					<td ><font size="4">Vessel: -  </font></td>
					<td colspan="3" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $vsl_name; ?></b></font></td>
					<td><font size="4">VOY : - </font></td>
					<td colspan="3" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $voysNo; ?></b></font></td>
					<td ><font size="4">ROT/NO : -</font></td>
					<td colspan="3" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $ddl_imp_rot_no; ?></b></font></td>
				</tr>
				<tr>
					<td colspan="2"><font size="4">Arrived on : - </font></td>
					<td colspan="2" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $arriveTime; ?></b></font></td>
					<td colspan="2" ><font size="4">Sailed on : - </font></td>
					<td colspan="2" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $sailedTime; ?></b></font></td>
					<td colspan="2" ><font size="4">Berth No :- </font></td>
					<td colspan="2" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $berthTime; ?></b></font></td>
				</tr>
			</table>
		
		</td>
		
	</tr>
	

</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr>
		<td></td>
		<td colspan="11" align="center"><b>LADEN</b></td>
		<td colspan="11" align="center"><b>EMPTY</b></td>
		<td></td>
		<td></td>
	</tr>
	<tr height="40px">
		<td  align="center"><b>MLO'S</b></td>		
		<td  align="center"><b>2D</b></td>
		<td  align="center"><b>4D</b></td>
		<td  align="center"><b>4H</b></td>
		<td  align="center"><b>45H</b></td>		
		<td  align="center"><b>4RH</b></td>
		<td  align="center"><b>2RF</b></td>
		<td  align="center"><b>2OT</b></td>
		<td  align="center"><b>2FR</b></td>
		<td  align="center"><b>2TK</b></td>
		<td  align="center"><b>4FR</b></td>
		<td  align="center"><b>4OT</b></td>
		<td  align="center"><b>2D</b></td>
		<td  align="center"><b>4D</b></td>
		<td  align="center"><b>4H</b></td>
		<td  align="center"><b>45H</b></td>		
		<td  align="center"><b>4RH</b></td>
		<td  align="center"><b>2RF</b></td>
		<td  align="center"><b>2OT</b></td>
		<td  align="center"><b>2FR</b></td>
		<td  align="center"><b>2TK</b></td>
		<td  align="center"><b>4FR</b></td>
		<td  align="center"><b>4OT</b></td>				
		<td  align="center"><b>TOTAL CONTS</b></td>
		<td  align="center"><b>TOTAL TEUS</b></td>
	</tr>
	
	

<?php
	$query=oci_parse($con_sparcsn4_oracle,"SELECT mlo,
	NVL(SUM(D_20),0) AS D_20,
	NVL(SUM(D_40),0) AS D_40,
	NVL(SUM(H_40),0) AS H_40,
	NVL(SUM(H_45),0) AS H_45,
	
	NVL(SUM(R_20),0) AS R_20,
	NVL(SUM(RH_40),0) AS RH_40,
	
	NVL(SUM(OT_20),0) AS OT_20,
	NVL(SUM(OT_40),0) AS OT_40,
	
	NVL(SUM(FR_20),0) AS FR_20,
	NVL(SUM(FR_40),0) AS FR_40,
	
	NVL(SUM(TK_20),0) AS TK_20,
	
	NVL(SUM(MD_20),0) AS MD_20,
	NVL(SUM(MD_40),0) AS MD_40,
	NVL(SUM(MH_40),0) AS MH_40,
	NVL(SUM(MH_45),0) AS MH_45,
	
	NVL(SUM(MR_20),0) AS MR_20,
	NVL(SUM(MRH_40),0) AS MRH_40,
	
	NVL(SUM(MOT_20),0) AS MOT_20,
	NVL(SUM(MOT_40),0) AS MOT_40,
	
	NVL(SUM(MFR_20),0) AS MFR_20,
	NVL(SUM(MFR_40),0) AS MFR_40,
	
	NVL(SUM(MTK_20),0) AS MTK_20,
	
	NVL(SUM(grand_tot),0) AS grand_tot,
	NVL(SUM(tues),0) AS tues
	FROM (
	SELECT DISTINCT inv_unit.gkey AS gkey, 
	ref_bizunit_scoped.id AS mlo, 
	ref_bizunit_scoped.name  AS mlo_name,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS D_20, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS D_40, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND SUBSTR(ref_equip_type.nominal_height,-2) ='96' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS H_40, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '45' AND SUBSTR(ref_equip_type.nominal_height,-2) ='96' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS H_45,
	
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
	ELSE NULL END) AS R_20, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
	ELSE NULL END) AS RH_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('UT')  THEN 1  
	ELSE NULL END) AS OT_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('UT')  THEN 1  
	ELSE NULL END) AS OT_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
	ELSE NULL END) AS FR_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
	ELSE NULL END) AS FR_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('TN','TD','TG')  THEN 1  
	ELSE NULL END) AS TK_20,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,2) = '20' AND SUBSTR(ref_equip_type.nominal_height,2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS MD_20, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS MD_40, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND SUBSTR(ref_equip_type.nominal_height,-2) ='96' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS MH_40, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '45' AND SUBSTR(ref_equip_type.nominal_height,-2) ='96' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS MH_45,
	
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
	ELSE NULL END) AS MR_20, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
	ELSE NULL END) AS MRH_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('UT')  THEN 1  
	ELSE NULL END) AS MOT_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('UT')  THEN 1  
	ELSE NULL END) AS MOT_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
	ELSE NULL END) AS MFR_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
	ELSE NULL END) AS MFR_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('TN','TD','TG')  THEN 1  
	ELSE NULL END) AS MTK_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) IN('20','40','45','42')  THEN 1 ELSE NULL END) AS grand_tot,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2)='20'  THEN 1 ELSE 2 END) AS tues     
	FROM inv_unit
	
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
	INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON  inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
	INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	LEFT JOIN ref_bizunit_scoped  ON ref_bizunit_scoped.gkey = inv_unit.line_op
	WHERE  vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND inv_unit.category='IMPRT' AND inv_unit_fcy_visit.time_in IS NOT NULL  
	)  tmp GROUP BY mlo");
	oci_execute($query);
	$i=0;
	$j=0;
	
	$mlo="";
	
	$tot_D_20=""; 
	$tot_D_40=""; 
	$tot_H_40=""; 
	$tot_H_45=""; 
	$tot_RH_40=""; 
	$tot_R_20=""; 
	$tot_OT_20=""; 
	$tot_FR_20=""; 
	$tot_TK_20=""; 
	$tot_FR_40=""; 
	$tot_OT_40=""; 
	$tot_MD_20=""; 
	$tot_MD_40=""; 
	$tot_MH_40=""; 
	$tot_MH_45=""; 
	$tot_MRH_40=""; 
	$tot_MR_20=""; 
	$tot_MOT_20=""; 
	$tot_MFR_20=""; 
	$tot_MTK_20=""; 
	$tot_MFR_40=""; 
	$tot_MOT_40=""; 
	$tot_grand_tot=""; 
	$tot_tues=""; 
			
	if(!is_bool($query)){
	while(($row=oci_fetch_object($query))!=false){
	$i++;
	
		
	
?>
	<tr align="center">
		<td><?php if($row->MLO) { echo $row->MLO; } else echo "";?></td>
		<td><?php if($row->D_20 ) { echo $row->D_20; $tot_D_20 = $tot_D_20+$row->D_20; } else echo "&nbsp;";?></td>
		<td><?php if($row->D_40) { echo $row->D_40; $tot_D_40 = $tot_D_40+$row->D_40; } else echo "&nbsp;";?></td>
		<td><?php if($row->H_40) { echo $row->H_40; $tot_H_40 = $tot_H_40+$row->H_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row->H_45) { echo $row->H_45; $tot_H_45 = $tot_H_45+$row->H_45;  }  else echo "&nbsp;";?></td>
		
		<td><?php if($row->RH_40) { echo $row->RH_40; $tot_RH_40 = $tot_RH_40+$row->RH_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row->R_20) { echo $row->R_20; $tot_R_20 = $tot_R_20+$row->R_20;  }  else echo "&nbsp;";?></td>
		
		
		<td><?php if($row->OT_20) { echo $row->OT_20; $tot_OT_20 = $tot_OT_20+$row->OT_20;  }  else echo "&nbsp;";?></td>
		<td><?php if($row->FR_20) { echo $row->FR_20; $tot_FR_20 = $tot_FR_20+$row->FR_20;  }  else echo "&nbsp;";?></td>
		<td><?php if($row->TK_20) { echo $row->TK_20; $tot_TK_20 = $tot_TK_20+$row->TK_20;  }  else echo "&nbsp;";?></td>
		
		<td><?php if($row->FR_40) { echo $row->FR_40; $tot_FR_40 = $tot_FR_40+$row->FR_40;  } else echo "&nbsp;";?></td>
		<td><?php if($row->OT_40) { echo $row->OT_40; $tot_OT_40 = $tot_OT_40+$row->OT_40;  }  else echo "&nbsp;";?></td>
		
		<td><?php if($row->MD_20) { echo $row->MD_20; $tot_MD_20 = $tot_MD_20+$row->MD_20;  }  else echo "&nbsp;";?></td>
		<td><?php if($row->MD_40) { echo $row->MD_40; $tot_MD_40 = $tot_MD_40+$row->MD_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row->MH_40) { echo $row->MH_40; $tot_MH_40 = $tot_MH_40+$row->MH_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row->MH_45) { echo $row->MH_45; $tot_MH_45 = $tot_MH_45+$row->MH_45;  }  else echo "&nbsp;";?></td>
		
		<td><?php if($row->MRH_40) { echo $row->MRH_40; $tot_MRH_40 = $tot_MRH_40+$row->MRH_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row->MR_20) { echo $row->MR_20; $tot_MR_20 = $tot_MR_20+$row->MR_20;  }  else echo "&nbsp;";?></td>		
		
		
		<td><?php if($row->MOT_20) { echo $row->MOT_20; $tot_MOT_20 = $tot_MOT_20+$row->MOT_20;  }  else echo "&nbsp;";?></td>
		<td><?php if($row->MFR_20) { echo $row->MFR_20; $tot_MFR_20 = $tot_MFR_20+$row->MFR_20;  }  else echo "&nbsp;";?></td>
		<td><?php if($row->MTK_20) { echo $row->MTK_20; $tot_MTK_20 = $tot_MTK_20+$row->MTK_20;  }  else echo "&nbsp;";?></td>
		
		<td><?php if($row->MFR_40) { echo $row->MFR_40; $tot_MFR_40 = $tot_MFR_40+$row->MFR_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row->MOT_40) { echo $row->MOT_40; $tot_MOT_40 = $tot_MOT_40+$row->MOT_40;  }  else echo "&nbsp;";?></td>
		
		<td  align="center" ><?php if($row->GRAND_TOT) { echo $row->GRAND_TOT; $tot_grand_tot = $tot_grand_tot+$row->GRAND_TOT;  }  else echo "&nbsp;";?></td>
		<td  align="center" ><?php if($row->TUES) { echo $row->TUES; $tot_tues = $tot_tues+$row->TUES;  }   else echo "&nbsp;";?></td>
	</tr>

	<?php } 
	oci_free_statement($query);
} ?>

<tr align="center">
		<td><b>Total</td>
		<td><b><?php  echo  $tot_D_20;?></b></td>
		<td><b><?php  echo  $tot_D_40;?></b></td>
		<td><b><?php  echo  $tot_H_40 ;?></b></td>
		<td><b><?php  echo  $tot_H_45;?></b></td>
		
		<td><b><?php echo  $tot_RH_40;?></b></td>
		<td><b><?php echo  $tot_R_20;?></b></td>
		
		
		<td><b><?php echo $tot_OT_20; ?></b></td>
		<td><b><?php echo $tot_FR_20;?></b></td>
		<td><b><?php echo $tot_TK_20;?></b></td>
		
		<td><b><?php echo $tot_FR_40;?></b></td>
		<td><b><?php echo $tot_OT_40;?></b></td>
		
		<td><b><?php echo $tot_MD_20 ;?></b></td>
		<td><b><?php echo $tot_MD_40 ;?></b></td>
		<td><b><?php echo $tot_MH_40;?></b></td>
		<td><b><?php echo $tot_MH_45 ;?></b></td>
		
		<td><b><?php echo $tot_MRH_40 ;?></b></td>
		<td><b><?php echo $tot_MR_20;?></b></td>		
		
		
		<td><b><?php echo $tot_MOT_20;?></b></td>
		<td><b><?php echo $tot_MFR_20 ;?></b></td>
		<td><b><?php echo $tot_MTK_20;?></b></td>
		
		<td><b><?php echo $tot_MFR_40 ;?></b></td>
		<td><b><?php echo $tot_MOT_40 ;?></b></td>
		
		<td  align="center" ><b><?php  echo $tot_grand_tot ;?></b></td>
		<td  align="center"><b><?php  echo $tot_tues ;?></b></td>
	</tr>

		
	




	<?php
	$holdStr=" SELECT mlo,
		IFNULL(SUM(D_20),0) AS D_20,
		IFNULL(SUM(D_40),0) AS D_40,
		IFNULL(SUM(H_40),0) AS H_40,
		IFNULL(SUM(H_45),0) AS H_45,

		IFNULL(SUM(R_20),0) AS R_20,
		IFNULL(SUM(RH_40),0) AS RH_40,

		IFNULL(SUM(OT_20),0) AS OT_20,
		IFNULL(SUM(OT_40),0) AS OT_40,

		IFNULL(SUM(FR_20),0) AS FR_20,
		IFNULL(SUM(FR_40),0) AS FR_40,

		IFNULL(SUM(TK_20),0) AS TK_20,

		IFNULL(SUM(MD_20),0) AS MD_20,
		IFNULL(SUM(MD_40),0) AS MD_40,
		IFNULL(SUM(MH_40),0) AS MH_40,
		IFNULL(SUM(MH_45),0) AS MH_45,

		IFNULL(SUM(MR_20),0) AS MR_20,
		IFNULL(SUM(MRH_40),0) AS MRH_40,

		IFNULL(SUM(MOT_20),0) AS MOT_20,
		IFNULL(SUM(MOT_40),0) AS MOT_40,

		IFNULL(SUM(MFR_20),0) AS MFR_20,
		IFNULL(SUM(MFR_40),0) AS MFR_40,

		IFNULL(SUM(MTK_20),0) AS MTK_20,

		IFNULL(SUM(grand_tot),0) AS grand_tot,
		IFNULL(SUM(tues),0) AS tues
		 FROM (
		 SELECT import_container_hold_shifting.unit_no, mlo_code as mlo, unit_iso, move_num,
				(CASE WHEN  cont_size = '20' AND cont_height ='86' AND freight_kind IN ('FCL','LCL') 
		AND ctmsmis.import_container_hold_shifting.unit_iso NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN move_num  
		ELSE NULL END) AS D_20, 
		(CASE WHEN  cont_size = '40' AND cont_height ='86' AND freight_kind IN ('FCL','LCL') AND ctmsmis.import_container_hold_shifting.unit_iso NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN move_num  
		ELSE NULL END) AS D_40, 
		(CASE WHEN  cont_size = '40' AND cont_height ='96' AND freight_kind IN ('FCL','LCL') AND ctmsmis.import_container_hold_shifting.unit_iso  NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN move_num  
		ELSE NULL END) AS H_40, 
		(CASE WHEN  cont_size = '45' AND cont_height ='96' AND freight_kind IN ('FCL','LCL') AND ctmsmis.import_container_hold_shifting.unit_iso  NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN move_num  
		ELSE NULL END) AS H_45,


		(CASE WHEN  cont_size = '20' AND cont_height ='86' AND freight_kind IN ('FCL','LCL') AND ctmsmis.import_container_hold_shifting.unit_iso  IN ('RS','RT','RE')  THEN move_num  
		ELSE NULL END) AS R_20, 
		(CASE WHEN  cont_size = '40' AND freight_kind IN ('FCL','LCL') AND
		 ctmsmis.import_container_hold_shifting.unit_iso  IN ('RS','RT','RE')  THEN move_num 
		ELSE NULL END) AS RH_40,

		(CASE WHEN  cont_size = '20' AND cont_height ='86' AND freight_kind IN ('FCL','LCL') AND ctmsmis.import_container_hold_shifting.unit_iso  IN ('UT')  THEN move_num  
		ELSE NULL END) AS OT_20,
		(CASE WHEN  cont_size = '40' AND freight_kind IN ('FCL','LCL') 
		AND ctmsmis.import_container_hold_shifting.unit_iso  IN ('UT')  THEN move_num 
		ELSE NULL END) AS OT_40,

		(CASE WHEN  cont_size = '20' AND cont_height ='86' AND freight_kind IN ('FCL','LCL') AND ctmsmis.import_container_hold_shifting.unit_iso  IN ('PF','PC')  THEN move_num  
		ELSE NULL END) AS FR_20,
		(CASE WHEN  cont_size = '40' AND freight_kind IN ('FCL','LCL') 
		AND ctmsmis.import_container_hold_shifting.unit_iso  IN ('PF','PC')  THEN move_num 
		ELSE NULL END) AS FR_40,

		(CASE WHEN  cont_size = '20' AND cont_height ='86' AND freight_kind IN ('FCL','LCL') AND ctmsmis.import_container_hold_shifting.unit_iso  IN ('TN','TD','TG')  THEN move_num 
		ELSE NULL END) AS TK_20,

		(CASE WHEN  cont_size = '20' AND cont_height ='86' AND freight_kind ='MTY' AND ctmsmis.import_container_hold_shifting.unit_iso  NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN move_num 
		ELSE NULL END) AS MD_20, 
		(CASE WHEN  cont_size = '40' AND cont_height ='86' AND freight_kind ='MTY' AND ctmsmis.import_container_hold_shifting.unit_iso  NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN move_num
		ELSE NULL END) AS MD_40, 
		(CASE WHEN  cont_size = '40' AND cont_height ='96' AND freight_kind ='MTY' AND ctmsmis.import_container_hold_shifting.unit_iso  NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN move_num  
		ELSE NULL END) AS MH_40, 
		(CASE WHEN  cont_size = '45' AND cont_height ='96' AND freight_kind ='MTY' AND ctmsmis.import_container_hold_shifting.unit_iso  NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN move_num  
		ELSE NULL END) AS MH_45,


		(CASE WHEN  cont_size = '20' AND cont_height ='86' AND freight_kind ='MTY' AND ctmsmis.import_container_hold_shifting.unit_iso  IN ('RS','RT','RE')  THEN move_num 
		ELSE NULL END) AS MR_20, 
		(CASE WHEN  cont_size = '40' AND freight_kind ='MTY'
		 AND ctmsmis.import_container_hold_shifting.unit_iso  IN ('RS','RT','RE')  THEN move_num 
		ELSE NULL END) AS MRH_40,

		(CASE WHEN  cont_size = '20' AND cont_height ='86' AND freight_kind ='MTY' AND ctmsmis.import_container_hold_shifting.unit_iso  IN ('UT')  THEN move_num  
		ELSE NULL END) AS MOT_20,
		(CASE WHEN  cont_size = '40' AND freight_kind ='MTY' AND 
		ctmsmis.import_container_hold_shifting.unit_iso  IN ('UT')  THEN move_num 
		ELSE NULL END) AS MOT_40,

		(CASE WHEN  cont_size = '20' AND cont_height ='86' AND freight_kind ='MTY' AND ctmsmis.import_container_hold_shifting.unit_iso  IN ('PF','PC')  THEN move_num 
		ELSE NULL END) AS MFR_20,
		(CASE WHEN  cont_size = '40' AND freight_kind ='MTY' 
		AND ctmsmis.import_container_hold_shifting.unit_iso  IN ('PF','PC')  THEN move_num  
		ELSE NULL END) AS MFR_40,

		(CASE WHEN  cont_size = '20' AND cont_height ='86' AND freight_kind ='MTY' AND ctmsmis.import_container_hold_shifting.unit_iso 
		 IN ('TN','TD','TG')  THEN move_num  
		ELSE NULL END) AS MTK_20,
		(CASE WHEN  cont_size IN('20','40','45','42')  THEN move_num ELSE NULL END) AS grand_tot,
		(CASE WHEN  cont_size=20  THEN 1*move_num ELSE 2*move_num END) AS tues          
		 
		FROM ctmsmis.import_container_hold_shifting
		WHERE vvd_gkey='$vvdGkey') AS tmp GROUP BY mlo WITH ROLLUP";
	
		$tothold_D_20="";
		$totholdD_40="";
		$totholdH_40="" ;
		$totholdH_45="";
		$totholdRH_40="";
		$totholdR_20="";
		
		
		$totholdOT_20="" ;
		$totholdFR_20="";
		$totholdTK_20="";
		
		$totholdFR_40="";
		$totholdOT_40="";
		
		$totholdMD_20="";
		$totholdMD_40="" ;
		$totholdMH_40="";
		$totholdMH_45="" ;
		
		$totholdMRH_40="" ;
		$totholdMR_20="";
		
		
		$totholdMOT_20="";
		$totholdMFR_20="" ;
		$totholdMTK_20="";
		
		$totholdMFR_40 ="";
		$totholdMOT_40 ="";
		
		$totholdgrand_tot="";
		$totholdTues ="";
		$rowcount=0;
		$m=0;
		$query2=mysqli_query($con_sparcsn4, $holdStr);
		$rowcount=mysqli_num_rows($query2);
			$i=0;
			$j=0;
			
			$mlo="";
		//	if(!is_bool($query2)){
			if($rowcount>0)
			{	
		?>
		<tr>
			<td colspan="26" align="center"><font size="4"><b>Import Hold Container Shifting</b></font></td>
		</tr>
		 <?php			
			while($row1=mysqli_fetch_object($query2)){				

			$i++;
			if($row1->mlo) {
		?>
			
	<tr align="center">
		<td align="center"><?php if($row1->mlo) echo $row1->mlo; else echo "";?></td>
		<td><?php if($row1->D_20) { echo $row1->D_20; $tothold_D_20 = $tothold_D_20 + $row1->D_20; } else echo "&nbsp;";?></td>
		<td><?php if($row1->D_40) { echo $row1->D_40; $totholdD_40 = $totholdD_40 + $row1->D_40; }  else echo "&nbsp;";?></td>
		<td><?php if($row1->H_40) { echo $row1->H_40; $totholdH_40 = $totholdH_40 + $row1->H_40; } else echo "&nbsp;";?></td>
		<td><?php if($row1->H_45) { echo $row1->H_45; $totholdH_45 = $totholdH_45 + $row1->H_45; } else echo "&nbsp;";?></td>
		
		<td><?php if($row1->RH_40) { echo $row1->RH_40; $totholdRH_40 = $totholdRH_40 + $row1->RH_40; } else echo "&nbsp;";?></td>
		<td><?php if($row1->R_20) { echo $row1->R_20; $totholdR_20 = $totholdR_20 + $row1->R_20; } else echo "&nbsp;";?></td>
		
		
		<td><?php if($row1->OT_20) { echo $row1->OT_20; $totholdOT_20 = $totholdOT_20 + $row1->OT_20; } else echo "&nbsp;";?></td>
		<td><?php if($row1->FR_20) { echo $row1->FR_20; $totholdFR_20 = $totholdFR_20 + $row1->FR_20; } else echo "&nbsp;";?></td>
		<td><?php if($row1->TK_20) { echo $row1->TK_20; $totholdTK_20 = $totholdTK_20 + $row1->TK_20; }  else echo "&nbsp;";?></td>
		
		<td><?php if($row1->FR_40) { echo $row1->FR_40; $totholdFR_40 = $totholdFR_40 + $row1->FR_40; } else echo "&nbsp;";?></td>
		<td><?php if($row1->OT_40) { echo $row1->OT_40; $totholdOT_40 = $totholdOT_40 + $row1->OT_40; } else echo "&nbsp;";?></td>
		
		<td><?php if($row1->MD_20) { echo $row1->MD_20; $totholdMD_20 = $totholdMD_20 + $row1->MD_20; } else echo "&nbsp;";?></td>
		<td><?php if($row1->MD_40) { echo $row1->MD_40; $totholdMD_40 = $totholdMD_40 + $row1->MD_40; } else echo "&nbsp;";?></td>
		<td><?php if($row1->MH_40) { echo $row1->MH_40; $totholdMH_40 = $totholdMH_40 + $row1->MH_40; }else echo "&nbsp;";?></td>
		<td><?php if($row1->MH_45) { echo $row1->MH_45; $totholdMH_45 = $totholdMH_45 + $row1->MH_45; } else echo "&nbsp;";?></td>
		
		<td><?php if($row1->MRH_40) { echo $row1->MRH_40; $totholdMRH_40 = $totholdMRH_40 + $row1->MRH_40; } else echo "&nbsp;";?></td>
		<td><?php if($row1->MR_20) { echo $row1->MR_20; $totholdMR_20 = $totholdMR_20 + $row1->MR_20; } else echo "&nbsp;";?></td>		
		
		
		<td><?php if($row1->MOT_20) { echo $row1->MOT_20; $totholdMOT_20 = $totholdMOT_20 + $row1->MOT_20; }  else echo "&nbsp;";?></td>
		<td><?php if($row1->MFR_20) { echo $row1->MFR_20; $totholdMFR_20 = $totholdMFR_20 + $row1->MFR_20; } else echo "&nbsp;";?></td>
		<td><?php if($row1->MTK_20) { echo $row1->MTK_20; $totholdMTK_20 = $totholdMTK_20 + $row1->MTK_20; } else echo "&nbsp;";?></td>
		
		<td><?php if($row1->MFR_40) { echo $row1->MFR_40; $totholdMFR_40 = $totholdMFR_40 + $row1->MFR_40; } else echo "&nbsp;";?></td>
		<td><?php if($row1->MOT_40) { echo $row1->MOT_40; $totholdMOT_40 = $totholdMOT_40 + $row1->MOT_40; } else echo "&nbsp;";?></td>
		
		<td  align="center"><?php if($row1->grand_tot) {  echo $row1->grand_tot; $totholdgrand_tot = $totholdgrand_tot + $row1->grand_tot; } else echo "&nbsp;";?></td>
		<td  align="center"><?php if($row1->tues)  { echo $row1->tues; $totholdTues = $totholdTues + $row1->tues; } else echo "&nbsp;";?></td>	
	</tr>
			<?php	}    }  ?>
	<tr align="center">
		<td><b>S.Total</b></td>
		<td><b><?php  echo $tothold_D_20 ; ?></b></td>
		<td><b><?php  echo $totholdD_40; ?></b></td>
		<td><b><?php  echo $totholdH_40; ?></b></td>
		<td><b><?php  echo $totholdH_45; ?></b></td>
		
		<td><b><?php  echo $totholdRH_40; ?></b></td>
		<td><b><?php  echo $totholdR_20; ?></b></td>
		
		
		<td><b><?php echo $totholdOT_20; ?></b></td>
		<td><b><?php echo $totholdFR_20; ?></b></td>
		<td><b><?php echo $totholdTK_20; ?></b></td>
		
		<td><b><?php echo $totholdFR_40; ?></b></td>
		<td><b><?php echo $totholdOT_40; ?></b></td>
		
		<td><b><?php echo $totholdMD_20; ?></b></td>
		<td><b><?php echo $totholdMD_40; ?></b></td>
		<td><b><?php echo $totholdMH_40; ?></b></td>
		<td><b><?php echo $totholdMH_45; ?></b></td>
		
		<td><b><?php echo $totholdMRH_40;?></b></td>
		<td><b><?php echo $totholdMR_20; ?></b></td>		
		
		
		<td><b><?php echo $totholdMOT_20; ?></b></td>
		<td><b><?php echo $totholdMFR_20; ?></b></td>
		<td><b><?php echo $totholdMTK_20; ?></b></td>
		
		<td><b><?php echo $totholdMFR_40; ?></b></td>
		<td><b><?php echo $totholdMOT_40; ?></b></td>
		
		<td  align="center"><b><?php echo $totholdgrand_tot;?></b></td>
		<td  align="center"><b><?php echo $totholdTues;?></b></td>	
	</tr>
	<tr><td colspan="25"></td></tr>
	
	<tr align="center">
		<td align="center"><b>Grand Total</b></td>
		<td align="center"><b><?php  if(($tothold_D_20 +  $tot_D_20) >0) echo $tothold_D_20 +  $tot_D_20; ?></b></td>
		<td align="center" ><b><?php  if(($totholdD_40 + $tot_D_40) >0)  echo $totholdD_40 + $tot_D_40; ?></b></td>
		<td align="center"><b><?php if(($totholdH_40 + $tot_H_40) >0) echo $totholdH_40 + $tot_H_40; ?></b></td>
		<td align="center"><b><?php if(($totholdH_45 + $tot_H_45) >0) echo $totholdH_45 + $tot_H_45; ?></b></td>
		
		<td align="center"><b><?php if(($totholdRH_40 + $tot_RH_40) >0) echo $totholdRH_40 + $tot_RH_40; ?></b></td>
		<td align="center"><b><?php  if(($totholdR_20 + $tot_R_20) >0) echo $totholdR_20 + $tot_R_20; ?></b></td>
		
		
		<td align="center"><b><?php if(($totholdOT_20 + $tot_OT_20) >0) echo $totholdOT_20 + $tot_OT_20; ?></b></td>
		<td align="center"><b><?php if(($totholdFR_20 + $tot_FR_20) >0) echo $totholdFR_20 + $tot_FR_20; ?></b></td>
		<td align="center"><b><?php  if(($totholdTK_20 + $tot_TK_20) >0)echo $totholdTK_20 + $tot_TK_20; ?></b></td>
		
		<td align="center"><b><?php if(($totholdFR_40 + $tot_FR_40) >0) echo $totholdFR_40 + $tot_FR_40; ?></b></td>
		<td align="center"><b><?php if(($totholdOT_40 + $tot_OT_40) >0) echo $totholdOT_40 + $tot_OT_40; ?></b></td>
		
		<td align="center"><b><?php if(($totholdMD_20 + $tot_MD_20) >0) echo $totholdMD_20 + $tot_MD_20; ?></b></td>
		<td align="center"><b><?php if(($totholdMD_40 + $tot_MD_40) >0) echo $totholdMD_40 + $tot_MD_40; ?></b></td>
		<td align="center"><b><?php if(($totholdMD_40 + $tot_MD_40) >0) echo $totholdMD_40 + $tot_MD_40; ?></b></td>
		<td align="center"><b><?php if(($totholdMH_45 + $tot_MH_45) >0) echo $totholdMH_45 + $tot_MH_45; ?></b></td>
		
		<td align="center"><b><?php if(($totholdMRH_40 + $tot_MRH_40) >0) echo $totholdMRH_40 + $tot_MRH_40;?></b></td>
		<td align="center"><b><?php if(($totholdMR_20 + $tot_MR_20) >0) echo $totholdMR_20 + $tot_MR_20; ?></b></td>		
		
		
		<td align="center"><b><?php if(($totholdMOT_20 + $tot_MOT_20) >0) echo $totholdMOT_20 + $tot_MOT_20; ?></b></td>
		<td align="center"><b><?php if(($totholdMFR_20 + $tot_MFR_20) >0) echo $totholdMFR_20 + $tot_MFR_20; ?></b></td>
		<td align="center"><b><?php if(($totholdMTK_20 + $tot_MTK_20) >0) echo $totholdMTK_20 + $tot_MTK_20; ?></b></td>
		
		<td align="center"><b><?php if(($totholdMFR_40  + $tot_MFR_40) >0) echo $totholdMFR_40  + $tot_MFR_40; ?></b></td>
		<td align="center"><b><?php if(($totholdMOT_40 + $tot_MOT_40) >0) echo $totholdMOT_40 + $tot_MOT_40 ; ?></b></td>
		
		<td  align="center"><b><?php echo $totholdgrand_tot + $tot_grand_tot;?></b></td>
		<td  align="center"><b><?php echo $totholdTues + $tot_tues;?></b></td>	
	</tr>
			

</table>

	<p align="center" ><b>REMARKS:</b> IMPORT HOLD SHIFTING. Discharged on trailer and then again loaded for bay to bay shifting. </p>
	

    <table border ='1' cellpadding='0' cellspacing='0' align="center" width="60%">
	        <!--tr>
				<td align="center" colspan="5" border="0"><p align="center"><b>REMARKS:</b> IMPORT HOLD SHIFTING. Discharged on trailer and then again loaded for bay to bay shifting. </p>
				</td>
			 </tr-->

			<tr>
				<th>MLO</th>
				<th>Container</th>
				<th>Size</th>
				<th>Height</th>
				<th>ISO group</th>
			</tr>
			<?php
				$hold_str="SELECT import_container_hold_shifting.unit_no, mlo_code AS mlo, unit_iso,import_container_hold_shifting.cont_size,
				import_container_hold_shifting.cont_height,
				(CASE WHEN  cont_size IN('20','40','45','42')  THEN move_num ELSE NULL END) AS grand_tot,
				(CASE WHEN  cont_size=20  THEN 1 ELSE 2 END) AS tues      

				FROM ctmsmis.import_container_hold_shifting
				WHERE vvd_gkey='$vvdGkey'";
				$hold_query=mysqli_query($con_sparcsn4, $hold_str);		
				$h=0;		
				$mlo="";
				

				while($row_hold=mysqli_fetch_object($hold_query)){
				$h++;				
			?>
			<tr>
				<td align="center"><?php echo $row_hold->mlo; ?></td>
				<td align="center"><?php echo $row_hold->unit_no; ?></td>
				<td align="center"><?php echo $row_hold->cont_size; ?></td>
				<td align="center"><?php echo $row_hold->cont_height; ?></td>
				<td align="center"><?php echo $row_hold->unit_iso; ?></td>

			</tr>
				<?php } ?>
			
	</table>

<?php } ?>
	<pagebreak />


	<!---MLO wise  Export Loading Summay--->

	<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
	<tr  align="center" height="100px">
		<td width="70%" align="center">
			<table border=0 width="100%">
				<tr>
					<td colspan="11" align="center"><font size="4"><b> <u>MLO WISE LOADING SUMMARY</u></b></font></td>
					
				<tr>
					<td ><font size="4">Vessel: -  </font></td>
					<td colspan="3" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $vsl_name; ?></b></font></td>
					<td><font size="4">VOY : - </font></td>
					<td colspan="3" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $voysNo; ?></b></font></td>
					<td ><font size="4">ROT/NO : -</font></td>
					<td colspan="3" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $ddl_imp_rot_no; ?></b></font></td>
				</tr>
				<tr>
					<td colspan="2"><font size="4">Arrived on : - </font></td>
					<td colspan="2" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $arriveTime; ?></b></font></td>
					<td colspan="2" ><font size="4">Sailed on : - </font></td>
					<td colspan="2" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $sailedTime; ?></b></font></td>
					<td colspan="2" ><font size="4">Berth No :- </font></td>
					<td colspan="2" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $berthTime; ?></b></font></td>
				</tr>
			</table>
		
		</td>
		
	</tr>
	

</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr>
		<td></td>
		<td colspan="11" align="center"><b>LADEN</b></td>
		<td colspan="11" align="center"><b>EMPTY</b></td>
		<td></td>
		<td></td>
	</tr>
	<tr height="40px" align="center">
		<td  align="center"><b>MLO'S</b></td>		
		<td  align="center"><b>2D</b></td>
		<td  align="center"><b>4D</b></td>
		<td  align="center"><b>4H</b></td>
		<td  align="center"><b>45H</b></td>		
		<td  align="center"><b>4RH</b></td>
		<td  align="center"><b>2RF</b></td>
		<td  align="center"><b>2OT</b></td>
		<td  align="center"><b>2FR</b></td>
		<td  align="center"><b>2TK</b></td>
		<td  align="center"><b>4FR</b></td>
		<td  align="center"><b>4OT</b></td>
		<td  align="center"><b>2D</b></td>
		<td  align="center"><b>4D</b></td>
		<td  align="center"><b>4H</b></td>
		<td  align="center"><b>45H</b></td>		
		<td  align="center"><b>4RH</b></td>
		<td  align="center"><b>2RF</b></td>
		<td  align="center"><b>2OT</b></td>
		<td  align="center"><b>2FR</b></td>
		<td  align="center"><b>2TK</b></td>
		<td  align="center"><b>4FR</b></td>
		<td  align="center"><b>4OT</b></td>				
		<td  align="center"><b>TOTAL CONTS</b></td>
		<td  align="center"><b>TOTAL TEUS</b></td>
	</tr>
	
<?php
	
	$exp_str="SELECT mlo,
	NVL(SUM(D_20),0) AS D_20,
	NVL(SUM(D_40),0) AS D_40,
	NVL(SUM(H_40),0) AS H_40,
	NVL(SUM(H_45),0) AS H_45,
	
	NVL(SUM(R_20),0) AS R_20,
	NVL(SUM(RH_40),0) AS RH_40,
	
	NVL(SUM(OT_20),0) AS OT_20,
	NVL(SUM(OT_40),0) AS OT_40,
	
	NVL(SUM(FR_20),0) AS FR_20,
	NVL(SUM(FR_40),0) AS FR_40,
	
	NVL(SUM(TK_20),0) AS TK_20,
	
	NVL(SUM(MD_20),0) AS MD_20,
	NVL(SUM(MD_40),0) AS MD_40,
	NVL(SUM(MH_40),0) AS MH_40,
	NVL(SUM(MH_45),0) AS MH_45,
	
	NVL(SUM(MR_20),0) AS MR_20,
	NVL(SUM(MRH_40),0) AS MRH_40,
	
	NVL(SUM(MOT_20),0) AS MOT_20,
	NVL(SUM(MOT_40),0) AS MOT_40,
	
	NVL(SUM(MFR_20),0) AS MFR_20,
	NVL(SUM(MFR_40),0) AS MFR_40,
	
	NVL(SUM(MTK_20),0) AS MTK_20,
	
	NVL(SUM(grand_tot),0) AS grand_tot,
	NVL(SUM(tues),0) AS tues
	FROM (
	SELECT DISTINCT inv_unit.gkey AS gkey, 
	ref_bizunit_scoped.id AS mlo, 
	ref_bizunit_scoped.name  AS mlo_name,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS D_20, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS D_40, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND SUBSTR(ref_equip_type.nominal_height,-2) ='96' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS H_40, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '45' AND SUBSTR(ref_equip_type.nominal_height,-2) ='96' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS H_45,
	
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
	ELSE NULL END) AS R_20, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
	ELSE NULL END) AS RH_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('UT')  THEN 1  
	ELSE NULL END) AS OT_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('UT')  THEN 1  
	ELSE NULL END) AS OT_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
	ELSE NULL END) AS FR_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
	ELSE NULL END) AS FR_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('TN','TD','TG')  THEN 1  
	ELSE NULL END) AS TK_20,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS MD_20, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS MD_40, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND SUBSTR(ref_equip_type.nominal_height,-2) ='96' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS MH_40, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '45' AND SUBSTR(ref_equip_type.nominal_height,-2) ='96' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
	ELSE NULL END) AS MH_45,
	
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
	ELSE NULL END) AS MR_20, 
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
	ELSE NULL END) AS MRH_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('UT')  THEN 1  
	ELSE NULL END) AS MOT_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('UT')  THEN 1  
	ELSE NULL END) AS MOT_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
	ELSE NULL END) AS MFR_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
	ELSE NULL END) AS MFR_40,
	
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND SUBSTR(ref_equip_type.nominal_height,-2) ='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('TN','TD','TG')  THEN 1  
	ELSE NULL END) AS MTK_20,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) IN('20','40','45','42')  THEN 1 ELSE NULL END) AS grand_tot,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2)='20'  THEN 1 ELSE 2 END) AS tues     
	FROM inv_unit
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
	INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON  inv_unit_fcy_visit.actual_ob_cv=argo_carrier_visit.gkey
	INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	LEFT JOIN ref_bizunit_scoped  ON ref_bizunit_scoped.gkey = inv_unit.line_op
	WHERE  vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND inv_unit.category='EXPRT' AND inv_unit_fcy_visit.last_pos_loctype='VESSEL' 
	) tmp GROUP BY mlo";
		
	$exp_query=oci_parse($con_sparcsn4_oracle, $exp_str);
	oci_execute($exp_query);
		
		//echo $exp_str;
		 $i=0;
		 $j=0;
		
		 $mlo="";
	
		 $totexpD_20="";
		 $totexpD_40="";
		 $totexpH_40="" ;
		 $totexpH_45="";
		
		 $totexpRH_40="";
		 $totexpR_20="";
		
		
		$totexpOT_20="" ;
		$totexpFR_20="";
		$totexpTK_20="";
		
		$totexpFR_40="";
		$totexpOT_40="";
		
		$totexpMD_20="";
		$totexpMD_40="" ;
		$totexpMH_40="";
		$totexpMH_45="" ;
		
		$totexpMRH_40="" ;
		$totexpMR_20="";
		
		
		$totexpMOT_20="";
		$totexpMFR_20="" ;
		$totexpMTK_20="";
		
		$totexpMFR_40 ="";
		$totexpMOT_40 ="";
		
		$totexpgrand_tot="";
		$totexptues ="";
		
	while(($row_ex=oci_fetch_object($exp_query))!=false){
	$i++;
	
	$mlo="";
	
	$mlo=$row_ex->MLO;
	
	
?>
	<tr align="center">
		<td><?php if($row_ex->MLO) { echo $row_ex->MLO; } else echo ""; ?></td>
		<td><?php if($row_ex->D_20) { echo $row_ex->D_20; $totexpD_20 = $totexpD_20+$row_ex->D_20; } else echo "&nbsp;";?></td>
		<td><?php if($row_ex->D_40) { echo $row_ex->D_40; $totexpD_40 = $totexpD_40+$row_ex->D_40; } else echo "&nbsp;";?></td>
		<td><?php if($row_ex->H_40) { echo $row_ex->H_40; $totexpH_40 = $totexpH_40+$row_ex->H_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row_ex->H_45) { echo $row_ex->H_45; $totexpH_45 = $totexpH_45+$row_ex->H_45;  }  else echo "&nbsp;";?></td>
		
		<td><?php if($row_ex->RH_40) { echo $row_ex->RH_40; $totexpRH_40 = $totexpRH_40+$row_ex->RH_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row_ex->R_20) { echo $row_ex->R_20; $totexpR_20 = $totexpR_20+$row_ex->R_20;  }  else echo "&nbsp;";?></td>
		
		
		<td><?php if($row_ex->OT_20) { echo $row_ex->OT_20; $totexpOT_20 = $totexpOT_20+$row_ex->OT_20;  }  else echo "&nbsp;";?></td>
		<td><?php if($row_ex->FR_20) { echo $row_ex->FR_20; $totexpFR_20 = $totexpFR_20+$row_ex->FR_20;  }  else echo "&nbsp;";?></td>
		<td><?php if($row_ex->TK_20) { echo $row_ex->TK_20; $totexpTK_20 = $totexpTK_20+$row_ex->TK_20;  }  else echo "&nbsp;";?></td>
		
		<td><?php if($row_ex->FR_40) { echo $row_ex->FR_40; $totexpFR_40 = $totexpFR_40+$row_ex->FR_40;  } else echo "&nbsp;";?></td>
		<td><?php if($row_ex->OT_40) { echo $row_ex->OT_40; $totexpOT_40 = $totexpOT_40+$row_ex->OT_40;  }  else echo "&nbsp;";?></td>
		
		<td><?php if($row_ex->MD_20) { echo $row_ex->MD_20; $totexpMD_20 = $totexpMD_20+$row_ex->MD_20;  }  else echo "&nbsp;";?></td>
		<td><?php if($row_ex->MD_40) { echo $row_ex->MD_40; $totexpMD_40 = $totexpMD_40+$row_ex->MD_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row_ex->MH_40) { echo $row_ex->MH_40; $totexpMH_40 = $totexpMH_40+$row_ex->MH_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row_ex->MH_45) { echo $row_ex->MH_45; $totexpMH_45 = $totexpMH_45+$row_ex->MH_45;  }  else echo "&nbsp;";?></td>
		
		<td><?php if($row_ex->MRH_40) { echo $row_ex->MRH_40; $totexpMRH_40 = $totexpMRH_40+$row_ex->MRH_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row_ex->MR_20) { echo $row_ex->MR_20; $totexpMR_20 = $totexpMR_20+$row_ex->MR_20;  }  else echo "&nbsp;";?></td>		
		
		
		<td><?php if($row_ex->MOT_20) { echo $row_ex->MOT_20; $totexpMOT_20 = $totexpMOT_20+$row_ex->MOT_20;  }  else echo "&nbsp;";?></td>
		<td><?php if($row_ex->MFR_20) { echo $row_ex->MFR_20; $totexpMFR_20 = $totexpMFR_20+$row_ex->MFR_20;  }  else echo "&nbsp;";?></td>
		<td><?php if($row_ex->MTK_20) { echo $row_ex->MTK_20; $totexpMTK_20 = $totexpMTK_20+$row_ex->MTK_20;  }  else echo "&nbsp;";?></td>
		
		<td><?php if($row_ex->MFR_40) { echo $row_ex->MFR_40; $totexpMFR_40 = $totexpMFR_40+$row_ex->MFR_40;  }  else echo "&nbsp;";?></td>
		<td><?php if($row_ex->MOT_40) { echo $row_ex->MOT_40; $totexpMOT_40 = $totexpMOT_40+$row_ex->MOT_40;  }  else echo "&nbsp;";?></td>
		
		<td  align="center"><?php if($row_ex->GRAND_TOT) { echo $row_ex->GRAND_TOT; $totexpgrand_tot = $totexpgrand_tot+$row_ex->GRAND_TOT;  }  else echo "&nbsp;";?></td>
		<td  align="center"><?php if($row_ex->TUES) { echo $row_ex->TUES; $totexptues = $totexptues+$row_ex->TUES;  }   else echo "&nbsp;";?></td>
	</tr>

<?php } ?>

<tr align="center">
		<td><b>Total</td>
		<td><b><?php  echo  $totexpD_20;?></b></td>
		<td><b><?php  echo  $totexpD_40;?></b></td>
		<td><b><?php  echo  $totexpH_40 ;?></b></td>
		<td><b><?php  echo  $totexpH_45;?></b></td>
		
		<td><b><?php echo  $totexpRH_40;?></b></td>
		<td><b><?php echo  $totexpR_20;?></b></td>
		
		
		<td><b><?php echo $totexpOT_20; ?></b></td>
		<td><b><?php echo $totexpFR_20;?></b></td>
		<td><b><?php echo $totexpTK_20;?></b></td>
		
		<td><b><?php echo $totexpFR_40;?></b></td>
		<td><b><?php echo $totexpOT_40;?></b></td>
		
		<td><b><?php echo $totexpMD_20 ;?></b></td>
		<td><b><?php echo $totexpMD_40 ;?></b></td>
		<td><b><?php echo $totexpMH_40;?></b></td>
		<td><b><?php echo $totexpMH_45 ;?></b></td>
		
		<td><b><?php echo $totexpMRH_40 ;?></b></td>
		<td><b><?php echo $totexpMR_20;?></b></td>		
		
		
		<td><b><?php echo $totexpMOT_20;?></b></td>
		<td><b><?php echo $totexpMFR_20 ;?></b></td>
		<td><b><?php echo $totexpMTK_20;?></b></td>
		
		<td><b><?php echo $totexpMFR_40 ;?></b></td>
		<td><b><?php echo $totexpMOT_40 ;?></b></td>
		
		<td  align="center"><b><?php  echo $totexpgrand_tot ;?></b></td>
		<td  align="center"><b><?php  echo $totexptues ;?></b></td>
	</tr>
	
	<?php
	
		$tothold_D_20="";
		$totholdD_40="";
		$totholdH_40="" ;
		$totholdH_45="";
		$totholdRH_40="";
		$totholdR_20="";
		
		
		$totholdOT_20="" ;
		$totholdFR_20="";
		$totholdTK_20="";
		
		$totholdFR_40="";
		$totholdOT_40="";
		
		$totholdMD_20="";
		$totholdMD_40="" ;
		$totholdMH_40="";
		$totholdMH_45="" ;
		
		$totholdMRH_40="" ;
		$totholdMR_20="";
		
		
		$totholdMOT_20="";
		$totholdMFR_20="" ;
		$totholdMTK_20="";
		
		$totholdMFR_40 ="";
		$totholdMOT_40 ="";
		
		$totholdgrand_tot="";
		$totholdTues ="";
		$rowcount2=0;
		$m=0;
		$query3=mysqli_query($con_sparcsn4, $holdStr);
			$rowcount2=mysqli_num_rows($query3);
			$i=0;
			$j=0;
			
			$mlo="";
		//	if(!is_bool($query2)){
			if($rowcount>0)
			{	
		?>
		<tr>
			<td colspan="26" align="center"><font size="4"><b>Import Hold Container Shifting</b></font></td>
		</tr>
		 <?php			
			while($row2=mysqli_fetch_object($query3)){
			$i++;
			if($row2->mlo) {
		?>
			
	<tr>
		<td align="center"><?php if($row2->mlo) echo $row2->mlo; else echo "";?></td>
		<td><?php if($row2->D_20) { echo $row2->D_20; $tothold_D_20 = $tothold_D_20 + $row2->D_20; } else echo "&nbsp;";?></td>
		<td><?php if($row2->D_40) { echo $row2->D_40; $totholdD_40 = $totholdD_40 + $row2->D_40; }  else echo "&nbsp;";?></td>
		<td><?php if($row2->H_40) { echo $row2->H_40; $totholdH_40 = $totholdH_40 + $row2->H_40; } else echo "&nbsp;";?></td>
		<td><?php if($row2->H_45) { echo $row2->H_45; $totholdH_45 = $totholdH_45 + $row2->H_45; } else echo "&nbsp;";?></td>
		
		<td><?php if($row2->RH_40) { echo $row2->RH_40; $totholdRH_40 = $totholdRH_40 + $row2->RH_40; } else echo "&nbsp;";?></td>
		<td><?php if($row2->R_20) { echo $row2->R_20; $totholdR_20 = $totholdR_20 + $row2->R_20; } else echo "&nbsp;";?></td>
		
		
		<td><?php if($row2->OT_20) { echo $row2->OT_20; $totholdOT_20 = $totholdOT_20 + $row2->OT_20; } else echo "&nbsp;";?></td>
		<td><?php if($row2->FR_20) { echo $row2->FR_20; $totholdFR_20 = $totholdFR_20 + $row2->FR_20; } else echo "&nbsp;";?></td>
		<td><?php if($row2->TK_20) { echo $row2->TK_20; $totholdTK_20 = $totholdTK_20 + $row2->TK_20; }  else echo "&nbsp;";?></td>
		
		<td><?php if($row2->FR_40) { echo $row2->FR_40; $totholdFR_40 = $totholdFR_40 + $row2->FR_40; } else echo "&nbsp;";?></td>
		<td><?php if($row2->OT_40) { echo $row2->OT_40; $totholdOT_40 = $totholdOT_40 + $row2->OT_40; } else echo "&nbsp;";?></td>
		
		<td><?php if($row2->MD_20) { echo $row2->MD_20; $totholdMD_20 = $totholdMD_20 + $row2->MD_20; } else echo "&nbsp;";?></td>
		<td><?php if($row2->MD_40) { echo $row2->MD_40; $totholdMD_40 = $totholdMD_40 + $row2->MD_40; } else echo "&nbsp;";?></td>
		<td><?php if($row2->MH_40) { echo $row2->MH_40; $totholdMH_40 = $totholdMH_40 + $row2->MH_40; }else echo "&nbsp;";?></td>
		<td><?php if($row2->MH_45) { echo $row2->MH_45; $totholdMH_45 = $totholdMH_45 + $row2->MH_45; } else echo "&nbsp;";?></td>
		
		<td><?php if($row2->MRH_40) { echo $row2->MRH_40; $totholdMRH_40 = $totholdMRH_40 + $row2->MRH_40; } else echo "&nbsp;";?></td>
		<td><?php if($row2->MR_20) { echo $row2->MR_20; $totholdMR_20 = $totholdMR_20 + $row2->MR_20; } else echo "&nbsp;";?></td>		
		
		
		<td><?php if($row2->MOT_20) { echo $row2->MOT_20; $totholdMOT_20 = $totholdMOT_20 + $row2->MOT_20; }  else echo "&nbsp;";?></td>
		<td><?php if($row2->MFR_20) { echo $row2->MFR_20; $totholdMFR_20 = $totholdMFR_20 + $row2->MFR_20; } else echo "&nbsp;";?></td>
		<td><?php if($row2->MTK_20) { echo $row2->MTK_20; $totholdMTK_20 = $totholdMTK_20 + $row2->MTK_20; } else echo "&nbsp;";?></td>
		
		<td><?php if($row2->MFR_40) { echo $row2->MFR_40; $totholdMFR_40 = $totholdMFR_40 + $row2->MFR_40; } else echo "&nbsp;";?></td>
		<td><?php if($row2->MOT_40) { echo $row2->MOT_40; $totholdMOT_40 = $totholdMOT_40 + $row2->MOT_40; } else echo "&nbsp;";?></td>
		
		<td  align="center"><?php if($row2->grand_tot) {  echo $row2->grand_tot; $totholdgrand_tot = $totholdgrand_tot + $row2->grand_tot; } else echo "&nbsp;";?></td>
		<td  align="center"><?php if($row2->tues)  { echo $row2->tues; $totholdTues = $totholdTues + $row2->tues; } else echo "&nbsp;";?></td>	
	</tr>
			<?php	}  }   ?>
	<tr>
		<td align="center"><b>S.Total</b></td>
		<td><b><?php  echo $tothold_D_20 ; ?></b></td>
		<td><b><?php  echo $totholdD_40; ?></b></td>
		<td><b><?php  echo $totholdH_40; ?></b></td>
		<td><b><?php  echo $totholdH_45; ?></b></td>
		
		<td><b><?php  echo $totholdRH_40; ?></b></td>
		<td><b><?php  echo $totholdR_20; ?></b></td>
		
		
		<td><b><?php echo $totholdOT_20; ?></b></td>
		<td><b><?php echo $totholdFR_20; ?></b></td>
		<td><b><?php echo $totholdTK_20; ?></b></td>
		
		<td><b><?php echo $totholdFR_40; ?></b></td>
		<td><b><?php echo $totholdOT_40; ?></b></td>
		
		<td><b><?php echo $totholdMD_20; ?></b></td>
		<td><b><?php echo $totholdMD_40; ?></b></td>
		<td><b><?php echo $totholdMH_40; ?></b></td>
		<td><b><?php echo $totholdMH_45; ?></b></td>
		
		<td><b><?php echo $totholdMRH_40;?></b></td>
		<td><b><?php echo $totholdMR_20; ?></b></td>		
		
		
		<td><b><?php echo $totholdMOT_20; ?></b></td>
		<td><b><?php echo $totholdMFR_20; ?></b></td>
		<td><b><?php echo $totholdMTK_20; ?></b></td>
		
		<td><b><?php echo $totholdMFR_40; ?></b></td>
		<td><b><?php echo $totholdMOT_40; ?></b></td>
		
		<td  align="center"><b><?php echo $totholdgrand_tot;?></b></td>
		<td  align="center"><b><?php echo $totholdTues;?></b></td>	
	</tr>
	<tr><td colspan="25"></td></tr>
	<tr>
		<td align="center"><b>Grand Total</b></td>
		<td align="center"><b><?php  if(($tothold_D_20 +  $totexpD_20) >0) echo $tothold_D_20 +  $totexpD_20; ?></b></td>
		<td align="center"><b><?php  if(( $totholdD_40 + $totexpD_40) >0) echo $totholdD_40 + $totexpD_40; ?></b></td>
		<td align="center"><b><?php  if(($totholdH_40 + $totexpH_40) >0) echo $totholdH_40 + $totexpH_40; ?></b></td>
		<td align="center"><b><?php  if(($totholdH_45 + $totexpH_45) >0) echo $totholdH_45 + $totexpH_45; ?></b></td>
		
		<td align="center"><b><?php  if(($totholdRH_40 + $totexpRH_40) >0) echo $totholdRH_40 + $totexpRH_40; ?></b></td>
		<td align="center"><b><?php  if(($totholdR_20 + $totexpR_20) >0) echo $totholdR_20 + $totexpR_20; ?></b></td>
		
		
		<td align="center"><b><?php if(($totholdOT_20 + $totexpOT_20) >0) echo $totholdOT_20 + $totexpOT_20; ?></b></td>
		<td align="center"><b><?php if(($totholdFR_20 + $totexpFR_20) >0) echo $totholdFR_20 + $totexpFR_20; ?></b></td>
		<td align="center"><b><?php if(($totholdTK_20 + $totexpTK_20) >0) echo $totholdTK_20 + $totexpTK_20; ?></b></td>
		
		<td align="center"><b><?php if(($totholdFR_40 + $totexpFR_40) >0) echo $totholdFR_40 + $totexpFR_40; ?></b></td>
		<td align="center"><b><?php if(($totholdOT_40 + $totexpOT_40) >0) echo $totholdOT_40 + $totexpOT_40; ?></b></td>
		
		<td align="center"><b><?php if(($totholdMD_20 + $totexpMD_20) >0) echo $totholdMD_20 + $totexpMD_20; ?></b></td>
		<td align="center"><b><?php if(($totholdMD_40 + $totexpMD_40) >0) echo $totholdMD_40 + $totexpMD_40; ?></b></td>
		<td align="center"><b><?php if(($totholdMH_40 + $totexpMH_40) >0) echo $totholdMH_40 + $totexpMH_40; ?></b></td>
		<td align="center"><b><?php if(($totholdMH_45 + $totexpMH_45) >0) echo $totholdMH_45 + $totexpMH_45; ?></b></td>
		
		<td align="center"><b><?php if(($totholdMRH_40 + $totexpMH_45) >0) echo $totholdMRH_40 + $totexpMH_45;?></b></td>
		<td align="center"><b><?php if(($totholdMR_20 + $totexpMR_20) >0) echo $totholdMR_20 + $totexpMR_20; ?></b></td>		
		
		
		<td align="center"><b><?php if(($totholdMOT_20 + $totexpMOT_20) >0) echo $totholdMOT_20 + $totexpMOT_20; ?></b></td>
		<td align="center"><b><?php if(($totholdMFR_20 + $totexpMFR_20) >0) echo $totholdMFR_20 + $totexpMFR_20; ?></b></td>
		<td align="center"><b><?php if(($totholdMTK_20 + $totexpMTK_20) >0) echo $totholdMTK_20 + $totexpMTK_20; ?></b></td>
		
		<td align="center"><b><?php if(($totholdMFR_40  + $totexpMFR_40) >0) echo $totholdMFR_40  + $totexpMFR_40; ?></b></td>
		<td align="center"><b><?php if(($totholdMOT_40 + $totexpMOT_40) >0) echo $totholdMOT_40 + $totexpMOT_40 ; ?></b></td>
		
		<td  align="center"><b><?php echo $totholdgrand_tot + $totexpgrand_tot;?></b></td>
		<td  align="center"><b><?php echo $totholdTues + $totexptues;?></b></td>	
	</tr>

</table>
	<p align="center"><b>REMARKS:</b> IMPORT HOLD SHIFTING. Discharged on trailer and then again loaded for bay to bay shifting. </p>
	

    <table border ='1' cellpadding='0' cellspacing='0' align="center" width="60%">
			<tr>
				<th>MLO</th>
				<th>Container</th>
				<th>Size</th>
				<th>Height</th>
				<th>ISO group</th>
			</tr>
			<?php
				$hold_str="SELECT import_container_hold_shifting.unit_no, mlo_code AS mlo, unit_iso,import_container_hold_shifting.cont_size,
				import_container_hold_shifting.cont_height,
				(CASE WHEN  cont_size IN('20','40','45','42')  THEN move_num ELSE NULL END) AS grand_tot,
				(CASE WHEN  cont_size=20  THEN 1 ELSE 2 END) AS tues      

				FROM ctmsmis.import_container_hold_shifting
				WHERE vvd_gkey='$vvdGkey'";
				$hold_query=mysqli_query($con_sparcsn4, $hold_str);		
				$h=0;		
				$mlo="";
				

				while($row_hold=mysqli_fetch_object($hold_query)){
				$h++;				
			?>
			<tr>
				<td align="center"><?php echo $row_hold->mlo; ?></td>
				<td align="center"><?php echo $row_hold->unit_no; ?></td>
				<td align="center"><?php echo $row_hold->cont_size; ?></td>
				<td align="center"><?php echo $row_hold->cont_height; ?></td>
				<td align="center"><?php echo $row_hold->unit_iso; ?></td>

			</tr>
				<?php } ?>
			
		</table>
		
			<?php } 
			
		}
		
		else
		{
			echo "The rotation data not found.";
		}
	?>






<?php 
mysqli_close($con_sparcsn4);
//if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php // } ?>

