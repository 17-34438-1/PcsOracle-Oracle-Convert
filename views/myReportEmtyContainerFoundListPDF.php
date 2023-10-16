<html>
<?php 
	include("dbConection.php");
	include("dbOracleConnection.php");
	include("mydbPConnection.php");

	$yardStatus=0;
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
	
	
	//for Security building user: "pass"  
   if($this->session->userdata('login_id')=="pass")
	{
		$substr1="";
		$substr2="";
		
	}
	else
	{
		//$substr1="AND config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')";
		$substr1="AND mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')";
		if($yard_no=="all")
		{
			$substr1= $substr1." order by Yard_No";
			$yardStatus=0;
		}
		else
		{
			$substr1= $substr1." AND Yard_No='$yard_no' order by Yard_No";
			$yardStatus=1;
		}
		
	}
	
	?>

<head>
    <table border=0 width="100%">				
		<!-- <tr >
			<td align="center" colspan="12"><font size="4"><title align="center">PROPOSED EMPTY AND EMPTY  CONTAINER REPORT</title></font></td>
		</tr> -->
	</table>
</head>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<!--tr>
					<td align="center" colspan="12"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
				</tr-->
				<tr>
					<td colspan="12" align="center"><img width="200px" height="60px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr>
					<td align="center" colspan="12"><font size="4"><b><?php echo $head; ?></b></font></td>
				</tr>
				<tr>
					<td align="center" colspan="12"><font size="4"><b>DATE : <?php echo $fromdate; ?>&nbsp;&nbsp;Yard NO : <?php echo $yard_no; ?></b></font></td>
				</tr>
			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
	<tr align="center">
		<td style="border-width:3px;"><b>SlNo.</b></td>
		<td style="border-width:3px;"><b>Container No.</b></td>
		<td style="border-width:3px;"><b>Rotation No.</b></td>
		<td style="border-width:3px;"><b>Type</b></td>
		<td style="border-width:3px;"><b>MLO</b></td>
		<td style="border-width:3px;"><b>Status</b></td>	
		<td style="border-width:3px;"><b>Assignment Type</b></td>			
		<td style="border-width:3px;"><b>C&F Name</b></td>
		<td style="border-width:3px;"><b>Weight</b></td>
		<td style="border-width:3px;"><b>Current Position</b></td>
		<!--td style="border-width:3px;border-style: double;"><b>Discharge date</b></td-->
		<td style="border-width:3px;"><b>Assignment date</b></td>
		<td style="border-width:3px;"><b>Delivery/Empty Date</b></td>
		<td style="border-width:3px;"><b>Stripped By</b></td>
		<td style="border-width:3px;"><b>Remarks</b></td>
	</tr>

