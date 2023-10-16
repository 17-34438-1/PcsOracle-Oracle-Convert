<?php if(@$_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Bearth Operator Report</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php } 
	else if(@$_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=EXPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
$ddl_imp_rot_no=@$_REQUEST['ddl_imp_rot_no']; 

	include("dbConection.php");
	include("dbOracleConnection.php");
	
	$sql=oci_parse($con_sparcsn4_oracle,"select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	oci_execute($sql);
	//$rowVessel=oci_fetch_object($sql);
	$vvdGkey="";
	while(($rowVessel=oci_fetch_object($sql))!=false){
		$vvdGkey=$rowVessel->VVD_GKEY;

	}
	
	
	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql);
	$yardAndBlockStatus=0;
	
	if($type=="deli")
	{
		$col = "b.time_out";		
		$head = "Date Wise Delivery Import Container List";
	}
	
	elseif($type=="assign")
	{
		$col = "b.flex_date01";
		$head = "Assignment Date Wise Import Container List";
	}
	else
	{
		$col = "b.time_in";
		$head = "Discharge Import Container List";
	}
	
	
	if($block=="" || $block==null)
	{
		$find="Yard_No='$yard_no'";
		$yardAndBlockStatus=0;
	}
	else{
		$find="Yard_No='$yard_no' and Block_No='$block'";
		$yardAndBlockStatus=1;
	}
	
	
	
	
	
	
	
	?>
<html>
<title>YARD WISE PROPOSED EMPTY AND EMPTY  CONTAINER REPORT</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr align="center">
					<td colspan="12"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><?php echo $head; ?></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b>DATE : <?php echo $fromdate; ?>&nbsp;&nbsp;Yard NO : <?php echo $yard_no; ?>&nbsp;&nbsp;Block NO : <?php if($block=="" || $block==null) echo "ALL";  else echo $block; ?></b></font></td>
				</tr>


			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
		<thead>
			<tr class="gridDark">
				<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Rotation No.</b></td>
				<td style="border-width:3px;border-style: double;"><b>Type</b></td>
				<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
				<td style="border-width:3px;border-style: double;"><b>Status</b></td>	
				<td style="border-width:3px;border-style: double;"><b>Assignment Type</b></td>		
				<td style="border-width:3px;border-style: double;"><b>C&F</b></td>		
				<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
				<td style="border-width:3px;border-style: double;"><b>Current Position</b></td>
				<!--td style="border-width:3px;border-style: double;"><b>Discharge date</b></td-->
				<td style="border-width:3px;border-style: double;"><b>Assignment date</b></td>
				<td style="border-width:3px;border-style: double;"><b>Delivery/Empty Date</b></td>
				<td style="border-width:3px;border-style: double;"><b>Stripped By</b></td>
				<td style="border-width:3px;border-style: double;"><b>Remarks</b></td>
			</tr>
		</thead>
<?php
		

			$query_yard="SELECT a.cont_no,
			a.iso_code,
			a.weight,
			a.assignmentDate AS assignmentdate,
			a.unit_gkey,
			a.goods,
			a.cf_name AS cf,
			a.mfdch_desc,
			a.mlo AS mlo,
			a.rot_no,
			a.cont_status AS statu,
			(SELECT ctmsmis.mis_exp_unit_load_failed.last_update
			FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey=a.unit_gkey) AS last_update,
			(SELECT ctmsmis.mis_exp_unit_load_failed.user_id
			FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey=a.unit_gkey) AS user_id,
			IF(UCASE(a.stay) LIKE '%STAY%',1,0) AS stay
			FROM ctmsmis.tmp_oracle_assignment a
			WHERE mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF') and  DATE(a.flex_date01)='$fromdate' ";
		
			

	
	$query=mysqli_query($con_sparcsn4,$query_yard);

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
	$numRows=mysqli_num_rows($query);
	
	while($row=mysqli_fetch_object($query))
	{
		$i++;
		$stayed=$stayed+$row->stay;

		$unitGKey="";
	$goods="";
	$unitGKey=$row->unit_gkey;
	$goods=$row->goods;

	/*$query1="SELECT   
	(CASE WHEN b.time_out >= to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS') 
	AND b.time_out < to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') THEN 'Shift A'
	WHEN b.time_out >= to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') 
	AND b.time_out <
	to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift B'
	WHEN b.time_out >= to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
	AND b.time_out < to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift C'
	END) AS shift, (CASE WHEN b.time_out IS NULL THEN 2 ELSE 1 END) AS sl, b.*
	FROM inv_unit_fcy_visit b
	WHERE b.unit_gkey='$unitGKey' AND  to_date(to_char(b.flex_date01,'yyyy-mm-dd'),'yyyy-mm-dd')=to_date('$fromdate','yyyy-mm-dd') ";*/

		$query1="SELECT   
	(CASE WHEN b.time_out >= to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS') 
	AND b.time_out < to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') THEN 'Shift A'
	WHEN b.time_out >= to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') 
	AND b.time_out <
	to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift B'
	WHEN b.time_out >= to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
	AND b.time_out < to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift C'
	END) AS shift, (CASE WHEN b.time_out IS NULL THEN 2 ELSE 1 END) AS sl, b.time_out,b.FLEX_STRING10,b.time_in
	FROM inv_unit_fcy_visit b
	WHERE b.unit_gkey='$unitGKey'";
	

	 $stm1=oci_parse($con_sparcsn4_oracle,$query1);
	 oci_execute( $stm1);
	 $result1=array();
	 $nrows1 = oci_fetch_all($stm1, $result1, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	 oci_free_statement($stm1);
	

	 $stm1=oci_parse($con_sparcsn4_oracle,$query1);
	 oci_execute( $stm1);
	 $query1Res=oci_fetch_object($stm1);

	/*$query2="SELECT g.id as mlo,inv_unit.goods_and_ctr_wt_kg AS weight,inv_unit.*,g.* FROM inv_unit 
	INNER JOIN ref_bizunit_scoped g ON inv_unit.line_op = g.gkey  
	WHERE inv_unit.gkey='$unitGKey'";*/
	$query2="SELECT g.id as mlo,inv_unit.goods_and_ctr_wt_kg AS weight FROM inv_unit 
	INNER JOIN ref_bizunit_scoped g ON inv_unit.line_op = g.gkey  
	WHERE inv_unit.gkey='$unitGKey'";
	

	$stm2=oci_parse($con_sparcsn4_oracle,$query2);
	oci_execute( $stm2);
	$result2=array();
	$nrows2 = oci_fetch_all($stm2, $result2, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	oci_free_statement($stm2);

	$stm2=oci_parse($con_sparcsn4_oracle,$query2);
	oci_execute( $stm2);
	$query2Res=oci_fetch_object($stm2);

	//if($nrows1 > 0 && $nrows2 > 0){
		$carrierPosition="";

		$carrierPositionquery="SELECT substr(srv_event_field_changes.new_value,7) AS carrentPosition FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.applied_to_gkey='$unitGKey' AND srv_event.event_type_gkey IN(31450,31443,31447)  
		AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !=''
		AND srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey<(SELECT srv_event.gkey 
		FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.event_type_gkey=4 AND srv_event.applied_to_gkey='$unitGKey' AND metafield_id='unitFlexString01'
		AND new_value IS NOT NULL ORDER BY srv_event_field_changes.gkey DESC FETCH FIRST 1 ROWS ONLY ) 
		ORDER BY srv_event.gkey DESC FETCH FIRST 1 ROWS ONLY ";
		
		$carrierPositionStm=oci_parse($con_sparcsn4_oracle,$carrierPositionquery);
		oci_execute( $carrierPositionStm);
		$result3=array();
		$nrows3= oci_fetch_all($carrierPositionStm, $result3, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		oci_free_statement($carrierPositionStm);

		$carrierPositionStm=oci_parse($con_sparcsn4_oracle,$carrierPositionquery);
		oci_execute( $carrierPositionStm);
		$carrierPositionRes=oci_fetch_object($carrierPositionStm);
			
		if($nrows3>0){
			$carrierPosition=$carrierPositionRes->CARRENTPOSITION;

		}
		else{
			$carrierPositionquery1="SELECT substr(srv_event_field_changes.new_value,7) AS carrentPosition
			FROM srv_event 
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey='$unitGKey' AND srv_event.event_type_gkey IN(31450,31443,31447) 
			ORDER BY srv_event_field_changes.gkey DESC FETCH FIRST 1 ROWS ONLY";
		$carrierPositionStm1=oci_parse($con_sparcsn4_oracle,$carrierPositionquery1);

		//echo "\n". $carrierPositionquery1;
		
		oci_execute( $carrierPositionStm1);
		$result4=array();
		$nrows4= oci_fetch_all($carrierPositionStm1, $result4, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		oci_free_statement($carrierPositionStm1);

		$carrierPositionStm1=oci_parse($con_sparcsn4_oracle,$carrierPositionquery1);
		oci_execute( $carrierPositionStm1);
		$carrierPositionRes1=oci_fetch_object($carrierPositionStm1);

		$carrierPosition=$carrierPositionRes1->CARRENTPOSITION;
		}
		
		$yardName="";

		$queryYard="SELECT ctmsmis.cont_yard('$carrierPosition') AS Yard_No";
		$queryYardRes=mysqli_query($con_sparcsn4,$queryYard);
		$yardRowsNum=mysqli_num_rows($queryYardRes);
		$yardRow=mysqli_fetch_object($queryYardRes);
		$yardName=$yardRow->Yard_No;

		$blockName="";

		$queryBlock="SELECT ctmsmis.cont_block('$carrentPosition',ctmsmis.cont_yard('$carrentPosition')) AS Block_No";
		$queryBlockRes=mysqli_query($con_sparcsn4,$queryBlock);
		$blockRowsNum=mysqli_num_rows($queryBlockRes);
		$blockRow=mysqli_fetch_object($queryBlockRes);
		$$blockName=$blockRow->Block_No;
		//if( ($yardAndBlockStatus==0 && $yardName==$yard_no) || ($yardAndBlockStatus==1 && $yardName==$yard_no && $blockName==$block) ){

			$query3="SELECT creator as stripped_by FROM srv_event 
			WHERE applied_to_gkey='$unitGKey' AND event_type_gkey=30 
			ORDER BY gkey DESC fetch first 1 rows only";
		
			$stm3=oci_parse($con_sparcsn4_oracle,$query3);
			oci_execute( $stm3);
			$query3Res=oci_fetch_object($stm3);
		
			$query4="SELECT srv_event.created as proEmtyDate FROM  srv_event 
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE applied_to_gkey='$unitGKey' AND event_type_gkey=4 AND srv_event_field_changes.new_value='E' fetch first 1 rows only";
		
			$stm4=oci_parse($con_sparcsn4_oracle,$query4);
			oci_execute( $stm4);
			$query4Res=oci_fetch_object($stm4);
		


		//if($yard_no=="GCB")
		//{
			if($i==$numRows)
				$allCont .=$row->cont_no;
			else
				$allCont .=$row->cont_no.", ";
		//}
		$sqlIsoCode=mysqli_query($con_cchaportdb,"select cont_iso_type from igm_detail_container where cont_number='$row->cont_no'");
		
		//echo "select cont_iso_type from igm_detail_container where cont_number='$row->cont_no";
	
		$iso = "";
		while($rtnIsoCode=mysqli_fetch_object($sqlIsoCode))
		{
			$iso=$rtnIsoCode->cont_iso_type;
		}
		
		// $rtnIsoCode=mysqli_fetch_object($sqlIsoCode);
		// $iso=$rtnIsoCode->cont_iso_type;
		if(substr($iso,0,1)==2)
			$j20=$j20+1;
		else
			$j40=$j40+1;
		
		if(substr($iso,0,1)==2)
		{
		
			if($query1Res->SHIFT=="Shift A")
			    $a20 = $a20+1;
			if($query1Res->SHIFT=="Shift B")
				$b20 = $b20+1;
			if($query1Res->SHIFT=="Shift C")
				$c20 = $c20+1;
		}
		else
		{
				
			if($query1Res->SHIFT=="Shift A")
				$a40 = $a40+1;
			if($query1Res->SHIFT=="Shift B")
				$b40 = $b40+1;
			if($query1Res->SHIFT=="Shift C")
				$c40 = $c40+1;
		}
		
		if($shift==$query1Res->SHIFT or $i==1)
		{
			if(substr($iso,0,1)==2)
				$tot20 = $tot20+1;
			else 
				$tot40 = $tot40+1;
		}
		
		if($yard!=$yardRow->Yard_No)
		{
			$yard=$yardRow->Yard_No;
			if($i!=1)
			{
			?>
			<tr>
				<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
			</tr>
			<?php
				if(substr($iso,0,1)==2)
				{
					$tot20 = 1;
					$tot40 = 0;
				}
				else
				{
					$tot20 = 0;
					$tot40 = 1;
				}
			}
			?>
			<tr>
				<td colspan="15"><b><?php  echo $yardRow->Yard_No;?></b></td>
			</tr>
			<?php
			$i=1;
		}
		if($shift!=$query1Res->SHIFT)
		{	
			$shift=$query1Res->SHIFT;		
			if($i!=1)
			{
				if(substr($iso,0,1)==2)
				{
					$tot20 = $tot20;
				}
				else
				{
					$tot40 = $tot40;
				}
			?>
			<tr>
				<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
			</tr>
			<?php
				if(substr($iso,0,1)==2)
				{
					$tot20 = 1;
					$tot40 = 0;
				}
				else
				{
					$tot20 = 0;
					$tot40 = 1;
				}
			}
			?>
			<tr>
				<td colspan="15"><b><?php  echo $query1Res->SHIFT;?></b></td>
			</tr>	
			<?php	
			$i=1;
		}
		//$shift=$row->shift;
		$shift=$query1Res->SHIFT;	
	?>
	
	<?php if (($row->last_update!="") or  ($row->last_update!=null))    {?>
          <tr bgcolor="#ec7063" align="center">
	<?php } else if (($query1Res->TIME_OUT)=="" or ($query1Res->TIME_OUT)==null) {?>
		<tr  bgcolor="#F2DC5D" align="center">
	<?php } else {?>
	<tr align="center">
	<?php } ?>
			<td><?php  echo $i;?></td>
			<td><?php if($row->cont_no) echo $row->cont_no; else echo "&nbsp;";?></td>
			<!--td><?php if($query1Res->FLEX_STRING10) echo $$query1Res->FLEX_STRING10; else echo "&nbsp;";?></td-->
			<td><?php if($row->rot_no) echo $row->rot_no; else echo "&nbsp;";?></td>
			<td><?php if($iso) echo $iso; else echo "&nbsp;";?></td>
			<!--td><?php if($query2Res->MLO) echo $query2Res->MLO; else echo "&nbsp;";?></td-->
			<td><?php if($row->mlo) echo $row->mlo; else echo "&nbsp;";?></td>
			<td><?php if($row->statu) echo $row->statu; else echo "&nbsp;";?></td>
			<td><?php if($row->mfdch_desc) echo $row->mfdch_desc; else echo "&nbsp;";?></td>
			<td><?php if($row->cf) echo $row->cf; else echo "&nbsp;";?></td>
			<!--td><?php if($query2Res->WEIGHT) echo $query2Res->WEIGHT; else echo "&nbsp;";?></td-->
			<td><?php if($row->weight) echo $row->weight; else echo "&nbsp;";?></td>
			<td><?php echo $carrierPosition;?></td>
			<!--td><?php if($query1Res->TIME_IN) echo $query1Res->TIME_IN; else echo "&nbsp;";?></td-->
			<td><?php if($row->assignmentdate) echo $row->assignmentdate; else echo "&nbsp;";?></td>
			<td><?php if($query1Res->TIME_OUT) echo $query1Res->TIME_OUT; else echo "&nbsp;";?></td>
			<td><?php if($query3Res->STRIPPED_BY) echo $query3Res->STRIPPED_BY; else echo "&nbsp;";?></td>
			<td><?php if (($row->last_update!="") or  ($row->last_update!=null)) echo "Tried to Exp. Load:".$row->last_update." by ".$row->user_id; else echo "&nbsp;";?></td>
		</tr>
	<?php
		//}
	  //}

	} 
	mysqli_close($con_cchaportdb);
	?>
		<tr>
			<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
			
		</tr>
		<?php 
		if($yard_no=="GCB")
		{
		?>
		<tr>
			<td colspan="15"><?php echo $allCont;?></td>
		</tr>
		<!--tr>
			
			<td colspan="15" align="center" >
			<form action="<?php echo site_url('report/strippingPreparation/');?>" method="post">
				<input type="hidden" name="fromdate" value="<?php echo $fromdate; ?>">
				<button>StrippingPreparation</button>
			</form>
			<form action="<?php echo site_url('report/stripping/');?>" method="post">
				<input type="hidden" name="fromdate" value="<?php echo $fromdate; ?>">
				<button>Stripping</button>
			</form>
			
			</td>
		</tr-->
		<?php 
		}
		?>
	</table>
	<br/>
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
		<thead>
			<tr>
				<th rowspan="3">UNIT/YARD</th>
				<th rowspan="3">SHIFT</th>
				<th colspan="3">TOTAL ASSIGNMENT</th>
				<th colspan="3">STRIPPED</th>
				<th colspan="3">BALANCE</th>
				<th rowspan="3">REMARKS</th>
			</tr>
			<tr>
				<th>20(L)</th>
				<th>40(L)</th>
				<th>Total</th>
				<th>20(E)</th>
				<th>40(E)</th>
				<th>Total</th>
				<th>20(L)</th>
				<th>40(L)</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			<tr align="center">
				<td><?php echo $yard_no;?></td>
				<td>A</td>
				<td><?php echo $j20;?></td>
				<td><?php echo $j40;?></td>
				<td><?php echo $j20+$j40?></td>
				<td><?php echo $a20;?></td>
				<td><?php echo $a40;?></td>
				<td><a href="<?php echo site_url('report/shiftWiseContainerReport/A/'.$yard_no.'/'.$fromdate.'/'.$block);?>" target="_blank"><?php echo $a20+$a40;?></a></td>
				<td>
					<?php 
						$balA20 = $j20-$a20;
						echo $balA20;
					?>
				</td>
				<td>
					<?php 
						$balA40 = $j40-$a40;
						echo $balA40;
					?>
				</td>
				<td><a href="<?php echo site_url('report/shiftWiseContainerReport/N/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $balA20+$balA40;?></td>
				<td width="150"></td>
			</tr>
			<tr align="center">
				<td><?php echo $yard_no; ?></td>
				<td>B</td>
				<td><?php echo $balA20;?></td>
				<td><?php echo $balA40;?></td>
				<td><?php echo $balA20+$balA40?></td>
				<td><?php echo $b20;?></td>
				<td><?php echo @$b40;?></td>
				<td><a  href="<?php echo site_url('report/shiftYardAndBlockWiseContainerReport/B/'.$yard_no.'/'.$fromdate.'/'.$block);?>" target="_blank"><?php echo $b20+@$b40;?></a></td>
				<td>
					<?php 
						$balB20 = $balA20-$b20;
						echo $balB20;
					?>
				</td>
				<td>
					<?php 
						$balB40 = $balA40-@$b40;
						echo $balB40;
					?>
				</td>
				<td><a href="<?php echo site_url('report/shiftYardAndBlockWiseContainerReport/N/'.$yard_no.'/'.$fromdate.'/'.$block);?>" target="_blank"><?php echo $balB20+$balB40;?></td>
				<td></td>
			</tr>
			<tr align="center">
				<td><?php echo $yard_no; ?></td>
				<td>C</td>
				<td><?php echo $balB20;?></td>
				<td><?php echo $balB40;?></td>
				<td><?php echo $balB20+$balB40?></td>
				<td><?php echo $c20;?></td>
				<td><?php echo @$c40;?></td>
				<td><a  href="<?php echo site_url('report/shiftYardAndBlockWiseContainerReport/C/'.$yard_no.'/'.$fromdate.'/'.$block);?>" target="_blank"><?php echo $c20+@$c40;?></a></td>
				<td>
					<?php 
						$balC20 = $balB20-$c20;
						echo $balC20;
					?>
				</td>
				<td>
					<?php 
						$balC40 = $balB40-@$c40;
						echo $balC40;
					?>
				</td>
				<td><a href="<?php echo site_url('report/shiftYardAndBlockWiseContainerReport/N/'.$yard_no.'/'.$fromdate.'/'.$block);?>" target="_blank"><?php echo $balC20+$balC40;?></td>
				<td><a href="<?php echo site_url('report/shiftYardAndBlockWiseContainerReport/Stay/'.$yard_no.'/'.$fromdate.'/'.$block);?>" target="_blank"><?php echo "Stayed: ". $stayed; ?></a></td>
			</tr>
		</tbody>
	</table>
	<?php 
	@mysqli_close($con_sparcsn4);
	if(@$_POST['options']=='html'){?>	
		</BODY>
	</HTML>
<?php }?>
