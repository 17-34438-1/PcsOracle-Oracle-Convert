<?php
class Vessel extends CI_Controller {
	function __construct()
	{
	    parent::__construct();	
            $this->load->library(array('session', 'form_validation'));
            $this->load->model(array('CI_auth', 'CI_menu'));
            $this->load->helper(array('html','form', 'url'));
			//$this->load->driver('cache');
			$this->load->model('ci_auth', 'bm', TRUE);			
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");			
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
		
		
		
	function vesselForwardingbyMarineForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$masterFlag = "";
			
			$data['title']="Vessel Forwarding by Marine";
			$data['masterFlag'] = $masterFlag;
			$data['msg'] = "";
			$data['flag'] = 0;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingbyMarineForm',$data);
			$this->load->view('jsAssets');
		}
	}


	function vesselForwardingbyMarine()		// N4
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
			$section = $this->session->userdata('section');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$data['title']="Vessel Forwarding by Marine";
			$data['msg'] = "";
			
			$fromDate = $this->input->post("fromDate");
			$toDate = $this->input->post("toDate");
			
			$departQuery = "";
			$masterFlag = "";
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Vessel Forwarding by Marine";
				
				// search by departure date
				$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
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
				WHERE DATE(sparcsn4.argo_carrier_visit.atd) BETWEEN '$fromDate' AND '$toDate' AND  vvd_gkey NOT IN (SELECT ctmsmis.vsl_forward_info.vvd_gkey FROM ctmsmis.vsl_forward_info)
				ORDER BY sparcsn4.argo_carrier_visit.atd ASC";												
			}
			else if($org_Type_id=='81') //Master			
			{
				$masterFlag = "master";
				$data['title']="Vessel Forwarding by Harbaour Master";
				/* $departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
				DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,
				sparcsn4.vsl_vessels.ves_captain,sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,
				sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd,
				sparcsn4.vsl_vessel_visit_details.ib_vyg,
				ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM sparcsn4.argo_carrier_visit
				INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
				INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
				INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
				INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
				INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
				WHERE DATE(sparcsn4.argo_carrier_visit.atd) BETWEEN '$fromDate' AND '$toDate' 
				AND ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_stat='0'
				ORDER BY sparcsn4.argo_carrier_visit.atd ASC"; */
				
				// search by forwarding date
				$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
				DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,
				sparcsn4.vsl_vessels.ves_captain,sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,
				sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd,
				sparcsn4.vsl_vessel_visit_details.ib_vyg,
				ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM sparcsn4.argo_carrier_visit
				INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
				INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
				INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
				INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
				INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
				WHERE DATE(ctmsmis.vsl_forward_info.marine_forward_at) BETWEEN '$fromDate' AND '$toDate' 
				AND ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_stat='0'
				ORDER BY sparcsn4.argo_carrier_visit.atd ASC";
			}
			else if($org_Type_id=='82') // Accounts
			{
				// if($login_id=='sr_acc') //Sr. Accountant
				if($section=='acc') // Accountant - by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding by Sr. Accountant";
					/* $departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
					DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain,
					sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
					sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd, sparcsn4.vsl_vessel_visit_details.ib_vyg,
					ctmsmis.vsl_forward_info.master_forward_at AS forwarded_dt
					FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
					INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
					INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
					INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
					INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
					WHERE DATE(sparcsn4.argo_carrier_visit.atd) BETWEEN '$fromDate' AND '$toDate'
					AND ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
					ORDER BY sparcsn4.argo_carrier_visit.atd ASC"; */
					
					// search by forwarding date
					$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
					DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain,
					sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
					sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd, sparcsn4.vsl_vessel_visit_details.ib_vyg,
					ctmsmis.vsl_forward_info.master_forward_at AS forwarded_dt
					FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
					INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
					INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
					INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
					INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
					WHERE DATE(ctmsmis.vsl_forward_info.master_forward_at) BETWEEN '$fromDate' AND '$toDate'
					AND ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
					ORDER BY sparcsn4.argo_carrier_visit.atd ASC";
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding by Accountant";
					/* $departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
					DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain,
					sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
					sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd, sparcsn4.vsl_vessel_visit_details.ib_vyg,
					ctmsmis.vsl_forward_info.sr_acnt_forward_at AS forwarded_dt
					FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
					INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
					INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
					INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
					INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
					WHERE DATE(sparcsn4.argo_carrier_visit.atd) BETWEEN '$fromDate' AND '$toDate' 
					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_stat='0'
					ORDER BY sparcsn4.argo_carrier_visit.atd ASC"; */
					
					// search by forwarding date
					$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
					DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain,
					sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
					sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd, sparcsn4.vsl_vessel_visit_details.ib_vyg,
					ctmsmis.vsl_forward_info.sr_acnt_forward_at AS forwarded_dt
					FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
					INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
					INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
					INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
					INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
					WHERE DATE(ctmsmis.vsl_forward_info.sr_acnt_forward_at) BETWEEN '$fromDate' AND '$toDate' 
					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_stat='0'
					ORDER BY sparcsn4.argo_carrier_visit.atd ASC";
				}					
			}
			// echo $departQuery;return;
			$departData = $this->bm->dataSelect($departQuery);

			$data['departData']=$departData;
			$data['masterFlag']=$masterFlag;
			$data['fromDate']=$fromDate;
			$data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;


			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingbyMarineForm',$data);			
			$this->load->view('jsAssets');
		}

	}

	function vesselForwardingPerform()		// N4
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
			$fromDate = $this->input->post("fromDate");
			$toDate = $this->input->post("toDate");
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$departQuery="";
			$masterFlag="";
			
			if(isset($_POST['idchk']))
			{
				$rotationChk = $_POST['idchk'];
			}	
			if($org_Type_id=='83')		// marine
			{
				$k=0;
				foreach ($rotationChk as $rCheck)
				{					
					// lot data - start - n4				
					$sql_nextLotSl = "SELECT IFNULL(MAX(lot_sl),0)+1 AS rtnValue
					FROM ctmsmis.vsl_forward_lot_info
					WHERE lot_dt=DATE(NOW())";
									
					$nextLotSl = $this->bm->dataReturn($sql_nextLotSl);				
					$nextLotSl = (strlen($nextLotSl)<2)?("0".$nextLotSl):$nextLotSl;		
					
					$nextLotId = date('d').date('m').substr(date('Y'),2,2).$nextLotSl;				
									
					$totVsl = count($rotationChk);
									
					$lotInsFlag = 0;
					$insertLotInfo = "INSERT INTO ctmsmis.vsl_forward_lot_info(lot_sl,lot_dt,lot_id,tot_vsl,forward_at,forward_by,vsl_lot_type)
									VALUES('$nextLotSl',CURDATE(),'$nextLotId','$totVsl',NOW(),'$login_id','Not Entering')";								
					$lotInsFlag = $this->bm->dataInsert($insertLotInfo);
					
					if($lotInsFlag == 0)
					{
						echo "<font color='red'>Lot not created</font>";
						return;
					}
					
					$sql_lotId = "SELECT MAX(id) AS rtnValue FROM ctmsmis.vsl_forward_lot_info";
					$lotId = $this->bm->dataReturn($sql_lotId);
					// echo $lotId;return;
					// lot data - end
					
					
					// echo $rCheck;return;
					$chk_str="SELECT COUNT(*) AS rtnValue from ctmsmis.vsl_forward_info WHERE  vvd_gkey='$rCheck'";
					// echo $chk_str;
					$ckh_st = $this->bm->dataReturn($chk_str);
					// echo $ckh_st;return;
					$resInsertst=0;
					if($ckh_st==0)
					{
						$strInsert = "INSERT INTO ctmsmis.vsl_forward_info(vsl_fwd_lot_info_id,vvd_gkey, marine_forward_stat, marine_forward_by, marine_forward_at,marine_forward_ip) 
						VALUES('$lotId','$rCheck', '1', '$login_id', NOW(), '$ipAddress')";
						// echo $strInsert;return;
						$resInsertst = $this->bm->dataInsert($strInsert);
						// echo $resInsertst;return;
						$k++;
					}
				}
				if($resInsertst>0)
				{
					$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
				}
				else
				{
					$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
				}
				
				if($this->input->post('fwBtn'))
				{								
					$data['title']="Vessel Forwarding by Marine";
					
					$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
					DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain,
					sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,
					sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd, 
					sparcsn4.vsl_vessel_visit_details.ib_vyg
					FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
					INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
					INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
					INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
					INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
					WHERE DATE(sparcsn4.argo_carrier_visit.atd) BETWEEN '$fromDate' AND '$toDate'
					AND  vvd_gkey NOT IN (SELECT ctmsmis.vsl_forward_info.vvd_gkey FROM ctmsmis.vsl_forward_info)
					ORDER BY sparcsn4.argo_carrier_visit.atd DESC";
				}
				else if($this->input->post('fwBtnOuter'))
				{
					$data['title']="Outer Anchorage Forwarding by Marine";
					
					$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
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
					WHERE sparcsn4.argo_carrier_visit.phase='20INBOUND' AND  vvd_gkey NOT IN (SELECT ctmsmis.vsl_forward_info.vvd_gkey FROM ctmsmis.vsl_forward_info) AND sparcsn4.ref_country.cntry_code!='BD'
					ORDER BY sparcsn4.argo_carrier_visit.atd DESC";
				}
			}
			else if($org_Type_id=='81')		//master
			{
				$masterFlag = "master";
				$fileNo = $this->input->post("fileNo");
				$filedt = $this->input->post("filedt");
				$filesub = $this->input->post("filesub");
				$noVsl=count($rotationChk);
				$chk_str="SELECT count(*) AS rtnValue from ctmsmis.vsl_frwrd_letter_info WHERE file_no='$fileNo' ";
				$chk_st = $this->bm->dataReturn($chk_str);
				if($chk_st>0)
				{
					$data['msg'] = "<font color='red' size=2>You already used this File no. Use new one.</font>";
				}
				else
				{
					$insert_str="INSERT INTO ctmsmis.vsl_frwrd_letter_info (file_dt, file_sub, file_no, no_vsl) VALUES ('$filedt', '$filesub','$fileNo', '$noVsl')";			
					$insert_st = $this->bm->dataInsert($insert_str);
					$k=0;
					$str="SELECT id AS rtnValue from ctmsmis.vsl_frwrd_letter_info WHERE file_no='$fileNo' ";
					$letter_no = $this->bm->dataReturn($str);
					
					foreach ($rotationChk as $rCheck)
					{
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET master_forward_stat='1', master_forward_by='$login_id', master_forward_at=NOW(),
									master_forward_ip='$ipAddress', vsl_frwd_letter_no='$letter_no' WHERE vvd_gkey='$rCheck'";
						$update_st = $this->bm->dataUpdate($updateSt);
						$k++;
					}
					$data['msg']="";
					if($update_st>0)
					{
						$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					}
					else
					{
						$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					}
				}
				
				if($this->input->post('fwBtn'))
				{
					$data['title']="Vessel Forwarding by Master";
					
					$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
					DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain,
					sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
					sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd,  sparcsn4.vsl_vessel_visit_details.ib_vyg
					FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
					INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
					INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
					INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
					INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
					WHERE DATE(sparcsn4.argo_carrier_visit.atd) BETWEEN '$fromDate' AND '$toDate' 
					AND ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_stat='0'
					ORDER BY sparcsn4.argo_carrier_visit.atd DESC";
				}
				else if($this->input->post('fwBtnOuter'))
				{
					$data['title']="Outer Anchorage Forwarding by Master";
					
					$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
					DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,
					sparcsn4.vsl_vessels.ves_captain,sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,
					sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd,
					sparcsn4.vsl_vessel_visit_details.ib_vyg
					FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
					INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
					INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
					INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
					INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
					WHERE sparcsn4.argo_carrier_visit.phase='20INBOUND'
					AND ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_stat='0'
					AND sparcsn4.ref_country.cntry_code!='BD'
					ORDER BY sparcsn4.argo_carrier_visit.atd DESC";
				}
			}
			else if($org_Type_id=='82')
			{
				if($login_id=='sr_acc') //Sr. Accountant 
				{
					$k=0;
					foreach ($rotationChk as $rCheck)
					{
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),
									sr_acnt_forward_ip='$ipAddress' WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdate($updateSt);
						$k++;
					} 
					$data['msg']="";
					if($update_st>0)
					{
						$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					}
					else
					{
						$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					}
					
					if($this->input->post('fwBtn'))
					{
						$data['title']="Vessel Forwarding by Sr. Accountant";
						$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
						DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain,
						sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
						sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd,  sparcsn4.vsl_vessel_visit_details.ib_vyg
						FROM sparcsn4.argo_carrier_visit
						INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
						INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
						INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
						INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
						INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
						INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
						INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
						WHERE DATE(sparcsn4.argo_carrier_visit.atd) BETWEEN '$fromDate' AND '$toDate'
						AND ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
						ORDER BY sparcsn4.argo_carrier_visit.atd DESC";
					}
					else if($this->input->post('fwBtnOuter'))
					{
						$data['title']="Vessel Forwarding by Sr. Accountant";
						$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
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
						AND ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
						AND sparcsn4.ref_country.cntry_code!='BD'
						ORDER BY sparcsn4.argo_carrier_visit.atd DESC";
					}
				}
				else if ($login_id=='acc') //Accountant 
				{
					$k=0;
					foreach ($rotationChk as $rCheck)
					{
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET billop_bill_stat='1', billop_bill_by='$login_id', billop_bill_at=NOW(),
									billop_bill_ip='$ipAddress' WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdate($updateSt);
						$k++;
					} 
					$data['msg']="";
					
					/* if($update_st>0)
					{
						$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					}
					else
					{
						$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					} */
					$data['title']="Vessel Forwarding by Accountant";
					$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
					DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain,sparcsn4.vsl_vessel_classes.loa_cm,
					sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,sparcsn4.ref_bizunit_scoped.name,
					sparcsn4.ref_country.cntry_name,ata,atd,  sparcsn4.vsl_vessel_visit_details.ib_vyg
					FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
					INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
					INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
					INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
					INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
					WHERE DATE(sparcsn4.argo_carrier_visit.atd) BETWEEN '$fromDate' AND '$toDate' 
					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_stat='0'
					AND sparcsn4.ref_country.cntry_code!='BD'
					ORDER BY sparcsn4.argo_carrier_visit.atd DESC";
				}				
			}

			$departData = $this->bm->dataSelect($departQuery);

			$data['departData']=$departData;
			$data['masterFlag']=$masterFlag;
			$data['fromDate']=$fromDate;
			$data['toDate']=$toDate;
			$data['flag'] = 1;
			$data['login_id']=$login_id;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			if($this->input->post('fwBtn'))
				$this->load->view('vesselForwardingbyMarineForm',$data);
			else if($this->input->post('fwBtnOuter'))
				$this->load->view('vesselForwardOuterAnchorage',$data);
			$this->load->view('jsAssets');
		}
		
	}
	
	function vesselForwardingbyMasterList()
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

			$org_Type_id =$this->session->userdata('org_Type_id');			
			$data['msg'] = "";
			
			//$fromDate = $this->input->post("fromDate");
			//$toDate = $this->input->post("toDate");
			
			$data['title']="Vessel Forwarding Letter";
			$Query = "SELECT vsl_frwrd_letter_info.id, file_dt,file_sub,file_no,no_vsl FROM ctmsmis.vsl_frwrd_letter_info ORDER BY vsl_frwrd_letter_info.id DESC";
			$letterList = $this->bm->dataSelect($Query);

			$data['letterList']=$letterList;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingbyMasterList',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	
	
	function vesselForwardingLetter()
	{
		$fileNo = $this->input->post("file_no");
		$filedt = $this->input->post("file_dt");
		$filesub = $this->input->post("file_sub");
		$no_vsl = $this->input->post("no_vsl");
		$letter_id = $this->input->post("letter_id");
		/* $this->load->library('m_pdf');
		$mpdf->use_kwt = true;
		$mpdf->simpleTables = true;	 */
		$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
					DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain, sparcsn4.vsl_vessel_classes.loa_cm,
					sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,sparcsn4.ref_bizunit_scoped.name,
					sparcsn4.ref_country.cntry_name, ata, atd,  sparcsn4.vsl_vessel_visit_details.ib_vyg
					FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
					INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
					INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
					INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
					INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
					WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no='$letter_id'";
		/* 	echo $fileNo;
			return;	 */

		$departData = $this->bm->dataSelect($departQuery);
		$data['departData']=$departData;
		$data['fileNo']=$fileNo;
		$data['filedt']=$filedt;
		$data['filesub']=$filesub;
		$data['no_vsl']=$no_vsl;
		$this->load->view('vesselForwardingLetterView',$data);


		/* $departData = $this->bm->dataSelect($departQuery);
		$this->data['departData']=$departData;
		$this->data['fileNo']=$fileNo;
		$this->data['filedt']=$filedt;
		$this->data['filesub']=$filesub;
		$this->data['no_vsl']=$no_vsl;
		//$this->load->view('shedWiseDeliveryReportView',$data);
		$html=$this->load->view('vesselForwardingLetterView',$this->data, true); 
		$pdfFilePath ="vesselForwardingLetterView";

		$pdf = $this->m_pdf->load();
		$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
		$pdf = new mPDF('utf-8', 'A4');  //have tried several of the formats
		$pdf->WriteHTML($html,2);
		$pdf->Output($pdfFilePath, "I");	 */			
	}

	function vesselForwardingbyMarineByAppForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Vessel Forwarding by Marine";
			$data['msg'] = "";
			$data['flag'] = 0;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingbyMarineByApp',$data);
			$this->load->view('jsAssets');
		}
	}

	function vesselForwardingbyAPP()
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

			$section = $this->session->userdata('section');
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$data['title']="Vessel Forwarding by Marine";
			$data['msg'] = "";
			
			$fromDate = $this->input->post("fromDate");
			$toDate = $this->input->post("toDate");
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Vessel Forwarding by Marine (Apps)";

				
				$departQuery = "SELECT  doc_vsl_depart.vvd_gkey,igm_masters.Vessel_Name AS vsl_name, igm_masters.Import_Rotation_No AS ib_vyg,
				DATE(doc_vsl_arrival.mooring_frm_time) AS ata,
				DATE(doc_vsl_depart.mooring_to_time) AS atd, doc_vsl_shift.mooring_frm_time AS shf_frm_time, 
				doc_vsl_shift.mooring_to_time AS shf_to_time, 
				(SELECT u_name FROM users WHERE users.login_id= doc_vsl_depart.pilot_name) as pilot_name,
				doc_vsl_depart.draught
				FROM doc_vsl_depart
				INNER JOIN igm_masters ON igm_masters.id=doc_vsl_depart.igm_id 
				LEFT JOIN doc_vsl_arrival ON  doc_vsl_arrival.vvd_gkey=doc_vsl_depart.vvd_gkey
				LEFT JOIN doc_vsl_shift ON doc_vsl_shift.vvd_gkey=doc_vsl_depart.vvd_gkey
				WHERE DATE(doc_vsl_depart.mooring_to_time) BETWEEN '$fromDate' AND '$toDate' 
				AND doc_vsl_depart.pilot_name!='devpilot'
				
				AND doc_vsl_depart.vvd_gkey NOT IN (SELECT vsl_forward_info.vvd_gkey FROM vsl_forward_info)
				ORDER BY doc_vsl_depart.mooring_to_time DESC";
			}
			else if($org_Type_id=='81') //Master			
			{
				$data['title']="Vessel Forwarding by Harbaour Master (Apps)";
				
				$departQuery = "SELECT  doc_vsl_depart.vvd_gkey,  igm_masters.Vessel_Name AS vsl_name, igm_masters.Import_Rotation_No AS ib_vyg,
				DATE(doc_vsl_arrival.mooring_frm_time) AS ata,
				DATE(doc_vsl_depart.mooring_to_time) AS atd, doc_vsl_shift.mooring_frm_time AS shf_frm_time, 
				doc_vsl_shift.mooring_to_time AS shf_to_time, 	
				(SELECT u_name FROM users WHERE users.login_id= doc_vsl_depart.pilot_name) as pilot_name,
				doc_vsl_depart.draught
				FROM doc_vsl_depart
				INNER JOIN igm_masters ON igm_masters.id=doc_vsl_depart.igm_id 
				LEFT JOIN doc_vsl_arrival ON  doc_vsl_arrival.vvd_gkey=doc_vsl_depart.vvd_gkey
				LEFT JOIN doc_vsl_shift ON doc_vsl_shift.vvd_gkey=doc_vsl_depart.vvd_gkey
				INNER JOIN vsl_forward_info ON vsl_forward_info.vvd_gkey=doc_vsl_depart.vvd_gkey
				WHERE DATE(doc_vsl_depart.mooring_to_time) BETWEEN '$fromDate' AND '$toDate'
				AND doc_vsl_depart.pilot_name!='devpilot'
				AND vsl_forward_info.marine_forward_stat='1' AND vsl_forward_info.master_forward_stat='0'
				ORDER BY doc_vsl_depart.mooring_to_time DESC";
			}
			else if($org_Type_id=='82') // Accounts
			{
				// if($login_id=='sr_acc') //Sr. Accountant
				if($section=='acc') //Sr. Accountant
				{
					$data['title']="Vessel Forwarding by Sr. Accountant (Apps)";
					$departQuery = "SELECT  doc_vsl_depart.vvd_gkey, igm_masters.Vessel_Name AS vsl_name, igm_masters.Import_Rotation_No AS ib_vyg,
					DATE(doc_vsl_arrival.mooring_frm_time) AS ata,
					DATE(doc_vsl_depart.mooring_to_time) AS atd, doc_vsl_shift.mooring_frm_time AS shf_frm_time, 
					doc_vsl_shift.mooring_to_time AS shf_to_time, 		
					(SELECT u_name FROM users WHERE users.login_id= doc_vsl_depart.pilot_name) as pilot_name,
					doc_vsl_depart.draught
					FROM doc_vsl_depart
					INNER JOIN igm_masters ON igm_masters.id=doc_vsl_depart.igm_id 
					LEFT JOIN doc_vsl_arrival ON  doc_vsl_arrival.vvd_gkey=doc_vsl_depart.vvd_gkey
					LEFT JOIN doc_vsl_shift ON doc_vsl_shift.vvd_gkey=doc_vsl_depart.vvd_gkey
					INNER JOIN vsl_forward_info ON vsl_forward_info.vvd_gkey=doc_vsl_depart.vvd_gkey
					WHERE DATE(doc_vsl_depart.mooring_to_time) BETWEEN '$fromDate' AND '$toDate'
					AND doc_vsl_depart.pilot_name!='devpilot'
					AND vsl_forward_info.master_forward_stat='1' AND vsl_forward_info.sr_acnt_forward_stat='0'
					ORDER BY doc_vsl_depart.mooring_to_time DESC";
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding by Accountant (Apps)";
					/* $departQuery = "SELECT  doc_vsl_depart.vvd_gkey,igm_masters.Vessel_Name AS vsl_name, igm_masters.Import_Rotation_No AS ib_vyg,
					DATE(doc_vsl_arrival.mooring_frm_time) AS ata,
					DATE(doc_vsl_depart.mooring_to_time) AS atd, doc_vsl_shift.mooring_frm_time AS shf_frm_time, 
					doc_vsl_shift.mooring_to_time AS shf_to_time, 	
					(SELECT u_name FROM users WHERE users.login_id= doc_vsl_depart.pilot_name) as pilot_name,
					doc_vsl_depart.draught
					FROM doc_vsl_depart
					INNER JOIN igm_masters ON igm_masters.id=doc_vsl_depart.igm_id 
					LEFT JOIN doc_vsl_arrival ON  doc_vsl_arrival.vvd_gkey=doc_vsl_depart.vvd_gkey
					LEFT JOIN doc_vsl_shift ON doc_vsl_shift.vvd_gkey=doc_vsl_depart.vvd_gkey
					INNER JOIN vsl_forward_info ON vsl_forward_info.vvd_gkey=doc_vsl_depart.vvd_gkey
					WHERE DATE(doc_vsl_depart.mooring_to_time) BETWEEN '$fromDate' AND '$toDate'
					AND doc_vsl_depart.pilot_name!='devpilot'
					AND vsl_forward_info.sr_acnt_forward_stat='1' AND vsl_forward_info.billop_bill_stat='0'
					ORDER BY doc_vsl_depart.mooring_to_time DESC"; */
					
					$departQuery = "SELECT  doc_vsl_depart.vvd_gkey,igm_masters.Vessel_Name AS vsl_name, igm_masters.Import_Rotation_No AS ib_vyg,
					DATE(doc_vsl_arrival.mooring_frm_time) AS ata,
					DATE(doc_vsl_depart.mooring_to_time) AS atd, doc_vsl_shift.mooring_frm_time AS shf_frm_time, 
					doc_vsl_shift.mooring_to_time AS shf_to_time, 	
					(SELECT u_name FROM users WHERE users.login_id= doc_vsl_depart.pilot_name) as pilot_name,
					doc_vsl_depart.draught
					FROM doc_vsl_depart
					INNER JOIN igm_masters ON igm_masters.id=doc_vsl_depart.igm_id 
					LEFT JOIN doc_vsl_arrival ON  doc_vsl_arrival.vvd_gkey=doc_vsl_depart.vvd_gkey
					LEFT JOIN doc_vsl_shift ON doc_vsl_shift.vvd_gkey=doc_vsl_depart.vvd_gkey
					INNER JOIN vsl_forward_info ON vsl_forward_info.vvd_gkey=doc_vsl_depart.vvd_gkey
					WHERE DATE(doc_vsl_depart.mooring_to_time) BETWEEN '$fromDate' AND '$toDate'
					AND doc_vsl_depart.pilot_name!='devpilot'
					AND vsl_forward_info.sr_acnt_forward_stat='1' AND vsl_forward_info.acnt_bill_stat='0'
					ORDER BY doc_vsl_depart.mooring_to_time DESC";
				}					
			}
			// echo $departQuery;return;
			$departData = $this->bm->dataSelectDB1($departQuery);

			$data['departData']=$departData;
			$data['fromDate']=$fromDate;
			$data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingbyMarineByApp',$data);			
			$this->load->view('jsAssetsList');
		}
	}

	function vesselForwardingbyLetterListApps()
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

			$org_Type_id =$this->session->userdata('org_Type_id');			
			$data['msg'] = "";
			
			//$fromDate = $this->input->post("fromDate");
			//$toDate = $this->input->post("toDate");
			
			$data['title']="Vessel Forwarding Letter";
			$Query = "SELECT vsl_frwrd_letter_info.id, file_dt,file_sub,file_no,no_vsl FROM vsl_frwrd_letter_info ORDER BY vsl_frwrd_letter_info.id DESC";
			$letterList = $this->bm->dataSelectDB1($Query);

			$data['letterList']=$letterList;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingbyMasterListApps',$data);			
			$this->load->view('jsAssetsList');
		}
	}

	function vesselForwardingLetterApps()
	{
		$fileNo = $this->input->post("file_no");
		$filedt = $this->input->post("file_dt");
		$filesub = $this->input->post("file_sub");
		$no_vsl = $this->input->post("no_vsl");
		$letter_id = $this->input->post("letter_id");
					
		 $departQuery = "SELECT  doc_vsl_depart.vvd_gkey,igm_masters.Vessel_Name AS vsl_name, igm_masters.Import_Rotation_No AS ib_vyg,
					DATE(doc_vsl_arrival.mooring_frm_time) AS ata,
					DATE(doc_vsl_depart.mooring_to_time) AS atd, doc_vsl_shift.mooring_frm_time AS shf_frm_time, 
					doc_vsl_shift.mooring_to_time AS shf_to_time,
					(SELECT u_name FROM users WHERE users.login_id= doc_vsl_depart.pilot_name) as pilot_name,
					doc_vsl_depart.draught
					FROM doc_vsl_depart
					INNER JOIN igm_masters ON igm_masters.id=doc_vsl_depart.igm_id 
					LEFT JOIN doc_vsl_arrival ON  doc_vsl_arrival.vvd_gkey=doc_vsl_depart.vvd_gkey
					LEFT JOIN doc_vsl_shift ON doc_vsl_shift.vvd_gkey=doc_vsl_depart.vvd_gkey
					INNER JOIN vsl_forward_info ON vsl_forward_info.vvd_gkey=doc_vsl_depart.vvd_gkey
					WHERE vsl_forward_info.vsl_frwd_letter_no='$letter_id'";
	/* 	echo $fileNo;
		return;	 */

		$departData = $this->bm->dataSelectDB1($departQuery);
		$data['departData']=$departData;
		$data['fileNo']=$fileNo;
		$data['filedt']=$filedt;
		$data['filesub']=$filesub;
		$data['no_vsl']=$no_vsl;
		$this->load->view('vesselForwardingAppsLetterView',$data);		
	}
	
	
		
	function vesselAppForwardingPerform()
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
			$fromDate = $this->input->post("fromDate");
			$toDate = $this->input->post("toDate");
			$org_Type_id =$this->session->userdata('org_Type_id');

			if(isset($_POST['idchk']))
			{
				$rotationChk = $_POST['idchk'];
			}	
			if($org_Type_id=='83')  //marine
			{
				$k=0;
				foreach ($rotationChk as $rCheck)
				{
					 $chk_str="SELECT COUNT(*) AS rtnValue from vsl_forward_info WHERE  vvd_gkey='$rCheck'";
					$ckh_st = $this->bm->dataReturnDb1($chk_str);
					$resInsertst=0;
					if($ckh_st=='0')
					{
						$strInsert = "INSERT INTO vsl_forward_info(vvd_gkey, marine_forward_stat, marine_forward_by, marine_forward_at,  marine_forward_ip) 
											VALUES('$rCheck', '1', '$login_id', NOW(), '$ipAddress')";
						$resInsertst = $this->bm->dataInsertDB1($strInsert);
						$k++;
					}
				}
				if($resInsertst>0)
				{
					$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
				}
				else
				{
					$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
				}
				
				$data['title']="Vessel Forwarding by Marine";
				
				$departQuery = "SELECT  doc_vsl_depart.vvd_gkey,igm_masters.Vessel_Name AS vsl_name, igm_masters.Import_Rotation_No AS ib_vyg,
				DATE(doc_vsl_arrival.mooring_frm_time) AS ata,
				DATE(doc_vsl_depart.mooring_to_time) AS atd, doc_vsl_shift.mooring_frm_time AS shf_frm_time, 
				doc_vsl_shift.mooring_to_time AS shf_to_time, doc_vsl_depart.pilot_name, doc_vsl_depart.draught
				FROM doc_vsl_depart
				INNER JOIN igm_masters ON igm_masters.id=doc_vsl_depart.igm_id 
				LEFT JOIN doc_vsl_arrival ON  doc_vsl_arrival.vvd_gkey=doc_vsl_depart.vvd_gkey
				LEFT JOIN doc_vsl_shift ON doc_vsl_shift.vvd_gkey=doc_vsl_depart.vvd_gkey
				WHERE DATE(doc_vsl_depart.mooring_to_time) BETWEEN '$fromDate' AND '$toDate' AND doc_vsl_depart.vvd_gkey NOT IN (SELECT vsl_forward_info.vvd_gkey FROM vsl_forward_info)
				ORDER BY doc_vsl_depart.mooring_to_time DESC";
			}
			else if($org_Type_id=='81') //master
			{
				$fileNo = $this->input->post("fileNo");
				$filedt = $this->input->post("filedt");
				$filesub = $this->input->post("filesub");
				$noVsl=count($rotationChk);
				$chk_str="SELECT count(*) AS rtnValue from vsl_frwrd_letter_info WHERE file_no='$fileNo' ";
				$chk_st = $this->bm->dataReturnDb1($chk_str);
				if($chk_st>0)
				{
					$data['msg'] = "<font color='red' size=2>You already used this File no. Use new one.</font>";
				}
				else
				{
					echo $noVsl.'<br/>';
					
					$insert_str="INSERT INTO vsl_frwrd_letter_info (file_dt, file_sub, file_no, no_vsl) VALUES ('$filedt', '$filesub','$fileNo', '$noVsl')";			
					$insert_st = $this->bm->dataInsertDB1($insert_str);
					$k=0;
					$str="SELECT id AS rtnValue from vsl_frwrd_letter_info WHERE file_no='$fileNo' ";
					$letter_no = $this->bm->dataReturnDb1($str);
					print_r($rotationChk);
					foreach ($rotationChk as $rCheck)
					{
						echo $updateSt="UPDATE vsl_forward_info SET master_forward_stat='1', master_forward_by='$login_id', master_forward_at=NOW(),
									master_forward_ip='$ipAddress', vsl_frwd_letter_no='$letter_no' WHERE vvd_gkey='$rCheck'";
						$update_st = $this->bm->dataUpdateDB1($updateSt);
						$k++;
					}
					$data['msg']="";
					if($update_st>0)
					{
						$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					}
					else
					{
						$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					}
				}
				$data['title']="Vessel Forwarding by Master";
				
				 $departQuery = "SELECT  doc_vsl_depart.vvd_gkey,  igm_masters.Vessel_Name AS vsl_name, igm_masters.Import_Rotation_No AS ib_vyg,
				DATE(doc_vsl_arrival.mooring_frm_time) AS ata,
				DATE(doc_vsl_depart.mooring_to_time) AS atd, doc_vsl_shift.mooring_frm_time AS shf_frm_time, 
				doc_vsl_shift.mooring_to_time AS shf_to_time, doc_vsl_depart.pilot_name, doc_vsl_depart.draught
				FROM doc_vsl_depart
				INNER JOIN igm_masters ON igm_masters.id=doc_vsl_depart.igm_id 
				LEFT JOIN doc_vsl_arrival ON  doc_vsl_arrival.vvd_gkey=doc_vsl_depart.vvd_gkey
				LEFT JOIN doc_vsl_shift ON doc_vsl_shift.vvd_gkey=doc_vsl_depart.vvd_gkey
				INNER JOIN vsl_forward_info ON vsl_forward_info.vvd_gkey=doc_vsl_depart.vvd_gkey
				WHERE DATE(doc_vsl_depart.mooring_to_time) BETWEEN '$fromDate' AND '$toDate'
				AND vsl_forward_info.marine_forward_stat='1' AND vsl_forward_info.master_forward_stat='0'
				ORDER BY doc_vsl_depart.mooring_to_time DESC";
			}
			else if($org_Type_id=='82')
			{
				if($login_id=='sr_acc') //Sr. Accountant 
				{
					$k=0;
					foreach ($rotationChk as $rCheck)
					{
						$updateSt="UPDATE vsl_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),
									sr_acnt_forward_ip='$ipAddress' WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdateDB1($updateSt);
						$k++;
					} 
					$data['msg']="";
					if($update_st>0)
					{
						$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					}
					else
					{
						$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					}
					$data['title']="Vessel Forwarding by Sr. Accountant";
					$departQuery = "SELECT  doc_vsl_depart.vvd_gkey, igm_masters.Vessel_Name AS vsl_name, igm_masters.Import_Rotation_No AS ib_vyg,
					DATE(doc_vsl_arrival.mooring_frm_time) AS ata,
					DATE(doc_vsl_depart.mooring_to_time) AS atd, doc_vsl_shift.mooring_frm_time AS shf_frm_time, 
					doc_vsl_shift.mooring_to_time AS shf_to_time, doc_vsl_depart.pilot_name, doc_vsl_depart.draught
					FROM doc_vsl_depart
					INNER JOIN igm_masters ON igm_masters.id=doc_vsl_depart.igm_id 
					LEFT JOIN doc_vsl_arrival ON  doc_vsl_arrival.vvd_gkey=doc_vsl_depart.vvd_gkey
					LEFT JOIN doc_vsl_shift ON doc_vsl_shift.vvd_gkey=doc_vsl_depart.vvd_gkey
					INNER JOIN vsl_forward_info ON vsl_forward_info.vvd_gkey=doc_vsl_depart.vvd_gkey
					WHERE DATE(doc_vsl_depart.mooring_to_time) BETWEEN '$fromDate' AND '$toDate'
					AND vsl_forward_info.master_forward_stat='1' AND vsl_forward_info.sr_acnt_forward_stat='0'
					ORDER BY doc_vsl_depart.mooring_to_time DESC";
				}
				else if ($login_id=='acc') //Accountant 
				{
					$k=0;
					foreach ($rotationChk as $rCheck)
					{
						$updateSt="UPDATE vsl_forward_info SET billop_bill_stat='1', billop_bill_by='$login_id', billop_bill_at=NOW(),
									billop_bill_ip='$ipAddress' WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdateDB1($updateSt);
						$k++;
					} 
					$data['msg']="";
					
					/* if($update_st>0)
					{
						$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					}
					else
					{
						$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					} */
					$data['title']="Vessel Forwarding by Accountant";
					$departQuery = "SELECT  doc_vsl_depart.vvd_gkey,igm_masters.Vessel_Name AS vsl_name, igm_masters.Import_Rotation_No AS ib_vyg,
					DATE(doc_vsl_arrival.mooring_frm_time) AS ata,
					DATE(doc_vsl_depart.mooring_to_time) AS atd, doc_vsl_shift.mooring_frm_time AS shf_frm_time, 
					doc_vsl_shift.mooring_to_time AS shf_to_time, doc_vsl_depart.pilot_name, doc_vsl_depart.draught
					FROM doc_vsl_depart
					INNER JOIN igm_masters ON igm_masters.id=doc_vsl_depart.igm_id 
					LEFT JOIN doc_vsl_arrival ON  doc_vsl_arrival.vvd_gkey=doc_vsl_depart.vvd_gkey
					LEFT JOIN doc_vsl_shift ON doc_vsl_shift.vvd_gkey=doc_vsl_depart.vvd_gkey
					INNER JOIN vsl_forward_info ON vsl_forward_info.vvd_gkey=doc_vsl_depart.vvd_gkey
					WHERE DATE(doc_vsl_depart.mooring_to_time) BETWEEN '$fromDate' AND '$toDate'
					AND vsl_forward_info.sr_acnt_forward_stat='1' AND vsl_forward_info.billop_bill_stat='0'
					ORDER BY doc_vsl_depart.mooring_to_time DESC";
				}				
			}
				$departData = $this->bm->dataSelectDB1($departQuery);

				$data['departData']=$departData;
				$data['fromDate']=$fromDate;
				$data['toDate']=$toDate;
				$data['flag'] = 1;
				$data['login_id']=$login_id;
				$data['org_Type_id']=$org_Type_id;
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('vesselForwardingbyMarineByApp',$data);			
				$this->load->view('jsAssets');
		}		
	}
	
	// for inbound - start
		
	function outerAnchorageForwarding()		// List
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
			$section = $this->session->userdata('section');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$data['msg'] = "";
			
			// $fromDate = $this->input->post("fromDate");
			// $toDate = $this->input->post("toDate");
			if($org_Type_id=='83') //Marine
			{
				// $data['title']="Outer Anchorage Forwarding by Marine";
				$data['title']="Vessel Not Entering by Marine";
				$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
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
				WHERE sparcsn4.argo_carrier_visit.phase='20INBOUND' AND  vvd_gkey NOT IN (SELECT ctmsmis.vsl_forward_info.vvd_gkey FROM ctmsmis.vsl_forward_info) AND sparcsn4.ref_country.cntry_code!='BD'
				ORDER BY sparcsn4.argo_carrier_visit.atd DESC";
			}
			else if($org_Type_id=='81') //Master			
			{
				// $data['title']="Outer Anchorage Forwarding by Harbaour Master";
				$data['title']="Vessel Not Entering by Harbaour Master";
				$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
				DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,
				sparcsn4.vsl_vessels.ves_captain,sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,
				sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd,
				sparcsn4.vsl_vessel_visit_details.ib_vyg
				FROM sparcsn4.argo_carrier_visit
				INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
				INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
				INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
				INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
				INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
				WHERE sparcsn4.argo_carrier_visit.phase='20INBOUND'
				AND ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_stat='0' 
				AND sparcsn4.ref_country.cntry_code!='BD'
				ORDER BY sparcsn4.argo_carrier_visit.atd DESC";
			}
			else if($org_Type_id=='82') // Accounts
			{
				// if($login_id=='sr_acc') //Sr. Accountant
				if($section=='acc') // Accountant - by Intakhab - 2022-08-28
				{
					// $data['title']="Outer Anchorage Forwarding by Sr. Accountant";
					$data['title']="Vessel Not Entering by Sr. Accountant";
					$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
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
					AND ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
					 AND sparcsn4.ref_country.cntry_code!='BD'
					ORDER BY sparcsn4.argo_carrier_visit.atd DESC";
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28
				{
					// $data['title']="Outer Anchorage Forwarding by Accountant";
					$data['title']="Vessel Not Entering by Accountant";
					$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
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
					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_stat='0'
					AND sparcsn4.ref_country.cntry_code!='BD'
					ORDER BY sparcsn4.argo_carrier_visit.atd DESC";
				}					
			}
			$departData = $this->bm->dataSelect($departQuery);

			$data['departData']=$departData;
			// $data['fromDate']=$fromDate;
			// $data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;


			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardOuterAnchorage',$data);			
			$this->load->view('jsAssets');
		}
	}
	
	// forwarding - new start
	function vslNotEnteringForwardList()		// Not Entering
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
			$section = $this->session->userdata('section');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$data['msg'] = "";
			$masterFlag = "";
			// $fromDate = $this->input->post("fromDate");
			// $toDate = $this->input->post("toDate");
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Vessels Not Entering - Forwarding by Marine";				
				
				$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
				FROM outer_vsl_visit_info
				INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				WHERE outer_vsl_visit_info.id NOT IN (SELECT vsl_visit_id FROM outer_vsl_forward_info) AND agent_entry_approve_flag='1'
				ORDER BY date_of_arrival DESC";
				
				// $departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat
				// FROM outer_vsl_visit_info
				// INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				// INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				// INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
				// WHERE agent_entry_approve_flag='1'
				// ORDER BY date_of_arrival DESC";
			}
			else if($org_Type_id=='81') //Master			
			{
				$masterFlag = "master";
				$data['title']="Vessels Not Entering - Forwarding by Harbaour Master";
				
				// $departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat
				// FROM outer_vsl_visit_info
				// INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				// INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				// INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
				// WHERE outer_vsl_forward_info.marine_forward_stat='1' AND outer_vsl_forward_info.master_forward_stat='0'
				// ORDER BY date_of_arrival DESC";
				
				$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat,outer_vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM outer_vsl_visit_info
				INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
				WHERE outer_vsl_forward_info.marine_forward_stat='1' AND outer_vsl_forward_info.master_forward_stat='0'
				ORDER BY date_of_arrival DESC";
			}
			else if($org_Type_id=='82') // Accounts
			{
				// if($login_id=='sr_acc') //Sr. Accountant
				if($section=='acc') // Accountant - by Intakhab - 2022-08-28
				{
					$data['title']="Vessels Not Entering - Forwarding by Sr. Accountant";
					
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat,outer_vsl_forward_info.master_forward_at AS forwarded_dt
					FROM outer_vsl_visit_info
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
					WHERE outer_vsl_forward_info.master_forward_stat='1' AND outer_vsl_forward_info.sr_acnt_forward_stat='0'
					ORDER BY date_of_arrival DESC";
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28
				{
					$data['title']="Vessels Not Entering - Forwarding by Accountant";
					
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat
					FROM outer_vsl_visit_info
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
					WHERE outer_vsl_forward_info.sr_acnt_forward_stat='1' AND outer_vsl_forward_info.billop_bill_stat='0'
					ORDER BY date_of_arrival DESC";
				}					
			}
			$departData = $this->bm->dataSelectDB1($departQuery);

			$data['departData']=$departData;
			$data['masterFlag']=$masterFlag;
			// $data['fromDate']=$fromDate;
			// $data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			// $this->load->view('vesselForwardOuterAnchorage',$data);			
			$this->load->view('vesselForwardList_notEntering',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	
	function vslNotEnteringForwardingPerform()			// Not Entering
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
			$fromDate = $this->input->post("fromDate");
			$toDate = $this->input->post("toDate");
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$departQuery="";
			$masterFlag="";
			
			if(isset($_POST['idchk']))
			{
				$rotationChk = $_POST['idchk'];
			}	
			if($org_Type_id=='83')		// marine
			{
				$data['title']="Vessels Not Entering - Forwarding by Marine";
				$k=0;
				
				// lot data - start
				
				$sql_nextLotSl = "SELECT IFNULL(MAX(lot_sl),0)+1 AS rtnValue
				FROM vsl_forward_lot_info
				WHERE lot_dt=DATE(NOW())";
								
				$nextLotSl = $this->bm->dataReturnDb1($sql_nextLotSl);				
				$nextLotSl = (strlen($nextLotSl)<2)?("0".$nextLotSl):$nextLotSl;		
				
				$nextLotId = date('d').date('m').substr(date('Y'),2,2).$nextLotSl;				
								
				$totVsl = count($rotationChk);
								
				$lotInsFlag = 0;
				$insertLotInfo = "INSERT INTO vsl_forward_lot_info(lot_sl,lot_dt,lot_id,tot_vsl,forward_at,forward_by,vsl_lot_type)
								VALUES('$nextLotSl',CURDATE(),'$nextLotId','$totVsl',NOW(),'$login_id','Not Entering')";								
				$lotInsFlag = $this->bm->dataInsertDB1($insertLotInfo);
				
				if($lotInsFlag == 0)
				{
					echo "<font color='red'>Lot not created</font>";
					return;
				}
				
				$sql_lotId = "SELECT MAX(id) AS rtnValue FROM vsl_forward_lot_info";
				$lotId = $this->bm->dataReturnDb1($sql_lotId);
				// echo $lotId;return;
				// lot data - end
				
				foreach ($rotationChk as $rCheck)
				{
					// $chk_str="SELECT COUNT(*) AS rtnValue from ctmsmis.vsl_forward_info WHERE  vvd_gkey='$rCheck'";
					$chk_str="SELECT COUNT(*) AS rtnValue from outer_vsl_forward_info WHERE  vsl_visit_id='$rCheck'";
					$ckh_st = $this->bm->dataReturnDb1($chk_str);
					$resInsertst=0;
					if($ckh_st=='0')
					{
						$strInsert = "INSERT INTO outer_vsl_forward_info(vsl_fwd_lot_info_id,vsl_visit_id, marine_forward_stat, marine_forward_by, marine_forward_at,  marine_forward_ip) 
						VALUES('$lotId','$rCheck', '1', '$login_id', NOW(), '$ipAddress')";
						$resInsertst = $this->bm->dataInsertDB1($strInsert);
						$k++;
					}
				}
				if($resInsertst>0)
				{
					$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
				}
				else
				{
					$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
				}								
				
				// $departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
				// FROM outer_vsl_visit_info
				// INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				// INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				// WHERE outer_vsl_visit_info.id NOT IN (SELECT vsl_visit_id FROM outer_vsl_forward_info) ORDER BY date_of_arrival DESC";
				
				$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
				FROM outer_vsl_visit_info
				INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				WHERE outer_vsl_visit_info.id NOT IN (SELECT vsl_visit_id FROM outer_vsl_forward_info) AND agent_entry_approve_flag='1'
				ORDER BY date_of_arrival DESC";
			}
			else if($org_Type_id=='81')		//master
			{
				$masterFlag = "master";
				$data['title']="Vessels Not Entering - Forwarding by Harbaour Master";
				$fileNo = $this->input->post("fileNo");
				$filedt = $this->input->post("filedt");
				$filesub = $this->input->post("filesub");
				$noVsl=count($rotationChk);
				$chk_str="SELECT count(*) AS rtnValue from outer_visit_frwrd_letter_info WHERE file_no='$fileNo' ";
				// echo $chk_str;return;
				$chk_st = $this->bm->dataReturnDb1($chk_str);
				if($chk_st>0)
				{
					$data['msg'] = "<font color='red' size=2>You already used this File no. Use new one.</font>";
				}
				else
				{
					$insert_str="INSERT INTO outer_visit_frwrd_letter_info (file_dt, file_sub, file_no, no_vsl)
					VALUES ('$filedt', '$filesub','$fileNo', '$noVsl')";			
					$insert_st = $this->bm->dataInsertDB1($insert_str);
					$k=0;
					$str="SELECT id AS rtnValue from outer_visit_frwrd_letter_info WHERE file_no='$fileNo' ";
					$letter_no = $this->bm->dataReturnDb1($str);
					
					foreach ($rotationChk as $rCheck)
					{
						$updateSt="UPDATE outer_vsl_forward_info 
									SET master_forward_stat='1', master_forward_by='$login_id', master_forward_at=NOW(),
									master_forward_ip='$ipAddress', vsl_frwd_letter_no='$letter_no' WHERE vsl_visit_id='$rCheck'";
						$update_st = $this->bm->dataUpdateDB1($updateSt);
						$k++;
					}
					$data['msg']="";
					if($update_st>0)
					{
						$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					}
					else
					{
						$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					}
				}
				
				$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
				FROM outer_vsl_visit_info
				INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
				WHERE outer_vsl_forward_info.marine_forward_stat='1' AND outer_vsl_forward_info.master_forward_stat='0'
				ORDER BY date_of_arrival DESC";
			}
			else if($org_Type_id=='82')
			{
				if($login_id=='sr_acc') //Sr. Accountant 
				{
					$data['title']="Vessels Not Entering - Forwarding by Sr. Accountant";
					$k=0;
					foreach ($rotationChk as $rCheck)
					{
						$updateSt="UPDATE outer_vsl_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),
									sr_acnt_forward_ip='$ipAddress' WHERE vsl_visit_id='$rCheck'";
									
						$update_st = $this->bm->dataUpdateDB1($updateSt);
						$k++;
					} 
					$data['msg']="";
					if($update_st>0)
					{
						$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					}
					else
					{
						$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					}
					
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
					FROM outer_vsl_visit_info
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
					WHERE outer_vsl_forward_info.master_forward_stat='1' AND outer_vsl_forward_info.sr_acnt_forward_stat='0'
					ORDER BY date_of_arrival DESC";
				}
				else if ($login_id=='acc') //Accountant 
				{
					$data['title']="Vessels Not Entering - Forwarding by Accountant";
					$k=0;
					foreach ($rotationChk as $rCheck)
					{
						$updateSt="UPDATE outer_vsl_forward_info SET billop_bill_stat='1', billop_bill_by='$login_id', billop_bill_at=NOW(),
									billop_bill_ip='$ipAddress' WHERE vsl_visit_id='$rCheck'";
									
						$update_st = $this->bm->dataUpdateDB1($updateSt);
						$k++;
					} 
					$data['msg']="";
					
					$data['title']="Vessel Forwarding by Accountant";
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
					FROM outer_vsl_visit_info
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
					WHERE outer_vsl_forward_info.sr_acnt_forward_stat='1' AND outer_vsl_forward_info.billop_bill_stat='0'
					ORDER BY date_of_arrival DESC";
				}				
			}

			$departData = $this->bm->dataSelectDB1($departQuery);

			$data['departData']=$departData;
			$data['masterFlag']=$masterFlag;
			$data['fromDate']=$fromDate;
			$data['toDate']=$toDate;
			$data['flag'] = 1;
			$data['login_id']=$login_id;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
/* 			if($this->input->post('fwBtn'))
				$this->load->view('vesselForwardingbyMarineForm',$data);
			else if($this->input->post('fwBtnOuter'))
				$this->load->view('vesselForwardOuterAnchorage',$data); */
			$this->load->view('vesselForwardList_notEntering',$data);
			$this->load->view('jsAssets');
		}
		
	}
	// forwarding - new end
	
	// vsl function - ovi - start
	function vesselsNotEntering()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Vessel Entry Form";
			$data['msg'] = "";
			$data['msg1'] = "";
			$data['msg2'] = "";
			$data['agentFlag'] = "";
			$data['vesselFlag'] = "";
			$data['vesselVisitFlag'] = "";
			
			$sql_vslType = "SELECT vsl_type FROM outer_vsl_type";
			$rslt_vslType = $this->bm->dataSelectDB1($sql_vslType);
			$data['rslt_vslType'] = $rslt_vslType;
			
            $this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselsNotEnteringForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function vesselsNotEnteringAgentForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Agent Entry Form";
			$data['msg'] = "";
			$data['msg1'] = "";
			$data['msg2'] = "";
			$data['agentFlag'] = "";
			$data['vesselFlag'] = "";
			$data['vesselVisitFlag'] = "";
            $this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselsNotEnteringAgentForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function visitedVesselsNotEntering()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Vessel Visit Entry Form";
			$data['msg'] = "";
			$data['msg1'] = "";
			$data['msg2'] = "";
			$data['agentFlag'] = "";
			$data['vesselFlag'] = "";
			$data['vesselVisitFlag'] = "";
            $this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('visitedvesselsNotEnteringForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function vesselVisitAction()
	{		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			if (isset($_POST['vesselVisitInsert']))
			{
				$login_id = $this->session->userdata('login_id');
				$ipAddress = $_SERVER['REMOTE_ADDR'];
				$visitedVesselId = $this->input->post('visitedVesselId');
			
				$vslName = $this->input->post('vslName');
				$rotNo = $this->input->post('rotNo');
				$dateOfArrival = $this->input->post('dateOfArrival');
				// $timeOfArrival = $this->input->post('timeOfArrival');
				$ataHH = $this->input->post('ataHH');
				$ataMM = $this->input->post('ataMM');
				$timeOfArrival = $ataHH.":".$ataMM.":00";
				
				$dateOfDeparture = $this->input->post('dateOfDeparture');
				// $timeOfDeparture = $this->input->post('timeOfDeparture');
				$atdHH = $this->input->post('atdHH');
				$atdMM = $this->input->post('atdMM');
				$timeOfDeparture = $atdHH.":".$atdMM.":00";
				
				$voyage_no = $this->input->post('voyage_no');
				$remarks = $this->input->post('remarks');
				$vslType = $this->input->post('vslType');
				
				
				if($vslType=="")
				{
					$msg = "<font color='red'>Vessel type is blank.</font>";
				}
				else
				{		

					// if vslType then generate rotation
					// check how many vessel of that agent in that year
					// add 1, make new rotation
					// assign rotation
					
					if($vslType=="Self Piloting")
					{
						$sql_agentInfo = "SELECT outer_agent_info.id AS agent_id,outer_agent_info.agent_name,outer_agent_info.agent_code
						FROM outer_vsl_info
						INNER JOIN outer_agent_info ON outer_agent_info.id = outer_vsl_info.agent_id
						WHERE outer_vsl_info.vsl_name='$vslName'";
						$rslt_agentInfo = $this->bm->dataSelectDB1($sql_agentInfo);
						
						$agentId = $rslt_agentInfo[0]['agent_id'];
						$agentName = $rslt_agentInfo[0]['agent_name'];
						$agentCode = $rslt_agentInfo[0]['agent_code'];
						
						$sql_nextSL = "SELECT COUNT(*)+1 AS rtnValue
						FROM outer_vsl_visit_info
						INNER JOIN outer_vsl_info ON outer_vsl_info.id = outer_vsl_visit_info.outer_vsl_id
						WHERE vsl_type='Self Piloting' AND outer_vsl_info.agent_id='$agentId' AND YEAR(outer_vsl_visit_info.entry_at)=YEAR(NOW())";
						$nextSL = $this->bm->dataReturnDb1($sql_nextSL);
				
						$rotNo = date("Y")."/".$agentCode.$nextSL;
					}
					
					$agent_entry_flag = 0;
					
					if($login_id == "agent1")
					{
						$agent_entry_flag = 1;
					}
					
					$sql_chkVslVisit = "SELECT COUNT(*) AS rtnValue
					FROM outer_vsl_visit_info
					WHERE vsl_name='$vslName' AND imp_rot='$rotNo' AND delete_flag='0'";
					$chkVslVisit = $this->bm->dataReturnDb1($sql_chkVslVisit);
					
					$msg = "";
					$msg1 = "";
					$msg2 = "";
					if($chkVslVisit == 0)
					{					
						$sql_insertVslVisit = "";
						
						$sql_insertVslVisit = "INSERT INTO outer_vsl_visit_info(vsl_name,outer_vsl_id,imp_rot,date_of_arrival,time_of_arrival,date_of_departure,time_of_departure,voyage_no,remarks,
						agent_entry_flag,entry_at,entry_by,entry_ip)
						VALUES('$vslName','$visitedVesselId','$rotNo','$dateOfArrival','$timeOfArrival','$dateOfDeparture','$timeOfDeparture','$voyage_no',
						'$remarks','$agent_entry_flag',NOW(),'$login_id','$ipAddress')";
						
						$flag = $this->bm->dataInsertDB1($sql_insertVslVisit);
						
						if($flag == 1)
						{
							$msg = "<font color='green'>Vessel visit is created.</font>";
						}
					}
				}
				

				$data['title']="Vessel Visit Entry Form";
				$data['msg'] = $msg;			
				$data['msg1'] = $msg1;			
				$data['msg2'] = $msg2;
				$data['agentFlag'] = "";
				$data['vesselFlag'] = "";
				$data['vesselVisitFlag'] = "";			
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('visitedvesselsNotEnteringForm',$data);
				$this->load->view('jsAssets');
			}
			else if(isset($_POST['vesselVisitUpdate']))
			{
				$vesselVisitUpdateId = $this->input->post('vesselVisitUpdateId');
				$login_id = $this->session->userdata('login_id');
				$ipAddress = $_SERVER['REMOTE_ADDR'];
				$vslName = $this->input->post('vslName');
				$rotNo = $this->input->post('rotNo');
				$dateOfArrival = $this->input->post('dateOfArrival');
				// $timeOfArrival = $this->input->post('timeOfArrival');
				$ataHH = $this->input->post('ataHH');
				$ataMM = $this->input->post('ataMM');
				$timeOfArrival = $ataHH.":".$ataMM.":00";
				
				$dateOfDeparture = $this->input->post('dateOfDeparture');
				// $timeOfDeparture = $this->input->post('timeOfDeparture');
				$atdHH = $this->input->post('atdHH');
				$atdMM = $this->input->post('atdMM');
				$timeOfDeparture = $atdHH.":".$atdMM.":00";
				
				$voyage_no = $this->input->post('voyage_no'); 
				$remarks = $this->input->post('remarks'); 
				// $remarks = str_replace("/"," ",$remarks);
				
				$sql_vslId = "SELECT id AS rtnValue FROM outer_vsl_info WHERE vsl_name='$vslName'";				
				$visitedVesselId = $this->bm->dataReturnDb1($sql_vslId); 
				
				$login_id = $this->session->userdata('login_id');
				$ipaddress = $_SERVER['REMOTE_ADDR'];
				
				// vsl visit log - start
				
				$sql_matchVslVisitData = "SELECT id,outer_vsl_id,vsl_name,imp_rot,date_of_arrival,time_of_arrival,date_of_departure,time_of_departure,voyage_no,remarks
										FROM outer_vsl_visit_info
										WHERE id = '$vesselVisitUpdateId'";
				$rslt_matchVslVisitData = $this->bm->dataSelectDB1($sql_matchVslVisitData);
				
				if(count($rslt_matchVslVisitData)>0)
				{
					$vslVisit_tbl_id = $rslt_matchVslVisitData[0]['id'];
					$prevVal = "";
					$currVal = "";
					$columnName = "";
					
					if($rslt_matchVslVisitData[0]['outer_vsl_id'] != $visitedVesselId)
					{
						$prevVal = $rslt_matchVslVisitData[0]['outer_vsl_id'];
						$currVal = $visitedVesselId;
						$columnName = "outer_vsl_id";
						
						$sql_insertVslVisitLog = "INSERT INTO outer_vsl_visit_change_log(vsl_visit_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
						VALUES('$vslVisit_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslVisitLog);
					}
					if($rslt_matchVslVisitData[0]['imp_rot'] != $rotNo)
					{
						$prevVal = $rslt_matchVslVisitData[0]['imp_rot'];
						$currVal = $rotNo;
						$columnName = "imp_rot";
						
						$sql_insertVslVisitLog = "INSERT INTO outer_vsl_visit_change_log(vsl_visit_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
						VALUES('$vslVisit_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslVisitLog);
					}
					if($rslt_matchVslVisitData[0]['date_of_arrival'] != $dateOfArrival)
					{
						$prevVal = $rslt_matchVslVisitData[0]['date_of_arrival'];
						$currVal = $dateOfArrival;
						$columnName = "date_of_arrival";
						
						$sql_insertVslVisitLog = "INSERT INTO outer_vsl_visit_change_log(vsl_visit_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
						VALUES('$vslVisit_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslVisitLog);
					}
					if($rslt_matchVslVisitData[0]['time_of_arrival'] != $timeOfArrival)
					{
						$prevVal = $rslt_matchVslVisitData[0]['time_of_arrival'];
						$currVal = $timeOfArrival;
						$columnName = "time_of_arrival";
						
						$sql_insertVslVisitLog = "INSERT INTO outer_vsl_visit_change_log(vsl_visit_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
						VALUES('$vslVisit_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslVisitLog);
					}
					if($rslt_matchVslVisitData[0]['date_of_departure'] != $dateOfDeparture)
					{
						$prevVal = $rslt_matchVslVisitData[0]['date_of_departure'];
						$currVal = $dateOfDeparture;
						$columnName = "date_of_departure";
						
						$sql_insertVslVisitLog = "INSERT INTO outer_vsl_visit_change_log(vsl_visit_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
						VALUES('$vslVisit_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslVisitLog);
					}
					if($rslt_matchVslVisitData[0]['time_of_departure'] != $timeOfDeparture)
					{
						$prevVal = $rslt_matchVslVisitData[0]['time_of_departure'];
						$currVal = $timeOfDeparture;
						$columnName = "time_of_departure";
						
						$sql_insertVslVisitLog = "INSERT INTO outer_vsl_visit_change_log(vsl_visit_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
						VALUES('$vslVisit_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslVisitLog);
					}
					if($rslt_matchVslVisitData[0]['voyage_no'] != $voyage_no)
					{
						$prevVal = $rslt_matchVslVisitData[0]['voyage_no'];
						$currVal = $voyage_no;
						$columnName = "voyage_no";
						
						$sql_insertVslVisitLog = "INSERT INTO outer_vsl_visit_change_log(vsl_visit_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
						VALUES('$vslVisit_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslVisitLog);
					}
					if($rslt_matchVslVisitData[0]['remarks'] != $remarks)
					{
						$prevVal = $rslt_matchVslVisitData[0]['remarks'];
						$currVal = $remarks;
						$columnName = "remarks";
						
						$sql_insertVslVisitLog = "INSERT INTO outer_vsl_visit_change_log(vsl_visit_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
						VALUES('$vslVisit_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslVisitLog);
					}
				}
				
				// vsl visit log - end
				
				$updateQuery="UPDATE outer_vsl_visit_info
				SET imp_rot='$rotNo',date_of_arrival='$dateOfArrival', time_of_arrival='$timeOfArrival', date_of_departure='$dateOfDeparture',
				time_of_departure='$timeOfDeparture', remarks='$remarks',voyage_no='$voyage_no',
				update_at=NOW(),update_by='$login_id',update_ip='$ipAddress'
				WHERE id='$vesselVisitUpdateId'";
				// echo $updateQuery;return;
				$update_st = $this->bm->dataUpdateDB1($updateQuery);
				
				$vslVisitInfoQuery = "SELECT * FROM outer_vsl_visit_info  WHERE delete_flag='0' AND agent_entry_approve_flag ='0' ";
				$vslVisitInfoData = $this->bm->dataSelectDB1($vslVisitInfoQuery);
				
				$data['title']="Vessel Visit List";
				$data['vslVisitInfoData']=$vslVisitInfoData;
				$data['msg']="";
				$data['msg1']="";
				if($update_st==1)
					$data['msg2']="<font size='3' color='green'>Updated Successfully</font>";
				else
					$data['msg2']="<font size='3' color='red'>Failed! Try again.</font>";
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('outerVesselVistInfoListView',$data);
				$this->load->view('jsAssetsList');
			}		
		}
	}
	
	function getVslInfo()
	{
		$vslName = $_GET["vslName"];
		
		$sql_getVslInfo = "SELECT outer_vsl_info.id,grt,nrt,flag,vsl_type,outer_agent_info.agent_name
		FROM outer_vsl_info
		INNER JOIN outer_agent_info ON outer_agent_info.id = outer_vsl_info.agent_id
		WHERE vsl_name='$vslName'";
		$rslt_getVslInfo = $this->bm->dataSelectDB1($sql_getVslInfo);
		
		$grt = "";
		$nrt = "";
		$flag = "";
		$id="";
		$agent_name="";
		
		for($i=0;$i<count($rslt_getVslInfo);$i++)
		{
			$grt = $rslt_getVslInfo[$i]['grt'];
			$nrt = $rslt_getVslInfo[$i]['nrt'];
			$flag = $rslt_getVslInfo[$i]['flag'];
			$id = $rslt_getVslInfo[$i]['id'];
			$agent_name = $rslt_getVslInfo[$i]['agent_name'];
			$vsl_type = $rslt_getVslInfo[$i]['vsl_type'];
		}
		
		$data['grt']=$grt;
		$data['nrt']=$nrt;
		$data['flag']=$flag;
		$data['id']=$id;
		$data['agent_name']=$agent_name;
		$data['vsl_type']=$vsl_type;
		
		echo json_encode($data);
		
	}
	
	function saveVesselInformation()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			if (isset($_POST['insertVessel']))
			{
				$vslInfoData="";
				$insertSt="yes";
				$data['msg1']="";
				$vesselId = $this->input->post('vesselId');
				$vesselName = $this->input->post('vesselName');
				$vesselClass = $this->input->post('vesselClass');
				$radioCallSign = $this->input->post('radioCallSign');
				$grt = $this->input->post('grt');
				$nrt = $this->input->post('nrt');
				$flag = $this->input->post('flag');
				
				$loa = $this->input->post('loa');
				$imo = $this->input->post('imo');
				$voyNo = $this->input->post('voyNo');
				
				$agentInfo = $this->input->post('agentInfo');
				$vslType = $this->input->post('vslType');		// private or not entering
				$login_id = $this->session->userdata('login_id');
				$ipaddress = $_SERVER['REMOTE_ADDR'];

				$vesselQuery="SELECT vsl_name FROM outer_vsl_info WHERE delete_flag = 0";
				$vslInfoData = $this->bm->dataSelectDB1($vesselQuery);
				
				for($i=0; $i<count($vslInfoData);$i++)
				{
					if($vesselName==$vslInfoData[$i]['vsl_name'])
					{
						$insertSt="no";
					}
				}
				/*$sql_chkVslVisit = "SELECT COUNT(*) AS rtnValue FROM outer_vsl_info WHERE vsl_name='$vesselName'";
				$chkVslVisit = $this->bm->dataReturnDb1($sql_chkVslVisit);
				if($chkVslVisit == 0){
					$insertSt="no";
				}*/
				if($insertSt=="yes")
				{
					$sqlInsert="INSERT INTO outer_vsl_info(vsl_id,vsl_name,vsl_class,vsl_type,radio_call_sign,grt,nrt,flag,loa,imo,voyage_no,agent_id,
					entry_at,entry_by,entry_ip)
					VALUES ('$vesselId', '$vesselName', '$vesselClass','$vslType','$radioCallSign','$grt', '$nrt',
					'$flag','$loa','$imo','$voyNo','$agentInfo',NOW(), '$login_id', '$ipaddress')";
					
					$insertState = $this->bm->dataInsertDB1($sqlInsert);
					if($insertState>0)
					{
						$data['msg1']="<font size='2' color='green'>Vessel has been successfully created.</font>";
					}
					else
					{
						$data['msg1']="<font size='2' color='red'>Sorry! try again later.</font>";
					}	
				}
				else
				{
					$data['msg1']="<font size='2' color='red'> Vessel was already created.</font>";
				}
			
				$data['title']="Vessel Entry Form";
				$data['msg']="";
				$data['msg2']="";
				$data['agentFlag'] = "";
				$data['vesselFlag'] = "";
				$data['vesselVisitFlag'] = "";
				//echo $data['title'];
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				// $this->load->view('vesselNotEnteringView',$data);
				$this->load->view('vesselsNotEnteringForm',$data);
				$this->load->view('jsAssets');
			}
			else if(isset($_POST['updateVeessel']))
			{
				$updateVesselId = $this->input->post('updateVesselId');  
				$vesselId = $this->input->post('vesselId');
				$vesselName = $this->input->post('vesselName');
				$vesselClass = $this->input->post('vesselClass');
				$vslType = $this->input->post('vslType');
				$radioCallSign = $this->input->post('radioCallSign');
				$grt = $this->input->post('grt');
				$nrt = $this->input->post('nrt');
				$flag = $this->input->post('flag');
				
				$loa = $this->input->post('loa');
				$imo = $this->input->post('imo');
				$voyNo = $this->input->post('voyNo');
				
				$agentInfo = $this->input->post('agentInfo');
				
				$login_id = $this->session->userdata('login_id');
				$ipaddress = $_SERVER['REMOTE_ADDR'];
				
				// vsl log - start
				$sql_matchVslData = "SELECT id,vsl_id,vsl_name,vsl_class,vsl_type,radio_call_sign,grt,nrt,flag,agent_id
								FROM outer_vsl_info
								WHERE id = '$updateVesselId'";
				$rslt_matchVslData = $this->bm->dataSelectDB1($sql_matchVslData);
				
				if(count($rslt_matchVslData)>0)
				{
					$vsl_tbl_id = $rslt_matchVslData[0]['id'];
					$prevVal = "";
					$currVal = "";
					$columnName = "";
					
					if($rslt_matchVslData[0]['vsl_name'] != $vesselName)
					{
						$prevVal = $rslt_matchVslData[0]['vsl_name'];
						$currVal = $vesselName;
						$columnName = "vsl_name";
						
						$sql_insertVslLog = "INSERT INTO outer_vsl_change_log(vsl_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$vsl_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslLog);
					}	
					if($rslt_matchVslData[0]['vsl_type'] != $vslType)
					{
						$prevVal = $rslt_matchVslData[0]['vsl_type'];
						$currVal = $vslType;
						$columnName = "vsl_type";
						
						$sql_insertVslLog = "INSERT INTO outer_vsl_change_log(vsl_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$vsl_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslLog);
					}
					if($rslt_matchVslData[0]['radio_call_sign'] != $radioCallSign)
					{
						$prevVal = $rslt_matchVslData[0]['radio_call_sign'];
						$currVal = $radioCallSign;
						$columnName = "radio_call_sign";
						
						$sql_insertVslLog = "INSERT INTO outer_vsl_change_log(vsl_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$vsl_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslLog);
					}
					if($rslt_matchVslData[0]['grt'] != $grt)
					{
						$prevVal = $rslt_matchVslData[0]['grt'];
						$currVal = $grt;
						$columnName = "grt";
						
						$sql_insertVslLog = "INSERT INTO outer_vsl_change_log(vsl_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$vsl_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslLog);
					}
					if($rslt_matchVslData[0]['nrt'] != $nrt)
					{
						$prevVal = $rslt_matchVslData[0]['nrt'];
						$currVal = $nrt;
						$columnName = "nrt";
						
						$sql_insertVslLog = "INSERT INTO outer_vsl_change_log(vsl_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$vsl_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslLog);
					}
					if($rslt_matchVslData[0]['flag'] != $flag)
					{
						$prevVal = $rslt_matchVslData[0]['flag'];
						$currVal = $flag;
						$columnName = "flag";
						
						$sql_insertVslLog = "INSERT INTO outer_vsl_change_log(vsl_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$vsl_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslLog);
					}
					if($rslt_matchVslData[0]['loa'] != $loa)
					{
						$prevVal = $rslt_matchVslData[0]['loa'];
						$currVal = $loa;
						$columnName = "loa";
						
						$sql_insertVslLog = "INSERT INTO outer_vsl_change_log(vsl_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$vsl_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslLog);
					}
					if($rslt_matchVslData[0]['imo'] != $imo)
					{
						$prevVal = $rslt_matchVslData[0]['imo'];
						$currVal = $imo;
						$columnName = "imo";
						
						$sql_insertVslLog = "INSERT INTO outer_vsl_change_log(vsl_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$vsl_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslLog);
					}
					if($rslt_matchVslData[0]['voyage_no'] != $voyNo)
					{
						$prevVal = $rslt_matchVslData[0]['voyage_no'];
						$currVal = $voyNo;
						$columnName = "voyage_no";
						
						$sql_insertVslLog = "INSERT INTO outer_vsl_change_log(vsl_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$vsl_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslLog);
					}
					if($rslt_matchVslData[0]['agent_id'] != $agentInfo)
					{
						$prevVal = $rslt_matchVslData[0]['agent_id'];
						$currVal = $agentInfo;
						$columnName = "agent_id";
						
						$sql_insertVslLog = "INSERT INTO outer_vsl_change_log(vsl_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$vsl_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertVslLog);
					}					
				}
				// vsl log - end
				
				$updateQuery="UPDATE outer_vsl_info SET vsl_name='$vesselName', radio_call_sign='$radioCallSign', grt='$grt',
				nrt='$nrt', flag='$flag',loa='$loa',imo='$imo',voyage_no='$voyNo',agent_id='$agentInfo',
				update_at=NOW(),update_by='$login_id',update_ip='$ipaddress' WHERE id='$updateVesselId'";
				$update_st = $this->bm->dataUpdateDB1($updateQuery);
				
				$vslInfoQuery = "SELECT outer_vsl_info.id, outer_vsl_info.vsl_name,outer_vsl_info.vsl_type,outer_vsl_info.radio_call_sign,outer_vsl_info.grt,outer_vsl_info.nrt,outer_vsl_info.flag,outer_vsl_info.loa,outer_vsl_info.imo,outer_vsl_info.voyage_no,outer_agent_info.agent_name,outer_vsl_info.agent_id
				FROM outer_vsl_info
				LEFT JOIN outer_agent_info ON outer_vsl_info.agent_id=outer_agent_info.id
				WHERE outer_vsl_info.delete_flag='0'";
				$vslInfoData = $this->bm->dataSelectDB1($vslInfoQuery);				
				$data['title']="Vessel List";
				$data['vslInfoData']=$vslInfoData;

				$data['msg']="";
				if($update_st==1)
					$data['msg1']="<font size='3' color='green'>Updated Successfully</font>";
				else
					$data['msg1']="<font size='3' color='red'>Failed! Try again.</font>";
				$data['msg2']="";
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('noEntryVesselList',$data);
				$this->load->view('jsAssetsList');
			}
		} 
	}
	
	function saveAgentInfromation()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			if (isset($_POST['insertAgent']))
			{
				$agentInfoData="";
				$insertSt="yes";
				$data['msg2']="";
				$agentCode = $this->input->post('agentCode');
				$agentName = $this->input->post('agentName');
				$aliAsId = $this->input->post('aliAsId');
				$contractName = $this->input->post('contractName');
				$contractAddress = $this->input->post('contractAddress');
				$contractCity = $this->input->post('contractCity');
				$contractEmail = $this->input->post('contractEmail');
				$contractCountry = $this->input->post('contractCountry');
				$contractPhone = $this->input->post('contractPhone');
				$login_id = $this->session->userdata('login_id');
				$ipaddress = $_SERVER['REMOTE_ADDR'];
				$agentQuery="SELECT agent_code FROM outer_agent_info";
				$agentInfoData = $this->bm->dataSelectDB1($agentQuery);
				
				for($i=0; $i<count($agentInfoData);$i++)
				{
					if($agentCode==$agentInfoData[$i]['agent_code'])
					{
						$insertSt="no";
					}
				}

				if($insertSt=="yes")
				{
					$sqlInsert="INSERT INTO outer_agent_info(agent_code,agent_name,alias_id,contact_name,contact_address,contact_city,contact_email,contact_country,contact_phone,entry_at,entry_by,entry_ip)
					VALUES ('$agentCode', '$agentName', '$aliAsId', '$contractName','$contractAddress', '$contractCity',
					'$contractEmail','$contractCountry','$contractPhone',NOW(), '$login_id', '$ipaddress')";
					
					$insertState = $this->bm->dataInsertDB1($sqlInsert);
					if($insertState>0)
					{
						$data['msg2']="<font size='2' color='green'>Agent has been successfully created.</font>";
					}
					else
					{
						$data['msg2']="<font size='2' color='red'>Sorry! try again later.</font>";
					}
				}
				else
				{
					$data['msg2']="<font size='2' color='red'> Agent  was already created.</font>";
				}

				$data['title']="Agent Entry Form";
				$data['msg']="";
				$data['msg1']="";
				$data['agentFlag'] = "";
				$data['vesselFlag'] = "";
				$data['vesselVisitFlag'] = "";
				//echo $data['title'];
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				// $this->load->view('vesselNotEnteringView',$data);
				$this->load->view('vesselsNotEnteringAgentForm',$data);
				$this->load->view('jsAssets');
			}
			else if(isset($_POST['updateAgent']))
			{
				$updateAgentId = $this->input->post('updateAgentId');
				$agentCode = $this->input->post('agentCode');
				$agentName = $this->input->post('agentName');
				$aliAsId = $this->input->post('aliAsId');
				$contractName = $this->input->post('contractName');
				$contractAddress = $this->input->post('contractAddress');
				$contractCity = $this->input->post('contractCity');
				$contractEmail = $this->input->post('contractEmail');
				$contractCountry = $this->input->post('contractCountry');
				$contractPhone = $this->input->post('contractPhone');
				
				$login_id = $this->session->userdata('login_id');
				$ipaddress = $_SERVER['REMOTE_ADDR'];
				
				// agent log - start
				$sql_matchAgentData = "SELECT id,agent_code,agent_name,alias_id,contact_name,contact_address,contact_city,contact_email,contact_country,contact_phone
							FROM outer_agent_info
							WHERE id='$updateAgentId'";
				$rslt_matchAgentData = $this->bm->dataSelectDB1($sql_matchAgentData);
				
				if(count($rslt_matchAgentData)>0)
				{
					$agent_tbl_id = $rslt_matchAgentData[0]['id'];
					$prevVal = "";
					$currVal = "";
					$columnName = "";
					
					if($rslt_matchAgentData[0]['agent_code'] != $agentCode)
					{
						$prevVal = $rslt_matchAgentData[0]['agent_code'];
						$currVal = $agentCode;
						$columnName = "agent_code";
						
						$sql_insertAgentLog = "INSERT INTO outer_agent_change_log(agent_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$agent_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertAgentLog);
					}
					if($rslt_matchAgentData[0]['agent_name'] != $agentName)
					{
						$prevVal = $rslt_matchAgentData[0]['agent_name'];
						$currVal = $agentName;
						$columnName = "agent_name";
						
						$sql_insertAgentLog = "INSERT INTO outer_agent_change_log(agent_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$agent_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertAgentLog);
					}
					if($rslt_matchAgentData[0]['alias_id'] != $aliAsId)
					{
						$prevVal = $rslt_matchAgentData[0]['alias_id'];
						$currVal = $aliAsId;
						$columnName = "alias_id";
						
						$sql_insertAgentLog = "INSERT INTO outer_agent_change_log(agent_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$agent_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertAgentLog);
					}
					if($rslt_matchAgentData[0]['contact_name'] != $contractName)
					{
						$prevVal = $rslt_matchAgentData[0]['contact_name'];
						$currVal = $contractName;
						$columnName = "contact_name";
						
						$sql_insertAgentLog = "INSERT INTO outer_agent_change_log(agent_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$agent_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertAgentLog);
					}
					if($rslt_matchAgentData[0]['contact_address'] != $contractAddress)
					{
						$prevVal = $rslt_matchAgentData[0]['contact_address'];
						$currVal = $contractAddress;
						$columnName = "contact_address";
						
						$sql_insertAgentLog = "INSERT INTO outer_agent_change_log(agent_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$agent_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertAgentLog);
					}
					if($rslt_matchAgentData[0]['contact_city'] != $contractCity)
					{
						$prevVal = $rslt_matchAgentData[0]['contact_city'];
						$currVal = $contractCity;
						$columnName = "contact_city";
						
						$sql_insertAgentLog = "INSERT INTO outer_agent_change_log(agent_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$agent_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertAgentLog);
					}
					if($rslt_matchAgentData[0]['contact_email'] != $contractEmail)
					{
						$prevVal = $rslt_matchAgentData[0]['contact_email'];
						$currVal = $contractEmail;
						$columnName = "contact_email";
						
						$sql_insertAgentLog = "INSERT INTO outer_agent_change_log(agent_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$agent_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertAgentLog);
					}
					if($rslt_matchAgentData[0]['contact_country'] != $contractCountry)
					{
						$prevVal = $rslt_matchAgentData[0]['contact_country'];
						$currVal = $contractCountry;
						$columnName = "contact_country";
						
						$sql_insertAgentLog = "INSERT INTO outer_agent_change_log(agent_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$agent_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertAgentLog);
					}
					if($rslt_matchAgentData[0]['contact_phone'] != $contractPhone)
					{
						$prevVal = $rslt_matchAgentData[0]['contact_phone'];
						$currVal = $contractPhone;
						$columnName = "contact_phone";
						
						$sql_insertAgentLog = "INSERT INTO outer_agent_change_log(agent_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip)
											VALUES('$agent_tbl_id','$columnName','$prevVal','$currVal','$login_id',NOW(),'$ipaddress')";
						$this->bm->dataInsertDB1($sql_insertAgentLog);
					}
				}
				// agent log - end
				
				$updateQuery="UPDATE outer_agent_info SET agent_code='$agentCode', agent_name='$agentName', alias_id='$aliAsId',
				contact_name='$contractName', contact_address='$contractAddress',contact_city='$contractCity',
				contact_email='$contractEmail',contact_country='$contractCountry',contact_phone='$contractPhone',update_at=NOW(),update_by='$login_id',update_ip='$ipaddress' WHERE id='$updateAgentId'";
				$update_st = $this->bm->dataUpdateDB1($updateQuery);

				

				$agentInfoQuery = "SELECT * FROM outer_agent_info WHERE delete_flag='0' ";
				$agentInfoData = $this->bm->dataSelectDB1($agentInfoQuery);
				
				$data['title']="Agent List";
				$data['agentInfoData']=$agentInfoData;
			
				if($update_st == 1)
					$data['msg']="<font size='3' color='green'>Updated Successfully</font>";
				else
					$data['msg']="<font size='3' color='red'>Failed! Try again.</font>";
				$data['msg1']="";
				$data['msg2']="";
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('noEntryAgentList',$data);
				$this->load->view('jsAssetsList');
			}
	    } 
	}
	
	function outerVesselVistInfoList()
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
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$cond = "";
			if($org_Type_id=='83' && $login_id != "agent1" ){
				$cond = " AND agent_entry_approve_flag ='0' ";
			}
			else
			{
				$cond = " AND agent_entry_flag='1'";
			}
			// $vslVisitInfoQuery = "SELECT * FROM outer_vsl_visit_info  WHERE delete_flag='0'";
			$vslVisitInfoQuery = "SELECT id,vsl_name,imp_rot,date_of_arrival,time_of_arrival,date_of_departure,time_of_departure,voyage_no,remarks,agent_entry_flag,
			agent_entry_approve_flag,entry_by
			FROM outer_vsl_visit_info
			WHERE delete_flag='0'".$cond;
			$vslVisitInfoData = $this->bm->dataSelectDB1($vslVisitInfoQuery);
			
			$data['title']="Vessel Visit List (Pending Approval)";
			$data['vslVisitInfoData']=$vslVisitInfoData;

			$data['msg']="";
			$data['msg1']="";
			$data['msg2']="";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('outerVesselVistInfoListView',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function noEntryAgentList(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			if($LoginStat!="yes"){
				$this->logout();
			}
			else {
				
				$agentInfoQuery = "SELECT * FROM outer_agent_info WHERE delete_flag='0'";
				$agentInfoData = $this->bm->dataSelectDB1($agentInfoQuery);
				
				$vslInfoQuery = "SELECT outer_vsl_info.id, outer_vsl_info.vsl_name,outer_vsl_info.radio_call_sign,outer_vsl_info.grt,outer_vsl_info.nrt,outer_vsl_info.flag,outer_agent_info.agent_name,outer_vsl_info.agent_id
				FROM outer_vsl_info
				LEFT JOIN outer_agent_info ON outer_vsl_info.agent_id=outer_agent_info.id
				WHERE outer_vsl_info.delete_flag='0'";
				$vslInfoData = $this->bm->dataSelectDB1($vslInfoQuery);
				
				$vslVisitInfoQuery = "SELECT * FROM outer_vsl_visit_info  WHERE delete_flag='0' ";
				$vslVisitInfoData = $this->bm->dataSelectDB1($vslVisitInfoQuery);
				
				$data['title']="Agent List";
				$data['agentInfoData']=$agentInfoData;
				$data['vslInfoData']=$vslInfoData;
				$data['vslVisitInfoData']=$vslVisitInfoData;

				$data['msg']="";
				$data['msg1']="";
			    $data['msg2']="";
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('noEntryAgentList',$data);
				$this->load->view('jsAssetsList');


			}

	}
	
	function noEntryVesselList()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else 
		{			
			$agentInfoQuery = "SELECT * FROM outer_agent_info WHERE delete_flag='0' ";
			$agentInfoData = $this->bm->dataSelectDB1($agentInfoQuery);
			
			$vslInfoQuery = "SELECT outer_vsl_info.id, outer_vsl_info.vsl_name,outer_vsl_info.vsl_type,outer_vsl_info.radio_call_sign,outer_vsl_info.grt,outer_vsl_info.nrt,outer_vsl_info.flag,outer_agent_info.agent_name,outer_vsl_info.agent_id
			FROM outer_vsl_info
			LEFT JOIN outer_agent_info ON outer_vsl_info.agent_id=outer_agent_info.id
			WHERE outer_vsl_info.delete_flag='0'";
			// echo $vslInfoQuery;return;
			$vslInfoData = $this->bm->dataSelectDB1($vslInfoQuery);
			
			$vslVisitInfoQuery = "SELECT * FROM outer_vsl_visit_info  WHERE delete_flag='0' ";
			$vslVisitInfoData = $this->bm->dataSelectDB1($vslVisitInfoQuery);
			
			$data['title']="Vessel List";
			$data['agentInfoData']=$agentInfoData;
			$data['vslInfoData']=$vslInfoData;
			$data['vslVisitInfoData']=$vslVisitInfoData;

			$data['msg']="";
			$data['msg1']="";
			$data['msg2']="";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('noEntryVesselList',$data);
			$this->load->view('jsAssetsList');


		}

	}
	
	function searchOnOuterVisitedVesselList(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			if($LoginStat!="yes"){
				$this->logout();
			}
			else{
			
				$searchType = $this->input->post("searchType");
				$searchValue=trim($this->input->post('searchValue'));
				$formDate = $this->input->post("formDate");
				$toDate = $this->input->post("toDate");
				
				$login_id = $this->session->userdata('login_id');
				$org_Type_id =$this->session->userdata('org_Type_id');
			
				$cond = "";
				if($org_Type_id=='83' && $login_id != "agent1" ){
					$cond = " AND agent_entry_approve_flag ='0' ";
				}
				else
				{
					$cond = " AND agent_entry_flag='1'";
				}
				
				if($searchType=="date_of_arrival" || $searchType=="date_of_departure" || $searchType=="entry_at"){

					$query="SELECT * FROM outer_vsl_visit_info  WHERE  delete_flag='0' AND  $searchType BETWEEN DATE('$formDate') AND DATE('$toDate')".$cond;

				}
				else{
					$query="SELECT * FROM outer_vsl_visit_info  WHERE  delete_flag='0' AND  $searchType ='$searchValue'".$cond;
				}
			
				$vslVisitInfoData = $this->bm->dataSelectDB1($query);
				
				$data['title']="Vessel Visit List (Pending Approval)";
				
				$data['vslVisitInfoData']=$vslVisitInfoData;
				
				$data['msg']="";
				$data['msg1']="";
			    $data['msg2']="";
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('outerVesselVistInfoListView',$data);
				$this->load->view('jsAssets');

			}

	}
	
	function searchOnAgentList(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			if($LoginStat!="yes"){
				$this->logout();
			}
			else{
				
			
				$agentSearch=trim($this->input->post('agentSearch'));
				$agentInfoQuery = "SELECT * FROM outer_agent_info WHERE delete_flag='0'  AND  agent_name ='$agentSearch' ";
				$agentInfoData = $this->bm->dataSelectDB1($agentInfoQuery);
				$data['title']="Agent List";
				$data['agentInfoData']=$agentInfoData;
				$data['msg']="";
				$data['msg1']="";
			    $data['msg2']="";
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('noEntryAgentList',$data);
				$this->load->view('jsAssetsList');

			}

	}
	
	function searchOnVesselList(){

		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			if($LoginStat!="yes"){
				$this->logout();
			}
			else{
				
				
				$vesselSearch=trim($this->input->post('vesselSearch'));
				$vslInfoQuery = "SELECT outer_vsl_info.id,outer_vsl_info.vsl_name,outer_vsl_info.radio_call_sign,outer_vsl_info.grt,outer_vsl_info.nrt,outer_vsl_info.flag,outer_agent_info.agent_name,outer_vsl_info.agent_id
				FROM outer_vsl_info
				INNER JOIN outer_agent_info ON outer_vsl_info.agent_id=outer_agent_info.id
				WHERE outer_vsl_info.delete_flag='0' AND vsl_name='$vesselSearch'";
				$vslInfoData = $this->bm->dataSelectDB1($vslInfoQuery);
				$data['title']="Vessel List";
			    $data['vslInfoData']=$vslInfoData;
				$data['msg']="";
				$data['msg1']="";
			    $data['msg2']="";
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('noEntryVesselList',$data);
				$this->load->view('jsAssetsList');

			}

	}
	
	function editAgentInfo(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			if($LoginStat!="yes"){
				$this->logout();
			}
			else {

				$agentFlag="edit";
				$editAgent=$this->input->post('editAgent');
                $selectQuery="SELECT * FROM outer_agent_info  WHERE id='$editAgent'";
				$agentInfo = $this->bm->dataSelectDB1($selectQuery);
				$data['title']="Agent Entry Form";
				$data['msg'] = "";
				$data['msg1'] = "";
				$data['msg2'] = "";
				$data['agentFlag'] =$agentFlag ;
				$data['vesselFlag'] = "";
				$data['vesselVisitFlag'] = "";
				$data['agentInfo'] =$agentInfo;
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('vesselsNotEnteringAgentForm',$data);
				$this->load->view('jsAssets');
				


			}

	}
	
	function deleteAgentInfo(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			if($LoginStat!="yes"){
				$this->logout();
			}
			else {
				$login_id = $this->session->userdata('login_id');
                 $deleteId=$this->input->post('deleteId');
			  
				$ipaddress = $_SERVER['REMOTE_ADDR'];
				 //$DeleteQuery = "DELETE FROM outer_anchorage_vsl WHERE id='$deleteId'";
				// $this->bm->dataDeleteDB1($DeleteQuery);
				$updateQuery="UPDATE outer_agent_info SET delete_flag='1', delete_at=NOW(), delete_by='$login_id',
									delete_ip='$ipaddress' WHERE id='$deleteId'";
						$update_st = $this->bm->dataUpdateDB1($updateQuery);
						
			$agentInfoQuery = "SELECT * FROM outer_agent_info WHERE delete_flag='0' ";
			$agentInfoData = $this->bm->dataSelectDB1($agentInfoQuery);
			$data['title']="Agent List";
			$data['agentInfoData']=$agentInfoData;
			$data['msg']="<font size='3' color='red'>Deleted Successfully</font>";
			$data['msg1']="";
			$data['msg2']="";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('noEntryAgentList',$data);
			$this->load->view('jsAssetsList');

						
				 
            }

	}
	
	function editVesselInfo()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else 
		{
			$vesselFlag="edit";
			$editVessel=$this->input->post('editVessel');
			$vslInfoQuery = "SELECT outer_vsl_info.id,outer_vsl_info.vsl_name,outer_vsl_info.vsl_type,outer_vsl_info.radio_call_sign,outer_vsl_info.grt,outer_vsl_info.nrt,outer_vsl_info.flag,outer_vsl_info.loa,outer_vsl_info.imo,outer_vsl_info.voyage_no,outer_agent_info.agent_name,outer_vsl_info.agent_id
			FROM outer_vsl_info
			LEFT JOIN outer_agent_info ON outer_vsl_info.agent_id=outer_agent_info.id
			WHERE outer_vsl_info.delete_flag='0' AND outer_vsl_info.id='$editVessel'";
			// echo $vslInfoQuery;return;
			$vslInfo = $this->bm->dataSelectDB1($vslInfoQuery);
			
			$sql_vslType = "SELECT vsl_type FROM outer_vsl_type";
			$rslt_vslType = $this->bm->dataSelectDB1($sql_vslType);
			$data['rslt_vslType'] = $rslt_vslType;
			
			$data['title']="Vessel Entry Form";
			$data['msg'] = "";
			$data['msg1'] = "";
			$data['msg2'] = "";
			$data['agentFlag'] = "";
			$data['vesselFlag'] = $vesselFlag;
			$data['vesselVisitFlag'] = "";
			$data['vslInfo'] =$vslInfo;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselsNotEnteringForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function deleteVesselInfo(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			if($LoginStat!="yes"){
				$this->logout();
			}
			else {
				$login_id = $this->session->userdata('login_id');
                 $deleteId=$this->input->post('deleteId');
			  
				$ipaddress = $_SERVER['REMOTE_ADDR'];
				 //$DeleteQuery = "DELETE FROM outer_anchorage_vsl WHERE id='$deleteId'";
				// $this->bm->dataDeleteDB1($DeleteQuery);
				$updateQuery="UPDATE outer_vsl_info SET delete_flag='1', delete_at=NOW(), delete_by='$login_id',
									delete_ip='$ipaddress' WHERE id='$deleteId'";
						$update_st = $this->bm->dataUpdateDB1($updateQuery);
						
			
				
			$vslInfoQuery = "SELECT outer_vsl_info.id, outer_vsl_info.vsl_name,outer_vsl_info.radio_call_sign,outer_vsl_info.grt,outer_vsl_info.nrt,outer_vsl_info.flag,outer_agent_info.agent_name,outer_vsl_info.agent_id
			FROM outer_vsl_info
			INNER JOIN outer_agent_info ON outer_vsl_info.agent_id=outer_agent_info.id
			WHERE outer_vsl_info.delete_flag='0'";
			$vslInfoData = $this->bm->dataSelectDB1($vslInfoQuery);
			
			$data['title']="Vessel List";
			$data['vslInfoData']=$vslInfoData;
			$data['msg']="";
			$data['msg1']="<font size='3' color='red'>Deleted Successfully</font>";
			$data['msg2']="";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('noEntryVesselList',$data);
			$this->load->view('jsAssetsList');

						
				 
            }

	}
	
	function editVisitedVesselList()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else 
		{
			$vesselVisitFlag="edit";
			$veseelVisitId=$this->input->post('veseelVisitId');
			// $vslVisitInfoQuery = "SELECT * FROM outer_vsl_visit_info  WHERE delete_flag='0' AND id='$veseelVisitId'";
			// $vslVisitInfoQuery = "SELECT * FROM outer_vsl_visit_info
								// INNER JOIN outer_vsl_info ON outer_vsl_info.id = outer_vsl_visit_info.outer_vsl_id
								// INNER JOIN outer_agent_info ON outer_agent_info.id = outer_vsl_info.agent_id
								// WHERE outer_vsl_visit_info.delete_flag='0' AND outer_vsl_visit_info.id='$veseelVisitId'";
								
			$vslVisitInfoQuery = "SELECT outer_vsl_visit_info.id,outer_vsl_visit_info.vsl_name,outer_vsl_info.vsl_type,imp_rot,grt,nrt,date_of_arrival,
			time_of_arrival,
			SUBSTRING(time_of_arrival, 1, 2) AS ataHH,
			SUBSTRING(time_of_arrival, 4, 2) AS ataMM,
			flag,agent_name,date_of_departure,
			time_of_departure,
			SUBSTRING(time_of_departure, 1, 2) AS atdHH,
			SUBSTRING(time_of_departure, 4, 2) AS atdMM,
			outer_vsl_visit_info.voyage_no,
			remarks
			FROM outer_vsl_visit_info
			INNER JOIN outer_vsl_info ON outer_vsl_info.id = outer_vsl_visit_info.outer_vsl_id
			INNER JOIN outer_agent_info ON outer_agent_info.id = outer_vsl_info.agent_id
			WHERE outer_vsl_visit_info.delete_flag='0' AND outer_vsl_visit_info.id='$veseelVisitId'";
			$vslVisitInfo = $this->bm->dataSelectDB1($vslVisitInfoQuery);
			$data['title']="Vessel Visit Entry Form";
			$data['msg'] = "";
			$data['msg1'] = "";
			$data['msg2'] = "";
			$data['agentFlag'] = "";
			$data['vesselFlag'] ="";
			$data['vesselVisitFlag'] = $vesselVisitFlag;
			$data['vslVisitInfo'] =$vslVisitInfo;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('visitedvesselsNotEnteringForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function deleteVisitedVesselInfo(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			if($LoginStat!="yes"){
				$this->logout();
			}
			else {
				$login_id = $this->session->userdata('login_id');
                 $deleteId=$this->input->post('deleteId');
			  
				$ipaddress = $_SERVER['REMOTE_ADDR'];
				 //$DeleteQuery = "DELETE FROM outer_anchorage_vsl WHERE id='$deleteId'";
				// $this->bm->dataDeleteDB1($DeleteQuery);
				$updateQuery="UPDATE outer_vsl_visit_info SET delete_flag='1', delete_at=NOW(), delete_by='$login_id',
									delete_ip='$ipaddress' WHERE id='$deleteId'";
						$update_st = $this->bm->dataUpdateDB1($updateQuery);
						
			
			$vslVisitInfoQuery = "SELECT * FROM outer_vsl_visit_info  WHERE delete_flag='0' AND agent_entry_approve_flag ='0' ";
			$vslVisitInfoData = $this->bm->dataSelectDB1($vslVisitInfoQuery);
			
			$data['title']="Vessel Visit List";
			$data['vslVisitInfoData']=$vslVisitInfoData;
			$data['msg']="";
			$data['msg1']="";
			$data['msg2']="<font size='3' color='red'>Deleted Successfully</font>";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('outerVesselVistInfoListView',$data);
			$this->load->view('jsAssets');

						
				 
            }

	}
	// vsl function - ovi - end
	
	// start outer anchorage vessel report
	
	function outerAnchorageVslReportForm(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			if($LoginStat!="yes"){
				$this->logout();
			}
			else{
				$data['msg']="";
				$data['title']="All Incoming & Departure Vessel in CPA";
				$sql_vslType = "SELECT vsl_type FROM outer_vsl_type";
				$rslt_vslType = $this->bm->dataSelectDB1($sql_vslType);
				$data['rslt_vslType'] = $rslt_vslType;
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('outerAnchorageVslReportFormView',$data);
				$this->load->view('jsAssets');	

			}

	}
	
	function outerAnchorageVslReport()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$date="";
			$query="";
			$searchType = $this->input->post("searchType");
			$vslType = $this->input->post("vslType");
			$rptType=$this->input->post('fileOptions');
			$formDate = $this->input->post("formDate");
			$toDate = $this->input->post("toDate");
			
			$str=" ";
			
			$queryList = null;
			
			$contArrival = "SELECT sparcsn4.vsl_vessel_visit_details.ib_vyg AS imp_rot,sparcsn4.vsl_vessels.name AS vsl_name,
			DATE(sparcsn4.argo_carrier_visit.ata) AS date_of_arrival,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_arrival,
			DATE(sparcsn4.argo_carrier_visit.atd) AS date_of_departure,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_departure,
			sparcsn4.ref_bizunit_scoped.name AS beaching_agent,sparcsn4.ref_country.cntry_name AS flag,
			sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
			'' AS remarks,'Container Vessel' AS vsl_type
			FROM sparcsn4.argo_carrier_visit
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
			INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
			INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessels.owner_gkey
			INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
			WHERE sparcsn4.argo_carrier_visit.ata BETWEEN '$formDate' AND '$toDate'";
			
			$contDeparture = "SELECT sparcsn4.vsl_vessel_visit_details.ib_vyg AS imp_rot,sparcsn4.vsl_vessels.name AS vsl_name,
			DATE(sparcsn4.argo_carrier_visit.ata) AS date_of_arrival,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_arrival,
			DATE(sparcsn4.argo_carrier_visit.atd) AS date_of_departure,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_departure,
			sparcsn4.ref_bizunit_scoped.name AS beaching_agent,sparcsn4.ref_country.cntry_name AS flag,
			sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
			'' AS remarks,'Container Vessel' AS vsl_type
			FROM sparcsn4.argo_carrier_visit
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
			INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
			INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessels.owner_gkey
			INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
			WHERE sparcsn4.argo_carrier_visit.atd BETWEEN '$formDate' AND '$toDate'";
			
			$contAll = "SELECT sparcsn4.vsl_vessel_visit_details.ib_vyg AS imp_rot,sparcsn4.vsl_vessels.name AS vsl_name,
			DATE(sparcsn4.argo_carrier_visit.ata) AS date_of_arrival,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_arrival,
			DATE(sparcsn4.argo_carrier_visit.atd) AS date_of_departure,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_departure,
			sparcsn4.ref_bizunit_scoped.name AS beaching_agent,sparcsn4.ref_country.cntry_name AS flag,
			sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
			'' AS remarks,'Container Vessel' AS vsl_type
			FROM sparcsn4.argo_carrier_visit
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
			INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
			INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessels.owner_gkey
			INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
			WHERE (sparcsn4.argo_carrier_visit.ata BETWEEN '$formDate' AND '$toDate'
			OR sparcsn4.argo_carrier_visit.atd BETWEEN '$formDate' AND '$toDate')";
			
			if($vslType=="Container Vessel")
			{
				if($searchType=="arrival")
				{
					$query = $contArrival;
				}
				else if($searchType=="departure")
				{
					$query = $contDeparture;
				}
				else
				{
					$query = $contAll;
				}
				
				$queryList =$this->bm->dataSelect($query);
			}
			else
			{			
				if($vslType=="ALL")
				{
					$str=" ";
					if($searchType=="arrival")
					{
						$query = $contArrival;
					}
					else if($searchType=="departure")
					{
						$query = $contDeparture;
					}
					else
					{
						$query = $contAll;
					}
					// echo $query;return;
					$rslt_contVsl = $this->bm->dataSelect($query);
					$data['rslt_contVsl'] = $rslt_contVsl;
					$this->data['rslt_contVsl'] = $rslt_contVsl;
				}
				else			
				{
					$str=" AND outer_vsl_info.vsl_type='$vslType'";
				}

				if($searchType=="arrival")
				{
					$query="SELECT imp_rot,outer_vsl_info.vsl_name,date_of_arrival,time_of_arrival,date_of_departure,time_of_departure,outer_agent_info.agent_name AS beaching_agent,flag,
					grt,nrt,remarks, vsl_type
					FROM outer_vsl_visit_info
					INNER JOIN outer_vsl_info ON outer_vsl_info.id = outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id = outer_vsl_info.agent_id
					WHERE outer_vsl_visit_info.delete_flag='0' AND date_of_arrival BETWEEN '$formDate' AND '$toDate' $str ORDER BY vsl_type";
				}
				else if($searchType=="departure")
				{	
					$query="SELECT imp_rot,outer_vsl_info.vsl_name,date_of_arrival,time_of_arrival,date_of_departure,time_of_departure,outer_agent_info.agent_name AS beaching_agent,
					flag,grt,nrt,remarks, vsl_type
					FROM outer_vsl_visit_info
					INNER JOIN outer_vsl_info ON outer_vsl_info.id = outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id = outer_vsl_info.agent_id
					WHERE outer_vsl_visit_info.delete_flag='0' AND date_of_departure BETWEEN '$formDate' AND '$toDate' $str ORDER BY vsl_type ";
				}
				else
				{
					$query="SELECT imp_rot,outer_vsl_info.vsl_name,date_of_arrival,time_of_arrival,date_of_departure,time_of_departure,outer_agent_info.agent_name AS beaching_agent,
					flag,grt,nrt,remarks,vsl_type
					FROM outer_vsl_visit_info
					INNER JOIN outer_vsl_info ON outer_vsl_info.id = outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id = outer_vsl_info.agent_id
					WHERE outer_vsl_visit_info.delete_flag='0' 
					AND ( date_of_arrival BETWEEN '$formDate' AND '$toDate' 
					OR date_of_departure BETWEEN '$formDate' AND '$toDate' )
					$str  ORDER BY  vsl_type";
				}
				// echo $query;return;
				$queryList =$this->bm->dataSelectDB1($query);
			}
			// echo $query;return;
			
			// $queryList =$this->bm->dataSelectDB1($query);
			if($rptType=="html" || $rptType=="xl")
			{
				$data['queryList']= $queryList;
				$data['formDate']=	$formDate;
				$data['toDate']=	$toDate;
				$data['vslType']= $vslType;
				$data['searchType']= $searchType;
				if($searchType=='arrival')
				{
					$data['title']="Incoming Vessel in CPA";
				}
				else if($searchType=='departure')
				{
					$data['title']="Departure Vessel in CPA";
				}
				else
				{
					$data['title']="All Incoming & Departure Vessel in CPA";
				}
				
				$this->load->view('outerAnchorageVslReportView',$data);
			}
			else if($rptType == "pdf")
			{
				$this->data['queryList']=$queryList;
				$this->data['formDate']=$formDate;
				$this->data['toDate']=$toDate;
				$this->data['vslType']= $vslType;
				$this->data['searchType']= $searchType;
				if($searchType=='arrival')
				{
					$this->data['title']="Incoming Vessel in CPA";
				}
				else if($searchType=='departure')
				{
					$this->data['title']="Departure Vessel in CPA";
				}
				else
				{
					$this->data['title']="All Incoming & Departure Vessel in CPA";
				}												
				$this->load->library('m_pdf');
				
				$html=$this->load->view('outerAnchorageVslReportViewPDF',$this->data, true); 

				$pdfFilePath ="vslNotEntering-".time()."-download.pdf";

				$pdf = $this->m_pdf->load();
				$pdf->SetWatermarkText('CPA CTMS');
				$pdf->showWatermarkText = false;
				//	$stylesheet = file_get_contents('resources/styles/test.css'); // external css
				$stylesheet = file_get_contents('resources/styles/cartticket.css'); // external css
				//	$pdf->useSubstitutions = true; // optional - just as an example

				//$pdf->setFooter('Developed By : '.$login_id.'|Page {PAGENO}|Date {DATE j-m-Y}');

				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);

				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			}
		}
	}
	
	/*
	function outerAnchorageVslReportQuery($vslType,$searchType,$formDate,$toDate)
	{
		if($vslType=="Container Vessel")
		{
			if($searchType=="arrival")
			{
				return "SELECT sparcsn4.vsl_vessel_visit_details.ib_vyg AS imp_rot,sparcsn4.vsl_vessels.name AS vsl_name,
				DATE(sparcsn4.argo_carrier_visit.ata) AS date_of_arrival,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_arrival,
				DATE(sparcsn4.argo_carrier_visit.atd) AS date_of_departure,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_departure,
				sparcsn4.ref_bizunit_scoped.name AS beaching_agent,sparcsn4.ref_country.cntry_name AS flag,
				sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
				'' AS remarks,'Container Vessel' AS vsl_type
				FROM sparcsn4.argo_carrier_visit
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
				INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessels.owner_gkey
				INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
				WHERE sparcsn4.argo_carrier_visit.ata BETWEEN '$formDate' AND '$toDate'";
			}
			else if($searchType=="departure")
			{
				return "SELECT sparcsn4.vsl_vessel_visit_details.ib_vyg AS imp_rot,sparcsn4.vsl_vessels.name AS vsl_name,
				DATE(sparcsn4.argo_carrier_visit.ata) AS date_of_arrival,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_arrival,
				DATE(sparcsn4.argo_carrier_visit.atd) AS date_of_departure,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_departure,
				sparcsn4.ref_bizunit_scoped.name AS beaching_agent,sparcsn4.ref_country.cntry_name AS flag,
				sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
				'' AS remarks,'Container Vessel' AS vsl_type
				FROM sparcsn4.argo_carrier_visit
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
				INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessels.owner_gkey
				INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
				WHERE sparcsn4.argo_carrier_visit.atd BETWEEN '$formDate' AND '$toDate'";
			}
			else
			{
				return "SELECT sparcsn4.vsl_vessel_visit_details.ib_vyg AS imp_rot,sparcsn4.vsl_vessels.name AS vsl_name,
				DATE(sparcsn4.argo_carrier_visit.ata) AS date_of_arrival,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_arrival,
				DATE(sparcsn4.argo_carrier_visit.atd) AS date_of_departure,TIME(sparcsn4.argo_carrier_visit.ata) AS time_of_departure,
				sparcsn4.ref_bizunit_scoped.name AS beaching_agent,sparcsn4.ref_country.cntry_name AS flag,
				sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt,
				'' AS remarks,'Container Vessel' AS vsl_type
				FROM sparcsn4.argo_carrier_visit
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
				INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessels.owner_gkey
				INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
				WHERE (sparcsn4.argo_carrier_visit.ata BETWEEN '$formDate' AND '$toDate'
				OR sparcsn4.argo_carrier_visit.atd BETWEEN '$formDate' AND '$toDate')";
			}
		}
	}
	*/
	
	// finish outer anchorage vessel report
	
	// approve vessel visit by marine - start
	function approveVslVisit()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$apprvId = $this->input->post('apprvId');
			
			$sql_approveByMarine = "UPDATE outer_vsl_visit_info
								SET agent_entry_approve_flag = '1'
								WHERE id='$apprvId'";
			$this->bm->dataUpdateDB1($sql_approveByMarine);
			
			$this->outerVesselVistInfoList();
		}
	}
	// approve vessel visit by marine - end
	
	// forwarded vsl history - start
	function forwardedVslHistoryN4()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			// use view of vesselForwardingbyMarineForm
			// make section on org_Type_id
			// write query for forwarded list
			
			$org_Type_id =$this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');
			$login_id = $this->session->userdata('login_id');
			
			if($org_Type_id=='83')
			{
				$data['title']="Forwarded By Marine - History";
				
				$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata, DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain, sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt, sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd, sparcsn4.vsl_vessel_visit_details.ib_vyg,
				ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM sparcsn4.argo_carrier_visit
				INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
				INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
				INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
				INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
				INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
				WHERE ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.marine_forward_by='$login_id'
				ORDER BY ctmsmis.vsl_forward_info.marine_forward_at ASC";
			}
			else if($org_Type_id=='81')
			{
				$data['title']="Forwarded By Master - History";

				$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata, DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain, sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt, sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd, sparcsn4.vsl_vessel_visit_details.ib_vyg,ctmsmis.vsl_forward_info.master_forward_at AS forwarded_dt
				FROM sparcsn4.argo_carrier_visit
				INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
				INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
				INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
				INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
				INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
				WHERE ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_by='$login_id'
				ORDER BY ctmsmis.vsl_forward_info.master_forward_at ASC";
			}
			else if($org_Type_id=='82')
			{
				if($section=='acc')
				{
					$data['title']="Forwarded By Sr. Accountant - History";

					$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata, DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain, sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt, sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd, sparcsn4.vsl_vessel_visit_details.ib_vyg,ctmsmis.vsl_forward_info.sr_acnt_forward_at AS forwarded_dt
					FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
					INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
					INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
					INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
					INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
					WHERE ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_by='$login_id'
					ORDER BY ctmsmis.vsl_forward_info.sr_acnt_forward_at ASC";
				}
				else if ($section=='billop')
				{
					$data['title']="Forwarded By Bill Operator - History";

					$departQuery = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,DATE(sparcsn4.argo_carrier_visit.ata) AS ata, DATE(sparcsn4.argo_carrier_visit.atd) AS atd, sparcsn4.vsl_vessels.name AS vsl_name,sparcsn4.vsl_vessels.ves_captain, sparcsn4.vsl_vessel_classes.loa_cm,sparcsn4.vsl_vessel_classes.gross_registered_ton AS grt,sparcsn4.vsl_vessel_classes.net_registered_ton AS nrt, sparcsn4.ref_bizunit_scoped.name,sparcsn4.ref_country.cntry_name,ata,atd, sparcsn4.vsl_vessel_visit_details.ib_vyg,ctmsmis.vsl_forward_info.billop_bill_at AS forwarded_dt
					FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
					INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
					INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
					INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
					INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
					WHERE ctmsmis.vsl_forward_info.billop_bill_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_by='$login_id'
					ORDER BY ctmsmis.vsl_forward_info.billop_bill_at ASC";
				}
			}
			// echo $departQuery;return;
			$departData = $this->bm->dataSelect($departQuery);

			$data['departData']=$departData;
			// $data['masterFlag']=$masterFlag;
			// $data['fromDate']=$fromDate;
			// $data['toDate']=$toDate;
			$data['msg']="";
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			// $this->load->view('vesselForwardingbyMarineForm',$data);			
			$this->load->view('vesselForwardList_history',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	
	function forwardedVslHistoryNotEntering()
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
			$section = $this->session->userdata('section');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			$org_Type_id =$this->session->userdata('org_Type_id');
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Forwarded By Marine (Not Entering) - History";
				
				$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
				FROM outer_vsl_forward_info
				INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
				INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				WHERE marine_forward_stat='1' AND marine_forward_by='$login_id'";								
			}
			else if($org_Type_id=='81') //Master			
			{
				$data['title']="Forwarded By Master (Not Entering) - History";
				
				$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,master_forward_at
				FROM outer_vsl_forward_info
				INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
				INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				WHERE master_forward_stat='1' AND master_forward_by='$login_id'";
			}
			else if($org_Type_id=='82') // Accounts
			{
				// if($login_id=='sr_acc') //Sr. Accountant
				if($section=='acc') // Accountant - by Intakhab - 2022-08-28
				{					
					$data['title']="Forwarded By Sr. Accountant (Not Entering) - History";
					
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
					FROM outer_vsl_forward_info
					INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					WHERE sr_acnt_forward_stat='1' AND sr_acnt_forward_by='$login_id'";
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28
				{
					$data['title']="Bill Generated By Accountant (Not Entering) - History";
					
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
					FROM outer_vsl_forward_info
					INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					WHERE billop_bill_stat='1' AND billop_bill_by='$login_id'";
				}					
			}
			// echo $departQuery;return;
			$departData = $this->bm->dataSelectDB1($departQuery);

			$data['departData']=$departData;
			// $data['fromDate']=$fromDate;
			// $data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['msg']="";
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');			
			$this->load->view('vesselForwardList_notEntering_history',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	// forwarded vsl history - end
	
	function outerVesselVistInfoApprovedList()
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
			$org_Type_id =$this->session->userdata('org_Type_id');

			
			$cond = "";
			if($org_Type_id=='83' && $login_id != "agent1" ){
				$cond = " AND agent_entry_approve_flag ='1' ";
			}
			else
			{
				$cond = " AND agent_entry_flag='1' ";
			}
			
			// $vslVisitInfoQuery = "SELECT * FROM outer_vsl_visit_info  WHERE delete_flag='0'";
			$vslVisitInfoQuery = "SELECT id,vsl_name,imp_rot,date_of_arrival,time_of_arrival,date_of_departure,time_of_departure,voyage_no,remarks,agent_entry_flag,
			agent_entry_approve_flag,entry_by
			FROM outer_vsl_visit_info
			WHERE delete_flag='0'".$cond;
			
			//return;
			$vslVisitInfoData = $this->bm->dataSelectDB1($vslVisitInfoQuery);
			
			$data['title']="Vessel Visit List (Approved)";
			$data['vslVisitInfoData']=$vslVisitInfoData;

			$data['msg']="";
			$data['msg1']="";
			$data['msg2']="";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('outerVesselVistInfoListApprovedView',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function searchOnOuterVisitedVesselApprovedList(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			if($LoginStat!="yes"){
				$this->logout();
			}
			else{
			
				$searchType = $this->input->post("searchType");
				$searchValue=trim($this->input->post('searchValue'));
				$formDate = $this->input->post("formDate");
				$toDate = $this->input->post("toDate");
				
				$login_id = $this->session->userdata('login_id');
				$org_Type_id =$this->session->userdata('org_Type_id');
			
				$cond = "";
				if($org_Type_id=='83' && $login_id != "agent1" ){
					$cond = " AND agent_entry_approve_flag ='1' ";
				}
				else
				{
					$cond = " AND agent_entry_flag='1'";
				}
				
				if($searchType=="date_of_arrival" || $searchType=="date_of_departure" || $searchType=="entry_at"){

					$query="SELECT * FROM outer_vsl_visit_info  WHERE  delete_flag='0' AND  $searchType BETWEEN DATE('$formDate') AND DATE('$toDate')".$cond;

				}
				else{
					$query="SELECT * FROM outer_vsl_visit_info  WHERE  delete_flag='0' AND  $searchType ='$searchValue'".$cond;
				}
			
				$vslVisitInfoData = $this->bm->dataSelectDB1($query);
				
				$data['title']="Vessel Visit List (Approved)";
				
				$data['vslVisitInfoData']=$vslVisitInfoData;
				
				$data['msg']="";
				$data['msg1']="";
			    $data['msg2']="";
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('outerVesselVistInfoListApprovedView',$data);
				$this->load->view('jsAssets');

			}

	}
	
	// marine vessel lot - start
		
	function marineVslLot_NotEntering()		// Not entering vessel - marine1
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Marine Vessel Lot (Not Entering)";
			
			$login_id = $this->session->userdata('login_id');
			$section = $this->session->userdata('section');
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			
			$sql_marineVslLot_NE = "SELECT id,lot_dt,lot_id,tot_vsl,forward_at,forward_by,vsl_lot_type
								FROM vsl_forward_lot_info
								ORDER BY forward_at DESC";
			$rslt_marineVslLot_NE = $this->bm->dataSelectDB1($sql_marineVslLot_NE);
			
			$data['title'] = $title;
			$data['rslt_marineVslLot_NE'] = $rslt_marineVslLot_NE;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('marineVslLot_NotEntering',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function marineVslLot_NotEntering_List()		// Not entering vessel - marine1
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Marine Vessel Lot (Not Entering) - Vessel List";
			
			$login_id = $this->session->userdata('login_id');
			$section = $this->session->userdata('section');
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			
			$sql_marineVslLot_NE_List = "SELECT outer_vsl_info.vsl_name,outer_vsl_info.vsl_class,outer_vsl_info.vsl_type
			FROM outer_vsl_forward_info
			INNER JOIN outer_vsl_info ON outer_vsl_info.id = outer_vsl_forward_info.vsl_visit_id
			INNER JOIN vsl_forward_lot_info ON vsl_forward_lot_info.id = outer_vsl_forward_info.vsl_fwd_lot_info_id";
			$rslt_marineVslLot_NE_List = $this->bm->dataSelectDB1($sql_marineVslLot_NE_List);
			
			$data['title'] = $title;
			$data['rslt_marineVslLot_NE_List'] = $rslt_marineVslLot_NE_List;
			
			$this->load->view('marineVslLot_NotEntering_List',$data);		// start from here - crete html			
		}
	}
	
	function marineVslLot_N4()		// N4 vessel - mhossain
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Marine Vessel Lot (Not Entering)";
			
			$login_id = $this->session->userdata('login_id');
			$section = $this->session->userdata('section');
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			
			$sql_marineVslLot_N4 = "SELECT id,lot_dt,lot_id,tot_vsl,forward_at,forward_by,vsl_lot_type
								FROM ctmsmis.vsl_forward_lot_info
								ORDER BY forward_at DESC";
			$rslt_marineVslLot_N4 = $this->bm->dataSelect($sql_marineVslLot_N4);
			
			$data['title'] = $title;
			$data['rslt_marineVslLot_N4'] = $rslt_marineVslLot_N4;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('marineVslLot_N4',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function marineVslLot_N4_List()		// N4 vessel - mhossain
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Marine Vessel Lot (Not Entering) - Vessel List";
			
			$login_id = $this->session->userdata('login_id');
			$section = $this->session->userdata('section');
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			
			$sql_marineVslLot_N4_List = "SELECT outer_vsl_info.vsl_name,outer_vsl_info.vsl_class,outer_vsl_info.vsl_type
			FROM outer_vsl_forward_info
			INNER JOIN outer_vsl_info ON outer_vsl_info.id = outer_vsl_forward_info.vsl_visit_id
			INNER JOIN vsl_forward_lot_info ON vsl_forward_lot_info.id = outer_vsl_forward_info.vsl_fwd_lot_info_id";
			$rslt_marineVslLot_N4_List = $this->bm->dataSelectDB1($sql_marineVslLot_N4_List);
			
			$data['title'] = $title;
			$data['rslt_marineVslLot_N4_List'] = $rslt_marineVslLot_N4_List;
			
			$this->load->view('marineVslLot_N4_List',$data);		// start from here - crete html			
		}
	}
	// marine vessel lot - end
}
