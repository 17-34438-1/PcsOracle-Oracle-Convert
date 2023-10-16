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
	
	function generateVesselsBillN4()
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

			// $dollarAta = $this->uri->segment(4);
			// $dollarAta = str_replace("_","-",$dollarAta);			
			// echo $dollarAta; return;
			
			// Check bill first 
			$sql_chkBill = "SELECT COUNT(*) AS rtnValue FROM ".$this->Init_Table_Map("DETAILS")." WHERE rotation='$rotation'";
			// echo $sql_chkBill;return;
			$chkBill = $this->bm->dataReturnDb2($sql_chkBill);
			// echo $chkBill;return;
			if($chkBill == 0)
			{										
				// exchangeRate
				/* if(!$this->checkExchangeRate($rotation))
				{
					echo $msg = "<font color='red'>NO EXCHANGE RATE ???? </font>";
					return;
				} */
				
				$this->Generate_Jetty_Charges_Bill($rotation);
				
				$this->Generate_Pilot_Charges_Bill($rotation);
				
				//$this->Generate_Fireman_Bill($rotation);
				
				//$this->Generate_WaterSupply_Bill($rotation);
				
				//Generate Fireman Bill....Starts
				
				// this checking is already done inside Generate_Fireman_Bill; so it is not necessary here.
				/* $sql_cntFiremanBill = "SELECT COUNT(*) cntEvent 
						FROM sparcsn4.srv_event_types
						INNER JOIN sparcsn4.srv_event ON  srv_event_types.gkey=srv_event.event_type_gkey
						INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.srv_event.applied_to_gkey
						WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotation' AND srv_event_types.gkey IN(213,211)";			
				$cntFiremanBill = $this->bm->dataReturn($sql_cntFiremanBill);
				if($cntFiremanBill > 0){
					$this->Generate_Fireman_Bill($rotation);
				} */
				//Generate Fireman Bill....Ends
				
			}
			else
			{
				echo $msg = "<font color='red'>Bill already created for $rotation</font>";
				echo "<br>";
				return;
			}
			
			$vvdGkey = "";
			if($jtyChrgFlag==1 and $pilotChrgFlag==1)
			{
				$sql_getVvdGkey = "SELECT vsl_vessel_visit_details.vvd_gkey AS rtnValue
				FROM vsl_vessel_visit_details
				WHERE ib_vyg='$rotation'";
				$vvdGkey = $this->bm->dataReturn($sql_getVvdGkey);
				
				$updateBillInfo = "UPDATE ctmsmis.vsl_forward_info
								SET ctmsmis.vsl_forward_info.billop_bill_stat='1',
								ctmsmis.vsl_forward_info.billop_bill_at=NOW(),
								ctmsmis.vsl_forward_info.billop_bill_by='$login_id',
								ctmsmis.vsl_forward_info.billop_bill_ip='$ipAddress'
								WHERE ctmsmis.vsl_forward_info.vvd_gkey='$vvdGkey'";
				$this->bm->dataUpdatedb2($updateBillInfo);
			}
			
			// echo "jtyChrgFlag : ".$jtyChrgFlag;
			// echo "<br>";
			// echo "pilotChrgFlag : ".$pilotChrgFlag;
			// echo "<br>";
			// echo "firemanChrgFlag : ".$firemanChrgFlag;
			// echo "<br>";
			// echo "--------------------------";
			// redirect('/Vessel/vesselForwardingForAcc', 'location');
			
			$msgRot = str_replace('/','_',$rotation);
			// echo "1 ".$msgRot;
			$msgRot = "billReady_".$msgRot;
			// echo "2 ".$msgRot;
			
			redirect('VesselBill/vesselBillListAcc/p/'.$msgRot, 'location');
		}
	}		// generateVesselsBillN4 end
	
	function checkExchangeRate($rotation)
	{
		$checkExchangeRateQuery = $this->vbq->checkExchangeRateQuery($rotation);
		$checkExchangeRate = $this->bm->dataSelect($checkExchangeRateQuery);
		
		$exchangeRate = "";
		
		for($i = 0;$i<count($checkExchangeRate);$i++)
		{
			$exchangeRate = $checkExchangeRate[$i]['exchangeRate'];
		}
		
		if($exchangeRate == "" or $exchangeRate == null or $exchangeRate == "N")
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function generateVesselsBillNotEntering($rotation)
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
			$section =$this->session->userdata('section');
			$msg = "";
		
			if($rotation == null or $rotation == "")
				$rotation = $this->input->post('rot');
			
			if($rotation == null or $rotation == "")
			{
				$rotation = $this->uri->segment(3);
				$rotation = str_replace("_","/",$rotation);
			}
			
			// Check bill first 
			$sql_chkBill = "SELECT COUNT(*) AS rtnValue FROM ".$this->Init_Table_Map("DETAILS")." WHERE rotation='$rotation'";
			// echo $sql_chkBill;return;
			$chkBill = $this->bm->dataReturn($sql_chkBill);
			
			if($chkBill == 0)
			{			
				// New Pilotage Bill					
				$rtnData = $this->generatePilotageBillNotEntering($rotation);
				// echo "hello 2";return;
				if($rtnData == "BillGenerated")
				{
					$sql_vviId = "SELECT id AS rtnValue FROM outer_vsl_visit_info WHERE imp_rot='$rotation'";
					$vviId = $this->bm->dataReturnDb1($sql_vviId);
										
					/* $sql_updateBillStat = "UPDATE outer_vsl_forward_info
					SET sr_acnt_forward_stat='1',sr_acnt_bill_by='$login_id',sr_acnt_bill_at=NOW(),sr_acnt_bill_ip='$ipAddress'
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
		
			$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,sr_acnt_forward_at AS forwarded_dt
			FROM outer_vsl_visit_info
			INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
			INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
			INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
			WHERE outer_vsl_forward_info.sr_acnt_forward_stat='1' AND outer_vsl_forward_info.billop_bill_stat='0'
			ORDER BY date_of_arrival DESC";
			
			$departData = $this->bm->dataSelectDb1($departQuery);

			$data['departData']=$departData;
			
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;


		
			
			$msgRot = str_replace('/','_',$rotation);
			echo "3 ".$msgRot;
			$msgRot = "billReady_".$msgRot;
			echo "4 ".$msgRot;
			redirect('VesselBill/vesselBillListAcc/p/'.$msgRot, 'refresh');
		}
	}
	
	function generatePilotageBillNotEntering($rotation)
	{
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		$insertDETAILS = 0;
		$insertSUB_DETAILS = 0;
		
		$pilotChargesQuery = $this->vbq->getPilotChargesQueryInboundOnly($rotation);
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
				
			
				
				$sql_offPortArr = "SELECT off_port_arr
								FROM vsl_vessel_visit_details
								WHERE ib_vyg='$rotNo'";
				$rslt_offPortArr = $this->bm->dataSelect($sql_offPortArr);
				
				$offPortArr = "";
				
				for($j=0;$j<count($rslt_offPortArr);$j++)
				{
					$offPortArr = $rslt_offPortArr[$j]['OFF_PORT_ARR'];
				}
								
				$this->updateDollarRateIn42($offPortArr);				
				
				$sql_dollarRate = "SELECT rate
								FROM cchaportdb.bil_currency_exchange_rates
								WHERE effective_date=DATE('$offPortArr')
								ORDER BY gkey DESC
								LIMIT 1";
				
				$rslt_dollarRate = $this->bm->dataSelectDB1($sql_dollarRate);
				
				for($j=0;$j<count($rslt_dollarRate);$j++)
				{
					$dollarRate = $rslt_dollarRate[$j]['rate'];
				}
				
				// dollar rate - end
				
				$exchangeRate = $dollarRate;
				
				$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")." (rotation,vsl_name,ata,atd,agent_code,agent_name,flag,cnt_code,grt,deck_cargo,exchangeRate,berth_suffix,bill_type,bill_name,creator,agent_alias_id,agent_address,ip_address,billing_date)
				VALUES('$rotNo','$vsl_name','$ata','$atd','$agent_code','$agent_name','$flag','$cnt_code','$grt','$deck_cargo','$exchangeRate','$berth_suffix','$bill_type','$bill_name','$user_name','$agent_alias_id','$agent_address','$ipAddress',NOW())";	
				$insertDETAILS = $this->bm->dataInsertDb2($insertQuery);
				
				if($insertDETAILS == 0)
				{
					return "<font color='red'>Insertion DETAILS failed</font>";
				}	
				
				$sql_draftNumber = "SELECT draftNumber AS rtnValue 
								FROM ".$this->Init_Table_Map("DETAILS")."
								ORDER BY draftNumber DESC LIMIT 1";
				$draftNumber = $this->bm->dataReturnDb2($sql_draftNumber);
				
			

				$rsPilotSubDetailResult = $this->vbq->getPilotSubDetailsQueryInboundOnly($grt,$deck_cargo);
				
				if(count($rsPilotSubDetailResult)>0)
				{
					for($j=0;$j<count($rsPilotSubDetailResult);$j++)
					{
					

						$description 	= $rsPilotSubDetailResult[$j]['DESCRIPTION'];
						$gl_code 		= $rsPilotSubDetailResult[$j]['GL_CODE'];
						$rate 			= $rsPilotSubDetailResult[$j]['RATE'];
						$unit 			= $rsPilotSubDetailResult[$j]['UNIT'];
						$bas 			= $rsPilotSubDetailResult[$j]['BAS'];
						$move 			= $rsPilotSubDetailResult[$j]['MOVE'];
											
						$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,unit_for_pilot,bas,move)
								VALUES('$draftNumber','$description','$gl_code','$rate','$unit','$bas','$move')";
						$insertSUB_DETAILS = $this->bm->dataInsertDb2($query);
						if($insertSUB_DETAILS == 0)
						{
							return "<font color='red'>Insertion SUB_DETAILS failed for ".$j."</font>";
						}
					}
				}
				else
				{
					return "<font color='red'>rsPilotSubDetailResult not entering SUB_DETAILS failed for ".$j."</font>";
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
		$query = "SELECT Name_of_Master FROM igm_masters WHERE Import_Rotation_No='$rotNo'";
		$res_masterName = $this->bm->dataSelectDb1($query);
		
		$masterName = "";
		for($i=0;$i<count($res_masterName);$i++)
		{
			$masterName = $res_masterName[$i]['Name_of_Master'];
		}
		
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
		
		$cnt = 0;
		for($l=0;$l<count($additionalEventResult);$l++)
		{
			$description 	= $additionalEventResult[$l]['description'];
			$gl_code 		= $additionalEventResult[$l]['gl_code'];
			$rate 			= $additionalEventResult[$l]['rate'];
			$bas 			= $additionalEventResult[$l]['bas'];
			$move 			= $additionalEventResult[$l]['move'];
			$unit 			= $additionalEventResult[$l]['unit'];
			
			$query="INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,move,unit_for_pilot)
			VALUES('$draftNumber','$description','$gl_code','$rate','$bas','$move','$unit')";
			$insertStat_3 = $this->bm->dataInsert($query);
						
			if($insertStat_3 == 1)
			{
				$cnt++;
				// return "<font color='red'>Insertion 3 failed for ".$l."</font>";
			}
		}
		
		$rtnMsg = "";
		if($cnt == count($additionalEventResult))
		{
			$rtnMsg = "AdditionalEvent_Success";			
		}
		else
		{
			$rtnMsg = "AdditionalEvent_Failed";
		}
		
		return $rtnMsg;
	}
	
	function Init_Table_Map($tblCode)
	{
		if($tblCode == "DETAILS")
		{
			// return "ctmsmis.mis_vsl_billing_detail";
			return "ctmsmis.mis_vsl_billing_detail_test";
		}
		else if($tblCode == "SUB_DETAILS")
		{
			// return "ctmsmis.mis_vsl_billing_sub_detail";
			return "ctmsmis.mis_vsl_billing_sub_detail_test";
		}
		else if($tblCode == "CHALLAN")
		{
			// return "ctmsmis.mis_vsl_billing_challan";
			return "ctmsmis.mis_vsl_billing_challan_test";
		}
		else if($tblCode == "DISPUTE")
		{
			return "ctmsmis.mis_vsl_billing_dispute";
		}
	}		
	


	// Added by Kawsar on 17 oct 2022 -- start

	function vesselBillListAcc($action=null,$acc=null,$msg = null)		// from vesselBillList of Report, check for backup
	{
			// echo "hello";return;
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		// $this->session->set_userdata(array('menu' => "bill"));
		// $this->session->set_userdata(array('sub_menu' => "vesselBillListAcc/".$action));
		// echo $acc;return;
		if(!is_null($acc)){
			$this->session->set_userdata(array('menu' => "dispute_bill"));
		}
		else
		{
			$this->session->set_userdata(array('menu' => "bill"));
		}
		
		if(!is_null($acc)){
			$this->session->set_userdata(array('sub_menu' => "vesselBillListAcc/".$action.'/'.$acc));
		}
		else
		{
			$this->session->set_userdata(array('sub_menu' => "vesselBillListAcc/".$action));
		}

		//Menu Expanding....
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{				
			// echo $action;return;
			
			$section = $this->session->userdata('section');
			$login_id = $this->session->userdata('login_id');
			$orgTypeId = $this->session->userdata('org_Type_id');

			$msgRot = "";
			if($msg =='ok'){
				$msg = "<font color='green'>Bill Approved.</font>";
			}
			else if($msg!="")
			{
				$msgArr = explode("_",$msg);
				
				$msgRot = $msgArr[1]."/".$msgArr[2];
				
				if($msgArr[0] == 'billReady')
				{
					$msg = "<font color='green' size='3'>Bill Generated for $msgRot</font>";
				}
				else if($msgArr[0] == 'billFailed')
				{
					$msg = "<font color='red' size='3'>Bill Not Generated for $msgRot</font>";
				}
				else
				{
					$msg = "<font color='red' size='3'>No status message</font>";
				}
			}
						
			$sql_bill_list = "";
			
			$cond = "";
			if($section == "billop")
			{
				$cond = " AND creator='$login_id'";
				
				if($msgRot!="")
				{
					$cond = $cond." AND rotation='$msgRot'";
				}
			}
			else if($orgTypeId == 1)	// for MLO
			{
				$agentCode = $this->session->userdata('agentCode');
				$cond = " AND agent_code='$agentCode'";
			}
			
			if($action == "p"){
				$data['title']="VESSEL BILL LIST (Pending)";
				
				// $sql_bill_list="SELECT draftNumber,finalNumber,
				// IF(finalNumber IS NULL OR finalNumber='',
				// (IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS billNumber,
				// rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st,creator
				// FROM ".$this->Init_Table_Map("DETAILS")."
				// WHERE acc_apprv_st = 0 ".$cond." ORDER BY draftNumber DESC";
				
				$sql_bill_list="SELECT draftNumber,finalNumber, cnt_code,bill_type,disputeraised,
				IF(finalNumber IS NULL OR finalNumber='',
				(IF(cnt_code='BD',CONCAT(if(bill_type='101','JL/','PL/'),".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT(if(bill_type='101','JF/','PF/'),".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS billNumber, rotation,vsl_name,bill_name,CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st,creator 
				FROM ".$this->Init_Table_Map("DETAILS")." 
				WHERE acc_apprv_st = 0 ".$cond." ORDER BY draftNumber DESC";			
			} 
			else 
			{
				if($orgTypeId==1 || $orgTypeId==81)
					$data['title']="VESSEL BILL LIST";
				else
					$data['title']="VESSEL BILL LIST (Approved)";
					
				if($orgTypeId==81){
					$cond.=" AND disputeraised = 1";
				}

				if($orgTypeId==83){
					$cond.=" AND disputeraised = 1 AND app_st = 1";
				}

				if($orgTypeId==82 && $acc == 'dis'){
					$cond.=" AND disputeraised = 1 AND forward_post_mod_st = 1";
				}

				// $sql_bill_list="SELECT draftNumber,finalNumber,
				// IF(finalNumber IS NULL OR finalNumber='',(IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS billNumber,
				// rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st,creator
				// FROM ".$this->Init_Table_Map("DETAILS")."
				// WHERE acc_apprv_st = 1 ".$cond." ORDER BY draftNumber DESC";
				
				$sql_bill_list="SELECT ".$this->Init_Table_Map("DETAILS").".draftNumber,finalNumber, cnt_code,bill_type,disputeraised,
				IF(finalNumber IS NULL OR finalNumber='',
				(IF(cnt_code='BD',CONCAT(if(bill_type='101','JL/','PL/'),".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT(if(bill_type='101','JF/','PF/'),".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS billNumber, rotation,vsl_name,bill_name,
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st,creator,IFNULL(id,0) AS dispute_id,IFNULL(DATE_FORMAT(entry_at, '%d/%m/%Y %H:%i:%s'),'') AS dispute_at,IFNULL(app_st,0) AS app_st,IFNULL(DATE_FORMAT(app_at, '%d/%m/%Y %H:%i:%s'),'') AS app_at,IFNULL(mod_st,0) AS mod_st,IFNULL(DATE_FORMAT(mod_at, '%d/%m/%Y %H:%i:%s'),'') AS mod_at,IFNULL(forward_post_mod_st,0) AS forward_post_mod_st,IFNULL(DATE_FORMAT(forward_post_mod_at, '%d/%m/%Y %H:%i:%s'),'') AS forward_post_mod_at,IFNULL(forward_by_st,0) AS forward_by_st,IFNULL(DATE_FORMAT(forward_by_at, '%d/%m/%Y %H:%i:%s'),'') AS forward_by_at,IFNULL(bill_regenerate_st,0) AS bill_regenerate_st,IFNULL(DATE_FORMAT(bill_regenerate_at, '%d/%m/%Y %H:%i:%s'),'') AS bill_regenerate_at,dispute_text,dispute_doc
				FROM ".$this->Init_Table_Map("DETAILS")."
				LEFT JOIN ".$this->Init_Table_Map("DISPUTE")." ON ".$this->Init_Table_Map("DISPUTE").".draftNumber = ".$this->Init_Table_Map("DETAILS").".draftNumber
				WHERE acc_apprv_st = 1 ".$cond." ORDER BY ".$this->Init_Table_Map("DETAILS").".draftNumber DESC";
			}
			
			// echo $sql_bill_list;return;

			$rslt_bill_list=$this->bm->dataSelectDb2($sql_bill_list);						
			
			$data['rslt_bill_list']=$rslt_bill_list;
			// $data['start']=$start;
			// $data["links"] = $this->pagination->create_links();
			$data['action'] = $action;
			$data['msg'] = $msg;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselBillListAcc',$data);			
			$this->load->view('jsAssetsList');		
		}
	}

	function vslBillAction($action = null, $draft = null,$billNumber = null)
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
			$billNumber = str_replace("_","/",$billNumber);
			
			if($this->vslbillperform($action,$draft))
			{
				if($action == "app")
				{
					$msg = "Approved for <strong>{$billNumber}</strong>";
				}
				else if($action == "mod")
				{
					$msg = "Modified for <strong>{$billNumber}</strong>";
				}
				else if($action == "postMod")
				{
					$msg = "Forwarded to Accountant for <strong>{$billNumber}</strong>";
				}
				else if($action == "forward")
				{
					$msg = "Forwarded to Bill Operator for <strong>{$billNumber}</strong>";
				}

				$this->session->set_flashdata("error", "<div class='alert alert-success'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
				<font size='4'><strong>{$msg}</strong></font></div>", 3);
			}
			else
			{
				$this->session->set_flashdata("error", "<div class='alert alert-danger'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
				<font size='4'><strong>Failed!</strong> No action Choosen... </font></div>", 3);
			}

			redirect('VesselBill/vesselBillListAcc/a/', 'refresh');
		}
	}

	function vslbillperform($action = null, $draft = null)
	{
		$login_id = $this->session->userdata('login_id');
		if(!is_null($action) && !is_null($draft))
		{
			$query = "";
			if($action == "app")
			{
				$query = "UPDATE ".$this->Init_Table_Map("DISPUTE")." SET app_st = 1, app_by = '$login_id' , app_at = NOW() WHERE draftNumber = '$draft'";
			}
			else if($action == "mod")
			{
				$query = "UPDATE ".$this->Init_Table_Map("DISPUTE")." SET mod_st = 1, mod_by = '$login_id' , mod_at = NOW() WHERE draftNumber = '$draft'";
			}
			else if($action == "postMod")
			{
				$query = "UPDATE ".$this->Init_Table_Map("DISPUTE")." SET forward_post_mod_st = 1, forward_post_mod_by = '$login_id' , forward_post_mod_at = NOW() WHERE draftNumber = '$draft'";
			}
			else if($action == "forward")
			{
				$query = "UPDATE ".$this->Init_Table_Map("DISPUTE")." SET forward_by_st = 1, forward_by = '$login_id' , forward_by_at = NOW() WHERE draftNumber = '$draft'";
			}

			if($this->bm->dataUpdate($query))
			{
				return true;
			}
		}
		
		return false;
	}

	function vslBillDispute()
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
			$draft = $this->input->post('draftForDispute');
			$bill = $this->input->post('billForDispute');
			$note = $this->input->post('note');
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			$file = $_FILES["noteFile"]["name"];

			if(!empty($file)){
				$ext = pathinfo($file, PATHINFO_EXTENSION);
				$fileName = $login_id.date("YmdHis").".".$ext;

				move_uploaded_file($_FILES["noteFile"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/vslbilldisputedoc/".$file);			
				rename($_SERVER['DOCUMENT_ROOT']."/vslbilldisputedoc/".$file,$_SERVER['DOCUMENT_ROOT']."/vslbilldisputedoc/".$fileName);
			}

			$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DISPUTE")."(draftNumber,dispute_text,dispute_doc,entry_at,entry_by,entry_ip) VALUES('$draft','$note','$fileName',NOW(),'$login_id','$ipAddress')";

			if($this->bm->dataInsertDb2($insertQuery)){
				$updateQuery = "Update ".$this->Init_Table_Map("DETAILS")." set disputeraised = 1 WHERE draftNumber = '$draft'";
				if($this->bm->dataUpdatedb2($updateQuery)){
					$this->session->set_flashdata("success", "<div class='alert alert-success'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<font size='4'>Dispute Raised for <strong>{$bill}</strong></font></div>", 3);
					
				}
			}else{
				$this->session->set_flashdata("error", "<div class='alert alert-danger'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
				<font size='4'><strong>Failed!</strong> Please try again later... </font></div>", 3);
			}

			redirect('VesselBill/vesselBillListAcc/a/', 'refresh');
		}
	}

	// Added by Kawsar on 17 Oct 2022 - start


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

			$query = "Update ".$this->Init_Table_Map("DETAILS")." set acc_apprv_st = 1 , acc_apprv_at = NOW() , acc_apprv_by = '$login_id' where draftNumber = '$draft'";
			if($this->bm->dataUpdatedb2($query)){
				$msg = "ok";
			}
			
			// $getBillRot = "";
			
			// $msgRot = str_replace('/','_',$rotation);
			
			// $msgRot = "billReady_".$msgRot;
			
			redirect('VesselBill/vesselBillListAcc/p/'.$msg, 'refresh');
		}
	}

	function vslBillEditForm()
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
			$draft = $this->input->post('draftForEdit');
			$billNo = $this->input->post('billNo');
			$msg = "";

			$data['msg'] = $msg;
			$data['billNo'] = $billNo;
			$data['draft'] = $draft;
			$data['title'] = "EDIT VESSEL BILL";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vslBillEditForm',$data);			
			$this->load->view('jsAssetsList');	
		}
	}

	function vslBillEdit()
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
			$newBill = $this->input->post('newBill');
			$msg = "";

			$query = "Update ".$this->Init_Table_Map("DETAILS")." set finalNumber = '$newBill' WHERE draftNumber = '$draft'";
			if($this->bm->dataUpdatedb2($query)){
				$msg = "<font color='green'>Bill No. Updated</font>";
			}

			$data['msg'] = $msg;
			$data['draft'] = $draft;
			$data['title'] = "EDIT VESSEL BILL";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vslBillEditForm',$data);			
			$this->load->view('jsAssetsList');	
		}
	}
	
	function viewVesselBillAcc()
	{
		// $draftNumber = 2;
		$draftNumber = $this->uri->segment(3);
		$bill_type = $this->uri->segment(4);	// added				
		
		$bill_sql = "";
		
		// -- agent_code AS payeecustomerkey,	// old
		if($bill_type == 101)
		{
			// -- CONCAT(agent_code,'(',IFNULL(agent_alias_id,''),')') AS payeecustomerkey,	
			$bill_sql = "SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName,payeecustomerkey,agent_address,grossRevenueTons,
			exchangeRate,created,berth,flagcountry,cargo,ffd,description,glcode,rateBilled,quantityUnit,SUM(quantityBilled) AS quantityBilled,
			creator,
			ROUND(SUM(totusd),4) AS totusd,
			ROUND(SUM(vat),4) AS vat,
			ROUND(SUM(totbsd),2) AS totbsd,ROUND(SUM(vatbd),2) AS vatbd,STATUS,acc_apprv_by,oa_date,
			TIME(inboundpiloton) AS timeBerthFrom, 
			TIME(inboundpilotoff) AS timeBerthTo, 
			TIME(onboundpiloton) AS timeLeaveFrom, 
			TIME(onboundpilotoff) AS timeLeaveTo 
			FROM(
			SELECT bill_name AS invoiceDesc,
			
			IF((finalNumber IS NULL OR finalNumber=''),(IF(cnt_code='BD',CONCAT('JL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),
			CONCAT('JF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS draftNumber,
					
			vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,
			CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS ATD,
			CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ATA,			
			agent_name AS customerName,
			
			
			agent_alias_id AS payeecustomerkey,
			
			agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,
			
			CONCAT(DATE_FORMAT(billing_date,'%d/%m/%Y'),' ',TIME(billing_date)) AS created,
			berth AS berth,flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,description AS description,CONCAT(gl_code,'0') AS glcode,rate AS rateBilled,bas AS quantityUnit,
			IF(description LIKE 'BERTH_HIRE_1%',".$this->Init_Table_Map("DETAILS").".unit,".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot) AS quantityBilled,IF(description LIKE 'BERTH_HIRE_1%',((grt+IFNULL(deck_cargo,0))*rate*unit),(rate*".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot)) AS totusd,
			
			(SELECT IF(DATE(ata)>='2017-12-27',ROUND(totusd*15/100,4),0)) AS vat,
			
			creator,
								
			IF(description LIKE 'BERTH_HIRE_1%',((grt+IFNULL(deck_cargo,0))*rate*unit*exchangeRate),(rate*".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot*exchangeRate)) AS totbsd,
			(SELECT IF(DATE(ata)>='2017-12-27',ROUND(totusd*15/100,4),0)) AS vatusd,
			(SELECT vatusd*exchangeRate) AS vatbd,'DRAFT' AS STATUS,acc_apprv_by,
						
			CONCAT(DATE_FORMAT(oa_date,'%d/%m/%Y'),' ',TIME(oa_date)) AS oa_date,
			pilot_ob_onboard AS onboundpiloton,
			pilot_ob_offboard AS onboundpilotoff, 
			pilot_ib_onboard AS inboundpiloton,
			pilot_ib_offboard AS inboundpilotoff 
			
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type'
			ORDER BY draftNumber,description) AS tbl
			GROUP BY description";
		}
		else if($bill_type == 102)		// BILL FOR PORT & PILOTAGE CHARGES ON VESSEL
		{
			// -- CONCAT(agent_code,'(',IFNULL(agent_alias_id,''),')') AS payeecustomerkey,		
			
			$bill_sql="SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,
			
			DATE_FORMAT(inboundpiloton,'%d/%m/%Y') AS dateOfBerth, 			
			DATE_FORMAT(onboundpiloton,'%d/%m/%Y') AS dateOfLeave, 

			TIME(inboundpiloton) AS timeBerthFrom,
			TIME(inboundpilotoff) AS timeBerthTo,
	
			TIME(onboundpiloton) AS timeLeaveFrom,
			TIME(onboundpilotoff) AS timeLeaveTo,
			customerName,payeecustomerkey,agent_address,grossRevenueTons,exchangeRate,created,flagcountry,cargo,ffd,onboundpiloton,onboundpilotoff,inboundpiloton,inboundpilotoff,description,glcode,rateBilled,quantityUnit,IF(description LIKE 'Tug%' OR description='Additional Tug Charge for Unberthing',SUM(quantityBilled),quantityBilled) AS quantityBilled,IF(description ='PILOTAGE FEE' OR description ='Night Navigation Fee' OR description LIKE 'SHIFT%' OR description LIKE 'Additional Tug Charges for Shifting%',SUM(move),move) AS move,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(bdChraged),2) AS bdChraged,
			ROUND(SUM(vatusd),4) AS vatusd,
			ROUND((SUM(bdChraged)*15/100),2) AS bdVat,STATUS,creator,acc_apprv_by,oa_date
			FROM (
			SELECT bill_name AS invoiceDesc,
			
			IF((finalNumber IS NULL OR finalNumber=''),(IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS draftNumber,
			
			vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,
			CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS ATD,
			CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ATA,						
			agent_name AS customerName,
						
			agent_alias_id AS payeecustomerkey,
			
			agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,
			CONCAT(DATE_FORMAT(billing_date,'%d/%m/%Y'),' ',TIME(billing_date)) AS created,
			flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
			pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,description AS description,CONCAT(gl_code,'0') AS glcode,rate AS rateBilled,bas AS quantityUnit,
			unit_for_pilot AS quantityBilled,move,(rate*unit_for_pilot*move) AS totusd,
			(rate*unit_for_pilot*move*exchangeRate) AS bdChraged,
			'DRAFT' AS STATUS,creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,
			(SELECT IF(vtdt=1,((totusd*15)/100),IF((description='BERTHING' OR description='SHIFT VESSEL BERTH'),((totusd*15)/100),0)))  AS vatusd,
			acc_apprv_by,
						
			CONCAT(DATE_FORMAT(oa_date,'%d/%m/%Y'),' ',TIME(oa_date)) AS oa_date
			
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type') AS tbl
			GROUP BY description";
		}
		else if($bill_type == 106)		// 106 = Not Entering                     -- Sumon modified-- 09/03/2023
		{											
			$bill_sql="SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName,payeecustomerkey,agent_address,grossRevenueTons,exchangeRate,created,flagcountry,cargo,ffd,onboundpiloton,onboundpilotoff,inboundpiloton,inboundpilotoff,description,glcode,rateBilled,quantityUnit,IF(description LIKE 'Tug%' OR description='Additional Tug Charge for Unberthing',SUM(quantityBilled),quantityBilled) AS quantityBilled,IF(description ='PILOTAGE FEE' OR description ='Night Navigation Fee' OR description LIKE 'SHIFT%' OR description LIKE 'Additional Tug Charges for Shifting%',SUM(move),move) AS move,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(bdChraged),2) AS bdChraged,
			ROUND(SUM(vatusd),4) AS vatusd,
			ROUND((SUM(bdChraged)*15/100),2) AS bdVat,STATUS,creator,acc_apprv_by,oa_date 
			FROM 
			(SELECT bill_name AS invoiceDesc,
			
			IF((finalNumber IS NULL OR finalNumber=''),(IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS draftNumber,
			
			vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,
						
			CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS ATD,
			CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ATA,
			
			agent_name AS customerName,
			
			agent_alias_id AS payeecustomerkey,
			
			agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,
			CONCAT(DATE_FORMAT(billing_date,'%d/%m/%Y'),' ',TIME(billing_date)) AS created,
			flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
			pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,description AS description,CONCAT(gl_code,'0') AS glcode,
			rate AS rateBilled,bas AS quantityUnit,unit_for_pilot AS quantityBilled,move,(rate*unit_for_pilot*move) AS totusd,
			(rate*unit_for_pilot*move*exchangeRate) AS bdChraged,
			'DRAFT' AS STATUS,creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,		
			(((rate*unit_for_pilot*move)*15)/100) AS vatusd,acc_apprv_by,
						
			CONCAT(DATE_FORMAT(oa_date,'%d/%m/%Y'),' ',TIME(oa_date)) AS oa_date
			
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type') AS tbl
			GROUP BY description";
		}
		else if($bill_type == 107)		// 107 = Bhatiari           			  -- Sumon added-- 19/02/2023
		{											
			$bill_sql="SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName,payeecustomerkey,agent_address,grossRevenueTons,exchangeRate,created,flagcountry,cargo,ffd,onboundpiloton,onboundpilotoff,inboundpiloton,inboundpilotoff,description,glcode,rateBilled,quantityUnit,IF(description LIKE 'Tug%' OR description='Additional Tug Charge for Unberthing',SUM(quantityBilled),quantityBilled) AS quantityBilled,IF(description ='PILOTAGE FEE' OR description ='Night Navigation Fee' OR description LIKE 'SHIFT%' OR description LIKE 'Additional Tug Charges for Shifting%',SUM(move),move) AS move,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(bdChraged),2) AS bdChraged,
			ROUND(SUM(vatusd),4) AS vatusd,
			ROUND((SUM(bdChraged)*15/100),2) AS bdVat,STATUS,creator,acc_apprv_by,oa_date 
			FROM 
			(SELECT bill_name AS invoiceDesc,
			
			IF((finalNumber IS NULL OR finalNumber=''),(IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS draftNumber,
			
			vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,
						
			CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS ATD,
			CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ATA,
			
			agent_name AS customerName,
						
			agent_alias_id AS payeecustomerkey,
			
			agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,
			CONCAT(DATE_FORMAT(billing_date,'%d/%m/%Y'),' ',TIME(billing_date)) AS created,
			flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
			pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,description AS description,CONCAT(gl_code,'0') AS glcode,
			rate AS rateBilled,bas AS quantityUnit,unit_for_pilot AS quantityBilled,move,(rate*unit_for_pilot*move) AS totusd,
			(rate*unit_for_pilot*move*exchangeRate) AS bdChraged,
			'DRAFT' AS STATUS,creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,		
			(((rate*unit_for_pilot*move)*15)/100) AS vatusd,acc_apprv_by,
						
			CONCAT(DATE_FORMAT(oa_date,'%d/%m/%Y'),' ',TIME(oa_date)) AS oa_date
			
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type') AS tbl
			GROUP BY description";
		}
		else if($bill_type == 108)		// 108 = Kutubdia                      -- Sumon added-- 25/03/2023
		{											
			$bill_sql="SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName,payeecustomerkey,agent_address,grossRevenueTons,exchangeRate,created,flagcountry,cargo,ffd,onboundpiloton,onboundpilotoff,inboundpiloton,inboundpilotoff,description,glcode,rateBilled,quantityUnit,IF(description LIKE 'Tug%' OR description='Additional Tug Charge for Unberthing',SUM(quantityBilled),quantityBilled) AS quantityBilled,IF(description ='PILOTAGE FEE' OR description ='Night Navigation Fee' OR description LIKE 'SHIFT%' OR description LIKE 'Additional Tug Charges for Shifting%',SUM(move),move) AS move,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(bdChraged),2) AS bdChraged,
			ROUND(SUM(vatusd),4) AS vatusd,
			ROUND((SUM(bdChraged)*15/100),2) AS bdVat,STATUS,creator,acc_apprv_by,oa_date 
			FROM 
			(SELECT bill_name AS invoiceDesc,
			
			IF((finalNumber IS NULL OR finalNumber=''),(IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS draftNumber,
			
			vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,
						
			CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS ATD,
			CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ATA,
			
			agent_name AS customerName,
			
			agent_alias_id AS payeecustomerkey,
			
			agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,
			CONCAT(DATE_FORMAT(billing_date,'%d/%m/%Y'),' ',TIME(billing_date)) AS created,
			flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
			pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,description AS description,CONCAT(gl_code,'0') AS glcode,
			rate AS rateBilled,bas AS quantityUnit,unit_for_pilot AS quantityBilled,move,(rate*unit_for_pilot*move) AS totusd,
			(rate*unit_for_pilot*move*exchangeRate) AS bdChraged,
			'DRAFT' AS STATUS,creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,		
			(((rate*unit_for_pilot*move)*15)/100) AS vatusd,acc_apprv_by,
						
			CONCAT(DATE_FORMAT(oa_date,'%d/%m/%Y'),' ',TIME(oa_date)) AS oa_date
			
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type') AS tbl
			GROUP BY description";
		}
		else if($bill_type == 104)		// 104 = Firework	// check query               
		{											
			$bill_sql="SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName, berth, agent_code, agent_alias_id,payeecustomerkey,agent_address,
			grossRevenueTons,exchangeRate,created,flagcountry,cargo,ffd,onboundpiloton,onboundpilotoff,inboundpiloton,inboundpilotoff,description,glcode,rateBilled,quantityUnit,
			IF(description LIKE 'Tug%' OR description='Additional Tug Charge for Unberthing',SUM(quantityBilled),quantityBilled) AS quantityBilled,
			IF(description ='PILOTAGE FEE' OR description ='Night Navigation Fee' OR description LIKE 'SHIFT%' OR description LIKE 'Additional Tug Charges for Shifting%',SUM(move),move) AS move,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(bdChraged),2) AS bdChraged,
			ROUND(SUM(vatusd),4) AS vatusd,
			ROUND((SUM(bdChraged)*15/100),2) AS bdVat,STATUS,creator,acc_apprv_by,oa_date 
			FROM 
			(SELECT bill_name AS invoiceDesc,
			
			IF((finalNumber IS NULL OR finalNumber=''),(IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS draftNumber,
			
			vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,
						
			CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS ATD,
			CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ATA,
			
			agent_name AS customerName, berth, agent_code, agent_alias_id,
			
			agent_alias_id AS payeecustomerkey,
			
			agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,
			CONCAT(DATE_FORMAT(billing_date,'%d/%m/%Y'),' ',TIME(billing_date)) AS created,
			flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
			pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,description AS description,CONCAT(gl_code,'0') AS glcode,
			rate AS rateBilled,bas AS quantityUnit,unit_for_pilot AS quantityBilled,move,(rate*unit_for_pilot*move) AS totusd,
			(rate*unit_for_pilot*move*exchangeRate) AS bdChraged,
			'DRAFT' AS STATUS,creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,		
			(((rate*unit_for_pilot*move)*15)/100) AS vatusd,acc_apprv_by,
						
			DATE_FORMAT(oa_date,'%d/%m/%Y') AS oa_date			
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type') AS tbl
			GROUP BY description";
		}
		else if($bill_type == 105)		// 105 = WaterBill	 ---Modified  08/03/2023       --  Sumon           
		{
			
			//IF(vtdt=1,((SUM(bdChraged)*15)/100),IF((description='BERTHING' OR description='SHIFT VESSEL BERTH'),((bdChraged*15)/100),0)) AS bdVat,
			
			$bill_sql="SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName,payeecustomerkey,
			agent_alias_id,agent_address,grossRevenueTons,exchangeRate,created,flagcountry,cargo,ffd,onboundpiloton,onboundpilotoff,
			inboundpiloton,inboundpilotoff,description,glcode,rateBilled,quantityUnit,quantityBilled,SUM(move) AS move,
			 ROUND(SUM(vatusd),4) AS vatusd, ROUND((SUM(bdChraged)*15/100),2) AS bdVat, 
			SUM(bdChraged) AS bdChraged,			
			STATUS,creator,berth,water_supply_dt,oa_date,acc_apprv_by
			FROM 
			(
			SELECT water_supply_dt, bill_name AS invoiceDesc,berth AS berth,IFNULL(finalNumber,IF(cnt_code='BD' OR cnt_code='PBD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTR(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTR(billing_date,4,1)))) AS draftNumber,vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,atd AS ATD,ata AS ATA,agent_name AS customerName,agent_alias_id,CONCAT(agent_code,'(',IFNULL(agent_alias_id,''),')') AS payeecustomerkey,agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,billing_date AS created,flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,
			pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
			pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,IF(description='Port Dues for Sea-going Vessel',SUBSTR(description,1,9),description) AS description,CONCAT(gl_code,'0') AS glcode,rate AS rateBilled,bas AS quantityUnit,unit_for_pilot AS quantityBilled,move, (((rate*unit_for_pilot*move)*15)/100) AS vatusd,
           (rate*unit_for_pilot*move) AS bdChraged,'DRAFT' AS STATUS,acc_apprv_by,IF(finalNumber IS NULL,creator,drft_update_by) AS creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,
			CONCAT(DATE_FORMAT(oa_date,'%d/%m/%Y'),' ',TIME(oa_date)) AS oa_date
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type=105 AND description NOT LIKE '%BIWTA%') AS tbl
			GROUP BY description";
		}
		
		else if($bill_type == 109)		// 109 = Beaching  							-Sumon---  07/03/2023
		{											
			$bill_sql="SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName,payeecustomerkey,agent_address,grossRevenueTons,exchangeRate,created,flagcountry,cargo,ffd,onboundpiloton,onboundpilotoff,inboundpiloton,inboundpilotoff,description,glcode,rateBilled,quantityUnit,IF(description LIKE 'Tug%' OR description='Additional Tug Charge for Unberthing',SUM(quantityBilled),quantityBilled) AS quantityBilled,IF(description ='PILOTAGE FEE' OR description ='Night Navigation Fee' OR description LIKE 'SHIFT%' OR description LIKE 'Additional Tug Charges for Shifting%',SUM(move),move) AS move,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(bdChraged),2) AS bdChraged,
			ROUND(SUM(vatusd),4) AS vatusd,
			ROUND((SUM(bdChraged)*15/100),2) AS bdVat,STATUS,creator,acc_apprv_by,oa_date 
			FROM 
			(SELECT bill_name AS invoiceDesc,
			
			IF((finalNumber IS NULL OR finalNumber=''),(IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS draftNumber,
			
			vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,
						
			CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS ATD,
			CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ATA,
			
			agent_name AS customerName,
						
			agent_alias_id AS payeecustomerkey,
			
			agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,
			CONCAT(DATE_FORMAT(billing_date,'%d/%m/%Y'),' ',TIME(billing_date)) AS created,
			flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
			pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,description AS description,CONCAT(gl_code,'0') AS glcode,
			rate AS rateBilled,bas AS quantityUnit,unit_for_pilot AS quantityBilled,move,(rate*unit_for_pilot*move) AS totusd,
			(rate*unit_for_pilot*move*exchangeRate) AS bdChraged,
			'DRAFT' AS STATUS,creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,		
			(((rate*unit_for_pilot*move)*15)/100) AS vatusd,acc_apprv_by,
						
			CONCAT(DATE_FORMAT(oa_date,'%d/%m/%Y'),' ',TIME(oa_date)) AS oa_date
			
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type') AS tbl
			GROUP BY description";
		}
		
		else if($bill_type == 110)		// 110 = Tug Cancellation 						--Sumon---  08/03/2023
		{											
			$bill_sql="SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName,payeecustomerkey,agent_address,grossRevenueTons,exchangeRate,created,flagcountry,cargo,ffd,onboundpiloton,onboundpilotoff,inboundpiloton,inboundpilotoff,description,glcode,rateBilled,quantityUnit,IF(description LIKE 'Tug%' OR description='Additional Tug Charge for Unberthing',SUM(quantityBilled),quantityBilled) AS quantityBilled,IF(description ='PILOTAGE FEE' OR description ='Night Navigation Fee' OR description LIKE 'SHIFT%' OR description LIKE 'Additional Tug Charges for Shifting%',SUM(move),move) AS move,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(bdChraged),2) AS bdChraged,
			ROUND(SUM(vatusd),4) AS vatusd,
			ROUND((SUM(bdChraged)*15/100),2) AS bdVat,STATUS,creator,acc_apprv_by,oa_date 
			FROM 
			(SELECT bill_name AS invoiceDesc,
			
			IF((finalNumber IS NULL OR finalNumber=''),(IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS draftNumber,
			
			vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,
						
			CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS ATD,
			CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ATA,
			
			agent_name AS customerName,
						
			agent_alias_id AS payeecustomerkey,
			
			agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,
			CONCAT(DATE_FORMAT(billing_date,'%d/%m/%Y'),' ',TIME(billing_date)) AS created,
			flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
			pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,description AS description,CONCAT(gl_code,'0') AS glcode,
			rate AS rateBilled,bas AS quantityUnit,unit_for_pilot AS quantityBilled,move,(rate*unit_for_pilot*move) AS totusd,
			(rate*unit_for_pilot*move*exchangeRate) AS bdChraged,
			'DRAFT' AS STATUS,creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,		
			(((rate*unit_for_pilot*move)*15)/100) AS vatusd,acc_apprv_by,
						
			CONCAT(DATE_FORMAT(oa_date,'%d/%m/%Y'),' ',TIME(oa_date)) AS oa_date
			
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type') AS tbl
			GROUP BY description";
		}
		//echo $bill_sql;
		//return;
		$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";
			
		$bill_rslt=$this->bm->dataSelectDb2($bill_sql);

		if(count($bill_rslt)==0)
		{
			echo "wrong draft";
			return;
		}	
		
		$print_time=$this->bm->dataSelectDb2($bill_print_time);
		
		$this->data['bill_rslt']=$bill_rslt;
		$this->data['print_time']=$print_time;
		
		$this->load->library('m_pdf');
		
		
		// bill op and acc name 
		$sql_billOpName = "SELECT u_name FROM users WHERE login_id='".$bill_rslt[0]['creator']."'";
		$rslt_billOpName = $this->bm->dataSelectDb1($sql_billOpName);
		
		$billOpName = "";
		
		for($i=0;$i<count($rslt_billOpName);$i++)
		{
			$billOpName = $rslt_billOpName[$i]['u_name'];
		}
		$this->data['billOpName']=$billOpName;
		
		$sql_aprvByName = "SELECT u_name FROM users WHERE login_id='".$bill_rslt[0]['acc_apprv_by']."'";
		$rslt_aprvByName = $this->bm->dataSelectDb1($sql_aprvByName);
		
		$aprvByName = "";
		
		for($i=0;$i<count($rslt_aprvByName);$i++)
		{
			$aprvByName = $rslt_aprvByName[$i]['u_name'];
		}
		$this->data['aprvByName']=$aprvByName;
		//--
		
		$pdfFilePath = "";
		if($bill_type == 101)         // 101 = Jetty / QGC CHARGES ON VESSEL
		{
			$html=$this->load->view('Vessel/vesselBill_JettyChargesOnVessel',$this->data, true);	
			$pdfFilePath ="Jetty Bill-".time()."-download.pdf";
		}
		else if($bill_type == 102)		// 102 = BILL FOR PORT & PILOTAGE CHARGES ON VESSEL
		{
			$html=$this->load->view('Vessel/vesselBill_BillForPortAndPilotageChargesOnVessel',$this->data, true);			
			$pdfFilePath ="Pilotage Bill-".time()."-download.pdf";
		}
		else if($bill_type == 106)		// 106 = Not Entering
		{
			$html=$this->load->view('Vessel/vesselBillIbnoundOnly',$this->data, true);			 
			$pdfFilePath ="Not Entering Bill-".time()."-download.pdf";
		}
		else if($bill_type == 107)		// 107 = Bhatiari
		{
			$html=$this->load->view('Vessel/vesselBillI_Bhatiari',$this->data, true);			 
			$pdfFilePath ="Bhatiari Vessel Bill-".time()."-download.pdf";
		}
		else if($bill_type == 108)		// 108 = Kutubdia
		{
			$html=$this->load->view('Vessel/vesselBillI_Kutubdia',$this->data, true);			 
			$pdfFilePath ="Kutubdia Vessel Bill-".time()."-download.pdf";
		}
		else if($bill_type == 104)		// 104 = Firework	// check query
		{
			$html=$this->load->view('Vessel/vesselBill_Fire',$this->data, true);			 
			$pdfFilePath ="Fireman Bill-".time()."-download.pdf";
		}
		else if($bill_type == 105)		// 105 = WaterBill	// check query
		{			
			$html=$this->load->view('Vessel/vesselBill_Water',$this->data, true);			 
			$pdfFilePath ="Water Bill-".time()."-download.pdf";
		}
		else if($bill_type == 109)		// 109 = Beaching 
		{
			$html=$this->load->view('Vessel/vesselBill_Beaching',$this->data, true);			 
			$pdfFilePath ="Beaching Vessel Bill-".time()."-download.pdf";
		}
		else if($bill_type == 110)		// 110 = Tug Cancellation 
		{
			$html=$this->load->view('Vessel/vesselBill_TugCancellation',$this->data, true);			 
			$pdfFilePath ="Tug Cancellation Bill-".time()."-download.pdf";
		}
		
		// $pdfFilePath ="Vessel Bill-".time()."-download.pdf";
		$pdf = $this->m_pdf->load();
		// $pdf->allow_charset_conversion = true;
		// $pdf->charset_in = 'iso-8859-4';
				
		//$stylesheet = file_get_contents('assets/stylesheets/billView.css');				
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);				 
		$pdf->Output($pdfFilePath, "I");
	}
	
	
	// Vessel bill delete - start
	// Vessel bill delete - start
	function vslBillDeleteMain($draftForDelete)
	{
		// first check if bill exists
		$sql_chkDraft = "SELECT COUNT(*) AS rtnValue FROM ".$this->Init_Table_Map("DETAILS")." WHERE draftNumber='$draftForDelete'";
		$chkDraft = $this->bm->dataReturn($sql_chkDraft);			
		
		$msg = "";
		if($chkDraft==0)
		{
			$msg = "<font color='red'>This bill does not exist</font>";
		}
		else
		{
			$msg = "bill exists";
			
			// if exists write log for mis_vsl_billing_detail
			$sql_detailLogInfo = "SELECT draftNumber,finalNumber,rotation,vsl_name,ata,atd,berth,agent_code,agent_name,oa_date,flag,cnt_code,grt,master_name,deck_cargo,exchangeRate,
			unit,berth_suffix,bill_type,bill_name,pilot_ib_onboard,pilot_ib_offboard,pilot_ob_onboard,pilot_ob_offboard,billing_date,creator,
			ip_address,paid_status,transaction_id,disputeraised,agent_alias_id,agent_address,drft_update_by,drft_update_time,water_supply_dt,acc_apprv_st,
			acc_apprv_at,acc_apprv_by
			FROM ".$this->Init_Table_Map("DETAILS")."
			WHERE draftNumber='$draftForDelete'";
			$rslt_detailLogInfo = $this->bm->dataSelect($sql_detailLogInfo);
			
			$insertDtlLog=0;
			for($i=0;$i<count($rslt_detailLogInfo);$i++)
			{
				$draftNumberDtl = $rslt_detailLogInfo[$i]['draftNumber'];
				$finalNumber = $rslt_detailLogInfo[$i]['finalNumber'];
				$rotation = $rslt_detailLogInfo[$i]['rotation'];
				$vsl_name = $rslt_detailLogInfo[$i]['vsl_name'];
				$ata = $rslt_detailLogInfo[$i]['ata'];
				$atd = $rslt_detailLogInfo[$i]['atd'];
				$berth = $rslt_detailLogInfo[$i]['berth'];
				$agent_code = $rslt_detailLogInfo[$i]['agent_code'];
				$agent_name = $rslt_detailLogInfo[$i]['agent_name'];
				$oa_date = $rslt_detailLogInfo[$i]['oa_date'];
				$flag = $rslt_detailLogInfo[$i]['flag'];
				$cnt_code = $rslt_detailLogInfo[$i]['cnt_code'];
				$grt = $rslt_detailLogInfo[$i]['grt'];
				$master_name = $rslt_detailLogInfo[$i]['master_name'];
				$deck_cargo = $rslt_detailLogInfo[$i]['deck_cargo'];
				$exchangeRate = $rslt_detailLogInfo[$i]['exchangeRate'];
				$unit = $rslt_detailLogInfo[$i]['unit'];
				$berth_suffix = $rslt_detailLogInfo[$i]['berth_suffix'];
				$bill_type = $rslt_detailLogInfo[$i]['bill_type'];
				$bill_name = $rslt_detailLogInfo[$i]['bill_name'];
				$pilot_ib_onboard = $rslt_detailLogInfo[$i]['pilot_ib_onboard'];
				$pilot_ib_offboard = $rslt_detailLogInfo[$i]['pilot_ib_offboard'];
				$pilot_ob_onboard = $rslt_detailLogInfo[$i]['pilot_ob_onboard'];
				$pilot_ob_offboard = $rslt_detailLogInfo[$i]['pilot_ob_offboard'];
				$billing_date = $rslt_detailLogInfo[$i]['billing_date'];
				$creator = $rslt_detailLogInfo[$i]['creator'];
				$ip_address = $rslt_detailLogInfo[$i]['ip_address'];
				$paid_status = $rslt_detailLogInfo[$i]['paid_status'];
				$transaction_id = $rslt_detailLogInfo[$i]['transaction_id'];
				$disputeraised = $rslt_detailLogInfo[$i]['disputeraised'];
				$agent_alias_id = $rslt_detailLogInfo[$i]['agent_alias_id'];
				$agent_address = $rslt_detailLogInfo[$i]['agent_address'];
				$drft_update_by = $rslt_detailLogInfo[$i]['drft_update_by'];
				$drft_update_time = $rslt_detailLogInfo[$i]['drft_update_time'];
				$water_supply_dt = $rslt_detailLogInfo[$i]['water_supply_dt'];
				$acc_apprv_st = $rslt_detailLogInfo[$i]['acc_apprv_st'];
				$acc_apprv_at = $rslt_detailLogInfo[$i]['acc_apprv_at'];
				$acc_apprv_by = $rslt_detailLogInfo[$i]['acc_apprv_by']; //38
									
				$sql_insertDtlLog = "INSERT INTO ctmsmis.mis_vsl_billing_detail_delete_log(draftNumber,finalNumber,rotation,vsl_name,ata,atd,berth,agent_code,agent_name,oa_date,flag,
				cnt_code,grt,master_name,deck_cargo,exchangeRate,unit,berth_suffix,bill_type,bill_name,pilot_ib_onboard,pilot_ib_offboard,
				pilot_ob_onboard,pilot_ob_offboard,billing_date,creator,ip_address,paid_status,transaction_id,disputeraised,agent_alias_id,agent_address,
				drft_update_by,drft_update_time,water_supply_dt,acc_apprv_st,acc_apprv_at,acc_apprv_by,delete_dt,delete_by,delete_ip)
				VALUES('$draftNumberDtl','$finalNumber','$rotation','$vsl_name','$ata','$atd','$berth','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt','$master_name','$deck_cargo','$exchangeRate','$unit','$berth_suffix','$bill_type','$bill_name','$pilot_ib_onboard','$pilot_ib_offboard','$pilot_ob_onboard','$pilot_ob_offboard','$billing_date','$creator','$ip_address','$paid_status','$transaction_id','$disputeraised','$agent_alias_id','$agent_address','$drft_update_by','$drft_update_time','$water_supply_dt','$acc_apprv_st',
				'$acc_apprv_at','$acc_apprv_by',NOW(),'$login_id','$ipAddress')";
				$insertDtlLog = $this->bm->dataInsert($sql_insertDtlLog);
				
				$sql_vvdGkey = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey AS rtnValue
				FROM sparcsn4.vsl_vessel_visit_details
				WHERE ib_vyg='$rotation'";
				$vvdGkey = $this->bm->dataReturn($sql_vvdGkey);
				

				if($bill_type=='104')
				{
					$fireDemandUpdateStr="UPDATE ctmsmis.hotwork_demand SET hotwork_demand.bill_op_bill_st=0 WHERE hotwork_demand.rotation='$rotation'";
					$this->bm->dataUpdate($fireDemandUpdateStr);
				}	

				else if($bill_type=='105')
				{
					 $waterDemandUpdateStr="UPDATE ctmsmis.water_demand_info SET 					
					water_demand_info.bill_op_bill_st=0, 
					water_demand_info.bill_op_bill_ip='',
					water_demand_info.bill_op_bill_at=''
					WHERE water_demand_info.rotation_no='$rotation'";
					$this->bm->dataUpdate($waterDemandUpdateStr);
				}				
				else if($bill_type=='110')
				{
					$sql_update_cancellation_str= "UPDATE ctmsmis.vsl_cancelation_forward_info
					SET bill_op_bill_st='0',bill_op_bill_by='',bill_op_bill_at='',bill_op_bill_ip=''
					WHERE vvd_gkey='$vvdGkey'";
					$rslt_updateFireInfo = $this->bm->dataUpdate($sql_updateFireInfo);
				}
				else
				{
					$sql_updateFrwdInfo = "UPDATE ctmsmis.vsl_forward_info
					SET ctmsmis.vsl_forward_info.billop_bill_stat='0',
					ctmsmis.vsl_forward_info.billop_bill_at='',
					ctmsmis.vsl_forward_info.billop_bill_by='',
					ctmsmis.vsl_forward_info.billop_bill_ip=''
					WHERE ctmsmis.vsl_forward_info.vvd_gkey='$vvdGkey'";
					$this->bm->dataUpdate($sql_updateFrwdInfo);
				}
				
				// write code for not entering vessel
			}
			
			// if exists write log for mis_vsl_billing_sub_detail
			$sql_subDtlLogInfo = "SELECT draftNumber,description,gl_code,rate,bas,unit_for_pilot,move,currency_gkey,update_by,update_time
			FROM ".$this->Init_Table_Map("SUB_DETAILS")."
			WHERE draftNumber='$draftForDelete'";
			// echo $sql_subDtlLogInfo;return;
			$rslt_subDtlLogInfo = $this->bm->dataSelect($sql_subDtlLogInfo);
			
			for($i=0;$i<count($rslt_subDtlLogInfo);$i++)
			{
				$draftNumberSubDtl = $rslt_subDtlLogInfo[$i]['draftNumber'];
				$description = $rslt_subDtlLogInfo[$i]['description'];
				$gl_code = $rslt_subDtlLogInfo[$i]['gl_code'];
				$rate = $rslt_subDtlLogInfo[$i]['rate'];
				$bas = $rslt_subDtlLogInfo[$i]['bas'];
				$unit_for_pilot = $rslt_subDtlLogInfo[$i]['unit_for_pilot'];
				$move = $rslt_subDtlLogInfo[$i]['move'];
				$currency_gkey = $rslt_subDtlLogInfo[$i]['currency_gkey'];
				$update_by = $rslt_subDtlLogInfo[$i]['update_by'];
				$update_time = $rslt_subDtlLogInfo[$i]['update_time'];
				
				$sql_insertSubDtlLog = "INSERT INTO ctmsmis.mis_vsl_billing_sub_detail_delete_log(draftNumber,description,gl_code,rate,bas,unit_for_pilot,move,currency_gkey,update_by,
				update_time,delete_dt,delete_by,delete_ip)
				VALUES('$draftNumberSubDtl','$description','$gl_code','$rate','$bas','$unit_for_pilot','$move','$currency_gkey','$update_by',
				'$update_time',NOW(),'$login_id','$ipAddress')";
				$insertSubDtlLog = $this->bm->dataInsert($sql_insertSubDtlLog);
			}
							
			// if both log write is successfull, delete from mis_vsl_billing_detail and mis_vsl_billing_sub_detail
			
			$sql_deleteDtl = "DELETE FROM ".$this->Init_Table_Map("DETAILS")." WHERE draftNumber='$draftForDelete'";
			$rslt_deleteDtl = $this->bm->dataDelete($sql_deleteDtl);
			
			$sql_deleteSubDtl = "DELETE FROM ".$this->Init_Table_Map("SUB_DETAILS")." WHERE draftNumber='$draftForDelete'";
			$rslt_deleteSubDtl = $this->bm->dataDelete($sql_deleteSubDtl);

			if($rslt_deleteDtl==1 and $rslt_deleteSubDtl==1)
			{
				// $msg = "<font color='green'>Bill deleted successfull</font>";
				$msg = "<font color='green'><b>Bill deleted successfully</b></font>";
			}
			else
			{
				// $msg = "<font color='red'>Failed. Try again.</font>";					
				$msg = "<font color='red'><b>Failed. Try again.</b></font>";
			}
		}		// bill exists - end
	}
	
	
	// function vslBillDelete()
	function vslBillDelete($draftForDelete=null)
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
			$section = $this->session->userdata('section');
			$login_id = $this->session->userdata('login_id');

			// $draftForDelete=$this->input->post('draftForDelete');
		
			if($draftForDelete == null or $draftForDelete == "")
				$draftForDelete=$this->input->post('draftForDelete');
			
			// call delete operation function
			$this->vslBillDeleteMain($draftForDelete);
			
			// $this->vesselBillListAcc($msg);
			
			$cond = "";
			if($section == "billop")
			{
				$cond = " AND creator='$login_id'";								
			}
			
			$title = "VESSEL BILL LIST (Pending)";
			$action = "p";
 
			$sql_bill_list="SELECT draftNumber,IFNULL(finalNumber,draftNumber) AS finalNumber,
			
			IF(finalNumber IS NULL OR finalNumber='',
			(IF(cnt_code='BD',CONCAT(if(bill_type='101','JL/','PL/'),".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT(if(bill_type='101','JF/','PF/'),".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS billNumber,
			
			rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st,creator
			FROM ".$this->Init_Table_Map("DETAILS")."
			WHERE acc_apprv_st = 0 ".$cond." ORDER BY draftNumber DESC";
			
			$rslt_bill_list=$this->bm->dataSelect($sql_bill_list);						
			
			$data['rslt_bill_list']=$rslt_bill_list;
			// $data['start']=$start;billNumber/creator;
			// $data["links"] = $this->pagination->create_links();
			$data['title'] = $title;
			$data['section'] = $section;
			$data['action'] = $action;
			$data['msg'] = $msg;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselBillListAcc',$data);			
			$this->load->view('jsAssetsList');		 
		}
	}
	
	// backup
	/* function vslBillDelete($draftForDelete=null)
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
			$section = $this->session->userdata('section');
			$login_id = $this->session->userdata('login_id');

			// $draftForDelete=$this->input->post('draftForDelete');
		
			if($draftForDelete == null or $draftForDelete == "")
				$draftForDelete=$this->input->post('draftForDelete');
			
			// first check if bill exists
			$sql_chkDraft = "SELECT COUNT(*) AS rtnValue FROM ".$this->Init_Table_Map("DETAILS")." WHERE draftNumber='$draftForDelete'";
			$chkDraft = $this->bm->dataReturn($sql_chkDraft);			
			
			$msg = "";
			if($chkDraft==0)
			{
				$msg = "<font color='red'>This bill does not exist</font>";
			}
			else
			{
				$msg = "bill exists";
				
				// if exists write log for mis_vsl_billing_detail
				$sql_detailLogInfo = "SELECT draftNumber,finalNumber,rotation,vsl_name,ata,atd,berth,agent_code,agent_name,oa_date,flag,cnt_code,grt,master_name,deck_cargo,exchangeRate,
				unit,berth_suffix,bill_type,bill_name,pilot_ib_onboard,pilot_ib_offboard,pilot_ob_onboard,pilot_ob_offboard,billing_date,creator,
				ip_address,paid_status,transaction_id,disputeraised,agent_alias_id,agent_address,drft_update_by,drft_update_time,water_supply_dt,acc_apprv_st,
				acc_apprv_at,acc_apprv_by
				FROM ".$this->Init_Table_Map("DETAILS")."
				WHERE draftNumber='$draftForDelete'";
				$rslt_detailLogInfo = $this->bm->dataSelect($sql_detailLogInfo);
				
				$insertDtlLog=0;
				for($i=0;$i<count($rslt_detailLogInfo);$i++)
				{
					$draftNumberDtl = $rslt_detailLogInfo[$i]['draftNumber'];
					$finalNumber = $rslt_detailLogInfo[$i]['finalNumber'];
					$rotation = $rslt_detailLogInfo[$i]['rotation'];
					$vsl_name = $rslt_detailLogInfo[$i]['vsl_name'];
					$ata = $rslt_detailLogInfo[$i]['ata'];
					$atd = $rslt_detailLogInfo[$i]['atd'];
					$berth = $rslt_detailLogInfo[$i]['berth'];
					$agent_code = $rslt_detailLogInfo[$i]['agent_code'];
					$agent_name = $rslt_detailLogInfo[$i]['agent_name'];
					$oa_date = $rslt_detailLogInfo[$i]['oa_date'];
					$flag = $rslt_detailLogInfo[$i]['flag'];
					$cnt_code = $rslt_detailLogInfo[$i]['cnt_code'];
					$grt = $rslt_detailLogInfo[$i]['grt'];
					$master_name = $rslt_detailLogInfo[$i]['master_name'];
					$deck_cargo = $rslt_detailLogInfo[$i]['deck_cargo'];
					$exchangeRate = $rslt_detailLogInfo[$i]['exchangeRate'];
					$unit = $rslt_detailLogInfo[$i]['unit'];
					$berth_suffix = $rslt_detailLogInfo[$i]['berth_suffix'];
					$bill_type = $rslt_detailLogInfo[$i]['bill_type'];
					$bill_name = $rslt_detailLogInfo[$i]['bill_name'];
					$pilot_ib_onboard = $rslt_detailLogInfo[$i]['pilot_ib_onboard'];
					$pilot_ib_offboard = $rslt_detailLogInfo[$i]['pilot_ib_offboard'];
					$pilot_ob_onboard = $rslt_detailLogInfo[$i]['pilot_ob_onboard'];
					$pilot_ob_offboard = $rslt_detailLogInfo[$i]['pilot_ob_offboard'];
					$billing_date = $rslt_detailLogInfo[$i]['billing_date'];
					$creator = $rslt_detailLogInfo[$i]['creator'];
					$ip_address = $rslt_detailLogInfo[$i]['ip_address'];
					$paid_status = $rslt_detailLogInfo[$i]['paid_status'];
					$transaction_id = $rslt_detailLogInfo[$i]['transaction_id'];
					$disputeraised = $rslt_detailLogInfo[$i]['disputeraised'];
					$agent_alias_id = $rslt_detailLogInfo[$i]['agent_alias_id'];
					$agent_address = $rslt_detailLogInfo[$i]['agent_address'];
					$drft_update_by = $rslt_detailLogInfo[$i]['drft_update_by'];
					$drft_update_time = $rslt_detailLogInfo[$i]['drft_update_time'];
					$water_supply_dt = $rslt_detailLogInfo[$i]['water_supply_dt'];
					$acc_apprv_st = $rslt_detailLogInfo[$i]['acc_apprv_st'];
					$acc_apprv_at = $rslt_detailLogInfo[$i]['acc_apprv_at'];
					$acc_apprv_by = $rslt_detailLogInfo[$i]['acc_apprv_by']; //38
										
					$sql_insertDtlLog = "INSERT INTO ctmsmis.mis_vsl_billing_detail_delete_log(draftNumber,finalNumber,rotation,vsl_name,ata,atd,berth,agent_code,agent_name,oa_date,flag,
					cnt_code,grt,master_name,deck_cargo,exchangeRate,unit,berth_suffix,bill_type,bill_name,pilot_ib_onboard,pilot_ib_offboard,
					pilot_ob_onboard,pilot_ob_offboard,billing_date,creator,ip_address,paid_status,transaction_id,disputeraised,agent_alias_id,agent_address,
					drft_update_by,drft_update_time,water_supply_dt,acc_apprv_st,acc_apprv_at,acc_apprv_by,delete_dt,delete_by,delete_ip)
					VALUES('$draftNumberDtl','$finalNumber','$rotation','$vsl_name','$ata','$atd','$berth','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt','$master_name','$deck_cargo','$exchangeRate','$unit','$berth_suffix','$bill_type','$bill_name','$pilot_ib_onboard','$pilot_ib_offboard','$pilot_ob_onboard','$pilot_ob_offboard','$billing_date','$creator','$ip_address','$paid_status','$transaction_id','$disputeraised','$agent_alias_id','$agent_address','$drft_update_by','$drft_update_time','$water_supply_dt','$acc_apprv_st',
					'$acc_apprv_at','$acc_apprv_by',NOW(),'$login_id','$ipAddress')";
					$insertDtlLog = $this->bm->dataInsert($sql_insertDtlLog);
					
					$sql_vvdGkey = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey AS rtnValue
					FROM sparcsn4.vsl_vessel_visit_details
					WHERE ib_vyg='$rotation'";
					$vvdGkey = $this->bm->dataReturn($sql_vvdGkey);
					
					$sql_updateFrwdInfo = "UPDATE ctmsmis.vsl_forward_info
					SET ctmsmis.vsl_forward_info.billop_bill_stat='0',
					ctmsmis.vsl_forward_info.billop_bill_at='',
					ctmsmis.vsl_forward_info.billop_bill_by='',
					ctmsmis.vsl_forward_info.billop_bill_ip=''
					WHERE ctmsmis.vsl_forward_info.vvd_gkey='$vvdGkey'";
					$this->bm->dataUpdate($sql_updateFrwdInfo);
					
					// write code for not entering vessel
				}
				
				// if exists write log for mis_vsl_billing_sub_detail
				$sql_subDtlLogInfo = "SELECT draftNumber,description,gl_code,rate,bas,unit_for_pilot,move,currency_gkey,update_by,update_time
				FROM ".$this->Init_Table_Map("SUB_DETAILS")."
				WHERE draftNumber='$draftForDelete'";
				// echo $sql_subDtlLogInfo;return;
				$rslt_subDtlLogInfo = $this->bm->dataSelect($sql_subDtlLogInfo);
				
				for($i=0;$i<count($rslt_subDtlLogInfo);$i++)
				{
					$draftNumberSubDtl = $rslt_subDtlLogInfo[$i]['draftNumber'];
					$description = $rslt_subDtlLogInfo[$i]['description'];
					$gl_code = $rslt_subDtlLogInfo[$i]['gl_code'];
					$rate = $rslt_subDtlLogInfo[$i]['rate'];
					$bas = $rslt_subDtlLogInfo[$i]['bas'];
					$unit_for_pilot = $rslt_subDtlLogInfo[$i]['unit_for_pilot'];
					$move = $rslt_subDtlLogInfo[$i]['move'];
					$currency_gkey = $rslt_subDtlLogInfo[$i]['currency_gkey'];
					$update_by = $rslt_subDtlLogInfo[$i]['update_by'];
					$update_time = $rslt_subDtlLogInfo[$i]['update_time'];
					
					$sql_insertSubDtlLog = "INSERT INTO ctmsmis.mis_vsl_billing_sub_detail_delete_log(draftNumber,description,gl_code,rate,bas,unit_for_pilot,move,currency_gkey,update_by,
					update_time,delete_dt,delete_by,delete_ip)
					VALUES('$draftNumberSubDtl','$description','$gl_code','$rate','$bas','$unit_for_pilot','$move','$currency_gkey','$update_by',
					'$update_time',NOW(),'$login_id','$ipAddress')";
					$insertSubDtlLog = $this->bm->dataInsert($sql_insertSubDtlLog);
				}
								
				// if both log write is successfull, delete from mis_vsl_billing_detail and mis_vsl_billing_sub_detail
				
				$sql_deleteDtl = "DELETE FROM ".$this->Init_Table_Map("DETAILS")." WHERE draftNumber='$draftForDelete'";
				$rslt_deleteDtl = $this->bm->dataDelete($sql_deleteDtl);
				
				$sql_deleteSubDtl = "DELETE FROM ".$this->Init_Table_Map("SUB_DETAILS")." WHERE draftNumber='$draftForDelete'";
				$rslt_deleteSubDtl = $this->bm->dataDelete($sql_deleteSubDtl);

				if($rslt_deleteDtl==1 and $rslt_deleteSubDtl==1)
				{
					// $msg = "<font color='green'>Bill deleted successfull</font>";
					$msg = "<font color='green'><b>Bill deleted successfully</b></font>";
				}
				else
				{
					// $msg = "<font color='red'>Failed. Try again.</font>";					
					$msg = "<font color='red'><b>Failed. Try again.</b></font>";
				}
			}		// bill exists - end
			
			// $this->vesselBillListAcc($msg);
			
			$cond = "";
			if($section == "billop")
			{
				$cond = " AND creator='$login_id'";								
			}
			
			$title = "VESSEL BILL LIST (Pending)";
			$action = "p";
 
			$sql_bill_list="SELECT draftNumber,IFNULL(finalNumber,draftNumber) AS finalNumber,rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st
			FROM ".$this->Init_Table_Map("DETAILS")."
			WHERE acc_apprv_st = 0 ".$cond." ORDER BY draftNumber DESC";
			
			$rslt_bill_list=$this->bm->dataSelect($sql_bill_list);						
			
			$data['rslt_bill_list']=$rslt_bill_list;
			// $data['start']=$start;
			// $data["links"] = $this->pagination->create_links();
			$data['title'] = $title;
			$data['section'] = $section;
			$data['action'] = $action;
			$data['msg'] = $msg;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselBillListAcc',$data);			
			$this->load->view('jsAssetsList');		
		}
	} */
	
	// Vessel bill delete - end
	
	// Bank Statement - start
	function bankStatementForm()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		$this->session->set_userdata(array('menu' => "bill"));
		$this->session->set_userdata(array('sub_menu' => "bankStatementForm"));
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "BANK STATEMENT";
			$msg = "";
			
			$data['title'] = $title;
			$data['msg'] = $msg;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/bankStatementForm',$data);			
			$this->load->view('jsAssetsList');			
		}
	}		
	
	function bankStatement()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$bankStatementDate = $this->input->post('bankStatementDate');
			$bankStatementFlag = $this->input->post('bankStatementFlag');
			
			$sql_bankStatement = "";
			$statementView = "";
			$filePath = "";
			
			if($bankStatementFlag == "L")
			{
				$sql_bankStatement = "SELECT IF(bill_type IN(101,103),101,102) AS bill_type,draftNumber,DATE(billing_date) AS billing_date,vsl_name,DATE(ata) AS ata,agent_name,flag,SUM(usd) AS usd,SUM(totbsd) AS totbsd,SUM(vat) AS vat,CONCAT('FOR THE MONTH OF ',UPPER(MONTHNAME('$bankStatementDate')),' ',YEAR('$bankStatementDate')) AS month_name
				FROM 
				(
				SELECT IFNULL(finalNumber,IF(cnt_code='BD',CONCAT('JL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'),
				CONCAT('JF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'))) AS draftNumber,
				billing_date,vsl_name,ata,agent_name,UPPER(flag) AS flag,
				(rate*unit_for_pilot) AS usd,(rate*unit_for_pilot*exchangeRate)AS totbsd,0 AS vat,bill_type,
				".$this->Init_Table_Map("DETAILS").".draftNumber AS draft
				FROM ".$this->Init_Table_Map("DETAILS")." 
				INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
				WHERE DATE(billing_date) >=CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01') AND billing_date < DATE_ADD(CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01'),INTERVAL 1 MONTH) AND bill_type=103 AND (IF('$bankStatementFlag'='L',cnt_code IN('BD','PBD'),cnt_code!='BD')) 
				UNION ALL
				SELECT IFNULL(finalNumber,IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'))) AS draftNumber,billing_date,vsl_name,ata,agent_name,UPPER(flag) AS flag,(rate*unit_for_pilot*move) AS usd,(rate*unit_for_pilot*move*exchangeRate) AS totbsd,IF((description='BERTHING' OR description='SHIFT VESSEL BERTH'),(((rate*unit_for_pilot*move*exchangeRate)*15)/100),0) AS vat,bill_type,".$this->Init_Table_Map("DETAILS").".draftNumber AS draft 
				FROM ".$this->Init_Table_Map("DETAILS")." 
				INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
				WHERE DATE(billing_date) >=CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01') AND billing_date < DATE_ADD(CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01'),INTERVAL 1 MONTH) AND bill_type IN(102,104) AND (IF('$bankStatementFlag'='L',cnt_code IN('BD','PBD'),cnt_code!='BD'))
				UNION ALL
				SELECT IFNULL(finalNumber,IF(cnt_code='BD',CONCAT('JL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'),CONCAT('JF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'))) AS draftNumber,billing_date,vsl_name,ata,agent_name,UPPER(flag) AS flag,IF(description LIKE 'BERTH_HIRE_1%',IF('$bankStatementFlag'='L',(rate*unit),((grt+deck_cargo)*rate*unit)),(rate*".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot)) AS usd,IF(description LIKE 'BERTH_HIRE_1%',IF('$bankStatementFlag'='L',(rate*unit),((grt+deck_cargo)*rate*unit*exchangeRate)),(rate*".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot*exchangeRate)) AS totbsd,0 AS vat,bill_type,".$this->Init_Table_Map("DETAILS").".draftNumber AS draft 
				FROM ".$this->Init_Table_Map("DETAILS")." 
				INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
				WHERE DATE(billing_date) >=CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01') AND billing_date < DATE_ADD(CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01'),INTERVAL 1 MONTH) AND bill_type=101 AND (IF('$bankStatementFlag'='L',cnt_code IN('BD','PBD'),cnt_code!='BD'))
				) AS TMP GROUP BY draftNumber ORDER BY bill_type,draft";
			
				$statementView = "bankStatementLocal";
				$filePath = "Bank Statement Local-";
			}
			else
			{
				$sql_bankStatement = "SELECT IF(bill_type IN(101,103),101,102) AS bill_type,draftNumber,DATE(billing_date) AS billing_date,vsl_name,DATE(ata) AS ata,agent_name,flag,SUM(usd) AS usd,SUM(totbsd) AS totbsd,SUM(vat) AS vat,CONCAT('FOR THE MONTH OF ',UPPER(MONTHNAME('$bankStatementDate')),' ',YEAR('$bankStatementDate')) AS month_name
				FROM 
				(
				SELECT IFNULL(finalNumber,IF(cnt_code='BD',CONCAT('JL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'),CONCAT('JF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'))) AS draftNumber,billing_date,vsl_name,ata,agent_name,UPPER(flag) AS flag,(rate*unit_for_pilot) AS usd,(rate*unit_for_pilot*exchangeRate)AS totbsd,0 AS vat,bill_type,".$this->Init_Table_Map("DETAILS").".draftNumber AS draft
				FROM ".$this->Init_Table_Map("DETAILS")." 
				INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
				WHERE DATE(billing_date) >=CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01') AND billing_date<DATE_ADD(CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01'),INTERVAL 1 MONTH) AND bill_type=103 AND (IF('$bankStatementFlag'='L',cnt_code IN('BD','PBD'),cnt_code!='BD')) 
				UNION ALL
				SELECT IFNULL(finalNumber,IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'))) AS draftNumber,billing_date,vsl_name,ata,agent_name,UPPER(flag) AS flag,(rate*unit_for_pilot*move) AS usd,(rate*unit_for_pilot*move*exchangeRate) AS totbsd,IF((description='BERTHING' OR description='SHIFT VESSEL BERTH'),(((rate*unit_for_pilot*move*exchangeRate)*15)/100),0) AS vat,bill_type,".$this->Init_Table_Map("DETAILS").".draftNumber AS draft 
				FROM ".$this->Init_Table_Map("DETAILS")." 
				INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
				WHERE DATE(billing_date) >=CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01') AND billing_date<DATE_ADD(CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01'),INTERVAL 1 MONTH) AND bill_type IN(102,104) AND (IF('$bankStatementFlag'='L',cnt_code IN('BD','PBD'),cnt_code!='BD'))
				UNION ALL
				SELECT IFNULL(finalNumber,IF(cnt_code='BD',CONCAT('JL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'),CONCAT('JF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-5'))) AS draftNumber,billing_date,vsl_name,ata,agent_name,UPPER(flag) AS flag,IF(description LIKE 'BERTH_HIRE_1%',IF('$bankStatementFlag'='L',(rate*unit),((grt+deck_cargo)*rate*unit)),(rate*".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot)) AS usd,IF(description LIKE 'BERTH_HIRE_1%',IF('$bankStatementFlag'='L',(rate*unit),((grt+deck_cargo)*rate*unit*exchangeRate)),(rate*".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot*exchangeRate)) AS totbsd,0 AS vat,bill_type,".$this->Init_Table_Map("DETAILS").".draftNumber AS draft 
				FROM ".$this->Init_Table_Map("DETAILS")." 
				INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
				WHERE DATE(billing_date) >=CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01') AND billing_date<DATE_ADD(CONCAT(YEAR('$bankStatementDate'),'-',MONTH('$bankStatementDate'),'-01'),INTERVAL 1 MONTH) AND bill_type=101 AND (IF('$bankStatementFlag'='L',cnt_code IN('BD','PBD'),cnt_code!='BD'))
				) AS TMP GROUP BY draftNumber ORDER BY bill_type,draft";
			
				$statementView = "bankStatementForeign";
				$filePath = "Bank Statement Foreign-";
			}
			// echo $sql_bankStatement;return;
			$rslt_bankStatement = $this->bm->dataSelectDb2($sql_bankStatement);
			
			$this->load->library('m_pdf');
			$this->data['rslt_bankStatement']=$rslt_bankStatement;
			$this->data['bankStatementDate']=$bankStatementDate;
		
		
			$html=$this->load->view('Vessel/'.$statementView,$this->data, true);
			
			$pdfFilePath =$filePath.time()."-download.pdf";
			$pdf = $this->m_pdf->load();			
						
			$pdf->useSubstitutions = true;
			
			$stylesheet = file_get_contents('assets/stylesheets/test.css');				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);				 
			$pdf->Output($pdfFilePath, "I");
		}
	}
	
	// Bank Statement - end
	
	// Billwise Statement - start
	function billwiseStatementForm()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		$this->session->set_userdata(array('menu' => "bill"));
		$this->session->set_userdata(array('sub_menu' => "billwiseStatementForm"));
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "BANK STATEMENT";
			$msg = "";
			
			$data['title'] = $title;
			$data['msg'] = $msg;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/billwiseStatementForm',$data);			
			$this->load->view('jsAssetsList');			
		}
	}
	
	function billwiseStatement()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{			
			$billwiseStatementMonth = $this->input->post('billwiseStatementMonth');
			$billwiseStatementYear = $this->input->post('billwiseStatementYear');
			
			$monthName = $this->getMonthName($billwiseStatementMonth);
			
			$sql_billwiseStatement = "SELECT DATE(bl_detail.billing_date) AS billing_date,bl_detail.draftNumber,bl_subdetail.description,
			IFNULL(finalNumber,
			(CASE 
				WHEN bl_detail.bill_type = '101' 
					THEN IF(cnt_code='BD',CONCAT('JL/',bl_detail.draftNumber,'-5'),CONCAT('JF/',bl_detail.draftNumber,'-5'))
				WHEN bl_detail.bill_type = '102'
					THEN IF(cnt_code='BD',CONCAT('PL/',bl_detail.draftNumber,'-5'),CONCAT('PF/',bl_detail.draftNumber,'-5'))
				WHEN bl_detail.bill_type = '103' 
					THEN IF(cnt_code='BD',CONCAT('JL/',bl_detail.draftNumber,'-5'),CONCAT('JF/',bl_detail.draftNumber,'-5'))
				WHEN bl_detail.bill_type = '104' 
					THEN IF(cnt_code='BD',CONCAT('JL/',bl_detail.draftNumber,'-5'),CONCAT('JF/',bl_detail.draftNumber,'-5'))
			END)) AS draftNumber2,
			rotation,bl_detail.bill_name,vsl_name,ata,atd,berth,CONCAT(gl_code,'0') AS gl_code,
			(CASE 
				WHEN bl_detail.bill_type = '101'
					THEN  IF(bl_subdetail.description LIKE '%BERTH_HIRE_1%',
					IF(cnt_code IN('BD','PBD'),(rate*unit),((bl_detail.grt+bl_detail.deck_cargo)*rate*unit)),rate*bl_subdetail.unit_for_pilot)
				WHEN bl_detail.bill_type = '102' THEN (rate*unit_for_pilot*move)
				WHEN bl_detail.bill_type = '103' THEN (rate*unit_for_pilot) 
				WHEN bl_detail.bill_type = '104' THEN (rate*unit_for_pilot)
			END) AS usd_charges,
			(CASE 
				WHEN bl_detail.bill_type = '101' THEN IF(bl_subdetail.description LIKE '%BERTH_HIRE_1%',
				IF(cnt_code IN('BD','PBD'),(rate*unit),((bl_detail.grt+bl_detail.deck_cargo)*rate*unit*exchangeRate)),
				rate*bl_subdetail.unit_for_pilot*exchangeRate)
				WHEN bl_detail.bill_type = '102' THEN (rate*unit_for_pilot*move*exchangeRate)     
				WHEN bl_detail.bill_type = '103' THEN (rate*unit_for_pilot*exchangeRate) 
				WHEN bl_detail.bill_type = '104' THEN (rate*unit_for_pilot*exchangeRate)
			END) AS bdt_charges, 
			MONTHNAME(bl_detail.billing_date) AS MONTH
			FROM ".$this->Init_Table_Map("DETAILS")." bl_detail 
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." bl_subdetail ON bl_detail.draftNumber = bl_subdetail.draftNumber
			WHERE MONTH(bl_detail.billing_date) = '$billwiseStatementMonth' AND YEAR(bl_detail.billing_date) = '$billwiseStatementYear' AND finalNumber IS NOT NULL AND description NOT LIKE '%BIWTA%'
			ORDER BY bl_detail.draftNumber,bl_subdetail.description";
			
			$rslt_billwiseStatement = $this->bm->dataSelectDb2($sql_billwiseStatement);
			
			$this->load->library('m_pdf');
			$this->data['monthName']=$monthName;					
			$this->data['billwiseStatementYear']=$billwiseStatementYear;					
			$this->data['rslt_billwiseStatement']=$rslt_billwiseStatement;					
		
			$html=$this->load->view('Vessel/billwiseStatement',$this->data, true);
			
			$pdfFilePath =$filePath.time()."-download.pdf";
			$pdf = $this->m_pdf->load();			
						
			$pdf->useSubstitutions = true;
			
			$stylesheet = file_get_contents('assets/stylesheets/test.css');				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);				 
			$pdf->Output($pdfFilePath, "I");
		}
	}
	
	
	function getMonthName($monthNum)
	{
		if($monthNum == 1)
			return "January";
		if($monthNum == 2)
			return "February";
		if($monthNum == 3)
			return "March";
		if($monthNum == 4)
			return "April";
		if($monthNum == 5)
			return "May";
		if($monthNum == 6)
			return "June";
		if($monthNum == 7)
			return "July";
		if($monthNum == 8)
			return "August";
		if($monthNum == 9)
			return "September";
		if($monthNum == 10)
			return "October";
		if($monthNum == 11)
			return "November";
		if($monthNum == 12)
			return "December";
	}
	// Billwise Statement - end
	
	// Periodic Statement - start
	function periodicStatementForm()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		$this->session->set_userdata(array('menu' => "bill"));
		$this->session->set_userdata(array('sub_menu' => "periodicStatementForm"));
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "PERIODIC STATEMENT";
			$msg = "";
			
			$data['title'] = $title;
			$data['msg'] = $msg;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/periodicStatementForm',$data);			
			$this->load->view('jsAssetsList');			
		}
	}
	
	function periodicStatement()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$periodicFromDate = $this->input->post('periodicFromDate');
			$periodicToDate = $this->input->post('periodicToDate');
			
			$sql_periodicStatement = "SELECT 
									finalNumber,billing_date,rotation,ata,agent_name,vsl_name,flag,
									SUM(bdt_charges) AS amt,
									SUM(bdtVat) AS vat
									FROM (
									SELECT bl_detail.finalNumber,bl_detail.billing_date,bl_detail.rotation,bl_detail.ata,bl_detail.agent_name,bl_detail.vsl_name,bl_detail.flag,
									(CASE 
										WHEN bl_detail.bill_type = '101' 
											THEN  IF(bl_subdetail.description LIKE '%BERTH_HIRE_1%',IF(cnt_code IN('BD','PBD','QBD'),(rate*unit), ((bl_detail.grt+bl_detail.deck_cargo)*rate*unit*exchangeRate)),
									rate*bl_subdetail.unit_for_pilot*exchangeRate)
										WHEN bl_detail.bill_type = '102'
											THEN (rate*unit_for_pilot*move*exchangeRate)     
										WHEN bl_detail.bill_type = '103'
											THEN (rate*unit_for_pilot*exchangeRate) 
										WHEN bl_detail.bill_type = '104'
											THEN (rate*unit_for_pilot*exchangeRate)
									END) AS bdt_charges,
									(SELECT IF(DATE(ata)>='2017-12-27',(bdt_charges*15)/100,'0')) AS bdtVat
									FROM ".$this->Init_Table_Map("DETAILS")." bl_detail
									INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." bl_subdetail ON bl_subdetail.draftNumber=bl_detail.draftNumber
									WHERE DATE(billing_date) BETWEEN '$periodicFromDate' AND '$periodicToDate' AND finalNumber IS NOT NULL AND description NOT LIKE '%BIWTA%'
									ORDER BY billing_date
									) AS tbl GROUP BY finalNumber";
			$rslt_periodicStatement = $this->bm->dataSelectDb2($sql_periodicStatement);
			
			$this->load->library('m_pdf');			
							
			$this->data['periodicFromDate']=$periodicFromDate;					
			$this->data['periodicToDate']=$periodicToDate;					
			$this->data['rslt_periodicStatement']=$rslt_periodicStatement;					
		
			$html=$this->load->view('Vessel/periodicStatement',$this->data, true);
			
			$pdfFilePath =$filePath.time()."-download.pdf";
			$pdf = $this->m_pdf->load();			
			
			$pdf->useSubstitutions = true;
			
			$stylesheet = file_get_contents('assets/stylesheets/test.css');				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);				 
			$pdf->Output($pdfFilePath, "I");
		}
	}
	
	
	// Periodic Statement - end
	
	// Monthly Statement - from
	function monthlyStatementForm()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		$this->session->set_userdata(array('menu' => "bill"));
		$this->session->set_userdata(array('sub_menu' => "monthlyStatementForm"));
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "PERIODIC STATEMENT";
			$msg = "";
			
			$data['title'] = $title;
			$data['msg'] = $msg;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/monthlyStatementForm',$data);			
			$this->load->view('jsAssetsList');			
		}
	}
	
	function monthlyStatement()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$monthlyStatementMonth = $this->input->post('monthlyStatementMonth');
			$monthlyStatementYear = $this->input->post('monthlyStatementYear');
			
			$sql_monthlyStatement = "SELECT 
			finalNumber,DATE(billing_date) AS billing_date,rotation,DATE(ata) AS ata,agent_name,
			SUM(bdt_charges) AS amt,
			SUM(bdtVat) AS vat
			FROM (
			SELECT bl_detail.finalNumber,bl_detail.billing_date,bl_detail.rotation,bl_detail.ata,bl_detail.agent_name,
			(CASE 
				WHEN bl_detail.bill_type = '101'
					THEN  IF(bl_subdetail.description LIKE '%BERTH_HIRE_1%',IF(cnt_code IN('BD','PBD','QBD'),(rate*unit), ((bl_detail.grt+bl_detail.deck_cargo)*rate*unit*exchangeRate)),rate*bl_subdetail.unit_for_pilot*exchangeRate)
				   WHEN bl_detail.bill_type = '102' THEN (rate*unit_for_pilot*move*exchangeRate)     
				   WHEN bl_detail.bill_type = '103' THEN (rate*unit_for_pilot*exchangeRate) 
				   WHEN bl_detail.bill_type = '104' THEN (rate*unit_for_pilot*exchangeRate)
			 END) AS bdt_charges,
			(SELECT IF(bl_subdetail.description='BERTHING',bdt_charges*15/100,0)) AS bdtVat
			FROM ".$this->Init_Table_Map("DETAILS")." bl_detail
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." bl_subdetail ON bl_subdetail.draftNumber=bl_detail.draftNumber
			WHERE MONTH(billing_date)= '$monthlyStatementMonth' AND YEAR(billing_date)= '$monthlyStatementYear' AND finalNumber IS NOT NULL AND description NOT LIKE '%BIWTA%'
			ORDER BY finalNumber
			) AS tbl GROUP BY finalNumber";
			$rslt_monthlyStatement = $this->bm->dataSelectDb2($sql_monthlyStatement);
			
			$monthName = $this->getMonthName($monthlyStatementMonth);
			
			$this->load->library('m_pdf');			
							
			$this->data['monthName']=$monthName;					
			$this->data['monthlyStatementYear']=$monthlyStatementYear;					
			$this->data['rslt_monthlyStatement']=$rslt_monthlyStatement;					
		
			$html=$this->load->view('Vessel/monthlyStatement',$this->data, true);
			
			$pdfFilePath =$filePath.time()."-download.pdf";
			$pdf = $this->m_pdf->load();			
			
			$pdf->useSubstitutions = true;
			
			$stylesheet = file_get_contents('assets/stylesheets/test.css');				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);				 
			$pdf->Output($pdfFilePath, "I");
		}
	}
	
	// Monthly Statement - end
	
	function Generate_Jetty_Charges_Bill($rotation)
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
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			// ####################### Generate_Jetty_Charges_Bill - start #######################
				
			$chkPangoanVsl = strtoupper(substr($rotation,5,1));
			// echo $chkPangoanVsl;return;
			$jettyChargesQuery = "";
			if($chkPangoanVsl == "P")				// INSIDE PANGOAN
			{
				$jettyChargesQuery = $this->vbq->getJettyChargesQueryPangoanVsl($rotation);
			}
			else if($chkPangoanVsl == "Q")			// INSIDE PANGOAN QGC
			{
				$jettyChargesQuery = $this->vbq->getJettyChargesQueryPangoanVslQGC($rotation);
			}
			else									// INSIDE NORMAL
			{
				$jettyChargesQuery = $this->vbq->getJettyChargesQuery($rotation);
			}
			// echo $jettyChargesQuery;return;
			$jettyChargesResult = $this->bm->dataSelect($jettyChargesQuery);
			
			$cnt = 0;
			$bill_name = "";
			$rtdt = 0;
		
			$int=1;
			
			$dollarRate = "";
			if(count($jettyChargesResult)>0)
			{					
				/* $sql_dollarRate = "SELECT rate
				FROM bil_currency_exchange_rates
				WHERE effective_date='".$jettyChargesResult[0]['argoAta']."' ORDER BY gkey DESC
				LIMIT 1"; */
				
				$this->updateDollarRateIn42($jettyChargesResult[0]['OA_DATE']);
				
				$sql_dollarRate = "SELECT rate
				FROM bil_currency_exchange_rates
				WHERE effective_date=DATE('".$jettyChargesResult[0]['OA_DATE']."') ORDER BY gkey DESC
				LIMIT 1";
				// echo $sql_dollarRate;return;
				
				$rslt_dollarRate = $this->bm->dataSelectDB1($sql_dollarRate);
				
				for($i=0;$i<count($rslt_dollarRate);$i++)
				{
					$dollarRate = $rslt_dollarRate[$i]['rate'];
				}
							
				for($i=0;$i<count($jettyChargesResult);$i++)
				{
					// $rotation     = $jettyChargesResult[$i]['rotation'];
					$rotJCR     = $jettyChargesResult[$i]['ROTATION'];		// JCR = Jetty Charges Result
					$vvdGkey     = $jettyChargesResult[$i]['VVD_GKEY'];		
					$vsl_name     = $jettyChargesResult[$i]['VSL_NAME'];
					$ata          = $jettyChargesResult[$i]['ATA'];
					$atd          = $jettyChargesResult[$i]['ATD'];
					$berth        = $jettyChargesResult[$i]['BERTH'];
					$agent_alias_id = $jettyChargesResult[$i]['AGENT_ALIAS_ID'];
					$agent_code   = $jettyChargesResult[$i]['AGENT_CODE'];
					$agent_name   = $jettyChargesResult[$i]['AGNET_NAME'];
					$agent_address   = $jettyChargesResult[$i]['ADDRESS'];
					$oa_date      = $jettyChargesResult[$i]['OA_DATE'];
					$flag         = $jettyChargesResult[$i]['FLAG'];
					$cnt_code     = $jettyChargesResult[$i]['CNT_CODE'];
					$grt          = $jettyChargesResult[$i]['GRT'];
					$master_name  = $this->getMasterName($rotation);		// check later for empty master name					
					//$deck_cargo   = $jettyChargesResult[$i]['deck_cargo'];
					$deck_cargo = $this->bm->dataReturnDb1("SELECT doc_vsl_info.deck_cargo as rtnValue FROM cchaportdb.doc_vsl_info WHERE doc_vsl_info.vvd_gkey='$vvdGkey'");
					if($deck_cargo=="")
					{
						$deck_cargo=0;
					}

					// $exchangeRate = $jettyChargesResult[$i]['exchangeRate'];
					$exchangeRate = $dollarRate;
					$unit         = $jettyChargesResult[$i]['UNIT'];
					$rtdt         = $jettyChargesResult[$i]['RTDT'];
					
					$pilot_ib_onboard = $pilotChargesResult[$i]['pilot_ib_onboard'];		// added for bill - hanif vai's requirement
					$pilot_ib_offboard = $pilotChargesResult[$i]['pilot_ib_offboard'];		//			"
					$pilot_ob_onboard = $pilotChargesResult[$i]['pilot_ob_onboard'];		//			"
					$pilot_ob_offboard = $pilotChargesResult[$i]['pilot_ob_offboard'];		//			"
					
					$cnt++;
					
					if($cnt == 1)
					{
						if($chkPangoanVsl == "P")
							$bill_name = "JETTY CHARGES ON VESSEL (PCT)"; 
						else
							$bill_name = "JETTY CHARGES ON VESSEL"; 
					}
					if($cnt > 1)
					{
						if($chkPangoanVsl == "P")
							$bill_name = "JETTY CHARGES ON VESSEL SFT VSL_".$cnt." (PCT)";
						else
							$bill_name = "JETTY CHARGES ON VESSEL SFT VSL_".$cnt;
					}
					// echo "<br>".$bill_name;return;
					
					$berth_suffix = $cnt;
					
					// checkIfJettyExists
			
					$checkIfExists = "SELECT COUNT(*) AS rotCount
					FROM ".$this->Init_Table_Map("DETAILS")."
					WHERE rotation = '".$rotation."' AND bill_type = '101' AND berth_suffix = '".$berth_suffix."'";
					$rsltCheckifExists = $this->bm->dataSelectDb2($checkIfExists);
					
					$cntIfExists = 0;
					for($j = 0;$j<count($rsltCheckifExists);$j++)
					{
						$cntIfExists = $rsltCheckifExists[$j]['rotCount'];
					}			
					
					if($cntIfExists > 0)
					{
						continue;					
					}				
					
					$bill_type = $jettyChargesResult[$i]['BILL_TYPE'];
					
					// insertQuery
					/* $insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")." (rotation,vsl_name,ata,atd,berth,agent_code,agent_name,oa_date,flag,cnt_code,grt,master_name,deck_cargo,exchangeRate,unit,berth_suffix,
					bill_type,bill_name,creator,agent_alias_id,agent_address,ip_address,billing_date)
					VALUES('$rotJCR','$vsl_name','$ata','$atd','$berth','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt','$master_name',
					'$deck_cargo','$exchangeRate','$unit','$berth_suffix','$bill_type','$bill_name','$login_id','$agent_alias_id','$agent_address',
					'$ipAddress',NOW())"; */
					
					$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")." (rotation,vsl_name,ata,atd,berth,agent_code,agent_name,oa_date,flag,cnt_code,grt,master_name,deck_cargo,exchangeRate,unit,berth_suffix,
					bill_type,bill_name,creator,agent_alias_id,agent_address,ip_address,billing_date,pilot_ib_onboard,pilot_ib_offboard,pilot_ob_onboard,
					pilot_ob_offboard)
					VALUES('$rotJCR','$vsl_name','$ata','$atd','$berth','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt','$master_name',
					'$deck_cargo','$exchangeRate','$unit','$berth_suffix','$bill_type','$bill_name','$login_id','$agent_alias_id','$agent_address',
					'$ipAddress',NOW(),'$pilot_ib_onboard','$pilot_ib_offboard','$pilot_ob_onboard','$pilot_ob_offboard')";
					// echo $insertQuery;return;
					
					$insertDETAILS = $this->bm->dataInsertDb2($insertQuery);
					
					if($insertDETAILS == 0)
					{
						//echo $msg = "<font color='red'>jettyChargesResult Not Successful at $i...</font>";
						//echo "<br>";
						// return;					
					}
					
					$sql_draftNumber = "SELECT draftNumber AS rtnValue
					FROM ".$this->Init_Table_Map("DETAILS")."
					WHERE rotation='$rotJCR' AND bill_type='$bill_type' AND berth_suffix='$berth_suffix'";		// rotJCR or rotation		??
					$draftNumberJCR = $this->bm->dataReturnDb2($sql_draftNumber);				
				
					// water bill will be separate from now
					/* if($chkPangoanVsl != "P" and $chkPangoanVsl != "Q")
					{
						if($cnt == 1)
						{
							$waterSupplySubDetailQuery = "";						
							if($chkPangoanVsl == "P" or $chkPangoanVsl == "Q")
							{
								$waterSupplySubDetailQuery = $this->vbq->waterSupplySubDetailQueryPangoan($rotation);
							}
							else
							{
								$waterSupplySubDetailQuery = $this->vbq->waterSupplySubDetailQuery($rotation);							
							}
							// echo $waterSupplySubDetailQuery;return;
							$waterSupplyResult = $this->bm->dataSelect($waterSupplySubDetailQuery);
							
							if(count($waterSupplyResult)>0)
							{								
								for($j=0;$j<count($waterSupplyResult);$j++)
								{															
									$description 	= $waterSupplyResult[$j]['description'];
									$gl_code 		= $waterSupplyResult[$j]['gl_code'];
									$rate 			= $waterSupplyResult[$j]['rate'];
									$bas 			= $waterSupplyResult[$j]['bas'];
									$unit 			= $waterSupplyResult[$j]['unit'];						

									$insert_waterSupplyResult = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot)
									VALUES('$draftNumberJCR','$description','$gl_code','$rate','$bas','$unit')";
									$rsltWSR = $this->bm->dataInsert($insert_waterSupplyResult);
									
									if($rsltWSR==0)
									{
										//echo $msg = "<font color='red'>waterSupplyResult Not Inserted at $j ...</font>";
										//echo "<br>";
										// return;	
									}
								}
							}
							else
							{
								//echo $msg = "<font color='red'>SUB DETAILS waterSupplyResult Not Found...</font>";
								//echo "<br>";
								// return;	
							}
						}
					} */
					
					$jettyChargeSubDetailQuery = "";
					$ctmsQuery="SELECT id  FROM ctmsmis.mis_vsl_bill_tarrif
					 WHERE mis_vsl_bill_tarrif.bill_type=101 AND mis_vsl_bill_tarrif.sub_type='$berth_suffix'";
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
					
					if($chkPangoanVsl == "P" or $chkPangoanVsl == "Q")  
					{
						/*$jettyChargeSubDetailQuery = "SELECT DISTINCT billing.bil_tariffs.description,billing.bil_tariffs.gl_code,ctmsmis.mis_vsl_bill_tarrif.pngn_new_rate AS rate,'HRS' AS bas 
						FROM ctmsmis.mis_vsl_bill_tarrif  
						INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=mis_vsl_bill_tarrif.id 
						INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey 
						WHERE sub_type='$berth_suffix' AND bill_type=101";*/
						$jettyChargeSubDetailQuery = "SELECT DISTINCT bil_tariffs.description,id,bil_tariffs.gl_code,'HRS' AS bas 
						FROM  bil_tariffs
						INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey 
						WHERE bil_tariffs.id IN ($billTypeIdList)";
					}
					else
					{
						// no data entry - 
						$jettyChargeSubDetailQuery = "SELECT DISTINCT bil_tariffs.description,id,bil_tariffs.gl_code, 
						(SELECT amount FROM bil_tariff_rate_tiers WHERE rate_gkey=bil_tariff_rates.gkey fetch first 1 rows only) AS rate,'HRS' AS bas 
						FROM bil_tariffs  
						INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey 
						WHERE bil_tariffs.id IN ($billTypeIdList)";
					}
					
					$rsJettyDetailResult = $this->bm->dataSelectDb3($jettyChargeSubDetailQuery);
					
					
				
						
					if(count($rsJettyDetailResult)>0)
					{
						for($k=0;$k<count($rsJettyDetailResult);$k++)
						{
							$tarifId        = $rsJettyDetailResult[$k]['ID'];
							$description 	= $rsJettyDetailResult[$k]['DESCRIPTION'];
							$gl_code 		= $rsJettyDetailResult[$k]['GL_CODE'];
							$rate="";
							if($chkPangoanVsl == "P" or $chkPangoanVsl == "Q")  {
								$rateCtmsQuery="SELECT pngn_new_rate as rate FROM ctmsmis.mis_vsl_bill_tarrif WHERE mis_vsl_bill_tarrif.bill_type=101 
								AND mis_vsl_bill_tarrif.sub_type='$berth_suffix' AND mis_vsl_bill_tarrif.id ='$tarifId'";
								$rateRes =$this->bm->dataSelectDb2($rateCtmsQuery);
								$rate 			=  $rateRes[0]['rate'];

							}
							else{

								$rate 			= $rsJettyDetailResult[$k]['RATE'];	
							}
							
							    $bas 			= $rsJettyDetailResult[$k]['BAS'];
							
							$insert_JettyDetailResult = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas)
											VALUES('$draftNumberJCR','$description','$gl_code','$rate','$bas')";
							$rsltJDR = $this->bm->dataInsertDb2($insert_JettyDetailResult);
							
							if($rsltJDR==0)
							{
								//echo $msg = "<font color='red'>rsJettyDetailResult Not Inserted at $k ...</font>";
								//echo "<br>";
								// return;	
							}
						}
					}
					else
					{
						//echo $msg = "<font color='red'>SUB DETAILS rsJettyDetailResult Not Found...</font>";
						//echo "<br>";
						// return;	
					}

					// For Pangoan Harbour Crane event				
					if($chkPangoanVsl=="P")
					{
						$harbourCraneSubDetailResult = "";
						
						if($rtdt == 1)
						{
							$harbourCraneSubDetailResult = $this->vbq->getHarbourCraneSubDetailChargesQueryCurrentVslIMP($vvdGkey);
						}
						else
						{						
							$harbourCraneSubDetailResult = $this->vbq->getHarbourCraneSubDetailChargesQueryOldVslIMP($vvdGkey);						
						}
						
					//	$harbourCraneSubDetailResult = $this->bm->dataSelect($harbourCraneSubDetailQueryIMP);
						
						if(count($harbourCraneSubDetailResult)>0)
						{
							for($m=0;$m<count($harbourCraneSubDetailResult);$m++)
							{
								$description 	= $harbourCraneSubDetailResult[$m]['DESCRIPTION'];
								$gl_code 		= $harbourCraneSubDetailResult[$m]['GL_CODE'];
								$rate 			= $harbourCraneSubDetailResult[$m]['RATE'];
								$bas 			= $harbourCraneSubDetailResult[$m]['BAS'];
								$unit=0;
								for($k=0; $k<count($harbourCraneSubDetailResult); $k++){
									$des=$harbourCraneSubDetailResult[$k]['DESCRIPTION'];
									if($description==$des){
										$unit=$unit+1;
									}

								}
								
								
								$harbourQueryIMP = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot)
								VALUES('$draftNumberJCR','$description','$gl_code','$rate','$bas','$unit')";
								$rsltHQIMP = $this->bm->dataInsertDb2($harbourQueryIMP);
								
								if($rsltHQIMP==0)
								{
									//echo $msg = "<font color='red'>harbourCraneSubDetailResult Not Inserted at $m ...</font>";
									//echo "<br>";
									// return;	
								}
							}
						}
						else
						{
							//echo $msg = "<font color='red'>SUB DETAILS harbourCraneSubDetailResult Not Found...</font>";
							//echo "<br>";
							// return;	
						}
						
						$harbourCraneSubDetailResultEXP = "";
						
						if($rtdt==1)
						{
							$harbourCraneSubDetailResultEXP = $this->vbq->getHarbourCraneSubDetailChargesQueryCurrentVslEXP($vvdGkey);
						}
						else
						{
							$harbourCraneSubDetailResultEXP = $this->vbq->getHarbourCraneSubDetailChargesQueryOldVslEXP($vvdGkey);
						}
						
						//$harbourCraneSubDetailResultEXP = $this->bm->dataSelect($harbourCraneSubDetailQueryEXP);
						
						if(count($harbourCraneSubDetailResultEXP)>0)
						{
							for($m=0;$m<count($harbourCraneSubDetailResultEXP);$m++)
							{							
								$description 	= $harbourCraneSubDetailResultEXP[$m]['DESCRIPTION'];
								$gl_code 		= $harbourCraneSubDetailResultEXP[$m]['GL_CODE'];
								$rate 			= $harbourCraneSubDetailResultEXP[$m]['RATE'];
								$bas 			= $harbourCraneSubDetailResultEXP[$m]['BAS'];
								$unit=0;
								for($k=0; $k<count($harbourCraneSubDetailResultEXP); $k++){
									$des=$harbourCraneSubDetailResultEXP[$k]['DESCRIPTION'];
									if($description==$des){
										$unit=$unit+1;
									}

								}
								
								
								$harbourQueryEXP = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot)
								VALUES('$draftNumberJCR','$description','$gl_code','$rate','$bas','$unit')";
								$rsltHQEXP = $this->bm->dataInsertDb2($harbourQueryEXP);
								
								if($rsltHQEXP==0)
								{
									//echo $msg = "<font color='red'>harbourCraneSubDetailResultEXP Not Inserted at $m ...</font>";
									//echo "<br>";
									// return;	
								}
							}
						}
						else
						{
							//echo $msg = "<font color='red'>SUB DETAILS harbourCraneSubDetailResultEXP Not Found...</font>";
							//echo "<br>";
							// return;
						}
					}		// chkPangoanVsl == P	
					else if($chkPangoanVsl == "Q")
					{
						// For Pangoan Gantry Crane event
						// $getGantryCraneSubDetailChargesQueryForQGC = $this->vbq->getGantryCraneSubDetailChargesQueryForQGC($rotation);
						$rsSubDetailResult = $this->vbq->getGantryCraneSubDetailChargesQueryForQGC($rotJCR);
						
						//$rsSubDetailResult = $this->bm->dataSelect($getGantryCraneSubDetailChargesQueryForQGC);
						
						if(count($rsSubDetailResult)>0)
						{
							for($m=0;$m<count($rsSubDetailResult);$m++)
							{
								$description 	= $rsSubDetailResult[$m]['DESCRIPTION'];
								$gl_code 		= $rsSubDetailResult[$m]['GL_CODE'];
								$rate 			= $rsSubDetailResult[$m]['RATE'];
								$bas 			= $rsSubDetailResult[$m]['BAS'];
								$unit 			= $rsSubDetailResult[$m]['unit'];
								$currency_gkey 	= $rsSubDetailResult[$m]['CURRENCY_GKEY'];
								$unit=0;
								for($k=0; $k<count($rsSubDetailResult); $k++){
									$des=$rsSubDetailResult[$k]['DESCRIPTION'];
									if($description==$des){
										$unit=$unit+1;
									}

								}
								
								
								$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot,currency_gkey) 
								VALUES('$draftNumberJCR','$description','$gl_code','$rate','$bas','$unit','$currency_gkey')";
								$rsltSubDtlRslt = $this->bm->dataInsertDb2($query);
								
								if($rsltSubDtlRslt==0)
								{
									//echo $msg = "<font color='red'>rsSubDetailResult (1) Not Inserted at $m ...</font>";
									//echo "<br>";
									// return;	
								}
							}
						}
						else
						{
							//echo $msg = "<font color='red'>SUB DETAILS rsSubDetailResult (1) Not Found...</font>";
							//echo "<br>";
							// return;
						}
					}		// chkPangoanVsl == Q	
						
					// For CCT Gantry Crane event
					
					if($chkPangoanVsl != "P" and $chkPangoanVsl != "Q")
					{
						$sql_cntShoreCraneData = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.shore_crane_demand 
													WHERE rotation='$rotation' AND STATUS='4'";
						$cntShoreCraneData = $this->bm->dataReturnDb2($sql_cntShoreCraneData);
						
						$craneFlag = "";						
						if($cntShoreCraneData > 0)
						{
							// Shore Crane (GCB)
							$craneFlag = "shore";
							$cctCountQuery = $this->vbq->getGCBCountQuery($rotation);

						} else {
							//QGC
							$craneFlag = "qgc";
							$cctCountQuery = $this->vbq->getCCTCountQuery($rotation);
						}
						$cctCountResult = $this->bm->dataSelect($cctCountQuery);
						
						// checkIfQGCExists - start
						$checkIfExists = "SELECT COUNT(*) AS rtnValue
						FROM ".$this->Init_Table_Map("DETAILS")."
						WHERE rotation = '$rotation' AND bill_type = '103'";
						$countResult = $this->bm->dataReturnDb2($checkIfExists);
						
						if($countResult > 0)
						{						         
							//echo $msg = "<font color='red'>QGC EXISTS FOR $rotation</font>";    
							//echo "<br>";								
							// return;
						}
						// checkIfQGCExists - end
						
						$cctCount = 0;
						for($j=0;$j<count($cctCountResult);$j++)
						{
							$cctCount = $cctCountResult[$j]['TOTALCCT'];
						}
						
						if($cctCount > 0)
						{
							// $getGantryCraneSubDetailChargesQuery = $this->vbq->getGantryCraneSubDetailChargesQuery($rotation);
							// $getGantryCraneSubDetailChargesQuery = $this->vbq->getGantryCraneSubDetailChargesQuery($rotation);
							if($craneFlag == "qgc"){
								$rsSubDetailResult = $this->vbq->getGantryCraneSubDetailChargesQuery($rotJCR);
							}
							else if($craneFlag == "shore") {
								$rsSubDetailResult = $this->vbq->getShoreCraneSubDetailChargesQuery($rotJCR);
							}
							//$rsSubDetailResult = $this->bm->dataSelect($getCraneSubDetailChargesQuery);
							
							//echo $getCraneSubDetailChargesQuery;
							//return;
							
							if(count($rsSubDetailResult)>0)
							{							
								for($j=0;$j<count($rsSubDetailResult);$j++)
								{
									$description 	= $rsSubDetailResult[$j]['DESCRIPTION'];
									$gl_code 		= $rsSubDetailResult[$j]['GL_CODE'];
									$rate 			= $rsSubDetailResult[$j]['RATE'];
									$bas 			= $rsSubDetailResult[$j]['BAS'];
									$unit=0;
									if($craneFlag == "qgc"){
										
										for($k=0; $k<count($rsSubDetailResult); $k++){
											$des=$rsSubDetailResult[$k]['DESCRIPTION'];
											if($description==$des){
												$unit=$unit+1;
											}

								         }

									}
									else{
										   $unit = $rsSubDetailResult[$j]['UNIT'];

									}
									
									
									if($unit != "0"){
										$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot)
										VALUES('$draftNumberJCR','$description','$gl_code','$rate','$bas','$unit')";	
										$rsltSubDtlRslt = $this->bm->dataInsertDb2($query);

										if($rsltSubDtlRslt==0)
										{
											//echo $msg = "<font color='red'>rsSubDetailResult (2) Not Inserted at $m ...</font>";
											//echo "<br>";
											// return;	
										}
									}									
								}
							}
							
						}
						else
						{
							//echo $msg = "<font color='red'>SUB DETAILS rsSubDetailResult (2) Not Found...</font>";
							//echo "<br>";
							// return;
						}
					}		// For CCT Gantry Crane event				
				}		// jettyChargesResult for loop	i=0	
			}
			else
			{					
				//echo $msg = "<font color='red'>jettyChargesResult Not Found...</font>";
				//echo "<br>";
				// return;
			}

			$jtyChrgFlag = 1;	// may need later
			
			// ####################### Generate_Jetty_Charges_Bill - end #######################	
		}
	}
	
	
	function Generate_Pilot_Charges_Bill($rotation)
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
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			// ####################### Generate_Pilot_Charges_Bill - start #######################
			$cnt = 0;
			$bill_name = "";
			$rtdt = 0;
			
			$int=1;
			
			// get rtdt  value - start
			$jettyChargesQuery = "";
			if($chkPangoanVsl == "P")				// INSIDE PANGOAN
			{
				$jettyChargesQuery = $this->vbq->getJettyChargesQueryPangoanVsl($rotation);
			}
			else if($chkPangoanVsl == "Q")			// INSIDE PANGOAN QGC
			{
				$jettyChargesQuery = $this->vbq->getJettyChargesQueryPangoanVslQGC($rotation);
			}
			else									// INSIDE NORMAL
			{
				$jettyChargesQuery = $this->vbq->getJettyChargesQuery($rotation);
			}
			// echo $jettyChargesQuery;return;
			$jettyChargesResult = $this->bm->dataSelect($jettyChargesQuery);
			
			$rtdt = $jettyChargesResult[0]['RTDT'];
			// get rtdt  value - end
				
			$jettyCountQuery = $this->vbq->getJettyCountQuery($rotation); 
			$visitCountQuery = $this->vbq->getVisitCountQuery($rotation); 
			
			$pilotChargesQuery = "";
			
			$chkPangoanVsl = strtoupper(substr($rotation,5,1));
	
			if($chkPangoanVsl == "P" or $chkPangoanVsl == "Q")
				$pilotChargesQuery = $this->vbq->getPilotChargesQueryPangoan($rotation);
			else
				$pilotChargesQuery = $this->vbq->getPilotChargesQuery($rotation);

			$jettyCountResult   = $this->bm->dataSelect($jettyCountQuery);
			$visitCountResult   = $this->bm->dataSelect($visitCountQuery);

			// echo $pilotChargesQuery;return;
			$pilotChargesResult = $this->bm->dataSelect($pilotChargesQuery); 

			// checkIfPilotExists
			$chk = $this->checkIfPilotExists($rotation);		
		
			if($chk > 0)
			{
				//echo $msg = "PILOT EXISTS FOR ".$rotation;		
				//echo "<br>";					
				// return;
			}
		
			$count = 0;
			for($i=0;$i<count($jettyCountResult);$i++)
			{
				$count = $jettyCountResult[$i]['CNT'];
			}
			
			$visitCount = 0;
			for($i=0;$i<count($visitCountResult);$i++)
			{
				$visitCount = $visitCountResult[$i]['CNT'];
			}
		
			$dollarRate = "";
			if(count($pilotChargesResult)>0)
			{					
				$this->updateDollarRateIn42($pilotChargesResult[0]['OA_DATE']);
				
				$sql_dollarRate = "SELECT rate
				FROM bil_currency_exchange_rates
				WHERE effective_date=DATE('".$pilotChargesResult[0]['OA_DATE']."') ORDER BY gkey DESC
				LIMIT 1";
				// echo $sql_dollarRate;return;
				
				$rslt_dollarRate = $this->bm->dataSelectDB1($sql_dollarRate);
				
				for($i=0;$i<count($rslt_dollarRate);$i++)
				{
					$dollarRate = $rslt_dollarRate[$i]['rate'];
				}
				
				for($i=0;$i<count($pilotChargesResult);$i++)
				{
					// $rotation = $pilotChargesResult[$i]['rotation'];
					$rotPCR = $pilotChargesResult[$i]['ROTATION'];			// PCR = Pilot Charges Result
					$vsl_name = $pilotChargesResult[$i]['VSL_NAME'];
					$ata = $pilotChargesResult[$i]['ATA'];
					$atd = $pilotChargesResult[$i]['ATD'];
					$agent_alias_id = $pilotChargesResult[$i]['AGENT_ALIAS_ID'];
					$agent_code = $pilotChargesResult[$i]['AGENT_CODE'];
					$agent_name = $pilotChargesResult[$i]['AGENT_NAME'];
					$agent_address = $pilotChargesResult[$i]['ADDRESS'];
					$oa_date = $pilotChargesResult[$i]['OA_DATE'];
					$flag = $pilotChargesResult[$i]['FLAG'];
					$cnt_code = $pilotChargesResult[$i]['CNT_CODE'];
					$grt = $pilotChargesResult[$i]['GRT'];
					$loa_cm = $pilotChargesResult[$i]['LOA_CM'];
					$master_name = $this->getMasterName($rotation);					
					$deck_cargo = $pilotChargesResult[$i]['DECK_CARGO'];
					// $exchangeRate = $pilotChargesResult[$i]['exchangeRate'];
					$exchangeRate = $dollarRate;
					$bill_type = $pilotChargesResult[$i]['BILL_TYPE'];
					$pilot_ib_onboard = $pilotChargesResult[$i]['PILOT_IB_ONBOARD'];
					$pilot_ib_offboard = $pilotChargesResult[$i]['PILOT_IB_OFFBOARD'];
					$pilot_ob_onboard = $pilotChargesResult[$i]['PILOT_OB_ONBOARD'];
					$pilot_ob_offboard = $pilotChargesResult[$i]['PILOT_OB_OFFBOARD'];
					$outbound_call_nbr = $pilotChargesResult[$i]['OUT_CALL_NUMBER'];
					
					$berth = $pilotChargesResult[$i]['BERTH'];		// added for bill	- hanif vai's requirement
					
					$s1N = $pilotChargesResult[$i]['S1N'];
					$s2N = $pilotChargesResult[$i]['S2N'];
								
					$berth_suffix = "";
					if($visitCount>1)
					{
						$berth_suffix = "1";
					}
					else
					{
						$berth_suffix = $count;
					}
					
					if($chkPangoanVsl == "P" || $chkPangoanVsl == "Q")
						$bill_name = "BILL FOR PORT & PILOTAGE CHARGES ON VESSEL (PCT)";
					else
						$bill_name = "BILL FOR PORT & PILOTAGE CHARGES ON VESSEL";
									
					/* $insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")."(rotation,vsl_name,ata,atd,agent_code,agent_name,oa_date,flag,cnt_code,grt,master_name,deck_cargo,exchangeRate,pilot_ib_onboard,
					pilot_ib_offboard,pilot_ob_onboard,pilot_ob_offboard,berth_suffix,bill_type,bill_name,creator,agent_alias_id,agent_address,ip_address,
					billing_date)
					VALUES('$rotPCR','$vsl_name','$ata','$atd','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt','$master_name','$deck_cargo','$exchangeRate','$pilot_ib_onboard','$pilot_ib_offboard','$pilot_ob_onboard','$pilot_ob_offboard','$berth_suffix','$bill_type','$bill_name','$login_id','$agent_alias_id','$agent_address','$ipAddress',NOW())"; */
					
					$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")."(rotation,vsl_name,ata,atd,agent_code,agent_name,oa_date,flag,cnt_code,grt,master_name,deck_cargo,exchangeRate,
					pilot_ib_onboard,pilot_ib_offboard,pilot_ob_onboard,pilot_ob_offboard,berth,berth_suffix,bill_type,bill_name,creator,agent_alias_id,
					agent_address,ip_address,billing_date)
					VALUES('$rotPCR','$vsl_name','$ata','$atd','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt','$master_name',
					'$deck_cargo','$exchangeRate','$pilot_ib_onboard','$pilot_ib_offboard','$pilot_ob_onboard','$pilot_ob_offboard','$berth','$berth_suffix',
					'$bill_type','$bill_name','$login_id','$agent_alias_id','$agent_address','$ipAddress',NOW())";
					
					$insertDETAILS = $this->bm->dataInsertDb2($insertQuery);
					
					if($insertDETAILS == 0)
					{
						//echo $msg = "<font color='red'>DETAILS Not Successful...</font>";
						//echo "<br>";
						// return;					
					}
					
					/* $sql_draftNumber = "SELECT draftNumber AS rtnValue
					FROM ".$this->Init_Table_Map("DETAILS")."
					WHERE rotation='$rotation' AND bill_type='$bill_type'"; */
					
					$sql_draftNumber = "SELECT draftNumber AS rtnValue
					FROM ".$this->Init_Table_Map("DETAILS")."
					WHERE rotation='$rotation' AND bill_type='$bill_type' AND berth_suffix='$berth_suffix'";
					$draftNumberPCR = $this->bm->dataReturnDb2($sql_draftNumber);
					
					$addPortDues = true;  
					
					if($outbound_call_nbr == 2)
						$addPortDues = false;
					
					$rsPilotSubDetailResult = "";

					
					if($chkPangoanVsl == "P" or $chkPangoanVsl == "Q")
					{
						if($rtdt==1)		// line 339
						{
							$rsPilotSubDetailResult =  $this->vbq->getPilotSubDetailsQueryPangoanNewRate($berth_suffix,$grt,$deck_cargo,$addPortDues);
						}               
						else
						{
							$rsPilotSubDetailResult =  $this->vbq->getPilotSubDetailsQueryPangoan($berth_suffix,$grt,$deck_cargo,$addPortDues);
						}              
					}
					else
					{
						$rsPilotSubDetailResult = $this->vbq->getPilotSubDetailsQuery($berth_suffix,$grt,$deck_cargo,$addPortDues,$rotation,$loa_cm);
					}
					
					//$rsPilotSubDetailResult = $this->bm->dataSelect($pilotChargeSubDetailQuery);
					
					if(count($rsPilotSubDetailResult)>0)
					{							
						for($j=0;$j<count($rsPilotSubDetailResult);$j++)
						{
							$description 	= $rsPilotSubDetailResult[$j]['DESCRIPTION'];
							$gl_code 		= $rsPilotSubDetailResult[$j]['GL_CODE'];
							$unit 			= $rsPilotSubDetailResult[$j]['UNIT'];
							$bas 			= $rsPilotSubDetailResult[$j]['BAS'];
							$move 			= $rsPilotSubDetailResult[$j]['MOVE'];
							$rate="";

							if($chkPangoanVsl == "P" or $chkPangoanVsl == "Q"){
								$id 	= $rsPilotSubDetailResult[$j]['ID'];
								$ctmsQuery="SELECT ctmsmis.mis_vsl_bill_tarrif.pangaon_rate AS rate FROM ctmsmis.mis_vsl_bill_tarrif 
								WHERE mis_vsl_bill_tarrif.id='$id'"; 
		                        $ctmsQueryRes=$this->bm->dataSelectDb2($ctmsQuery);
								$rate=$ctmsQueryRes[0]['rate'];

							}
							else{
								$rate 			= $rsPilotSubDetailResult[$j]['RATE'];
							}

						
							$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,unit_for_pilot,bas,move)
							VALUES('$draftNumberPCR','$description','$gl_code','$rate','$unit','$bas','$move')";
							
							$insertDETAILS = $this->bm->dataInsertDb2($query);
						
							if($insertDETAILS == 0)
							{
								//echo $msg = "<font color='red'>rsPilotSubDetailResult (1) insert Not Successful...</font>";
								//echo "<br>";
								// return;					
							}
						}
					}
					else
					{
						//echo $msg = "<font color='red'>rsPilotSubDetailResult (1) not found...</font>";
						//echo "<br>";
						// return;	
					}
					
					// For Pangoan BIWTA Charge start
					if($chkPangoanVsl == "P" or $chkPangoanVsl == "Q")
					{                    
						$pilotChargeBIWTAsubDetailQuery = $this->vbq->getPilotChargeBIWTAsubDetailQueryPan();
						
						$rsPilotSubDetailBIWTAResult = $this->bm->dataSelectDb2($pilotChargeBIWTAsubDetailQuery);
								
						if(count($rsPilotSubDetailBIWTAResult)>0)
						{
							for($j=0;$j<count($rsPilotSubDetailBIWTAResult);$j++)
							{
								$description 	= $rsPilotSubDetailBIWTAResult[$j]['description'];
								$gl_code 		= $rsPilotSubDetailBIWTAResult[$j]['gl_code'];
								$rate 			= $rsPilotSubDetailBIWTAResult[$j]['rate'];
								$unit 			= $rsPilotSubDetailBIWTAResult[$j]['unit'];
								$bas 			= $rsPilotSubDetailBIWTAResult[$j]['bas'];
								$move 			= $rsPilotSubDetailBIWTAResult[$j]['move'];
								
								$queryBIWTA = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,unit_for_pilot,bas,move)
								VALUES('$draftNumberPCR','$description','$gl_code','$rate','$unit','$bas','$move')";
								$insertBIWTA = $this->bm->dataInsertDb2($queryBIWTA);
						
								if($insertBIWTA == 0)
								{
									//echo $msg = "<font color='red'>queryBIWTA insert Not Successful...</font>";
									//echo "<br>";
									// return;					
								}
							}
						}
						else
						{
							//echo $msg = "<font color='red'>rsPilotSubDetailBIWTAResult not found...</font>";
							//echo "<br>";
							// return;	
						}
					}
					// For Pangoan BIWTA Charge end
					
					// SHIFT SWING DAY/NIGHT start
					$bs = $berth_suffix;
					
					if($bs>1)
					{
						for($j=1;$j<$bs;$j++)
						{
							$dayNightQuery = "";
							if($j==1)
							{
								$dayNightQuery = "select 
								(case 
								when to_char(vsl_vessel_visit_details.flex_date05,'hh24:mi:ss') BETWEEN '06:00:00' AND '17:59:59'
								and to_char(vsl_vessel_visit_details.flex_date06,'hh24:mi:ss') BETWEEN '06:00:00' AND '17:59:59' then 'D'
								else 'N'
								end) AS day_night
								FROM vsl_vessel_visit_details WHERE ib_vyg='$rotation'";
							}
							else
							{
								$dayNightQuery = "SELECT 

								(case
								when (to_char(vsl_vessel_berthings.start_work,'hh24:mi:ss') between  '06:00:00' AND '17:59:59') And
								(to_char(vsl_vessel_berthings.stop_work,'hh24:mi:ss') between  '06:00:00' AND '17:59:59') then 'D'
								else 'N' 
								end) as day_night
								FROM vsl_vessel_visit_details
								INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
								WHERE ib_vyg='$rotation' ORDER BY ata ";
							}
							
							$rdn = $this->bm->dataSelect($dayNightQuery);
							
							if(count($rdn)>0)
							{
								for($k=0;$k<count($rdn);$k++)
								{
									$dayNight = $rdn[$k]['DAY_NIGHT'];

									$ctmsQuery="SELECT id  FROM ctmsmis.mis_vsl_bill_tarrif
									WHERE mis_vsl_bill_tarrif.bill_type=102 AND mis_vsl_bill_tarrif.berth_time=CONCAT('$berth_suffix','$dayNight')";
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

								   $dayNightSubDetail = "SELECT DISTINCT bil_tariffs.description,id,bil_tariffs.gl_code,
								   bil_tariff_rates.amount AS rate,1 AS unit,'NOS' AS bas,1 AS move
									FROM  bil_tariffs
									INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey 
									WHERE bil_tariffs.id IN ($billTypeIdList)";
									
									/*$dayNightSubDetail = "SELECT DISTINCT billing.bil_tariffs.description,billing.bil_tariffs.description AS des,billing.bil_tariffs.gl_code,
									billing.bil_tariff_rates.amount AS rate,1 AS unit,'NOS' AS bas,1 AS move
									FROM ctmsmis.mis_vsl_bill_tarrif
									INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=ctmsmis.mis_vsl_bill_tarrif.id
									INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
									WHERE berth_time=CONCAT('$berth_suffix','$dayNight') AND bill_type=102";*/
									
									$dayNightSubDetailResult = $this->bm->dataSelectDb3($dayNightSubDetail);

									for($m=0;$m<count($dayNightSubDetailResult);$m++)
									{
										$description 	= $dayNightSubDetailResult[$m]['description DESCRIPTION'];
										$gl_code 		= $dayNightSubDetailResult[$m]['GL_CODE'];
										$rate 			= $dayNightSubDetailResult[$m]['RATE'];
										$bas 			= $dayNightSubDetailResult[$m]['BAS'];
										$unit 			= $dayNightSubDetailResult[$m]['UNIT'];
										$move 			= $dayNightSubDetailResult[$m]['MOVE'];
										
										$dayNightSubDetailQuery = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")." (draftNumber,description,gl_code,rate,bas,unit_for_pilot,move)
										VALUES('$draftNumberPCR','$description','$gl_code','$rate','$bas','$unit','$move')";
																		
										if($chkPangoanVsl != "P" or $chkPangoanVsl == "Q")
										{                                    
											$rslt_dayNightSubDetailQuery = $this->bm->dataInsertDb2($dayNightSubDetailQuery);
											
											if($rslt_dayNightSubDetailQuery == 0)
											{
												//echo $msg = "<font color='red'>rslt_dayNightSubDetailQuery stopped at $m</font>";
												//echo "<br>";
												// return;
											}
										}
									}
								}	
							}
							else
							{
								//echo $msg = "<font color='red'>rdn not found</font>";
								//echo "<br>";
								// return;
							}									
						}		// $bs>1 loop j=0
					}
					else
					{
						//echo $msg = "<font color='red'>bs not found</font>";
						//echo "<br>";
						// return;
					}
					// SHIFT SWING DAY/NIGHT end
					
					// night navigaion start				
					if($chkPangoanVsl != "P" and $chkPangoanVsl != "Q")
					{					  
						if($s1N == "1" or $s2N == "1")     	// check if s1n, s2n exists
						{
							$rsPilotNightNavigationResult = $this->vbq->getNightNavigationQuery($grt);		// grt exists
							
							//$rsPilotNightNavigationResult = $this->bm->dataSelect($getNightNavigationQuery);
							
							if(count($rsPilotNightNavigationResult)>0)
							{
								for($j=0;$j<count($rsPilotNightNavigationResult);$j++)
								{
									$description 	= $rsPilotNightNavigationResult[$j]['DESCRIPTION'];
									$gl_code 		= $rsPilotNightNavigationResult[$j]['GL_CODE'];
									$rate 			= $rsPilotNightNavigationResult[$j]['RATE'];
									$bas 			= $rsPilotNightNavigationResult[$j]['BAS'];
									$unit 			= $rsPilotNightNavigationResult[$j]['UNIT'];
									$move 			= $rsPilotNightNavigationResult[$j]['MOVE'];
									
									$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot,move)
									VALUES('$draftNumberPCR','$description','$gl_code','$rate','$bas','$unit','$move')";
														
									if($s1N == "1" and $s2N == "1")
									{
										// "Nav Both"
										
										$qFlag_1_1 = $this->bm->dataInsertDb2($query);
										if($qFlag_1_1 == 0)
										{
											//echo $msg = "<font color='red'>qFlag_1_1 stopped at $j</font>";
											//echo "<br>";
											// return;
										}
										
										$qFlag_1_2 = $this->bm->dataInsertDb2($query);
										if($qFlag_1_2 == 0)
										{
											//echo $msg = "<font color='red'>qFlag_1_2 stopped at $j</font>";
											//echo "<br>";
											// return;
										}
									}
									else
									{
										// "Nav 1"
										
										$qFlag_2_1 = $this->bm->dataInsertDb2($query);
										
										if($qFlag_2_1 == 0)
										{
											//echo $msg = "<font color='red'>qFlag_2_1 stopped at $j</font>";
											//echo "<br>";
											// return;
										}
									}
								}		// rsPilotNightNavigationResult j=0
							}
							else
							{
								//echo $msg = "<font color='red'>rsPilotNightNavigationResult not found...</font>";
								//echo "<br>";
								// return;
							}
						}		// if($s1N == "1" or $s2N == "1")
					}		// if($chkPangoanVsl != "P" and $chkPangoanVsl != "Q")
					// night navigaion end
					
					// line 448
					$events = array(187,407,195);
					
					$rtnMsg = $this->addAdditionalEventPilotCharges($rotation,$draftNumberPCR,$events,$grt,$loa_cm);
					
					if($rtnMsg == "AdditionalEvent_Failed")		// AdditionalEvent_Success - for success
					{
						//echo $msg = "<font color='red'>addAdditionalEventPilotCharges failed...</font>";
						//echo "<br>";
						// return;
					}
					
					// Rotation or rot in loop - check - emergency
				}		// pilotChargesResult loop - i = 0
			}
			else
			{					
				//echo $msg = "<font color='red'>pilotChargesResult not found</font>";
				//echo "<br>";
				// return;
			}
			
			$pilotChrgFlag = 1;			
			// ####################### Generate_Pilot_Charges_Bill - end #######################
		}
	}
	
	function Generate_Fireman_Bill($rotation)
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
			$ipAddress = $_SERVER['REMOTE_ADDR'];			
			$rotation = $this->uri->segment(3);
			
			$rotation = str_replace("_","/",$rotation);
			$serviceDate_str="SELECT service_date FROM ctmsmis.hotwork_demand WHERE  hotwork_demand.rotation='$rotation' AND service_date IS NOT NULL";
			$rslt_serviceDate = $this->bm->dataSelectDb2($serviceDate_str);
			$serviceDate="";
			if(count($rslt_serviceDate)>0)
			{
				
				 $serviceDate=$rslt_serviceDate[0]['service_date'];
			}	
				
			
			$getFirePumpQuery = $this->vbq->getFirePumpQuery($rotation);
			//echo $getFirePumpQuery;return;
			$subDetailResult = $this->bm->dataSelect($getFirePumpQuery);
			
			
			if(count($subDetailResult) == 0)
			{
				echo $msg = "<font color='red'>getFirePumpQuery returns 0</font>";
				echo "<br>";					
			}
						
			$vesselDetailQuery = $this->vbq->getFireJettyInfoQuery($rotation);
			// echo $vesselDetailQuery;return;
			$vesselDetailResult = $this->bm->dataSelect($vesselDetailQuery);
			//print_r($vesselDetailResult);
			if(count($vesselDetailResult)>0)
			{
				/* $oa_date = "";				
				for($j=0;$j<count($vesselDetailResult);$j++)
				{
					$oa_date = $vesselDetailResult[$j]['oa_date'];
				}	 */			
				
				// echo $oa_date;return;
				$dollarRate = "";
				$this->updateDollarRateIn42($serviceDate);
					
				$sql_dollarRate = "SELECT rate
				FROM bil_currency_exchange_rates
				WHERE effective_date=DATE('".$serviceDate."') ORDER BY gkey DESC
				LIMIT 1";
				$rslt_dollarRate = $this->bm->dataSelectDB1($sql_dollarRate);
				
				for($i=0;$i<count($rslt_dollarRate);$i++)
				{
					$dollarRate = $rslt_dollarRate[$i]['rate'];
				}
				
				
				for($i=0;$i<count($vesselDetailResult);$i++)
				{
					$rotVDR 		= $vesselDetailResult[$i]['ROTATION'];
					$vsl_name 		= $vesselDetailResult[$i]['VSL_NAME'];
					$ata 			= $vesselDetailResult[$i]['ATA'];
					$atd 			= $vesselDetailResult[$i]['ATD'];
					$berth 			= "";
					$agent_code 	= $vesselDetailResult[$i]['AGENT_ALIAS_ID'];
					$agent_name 	= $vesselDetailResult[$i]['AGENT_NAME'];
					$oa_date 		= $vesselDetailResult[$i]['OA_DATE'];
					$flag 			= $vesselDetailResult[$i]['FLAG'];
					$cnt_code 		= $vesselDetailResult[$i]['CNT_CODE'];
					$grt 			= $vesselDetailResult[$i]['GRT'];
					$vvd_gkey       = $vesselDetailResult[$i]['VVD_GKEY'];
					$master_name  	= $this->getMasterName($rotation);	
					//$deck_cargo 	= $vesselDetailResult[$i]['deck_cargo'];
					$deck_cargo = $this->bm->dataReturnDb1("SELECT doc_vsl_info.deck_cargo as rtnValue FROM cchaportdb.doc_vsl_info WHERE doc_vsl_info.vvd_gkey='$vvd_gkey'");
					if($deck_cargo=="")
					{
						$deck_cargo=0;
					}
					// $exchangeRate 	= $vesselDetailResult[$i]['exchangeRate'];
					$exchangeRate = $dollarRate;						
					//$unit 			= $vesselDetailResult[$i]['unit'];		

					$bill_type = "104";
					$bill_name = "Firework Bill";
					 
					$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")."(rotation,vsl_name,ata,atd,berth,agent_code,agent_name,oa_date,flag,cnt_code,grt,master_name,deck_cargo,exchangeRate,bill_type,
					bill_name,creator,ip_address,billing_date)
					
					VALUES('$rotVDR','$vsl_name','$ata','$atd','$berth','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt',
					'$master_name','$deck_cargo','$exchangeRate','$bill_type','$bill_name','$login_id','$ipAddress',NOW())";
					
					$vdrInsertFlag = $this->bm->dataInsertDb2($insertQuery);
					
					if($vdrInsertFlag == 0)
					{
						echo $msg = "<font color='red'>vdrInsert Flag stopped at $i</font>";
						echo "<br>";
						return;
					}
					
					$sql_draftNumber = "SELECT draftNumber AS rtnValue
					FROM ".$this->Init_Table_Map("DETAILS")."
					WHERE rotation='$rotVDR' AND bill_type='$bill_type'";
					$draftNumberVDR = $this->bm->dataReturnDb2($sql_draftNumber);
					
					if(count($subDetailResult)>0)
					{
						
						for($j=0;$j<count($subDetailResult);$j++)
						{
							//$description 	= $subDetailResult[$j]['description'];
							//$gl_code 		= $subDetailResult[$j]['gl_code'];
							//$rate 			= $subDetailResult[$j]['rate'];
							$bas 			= $subDetailResult[$j]['BAS'];
							$unit 			= $subDetailResult[$j]['UNIT'];	
							$id             = $subDetailResult[$j]['ID'];	
							
							$subDetailBillingQuery="SELECT *
							FROM bil_tariffs  
							INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
							WHERE bil_tariffs.id='$id' and bil_tariff_rates.rate_type='REGULAR' ";
							$subDetailBillingRes=$this->bm->dataSelectDb3($subDetailBillingQuery);

							if(count($subDetailBillingRes)>0){
								for($k=0;$k<count($subDetailBillingRes);$k++){
									$description 	= $subDetailBillingRes[$k]['DESCRIPTION'];
									$gl_code 		= $subDetailBillingRes[$k]['GL_CODE'];
									$rate 			= $subDetailBillingRes[$k]['RATE'];

											
									$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot,move)
									VALUES('$draftNumberVDR','$description','$gl_code','$rate','$bas','$unit','1')";
									
									$subDtlRsltFlag = $this->bm->dataInsertDb2($query);
									
									if($subDtlRsltFlag == 0)
									{
										echo $msg = "<font color='red'>subDetailResult insert stopped at $j</font>";
										echo "<br>";
										return;
									}
							    }	
						   }
						}
					}
					else
					{
						echo $msg = "<font color='red'>subDetailResult not found</font>";
						echo "<br>";						
					}	
				}		// $vesselDetailResult for loop - i=0
				
				$sql_updateFireInfo = "UPDATE ctmsmis.hotwork_demand
									SET bill_op_bill_st='1',bill_op_bill_by='$login_id',bill_op_bill_at=NOW(),bill_op_bill_ip='$ipAddress'
									WHERE rotation='$rotation'";
				$rslt_updateFireInfo = $this->bm->dataUpdatedb2($sql_updateFireInfo);
			}
			else
			{					
				echo $msg = "<font color='red'>vessel Detail Result not found</font>";
				echo "<br>";
				// return;
			}
			
			$firemanChrgFlag = 1;	 	
			// ####################### Generate_Fireman_Bill - end #######################
			
			/* $title = "Hot Work Demand Approved List";
			
			$query = "SELECT * FROM ctmsmis.hotwork_demand  WHERE  fire_dpt_aprv_st = 1 AND acc_aprv_st=1";
			$result = $this->bm->dataSelect($query);

			$data['result'] = $result;
			$data['title'] = $title;
			$data['msg'] = "";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkDemandApproveListForBillOperator',$data);
			$this->load->view('jsAssetsList'); */
			
			$msgRot = str_replace('/','_',$rotation);
			// echo "1 ".$msgRot;
			$msgRot = "billReady_".$msgRot;
			// echo "2 ".$msgRot;
			
			redirect('VesselBill/vesselBillListAcc/p/'.$msgRot, 'location');
		}
	}
	
	
	
	
	//Beaching bill- start ---------
	function Generate_Beaching_Bill()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{		
			$rotation = $this->input->post('rot');						
				
			if($rotation == null or $rotation == "")
			{
				$rotation = $this->uri->segment(3);
				$rotation = str_replace("_","/",$rotation);
			}
			
			$sql_chkBill = "SELECT COUNT(*) AS rtnValue
			FROM ".$this->Init_Table_Map("DETAILS")."
			WHERE rotation='$rotation' AND ".$this->Init_Table_Map("DETAILS").".bill_type='108'";
			$chkBill = $this->bm->dataReturndb2($sql_chkBill);
			
			if($chkBill == 0)
			{
				$rtnData = $this->Generate_Beaching_Bill_Main($rotation);
				// echo "<br>---";
				// echo "<br>Kutubdia";
				// return;
				if($rtnData == "BeachingBillGenerated")
				{
					$sql_getVvdGkey = "SELECT vsl_vessel_visit_details.vvd_gkey AS rtnValue
					FROM vsl_vessel_visit_details
					WHERE ib_vyg='$rotation'";
					$vvdGkey = $this->bm->dataReturn($sql_getVvdGkey);
					
					$updateBillInfo = "UPDATE ctmsmis.vsl_forward_info
									SET ctmsmis.vsl_forward_info.billop_bill_stat='1',
									ctmsmis.vsl_forward_info.billop_bill_at=NOW(),
									ctmsmis.vsl_forward_info.billop_bill_by='$login_id',
									ctmsmis.vsl_forward_info.billop_bill_ip='$ipAddress'
									WHERE ctmsmis.vsl_forward_info.vvd_gkey='$vvdGkey'";
					$this->bm->dataUpdatedb2($updateBillInfo);
				}
											
				$msgRot = str_replace('/','_',$rotation);
				
				$msgRot = "billReady_".$msgRot;
							
				redirect('VesselBill/vesselBillListAcc/p/'.$msgRot, 'location');
			}
			else
			{
				echo $msg = "<font color='red'>Bill already created for $rotation</font>";
				echo "<br>";
				return;
			}
		}
	}
	
	function Generate_Beaching_Bill_Main($rotation)	
	{
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		$insertDETAILS = 0;
		$insertSUB_DETAILS = 0;
		
		$pilotChargesQueryBeaching = $this->vbq->getPilotChargesQueryBeaching($rotation);
		$pilotChargesResultBeaching = $this->bm->dataSelect($pilotChargesQueryBeaching);
		$dollarRate = 0;
		if(count($pilotChargesResultBeaching)>0)
		{
			$this->updateDollarRateIn42($pilotChargesResultBeaching[0]['OA_DATE']);
			
			$sql_dollarRate = "SELECT rate
			FROM bil_currency_exchange_rates
			WHERE effective_date=DATE('".$pilotChargesResultBeaching[0]['OA_DATE']."') ORDER BY gkey DESC
			LIMIT 1";			

			$rslt_dollarRate = $this->bm->dataSelectDB1($sql_dollarRate);

			for($i=0;$i<count($rslt_dollarRate);$i++)
			{
				$dollarRate = $rslt_dollarRate[$i]['rate'];
			}
			// echo "<br>---";
			// echo $dollarRate;
			// return;
			$berth_suffix = 1;
			for($i=0;$i<count($pilotChargesResultBeaching);$i++)
			{
				$vvd_gkey = $pilotChargesResultBeaching[$i]['VVD_GKEY'];
				$vsl_name = $pilotChargesResultBeaching[$i]['VSL_NAME'];
				$rotNo = $pilotChargesResultBeaching[$i]['ROTATION'];
				$oa_date = $pilotChargesResultBeaching[$i]['OA_DATE'];
				$ata = $pilotChargesResultBeaching[$i]['ATA'];
				$atd = $pilotChargesResultBeaching[$i]['ATD'];
				$date_of_arrival = $pilotChargesResultBeaching[$i]['DATE_OF_ARRIVAL'];
				$time_of_arrival = $pilotChargesResultBeaching[$i]['TIME_OF_ARRIVAL'];
				$date_of_departure = $pilotChargesResultBeaching[$i]['DATE_OF_DEPARTURE'];
				$time_of_departure = $pilotChargesResultBeaching[$i]['TIME_OF_DEPARTURE'];
				$grt = $pilotChargesResultBeaching[$i]['GRT'];
				$nrt = $pilotChargesResultBeaching[$i]['NRT'];
				$flag = $pilotChargesResultBeaching[$i]['FLAG'];
				$cnt_code = $pilotChargesResultBeaching[$i]['CNT_CODE'];
				$agent_code = $pilotChargesResultBeaching[$i]['AGENT_CODE'];
				$agent_name = $pilotChargesResultBeaching[$i]['AGENT_NAME'];
				$agent_address = $pilotChargesResultBeaching[$i]['AGENT_ADDRESS'];
				$agent_alias_id = $pilotChargesResultBeaching[$i]['AGENT_ALIAS_ID'];
				$deck_cargo = $pilotChargesResultBeaching[$i]['DECK_CARGO'];
				$bill_type = $pilotChargesResultBeaching[$i]['BILL_TYPE'];
				$loa_cm = $pilotChargesResultBeaching[$i]['LOA_CM'];
				$exchangeRate = $dollarRate;
				
				$bill_name = "BILL FOR PORT & PILOTAGE CHARGES ON VESSEL";
				
				$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")." (rotation,vsl_name,ata,atd,agent_code,agent_name,flag,cnt_code,grt,deck_cargo,exchangeRate,berth_suffix,bill_type,bill_name,creator,
				agent_alias_id,agent_address,ip_address,billing_date, oa_date)
				VALUES('$rotNo','$vsl_name','$ata','$atd','$agent_code','$agent_name','$flag','$cnt_code','$grt','$deck_cargo','$exchangeRate',
				'$berth_suffix','$bill_type','$bill_name','$login_id','$agent_alias_id','$agent_address','$ipAddress',NOW(), '$oa_date')";	
				$insertDETAILS = $this->bm->dataInsertDb2($insertQuery);
				
				if($insertDETAILS == 0)
				{
					return "<font color='red'>Insertion DETAILS failed</font>";
				}	
				
				$sql_draftNumber = "SELECT draftNumber AS rtnValue 
				FROM ".$this->Init_Table_Map("DETAILS")."
				WHERE ".$this->Init_Table_Map("DETAILS").".rotation='$rotNo' AND ".$this->Init_Table_Map("DETAILS").".bill_type='109'
				ORDER BY draftNumber DESC LIMIT 1";
				 //echo $sql_draftNumber;
				// echo "<br>";
				$draftNumber = $this->bm->dataReturnDb2($sql_draftNumber);
				//return;
				
				$pilotChargeSubDetailQueryBeaching = $this->vbq->getPilotSubDetailsQueryBeaching($grt,$deck_cargo,$loa_cm,$rotNo);
				// echo "<br>";
				// return;
				$rsPilotSubDetailResultBeaching = $this->bm->dataSelectDb3($pilotChargeSubDetailQueryBeaching);
				
				if(count($rsPilotSubDetailResultBeaching)>0)
				{
					for($j=0;$j<count($rsPilotSubDetailResultBeaching);$j++)
					{
						$description 	= $rsPilotSubDetailResultBeaching[$j]['DESCRIPTION'];
						$gl_code 		= $rsPilotSubDetailResultBeaching[$j]['GL_CODE'];
						$rate 			= $rsPilotSubDetailResultBeaching[$j]['RATE'];
						$unit 			= $rsPilotSubDetailResultBeaching[$j]['UNIT'];
						$bas 			= $rsPilotSubDetailResultBeaching[$j]['BAS'];
						$move 			= $rsPilotSubDetailResultBeaching[$j]['MOVE'];
											
						$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,unit_for_pilot,bas,move)
								VALUES('$draftNumber','$description','$gl_code','$rate','$unit','$bas','$move')";
						$insertSUB_DETAILS = $this->bm->dataInsertDb2($query);
						if($insertSUB_DETAILS == 0)
						{
							return "<font color='red'>Insertion SUB_DETAILS failed for ".$j."</font>";
						}
					}
				}
				else
				{
					return "<font color='red'>Sub Detail Beaching not found</font>";
				}
			}
		}
		else
		{
			return "<font color='red'>Pilot charge for Beaching not found</font>";
		}
		
		if(($insertDETAILS == 1) and ($insertSUB_DETAILS == 1))
		{
			// $rtnData = "<font color='green'>Bill Generated</font>";
			$rtnData = "BeachingBillGenerated";
			return $rtnData;
		}
		else
		{
			$rtnData = "BeachingBillSomethingWrong";
			return $rtnData;
		}
	} 
	// beaching Bill - end
	
	// Regenerated vsl bill - start
	function vslBillRegenerate()
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
			$section = $this->session->userdata('section');
			$login_id = $this->session->userdata('login_id');
			
			// echo "<br>".$regenerateRotation = $this->input->post('regenerateRotation');
			// echo "<br>".$regenerateBillType = $this->input->post('regenerateBillType');
			// echo "<br>".$draftForRegenerate = $this->input->post('draftForRegenerate');
			
			$rgRotation = $this->input->post('regenerateRotation');
			$rgBillType = $this->input->post('regenerateBillType');
			$draftForRegenerate = $this->input->post('draftForRegenerate');
			
			// echo "ok";
			// return;
			
			// delete bill with log
			$this->vslBillDeleteMain($draftForRegenerate);
			
			// generate bill
			if($rgBillType == 101)			// jetty bill
			{
				$this->Generate_Jetty_Charges_Bill($rgRotation);
			}
			else if($rgBillType == 102)		// pilot bill
			{
				$this->Generate_Pilot_Charges_Bill($rgRotation);
			}
			else if($rgBillType == 106)		// not entering bill
			{
				// $this->generateVesselsBillNotEntering($rotation);
				$this->generatePilotageBillNotEntering($rgRotation);
			}
			else if($rgBillType == 104)		// Fireman
			{								
				$this->Generate_Fireman_Bill($rgRotation);
			}
			else if($rgBillType == 105)		// WaterBill
			{							
				$this->Generate_WaterSupply_Bill($rgRotation);
			}
			else if($rgBillType == 107)		// Bhatiari
			{								
				$this->Generate_Bhatiari_Bill_Main($rgRotation);
			}
			else if($rgBillType == 108)		// Kutubdia
			{			
				$this->Generate_Bhatiari_Bill_Main($rgRotation);
			}
			
			// update dispute info
			$sql_updateDispute = "UPDATE ".$this->Init_Table_Map("DETAILS")."
								SET ".$this->Init_Table_Map("DETAILS").".disputeraised='1'
								WHERE ".$this->Init_Table_Map("DETAILS").".rotation='$rgRotation' 
								AND ".$this->Init_Table_Map("DETAILS").".bill_type='$rgBillType'";
			$this->bm->dataUpdatedb2($sql_updateDispute);
			
			// go to bill list
			$title = "VESSEL BILL LIST (Approved)";
			$action = "a";
			$msg = "<font color='green'>Bill regenerated for ".$rgRotation."</font>";
 
			$sql_bill_list="SELECT draftNumber,IFNULL(finalNumber,draftNumber) AS finalNumber,rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st
			FROM ".$this->Init_Table_Map("DETAILS")."
			WHERE acc_apprv_st = 1 ".$cond." ORDER BY draftNumber DESC";
			
			$rslt_bill_list=$this->bm->dataSelectDb2($sql_bill_list);						
			
			$data['rslt_bill_list']=$rslt_bill_list;
		
			$data['title'] = $title;
			$data['section'] = $section;
			$data['action'] = $action;
			$data['msg'] = $msg;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselBillListAcc',$data);			
			$this->load->view('jsAssetsList');	
		}
	}
	// Regenerated vsl bill - end
	
	// Bhatiari Bill - start
	function Generate_Bhatiari_Bill()
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
		
			$rotation = $this->input->post('rot');						
				
			if($rotation == null or $rotation == "")
			{
				$rotation = $this->uri->segment(3);
				$rotation = str_replace("_","/",$rotation);
			}
			
			$sql_chkBill = "SELECT COUNT(*) AS rtnValue
			FROM ".$this->Init_Table_Map("DETAILS")."
			WHERE rotation='$rotation' AND ".$this->Init_Table_Map("DETAILS").".bill_type='107'";
			
			$chkBill = $this->bm->dataReturnDb2($sql_chkBill);
			
			if($chkBill == 0)
			{				
				$rtnData = $this->Generate_Bhatiari_Bill_Main($rotation);
				
				// echo "Bhatiari";
				// return;
				
				if($rtnData == "BhatiariBillGenerated")
				{
					$sql_getVvdGkey = "SELECT vsl_vessel_visit_details.vvd_gkey AS rtnValue
					FROM vsl_vessel_visit_details
					WHERE ib_vyg='$rotation'";
					$vvdGkey = $this->bm->dataReturn($sql_getVvdGkey);
					
					$updateBillInfo = "UPDATE ctmsmis.vsl_forward_info
									SET ctmsmis.vsl_forward_info.billop_bill_stat='1',
									ctmsmis.vsl_forward_info.billop_bill_at=NOW(),
									ctmsmis.vsl_forward_info.billop_bill_by='$login_id',
									ctmsmis.vsl_forward_info.billop_bill_ip='$ipAddress'
									WHERE ctmsmis.vsl_forward_info.vvd_gkey='$vvdGkey'";
					$this->bm->dataUpdatedb2($updateBillInfo);
				}
											
				$msgRot = str_replace('/','_',$rotation);
				
				$msgRot = "billReady_".$msgRot;
							
				redirect('VesselBill/vesselBillListAcc/p/'.$msgRot, 'location');
			}
			else
			{
				echo $msg = "<font color='red'>Bill already created for $rotation</font>";
				echo "<br>";
				return;
			}
		}
	}
	
	
	function Generate_Bhatiari_Bill_Main($rotation)	
	{
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		$insertDETAILS = 0;
		$insertSUB_DETAILS = 0;
		
		$pilotChargesQueryBhatiari = $this->vbq->getPilotChargesQueryBhatiari($rotation);
		$pilotChargesResultBhatiari = $this->bm->dataSelect($pilotChargesQueryBhatiari);
		
		$dollarRate = 0;
		if(count($pilotChargesResultBhatiari)>0)
		{
			$this->updateDollarRateIn42($pilotChargesResultBhatiari[0]['OA_DATE']);
			
			$sql_dollarRate = "SELECT rate
			FROM bil_currency_exchange_rates
			WHERE effective_date=DATE('".$pilotChargesResultBhatiari[0]['OA_DATE']."') ORDER BY gkey DESC
			LIMIT 1";			

			$rslt_dollarRate = $this->bm->dataSelectDB1($sql_dollarRate);

			for($i=0;$i<count($rslt_dollarRate);$i++)
			{
				$dollarRate = $rslt_dollarRate[$i]['rate'];
			}
			// echo "<br>---";
			// echo $dollarRate;
			// return;
			$berth_suffix = 1;
			for($i=0;$i<count($pilotChargesResultBhatiari);$i++)
			{
				$vvd_gkey = $pilotChargesResultBhatiari[$i]['VVD_GKEY'];
				$vsl_name = $pilotChargesResultBhatiari[$i]['VSL_NAME'];
				$rotNo = $pilotChargesResultBhatiari[$i]['ROTATION'];
				$oa_date = $pilotChargesResultBhatiari[$i]['OA_DATE'];
				$ata = $pilotChargesResultBhatiari[$i]['ATA'];
				$atd = $pilotChargesResultBhatiari[$i]['ATD'];
				$date_of_arrival = $pilotChargesResultBhatiari[$i]['DATE_OF_ARRIVAL'];
				$time_of_arrival = $pilotChargesResultBhatiari[$i]['TIME_OF_ARRIVAL'];
				$date_of_departure = $pilotChargesResultBhatiari[$i]['DATE_OF_DEPARTURE'];
				$time_of_departure = $pilotChargesResultBhatiari[$i]['TIME_OF_DEPARTURE'];
				$grt = $pilotChargesResultBhatiari[$i]['GTR'];
				$nrt = $pilotChargesResultBhatiari[$i]['NTR'];
				$flag = $pilotChargesResultBhatiari[$i]['FLAG'];
				$cnt_code = $pilotChargesResultBhatiari[$i]['CNT_CODE'];
				$agent_code = $pilotChargesResultBhatiari[$i]['AGENT_CODE'];
				$agent_name = $pilotChargesResultBhatiari[$i]['AGENT_NAME'];
				$agent_address = $pilotChargesResultBhatiari[$i]['AGENT_ADDRESS'];
				$agent_alias_id = $pilotChargesResultBhatiari[$i]['AGENT_ALIAS_ID'];
				$deck_cargo = $pilotChargesResultBhatiari[$i]['DECK_CARGO'];
				$bill_type = $pilotChargesResultBhatiari[$i]['BILL_TYPE'];
				$loa_cm = $pilotChargesResultBhatiari[$i]['LOA_CM'];
				$exchangeRate = $dollarRate;
				
				$bill_name = "BILL FOR PORT & PILOTAGE CHARGES ON BHATIARI VESSEL";
				
				$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")." (rotation,vsl_name,ata,atd,agent_code,agent_name,flag,cnt_code,grt,deck_cargo,exchangeRate,berth_suffix,bill_type,bill_name,creator,
				agent_alias_id,agent_address,ip_address,billing_date)
				VALUES('$rotNo','$vsl_name','$ata','$atd','$agent_code','$agent_name','$flag','$cnt_code','$grt','$deck_cargo','$exchangeRate',
				'$berth_suffix','$bill_type','$bill_name','$login_id','$agent_alias_id','$agent_address','$ipAddress',NOW())";	
				$insertDETAILS = $this->bm->dataInsertDb2($insertQuery);
				
				if($insertDETAILS == 0)
				{
					return "<font color='red'>Insertion DETAILS failed</font>";
				}	
				
				$sql_draftNumber = "SELECT draftNumber AS rtnValue 
				FROM ".$this->Init_Table_Map("DETAILS")."
				WHERE ".$this->Init_Table_Map("DETAILS").".rotation='$rotNo' AND ".$this->Init_Table_Map("DETAILS").".bill_type='107'
				ORDER BY draftNumber DESC LIMIT 1";
				// echo $sql_draftNumber;
				// echo "<br>";
				$draftNumber = $this->bm->dataReturnDb2($sql_draftNumber);
				
				//$pilotChargeSubDetailQueryBhatiari = $this->vbq->getPilotSubDetailsQueryBhatiari($grt,$deck_cargo,$loa_cm,$rotNo);
				$rsPilotSubDetailResultBhatiari = $this->vbq->getPilotSubDetailsQueryBhatiari($grt,$deck_cargo,$loa_cm,$rotNo);
				// echo $pilotChargeSubDetailQueryBhatiari;
				// echo "<br>";
				// return;
				//$rsPilotSubDetailResultBhatiari = $this->bm->dataSelect($pilotChargeSubDetailQueryBhatiari);
				
				if(count($rsPilotSubDetailResultBhatiari)>0)
				{
					for($j=0;$j<count($rsPilotSubDetailResultBhatiari);$j++)
					{
						$description 	= $rsPilotSubDetailResultBhatiari[$j]['DESCRIPTION'];
						$gl_code 		= $rsPilotSubDetailResultBhatiari[$j]['GL_CODE'];
						$rate 			= $rsPilotSubDetailResultBhatiari[$j]['RATE'];
						$unit 			= $rsPilotSubDetailResultBhatiari[$j]['UNIT'];
						$bas 			= $rsPilotSubDetailResultBhatiari[$j]['BASE'];
						$move 			= $rsPilotSubDetailResultBhatiari[$j]['MOVE'];
											
						$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,unit_for_pilot,bas,move)
								VALUES('$draftNumber','$description','$gl_code','$rate','$unit','$bas','$move')";
						$insertSUB_DETAILS = $this->bm->dataInsertDb2($query);
						if($insertSUB_DETAILS == 0)
						{
							return "<font color='red'>Insertion SUB_DETAILS failed for ".$j."</font>";
						}
					}
				}
				else
				{
					return "<font color='red'>Sub Detail Bhatiari not found</font>";
				}
			}
		}
		else
		{
			return "<font color='red'>Pilot charge for Bhatiari not found</font>";
		}
		
		if(($insertDETAILS == 1) and ($insertSUB_DETAILS == 1))
		{
			// $rtnData = "<font color='green'>Bill Generated</font>";
			$rtnData = "BhatiariBillGenerated";
			return $rtnData;
		}
		else
		{
			$rtnData = "BhatiariBillSomethingWrong";
			return $rtnData;
		}
	} 
	// Bhatiari Bill - end
	
	// Kutubdia_Bill - start
	function Generate_Kutubdia_Bill()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{		
			$rotation = $this->input->post('rot');						
				
			if($rotation == null or $rotation == "")
			{
				$rotation = $this->uri->segment(3);
				$rotation = str_replace("_","/",$rotation);
			}
			
			$sql_chkBill = "SELECT COUNT(*) AS rtnValue
			FROM ".$this->Init_Table_Map("DETAILS")."
			WHERE rotation='$rotation' AND ".$this->Init_Table_Map("DETAILS").".bill_type='108'";
			
			$chkBill = $this->bm->dataReturnDb2($sql_chkBill);
			
			if($chkBill == 0)
			{
				$rtnData = $this->Generate_Kutubdia_Bill_Main($rotation);
				// echo "<br>---";
				// echo "<br>Kutubdia";
				// return;
				if($rtnData == "KutubdiaBillGenerated")
				{
					$sql_getVvdGkey = "SELECT vsl_vessel_visit_details.vvd_gkey AS rtnValue
					FROM vsl_vessel_visit_details
					WHERE ib_vyg='$rotation'";
					$vvdGkey = $this->bm->dataReturn($sql_getVvdGkey);
					
					$updateBillInfo = "UPDATE ctmsmis.vsl_forward_info
									SET ctmsmis.vsl_forward_info.billop_bill_stat='1',
									ctmsmis.vsl_forward_info.billop_bill_at=NOW(),
									ctmsmis.vsl_forward_info.billop_bill_by='$login_id',
									ctmsmis.vsl_forward_info.billop_bill_ip='$ipAddress'
									WHERE ctmsmis.vsl_forward_info.vvd_gkey='$vvdGkey'";
					$this->bm->dataUpdatedb2($updateBillInfo);
				}
											
				$msgRot = str_replace('/','_',$rotation);
				
				$msgRot = "billReady_".$msgRot;
							
				redirect('VesselBill/vesselBillListAcc/p/'.$msgRot, 'location');
			}
			else
			{
				echo $msg = "<font color='red'>Bill already created for $rotation</font>";
				echo "<br>";
				return;
			}
		}
	}
	function Generate_Kutubdia_Bill_Main($rotation)
	{
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		$insertDETAILS = 0;
		$insertSUB_DETAILS = 0;
		
		$pilotChargesQueryKutubdia = $this->vbq->getPilotChargesQueryKutubdia($rotation);
		$pilotChargesResultKutubdia = $this->bm->dataSelect($pilotChargesQueryKutubdia);
		
		$dollarRate = 0;
		if(count($pilotChargesResultKutubdia)>0)
		{
			$this->updateDollarRateIn42($pilotChargesResultKutubdia[0]['OA_DATE']);
			
			$sql_dollarRate = "SELECT rate
			FROM bil_currency_exchange_rates
			WHERE effective_date=DATE('".$pilotChargesResultKutubdia[0]['OA_DATE']."') ORDER BY gkey DESC
			LIMIT 1";			

			$rslt_dollarRate = $this->bm->dataSelectDB1($sql_dollarRate);

			for($i=0;$i<count($rslt_dollarRate);$i++)
			{
				$dollarRate = $rslt_dollarRate[$i]['rate'];
			}

			$berth_suffix = 1;
			for($i=0;$i<count($pilotChargesResultKutubdia);$i++)
			{
				$vvd_gkey = $pilotChargesResultKutubdia[$i]['VVD_GKEY'];
				$vsl_name = $pilotChargesResultKutubdia[$i]['VSL_NAME'];
				$rotNo = $pilotChargesResultKutubdia[$i]['ROTATION'];
				$oa_date = $pilotChargesResultKutubdia[$i]['OA_DATE'];
				$ata = $pilotChargesResultKutubdia[$i]['ATA'];
				$atd = $pilotChargesResultKutubdia[$i]['ATD'];
				$date_of_arrival = $pilotChargesResultKutubdia[$i]['DATE_OF_ARRIVAL'];
				$time_of_arrival = $pilotChargesResultKutubdia[$i]['TIME_OF_ARRIVAL'];
				$date_of_departure = $pilotChargesResultKutubdia[$i]['DATE_OF_DEPARTURE'];
				$time_of_departure = $pilotChargesResultKutubdia[$i]['TIME_OF_DEPARTURE'];
				$grt = $pilotChargesResultKutubdia[$i]['grt GRT'];
				$nrt = $pilotChargesResultKutubdia[$i]['nrt NRT'];
				$flag = $pilotChargesResultKutubdia[$i]['flag FLAG'];
				$cnt_code = $pilotChargesResultKutubdia[$i]['CNT_CODE'];
				$agent_code = $pilotChargesResultKutubdia[$i]['AGENT_CODE'];
				$agent_name = $pilotChargesResultKutubdia[$i]['AGENT_NAME'];
				$agent_address = $pilotChargesResultKutubdia[$i]['AGENT_ADDRESS'];
				$agent_alias_id = $pilotChargesResultKutubdia[$i]['AGENT_ALIAS_ID'];
				$deck_cargo = $pilotChargesResultKutubdia[$i]['DECK_CARGO'];
				$bill_type = $pilotChargesResultKutubdia[$i]['BILL_TYPE'];
				$loa_cm = $pilotChargesResultKutubdia[$i]['LOA_CM'];
				$exchangeRate = $dollarRate;
				
				$bill_name = "BILL FOR PORT & PILOTAGE CHARGES ON KUTUBDIA VESSEL";
				
				$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")." (rotation,vsl_name,ata,atd,agent_code,agent_name,oa_date,flag,cnt_code,grt,deck_cargo,exchangeRate,berth_suffix,bill_type,bill_name,creator,
				agent_alias_id,agent_address,ip_address,billing_date)
				VALUES('$rotNo','$vsl_name','$ata','$atd','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt','$deck_cargo','$exchangeRate',
				'$berth_suffix','$bill_type','$bill_name','$login_id','$agent_alias_id','$agent_address','$ipAddress',NOW())";	
				$insertDETAILS = $this->bm->dataInsert($insertQuery);
				
				if($insertDETAILS == 0)
				{
					return "<font color='red'>Insertion DETAILS failed</font>";
				}	

				$sql_draftNumber = "SELECT draftNumber AS rtnValue 
				FROM ".$this->Init_Table_Map("DETAILS")."
				WHERE ".$this->Init_Table_Map("DETAILS").".rotation='$rotNo' AND ".$this->Init_Table_Map("DETAILS").".bill_type='108'
				ORDER BY draftNumber DESC LIMIT 1";
				// echo $sql_draftNumber;return;
				$draftNumber = $this->bm->dataReturnDb2($sql_draftNumber);
				
				//$pilotChargeSubDetailQueryKutubdia = $this->vbq->getPilotSubDetailsQueryKutubdia($grt,$deck_cargo,$loa_cm,$rotNo);
				//$rsPilotSubDetailResultKutubdia = $this->bm->dataSelect($pilotChargeSubDetailQueryKutubdia);
				$rsPilotSubDetailResultKutubdia =  $this->vbq->getPilotSubDetailsQueryKutubdia($grt,$deck_cargo,$loa_cm,$rotNo);
				
				if(count($rsPilotSubDetailResultKutubdia)>0)
				{
					for($j=0;$j<count($rsPilotSubDetailResultKutubdia);$j++)
					{
						$description 	= $rsPilotSubDetailResultKutubdia[$j]['DESCRIPTION'];
						$gl_code 		= $rsPilotSubDetailResultKutubdia[$j]['GL_CODE'];
						$rate 			= $rsPilotSubDetailResultKutubdia[$j]['RATE'];
						$unit 			= $rsPilotSubDetailResultKutubdia[$j]['UNIT'];
						$bas 			= $rsPilotSubDetailResultKutubdia[$j]['BAS'];
						$move 			= $rsPilotSubDetailResultKutubdia[$j]['MOVE'];
											
						$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,unit_for_pilot,bas,move)
								VALUES('$draftNumber','$description','$gl_code','$rate','$unit','$bas','$move')";
						$insertSUB_DETAILS = $this->bm->dataInsertDb2($query);
						if($insertSUB_DETAILS == 0)
						{
							return "<font color='red'>Insertion SUB_DETAILS failed for ".$j."</font>";
						}
					}
				}
				else
				{
					return "<font color='red'>Sub Detail Bhatiari not found</font>";
				}
			}
		}
		else
		{
			return "<font color='red'>Pilot charge for Bhatiari not found</font>";
		}
		
		if(($insertDETAILS == 1) and ($insertSUB_DETAILS == 1))
		{
			// $rtnData = "<font color='green'>Bill Generated</font>";
			$rtnData = "KutubdiaBillGenerated";
			return $rtnData;
		}
		else
		{
			$rtnData = "KutubdiaBillSomethingWrong";
			return $rtnData;
		}
	}
	// Kutubdia_Bill - end
	
	function updateDollarRateIn42($effectiveDate)
	{
		$login_id = $this->session->userdata('login_id');
		
		// get dollar rate from N4
		$sql_dollarRateN4 = "SELECT rate AS rtnValue
		FROM bil_currency_exchange_rates
		WHERE effective_date=to_date('$effectiveDate','yyyy-mm-dd')";
		$dollarRateN4 = $this->bm->dataReturnDb3($sql_dollarRateN4);
		
		// check if dollar is in pcs for that date
		$sql_cntPCSDollarRate = "SELECT COUNT(*) AS rtnValue
								FROM cchaportdb.bil_currency_exchange_rates
								WHERE DATE(effective_date)=DATE('$effectiveDate')";
		$cntPCSDollarRate = $this->bm->dataReturnDb1($sql_cntPCSDollarRate);
		
		if($cntPCSDollarRate == 0)		// if no, insert
		{
			$sql_insertDollarRate = "INSERT INTO bil_currency_exchange_rates(rate,notes,effective_date,from_currency_gkey,to_currency_gkey,created,creator,currency_gkey)
			VALUES('$dollarRateN4','Value from N4',DATE('$effectiveDate'),'2','1',NOW(),'$login_id','1')";
			$this->bm->dataInsertDB1($sql_insertDollarRate);
		}
		else		// if yes, update
		{			
			$sql_updateDollarRate = "UPDATE bil_currency_exchange_rates
									SET rate='$dollarRateN4',notes='Update from N4',changed=NOW(),changer='$login_id'
									WHERE effective_date=DATE('$effectiveDate')";
			$this->bm->dataUpdateDb1($sql_updateDollarRate);
		}
	}
	





	// Vessel Bill Cancellation Bill - start
	function Generate_TugCancellation_Bill()
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
		
			$rotation = $this->input->post('rot');						
			$rotation = "2023/504";						
			
			if($rotation == null or $rotation == "")
			{
				$rotation = $this->uri->segment(3);
				$rotation = str_replace("_","/",$rotation);
			}
			
			$sql_chkBill = "SELECT COUNT(*) AS rtnValue
			FROM ".$this->Init_Table_Map("DETAILS")."
			WHERE rotation='$rotation' AND ".$this->Init_Table_Map("DETAILS").".bill_type='110'";
			$chkBill = $this->bm->dataReturnDb2($sql_chkBill);
			
			if($chkBill == 0)
			{				
				$rtnData = $this->Generate_TugCancellation_Bill_Main($rotation);
								
				if($rtnData == "TugCancellationBillGenerated")
				{
					$sql_getVvdGkey = "SELECT vsl_vessel_visit_details.vvd_gkey AS rtnValue
									FROM vsl_vessel_visit_details
									WHERE ib_vyg='$rotation'";
					$vvdGkey = $this->bm->dataReturn($sql_getVvdGkey);
					
					$updateBillInfo = "UPDATE ctmsmis.vsl_cancelation_forward_info
									SET ctmsmis.vsl_cancelation_forward_info.billop_bill_stat='1',
									ctmsmis.vsl_cancelation_forward_info.bill_gen_st='1',
									ctmsmis.vsl_cancelation_forward_info.billop_bill_at=NOW(),
									ctmsmis.vsl_cancelation_forward_info.billop_bill_by='$login_id',
									ctmsmis.vsl_cancelation_forward_info.billop_bill_ip='$ipAddress'
									WHERE ctmsmis.vsl_cancelation_forward_info.vvd_gkey='$vvdGkey'";
					$this->bm->dataUpdateDb2($updateBillInfo);
				}
											
				$msgRot = str_replace('/','_',$rotation);
				
				$msgRot = "billReady_".$msgRot;
							
				redirect('VesselBill/vesselBillListAcc/p/'.$msgRot, 'location');
			}
			else
			{
				echo $msg = "<font color='red'>Bill already created for $rotation</font>";
				echo "<br>";
				return;
			}
		}
	}
	
	function Generate_TugCancellation_Bill_Main($rotation)	
	{
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		$insertDETAILS = 0;
		$insertSUB_DETAILS = 0;
		
		$pilotChargesQueryTugCancellation = $this->vbq->getPilotChargesQueryTugCancellation($rotation);		
		$pilotChargesResultTugCancellation = $this->bm->dataSelect($pilotChargesQueryTugCancellation);
			
		$dollarRate = 0;
		if(count($pilotChargesResultTugCancellation)>0)
		{
			$offport_arrival_date = "";
			for($k=0;$k<count($pilotChargesResultTugCancellation);$k++)
			{
				$offport_arrival_date = $pilotChargesResultTugCancellation[$k]['OA_DATE'];
			}
			
			$this->updateDollarRateIn42($offport_arrival_date);
		
			$sql_dollarRate = "SELECT rate FROM bil_currency_exchange_rates 
								WHERE to_char(effective_date,'yyyy-mm-dd')='$offport_arrival_date' 
								ORDER BY gkey DESC fetch first 1 rows only";					
			$rslt_dollarRate = $this->bm->dataSelectDb3($sql_dollarRate);
			for($i=0;$i<count($rslt_dollarRate);$i++)
			{
				$dollarRate = $rslt_dollarRate[$i]['RATE'];
			}
			
			$berth_suffix = 1;
			for($i=0;$i<count($pilotChargesResultTugCancellation);$i++)
			{
				$vvd_gkey = $pilotChargesResultTugCancellation[$i]['VVD_GKEY'];
				$vsl_name = $pilotChargesResultTugCancellation[$i]['VSL_NAME'];
				$rotNo = $pilotChargesResultTugCancellation[$i]['ROTATION'];
				$oa_date = $pilotChargesResultTugCancellation[$i]['OA_DATE'];
				$ata = $pilotChargesResultTugCancellation[$i]['ATA'];
				$atd = $pilotChargesResultTugCancellation[$i]['ATD'];			
				$grt = $pilotChargesResultTugCancellation[$i]['GRT'];
				$nrt = $pilotChargesResultTugCancellation[$i]['NRT'];
				$flag = $pilotChargesResultTugCancellation[$i]['FLAG'];
				$cnt_code = $pilotChargesResultTugCancellation[$i]['CNT_CODE'];
				$agent_code = $pilotChargesResultTugCancellation[$i]['AGENT_CODE'];
				$agent_name = $pilotChargesResultTugCancellation[$i]['AGENT_NAME'];
				$agent_address = $pilotChargesResultTugCancellation[$i]['AGENT_ADDRESS'];
				$agent_alias_id = $pilotChargesResultTugCancellation[$i]['AGENT_ALIAS_ID'];
				$deck_cargo = $pilotChargesResultTugCancellation[$i]['DECK_CARGO'];
				$bill_type = $pilotChargesResultTugCancellation[$i]['BILL_TYPE'];
				$loa_cm = $pilotChargesResultTugCancellation[$i]['LOA_CM'];
				$exchangeRate = $dollarRate;
				
				$bill_name = "BILL FOR PORT & PILOTAGE CHARGES ON VESSEL";
								
				$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")." (rotation,vsl_name,ata,atd,agent_code,agent_name,flag,
								cnt_code,grt,deck_cargo,exchangeRate,berth_suffix,bill_type,bill_name,creator,agent_alias_id,agent_address,
								ip_address,billing_date, oa_date)
							VALUES('$rotNo','$vsl_name','$ata','$atd','$agent_code','$agent_name','$flag','$cnt_code','$grt','$deck_cargo',
								'$exchangeRate','$berth_suffix','$bill_type','$bill_name','$login_id','$agent_alias_id','$agent_address',
								'$ipAddress',NOW(), '$oa_date')";	
				$insertDETAILS = $this->bm->dataInsertDb2($insertQuery); 
				$insertDETAILS."<br>";
				if($insertDETAILS == 0)
				{
					return "<font color='red'>Insertion DETAILS failed</font>";
				}	
				
				$sql_draftNumber = "SELECT draftNumber AS rtnValue 
				FROM ".$this->Init_Table_Map("DETAILS")."
				WHERE ".$this->Init_Table_Map("DETAILS").".rotation='$rotNo' AND ".$this->Init_Table_Map("DETAILS").".bill_type='110'
				ORDER BY draftNumber DESC LIMIT 1";		
				$draftNumber = $this->bm->dataReturnDb2($sql_draftNumber);
				
				$rsPilotSubDetailResultTugCancellation =  $this->vbq->getPilotSubDetailsQueryTugCancellation($grt,$deck_cargo,$loa_cm,$rotNo);
				
				if(count($rsPilotSubDetailResultTugCancellation)>0)
				{
					for($j=0;$j<count($rsPilotSubDetailResultTugCancellation);$j++)
					{				
						$description 	= $rsPilotSubDetailResultTugCancellation[$j]['DESCRIPTION'];
						$gl_code 		= $rsPilotSubDetailResultTugCancellation[$j]['GL_CODE'];
						$rate 			= $rsPilotSubDetailResultTugCancellation[$j]['RATE'];
						$unit 			= $rsPilotSubDetailResultTugCancellation[$j]['UNIT'];
						$bas 			= $rsPilotSubDetailResultTugCancellation[$j]['BAS'];
						$move 			= $rsPilotSubDetailResultTugCancellation[$j]['MOVE'];
											
						$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,unit_for_pilot,bas,move)
								VALUES('$draftNumber','$description','$gl_code','$rate','$unit','$bas','$move')";
						$insertSUB_DETAILS = $this->bm->dataInsertDb2($query);
						if($insertSUB_DETAILS == 0)
						{
							return "<font color='red'>Insertion SUB_DETAILS failed for ".$j."</font>";
						}
					}
				}
				else
				{
					return "<font color='red'>Sub Detail Tug Cancellation not found</font>";
				}
			}			
		}
		else
		{
			return "<font color='red'>Pilot charge for Tug Cancellation not found</font>";
		}
		
		if(($insertDETAILS == 1) and ($insertSUB_DETAILS == 1))
		{
			
			$rtnData = "TugCancellationBillGenerated";
			return $rtnData;
		}
		else
		{
			$rtnData = "TugCancellationSomethingWrong";
			return $rtnData;
		}
	} 
	// Tug Cancellation Bill - end	
	











	
	// water bill - start
	function Generate_WaterSupply_Bill($rotation)		// pangaon query (waterSupplySubDetailQueryPangoan) is not in this function 
	{
		$rotation = $this->input->post('rot');						
			
		if($rotation == null or $rotation == "")
		{
			$rotation = $this->uri->segment(3);
			$rotation = str_replace("_","/",$rotation);
		}	
			
		// check water bill
		$sql_chkWaterBill = "SELECT COUNT(*) AS rtnValue		
		FROM ".$this->Init_Table_Map("DETAILS")."
		WHERE rotation = '$rotation' AND bill_type = '105'";
		$chkWaterBill = $this->bm->dataReturnDb2($sql_chkWaterBill);
		// echo $chkWaterBill;return;
		if($chkWaterBill>0)
		{
			return;
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ipAddress = $_SERVER['REMOTE_ADDR'];
						
			// work on dollar rate later//
			
			// check water event
			$waterSupplySubDetailQuery = $this->vbq->waterSupplySubDetailQuery($rotation);
			// echo $waterSupplySubDetailQuery;return;
			$waterSupplySubDetailResult = $this->bm->dataSelect($waterSupplySubDetailQuery);
			
			if(count($waterSupplySubDetailResult)==0)
			{
				// no event for that rotation
				return;
			}
			else
			{
				$getWaterSupplyQuery = $this->vbq->getWaterSupplyQuery($rotation);
				// echo $getWaterSupplyQuery;return;
				$vesselDetailResult = $this->bm->dataSelect($getWaterSupplyQuery);
				// echo $vesselDetailResult[0]['oa_date'];return;
				$dollarRate = "";
				if(count($vesselDetailResult)>0)
				{
					// dollar rate
					$this->updateDollarRateIn42($vesselDetailResult[0]['OA_DATE']);
				
					$sql_dollarRate = "SELECT rate
					FROM bil_currency_exchange_rates
					WHERE to_char(effective_date,'yyyy-mm-dd')=to_char('".$vesselDetailResult[0]['OA_DATE']."','yyyy-mm-dd') ORDER BY gkey DESC fetch first 1 rows only";
					
					
					// SELECT rate,effective_date,gkey
					// FROM bil_currency_exchange_rates 
					// WHERE to_char(effective_date,'yyyy-mm-dd')=to_char('','yyyy-mm-dd') ORDER BY gkey DESC fetch first 1 rows only
					
					// echo $sql_dollarRate;return;
					
					$rslt_dollarRate = $this->bm->dataSelectDb3($sql_dollarRate);
					
					for($i=0;$i<count($rslt_dollarRate);$i++)
					{
						// $dollarRate = $rslt_dollarRate[$i]['rate'];
						$dollarRate = $rslt_dollarRate[$i]['RATE'];
						
					}
					// dollar rate
					
					for($i=0;$i<count($vesselDetailResult);$i++)
					{
						

						$rotWater     = $vesselDetailResult[$i]['ROTATION'];
						$vsl_name     = $vesselDetailResult[$i]['VSL_NAME'];
						$ata          = $vesselDetailResult[$i]['ATA'];
						$atd          = $vesselDetailResult[$i]['ATD'];
						$berth        = $vesselDetailResult[$i]['BERTH'];  
						$agent_code   = $vesselDetailResult[$i]['AGENT_CODE'];  
						$agent_name   = $vesselDetailResult[$i]['AGENT_NAME'];  
						$agent_alias   = $vesselDetailResult[$i]['AGENT_ALIAS_ID'];  
						$agent_address   = $vesselDetailResult[$i]['ADDRESS'];  
						$oa_date      = $vesselDetailResult[$i]['OA_DATE'];  
						$flag         = $vesselDetailResult[$i]['FLAG'];  
						$cnt_code     = $vesselDetailResult[$i]['CNT_CODE'];  
						$grt          = $vesselDetailResult[$i]['GRT'];  
						$master_name  = $this->getMasterName($rotation);
						$deck_cargo   = $vesselDetailResult[$i]['DECK_CARGO'];  
						$exchangeRate = $dollarRate;
						$unit         = $vesselDetailResult[$i]['UNIT'];  
						$water_supply_dt = $vesselDetailResult[$i]['WATER_SUPPLY_DT']; 
						
						$bill_type = 105;
						$bill_name = "BILL FOR WATER CHARGES ON VESSEL";
											
						$insertWaterQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")."(rotation,vsl_name,ata,atd,berth,agent_code,agent_name,oa_date,flag,cnt_code,grt,master_name,deck_cargo,exchangeRate,unit,
						bill_type,bill_name,creator,ip_address,billing_date,agent_alias_id,agent_address,water_supply_dt)
						VALUES('$rotWater','$vsl_name','$ata','$atd','$berth','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt',
						'$master_name','$deck_cargo','$exchangeRate','$unit','$bill_type','$bill_name','$login_id','$ipAddress',NOW(),'$agent_alias',
						'$agent_address','$water_supply_dt')";
						$insertWaterDetails = $this->bm->dataInsertDb2($insertWaterQuery);
						
						if($insertWaterDetails == 0)
						{
										
						}
					
						$sql_draftNumberWater = "SELECT draftNumber AS rtnValue
											FROM ".$this->Init_Table_Map("DETAILS")."
											WHERE rotation='$rotWater' AND bill_type='105'";
						$draftNumberWater = $this->bm->dataReturnDb2($sql_draftNumberWater );
						

						if(count($waterSupplySubDetailResult)>0){

						
							for($j=0;$j<count($waterSupplySubDetailResult);$j++)
							{
								
								$bas = $waterSupplySubDetailResult[$j]['BAS'];
								$unit = $waterSupplySubDetailResult[$j]['UNIT'];
								$id = $waterSupplySubDetailResult[$j]['ID'];	


									$subDetailBillingQuery="SELECT *
									FROM bil_tariffs  
									INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
									WHERE bil_tariffs.id='$id' and bil_tariff_rates.rate_type='REGULAR' ";
									$subDetailBillingRes=$this->bm->dataSelectDb3($subDetailBillingQuery);
									if(count($subDetailBillingRes)>0){

										for($k=0;$k<count($subDetailBillingRes);$k++){

											$description = $subDetailBillingRes[$k]['DESCRIPTION'];
											$gl_code = $subDetailBillingRes[$k]['GL_CODE'];
											$rate = $subDetailBillingRes[$k]['RATE'];

										$insertWaterSubDetail = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot,move) 
										VALUES('$draftNumberWater','$description','$gl_code','$rate','$bas','$unit','1')";
										$rslt_insertWaterSubDetail = $this->bm->dataInsertDb2($insertWaterSubDetail);
										
										if($rslt_insertWaterSubDetail==0)
										{
											
										}
										}
									}
							}	// for loop waterSupplySubDetailResult						
						
						}

					}	// for loop vesselDetailResult
					
					// update info
					$sql_updateWaterDemandInfo = "UPDATE ctmsmis.water_demand_info
					SET bill_op_bill_st='1',bill_op_bill_by='$login_id',bill_op_bill_at=NOW(),bill_op_bill_ip='$ipAddress'
					WHERE rotation_no='$rotation'";
					$rslt_updateWaterDemandInfo = $this->bm->dataUpdateDb2($sql_updateWaterDemandInfo);
					
				} // if count vesselDetailResult
				else
				{
					
				}
			}
						
			
			
			$msgRot = str_replace('/','_',$rotation);
			$msgRot = "billReady_".$msgRot;
			
			redirect('VesselBill/vesselBillListAcc/p/'.$msgRot, 'location');
		}				
	}
	// water bill - end
	
	// Generate Tug Hiring Bill Starts...
	function tugHireBill()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$tug_hire_id = $this->input->post("tug_hire_id");
			$location = $this->input->post("location");
			
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			$login_id = $this->session->userdata('login_id');
			$org_Type_id =$this->session->userdata('org_Type_id');
			$section =$this->session->userdata('section');
			
			
			$data['tug_hire_id']=$tug_hire_id;
			$data['location']=$location;
			
			$rotation = "";
			$queryTugHireDtls = "SELECT * FROM tug_hire WHERE id='$tug_hire_id'";
			$tugHireDtls = $this->bm->dataSelectDB1($queryTugHireDtls);
			for($i=0;$i<count($tugHireDtls);$i++){
				$rotation = $tugHireDtls[$i]["rotation"];
			}
			
			
			if($location == "inside")
			{
				$sql_chkBill = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.mis_vsl_billing_detail_test 
								WHERE rotation='$rotation'";				
				$chkBill = $this->bm->dataReturnDb2($sql_chkBill);
				if($chkBill == 0)
				{			
					// New Tug Hiring Bill					
					$rtnData = $this->generateTugHiringBill($rotation);
					if($rtnData == "BillGenerated")
					{
						$sql_vviId = "SELECT id AS rtnValue FROM outer_vsl_visit_info WHERE imp_rot='$rotation'";
						$vviId = $this->bm->dataReturnDb1($sql_vviId);
						
						$sql_updateBillStat = "UPDATE outer_vsl_forward_info
						SET billop_bill_stat='1',billop_bill_by='$login_id',billop_bill_at=NOW(),billop_bill_ip='$ipAddress'
						WHERE vsl_visit_id='$vviId'";
						$this->bm->dataUpdateDb1($sql_updateBillStat);
						$msg = "<font color='green'>Bill generated for ".$rotation."</font>";
					}
					else
					{
						$msg = "<font color='red'>Bill generation failed ".$rotation."</font>";
					}				
				}
				else
				{
					$msg = "<font color='red'><b>Bill already generated for ".$rotation."</b></font>";
				}
			}
			else if($location == "outside")
			{
				
			}
			else
			{
				
			}
			die();
			$html=$this->load->view('tug_hiring_bill',$this->data, true);			 
			$pdfFilePath ="Tug Hiring Bill -".time()."-download.pdf";
			
			// $pdfFilePath ="Vessel Bill-".time()."-download.pdf";
			$pdf = $this->m_pdf->load();
			// $pdf->allow_charset_conversion = true;
			// $pdf->charset_in = 'iso-8859-4';
					
			//$stylesheet = file_get_contents('assets/stylesheets/billView.css');				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);				 
			$pdf->Output($pdfFilePath, "I");
		}
	}
	
	function generateTugHiringBill($rotation)
	{
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		$insertDETAILS = 0;
		$insertSUB_DETAILS = 0;
		
		$pilotChargesQuery = $this->vbq->getTugHiringVesselDtls($rotation);
		$pilotChargesResult = $this->bm->dataSelectDb1($pilotChargesQuery);

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
			
		
			
			$sql_offPortArr = "SELECT to_char(off_port_arr,'yyyy-mm-dd') as off_port_arr  FROM vsl_vessel_visit_details	
								WHERE ib_vyg='$rotNo'";
			$rslt_offPortArr = $this->bm->dataSelectdb4($sql_offPortArr);
			
			$offPortArr = "";
			
			for($j=0;$j<count($rslt_offPortArr);$j++)
			{
				 $offPortArr = $rslt_offPortArr[$j]['OFF_PORT_ARR'];
			}
			
			$this->updateDollarRateIn42($offPortArr);		
			
			$sql_dollarRate = "SELECT rate
							FROM cchaportdb.bil_currency_exchange_rates
							WHERE effective_date=DATE('$offPortArr')
							ORDER BY gkey DESC
							LIMIT 1";
			
			$rslt_dollarRate = $this->bm->dataSelectDB1($sql_dollarRate);
					
			for($j=0;$j<count($rslt_dollarRate);$j++)
			{
				 $dollarRate = $rslt_dollarRate[$j]['rate'];
			}
			
			$exchangeRate = $dollarRate;
			
			$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")." (rotation,vsl_name,ata,atd,agent_code,agent_name,oa_date,flag,cnt_code,grt,deck_cargo,
			exchangeRate,berth_suffix,bill_type,
			bill_name,creator,agent_alias_id,agent_address,ip_address,billing_date)
			VALUES('$rotNo','$vsl_name','$ata','$atd','$agent_code','$agent_name', '$offPortArr', '$flag','$cnt_code','$grt',
			'$deck_cargo','$exchangeRate','$berth_suffix','$bill_type','$bill_name','$user_name','$agent_alias_id',
			'$agent_address','$ipAddress',NOW())";	
			$insertDETAILS = $this->bm->dataInsertDb2($insertQuery);
			
			if($insertDETAILS == 0)
			{
				return "<font color='red'>Insertion DETAILS failed</font>";
			}	
			
			$sql_draftNumber = "SELECT draftNumber AS rtnValue 
							FROM ".$this->Init_Table_Map("DETAILS")."
							ORDER BY draftNumber DESC LIMIT 1";
			$draftNumber = $this->bm->dataReturnDb2($sql_draftNumber);
			
			$rsPilotSubDetailResult = $this->vbq->getSubDetailsQueryTugHiringInsidePort($grt,$deck_cargo);
			
			if(count($rsPilotSubDetailResult)>0)
			{
				for($j=0;$j<count($rsPilotSubDetailResult);$j++)
				{
				

					$description 	= $rsPilotSubDetailResult[$j]['DESCRIPTION'];
					$gl_code 		= $rsPilotSubDetailResult[$j]['GL_CODE'];
					$rate 			= $rsPilotSubDetailResult[$j]['RATE'];
					$unit 			= $rsPilotSubDetailResult[$j]['UNIT'];
					$bas 			= $rsPilotSubDetailResult[$j]['BAS'];
					$move 			= $rsPilotSubDetailResult[$j]['MOVE'];
										
					$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS").
								"(draftNumber,description,gl_code,rate,unit_for_pilot,bas,move)
							VALUES('$draftNumber','$description','$gl_code','$rate','$unit','$bas','$move')";
					$insertSUB_DETAILS = $this->bm->dataInsertDb2($query);
					if($insertSUB_DETAILS == 0)
					{
						return "<font color='red'>Insertion SUB_DETAILS failed for ".$j."</font>";
					}
				}
			}
			else
			{				
				return "<font color='red'>rsPilotSubDetailResult not entering SUB_DETAILS failed for ".$j."</font>";
			}				
		}
		
		
		
		if(($insertDETAILS == 1) and ($insertSUB_DETAILS == 1))
		{
			$rtnData = "BillGenerated";
			return $rtnData;
		}
		else
		{
			$rtnData = "SomethingWrong";
			return $rtnData;
		}
	}
	// Generate Tug Hiring Bill Ends...
	
}
?>