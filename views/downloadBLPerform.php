<?php
	include("mydbPConnection.php");
	$rot = $txt_imp_rot1;
	$bl = $txt_bl;
	$fileName = removeSpChar($bl);
	$strChkIgmType = "SELECT * FROM igm_supplimentary_detail WHERE Import_Rotation_No='$rot' AND BL_No='$bl'";
	$resChkIgmType = mysqli_query($con_cchaportdb,$strChkIgmType);
	$ChkIgmType = mysqli_num_rows($resChkIgmType);
	$IgmType = "";
	if($ChkIgmType>0)
		$IgmType = "HSB";
	else
		$IgmType = "MSB";
	
	$strMasterInfo = "SELECT `Customs_office_code`,`Voy_No`,`Import_Rotation_No`,`Total_number_of_bols`,
	`Total_number_of_packages`,	`Total_number_of_containers`,`Total_gross_mass`,`Mode_of_transport_code`,`Vessel_Name`,
	`Nationality_of_transporter_code`,`Registration_number_of_transport_code`,`Name_of_Master`,`Port_Ship_ID`,`Port_of_Destination`,
	`Submitee_Org_Id`,`Submitee_Id`,IFNULL(AIN_No,AIN_No_New) AS AIN_No,Organization_Name,Address_1
	FROM igm_masters 
	INNER JOIN organization_profiles ON organization_profiles.id=igm_masters.Submitee_Org_Id
	WHERE igm_masters.Import_Rotation_No='$rot'";
	$resMasterInfo = mysqli_query($con_cchaportdb,$strMasterInfo);
	$Customs_office_code = "";
	$Voy_No = "";
	$Import_Rotation_No = "";
	$Total_number_of_bols = "";
	$Total_number_of_packages = "";
	$Total_number_of_containers = "";
	$Total_gross_mass = "";
	$Carrier_code = "";
	$Carrier_name = "";
	$Carrier_address = "";
	$Mode_of_transport_code = "";
	$Identity_of_transporter = "";
	$Nationality_of_transporter_code = "";
	$Registration_number_of_transport_code = "";
	$Master_information = "";
	$Place_of_departure_code = "";
	$Place_of_destination_code = "";
	while($rowMasterInfo = mysqli_fetch_object($resMasterInfo))
	{
		$Customs_office_code = $rowMasterInfo->Customs_office_code;
		$Voy_No = $rowMasterInfo->Voy_No;
		$Import_Rotation_No = $rowMasterInfo->Import_Rotation_No;
		$Total_number_of_bols = $rowMasterInfo->Total_number_of_bols;
		$Total_number_of_packages = $rowMasterInfo->Total_number_of_packages;
		$Total_number_of_containers = $rowMasterInfo->Total_number_of_containers;
		$Total_gross_mass = $rowMasterInfo->Total_gross_mass;
		$Carrier_code = $rowMasterInfo->AIN_No;
		$Carrier_name = $rowMasterInfo->Organization_Name;
		$Carrier_address = $rowMasterInfo->Address_1;
		$Mode_of_transport_code = $rowMasterInfo->Mode_of_transport_code;
		$Identity_of_transporter = $rowMasterInfo->Vessel_Name;
		$Nationality_of_transporter_code = $rowMasterInfo->Nationality_of_transporter_code;
		$Registration_number_of_transport_code = $rowMasterInfo->Registration_number_of_transport_code;
		$Master_information = $rowMasterInfo->Name_of_Master;
		$Place_of_departure_code = $rowMasterInfo->Port_Ship_ID;
		$Place_of_destination_code = $rowMasterInfo->Port_of_Destination;
	}
	
	$strBerthInfo = "SELECT `Import_Rotation_No`,`VoyNo`,`ETD_Date`,`ETA_Date`,`org_id` FROM vessels_berth_detail 
	WHERE Import_Rotation_No='$rot'";
	$resBerthInfo = mysqli_query($con_cchaportdb,$strBerthInfo);
	$Date_of_departure = "";
	$Date_of_arrival = "";
	while($rowBerthInfo = mysqli_fetch_object($resBerthInfo))
	{
		$Date_of_departure = $rowBerthInfo->ETD_Date;
		$Date_of_arrival = $rowBerthInfo->ETA_Date;
	}
	
	$myFile=$fileName.'.xml';		
	if(file_exists($_SERVER['DOCUMENT_ROOT']."/pcs/resources/bl/".$myFile))
	{
		unlink($_SERVER['DOCUMENT_ROOT']."/pcs/resources/bl/".$myFile);
	}
	$fh= fopen($_SERVER['DOCUMENT_ROOT']."/pcs/resources/bl/".$myFile , 'a');
	$stringData = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
	fwrite($fh, $stringData);
	$stringData = "<Awmds>\n";
	fwrite($fh, $stringData);		
		$stringData = "<General_segment>\n";
		fwrite($fh, $stringData);
			$stringData = "<General_segment_id>\n";
			fwrite($fh, $stringData);
				$stringData = "<Customs_office_code>".$Customs_office_code."</Customs_office_code>\n";
				fwrite($fh, $stringData);
				$stringData = "<Voyage_number>".$Voy_No."</Voyage_number>\n";
				fwrite($fh, $stringData);
				$stringData = "<Import_Rotation>".$Import_Rotation_No."</Import_Rotation>\n";
				fwrite($fh, $stringData);
				$stringData = "<Date_of_departure>".$Date_of_departure."</Date_of_departure>\n";
				fwrite($fh, $stringData);
				$stringData = "<Date_of_arrival>".$Date_of_arrival."</Date_of_arrival>\n";
				fwrite($fh, $stringData);
			$stringData = "</General_segment_id>\n";
			fwrite($fh, $stringData);
			
			$stringData = "<Totals_segment>\n";
			fwrite($fh, $stringData);
				$stringData = "<Total_number_of_bols>".$Total_number_of_bols."</Total_number_of_bols>\n";
				fwrite($fh, $stringData);
				$stringData = "<Total_number_of_packages>".$Total_number_of_packages."</Total_number_of_packages>\n";
				fwrite($fh, $stringData);
				$stringData = "<Total_number_of_containers>".$Total_number_of_containers."</Total_number_of_containers>\n";
				fwrite($fh, $stringData);
				$stringData = "<Total_gross_mass>".$Total_gross_mass."</Total_gross_mass>\n";
				fwrite($fh, $stringData);
			$stringData = "</Totals_segment>\n";
			fwrite($fh, $stringData);			
			
			$stringData = "<Transport_information>\n";
			fwrite($fh, $stringData);
				$stringData = "<Carrier>\n";
				fwrite($fh, $stringData);
					$stringData = "<Carrier_code>".$Carrier_code."</Carrier_code>\n";
					fwrite($fh, $stringData);
					$stringData = "<Carrier_name>".$Carrier_name."</Carrier_name>\n";
					fwrite($fh, $stringData);
					$stringData = "<Carrier_address>".$Carrier_address."</Carrier_address>\n";
					fwrite($fh, $stringData);
				$stringData = "</Carrier>\n";
				fwrite($fh, $stringData);
			
				$stringData = "<Mode_of_transport_code>".$Mode_of_transport_code."</Mode_of_transport_code>\n";
				fwrite($fh, $stringData);
				$stringData = "<Identity_of_transporter>".$Identity_of_transporter."</Identity_of_transporter>\n";
				fwrite($fh, $stringData);
				$stringData = "<Nationality_of_transporter_code>".$Nationality_of_transporter_code."</Nationality_of_transporter_code>\n";
				fwrite($fh, $stringData);
				$stringData = "<Registration_number_of_transport_code>".$Registration_number_of_transport_code."</Registration_number_of_transport_code>\n";
				fwrite($fh, $stringData);
				$stringData = "<Master_information>".$Master_information."</Master_information>\n";
				fwrite($fh, $stringData);
			$stringData = "</Transport_information>\n";
			fwrite($fh, $stringData);
			
			$stringData = "<Load_unload_place>\n";
			fwrite($fh, $stringData);
				$stringData = "<Place_of_departure_code>".$Place_of_departure_code."</Place_of_departure_code>\n";
				fwrite($fh, $stringData);
				$stringData = "<Place_of_destination_code>".$Place_of_destination_code."</Place_of_destination_code>\n";
				fwrite($fh, $stringData);
			$stringData = "</Load_unload_place>\n";
			fwrite($fh, $stringData);
		$stringData = "</General_segment>\n";
		fwrite($fh, $stringData);	
		
