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
		igm_supplimentary_detail.Exporter_address,
		igm_supplimentary_detail.Notify_code,
		igm_supplimentary_detail.Notify_name,
		igm_supplimentary_detail.Notify_address,
		igm_supplimentary_detail.Consignee_code,
		igm_supplimentary_detail.Consignee_name,
		igm_supplimentary_detail.Consignee_address,
		igm_supplimentary_detail.Volume_in_cubic_meters,
		igm_supplimentary_detail.DG_status,
		place_of_unloading,
		igm_supplimentary_detail.port_of_origin
		FROM igm_supplimentary_detail 
		LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rot' AND igm_supplimentary_detail.BL_No='$bl'";
		$resBlInfo = mysqli_query($con_cchaportdb,$strBlInfo);
		
		$Bol_reference = "";
		$Line_number = "";
		$Bol_nature = "";
		$Bol_type_code = "";
		$DG_status = "";
		$Port_of_origin_code = "";
		$Place_of_unloading_code = "";
		
		while($rowBlInfo = mysqli_fetch_object($resBlInfo))
		{
			$Bol_reference = $rowBlInfo->BL_No;
			$Line_number = $rowBlInfo->Line_No;
			$Bol_nature = $rowBlInfo->BL_Type;
			
			if($igmDtl == 0)
				$Bol_type_code = "HSB";
			else if($igmDtl == 0)
				$Bol_type_code = "MSB";
			
			$DG_status = $rowBlInfo->DG_status;
			$Port_of_origin_code = $rowBlInfo->port_of_origin;
			$Place_of_unloading_code = $rowBlInfo->place_of_unloading;
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
			
			$stringData = "<Consolidated_Cargo>"."false"."<Consolidated_Cargo>\n";
			fwrite($fh, $stringData);
			
			$stringData = "<Load_unload_place>\n";
			fwrite($fh, $stringData);
				$stringData = "<Port_of_origin_code></Port_of_origin_code>\n";
				fwrite($fh, $stringData);
				$stringData = "<Place_of_unloading_code></Place_of_unloading_code>\n";
				fwrite($fh, $stringData);
			$stringData = "<Load_unload_place>\n";
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