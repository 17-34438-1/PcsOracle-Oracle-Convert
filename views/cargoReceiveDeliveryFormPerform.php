
<?php //if($_POST['fileOptions']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Cargo Receiving & Delivery Report</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<!--?php } 
	else if($_POST['fileOptions']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=Container_Reister_inward.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	} ?--> 

	<table width="95%" align="center">

		<tr height="100px">
			<th align="center" colspan="10">
				<h2><img align="middle"  width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"></h2>
			</th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="10"><font size="5"><b><?php echo $title; ?></b></font></th>
		</tr>
		<!--tr bgcolor="#ffffff" height="50px"  colspan="10">
			<th colspan="5" align="left"><font size="5"><b><?php echo "Gate:  ". $gate;?></b></font></td>
			<th colspan="5" align="right"><font size="5"><b><?php echo "Date:  ". $date; ?></b></font></td>
		</tr-->
		
		<?php 
				$loadin=0;
				$loadout=0;
				$mtyin=0;
				$mtyout=0;
		?>
		
		<tr>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>1</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>2</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>3</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>4</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>5</b></th>
			<th  colspan="2" style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>6</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>7</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>8</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>9</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>10</b></th>

		</tr>
		<tr>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>VESSEL NO.</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>REG NO</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>CONT NO.</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>SEAL NO</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>B\L NO</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>QTY(PKG)</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>TON</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>B/L NO</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>REC.BAY NO</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>BAY INCH</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>T/S OF B.OP </b></th>
		</tr>

		

		<?php
			for($i=0;$i<count($result);$i++) { 
			?>
				<tr border ='1' cellpadding='0' cellspacing='0' style="font-size:12px;  border-collapse: collapse;">
					<td  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['Vessel_Name']; ?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['Registration_number_of_transport_code']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['cont_number']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php //echo $result[$i]['size']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['BL_No']?> 
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['Pack_Number']?> 
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['weight']/1000;?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['BL_No']?>  
					</td>					
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['shed_loc']; ?>
					</td>
					
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php //echo $result[$i]['created']; ?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php //echo $result[$i]['handled']; ?>
					</td>
								
				</tr>
			<?php
			}
		?>
		</table>
		<table width="95%" align="center">
		<br/>
		<br/>
		<br/>
		<tr>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>11</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>12</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>13</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>14</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>15</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>16</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>17</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>18</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>19</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>20</b></th>

		</tr>
		<tr>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>B/E NO. & DATE</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>C/P NO</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>VERIFY NO</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>C&F AGENT & CODE</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>JETTY SIRCAR NAME & LICENSE </b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>DLY QTY</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>DLY DATE</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>BALANCE QTY</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>DLY GANG</b></th>
			<th  colspan="2" style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>REMARKS </b></th>
		</tr>
		<?php
			for($i=0;$i<count($result);$i++) { 
			?>
				<tr border ='1' cellpadding='0' cellspacing='0' style="font-size:12px;  border-collapse: collapse;">
					<td  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['be_no']."-".$result[$i]['be_date']; ?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['cp_no']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['verify_number']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['cnf_name']."-".$result[$i]['cnf_lic_no']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
					
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php //echo $result[$i]['ub_rcv_qty']?>
					</td>					
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php //echo $result[$i]['shed_loc']; ?>
					</td>
					
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php //echo $result[$i]['created']; ?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php //echo $result[$i]['handled']; ?>
					</td>								
				</tr>
			<?php
			}
		?>
		
		
	</table>
<?php
//if($_POST['options']=='html'){?>	
		</BODY>
	</HTML>
<?php// }?>