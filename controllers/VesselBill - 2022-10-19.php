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
			
			$chkBill = $this->bm->dataReturn($sql_chkBill);
			// echo $chkBill;return;
			if($chkBill == 0)
			{										
				// exchangeRate
				/* if(!$this->checkExchangeRate($rotation))
				{
					echo $msg = "<font color='red'>NO EXCHANGE RATE ???? </font>";
					return;
				} */
				
				// ####################### Generate_Jetty_Charges_Bill - start #######################
				
				$this->Generate_Jetty_Charges_Bill($rotation);
				
				// ####################### Generate_Jetty_Charges_Bill - end #######################									
						
				// ####################### Generate_Pilot_Charges_Bill - start #######################
			
				$this->Generate_Pilot_Charges_Bill($rotation);
				
				// ####################### Generate_Pilot_Charges_Bill - end #######################
			
				// ####################### Generate_Fireman_Bill - start #######################
				
				// before uncomment - correction for dollar rate is necessary
				
				/* $getFirePumpQuery = $this->vbq->getFirePumpQuery($rotation);
				
				$subDetailResult = $this->bm->dataSelect($getFirePumpQuery);
				
				if(count($subDetailResult) == 0)
				{
					echo $msg = "<font color='red'>getFirePumpQuery returns 0</font>";
					echo "<br>";					
				}
							
				$vesselDetailQuery = $this->vbq->getJettyChargesQuery($rotation);
				
				$vesselDetailResult = $this->bm->dataSelect($vesselDetailQuery);
				
				if(count($vesselDetailResult)>0)
				{
					for($i=0;$i<count($vesselDetailResult);$i++)
					{
						$rotVDR 		= $vesselDetailResult[$i]['rotation'];
						$vsl_name 		= $vesselDetailResult[$i]['vsl_name'];
						$ata 			= $vesselDetailResult[$i]['ata'];
						$atd 			= $vesselDetailResult[$i]['atd'];
						$berth 			= $vesselDetailResult[$i]['berth'];
						$agent_code 	= $vesselDetailResult[$i]['agent_code'];
						$agent_name 	= $vesselDetailResult[$i]['agent_name'];
						$oa_date 		= $vesselDetailResult[$i]['oa_date'];
						$flag 			= $vesselDetailResult[$i]['flag'];
						$cnt_code 		= $vesselDetailResult[$i]['cnt_code'];
						$grt 			= $vesselDetailResult[$i]['grt'];
						$master_name  	= $this->getMasterName($rotation);		
						$deck_cargo 	= $vesselDetailResult[$i]['deck_cargo'];
						// $exchangeRate 	= $vesselDetailResult[$i]['exchangeRate'];
						$exchangeRate = $dollarRate;						
						$unit 			= $vesselDetailResult[$i]['unit'];		

						$bill_type = "104";
						$bill_name = "Firework Bill";
						 
						$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")."(rotation,vsl_name,ata,atd,berth,agent_code,agent_name,oa_date,flag,cnt_code,grt,master_name,deck_cargo,exchangeRate,unit,bill_type,
						bill_name,creator,ip_address,billing_date)
						VALUES('$rotVDR','$vsl_name','$ata','$atd','$berth','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt','$master_name',
						'$deck_cargo','$exchangeRate','$unit','$bill_type','$bill_name','$login_id','$ipAddress',NOW())";
						
						$vdrInsertFlag = $this->bm->dataInsert($insertQuery);
						
						if($vdrInsertFlag == 0)
						{
							echo $msg = "<font color='red'>vdrInsertFlag stopped at $i</font>";
							echo "<br>";
							return;
						}
						
						$sql_draftNumber = "SELECT draftNumber AS rtnValue
						FROM ".$this->Init_Table_Map("DETAILS")."
						WHERE rotation='$rotVDR' AND bill_type='$bill_type'";
						$draftNumberVDR = $this->bm->dataReturn($sql_draftNumber);
						
						if(count($subDetailResult)>0)
						{
							for($j=0;$j<count($subDetailResult);$j++)
							{
								$description = $subDetailResult[$j]['description'];
								$gl_code = $subDetailResult[$j]['gl_code'];
								$rate = $subDetailResult[$j]['rate'];
								$bas = $subDetailResult[$j]['bas'];
								$unit = $subDetailResult[$j]['unit'];										
											  
								$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot,move)
								VALUES('$draftNumberVDR','$description','$gl_code','$rate','$bas','$unit','1')";
								
								$subDtlRsltFlag = $this->bm->dataInsert($query);
								
								if($subDtlRsltFlag == 0)
								{
									echo $msg = "<font color='red'>subDetailResult insert stopped at $j</font>";
									echo "<br>";
									return;
								}
							}
						}
						else
						{
							echo $msg = "<font color='red'>subDetailResult not found</font>";
							echo "<br>";						
						}	
					}		// $vesselDetailResult for loop - i=0
				}
				else
				{					
					echo $msg = "<font color='red'>vesselDetailResult not found</font>";
					echo "<br>";
					// return;
				}
				
				$firemanChrgFlag = 1;	 */		
				// ####################### Generate_Fireman_Bill - end #######################
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
				$sql_getVvdGkey = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey AS rtnValue
				FROM sparcsn4.vsl_vessel_visit_details
				WHERE ib_vyg='$rotation'";
				$vvdGkey = $this->bm->dataReturn($sql_getVvdGkey);
				
				$updateBillInfo = "UPDATE ctmsmis.vsl_forward_info
								SET ctmsmis.vsl_forward_info.billop_bill_stat='1',
								ctmsmis.vsl_forward_info.billop_bill_at=NOW(),
								ctmsmis.vsl_forward_info.billop_bill_by='$login_id',
								ctmsmis.vsl_forward_info.billop_bill_ip='$ipAddress'
								WHERE ctmsmis.vsl_forward_info.vvd_gkey='$vvdGkey'";
				$this->bm->dataUpdate($updateBillInfo);
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
			$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,sr_acnt_forward_at AS forwarded_dt
			FROM outer_vsl_visit_info
			INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
			INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
			INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
			WHERE outer_vsl_forward_info.sr_acnt_forward_stat='1' AND outer_vsl_forward_info.billop_bill_stat='0'
			ORDER BY date_of_arrival DESC";
			
			$departData = $this->bm->dataSelectDb1($departQuery);

			$data['departData']=$departData;
			// $data['fromDate']=$fromDate;
			// $data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;


			/* $this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			// $this->load->view('vesselForwardOuterAnchorage',$data);			
			$this->load->view('vesselForwardList_notEntering',$data);			
			$this->load->view('jsAssets'); */
			
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
		// echo $pilotChargesQuery;return;
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
				// $agent_code = $pilotChargesResult[$i]['agent_id'];
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
				
				// $sql_exchangeRate = "SELECT rate AS rtnValue FROM billing.bil_currency_exchange_rates ORDER BY effective_date DESC LIMIT 1";
				
				// dollar rate - start
				
				$sql_dollarRate = "SELECT rate
				FROM bil_currency_exchange_rates
				WHERE effective_date='".$pilotChargesResult[$i]['date_of_arrival']."' ORDER BY gkey DESC
				LIMIT 1";
				
				$rslt_dollarRate = $this->bm->dataSelectDB1($sql_dollarRate);
				
				for($i=0;$i<count($rslt_dollarRate);$i++)
				{
					$dollarRate = $rslt_dollarRate[$i]['rate'];
				}
				
				// dollar rate - end
				
				$exchangeRate = $dollarRate;
				
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
				
				if(count($rsPilotSubDetailResult)>0)
				{
					for($j=0;$j<count($rsPilotSubDetailResult);$j++)
					{
						$description 	= $rsPilotSubDetailResult[$j]['description'];
						$gl_code 		= $rsPilotSubDetailResult[$j]['gl_code'];
						$rate 			= $rsPilotSubDetailResult[$j]['rate'];
						$unit 			= $rsPilotSubDetailResult[$j]['unit'];
						$bas 			= $rsPilotSubDetailResult[$j]['bas'];
						$move 			= $rsPilotSubDetailResult[$j]['move'];
											
						$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,unit_for_pilot,bas,move)
								VALUES('$draftNumber','$description','$gl_code','$rate','$unit','$bas','$move')";
						$insertSUB_DETAILS = $this->bm->dataInsert($query);
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
	
	// function vesselBillListAcc($action=null,$msg = null)		// from vesselBillList of Report, check for backup
	// {
	// 	$session_id = $this->session->userdata('value');			
	// 	$LoginStat = $this->session->userdata('LoginStat');
	// 	//Menu Expanding....
	// 	$this->session->set_userdata(array('menu' => "bill"));
	// 	$this->session->set_userdata(array('sub_menu' => "vesselBillListAcc/".$action));
	// 	//Menu Expanding....
		
	// 	if($LoginStat!="yes")
	// 	{
	// 		$this->logout();
	// 	}
	// 	else
	// 	{				
	// 		$section = $this->session->userdata('section');
	// 		$login_id = $this->session->userdata('login_id');
	// 		$orgTypeId = $this->session->userdata('org_Type_id');

	// 		$msgRot = "";
	// 		if($msg =='ok'){
	// 			$msg = "<font color='green'>Bill Approved.</font>";
	// 		}
	// 		else if($msg!="")
	// 		{
	// 			$msgArr = explode("_",$msg);
				
	// 			$msgRot = $msgArr[1]."/".$msgArr[2];
				
	// 			if($msgArr[0] == 'billReady')
	// 			{
	// 				$msg = "<font color='green' size='3'>Bill Generated for $msgRot</font>";
	// 			}
	// 			else if($msgArr[0] == 'billFailed')
	// 			{
	// 				$msg = "<font color='red' size='3'>Bill Not Generated for $msgRot</font>";
	// 			}
	// 			else
	// 			{
	// 				$msg = "<font color='red' size='3'>No status message</font>";
	// 			}
	// 		}
						
	// 		$sql_bill_list = "";
			
	// 		$cond = "";
	// 		if($section == "billop")
	// 		{
	// 			$cond = " AND creator='$login_id'";
				
	// 			if($msgRot!="")
	// 			{
	// 				$cond = $cond." AND rotation='$msgRot'";
	// 			}
	// 		}
	// 		else if($orgTypeId == 1)	// for MLO
	// 		{
	// 			$agentCode = $this->session->userdata('agentCode');
	// 			$cond = " AND agent_code='$agentCode'";
	// 		}
			
	// 		if($action == "p"){
	// 			$data['title']="VESSEL BILL LIST (Pending)";
				
	// 			// $sql_bill_list="SELECT draftNumber,finalNumber,
	// 			// IF(finalNumber IS NULL OR finalNumber='',
	// 			// (IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS billNumber,
	// 			// rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st,creator
	// 			// FROM ".$this->Init_Table_Map("DETAILS")."
	// 			// WHERE acc_apprv_st = 0 ".$cond." ORDER BY draftNumber DESC";
				
	// 			$sql_bill_list="SELECT draftNumber,finalNumber, cnt_code,bill_type,
	// 			IF(finalNumber IS NULL OR finalNumber='', 
	// 			(IF(cnt_code='BD',CONCAT(if(bill_type='101','JL/','PL/'),".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT(if(bill_type='101','JF/','PF/'),".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS billNumber, rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st,creator 
	// 			FROM ".$this->Init_Table_Map("DETAILS")." 
	// 			WHERE acc_apprv_st = 0 ".$cond." ORDER BY draftNumber DESC";			
	// 		} else {
	// 			if($orgTypeId==1)
	// 				$data['title']="VESSEL BILL LIST";
	// 			else
	// 				$data['title']="VESSEL BILL LIST (Approved)";								

	// 			// $sql_bill_list="SELECT draftNumber,finalNumber,
	// 			// IF(finalNumber IS NULL OR finalNumber='',(IF(cnt_code='BD',CONCAT('PL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT('PF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS billNumber,
	// 			// rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st,creator
	// 			// FROM ".$this->Init_Table_Map("DETAILS")."
	// 			// WHERE acc_apprv_st = 1 ".$cond." ORDER BY draftNumber DESC";
				
	// 			$sql_bill_list="SELECT draftNumber,finalNumber, cnt_code,bill_type,
	// 			IF(finalNumber IS NULL OR finalNumber='', 
	// 			(IF(cnt_code='BD',CONCAT(if(bill_type='101','JL/','PL/'),".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),CONCAT(if(bill_type='101','JF/','PF/'),".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS billNumber, rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st,creator 
	// 			FROM ".$this->Init_Table_Map("DETAILS")." 
	// 			WHERE acc_apprv_st = 1 ".$cond." ORDER BY draftNumber DESC";
	// 		}
	// 		// echo $sql_bill_list;return;
			
	// 		$rslt_bill_list=$this->bm->dataSelect($sql_bill_list);						
			
	// 		$data['rslt_bill_list']=$rslt_bill_list;
	// 		// $data['start']=$start;
	// 		// $data["links"] = $this->pagination->create_links();
	// 		$data['action'] = $action;
	// 		$data['msg'] = $msg;
			
	// 		$this->load->view('cssAssetsList');
	// 		$this->load->view('headerTop');
	// 		$this->load->view('sidebar');
	// 		$this->load->view('Vessel/vesselBillListAcc',$data);			
	// 		$this->load->view('jsAssetsList');		
	// 	}
	// }

	// Added by Kawsar on 17 oct 2022 -- start

	function vesselBillListAcc($action=null,$msg = null)		// from vesselBillList of Report, check for backup
	{
			// echo "hello";return;
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "bill"));
		$this->session->set_userdata(array('sub_menu' => "vesselBillListAcc/".$action));
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

				// if($orgTypeId==82){
				// 	$cond.=" AND disputeraised = 1 AND forward_post_mod_st = 1";
				// }

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

			$rslt_bill_list=$this->bm->dataSelect($sql_bill_list);						
			
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

			if($this->bm->dataInsert($insertQuery)){
				$updateQuery = "Update ".$this->Init_Table_Map("DETAILS")." set disputeraised = 1 WHERE draftNumber = '$draft'";
				if($this->bm->dataUpdate($updateQuery)){
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
			if($this->bm->dataUpdate($query)){
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
			if($this->bm->dataUpdate($query)){
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
		if($bill_type == 101)
		{						
			$bill_sql = "SELECT invoiceDesc,draftNumber,vesselName,ibVoyageNbr,captain,ATD,ATA,customerName,payeecustomerkey,agent_address,grossRevenueTons,
			exchangeRate,created,berth,flagcountry,cargo,ffd,description,glcode,rateBilled,quantityUnit,SUM(quantityBilled) AS quantityBilled,
			creator,ROUND(SUM(totusd),4) AS totusd,ROUND(SUM(totbsd),2) AS totbsd,ROUND(SUM(vatbd),2) AS vatbd,STATUS,acc_apprv_by,oa_date
			FROM(
			SELECT bill_name AS invoiceDesc,
			
			IF((finalNumber IS NULL OR finalNumber=''),(IF(cnt_code='BD',CONCAT('JL/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)),
			CONCAT('JF/',".$this->Init_Table_Map("DETAILS").".draftNumber,'-',SUBSTRING(billing_date,4,1)))),finalNumber) AS draftNumber,
					
			vsl_name AS vesselName,rotation AS ibVoyageNbr,master_name AS captain,
			CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS ATD,
			CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ATA,			
			agent_name AS customerName,
			
			-- CONCAT(agent_code,'(',IFNULL(agent_alias_id,''),')') AS payeecustomerkey,
			agent_alias_id AS payeecustomerkey,
			
			agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,
			
			CONCAT(DATE_FORMAT(billing_date,'%d/%m/%Y'),' ',TIME(billing_date)) AS created,
			berth AS berth,flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,description AS description,gl_code AS glcode,rate AS rateBilled,bas AS quantityUnit,
			IF(description LIKE 'BERTH_HIRE_1%',".$this->Init_Table_Map("DETAILS").".unit,".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot) AS quantityBilled,IF(description LIKE 'BERTH_HIRE_1%',((grt+IFNULL(deck_cargo,0))*rate*unit),(rate*".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot)) AS totusd,creator,
			IF(description LIKE 'BERTH_HIRE_1%',((grt+IFNULL(deck_cargo,0))*rate*unit*exchangeRate),(rate*".$this->Init_Table_Map("SUB_DETAILS").".unit_for_pilot*exchangeRate)) AS totbsd,
			(SELECT IF(DATE(ata)>='2017-12-27',ROUND(totusd*15/100,4),0)) AS vatusd,
			(SELECT vatusd*exchangeRate) AS vatbd,'DRAFT' AS STATUS,acc_apprv_by,
			
			-- oa_date
			CONCAT(DATE_FORMAT(oa_date,'%d/%m/%Y'),' ',TIME(oa_date)) AS oa_date
			
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type'
			ORDER BY draftNumber,description) AS tbl
			GROUP BY description";
		}
		else if($bill_type == 102)		// BILL FOR PORT & PILOTAGE CHARGES ON VESSEL
		{						
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
			
			-- CONCAT(agent_code,'(',IFNULL(agent_alias_id,''),')') AS payeecustomerkey,
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
			
			-- oa_date
			CONCAT(DATE_FORMAT(oa_date,'%d/%m/%Y'),' ',TIME(oa_date)) AS oa_date
			
			FROM ".$this->Init_Table_Map("DETAILS")."
			INNER JOIN ".$this->Init_Table_Map("SUB_DETAILS")." ON ".$this->Init_Table_Map("SUB_DETAILS").".draftNumber=".$this->Init_Table_Map("DETAILS").".draftNumber
			WHERE ".$this->Init_Table_Map("DETAILS").".draftNumber='$draftNumber' AND bill_type='$bill_type') AS tbl
			GROUP BY description";
		}
		else if($bill_type == 106)		// 106 = Not Entering
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
			
			-- agent_code AS payeecustomerkey,
			agent_alias_id AS payeecustomerkey,
			
			agent_address,grt AS grossRevenueTons,exchangeRate AS exchangeRate,
			CONCAT(DATE_FORMAT(billing_date,'%d/%m/%Y'),' ',TIME(billing_date)) AS created,
			flag AS flagcountry,deck_cargo AS cargo,oa_date AS ffd,pilot_ob_onboard AS onboundpiloton,pilot_ob_offboard AS onboundpilotoff,
			pilot_ib_onboard AS inboundpiloton,pilot_ib_offboard AS inboundpilotoff,description AS description,CONCAT(gl_code,'0') AS glcode,
			rate AS rateBilled,bas AS quantityUnit,unit_for_pilot AS quantityBilled,move,(rate*unit_for_pilot*move) AS totusd,
			(rate*unit_for_pilot*move*exchangeRate) AS bdChraged,
			'DRAFT' AS STATUS,creator,IF(DATE(ata)>='2017-12-27',1,0) AS vtdt,		
			(((rate*unit_for_pilot*move)*15)/100) AS vatusd,acc_apprv_by,
			
			-- oa_date
			CONCAT(DATE_FORMAT(oa_date,'%d/%m/%Y'),' ',TIME(oa_date)) AS oa_date
			
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
		
		if($bill_type == 101)
		{
			$html=$this->load->view('Vessel/vesselBill_JettyChargesOnVessel',$this->data, true);	
		}
		else if($bill_type == 102)		// BILL FOR PORT & PILOTAGE CHARGES ON VESSEL
		{
			$html=$this->load->view('Vessel/vesselBill_BillForPortAndPilotageChargesOnVessel',$this->data, true);			
		}
		else if($bill_type == 106)		// 106 = Not Entering
		{
			$html=$this->load->view('Vessel/vesselBillIbnoundOnly',$this->data, true);			 
		}
		
		$pdfFilePath ="Vessel Bill-".time()."-download.pdf";
		$pdf = $this->m_pdf->load();
		// $pdf->allow_charset_conversion = true;
		// $pdf->charset_in = 'iso-8859-4';
				
		$stylesheet = file_get_contents('assets/stylesheets/billView.css');				
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);				 
		$pdf->Output($pdfFilePath, "I");
	}
	
	// Vessel bill delete - start
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
	}
	
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
			$rslt_bankStatement = $this->bm->dataSelect($sql_bankStatement);
			
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
			
			$rslt_billwiseStatement = $this->bm->dataSelect($sql_billwiseStatement);
			
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
			$rslt_periodicStatement = $this->bm->dataSelect($sql_periodicStatement);
			
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
			$rslt_monthlyStatement = $this->bm->dataSelect($sql_monthlyStatement);
			
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
			// ####################### Generate_Jetty_Charges_Bill - start #######################
				
			$chkPangoanVsl = strtoupper(substr($rotation,5,1));
			
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
				
				$sql_dollarRate = "SELECT rate
				FROM bil_currency_exchange_rates
				WHERE effective_date='".$jettyChargesResult[0]['oa_date']."' ORDER BY gkey DESC
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
					$rotJCR     = $jettyChargesResult[$i]['rotation'];		// JCR = Jetty Charges Result
					$vsl_name     = $jettyChargesResult[$i]['vsl_name'];
					$ata          = $jettyChargesResult[$i]['ata'];
					$atd          = $jettyChargesResult[$i]['atd'];
					$berth        = $jettyChargesResult[$i]['berth'];
					$agent_alias_id = $jettyChargesResult[$i]['agent_alias_id'];
					$agent_code   = $jettyChargesResult[$i]['agent_code'];
					$agent_name   = $jettyChargesResult[$i]['agent_name'];
					$agent_address   = $jettyChargesResult[$i]['address'];
					$oa_date      = $jettyChargesResult[$i]['oa_date'];
					$flag         = $jettyChargesResult[$i]['flag'];
					$cnt_code     = $jettyChargesResult[$i]['cnt_code'];
					$grt          = $jettyChargesResult[$i]['grt'];
					$master_name  = $this->getMasterName($rotation);		// check later for empty master name					
					$deck_cargo   = $jettyChargesResult[$i]['deck_cargo'];
					// $exchangeRate = $jettyChargesResult[$i]['exchangeRate'];
					$exchangeRate = $dollarRate;
					$unit         = $jettyChargesResult[$i]['unit'];
					$vvdGkey      = $jettyChargesResult[$i]['vvd_gkey'];
					$rtdt         = $jettyChargesResult[$i]['rtdt'];
					
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
					$rsltCheckifExists = $this->bm->dataSelect($checkIfExists);
					
					$cntIfExists = 0;
					for($j = 0;$j<count($rsltCheckifExists);$j++)
					{
						$cntIfExists = $rsltCheckifExists[$j]['rotCount'];
					}			
					
					if($cntIfExists > 0)
					{
						continue;					
					}				
					
					$bill_type = $jettyChargesResult[$i]['bill_type'];
					
					// insertQuery
					$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")." (rotation,vsl_name,ata,atd,berth,agent_code,agent_name,oa_date,flag,cnt_code,grt,master_name,deck_cargo,exchangeRate,unit,berth_suffix,
					bill_type,bill_name,creator,agent_alias_id,agent_address,ip_address,billing_date)
					VALUES('$rotJCR','$vsl_name','$ata','$atd','$berth','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt','$master_name',
					'$deck_cargo','$exchangeRate','$unit','$berth_suffix','$bill_type','$bill_name','$login_id','$agent_alias_id','$agent_address',
					'$ipAddress',NOW())";
					// echo $insertQuery;return;
					
					$insertDETAILS = $this->bm->dataInsert($insertQuery);
					
					if($insertDETAILS == 0)
					{
						//echo $msg = "<font color='red'>jettyChargesResult Not Successful at $i...</font>";
						//echo "<br>";
						// return;					
					}
					
					$sql_draftNumber = "SELECT draftNumber AS rtnValue
					FROM ".$this->Init_Table_Map("DETAILS")."
					WHERE rotation='$rotJCR' AND bill_type='$bill_type' AND berth_suffix='$berth_suffix'";		// rotJCR or rotation		??
					$draftNumberJCR = $this->bm->dataReturn($sql_draftNumber);				
				
					if($chkPangoanVsl != "P" and $chkPangoanVsl != "Q")
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
					}
					
					$jettyChargeSubDetailQuery = "";
					
					if($chkPangoanVsl == "P" or $chkPangoanVsl == "Q")  
					{
						$jettyChargeSubDetailQuery = "SELECT DISTINCT billing.bil_tariffs.description,billing.bil_tariffs.gl_code,ctmsmis.mis_vsl_bill_tarrif.pngn_new_rate AS rate,'HRS' AS bas 
						FROM ctmsmis.mis_vsl_bill_tarrif  
						INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=mis_vsl_bill_tarrif.id 
						INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey 
						WHERE sub_type='$berth_suffix' AND bill_type=101";
					}
					else
					{
						// no data entry - 
						$jettyChargeSubDetailQuery = "SELECT DISTINCT billing.bil_tariffs.description,billing.bil_tariffs.gl_code, 
						(SELECT amount FROM billing.bil_tariff_rate_tiers WHERE rate_gkey=billing.bil_tariff_rates.gkey LIMIT 1) AS rate,'HRS' AS bas 
						FROM ctmsmis.mis_vsl_bill_tarrif  
						INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=mis_vsl_bill_tarrif.id 
						INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey 
						WHERE sub_type='$berth_suffix' AND bill_type=101";
					}
					
					$rsJettyDetailResult = $this->bm->dataSelect($jettyChargeSubDetailQuery);
					
					
					// echo "<br>";
					// echo "--------------------------";
					// echo "<br>";
					// echo "int : ".$int;
					// echo "<br>";
					// echo $jettyChargeSubDetailQuery;
					// echo "<br>";
					// print_r($rsJettyDetailResult);
					// echo "<br>";
					// echo "--------------------------";
					// echo "<br>";
					// $int++;
						
					if(count($rsJettyDetailResult)>0)
					{
						for($k=0;$k<count($rsJettyDetailResult);$k++)
						{
							$description 	= $rsJettyDetailResult[$k]['description'];
							$gl_code 		= $rsJettyDetailResult[$k]['gl_code'];
							$rate 			= $rsJettyDetailResult[$k]['rate'];
							$bas 			= $rsJettyDetailResult[$k]['bas'];
							
							$insert_JettyDetailResult = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas)
											VALUES('$draftNumberJCR','$description','$gl_code','$rate','$bas')";
							$rsltJDR = $this->bm->dataInsert($insert_JettyDetailResult);
							
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
						$harbourCraneSubDetailQueryIMP = "";
						
						if($rtdt == 1)
						{
							$harbourCraneSubDetailQueryIMP = $this->vbq->getHarbourCraneSubDetailChargesQueryCurrentVslIMP($vvdGkey);
						}
						else
						{						
							$harbourCraneSubDetailQueryIMP = $this->vbq->getHarbourCraneSubDetailChargesQueryOldVslIMP($vvdGkey);						
						}
						
						$harbourCraneSubDetailResult = $this->bm->dataSelect($harbourCraneSubDetailQueryIMP);
						
						if(count($harbourCraneSubDetailResult)>0)
						{
							for($m=0;$m<count($harbourCraneSubDetailResult);$m++)
							{
								$description 	= $harbourCraneSubDetailResult[$m]['description'];
								$gl_code 		= $harbourCraneSubDetailResult[$m]['gl_code'];
								$rate 			= $harbourCraneSubDetailResult[$m]['rate'];
								$bas 			= $harbourCraneSubDetailResult[$m]['bas'];
								$unit 			= $harbourCraneSubDetailResult[$m]['unit'];
								
								$harbourQueryIMP = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot)
								VALUES('$draftNumberJCR','$description','$gl_code','$rate','$bas','$unit')";
								$rsltHQIMP = $this->bm->dataInsert($harbourQueryIMP);
								
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
						
						$harbourCraneSubDetailQueryEXP = "";
						
						if($rtdt==1)
						{
							$harbourCraneSubDetailQueryEXP = $this->vbq->getHarbourCraneSubDetailChargesQueryCurrentVslEXP($vvdGkey);
						}
						else
						{
							$harbourCraneSubDetailQueryEXP = $this->vbq->getHarbourCraneSubDetailChargesQueryOldVslEXP($vvdGkey);
						}
						
						$harbourCraneSubDetailResultEXP = $this->bm->dataSelect($harbourCraneSubDetailQueryEXP);
						
						if(count($harbourCraneSubDetailResultEXP)>0)
						{
							for($m=0;$m<count($harbourCraneSubDetailResultEXP);$m++)
							{							
								$description 	= $harbourCraneSubDetailResultEXP[$m]['description'];
								$gl_code 		= $harbourCraneSubDetailResultEXP[$m]['gl_code'];
								$rate 			= $harbourCraneSubDetailResultEXP[$m]['rate'];
								$bas 			= $harbourCraneSubDetailResultEXP[$m]['bas'];
								$unit 			= $harbourCraneSubDetailResultEXP[$m]['unit'];
								
								$harbourQueryEXP = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot)
								VALUES('$draftNumberJCR','$description','$gl_code','$rate','$bas','$unit')";
								$rsltHQEXP = $this->bm->dataInsert($harbourQueryEXP);
								
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
						$getGantryCraneSubDetailChargesQueryForQGC = $this->vbq->getGantryCraneSubDetailChargesQueryForQGC($rotJCR);
						
						$rsSubDetailResult = $this->bm->dataSelect($getGantryCraneSubDetailChargesQueryForQGC);
						
						if(count($rsSubDetailResult)>0)
						{
							for($m=0;$m<count($rsSubDetailResult);$m++)
							{
								$description 	= $rsSubDetailResult[$m]['description'];
								$gl_code 		= $rsSubDetailResult[$m]['gl_code'];
								$rate 			= $rsSubDetailResult[$m]['rate'];
								$bas 			= $rsSubDetailResult[$m]['bas'];
								$unit 			= $rsSubDetailResult[$m]['unit'];
								$currency_gkey 	= $rsSubDetailResult[$m]['currency_gkey'];
								
								$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot,currency_gkey) 
								VALUES('$draftNumberJCR','$description','$gl_code','$rate','$bas','$unit','$currency_gkey')";
								$rsltSubDtlRslt = $this->bm->dataInsert($query);
								
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
						$cctCountQuery = $this->vbq->getCCTCountQuery($rotation);
						$cctCountResult = $this->bm->dataSelect($cctCountQuery);
						
						// checkIfQGCExists - start
						$checkIfExists = "SELECT COUNT(*) AS rtnValue
						FROM ".$this->Init_Table_Map("DETAILS")."
						WHERE rotation = '$rotation' AND bill_type = '103'";
						$countResult = $this->bm->dataReturn($checkIfExists);
						
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
							$cctCount = $cctCountResult[$j]['totalCCT'];
						}
						
						if($cctCount > 0)
						{
							// $getGantryCraneSubDetailChargesQuery = $this->vbq->getGantryCraneSubDetailChargesQuery($rotation);
							$getGantryCraneSubDetailChargesQuery = $this->vbq->getGantryCraneSubDetailChargesQuery($rotJCR);
							$rsSubDetailResult = $this->bm->dataSelect($getGantryCraneSubDetailChargesQuery);
							
							if(count($rsSubDetailResult)>0)
							{							
								for($j=0;$j<count($rsSubDetailResult);$j++)
								{
									$description 	= $rsSubDetailResult[$j]['description'];
									$gl_code 		= $rsSubDetailResult[$j]['gl_code'];
									$rate 			= $rsSubDetailResult[$j]['rate'];
									$bas 			= $rsSubDetailResult[$j]['bas'];
									$unit 			= $rsSubDetailResult[$j]['unit'];
									
									$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot)
									VALUES('$draftNumberJCR','$description','$gl_code','$rate','$bas','$unit')";	
									$rsltSubDtlRslt = $this->bm->dataInsert($query);

									if($rsltSubDtlRslt==0)
									{
										//echo $msg = "<font color='red'>rsSubDetailResult (2) Not Inserted at $m ...</font>";
										//echo "<br>";
										// return;	
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
			
			$rtdt = $jettyChargesResult[0]['rtdt'];
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
				$count = $jettyCountResult[$i]['cnt'];
			}
			
			$visitCount = 0;
			for($i=0;$i<count($visitCountResult);$i++)
			{
				$visitCount = $visitCountResult[$i]['cnt'];
			}
		
			$dollarRate = "";
			if(count($pilotChargesResult)>0)
			{					
				$sql_dollarRate = "SELECT rate
				FROM bil_currency_exchange_rates
				WHERE effective_date='".$pilotChargesResult[0]['oa_date']."' ORDER BY gkey DESC
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
					$rotPCR = $pilotChargesResult[$i]['rotation'];			// PCR = Pilot Charges Result
					$vsl_name = $pilotChargesResult[$i]['vsl_name'];
					$ata = $pilotChargesResult[$i]['ata'];
					$atd = $pilotChargesResult[$i]['atd'];
					$agent_alias_id = $pilotChargesResult[$i]['agent_alias_id'];
					$agent_code = $pilotChargesResult[$i]['agent_code'];
					$agent_name = $pilotChargesResult[$i]['agent_name'];
					$agent_address = $pilotChargesResult[$i]['address'];
					$oa_date = $pilotChargesResult[$i]['oa_date'];
					$flag = $pilotChargesResult[$i]['flag'];
					$cnt_code = $pilotChargesResult[$i]['cnt_code'];
					$grt = $pilotChargesResult[$i]['grt'];
					$loa_cm = $pilotChargesResult[$i]['loa_cm'];
					$master_name = $this->getMasterName($rotation);					
					$deck_cargo = $pilotChargesResult[$i]['deck_cargo'];
					// $exchangeRate = $pilotChargesResult[$i]['exchangeRate'];
					$exchangeRate = $dollarRate;
					$bill_type = $pilotChargesResult[$i]['bill_type'];
					$pilot_ib_onboard = $pilotChargesResult[$i]['pilot_ib_onboard'];
					$pilot_ib_offboard = $pilotChargesResult[$i]['pilot_ib_offboard'];
					$pilot_ob_onboard = $pilotChargesResult[$i]['pilot_ob_onboard'];
					$pilot_ob_offboard = $pilotChargesResult[$i]['pilot_ob_offboard'];
					$outbound_call_nbr = $pilotChargesResult[$i]['out_call_number'];
					
					$s1N = $pilotChargesResult[$i]['s1N'];
					$s2N = $pilotChargesResult[$i]['s2N'];
								
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
									
					$insertQuery = "INSERT INTO ".$this->Init_Table_Map("DETAILS")."(rotation,vsl_name,ata,atd,agent_code,agent_name,oa_date,flag,cnt_code,grt,master_name,deck_cargo,exchangeRate,pilot_ib_onboard,
					pilot_ib_offboard,pilot_ob_onboard,pilot_ob_offboard,berth_suffix,bill_type,bill_name,creator,agent_alias_id,agent_address,ip_address,
					billing_date)
					VALUES('$rotPCR','$vsl_name','$ata','$atd','$agent_code','$agent_name','$oa_date','$flag','$cnt_code','$grt','$master_name','$deck_cargo','$exchangeRate','$pilot_ib_onboard','$pilot_ib_offboard','$pilot_ob_onboard','$pilot_ob_offboard','$berth_suffix','$bill_type','$bill_name','$login_id','$agent_alias_id','$agent_address','$ipAddress',NOW())";
					
					$insertDETAILS = $this->bm->dataInsert($insertQuery);
					
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
					$draftNumberPCR = $this->bm->dataReturn($sql_draftNumber);
					
					$addPortDues = true;  
					
					if($outbound_call_nbr == 2)
						$addPortDues = false;
					
					$pilotChargeSubDetailQuery = "";
					
					if($chkPangoanVsl == "P" or $chkPangoanVsl == "Q")
					{
						if($rtdt==1)		// line 339
						{
							$pilotChargeSubDetailQuery =  $this->vbq->getPilotSubDetailsQueryPangoanNewRate($berth_suffix,$grt,$deck_cargo,$addPortDues);
						}               
						else
						{
							$pilotChargeSubDetailQuery =  $this->vbq->getPilotSubDetailsQueryPangoan($berth_suffix,$grt,$deck_cargo,$addPortDues);
						}              
					}
					else
					{
						$pilotChargeSubDetailQuery = $this->vbq->getPilotSubDetailsQuery($berth_suffix,$grt,$deck_cargo,$addPortDues,$rotation,$loa_cm);
					}
					
					$rsPilotSubDetailResult = $this->bm->dataSelect($pilotChargeSubDetailQuery);
					
					if(count($rsPilotSubDetailResult)>0)
					{							
						for($j=0;$j<count($rsPilotSubDetailResult);$j++)
						{
							$description 	= $rsPilotSubDetailResult[$j]['description'];
							$gl_code 		= $rsPilotSubDetailResult[$j]['gl_code'];
							$rate 			= $rsPilotSubDetailResult[$j]['rate'];
							$unit 			= $rsPilotSubDetailResult[$j]['unit'];
							$bas 			= $rsPilotSubDetailResult[$j]['bas'];
							$move 			= $rsPilotSubDetailResult[$j]['move'];
							
							$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,unit_for_pilot,bas,move)
							VALUES('$draftNumberPCR','$description','$gl_code','$rate','$unit','$bas','$move')";
							
							$insertDETAILS = $this->bm->dataInsert($query);
						
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
						
						$rsPilotSubDetailBIWTAResult = $this->bm->dataSelect($pilotChargeBIWTAsubDetailQuery);
								
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
								$insertBIWTA = $this->bm->dataInsert($queryBIWTA);
						
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
								$dayNightQuery = "SELECT IF(TIME(sparcsn4.vsl_vessel_visit_details.flex_date05) BETWEEN '06:00:00' AND '17:59:59' AND TIME(sparcsn4.vsl_vessel_visit_details.flex_date06) BETWEEN '06:00:00' AND '17:59:59','D','N') AS day_night
								FROM sparcsn4.vsl_vessel_visit_details WHERE ib_vyg='$rotation'";
							}
							else
							{
								$dayNightQuery = "SELECT IF(TIME(sparcsn4.vsl_vessel_berthings.start_work) BETWEEN '06:00:00' AND '17:59:59'
								AND TIME(sparcsn4.vsl_vessel_berthings.stop_work) BETWEEN '06:00:00' AND '17:59:59','D','N') AS day_night
								FROM sparcsn4.vsl_vessel_visit_details
								INNER JOIN sparcsn4.vsl_vessel_berthings ON sparcsn4.vsl_vessel_berthings.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
								WHERE ib_vyg='$rotation' ORDER BY ata LIMIT $j,1";
							}
							
							$rdn = $this->bm->dataSelect($dayNightQuery);
							
							if(count($rdn)>0)
							{
								for($k=0;$k<count($rdn);$k++)
								{
									$dayNight = $rdn[$k]['day_night'];
									
									$dayNightSubDetail = "SELECT DISTINCT billing.bil_tariffs.description,billing.bil_tariffs.description AS des,billing.bil_tariffs.gl_code,
									billing.bil_tariff_rates.amount AS rate,1 AS unit,'NOS' AS bas,1 AS move
									FROM ctmsmis.mis_vsl_bill_tarrif
									INNER JOIN billing.bil_tariffs  ON billing.bil_tariffs.id=ctmsmis.mis_vsl_bill_tarrif.id
									INNER JOIN billing.bil_tariff_rates ON billing.bil_tariff_rates.tariff_gkey=billing.bil_tariffs.gkey
									WHERE berth_time=CONCAT('$berth_suffix','$dayNight') AND bill_type=102";
									
									$dayNightSubDetailResult = $this->bm->dataSelect($dayNightSubDetail);

									for($m=0;$m<count($dayNightSubDetailResult);$m++)
									{
										$description 	= $dayNightSubDetailResult[$m]['description'];
										$gl_code 		= $dayNightSubDetailResult[$m]['gl_code'];
										$rate 			= $dayNightSubDetailResult[$m]['rate'];
										$bas 			= $dayNightSubDetailResult[$m]['bas'];
										$unit 			= $dayNightSubDetailResult[$m]['unit'];
										$move 			= $dayNightSubDetailResult[$m]['move'];
										
										$dayNightSubDetailQuery = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")." (draftNumber,description,gl_code,rate,bas,unit_for_pilot,move)
										VALUES('$draftNumberPCR','$description','$gl_code','$rate','$bas','$unit','$move')";
																		
										if($chkPangoanVsl != "P" or $chkPangoanVsl == "Q")
										{                                    
											$rslt_dayNightSubDetailQuery = $this->bm->dataInsert($dayNightSubDetailQuery);
											
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
							$getNightNavigationQuery = $this->vbq->getNightNavigationQuery($grt);		// grt exists
							
							$rsPilotNightNavigationResult = $this->bm->dataSelect($getNightNavigationQuery);
							
							if(count($rsPilotNightNavigationResult)>0)
							{
								for($j=0;$j<count($rsPilotNightNavigationResult);$j++)
								{
									$description 	= $rsPilotNightNavigationResult[$j]['description'];
									$gl_code 		= $rsPilotNightNavigationResult[$j]['gl_code'];
									$rate 			= $rsPilotNightNavigationResult[$j]['rate'];
									$bas 			= $rsPilotNightNavigationResult[$j]['bas'];
									$unit 			= $rsPilotNightNavigationResult[$j]['unit'];
									$move 			= $rsPilotNightNavigationResult[$j]['move'];
									
									$query = "INSERT INTO ".$this->Init_Table_Map("SUB_DETAILS")."(draftNumber,description,gl_code,rate,bas,unit_for_pilot,move)
									VALUES('$draftNumberPCR','$description','$gl_code','$rate','$bas','$unit','$move')";
														
									if($s1N == "1" and $s2N == "1")
									{
										// "Nav Both"
										
										$qFlag_1_1 = $this->bm->dataInsert($query);
										if($qFlag_1_1 == 0)
										{
											//echo $msg = "<font color='red'>qFlag_1_1 stopped at $j</font>";
											//echo "<br>";
											// return;
										}
										
										$qFlag_1_2 = $this->bm->dataInsert($query);
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
										
										$qFlag_2_1 = $this->bm->dataInsert($query);
										
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
			
			$regenerateRotation = $this->input->post('regenerateRotation');
			$regenerateBillType = $this->input->post('regenerateBillType');
			$draftForRegenerate = $this->input->post('draftForRegenerate');
			
			// echo "ok";
			// return;
			
			// delete bill with log
			$this->vslBillDelete($draftForRegenerate);
			
			// generate bill
			if($regenerateBillType == 101)			// jetty bill
			{
				$this->Generate_Jetty_Charges_Bill($regenerateRotation);
			}
			else if($regenerateBillType == 102)		// pilot bill
			{
				$this->Generate_Pilot_Charges_Bill($regenerateRotation);
			}
			else if($regenerateBillType == 106)		// not entering bill
			{
				// $this->generateVesselsBillNotEntering($rotation);
				$this->generatePilotageBillNotEntering($regenerateRotation);
			}
			
			// update dispute info
			$sql_updateDispute = "UPDATE ".$this->Init_Table_Map("DETAILS")."
								SET ".$this->Init_Table_Map("DETAILS").".disputeraised='1'
								WHERE ".$this->Init_Table_Map("DETAILS").".rotation='$regenerateRotation' 
								AND ".$this->Init_Table_Map("DETAILS").".bill_type='$regenerateBillType'";
			$this->bm->dataUpdate($sql_updateDispute);
			
			// go to bill list
			$title = "VESSEL BILL LIST (Approved)";
			$action = "a";
			$msg = "<font color='green'>Bill regenerated for ".$regenerateRotation."</font>";
 
			$sql_bill_list="SELECT draftNumber,IFNULL(finalNumber,draftNumber) AS finalNumber,rotation,vsl_name,bill_name,ata,atd,berth,agent_code,agent_name,flag,cnt_code,bill_type,acc_apprv_st
			FROM ".$this->Init_Table_Map("DETAILS")."
			WHERE acc_apprv_st = 1 ".$cond." ORDER BY draftNumber DESC";
			
			$rslt_bill_list=$this->bm->dataSelect($sql_bill_list);						
			
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
}
?>