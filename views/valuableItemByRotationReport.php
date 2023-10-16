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
		header("Content-Disposition: attachment; filename=Valuable_Item_Report.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	?>
<html>

<body>

<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
	<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				<?php if($_POST['fileOptions']=='html'){?>
				<tr align="center">
					<td colspan="12"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
				<?php }?>
				<tr align="center">
					<td colspan="12"><font size="4"><b><?php echo $title;?></b></font></td>
				</tr>
				
                <tr align="center">
                    <td colspan="12"><font size="4">ROTATION : <?php echo $rotation; ?></font></td>
                </tr>
				
				
				
			</table>
		</td>
	</tr>

	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
	</tr>
</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;" align="center">
		<tr align="center">
			<th style="border-width:3px;">Sl No.</th>
			<th style="border-width:3px;">Vessel Name</th>
			<th style="border-width:3px;">Registration No.</th>
			<th style="border-width:3px;">Container No.</th>			
			<th style="border-width:3px;">Size</th>	
            <th style="border-width:3px;">Seal No</th>
            <th style="border-width:3px;">Status</th>
            <th style="border-width:3px;">Description of Goods</th>
			<th style="border-width:3px;">DG Class</th>	
			<th style="border-width:3px;">UN No</th>	
			<th style="border-width:3px;">Bearth no</th>
			<th style="border-width:3px;">MLO</th>
			<th style="border-width:3px;">Remarks</th>			
		</tr>
        <?php
            include("mydbPConnection.php");
            include("dbConection.php");
			include("dbOracleConnection.php");
			

            $query = "SELECT DISTINCT igms.id AS id,igm_detail_container.cont_number,igm_detail_container.cont_size,igms.mlocode,igms.IGM_id AS IGM_id,igms.Import_Rotation_No AS Import_Rotation_No,
            cont_status,igms.imco,cont_imo,cont_un,igms.Line_No AS Line_No,
            igms.BL_No AS BL_No,igms.Pack_Number AS Pack_Number,igms.Pack_Description AS Pack_Description,igms.Pack_Marks_Number AS Pack_Marks_Number,
            igms.Description_of_Goods AS Description_of_Goods,igms.Date_of_Entry_of_Goods AS Date_of_Entry_of_Goods,igms.weight AS weight,
            igms.weight_unit,igms.net_weight,igms.net_weight_unit, igms.Bill_of_Entry_No AS Bill_of_Entry_No,
            igms.Bill_of_Entry_Date AS Bill_of_Entry_Date,igms.No_of_Pack_Delivered AS No_of_Pack_Delivered, 
            igms.No_of_Pack_Discharged AS No_of_Pack_Discharged,igms.Remarks AS Remarks,igms.AFR AS AFR,igms.ConsigneeDesc,
            igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode, (SELECT Organization_Name FROM organization_profiles orgs
            WHERE orgs.id=igms.Submitee_Org_Id) AS Organization_Name, (SELECT Vessel_Name FROM igm_masters igm_Master WHERE igm_Master.id=igms.IGM_id)
            AS vessel_Name, imco,un,extra_remarks,navyresponse.response_details1, navyresponse.response_details2,navyresponse.secondapprovaltime,
            navyresponse.response_details3,navyresponse.thirdapprovaltime, navyresponse.hold_application,navyresponse.hold_date,navyresponse.rejected_application,
            navyresponse.rejected_date,  yard_lying_info.discharge_dt,yard_lying_info.location,
            un,extra_remarks,navyresponse.response_details1, navyresponse.response_details2,navyresponse.secondapprovaltime,
            navyresponse.response_details3,navyresponse.thirdapprovaltime, navyresponse.hold_application,navyresponse.hold_date,navyresponse.rejected_application,
            navyresponse.rejected_date, navyresponse.final_amendment,navyresponse.navy_response_to_port,
            igm_detail_container.Delivery_Status_date,igms.Notify_name,igm_detail_container.cont_seal_number
            FROM  igm_detail_container 
            INNER JOIN igm_details igms ON igm_detail_container.igm_detail_id=igms.id 
            INNER JOIN yard_lying_info ON igm_detail_container.cont_number= yard_lying_info.id  AND igms.Import_Rotation_No = yard_lying_info.rotation
            LEFT JOIN igm_navy_response navyresponse ON navyresponse.igm_details_id=igms.id
            WHERE igms.Import_Rotation_No='$rotation' AND UPPER(igms.Description_of_Goods) LIKE '%COMPUTER%' OR 
            UPPER(igms.Description_of_Goods) LIKE '%INGOT%'
            AND (cont_imo <> '' OR igms.imco <> '' OR igms.un <> '') 
            
            UNION
            
            SELECT DISTINCT igms_sup.id AS id,igm_sup_detail_container.cont_number,igm_sup_detail_container.cont_size,igms_sup.mlocode,igms_sup.IGM_id AS IGM_id,igms_sup.Import_Rotation_No AS Import_Rotation_No,
            cont_status,igms_sup.imco,cont_imo,cont_un,igms_sup.Line_No AS Line_No,
            igms_sup.BL_No AS BL_No,igms_sup.Pack_Number AS Pack_Number,igms_sup.Pack_Description AS Pack_Description,igms_sup.Pack_Marks_Number AS Pack_Marks_Number,
            igms_sup.Description_of_Goods AS Description_of_Goods,igms_sup.Date_of_Entry_of_Goods AS Date_of_Entry_of_Goods,igms_sup.weight AS weight,
            igms_sup.weight_unit,igms_sup.net_weight,igms_sup.net_weight_unit, igms_sup.Bill_of_Entry_No AS Bill_of_Entry_No,
            igms_sup.Bill_of_Entry_Date AS Bill_of_Entry_Date,igms_sup.No_of_Pack_Delivered AS No_of_Pack_Delivered, 
            igms_sup.No_of_Pack_Discharged AS No_of_Pack_Discharged,igms_sup.Remarks AS Remarks,igms_sup.AFR AS AFR,igms_sup.ConsigneeDesc,
            igms_sup.NotifyDesc,igms_sup.navy_comments,igms_sup.Submitee_Org_Id,igms_sup.mlocode, (SELECT Organization_Name FROM organization_profiles orgs
            WHERE orgs.id=igms_sup.Submitee_Org_Id) AS Organization_Name, (SELECT Vessel_Name FROM igm_masters igm_Master WHERE igm_Master.id=igms_sup.IGM_id)
            AS vessel_Name, imco,un,extra_remarks,navyresponse.response_details1, navyresponse.response_details2,navyresponse.secondapprovaltime,
            navyresponse.response_details3,navyresponse.thirdapprovaltime, navyresponse.hold_application,navyresponse.hold_date,navyresponse.rejected_application,
            navyresponse.rejected_date, yard_lying_info.discharge_dt, yard_lying_info.location,
            un,extra_remarks,navyresponse.response_details1, navyresponse.response_details2,navyresponse.secondapprovaltime,
            navyresponse.response_details3,navyresponse.thirdapprovaltime, navyresponse.hold_application,navyresponse.hold_date,navyresponse.rejected_application,
            navyresponse.rejected_date, navyresponse.final_amendment,navyresponse.navy_response_to_port,
            igm_sup_detail_container.Delivery_Status_date,igms_sup.Notify_name,igm_sup_detail_container.cont_seal_number
            FROM igm_sup_detail_container
            INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id 
            INNER JOIN igm_details igms_sup ON igms_sup.id=igm_supplimentary_detail.igm_detail_id
            INNER JOIN yard_lying_info ON igm_sup_detail_container.cont_number= yard_lying_info.id  AND igms_sup.Import_Rotation_No = yard_lying_info.rotation
            LEFT JOIN igm_navy_response navyresponse ON navyresponse.igm_details_id=igms_sup.id 
            WHERE  igm_supplimentary_detail.Import_Rotation_No='$rotation' AND UPPER(igms_sup.Description_of_Goods) LIKE '%COMPUTER%' OR 
            UPPER(igms_sup.Description_of_Goods) LIKE '%INGOT%'
            AND (cont_imo <> '' OR igms_sup.imco <> '' OR igms_sup.un <> '')";

            $rslt=mysqli_query($con_cchaportdb,$query);
            
            $vessel_name = "";
            $reg = "";
            $cont = "";
            $size = "";
            $seal = "";
            $status = "";
            $descOfGoods = "";
            $dgClass="";
            $berth = "";
            $mlo = "";
            $remarks = "";
            $un = "";

            $i=0;
            while($row = mysqli_fetch_object($rslt)){
                $i++;
                $vessel_name = $row->vessel_Name;
                $reg = $row->Import_Rotation_No;
                $cont = $row->cont_number;
                $size = $row->cont_size;
                $seal = $row->cont_seal_number;
                $status = $row->cont_status;
                $descOfGoods = $row->Description_of_Goods;
                $dgClass=$row->cont_imo;
                //$berth = $row->;
                $mlo = $row->mlocode;
                $remarks = $row->Remarks;
                $un = $row->un;

             

				
			    $berth_query = "SELECT inv_unit_fcy_visit.time_out,argo_carrier_visit.ata,r.id AS mlo, argo_quay.id AS berth
				FROM inv_unit 
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
				INNER JOIN argo_carrier_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey 
				INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
				INNER JOIN ref_bizunit_scoped r ON r.gkey=inv_unit.line_op 
				LEFT JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
				LEFT JOIN argo_quay ON argo_quay.gkey=vsl_vessel_berthings.quay
				WHERE inv_unit.id ='$cont' AND vsl_vessel_visit_details.ib_vyg='$reg'";
			
			
                
             
				$berth_rslt= oci_parse($con_sparcsn4_oracle, $berth_query);
                oci_execute($berth_rslt);

                $j=0;
             
				while(($berth_row= oci_fetch_object($berth_rslt)) != false){
                    $j++;
                    $berth = $berth_row->BERTH;
                }
        ?>

        <tr>
            <td align="center"><?php echo $i; ?></td>
            <td align="center"><?php echo $vessel_name; ?></td>
            <td align="center"><?php echo $reg; ?></td>
            <td align="center"><?php echo $cont; ?></td>
            <td align="center"><?php echo $size; ?></td>
            <td align="center"><?php echo $seal; ?></td>
            <td align="center"><?php echo $status; ?></td>
            <td align="center"><?php echo $descOfGoods; ?></td>
            <td align="center"><?php echo $dgClass; ?></td>    
            <td align="center"><?php echo $un; ?></td>    
            <td align="center"><?php echo $berth; ?></td>
            <td align="center"><?php echo $mlo; ?></td>
            <td align="center"><?php echo $remarks; ?></td>
        </tr>

        <?php
            }
        ?>

	</table>
<?php mysqli_close($con_sparcsn4);?>
<?php 
 oci_free_statement($berth_rslt);
 oci_close($con_sparcsn4_oracle);
 
?>
<br />
<br />
<?php 
if($_POST['fileOptions']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
