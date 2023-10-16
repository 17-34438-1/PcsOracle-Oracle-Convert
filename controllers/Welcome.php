<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	function __construct()
	{
		parent::__construct();	
        $this->load->library(array('session', 'form_validation'));
        $this->load->model(array('CI_auth'));
        $this->load->helper(array('html','form', 'url'));
		//$this->load->driver('cache');
		$this->load->helper('file');
		$this->load->model('CI_auth', 'bm', TRUE);
		$this->load->library("pagination");
		date_default_timezone_set("Asia/Dhaka");
			
			
	
			
		header("cache-Control: no-store, no-cache, must-revalidate");
		header("cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");			
	}
	
	
	public function index()
	{
		
		// $this->bm->updateAssignmet();
		if($this->session->userdata('login_id'))
		{
			
				
			$org_Type_id = $this->session->userdata('org_Type_id');
			$data['org_Type_id']=$org_Type_id;
			
			if($org_Type_id==2)	{
				
				//Code for C&F Assignment List in dashboard starts-----------------
				$org_license = $this->session->userdata('org_license');
				$data['msg'] = "";
				$cond = "";
				$sql_assignmentList = "";
				
				// $sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
				// FROM ctmsmis.tmp_oracle_assignment 
				// WHERE Block_No = 'NCY' AND cf_lic='$org_license' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";
				
				$sql_assignmentList = "SELECT DISTINCT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
				FROM ctmsmis.tmp_oracle_assignment 
				WHERE cf_lic='$org_license' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";
				$rslt_assignmentList=$this->bm->dataSelect($sql_assignmentList);
				
				// 2021-04-21 - start	- work on custom_remarks
				if(count($rslt_assignmentList)==0)
				{
					$sql_cnfGkey = "SELECT gkey AS rtnValue FROM ref_bizunit_scoped WHERE id='$org_license'";
					$cnfGkey = $this->bm->dataReturn($sql_cnfGkey);										
					
					$sql_assignmentList="SELECT DISTINCT a.gkey AS unit_gkey,a.id AS cont_no,k.name  AS cnf,a.freight_kind AS cont_status,
					(SELECT ib_vyg FROM sparcsn4.argo_carrier_visit
					INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
					WHERE sparcsn4.argo_carrier_visit.gkey=b.actual_ib_cv
					) AS rot_no,
					CONCAT(k.address_line1,k.address_line2) AS cnf_addr,
					b.flex_date01,
					(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.inv_unit_equip
					INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
					INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
					WHERE sparcsn4.inv_unit_equip.unit_gkey=a.gkey
					)  AS size,
					(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2) FROM sparcsn4.inv_unit_equip
					INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
					INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
					WHERE sparcsn4.inv_unit_equip.unit_gkey=a.gkey
					)  AS height,
					DATE(b.flex_date01) AS assignmentDate, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
					mfdch_desc,'' AS custom_remarks,
					b.last_pos_slot,(SELECT ctmsmis.cont_yard(b.last_pos_slot)) AS Yard_No,
					(SELECT ctmsmis.cont_block(b.last_pos_slot, Yard_No)) AS Block_No							
					FROM sparcsn4.inv_unit a
					INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
					INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods
					INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=a.gkey	
					LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
					INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value	
					WHERE (b.flex_date01 BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')) AND j.consignee_bzu = '$cnfGkey' AND config_metafield_lov.mfdch_value!='CANCEL'";
					$rslt_assignmentList=$this->bm->dataSelect($sql_assignmentList);
				}
				// echo $sql_assignmentList;
				// 2021-04-21 - end
				
				$data['rslt_assignmentList']=$rslt_assignmentList;
				
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('dashboard',$data);
				$this->load->view('jsAssetsList');
				//Code for C&F Assignment List in dashboard ends-------------------
			}
			else
			{
			
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('dashboard');
				$this->load->view('jsAssets');
			}
		}
		else{
		
			$query=$this->welcomePageQuery();
			$rtnVesselList = $this->bm->dataSelect($query);
			$data['rtnVesselList']=$rtnVesselList;
			
			
		// Hit Counter---------------------------------------------
			$user_agent="";
			$user_agent=$_SERVER ['HTTP_USER_AGENT'];
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			
			//echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";
			
			$chkstr = "SELECT count(*) as rtnValue FROM visitor_log where visitor_log.visit_date=DATE(NOW())";
			$chkst=$this->bm->dataReturnDB1($chkstr);
			if($chkst==0)
			{
				
				$sqlInsert = "INSERT INTO visitor_log (visit_date, total_hit) VALUES (DATE(NOW()),'1')";									
				$insertStat = $this->bm->dataInsertDB1($sqlInsert);
				$query = "SELECT id, total_hit FROM visitor_log where visitor_log.visit_date=DATE(NOW()) ";
				$log_reslt=$this->bm->dataSelectDB1($query);	
			 	$visit_log_id=$log_reslt[0]['id'];
			}
			else
			{
				$query = "SELECT id, total_hit FROM visitor_log where visitor_log.visit_date=DATE(NOW()) ";
				$log_reslt=$this->bm->dataSelectDB1($query);	
				$visit_log_id=$log_reslt[0]['id'];
				$new_count=$log_reslt[0]['total_hit']+1;

				//$new_count=$total_hit+1;
				$sql = "UPDATE visitor_log SET total_hit='$new_count' WHERE visitor_log.id='$visit_log_id'";
				$row3=$this->bm->dataUpdateDB1($sql);
			}
			
			$todayHit="";
			//$query = "SELECT count(*) as rtnValue FROM visitor_log_deatil";
			//$counts=$this->bm->dataReturnDB1($query);	

			$tohitStr = "SELECT count(*) as rtnValue FROM visitor_log_deatil where DATE(visitor_log_deatil.visit_time) =DATE(NOW())";
			$todayHit=$this->bm->dataReturnDB1($tohitStr);
			//$visit_log_id=$visit_log[0]['id'];
			//$counts=$visit_log[0]['total_hit'];
			//$new_count=$counts+1;
			//$sql = "UPDATE visitor_log SET visit_date=CURDATE(), total_hit='$new_count'";
			//$row3=$this->bm->dataUpdateDB1($sql);
			
			$sqlInsert = "INSERT INTO visitor_log_deatil (visiting_id, ip_addr, browser, visit_time) VALUES ('$visit_log_id', '$ipaddr', '$user_agent', NOW())";									
			$insertStat = $this->bm->dataInsertDB1($sqlInsert);
			
			//$data['counts']=$counts;
			$data['todayHit']=$todayHit;


			$body = "";
			$data['body']=$body;

			$this->load->view('cssVesselList');
			$this->load->view('jsVesselList');
			$this->load->view('FrontEnd/header');
			$this->load->view('FrontEnd/slider');
			$this->load->view('FrontEnd/index',$data);
			$this->load->view('FrontEnd/footer',$data);
		}
	}
	
	function welcomePageQuery()
	{
		/*$qry="SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,sparcsn4.vsl_vessels.name,sparcsn4.vsl_vessel_visit_details.ib_vyg,sparcsn4.vsl_vessel_visit_details.ob_vyg,
		LEFT(sparcsn4.argo_carrier_visit.phase,2) AS phase_num,SUBSTR(sparcsn4.argo_carrier_visit.phase,3) AS phase_str,sparcsn4.argo_visit_details.eta,
		sparcsn4.argo_visit_details.etd,sparcsn4.argo_carrier_visit.ata,
		sparcsn4.argo_carrier_visit.atd,sparcsn4.ref_bizunit_scoped.id AS agent,
		(SELECT sparcsn4.argo_quay.id
		FROM sparcsn4.argo_quay
		INNER JOIN sparcsn4.vsl_vessel_berthings brt ON brt.quay=sparcsn4.argo_quay.gkey
		WHERE brt.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey ORDER BY brt.ata DESC LIMIT 1) AS berth,
		IFNULL(sparcsn4.vsl_vessel_visit_details.flex_string02,sparcsn4.vsl_vessel_visit_details.flex_string03) AS berthop
		FROM sparcsn4.argo_carrier_visit
		INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
		INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
		INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey			
		WHERE sparcsn4.argo_carrier_visit.phase IN ('20INBOUND','30ARRIVED','40WORKING','50COMPLETE','60DEPARTED')
		ORDER BY sparcsn4.argo_carrier_visit.phase";*/
		
		$qry="SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name,vsl_vessel_visit_details.ib_vyg,vsl_vessel_visit_details.ob_vyg,
		SUBSTR(argo_carrier_visit.phase, 3, Length( argo_carrier_visit.phase)) AS phase_num,SUBSTR(argo_carrier_visit.phase,3) AS phase_str,
		to_char( argo_visit_details.eta, 'yyyy/mm/dd hh:mi:ss am') AS eta,to_char( argo_visit_details.etd, 'yyyy/mm/dd hh:mi:ss am')AS etd,
		to_char( argo_carrier_visit.ata, 'yyyy/mm/dd hh:mi:ss am') AS ata,to_char( argo_carrier_visit.atd, 'yyyy/mm/dd hh:mi:ss am')AS atd,
		ref_bizunit_scoped.id AS agent,
		(SELECT argo_quay.id
		FROM argo_quay
		INNER JOIN vsl_vessel_berthings brt ON brt.quay=argo_quay.gkey
		WHERE brt.vvd_gkey=vsl_vessel_visit_details.vvd_gkey ORDER BY brt.ata DESC FETCH FIRST 1 ROWS ONLY ) AS berth,
		COALESCE(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03) AS berthop
		FROM argo_carrier_visit
		INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey			
		WHERE argo_carrier_visit.phase IN ('20INBOUND','30ARRIVED','40WORKING','50COMPLETE','60DEPARTED')
		ORDER BY argo_carrier_visit.phase";
		
		return $qry;
	}
	
	 function containerHandlingView(){
		  	// echo "<pre>";
       //  print_r("hello bangladesh");
        // echo "</pre>";
       //  exit();
		 
			
			if($this->uri->segment(3)){
				$session_id = $this->session->userdata('value');
			
			$this->data['title']="CONTAINER HANDLING REPORT";
			
				 if($this->input->post())
				{
					$equipment=$this->input->post('equipment');
					$shift=$this->input->post('shift');
					$sVal=$this->input->post('sVal');
					$sDate=$this->input->post('sDate');
				}
				else
				{
					$equipment="All";
					$shift="All";
					$sDate = date('Y-m-d');
					$sVal = null;
				}
				
				
				$this->data['equipment']=$equipment;
				$this->data['sVal']=$sVal;
				$this->data['sDate']=$sDate;
				$this->data['shift']=$shift;
			

				$this->load->view('cssAssetsList');
				$this->load->view('FrontEnd/myReportContainerHandlingListPrint', $this->data);
				$this->load->view('jsAssetsList.php');

			}else{
				$session_id = $this->session->userdata('value');
			
			$this->data['title']="CONTAINER HANDLING REPORT";
			
				 if($this->input->post())
				{
					$equipment=$this->input->post('equipment');
					$shift=$this->input->post('shift');
					$sVal=$this->input->post('sVal');
					$sDate=$this->input->post('sDate');
				}
				else
				{
					$equipment="All";
					$shift="All";
					$sDate = date('Y-m-d');
					$sVal = null;
				}
				
				
				$this->data['equipment']=$equipment;
				$this->data['sVal']=$sVal;
				$this->data['sDate']=$sDate;
				$this->data['shift']=$shift;
			

				$this->load->view('cssAssetsList');
				$this->load->view('FrontEnd/myReportContainerHandlingList', $this->data);
				$this->load->view('jsAssetsList.php');
			}
		}
	
	
	
}
