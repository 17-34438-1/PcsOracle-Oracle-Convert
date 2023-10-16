<!DOCTYPE html>
<html lang="en">
	
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-language" content="en" />
<meta http-equiv="cache-control" content="no-cache">
<!--meta http-equiv="refresh" content="360"-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--[if lt IE 9]>
<script src="../_default/js/html5shiv.js" type="text/javascript"></script>
<![endif]-->



<title>Vessel Bay View</title>
<style type="text/css">
		/*body  {
			background: url("slide7.png")no-repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}*/
		
		.grid
		{
			font-family: Verdana,Geneva,sans-serif;
			border: 1px solid #000;
			height:60px;
			width: 70px;
		}
		
		.gridcolor
		{
			font-family: Verdana,Geneva,sans-serif;
			border: 1px solid #000;
			height: 60px;
			width: 70px;
			background-color:#93E0FE;
			color:black;
			font-size: 75%;
		}
		
		.nogrid
		{
			height: 60px;
			width: 70px;
		}
		
		hr
		{ 
			/*height: 12px; 
			border: 0; 
			box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5); */
			display: block;
			margin-top: 0.5em;
			margin-bottom: 0.5em;
			margin-left: auto;
			margin-right: auto;
			border-style: inset;
			border-width: 3px;
		}
		
		.pagebreak { page-break-before: always; }
		
</style>

