<?php
class VesselBill extends CI_Controller {
	function __construct()
	{
	    parent::__construct();	
		$this->load->library(array('session', 'form_validation'));
		$this->load->model(array('CI_auth', 'CI_menu'));
		$this->load->helper(array('html','form', 'url'));
		//$this->load->driver('cache');
		$this->load->helper('file');
		$this->load->model('ci_auth', 'bm', TRUE);
		$this->load->model('Vessel_Bill_Queries', 'vbq', TRUE);
		$this->load->library("pagination");
	}
	
	function logout()
	{
	
		$query="SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,sparcsn4.vsl_vessels.name,sparcsn4.vsl_vessel_visit_details.ib_vyg,sparcsn4.vsl_vessel_visit_details.ob_vyg,
		LEFT(sparcsn4.argo_carrier_visit.phase,2) AS phase_num,SUBSTR(sparcsn4.argo_carrier_visit.phase,3) AS phase_str,sparcsn4.argo_visit_details.eta,
		sparcsn4.argo_visit_details.etd,sparcsn4.argo_carrier_visit.ata,
		sparcsn4.argo_carrier_visit.atd,sparcsn4.ref_bizunit_scoped.id AS agent,sparcsn4.argo_quay.id AS berth,
		IFNULL(sparcsn4.vsl_vessel_visit_details.flex_string02,sparcsn4.vsl_vessel_visit_details.flex_string03) AS berthop
		FROM sparcsn4.argo_carrier_visit
		INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
		INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
		INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
		LEFT JOIN sparcsn4.vsl_vessel_berthings ON sparcsn4.vsl_vessel_berthings.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
		LEFT JOIN sparcsn4.argo_quay ON sparcsn4.argo_quay.gkey=sparcsn4.vsl_vessel_berthings.quay
		WHERE sparcsn4.argo_carrier_visit.phase IN ('20INBOUND','30ARRIVED','40WORKING','50COMPLETE','60DEPARTED')
		ORDER BY sparcsn4.argo_carrier_visit.phase";
		//echo $data['voysNo'];
		$rtnVesselList = $this->bm->dataSelect($query);
		$data['rtnVesselList']=$rtnVesselList;
		
		$data['body']="<font color='blue' size=2>LogOut Successfully....</font>";

		$this->session->sess_destroy();
		$this->cache->clean();
		//redirect(base_url(),$data);
		// $this->load->view('header');
		// $this->load->view('welcomeview_1', $data);
		// $this->load->view('footer');

		$this->load->view('cssVesselList');
		$this->load->view('jsVesselList');
		$this->load->view('FrontEnd/header');
		$this->load->view('FrontEnd/slider');
		$this->load->view('FrontEnd/index',$data);
		$this->load->view('FrontEnd/footer');

		$this->db->cache_delete_all();
	}
	
	function generateVesselsBillNotEntering()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			$login_id = $this->session->userdata('login_id');
			$org_Type_id =$this->session->userdata('org_Type_id');
			$msg = "";
		
			$rotation = $this->input->post('rot');
			
			if($rotation == null or $rotation == "")
			{
				$rotation = $this->uri->segment(3);
				$rotation = str_replace("_","/",$rotation);
			}
			
			// Check bill first 
			$sql_chkBill = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.mis_vsl_billing_detail_test WHERE rotation='$rotation'";
			// echo $sql_chkBill;return;
			$chkBill = $this->bm->dataReturn($sql_chkBill);
			
