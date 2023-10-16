


<div align ="center" style="margin:100px;">
	<table width="100%" style="border-collapse: collapse;" align="center">
	  <thead>
		
      <tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="5">
            <img align="center" width="160px" style="margin:0px;padding:0px;" height="90px" src="<?php echo ASSETS_PATH?>images/cpa_logo.png">
            </th>
	</tr>
    <tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="5"><font size="6"><b>Chattogram Port Authority</b></font></th>
	</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="5"><font size="6"><b>EDO Verification Report (Organization Wise Summary)</b></font></th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="5"><font size="6"><b>From Date : <?php echo $from_date; ?> To Date : <?php echo $to_date; ?></b></font></th>
		</tr>
		<tr>			
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">#Sl</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Organization</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Total</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Approve</th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Not Approve</th>
			
			
			
		</tr>
		</thead>		
		
		<?php for($i=0; $i < count($edoVerificationList); $i++) { ?>
				<tr border ='1' cellpadding='0' cellspacing='0' style="font-size:12px;  border-collapse: collapse;">
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
					<?php echo $i+1;?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="left">
					<?php echo $edoVerificationList[$i]['org_name'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
					<?php echo $edoVerificationList[$i]['tot'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
					<?php echo $edoVerificationList[$i]['approve'];?>
						
					</td>
                    <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
					<?php echo $edoVerificationList[$i]['notApprove'];?>
						
					</td>
                    
					
				</tr>
		<?php } ?>
        
        <tr bgcolor="#ffffff" height="50px">
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;"align="center" colspan="2"><font size="5"><b>Total</b></font></td>
            <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;"align="center" colspan="1"><font size="15px">
                <?php
                  $total=0;
                 for($i=0; $i < count($edoVerificationList); $i++){
                    
                    $total =$total+$edoVerificationList[$i]['tot'];
                 }
                 echo $total;

                ?>

            </font></td>
            <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;"align="center" colspan="1"><font size="15px">
            <?php
                  $approve=0;
                 for($i=0; $i < count($edoVerificationList); $i++){
                    
                    $approve= $approve+$edoVerificationList[$i]['approve'];
                 }
                 echo $approve;

                ?>
            </font></td>
            <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;"align="center" colspan="1"><font size="15px">
            <?php
                $notApprove=0;
                 for($i=0; $i < count($edoVerificationList); $i++){
                    
                   $notApprove=$notApprove+$edoVerificationList[$i]['notApprove'];
                 }
                 echo $notApprove;

                ?>
            </font></td>
            
		</tr>				
	</table>
</div>