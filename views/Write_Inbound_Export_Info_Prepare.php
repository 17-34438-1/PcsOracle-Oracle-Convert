<?php
	$xml = new DOMDocument("1.0","UTF-8");
	$xml->formatOutput=true;
	$argosnx=$xml->createElement("argo:snx");
	$argosnx->setAttribute( "xmlns:argo", "http://www.navis.com/argo" );
	$argosnx->setAttribute( "xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance" );
	$argosnx->setAttribute( "xsi:schemaLocation", "http://www.navis.com/argo snx.xsd" );
	$xml->appendChild($argosnx);
	
	for($i=0;$i<count($result);$i++){
		if($result[$i]['category'] == "EXPRT")
		{
			$truckid = "";
			$Slotpos = "";
			if($result[$i]['transit_state'] == "S20_INBOUND" or $result[$i]['transit_state'] == "S40_YARD")
			{				
				$truckNo = "";
				$unit=$xml->createElement("unit");
				$unit->setAttribute( "update-mode", "REPLACE" );
				$unit->setAttribute( "id", $result[$i]['cont_id'] );
				$unit->setAttribute( "category", "EXPORT" );
				$unit->setAttribute( "restow", "NONE" );
				$unit->setAttribute( "transit-state", "YARD" );
				$unit->setAttribute( "freight-kind", $result[$i]['cont_status'] );
				$unit->setAttribute( "line", $result[$i]['mlo'] );
				$unit->setAttribute( "unique-key", $result[$i]['cont_id'] );
				$argosnx->appendChild($unit);				
				
				$Slotpos = trim($result[$i]['pos_slot']);
				if($Slotpos == ""){
					$position=$xml->createElement("position");
					$position->setAttribute( "loc-type", "YARD" );
					$position->setAttribute( "location", "CGP" );
				} else {
					$position=$xml->createElement("position");
					$position->setAttribute( "loc-type", "YARD" );
					$position->setAttribute( "slot", $Slotpos );
				}
				$unit->appendChild($position);
				
				$routing=$xml->createElement("routing");
				$routing->setAttribute( "pol", "CGP" );
				$routing->setAttribute( "pol-name", "Bangladesh" );
				$routing->setAttribute( "pod-1", $result[$i]['pod'] );
				$routing->setAttribute( "pod-1-name", $result[$i]['cntry_name'] );
				$unit->appendChild($routing);
				
				$carrier=$xml->createElement("carrier");
				$carrier->setAttribute( "direction", "IB" );
				$carrier->setAttribute( "qualifier", "DECLARED" );
				$carrier->setAttribute( "mode", $result[$i]['carrier_mode'] );
				$routing->appendChild($carrier);
				
				$truckid = trim($result[$i]['truck']);
				if($truckid==""){
					$carrier=$xml->createElement("carrier");
					$carrier->setAttribute( "direction", "IB" );
					$carrier->setAttribute( "qualifier", "ACTUAL" );
					$carrier->setAttribute( "mode", $result[$i]['carrier_mode'] );
					$routing->appendChild($carrier);
				} else {
					$carrier=$xml->createElement("carrier");
					$carrier->setAttribute( "direction", "IB" );
					$carrier->setAttribute( "qualifier", "ACTUAL" );
					$carrier->setAttribute( "mode", $result[$i]['carrier_mode'] );
					$carrier->setAttribute( "id", $truckid );
					$routing->appendChild($carrier);
				}
				
				$carrier=$xml->createElement("carrier");
				$carrier->setAttribute( "direction", "OB" );
				$carrier->setAttribute( "qualifier", "DECLARED" );
				$carrier->setAttribute( "mode", "VESSEL" );
				$carrier->setAttribute( "id", $visit_id );
				$routing->appendChild($carrier);
				
				$carrier=$xml->createElement("carrier");
				$carrier->setAttribute( "direction", "OB" );
				$carrier->setAttribute( "qualifier", "ACTUAL" );
				$carrier->setAttribute( "mode", "VESSEL" );
				$carrier->setAttribute( "id", $visit_id );
				$routing->appendChild($carrier);
				
				$contents=$xml->createElement("contents");
				$contents->setAttribute( "weight-kg", $result[$i]['goods_and_ctr_wt_kg'] );
				$unit->appendChild($contents);
				
				$unitetc=$xml->createElement("unit-etc");
				$unitetc->setAttribute( "category", "EXPORT" );
				$unitetc->setAttribute( "line", $result[$i]['mlo'] );
				$unit->appendChild($unitetc);
				
				$unitflex=$xml->createElement("unit-flex");
				$unitflex->setAttribute( "unit-flex-7", "YES" );
				$unit->appendChild($unitflex);
				
				$timestamps=$xml->createElement("timestamps");
				$timestamps->setAttribute( "time-last-move", $date_time);
				$unit->appendChild($timestamps);
				
				$truckNo = trim($result[$i]['truck']);
				if($truckNo == "")
				{
					$movehistory=$xml->createElement("move-history");
					$unit->appendChild($movehistory);
					
					$move=$xml->createElement("move");
					$move->setAttribute( "move-kind", "RECV" );
					$move->setAttribute( "timestamp", $date_time);
					$movehistory->appendChild($move);
					
					$fromposition=$xml->createElement("from-position");
					$fromposition->setAttribute( "loc-type", "TRUCK" );
					$fromposition->setAttribute( "location","GEN_TRUCK");
					$move->appendChild($fromposition);
					
					$Slotpos = trim($result[$i]['pos_slot']);
					if($Slotpos == ""){
						$toposition=$xml->createElement("to-position");
						$toposition->setAttribute( "loc-type", "YARD" );
						$toposition->setAttribute( "location","CGP");
						$move->appendChild($toposition);
					} else {
						$toposition=$xml->createElement("to-position");
						$toposition->setAttribute( "loc-type", "YARD" );
						$toposition->setAttribute( "location","CGP");
						$toposition->setAttribute( "slot",$result[$i]['pos_slot']);
						$move->appendChild($toposition);
					}
					
					$movedetails=$xml->createElement("move-details");
					$movedetails->setAttribute( "meters-to-start", "0" );
					$movedetails->setAttribute( "meters-of-carry","0");					
					$movedetails->setAttribute( "time-put", $date_time);
					$movedetails->setAttribute( "was-twin-carry","N");
					$movedetails->setAttribute( "was-twin-fetch","N");
					$movedetails->setAttribute( "was-twin-put","N");
					$move->appendChild($movedetails);
					
				}
			}
		} 
		else if($result[$i]['category'] == "STRGE")
		{
			$Slotpos = "";
			if($result[$i]['transit_state'] == "S20_INBOUND" or $result[$i]['transit_state'] == "S40_YARD")
			{				
				$truckNo = "";
				$unit=$xml->createElement("unit");
				$unit->setAttribute( "update-mode", "REPLACE" );
				$unit->setAttribute( "id", $result[$i]['cont_id'] );
				$unit->setAttribute( "category", "EXPORT" );
				$unit->setAttribute( "restow", "NONE" );
				$unit->setAttribute( "transit-state", "YARD" );
				$unit->setAttribute( "freight-kind", $result[$i]['cont_status'] );
				$unit->setAttribute( "line", $result[$i]['mlo'] );
				$unit->setAttribute( "unique-key", $result[$i]['cont_id'] );
				$argosnx->appendChild($unit);				
				
				$Slotpos = trim($result[$i]['fcy_last_pos_slot']);
				if($Slotpos == ""){
					$position=$xml->createElement("position");
					$position->setAttribute( "loc-type", "YARD" );
					$position->setAttribute( "location", "CGP" );
				} else {
					$position=$xml->createElement("position");
					$position->setAttribute( "loc-type", "YARD" );
					$position->setAttribute( "slot", $Slotpos );
				}
				$unit->appendChild($position);
				
				$routing=$xml->createElement("routing");
				$routing->setAttribute( "pol", "CGP" );
				$routing->setAttribute( "pol-name", "Bangladesh" );
				$routing->setAttribute( "pod-1", $result[$i]['pod'] );
				$routing->setAttribute( "pod-1-name", $result[$i]['cntry_name'] );
				$unit->appendChild($routing);
				
				$carrier=$xml->createElement("carrier");
				$carrier->setAttribute( "direction", "IB" );
				$carrier->setAttribute( "qualifier", "DECLARED" );
				$carrier->setAttribute( "mode", $result[$i]['carrier_mode'] );
				$carrier->setAttribute( "id", $result[$i]['truck'] );
				$routing->appendChild($carrier);				
				
				$carrier=$xml->createElement("carrier");
				$carrier->setAttribute( "direction", "IB" );
				$carrier->setAttribute( "qualifier", "ACTUAL" );
				$carrier->setAttribute( "mode", $result[$i]['carrier_mode'] );
				$carrier->setAttribute( "id", $result[$i]['truck'] );
				$routing->appendChild($carrier);
				
				$carrier=$xml->createElement("carrier");
				$carrier->setAttribute( "direction", "OB" );
				$carrier->setAttribute( "qualifier", "DECLARED" );
				$carrier->setAttribute( "mode", "VESSEL" );
				$carrier->setAttribute( "id", $result[$i]['arcar_id'] );
				$routing->appendChild($carrier);
				
				$carrier=$xml->createElement("carrier");
				$carrier->setAttribute( "direction", "OB" );
				$carrier->setAttribute( "qualifier", "ACTUAL" );
				$carrier->setAttribute( "mode", "VESSEL" );
				$carrier->setAttribute( "id", $result[$i]['arcar_id'] );
				$routing->appendChild($carrier);
			}
		}
	}
	
	$filename="LOADING_".str_replace("/","_",$rot_no)."_PREPARE";
			
	ob_end_clean();
	header_remove();

	header("Content-type: text/xml");
	header("Content-Disposition: attachment; filename=".basename($filename));
	echo $xml->saveXML();
	exit();	
?>





