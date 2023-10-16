
	<?php
	include("mydbPConnection.php");
	include("dbConection.php");
	$counter=count($result);
	$j=0;
	
	
	$agent="";
	$rl_no="";
	$rl_date="";
	$remarks="";
	
	for($i=0;$i<count($result);$i++)		// main loop
		{
			
				$prevBl = "";
				//print_r($result);
						
				if($rtnValue=='0'){	
			    $rl_no = $result[$i]['RL_NO'];
				$rl_date = $result[$i]['RL_DATE'];
				$remarks = $result[$i]['REMARKS'];
				}else{	
				
				$rl_no = $result[$i]['rl_no'];
				$rl_date = $result[$i]['rl_date'];
				$obpc_number = $result[$i]['obpc_number'];
				$obpc_date="";
				if($result[$i]['obpc_date']!='0000-00-00')
				{
					$obpc_date = $result[$i]['obpc_date'];
				}
				$remarks = $result[$i]['remarks'];
				$blNo = $result[$i]['bl_no'];
				$house_bl = $result[$i]['houseBL'];
				$master_bl = $result[$i]['masterBL'];
				$rotation = $result[$i]['rotation_no'];
				$pack_Marks_Number = $result[$i]['pack_Marks_Number'];
				$description_of_Goods = $result[$i]['description_of_Goods'];
				$notify_name = $result[$i]['Notify_name_'];
				$Notify_address = $result[$i]['Notify_sup_name_'];
				$mlocode = $result[$i]['mlocode'];
				$ff_name = $result[$i]['ff_name'];
				$ff_addr = $result[$i]['ff_addr'];
				$quantity = $result[$i]['quantity'];
				}
		?>
		
			<?php if($j<$counter-1) { ?>
		<div style="position: relative;PAGE-BREAK-AFTER: always;">
		<?php } else { ?>
		<div style="position: relative;PAGE-BREAK-AFTER: avoid;">
		<?php }?>
	<body>	
	<table border="0" align="center" width="100%" style="border-collapse:collapse;margin-bottom:10px;">
	<?php $path = 'http://cpatos.gov.bd'."/assets/images";?>
		<tr>
			<td align="center" style="width:90%" colspan="2">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<!-- <img align="center" width="160px" style="margin:0px;padding:0px;" height="90px" src="<?php echo ASSETS_PATH?>images/cpa_logo.png"> -->
				<img align="center" width="160px" style="margin:0px;padding:0px;" height="90px" src="<?php echo $path ?>/cpa_logo.png">
				
			</td>
			<td style="width:10%;">
				 <!-- <img align="left" width="80px" height="45px"  src="<?php echo ASSETS_PATH?>assets/images/auction_img.png">  -->
				 <img align="left" width="80px" height="45px"  src="<?php echo $path?>/auction_img.png"> 
			
			</td>
		</tr>
		<tr>
			<td align="center" colspan="3">
				<font size="6"><strong>Chattogram Port Authority</strong></font><br>
				<font size="4"><strong>(Removal Tally Book) </strong></font><br>
				<font size="4"><strong>Copy to be Returned to Parent Shed</strong></font>		 	
			</td>
		</tr>
		<tr>
			<td style="width:50%"></td>
			<td align="right" style="width:40%; font-size:15px">
				<strong> <?php echo "  RL No. : ". $rl_no."				Date :  ".$rl_date; ?> </strong>
			</td>
			<td> <strong> </strong></td>
		</tr>
		<tr>
			<td style="width:50%"></td>
			<td align="right" style="width:40%; font-size:15px">
				<strong> <?php echo "  OPBC No. : ".$obpc_number."		Date :  ".$obpc_date; ?> </strong>
			</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="3"><br/></td>
		</tr>
		<tr>
			<!--td align="center">
				<font size="4">
					<strong>
						ARRIVAL DATE: <?php echo $arrival_date; ?>, ROTATION: <?php echo $rotation; ?>, 
						C/L DATE: <?php echo $cl_date; ?>, Unit: <?php echo $unit; ?>
					</strong>
				</font>
			</td-->
		</tr>
		<tr>
			<td align="center" colspan="3" style="font-size:15px">
				The cargo of <strong><?php echo $v_name; ?></strong>, Rotation No <strong><?php echo $rotation; ?></strong>, Arrival date : <strong><?php echo $arrival_date; ?></strong>, CL Date: <strong><?php echo $cl_date; ?></strong> 
				Ex. Shed OneStop & M shed Unit No: <strong><?php echo $unit; ?></strong> transferred to shed No.______/AUCTION in wagon No. by paper.
			</td>
		</tr>
	</table>
	<table border="1" align="center" width="100%" style="border-collapse:collapse;">
		<thead>
			<tr>
				<th rowspan='2' class="text-center">SL No</th>
				<th rowspan='2' class="text-center">Master BL</th>
				<th rowspan='2' class="text-center">House BL</th>
				<th rowspan='2' class="text-center">Marks</th>
				<th rowspan='2' class="text-center">Goods Description</th>
				<th rowspan='2' class="text-center">Importer Name & Address</th>
				<th rowspan='2' class="text-center">Container Gross Wt</th>
				<th rowspan='2' class="text-center">Quantity (STC)</th>
				<th rowspan='2' class="text-center">MLO</th>
				<th rowspan='2' class="text-center">Agent</th>
				<th rowspan='2' class="text-center">Freight Forwarder Name & Address</th>
				<!--th rowspan='2' class="text-center">RL_No RL_Dt</th-->
				<th colspan='6' class="text-center">Container Detail</th>				
				<!--th class="text-center">Custom Part</th>
				<th rowspan='2' class="text-center">Remarks</th-->
				
			</tr>
			<tr>
				<th class="text-center">Container No</th>
				<th class="text-center">Size</th>
				<th class="text-center">Height</th>
				<th class="text-center">Gross Wt</th>
				<th class="text-center">Status</th>
				<th class="text-center">Location (Yard)</th>
				<!--th class="text-center" width="12%">OBPC_No OBPC_Dt</th-->
			</tr>
		</thead>
		<tbody>
			<?php
	

				//$yard = $result[$i]['yard'];
				
				//$contNo = $result[$i]['id'];
				//$size = $result[$i]['size'];
				//$height = $result[$i]['height'];
				//$cont_status = $result[$i]['cont_status'];
				//$last_pos_slot = $result[$i]['last_pos_slot'];
				
				$query = "SELECT Description_of_Goods,Pack_Marks_Number, igm_supplimentary_detail.BL_No, igm_sup_detail_container.cont_gross_weight,
						igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description
						FROM cchaportdb.igm_supplimentary_detail 
						INNER JOIN cchaportdb.igm_sup_detail_container ON cchaportdb.igm_sup_detail_container.igm_sup_detail_id=cchaportdb.igm_supplimentary_detail.id
						WHERE cchaportdb.igm_supplimentary_detail.Import_Rotation_No='$rotation' 
						AND cchaportdb.igm_supplimentary_detail.BL_No='$blNo'

						UNION
						
						SELECT Description_of_Goods,Pack_Marks_Number, igm_details.BL_No, igm_detail_container.cont_gross_weight,
						igm_details.Pack_Number, igm_details.Pack_Description
						FROM cchaportdb.igm_details 
						INNER JOIN cchaportdb.igm_detail_container ON cchaportdb.igm_detail_container.igm_detail_id=cchaportdb.igm_details.id
						WHERE cchaportdb.igm_details.Import_Rotation_No='$rotation' 
						AND cchaportdb.igm_details.BL_No='$blNo'";
				//return;		

				$str1=mysqli_query($con_cchaportdb,$query);

				$pack_Marks_Number = "";
				$Pack_Number = "";
				$Pack_Description = "";
				$cont_gr_wt = "";

				while($row2 = mysqli_fetch_object($str1)){
					//$cont_status = $row2->cont_status;
					$pack_Marks_Number = $row2->Pack_Marks_Number;
					//$blNo = $row2->BL_No;
					$Pack_Number = $row2->Pack_Number;
					$Pack_Description = $row2->Pack_Description;
					//$cont_gr_wt = $row2->cont_gross_weight;

				}
							
				$count=0;
				
				 $query2 = "SELECT DISTINCT cont, size, height, TYPE, auction_handover.cont_status, last_pos_slot, obpc_number, obpc_date, weight FROM auction_handover
							WHERE auction_handover.rotation_no='$rotation' AND ( auction_handover.bl_no='$blNo' OR  auction_handover.house_bl='$blNo' )";
							//return;
				$str2=mysqli_query($con_cchaportdb,$query2);
				$count = mysqli_num_rows($str2);
				
				$all_cont_gross_weight=0;
				$resultSub = $this->bm->dataSelectDb1($query2);	
				
				 while($grossRow = mysqli_fetch_object($str2)){
					$all_cont_gross_weight =$all_cont_gross_weight + $grossRow->weight;				
				} 
					$last_pos_slot = @$resultSub[0]['last_pos_slot'];
				
				$query3 = "SELECT ctmsmis.cont_yard('$last_pos_slot') as rtnValue";
				//$resultYard = $this->bm->dataReturn($query3);
				$resultYard = $this->bm->dataReturnDb2($query3);
			
				
				/* $hBL="";
				$houseBL=array();
				$query3 = "SELECT igm_supplimentary_detail.BL_No FROM igm_supplimentary_detail WHERE igm_supplimentary_detail.master_BL_No='$blNo'";
				$str3=mysqli_query($con_cchaportdb,$query3);
				while($row3 = mysqli_fetch_object($str3))
				{
					$houseBL []= $row3->BL_No;
				}	
				$hBL = nl2br(implode(', ', $houseBL)); */
			?>
				<tr align="center">
					<td rowspan="<?php echo $count; ?>" align="center" ><?php echo $i+1; ?></td>
					<td rowspan="<?php echo $count; ?>" align="center" ><?php echo $master_bl; ?></td>
					<td rowspan="<?php echo $count; ?>" align="center" ><?php echo $house_bl; ?></td>
					<td rowspan="<?php echo $count; ?>" align="center" ><?php echo substr($pack_Marks_Number,0,100); ?></td>
					<td rowspan="<?php echo $count; ?>" align="center" ><?php echo substr($description_of_Goods,0,100); ?></td>
					<td rowspan="<?php echo $count; ?>" align="center" ><?php echo $notify_name.', : '.$Notify_address; ?></td>
					<!--td rowspan="<?php echo $count; ?>" align="center" ><?php echo $Notify_address; ?></td-->
					<!--td rowspan="<?php echo $count; ?>" align="center" ><?php echo $cont_gr_wt; ?></td-->
					<td rowspan="<?php echo $count; ?>" align="center" ><?php echo $all_cont_gross_weight; ?></td>
					<td rowspan="<?php echo $count; ?>" align="center" ><?php echo $Pack_Number."<br/> ".$Pack_Description; ?></td>
					<td rowspan="<?php echo $count; ?>" align="center" ><?php echo $mlocode; ?></td>
					<td rowspan="<?php echo $count; ?>" align="center" ><?php echo $agent; ?></td>
					<td rowspan="<?php echo $count; ?>" align="center" ><?php echo $ff_name.' : '.$ff_addr; ?></td>
					<!--td rowspan="<?php echo $count; ?>" align="center" ><?php echo $rl_no."<br/> ".$rl_date;; ?></td-->
					<?php 

						
						//while($row3 = mysqli_fetch_object($str2)){
						$cont = @$resultSub[0]['cont'];
						$size = @$resultSub[0]['size'];
						$height = @$resultSub[0]['height'];
						$weight = @$resultSub[0]['weight'];
						$cont_status = @$resultSub[0]['cont_status'];
						$obpc_number = 'OBPC   /--';
						$obpc_date = '';
						
						
					?>
					
					<td align="center" ><?php echo $cont; ?></td>
					<td align="center" ><?php echo $size; ?></td>
					<td align="center" ><?php echo $height; ?></td>
					<td align="center" ><?php echo $weight; ?></td>
					<td align="center" ><?php echo $cont_status; ?></td>
					<td align="center" ><?php echo $last_pos_slot.' ('.$resultYard.')'; ?></td>
					<!--td rowspan="<?php echo $count; ?>" ><?php echo $obpc_number."<br/> ".$obpc_date ?></td>
					
					<td rowspan="<?php echo $count; ?>" ><?php echo $remarks; ?></td-->
				
				</tr>
	
              <?php if($count>1)
				  	for($n=1; $n<count($resultSub); $n++)
						{
						//while($row3 = mysqli_fetch_object($str2)){
						$cont = $resultSub[$n]['cont'];
						$size = $resultSub[$n]['size'];
						$height = $resultSub[$n]['height'];
						$weight = $resultSub[$n]['weight'];
						$last_pos_slot = $resultSub[$n]['last_pos_slot'];
						$cont_status = $resultSub[$n]['cont_status'];
						
						$query4 = "SELECT ctmsmis.cont_yard('$last_pos_slot') as rtnValue";
						$resultYard = $this->bm->dataReturnDb2($query4);


				  ?>
				
				<tr>
					<td  align="center" ><?php echo $cont; ?></td>
					<td  align="center" ><?php echo $size; ?></td>
					<td  align="center" ><?php echo $height; ?></td>
					<td  align="center" ><?php echo $weight; ?></td>
					<td  align="center" ><?php echo $cont_status; ?></td>
					<td  align="center" ><?php echo $last_pos_slot.' ('.$resultYard.')'; ?></td>
					
				</tr>
				
			<?php
					}
				
			?>
			
		</tbody>
		
	</table>
	
	<br/>
	<br/>
	<br/>

	<table width="100%">
		<tr>
			<td><b>CPA</b></td>
			<td width="50%">&nbsp;</td>
			<td><b>Custom</b></td>
		</tr>
		<tr>
			<td>Name: .........................................</td>
			<td width="60%">&nbsp;</td>
			<td>Name: .........................................</td>
		</tr>
		<tr>
			<td>Designation: ................................</td>
			<td width="60%">&nbsp;</td>
			<td>Designation: ................................</td>
		</tr>
		<tr>
			<td>Unit No:  ......................................</td>
			<td width="60%">&nbsp;</td>
			<td>Unit No:  ......................................</td>
		</tr>
		
	</table>
	<?php //mysqli_close($con_cchaportdb); ?>
	<script>
		//window.print();
	</script>
</body>
</div>
<?php
		}
		
		mysqli_close($con_cchaportdb);
?>
