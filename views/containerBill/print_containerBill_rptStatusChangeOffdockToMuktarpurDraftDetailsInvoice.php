<!--<style>
	table, th, td {
		color: black;
	}
	@media print 
	{
	  @page { margin: 0; }
	  body  { margin: 1.6cm; }
	}
</style>-->
<html>
<head>
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">

		<!-- Invoice Print Style -->
		<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>stylesheets/invoice-print.css" />
		<table align="center" width="95%">
			<tr>
				<td align="center"><font size="4"><b>CHITTAGONG PORT AUTHORITY</b></font></td>
			</tr>
			<tr>
				<td align="center"><b>VAT Reg:2041001546</b></td>
			</tr>
			<tr>
				<td align="center"><b><?php echo $invoiceDesc;?></b></td>
			</tr>
			
		</table>	
		<!--div align="center">(LCL BILL)</div-->

</head>
<body>	
		<div align ="center">
			<table align="center"  width="98%" style="font-size:13px;">
			<table align="center" border="0" width="100%" style="font-size:13px;">
				<tr>
					<td align="left">Bill No</td>
					<td align="center">:</td>
					<td align="left"><?php echo $draftNumber;?></td>
					
					<td align="left">&nbsp;</td>
					
					<td align="left">Rotation No</td>
					<td align="center">:</td>
					<td align="left"><?php echo $ibVisitId;?></td>
					
					<td align="left">&nbsp;</td>
					
					<td align="left">Date</td>
					<td align="center">:</td>
					<td align="left"><?php echo $created;?></td>
				</tr>
				
				<tr>
					<td align="left">Vessel</td>
					<td align="center">:</td>
					<td align="left"><?php echo $ibCarrierName;?></td>
					
					<td align="left">&nbsp;</td>
					
					<td align="left">MLO</td>
					<td align="center">:</td>
					<td align="left"><?php echo $customerId;?></td>
					
					<td align="left">&nbsp;</td>
					
					<td align="left">Agent</td>
					<td align="center">:</td>
					<!--td align="left"><?php echo $concustomerid;?></td-->
				</tr>
				
				<tr>
					<td valign="top" align="left"><nobr>MLO Name</nobr></td>
					<td valign="top">:</td>
					<td valign="top"><?php echo $customerName;?></td>
					
					<td align="left">&nbsp;</td>
					
					<td valign="top" align="left">Agent Name</td>
					<td valign="top">:</td>
					<!--td valign="top"><?php echo $concustomername;?></td-->
					
					<td colspan="4"></td>
				</tr>
			</table>
			    <tr>
					<td align="left">Bill No</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $draftNumber;?></td>
					<td align="left"></td>
					<td align="left"><nobr>Rotation No</nobr></td>
					<td align="left">: &nbsp;&nbsp;<?php echo $ibVisitId;?></td>
					<td align="left"></td>
					<td align="left">Date </td>
				
					<td align="left"><nobr>:&nbsp;&nbsp;<?php echo $created;?></nobr></td>
					<td align="left"></td>
				</tr>
			       <tr>
					<td align="left">Vessel</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $ibCarrierName;?></td>
					<td align="left"></td>
					<td align="left">MLO</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $customerId;?></td>
					<td align="left"></td>
					<td align="left">Agent </td>
					<!--td align="left">: &nbsp;&nbsp;<?php echo $concustomerid;?></td-->
					<td align="left"></td>
					
				</tr>
				<tr>
					<td align="left"><nobr>MLO Name</nobr></td>
					<td colspan="2" align="left">: &nbsp;<?php echo $customerName;?>&nbsp;&nbsp;&nbsp;</td>
					<td align="left"><nobr>Agent Name</nobr></td>
					<!--td colspan="2" align="left"><nobr>:&nbsp;<?php echo $concustomername;?></nobr></td-->
					<td align="left"></td>
					<td align="left"></td>
					<td align="left"></td>
				</tr>
				
			</table>	
			<table  align="center" width="100%">
				<tr>
					<td colspan="14">
						<hr style="margin: 3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="center">SL</td>
					<td align="center">CONTAINER NO</td>
					<td align="center">SIZE</td>
					<td align="center">HEIGHT</td>
					<td align="center">STATUS</td>
					<td align="center" style="width:100px">IMT ROT</td>
					<td align="center" style="width:100px">IMT ATA</td>
					<td align="center" style="width:100px">C.L.DATE</td>
					<td align="center">Depo DT</td>
					<td align="center">Port DT</td>
					<td align="center">PCT OUT</td>
					<td align="center" style="width:50px">PRE LOC</td>
					<td align="center">VAT(%)</td>
					<td align="center">Days</td>
				</tr>
				<tr>
					<td colspan="14">
						<hr style="margin: 3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
					
				<?php
				
				for($i=0; $i<count($rslt_detail); $i++) {?>
				<tr >
				<?php 
				    $port_date="";
					$portInt1Day="";
					$clDateInt4Day="";
					$depoInt1Day="";
					$freight_kind="";
					$fcy_time_in="";
					$fcy_time_out="";
					$depo_Int_Zero_Day="";
					$id="";
					$description="";
					$depo_date="";
					$gkey="";
				    $fcy_time_out=$rslt_detail[$i]['fcy_time_out'];
					$fcy_time_in=$rslt_detail[$i]['fcy_time_in'];
					$port_date=$rslt_detail[$i]['port_date'];
					$portInt1Day=$rslt_detail[$i]['portInt1Day'];
					$clDateInt4Day=$rslt_detail[$i]['clDateInt4Day'];
					$depoInt1Day=$rslt_detail[$i]['depoInt1Day'];
					$depo_Int_Zero_Day=$rslt_detail[$i]['depo_Int_Zero_Day'];
					$id=$rslt_detail[$i]['unitId'];
					$depo_date=$rslt_detail[$i]['depo_date'];
					$description=$rslt_detail[$i]['description'];
				    $gkey=$rslt_detail[$i]['gkey'];
				  
					//days subquery start here
					//please check the previous pcs_old here modife days
					$depoDateRes="";
					if($depoInt1Day==null){
					     $freight_kind_qu="SELECT inv_unit.freight_kind 
						FROM inv_unit INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
						WHERE id='$id' AND category='IMPRT' AND to_char(inv_unit_fcy_visit.time_in,'YYYY-MM-DD HH24:MI:SS') < '$fcy_time_in' ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
						$rslt_freight_kind = $this->bm->dataSelect($freight_kind_qu);
						$freight_kind="";
					    $freight_kind=$rslt_freight_kind[0]['FREIGHT_KIND'];
						$portDate="";
						if($freight_kind=='LCL'){	
						$portDate=$port_date;
						}else{
						 $portDate=$portInt1Day;
						}
						$depoDateRes=$portDate;
					}
					else{
						 $depoDateRes=$depoInt1Day;	
					}
						$clDate_Res_final="";
						if($depoDateRes==null){
						 $clDate_Res_final=$clDateInt4Day;  
						}else{
						$clDate_Res_final= $depoDateRes;
						}
					
					
					   $dayDiffRes1="";
					   $difference1_qu="SELECT DATEDIFF('$fcy_time_out','$clDate_Res_final')+1 AS day1";
					   //echo "<br>";
					   $rslt_difference1=$this->bm->dataSelectDb2($difference1_qu);
					  // print_r($rslt_difference1);
					   $dayDiffRes1=$rslt_difference1[0]['day1'];
					  //echo "<br>";
				 
				      //difference2   
					   $depoDateRes1="";
				  
					if($depo_Int_Zero_Day==null){
						
						 $freight_kind_qu2=" SELECT inv_unit.freight_kind
						FROM inv_unit    
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
						WHERE id='$id' AND category='IMPRT'  
						AND to_char(inv_unit_fcy_visit.time_in,'YYYY-MM-DD HH24:MI:SS') < '$fcy_time_in'
						ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
						//echo "<br>";
								
						$rslt_freight_kind2 = $this->bm->dataSelect($freight_kind_qu2);
						$freight_kind1="";
					    $freight_kind1=$rslt_freight_kind2[0]['FREIGHT_KIND'];
						
						$portDate1="";
						if($freight_kind1=='LCL'){	
						$portDate1=$rslt_detail[$i]['port_date'];
						}else{
						$portDate1=$rslt_detail[$i]['portInt1Day'];
						}
						$depoDateRes1=$portDate1;
					}else{
						$depoDateRes1=$rslt_detail[$i]['depo_Int_Zero_Day'];	
					}
				  
						$clDate_Res_final2="";
						if($depoDateRes1==null){
						  $clDate_Res_final2=$rslt_detail[$i]['clDateInt4Day'];  
						}else{
						 echo $clDate_Res_final2= $depoDateRes1;
						}
						
					   $difference2_qu="SELECT DATEDIFF('$fcy_time_out','$clDate_Res_final2')+1 AS day2";
					   $rslt_difference1=$this->bm->dataSelectDb2($difference2_qu);
					   $dayDiffRes2=$rslt_difference1[0]['day2'];
				  
				      //final if result for days
					  $days="";
					   if($dayDiffRes1<1){
						  $days='0';
					   }else{
							 $days=$dayDiffRes2;
					   }
					   
					   //days subquery end here
					   
					   //for preloc subquery  start here
					    $preLoc_Subqu="select inv_goods.destination,inv_unit.gkey
						from inv_unit
						inner join inv_goods on inv_goods.gkey=inv_unit.goods
						where inv_unit.gkey=$gkey fetch first 1 rows only";
						$rslt_preloc = $this->bm->dataSelect($preLoc_Subqu);
						$preloc="";
						$preloc =$rslt_preloc[0]['DESTINATION'];
					   //for preloc subquery  End here
				
				?>
					<td style="border:0px;"><font style="border:0 px solid black"><?php echo  $i+1;?></font></td>
					<td align="center" ><?php echo $rslt_detail[$i]['unitId'] ?></td>
					<td align="center"><?php echo $rslt_detail[$i]['isoLength'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['isoHeight'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['freightKind'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['imp_rot'];?></td>
					<td align="center" style="width:70px"><?php echo $rslt_detail[$i]['imp_ata'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['timeIn'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['depo_date'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['port_date'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['timeOut'];?></td>
					<td align="center"><?php echo $preloc;?></td>
					<td align="center"><?php echo $rslt_detail[$i]['vatperc'];?></td>
					<td align="center"><?php echo $days;?></td>
				</tr>
				<?php  } ?>
				
			</table>	
			<table align="center" width="100%" style="font-size:13px;">	
				<tr>
					<td colspan="17">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="left"><u>Equipment</u></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td colspan="2" align="right"><u>Vat</u></td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="center" style="width:90px"><u><nobr>Lift On / Lift Off:</nobr></u></td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="right"></td>
				</tr>
				<tr>
					<td style="border: 1px solid black; border-collapse: collapse; " align="left">Wholly:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $equipmentW;?></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="left">No:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center" ><?php echo $equipmentN;?></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="center">Partly:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $equipmentP;?></td>
					<td align="right" style="width:25px"></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="left">Vat:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $vat;?></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="left">Non Vat:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $nonvat;?></td>
					<td align="right" style="width:120px"></td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center">Yes:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center"><?php echo $lon;?></td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center">No:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center"><?php echo $nlon;?></td>
					<td align="right"></td>
				</tr>
				<tr>
					<td colspan="17">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="17">&nbsp;</td>
				</tr>
			</table>
			<table align="center" width="100%" style="font-size:14px;">
				<tr>
					<td align="center"><u>Summary</u></td>
					<td align="center"><u>20 X 8.5</u></td>
					<td align="center"><u>20 X 9.5</u></td>
					<td align="center"><u>40 X 8.5</u></td>
					<td align="center"><u>40 X 9.5</u></td>
					<td align="center"><u>45 X 8.5</u></td>
					<td align="center"><u>45 X 9.5</u></td>
					<td align="center" style=" border-left: 1px solid black;"></td>
					<td align="center"><u>Total</u></td>
				</tr>
				<tr>
					<td align="center">FCL - </td>
					<td align="center"><?php echo $fcl_20_85;?></td>
					<td align="center"><?php echo $fcl_20_95;?></td>
					<td align="center"><?php echo $fcl_40_85;?></td>
					<td align="center"><?php echo $fcl_40_95;?></td>
					<td align="center"><?php echo $fcl_45_85;?></td>
					<td align="center"><?php echo $fcl_45_95;?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>
					<td align="center"><?php echo $fcl;?></td>
				</tr>
				<tr>
					<td align="center">LCL - </td>
					<td align="center"><?php echo $lcl_20_85;?></td>
					<td align="center"><?php echo $lcl_20_95;?></td>
					<td align="center"><?php echo $lcl_40_85;?></td>
					<td align="center"><?php echo $lcl_40_95;?></td>
					<td align="center"><?php echo $lcl_45_85;?></td>
					<td align="center"><?php echo $lcl_45_95;?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>					
					<td align="center"><?php echo $lcl;?></td>
				</tr>
				<tr>
					<td align="center">EMT - </td>
					<td align="center"><?php echo $mty_20_85;?></td>
					<td align="center"><?php echo $mty_20_95;?></td>
					<td align="center"><?php echo $mty_40_85;?></td>
					<td align="center"><?php echo $mty_40_95;?></td>
					<td align="center"><?php echo $mty_45_85;?></td>
					<td align="center"><?php echo $mty_45_95;?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>					
					<td align="center"><?php echo $mty;?></td>
				</tr>
				<tr>
				    <td colspan="9"><hr style=" border-top:1px dotted; color:black;"/></td>
				</tr>
				<tr>
					<td align="center"> </td>
					<td align="center"><?php echo $tot_20_85;?></td>
					<td align="center"><?php echo $tot_20_95;?></td>
					<td align="center"><?php echo $tot_40_85;?></td>
					<td align="center"><?php echo $tot_40_95;?></td>
					<td align="center"><?php echo $tot_45_85;?></td>
					<td align="center"><?php echo $tot_45_95;?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>					
					<td align="center"><?php echo $tot;?></td>
				</tr>
			</table>
			
			<table align="center" width="90%" style="font-size:14px;">
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr><tr>
					<td colspan="6">&nbsp;</td>
				</tr><tr>
					<td colspan="6">&nbsp;</td>
				</tr><tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6">Print Date : &nbsp;&nbsp; <?php echo $Time; ?></td>
				</tr>
			</table>			
		</div>
	
</body>
<!--<script>
	window.print();
</script>-->
</html>
