<?php if($_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>ASSIGNMENT REPORT VIEW</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php } 
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=ASSIGNMENT_REPORT_VIEW.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}


	//include("dbConection.php");
	
		$col = "b.flex_date01";
		$head = "Delivery Container List";
		//echo "Hello : ".$login_id;
	?>
<html>
<!--<title>YARD WISE PROPOSED EMPTY AND EMPTY  CONTAINER REPORT</title>-->
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr align="center" >
					<td colspan="12"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="5"><b><?php echo $head; ?></b></font></td>
				</tr>
<!--				<tr align="center">
					<td colspan="12"><font size="4"><b>DATE : <?php echo $fromdate; ?></b></font></td>
				</tr>-->
                                <tr ALIGN="center">
                                    <!--td colspan="1"></td-->
                                    <td colspan="6" align ><font size="4"><b>C & F : <?php echo $cnfName ; ?></b></font></td>
                                    <td colspan="6"><font size="4"><b>Assignment Date : <?php echo $fromdate; ?></b></font></td>
                                    <!--td colspan="3"></td-->
                                </tr>


			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr  align="center">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Cont.Size</b></td>
		<td style="border-width:3px;border-style: double;"><b>Cont.Height</b></td>
		<td style="border-width:3px;border-style: double;"><b>Rotation No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
		<td style="border-width:3px;border-style: double;"><b>Status</b></td>	
		<!--<td style="border-width:3px;border-style: double;"><b>Assignment Type</b></td>-->		
		<!--<td style="border-width:3px;border-style: double;"><b>C&F</b></td>-->		
		<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
		<td style="border-width:3px;border-style: double;"><b>Current Position</b></td>
		<!--td style="border-width:3px;border-style: double;"><b>Discharge date</b></td-->
		<!--<td style="border-width:3px;border-style: double;"><b>Assignment date</b></td>-->
		<td style="border-width:3px;border-style: double;"><b>Delivery Date</b></td>
                <td style="border-width:3px;border-style: double;"><b>Delivery Time</b></td>
                <td style="border-width:3px;border-style: double;"><b> Time</b></td>
		<td style="border-width:3px;border-style: double;"><b>Stripped By</b></td>
		<td style="border-width:3px;border-style: double;"><b>Remarks</b></td>
		
		
	</tr>

<?php
	//echo $type;
	
