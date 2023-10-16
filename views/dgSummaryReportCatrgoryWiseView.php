<?php //if($_POST['fileOptions']=='html'){?>
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

	<?php /*  } 
	else if($_POST['fileOptions']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=EXPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	} */


	
	/* $sql=mysql_query("select vvd_gkey from sparcsn4.vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	$row=mysql_fetch_object($sql);
	$vvdGkey=$row->vvd_gkey;
	
	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysql_query($sql); */
	
	if($cat=="laden20")
	{
		$size = "cont_size = 20";	
		$type_of_igm = " igm_details.type_of_igm<>'TS' ";		
		$head = "Date Wise Delivery Import Container List";
		$offdock="off_dock_id <>'2592'";
		$iso=" AND cont_iso_type NOT IN('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') ";
	}
	
	elseif($cat=="laden40")
	{
		$size = "cont_size = 40";
		$type_of_igm = " igm_details.type_of_igm<>'TS' ";		
		$head = "Assignment Date Wise Import Container List";
		$offdock="off_dock_id <>'2592'";
		$iso=" AND cont_iso_type NOT IN('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') ";
		$cont_status="";

	}
	elseif($cat=="reffer20")
	{
		$size = "cont_size = 20";
		$type_of_igm = " igm_details.type_of_igm<>'TS' ";	
		
		$head = "Assignment Date Wise Import Container List";
		
		$offdock="off_dock_id <>'2592'";
		$iso=" AND cont_iso_type like '%R%' and cont_iso_type not in ('DRY')";
		$cont_status="";
	}
	elseif($cat=="reffer40")
	{
		$size = "cont_size = 40";
		$type_of_igm = " igm_details.type_of_igm<>'TS' ";		
		$head = "Assignment Date Wise Import Container List";
		$offdock="off_dock_id <>'2592'";
		$iso=" AND cont_iso_type like '%R%' and cont_iso_type not in ('DRY')";
		$cont_status="";
	}
	elseif($cat=="imdg20")
	{
		$size = "cont_size = 20";
		$type_of_igm = " igm_details.type_of_igm<>'TS' ";			
		$head = "Assignment Date Wise Import Container List";
		$offdock="off_dock_id <>'2592'";
		$iso=" AND cont_iso_type NOT IN('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') ";
		$cont_status="";
	}
	elseif($cat=="imdg40")
	{
		$size = "cont_size = 40";
		$type_of_igm = " igm_details.type_of_igm<>'TS' ";			
		$head = "Assignment Date Wise Import Container List";
		$offdock="off_dock_id <>'2592'";
		$iso=" AND cont_iso_type NOT IN('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') ";
		$cont_status="";
	}
	elseif($cat=="trans20")
	{
		$size = "cont_size = 20";
		$type_of_igm = " igm_details.type_of_igm='TS' ";		
		$head = "Assignment Date Wise Import Container List";
		$offdock="off_dock_id <>'2592'";
		$iso=" AND cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')  ";
		$cont_status="";
	}
	elseif($cat=="trans40")
	{
		$size = "cont_size = 40";
		$type_of_igm = " igm_details.type_of_igm = 'TS' ";		
		$head = "Assignment Date Wise Import Container List";
		$offdock="off_dock_id <>'2592'";
		$iso=" AND cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')  ";
		$cont_status="";
	}
	elseif($cat=="icd20")
	{
		$size = "cont_size = 20";
		$type_of_igm = " igm_details.type_of_igm<>'TS' ";		
		$head = "Assignment Date Wise Import Container List";
		$offdock="off_dock_id = '2592'";
		$iso=" AND cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')  ";
		$cont_status="";
	}
	elseif($cat=="icd40")
	{
		$size = "cont_size = 40";
		$type_of_igm = " igm_details.type_of_igm<>'TS' ";		
		$offdock="off_dock_id = '2592'";
		$head = "Assignment Date Wise Import Container List";
		$cont_status="";
	}
	elseif($cat=="ld45")
	{
		$size = "cont_size > 40";
		$type_of_igm = " igm_details.type_of_igm<>'TS' ";	
		$offdock="off_dock_id <>'2592'";		
		$head = "Assignment Date Wise Import Container List";
		$iso="";
		$cont_status= " and (cont_status <> 'EMT' and cont_status <> 'Empty' and cont_status <> 'MT' and cont_status <> 'ETY') ";
		
	}
	elseif($cat=="mt45")
	{
		$size = "cont_size > 40";
		$type_of_igm = " igm_details.type_of_igm<>'TS' ";	
		$offdock="off_dock_id <>'2592'";		
		$head = "Assignment Date Wise Import Container List";
		$iso="";
		$cont_status= " and (cont_status='EMT' or cont_status='Empty' or cont_status='MT' or cont_status='ETY') ";
	}
	
	
	
	?>
