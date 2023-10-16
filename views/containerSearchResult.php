<table style="border-collapse: collapse;"  align="center">
	<tr align="center"><td colspan="6" ><h2><span ><?php echo $title; ?></span> </h2></td></tr>
	<tr align="center"><td colspan="6" ><h2><span ><?php echo "Container No: ".$containerNo; ?></span> </h2></td></tr>
	
	<tr><td align="center">
		<table class="table table-bordered table-responsive table-hover table-striped mb-none">
		<thead>
			<tr class="gridDark">
				<th align="center">SL</th>
				<th align="center">Consignee</th>
				<th align="center">Container</th>
				<th align="center">Rotation</th>
				<th align="center">Type</th>
				<th align="center">MLO</th>
				<th align="center">Status</th>
				<th align="center">Weight</th>
				<th align="center">Description of Goods</th>
				<th align="center">Master BL No</th>
				<th align="center">Sub BL No</th>
				<th align="center">Assignment Date</th>
				<th align="center">Assignment Type</th>
				<th align="center">Current Position</th>
			</tr>
		</thead>
		<tbody id="data">
		
		<?php
			include("mydbPConnection.php");
			include("mydbPConnectionn4.php");
			include("dbOracleConnection.php");
			$totcontainerNo="";
		//	$totContQute="";
			
			if($rtnContainerList) 
			{
				$len=count($rtnContainerList);
			//	$j=0;
				for($i=0;$i<$len;$i++)
				{
					$id=$rtnContainerList[$i]['id'];
					$sql=mysqli_query($con_cchaportdb,"select  group_concat(igm_supplimentary_detail.BL_No) as sub_bl from igm_supplimentary_detail where igm_detail_id=$id");
					$rtnSubBl=mysqli_fetch_object($sql);
					$subBl=$rtnSubBl->sub_bl;
					$containerNo1=$rtnContainerList[$i]['cont_number'];
					


					$sql_n4="SELECT substr(k.name,1,20) AS cf, k.name,
					NVL((SELECT substr(srv_event_field_changes.new_value,7)
					FROM srv_event
					INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
					WHERE srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey IN(18,13) AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !='' AND srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey<(SELECT srv_event.gkey FROM srv_event
					INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
					WHERE srv_event.event_type_gkey=4 AND srv_event.applied_to_gkey=a.gkey AND metafield_id='unitFlexString01' AND new_value IS NOT NULL ORDER BY srv_event_field_changes.gkey DESC FETCH FIRST 1 ROWS ONLY) ORDER BY srv_event.gkey DESC FETCH FIRST 1 ROWS ONLY),(SELECT substr(srv_event_field_changes.new_value,7)
					FROM srv_event
					INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
					WHERE srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey=18 ORDER BY srv_event_field_changes.gkey DESC FETCH FIRST 1 ROWS ONLY)) AS slot,
					to_date(to_char(b.flex_date01,'yyyy-mm-dd'),'yyyy-mm-dd') AS assignDate,config_metafield_lov.mfdch_desc
					FROM inv_unit a
					INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
					INNER JOIN ref_bizunit_scoped g ON a.line_op = g.gkey
					INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value
					INNER JOIN inv_goods j ON j.gkey = a.goods
					LEFT JOIN ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
					WHERE a.id='$containerNo1' AND a.category='IMPRT' AND a.freight_kind='FCL' ORDER BY a.gkey DESC FETCH FIRST 1 ROWS ONLY";
					
					$rslt_n4=oci_parse($con_sparcsn4_oracle,$sql_n4);
					oci_execute($rslt_n4);
					$row=oci_fetch_object($rslt_n4);
					
					$cf = "";
					$slot = "";
					$assignDate = "";
					$mfdch_desc = "";
					if(count($row)>0)
					{
						$cf=$row->CF;
						$slot=$row->SLOT;
						$assignDate=$row->ASSIGNDATE;
						$mfdch_desc=$row->MFDCH_DESC;
					}
					
				?>
					<?php //if($containerNo==$containerNo1) {?>
					<?php if($assignDate!=$assignment) {?>
					<tr class="gradeX">
					<?php } else { ?> 
					<tr style="background-color:#E1F0FF;">
					<?php } ?> 
						<td align="center"><?php echo $i+1; ?></td>
						<td align="center"><?php echo $cf ;?></td>
						<td align="center"><?php echo $rtnContainerList[$i]['cont_number']; ?></td>
						<td align="center"><?php echo $rtnContainerList[$i]['Import_Rotation_No']; ?></td>
						<td align="center"><?php echo $rtnContainerList[$i]['cont_iso_type']; ?></td>
						<td align="center"><?php echo $rtnContainerList[$i]['mlocode']; ?></td>
						<td align="center"><?php echo($rtnContainerList[$i]['cont_status']); ?></td>
						<td align="center"><?php echo number_format($rtnContainerList[$i]['cont_gross_weight'],2); ?></td>
						<td align="center"><?php echo $rtnContainerList[$i]['Description_of_Goods']; ?></td>
						<td align="center"><?php echo $rtnContainerList[$i]['BL_No']; ?></td>
						<td align="center"><?php echo $subBl; ?></td>
						<td align="center"><?php echo $assignDate; ?></td>
						<td align="center"><?php echo $mfdch_desc; ?></td>
						<td align="center"><?php echo $slot; ?></td>
					</tr>
			<?php	
				//	if($totcontainerNo!="")
					if($assignDate==$assignment)
					{
						$totcontainerNo=$totcontainerNo.", ".$containerNo1;
					}
					
				//	$j++;
				}
				
				$totcontainerNo=substr($totcontainerNo,1);
			}
		?>
		</tbody>
		<tr><td colspan="16" align="center"><?php echo "Total Container: ". @$len;?></td></tr>
		<tr><td colspan="16" align="center"><?php if($totcontainerNo) echo  $totcontainerNo; else echo "&nbsp;"; ?></td></tr>
	</table>
	
</td></tr>
<br/>
<?php 
mysqli_close($con_sparcsn4);
mysqli_close($con_cchaportdb);
?>
</table>
