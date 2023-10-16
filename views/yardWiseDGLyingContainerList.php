<?php 
	if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=Yard_wise_lying_DG_container.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
?>
<html>
<head>
    <!--meta http-equiv="refresh" content="20"-->
    <style>
        body{font-family: "Calibri";}
    </style>
</head>
<body>
<div>
<?php

		$slot_="";
		
		
?>
<!--Sumon Roy--->
    <div align="center">
        <table>
				<tr align="center">
					<td align="center" valign="middle" colspan="9" align="center"><img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>		
            <tr style="margin:5px;">
                <td colspan="9" align="center"><font size="5"><b>Yard wise lying DG container  </b></font><font size="4"><?php //echo date("d/m/Y h:i:s")?></font></td>
            </tr>
			<tr style="margin:5px;">
                <td colspan="9" align="center"><font size="4"><b> Yard:  <?php echo  $yard; ?>  </b></font></td>
            </tr>
			
        </table>
        <table width="100%" border ='1' cellpadding='0' cellspacing='0'>
            <tr align="center" bgcolor="#D8D0CE">
                <td><b>SlNo.</b></td>
                <td><b>Container</b></td>
				<td><b>Size</b></td>
                <td><b>Height</b></td>
                <td><b>ISO</b></td>
                <td><b>IMDG Class</b></td>
                <td><b>MLO</b></td>
				<td><b>Freight Kind</b></td>
                <td><b>Rotation </b></td>
           
                <td><b>Yard No</b></td>
             
            </tr>

            <?php

            //echo$vvdGkey;
       include("dbConection.php");
	   include("dbOracleConnection.php");

	   include_once("FrontEnd/mydbPConnection.php");
		$sql_cond="";
	
			//  $sql = "SELECT *  FROM(SELECT inv_unit.id AS cont_no,vsl_vessel_visit_details.ib_vyg, (inv_unit.goods_and_ctr_wt_kg/1000) AS weight,
			//  inv_unit.seal_nbr1 AS seal_nbr, (SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.inv_unit_equip 
			//  INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			//  INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
			//  WHERE sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey) AS size,
			//  (SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2)
			//  FROM sparcsn4.inv_unit_equip 
			//  INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			//  INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
			//  WHERE sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey)/10 AS height,
			//  sparcsn4.ref_bizunit_scoped.id AS MLO,inv_unit.category,inv_unit.freight_kind, 
			//  (SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No, 
			//  (SELECT ctmsmis.cont_block(sparcsn4.inv_unit_fcy_visit.last_pos_slot,Yard_No)) AS Block_No,
			//  inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot, sparcsn4.vsl_vessel_visit_details.flex_date08, 
			//  sparcsn4.ref_equip_type.iso_group, DATEDIFF(DATE(NOW()), DATE(sparcsn4.vsl_vessel_visit_details.flex_date08)) AS lying_days 
			//  FROM sparcsn4.inv_unit INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey 
			//  INNER JOIN ref_bizunit_scoped ON sparcsn4.inv_unit.line_op=sparcsn4.ref_bizunit_scoped.gkey 
			//  INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
			//  INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
			//  INNER JOIN sparcsn4.inv_unit_equip ON inv_unit.gkey=inv_unit_equip.unit_gkey 
			//  INNER JOIN sparcsn4.ref_equipment ON inv_unit_equip.eq_gkey=ref_equipment.gkey
			//  INNER JOIN sparcsn4.ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
			//  WHERE sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD' AND sparcsn4.inv_unit_fcy_visit.visit_state='1ACTIVE' 
			//  AND sparcsn4.inv_unit.category='IMPRT'  ORDER BY lying_days ASC) 
			//  AS tmp WHERE Yard_No='$yard'";


			$sql = "
			SELECT inv_unit.id AS cont_no,vsl_vessel_visit_details.ib_vyg, (inv_unit.goods_and_ctr_wt_kg/1000) AS weight,
			inv_unit.seal_nbr1 AS seal_nbr,
			to_char((CURRENT_DATE-vsl_vessel_visit_details.flex_date08),'yyyy-mm-dd') AS lying_days,
			(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only
			) as siz,

			(select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
				
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only
			) as height,
			ref_bizunit_scoped.id AS MLO,inv_unit.category,inv_unit.freight_kind, 
			inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot, vsl_vessel_visit_details.flex_date08, 
			ref_equip_type.iso_group
		      
		   FROM inv_unit 
		   INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
		   INNER JOIN ref_bizunit_scoped ON inv_unit.line_op=ref_bizunit_scoped.gkey 
		   INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
		   INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
		   INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
		   INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
		   WHERE inv_unit_fcy_visit.transit_state='S40_YARD' AND inv_unit_fcy_visit.visit_state='1ACTIVE' 
		   AND inv_unit.category='IMPRT'   ORDER BY lying_days ASC";
	   
		   $sqlRslt=oci_parse($con_sparcsn4_oracle,$strQuery2);
		   $row = oci_execute($sqlRslt);

  

		   while(($rowSubQuery=oci_fetch_object($sqlRslt))!=false){
			
			
	         
			$i++;

			$carrentPosition1="";
			$carrentPosition1=$rowSubQuery->LAST_POS_SLOT;
			$strQuery3="SELECT ctmsmis.cont_yard('$carrentPosition1') AS Yard_No ";

		 
			$strQuery4="SELECT ctmsmis.cont_block('$carrentPosition1') AS Block_No ";

			$strQuery3Res = mysqli_query($con_sparcsn4,$strQuery3);
			$Yard_No="";
			$strQuery3Row=mysqli_fetch_object($strQuery3Res);
			$Yard_No=$strQuery3Row->Yard_No;


			$strQuery4Res = mysqli_query($con_sparcsn4,$strQuery4);
			$Block_No="";
			$strQuery4Row=mysqli_fetch_object($strQuery4Res);
			$Block_No=$strQuery4Row->Block_No;

				 
					

					 $strIgm = "SELECT igm_detail_container.cont_number, igm_detail_container.cont_imo,  igm_details.Notify_name
							FROM igm_detail_container
							INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
							WHERE igm_details.Import_Rotation_No='$row->ib_vyg' AND igm_detail_container.cont_number='$row->cont_no' and igm_detail_container.cont_imo !=''

							UNION 

							SELECT igm_sup_detail_container.cont_number, igm_sup_detail_container.cont_imo, igm_details.Notify_name
							FROM igm_sup_detail_container
							INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
							LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
							WHERE igm_supplimentary_detail.Import_Rotation_No='$row->ib_vyg' AND igm_sup_detail_container.cont_number='$row->cont_no' 
							and igm_sup_detail_container.cont_imo!='' ";
					$res_igm = mysqli_query($con_cchaportdb,$strIgm);
				while($row_igm = mysqli_fetch_object($res_igm))
				{
					$j++;
				
                ?>
	
				<tr align="center" bgcolor="#FCF7F7">
				<td align="center"><?php if($row->CONT_NO) echo($row->CONT_NO); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->SIZ) echo($row->SIZ); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->HEIGHT) echo($row->HEIGHT); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->ISO_GROUP) echo($row->ISO_GROUP); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row_igm->cont_imo) echo($row_igm->cont_imo); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->MLO) echo($row->MLO); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->FREIGHT_KIND) echo($row->FREIGHT_KIND); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->IB_VYG) echo($row->IB_VYG); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->Yard_No) echo($row->Yard_No); else echo("&nbsp;");?></td>
                </tr>

            <?php 
				}
			}
			?>
        </table>
    </div>
</div>
<br>
<?php mysqli_close($con_cchaportdb); ?>
<?php mysqli_close($con_sparcsn4); ?>

</body>
</html>
