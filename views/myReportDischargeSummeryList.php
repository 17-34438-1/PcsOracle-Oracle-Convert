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
						<tr><td colspan="2" align="center"><h1> FEEDER SUMMARY LIST</h1></td></tr>
											
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
							<th colspan='3' align="center"><span class="style1" >LADEN</span></th>
							<th colspan='3' align="center"><span class="style1" >EMPTY</span></th>					
							<th colspan='3' align="center"><span class="style1" >REFFER</span></th>
							<th colspan='3' align="center"><span class="style1" >IMDG</span></th>						
							<th colspan='3' align="center"><span class="style1" >TRANS</span></th>			
							<th colspan='3' align="center"><span class="style1" >ICD</span></th>						
							<th colspan='2' align="center"><span class="style1" >45'</span></th>						
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
							<td  align="center"><?php print("&nbsp;");?></td>
 							<td  align="center"><?php print("&nbsp;");?></td>
 							<td  align="center"><?php print("&nbsp;");?></td>
							<td  align="center"><?php  print("20");?></td>
							<td  align="center"><?php print("40");?></td>
							<td  align="center"><?php print("TEUs");?></td>
							<td  align="center"><?php  print("20");?></td>
							<td  align="center"><?php print("40");?></td>
							<td  align="center"><?php print("TEUs");?></td>
							<td  align="center"><?php  print("20");?></td>
							<td  align="center"><?php print("40");?></td>
							<td  align="center"><?php print("TEUs");?></td>
							<td  align="center"><?php  print("20");?></td>
							<td  align="center"><?php print("40");?></td>
							<td  align="center"><?php print("TEUs");?></td>
							<td  align="center"><?php  print("20");?></td>
							<td  align="center"><?php print("40");?></td>
							<td  align="center"><?php print("TEUs");?></td>
							<td  align="center"><?php  print("20");?></td>
							<td  align="center"><?php  print("40");?></td>
							<td  align="center"><?php print("TEUs");?></td>
							<td  align="center"><?php print("LD");?></td>
							<td  align="center"><?php  print("MT");?></td>							
						</tr>
							
						<?php
						$str="select distinct submitee_org_id,organization_profiles.Organization_Name as Organization_Name,organization_profiles.Agent_Code,mlocode as mlocode from igm_details 
						left join organization_profiles on igm_details.Submitee_Org_Id=organization_profiles.id 
						where Import_Rotation_No='$rotation' order by Organization_Name,mlocode";
						
						$result=mysqli_query($con_cchaportdb,$str);
						$i=0;
						$totalteu1 = 0;
						$totalteu2 = 0;
						$totalteu3 = 0;
						$totalteu4 = 0;
						$totalteu5 = 0;
						$totalteu6 = 0;
						
						$total1 = 0;
						$total2 = 0;
						$total3 = 0;
						$total4 = 0;
						$total5 = 0;
						$total6 = 0;
						$total7 = 0;
						$total8 = 0;
						$total9 = 0;
						$total10 = 0;
						$total11 = 0;
						$total12 = 0;
						$total13 = 0;
						$total14 = 0;
						
						
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
							<td  align="left"><?php if(@$row_agent_code->mlo_agent_code_ctms) print($row_agent_code->mlo_agent_code_ctms); else print("&nbsp;");
								?>
							</td>
							<td  align="left"><?php if($row->mlocode) print($row->mlocode); else print("&nbsp;");?></td>
							<?php
//FCL-20"
							$str1="select count(distinct cont_number) as total 
							from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') and type_of_igm<>'TS' 
                            and off_dock_id <>'2592' and cont_status not in ('EMT','EMPTY','MT','ETY') and cont_size =20 and  igm_details.final_submit=1";	
							
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
			//imdg			
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') and type_of_igm<>'TS' 
							and off_dock_id<>'2592'  and cont_size =20 and (cont_imo <> '' and cont_un <> '' and igm_details.final_submit=1)";	
							$result1_dmg=mysqli_query($con_cchaportdb,$str1);
							$row1_dmg=mysqli_fetch_object($result1_dmg);

							?>
							<td  align="left"><?php if($row1->total) print($row1->total-$row1_dmg->total); else print("0"); $total1=$row1->total-$row1_dmg->total; @$totalg1=$totalg1+$total1;?></td>
							<?php
//FCL-40"
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') and type_of_igm<>'TS' 
                            and off_dock_id<>'2592' and cont_status not in ('EMT','EMPTY','MT','ETY') and cont_size =40 and igm_details.final_submit=1";
