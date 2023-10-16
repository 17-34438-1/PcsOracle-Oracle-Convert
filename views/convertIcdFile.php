<?php
	putenv('TZ=Asia/Dhaka');
	
	include("dbConection.php");
	include("dbOracleConnection.php");
	$visit=trim($_POST['visit']);
	
	$strMain = "select * from ctmsmis.mis_icd_unit where visit_id='$visit'";
	$resMain = mysqli_query($con_sparcsn4,$strMain);
	
	$xml = new DOMDocument("1.0","UTF-8");
	$xml->formatOutput=true;
	$argosnx=$xml->createElement("argo:snx");
	$argosnx->setAttribute( "xmlns:argo", "http://www.navis.com/argo" );
	$argosnx->setAttribute( "xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance" );
	$argosnx->setAttribute( "xsi:schemaLocation", "http://www.navis.com/argo snx.xsd" );
	$xml->appendChild($argosnx);
		
	$st = 0;
	$tbl = "<table border='1'><tr><td colspan='5'><b>Listed Containers are need to be depart or already exported</b></td></tr><tr><th>Container</th><th>Category</th><th>Status</th><th>MLO</th><th>Iso Type</th></tr>";
	while($rowMain= mysqli_fetch_object($resMain))
	{	
		$contId=$rowMain->cont_id;
		
		$strIso = "SELECT ref_equip_type.id 
        FROM  ref_equipment 
		INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
        WHERE ref_equipment.id_full='$contId' FETCH FIRST 1 ROWS ONLY";
		$rIso = oci_parse($con_sparcsn4_oracle, $strIso);
		oci_execute($rIso);
		$iso="";
		while(($rowIso=oci_fetch_object($rIso)) != false)
		{
			$iso=$rowIso->ID;
			
		}
		
		$strMlo = "select ref_bizunit_scoped.id from inv_unit
		inner join ref_bizunit_scoped on ref_bizunit_scoped.gkey=inv_unit.line_op
		where inv_unit.id='$contId' and category='IMPRT' order by inv_unit.gkey desc  FETCH FIRST 1 ROWS ONLY";		
		$rMlo = oci_parse($con_sparcsn4_oracle, $strMlo);
		oci_execute($rMlo);
		
		$mlo="";
		while(($rowMlo=oci_fetch_object($rMlo)) != false)
		{
			$mlo=$rowMlo->ID;
			
		}
		
		$strTrans = "select inv_unit_fcy_visit.transit_state,inv_unit.category from inv_unit 
		inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit.id='$contId' order by inv_unit_fcy_visit.gkey";
		$resTrans = oci_parse($con_sparcsn4_oracle, $strTrans);
		oci_execute($resTrans);

		$Trans="";
		$cat="";
		while(($rowTrans=oci_fetch_object($resTrans)) != false)
		{
			$Trans=$rowTrans->TRANSIT_STATE;
			$cat=$rowTrans->CATEGORY;
		}
		
		$s = 0;
		if($cat=="IMPRT" and $Trans=="S40_YARD")
			$s = 1;
		else if($cat=="EXPRT" and ($Trans=="S60_LOADED" or $Trans=="S40_YARD"))
			$s = 1;
		else if($cat=="STRGE" and $Trans=="S40_YARD")
			$s = 1;
		$strCont = "";
		
		if($s==0)
		{
			$unit=$xml->createElement("unit");
			$unit->setAttribute( "id", $rowMain->cont_id );
			$unit->setAttribute( "category", $rowMain->category );
			$unit->setAttribute( "transit-state", "INBOUND" );
			$unit->setAttribute( "visit-state", "ACTIVE" );
			$unit->setAttribute( "freight-kind", $rowMain->fried_kind );
			$unit->setAttribute( "weight-kg", $rowMain->gross_weight );
			$unit->setAttribute( "line", $rowMain->mlo );
			$unit->setAttribute( "xml-status", "0" );
			$argosnx->appendChild($unit);
			
			$equipment=$xml->createElement("equipment"," ");
			$equipment->setAttribute( "eqid", $rowMain->cont_id );
			$equipment->setAttribute( "type", $iso );
			$equipment->setAttribute( "class", "CTR" );
			$equipment->setAttribute( "life-cycle-state", "ACT" );
			$equipment->setAttribute( "role", "PRIMARY" );
			$unit->appendChild($equipment);
			
			$position=$xml->createElement("position"," ");
			$position->setAttribute( "loc-type", "RAILCAR" );
			$position->setAttribute( "location", $rowMain->transport_id );
			$position->setAttribute( "slot", $rowMain->slot );
			$position->setAttribute( "carrier-id", $rowMain->visit_id );
			$unit->appendChild($position);
			
			$routing=$xml->createElement("routing");
			$routing->setAttribute( "pol", "CGP" );
			$routing->setAttribute( "pod-1", "CGP" );
			
			$carrier=$xml->createElement("carrier"," ");
			$carrier->setAttribute( "direction", "IB" );
			$carrier->setAttribute( "qualifier", "DECLARED" );
			$carrier->setAttribute( "mode", $rowMain->trans_mode );
			$carrier->setAttribute( "id", $rowMain->visit_id );
			$routing->appendChild($carrier);
			
			$carrier=$xml->createElement("carrier"," ");
			$carrier->setAttribute( "direction", "IB" );
			$carrier->setAttribute( "qualifier", "ACTUAL" );
			$carrier->setAttribute( "mode", $rowMain->trans_mode );
			$carrier->setAttribute( "id", $rowMain->visit_id );
			$routing->appendChild($carrier);
			
			$carrier=$xml->createElement("carrier"," ");
			$carrier->setAttribute( "direction", "OB" );
			$carrier->setAttribute( "qualifier", "DECLARED" );
			$carrier->setAttribute( "mode", "VESSEL" );
			$carrier->setAttribute( "id", "GEN_CARRIER" );
			$routing->appendChild($carrier);
			
			$carrier=$xml->createElement("carrier"," ");
			$carrier->setAttribute( "direction", "OB" );
			$carrier->setAttribute( "qualifier", "ACTUAL" );
			$carrier->setAttribute( "mode", "VESSEL" );
			$carrier->setAttribute( "id", "GEN_CARRIER" );
			$routing->appendChild($carrier);	
			
			$unit->appendChild($routing);
			
			$contents=$xml->createElement("contents");
			$contents->setAttribute( "weight-kg", $rowMain->gross_weight );
			$unit->appendChild($contents);
			
			$unitetc=$xml->createElement("unit-etc");
			$unitetc->setAttribute( "category", $rowMain->category );
			$unitetc->setAttribute( "line", $rowMain->mlo );
			$unit->appendChild($unitetc);
			
			if($rowMain->fried_kind=="FCL" or $rowMain->fried_kind=="LCL")
			{
				$seals=$xml->createElement("seals");
				$seals->setAttribute( "seal-1", $rowMain->seal );
				$unit->appendChild($seals);
			}
		}
		else if($s==1)
		{
			$tbl = $tbl."<tr><td>$rowMain->cont_id</td><td>$rowMain->category</td><td>$rowMain->fried_kind</td><td>$rowMain->mlo</td><td>$rowMain->iso_code</td></tr>";
			$strCont = $strCont.$rowMain->cont_id.", ";
			$st++;			
		}
	}
	
	$tbl = $tbl."<tr><td colspan='5'>$strCont</td></tr></table>";
	
	if($st>0)
	{
		$data['msg'] = $tbl;
		$data['title']="CONVERT ICD FILE...";		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('convertIcdFileForm',$data);
		$this->load->view('jsAssets');
		return;
	}
	
	$file_old = $visit;
	$myFile_old="ICD-".$file_old.'.xml';
	
	oci_close($con_sparcsn4_oracle);
	mysqli_close($con_sparcsn4);
	
	function concatstaus($str)
    {
		$vl = "";	 
		if($str=="I")
			$vl = "3";
		elseif($str=="E")
			$vl = "2";
		elseif($str=="T")
			$vl = "6";
		elseif($str=="FCL")
			$vl = "5";
		elseif($str=="MTY")
			$vl = "4";
		return $vl;
	}

	function remove_numbers($string) 
	{
		$spchar = array("\n","&",'"',"'","/",">","<","^","  ","~");
		$string = str_replace($spchar, '', $string);				
		$string=substr($string, 0, 80);
		return $string;				
	}
	
	ob_end_clean();
	header_remove();

	header("Content-type: text/xml");
	header("Content-Disposition: attachment; filename=".basename($myFile_old));
	echo $xml->saveXML();
	exit();
?>