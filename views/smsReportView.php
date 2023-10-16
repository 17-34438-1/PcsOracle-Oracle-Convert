<table width="100%" border="0" style="padding-top:0px;margin-top:0px;padding-bottom:5px;margin-bottom:5px;">
		<tr>
			<td colspan="5" style="padding-top:0px;margin-top:0px;">
				<table align="center" style="padding-top:0px;margin-top:0px;">
					<tr>						
					<td align="center">
						<img align="middle"  height="70px" width="210px" src="<?php echo IMG_PATH?>cpanew.jpg">
						<p style="margin-top:-3px;">www.cpatos.gov.bd</p>
					</td>
					 
					</tr>
				</table>
    </tr>
    <tr>
      <td align="center" style="padding-left:100px">
               Assignment Delivery SMS Report Date: <?php echo $date; ?>
        </td>
     </tr>
</table>
<table align="center" width=80% border="1" style=" border-collapse: collapse;">
      <tr>
          <th>SL </th>
          <th>Conainer </th>
          <th>Sending Time</th>
          
      </tr>

        



        <?php
          for($i=0; $i< count($sms_report) ; $i++) 
          {
        ?>  
            <tr>
              <td><?php echo $i+1; ?></td>
              <td><?php echo $sms_report[$i]['cont_no']; ?></td>
              <td><?php echo $sms_report[$i]['sms_sending_time']; ?></td>
            
          </tr>
        <?php
        }
        ?>
 </table>
<script>
window.print();
</script>
