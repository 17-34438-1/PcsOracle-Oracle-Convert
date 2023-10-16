
<html>
<title>Lying Container by Rotation</title>

	<table align="center" width="95%">
	  <thead>
		<th align="center" >
			<h2 align="center"><img align="middle"  width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"></h2>
			<h3 align="center">(Removal Tally Book)</h3>
			<h3 align="center"><b>COPY TO BE RETURNED TO PARENT SHED</b></h3>
		</th>

		<tr bgcolor="#ffffff" >
			<!--th  align="left"><font size="4"><b><?php echo $title; ?></b></font></td-->
			<td align="center"><font align="center" size="4"><b><?php echo "ROT: ".$rotation; ?>
			&nbsp;&nbsp;&nbsp;&nbsp; ARRIVAL DATE: <?php echo $result[0]['ATA']; ?>
			&nbsp;&nbsp;&nbsp;&nbsp; C/L DATE: <?php echo $landingDt; ?></b></font></td>
			<!--th colspan="3" align="center"><font size="5"><b>File No: </b></font></td>
			<th colspan="3" ><font size="5"><b>Duty Hours:</b></font></td>
			<th colspan="2" align="right"><font size="5"><b><?php echo "Date:  ". $date; ?></b></font></td-->
		</tr>
		<tr>
			<td align="center"><b>The cargo of S.S -  <?php echo $result[0]['V_NAME']; ?> &nbsp;&nbsp;&nbsp;Ex. Shed ___________
			transferred to Shed No. ____/AUCTION  in wagon No. <u>By paper</u></b>
			</td>	
		</tr>
		</table>
		<table class="table table-bordered table-responsive table-hover table-striped mb-none"  width="95%">
		
		<thead>
			<td align="center"><b>SL.</b></td>
			<td align="center"><b>CONTAINER</b></td>
			<td align="center"><b>SIZE</b></td>
			<td align="center"><b>STATUS</b></td>
			<td align="center"><b>BL NO.</b></td>
			<td align="center"><b>MARKS</b></td>
			<td align="center"><b>GOODS DESCRIPTION</b></td>
			<td align="center"><b>IMPORTER</b></td>
			<td align="center"><b>POSITION</b></td>
			<td align="center"><b>RL NO</b></td>
			<td align="center"><b>RL DATE</b></td>
			<td align="center"><b>OBPC NO</b></td>
			<td align="center"><b>OBPC DATE</b></td>
			<td align="center"><b>REMARKS</b></td>

		
		</thead>
		

		<?php
			//	include_once("mydbPConnection.php");
			for($i=0;$i<count($result);$i++) { 
			?>
				<tr>
					<td align="center"><?php echo $i+1;?></td>
					<td  align="center"><?php echo $result[$i]['ID']?></td>
					<td  align="center"><?php echo $result[$i]['SIZ']?></td>											

					<?php include("mydbPConnection.php");
					$cont=$result[$i]['ID'];
					$rotation=$result[$i]['ROT_NO'];
	$query1="SELECT BL_No,Description_of_Goods,Pack_Marks_Number, igm_details.BL_No, igm_detail_container.cont_status,
			igm_detail_container.cont_size, igm_details.Notify_name, igm_detail_container.cont_imo,igm_detail_container.cont_un 
			FROM cchaportdb.igm_details INNER JOIN cchaportdb.igm_detail_container ON cchaportdb.igm_detail_container.igm_detail_id=cchaportdb.igm_details.id
			WHERE cchaportdb.igm_details.Import_Rotation_No='$rotation'
			AND cchaportdb.igm_detail_container.cont_number='$cont'";
 
	                $str1=mysqli_query($con_cchaportdb,$query1);
	                $row2=mysqli_fetch_object($str1); ?>
					<td align="center"><?php  echo $row2->cont_status; ?></td>
					<td align="center"><?php  echo $row2->BL_No; ?></td>
					<td align="center"><?php  echo $row2->Pack_Marks_Number; ?></td>
					<td align="center"><?php  echo $row2->Description_of_Goods; ?></td>
					<td align="center"><?php  echo $row2->Notify_name; ?></td>
					<td  align="center"><?php echo $result[$i]['LAST_POS_SLOT']?></td>											
					<td  align="center"><?php echo $result[$i]['RL_NO']?></td>											
					<td  align="center"><?php echo $result[$i]['RL_DATE']?></td>											
					<td  align="center"><?php echo $result[$i]['OBPC_NUMBER']?></td>											
					<td  align="center"><?php echo $result[$i]['OBPC_DATE']?></td>											
					<td align="center"><?php   ?></td>
						
				</tr>
			<?php }
			mysqli_close($con_cchaportdb);
			
		?>
		<!--tr><td colspan='5' border='0'>Total Container :</td></tr-->
	
	</table>
	<table align="center" width="95%">
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Date ________________Posted &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Manifest Clerk</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tally Clerk</td>
	</tr>
	</table>
<?php
?>
<body>

<!--script>
		window.print();
</script-->
</html>
	

	
		


