<html>
	<head>
		<title>Chittagong Port Authority</title>
		<link rel="shortcut icon" href="<?php echo IMG_PATH; ?>cpa.png" />
	</head>
	<body>
		<table width="72%" align="center" border="0">
			<tr>
				<td height="80px" bgcolor="#F9FAFB" >
					<img src="<?php echo IMG_PATH;?>logo_cpa.gif" />
				</td>
			</tr>
			<tr>
				<td height="50px"  >
				<?php $cont_id=$_POST['containerLocation']; //echo $cont_id;?>	
					<table WIDTH="95%" align="center">
						<tr>
							<td WIDTH="20%" ALIGN="left">Container No</td>
							<td ALIGN="CENTER">Location Description</td>
						</tr>
				<?php
					include("mydbPConnectionn4.php");
					$strCat = "select category from sparcsn4.inv_unit where id='$cont_id' order by sparcsn4.inv_unit.gkey desc limit 1";
					$resCat = mysqli_query($con_sparcsn4,$strCat);
					$cat="";
					$block="CPA";			
					while($rowCat = mysqli_fetch_object($resCat))
					{
						$cat=$rowCat->category;
					}
					
					// 22 June 2021
					$sql_contDest = "SELECT desti.destination,(SELECT ctmsmis.offdoc.name FROM ctmsmis.offdoc WHERE id=desti.destination) AS dest_name				
					FROM sparcsn4.inv_unit inv  
					INNER JOIN sparcsn4.inv_goods desti ON desti.gkey=inv.goods
					WHERE inv.id ='$cont_id'
					ORDER BY inv.gkey DESC
					LIMIT 1";
					$res_contDest = mysqli_query($con_sparcsn4,$sql_contDest);
					$contDest = "";
					$contDestName = "";
					while($row_contDest = mysqli_fetch_object($res_contDest))
					{
						$contDest = $row_contDest->destination;
						$contDestName = $row_contDest->dest_name;
					}
					
					echo $offdockCode;
					
					//CONCAT('Position : ',CONVERT(fcyVisit.last_pos_name USING utf8),',Vessel Name : ',sparcsn4.vsl_vessels.name,',Category : ',inv.category)
			
					if($cat=="IMPRT")
					{						
						if($contDest == $offdockCode)
						{							
							$sql="SELECT inv.id,fcyVisit.transit_state,fcyVisit.time_in,
							 
							  (CASE WHEN   
								 fcyVisit.last_pos_loctype ='YARD'   
									THEN   
								 CONCAT('Yard:',IFNULL(ctmsmis.cont_yard(fcyVisit.last_pos_slot),''), 
										  ',Block:',IFNULL(ctmsmis.cont_block(fcyVisit.last_pos_slot,ctmsmis.cont_yard(fcyVisit.last_pos_slot)),''),  
												 ',Pos:',CONVERT(fcyVisit.last_pos_slot USING utf8), 
										  ',Ctgry:',inv.category,',Status:',inv.freight_kind, 
										  ',Dest:',IFNULL(desti.destination,''), 
										  ',MLO:',g.id,
										  ',ISO:',IFNULL(sparcsn4.ref_equip_type.id,''), 
										  ',AssnType:',IFNULL((SELECT sparcsn4.inv_unit.flex_string01 FROM sparcsn4.inv_unit WHERE sparcsn4.inv_unit.gkey=inv.gkey),''), 
										  ',AssnDate:',IFNULL(fcyVisit.flex_date01,''),  
										  ',DisTime:',ifnull(fcyVisit.time_in,''),
										', Height : ',
								right(sparcsn4.ref_equip_type.nominal_height,2)/10,', Size : ',right(sparcsn4.ref_equip_type.nominal_length,2),',ISO Code : ',sparcsn4.ref_equip_type.id
										  )   
								   WHEN   
									   fcyVisit.last_pos_loctype ='VESSEL'    
								   THEN  
								  CONCAT('Position : ',IFNULL(CONVERT(fcyVisit.last_pos_name USING utf8),''),',Vessel Name : ',sparcsn4.vsl_vessels.name,',Category : ',inv.category,', MLO : ',g.id,', Height : ',
								right(sparcsn4.ref_equip_type.nominal_height,2)/10,', Size : ',right(sparcsn4.ref_equip_type.nominal_length,2),',ISO Code : ',sparcsn4.ref_equip_type.id)
								   ELSE 
								IFNULL(CONCAT('Position : ',CONVERT(fcyVisit.last_pos_name USING utf8),',Vessel Name : ',sparcsn4.vsl_vessels.name,',Category : ',inv.category,',
												MLO : ',g.id,', Height : ',
								right(sparcsn4.ref_equip_type.nominal_height,2)/10,', Size : ',right(sparcsn4.ref_equip_type.nominal_length,2),',ISO Code : ',sparcsn4.ref_equip_type.id),'NO CONTAINER FOUND')  
							   
								END) 
							
							 
							AS dsc,
							IFNULL(ctmsmis.cont_block(fcyVisit.last_pos_slot,ctmsmis.cont_yard(fcyVisit.last_pos_slot)),'') as block				
							 
							FROM sparcsn4.inv_unit inv  
							inner join sparcsn4.inv_unit_fcy_visit fcyVisit on fcyVisit.unit_gkey=inv.gkey
							INNER JOIN sparcsn4.argo_carrier_visit ON (argo_carrier_visit.gkey=inv.declrd_ib_cv or argo_carrier_visit.gkey=inv.cv_gkey)
							INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
							INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
							inner join sparcsn4.vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
							inner join sparcsn4.vsl_vessel_classes on vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey
							INNER JOIN sparcsn4.inv_unit_equip ON inv.gkey=inv_unit_equip.unit_gkey 
							inner join  sparcsn4.ref_bizunit_scoped g ON inv.line_op = g.gkey
							INNER JOIN sparcsn4.ref_equipment ON inv_unit_equip.eq_gkey=ref_equipment.gkey
							INNER JOIN sparcsn4.ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
							inner join sparcsn4.inv_goods desti on desti.gkey=inv.goods
							WHERE inv.id ='$cont_id' ORDER BY inv.gkey DESC LIMIT 1";
						}
						else
						{							
							$sql="SELECT inv.id,fcyVisit.transit_state,fcyVisit.time_in,inv.freight_kind,
							 
							  (CASE WHEN   
										fcyVisit.last_pos_loctype ='YARD'   
									THEN   
										CONCAT('<b>','Position:','Inside Port','</b><br>',		
										', MLO : ',g.id,' , DisTime:',IFNULL(fcyVisit.time_in,''))  
								   WHEN   
									   fcyVisit.last_pos_loctype ='VESSEL'    
								   THEN  
								  CONCAT('Position : ',IFNULL(CONVERT(fcyVisit.last_pos_name USING utf8),''),'<br>',',Vessel Name : ',sparcsn4.vsl_vessels.name,',Category : ',inv.category,', MLO : ',g.id,', Height : ',
								right(sparcsn4.ref_equip_type.nominal_height,2)/10,', Size : ',right(sparcsn4.ref_equip_type.nominal_length,2),',ISO Code : ',sparcsn4.ref_equip_type.id)
								   ELSE 
								IFNULL(CONCAT('Position : ',CONVERT(fcyVisit.last_pos_name USING utf8),'<br>',',Vessel Name : ',sparcsn4.vsl_vessels.name,',Category : ',inv.category,',
												MLO : ',g.id,', Height : ',
								right(sparcsn4.ref_equip_type.nominal_height,2)/10,', Size : ',right(sparcsn4.ref_equip_type.nominal_length,2),',ISO Code : ',sparcsn4.ref_equip_type.id),'NO CONTAINER FOUND')  
							   
								END) 
							
							 
							AS dsc,
							IFNULL(ctmsmis.cont_block(fcyVisit.last_pos_slot,ctmsmis.cont_yard(fcyVisit.last_pos_slot)),'') as block				
							 
							FROM sparcsn4.inv_unit inv  
							inner join sparcsn4.inv_unit_fcy_visit fcyVisit on fcyVisit.unit_gkey=inv.gkey
							INNER JOIN sparcsn4.argo_carrier_visit ON (argo_carrier_visit.gkey=inv.declrd_ib_cv or argo_carrier_visit.gkey=inv.cv_gkey)
							INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
							INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
							inner join sparcsn4.vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
							inner join sparcsn4.vsl_vessel_classes on vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey
							INNER JOIN sparcsn4.inv_unit_equip ON inv.gkey=inv_unit_equip.unit_gkey 
							inner join  sparcsn4.ref_bizunit_scoped g ON inv.line_op = g.gkey
							INNER JOIN sparcsn4.ref_equipment ON inv_unit_equip.eq_gkey=ref_equipment.gkey
							INNER JOIN sparcsn4.ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
							inner join sparcsn4.inv_goods desti on desti.gkey=inv.goods
							WHERE inv.id ='$cont_id' ORDER BY inv.gkey DESC LIMIT 1";
						}
										
					}
					else if($cat=="EXPRT") 
					{
						$sql="
						SELECT inv.id, fcyVisit.transit_state,freight_kind,
						 
						  (CASE WHEN   
							 fcyVisit.last_pos_loctype ='YARD'   
								THEN   
							 CONCAT('Yard:',IFNULL(ctmsmis.cont_yard(fcyVisit.last_pos_slot),''),  
							 ', Block:',IFNULL(ctmsmis.cont_block(fcyVisit.last_pos_slot,ctmsmis.cont_yard(fcyVisit.last_pos_slot)),''), 
							 ',Position:',IFNULL(CONVERT(fcyVisit.last_pos_slot USING utf8),''),'<br>',
							 ', MLO:',g.id,',Status : ',inv.freight_kind,
							 ',Category:',inv.category,',Gate In:',ifnull(fcyVisit.time_in,''),',
							 Height : ',
							right(sparcsn4.ref_equip_type.nominal_height,2)/10,', Size : ',right(sparcsn4.ref_equip_type.nominal_length,2),',ISO Code : ',sparcsn4.ref_equip_type.id) 
							   WHEN   
								   fcyVisit.last_pos_loctype ='VESSEL'    
							   THEN  
								CONCAT('Position : ',IFNULL(CONVERT(fcyVisit.last_pos_name USING utf8),''),'<br>',',Vessel Name : ',sparcsn4.vsl_vessels.name,', Category : ',inv.category,', Load Time : ',ifnull(fcyVisit.time_load,''),', MLO : ',g.id,', Status : ',inv.freight_kind,', Height : ',
							right(sparcsn4.ref_equip_type.nominal_height,2)/10,', Size : ',right(sparcsn4.ref_equip_type.nominal_length,2),', ISO Code : ',sparcsn4.ref_equip_type.id)
							   ELSE 
							CONCAT('PRE ADVISED : ',IFNULL(CONVERT(fcyVisit.last_pos_name USING utf8),''), 
								   ',Category : ',CONCAT (inv.category ,', ', 
									 IFNULL((SELECT CONCAT (CASE WHEN sub_type='DE' THEN 'Dray Off'   
									WHEN sub_type='DI' THEN 'Delivery Import'   
									WHEN sub_type='DM' THEN 'Delivery EMPTY'   
									WHEN sub_type='RE' THEN 'INBOUND'   
							   END  ,' to Offdock :', NAME) AS d FROM sparcsn4.road_truck_transactions   
							   inner JOIN sparcsn4.ref_bizunit_scoped ON road_truck_transactions.trkco_id=ref_bizunit_scoped.id   
							   WHERE unit_gkey=inv.gkey LIMIT 1),'') 
								 ),',MLO : ',g.id,',Status : ',inv.freight_kind,', Height : ',
							right(sparcsn4.ref_equip_type.nominal_height,2)/10,', Size : ',right(sparcsn4.ref_equip_type.nominal_length,2),',ISO Code : ',sparcsn4.ref_equip_type.id   
								 )   
						   
							END) 
						AS dsc  ,
						IFNULL(ctmsmis.cont_block(fcyVisit.last_pos_slot,ctmsmis.cont_yard(fcyVisit.last_pos_slot)),'') as block
						 
						FROM sparcsn4.inv_unit inv  

						inner join sparcsn4.inv_unit_fcy_visit fcyVisit on fcyVisit.unit_gkey=inv.gkey
						INNER JOIN sparcsn4.argo_carrier_visit ON (argo_carrier_visit.gkey=inv.declrd_ib_cv or argo_carrier_visit.gkey=inv.cv_gkey)
						INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
						INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
						inner join sparcsn4.vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
						INNER JOIN sparcsn4.inv_unit_equip ON inv.gkey=inv_unit_equip.unit_gkey 
						inner join  sparcsn4.ref_bizunit_scoped g ON inv.line_op = g.gkey
						INNER JOIN sparcsn4.ref_equipment ON inv_unit_equip.eq_gkey=ref_equipment.gkey
						INNER JOIN sparcsn4.ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
						inner join sparcsn4.inv_goods desti on desti.gkey=inv.goods
						WHERE inv.id ='$cont_id' ORDER BY inv.gkey DESC LIMIT 1";
					}					
					else
					{
						$sql="SELECT inv.id, fcyVisit.transit_state,
						CONCAT('Position:',fcyVisit.last_pos_name,'<br>',
						',Category:',inv.category,
						',Freight Kind:',inv.freight_kind, 
						',Time Move:',ifnull(fcyVisit.time_move,''),', MLO : ',g.id,', Height : ',
						right(sparcsn4.ref_equip_type.nominal_height,2)/10,', Size : ',right(sparcsn4.ref_equip_type.nominal_length,2),',ISO Code : ',sparcsn4.ref_equip_type.id)       
						AS dsc,'' AS block 
						FROM sparcsn4.inv_unit inv  
						inner join sparcsn4.inv_unit_fcy_visit fcyVisit on fcyVisit.unit_gkey=inv.gkey
						INNER JOIN sparcsn4.inv_unit_equip ON inv.gkey=inv_unit_equip.unit_gkey 
						inner join  sparcsn4.ref_bizunit_scoped g ON inv.line_op = g.gkey
						INNER JOIN sparcsn4.ref_equipment ON inv_unit_equip.eq_gkey=ref_equipment.gkey
						INNER JOIN sparcsn4.ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
						inner join sparcsn4.inv_goods desti on desti.gkey=inv.goods
						WHERE inv.id ='$cont_id' ORDER BY inv.gkey DESC LIMIT 1";
							
						// $sql="SELECT inv.id, fcyVisit.transit_state,fcyVisit.time_in,fcyVisit.time_out,
						// CONCAT('Position:',IF(fcyVisit.time_in IS NOT NULL AND fcyVisit.time_out IS NULL,'Inside Port',fcyVisit.last_pos_name),
						// ',Category:',inv.category,
						// ',Freight Kind:',inv.freight_kind, 
						// ',Time Move:',ifnull(fcyVisit.time_move,''),', MLO : ',g.id,', Height : ',
						// right(sparcsn4.ref_equip_type.nominal_height,2)/10,', Size : ',right(sparcsn4.ref_equip_type.nominal_length,2),',ISO Code : ',sparcsn4.ref_equip_type.id)       
						// AS dsc,'' AS block 
						// FROM sparcsn4.inv_unit inv  
						// inner join sparcsn4.inv_unit_fcy_visit fcyVisit on fcyVisit.unit_gkey=inv.gkey
						// INNER JOIN sparcsn4.inv_unit_equip ON inv.gkey=inv_unit_equip.unit_gkey 
						// inner join  sparcsn4.ref_bizunit_scoped g ON inv.line_op = g.gkey
						// INNER JOIN sparcsn4.ref_equipment ON inv_unit_equip.eq_gkey=ref_equipment.gkey
						// INNER JOIN sparcsn4.ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
						// inner join sparcsn4.inv_goods desti on desti.gkey=inv.goods
						// WHERE inv.id ='$cont_id' ORDER BY inv.gkey DESC LIMIT 1";
					}		
												
					//echo $sql;
					 $result=mysqli_query($con_sparcsn4,$sql);
					// echo mysql_num_rows($result);
					 //$row=mysqli_fetch_object($result);
					// echo $row->id."=".$row->dsc."<hr>";
					
					$freightKind = "";
					$dsc = "";
					$blockVal = "";
					while($row=mysqli_fetch_object($result))
					{
						@$freightKind=$row->freight_kind;
						$dsc=$row->dsc;
						$blockVal=$row->block;
					}
						
					$evt = 0;
					if($freightKind=="LCL")
					{
						$sql_stripped = "SELECT 
						(SELECT COUNT(*) FROM sparcsn4.srv_event WHERE sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND event_type_gkey=30) AS rtnValue
						FROM sparcsn4.inv_unit WHERE id='$cont_id' AND  category='IMPRT' ORDER BY sparcsn4.inv_unit.gkey DESC LIMIT 1";
						$rslt_stripped = mysqli_query($con_sparcsn4,$sql_stripped);
						
						while($row_stripped=mysqli_fetch_object($rslt_stripped))
						{
							$evt=$row_stripped->rtnValue;
						}
					}
							
					$stripSt="";
					if($evt==1)
					{
						$stripSt = "Stripped";
					}							
					
					$ipAddress=$this->ip_address = $_SERVER['REMOTE_ADDR'];
					//date_default_timezone_set("Asia/Dhaka");
					$s2=date("Y-m-d H:i:s");
					$data="$cont_id |$ipAddress |$s2 \n";
					write_file("ContainerSearch.txt", $data, 'a');
							 
					?>
						<tr>
							<td  WIDTH="20%" ALIGN="LEFT"><?php echo strtoupper($cont_id); ?></td>
							<td ALIGN="CENTER"> 
							
								<?php
									if($cat!=""){
										if($dsc!=NULL && $blockVal!=NULL && $blockVal!='Y6') 
										{	
											
											echo $stripSt." ".$dsc;
											@$block=$row->block;
										}
										else if($dsc!=NULL && $blockVal=='Y6') 
										{	
											
											echo $stripSt." ".$dsc;
											$block="HS6";
										}
										else 
										{ 
											
											echo $stripSt." ".$dsc;
											$block="CPA";
											echo "&nbsp";
										}
									}
								?>
							</td>
							
						</tr>
						
						<!--tr>
							<td  WIDTH="20%" ALIGN="LEFT"></td>
							<td align="center">
                                <table border="1" cellspacing="0" cellpadding="0" bgcolor="#B5EFF0">
                                    <tr><td align="center" >
                                            <font color="black">&nbsp;You can get Container Location by sending SMS to <b>2777</b><br/>&nbsp;SMS Format: cont < space > Container No<br/>&nbsp;EXAMPLE: cont WHLU2317382<br/>&nbsp; (This is on TEST BASIS)</font>
                                    </td></tr></table>
                            </td>
						</tr-->
						
					</table>
				</td>
			</tr>
			<tr>
				<!--td height="80px" bgcolor="#F9FAFB" >
					<img src="<?php echo IMG_PATH;?>datasoft_logo.gif" align="left" height="30px"/> <img src="<?php echo IMG_PATH;?>stlogo.gif" align="right"/> <ST logo not shown by Sumon>
				</td-->
				
			</tr>
			<tr>
				<td height="10px"  ></td>
				
			</tr>
			<tr>
				<td ><hr/></td>
			</tr>
			<?php
			mysqli_close($con_sparcsn4);
			include_once("mydbPConnection.php");
			$sql1="SELECT igm_masters.Import_Rotation_No,Vessel_Name,Voy_No,Port_of_Shipment,igm_masters.file_clearence_date,(SELECT CONCAT(IFNULL(Organization_Name,' '),' ',IFNULL(Address_1,' '),' ',IFNULL(Address_2,' ')) FROM organization_profiles WHERE organization_profiles.id=igm_detail_container.off_dock_id) AS offdock 
			FROM igm_detail_container 
			INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
			WHERE cont_number='$cont_id'
			ORDER BY igm_detail_container.id DESC 
			LIMIT 1"; //file_clearence_date,
			//echo "SELECT igm_masters.Import_Rotation_No,Vessel_Name,Voy_No,Port_of_Shipment,igm_masters.file_clearence_date,(SELECT CONCAT(IFNULL(Organization_Name,' '),' ',IFNULL(Address_1,' '),' ',IFNULL(Address_2,' ')) FROM organization_profiles WHERE organization_profiles.id=igm_detail_container.off_dock_id) AS offdock FROM igm_detail_container INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id WHERE cont_number='$cont_id'";
			$abc = mysqli_query($con_cchaportdb,$sql1);
			$row2=mysqli_fetch_object($abc);
			//echo mysql_num_rows($sql1);
			
			$Vessel_Name = "";
			$Import_Rotation_No = "";
			$Voy_No = "";
			$file_clearence_date = "";
			$Port_of_Shipment = "";
			$Port_of_Shipment = "";
			$offdock = "";
			if(count($row2)>0)
			{
				$Vessel_Name = $row2->Vessel_Name;
				$Import_Rotation_No = $row2->Import_Rotation_No;
				$Voy_No = $row2->Voy_No;
				$file_clearence_date = $row2->file_clearence_date;
				$Port_of_Shipment = $row2->Port_of_Shipment;
				$Port_of_Shipment = $row2->Port_of_Shipment;
				$offdock = $row2->offdock;
			}
			
			?>
			
			<?php
			// echo $contDest;
			// echo $offdockCode;			
			// if($contDest == $offdockCode)
			// {
			?>	
			<tr>
				<td bgcolor="#B5EFF0">
					<table width="100%"  align="center" border="0">			
						<tr>
							<td ><b>Detail Information</b></td>
						</tr>
						<tr>
							<td width="20%">Container No</td><td>:</td><td ><b><?php echo strtoupper($cont_id); ?></b></td>
						</tr>
						<tr>
							<td width="20%">Last Vessel Name</td><td>:</td><td ><b><?php if($cat == ""){echo "";}else{echo $Vessel_Name;}?></b></td>
						</tr>
						<tr>
							<td width="20%">Import Rotation No</td><td>:</td><td ><b><?php if($cat == ""){echo "";}else{echo $Import_Rotation_No; }?></b></td>
						</tr>
						<tr>
							<td width="20%">Voy No</td><td>:</td><td ><b><?php if($cat == ""){echo "";}else{echo $Voy_No; }?></b></td>
						</tr>
						<tr>
							<td width="20%">Vessel Arrival Date(Estimated)</td><td>:</td><td ><b><?php if($cat == ""){echo "";}else{echo  $file_clearence_date; }?></b></td>
						</tr>
						<tr>
							<td width="20%">Port of Shipment</td><td>:</td><td ><b><?php if($cat == ""){echo "";}else{echo  $Port_of_Shipment; }?></b></td>
						</tr>
						<tr>
							<!--td width="20%">Port/Offdock</td><td>:</td><td ><b><?php if($cat == ""){echo "";}else{echo  $offdock; }?></b></td-->
							<td width="20%">Port/Offdock</td><td>:</td><td ><b><?php if($cat == ""){echo "";}else{echo  $contDestName; }?></b></td>
						</tr>
						<?php 
							if($cat == "IMPRT")
							{
						?>
							<tr>
								<td width="20%">Destination</td><td>:</td><td ><b><?php echo $contDest; ?></b></td>
							</tr>
						<?php
							}
						?>
					</table>
				</td>
			</tr>	
			<?php
			// }
			?>
			<?php mysqli_close($con_cchaportdb);?>
					
				
			
			<tr>
				<td ><hr/></td>
			</tr>
			
			<tr>
			        <!--here real image is: containerloc.png-->
				<td ><!--img src="<?php echo IMG_PATH; ?>locationimage/<?php echo $block?>.jpg" width="1050" height="500" alt="" /--></td>
			</tr>
		</table>
		
	</body>
</html>