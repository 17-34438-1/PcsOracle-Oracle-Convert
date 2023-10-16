<?php if($_POST['fileOptions']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Statement of Containerized Cargo</TITLE>
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
					<td colspan="12"><font size="4"><b> Statement of Containerized Cargo  </b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b> From: <?php echo $frmDt;?> To :  <?php echo $toDt;?> </b></font></td>
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
			<th style="border-width:3px;">Shipping Agent.</th>			
			<th style="border-width:3px;">Registration No.</th>
            <th style="border-width:3px;">Arrival Date</th>
            <th style="border-width:3px;">Sailing Date</th>
            <!--th style="border-width:3px;">Cargo</th-->
			<th style="border-width:3px;">Description of Cargo</th>	
			<th style="border-width:3px;">Ton</th>	
			<th style="border-width:3px;">Importer Name</th>
			<th style="border-width:3px;">Importer Address</th>
	
		</tr>
        <?php
            include("mydbPConnection.php");
            include("dbConection.php");
            include("dbOracleConnection.php");
            
            $vessel_name = "";
            $reg = "";
            $cont = "";
            $size = "";
            $seal = "";
            $status = "";
            $descOfGoods = "";
            $dgClass="";
            $berth = "";
            $sh_agent = "";
            $tonage = "";
          //  $commudity_desc = "";
            $Notify_name = "";
            $Notify_address = "";

            $i=0;
            for($i=0; $i<count($goodsRslt); $i++){
                
                $vessel_name = $goodsRslt[$i]['Vessel_Name'];
                $reg = $goodsRslt[$i]['Import_Rotation_No'];

                $sh_agent = $goodsRslt[$i]['org_name'];
                $descOfGoods = $goodsRslt[$i]['Description_of_Goods'];
                $tonage = $goodsRslt[$i]['tonage'];
              //  $commudity_desc = $goodsRslt[$i]['commudity_desc'];;
                $Notify_name = $goodsRslt[$i]['Notify_name'];
                $Notify_address = $goodsRslt[$i]['Notify_address'];



               
               
               
               $berth_query = "SELECT  DISTINCT argo_carrier_visit.ata, argo_carrier_visit.atd
               FROM inv_unit 
               INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
               INNER JOIN argo_carrier_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey 
               INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
               INNER JOIN ref_bizunit_scoped r ON r.gkey=inv_unit.line_op 
               WHERE vsl_vessel_visit_details.ib_vyg='$reg'";
				
				$arv_dt="";
				$dep_dt="";
                
                $berth_rslt=oci_parse($con_sparcsn4_oracle,$berth_query);
                $row=oci_execute($berth_rslt);
                $j=0;


                while(($berth_row = oci_fetch_object($berth_rslt)) != false){
                    $j++;
                    $arv_dt = $berth_row->ATA;
                    $dep_dt = $berth_row->ATD;
                }
        ?>

        <tr>
            <td align="center"><?php echo $i+1; ?></td>
            <td align="center"><?php echo $vessel_name; ?></td>
			<td align="center"><?php echo $sh_agent; ?></td>
            <td align="center"><?php echo $reg; ?></td>
            <td align="center"><?php echo $arv_dt; ?></td>
            <td align="center"><?php echo $dep_dt; ?></td>
            <td align="center"><?php echo $descOfGoods; ?></td>
            <td align="center"><?php echo $tonage; ?></td>
            <td align="center"><?php echo $Notify_name; ?></td>
            <td align="center"><?php echo $Notify_address; ?></td>

        </tr>

        <?php
            }
        ?>

	</table>
<br />
<br />
<?php 
if($_POST['fileOptions']=='html'){?>	
	</BODY>
</HTML>
<?php }?>