//print("<br>".$str1);		
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
		//imdg							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and type_of_igm<>'TS' and off_dock_id<>'2592' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')
							and  cont_size =40 and (cont_imo <> '' or cont_un <> '') and igm_details.final_submit=1";	
							$result1_dmg=mysqli_query($con_cchaportdb,$str1);
							$row1_dmg=mysqli_fetch_object($result1_dmg);
							?>
							 <td align="left">
								<?php 
									if($row1->total) print($row1->total-$row1_dmg->total); else print("0"); 
									$total2=$row1->total-$row1_dmg->total; @$totalg2=$totalg2+$total2;
								?>
							</td>
							 
							<td align="left">
								<?php
									echo $teu1 = ($total1*1)+($total2*2);
									$totalteu1+=$teu1;
								?>
							</td>
							
							
							<?php
//Empty-20"
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')and type_of_igm<>'TS' 
                            and off_dock_id<>'2592' and   (cont_status='EMT' or cont_status='Empty' or cont_status='MT' or cont_status='ETY') and cont_size =20 and (cont_imo = '' and cont_un = '' and igm_details.final_submit=1)";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td  align="left"><?php if($row1->total) print($row1->total); else print("0"); $total3=$row1->total; @$totalg3=$totalg3+$total3;?></td>		 
							<?php
//Empty-40"
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') and type_of_igm<>'TS' 
                            and off_dock_id<>'2592' and  (cont_status='EMT' or cont_status='Empty' or cont_status='MT' or cont_status='ETY') and cont_size =40 and (cont_imo = '' and cont_un = '' and igm_details.final_submit=1)";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td  align="left"><?php if($row1->total) print($row1->total); else print("0"); $total4=$row1->total; @$totalg4=$totalg4+$total4;?></td>
							 
							<td align="left">
								<?php
									echo $teu2 = ($total3*1)+($total4*2);
									$totalteu2+=$teu2;
								?>
							</td>
						<?php
//Reefer - 20"
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type like '%R%' and cont_iso_type not in ('DRY') and type_of_igm<>'TS' 
                            and off_dock_id<>'2592'  and cont_size =20 and igm_details.final_submit=1";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td  align="left"><?php if($row1->total) print($row1->total); else print("0"); $total5=$row1->total; @$totalg5=$totalg5+$total5;?></td>		 
						<?php
//Reefer - 40
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type like '%R%' and cont_iso_type not in ('DRY') and type_of_igm<>'TS' 
                            and off_dock_id<>'2592'  and cont_size =40 and igm_details.final_submit=1";	
							//print($str1."<br>");
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td  align="left"><?php if($row1->total) print($row1->total); else print("0"); $total6=$row1->total; @$totalg6=$totalg6+$total6;?></td>
							 
							<td align="left">
								<?php
									echo $teu3 = ($total5*1)+($total6*2);
									$totalteu3+=$teu3;
								?>
							</td>
						<?php
//IMDG 20"
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_type not in ('REFER','REEFER')and type_of_igm<>'TS' 
                            and off_dock_id<>'2592'  and cont_size =20 and (cont_imo <> '' and cont_un <> '' and igm_details.final_submit=1)";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td  align="left"><?php if($row1->total) print($row1->total); else print("0"); $total7=$row1->total; @$totalg7=$totalg7+$total7;?></td>		 
						<?php

//IMDG 40"

							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and type_of_igm<>'TS' and off_dock_id<>'2592' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')
                            and  cont_size =40 and (cont_imo <> '' or cont_un <> '') and igm_details.final_submit=1";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td align="left">
								<?php 
									if($row1->total) print($row1->total); else print("0"); 
									$total8=$row1->total; @$totalg8=$totalg8+$total8;
								?>
							 </td>
							 
							 <td align="left">
								<?php
									echo $teu4 = ($total7*1)+($total8*2);
									$totalteu4+=$teu4;
								?>
							</td>
						<?php
//TS 20"
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and type_of_igm='TS' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') 
                            and off_dock_id<>'2592'  and cont_size =20 and igm_details.final_submit=1";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td align="left"><?php if($row1->total) print($row1->total); else print("0"); $total9=$row1->total; @$totalg9=$totalg9+$total9;?></td>		 
						<?php
