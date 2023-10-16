<HTML>
  <head>
    <table width="100%" cellpadding="0">
	   <tr height="100px">
			<td align="center" valign="middle">
				 <h1>Chittagong Port Authority </h1>
				 <h3>Discharge List <?php echo " From: ".$fromDate." To: ".$toDate;?></h3>
			</td>
	   </tr>
     </table>
 </head>
 <body>
     <table border="1" align="center" cellspacing="1" cellpadding="1" style="border-collapse: collapse;">
		  <tr>
			   <th>Sl No.</th>     
			   <th>CONTAINER</th>       
			   <th>CONT. SIZE</th>       
			   <th>CONT. HEIGHT</th>       
			   <th>STATUS</th>       
			   <th>DISCHARGE TIME</th>
			   <th>DELIVERY TIME</th>
		  </tr>
       <?php      
        for($i=0;$i<count($getList);$i++) { 
         ?>
         <tr>
			  <td>
					<?php echo $i+1;?>
			  </td>
			  <td align="center">
					<?php echo $getList[$i]['ID']?>
			  </td>
			  <td align="center">
					<?php echo $getList[$i]['SIZ']?>
			  </td>
			  <td align="center">
					<?php echo $getList[$i]['HEIGHT']?>
			  </td>
			  <td align="center">
					<?php echo $getList[$i]['FREIGHT_KIND']?>
			  </td>
			  <td align="center">
					<?php echo $getList[$i]['TIME_IN']?>
			  </td>   		  
			  <td align="center">
					<?php echo $getList[$i]['TIME_OUT']?>
			  </td>                  
         </tr>
         <?php
        }
       ?>
     </table>
   </body>
</html>