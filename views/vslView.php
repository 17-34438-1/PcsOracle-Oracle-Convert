<!DOCTYPE html>
<html lang="en">
	
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-language" content="en" />
<meta http-equiv="cache-control" content="no-cache">

<meta name="viewport" content="width=device-width, initial-scale=1.0">



<title>Vessel Bay View</title>

<style type="text/css">
	
		
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

$numrowPrevCont=0;
$numrowPrevContRight=0;
$numrowPrevContBelow=0;
$numrowPrevContBelowRight=0;


// GET GKEY BY USING ROTATION START
$vsl_rotation = $_REQUEST['vsl_rotation'];





$str = "select vsl_vessel_visit_details.vvd_gkey,vsl_vessel_visit_details.ib_vyg
from vsl_vessel_visit_details
inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
where ib_vyg='$vsl_rotation'";

$sqlVvvGkeyRes=oci_parse($con_sparcsn4_oracle,$sql);
oci_execute($sqlVvvGkeyRes);

$vvdGkey = "";
$cond = "";

while(($row = oci_fetch_object($sqlVvvGkeyRes)) != false)
{
	$vvdGkey = $row->VVD_GKEY;
}



		
		$strVGkey = "select vsl_vessels.id,vsl_vessels.name,vsl_vessel_visit_details.ib_vyg,vsl_vessel_visit_details.vvd_gkey,
		argo_carrier_visit.ata,argo_carrier_visit.atd
		from vsl_vessel_visit_details
		inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		where vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
		$sqlVvvGkeyRes=oci_parse($con_sparcsn4_oracle,$strVGkey);
		oci_execute($sqlVvvGkeyRes);
		$vslId	="";
		$vslName ="";
		$rot ="";
		$ata="";
		$atd="";
		$vvdGkey = "";
		$cond = "";

		while(($row = oci_fetch_object($sqlVvvGkeyRes)) != false)
		{
		$vslId = $row->ID;
		$vslName = $row->NAME;
		$rot = $row->IB_VYG;
		$ata = $row->ATA;
		$atd = $row->ATD;
		}

