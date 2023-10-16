
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
		if($day_slot=='1m')
		{
			$slot_='Lying Upto 1 month';
		}
		else if($day_slot=='6m')
		{
			$slot_='Lying Upto 6 months';
		}
		else if($day_slot=='1y')
		{
			$slot_='Lying Upto 1 year';
		}
		else if($day_slot=='0-4')
		{
			$slot_='Tariff Slot 0-4 days (Free Days)';
		}
		else if($day_slot=='1-7')
		{
			$slot_='Tariff Slot 1-7 days (After Free 4 Days)';
		}
		else if($day_slot=='8-20')
		{
			$slot_='Tariff Slot 8-20 days (After Free 4 Days)';
		}
		else if($day_slot=='20+')
		{
			$slot_='Tariff Slot 20+ days (After Free 4 Days)';
		}
		else if($day_slot=='1y+')
		{
			$slot_='Lying Over 1 year (365 days+)';
		}
		
?>
<!--Sumon Roy--->
    <div align="center">
        <table>
				<tr align="center">
					<td align="center" valign="middle" colspan="9" align="center"><img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>		
            <tr style="margin:5px;">
                <td colspan="9" align="center"><font size="5"><b>Day slot wise lying container Report </b></font><font size="4"><?php //echo date("d/m/Y h:i:s")?></font></td>
            </tr>
			<tr style="margin:5px;">
                <td colspan="9" align="center"><font size="4"><b> <?php echo  $slot_; ?>  </b></font></td>
            </tr>
			<tr style="margin:5px;">
                <td colspan="9" align="center"><font size="4"><b>Container Type: <?php echo  $cont_type; ?>  </b></font></td>
            </tr>
        </table>
        <table width="100%" border ='1' cellpadding='0' cellspacing='0'>
            <tr align="center" bgcolor="#D8D0CE">
                <td><b>SlNo.</b></td>
                <td><b>Container</b></td>
				<td><b>Size</b></td>
                <td><b>Height</b></td>
                <td><b>ISO</b></td>
				<?php if($cont_type=="IMDG") { ?>
                <td><b>IMDG Class</b></td>
				<?php } ?>
                <td><b>MLO</b></td>
				<td><b>Freight Kind</b></td>
                <td><b>Rotation </b></td>
                <td><b>Yard</b></td>
                <td><b>Last Position</b></td>
                <td><b>Importer Name</b></td>
                <td><b>Com. Landing Dt</b></td>
                <td><b>lying Days</b></td>

                <!--td><b>Last Position</b></td-->
            </tr>

            <?php

            //echo$vvdGkey;
       include("dbConection.php");
	   include_once("FrontEnd/mydbPConnection.php");
		$sql_cond="";
		//echo $cont_type;
		if($cont_type=='Reefer')
		{
			if($day_slot=="0-4")
			{
				$sql_cond=" AND sparcsn4.ref_equip_type.iso_group  IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= DATE(NOW()) AND DATE(vsl_vessel_visit_details.flex_date08) >= (DATE_ADD(DATE(NOW()), INTERVAL -4 DAY))";
			}
			else if($day_slot=="1-7")
			{
				$sql_cond=" AND sparcsn4.ref_equip_type.iso_group  IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= (DATE_ADD(DATE(NOW()), INTERVAL -5 DAY))
						AND DATE(vsl_vessel_visit_details.flex_date08)>=(DATE_ADD(DATE(NOW()), INTERVAL -11 DAY))";
			}
			else if($day_slot=="8-20")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= (DATE_ADD(DATE(NOW()), INTERVAL -12 DAY)) 
						AND DATE(vsl_vessel_visit_details.flex_date08) >= (DATE_ADD(DATE(NOW()), INTERVAL -20 DAY))";
			}
			else if($day_slot=="20+")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= (DATE_ADD(DATE(NOW()), INTERVAL -24 DAY))";
			}
			else if($day_slot=="1m")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= DATE(NOW())
						AND DATE(vsl_vessel_visit_details.flex_date08) >= (DATE_ADD(DATE(NOW()), INTERVAL -30 DAY))";
			}
			else if($day_slot=="6m")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= DATE(NOW())
						AND DATE(vsl_vessel_visit_details.flex_date08) >= (DATE_ADD(DATE(NOW()), INTERVAL -180 DAY))";
			}
			else if($day_slot=="1y")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= DATE(NOW())
						AND DATE(vsl_vessel_visit_details.flex_date08) >= (DATE_ADD(DATE(NOW()), INTERVAL -365 DAY))";
			}
			else if($day_slot=="1y+")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= (DATE_ADD(DATE(NOW()), INTERVAL -365 DAY))";
			}
		}
		else if($cont_type=="Normal" or $cont_type=="IMDG")
		{
			if($day_slot=="0-4")
			{
				$sql_cond=" AND sparcsn4.ref_equip_type.iso_group  NOT IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= DATE(NOW()) AND DATE(vsl_vessel_visit_details.flex_date08) >= (DATE_ADD(DATE(NOW()), INTERVAL -4 DAY))";
			}
			else if($day_slot=="1-7")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  NOT IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= (DATE_ADD(DATE(NOW()), INTERVAL -5 DAY))
						AND DATE(vsl_vessel_visit_details.flex_date08)>=(DATE_ADD(DATE(NOW()), INTERVAL -11 DAY))";
			}
			else if($day_slot=="8-20")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  NOT IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= (DATE_ADD(DATE(NOW()), INTERVAL -12 DAY)) 
						AND DATE(vsl_vessel_visit_details.flex_date08) >= (DATE_ADD(DATE(NOW()), INTERVAL -20 DAY))";
			}
			else if($day_slot=="20+")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= (DATE_ADD(DATE(NOW()), INTERVAL -24 DAY))";
			}
			else if($day_slot=="1m")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  NOT IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= DATE(NOW())
						AND DATE(vsl_vessel_visit_details.flex_date08) >= (DATE_ADD(DATE(NOW()), INTERVAL -30 DAY))";
			
			}
			else if($day_slot=="6m")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  NOT IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= DATE(NOW())
						AND DATE(vsl_vessel_visit_details.flex_date08) >= (DATE_ADD(DATE(NOW()), INTERVAL -180 DAY))";
			}
			
			else if($day_slot=="1y")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  NOT IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= DATE(NOW())
						AND DATE(vsl_vessel_visit_details.flex_date08) >= (DATE_ADD(DATE(NOW()), INTERVAL -365 DAY))";
			
			}
			else if($day_slot=="1y+")
			{
				$sql_cond="AND sparcsn4.ref_equip_type.iso_group  NOT IN ('RE','RS','RT')
						AND DATE(vsl_vessel_visit_details.flex_date08) <= (DATE_ADD(DATE(NOW()), INTERVAL -365 DAY))";
			
			}
			
		}
			

			echo $sql = "SELECT inv_unit.id as cont_no,vsl_vessel_visit_details.ib_vyg,
			(inv_unit.goods_and_ctr_wt_kg/1000) AS weight,inv_unit.seal_nbr1 AS seal_nbr,
			(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.inv_unit_equip
			INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			INNER JOIN  sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey) AS size,
			(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2) FROM sparcsn4.inv_unit_equip
			INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			INNER JOIN  sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey)/10 AS height,
			sparcsn4.ref_bizunit_scoped.id AS MLO,inv_unit.category,inv_unit.freight_kind,
			(SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No,
			(SELECT ctmsmis.cont_block(sparcsn4.inv_unit_fcy_visit.last_pos_slot,Yard_No)) AS Block_No,
			inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
			sparcsn4.vsl_vessel_visit_details.flex_date08, sparcsn4.ref_equip_type.iso_group,
			DATEDIFF(DATE(NOW()), DATE(sparcsn4.vsl_vessel_visit_details.flex_date08)) AS lying_days
			
			FROM sparcsn4.inv_unit 
			INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
			INNER JOIN ref_bizunit_scoped ON sparcsn4.inv_unit.line_op=sparcsn4.ref_bizunit_scoped.gkey

			INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey

			INNER JOIN sparcsn4.inv_unit_equip ON inv_unit.gkey=inv_unit_equip.unit_gkey 
			INNER JOIN sparcsn4.ref_equipment ON inv_unit_equip.eq_gkey=ref_equipment.gkey
			INNER JOIN sparcsn4.ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
			WHERE sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD' AND sparcsn4.inv_unit_fcy_visit.visit_state='1ACTIVE' 
			AND sparcsn4.inv_unit.category='IMPRT' ".$sql_cond." order by lying_days ASC";
			
			//return;
            //echo $strQuery;
			$sqlRslt=mysqli_query($con_sparcsn4, $sql);								
            $i=0;
			$j=0;
			while ($row=mysqli_fetch_object($sqlRslt))						
			{            
			$i++;

		 			$str="";
		 			$str1="";
					if($cont_type=="Normal")
					{
						$str=" and igm_detail_container.cont_imo=''";
						$str1=" and igm_sup_detail_container.cont_imo=''";
					}
					else if($cont_type=="IMDG")
					{
						$str=" and igm_detail_container.cont_imo !=''";
						$str1=" and igm_sup_detail_container.cont_imo!=''";
					}
					

					 $strIgm = "SELECT igm_detail_container.cont_number, igm_detail_container.cont_imo,  igm_details.Notify_name
							FROM igm_detail_container
							INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
							WHERE igm_details.Import_Rotation_No='$row->ib_vyg' AND igm_detail_container.cont_number='$row->cont_no' $str

							UNION 

							SELECT igm_sup_detail_container.cont_number, igm_sup_detail_container.cont_imo, igm_details.Notify_name
							FROM igm_sup_detail_container
							INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
							LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
							WHERE igm_supplimentary_detail.Import_Rotation_No='$row->ib_vyg' AND igm_sup_detail_container.cont_number='$row->cont_no' $str1 ";
					$res_igm = mysqli_query($con_cchaportdb,$strIgm);
				while($row_igm = mysqli_fetch_object($res_igm))
				{
					$j++;
					//$row_igm = mysqli_fetch_object($res_igm);
			        
					
				//if($row_igm->cont_number){ 
                ?>
	
				<tr align="center" bgcolor="#FCF7F7">
					<td align="center"><?php echo $j;?> </td>
					<td align="center"><?php if($row->cont_no) echo($row->cont_no); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->size) echo($row->size); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->height) echo($row->height); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->iso_group) echo($row->iso_group); else echo("&nbsp;");?></td>
					<?php if($cont_type=="IMDG") { ?>
					<td align="center"><?php if($row_igm->cont_imo) echo($row_igm->cont_imo); else echo("&nbsp;");?></td>
					<?php } ?>
					<td align="center"><?php if($row->MLO) echo($row->MLO); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->freight_kind) echo($row->freight_kind); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->ib_vyg) echo($row->ib_vyg); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->Yard_No) echo($row->Yard_No); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->last_pos_name) echo($row->last_pos_name); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row_igm->Notify_name) echo($row_igm->Notify_name); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->flex_date08) echo($row->flex_date08); else echo("&nbsp;");?></td>
					<td align="center"><?php if($row->lying_days) echo($row->lying_days); else echo("&nbsp;");?></td>
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