			if($chkBill == 0)
			{			
				// New Pilotage Bill					
				$rtnData = $this->generatePilotageBill($rotation);
				// echo "hello 2";return;
				if($rtnData == "BillGenerated")
				{
					$sql_vviId = "SELECT id AS rtnValue FROM outer_vsl_visit_info WHERE imp_rot='$rotation'";
					$vviId = $this->bm->dataReturnDb1($sql_vviId);
										
					/* $sql_updateBillStat = "UPDATE outer_vsl_forward_info
					SET acnt_bill_stat='1',acnt_bill_by='$login_id',acnt_bill_at=NOW(),acnt_bill_ip='$ipAddress'
					WHERE vsl_visit_id='$vviId'"; */
					$sql_updateBillStat = "UPDATE outer_vsl_forward_info
					SET billop_bill_stat='1',billop_bill_by='$login_id',billop_bill_at=NOW(),billop_bill_ip='$ipAddress'
					WHERE vsl_visit_id='$vviId'";
					$this->bm->dataUpdateDb1($sql_updateBillStat);
					// echo "<font color='green'>Bill generated for ".$rotation."</font>";
					$msg = "<font color='green'>Bill generated for ".$rotation."</font>";
					// return;
				}
				else
				{
					// echo "<font color='red'>Bill generation failed ".$rotation."</font>";
					$msg = "<font color='red'>Bill generation failed ".$rotation."</font>";
					// return;
				}				
			}
			else
			{
				$msg = "<font color='red'>Bill already generated for ".$rotation."</font>";
			}
			
			// after generating bill, take to vessel list page.
			$data['msg']=$msg;
			
			$data['title']="Vessel Forwarding by Accountant";
			/* $departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
			DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain,
			sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
			sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd, sparcsn4.vsl_vessel_visit_details.ib_vyg
			FROM sparcsn4.argo_carrier_visit
			INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
			INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
			INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
			INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
			INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
			WHERE sparcsn4.argo_carrier_visit.phase='20INBOUND'
			AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.acnt_bill_stat='0'
			AND sparcsn4.ref_country.cntry_code!='BD'
			ORDER BY sparcsn4.argo_carrier_visit.atd DESC"; */
			$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
			FROM outer_vsl_visit_info
			INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
			INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
			INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
			WHERE outer_vsl_forward_info.sr_acnt_forward_stat='1' AND outer_vsl_forward_info.acnt_bill_stat='0'
			ORDER BY date_of_arrival DESC";
			
			$departData = $this->bm->dataSelectDb1($departQuery);