<?php
				

			$str_qry="SELECT a.cont_no,
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
			WHERE a.assignmentDate = '$fromdate'  $substr1";
	$query=mysqli_query($con_sparcsn4, $str_qry);
	$numRows=mysqli_num_rows($query);
	
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
	$stayed=0;   //stayed
	
	while($row=mysqli_fetch_object($query)){
	$i++;
	$stayed=$stayed+$row->stay;

	$unitGKey="";
	$goods="";
	$unitGKey=$row->unit_gkey;
	$goods=$row->goods;
	
	//made by awal start
	$strCF = "select ref_bizunit_scoped.name from inv_unit
	inner join inv_goods on inv_goods.gkey=inv_unit.goods
	inner join ref_bizunit_scoped on ref_bizunit_scoped.gkey=inv_goods.shipper_bzu
	where inv_unit.gkey='$unitGKey'";
	$stmCF=oci_parse($con_sparcsn4_oracle,$strCF);				
	oci_execute($stmCF);
	$query1ResCF=oci_fetch_object($stmCF);
	$cfName = $query1ResCF->NAME;
	//echo $cfName."<br>";
	//made by awal end

	$query1="SELECT   
		(CASE WHEN b.time_out >= to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS') 
		AND b.time_out < to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') THEN 'Shift A'
		WHEN b.time_out >= to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') 
		AND b.time_out <
		to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift B'
		WHEN b.time_out >= to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
		AND b.time_out < to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift C'
		END) AS shift, (CASE WHEN b.time_out IS NULL THEN 2 ELSE 1 END) AS sl, b.flex_string10,b.time_in,b.time_out
		FROM inv_unit_fcy_visit b
		WHERE b.unit_gkey='$unitGKey'";

	 $stm1=oci_parse($con_sparcsn4_oracle,$query1);
	 oci_execute( $stm1);
	 $result1=array();
	 $nrows1 = oci_fetch_all($stm1, $result1, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	 oci_free_statement($stm1);

	 $stm1=oci_parse($con_sparcsn4_oracle,$query1);
	 oci_execute($stm1);	 
	 $query1Res=oci_fetch_object($stm1);
	 
	 $query1Res_shift = "";
	 while(($rowQuery1Res = oci_fetch_object($query1Res))!= false){		 
		$query1Res_shift = $rowQuery1Res->SHIFT;
	}

	$query2="SELECT * FROM inv_unit 
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

	if($nrows1>0 && $nrows2>0){
	$carrierPosition="";

	$carrierPositionquery="SELECT substr(srv_event_field_changes.new_value,7) AS CARRENTPOSITION FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.applied_to_gkey='$unitGKey' AND srv_event.event_type_gkey IN(31450,31443,31447) 
		AND srv_event_field_changes.new_value IS NOT NULL 
		
		AND srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey<(SELECT srv_event.gkey 
		FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.event_type_gkey=31426 AND srv_event.applied_to_gkey='$unitGKey' AND 
		(metafield_id='unitFlexString01' or metafield_id='posName')
		AND new_value IS NOT NULL ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only) 
		ORDER BY srv_event.gkey DESC fetch first 1 rows only";
	$carrierPositionStm=oci_parse($con_sparcsn4_oracle,$carrierPositionquery);
	oci_execute( $carrierPositionStm);
	$result3=array();
	$nrows3= oci_fetch_all($carrierPositionStm, $result3, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	oci_free_statement($carrierPositionStm);
		
	if($nrows3>0){
		$carrierPositionStm=oci_parse($con_sparcsn4_oracle,$carrierPositionquery);
		oci_execute( $carrierPositionStm);
		while(($carrierPositionRes = oci_fetch_object($carrierPositionStm))!= false){
			$carrierPosition=$carrierPositionRes->CARRENTPOSITION;
		}
	}
	else{
		$carrierPositionquery1="SELECT substr(srv_event_field_changes.new_value,7) AS CARRENTPOSITION
			FROM srv_event 
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey='$unitGKey' AND srv_event.event_type_gkey IN(31450,31443,31447) 
			ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only";
			
		$carrierPositionStm1=oci_parse($con_sparcsn4_oracle,$carrierPositionquery1);
		oci_execute( $carrierPositionStm1);
		oci_free_statement($carrierPositionStm1);

		$carrierPositionStm1=oci_parse($con_sparcsn4_oracle,$carrierPositionquery1);
		oci_execute( $carrierPositionStm1);
		while(($carrierPositionRes1 = oci_fetch_object($carrierPositionStm1))!= false){
			$carrierPosition=$carrierPositionRes1->CARRENTPOSITION;
			// //echo "second ". $carrierPosition."<br>";
		}	
		// echo "second ". $carrierPosition;
		oci_free_statement($carrierPositionStm1);
	}
	$yardName="";

	$queryYard="SELECT ctmsmis.cont_yard('$carrierPosition') AS Yard_No";
	$queryYardRes=mysqli_query($con_sparcsn4,$queryYard);
	$yardRowsNum=mysqli_num_rows($queryYardRes);
	$yardRow=mysqli_fetch_object($queryYardRes);
	$yardName=$yardRow->Yard_No;
	


	//if($yardStatus==0 || ($yardStatus==1 && ($yardName==$yard_no))){

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

	$i++;
	$stayed=$stayed+$row->stay;

	// if($yard_no=="GCB")
	// {
		if($i==$numRows)
			$allCont .=$row->cont_no;
		else
			$allCont .=$row->cont_no.", ";
	//}
	$sqlIsoCode=mysqli_query($con_cchaportdb,"select cont_iso_type from igm_detail_container where cont_number='$row->cont_no'");
	
	//echo "select cont_iso_type from igm_detail_container where cont_number='$row->cont_no";
	$rtnIsoCode=mysqli_fetch_object($sqlIsoCode);

	$b40 = 0;
	$c40 = 0;

	$iso=$rtnIsoCode->cont_iso_type;
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

	if($yard_no!=$yardRow->Yard_No)
	{
		$yard=$yardRow->Yard_No;
		if($i!=1)
		{
		?>
		<tr>
			<td colspan="13"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
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
			<td colspan="13"><b><?php  echo $yardRow->Yard_No;?></b></td>
		</tr>
		<?php
		//$i=1;
	}
	if($shift!=$query1Res->SHIFT)
	{	
		$shift=$query1Res->SHIFT;		
		if($i!=1)
		{
		?>
		<tr>
			<td colspan="13"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
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
			<td colspan="13"><b><?php  echo $query1Res->SHIFT;?></b></td>
		</tr>	
		<?php	
		$i=1;
	}
	?>
	
	<?php if (($row->last_update!="") or  ($row->last_update!=null)) {?>
          <tr bgcolor="#ec7063" align="center">
	<?php } else if (($row->delivery)=="" or ($row->delivery)==null) {?>
		<tr  bgcolor="#F2DC5D" align="center">
	<?php } else {?>
	<tr align="center">
	<?php } ?>
			<td><?php  echo $i;?></td>
			<td><?php if($row->cont_no) echo $row->cont_no; else echo "&nbsp;";?></td>
			<td><?php if($row->rot_no) echo $row->rot_no; else echo "&nbsp;";?></td>
			<td><?php if($iso) echo $iso; else echo "&nbsp;";?></td>
			<td><?php if($row->mlo) echo $row->mlo; else echo "&nbsp;";?></td>
			<td><?php if($row->statu) echo $row->statu; else echo "&nbsp;";?></td>
			<td><?php if($row->mfdch_desc) echo $row->mfdch_desc; else echo "&nbsp;";?></td>
			<td><?php if($cfName) echo $cfName; else echo "&nbsp;";?></td>
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
    }
	//...
} 

	?>
		<tr>
			<td colspan="13"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
			
		</tr>
		<?php 
		if($yard_no=="GCB")
		{
		?>
		<tr>
			<td colspan="13"><?php echo $allCont;?></td>
		</tr>

		<?php 
		}
		?>
	</table>
	<br/>
		<table border="1" align="center" style="border-collapse: collapse;">
		<thead>
			<tr>
				<th rowspan="2">UNIT/YARD</th>
				<th rowspan="2">SHIFT</th>
				<th colspan="3">TOTAL ASSIGNMENT</th>
				<th colspan="3">STRIPPED BY HHT</th>
				<th colspan="3">BALANCE</th>
				<th rowspan="2">REMARKS</th>
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
				<td><?php echo $yard_no; ?></td>
				<td>A</td>
				<td><?php echo $j20;?></td>
				<td><?php echo $j40;?></td>
				<td><?php echo $j20+$j40;?></td>
				<td><?php echo $a20;?></td>
				<td><?php echo $a40;?></td>
				<td><a href="<?php echo site_url('report/shiftWiseContainerReport/A/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $a20+$a40;?></a></td>
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
				<td><?php echo $balA20+$balA40;?></td>
				<td><?php echo $b20;?></td>
				<td><?php echo $b40;?></td>
				<td><a  href="<?php echo site_url('report/shiftWiseContainerReport/B/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $b20+$b40;?></a></td>
				<td>
					<?php 
						$balB20 = $balA20-$b20;
						echo $balB20;
					?>
				</td>
				<td>
					<?php 
						$balB40 = $balA40-$b40;
						echo $balB40;
					?>
				</td>
				<td><a href="<?php echo site_url('report/shiftWiseContainerReport/N/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $balB20+$balB40;?></td>
				<td></td>
			</tr>
			<tr align="center">
				<td><?php echo $yard_no; ?></td>
				<td>C</td>
				<td><?php echo $balB20;?></td>
				<td><?php echo $balB40;?></td>
				<td><?php echo $balB20+$balB40;?></td>
				<td><?php echo $c20;?></td>
				<td><?php echo $c40;?></td>
				<td><a  href="<?php echo site_url('report/shiftWiseContainerReport/C/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $c20+$c40;?></a></td>
				<td>
					<?php 
						$balC20 = $balB20-$c20;
						echo $balC20;
					?>
				</td>
				<td>
					<?php 
						$balC40 = $balB40-$c40;
						echo $balC40;
					?>
				</td>
				<td><a href="<?php echo site_url('report/shiftWiseContainerReport/N/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $balC20+$balC40;?></td>
			    <td><a href="<?php echo site_url('report/shiftWiseContainerReport/Stay/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo "Stayed: ". $stayed; ?></a></td>
			</tr>
		</tbody>
	</table>

		</body>
	</html>

