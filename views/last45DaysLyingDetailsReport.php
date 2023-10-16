<?php 
if($options=='html')
{?>
<HTML>
    <HEAD>
        <TITLE>Lying Food Items Details</TITLE>
        <LINK href="../css/report.css" type=text/css rel=stylesheet>
        <style type="text/css">

        </style>
	</HEAD>
	<BODY>

<?php
}
elseif ($options=='xl') 
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Last 45 days Lying Details.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");
}
include('mydbPConnection.php');
?>
<?php 
if($options=='html')
{
?>
		<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
			<tr bgcolor="#ffffff" align="center" height="100px">
				<td colspan="13" align="center">
					<table border=0 width="100%">												
						<tr align="center">
							<td colspan="12"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
						</tr>					
						<tr align="center">
							<td colspan="12"><font size="4"><b><u>Lying Food Items Details</u></b></font></td>
						</tr>
						<tr align="center">
							<td colspan="12"><font size="4"><b></b></font></td>
						</tr>					  
					</table>				
				</td> 	       
			</tr>    
			<tr bgcolor="#ffffff" align="center" height="25px">
				<td colspan="15" align="center"></td>				
			</tr>
		</table>
<?php 
} 
?>
		<table border="1">
			<h4><span><b>Lying Food Items Details</b> Date :  <?php echo $date;?></span></h4>
			<tr>
				<th>S/L No</th>
				<th>Goods Name</th>
				<th>Container Number</th>
				<th>Rotation No</th>
				<th>Container Size</th>
				<th>Goods Description</th>
				<th>Importer Name</th>
				<th>Importer Address</th>
			</tr>
			<?php
			for($i=0;$i<count($goodsResult);$i++)
			{
			?>
			<tr>
				<td><?php echo $i+1 ?></td>
				<td><?php echo $goodsResult[$i]['goodsName'] ?></td>
				<td><?php echo $goodsResult[$i]['cont_number']; ?></td>
				<td><?php echo $goodsResult[$i]['Import_Rotation_No']; ?></td>
				<td><?php echo $goodsResult[$i]['cont_size']; ?></td>
				<td><?php echo $goodsResult[$i]['Description_of_Goods']; ?></td>
				<td><?php echo $goodsResult[$i]['Notify_code']; ?></td>
				<td><?php echo $goodsResult[$i]['Notify_address']; ?></td>
			</tr>
			<?php
			}
			?>
		</table>
		<br><br>
<?php 
if($options=='html')
{
?>    
    </BODY>
</HTML>
<?php 
}
?>