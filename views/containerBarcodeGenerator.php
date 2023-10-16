<html>
<head>
<style>

</style>

<script type="text/javascript" src="<?php echo ASSETS_JS_PATH; ?>JsBarcode.all.min.js"></script>

<!--title>Barcode Generator</title-->
</head>

<?php include("dbConection.php");
			include("dbOracleConnection.php");

		$strTruckNum="SELECT ctmsmis.mis_cf_assign_truck.number_of_truck FROM ctmsmis.mis_cf_assign_truck
		WHERE ctmsmis.mis_cf_assign_truck.cont_id='$cont_no'";
		$truckNum=mysqli_query($con_sparcsn4,$strTruckNum);
		$truckStat=mysqli_fetch_object($truckNum);
		$trStat= $truckStat->number_of_truck;	
	?>


	<?php
	
	
			$strInfoQry="SELECT inv.id,
substr(ref_equip_type.nominal_length,-2) as siz,substr(ref_equip_type.nominal_height,-2) as height,
inv.seal_nbr1,
vsl_vessels.name,g.id AS MLO,inv.category,inv.freight_kind,vsl_vessel_visit_details.ib_vyg AS rotation
FROM inv_unit inv  
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
INNER JOIN ref_bizunit_scoped g ON inv.line_op = g.gkey
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
WHERE inv.id ='$cont_no' ORDER BY inv.gkey  DESC FETCH FIRST 1 ROWS only";
			

			$rslt_n4ISOType = oci_parse($con_sparcsn4_oracle, $strInfoQry);
			oci_execute($rslt_n4ISOType);
			
			
$size="";
$height="";
$seal_nbr1="";
$name="";
$mlo="";
$category="";
$freight_kind="";
$rotation="";

			while(($rowInfoQry= oci_fetch_object($rslt_n4ISOType)) != false)
			{
				
				$name = $rowInfoQry->NAME;
				$size = $rowInfoQry->SIZ;
				$height = $rowInfoQry->HEIGHT;
				$seal_nbr1 = $rowInfoQry->SEAL_NBR1;
				$rotation = $rowInfoQry->ROTATION;
				$mlo=$rowInfoQry->MLO;
				$category=$rowInfoQry->CATEGORY;

			}


	

			// $rtnInfoQry=mysqli_query($con_sparcsn4,$strInfoQry);
			// $rowInfoQry=mysqli_fetch_object($rtnInfoQry);
	

	
			$strBarcode="SELECT ctmsmis.mis_cf_assign_truck.cont_id, ctmsmis.mis_cf_assign_truck.number_of_truck,
				ctmsmis.cont_wise_truck_dtl.bizu_gkey,cont_id, 
				ctmsmis.cont_wise_truck_dtl.encrypted_data, ctmsmis.cont_wise_truck_dtl.truck_number,entrance_gate,entrance_serial 
				FROM ctmsmis.mis_cf_assign_truck 
				INNER JOIN ctmsmis.cont_wise_truck_dtl ON ctmsmis.cont_wise_truck_dtl.cf_assign_truck_id=ctmsmis.mis_cf_assign_truck.id
				WHERE ctmsmis.mis_cf_assign_truck.cont_id='$cont_no'";
		$barcodeData=mysqli_query($con_sparcsn4,$strBarcode);
		// $barcodeStat=mysql_fetch_object($barcodeData);
		
			$i=0;	
	while($barcodeStat=mysqli_fetch_object($barcodeData)){
	$i++;

		$encrptData= $barcodeStat->encrypted_data;
	?>
	
	<body>
	<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
		<tr bgcolor="#ffffff" align="center" height="100px">
				<td colspan="8" align="center">
					<table border=0 width="100%">
						
						<tr>
							<td align="center" colspan="8"><img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
						</tr>

						<tr align="center">
							<td colspan="8"><font size="4"><b><?php echo $title;?></b></font></td>
						</tr>
						<tr align="center">
							<td colspan="8"><font size="4"><b></b></font></td>
						</tr>
					</table>
				
				</td>
				
		</tr>
		<tr>
			<td  colspan="8" align="center"><b>Truck No :<?php echo strtoupper($barcodeStat->truck_number);?></b></td>
		</tr>
		<br/>
		<tr>
			<td align="center" colspan="7"> 
				<svg id="barcode<?php echo $i;?>"></svg>
			</td>
			<script>
				JsBarcode("#barcode<?php echo $i;?>", "<?php echo $barcodeStat->encrypted_data; ?>", {
				  textAlign: "center",
				  textPosition: "bottom",
				  font: "plain",
				  fontOptions: "bold",
				  fontSize: 20,
				  textMargin: 5,
				  text: "<?php echo $barcodeStat->cont_id; ?>",
				  width:1,
				  height: 50
				});
				</script>
		</tr>
		<table align="center" border="0">
		<tr>
			<td  align="left"><b>Serial No  :<?php echo $barcodeStat->entrance_serial;?>,</b></td>
			<td  align="left"><b> Gate  : <?php echo $barcodeStat->entrance_gate;?>,</b></td>
			
			
			<td  align="left"><b> Vessel Name  : <?php echo $name;?>,</b></td>
			<td  align="left"><b> MLO  : <?php echo $mlo;?>,</b></td>
			<td  align="left"><b> Freight Kind  : <?php echo $freight_kind;?></b></td>
		</tr>
		<tr>
			<td  align="left"><b>Size  :<?php echo $size;?>,</b></td>
			<td  align="left"><b> Height  : <?php echo $height;?>,</b></td>
			<td  align="left"><b> Seal No  : <?php echo $seal_nbr1;?>,</b></td>
			<td  align="left"><b> Rotation  : <?php echo $rotation;?>,</b></td>
			<td  align="left"><b> Status  : <?php if($category=="EXPRT") { echo "EXPORT"; } else if($category=="IMPRT") { echo "IMPORT"; } else if($category=="STRGE") { echo "STORAGE"; }?></b></td>
		</tr>
		
	</table>
	
	
	
</body>
<div class="mybreak"> </div>
	<?php } ?>

</html>
<?php 
mysqli_close($con_sparcsn4);
?>