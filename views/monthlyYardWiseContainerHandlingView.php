
<html>
<head>
		<div align="center" style="font-size:18px">
			<title>CHITTAGONG PORT AUTHORITY</title>
		</div>
		<div align="center"></div>
		<div align="center"><img align="middle" width="235px" height="75px" src="<?php echo ASSETS_WEB_PATH?>fimg/cpanew.jpg"></div>
		<div align="center"><h3>YARDWISE CONTAINER HANDLING REPORT FROM <?php echo $fromDate." TO ".$toDate; ?></h3></div>

</head>
<body>	
		<div align ="center">
		<table align="center" width="80%" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
		
		<tr>
		  <th rowspan="2">TERMINAL</th>
		  <th colspan="4">IMPORT</th>
		  <th colspan="4">EXPORT</th>
		  <th rowspan="2">PERCHANTAGE</th>
		</tr>
		<tr>
		  <th>20</th>
		  <th>40</th>
		  <th>Total Box</th>
		  <th>Teus</th>
		  
		  <th>20</th>
		  <th>40</th>
		  <th>Total Box</th>
		  <th>Teus</th>
		</tr>
		<?php $total=0; ?>
		<?php for ($i=0; $i<count($getDetails); $i++) {
			 $total=$total+$getDetails[$i]['IMP20']+$getDetails[$i]['IMP40']+$getDetails[$i]['EXP20']+$getDetails[$i]['EXP40'];
		} ?>
		
		<?php for ($i=0; $i<count($getDetails); $i++) {   
		$terminal_total_handling=$getDetails[$i]['IMP20']+$getDetails[$i]['IMP40']+$getDetails[$i]['EXP20']+$getDetails[$i]['EXP40'];?>
		<tr>
		  <td align="center"><?php echo $getDetails[$i]['BERTH']; ?></td>
		  <td align="center"><?php echo $getDetails[$i]['IMP20']; ?></td>
		  <td align="center"><?php echo $getDetails[$i]['IMP40']; ?></td>
		  <td align="center"><?php echo $getDetails[$i]['IMP20']+$getDetails[$i]['IMP40']; ?></td>
		  <td align="center"><?php echo $getDetails[$i]['IMPTEUS']; ?></td>
		  <td align="center"><?php echo $getDetails[$i]['EXP20']; ?></td>
		  <td align="center"><?php echo $getDetails[$i]['EXP40']; ?></td>
		  <td align="center"><?php echo $getDetails[$i]['EXP20']+$getDetails[$i]['EXP40']; ?></td>
		  <td align="center"><?php echo $getDetails[$i]['EXPTEUS']; ?></td>
		  <td align="center"><?php $result =(($terminal_total_handling/$total)*100); 
		                 echo round($result,2)."%";  ?></td>
		  
		</tr>
		<?php }?>
       
		 
		</table>
		
		
		</div>
	
</body>
</html>