/* 	$str="SELECT DISTINCT *,
				(case 
				when delivery >= concat('$fromdate',' 08:00:00') AND delivery <concat('$fromdate',' 16:00:00') then 'Shift A'
				when delivery >= concat('$fromdate',' 16:00:00') AND delivery <concat(date_add('$fromdate',interval 1 day),' 00:00:00') then 'Shift B'
				when delivery >= concat(date_add('$fromdate',interval 1 day),' 00:00:00') AND delivery <concat(date_add('$fromdate',interval 1 day),' 08:00:00') then 'Shift C'
				end) as shift,
				(case when delivery is null then 2 else 1 end) as sl
				FROM (
				SELECT a.id AS cont_no,
				(SELECT sparcsn4.ref_equip_type.id FROM sparcsn4.inv_unit_equip
				INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
				INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
				WHERE sparcsn4.inv_unit_equip.unit_gkey=a.gkey) AS iso_code,
				b.flex_string10 AS rot_no,
				b.time_in AS dischargetime,
				b.time_out AS delivery,
                                DATE(b.time_out) AS delivery_dt,  TIME(b.time_out) AS delivery_time,
				g.id AS mlo,
				k.name as cf,
				sparcsn4.config_metafield_lov.mfdch_desc,
				a.freight_kind AS statu,
				a.goods_and_ctr_wt_kg AS weight,
				(SELECT ctmsmis.mis_exp_unit_load_failed.last_update
				FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey=a.gkey) AS last_update,
				(SELECT ctmsmis.mis_exp_unit_load_failed.user_id
				FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey=a.gkey) AS user_id,

				IFNULL((SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
						FROM sparcsn4.srv_event
						INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
						WHERE sparcsn4.srv_event.applied_to_gkey=a.gkey  AND sparcsn4.srv_event.event_type_gkey IN(18,13,16) AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND sparcsn4.srv_event_field_changes.new_value !='' AND sparcsn4.srv_event_field_changes.new_value !='Y-CGP-.' AND sparcsn4.srv_event.gkey<(SELECT sparcsn4.srv_event.gkey FROM sparcsn4.srv_event
						INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
						WHERE sparcsn4.srv_event.event_type_gkey=4 AND sparcsn4.srv_event.applied_to_gkey=a.gkey AND metafield_id='unitFlexString01' AND new_value IS NOT NULL ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1) ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1),(SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
						FROM sparcsn4.srv_event
						INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
						WHERE sparcsn4.srv_event.applied_to_gkey=a.gkey  AND sparcsn4.srv_event.event_type_gkey IN(18,13,16) ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1)) AS carrentPosition,

				(SELECT ctmsmis.cont_yard(carrentPosition)) AS Yard_No,

				(SELECT ctmsmis.cont_block(carrentPosition,Yard_No)) AS Block_No,
				(SELECT creator FROM sparcsn4.srv_event WHERE applied_to_gkey=a.gkey AND event_type_gkey=30 ORDER BY gkey DESC LIMIT 1) as stripped_by,

				(SELECT sparcsn4.srv_event.created FROM  sparcsn4.srv_event 
				INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
				WHERE applied_to_gkey=a.gkey AND event_type_gkey=4 AND sparcsn4.srv_event_field_changes.new_value='E' LIMIT 1) AS proEmtyDate,
				b.flex_date01 AS assignmentdate, if(ucase(a.flex_string15) like '%STAY%',1,0) as stay,
				Y.id AS shp_id,
				
								(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.inv_unit_equip 
				INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
				INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
				WHERE sparcsn4.inv_unit_equip.unit_gkey=a.gkey) AS size,

				((SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2) FROM sparcsn4.inv_unit_equip 
				INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
				INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
				WHERE sparcsn4.inv_unit_equip.unit_gkey=a.gkey)/10) AS height 


				FROM sparcsn4.inv_unit a
				INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
				INNER JOIN  ( sparcsn4.ref_bizunit_scoped g        
				LEFT JOIN ( sparcsn4.ref_agent_representation X        
				LEFT JOIN sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON g.gkey=X.bzu_gkey        
				)  ON g.gkey = a.line_op
				INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value

				INNER JOIN
						sparcsn4.inv_goods j ON j.gkey = a.goods
				LEFT JOIN
						sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
				WHERE date(b.flex_date01)='$fromdate' AND config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF') and k.gkey=$n4_bizu_gkey
				) AS tmp order by sl,Yard_No,shift,proEmtyDate"; */
	
	//return;
	//$query=mysqli_query($con_sparcsn4,$ass_query);
	//$query=mysqli_query($con_sparcsn4,$str);

	$i=0;
	$j=0;	

	
        $k=0;
	$i=0;
	$j=0;
	$j20=0;
	$j40=0;
	$a20 = 0;
	$a40 = 0;
	$b20 = 0;
	$a40 = 0;
	$c20 = 0;
	$a40 = 0;
	$allCont="";
	$yard="";
	$shift="";
	$tot20 = 0;
	$tot40 = 0;
	$stayed=0;

	
	include("mydbPConnection.php");
	$numRows=count($rowRslt);
	//print_r($rowRslt);
	for($m=0; $m<count($rowRslt); $m++)
	{
	//while($row=mysqli_fetch_object($query)){
        $k++;  
	$i++;
	$stayed=$stayed+$rowRslt[$m]['stay'];
	//if($yard_no=="GCB")
	//{
		if($i==$numRows)
			$allCont .=$rowRslt[$m]['cont_no'];
		else
			$allCont .=$rowRslt[$m]['cont_no'].", ";
	//}
		$cont_no=$rowRslt[$m]['cont_no'];
	$sqlIsoCode=mysqli_query($con_cchaportdb,"select cont_iso_type from igm_detail_container where cont_number='$cont_no'");
//	
	$rtnIsoCode=mysqli_fetch_object($sqlIsoCode);
	$iso=$rtnIsoCode->cont_iso_type;
//	if(substr($iso,0,1)==2)
//		$j20=$j20+1;
//	else
//		$j40=$j40+1;
//		
//	if(substr($iso,0,1)==2)
//	{
//		if($row->shift=="Shift A")
//			$a20 = $a20+1;
//		if($row->shift=="Shift B")
//			$b20 = $b20+1;
//		if($row->shift=="Shift C")
//			$c20 = $c20+1;
//	}
//	else
//	{
//		if($row->shift=="Shift A")
//			$a40 = $a40+1;
//		if($row->shift=="Shift B")
//			$b40 = $b40+1;
//		if($row->shift=="Shift C")
//			$c40 = $c40+1;
//	}
//		
//	if($shift==$row->shift or $i==1)
//	{
//		if(substr($iso,0,1)==2)
//			$tot20 = $tot20+1;
//		else 
//			$tot40 = $tot40+1;
//	}
	/*
	if($totalcon==$row->cont_no or $i==1)
	{
		echo "test";
		if(substr($iso,0,1)==2)
			$tot20 = $tot20+1;
		else 
			$tot40 = $tot40+1;
		
		echo $tot40;
		
	}*/
	$rsltdel=$rowRslt[$m]['mfdch_desc'];
	
		?>
		<tr>
			<td colspan="15"><b><?php  echo "Assignment Type:  ".$rowRslt[$m]['mfdch_desc'];?></b></td>
		</tr>
		<?php
		$i=1;
	//}?>


	
	<?php if (($rowRslt[$m]['last_update']!="") or  ($rowRslt[$m]['last_update']!=null))    {?>
          <tr bgcolor="#ec7063" align="center">
	<?php } else if (($rowRslt[$m]['delivery'])=="" or ($rowRslt[$m]['delivery'])==null) {?>
		<tr  bgcolor="#F2DC5D" align="center">
	<?php } else {?>
	<tr align="center">
	<?php } ?>
			<td><?php  echo $i;?></td>
			<td><?php  echo $rowRslt[$m]['cont_no'] ;?></td>
			<td><?php echo $rowRslt[$m]['size'] ;?></td>
			<td><?php  echo $rowRslt[$m]['height'] ;?></td>
			<td><?php  echo $rowRslt[$m]['rot_no'];?></td>
			<td><?php if($iso) echo $iso; else echo "&nbsp;";?></td>
			<td><?php  echo  $rowRslt[$m]['mlo'];?></td>
			<td><?php  echo $rowRslt[$m]['statu'];?></td>
			<!--<td><?php if($row->mfdch_desc) echo $row->mfdch_desc; else echo "&nbsp;";?></td>-->
			<!--<td><?php if($row->cf) echo $row->cf; else echo "&nbsp;";?></td>-->
			<td><?php  echo $rowRslt[$m]['weight'];?></td>
			<td><?php  echo $rowRslt[$m]['carrentPosition'];?></td>
			<!--td><?php if($row->dischargetime) echo $row->dischargetime; else echo "&nbsp;";?></td-->
			<!--<td><?php if($row->assignmentdate) echo $row->assignmentdate; else echo "&nbsp;";?></td>-->
			<td><?php echo $rowRslt[$m]['delivery_dt'];?></td>
			<td><?php  echo $rowRslt[$m]['delivery_time'];?></td>
			<td><?php 
                            
//                            if(strpos($row->mfdch_desc,"reefer")) {
//                                      echo "04:00PM" ;}
//                            if(strpos($row->mfdch_desc,"Appraise Cum Delivery 2 High")) {
//                                      echo "02.00PM - 05.00PM";  }          
                        $delTime=$rowRslt[$m]['delivery_time'];
                         if( $delTime="Appraise Cum Delivery 2 High") 
                                {
                             echo "02.00PM - 05.00PM";                             
                                }
						 else if( $delTime=="Appraise Cum Delivery Ground") 
                                {
                             echo "02.00PM - 05.00PM";                             
                                }		
                       else if(strpos( $delTime,"reefer"))
                               {
                                      echo "04:00PM - 07:00PM";  
                               }
                          else { echo "10.00AM - 01.00PM";}         
                        
//                        if($row->delivery_time) echo $row->delivery_time; else echo "&nbsp;";?></td>
			<td><?php  echo $rowRslt[$m]['stripped_by']; ?></td>
			<td><?php if (($rowRslt[$m]['last_update']!="") or  ($rowRslt[$m]['last_update']!=null)) echo "Tried to Exp. Load:".$rowRslt[$m]['last_update']." by ".$rowRslt[$m]['user_id']; else echo "&nbsp;";?></td>
		</tr>
	<?php

	} 
	
		?>
	</table>
    <table border='0'>
    <tr>
			<td colspan="15"><b>Grand Total : <?php echo $k;?></b></td>
		</tr>
    </table>
	<br/>
	<?php 
	//mysqli_close($con_sparcsn4);
	if($_POST['options']=='html'){?>	
		</BODY>
	</HTML>
<?php }?>
