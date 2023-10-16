<?php if($_POST['fileOptions']=='html'){?>
<HTML>
	<HEAD>
		<TITLE><?php echo $title;?></TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">

        </style>
</HEAD>
<BODY>

	<?php } 
	else if($_POST['fileOptions']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=importDischargeReport.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	?>
    
<html>

<body>

	<table width="100%" style="border-collapse: collapse;" align="center">
	  <thead>
      <?php  if ($_POST['fileOptions']=='html'){ ?>
         	
      <tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="11">
            <img align="center" width="150px" style="margin:0px;padding:0px;" height="80px" src="<?php echo ASSETS_PATH?>images/cpa_logo.png">
            </th>
	</tr>
    <?php } ?>
   
    <tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="11"><font size="6"><b>Chattogram Port Authority</b></font></th>
	</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="11"><font size="6"><b>Import Container Discharge Report & Summary</b></font></th>
		</tr>
		
		<tr>			
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">#Sl</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Container No</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">ISO Type</th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Type</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">MLO</th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Status</th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Weight</th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">POD</th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Yard Position</th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Discharge Date</th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">Remarks</th>
            <th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center">User Id</th>
			
			
			
		</tr>
		</thead>		
		
		<?php for($i=0; $i < count($queryList); $i++) {
			$gkey="";
			$carrentPosition="";
			$userId="";
			$yardNo="";
			$carrentPosition=$queryList[$i]['CARRENTPOSITION'];
			$gkey=$queryList[$i]['GKEY'];

			$userIdStr="SELECT ctmsmis.mis_exp_unit_load_failed.user_id
			FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey='$gkey'";
			$userIdQuery=mysqli_query($con_sparcsn4,$userIdStr);
			$rowUserId=mysqli_fetch_object($userIdQuery);
			$userId=$rowUserId->user_id;

			$yardNoStr="SELECT ctmsmis.cont_yard('$carrentPosition') AS Yard_No";
			$yardNoQuery=mysqli_query($con_sparcsn4,$yardNoStr);
			$rowYardNo=mysqli_fetch_object($yardNoQuery);
			$yardNo=$rowYardNo->Yard_No;
		?>
				<tr border ='1' cellpadding='0' cellspacing='0' style="font-size:12px;  border-collapse: collapse;">
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center" >
					<?php echo $i+1;?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center" >
					<?php echo $queryList[$i]['ID'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center" >
					<?php echo $queryList[$i]['ISO_CODE'];?>
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $queryList[$i]['TYPE'];?>
                    </td>
                    <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $queryList[$i]['MLO'];?>
                    </td>
                    <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
				
                    </td>
                    <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $queryList[$i]['WEIGHT'];?>
                    </td>
                    <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					 </td>
                    <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $yardNo;?>
                    </td>
                    <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $queryList[$i]['DISCHARGETIME'];?>
                    </td>
                    <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">

					</td>
                   
                    <td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" align="center">
					<?php echo $userId;?>
					</td>
                    
					
				</tr>
		<?php } ?>
        
       
	</table>


<div align ="center" style="margin:100px;">
	<table width="100%" style="border-collapse: collapse;" align="center">
	
		
     <thead>
   
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="10"><font size="6"><b>Import Summary Report</b></font></th>
		</tr>
		
		<tr>			
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="6" >Total Discharge</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="4" >Balance TO Discharge </th>
			
			
		</tr>
			
		<tr>			
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" >LADEN</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" >EMPTY </th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" >TUES</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" >LADEN</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="2" >EMPTY</th>
			
			
		</tr>
		<tr>			
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" >20</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" >40 </th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" >20</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" >40</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" >LD</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" >MT</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" >20</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" >40 </th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" >20</th>
			<th style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" >40</th>
			
		</tr>
	</thead>
		<tr>			
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" ><?php echo $countLadenTwenty;?></td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" ><?php echo $countLadenFourty;?> </td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" ><?php echo $countEmptTwenty;?></td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" ><?php echo $countEmptFourty;?></td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" ><?php echo $tuesLD;?></td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" ><?php echo $tuesMT;?></td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" ><?php echo $disCountLadenTwenty;?></td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" ><?php echo $disCountLadenFourty;?> </td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" ><?php echo $disCountEmptTwenty;?></td>
			<td style="border:1px solid black; font-size:15px;  border-collapse: collapse;" align="center" class="text-center" colspan="1" ><?php echo $disCountEmptFourty;?></td>
			
			
		</tr>
		
				
	
        
       
	</table>
</div>

<br />
<br />
<?php 
if($_POST['fileOptions']=='html'){?>	
	</BODY>
</HTML>
<?php } ?>