// $resVGkey = mysqli_query($con_sparcsn4,$strVGkey);
// $rowVGkey = mysqli_fetch_object($resVGkey);
// $vslId = $rowVGkey->id;
// $vslName = $rowVGkey->name;
// $rot = $rowVGkey->ib_vyg;
// $ata = $rowVGkey->ata;
// $atd = $rowVGkey->atd;


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
	$paired = "";
	if($rowChkBay->cnt>0)
		$strBayState = "select paired from ctmsmis.misBayView where vslId='$vslId' and bay=$prevBay1";
	else
		$strBayState = "select paired from ctmsmis.misBayView where vslId='$vslId' and bay=$prevBay2";
		
	$resBayState = mysqli_query($con_sparcsn4,$strBayState);
	while($rowBayState = mysqli_fetch_object($resBayState))
	{
		$paired = $rowBayState->paired;
	}
	$bayState = $paired;
	
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
		//$tons13 and $tons14 are accessed dynamically but initialized statically like $tons12
		$tons14 =0;
		$tons13 =0;
		
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
						
					
					$slot1 = $rby12.$pos;
					$slot2 = $rby22.$pos;
					
				



					$strPrevCont = "SELECT ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					WHERE (stowage_pos='$slot1' OR stowage_pos='$slot2') AND ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey";



					$resPrevCont = mysqli_query($con_sparcsn4,$strPrevCont);
					$rowPrevCont = mysqli_fetch_object($resPrevCont);
					$numrowPrevCont = mysqli_num_rows($resPrevCont);

					$resPrevCont=mysqli_query($con_sparcsn4,$strPrevCont);
					$i=0;
					$j=0;
					
					$mlo="";
					$vvdgkey="";
					while($rowPrevCont=mysqli_fetch_object($resPrevCont)){
					$i++;
				
					$vvdgkey=$rowPrevCont->gkey;





					$sql2="
					select inv_unit.gkey,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS size
					from inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					where inv_unit.gkey='$vvdgkey'
					";
					
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						$rowPrevCont=oci_execute($strQuery2Res);
						$results=array();
						$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
						oci_free_statement($strQuery2Res);
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						oci_execute($strQuery2Res);
						$rowShortName=oci_fetch_object($strQuery2Res);
					

					}



				}
				
				if($numrowPrevCont>0 and $rowbay->paired==0 and $rowPrevCont->size>20 and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevCont->size."'";?>
					</td>
					<?php
				}
				else
				{
				
					
					$strPos = "
					SELECT  ctmsmis.mis_exp_unit.pod,CEIL((ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg/1000)) AS tons,cont_mlo AS mlo,ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					WHERE ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey 
					AND LEFT(stowage_pos,2) IN($bay) AND RIGHT(stowage_pos,4)='$pos' AND RIGHT(stowage_pos,2)>=80 ORDER BY stowage_pos
					";




					$resPos = mysqli_query($con_sparcsn4,$strPos);
					$rowPos = mysqli_fetch_object($resPos);
					$numrow = mysqli_num_rows($resPos);

					$vvdgkey=$rowPos->gkey;



					$sql2="
					SELECT inv_unit.freight_kind,inv_unit.id,ref_equip_type.iso_group AS rfr_connect 
					FROM inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					INNER JOIN ref_bizunit_scoped r ON r.gkey=inv_unit.line_op 
					LEFT JOIN ref_agent_representation X ON r.gkey=x.bzu_gkey 
					LEFT JOIN ref_bizunit_scoped Y ON x.agent_gkey=y.gkey
					WHERE inv_unit.gkey='$vvdgkey' 

					";
					
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						$rowPos=oci_execute($strQuery2Res);
						$results=array();
						$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
						oci_free_statement($strQuery2Res);
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						oci_execute($strQuery2Res);
						$rowShortName=oci_fetch_object($strQuery2Res);



					if($minColLimitUp!=0)
					{
					?>
					<td <?php if($k >$maxColLimitUp){?>class="nogrid"<?php } elseif(in_array($gapStr, $upGapValArr)){?>class="nogrid"<?php } elseif($numrow>0) {?> class="gridcolor" <?php } else {?> class="grid" <?php }?> align="center">
					<?php
						if($numrow>0 and $k<=$maxColLimitUp)
						{
								$rfrAbvL = $rowPos->rfr_connect;
								$freight_kindAbvL = $rowPos->freight_kind;
								$txtAbvL = "";
								if($rfrAbvL == "RE" or $rfrAbvL == "RS" or $rfrAbvL == "RT" or $rfrAbvL == "HR")
									$txtAbvL = "R";
								elseif($freight_kindAbvL=="MTY")
									$txtAbvL = "E";
								elseif($freight_kindAbvL=="FCL" or $freight_kindAbvL=="LCL")
									$txtAbvL = "D";
									
								echo $rowPos->pod." ".$txtAbvL.$rowPos->size."'<br/>";
								echo $rowPos->id."<br/>";
								echo $rowPos->mlo." ".$rowPos->tons."Ts";
								${'tons'.$k} += $rowPos->tons;
						}
					?>
					</td>
					<?php 
					}
				}				
				$k = $k-2;
			}
			
			// for centre line
			$numrowPrevContCntr = 0;
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
					
				

					$strPrevContCntr = "SELECT ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					where (stowage_pos='$slotCntr1' or stowage_pos='$slotCntr2') and ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey";






					$resPrevContCntr = mysqli_query($con_sparcsn4,$strPrevContCntr);
					$rowPrevContCntr = mysqli_fetch_object($resPrevContCntr);
					$numrowPrevContCntr = mysqli_num_rows($resPrevContCntr);
					$i=0;
					$j=0;
					
					$mlo="";
					$vvdgkey="";
					while($rowPrevContCntr=mysqli_fetch_object($resPrevContCntr)){
					$i++;
				
					$vvdgkey=$rowPrevContCntr->gkey;

					$sql2="
					select inv_unit.gkey,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS size
					from inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					where inv_unit.gkey='$vvdgkey'
					";
					
					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
					$rowPrevContCntr=oci_execute($strQuery2Res);
					$results=array();
					$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
					oci_free_statement($strQuery2Res);
					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
					oci_execute($strQuery2Res);
					$rowShortName=oci_fetch_object($strQuery2Res);
					

					}
				}
				if($numrowPrevContCntr>0 and $rowbay->paired==0 and $rowPrevContCntr->size>20 and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevContCntr->size."'";?>
					</td>
					<?php
				}
				else
				{
				
					$strPosCentre = "
					SELECT  ctmsmis.mis_exp_unit.pod,CEIL((ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg/1000)) AS tons,cont_mlo AS mlo,ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					WHERE ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey 
					AND LEFT(stowage_pos,2) IN($bay) AND RIGHT(stowage_pos,4)='$posCentre' AND RIGHT(stowage_pos,2)>=80 ORDER BY stowage_pos";
			
					$resPosCentre = mysqli_query($con_sparcsn4,$strPosCentre);
					$rowPosCentre = mysqli_fetch_object($resPosCentre);
					$numrowCentre = mysqli_num_rows($resPosCentre);

					$mlo="";
					$vvdgkey="";
					
					$vvdgkey=$rowPosCentre->gkey;


					$mlo="";
					$vvdgkey="";
					while($rowPosCentre=mysqli_fetch_object($resPosCentre)){
					$i++;
				
					$vvdgkey=$rowPrevContCntr->gkey;

					$sql2="
					select inv_unit.gkey,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS size
					from inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					where inv_unit.gkey='$vvdgkey'
					";
					
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						$rowPrevContCntr=oci_execute($strQuery2Res);
						$results=array();
						$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
						oci_free_statement($strQuery2Res);
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						oci_execute($strQuery2Res);
						$rowShortName=oci_fetch_object($strQuery2Res);
					

					}


					?>
			
					<td <?php if($numrowCentre>0) {?> class="gridcolor" <?php } else {?> class="grid" <?php }?> align="center">
						<?php
							if($numrowCentre>0)
							{
									$rfrAbvC = $rowPosCentre->rfr_connect;
									$freight_kindAbvC = $rowPosCentre->freight_kind;
									$txtAbvC = "";
									if($rfrAbvC == "RE" or $rfrAbvC == "RS" or $rfrAbvC == "RT" or $rfrAbvC == "HR")
										$txtAbvC = "R";
									elseif($freight_kindAbvC=="MTY")
										$txtAbvC = "E";
									elseif($freight_kindAbvC=="FCL" or $freight_kindAbvC=="LCL")
										$txtAbvC = "D";
										
									echo $rowPosCentre->pod." ".$txtAbvC.$rowPosCentre->size."'<br/>";
									echo $rowPosCentre->id."<br/>";
									echo $rowPosCentre->mlo." ".$rowPosCentre->tons."Ts";
									$tons0 += $rowPosCentre->tons;
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
						
					
					$slotRight1 = $rbyRight12.$posRight;
					$slotRight2 = $rbyRight22.$posRight;
					
			

					$strPrevContRight = "SELECT ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					where (stowage_pos='$slotCntr1' or stowage_pos='$slotCntr2') and ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey";




					$resPrevContRight = mysqli_query($con_sparcsn4,$strPrevContRight);
					$rowPrevContRight = mysqli_fetch_object($resPrevContRight);
					$numrowPrevContRight = mysqli_num_rows($resPrevContRight);



					$mlo="";
					$vvdgkey="";
					while($rowPrevContRight=mysqli_fetch_object($resPrevContRight)){
					$i++;
				
					$vvdgkey=$rowPrevContRight->gkey;

					$sql2="
					select inv_unit.gkey,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS size
					from inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					where inv_unit.gkey='$vvdgkey'
					";
					
					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
					$rowPrevContRight=oci_execute($strQuery2Res);
					$results=array();
					$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
					oci_free_statement($strQuery2Res);
					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
				
					

					}




					//echo $pos."=".$numrowPrevCont."-".$rowPrevCont->size;
				}
				
				if($numrowPrevContRight>0 and $rowbay->paired==0 and $rowPrevContRight->size>20 and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevContRight->size."'";?>
					</td>
					<?php
				}
				else
				{




					$strPosCentre = "
					SELECT  ctmsmis.mis_exp_unit.pod,CEIL((ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg/1000)) AS tons,cont_mlo AS mlo,ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					WHERE ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey 
					AND LEFT(stowage_pos,2) IN($bay) AND RIGHT(stowage_pos,4)='$posRight' AND RIGHT(stowage_pos,2)>=80 ORDER BY stowage_pos";
			
		
					$resPosRight = mysqli_query($con_sparcsn4,$strPosRight);
					$rowPosRight = mysqli_fetch_object($resPosRight);
					$numrowRight = mysqli_num_rows($resPosRight);

					$mlo="";
					$vvdgkey="";
					
					$vvdgkey=$rowPosCentre->gkey;


					$mlo="";
					$vvdgkey="";
					while($rowPosRight=mysqli_fetch_object($resPosRight)){
					$i++;
				
					$vvdgkey=$resPosRight->gkey;

					$sql2="
					select inv_unit.gkey,inv_unit.freight_kind,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS size,ref_equip_type.iso_group as rfr_connect
					from inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					where inv_unit.gkey='$vvdgkey'
					";
					
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						$rowPosRight=oci_execute($strQuery2Res);
						$results=array();
						$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
						oci_free_statement($strQuery2Res);
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						oci_execute($strQuery2Res);
						$rowShortName=oci_fetch_object($strQuery2Res);
					

					}





			


					?>
					<td <?php if($l >$maxColLimitUp){?>class="nogrid"<?php } elseif(in_array($gapStrRisht, $upGapValArr)){?>class="nogrid"<?php } elseif($numrowRight>0) {?> class="gridcolor" <?php } else {?> class="grid" <?php }?> align="center">
					<?php
						if($numrowRight>0 and $l<=$maxColLimitUp)
						{
								$rfrAbvR = $rowPosRight->rfr_connect;
								$freight_kindAbvR = $rowPosRight->freight_kind;
								$txtAbvR = "";
								if($rfrAbvR == "RE" or $rfrAbvR == "RS" or $rfrAbvR == "RT" or $rfrAbvR == "HR")
									$txtAbvR = "R";
								elseif($freight_kindAbvR=="MTY")
									$txtAbvR = "E";
								elseif($freight_kindAbvR=="FCL" or $freight_kindAbvR=="LCL")
									$txtAbvR = "D";
									
								echo $rowPosRight->pod." ".$txtAbvR.$rowPosRight->size."'<br/>";
								echo $rowPosRight->id."<br/>";
								echo $rowPosRight->mlo." ".$rowPosRight->tons."Ts";
								${'tons'.$l} += $rowPosRight->tons;
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
					

				
				



					
					$strPrevContBelow = "SELECT ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					where (stowage_pos='$slotCntr1' or stowage_pos='$slotCntr2') and ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey";




					$resPrevContBelow = mysqli_query($con_sparcsn4,$strPrevContBelow);
					$rowPrevContBelow = mysqli_fetch_object($resPrevContBelow);
					$numrowPrevContBelow= mysqli_num_rows($resPrevContBelow);



					$mlo="";
					$vvdgkey="";
					while($rowPrevContBelow=mysqli_fetch_object($resPrevContBelow)){
					$i++;
				
					$vvdgkey=$rowPrevContBelow->gkey;

					$sql2="


					select inv_unit.gkey,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS size
					from inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					where inv_unit.gkey='$vvdgkey'


					";
					
					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
					$rowPrevContBelow=oci_execute($strQuery2Res);
					$results=array();
					$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
					oci_free_statement($strQuery2Res);
					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
				
					

					}

					//echo $pos."=".$numrowPrevCont."-".$rowPrevCont->size;
				}
				
				
				if($numrowPrevContBelow>0 and $rowbay->paired==0 and $rowPrevContBelow->size>20 and $kbelow <=$maxColLimit and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevContBelow->size."'";?>
					</td>
					<?php
				}
				else
				{
				
					


					$strPosbelow = "
					SELECT  ctmsmis.mis_exp_unit.pod,CEIL((ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg/1000)) AS tons,cont_mlo AS mlo,ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					WHERE ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey 
					AND LEFT(stowage_pos,2) IN($bay) AND RIGHT(stowage_pos,4)='$posbelow' AND RIGHT(stowage_pos,2)>=80 ORDER BY stowage_pos";
			
					$resPosbelow = mysqli_query($con_sparcsn4,$strPosbelow);
					$rowPosbelow = mysqli_fetch_object($resPosbelow);
					$numrowbelow = mysqli_num_rows($resPosbelow);

				
					$mlo="";
					$vvdgkey="";
					
					


					$mlo="";
					$vvdgkey="";
					while($rowPosbelow=mysqli_fetch_object($resPosbelow)){
					$i++;
				
					$vvdgkey=$rowPosbelow->gkey;

					$sql2="
					select inv_unit.gkey,inv_unit.freight_kind,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS size,ref_equip_type.iso_group as rfr_connect
					from inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					where inv_unit.gkey='$vvdgkey'
					";
					
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						$rowPosbelow=oci_execute($strQuery2Res);
						$results=array();
						$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
						oci_free_statement($strQuery2Res);
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						oci_execute($strQuery2Res);
						$rowShortName=oci_fetch_object($strQuery2Res);
					

					}






				
					?>
					<td <?php if($numrowbelow>0 and $kbelow <=$maxColLimit) {?> class="gridcolor" <?php } elseif(in_array($posbelow, $upGapValArrBelow)){?>class="nogrid"<?php } elseif($kbelow >$maxColLimit){?>class="nogrid"<?php } else {?>class="grid" <?php } ?> align="center">
						<?php 
							
							if($numrowbelow>0 and $kbelow <=$maxColLimit)
							{
								$rfr = $rowPosbelow->rfr_connect;
								$freight_kind = $rowPosbelow->freight_kind;
								$txt = "";
								if($rfr == "RE" or $rfr == "RS" or $rfr == "RT" or $rfr == "HR")
									$txt = "R";
								elseif($freight_kind=="MTY")
									$txt = "E";
								elseif($freight_kind=="FCL" or $freight_kind=="LCL")
									$txt = "D";
									
								echo $rowPosbelow->pod." ".$txt.$rowPosbelow->size."'<br/>";
								echo $rowPosbelow->id."<br/>";
								echo $rowPosbelow->mlo." ".$rowPosbelow->tons."Ts";
								${'tonsB'.$kbelow} += $rowPosbelow->tons;
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
			
				$numrowPrevContBelowCntr = 0;		// 2022-03-08 - intakhab
				
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
					



				
				
					
					$strPrevContBelowCntr = "SELECT ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					where (stowage_pos='$slotBelowCntr1' or stowage_pos='$slotBelowCntr2') and ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey";



					$resPrevContBelowCntr = mysqli_query($con_sparcsn4,$strPrevContBelowCntr);
					$rowPrevContBelowCntr = mysqli_fetch_object($resPrevContBelowCntr);
					$numrowPrevContBelowCntr= mysqli_num_rows($resPrevContBelowCntr);



					$mlo="";
					$vvdgkey="";
					while($rowPrevContBelowCntr=mysqli_fetch_object($resPrevContBelowCntr)){
					$i++;
				
					$vvdgkey=$rowPrevContBelowCntr->gkey;

					$sql2="
					select inv_unit.gkey,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS size
					from inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					where inv_unit.gkey='$vvdgkey'
					";
					
					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
					$rowPrevContBelowCntr=oci_execute($strQuery2Res);
					$results=array();
					$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
					oci_free_statement($strQuery2Res);
					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
				
					

					}



			
					//echo $pos."=".$numrowPrevCont."-".$rowPrevCont->size;
				}
				
				if($numrowPrevContBelowCntr>0 and $rowbay->paired==0 and $rowPrevContBelowCntr->size>20 and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevContBelowCntr->size."'";?>
					</td>
					<?php
				}
				else
				{
			

					$strPosCentreBelow = "
					SELECT  ctmsmis.mis_exp_unit.pod,CEIL((ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg/1000)) AS tons,cont_mlo AS mlo,ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					WHERE ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey 
					AND LEFT(stowage_pos,2) IN($bay) AND RIGHT(stowage_pos,4)='$posCentreBelow' AND RIGHT(stowage_pos,2)>=80 ORDER BY stowage_pos";
			
					


					$resPosCentreBelow = mysqli_query($con_sparcsn4,$strPosCentreBelow);
					$rowPosCentreBelow = mysqli_fetch_object($resPosCentreBelow);
					$numrowCentreBelow = mysqli_num_rows($resPosCentreBelow);

				
					$mlo="";
					$vvdgkey="";
					
				
					while($rowPosCentreBelow=mysqli_fetch_object($resPosCentreBelow)){
					$i++;
				
					$vvdgkey=$rowPosCentreBelow->gkey;

					$sql2="
					select inv_unit.gkey,inv_unit.freight_kind,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS size,ref_equip_type.iso_group as rfr_connect
					from inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					where inv_unit.gkey='$vvdgkey'
					";
					
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						$rowPosCentreBelow=oci_execute($strQuery2Res);
						$results=array();
						$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
						oci_free_statement($strQuery2Res);
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						oci_execute($strQuery2Res);
						$rowShortName=oci_fetch_object($strQuery2Res);
					

					}




					?>
					<!-- Center column -->
					<td <?php if($numrowCentreBelow>0) {?> class="gridcolor" <?php } else {?> class="grid" <?php }?> align="center">
							<?php
								if($numrowCentreBelow>0)
								{
									$rfrCtr = $rowPosCentreBelow->rfr_connect;
									$freight_kindCtr = $rowPosCentreBelow->freight_kind;
									$txtCtr = "";
									if($rfrCtr == "RE" or $rfrCtr == "RS" or $rfrCtr == "RT" or $rfrCtr == "HR")
										$txtCtr = "R";
									elseif($freight_kindCtr=="MTY")
										$txtCtr = "E";
									elseif($freight_kindCtr=="FCL" or $freight_kindCtr=="LCL")
										$txtCtr = "D";
										
									echo $rowPosCentreBelow->pod." ".$txtCtr.$rowPosCentreBelow->size."'<br/>";
									echo $rowPosCentreBelow->id."<br/>";
									echo $rowPosCentreBelow->mlo." ".$rowPosCentreBelow->tons."Ts";
									$tonsB0 += $rowPosCentreBelow->tons;
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
			
					
					
					$strPrevContBelowRight = "SELECT ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					where (stowage_pos='$slotBelowRight1' or stowage_pos='$slotBelowRight2') and ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey";


					$resPrevContBelowRight = mysqli_query($con_sparcsn4,$strPrevContBelowRight);
					$rowPrevContBelowRight = mysqli_fetch_object($resPrevContBelowRight);
					$numrowPrevContBelowRight= mysqli_num_rows($resPrevContBelowRight);



					$mlo="";
					$vvdgkey="";
					while($rowPrevContBelowRight=mysqli_fetch_object($resPrevContBelowRight)){
					$i++;
				
					$vvdgkey=$rowPrevContBelowRight->gkey;

					$sql2="
					select inv_unit.gkey,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS size
					from inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					where inv_unit.gkey='$vvdgkey'
					";
					
					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
					$rowPrevContBelowCntr=oci_execute($strQuery2Res);
					$results=array();
					$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
					oci_free_statement($strQuery2Res);
					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
				
					

					}

			
					//echo $pos."=".$numrowPrevCont."-".$rowPrevCont->size;
				}
				
				if($numrowPrevContBelowRight>0 and $rowbay->paired==0 and $rowPrevContBelowRight->size>20 and $lBelow <=$maxColLimit-1 and $mystat==0 and $bayState>0)
				{
					?>
					<td class="gridcolor" align="center">
						<?php echo $rowPrevContBelowRight->size."'";?>
					</td>
					<?php
				}
				else
				{				
				
					
					$strPosRightBelow = "
					SELECT  ctmsmis.mis_exp_unit.pod,CEIL((ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg/1000)) AS tons,cont_mlo AS mlo,ctmsmis.mis_exp_unit.gkey
					FROM ctmsmis.mis_exp_unit 
					WHERE ctmsmis.mis_exp_unit.vvd_gkey=$vvdGkey 
					AND LEFT(stowage_pos,2) IN($bay) AND RIGHT(stowage_pos,4)=right('$posRightBelow',4) AND RIGHT(stowage_pos,2)>=80 ORDER BY stowage_pos";
			
				



					$resPosRightBelow = mysqli_query($con_sparcsn4,$strPosRightBelow);
					$rowPosRightBelow = mysqli_fetch_object($resPosRightBelow);
					$numrowRightBelow = mysqli_num_rows($resPosRightBelow);

				
					$mlo="";
					$vvdgkey="";
					
					


					$mlo="";
					$vvdgkey="";
					while($rowPosRightBelow=mysqli_fetch_object($resPosRightBelow)){
					$i++;
				
					$vvdgkey=$rowPosRightBelow->gkey;

					$sql2="
					select inv_unit.gkey,inv_unit.freight_kind,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS size,ref_equip_type.iso_group as rfr_connect
					from inv_unit
					INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					where inv_unit.gkey='$vvdgkey'
					";
					
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						$rowPosRightBelow=oci_execute($strQuery2Res);
						$results=array();
						$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
						oci_free_statement($strQuery2Res);
						$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql2);
						oci_execute($strQuery2Res);
						$rowShortName=oci_fetch_object($strQuery2Res);
					

					}



					
					
					
					
					
				
					?>		
					<td <?php if($numrowRightBelow>0 and $lBelow <=$maxColLimit-1) {?> class="gridcolor" <?php } elseif(in_array($posRightBelow, $upGapValArrBelow)){?>class="nogrid"<?php } elseif($lBelow >$maxColLimit){?>class="nogrid"<?php } else {?> class="grid" <?php }?> align="center">
						<?php
							if($numrowRightBelow>0 and $lBelow <=$maxColLimit-1)
							{
								$rfrBlw = $rowPosRightBelow->rfr_connect;
								$freight_kindBlw = $rowPosRightBelow->freight_kind;
								$txtBlw = "";
								if($rfrBlw == "RE" or $rfrBlw == "RS" or $rfrBlw == "RT" or $rfrBlw == "HR")
									$txtBlw = "R";
								elseif($freight_kindBlw=="MTY")
									$txtBlw = "E";
								elseif($freight_kindBlw=="FCL" or $freight_kindBlw=="LCL")
									$txtBlw = "D";
									
								echo $rowPosRightBelow->pod." ".$txtBlw.$rowPosRightBelow->size."'<br/>";
								echo $rowPosRightBelow->id."<br/>";
								echo $rowPosRightBelow->mlo." ".$rowPosRightBelow->tons."Ts";
								${'tonsB'.$lBelow} += $rowPosRightBelow->tons;
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