			$data['departData']=$departData;
			// $data['fromDate']=$fromDate;
			// $data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;


			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			// $this->load->view('vesselForwardOuterAnchorage',$data);			
			$this->load->view('vesselForwardList_notEntering',$data);			
			$this->load->view('jsAssets');
		}
	}
	
	function generatePilotageBill($rotation)
	{
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		$insertDETAILS = 0;
		$insertSUB_DETAILS = 0;
		
		// $jettyCountQuery = $this->vbq->getJettyCountQuery($rotation);
		// $visitCountQuery = $this->vbq->getVisitCountQuery($rotation);
		$pilotChargesQuery = $this->vbq->getPilotChargesQueryInboundOnly($rotation);
		
		// $jettyCountResult = $this->bm->dataSelect($jettyCountQuery);		
		// $visitCountResult = $this->bm->dataSelect($visitCountQuery);		
		$pilotChargesResult = $this->bm->dataSelectDb1($pilotChargesQuery);
	
		$chk = $this->checkIfPilotExists($rotation);		
		
		if($chk > 0)
		{
			$msg = "PILOT EXISTS FOR ".$rotation;			
			return $msg;
		}		
		else
		{
			$berth_suffix = 1;			
						
			for($i=0;$i<count($pilotChargesResult);$i++)
			{
				$rotNo = $pilotChargesResult[$i]['rotation'];
				$vsl_name = $pilotChargesResult[$i]['vsl_name'];
				$ata = $pilotChargesResult[$i]['date_of_arrival']." ".$pilotChargesResult[$i]['time_of_arrival'];
				$atd = $pilotChargesResult[$i]['date_of_departure']." ".$pilotChargesResult[$i]['time_of_departure'];
				$agent_code = $pilotChargesResult[$i]['agent_code'];
				$agent_name = $pilotChargesResult[$i]['agent_name'];
				$agent_alias_id = $pilotChargesResult[$i]['alias_id'];
				$agent_address = $pilotChargesResult[$i]['agent_address'];
				$flag = $pilotChargesResult[$i]['flag'];
				$cnt_code = $pilotChargesResult[$i]['flag'];
				$grt = $pilotChargesResult[$i]['grt'];
				$deck_cargo = $pilotChargesResult[$i]['deck_cargo'];
				$bill_type = $pilotChargesResult[$i]['bill_type'];
				
				$remarks = $pilotChargesResult[$i]['remarks'];
				
				$bill_name = "BILL FOR PORT & PILOTAGE CHARGES ON VESSEL";
				$user_name = $login_id;
				
				$sql_exchangeRate = "SELECT rate AS rtnValue FROM billing.bil_currency_exchange_rates ORDER BY effective_date DESC LIMIT 1";
				$exchangeRate = $this->bm->dataReturn($sql_exchangeRate);
				
				$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")." (rotation,vsl_name,ata,atd,agent_code,agent_name,flag,cnt_code,grt,deck_cargo,exchangeRate,berth_suffix,bill_type,bill_name,creator,agent_alias_id,agent_address,ip_address,billing_date)
				VALUES('$rotNo','$vsl_name','$ata','$atd','$agent_code','$agent_name','$flag','$cnt_code','$grt','$deck_cargo','$exchangeRate','$berth_suffix','$bill_type','$bill_name','$user_name','$agent_alias_id','$agent_address','$ipAddress',NOW())";	
				$insertDETAILS = $this->bm->dataInsert($insertQuery);
				
				if($insertDETAILS == 0)
				{
					return "<font color='red'>Insertion DETAILS failed</font>";
				}	
				
				$sql_draftNumber = "SELECT draftNumber AS rtnValue 
								FROM ".$this->Init_Table_Map("DETAILS")."
								ORDER BY draftNumber DESC LIMIT 1";
				$draftNumber = $this->bm->dataReturn($sql_draftNumber);
				
				$pilotChargeSubDetailQuery = $this->vbq->getPilotSubDetailsQueryInboundOnly($grt,$deck_cargo);
				$rsPilotSubDetailResult = $this->bm->dataSelect($pilotChargeSubDetailQuery);
				
				for($j=0;$j<count($rsPilotSubDetailResult);$j++)
				{
					$description = $rsPilotSubDetailResult[$j]['description'];
					$gl_code = $rsPilotSubDetailResult[$j]['gl_code'];
					$rate = $rsPilotSubDetailResult[$j]['rate'];
					$unit = $rsPilotSubDetailResult[$j]['unit'];
					$bas = $rsPilotSubDetailResult[$j]['bas'];
					$move = $rsPilotSubDetailResult[$j]['move'];
										
					$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,unit_for_pilot,bas,move)
							VALUES('$draftNumber','$description','$gl_code','$rate','$unit','$bas','$move')";
					$insertSUB_DETAILS = $this->bm->dataInsert($query);
					if($insertSUB_DETAILS == 0)
					{
						return "<font color='red'>Insertion SUB_DETAILS failed for ".$j."</font>";
					}
				}	
			}
			// pilotChargesResult loop end
		}		
		// else of $chk > 0
		
		if(($insertDETAILS == 1) and ($insertSUB_DETAILS == 1))
		{
			// $rtnData = "<font color='green'>Bill Generated</font>";
			$rtnData = "BillGenerated";
			return $rtnData;
		}
		else
		{
			$rtnData = "SomethingWrong";
			return $rtnData;
		}
	}
	
	function checkIfPilotExists($rotation)
	{
		$checkIfExists = "SELECT COUNT(*) AS rtnValue
						FROM ".$this->Init_Table_Map("DETAILS")."
						WHERE rotation = '".$rotation."' AND bill_type = '102'";		
		$cntPilot = $this->bm->dataReturn($checkIfExists);
		return $cntPilot;
	}

	function getMasterName($rotNo)
	{
		$query = "SELECT Name_of_Master AS rtnValue FROM igm_masters WHERE Import_Rotation_No='$rotNo'";
		$masterName = $this->bm->dataReturnDb1($query);
		
		return $masterName;
	}
	
	function addAdditionalEventPilotCharges($rotation,$draftNumber,$events,$grt,$loa_cm)
	{
		$evnts = "";
				
		for($k = 0 ; $k < count($events) ; $k++)
		{
			if($k == (count($events)-1))
			{
				// $evnts += ($events[$k] + "");
				$evnts = $evnts.($events[$k]."");
			}
			else
			{
				// $evnts += ($events[$k] + ",");
				$evnts = $evnts.($events[$k].",");
			}
		}
		
		$additionalEventSubDetailQuery = "SELECT billing.bil_tariffs.description,billing.bil_tariffs.gl_code,billing.bil_tariff_rates.amount AS rate,
		(SELECT CASE sparcsn4.srv_event_types.id  WHEN 'BERTHING' THEN 'NOS'
		WHEN 'WATER_BY_LINE' THEN 'TON'
		WHEN 'BERTH HIRE 1-13, 17, SLJ' THEN 'HRS'
		WHEN 'WTR CPA BRG' THEN 'TON'
		WHEN 'TUG_ADDITIONAL_BERTHING' THEN 'HRS'
		WHEN 'TUG_ADDITIONAL_UNBERTHING' THEN 'HRS'
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
		ELSE NULL END) AS bas,
		'1' AS unit,
		IF('$loa_cm'>186,(CEIL(TIMESTAMPDIFF(SECOND,IF(srv_event_types.gkey=187,sparcsn4.vsl_vessel_visit_details.flex_date01,sparcsn4.vsl_vessel_visit_details.flex_date03),IF(srv_event_types.gkey=187,sparcsn4.vsl_vessel_visit_details.flex_date02,sparcsn4.vsl_vessel_visit_details.flex_date04))/3600))*2,CEIL(TIMESTAMPDIFF(SECOND,IF(srv_event_types.gkey=187,sparcsn4.vsl_vessel_visit_details.flex_date01,sparcsn4.vsl_vessel_visit_details.flex_date03),IF(srv_event_types.gkey=187,sparcsn4.vsl_vessel_visit_details.flex_date02,sparcsn4.vsl_vessel_visit_details.flex_date04))/3600)) AS move
		FROM sparcsn4.srv_event
		INNER JOIN sparcsn4.srv_event_types ON  srv_event_types.gkey=srv_event.event_type_gkey
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.srv_event.applied_to_gkey
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=sparcsn4.srv_event_types.id
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotation' AND applied_to_class='VV' AND srv_event_types.gkey IN('$evnts')
		AND billing.bil_tariff_rates.rate_type='REGULAR'

		UNION ALL

		SELECT IF('$grt'<1001,CONCAT(billing.bil_tariffs.description,' ',(SELECT description FROM billing.bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=billing.bil_tariff_rates.gkey)),IF('$grt'<5001,CONCAT(billing.bil_tariffs.description,' ',(SELECT description FROM billing.bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=billing.bil_tariff_rates.gkey)),CONCAT(billing.bil_tariffs.description,' ',(SELECT description FROM billing.bil_tariff_rate_tiers WHERE min_quantity=5001 AND rate_gkey=billing.bil_tariff_rates.gkey)))) AS description,
		billing.bil_tariffs.gl_code,
		IF('$grt'<1001,(SELECT amount FROM billing.bil_tariff_rate_tiers WHERE min_quantity=200 AND rate_gkey=billing.bil_tariff_rates.gkey),IF('$grt'<5001,(SELECT amount FROM billing.bil_tariff_rate_tiers WHERE min_quantity=1001 AND rate_gkey=billing.bil_tariff_rates.gkey),(SELECT amount FROM billing.bil_tariff_rate_tiers WHERE min_quantity=5001 AND rate_gkey=billing.bil_tariff_rates.gkey))) AS rate,
		'NOS' AS bas,'1' AS unit,
		IF('$loa_cm'>186,2,1) AS move
		FROM sparcsn4.srv_event
		INNER JOIN sparcsn4.srv_event_types ON  srv_event_types.gkey=srv_event.event_type_gkey
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.srv_event.applied_to_gkey
		INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=sparcsn4.srv_event_types.id
		INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
		WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotation' AND applied_to_class='VV' AND srv_event_types.gkey IN('$evnts')
		AND billing.bil_tariff_rates.rate_type='BAND'";
		$additionalEventResult = $this->bm->dataSelect($additionalEventSubDetailQuery);
		
		for($l=0;$l<count($additionalEventResult);$l++)
		{
			$description = $additionalEventResult[$l]['description'];
			$gl_code = $additionalEventResult[$l]['gl_code'];
			$rate = $additionalEventResult[$l]['rate'];
			$bas = $additionalEventResult[$l]['bas'];
			$move = $additionalEventResult[$l]['move'];
			$unit = $additionalEventResult[$l]['unit'];
			
			$query="INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,move,unit_for_pilot)
			VALUES('$draftNumber','$description','$gl_code','$rate','$bas','$move','$unit')";
			// $insertStat_3 = $this->bm->dataInsert($query);
			if($insertStat_3 == 0)
			{
				return "<font color='red'>Insertion 3 failed for ".$l."</font>";
			}
		}
	}
	
	function Init_Table_Map($tblCode)
	{
		if($tblCode == "DETAILS")
			// return "ctmsmis.mis_vsl_billing_detail";
			return "ctmsmis.mis_vsl_billing_detail_test";
		else if($tblCode == "SUB_DETAILS")
			// return "ctmsmis.mis_vsl_billing_sub_detail";
			return "ctmsmis.mis_vsl_billing_sub_detail_test";
		else if($tblCode == "CHALLAN")
			return "ctmsmis.mis_vsl_billing_challan";
	}		
	
	function vesselBillListAcc($action=null,$msg = null)		// from vesselBillList of Report, check for backup
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{				
			$login_id = $this->session->userdata('login_id');
			$data['title']="VESSEL BILL LIST...";

			if($msg =='ok'){
				$msg = "<font color='green'>Bill Approved.</font>";
			}
			
			// $sql_row_num="select count(*) as rtnValue from ctmsmis.mis_vsl_billing_detail_test where agent_code='$login_id'";
			
			//echo $sql_row_num;
			// $segment_three = $this->uri->segment(3);
			
			// $config = array();
			// $config["base_url"] = site_url("VesselBill/vesselBillListAcc/$segment_three");
			// $config["total_rows"] = $this->bm->dataReturn($sql_row_num);
			// $config["per_page"] = 20;
			// $offset = $this->uri->segment(4, 0);
			// $config["uri_segment"] = 4;
			// $limit=$config["per_page"];
			
			// $this->pagination->initialize($config);
			// $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			// $start=$page;
			$sql_bill_list = "";
			
			if($action == "p"){
				$data['title']="VESSEL BILL LIST (Pending)";
				$sql_bill_list="select draftNumber,IFNULL(finalNumber,draftNumber) as finalNumber,rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st
				from ctmsmis.mis_vsl_billing_detail_test WHERE acc_apprv_st = 0 order by draftNumber DESC";
			}else {
				$data['title']="VESSEL BILL LIST (Approved)";
				$sql_bill_list="select draftNumber,IFNULL(finalNumber,draftNumber) as finalNumber,rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st
				from ctmsmis.mis_vsl_billing_detail_test WHERE acc_apprv_st = 1 order by draftNumber DESC";
			}

			$rslt_bill_list=$this->bm->dataSelect($sql_bill_list);						
			
			$data['rslt_bill_list']=$rslt_bill_list;
			// $data['start']=$start;
			// $data["links"] = $this->pagination->create_links();
			$data['action'] = $action;
			$data['msg'] = $msg;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselBillListAcc',$data);			
			$this->load->view('jsAssetsList');		
		}
	}

	function vesselBillListApprove()		// from vesselBillList of Report, check for backup
	{
		$this->load->helper('url');
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{				
			$login_id = $this->session->userdata('login_id');
			$draft = $this->input->post('draft');
			$msg = "";

			$query = "Update ctmsmis.mis_vsl_billing_detail_test set acc_apprv_st = 1 , acc_apprv_at = NOW() , acc_apprv_by = '$login_id' where draftNumber = '$draft'";
			if($this->bm->dataUpdate($query)){
				$msg = "ok";
			}
			
			redirect('VesselBill/vesselBillListAcc/p'.$msg, 'refresh');
		}
	}
	
	/* function viewVesselBillAcc()
	{					
		// $draftNumber = 2;
		$draftNumber = $this->uri->segment(3);
		
		$bill_sql="SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName,payeecustomerkey,agent_address,grossRevenueTons,exchangeRate,created,flagcountry,cargo,ffd,onboundpiloton,onboundpilotoff,inboundpiloton,inboundpilotoff,description,glcode,rateBilled,quantityUnit,IF(description LIKE 'Tug%' OR description='Additional Tug Charge for Unberthing',SUM(quantityBilled),quantityBilled) AS quantityBilled,IF(description ='PILOTAGE FEE' OR description ='Night Navigation Fee' OR description LIKE 'SHIFT%' OR description LIKE 'Additional Tug Charges for Shifting%',SUM(move),move) AS move,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(bdChraged),2) AS bdChraged,
		ROUND(SUM(vatusd),4) AS vatusd,
		ROUND((SUM(bdChraged)*15/100),2) AS bdVat,STATUS,creator 
		FROM 
		(SELECT bill_name AS invoiceDesc,
		IF(cnt_code='BD',CONCAT('PL/',ctmsmis.mis_vsl_billing_detail_test.draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',ctmsmis.mis_vsl_billing_detail_test.draftNumber,'-',SUBSTRING(billing_date,4,1))) AS draftNumber,
		vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,atd AS ATD,ata AS ATA,agent_name AS customerName,
		agent_code AS payeecustomerkey,agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,billing_date AS created,
		flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
		pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,description AS description,CONCAT(gl_code,'0') AS glcode,
		rate AS rateBilled,bas AS quantityUnit,unit_for_pilot AS quantityBilled,move,(rate*unit_for_pilot*move) AS totusd,
		(rate*unit_for_pilot*move*exchangeRate) AS bdChraged,
		'DRAFT' AS STATUS,creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,
		-- (SELECT IF(vtdt=1,((totusd*15)/100),IF((description='BERTHING' OR description='SHIFT VESSEL BERTH'),((totusd*15)/100),0)))  AS vatusd
		(((rate*unit_for_pilot*move)*15)/100) AS vatusd
		FROM ctmsmis.mis_vsl_billing_detail_test
		INNER JOIN ctmsmis.mis_vsl_billing_sub_detail_test ON ctmsmis.mis_vsl_billing_sub_detail_test.draftNumber=ctmsmis.mis_vsl_billing_detail_test.draftNumber
		WHERE ctmsmis.mis_vsl_billing_detail_test.draftNumber='$draftNumber' AND bill_type=102) AS tbl
		GROUP BY description";
		
		$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";
			
		$bill_rslt=$this->bm->dataSelect($bill_sql);

		if(count($bill_rslt)==0)
		{
			echo "wrong draft";
			return;
		}	
		
		$print_time=$this->bm->dataSelect($bill_print_time);
		
		$this->data['bill_rslt']=$bill_rslt;
		$this->data['print_time']=$print_time;
		
		$this->load->library('m_pdf');
		$html=$this->load->view('vesselBillIbnoundOnly',$this->data, true);			 
		$pdfFilePath ="Vessel Bill-".time()."-download.pdf";
		$pdf = $this->m_pdf->load();
		// $pdf->allow_charset_conversion = true;
		// $pdf->charset_in = 'iso-8859-4';
		$stylesheet = file_get_contents('assets/stylesheets/test.css');				
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);				 
		$pdf->Output($pdfFilePath, "I");
	} */
	
	function viewVesselBillAcc()
	{					
		// $draftNumber = 2;
		$draftNumber = $this->uri->segment(3);
		$bill_type = $this->uri->segment(4);	// added				
		
		$bill_sql = "";
		if($bill_type == 101)
		{						
			$bill_sql = "SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName,payeecustomerkey,agent_address,grossRevenueTons,
			exchangeRate,created,berth,flagcountry,cargo,ffd,description,glcode,rateBilled,quantityUnit,SUM(quantityBilled) AS quantityBilled,
			creator,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(totbsd),2) AS totbsd,ROUND(SUM(vatbd),2) AS vatbd,STATUS
			FROM(
			SELECT bill_name AS invoiceDesc,IF(cnt_code='BD',CONCAT('JL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),
			CONCAT('JF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1))) AS draftNumber,vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,atd AS ATD,ata AS ATA,agent_name AS customerName,CONCAT(agent_code,'(',IFNULL(agent_alias_id,''),')') AS payeecustomerkey,agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,billing_date AS created,berth AS berth,flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,description AS description,gl_code AS glcode,rate AS rateBilled,bas AS quantityUnit,
			IF(description LIKE 'BERTH_HIRE_1%',".$this->Init_Table_Map("DETAILS").".unit,".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot) AS quantityBilled,IF(description LIKE 'BERTH_HIRE_1%',((grt+IFNULL(deck_cargo,0))*rate*unit),(rate*".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot)) AS totusd,creator,
			IF(description LIKE 'BERTH_HIRE_1%',((grt+IFNULL(deck_cargo,0))*rate*unit*exchangeRate),(rate*".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot*exchangeRate)) AS totbsd,
			(SELECT IF(DATE(ata)>='2017-12-27',ROUND(totusd*15/100,4),0)) AS vatusd,
			(SELECT vatusd*exchangeRate) AS vatbd,'DRAFT' AS STATUS
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type'
			ORDER BY draftNumber,description) AS tbl
			GROUP BY description";
		}
		else if($bill_type == 102)		// BILL FOR PORT & PILOTAGE CHARGES ON VESSEL
		{						
			$bill_sql="SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,
			
			DATE(inboundpiloton) AS dateOfBerth,
			DATE(onboundpiloton) AS dateOfLeave,

			TIME(inboundpiloton) AS timeBerthFrom,
			TIME(inboundpilotoff) AS timeBerthTo,
	
			TIME(onboundpiloton) AS timeLeaveFrom,
			TIME(onboundpilotoff) AS timeLeaveTo,
			customerName,payeecustomerkey,agent_address,grossRevenueTons,exchangeRate,created,flagcountry,cargo,ffd,onboundpiloton,onboundpilotoff,inboundpiloton,inboundpilotoff,description,glcode,rateBilled,quantityUnit,IF(description LIKE 'Tug%' OR description='Additional Tug Charge for Unberthing',SUM(quantityBilled),quantityBilled) AS quantityBilled,IF(description ='PILOTAGE FEE' OR description ='Night Navigation Fee' OR description LIKE 'SHIFT%' OR description LIKE 'Additional Tug Charges for Shifting%',SUM(move),move) AS move,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(bdChraged),2) AS bdChraged,
			ROUND(SUM(vatusd),4) AS vatusd,
			ROUND((SUM(bdChraged)*15/100),2) AS bdVat,STATUS,creator 
			FROM (
			SELECT bill_name AS invoiceDesc,
			IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1))) AS draftNumber,
			vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,atd AS ATD,ata AS ATA,agent_name AS customerName,CONCAT(agent_code,'(',IFNULL(agent_alias_id,''),')') AS payeecustomerkey,agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,billing_date AS created,flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
			pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,description AS description,CONCAT(gl_code,'0') AS glcode,rate AS rateBilled,bas AS quantityUnit,
			unit_for_pilot AS quantityBilled,move,(rate*unit_for_pilot*move) AS totusd,
			(rate*unit_for_pilot*move*exchangeRate) AS bdChraged,
			'DRAFT' AS STATUS,creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,
			(SELECT IF(vtdt=1,((totusd*15)/100),IF((description='BERTHING' OR description='SHIFT VESSEL BERTH'),((totusd*15)/100),0)))  AS vatusd
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type') AS tbl
			GROUP BY description";
		}
		else if($bill_type == 106)		// 106 = Not Entering
		{											
			$bill_sql="SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName,payeecustomerkey,agent_address,grossRevenueTons,exchangeRate,created,flagcountry,cargo,ffd,onboundpiloton,onboundpilotoff,inboundpiloton,inboundpilotoff,description,glcode,rateBilled,quantityUnit,IF(description LIKE 'Tug%' OR description='Additional Tug Charge for Unberthing',SUM(quantityBilled),quantityBilled) AS quantityBilled,IF(description ='PILOTAGE FEE' OR description ='Night Navigation Fee' OR description LIKE 'SHIFT%' OR description LIKE 'Additional Tug Charges for Shifting%',SUM(move),move) AS move,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(bdChraged),2) AS bdChraged,
			ROUND(SUM(vatusd),4) AS vatusd,
			ROUND((SUM(bdChraged)*15/100),2) AS bdVat,STATUS,creator 
			FROM 
			(SELECT bill_name AS invoiceDesc,
			IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1))) AS draftNumber,
			vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,atd AS ATD,ata AS ATA,agent_name AS customerName,
			agent_code AS payeecustomerkey,agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,billing_date AS created,
			flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
			pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,description AS description,CONCAT(gl_code,'0') AS glcode,
			rate AS rateBilled,bas AS quantityUnit,unit_for_pilot AS quantityBilled,move,(rate*unit_for_pilot*move) AS totusd,
			(rate*unit_for_pilot*move*exchangeRate) AS bdChraged,
			'DRAFT' AS STATUS,creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,		
			(((rate*unit_for_pilot*move)*15)/100) AS vatusd
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type') AS tbl
			GROUP BY description";
		}
		// echo $bill_sql;return;
		$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";
			
		$bill_rslt=$this->bm->dataSelect($bill_sql);

		if(count($bill_rslt)==0)
		{
			echo "wrong draft";
			return;
		}	
		
		$print_time=$this->bm->dataSelect($bill_print_time);
		
		$this->data['bill_rslt']=$bill_rslt;
		$this->data['print_time']=$print_time;
		
		$this->load->library('m_pdf');
		
		
		// work on type
		
		if($bill_type == 101)
		{
			$html=$this->load->view('vesselBill_JettyChargesOnVessel',$this->data, true);	
		}
		else if($bill_type == 102)		// BILL FOR PORT & PILOTAGE CHARGES ON VESSEL
		{
			$html=$this->load->view('vesselBill_BillForPortAndPilotageChargesOnVessel',$this->data, true);			
		}
		else if($bill_type == 106)		// 106 = Not Entering
		{
			$html=$this->load->view('vesselBillIbnoundOnly',$this->data, true);			 
		}
		
		$pdfFilePath ="Vessel Bill-".time()."-download.pdf";
		$pdf = $this->m_pdf->load();
		// $pdf->allow_charset_conversion = true;
		// $pdf->charset_in = 'iso-8859-4';
		$stylesheet = file_get_contents('assets/stylesheets/test.css');				
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);				 
		$pdf->Output($pdfFilePath, "I");
	}
}
?>