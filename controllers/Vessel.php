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
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "vesselForwardingbyMarineForm"));
		//Menu Expanding....
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$masterFlag = "";
			$section = $this->session->userdata('section');
			
			$org_Type_id =$this->session->userdata('org_Type_id');
			if($org_Type_id=='83') //Marine
			{
				$title="Vessel Forwarding by Marine";
			}
			else if($org_Type_id=='81')
			{
				$title="Vessel Forwarding by Harbaour Master";
			}	
			else if($org_Type_id=='82') // Accounts
			{
				if($section=='acc') // Accountant
				{
					$title="Vessel Forwarding by Accountant";		
				}
				if($section=='billop') // Bill Operator
				{
					$title="Vessel Forwarding List";		
				}
			}		
			$data['masterFlag'] = $masterFlag;
			$data['msg'] = "";
			$data['flag'] = 0;
			$data['title'] = $title;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselForwardingbyMarineForm',$data);
			$this->load->view('jsAssets');
		}
	}


    function vesselForwardingbyMarine()
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
			$this->bm->VesselDataDumpingByDT($fromDate,$toDate);
			
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Vessel Forwarding by Marine"; 
				
				// search by departure date
				$departQuery = "SELECT DISTINCT ctmsmis.vsl_vssel_info.vvd_gkey as vvd,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
				ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
				ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				
				ctmsmis.vsl_vssel_info.ib_vyg, vsl_forward_info.vvd_gkey,
				ctmsmis.vsl_vssel_info.vsl_class
				FROM ctmsmis.vsl_vssel_info
				Left JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate'
				AND  ctmsmis.vsl_forward_info.vvd_gkey IS NULL ORDER BY ctmsmis.vsl_vssel_info.atd ASC";																						
			}
			else if($org_Type_id=='81') //Master			
			{
				$masterFlag = "master";
				$data['title']="Vessel Forwarding by Harbaour Master";				
				
				// search by forwarding date
				$departQuery = "SELECT DISTINCT ctmsmis.vsl_vssel_info.vvd_gkey as vvd,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
				ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
				ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				
				ctmsmis.vsl_vssel_info.ib_vyg,  vsl_forward_info.vvd_gkey,
				CONCAT(DATE_FORMAT(ctmsmis.vsl_forward_info.marine_forward_at,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_forward_info.marine_forward_at)) AS forwarded_dt,
				ctmsmis.vsl_vssel_info.vsl_class
				FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE DATE(ctmsmis.vsl_forward_info.marine_forward_at) BETWEEN '$fromDate' AND '$toDate' 
				AND ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_stat='0'
				ORDER BY ctmsmis.vsl_vssel_info.atd ASC";
			}
			else if($org_Type_id=='82') // Accounts
			{
				// if($login_id=='sr_acc') //Sr. Accountant
				if($section=='acc') // Accountant - by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding by Sr. Accountant";
					
					// search by forwarding date
					$departQuery = "SELECT DISTINCT ctmsmis.vsl_vssel_info.vvd_gkey as vvd,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
					ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
					ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					ctmsmis.vsl_vssel_info.ib_vyg,  vsl_forward_info.vvd_gkey,
					ctmsmis.vsl_forward_info.master_forward_at AS forwarded_dt,
					ctmsmis.vsl_vssel_info.vsl_class
					FROM ctmsmis.vsl_vssel_info
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE DATE(ctmsmis.vsl_forward_info.master_forward_at) BETWEEN '$fromDate' AND '$toDate'
					AND ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
					ORDER BY ctmsmis.vsl_vssel_info.atd ASC";
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding by Accountant";
					
					// search by forwarding date
					$departQuery = "SELECT DISTINCT ctmsmis.vsl_vssel_info.vvd_gkey as vvd,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
					ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
					ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					ctmsmis.vsl_vssel_info.ib_vyg,  vsl_forward_info.vvd_gkey,
					ctmsmis.vsl_forward_info.sr_acnt_forward_at AS forwarded_dt,
					ctmsmis.vsl_vssel_info.vsl_class
					FROM ctmsmis.vsl_vssel_info
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE DATE(ctmsmis.vsl_forward_info.sr_acnt_forward_at) BETWEEN '$fromDate' AND '$toDate' 
					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_stat='0'
					ORDER BY ctmsmis.vsl_vssel_info.atd ASC";
				}					
			}
			// echo $departQuery;return;
			
			$departData = $this->bm->dataSelectDb2($departQuery);
			// var_dump($departData);return;

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
			$this->load->view('Vessel/vesselForwardingbyMarineForm',$data);			
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
			$billOp = $this->input->post("billOp");
			
			$section = $this->session->userdata('section');
			//echo $billOp.' __ '.$section;
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
				// lot data - start - n4				
				$sql_nextLotSl = "SELECT IFNULL(MAX(lot_sl),0)+1 AS rtnValue
				FROM ctmsmis.vsl_forward_lot_info
				WHERE lot_dt=DATE(NOW())";
								
				$nextLotSl = $this->bm->dataReturnDb2($sql_nextLotSl);				
				$nextLotSl = (strlen($nextLotSl)<2)?("0".$nextLotSl):$nextLotSl;		
				
				$nextLotId = date('d').date('m').substr(date('Y'),2,2).$nextLotSl;				
								
				$totVsl = count($rotationChk);
								
				$lotInsFlag = 0;
				 $insertLotInfo = "INSERT INTO ctmsmis.vsl_forward_lot_info(lot_sl,lot_dt,lot_id,tot_vsl,forward_at,forward_by,vsl_lot_type, ip_addr)
								VALUES('$nextLotSl',CURDATE(),'$nextLotId','$totVsl',NOW(),'$login_id','Marine', '$ipAddress')";								
				$lotInsFlag = $this->bm->dataInsertDb2($insertLotInfo);
				
				if($lotInsFlag == 0)
				{
					echo "<font color='red'>Lot not created</font>";
					return;
				}
				
				$sql_lotId = "SELECT MAX(id) AS rtnValue FROM ctmsmis.vsl_forward_lot_info";
				$lotId = $this->bm->dataReturnDb2($sql_lotId);
				// echo $lotId;return;
				// lot data - end
				foreach ($rotationChk as $rCheck)
				{										
					// echo $rCheck;return;
					$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
					 $chk_str="SELECT COUNT(*) AS rtnValue from ctmsmis.vsl_forward_info WHERE  vvd_gkey='$rCheck'";
					// echo $chk_str;
					$ckh_st = $this->bm->dataReturnDb2($chk_str);
					// echo $ckh_st;return;
					$resInsertst=0;
					if($ckh_st==0)
					{
						$strInsert = "INSERT INTO ctmsmis.vsl_forward_info(vsl_fwd_lot_info_id,vvd_gkey, marine_forward_stat, marine_forward_by, marine_forward_at,  marine_forward_ip,vsl_category) 
						VALUES('$lotId','$rCheck', '1', '$login_id', NOW(), '$ipAddress','CONTAINER')";
						// echo $strInsert;return;
						$resInsertst = $this->bm->dataInsertDb2($strInsert);
						// echo $resInsertst;return;
						$k++;
					}
				}
				//return;
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
					
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey as vvd,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
					ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
					ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					ctmsmis.vsl_vssel_info.ib_vyg,  vsl_forward_info.vvd_gkey,
					ctmsmis.vsl_vssel_info.vsl_class
					FROM ctmsmis.vsl_vssel_info
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate'
					AND  ctmsmis.vsl_vssel_info.vvd_gkey NOT IN (SELECT ctmsmis.vsl_forward_info.vvd_gkey FROM ctmsmis.vsl_forward_info)
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else if($this->input->post('fwBtnOuter'))
				{
					$data['title']="Outer Anchorage Forwarding by Marine";
					
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey as vvd,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
					ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
					ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					ctmsmis.vsl_vssel_info.ib_vyg,  vsl_forward_info.vvd_gkey,
					ctmsmis.vsl_vssel_info.vsl_class
					FROM ctmsmis.vsl_vssel_info
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE  ctmsmis.vsl_vssel_info.phase='20INBOUND' AND  
					 ctmsmis.vsl_vssel_info.vvd_gkey NOT IN (SELECT ctmsmis.vsl_forward_info.vvd_gkey FROM ctmsmis.vsl_forward_info)
					  AND ctmsmis.vsl_vssel_info.cntry_code!='BD'
					ORDER BY  ctmsmis.vsl_vssel_info.atd DESC";
				}
			}
			else if($org_Type_id=='81')		//master
			{
				$masterFlag = "master";
				
			
				$filesub = $this->input->post("filesub");
				$noVsl=count($rotationChk);
			
					$sql_nextFileSl = "SELECT IFNULL(MAX(file_sl),0)+1 AS rtnValue
					FROM ctmsmis.vsl_frwrd_letter_info";
					$nextFileSl = $this->bm->dataReturnDb2($sql_nextFileSl);
					
					$fileNo = "ডিসি /বিএস/বিবিধ/".$nextFileSl;
										
					

					$insert_str="INSERT INTO ctmsmis.vsl_frwrd_letter_info(file_dt,file_sub,file_sl,file_no,no_vsl) 
					VALUES (DATE(NOW()),'$filesub','$nextFileSl','$fileNo','$noVsl')";			
					$insert_st = $this->bm->dataInsertDb2($insert_str);
					// file sl and date - end
					
					$k=0;
					$str="SELECT id AS rtnValue
					FROM ctmsmis.vsl_frwrd_letter_info
					WHERE file_no='$fileNo'
					ORDER BY id DESC
					LIMIT 1";
					$letter_no = $this->bm->dataReturnDb2($str);
					
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET master_forward_stat='1', master_forward_by='$login_id', master_forward_at=NOW(),
									master_forward_ip='$ipAddress', vsl_frwd_letter_no='$letter_no' WHERE vvd_gkey='$rCheck'";
						$update_st = $this->bm->dataUpdatedb2($updateSt);
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
				// }
				
				if($this->input->post('fwBtn'))
				{
					$data['title']="Vessel Forwarding by Master";
					
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey as vvd,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
					ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
					ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					ctmsmis.vsl_vssel_info.ib_vyg,  vsl_forward_info.vvd_gkey,
					ctmsmis.vsl_vssel_info.vsl_class
					FROM ctmsmis.vsl_vssel_info
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' 
					AND ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_stat='0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else if($this->input->post('fwBtnOuter'))
				{
					$data['title']="Outer Anchorage Forwarding by Master";
					
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
					ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
					ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					ctmsmis.vsl_vssel_info.ib_vyg,  vsl_forward_info.vvd_gkey,
					ctmsmis.vsl_vssel_info.vsl_class
					FROM ctmsmis.vsl_vssel_info
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.phase='20INBOUND'
					AND ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_stat='0'
					AND ctmsmis.vsl_vssel_info.cntry_code!='BD'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
			}
			else if($org_Type_id=='82')
			{
				if($section=='acc') //Sr. Accountant 
				{
					$k=0;
					$billOp = $this->input->post("billOp");
					
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),
									sr_acnt_forward_ip='$ipAddress', bill_op_user_id='$billOp'  WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdatedb2($updateSt);
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
						$data['title']="Vessel Forwarding by  Accountant";
						$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey as vvd,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
						DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
						ctmsmis.vsl_vssel_info.loa_cm,
						ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
						ctmsmis.vsl_vssel_info.agent,
						ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
						CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
						CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
						ctmsmis.vsl_vssel_info.ib_vyg,  vsl_forward_info.vvd_gkey,
						ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_forward_info.master_forward_at AS forwarded_dt
						FROM ctmsmis.vsl_vssel_info
					    INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
						WHERE DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate'
						AND ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
						ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
					}
					else if($this->input->post('fwBtnOuter'))
					{
						$data['title']="Vessel Forwarding by  Accountant";
						$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
						DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
						ctmsmis.vsl_vssel_info.loa_cm,
						ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
						ctmsmis.vsl_vssel_info.agent,
						ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
						CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
						CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
						ctmsmis.vsl_vssel_info.ib_vyg,  vsl_forward_info.vvd_gkey,
						ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_forward_info.master_forward_at AS forwarded_dt
						FROM ctmsmis.vsl_vssel_info
					    INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
						WHERE ctmsmis.vsl_vssel_info.phase='20INBOUND'
						AND ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
						AND ctmsmis.vsl_vssel_info.cntry_code!='BD'
						ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
					}
				}
				else if ($login_id=='acc') //Accountant 
				{
					$k=0;
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET billop_bill_stat='1', billop_bill_by='$login_id', billop_bill_at=NOW(),
									billop_bill_ip='$ipAddress' WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdatedb2($updateSt);
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
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey as ,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
					ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
					ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					ctmsmis.vsl_vssel_info.ib_vyg,  vsl_forward_info.vvd_gkey,
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_forward_info.master_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_vssel_info
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' 
					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_stat='0'
					AND ctmsmis.vsl_vssel_info.cntry_code!='BD'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}				
			}

			$departData = $this->bm->dataSelectDb2($departQuery);

			$data['departData']=$departData;
			$data['masterFlag']=$masterFlag;
			$data['fromDate']=$fromDate;
			$data['toDate']=$toDate;
			$data['section']=$section;
			$data['flag'] = 1;
			$data['login_id']=$login_id;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			if($this->input->post('fwBtn'))
				$this->load->view('Vessel/vesselForwardingbyMarineForm',$data);
			else if($this->input->post('fwBtnOuter'))
				$this->load->view('Vessel/vesselForwardOuterAnchorage',$data);
			$this->load->view('jsAssets');
		}
		
	}

	// Vessel Forwarding By Marine for Vatiary -- start

	function vesselForwardingbyMarineforVatiaryForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "vesselForwardingbyMarineforVatiaryForm"));
		//Menu Expanding....
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$masterFlag = "";
			$section = $this->session->userdata('section');
			
			$org_Type_id =$this->session->userdata('org_Type_id');
			if($org_Type_id=='83') //Marine
			{
				$title="Vessel Forwarding (Vatiary) by Marine";
			}
			else if($org_Type_id=='81')
			{
				$title="Vessel Forwarding (Vatiary) by Harbaour Master";
			}	
			else if($org_Type_id=='82') // Accounts
			{
				if($section=='acc') // Accountant
				{
					$title="Vessel Forwarding (Vatiary) by Accountant";		
				}
				if($section=='billop') // Bill Operator
				{
					$title="Vessel Forwarding List";		
				}
			}		
			$data['masterFlag'] = $masterFlag;
			$data['msg'] = "";
			$data['flag'] = 0;
			$data['title'] = $title;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselForwardingbyMarineforVatiaryForm',$data);
			$this->load->view('jsAssets');
		}
	}

    function vesselForwardingbyMarineforVatiary()
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
			
			$data['title']="Vessel Forwarding (Bhatiary) by Marine";
			$data['msg'] = "";
			
			$fromDate = $this->input->post("fromDate");
			$toDate = $this->input->post("toDate");
			
			$departQuery = "";
			$masterFlag = "";
			$this->bm->VesselDataDumpingByDT($fromDate,$toDate);

			// echo $org_Type_id; return;
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Vessel Forwarding (Bhatiary) by Marine";				
				
				if($login_id == '23026') // Farzana
				{		
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%LPG%' AND
					DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND 
					ctmsmis.vsl_vssel_info.vvd_gkey NOT IN (SELECT ctmsmis.vsl_forward_info.vvd_gkey FROM ctmsmis.vsl_forward_info) 
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else if($login_id == '24087') // Fardaus
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%LPG%' AND DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' 
					AND ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.svtmis_forward_stat = '0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else if($login_id == '12369') // Habib
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%LPG%' AND 
					DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' 
					AND ctmsmis.vsl_forward_info.svtmis_forward_stat = '1' AND ctmsmis.vsl_forward_info.hob_forward_stat = '0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
			}
			else if($org_Type_id=='81') //Master			
			{
				$masterFlag = "master";
				$data['title']="Vessel Forwarding (Bhatiary) by Harbaour Master";				
				
				// search by forwarding date
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
				DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
				ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
				ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
				ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
				FROM ctmsmis.vsl_forward_info
				INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_vssel_info.notes LIKE '%LPG%' AND
				 DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND 
				 ctmsmis.vsl_forward_info.hob_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_stat='0'
				ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
			}
			else if($org_Type_id=='82') // Accounts
			{
				// if($login_id=='sr_acc') //Sr. Accountant
				if($section=='acc') // Accountant - by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding (Bhatiary) by Sr. Accountant";
					
					// search by forwarding date
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%LPG%' AND
					 DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND 
					 ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding (Bhatiary) by Accountant";
					
					// search by forwarding date
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%LPG%' AND
					 DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND 
					 ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_stat='0'
					ORDER BY  ctmsmis.vsl_vssel_info.atd DESC";
				}					
			}
			// echo $departQuery;return;
			
			$departData = $this->bm->dataSelectDb2($departQuery);
			// var_dump($departData);return;

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
			$this->load->view('Vessel/vesselForwardingbyMarineforVatiaryForm',$data);			
			$this->load->view('jsAssets');
		}
	}
	
	function vesselForwardingPerformforVatiary()		// N4
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
			$billOp = $this->input->post("billOp");
			
			$section = $this->session->userdata('section');
			//echo $billOp.' __ '.$section;
			//return;
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
				// lot data - start - n4				
				$sql_nextLotSl = "SELECT IFNULL(MAX(lot_sl),0)+1 AS rtnValue
				FROM ctmsmis.vsl_forward_lot_info
				WHERE lot_dt=DATE(NOW())";
								
				$nextLotSl = $this->bm->dataReturnDb2($sql_nextLotSl);				
				$nextLotSl = (strlen($nextLotSl)<2)?("0".$nextLotSl):$nextLotSl;		
				
				$nextLotId = date('d').date('m').substr(date('Y'),2,2).$nextLotSl;				
								
				$totVsl = count($rotationChk);
				
				$ignoredId = array(24087,12369);
				if(!in_array($login_id,$ignoredId))
				{
					$lotInsFlag = 0;
					$insertLotInfo = "INSERT INTO ctmsmis.vsl_forward_lot_info(lot_sl,lot_dt,lot_id,tot_vsl,forward_at,forward_by,vsl_lot_type)
									VALUES('$nextLotSl',CURDATE(),'$nextLotId','$totVsl',NOW(),'$login_id','Marine')";								
					$lotInsFlag = $this->bm->dataInsertDb2($insertLotInfo);
					
					if($lotInsFlag == 0)
					{
						echo "<font color='red'>Lot not created</font>";
						return;
					}
				}
				
				$sql_lotId = "SELECT MAX(id) AS rtnValue FROM ctmsmis.vsl_forward_lot_info";
				$lotId = $this->bm->dataReturnDb2($sql_lotId);
				// echo $lotId;return;
				// lot data - end
				foreach ($rotationChk as $rCheck)
				{
					$this->bm->VesselDataDumpingByVVDGkey($rCheck);											
					$chk_str="SELECT COUNT(*) AS rtnValue from ctmsmis.vsl_forward_info WHERE  vvd_gkey='$rCheck'";
					// echo $chk_str;
					$ckh_st = $this->bm->dataReturnDb2($chk_str);
					// echo $ckh_st;return;
					$resInsertst=0;

					$strInsert = "";

					if($ckh_st==0)
					{
						$strInsert = "INSERT INTO ctmsmis.vsl_forward_info(vsl_fwd_lot_info_id,vvd_gkey, marine_forward_stat, marine_forward_by, marine_forward_at,  marine_forward_ip, vsl_category) 
						VALUES('$lotId','$rCheck', '1', '$login_id', NOW(), '$ipAddress','LPG')";
						// echo $strInsert;return;
						
					}
					else
					{
						if($login_id == 24087)
						{
							$strInsert = "UPDATE ctmsmis.vsl_forward_info SET svtmis_forward_stat = 1 , svtmis_forward_by = '$login_id' , svtmis_forward_at = NOW() , svtmis_forward_ip = '$ipAddress' WHERE vvd_gkey = '$rCheck'";
							//$resInsertst = $this->bm->dataInsert($strInsert);
						}
						else if($login_id == 12369)
						{
							$strInsert = "UPDATE ctmsmis.vsl_forward_info SET hob_forward_stat = 1 , hob_forward_by = '$login_id' , hob_forward_at = NOW() , hob_forward_ip = '$ipAddress' WHERE vvd_gkey = '$rCheck'";
							//$resInsertst = $this->bm->dataInsert($strInsert);
						}
					}

					$resInsertst = $this->bm->dataInsertDb2($strInsert);
					// echo $strInsert;return;
					$k++;
				}

				

				if($resInsertst>0)
				{
					//$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					$this->session->set_flashdata("success", "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>$k Vessel forwarded succesfully</font></div>");
				}
				else
				{
					//$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
				}
				
			}
			else if($org_Type_id=='81')		//master
			{
				$masterFlag = "master";
				
				$filesub = $this->input->post("filesub");
				$noVsl=count($rotationChk);
					
				// file sl and date - start
				$sql_nextFileSl = "SELECT IFNULL(MAX(file_sl),0)+1 AS rtnValue
				FROM ctmsmis.vsl_frwrd_letter_info";
				$nextFileSl = $this->bm->dataReturnDb2($sql_nextFileSl);
				
				$fileNo = "ডিসি/বিএস/সিএস/অংশ-".$nextFileSl;

				$insert_str="INSERT INTO ctmsmis.vsl_frwrd_letter_info(file_dt,file_sub,file_sl,file_no,no_vsl) 
				VALUES (DATE(NOW()),'$filesub','$nextFileSl','$fileNo','$noVsl')";			
				$insert_st = $this->bm->dataInsertDb2($insert_str);
				// file sl and date - end
				
				$k=0;
				$str="SELECT id AS rtnValue
				FROM ctmsmis.vsl_frwrd_letter_info
				WHERE file_no='$fileNo'
				ORDER BY id DESC
				LIMIT 1";
				$letter_no = $this->bm->dataReturnDb2($str);
				
				foreach ($rotationChk as $rCheck)
				{
					$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
					$updateSt="UPDATE ctmsmis.vsl_forward_info SET master_forward_stat='1', master_forward_by='$login_id', master_forward_at=NOW(),
								master_forward_ip='$ipAddress', vsl_frwd_letter_no='$letter_no' WHERE vvd_gkey='$rCheck'";
					$update_st = $this->bm->dataUpdatedb2($updateSt);
					$k++;
				}

				if($update_st>0)
				{
					//$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					$this->session->set_flashdata("success", "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>$k Vessel forwarded succesfully</font></div>");
				}
				else
				{
					//$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
				}
				
			}
			else if($org_Type_id=='82')
			{
				if($section=='acc') //Sr. Accountant 
				{
					$k=0;
					$billOp = $this->input->post("billOp");
					
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),
									sr_acnt_forward_ip='$ipAddress', bill_op_user_id='$billOp'  WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdatedb2($updateSt);
						$k++;
					} 
					$data['msg']="";

					if($update_st>0)
					{
						//$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
						$this->session->set_flashdata("success", "<div class='alert alert-success'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>$k Vessel forwarded succesfully</font></div>");
					}
					else
					{
						//$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
					}
					
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28 
				{
					$k=0;
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET billop_bill_stat='1', billop_bill_by='$login_id', billop_bill_at=NOW(),
									billop_bill_ip='$ipAddress' WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdatedb2($updateSt);
						$k++;
					} 
					$data['msg']="";
					
					$data['title']="Vessel Forwarding (Bhatiary) by Accountant";
				}				
			}

			redirect('Vessel/forwardedVslHistoryBhatiary/', 'refresh');

		}
		
	}

	// Vessel Forwarding By Marine for Vatiary -- end
	
	
	function forwardedVslHistoryBhatiary()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		// $this->session->set_userdata(array('menu' => "VESSEL"));
		// $this->session->set_userdata(array('sub_menu' => "forwardedVslHistoryBeached"));
		//Menu Expanding....
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			$section =$this->session->userdata('section');
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Forwarded By Marine (Bhatiary) - History";
				
				if($login_id == 24087)
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes, 
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%LPG%' AND
					ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.svtmis_forward_stat='1'
					AND ctmsmis.vsl_forward_info.svtmis_forward_by='$login_id'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else if($login_id == 12369)
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes, 
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%LPG%' AND 
					ctmsmis.vsl_forward_info.svtmis_forward_stat='1' AND ctmsmis.vsl_forward_info.hob_forward_stat='1'
					AND ctmsmis.vsl_forward_info.hob_forward_by='$login_id'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes, 
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%LPG%' AND 
					ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.marine_forward_by='$login_id'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}							
			}
			else if($org_Type_id=='81') //Master			
			{
				$data['title']="Forwarded By Master (Bhatiary) - History";
				
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
				DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
				ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
				ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
				ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes, 
				ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM ctmsmis.vsl_forward_info
				INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_vssel_info.notes LIKE '%LPG%' AND 
				ctmsmis.vsl_forward_info.hob_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_stat='1' 
				AND ctmsmis.vsl_forward_info.master_forward_by='$login_id'
				ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
			}
			
		
			$departData = $this->bm->dataSelectDb2($departQuery);

			$data['departData']=$departData;
			// $data['fromDate']=$fromDate;
			// $data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['msg']="";
			$data['flag'] = 1;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');			
			$this->load->view('vesselForwardList_vatiary_history',$data);			
			$this->load->view('jsAssetsList');
		}
	}

	// forwarded Vatiary vsl history - end
	
	

	// Vessel Forwarding By Marine for Kutubdia -- start

	function vesselForwardingbyMarineforKutubdiaForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "vesselForwardingbyMarineforKutubdiaForm"));
		//Menu Expanding....
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$masterFlag = "";
			$section = $this->session->userdata('section');
			
			$org_Type_id =$this->session->userdata('org_Type_id');
			if($org_Type_id=='83') //Marine
			{
				$title="Vessel Forwarding (Kutubdia) by Marine";
			}
			else if($org_Type_id=='81')
			{
				$title="Vessel Forwarding (Kutubdia) by Harbaour Master";
			}	
			else if($org_Type_id=='82') // Accounts
			{
				if($section=='acc') // Accountant
				{
					$title="Vessel Forwarding (Kutubdia) by Accountant";		
				}
				if($section=='billop') // Bill Operator
				{
					$title="Vessel Forwarding List";		
				}
			}		
			$data['masterFlag'] = $masterFlag;
			$data['msg'] = "";
			$data['flag'] = 0;
			$data['title'] = $title;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselForwardingbyMarineforKutubdiaForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function vesselForwardingbyMarineforKutubdia()
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
			
			$data['title']="Vessel Forwarding (Bhatiary) by Marine";
			$data['msg'] = "";
			
			$fromDate = $this->input->post("fromDate");
			$toDate = $this->input->post("toDate");
			
			$departQuery = "";
			$masterFlag = "";
			$this->bm->VesselDataDumpingByDT($fromDate,$toDate);
			
			
			if($org_Type_id=='83') //Marine
			{
			
				$data['title']="Vessel Forwarding (Kutubdia) by Marine";
				
				// search by departure date
				
				if($login_id == '23026') // Farzana
				{		
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%KUTUBDIA%' AND 
					DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND 
					ctmsmis.vsl_vssel_info.vvd_gkey NOT IN (SELECT ctmsmis.vsl_forward_info.vvd_gkey FROM ctmsmis.vsl_forward_info) 
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else if($login_id == '24087') // Fardaus
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%KUTUBDIA%' 
					AND DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' 
					AND ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.svtmis_forward_stat = '0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else if($login_id == '12369') // Habib
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%KUTUBDIA%' AND 
					DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' 
					AND ctmsmis.vsl_forward_info.svtmis_forward_stat = '1' AND ctmsmis.vsl_forward_info.hob_forward_stat = '0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
			}
			else if($org_Type_id=='81') //Master			
			{
				$masterFlag = "master";
				$data['title']="Vessel Forwarding (Kutubdia) by Harbaour Master";				
				
				// search by forwarding date
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
				DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
				ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
				ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
				ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes, 
				ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM ctmsmis.vsl_forward_info
				INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_vssel_info.notes LIKE '%KUTUBDIA%' AND 
				DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' 
				AND ctmsmis.vsl_forward_info.hob_forward_stat='1' 
				AND ctmsmis.vsl_forward_info.master_forward_stat='0'
				ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
			}
			else if($org_Type_id=='82') // Accounts
			{
				// if($login_id=='sr_acc') //Sr. Accountant
				if($section=='acc') // Accountant - by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding (Kutubdia) by Sr. Accountant";
					
					// search by forwarding date
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%KUTUBDIA%' AND 
					DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate'  AND ctmsmis.vsl_forward_info.master_forward_stat='1' 
					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding (Kutubdia) by Accountant";
					
					// search by forwarding date
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%KUTUBDIA%' AND
					 DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' 
					 AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_stat='0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}					
			}
			// echo $departQuery;return;
			
			$departData = $this->bm->dataSelectDb2($departQuery);
			// var_dump($departData);return;

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
			$this->load->view('Vessel/vesselForwardingbyMarineforKutubdiaForm',$data);			
			$this->load->view('jsAssets');
		}
	}
	
	function vesselForwardingPerformforKutubdia()		// N4
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
			$billOp = $this->input->post("billOp");
			
			$section = $this->session->userdata('section');
			//echo $billOp.' __ '.$section;
			//return;
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
				// lot data - start - n4				
				$sql_nextLotSl = "SELECT IFNULL(MAX(lot_sl),0)+1 AS rtnValue
				FROM ctmsmis.vsl_forward_lot_info
				WHERE lot_dt=DATE(NOW())";
								
				$nextLotSl = $this->bm->dataReturnDb2($sql_nextLotSl);				
				$nextLotSl = (strlen($nextLotSl)<2)?("0".$nextLotSl):$nextLotSl;		
				
				$nextLotId = date('d').date('m').substr(date('Y'),2,2).$nextLotSl;				
								
				$totVsl = count($rotationChk);

				$ignoredId = array(24087,12369);
				if(!in_array($login_id,$ignoredId))
				{
					$lotInsFlag = 0;
					$insertLotInfo = "INSERT INTO ctmsmis.vsl_forward_lot_info(lot_sl,lot_dt,lot_id,tot_vsl,forward_at,forward_by,vsl_lot_type)
									VALUES('$nextLotSl',CURDATE(),'$nextLotId','$totVsl',NOW(),'$login_id','Marine')";								
					$lotInsFlag = $this->bm->dataInsertDb2($insertLotInfo);
					
					if($lotInsFlag == 0)
					{
						echo "<font color='red'>Lot not created</font>";
						return;
					}
				}
				
				$sql_lotId = "SELECT MAX(id) AS rtnValue FROM ctmsmis.vsl_forward_lot_info";
				$lotId = $this->bm->dataReturnDb2($sql_lotId);
				// echo $lotId;return;
				// lot data - end
				foreach ($rotationChk as $rCheck)
				{
					$this->bm->VesselDataDumpingByVVDGkey($rCheck);										
					// echo $rCheck;return;
					$chk_str="SELECT COUNT(*) AS rtnValue from ctmsmis.vsl_forward_info WHERE  vvd_gkey='$rCheck'";
					// echo $chk_str;
					$ckh_st = $this->bm->dataReturnDb2($chk_str);
					// echo $ckh_st;return;
					$resInsertst=0;
					$strInsert = "";

					if($ckh_st==0)
					{
						$strInsert = "INSERT INTO ctmsmis.vsl_forward_info(vvd_gkey, marine_forward_stat, marine_forward_by, marine_forward_at,  marine_forward_ip,vsl_category) 
						VALUES('$rCheck', '1', '$login_id', NOW(), '$ipAddress','KUTUBDIA')";
						// echo $strInsert;return;
						//$resInsertst = $this->bm->dataInsert($strInsert);
						// echo $resInsertst;return;
						// $k++;
					}
					else
					{
						if($login_id == 24087)
						{
							$strInsert = "UPDATE ctmsmis.vsl_forward_info SET svtmis_forward_stat = 1 , svtmis_forward_by = '$login_id' , svtmis_forward_at = NOW() , svtmis_forward_ip = '$ipAddress' WHERE vvd_gkey = '$rCheck'";
							//$resInsertst = $this->bm->dataInsert($strInsert);
						}
						else if($login_id == 12369)
						{
							$strInsert = "UPDATE ctmsmis.vsl_forward_info SET hob_forward_stat = 1 , hob_forward_by = '$login_id' , hob_forward_at = NOW() , hob_forward_ip = '$ipAddress' WHERE vvd_gkey = '$rCheck'";
							//$resInsertst = $this->bm->dataInsert($strInsert);
						}
					}

					$resInsertst = $this->bm->dataInsertDb2($strInsert);
					// echo $strInsert;return;
					$k++;
				}

				if($resInsertst>0)
				{
					//$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					$this->session->set_flashdata("success", "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>$k Vessel forwarded succesfully</font></div>");
				}
				else
				{
					//$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
				}
				
			}
			else if($org_Type_id=='81')		//master
			{
				$masterFlag = "master";
				
				
				$filesub = $this->input->post("filesub");
				$noVsl=count($rotationChk);
				
					$sql_nextFileSl = "SELECT IFNULL(MAX(file_sl),0)+1 AS rtnValue
					FROM ctmsmis.vsl_frwrd_letter_info";
					$nextFileSl = $this->bm->dataReturnDb2($sql_nextFileSl);
					
					$fileNo = "ডিসি/বিএস/সিএস/অংশ-".$nextFileSl;
										
				

					$insert_str="INSERT INTO ctmsmis.vsl_frwrd_letter_info(file_dt,file_sub,file_sl,file_no,no_vsl) 
					VALUES (DATE(NOW()),'$filesub','$nextFileSl','$fileNo','$noVsl')";			
					$insert_st = $this->bm->dataInsertDb2($insert_str);
				
					$k=0;
					$str="SELECT id AS rtnValue
					FROM ctmsmis.vsl_frwrd_letter_info
					WHERE file_no='$fileNo'
					ORDER BY id DESC
					LIMIT 1";
					$letter_no = $this->bm->dataReturnDb2($str);
					
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);		
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET master_forward_stat='1', master_forward_by='$login_id', master_forward_at=NOW(),
									master_forward_ip='$ipAddress', vsl_frwd_letter_no='$letter_no' WHERE vvd_gkey='$rCheck'";
						$update_st = $this->bm->dataUpdatedb2($updateSt);
						$k++;
					}
					$data['msg']="";
					if($update_st>0)
					{
				
						$this->session->set_flashdata("success", "<div class='alert alert-success'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>$k Vessel forwarded succesfully</font></div>");
					}
					else
					{
						//$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
					}
				// }
				
			}
			else if($org_Type_id=='82')
			{
				if($section=='acc') //Sr. Accountant 
				{
					$k=0;
					$billOp = $this->input->post("billOp");
					
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);		
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),
									sr_acnt_forward_ip='$ipAddress', bill_op_user_id='$billOp'  WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdatedb2($updateSt);
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
				else if ($section=='billop') //Accountant 
				{
					$k=0;
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);		
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET billop_bill_stat='1', billop_bill_by='$login_id', billop_bill_at=NOW(),
									billop_bill_ip='$ipAddress' WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdatedb2($updateSt);
						$k++;
					} 
					$data['msg']="";
					
					$data['title']="Vessel Forwarding (Kutubdia) by Accountant";

				}				
			}

			redirect('Vessel/forwardedVslHistoryKutubdia/', 'refresh');
		}
		
	}

	// Vessel Forwarding By Marine for Kutubdia -- end

	function forwardedVslHistoryKutubdia()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		// $this->session->set_userdata(array('menu' => "VESSEL"));
		// $this->session->set_userdata(array('sub_menu' => "forwardedVslHistoryBeached"));
		//Menu Expanding....
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			$section =$this->session->userdata('section');
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Forwarded By Marine (Kutubdia) - History";
				
				if($login_id == 24087)
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes, 
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%KUTUBDIA%' AND ctmsmis.vsl_forward_info.marine_forward_stat='1'
					 AND ctmsmis.vsl_forward_info.svtmis_forward_stat='1' AND ctmsmis.vsl_forward_info.svtmis_forward_by='$login_id'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else if($login_id == 12369)
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes, 
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%KUTUBDIA%' AND ctmsmis.vsl_forward_info.svtmis_forward_stat='1' 
					AND ctmsmis.vsl_forward_info.hob_forward_stat='1' AND ctmsmis.vsl_forward_info.hob_forward_by='$login_id'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes, 
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%KUTUBDIA%' AND ctmsmis.vsl_forward_info.marine_forward_stat='1' 
					AND ctmsmis.vsl_forward_info.marine_forward_by='$login_id'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";

					
				}							
			}
			else if($org_Type_id=='81') //Master			
			{
				$data['title']="Forwarded By Master (Kutubdia) - History";
				
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
				DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
				ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
				ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
				ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes, 
				ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM ctmsmis.vsl_forward_info
				INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_vssel_info.notes LIKE '%KUTUBDIA%' AND ctmsmis.vsl_forward_info.hob_forward_stat='1' 
				AND ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_by='$login_id'
				ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
			}
			
			$departData = $this->bm->dataSelectDb2($departQuery);

			$data['departData']=$departData;
			// $data['fromDate']=$fromDate;
			// $data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['msg']="";
			$data['flag'] = 1;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');			
			$this->load->view('Vessel/vesselForwardList_kutubdia_history',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	// Vessel Forwarding By Marine for Kutubdia -- end
	
	
    function vesselForwardingForAcc($action = null)
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

			//$fromDate = $this->input->post("fromDate");
			//$toDate = $this->input->post("toDate");
			//$billOp = $this->input->post("billOp");
			
			$section = $this->session->userdata('section');
		
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			
			$vsl_category = $this->input->post("vsl_category");
			$fileNo = $this->input->post("file_no");
			$filedt = $this->input->post("file_dt");
			$filesub = $this->input->post("file_sub");
			$no_vsl = $this->input->post("no_vsl");
			$letter_id = $this->input->post("letter_id");
			
			
			if($org_Type_id=='83') //Marine
			{
				$title="Vessel Forwarding by Marine";
			}
			else if($org_Type_id=='81')
			{
				$title="Vessel Forwarding by Harbaour Master";
			}	
			else if($org_Type_id=='82') // Accounts
			{
				if($section=='acc') // Accountant
				{
					$title="Vessel Forwarding by Accountant";		
				}
				if($section=='billop') // Bill Operator
				{
					$title="Vessel Forwarding List";		
				}
			}		
			
			if($section=='acc') 
			{
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
				ctmsmis.vsl_vssel_info.ves_captain, ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name, 
				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				
				ctmsmis.vsl_vssel_info.ib_vyg, 
				CONCAT(DATE_FORMAT(ctmsmis.vsl_forward_info.master_forward_at,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_forward_info.master_forward_at)) AS forwarded_dt,
				ctmsmis.vsl_vssel_info.vsl_class,
				ctmsmis.vsl_vssel_info.off_port_arr AS oa_date_dollar,
				CONCAT(DATE_FORMAT(ctmsmis.vsl_vssel_info.off_port_arr,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_vssel_info.off_port_arr)) AS oa_date,ctmsmis.vsl_forward_info.vsl_category
				FROM ctmsmis.vsl_forward_info
                INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0' AND  ctmsmis.vsl_forward_info.vsl_frwd_letter_no='$letter_id'";
			}
			else if($section=='billop') 
			{
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
				ctmsmis.vsl_vssel_info.ves_captain, ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name, 
				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				
				ctmsmis.vsl_vssel_info.ib_vyg, 
				CONCAT(DATE_FORMAT(ctmsmis.vsl_forward_info.master_forward_at,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_forward_info.master_forward_at)) AS forwarded_dt,
				ctmsmis.vsl_vssel_info.vsl_class,
				ctmsmis.vsl_vssel_info.off_port_arr AS oa_date_dollar,
				CONCAT(DATE_FORMAT(ctmsmis.vsl_vssel_info.off_port_arr,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_vssel_info.off_port_arr)) AS oa_date,ctmsmis.vsl_forward_info.vsl_category
				FROM ctmsmis.vsl_forward_info
                INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND  ctmsmis.vsl_forward_info.vsl_frwd_letter_no='$letter_id'
				AND bill_op_user_id='$login_id' AND ctmsmis.vsl_forward_info.billop_bill_stat=0";
			}	
			// echo $departQuery;return;
			$departData = $this->bm->dataSelectDb2($departQuery);
			
			// dollar rate - start
			$sql_dollarRate = "SELECT rate
			FROM bil_currency_exchange_rates
			WHERE effective_date=DATE(NOW()) ORDER BY gkey DESC LIMIT 1";
			$rslt_dollarRate = $this->bm->dataSelectDB1($sql_dollarRate);
			
			$dollarRate = "";
			
			for($i=0;$i<count($rslt_dollarRate);$i++)
			{
				$dollarRate = $rslt_dollarRate[$i]['rate'];
			}
			
					
			$data['departData']=$departData;
			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);

			$data['billOpListRslt']=$billOpListRslt;
			$data['dollarRate']=$dollarRate;
			
			$data['vsl_category']=$vsl_category;
			$data['filedt']=$filedt;
			$data['fileNo']=$fileNo;
			$data['filesub']=$filesub;
			$data['letter_id']=$letter_id;
			$data['title']=$title;
			//$data['toDate']=$toDate;
			$data['section']=$section;
			$data['flag'] = 1;
			$data['msg'] = "";
			$data['login_id']=$login_id;
			$data['org_Type_id']=$org_Type_id;
			$data['action']=$action;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			//if($this->input->post('fwBtn'))
				$this->load->view('Vessel/vesselForwardingForAccBill',$data);
			//else if($this->input->post('fwBtnOuter'))
			//	$this->load->view('vesselForwardOuterAnchorage',$data);
			$this->load->view('jsAssets');
		}
			
	}
	function vesselForwardingForAccBillAction()
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
			$section = $this->session->userdata('section');
			$filedt = $this->session->userdata('filedt');
			$fileNo = $this->session->userdata('fileNo');
			$filesub = $this->session->userdata('filesub');
			$msg="";
			$departQuery="";
			if($section=='acc')
			{
				if(isset($_POST['idchk']))
				{
					$rotationChk = $_POST['idchk'];
				}
				$k=0;
				
				$letter_id = $this->input->post("letter_id");
				$billOp = $this->input->post("billOp");
				foreach ($rotationChk as $rCheck)
				{
					$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
					  $updateSt="UPDATE ctmsmis.vsl_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),
								sr_acnt_forward_ip='$ipAddress', bill_op_user_id='$billOp'  WHERE vvd_gkey='$rCheck'";
								
					$update_st = $this->bm->dataUpdatedb2($updateSt);
					$k++;
				} 
				
				//return;
				$msg="";
				if($update_st>0)
				{
					$msg = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
				}
				else
				{
					$msg = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
				}
				
				$title="Vessel Forwarding by Accountant";	
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
				ctmsmis.vsl_vssel_info.ves_captain, ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name, 
				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				
				ctmsmis.vsl_vssel_info.ib_vyg, 
				CONCAT(DATE_FORMAT(ctmsmis.vsl_forward_info.master_forward_at,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_forward_info.master_forward_at)) AS forwarded_dt,
				ctmsmis.vsl_vssel_info.vsl_class,
				ctmsmis.vsl_vssel_info.off_port_arr AS oa_date_dollar,
				CONCAT(DATE_FORMAT(ctmsmis.vsl_vssel_info.off_port_arr,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_vssel_info.off_port_arr)) AS oa_date,ctmsmis.vsl_forward_info.vsl_category
				FROM ctmsmis.vsl_forward_info
                INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0' AND  ctmsmis.vsl_forward_info.vsl_frwd_letter_no='$letter_id'";
				//return;									
			}
			else if($section=='billop')
			{
				
			}
			$departData = $this->bm->dataSelectDb2($departQuery);
			$data['departData']=$departData;
			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);

			$data['billOpListRslt']=$billOpListRslt;
			$data['filedt']=$filedt;
			$data['fileNo']=$fileNo;
			$data['filesub']=$filesub;
			$data['letter_id']=$letter_id;
			$data['title']=$title;
			//$data['toDate']=$toDate;
			$data['section']=$section;
			$data['flag'] = 1;
			$data['msg'] = $msg;
			$data['login_id']=$login_id;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			//if($this->input->post('fwBtn'))
				$this->load->view('Vessel/vesselForwardingForAccBill',$data);
			//else if($this->input->post('fwBtnOuter'))
			//	$this->load->view('vesselForwardOuterAnchorage',$data);
			$this->load->view('jsAssets');
		}			
	}

	function vesselForwardinghistory()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$this->session->set_userdata(array('menu' => "bill"));
		$this->session->set_userdata(array('sub_menu' => "vesselForwardinghistory"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			// $query = "SELECT ctmsmis.vsl_forward_info.id, ctmsmis.vsl_forward_info.bill_op_user_id AS forwarded_to, ctmsmis.vsl_forward_info.sr_acnt_forward_by AS forwarded_by, 
			// CONCAT(DATE_FORMAT(ctmsmis.vsl_forward_info.sr_acnt_forward_at,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_forward_info.sr_acnt_forward_at)) AS forwarded_at,sparcsn4.vsl_vessel_visit_details.ib_vyg AS rotation,sparcsn4.vsl_vessels.name AS vsl_name, (CASE WHEN sparcsn4.vsl_vessel_classes.basic_class='CELL' THEN 'CONTAINER' WHEN sparcsn4.vsl_vessel_classes.basic_class='BBULK' THEN 'BREAK BULK' WHEN sparcsn4.vsl_vessel_classes.basic_class='PSNGR' THEN 'PESSENGER' WHEN sparcsn4.vsl_vessel_classes.basic_class='UNKNOWN' THEN sparcsn4.vsl_vessel_classes.notes ELSE sparcsn4.vsl_vessel_classes.basic_class END) AS basic_class
			// FROM ctmsmis.vsl_forward_info 
			// INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey = ctmsmis.vsl_forward_info.vvd_gkey 
			// INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey 
			// INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
			// WHERE ctmsmis.vsl_forward_info.sr_acnt_forward_by = '$login_id'";

			$query = "SELECT ctmsmis.vsl_forward_info.id, ctmsmis.vsl_forward_info.bill_op_user_id AS forwarded_to, 
			ctmsmis.vsl_forward_info.sr_acnt_forward_by AS forwarded_by, 
			CONCAT(DATE_FORMAT(ctmsmis.vsl_forward_info.sr_acnt_forward_at,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_forward_info.sr_acnt_forward_at)) AS forwarded_at,
			ctmsmis.vsl_vssel_info.ib_vyg AS rotation,ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
			ctmsmis.vsl_vssel_info.notes as notes,
			ctmsmis.vsl_vssel_info.basic_class AS basic_class
			FROM ctmsmis.vsl_forward_info 
			INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
			WHERE ctmsmis.vsl_forward_info.sr_acnt_forward_by = '$login_id'
			UNION
			SELECT ctmsmis.vsl_cancelation_forward_info.id, ctmsmis.vsl_cancelation_forward_info.bill_op_user_id AS forwarded_to,
			ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_by AS forwarded_by,
			CONCAT(DATE_FORMAT(ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_at,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_at)) AS forwarded_at,
			ctmsmis.vsl_vssel_info.ib_vyg AS rotation,ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
			ctmsmis.vsl_vssel_info.notes as notes,
			'VESSEL CANCELATION' AS basic_class
			FROM ctmsmis.vsl_cancelation_forward_info 
			INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey = ctmsmis.vsl_cancelation_forward_info.vvd_gkey 
			WHERE ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_by = '$login_id'";

			// echo $query;return;
			$billHistory = $this->bm->dataSelectDb2($query);

			// IGM Query
			$queryIGM = "SELECT outer_vsl_forward_info.id,outer_vsl_forward_info.bill_op_user_id AS forwarded_to,outer_vsl_forward_info.sr_acnt_forward_by AS forwarded_by,CONCAT(DATE_FORMAT(outer_vsl_forward_info.sr_acnt_forward_at,'%d/%m/%Y'),' ',TIME(outer_vsl_forward_info.sr_acnt_forward_at)) AS forwarded_at,outer_vsl_visit_info.imp_rot AS rotation,outer_vsl_info.vsl_name AS vsl_name,'NOT ENTERING' AS basic_class
			FROM outer_vsl_forward_info
			INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
			INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
			WHERE outer_vsl_forward_info.sr_acnt_forward_by = '$login_id'";
			$billHistoryIGM = $this->bm->dataSelectDB1($queryIGM);

			// Merge two data in one variable
			$billHistory = array_merge($billHistory,$billHistoryIGM);

			$data['title'] = "Vessel Forwarding History";
			$data['billHistory'] = $billHistory;
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselForwardinghistory',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function usdtoBdtExchangeRateform()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$this->session->set_userdata(array('menu' => "bill"));
		$this->session->set_userdata(array('sub_menu' => "usdtoBdtExchangeRateform"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$msg = "";

			$currencyQuery = "SELECT gkey,id FROM bil_currencies";
			$currencies = $this->bm->dataSelectDB1($currencyQuery);

			$data['currencies'] = $currencies;
			$data['msg'] = $msg;
			$data['title'] = "USD to BDT Exchange Rate...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/usdtoBdtExchangeRateform',$data);
			$this->load->view('jsAssets');
		}
	}

	function usdtoBdtExchangeRate()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$this->session->set_userdata(array('menu' => "bill"));
		$this->session->set_userdata(array('sub_menu' => "usdtoBdtExchangeRateform"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$msg = "";

			$rate = htmlspecialchars($this->input->post('rate'));
			$note = htmlspecialchars($this->input->post('note'));
			$from = htmlspecialchars($this->input->post('from'));
			$to = htmlspecialchars($this->input->post('to'));
			$effectiveDate = htmlspecialchars($this->input->post('date'));
			$store = htmlspecialchars($this->input->post('store'));
			// return;

			if($store == "Store")
			{
				$query = "INSERT INTO bil_currency_exchange_rates(rate,notes,effective_date,from_currency_gkey,to_currency_gkey,created,creator) VALUES('$rate','$note','$effectiveDate','$from','$to',NOW(),'$login_id')";
				// echo $query;return;
				$result = $this->bm->dataInsertDB1($query);
				if($result){
					$msg = "<font color='green' size='5'>Success...</font>";
				}else{
					$msg = "<font color='red' size='5'>Failed. Please try again later...</font>";
				}
			}
			else if($store == "Update")
			{
				$gkey = $this->input->post('gkey');
				$dataQuery = "SELECT * FROM bil_currency_exchange_rates WHERE gkey = '$gkey'";
				$dataResult = $this->bm->dataSelectDB1($dataQuery);
				
				$prev_gkey = "";
				$prev_rate = "";
				$prev_notes = "";
				$prev_effective_date = "";
				$prev_from_currency_gkey = "";
				$prev_to_currency_gkey = "";
				$prev_created = "";
				$prev_creator = "";
				$prev_changed = "";
				$prev_changer = "";
				$prev_currency_gkey = "";

				foreach($dataResult as $result){
					$prev_gkey = $result['gkey'];
					$prev_rate = $result['rate'];
					$prev_notes = $result['notes'];
					$prev_effective_date = $result['effective_date'];
					$prev_from_currency_gkey = $result['from_currency_gkey'];
					$prev_to_currency_gkey = $result['to_currency_gkey'];
					$prev_created = $result['created'];
					$prev_creator = $result['creator'];
					$prev_changed = $result['changed'];
					$prev_changer = $result['changer'];
					$prev_currency_gkey = $result['currency_gkey'];
				}

				$insertQuery = "INSERT INTO bill_currency_exchange_log (gkey,rate,notes,effective_date,from_currency_gkey,to_currency_gkey,created,creator,changed,changer,currency_gkey,action,action_by,action_at) VALUES('$prev_gkey','$prev_rate','$prev_notes','$prev_effective_date','$prev_from_currency_gkey','$prev_to_currency_gkey','$prev_created','$prev_creator','$prev_changed','$prev_changer','$prev_currency_gkey','update','$login_id',NOW())";
				
				if($this->bm->dataInsertDB1($insertQuery))
				{
					$updateQuery = "UPDATE bil_currency_exchange_rates SET rate = '$rate', notes = '$note', effective_date = '$effectiveDate', from_currency_gkey = '$from', to_currency_gkey = '$to',changed = NOW(), changer= '$login_id' WHERE gkey = '$gkey'";
					// return;
					if($this->bm->dataUpdateDB1($updateQuery))
					{
						$msg = "<font color='green' size='5'>Success...</font>";
					}
					else
					{
						$msg = "<font color='red' size='5'>Something Wrong! Please try again later...</font>";
					}
				}
				else
				{
					$msg = "<font color='red' size='5'>Something Wrong! Please try again later...</font>";
				}
			}
			
			$currencyQuery = "SELECT gkey,id FROM bil_currencies";
			$currencies = $this->bm->dataSelectDB1($currencyQuery);

			$data['currencies'] = $currencies;
			$data['msg'] = $msg;
			$data['title'] = "USD to BDT Exchange Rate...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/usdtoBdtExchangeRateform',$data);
			$this->load->view('jsAssets');
		}
	}

	function usdtoBdtExchangeRateList()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$this->session->set_userdata(array('menu' => "bill"));
		$this->session->set_userdata(array('sub_menu' => "usdtoBdtExchangeRateList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$msg = "";

			if($this->input->server('REQUEST_METHOD') === 'POST'){

				$action = $this->input->post('action');
				$gkey = $this->input->post('gkey');
				$dataQuery = "SELECT * from bil_currency_exchange_rates where gkey = '$gkey'";
				$dataResult = $this->bm->dataSelectDB1($dataQuery);

				if($action == "Edit"){
					$data['rslt'] = $dataResult;
					$data['flag'] = 'edit';

					$currencyQuery = "SELECT gkey,id FROM bil_currencies";
					$currencies = $this->bm->dataSelectDB1($currencyQuery);

					$data['currencies'] = $currencies;
					$data['msg'] = $msg;
					$data['title'] = "Edit Exchange Rate...";
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('Vessel/usdtoBdtExchangeRateform',$data);
					$this->load->view('jsAssets');
					return;
				}
				if($action == "Delete")
				{	
					$prev_gkey = "";
					$prev_rate = "";
					$prev_notes = "";
					$prev_effective_date = "";
					$prev_from_currency_gkey = "";
					$prev_to_currency_gkey = "";
					$prev_created = "";
					$prev_creator = "";
					$prev_changed = "";
					$prev_changer = "";
					$prev_currency_gkey = "";

					foreach($dataResult as $result){
						$prev_gkey = $result['gkey'];
						$prev_rate = $result['rate'];
						$prev_notes = $result['notes'];
						$prev_effective_date = $result['effective_date'];
						$prev_from_currency_gkey = $result['from_currency_gkey'];
						$prev_to_currency_gkey = $result['to_currency_gkey'];
						$prev_created = $result['created'];
						$prev_creator = $result['creator'];
						$prev_changed = $result['changed'];
						$prev_changer = $result['changer'];
						$prev_currency_gkey = $result['currency_gkey'];
					}

					$insertQuery = "INSERT INTO bill_currency_exchange_log (gkey,rate,notes,effective_date,from_currency_gkey,to_currency_gkey,created,creator,changed,changer,currency_gkey,action,action_by,action_at) VALUES('$prev_gkey','$prev_rate','$prev_notes','$prev_effective_date','$prev_from_currency_gkey','$prev_to_currency_gkey','$prev_created','$prev_creator','$prev_changed','$prev_changer','$prev_currency_gkey','delete','$login_id',NOW())";
					
					if($this->bm->dataInsertDB1($insertQuery))
					{
						$deleteQuery = "DELETE from bil_currency_exchange_rates WHERE gkey = '$gkey'";
						// return;
						if($this->bm->dataUpdateDB1($deleteQuery))
						{
							$msg = "<font color='green' size='5'>Success...</font>";
						}
						else
						{
							$msg = "<font color='red' size='5'>Something Wrong! Please try again later...</font>";
						}
					}
					else
					{
						$msg = "<font color='red' size='5'>Something Wrong! Please try again later...</font>";
					}
				}
			}

			$exchangeList = "SELECT bil_currency_exchange_rates.gkey,rate,notes,CONCAT(DATE_FORMAT(effective_date,'%d/%m/%Y')) As effective_date,bil_currencies.id AS from_currency,(SELECT id FROM bil_currencies WHERE gkey = bil_currency_exchange_rates.to_currency_gkey) AS to_currency
			FROM bil_currency_exchange_rates
			INNER JOIN bil_currencies ON bil_currencies.gkey = bil_currency_exchange_rates.from_currency_gkey";
			$result = $this->bm->dataSelectDB1($exchangeList);

			$data['result'] = $result;
			$data['msg'] = $msg;
			$data['title'] = "Exchange Rate List";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/usdtoBdtExchangeRateList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function searchNotEnteringVslFromLetter()
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
			$org_Type_id = $this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');
						
			$searchRotation = $this->input->post('searchRotation');
			
			$sql_vslFromLetter = "";

			if($section=='billop')
			{
				$sql_vslFromLetter = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl,stmt_month, (SELECT COUNT(*) FROM cchaportdb.outer_vsl_forward_info WHERE cchaportdb.outer_vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no AND cchaportdb.outer_vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no 
				FROM (SELECT DISTINCT outer_visit_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,stmt_month 
				FROM cchaportdb.outer_visit_frwrd_letter_info 
				INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_frwd_letter_no=outer_visit_frwrd_letter_info.id 
				INNER JOIN outer_vsl_visit_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id 
				WHERE outer_vsl_forward_info.bill_op_user_id='20217' AND outer_vsl_visit_info.imp_rot = '$searchRotation') AS tbl ORDER BY letter_no DESC";
			}
			else
			{
				$sql_vslFromLetter = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl,stmt_month,
				(SELECT COUNT(*)  FROM cchaportdb.outer_vsl_forward_info 
				WHERE cchaportdb.outer_vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no 
				AND cchaportdb.outer_vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
				FROM  (SELECT DISTINCT outer_visit_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,stmt_month
				FROM cchaportdb.outer_visit_frwrd_letter_info
				INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_frwd_letter_no=outer_visit_frwrd_letter_info.id
				INNER JOIN outer_vsl_visit_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
				WHERE outer_vsl_visit_info.imp_rot = '$searchRotation') AS tbl  ORDER BY letter_no DESC";
			}
			
			// echo $sql_vslFromLetter;return;
			$letterList = $this->bm->dataSelectDB1($sql_vslFromLetter);
			
			$msg = "";
			$title="";
			if($section=='acc')
			{
				$title="Vessel Forwarding by Accountant";
			}	
			 else if($section=='billop')
			{
				$title="Vessel Forwarding ";
			}
			if(count($letterList) == 0)
				$msg = "<font color='red'>No data found...</font>";

			$data['letterList']=$letterList;
			$data['title']=$title;
			$data['msg']=$msg;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;
			$data['section']=$section;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselForwardingbyMasterListNotEntering',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	
	function vesselForwardingForAcc_NotEntering()
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
			//echo $billOp.' __ '.$section;
			//return;
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			
			$fileNo = $this->input->post("file_no");
			$filedt = $this->input->post("file_dt");
			$filesub = $this->input->post("file_sub");
			$no_vsl = $this->input->post("no_vsl");
			$letter_id = $this->input->post("letter_id");
			
			
			if($org_Type_id=='83') //Marine
			{
				$title="Vessel Forwarding by Marine (Not Entering)";
			}
			else if($org_Type_id=='81')
			{
				$title="Vessel Forwarding by Harbaour Master (Not Entering)";
			}	
			else if($org_Type_id=='82') // Accounts
			{
				if($section=='acc') // Accountant
				{
					$title="Vessel Forwarding by Accountant (Not Entering)";		
				}
				if($section=='billop') // Bill Operator
				{
					$title="Vessel Forwarding List (Not Entering)";		
				}
			}		
			
			if($section=='acc') 
			{
				$departQuery = "SELECT outer_vsl_forward_info.id AS vvd_gkey, outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name AS vsl_name,
				CONCAT(date_of_arrival,' ',time_of_arrival) AS ata, CONCAT(date_of_departure,' ',time_of_departure) AS atd,
				grt,nrt,flag AS cntry_name, vsl_visit_id as vs_id,
				outer_vsl_info.loa*100 AS loa_cm, 
				agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat,
				outer_vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM outer_vsl_visit_info
				INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
				WHERE cchaportdb.outer_vsl_forward_info.sr_acnt_forward_stat='0' 
				AND cchaportdb.outer_vsl_forward_info.vsl_frwd_letter_no='$letter_id'";
			}
			else if($section=='billop') 
			{
				$departQuery = "SELECT outer_vsl_forward_info.id AS vvd_gkey, outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name AS vsl_name,
				CONCAT(date_of_arrival,' ',time_of_arrival) AS ata, CONCAT(date_of_departure,' ',time_of_departure) AS atd,
				grt,nrt,flag AS cntry_name, vsl_visit_id as vs_id,
				outer_vsl_info.loa*100 AS loa_cm,
				agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat,
				outer_vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM outer_vsl_visit_info
				INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
				WHERE cchaportdb.outer_vsl_forward_info.sr_acnt_forward_stat='1' 
				AND cchaportdb.outer_vsl_forward_info.vsl_frwd_letter_no='$letter_id'
				AND bill_op_user_id='$login_id' AND cchaportdb.outer_vsl_forward_info.billop_bill_stat='0'";
			}	
			//return;
			$departData = $this->bm->dataSelectDB1($departQuery);

			
			$data['departData']=$departData;
			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);

			$data['billOpListRslt']=$billOpListRslt;
			$data['filedt']=$filedt;
			$data['fileNo']=$fileNo;
			$data['filesub']=$filesub;
			$data['letter_id']=$letter_id;
			$data['title']=$title;
			//$data['toDate']=$toDate;
			$data['section']=$section;
			$data['flag'] = 1;
			$data['msg'] = "";
			$data['login_id']=$login_id;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselForwardingForAccBill_NotEntering',$data);
			$this->load->view('jsAssets');
		}
			
	}
	
	
	function vesselForwardingForAccBillAction_NotEntering()
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
			$section = $this->session->userdata('section');
			$fileNo = $this->input->post("file_no");
			$filedt = $this->input->post("file_dt");
			$filesub = $this->input->post("file_sub");
			//$no_vsl = $this->input->post("no_vsl");
			$msg="";
			if($section=='acc')
			{
				if(isset($_POST['idchk']))
				{
					$rotationChk = $_POST['idchk'];
				}
				$k=0;
				
				$letter_id = $this->input->post("letter_id");
				$billOp = $this->input->post("billOp");
				foreach ($rotationChk as $rCheck)
				{
					  $updateSt="UPDATE cchaportdb.outer_vsl_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),
								sr_acnt_forward_ip='$ipAddress', bill_op_user_id='$billOp'  WHERE vsl_visit_id='$rCheck'";
								
					$update_st = $this->bm->dataUpdateDB1($updateSt);
					$k++;
				} 
				
				//return;
				
				if($update_st>0)
				{
					$msg = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
				}
				else
				{
					$msg = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
				}
				
				$title="Vessel Forwarding by Accountant";	
				$departQuery = "SELECT outer_vsl_forward_info.id AS vvd_gkey, outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name AS vsl_name,
				CONCAT(date_of_arrival,' ',time_of_arrival) AS ata, CONCAT(date_of_departure,' ',time_of_departure) AS atd,
				grt,nrt,flag AS cntry_name,  vsl_visit_id as vs_id,
				outer_vsl_info.loa*100 AS loa_cm, 
				agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat,
				outer_vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM outer_vsl_visit_info
				INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
				WHERE cchaportdb.outer_vsl_forward_info.sr_acnt_forward_stat='0' 
				AND cchaportdb.outer_vsl_forward_info.vsl_frwd_letter_no='$letter_id'";
				//return;									
			}
			else if($section=='billop')
			{
				
			}
			$departData = $this->bm->dataSelectDB1($departQuery);
			$data['departData']=$departData;
			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);

			$data['billOpListRslt']=$billOpListRslt;
			$data['filedt']=$filedt;
			$data['fileNo']=$fileNo;
			$data['filesub']=$filesub;
			$data['letter_id']=$letter_id;
			$data['title']=$title;
			//$data['toDate']=$toDate;
			$data['section']=$section;
			$data['flag'] = 1;
			$data['msg'] = $msg;
			$data['login_id']=$login_id;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselForwardingForAccBill_NotEntering',$data);
			$this->load->view('jsAssets');
		}			
	}
	
	
	// function vesselForwardingbyMasterList($action = null)
	// {
	// 	$session_id = $this->session->userdata('value');
	// 	$LoginStat = $this->session->userdata('LoginStat');
	// 	//Menu Expanding....
	// 	$this->session->set_userdata(array('menu' => "bill"));
	// 	$this->session->set_userdata(array('sub_menu' => "vesselForwardingbyMasterList"));
	// 	//Menu Expanding....
	
	// 	if($LoginStat!="yes")
	// 	{
	// 		$this->logout();
	// 	}
	// 	else
	// 	{
	// 		$login_id = $this->session->userdata('login_id');
	// 		$ipAddress = $_SERVER['REMOTE_ADDR'];

	// 		$org_Type_id =$this->session->userdata('org_Type_id');
	// 		$section = $this->session->userdata('section');
			
	// 		$data['msg'] = "";
			
	// 		//$fromDate = $this->input->post("fromDate");
	// 		//$toDate = $this->input->post("toDate");
			
	// 		$data['title']="Vessel Forwarding Letter";
	// 		if($section=='acc')
	// 		{
	// 			$Query = "SELECT letter_no,CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl,
	// 					(SELECT COUNT(*)  FROM ctmsmis.vsl_forward_info 
	// 					WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no 
	// 					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no

	// 					FROM  (SELECT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl
	// 					FROM ctmsmis.vsl_frwrd_letter_info) AS tbl  ORDER BY letter_no DESC";
	// 		}
	// 		else if($section=='billop')
	// 		{
	// 					$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl,
	// 					(SELECT COUNT(*)  FROM ctmsmis.vsl_forward_info 
	// 					WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no 
	// 					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no

	// 					FROM  ( SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl
	// 					FROM ctmsmis.vsl_frwrd_letter_info
	// 					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
	// 					WHERE  vsl_forward_info.bill_op_user_id='$login_id') AS tbl  ORDER BY letter_no DESC";
	// 				//return;	
	// 		}
	// 		else
	// 		{
	// 			$Query = "SELECT vsl_frwrd_letter_info.id as letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl 
	// 					FROM ctmsmis.vsl_frwrd_letter_info ORDER BY vsl_frwrd_letter_info.id DESC";
	// 		}
	// 		// echo $Query;return;
	// 		$letterList = $this->bm->dataSelect($Query);

	// 		$data['letterList']=$letterList;
	// 		$data['login_id']=$login_id;
	// 		$data['flag'] = 1;
	// 		$data['org_Type_id']=$org_Type_id;
	// 		$data['section']=$section;
	// 		$this->load->view('cssAssetsList');
	// 		$this->load->view('headerTop');
	// 		$this->load->view('sidebar');
	// 		$this->load->view('Vessel/vesselForwardingbyMasterList',$data);			
	// 		$this->load->view('jsAssetsList');
	// 	}
	// }

	function vesselForwardingbyMasterList($action = null)
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
			$section = $this->session->userdata('section');
			
			$data['msg'] = "";
			
		
			if(is_null($action))
			{
				$data['title']="Vessel Forwarding Letter";
			}
			else
			{
				$type = $action=='Vatiary'?'Bhatiary':'Kutubdia';
				$data['title']="Vessel Forwarding Letter ($type)";
			}

			$Query = "";

			if($action == "Vatiary")
			{
				if($section=='acc')
				{
					$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl, vsl_category,
					(SELECT COUNT(*) FROM ctmsmis.vsl_forward_info WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
					FROM (SELECT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl, vsl_forward_info.vsl_category
					FROM ctmsmis.vsl_frwrd_letter_info INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no = ctmsmis.vsl_frwrd_letter_info.id 
					WHERE ctmsmis.vsl_forward_info.vsl_category='LPG') AS tbl ORDER BY letter_no DESC";
					//echo  $Query; return;
				}
				else if($section=='billop')
				{
					$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl, vsl_category,
					(SELECT COUNT(*)  FROM ctmsmis.vsl_forward_info 
					WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no 
					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
					FROM  ( SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl, vsl_forward_info.vsl_category
					FROM ctmsmis.vsl_frwrd_letter_info
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
					WHERE  vsl_forward_info.bill_op_user_id='$login_id' AND ctmsmis.vsl_forward_info.vsl_category='LPG') AS tbl  ORDER BY letter_no DESC";
						
				}
				else
				{
					$Query = "SELECT vsl_frwrd_letter_info.id AS letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl, vsl_category 
					FROM ctmsmis.vsl_frwrd_letter_info 
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
					WHERE ctmsmis.vsl_forward_info.vsl_category='LPG'
					ORDER BY vsl_frwrd_letter_info.id DESC";
				}
			}

			// if($action == "Vatiary")
			// {
				if($section=='acc')
				{
					$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl, vsl_category,stmt_month,
					(SELECT COUNT(*) FROM ctmsmis.vsl_forward_info WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
					FROM (SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl, vsl_forward_info.vsl_category,stmt_month
					FROM ctmsmis.vsl_frwrd_letter_info INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no = ctmsmis.vsl_frwrd_letter_info.id 
					WHERE ctmsmis.vsl_forward_info.vsl_category='LPG') AS tbl ORDER BY letter_no DESC";
				}
			// 	else if($section=='billop')
			// 	{
			// 		$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl, vsl_category,stmt_month,
			// 		(SELECT COUNT(*)  FROM ctmsmis.vsl_forward_info 
			// 		WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no 
			// 		AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
			// 		FROM  ( SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl, vsl_forward_info.vsl_category,stmt_month
			// 		FROM ctmsmis.vsl_frwrd_letter_info
			// 		INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
			// 		WHERE  vsl_forward_info.bill_op_user_id='$login_id' AND ctmsmis.vsl_forward_info.vsl_category='LPG') AS tbl  ORDER BY letter_no DESC";
			// 			//return;	
			// 	}
			// 	else
			// 	{
			// 		$Query = "SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl, vsl_category,stmt_month
			// 		FROM ctmsmis.vsl_frwrd_letter_info 
			// 		INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
			// 		WHERE ctmsmis.vsl_forward_info.vsl_category='LPG'
			// 		ORDER BY vsl_frwrd_letter_info.id DESC";
			// 	}
			// }

			else if($action == "Kutubdia")
			{
				if($section=='acc')
				{
					$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl, vsl_category, 
					(SELECT COUNT(*) FROM ctmsmis.vsl_forward_info WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
					FROM (SELECT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl, vsl_forward_info.vsl_category
					FROM ctmsmis.vsl_frwrd_letter_info 
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no = ctmsmis.vsl_frwrd_letter_info.id 
					WHERE ctmsmis.vsl_forward_info.vsl_category='KUTUBDIA') AS tbl ORDER BY letter_no DESC";
				}
				else if($section=='billop')
				{
					$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl, vsl_category,
					(SELECT COUNT(*)  FROM ctmsmis.vsl_forward_info 
					WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no 
					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
					FROM  ( SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl, vsl_forward_info.vsl_category
					FROM ctmsmis.vsl_frwrd_letter_info
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
					WHERE  vsl_forward_info.bill_op_user_id='$login_id' AND ctmsmis.vsl_forward_info.vsl_category='KUTUBDIA') AS tbl  ORDER BY letter_no DESC";
						//return;	
				}
				else
				{
					$Query = "SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl, vsl_category,stmt_month
					FROM ctmsmis.vsl_frwrd_letter_info 
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
					WHERE ctmsmis.vsl_forward_info.vsl_category='KUTUBDIA'
					ORDER BY vsl_frwrd_letter_info.id DESC";
				}
			}
			else
			{
				if($section=='acc')
				{
					$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl, vsl_category,
					(SELECT COUNT(*) FROM ctmsmis.vsl_forward_info WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
					FROM (SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl, vsl_forward_info.vsl_category
					FROM ctmsmis.vsl_frwrd_letter_info INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no = ctmsmis.vsl_frwrd_letter_info.id 
					WHERE ctmsmis.vsl_forward_info.vsl_category='CONTAINER') AS tbl ORDER BY letter_no DESC";
				}
				else if($section=='billop')
				{
					$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl, vsl_category,
					(SELECT COUNT(*)  FROM ctmsmis.vsl_forward_info 
					WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no 
					AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
					FROM  ( SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl, vsl_forward_info.vsl_category
					FROM ctmsmis.vsl_frwrd_letter_info
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
					WHERE  vsl_forward_info.bill_op_user_id='$login_id' AND ctmsmis.vsl_forward_info.vsl_category='CONTAINER') AS tbl  ORDER BY letter_no DESC";
						//return;	
				}
				else
				{
					$Query = "SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl, vsl_category
					FROM ctmsmis.vsl_frwrd_letter_info 
					INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
					WHERE ctmsmis.vsl_forward_info.vsl_category='CONTAINER'
					ORDER BY vsl_frwrd_letter_info.id DESC";
				}
			}
			// echo $Query;return;
			$letterList = $this->bm->dataSelectDb2($Query);
			$data['letterList']=$letterList;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;
			$data['section']=$section;
			$data['action']=$action;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselForwardingbyMasterList',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	// Letter List Not Entering(IGM)-- Sumon
	function vesselForwardingbyMasterListNotEntering()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		// $this->session->set_userdata(array('menu' => "bill"));
		// $this->session->set_userdata(array('sub_menu' => "vesselForwardingbyMasterListNotEntering"));
		//Menu Expanding....
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			$org_Type_id =$this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');
			
			$data['msg'] = "";
			
			//$fromDate = $this->input->post("fromDate");
			//$toDate = $this->input->post("toDate");
			
			$data['title']="Vessel Forwarding Letter (Not Entering)";
			if($section=='acc')
			{
				$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl,stmt_month,
				(SELECT COUNT(*)  FROM cchaportdb.outer_vsl_forward_info 
				WHERE cchaportdb.outer_vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no 
				AND cchaportdb.outer_vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
				FROM  (SELECT DISTINCT outer_visit_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,stmt_month
				FROM cchaportdb.outer_visit_frwrd_letter_info
				INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_frwd_letter_no=outer_visit_frwrd_letter_info.id
				INNER JOIN outer_vsl_visit_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
				WHERE remarks != 'BEACHED') AS tbl  ORDER BY letter_no DESC";
			}
			else if($section=='billop')
			{
				$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl,stmt_month,
				(SELECT COUNT(*)  FROM cchaportdb.outer_vsl_forward_info 
				WHERE cchaportdb.outer_vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no 
				AND cchaportdb.outer_vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
				FROM  (SELECT distinct outer_visit_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,stmt_month
				FROM cchaportdb.outer_visit_frwrd_letter_info
				INNER JOIN cchaportdb.outer_vsl_forward_info ON cchaportdb.outer_vsl_forward_info.vsl_frwd_letter_no=cchaportdb.outer_visit_frwrd_letter_info.id
				WHERE  outer_vsl_forward_info.bill_op_user_id='$login_id') AS tbl  ORDER BY letter_no DESC";
			}
			else
			{
				// $Query = "SELECT outer_visit_frwrd_letter_info.id as letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl 
				// FROM cchaportdb.outer_visit_frwrd_letter_info ORDER BY outer_visit_frwrd_letter_info.id DESC";

				$Query = "SELECT DISTINCT outer_visit_frwrd_letter_info.id AS letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) as file_dt,file_sub,file_no,no_vsl,stmt_month
				FROM cchaportdb.outer_visit_frwrd_letter_info
				INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_frwd_letter_no=outer_visit_frwrd_letter_info.id
				INNER JOIN outer_vsl_visit_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
				WHERE remarks != 'BEACHED' ORDER BY letter_no DESC";
			}

			$letterList = $this->bm->dataSelectDB1($Query);

			$data['letterList']=$letterList;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;
			$data['section']=$section;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselForwardingbyMasterListNotEntering',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	
    function vesselForwardingLetter($stmt = null)
	{
		$fileNo = $this->input->post("file_no");
		$filedt = $this->input->post("file_dt");
		$filesub = $this->input->post("file_sub");
		$no_vsl = $this->input->post("no_vsl");
		$letter_id = $this->input->post("letter_id");
		$action = $this->input->post("action");
		/* $this->load->library('m_pdf');
		$mpdf->use_kwt = true;
		$mpdf->simpleTables = true;	 */
		$departQuery = "SELECT DISTINCT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
		DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
		ctmsmis.vsl_vssel_info.loa_cm,
		ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
		ctmsmis.vsl_vssel_info.agent,
		ctmsmis.vsl_vssel_info.cntry_name, 				
	    CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
		CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
		
		ctmsmis.vsl_vssel_info.ib_vyg, vsl_forward_info.master_forward_by, vsl_forward_info.vvd_gkey,
		ctmsmis.vsl_forward_info.marine_forward_at as forwarded_dt
		FROM ctmsmis.vsl_forward_info
        INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
		WHERE  ctmsmis.vsl_forward_info.vsl_frwd_letter_no='$letter_id'";
		// echo $departQuery;return;
				

		$departData = $this->bm->dataSelectDb2($departQuery);
		if($stmt == "stmt")
		{
			$this->data['departData']=$departData;
			$this->data['fileNo']=$fileNo;
			$this->data['filedt']=$filedt;
			$this->data['filesub']=$filesub;
			$this->data['no_vsl']=$no_vsl;
			$this->data['action']=$action;
			$this->data['stmt_month']=$stmt_month = $this->input->post("stmt_month");
			// $this->load->view('vesselForwardingStatementForBeached',$data);

			$this->load->library('m_pdf');
			
			//load the pdf_output.php by passing our data and get all data in $html varriable.
			$html=$this->load->view('Vessel/vesselForwardingStatementForVatiaryKutubdia',$this->data, true); 
			//var_dump($html);
			
			$pdfFilePath ="vesselForwardingStatementForVatiaryKutubdia-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			//Follwing line is commented to show bangla font in PDF
			//$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
			
			
			// please follow : https://mpdf.github.io/reference/mpdf-functions/addpage.html
			// L - landscape, P - portrait
			$pdf->AddPage(
				'', // orientation L|P
				'', // type = E|O|even|odd|next-odd|next-even
				'', // resetpagenum = 1 - ∞
				'', // pagenumstyle = 1|A|a|I|i
				'', // suppress = on|off|1|0
				40, // margin_left
				2, // margin right
				5, // margin top
				2, // margin bottom
				10, // margin header
				15, // margin footer
				'', // odd-header-name
				'', // even-header-name
				'', // odd-footer-name
				'', // even-footer-name
				0, // odd-header-value
				0, // even-header-value
				0, // odd-footer-value
				0, // even-footer-value
				'', // pageselector ... Select a named CSS @page.
				'Legal-L' //Sheet size...array can be set also, like [210,297]
			); 
			
			// $footer = array (
			// 	'odd' => array (
			// 		'L' => array (
			// 			'content' => '',
			// 			'font-size' => 8,
			// 			'font-style' => 'B',
			// 			'font-family' => 'serif',
			// 			'color'=>'#000000'
			// 		),
			// 		'C' => array (
			// 			'content' => '',
			// 			'font-size' => 8,
			// 			'font-style' => 'B',
			// 			'font-family' => 'serif',
			// 			'color'=>'#000000'
			// 		),
			// 		'R' => array (
			// 			'content' => 'page {PAGENO} of '.$totalPage,
			// 			'font-size' => 8,
			// 			'font-style' => 'B',
			// 			'font-family' => 'serif',
			// 			'color'=>'#000000'
			// 		),
			// 		'line' => 0, // 1= draw footer border, 0 = don't draw footer border
			// 	),
			// 	'even' => array ()
			// );
			// $pdf->setFooter($footer);
			
			$pdf->shrink_tables_to_fit = 1;
			$pdf->WriteHTML($stylesheet,1);

			$pdf->WriteHTML($html,2);

			// return;
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		}
		else
		{
			$data['departData']=$departData;
			$data['fileNo']=$fileNo;
			$data['filedt']=$filedt;
			$data['filesub']=$filesub;
			$data['no_vsl']=$no_vsl;
			$data['action']=$action;
			$this->load->view('Vessel/vesselForwardingLetterView',$data);
		}
	}
	
	function vesselForwardingLetter_NotEntering($stmt = null)
	{
		$fileNo = $this->input->post("file_no");
		$filedt = $this->input->post("file_dt");
		$filesub = $this->input->post("file_sub");
		$no_vsl = $this->input->post("no_vsl");
		$letter_id = $this->input->post("letter_id");
		/* $this->load->library('m_pdf');
		$mpdf->use_kwt = true;
		$mpdf->simpleTables = true;	 */
		$departQuery = "SELECT outer_vsl_forward_info.id AS vvd_gkey, outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name AS vsl_name,
		CONCAT(DATE_FORMAT(date_of_arrival,'%d/%m/%Y'),' ',TIME(time_of_arrival)) AS ata, CONCAT(DATE_FORMAT(date_of_departure,'%d/%m/%Y'),' ',time_of_departure) AS atd,
		grt,nrt,flag AS cntry_name, vsl_visit_id as vs_id,
		outer_vsl_info.loa*100 AS loa_cm,
		agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat,
		CONCAT(DATE_FORMAT(outer_vsl_forward_info.marine_forward_at,'%d/%m/%Y'),' ',TIME(outer_vsl_forward_info.marine_forward_at)) AS forwarded_dt
		FROM outer_vsl_visit_info
		INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
		INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
		INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
		WHERE cchaportdb.outer_vsl_forward_info.vsl_frwd_letter_no='$letter_id'";
		//echo $departQuery;
		// return;	 

		$departData = $this->bm->dataSelectDB1($departQuery);

		if($stmt == "stmt")
		{
			$this->data['departData']=$departData;
			$this->data['fileNo']=$fileNo;
			$this->data['filedt']=$filedt;
			$this->data['filesub']=$filesub;
			$this->data['no_vsl']=$no_vsl;
			$this->data['stmt_month']=$stmt_month = $this->input->post("stmt_month");
			// $this->load->view('vesselForwardingStatementForBeached',$data);

			$this->load->library('m_pdf');
			
			//load the pdf_output.php by passing our data and get all data in $html varriable.
			$html=$this->load->view('vesselForwardingStatementNotEntering',$this->data, true); 
			//var_dump($html);
			
			$pdfFilePath ="vesselForwardingStatementNotEntering-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			//Follwing line is commented to show bangla font in PDF
			//$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
			
			
			// please follow : https://mpdf.github.io/reference/mpdf-functions/addpage.html
			// L - landscape, P - portrait
			$pdf->AddPage(
				'', // orientation L|P
				'', // type = E|O|even|odd|next-odd|next-even
				'', // resetpagenum = 1 - ∞
				'', // pagenumstyle = 1|A|a|I|i
				'', // suppress = on|off|1|0
				40, // margin_left
				2, // margin right
				10, // margin top
				5, // margin bottom
				10, // margin header
				15, // margin footer
				'', // odd-header-name
				'', // even-header-name
				'', // odd-footer-name
				'', // even-footer-name
				0, // odd-header-value
				0, // even-header-value
				0, // odd-footer-value
				0, // even-footer-value
				'', // pageselector ... Select a named CSS @page.
				'Legal-L' //Sheet size...array can be set also, like [210,297]
			); 
			
			$footer = array (
				'odd' => array (
					'L' => array (
						'content' => '',
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'C' => array (
						'content' => '',
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'R' => array (
						'content' => 'page {PAGENO} of '.$totalPage,
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'line' => 0, // 1= draw footer border, 0 = don't draw footer border
				),
				'even' => array ()
			);
			$pdf->setFooter($footer);
			
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		}
		else
		{
			$data['departData']=$departData;
			$data['fileNo']=$fileNo;
			$data['filedt']=$filedt;
			$data['filesub']=$filesub;
			$data['no_vsl']=$no_vsl;
			$this->load->view('Vessel/vesselForwardingLetterView_NotEntering',$data);
		}
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
				else if ($login_id=='acc') //Accountant
				{
					$data['title']="Vessel Forwarding by Accountant (Apps)";
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
					AND vsl_forward_info.sr_acnt_forward_stat='1' AND vsl_forward_info.billop_bill_stat='0'
					ORDER BY doc_vsl_depart.mooring_to_time DESC";
				}					
			}
			$departData = $this->bm->dataSelectDB1($departQuery);

			$data['departData']=$departData;
			$data['fromDate']=$fromDate;
			$data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselForwardingbyMarineByApp',$data);			
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
			$this->load->view('Vessel/vesselForwardingbyMasterListApps',$data);			
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
		$this->load->view('Vessel/vesselForwardingAppsLetterView',$data);		
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
				if($section=='acc') //Sr. Accountant 
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
				$this->load->view('Vessel/vesselForwardingbyMarineByApp',$data);			
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
				if($section=='acc') //Sr. Accountant
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
				else if ($login_id=='acc') //Accountant
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
			$this->load->view('Vessel/vesselForwardOuterAnchorage',$data);			
			$this->load->view('jsAssets');
		}
	}
	
	// forwarding - new start
	function vslNotEnteringForwardList()		// List
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		// $this->session->set_userdata(array('menu' => "VESSEL"));
		// $this->session->set_userdata(array('sub_menu' => "vslNotEnteringForwardList"));
		//Menu Expanding....
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			$org_Type_id =$this->session->userdata('org_Type_id');
			$section =$this->session->userdata('section');
			
			$data['msg'] = "";
			$masterFlag = "";
			$departQuery="";
			// $fromDate = $this->input->post("fromDate");
			// $toDate = $this->input->post("toDate");
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Vessels Not Entering - Forwarding by Marine";

				if($login_id == '23026') // Farzana
				{		
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(DATE_FORMAT(date_of_arrival,'%d/%m/%Y'),' ',time_of_arrival) AS ata,CONCAT(DATE_FORMAT(date_of_departure,'%d/%m/%Y'),' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,'' AS forwarded_dt
					FROM outer_vsl_visit_info
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					WHERE outer_vsl_visit_info.id NOT IN (SELECT vsl_visit_id FROM outer_vsl_forward_info) AND agent_entry_approve_flag='1'
					ORDER BY date_of_arrival DESC";
				}
				else if($login_id == '24087') // Fardaus
				{
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(DATE_FORMAT(date_of_arrival,'%d/%m/%Y'),' ',time_of_arrival) AS ata,CONCAT(DATE_FORMAT(date_of_departure,'%d/%m/%Y'),' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat,outer_vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM outer_vsl_visit_info
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
					WHERE outer_vsl_forward_info.marine_forward_stat='1' AND outer_vsl_forward_info.svtmis_forward_stat='0'
					ORDER BY date_of_arrival DESC";
				}
				else if($login_id == '12369') // Habib
				{
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(DATE_FORMAT(date_of_arrival,'%d/%m/%Y'),' ',time_of_arrival) AS ata,CONCAT(DATE_FORMAT(date_of_departure,'%d/%m/%Y'),' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat,outer_vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM outer_vsl_visit_info
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
					WHERE outer_vsl_forward_info.marine_forward_stat='1' AND outer_vsl_forward_info.svtmis_forward_stat='1' AND outer_vsl_forward_info.hob_forward_stat='0'
					ORDER BY date_of_arrival DESC";
				}
				
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
				
				// $departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,outer_vsl_forward_info.marine_forward_stat,outer_vsl_forward_info.master_forward_stat,outer_vsl_forward_info.sr_acnt_forward_stat,outer_vsl_forward_info.billop_bill_stat,outer_vsl_forward_info.marine_forward_at AS forwarded_dt
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
				WHERE outer_vsl_forward_info.marine_forward_stat='1' AND outer_vsl_forward_info.svtmis_forward_stat='1' AND outer_vsl_forward_info.hob_forward_stat='1' AND outer_vsl_forward_info.master_forward_stat='0'
				ORDER BY date_of_arrival DESC";
			}
			else if($org_Type_id=='82') // Accounts
			{
				if($section=='acc') //Sr. Accountant
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


			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			// $this->load->view('vesselForwardOuterAnchorage',$data);			
			$this->load->view('Vessel/vesselForwardList_notEntering',$data);			
			$this->load->view('jsAssets');
		}
	}
	
	function vslNotEnteringForwardingPerform()
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
			$section =$this->session->userdata('section');
			$org_Type_id =$this->session->userdata('org_Type_id');
			$remarks = $this->input->post("remarks");
			
			$departQuery="";
			$masterFlag="";
			// echo "ok...";return;
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
				
				$ignoredId = array(24087,12369);
				if(!in_array($login_id,$ignoredId))
				{
					$lotInsFlag = 0;
					$insertLotInfo = "INSERT INTO vsl_forward_lot_info(lot_sl,lot_dt,lot_id,tot_vsl,forward_at,forward_by,vsl_lot_type)
									VALUES('$nextLotSl',CURDATE(),'$nextLotId','$totVsl',NOW(),'$login_id','Not Entering')";	
					$lotInsFlag = $this->bm->dataInsertDB1($insertLotInfo);

					
					if($lotInsFlag == 0)
					{
						echo "<font color='red'>Lot not created</font>";
						return;
					}
				}
				
				$sql_lotId = "SELECT MAX(id) AS rtnValue FROM vsl_forward_lot_info";
				$lotId = $this->bm->dataReturnDb1($sql_lotId);
				// echo $lotId;return;
				// lot data - end
				
				foreach ($rotationChk as $rCheck)
				{
					//echo $rCheck; return;
					// $chk_str="SELECT COUNT(*) AS rtnValue from ctmsmis.vsl_forward_info WHERE  vvd_gkey='$rCheck'";
					$chk_str="SELECT COUNT(*) AS rtnValue from outer_vsl_forward_info WHERE  vsl_visit_id='$rCheck'";
					$ckh_st = $this->bm->dataReturnDb1($chk_str);
					$resInsertst=0;
					if($ckh_st=='0')
					{
						$remarksQuery = "SELECT forward_remarks FROM outer_vsl_visit_info WHERE id = '$rCheck'";
						$remarksData = $this->bm->dataSelectDB1($remarksQuery);
						$prevRemarks = "";
						if(count($remarksData)>0){
							$prevRemarks = $remarksData[0]['forward_remarks'];
						}
						
						$strInsert = "INSERT INTO outer_vsl_forward_info(vsl_fwd_lot_info_id,vsl_visit_id, marine_forward_stat, marine_forward_by, marine_forward_at,  marine_forward_ip) 
						VALUES('$lotId','$rCheck', '1', '$login_id', NOW(), '$ipAddress')";
						//$resInsertst = $this->bm->dataInsertDB1($strInsert);

						if($resInsertst = $this->bm->dataInsertDB1($strInsert)){
							$logQuery = "INSERT INTO outer_vsl_visit_change_log(vsl_visit_tbl_id,column_name,prev_val,new_val,change_by,change_at,change_ip) VALUES('$rCheck','forward_remarks','$prevRemarks','$remarks','$login_id',NOW(),'$ipAddress')";
							$this->bm->dataInsertDB1($logQuery);

							$updateQuery = "UPDATE outer_vsl_visit_info SET forward_remarks = '$remarks', update_at = NOW() , update_by = '$login_id' , update_ip = '$ipAddress' WHERE id = '$rCheck'";
							$this->bm->dataUpdateDB1($updateQuery);
						}
					}
					else
					{
						if($login_id == 24087)
						{
							$strInsert = "UPDATE outer_vsl_forward_info SET svtmis_forward_stat = 1 , svtmis_forward_by = '$login_id' , svtmis_forward_at = NOW() , svtmis_forward_ip = '$ipAddress' WHERE vsl_visit_id = '$rCheck'";
							$resInsertst = $this->bm->dataInsertDB1($strInsert);
						}
						else if($login_id == 12369)
						{
							$strInsert = "UPDATE outer_vsl_forward_info SET hob_forward_stat = 1 , hob_forward_by = '$login_id' , hob_forward_at = NOW() , hob_forward_ip = '$ipAddress' WHERE vsl_visit_id = '$rCheck'";
							$resInsertst = $this->bm->dataInsertDB1($strInsert);
						}
					}

					$k++;
				}

				// if($resInsertst>0)
				// {
				// 	$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
				// }
				// else
				// {
				// 	$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
				// }	
				
				if($resInsertst>0)
				{
					$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					$this->session->set_flashdata("success", "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>$k Vessel forwarded succesfully</font></div>");
				}
				else
				{
					$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
				}
				
				// $departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
				// FROM outer_vsl_visit_info
				// INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				// INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				// WHERE outer_vsl_visit_info.id NOT IN (SELECT vsl_visit_id FROM outer_vsl_forward_info) ORDER BY date_of_arrival DESC";
				
				// $departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,'' AS forwarded_dt
				// FROM outer_vsl_visit_info
				// INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				// INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				// WHERE outer_vsl_visit_info.id NOT IN (SELECT vsl_visit_id FROM outer_vsl_forward_info) AND agent_entry_approve_flag='1'
				// ORDER BY date_of_arrival DESC";
			}
			else if($org_Type_id=='81')		//master
			{
				$masterFlag = "master";
				$data['title']="Vessels Not Entering - Forwarding by Harbaour Master";
				// $fileNo = $this->input->post("fileNo");
				// $filedt = $this->input->post("filedt");
				$filesub = $this->input->post("filesub");
				$noVsl=count($rotationChk);
				// echo $noVsl;return;
				// $chk_str="SELECT count(*) AS rtnValue from outer_visit_frwrd_letter_info WHERE file_no='$fileNo' ";
				// $chk_st = $this->bm->dataReturnDb1($chk_str);
				// if($chk_st>0)
				// {
					// $data['msg'] = "<font color='red' size=2>You already used this File no. Use new one.</font>";
				// }
				// else
				// {
					// file sl and date - start
					$sql_nextFileSl = "SELECT IFNULL(MAX(file_sl),0)+1 AS rtnValue
					FROM outer_visit_frwrd_letter_info";
					// echo $sql_nextFileSl;
					$nextFileSl = $this->bm->dataReturnDb1($sql_nextFileSl);
					// echo $nextFileSl;return;
					$fileNo = "ডিসি/বিএস/সিএস/".$nextFileSl;
					
					// $insert_str="INSERT INTO outer_visit_frwrd_letter_info (file_dt, file_sub, file_no, no_vsl)
					// VALUES ('$filedt', '$filesub','$fileNo', '$noVsl')";	
					$stmt_month = date("m");
					$insert_str="INSERT INTO outer_visit_frwrd_letter_info (file_dt,file_sub,file_sl,file_no, no_vsl,stmt_month)
					VALUES (DATE(NOW()),'$filesub','$nextFileSl','$fileNo','$noVsl','$stmt_month')";					
					$insert_st = $this->bm->dataInsertDB1($insert_str);
					// file sl and date - end

					$k=0;
					$str="SELECT id AS rtnValue
					FROM outer_visit_frwrd_letter_info
					WHERE file_no='$fileNo'
					ORDER BY id DESC
					LIMIT 1";
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
					// if($update_st>0)
					// {
					// 	$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					// }
					// else
					// {
					// 	$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					// }

					if($update_st>0)
					{
						$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
						$this->session->set_flashdata("success", "<div class='alert alert-success'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>$k Vessel forwarded succesfully</font></div>");
					}
					else
					{
						$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
					}
				// }
				
				// $departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,outer_vsl_forward_info.marine_forward_at AS forwarded_dt
				// FROM outer_vsl_visit_info
				// INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				// INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				// INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
				// WHERE outer_vsl_forward_info.marine_forward_stat='1' AND outer_vsl_forward_info.master_forward_stat='0'
				// ORDER BY date_of_arrival DESC";
			}
			else if($org_Type_id=='82')
			{
				if($section=='acc') //Sr. Accountant 
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
					// if($update_st>0)
					// {
					// 	$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					// }
					// else
					// {
					// 	$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					// }

					if($update_st>0)
					{
						$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
						$this->session->set_flashdata("success", "<div class='alert alert-success'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>$k Vessel forwarded succesfully</font></div>");
					}
					else
					{
						$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
					}
					
					// $departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
					// FROM outer_vsl_visit_info
					// INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					// INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					// INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
					// WHERE outer_vsl_forward_info.master_forward_stat='1' AND outer_vsl_forward_info.sr_acnt_forward_stat='0'
					// ORDER BY date_of_arrival DESC";
				}
				// else if ($login_id=='acc') //Accountant 
				else if ($section=='billop')
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
					
					// $data['title']="Vessel Forwarding by Accountant";
					// $departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name
					// FROM outer_vsl_visit_info
					// INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					// INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					// INNER JOIN outer_vsl_forward_info ON outer_vsl_forward_info.vsl_visit_id=outer_vsl_visit_info.id
					// WHERE outer_vsl_forward_info.sr_acnt_forward_stat='1' AND outer_vsl_forward_info.billop_bill_stat='0'
					// ORDER BY date_of_arrival DESC";
				}				
			}

			redirect('Vessel/forwardedVslHistoryNotEntering/', 'refresh');

// 			$departData = $this->bm->dataSelectDB1($departQuery);

// 			$data['departData']=$departData;
// 			$data['masterFlag']=$masterFlag;
// 			$data['fromDate']=$fromDate;
// 			$data['toDate']=$toDate;
// 			$data['flag'] = 1;
// 			$data['login_id']=$login_id;
// 			$data['section']=$section;
// 			$data['org_Type_id']=$org_Type_id;
			
// 			$this->load->view('cssAssets');
// 			$this->load->view('headerTop');
// 			$this->load->view('sidebar');
// /* 			if($this->input->post('fwBtn'))
// 				$this->load->view('Vessel/vesselForwardingbyMarineForm',$data);
// 			else if($this->input->post('fwBtnOuter'))
// 				$this->load->view('vesselForwardOuterAnchorage',$data); */
// 			$this->load->view('Vessel/vesselForwardList_notEntering',$data);
// 			$this->load->view('jsAssets');
		}
		
	}
	// forwarding - new end
	
	// vsl function - ovi - start
	function vesselsNotEntering()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "vesselsNotEntering"));
		//Menu Expanding....
	
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
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "vesselsNotEnteringAgentForm"));
		//Menu Expanding....
	
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
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "visitedVesselsNotEntering"));
		//Menu Expanding....
	
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
				
				// $vslVisitInfoQuery = "SELECT * FROM outer_vsl_visit_info  WHERE delete_flag='0' ";
				// $vslVisitInfoData = $this->bm->dataSelectDB1($vslVisitInfoQuery);
				
				//
				if($org_Type_id=='83' && $login_id != "agent1" )
				{
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
				//
				
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
				// $this->load->view('outerVesselVistInfoListView',$data);
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
					// $sqlInsert="INSERT INTO outer_vsl_info(vsl_id,vsl_name,vsl_class,vsl_type,radio_call_sign,grt,nrt,flag,loa,imo,voyage_no,agent_id,
					// entry_at,entry_by,entry_ip)
					// VALUES ('$vesselId', '$vesselName', '$vesselClass','$vslType','$radioCallSign','$grt', '$nrt',
					// '$flag','$loa','$imo','$voyNo','$agentInfo',NOW(), '$login_id', '$ipaddress')";
					
					$sqlInsert="INSERT INTO outer_vsl_info(vsl_name,vsl_class,vsl_type,radio_call_sign,grt,nrt,flag,loa,imo,voyage_no,agent_id,
					entry_at,entry_by,entry_ip)
					VALUES ('$vesselName', '$vesselClass','$vslType','$radioCallSign','$grt', '$nrt',
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
				// echo "ok..";return;
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
				// $sql_matchVslData = "SELECT id,vsl_id,vsl_name,vsl_class,vsl_type,radio_call_sign,grt,nrt,flag,agent_id
								// FROM outer_vsl_info
								// WHERE id = '$updateVesselId'";
				$sql_matchVslData = "SELECT id,vsl_name,vsl_class,vsl_type,radio_call_sign,grt,nrt,flag,agent_id
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
				
				$updateQuery="UPDATE outer_vsl_info SET vsl_name='$vesselName',vsl_type='$vslType', radio_call_sign='$radioCallSign', grt='$grt',
				nrt='$nrt', flag='$flag',loa='$loa',imo='$imo',voyage_no='$voyNo',agent_id='$agentInfo',
				update_at=NOW(),update_by='$login_id',update_ip='$ipaddress' WHERE id='$updateVesselId'";
				// echo $updateQuery;return;
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
	
	/* function outerVesselVistInfoList()
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
			
			$cond = "";
			if($login_id == "agent1")
			{
				$cond = " AND agent_entry_flag='1'";
			}
			// $vslVisitInfoQuery = "SELECT * FROM outer_vsl_visit_info  WHERE delete_flag='0'";
			$vslVisitInfoQuery = "SELECT id,vsl_name,imp_rot,date_of_arrival,time_of_arrival,date_of_departure,time_of_departure,voyage_no,remarks,agent_entry_flag,
			agent_entry_approve_flag,entry_by
			FROM outer_vsl_visit_info
			WHERE delete_flag='0'".$cond;
			$vslVisitInfoData = $this->bm->dataSelectDB1($vslVisitInfoQuery);
			
			$data['title']="Vessel Visit List";
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
	} */
	
	function outerVesselVistInfoList()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "outerVesselVistInfoList"));
		//Menu Expanding....
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
			$vslVisitInfoQuery = "SELECT id,vsl_name,imp_rot,CONCAT(DATE_FORMAT(date_of_arrival,'%d/%m/%Y')) as date_of_arrival,time_of_arrival,
			CONCAT(DATE_FORMAT(date_of_departure,'%d/%m/%Y')) as date_of_departure,time_of_departure,voyage_no,remarks,agent_entry_flag,
			agent_entry_approve_flag,entry_by
			FROM outer_vsl_visit_info
			WHERE delete_flag='0'".$cond;
			// echo $vslVisitInfoQuery;return;
			$vslVisitInfoData = $this->bm->dataSelectDB1($vslVisitInfoQuery);
			
			$data['title']="Vessel Visit List (Pending Approval)";
			$data['vslVisitInfoData']=$vslVisitInfoData;

			$data['msg']="";
			$data['msg1']="";
			$data['msg2']="";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/outerVesselVistInfoListView',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function outerVesselVistInfoApprovedList()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "outerVesselVistInfoApprovedList"));
		//Menu Expanding....
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
			$vslVisitInfoQuery = "SELECT id,vsl_name,imp_rot,CONCAT(DATE_FORMAT(date_of_arrival,'%d/%m/%Y')) as date_of_arrival,time_of_arrival,
			CONCAT(DATE_FORMAT(date_of_departure,'%d/%m/%Y')) as date_of_departure,time_of_departure,voyage_no,remarks,agent_entry_flag,
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
	
	function noEntryAgentList(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "noEntryAgentList"));
		//Menu Expanding....
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
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "noEntryVesselList"));
		//Menu Expanding....
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else 
		{			
			$agentInfoQuery = "SELECT * FROM outer_agent_info WHERE delete_flag='0' ";
			$agentInfoData = $this->bm->dataSelectDB1($agentInfoQuery);
			
			// $vslInfoQuery = "SELECT outer_vsl_info.id, outer_vsl_info.vsl_name,outer_vsl_info.vsl_type,outer_vsl_info.radio_call_sign,outer_vsl_info.grt,outer_vsl_info.nrt,outer_vsl_info.flag,outer_agent_info.agent_name,outer_vsl_info.agent_id
			// FROM outer_vsl_info
			// LEFT JOIN outer_agent_info ON outer_vsl_info.agent_id=outer_agent_info.id
			// WHERE outer_vsl_info.delete_flag='0'";

			$vslInfoQuery = "SELECT DISTINCT outer_vsl_info.id, outer_vsl_info.vsl_name,outer_vsl_info.vsl_type,outer_vsl_info.radio_call_sign,
			outer_vsl_info.grt,outer_vsl_info.nrt,outer_vsl_info.flag,outer_agent_info.agent_name,outer_vsl_info.agent_id,
			IFNULL(marine_forward_stat,'') AS forward_done
			FROM outer_vsl_info
			LEFT JOIN outer_agent_info ON outer_vsl_info.agent_id=outer_agent_info.id
			LEFT JOIN outer_vsl_visit_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
			LEFT JOIN outer_vsl_forward_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
			WHERE outer_vsl_info.delete_flag='0' GROUP BY outer_vsl_info.id";

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
			
				$cond = "";
				if($login_id == "agent1")
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
				
				$data['title']="Vessel Visit List";
				
				$data['vslVisitInfoData']=$vslVisitInfoData;
				
				$data['msg']="";
				$data['msg1']="";
			    $data['msg2']="";
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('Vessel/outerVesselVistInfoListView',$data);
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
			else 
			{
				$login_id = $this->session->userdata('login_id');
                $deleteId=$this->input->post('deleteId');
				$ipaddress = $_SERVER['REMOTE_ADDR'];

				$getDataQuery = "SELECT * FROM outer_agent_info WHERE id = '$deleteId'";
				$getData = $this->bm->dataSelectDB1($getDataQuery);

				$id = "";
				$agent_code = "";
				$agent_name = "";
				$alias_id = "";
				$contact_name = "";
				$contact_address = "";
				$contact_city = "";
				$contact_email = "";
				$contact_country = "";
				$contact_phone = "";
				$entry_at = "";
				$entry_by = "";
				$entry_ip = "";
				$update_at = "";
				$update_by = "";
				$update_ip = "";

				for($i=0;count($getData)>$i;$i++){
					$id = $getData[$i]['id'];
					$agent_code = $getData[$i]['agent_code'];
					$agent_name = $getData[$i]['agent_name'];
					$alias_id = $getData[$i]['alias_id'];
					$contact_name = $getData[$i]['contact_name'];
					$contact_address = $getData[$i]['contact_address'];
					$contact_city = $getData[$i]['contact_city'];
					$contact_email = $getData[$i]['contact_email'];
					$contact_country = $getData[$i]['contact_country'];
					$contact_phone = $getData[$i]['contact_phone'];
					$entry_at = $getData[$i]['entry_at'];
					$entry_by = $getData[$i]['entry_by'];
					$entry_ip = $getData[$i]['entry_ip'];
					$update_at = $getData[$i]['update_at'];
					$update_by = $getData[$i]['update_by'];
					$update_ip = $getData[$i]['update_ip'];
				}

				$insertQuery = "INSERT INTO outer_agent_info_delete_log(outer_agent_info_id,agent_code,agent_name,alias_id,contact_name,contact_address,contact_city,contact_email,contact_country,contact_phone,entry_at,entry_by,entry_ip,update_at,update_by,update_ip,delete_at,delete_by,delete_ip) VALUES('$id','$agent_code','$agent_name','$alias_id','$contact_name','$contact_address','$contact_city','$contact_email','$contact_country','$contact_phone','$entry_at','$entry_by','$entry_ip','$update_at','$update_by','$update_ip',NOW(),'$login_id','$ipaddress')";

				if($this->bm->dataInsertDB1($insertQuery)){
					$deleteQuery="DELETE from outer_agent_info WHERE id='$deleteId'";
					if($this->bm->dataDeleteDB1($deleteQuery)){
						$msg = "<font size='3' color='green'>Deleted Successfully</font>";
					}
					else
					{
						$msg = "<font size='3' color='green'>Failed. Try again please.</font>";
					}
				}
				else
				{
					$msg = "<font size='3' color='red'>Agent delete failed.</font>";
				}

				//$DeleteQuery = "DELETE FROM outer_anchorage_vsl WHERE id='$deleteId'";
				// $this->bm->dataDeleteDB1($DeleteQuery);
				// $updateQuery="UPDATE outer_agent_info SET delete_flag='1', delete_at=NOW(), delete_by='$login_id',
				// 		delete_ip='$ipaddress' WHERE id='$deleteId'";
				// $update_st = $this->bm->dataUpdateDB1($updateQuery);
						
			$agentInfoQuery = "SELECT * FROM outer_agent_info WHERE delete_flag='0' ";
			$agentInfoData = $this->bm->dataSelectDB1($agentInfoQuery);
			$data['title']="Agent List";
			$data['agentInfoData']=$agentInfoData;
			$data['msg']=$msg;
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
		else 
		{
			$login_id = $this->session->userdata('login_id');
				$deleteId=$this->input->post('deleteId');
			$ipaddress = $_SERVER['REMOTE_ADDR'];

			$getDataQuery = "SELECT * FROM outer_vsl_info WHERE id = '$deleteId'";
			$getData = $this->bm->dataSelectDB1($getDataQuery);

			$id = "";
			$vsl_name = "";
			$vsl_class = "";
			$vsl_type = "";
			$radio_call_sign = "";
			$grt = "";
			$nrt = "";
			$flag = "";
			$loa = "";
			$imo = "";
			$voyage_no = "";
			$agent_id = "";
			$entry_at = "";
			$entry_by = "";
			$entry_ip = "";
			$update_at = "";
			$update_by = "";
			$update_ip = "";

			for($i=0;count($getData)>$i;$i++){
				$id = $getData[$i]['id'];
				$vsl_name = $getData[$i]['vsl_name'];
				$vsl_class = $getData[$i]['vsl_class'];
				$vsl_type = $getData[$i]['vsl_type'];
				$radio_call_sign = $getData[$i]['radio_call_sign'];
				$grt = $getData[$i]['grt'];
				$nrt = $getData[$i]['nrt'];
				$flag = $getData[$i]['flag'];
				$loa = $getData[$i]['loa'];
				$imo = $getData[$i]['imo'];
				$voyage_no = $getData[$i]['voyage_no'];
				$agent_id = $getData[$i]['agent_id'];
				$entry_at = $getData[$i]['entry_at'];
				$entry_by = $getData[$i]['entry_by'];
				$entry_ip = $getData[$i]['entry_ip'];
				$update_at = $getData[$i]['update_at'];
				$update_by = $getData[$i]['update_by'];
				$update_ip = $getData[$i]['update_ip'];
			}

			$insertQuery = "INSERT INTO outer_vsl_info_delete_log(outer_vsl_info_id,vsl_name,vsl_class,vsl_type,radio_call_sign,grt,nrt,flag,loa,imo,voyage_no,agent_id,entry_at,entry_by,entry_ip,update_at,update_by,update_ip,delete_at,delete_by,delete_ip) VALUES('$id','$vsl_name','$vsl_class','$vsl_type','$radio_call_sign','$grt','$nrt','$flag','$loa','$imo','$voyage_no','$agent_id','$entry_at','$entry_by','$entry_ip','$update_at','$update_by','$update_ip',NOW(),'$login_id','$ipaddress')";

			if($this->bm->dataInsertDB1($insertQuery)){
				$deleteQuery="DELETE from outer_vsl_info WHERE id='$deleteId'";
				if($this->bm->dataDeleteDB1($deleteQuery)){
					$msg = "<font size='3' color='green'>Deleted Successfully</font>";
				}
				else
				{
					$msg = "<font size='3' color='green'>Failed. Try again please.</font>";
				}
			}
			else
			{
				$msg = "<font size='3' color='red'>Vessel delete failed.</font>";
			}

			//$DeleteQuery = "DELETE FROM outer_anchorage_vsl WHERE id='$deleteId'";
			// $this->bm->dataDeleteDB1($DeleteQuery);
			// $updateQuery="UPDATE outer_vsl_info SET delete_flag='1', delete_at=NOW(), delete_by='$login_id',
			// 		delete_ip='$ipaddress' WHERE id='$deleteId'";
			// $update_st = $this->bm->dataUpdateDB1($updateQuery);
					
		
			
			$vslInfoQuery = "SELECT outer_vsl_info.id, outer_vsl_info.vsl_name,outer_vsl_info.radio_call_sign,outer_vsl_info.grt,outer_vsl_info.nrt,outer_vsl_info.flag,outer_agent_info.agent_name,outer_vsl_info.agent_id
			FROM outer_vsl_info
			INNER JOIN outer_agent_info ON outer_vsl_info.agent_id=outer_agent_info.id
			WHERE outer_vsl_info.delete_flag='0'";
			$vslInfoData = $this->bm->dataSelectDB1($vslInfoQuery);
			
			$data['title']="Vessel List";
			$data['vslInfoData']=$vslInfoData;
			$data['msg']="";
			$data['msg1']=$msg;
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

				$getDataQuery = "SELECT * FROM outer_vsl_visit_info WHERE id = '$deleteId'";
				$getData = $this->bm->dataSelectDB1($getDataQuery);

				$id = "";
				$outer_vsl_id = "";
				$vsl_name = "";
				$imp_rot = "";
				$date_of_arrival = "";
				$time_of_arrival = "";
				$date_of_departure = "";
				$time_of_departure = "";
				$voyage_no = "";
				$remarks = "";
				$entry_at = "";
				$entry_by = "";
				$entry_ip = "";
				$update_at = "";
				$update_by = "";
				$update_ip = "";
				$agent_entry_flag = "";
				$agent_entry_approve_flag = "";

				for($i=0;count($getData)>$i;$i++){
					$id = $getData[$i]['id'];
					$outer_vsl_id = $getData[$i]['outer_vsl_id'];
					$vsl_name = $getData[$i]['vsl_name'];
					$imp_rot = $getData[$i]['imp_rot'];
					$date_of_arrival = $getData[$i]['date_of_arrival'];
					$time_of_arrival = $getData[$i]['time_of_arrival'];
					$date_of_departure = $getData[$i]['date_of_departure'];
					$time_of_departure = $getData[$i]['time_of_departure'];
					$voyage_no = $getData[$i]['voyage_no'];
					$remarks = $getData[$i]['remarks'];
					$entry_at = $getData[$i]['entry_at'];
					$entry_by = $getData[$i]['entry_by'];
					$entry_ip = $getData[$i]['entry_ip'];
					$update_at = $getData[$i]['update_at'];
					$update_by = $getData[$i]['update_by'];
					$update_ip = $getData[$i]['update_ip'];
					$agent_entry_flag = $getData[$i]['agent_entry_flag'];
					$agent_entry_approve_flag = $getData[$i]['agent_entry_approve_flag'];
				}

				$insertQuery = "INSERT INTO outer_vsl_visit_info_delete_log(outer_vsl_visit_info_id,outer_vsl_id,vsl_name,imp_rot,date_of_arrival,time_of_arrival,date_of_departure,time_of_departure,voyage_no,remarks,entry_at,entry_by,entry_ip,update_at,update_by,update_ip,agent_entry_flag,agent_entry_approve_flag,delete_at,delete_by,delete_ip) VALUES('$id','$outer_vsl_id','$vsl_name','$imp_rot','$date_of_arrival','$time_of_arrival','$date_of_departure','$time_of_departure','$voyage_no','$remarks','$entry_at','$entry_by','$entry_ip','$update_at','$update_by','$update_ip','$agent_entry_flag','$agent_entry_approve_flag',NOW(),'$login_id','$ipaddress')";

				if($this->bm->dataInsertDB1($insertQuery)){
					$deleteQuery="DELETE from outer_vsl_visit_info WHERE id='$deleteId'";
					if($this->bm->dataDeleteDB1($deleteQuery)){
						$msg = "<font size='3' color='green'>Deleted Successfully</font>";
					}
					else
					{
						$msg = "<font size='3' color='green'>Failed. Try again please.</font>";
					}
				}
				else
				{
					$msg = "<font size='3' color='red'>Vessel Visit delete failed.</font>";
				}

				// Write log for delete in outer_vsl_visit_info_delete_log table
					// if log write is failed, give "Vessel Visit delete failed." message to user.
					// if log write is successfully, allow to delete.
						// if delete is successfully, give "Vessel Visit deleted successfully"
						// if delete is failed, give "Failed. Try again please."
				
				// Execute delete query, instead of updating delete_flag
				
				// $updateQuery="UPDATE outer_vsl_visit_info SET delete_flag='1', delete_at=NOW(), delete_by='$login_id',
				// 			delete_ip='$ipaddress' WHERE id='$deleteId'";
				// $update_st = $this->bm->dataUpdateDB1($updateQuery);
						
			
			$vslVisitInfoQuery = "SELECT * FROM outer_vsl_visit_info  WHERE delete_flag='0' ";
			$vslVisitInfoData = $this->bm->dataSelectDB1($vslVisitInfoQuery);
			
			$data['title'] = "Vessel Visit List";
			$data['vslVisitInfoData'] = $vslVisitInfoData;
			$data['msg'] = "";
			$data['msg1'] = "";
			$data['msg2'] = $msg;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/outerVesselVistInfoListView',$data);
			$this->load->view('jsAssets');

						
				 
            }

	}
	// vsl function - ovi - end
	
	// start outer anchorage vessel report
	
	function outerAnchorageVslReportForm(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "outerAnchorageVslReportForm"));
		//Menu Expanding....
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
			
			$contArrival = "SELECT vsl_vessel_visit_details.ib_vyg AS imp_rot,vsl_vessels.name AS vsl_name,
			to_char(argo_carrier_visit.ata,'yyyy-mm-dd') AS date_of_arrival,to_char(argo_carrier_visit.ata,'hh24:mi:ss' ) AS time_of_arrival,
			to_char(argo_carrier_visit.atd,'yyyy-mm-dd') AS date_of_departure,to_char(argo_carrier_visit.ata,'hh24:mi:ss') AS time_of_departure,
			ref_bizunit_scoped.name AS beaching_agent,ref_country.cntry_name AS flag,
			vsl_vessel_classes.gross_registered_ton AS grt,vsl_vessel_classes.net_registered_ton AS nrt,
			'' AS remarks,'Container Vessel' AS vsl_type
			FROM argo_carrier_visit
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			INNER JOIN vsl_vessel_classes ON vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey
			INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessels.owner_gkey
			INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
			WHERE to_char(argo_carrier_visit.ata,'yyyy-mm-dd') BETWEEN '$formDate' AND '$toDate'";
			
			$contDeparture = "SELECT vsl_vessel_visit_details.ib_vyg AS imp_rot,vsl_vessels.name AS vsl_name,
			to_char(argo_carrier_visit.ata,'yyyy-mm-dd') AS date_of_arrival,to_char(argo_carrier_visit.ata,'hh24:mi:ss') AS time_of_arrival,
			to_char(argo_carrier_visit.atd,'yyyy-mm-dd') AS date_of_departure,to_char(argo_carrier_visit.ata,'hh24:mi:ss') AS time_of_departure,
			ref_bizunit_scoped.name AS beaching_agent,ref_country.cntry_name AS flag,
			vsl_vessel_classes.gross_registered_ton AS grt,vsl_vessel_classes.net_registered_ton AS nrt,
			'' AS remarks,'Container Vessel' AS vsl_type
			FROM argo_carrier_visit
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			INNER JOIN vsl_vessel_classes ON vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey
			INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessels.owner_gkey
			INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
			WHERE to_char(argo_carrier_visit.atd,'yyyy-mm-dd') BETWEEN '$formDate' AND '$toDate'";
			
			$contAll = "SELECT vsl_vessel_visit_details.ib_vyg AS imp_rot,vsl_vessels.name AS vsl_name,
			to_char(argo_carrier_visit.ata,'yyyy-mm-ss') AS date_of_arrival,to_char(argo_carrier_visit.ata,'hh24:mi:ss') AS time_of_arrival,
			to_char(argo_carrier_visit.atd,'yyyy-mm-ss') AS date_of_departure,to_char(argo_carrier_visit.ata,'hh24:mi:ss') AS time_of_departure,
			ref_bizunit_scoped.name AS beaching_agent,ref_country.cntry_name AS flag,
			vsl_vessel_classes.gross_registered_ton AS grt,vsl_vessel_classes.net_registered_ton AS nrt,
			'' AS remarks,'Container Vessel' AS vsl_type
			FROM argo_carrier_visit
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			INNER JOIN vsl_vessel_classes ON vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey
			INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessels.owner_gkey
			INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
			WHERE (to_char(argo_carrier_visit.ata,'yyyy-mm-dd') BETWEEN '$formDate' AND '$toDate'
			OR to_char(argo_carrier_visit.atd,'yyyy-mm-dd') BETWEEN '$formDate' AND '$toDate')";

			$resultList;
			
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
				for($i=0;$i<count($queryList);$i++){
					$resultList[$i]['vsl_type']=$queryList[$i]['VSL_TYPE'];
					$resultList[$i]['imp_rot']=$queryList[$i]['IMP_ROT'];
					$resultList[$i]['vsl_name']=$queryList[$i]['VSL_NAME'];
					$resultList[$i]['date_of_arrival']=$queryList[$i]['DATE_OF_ARRIVAL'];
					$resultList[$i]['time_of_arrival']=$queryList[$i]['TIME_OF_ARRIVAL'];
					$resultList[$i]['date_of_departure']=$queryList[$i]['DATE_OF_DEPARTURE'];
                    $resultList[$i]['time_of_departure']=$queryList[$i]['TIME_OF_DEPARTURE'];
					$resultList[$i]['beaching_agent']=$queryList[$i]['BEACHING_AGENT'];
					$resultList[$i]['flag']=$queryList[$i]['FLAG'];
					$resultList[$i]['grt']=$queryList[$i]['GRT'];
					$resultList[$i]['nrt']=$queryList[$i]['NRT'];
					$resultList[$i]['remarks']=$queryList[$i]['REMARKS'];

				}
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
				for($i=0;$i<count($queryList);$i++){
					$resultList[$i]['vsl_type']=$queryList[$i]['vsl_type'];
					$resultList[$i]['imp_rot']=$queryList[$i]['imp_rot'];
					$resultList[$i]['vsl_name']=$queryList[$i]['vsl_name'];
					$resultList[$i]['date_of_arrival']=$queryList[$i]['date_of_arrival'];
					$resultList[$i]['time_of_arrival']=$queryList[$i]['time_of_arrival'];
					$resultList[$i]['date_of_departure']=$queryList[$i]['date_of_departure'];
                    $resultList[$i]['time_of_departure']=$queryList[$i]['time_of_departure'];
					$resultList[$i]['beaching_agent']=$queryList[$i]['beaching_agent'];
					$resultList[$i]['flag']=$queryList[$i]['flag'];
					$resultList[$i]['grt']=$queryList[$i]['grt'];
					$resultList[$i]['nrt']=$queryList[$i]['nrt'];
					$resultList[$i]['remarks']=$queryList[$i]['remarks'];

				}
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
				$this->data['queryList']=$resultList;
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
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "forwardedVslHistoryN4"));
		//Menu Expanding....
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
				
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey, ctmsmis.vsl_vssel_info.ib_vyg,ctmsmis.vsl_vssel_info.vsl_name,
				ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt,
				ctmsmis.vsl_forward_info.marine_forward_by AS forwarded_by
				 FROM ctmsmis.vsl_forward_info
				INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.marine_forward_by='$login_id'
				ORDER BY ctmsmis.vsl_forward_info.marine_forward_at ASC ";
			}
			else if($org_Type_id=='81')
			{
				$data['title']="Forwarded By Master - History";

				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey, ctmsmis.vsl_vssel_info.ib_vyg,ctmsmis.vsl_vssel_info.vsl_name,
				ctmsmis.vsl_forward_info.master_forward_at AS forwarded_dt,
				ctmsmis.vsl_forward_info.master_forward_by AS forwarded_by
				FROM ctmsmis.vsl_forward_info
				INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_by='$login_id'
				ORDER BY ctmsmis.vsl_forward_info.master_forward_at ASC";
			}
			else if($org_Type_id=='82')
			{
				if($section=='acc')
				{
					$data['title']="Forwarded By Accountant - History";

					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey, ctmsmis.vsl_vssel_info.ib_vyg,ctmsmis.vsl_vssel_info.vsl_name,
					ctmsmis.vsl_forward_info.sr_acnt_forward_at AS forwarded_dt,
					ctmsmis.vsl_forward_info.sr_acnt_forward_by AS forwarded_by
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_by='$login_id'
					ORDER BY ctmsmis.vsl_forward_info.sr_acnt_forward_at ASC";
				}
				else if ($section=='billop')
				{
					$data['title']="Forwarded By Bill Operator - History";

					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey, ctmsmis.vsl_vssel_info.ib_vyg,ctmsmis.vsl_vssel_info.vsl_name,
					ctmsmis.vsl_forward_info.billop_bill_at AS forwarded_dt,
					ctmsmis.vsl_forward_info.sr_acnt_forward_by AS forwarded_by
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_forward_info.billop_bill_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_by='$login_id'
					ORDER BY ctmsmis.vsl_forward_info.billop_bill_at ASC";
				}

			}
			// echo $departQuery;return;
			$departData = $this->bm->dataSelectDb2($departQuery);

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
			// $this->load->view('Vessel/vesselForwardingbyMarineForm',$data);			
			$this->load->view('vesselForwardList_history',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	
	function forwardedVslHistoryNotEntering()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "VESSEL"));
		$this->session->set_userdata(array('sub_menu' => "forwardedVslHistoryNotEntering"));
		//Menu Expanding....
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			$section =$this->session->userdata('section');
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Forwarded By Marine (Not Entering) - History";
				
				// $departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,
				// CONCAT(DATE_FORMAT(date_of_arrival,'%d/%m/%Y'),' ',TIME(time_of_arrival)) AS ata,CONCAT(DATE_FORMAT(date_of_departure,'%d/%m/%Y'),' ',TIME(time_of_departure)) AS atd,
				// grt,nrt,flag AS cntry_name,agent_name AS name,CONCAT(DATE_FORMAT(marine_forward_at,'%d/%m/%Y'),' ',TIME(marine_forward_at)) AS forward_at,forward_remarks
				// FROM outer_vsl_forward_info
				// INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
				// INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				// INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				// WHERE marine_forward_stat='1' AND marine_forward_by='$login_id'";	
				
				if($login_id == 24087)
				{
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(DATE_FORMAT(date_of_arrival,'%d/%m/%Y'),' ',TIME(time_of_arrival)) AS ata,CONCAT(DATE_FORMAT(date_of_departure,'%d/%m/%Y'),' ',TIME(time_of_departure)) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,CONCAT(DATE_FORMAT(svtmis_forward_at,'%d/%m/%Y'),' ',TIME(svtmis_forward_at)) AS forward_at,forward_remarks
					FROM outer_vsl_forward_info
					INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					WHERE marine_forward_stat='1' AND svtmis_forward_stat='1' AND svtmis_forward_by='$login_id' ORDER BY marine_forward_at DESC";
				}
				else if($login_id == 12369)
				{
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(DATE_FORMAT(date_of_arrival,'%d/%m/%Y'),' ',TIME(time_of_arrival)) AS ata,CONCAT(DATE_FORMAT(date_of_departure,'%d/%m/%Y'),' ',TIME(time_of_departure)) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,CONCAT(DATE_FORMAT(hob_forward_at,'%d/%m/%Y'),' ',TIME(hob_forward_at)) AS forward_at,forward_remarks
					FROM outer_vsl_forward_info
					INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					WHERE marine_forward_stat='1' AND svtmis_forward_stat='1' AND hob_forward_stat='1' AND hob_forward_by='$login_id' ORDER BY marine_forward_at DESC";
				}
				else
				{
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(DATE_FORMAT(date_of_arrival,'%d/%m/%Y'),' ',TIME(time_of_arrival)) AS ata,CONCAT(DATE_FORMAT(date_of_departure,'%d/%m/%Y'),' ',TIME(time_of_departure)) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,CONCAT(DATE_FORMAT(marine_forward_at,'%d/%m/%Y'),' ',TIME(marine_forward_at)) AS forward_at,forward_remarks
					FROM outer_vsl_forward_info
					INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					WHERE marine_forward_stat='1' AND marine_forward_by='$login_id' ORDER BY marine_forward_at DESC";
				}
			}
			else if($org_Type_id=='81') //Master			
			{
				$data['title']="Forwarded By Master (Not Entering) - History";
				
				$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,
				CONCAT(DATE_FORMAT(date_of_arrival,'%d/%m/%Y'),' ',TIME(time_of_arrival)) AS ata,CONCAT(DATE_FORMAT(date_of_departure,'%d/%m/%Y'),' ',TIME(time_of_departure)) AS atd,
				grt,nrt,flag AS cntry_name,agent_name AS name,CONCAT(DATE_FORMAT(master_forward_at,'%d/%m/%Y'),' ',TIME(master_forward_at)) AS forward_at,forward_remarks
				FROM outer_vsl_forward_info
				INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
				INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
				INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
				WHERE master_forward_stat='1' AND master_forward_by='$login_id' ORDER BY master_forward_at DESC";
			}
			else if($org_Type_id=='82') // Accounts
			{
				if($section=='acc') //Sr. Accountant
				{					
					$data['title']="Forwarded By Accountant (Not Entering) - History";
					
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,sr_acnt_forward_at AS forward_at,forward_remarks
					FROM outer_vsl_forward_info
					INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					WHERE sr_acnt_forward_stat='1' AND sr_acnt_forward_by='$login_id' ORDER BY sr_acnt_forward_at DESC";
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //Accountant
				{
					$data['title']="Bill Generated By Bill Operator (Not Entering) - History";
					
					$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,flag AS cntry_name,agent_name AS name,forward_remarks
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
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');			
			$this->load->view('vesselForwardList_notEntering_history',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	// forwarded vsl history - end
	
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
			
			$lotVslListId=$this->input->post('lotVslListId');							
			
			$sql_marineVslLot_NE_List = "SELECT outer_vsl_visit_info.imp_rot AS impRot,outer_vsl_info.vsl_name,outer_vsl_info.vsl_type AS vsl_class,'Not Entering' AS vsl_type
			FROM outer_vsl_forward_info
			INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
			INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
			WHERE vsl_fwd_lot_info_id='$lotVslListId'";
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
			$title = "Marine Vessel Lot (Marine)";
			
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
			$title = "Marine Vessel Lot (Marine) - Vessel List";
			
			$login_id = $this->session->userdata('login_id');
			$section = $this->session->userdata('section');
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			
			$lotVslListId=$this->input->post('lotVslListId');
			/* $sql_vvdGkeyFwdTbl = "SELECT ctmsmis.vsl_forward_info.vvd_gkey
			FROM ctmsmis.vsl_forward_info
			WHERE ctmsmis.vsl_forward_info.vsl_fwd_lot_info_id='$lotVslListId'";
			$rslt_vvdGkeyFwdTbl = $this->bm->dataSelect($sql_vvdGkeyFwdTbl);
			
			$vvdGkeyFwdTbl = "";
			for($i=0;$i<count($rslt_vvdGkeyFwdTbl);$i++)
			{
				$vvdGkeyFwdTbl = $rslt_vvdGkeyFwdTbl[$i]['vvd_gkey'];
			} */
						
			$sql_marineVslLot_N4_List = "SELECT sparcsn4.vsl_vessel_visit_details.ib_vyg AS impRot,sparcsn4.vsl_vessels.name AS vsl_name,
			(CASE
				WHEN sparcsn4.vsl_vessel_classes.basic_class='CELL'
					THEN 'CONTAINER'
				WHEN sparcsn4.vsl_vessel_classes.basic_class='BBULK'
					THEN 'BREAK BULK'
				WHEN sparcsn4.vsl_vessel_classes.basic_class='PSNGR'
					THEN 'PESSENGER'
				ELSE sparcsn4.vsl_vessel_classes.basic_class	
			END
			) AS vsl_class,
			'Marine Vessel' AS vsl_type
			FROM sparcsn4.argo_carrier_visit
			INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
			INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
			INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
			WHERE ctmsmis.vsl_forward_info.vsl_fwd_lot_info_id='$lotVslListId'";
			$rslt_marineVslLot_N4_List = $this->bm->dataSelect($sql_marineVslLot_N4_List);
			
			$data['title'] = $title;
			$data['rslt_marineVslLot_N4_List'] = $rslt_marineVslLot_N4_List;
			
			$this->load->view('marineVslLot_N4_List',$data);		// start from here - crete html			
		}
	}
	// marine vessel lot - end
	
	// search vsl from letter - start
	function searchVslFromLetter()
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
			$org_Type_id = $this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');
						
			$searchRotation = $this->input->post('searchRotation');
			$action = $this->input->post('action');

			
			if($section=='billop')
			{
				$sql_vslFromLetter = "SELECT vsl_frwrd_letter_info.id AS letter_no,DATE_FORMAT(file_dt,'%d/%m/%Y') AS file_dt,
				file_sub,file_no,no_vsl,
				(SELECT COUNT(*) FROM ctmsmis.vsl_forward_info WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=letter_no AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
				FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
				INNER JOIN ctmsmis.vsl_frwrd_letter_info ON ctmsmis.vsl_frwrd_letter_info.id = ctmsmis.vsl_forward_info.vsl_frwd_letter_no
				WHERE ctmsmis.vsl_vssel_info.ib_vyg='$searchRotation' AND vsl_forward_info.bill_op_user_id='$login_id'
				
				UNION
				
				SELECT vsl_frwrd_letter_info.id AS letter_no,DATE_FORMAT(file_dt,'%d/%m/%Y') AS file_dt,file_sub,file_no,no_vsl,
				(SELECT COUNT(*) FROM ctmsmis.vsl_cancelation_forward_info WHERE ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no=letter_no AND ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_stat='0') AS pending_no
				FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
				INNER JOIN ctmsmis.vsl_frwrd_letter_info ON ctmsmis.vsl_frwrd_letter_info.id = ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no
				WHERE ctmsmis.vsl_vssel_info.ib_vyg='$searchRotation' AND vsl_cancelation_forward_info.bill_op_user_id='$login_id'";
			}
			else
			{
				$sql_vslFromLetter = "SELECT vsl_frwrd_letter_info.id AS letter_no, DATE_FORMAT(file_dt,'%d/%m/%Y') AS file_dt,
				file_sub,file_no,no_vsl,
				(SELECT COUNT(*) FROM ctmsmis.vsl_forward_info WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=letter_no AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no
				FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
				INNER JOIN ctmsmis.vsl_frwrd_letter_info ON ctmsmis.vsl_frwrd_letter_info.id = ctmsmis.vsl_forward_info.vsl_frwd_letter_no
				WHERE ctmsmis.vsl_vssel_info.ib_vyg='$searchRotation'
				
				UNION 

				SELECT vsl_frwrd_letter_info.id AS letter_no, DATE_FORMAT(file_dt,'%d/%m/%Y') AS file_dt,file_sub,file_no,no_vsl,
				(SELECT COUNT(*) FROM ctmsmis.vsl_cancelation_forward_info WHERE ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no=letter_no AND ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_stat='0') AS pending_no
				FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
				INNER JOIN ctmsmis.vsl_frwrd_letter_info ON ctmsmis.vsl_frwrd_letter_info.id = ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no
				WHERE ctmsmis.vsl_vssel_info.ib_vyg='$searchRotation'";
			}
			
			// echo $sql_vslFromLetter;return;
			$letterList = $this->bm->dataSelectDb2($sql_vslFromLetter);
			
			$msg = "";
			$title="";
			if($section=='acc')
			{
				$title="Vessel Forwarding by Accountant";
			}	
			 else if($section=='billop')
			{
				$title="Vessel Forwarding ";
			}
			if(count($letterList) == 0)
				$msg = "<font color='red'>No data found...</font>";

			$data['letterList']=$letterList;
			$data['title']=$title;
			$data['msg']=$msg;
			$data['action']=$action;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;
			$data['section']=$section;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/vesselForwardingbyMasterList',$data);			
			$this->load->view('jsAssetsList');
		}
	}

	// search vsl from letter - end
	
	// summary for HMaster - start
	function forwardedVslSummary()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Vessel Forward Summary";
			$masterFlag = "";
			$data['masterFlag'] = $masterFlag;
			$data['msg'] = "";
			$data['flag'] = 0;
			$data['title'] = $title;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('Vessel/forwardedVslSummary',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function forwardedVslSummaryReport()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$fromDate = $this->input->post('fromDate');
			$toDate = $this->input->post('toDate');
			$vslType = $this->input->post('vslType');
			$rptType = $this->input->post('rptType');
			$app_data = $this->input->post('app_data');	

			$title = "";
			$rslt_hmSummary = null;
			
			$totContVslSum = null;
			$totNEVslSum = null;
			$totBillGenSum = null;
			$totPendingBill = null;
			$totBillAprvSum = null;
			
			$totVslArrive = null;
			$totVslDepart = null;
			$totVslShift = null;
			
			if($rptType == "detail")
			{
				if($vslType == "Container Vessel")
				{
					$title = "Container Vessel - Forward & Bill Generation Statement";
				/* 	$sql_hmSummary = "SELECT forwarded_dt,forwarded_by_master,
					(SELECT COUNT(DISTINCT rotation) FROM ctmsmis.mis_vsl_billing_detail_test
					WHERE DATE(ctmsmis.mis_vsl_billing_detail_test.billing_date)=forwarded_dt) AS tot_bill
					FROM(
					SELECT COUNT(*) AS forwarded_by_master,DATE(ctmsmis.vsl_forward_info.master_forward_at) AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					WHERE DATE(ctmsmis.vsl_forward_info.master_forward_at) BETWEEN '$fromDate' AND '$toDate' AND master_forward_stat='1'
					GROUP BY DATE(ctmsmis.vsl_forward_info.master_forward_at)) AS tbl"; */
					
					$sql_hmSummary = "SELECT forwarded_dt,forwarded_by_master,
					(SELECT COUNT(DISTINCT rotation) FROM ".$this->Init_Table_Map("DETAILS")."
					WHERE DATE(".$this->Init_Table_Map("DETAILS").".billing_date)=forwarded_dt) AS tot_bill
					FROM(
					SELECT COUNT(*) AS forwarded_by_master,DATE(ctmsmis.vsl_forward_info.master_forward_at) AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					WHERE DATE(ctmsmis.vsl_forward_info.master_forward_at) BETWEEN '$fromDate' AND '$toDate' AND master_forward_stat='1'
					GROUP BY DATE(ctmsmis.vsl_forward_info.master_forward_at)) AS tbl";
					$rslt_hmSummary = $this->bm->dataSelectDb2($sql_hmSummary);	
				}
				else if($vslType == "Not Entering Vessel")
				{
					$title = "Not Entering Vessel - Forward & Bill Generation Statement";	

					$sql_hmSummary = "SELECT * FROM 
					(SELECT ADDDATE('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) forwarded_dt,
					(SELECT COUNT(*) FROM outer_vsl_forward_info WHERE DATE(outer_vsl_forward_info.master_forward_at)=forwarded_dt AND outer_vsl_forward_info.marine_forward_stat='1') AS forwarded_by_master
					FROM
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
					WHERE forwarded_dt BETWEEN '$fromDate' AND '$toDate'";
					$rslt_hmSummary = $this->bm->dataSelectDB1($sql_hmSummary);	
				}
			}
			else if($rptType == "summary")
			{
				if($vslType == "Container Vessel")
				{
					// cont forwarded
					$title = "Forward & Bill Info (Container)";
					$sql_totContVslSum = "SELECT COUNT(*) AS rtnValue
					FROM ctmsmis.vsl_forward_info
					WHERE ctmsmis.vsl_forward_info.marine_forward_stat='1' AND DATE(ctmsmis.vsl_forward_info.marine_forward_at)
					BETWEEN '$fromDate' AND '$toDate'";
					$totContVslSum = $this->bm->dataReturnDb2($sql_totContVslSum);	
				}
				else if($vslType == "Not Entering Vessel")
				{
					// NE forwarded
					$title = "Forward & Bill Info (Not Entering)";
					$sql_totNEVslSum = "SELECT COUNT(*) AS rtnValue
					FROM outer_vsl_forward_info
					WHERE outer_vsl_forward_info.master_forward_stat='1' AND DATE(outer_vsl_forward_info.master_forward_at)
					BETWEEN '$fromDate' AND '$toDate'";
					$totNEVslSum = $this->bm->dataReturnDb1($sql_totNEVslSum);
				}
				
				// Forward & Bill Info - start
				
				// // cont forwarded
				// $sql_totContVslSum = "SELECT COUNT(*) AS rtnValue
				// FROM ctmsmis.vsl_forward_info
				// WHERE ctmsmis.vsl_forward_info.marine_forward_stat='1' AND DATE(ctmsmis.vsl_forward_info.marine_forward_at)
				// BETWEEN '$fromDate' AND '$toDate'";
				// $totContVslSum = $this->bm->dataReturn($sql_totContVslSum);

				// // NE forwarded
				// $sql_totNEVslSum = "SELECT COUNT(*) AS rtnValue
				// FROM outer_vsl_forward_info
				// WHERE outer_vsl_forward_info.master_forward_stat='1' AND DATE(outer_vsl_forward_info.master_forward_at)
				// BETWEEN '$fromDate' AND '$toDate'";
				// $totNEVslSum = $this->bm->dataReturnDb1($sql_totNEVslSum);
				
				// total generated
				$sql_totBillGenSum = "SELECT COUNT(DISTINCT rotation) AS rtnValue
				FROM ".$this->Init_Table_Map("DETAILS")."
				WHERE DATE(billing_date) BETWEEN '$fromDate' AND '$toDate'";
				$totBillGenSum = $this->bm->dataReturnDb2($sql_totBillGenSum);

				// total pending for bill
				$sql_totPendingBill = "SELECT COUNT(*) AS rtnValue
				FROM ctmsmis.vsl_forward_info
				WHERE ctmsmis.vsl_forward_info.billop_bill_stat='0' AND DATE(ctmsmis.vsl_forward_info.marine_forward_at) BETWEEN '$fromDate' AND '$toDate'";
				$totPendingBill = $this->bm->dataReturnDb2($sql_totPendingBill);
				
				// total approved
				$sql_totBillAprvSum = "SELECT COUNT(DISTINCT rotation) AS rtnValue
				FROM ".$this->Init_Table_Map("DETAILS")."
				WHERE DATE(billing_date) BETWEEN '$fromDate' AND '$toDate' AND acc_apprv_st=1";
				$totBillAprvSum = $this->bm->dataReturnDb2($sql_totBillAprvSum);
				
				// Forward & Bill Info - end
				
				// Vessel Handling BY Apps - start

				if($app_data == "yes")
				{
					$sql_totVslArrive = "SELECT COUNT(*) AS rtnValue
					FROM doc_vsl_arrival
					WHERE DATE(mooring_frm_time) BETWEEN '$fromDate' AND '$toDate'";
					$totVslArrive = $this->bm->dataReturnDb1($sql_totVslArrive);
					
					$sql_totVslDepart = "SELECT COUNT(*) AS rtnValue
					FROM doc_vsl_depart
					WHERE DATE(mooring_to_time) BETWEEN '$fromDate' AND '$toDate'";
					$totVslDepart = $this->bm->dataReturnDb1($sql_totVslDepart);
					
					$sql_totVslShift = "SELECT COUNT(*) AS rtnValue
					FROM doc_vsl_shift
					WHERE DATE(mooring_frm_time) BETWEEN '$fromDate' AND '$toDate'";
					$totVslShift = $this->bm->dataReturnDb1($sql_totVslShift);
				}
				
				// Vessel Handling BY Apps - end
			}
			
			// echo $sql_hmSummary;return;

			
			// pdf - start
			
			$this->data['title'] = $title;
			$this->data['fromDate'] = $fromDate;
			$this->data['toDate'] = $toDate;
			$this->data['vslType'] = $vslType;
			$this->data['rslt_hmSummary'] = $rslt_hmSummary;
			
			$this->data['totContVslSum'] = $totContVslSum;
			$this->data['totNEVslSum'] = $totNEVslSum;
			$this->data['totBillGenSum'] = $totBillGenSum;
			$this->data['totPendingBill'] = $totPendingBill;
			$this->data['totBillAprvSum'] = $totBillAprvSum;
			
			$this->data['totVslArrive'] = $totVslArrive;
			$this->data['totVslDepart'] = $totVslDepart;
			$this->data['totVslShift'] = $totVslShift;
			$this->data['app_data'] = $app_data;
			
			$this->load->library('m_pdf');
				
			if($rptType == "summary")
			{
				$html=$this->load->view('Vessel/forwardedVslSummaryReportNew',$this->data, true); 
				
				$pdfFilePath ="VesselForwardSummary-".time()."-download.pdf";

				$pdf = $this->m_pdf->load();
				$pdf->SetWatermarkText('CPA CTMS');
				$pdf->showWatermarkText = false;
				
				$stylesheet = file_get_contents('resources/styles/cartticket.css'); // external css

				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);

				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			}
			else if($rptType == "detail")
			{
				$html=$this->load->view('Vessel/forwardedVslSummaryReport',$this->data, true); 
				
				$pdfFilePath ="VesselForwardSummary-".time()."-download.pdf";

				$pdf = $this->m_pdf->load();
				$pdf->SetWatermarkText('CPA CTMS');
				$pdf->showWatermarkText = false;
				
				$stylesheet = file_get_contents('resources/styles/cartticket.css'); // external css

				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);

				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			}									
			// pdf - end					
		}
	}
	
	// summary for HMaster - end
	
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
	
	//forwared vessel details starts...		
	function vesselForwardingStatementForm()
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
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$msg="";
			$frmType="new";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Forwarding Vessel Statement";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingStatementForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
    function vesselForwardingStatement()
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
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$from_date =$this->input->post('from_date')." 00:00:00";
			$to_date =$this->input->post('to_date')." 23:59:59";
						
			$this->data['from_date']=$from_date;
			$this->data['to_date']=$to_date;
			$this->data['login_id']=$login_id;
			
			if($login_id=="HMaster"){
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
				ctmsmis.vsl_vssel_info.ves_captain, ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name, 
				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				ctmsmis.vsl_vssel_info.ib_vyg, 
				DATE_FORMAT(ctmsmis.vsl_forward_info.master_forward_at,'%d/%m/%Y %H:%i:%s') AS forwarded_dt,
				vsl_forward_info.master_forward_by AS forwarded_by,vsl_forward_info.marine_forward_by
				FROM ctmsmis.vsl_forward_info
                INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE master_forward_at >= '$from_date' AND master_forward_at <= '$to_date'";
			} else {
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
				ctmsmis.vsl_vssel_info.ves_captain, ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name, 
				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				
				ctmsmis.vsl_vssel_info.ib_vyg, 
				DATE_FORMAT(ctmsmis.vsl_forward_info.master_forward_at,'%d/%m/%Y %H:%i:%s') AS forwarded_dt,
				vsl_forward_info.master_forward_by AS forwarded_by,vsl_forward_info.marine_forward_by
				FROM ctmsmis.vsl_forward_info
                INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE marine_forward_by='$login_id' AND master_forward_at >= '$from_date' AND master_forward_at <= '$to_date'";
			}
			
			
			$departData = $this->bm->dataSelectDb2($departQuery);
			
			$this->data['departData']=$departData;
			
			$totalPage=ceil(count($departData)/20);
			$this->data['totalPage']=$totalPage;
			$this->data['totalRow']=count($departData);
			
			$marine_forward_by = "";
			$master_forward_by = "";
			for($i=0;$i<count($departData);$i++)
			{
				$marine_forward_by = $departData[$i]['marine_forward_by'];
				$master_forward_by = $departData[$i]['forwarded_by'];
			}
			$this->data['marine_forward_by']=$marine_forward_by;
			$this->data['master_forward_by']=$master_forward_by;
			
			$this->load->library('m_pdf');
			
			//load the pdf_output.php by passing our data and get all data in $html varriable.
			$html=$this->load->view('vesselForwardingStatement',$this->data, true); 
			//var_dump($html);
			
			$pdfFilePath ="vesselForwardingStatement-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			
			
			$pdf->allow_charset_conversion = true;
			//Follwing line is commented to show bangla font in PDF
			//$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
			
			// please follow : https://mpdf.github.io/reference/mpdf-functions/addpage.html
			// L - landscape, P - portrait
			$pdf->AddPage(
				'', // orientation L|P
				'', // type = E|O|even|odd|next-odd|next-even
				'', // resetpagenum = 1 - ∞
				'', // pagenumstyle = 1|A|a|I|i
				'', // suppress = on|off|1|0
				2, // margin_left
				2, // margin right
				5, // margin top
				2, // margin bottom
				5, // margin header
				5, // margin footer
				'', // odd-header-name
				'', // even-header-name
				'', // odd-footer-name
				'', // even-footer-name
				0, // odd-header-value
				0, // even-header-value
				0, // odd-footer-value
				0, // even-footer-value
				'', // pageselector ... Select a named CSS @page.
				'Legal-L' //Sheet size...array can be set also, like [210,297]
			); 
			
			$footer = array (
				'odd' => array (
					'L' => array (
						'content' => '',
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'C' => array (
						'content' => '',
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'R' => array (
						'content' => 'page {PAGENO} of '.$totalPage,
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'line' => 0, // 1= draw footer border, 0 = don't draw footer border
				),
				'even' => array ()
			);
			$pdf->setFooter($footer);
			
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			
		}
	}
	function vesselForwardingStatementForNotEnteringForm()
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
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$msg="";
			$frmType="new";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Forwarding Vessel Statement (Not Entering)";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingStatementForNotEnteringForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function vesselForwardingStatementForNotEntering()
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
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$from_date =$this->input->post('from_date');
			$to_date =$this->input->post('to_date');
						
			$this->data['from_date']=$from_date;
			$this->data['to_date']=$to_date;
			$this->data['login_id']=$login_id;
			
			if($login_id=="HMaster"){
				$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,
							CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,
							flag AS cntry_name,agent_name AS name,master_forward_at AS forward_at,forward_remarks,
							outer_vsl_forward_info.master_forward_by,outer_vsl_forward_info.marine_forward_by
							FROM outer_vsl_forward_info
							INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
							INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
							INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
							WHERE master_forward_stat='1' AND (master_forward_at BETWEEN '$from_date' AND '$to_date')";
			} else {				
							
				$departQuery = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,
							CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,
							flag AS cntry_name,agent_name AS name,master_forward_at AS forward_at,forward_remarks,
							outer_vsl_forward_info.master_forward_by,outer_vsl_forward_info.marine_forward_by
							FROM outer_vsl_forward_info
							INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
							INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
							INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
							WHERE master_forward_stat='1' AND marine_forward_by='$login_id' 
							AND (master_forward_at BETWEEN '$from_date' AND '$to_date')";
			}
			
			
			$departData = $this->bm->dataSelectDB1($departQuery);
			
			$this->data['departData']=$departData;
			
			$totalPage=ceil(count($departData)/15);
			$this->data['totalPage']=$totalPage;
			$this->data['totalRow']=count($departData);
			
			$marine_forward_by = "";
			$master_forward_by = "";
			for($i=0;$i<count($departData);$i++)
			{
				$marine_forward_by = $departData[$i]['marine_forward_by'];
				$master_forward_by = $departData[$i]['master_forward_by'];
			}
			$this->data['marine_forward_by']=$marine_forward_by;
			$this->data['master_forward_by']=$master_forward_by;
			
			$this->load->library('m_pdf');
			
			//load the pdf_output.php by passing our data and get all data in $html varriable.
			$html=$this->load->view('vesselForwardingStatementForNotEntering',$this->data, true); 
			//var_dump($html);
			
			$pdfFilePath ="vesselForwardingStatementForNotEntering-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			//Follwing line is commented to show bangla font in PDF
			//$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
			
			
			// please follow : https://mpdf.github.io/reference/mpdf-functions/addpage.html
			// L - landscape, P - portrait
			$pdf->AddPage(
				'', // orientation L|P
				'', // type = E|O|even|odd|next-odd|next-even
				'', // resetpagenum = 1 - ∞
				'', // pagenumstyle = 1|A|a|I|i
				'', // suppress = on|off|1|0
				2, // margin_left
				2, // margin right
				5, // margin top
				2, // margin bottom
				5, // margin header
				5, // margin footer
				'', // odd-header-name
				'', // even-header-name
				'', // odd-footer-name
				'', // even-footer-name
				0, // odd-header-value
				0, // even-header-value
				0, // odd-footer-value
				0, // even-footer-value
				'', // pageselector ... Select a named CSS @page.
				'Legal-L' //Sheet size...array can be set also, like [210,297]
			); 
			
			$footer = array (
				'odd' => array (
					'L' => array (
						'content' => '',
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'C' => array (
						'content' => '',
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'R' => array (
						'content' => 'page {PAGENO} of '.$totalPage,
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'line' => 0, // 1= draw footer border, 0 = don't draw footer border
				),
				'even' => array ()
			);
			$pdf->setFooter($footer);
			
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			
		}
	}
	//forwared vessel details ends...
	function showContainerForwardedSummary()
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
            $login_id = $this->session->userdata('login_id');			
				
			if($rotation == null or $rotation == "")
			{
				$rotation = $this->uri->segment(3);
				$rotation = str_replace("_","/",$rotation);
			}
			
			$data['rotation'] = $rotation;	
			$data['login_id']=$login_id;
			$data['countBillRow'] = 0;	
			
			//$this->load->view('cssAssets');
			//$this->load->view('qgcContForwardView',$data);
			//$this->load->view('myclosebar');
			//$this->load->view('jsAssets');
			
			$this->load->library('m_pdf');
			$html=$this->load->view('qgcContForwardView',$data, true); 
			$pdfFilePath ="qgcContForwardView-".time()."-download.pdf";
			$pdf = $this->m_pdf->load();
			$pdf->useSubstitutions = true; 
			$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
			$stylesheet = file_get_contents('assets/stylesheets/test.css');
			 $pdf->AddPage('L', // L - landscape, P - portrait
							 '', '', '', '',
							 5, // margin_left
							 5, // margin right
							 10, // margin top
							 10, // margin bottom
							 10, // margin header
							 10); // margin footer
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath, "I");
		}
	}
	
	

	// Water Demand -- start

	function waterDemandForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Water Demand Form";

			if(isset($_POST['submit']))
			{

				$vsl_name = trim($this->input->post('vsl_name'));
				$rotation = trim($this->input->post('rotation'));
				$supplyType = trim($this->input->post('supplyType'));
				$water_demand = trim($this->input->post('water_demand'));
				$unit = trim($this->input->post('unit'));
				$demand_date = trim($this->input->post('demand_date'));

				$berthQuery = "SELECT argo_quay.id as berth
				FROM argo_quay
				INNER JOIN vsl_vessel_berthings brt ON brt.quay=argo_quay.gkey
				INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=brt.vvd_gkey
				WHERE vsl_vessel_visit_details.ib_vyg = '$rotation' ORDER BY atd DESC FETCH FIRST 1 ROWS ONLY";
				$berthResult = $this->bm->dataSelect($berthQuery);
				
				$berth = null;

				if(count($berthResult)>0){
					$berth = $berthResult[0]['BERTH'];
				}
				

				// $duplicateChkQuery = "SELECT count(*) as rtnValue FROM ctmsmis.water_demand_info WHERE rotation_no = '$rotation'";
				// $duplicateChk = $this->bm->dataReturn($duplicateChkQuery);

				// if($duplicateChk>0)
				// {
				// 	$this->session->set_flashdata("error", "<div class='alert alert-danger'>
				// 	<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
				// 	<font size='4'>Duplicate Rotation found. Please try with different rotation...</font></div>");
				// }
				// else
				// {
					if($supplyType=='shore')
					{
						$query = "INSERT INTO ctmsmis.water_demand_info(rotation_no,vessel_name,supply_type,berth,demand_qty,demand_unit,demand_date,demand_by,demand_at,demand_ip) VALUES('$rotation','$vsl_name','$supplyType','$berth','$water_demand','$unit','$demand_date','$login_id',NOW(),'$ipAddr')";
					
						if($this->bm->dataInsertDb2($query)){
							$this->session->set_flashdata("error", "<div class='alert alert-success'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Demand successfully submitted...</font></div>");
						}else{
							$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Failed! Please try again...</font></div>");
						}
					}
					else if($supplyType=='burge')
					{
						$delv_area = $this->input->post('delv_area');
						$agent_name = $this->input->post('ship_agent');
						$mobl_number = trim($this->input->post('mobl_number'));
						$bizu_gkey = trim($this->input->post('bizu_gkey'));
						$agent_gkey = trim($this->input->post('agent_gkey'));
				
						
						$isExistStr="SELECT COUNT(*) AS rtnValue FROM ctmsmis.water_demand_info WHERE water_demand_info.rotation_no='$rotation'"; 
						$isExist = $this->bm->dataReturnDb2($isExistStr);

						if($isExist>0)
						{
							$demand_id_str="SELECT id as rtnValue FROM ctmsmis.water_demand_info WHERE water_demand_info.rotation_no='$rotation' order by id DESC LIMIT 1";							
							$demand_id=$this->bm->dataReturnDb2($demand_id_str);
							
							// $burgeDetailstr="UPDATE ctmsmis.water_demand_burge_detail SET  delivery_area ='$delv_area'  WHERE water_demand_id ='$demand_id'";
							// $upload_st = $this->bm->dataUpdate($burgeDetailstr);
							
							$updateQuery = "UPDATE ctmsmis.water_demand_info SET supply_type='$supplyType',berth = '$berth', demand_qty='$water_demand', 
							demand_unit='$unit',demand_date='$demand_date',demand_by='$login_id', 
							demand_at=NOW(), bizu_gkey='$bizu_gkey', agent_gkey='$agent_gkey', 
							agent_mobile='$mobl_number', agent_name='$agent_name', delivery_area ='$delv_area' WHERE rotation_no='$rotation' AND water_demand_info.id='$demand_id'";
							$upload_st2 = $this->bm->dataUpdatedb2($updateQuery);
							
							$fileName = $_FILES["file"]["name"];
							$fileTmpLoc = $_FILES["file"]["tmp_name"];
							$splitFile = explode(".", $fileName); // Split file name into an array using the dot
							$fileExt = end($splitFile); 

							$rotNo = str_replace("/","_",$rotation);

							$fileName = "ReqLetter_".$rotNo.".".$fileExt;
							$docPath = $_SERVER['DOCUMENT_ROOT']."/resources/waterBill/burge/".$fileName;

							$moveResult = move_uploaded_file($fileTmpLoc,$docPath);
							
							if($upload_st2 == 1)
							{
								//$query = "UPDATE ctmsmis.water_demand_info SET docPath = '$fileName' WHERE id = '$id'";
								//$upload_st = $this->bm->dataUpdate($query);

									$this->session->set_flashdata("error", "<div class='alert alert-success'>
									<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
									<font size='4'>Previous data updated successfully ...</font></div>");							
							}
							else
							{
								$this->session->set_flashdata("error", "<div class='alert alert-danger'>
										<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
										<font size='4'>File not uploaded. Try again.</font></div>");
								unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
							}
						}
						else
						{
							$fileName = $_FILES["file"]["name"];
							$fileTmpLoc = $_FILES["file"]["tmp_name"];
							$splitFile = explode(".", $fileName); // Split file name into an array using the dot
							$fileExt = end($splitFile); 

							$rotNo = str_replace("/","_",$rotation);

							$fileName = "ReqLetter_".$rotNo."_0.".$fileExt;
							$docPath = $_SERVER['DOCUMENT_ROOT']."/resources/waterBill/burge/".$fileName;

							$moveResult = move_uploaded_file($fileTmpLoc,$docPath);

							$insertQuery = "INSERT INTO ctmsmis.water_demand_info(rotation_no,vessel_name,supply_type,berth,demand_qty, 
							demand_unit,demand_date,demand_by,demand_at,demand_ip, docPath, bizu_gkey, agent_gkey, agent_mobile, agent_name , delivery_area)
							VALUES('$rotation','$vsl_name','$supplyType','$berth','$water_demand',
							'$unit','$demand_date','$login_id',NOW(),'$ipAddr', '$fileName', '$bizu_gkey', '$agent_gkey', '$mobl_number', '$agent_name', '$delv_area')";

							$insSt = $this->bm->dataInsertDb2($insertQuery);
							
							$demand_id="";
							$demand_str="SELECT id AS rtnValue FROM ctmsmis.water_demand_info WHERE  water_demand_info.rotation_no='$rotation' order by id DESC LIMIT 1";							
							$demand_id=$this->bm->dataReturnDb2($demand_str);
							
							$burgeDetailstr="INSERT INTO ctmsmis.water_demand_burge_detail(water_demand_id) VALUES('$demand_id')";

							$detailSt = $this->bm->dataInsertDb2($burgeDetailstr);


							if($detailSt == 1 && $insSt==1)
							{
								//$query = "UPDATE ctmsmis.water_demand_info SET docPath = '$fileName' WHERE id = '$id'";
								//$upload_st = $this->bm->dataUpdate($query);

									$this->session->set_flashdata("error", "<div class='alert alert-success'>
									<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
									<font size='4'>Successfully uploaded...</font></div>");							
							}
							else
							{
								$this->session->set_flashdata("error", "<div class='alert alert-danger'>
										<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
										<font size='4'>File not uploaded. Try again.</font></div>");
								unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
							}
						}
					}
					
					
					
				// }
				redirect('Vessel/waterDemandForm/', 'refresh');
				return;
			}

			$data['title'] = $title;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('waterDemandForm',$data);
			$this->load->view('jsAssets');
		}
	}

	/*function waterDemandList($rotation = null)
	{
		
		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "waterDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			
			$org_Type_id =$this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');

			$section_query = "SELECT section_value FROM users 
				INNER JOIN tbl_org_section ON tbl_org_section.gkey = users.section
				WHERE login_id = '$login_id'";

			$section_result = $this->bm->dataSelectDB1($section_query);
			
			$eng_section = null;
			if(count($section_result)>0)
			{
				$eng_section = $section_result[0]['section_value'];
			}

			$title = "Water Demand List";

			if($eng_section == "AENG" || $eng_section == "EXENCT" || $eng_section == "DCEE" || $eng_section == "SRSAE" || $org_Type_id == 81)
			{
				$title = "Water Bill Document Forwarding";
			}
			else if($org_Type_id == 82)
			{
				$title = "Water Document List";
			}

			$cond = "";

			if($rotation != null){
				if($org_Type_id == 78 || $org_Type_id == 83 || $org_Type_id == 81 || $section == 'acc')
				{
					$cond = " AND rotation_no = '$rotation'";
				}
				else
				{
					$cond = " WHERE rotation_no = '$rotation'";
				}
			}
			
			
			if($org_Type_id == 78)  // Sub Assistant Eng.  // XEN
			{
				if($eng_section == "SAECCT")
				{
					$cond.=" AND berth LIKE '%cct%'";
				}
				else if($eng_section == "SAENCT")
				{
					$cond.=" AND berth LIKE '%nct%'";
				}
				else if($eng_section == "SAEGCB" || $eng_section == "SRSAE")
				{
					$cond.=" AND berth LIKE '%gcb%'";
				}

				$query = "SELECT * FROM ctmsmis.water_demand_info WHERE supply_type = 'shore'".$cond." order by id desc";
			}
			else if($org_Type_id == 83 || $org_Type_id == 81)  // Marine // HMaster
			{
				$query = "SELECT * FROM ctmsmis.water_demand_info WHERE supply_type = 'burge'".$cond." order by id desc";
			}
			else if($org_Type_id == 82 && $section == 'billop')
			{
				$query = "SELECT * FROM ctmsmis.water_demand_info where bill_op = '$login_id' order by id desc";
			}
			else if($org_Type_id == 82 && $section == 'acc')
			{
				$query = "SELECT * FROM ctmsmis.water_demand_info  WHERE ctmsmis.water_demand_info.acc_aprv_st!=1 AND  (ctmsmis.water_demand_info.xen_aprv_st='1' OR ctmsmis.water_demand_info.demand_aprv_st='1') 
				AND ctmsmis.water_demand_info.bill_op_bill_st!=1 order by id desc";
			}
			
            //$result = $this->bm->dataReturnDb2($query);
			$result = $this->bm->dataSelectDb2($query);
			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);
			
			
			$data['billOpListRslt'] = $billOpListRslt;

			
			$data['eng_section'] = $eng_section;
			$data['result'] = $result;
			$data['title'] = $title;

			

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('waterDemandList',$data);
			$this->load->view('jsAssetsList');
		}
	}*/
	
	function waterDemandList($rotation = null)
	{
		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "waterDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			 $org_Type_id =$this->session->userdata('org_Type_id');
		     $section = $this->session->userdata('section');
			 $section_query = "SELECT section_value FROM users 
				INNER JOIN tbl_org_section ON tbl_org_section.gkey = users.section
				WHERE login_id = '$login_id'";

			$section_result = $this->bm->dataSelectDB1($section_query);
			
			$eng_section = null;
			if(count($section_result)>0)
			{
				$eng_section = $section_result[0]['section_value'];
			}

			$title = "Water Demand List";

			if($eng_section == "AENG" || $eng_section == "EXENCT" || $eng_section == "DCEE" || $eng_section == "SRSAE" || $org_Type_id == 81)
			{
				$title = "Water Bill Document Forwarding";
			}
			else if($org_Type_id == 82)
			{
				$title = "Water Document List";
			}

			$cond = "";

			if($rotation != null){
				if($org_Type_id == 78 || $org_Type_id == 83 || $org_Type_id == 81 || $section == 'acc')
				{
					$cond = " AND rotation_no = '$rotation'";
				}
				else
				{
					$cond = " WHERE rotation_no = '$rotation'";
				}
			}

			if($org_Type_id == 78)  // Sub Assistant Eng.  // XEN
			{
				if($eng_section == "SAECCT")
				{
					$cond.=" AND berth LIKE '%cct%'";
				}
				else if($eng_section == "SAENCT")
				{
					$cond.=" AND berth LIKE '%nct%'";
				}
				else if($eng_section == "SAEGCB" || $eng_section == "SRSAE")
				{
					$cond.=" AND berth LIKE '%gcb%'";
				}

				$query = "SELECT * FROM ctmsmis.water_demand_info WHERE supply_type = 'shore'".$cond." order by id desc";
			}
			else if($org_Type_id == 83 || $org_Type_id == 81)  // MARINE // HMASTER
			{
				$query = "SELECT * FROM ctmsmis.water_demand_info where supply_type = 'BURGE'".$cond." ORDER BY ID DESC";
			}
			else if($org_Type_id == 82 && $section == 'BILLOP')
			{
				$query = "SELECT * FROM ctmsmis.water_demand_info where  bill_op = '$login_id' ORDER BY ID DESC";
			}
			else if($org_Type_id == 82 && $section == 'ACC')
			{
				$query = "SELECT * FROM ctmsmis.water_demand_info 
				WHERE ctmsmis.water_demand_info.acc_aprv_st!=1 
				AND(ctmsmis.water_demand_info.xen_aprv_st='1' OR ctmsmis.water_demand_info.acc_aprv_st!=1)
				AND ctmsmis.water_demand_info.bill_op_bill_st!=1 ORDER BY ID DESC";
			}
			else
			{
				$query = "SELECT * FROM ctmsmis.water_demand_info where demand_by = '301053179M' ORDER BY ID DESC";
			}
			

			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);
			
			$data['billOpListRslt'] = $billOpListRslt;
			$result = $this->bm->dataSelectDb2($query);
			
			
			//echo "<pre>";
			//print_r($result);
			//echo "</pre>";
			//return;
			
			
			$data['eng_section'] = $eng_section;
			$data['result'] = $result;
			$data['title'] = $title;

			

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('waterDemandList',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function waterDemandListforDocMaster($rotation = null)
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$org_Type_id =$this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');

			$title = "Water Demand List";

			$cond = "";

			if($rotation != null){
				$cond = " AND rotation_no = '$rotation'";
			}
			
			$query = "";

			$query = "SELECT *,(SELECT SUM(burge_master_supply_qty) FROM ctmsmis.water_demand_burge_detail WHERE water_demand_id = water_demand_info.id) AS supplied_qty
			FROM ctmsmis.water_demand_info 
			WHERE supply_type = 'burge'".$cond." order by id desc";

			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);

			$data['billOpListRslt']=$billOpListRslt;
			$result = $this->bm->dataSelectDb2($query);
			// print_r($billOpListRslt);
			// return;
			$data['result'] = $result;
			$data['title'] = $title;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('waterDemandListforDocMaster',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function waterDemandForwardByAcc()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$org_Type_id =$this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');

			if($org_Type_id == 82 && $section == "acc")
			{
				if(isset($_POST['idchk']))
				{
					$ids = $_POST['idchk'];
				}

				$billOp = $this->input->post('billOp');

				$successCount = 0;
				$errorCount = 0;
					
				foreach($ids as $id)
				{
					$getDataQuery = "SELECT demand_aprv_st,dcee_aprv_st FROM ctmsmis.water_demand_info WHERE id = '$id'";
					$queryData = $this->bm->dataSelectDb2($getDataQuery);
					$st = 0;
					if(count($queryData)>0)
					{
						if(!is_null($queryData[0]['dcee_aprv_st']) || !is_null($queryData[0]['demand_aprv_st'])){
							$st = 1;
						}
						// $st = $queryData[0]['dcee_aprv_st'];
						// $st = $queryData[0]['demand_aprv_st'];
						if($st == 1)
						{
							$updateQuery = "UPDATE ctmsmis.water_demand_info SET bill_op = '$billOp' , acc_aprv_st = 1, acc_aprv_by = '$login_id' , acc_aprv_at = NOW() , acc_aprv_ip = '$ipAddr' WHERE id = '$id'";
							if($this->bm->dataUpdatedb2($updateQuery))
							{
								$successCount++;
							}
							else
							{
								$errorCount++;
							}
						}
						else
						{
							$errorCount++;
						}
					}
				}

				$this->session->set_flashdata("error", "<div class='alert alert-success'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>{$successCount} demand is forwarded successfully.</font></div>");
			}
			else
			{
				$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>You are not allowd to do this operation.</font></div>");
			}

			redirect('Vessel/waterDemandList/', 'refresh');
		}
	}

	function waterDemandFileUpload($id = null)
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		$serverIp = $_SERVER['SERVER_ADDR'];

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$fileName = $_FILES["file"]["name"];
			$fileTmpLoc = $_FILES["file"]["tmp_name"];
			$splitFile = explode(".", $fileName); // Split file name into an array using the dot
			$fileExt = end($splitFile); 

			if(is_null($id)){
				$id = $this->input->post('id');
			}

			$dataQuery = "SELECT rotation_no,docPath FROM ctmsmis.water_demand_info WHERE id = '$id'";
			$dataResult = $this->bm->dataSelectDb2($dataQuery);

			$rotation = "";
			$path = "";
			$count = 0;
			if(count($dataResult)>0)
			{
				$rotation = str_replace('/','_',$dataResult[0]['rotation_no']);
				$path = $dataResult[0]['docPath'];
				if(!is_null($path))
				{
					$splitPath = explode(".", $path);
					$count = substr($splitPath[0],-1) + 1;
				}
				
			}

			$fileName = $id."_".$rotation."_"."$count".".".$fileExt;
			$path = $_SERVER['DOCUMENT_ROOT']."/resources/waterBill/".date(Y_m);
			
			if(!file_exists($path)){
				mkdir($path, 0777, true);
				chmod($path, 0777);
			}

			$docPath = $path."/".$fileName;

			if($fileTmpLoc)
			{
				$moveResult = move_uploaded_file($fileTmpLoc,$docPath);

				if($moveResult == true)
				{
					$query = "UPDATE ctmsmis.water_demand_info SET docPath = '$fileName' WHERE id = '$id'";
					$upload_st = $this->bm->dataUpdatedb2($query);
					if($upload_st == 1)
					{
						$this->session->set_flashdata("error", "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Successfully uploaded...</font></div>");
					}
					else
					{
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Failed! Please try again...</font></div>");
						unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
					}

					//upload file to 245
					$pcsResult = file_get_contents('http://119.18.146.245/resources/waterBill/pullFile.php?fileName='.$fileName.'&ip='.$serverIp);

					//upload file to 185
					$pcsResult = file_get_contents('http://122.152.54.185/resources/waterBill/pullFile.php?fileName='.$fileName.'&ip='.$serverIp);

					//upload file to 179
					// $pcsResult = file_get_contents('http://119.18.146.245/resources/waterBill/pullFile.php?fileName='.$fileName.'&ip='.$serverIp);

				}
				else
				{
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>File not uploaded. Try again.</font></div>");
					unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
				}
			}

			$rotation = str_replace('_','/',$dataResult[0]['rotation_no']);
			$this->waterDemandList($rotation);
			return;
		}
	}


	function waterDemandForward($id = null)
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "waterDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$org_Type_id =$this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');

			// Find Engineer Section

			$section_query = "SELECT section_value FROM users 
				INNER JOIN tbl_org_section ON tbl_org_section.gkey = users.section
				WHERE login_id = '$login_id'";
			$section_result = $this->bm->dataSelectDB1($section_query);

			$eng_section = null;
			if(count($section_result)>0)
			{
				$eng_section = $section_result[0]['section_value'];
			}

			$dataQuery = "select * from ctmsmis.water_demand_info where id = '$id'";
			$dataResult = $this->bm->dataSelectDb2($dataQuery);

			if($org_Type_id == 81 || $org_Type_id == 83 || $org_Type_id == 78 || $org_Type_id == 82)
			{
				$forward_query = "";

				if($org_Type_id == 83)
				{
					$forward_query = "UPDATE ctmsmis.water_demand_info SET marine_aprv_st = 1, marine_aprv_by = '$login_id', marine_aprv_at = NOW(), marine_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Marine
				}
				else if($org_Type_id == 81)
				{
					$forward_query = "UPDATE ctmsmis.water_demand_info SET demand_aprv_st = 1, demand_aprv_by = '$login_id', demand_aprv_at = NOW(), demand_aprv_ip = '$ipAddr' WHERE id = '$id'"; // HMaster
				}
				else if($org_Type_id == 78)
				{
					if(strtoupper(substr($eng_section,0,3)) == "SAE")
					{
						if($dataResult[0]['asst_eng_dispute_st'] == 1)
						{
							$forward_query = "UPDATE ctmsmis.water_demand_info SET asst_eng_dispute_st = 0, eng_aprv_by = '$login_id', eng_aprv_at = NOW(), eng_aprv_ip = '$ipAddr' WHERE id = '$id'"; 
						}
						else
						{
							$forward_query = "UPDATE ctmsmis.water_demand_info SET eng_aprv_st = 1, eng_aprv_by = '$login_id', eng_aprv_at = NOW(), eng_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Sub Asst. Engineer
						}
					}
					else if($eng_section == "SRSAE")
					{
						if($dataResult[0]['asst_eng_dispute_st'] == 1)
						{
							$forward_query = "UPDATE ctmsmis.water_demand_info SET asst_eng_dispute_st = 0, sr_sub_eng_aprv_by = '$login_id', sr_sub_eng_aprv_at = NOW(), sr_sub_eng_aprv_ip = '$ipAddr' WHERE id = '$id'"; 
						}
						else
						{
							$forward_query = "UPDATE ctmsmis.water_demand_info SET sr_sub_eng_aprv_st = 1, sr_sub_eng_aprv_by = '$login_id', sr_sub_eng_aprv_at = NOW(), sr_sub_eng_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Sub Asst. Engineer
						}
					}
					else if($eng_section == "AENG")
					{
						$forward_query = "UPDATE ctmsmis.water_demand_info SET asst_eng_aprv_st = 1, asst_eng_aprv_by = '$login_id', asst_eng_aprv_at = NOW(), asst_eng_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Sub Asst. Engineer
					}
					else if($eng_section == "EXENCT")
					{
						// $forward_query = "UPDATE ctmsmis.water_demand_info SET xen_aprv_st = 1, xen_aprv_by = '$login_id', xen_aprv_at = NOW(), xen_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Exen

						$forward_query = "UPDATE ctmsmis.water_demand_info SET xen_aprv_st = 1, xen_aprv_by = '$login_id', xen_aprv_at = NOW(), xen_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Exen
					}
					else if($eng_section == "DCEE")
					{
						$forward_query = "UPDATE ctmsmis.water_demand_info SET dcee_aprv_st = 1, dcee_aprv_by = '$login_id', dcee_aprv_at = NOW(), dcee_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Deputy Chief

						// $forward_query = "UPDATE ctmsmis.water_demand_info SET dcee_aprv_st = 1, dcee_aprv_by = '$login_id', dcee_aprv_at = NOW(), dcee_aprv_ip = '$ipAddr', dcfo_aprv_st = 1, dcfo_aprv_by = '$login_id', dcfo_aprv_at = NOW(), dcfo_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Deputy Chief
					}
					
				}
				else if($org_Type_id == 82)
				{
					if($section == "dcfo")   // Deputy Chief Finance
					{
						$forward_query = "UPDATE ctmsmis.water_demand_info SET dcfo_aprv_st = 1, dcfo_aprv_by = '$login_id', dcfo_aprv_at = NOW(), dcfo_aprv_ip = '$ipAddr' WHERE id = '$id'";
					}
					else if($section == "acc")   // Accountant
					{
						$forward_query = "UPDATE ctmsmis.water_demand_info SET acc_aprv_st = 1, acc_aprv_by = '$login_id', acc_aprv_at = NOW(), acc_aprv_ip = '$ipAddr' WHERE id = '$id'";
					}
					// else if($section == "billop")   // Bill Operator
					// {
					// 	$forward_query = "UPDATE water_demand_info SET xen_aprv_st = 1, xen_aprv_by = '$login_id', xen_aprv_at = NOW(), xen_aprv_ip = '$ipAddr' WHERE id = '$id'"; 
					// }
				}
				
				
				if($this->bm->dataUpdatedb2($forward_query))
				{
					$this->session->set_flashdata("error", "<div class='alert alert-success'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<font size='4'>Successfully forwarded...</font></div>");
				}
				else
				{
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Failed! Please try again...</font></div>");
				}
			}
			else
			{
				$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>You are not allowd to do this operation.</font></div>");
			}

			$rotation = str_replace('_','/',$dataResult[0]['rotation_no']);
			$this->waterDemandList($rotation);
			
			// redirect('Vessel/waterDemandList/', 'refresh');
		}
	}

	function supplyDateUpdate($id = null)
	{
		/* $session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "waterDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{ */
			$org_Type_id =$this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');
			$supplyDate = $this->input->post('supplyDate');
			$supply_id = $this->input->post('supply_id');
			$update_supply_date_str = "UPDATE ctmsmis.water_demand_info SET water_demand_info.supply_date='$supplyDate' WHERE id = '$supply_id'";
			//return;
			$this->bm->dataUpdatedb2($update_supply_date_str);
			redirect('Vessel/waterDemandList/', 'refresh');
		//}
	}

	function waterDemandBackward($id = null)
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "waterDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$org_Type_id =$this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');
			$remarks = $this->input->post('remarks');
			if(is_null($id)){
				$id = $this->input->post('id');
			}

			// Find Engineer Section

			$section_query = "SELECT section_value FROM users 
				INNER JOIN tbl_org_section ON tbl_org_section.gkey = users.section
				WHERE login_id = '$login_id'";
			$section_result = $this->bm->dataSelectDB1($section_query);

			$eng_section = null;
			if(count($section_result)>0)
			{
				$eng_section = $section_result[0]['section_value'];
			}

			$dataQuery = "select * from ctmsmis.water_demand_info where id = '$id'";
			$dataResult = $this->bm->dataSelectDb2($dataQuery);
			
			if(count($dataResult)>0)
			{
				if($org_Type_id == 81 || $org_Type_id == 83 || $org_Type_id == 78 || $org_Type_id == 82)
				{
					$forward_query = "";

					// if($org_Type_id == 83)
					// {
					// 	$forward_query = "UPDATE ctmsmis.water_demand_info SET marine_aprv_st = 1, marine_aprv_by = '$login_id', marine_aprv_at = NOW(), marine_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Marine
					// }
					// else if($org_Type_id == 81)
					// {
					// 	$forward_query = "UPDATE ctmsmis.water_demand_info SET demand_aprv_st = 1, demand_aprv_by = '$login_id', demand_aprv_at = NOW(), demand_aprv_ip = '$ipAddr' WHERE id = '$id'"; // HMaster
					// }
					// else 
					if($org_Type_id == 78)
					{
						// if(strtoupper(substr($eng_section,0,3)) == "SAE")
						// {
						// 	$forward_query = "UPDATE ctmsmis.water_demand_info SET eng_aprv_st = 1, eng_aprv_by = '$login_id', eng_aprv_at = NOW(), eng_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Sub Asst. Engineer
						// }
						// else if($eng_section == "SRSAE")
						// {
						// 	$forward_query = "UPDATE ctmsmis.water_demand_info SET sr_sub_eng_aprv_st = 1, sr_sub_eng_aprv_by = '$login_id', sr_sub_eng_aprv_at = NOW(), sr_sub_eng_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Sub Asst. Engineer
						// }
						// else 
						if($eng_section == "AENG") // Sub Asst. Engineer
						{
							$forward_query = "UPDATE ctmsmis.water_demand_info SET asst_eng_dispute_st = 1, asst_eng_dispute_by = '$login_id', asst_eng_dispute_at = NOW(), asst_eng_dispute_remarks = '$remarks' WHERE id = '$id'";
						}
						// else if($eng_section == "EXENCT")
						// {
						// 	$forward_query = "UPDATE ctmsmis.water_demand_info SET xen_aprv_st = 1, xen_aprv_by = '$login_id', xen_aprv_at = NOW(), xen_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Exen
						// }
						// else if($eng_section == "DCEE")
						// {
						// 	$forward_query = "UPDATE ctmsmis.water_demand_info SET dcee_aprv_st = 1, dcee_aprv_by = '$login_id', dcee_aprv_at = NOW(), dcee_aprv_ip = '$ipAddr' WHERE id = '$id'"; // Exen
						// }
						
					}
					// else if($org_Type_id == 82)
					// {
					// 	if($section == "acc")   // Accountant
					// 	{
					// 		$forward_query = "UPDATE ctmsmis.water_demand_info SET acc_aprv_st = 1, acc_aprv_by = '$login_id', acc_aprv_at = NOW(), acc_aprv_ip = '$ipAddr' WHERE id = '$id'";
					// 	}
					// 	// else if($section == "billop")   // Bill Operator
					// 	// {
					// 	// 	$forward_query = "UPDATE water_demand_info SET xen_aprv_st = 1, xen_aprv_by = '$login_id', xen_aprv_at = NOW(), xen_aprv_ip = '$ipAddr' WHERE id = '$id'"; 
					// 	// }
					// }
					
					if($this->bm->dataUpdatedb2($forward_query))
					{
						$this->session->set_flashdata("error", "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Successfully backwarded...</font></div>");

						$logQuery = "INSERT INTO ctmsmis.water_demand_log(water_demand_id,remarks,login_id,section,time,ip) VALUES('$id','$remarks','$login_id','$eng_section',NOW(),'$ipAddr')";
						$this->bm->dataInsertDb2($logQuery);
					}
					else
					{
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Failed! Please try again...</font></div>");
					}
				}
				else
				{
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>You are not allowd to do this operation.</font></div>");
				}
			}
			else
			{
				$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Failed! Please try again...</font></div>");
			}
			

			redirect('Vessel/waterDemandList/', 'refresh');
		}
	}



	function waterBillDocumentForwarding()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "waterBillDocumentForwarding"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$org_Type_id =$this->session->userdata('org_Type_id');
			$title = "Water Bill Document Forwarding";

			$section_query = "SELECT section_value FROM users 
				INNER JOIN tbl_org_section ON tbl_org_section.gkey = users.section
				WHERE login_id = '$login_id'";
			$section_result = $this->bm->dataSelectDB1($section_query);

			$eng_section = null;
			if(count($section_result)>0)
			{
				$eng_section = $section_result[0]['section_value'];
			}

			if(isset($_POST['submit']))
			{
				$rotation = trim($this->input->post('rotation'));
				$supply_date = trim($this->input->post('supply_date'));
				
				//$vslQuery = "SELECT Vessel_Name FROM igm_masters WHERE Import_Rotation_No = '$rotation'";
				$vslResult = $this->bm->getVslNameN4($rotation);
				$vsl_name = "";
				if(count($vslResult)>0){
					$vsl_name = $vslResult;
				}

				// $duplicateChkQuery = "SELECT COUNT(*) AS rtnValue,
				// CASE
				// 	WHEN marine_aprv_st = 1 OR eng_aprv_st = 1 THEN 1
				// 	ELSE 0
				// END AS forwarded_st
				// FROM ctmsmis.water_demand_info WHERE rotation_no = '$rotation'";

				// $duplicateResult = $this->bm->dataSelect($duplicateChkQuery);
				
				// $duplicateChk = null;
				// $forwarded_st = null;
				// if(count($duplicateResult)>0)
				// {
				// 	$duplicateChk = $duplicateResult[0]['rtnValue'];
				// 	$forwarded_st = $duplicateResult[0]['forwarded_st'];
				// }

				// if($duplicateChk>0 && $forwarded_st == 1)
				// {
				// 	$this->session->set_flashdata("error", "<div class='alert alert-danger'>
				// 	<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
				// 	<font size='4'>Already forwarded for rotation: {$rotation}</font></div>");
				// }
				// else if($duplicateChk>0)
				// {
				// 	$this->session->set_flashdata("error", "<div class='alert alert-danger'>
				// 	<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
				// 	<font size='4'>Duplicate Rotation found. Please try with different rotation...</font></div>");
				// }
				// else
				// {
					$supplyQuery = "";
					$allowed = array('SAECCT','SAEGCB','SAENCT');
					if($org_Type_id == 78 && in_array($eng_section,$allowed))  // Sub Assistant Eng.
					{
						$supplyType = "shore";
						$supplyQuery = "SELECT quantity, quantity_unit, created AS demand_date FROM vsl_vessel_visit_details 
						INNER JOIN srv_event ON srv_event.applied_to_gkey=vsl_vessel_visit_details.vvd_gkey
						WHERE ib_vyg='$rotation' AND srv_event.event_type_gkey='169'";
					}
					else if($org_Type_id == 83)  // Marine 
					{
						$supplyType = "burge";
						$supplyQuery = "SELECT quantity, quantity_unit, created AS demand_date FROM vsl_vessel_visit_details 
						INNER JOIN srv_event ON srv_event.applied_to_gkey=vsl_vessel_visit_details.vvd_gkey
						WHERE ib_vyg='$rotation' AND srv_event.event_type_gkey IN(168,459,461,463)";
					}

					$supplyStatus =count($supplyResult) ;
					$water_demand = null;
					$unit = null;
					$demand_date = null;

					if($supplyQuery != ""){
						$supplyResult = $this->bm->dataSelect($supplyQuery);

						if(count($supplyResult)>0){
						
							$water_demand = $supplyResult[0]["QUANTITY"];
							$unit = $supplyResult[0]["QUANTITY_UNIT"];
							$demand_date = $supplyResult[0]["DEMAND_DATE"];
						}
					}
					
					// Find Berth

					$berthQuery = "SELECT argo_quay.id as berth
					FROM argo_quay
					INNER JOIN vsl_vessel_berthings brt ON brt.quay=argo_quay.gkey
					INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=brt.vvd_gkey
					WHERE vsl_vessel_visit_details.ib_vyg = '$rotation' ORDER BY atd DESC FETCH FIRST  1 ROWS ONLY";
					$berthResult = $this->bm->dataSelect($berthQuery);
					
					$berth = null;

					if(count($berthResult)>0){
						$berth = $berthResult[0]['BERTH'];
					}

					$berthPart = strtoupper(substr($berth,0,1));
					$engPart = strtoupper(substr($eng_section,3,1));

					if($supplyStatus == 1)
					{
						if($berthPart == $engPart || $org_Type_id == 83)
						{
							if($supplyType == "shore")
							{
								// $query = "INSERT INTO ctmsmis.water_demand_info(rotation_no,vessel_name,supply_type,berth,demand_qty,demand_unit,demand_date,demand_by,demand_at,demand_ip,eng_aprv_st,eng_aprv_by,eng_aprv_at,eng_aprv_ip) VALUES('$rotation','$vsl_name','$supplyType','$berth','$water_demand','$unit','$demand_date','$login_id',NOW(),'$ipAddr',1,'$login_id',NOW(),'$ipAddr')";
								$query = "INSERT INTO ctmsmis.water_demand_info(rotation_no,vessel_name,supply_type, supply_date, berth,demand_qty,demand_unit,demand_date,demand_by,demand_at,demand_ip) VALUES('$rotation','$vsl_name','$supplyType', '$supply_date', '$berth','$water_demand','$unit','$demand_date','$login_id',NOW(),'$ipAddr')";
							}
							else if($supplyType == "burge")
							{
								// $query = "INSERT INTO ctmsmis.water_demand_info(rotation_no,vessel_name,supply_type,berth,demand_qty,demand_unit,demand_date,demand_by,demand_at,demand_ip,marine_aprv_st,marine_aprv_by,marine_aprv_at,marine_aprv_ip) VALUES('$rotation','$vsl_name','$supplyType','$berth','$water_demand','$unit','$demand_date','$login_id',NOW(),'$ipAddr',1,'$login_id',NOW(),'$ipAddr')";
								$query = "INSERT INTO ctmsmis.water_demand_info(rotation_no,vessel_name,supply_type, supply_date, berth,demand_qty,demand_unit,demand_date,demand_by,demand_at,demand_ip) VALUES('$rotation','$vsl_name','$supplyType', '$supply_date', '$berth','$water_demand','$unit','$demand_date','$login_id',NOW(),'$ipAddr')";
							}
							
							if($this->bm->dataInsertDb2($query)){
								$this->session->set_flashdata("error", "<div class='alert alert-success'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<font size='4'>Successfully forwarded...</font></div>", 3);
							}else{
								$this->session->set_flashdata("error", "<div class='alert alert-danger'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<font size='4'>Failed! Please try again...</font></div>", 3);
							}
						}
						else
						{
							$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>You are not authorize to forward this rotation.</font></div>");
						}
						
					}
					else
					{
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Water not supplied</font></div>");
					}
				// }

				$this->waterDemandList($rotation);
				return;
			}

			$data['title'] = $title;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('waterBillDocumentForwarding',$data);
			$this->load->view('jsAssets');
		}
	}

	function waterSpplyByLineForwarding()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "waterSpplyByLineForwarding"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$org_Type_id =$this->session->userdata('org_Type_id');
			$title = "Water Supply Document Forwarding";

			$section_query = "SELECT section_value FROM users 
				INNER JOIN tbl_org_section ON tbl_org_section.gkey = users.section
				WHERE login_id = '$login_id'";
			$section_result = $this->bm->dataSelectDB1($section_query);

			$eng_section = null;
			$msg = "";
			if(count($section_result)>0)
			{
				$eng_section = $section_result[0]['section_value'];
			}
			
			if(isset($_POST['submit']))
			{
				$rotation = trim($this->input->post('rotation'));
				$supply_date = trim($this->input->post('supply_date'));
				$supply_qty = trim($this->input->post('supply_qty'));
				
				$userChk = "SELECT COUNT(*) as rtnValue FROM base_user where base_user.buser_userid ='$login_id'";			 
				$userChkRtn = $this->bm->dataReturn($userChk);
				if($userChkRtn<1)
				{
					$msg = "<font color='red' size='4'><strong>Sorry! User: $login_id not exist in N4.</strong></font>";									
				}
				else
				{
					
				
				
					//$vslQuery = "SELECT Vessel_Name FROM igm_masters WHERE Import_Rotation_No = '$rotation'";
					$vslResult = $this->bm->getVslNameN4($rotation);
					$vsl_name = "";
					if(count($vslResult)>0){
						$vsl_name = $vslResult;
					}

						$supplyCond="";
						$supplyQuery = "";
						$allowed = array('SAECCT','SAEGCB','SAENCT');
						if($org_Type_id == 78 && in_array($eng_section,$allowed))  // Sub Assistant Eng.
						{
							$supplyType = "shore";
							//$supplyCond  = " AND sparcsn4.srv_event.event_type_gkey=169";
						}
						else if($org_Type_id == 83)  // Marine 
						{
							$supplyType = "burge";
							//$supplyCond = " AND sparcsn4.srv_event.event_type_gkey IN(168,459,461,463)";
						} 
						
						$supplyQuery = "SELECT vsl_vessel_visit_details.vvd_gkey,argo_carrier_visit.id AS visit_id, vsl_vessels.name AS vessel_name
						FROM vsl_vessel_visit_details 
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
						INNER JOIN vsl_vessels ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey
						WHERE ib_vyg='$rotation' ".$supplyCond;
						
						$vvd_gkey = null;
						$visit_id = null;
						$vessel_name = null;
						$unit='METRIC_TONNES';
						
						$supplyResult = $this->bm->dataSelect($supplyQuery);

						if(count($supplyResult)>0)
						{
							$vvd_gkey = $supplyResult[0]["VVD_GKEY"];
							$visit_id = $supplyResult[0]["VISIT_ID"];
							$vessel_name = $supplyResult[0]["VESSEL_NAME"];
							//$demand_date = $supplyResult[0]["demand_date"];
						}
						
						// Find Berth

						// $berthQuery = "SELECT argo_quay.id as berth
						// FROM argo_quay
						// INNER JOIN vsl_vessel_berthings brt ON brt.quay=argo_quay.gkey
						// INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=brt.vvd_gkey
						// WHERE vsl_vessel_visit_details.ib_vyg = '$rotation' ORDER BY atd DESC fetch first 1 rows only";

						$berthQuery = "SELECT argo_quay.id as berth
						FROM argo_quay
						INNER JOIN vsl_vessel_berthings brt ON brt.quay=argo_quay.gkey
						INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=brt.vvd_gkey
						WHERE vsl_vessel_visit_details.ib_vyg = '$rotation'";

						// $berthResult = $this->bm->dataSelect($berthQuery);
						$berths = $this->bm->dataSelect($berthQuery);

						
						// $berth = "";

						// if(count($berthResult)>0){
						// 	$berth = $berthResult[0]['BERTH'];
						// }

						// $berthPart = strtoupper(substr($berth,0,1));
						$engPart = strtoupper(substr($eng_section,3));

						$haveBerth = 0;
						foreach($berths as $berth){
							$res = strpos($berth['BERTH'],$engPart);
							if($res !== false){
								$haveBerth = 1;
								break;
							}
						}
						// echo $haveBerth;

						// return;
						
						/* if($supplyStatus == 1)
						{ */
							if($haveBerth || $org_Type_id == 83)
							{
								if($supplyType == "shore")
								{
									// $query = "INSERT INTO ctmsmis.water_demand_info(rotation_no,vessel_name,supply_type,berth,demand_qty,demand_unit,demand_date,demand_by,demand_at,demand_ip,eng_aprv_st,eng_aprv_by,eng_aprv_at,eng_aprv_ip) VALUES('$rotation','$vsl_name','$supplyType','$berth','$water_demand','$unit','$demand_date','$login_id',NOW(),'$ipAddr',1,'$login_id',NOW(),'$ipAddr')";
									$query = "INSERT INTO ctmsmis.water_demand_info(rotation_no,vessel_name,supply_type, supply_date, berth,demand_qty,demand_unit,demand_date,demand_by,demand_at,demand_ip) VALUES('$rotation','$vsl_name','$supplyType', '$supply_date', '$berth','$supply_qty','$unit','$demand_date','$login_id',NOW(),'$ipAddr')";
								}
								else if($supplyType == "burge")
								{
									// $query = "INSERT INTO ctmsmis.water_demand_info(rotation_no,vessel_name,supply_type,berth,demand_qty,demand_unit,demand_date,demand_by,demand_at,demand_ip,marine_aprv_st,marine_aprv_by,marine_aprv_at,marine_aprv_ip) VALUES('$rotation','$vsl_name','$supplyType','$berth','$water_demand','$unit','$demand_date','$login_id',NOW(),'$ipAddr',1,'$login_id',NOW(),'$ipAddr')";
									$query = "INSERT INTO ctmsmis.water_demand_info(rotation_no,vessel_name,supply_type, supply_date, berth,demand_qty,demand_unit,demand_date,demand_by,demand_at,demand_ip) VALUES('$rotation','$vsl_name','$supplyType', '$supply_date', '$berth','$water_demand','$unit','$demand_date','$login_id',NOW(),'$ipAddr')";
								}
								if($this->bm->dataInsertDb2($query))
								{
									if($supplyType == "shore")
										{
											if($supply_qty>0)
											{ 
												$strInsrtWaterMainLineEvent = "INSERT INTO srv_event(operator_gkey,complex_gkey,facility_gkey,yard_gkey,placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator)
															VALUES('1','1','1','1','$login_id',CURRENT_DATE,'169','VV','$vvd_gkey','$visit_id','$supply_qty','METRIC_TONNES',CURRENT_DATE,'$login_id')";
												$this->bm->dataInsert($strInsrtWaterMainLineEvent);	
											}
										}
										
									
									$this->session->set_flashdata("error", "<div class='alert alert-success'>
									<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
									<font size='4'>Successfully forwarded...</font></div>", 3);
								}
								else
								{
									$this->session->set_flashdata("error", "<div class='alert alert-danger'>
									<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
									<font size='4'>Failed! Please try again...</font></div>", 3);
								}
							}
							else
							{
								$this->session->set_flashdata("error", "<div class='alert alert-danger'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<font size='4'>You are not authorize to forward this rotation.</font></div>");
							}
							
						//}
						/* else
						{
							$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Water not supplied</font></div>");
						} */
					// }

					$this->waterDemandList($rotation);
					return;
				}
			}
			
			$data['title'] = $title;
			$data['msg'] = $msg;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('waterSpplyByLineForwarding',$data);
			$this->load->view('jsAssets');
		}
	}

	// Water Demand -- end
	
	// Hot Work Demand -- start

	function hotWorkDemandForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "hotWorkDemandForm"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Hot Work Demand Form";

			if(isset($_POST['submit']))
			{
				$vsl_name = trim($this->input->post('vsl_name'));
				//$voy_no = trim($this->input->post('voy_no'));
				$rotation = trim($this->input->post('rotation'));
				$berth_hr = trim($this->input->post('berth_hr'));
				$berth_date = trim($this->input->post('berth_date'));

				$duplicateChkQuery = "SELECT count(*) as rtnValue FROM ctmsmis.hotwork_demand WHERE rotation = '$rotation' AND vessel_name = '$vsl_name'";
				$duplicateChk = $this->bm->dataReturn($duplicateChkQuery);

				if($duplicateChk>0)
				{
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<font size='4'>Duplicate combination found. Please try with different combination...</font></div>", 3);
				}
				else
				{
					$igmMasterIdQuery = "SELECT id FROM igm_masters WHERE Import_Rotation_No = '$rotation' AND Vessel_Name = '$vsl_name'";
					$igmMasters = $this->bm->dataSelectDB1($igmMasterIdQuery);

					$igm_master_id = null;
					if(count($igmMasters)>0){
						$igm_master_id = $igmMasters[0]['id'];
					}

					if(!is_null($igm_master_id))
					{
						$query = "INSERT INTO ctmsmis.hotwork_demand(igm_masters_id,rotation,vessel_name,start_time,start_date,entry_by,entry_at,entry_ip) VALUES('$igm_master_id','$rotation','$vsl_name','$berth_hr','$berth_date','$login_id',NOW(),'$ipAddr')";
						if($this->bm->dataInsert($query)){
							$this->session->set_flashdata("error", "<div class='alert alert-success'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Demand successfully submitted...</font></div>", 3);
						}else{
							$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Failed! Please try again...</font></div>", 3);
						}
					}
					else
					{
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Wrong Vessel Name or Voyage No given! Please try again...</font></div>", 3);
					}

				}

				redirect('Vessel/hotWorkDemandForm/', 'refresh');
				return;
			}

			$data['title'] = $title;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkDemandForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function hotWorkDemandList()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		$supplyStatus=0;
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "hotWorkDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Hot Work Demand List";
			
			$query = "SELECT * FROM ctmsmis.hotwork_demand WHERE  fire_dpt_aprv_st = 0";
			$result = $this->bm->dataSelect($query);

			$data['result'] = $result;
			$data['title'] = $title;
			$data['msg'] = "";
			$data['status']=$supplyStatus;
            $this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkDemandList',$data);
			$this->load->view('jsAssetsList');
		}
	}

	// Hot Work Demand -- end
  
   function hotWorkDemandListFoward()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		//$this->session->set_userdata(array('menu' => "Bill"));
		//$this->session->set_userdata(array('sub_menu' => "waterDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			
			if(!empty($_POST['list']))
			{
				
				
				foreach($_POST['list'] as $fireId)
				{ 
					$forward_query = "UPDATE ctmsmis.hotwork_demand SET fire_dpt_aprv_st = 1, fire_dpt_aprv_by = '$login_id', fire_dpt_aprv_at = NOW(), fire_dpt_aprv_ip = '$ipAddr' WHERE id = '$fireId'";
					$this->bm->dataUpdate($forward_query);
					$data['msg'] = "<font color='green' size='4'><strong>Forwarded Successfully</strong></font>";	
				}
				
			} 
			else{
				$data['msg'] = "<font color='red' size='4'><strong>No Work Damand has been selected</strong></font>";	

			}
		
			
			$title = "Hot Work Demand List";
			
			$query = "SELECT * FROM ctmsmis.hotwork_demand WHERE  fire_dpt_aprv_st = 0 ";
			$result = $this->bm->dataSelect($query);

			$data['result'] = $result;
			$data['title'] = $title;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkDemandList',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function hotWorkDemandApproveList()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "hotWorkDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Hot Work Demand Forward List";			
			$query = "SELECT * FROM ctmsmis.hotwork_demand WHERE  fire_dpt_aprv_st = 1 ORDER BY hotwork_demand.id DESC";
			$result = $this->bm->dataSelectDb2($query);
			
			
			

			$data['result'] = $result;
			$data['title'] = $title;
			$data['msg'] = "";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkDemandApproveList',$data);
			$this->load->view('jsAssetsList');
		}
	}


	function hotWorkDemandListForAccount()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		$section = $this->session->userdata('section');
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "hotWorkDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Hot Work Demand List";
			
			$query = "";
			if($login_id == "dirsecurity")
			{
				$query = "SELECT * FROM ctmsmis.hotwork_demand WHERE  fire_dpt_aprv_st = 1 AND director_aprv_st = 0 ORDER BY id DESC";
			}
			else if($section == "dcfo")
			{
				$query = "SELECT * FROM ctmsmis.hotwork_demand WHERE  director_aprv_st = 1 AND dcfo_aprv_st = 0 ORDER BY id DESC";
			}
			else if($section == "acc")
			{
				// $query = "SELECT * FROM ctmsmis.hotwork_demand WHERE  dcfo_aprv_st = 1 AND acc_aprv_st = 0 ORDER BY id DESC"; // DCFO Removed
				$query = "SELECT * FROM ctmsmis.hotwork_demand WHERE  director_aprv_st = 1 AND acc_aprv_st = 0 ORDER BY id DESC";
			}

			$result = $this->bm->dataSelectDb2($query);
			$data['result'] = $result;
			
			$billOpListStr="SELECT login_id, u_name FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);
			$data['billOpListRslt']=$billOpListRslt;
			
			$data['title'] = $title;
			$data['section'] = $section;
			$data['msg'] = "";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkDemandListForAccount',$data);
			$this->load->view('jsAssetsList');
		}
	}


	function hotWorkDemandListFowardByAccount()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$section = $this->session->userdata('section');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		//$this->session->set_userdata(array('menu' => "Bill"));
		//$this->session->set_userdata(array('sub_menu' => "waterDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{		
			if(isset($_POST['idchk']))
			{	
				$billOp = $this->input->post("billOp");
				$selectedIds = $_POST['idchk'];
				foreach ($selectedIds as $fireId){
					$forward_query = "";
					if($section == "acc")
					{
						$forward_query = "UPDATE ctmsmis.hotwork_demand SET acc_aprv_st = 1, acc_aprv_by = '$login_id', acc_aprv_at = NOW(), acc_aprv_ip = '$ipAddr', forwarded_to_bill_op_by='$login_id',
						forwarded_to_bill_op_at=NOW(),forwarded_to_bill_op_by_ip='$ipAddr',bill_op_user_id='$billOp'
						WHERE id = '$fireId'";
					}
					else if($section == "dcfo")
					{
						$forward_query = "UPDATE ctmsmis.hotwork_demand SET dcfo_aprv_st = 1, dcfo_aprv_by = '$login_id', dcfo_aprv_at = NOW(), dcfo_aprv_ip = '$ipAddr'
						WHERE id = '$fireId'";
					}
					else if($login_id == "dirsecurity")
					{
						$forward_query = "UPDATE ctmsmis.hotwork_demand SET director_aprv_st = 1, director_aprv_by = '$login_id', director_aprv_at = NOW(), director_aprv_ip = '$ipAddr'
						WHERE id = '$fireId'";
					}
					else
					{
						$data['msg'] = "<font color='red' size='4'><strong>Something Wrong!</strong></font>";
					}
					
					$this->bm->dataUpdatedb2($forward_query);
					$data['msg'] = "<font color='blue' size='3'><strong>Forwarded Successfully</strong></font>";
				}
			}
			else 
			{
				$data['msg'] = "<font color='red' size='3'><strong>No Work Damand has been selected</strong></font>";
			}
			
			
			$title = "Hot Work Demand List";
			
			$query = "";
			if($login_id == "dirsecurity")
			{
				$query = "SELECT * FROM ctmsmis.hotwork_demand WHERE  fire_dpt_aprv_st = 1 AND director_aprv_st = 0 ORDER BY id DESC";
			}
			else if($section == "dcfo")
			{
				$query = "SELECT * FROM ctmsmis.hotwork_demand WHERE  director_aprv_st = 1 AND dcfo_aprv_st = 0 ORDER BY id DESC";
			}
			else if($section == "acc")
			{
				$query = "SELECT * FROM ctmsmis.hotwork_demand WHERE  dcfo_aprv_st = 1 AND acc_aprv_st = 0 ORDER BY id DESC";
			}
			
			$result = $this->bm->dataSelectDb2($query);
			$data['result'] = $result;
			
			$billOpListStr="SELECT login_id, u_name FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);
			$data['billOpListRslt']=$billOpListRslt;

			$data['section'] = $section;			
			$data['title'] = $title;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkDemandListForAccount',$data);
			$this->load->view('jsAssetsList');
		}
	}


	function hotWorkDemandApproveListForAccount()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "hotWorkDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Hot Work Demand Approved List";
			
			$query = "SELECT * FROM ctmsmis.hotwork_demand  WHERE  fire_dpt_aprv_st = 1 AND acc_aprv_st=1";
			$result = $this->bm->dataSelectDb2($query);

			$data['result'] = $result;
			$data['title'] = $title;
			$data['msg'] = "";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkDemandApproveListForAccount',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function hotWorkDemandApproveListForBillOperator()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "hotWorkDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Hot Work Demand Approved List";
			
			$query = "SELECT rotation,vessel_name,start_date,service_date,start_time,bill_op_bill_st
			FROM ctmsmis.hotwork_demand
			WHERE fire_dpt_aprv_st = 1 AND acc_aprv_st=1 and bill_op_user_id='$login_id' order by hotwork_demand.id desc";
			// echo $query;return;
			$result = $this->bm->dataSelectDb2($query);

			$data['result'] = $result;
			$data['title'] = $title;
			$data['msg'] = "";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkDemandApproveListForBillOperator',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function billGenerateForBillOperator()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "hotWorkDemandList"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$hotdemandId = $this->input->post('demandId');
			$title = "Hot Work Demand Approved List";
			
			/*$query = "SELECT * FROM ctmsmis.hotwork_demand  WHERE  fire_dpt_aprv_st = 1 AND acc_aprv_st=1";
			$result = $this->bm->dataSelect($query);

			$data['result'] = $result;
			$data['title'] = $title;
			$data['msg'] = "";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkDemandApproveListForBillOperator',$data);
			$this->load->view('jsAssetsList');*/
		}
	}
	
	function hotWorkDemandListForMlo()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		$supplyStatus=0;
		
		$this->session->set_userdata(array('menu' => "Bill"));
		$this->session->set_userdata(array('sub_menu' => "hotWorkDemandListForMlo"));

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$title = "Hot Work Demand List";
			
			$query = "SELECT * FROM ctmsmis.hotwork_demand WHERE  entry_by = '$login_id' ORDER BY id Desc";
			$result = $this->bm->dataSelectDb2($query);

			$data['result'] = $result;
			$data['title'] = $title;
			$data['msg'] = "";
			$data['status']=$supplyStatus;
            $this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkDemandListForMlo',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function fireHotWorkDemandForm()
	{
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Hot Work Service Form";
			$data['msg'] = "";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('fireHotWorkDemandForm',$data);
			$this->load->view('jsAssets');
		}	
    }

	function saveHotWorkDemandForFire()
	{
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$rotation = trim($this->input->post('rotation'));
			$service_date = trim($this->input->post('service_date'));
			$login_id = $this->session->userdata('login_id');
			$ipAddr = $_SERVER['REMOTE_ADDR'];

			$duplicateChkQuery = "SELECT count(*) as rtnValue FROM ctmsmis.hotwork_demand 
								WHERE rotation = '$rotation' AND service_date='$service_date' AND fire_dpt_aprv_st = 1";
			$duplicateChk = $this->bm->dataReturnDb2($duplicateChkQuery);

			if($duplicateChk>0){
				$title = "Hot Work Service Forward List";
		
				$approvedListQuery = "SELECT * FROM ctmsmis.hotwork_demand WHERE fire_dpt_aprv_st = 1 ORDER BY hotwork_demand.id DESC";
				$approvedList = $this->bm->dataSelectDb2($approvedListQuery);

				$data['result'] = $approvedList;
				$data['title'] = $title;
				$data['msg'] = "";

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('hotWorkDemandApproveList',$data);
				$this->load->view('jsAssetsList');
				


			} else{
				$msg="";

				$igmMasterIdQuery = "SELECT * FROM igm_masters WHERE Import_Rotation_No = '$rotation'";
				$igmMasters = $this->bm->dataSelectDB1($igmMasterIdQuery);

				$eventQuery="SELECT vsl_vessels.name AS vessel_name,to_char(vsl_vessel_visit_details.start_work,'yyyy-mm-dd') AS start_work,srv_event.quantity,srv_event.quantity_unit
				FROM vsl_vessel_visit_details 
				INNER JOIN vsl_vessels ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey
				INNER JOIN srv_event ON srv_event.applied_to_gkey=vsl_vessel_visit_details.vvd_gkey
				WHERE ib_vyg='$rotation' AND srv_event.event_type_gkey='213'";
				//echo  $eventQuery;
				//return;
				$eventResult=$this->bm->dataSelect($eventQuery);
				$igm_master_id = null;
				$vsl_name="";
				$berth_hr="";
				$quantity="";
				$unit="";
				$berth_date="";
				
			//	if(count($igmMasters)>0 and count($eventResult)>0){
				if(count($eventResult)>0){
					@$igm_master_id = $igmMasters[0]['id'];
					$vsl_name = $eventResult[0]['vessel_name'];
					$berth_date=$eventResult[0]['start_work'];
					$quantity=$eventResult[0]['quantity'];
					$unit=$eventResult[0]['quantity_unit'];
					$berth_hr=$quantity." ".$unit;
				
					// $intsertQuery = "INSERT INTO ctmsmis.hotwork_demand(igm_masters_id,rotation,vessel_name,
					// start_time,start_date,entry_by,entry_at,entry_ip,fire_dpt_aprv_st) 
					// VALUES('$igm_master_id','$rotation','$vsl_name','$berth_hr','$berth_date','$login_id',NOW(),'$ipAddr',1)";
					
					$intsertQuery = "INSERT INTO ctmsmis.hotwork_demand(igm_masters_id,rotation,vessel_name,service_date,start_time,
					start_date,entry_by,entry_at,entry_ip,fire_dpt_aprv_st,fire_dpt_aprv_at,fire_dpt_aprv_by,fire_dpt_aprv_ip) 
					VALUES('$igm_master_id','$rotation','$vsl_name','$service_date','$berth_hr','$berth_date','$login_id',NOW(),
					'$ipAddr',1,NOW(),'$login_id','$ipAddr')";
					//return;
					if($this->bm->dataInsertDb2($intsertQuery)){
						$msg = "<font color='green' size='4'><strong>Rotation: $rotation.  Hot Work Service Successfully Submitted...</strong></font>";
						
					}
					else{
						$msg = "<font color='red' size='4'><strong>Failed! Please Try Again...</strong></font>";

					}
				}
				else{
					$msg = "<font color='red' size='4'><strong>Rotation: $rotation, Hot work Service not recorded yet.</strong></font>";
				}

				$data['title']="Hot Work Service Form";
				$data['msg'] = $msg;
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('fireHotWorkDemandForm',$data);
				$this->load->view('jsAssets');
			}
		}	
    }

	function fireWorkServiceFormExtended()
	{
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Hot Work Service Form";
			$data['msg'] = "";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('fireWorkServiceExtendedForm',$data);
			$this->load->view('jsAssets');
		}	
    }

	function savefireWorkServiceFormExtended()
	{
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$rotation = trim($this->input->post('rotation'));
			$service_date = trim($this->input->post('service_date'));
			$firemanHRS = trim($this->input->post('firemanHRS'));
			$firepumpHRS = trim($this->input->post('firepumpHRS'));
			$fireofficerHRS = trim($this->input->post('fireofficerHRS'));
			$fireEngineHRS = trim($this->input->post('fireEngineHRS'));
			$login_id = $this->session->userdata('login_id');
			$ipAddr = $_SERVER['REMOTE_ADDR'];

			$userChk = "SELECT COUNT(*) as rtnValue FROM base_user where base_user.buser_userid ='$login_id'";			 
			$userChkRtn = $this->bm->dataReturn($userChk);
			
			if($userChkRtn<1)
			{
				$msg = "<font color='red' size='4'><strong>Sorry! User: $login_id does not exist in N4.</strong></font>";									
			}
			else
			{

			$duplicateChkQuery = "SELECT count(*) as rtnValue FROM ctmsmis.hotwork_demand 
								WHERE rotation = '$rotation' AND service_date='$service_date' AND fire_dpt_aprv_st = 1";
			$duplicateChk = $this->bm->dataReturnDb2($duplicateChkQuery);

			if($duplicateChk>0)
			{
				$title = "Hot Work Service Forward List";
		
				$approvedListQuery = "SELECT * FROM ctmsmis.hotwork_demand WHERE fire_dpt_aprv_st = 1 ORDER BY hotwork_demand.id DESC";
				$approvedList = $this->bm->dataSelectDb2($approvedListQuery);

				$data['result'] = $approvedList;
				$data['title'] = $title;
				$data['msg'] = "";

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('hotWorkDemandApproveList',$data);
				$this->load->view('jsAssetsList');
				
			}
			else
			{
				$msg="";

				$igmMasterIdQuery = "SELECT * FROM igm_masters WHERE Import_Rotation_No = '$rotation'";
				$igmMasters = $this->bm->dataSelectDB1($igmMasterIdQuery);

				$eventQuery="SELECT vsl_vessel_visit_details.vvd_gkey,argo_carrier_visit.id as visit_id, vsl_vessels.name AS vessel_name,
				to_char(vsl_vessel_visit_details.start_work,'yyyy-mm-dd') AS start_work
				FROM vsl_vessel_visit_details 
				INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
				INNER JOIN vsl_vessels ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey
				WHERE ib_vyg='$rotation'";
				//echo  $eventQuery;
				//return;
				$eventResult=$this->bm->dataSelect($eventQuery);
				$igm_master_id = null;
				$vsl_name="";
				$vvd_gkey="";
				$visit_id="";
				$berth_hr="";
				$quantity="";
				$unit="";
				$berth_date="";
				
			//	if(count($igmMasters)>0 and count($eventResult)>0){
				if(count($eventResult)>0){
					@$igm_master_id = $igmMasters[0]['id'];
					$vsl_name = $eventResult[0]['VESSEL_NAME'];
					$vvd_gkey = $eventResult[0]['VVD_GKEY'];
					$visit_id = $eventResult[0]['VISIT_ID'];
					$berth_date=$eventResult[0]['START_WORK'];
					//$quantity=$eventResult[0]['quantity'];
					//$unit=$eventResult[0]['quantity_unit'];
					if($firemanHRS>0)
					{
						$berth_hr=$firemanHRS." ".'HOURS';
					}				
					
					$intsertQuery = "INSERT INTO ctmsmis.hotwork_demand(igm_masters_id,rotation,vessel_name,service_date,start_time,
					start_date,entry_by,entry_at,entry_ip,fire_dpt_aprv_st,fire_dpt_aprv_at,fire_dpt_aprv_by,fire_dpt_aprv_ip, firemanHrs, firepumpHrs) 
					VALUES('$igm_master_id','$rotation','$vsl_name','$service_date','$berth_hr','$berth_date','$login_id',NOW(),
					'$ipAddr',1,NOW(),'$login_id','$ipAddr', '$firemanHRS', '$firepumpHRS')";
					//return;
					if($this->bm->dataInsertDB2($intsertQuery)){
						if($firemanHRS>0)
						{
							$strInsrtFireManEvent = "INSERT INTO srv_event(operator_gkey,complex_gkey,facility_gkey,yard_gkey,placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator)
							VALUES('1','1','1','1','$login_id',CURRENT_DATE,'213','VV','$vvd_gkey','$visit_id','$firemanHRS','HOURS',CURRENT_DATE,'$login_id')";
							$strInsrtFireManEventST=$this->bm->dataInsertdb5($strInsrtFireManEvent);
						}
						
						if($firepumpHRS>0)
						{
							$strInsrtFirePumpEvent = "INSERT INTO srv_event(operator_gkey,complex_gkey,facility_gkey,yard_gkey,placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator)
							VALUES('1','1','1','1','$login_id',CURRENT_DATE,'211','VV','$vvd_gkey','$visit_id','$firepumpHRS','HOURS',CURRENT_DATE,'$login_id')";
							$strInsrtFirePumpEventST=$this->bm->dataInsertdb5($strInsrtFirePumpEvent);
						}
						if($fireofficerHRS>0)
						{
							$strInsrtFireOfficerEvent = "INSERT INTO srv_event(operator_gkey,complex_gkey,facility_gkey,yard_gkey,placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator)
							VALUES('1','1','1','1','$login_id',CURRENT_DATE,'223','VV','$vvd_gkey','$visit_id','$fireofficerHRS','HOURS',CURRENT_DATE,'$login_id')";
							$strInsrtFireOfficerEventST=$this->bm->dataInsertdb5($strInsrtFireOfficerEvent);
						}
						if($fireEngineHRS>0)
						{
							$strInsrtFireEngineEvent = "INSERT INTO srv_event(operator_gkey,complex_gkey,facility_gkey,yard_gkey,placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator)
							VALUES('1','1','1','1','$login_id',CURRENT_DATE,'219','VV','$vvd_gkey','$visit_id','$fireEngineHRS','HOURS',CURRENT_DATE,'$login_id')";
							$strInsrtFireEngineEventST=$this->bm->dataInsertdb5($strInsrtFireEngineEvent);
						}
						if($strInsrtFireManEventST>0 || $strInsrtFirePumpEventST>0 || $strInsrtFireOfficerEventST>0 || $strInsrtFireEngineEventST>0)
						{
							$msg = "<font color='green' size='4'><strong>Rotation: $rotation.  Hot Work Service Successfully Submitted.</strong></font>";
						}
						else
						{
							$msg = "<font color='red' size='4'><strong>Rotation: $rotation.  Failed! Please Try Again.</strong></font>";
						}
						
					}
					else{
						$msg = "<font color='red' size='4'><strong>Failed! Please Try Again...</strong></font>";

					}
				}
				else{
					$msg = "<font color='red' size='4'><strong>Rotation: $rotation, Hot work Service not recorded yet.</strong></font>";
				}
			  }
			}
			$data['title']="Hot Work Service Form";
			$data['msg'] = $msg;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('fireWorkServiceExtendedForm',$data);
			$this->load->view('jsAssets');
		}	
    }


	
	
	function vslBeachedForwardForm()
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
			$section = $this->session->userdata('section');
			
			$org_Type_id =$this->session->userdata('org_Type_id');
			if($org_Type_id=='83') //Marine
			{
				$title="Vessel Forwarding (Beaching) by Marine";
			}
			else if($org_Type_id=='81')
			{
				$title="Vessel Forwarding (Beaching) by Harbaour Master";
			}	
			else if($org_Type_id=='82') // Accounts
			{
				if($section=='acc') // Accountant
				{
					$title="Vessel Forwarding (Beaching) by Accountant";		
				}
				if($section=='billop') // Bill Operator
				{
					$title="Vessel Forwarding List";		
				}
			}		
			$data['masterFlag'] = $masterFlag;
			$data['msg'] = "";
			$data['flag'] = 0;
			$data['title'] = $title;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardList_beached',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function vslBeachedForwardList()
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
			
			$data['title']="Vessel Forwarding (Beached) by Marine";
			$data['msg'] = "";
			
			$fromDate = $this->input->post("fromDate");
			$toDate = $this->input->post("toDate");
			
			$departQuery = "";
			$masterFlag = "";
			$this->bm->VesselDataDumpingByDT($fromDate,$toDate);
			return;
			// echo $org_Type_id; return;
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Vessel Forwarding (Beaching) by Marine";				
				
				if($login_id == '23026') // Farzana
				{		
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes
					FROM ctmsmis.vsl_vssel_info
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' AND 
					DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND
				    ctmsmis.vsl_vssel_info.vvd_gkey NOT IN (SELECT ctmsmis.vsl_forward_info.vvd_gkey FROM ctmsmis.vsl_forward_info) 
					ORDER BY  ctmsmis.vsl_vssel_info.atd DESC";
				}
				else if($login_id == '24087') // Fardaus
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes,
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' AND
					 DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' 
					 AND ctmsmis.vsl_forward_info.marine_forward_stat='1' AND ctmsmis.vsl_forward_info.svtmis_forward_stat = '0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else if($login_id == '12369') // Habib
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes,
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' AND
					 DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND 
					 ctmsmis.vsl_forward_info.svtmis_forward_stat = '1' AND ctmsmis.vsl_forward_info.hob_forward_stat = '0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
			}
			else if($org_Type_id=='81') //Master			
			{
				$masterFlag = "master";
				$data['title']="Vessel Forwarding (Beaching) by Harbaour Master";				
				
				// search by forwarding date
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
				DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
				ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
				ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
				ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes,
				ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM ctmsmis.vsl_forward_info
				INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' AND 
				DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND 
				ctmsmis.vsl_forward_info.hob_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_stat='0'
				ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
			}
			else if($org_Type_id=='82') // Accounts
			{
				// if($login_id=='sr_acc') //Sr. Accountant
				if($section=='acc') // Accountant - by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding (Beaching) by Sr. Accountant";
					
					// search by forwarding date
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes,
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' AND 
					DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND
					 ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding (Beaching) by Accountant";
					
					// search by forwarding date
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes,
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' AND 
					DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND 
					ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_stat='0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}					
			}
			// echo $departQuery;return;
			
			$departData = $this->bm->dataSelectDb2($departQuery);
			// var_dump($departData);return;

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
			$this->load->view('vesselForwardList_beached',$data);			
			$this->load->view('jsAssets');
		}
	}
	
	function vslBeachedForwardingPerform()		// N4
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
			$billOp = $this->input->post("billOp");
			
			$section = $this->session->userdata('section');
			//echo $billOp.' __ '.$section;
			//return;
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
				// lot data - start - n4				
				$sql_nextLotSl = "SELECT IFNULL(MAX(lot_sl),0)+1 AS rtnValue
				FROM ctmsmis.vsl_forward_lot_info
				WHERE lot_dt=DATE(NOW())";
								
				$nextLotSl = $this->bm->dataReturnDb2($sql_nextLotSl);				
				$nextLotSl = (strlen($nextLotSl)<2)?("0".$nextLotSl):$nextLotSl;		
				
				$nextLotId = date('d').date('m').substr(date('Y'),2,2).$nextLotSl;				
								
				$totVsl = count($rotationChk);
				
				$ignoredId = array(24087,12369);
				if(!in_array($login_id,$ignoredId))
				{
					$lotInsFlag = 0;
					$insertLotInfo = "INSERT INTO ctmsmis.vsl_forward_lot_info(lot_sl,lot_dt,lot_id,tot_vsl,forward_at,forward_by,vsl_lot_type)
									VALUES('$nextLotSl',CURDATE(),'$nextLotId','$totVsl',NOW(),'$login_id','Marine')";								
					$lotInsFlag = $this->bm->dataInsertDb2($insertLotInfo);
					
					if($lotInsFlag == 0)
					{
						echo "<font color='red'>Lot not created</font>";
						return;
					}
				}
				
				$sql_lotId = "SELECT MAX(id) AS rtnValue FROM ctmsmis.vsl_forward_lot_info";
				$lotId = $this->bm->dataReturnDb2($sql_lotId);
				// echo $lotId;return;
				// lot data - end
				foreach ($rotationChk as $rCheck)
				{	
					$this->bm->VesselDataDumpingByVVDGkey($rCheck);										
					// echo $rCheck;return;
					$chk_str="SELECT COUNT(*) AS rtnValue from ctmsmis.vsl_forward_info WHERE  vvd_gkey='$rCheck'";
					// echo $chk_str;
					$ckh_st = $this->bm->dataReturnDb2($chk_str);
					// echo $ckh_st;return;
					$resInsertst=0;

					$strInsert = "";

					if($ckh_st==0)
					{
						$strInsert = "INSERT INTO ctmsmis.vsl_forward_info(vsl_fwd_lot_info_id,vvd_gkey, marine_forward_stat, marine_forward_by, marine_forward_at,  marine_forward_ip, vsl_category) 
						VALUES('$lotId','$rCheck', '1', '$login_id', NOW(), '$ipAddress','BEACHING')";
						// echo $strInsert;return;
						
					}
					else
					{
						if($login_id == 24087)
						{
							$strInsert = "UPDATE ctmsmis.vsl_forward_info SET svtmis_forward_stat = 1 , svtmis_forward_by = '$login_id' , svtmis_forward_at = NOW() , svtmis_forward_ip = '$ipAddress' WHERE vvd_gkey = '$rCheck'";
							//$resInsertst = $this->bm->dataInsert($strInsert);
						}
						else if($login_id == 12369)
						{
							$strInsert = "UPDATE ctmsmis.vsl_forward_info SET hob_forward_stat = 1 , hob_forward_by = '$login_id' , hob_forward_at = NOW() , hob_forward_ip = '$ipAddress' WHERE vvd_gkey = '$rCheck'";
							//$resInsertst = $this->bm->dataInsert($strInsert);
						}
					}

					$resInsertst = $this->bm->dataInsertDb2($strInsert);
					// echo $strInsert;return;
					$k++;
				}

				if($resInsertst>0)
				{
					//$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					$this->session->set_flashdata("success", "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>$k Vessel forwarded succesfully</font></div>");
				}
				else
				{
					//$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
				}
				
			}
			else if($org_Type_id=='81')		//master
			{
				$masterFlag = "master";
				
				// $fileNo = $this->input->post("fileNo");
				// $filedt = $this->input->post("filedt");
				$filesub = $this->input->post("filesub");
				$noVsl=count($rotationChk);
				// $chk_str="SELECT count(*) AS rtnValue from ctmsmis.vsl_frwrd_letter_info WHERE file_no='$fileNo' ";
				// $chk_st = $this->bm->dataReturn($chk_str);
				// if($chk_st>0)
				// {
					// $data['msg'] = "<font color='red' size=2>You already used this File no. Use new one.</font>";
				// }
				// else
				// {
					
					// file sl and date - start
					$sql_nextFileSl = "SELECT IFNULL(MAX(file_sl),0)+1 AS rtnValue
					FROM ctmsmis.vsl_frwrd_letter_info";
					$nextFileSl = $this->bm->dataReturnDb2($sql_nextFileSl);
					
					$fileNo = "ডিসি/বিএস/সিএস/অংশ-".$nextFileSl;
										
					// $insert_str="INSERT INTO ctmsmis.vsl_frwrd_letter_info(file_dt,file_sub,file_sl,file_no,no_vsl) 
					// VALUES ('$filedt','$filesub','$nextFileSl','$fileNo','$noVsl')";

					$insert_str="INSERT INTO ctmsmis.vsl_frwrd_letter_info(file_dt,file_sub,file_sl,file_no,no_vsl) 
					VALUES (DATE(NOW()),'$filesub','$nextFileSl','$fileNo','$noVsl')";			
					$insert_st = $this->bm->dataInsertDb2($insert_str);
					// file sl and date - end
					
					$k=0;
					$str="SELECT id AS rtnValue
					FROM ctmsmis.vsl_frwrd_letter_info
					WHERE file_no='$fileNo'
					ORDER BY id DESC
					LIMIT 1";
					$letter_no = $this->bm->dataReturnDb2($str);
					
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET master_forward_stat='1', master_forward_by='$login_id', master_forward_at=NOW(),
									master_forward_ip='$ipAddress', vsl_frwd_letter_no='$letter_no' WHERE vvd_gkey='$rCheck'";
						$update_st = $this->bm->dataUpdatedb2($updateSt);
						$k++;
					}


					if($update_st>0)
					{
						//$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
						$this->session->set_flashdata("success", "<div class='alert alert-success'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>$k Vessel forwarded succesfully</font></div>");
					}
					else
					{
						//$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
					}
				// }
			}
			else if($org_Type_id=='82')
			{
				if($section=='acc') //Sr. Accountant 
				{
					$k=0;
					$billOp = $this->input->post("billOp");
					
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),
									sr_acnt_forward_ip='$ipAddress', bill_op_user_id='$billOp'  WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdatedb2($updateSt);
						$k++;
					} 
					$data['msg']="";


					if($update_st>0)
					{
						//$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
						$this->session->set_flashdata("success", "<div class='alert alert-success'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>$k Vessel forwarded succesfully</font></div>");
					}
					else
					{
						//$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
					}
					
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28 
				{
					$k=0;
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
						$updateSt="UPDATE ctmsmis.vsl_forward_info SET billop_bill_stat='1', billop_bill_by='$login_id', billop_bill_at=NOW(),
									billop_bill_ip='$ipAddress' WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdatedb2($updateSt);
						$k++;
					} 
					$data['msg']="";
					
					$data['title']="Vessel Forwarding (Bhatiary) by Accountant";
					
				}				
			}

			redirect('Vessel/forwardedVslHistoryBeached/', 'refresh');
		}
		
	}
	
	function forwardedVslHistoryBeached()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		// $this->session->set_userdata(array('menu' => "VESSEL"));
		// $this->session->set_userdata(array('sub_menu' => "forwardedVslHistoryBeached"));
		//Menu Expanding....
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			$section =$this->session->userdata('section');
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Forwarded By Marine (Beached) - History";
				
				if($login_id == 24087)
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes,
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' AND ctmsmis.vsl_forward_info.marine_forward_stat='1' 
					AND ctmsmis.vsl_forward_info.svtmis_forward_stat='1' AND ctmsmis.vsl_forward_info.svtmis_forward_by='$login_id'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else if($login_id == 12369)
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes,
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' AND ctmsmis.vsl_forward_info.svtmis_forward_stat='1'
					 AND ctmsmis.vsl_forward_info.hob_forward_stat='1' AND ctmsmis.vsl_forward_info.hob_forward_by='$login_id'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				else
				{
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
					DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
					ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
					ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
					ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes,
					ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
					FROM ctmsmis.vsl_forward_info
					INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' AND ctmsmis.vsl_forward_info.marine_forward_stat='1' 
					AND ctmsmis.vsl_forward_info.marine_forward_by='$login_id'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}							
			}
			else if($org_Type_id=='81') //Master			
			{
				$data['title']="Forwarded By Master (Beached) - History";
				
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,
				DATE(ctmsmis.vsl_vssel_info.ata) AS ata, DATE(ctmsmis.vsl_vssel_info.atd) AS atd,
				ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt, 
				ctmsmis.vsl_vssel_info.agent,ctmsmis.vsl_vssel_info.cntry_name,ata,atd, ctmsmis.vsl_vssel_info.ib_vyg, 
				ctmsmis.vsl_vssel_info.vsl_class,ctmsmis.vsl_vssel_info.basic_class,ctmsmis.vsl_vssel_info.notes,
				ctmsmis.vsl_forward_info.marine_forward_at AS forwarded_dt
				FROM ctmsmis.vsl_forward_info
				INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' AND ctmsmis.vsl_forward_info.hob_forward_stat='1' 
				AND ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.master_forward_by='$login_id'
				ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
			}
			
			// echo $departQuery;return;
			$departData = $this->bm->dataSelectDb2($departQuery);

			$data['departData']=$departData;
			// $data['fromDate']=$fromDate;
			// $data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['msg']="";
			$data['flag'] = 1;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');			
			$this->load->view('vesselForwardList_beached_history',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	
	function vesselForwardingbyMasterListBeached()
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
			$section = $this->session->userdata('section');
			
			$data['msg'] = "";
			
			//$fromDate = $this->input->post("fromDate");
			//$toDate = $this->input->post("toDate");
			
			$data['title']="Vessel Forwarding Letter (Beached)";

			$Query = "";

			if($section=='acc')
			{
				$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl,stmt_month,
				(SELECT COUNT(*) FROM ctmsmis.vsl_forward_info WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0') AS pending_no 
				FROM (SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,stmt_month 
				FROM ctmsmis.vsl_frwrd_letter_info INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no = ctmsmis.vsl_frwrd_letter_info.id 
				WHERE ctmsmis.vsl_forward_info.vsl_category='BEACHING') AS tbl ORDER BY letter_no DESC";
			}
			else if($section=='billop')
			{
				$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl,stmt_month,
				(SELECT COUNT(*)  FROM ctmsmis.vsl_forward_info 
				WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no=tbl.letter_no 
				AND ctmsmis.vsl_forward_info.billop_bill_stat='0') AS pending_no
				FROM  ( SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,stmt_month
				FROM ctmsmis.vsl_frwrd_letter_info
				INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
				WHERE  vsl_forward_info.bill_op_user_id='$login_id' AND ctmsmis.vsl_forward_info.vsl_category='BEACHING') AS tbl  ORDER BY letter_no DESC";
					//return;	
			}
			else
			{
				$Query = "SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl,stmt_month 
				FROM ctmsmis.vsl_frwrd_letter_info 
				INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
				WHERE ctmsmis.vsl_forward_info.vsl_category='BEACHING'
				ORDER BY vsl_frwrd_letter_info.id DESC";
			}

			// echo $Query;return;
			$letterList = $this->bm->dataSelectDb2($Query);
			$data['letterList']=$letterList;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;
			$data['section']=$section;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingbyMasterListBeached',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	
	function vesselForwardingLetter_Beached($stmt = null)
	{
		$fileNo = $this->input->post("file_no");
		$filedt = $this->input->post("file_dt");
		$filesub = $this->input->post("file_sub");
		$no_vsl = $this->input->post("no_vsl");
		$letter_id = $this->input->post("letter_id");

		/* $this->load->library('m_pdf');
		$mpdf->use_kwt = true;
		$mpdf->simpleTables = true;	 */

		$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,CONCAT(DATE_FORMAT(ctmsmis.vsl_vssel_info.ata,'%d-%m-%Y'),' ',TIME(ctmsmis.vsl_vssel_info.ata)) as ata ,CONCAT(DATE_FORMAT(ctmsmis.vsl_vssel_info.atd,'%d-%m-%Y'),' ',TIME(ctmsmis.vsl_vssel_info.atd)) as atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
		ctmsmis.vsl_vssel_info.loa_cm,
		ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
		ctmsmis.vsl_vssel_info.agent,
		ctmsmis.vsl_vssel_info.line as name,
		ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg,
		ctmsmis.vsl_vssel_info.ib_vyg, vsl_forward_info.master_forward_by, vsl_forward_info.vvd_gkey,CONCAT(DATE_FORMAT(ctmsmis.vsl_forward_info.marine_forward_at,'%d-%m-%Y'),' ',TIME(ctmsmis.vsl_forward_info.marine_forward_at)) as forwarded_dt
		FROM ctmsmis.vsl_forward_info
        INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
		WHERE ctmsmis.vsl_forward_info.vsl_frwd_letter_no='$letter_id'";
		// echo $departQuery;
		 //return;	 

		$letterQuery = "SELECT DATE_FORMAT(from_date,'%d/%m/%Y') as from_date, DATE_FORMAT(to_date,'%d/%m/%Y') as to_date FROM ctmsmis.vsl_frwrd_letter_info WHERE id = '$letter_id'";
		$letterData = $this->bm->dataSelectDb2($letterQuery);
	 

		$departData = $this->bm->dataSelectDb2($departQuery);

		if($stmt == "stmt")
		{
			$this->data['departData']=$departData;
			$this->data['fileNo']=$fileNo;
			$this->data['filedt']=$filedt;
			$this->data['filesub']=$filesub;
			$this->data['no_vsl']=$no_vsl;
			$this->data['stmt_month']=$stmt_month = $this->input->post("stmt_month");
			// $this->load->view('vesselForwardingStatementForBeached',$data);

			$this->load->library('m_pdf');
			
			//load the pdf_output.php by passing our data and get all data in $html varriable.
			$html=$this->load->view('vesselForwardingStatementForBeached',$this->data, true); 
			//var_dump($html);
			
			$pdfFilePath ="vesselForwardingStatementForBeached-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			//Follwing line is commented to show bangla font in PDF
			//$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
			
			
			// please follow : https://mpdf.github.io/reference/mpdf-functions/addpage.html
			// L - landscape, P - portrait
			$pdf->AddPage(
				'', // orientation L|P
				'', // type = E|O|even|odd|next-odd|next-even
				'', // resetpagenum = 1 - ∞
				'', // pagenumstyle = 1|A|a|I|i
				'', // suppress = on|off|1|0
				40, // margin_left
				2, // margin right
				5, // margin top
				2, // margin bottom
				10, // margin header
				15, // margin footer
				'', // odd-header-name
				'', // even-header-name
				'', // odd-footer-name
				'', // even-footer-name
				0, // odd-header-value
				0, // even-header-value
				0, // odd-footer-value
				0, // even-footer-value
				'', // pageselector ... Select a named CSS @page.
				'Legal-L' //Sheet size...array can be set also, like [210,297]
			); 
			
			// $footer = array (
			// 	'odd' => array (
			// 		'L' => array (
			// 			'content' => '',
			// 			'font-size' => 8,
			// 			'font-style' => 'B',
			// 			'font-family' => 'serif',
			// 			'color'=>'#000000'
			// 		),
			// 		'C' => array (
			// 			'content' => '',
			// 			'font-size' => 8,
			// 			'font-style' => 'B',
			// 			'font-family' => 'serif',
			// 			'color'=>'#000000'
			// 		),
			// 		'R' => array (
			// 			'content' => 'page {PAGENO} of '.$totalPage,
			// 			'font-size' => 8,
			// 			'font-style' => 'B',
			// 			'font-family' => 'serif',
			// 			'color'=>'#000000'
			// 		),
			// 		'line' => 0, // 1= draw footer border, 0 = don't draw footer border
			// 	),
			// 	'even' => array ()
			// );
			// $pdf->setFooter($footer);
			
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		}
		else
		{
			$data['departData']=$departData;
			$data['letterData']=$letterData;
			$data['fileNo']=$fileNo;
			$data['filedt']=$filedt;
			$data['filesub']=$filesub;
			$data['no_vsl']=$no_vsl;
			$this->load->view('vesselForwardingLetterView_Beached',$data);
		}
	}
	
	function vesselForwardingForAcc_Beached()
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
			
			
			$fileNo = $this->input->post("file_no");
			$filedt = $this->input->post("file_dt");
			$filesub = $this->input->post("file_sub");
			$no_vsl = $this->input->post("no_vsl");
			$letter_id = $this->input->post("letter_id");
			
			
			if($org_Type_id=='83') //Marine
			{
				$title="Vessel Forwarding by Marine";
			}
			else if($org_Type_id=='81')
			{
				$title="Vessel Forwarding by Harbaour Master";
			}	
			else if($org_Type_id=='82') // Accounts
			{
				if($section=='acc') // Accountant
				{
					$title="Vessel Forwarding by Accountant";		
				}
				if($section=='billop') // Bill Operator
				{
					$title="Vessel Forwarding List";		
				}
			}		
			
			if($section=='acc') 
			{
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
				ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
				ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				
				ctmsmis.vsl_vssel_info.ib_vyg, vsl_forward_info.master_forward_by, vsl_forward_info.vvd_gkey,
				ctmsmis.vsl_forward_info.master_forward_at AS forwarded_dt,	ctmsmis.vsl_vssel_info.vsl_class
				FROM ctmsmis.vsl_forward_info
				INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0' AND  ctmsmis.vsl_forward_info.vsl_frwd_letter_no='$letter_id'";
			}
			else if($section=='billop') 
			{
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
				ctmsmis.vsl_vssel_info.ves_captain, ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name,
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				ctmsmis.vsl_vssel_info.ib_vyg, 
				CONCAT(DATE_FORMAT(ctmsmis.vsl_forward_info.master_forward_at,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_forward_info.master_forward_at)) AS forwarded_dt,
				ctmsmis.vsl_vssel_info.vsl_class,
				ctmsmis.vsl_vssel_info.off_port_arr AS oa_date_dollar,
				CONCAT(DATE_FORMAT(ctmsmis.vsl_vssel_info.off_port_arr,'%d/%m/%Y'),' ',TIME(ctmsmis.vsl_vssel_info.off_port_arr)) AS oa_date,ctmsmis.vsl_forward_info.vsl_category
				FROM ctmsmis.vsl_forward_info
                INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND  ctmsmis.vsl_forward_info.vsl_frwd_letter_no='$letter_id'
				AND bill_op_user_id='$login_id' AND ctmsmis.vsl_forward_info.billop_bill_stat=0";
			}	
			//return;
			$departData = $this->bm->dataSelectDb2($departQuery);
					
			$data['departData']=$departData;
			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);

			$data['billOpListRslt']=$billOpListRslt;
			$data['filedt']=$filedt;
			$data['fileNo']=$fileNo;
			$data['filesub']=$filesub;
			$data['letter_id']=$letter_id;
			$data['title']=$title;
			$data['section']=$section;
			$data['flag'] = 1;
			$data['msg'] = "";
			$data['login_id']=$login_id;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingForAccBill_Beached',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function vesselForwardingForAccBillAction_beached()
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
			$section = $this->session->userdata('section');
			$filedt = $this->session->userdata('filedt');
			$fileNo = $this->session->userdata('fileNo');
			$filesub = $this->session->userdata('filesub');
			$msg="";
			$departQuery="";
			if($section=='acc')
			{
				if(isset($_POST['idchk']))
				{
					$rotationChk = $_POST['idchk'];
				}
				$k=0;
				
				$letter_id = $this->input->post("letter_id");
				$billOp = $this->input->post("billOp");
				foreach ($rotationChk as $rCheck)
				{
					$this->bm->VesselDataDumpingByVVDGkey($rCheck);	

					  $updateSt="UPDATE ctmsmis.vsl_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),
								sr_acnt_forward_ip='$ipAddress', bill_op_user_id='$billOp'  WHERE vvd_gkey='$rCheck'";
								
					$update_st = $this->bm->dataUpdatedb2($updateSt);
					$k++;
				} 
				
				//return;
				$msg="";
				if($update_st>0)
				{
					$msg = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
				}
				else
				{
					$msg = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
				}
				
				$title="Vessel Forwarding by Accountant";	
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
				ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
				ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				
				ctmsmis.vsl_vssel_info.ib_vyg, vsl_forward_info.master_forward_by, vsl_forward_info.vvd_gkey,
				ctmsmis.vsl_forward_info.master_forward_at AS forwarded_dt,ctmsmis.vsl_vssel_info.vsl_class
				FROM ctmsmis.vsl_forward_info
				INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
				WHERE ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0' AND ctmsmis.vsl_forward_info.vsl_frwd_letter_no='$letter_id'";
				//return;									
			}
			else if($section=='billop')
			{	
				
			}
			$departData = $this->bm->dataSelectDb2($departQuery);
			$data['departData']=$departData;
			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);

			$data['billOpListRslt']=$billOpListRslt;
			$data['filedt']=$filedt;
			$data['fileNo']=$fileNo;
			$data['filesub']=$filesub;
			$data['letter_id']=$letter_id;
			$data['title']=$title;
			//$data['toDate']=$toDate;
			$data['section']=$section;
			$data['flag'] = 1;
			$data['msg'] = $msg;
			$data['login_id']=$login_id;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			//if($this->input->post('fwBtn'))
			$this->load->view('vesselForwardingForAccBill_Beached',$data);
			//else if($this->input->post('fwBtnOuter'))
			//	$this->load->view('vesselForwardOuterAnchorage',$data);
			$this->load->view('jsAssets');
		}			
	}
	
	// Cancelation Vesssel Forward List -- start
	
	function vslCancelationForwardForm()
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
			$section = $this->session->userdata('section');
			
			$org_Type_id =$this->session->userdata('org_Type_id');
			if($org_Type_id=='83') //Marine
			{
				$title="Vessel Forwarding (Cancellation) by Marine";
			}
			else if($org_Type_id=='81')
			{
				$title="Vessel Forwarding (Cancellation) by Harbaour Master";
			}	
			else if($org_Type_id=='82') // Accounts
			{
				if($section=='acc') // Accountant
				{
					$title="Vessel Forwarding (Cancellation) by Accountant";		
				}
				if($section=='billop') // Bill Operator
				{
					$title="Vessel Forwarding List";		
				}
			}		
			$data['masterFlag'] = $masterFlag;
			$data['msg'] = "";
			$data['flag'] = 0;
			$data['title'] = $title;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardList_cancelation',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function vslCancelationForwardList()
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
			
			$data['title']="Vessel Forwarding (Cancellation) by Marine";
			$data['msg'] = "";
			
			$fromDate = $this->input->post("fromDate");
			$toDate = $this->input->post("toDate");
			
			$departQuery = "";
			$masterFlag = "";
			$this->bm->VesselDataDumpingByDT($fromDate,$toDate);
			// echo $org_Type_id; return;
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Vessel Forwarding (Cancellation) by Marine";				
				
				if($login_id == '23026') // Farzana
				{		
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
					ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
					ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					'VESSEL CANCELATION' AS vsl_class
					FROM ctmsmis.vsl_vssel_info
					WHERE ctmsmis.vsl_vssel_info.event_type_gkey = '6092173' AND
					DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND 
					ctmsmis.vsl_vssel_info.vvd_gkey NOT IN (SELECT ctmsmis.vsl_cancelation_forward_info.vvd_gkey FROM ctmsmis.vsl_cancelation_forward_info) 
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				
			}
			else if($org_Type_id=='81') //Master			
			{
				$masterFlag = "master";
				$data['title']="Vessel Forwarding (Cancellation) by Harbaour Master";				
				
				// search by forwarding date
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
				ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
				ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				'VESSEL CANCELATION' AS vsl_class,
				CONCAT(DATE_FORMAT(ctmsmis.vsl_cancelation_forward_info.marine_forward_at,'%d-%m-%Y'),' ',TIME(ctmsmis.vsl_cancelation_forward_info.marine_forward_at)) AS forwarded_dt
				FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
				WHERE ctmsmis.vsl_vssel_info.event_type_gkey = '6092173' AND
				 DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND
				ctmsmis.vsl_cancelation_forward_info.marine_forward_stat = 1 AND
				ctmsmis.vsl_cancelation_forward_info.master_forward_stat = 0
				ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
			}
			else if($org_Type_id=='82') // Accounts
			{
				// if($login_id=='sr_acc') //Sr. Accountant
				if($section=='acc') // Accountant - by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding (Cancellation) by Sr. Accountant";
					
					// search by forwarding date
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
					ctmsmis.vsl_vssel_info.ves_captain,
					ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
					ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					ctmsmis.vsl_vssel_info.vsl_class,
					CONCAT(DATE_FORMAT(ctmsmis.vsl_forward_info.marine_forward_at,'%d-%m-%Y'),' ',TIME(ctmsmis.vsl_forward_info.marine_forward_at)) as forwarded_dt
					FROM ctmsmis.vsl_vssel_info
				    INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' 
					AND DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND 
					ctmsmis.vsl_forward_info.master_forward_stat='1' AND ctmsmis.vsl_forward_info.sr_acnt_forward_stat='0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28
				{
					$data['title']="Vessel Forwarding (Cancellation) by Accountant";
					
					// search by forwarding date
					$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
					ctmsmis.vsl_vssel_info.ves_captain,
					ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
					ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					ctmsmis.vsl_vssel_info.vsl_class,
					CONCAT(DATE_FORMAT(ctmsmis.vsl_forward_info.marine_forward_at,'%d-%m-%Y'),' ',TIME(ctmsmis.vsl_forward_info.marine_forward_at)) as forwarded_dt
					FROM ctmsmis.vsl_vssel_info
				    INNER JOIN ctmsmis.vsl_forward_info ON ctmsmis.vsl_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
					WHERE ctmsmis.vsl_vssel_info.notes LIKE '%DEMOLITION%' AND 
					DATE(ctmsmis.vsl_vssel_info.atd) BETWEEN '$fromDate' AND '$toDate' AND 
					ctmsmis.vsl_forward_info.sr_acnt_forward_stat='1' AND ctmsmis.vsl_forward_info.billop_bill_stat='0'
					ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
				}					
			}
			// echo $departQuery;return;
			
			$departData = $this->bm->dataSelectDb2($departQuery);
			// var_dump($departData);return;

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
			$this->load->view('vesselForwardList_cancelation',$data);			
			$this->load->view('jsAssets');
		}
	}
	
	function vslCancelationForwardingPerform()		// N4
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
			$billOp = $this->input->post("billOp");
			
			$section = $this->session->userdata('section');
			//echo $billOp.' __ '.$section;
			//return;
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
				// lot data - start - n4				
				$sql_nextLotSl = "SELECT IFNULL(MAX(lot_sl),0)+1 AS rtnValue
				FROM ctmsmis.vsl_forward_lot_info
				WHERE lot_dt=DATE(NOW())";
								
				$nextLotSl = $this->bm->dataReturnDb2($sql_nextLotSl);				
				$nextLotSl = (strlen($nextLotSl)<2)?("0".$nextLotSl):$nextLotSl;		
				
				$nextLotId = date('d').date('m').substr(date('Y'),2,2).$nextLotSl;				
								
				$totVsl = count($rotationChk);
				
				$lotInsFlag = 0;
				$insertLotInfo = "INSERT INTO ctmsmis.vsl_forward_lot_info(lot_sl,lot_dt,lot_id,tot_vsl,forward_at,forward_by,vsl_lot_type) VALUES('$nextLotSl',CURDATE(),'$nextLotId','$totVsl',NOW(),'$login_id','Marine')";

				$lotInsFlag = $this->bm->dataInsertDb2($insertLotInfo);
				
				if($lotInsFlag == 0)
				{
					echo "<font color='red'>Lot not created</font>";
					return;
				}
				
				$sql_lotId = "SELECT MAX(id) AS rtnValue FROM ctmsmis.vsl_forward_lot_info";
				$lotId = $this->bm->dataReturnDb2($sql_lotId);
				// echo $lotId;return;
				// lot data - end
				foreach ($rotationChk as $rCheck)
				{	
					$this->bm->VesselDataDumpingByVVDGkey($rCheck);										
					// echo $rCheck;return;
					$chk_str="SELECT COUNT(*) AS rtnValue from ctmsmis.vsl_cancelation_forward_info WHERE  vvd_gkey='$rCheck'";
					// echo $chk_str;
					$ckh_st = $this->bm->dataReturnDb2($chk_str);
					// echo $ckh_st;return;
					$resInsertst=0;

					$strInsert = "";

					if($ckh_st==0)
					{
						$strInsert = "INSERT INTO ctmsmis.vsl_cancelation_forward_info(vsl_fwd_lot_info_id,vvd_gkey, marine_forward_stat, marine_forward_by, marine_forward_at,  marine_forward_ip, vsl_category) 
						VALUES('$lotId','$rCheck', '1', '$login_id', NOW(), '$ipAddress','VESSEL CANCELATION')";
						// echo $strInsert;return;
						
					}

					$resInsertst = $this->bm->dataInsertDb2($strInsert);
					// echo $strInsert;return;
					$k++;
				}

				if($resInsertst>0)
				{
					//$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
					$this->session->set_flashdata("success", "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>$k Vessel forwarded succesfully</font></div>");
				}
				else
				{
					//$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
				}
				
			}
			else if($org_Type_id=='81')		//master
			{
				$masterFlag = "master";
				
				// $fileNo = $this->input->post("fileNo");
				// $filedt = $this->input->post("filedt");
				$filesub = $this->input->post("filesub");
				$noVsl=count($rotationChk);
				// $chk_str="SELECT count(*) AS rtnValue from ctmsmis.vsl_frwrd_letter_info WHERE file_no='$fileNo' ";
				// $chk_st = $this->bm->dataReturn($chk_str);
				// if($chk_st>0)
				// {
					// $data['msg'] = "<font color='red' size=2>You already used this File no. Use new one.</font>";
				// }
				// else
				// {
					
					// file sl and date - start
					$sql_nextFileSl = "SELECT IFNULL(MAX(file_sl),0)+1 AS rtnValue
					FROM ctmsmis.vsl_frwrd_letter_info";
					$nextFileSl = $this->bm->dataReturnDb2($sql_nextFileSl);
					
					$fileNo = "ডিসি/বিএস/সিএস/অংশ-".$nextFileSl;
										
					// $insert_str="INSERT INTO ctmsmis.vsl_frwrd_letter_info(file_dt,file_sub,file_sl,file_no,no_vsl) 
					// VALUES ('$filedt','$filesub','$nextFileSl','$fileNo','$noVsl')";

					$insert_str="INSERT INTO ctmsmis.vsl_frwrd_letter_info(file_dt,file_sub,file_sl,file_no,no_vsl) 
					VALUES (DATE(NOW()),'$filesub','$nextFileSl','$fileNo','$noVsl')";			
					$insert_st = $this->bm->dataInsertDb2($insert_str);
					// file sl and date - end
					
					$k=0;
					$str="SELECT id AS rtnValue
					FROM ctmsmis.vsl_frwrd_letter_info
					WHERE file_no='$fileNo'
					ORDER BY id DESC
					LIMIT 1";
					$letter_no = $this->bm->dataReturnDb2($str);
					
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
						$updateSt="UPDATE ctmsmis.vsl_cancelation_forward_info SET master_forward_stat='1', master_forward_by='$login_id', master_forward_at=NOW(),
									master_forward_ip='$ipAddress', vsl_frwd_letter_no='$letter_no' WHERE vvd_gkey='$rCheck'";
						$update_st = $this->bm->dataUpdatedb2($updateSt);
						$k++;
					}


					if($update_st>0)
					{
						//$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
						$this->session->set_flashdata("success", "<div class='alert alert-success'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>$k Vessel forwarded succesfully</font></div>");
					}
					else
					{
						//$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
					}
				// }
			}
			else if($org_Type_id=='82')
			{
				if($section=='acc') //Sr. Accountant 
				{
					$k=0;
					$billOp = $this->input->post("billOp");
					
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
						$updateSt="UPDATE ctmsmis.vsl_cancelation_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),
									sr_acnt_forward_ip='$ipAddress', bill_op_user_id='$billOp'  WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdatedb2($updateSt);
						$k++;
					} 
					$data['msg']="";


					if($update_st>0)
					{
						//$data['msg'] = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
						$this->session->set_flashdata("success", "<div class='alert alert-success'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>$k Vessel forwarded succesfully</font></div>");
					}
					else
					{
						//$data['msg'] = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'>Sorry! not forwarderd. Please try again later.</font></div>");
					}
					
				}
				// else if ($login_id=='acc') //Accountant
				else if ($section=='billop') //bill operator	// by Intakhab - 2022-08-28 
				{
					$k=0;
					foreach ($rotationChk as $rCheck)
					{
						$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
						$updateSt="UPDATE ctmsmis.vsl_cancelation_forward_info SET billop_bill_stat='1', billop_bill_by='$login_id', billop_bill_at=NOW(),
									billop_bill_ip='$ipAddress' WHERE vvd_gkey='$rCheck'";
									
						$update_st = $this->bm->dataUpdatedb2($updateSt);
						$k++;
					} 
					$data['msg']="";
					
					$data['title']="Vessel Forwarding (Bhatiary) by Accountant";
					
				}				
			}

			redirect('Vessel/forwardedVslHistoryCancelation/', 'refresh');
		}
		
	}
	
	function forwardedVslHistoryCancelation()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		//Menu Expanding....
		// $this->session->set_userdata(array('menu' => "VESSEL"));
		// $this->session->set_userdata(array('sub_menu' => "forwardedVslHistoryBeached"));
		//Menu Expanding....
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			$section =$this->session->userdata('section');
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			if($org_Type_id=='83') //Marine
			{
				$data['title']="Forwarded By Master (Cancelation) - History";

				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
				ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
				ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				'VESSEL CANCELATION' AS vsl_class,
				CONCAT(DATE_FORMAT(ctmsmis.vsl_cancelation_forward_info.marine_forward_at,'%d-%m-%Y'),' ',TIME(ctmsmis.vsl_cancelation_forward_info.marine_forward_at)) AS forward_at
				FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
				WHERE ctmsmis.vsl_vssel_info.event_type_gkey = '163' AND ctmsmis.vsl_cancelation_forward_info.marine_forward_stat = 1
				ORDER BY ctmsmis.vsl_vssel_info.atd DESC";							
			}
			else if($org_Type_id=='81') //Master			
			{
				$data['title']="Forwarded By Master (Cancellation) - History";
				
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
				ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
				ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
				CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
				CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
				'VESSEL CANCELATION' AS vsl_class,
				 CONCAT(DATE_FORMAT(ctmsmis.vsl_cancelation_forward_info.master_forward_at,'%d-%m-%Y'),' ',TIME(ctmsmis.vsl_cancelation_forward_info.master_forward_at)) AS forward_at
				 FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
				WHERE ctmsmis.vsl_vssel_info.event_type_gkey = '163' AND ctmsmis.vsl_cancelation_forward_info.master_forward_stat = 1
				ORDER BY ctmsmis.vsl_vssel_info.atd DESC";
			}
			
			// echo $departQuery;return;
			$departData = $this->bm->dataSelectDb2($departQuery);

			$data['departData']=$departData;
			// $data['fromDate']=$fromDate;
			// $data['toDate']=$toDate;
			$data['login_id']=$login_id;
			$data['msg']="";
			$data['flag'] = 1;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');			
			$this->load->view('vesselForwardList_cancelation_history',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	
	function vesselForwardingbyMasterListCancelation()
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
			$section = $this->session->userdata('section');
			
			$data['msg'] = "";
			
	
			
			$data['title']="Vessel Forwarding Letter (Cancellation)";

			$Query = "";

			if($section=='acc')
			{
				// $Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl, 
				// (SELECT COUNT(*) FROM ctmsmis.vsl_cancelation_forward_info
				//  WHERE ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no=tbl.letter_no AND 
				//  ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_stat='0') AS pending_no
				//   FROM (
				//   SELECT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl FROM
				//   ctmsmis.vsl_frwrd_letter_info INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no = ctmsmis.vsl_frwrd_letter_info.id 
				//   WHERE ctmsmis.vsl_cancelation_forward_info.vsl_category='VESSEL CANCELATION') AS tbl ORDER BY letter_no DESC";

				$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl,stmt_month,(SELECT COUNT(*) FROM ctmsmis.vsl_cancelation_forward_info WHERE ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no=tbl.letter_no AND ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_stat='0') AS pending_no 
				FROM (SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,stmt_month
				FROM ctmsmis.vsl_frwrd_letter_info 
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no = ctmsmis.vsl_frwrd_letter_info.id 
				WHERE ctmsmis.vsl_cancelation_forward_info.vsl_category='VESSEL CANCELATION') AS tbl ORDER BY letter_no DESC";
			}
			else if($section=='billop')
			{
				
				// $Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl,pending_no
				// FROM  ( SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,ib_vyg,
				// (SELECT COUNT(*) AS cnt FROM ctmsmis.mis_vsl_billing_detail_test
				//  WHERE rotation=ctmsmis.vsl_vssel_info.ib_vyg) AS bill_done,
				// (no_vsl - (SELECT COUNT(*) AS cnt FROM ctmsmis.mis_vsl_billing_detail_test 
				// WHERE rotation=ctmsmis.vsl_vssel_info.ib_vyg AND bill_type = '110')) AS pending_no
				// FROM ctmsmis.vsl_frwrd_letter_info
				// INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
				// INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_cancelation_forward_info.vvd_gkey
				// WHERE  vsl_cancelation_forward_info.bill_op_user_id='$login_id' AND ctmsmis.vsl_cancelation_forward_info.vsl_category='VESSEL CANCELATION') AS tbl
				// ORDER BY letter_no DESC";


				$Query = "SELECT letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl,stmt_month,pending_no
				FROM  ( SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,stmt_month,(SELECT COUNT(*) AS cnt FROM ctmsmis.mis_vsl_billing_detail_test WHERE rotation=sparcsn4.vsl_vessel_visit_details.ib_vyg) AS bill_done,
				(no_vsl - (SELECT COUNT(*) AS cnt FROM ctmsmis.mis_vsl_billing_detail_test WHERE rotation=sparcsn4.vsl_vessel_visit_details.ib_vyg AND bill_type = '110')) AS pending_no
				FROM ctmsmis.vsl_frwrd_letter_info
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=ctmsmis.vsl_cancelation_forward_info.vvd_gkey
				WHERE  vsl_cancelation_forward_info.bill_op_user_id='$login_id' AND ctmsmis.vsl_cancelation_forward_info.vsl_category='VESSEL CANCELATION') AS tbl
				ORDER BY letter_no DESC";

				//return;	
			}
			else
			{
				// $Query = "SELECT vsl_frwrd_letter_info.id AS letter_no,
				//  CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl 
				// FROM ctmsmis.vsl_frwrd_letter_info 
				// INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
				// WHERE ctmsmis.vsl_cancelation_forward_info.vsl_category='VESSEL CANCELATION'
				// ORDER BY vsl_frwrd_letter_info.id DESC";

				$Query = "SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, CONCAT(DATE_FORMAT(file_dt,'%d/%m/%Y')) AS file_dt,file_sub,file_no,no_vsl,stmt_month
				FROM ctmsmis.vsl_frwrd_letter_info 
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
				WHERE ctmsmis.vsl_cancelation_forward_info.vsl_category='VESSEL CANCELATION'
				ORDER BY vsl_frwrd_letter_info.id DESC";
			}


			// echo $Query;return;
			$letterList = $this->bm->dataSelectDb2($Query);
			$data['letterList']=$letterList;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;
			$data['section']=$section;


			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingbyMasterListCancelation',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	


	










	function vesselForwardingLetter_Cancelation($stmt = null)
	{
		$fileNo = $this->input->post("file_no");
		$filedt = $this->input->post("file_dt");
		$filesub = $this->input->post("file_sub");
		$no_vsl = $this->input->post("no_vsl");
		$letter_id = $this->input->post("letter_id");
		/* $this->load->library('m_pdf');
		$mpdf->use_kwt = true;
		$mpdf->simpleTables = true;	 */
		$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
		DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
		ctmsmis.vsl_vssel_info.loa_cm,
		ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
		ctmsmis.vsl_vssel_info.agent,
		ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg, 				
		CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
		CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
		ctmsmis.vsl_cancelation_forward_info.master_forward_at AS forwarded_dt, 'VESSEL CANCELATION' AS vsl_class 
		FROM ctmsmis.vsl_vssel_info
		INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
		WHERE ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no ='$letter_id'";
		// echo $departQuery;
		// return;	 
		
		$departData = $this->bm->dataSelectDb2($departQuery);

		if($stmt == "stmt")
		{
			$this->data['departData']=$departData;
			$this->data['fileNo']=$fileNo;
			$this->data['filedt']=$filedt;
			$this->data['filesub']=$filesub;
			$this->data['no_vsl']=$no_vsl;
			// $this->load->view('vesselForwardingStatementForBeached',$data);

			$this->load->library('m_pdf');
			
			//load the pdf_output.php by passing our data and get all data in $html varriable.
			$html=$this->load->view('vesselForwardingStatementForCancelation',$this->data, true); 
			//var_dump($html);
			
			$pdfFilePath ="vesselForwardingStatementForCancelation-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			//Follwing line is commented to show bangla font in PDF
			//$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
			
			
			// please follow : https://mpdf.github.io/reference/mpdf-functions/addpage.html
			// L - landscape, P - portrait
			$pdf->AddPage(
				'', // orientation L|P
				'', // type = E|O|even|odd|next-odd|next-even
				'', // resetpagenum = 1 - ∞
				'', // pagenumstyle = 1|A|a|I|i
				'', // suppress = on|off|1|0
				2, // margin_left
				2, // margin right
				5, // margin top
				2, // margin bottom
				5, // margin header
				5, // margin footer
				'', // odd-header-name
				'', // even-header-name
				'', // odd-footer-name
				'', // even-footer-name
				0, // odd-header-value
				0, // even-header-value
				0, // odd-footer-value
				0, // even-footer-value
				'', // pageselector ... Select a named CSS @page.
				'Legal-L' //Sheet size...array can be set also, like [210,297]
			); 
			
			$footer = array (
				'odd' => array (
					'L' => array (
						'content' => '',
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'C' => array (
						'content' => '',
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'R' => array (
						'content' => 'page {PAGENO} of '.$totalPage,
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'serif',
						'color'=>'#000000'
					),
					'line' => 0, // 1= draw footer border, 0 = don't draw footer border
				),
				'even' => array ()
			);
			$pdf->setFooter($footer);
			
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		}
		else
		{
			$data['departData']=$departData;
			$data['fileNo']=$fileNo;
			$data['filedt']=$filedt;
			$data['filesub']=$filesub;
			$data['no_vsl']=$no_vsl;
			$this->load->view('vesselForwardingLetterView_Cancelation',$data);
		}
	}
	
	function searchCancellationVslFromLetter()
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
			$org_Type_id = $this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');
						
			$searchRotation = $this->input->post('searchRotation');
			
			if($section=='billop')
			{
				// $sql_vslFromLetter = "SELECT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,
				// (SELECT COUNT(*) FROM ctmsmis.vsl_cancelation_forward_info WHERE ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no=letter_no AND ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_stat='0') AS pending_no
				// FROM sparcsn4.argo_carrier_visit
				// INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				// INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
				// INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				// INNER JOIN sparcsn4.vsl_vessel_classes ON sparcsn4.vsl_vessel_classes.gkey=sparcsn4.vsl_vessels.vesclass_gkey
				// INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
				// INNER JOIN sparcsn4.ref_country ON sparcsn4.ref_country.cntry_code=sparcsn4.vsl_vessels.country_code
				// INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
				// INNER JOIN ctmsmis.vsl_frwrd_letter_info ON ctmsmis.vsl_frwrd_letter_info.id = ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no
				// WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg='$searchRotation' AND vsl_cancelation_forward_info.bill_op_user_id='$login_id'";

				$sql_vslFromLetter = "SELECT DISTINCT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,ib_vyg,
				(SELECT COUNT(*) AS cnt FROM ctmsmis.mis_vsl_billing_detail_test WHERE rotation=ctmsmis.vsl_vssel_info.ib_vyg) AS bill_done,
				(no_vsl - (SELECT COUNT(*) AS cnt FROM 
				ctmsmis.mis_vsl_billing_detail_test 
				WHERE rotation=ctmsmis.vsl_vssel_info.ib_vyg AND bill_type = '110')) AS pending_no
				FROM ctmsmis.vsl_frwrd_letter_info
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no=ctmsmis.vsl_frwrd_letter_info.id
				INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_cancelation_forward_info.vvd_gkey
				WHERE  vsl_cancelation_forward_info.bill_op_user_id='$login_id' AND 
				ctmsmis.vsl_cancelation_forward_info.vsl_category='VESSEL CANCELATION' 
				AND ctmsmis.vsl_vssel_info.ib_vyg = '$searchRotation'";
			}
			else
			{
				$sql_vslFromLetter = "SELECT vsl_frwrd_letter_info.id AS letter_no, file_dt,file_sub,file_no,no_vsl,
				(SELECT COUNT(*) FROM ctmsmis.vsl_cancelation_forward_info 
				WHERE ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no=letter_no AND
				 ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_stat='0') AS pending_no
				FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vvd_gkey= ctmsmis.vsl_vssel_info.vvd_gkey
				INNER JOIN ctmsmis.vsl_frwrd_letter_info ON ctmsmis.vsl_frwrd_letter_info.id = ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no
				WHERE  ctmsmis.vsl_vssel_info.ib_vyg='$searchRotation'";
			}
			
			// echo $sql_vslFromLetter;return;
			$letterList = $this->bm->dataSelectDb2($sql_vslFromLetter);
			
			$msg = "";
			$title="";
			if($section=='acc')
			{
				$title="Vessel Forwarding by Accountant";
			}	
			 else if($section=='billop')
			{
				$title="Vessel Forwarding ";
			}
			if(count($letterList) == 0)
				$msg = "<font color='red'>No data found...</font>";

			$data['letterList']=$letterList;
			$data['title']=$title;
			$data['msg']=$msg;
			$data['action']=$action;
			$data['login_id']=$login_id;
			$data['flag'] = 1;
			$data['org_Type_id']=$org_Type_id;
			$data['section']=$section;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingbyMasterListCancelation',$data);			
			$this->load->view('jsAssetsList');
		}
	}
	
	
	function vesselForwardingForAcc_Cancelation()
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
			
			
			$fileNo = $this->input->post("file_no");
			$filedt = $this->input->post("file_dt");
			$filesub = $this->input->post("file_sub");
			$no_vsl = $this->input->post("no_vsl");
			$letter_id = $this->input->post("letter_id");
			
			
			if($org_Type_id=='83') //Marine
			{
				$title="Vessel Forwarding by Marine (Cancellation)";
			}
			else if($org_Type_id=='81')
			{
				$title="Vessel Forwarding by Harbaour Master (Cancellation)";
			}	
			else if($org_Type_id=='82') // Accounts
			{
				if($section=='acc') // Accountant
				{
					$title="Vessel Forwarding by Accountant (Cancellation)";		
				}
				if($section=='billop') // Bill Operator
				{
					$title="Vessel Forwarding List (Cancellation)";		
				}
			}		
			
			if($section=='acc') 
			{
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
				ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
				ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg,
				CONCAT(DATE_FORMAT(ctmsmis.vsl_cancelation_forward_info.master_forward_at,'%d-%m-%Y'),' ',TIME(ctmsmis.vsl_cancelation_forward_info.master_forward_at)) AS forwarded_dt, 
				'VESSEL CANCELATION' AS vsl_class,
				ctmsmis.vsl_vssel_info.off_port_arr AS oa_date,
				ctmsmis.vsl_vssel_info.off_port_arr AS oa_date_dollar
				FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
				WHERE ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_stat='0' AND  ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no='$letter_id'";
			}
			else if($section=='billop') 
			{
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
				ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
				ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg,
				CONCAT(DATE_FORMAT(ctmsmis.vsl_cancelation_forward_info.master_forward_at,'%d-%m-%Y'),' ',TIME(ctmsmis.vsl_cancelation_forward_info.master_forward_at)) AS forwarded_dt, 
				'VESSEL CANCELATION' AS vsl_class,
				ctmsmis.vsl_vssel_info.off_port_arr AS oa_date,
				DATE(ctmsmis.vsl_vssel_info.off_port_arr) AS oa_date_dollar
				FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
				WHERE ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_stat='1' AND  ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no='$letter_id'
				AND bill_op_user_id='$login_id'";
			}	
			// echo $departQuery;
			//return;
			$departData = $this->bm->dataSelectDb2($departQuery);
					
			$data['departData']=$departData;
			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);

			$data['billOpListRslt']=$billOpListRslt;
			$data['filedt']=$filedt;
			$data['fileNo']=$fileNo;
			$data['filesub']=$filesub;
			$data['letter_id']=$letter_id;
			$data['title']=$title;
			$data['section']=$section;
			$data['flag'] = 1;
			$data['msg'] = "";
			$data['login_id']=$login_id;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingForAccBill_Cancelation',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function vesselForwardingForAccBillAction_Cancelation()
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
			$section = $this->session->userdata('section');
			$filedt = $this->session->userdata('filedt');
			$fileNo = $this->session->userdata('fileNo');
			$filesub = $this->session->userdata('filesub');
			$msg="";
			if($section=='acc')
			{
				if(isset($_POST['idchk']))
				{
					$rotationChk = $_POST['idchk'];
				}
				$k=0;
				
				$letter_id = $this->input->post("letter_id");
				$billOp = $this->input->post("billOp");
				foreach ($rotationChk as $rCheck)
				{
					$this->bm->VesselDataDumpingByVVDGkey($rCheck);	
					$updateSt="UPDATE ctmsmis.vsl_cancelation_forward_info SET sr_acnt_forward_stat='1', sr_acnt_forward_by='$login_id', sr_acnt_forward_at=NOW(),sr_acnt_forward_ip='$ipAddress', bill_op_user_id='$billOp'  WHERE vvd_gkey='$rCheck'";
					$update_st = $this->bm->dataUpdatedb2($updateSt);
					$k++;
				} 
				
				//return;
				$msg="";
				if($update_st>0)
				{
					$msg = "<font color='blue' size=2>$k Vessel forwarded succesfully</font>";
				}
				else
				{
					$msg = "<font color='red' size=2>Sorry! not forwarderd. Please try again later.</font>";
				}
				
				$title="Vessel Forwarding by Accountant";	
				$departQuery = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
				DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,ctmsmis.vsl_vssel_info.ves_captain,
				ctmsmis.vsl_vssel_info.loa_cm,
				ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,
				ctmsmis.vsl_vssel_info.agent,
				ctmsmis.vsl_vssel_info.cntry_name,ctmsmis.vsl_vssel_info.ib_vyg,
				ctmsmis.vsl_cancelation_forward_info.master_forward_at AS forwarded_dt, 'VESSEL CANCELATION' AS vsl_class
				FROM ctmsmis.vsl_vssel_info
				INNER JOIN ctmsmis.vsl_cancelation_forward_info ON ctmsmis.vsl_cancelation_forward_info.vvd_gkey=ctmsmis.vsl_vssel_info.vvd_gkey
				WHERE ctmsmis.vsl_cancelation_forward_info.sr_acnt_forward_stat='0' AND  ctmsmis.vsl_cancelation_forward_info.vsl_frwd_letter_no='$letter_id'";
				//return;									
			}
			else if($section=='billop')
			{	
				
			}
			$departData = $this->bm->dataSelectDb2($departQuery);
			$data['departData']=$departData;
			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpListRslt = $this->bm->dataSelectDB1($billOpListStr);

			$data['billOpListRslt']=$billOpListRslt;
			$data['filedt']=$filedt;
			$data['fileNo']=$fileNo;
			$data['filesub']=$filesub;
			$data['letter_id']=$letter_id;
			$data['title']=$title;
			$data['section']=$section;
			$data['flag'] = 1;
			$data['msg'] = $msg;
			$data['login_id']=$login_id;
			$data['org_Type_id']=$org_Type_id;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('vesselForwardingForAccBill_Cancelation',$data);
			$this->load->view('jsAssets');
		}			
	}

	function holdShiftingContainerForm()
	{
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			$data['title']="Import Hold Shifting Container Form";
			$data['msg'] = "";
			
			$query = "SELECT import_container_hold_shifting.id AS hold_id, import_container_hold_shifting.rotation, import_container_hold_shifting.vvd_gkey, unit_no, unit_gkey, mlo_code, freight_kind, cont_size,
					cont_height, remarks,category, unit_iso, entry_time, ctmsmis.qgc_container_handling.berth_forwared_st
					FROM ctmsmis.import_container_hold_shifting
					LEFT JOIN ctmsmis.qgc_container_handling ON ctmsmis.qgc_container_handling.vvd_gkey=ctmsmis.import_container_hold_shifting.vvd_gkey
					ORDER BY import_container_hold_shifting.id DESC";
			
			$list = $this->bm->dataSelectDb2($query);
			$data['list']=$list;
			$data['editFlag']=0;

			
			$this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('holdShiftingContainerForm',$data);
			$this->load->view('jsAssetsList');
		
		}
	}

	function holdShiftingContainer()
	{
		$LoginStat = $this->session->userdata('LoginStat');		
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$data['title']="Import Hold Shifting Container Form";
			$rotation = $this->input->post('rotation');
			$contNo = trim(strtoupper($this->input->post('contNo')));
			//$move_num = $this->input->post('move_num');
			$move_num = 1;;
			$remarks = $this->input->post('remarks');

			$data['msg'] = "";
			$chkStr = "SELECT count(*) as rtnValue FROM  ctmsmis.import_container_hold_shifting
				WHERE ctmsmis.import_container_hold_shifting.rotation='$rotation' AND  ctmsmis.import_container_hold_shifting.unit_no='$contNo'";
			$chkSt = $this->bm->dataReturnDb2($chkStr);
			$chkStrForward = "SELECT COUNT(*) AS rtnValue FROM 
					ctmsmis.import_container_hold_shifting
					INNER JOIN ctmsmis.qgc_container_handling ON ctmsmis.qgc_container_handling.vvd_gkey=ctmsmis.import_container_hold_shifting.vvd_gkey
					WHERE import_container_hold_shifting.rotation='$rotation' AND qgc_container_handling.berth_forwared_st=1";
			$chkForwardSt = $this->bm->dataReturnDb2($chkStrForward);
			if($chkSt>0)
			{
				$data['msg'] = "<font color=red><b> Container : ".$contNo."  handle information for this rotation : ".$rotation." already stored. please, check again.</b></font>";

			}
			else if($chkForwardSt>0)
			{
				$data['msg'] = "<font color=red><b> Rotation : ".$rotation." already forwarded to CPA billing section. please, check again. </b></font>";

			}
			else
			{
				
				$moveDtls = "SELECT  inv_unit.id AS contNo, ref_bizunit_scoped.id AS mlocode, inv_unit.gkey AS unit_gkey, inv_unit.category,
				ref_equip_type.iso_group, vsl_vessel_visit_details.vvd_gkey, inv_unit.freight_kind,
				SUBSTR(ref_equip_type.nominal_length,-2) AS siz, SUBSTR(ref_equip_type.nominal_height,-2) AS height
				FROM inv_unit
				INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				INNER JOIN argo_carrier_visit ON  inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
				INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
				INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
				INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
				INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
				WHERE vsl_vessel_visit_details.ib_vyg='$rotation' AND  inv_unit.id='$contNo'";
				$resMoveDtls = $this->bm->dataSelect($moveDtls);
				$vessel_name = "";
				$jetty = "";
				$insertSt=0;
				
				if($this->input->post('save')=='SAVE')
				{
					$igmSt = $this->input->post('igmSt');
					//return;
					if($igmSt=="Yes")
					{
						for($j=0;$j<count($resMoveDtls);$j++)
						{
							$contNo= $resMoveDtls[$j]['CONTNO'];
							$mlocode= $resMoveDtls[$j]['MLOCODE'];
							$unit_gkey= $resMoveDtls[$j]['UNIT_GKEY'];
							$category= $resMoveDtls[$j]['CATEGORY'];
							$iso_group= $resMoveDtls[$j]['ISO_GROUP'];
							$vvd_gkey= $resMoveDtls[$j]['VVD_GKEY'];
							$freight_kind= $resMoveDtls[$j]['FREIGHT_KIND'];
							$cont_size= $resMoveDtls[$j]['SIZ'];
							$cont_height= $resMoveDtls[$j]['HEIGHT'];
							
							$insertQuery="INSERT INTO ctmsmis.import_container_hold_shifting (rotation, vvd_gkey, unit_no, unit_gkey, mlo_code, freight_kind, cont_size, cont_height, remarks,category, unit_iso, move_num, entry_time, entry_by, ip_addr, igm_select_st	)
							values('$rotation', '$vvd_gkey', '$contNo', '$unit_gkey', '$mlocode', '$freight_kind', '$cont_size', '$cont_height', '$remarks', '$category', '$iso_group', '$move_num', NOW(), '$login_id','$ipaddr', '1')";
							$insertSt=$this->bm->dataInsertDb2($insertQuery);	
						}
					}
					else
					{
						$mlocode = strtoupper($this->input->post('cont_mlo'));
						$cont_iso = trim(strtoupper($this->input->post('cont_iso')));
						$freight_kind = $this->input->post('freight_kind');
						
						$moveDtls_vvdgkey = "SELECT vsl_vessel_visit_details.vvd_gkey as rtnValue FROM vsl_vessel_visit_details WHERE vsl_vessel_visit_details.ib_vyg='$rotation'";
						$vvd_gkey = $this->bm->dataReturn($moveDtls_vvdgkey);	

						
						
						$isoDtls_str = "SELECT iso_group,SUBSTR(ref_equip_type.nominal_length,-2) AS siz,
						SUBSTR(ref_equip_type.nominal_height,-2) AS height FROM ref_equip_type WHERE id='$cont_iso'"; 
						
						$resDtls_str = $this->bm->dataSelect($isoDtls_str);
						for($j=0;$j<count($resDtls_str);$j++)
						{
							//$mlocode= $resMoveDtls[$j]['mlocode'];
							$iso_group= $resDtls_str[$j]['ISO_GROUP'];
							$cont_size= $resDtls_str[$j]['SIZ'];
							$cont_height= $resDtls_str[$j]['HEIGHT'];

						
							$insertQuery="INSERT INTO ctmsmis.import_container_hold_shifting (rotation, vvd_gkey, unit_no, unit_gkey, mlo_code, freight_kind, cont_size, cont_height, remarks,category, unit_iso, move_num, entry_time, entry_by, ip_addr, igm_select_st	)
										values('$rotation', '$vvd_gkey', '$contNo', '', '$mlocode', '$freight_kind', '$cont_size', '$cont_height', '$remarks', '', '$iso_group', '$move_num', NOW(), '$login_id','$ipaddr', '0')";
							$insertSt=$this->bm->dataInsertDb2($insertQuery);	

						}
					}
					
					
					if($insertSt>0)
					{
						$data['msg'] = "<font color=green><b>Information successfully stored.</b></font>";
					}
					else
					{
						$data['msg'] = "<font color=red><b>Information not updated. Please, provide correct information.</b></font>";
					}
				}
				else
				{
					$updateSt="";
					$hold_id = $this->input->post('hold_id');
					$igmSt = $this->input->post('igmSt');
					//return;
					if($igmSt=="Yes")
					{
						for($j=0;$j<count($resMoveDtls);$j++)
						{
							
							$contNo= $resMoveDtls[$j]['CONTNO'];
							$mlocode= $resMoveDtls[$j]['MLOCODE'];
							$unit_gkey= $resMoveDtls[$j]['UNIT_GKEY'];
							$category= $resMoveDtls[$j]['CATEGORY'];
							$iso_group= $resMoveDtls[$j]['ISO_GROUP'];
							$vvd_gkey= $resMoveDtls[$j]['VVD_GKEY'];
							$freight_kind= $resMoveDtls[$j]['FREIGHT_KIND'];
							$cont_size= $resMoveDtls[$j]['SIZ'];
							$cont_height= $resMoveDtls[$j]['HEIGHT'];
							
							$updateStr="UPDATE ctmsmis.import_container_hold_shifting SET rotation='$rotation', vvd_gkey='$vvd_gkey', unit_no='$contNo', 
							unit_gkey='$unit_gkey', mlo_code='$mlocode', freight_kind='$freight_kind', cont_size='$cont_size', 
							cont_height='$cont_height', remarks='$remarks',category='$category', unit_iso='$iso_group', move_num='$move_num',
							update_time=NOW(), update_by='$login_id',  ip_addr='$ipaddr', igm_select_st='1' WHERE id='$hold_id'";
							
							$updateSt=$this->bm->dataUpdatedb2($updateStr);
						}
					}
					else
					{
						$mlocode = strtoupper($this->input->post('cont_mlo'));
						$cont_iso = trim(strtoupper($this->input->post('cont_iso')));
						$freight_kind = $this->input->post('freight_kind');
						
						$moveDtls_vvdgkey = "SELECT vsl_vessel_visit_details.vvd_gkey as rtnValue FROM vsl_vessel_visit_details WHERE vsl_vessel_visit_details.ib_vyg='$rotation'";
						$vvd_gkey = $this->bm->dataReturn($moveDtls_vvdgkey);	
						
						$isoDtls_str = "SELECT iso_group,SUBSTR(ref_equip_type.nominal_length,-2) AS siz,
						SUBSTR(ref_equip_type.nominal_height,-2) AS height FROM ref_equip_type WHERE id='$cont_iso'"; 
						
						$resDtls_str = $this->bm->dataSelect($isoDtls_str);
						for($j=0;$j<count($resDtls_str);$j++)
						{
							//$mlocode= $resMoveDtls[$j]['mlocode'];
							$iso_group= $resDtls_str[$j]['ISO_GROUP'];
							$cont_size= $resDtls_str[$j]['SIZ'];
							$cont_height= $resDtls_str[$j]['HEIGHT'];

							$updateStr="UPDATE ctmsmis.import_container_hold_shifting SET rotation='$rotation', vvd_gkey='$vvd_gkey', unit_no='$contNo', 
							 mlo_code='$mlocode', freight_kind='$freight_kind', cont_size='$cont_size', 
							cont_height='$cont_height', remarks='$remarks', unit_iso='$iso_group',
							update_time=NOW(), update_by='$login_id',  ip_addr='$ipaddr', igm_select_st='0' WHERE id='$hold_id'";
							
							$updateSt=$this->bm->dataUpdatedb2($updateStr);

						}
						
					}						

					if($updateSt>0)
					{
						$data['msg'] = "<font color=green><b>Information successfully updated.</b></font>";
					}
					else
					{
						$data['msg'] = "<font color=red><b>Information not updated.</b></font>";
					}					
				}
			}
			$data['title']="Import Hold Shifting Container Form";	
			$data['editFlag']=0;
			
			$query = "SELECT import_container_hold_shifting.id AS hold_id, import_container_hold_shifting.rotation, import_container_hold_shifting.vvd_gkey, unit_no, unit_gkey, mlo_code, freight_kind, cont_size,
					cont_height, remarks,category, unit_iso, entry_time, ctmsmis.qgc_container_handling.berth_forwared_st
					FROM ctmsmis.import_container_hold_shifting
					LEFT JOIN ctmsmis.qgc_container_handling ON ctmsmis.qgc_container_handling.vvd_gkey=ctmsmis.import_container_hold_shifting.vvd_gkey
					ORDER BY import_container_hold_shifting.id DESC";
			
			$list = $this->bm->dataSelectDb2($query);
			$data['list']=$list;

			$this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('holdShiftingContainerForm',$data);
			$this->load->view('jsAssetsList');
		
		}
	}

	function holdShiftingContainerEdit()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id=$this->session->userdata('org_Type_id');
		$login_id =$this->session->userdata('login_id');
		//$this->session->set_userdata(array('menu' => "shoreCrane"));
		//$this->session->set_userdata(array('sub_menu' => "shoreCraneDemandForm"));
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$hold_id=$this->input->post('hold_id');
						
			$selectStr= "SELECT import_container_hold_shifting.id AS hold_id, import_container_hold_shifting.rotation, import_container_hold_shifting.vvd_gkey, unit_no, unit_gkey, mlo_code, freight_kind, cont_size,
				cont_height, remarks,category, unit_iso, mlo_code, entry_time, ctmsmis.qgc_container_handling.berth_forwared_st, igm_select_st
				FROM ctmsmis.import_container_hold_shifting
				LEFT JOIN ctmsmis.qgc_container_handling ON ctmsmis.qgc_container_handling.vvd_gkey=ctmsmis.import_container_hold_shifting.vvd_gkey
				WHERE ctmsmis.import_container_hold_shifting.id='$hold_id'";
			$holdRslt= $this->bm->dataSelectDb2($selectStr);
			for($t=0; $t<count($holdRslt); $t++)
			{
				$hold_id=$holdRslt[$t]['hold_id'];
				$rotation=$holdRslt[$t]['rotation'];
				$remarks=$holdRslt[$t]['remarks'];
				$unit_no=$holdRslt[$t]['unit_no'];
				$unit_iso=$holdRslt[$t]['unit_iso'];
				$mlo_code=$holdRslt[$t]['mlo_code'];
				$igm_select_st=$holdRslt[$t]['igm_select_st'];
			}	

			$data['hold_id']=$hold_id;
			$data['rotation']=$rotation;
			$data['remarks']=$remarks;
			$data['unit_no']=$unit_no;
			$data['unit_iso']=$unit_iso;
			$data['mlo_code']=$mlo_code;
			$data['igm_select_st']=$igm_select_st;

			$data['editFlag']=1;
			$data['msg']="";


			$query = "SELECT import_container_hold_shifting.id AS hold_id, import_container_hold_shifting.rotation, import_container_hold_shifting.vvd_gkey, unit_no, unit_gkey, mlo_code, freight_kind, cont_size,
					cont_height, remarks,category, unit_iso, entry_time, ctmsmis.qgc_container_handling.berth_forwared_st
					FROM ctmsmis.import_container_hold_shifting
					LEFT JOIN ctmsmis.qgc_container_handling ON ctmsmis.qgc_container_handling.vvd_gkey=ctmsmis.import_container_hold_shifting.vvd_gkey
					ORDER BY import_container_hold_shifting.id DESC";
			
			$list = $this->bm->dataSelectDb2($query);
			$data['list']=$list;
			$data['title']="Import Hold Shifting Container Form";
						
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('holdShiftingContainerForm',$data);
			$this->load->view('jsAssetsList');
		}
	}

		
	function holdShiftingContainerDelete()
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		//$this->session->set_userdata(array('menu' => "shoreCrane"));
		//$this->session->set_userdata(array('sub_menu' => "shoreCraneDemandList"));
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$hold_id=$this->input->post('hold_id');
			$msg = "";

			$strDelete="DELETE FROM ctmsmis.import_container_hold_shifting WHERE id='$hold_id'";
			$deleteSt = $this->bm->dataDeleteDb2($strDelete);				
			if($deleteSt)
			{
				$msg="<font color='blue'><b> Deleted Successfully!</b></font>";
			}
			else
			{
				$msg="<font color='red'><b>Sorry! Can not delete.</b></font>";
			}

			$data['title']="Import Hold Shifting Container Form";
			$data['msg'] = $msg;
			$data['editFlag']=0;
			
			$query = "SELECT import_container_hold_shifting.id AS hold_id, import_container_hold_shifting.rotation, import_container_hold_shifting.vvd_gkey, unit_no, unit_gkey, mlo_code, freight_kind, cont_size,
				cont_height, remarks,category, unit_iso, entry_time, ctmsmis.qgc_container_handling.berth_forwared_st
				FROM ctmsmis.import_container_hold_shifting
				LEFT JOIN ctmsmis.qgc_container_handling ON ctmsmis.qgc_container_handling.vvd_gkey=ctmsmis.import_container_hold_shifting.vvd_gkey
				ORDER BY import_container_hold_shifting.id DESC";
			
			$list = $this->bm->dataSelectDb2($query);
			$data['list']=$list;

			$this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('holdShiftingContainerForm',$data);
			$this->load->view('jsAssetsList');
			
	
		}
	}
	function qgcContForwardNewForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$org_id = $this->session->userdata('org_id');
		$section = $this->session->userdata('section');
		
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{			
			if($org_Type_id=='30') // Berth operator
			{
				$sql_qgcFwdList = "SELECT id,rotation,entry_by AS forward_by,entry_at AS forward_at,traffic_forward_st,vvd_gkey,
				traffic_forward_by,trffic_forward_at, berth_forwared_st, billingSection_forwrd_st, shippingSection_forwrd_st, billing_statement_generate_st
				FROM ctmsmis.qgc_container_handling ORDER BY qgc_container_handling.id DESC";
			}
			else
			{
				if($section=='1')  //Shipping  Section 
				{
					$sql_qgcFwdList = "SELECT id,rotation,entry_by AS forward_by,entry_at AS forward_at,traffic_forward_st,vvd_gkey,
					traffic_forward_by,trffic_forward_at, berth_forwared_st, billingSection_forwrd_st, shippingSection_forwrd_st,billing_statement_generate_st
					FROM ctmsmis.qgc_container_handling WHERE qgc_container_handling.berth_forwared_st='1' ORDER BY qgc_container_handling.id DESC";
				}
				else if($section=='19') // Billing Section
				{
					$sql_qgcFwdList = "SELECT id,rotation,entry_by AS forward_by,entry_at AS forward_at,traffic_forward_st,vvd_gkey,
					traffic_forward_by,trffic_forward_at, berth_forwared_st, billingSection_forwrd_st, shippingSection_forwrd_st,billing_statement_generate_st
					FROM ctmsmis.qgc_container_handling WHERE qgc_container_handling.berth_forwared_st='1' ORDER BY qgc_container_handling.id DESC";
				}
			}
			$rslt_qgcFwdList = $this->bm->dataSelectDb2($sql_qgcFwdList);			
						
			$data['msg']="";
			$data['rslt_qgcFwdList']=$rslt_qgcFwdList;	
			
			$data['title']="QGC CONTAINER FORWARD FORM";								
			$data['org_Type_id']= $org_Type_id;								
			$data['org_id']=$org_id;								
			$data['section']=$section;								

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('qgcContForwardNewForm',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function qgcContForwardNew()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$org_id = $this->session->userdata('org_id');
		$section = $this->session->userdata('section');
		//$login_id = $this->session->userdata('login_id');
	
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			$rotation = $this->input->post('impRot');
			$ddl_imp_rot_no = $this->input->post('impRot');
			//$btnStatus = $this->input->post('btnStatus');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$login_id = $this->session->userdata('login_id');
			$user_Name = $this->session->userdata('User_Name');
			
			$data['rotation'] = $rotation;
			$data['ddl_imp_rot_no'] = $ddl_imp_rot_no;
			$data['login_id']=$login_id;
			$data['countBillRow'] = 0;
			
			$this->load->model('ci_auth', 'bm', TRUE);
			$getVoyNo = $this->bm->myExportImExSummeryView($ddl_imp_rot_no);
			$data['voysNo']=$getVoyNo;
			//if($btnStatus == "show")
			if($this->input->post('submit') == "show") 	
			{
				//$this->load->view('cssAssets');
				//$this->load->view('qgcContForwardView',$data);
				//$this->load->view('myclosebar');
				//$this->load->view('jsAssets');
				$this->data['voysNo']=$getVoyNo;
				$this->data['ddl_imp_rot_no']=$ddl_imp_rot_no;

				$this->load->view('qgcContForwardNewView',$data); 
			   /* $this->load->library('m_pdf');
				$html=$this->load->view('qgcContForwardNewView',$data, true); 
				$pdfFilePath ="qgcContForwardView-".time()."-download.pdf";
				$pdf = $this->m_pdf->load();
				$pdf->useSubstitutions = true; 
				$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
				$stylesheet = file_get_contents('assets/stylesheets/test.css');
				$pdf->AddPage('L', // L - landscape, P - portrait
							 '', '', '', '',
							 5, // margin_left
							 5, // margin right
							 10, // margin top
							 10, // margin bottom
							 10, // margin header
							 10); // margin footer
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
				$pdf->Output($pdfFilePath, "I");   */
			/* 
					$this->load->library('m_pdf');
					$html=$this->load->view('qgcContForwardNewView',$this->data, true); 						 
					$pdfFilePath ="qgcContForwardView-".time()."-download.pdf";
					$pdf = $this->m_pdf->load();					
					$pdf->allow_charset_conversion = true;
					$pdf->charset_in = 'iso-8859-4';
					$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
					$mpdf->shrink_tables_to_fit = 1;					
					//$pdf->SetWatermarkText('CPA CTMS');
					$pdf->showWatermarkText = true;
					$pdf->WriteHTML($stylesheet,1);
					$pdf->WriteHTML($html,2);							 
					$pdf->Output($pdfFilePath, "I"); // For Showing Pdf	 */
					
					
			}
			//else if($btnStatus == "confirm")
			else if($this->input->post('submit') == "confirm")
			{
				$sql_chkExist = "SELECT COUNT(*) AS rtnValue
							FROM ctmsmis.qgc_container_handling
							WHERE ctmsmis.qgc_container_handling.rotation='$rotation'";
				$chkExist = $this->bm->dataReturnDb2($sql_chkExist);

				if($chkExist == 0)
				{					

					// Berth Operator authentication check---------------- 
					
					$berthop_Str = "SELECT NVL(flex_string02, flex_string03) AS rtnValue FROM vsl_vessel_visit_details WHERE ib_vyg='$rotation'";
					$berthopName = $this->bm->dataReturn($berthop_Str);	
					if(is_null($berthopName))
					{
						$msg = "<font color='red'>Berth operator name not found in N4 for this <b> $rotation.</b> Please contact with operation.</font>";
					}
					else if($user_Name!=$berthopName)
					{
						$msg = "<font color='red'>Rotation : <b> $rotation </b> handled by other berth operator: <b>$berthopName.</b> Please try with your correct rotation</font>";
					}					
					else if($user_Name==$berthopName)						
					{
						$sql_vvdGkey = "SELECT vvd_gkey
						FROM vsl_vessel_visit_details
						WHERE vsl_vessel_visit_details.ib_vyg='$rotation'";
						$rslt_vvdGkey = $this->bm->dataSelect($sql_vvdGkey);
						
						$vvdGkey = "";
						for($i=0;$i<count($rslt_vvdGkey);$i++)
						{
							$vvdGkey = $rslt_vvdGkey[$i]['vvd_gkey'];
						}
						
						$sql_insertQGCContInfo = "INSERT INTO ctmsmis.qgc_container_handling(rotation,vvd_gkey,entry_by,entry_at,entry_ip,
												berth_forwared_st, berth_forwared_by, berth_forwared_at) 
												VALUES('$rotation','$vvdGkey','$login_id',NOW(),'$ipaddr', '1', '$login_id', NOW())";
						$rslt_insertQGCContInfo = $this->bm->dataInsertDb2($sql_insertQGCContInfo);
						
						$msg = "";
						if($rslt_insertQGCContInfo == 1)
						{
							$msg = "<font color='green'><b>$rotation</b> Forwarded Succesfully</font>";
						}
						else
						{
							$msg = "<font color='red'>Failed to forward</font>";
						}
					}
				}
				else
				{
					$msg = "<font color='red'><b>$rotation</b> was forwarded previously</font>";
				}
				
				if($org_Type_id=='30') // Berth operator
					{
						$sql_qgcFwdList = "SELECT id,rotation,entry_by AS forward_by,entry_at AS forward_at,traffic_forward_st,vvd_gkey,
						traffic_forward_by,trffic_forward_at, berth_forwared_st, billingSection_forwrd_st, shippingSection_forwrd_st,billing_statement_generate_st
						FROM ctmsmis.qgc_container_handling WHERE qgc_container_handling.berth_forwared_st='1' ORDER BY qgc_container_handling.id DESC";
					}
				else
					{
						if($section=='1')  // Shipping Section 
						{
							$sql_qgcFwdList = "SELECT id,rotation,entry_by AS forward_by,entry_at AS forward_at,traffic_forward_st,vvd_gkey,
							traffic_forward_by,trffic_forward_at, berth_forwared_st, billingSection_forwrd_st, shippingSection_forwrd_st,billing_statement_generate_st
							FROM ctmsmis.qgc_container_handling WHERE qgc_container_handling.berth_forwared_st='1' ORDER BY qgc_container_handling.id DESC";
						}
						else if($section=='19') //Billing  Section
						{
							$sql_qgcFwdList = "SELECT id,rotation,entry_by AS forward_by,entry_at AS forward_at,traffic_forward_st,vvd_gkey,
							traffic_forward_by,trffic_forward_at, berth_forwared_st, billingSection_forwrd_st, shippingSection_forwrd_st,billing_statement_generate_st
							FROM ctmsmis.qgc_container_handling WHERE qgc_container_handling.berth_forwared_st='1' ORDER BY qgc_container_handling.id DESC";
						}
					}		

				$rslt_qgcFwdList = $this->bm->dataSelectDb2($sql_qgcFwdList);
				$data['rslt_qgcFwdList']=$rslt_qgcFwdList;
				
				$data['msg']=$msg;
				$data['title']="QGC CONTAINER FORWARD FORM";								
				$data['org_Type_id']=$org_Type_id;								
				$data['org_id']=$org_id;	
				$data['section']=$section;	
				
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('qgcContForwardNewForm',$data);
				$this->load->view('jsAssetsList');
			}
		}
	}

	

	function stateOfContainerHandledByQGCreport()
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
	    $org_Type_id=$this->session->userdata('org_Type_id');
		$section = $this->session->userdata('section');
		//RETURN;
		
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{		
			if($this->input->post('rotation'))
			{
				$rotation = $this->input->post('rotation');
			}
			else
			{				
				/* $rot_year=$this->uri->segment(3);
				$rot_no=$this->uri->segment(4);
				$rotation=$rot_year.'/'.$rot_no; */
				$vvd=$this->uri->segment(3);
				$str="SELECT vsl_vessel_visit_details.ib_vyg as rtnValue FROM vsl_vessel_visit_details 
				WHERE vsl_vessel_visit_details.vvd_gkey='$vvd'";
				 $rotation  = $this->bm->dataReturn($str);
			}	
			
			$data['msg']="";
			if($this->input->post('vvd_gkey') )
			{
				$vvd_gkey = $this->input->post('vvd_gkey');
				if($this->input->post('submit1') == "generate")
				{
					$updateStr="UPDATE ctmsmis.qgc_container_handling SET billing_statement_generate_st=1, billing_statement_generate_at=NOW() WHERE  ctmsmis.qgc_container_handling.vvd_gkey='$vvd_gkey'";
					$updatest = $this->bm->dataUpdatedb2($updateStr);
				}
				else if($this->input->post('submit1') == "forward")
				{
					if($section=='19') // for billing section forwarding
					{
						$updateStr="UPDATE ctmsmis.qgc_container_handling SET billingSection_forwrd_st='1', billingSection_forwrd_by='$login_id', 
						billingSection_forwrd_at=NOW() WHERE  ctmsmis.qgc_container_handling.vvd_gkey='$vvd_gkey'";	
					}
					else if($section=='1') // for shipping section forwarding
					{
						$updateStr="UPDATE ctmsmis.qgc_container_handling SET shippingSection_forwrd_st='1', shippingSection_forwrd_by='$login_id', 
						shippingSection_forwrd_at=NOW() WHERE  ctmsmis.qgc_container_handling.vvd_gkey='$vvd_gkey'";	
					}					
					$updatest = $this->bm->dataUpdatedb2($updateStr);
					$data['msg']="<font color='green'>$rotation Forwarded Succesfully</font>";
				}
				$data['vvd_gkey']=$vvd_gkey;		
			}	
					
			$data['rotation']=$rotation;	
			//echo $this->input->post('submit1');
			if($this->input->post('submit1') == "forward" || $this->input->post('submit1') == "generate")
			{
				if($org_Type_id=='30') // Berth operator
				{
					$sql_qgcFwdList = "SELECT id,rotation,entry_by AS forward_by,entry_at AS forward_at,traffic_forward_st,vvd_gkey,
					traffic_forward_by,trffic_forward_at, berth_forwared_st, billingSection_forwrd_st, shippingSection_forwrd_st, billing_statement_generate_st
					FROM ctmsmis.qgc_container_handling ORDER BY qgc_container_handling.id DESC";
				}
				else
				{
					if($section=='1')  //Shipping  Section 
					{
						$sql_qgcFwdList = "SELECT id,rotation,entry_by AS forward_by,entry_at AS forward_at,traffic_forward_st,vvd_gkey,
						traffic_forward_by,trffic_forward_at, berth_forwared_st, billingSection_forwrd_st, shippingSection_forwrd_st,billing_statement_generate_st
						FROM ctmsmis.qgc_container_handling WHERE qgc_container_handling.berth_forwared_st='1' ORDER BY qgc_container_handling.id DESC";
					}
					else if($section=='19') //Billing Section
					{
						$sql_qgcFwdList = "SELECT id,rotation,entry_by AS forward_by,entry_at AS forward_at,traffic_forward_st,vvd_gkey,
						traffic_forward_by,trffic_forward_at, berth_forwared_st, billingSection_forwrd_st, shippingSection_forwrd_st,billing_statement_generate_st
						FROM ctmsmis.qgc_container_handling WHERE qgc_container_handling.berth_forwared_st='1' ORDER BY qgc_container_handling.id DESC";
					}
				}
				

				$rslt_qgcFwdList = $this->bm->dataSelectDb2($sql_qgcFwdList);
				$data['rslt_qgcFwdList']=$rslt_qgcFwdList;
				
				$data['title']="QGC CONTAINER FORWARD FORM";								
				$data['org_Type_id']=$org_Type_id;	
				//$data['org_id']=$org_id;	
			 	$data['section']=$section;								
							
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('qgcContForwardNewForm',$data);
				$this->load->view('jsAssetsList');
			}
			else
			{
				$this->load->view('stateOfContainerHandledReport',$data);	
			}			
		}
	}
	
	function updateHotWorkDemand(){
		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$rotation = $this->input->post('rotation');
			//echo $rotation;
			//return;
			$hotWorkDetailsQu="select firemanHRS,firepumpHRS,fireofficerHRS,fireEngineHRS,rotation,service_date from  ctmsmis.hotwork_demand where rotation='$rotation'";
			$hotWorkDetailsRes = $this->bm->dataSelectDb2($hotWorkDetailsQu);
			$rotation="";
			$service_date="";
			$firemanHRS="";
			$firepumpHRS="";
			$fireofficerHRS="";
			$fireEngineHRS="";
			for($i=0;$i<count($hotWorkDetailsRes);$i++){
				 $rotation=$hotWorkDetailsRes[$i]['rotation'];
				 $service_date=$hotWorkDetailsRes[$i]['service_date'];
				 $firemanHRS=$hotWorkDetailsRes[$i]['firemanHRS'];
				 $firepumpHRS=$hotWorkDetailsRes[$i]['firepumpHRS'];
				 $fireofficerHRS=$hotWorkDetailsRes[$i]['fireofficerHRS'];
				 $fireEngineHRS=$hotWorkDetailsRes[$i]['fireEngineHRS'];
			}
			//return;
		
			//echo "<pre>";
			//print_r($hotWorkDetailsRes);
			//echo "</pre>";
			//return;
			
			$frmType = "edit";
			//$data['msg']=$msg;
			$data['frmType']=$frmType;
			$data['rotation']=$rotation;
			$data['service_date']=$service_date;
			$data['firemanHRS']=$firemanHRS;
			$data['firepumpHRS']=$firepumpHRS;
			$data['fireofficerHRS']=$fireofficerHRS;
			$data['fireEngineHRS']=$fireEngineHRS;
			
			
			$data['title']="Hot Work Edit Form";
			$data['hotWorkDetailsRes']=$hotWorkDetailsRes;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('hotWorkUpdateDemandForm',$data);
			$this->load->view('jsAssets');

		
		}
		
	}
	
	function updateHotWorkDemandExtended()
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
			$ipAddr = $_SERVER['REMOTE_ADDR'];
			$rotation = $this->input->post('rotation');
			$service_date = $this->input->post('service_date');
	        $firemanHRS = $this->input->post('firemanHRS');
			$firepumpHRS = $this->input->post('firepumpHRS');
			
			$fireofficerHRS = $this->input->post('fireofficerHRS');
			$fireEngineHRS=$this->input->post('fireEngineHRS');
			$org_Type_id =$this->session->userdata('org_Type_id');
		
		
			
			
		    $eventQuery="SELECT vsl_vessel_visit_details.vvd_gkey,argo_carrier_visit.id as visit_id
			FROM vsl_vessel_visit_details 
			INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
			WHERE ib_vyg='$rotation' fetch first 1 rows only";
		
	
			//$strRes = $this->bm->dataSelect($vvdQu);
			$eventResult=$this->bm->dataSelect($eventQuery);
			
			$vvd_gkey="";
			
			if(count($eventResult)>0){
					//$vvdgkey=$strRes[0]['VVD_GKEY'];
					$vvd_gkey = $eventResult[0]['VVD_GKEY'];
					$visit_id = $eventResult[0]['VISIT_ID'];
					
					$updateQuery ="UPDATE ctmsmis.hotwork_demand SET service_date='$service_date',firemanHRS=$firemanHRS,
					firepumpHRS=$firepumpHRS,fireofficerHRS=$fireofficerHRS,
					fireEngineHRS=$fireEngineHRS,update_ip='$ipAddr',update_by='$login_id',update_at=NOW() WHERE rotation='$rotation'";
					
					  $updateRes = $this->bm->dataUpdatedb2($updateQuery);
			        //$updateRes=true;
			
					if($updateRes)
					{
						$userId = 'user:'.$login_id;
						if($firemanHRS>0)
						{
							/*$strInsrtFireManEvent = "INSERT INTO srv_event(gkey,operator_gkey,complex_gkey,facility_gkey,yard_gkey,
							placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator) 
							VALUES('$maxGkey','31350','36015','46377','47093','$userId',CURRENT_DATE,'6029207','VV','$vvd_gkey','$visit_id','$firemanHRS','HOURS',CURRENT_DATE,'$login_id')";
							$strInsrtFireManEventST=$this->bm->dataInsertdb5($strInsrtFireManEvent);*/
						    $rowQu1="select count(*) as rtnValue from srv_event WHERE event_type_gkey=6029207 AND applied_to_gkey='$vvd_gkey'";
							$rowReturn1=$this->bm->dataReturn($rowQu1);
						   
							
							if($rowReturn1>0)
							{
								 $strUpdateFireManEvent="UPDATE srv_event
								SET quantity='$firemanHRS'
								WHERE event_type_gkey=6029207 AND applied_to_gkey='$vvd_gkey'";
								//$strUpdateFireManEventST=$this->bm->dataUpdatedb5($strUpdateFireManEvent);
								//echo "<br>";
								 
						
							}
							else
							{
								$maxGkey;
								$maxGkeyQuery = "select max(gkey)+1 as rtnValue from srv_event";
							    $maxGkey = $this->bm->datareturn($maxGkeyQuery);
								
								$strInsrtFireManEvent = "INSERT INTO srv_event(gkey,operator_gkey,complex_gkey,facility_gkey,yard_gkey,
							    placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator) 
							    VALUES('$maxGkey','31350','36015','46377','47093','$userId',CURRENT_DATE,'6029207','VV','$vvd_gkey','$visit_id','$firemanHRS','HOURS',CURRENT_DATE,'$login_id')";
							   //$strInsrtFireManEventST=$this->bm->dataInsertdb5($strInsrtFireManEvent);
							}
						
							
						}
						
						if($firepumpHRS>0)
						{ 
					        $maxGkeyQuery = "select max(gkey)+1 as rtnValue from srv_event";
							$maxGkey = $this->bm->datareturn($maxGkeyQuery);
							/*$strInsrtFirePumpEvent = "INSERT INTO srv_event(gkey,operator_gkey,complex_gkey,facility_gkey,yard_gkey,
							placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator) 
							VALUES('$maxGkey','31350','36015','46377','47093','$userId',CURRENT_DATE,'6025947','VV','$vvd_gkey','$visit_id','$firepumpHRS','HOURS',CURRENT_DATE,'$login_id')";*/
							//$strInsrtFirePumpEventST=$this->bm->dataInsertdb5($strInsrtFirePumpEvent);
							$rowQu2="select count(*) as rtnValue from srv_event WHERE event_type_gkey=6025947 AND applied_to_gkey='$vvd_gkey'";
						    $rowReturn2=$this->bm->dataReturn($rowQu2);
							
							
							if($rowReturn2>0)
							{
								 $strUpdateFirePumpEvent="Update  srv_event SET quantity='$firepumpHRS'
								where event_type_gkey=6025947 and applied_to_gkey='$vvd_gkey'";
								//$strUpdateFirePumpEventST=$this->bm->dataUpdatedb5($strUpdateFirePumpEvent);
								//echo "<br>";
							}
							else
							{
								 $strInsrtFirePumpEvent = "INSERT INTO srv_event(gkey,operator_gkey,complex_gkey,facility_gkey,yard_gkey,
								placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator) 
								VALUES('$maxGkey','31350','36015','46377','47093','$userId',CURRENT_DATE,'6025947','VV','$vvd_gkey','$visit_id','$firepumpHRS','HOURS',CURRENT_DATE,'$login_id')";
								//$strInsrtFirePumpEventST=$this->bm->dataInsertdb5($strInsrtFirePumpEvent);
								//echo "<br>";
							}
						}

						if($fireofficerHRS>0)
						{
							$maxGkeyQuery = "select max(gkey)+1 as rtnValue from srv_event";
							$maxGkey = $this->bm->datareturn($maxGkeyQuery);
							
							/*$strInsrtFireOfficerEvent = "INSERT INTO srv_event(gkey,operator_gkey,complex_gkey,facility_gkey,yard_gkey,
							placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator) 
							VALUES('$maxGkey','31350','36015','46377','47093','$userId',CURRENT_DATE,'6029206','VV','$vvd_gkey','$visit_id','$fireofficerHRS','HOURS',CURRENT_DATE,'$login_id')";

							$strInsrtFireOfficerEventST=$this->bm->dataInsertdb5($strInsrtFireOfficerEvent);*/
							
							$rowQu3="select count(*) as rtnValue from srv_event WHERE event_type_gkey=6029206 AND applied_to_gkey='$vvd_gkey'";
						    $rowReturn3=$this->bm->dataReturn($rowQu3);
							
							if($rowReturn3>0)
							{
								$strUpdateFireOfficerEvent="Update srv_event SET quantity='$fireofficerHRS'
								where event_type_gkey=6029206 and applied_to_gkey='$vvd_gkey'";
								//$strUpdateFireOfficerEventST=$this->bm->dataUpdatedb5($strUpdateFireOfficerEvent);
								
							}
							else
							{
								$strInsrtFireOfficerEvent = "INSERT INTO srv_event(gkey,operator_gkey,complex_gkey,facility_gkey,yard_gkey,placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator) 
								VALUES('$maxGkey','31350','36015','46377','47093','$userId',CURRENT_DATE,'6029206','VV','$vvd_gkey','$visit_id','$fireofficerHRS','HOURS',CURRENT_DATE,'$login_id')";
								//$strInsrtFireOfficerEventST=$this->bm->dataInsertdb5($strInsrtFireOfficerEvent);
								
							}
						}

						if($fireEngineHRS>0)
						{
							$maxGkeyQuery = "select max(gkey)+1 as rtnValue from srv_event";
							$maxGkey = $this->bm->datareturn($maxGkeyQuery);
							$rowQu4="select count(*) as rtnValue from srv_event WHERE event_type_gkey=6025949 AND applied_to_gkey='$vvd_gkey'";
							$rowReturn4=$this->bm->dataReturn($rowQu4);
							if($rowReturn4>0)
							{
								 $strUpateFireEngineEvent="Update srv_event SET quantity='$fireEngineHRS'
								where event_type_gkey=6025949 and applied_to_gkey='$vvd_gkey'";
								//$strUpdateFireEngineEventST=$this->bm->dataUpdatedb5($strUpateFireEngineEvent);
							
							}
							else
							{
								$strInsrtFireEngineEvent = "INSERT INTO srv_event(gkey,operator_gkey,complex_gkey,facility_gkey,yard_gkey,
								placed_by,placed_time,event_type_gkey,applied_to_class,applied_to_gkey,applied_to_natural_key,quantity,quantity_unit,created,creator) 
								VALUES('$maxGkey','31350','36015','46377','47093','$userId',CURRENT_DATE,'6025949','VV','$vvd_gkey','$visit_id','$fireEngineHRS','HOURS',CURRENT_DATE,'$login_id')";

								//$strInsrtFireEngineEventST=$this->bm->dataInsertdb5($strInsrtFireEngineEvent);
							
							}
						}
						
						if($strUpdateFireManEventST>0 || $strUpdateFirePumpEventST>0 || $strUpdateFireOfficerEventST>0 || $strUpdateFireEngineEventST>0)
						{
							$msg = "<font color='green' size='4'><strong>Rotation: $rotation.  Hot Work Service Successfully Updated.</strong></font>";
						}
						else
						{
							
							  $msg = "<font color='red' size='4'><strong>Failed! Please Try Again.</strong></font>";
						}
						
					}
					else
					{
						$msg = "<font color='red' size='4'><strong>Failed! Please Try Again...</strong></font>";

					}
						
			}
				
		}
		
	}
	
	//fetch edit value 
	function updateWaterDemand($id=null)
	{
		 
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			 $org_Type_id =$this->session->userdata('org_Type_id');
			 $section = $this->session->userdata('section');
		
			if($org_Type_id==1)
			{
			
			  $fetchQu="SELECT rotation_no,vessel_name,demand_qty,supply_type,demand_date,demand_unit,
			  delivery_area,docPath FROM ctmsmis.water_demand_info where id='$id'";
			  $EditFetchRes = $this->bm->dataSelectDb2($fetchQu);
		
			  $rotation_no="";
			  $demand_qty="";
			  $supply_type="";
			  $demand_dateTmp="";
			  $demand_unit="";
			  $delivery_area="";
			  $docPath="";
			  for($i=0;$i<count($EditFetchRes);$i++){
				  $rotation_no=$EditFetchRes[$i]['rotation_no'];
				  $demand_qty=$EditFetchRes[$i]['demand_qty'];
				  $supply_type=$EditFetchRes[$i]['supply_type'];
				  $demand_dateTmp=$EditFetchRes[$i]['demand_date'];
				  $demand_date =date('Y-m-d',strtotime($demand_dateTmp));
				  $demand_unit=$EditFetchRes[$i]['demand_unit'];
				  $delivery_area=$EditFetchRes[$i]['delivery_area'];
				  $docPath=$EditFetchRes[$i]['docPath'];
				
			  }
		 
		       $queryN4= "SELECT vsl_vessels.name AS vsl_name, vsl_vessel_visit_details.ib_vyg as rotation,vsl_vessels.lloyds_id AS vsl_imo,
				ref_bizunit_scoped.name as agent_name, CONCAT(COALESCE(ref_bizunit_scoped.address_line1,''), COALESCE(ref_bizunit_scoped.address_line2,'')) AS address,
				ref_bizunit_scoped.email_address, ref_bizunit_scoped.bizu_gkey, 
				COALESCE (ref_bizunit_scoped.sms_number,ref_bizunit_scoped.telephone) AS contact_num,
				ref_agent_representation.agent_gkey
				FROM vsl_vessels
				INNER JOIN vsl_vessel_visit_details ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
				LEFT JOIN ref_agent_representation ON ref_agent_representation.bzu_gkey=vsl_vessel_visit_details.bizu_gkey
				LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=ref_agent_representation.agent_gkey
				WHERE vsl_vessel_visit_details.ib_vyg='$rotation_no'";
			
				
				//collected from getVslName Ajax Controller
				$queryForRotWise = $this->bm->dataSelect($queryN4);
				$VSL_IMO="";
				$VSL_NAME="";
				$AGENT_NAME="";
				$ADDRESS="";
				$CONTACT_NUM="";
				for($t=0;$t<count($queryForRotWise);$t++){
					 $VSL_NAME=$queryForRotWise[$t]['VSL_NAME'];
					 $VSL_IMO=$queryForRotWise[$t]['VSL_IMO'];
					 $AGENT_NAME=$queryForRotWise[$t]['AGENT_NAME'];
					 $ADDRESS=$queryForRotWise[$t]['ADDRESS'];
					 $CONTACT_NUM=$queryForRotWise[$t]['CONTACT_NUM']; 
				}
				
				$frmType = "edit";
				$data['frmType']=$frmType;
				$data['rotation_no']=$rotation_no;
				$data['demand_qty']=$demand_qty;
				$data['supply_type']=$supply_type;
				$data['demand_date']=$demand_date;
				$data['demand_unit']=$demand_unit;
				$data['VSL_NAME']=$VSL_NAME;
				$data['VSL_IMO']=$VSL_IMO;
				$data['AGENT_NAME']=$AGENT_NAME;
				$data['ADDRESS']=$ADDRESS;
				$data['CONTACT_NUM']=$CONTACT_NUM;
				$data['id']=$id;
				$data['delivery_area']=$delivery_area;
				$data['docPath']=$docPath;
				$data['title']="Water Demand Edit Form";
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('updateWaterForm',$data);
				$this->load->view('jsAssets');
			
		 }
		 else if($org_Type_id==78 AND ($section == 10 || $section == 14 || $section == 15))
		 {	 
			 //CPA Electric Department
			  $fetchQu2="SELECT supply_date,rotation_no,vessel_name,demand_qty,supply_type,demand_date,demand_unit,
			  delivery_area,docPath FROM ctmsmis.water_demand_info where id='$id'";
			  
			  $EditFetchRes2 = $this->bm->dataSelectDb2($fetchQu2);
			  $rotation_no="";
			  $demand_qty="";
			  $demand_unit="";
			  $supply_date="";
			
			  for($t=0;$t<count($EditFetchRes2);$t++){
				  $rotation_no=$EditFetchRes2[$t]['rotation_no'];
				  $supply_date=date('Y-m-d',strtotime($EditFetchRes2[$t]['supply_date']));
				  $demand_qty=$EditFetchRes2[$t]['demand_qty'];
			  }
			
			    $frmType2 = "edit2";
			    $data['title']="Water Demand Edit Form";
				$data['id']=$id;
				$data['rotation_no']=$rotation_no;
				$data['frmType2']=$frmType2;
				$data['supply_date']=$supply_date;
				$data['demand_qty']=$demand_qty;
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('updateFormForEtDepartMent',$data);
				$this->load->view('jsAssets'); 
		 }
		 
		}
		
	}
	
	//finally edit the value
	function editWaterDemand($id=null)
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$org_Type_id =$this->session->userdata('org_Type_id');
			$section = $this->session->userdata('section');

			if($org_Type_id==1)
			{
				if(isset($_POST['submit']))
				{	
						$login_id = $this->session->userdata('login_id');
						$ipAddr = $_SERVER['REMOTE_ADDR'];
						
						$rotation = $this->input->post('rotation');
						$demandQtyVal = $this->input->post('water_demand');
						$demand_unitVal = $this->input->post('demand_unit');
						$demand_dateVal = $this->input->post('demand_date');
						$supplyType = $this->input->post('supply_type');
				
						if($supplyType=='shore')
						{
							$updateQuForShore="Update ctmsmis.water_demand_info SET demand_qty='$demandQtyVal'
							,demand_unit='$demand_unitVal',demand_date='$demand_dateVal',update_by='$login_id',update_at=NOW(),update_ip='$ipAddr' where id='$id' ";

							//$updateShoreResult=$this->bm->dataUpdatedb2($updateQuForShore);
							
							if($updateShoreResult){
								$this->session->set_flashdata("error", "<div class='alert alert-success'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<font size='4'>Demand successfully Updated...</font></div>");
							}else{
								$this->session->set_flashdata("error", "<div class='alert alert-danger'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<font size='4'>Failed! Please try again...</font></div>");
							}
						}	
						else 
						{
							
							$supplyType = $this->input->post('supply_type');
							$delv_area = $this->input->post('delv_area');
							
							$isExistStr="SELECT COUNT(*) AS rtnValue FROM ctmsmis.water_demand_info WHERE water_demand_info.rotation_no='$rotation' AND id='$id'"; 
							$isExist = $this->bm->dataReturnDb2($isExistStr);
						
							$fileName="";
							$fileTmpLoc="";
							
									if (is_uploaded_file($_FILES["file"]["tmp_name"])) 	
									{
									  $fileName = $_FILES["file"]["name"];
									  $fileTmpLoc = $_FILES["file"]["tmp_name"];
									  $splitFile = explode(".", $fileName); // Split file name into an array using the dot
									  $fileExt = end($splitFile); 
									  $rotNo = str_replace("/","_",$rotation);
									  $fileName = "ReqLetter_".$rotNo.".".$fileExt;
									  $docPath = $_SERVER['DOCUMENT_ROOT']."/resources/waterBill/burge/".$fileName;
									  $moveResult = move_uploaded_file($fileTmpLoc,$docPath);
									  
									 $updateQuForBurge="Update ctmsmis.water_demand_info SET demand_qty='$demandQtyVal'
									 ,demand_unit='$demand_unitVal',delivery_area='$delv_area',$demand_date='$demand_dateVal',docPath='$fileName',
									 update_by='$login_id',update_at=NOW(),update_ip='$ipAddr' where id='$id' ";
									
									} else{
										$updateQuForBurge="Update ctmsmis.water_demand_info SET demand_qty='$demandQtyVal'
										,demand_unit='$demand_unitVal',delivery_area='$delv_area',$demand_date='$demand_dateVal',
										update_by='$login_id',update_at=NOW(),update_ip='$ipAddr' where id='$id'";
									}
									//$upload_st2 = $this->bm->dataUpdatedb2($updateQuForBurge);
									/*if($upload_st2 == 1)
									{
		
											$this->session->set_flashdata("error", "<div class='alert alert-success'>
											<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
											<font size='4'>Previous data updated successfully ...</font></div>");							
									}
									else
									{
										$this->session->set_flashdata("error", "<div class='alert alert-danger'>
												<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
												<font size='4'>File not uploaded. Try again.</font></div>");
										unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
									}*/
		
						}
						redirect('Vessel/waterDemandList', 'refresh');	
			   }
			}
			else if($org_Type_id==78 AND ($section == 10 || $section == 14 || $section == 15))
			{
				$login_id = $this->session->userdata('login_id');
				$ipAddr = $_SERVER['REMOTE_ADDR'];
				$rotn = $this->input->post('rotation');
			
				//from pcs start
				$section_query = "SELECT section_value FROM users 
					INNER JOIN tbl_org_section ON tbl_org_section.gkey = users.section
					WHERE login_id = '$login_id'";
				$section_result = $this->bm->dataSelectDB1($section_query);

				$eng_section = null;
				$msg = "";
				if(count($section_result)>0)
				{
					$eng_section = $section_result[0]['section_value'];
				}
				//from pcs end
				
				if(isset($_POST['submit']))
				{
					$supply_dat = $this->input->post('supply_date');
					$supply_qty = $this->input->post('supply_qty');
				
				
					$supplyCond="";
					$supplyQuery = "";
					$allowed = array('SAECCT','SAEGCB','SAENCT');
					if($org_Type_id == 78 && in_array($eng_section,$allowed))  // Sub Assistant Eng.
					{
						$supplyType = "shore";
						$supplyCond  = " AND srv_event.event_type_gkey=6092182";
					}
					else if($org_Type_id == 83)  // Marine 
					{
					    $supplyType = "burge";
						$supplyCond = " AND srv_event.event_type_gkey IN(6092181,12571818,12572266,12572340)";
					}

				    $supplyQuery = "SELECT DISTINCT vsl_vessel_visit_details.vvd_gkey,argo_carrier_visit.id AS visit_id, vsl_vessels.name AS vessel_name
					FROM vsl_vessel_visit_details 
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
					INNER JOIN vsl_vessels ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey
					INNER JOIN srv_event ON srv_event.applied_to_gkey=vsl_vessel_visit_details.vvd_gkey
					WHERE ib_vyg='$rotn' ".$supplyCond;
					
					$vvd_gkey = null;
					$visit_id = null;
					$vessel_name = null;
					$unit='METRIC_TONNES';
					$supplyResult = $this->bm->dataSelect($supplyQuery);
					if(count($supplyResult)>0)
					{
						$vvd_gkey = $supplyResult[0]["VVD_GKEY"];
						$visit_id = $supplyResult[0]["VISIT_ID"];
						$vessel_name = $supplyResult[0]["VESSEL_NAME"];
					}
					
					if($supplyType == "shore")
					{
					$query="Update ctmsmis.water_demand_info SET supply_date='$supply_dat',demand_qty='$supply_qty',update_by='$login_id',update_at=NOW(),update_ip='$ipAddr'
					where id='$id'";
					}
					else if($supplyType == "burge")
					{
						$query="Update ctmsmis.water_demand_info SET supply_date='$supply_dat',demand_qty='$supply_qty',update_by='$login_id',update_at=NOW(),update_ip='$ipAddr'
						where id='$id' ";
					
					}
					//$updateRes=$this->bm->dataUpdatedb2($query);
					if($updateRes)
					{
							if($supplyType == "shore")
							{
								if($supply_qty>0)
								{ 
									$strUpdateWaterEvent="UPDATE srv_event
									SET quantity='$supply_qty'
									WHERE event_type_gkey=6092182 AND applied_to_gkey='$vvd_gkey'";
									//$this->bm->dataUpdatedb5($strUpdateWaterEvent);
								}
							}
						
						$this->session->set_flashdata("error", "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Successfully Updated...</font></div>", 3);
					}
					else
					{
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'>Failed! Please try again...</font></div>", 3);
					}
					redirect('Vessel/waterDemandList', 'refresh');
				
				}
			}
			else
			{
				echo "Org. Type Id Is Invalid";
			}
				
		}
	}
	
	
	// Tug Hiring Process Starts...
	function tugHiringForm()
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
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryVesselNameList = "SELECT vessel_name FROM tug_hire";
			$vesselNameList = $this->bm->dataSelectDB1($queryVesselNameList);
			
			$queryShippingAgentList = "SELECT id,Organization_Name FROM organization_profiles 
									WHERE Org_Type_id='10' AND Organization_Name IS NOT NULL";
			$shippingAgentList = $this->bm->dataSelectDB1($queryShippingAgentList);
						
			$msg="";
			$frmType="new";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring Form";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['vesselNameList']=$vesselNameList;
			$data['shippingAgentList']=$shippingAgentList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tugHiringForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function getVesselNameByRotation()
	{
		$rotation = $_GET["rotation"];
		
		$queryVslName = "SELECT vsl_vessels.name AS vessel_name
			FROM vsl_vessel_visit_details
			INNER JOIN vsl_vessels ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey
			WHERE vsl_vessel_visit_details.ib_vyg='$rotation'";
		$resultVslName = $this->bm->dataSelect($queryVslName);
				
		echo json_encode($resultVslName);
	}
	
	function insertTempTugHiringData()
	{
		$vehicle_type = $_GET["vehicleType"];
		$vehicle_name = $_GET["vehicleName"];
		$vessel_name = $_GET["vesselName"];
		$call_number = $_GET["callNumber"];
		$fromDate = $_GET["fromDate"];
		$fromTime = $_GET["fromTime"];
		$toDate = $_GET["toDate"];
		$toTime = $_GET["toTime"];
		$water_supply = $_GET["waterSupply"];
		$totalHours = $_GET["totalHours"];
		
		$water_supply_st = 0;
		if($water_supply > 0)
		{
			$water_supply_st = 1;
		}
		else
		{
			$water_supply_st = 0;
		}
		
		$query = "INSERT INTO tug_hire_helper(vehicle_type,vehicle_name,vessel_name,call_number,water_supply_st,water_supply,
					from_date,from_time,to_date,to_time,hours) 
		VALUES('$vehicle_type','$vehicle_name','$vessel_name','$call_number','$water_supply_st','$water_supply',
					'$fromDate','$fromTime','$toDate','$toTime','$totalHours')";
		$result = $this->bm->dataInsertDB1($query);
		
		$queryList = "SELECT * FROM tug_hire_helper where vessel_name='$vessel_name' and call_number='$call_number'";
		$resultList = $this->bm->dataSelectDB1($queryList);
		
		echo json_encode($resultList);
	}
	
	function insertTempTugHiringDataForEditing()
	{
		$vehicle_type = $_GET["vehicleType"];
		$vehicle_name = $_GET["vehicleName"];
		$vessel_name = $_GET["vesselName"];
		$call_number = $_GET["callNumber"];
		$fromDate = $_GET["fromDate"];
		$fromTime = $_GET["fromTime"];
		$toDate = $_GET["toDate"];
		$toTime = $_GET["toTime"];
		$water_supply = $_GET["waterSupply"];
		$totalHours = $_GET["totalHours"];
		
		$water_supply_st = 0;
		if($water_supply > 0)
		{
			$water_supply_st = 1;
		}
		else
		{
			$water_supply_st = 0;
		}
		
		$query = "INSERT INTO tug_hire_helper(vehicle_type,vehicle_name,vessel_name,call_number,water_supply_st,water_supply,
					from_date,from_time,to_date,to_time,hours,status) 
		VALUES('$vehicle_type','$vehicle_name','$vessel_name','$call_number','$water_supply_st','$water_supply',
					'$fromDate','$fromTime','$toDate','$toTime','$totalHours','0')";
		$result = $this->bm->dataInsertDB1($query);
		
		$queryList = "SELECT * FROM tug_hire_helper 
					where vessel_name='$vessel_name' and call_number='$call_number' and status='0'";
		$resultList = $this->bm->dataSelectDB1($queryList);
		
		echo json_encode($resultList);
	}
	
	function deleteTempTugHiringData()
	{
		$id = $_GET["id"];
		$vessel_name = $_GET["vesselName"];
		$call_number = $_GET["callNumber"];
		
		$query = "DELETE FROM tug_hire_helper WHERE id='$id'";
		$result = $this->bm->dataDeleteDB1($query);
		
		$queryList = "SELECT * FROM tug_hire_helper where vessel_name='$vessel_name' and call_number='$call_number'";
		$resultList = $this->bm->dataSelectDB1($queryList);
		
		echo json_encode($resultList);
	}
	
	function deleteTempTugHiringDataForEditing()
	{
		$id = $_GET["id"];
		$vessel_name = $_GET["vesselName"];
		$call_number = $_GET["callNumber"];
		
		$query = "DELETE FROM tug_hire_helper WHERE id='$id'";
		$result = $this->bm->dataDeleteDB1($query);
		
		$queryList = "SELECT * FROM tug_hire_helper where vessel_name='$vessel_name' and call_number='$call_number' and status='0'";
		$resultList = $this->bm->dataSelectDB1($queryList);
		
		echo json_encode($resultList);
	}
	
	function deleteTempTugHiringDataForRemoving()
	{
		$vessel_name = $_GET["vessel_name"];
		$call_number = $_GET["call_number"];
		$vehicle_type = $_GET["vehicle_type"];
		$vehicle_name = $_GET["vehicle_name"];
		$water_supply = $_GET["water_supply"];
		$from_date = $_GET["from_date"];
		$from_time = $_GET["from_time"];
		$to_date = $_GET["to_date"];
		$to_time = $_GET["to_time"];
		$hours = $_GET["hours"];
		
		echo $query = "DELETE FROM tug_hire_helper 
					WHERE vessel_name='$vessel_name' AND call_number='$call_number' AND vehicle_type='$vehicle_type' AND 
					vehicle_name='$vehicle_name' AND water_supply='$water_supply' AND from_date='$from_date' AND
					from_time='$from_time' AND to_date='$to_date' AND to_time='$to_time' AND hours='$hours'";
		$result = $this->bm->dataDeleteDB1($query);
		
		echo json_encode($result);
		
		// $queryList = "SELECT * FROM tug_hire_helper where vessel_name='$vessel_name' and call_number='$call_number' and status='0'";
		// $resultList = $this->bm->dataSelectDB1($queryList);
		
		// echo json_encode($resultList);
	}
	
	function updateTempTugHiringData()
	{
		$edit_vehicle_type = $_GET["edit_vehicle_type"];
		$edit_vehicle_name = $_GET["edit_vehicle_name"];
		$edit_water_supply = $_GET["edit_water_supply"];
		$edit_from_date = $_GET["edit_from_date"];
		$edit_from_time = $_GET["edit_from_time"];
		$edit_to_date = $_GET["edit_to_date"];
		$edit_to_time = $_GET["edit_to_time"];
		$edit_hours = $_GET["edit_hours"];
		
		$vehicle_type_prev = $_GET["vehicle_type_prev"];
		$vehicle_name_prev = $_GET["vehicle_name_prev"];
		$water_supply_prev = $_GET["water_supply_prev"];
		$from_date_prev = $_GET["from_date_prev"];
		$from_time_prev = $_GET["from_time_prev"];
		$to_date_prev = $_GET["to_date_prev"];
		$to_time_prev = $_GET["to_time_prev"];
		$vessel_name_prev = $_GET["vessel_name_prev"];
		$call_number_prev = $_GET["call_number_prev"];
		$hours_prev = $_GET["hours_prev"];
		
		$water_supply_st = 0;
		if($edit_water_supply > 0)
		{
			$water_supply_st = 1;
		}
		else
		{
			$water_supply_st = 0;
		}
		
		echo $query = "UPDATE tug_hire_helper SET vehicle_type='$edit_vehicle_type', vehicle_name='$edit_vehicle_name', 
						water_supply_st='$water_supply_st', water_supply='$edit_water_supply', from_date='$edit_from_date', 
						from_time='$edit_from_time', to_date='$edit_to_date', to_time='$edit_to_time', hours='$edit_hours', STATUS='1' 
				WHERE vehicle_type='$vehicle_type_prev' AND vehicle_name='$vehicle_name_prev' AND vessel_name='$vessel_name_prev' 
					AND call_number='$call_number_prev' AND water_supply='$water_supply_prev' AND from_date='$from_date_prev' 
					AND from_time='$from_time_prev' AND to_date='$to_date_prev' AND to_time='$to_time_prev' AND hours='$hours_prev' 
					AND STATUS='1'";
		$result = $this->bm->dataUpdateDB1($query);
		
		echo json_encode($result);
		
		// $queryList = "SELECT * FROM tug_hire_helper where vessel_name='$vessel_name' and call_number='$call_number' and status='0'";
		// $resultList = $this->bm->dataSelectDB1($queryList);
		
		// echo json_encode($resultList);
	}
	
	function tugHiring()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$vessel_name = $this->input->post('vessel_name');
			$call_number = $this->input->post('call_number');
			$rotation = $this->input->post('rotation');
			$shipping_agent_id = $this->input->post('shipping_agent');
			$location = $this->input->post('location');
			$description = $this->input->post('description');
			$total_hours = $this->input->post('total_hours');
			$ivalue = $this->input->post('ivalue');
			
			$login_id = $this->session->userdata('login_id');
			
			$msg = "";
			
			// $chkCount = "select count(*) as rtnValue from tug_hire 
							// where vessel_name='$vessel_name' AND call_number='$call_number'";
			// $countVal = $this->bm->dataReturnDb1($chkCount);
			// if($countVal>0)
			// {
				// $msg = "<font color='red'><b>Sorry! Duplicate Data.</b></font>";
			// }
			// else
			// {
				if($vessel_name == "" or $vessel_name == null)
				{
					$msg = "<font color='red'><b>Sorry! You have not entered vessel name.</b></font>";
				}
				else if($ivalue == 0 or $ivalue == "0")
				{
					$msg = "<font color='red'><b>Sorry! You have not selected any timing.</b></font>";
				}
				else if($ivalue > 0)
				{
					$insertSql="INSERT INTO tug_hire(shipping_agent_id,vessel_name,location,call_number,rotation,description,
								total_hours,entered_by,entered_at) 
						VALUES('$shipping_agent_id','$vessel_name','$location','$call_number','$rotation','$description',
								'$total_hours','$login_id',NOW())";
					$insertStat=$this->bm->dataInsertDB1($insertSql);
					if($insertStat == 1){												
						$sqlGetID = "select id as rtnValue from tug_hire 
									WHERE vessel_name='$vessel_name' AND call_number='$call_number'
									ORDER BY id DESC LIMIT 1";
						$tug_hire_id = $this->bm->dataReturnDb1($sqlGetID);
						
						$queryTempList = "SELECT * FROM tug_hire_helper 
									WHERE vessel_name='$vessel_name' AND call_number='$call_number'";
						$tempList = $this->bm->dataSelectDB1($queryTempList);
						for($i=0;$i<count($tempList);$i++)
						{
							$vehicle_type = $tempList[$i]["vehicle_type"];
							$vehicle_name = $tempList[$i]["vehicle_name"];
							$from_date = $tempList[$i]["from_date"];
							$from_time = $tempList[$i]["from_time"];
							$to_date = $tempList[$i]["to_date"];
							$to_time = $tempList[$i]["to_time"];
							$hours = $tempList[$i]["hours"];
							$water_supply_st = $tempList[$i]["water_supply_st"];
							$water_supply = $tempList[$i]["water_supply"];
							
							$insertTiming="INSERT INTO tug_hire_timing(tug_hire_id,vehicle_type,vehicle_name,water_supply_st,water_supply,
												from_date,from_time,to_date,to_time,hours) 
											VALUES('$tug_hire_id','$vehicle_type','$vehicle_name','$water_supply_st','$water_supply',
												'$from_date','$from_time','$to_date','$to_time','$hours')";
							$insertTimingStat=$this->bm->dataInsertDB1($insertTiming);
						}
						
						
						
						$msg = "<font color='blue'><b>Successfully Saved</b></font>";
					}
				}
				else
				{
					$msg = "<font color='red'><b>Sorry! could not save data.</b></font>";
				}				
			//}
			
			$deleteTempData="DELETE from tug_hire_helper WHERE vessel_name='$vessel_name' AND call_number='$call_number'";
			$this->bm->dataDeleteDB1($deleteTempData);
			
			$login_id = $this->session->userdata('login_id');	
			$section = $this->session->userdata('section');			
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryVesselNameList = "SELECT vessel_name FROM tug_hire";
			$vesselNameList = $this->bm->dataSelectDB1($queryVesselNameList);
			
			$queryShippingAgentList = "SELECT id,Organization_Name FROM organization_profiles 
									WHERE Org_Type_id='10' AND Organization_Name IS NOT NULL";
			$shippingAgentList = $this->bm->dataSelectDB1($queryShippingAgentList);
					
			$frmType="new";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring Form";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['vesselNameList']=$vesselNameList;
			$data['shippingAgentList']=$shippingAgentList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tugHiringForm',$data);
			$this->load->view('jsAssetsList');
			
		}
	}
	
	function tugHiringList()
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
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryTugHiringList = "SELECT tug_hire.*,organization_profiles.Organization_Name
				FROM tug_hire
				INNER JOIN organization_profiles ON tug_hire.shipping_agent_id=organization_profiles.id
				WHERE tug_hire.hm_frwd_st='0'";
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
						
			$msg="";
			$frmType="list";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring List";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['tugHiringList']=$tugHiringList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tugHiringList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function deleteTugHiring()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$msg="";
			$tug_hire_id = $this->input->post("tug_hire_id");
			
			$queryTimingDlt = "DELETE FROM tug_hire_timing WHERE tug_hire_id='$tug_hire_id'";
			$resultTimingDlt = $this->bm->dataDeleteDB1($queryTimingDlt);
			
			$queryTugHireDlt = "DELETE FROM tug_hire WHERE id='$tug_hire_id'";
			$resultTugHireDlt = $this->bm->dataDeleteDB1($queryTugHireDlt);
			
			$msg = "<font color='blue'><b>Data Deleted</b></font>";
			
			$login_id = $this->session->userdata('login_id');	
			$section = $this->session->userdata('section');			
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryTugHiringList = "SELECT * FROM tug_hire WHERE section_frwd_st='0'";
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);						
			
			$frmType="list";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring List";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['tugHiringList']=$tugHiringList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tugHiringList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function tugHiringEditForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$msg="";
			$tug_hire_id = $this->input->post("tug_hire_id");
			
			$queryTugHiringList = "SELECT tug_hire_timing.tug_hire_id,tug_hire_timing.id AS tug_hire_timing_id,
					tug_hire_timing.vehicle_type,tug_hire_timing.vehicle_name,tug_hire_timing.water_supply_st,
					tug_hire_timing.water_supply,tug_hire_timing.from_date,tug_hire_timing.from_time,tug_hire_timing.to_date,
					tug_hire_timing.to_time,tug_hire_timing.hours,tug_hire.vessel_name,
					tug_hire.rotation,tug_hire.location,tug_hire.call_number,
					tug_hire.shipping_agent_id,tug_hire.description
					FROM tug_hire_timing
					INNER JOIN tug_hire ON tug_hire_timing.tug_hire_id=tug_hire.id
					WHERE tug_hire_timing.tug_hire_id='$tug_hire_id'";
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
			
			$tug_hire_id = "";
			$v_name = "";
			$call_no = "";
			for($j=0;$j<count($tugHiringList);$j++){
				$tug_hire_id = $tugHiringList[$j]["tug_hire_id"];
				$v_name = $tugHiringList[$j]["vessel_name"];
				$call_no = $tugHiringList[$j]["call_number"];
			}
			
			$query = "DELETE FROM tug_hire_helper WHERE vessel_name='$v_name' AND call_number='$call_no'";
			$result = $this->bm->dataDeleteDB1($query);
			
			$location = "";
			$rotation = "";
			$vessel_name = "";
			$call_number = "";
			$description = "";
			$shipping_agent_id = "";
			$vehicle_type = "";
			$vehicle_name = "";
			$water_supply = "";
			$hours = 0;
			$tHours = 0;
			for($i=0;$i<count($tugHiringList);$i++){
				$location = $tugHiringList[$i]["location"];
				$rotation = $tugHiringList[$i]["rotation"];
				$vessel_name = $tugHiringList[$i]["vessel_name"];
				$call_number = $tugHiringList[$i]["call_number"];
				$description = $tugHiringList[$i]["description"];
				$shipping_agent_id = $tugHiringList[$i]["shipping_agent_id"];
				$vehicle_type = $tugHiringList[$i]["vehicle_type"];
				$vehicle_name = $tugHiringList[$i]["vehicle_name"];
				$from_date = $tugHiringList[$i]["from_date"];
				$from_time = $tugHiringList[$i]["from_time"];
				$to_date = $tugHiringList[$i]["to_date"];
				$to_time = $tugHiringList[$i]["to_time"];
				$water_supply = $tugHiringList[$i]["water_supply"];
				
				$water_supply_st = 0;
				if($water_supply > 0)
				{
					$water_supply_st = 1;
				}
				else
				{
					$water_supply_st = 0;
				}
				
				$tHours = $tugHiringList[$i]["hours"];
				$query = "INSERT INTO tug_hire_helper(vehicle_type,vehicle_name,vessel_name,call_number,water_supply_st,water_supply,
							from_date,from_time,to_date,to_time,hours,status) 
				VALUES('$vehicle_type','$vehicle_name','$vessel_name','$call_number','$water_supply_st','$water_supply',
							'$from_date','$from_time','$to_date','$to_time','$tHours','1')";
				$this->bm->dataInsertDB1($query);
				
				$hours = $hours + $tugHiringList[$i]["hours"];
			}
			
			$login_id = $this->session->userdata('login_id');	
			$section = $this->session->userdata('section');			
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryVesselNameList = "SELECT vessel_name FROM tug_hire";
			$vesselNameList = $this->bm->dataSelectDB1($queryVesselNameList);
			
			$queryShippingAgentList = "SELECT id,Organization_Name FROM organization_profiles 
									WHERE Org_Type_id='10' AND Organization_Name IS NOT NULL";
			$shippingAgentList = $this->bm->dataSelectDB1($queryShippingAgentList);
					
			$frmType="edit";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring Form";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['vesselNameList']=$vesselNameList;
			$data['shippingAgentList']=$shippingAgentList;
			$data['tugHiringList']=$tugHiringList;
			$data['tug_hire_id']=$tug_hire_id;
			$data['location']=$location;
			$data['rotation']=$rotation;
			$data['vessel_name']=$vessel_name;
			$data['call_number']=$call_number;
			$data['description']=$description;
			$data['shipping_agent_id']=$shipping_agent_id;
			$data['hours']=$hours;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tugHiringEditForm',$data);
			$this->load->view('jsAssetsList');
			
		}
	}
	
	function tugHiringEdit()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$tug_hire_id = $this->input->post('tug_hire_id');
			$vessel_name = $this->input->post('vessel_name');
			$vessel_name_prev = $this->input->post('vessel_name_prev');
			$call_number = $this->input->post('call_number');
			$call_number_prev = $this->input->post('call_number_prev');
			$rotation = $this->input->post('rotation');
			$shipping_agent_id = $this->input->post('shipping_agent');
			$location = $this->input->post('location');
			$description = $this->input->post('description');
			$total_hours = $this->input->post('total_hours');
			$ivalue = $this->input->post('ivalue');			
			
			$login_id = $this->session->userdata('login_id');
			
			$msg = "";
			
			$chkCount = "select count(*) as rtnValue from tug_hire 
							where vessel_name='$vessel_name' AND call_number='$call_number' AND id!='$tug_hire_id'";
			$countVal = $this->bm->dataReturnDb1($chkCount);
			
			if($countVal>0)
			{
				$msg = "<font color='red'><b>Sorry! Duplicate Data.</b></font>";
			}
			else
			{
				if($vessel_name == "" or $vessel_name == null)
				{
					$msg = "<font color='red'><b>Sorry! You have not entered vessel name.</b></font>";
				}				
				else
				{
					$updateSql="UPDATE tug_hire SET shipping_agent_id='$shipping_agent_id',vessel_name='$vessel_name',
								location='$location',call_number='$call_number',rotation='$rotation',
								description='$description',total_hours='$total_hours'
								WHERE id='$tug_hire_id'";
					$updateStat=$this->bm->dataUpdateDB1($updateSql);					
					if($updateStat){	
						$deleteTimingData="DELETE from tug_hire_timing WHERE tug_hire_id='$tug_hire_id'";
						$this->bm->dataDeleteDB1($deleteTimingData);
						
						$queryTempList = "SELECT * FROM tug_hire_helper 
									WHERE vessel_name='$vessel_name_prev' AND call_number='$call_number_prev'";
						$tempList = $this->bm->dataSelectDB1($queryTempList);
						for($i=0;$i<count($tempList);$i++)
						{
							$vehicle_type = $tempList[$i]["vehicle_type"];
							$vehicle_name = $tempList[$i]["vehicle_name"];
							$from_date = $tempList[$i]["from_date"];
							$from_time = $tempList[$i]["from_time"];
							$to_date = $tempList[$i]["to_date"];
							$to_time = $tempList[$i]["to_time"];
							$hours = $tempList[$i]["hours"];
							$water_supply_st = $tempList[$i]["water_supply_st"];
							$water_supply = $tempList[$i]["water_supply"];
							
							$insertTiming="INSERT INTO tug_hire_timing(tug_hire_id,vehicle_type,vehicle_name,water_supply_st,water_supply,
												from_date,from_time,to_date,to_time,hours) 
											VALUES('$tug_hire_id','$vehicle_type','$vehicle_name','$water_supply_st','$water_supply',
												'$from_date','$from_time','$to_date','$to_time','$hours')";
							$insertTimingStat=$this->bm->dataInsertDB1($insertTiming);
						}
						
						
						
						$msg = "<font color='blue'><b>Successfully Saved</b></font>";
					}
				}				
			}
			
			$deleteTempData="DELETE from tug_hire_helper WHERE vessel_name='$vessel_name_prev' AND call_number='$call_number_prev'";
			$this->bm->dataDeleteDB1($deleteTempData);
			
			$login_id = $this->session->userdata('login_id');	
			$section = $this->session->userdata('section');			
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryVesselNameList = "SELECT vessel_name FROM tug_hire";
			$vesselNameList = $this->bm->dataSelectDB1($queryVesselNameList);
			
			$queryShippingAgentList = "SELECT id,Organization_Name FROM organization_profiles 
									WHERE Org_Type_id='10' AND Organization_Name IS NOT NULL";
			$shippingAgentList = $this->bm->dataSelectDB1($queryShippingAgentList);
					
			$frmType="new";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring Form";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['vesselNameList']=$vesselNameList;
			$data['shippingAgentList']=$shippingAgentList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tugHiringForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function tugHiringListForForwardingToHM()
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
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryTugHiringList = "SELECT * FROM tug_hire WHERE section_frwd_st='0'";
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
						
			$msg="";
			$frmType="list";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring List";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['tugHiringList']=$tugHiringList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tugHiringListForForwardingToHM',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	
	function tugHiringSearch()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$from_date = $this->input->post("from_date");
			$to_date = $this->input->post("to_date");
			
			$login_id = $this->session->userdata('login_id');	
			$section = $this->session->userdata('section');			
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryTugHiringList = "SELECT * FROM tug_hire 
								WHERE section_frwd_st='0' AND (DATE(entered_at) BETWEEN '$from_date' AND '$to_date')";
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
				
			$msg="";
			$frmType="search";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring List";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['from_date']=$from_date;
			$data['to_date']=$to_date;
			$data['tugHiringList']=$tugHiringList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tugHiringListForForwardingToHM',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function forwardTugHiringToHM()
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
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$ids = $_POST['idchk'];
			foreach ($ids as $id)
				{
					$updateSt="UPDATE tug_hire SET section_frwd_st='1',section_frwd_by='$login_id',section_frwd_at=NOW() WHERE id='$id'";
					$update_st = $this->bm->dataUpdateDB1($updateSt);
				}
			$msg = "<font color='blue'><b>Successfully Forwarded</b></font>";
			$queryTugHiringList = "SELECT * FROM tug_hire WHERE section_frwd_st='0'";
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
					
			$frmType="list";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring List";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['tugHiringList']=$tugHiringList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tugHiringListForForwardingToHM',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function SelectTugHiringListForForwarding()
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
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryTugHiringList = "SELECT * FROM tug_hire WHERE section_frwd_st='1' AND hm_frwd_st='0'";
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
						
			$msg="";
			$frmType="list";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Forward Tug Hiring to Accounts";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['tugHiringList']=$tugHiringList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tugForwardingToAccForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function forwardTugHiringListToAccounts()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$forward_date = $this->input->post("forward_date");
			$login_id = $this->session->userdata('login_id');
			$msg="";
			
			if(isset($_POST['idchk']))
			{
				$ids = $_POST['idchk'];
				
				$sql_nextFileSl = "SELECT IFNULL(MAX(forward_sl),0)+1 AS rtnValue FROM tug_hire_forward_info";
				$forward_sl = $this->bm->dataReturnDb1($sql_nextFileSl);
				$file_no = "ডিসি/বিএস/সিএস/".$forward_sl;
				$no_of_vsl=count($ids);
				
				$insert_str="INSERT INTO tug_hire_forward_info (forward_date,forward_sl,file_no,no_of_vsl,entered_by,entered_at)
					VALUES ('$forward_date','$forward_sl','$file_no','$no_of_vsl','$login_id',NOW())";					
				$insert_st = $this->bm->dataInsertDB1($insert_str);
				
				$sql_forward_id = "SELECT id AS rtnValue FROM tug_hire_forward_info ORDER BY id DESC LIMIT 1";
				$hm_frwd_id = $this->bm->dataReturnDb1($sql_forward_id);
								
				foreach ($ids as $id)
				{
					$updateSt="UPDATE tug_hire SET hm_frwd_st='1',hm_frwd_by='$login_id',hm_frwd_at=NOW(),hm_frwd_id='$hm_frwd_id' 
								WHERE id='$id'";
					$update_st = $this->bm->dataUpdateDB1($updateSt);	
				}
				
				$msg = "<font color='blue'><b>Forwarded Succesfully.</b></font>";
			}
			else
			{
				$msg = "<font color='red'><b>Sorry! you have not selected any vessel.</b></font>";
			}
			
				
			$section = $this->session->userdata('section');			
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryTugHiringList = "SELECT * FROM tug_hire WHERE section_frwd_st='1' AND hm_frwd_st='0'";
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
						
			
			$frmType="list";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Forward Tug Hiring to Accounts";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['tugHiringList']=$tugHiringList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tugForwardingToAccForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function forwardedTugHiringLetterList()
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
			$org_Type_id =$this->session->userdata('org_Type_id');
			$tugHiringList = "";
			
			if($section=='acc') {
				$queryTugHiringList = "SELECT tug_hire_forward_info.*,
				(SELECT COUNT(*) FROM tug_hire WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='0') AS pending,
				(SELECT COUNT(*) FROM tug_hire WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='1') AS forwarded
				FROM tug_hire_forward_info";
			} else if($section=='billop') {
				$queryTugHiringList = "SELECT tug_hire_forward_info.*,
				(SELECT COUNT(*) FROM tug_hire WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id) AS total_forwarded_by_hm,
				(SELECT COUNT(*) FROM tug_hire 
				WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='1' AND bill_op_user_id='$login_id') AS forwarded,
				(SELECT COUNT(*) FROM tug_hire 
				WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='1' 
				AND bill_op_user_id='$login_id' AND bill_generation_st='1') AS bill_generated,
				(SELECT COUNT(*) FROM tug_hire 
				WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='1' 
				AND bill_op_user_id='$login_id' AND bill_generation_st='0') AS pending
				FROM tug_hire_forward_info";
			} else {
				$queryTugHiringList = "SELECT * FROM tug_hire_forward_info ORDER BY id DESC LIMIT 500";
			}
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
				
			$msg="";
			$frmType="list";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring List";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['tugHiringList']=$tugHiringList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('forwardedTugHiringLetterList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function SearchForwardedTugHiringLetterListByDate()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{		
			$from_date=$this->input->post('from_date');
			$to_date=$this->input->post('to_date');
			
			$login_id = $this->session->userdata('login_id');	
			$section = $this->session->userdata('section');			
			$org_Type_id =$this->session->userdata('org_Type_id');
			$tugHiringList = "";
			if($section=='acc') {
				$queryTugHiringList = "SELECT tug_hire_forward_info.*,
				(SELECT COUNT(*) FROM tug_hire WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='0') AS pending,
				(SELECT COUNT(*) FROM tug_hire WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='1') AS forwarded
				FROM tug_hire_forward_info WHERE forward_date BETWEEN '$from_date' AND '$to_date'";
			} else if($section=='billop') {
				$queryTugHiringList = "SELECT tug_hire_forward_info.*,
				(SELECT COUNT(*) FROM tug_hire WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id) AS total_forwarded_by_hm,
				(SELECT COUNT(*) FROM tug_hire 
				WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='1' AND bill_op_user_id='$login_id') AS forwarded,
				(SELECT COUNT(*) FROM tug_hire 
				WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='1' 
				AND bill_op_user_id='$login_id' AND bill_generation_st='1') AS bill_generated,
				(SELECT COUNT(*) FROM tug_hire 
				WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='1' 
				AND bill_op_user_id='$login_id' AND bill_generation_st='0') AS pending
				FROM tug_hire_forward_info WHERE forward_date BETWEEN '$from_date' AND '$to_date'";
			} else {
				$queryTugHiringList = "SELECT * FROM tug_hire_forward_info WHERE forward_date BETWEEN '$from_date' AND '$to_date'";
			}
			
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
				
			$msg="";
			$frmType="search";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring List";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['from_date']=$from_date;
			$data['to_date']=$to_date;
			$data['tugHiringList']=$tugHiringList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('forwardedTugHiringLetterList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function tugHiringForwardingLetterById()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{			
			$forwarding_id=$this->input->post('forwarding_id');
						
			$str="SELECT * FROM tug_hire_forward_info WHERE id='$forwarding_id'";
			$forwardingInfoById = $this->bm->dataSelectDB1($str);
			
			$forward_date = "";
			for($i=0;$i<count($forwardingInfoById);$i++)
			{
				$forward_date = $forwardingInfoById[$i]["forward_date"];
			}
			
			$data['forward_date']=$forward_date;
			$data['forwardingInfoById']=$forwardingInfoById;
			
			$this->load->view('tugHireForwardingLetter',$data);
			
		}
	}
	
	function tugHiringForwardingStatementById()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{			
			$forwarding_id=$this->input->post('forwarding_id');
			$section = $this->session->userdata('section');	
			$login_id = $this->session->userdata('login_id');
			
			if($section == "billop"){
				$str="SELECT tug_hire.vessel_name,tug_hire.call_number,tug_hire.rotation,
					tug_hire.total_hours,tug_hire.description,tug_hire_forward_info.forward_date,tug_hire.section_frwd_by
					FROM tug_hire
					INNER JOIN tug_hire_forward_info ON tug_hire.hm_frwd_id = tug_hire_forward_info.id
					WHERE tug_hire.hm_frwd_id='$forwarding_id' AND tug_hire.sr_accnt_frwd_st='1' AND tug_hire.bill_op_user_id='$login_id'";
			} else {
				$str="SELECT tug_hire.vessel_name,tug_hire.call_number,tug_hire.rotation,
					tug_hire.total_hours,tug_hire.description,tug_hire_forward_info.forward_date,tug_hire.section_frwd_by
					FROM tug_hire
					INNER JOIN tug_hire_forward_info ON tug_hire.hm_frwd_id = tug_hire_forward_info.id
					WHERE tug_hire.hm_frwd_id='$forwarding_id'";
			}
			
			$tugHireInfoByForwardingId = $this->bm->dataSelectDB1($str);
			
			$this->data['tugHireInfoByForwardingId']=$tugHireInfoByForwardingId;
			
			$this->load->library('m_pdf');
			$mpdf->use_kwt = true;
			$mpdf->simpleTables = true;
				
			$html=$this->load->view('tugHiringStatement',$this->data, true); 
			$pdfFilePath ="tugHiringStatement";

			$pdf = $this->m_pdf->load();
			//$stylesheet = file_get_contents(CSS_PATH.'style.css'); // external css
			//$stylesheet = file_get_contents('resources/styles/test.css'); 
			//$pdf->useSubstitutions = true; 				
			//$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
		
			//$pdf->WriteHTML($stylesheet,1);
			//$pdf = new mPDF('utf-8', 'A4-L');  //have tried several of the formats
			//$pdf->WriteHTML($content,2);
			$pdf->WriteHTML($html,2);
				
			$pdf->Output($pdfFilePath, "I");
			
		}
	}
	
	function SelectTugHiringListForForwardingToBillOp()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$forwarding_id=$this->input->post('forwarding_id');
			$login_id = $this->session->userdata('login_id');	
			$section = $this->session->userdata('section');			
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryTugHiringList = "SELECT * FROM tug_hire WHERE sr_accnt_frwd_st='0' AND hm_frwd_id='$forwarding_id'";
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
			
			$billOpListStr="SELECT login_id, u_name  FROM users WHERE users.section='billop'";
			$billOpList = $this->bm->dataSelectDB1($billOpListStr);

			$msg="";
			$frmType="list";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Forward Tug Hiring to Bill Operator";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['tugHiringList']=$tugHiringList;
			$data['billOpList']=$billOpList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('SelectTugHiringListForForwardingToBillOp',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function forwardTugHiringListToBillOp()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$ip_address = $_SERVER['REMOTE_ADDR'];
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$bill_op_user_id = $this->input->post("bill_op");
			$login_id = $this->session->userdata('login_id');
			$msg="";
			
			if(isset($_POST['idchk']))
			{
				$ids = $_POST['idchk'];
					
				foreach ($ids as $id)
				{
					$updateSt="UPDATE tug_hire SET sr_accnt_frwd_st='1',sr_accnt_frwd_by='$login_id',sr_accnt_frwd_at=NOW(),
							sr_accnt_frwd_ip='$ip_address',bill_op_user_id='$bill_op_user_id' 
							WHERE id='$id'";
					$update_st = $this->bm->dataUpdateDB1($updateSt);	
				}
				
				$msg = "<font color='blue'><b>Forwarded Succesfully.</b></font>";
			}
			else
			{
				$msg = "<font color='red'><b>Sorry! you have not selected any vessel.</b></font>";
			}
				
			$login_id = $this->session->userdata('login_id');	
			$section = $this->session->userdata('section');			
			$org_Type_id =$this->session->userdata('org_Type_id');
			$tugHiringList = "";
			if($section=='acc') {
				$queryTugHiringList = "SELECT tug_hire_forward_info.*,
				(SELECT COUNT(*) FROM tug_hire WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='0') AS pending,
				(SELECT COUNT(*) FROM tug_hire WHERE tug_hire.hm_frwd_id=tug_hire_forward_info.id AND sr_accnt_frwd_st='1') AS forwarded
				FROM tug_hire_forward_info";
			} else {
				$queryTugHiringList = "SELECT * FROM tug_hire_forward_info ORDER BY id DESC LIMIT 500";
			}
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
				
			$frmType="list";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring List";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['tugHiringList']=$tugHiringList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('forwardedTugHiringLetterList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	function pendingTugHiringListForBillGeneration()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{		
			$forwarding_id = $this->input->post("forwarding_id");
			$login_id = $this->session->userdata('login_id');
			$msg="";
			
			$section = $this->session->userdata('section');			
			$org_Type_id =$this->session->userdata('org_Type_id');
			
			$queryTugHiringList = "SELECT * FROM tug_hire 
								WHERE hm_frwd_id='$forwarding_id' AND bill_generation_st='0' AND bill_op_user_id='$login_id'";
			$tugHiringList = $this->bm->dataSelectDB1($queryTugHiringList);
									
			$frmType="list";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Tug Hiring Bill";
			$data['login_id']=$login_id;
			$data['section']=$section;
			$data['org_Type_id']=$org_Type_id;
			$data['tugHiringList']=$tugHiringList;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('pendingTugHiringListForBillGeneration',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	
	// Tug Hiring Process Ends...
}