<?php
	$xml = new DOMDocument("1.0","UTF-8");
	$xml->formatOutput=true;
	$argosnx=$xml->createElement("argo:snx");
	$argosnx->setAttribute( "xmlns:argo", "http://www.navis.com/argo" );
	$argosnx->setAttribute( "xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance" );
	$argosnx->setAttribute( "xsi:schemaLocation", "http://www.navis.com/argo snx.xsd" );
	$xml->appendChild($argosnx);
	
	for($i=0;$i<count($result_imp);$i++){
		
		$truckid = "";
		$Slotpos = "";			
			
		$truckNo = "";
		$unit=$xml->createElement("unit");
		$unit->setAttribute( "update-mode", "REPLACE" );
		$unit->setAttribute( "id", $result_imp[$i]['cont_id'] );
		$unit->setAttribute( "category", "IMPORT" );
		$unit->setAttribute( "restow", "NONE" );
		$unit->setAttribute( "transit-state", "YARD" );
		$unit->setAttribute( "freight-kind", $result_imp[$i]['cont_status'] );
		$unit->setAttribute( "line", $result_imp[$i]['cont_mlo'] );
		$unit->setAttribute( "unique-key", $result_imp[$i]['cont_id'] );
		$argosnx->appendChild($unit);			
				
		$position=$xml->createElement("position");
		$position->setAttribute( "loc-type", "YARD" );
		$position->setAttribute( "location", "CGP" );		
		$position->setAttribute( "slot", $result_imp[$i]['current_position'] );		
		$unit->appendChild($position);
		
		$routing=$xml->createElement("routing");
		$routing->setAttribute( "pod-1", "CGP" );
		$routing->setAttribute( "pod-1-name", "Bangladesh" );
		$routing->setAttribute( "destination", $result_imp[$i]['dest'] );
		$unit->appendChild($routing);
		
		$carrier=$xml->createElement("carrier");
		$carrier->setAttribute( "direction", "IB" );
		$carrier->setAttribute( "qualifier", "DECLARED" );
		$carrier->setAttribute( "mode", "VESSEL" );
		$carrier->setAttribute( "id", $result_imp[$i]['arcar_id'] );
		$routing->appendChild($carrier);
		
		$carrier=$xml->createElement("carrier");
		$carrier->setAttribute( "direction", "IB" );
		$carrier->setAttribute( "qualifier", "ACTUAL" );
		$carrier->setAttribute( "mode", "VESSEL" );
		$carrier->setAttribute( "id", $result_imp[$i]['arcar_id'] );
		$routing->appendChild($carrier);
		
		$carrier=$xml->createElement("carrier");
		$carrier->setAttribute( "direction", "OB" );
		$carrier->setAttribute( "qualifier", "DECLARED" );
		$carrier->setAttribute( "mode", "TRUCK" );
		$routing->appendChild($carrier);
		
		$carrier=$xml->createElement("carrier");
		$carrier->setAttribute( "direction", "OB" );
		$carrier->setAttribute( "qualifier", "ACTUAL" );
		$carrier->setAttribute( "mode", "TRUCK" );
		$routing->appendChild($carrier);
		
		$seals=$xml->createElement("seals");
		$seals->setAttribute( "seal-1", $result_imp[$i]['seal_no'] );
		$unit->appendChild($seals);
		
		$ufvflex=$xml->createElement("ufv-flex");
		$ufvflex->setAttribute( "ufv-flex-6", "W" );
		$ufvflex->setAttribute( "ufv-flex-9", "LON" );
		$ufvflex->setAttribute( "ufv-flex-10", $result_imp[$i]['rotation'] );
		$unit->appendChild($ufvflex);
		
		$timestamps=$xml->createElement("timestamps");
		$timestamps->setAttribute( "time-in", 
										substr($result_imp[$i]['last_update'],0,10)."T".substr($result_imp[$i]['last_update'],11,19)
									);
		$timestamps->setAttribute( "time-last-move", 
										substr($result_imp[$i]['last_update'],0,10)."T".substr($result_imp[$i]['last_update'],11,19)
									);
		$unit->appendChild($timestamps);
		
		$movehistory=$xml->createElement("move-history");
		$unit->appendChild($movehistory);
		
		$move=$xml->createElement("move");
		$move->setAttribute( "move-kind", "DSCH" );
		$move->setAttribute( "timestamp", 
							substr($result_imp[$i]['last_update'],0,10)."T".substr($result_imp[$i]['last_update'],11,19) 
							);
		$movehistory->appendChild($move);
		
		$fromposition=$xml->createElement("from-position");
		$fromposition->setAttribute( "loc-type", "VESSEL" );
		$fromposition->setAttribute( "location",$result_imp[$i]['arcar_id']);
		$fromposition->setAttribute( "slot",$result_imp[$i]['last_pos_slot']);
		$move->appendChild($fromposition);
		
		$fromposition=$xml->createElement("from-position");
		$fromposition->setAttribute( "loc-type", "YARD" );
		$fromposition->setAttribute( "location","CGP");
		$fromposition->setAttribute( "slot",$result_imp[$i]['current_position']);
		$move->appendChild($fromposition);
		
		$movedetails=$xml->createElement("move-details");
		$movedetails->setAttribute( "meters-to-start", "0" );
		$movedetails->setAttribute( "meters-of-carry","0");					
		$movedetails->setAttribute( "time-discharge",
									substr($result_imp[$i]['last_update'],0,10)."T".substr($result_imp[$i]['last_update'],11,19)
								);
		$movedetails->setAttribute( "rehandle-count","0");
		$movedetails->setAttribute( "was-twin-carry","N");
		$movedetails->setAttribute( "was-twin-fetch","N");
		$movedetails->setAttribute( "was-twin-put","N");
		$move->appendChild($movedetails);		
		
	}
	
	$filename="IMPORT_DISCHARGE_".str_replace("/","_",$rot_no);
			
	ob_end_clean();
	header_remove();

	header("Content-type: text/xml");
	header("Content-Disposition: attachment; filename=".basename($filename));
	echo $xml->saveXML();
	exit();	
?>