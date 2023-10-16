
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
				<td height="50px">	
					<table WIDTH="95%" align="center">
						<tr>
							<td WIDTH="20%" ALIGN="left">Container No</td>
							<td ALIGN="CENTER">Location Description</td>
						</tr>
				<?php
					include("mydbPConnectionn4.php");
					include("dbOracleConnection.php");
					include("dbConection.php");
					
				    $strCat="SELECT category FROM inv_unit WHERE id='$cont_id' ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
					  
					
					$resCat= oci_parse($con_sparcsn4_oracle, $strCat);
					oci_execute($resCat);

		
					$cat="";
					$block="CPA";			
				

					while(($rowCat=oci_fetch_object($resCat))!=false)
					{
						 $cat=$rowCat->CATEGORY;	
						
					}
					
					
					$sql_contDest ="SELECT inv_goods.destination			
					FROM inv_unit   
					INNER JOIN inv_goods  ON inv_goods.gkey=inv_unit.goods 
					WHERE inv_unit.id ='$cont_id'
					ORDER BY inv_unit.gkey DESC
					fetch first 1 rows  only";
		
                    $res_contDest = oci_parse($con_sparcsn4_oracle,$sql_contDest);
                    oci_execute($res_contDest );

					$contDest = "";
					$contDestName = "";

					/*while(($row_contDest=oci_fetch_object($res_contDest))!=false)
					{
						$contDest = $row_contDest->DESTINATION;
					    $sql_contDest2="SELECT ctmsmis.offdoc.name FROM ctmsmis.offdoc WHERE id='$contDest'";
						
						$res_contDest2 = mysqli_query($con_sparcsn4,$sql_contDest2);
						$row_contDest2 = mysqli_fetch_object($res_contDest2);
				        $contDestName = $row_contDest2->name;
			
					}*/
					
					while(($row_contDest=oci_fetch_object($res_contDest))!=false)
					{
						$contDest = $row_contDest->DESTINATION;
					    $sql_contDest2="SELECT ctmsmis.offdoc.name FROM ctmsmis.offdoc WHERE id='$contDest'";
						$res_contDest2 = mysqli_query($con_sparcsn4,$sql_contDest2);
						 while($row_contDest2 = mysqli_fetch_object($res_contDest2)){
						  $contDestName = $row_contDest2->name;
						}
						//$row_contDest2 = mysqli_fetch_object($res_contDest2);
				     	//$contDestName = $row_contDest2->name;
					}

					//1/24/2023 test end
					
					//echo $offdockCode;

					
					
			
					if($cat=="IMPRT")
					{						
						if($contDest == $offdockCode)
						{		
							
							$sql="
							select inv.id,
							fcyVisit.transit_state,
							fcyVisit.time_in,(
							CASE WHEN fcyVisit.last_pos_loctype ='YARD'
							THEN ( 'Pos:'||CONVERT(fcyVisit.last_pos_slot , 'utf8')|| ',Ctgry:'||inv.category||',Status:'||inv.freight_kind|| ',Dest:'||NVL(desti.destination,'')|| ',MLO:'||g.id|| ',ISO:'||NVL(ref_equip_type.id,'')|| ',AssnType:'||NVL((SELECT inv_unit.flex_string01 FROM inv_unit WHERE inv_unit.gkey=inv.gkey),'')|| ',AssnDate:'||NVL(fcyVisit.flex_date01,'')||
							',DisTime:'||NVL(fcyVisit.time_in,'')|| ', Height : '|| substr(ref_equip_type.nominal_height,-2)/10 ||', Size : ' || substr(ref_equip_type.nominal_length,-2)||',ISO Code : '||ref_equip_type.id ) 
							WHEN 
							fcyVisit.last_pos_loctype ='VESSEL' 
							THEN ('Position : '|| NVL(CONVERT(fcyVisit.last_pos_name ,'utf8'),'')|| ',Vessel Name : '|| vsl_vessels.name|| ',Category : '|| inv.category|| ', MLO : '|| g.id|| ',
							Height : '|| substr(ref_equip_type.nominal_height,-2)/10|| ', Size : '|| substr(ref_equip_type.nominal_length,-2)|| ',ISO Code : '|| ref_equip_type.id) 
							ELSE 
							NVL(('Position : '|| CONVERT(fcyVisit.last_pos_name, 'utf8') || ',Vessel Name : ' || 
							vsl_vessels.name || ',Category : ' || inv.category || ', MLO : ' || g.id || ', Height : ' ||
							substr(ref_equip_type.nominal_height,-2)/10 || ', Size : ' || substr(ref_equip_type.nominal_length,-2) || ',ISO Code : ' ||
							ref_equip_type.id),'NO CONTAINER FOUND') END) AS dsc
							FROM inv_unit inv
							INNER JOIN inv_unit_fcy_visit fcyVisit ON fcyVisit.unit_gkey=inv.gkey
							INNER JOIN argo_carrier_visit ON (argo_carrier_visit.gkey=inv.declrd_ib_cv OR argo_carrier_visit.gkey=inv.cv_gkey)
							INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
							INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
							INNER JOIN vsl_vessel_classes ON vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey 
							INNER JOIN ref_bizunit_scoped g ON inv.line_op = g.gkey
							INNER JOIN ref_equipment ON inv.eq_gkey=ref_equipment.gkey 
							INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
							INNER JOIN inv_goods desti ON desti.gkey=inv.goods 
							WHERE inv.id ='$cont_id' ORDER BY inv.gkey DESC fetch FIRST 1 rows only
							";
						}
						else
						{			
				
							 $sql="SELECT inv.id,fcyVisit.transit_state,fcyVisit.time_in,inv.freight_kind,fcyVisit.last_pos_slot,

							(CASE WHEN   
							fcyVisit.last_pos_loctype ='YARD'   
							THEN   
							( 'Position:' ||  'Inside Port' || 		
							', MLO : ' ||  g.id ||  ' , DisTime:' ||  NVL(fcyVisit.time_in,'')
							)  
							WHEN   
							fcyVisit.last_pos_loctype ='VESSEL'    
							THEN  
							('Position : ' || NVL(CONVERT(fcyVisit.last_pos_name , 'utf8'),'')||
							',Vessel Name : ' || vsl_vessels.name || ',Category : ' || inv.category || ', MLO : ' || g.id || ', Height : ' ||
							substr(ref_equip_type.nominal_height,-2)/10 || ', Size : ' || substr(ref_equip_type.nominal_length,-2) || ',ISO Code : ' || ref_equip_type.id
							)
							ELSE 
							NVL(('Position : ' || CONVERT(fcyVisit.last_pos_name , 'utf8')|| 
							',Vessel Name : ' || vsl_vessels.name || ',Category : ' || inv.category || ',MLO : ' || g.id || ', Height : ' ||
							substr(ref_equip_type.nominal_height,-2)/10 || ', Size : '
							|| substr(ref_equip_type.nominal_length,-2) || ',ISO Code : ' || ref_equip_type.id),'NO CONTAINER FOUND')  

							END) 
							AS dsc    

							FROM inv_unit  inv
							INNER JOIN inv_unit_fcy_visit fcyVisit  ON fcyVisit.unit_gkey=inv.gkey
							INNER JOIN argo_carrier_visit ON (argo_carrier_visit.gkey=inv.declrd_ib_cv OR argo_carrier_visit.gkey=inv.cv_gkey)
							INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
							INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
							INNER JOIN vsl_vessel_classes ON vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey
							INNER JOIN  ref_bizunit_scoped g ON inv.line_op = g.gkey
							INNER JOIN ref_equipment ON inv.eq_gkey=ref_equipment.gkey
							INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
							INNER JOIN inv_goods  ON inv_goods.gkey=inv.goods
							WHERE inv.id ='$cont_id' ORDER BY inv.gkey DESC fetch FIRST 1 rows only";

						}
										
					}
					else if($cat=="EXPRT") 
					{
						$sql="SELECT inv.id, fcyVisit.transit_state,inv.freight_kind,fcyVisit.last_pos_slot,

						(CASE WHEN   
						fcyVisit.last_pos_loctype ='YARD'   
							THEN   
						(  
						'Position:' || NVL(CONVERT(fcyVisit.last_pos_slot , 'utf8'),'') ||
						', MLO:' || g.id || ',Status : ' || inv.freight_kind ||
						',Category:' || inv.category || ',Gate In:' || NVL(fcyVisit.time_in,'') || ', Height : ' ||
						substr(ref_equip_type.nominal_height,-2)/10 || ', Size : ' || substr(ref_equip_type.nominal_length,-2) || ',ISO Code : ' || ref_equip_type.id
						) 
						WHEN   
							fcyVisit.last_pos_loctype ='VESSEL'    
						THEN  
							('Position : ' || NVL(CONVERT(fcyVisit.last_pos_name , 'utf8'),'') || ',Vessel Name : ' || vsl_vessels.name || ' , Category : ' || inv.category || ', Load Time : ' || NVL(fcyVisit.time_load,'') ||', MLO : ' || g.id || ', Status : ' || inv.freight_kind || ', Height : ' ||
						substr(ref_equip_type.nominal_height,-2)/10 || ', Size : ' || substr(ref_equip_type.nominal_length,-2) || ', ISO Code : ' || ref_equip_type.id
						)ELSE 
						('PRE ADVISED : ' || NVL(CONVERT(fcyVisit.last_pos_name , 'utf8'),'') || ',Category : ' || (inv.category || ', ' || 
						NVL(
							(SELECT ( CASE WHEN sub_type='DE' THEN 'Dray Off'   
							WHEN sub_type='DI' THEN 'Delivery Import'   
							WHEN sub_type='DM' THEN 'Delivery EMPTY'   
							WHEN sub_type='RE' THEN 'INBOUND'   
							END  || ' to Offdock :' || NAME) AS d FROM road_truck_transactions   
							inner JOIN ref_bizunit_scoped ON road_truck_transactions.trkco_id=ref_bizunit_scoped.id   
							WHERE unit_gkey=inv.gkey Fetch first 1 rows only)
						,''
						)
							) ||  ',MLO : ' ||  g.id ||  ',Status : ' ||  inv.freight_kind || ', Height : ' || 
						substr(ref_equip_type.nominal_height,-2)/10 || ', Size : ' || substr(ref_equip_type.nominal_length,-2) || ',ISO Code : ' || ref_equip_type.id   
							)   
						
						
						END) 
						AS dsc 
						
						FROM inv_unit inv
						inner join inv_unit_fcy_visit fcyVisit on fcyVisit.unit_gkey=inv.gkey
						INNER JOIN argo_carrier_visit ON (argo_carrier_visit.gkey=inv.declrd_ib_cv or argo_carrier_visit.gkey=inv.cv_gkey)
						INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
						INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
						inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
						inner join  ref_bizunit_scoped g ON inv.line_op = g.gkey
						INNER JOIN ref_equipment ON inv.eq_gkey=ref_equipment.gkey
						INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
						inner join inv_goods desti on desti.gkey=inv.goods 
						WHERE inv.id ='$cont_id' ORDER BY inv.gkey DESC fetch FIRST 1 rows only";
					}					
					else
					{
						 $sql="SELECT inv.id, fcyVisit.transit_state,
						('Position:' || fcyVisit.last_pos_name ||
						',Category:' || inv.category ||
						',Freight Kind:' || inv.freight_kind ||
						',Time Move:' || NVL(fcyVisit.time_move,'') || ', MLO : ' || g.id || ', Height : ' ||
						substr(ref_equip_type.nominal_height,-2)/10 || ', Size : ' || substr(ref_equip_type.nominal_length,-2) || ',ISO Code : ' || ref_equip_type.id
						)       
						AS dsc,'' AS block 
						FROM inv_unit inv
						INNER JOIN inv_unit_fcy_visit fcyVisit ON fcyVisit.unit_gkey=inv.gkey
						INNER JOIN  ref_bizunit_scoped g ON inv.line_op = g.gkey
						INNER JOIN ref_equipment ON inv.eq_gkey=ref_equipment.gkey
						INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
						INNER JOIN inv_goods desti ON desti.gkey=inv.goods
						WHERE inv.id ='$cont_id' ORDER BY inv.gkey DESC fetch FIRST 1 rows only";
					}		
												
				
					$result = oci_parse($con_sparcsn4_oracle, $sql);
                    oci_execute($result );
					
					$freightKind = "";
					$dsc = "";
					$blockVal = "";
				


					while(($row=oci_fetch_object($result ))!=false)
					{

					
					     @$freightKind=$row->FREIGHT_KIND;
						 $dscValue=$row->DSC;
						 $last_pos_slot=$row->LAST_POS_SLOT;


						if($cat=="IMPRT"){
							
						
							if($contDest == $offdockCode){
						
							$sql2="SELECT IFNULL(ctmsmis.cont_block('$last_pos_slot',ctmsmis.cont_yard('$last_pos_slot')),'') AS block";
							$strQueryRes2 = mysqli_query($con_sparcsn4,$sql2);
						
							$blockVal="";
							$strQueryRow2=mysqli_fetch_object($strQueryRes2);
							$blockVal=$strQueryRow2->block;

							$sqlQu3="SELECT IFNULL(ctmsmis.cont_yard('$last_pos_slot'),'') AS yard";
							$strQueryRes3 = mysqli_query($con_sparcsn4,$sqlQu3);
							$strQueryRow3=mysqli_fetch_object($strQueryRes3);
							$yard=$strQueryRow3->yard;
							$yardConcat="";
							$blockConcat="";
							if($blockVal!=null){
								$blockConcat=$blockVal;
							}
							else{
								$blockConcat=' ';
							}
							if($yard!=null){
								$yardConcat=$yard;

							}
							else{
								$yardConcat=' ';
							}
							$dsc= 'Yard:'.$yardConcat.',Block:'.$blockConcat.", ".$dscValue;

							}
							else{
								
								$dsc=$row->DSC;
							}
						}
						else if($cat=="EXPRT"){
				
                             
					        $sql2="SELECT IFNULL(ctmsmis.cont_block('$last_pos_slot',ctmsmis.cont_yard('$last_pos_slot')),'') AS block";
							$strQueryRes2 = mysqli_query($con_sparcsn4,$sql2);
							 
							$blockVal="";
							$strQueryRow2=mysqli_fetch_object($strQueryRes2);
						     $blockVal=$strQueryRow2->block;

						     $sqlQu3="SELECT IFNULL(ctmsmis.cont_yard('$last_pos_slot'),'') AS yard";
							$strQueryRes3 = mysqli_query($con_sparcsn4,$sqlQu3);
							$strQueryRow3=mysqli_fetch_object($strQueryRes3);
							$yard=$strQueryRow3->yard;

							$yardConcat="";
							$blockConcat="";
							if($blockVal!=null){
								$blockConcat=$blockVal;

							}
							else{
								$blockConcat=' ';

							}
							if($yard!=null){
								$yardConcat=$yard;

							}
							else{
								$yardConcat=' ';
							}
							 $dsc= 'Yard:'.$yardConcat.',Block:'.$blockConcat.", ".$dscValue;


						}
						else{
							$dsc=$row->DSC;
							$blockVal='';
							
						}

					
						
					}
						
					$evt = 0;
					if($freightKind=="LCL")
					{
					
						$sql_stripped="SELECT 
						(SELECT COUNT(*) FROM srv_event WHERE srv_event.applied_to_gkey=inv_unit.gkey AND event_type_gkey=30) AS rtnValue
						FROM inv_unit WHERE id='$cont_id' AND  category='IMPRT' ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
					    $rslt_stripped= oci_parse($con_sparcsn4_oracle, $sql_stripped);
						oci_execute($rslt_stripped);
						
						while(($row_stripped=oci_fetch_object($rslt_stripped))!=false)
						{
							$evt=$row_stripped->RTNVALUE;
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
			// Free the statement identifier when closing the connection
            oci_free_statement($result);
			oci_close($con_sparcsn4_oracle);
			include_once("mydbPConnection.php");
			 $sql1="SELECT igm_masters.Import_Rotation_No,Vessel_Name,Voy_No,Port_of_Shipment,igm_masters.file_clearence_date,(SELECT CONCAT(IFNULL(Organization_Name,' '),' ',IFNULL(Address_1,' '),' ',IFNULL(Address_2,' ')) FROM organization_profiles WHERE organization_profiles.id=igm_detail_container.off_dock_id) AS offdock,mlocode
			FROM igm_detail_container 
			INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
			WHERE cont_number='$cont_id'
			ORDER BY igm_detail_container.id DESC 
			LIMIT 1"; //file_clearence_date,
			

			$abc = mysqli_query($con_cchaportdb,$sql1);
		    $row2=mysqli_fetch_object($abc);
			
			
			
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
							<td width="20%">MLO</td><td>:</td><td ><b><?php if($cat == ""){echo "";}else{echo  $row2->mlocode; }?></b></td>
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