/* 		$strBlInfo = "SELECT Import_Rotation_No,Line_No,BL_No,NULL as BL_Type,Pack_Number,Pack_Description,Pack_Marks_Number,
		Description_of_Goods,weight,office_code,ConsigneeDesc,NotifyDesc,Submitee_Id,Submitee_Org_Type,Submitee_Org_Id,Last_Update_By_id,
		last_update,type_of_igm,weight_unit,Exporter_name,Exporter_address,
		Notify_code,Notify_name,Notify_address,Consignee_code,Consignee_name,Consignee_address,Volume_in_cubic_meters,
		DG_status,port_of_origin
		FROM igm_supplimentary_detail 
		WHERE Import_Rotation_No='2022/5256' AND BL_No='DXO2210226'"; */

		/* $strBlInfo = "SELECT Import_Rotation_No,Line_No,BL_No,NULL as BL_Type,Pack_Number,Pack_Description,Pack_Marks_Number,
		Description_of_Goods,weight,office_code,ConsigneeDesc,NotifyDesc,Submitee_Id,Submitee_Org_Type,Submitee_Org_Id,Last_Update_By_id,
		last_update,type_of_igm,weight_unit,Exporter_name,Exporter_address,
		Notify_code,Notify_name,Notify_address,Consignee_code,Consignee_name,Consignee_address,Volume_in_cubic_meters,
		DG_status,port_of_origin
		FROM igm_supplimentary_detail 
		WHERE Import_Rotation_No='$rot' AND BL_No='$bl'";
		$resBlInfo = mysqli_query($con_cchaportdb,$strBlInfo);
		
		$igmDtl = 0;
		if(mysqli_num_rows($resBlInfo)==0)
		{
			$igmDtl = 1;
			$strBlInfo = "SELECT Import_Rotation_No,Line_No,BL_No,NULL as BL_Type,Pack_Number,Pack_Description,Pack_Marks_Number,
			Description_of_Goods,weight,office_code,ConsigneeDesc,NotifyDesc,Submitee_Id,Submitee_Org_Type,Submitee_Org_Id,Last_Update_By_id,
			last_update,type_of_igm,weight_unit,Exporter_name,Exporter_address,
			Notify_code,Notify_name,Notify_address,Consignee_code,Consignee_name,Consignee_address,Volume_in_cubic_meters,
			DG_status,port_of_origin
			FROM igm_details
			WHERE Import_Rotation_No='$rot' AND BL_No='$bl'";
			$resBlInfo = mysqli_query($con_cchaportdb,$strBlInfo);
		} */
		
		$sql_chkIgmSupDtl = "SELECT COUNT(*) AS cnt
		FROM igm_supplimentary_detail
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rot' AND igm_supplimentary_detail.BL_No='$bl'";
		$res_chkIgmSupDtl = mysqli_query($con_cchaportdb,$sql_chkIgmSupDtl);
		
		$chkIgmSupDtl = 0;
		while($row_chkIgmSupDtl = mysqli_fetch_object($res_chkIgmSupDtl))
		{
			$chkIgmSupDtl = $row_chkIgmSupDtl->cnt;
		}
		
		$strBlInfo = "";
		if($chkIgmSupDtl == 0)
		{
			$strBlInfo = "SELECT Import_Rotation_No,
			Line_No,
			BL_No,
			NULL AS BL_Type,
			Pack_Number,
			Pack_Description,
			Pack_Marks_Number,
			Description_of_Goods,
			weight,
			office_code,
			ConsigneeDesc,
			NotifyDesc,
			Submitee_Id,
			Submitee_Org_Type,
			Submitee_Org_Id,
			Last_Update_By_id,
			last_update,
			type_of_igm,
			weight_unit,
			Exporter_name,
			REPLACE(Exporter_address,'&','and ') AS Exporter_address,
			Notify_code,
			Notify_name,
			REPLACE(Notify_address,'&','and ') AS Notify_address,
			Consignee_code,
			Consignee_name,			
			REPLACE(Consignee_address,'&','and ') AS Consignee_address,
			Volume_in_cubic_meters,
			DG_status,
			place_of_unloading,
			port_of_origin,
			mlocode,
			Remarks
			FROM igm_details 
			WHERE igm_details.Import_Rotation_No='$rot' AND igm_details.BL_No='$bl'";
		}
		else
		{
			$strBlInfo = "SELECT igm_supplimentary_detail.Import_Rotation_No,
			igm_supplimentary_detail.Line_No,
			igm_supplimentary_detail.BL_No,
			NULL AS BL_Type,
			igm_supplimentary_detail.Pack_Number,
			igm_supplimentary_detail.Pack_Description,
			igm_supplimentary_detail.Pack_Marks_Number,
			igm_supplimentary_detail.Description_of_Goods,
			igm_supplimentary_detail.weight,
			igm_supplimentary_detail.office_code,
			igm_supplimentary_detail.ConsigneeDesc,
			igm_supplimentary_detail.NotifyDesc,
			igm_supplimentary_detail.Submitee_Id,
			igm_supplimentary_detail.Submitee_Org_Type,
			igm_supplimentary_detail.Submitee_Org_Id,
			igm_supplimentary_detail.Last_Update_By_id,
			igm_supplimentary_detail.last_update,
			igm_supplimentary_detail.type_of_igm,
			igm_supplimentary_detail.weight_unit,
			igm_supplimentary_detail.Exporter_name,
			REPLACE(igm_supplimentary_detail.Exporter_address,'&','and ') AS Exporter_address,
			igm_supplimentary_detail.Notify_code,
			igm_supplimentary_detail.Notify_name,			
			REPLACE(igm_supplimentary_detail.Notify_address,'&','and ') AS Notify_address,
			igm_supplimentary_detail.Consignee_code,
			igm_supplimentary_detail.Consignee_name,			
			REPLACE(igm_supplimentary_detail.Consignee_address,'&','and ') AS Consignee_address,
			igm_supplimentary_detail.Volume_in_cubic_meters,
			igm_supplimentary_detail.DG_status,
			place_of_unloading,
			igm_supplimentary_detail.port_of_origin,
			igm_details.mlocode,
			igm_supplimentary_detail.Remarks
			FROM igm_supplimentary_detail 
			LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rot' AND igm_supplimentary_detail.BL_No='$bl'";
		}
		
		// echo $strBlInfo;return;
		$resBlInfo = mysqli_query($con_cchaportdb,$strBlInfo);
		// print_r($resBlInfo);return;
		$Bol_reference = "";
		$Line_number = "";
		$Bol_nature = "";
		$Bol_type_code = "";
		$DG_status = "";
		$Consolidated_Cargo = "false";
		$Port_of_origin_code = "";
		$Place_of_unloading_code = "";
		
		$Submitee_Org_Id = "";

		$Carrier_code = "";
		$Carrier_name = "";
		$Carrier_address = "";
		
		$Shipping_Agent_code = "";
		$Shipping_Agent_name = "";
		
		$Exporter_name = "";
		$Exporter_address = "";
		
		$Notify_code = "";
		$Notify_name = "";
		$Notify_address = "";
		
		$Consignee_code = "";
		$Consignee_name = "";
		$Consignee_address = "";

		$Package_type_code = "";
		$Gross_mass = "";
		$Shipping_marks = "";
		$Goods_description = "";
		$Volume_in_cubic_meters = "";
		$Num_of_ctn_for_this_bol = 0;
		$Remarks = "";
		
		
		while($rowBlInfo = mysqli_fetch_object($resBlInfo))
		{
			$Bol_reference = $rowBlInfo->BL_No;
			$Line_number = $rowBlInfo->Line_No;
			$Bol_nature = $rowBlInfo->BL_Type;
			
			if($chkIgmSupDtl == 1)
				$Bol_type_code = "HSB";
			else if($chkIgmSupDtl == 0)
				$Bol_type_code = "MSB";
			
			$DG_status = $rowBlInfo->DG_status;
			$Port_of_origin_code = $rowBlInfo->port_of_origin;
			$Place_of_unloading_code = $rowBlInfo->place_of_unloading;
			
			$Submitee_Org_Id = $rowBlInfo->Submitee_Org_Id;
			
			// $sql_carrierInfo = "SELECT AIN_No AS Carrier_code,Organization_Name AS Carrier_name,Address_1 AS Carrier_address
			// FROM organization_profiles
			// WHERE id='$Submitee_Org_Id'";

			$sql_carrierInfo = "SELECT AIN_No AS Carrier_code,Organization_Name AS Carrier_name,			
			REPLACE(Address_1,'&','and ') AS Carrier_address
			FROM organization_profiles
			WHERE id='$Submitee_Org_Id'";
			$rslt_carrierInfo = mysqli_query($con_cchaportdb,$sql_carrierInfo);
			
			while($row_carrierInfo = mysqli_fetch_object($rslt_carrierInfo))
			{
				$Carrier_code = $row_carrierInfo->Carrier_code;
				$Carrier_name = $row_carrierInfo->Carrier_name;
				$Carrier_address = $row_carrierInfo->Carrier_address;				
			}						

			$Shipping_Agent_code = $rowBlInfo->mlocode;
			$Shipping_Agent_name = "";
			
			$Exporter_name = $rowBlInfo->Exporter_name;
			$Exporter_address = $rowBlInfo->Exporter_address;		//check & and replace with "and"
			
			$Notify_code = $rowBlInfo->Notify_code;
			$Notify_name = $rowBlInfo->Notify_name;
			$Notify_address = $rowBlInfo->Notify_address;
			
			$Consignee_code = $rowBlInfo->Consignee_code;
			$Consignee_name = $rowBlInfo->Consignee_name;
			$Consignee_address = $rowBlInfo->Consignee_address;
			
			// goods
			$Package_type_code = $rowBlInfo->Pack_Description;
			$Gross_mass = $rowBlInfo->weight;
			$Shipping_marks = $rowBlInfo->Pack_Marks_Number;
			$Goods_description = $rowBlInfo->Description_of_Goods;
			$Volume_in_cubic_meters = $rowBlInfo->Volume_in_cubic_meters;
			$Remarks = $rowBlInfo->Remarks;
		}
		
		$stringData = "<Bol_segment>\n";
		fwrite($fh, $stringData);
		
			$stringData = "<Bol_id>\n";
			fwrite($fh, $stringData);
				$stringData = "<Bol_reference>".$Bol_reference."</Bol_reference>\n";
				fwrite($fh, $stringData);
				$stringData = "<Line_number>".$Line_number."</Line_number>\n";
				fwrite($fh, $stringData);
				$stringData = "<Bol_nature>".$Bol_nature."</Bol_nature>\n";
				fwrite($fh, $stringData);
				$stringData = "<Bol_type_code>".$Bol_type_code."</Bol_type_code>\n";
				fwrite($fh, $stringData);
				$stringData = "<DG_status>".$DG_status."</DG_status>\n";
				fwrite($fh, $stringData);
			$stringData = "</Bol_id>\n";
			fwrite($fh, $stringData);
			
			$stringData = "<Consolidated_Cargo>".$Consolidated_Cargo."</Consolidated_Cargo>\n";
			fwrite($fh, $stringData);
			
			$stringData = "<Load_unload_place>\n";
			fwrite($fh, $stringData);
				$stringData = "<Port_of_origin_code>".$Port_of_origin_code."</Port_of_origin_code>\n";
				fwrite($fh, $stringData);
				$stringData = "<Place_of_unloading_code>".$Place_of_unloading_code."</Place_of_unloading_code>\n";
				fwrite($fh, $stringData);
			$stringData = "</Load_unload_place>\n";
			fwrite($fh, $stringData);
			
			$stringData = "<Traders_segment>\n";
			fwrite($fh, $stringData);
				
				$stringData = "<Carrier>\n";
				fwrite($fh, $stringData);
				
					$stringData = "<Carrier_code>".$Carrier_code."</Carrier_code>\n";
					fwrite($fh, $stringData);
					$stringData = "<Carrier_name>".$Carrier_name."</Carrier_name>\n";
					fwrite($fh, $stringData);
					$stringData = "<Carrier_address>".$Carrier_address."</Carrier_address>\n";
					fwrite($fh, $stringData);
			
				$stringData = "</Carrier>\n";
				fwrite($fh, $stringData);
				
				$stringData = "<Shipping_Agent>\n";
				fwrite($fh, $stringData);
				
					$stringData = "<Shipping_Agent_code>".$Shipping_Agent_code."</Shipping_Agent_code>\n";
					fwrite($fh, $stringData);
					$stringData = "<Shipping_Agent_name>".$Shipping_Agent_name."</Shipping_Agent_name>\n";
					fwrite($fh, $stringData);
			
				$stringData = "</Shipping_Agent>\n";
				fwrite($fh, $stringData);
				
				$stringData = "<Exporter>\n";
				fwrite($fh, $stringData);
				
					$stringData = "<Exporter_name>".$Exporter_name."</Exporter_name>\n";
					fwrite($fh, $stringData);
					$stringData = "<Exporter_address>".$Exporter_address."</Exporter_address>\n";
					fwrite($fh, $stringData);
			
				$stringData = "</Exporter>\n";
				fwrite($fh, $stringData);
				
				$stringData = "<Notify>\n";
				fwrite($fh, $stringData);
				
					$stringData = "<Notify_code>".$Notify_code."</Notify_code>\n";
					fwrite($fh, $stringData);
					$stringData = "<Notify_name>".$Notify_name."</Notify_name>\n";
					fwrite($fh, $stringData);
					$stringData = "<Notify_address>".$Notify_address."</Notify_address>\n";
					fwrite($fh, $stringData);
			
				$stringData = "</Notify>\n";
				fwrite($fh, $stringData);
				
				$stringData = "<Consignee>\n";
				fwrite($fh, $stringData);
				
					$stringData = "<Consignee_code>".$Consignee_code."</Consignee_code>\n";
					fwrite($fh, $stringData);
					$stringData = "<Consignee_name>".$Consignee_name."</Consignee_name>\n";
					fwrite($fh, $stringData);
					$stringData = "<Consignee_address>".$Consignee_address."</Consignee_address>\n";
					fwrite($fh, $stringData);
			
				$stringData = "</Consignee>\n";
				fwrite($fh, $stringData);
				
			$stringData = "</Traders_segment>\n";
			fwrite($fh, $stringData);
			
			// ctn segment - start	

			$sql_ctnSegment = "";
			if($chkIgmSupDtl==1)
			{
				$sql_ctnSegment = "SELECT cont_number AS Ctn_reference,cont_number_packaages AS Number_of_packages,cont_iso_type AS Type_of_container,
				cont_status AS Status,cont_seal_number AS Seal_number,cont_location_code AS Ctn_location,
				IF(cont_imo IS NULL OR cont_imo='',0,cont_imo) AS IMCO,
				IF(cont_un IS NULL OR cont_un='',0,cont_un) AS UN,
				commudity_code AS Commodity_code,Cont_gross_weight AS Gross_weight
				FROM igm_sup_detail_container
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
				WHERE igm_supplimentary_detail.Import_Rotation_No='$rot' AND igm_supplimentary_detail.BL_No='$bl'";	
			}
			else
			{
				$sql_ctnSegment = "SELECT cont_number AS Ctn_reference,cont_number_packaages AS Number_of_packages,cont_iso_type AS Type_of_container,
				cont_status AS Status,cont_seal_number AS Seal_number,cont_location_code AS Ctn_location,
				IF(cont_imo IS NULL OR cont_imo='',0,cont_imo) AS IMCO,
				IF(cont_un IS NULL OR cont_un='',0,cont_un) AS UN,
				commudity_code AS Commodity_code,Cont_gross_weight AS Gross_weight
				FROM igm_detail_container
				INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
				WHERE igm_details.Import_Rotation_No='$rot' AND igm_details.BL_No='$bl'";	
			}
			
			$rslt_ctnSegment = mysqli_query($con_cchaportdb,$sql_ctnSegment);
			
			$Ctn_reference = "";
			$Number_of_packages = "";
			$Type_of_container = "";
			$Status = "";
			$Seal_number = "";
			$Ctn_location = "";
			$IMCO = "";
			$UN = "";
			$Commodity_code = "";
			$Gross_weight = "";
			
			$Number_of_packages_goods = 0;
			
			while($row_ctnSegment = mysqli_fetch_object($rslt_ctnSegment))
			{
				$Num_of_ctn_for_this_bol++;
				
				$Ctn_reference = $row_ctnSegment->Ctn_reference;
				$Number_of_packages = $row_ctnSegment->Number_of_packages;
				$Type_of_container = $row_ctnSegment->Type_of_container;
				$Status = $row_ctnSegment->Status;
				$Seal_number = $row_ctnSegment->Seal_number;
				$Ctn_location = $row_ctnSegment->Ctn_location;
				$IMCO = $row_ctnSegment->IMCO;
				$UN = $row_ctnSegment->UN;
				$Commodity_code = $row_ctnSegment->Commodity_code;
				$Gross_weight = $row_ctnSegment->Gross_weight;
				
				$Number_of_packages_goods = $Number_of_packages_goods + $Number_of_packages;
				
				$stringData = "<ctn_segment>\n";
				fwrite($fh, $stringData);
				
					$stringData = "<Ctn_reference>".$Ctn_reference."</Ctn_reference>\n";
					fwrite($fh, $stringData);
					
					$stringData = "<Number_of_packages>".$Number_of_packages."</Number_of_packages>\n";
					fwrite($fh, $stringData);
					
					$stringData = "<Type_of_container>".$Type_of_container."</Type_of_container>\n";
					fwrite($fh, $stringData);
					
					$stringData = "<Status>".$Status."</Status>\n";
					fwrite($fh, $stringData);
					
					$stringData = "<Seal_number>".$Seal_number."</Seal_number>\n";
					fwrite($fh, $stringData);
					
					$stringData = "<Ctn_location>".$Ctn_location."</Ctn_location>\n";
					fwrite($fh, $stringData);
					
					$stringData = "<IMCO>".$IMCO."</IMCO>\n";
					fwrite($fh, $stringData);
					
					$stringData = "<UN>".$UN."</UN>\n";
					fwrite($fh, $stringData);
					
					$stringData = "<Commodity_code>".$Commodity_code."</Commodity_code>\n";
					fwrite($fh, $stringData);
					
					$stringData = "<Gross_weight>".$Gross_weight."</Gross_weight>\n";
					fwrite($fh, $stringData);
					
				$stringData = "</ctn_segment>\n";
				fwrite($fh, $stringData);
			}
			// ctn segment - end
			
			// goods
			
			
			$stringData = "<Goods_segment>\n";
			fwrite($fh, $stringData);
			
				$stringData = "<Number_of_packages>".$Number_of_packages_goods."</Number_of_packages>\n";
				fwrite($fh, $stringData);
				
				$stringData = "<Package_type_code>".$Package_type_code."</Package_type_code>\n";
				fwrite($fh, $stringData);
				
				$stringData = "<Gross_mass>".$Gross_mass."</Gross_mass>\n";
				fwrite($fh, $stringData);
				
				$stringData = "<Shipping_marks>".$Shipping_marks."</Shipping_marks>\n";
				fwrite($fh, $stringData);
				
				$stringData = "<Goods_description>".$Goods_description."</Goods_description>\n";
				fwrite($fh, $stringData);
				
				$stringData = "<Volume_in_cubic_meters>".$Volume_in_cubic_meters."</Volume_in_cubic_meters>\n";
				fwrite($fh, $stringData);
				
				$stringData = "<Num_of_ctn_for_this_bol>".$Num_of_ctn_for_this_bol."</Num_of_ctn_for_this_bol>\n";
				fwrite($fh, $stringData);
				
				$stringData = "<Remarks>".$Remarks."</Remarks>\n";
				fwrite($fh, $stringData);
			
			$stringData = "</Goods_segment>\n";
			fwrite($fh, $stringData);
			
		$stringData = "</Bol_segment>\n";
		fwrite($fh, $stringData);
		
	$stringData = "</Awmds>\n";
	fwrite($fh, $stringData);
	fclose($fh);
	
	if (file_exists($_SERVER['DOCUMENT_ROOT']."/pcs/resources/bl/".$myFile)) 
	{
		ob_start();		
		$myFileName=str_replace(" ","_",$myFile);
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header("Content-Disposition: attachment; filename=".$myFileName);
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($_SERVER['DOCUMENT_ROOT']."/pcs/resources/bl/".$myFile));
		
		ob_end_clean(); 
		flush();
		readfile($_SERVER['DOCUMENT_ROOT']."/pcs/resources/bl/".$myFile);
		
		//exit;	
	}
	
	function removeSpChar($string) {
	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
?>