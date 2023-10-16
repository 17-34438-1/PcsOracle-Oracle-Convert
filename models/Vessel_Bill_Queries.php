<?php
class Vessel_Bill_Queries extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session'); 
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('file');												
	}
	
	function getJettyCountQuery($rotation)
	{
		$jettyCountQuery = "SELECT COUNT(*) AS cnt
		FROM vsl_vessel_visit_details
		INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg='$rotation'";				
		
		return $jettyCountQuery;
	}
	
	function getVisitCountQuery($rotation)
	{
		$visitCountQuery = "SELECT COUNT(*) AS cnt
		FROM vsl_vessel_visit_details
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg='$rotation' AND PHASE !='80CANCELED'";		
		
		return $visitCountQuery;
	}
	
	function getPilotChargesQueryInboundOnly($rotation)
	{				
		$pilotChargesQuery = "SELECT outer_vsl_visit_info.id,outer_vsl_visit_info.vsl_name,outer_vsl_visit_info.imp_rot AS rotation,outer_vsl_visit_info.date_of_arrival,outer_vsl_visit_info.time_of_arrival,outer_vsl_visit_info.date_of_departure,outer_vsl_visit_info.time_of_departure,outer_vsl_visit_info.remarks,
		outer_agent_info.agent_code,outer_agent_info.agent_name,outer_agent_info.alias_id,outer_agent_info.contact_address AS agent_address,
		outer_vsl_info.grt,outer_vsl_info.nrt,outer_vsl_info.flag,'0' AS deck_cargo,'106' AS bill_type
		FROM outer_vsl_visit_info
		INNER JOIN outer_vsl_info ON outer_vsl_info.vsl_name = outer_vsl_visit_info.vsl_name
		INNER JOIN outer_agent_info ON outer_agent_info.id = outer_vsl_info.agent_id
		WHERE imp_rot='$rotation'";

		return $pilotChargesQuery;
	}
	
	function getTugHiringVesselDtls($rotation)
	{		
		$pilotChargesQuery = "SELECT outer_vsl_visit_info.id,outer_vsl_visit_info.vsl_name,outer_vsl_visit_info.imp_rot AS rotation,
		outer_vsl_visit_info.date_of_arrival,outer_vsl_visit_info.time_of_arrival,outer_vsl_visit_info.date_of_departure,
		outer_vsl_visit_info.time_of_departure,outer_vsl_visit_info.remarks,
		outer_agent_info.agent_code,outer_agent_info.agent_name,outer_agent_info.alias_id,outer_agent_info.contact_address AS agent_address,
		outer_vsl_info.grt,outer_vsl_info.nrt,outer_vsl_info.flag,'0' AS deck_cargo,'500' AS bill_type
		FROM outer_vsl_visit_info
		INNER JOIN outer_vsl_info ON outer_vsl_info.vsl_name = outer_vsl_visit_info.vsl_name
		INNER JOIN outer_agent_info ON outer_agent_info.id = outer_vsl_info.agent_id
		WHERE imp_rot='$rotation' ORDER BY outer_vsl_visit_info.id DESC LIMIT 1";

		return $pilotChargesQuery;
	}
	
	function getPilotSubDetailsQueryTugHiring($grt,$deckCargo)
	{


		$ctmsQuery="SELECT id FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=106";
		$billTypeIdRes=$this->bm->dataSelectDb2($ctmsQuery);

		$billTypeIdList="";
		for($i=0;$i<count($billTypeIdRes);$i++){
			$id="";
			$id=$billTypeIdRes[$i]['id'];

			if($i==(count($billTypeIdRes)-1)){
				$billTypeIdList=$billTypeIdList."'".$id."'";
			}
			else{
				$billTypeIdList=$billTypeIdList."'".$id."',";

			}

		}

		$pilotChargeSubDetailQuery = "SELECT bil_tariffs.description,bil_tariffs.id
		,bil_tariffs.gl_code
		,bil_tariff_rates.rate_type,
		bil_tariff_rates.amount AS rate,

		(CASE WHEN bil_tariffs.description='PD SEA VESSEL' THEN ($grt+$deckCargo) ELSE 1 END) AS unit,
		1 AS move,
			
		(CASE WHEN bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='PD SEA VESSEL' THEN 'GRT' ELSE 'NOS' END) AS bas

		FROM bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='REGULAR' and bil_tariffs.id IN ($billTypeIdList)";
		$pilotChargeSubDetailQuery= $this->bm->dataSelectDb3($pilotChargeSubDetailQuery);


		return $pilotChargeSubDetailQuery;
	}
	
	function getPilotSubDetailsQueryInboundOnly($grt,$deckCargo)
	{


		$ctmsQuery="SELECT id as rtnValue FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=106";
		$billTypeIdRes=$this->bm->dataSelectDb2($ctmsQuery);

		$billTypeIdList="";
		for($i=0;$i<count($billTypeIdRes);$i++){
			$id="";
			$id=$billTypeIdRes[$i]['id'];

			if($i==(count($billTypeIdRes)-1)){
				$billTypeIdList=$billTypeIdList."'".$id."'";
			}
			else{
				$billTypeIdList=$billTypeIdList."'".$id."',";

			}

		}

		$pilotChargeSubDetailQuery = "SELECT bil_tariffs.description,bil_tariffs.id
		,bil_tariffs.gl_code
		,bil_tariff_rates.rate_type,
		bil_tariff_rates.amount AS rate,

		(CASE WHEN bil_tariffs.description='PD SEA VESSEL' THEN ($grt+$deckCargo) ELSE 1 END) AS unit,
		1 AS move,
			
		(CASE WHEN bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='PD SEA VESSEL' THEN 'GRT' ELSE 'NOS' END) AS bas

		FROM bil_tariffs

		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='REGULAR' and bil_tariffs.id IN ($billTypeIdList)";
		$pilotChargeSubDetailQuery= $this->bm->dataSelectDb3($pilotChargeSubDetailQuery);


		return $pilotChargeSubDetailQuery;
	}
	
	function getSubDetailsQueryTugHiringInsidePort($grt,$deckCargo)
	{
		$ctmsQuery="SELECT id as rtnValue FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=106";
		$billTypeIdRes=$this->bm->dataSelectDb2($ctmsQuery);
		

		$billTypeIdList="";
		for($i=0;$i<count($billTypeIdRes);$i++){
			$id="";
			$id=$billTypeIdRes[$i]['id'];

			if($i==(count($billTypeIdRes)-1)){
				
				$billTypeIdList=$billTypeIdList."'".$id."'";
			}
			else{
				$billTypeIdList=$billTypeIdList."'".$id."',";

			}

		}

		$pilotChargeSubDetailQuery = "SELECT bil_tariffs.description,bil_tariffs.id
		,bil_tariffs.gl_code
		,bil_tariff_rates.rate_type,
		bil_tariff_rates.amount AS rate,

		(CASE WHEN bil_tariffs.description='PD SEA VESSEL' THEN ($grt+$deckCargo) ELSE 1 END) AS unit,
		1 AS move,
			
		(CASE WHEN bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='PD SEA VESSEL' THEN 'GRT' ELSE 'NOS' END) AS bas

		FROM bil_tariffs

		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='REGULAR' and bil_tariffs.id IN ($billTypeIdList)";
		$pilotChargeSubDetailQuery= $this->bm->dataSelectDb3($pilotChargeSubDetailQuery);
			
		return $pilotChargeSubDetailQuery;
	}






	function getPilotChargesQueryTugCancellation($rotation) // changed here
	{
		$getPilotChargesQueryTugCancellation = "  SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessel_visit_details.ib_vyg,
		vsl_vessels.name AS vsl_name,
		vsl_vessel_visit_details.ib_vyg AS rotation,
		to_char(vsl_vessel_visit_details.off_port_arr,'yyyy-mm-dd') AS oa_date,
		to_char(ata,'yyyy-mm-dd hh24:mi:ss') AS ata,
		to_char(atd,'yyyy-mm-dd hh24:mi:ss') AS atd,
		
		vsl_vessel_classes.gross_registered_ton AS grt,
		vsl_vessel_classes.net_registered_ton AS nrt,
		ref_country.cntry_name AS flag,
		ref_country.cntry_code AS cnt_code,
		Y.id AS agent_code,
		Y.name AS agent_name,
		Y.address_line1 AS agent_address,
		NVL(ref_agent.agent_alias_id,'') AS agent_alias_id,
		'0' AS deck_cargo,
		'110' AS bill_type,
		loa_cm
		FROM vsl_vessel_visit_details
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		INNER JOIN vsl_vessel_classes ON vsl_vessels.vesclass_gkey=vsl_vessel_classes.gkey
		
		INNER JOIN ref_bizunit_scoped r ON r.gkey = vsl_vessel_visit_details.bizu_gkey
		LEFT JOIN ref_agent_representation X ON r.gkey=X.bzu_gkey
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey
		
		LEFT JOIN ref_country ON vsl_vessels.country_code=ref_country.cntry_code
		LEFT JOIN ref_agent ON ref_agent.agent_id=X.agent_gkey
		WHERE vsl_vessel_visit_details.ib_vyg='$rotation'";
		return $getPilotChargesQueryTugCancellation;
	}

	function getPilotSubDetailsQueryTugCancellation($grt,$deck_cargo,$loa_cm,$rotNo) //changed here
	{	
		$ctmsQuery="SELECT id FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=110";
		$billTypeIdRes=$this->bm->dataSelectDb2($ctmsQuery);

		$billTypeIdList="";
		for($i=0;$i<count($billTypeIdRes);$i++){
			$id="";
			$id=$billTypeIdRes[$i]['id'];

			if($i==(count($billTypeIdRes)-1)){
				$billTypeIdList=$billTypeIdList."'".$id."'";
			}
			else{
				$billTypeIdList=$billTypeIdList."'".$id."',";

			}
		}

		$getPilotSubDetailsQueryTugCancell = "

		SELECT description,gl_code,rate,unit,bas,move,rate_type
		FROM
		(
		
		SELECT (CASE
		WHEN $grt<1001 THEN CONCAT(CONCAT(bil_tariffs.description,' '),
		
		
		(SELECT description FROM bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=bil_tariff_rates.gkey))
		
		
		ELSE 
		(CASE
		WHEN 18870<5001 THEN CONCAT(CONCAT(bil_tariffs.description,' '),
		
		
		(SELECT description FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey))
		ELSE CONCAT(CONCAT(bil_tariffs.description,' '),
		
		
		(SELECT description FROM bil_tariff_rate_tiers 
		WHERE min_quantity=5001 AND rate_gkey=bil_tariff_rates.gkey))
		END)
		END)
		AS description,
		bil_tariffs.gl_code,
		bil_tariff_rates.rate_type,
		
		
		(CASE
		WHEN $grt<1001 THEN (SELECT is_flat_rate FROM bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=bil_tariff_rates.gkey)
		ELSE 
		(CASE WHEN  $grt<5001 THEN (SELECT is_flat_rate FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey)
		ELSE 
		(SELECT is_flat_rate FROM bil_tariff_rate_tiers WHERE min_quantity=5001 AND rate_gkey=bil_tariff_rates.gkey)
		END)
		END)
		AS rate,
		
		(CASE WHEN  $loa_cm>18600  THEN 1 ELSE 2  END) AS unit,
		
		1 AS move,'NOS' AS bas
		
		FROM bil_tariffs
		
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='BAND' AND bil_tariffs.id IN ($billTypeIdList)
		
		
		) final";

		
		$getPilotSubDetailsQueryTugCancell= $this->bm->dataSelectDb3($getPilotSubDetailsQueryTugCancell);

	
		return $getPilotSubDetailsQueryTugCancell;
	}


	
	function checkExchangeRateQuery($rotation)
	{
		$checkExchangeRateQuery = "SELECT IFNULL((SELECT rate FROM billing.bil_currency_exchange_rates WHERE DATE(effective_date)=DATE(off_port_arr)),
		(SELECT rate FROM billing.bil_currency_exchange_rates ORDER BY effective_date DESC LIMIT 1)) AS exchangeRate
		FROM sparcsn4.vsl_vessel_visit_details
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.cvcvd_gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg='$rotation' AND sparcsn4.argo_carrier_visit.phase != '80CANCELED'";
		return $checkExchangeRateQuery;
	}
	
	function getJettyChargesQueryPangoanVsl($rotation)
	{
		$getJettyChargesQueryPangoanVsl = "SELECT vsldtl.ib_vyg AS rotation,arcar_visit.phase,vsl.name AS vsl_name,vslbrth.ata,vslbrth.atd,quay.id AS berth,
		NVL(ref_agent.agent_alias_id,'') AS agent_alias_id,Y.id AS agent_code,Y.name AS agent_name,
		CONCAT(CONCAT(NVL(Y.address_line1,''),' '),NVL(Y.address_line2,'')) AS address,
		vsldtl.off_port_arr AS oa_date,
		'Bangladesh' AS flag,'PBD' AS cnt_code,classes.gross_registered_ton AS grt,vsl.ves_captain AS master_name,
		0 AS deck_cargo,1 AS exchangeRate,
		
		(CASE WHEN Extract(second from (NVL(vslbrth.atd,CURRENT_DATE)-vslbrth.ata))< 5 THEN 5 
		ELSE
		Extract(second from (NVL(vslbrth.atd,CURRENT_DATE)-vslbrth.ata))
		END) AS unit,
		101 AS bill_type,vsldtl.vvd_gkey,
		
		(CASE WHEN to_char(vslbrth.ata,'yyyy-mm-dd') >= '2016-10-10' THEN 1 ELSE 0 END)  AS rtdt
		FROM vsl_vessel_visit_details vsldtl
		INNER JOIN argo_carrier_visit arcar_visit ON arcar_visit.cvcvd_gkey = vsldtl.vvd_gkey
		INNER JOIN vsl_vessels vsl ON vsl.gkey=vsldtl.vessel_gkey 
		INNER JOIN vsl_vessel_berthings vslbrth ON vslbrth.vvd_gkey=vsldtl.vvd_gkey
		INNER JOIN argo_quay quay ON quay.gkey=vslbrth.quay
		INNER JOIN vsl_vessel_classes classes ON vsl.vesclass_gkey=classes.gkey 
		INNER JOIN  ( ref_bizunit_scoped r  
		LEFT JOIN ( ref_agent_representation X  
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )
		ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
		LEFT JOIN ref_country cntry ON vsl.country_code=cntry.cntry_code
		LEFT JOIN ref_agent ON ref_agent.agent_id=X.agent_gkey
		WHERE ib_vyg='$rotation' AND arcar_visit.phase != '80CANCELED'";
		
		return $getJettyChargesQueryPangoanVsl;
	}
	
	
	
	function getJettyChargesQueryPangoanVslQGC($rotation)
	{
		$getJettyChargesQueryPangoanVslQGC = "SELECT vsldtl.ib_vyg AS rotation,arcar_visit.phase,vsl.name AS vsl_name,vslbrth.ata,vslbrth.atd,quay.id AS berth,
		NVL(ref_agent.agent_alias_id,'') AS agent_alias_id,Y.id AS agent_code,Y.name AS agent_name,CONCAT(CONCAT(NVL(Y.address_line1,''),' '),
		NVL(Y.address_line2,'')) AS address,
		
		vsldtl.off_port_arr AS oa_date,
		'Bangladesh' AS flag,'QBD' AS cnt_code,classes.gross_registered_ton AS grt,vsl.ves_captain AS master_name,
		0 AS deck_cargo,
		(CASE WHEN Extract(second from (NVL(vslbrth.atd,CURRENT_DATE)-vslbrth.ata))< 5 THEN 5 
		ELSE
		Extract(second from (NVL(vslbrth.atd,CURRENT_DATE)-vslbrth.ata))
		END) AS unit,101 AS bill_type,vsldtl.vvd_gkey,
		(CASE WHEN to_char(vslbrth.ata,'yyyy-mm-dd') >= '2016-10-10' THEN 1 ELSE 0 END)  AS rtdt
		FROM vsl_vessel_visit_details vsldtl
		INNER JOIN argo_carrier_visit arcar_visit ON arcar_visit.cvcvd_gkey = vsldtl.vvd_gkey
		INNER JOIN vsl_vessels vsl ON vsl.gkey=vsldtl.vessel_gkey 
		INNER JOIN vsl_vessel_berthings vslbrth ON vslbrth.vvd_gkey=vsldtl.vvd_gkey
		INNER JOIN argo_quay quay ON quay.gkey=vslbrth.quay
		INNER JOIN vsl_vessel_classes classes ON vsl.vesclass_gkey=classes.gkey 
		INNER JOIN  ( ref_bizunit_scoped r  
		LEFT JOIN ( ref_agent_representation X  
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )
		ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
		LEFT JOIN ref_country cntry ON vsl.country_code=cntry.cntry_code
		LEFT JOIN ref_agent ON ref_agent.agent_id=X.agent_gkey
		WHERE ib_vyg='$rotation' AND arcar_visit.phase != '80CANCELED'";
		
		return $getJettyChargesQueryPangoanVslQGC;
	}
	
	
	function getJettyChargesQuery($rotation)
	{
		
		$getJettyChargesQuery = "SELECT vsldtl.ib_vyg AS rotation,arcar_visit.phase,TO_CHAR(arcar_visit.ata,'YYYY-MM-DD') AS argoAta,vsl.name AS vsl_name,
		vslbrth.ata,vslbrth.atd,quay.id AS berth,ref_agent.agent_alias_id,Y.id AS agent_code,Y.name AS agent_name,
		CONCAT(CONCAT(NVL(Y.address_line1,''),' '),NVL(Y.address_line2,'')) AS address,
		vsldtl.off_port_arr AS oa_date,
		cntry.cntry_code AS cnt_code,cntry.cntry_name AS flag,classes.gross_registered_ton AS grt,vsl.ves_captain AS master_name,
		0 AS deck_cargo,
		Extract(second from (NVL(vslbrth.atd,CURRENT_DATE))-vslbrth.ata) AS unit,
		101 AS bill_type,vsldtl.vvd_gkey,
		(CASE WHEN to_char(vslbrth.ata,'yyyy-mm-dd')>= '2016-10-10' THEN 1 ELSE 0 END)  AS rtdt,
		vsldtl.flex_date01 AS pilot_ib_onboard,vsldtl.flex_date02 AS pilot_ib_offboard,vsldtl.flex_date03 AS pilot_ob_onboard,
		vsldtl.flex_date04 AS pilot_ob_offboard
		FROM vsl_vessel_visit_details vsldtl
		INNER JOIN argo_carrier_visit arcar_visit ON arcar_visit.cvcvd_gkey = vsldtl.vvd_gkey
		INNER JOIN vsl_vessels vsl ON vsl.gkey=vsldtl.vessel_gkey 
		INNER JOIN vsl_vessel_berthings vslbrth ON vslbrth.vvd_gkey=vsldtl.vvd_gkey
		INNER JOIN argo_quay quay ON quay.gkey=vslbrth.quay
		INNER JOIN vsl_vessel_classes classes ON vsl.vesclass_gkey=classes.gkey 
		INNER JOIN  ( ref_bizunit_scoped r  
		LEFT JOIN ( ref_agent_representation X  
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
		ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
		LEFT JOIN ref_country cntry ON vsl.country_code=cntry.cntry_code
		LEFT JOIN ref_agent ON ref_agent.agent_id=X.agent_gkey
		WHERE ib_vyg='$rotation' AND arcar_visit.phase != '80CANCELED'";
		
		return $getJettyChargesQuery;
	}

	
	function waterSupplySubDetailQueryPangoan($rotation)
	{
		

		$waterSupplySubDetailQueryPangoan = "SELECT srv_event_types.id,
		(CASE srv_event_types.id  WHEN 'BERTHING' THEN 'NOS'
		WHEN 'WATER_BY_LINE' THEN 'TON'
		WHEN 'BERTH HIRE 1-13, 17, SLJ' THEN 'HRS'
		WHEN 'WTR CPA BRG' THEN 'TON'
		WHEN 'TUG_ADDITIONAL' THEN 'HRS'
		WHEN 'TUG CHG SFT VSL' THEN 'NOS'
		WHEN 'UNBERTHING' THEN 'NOS'
		WHEN 'TUG' THEN 'NOS'
		WHEN 'NGT_NAVI_VSL' THEN 'NOS'
		WHEN 'SHIFT/SWING DAY' THEN 'NOS'
		WHEN 'PD_SEA_VESSEL' THEN 'GRT'
		WHEN 'PD_SEA_NON_LIGHTER' THEN 'GRT'
		WHEN 'SWING MRNG/DAY/PART' THEN 'DIM'
		WHEN 'FXD MRNG/DAY/PART' THEN 'DIM'
		WHEN 'SHIFT VESSEL BERTH' THEN 'NOS'
		WHEN 'SHIFT VESSEL UNBERTH' THEN 'NOS'
		WHEN 'DIVING BOAT' THEN 'DIM'
		WHEN 'CPA PORTABLE FIRE PUMP' THEN 'HRS'
		WHEN 'HIRE OF RIGGERS UNIT' THEN 'DIM'
		WHEN 'HIRE OF SALVAGE DIV UNIT' THEN 'DIM'
		WHEN 'FIREMAN' THEN 'HRS'
		WHEN 'SHIFT/SWING NIGHT' THEN 'NOS'
		WHEN 'CPA FIRE ENGINE' THEN 'HRS'
		WHEN 'FIRE OFFICER' THEN 'HRS'
		WHEN 'BERTHING' THEN 'NOS'
		WHEN 'PILOTAGE' THEN 'GRT'
		WHEN 'BLV FOR SALVAGE WORK IN/OUT' THEN 'DIM'
		WHEN 'BERTH_HIRE_1-13_17_SLJ' THEN 'HRS'
		WHEN 'TUG_DEAD_VESSEL' THEN 'HRS'
		WHEN 'TUG_CANCEL' THEN 'NOS'
		WHEN 'WORK_OUTSIDE_PORT_LIMIT' THEN 'HRS'
		WHEN 'CPA WATER FOAM TENDER' THEN 'HRS'
		WHEN 'TUG FOR FIRE FIGHTING' THEN 'HRS'
		WHEN 'CPA TRAILER PUMP' THEN 'HRS'
		ELSE NULL END) AS bas,srv_event.quantity AS unit
		FROM srv_event
		INNER JOIN srv_event_types ON  srv_event_types.gkey=srv_event.event_type_gkey
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=srv_event.applied_to_gkey
		WHERE vsl_vessel_visit_details.ib_vyg='$rotation' AND applied_to_class='VV' AND srv_event_types.gkey IN(169)";

		return $waterSupplySubDetailQueryPangoan;
	}
	
	function waterSupplySubDetailQuery($rotation)		// non pangaon
	{
		$waterSupplySubDetailQuery = "SELECT DISTINCT billing.bil_tariffs.description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.amount AS rate,
		(SELECT CASE sparcsn4.srv_event_types.id  WHEN 'BERTHING' THEN 'NOS'
		WHEN 'WATER_BY_LINE' THEN 'MT'
		WHEN 'BERTH HIRE 1-13, 17, SLJ' THEN 'HRS'
		WHEN 'WTR CPA BRG' THEN 'TON'
		WHEN 'TUG_ADDITIONAL' THEN 'HRS'
		WHEN 'TUG CHG SFT VSL' THEN 'NOS'
		WHEN 'UNBERTHING' THEN 'NOS'
		WHEN 'TUG' THEN 'NOS'
		WHEN 'NGT_NAVI_VSL' THEN 'NOS'
		WHEN 'SHIFT/SWING DAY' THEN 'NOS'
		WHEN 'PD_SEA_VESSEL' THEN 'GRT'
		WHEN 'PD_SEA_NON_LIGHTER' THEN 'GRT'
		WHEN 'SWING MRNG/DAY/PART' THEN 'DIM'
		WHEN 'FXD MRNG/DAY/PART' THEN 'DIM'
		WHEN 'SHIFT VESSEL BERTH' THEN 'NOS'
		WHEN 'SHIFT VESSEL UNBERTH' THEN 'NOS'
		WHEN 'DIVING BOAT' THEN 'DIM'
		WHEN 'CPA PORTABLE FIRE PUMP' THEN 'HRS'
		WHEN 'HIRE OF RIGGERS UNIT' THEN 'DIM'
		WHEN 'HIRE OF SALVAGE DIV UNIT' THEN 'DIM'
		WHEN 'FIREMAN' THEN 'HRS'
		WHEN 'SHIFT/SWING NIGHT' THEN 'NOS'
		WHEN 'CPA FIRE ENGINE' THEN 'HRS'
		WHEN 'FIRE OFFICER' THEN 'HRS'
		WHEN 'BERTHING' THEN 'NOS'
		WHEN 'PILOTAGE' THEN 'GRT'
		WHEN 'BLV FOR SALVAGE WORK IN/OUT' THEN 'DIM'
		WHEN 'BERTH_HIRE_1-13_17_SLJ' THEN 'HRS'
		WHEN 'TUG_DEAD_VESSEL' THEN 'HRS'
		WHEN 'TUG_CANCEL' THEN 'NOS'
		WHEN 'WORK_OUTSIDE_PORT_LIMIT' THEN 'HRS'
		WHEN 'CPA WATER FOAM TENDER' THEN 'HRS'
		WHEN 'TUG FOR FIRE FIGHTING' THEN 'HRS'
		WHEN 'CPA TRAILER PUMP' THEN 'HRS'
		ELSE NULL END) AS bas,sparcsn4.srv_event.quantity AS unit
		FROM sparcsn4.srv_event
		INNER JOIN sparcsn4.srv_event_types ON  srv_event_types.gkey=srv_event.event_type_gkey
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.srv_event.applied_to_gkey
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=sparcsn4.srv_event_types.id
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotation' AND applied_to_class='VV' AND srv_event_types.gkey IN(168,169,461,463)
		AND billing.bil_tariff_rates.rate_type='REGULAR'";
		
		return $waterSupplySubDetailQuery;
	}
	
	function getHarbourCraneSubDetailChargesQueryCurrentVslIMP($vvdGkey)
    {
		/*$getHarbourCraneSubDetailChargesQueryCurrentVslIMP = "SELECT description,IFNULL(gl_code,'333') AS gl_code,amount/2 AS rate,'NOS' AS bas,COUNT(description) AS unit
		FROM
		(
		SELECT
		billing.bil_tariffs.description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.amount
		FROM 
		(
		SELECT (SELECT  ctmsmis.get_mis_bill_tarrif(sparcsn4.inv_unit.gkey,500)) AS  tarrif
		FROM sparcsn4.inv_unit
		INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
		WHERE sparcsn4.argo_carrier_visit.cvcvd_gkey='$vvdGkey'
		) AS mytbl
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=mytbl.tarrif
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		) final GROUP BY description";
		return $getHarbourCraneSubDetailChargesQueryCurrentVslIMP;*/

		$sparcsn4Query="select inv_unit.gkey
		FROM inv_unit
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
		WHERE argo_carrier_visit.cvcvd_gkey='$vvdGkey'";
		$sparcsn4QueryRes=$this->bm->dataSelect($sparcsn4Query);
        $tarifList="";
		for($i=0;$i<count($sparcsn4QueryRes);$i++){
			$gkey="";
			$gkey=$sparcsn4QueryRes[$i]['GKEY'];
			$ctmsQuery="SELECT  ctmsmis.get_mis_bill_tarrif('$gkey',500)) AS  tarrif";
			$ctmsQueryRes=$this->bm->dataSelectDb2($ctmsQuery);
			$tarif="";
			$tarif=$ctmsQueryRes[0]['tarrif'];
			if($i==(count($sparcsn4QueryRes)-1)){
				$tarifList=$tarifList."'".$tarif."'";
			}
			else{
				$tarifList=$tarifList."'".$tarif."',";

			}

		}

		$billingQuery="SELECT description,NVL(gl_code,'333') AS gl_code,amount/2 AS rate,'NOS' AS bas
		from(
		SELECT
		bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.amount
		from bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		where bil_tariffs.id IN ($tarifList)
		) final";
			$billingQueryRes=$this->bm->dataSelectDb3($billingQuery);
			
		return $billingQueryRes;
    }
	
	
	function getHarbourCraneSubDetailChargesQueryOldVslIMP($rotation)
    {
		/*$getHarbourCraneSubDetailChargesQueryOldVslIMP = "SELECT description,IFNULL(gl_code,'333') AS gl_code,amount AS rate,'NOS' AS bas,COUNT(description) AS unit
		FROM
		(
		SELECT
		billing.bil_tariffs.description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.amount
		FROM 
		(
		SELECT (SELECT  ctmsmis.get_mis_bill_tarrif_pan_old_vsl(pan_gkey,500)) AS  tarrif
		FROM ctmsmis.mis_pangoan_old_vsl_cont
		WHERE vvd_gkey='$rotation' AND category='IMPRT'
		) AS mytbl
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=mytbl.tarrif
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		) final GROUP BY description";
		return $getHarbourCraneSubDetailChargesQueryOldVslIMP;*/

		$ctmsQuery="SELECT (SELECT  ctmsmis.get_mis_bill_tarrif_pan_old_vsl(pan_gkey,500)) AS  tarrif
		FROM ctmsmis.mis_pangoan_old_vsl_cont
		WHERE vvd_gkey='$rotation' AND category='IMPRT'";
		$ctmsQueryRes=$this->bm->dataSelectDb2($ctmsQuery);
		$tarifList="";
		for($i=0;$i<count($ctmsQueryRes);$i++){
			$tarif="";
			$tarif=$ctmsQueryRes[$i]['tarrif'];
			if($i==(count($ctmsQueryRes)-1)){
				$tarifList=$tarifList."'".$tarif."'";
			}
			else{
				$tarifList=$tarifList."'".$tarif."',";

			}

		}

		$billingQuery="SELECT  description,NVL(gl_code,'333') AS gl_code,amount AS rate,'NOS' AS bas
		from(
		SELECT
		bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.amount
		from bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		where bil_tariffs.id IN ($tarifList)
		) final";
			$billingQueryRes=$this->bm->dataSelectDb3($billingQuery);


		return $billingQueryRes;
	 }
	
	 function getHarbourCraneSubDetailChargesQueryCurrentVslEXP($vvdGkey)
	 {
		/* $getHarbourCraneSubDetailChargesQueryCurrentVslEXP = "SELECT description,IFNULL(gl_code,'333') AS gl_code,amount/2 AS rate,'NOS' AS bas,COUNT(description) AS unit
		 FROM
		 (
		 SELECT
		 billing.bil_tariffs.description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.amount
		 FROM 
		 (
		 SELECT (SELECT  ctmsmis.get_mis_bill_tarrif(sparcsn4.inv_unit.gkey,500)) AS  tarrif
		 FROM sparcsn4.inv_unit
		 INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
		 INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ob_cv
		 WHERE sparcsn4.argo_carrier_visit.cvcvd_gkey='$vvdGkey'
		 ) AS mytbl
		 INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=mytbl.tarrif
		 INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		 ) final GROUP BY description";
		 
		 return $getHarbourCraneSubDetailChargesQueryCurrentVslEXP;*/

		 $sparcsn4Query="select inv_unit.gkey
		FROM inv_unit
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
		WHERE argo_carrier_visit.cvcvd_gkey='$vvdGkey'";
		$sparcsn4QueryRes=$this->bm->dataSelect($sparcsn4Query);
		$tarifList="";
		for($i=0;$i<count($sparcsn4QueryRes);$i++){
			$gkey="";
			$gkey=$sparcsn4QueryRes[$i]['GKEY'];
			$ctmsQuery="SELECT  ctmsmis.get_mis_bill_tarrif('$gkey',500)) AS  tarrif";
			$ctmsQueryRes=$this->bm->dataSelectDb2($ctmsQuery);
			$tarif="";
			$tarif=$ctmsQueryRes[0]['tarrif'];
			if($i==(count($sparcsn4QueryRes)-1)){
				$tarifList=$tarifList."'".$tarif."'";
			}
			else{
				$tarifList=$tarifList."'".$tarif."',";

			}

		}

		$billingQuery="SELECT description,NVL(gl_code,'333') AS gl_code,amount/2 AS rate,'NOS' AS bas
		from(
		SELECT
		bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.amount
		from bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		where bil_tariffs.id IN ($tarifList)
		) final";
			$billingQueryRes=$this->bm->dataSelectDb3($billingQuery);
			
		return $billingQueryRes;

		 
	 }
	 
	
	 function getHarbourCraneSubDetailChargesQueryOldVslEXP($vvdGkey)
	 {
		/* $getHarbourCraneSubDetailChargesQueryOldVslEXP = "SELECT description,IFNULL(gl_code,'333') AS gl_code,amount AS rate,'NOS' AS bas,COUNT(description) AS unit
		 FROM
		 (
		 SELECT 
		 billing.bil_tariffs.description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.amount
		 FROM 
		 ( 
		 SELECT (SELECT  ctmsmis.get_mis_bill_tarrif_pan_old_vsl(pan_gkey,500)) AS  tarrif
		 FROM ctmsmis.mis_pangoan_old_vsl_cont 
		 WHERE vvd_gkey='$vvdGkey' AND category='EXPRT'
		 ) AS mytbl 
		 INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=mytbl.tarrif
		 INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		 ) final GROUP BY description";
		 
		 return $getHarbourCraneSubDetailChargesQueryOldVslEXP;*/

		 $ctmsQuery="SELECT (SELECT  ctmsmis.get_mis_bill_tarrif_pan_old_vsl(pan_gkey,500)) AS  tarrif
		FROM ctmsmis.mis_pangoan_old_vsl_cont
		WHERE vvd_gkey='$vvdGkey' AND category='IMPRT'";
		$ctmsQueryRes=$this->bm->dataSelectDb2($ctmsQuery);
		$tarifList="";
		for($i=0;$i<count($ctmsQueryRes);$i++){
			$tarif="";
			$tarif=$ctmsQueryRes[$i]['tarrif'];
			if($i==(count($ctmsQueryRes)-1)){
				$tarifList=$tarifList."'".$tarif."'";
			}
			else{
				$tarifList=$tarifList."'".$tarif."',";

			}

		}

		$billingQuery="SELECT  description, NVL(gl_code,'333') AS gl_code,amount AS rate,'NOS' AS bas
		from(
		SELECT
		bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.amount
		from bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		where bil_tariffs.id IN ($tarifList)
		) final";
		$billingQueryRes=$this->bm->dataSelectDb3($billingQuery);


		return $billingQueryRes;
	 }
	 
	
	 function getGantryCraneSubDetailChargesQueryForQGC($rotation)
	 {
		/* $getGantryCraneSubDetailChargesQueryForQGC = "SELECT * FROM  
		 ( 
		 SELECT description,gl_code,amount AS rate,'NOS' AS bas,COUNT(description) AS unit,currency_gkey 
		 FROM 
		 ( 
		 SELECT 
		 CONCAT(SUBSTRING(SUBSTRING(billing.bil_tariffs.description,6),1,8),'IMP ',SUBSTRING(SUBSTRING(billing.bil_tariffs.description,6),9)) AS description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.amount,billing.bil_tariff_rates.currency_gkey 
		 FROM  
		 ( 
		 SELECT CONCAT('CCT1_',(SELECT  ctmsmis.get_mis_bill_tarrif(sparcsn4.inv_unit.gkey,155))) AS  tarrif,(SELECT ctmsmis.berth_for_cont(sparcsn4.vsl_vessel_visit_details.vvd_gkey,sparcsn4.inv_unit_fcy_visit.time_in)) AS berth 
		 FROM sparcsn4.inv_unit 
		 INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey 
		 INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv 
		 INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey 
		 WHERE ib_vyg='$rotation' AND category='IMPRT' 
		 ) AS mytbl 
		 INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=mytbl.tarrif 
		 INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey 
		 ) final GROUP BY description 
		 UNION  
		 SELECT description,gl_code,amount AS rate,'NOS' AS bas,COUNT(description) AS unit,currency_gkey 
		 FROM 
		 ( 
		 SELECT 
		 CONCAT(SUBSTRING(SUBSTRING(billing.bil_tariffs.description,6),1,8),'EXP ',SUBSTRING(SUBSTRING(billing.bil_tariffs.description,6),9)) AS description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.amount,billing.bil_tariff_rates.currency_gkey 
		 FROM  
		 ( 
		 SELECT sparcsn4.inv_unit.gkey,sparcsn4.inv_unit.id,CONCAT('CCT1_',(SELECT  ctmsmis.get_mis_bill_tarrif(sparcsn4.inv_unit.gkey,155))) AS  tarrif,(SELECT ctmsmis.berth_for_cont(sparcsn4.vsl_vessel_visit_details.vvd_gkey,sparcsn4.inv_unit_fcy_visit.time_in)) AS berth 
		 FROM sparcsn4.inv_unit 
		 INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey 
		 INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ob_cv 
		 INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey 
		 WHERE ib_vyg='$rotation' AND category='EXPRT' AND sparcsn4.inv_unit_fcy_visit.transit_state='S70_DEPARTED' 
		 ) AS mytbl 
		 INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=mytbl.tarrif 
		 INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey 
		 ) final GROUP BY description 
		 ) t GROUP BY description  ORDER BY unit DESC";
 
		 return $getGantryCraneSubDetailChargesQueryForQGC;*/

		 $sparcsn4Query1="SELECT inv_unit.gkey         
		 FROM inv_unit 
		 INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
		 INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv 
		 INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
		 WHERE ib_vyg='$rotation' AND category='IMPRT'";
		 $sparcsn4QueryRes1=$this->bm->dataSelect($sparcsn4Query1);
		 $tarifList1="";
		 for($i=0;$i<count($sparcsn4QueryRes1);$i++){
			 $gkey="";
			 $gkey=$sparcsn4QueryRes1[$i]['GKEY'];
			 $ctmsQuery1="SELECT CONCAT('CCT1_',(SELECT  ctmsmis.get_mis_bill_tarrif('$gkey',155))) AS  tarrif";
			 $ctmsQueryRes1=$this->bm->dataSelectDb2($ctmsQuery1);
			 $tarif="";
			 $tarif=$ctmsQueryRes1[0]['tarrif'];
			 if($i==(count($sparcsn4QueryRes1)-1)){
				 $tarifList1=$tarifList1."'".$tarif."'";
			 }
			 else{
				 $tarifList1=$tarifList1."'".$tarif."',";
 
			 }
 
		 }
		 $tarifList2="";
		 $sparcsn4Query2="SELECT inv_unit.gkey   
		 FROM inv_unit 
		 INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
		 INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv 
		 INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey 
		 WHERE ib_vyg='$rotation' AND category='EXPRT' AND inv_unit_fcy_visit.transit_state='S70_DEPARTED'";
		 $sparcsn4QueryRes2=$this->bm->dataSelect($sparcsn4Query2);
		 $tarifList2="";
		 for($i=0;$i<count($sparcsn4QueryRes2);$i++){
			 $gkey="";
			 $gkey=$sparcsn4QueryRes2[$i]['GKEY'];
			 $ctmsQuery2="SELECT CONCAT('CCT1_',(SELECT  ctmsmis.get_mis_bill_tarrif('$gkey',155))) AS  tarrif";
			 $ctmsQueryRes2=$this->bm->dataSelectDb2($ctmsQuery2);
			 $tarif="";
			 $tarif=$ctmsQueryRes2[0]['tarrif'];
			 if($i==(count($sparcsn4QueryRes2)-1)){
				 $tarifList2=$tarifList2."'".$tarif."'";
			 }
			 else{
				 $tarifList2=$tarifList2."'".$tarif."',";
 
			 }
 
		 }

		 $billingQuery="SELECT CONCAT( CONCAT(SUBSTR(SUBSTR(bil_tariffs.description,6),1,8),'EXP '),SUBSTR(SUBSTR(bil_tariffs.description,6),9)) AS description,
		 bil_tariffs.gl_code,bil_tariff_rates.amount,bil_tariff_rates.currency_gkey
		from bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey 
		where bil_tariffs.id IN ($tarifList1) 
		UNION
		SELECT CONCAT( CONCAT(SUBSTR(SUBSTR(bil_tariffs.description,6),1,8),'EXP '),SUBSTR(SUBSTR(bil_tariffs.description,6),9)) AS description,
		 bil_tariffs.gl_code,bil_tariff_rates.amount,bil_tariff_rates.currency_gkey
		from bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey 
		where bil_tariffs.id IN ($tarifList2)";
	    $billingQueryRes=$this->bm->dataSelectDb3($billingQuery);

		return $billingQueryRes;


	 }
	 
	
	 function getCCTCountQuery($rotation)
	 {
		 $getCCTCountQuery = "SELECT COUNT(quay.id) AS totalCCT
		 FROM vsl_vessel_visit_details
		 INNER JOIN vsl_vessel_berthings vslbrth ON vslbrth.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
		 INNER JOIN argo_quay quay ON quay.gkey=vslbrth.quay
		 WHERE ib_vyg='$rotation' AND (quay.id LIKE 'CCT%' OR quay.id LIKE 'NCT%')";
		 
		 return $getCCTCountQuery;
	 }

	function getGCBCountQuery($rotation)
    {
		$getGCBCountQuery = "SELECT COUNT(quay.id) AS totalCCT
		FROM vsl_vessel_visit_details
		INNER JOIN vsl_vessel_berthings vslbrth ON vslbrth.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
		INNER JOIN argo_quay quay ON quay.gkey=vslbrth.quay
		WHERE ib_vyg='$rotation' AND quay.id LIKE 'GCB%' OR quay.id LIKE 'PCT%'";
		
		return $getGCBCountQuery;
    }
	
	function getGantryCraneSubDetailChargesQuery($rotation)
	{
		
		
		// correction for CCT1_QGC_LOADED_45 added
		/*$getGantryCraneSubDetailChargesQuery = "SELECT * FROM 
		(
		SELECT description,gl_code,amount AS rate,'NOS' AS bas,COUNT(description) AS unit
		FROM
		(

		SELECT IF(tarrif_id='CCT1_QGC_LOADED_45','G/C CHE IMP 40 L CTR',description) AS description,gl_code,amount
		FROM
		(
		SELECT
		CONCAT(SUBSTRING(SUBSTRING(billing.bil_tariffs.description,6),1,8),'IMP ',SUBSTRING(SUBSTRING(billing.bil_tariffs.description,6),9)) AS description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.amount,billing.bil_tariffs.id AS tarrif_id
		FROM 
		(
		SELECT CONCAT('CCT1_',(SELECT  ctmsmis.get_mis_bill_tarrif(sparcsn4.inv_unit.gkey,155))) AS  tarrif,(SELECT ctmsmis.berth_for_cont(sparcsn4.vsl_vessel_visit_details.vvd_gkey,sparcsn4.inv_unit_fcy_visit.time_in)) AS berth
		FROM sparcsn4.inv_unit
		INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
		WHERE ib_vyg='$rotation' AND category='IMPRT'
		) AS mytbl
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=mytbl.tarrif
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE mytbl.berth LIKE 'CCT%' OR mytbl.berth LIKE 'NCT%'
		) AS mytbl_2

		) final GROUP BY description

		UNION 

		SELECT description,gl_code,amount AS rate,'NOS' AS bas,COUNT(description) AS unit
		FROM
		(

		SELECT IF(tarrif_id='CCT1_QGC_LOADED_45','G/C CHE EXP 40 L CTR',description) AS description,gl_code,amount
		FROM
		(
		SELECT
		CONCAT(SUBSTRING(SUBSTRING(billing.bil_tariffs.description,6),1,8),'EXP ',SUBSTRING(SUBSTRING(billing.bil_tariffs.description,6),9)) AS description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.amount,billing.bil_tariffs.id AS tarrif_id
		FROM 
		(
		SELECT sparcsn4.inv_unit.gkey,sparcsn4.inv_unit.id,CONCAT('CCT1_',(SELECT  ctmsmis.get_mis_bill_tarrif(sparcsn4.inv_unit.gkey,155))) AS  tarrif,(SELECT ctmsmis.berth_for_cont(sparcsn4.vsl_vessel_visit_details.vvd_gkey,sparcsn4.inv_unit_fcy_visit.time_in)) AS berth
		FROM sparcsn4.inv_unit
		INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ob_cv
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
		WHERE ib_vyg='$rotation' AND category='EXPRT' AND sparcsn4.inv_unit_fcy_visit.transit_state='S70_DEPARTED'
		) AS mytbl
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=mytbl.tarrif
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE mytbl.berth LIKE 'CCT%' OR mytbl.berth LIKE 'NCT%'

		) AS mytbl_2

		) final GROUP BY description
		) t GROUP BY description  ORDER BY unit DESC";
		
		return $getGantryCraneSubDetailChargesQuery;*/

		$sparcsn4Query1="SELECT inv_unit.gkey,vsl_vessel_visit_details.vvd_gkey,inv_unit_fcy_visit.time_in        
		FROM inv_unit 
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv 
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
		WHERE ib_vyg='$rotation' AND category='IMPRT'";
		$sparcsn4QueryRes1=$this->bm->dataSelect($sparcsn4Query1);
		$tarifList1="";
		for($i=0;$i<count($sparcsn4QueryRes1);$i++){
				$gkey="";
				$vvd_gkey="";
				$time_in="";
				$gkey=$sparcsn4QueryRes1[$i]['GKEY'];
				$vvd_gkey=$sparcsn4QueryRes1[$i]['VVD_GKEY'];
				$time_in=$sparcsn4QueryRes1[$i]['TIME_IN'];
				$ctmsQuery1="SELECT *
				FROM(
				SELECT CONCAT('CCT1_',(SELECT  ctmsmis.get_mis_bill_tarrif('$gkey',155))) AS  tarrif, 
				(SELECT ctmsmis.berth_for_cont('$vvd_gkey','$time_in')) AS berth 
				)AS tmp WHERE berth  LIKE 'CCT%' OR berth LIKE 'NCT%'";
				$ctmsQueryRes1=$this->bm->dataSelectDb2($ctmsQuery1);
				if(count($ctmsQueryRes1)>0){
				$tarif="";
				$tarif=$ctmsQueryRes1[0]['tarrif'];
				if($i==(count($sparcsn4QueryRes1)-1)){
					$tarifList1=$tarifList1."'".$tarif."'";
				}
				else{
					$tarifList1=$tarifList1."'".$tarif."',";

				}
			}

		}
		$tarifList2="";
		$sparcsn4Query2="SELECT inv_unit.gkey,vsl_vessel_visit_details.vvd_gkey,inv_unit_fcy_visit.time_in    
		FROM inv_unit 
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv 
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey 
		WHERE ib_vyg='$rotation' AND category='EXPRT' AND inv_unit_fcy_visit.transit_state='S70_DEPARTED'";
		$sparcsn4QueryRes2=$this->bm->dataSelect($sparcsn4Query2);
		$tarifList2="";
		for($i=0;$i<count($sparcsn4QueryRes2);$i++){
				$gkey="";
				$vvd_gkey="";
				$time_in="";
				$gkey=$sparcsn4QueryRes2[$i]['GKEY'];
				$vvd_gkey=$sparcsn4QueryRes2[$i]['VVD_GKEY'];
				$time_in=$sparcsn4QueryRes2[$i]['TIME_IN'];
				$ctmsQuery2="SELECT *
				FROM(
				SELECT CONCAT('CCT1_',(SELECT  ctmsmis.get_mis_bill_tarrif('$gkey',155))) AS  tarrif, 
			   (SELECT ctmsmis.berth_for_cont('$vvd_gkey','$time_in')) AS berth 
			   )AS tmp WHERE berth  LIKE 'CCT%' OR berth LIKE 'NCT%'";
				$ctmsQueryRes2=$this->bm->dataSelectDb2($ctmsQuery2);
				if(count($ctmsQueryRes2)>0){
				$tarif="";
				$tarif=$ctmsQueryRes2[0]['tarrif'];
				if($i==(count($sparcsn4QueryRes2)-1)){
					$tarifList2=$tarifList2."'".$tarif."'";
				}
				else{
					$tarifList2=$tarifList2."'".$tarif."',";

				}

			}

		}

		$billingQuery="SELECT 
		(case 
		when bil_tariffs.id ='CCT1_QGC_LOADED_45' then 'G/C CHE EXP 40 L CTR'
		else bil_tariffs.description
		end) AS description,
		bil_tariffs.gl_code,amount AS rate,'NOS' AS bas
		from bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey 
		where bil_tariffs.id IN ($tarifList2)
		UNION
		SELECT 
		(case 
		when bil_tariffs.id ='CCT1_QGC_LOADED_45' then 'G/C CHE EXP 40 L CTR'
		else bil_tariffs.description
		end) AS description,
		bil_tariffs.gl_code,amount AS rate,'NOS' AS bas
		from bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey 
		where bil_tariffs.id IN ($tarifList2)";
	   $billingQueryRes=$this->bm->dataSelectDb3($billingQuery);

	   return $billingQueryRes;
	}

	function getShoreCraneSubDetailChargesQuery($rotation)
	{
		/*$getShoreCraneSubDetailChargesQuery = "SELECT id,description,gl_code,rate,bas,
		IFNULL(IF(id='JETTY_CRANE',jetty_crane_total_used,derrick_crane_total_used),0) AS unit
		FROM
		(
		 SELECT id,description,gl_code,rate,bas,jetty_crane_total_used,derrick_crane_total_used FROM 
		 (
		   SELECT billing.bil_tariffs.gkey,id,billing.bil_tariffs.description,billing.bil_tariffs.gl_code,amount AS rate,'NOS' AS bas
		   FROM billing.bil_tariff_rates
		   INNER JOIN billing.bil_tariffs ON billing.bil_tariffs.gkey=billing.bil_tariff_rates.tariff_gkey
		   WHERE amount IS NOT NULL AND id LIKE '%JETTY_CRANE%'
		 ) AS tarrif_data,
		 (
			SELECT ctmsmis.shore_crane_allocation.rotation,crane_type,
			SUM((no_of_3_ton_crane+no_of_5_ton_crane+no_of_10_crane)) AS jetty_crane_total_used
			FROM ctmsmis.shore_crane_allocation
			WHERE rotation='$rotation' AND crane_type='1'
		 ) AS shore_crane_usage_details,
		 (
			SELECT ctmsmis.shore_crane_allocation.rotation,crane_type,
			SUM((no_of_3_ton_crane+no_of_5_ton_crane+no_of_10_crane)) AS derrick_crane_total_used
			FROM ctmsmis.shore_crane_allocation
			WHERE rotation='$rotation' AND crane_type='2'
		 ) AS derrick_crane_usage_details
		) AS final_output";
		
		return $getShoreCraneSubDetailChargesQuery;*/

		$billingQuery="SELECT bil_tariffs.gkey,id,bil_tariffs.description,bil_tariffs.gl_code,amount AS rate,'NOS' AS bas
		FROM bil_tariff_rates
		INNER JOIN bil_tariffs ON bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
		WHERE amount IS NOT NULL AND id LIKE '%JETTY_CRANE%'";
	   $billingQueryRes=$this->bm->dataSelectDb3($billingQuery);
	   $ctmsQUery1="";
	   $shoreCraneRes="";
	   for($i=0;$i<count($billingQueryRes);$i++){
		$id=$billingQueryRes[$i]['ID'];

		$ctmsQuery1="SELECT ctmsmis.shore_crane_allocation.rotation,crane_type,
		SUM((no_of_3_ton_crane+no_of_5_ton_crane+no_of_10_crane)) AS jetty_crane_total_used
		FROM ctmsmis.shore_crane_allocation
		WHERE rotation='$rotation' AND crane_type='1'
	 ) AS shore_crane_usage_details";
		$ctmsQuery1Res=$this->bm->dataSelectDb2($ctmsQuery1);
		$jetty_crane_total_used=$ctmsQuery1Res[0]['jetty_crane_total_used'];

		$ctmsQuery2="SELECT ctmsmis.shore_crane_allocation.rotation,crane_type,
		SUM((no_of_3_ton_crane+no_of_5_ton_crane+no_of_10_crane)) AS derrick_crane_total_used
		FROM ctmsmis.shore_crane_allocation
		WHERE rotation='$rotation' AND crane_type='2'
	    ) AS derrick_crane_usage_details";
		$ctmsQuery2Res=$this->bm->dataSelectDb2($ctmsQuery2);
		$derrick_crane_usage_details=$ctmsQuery2Res[0]['derrick_crane_usage_details'];
		$unit="";
		if($id=='JETTY_CRANE'){
			$unit=$jetty_crane_total_used;

		}
		else{
			$unit=$derrick_crane_usage_details;
		}
		$shoreCraneRes[$i]['ID']=$billingQueryRes[$i]['ID'];
		$shoreCraneRes[$i]['DESCRIPTION']=$billingQueryRes[$i]['DESCRIPTION'];
		$shoreCraneRes[$i]['GL_CODE']=$billingQueryRes[$i]['GL_CODE'];
		$shoreCraneRes[$i]['RATE']=$billingQueryRes[$i]['RATE'];
		$shoreCraneRes[$i]['BAS']=$billingQueryRes[$i]['BAS'];
		$shoreCraneRes[$i]['UNIT']=$billingQueryRes[$i]['UNIT'];
		



	   }
	 
	}
	
	
	function getPilotChargesQueryPangoan($rotation)
    {
		$getPilotChargesQueryPangoan = "SELECT vsldtl.ib_vyg AS rotation,arvisit.out_call_number,vsl.name AS vsl_name,arcar.ata,arcar.atd,
		NVL(ref_agent.agent_alias_id,'') AS agent_alias_id,Y.id AS agent_code,Y.name AS agent_name,
		CONCAT(CONCAT(NVL(Y.address_line1,''),' '),NVL(Y.address_line2,'')) AS address,
		vsldtl.off_port_arr AS oa_date,
		'Bangladesh' AS flag,'PBD' AS cnt_code,classes.gross_registered_ton AS grt,CEIL(classes.loa_cm/100) AS loa_cm,vsl.ves_captain AS master_name,
		0 AS deck_cargo,
		1 AS exchangeRate,102 AS bill_type,
		(case 
		when to_char(vsldtl.flex_date01,'hh24:mi:ss') > '18:00:00' OR to_char(vsldtl.flex_date01,'hh24:mi:ss')<'05:59:59' then 1
		else 
		(case
		when to_char(vsldtl.flex_date02,'hh24:mi:ss') > '18:00:00' OR to_char(vsldtl.flex_date02,'hh24:mi:ss')<'05:59:59' then 1
		else 0
		end)
		end) as s1N,
		(case 
		when to_char(vsldtl.flex_date03,'hh24:mi:ss') > '18:00:00' OR to_char(vsldtl.flex_date03,'hh24:mi:ss')<'05:59:59' then 1
		else 
		(case
		when to_char(vsldtl.flex_date04,'hh24:mi:ss') > '18:00:00' OR to_char(vsldtl.flex_date04,'hh24:mi:ss')<'05:59:59' then 1
		else 0
		end)
		end) as s2N,
		
		vsldtl.flex_date01 AS pilot_ib_onboard,vsldtl.flex_date02 AS pilot_ib_offboard,vsldtl.flex_date03 AS pilot_ob_onboard,vsldtl.flex_date04 AS pilot_ob_offboard
		FROM vsl_vessel_visit_details vsldtl
		INNER JOIN argo_carrier_visit arcar ON arcar.cvcvd_gkey=vsldtl.vvd_gkey
		INNER JOIN argo_visit_details arvisit ON arvisit.gkey = arcar.cvcvd_gkey
		INNER JOIN vsl_vessels vsl ON vsl.gkey=vsldtl.vessel_gkey
		INNER JOIN vsl_vessel_classes classes ON vsl.vesclass_gkey=classes.gkey 
		INNER JOIN  ( ref_bizunit_scoped r  
		LEFT JOIN ( ref_agent_representation X  
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
		ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
		LEFT JOIN ref_country cntry ON vsl.country_code=cntry.cntry_code
		LEFT JOIN ref_agent ON ref_agent.agent_id=X.agent_gkey
		WHERE ib_vyg='$rotation' AND PHASE !='80CANCELED'";
		
		return $getPilotChargesQueryPangoan;
    }
	
	
	function getPilotChargesQuery($rotation)
    {
		
		
		// berth added for bill - new inner join
		$getPilotChargesQuery = "SELECT vsldtl.ib_vyg AS rotation,arvisit.out_call_number,vsl.name AS vsl_name,
		arcar.ata,arcar.atd,ref_agent.agent_alias_id,Y.id AS agent_code,Y.name AS agent_name,
		CONCAT(CONCAT(NVL(Y.address_line1,''),' '),NVL(Y.address_line2,'')) AS address,
		vsldtl.off_port_arr AS oa_date,
		cntry.cntry_name AS flag,cntry.cntry_code AS cnt_code,classes.gross_registered_ton AS grt,
		CEIL(classes.loa_cm/100) AS loa_cm,vsl.ves_captain AS master_name,
		0 AS deck_cargo,
		
		102 AS bill_type,
		(case 
		when to_char(vsldtl.flex_date01,'hh24:mi:ss') > '18:00:00' OR to_char(vsldtl.flex_date01,'hh24:mi:ss')<'05:59:59' then 1
		else 
		(case
		when to_char(vsldtl.flex_date02,'hh24:mi:ss') > '18:00:00' OR to_char(vsldtl.flex_date02,'hh24:mi:ss')<'05:59:59' then 1
		else 0
		end)
		end) as s1N,
		(case 
		when to_char(vsldtl.flex_date03,'hh24:mi:ss') > '18:00:00' OR to_char(vsldtl.flex_date03,'hh24:mi:ss')<'05:59:59' then 1
		else 
		(case
		when to_char(vsldtl.flex_date04,'hh24:mi:ss') > '18:00:00' OR to_char(vsldtl.flex_date04,'hh24:mi:ss')<'05:59:59' then 1
		else 0
		end)
		end) as s2N,
		vsldtl.flex_date01 AS pilot_ib_onboard,vsldtl.flex_date02 AS pilot_ib_offboard,vsldtl.flex_date03 AS pilot_ob_onboard,vsldtl.flex_date04 AS pilot_ob_offboard,quay.id AS berth
		FROM vsl_vessel_visit_details vsldtl
		INNER JOIN argo_carrier_visit arcar ON arcar.cvcvd_gkey=vsldtl.vvd_gkey
		INNER JOIN argo_visit_details arvisit ON arvisit.gkey = arcar.cvcvd_gkey
		INNER JOIN vsl_vessels vsl ON vsl.gkey=vsldtl.vessel_gkey
		INNER JOIN vsl_vessel_berthings vslbrth ON vslbrth.vvd_gkey=vsldtl.vvd_gkey
		INNER JOIN argo_quay quay ON quay.gkey=vslbrth.quay
		INNER JOIN vsl_vessel_classes classes ON vsl.vesclass_gkey=classes.gkey 
		INNER JOIN  ( ref_bizunit_scoped r  
		LEFT JOIN ( ref_agent_representation X  
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
		ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
		LEFT JOIN ref_country cntry ON vsl.country_code=cntry.cntry_code
		LEFT JOIN ref_agent ON ref_agent.agent_id=X.agent_gkey
		WHERE ib_vyg='$rotation' AND PHASE !='80CANCELED'";
		
		return $getPilotChargesQuery;
    }
	
	function getPilotSubDetailsQueryPangoanNewRate($berthTime,$grt,$deckCargo,$addPortDues)
    {
                       
		/*$getPilotSubDetailsQueryPangoanNewRate = "SELECT description,gl_code,rate,unit,bas,1 AS move ,rate_type 
		FROM
		(
		SELECT billing.bil_tariffs.description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.rate_type,
		ctmsmis.mis_vsl_bill_tarrif.pngn_new_rate AS rate,
		1 AS unit,
		IF(billing.bil_tariffs.description='PILOTAGE FEE' OR billing.bil_tariffs.description='Port Dues for Sea-going Vessel','GRT','NOS') AS bas
		FROM ctmsmis.mis_vsl_bill_tarrif 
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=ctmsmis.mis_vsl_bill_tarrif.id
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE berth_time='$berthTime' AND bill_type=102 AND billing.bil_tariff_rates.rate_type='REGULAR'
		UNION ALL
		SELECT billing.bil_tariffs.description AS description,
		billing.bil_tariffs.gl_code,billing.bil_tariff_rates.rate_type,
		ctmsmis.mis_vsl_bill_tarrif.pngn_new_rate AS rate,
		1 AS unit,
		IF(billing.bil_tariffs.description='PILOTAGE FEE' OR billing.bil_tariffs.description='Port Dues for Sea-going Vessel','GRT','NOS') AS bas
		FROM ctmsmis.mis_vsl_bill_tarrif 
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=ctmsmis.mis_vsl_bill_tarrif.id
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE berth_time='$berthTime' AND bill_type=102 AND billing.bil_tariff_rates.rate_type='BAND'
		) AS final WHERE rate_type IN ".$condition;*/
		$condition = $addPortDues ? "('REGULAR','BAND')" : "('BAND')";
		
	    $ctmsQuery="SELECT id FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=102 AND berth_time='$berthTime'";
		$billTypeIdRes=$this->bm->dataSelectDb2($ctmsQuery);
		$billTypeIdList="";
		for($i=0;$i<count($billTypeIdRes);$i++){
			$id="";
			$id=$billTypeIdRes[$i]['id'];

			if($i==(count($billTypeIdRes)-1)){
				$billTypeIdList=$billTypeIdList."'".$id."'";
			}
			else{
				$billTypeIdList=$billTypeIdList."'".$id."',";

			}

		}

		$getPilotSubDetailsQueryPangoanNewRate = "SELECT description,gl_code,unit,bas,1 AS move ,rate_type ,id
		FROM
		(
		SELECT bil_tariffs.id, bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		1 AS unit,
		(case
		when bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='Port Dues for Sea-going Vessel' then 'GRT'
		else 'NOS'
		end)  as bas
		From bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='REGULAR'  AND bil_tariffs.id IN ($billTypeIdList)
		
		UNION ALL
		
		SELECT bil_tariffs.id, bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		1 AS unit,
		(case
		when bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='Port Dues for Sea-going Vessel' then 'GRT'
		else 'NOS'
		end) as bas
		From bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='BAND'  AND bil_tariffs.id IN ($billTypeIdList)
		) final WHERE rate_type IN".$condition;

		$getPilotSubDetailsQueryPangoanNewRateRes= $this->bm->dataSelectDb3($getPilotSubDetailsQueryPangoanNewRate);
		
		return $getPilotSubDetailsQueryPangoanNewRate;


    }
	
	
	function getPilotSubDetailsQueryPangoan($berthTime, $grt, $deckCargo, $addPortDues)
    {
        $condition = $addPortDues ? "('REGULAR','BAND')" : "('BAND')";
                
	/*	$getPilotSubDetailsQueryPangoan = "SELECT description,gl_code,rate,unit,bas,1 AS move ,rate_type 
		FROM
		(
		SELECT billing.bil_tariffs.description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.rate_type,
		ctmsmis.mis_vsl_bill_tarrif.pangaon_rate AS rate,
		1 AS unit,
		IF(billing.bil_tariffs.description='PILOTAGE FEE' OR billing.bil_tariffs.description='Port Dues for Sea-going Vessel','GRT','NOS') AS bas
		FROM ctmsmis.mis_vsl_bill_tarrif 
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=ctmsmis.mis_vsl_bill_tarrif.id
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE berth_time='$berthTime' AND bill_type=102 AND billing.bil_tariff_rates.rate_type='REGULAR'
		UNION ALL
		SELECT billing.bil_tariffs.description AS description,
		billing.bil_tariffs.gl_code,billing.bil_tariff_rates.rate_type,
		ctmsmis.mis_vsl_bill_tarrif.pangaon_rate AS rate,
		1 AS unit,
		IF(billing.bil_tariffs.description='PILOTAGE FEE' OR billing.bil_tariffs.description='Port Dues for Sea-going Vessel','GRT','NOS') AS bas
		FROM ctmsmis.mis_vsl_bill_tarrif 
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=ctmsmis.mis_vsl_bill_tarrif.id
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE berth_time='$berthTime' AND bill_type=102 AND billing.bil_tariff_rates.rate_type='BAND'
		) AS final WHERE rate_type IN ".$condition;*/

		$condition = $addPortDues ? "('REGULAR','BAND')" : "('BAND')";

		$ctmsQuery="SELECT id FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=102 AND berth_time='$berthTime'";
		$billTypeIdRes=$this->bm->dataSelectDb2($ctmsQuery);
		$billTypeIdList="";
		for($i=0;$i<count($billTypeIdRes);$i++){
			$id="";
			$id=$billTypeIdRes[$i]['id'];

			if($i==(count($billTypeIdRes)-1)){
				$billTypeIdList=$billTypeIdList."'".$id."'";
			}
			else{
				$billTypeIdList=$billTypeIdList."'".$id."',";

			}

		}

		$getPilotSubDetailsQueryPangoan = "SELECT description,gl_code,unit,bas,1 AS move ,rate_type,id 
		FROM
		(
		SELECT bil_tariffs.id, bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		1 AS unit,
		(case
		when bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='Port Dues for Sea-going Vessel' then 'GRT'
		else 'NOS'
		end)  as bas
		From bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='REGULAR'  AND bil_tariffs.id IN ($billTypeIdList)

		UNION ALL

		SELECT bil_tariffs.id, bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		1 AS unit,
		(case
		when bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='Port Dues for Sea-going Vessel' then 'GRT'
		else 'NOS'
		end) as bas
		From bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='BAND'  AND bil_tariffs.id IN ($billTypeIdList)
		) final WHERE rate_type IN".$condition;
		$getPilotSubDetailsQueryPangoanRes= $this->bm->dataSelectDb3($getPilotSubDetailsQueryPangoan);

		return $getPilotSubDetailsQueryPangoanRes;

		
    }

	function getPilotSubDetailsQuery($berthTime, $grt, $deckCargo, $addPortDues, $rotation,$loa_cm)
    {
        
		
	/*	$getPilotSubDetailsQuery = "SELECT description,gl_code,rate,unit,bas,move,rate_type
		FROM
		(
		SELECT billing.bil_tariffs.description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.rate_type,
		billing.bil_tariff_rates.amount AS rate,
		IF(billing.bil_tariffs.description='PD SEA VESSEL',($grt+$deckCargo),1) AS unit,1 AS move,
		IF(billing.bil_tariffs.description='PILOTAGE FEE' OR billing.bil_tariffs.description='PD SEA VESSEL','GRT','NOS') AS bas
		FROM ctmsmis.mis_vsl_bill_tarrif
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=ctmsmis.mis_vsl_bill_tarrif.id
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE berth_time='$berthTime' AND bill_type=102 AND billing.bil_tariff_rates.rate_type='REGULAR'

		UNION ALL

		SELECT IF(billing.bil_tariffs.description LIKE 'tug%',IF($grt<1001,CONCAT(billing.bil_tariffs.description,' ',(SELECT description FROM billing.bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=billing.bil_tariff_rates.gkey)),
		IF($grt<5001,CONCAT(billing.bil_tariffs.description,' ',(SELECT description FROM billing.bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=billing.bil_tariff_rates.gkey)),CONCAT(billing.bil_tariffs.description,' ',(SELECT description FROM billing.bil_tariff_rate_tiers WHERE min_quantity=5001 AND rate_gkey=billing.bil_tariff_rates.gkey)))),billing.bil_tariffs.description) AS description,
		billing.bil_tariffs.gl_code,billing.bil_tariff_rates.rate_type,
		IF(billing.bil_tariffs.description IN('BERTHING','UNBERTHING'),
		(SELECT is_flat_rate FROM billing.bil_tariff_rate_tiers WHERE rate_gkey=billing.bil_tariff_rates.gkey LIMIT 1),
		IF(billing.bil_tariffs.description='PILOTAGE FEE','35.7500',
		IF(billing.bil_tariffs.description LIKE 'tug%',(IF($grt<1001,(SELECT amount FROM billing.bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=billing.bil_tariff_rates.gkey),IF($grt<5001,(SELECT amount FROM billing.bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=billing.bil_tariff_rates.gkey),(SELECT amount FROM billing.bil_tariff_rate_tiers WHERE min_quantity=5001 AND rate_gkey=billing.bil_tariff_rates.gkey)))),(SELECT amount FROM billing.bil_tariff_rate_tiers WHERE rate_gkey=billing.bil_tariff_rates.gkey LIMIT 1)))) AS rate,
		IF(billing.bil_tariffs.description LIKE 'tug%' AND $loa_cm>18600,IF(billing.bil_tariffs.description LIKE 'TUG CHG SFT%',1,1*2),IF(billing.bil_tariffs.description='PILOTAGE FEE',IF(CEIL(($grt+$deckCargo)/1000)<10,10,CEIL(($grt+$deckCargo)/1000)),1)) AS unit,
		IF((SELECT sparcsn4.vsl_vessel_visit_details.flex_string01
		FROM sparcsn4.vsl_vessel_visit_details
		WHERE vsl_vessel_visit_details.ib_vyg='$rotation' LIMIT 1)>0 AND billing.bil_tariffs.description='PILOTAGE FEE',((SELECT sparcsn4.vsl_vessel_visit_details.flex_string01 FROM sparcsn4.vsl_vessel_visit_details WHERE vsl_vessel_visit_details.ib_vyg='$rotation'
		LIMIT 1)/2)+1,1) AS move,
		IF(billing.bil_tariffs.description='PILOTAGE FEE' OR billing.bil_tariffs.description='PD SEA VESSEL','GRT','NOS') AS bas
		FROM ctmsmis.mis_vsl_bill_tarrif
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=ctmsmis.mis_vsl_bill_tarrif.id
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE berth_time='$berthTime' AND bill_type=102 AND billing.bil_tariff_rates.rate_type='BAND'
		) AS final WHERE rate_type IN ".$condition;*/
		
		$condition = $addPortDues ? "('REGULAR','BAND')" : "('BAND')";

		$flexStringQuery="SELECT vsl_vessel_visit_details.flex_string01 as rtnValue
		FROM vsl_vessel_visit_details
		WHERE vsl_vessel_visit_details.ib_vyg='$rotation' fetch first 1 rows only";
		$flexString = $this->bm->dataReturn($flexStringQuery);	

		$ctmsQuery="SELECT id FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=102 AND berth_time='$berthTime'";
		$billTypeIdRes=$this->bm->dataSelectDb2($ctmsQuery);
		$billTypeIdList="";
		for($i=0;$i<count($billTypeIdRes);$i++){
			$id="";
			$id=$billTypeIdRes[$i]['id'];

			if($i==(count($billTypeIdRes)-1)){
				$billTypeIdList=$billTypeIdList."'".$id."'";
			}
			else{
				$billTypeIdList=$billTypeIdList."'".$id."',";

			}

		}

		$getPilotSubDetailsQuery = "SELECT  description,gl_code,rate,unit,bas,move,rate_type,id
		FROM
		(
		select  bil_tariffs.id, bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		bil_tariff_rates.amount AS rate,
		
		(case when bil_tariffs.description='PD SEA VESSEL' then ($grt+$deckCargo)
		else 1
		end) AS unit,1 AS move,
		(case
		when bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='PD SEA VESSEL' then 'GRT'
		else 'NOS'
		end)  as bas
		From bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='REGULAR'  AND bil_tariffs.id IN ($billTypeIdList)
		
		UNION ALL
		
		SELECT  bil_tariffs.id, (case when bil_tariffs.description LIKE 'tug%' then
		(case
		when $grt<1001 then concat(concat(bil_tariffs.description,' '),
		
		
		(SELECT description FROM bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=bil_tariff_rates.gkey))
		else 
		(case
		when $grt<5001 then concat(concat(bil_tariffs.description,' '),
		
		
		(SELECT description FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey))
		else concat(concat(bil_tariffs.description,' '),
		
		
		(SELECT description FROM bil_tariff_rate_tiers 
		WHERE min_quantity=5001 AND rate_gkey=bil_tariff_rates.gkey))
		end)
		end)
		else bil_tariffs.description 
		end) AS description,
		
		
		bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		(case when bil_tariffs.description IN('BERTHING','UNBERTHING') then
		(SELECT is_flat_rate FROM bil_tariff_rate_tiers WHERE rate_gkey=bil_tariff_rates.gkey fetch first 1 rows only)
		else 
		(case when bil_tariffs.description='PILOTAGE FEE' then 35.7500
		else
		(case when bil_tariffs.description LIKE 'tug%' then 
		(case
		when $grt<1001 then (SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=bil_tariff_rates.gkey)
		else 
		(case when $grt<5001 then (SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey)
		else 
		(SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=5001 AND rate_gkey=bil_tariff_rates.gkey)
		end)
		end)
		else
		(SELECT amount FROM bil_tariff_rate_tiers WHERE rate_gkey=bil_tariff_rates.gkey fetch first 1 rows only)
		end)
		
		end)
		end) AS rate,
		(case when bil_tariffs.description LIKE 'tug%'  AND $loa_cm>18600 then
		(case when bil_tariffs.description LIKE 'TUG CHG SFT%' then 1 else 2  end)
		else (case when bil_tariffs.description='PILOTAGE FEE' then
		(case when CEIL(($grt+$deckCargo)/1000)<10 then 10 else CEIL(($grt+$deckCargo)/1000)   end)
		else  1
		end)
		end) as unit,
		
		(case when $flexString >0 AND bil_tariffs.description='PILOTAGE FEE' then ($flexString/2)+1 else 1 end) as move,
		(case when bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='PD SEA VESSEL' then 'GRT' else 'NOS' end) as bas
		From bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='BAND'  AND bil_tariffs.id IN ($billTypeIdList)
		) final WHERE rate_type IN".$condition;
		$getPilotSubDetailsQueryRes= $this->bm->dataSelectDb3($getPilotSubDetailsQuery);

		return $getPilotSubDetailsQueryRes;
    }
	
	
	

	
	function getPilotChargeBIWTAsubDetailQueryPan()
	{
		$getPilotChargeBIWTAsubDetailQueryPan = "SELECT id AS description,216 AS gl_code,pngn_new_rate AS rate,1 AS unit,'GRT' AS bas,1 AS move
											FROM ctmsmis.mis_vsl_bill_tarrif
											WHERE bill_type=105 AND berth_time=1";
		
		return $getPilotChargeBIWTAsubDetailQueryPan;
	}
	
	function getNightNavigationQuery($grt)
	{
		$getNightNavigationQuery = "SELECT DISTINCT billing.bil_tariffs.description,billing.bil_tariffs.description AS des,billing.bil_tariffs.gl_code,
		IF($grt<5001,(SELECT amount FROM billing.bil_tariff_rate_tiers WHERE min_quantity=1 AND rate_gkey=billing.bil_tariff_rates.gkey),IF($grt<10001,(SELECT amount FROM billing.bil_tariff_rate_tiers WHERE min_quantity=5001 AND rate_gkey=billing.bil_tariff_rates.gkey),(SELECT amount FROM billing.bil_tariff_rate_tiers WHERE min_quantity=10001 AND rate_gkey=billing.bil_tariff_rates.gkey))) AS rate,1 AS unit,'NOS' AS bas,1 AS move
		FROM ctmsmis.mis_vsl_bill_tarrif
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=ctmsmis.mis_vsl_bill_tarrif.id
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE bill_type=102 AND berth_time=0";

		$ctmsQuery="SELECT id FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=102 AND berth_time='0'";
		$billTypeIdRes=$this->bm->dataSelectDb2($ctmsQuery);
		$billTypeIdList="";
		for($i=0;$i<count($billTypeIdRes);$i++){
			$id="";
			$id=$billTypeIdRes[$i]['id'];

			if($i==(count($billTypeIdRes)-1)){
				$billTypeIdList=$billTypeIdList."'".$id."'";
			}
			else{
				$billTypeIdList=$billTypeIdList."'".$id."',";

			}

		}

		$getNightNavigationQuery = "SELECT DISTINCT bil_tariffs.description,bil_tariffs.description AS des,bil_tariffs.gl_code,
		(CASE
		WHEN $grt<5001 THEN (SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=1 AND rate_gkey=bil_tariff_rates.gkey)
		ELSE 
		(CASE 
		WHEN $grt<10001 THEN (SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=5001 AND rate_gkey=bil_tariff_rates.gkey)
		ELSE 
		(SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=10001 AND rate_gkey=bil_tariff_rates.gkey)
		END) 
		END) AS rate,
		1 AS unit,'NOS' AS bas,1 AS move
		FROM bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE  bil_tariffs.id IN ($billTypeIdList)";
		$getNightNavigationQueryRes= $this->bm->dataSelectDb3($getNightNavigationQuery);

		return $getNightNavigationQueryRes;
	}
	
	function getFirePumpQuery($rotation)
	{
		$getFirePumpQuery = "SELECT srv_event_types.id,
		(CASE srv_event_types.id  WHEN 'BERTHING' THEN 'NOS'
		WHEN 'WATER_BY_LINE' THEN 'TON'
		WHEN 'BERTH HIRE 1-13, 17, SLJ' THEN 'HRS'
		WHEN 'WTR CPA BRG' THEN 'TON'
		WHEN 'TUG_ADDITIONAL' THEN 'HRS'
		WHEN 'TUG CHG SFT VSL' THEN 'NOS'
		WHEN 'UNBERTHING' THEN 'NOS'
		WHEN 'TUG' THEN 'NOS'
		WHEN 'NGT_NAVI_VSL' THEN 'NOS'
		WHEN 'SHIFT/SWING DAY' THEN 'NOS'
		WHEN 'PD_SEA_VESSEL' THEN 'GRT'
		WHEN 'PD_SEA_NON_LIGHTER' THEN 'GRT'
		WHEN 'SWING MRNG/DAY/PART' THEN 'DIM'
		WHEN 'FXD MRNG/DAY/PART' THEN 'DIM'
		WHEN 'SHIFT VESSEL BERTH' THEN 'NOS'
		WHEN 'SHIFT VESSEL UNBERTH' THEN 'NOS'
		WHEN 'DIVING BOAT' THEN 'DIM'
		WHEN 'CPA PORTABLE FIRE PUMP' THEN 'HRS'
		WHEN 'HIRE OF RIGGERS UNIT' THEN 'DIM'
		WHEN 'HIRE OF SALVAGE DIV UNIT' THEN 'DIM'
		WHEN 'FIREMAN' THEN 'HRS'
		WHEN 'SHIFT/SWING NIGHT' THEN 'NOS'
		WHEN 'CPA FIRE ENGINE' THEN 'HRS'
		WHEN 'FIRE OFFICER' THEN 'HRS'
		WHEN 'BERTHING' THEN 'NOS'
		WHEN 'PILOTAGE' THEN 'GRT'
		WHEN 'BLV FOR SALVAGE WORK IN/OUT' THEN 'DIM'
		WHEN 'BERTH_HIRE_1-13_17_SLJ' THEN 'HRS'
		WHEN 'TUG_DEAD_VESSEL' THEN 'HRS'
		WHEN 'TUG_CANCEL' THEN 'NOS'
		WHEN 'WORK_OUTSIDE_PORT_LIMIT' THEN 'HRS'
		WHEN 'CPA WATER FOAM TENDER' THEN 'HRS'
		WHEN 'TUG FOR FIRE FIGHTING' THEN 'HRS'
		WHEN 'CPA TRAILER PUMP' THEN 'HRS'
		ELSE NULL END) AS bas,srv_event.quantity AS unit
		FROM srv_event
		INNER JOIN srv_event_types ON  srv_event_types.gkey=srv_event.event_type_gkey
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=srv_event.applied_to_gkey
		WHERE vsl_vessel_visit_details.ib_vyg='$rotation' AND applied_to_class='VV' AND srv_event_types.gkey IN(213,211,223,219)";
		
		return $getFirePumpQuery;
	}	


	function getPilotChargesQueryBhatiari($rotation)
	{
		$getPilotChargesQueryBhatiari = "SELECT vsl_vessel_visit_details.vvd_gkey,
		vsl_vessels.name AS vsl_name,
		vsl_vessel_visit_details.ib_vyg AS rotation,
		vsl_vessel_visit_details.off_port_arr AS oa_date,
		ata,atd,
		to_char(argo_carrier_visit.ata,'yyyy-mm-dd') AS date_of_arrival,
		to_char(argo_carrier_visit.ata,'hh24:mi:ss') AS time_of_arrival,
		to_char(argo_carrier_visit.atd,'yyyy-mm-dd') AS date_of_departure,
		to_char(argo_carrier_visit.atd,'hh24:mi:ss') AS time_of_departure,
		vsl_vessel_classes.gross_registered_ton AS grt,
		vsl_vessel_classes.net_registered_ton AS nrt,
		ref_country.cntry_name AS flag,
		ref_country.cntry_code AS cnt_code,
		Y.id AS agent_code,
		Y.name AS agent_name,
		Y.address_line1 AS agent_address,
		NVL(ref_agent.agent_alias_id,'') AS agent_alias_id,
		'0' AS deck_cargo,
		'107' AS bill_type,
		loa_cm
		FROM vsl_vessel_visit_details
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		INNER JOIN vsl_vessel_classes ON vsl_vessels.vesclass_gkey=vsl_vessel_classes.gkey
		
		INNER JOIN ref_bizunit_scoped r ON r.gkey = vsl_vessel_visit_details.bizu_gkey
		LEFT JOIN ref_agent_representation X ON r.gkey=X.bzu_gkey
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey
		
		LEFT JOIN ref_country ON vsl_vessels.country_code=ref_country.cntry_code
		LEFT JOIN ref_agent ON ref_agent.agent_id=X.agent_gkey
		WHERE vsl_vessel_visit_details.ib_vyg='$rotation'";
		
		return $getPilotChargesQueryBhatiari;
	}
	
	
	function getPilotSubDetailsQueryBhatiari($grt,$deck_cargo,$loa_cm,$rotNo)
	{
		$flexStringQuery="SELECT vsl_vessel_visit_details.flex_string01 as rtnValue
		FROM vsl_vessel_visit_details
		WHERE vsl_vessel_visit_details.ib_vyg='$rotNo' fetch first 1 rows only";
		$flexString = $this->bm->dataReturn($flexStringQuery);

		$ctmsQuery="SELECT id FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=108";
		$billTypeIdRes=$this->bm->dataSelectDb2($ctmsQuery);

		$billTypeIdList="";
		for($i=0;$i<count($billTypeIdRes);$i++){
			$id="";
			$id=$billTypeIdRes[$i]['id'];

			if($i==(count($billTypeIdRes)-1)){
				$billTypeIdList=$billTypeIdList."'".$id."'";
			}
			else{
				$billTypeIdList=$billTypeIdList."'".$id."',";

			}

		}

		$getPilotSubDetailsQueryBhatiari = "SELECT bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		bil_tariff_rates.amount AS rate,
		(CASE WHEN bil_tariffs.description='PD SEA VESSEL' then 2 else 1 END)  AS unit,
		1 AS move,
		(CASE WHEN bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='PD SEA VESSEL' then 'GRT' else 'NOS'   END)  AS bas
		From bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE  bil_tariff_rates.rate_type='REGULAR' and bil_tariffs.id IN ($billTypeIdList)

		UNION ALL

		SELECT 
		(case when bil_tariffs.description LIKE 'tug%' then
		(case
		when $grt<1001 then concat(concat(bil_tariffs.description,' '),
		(SELECT description FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey))
		else 
		(case
		when $grt<5001 then concat(concat(bil_tariffs.description,' '),
		(SELECT description FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey))
		else concat(concat(bil_tariffs.description,' '),
		(SELECT description FROM bil_tariff_rate_tiers 
		WHERE min_quantity=5001 AND rate_gkey=bil_tariff_rates.gkey))
		end)
		end)
		else bil_tariffs.description 
		end) AS description,
		bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		(case when bil_tariffs.description IN('BERTHING','UNBERTHING') then
		(SELECT is_flat_rate FROM bil_tariff_rate_tiers WHERE rate_gkey=bil_tariff_rates.gkey fetch first 1 rows only)
		else 
		(case when bil_tariffs.description='PILOTAGE FEE' then 35.7500
		else
		(case when bil_tariffs.description LIKE 'tug%' then 
		(case
		when $grt<1001 then (SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=bil_tariff_rates.gkey)
		else 
		(case when  $grt<5001 then (SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey)
		else 
		(SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=5001 AND rate_gkey=bil_tariff_rates.gkey)
		end)
		end)
		else
		(SELECT amount FROM bil_tariff_rate_tiers WHERE rate_gkey=bil_tariff_rates.gkey fetch first 1 rows only)
		end)
		
		end)
		end) AS rate,
		
		(case when bil_tariffs.description LIKE 'tug%'  AND $loa_cm>186 then
		(case when bil_tariffs.description LIKE 'TUG CHG SFT%' then 1 else 2  end)
		else (case when bil_tariffs.description='PILOTAGE FEE' then
		(case when CEIL(($grt+$deck_cargo)/1000)<10 then 10 else CEIL(($grt+$deck_cargo)/1000)   end)
		else  1
		end)
		end) as unit,
		(case when $flexString >0 AND bil_tariffs.description='PILOTAGE FEE' then ($flexString/2)+1 else 1 end) as move,
		(case when bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='PD SEA VESSEL' then 'GRT' else 'NOS' end) as bas
		From bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='BAND'  and bil_tariffs.id IN ($billTypeIdList)";

		$getPilotSubDetailsQueryBhatiariRes= $this->bm->dataSelectDb3($getPilotSubDetailsQueryBhatiari);	


		
		return $getPilotSubDetailsQueryBhatiariRes;
	}
	
	
	function getPilotChargesQueryKutubdia($rotation)
	{
		$getPilotChargesQueryKutubdia = "SELECT vsl_vessel_visit_details.vvd_gkey,
		vsl_vessels.name AS vsl_name,
		vsl_vessel_visit_details.ib_vyg AS rotation,
		vsl_vessel_visit_details.off_port_arr AS oa_date,
		ata,atd,
		to_char(argo_carrier_visit.ata,'yyyy-mm-dd') AS date_of_arrival,
		to_char(argo_carrier_visit.ata,'hh24:mi:ss') AS time_of_arrival,
		to_char(argo_carrier_visit.atd,'yyyy-mm-dd') AS date_of_departure,
		to_char(argo_carrier_visit.atd,'hh24:mi:ss') AS time_of_departure,
		vsl_vessel_classes.gross_registered_ton AS grt,
		vsl_vessel_classes.net_registered_ton AS nrt,
		ref_country.cntry_name AS flag,
		ref_country.cntry_code AS cnt_code,
		Y.id AS agent_code,
		Y.name AS agent_name,
		Y.address_line1 AS agent_address,
		NVL(ref_agent.agent_alias_id,'') AS agent_alias_id,
		'0' AS deck_cargo,
		'108' AS bill_type,
		loa_cm
		FROM vsl_vessel_visit_details
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		INNER JOIN vsl_vessel_classes ON vsl_vessels.vesclass_gkey=vsl_vessel_classes.gkey

		INNER JOIN ref_bizunit_scoped r ON r.gkey = vsl_vessel_visit_details.bizu_gkey
		LEFT JOIN ref_agent_representation X ON r.gkey=X.bzu_gkey
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey

		LEFT JOIN ref_country ON vsl_vessels.country_code=ref_country.cntry_code
		LEFT JOIN ref_agent ON ref_agent.agent_id=X.agent_gkey
		WHERE vsl_vessel_visit_details.ib_vyg='$rotation'";
		
		return $getPilotChargesQueryKutubdia;
	}
	
	function getPilotSubDetailsQueryKutubdia($grt,$deck_cargo,$loa_cm,$rotNo)
	{
		$flexStringQuery="SELECT vsl_vessel_visit_details.flex_string01 as rtnValue
		FROM vsl_vessel_visit_details
		WHERE vsl_vessel_visit_details.ib_vyg='$rotNo' fetch first 1 rows only";
		$flexString = $this->bm->dataReturn($flexStringQuery);	

		$ctmsQuery="SELECT id  FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=108";
		$billTypeIdRes=$this->bm->dataSelectDb2($ctmsQuery);

		$billTypeIdList="";
		for($i=0;$i<count($billTypeIdRes);$i++){
			$id="";
			$id=$billTypeIdRes[$i]['id'];

			if($i==(count($billTypeIdRes)-1)){
				$billTypeIdList=$billTypeIdList."'".$id."'";
			}
			else{
				$billTypeIdList=$billTypeIdList."'".$id."',";

			}

		}
		$getPilotSubDetailsQueryKutubdia="SELECT bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		bil_tariff_rates.amount AS rate,
		(CASE WHEN bil_tariffs.description='PD SEA VESSEL' THEN (20+1) ELSE 1 END) AS unit,
		1 AS move,
		(CASE WHEN bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='PD SEA VESSEL'
		THEN 'GRT' ELSE 'NOS' END) AS bas
		FROM bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='REGULAR' and bil_tariffs.id IN ($billTypeIdList)
		
		UNION ALL
		
		SELECT (case when bil_tariffs.description LIKE 'tug%' then
		(case
		when $grt<1001 then concat(concat(bil_tariffs.description,' '),


		(SELECT description FROM bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=bil_tariff_rates.gkey))
		else 
		(case
		when $grt<5001 then concat(concat(bil_tariffs.description,' '),


		(SELECT description FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey))
		else concat(concat(bil_tariffs.description,' '),
		
		
		(SELECT description FROM bil_tariff_rate_tiers 
		WHERE min_quantity=5001 AND rate_gkey=bil_tariff_rates.gkey))
		end)
		end)
		else bil_tariffs.description 
		end) AS description,


		bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		(case when bil_tariffs.description IN('BERTHING','UNBERTHING') then
		(SELECT is_flat_rate FROM bil_tariff_rate_tiers WHERE rate_gkey=bil_tariff_rates.gkey fetch first 1 rows only)
		else 
		(case when bil_tariffs.description='PILOTAGE FEE' then 35.7500
		else
		(case when bil_tariffs.description LIKE 'tug%' then 
		(case
		when $grt<1001 then (SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=bil_tariff_rates.gkey)
		else 
		(case when  $grt<5001 then (SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey)
		else 
		(SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=5001 AND rate_gkey=bil_tariff_rates.gkey)
		end)
		end)
		else
		(SELECT amount FROM bil_tariff_rate_tiers WHERE rate_gkey=bil_tariff_rates.gkey fetch first 1 rows only)
		end)
		
		end)
		end) AS rate,
		(case when bil_tariffs.description LIKE 'tug%'  AND $loa_cm>18600 then
		(case when bil_tariffs.description LIKE 'TUG CHG SFT%' then 1 else 2  end)
		else (case when bil_tariffs.description='PILOTAGE FEE' then
		(case when CEIL(($grt+$deck_cargo)/1000)<10 then 10 else CEIL(($grt+$deck_cargo)/1000)   end)
		else  1
		end)
		end) as unit,
		
		(case when $flexString >0 AND bil_tariffs.description='PILOTAGE FEE' then ($flexString/2)+1 else 1 end) as move,
		(case when bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='PD SEA VESSEL' then 'GRT' else 'NOS' end) as bas
		FROM bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='BAND' and bil_tariffs.id IN ($billTypeIdList)";
		$getPilotSubDetailsQueryBhatiariRes= $this->bm->dataSelectDb3($getPilotSubDetailsQueryKutubdia);

		
		return $getPilotSubDetailsQueryBhatiariRes;
	}
	
	
	/* function waterSupplySubDetailQuery($rotation)
    {
		$waterSupplySubDetailQuery="SELECT DISTINCT billing.bil_tariffs.description,billing.bil_tariffs.gl_code,150 AS rate,
		(SELECT CASE sparcsn4.srv_event_types.id  WHEN 'BERTHING' THEN 'NOS'
		WHEN 'WATER_BY_LINE' THEN 'MT'
		WHEN 'BERTH HIRE 1-13, 17, SLJ' THEN 'HRS'
		WHEN 'WTR CPA BRG' THEN 'TON'
		WHEN 'TUG_ADDITIONAL' THEN 'HRS'
		WHEN 'TUG CHG SFT VSL' THEN 'NOS'
		WHEN 'UNBERTHING' THEN 'NOS'
		WHEN 'TUG' THEN 'NOS'
		WHEN 'NGT_NAVI_VSL' THEN 'NOS'
		WHEN 'SHIFT/SWING DAY' THEN 'NOS'
		WHEN 'PD_SEA_VESSEL' THEN 'GRT'
		WHEN 'PD_SEA_NON_LIGHTER' THEN 'GRT'
		WHEN 'SWING MRNG/DAY/PART' THEN 'DIM'
		WHEN 'FXD MRNG/DAY/PART' THEN 'DIM'
		WHEN 'SHIFT VESSEL BERTH' THEN 'NOS'
		WHEN 'SHIFT VESSEL UNBERTH' THEN 'NOS'
		WHEN 'DIVING BOAT' THEN 'DIM'
		WHEN 'CPA PORTABLE FIRE PUMP' THEN 'HRS'
		WHEN 'HIRE OF RIGGERS UNIT' THEN 'DIM'
		WHEN 'HIRE OF SALVAGE DIV UNIT' THEN 'DIM'
		WHEN 'FIREMAN' THEN 'HRS'
		WHEN 'SHIFT/SWING NIGHT' THEN 'NOS'
		WHEN 'CPA FIRE ENGINE' THEN 'HRS'
		WHEN 'FIRE OFFICER' THEN 'HRS'
		WHEN 'BERTHING' THEN 'NOS'
		WHEN 'PILOTAGE' THEN 'GRT'
		WHEN 'BLV FOR SALVAGE WORK IN/OUT' THEN 'DIM'
		WHEN 'BERTH_HIRE_1-13_17_SLJ' THEN 'HRS'
		WHEN 'TUG_DEAD_VESSEL' THEN 'HRS'
		WHEN 'TUG_CANCEL' THEN 'NOS'
		WHEN 'WORK_OUTSIDE_PORT_LIMIT' THEN 'HRS'
		WHEN 'CPA WATER FOAM TENDER' THEN 'HRS'
		WHEN 'TUG FOR FIRE FIGHTING' THEN 'HRS'
		WHEN 'CPA TRAILER PUMP' THEN 'HRS'
		ELSE NULL END) AS bas,sparcsn4.srv_event.quantity AS unit
		FROM sparcsn4.srv_event
		INNER JOIN sparcsn4.srv_event_types ON  srv_event_types.gkey=srv_event.event_type_gkey
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.srv_event.applied_to_gkey
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=sparcsn4.srv_event_types.id
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotation' AND applied_to_class='VV' AND srv_event_types.gkey IN(169)
		AND billing.bil_tariff_rates.rate_type='REGULAR'";
		
		return $waterSupplySubDetailQuery;
    } */
	
	function getWaterSupplyQuery($rotation)
    {
		
	
		$getWaterSupplyQuery = "
		
		SELECT vsldtl.ib_vyg AS rotation,arcar_visit.phase,vsl.name AS vsl_name,vslbrth.ata,vslbrth.atd,quay.id AS berth,ref_agent.agent_alias_id,Y.id AS agent_code,Y.name AS agent_name,
		CONCAT(CONCAT(NVL(Y.address_line1,''),' '),NVL(Y.address_line2,'')) AS address,vsldtl.flex_date07,
		CEIL(extract(SECOND from vslbrth.atd-vslbrth.ata )/3600) as unit,
		vsldtl.off_port_arr AS oa_date,
		(Case

		when to_char(vslbrth.ata,'yyyy-mm-dd') >= '2016-10-10' then 1
		else
		0
		End) as rtdt,

		cntry.cntry_code AS cnt_code,cntry.cntry_name AS flag,classes.gross_registered_ton AS grt,vsl.ves_captain AS master_name,
		0 AS deck_cargo,
		101 AS bill_type,vsldtl.vvd_gkey,
		vsldtl.flex_string04 AS water_supply_dt ,ib_vyg
		FROM vsl_vessel_visit_details vsldtl
		INNER JOIN argo_carrier_visit arcar_visit ON arcar_visit.cvcvd_gkey = vsldtl.vvd_gkey
		INNER JOIN vsl_vessels vsl ON vsl.gkey=vsldtl.vessel_gkey 
		INNER JOIN vsl_vessel_berthings vslbrth ON vslbrth.vvd_gkey=vsldtl.vvd_gkey
		INNER JOIN argo_quay quay ON quay.gkey=vslbrth.quay
		INNER JOIN vsl_vessel_classes classes ON vsl.vesclass_gkey=classes.gkey 
		INNER JOIN  ( ref_bizunit_scoped r  
		LEFT JOIN ( ref_agent_representation X  
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
		ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
		LEFT JOIN ref_country cntry ON vsl.country_code=cntry.cntry_code
		LEFT JOIN ref_agent ON ref_agent.agent_id=X.agent_gkey
		WHERE ib_vyg='$rotation' AND arcar_visit.phase != '80CANCELED'";



		return $getWaterSupplyQuery;
    }

	function getFireJettyInfoQuery($rotation)
	{	
		$getFireJettyInfoQuery = "SELECT vsldtl.ib_vyg AS rotation,arcar_visit.phase,arcar_visit.ata, arcar_visit.atd, to_char(arcar_visit.ata,'yyyy-mm-dd') AS argoAta,
		vsl.name AS vsl_name,ref_agent.agent_alias_id,Y.id AS agent_code,Y.name AS agent_name,
		CONCAT(CONCAT(NVL(Y.address_line1,''),' '),NVL(Y.address_line2,'')) AS address,
		vsldtl.off_port_arr AS oa_date,
		cntry.cntry_code AS cnt_code,cntry.cntry_name AS flag,classes.gross_registered_ton AS grt,vsl.ves_captain AS master_name,
		0 AS deck_cargo,
		
		104 AS bill_type,vsldtl.vvd_gkey,
		vsldtl.flex_date01 AS pilot_ib_onboard,vsldtl.flex_date02 AS pilot_ib_offboard,vsldtl.flex_date03 AS pilot_ob_onboard,vsldtl.flex_date04 AS pilot_ob_offboard
		FROM vsl_vessel_visit_details vsldtl
		INNER JOIN argo_carrier_visit arcar_visit ON arcar_visit.cvcvd_gkey = vsldtl.vvd_gkey
		INNER JOIN vsl_vessels vsl ON vsl.gkey=vsldtl.vessel_gkey 
		INNER JOIN vsl_vessel_classes classes ON vsl.vesclass_gkey=classes.gkey 
		INNER JOIN  ( ref_bizunit_scoped r  
		LEFT JOIN ( ref_agent_representation X  
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
		ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
		LEFT JOIN ref_country cntry ON vsl.country_code=cntry.cntry_code
		LEFT JOIN ref_agent ON ref_agent.agent_id=X.agent_gkey
		INNER JOIN srv_event ON srv_event.applied_to_gkey=vsldtl.vvd_gkey
		WHERE ib_vyg='$rotation' AND arcar_visit.phase != '80CANCELED'
		AND srv_event.event_type_gkey = 213";
		
		return $getFireJettyInfoQuery;
	}
	
	function getPilotChargesQueryBeaching($rotation)
	{
		$getPilotChargesQueryBeaching = "SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name AS vsl_name,
		vsl_vessel_visit_details.ib_vyg AS rotation,to_char(vsl_vessel_visit_details.off_port_arr,'yyyy-mm-dd') as oa_date,to_char(ata,'yyyy-mm-dd') as ata,to_char(atd,'yyyy-mm-dd') as atd,
		to_char(argo_carrier_visit.ata,'yyyy-mm-dd') AS date_of_arrival,
		to_char(argo_carrier_visit.ata,'hh24:mi:ss') AS time_of_arrival,
		to_char(argo_carrier_visit.atd,'yyyy-mm-dd') AS date_of_departure,
		to_char(argo_carrier_visit.atd,'hh24:mi:ss') AS time_of_departure,
		vsl_vessel_classes.gross_registered_ton AS grt,
		vsl_vessel_classes.net_registered_ton AS nrt,
		ref_country.cntry_name AS flag,
		ref_country.cntry_code AS cnt_code,
		Y.id AS agent_code,
		Y.name AS agent_name,
		Y.address_line1 AS agent_address,
		NVL(ref_agent.agent_alias_id,'') AS agent_alias_id,
		'0' AS deck_cargo,
		'109' AS bill_type,
		loa_cm
		FROM vsl_vessel_visit_details
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		INNER JOIN vsl_vessel_classes ON vsl_vessels.vesclass_gkey=vsl_vessel_classes.gkey
		INNER JOIN ref_bizunit_scoped r ON r.gkey = vsl_vessel_visit_details.bizu_gkey
		LEFT JOIN ref_agent_representation X ON r.gkey=X.bzu_gkey
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey
		LEFT JOIN ref_country ON vsl_vessels.country_code=ref_country.cntry_code
		LEFT JOIN ref_agent ON ref_agent.agent_id=X.agent_gkey
		WHERE vsl_vessel_visit_details.ib_vyg='$rotation'";

		return $getPilotChargesQueryBeaching;
	}


	function getPilotSubDetailsQueryBeaching($grt,$deck_cargo,$loa_cm,$rotNo)
	{		
		$flexStringQuery="SELECT vsl_vessel_visit_details.flex_string01 as rtnValue
		FROM vsl_vessel_visit_details
		WHERE vsl_vessel_visit_details.ib_vyg='$rotNo' fetch first 1 rows only";
		$flexString = $this->bm->dataReturn($flexStringQuery);
		if($flexString==null || $flexString=="")
		{
			$flexString=0;
		}

		$ctmsQuery="SELECT id FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=109";
		$billTypeIdRes=$this->bm->dataSelectDb2($ctmsQuery);

		$billTypeIdList="";
		for($i=0;$i<count($billTypeIdRes);$i++){
			$id="";
			$id=$billTypeIdRes[$i]['id'];

			if($i==(count($billTypeIdRes)-1)){
				$billTypeIdList=$billTypeIdList."'".$id."'";
			}
			else{
				$billTypeIdList=$billTypeIdList."'".$id."',";

			}

		}

		$getPilotSubDetailsQueryBeaching = "SELECT bil_tariffs.description,bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		bil_tariff_rates.amount AS rate,
		(CASE WHEN bil_tariffs.description='PD SEA VESSEL' then 2 else 1 END)  AS unit,
		1 AS move,
		(CASE WHEN bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='PD SEA VESSEL' then 'GRT' else 'NOS'   END)  AS bas
		From bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE  bil_tariff_rates.rate_type='REGULAR' and bil_tariffs.id IN ($billTypeIdList)

		UNION ALL

		SELECT 
		(case when bil_tariffs.description LIKE 'tug%' then
		(case
		when $grt<1001 then concat(concat(bil_tariffs.description,' '),
		(SELECT description FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey))
		else 
		(case
		when $grt<5001 then concat(concat(bil_tariffs.description,' '),
		(SELECT description FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey))
		else concat(concat(bil_tariffs.description,' '),
		(SELECT description FROM bil_tariff_rate_tiers 
		WHERE min_quantity=5001 AND rate_gkey=bil_tariff_rates.gkey))
		end)
		end)
		else bil_tariffs.description 
		end) AS description,
		bil_tariffs.gl_code,bil_tariff_rates.rate_type,
		(case when bil_tariffs.description IN('BERTHING','UNBERTHING') then
		(SELECT is_flat_rate FROM bil_tariff_rate_tiers WHERE rate_gkey=bil_tariff_rates.gkey fetch first 1 rows only)
		else 
		(case when bil_tariffs.description='PILOTAGE FEE' then 35.7500
		else
		(case when bil_tariffs.description LIKE 'tug%' then 
		(case
		when $grt<1001 then (SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=bil_tariff_rates.gkey)
		else 
		(case when  $grt<5001 then (SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=bil_tariff_rates.gkey)
		else 
		(SELECT amount FROM bil_tariff_rate_tiers WHERE min_quantity=5001 AND rate_gkey=bil_tariff_rates.gkey)
		end)
		end)
		else
		(SELECT amount FROM bil_tariff_rate_tiers WHERE rate_gkey=bil_tariff_rates.gkey fetch first 1 rows only)
		end)
		
		end)
		end) AS rate,
		
		(case when bil_tariffs.description LIKE 'tug%'  AND $loa_cm>186 then
		(case when bil_tariffs.description LIKE 'TUG CHG SFT%' then 1 else 2  end)
		else (case when bil_tariffs.description='PILOTAGE FEE' then
		(case when CEIL(($grt+$deck_cargo)/1000)<10 then 10 else CEIL(($grt+$deck_cargo)/1000)   end)
		else  1
		end)
		end) as unit,
		(case when $flexString >0 AND bil_tariffs.description='PILOTAGE FEE' then ($flexString/2)+1 else 1 end) as move,
		(case when bil_tariffs.description='PILOTAGE FEE' OR bil_tariffs.description='PD SEA VESSEL' then 'GRT' else 'NOS' end) as bas
		From bil_tariffs
		INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
		WHERE bil_tariff_rates.rate_type='BAND'  and bil_tariffs.id IN ($billTypeIdList)";
		//$getPilotSubDetailsQueryBeaching= $this->bm->dataSelectDb3($getPilotSubDetailsQueryBeaching);	

		return $getPilotSubDetailsQueryBeaching;
	}
	
	
}
?>