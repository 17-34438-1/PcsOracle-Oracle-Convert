<style>
	.column 
	{
		float: left;
		width: 50%;
		padding: 10px;
		height: 300px; /* Should be removed. Only for demonstration */
	}

	/* Clear floats after the columns */
	.row:after 
	{	
		content: "";
		display: table;
		clear: both;
	}
</style>
<?php
	include("dbConection.php");
	include("dbOracleConnection.php");
?>
<html>
	<body>
		<div>
			<div align="center">
			</div>	
			<div align="center">			
				<table border=0 width="100%">
					<tr align="center">
						<td><img align="middle"  width="235px" height="80x" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
					</tr>				
					<tr align="center">
						<td colspan="10"><font size="4"><b>LAST 24 HOURS POSITION</b></font></td>
					</tr>
					<tr align="center">
						<td colspan="10"><font size="4">Date: <?php echo $date; ?></font></td>
					</tr>
					<tr align="center">
						<td colspan="10"><font size="4"><?php if($unit=='NCY' || $unit=='ICD') { ?>Unit: <?php } echo $unit; ?></font></td>
					</tr>	
				</table>
	
<?php		
	//$yards="SELECT block_cpa FROM ctmsmis.yard_block WHERE block_unit='$unit'";


	//customize yards for get blockcpa start
	 $blockListQuery="SELECT block FROM ctmsmis.yard_block WHERE block_unit='$unit' AND  block!='NULL' ORDER BY block ASC";
	
	$blockListRes=mysqli_query($con_sparcsn4,$blockListQuery);
	 $totalRow=mysqli_num_rows($blockListRes);
  
   
   //echo count($blockRowRes);
  
   $blockList="";
   $t=0;
  while($blockRow=mysqli_fetch_object($blockListRes)){
	
	    $blockString="";
	   $blockString=$blockRow->block;
		   
		if($t==($totalRow-1)){
		 $blockList=$blockList."'".$blockString."'";
		}
		else{
		 $blockList=$blockList."'".$blockString."',";

		}
		 $t++;
	}
  
   
	 $blockList;
	 //convert strin to array
	 $blockListArray = explode(',', $blockList);

	 //customize yards for get blockcpa end


	
	/* $sql_query="SELECT Block_No,size,COUNT(id) AS tot_cont FROM (
	SELECT a.id,
	(SELECT ctmsmis.cont_yard(b.last_pos_slot)) AS Yard_No,
	(SELECT ctmsmis.cont_block(b.last_pos_slot,Yard_No)) AS Block_No,
	(CASE
		WHEN (SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) 
			FROM sparcsn4.inv_unit_equip		
			INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey		
			INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey		
			WHERE sparcsn4.inv_unit_equip.unit_gkey=a.gkey LIMIT 1)=20 
		THEN 20
		ELSE 40 
	END) AS size
	FROM sparcsn4.inv_unit a  
	INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
	WHERE a.category='IMPRT' AND b.transit_state='S40_YARD' AND a.freight_kind!='MTY'
	AND
	(SELECT IF((SELECT block FROM ctmsmis.yard_block WHERE block=b.last_pos_slot AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block WHERE block=
	b.last_pos_slot  )=b.last_pos_slot,((SELECT terminal FROM ctmsmis.yard_block WHERE block=b.last_pos_slot  )),
	(SELECT terminal  FROM ctmsmis.yard_block WHERE b.last_pos_slot LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  ))) )=b.last_pos_slot,
	(SELECT block_cpa FROM ctmsmis.yard_block WHERE block=b.last_pos_slot AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block 
	WHERE block=b.last_pos_slot )=b.last_pos_slot,((SELECT terminal FROM ctmsmis.yard_block WHERE block=b.last_pos_slot )),
	(SELECT terminal  FROM ctmsmis.yard_block WHERE b.last_pos_slot LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )) )  ),
	(SELECT block_cpa  FROM ctmsmis.yard_block WHERE b.last_pos_slot LIKE CONCAT(block,'%') AND terminal=(SELECT IF((SELECT block 
	FROM ctmsmis.yard_block WHERE block=b.last_pos_slot )=b.last_pos_slot,((SELECT terminal FROM ctmsmis.yard_block WHERE block=b.last_pos_slot )),
	(SELECT terminal  FROM ctmsmis.yard_block WHERE b.last_pos_slot LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )))  ORDER BY 1  LIMIT 1  ))) 
	IN ($yards)
	) AS tbl
	WHERE Block_No IN ($yards) 
	GROUP BY Block_No,size
	ORDER BY Block_No,size";
	*/

		//final query

	 $sql="";
	 // final
    $sql_query="SELECT siz,sel_block,last_pos_slot,COUNT(id) AS tot_cont 
	FROM (
	SELECT a.id,sel_block,b.last_pos_slot,
	(CASE
	WHEN (
	SELECT SUBSTR(ref_equip_type.nominal_length,-2) 
	FROM inv_unit	
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=a.gkey fetch first 1 rows only
	)=20 
	THEN 20
	ELSE 40 
	END) AS siz
	FROM inv_unit a  
	INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
	
	INNER JOIN srv_event ON  srv_event.applied_to_gkey=a.gkey
	INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
	INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
	INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id
	
	WHERE a.category='IMPRT' AND b.transit_state='S40_YARD' AND a.freight_kind!='MTY' 
	)  tbl WHERE sel_block IN($blockList) GROUP BY siz,sel_block,last_pos_slot ORDER BY siz,sel_block";

	

			
	// $sql=mysqli_query($con_sparcsn4,$sql_query);		
	$sql = oci_parse($con_sparcsn4_oracle, $sql_query);
    oci_execute($sql);	
	
				
?>
	<div>		
		<table width="90%" border ='1' cellpadding='0' cellspacing='0' >
			<tr align="center">
				<td rowspan=2 colspan=2>OPENING BALANCE </td>
				<td colspan=3>APPRAISEMENT</td>
				<td colspan=3>DELIVERY</td>
				<td rowspan=2>RECEIVING</td>
				<td rowspan=2>REMOVAL</td>
				<td rowspan=2>SHIPMENT</td>
				<td rowspan=2>CLOSING BALANCE</td>
			</tr>
			<tr align="center">			
				<td>ASSIGN.</td>
				<td>K/DOWN</td>
				<td>W/DOWN</td>
				<td>ASSIGN.</td>
				<td>K/DOWN</td>
				<td>W/DOWN</td>
				<!--td>Y-3</td>
				<td>Y-5</td>
				<td>Y-6</td-->
			</tr>
<?php 	

    $tot_cl_20=0;
    $tot_cl_40=0;

	
	$i=0;
	
	while(($row=oci_fetch_object($sql))!=false)
	{
		
		
		   $last_pos_slot="";
		   $last_pos_slot=$row->LAST_POS_SLOT;

		     $sql_query1="SELECT IF(
			(
			SELECT block FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block WHERE block=
			'$last_pos_slot'  )='$last_pos_slot',
			((SELECT terminal FROM ctmsmis.yard_block WHERE block=' $last_pos_slot' )),
			(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  ))) 
			)='$last_pos_slot',
			
			(SELECT block_cpa FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block 
			WHERE block=' $last_pos_slot' )=' $last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
			(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )) )  ),
			
			(SELECT block_cpa  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') AND terminal=(SELECT IF((SELECT block 
			FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
			(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )))  ORDER BY 1  LIMIT 1  )
			)  AS res";
			 $sql_query1_Res = mysqli_query($con_sparcsn4,$sql_query1);
			 $row_res = mysqli_fetch_object($sql_query1_Res);
			 $block_res="";
			 $block_res=$row_res->res;
			 $status=0;
			 foreach($blockListArray as  $val){
			   
				if($val== $block_res){
					$status=1;
				}
			 }

		