<html>
<title>DG CONTAINER REPORT </title>
<body>
<table class='table-header' border=0 width="100%">
	<tr>
		<td colspan="19" align="center"><img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
	</tr>
	<tr><td colspan="2" align="center"><h2>DG CONTAINER DISCHARGE SUMMARY</h1></td></tr>
						
	<!--tr>
		<td align="center">Vessel name:<?php print($row_igm_master->Vessel_Name);?></td>					
		<td align="center">Rotation No:&nbsp;&nbsp;<?php print($row_igm_master->Import_Rotation_No);?></td>
	</tr-->
</table>
	<table width="100%" border =1 cellpadding='0' cellspacing='0'>
	<tr align="center">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Size</b></td>
		<td style="border-width:3px;border-style: double;"><b>Height</b></td>
		<td style="border-width:3px;border-style: double;"><b>Type.</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>	
		
	</tr>

<?php
	//echo $type;
		include("mydbPConnection.php");
		
	   $str1="SELECT DISTINCT  cont_number,cont_size,cont_height,cont_type,mlocode  FROM igm_detail_container
		 INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
		WHERE Import_Rotation_No='$rotation' AND (cont_imo <> '' OR imco <> '' OR un <> '')
		  $iso AND $type_of_igm 
		AND $offdock AND cont_status NOT IN ('EMT','EMPTY','MT','ETY') AND $size AND igm_details.final_submit=1

		UNION
		SELECT DISTINCT  cont_number,cont_size,cont_height,cont_type,mlocode  FROM igm_sup_detail_container 
		INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id 
		INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id 
		WHERE igm_details.Import_Rotation_No='$rotation' AND (cont_imo <> '' OR imco <> '' OR un <> '')
		 $iso AND $type_of_igm 
		AND $offdock AND cont_status NOT IN ('EMT','EMPTY','MT','ETY') AND $size AND  igm_details.final_submit=1" ;	
	
	$query=mysqli_query($con_cchaportdb,$str1);

	$i=0;

	
	while($row=mysqli_fetch_object($query)){
	$i++;
	?>
	<tr>
			<td align="center"><?php  echo $i;?></td>
			<td align="center"><?php if($row->cont_number) echo $row->cont_number; else echo "&nbsp;";?></td>
			<td align="center"><?php if($row->cont_size) echo $row->cont_size; else echo "&nbsp;";?></td>
			<td align="center"><?php if($row->cont_height) echo $row->cont_height; else echo "&nbsp;";?></td>
			<td align="center"><?php if($row->cont_type) echo $row->cont_type; else echo "&nbsp;";?></td>
			<td align="center"><?php if($row->mlocode) echo $row->mlocode; else echo "&nbsp;";?></td>
		</tr>
	<?php

	} 
	mysqli_close($con_cchaportdb);
	?>
		
		<?php 
		//if($yard_no=="GCB")
		//{
		?>
		<!--tr>
			<td colspan="15"><?php echo $allCont;?></td>
		</tr-->



	
	<?php 
	//mysql_close($con_sparcsn4);
	//if($_POST['options']=='html'){?>	
		</BODY>
	</HTML>
<?php //}?>
