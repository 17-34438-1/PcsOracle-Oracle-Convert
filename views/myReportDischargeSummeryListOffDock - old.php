<HTML>
	<HEAD>
		<TITLE>IGM Final Amendment</TITLE>
		
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php
	
    $sum=0;
	$grand=0;
//mysql_connect("192.168.1.7", "user-dev", "user7test");
//mysql_select_db("ccha");
    include("mydbPConnection.php");
	$rotation=$_POST['ddl_imp_rot_no']; 
	//$rotation=$_REQUEST['ddl_imp_rot_no'];
	$igmdetail=mysqli_query($con_cchaportdb,"select * from igm_details where Import_Rotation_No='$rotation'");
	$testdetailRes2=mysqli_num_rows($igmdetail);
	//echo $testdetailRes2."<hr>";
	if($testdetailRes2<1)
	{
		$igmMaster=mysqli_query($con_cchaportdb,"select Import_Rotation_No,Vessel_Name,Total_number_of_bols,Total_number_of_packages,Total_number_of_containers,Total_gross_mass from igm_masters where Import_Rotation_No='$rotation'");
		$testRes2=mysqli_num_rows($igmMaster);
		$row_igm_master=mysqli_fetch_object($igmMaster);		
		if($testRes2>0)
		{
		?>
			<TABLE width="100%">
				<TR>
					<TD width="100%">
						<table class='table-header' border=0 width="100%">
							<tr><td colspan="2" align="center"><h1> GENERAL INFORMATION</h1></td></tr>
							<tr>
								<tr>
								<td align="center">Vessel name:<?php print($row_igm_master->Vessel_Name);?></td>
								<td align="center">Rotation No:&nbsp;&nbsp;<?php print($row_igm_master->Import_Rotation_No);?></td>
								</tr>
							</tr>						
						</table>
					</TD>
				</TR>
				<TR>
					<TD>
						<table width="100%" border=1  cellspacing="0" cellpadding="0">
							<tr>
								<th align="center"><span class="style1" >Total Number Of BL</span></th>
								<th align="center"><span class="style1" >Total Number Of Packages</span></th>
								<th align="center"><span class="style1" >Total Number Of Containers</span></th>
								<th align="center"><span class="style1" >Total Gross Mass</span></th>
							</tr>				
							<tr>
								<td align="right"><?php print($row_igm_master->Total_number_of_bols);?></td>
								<td align="right"><?php print($row_igm_master->Total_number_of_packages);?></td>
								<td align="right"><?php print($row_igm_master->Total_number_of_containers);?></td>
								<td align="right"><?php print($row_igm_master->Total_gross_mass);?></td>
							</tr>
						</table>
								<!--<tr><td colspan="2" align="center"><h3><font color="red"> We found Only General segment from ASYCUDA World but we don't get any BL and Container segment. Please contact with ASYCUDA World Team for resending .</font></h3></td></tr>-->
							
					</TD>
				</TR>
			</TABLE>
		<?php 
		}
		else
		{
		?>
			<tr><td colspan="2" align="center"><h3><font color="red">No Record found for your given rotation . Please type correctly and try again.</font></h3></td></tr>		
		<?php
		}

	} 
	else
	{ ?>
	<?php
		$result_igm_master1="SELECT
								igm_masters.Import_Rotation_No,
								igm_masters.Vessel_Name,
								Voy_No,
								Net_Tonnage,
								Port_of_Shipment,
								Port_of_Destination,
								Sailed_Year,
								Submitee_Org_Id,
								Name_of_Master,
								Organization_Name,
								is_Foreign,
								Vessel_Type,Actual_Berth,Actual_Berth_time
							FROM
								igm_masters 
								LEFT JOIN organization_profiles ON 
								organization_profiles.id=igm_masters.Submitee_Org_Id
								LEFT JOIN vessels ON vessels.id=igm_masters.Vessel_Id
								left join vessels_berth_detail on vessels_berth_detail.Import_Rotation_No=igm_masters.Import_Rotation_No
							WHERE igm_masters.Import_Rotation_No='$rotation'";
							
		$result_igm_master=mysqli_query($con_cchaportdb,$result_igm_master1);
		$row_igm_master=mysqli_fetch_object($result_igm_master);	
         // print("Shemul".$row_igm_master->Import_Rotation_No);
	?>
			
		<TABLE width="100%">
			<TR>
				<TD width="100%">
					<table class='table-header' border=0 width="100%">
						<!--tr align="center">
							<td colspan="2" align="center"><h1>CHITTAGONG PORT AUTHOURITY, CHITTAGONG</h1></td>
						</tr-->
						<tr>
							<td colspan="19" align="center"><img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
						</tr>
						<tr><td colspan="2" align="center"><h1> FEEDER SUMMARY LIST OFFDOCK WISE</h1></td></tr>
											
						<tr>
							<td align="center">Vessel name:<?php print($row_igm_master->Vessel_Name);?></td>						
							<td align="center">Rotation No:&nbsp;&nbsp;<?php print($row_igm_master->Import_Rotation_No);?></td>				
						<!--<td align="left">ATA:<?php print($row_igm_master->Actual_Berth);?><td align="left">ATD:<?php print($row_igm_master->Actual_Berth_time);?></td>-->
						</tr>					
					</table>
				</TD>
			</TR>
			<TR>
				<TD>
					<table class="table table-bordered table-responsive table-hover table-striped mb-none">
						<tr>							
							<th align="center"><span class="style1" >Shipping Agent Name</span></th>
							<th align="center"><span class="style1" >Agent Name</span></th>
							<th align="center"><span class="style1" >Agent CODE</span></th>						
							<th align="center"><span class="style1" >MLO CODE</span></th>
							<th colspan='2' align="center"><span class="style1" >LADEN</span></th>
							<th colspan='2' align="center"><span class="style1" >EMPTY</span></th>					
							<th colspan='2' align="center"><span class="style1" >REFFER</span></th>
							<th colspan='2' align="center"><span class="style1" >IMDG</span></th>						
							<th colspan='2' align="center"><span class="style1" >TRANS</span></th>	
							<th colspan='2' align="center"><span class="style1" >45'</span></th>						
							<th colspan='2' align="center"><span class="style1" >TOTAL</span></th>						
							<th colspan='' align="center"><span class="style1" >TOTAL</span></th>						
							<?php 
							if($countBillRow>0) 
							{ ?>
							<th  align="center"><span class="style1" >VIEW BILL</span></th>
							<th  align="center"><span class="style1" >VIEW DETAIL</span></th>
							<?php 
							} ?>									
						</tr>
						<tr>
							<td  align="center"><?php  print("&nbsp;");?></td>
							<td  align="center"><?php  print("&nbsp;");?></td>
 							<td  align="center"><?php  print("&nbsp;");?></td>
 							<td  align="center"><?php  print("&nbsp;");?></td>
							<td  align="center"><?php  print("20")    ;?></td>
							<td  align="center"><?php  print("40")    ;?></td>
							<td  align="center"><?php  print("20")    ;?></td>
							<td  align="center"><?php  print("40")    ;?></td>
							<td  align="center"><?php  print("20")    ;?></td>
							<td  align="center"><?php  print("40")    ;?></td>
							<td  align="center"><?php  print("20")    ;?></td>
							<td  align="center"><?php  print("40")    ;?></td>
							<td  align="center"><?php  print("20")    ;?></td>
							<td  align="center"><?php  print("40")    ;?></td>
							<td  align="center"><?php  print("LD")    ;?></td>
							<td  align="center"><?php  print("MT")    ;?></td>
							<td  align="center"><?php  print("20")    ;?></td>
							<td  align="center"><?php  print("40")    ;?></td>								
						</tr>
							
						<?php
						$str="select distinct submitee_org_id,organization_profiles.Organization_Name as Organization_Name,organization_profiles.Agent_Code,mlocode as mlocode from igm_details 
						left join organization_profiles on igm_details.Submitee_Org_Id=organization_profiles.id 
						where Import_Rotation_No='$rotation' order by Organization_Name,mlocode";
						
						$result=mysqli_query($con_cchaportdb,$str);
						$i=0;
						while ($row=mysqli_fetch_object($result))
						{
							//$i++;
							$sum=$sum+$grand;
						?>
						<tr>							
							<td align="left"><?php if($row->Organization_Name) print($row->Organization_Name); else print("&nbsp;");?></td>
							
							<?php
								$sql_agent_code=mysqli_query($con_cchaportdb,"select mlodescription,mlo_agent_code_ctms,agent_from,org_id from mlo_detail where mlocode='$row->mlocode'");
									
								$row_agent_code=mysqli_fetch_object($sql_agent_code);														
							?>							
							<td  align="left"><?php if(@$row_agent_code->mlodescription) print($row_agent_code->mlodescription); else print("&nbsp;");?></td>
							<td  align="left"><?php if(@$row_agent_code->mlo_agent_code_ctms) print($row_agent_code->mlo_agent_code_ctms); else print("&nbsp;");?></td>
							<td  align="left"><?php if($row->mlocode) print($row->mlocode); else print("&nbsp;");?></td>
							<!--ASIF START -->
							
							
							<?php
//FCL-20"
							$str1="select count(distinct cont_number) as total 
							from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') and type_of_igm<>'TS' 
                            and off_dock_id  IN('2594','2595','2596','2597','2598','2599','2600','2601','2603','2620','2624','2643','2645','2646','2647','3328','3450','3697','3709','3725','4013','5884') and cont_status not in ('EMT','EMPTY','MT','ETY') and cont_size =20 and  igm_details.final_submit=1";	
							
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
			//imdg			
							
							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')and type_of_igm<>'TS' 
                            and off_dock_id IN('2594','2595','2596','2597','2598','2599','2600','2601','2603','2620','2624','2643','2645','2646','2647','3328','3450','3697','3709','3725','4013','5884') and   (cont_status='EMT' or cont_status='Empty' or cont_status='MT' or cont_status='ETY') and cont_size =20 and (cont_imo = '' and cont_un = '' and igm_details.final_submit=1)";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1_mty_20=mysqli_fetch_object($result1);
							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type like '%R%' and cont_iso_type not in ('DRY') and type_of_igm<>'TS' 
                            and off_dock_id IN('2594','2595','2596','2597','2598','2599','2600','2601','2603','2620','2624','2643','2645','2646','2647','3328','3450','3697','3709','3725','4013','5884')  and cont_size =20 and igm_details.final_submit=1";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1_ref_20=mysqli_fetch_object($result1);
							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') and type_of_igm<>'TS' 
							and off_dock_id  IN('2594','2595','2596','2597','2598','2599','2600','2601','2603','2620','2624','2643','2645','2646','2647','3328','3450','3697','3709','3725','4013','5884')  and cont_size =20 and (cont_imo <> '' and cont_un <> '' and igm_details.final_submit=1)";	
							 
							$result1_dmg=mysqli_query($con_cchaportdb,$str1);
							$row1_dmg_20=mysqli_fetch_object($result1_dmg);
							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and type_of_igm='TS' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') 
                            and off_dock_id  IN('2594','2595','2596','2597','2598','2599','2600','2601','2603','2620','2624','2643','2645','2646','2647','3328','3450','3697','3709','3725','4013','5884')   and cont_size =20 and igm_details.final_submit=1";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1_tran_20=mysqli_fetch_object($result1);
							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode'  and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')
                            and cont_size >40 and igm_details.final_submit=1 and (cont_status <> 'EMT' and cont_status <> 'Empty' and cont_status <> 'MT' and cont_status <> 'ETY')";	
							
		
							$result1=mysqli_query($con_cchaportdb,$str1);  
							$row1_45_ld=mysqli_fetch_object($result1);
							
							
							if($row1->total) 
								$totalRes=$row1->total-$row1_mty_20->total-$row1_ref_20->total-$row1_dmg_20->total-$row1_tran_20->total-$row1_45_ld->total;
							$total1=$row1->total-$row1_mty_20->total-$row1_ref_20->total-$row1_dmg_20->total-$row1_tran_20->total-$row1_45_ld->total;
							@$totalg1=$totalg1+$total1;
							

							?>
							<td  align="left"><?php if($row1->total) print($totalRes); else print("0");?></td>
							<?php
//FCL-40"
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') and type_of_igm<>'TS' 
                            and off_dock_id  IN('2594','2595','2596','2597','2598','2599','2600','2601','2603','2620','2624','2643','2645','2646','2647','3328','3450','3697','3709','3725','4013','5884') and cont_status not in ('EMT','EMPTY','MT','ETY') and cont_size =40 and igm_details.final_submit=1";
//print("<br>".$str1);		
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
		//imdg							
								
							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')and type_of_igm<>'TS' 
                            and off_dock_id IN('2594','2595','2596','2597','2598','2599','2600','2601','2603','2620','2624','2643','2645','2646','2647','3328','3450','3697','3709','3725','4013','5884') and   (cont_status='EMT' or cont_status='Empty' or cont_status='MT' or cont_status='ETY') and cont_size =40 and (cont_imo = '' and cont_un = '' and igm_details.final_submit=1)";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1_mty_40=mysqli_fetch_object($result1);
							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type like '%R%' and cont_iso_type not in ('DRY') and type_of_igm<>'TS' 
                            and off_dock_id IN('2594','2595','2596','2597','2598','2599','2600','2601','2603','2620','2624','2643','2645','2646','2647','3328','3450','3697','3709','3725','4013','5884')  and cont_size =40 and igm_details.final_submit=1";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1_ref_40=mysqli_fetch_object($result1);
							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') and type_of_igm<>'TS' 
							and off_dock_id  IN('2594','2595','2596','2597','2598','2599','2600','2601','2603','2620','2624','2643','2645','2646','2647','3328','3450','3697','3709','3725','4013','5884')  and cont_size =40 and (cont_imo <> '' and cont_un <> '' and igm_details.final_submit=1)";	
							 
							$result1_dmg=mysqli_query($con_cchaportdb,$str1);
							$row1_dmg_40=mysqli_fetch_object($result1_dmg);
							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and type_of_igm='TS' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') 
                            and off_dock_id  IN('2594','2595','2596','2597','2598','2599','2600','2601','2603','2620','2624','2643','2645','2646','2647','3328','3450','3697','3709','3725','4013','5884')   and cont_size =40 and igm_details.final_submit=1";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1_tran_40=mysqli_fetch_object($result1);
							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')
                            and cont_size >40 and igm_details.final_submit=1 and   (cont_status='EMT' or cont_status='Empty' or cont_status='MT' or cont_status='ETY') ";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1_45_mty=mysqli_fetch_object($result1);
							
							
							if($row1->total) 
								$totalRes=$row1->total-$row1_mty_40->total-$row1_ref_40->total-$row1_dmg_40->total-$row1_tran_40->total-$row1_45_mty->total;
							$total2=$row1->total-$row1_mty_40->total-$row1_ref_40->total-$row1_dmg_40->total-$row1_tran_40->total-$row1_45_mty->total;
							@$totalg2=$totalg2+$total2;
							

							?>
							<td  align="left"><?php if($row1->total) print($totalRes); else print("0");?></td>
         					<td  align="left"><?php if($row1_mty_20->total) print($row1_mty_20->total); else print("0"); $total3=$row1_mty_20->total; @$totalg3=$totalg3+$total3;?></td>		 
							<td  align="left"><?php if($row1_mty_40->total) print($row1_mty_40->total); else print("0"); $total4=$row1_mty_40->total; @$totalg4=$totalg4+$total4;?></td>		 
							<td  align="left"><?php if($row1_ref_20->total) print($row1_ref_20->total); else print("0"); $total5=$row1_ref_20->total; @$totalg5=$totalg5+$total5;?></td>		 
							<td  align="left"><?php if($row1_ref_40->total) print($row1_ref_40->total); else print("0"); $total6=$row1_ref_40->total; @$totalg6=$totalg6+$total6;?></td>		 
							<td  align="left"><?php if($row1_dmg_20->total) print($row1_dmg_20->total); else print("0"); $total7=$row1_dmg_20->total; @$totalg7=$totalg7+$total7;?></td>		 
							<td  align="left"><?php if($row1_dmg_40->total) print($row1_dmg_40->total); else print("0"); $total8=$row1_dmg_40->total; @$totalg8=$totalg8+$total8;?></td>		 
							<td  align="left"><?php if($row1_tran_20->total) print($row1_tran_20->total); else print("0"); $total9=$row1_tran_20->total; @$totalg9=$totalg9+$total9;?></td>		 
							<td  align="left"><?php if($row1_tran_40->total) print($row1_tran_40->total); else print("0"); $total10=$row1_tran_40->total; @$totalg10=$totalg10+$total10;?></td>		 
							<td  align="left"><?php if($row1_45_ld->total) print($row1_45_ld->total); else print("0"); $total11=$row1_45_ld->total; @$totalg11=$totalg13+$total11;?></td>		 
							<td  align="left"><?php if($row1_45_mty->total) print($row1_45_mty->total); else print("0"); $total12=$row1_45_mty->total; @$totalg12=$totalg14+$total12;?></td>		 
							<?php $grand20=$total1+$total3+$total5+$total7+$total9; ?>
							<?php $grand40=$total2+$total4+$total6+$total8+$total10+$total11+$total12; ?>
							<?php $grand=$total1+$total2+$total3+$total4+$total5+$total6+$total7+$total8+$total9+$total10+$total11+$total12; ?>
							
					 		
							<td align="left"><?php if($grand20) print($grand20); else print("0"); ?></td>
							<td align="left"><?php if($grand40) print($grand40); else print("0"); ?></td>
							<td align="center"><?php if($grand) print($grand); else print("0"); ?></td>
							
							<?php 
							if($countBillRow>0) 
							{ 
							?>
							<td align="center">
								<form name= "billForm" onsubmit="" action="<?php echo site_url("report/viewContainerBill");?>" method="post" target="_blank">
									<input type="hidden" value="<?php if($rtnBillData[$i]['draft_id']) echo $rtnBillData[$i]['draft_id']; else echo " "; ?>" name="draftNumber" > 
									<input type="hidden" value="<?php if($rtnBillData[$i]['pdf_draft_view_name']) echo $rtnBillData[$i]['pdf_draft_view_name']; else echo " "; ?>" name="draft_view" > 
									<input type="submit" class="login_button" style="margin-top: 15%;" value="VIEW BILL"/>
								</form>
							</td>
							<td align="center">
								<form name= "dtlForm" onsubmit="" action="<?php echo site_url("report/viewContainerDetail");?>" method="post" target="_blank">
									<input type="hidden" value="<?php if($rtnBillData[$i]['draft_id']) echo $rtnBillData[$i]['draft_id']; else echo " "; ?>" name="draftNumberDetail" > 
									<input type="hidden" value="<?php if($rtnBillData[$i]['pdf_draft_view_name']) echo $rtnBillData[$i]['pdf_draft_view_name']; else echo " "; ?>" name="draft_detail_view" > 
									<input type="submit" class="login_button" style="margin-top: 15%;" value="VIEW DETAIL"/>
								</form>
							</td>
						<?php 
						} 
						?>
						</tr>
					 <?php $i++; } ?>
				
					<!--/tr-->

						<tr>
							<td align="center"><b>Grand Total</b></td>
							<td><?php print("&nbsp;");?></td>
							<td><?php print("&nbsp;");?></td>
							<td><?php print("&nbsp;");?></td>
							
							<td><b><?php print($totalg1);?></b></td>
							<td><b><?php print($totalg2);?></b></td>
							<td><?php print($totalg3);?></td>
							<td><?php print($totalg4);?></td>
							<td><?php print($totalg5);?></td>
							<td><?php print($totalg6);?></td>
							<td><?php print($totalg7);?></td>
							<td><?php print($totalg8);?></td>
							<td><?php print($totalg9);?></td>
							<td><?php print($totalg10);?></td>
							<td><?php print($totalg11);?></td>
							<td><?php print($totalg12);?></td>
							<?php $total20Feet= $totalg1+$totalg3+$totalg5+$totalg7+$totalg9;?>
							<td><?php print($total20Feet);?></td>
							<?php $total40Feet= $totalg2+$totalg4+$totalg6+$totalg8+$totalg10+$totalg11+$totalg12;?>
							<td><?php print($total40Feet);?></td>
							<td align="center"><b><?php print($sum+$grand);?></b></td>                    
						</tr>				
					</table>						
				</TD>
			</TR>
		</TABLE>
	<?php  }?>
	
	
	
<?php 
mysqli_close($con_cchaportdb);
if(@$_POST['options']=='html'){?>		
	</BODY>
</HTML>
<?php }?>

