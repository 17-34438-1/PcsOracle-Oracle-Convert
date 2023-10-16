<?php?>
<HTML>
	<HEAD>
		<TITLE>Assignment Summary</TITLE>
	   	<style>
			body,table{
				background-color:#ecedf0;
			}
		</style>
</HEAD>
<BODY>

	<?php 


	include("dbConection.php");
	

	
	?>
<html>
<title>PROPOSED EMPTY AND EMPTY CONTAINER REPORT</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr  align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr align="center">
					<td colspan="12"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4">Assignment Summary</td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"> C&F: <b><?php echo $cnfName; ?></b></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b>DATE : <?php echo $fromdate; ?>&nbsp;&nbsp;</font></td>
				</tr>
			</table>
		
		</td>
		
	</tr>

	</table>
	<!--table width="70%" border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;" align="center" >
		<tr align="center">
			<td style="border-width:3px;"><b>SlNo.</b></td>
			<td style="border-width:3px;"><b>Container No.</b></td>
			<td style="border-width:3px;"><b> C&F </b></td>
			<td style="border-width:3px;"><b> Assignment Type </b></td>
			
		</tr-->

<?php
	//echo $type;
	$str="SELECT DISTINCT a.gkey,a.id AS cont_no,k.name  AS cnf,
CONCAT(k.address_line1,k.address_line2) AS cnf_addr,
flex_date01,
DATE(b.flex_date01) AS assignDt, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
mfdch_desc 
FROM sparcsn4.inv_unit a
INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods
LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value
WHERE DATE(b.flex_date01) = '$fromdate' AND j.consignee_bzu = '$consignee_bzu' AND config_metafield_lov.mfdch_value!='CANCEL'";
  //echo $str;
	
	/* 
	$query=mysqli_query($con_sparcsn4,$str);

	$i=0;
	$j=0;
	
	$numRows=mysqli_num_rows($query);
	
	while($row=mysqli_fetch_object($query)){
	$i++;
	 */
		?>
		<!--tr>
			
		
			<td><?php  echo $i;?></td>
			<td><?php if($row->cont_no) echo $row->cont_no; else echo "&nbsp;";?></td>
			<td><?php if($row->cnf) echo $row->cnf; else echo "&nbsp;";?></td>
			<td><?php if($row->mfdch_desc) echo $row->mfdch_desc; else echo "&nbsp;";?></td>
			
		</tr>
	<?php
	//} 
	?>
		
		
	</table-->
	
	
	<table width="40%" border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;" align="center" >
		<tr align="center">
			<td style="border-width:3px;"><b>SlNo.</b></td>
			<td style="border-width:3px;"><b>Assignment Type</b></td>
			<td style="border-width:3px;"><b> No. Of Container </b></td>
		</tr>

	<?php
	$str="SELECT cnf,mfdch_desc,COUNT(cont_no) AS cnt FROM (
SELECT DISTINCT a.gkey,a.id AS cont_no,k.name  AS cnf,
CONCAT(k.address_line1,k.address_line2) AS cnf_addr,
flex_date01,
DATE(b.flex_date01) AS assignDt, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
mfdch_desc 
FROM sparcsn4.inv_unit a
INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods
LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value
WHERE DATE(b.flex_date01) = '$fromdate' AND j.consignee_bzu = '$consignee_bzu' AND config_metafield_lov.mfdch_value!='CANCEL'
) AS tbl GROUP BY mfdch_desc";
  //echo $str;
	
	
	$query=mysqli_query($con_sparcsn4,$str);

	$i=0;
	$j=0;
	
	$numRows=mysqli_num_rows($query);
	
	while($row=mysqli_fetch_object($query)){
	$i++;
	
		?>
		<tr>
			
		
			<td align="center"><?php  echo $i;?></td>
			<td align="center"><?php if($row->mfdch_desc) echo $row->mfdch_desc; else echo "&nbsp;";?></td>
			<td align="center"><?php if($row->cnt) echo $row->cnt; else echo "&nbsp;";?></td>
			
		</tr>
	<?php
	} 
	?>
		
		
	</table>
	
	
	
	
	
	<?php 
	mysqli_close($con_sparcsn4);
	?>	
		</BODY>
	</HTML>