?>
<?php 
         if( $status==1){
			$i++;
        
	
		
			//oracle convert query
	  
		$ass_query="SELECT a.id,last_pos_slot,
		NVL(
		(
		SELECT SUBSTR(srv_event_field_changes.new_value,7)
		FROM srv_event
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
		WHERE  srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey IN(18,13,16) 
		AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !=''
		AND srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey
		<
		(
		SELECT srv_event.gkey FROM srv_event
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.event_type_gkey=4 AND srv_event.applied_to_gkey=a.gkey 
		AND metafield_id='unitFlexString01' AND new_value IS NOT NULL ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only
		)ORDER BY srv_event.gkey DESC FETCH FIRST 1 ROWS only
		)
		,
		(
		SELECT SUBSTR(srv_event_field_changes.new_value,7)
		FROM srv_event
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
		WHERE srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey IN(18,13,16) ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only
		)
		)carrentPosition,flex_date01
		FROM inv_unit a  
		INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
		INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value
		WHERE  cast(b.flex_date01 as date)= to_date('$date','yyyy-mm-dd') AND
		UPPER(mfdch_value) IN ('APPCUS','APPOTH','APPREF','APPDLV2H','APPDLVGRD') AND a.category='IMPRT'
		AND
		(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only)=$row->SIZ";

		
		

		
		$ass_rtn = oci_parse($con_sparcsn4_oracle, $ass_query);
        oci_execute($ass_rtn);
		
		$block_rse2="";
		$countId_tot_cont_ass=0;
		
		while(($ass_row=oci_fetch_object($ass_rtn))!=false){
			 $last_pos_slot="";
			 $last_pos_slot=$ass_row->LAST_POS_SLOT;
			 $cont_id=$ass_row->ID;

			 $ass_query2="SELECT IF(
			(SELECT block FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block WHERE block=
			'$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot'  )),
			(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  ))) )='$last_pos_slot',
			(SELECT block_cpa FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block 
			WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
			(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )) )  ),
			(SELECT block_cpa  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') AND terminal=(SELECT IF((SELECT block 
			FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
			(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )))  ORDER BY 1  LIMIT 1  )
			) block_res";
			$sql_query2_Res = mysqli_query($con_sparcsn4,$ass_query2);
			$row_res2 = mysqli_fetch_object($sql_query2_Res);	 
		    $block_rse2=$row_res2->block_res;
		  
			if($block_rse2==$row->SEL_BLOCK && $block_rse2!==null ){
				$countId_tot_cont_ass=$countId_tot_cont_ass+1;
			}

		}
		 
		
	
		

		$kd_query="
		SELECT a.id,last_pos_slot,
		NVL((SELECT SUBSTR(srv_event_field_changes.new_value,7)
		FROM srv_event
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
		WHERE srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey IN(18,13,16) AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !='' AND srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey<(SELECT srv_event.gkey FROM srv_event
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
		WHERE srv_event.event_type_gkey=4 AND srv_event.applied_to_gkey=a.gkey AND metafield_id='unitFlexString01' AND new_value IS NOT NULL ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only) ORDER BY srv_event.gkey DESC fetch first 1 rows only
		),
		(
		SELECT SUBSTR(srv_event_field_changes.new_value,7)
		FROM srv_event
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
		WHERE srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey IN(18,13,16) ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only
		)
		) AS carrentPosition
		
		FROM inv_unit a  
		INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
		INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value
		WHERE cast(b.flex_date01 as date)= to_date('$date','yyyy-mm-dd')
		AND UPPER(mfdch_value) IN ('APPCUS','APPOTH','APPREF','APPDLV2H','APPDLVGRD') AND a.category='IMPRT'
		AND b.time_in IS NOT NULL
		AND
		(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
		WHERE inv_unit_fcy_visit.unit_gkey=a.gkey fetch first 1 rows only)=$row->SIZ
		";

		
		
		
				$kd_rtn = oci_parse($con_sparcsn4_oracle, $kd_query);
				oci_execute($kd_rtn);


			   
			    $count_Id_tot_cont_kd=0;
				
				while(($kd_row=oci_fetch_object($kd_rtn))!=false){	
				$last_pos_slot="";
				 $last_pos_slot=$kd_row->LAST_POS_SLOT;
					
				 $kd_query2="SELECT IF(
				(SELECT block FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block WHERE block=
				'$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot'  )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  ))) )='$last_pos_slot',
				(SELECT block_cpa FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block 
				WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )) )  ),
				(SELECT block_cpa  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') AND terminal=(SELECT IF((SELECT block 
				FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )))  ORDER BY 1  LIMIT 1  )
				) block_res";
				$kd_Res = mysqli_query($con_sparcsn4,$kd_query2);
				$row_Kd = mysqli_fetch_object($kd_Res);	 

				$block_res="";
				$block_res=$row_Kd->block_res;
			
				
				
			
				if($block_res==$row->SEL_BLOCK && $row_Kd->block_res!==null ){
					//$count_Id_tot_cont_kd++;
					
					 $count_Id_tot_cont_kd=$count_Id_tot_cont_kd+1;
				}

		       }
		
		
	
		 $ass_query_dlv="SELECT COUNT(id) AS tot_cont_ass
		FROM
		(
		SELECT a.id,SEL_BLOCK,
		(SELECT SUBSTR(srv_event_field_changes.new_value,7)
		FROM srv_event
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
		WHERE srv_event.applied_to_gkey=a.gkey AND srv_event.event_type_gkey=18) AS pos
		FROM inv_unit a  
		INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
		INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value

		INNER JOIN srv_event ON  srv_event.applied_to_gkey=a.gkey
		INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
		INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
		INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id

		WHERE b.flex_date01 BETWEEN to_date(CONCAT('$date',' 07:59:59'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')
		AND UPPER(mfdch_value) IN ('DLV2H','DLVGRD','DLVGRT2H','DLVGRTGRD','DLVHYS','DLVOTH','DLVREF2H','DLVREFGRD')
		AND a.category='IMPRT'
		AND
		(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only)=$row->SIZ
		) tbl WHERE tbl.SEL_BLOCK IN($blockList) ";
		
		
		$ass_rtn_dlv = oci_parse($con_sparcsn4_oracle, $ass_query_dlv);
		oci_execute($ass_rtn_dlv);
		
		$tot_cont_ass=0;
		while(($ass_row_dlv = oci_fetch_object($ass_rtn_dlv))!=false){
			$tot_cont_ass=$ass_row_dlv->TOT_CONT_ASS;
		}
	
		
	
			$kd_query_dlv="SELECT COUNT(id) AS tot_cont_kd
			FROM
			(
			SELECT a.id,(SELECT SUBSTR(srv_event_field_changes.new_value,7)
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=a.gkey AND srv_event.event_type_gkey=18) AS pos,SEL_BLOCK
	
			FROM inv_unit a  
			INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
			INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value

			INNER JOIN srv_event ON  srv_event.applied_to_gkey=a.gkey
			INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
			INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
			INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id

			WHERE b.flex_date01 BETWEEN to_date(CONCAT('$date',' 07:59:59'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')
			AND UPPER(mfdch_value) IN ('DLV2H','DLVGRD','DLVGRT2H','DLVGRTGRD','DLVHYS','DLVOTH','DLVREF2H','DLVREFGRD')
			AND a.category='IMPRT'
			AND b.time_in IS NOT NULL
			AND
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=a.gkey fetch first 1 rows only)= $row->SIZ
			) tbl WHERE tbl.SEL_BLOCK IN($blockList)";
		
		
		$kd_rtn_dlv="";
		$kd_rtn_dlv = oci_parse($con_sparcsn4_oracle, $kd_query_dlv);
		oci_execute($kd_rtn_dlv);
		
		$tot_cont_kd=0;
		while(($kd_row_dlv = oci_fetch_object($kd_rtn_dlv))!=false){
			$tot_cont_kd=$kd_row_dlv->TOT_CONT_KD;
		}
		
	
		$rcv_qry="SELECT COUNT(*) as tot_cont_rcv
		 FROM
		(
		SELECT inv_unit.id,SEL_BLOCK,
		(SELECT SUBSTR(srv_event_field_changes.new_value,7)
		FROM srv_event
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
		WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey=18) AS pos
		FROM argo_carrier_visit
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
		INNER JOIN inv_unit ON inv_unit.gkey=inv_unit_fcy_visit.unit_gkey

		INNER JOIN srv_event ON  srv_event.applied_to_gkey=inv_unit.gkey
		INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
		INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
		INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id

		WHERE PHASE IN('40WORKING','60DEPARTED') AND carrier_mode='VESSEL'
		AND inv_unit_fcy_visit.time_in BETWEEN to_date(CONCAT('$date',' 07:59:59'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')
		AND inv_unit.category='IMPRT'
		AND
		(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only)= $row->SIZ
		) tbl WHERE tbl.SEL_BLOCK IN($blockList)";
	
		
		$rtn_rcv = oci_parse($con_sparcsn4_oracle, $rcv_qry);
		oci_execute($rtn_rcv);
		$tot_cont_rcv=0;
		while(($row_rcv = oci_fetch_object($rtn_rcv))!=false){
			$tot_cont_rcv=$row_rcv->TOT_CONT_RCV;
		}
		
		// OFFDOCK REMOVAL
		
		$removal_qry="SELECT COUNT(id) as tot_rmv_cont  FROM 
		( SELECT inv_unit.id ,SEL_BLOCK,
		(
		SELECT SUBSTR(srv_event_field_changes.prior_value,7) FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey 
		IN(22) ORDER BY srv_event.gkey DESC fetch first 1 rows only
		) AS slot
		
		FROM inv_unit 
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv 
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey 
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
		INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods 
		INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
		INNER JOIN road_truck_transactions ON road_truck_transactions.unit_gkey=inv_unit.gkey
		INNER JOIN road_truck_visit_details ON road_truck_visit_details.tvdtls_gkey=road_truck_transactions.truck_visit_gkey

		INNER JOIN srv_event ON  srv_event.applied_to_gkey=inv_unit.gkey
		INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
		INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
		INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id

		WHERE inv_unit_fcy_visit.time_load
		BETWEEN to_date(CONCAT('$date',' 07:59:59'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')
		AND inv_goods.destination NOT IN('2591','2592','BDCGP') AND inv_unit.category='IMPRT'
		AND inv_goods.destination IS NOT NULL AND road_truck_transactions.status !='CANCEL'
		AND
		(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only)= $row->SIZ	
		) tbl WHERE tbl.SEL_BLOCK IN($blockList)";
		
		
		$rtn_removal = oci_parse($con_sparcsn4_oracle, $removal_qry);
		oci_execute($rtn_removal);
		$tot_rmv_cont=0;
		while(($row_removal = oci_fetch_object($rtn_removal))!=false){
			 $tot_rmv_cont=$row_removal->TOT_RMV_CONT;
		}
		
		if($row->SIZ==20)
		{
		    $tot_cl_20=$tot_cl_20+(($row->TOT_CONT+$tot_cont_rcv) - ($tot_cont_kd+$tot_rmv_cont));
			
		}
		else
		{
			 $tot_cl_40=$tot_cl_40+(($row->TOT_CONT +$tot_cont_rcv) - ($tot_cont_kd+$tot_rmv_cont));
		}
		
		?>
			<tr align="center">

				
				
				<!-- <td ><?php echo $$row->Block_No; ?>  -->
				<td ><?php echo $row->SEL_BLOCK; ?> 
				<td><?php echo $row->TOT_CONT."    X  ".$row->SIZ."'"; ?></td>
				<td><?php echo $countId_tot_cont_ass; ?></td>
				<td><?php echo $count_Id_tot_cont_kd; ?></td>
				<td><?php echo $count_Id_tot_cont_kd; ?></td>
				<td><?php echo $tot_cont_ass; ?></td>
				<td><?php echo $tot_cont_kd; ?></td>
				<td><?php echo $tot_cont_kd; ?></td>
				<td><?php echo $tot_cont_rcv; ?></td>
				<td><?php echo $tot_rmv_cont; ?></td>
				<td><?php echo "0"; ?></td>
				<td><?php echo (($row->TOT_CONT+$tot_cont_rcv) - ($tot_cont_kd+$tot_rmv_cont))."    X  ".$row->SIZ."'"; ?></td>
			</tr>
		
	      <?php 
		 }
		?>

	
		
		
		
<?php } ?>
		
		</table>
	</div>	
	<div width="90%" style="margin-left: 64px;">
	  <div>
	  <table  width="45%" align="left">
		<tr><td>
		<table width="80%" border =0 cellpadding='0' cellspacing='0' >
			<tr><td><nobr>LAST 24 HOURS DELIVERY DATA</nobr></td></tr>
		</table>
		<table width="80%" border ='1' cellpadding='0' cellspacing='0'>
		<?php 
		$size_str="";
		$cont_ton=0;
		$cont_pack=0;
		$cont_20=0;
		$cont_40=0;
	
		 $lst_dlv_data="SELECT id,ib_vyg
		FROM
		(
		SELECT a.id,vsl_vessel_visit_details.ib_vyg,SEL_BLOCK,
		(SELECT SUBSTR(srv_event_field_changes.new_value,7)
		FROM srv_event
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
		WHERE srv_event.applied_to_gkey=a.gkey AND srv_event.event_type_gkey=18) AS pos
		FROM inv_unit a  
		INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
		INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=b.actual_ib_cv
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey 
		
		INNER JOIN srv_event ON  srv_event.applied_to_gkey=a.gkey
		INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
		INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
		INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id fetch first 5 rows only
		)  tbl ";
		
		$rtn_lst_dlv_data="";
		$rtn_lst_dlv_data = oci_parse($con_sparcsn4_oracle, $lst_dlv_data);
		oci_execute($rtn_lst_dlv_data);
		
		while(($row_lst_dlv_data=oci_fetch_object($rtn_lst_dlv_data))!=false)
		{
		
		
			 include("mydbPConnection.php");
			
			 $igm_info="SELECT igm_detail_container.cont_size,ROUND((cont_gross_weight/1000),2) AS ton,igm_details.Pack_Number 
			FROM igm_details 
			INNER JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id
			WHERE Import_Rotation_No='$row_lst_dlv_data->IB_VYG' AND cont_number='$row_lst_dlv_data->ID'";
		 	
		    $rtn_igm_info=mysqli_query($con_cchaportdb,$igm_info);
			$row_igm_info=mysqli_fetch_object($rtn_igm_info);
		
			
		    $cont_ton=$cont_ton+$row_igm_info->ton;
			$cont_pack=$cont_pack+$row_igm_info->Pack_Number;
			
			if($row_igm_info->cont_size==20)
			{
				$cont_20++;
			}
			else
			{
				$cont_40++;
			}
			
			mysqli_close($con_cchaportdb);
		}
		
		
		?>
		
			<tr>
				<td><nobr>TOTAL DELIVERY</nobr></td>
				<td><?php echo $cont_20." X 20' + ".$cont_40." X 40'"; ?></td>
			</tr>
			<tr>
				<td>PACKAGES</td>
				<td><?php echo $cont_pack; ?></td>
			</tr>
			<tr>
				<td>TONS</td>
				<td><?php echo $cont_ton; ?></td>
			</tr>
		</table>
		
			  <table><tr><td>&nbsp;</td></tr></table>
	<?php 
			
			//return fcl_query;
	        $fcl_query="select  SUM(tot_del_20_day) AS tot_del_20_day,SUM(tot_del_20_eve) AS tot_del_20_eve,SUM(tot_del_20_night) AS tot_del_20_night,
			SUM(tot_del_40_day) AS tot_del_40_day,SUM(tot_del_40_eve) AS tot_del_40_eve,SUM(tot_del_40_night) AS tot_del_40_night
			from(
			SELECT tbl.*,
			( CASE
			WHEN
			tbl.flex_date01 
			BETWEEN to_date(CONCAT('$date','08:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS')-1
			AND 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=tbl.gkey fetch first 1 rows only) =20 
			THEN 1 ELSE 0 END ) as tot_del_20_day,
			(CASE
			WHEN 
			tbl.flex_date01
			BETWEEN to_date(CONCAT('$date','16:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 23:59:59'),'YYYY-MM-DD HH24-MI-SS')-1
			AND 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=tbl.gkey fetch first 1 rows only) ='20' 
			THEN 1 ELSE 0 END ) AS tot_del_20_eve,
			( CASE
			WHEN 
			tbl.flex_date01 BETWEEN to_date(CONCAT('$date','00:00:01'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')
					 AND 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=tbl.gkey fetch first 1 rows only) ='20' 
			THEN 1 ELSE 0 END ) AS tot_del_20_night,
			( CASE
			WHEN 
			tbl.flex_date01 
			BETWEEN to_date(CONCAT('$date',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS')-1
			
			AND 
			(
			SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=tbl.gkey fetch first 1 rows only) >='40' 
			THEN 1 ELSE 0 END 
			) AS tot_del_40_day,
			(CASE
			WHEN 
			tbl.flex_date01 BETWEEN
			to_date(CONCAT('$date','16:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 23:59:59'),'YYYY-MM-DD HH24-MI-SS')-1
			AND 
			(SELECT substr(ref_equip_type.nominal_length,2) FROM inv_unit	
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=tbl.gkey fetch first 1 rows only) >='40' 
			THEN '1' ELSE '0' END ) AS tot_del_40_eve,
			(CASE
			WHEN 
			tbl.flex_date01
			BETWEEN to_date(CONCAT('$date',' 00:00:01'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')
					 AND 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=tbl.gkey fetch first 1 rows only) >='40' 
			THEN 1 ELSE 0 END ) AS tot_del_40_night
			
			FROM 
			(
			SELECT a.gkey,SEL_BLOCK,
			(SELECT SUBSTR(srv_event_field_changes.new_value,7)
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=a.gkey AND srv_event.event_type_gkey=18) AS pos,
			a.id,b.flex_date01,
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) AS siz
			
			
			FROM inv_unit a  
			INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
			INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value

            INNER JOIN srv_event ON  srv_event.applied_to_gkey=a.gkey
			INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
			INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
			INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id

			WHERE a.freight_kind='FCL' AND b.flex_date01>to_date(CONCAT('$date',' 07:59:59'),'YYYY-MM-DD HH24-MI-SS')-1 AND  
			b.flex_date01 < to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS') AND a.category='IMPRT'
			AND UPPER(mfdch_value) IN ('DLV2H','DLVGRD','DLVGRT2H','DLVGRTGRD','DLVHYS','DLVOTH','DLVREF2H','DLVREFGRD')
			)  tbl WHERE tbl.SEL_BLOCK IN($blockList)
			)tbl2";

			$fcl_sql="";
			$fcl_sql = oci_parse($con_sparcsn4_oracle, $fcl_query);
		    oci_execute($fcl_sql);
			

	 ?>
	 
		<table width="100%" border =0 cellpadding='0' cellspacing='0'>
			<tr><td><nobr>LAST 24 HOURS FCL CONTAINER POSITION</nobr></td></tr>
		</table>
		<?php
			
				while(($row_fcl=oci_fetch_object($fcl_sql))!=false)
				{
			$i++;
			?>
		<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
			<tr>
				<td></td>
				<td></td>
				<td>20'</td>
				<td>40'</td>
			</tr>
			<tr>
				<td rowspan="3">DELIVERY</td>
				<td>DAY</td>
				<td><?php if($row_fcl->TOT_DEL_20_DAY!=("" || 0)){ echo $row_fcl->TOT_DEL_20_DAY; } else echo "-"; ?></td>
				<td><?php if($row_fcl->TOT_DEL_40_DAY!=("" || 0)){ echo $row_fcl->TOT_DEL_40_DAY; } else echo "-"; ?></td>
			</tr>
			<tr>
				<td>EVENING</td>
				<td><?php echo $row_fcl->TOT_DEL_20_EVE; ?></td>
				<td><?php echo $row_fcl->TOT_DEL_40_EVE; ?></td>
			</tr>
			<tr>
				<td>NIGHT</td>
				<td><?php if($row_fcl->TOT_DEL_20_NIGHT!=("" || 0)){ echo $row_fcl->TOT_DEL_20_NIGHT; } else echo "-"; ?></td>
				<td><?php if($row_fcl->TOT_DEL_40_NIGHT!=("" || 0)){ echo $row_fcl->TOT_DEL_40_NIGHT; } else echo "-"; ?></td>
			</tr>
			
			<?php
			
		

			$app_fcl="SELECT a.id,last_pos_slot,
			(
			 CASE
			WHEN (SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			WHERE inv_unit_fcy_visit.unit_gkey=a.gkey)=20 THEN 1 ELSE 0 END 
			) size_20_app,
			(
			CASE
			WHEN (SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) >=40 THEN 1 ELSE 0 END 
			) size_40_app
			FROM inv_unit a  
			INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
			INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value
			WHERE b.flex_date01 
			BETWEEN to_date(CONCAT('$date','07:59:59'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date','08:00:00'),'YYYY-MM-DD HH24-MI-SS')
			AND UPPER(mfdch_value) IN ('APPCUS','APPOTH','APPREF','APPDLV2H','APPDLVGRD') AND a.category='IMPRT'";

			
			$rtn_app_fcl = oci_parse($con_sparcsn4_oracle, $app_fcl);
		    oci_execute($rtn_app_fcl);


			$size_20_app=0;
			$size_40_app=0;
			$app_fcl_Res="";
		
		
			while(($row_app_fcl=oci_fetch_object($rtn_app_fcl))!=false){
				  $last_pos_slot="";
				  $last_pos_slot=$row_app_fcl->LAST_POS_SLOT;

				 $app_fcl2="SELECT IF(
				(SELECT block FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block WHERE block=
				'$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot'  )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  ))) )='$last_pos_slot',
				(SELECT block_cpa FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block 
				WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )) )  ),
				(SELECT block_cpa  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') AND terminal=(SELECT IF((SELECT block 
				FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )))  ORDER BY 1  LIMIT 1  )
				) block_res";
				$app_fcl_Res = mysqli_query($con_sparcsn4,$app_fcl2);
				$row_app_fcl2 = mysqli_fetch_object($app_fcl_Res);	 
				
				$block_res="";
				$block_res=$row_app_fcl2->block_res;
				$status=0;
				foreach($blockListArray as  $val){
				   if($val== $block_res){
					   $status=1;
				   }
				}
                
				if($status=1){
					 $size_20_app=$size_20_app+$row_app_fcl->SIZE_20_APP;
					$size_40_app=$size_40_app+$row_app_fcl->SIZE_40_APP;
				}

				
			}
			
			?>
			
			
			<tr>
				<td>APPRAISEMENT</td>
				<td></td>
				<td><?php if($size_20_app!=("" || 0)){ echo $size_20_app; } else echo "-"; ?></td>
				<td><?php if($size_40_app!=("" || 0)){ echo $size_40_app; } else echo "-"; ?></td>
			</tr>

			
			
			<?php 
			
		

			//sparcn4
            $fcl_cont_rcv="SELECT 
			SUM(rcv_20_day) AS rcv_20_day,SUM(rcv_40_day) AS rcv_40_day,SUM(rcv_20_eve) AS rcv_20_eve,SUM(rcv_40_eve) AS rcv_40_eve,
			SUM(rcv_20_night) AS rcv_20_night,SUM(rcv_40_night) AS rcv_40_night  
			FROM
			(
			SELECT inv_unit.id,inv_unit_fcy_visit.time_in,sel_block,
			
			(CASE
			WHEN 
			inv_unit_fcy_visit.time_in
			BETWEEN to_date(CONCAT('$date','08:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS')-1
			AND 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) ='20' 
			THEN 1 ELSE 0 END ) AS rcv_20_day,
			
			(CASE
			WHEN 
			inv_unit_fcy_visit.time_in 
			BETWEEN to_date(CONCAT('$date','08:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS')-1
			AND 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
			INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) >='40' 
			THEN 1 ELSE 0 END ) AS rcv_40_day,
			(CASE
			WHEN 
			inv_unit_fcy_visit.time_in
			BETWEEN to_date(CONCAT('$date','16:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 23:59:59'),'YYYY-MM-DD HH24-MI-SS')-1
			AND 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) ='20' 
			THEN 1 ELSE 0 END ) AS rcv_20_eve,
			(CASE
			WHEN 
			inv_unit_fcy_visit.time_in
			BETWEEN to_date(CONCAT('$date','16:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 23:59:59'),'YYYY-MM-DD HH24-MI-SS')-1
			AND 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) >='40' 
			THEN 1 ELSE 0 END ) AS rcv_40_eve,
			
			(CASE
			WHEN 
			inv_unit_fcy_visit.time_in
			BETWEEN to_date(CONCAT('$date','00:00:01'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')
			AND 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) ='20' 
			THEN 1 ELSE 0 END ) AS rcv_20_night,
			(CASE
			WHEN 
			inv_unit_fcy_visit.time_in
			BETWEEN to_date(CONCAT('$date','00:00:01'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')
			AND 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) >='40' 
			THEN 1 ELSE 0 END ) AS rcv_40_night,
			
			(SELECT SUBSTR(srv_event_field_changes.new_value,7)
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey=18) AS pos
			
			FROM argo_carrier_visit
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
			INNER JOIN inv_unit ON inv_unit.gkey=inv_unit_fcy_visit.unit_gkey
			
			INNER JOIN srv_event ON  srv_event.applied_to_gkey=inv_unit.gkey
			INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
			INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
			INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id
			
			WHERE PHASE IN('40WORKING','60DEPARTED') AND carrier_mode='VESSEL' AND inv_unit.category='IMPRT' AND inv_unit.freight_kind='FCL'
			AND inv_unit_fcy_visit.time_in 
			BETWEEN to_date(CONCAT('$date','07:59:59'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')
			)  tbl WHERE tbl.sel_block IN ($blockList)";
			
		
			
			
			$rtn_fcl_cont_rcv = oci_parse($con_sparcsn4_oracle, $fcl_cont_rcv);
		    oci_execute($rtn_fcl_cont_rcv);

			$rcv_20_day="";
			$rcv_40_day="";
			$rcv_20_eve="";
			$rcv_40_eve="";
			$rcv_20_night="";
			while(($row_fcl_cont_rcv=oci_fetch_object($rtn_fcl_cont_rcv))!=false){
				$rcv_20_day=$row_fcl_cont_rcv->RCV_20_DAY;
				$rcv_40_day=$row_fcl_cont_rcv->RCV_40_DAY;
				$rcv_20_eve=$row_fcl_cont_rcv->RCV_20_EVE;
				$rcv_40_eve=$row_fcl_cont_rcv->RCV_40_EVE;
			    $rcv_20_night=$row_fcl_cont_rcv->RCV_20_NIGHT;
				$rcv_40_night=$row_fcl_cont_rcv->RCV_40_NIGHT;
			}
			?>
			
			
			
			<tr>
				<td rowspan="3">FCL CONTAINER RECEIVING </td>
				<td>DAY</td>
				<td><?php echo $rcv_20_day; ?></td>
				<td><?php echo $rcv_40_day; ?></td>
			</tr>
			<tr>
				<td>EVENING</td>
				<td><?php echo $rcv_20_eve; ?></td>
				<td><?php echo $rcv_40_eve; ?></td>
			</tr>
			<tr>
				<td>NIGHT</td>
				<td><?php echo $rcv_20_night; ?></td>
				<td><?php echo $rcv_40_night; ?></td>
			</tr>
			
			<?php 
			

			?>
			
			<tr>
				<td>FCL LYING LOAD </td>
				<td></td>
				<!--td><?php echo $row_fcl_lying_load->fcl_lying_20; ?></td>
				<td><?php echo $row_fcl_lying_load->fcl_lying_40; ?></td-->
				
				<td><?php echo $tot_cl_20; ?></td>
				<td><?php echo $tot_cl_40; ?></td>
			</tr>
			
			<?php 
			

			
			 $mty_cont_rcv="SELECT 
			SUM(rcv_20_mty) AS rcv_20_mty,SUM(rcv_40_mty) AS rcv_40_mty
			FROM
			(
			SELECT inv_unit.id,inv_unit_fcy_visit.time_in,sel_block,
			(CASE
			WHEN 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only) ='20' 
			THEN 1 ELSE 0 END ) AS rcv_20_mty,
			(CASE
			WHEN 
			(SELECT SUBSTR(ref_equip_type.nominal_length,2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only) >='40' 
			THEN 1 ELSE 0 END ) AS rcv_40_mty,
			
			(SELECT SUBSTR(srv_event_field_changes.new_value,7)
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey=18) AS pos
			FROM argo_carrier_visit
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
			INNER JOIN inv_unit ON inv_unit.gkey=inv_unit_fcy_visit.unit_gkey

			INNER JOIN srv_event ON  srv_event.applied_to_gkey=inv_unit.gkey
			INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
			INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
			INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id
			
			WHERE PHASE IN('40WORKING','60DEPARTED') AND carrier_mode='VESSEL' AND inv_unit.category='IMPRT' AND inv_unit.freight_kind='MTY'
			AND inv_unit_fcy_visit.time_in 
			BETWEEN to_date(CONCAT('$date','08:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 07:59:59'),'YYYY-MM-DD HH24-MI-SS')
			)  tbl  WHERE tbl.sel_block IN ($blockList)";


			
			
			$rtn_mty_cont_rcv = oci_parse($con_sparcsn4_oracle, $mty_cont_rcv);
		    oci_execute($rtn_mty_cont_rcv);

			$rcv_20_mty=0;
			$rcv_40_mty=0;
			while(($row_mty_cont_rcv=oci_fetch_object($rtn_mty_cont_rcv))!=false){
				$rcv_20_mty=$row_mty_cont_rcv->RCV_20_MTY;
				$rcv_40_mty=$row_mty_cont_rcv->RCV_40_MTY;
			}

			?>
			
			
			
			<tr>
				<td>EMPTY CONT REC. </td>
				<td></td>
				<td><?php echo $rcv_20_mty; ?></td>
				<td><?php echo $rcv_40_mty; ?></td>
			</tr>
			<?php 
	
			
			//completed
				    $mty_cont_rmv="SELECT 
					SUM(rcv_20_day) AS mty_rmv_20_day,SUM(rcv_40_day) AS mty_rmv_40_day,SUM(rcv_20_eve) AS mty_rmv_20_eve,SUM(rcv_40_eve) AS mty_rmv_40_eve,
					SUM(rcv_20_night) AS mty_rmv_20_night,SUM(rcv_40_night) AS mty_rmv_40_night  
					from (
					select tbl.* ,(CASE
					WHEN 
					tbl.time_out
					BETWEEN to_date(CONCAT('$date','08:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS')-1
					AND 
					(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) ='20' 
					THEN 1 ELSE 0 END ) AS rcv_20_day,
					(CASE
					WHEN 
					tbl.time_out 
					BETWEEN to_date(CONCAT('$date','08:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS')-1
					AND 
					(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit	
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) >='40' 
					THEN 1 ELSE 0 END ) AS rcv_40_day,
					(CASE
					WHEN 
					tbl.time_out 
					BETWEEN to_date(CONCAT('$date','16:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 23:59:59'),'YYYY-MM-DD HH24-MI-SS')-1
					AND 
					(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) ='20' 
					THEN 1 ELSE 0 END ) AS rcv_20_eve,
					(CASE
					WHEN 
					tbl.time_out
					BETWEEN to_date(CONCAT('$date','16:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 23:59:59'),'YYYY-MM-DD HH24-MI-SS')-1
					AND 
					(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit	
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) >='40' 
					THEN 1 ELSE 0 END ) AS rcv_40_eve,
					
					(CASE
					WHEN 
					tbl.time_out
					BETWEEN to_date(CONCAT('$date','00:00:01'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')
					AND 
					(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) ='20' 
					THEN 1 ELSE 0 END ) AS rcv_20_night,
					(CASE
					WHEN 
					tbl.time_out
					BETWEEN to_date(CONCAT('$date','00:00:01'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$date',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')
					AND 
					(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) >='40' 
					THEN 1 ELSE 0 END ) AS rcv_40_night
					
					FROM
					(
					SELECT inv_unit.id,inv_unit_fcy_visit.time_in,sel_block,time_out,
					(SELECT SUBSTR(srv_event_field_changes.new_value,7)
					FROM srv_event
					INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
					WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey=18) AS pos
					
					FROM argo_carrier_visit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
					INNER JOIN inv_unit ON inv_unit.gkey=inv_unit_fcy_visit.unit_gkey
					
					
					INNER JOIN srv_event ON  srv_event.applied_to_gkey=inv_unit.gkey
					INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
					INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
					INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id
					
					WHERE PHASE IN('40WORKING','60DEPARTED') AND carrier_mode='VESSEL' AND inv_unit.category='IMPRT' AND inv_unit.freight_kind='MTY'
					AND inv_unit_fcy_visit.time_out
					BETWEEN to_date(CONCAT('$date','16:00:01'),'YYYY-MM-DD HH24-MI-SS')-1 AND to_date(CONCAT('$date',' 23:59:59'),'YYYY-MM-DD HH24-MI-SS')-1
					)  tbl where tbl.sel_block IN($blockList) 
					)tbl2 
					";
			
					

					$rtn_mty_cont_rmv = oci_parse($con_sparcsn4_oracle, $mty_cont_rmv);
		            oci_execute($rtn_mty_cont_rmv);
                   
					$mty_rmv_20_day=0;
					$mty_rmv_40_day=0;
					$mty_rmv_20_eve=0;
					$mty_rmv_40_eve=0;
					$mty_rmv_20_night=0;
					$mty_rmv_40_night=0;
					while(($row_mty_cont_rmv=oci_fetch_object($rtn_mty_cont_rmv))!=false){
						
					
						$mty_rmv_20_day= $row_mty_cont_rmv->MTY_RMV_20_DAY;
						$mty_rmv_40_day=$row_mty_cont_rmv->MTY_RMV_40_DAY;
						$mty_rmv_20_eve=$row_mty_cont_rmv->MTY_RMV_20_EVE;
						$mty_rmv_40_eve=$row_mty_cont_rmv->MTY_RMV_40_EVE;
						$mty_rmv_20_night=$row_mty_cont_rmv->MTY_RMV_20_NIGHT;
						$mty_rmv_40_night=$row_mty_cont_rmv->MTY_RMV_40_NIGHT;
					}
			?>
			
			<tr>
				<td rowspan="3">EMPTY CONT REMOVE </td>
				<td>DAY</td>
				<td><?php echo $mty_rmv_20_day; ?></td>
				<td><?php echo $mty_rmv_40_day; ?></td>
			</tr>
			<tr>
				<td>EVENING</td>
				<td><?php echo $mty_rmv_20_eve; ?></td>
				<td><?php echo $mty_rmv_40_eve; ?></td>
			</tr>
			<tr>
				<td>NIGHT</td>
				<td><?php echo $mty_rmv_20_night; ?></td>
				<td><?php echo $mty_rmv_40_night; ?></td>
			</tr>
			
			
			<?php 
			
		
			 $mty_lying="
			 SELECT a.id,last_pos_slot,
			 (CASE
			 WHEN (SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			 INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			 INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			 INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			 WHERE inv_unit_fcy_visit.unit_gkey=a.gkey fetch first 1 rows only)=20 THEN 1
			 ELSE 0 END ) AS mty_lying_20,
			 (CASE
			 WHEN (SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			 INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			 INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
			 INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			 WHERE inv_unit_fcy_visit.unit_gkey=a.gkey fetch first 1 rows only) >=40 THEN 1
			 ELSE 0 END ) AS mty_lying_40
			 
			 FROM inv_unit a  
			 INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
			 WHERE a.category='IMPRT' AND b.transit_state='S40_YARD' AND a.freight_kind='MTY'
			 ";
		
			
			
			$rtn_mty_lying = oci_parse($con_sparcsn4_oracle, $mty_lying);
		    oci_execute($rtn_mty_lying);

			$mty_lying_20=0;
			$mty_lying_40=0;
			$mty_lying2_Res="";
			
			
			while(($row_mty_lying=oci_fetch_object($rtn_mty_lying))!=false){
				
				  
				  $last_pos_slot="";
			
				  $last_pos_slot=$row_mty_lying->LAST_POS_SLOT;

				 $mty_lying2_qu="SELECT IF(
				(SELECT block FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block WHERE block=
				'$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot'  )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  ))) )='$last_pos_slot',
				(SELECT block_cpa FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block 
				WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )) )  ),
				(SELECT block_cpa  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') AND terminal=(SELECT IF((SELECT block 
				FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )))  ORDER BY 1  LIMIT 1  )
				) block_res";
				$mty_lying2_Res="";
				$mty_lying2_Res = mysqli_query($con_sparcsn4,$mty_lying2_qu);
				$row_mty_lying2 = mysqli_fetch_object($mty_lying2_Res);	 
					
					$block_res="";
					$block_res=$row_mty_lying2->block_res;
					$status=0;
					foreach($blockListArray as  $val){
					   if($val== $block_res){
						   $status=1;
					   }
					}
					if($status=1){
						$mty_lying_20=$mty_lying_20+$row_mty_lying->MTY_LYING_20;
						$mty_lying_40=$mty_lying_40+$row_mty_lying->MTY_LYING_40;
						
					}
			}
			
			?>
		  
			<tr>
				<td>EMPTY CONT LYING</td>
				<td></td>
				<td><?php echo $mty_lying_20; ?></td>
				<td><?php echo $mty_lying_40; ?></td>
			</tr>

			

		</table>
		</td>
	</tr>
