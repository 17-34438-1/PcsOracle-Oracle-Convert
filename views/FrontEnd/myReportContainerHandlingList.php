<section class="panel">
		<div class="panel-body">
			<div class="invoice">
						<header class="clearfix">
							<div class="row">
										<div class="col-sm-12 text-center mt-md mb-md">
											<div class="ib">
												<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
												<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4><br>
												<h1>Yardwise Equipment Booking Report</h1>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 mt-md">

									<!-- 		<h3>
								<?php 
									$strTitle = "";
									if($equipment=="All")
										$strTitle = "Search Date :".$sDate;
									else
										$strTitle = "Search For :".$sVal."<br>Search Date :".$sDate;
									echo $strTitle;
								?>
								</h3> -->

										 <h3 align="center" class="h4 mt-none mb-sm text-dark text-bold"><?php 
									$strTitle = "";
									if($equipment=="All")
										$strTitle = "Search Date :".$sDate;
									else
										$strTitle = "Search For :".$sVal."<br>Search Date :".$sDate;
									echo $strTitle;
								?>
											</h3> 
											
										</div>
									</div>
						</header>

	<?php
		include("dbConection.php");
		$cond = "";
		$shiftCondition=" and shift='$shift'";
		if($equipment=="All")
			if($shift=="All")
				$cond = " DATE(EAD.start_work_time)='$sDate'";
			else
				$cond = " DATE(EAD.start_work_time)='$sDate'".$shiftCondition;
		else if($equipment=="Equipment")
			if($shift=="All")
				$cond = " (ED.equipment='$sVal') AND DATE(EAD.start_work_time)='$sDate'";
			else
				$cond = " (ED.equipment='$sVal') AND DATE(EAD.start_work_time)='$sDate'".$shiftCondition;
		else if($equipment=="Yard")
			if($shift=="All")
				$cond = " (EAD.block='$sVal') AND DATE(EAD.start_work_time)='$sDate'";
			else
				$cond = " (EAD.block='$sVal') AND DATE(EAD.start_work_time)='$sDate'".$shiftCondition;

		$sql="select * from(
				select * from (
				select tbl1.equipement as equipment,tbl1.Block as block,tbl2.shift,tbl2.Start_Work_TIME,tbl2.End_Work_TIME,tbl2.Work_Out_Time,tbl2.Duration from
				(Select distinct  sel_block Block,short_name equipement from sparcsn4.xps_che
				inner join sparcsn4.xps_chezone on sparcsn4.xps_chezone.che_id=sparcsn4.xps_che.id
				inner join ctmsmis.yard_block on ctmsmis.yard_block.block=sel_block
				where (short_name like '%$sVal%' OR sel_block like '%$sVal%'))tbl1
				Right JOIN
				(SELECT ctmsmis.ED.equipment,ctmsmis.EAD.block,ctmsmis.EAD.shift, 
					IFNULL(ctmsmis.EAD.start_work_time,'') as Start_Work_TIME, 
					IFNULL(ctmsmis.EAD.end_work_time,'') as End_Work_TIME, 
					IFNULL(ctmsmis.EAD.work_out_time,'') AS Work_Out_Time, 
					CASE EAD.work_out_state WHEN '1' then IFNULL(TIMEDIFF(ctmsmis.EAD.work_out_time,ctmsmis.EAD.start_work_time),'') 
								WHEN '0' then IFNULL(TIMEDIFF(ctmsmis.EAD.end_work_time,ctmsmis.EAD.start_work_time),'') END AS Duration 
								FROM ctmsmis.mis_equip_assign_detail AS EAD 
								INNER JOIN ctmsmis.mis_equip_detail AS ED ON EAD.equip_detail_id=ED.id
								WHERE ".$cond." order by ctmsmis.ED.equipment,ctmsmis.EAD.block
				)tbl2
				on tbl1.equipement=tbl2.equipment
				where tbl2.Duration = ''
				) tbl3
				UNION
				(SELECT ctmsmis.ED.equipment,ctmsmis.EAD.block,ctmsmis.EAD.shift, 
					IFNULL(ctmsmis.EAD.start_work_time,'') as Start_Work_TIME, 
					IFNULL(ctmsmis.EAD.end_work_time,'') as End_Work_TIME, 
					IFNULL(ctmsmis.EAD.work_out_time,'') AS Work_Out_Time, 
					CASE EAD.work_out_state WHEN '1' then IFNULL(TIMEDIFF(ctmsmis.EAD.work_out_time,ctmsmis.EAD.start_work_time),'') 
								WHEN '0' then IFNULL(TIMEDIFF(ctmsmis.EAD.end_work_time,ctmsmis.EAD.start_work_time),'') END AS Duration 
								FROM ctmsmis.mis_equip_assign_detail AS EAD 
								INNER JOIN ctmsmis.mis_equip_detail AS ED ON EAD.equip_detail_id=ED.id
								
								WHERE ".$cond.")
								order by equipment,block
								) as tbl4";
								//echo $sql; exit();

		$sqlRslt=mysqli_query($con_sparcsn4,$sql);		
	// echo "<pre>";
 //    print_r($sqlRslt);
 //    echo "</pre>";
 //    exit();						
	?>
		<table width="100%">
				<tr>
				<td align="left"><h3> GCB Equipment</h3></th></td>
				</tr>
		</table>

		<div class="panel-body">
		<table class="table table-responsive table-bordered table-striped mb-none">
			<thead>
						<tr class="gridDark">
						<th align="center">Block</th>
						<th align="center">Shift</th>						
						<th align="center">Start Work</th>
						<th align="center">End Work</th>						
						<th align="center">Work Out</th>
						<th align="center">Duration</th>
						</tr>
			</thead>
			<tbody>

					<?php
					$eq = "";
					while ($row=mysqli_fetch_object($sqlRslt))						
					{
						if($eq!=$row->equipment)
						{
					?>
						<tr class="gradeX">
							    <th  align="left" colspan="6">Equipment : <?php if($row->equipment) print($row->equipment); else print("&nbsp;");?></th>
						</tr>
						
					<?php
						}
					?>

					 <tr class="gradeX">
								<td  align="center"><?php if($row->block) print($row->block); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->shift) print($row->shift); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->Start_Work_TIME) print($row->Start_Work_TIME); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->End_Work_TIME) print($row->End_Work_TIME); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->Work_Out_Time) print($row->Work_Out_Time); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->Duration) print($row->Duration); else print("&nbsp;");?></td>
						</tr>
					 
					<?php 
						$eq = $row->equipment;
					}
					?>
			</tbody>	

		</table>
		<?php
								$sql="select * from ( select * from (
										select equipement as equipment1,Block as block1 from (select *,if(yard ='CCT',1,2) as sl from ( select distinct sel_block Block,short_name equipement, 
										(select ctmsmis.yard_block.terminal from ctmsmis.yard_block where ctmsmis.yard_block.block=sel_block) as yard from sparcsn4.xps_che 
										inner join sparcsn4.xps_chezone on sparcsn4.xps_chezone.che_id=sparcsn4.xps_che.id 
										where short_name is not null and short_name!='' and short_name not like 'HHT%' and short_name not like 'F%' and short_name not like 'SP%') as tbl 
										where yard is not null order by equipement) as t1 where sl=1 and (equipement like '%$sVal%' OR Block like '%$sVal%')
										) equip
									LEFT join 
									(
									select * from (select * from(
									select * from (
									select tbl1.equipement as equipment,tbl1.Block as block,tbl2.shift,tbl2.Start_Work_TIME,tbl2.End_Work_TIME,tbl2.Work_Out_Time,tbl2.Duration from
									(Select distinct  sel_block Block,short_name equipement from sparcsn4.xps_che
									inner join sparcsn4.xps_chezone on sparcsn4.xps_chezone.che_id=sparcsn4.xps_che.id
									inner join ctmsmis.yard_block on ctmsmis.yard_block.block=sel_block
									where (short_name like '%$sVal%' OR sel_block like '%$sVal%'))tbl1
									Right JOIN
									(SELECT ctmsmis.ED.equipment,ctmsmis.EAD.block,ctmsmis.EAD.shift, 
										IFNULL(ctmsmis.EAD.start_work_time,'') as Start_Work_TIME, 
										IFNULL(ctmsmis.EAD.end_work_time,'') as End_Work_TIME, 
										IFNULL(ctmsmis.EAD.work_out_time,'') AS Work_Out_Time, 
										CASE EAD.work_out_state WHEN '1' then IFNULL(TIMEDIFF(ctmsmis.EAD.work_out_time,ctmsmis.EAD.start_work_time),'') 
													WHEN '0' then IFNULL(TIMEDIFF(ctmsmis.EAD.end_work_time,ctmsmis.EAD.start_work_time),'') END AS Duration 
													FROM ctmsmis.mis_equip_assign_detail AS EAD 
													INNER JOIN ctmsmis.mis_equip_detail AS ED ON EAD.equip_detail_id=ED.id
													
													WHERE ".$cond." order by ctmsmis.ED.equipment,ctmsmis.EAD.block
									)tbl2
									on tbl1.equipement=tbl2.equipment
									where tbl2.Duration = ''
									) tbl3
									UNION
									(SELECT ctmsmis.ED.equipment,ctmsmis.EAD.block,ctmsmis.EAD.shift, 
										IFNULL(ctmsmis.EAD.start_work_time,'') as Start_Work_TIME, 
										IFNULL(ctmsmis.EAD.end_work_time,'') as End_Work_TIME, 
										IFNULL(ctmsmis.EAD.work_out_time,'') AS Work_Out_Time, 
										CASE EAD.work_out_state WHEN '1' then IFNULL(TIMEDIFF(ctmsmis.EAD.work_out_time,ctmsmis.EAD.start_work_time),'') 
													WHEN '0' then IFNULL(TIMEDIFF(ctmsmis.EAD.end_work_time,ctmsmis.EAD.start_work_time),'') END AS Duration 
													FROM ctmsmis.mis_equip_assign_detail AS EAD 
													INNER JOIN ctmsmis.mis_equip_detail AS ED ON EAD.equip_detail_id=ED.id
													
													WHERE ".$cond.")
													order by equipment,block
													) as tbl4		
									WHERE equipment IN ( 
									select equipement as equipment from (select *,if(yard ='CCT',1,2) as sl from ( select distinct sel_block Block,short_name equipement, 
										(select ctmsmis.yard_block.terminal from ctmsmis.yard_block where ctmsmis.yard_block.block=sel_block) as yard from sparcsn4.xps_che 
										inner join sparcsn4.xps_chezone on sparcsn4.xps_chezone.che_id=sparcsn4.xps_che.id 
										where short_name is not null and short_name!='' and short_name not like 'HHT%' and short_name not like 'F%' and short_name not like 'SP%') as tbl 
										where yard is not null order by equipement) as t1 where sl=1 and (equipement like '%$sVal%' OR Block like '%$sVal%'
										)
									))AS tbl5)tt1  on equip.equipment1=tt1.equipment and equip.block1=tt1.block
									)tt2";
								//echo $sql; exit();
								//$sqlRslt=mysql_query($sql,$con_sparcsn4);
								 $sqlRslt = mysqli_query($con_sparcsn4,$sql);
						  ?>
						  <table width="100%">
							<tr>
							  <th align="left"><h3></br>CCT Equipment (Working)</h3></th>
						    </tr>
							</table>

						<table class="table table-responsive table-bordered table-striped mb-none">
						<thead>
						<tr class="gridDark">
							<th align="center">Block</th>
								<th align="center">Shift</th>						
								<th align="center">Start Work</th>
								<th align="center">End Work</th>						
								<th align="center">Work Out</th>
								<th align="center">Duration</th>	
						</tr>
						</thead>
						<tbody>
							<?php
					$eq = "";
					while ($row=mysqli_fetch_object($sqlRslt))						
					{
						if($eq!=$row->equipment1)
						{
					?>
						<tr class="gradeX">
							    <th  align="left" colspan="6">Equipment : <?php if($row->equipment1) print($row->equipment1); else print("&nbsp;");?></th>
						</tr>
						
					<?php
						}
					?>
						 <tr class="gradeX">
								<td  align="center"><?php if($row->block1) print($row->block1); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->shift) print($row->shift); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->Start_Work_TIME) print($row->Start_Work_TIME); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->End_Work_TIME) print($row->End_Work_TIME); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->Work_Out_Time) print($row->Work_Out_Time); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->Duration) print($row->Duration); else print("&nbsp;");?></td>
						</tr>
					 
					<?php 
						$eq = $row->equipment1;
					}
					?>
						</tbody>
						</table>
			 <?php
							$sql="select * from ( select * from (
										select equipement as equipment1,Block as block1 from (select *,if(yard ='NCT',1,2) as sl from ( select distinct sel_block Block,short_name equipement, 
										(select ctmsmis.yard_block.terminal from ctmsmis.yard_block where ctmsmis.yard_block.block=sel_block) as yard from sparcsn4.xps_che 
										inner join sparcsn4.xps_chezone on sparcsn4.xps_chezone.che_id=sparcsn4.xps_che.id 
										where short_name is not null and short_name!='' and short_name not like 'HHT%' and short_name not like 'F%' and short_name not like 'SP%') as tbl 
										where yard is not null order by equipement) as t1 where sl=1 and (equipement like '%$sVal%' OR Block like '%$sVal%')
										) equip
									LEFT join 
									(
									select * from (select * from(
									select * from (
									select tbl1.equipement as equipment,tbl1.Block as block,tbl2.shift,tbl2.Start_Work_TIME,tbl2.End_Work_TIME,tbl2.Work_Out_Time,tbl2.Duration from
									(Select distinct  sel_block Block,short_name equipement from sparcsn4.xps_che
									inner join sparcsn4.xps_chezone on sparcsn4.xps_chezone.che_id=sparcsn4.xps_che.id
									inner join ctmsmis.yard_block on ctmsmis.yard_block.block=sel_block
									where (short_name like '%$sVal%' OR sel_block like '%$sVal%'))tbl1
									Right JOIN
									(SELECT ctmsmis.ED.equipment,ctmsmis.EAD.block,ctmsmis.EAD.shift, 
										IFNULL(ctmsmis.EAD.start_work_time,'') as Start_Work_TIME, 
										IFNULL(ctmsmis.EAD.end_work_time,'') as End_Work_TIME, 
										IFNULL(ctmsmis.EAD.work_out_time,'') AS Work_Out_Time, 
										CASE EAD.work_out_state WHEN '1' then IFNULL(TIMEDIFF(ctmsmis.EAD.work_out_time,ctmsmis.EAD.start_work_time),'') 
													WHEN '0' then IFNULL(TIMEDIFF(ctmsmis.EAD.end_work_time,ctmsmis.EAD.start_work_time),'') END AS Duration 
													FROM ctmsmis.mis_equip_assign_detail AS EAD 
													INNER JOIN ctmsmis.mis_equip_detail AS ED ON EAD.equip_detail_id=ED.id
													
													WHERE ".$cond." order by ctmsmis.ED.equipment,ctmsmis.EAD.block
									)tbl2
									on tbl1.equipement=tbl2.equipment
									where tbl2.Duration = ''
									) tbl3
									UNION
									(SELECT ctmsmis.ED.equipment,ctmsmis.EAD.block,ctmsmis.EAD.shift, 
										IFNULL(ctmsmis.EAD.start_work_time,'') as Start_Work_TIME, 
										IFNULL(ctmsmis.EAD.end_work_time,'') as End_Work_TIME, 
										IFNULL(ctmsmis.EAD.work_out_time,'') AS Work_Out_Time, 
										CASE EAD.work_out_state WHEN '1' then IFNULL(TIMEDIFF(ctmsmis.EAD.work_out_time,ctmsmis.EAD.start_work_time),'') 
													WHEN '0' then IFNULL(TIMEDIFF(ctmsmis.EAD.end_work_time,ctmsmis.EAD.start_work_time),'') END AS Duration 
													FROM ctmsmis.mis_equip_assign_detail AS EAD 
													INNER JOIN ctmsmis.mis_equip_detail AS ED ON EAD.equip_detail_id=ED.id
													
													WHERE ".$cond.")
													order by equipment,block
													) as tbl4		
									WHERE equipment IN ( 
									select equipement as equipment from (select *,if(yard ='NCT',1,2) as sl from ( select distinct sel_block Block,short_name equipement, 
										(select ctmsmis.yard_block.terminal from ctmsmis.yard_block where ctmsmis.yard_block.block=sel_block) as yard from sparcsn4.xps_che 
										inner join sparcsn4.xps_chezone on sparcsn4.xps_chezone.che_id=sparcsn4.xps_che.id 
										where short_name is not null and short_name!='' and short_name not like 'HHT%' and short_name not like 'F%' and short_name not like 'SP%') as tbl 
										where yard is not null order by equipement) as t1 where sl=1 and (equipement like '%$sVal%' OR Block like '%$sVal%'
										)
									))AS tbl5)tt1  on equip.equipment1=tt1.equipment and equip.block1=tt1.block
									)tt2";
								$sqlRslt=mysqli_query($con_sparcsn4,$sql);
						  ?>
						  <table width="100%">
							<tr>
							  <th align="left"><h3></br>NCT Equipment (Working)</h3></th>
						    </tr>
							</table>	
							
							<table class="table table-responsive table-bordered table-striped mb-none">
						<thead>
						<tr class="gridDark">
								<th align="center">Block</th>
								<th align="center">Shift</th>						
								<th align="center">Start Work</th>
								<th align="center">End Work</th>						
								<th align="center">Work Out</th>
								<th align="center">Duration</th>	
						</tr>
						</thead>
						<tbody>
	<?php
					$eq = "";
					while ($row=mysqli_fetch_object($sqlRslt))						
					{
						if($eq!=$row->equipment1)
						{
					?>
						<tr class="gradeX">
							    <th  align="left" colspan="6">Equipment : <?php if($row->equipment1) print($row->equipment1); else print("&nbsp;");?></th>
						</tr>
						
					<?php
						}
					?>
						 <tr class="gradeX">
								<td  align="center"><?php if($row->block1) print($row->block1); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->shift) print($row->shift); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->Start_Work_TIME) print($row->Start_Work_TIME); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->End_Work_TIME) print($row->End_Work_TIME); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->Work_Out_Time) print($row->Work_Out_Time); else print("&nbsp;");?></td>
								<td  align="center"><?php if($row->Duration) print($row->Duration); else print("&nbsp;");?></td>
						</tr>
					 
					<?php 
						$eq = $row->equipment1;
					}
					?>
						</tbody>
						</table>		

		</div>
<?php 
//mysqli_close($con_cchaportdb);
mysqli_close($con_sparcsn4);
?>
		</div>
		<div class="text-right mr-lg">
						
						<a href="<?php echo site_url('report/containerHandlingView/'.$sDate)?>" target="_blank" class="btn btn-primary ml-sm"><i class="fa fa-print"></i> Print</a>
		</div>
	</div>
</section>

	