//TS 40"
							/*$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							       where Import_Rotation_No='$rotation' and Submitee_Org_Id=$row->submitee_org_id and mlocode='$row->mlocode' and type_of_igm='TS' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')
                                   and off_dock_id<>'2592' and  cont_size =40 and igm_details.final_submit=1";*/	
							
							// Edited By Sourav Remove 45R1 from the condition
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and type_of_igm='TS' and cont_iso_type not in('22R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')
                            and off_dock_id<>'2592' and  cont_size =40 and igm_details.final_submit=1";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td  align="left"><?php if($row1->total) print($row1->total); else print("0"); $total10=$row1->total; @$totalg10=$totalg10+$total10;?></td>
							 
							<td align="left">
								<?php
									echo $teu5 = ($total9*1)+($total10*2);
									$totalteu5+=$teu5;
								?>
							</td>

							<?php
//OFFDock 20"							
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3') 
                            and off_dock_id='2592'  and cont_size =20 and igm_details.final_submit=1";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td  align="left"><?php if($row1->total) print($row1->total); else print("0"); $total11=$row1->total; @$totalg11=$totalg11+$total11;?></td>		 
						<?php
//OFFDock 40"	
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')
                            and off_dock_id='2592' and  cont_size =40 and igm_details.final_submit=1";	
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td align="left">
								<?php 
									if($row1->total) print($row1->total); else print("0"); 
									$total12=$row1->total; @$totalg12=$totalg12+$total12;
								?>
							</td>
							 
							 <td align="left">
								<?php
									echo $teu6 = ($total11*1)+($total12*2);
									$totalteu6+=$teu6;
								?>
							</td>

							 <?php
//Full  45"
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode'  and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')
                            and cont_size >40 and igm_details.final_submit=1 and (cont_status <> 'EMT' and cont_status <> 'Empty' and cont_status <> 'MT' and cont_status <> 'ETY')";	
							
		
							$result1=mysqli_query($con_cchaportdb,$str1);  
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td  align="left"><?php if($row1->total) print($row1->total); else print("0"); $total13=$row1->total; @$totalg13=$totalg13+$total13;?></td>		 
						<?php
//Empty 45"
							$str1="select count(distinct cont_number) as total from igm_detail_container inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id 
							where Import_Rotation_No='$rotation' and Submitee_Org_Id='$row->submitee_org_id' and mlocode='$row->mlocode' and cont_iso_type not in('22R1','45R1','45R0','25R1','45R3','22R0','42R1','45R8','20R1','22R9','42R0','22R2','20R0','45R4','22R7','42R3')
                            and cont_size >40 and igm_details.final_submit=1 and   (cont_status='EMT' or cont_status='Empty' or cont_status='MT' or cont_status='ETY') ";	

//print($str1."<br>");							
							$result1=mysqli_query($con_cchaportdb,$str1);
							$row1=mysqli_fetch_object($result1);
							
							?>
							 <td align="left">
								<?php 
									if($row1->total) print($row1->total); else print("0"); 
									$total14=$row1->total; 
									$totalg14=$totalg14+$total14;
								?>
							</td>	
							 
							<?php 
								$grand=$total1+$total2+$total3+$total4+$total5+$total6+$total7+
										$total8+$total9+$total10+$total11+$total12+$total13+$total14; 
							?>
							
					 		
			
							<td align="center">
								<!--?php echo $total1."+".$total2."+".$total3."+".$total4."+".$total5."+".$total6."+".$total7."+".
										$total8."+".$total9."+".$total10."+".$total11."+".$total12."+".$total13."+".$total14."<br>";
								?-->
								<?php if($grand) print($grand); else print("0"); ?>
							</td>
							
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
							<td><b><?php print($totalteu1);?></b></td>
							<td><b><?php print($totalg3);?></b></td>
							<td><b><?php print($totalg4);?></b></td>
							<td><b><?php print($totalteu2);?></b></td>
							<td><b><?php print($totalg5);?></b></td>
							<td><b><?php print($totalg6);?></b></td>
							<td><b><?php print($totalteu3);?></b></td>
							<td><b><?php print($totalg7);?></b></td>
							<td><b><?php print($totalg8);?></b></td>
							<td><b><?php print($totalteu4);?></b></td>
							<td><b><?php print($totalg9);?></b></td>
							<td><b><?php print($totalg10);?></b></td>
							<td><b><?php print($totalteu5);?></b></td>
							<td><b><?php print($totalg11);?></b></td>
							<td><b><?php print($totalg12);?></b></td>
							<td><b><?php print($totalteu6);?></b></td>
							<td><b><?php print($totalg13);?></b></td>
							<td><b><?php print($totalg14);?></b></td>
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

