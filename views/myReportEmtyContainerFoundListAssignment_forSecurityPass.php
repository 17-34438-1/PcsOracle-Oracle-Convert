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
		header("Content-Disposition: attachment; filename=EXPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	

	include("dbConection.php");
	include("dbOracleConnection.php");
	
	
	
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
	
	if($this->session->userdata('login_id')=="pass")
	{
		//$substr1=" AND config_metafield_lov.mfdch_value!='CANCEL'";
		$substr1=" AND mfdch_value!='CANCEL'";
	//	$substr2=" order by sl,Yard_No,shift,proEmtyDate";
         $substr2=" order by Yard_No ";
		
	}
	else
	{
		//$substr1="AND config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')";
		$substr1=" AND mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')";
		if($yard_no=="all")
		{
			//$substr2=" order by sl,Yard_No,shift,proEmtyDate";
			$substr2=" order by Yard_No ";
		}
		else
		{
			//$substr2=" where Yard_No='$yard_no' order by sl,Yard_No,shift,proEmtyDate";
			$substr2=" AND Yard_No='$yard_no'  order by Yard_No ";
		}
		
	}
	
	//echo $substr1.'<br>'.$substr2;
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
			a.slot AS carrentPosition,
			a.unit_gkey,
			a.goods,
			a.Yard_No,
			a.mlo AS mlo,
			a.cf_name AS cf,
			a.mfdch_desc,
			a.cont_status AS statu,
			(SELECT ctmsmis.mis_exp_unit_load_failed.last_update
			FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey=a.unit_gkey) AS last_update,
			(SELECT ctmsmis.mis_exp_unit_load_failed.user_id
			FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey=a.unit_gkey) AS user_id,
			a.Yard_No AS Yard_No,
			
			a.Block_No AS Block_No, IF(UCASE(a.stay) LIKE '%STAY%',1,0) AS stay
			
			FROM ctmsmis.tmp_oracle_assignment a
			WHERE a.assignmentDate BETWEEN '$fromdate' AND '$fromdate'  $substr1  $substr2
			";
		
	
	$query=mysqli_query($con_sparcsn4,$str);

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
	include("mydbPConnection.php");
	$numRows=mysqli_num_rows($query);
	
	while($row=mysqli_fetch_object($query))
	{
		$unitGKey="";
		$goods="";
		$unitGKey=$row->unit_gkey;
		$goods=$row->goods;

		$query1="SELECT   
		(CASE WHEN b.time_out >= to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS') 
		AND b.time_out < to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') THEN 'Shift A'
		WHEN b.time_out >= to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') 
		AND b.time_out <
		to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift B'
		WHEN b.time_out >= to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
		AND b.time_out < to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift C'
		END) AS shift, (CASE WHEN b.time_out IS NULL THEN 2 ELSE 1 END) AS sl, b.*
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

        $query2="SELECT * FROM inv_goods WHERE inv_goods.gkey='$goods'";

		$stm2=oci_parse($con_sparcsn4_oracle,$query2);
		oci_execute( $stm2);
		$result2=array();
		$nrows2 = oci_fetch_all($stm2, $result2, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		oci_free_statement($stm2);

		$stm2=oci_parse($con_sparcsn4_oracle,$query2);
		oci_execute( $stm2);
		$query2Res=oci_fetch_object($stm2);

		if($nrows1>0 && $nrows2>0){


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
		$query2Res=oci_fetch_object($stm4);

		$i++;
		$stayed=$stayed+$row->stay;
		
			if($i==$numRows)
				$allCont .=$row->cont_no;
			else
				$allCont .=$row->cont_no.", ";
	
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
	
	
		if($yard!=$row->Yard_No)
		{
			$yard=$row->Yard_No;
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
				<td colspan="15"><b><?php  echo $row->Yard_No;?></b></td>
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
			<td><?php if($$query1Res->FLEX_STRING10) echo $$query1Res->FLEX_STRING10; else echo "&nbsp;";?></td>
			<td><?php if($iso) echo $iso; else echo "&nbsp;";?></td>
			<td><?php if($row->mlo) echo $row->mlo; else echo "&nbsp;";?></td>
			<td><?php if($row->statu) echo $row->statu; else echo "&nbsp;";?></td>
			<td><?php if($row->mfdch_desc) echo $row->mfdch_desc; else echo "&nbsp;";?></td>
			<td><a href="<?php echo site_url('Report/assignmentCNFContainerList/'.$query2Res->CONSIGNEE_BZU.'/'.$fromdate);?>" target="_blank"><?php if($row->cf) echo $row->cf; else echo "&nbsp;";?></td>		
			<td><?php if($row->weight) echo $row->weight; else echo "&nbsp;";?></td>
			<td><?php if($row->carrentPosition) echo $row->carrentPosition; else echo "&nbsp;";?></td>
			<!--td><?php if($query1Res->TIME_IN) echo $query1Res->TIME_IN; else echo "&nbsp;";?></td-->
			<td><?php if($row->assignmentdate) echo $row->assignmentdate; else echo "&nbsp;";?></td>
			<td><?php if($query1Res->TIME_OUT) echo $query1Res->TIME_OUT; else echo "&nbsp;";?></td>
			<td><?php if($query4Res->STRIPPED_BY) echo $query4Res->STRIPPED_BY; else echo "&nbsp;";?></td>
			<td><?php if (($row->last_update!="") or  ($row->last_update!=null)) echo "Tried to Exp. Load:".$row->last_update." by ".$row->user_id; else echo "&nbsp;";?></td>
		</tr>
	<?php
	} 
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