</head>
<body>
<?php
include('dbConection.php');
include("dbOracleConnection.php");	
//$vslId = "HCALYPSO";
$numrowPrevCont=0;
$numrowPrevContRight=0;
$numrowPrevContBelow=0;
$numrowPrevContBelowRight=0;
$vvdGkey = $_REQUEST['vvdGkey'];
$strVGkey = "select vsl_vessels.id,vsl_vessels.name,vsl_vessel_visit_details.ib_vyg,
argo_carrier_visit.ata,argo_carrier_visit.atd
from vsl_vessel_visit_details
inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
where vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
//echo $strVGkey."<hr>";
$resVGkey = oci_parse($con_sparcsn4_oracle,$strVGkey);
$rowVGkey = oci_fetch_object($resVGkey);
$vslId = $rowVGkey->ID;
$vslName = $rowVGkey->NAME;
$rot = $rowVGkey->IB_VYG;
$ata = $rowVGkey->ATA;
$atd = $rowVGkey->ATD; 
$strBay = "select * from ctmsmis.misBayView where vslId='$vslId' order by bay asc";
$resBay = mysqli_query($con_sparcsn4,$strBay);
$numRowsBay = mysqli_num_rows($resBay);
if($numRowsBay==0)
{
echo "<div align='center'><font color='red' size='5'><b>Sorry! Vessel $vslName is not drawn yet.Please contact with Datasoft people (+8801749923327) to draw this vessel...</b></font></div>";
return;
}
echo "<h1 align='center'>Vessel: $vslName, Rotation: $rot</h1>";
echo "<h2 align='center'>Arrival Time:$ata, Depart Time:$atd</h2>";
$mystat = 0;
while($rowbay = mysqli_fetch_object($resBay))
{
	//echo $rowbay->bay."<br>";
	$bay = "";
	$title = "";
	
	if($rowbay->paired == 1)
	{
		if($rowbay->bay<10)
			$title1 = "0".$rowbay->bay;
		else
			$title1 = $rowbay->bay;
		
		if($rowbay->pairedWith<10)
			$title2 = "0".$rowbay->pairedWith;
		else
			$title2 = $rowbay->pairedWith;
			
		$title=$title1."(".$title2.")";
		$bay = $title1.",".$title2;
		
	}	
	else 
	{
		if($rowbay->bay<10)
			$title = "0".$rowbay->bay;
		else
			$title = $rowbay->bay;
		
		$bay = $title;
	}
	
	$prevBay1 = $rowbay->bay-1;
	$prevBay2 = $rowbay->bay-2;
	$strChkBay = "select count(bay) as cnt from ctmsmis.misBayView where vslId='$vslId' and bay=$prevBay1";
	$resChkBay = mysqli_query($con_sparcsn4,$strChkBay);
	$rowChkBay = mysqli_fetch_object($resChkBay);
	
	$strBayState = "";
	if($rowChkBay->cnt>0)
		$strBayState = "select paired from ctmsmis.misBayView where vslId='$vslId' and bay=$prevBay1";
	else
		$strBayState = "select paired from ctmsmis.misBayView where vslId='$vslId' and bay=$prevBay2";
		
	$resBayState = mysqli_query($con_sparcsn4,$strBayState);
	$rowBayState = mysqli_fetch_object($resBayState);
	$bayState = @$rowBayState->paired;
	
	if($rowbay->bay==1 and $rowbay->paired==0)
		$mystat = 1;
	//echo $bay."<br>";
	
	$strMaxCol = "select max(maxColLimit) as maxCol from ctmsmis.misBayViewBelow where vslId='$vslId' and bay=$rowbay->bay";
	//echo $strMaxCol;
	
	$resMaxCol = mysqli_query($con_sparcsn4,$strMaxCol);
	$rowMaxCol = mysqli_fetch_object($resMaxCol);
	$maxCol = intval($rowMaxCol->maxCol);
?>
	<table align="center" cellspacing="0" cellpadding="1">		
		<tr><td></td><td colspan="<?php if($rowbay->centerLineA==1) echo $maxCol+1; else echo $maxCol;?>" class="nogrid" align="center" valign="bottom"><b><?php echo "Bay ".$title; ?></b></td></tr>
		<!-- Row leble start -->
		<tr>
			<td></td>
			<?php 
			//$maxCol = intval($rowbay->maxColLimAbv);			
			$strUpDeckLbl = "select minColLimit,maxColLimit from ctmsmis.misBayViewBelow where vslId='$vslId' and bay=$rowbay->bay";
			//echo $strBlDeck;
			$resUpDeckLbl = mysqli_query($con_sparcsn4,$strUpDeckLbl);
			$rowUpDeckLbl = mysqli_fetch_object($resUpDeckLbl);
			$minColLimitLbl = $rowUpDeckLbl->minColLimit;
			if($maxCol%2==1)
			{
				$kl = $maxCol-1;
			}
			else
			{
				$kl = $maxCol;
			}
			//echo "kl==".$kl;	
			while($kl>=$minColLimitLbl)
			{
			if($minColLimitLbl!=0){
			?>
			<td class="nogrid" align="center"><?php if($kl<10) echo "0".$kl; else echo $kl; ?></td>
			<?php 
			}
			$kl = $kl-2;
			}
			if($rowbay->centerLineA==1)
			{
			?>
			<td class="nogrid" align="center">00</td>
			<?php 
			}
			elseif($rowbay->centerLineA==0 and $rowbay->gapLineA==1)
			{
			?>
			<td class="nogrid" align="center"></td>
			<?php 
			}
			$ll = $minColLimitLbl;
			//echo $l;
			if($maxCol%2==0)
				$rLimit = $maxCol-1;
			else
				$rLimit = $maxCol;
			while($ll<=$rLimit)
			{
			?>
			<td class="nogrid" align="center"><?php if($ll<10) echo "0".$ll; else echo $ll; ?></td>
			<?php 
			$ll = $ll+2;
			}
			?>
		</tr>
		<!-- Row leble end -->
		
		<!-- Dynamic Row start -->
		<?php 
		$minRow = intval($rowbay->minRowLimAbv);	
		$maxRowAbv = intval($rowbay->maxRowLimAbv);	
		$upGapVal = $rowbay->gapUpperRow;
		$upGapValArr = explode(',',$upGapVal);
		$i=$maxRowAbv;
		$tons12 =0;
		$tons11 =0;
		$tons10 =0;
		$tons9 =0;
		$tons8 =0;
		$tons7 =0;
		$tons6 =0;
		$tons5 =0;
		$tons4 =0;
		$tons3 =0;
		$tons2 =0;
		$tons1 =0;
		$tons0 =0;
		while($i>=$minRow)
		{
			//echo $i;
			$strUpDeck = "select minColLimit,maxColLimit from ctmsmis.misBayViewBelow where vslId='$vslId' and bay=$rowbay->bay and bayRow=$i";
			//echo $strBlDeck;
			$resUpDeck = mysqli_query($con_sparcsn4,$strUpDeck);
			$rowUpDeck = mysqli_fetch_object($resUpDeck);
			$minColLimitUp = $rowUpDeck->minColLimit;
			$maxColLimitUp = $rowUpDeck->maxColLimit;
		?>
		<tr>
			<td class="nogrid" align="center"><?php echo $i; ?></td>
			<!-- Left side column -->
			<?php 
			if($maxCol%2==1)
			{
				$k = $maxCol-1;
			}
			else
			{
				$k = $maxCol;
			}
				
			while($k>=$minColLimitUp)
			{
				if($k<10)
					$kval = "0".$k;
				else
					$kval = $k;
				$gapStr = $kval.$i;
				//echo $k."<br>";
				if($k<10)
					$pos = "0".$k.$i;
				else
					$pos = $k.$i;
					
				if($rowbay->paired==0)
				{
					$rby1 = $rowbay->bay-2;
					$rby2 = $rowbay->bay-1;
					if($rby1<10)
						$rby12 = "0".$rby1;
					else
						$rby12 = $rby1;
						
					if($rby2<10)
						$rby22 = "0".$rby2;
					else
						$rby22 = $rby2;
						
					//echo $rby12.$pos."-".$rby22.$pos;
					$slot1 = $rby12.$pos;
					$slot2 = $rby22.$pos;
					
					$strPrevCont = "SELECT substr(ref_equip_type.nominal_length,-2) as siz
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
					WHERE (inv_unit_fcy_visit.last_pos_slot='$slot1' OR inv_unit_fcy_visit.last_pos_slot='$slot2') 
					AND argo_carrier_visit.cvcvd_gkey='$vvdGkey'";
					$resPrevCont = oci_parse($con_sparcsn4_oracle,$strPrevCont);
					oci_execute($resPrevCont);
					$results=array();
					$numrowPrevCont =oci_fetch_all($resPrevCont, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
					oci_free_statement($resPrevCont);
					$resPrevCont = oci_parse($con_sparcsn4_oracle,$strPrevCont);
					oci_execute($resPrevCont);
					$rowPrevCont = oci_fetch_object($resPrevCont);
				
					
				}
				
				if($numrowPrevCont>0 and $rowbay->paired==0 and $rowPrevCont->SIZ>20 and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevCont->SIZ."'";?>
					</td>
					<?php
				}
				else
				{
					$strPos = "SELECT ref_routing_point.id AS pod,inv_unit.freight_kind,inv_unit.id,r.id AS mlo, 
					substr(ref_equip_type.nominal_length,-2) AS siz, 
					CEIL((inv_unit.goods_and_ctr_wt_kg/1000)) AS tons,
					ref_equip_type.iso_group AS rfr_connect 
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
					INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
					INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
					INNER JOIN ref_unloc_code ON ref_unloc_code.cntry_code=ref_country.cntry_code 
					INNER JOIN ref_routing_point ON ref_routing_point.unloc_gkey=ref_unloc_code.gkey
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
					INNER JOIN ref_bizunit_scoped r ON r.gkey=inv_unit.line_op 
					WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND substr(inv_unit_fcy_visit.last_pos_slot,1,2) IN($bay)
					AND substr(inv_unit_fcy_visit.last_pos_slot,-4)='$pos' AND substr(inv_unit_fcy_visit.last_pos_slot,-2)>=80 
					ORDER BY inv_unit_fcy_visit.last_pos_slot";
					//echo $strPos."<br>";
					$resPos = oci_parse($con_sparcsn4_oracle,$strPos);
					oci_execute($resPos);
					$result1=array();
					$numrowPrevCont =oci_fetch_all($resPos, $result1, null, null, OCI_FETCHSTATEMENT_BY_ROW);
					oci_free_statement($resPos);
					$resPos = oci_parse($con_sparcsn4_oracle,$strPos);
					oci_execute($resPos);
					$rowPos = oci_fetch_object($resPos);
					if($minColLimitUp!=0)
					{
					?>
					<td <?php if($k >$maxColLimitUp){?>class="nogrid"<?php } elseif(in_array($gapStr, $upGapValArr)){?>class="nogrid"<?php } elseif($numrow>0) {?> class="gridcolor" <?php } else {?> class="grid" <?php }?> align="center">
					<?php
						if($numrow>0 and $k<=$maxColLimitUp)
						{
								$rfrAbvL = $rowPos->RFR_CONNECT;
								$freight_kindAbvL = $rowPos->FREIGHT_KIND;
								$txtAbvL = "";
								if($rfrAbvL == "RE" or $rfrAbvL == "RS" or $rfrAbvL == "RT" or $rfrAbvL == "HR")
									$txtAbvL = "R";
								elseif($freight_kindAbvL=="MTY")
									$txtAbvL = "E";
								elseif($freight_kindAbvL=="FCL" or $freight_kindAbvL=="LCL")
									$txtAbvL = "D";
									
								echo $rowPos->POD." ".$txtAbvL.$rowPos->SIZ."'<br/>";
								echo $rowPos->ID."<br/>";
								echo $rowPos->MLO." ".$rowPos->TONS."Ts";
								${'tons'.$k} += $rowPos->TONS;
						}
					?>
					</td>
					<?php 
					}
				}				
				$k = $k-2;
			}
			
			// for centre line
			if($rowbay->centerLineA==1 and (!in_array("00".$i, $upGapValArr)))
			{			
				$posCentre = "00".$i;
				
				if($rowbay->paired==0)
				{
					$rbyCntr1 = $rowbay->bay-2;
					$rbyCntr2 = $rowbay->bay-1;
					if($rbyCntr1<10)
						$rbyCntr12 = "0".$rbyCntr1;
					else
						$rbyCntr12 = $rbyCntr1;
						
					if($rbyCntr2<10)
						$rbyCntr22 = "0".$rbyCntr2;
					else
						$rbyCntr22 = $rbyCntr2;
						
					//echo $rby12.$pos."-".$rby22.$pos;
					$slotCntr1 = $rbyCntr12.$posCentre;
					$slotCntr2 = $rbyCntr22.$posCentre;
					
					$strPrevContCntr = "SELECT substr(ref_equip_type.nominal_length,-2) AS siz 
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					WHERE (inv_unit_fcy_visit.last_pos_slot='$slotCntr1' OR inv_unit_fcy_visit.last_pos_slot='$slotCntr2') 
					AND argo_carrier_visit.cvcvd_gkey='$vvdGkey'";
					$resPrevContCntr = oci_parse($con_sparcsn4_oracle,$strPrevContCntr);
					oci_execute($resPrevContCntr);
					$result2=array();
					$numrowPrevContCntr =oci_fetch_all($resPrevContCntr, $result2, null, null, OCI_FETCHSTATEMENT_BY_ROW);
					oci_free_statement($resPrevContCntr);
					$resPrevContCntr = oci_parse($con_sparcsn4_oracle,$strPrevContCntr);
					oci_execute($resPrevContCntr);
                    $rowPrevContCntr = oci_fetch_object($resPrevContCntr);
				
					//echo $strPrevContCntr."<hr>";
				}
				
				if(@$numrowPrevContCntr>0 and $rowbay->paired==0 and $rowPrevContCntr->SIZ>20 and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevContCntr->SIZ."'";?>
					</td>
					<?php
				}
				else
				{
					$strPosCentre = "SELECT ref_routing_point.id AS pod,inv_unit.freight_kind,inv_unit.id,r.id AS mlo, 
					substr(ref_equip_type.nominal_length,-2) AS siz, 
					CEIL((inv_unit.goods_and_ctr_wt_kg/1000)) AS tons,
					ref_equip_type.iso_group AS rfr_connect 
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
					INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
					INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
					INNER JOIN ref_unloc_code ON ref_unloc_code.cntry_code=ref_country.cntry_code 
					INNER JOIN ref_routing_point ON ref_routing_point.unloc_gkey=ref_unloc_code.gkey
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
					INNER JOIN ref_bizunit_scoped r ON r.gkey=inv_unit.line_op
					WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'
					AND substr(inv_unit_fcy_visit.last_pos_slot,1,2) IN($bay) 
					AND substr(inv_unit_fcy_visit.last_pos_slot,-4)='$posCentre' 
					AND substr(inv_unit_fcy_visit.last_pos_slot,-2)>=80 
					ORDER BY inv_unit_fcy_visit.last_pos_slot";
					//echo $strPosCentre."<hr>";
					$resPosCentre = oci_parse($con_sparcsn4_oracle,$strPosCentre);
					oci_execute($resPosCentre);
					$result3=array();
					$numrowCentre =oci_fetch_all($resPosCentre, $result3, null, null, OCI_FETCHSTATEMENT_BY_ROW);
					oci_free_statement($resPosCentre);

					$resPosCentre = oci_parse($con_sparcsn4_oracle,$strPosCentre);
					oci_execute($resPosCentre);
                    $rowPosCentre = oci_fetch_object($resPosCentre);
					
					?>
					<!-- Center column -->
					<td <?php if($numrowCentre>0) {?> class="gridcolor" <?php } else {?> class="grid" <?php }?> align="center">
						<?php
							if($numrowCentre>0)
							{
									$rfrAbvC = $rowPosCentre->RFR_CONNECT;
									$freight_kindAbvC = $rowPosCentre->FREIGHT_KIND;
									$txtAbvC = "";
									if($rfrAbvC == "RE" or $rfrAbvC == "RS" or $rfrAbvC == "RT" or $rfrAbvC == "HR")
										$txtAbvC = "R";
									elseif($freight_kindAbvC=="MTY")
										$txtAbvC = "E";
									elseif($freight_kindAbvC=="FCL" or $freight_kindAbvC=="LCL")
										$txtAbvC = "D";
										
									echo $rowPosCentre->POD." ".$txtAbvC.$rowPosCentre->SIZ."'<br/>";
									echo $rowPosCentre->ID."<br/>";
									echo $rowPosCentre->MLO." ".$rowPosCentre->TONS."Ts";
									$tons0 += $rowPosCentre->TONS;
							}
						?>
					</td>
					
					<!-- Right side column -->
					<?php 
				}
			}
			elseif(in_array("00".$i, $upGapValArr))
			{			
				?>
				<!-- Center column -->
				<td class="nogrid"></td>
				
				<!-- Right side column -->
				<?php 
			}
			elseif($rowbay->centerLineA==0 and $rowbay->gapLineA==1)
			{			
			?>
				<!-- Center column -->
				<td class="nogrid"></td>
				
				<!-- Right side column -->
				<?php 
			}
			
			$l = $minColLimitUp;
			//echo $l;
			if($maxCol%2==0)
				$rcLimit = $maxCol-1;
			else
				$rcLimit = $maxCol;
			while($l<=$rcLimit)
			{
				if($l<10)
					$lval = "0".$l;
				else
					$lval = $l;
				$gapStrRisht = $lval.$i;
				
				if($l<10)
				$posRight = "0".$l.$i;
				else
				$posRight = $l.$i;
				
				if($rowbay->paired==0)
				{
					$rbyRight1 = $rowbay->bay-2;
					$rbyRight2 = $rowbay->bay-1;
					if($rbyRight1<10)
						$rbyRight12 = "0".$rbyRight1;
					else
						$rbyRight12 = $rbyRight1;
						
					if($rbyRight2<10)
						$rbyRight22 = "0".$rbyRight2;
					else
						$rbyRight22 = $rbyRight2;
						
					//echo $rby12.$pos."-".$rby22.$pos;
					$slotRight1 = $rbyRight12.$posRight;
					$slotRight2 = $rbyRight22.$posRight;
					
					$strPrevContRight = "SELECT SUBSTR(ref_equip_type.nominal_length,-2) AS siz 
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
					WHERE (inv_unit_fcy_visit.last_pos_slot='$slotRight1' OR inv_unit_fcy_visit.last_pos_slot='$slotRight2') 
					AND argo_carrier_visit.cvcvd_gkey='$vvdGkey'";
					$resPrevContRight = oci_parse($con_sparcsn4_oracle,$strPrevContRight);
					oci_execute($resPrevContRight);
					$result4=array();
					$numrowPrevContRight =oci_fetch_all($resPrevContRight, $result4, null, null, OCI_FETCHSTATEMENT_BY_ROW);
					oci_free_statement($resPrevContRight);
					$resPrevContRight = oci_parse($con_sparcsn4_oracle,$strPrevContRight);
					oci_execute($resPrevContRight);
                    $rowPrevContRight = oci_fetch_object($resPrevContRight);
					//echo $pos."=".$numrowPrevCont."-".$rowPrevCont->size;
				}
				
				if($numrowPrevContRight>0 and $rowbay->paired==0 and $rowPrevContRight->SIZ>20 and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevContRight->SIZ."'";?>
					</td>
					<?php
				}
				else
				{
				$strPosRight = "SELECT ref_routing_point.id AS pod,inv_unit.freight_kind,inv_unit.id,r.id AS mlo, 
				substr(ref_equip_type.nominal_length,-2) AS siz, 
				CEIL((inv_unit.goods_and_ctr_wt_kg/1000)) AS tons,
				ref_equip_type.iso_group AS rfr_connect 
				FROM inv_unit
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
				INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
				INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
				INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
				INNER JOIN ref_unloc_code ON ref_unloc_code.cntry_code=ref_country.cntry_code 
				INNER JOIN ref_routing_point ON ref_routing_point.unloc_gkey=ref_unloc_code.gkey
				INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
				INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
				INNER JOIN ref_bizunit_scoped r ON r.gkey=inv_unit.line_op
				WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND substr(inv_unit_fcy_visit.last_pos_slot,1,2) IN($bay) 
				AND substr(inv_unit_fcy_visit.last_pos_slot,-4)='$posRight' AND substr(inv_unit_fcy_visit.last_pos_slot,-2)>=80 
				ORDER BY inv_unit_fcy_visit.last_pos_slot";
				//echo $strPosRight."<hr>";
				$resPosRight = oci_parse($con_sparcsn4_oracle,$strPosRight);
				oci_execute($resPosRight);
				$result5=array();
				$numrowRight =oci_fetch_all($resPosRight, $result5, null, null, OCI_FETCHSTATEMENT_BY_ROW);
				oci_free_statement($resPosRight);
				$resPosRight = oci_parse($con_sparcsn4_oracle,$strPosRight);
				oci_execute($resPosRight);
				$rowPosRight = oci_fetch_object($resPosRight);
				?>
				<td <?php if($l >$maxColLimitUp){?>class="nogrid"<?php } elseif(in_array($gapStrRisht, $upGapValArr)){?>class="nogrid"<?php } elseif($numrowRight>0) {?> class="gridcolor" <?php } else {?> class="grid" <?php }?> align="center">
					<?php
						if($numrowRight>0 and $l<=$maxColLimitUp)
						{
								$rfrAbvR = $rowPosRight->RFR_CONNECT;
								$freight_kindAbvR = $rowPosRight->FREIGHT_KIND;
								$txtAbvR = "";
								if($rfrAbvR == "RE" or $rfrAbvR == "RS" or $rfrAbvR == "RT" or $rfrAbvR == "HR")
									$txtAbvR = "R";
								elseif($freight_kindAbvR=="MTY")
									$txtAbvR = "E";
								elseif($freight_kindAbvR=="FCL" or $freight_kindAbvR=="LCL")
									$txtAbvR = "D";
									
								echo $rowPosRight->POD." ".$txtAbvR.$rowPosRight->SIZ."'<br/>";
								echo $rowPosRight->ID."<br/>";
								echo $rowPosRight->MLO." ".$rowPosRight->TONS."Ts";
								${'tons'.$l} += $rowPosRight->TONS;
						}
					?>
				</td>
				<?php 
				}
				$l = $l+2;	
			}
			?>
			<td class="nogrid" align="center"><?php echo $i; ?></td>
		</tr>
		<?php 
		$i=$i-2;
		}
		?>
		<!-- Calculation for total Tons start-->
		<tr>
		<td align="center" style="font-family: Verdana,Geneva,sans-serif;">Total</td>
		<!-- Calculation for Left side Total Tons-->
			<?php
				if($maxCol%2==1)
				{
					$tL = $maxCol-1;
				}
				else
				{
					$tL = $maxCol;
				}
				while($tL>=$minColLimitUp)
				{
			?>
					<td align="center" style="font-family: Verdana,Geneva,sans-serif;"><?php echo ${'tons'.$tL}.'Ts'; ?></td>
			<?php
				$tL = $tL-2;
				}
				
				if($rowbay->centerLineA==1)
				{
			?>
					<td align="center" style="font-family: Verdana,Geneva,sans-serif;"><?php echo $tons0."Ts"; ?></td>
			<?php
				}
				elseif($rowbay->centerLineA==0 and $rowbay->gapLineA==1)
				{			
				?>
					<!-- Center column -->
					<td></td>
					
					<!-- Right side column -->
					<?php 
				}
				$tR = $minColLimitUp;
				//echo $l;
				if($maxCol%2==0)
					$rcLimit = $maxCol-1;
				else
					$rcLimit = $maxCol;
				while($tR<=$rcLimit)
				{
			?>
				<td align="center" style="font-family: Verdana,Geneva,sans-serif;"><?php echo ${'tons'.$tR}.'Ts'; ?></td>
			<?php
				$tR = $tR+2;
				}
			?>
		</tr>
		<!-- Calculation for total Tons end-->
		<?php
		if($rowbay->isBelow==1)
		{
		?>
		
		<!-- Dynamic Row end -->
		<tr><td></td><td colspan="<?php if($rowbay->centerLineA==1) echo $maxCol+1; else echo $maxCol;?>" align="center"><hr></td></tr>
		
		<!-- Below part start -->
		<?php 
		$b = $rowbay->maxRowLimBlw;
		$bMin = $rowbay->minRowLimBlw;
		$upGapValBelow = $rowbay->gapLowerRow;
		$upGapValArrBelow = explode(',',$upGapValBelow);
		$tonsB12 =0;
		$tonsB11 =0;
		$tonsB10 =0;
		$tonsB9 =0;
		$tonsB8 =0;
		$tonsB7 =0;
		$tonsB6 =0;
		$tonsB5 =0;
		$tonsB4 =0;
		$tonsB3 =0;
		$tonsB2 =0;
		$tonsB1 =0;
		$tonsB0 =0;
		
		while($b>=$bMin)
		{
		?>
		<tr>
			<td class="nogrid" align="center"><?php if($b<10){echo "0".$b;}else{echo $b;} ?></td>
		<?php
			$strBlDeck = "select minColLimit,maxColLimit from ctmsmis.misBayViewBelow where vslId='$vslId' and bay=$rowbay->bay and bayRow=$b";
			//echo $strBlDeck;
			$resBlDeck = mysqli_query($con_sparcsn4,$strBlDeck);
			$rowBlDeck = mysqli_fetch_object($resBlDeck);
			$minColLimit = $rowBlDeck->minColLimit;
			$maxColLimit = $rowBlDeck->maxColLimit;
			//echo "M=".$maxColLimit."<br>";
			if($maxCol%2==1)
			{
				$kbelow = $maxCol-1;
			}
			else
			{
				$kbelow = $maxCol;
			}
			if($rowbay->centerLineA==0 and $rowbay->centerLineB!=0)
				$kbelow = $kbelow-2;
			else if($rowbay->centerLineA==0 and $rowbay->centerLineB==0)
				$kbelow = $kbelow;
			while($kbelow>=2)
			{
			//echo "KB=".$kbelow."<br>";
				if($b<10)
					$bb = "0".$b;
				else
					$bb = $b;
					
				if($kbelow<10)
					$cc = "0".$kbelow;
				else
					$cc = $kbelow;
					
				$posbelow = $cc.$bb;
				//$gapStr = $kval.$i;
				if($rowbay->paired==0)
				{
					$rbyBelow1 = $rowbay->bay-2;
					$rbyBelow2 = $rowbay->bay-1;
					if($rbyBelow1<10)
						$rbyBelow12 = "0".$rbyBelow1;
					else
						$rbyBelow12 = $rbyBelow1;
						
					if($rbyBelow2<10)
						$rbyBelow22 = "0".$rbyBelow2;
					else
						$rbyBelow22 = $rbyBelow2;
						
					//echo $rby12.$pos."-".$rby22.$pos;
					$slotBelow1 = $rbyBelow12.$posbelow;
					$slotBelow2 = $rbyBelow22.$posbelow;
					
					$strPrevContBelow = "SELECT substr(ref_equip_type.nominal_length,-2) AS siz 
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
					WHERE (inv_unit_fcy_visit.last_pos_slot='$slotBelow1' OR inv_unit_fcy_visit.last_pos_slot='$slotBelow2') 
					AND argo_carrier_visit.cvcvd_gkey='$vvdGkey'";
					$resPrevContBelow = oci_parse($con_sparcsn4_oracle,$strPrevContBelow);
					oci_execute($resPrevContBelow);
					$result6=array();
				    $numrowPrevContBelow =oci_fetch_all($resPrevContBelow, $result6, null, null, OCI_FETCHSTATEMENT_BY_ROW);
				    oci_free_statement($resPrevContBelow);

					$resPrevContBelow = oci_parse($con_sparcsn4_oracle,$strPrevContBelow);
					oci_execute($resPrevContBelow);
					$rowPrevContBelow = oci_fetch_object($resPrevContBelow);
				
					//echo $pos."=".$numrowPrevCont."-".$rowPrevCont->size;
				}
				
				
				if($numrowPrevContBelow>0 and $rowbay->paired==0 and $rowPrevContBelow->SIZ>20 and $kbelow <=$maxColLimit and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevContBelow->SIZ."'";?>
					</td>
					<?php
				}
				else
				{
					$strPosbelow = "SELECT ref_routing_point.id AS pod,inv_unit.freight_kind,inv_unit.id,r.id AS mlo, 
					substr(ref_equip_type.nominal_length,-2) AS siz, 
					CEIL((inv_unit.goods_and_ctr_wt_kg/1000)) AS tons,
					ref_equip_type.iso_group AS rfr_connect 
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
					INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
					INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
					INNER JOIN ref_unloc_code ON ref_unloc_code.cntry_code=ref_country.cntry_code 
					INNER JOIN ref_routing_point ON ref_routing_point.unloc_gkey=ref_unloc_code.gkey
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
					INNER JOIN ref_bizunit_scoped r ON r.gkey=inv_unit.line_op
					WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND substr(inv_unit_fcy_visit.last_pos_slot,1,2) IN($bay) 
					AND substr(inv_unit_fcy_visit.last_pos_slot,-4)='$posbelow' AND substr(inv_unit_fcy_visit.last_pos_slot,-2)<80 
					ORDER BY inv_unit_fcy_visit.last_pos_slot";
					//echo $strPosbelow."<br>";
					$resPosbelow = oci_parse($con_sparcsn4_oracle,$strPosbelow);
                    oci_execute($resPosbelow);
				    $result7=array();
				    $numrowbelow =oci_fetch_all($resPosbelow, $result7, null, null, OCI_FETCHSTATEMENT_BY_ROW);
				    oci_free_statement($resPosbelow);
					$resPosbelow = oci_parse($con_sparcsn4_oracle,$strPosbelow);
                    oci_execute($resPosbelow);
					$rowPosbelow = oci_fetch_object($resPosbelow);
					
					?>
					<td <?php if($numrowbelow>0 and $kbelow <=$maxColLimit) {?> class="gridcolor" <?php } elseif(in_array($posbelow, $upGapValArrBelow)){?>class="nogrid"<?php } elseif($kbelow >$maxColLimit){?>class="nogrid"<?php } else {?>class="grid" <?php } ?> align="center">
						<?php 
							
							if($numrowbelow>0 and $kbelow <=$maxColLimit)
							{
								$rfr = $rowPosbelow->RFR_CONNECT;
								$freight_kind = $rowPosbelow->FREIGHT_KIND;
								$txt = "";
								if($rfr == "RE" or $rfr == "RS" or $rfr == "RT" or $rfr == "HR")
									$txt = "R";
								elseif($freight_kind=="MTY")
									$txt = "E";
								elseif($freight_kind=="FCL" or $freight_kind=="LCL")
									$txt = "D";
									
								echo $rowPosbelow->POD." ".$txt.$rowPosbelow->SIZ."'<br/>";
								echo $rowPosbelow->ID."<br/>";
								echo $rowPosbelow->MLO." ".$rowPosbelow->TONS."Ts";
								${'tonsB'.$kbelow} += $rowPosbelow->TONS;
							}
					?>
					</td>
					<?php 	
				}
				$kbelow = $kbelow-2;
			} 
			// for centre line
			//if($rowbay->centerLineA==1 and (!in_array("00".$i, $upGapValArr)))
			if($rowbay->centerLineB==1 and (!in_array("00".$b, $upGapValArrBelow)))
			{	
				if($b<10)
					$cb = "0".$b;
				else
					$cb = $b;
				$posCentreBelow = "00".$cb;
			
				if($rowbay->paired==0)
				{
					$rbyBelowCntr1 = $rowbay->bay-2;
					$rbyBelowCntr2 = $rowbay->bay-1;
					if($rbyBelowCntr1<10)
						$rbyBelowCntr12 = "0".$rbyBelowCntr1;
					else
						$rbyBelowCntr12 = $rbyBelowCntr1;
						
					if($rbyBelowCntr2<10)
						$rbyBelowCntr22 = "0".$rbyBelowCntr2;
					else
						$rbyBelowCntr22 = $rbyBelowCntr2;
						
					//echo $rby12.$pos."-".$rby22.$pos;
					$slotBelowCntr1 = $rbyBelowCntr12.$posCentreBelow;
					$slotBelowCntr2 = $rbyBelowCntr22.$posCentreBelow;
					
					$strPrevContBelowCntr = "SELECT substr(ref_equip_type.nominal_length,-2) AS siz
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					WHERE (inv_unit_fcy_visit.last_pos_slot='$slotBelowCntr1' OR inv_unit_fcy_visit.last_pos_slot='$slotBelowCntr2') 
					AND argo_carrier_visit.cvcvd_gkey=$vvdGkey";
					$resPrevContBelowCntr = oci_parse($con_sparcsn4_oracle,$strPrevContBelowCntr);
					oci_execute($resPrevContBelowCntr);
					$result8=array();
				    $numrowPrevContBelowCntr =oci_fetch_all($resPrevContBelowCntr, $result8, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                    oci_free_statement($resPrevContBelowCntr);
					$resPrevContBelowCntr = oci_parse($con_sparcsn4_oracle,$strPrevContBelowCntr);
					oci_execute($resPrevContBelowCntr);
					$rowPrevContBelowCntr = oci_fetch_object($resPrevContBelowCntr);
	
					//echo $pos."=".$numrowPrevCont."-".$rowPrevCont->size;
				}
				
				if(@$numrowPrevContBelowCntr>0 and $rowbay->paired==0 and $rowPrevContBelowCntr->SIZ>20 and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevContBelowCntr->SIZ."'";?>
					</td>
					<?php
				}
				else
				{
					$strPosCentreBelow = "SELECT ref_routing_point.id AS pod,inv_unit.freight_kind,inv_unit.id,r.id AS mlo, 
					substr(ref_equip_type.nominal_length,-2) AS siz, 
					CEIL((inv_unit.goods_and_ctr_wt_kg/1000)) AS tons,
					ref_equip_type.iso_group AS rfr_connect 
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
					INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
					INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
					INNER JOIN ref_unloc_code ON ref_unloc_code.cntry_code=ref_country.cntry_code 
					INNER JOIN ref_routing_point ON ref_routing_point.unloc_gkey=ref_unloc_code.gkey
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
					INNER JOIN ref_bizunit_scoped r ON r.gkey=inv_unit.line_op
					WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND substr(inv_unit_fcy_visit.last_pos_slot,1,2) IN($bay) 
					AND substr(inv_unit_fcy_visit.last_pos_slot,-4)='$posCentreBelow' AND substr(inv_unit_fcy_visit.last_pos_slot,-2)<80 
					ORDER BY inv_unit_fcy_visit.last_pos_slot";
					//echo $pos."<br>";
					$resPosCentreBelow = oci_execute($con_sparcsn4_oracle,$strPosCentreBelow);
					oci_execute($resPosCentreBelow);
					$result9=array();
				    $numrowCentreBelow =oci_fetch_all($resPosCentreBelow, $result9, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                    oci_free_statement($resPosCentreBelow);
					$resPosCentreBelow = oci_execute($con_sparcsn4_oracle,$strPosCentreBelow);
					oci_execute($resPosCentreBelow);
					$rowPosCentreBelow = oci_fetch_object($resPosCentreBelow);
					?>
					<!-- Center column -->
					<td <?php if($numrowCentreBelow>0) {?> class="gridcolor" <?php } else {?> class="grid" <?php }?> align="center">
							<?php
								if($numrowCentreBelow>0)
								{
									$rfrCtr = $rowPosCentreBelow->RFR_CONNECT ;
									$freight_kindCtr = $rowPosCentreBelow->FREIGHT_KIND;
									$txtCtr = "";
									if($rfrCtr == "RE" or $rfrCtr == "RS" or $rfrCtr == "RT" or $rfrCtr == "HR")
										$txtCtr = "R";
									elseif($freight_kindCtr=="MTY")
										$txtCtr = "E";
									elseif($freight_kindCtr=="FCL" or $freight_kindCtr=="LCL")
										$txtCtr = "D";
										
									echo $rowPosCentreBelow->POD." ".$txtCtr.$rowPosCentreBelow->SIZ."'<br/>";
									echo $rowPosCentreBelow->ID."<br/>";
									echo $rowPosCentreBelow->MLO." ".$rowPosCentreBelow->TONS."Ts";
									$tonsB0 += $rowPosCentreBelow->TONS;
								}
							?>
					</td>			
					<!-- Right side column -->
					<?php	
				}
			}
			elseif($rowbay->centerLineB==0 and $rowbay->gapLineB==1)
			{
			?>
			<td class="nogrid"></td>
			<?php 
			}
			
			$lBelow = 1;
			//echo $l;
			if($maxCol%2==0)
				$rcLimitBelow = $maxCol-1;
			else
				$rcLimitBelow = $maxCol;
			//echo "l==".$rcLimitBelow;
			//echo "m==".$maxColLimit."<br>";
			while($lBelow<=$rcLimitBelow)
			{
			//echo "LB=".$lBelow."M=".($maxColLimit-1)."<br>";
				if($b<10)
				$posRightBelow = "0".$lBelow."0".$b;
				else
				$posRightBelow = "0".$lBelow.$b;
				
				if($rowbay->paired==0)
				{
					$rbyBelowRight1 = $rowbay->bay-2;
					$rbyBelowRight2 = $rowbay->bay-1;
					if($rbyBelowRight1<10)
						$rbyBelowRight12 = "0".$rbyBelowRight1;
					else
						$rbyBelowRight12 = $rbyBelowRight1;
						
					if($rbyBelowRight2<10)
						$rbyBelowRight22 = "0".$rbyBelowRight2;
					else
						$rbyBelowRight22 = $rbyBelowRight2;
						
					//echo $rby12.$pos."-".$rby22.$pos;
					$slotBelowRight1 = $rbyBelowRight12.$posRightBelow;
					$slotBelowRight2 = $rbyBelowRight22.$posRightBelow;
					
					$strPrevContBelowRight = "SELECT SUBSTR(ref_equip_type.nominal_length,-2) AS siz
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
					WHERE (inv_unit_fcy_visit.last_pos_slot='$slotBelowRight1' OR inv_unit_fcy_visit.last_pos_slot='$slotBelowRight2') 
					AND argo_carrier_visit.cvcvd_gkey='$vvdGkey'";
					$resPrevContBelowRight = oci_parse($con_sparcsn4_oracle,$strPrevContBelowRight);
					oci_execute($resPrevContBelowRight);
					$result10=array();
				    $numrowPrevContBelowRight =oci_fetch_all($resPrevContBelowRight, $result10, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                    oci_free_statement($resPrevContBelowRight);
					$resPrevContBelowRight = oci_parse($con_sparcsn4_oracle,$strPrevContBelowRight);
					oci_execute($resPrevContBelowRight);
                    $rowPrevContBelowRight = oci_fetch_object($resPrevContBelowRight);
				
					//echo $pos."=".$numrowPrevCont."-".$rowPrevCont->size;
				}
				
				if($numrowPrevContBelowRight>0 and $rowbay->paired==0 and $rowPrevContBelowRight->SIZ>20 and $lBelow <=$maxColLimit-1 and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevContBelowRight->SIZ."'";?>
					</td>
					<?php
				}
				else
				{				
					$strPosRightBelow = "SELECT ref_routing_point.id AS pod,inv_unit.freight_kind,inv_unit.id,r.id AS mlo, 
					substr(ref_equip_type.nominal_length,-2) AS siz, 
					CEIL((inv_unit.goods_and_ctr_wt_kg/1000)) AS tons,
					ref_equip_type.iso_group AS rfr_connect 
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
					INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
					INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
					INNER JOIN ref_unloc_code ON ref_unloc_code.cntry_code=ref_country.cntry_code 
					INNER JOIN ref_routing_point ON ref_routing_point.unloc_gkey=ref_unloc_code.gkey 
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
					INNER JOIN ref_bizunit_scoped r ON r.gkey=inv_unit.line_op
					WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND substr(inv_unit_fcy_visit.last_pos_slot,1,2) IN($bay) 
					AND substr(inv_unit_fcy_visit.last_pos_slot,-4)='$posRightBelow' AND substr(inv_unit_fcy_visit.last_pos_slot,-2)<80 
					ORDER BY inv_unit_fcy_visit.last_pos_slot";
					//echo $strPosRightBelow."<br>";
					$resPosRightBelow = oci_parse($con_sparcsn4_oracle,$strPosRightBelow);
					oci_execute($resPosRightBelow);
					$result11=array();
				    $numrowRightBelow =oci_fetch_all($resPosRightBelow, $result11, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                    oci_free_statement($resPosRightBelow);

					$resPosRightBelow = oci_parse($con_sparcsn4_oracle,$strPosRightBelow);
					oci_execute($resPosRightBelow);
					$rowPosRightBelow = oci_fetch_object($resPosRightBelow);

					?>		
					<td <?php if($numrowRightBelow>0 and $lBelow <=$maxColLimit-1) {?> class="gridcolor" <?php } elseif(in_array($posRightBelow, $upGapValArrBelow)){?>class="nogrid"<?php } elseif($lBelow >$maxColLimit){?>class="nogrid"<?php } else {?> class="grid" <?php }?> align="center">
						<?php
							if($numrowRightBelow>0 and $lBelow <=$maxColLimit-1)
							{
								$rfrBlw = $rowPosRightBelow->RFR_CONNECT ;
								$freight_kindBlw = $rowPosRightBelow->FREIGHT_KIND;
								$txtBlw = "";
								if($rfrBlw == "RE" or $rfrBlw == "RS" or $rfrBlw == "RT" or $rfrBlw == "HR")
									$txtBlw = "R";
								elseif($freight_kindBlw=="MTY")
									$txtBlw = "E";
								elseif($freight_kindBlw=="FCL" or $freight_kindBlw=="LCL")
									$txtBlw = "D";
									
								echo $rowPosRightBelow->POD." ".$txtBlw.$rowPosRightBelow->SIZ."'<br/>";
								echo $rowPosRightBelow->ID."<br/>";
								echo $rowPosRightBelow->MLO." ".$rowPosRightBelow->TONS."Ts";
								${'tonsB'.$lBelow} += $rowPosRightBelow->TONS;
							}
						?>
					</td>	
					<?php
				}	
				$lBelow = $lBelow+2;	
			}
			
			?>
			<td class="nogrid" align="center"><?php if($b<10){echo "0".$b;}else{echo $b;} ?></td>
			
		</tr>
				
		<?php 
		$b = $b-2;
		} 
		?>
		
		<!-- Calculation for total Tons start-->
		<tr>
		<td align="center" style="font-family: Verdana,Geneva,sans-serif;">Total</td>
		<!-- Calculation for Left side Total Tons-->
			<?php
				$strBlDeckMC = "select max(maxColLimit) as mc from ctmsmis.misBayViewBelow where vslId='$vslId' and bay=$rowbay->bay and bayRow<80";
				//$strBlDeck = "select minColLimit,maxColLimit from ctmsmis.misBayViewBelow where vslId='$vslId' and bay=$rowbay->bay";
				//echo $strBlDeck;
				$resBlDeckMC = mysqli_query($con_sparcsn4,$strBlDeckMC);
				$rowBlDeckMC = mysqli_fetch_object($resBlDeckMC);
				$mc = $rowBlDeckMC->mc;
				
				if($maxCol%2==1)
				{
					$kbelowT = $maxCol-1;
				}
				else
				{
					$kbelowT = $maxCol;
				}
				if($rowbay->centerLineA==0 and $rowbay->centerLineB!=0)
					$kbelowT = $kbelowT-2;
				else if($rowbay->centerLineA==0 and $rowbay->centerLineB==0)
					$kbelowT = $kbelowT;
					
				while($kbelowT>=2)
				{
			?>
					<td align="center" style="font-family: Verdana,Geneva,sans-serif;"><?php if($kbelowT <=$mc) {echo ${'tonsB'.$kbelowT}.'Ts';} else {echo "";} ?></td>
			<?php
				$kbelowT = $kbelowT-2;
				}
				
				if($rowbay->centerLineB==1)
				{
			?>
					<td align="center" style="font-family: Verdana,Geneva,sans-serif;"><?php echo $tonsB0."Ts"; ?></td>
			<?php
				}
				elseif($rowbay->centerLineB==0 and $rowbay->gapLineB==1)
				{
				?>
				<td></td>
				<?php 
				}
				$lBelowT = 1;
				//echo $l;
				if($maxCol%2==0)
					$rcLimitBelowT = $maxCol-1;
				else
					$rcLimitBelowT = $maxCol;
				while($lBelowT<=$rcLimitBelowT)
				{
			?>
				<td align="center" style="font-family: Verdana,Geneva,sans-serif;"><?php if($lBelowT <=$mc-1){echo ${'tonsB'.$lBelowT}.'Ts';} else {echo "";} ?></td>
			<?php
				$lBelowT = $lBelowT+2;
				}
			?>
		</tr>
		<!-- Calculation for total Tons end-->
		
		<!-- below label-->
		<tr>
			<td></td>
			<?php 
			//$maxCol = intval($rowbay->maxColLimAbv);			
				
			if($maxCol%2==1)
			{
				$kl = $maxCol-1;
			}
			else
			{
				$kl = $maxCol;
			}
			//echo "kl==".$kl;	
			if($rowbay->centerLineA==0 and $rowbay->centerLineB!=0)
				$kl = $kl-2;
			if($rowbay->centerLineA==0 and $rowbay->centerLineB==0)
				$kl = $kl;
			while($kl>=02)
			{
			?>
			<td class="nogrid" align="center"><?php if($kl<10) echo "0".$kl; else echo $kl; ?></td>
			<?php 
			$kl = $kl-2;
			}
			if($rowbay->centerLineB==1)
			{
			?>
			<td class="nogrid" align="center">00</td>
			<?php 
			}
			elseif($rowbay->centerLineB==0 and $rowbay->gapLineB==1)
			{
			?>
			<td class="nogrid" align="center"></td>
			<?php
			}
			$ll = 1;
			//echo $l;
			if($maxCol%2==0)
				$rLimit = $maxCol-1;
			else
				$rLimit = $maxCol;
			
			if($rowbay->centerLineA==0 and $rowbay->centerLineB!=0)
				$rLimit = $rLimit-2;
			if($rowbay->centerLineA==0 and $rowbay->centerLineB==0)
				$rLimit = $rLimit;
			while($ll<=$rLimit)
			{
			?>
			<td class="nogrid" align="center"><?php if($ll<10) echo "0".$ll; else echo $ll; ?></td>
			<?php 
			$ll = $ll+2;
			}
			}
			?>
		</tr>
		<tr>
			<td colspan="12">&nbsp;</td>
		</tr>
	</table>
	<span class="pagebreak"> </span>
<?php 
} 
?>
</body>
</html>
