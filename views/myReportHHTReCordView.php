<html>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0' style="border-spacing:0px;">
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="4" align="center">
			<table border=0 width="100%">
				<tr align="center">
					<td align="center" valign="middle" colspan="12"><img align="middle" src="<?php echo IMG_PATH?>cpa_logo.png"></td>
				</tr>
				<tr align="center">
					<td align="center" valign="middle" ><font size="4"><b><nobr>CHITTAGONG PORT AUTHORITY,CHITTAGONG</nobr></b></font></td>
				</tr>
			
				<tr align="center">
					<td align="center" valign="middle"colspan="12"><font size="4"><b><u>BLOCK WISE ASSIGNED EQUIPMENT LIST</u></b></font></td>
				</tr>
				<tr align="center">
					<td align="center" valign="middle" colspan="12"><font size="4"><b></b></font></td>
				</tr>

				

			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="4" align="center"></td>		
	</tr>
	</table>
	<table width="50%" border ='1' cellpadding='0' cellspacing='0' align="center" style="border-spacing:0px;">
		<thead>
			<tr bgcolor="#A9A9A9" align="center" height="25px" >
				<td align="center"><b>SLNO</b></td>
				<td align="center"><b>Yard</b></td>
				<td align="center"><b>Block</b></td>	
				<td align="center"><b>Equipment</b></td>		
			</tr>
		</thead>
		<tbody>

<?php
	include("dbConection.php");
	include("dbOracleConnection.php");	
	
	$query=oci_parse($con_sparcsn4_oracle,"
	    select * from(
		select distinct  sel_block Block,
		short_name equipement
		from xps_che
		inner join xps_chezone on xps_chezone.che_id=xps_che.id
		
		order by Block 
		) tm where  (equipement <> '')");
		oci_execute($query);

	$i=0;
	$j=0;

	
	$Yard_No="";
	$row="";
	while(($row=oci_fetch_object($query)) !=false){
		$block="";
		$block=$row->BLOCK;
		$sqlStr="SELECT ctmsmis.cont_yard($block) AS Yard_No";
		$query1=mysqli_query($con_sparcsn4,$sqlStr);
		$row1=mysqli_fetch_object($query1);
	if($row1->Yard_No !=null){
     if($Yard_No!=$row1->Yard_No){
		if($j>0){
		?>
		<tr   bgcolor="#aaffff" valign="center">

				<td  colspan="2"><font size="4"><b>&nbsp;&nbsp;Total (<?php echo $Yard_No; ?>):</b></font></td>
				<td  colspan="2">&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td>

		</tr>
		<?php
		}
		?>
		<tr   bgcolor="#F0F4CA" valign="center">
			<td  colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="4" color="blue"><b><?php  if($row1->Yard_No) echo $row1->Yard_No; else echo "&nbsp;"; ?></b></font></td>

		</tr>
		<?php
		
		
		$j=1;
		$i=1;
		
	}else{
		$j++;
		$i++;
	}
	$Yard_No=$row1->Yard_No;
	
?>
<tr align="center" >
		<td align="center"><?php  echo $i;?></td>
		<td align="center"><?php if($row1->Yard_No) echo $row1->Yard_No; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->BLOCK) echo $row->BLOCK; else echo "&nbsp;";?></td>
		
		<td align="center"><?php if($row->EQUIPMENT) echo $row->EQUIPMENT; else echo "&nbsp;";?></td>
</tr>

<?php 
}
 } ?>

<tr  bgcolor="#aaffff" valign="center"><td colspan="2"><font size="4"><b>&nbsp;&nbsp;Total  (<?php echo $Yard_No; ?>):</b></font></td><td  colspan="2">&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td></tr>
</tbody>
</table>
<br/><br/>
			<table border="2" width="50%" align="center" style="border-spacing:0px;">
				
				<tr align="center" >
					<td colspan="4"><font size="4"><b>SUMMARY OF EQUIPMENT DETAILS</b></font></td>
				</tr>
			</table>
<table width="50%" border ='1' cellpadding='0' cellspacing='0' align="center" style="border-spacing:0px;">
	<tr bgcolor="#A9A9A9" align="center" height="25px">
		
	
		<td align="center"><b>SLNO.</b></td>
		<td align="center"><b>Equipment.</b></td>
		<td align="center"><b>Total.</b></td>
		
	</tr>

<?php
	include("dbConection.php");
	
	$query2=oci_parse($con_sparcsn4_oracle,"
	    select tt,count(equipement) as total from(
		select distinct short_name equipement,
		Replace(Replace(Replace(Replace(Replace(Replace(Replace(Replace(Replace(Replace(short_name,'9',''),'8',''),'7',''),'6',''),'5',''),'4',''),'3',''),'2',''),'1',''),'0','') as tt
		from xps_che
		inner join xps_chezone on xps_chezone.che_id=xps_che.id
		) tm where (equipement <> '') and tt not in('F','HHT') group by tt");
		oci_execute($query2);
	$i=0;
	$j=0;

	
	
	while(($row2=oci_fetch_object($query2)) !=false){
	$i++;
?>
<tr align="center">
		<td align="center"><?php  echo $i;?></td>
		<td align="center"><?php if($row2->TT) echo $row2->TT; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row2->TOTAL) echo $row2->TOTAL; else echo "&nbsp;";?></td>
</tr>

<?php } ?>



</table>
<br/>
<br/>
<!-- <img src="<?php echo IMG_RESOURCE_PATH; ?>containerloc.png" width="1050" height="500" alt="" align="left" style="padding-left:40px;"> -->
<?php 
	mysqli_close($con_sparcsn4);
?>	
	</body>
</html>