</table>
		<?php } ?>

	  <div  style="margin-left: 30px;">
	 <?php 
	  
	  	


		$assignment_qu="
		SELECT a.id,b.flex_date01,last_pos_slot,
		(CASE
		WHEN UPPER(mfdch_value) IN ('DLV2H','DLVGRD','DLVGRT2H','DLVGRTGRD','DLVHYS','DLVOTH','DLVREF2H','DLVREFGRD') 
		AND (SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) ='20' 
		THEN 1 ELSE 0 END ) AS tot_ass_del_20,
		(CASE
		WHEN UPPER(mfdch_value) IN ('DLV2H','DLVGRD','DLVGRT2H','DLVGRTGRD','DLVHYS','DLVOTH','DLVREF2H','DLVREFGRD') 
		AND (SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit	
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) >='40' 
		THEN 1 ELSE 0 END ) AS tot_ass_del_40,
		(CASE
		WHEN UPPER(mfdch_value) IN ('APPCUS','APPOTH','APPREF')  
		AND (SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit		
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) ='20' 
		THEN 1 ELSE 0 END ) AS tot_ass_app_20,
		(CASE
		WHEN UPPER(mfdch_value) IN ('APPCUS','APPOTH','APPREF')
		AND (SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit		
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) >='40' 
		THEN 1 ELSE 0 END ) AS tot_ass_app_40,
		(CASE
		WHEN UPPER(mfdch_value) IN ('APPDLV2H','APPDLVGRD') AND 
		(SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit	
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) ='20' 
		THEN 1 ELSE 0 END ) AS tot_ass_appcum_20,
		(CASE
		WHEN UPPER(mfdch_value) IN ('APPDLV2H','APPDLVGRD') AND 
		(SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit	
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) >='40' 
		THEN 1 ELSE 0 END ) AS tot_ass_appcum_40,
		(CASE
		WHEN UPPER(mfdch_value) ='OCD' AND 
		(SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit	
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) ='20' 
		THEN 1 ELSE 0 END ) AS tot_ass_ocd_20,
		(CASE
		WHEN UPPER(mfdch_value) ='OCD' AND 
		(SELECT substr(ref_equip_type.nominal_length,-2) 
		FROM inv_unit
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) >='40' 
		THEN 1 ELSE 0 END ) AS tot_ass_ocd_40,
		(CASE
		WHEN UPPER(mfdch_value) ='CUSINV' AND 
		(SELECT substr(ref_equip_type.nominal_length,-2) 
		FROM inv_unit	
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) ='20' 
		THEN 1 ELSE 0 END ) AS tot_ass_cusinv_20,
		(CASE
		WHEN UPPER(mfdch_value) ='CUSINV' AND 
		(SELECT substr(ref_equip_type.nominal_length,-2)
		FROM inv_unit	
		INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) >='40' 
		THEN 1 ELSE 0 END ) AS tot_ass_cusinv_40 
		
		
		FROM inv_unit a  
		INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
		INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value
		WHERE b.flex_date01 > 
		to_date(CONCAT('$date','08:00:00'),'YYYY-MM-DD HH24-MI-SS') AND  
		b.flex_date01 < to_date(CONCAT('$date','08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
		AND a.category='IMPRT'
		AND config_metafield_lov.mfdch_value NOT IN ('CANCEL')
		";
				
			
	
	 $assignment_sql_res="";
	 
	 $assignment_sql_res = oci_parse($con_sparcsn4_oracle, $assignment_qu);
	 oci_execute($assignment_sql_res);

	
	
	 $tot_ass_del_20=0;
	 $tot_ass_del_40=0;
	 $tot_ass_app_20=0;
	 $tot_ass_app_40=0;
	 $tot_ass_appcum_20=0;
	 $tot_ass_appcum_40=0;
	 $tot_ass_ocd_20=0;
	 $tot_ass_ocd_40=0;
	 $tot_ass_cusinv_20=0;
	 $tot_ass_cusinv_40=0;
	 $tot_cont_assignment=0;

	 while(($assignment_row=oci_fetch_object($assignment_sql_res))!=false){
	

		$last_pos_slot="";
	    $last_pos_slot=$assignment_row->LAST_POS_SLOT;

		$assignment_qu_2="SELECT IF(
		(SELECT block FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block WHERE block=
		'$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot'  )),
		(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  ))) )='$last_pos_slot',
		(SELECT block_cpa FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block 
		WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
		(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )) )  ),
		(SELECT block_cpa  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') AND terminal=(SELECT IF((SELECT block 
		FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
		(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )))  ORDER BY 1  LIMIT 1  )
		) block_res";
		$assignment2_Res = mysqli_query($con_sparcsn4,$assignment_qu_2);
		$row_assignment2 = mysqli_fetch_object($assignment2_Res);	 
		  
		  $block_res="";
		  $block_res=$row_assignment2->block_res;
		  $status=0;
		  foreach($blockListArray as  $val){
			 if($val== $block_res){
				 $status=1;
			 }
		  }

		
		  if($status=1){
			$tot_ass_del_20=$tot_ass_del_20+$assignment_row->TOT_ASS_DEL_20;
			$tot_ass_del_40=$tot_ass_del_40+$assignment_row->TOT_ASS_DEL_40;
			$tot_ass_app_20=$tot_ass_app_20+$assignment_row->TOT_ASS_APP_20;
			$tot_ass_app_40=$tot_ass_app_40+$assignment_row->TOT_ASS_APP_40;
			$tot_ass_appcum_20=$tot_ass_appcum_20+$assignment_row->TOT_ASS_APPCUM_20;
			$tot_ass_appcum_40=$tot_ass_appcum_40+$assignment_row->TOT_ASS_APPCUM_40;
			$tot_ass_ocd_20=$tot_ass_ocd_20+$assignment_row->TOT_ASS_OCD_20;
			$tot_ass_ocd_40=$tot_ass_ocd_40+$assignment_row->TOT_ASS_OCD_40;
			$tot_ass_cusinv_20=$tot_ass_cusinv_20+$assignment_row->TOT_ASS_CUSINV_20;
			$tot_ass_cusinv_40=$tot_ass_cusinv_40+$assignment_row->TOT_ASS_CUSINV_40;
			$tot_cont_assignment=$tot_cont_assignment+1;
		  }
  }

	 



	$keepdown_qu="
	SELECT a.id,last_pos_slot,
	(CASE
	WHEN UPPER(mfdch_value) IN ('DLV2H','DLVGRD','DLVGRT2H','DLVGRTGRD','DLVHYS','DLVOTH','DLVREF2H','DLVREFGRD')
	AND (SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit		
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) ='20' 
	THEN 1 ELSE 0 END ) AS tot_ass_del_20,
	(CASE
	WHEN UPPER(mfdch_value) IN ('DLV2H','DLVGRD','DLVGRT2H','DLVGRTGRD','DLVHYS','DLVOTH','DLVREF2H','DLVREFGRD')
	AND (SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit		
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) >='40' 
	THEN 1 ELSE 0 END ) AS tot_ass_del_40 ,
	(CASE
	WHEN UPPER(mfdch_value) IN ('APPCUS','APPOTH','APPREF')
	AND (SELECT substr(ref_equip_type.nominal_length,-2)
	FROM inv_unit	
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) ='20' 
	THEN 1 ELSE 0 END ) AS tot_ass_app_20,
	(CASE
	WHEN UPPER(mfdch_value) IN ('APPCUS','APPOTH','APPREF')
	AND (SELECT substr(ref_equip_type.nominal_length,-2)
	FROM inv_unit		
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) >='40' 
	THEN 1 ELSE 0 END ) AS tot_ass_app_40, 
	(CASE
	WHEN UPPER(mfdch_value) IN ('APPDLV2H','APPDLVGRD') AND 
	(SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit	
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) ='20' 
	THEN 1 ELSE 0 END ) AS tot_ass_appcum_20,
	(CASE
	WHEN UPPER(mfdch_value) IN ('APPDLV2H','APPDLVGRD') AND 
	(SELECT substr(ref_equip_type.nominal_length,-2) 
	FROM inv_unit
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) >='40' 
	THEN 1 ELSE 0 END ) AS tot_ass_appcum_40,
	(CASE
	WHEN UPPER(mfdch_value) ='OCD' AND 
	(SELECT substr(ref_equip_type.nominal_length,-2)
	FROM inv_unit	
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) ='20' 
	THEN 1 ELSE 0 END ) AS tot_ass_ocd_20,
	(CASE
	WHEN UPPER(mfdch_value) ='OCD' AND
	(SELECT substr(ref_equip_type.nominal_length,-2) 
	FROM inv_unit	
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) >='40' 
	THEN 1 ELSE 0 END ) AS tot_ass_ocd_40,
	(CASE
	WHEN UPPER(mfdch_value) ='CUSINV' AND 
	(SELECT substr(ref_equip_type.nominal_length,-2)
	FROM inv_unit
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey	 	
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) ='20' 
	THEN 1 ELSE 0 END ) AS tot_ass_cusinv_20,
	(CASE
	WHEN UPPER(mfdch_value) ='CUSINV' AND 
	(SELECT substr(ref_equip_type.nominal_length,-2)
	FROM inv_unit	
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=a.gkey) >='40' 
	THEN 1 ELSE 0 END ) AS tot_ass_cusinv_40       
	FROM inv_unit a  
	INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
	INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value
	WHERE b.flex_date01 >to_date(CONCAT('$date','08:00:00'),'YYYY-MM-DD HH24-MI-SS') AND  
	b.flex_date01 < to_date(CONCAT('$date','08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
	AND config_metafield_lov.mfdch_value NOT IN ('CANCEL') AND 
	b.time_in IS NOT NULL AND a.category='IMPRT' ";
	
	 $keepdown_sql_res = oci_parse($con_sparcsn4_oracle, $keepdown_qu);
	 oci_execute($keepdown_sql_res);


	        $keepdown_tot_ass_del_20=0;
			$keepdown_tot_ass_del_40=0;
			$keepdown_tot_ass_app_20=0;
			$keepdown_tot_ass_app_40=0;
			$keepdown_tot_ass_appcum_20=0;
			$keepdown_tot_ass_appcum_40=0;
			$keepdown_tot_ass_ocd_20=0;
			$keepdown_tot_ass_ocd_40=0;
			$keepdown_tot_ass_cusinv_20=0;
			$keepdown_tot_ass_cusinv_40=0;
			
			
			while(($keepdown_row=oci_fetch_object($keepdown_sql_res))!=false){
				
				$last_pos_slot="";
				$last_pos_slot=$keepdown_row->LAST_POS_SLOT;
				$keepdown_qu2="SELECT IF(
				(SELECT block FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block WHERE block=
				'$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot'  )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  ))) )='$last_pos_slot',
				(SELECT block_cpa FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block 
				WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )) )  ),
				(SELECT block_cpa  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') AND terminal=(SELECT IF((SELECT block 
				FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )))  ORDER BY 1  LIMIT 1  )
				) block_res";
				$keepdown2_Res = mysqli_query($con_sparcsn4,$keepdown_qu2);
				$row_keepdown2 = mysqli_fetch_object($keepdown2_Res);	 
				
				$block_res="";
				$block_res=$row_keepdown2->block_res;
				$status=0;
				foreach($blockListArray as  $val){
					if($val== $block_res){
						$status=1;
					}
				}

				if($status=1){
					
					$keepdown_tot_ass_del_20=$keepdown_tot_ass_del_20+$keepdown_row->TOT_ASS_DEL_20;
					$keepdown_tot_ass_del_40=$keepdown_tot_ass_del_40+$keepdown_row->TOT_ASS_DEL_40;
					$keepdown_tot_ass_app_20=$keepdown_tot_ass_app_20+$keepdown_row->TOT_ASS_APP_20;
					$keepdown_tot_ass_app_40=$keepdown_tot_ass_app_40+$keepdown_row->TOT_ASS_APP_40;
					$keepdown_tot_ass_appcum_20=$keepdown_tot_ass_appcum_20+$keepdown_row->TOT_ASS_APPCUM_20;
					$keepdown_tot_ass_appcum_40=$keepdown_tot_ass_appcum_40+$keepdown_row->TOT_ASS_APPCUM_40;
					$keepdown_tot_ass_ocd_20=$keepdown_tot_ass_ocd_20+$keepdown_row->TOT_ASS_OCD_20;
					$keepdown_tot_ass_ocd_40=$keepdown_tot_ass_ocd_40+$keepdown_row->TOT_ASS_OCD_40;
					$keepdown_tot_ass_cusinv_20=$keepdown_tot_ass_cusinv_20+$keepdown_row->TOT_ASS_CUSINV_20;
					$keepdown_tot_ass_cusinv_40=$keepdown_tot_ass_cusinv_40+$keepdown_row->TOT_ASS_CUSINV_40;
				}
		}
	  ?>
	  
	  		<table width="55%" border =0 cellpadding='0' cellspacing='0' >
			<tr><td>KEEP DOWN POSITION FOR NEXT DAY</td></tr>
		</table>
		<table width="55%" border ='1' cellpadding='0' cellspacing='0'>
			<tr>
				<td rowspan="2"></td>
				<td colspan="2">ASSIGNMENT</td>
				<td colspan="2">KEEP DOWN</td>
				<td colspan="2">BALANCE</td>
			</tr>
			<tr>
				<td>20'</td>
				<td>40'</td>
				<td>20'</td>
				<td>40'</td>
				<td>20'</td>
				<td>40'</td>
			</tr>
			<tr>
				<td>DELIVERY</td>
				<td><?php if($tot_ass_del_20!=("" || 0)){ echo $tot_ass_del_20; } else echo "-"; ?></td>
				<td><?php if($tot_ass_del_40!=("" || 0)){ echo $tot_ass_del_40; } else echo "-";  ; ?></td>
				<td><?php if($keepdown_tot_ass_del_20!=("" || 0)){ echo $keepdown_tot_ass_del_20; } else echo "-"; ?></td>
				<td><?php if($keepdown_tot_ass_del_40!=("" || 0)){ echo $keepdown_tot_ass_del_40; } else echo "-";  ; ?></td>
				<td><?php $balance20=($tot_ass_del_20-$keepdown_tot_ass_del_20); if($balance20!=("" || 0)){ echo $balance20; } else echo "-"; ?></td>
				<td><?php $balance40=($tot_ass_del_40-$keepdown_tot_ass_del_40); if($balance40!=("" || 0)){ echo $balance40; } else echo "-"; ?></td>
			</tr>
			<tr>
				<td>APPRAISEMENT</td>
				<td><?php if($tot_ass_app_20!=("" || 0)){ echo $tot_ass_app_20; } else echo "-";  ?></td>
				<td><?php if($tot_ass_app_40!=("" || 0)){ echo $tot_ass_app_40; } else echo "-"; ?></td>
				<td><?php if($keepdown_tot_ass_app_20!=("" || 0)){ echo $keepdown_tot_ass_app_20; } else echo "-";  ?></td>
				<td><?php if($keepdown_tot_ass_app_40!=("" || 0)){ echo $keepdown_tot_ass_app_40; } else echo "-"; ?></td>
				<td><?php $appraise20=($tot_ass_app_20-$keepdown_tot_ass_app_20); if($appraise20!=("" || 0)){ echo $appraise20; } else echo "-"; ?></td>
				<td><?php $appraise40=($tot_ass_app_40-$keepdown_tot_ass_app_40); if($appraise20!=("" || 0)){ echo $appraise40; } else echo "-"; ?></td>
			</tr>	
			<tr>
				<td>APP CUM DELIVERY</td>
				<td><?php if($tot_ass_appcum_20!=("" || 0)){ echo $tot_ass_appcum_20; } else echo "-"; ?></td>
				<td><?php if($tot_ass_appcum_40!=("" || 0)){ echo $tot_ass_appcum_40; } else echo "-"; ?></td>
				<td><?php if($keepdown_tot_ass_appcum_20!=("" || 0)){ echo $keepdown_tot_ass_appcum_20; } else echo "-"; ?></td>
				<td><?php if($keepdown_tot_ass_appcum_40!=("" || 0)){ echo $keepdown_tot_ass_appcum_40; } else echo "-"; ?></td>
				<td><?php $appCumDel20=($tot_ass_appcum_20-$keepdown_tot_ass_appcum_20); if($appCumDel20!=("" || 0)){ echo $appCumDel20; } else echo "-"; ?></td>
				<td><?php $appCumDel40=($tot_ass_appcum_40-$keepdown_tot_ass_appcum_40); if($appCumDel40!=("" || 0)){ echo $appCumDel40; } else echo "-"; ?></td>
			</tr>		
			<tr>
				<td>ON CHASIS</td>
				<td><?php if($tot_ass_ocd_20!=("" || 0)){ echo $tot_ass_ocd_20; } else echo "-"; ?></td>
				<td><?php if($tot_ass_ocd_40!=("" || 0)){ echo $tot_ass_ocd_40; } else echo "-"; ?></td>
				<td><?php if($keepdown_tot_ass_ocd_20!=("" || 0)){ echo $keepdown_tot_ass_ocd_20; } else echo "-"; ?></td>
				<td><?php if($keepdown_tot_ass_ocd_40!=("" || 0)){ echo $keepdown_tot_ass_ocd_40; } else echo "-"; ?></td>
				<td><?php $chasis20=($tot_ass_ocd_20-$keepdown_tot_ass_ocd_20); if($chasis20!=("" || 0)){ echo $chasis20; } else echo "-"; ?></td>
				<td><?php $chasis40=($tot_ass_ocd_40-$keepdown_tot_ass_ocd_40); if($chasis40!=("" || 0)){ echo $chasis40; } else echo "-"; ?></td>
			</tr>	
			<tr>
				<td>CUSTOM INVENTORY</td>
				<td><?php if($tot_ass_cusinv_20!=("" || 0)){ echo $tot_ass_cusinv_20; } else echo "-"; ?></td>
				<td><?php if($tot_ass_cusinv_40!=("" || 0)){ echo $tot_ass_cusinv_40; } else echo "-"; ?></td>
				<td><?php if($keepdown_tot_ass_cusinv_20!=("" || 0)){ echo $keepdown_tot_ass_cusinv_20; } else echo "-"; ?></td>
				<td><?php if($keepdown_tot_ass_cusinv_40!=("" || 0)){ echo $keepdown_tot_ass_cusinv_40; } else echo "-"; ?></td>
				<td><?php $custInv20=($tot_ass_cusinv_20-$keepdown_tot_ass_cusinv_20); if($custInv20!=("" || 0)){ echo $custInv20; } else echo "-"; ?></td>
				<td><?php $custInv40=($tot_ass_cusinv_40-$keepdown_tot_ass_cusinv_40); if($custInv40!=("" || 0)){ echo $custInv40; } else echo "-"; ?></td>
			</tr>
		</table>
	  
	  <table><tr><td>&nbsp;</td></tr></table>
	  
		<table width="55%" border =0 cellpadding='0' cellspacing='0'>
			<tr><td>EMPTY CONTAINER 24 hrs POSITION</td></tr>
		</table>
		<table width="55%" border ='1' cellpadding='0' cellspacing='0' >
			<tr>
				<td>OPENING BALANCE</td>
				<td>RECEIVING</td>
				<td colspan="4">GATE PASS</td>
				<td colspan="4">SHIPMENT</td>
				<td>CLOSING BALANCE</td>
			</tr>
			
			<?php 
			$mty_row="";

		
			$mty_str="
			SELECT a.id,last_pos_slot,
			(CASE
			WHEN (SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=a.gkey fetch first 1 rows only)=20 THEN 1
			ELSE 0 END 
			) AS size_20,
			(CASE
			WHEN (SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit		
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey		
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey	
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE inv_unit_fcy_visit.unit_gkey=a.gkey fetch first 1 rows only)>=40 THEN 1
			ELSE 0 END 
			) AS size_40
			
			FROM inv_unit a  
			INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
			WHERE a.category='IMPRT' AND b.transit_state='S40_YARD' AND a.freight_kind='MTY'
			";

			
			$mty_str_sql_res = oci_parse($con_sparcsn4_oracle, $mty_str);
	        oci_execute($mty_str_sql_res);
			
			
			
			$size_20=0;
			$size_40=0;
			
			while(($row_str=oci_fetch_object($mty_str_sql_res))!=false){
				$last_pos_slot="";
			    $last_pos_slot=$row_str->LAST_POS_SLOT;
				$mty_str_qu2="SELECT IF(
				(SELECT block FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block WHERE block=
				'$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot'  )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  ))) )='$last_pos_slot',
				(SELECT block_cpa FROM ctmsmis.yard_block WHERE block='$last_pos_slot' AND terminal=(SELECT IF((SELECT block FROM ctmsmis.yard_block 
				WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )) )  ),
				(SELECT block_cpa  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') AND terminal=(SELECT IF((SELECT block 
				FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )='$last_pos_slot',((SELECT terminal FROM ctmsmis.yard_block WHERE block='$last_pos_slot' )),
				(SELECT terminal  FROM ctmsmis.yard_block WHERE '$last_pos_slot' LIKE CONCAT(block,'%') ORDER BY 1  LIMIT 1  )))  ORDER BY 1  LIMIT 1  )
				) block_res";

				$mty_str_qu2_Res="";
				$mty_str_qu2_Res = mysqli_query($con_sparcsn4,$mty_str_qu2);
				$row_str2 = mysqli_fetch_object($mty_str_qu2_Res);	 
				
				$block_res="";
				$block_res=$row_str2->block_res;
				$status=0;
				foreach($blockListArray as  $val){
					if($val== $block_res){
						$status=1;
					}
				}

				if($status=1){
					$size_20=$size_20+$row_str->SIZE_20;
					$size_40=$size_40+$row_str->SIZE_40;
				}

				
		    }
			
			

			?>
			<tr>
				<td rowspan="2"> <?php echo $size_20." X 20'";  ?></td>
				<td rowspan="2"><?php echo $rcv_20_mty; ?></td>
				<td>D/T</td>
				<td>E/T</td>
				<td>N/T</td>
				<td>TOTAL</td>
				<td>D/T</td>
				<td>E/T</td>
				<td>N/T</td>
				<td>TOTAL</td>
				<td rowspan="2"> <?php echo $size_20-$rcv_20_mty;  ?>  X 20'</td>
			</tr>
			<tr>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
			</tr>
			<tr>
				<td rowspan="2"> <?php echo $size_40." X 40'";  ?></td>
				<td rowspan="2"><?php echo $rcv_40_mty; ?></td>
				<td>D/T</td>
				<td>E/T</td>
				<td>N/T</td>
				<td>TOTAL</td>
				<td>D/T</td>
				<td>E/T</td>
				<td>N/T</td>
				<td>TOTAL</td>
				<td rowspan="2"><?php echo $size_40-$rcv_40_mty;  ?> X 40'</td>
			</tr>
			<tr>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
			</tr>
			<!--tr>
				<td rowspan="2"> X 40"</td>
				<td rowspan="2"></td>
				<td>D/T</td>
				<td>E/T</td>
				<td>N/T</td>
				<td>TOTAL</td>
				<td>D/T</td>
				<td>E/T</td>
				<td>N/T</td>
				<td>TOTAL</td>
				<td rowspan="2"> X 40"</td>
			</tr>
			
			<tr>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
			</tr-->	
		
		</table>
		

		
</div>

	</div>
		</div>
			<?php
			 mysqli_close($con_sparcsn4); 
			 oci_close($con_sparcsn4_oracle);
			?>
	</body>
</html>

