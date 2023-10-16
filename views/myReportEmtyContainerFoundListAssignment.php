<?php if($_POST['fileOptions']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Assignment Report</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php } 
	else if($_POST['fileOptions']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=Report.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}


	include("dbConection.php");
	include("dbOracleConnection.php");
	include("mydbPConnection.php");	
	

	if($type=="deli1")
	{
		$col = "b.time_out";		
		$head = "Date Wise Delivery Import Container List";
	}
	
	elseif($type=="assign1")
	{
		$col = "b.flex_date01";
		$head = "Assignment Date Wise Import Container List";
	}
	else
	{
		$col = "b.time_in";
		$head = "Discharge Import Container List";
	}
	
	$substr1 = "";
	if($this->session->userdata('login_id')=="pass")
	{
		//$substr1=" AND config_metafield_lov.mfdch_value!='CANCEL'";
		// $substr1=" AND mfdch_value!='CANCEL' order by sl,Yard_No,shift,proEmtyDate";
		$substr1=" AND mfdch_value!='CANCEL' order by Yard_No";
		//$substr2=" order by sl,Yard_No,shift,proEmtyDate";
		$yardStatus=0;
	}
	else
	{
		//$substr1="AND config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')";
		$substr1=" AND mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')";
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
<html>
<title>PROPOSED EMPTY AND EMPTY CONTAINER REPORT</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr align="center">
					<td colspan="12"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><?php echo $head; ?> </b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b>DATE : <?php echo $fromdate; ?>&nbsp;&nbsp;Yard NO : <?php echo $yard_no; ?></b></font></td>
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

		


			$str="SELECT a.cont_no,
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
			

			
	
	
	$query=mysqli_query($con_sparcsn4,$str);
	$numRows=mysqli_num_rows($query);
	// echo "Total : ".$numRows."<br>";
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
	
	//include("mydbPConnection.php");	
	
	while($row=mysqli_fetch_object($query))
	{
		//print_r($row);
		$query1="";
		$queryTotExportCont="";
		$unitGKey="";
		$goods="";
		$unitGKey=$row->unit_gkey;
		
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
		$goods=$row->goods;
		

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
		
		//echo "<br>";
		
		// $queryTotExportCont = oci_parse($con_sparcsn4_oracle,$query1);
		// oci_execute($queryTotExportCont);
		// $i=0;
		// while(($rowTotExportCont = oci_fetch_object($queryTotExportCont))!= false){
			// $i++;
		 // $rowTotExportCon=$rowTotExportCont->SHIFT;
		// }
		//echo "I=".$i;
				
		$stm1=oci_parse($con_sparcsn4_oracle,$query1);				
		oci_execute($stm1);
		// while(($roworacle = oci_fetch_object($stm1))!= false){
			// echo ".........";
		// }
		//echo oci_num_rows($stm1)."<br>";
			 
		$result1=array();
		$nrows1 = oci_fetch_all($stm1, $result1, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		oci_free_statement($stm1);
		 
		$stm1=oci_parse($con_sparcsn4_oracle,$query1);				
		oci_execute($stm1);
		 //$nrows1 = oci_num_rows($stm1);

		 
		 

		 // $stm1=oci_parse($con_sparcsn4_oracle,$query1);
		 // oci_execute( $stm1);
		 $query1Res=oci_fetch_object($stm1);
		 $query1Res_shift = "";
		 //$nrows1 = 0;
		 while(($rowQuery1Res = oci_fetch_object($query1Res))!= false){
			 //echo "...<br>";
			 //$nrows1++;
			$query1Res_shift = $rowQuery1Res->SHIFT;
		}
		
		
		// echo $nrows1;
		// echo "<br>";
        $query2="SELECT inv_unit.id FROM inv_unit 
		INNER JOIN ref_bizunit_scoped ON inv_unit.line_op = ref_bizunit_scoped.gkey  
		WHERE inv_unit.gkey='$unitGKey'";
		//echo "<br>";
		$stm2=oci_parse($con_sparcsn4_oracle,$query2);
		oci_execute( $stm2);
		
		
		
		$result2=array();
		$nrows2 = oci_fetch_all($stm2, $result2, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		oci_free_statement($stm1);
		
		
		//$nrows2 = oci_num_rows($stm2);
		
		
		//oci_free_statement($stm2);
		// echo $nrows2."....."."<br>";
		if($nrows1>0 && $nrows2>0){
		$carrierPosition="";
		// -- AND srv_event_field_changes.new_value !=''
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
		// echo "<br>";
		$carrierPositionStm=oci_parse($con_sparcsn4_oracle,$carrierPositionquery);
		oci_execute( $carrierPositionStm);
		//$carrierPositionRes=oci_fetch_object($carrierPositionStm);
		$result3=array();
		$nrows3= oci_fetch_all($carrierPositionStm, $result3, null, null, OCI_FETCHSTATEMENT_BY_ROW);		
		oci_free_statement($carrierPositionStm);
		
		// $carrierPositionStm=oci_parse($con_sparcsn4_oracle,$CarrierPositionquery);
		// oci_execute( $carrierPositionStm);
	
		if($nrows3>0){
			// echo "--".$nrows3."<br>";
			$carrierPositionStm=oci_parse($con_sparcsn4_oracle,$carrierPositionquery);
			oci_execute( $carrierPositionStm);
			while(($carrierPositionRes = oci_fetch_object($carrierPositionStm))!= false){
				$carrierPosition=$carrierPositionRes->CARRENTPOSITION;
			}
			
			//$carrierPosition=$carrierPositionRes->CARRENTPOSITION;
			// echo "first ". $carrierPosition;
		}
		else
		{
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
		// echo "--";
		$queryYardRes=mysqli_query($con_sparcsn4,$queryYard);
		$yardRowsNum=mysqli_num_rows($queryYardRes);
		$yardRow=mysqli_fetch_object($queryYardRes);
		$yardName=$yardRow->Yard_No;
		// echo "<br>";
		

		
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
		//if($yard_no=="GCB")
		//{
			if($i==$numRows)
				$allCont .=$row->cont_no;
			else
				$allCont .=$row->cont_no.", ";
		//}
		$sqlIsoCode=mysqli_query($con_cchaportdb,"select cont_iso_type from igm_detail_container where cont_number='$row->cont_no'");
		
	
		$iso = "";
		while($rtnIsoCode=mysqli_fetch_object($sqlIsoCode))
		{
			$iso=$rtnIsoCode->cont_iso_type;
		}
		
		$b40 = "";
		$c40 = "";

	

		if(substr($iso,0,1)==2)
			$j20=$j20+1;
		else
			$j40=$j40+1;
		
		if(substr($iso,0,1)==2)
		{
			if($query1Res_shift=="Shift A")
				$a20 = $a20+1;
			if($query1Res_shift=="Shift B")
				$b20 = $b20+1;
			if($query1Res_shift=="Shift C")
				$c20 = $c20+1;
		}
		else
		{
			if($query1Res_shift=="Shift A")
				$a40 = $a40+1;
			if($query1Res_shift=="Shift B")
				$b40 = $b40+1;
			if($query1Res_shift=="Shift C")
				$c40 = $c40+1;
		}
			
	
	
		if($yard_no!=$yardRow->Yard_No)
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
		if($shift!=$query1Res_shift)
		{	
			$shift=$query1Res_shift;		
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
				<td colspan="15"><b><?php  echo $query1Res_shift;?></b></td>
			</tr>	
			<?php	
			$i=1;
		}
		$shift=$row->shift;	
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
	mysqli_close($con_cchaportdb);
	?>
		<tr>
			<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>			
		</tr>
		<?php 
		//if($yard_no=="GCB")
		//{
		?>
		<tr>
			<td colspan="15"><?php echo $allCont;?></td>
		</tr>
		<!--tr>
			
			<td colspan="15" align="center" >
			<form action="<?php echo site_url('Report/strippingPreparation/');?>" method="post">
				<input type="hidden" name="fromdate" value="<?php echo $fromdate; ?>">
				<button>StrippingPreparation</button>
			</form>
			<form action="<?php echo site_url('Report/stripping/');?>" method="post">
				<input type="hidden" name="fromdate" value="<?php echo $fromdate; ?>">
				<button>Stripping</button>
			</form>
			
			</td>
		</tr-->
		<?php 
		//}
		?>
	</table>
	<br/>
	<table border="1" align="center" style="border-collapse: collapse;">
		<thead>
			<tr>
				<th rowspan="2">UNIT/YARD</th>
				<th rowspan="2">SHIFT</th>
				<th colspan="3">TOTAL ASSIGNMENT</th>
				<th colspan="3">STRIPPED</th>
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
				<td><a href="<?php echo site_url('Report/shiftWiseContainerReport/A/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $a20+$a40;?></a></td>
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
				<td><a href="<?php echo site_url('Report/shiftWiseContainerReport/N/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $balA20+$balA40;?></td>
				<td width="150"></td>
			</tr>
			<tr align="center">
				<td><?php echo $yard_no; ?></td>
				<td>B</td>
				<td><?php echo $balA20;?></td>
				<td><?php echo $balA40;?></td>
				<td><?php echo $balA20+$balA40;?></td>
				<td><?php echo $b20;?></td>
				<td><?php if(isset($b40)) echo $b40;?></td>
				<td><a  href="<?php echo site_url('Report/shiftWiseContainerReport/B/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php if(isset($b20) and isset($b40)) echo $b20+$b40;?></a></td>
				<td>
					<?php 
						$balB20 = $balA20-$b20;
						echo $balB20;
					?>
				</td>
				<td>
					<?php 
						if(isset($b40))
						{
							$balB40 = $balA40-$b40;
							echo $balB40;
						}
						else
							$balB40="";
					?>
				</td>
				<td><a href="<?php echo site_url('Report/shiftWiseContainerReport/N/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $balB20+$balB40;?></td>
				<td></td>
			</tr>
			<tr align="center">
				<td><?php echo $yard_no; ?></td>
				<td>C</td>
				<td><?php echo $balB20;?></td>
				<td><?php echo $balB40;?></td>
				<td><?php echo $balB20+$balB40;?></td>
				<td><?php echo $c20;?></td>
				<td><?php if(isset($c40)) echo $c40; ?></td>
				<td><a  href="<?php echo site_url('Report/shiftWiseContainerReport/C/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php if(isset($c20) and isset($c40)) echo $c20+$c40;?></a></td>
				<td>
					<?php 
						$balC20 = $balB20-$c20;
						echo $balC20;
					?>
				</td>
				<td>
					<?php 
						if(isset($c40))
						{
							$balC40 = $balB40-$c40;
							echo $balC40;
						}
						else
							$balC40="";
					?>
				</td>
				<td><a href="<?php echo site_url('Report/shiftWiseContainerReport/N/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $balC20+$balC40;?></td>
			    <td><a href="<?php echo site_url('Report/shiftWiseContainerReport/Stay/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo "Stayed: ". $stayed; ?></a></td>
			</tr>
		</tbody>
	</table>
	
	<form action="<?php echo site_url('Report/pendingEmptyContinerReport');?>"  method="POST" target="_blank">
	  <input type="hidden"  id="fromdate" name="fromdate" value="<?php echo $fromdate?>"> 
	  <input type="hidden"  id="yard_no" name="yard_no" value="<?php echo $yard_no?>"> 
	  <input type="submit" value="Pending Deliver" name="deliver" class="login_button">	
	</form>
	
	
	
	<?php 
	mysqli_close($con_sparcsn4);
	if($_POST['fileOptions']=='html'){?>	
		</BODY>
	</HTML>
<?php }?>
