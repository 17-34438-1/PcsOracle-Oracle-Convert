<?php 
for($j=0;$j<$count;$j++)   //in live, code may change as all configuration is not present in local
{ 
?>
<html>
<head>
		<hr>
		<div align="center" style="font-size:18px">
			<title>CHITTAGONG PORT AUTHORITY</title>
		</div>
		<div align="center"></div>

</head>
<body>	
		<div align ="center">
		<table align="center" width="80%" style="font-size:12px">
				
				<tr align="center" >
					<td  align="center"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>

			</table>
			
			<div align="center"><b><font size=3><b>Invoice / Challan</b></font></b></div>

		<table align="center" width="80%" style="font-size:12px">
			<tr style="border-bottom:1px solid black">
				<td><b><font size=3>Verify No : <?php echo $verifyNo;?></font></b></td>

			</tr>			
		</table>
		<!--hr-->

		
		
		<table align="center" width="80%" border="1" style="font-size:12px;  border-collapse: collapse;">
		 <tr>
		   <th rowspan="2"> C&F Detail </th>
		   <th> Name </th>
		   <td><?php echo $cnfName;?></td>	   
		 </tr>
		 
		  <tr>
		   <th> Address</th>
			<td><?php echo $cnfAddress1;?></td>
         </tr>
		 
		 <tr>
		   <th rowspan="2"> Importer Detail</th>
		   <th> Name </th>
		   <td><?php echo $notifyName;?></td>
		 </tr>
         <tr>		  
		   <th> Address</th>
		   <td><?php echo $notifyAddress;?></td>
		 </tr>
		 
		</table>
		
		

		<!--hr-->
		<br>

		<!--div align="center"><font size=4>Description</font></div-->
			<table  align="center" width=80% border="1" style="font-size:12px; border-collapse: collapse;" > 
				<thead style="">
					<tr >		
						<th align="center" >TRUCK NO</th>
						<th align="center" >DESCRIPTION OF GOODS</th>
						<th align="center" >QUANTITY</th>
                        <th align="center" >REMARKS</th>						
					</tr>
				</thead>
				<tbody>
				 <?php       
				for($i=0;$i<count($result3);$i++) { 
				 ?>
				 <tr class="" > 
				  
				  <td align="center">
				   <?php echo $result3[$i]['truck_id']?>
				  </td>
				  <td align="left">
				   <?php echo $goodsDes?>
				  </td>
				  <td align="center">
				   <?php echo $result3[$i]['delv_pack']?>
				  </td>
				  <td align="center">
				   <?php echo $result3[$i]['remarks']?>
				  </td>
				 

				</tr>
				 <?php
				}
			   ?>
			</tbody>
			</table>
		</div>
	
</body>
</html>
<?php 
}
?>
<script>
		window.print();
</script>
