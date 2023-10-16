<html>
	<!--head>
		 <meta http-equiv="refresh" content="20">
		 <style>
			body{font-family: "Calibri";}
		 </style>
	</head-->
	<body>
		<div>
			<div align="center">
				<table>
					<tr>
						<td  align="center"><img align="middle"  width="240px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
					</tr>
				</table>
			</div>			
			<div align="center">
				<?php 
					include("dbConection.php");
					include("dbOracleConnection.php");
				?>
				<table>
					<tr style="margin:5px;">
						<td colspan="12"><font size="4"><b>YARD WISE ASSIGNMENT TOTAL REPORT SUMMARY : </font><font size="4"><?php echo  date("d-m-Y", strtotime($date)); ?></font></b></td>
					</tr>
					<tr><td>&nbsp;</td><tr>
				</table>
				<table class="table table-bordered table-responsive table-hover table-striped mb-none">
					<tr align="center" bgcolor="#D8D0CE">
						<td rowspan="2"><b>Sl No.</b></td>
						<td rowspan="2"><b>TERMINAL/YARD</b></td>
						<td colspan="3"><b>SPECIAL</b></td>
						<td colspan="3"><b>ASSIGNMENT</b></td>
						<td rowspan="2"><b>CANCEL</b></td>
						<td rowspan="2"><b>DELIVERED</b></td>
						<td rowspan="2"><b>BALANCE</b></td>
						<td rowspan="2"><b>STAYED</b></td>
						<td colspan="3"><b>APPRAISE</b></td>
					</tr>
					<tr align="center" bgcolor="#D8D0CE">
						<td><b>BIDDER</b></td>
						<td><b>ON CHASIS</b></td>
						<td><b>OTHERS</b></td>
						<td><b>BIDDER</b></td>
						<td><b>ON CHASIS</b></td>
						<td><b>OTHERS</b></td>
						<td><b>YES</b></td>
						<td><b>NO</b></td>
						<td><b>TOTAL</b></td>
					</tr>
				<?php

				
					$totalRow=0;
					/*$blockListStr="SELECT DISTINCT block_cpa as block  FROM ctmsmis.yard_block
						WHERE terminal='$yard_no' AND  block_cpa!='NULL' ORDER BY block ASC";*/
						$blockListStr="SELECT DISTINCT block AS block  FROM ctmsmis.yard_block
						WHERE terminal='$yard_no' AND  block!='NULL' ORDER BY block ASC";
					    $blockListQuery=mysqli_query($con_sparcsn4,$blockListStr);
						$totalRow=mysqli_num_rows($blockListQuery);
						
						$blockList="";
						$i=0;
						while($blockRow=mysqli_fetch_object($blockListQuery)){
							$blockString="";
							$blockString=$blockRow->block;
							
							if($i==($totalRow-1)){
								$blockList=$blockList."'".$blockString."'";
							}
							else{
								$blockList=$blockList."'".$blockString."',";

							}
							$i++;

						}
						  $strQuery="
						SELECT sel_block, 
						SUM((CASE WHEN t!='CANCEL' AND ass='0' THEN '1' ELSE '0' End) ) AS assignment,
						SUM((CASE WHEN t!='CANCEL' AND ass=0 AND t='BIDDLV' THEN '1' ELSE '0' END) ) AS assignment_bidder,
						SUM((CASE WHEN t!='CANCEL' AND ass=0 AND t='OCD' THEN '1' ELSE '0' END) )AS assignment_ocd,
						SUM((CASE WHEN t!='CANCEL' AND ass=0 AND t!='OCD' AND t!='BIDDLV' THEN '1' ELSE '0' END) ) AS assignment_rest,
						
						SUM((CASE WHEN t='CANCEL' THEN '1' ELSE '0' END) )AS cancel,
						SUM((CASE WHEN delv = '1' AND t!='CANCEL' THEN '1' ELSE '0' END) ) AS delivered,
						SUM((CASE WHEN delv = '0' AND t!='CANCEL' AND ass=0 THEN '1' ELSE '0' END) ) AS balance,
						SUM((CASE WHEN stay = '0' THEN '1' ELSE '0' END) ) AS stayed,
						SUM((CASE WHEN ass=1 THEN '1' ELSE '0' END) ) AS appraise,
						SUM((CASE WHEN appraise_yes='1' THEN '1' ELSE '0' END) ) AS appraise_yes,
						SUM((CASE WHEN appraise_no='1' THEN '1' ELSE '0' END) ) AS appraise_no,
						
						SUM((CASE WHEN special=1 THEN '1' ELSE '0' END) ) AS special,
						SUM((CASE WHEN special=1 AND t='BIDDLV' THEN '1' ELSE '0' END) ) AS special_bidder,
						SUM((CASE WHEN special=1 AND t='OCD' THEN '1' ELSE '0' END) ) AS special_ocd,
						SUM((CASE WHEN special=1 AND t!='OCD' AND t!='BIDDLV' THEN '1' ELSE '0' END) ) AS special_rest
						FROM (
						
						SELECT sel_block, a.id AS cont,
						(CASE WHEN a.flex_string14='YES' AND mfdch_desc IN('Customs Appraise','Appraise Others','Appraise Reefer') THEN '1' ELSE '0' END) AS appraise_yes,
						(CASE WHEN a.flex_string14='NO' THEN '1'  ELSE '0' END) AS appraise_no,
						(CASE WHEN (SELECT to_char(srv_event.created,'yyyy-mm-dd') FROM  srv_event 
						INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
						WHERE applied_to_gkey=a.gkey AND event_type_gkey=31426 ORDER BY srv_event.gkey DESC FETCH FIRST 1 ROWS ONLY)='$date'
						THEN '1' ELSE '0' END) AS special,
						(CASE 
						WHEN b.time_out!='' THEN '1'
						WHEN b.time_out IS NOT NULL THEN '1'
						ELSE '0'
						END ) AS delv,
						(CASE 
						WHEN config_metafield_lov.mfdch_value='APPCUS' THEN '1' 
						WHEN config_metafield_lov.mfdch_value='APPOTH' THEN '1'
						WHEN config_metafield_lov.mfdch_value='APPREF' THEN '1'
						ELSE '0'
						END ) AS ass,
						b.time_in AS dischargetime,
						b.time_out AS delivery,
						config_metafield_lov.mfdch_desc,
						config_metafield_lov.mfdch_value AS t,
						
						a.freight_kind AS statu,
						
						
						
						(SELECT srv_event.created FROM  srv_event 
						INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
						WHERE applied_to_gkey=a.gkey AND event_type_gkey=31426 AND srv_event_field_changes.new_value='E'  FETCH FIRST 1 ROWS ONLY) AS proEmtyDate,
						b.flex_date01 AS assignmentdate, 
						(CASE WHEN UPPER(a.flex_string15) LIKE '%STAY%' THEN '1' ELSE '0' END) AS stay
						
						FROM inv_unit a
						INNER JOIN srv_event ON  srv_event.applied_to_gkey=a.gkey
						INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
						INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
						INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id
						INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
						INNER JOIN ref_bizunit_scoped g ON a.line_op = g.gkey
						INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value
						INNER JOIN
						inv_goods j ON j.gkey = a.goods
						LEFT JOIN
						ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
						WHERE to_char(b.flex_date01,'yyyy-mm-dd')= '$date' AND sel_block IN ($blockList)
						)  tmp GROUP BY sel_block";
				
					
				
					
					
					// echo $strQuery;
					$query=oci_parse($con_sparcsn4_oracle,$strQuery);
					oci_execute($query);
					
					$sp_sum=0;
					$sp_bidder_sum=0;
					$sp_ocd_sum=0;
					$sp_rest_sum=0;
					
					$ass_sum=0;
					$ass_bidder_sum=0;
					$ass_ocd_sum=0;
					$ass_rest_sum=0;
					
					$cancel_sum=0;
					$delivered_sum=0;
					$balance_sum=0;
					$stayed_sum=0;
					$appraise_sum=0;
					$appraise_yes=0;
					$appraise_no=0;
					$i=1;
					while(($row=oci_fetch_object($query))!=false){


				?>
				<tr align="center">
						<td><?php  echo $i;?></td>
						<td><?php if($row->SEL_BLOCK) echo $row->SEL_BLOCK; else echo "&nbsp;";?></td>
						
						<!-- special -->
						<td><?php if($row->SPECIAL_BIDDER){ ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/SP/'.$row->SEL_BLOCK.'/'.$date.'/BIDDLV');?>" target="_blank"> <?php $sp_bidder_sum+=$row->SPECIAL_BIDDER;  echo $row->SPECIAL_BIDDER; } else echo "&nbsp;";?></td>		
						<td><?php if($row->SPECIAL_OCD ){ ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/SP/'.$row->SEL_BLOCK.'/'.$date.'/OCD');?>" target="_blank"> <?php $sp_ocd_sum+=$row->SPECIAL_OCD;  echo $row->SPECIAL_OCD; } else echo "&nbsp;";?></td>		
						<td><?php if($row->SPECIAL_REST){ ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/SP/'.$row->SEL_BLOCK.'/'.$date.'/REST');?>" target="_blank"> <?php $sp_rest_sum+=$row->SPECIAL_REST;  echo $row->SPECIAL_REST; } else echo "&nbsp;";?></td>	
						
						<!-- assignment -->
						<td><?php if($row->ASSIGNMENT_BIDDER){ ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/assignment/'.$row->SEL_BLOCK.'/'.$date.'/BIDDLV');?>" target="_blank"> <?php $ass_bidder_sum+=$row->ASSIGNMENT_BIDDER;  echo $row->ASSIGNMENT_BIDDER; } else echo "&nbsp;";?></td>		
						<td><?php if($row->ASSIGNMENT_OCD ){ ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/assignment/'.$row->SEL_BLOCK.'/'.$date.'/OCD');?>" target="_blank"> <?php $ass_ocd_sum+=$row->ASSIGNMENT_OCD;  echo $row->ASSIGNMENT_OCD; } else echo "&nbsp;";?></td>
						<td><?php if($row->ASSIGNMENT_REST){ ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/assignment/'.$row->SEL_BLOCK.'/'.$date.'/REST');?>" target="_blank"> <?php $ass_rest_sum+=$row->ASSIGNMENT_REST;  echo $row->ASSIGNMENT_REST; } else echo "&nbsp;";?></td>			
						
						<td><?php if($row->CANCEL) { ?> <a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/C/'.$row->SEL_BLOCK.'/'.$date);?>" target="_blank"> <?php $cancel_sum+=$row->CANCEL; echo $row->CANCEL; } else echo "&nbsp;";?></a></td>						
						<td><?php if($row->DELIVERED) { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/delivered/'.$row->SEL_BLOCK.'/'.$date);?>" target="_blank"> <?php $delivered_sum+=$row->DELIVERED; echo $row->DELIVERED; } else echo "&nbsp;";?></td>
						<td><?php if($row->BALANCE) { ?> <a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/B/'.$row->SEL_BLOCK.'/'.$date);?>" target="_blank"> <?php $balance_sum+=$row->BALANCE; echo $row->BALANCE; } else echo "&nbsp;";?></a></td>						
						<td><?php if($row->STAYED) { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/S/'.$row->SEL_BLOCK.'/'.$date);?>" target="_blank"> <?php $stayed_sum+=$row->STAYED; echo $row->STAYED; } else echo "&nbsp;";?></a></td>
						<td><?php if($row->APPRAISE_YES) { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/AY_Single/'.$row->SEL_BLOCK.'/'.$date);?>" target="_blank"> <?php $APPRAISE_YES+=$row->APPRAISE_YES; echo $row->APPRAISE_YES; } else echo "&nbsp;";?></a></td>	
						<td><?php if($row->APPRAISE_NO) { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/AN_Single/'.$row->SEL_BLOCK.'/'.$date);?>" target="_blank"> <?php $appraise_no+=$row->APPRAISE_NO; echo $row->APPRAISE_NO; } else echo "&nbsp;";?></a></td>						
						<td><?php if($row->APPRAISE) { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/A/'.$row->SEL_BLOCK.'/'.$date);?>" target="_blank"> <?php $appraise_sum+=$row->APPRAISE; echo $row->APPRAISE; } else echo "&nbsp;";?></a></td>						
				</tr>

				<?php $i++;} ?>
				<tr  align="center">
					<td><b>TOTAL</b></td>
					<td></td>
					
					<td><b><?php if($sp_bidder_sum==0) echo $sp_bidder_sum; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/SPT/'.$yard_no.'/'.$date.'/BIDDLV');?>" target="_blank"><?php echo $sp_bidder_sum; } ?></b></td>
					<td><b><?php if($sp_ocd_sum==0) echo $sp_ocd_sum; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/SPT/'.$yard_no.'/'.$date.'/OCD');?>" target="_blank"><?php echo $sp_ocd_sum; } ?></b></td>
					<td><b><?php if($sp_rest_sum==0) echo $sp_rest_sum; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/SPT/'.$yard_no.'/'.$date.'/REST');?>" target="_blank"><?php echo $sp_rest_sum; } ?></b></td>
					
					<td><b><?php if($ass_bidder_sum==0) echo $ass_bidder_sum; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/AST/'.$yard_no.'/'.$date.'/BIDDLV');?>" target="_blank"><?php echo $ass_bidder_sum; } ?></b></td>
					<td><b><?php if($ass_ocd_sum==0) echo $ass_ocd_sum; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/AST/'.$yard_no.'/'.$date.'/OCD');?>" target="_blank"><?php echo $ass_ocd_sum; } ?></b></td>
					<td><b><?php if($ass_rest_sum==0) echo $ass_rest_sum; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/AST/'.$yard_no.'/'.$date.'/REST');?>" target="_blank"><?php echo $ass_rest_sum; } ?></b></td>
					
					<td><b><?php if($cancel_sum==0) echo $cancel_sum; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/CT/'.$yard_no.'/'.$date);?>" target="_blank"><?php echo $cancel_sum; } ?></b></td>
					<td><b><?php if($delivered_sum==0) echo $delivered_sum; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/DLVT/'.$yard_no.'/'.$date);?>" target="_blank"><?php echo $delivered_sum; } ?></b></td>
					<td><b><?php if($balance_sum==0) echo $balance_sum; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/BLT/'.$yard_no.'/'.$date);?>" target="_blank"><?php echo $balance_sum; } ?></b></td>
					<td><b><?php if($stayed_sum==0) echo $stayed_sum; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/STT/'.$yard_no.'/'.$date);?>" target="_blank"><?php echo $stayed_sum; } ?></b></td>
					<td><b><?php if($appraise_yes==0) echo $appraise_yes; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/AY/'.$yard_no.'/'.$date);?>" target="_blank"><?php echo $appraise_yes; } ?></b></td>
					<td><b><?php if($appraise_no==0) echo $appraise_no; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/AN/'.$yard_no.'/'.$date);?>" target="_blank"><?php echo $appraise_no; } ?></b></td>
					
					
					<td><b><?php if($appraise_sum==0) echo $appraise_sum; else { ?><a href="<?php echo site_url('Report/yardWiseImportTotalReportPerformDetails/T/'.$yard_no.'/'.$date);?>" target="_blank"><?php echo $appraise_sum; } ?></a></b></td>

				</tr>
				
				</table>
			</div>
		</div>

		<?php mysqli_close($con_sparcsn4); ?>
	</body>
</html>