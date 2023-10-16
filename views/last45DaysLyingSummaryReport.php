<?php 
if($options=='html')
{
?>
<HTML>
    <HEAD>
        <TITLE>Lying Food Items Summary</TITLE>
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
	header("Content-Disposition: attachment; filename=Last 45 days Lying Summary.xls;");
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
							<td colspan="12"><font size="4"><b><u>Lying Food Items Report</u></b></font></td>
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
		<table border="1" align="center">
			<h4 align="center"><span><b>Lying Food Items Summary</b> Date :  <?php echo $date;?></span></h4>
			<tr>
				<th>S/L No</th>
				<th>Item</th>
				<th>20</th>
				<th>40</th>
				<th>Teus</th>
				<th>Mton</th>
			</tr>
			<?php
				$c20 =0;
				$c40I = 0;
				$teus  = 0;
				$mton = 0;
				
				for($i=0;$i<count($goodsResult);$i++)
				{
			?>
			<tr>
				<td><?php echo $i+1 ?></td>
				<td><?php echo $goodsResult[$i]['goodsName'] ?></td>
				<td><?php echo $goodsResult[$i]['c20']; ?></td>
				<td><?php echo $goodsResult[$i]['c40I']; ?></td>
				<td><?php echo $goodsResult[$i]['teus']; ?></td>
				<td><?php echo $goodsResult[$i]['mton']; ?></td>
			</tr>
			<?php
					$c20 = $c20 + $goodsResult[$i]['c20'];
					$c40I = $c40I + $goodsResult[$i]['c40I'];
					$teus = $teus + $goodsResult[$i]['teus'];
					$mton = $mton + $goodsResult[$i]['mton'];
				}
			?>
			<tr>
				<td></td>
				<td align="center"><b>Total</b></td>
				<td align="center"><b><?php echo $c20; ?></b></td>
				<td align="center"><b><?php echo $c40I; ?></b></td>
				<td align="center"><b><?php echo $teus; ?></b></td>
				<td align="center"><b><?php echo $mton; ?></b></td>
			</tr>
		</table>
		<br><br>
<?php 
if($options=='html')
{?>    
    </BODY>
</HTML>
<?php 
}
?>