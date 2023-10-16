<?php

class ReleaseOrderController extends CI_Controller {
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

	function index()
	{
		
	}

	function roForm($msg = null)
	{

		$login_id = $this->session->userdata('login_id');
		$u_name = $this->session->userdata('User_Name');

		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$org_id = $this->session->userdata('org_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			
			$sqlAgent = "SELECT vcms_vehicle_agent.id,agent_type,agent_name,users.Cell_No_1,users.Address_1,users.Address_2,vcms_vehicle_agent.agent_code
			FROM users
			INNER JOIN organization_profiles ON organization_profiles.id=users.org_id
			INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.agency_code=organization_profiles.License_No
			INNER JOIN vcms_vehicle_agent ON vcms_vehicle_agent.agency_id=vcms_vehicle_agency.id
			WHERE users.login_id='$login_id' AND  vcms_vehicle_agent.agent_type LIKE '%Jetty Sircar%'";
			$rslt_agent = $this->bm->dataSelectDb1($sqlAgent);
			
			$data['rslt_agent'] = $rslt_agent;
			$data['login_id']=$login_id;
			$data['u_name']=$u_name;
			$data['org_Type_id']=$org_Type_id;
			$data['flag'] = 0;
			$data['msg']=$msg;
			$data['title']="RELEASE ORDER FORM";								

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('roForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function getAgentByCnf()
	{
		$cnf_ain = trim($_GET["cnf_ain"]);
		
		$login_id = $cnf_ain."CF";
		
		$sqlAgent = "SELECT vcms_vehicle_agent.id,agent_type,agent_name,users.Cell_No_1,users.Address_1,users.Address_2,
					vcms_vehicle_agent.agent_code
					FROM users
					INNER JOIN organization_profiles ON organization_profiles.id=users.org_id
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.agency_code=organization_profiles.License_No
					INNER JOIN vcms_vehicle_agent ON vcms_vehicle_agent.agency_id=vcms_vehicle_agency.id
					WHERE users.login_id='$login_id' AND  vcms_vehicle_agent.agent_type LIKE '%Jetty Sircar%'";
		$agentList = $this->bm->dataSelectDb1($sqlAgent);
		echo json_encode($agentList);
	}

	function submitRO()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$org_license = $this->session->userdata('org_license');
		$login_id = $this->session->userdata('login_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$msg = null;
			
			$org_id = "";
			$cnf_ain = "";
			
			
			if($org_Type_id == 28 or $org_Type_id == "28")
			{
				$cnf_ain = $this->input->post("cnf_ain");
				
				$sql_getCnfData = "SELECT id,License_No,Org_Type_id
					FROM organization_profiles
					WHERE organization_profiles.AIN_No='$cnf_ain' AND AIN_No_New='$cnf_ain'";
				$cnf_data = $this->bm->dataSelectDB1($sql_getCnfData);
								
				$org_license = "";
				
				for($c=0;$c<count($cnf_data);$c++)
				{
					$org_id 	 = $cnf_data[$c]['id'];
					$org_license = $cnf_data[$c]['License_No'];
				}
				
			}
			
			if($org_Type_id == "2")
			{
				$org_id =$this->session->userdata('org_id');
			}
			
			
			
			$sql_getAINLic = "SELECT AIN_No,License_No
			FROM organization_profiles
			WHERE organization_profiles.License_No='$org_license' AND Org_Type_id='2'";
			$rslt_getAINLic = $this->bm->dataSelectDB1($sql_getAINLic);
			
			$ainNo = "";
			$licNo = "";
			
			for($i=0;$i<count($rslt_getAINLic);$i++)
			{
				$ainNo = $rslt_getAINLic[$i]['AIN_No'];
				$licNo = $rslt_getAINLic[$i]['License_No'];
			}
			
			// intakhab - 2022-05-18
			if($login_id == "devcf")
			{
				$ainNo = "111111";
			}
			if($login_id == "testcf")
			{
				$ainNo = "222222";
			}
			if($login_id == "zakir")
			{
				$ainNo = "333333";
			}
			// intakhab - 2022-05-18
			
			if($ainNo == "" or $ainNo == null)
			{
				$msg = "<font color='red'>AIN No Missing</font>";
				
				$data['msg']=$msg;
				$data['title']="RELEASE ORDER FORM";								
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('roForm',$data);
				$this->load->view('jsAssets');
				return;
			}
			else
			{				
				$imp_rot = $this->input->post('imp_rot');
				$bl_no = $this->input->post('bl_no');
				$agent_id = $this->input->post('agent_id');
				$roType = $this->input->post('roType');
				
				$sql_supDtlId = "SELECT id FROM igm_supplimentary_detail WHERE Import_Rotation_No='$imp_rot' AND BL_No='$bl_no'";
				
				$rslt_supDtlId = $this->bm->dataSelectDB1($sql_supDtlId);
				
				$supDtlId = "";
				for($i=0;$i<count($rslt_supDtlId);$i++)
				{
					$supDtlId = $rslt_supDtlId[$i]['id'];
				}
				
				$dtlId = "";
				if($supDtlId == "")
				{
					$sql_dtlId = "SELECT id FROM igm_details WHERE Import_Rotation_No='$imp_rot' AND BL_No='$bl_no'";
					$rslt_dtlId = $this->bm->dataSelectDB1($sql_dtlId);
					
					for($i=0;$i<count($rslt_dtlId);$i++)
					{
						$dtlId = $rslt_dtlId[$i]['id'];
					}
				}
				
				$sql_nextSl = "SELECT IFNULL((MAX(ro_sl)+1),1) AS rtnValue
				FROM release_order_record
				WHERE cnf_ain='$ainNo' AND ro_year=SUBSTRING(YEAR(NOW()), 3)";

				$nextSl = $this->bm->dataReturnDB1($sql_nextSl);
				$roYr = substr(date("Y"),2);
				
				$sql_chkROSubmit = "SELECT id FROM release_order_record WHERE imp_rot='$imp_rot' AND bl_no='$bl_no'";
				$rslt_chkROSubmit = $this->bm->dataSelectDB1($sql_chkROSubmit);	

				if(count($rslt_chkROSubmit) == 0)	
				{
					
					$sql_insertRO = "INSERT INTO release_order_record(imp_rot,bl_no,igm_dtl_id,igm_sup_dtl_id,agent_id,org_id,cnf_ain,
										cnf_lic_no,ro_sl,ro_year,ro_type,entry_by,entry_at,entry_ip)
									VALUES('$imp_rot','$bl_no','$dtlId','$supDtlId','$agent_id','$org_id','$ainNo','$licNo',
										'$nextSl','$roYr','$roType','$login_id',NOW(),'$ipaddr')";
					
					 $rslt_insertRo = $this->bm->dataInsertDB1($sql_insertRO);
					 if ($rslt_insertRo)
					 {	 
						$this->session->set_flashdata("success", "<div class='alert alert-success'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'><strong>Release Order Succesfully Submitted.</strong></font></div>", 3);  		
					}
					else
					{
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'><strong>Warning!Release Order Not Submitted Yet.Please Submit It</strong></font></div>", 3);
					}

					// $data['msg']=$msg;
				
				}
				else
				{
					// $msg = "<font color='red'>Duplicate found. Please try with another...</font>";
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<font size='4'><strong>Duplicate found. Please try with another...</strong></font></div>", 3);
				}
				
				redirect('ReleaseOrderController/roForm/', 'refresh');
				// $this->roForm($msg);

				//$this->releaseOrderView($bl_no,$imp_rot);
			}

		}
	}
	
	function roList()
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$org_id =$this->session->userdata('org_id');
	
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{			
			if($org_Type_id == 62)		// devonestop
			{
				$sqlBl = "SELECT release_order_record.id,agent_id,imp_rot,bl_no,agent_name,entry_by,entry_at,unit_no,ro_type,appraise_st,appraise_at,IFNULL(assigned_unit.updated_at,assigned_unit.created_at) AS unit_assign_at,
				IFNULL((SELECT cont_status
				FROM  igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE igm_supplimentary_detail.Import_Rotation_No = release_order_record.imp_rot AND igm_supplimentary_detail.BL_No = release_order_record.bl_no LIMIT 1 ),
				(SELECT cont_status
				FROM  igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No = release_order_record.imp_rot AND igm_details.BL_No = release_order_record.bl_no LIMIT 1)) AS contStatus
				FROM release_order_record
				LEFT JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=release_order_record.agent_id
				LEFT JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				LEFT JOIN assigned_unit ON assigned_unit.rotation = release_order_record.imp_rot
				Order BY release_order_record.id DESC";		// LIMIT 100
			}
			else if($org_Type_id == 2)						// c&f
			{
				$sqlBl = "SELECT release_order_record.id,agent_id,imp_rot,bl_no,agent_name,entry_by,entry_at,unit_no,ro_type,appraise_st,appraise_at,IFNULL(assigned_unit.updated_at,assigned_unit.created_at) AS unit_assign_at,
				IFNULL((SELECT cont_status
				FROM  igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE igm_supplimentary_detail.Import_Rotation_No = release_order_record.imp_rot AND igm_supplimentary_detail.BL_No = release_order_record.bl_no LIMIT 1 ),
				(SELECT cont_status
				FROM  igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No = release_order_record.imp_rot AND igm_details.BL_No = release_order_record.bl_no LIMIT 1)) AS contStatus
				FROM release_order_record
				LEFT JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=release_order_record.agent_id
				LEFT JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				LEFT JOIN assigned_unit ON assigned_unit.rotation = release_order_record.imp_rot
				WHERE release_order_record.org_id='$org_id'
				Order BY release_order_record.id DESC"; 	// LIMIT 100
			}
			else
			{
				$sqlBl = "SELECT release_order_record.id,agent_id,imp_rot,bl_no,agent_name,entry_by,entry_at,unit_no,ro_type,appraise_st,appraise_at,IFNULL(assigned_unit.updated_at,assigned_unit.created_at) AS unit_assign_at,
				IFNULL((SELECT cont_status
				FROM  igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE igm_supplimentary_detail.Import_Rotation_No = release_order_record.imp_rot AND igm_supplimentary_detail.BL_No = release_order_record.bl_no LIMIT 1 ),
				(SELECT cont_status
				FROM  igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No = release_order_record.imp_rot AND igm_details.BL_No = release_order_record.bl_no LIMIT 1)) AS contStatus
				FROM release_order_record
				LEFT JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=release_order_record.agent_id
				LEFT JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				LEFT JOIN assigned_unit ON assigned_unit.rotation = release_order_record.imp_rot
				Order BY release_order_record.id DESC";		// LIMIT 100
			}
			
			$rslt_sqlRelease = $this->bm->dataSelectDb1($sqlBl);
			
			$data['rslt_sqlRelease']=$rslt_sqlRelease;
			$data['org_Type_id']=$org_Type_id;
			$data['msg']="";
			$data['title']="RELEASE ORDER LIST";								
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('roList',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function roListNts()
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id = $this->session->userdata('org_Type_id');

	
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			if($org_Type_id == 62)		// devonestop
			{
				$sqlBl = "SELECT release_order_record.id,agent_id,imp_rot,bl_no,agent_name,entry_by,entry_at,unit_no,ro_type,appraise_st,appraise_at,IFNULL(assigned_unit.updated_at,assigned_unit.created_at) AS unit_assign_at,
				IFNULL((SELECT cont_status
				FROM  igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE igm_supplimentary_detail.Import_Rotation_No = release_order_record.imp_rot AND igm_supplimentary_detail.BL_No = release_order_record.bl_no LIMIT 1 ),
				(SELECT cont_status
				FROM  igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No = release_order_record.imp_rot AND igm_details.BL_No = release_order_record.bl_no LIMIT 1)) AS contStatus
				FROM release_order_record
				INNER JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=release_order_record.agent_id
				INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				LEFT JOIN assigned_unit ON assigned_unit.rotation = release_order_record.imp_rot
				Order BY release_order_record.id DESC LIMIT 100";
			}
			else						// c&f
			{
				$sqlBl = "SELECT release_order_record.id,agent_id,imp_rot,bl_no,agent_name,entry_by,entry_at,unit_no,ro_type,appraise_st,appraise_at,IFNULL(assigned_unit.updated_at,assigned_unit.created_at) AS unit_assign_at,
				IFNULL((SELECT cont_status
				FROM  igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE igm_supplimentary_detail.Import_Rotation_No = release_order_record.imp_rot AND igm_supplimentary_detail.BL_No = release_order_record.bl_no LIMIT 1 ),
				(SELECT cont_status
				FROM  igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No = release_order_record.imp_rot AND igm_details.BL_No = release_order_record.bl_no LIMIT 1)) AS contStatus
				FROM release_order_record
				INNER JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=release_order_record.agent_id
				INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				LEFT JOIN assigned_unit ON assigned_unit.rotation = release_order_record.imp_rot
				WHERE release_order_record.entry_by='$login_id'
				Order BY release_order_record.id DESC LIMIT 100";
			}
			
			$rslt_sqlRelease = $this->bm->dataSelectDb1($sqlBl);
			
			$data['rslt_sqlRelease']=$rslt_sqlRelease;
			$data['org_Type_id']=$org_Type_id;
			$data['msg']="";
			$data['title']="RELEASE ORDER LIST";								
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('roListNts',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function lclAssignmentCertifyList($imp_rot=null,$bl_no=null)
	{			
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$org_id = $this->session->userdata('org_id');
		$data['org_Type_id']=$org_Type_id;
		$data['flag'] = 1;
		
		if($LoginStat!="yes")
		{
			$this->logout();
			
		}
		else
		{
			$action = '';
			$msg="";
			$title="";

			if($this->input->post('action')){
				$action = $this->input->post('action');
			}
			
			$data['action'] = $action;
			
			$pdfView=$this->input->post('pdfView');
			
			if($imp_rot=="" and $bl_no=="")
			{
				$ddl_imp_rot_no=trim($this->input->post('ddl_imp_rot_no'));
				$ddl_bl_no=trim($this->input->post('ddl_bl_no'));			
				$bill_en_no=trim($this->input->post('bill_en_no'));			
			}
			else
			{
				$ddl_imp_rot_no=$imp_rot;
				$ddl_bl_no=$bl_no;		
			}
			/* echo $ddl_imp_rot_no;
			echo "<br>";
			echo $ddl_bl_no; */
			//return;
			// chk exists ---
			$chkExist = 0;
			
			$igm_id = "";
			$sqlIgmId="SELECT igm_details.id AS igmID FROM igm_details 
				WHERE igm_details.Import_Rotation_No='$ddl_imp_rot_no' AND igm_details.BL_No='$ddl_bl_no'
				UNION
				SELECT igm_supplimentary_detail.id AS igmID FROM igm_supplimentary_detail 
				WHERE igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no'
				AND igm_supplimentary_detail.BL_No='$ddl_bl_no'";
			$rslt_sqlIgmId = $this->bm->dataSelectDb1($sqlIgmId);
			for($x=0;$x<count($rslt_sqlIgmId);$x++)
				{
					$igm_id=$rslt_sqlIgmId[$x]['igmID'];	
				}
			$data['igm_id'] = $igm_id;
			
			
			$sql_chkExist="SELECT COUNT(*) AS rtnValue
			FROM igm_details
			WHERE Import_Rotation_No='$ddl_imp_rot_no' AND BL_No='$ddl_bl_no'";
			// return;
			$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
			$chkExist = $rslt_chkExist[0]['rtnValue'];
			
			if($chkExist==0)
			{
				$sql_chkExist="SELECT COUNT(*) AS rtnValue FROM(SELECT cont_status
				FROM  igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no' AND igm_supplimentary_detail.BL_No='$ddl_bl_no'

				UNION

				SELECT cont_status
				FROM  igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$ddl_imp_rot_no' AND igm_details.BL_No='$ddl_bl_no') AS tbl";
				$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
				$chkExist = $rslt_chkExist[0]['rtnValue'];
			}
			// echo $sql_chkExist; return;
			if($chkExist>0)
			{
				$contStatus = "";

				$sql_contStatus="SELECT cont_status,Submitee_Org_Id
				FROM  igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no' AND igm_supplimentary_detail.BL_No='$ddl_bl_no'

				UNION

				SELECT cont_status,Submitee_Org_Id
				FROM  igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$ddl_imp_rot_no' AND igm_details.BL_No='$ddl_bl_no'";
									
				$rsltContStatus=$this->bm->dataSelectDb1($sql_contStatus);					
				
				$contStatus = $rsltContStatus[0]['cont_status'];
				$Submitee_Org_Id = $rsltContStatus[0]['Submitee_Org_Id'];

				/* if($org_Type_id == 4 && $org_id != $Submitee_Org_Id){
					$data['canFFview'] = 'no';wr_upto_date
				} */
				
				$sqlContainer = "";
				
				$manif_num = str_replace("/"," ",$ddl_imp_rot_no);
	
				
				$sqlContainer = "SELECT igm_supplimentary_detail.id,IFNULL(SUM(rcv_pack)+SUM(loc_first),0) AS rcv_pack,
				igm_sup_detail_container.cont_number,igm_supplimentary_detail.Import_Rotation_No,Vessel_Name,
				igm_supplimentary_detail.Pack_Marks_Number,shed_loc,shed_yard, igm_supplimentary_detail.Description_of_Goods,
				igm_supplimentary_detail.ConsigneeDesc, igm_supplimentary_detail.NotifyDesc,cont_size,
				cont_weight, igm_supplimentary_detail.weight,  CEILING (igm_supplimentary_detail.weight/1000) AS updat_tonage, cont_seal_number,igm_sup_detail_container.cont_status,cont_height,cont_iso_type,
				IFNULL(shed_tally_info.verify_number,0) AS verify_number, 
				IF(shed_mlo_do_info.valid_upto_dt IS NULL OR shed_mlo_do_info.valid_upto_dt='0000-00-00',shed_tally_info.wr_upto_date,
				shed_mlo_do_info.valid_upto_dt)
				AS wr_upto_date,
				shed_tally_info.wr_date AS ustuffing_dt, shed_tally_info.verify_by,shed_tally_info.verify_time, IFNULL(shed_tally_info.id,0) AS verify_id,off_dock_id, 
				IF(shed_mlo_do_info.be_no IS NULL OR shed_mlo_do_info.be_no=' ',
				IF(certify_info_fcl.be_no IS NULL OR certify_info_fcl.be_no=' ',verify_other_data.be_no,certify_info_fcl.be_no),
				shed_mlo_do_info.be_no) AS be_no, 
				IF(shed_mlo_do_info.be_date IS NULL OR shed_mlo_do_info.be_date='0000-00-00' OR shed_mlo_do_info.be_date=' ',
				IF(certify_info_fcl.be_date IS NULL OR certify_info_fcl.be_date='0000-00-00' OR certify_info_fcl.be_date=' ',
				verify_other_data.be_date,certify_info_fcl.be_date),
				shed_mlo_do_info.be_date) AS be_date, 
				(SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_sup_detail_container.off_dock_id) AS offdock_name, organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no, shed_mlo_do_info.id AS agent_do, DATE(shed_mlo_do_info.upload_time) AS do_date,
				IFNULL(IFNULL(verify_other_data.update_ton,certify_info_fcl.update_ton), CEILING (igm_supplimentary_detail.weight/1000)) AS update_ton,COUNT(edo_application_by_cf.id) AS edo_done,(SELECT unit_no FROM assigned_unit WHERE rotation = '$ddl_imp_rot_no') AS unit
				FROM igm_supplimentary_detail 
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id 
				LEFT JOIN edo_application_by_cf ON igm_supplimentary_detail.Import_Rotation_No=edo_application_by_cf.rotation AND igm_supplimentary_detail.BL_No=edo_application_by_cf.bl 
				LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by 
				LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id 
				LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id 
				LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.bl_no=edo_application_by_cf.bl AND shed_mlo_do_info.imp_rot=edo_application_by_cf.rotation
				LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
				LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				LEFT JOIN certify_info_fcl ON igm_details.id=certify_info_fcl.igm_detail_id OR igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id 
				WHERE igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no' AND igm_supplimentary_detail.BL_No='$ddl_bl_no' GROUP BY igm_sup_detail_container.id

				UNION ALL

				SELECT igm_details.id,'' AS rcv_pack,igm_detail_container.cont_number,igm_details.Import_Rotation_No,Vessel_Name,Pack_Marks_Number,'' AS shed_loc,'' AS shed_yard,Description_of_Goods, ConsigneeDesc,NotifyDesc,cont_size,cont_weight, igm_details.weight,  CEILING (igm_details.weight/1000) AS updat_tonage,  cont_seal_number, igm_detail_container.cont_status, cont_height,cont_iso_type,
				verify_number, 
				IF(shed_mlo_do_info.valid_upto_dt IS NULL OR shed_mlo_do_info.valid_upto_dt='0000-00-00',certify_info_fcl.wr_upto_date,
				shed_mlo_do_info.valid_upto_dt)
				AS wr_upto_date,
				certify_info_fcl.wr_upto_date AS ustuffing_dt,
				verify_by,verify_time,IFNULL(certify_info_fcl.id,0) AS verify_id,
				off_dock_id,
				IFNULL(shed_mlo_do_info.be_no,(SELECT reg_no
				FROM sad_info
				INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
				WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$ddl_bl_no' LIMIT 1)) AS be_no,
				IFNULL(shed_mlo_do_info.be_date,(SELECT reg_date
				FROM sad_info
				INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
				WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$ddl_bl_no' LIMIT 1)) AS be_date,

				(SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_detail_container.off_dock_id) AS offdock_name,
				organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no,

				shed_mlo_do_info.id AS do_no, DATE(shed_mlo_do_info.upload_time) AS do_date,
				IFNULL(certify_info_fcl.update_ton, CEILING (igm_details.weight/1000)) AS update_ton,COUNT(edo_application_by_cf.id) AS edo_done,(SELECT unit_no FROM assigned_unit WHERE rotation = '$ddl_imp_rot_no') AS unit

				FROM  igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
				LEFT JOIN edo_application_by_cf ON igm_details.Import_Rotation_No=edo_application_by_cf.rotation AND igm_details.BL_No=edo_application_by_cf.bl
				LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by
				LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id
				LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
				LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
				LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$ddl_imp_rot_no' AND igm_details.BL_No='$ddl_bl_no'
				GROUP BY igm_detail_container.id";
				// return;
				
				$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);

				//--//2020-03-10 
				$containerNo="";
				
				for($i=0;$i<count($rtnContainerList);$i++)
				{
					$containerNo_tmp=$rtnContainerList[$i]['cont_number'];				
					$containerNo=$containerNo."'".$containerNo_tmp."',";
				}
				
				$containerNo=rtrim($containerNo,",");
				//--//2020-03-10 
				
				
				$verify_id=$rtnContainerList[0]['verify_id'];
				$verify_num=$rtnContainerList[0]['verify_number'];
				
				$cnf_lic_no=$rtnContainerList[0]['cnf_lic_no'];
				/* $lic_p1 = substr($rtnContainerList[0]['cnf_lic_no'],5);			// 4356
				$lic_p2 = substr($rtnContainerList[0]['cnf_lic_no'],3,2);
				$cnf_lic_no = $lic_p1."/".$lic_p2; */

				$cnf_name = "";

				if($cnf_lic_no!=null)
				{
					$cnf_licQuery="SELECT name as rtnValue from ref_bizunit_scoped where id='$cnf_lic_no'";
					$rtnCnfName = $this->bm->dataSelect($cnf_licQuery);
					$cnf_name=$rtnCnfName;
				}		
				// $strID = "select count(*) as rtnValue from sparcsn4.inv_unit
				// inner join sparcsn4.srv_event on sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey
				// where id='$containerNo' and  category='IMPRT' and event_type_gkey=30";
				
				//2020-03-10 
				// $strID = "SELECT COUNT(*) AS rtnValue FROM sparcsn4.inv_unit
				// INNER JOIN sparcsn4.srv_event ON sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey
				// WHERE id IN($containerNo) AND category='IMPRT' AND event_type_gkey=30";
				// $rtnValue = $this->bm->dataReturn($strID);

				$strID="";
				$rtnValue="";
				$chkcertified = "";

				if($contStatus=="FCL")
				{
					
					$strID = "SELECT COUNT(*) AS rtnValue 
					FROM inv_unit
					INNER JOIN srv_event ON srv_event.applied_to_gkey=inv_unit.gkey 
					WHERE id IN($containerNo) AND category='IMPRT' AND event_type_gkey=30";
					
					//$rtnValue = $this->bm->dataReturn($strID);
					$rtnValue = 1;

					//Check if already certified    -- 2021-03-09
					$chkcertify = "SELECT COUNT(certify_info_fcl.id) AS rtnValue
					FROM certify_info_fcl 
					INNER JOIN igm_details ON certify_info_fcl.igm_detail_id=igm_details.id
					WHERE igm_details.Import_Rotation_No='$ddl_imp_rot_no' AND igm_details.BL_No='$ddl_bl_no'";
					$chkcertified = $this->bm->dataReturnDb1($chkcertify);

					if($chkcertified == 0){
							$chkcertify = "SELECT COUNT(certify_info_fcl.id) AS rtnValue
						FROM certify_info_fcl 
						INNER JOIN igm_supplimentary_detail ON certify_info_fcl.igm_sup_detail_id=igm_supplimentary_detail.id
						WHERE igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no' AND igm_supplimentary_detail.BL_No='$ddl_bl_no'";
						$chkcertified = $this->bm->dataReturnDb1($chkcertify);
					}
					
				}
				else
				{
					// $strID = "SELECT COUNT(*) AS rtnValue
					// FROM shed_tally_info WHERE import_rotation='$ddl_imp_rot_no' AND cont_number IN($containerNo)";
						$strID = "SELECT COUNT(*) AS rtnValue
					FROM shed_tally_info WHERE import_rotation='$ddl_imp_rot_no' AND cont_number IN($containerNo) 
					AND (igm_sup_detail_id='$igm_id' OR igm_detail_id='$igm_id')";
					$rtnValue = $this->bm->dataReturnDb1($strID);

					//Check if already certified    -- 2021-03-09
			
					$chkcertify = "SELECT COUNT(verify_other_data.id) AS rtnValue
					FROM verify_other_data 
					INNER JOIN shed_tally_info ON verify_other_data.shed_tally_id=shed_tally_info.id
					INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
					WHERE igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no' AND igm_supplimentary_detail.BL_No='$ddl_bl_no'";
					$chkcertified = $this->bm->dataReturnDb1($chkcertify);
				}				
			
				$data['certify'] = $chkcertified;
				
				if($rtnValue==0)
				{
						$msg="<font color='red'><b>CARGO IS NOT UNSTUFFED.</b></font>";		// uncomment later
					//$msg="rtn";		
				}
				else
				{
					
					if($contStatus=="FCL")
					{
						if($verify_id==0)
						{
							$msg="<font color='red'><b>NOT CERTIFIED YET</b></font>";
						}
					}
					else
					{
						$verifyid = "";
						$sqlVerifyFOrLCL="SELECT shed_tally_info.igm_sup_detail_id AS igmID,IFNULL(verify_other_data.id,0) AS vid 
						FROM igm_supplimentary_detail 
						INNER JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
						LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
						WHERE Import_Rotation_No='$ddl_imp_rot_no' AND BL_No='$ddl_bl_no'
						UNION
						SELECT shed_tally_info.igm_detail_id AS igmID,IFNULL(verify_other_data.id,0) AS vid FROM igm_details 
						INNER JOIN shed_tally_info ON shed_tally_info.igm_detail_id=igm_details.id
						LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
						WHERE Import_Rotation_No='$ddl_imp_rot_no' AND BL_No='$ddl_bl_no' ORDER BY vid ";
						$resVerifyId = $this->bm->dataSelectDb1($sqlVerifyFOrLCL);
						for($y=0;$y<count($resVerifyId);$y++)
							{
								$verifyid=$resVerifyId[$y]['vid'];		
							}
						if($verifyid==0)
						{
							$msg="<font color='red'><b>NOT CERTIFIED YET</b></font>";
						}
						else
						{
							$msg="<font color='blue'><b>ALREADY CERTIFIED.</b></font>";
						}
						
					}
				}

				if($pdfView=="pdfView")
				{			
					$position_data=$this->input->post('position_data');
					$yard_data=$this->input->post('yard_data');
					
					// $this->load->library('m_pdf');
					// $mpdf->use_kwt = true;
					
					// $this->data['rtnContainerList']=$rtnContainerList;
					// $this->data['cnf_lic_no'] = $cnf_lic_no;
					// $this->data['unstuff_flag']=$rtnValue;
					// $this->data['verify_id']=$verify_id;			// certify_id in case of FCL, shed_tally_id in case of LCL
					// $this->data['verify_num']=$verify_num;
					// $this->data['cnf_name']=$cnf_name;
					// $this->data['ddl_imp_rot_no']=$ddl_imp_rot_no;
					// $this->data['ddl_bl_no']=$ddl_bl_no;
					// $this->data['verify_number']="";					
					// $this->data['contStatus']=$contStatus;
					// $this->data['position_data']=$position_data;
					// $this->data['yard_data']=$yard_data;

					$data['rtnContainerList']=$rtnContainerList;
					$data['cnf_lic_no'] = $cnf_lic_no;
					$data['unstuff_flag']=$rtnValue;
					$data['verify_id']=$verify_id;			// certify_id in case of FCL, shed_tally_id in case of LCL
					$data['verify_num']=$verify_num;
					$data['cnf_name']=$cnf_name;
					$data['ddl_imp_rot_no']=$ddl_imp_rot_no;
					$data['ddl_bl_no']=$ddl_bl_no;
					$data['verify_number']="";					
					$data['contStatus']=$contStatus;
					$data['position_data']=$position_data;
					$data['yard_data']=$yard_data;
					
					// $html=$this->load->view('certificationListPdfOutput',$this->data, true);

					$this->load->view('certificationListPdfOutput',$data);
					
					//$pdfFilePath ="certificationPdfOutput-".time()."-download.pdf";
					
					//$pdf = $this->m_pdf->load();
					////$pdf->SetWatermarkText('CPA CTMS');
					//$pdf->showWatermarkText = true;	
					
					// $pdf->useSubstitutions = true;
					
					//$pdf->WriteHTML($html,2);
					
					//$pdf->Output($pdfFilePath, "I"); // For Show Pdf
					
				}
				else
				{
					$ustuffing_dt="";				
					$wr_upto_date="";				
					for($a=0;$a<count($rtnContainerList);$a++)
					{
						$ustuffing_dt=$rtnContainerList[$a]['ustuffing_dt'];
						$wr_upto_date=$rtnContainerList[$a]['wr_upto_date'];
					}
					
					$data['rtnContainerList']=$rtnContainerList;
					$data['cnf_lic_no'] = $cnf_lic_no;
					$data['unstuff_flag']=$rtnValue;
					$data['verify_id']=$verify_id;			// certify_id in case of FCL, shed_tally_id in case of LCL
					$data['verify_num']=$verify_num;
					$data['cnf_name']=$cnf_name;
					$data['ddl_imp_rot_no']=$ddl_imp_rot_no;
					$data['ddl_bl_no']=$ddl_bl_no;
					$data['ustuffing_dt']=$ustuffing_dt;
					$data['wr_upto_date']=$wr_upto_date;
					$data['verify_number']="";
					
					$data['contStatus']=$contStatus;
					
					$data['msg']=$msg;
					$data['title']="ASSINGMENT CERTIFY SECTION...";					
					
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('lclAssignmentCertifySectionHTML',$data);
					$this->load->view('jsAssets');
				}
			}
			else		// if not exist
			{					
				$data['unstuff_flag']=0;
				if($bill_en_no!="")
					$data['msg']="<font color='red'><b>THE INFORMATION OF B/E IS NOT FOUND.</b></font>";
				else
					$data['msg']="<font color='red'><b>ROTATION AND BL ARE NOT MATCHED</b></font>";
					
				$data['title']="ASSINGMENT CERTIFY SECTION...";	
				$data['flag'] = 0;				
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('lclAssignmentCertifySectionHTML',$data);
				$this->load->view('jsAssets');
			}				
		}
	}

	function releaseOrderViewForm()
	{
		$data['msg']="";
		$data['title']="RELEASE ORDER VIEW FORM";								
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('roFormView',$data);
		$this->load->view('jsAssets');
	}
		
	function releaseOrderView($bl_no = null, $imp_rot = null)
	{
		if(is_null($bl_no) && is_null($imp_rot))
		{
			$imp_rot = $this->input->post('imp_rot');
			$bl_no = $this->input->post('bl_no');
		}
		else
		{
			$imp_rot = str_replace("_","/",$imp_rot);
		}

		// check if exits
		$sql_chkRO = "SELECT COUNT(*) AS rtnValue FROM release_order_record WHERE imp_rot='$imp_rot' AND bl_no='$bl_no'";
		$chkRO = $this->bm->dataReturnDB1($sql_chkRO);
		
		if($chkRO == 0)
		{
			$data['msg']="<font color='red'>Release order not submitted yet by C&F.</font>";
			$data['title']="RELEASE ORDER VIEW FORM";								
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('roFormView',$data);
			$this->load->view('jsAssets');
			return;
		} 
		
		$sql_submitBy = "SELECT entry_by AS rtnValue FROM release_order_record WHERE imp_rot='$imp_rot' AND bl_no='$bl_no'";
		$submitBy = $this->bm->dataReturnDB1($sql_submitBy);
		$data['submitBy']=$submitBy;
		
		// cont status
		$sql_contStatus = "SELECT cont_status
		FROM igm_supplimentary_detail
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'
		LIMIT 1
		UNION ALL
		SELECT cont_status
		FROM igm_details
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
		WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'
		LIMIT 1";
		$rslt_contStatus = $this->bm->dataSelectDB1($sql_contStatus);	

		$contStatus = "";
		
		for($i=0;$i<count($rslt_contStatus);$i++)
		{
			$contStatus = $rslt_contStatus[$i]['cont_status'];
		}
		
		if($contStatus == "")
		{			
			$data['msg']="<font color='red'>Container status not found.</font>";
			$data['title']="RELEASE ORDER VIEW FORM";								
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('roFormView',$data);
			$this->load->view('jsAssets');
			return;
		}
		$data['contStatus']=$contStatus;
		// cont status - end
		
		//exit note no
		$sql_exitNoteNo = "SELECT place_dec AS exitNoteNo
		FROM sad_info
		INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
		WHERE REPLACE(manif_num,' ','') LIKE CONCAT('%',REPLACE('$imp_rot','/',''),'%') AND sum_declare='$bl_no'";
		$rslt_exitNoteNo = $this->bm->dataSelectDB1($sql_exitNoteNo);
		
		$exitNoteNo = "";
		for($i=0;$i<count($rslt_exitNoteNo);$i++)
		{
			$exitNoteNo = $rslt_exitNoteNo[$i]['exitNoteNo'];
		}
		
		// C&F Information
		$sql_entryBy = "SELECT entry_by AS rtnValue
		FROM release_order_record
		WHERE imp_rot='$imp_rot' AND bl_no='$bl_no'";
		$entryBy = $this->bm->dataReturnDB1($sql_entryBy);
		
		// $sql_cnfInfo = "SELECT Organization_Name,License_No
		// FROM organization_profiles
		// INNER JOIN users ON users.org_id = organization_profiles.id
		// WHERE login_id='$entryBy'";

		$sql_cnfInfo = "SELECT organization_profiles.Organization_Name, organization_profiles.License_No
		FROM igm_supplimentary_detail 
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
		LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id 
		LEFT JOIN edo_application_by_cf ON igm_supplimentary_detail.Import_Rotation_No=edo_application_by_cf.rotation AND igm_supplimentary_detail.BL_No=edo_application_by_cf.bl 
		LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by 
		LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id 
		LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id 
		LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.bl_no=edo_application_by_cf.bl AND shed_mlo_do_info.imp_rot=edo_application_by_cf.rotation 
		LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
		LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id 
		LEFT JOIN certify_info_fcl ON igm_details.id=certify_info_fcl.igm_detail_id OR igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no' GROUP BY igm_sup_detail_container.id 

		UNION ALL 

		SELECT organization_profiles.Organization_Name, organization_profiles.License_No
		FROM igm_details 
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
		LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
		LEFT JOIN edo_application_by_cf ON igm_details.Import_Rotation_No=edo_application_by_cf.rotation AND igm_details.BL_No=edo_application_by_cf.bl LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by 
		LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id 
		LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id 
		LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id 
		LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id 
		WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no' GROUP BY igm_detail_container.id";

		$rslt_cnfInfo = $this->bm->dataSelectDB1($sql_cnfInfo);
		
		$cnfName = "";
		$cnfLic = "";
		for($i=0;$i<count($rslt_cnfInfo);$i++)
		{
			$cnfName = $rslt_cnfInfo[$i]['Organization_Name'];
			$cnfLic = $rslt_cnfInfo[$i]['License_No'];
		}

		$data['cnfName'] = $cnfName;
		$data['cnfLic'] = $cnfLic;

		
		// pro
		$sql_pro = "SELECT CONCAT(cnf_lic_no,ro_year,ro_sl) AS pro,agent_id
					FROM(
					SELECT REPLACE(cnf_lic_no,'/','') AS cnf_lic_no,ro_year,
					(CASE
						WHEN LENGTH(ro_sl)=1 THEN CONCAT('0000',ro_sl)
						WHEN LENGTH(ro_sl)=2 THEN CONCAT('000',ro_sl)
						WHEN LENGTH(ro_sl)=3 THEN CONCAT('00',ro_sl)
						WHEN LENGTH(ro_sl)=4 THEN CONCAT('0',ro_sl)
						WHEN LENGTH(ro_sl)=5 THEN ro_sl
					END) AS ro_sl,agent_id
					FROM release_order_record
					WHERE imp_rot='$imp_rot' AND bl_no='$bl_no') AS tbl";
		$rslt_pro = $this->bm->dataSelectDB1($sql_pro);
		
		$pro = "";
		$agent_id = "";
		
		for($i=0;$i<count($rslt_pro);$i++)
		{
			$pro = $rslt_pro[$i]['pro'];
			$agent_id = $rslt_pro[$i]['agent_id'];
		}

		$agent_name_query = "SELECT agent_name,nid_number FROM vcms_vehicle_agent WHERE id = '$agent_id'";
		$agent_name_rslt = $this->bm->dataSelectDB1($agent_name_query);
		
		$data['bl_no']=$bl_no;
		$data['exitNoteNo']=$exitNoteNo;
		// $data['verifyNo']="";
		$data['billNo']="";	
		$data['pro']=$pro;
		$data['agent_id']=$agent_id;
		$data['agent_name_rslt']=$agent_name_rslt;

		
		$sql_headerData = "SELECT igm_supplimentary_detail.id,Vessel_Name,igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.BL_No,Notify_name,Bill_of_Entry_No,Bill_of_Entry_Date,
		(SELECT verify_no 
		FROM oracle_nts_data
		WHERE imp_rot_no=igm_supplimentary_detail.Import_Rotation_No AND bl_no=igm_supplimentary_detail.BL_No) AS verify_no,
		(SELECT cp_no
		FROM oracle_nts_data
		WHERE imp_rot_no=igm_supplimentary_detail.Import_Rotation_No AND bl_no=igm_supplimentary_detail.BL_No) AS cp_no,
		(SELECT DATE(cp_date)
		FROM oracle_nts_data
		WHERE imp_rot_no=igm_supplimentary_detail.Import_Rotation_No AND bl_no=igm_supplimentary_detail.BL_No) AS cp_date
		FROM igm_supplimentary_detail
		INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
		$rslt_headerData = $this->bm->dataSelectDB1($sql_headerData);
		
		if(count($rslt_headerData)==0)
		{
			$sql_headerData = "SELECT igm_details.id,Vessel_Name,igm_details.Import_Rotation_No,igm_details.BL_No,Notify_name,Bill_of_Entry_No,Bill_of_Entry_Date,
			(SELECT verify_no 
			FROM oracle_nts_data
			WHERE imp_rot_no=igm_details.Import_Rotation_No AND bl_no=igm_details.BL_No ORDER BY verify_no DESC LIMIT 1) AS verify_no,
			(SELECT cp_no 
			FROM oracle_nts_data
			WHERE imp_rot_no=igm_details.Import_Rotation_No AND bl_no=igm_details.BL_No ORDER BY cp_no DESC LIMIT 1) AS cp_no,
			(SELECT DATE(cp_date) 
			FROM oracle_nts_data
			WHERE imp_rot_no=igm_details.Import_Rotation_No AND bl_no=igm_details.BL_No ORDER BY cp_date DESC LIMIT 1) AS cp_date
			FROM igm_details
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'";
			$rslt_headerData = $this->bm->dataSelectDB1($sql_headerData);
		}
		//echo $sql_headerData;
		// return;
		$data['rslt_headerData']=$rslt_headerData;
		
		$sql_roData = "SELECT Pack_Marks_Number,Pack_Number,Description_of_Goods,Pack_Description,igm_supplimentary_detail.Volume_in_cubic_meters,
		Notify_name,Notify_code,Notify_address,CONCAT(Notify_name,'<br>',Notify_code) AS signAndLic,
		Voy_No,Vessel_Name,igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.Line_No,Bill_of_Entry_No AS be_no,Bill_of_Entry_Date AS be_date,
		shed_loc,shed_yard,igm_sup_detail_container.cont_number,cont_size,cont_height,cont_status,cont_iso_type,
		-- shed_mlo_do_info.delv_quantity,
		CONCAT(Pack_Number,' ',Pack_Description) AS delv_quantity,
		-- SUM(cont_weight) AS weight,
		'' AS gate_out_time,off_dock_id
		FROM igm_supplimentary_detail
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id		
		LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
		LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_supplimentary_detail.id		
		WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
		// echo $sql_roData;
		// return;
		$rslt_roData = $this->bm->dataSelectDB1($sql_roData);
		
		// if(count($rslt_roData) == 0)		// wrong result
		// if($rslt_roData[0]['Import_Rotation_No'] == null)
		
		// weight - start
		$weight = "";
		if(count($rslt_roData)==0)
		{
			$sql_roData = "SELECT Pack_Marks_Number,Pack_Number,Description_of_Goods,Pack_Description,igm_details.Volume_in_cubic_meters,Notify_name,Notify_code,Notify_address,CONCAT(Notify_name,'<br>',Notify_code) AS signAndLic,Voy_No,Vessel_Name,igm_details.Import_Rotation_No,igm_details.Line_No,Bill_of_Entry_No AS be_no,Bill_of_Entry_Date AS be_date,'' AS shed_loc,'' AS shed_yard,igm_detail_container.cont_number,cont_size,cont_height,cont_status,cont_iso_type,
			-- shed_mlo_do_info.delv_quantity,
			CONCAT(Pack_Number,' ',Pack_Description) AS delv_quantity,
			-- SUM(cont_weight) AS weight,
			'' AS gate_out_time,off_dock_id
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'";
			$rslt_roData = $this->bm->dataSelectDB1($sql_roData);
			
			$sql_weight = "SELECT SUM(igm_details.weight) AS rtnValue
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'";
			$weight = $this->bm->dataReturnDB1($sql_weight);
		}
		else
		{
			$sql_weight = "SELECT SUM(igm_supplimentary_detail.weight) AS rtnValue
			FROM igm_supplimentary_detail
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id		
			LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_supplimentary_detail.id		
			WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
			$weight = $this->bm->dataReturnDB1($sql_weight);
		}
		// echo $sql_roData;
		
		$data['weight']=$weight;
		$data['rslt_roData']=$rslt_roData;
		// weight - end
		
		// bill
		$strBillRcvInfo="select description,gl_code 
		from shed_bill_details 
		inner join shed_bill_master on shed_bill_master.bill_no=shed_bill_details.bill_no
		where shed_bill_master.verify_no='".$rslt_headerData[0]['verify_no']."'";							
		$rtnBillRcvInfo = $this->bm->dataSelectDb1($strBillRcvInfo);
		// return;	

		$data['rtnBillRcvInfo']=$rtnBillRcvInfo;
		
		$rtnBillQuery="select concat(right(YEAR(bill_date),2),'/',
		concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',
		if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,user,entry_dt,bill_date
		from shed_bill_master 
		where verify_no='".$rslt_headerData[0]['verify_no']."'";
		$rtnBillNo = $this->bm->dataSelectDb1($rtnBillQuery);
		
		$billNo = "";
		$billPreparedBy = "";
		$billPreparedTime = "";
		$billDate = "";
		for($i=0;$i<count($rtnBillNo);$i++)
		{
			$billNo = $rtnBillNo[$i]['bill_no'];
			$billDate = $rtnBillNo[$i]['bill_date'];
			$billPreparedBy = $rtnBillNo[$i]['user'];
			$billPreparedTime = $rtnBillNo[$i]['entry_dt'];
		}
		$data['billNo']=$billNo;
		$data['bill_date']=$billDate;
		$data['billPreparedBy']=$billPreparedBy;
		$data['billPreparedTime']=$billPreparedTime;
		
		// cp no - start
		$sql_billSl = "SELECT bill_no 
		FROM shed_bill_master
		WHERE import_rotation='$imp_rot' AND bl_no='$bl_no'";
		// echo $sql_billSl;return;
		$billSlList = $this->bm->dataSelectDB1($sql_billSl);
		$billSl = "";
		for($b=0;$b<count($billSlList);$b++)
		{
			$billSl = $billSlList[$b]["bill_no"];
		}
		
		$sql_cpNo = "SELECT CONCAT(tmp,'-',cp_no) as cpNo,recv_time AS cpDate
		FROM(
		SELECT CONCAT(cp_bank_code,cp_unit,'/',RIGHT(cp_year,2)) AS tmp,
		CASE
		WHEN LENGTH(cp_no)=1 THEN CONCAT('000',cp_no)
		WHEN LENGTH(cp_no)=2 THEN CONCAT('00',cp_no)
		WHEN LENGTH(cp_no)=3 THEN CONCAT('0',cp_no)
		WHEN LENGTH(cp_no)=4 THEN cp_no
		ELSE ''
		END AS cp_no,recv_time
		FROM bank_bill_recv WHERE bill_no='$billSl') AS tbl";
		$rslt_cpNo = $this->bm->dataSelectDB1($sql_cpNo);
		
		$cpNo = "";
		$cpDate = "";
		for($i=0;$i<count($rslt_cpNo);$i++)
		{
			$cpNo = $rslt_cpNo[$i]['cpNo'];
			$cpDate = $rslt_cpNo[$i]['cpDate'];
		}
		
		$data['cpNo']=$cpNo;
		$data['cpDate']=$cpDate;
		// cp no - end
					
		$str="select concat(right(YEAR(bill_date),2),'/',concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,arraival_date,import_rotation,vessel_name,cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,be_no,be_date,ado_no,ado_date,ado_valid_upto,manifest_qty,cont_size,cont_height,bill_rcv_stat,if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status 
		from shed_bill_master where verify_no='".$rslt_headerData[0]['verify_no']."'"; 
			
		$rtnBillList = $this->bm->dataSelectDb1($str);
		$data['rtnBillList']=$rtnBillList;
						
		// certify by - start
		$certifyBy = "";
		$sql_certifyBy = "SELECT certify_info_fcl.update_by
		FROM certify_info_fcl
		INNER JOIN igm_details ON igm_details.id=certify_info_fcl.igm_detail_id
		WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'";
		$rslt_certifyBy = $this->bm->dataSelectDB1($sql_certifyBy);
		
		for($i=0;$i<count($rslt_certifyBy);$i++)
		{
			$certifyBy = $rslt_certifyBy[$i]['update_by'];							
		}
		
		if($certifyBy=="")
		{
			$sql_certifyBy = "SELECT verify_other_data.update_by
			FROM verify_other_data
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=verify_other_data.igm_sup_detail_id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
			$rslt_certifyBy = $this->bm->dataSelectDB1($sql_certifyBy);
			
			for($i=0;$i<count($rslt_certifyBy);$i++)
			{
				$certifyBy = $rslt_certifyBy[$i]['update_by'];							
			}
		}
		$data['certifyBy']=$certifyBy;
		// certify by - end 
		
		// verify by - start
		$verifyBy = "";
		$verifyTime = "";
		$sql_verifyBy = "SELECT verify_info_fcl.verify_by,DATE(verify_info_fcl.verify_time) AS verify_time
		FROM verify_info_fcl
		INNER JOIN igm_details ON igm_details.id=verify_info_fcl.igm_detail_id
		WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'
		LIMIT 1";
		$rslt_verifyBy = $this->bm->dataSelectDB1($sql_verifyBy);
		
		for($i=0;$i<count($rslt_verifyBy);$i++)
		{
			$verifyBy = $rslt_verifyBy[$i]['verify_by'];							
			$verifyTime = $rslt_verifyBy[$i]['verify_time'];							
		}
		
		if($verifyBy == "")
		{
			$sql_verifyBy = "SELECT shed_tally_info.verify_by,DATE(shed_tally_info.verify_time) AS verify_time
			FROM shed_tally_info
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
			$rslt_verifyBy = $this->bm->dataSelectDB1($sql_verifyBy);	

			for($i=0;$i<count($rslt_verifyBy);$i++)
			{
				$verifyBy = $rslt_verifyBy[$i]['verify_by'];							
				$verifyTime = $rslt_verifyBy[$i]['verify_time'];							
			}				
		}
		

		$data['imp_rot']=$imp_rot;
		$data['bl_no']=$bl_no;
		$data['verifyBy']=$verifyBy;
		$data['verifyTime']=$verifyTime;
		// verify by - end 
			
		$this->load->view('roFormViewPDF',$data);
		
		/*	// backup for pdf	- sample from vsl bill
		$this->data['bl_no']=$bl_no;
		$this->data['exitNoteNo']=$exitNoteNo;
		$this->data['verifyNo']="";
		$this->data['billNo']="";	
		$this->data['pro']=$pro;
		
		$this->load->library('m_pdf');
		$html=$this->load->view('roFormViewPDF.php',$this->data, true);			 
		
		$pdfFilePath ="releaseOrder-".$pro."-download.pdf";
		$pdf = $this->m_pdf->load();
		
		$pdf->AddPage('L', // L - landscape, P - portrait
					'', '', '', '',
					5, // margin_left
					5, // margin right
					5, // margin top
					5, // margin bottom
					5, // margin header
					5); // margin footer
					
		$stylesheet = file_get_contents('assets/stylesheets/test.css');				
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);				 
		$pdf->Output($pdfFilePath, "I");				
		*/
	}
	// release order - new code - end

	// Release Order View - TOS 

	function releaseOrderViewTos($bl_no = null, $imp_rot = null)
	{
		if(is_null($bl_no) && is_null($imp_rot))
		{
			$imp_rot = $this->input->post('imp_rot');
			$bl_no = $this->input->post('bl_no');
		}
		else
		{
			$imp_rot = str_replace("_","/",$imp_rot);
		}

		// check if exits
		$sql_chkRO = "SELECT COUNT(*) AS rtnValue FROM release_order_record WHERE imp_rot='$imp_rot' AND bl_no='$bl_no'";
		$chkRO = $this->bm->dataReturnDB1($sql_chkRO);
		
		if($chkRO == 0)
		{
			$data['msg']="<font color='red'>Release order not submitted yet by C&F.</font>";
			$data['title']="RELEASE ORDER VIEW FORM";								
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('roFormView',$data);
			$this->load->view('jsAssets');
			return;
		} 
		
		$sql_submitBy = "SELECT entry_by AS rtnValue FROM release_order_record WHERE imp_rot='$imp_rot' AND bl_no='$bl_no'";
		$submitBy = $this->bm->dataReturnDB1($sql_submitBy);
		$data['submitBy']=$submitBy;
		
		// cont status
		$sql_contStatus = "SELECT cont_status
		FROM igm_supplimentary_detail
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'
		LIMIT 1
		UNION ALL
		SELECT cont_status
		FROM igm_details
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
		WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'
		LIMIT 1";

		$rslt_contStatus = $this->bm->dataSelectDB1($sql_contStatus);	

		$contStatus = "";
		
		for($i=0;$i<count($rslt_contStatus);$i++)
		{
			$contStatus = $rslt_contStatus[$i]['cont_status'];
		}
		
		if($contStatus == "")
		{			
			$data['msg']="<font color='red'>Container status not found.</font>";
			$data['title']="RELEASE ORDER VIEW FORM";								
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('roFormView',$data);
			$this->load->view('jsAssets');
			return;
		}
		$data['contStatus']=$contStatus;
		// cont status - end
		
		//exit note no
		$sql_exitNoteNo = "SELECT place_dec AS exitNoteNo
		FROM sad_info
		INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
		WHERE REPLACE(manif_num,' ','') LIKE CONCAT('%',REPLACE('$imp_rot','/',''),'%') AND sum_declare='$bl_no'";
		$rslt_exitNoteNo = $this->bm->dataSelectDB1($sql_exitNoteNo);
		
		$exitNoteNo = "";
		for($i=0;$i<count($rslt_exitNoteNo);$i++)
		{
			$exitNoteNo = $rslt_exitNoteNo[$i]['exitNoteNo'];
		}
		
		// C&F Information
		$sql_entryBy = "SELECT entry_by AS rtnValue
		FROM release_order_record
		WHERE imp_rot='$imp_rot' AND bl_no='$bl_no'";
		$entryBy = $this->bm->dataReturnDB1($sql_entryBy);
		
		// $sql_cnfInfo = "SELECT Organization_Name,License_No
		// FROM organization_profiles
		// INNER JOIN users ON users.org_id = organization_profiles.id
		// WHERE login_id='$entryBy'";

		$sql_cnfInfo = "SELECT organization_profiles.Organization_Name, organization_profiles.License_No
		FROM igm_supplimentary_detail 
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
		LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id 
		LEFT JOIN edo_application_by_cf ON igm_supplimentary_detail.Import_Rotation_No=edo_application_by_cf.rotation AND igm_supplimentary_detail.BL_No=edo_application_by_cf.bl 
		LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by 
		LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id 
		LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id 
		LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.bl_no=edo_application_by_cf.bl AND shed_mlo_do_info.imp_rot=edo_application_by_cf.rotation 
		LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
		LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id 
		LEFT JOIN certify_info_fcl ON igm_details.id=certify_info_fcl.igm_detail_id OR igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no' GROUP BY igm_sup_detail_container.id 

		UNION ALL 

		SELECT organization_profiles.Organization_Name, organization_profiles.License_No
		FROM igm_details 
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
		LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
		LEFT JOIN edo_application_by_cf ON igm_details.Import_Rotation_No=edo_application_by_cf.rotation AND igm_details.BL_No=edo_application_by_cf.bl LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by 
		LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id 
		LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id 
		LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id 
		LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id 
		WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no' GROUP BY igm_detail_container.id";

		$rslt_cnfInfo = $this->bm->dataSelectDB1($sql_cnfInfo);
		
		$cnfName = "";
		$cnfLic = "";
		for($i=0;$i<count($rslt_cnfInfo);$i++)
		{
			$cnfName = $rslt_cnfInfo[$i]['Organization_Name'];
			$cnfLic = $rslt_cnfInfo[$i]['License_No'];
		}

		$data['cnfName'] = $cnfName;
		$data['cnfLic'] = $cnfLic;

		
		// pro
		$sql_pro = "SELECT CONCAT(cnf_lic_no,ro_year,ro_sl) AS pro,agent_id
					FROM(
					SELECT REPLACE(cnf_lic_no,'/','') AS cnf_lic_no,ro_year,
					(CASE
						WHEN LENGTH(ro_sl)=1 THEN CONCAT('0000',ro_sl)
						WHEN LENGTH(ro_sl)=2 THEN CONCAT('000',ro_sl)
						WHEN LENGTH(ro_sl)=3 THEN CONCAT('00',ro_sl)
						WHEN LENGTH(ro_sl)=4 THEN CONCAT('0',ro_sl)
						WHEN LENGTH(ro_sl)=5 THEN ro_sl
					END) AS ro_sl,agent_id
					FROM release_order_record
					WHERE imp_rot='$imp_rot' AND bl_no='$bl_no') AS tbl";
		$rslt_pro = $this->bm->dataSelectDB1($sql_pro);
		
		$pro = "";
		$agent_id = "";
		
		for($i=0;$i<count($rslt_pro);$i++)
		{
			$pro = $rslt_pro[$i]['pro'];
			$agent_id = $rslt_pro[$i]['agent_id'];
		}

		$agent_name_query = "SELECT agent_name,nid_number FROM vcms_vehicle_agent WHERE id = '$agent_id'";
		$agent_name_rslt = $this->bm->dataSelectDB1($agent_name_query);
		
		$data['bl_no']=$bl_no;
		$data['exitNoteNo']=$exitNoteNo;
		// $data['verifyNo']="";
		$data['billNo']="";	
		$data['pro']=$pro;
		$data['agent_id']=$agent_id;
		$data['agent_name_rslt']=$agent_name_rslt;

		$sql_headerData = "SELECT igm_supplimentary_detail.id,Vessel_Name,igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.BL_No,Notify_name,Bill_of_Entry_No,Bill_of_Entry_Date, 
		(SELECT verify_number FROM verify_info_fcl WHERE verify_info_fcl.rotation='$imp_rot' AND verify_info_fcl.bl_no='$bl_no') AS verify_no 
		FROM igm_supplimentary_detail 
		INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id 
		WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";

		// echo $sql_headerData; return;
		$rslt_headerData = $this->bm->dataSelectDB1($sql_headerData);
		
		if(count($rslt_headerData)==0)
		{
			$sql_headerData = "SELECT igm_details.id,Vessel_Name,igm_details.Import_Rotation_No,igm_details.BL_No,Notify_name,Bill_of_Entry_No,Bill_of_Entry_Date, 
			(SELECT verify_number FROM verify_info_fcl WHERE verify_info_fcl.rotation='$imp_rot' AND verify_info_fcl.bl_no='$bl_no') AS verify_no 
			FROM igm_details 
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'";
			$rslt_headerData = $this->bm->dataSelectDB1($sql_headerData);
		}
		// echo $sql_headerData;
		// return;
		$data['rslt_headerData']=$rslt_headerData;
		
		$sql_roData = "SELECT Pack_Marks_Number,Pack_Number,Description_of_Goods,Pack_Description,igm_supplimentary_detail.Volume_in_cubic_meters,
		Notify_name,Notify_code,Notify_address,CONCAT(Notify_name,'<br>',Notify_code) AS signAndLic,
		Voy_No,Vessel_Name,igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.Line_No,Bill_of_Entry_No AS be_no,Bill_of_Entry_Date AS be_date,
		shed_loc,shed_yard,igm_sup_detail_container.cont_number,cont_size,cont_height,cont_status,cont_iso_type,
		-- shed_mlo_do_info.delv_quantity,
		CONCAT(Pack_Number,' ',Pack_Description) AS delv_quantity,
		-- SUM(cont_weight) AS weight,
		'' AS gate_out_time,off_dock_id
		FROM igm_supplimentary_detail
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id		
		LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
		LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_supplimentary_detail.id		
		WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
		// echo $sql_roData;
		// return;
		$rslt_roData = $this->bm->dataSelectDB1($sql_roData);
		
		// if(count($rslt_roData) == 0)		// wrong result
		// if($rslt_roData[0]['Import_Rotation_No'] == null)
			
		// weight - start
		$weight = "";
		if(count($rslt_roData)==0)
		{
			$sql_roData = "SELECT Pack_Marks_Number,Pack_Number,Description_of_Goods,Pack_Description,igm_details.Volume_in_cubic_meters,Notify_name,Notify_code,Notify_address,CONCAT(Notify_name,'<br>',Notify_code) AS signAndLic,Voy_No,Vessel_Name,igm_details.Import_Rotation_No,igm_details.Line_No,Bill_of_Entry_No AS be_no,Bill_of_Entry_Date AS be_date,'' AS shed_loc,'' AS shed_yard,igm_detail_container.cont_number,cont_size,cont_height,cont_status,cont_iso_type,
			-- shed_mlo_do_info.delv_quantity,
			CONCAT(Pack_Number,' ',Pack_Description) AS delv_quantity,
			-- SUM(cont_weight) AS weight,
			'' AS gate_out_time,off_dock_id
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'";
			$rslt_roData = $this->bm->dataSelectDB1($sql_roData);
			
			$sql_weight = "SELECT SUM(igm_details.weight) AS rtnValue
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'";
			$weight = $this->bm->dataReturnDB1($sql_weight);
		}
		else
		{
			$sql_weight = "SELECT SUM(igm_supplimentary_detail.weight) AS rtnValue
			FROM igm_supplimentary_detail
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id		
			LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_supplimentary_detail.id		
			WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
			$weight = $this->bm->dataReturnDB1($sql_weight);
		}
		// echo $sql_roData;
		
		$data['weight']=$weight;
		$data['rslt_roData']=$rslt_roData;
		// weight - end
		
		// bill
		$strBillRcvInfo="select description,gl_code 
		from shed_bill_details 
		inner join shed_bill_master on shed_bill_master.bill_no=shed_bill_details.bill_no
		where shed_bill_master.verify_no='".$rslt_headerData[0]['verify_no']."'";							
		$rtnBillRcvInfo = $this->bm->dataSelectDb1($strBillRcvInfo);
		// return;	

		$data['rtnBillRcvInfo']=$rtnBillRcvInfo;
		
		$rtnBillQuery="select concat(right(YEAR(bill_date),2),'/',
		concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',
		if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,user,entry_dt,bill_date
		from shed_bill_master 
		where verify_no='".$rslt_headerData[0]['verify_no']."'";
		$rtnBillNo = $this->bm->dataSelectDb1($rtnBillQuery);
		
		$billNo = "";
		$billPreparedBy = "";
		$billPreparedTime = "";
		$billDate = "";
		for($i=0;$i<count($rtnBillNo);$i++)
		{
			$billNo = $rtnBillNo[$i]['bill_no'];
			$billDate = $rtnBillNo[$i]['bill_date'];
			$billPreparedBy = $rtnBillNo[$i]['user'];
			$billPreparedTime = $rtnBillNo[$i]['entry_dt'];
		}
		$data['billNo']=$billNo;
		$data['bill_date']=$billDate;
		$data['billPreparedBy']=$billPreparedBy;
		$data['billPreparedTime']=$billPreparedTime;
		
		// cp no - start
		$sql_billSl = "SELECT bill_no
		FROM shed_bill_master
		WHERE import_rotation='$imp_rot' AND bl_no='$bl_no'";
		// echo $sql_billSl;return;
		$billSlList = $this->bm->dataSelectDB1($sql_billSl);
		$billSl = "";
		for($b=0;$b<count($billSlList);$b++)
		{
			$billSl = $billSlList[$b]["bill_no"];
		}
		
		$sql_cpNo = "SELECT CONCAT(tmp,'-',cp_no) as cpNo,recv_time AS cpDate
		FROM(
		SELECT CONCAT(cp_bank_code,cp_unit,'/',RIGHT(cp_year,2)) AS tmp,
		CASE
		WHEN LENGTH(cp_no)=1 THEN CONCAT('000',cp_no)
		WHEN LENGTH(cp_no)=2 THEN CONCAT('00',cp_no)
		WHEN LENGTH(cp_no)=3 THEN CONCAT('0',cp_no)
		WHEN LENGTH(cp_no)=4 THEN cp_no
		ELSE ''
		END AS cp_no,recv_time
		FROM bank_bill_recv WHERE bill_no='$billSl') AS tbl";
		// echo $sql_cpNo; return;

		$rslt_cpNo = $this->bm->dataSelectDB1($sql_cpNo);
		
		$cpNo = "";
		$cpDate = "";
		for($i=0;$i<count($rslt_cpNo);$i++)
		{
			$cpNo = $rslt_cpNo[$i]['cpNo'];
			$cpDate = $rslt_cpNo[$i]['cpDate'];
		}
		
		$data['cpNo']=$cpNo;
		$data['cpDate']=$cpDate;
		// cp no - end
					
		$str="select concat(right(YEAR(bill_date),2),'/',concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,arraival_date,import_rotation,vessel_name,cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,be_no,be_date,ado_no,ado_date,ado_valid_upto,manifest_qty,cont_size,cont_height,bill_rcv_stat,if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status 
		from shed_bill_master where verify_no='".$rslt_headerData[0]['verify_no']."'"; 
			
		$rtnBillList = $this->bm->dataSelectDb1($str);
		$data['rtnBillList']=$rtnBillList;
						
		// certify by - start
		$certifyBy = "";
		$sql_certifyBy = "SELECT certify_info_fcl.update_by
		FROM certify_info_fcl
		INNER JOIN igm_details ON igm_details.id=certify_info_fcl.igm_detail_id
		WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'";
		$rslt_certifyBy = $this->bm->dataSelectDB1($sql_certifyBy);
		
		for($i=0;$i<count($rslt_certifyBy);$i++)
		{
			$certifyBy = $rslt_certifyBy[$i]['update_by'];							
		}
		
		if($certifyBy=="")
		{
			$sql_certifyBy = "SELECT verify_other_data.update_by
			FROM verify_other_data
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=verify_other_data.igm_sup_detail_id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
			$rslt_certifyBy = $this->bm->dataSelectDB1($sql_certifyBy);
			
			for($i=0;$i<count($rslt_certifyBy);$i++)
			{
				$certifyBy = $rslt_certifyBy[$i]['update_by'];							
			}
		}
		$data['certifyBy']=$certifyBy;
		// certify by - end 
		
		// verify by - start
		$verifyBy = "";
		$verifyTime = "";
		$sql_verifyBy = "SELECT verify_info_fcl.verify_by,DATE(verify_info_fcl.verify_time) AS verify_time
		FROM verify_info_fcl
		INNER JOIN igm_details ON igm_details.id=verify_info_fcl.igm_detail_id
		WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'
		LIMIT 1";
		$rslt_verifyBy = $this->bm->dataSelectDB1($sql_verifyBy);
		
		for($i=0;$i<count($rslt_verifyBy);$i++)
		{
			$verifyBy = $rslt_verifyBy[$i]['verify_by'];							
			$verifyTime = $rslt_verifyBy[$i]['verify_time'];							
		}
		
		if($verifyBy == "")
		{
			$sql_verifyBy = "SELECT shed_tally_info.verify_by,DATE(shed_tally_info.verify_time) AS verify_time
			FROM shed_tally_info
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
			$rslt_verifyBy = $this->bm->dataSelectDB1($sql_verifyBy);	

			for($i=0;$i<count($rslt_verifyBy);$i++)
			{
				$verifyBy = $rslt_verifyBy[$i]['verify_by'];							
				$verifyTime = $rslt_verifyBy[$i]['verify_time'];							
			}				
		}
		

		$data['imp_rot']=$imp_rot;
		$data['bl_no']=$bl_no;
		$data['verifyBy']=$verifyBy;
		$data['verifyTime']=$verifyTime;
		// verify by - end 
			
		$this->load->view('roFormViewPDFTos',$data);
		
		/*	// backup for pdf	- sample from vsl bill
		$this->data['bl_no']=$bl_no;
		$this->data['exitNoteNo']=$exitNoteNo;
		$this->data['verifyNo']="";
		$this->data['billNo']="";	
		$this->data['pro']=$pro;
		
		$this->load->library('m_pdf');
		$html=$this->load->view('roFormViewPDF.php',$this->data, true);			 
		
		$pdfFilePath ="releaseOrder-".$pro."-download.pdf";
		$pdf = $this->m_pdf->load();
		
		$pdf->AddPage('L', // L - landscape, P - portrait
					'', '', '', '',
					5, // margin_left
					5, // margin right
					5, // margin top
					5, // margin bottom
					5, // margin header
					5); // margin footer
					
		$stylesheet = file_get_contents('assets/stylesheets/test.css');				
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);				 
		$pdf->Output($pdfFilePath, "I");				
		*/
	}

	// Release Order View - TOS

	function lclAssignmentVerify()		// certify action
	{
		$org_Type_id = $this->session->userdata('org_Type_id');
		$data['org_Type_id']=$org_Type_id;
		
		$session_id = $this->session->userdata('value');
		
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();			
		}
		else
		{
			$igm_sup_detail_id=$this->input->post('id');
			$cnfLicense=$this->input->post('strCnfLicense');
			$strCnfCode=$this->input->post('strCnfCode');
			$agent_do=$this->input->post('strAgentDo');
			$do_date=$this->input->post('strDoDate');
			$be_no=$this->input->post('strBEno');
			$be_date=$this->input->post('strBEdate');
			$wr_out_date=$this->input->post('strWRdate');
			$verify_rot=$this->input->post('verify_rot');
			$verify_bl=$this->input->post('verify_bl');
			$verify_num=$this->input->post('verify_num');		
			$verify_id=$this->input->post('verify_id');		// shed tally id for LCL, certify_id for FCL
			$strTonUpdt=$this->input->post('strTonUpdt');
			$contStatus=$this->input->post('contStatus');
			$login_id = $this->session->userdata('login_id');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$unit = $this->input->post("unit");
			$action = $this->input->post("action");	// value 'update' for update certify
			
			// Unit Assign
			$this->unitSetUpdatePerform($verify_rot,$unit);


			$sqlIglDtlId="SELECT id as rtnValue FROM igm_details WHERE Import_Rotation_No='$verify_rot' AND BL_No='$verify_bl'";
			$resigmDtlId = $this->bm->dataSelectDb1($sqlIglDtlId);
			$igmDtlId = 0;
			for($i=0;$i<count($resigmDtlId);$i++)
			{
				$igmDtlId = $resigmDtlId[$i]["rtnValue"];
			}
			
			$igm_id = "";
			$sqlIgmId="SELECT igm_details.id AS igmID FROM igm_details 
				WHERE igm_details.Import_Rotation_No='$verify_rot' AND igm_details.BL_No='$verify_bl'
				UNION
				SELECT igm_supplimentary_detail.id AS igmID FROM igm_supplimentary_detail 
				WHERE igm_supplimentary_detail.Import_Rotation_No='$verify_rot'
				AND igm_supplimentary_detail.BL_No='$verify_bl'";
			$rslt_sqlIgmId = $this->bm->dataSelectDb1($sqlIgmId);
			for($x=0;$x<count($rslt_sqlIgmId);$x++)
			{
				$igm_id=$rslt_sqlIgmId[$x]['igmID'];	
			}

			$data['igm_id'] = $igm_id;

			$sqlIgmSupDtlId="SELECT id as rtnValue FROM igm_supplimentary_detail WHERE Import_Rotation_No='$verify_rot' AND BL_No='$verify_bl'";
			$resigmSupDtlId = $this->bm->dataSelectDb1($sqlIgmSupDtlId);
			$igmSupDtlId = 0;

			for($i=0;$i<count($resigmSupDtlId);$i++)
			{
				$igmSupDtlId = $resigmSupDtlId[$i]["rtnValue"];
			}

			// commented on 2020-03-12
			// if($verify_num=="" or $verify_num==0)		// LCL
			// {
				// $VerifyNoQuery="select MAX(verify_number) as rtnValue from shed_tally_info";
				// $VerifyNo = $this->bm->dataReturnDb1($VerifyNoQuery);
				// $maxVerifyNo = $VerifyNo+1;
							
				// //echo "Date : ".$wr_out_date;
				// //echo "VerifyNo : ".$cont;

				// $strUpdateEq = "UPDATE shed_tally_info
				// set wr_upto_date='$wr_out_date',verify_number=$maxVerifyNo,verify_by='$login_id',verify_time=NOW() 
				// where id='$verify_id'";
			
				// $statUp = $this->bm->dataInsertDB1($strUpdateEq);
				
				// $AfterUpdateShedIdQuery="select id as rtnValue from shed_tally_info where verify_number='$maxVerifyNo'";
				// $AfterUpdateShedId = $this->bm->dataReturnDb1($AfterUpdateShedIdQuery);
				// $AfterUpdateMaxShedId = $AfterUpdateShedId;
				
				// $strInsertVerifyOther = "insert into verify_other_data (shed_tally_id,agent_do,do_date,be_no,be_date,cnf_lic_no,update_ton,last_update,update_by,user_ip,cnf_name) 
				// values ('$AfterUpdateMaxShedId','$agent_do','$do_date','$be_no','$be_date','$cnfLicense','$strTonUpdt',NOW(),'$login_id','$ipaddr','$strCnfCode')";
			
				// $stat = $this->bm->dataInsertDB1($strInsertVerifyOther);
				
				// $data['msg']="";
				// if($stat==1)
					// $msg="<font color='green'><b>LCL ASSIGNMENT VERIFIED SUCCESSFULLY FOR : </font>".$maxVerifyNo;
				// else
					// $msg="<font color='red'><b>NOT INSERTED.<font color='red'><b>";
			// }
			
		//	if($verify_num=="" or $verify_num==0)

			if($verify_num=="" or $verify_num==0)		
			{	
				if($contStatus=="LCL")					//2020-03-15
				{
					$strUpdateEq = "UPDATE shed_tally_info
					set wr_upto_date='$wr_out_date',verify_by='$login_id',verify_time=NOW() 
					where id='$verify_id'";

					$statUp = $this->bm->dataInsertDB1($strUpdateEq);								

					if($action != "update")
					{
						if($strTonUpdt!='0' or $strTonUpdt!="")	
						{
							
							$strInsertVerifyOther = "insert into verify_other_data (shed_tally_id,agent_do,do_date,
							be_no,be_date,cnf_lic_no,update_ton ,last_update,update_by,user_ip,cnf_name) 
							values ('$verify_id','$agent_do','$do_date','$be_no','$be_date','$cnfLicense','$strTonUpdt',NOW(),'$login_id','$ipaddr','$strCnfCode')";

						}
						else
						{
							$strInsertVerifyOther = "insert into verify_other_data (shed_tally_id,agent_do,do_date,be_no,be_date,cnf_lic_no,last_update,update_by,user_ip,cnf_name) 
							values ('$verify_id','$agent_do','$do_date','$be_no','$be_date','$cnfLicense',NOW(),'$login_id','$ipaddr','$strCnfCode')";
						}

						$stat = $this->bm->dataInsertDB1($strInsertVerifyOther);

						$data['msg']="";
						
						if($stat==1)
							//$msg="<font color='green'><b>ASSIGNMENT VERIFIED SUCCESSFULLY FOR : </font>".$maxVerifyNo;
							$msg="<font color='green'><b>LCL ASSIGNMENT CERTIFIED SUCCESSFULLY FOR : ".$verify_rot." AND ".$verify_bl."</font>";
						else
							$msg="<font color='red'><b>NOT INSERTED.</font><b>";
					}
					else
					{
						$updateWrdate = "UPDATE shed_mlo_do_info
						set valid_upto_dt='$wr_out_date' 
						where bl_no='$verify_bl' AND imp_rot = '$verify_rot'";

						if($this->bm->dataInsertDB1($updateWrdate))
						{
							$msg="<font color='green'><b>Certify Updated Successfully.</font><b>";
						}
						else
						{
							$msg="<font color='red'><b>UPDATE FAILED.TRY AGAIN LATER...</font><b>";
						}
					}
						
				}
				
				
				else if($contStatus=="FCL")				//2020-03-15
				{
					$certifyUpdate = "";
					$count = 0;
					if($igmDtlId == 0){
						$countSqlDetails = "SELECT COUNT(*) AS rtnValue FROM certify_info_fcl WHERE igm_sup_detail_id='$igmSupDtlId'";
						$count = $this->bm->dataReturnDB1($countSqlDetails);
						
						if($strTonUpdt!='0' or $strTonUpdt!="")	
						{
							$strInsertCertifyInfoFCL = "insert into certify_info_fcl (igm_sup_detail_id,cnf_lic_no,cnf_name,agent_do,do_date,be_no,be_date,wr_upto_date, update_ton, update_by,last_update,ip_addr,rotation_no, bl_no) values ('$igmSupDtlId','$cnfLicense','$strCnfCode','$agent_do','$do_date','$be_no','$be_date','$wr_out_date',
							'$strTonUpdt','$login_id',NOW(),'$ipaddr','$verify_rot','$verify_bl')";
						}
						else
						{
							$strInsertCertifyInfoFCL = "insert into certify_info_fcl (igm_sup_detail_id,cnf_lic_no,cnf_name,agent_do,do_date,be_no,be_date,wr_upto_date, update_by,
							last_update,ip_addr,rotation_no, bl_no) values ('$igmSupDtlId','$cnfLicense','$strCnfCode','$agent_do',
							'$do_date','$be_no','$be_date','$wr_out_date', '$login_id',NOW(),'$ipaddr','$verify_rot','$verify_bl')";
						}

						if($action == "update")
						{
							$certifyUpdate = "UPDATE certify_info_fcl SET wr_upto_date = '$wr_out_date' WHERE igm_sup_detail_id='$igmSupDtlId'";

							$updateWrdate = "UPDATE shed_mlo_do_info
							set valid_upto_dt='$wr_out_date' 
							where bl_no='$verify_bl' AND imp_rot = '$verify_rot'";
						}
					}
					else
					{
						$countSqlDetails = "SELECT COUNT(*) AS rtnValue FROM certify_info_fcl WHERE igm_detail_id='$igmDtlId'";
						$count = $this->bm->dataReturnDB1($countSqlDetails);
						
						if($strTonUpdt!='0' or $strTonUpdt!="")	
						{
							$strInsertCertifyInfoFCL = "insert into certify_info_fcl (igm_detail_id,cnf_lic_no,cnf_name,agent_do,do_date,be_no,be_date,wr_upto_date,update_ton,update_by,
							last_update,ip_addr,rotation_no, bl_no) values ('$igmDtlId','$cnfLicense','$strCnfCode','$agent_do','$do_date','$be_no',
							'$be_date','$wr_out_date','$strTonUpdt','$login_id',NOW(),'$ipaddr','$verify_rot','$verify_bl')";
						}
						else
						{
							$strInsertCertifyInfoFCL = "insert into certify_info_fcl (igm_detail_id,cnf_lic_no,cnf_name,agent_do,do_date,be_no,be_date,wr_upto_date,update_by,last_update,ip_addr,rotation_no, bl_no) values ('$igmDtlId','$cnfLicense','$strCnfCode','$agent_do','$do_date','$be_no','$be_date','$wr_out_date',
							'$login_id', NOW(), '$ipaddr', '$verify_rot', '$verify_bl')";
						}

						if($action == "update")
						{
							$certifyUpdate = "UPDATE certify_info_fcl SET wr_upto_date = '$wr_out_date' WHERE igm_detail_id='$igmDtlId'";

							$updateWrdate = "UPDATE shed_mlo_do_info
							set valid_upto_dt='$wr_out_date' 
							where bl_no='$verify_bl' AND imp_rot = '$verify_rot'";
							
						}
					}
					
					// echo $strInsertCertifyInfoFCL;
					// echo $count;
					// return;
					
					if($count == 0){
						$rsltInsertCertifyInfoFCL = $this->bm->dataInsertDB1($strInsertCertifyInfoFCL);

						$data['msg']="";
						if($rsltInsertCertifyInfoFCL==1)
						//	$msg="<font color='green'><b>FCL ASSIGNMENT VERIFIED SUCCESSFULLY FOR : </font>".$maxVerifyNo;
							$msg="<font color='green'><b>FCL ASSIGNMENT CERTIFIED SUCCESSFULLY FOR : ".$verify_rot." AND ".$verify_bl."</font>";
						else
							$msg="<font color='red'><b>NOT INSERTED.</font><b>";
					}
					else
					{
						// $msg="<font color='green'><b>Allready Certified!!</font><b>";
						// echo $certifyUpdate;
						if($this->bm->dataUpdateDB1($certifyUpdate) && $this->bm->dataUpdateDB1($updateWrdate)){
							$msg="<font color='green'><b>CERTIFY UPDATED SUCCESSFULLY.</font><b>";
						}else{
							$msg="<font color='red'><b>CERTIFY NOT UPDATED. PLEASE TRY AGAIN...</font><b>";
						}

					}
					
				}				
			}
			else			// old part for verification no != ""								
			{								
				if($contStatus=="LCL")
				{									
					$strInsertTally= "UPDATE shed_tally_info 
					set wr_upto_date='$wr_out_date'
					WHERE id='$verify_id'";
				
					$stat = $this->bm->dataInsertDB1($strInsertTally);
					
					$strInsertVerifyOther = "UPDATE verify_other_data 
					set agent_do='$agent_do',cnf_name='$strCnfCode',do_date='$do_date',be_no='$be_no',be_date='$be_date',cnf_lic_no='$cnfLicense',update_ton='$strTonUpdt'
					WHERE shed_tally_id='$verify_id'";
				
					$stat1 = $this->bm->dataInsertDB1($strInsertVerifyOther);
					
					$data['msg']="";
					if($stat1==1)
						$msg="<font color='green'><b>ASSIGNMENT UPDATED SUCCESSFULLY.</font>";
					else
						$msg="<font color='red'><b>NOT UPDATED.</font>";
				}
				else if($contStatus=="FCL")
				{
					$updateCertifyInfoFCL="UPDATE certify_info_fcl SET cnf_lic_no='$cnfLicense',cnf_name='$strCnfCode',
					agent_do='$agent_do',do_date='$do_date',be_no='$be_no',be_date='$be_date',wr_upto_date='$wr_out_date',update_ton='$strTonUpdt',update_by='$login_id',last_update=NOW(),ip_addr='$ipaddr' WHERE certify_info_fcl.igm_detail_id='$igmDtlId'";		
					$rsltUpdateCertifyInfoFCL = $this->bm->dataUpdateDB1($updateCertifyInfoFCL);
					
					$data['msg']="";
					if($rsltUpdateCertifyInfoFCL==1)
						$msg="<font color='green'><b>ASSIGNMENT UPDATED SUCCESSFULLY.</font>";
					else
						$msg="<font color='red'><b>NOT UPDATED.</font>";
				}
			}		
			
			// echo $msg; return;
			
			$sqlContainer = "";
			// if($contStatus=="LCL")				//2020-03-15
			// {
				// // $sqlContainer="SELECT igm_supplimentary_detail.id,IFNULL(SUM(rcv_pack)+SUM(loc_first),0) AS rcv_pack,igm_sup_detail_container.cont_number,shed_yard,off_dock_id,igm_supplimentary_detail.Import_Rotation_No,Pack_Marks_Number,shed_loc,Description_of_Goods,ConsigneeDesc,NotifyDesc,cont_size,cont_weight,cont_seal_number,cont_status,cont_height,cont_iso_type,IFNULL(shed_tally_info.verify_number,0) AS verify_number,shed_tally_info.wr_upto_date,shed_tally_info.verify_by,shed_tally_info.verify_time,shed_tally_info.wr_upto_date,IFNULL(shed_tally_info.id,0) AS verify_id,
				// // (SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_sup_detail_container.off_dock_id) AS offdock_name,
				// // agent_do,do_date,be_no,be_date,cnf_lic_no,update_ton,cnf_name
				// // FROM  igm_supplimentary_detail
				// // INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				// // LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				// // LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				// // WHERE igm_supplimentary_detail.Import_Rotation_No='$verify_rot' AND igm_supplimentary_detail.BL_No='$verify_bl'
				// // GROUP BY igm_sup_detail_container.id";
				
				// $sqlContainer="SELECT igm_supplimentary_detail.id,IFNULL(SUM(rcv_pack)+SUM(loc_first),0) AS rcv_pack,igm_sup_detail_container.cont_number,igm_supplimentary_detail.Import_Rotation_No,Pack_Marks_Number,shed_loc,shed_yard,Description_of_Goods,ConsigneeDesc,NotifyDesc,cont_size,cont_weight,cont_seal_number,cont_status,cont_height,cont_iso_type,IFNULL(shed_tally_info.verify_number,0) AS verify_number,shed_tally_info.wr_upto_date,shed_tally_info.verify_by,shed_tally_info.verify_time,shed_tally_info.wr_upto_date,IFNULL(shed_tally_info.id,0) AS verify_id,off_dock_id,
				// (SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_sup_detail_container.off_dock_id) AS offdock_name,
				// agent_do,do_date,be_no,be_date,cnf_lic_no,update_ton,cnf_name
				// FROM  igm_supplimentary_detail
				// INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				// LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				// LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				// WHERE igm_supplimentary_detail.Import_Rotation_No='$verify_rot' AND igm_supplimentary_detail.BL_No='$verify_bl'
				// GROUP BY igm_sup_detail_container.id";
			// }
			// else if($contStatus=="FCL")
			// {
				// // $sqlContainer="SELECT igm_details.id,igm_detail_container.cont_number,igm_details.Import_Rotation_No,Pack_Marks_Number,Description_of_Goods,ConsigneeDesc,NotifyDesc,cont_size,cont_weight,cont_seal_number,cont_status,cont_height,cont_iso_type,certify_info_fcl.wr_upto_date,certify_info_fcl.wr_upto_date,IFNULL(certify_info_fcl.id,0) AS verify_id,verify_number, '' as rcv_pack,
				// // (SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_detail_container.off_dock_id) AS offdock_name,
				// // agent_do,do_date,certify_info_fcl.be_no,be_date,cnf_lic_no,update_ton,cnf_name
				// // FROM  igm_details
				// // INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				// // LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
				// // LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
				// // WHERE igm_details.Import_Rotation_No='$verify_rot' AND igm_details.BL_No='$verify_bl'
				// // GROUP BY igm_detail_container.id";
				
				// $manif_num = str_replace("/"," ",$verify_rot);
					
				// $sqlContainer="SELECT igm_details.id,igm_detail_container.cont_number,igm_details.Import_Rotation_No,Pack_Marks_Number,Description_of_Goods,ConsigneeDesc,NotifyDesc,cont_size,cont_weight,cont_seal_number,cont_status,cont_height,cont_iso_type,'' AS rcv_pack,
				// certify_info_fcl.wr_upto_date,IFNULL(certify_info_fcl.id,0) AS verify_id,verify_number,
				// (SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_detail_container.off_dock_id) AS offdock_name,
				// agent_do,do_date,update_ton,					
				// (SELECT reg_no
				// FROM sad_info
				// INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
				// WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$verify_bl' limit 1) AS be_no,					
				// (SELECT reg_date
				// FROM sad_info
				// INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
				// WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$verify_bl' limit 1) AS be_date,					
				// (SELECT dec_code
				// FROM sad_info
				// INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
				// WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$verify_bl' limit 1) AS cnf_lic_no,					
				// (SELECT dec_name
				// FROM sad_info
				// INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
				// WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$verify_bl' limit 1) AS cnf_name
				// FROM  igm_details
				// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				// LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
				// LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
				// WHERE igm_details.Import_Rotation_No='$verify_rot' AND igm_details.BL_No='$verify_bl'
				// GROUP BY igm_detail_container.id";
			// }	

			$manif_num = str_replace("/"," ",$verify_rot);

			// $sqlContainer = "SELECT igm_supplimentary_detail.id,IFNULL(SUM(rcv_pack)+SUM(loc_first),0) AS rcv_pack,igm_sup_detail_container.cont_number,igm_supplimentary_detail.Import_Rotation_No,Vessel_Name,igm_supplimentary_detail.Pack_Marks_Number,shed_loc,shed_yard, igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.ConsigneeDesc, igm_supplimentary_detail.NotifyDesc,cont_size, cont_weight,cont_seal_number,
			// 	igm_sup_detail_container.cont_status,cont_height,cont_iso_type,IFNULL(shed_tally_info.verify_number,0) AS verify_number, 
				
			// 	IF(shed_mlo_do_info.valid_upto_dt IS NULL OR shed_mlo_do_info.valid_upto_dt='0000-00-00',shed_tally_info.wr_upto_date,
			// 		shed_mlo_do_info.valid_upto_dt)
			// 		AS wr_upto_date,
				
			// 	shed_tally_info.verify_by,shed_tally_info.verify_time, IFNULL(shed_tally_info.id,0) AS verify_id,off_dock_id, shed_tally_info.wr_date as ustuffing_dt,IFNULL(shed_mlo_do_info.be_no,certify_info_fcl.be_no) AS be_no, IFNULL(shed_mlo_do_info.be_date,(certify_info_fcl.be_date)) AS be_date,  (SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_sup_detail_container.off_dock_id) AS offdock_name, organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no, shed_mlo_do_info.id AS agent_do, DATE(shed_mlo_do_info.upload_time) AS do_date, IFNULL(verify_other_data.update_ton,certify_info_fcl.update_ton) AS update_ton,COUNT(edo_application_by_cf.id) AS edo_done,(SELECT unit_no FROM assigned_unit WHERE rotation = '$verify_rot') AS unit
			// 	FROM igm_supplimentary_detail 
			// 	INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
			// 	LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
			// 	LEFT JOIN edo_application_by_cf ON igm_supplimentary_detail.Import_Rotation_No=edo_application_by_cf.rotation AND igm_supplimentary_detail.BL_No=edo_application_by_cf.bl 
			// 	LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by 
			// 	LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id 
			// 	LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id 
			// 	LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id 
			// 	LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
			// 	LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
			// 	LEFT JOIN certify_info_fcl ON igm_details.id=certify_info_fcl.igm_detail_id OR igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id 
			// 	WHERE igm_supplimentary_detail.Import_Rotation_No='$verify_rot' AND igm_supplimentary_detail.BL_No='$verify_bl' GROUP BY igm_sup_detail_container.id

			// 	UNION ALL

			// 	SELECT igm_details.id,'' AS rcv_pack,igm_detail_container.cont_number,igm_details.Import_Rotation_No,Vessel_Name,Pack_Marks_Number,'' AS shed_loc,'' AS shed_yard,Description_of_Goods,ConsigneeDesc,NotifyDesc,cont_size,cont_weight,cont_seal_number,
			// 	igm_detail_container.cont_status,cont_height,cont_iso_type,
			// 	verify_number,
				
			// 	IF(shed_mlo_do_info.valid_upto_dt IS NULL OR shed_mlo_do_info.valid_upto_dt='0000-00-00',certify_info_fcl.wr_upto_date,
			// 		shed_mlo_do_info.valid_upto_dt)
			// 		AS wr_upto_date,
				
			// 	verify_by,verify_time,IFNULL(certify_info_fcl.id,0) AS verify_id,
			// 	off_dock_id,certify_info_fcl.wr_upto_date as ustuffing_dt,
			// 	IFNULL(shed_mlo_do_info.be_no,(SELECT reg_no
			// 	FROM sad_info
			// 	INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			// 	WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$verify_bl' LIMIT 1)) AS be_no,
			// 	IFNULL(shed_mlo_do_info.be_date,(SELECT reg_date
			// 	FROM sad_info
			// 	INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			// 	WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$verify_bl' LIMIT 1)) AS be_date,

			// 	(SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_detail_container.off_dock_id) AS offdock_name,
			// 	organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no,

			// 	shed_mlo_do_info.id AS do_no, DATE(shed_mlo_do_info.upload_time) AS do_date,
			// 	update_ton,COUNT(edo_application_by_cf.id) AS edo_done,(SELECT unit_no FROM assigned_unit WHERE rotation = '$verify_rot') AS unit

			// 	FROM  igm_details
			// 	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			// 	LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			// 	LEFT JOIN edo_application_by_cf ON igm_details.Import_Rotation_No=edo_application_by_cf.rotation AND igm_details.BL_No=edo_application_by_cf.bl
			// 	LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by
			// 	LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id
			// 	LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
			// 	LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			// 	LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			// 	WHERE igm_details.Import_Rotation_No='$verify_rot' AND igm_details.BL_No='$verify_bl'
			// 	GROUP BY igm_detail_container.id";

			$sqlContainer = "SELECT igm_supplimentary_detail.id,IFNULL(SUM(rcv_pack)+SUM(loc_first),0) AS
				rcv_pack, igm_sup_detail_container.cont_number,igm_supplimentary_detail.Import_Rotation_No,Vessel_Name, igm_supplimentary_detail.Pack_Marks_Number,shed_loc,shed_yard, igm_supplimentary_detail.Description_of_Goods,
				igm_supplimentary_detail.ConsigneeDesc, igm_supplimentary_detail.NotifyDesc,cont_size,
				cont_weight, igm_supplimentary_detail.weight,  CEILING (igm_supplimentary_detail.weight/1000) AS updat_tonage, cont_seal_number,
				igm_sup_detail_container.cont_status,cont_height,cont_iso_type,
				IFNULL(shed_tally_info.verify_number,0) AS verify_number, 
				IF(shed_mlo_do_info.valid_upto_dt IS NULL OR shed_mlo_do_info.valid_upto_dt='0000-00-00',shed_tally_info.wr_upto_date,
				shed_mlo_do_info.valid_upto_dt)
				AS wr_upto_date,
				shed_tally_info.wr_date AS ustuffing_dt, shed_tally_info.verify_by,shed_tally_info.verify_time, IFNULL(shed_tally_info.id,0) AS verify_id,
				off_dock_id, 
				IF(shed_mlo_do_info.be_no IS NULL OR shed_mlo_do_info.be_no=' ',
				IF(certify_info_fcl.be_no IS NULL OR certify_info_fcl.be_no=' ',verify_other_data.be_no,certify_info_fcl.be_no),
				shed_mlo_do_info.be_no) AS be_no, 
				IF(shed_mlo_do_info.be_date IS NULL OR shed_mlo_do_info.be_date='0000-00-00' OR shed_mlo_do_info.be_date=' ',
				IF(certify_info_fcl.be_date IS NULL OR certify_info_fcl.be_date='0000-00-00' OR certify_info_fcl.be_date=' ',
				verify_other_data.be_date,certify_info_fcl.be_date),
				shed_mlo_do_info.be_date) AS be_date, 
				(SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_sup_detail_container.off_dock_id) AS offdock_name, 
				organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no, shed_mlo_do_info.id AS agent_do, 
				DATE(shed_mlo_do_info.upload_time) AS do_date,
				IFNULL(IFNULL(verify_other_data.update_ton,certify_info_fcl.update_ton), CEILING (igm_supplimentary_detail.weight/1000)) AS update_ton,
				COUNT(edo_application_by_cf.id) AS edo_done,(SELECT unit_no FROM assigned_unit WHERE rotation = '$verify_rot') AS unit
				FROM igm_supplimentary_detail 
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id 
				LEFT JOIN edo_application_by_cf ON igm_supplimentary_detail.Import_Rotation_No=edo_application_by_cf.rotation AND igm_supplimentary_detail.BL_No=edo_application_by_cf.bl 
				LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by 
				LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id 
				LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id 
				LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.bl_no=edo_application_by_cf.bl AND shed_mlo_do_info.imp_rot=edo_application_by_cf.rotation
				LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
				LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				LEFT JOIN certify_info_fcl ON igm_details.id=certify_info_fcl.igm_detail_id OR igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id 
				WHERE igm_supplimentary_detail.Import_Rotation_No='$verify_rot' AND igm_supplimentary_detail.BL_No='$verify_bl' GROUP BY igm_sup_detail_container.id
				
				UNION ALL
				
				SELECT igm_details.id,'' AS rcv_pack,igm_detail_container.cont_number,igm_details.Import_Rotation_No,Vessel_Name,Pack_Marks_Number,
				'' AS shed_loc,'' AS shed_yard,Description_of_Goods, ConsigneeDesc,NotifyDesc,cont_size,cont_weight, igm_details.weight,  
				CEILING (igm_details.weight/1000) AS updat_tonage,  cont_seal_number, igm_detail_container.cont_status, cont_height,cont_iso_type,
				verify_number, 
				IF(shed_mlo_do_info.valid_upto_dt IS NULL OR shed_mlo_do_info.valid_upto_dt='0000-00-00',certify_info_fcl.wr_upto_date,
				shed_mlo_do_info.valid_upto_dt)
				AS wr_upto_date,
				certify_info_fcl.wr_upto_date AS ustuffing_dt,
				verify_by,verify_time,IFNULL(certify_info_fcl.id,0) AS verify_id,
				off_dock_id,
				IFNULL(shed_mlo_do_info.be_no,(SELECT reg_no
				FROM sad_info
				INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
				WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$verify_bl' LIMIT 1)) AS be_no,
				IFNULL(shed_mlo_do_info.be_date,(SELECT reg_date
				FROM sad_info
				INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
				WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$verify_bl' LIMIT 1)) AS be_date,
				(SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_detail_container.off_dock_id) AS offdock_name,
				organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no,
				shed_mlo_do_info.id AS do_no, DATE(shed_mlo_do_info.upload_time) AS do_date,
				IFNULL(certify_info_fcl.update_ton, CEILING (igm_details.weight/1000)) AS update_ton,COUNT(edo_application_by_cf.id) AS edo_done,
				(SELECT unit_no FROM assigned_unit WHERE rotation = '$verify_rot') AS unit
				FROM  igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
				LEFT JOIN edo_application_by_cf ON igm_details.Import_Rotation_No=edo_application_by_cf.rotation AND igm_details.BL_No=edo_application_by_cf.bl
				LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by
				LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id
				LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
				LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
				LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$verify_rot' AND igm_details.BL_No='$verify_bl'
				GROUP BY igm_detail_container.id";
				// return;			
		
			//echo $sqlContainer;
			$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
			$data['rtnContainerList']=$rtnContainerList;
		
			//--//2020-03-11 
			$containerNo="";
			
			for($i=0;$i<count($rtnContainerList);$i++)
			{
				$containerNo=$rtnContainerList[$i]['cont_number'];
				$containerNo="'".$containerNo."',";
				//$cnf_lic_no=$rtnContainerList[$i]['cnf_lic_no'];
			}
			
			$containerNo=rtrim($containerNo,",");
			//--//2020-03-11 
			
		//	$containerNo=$rtnContainerList[0]['cont_number'];
			$verify_id=$rtnContainerList[0]['verify_id'];
			
			$cnf_lic_no=$rtnContainerList[0]['cnf_lic_no'];
			$cnf_licQuery="SELECT name from ref_bizunit_scoped where id='$cnf_lic_no'";
			$rtnCnfName = $this->bm->dataSelect($cnf_licQuery);
			$cnf_name=@$rtnCnfName[0]['NAME'];			
			
			// $strID = "select count(*) as rtnValue from sparcsn4.inv_unit
			// inner join sparcsn4.srv_event on sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey
			// where id='$containerNo' and  category='IMPRT' and event_type_gkey=30";
			
			//2020-03-11
			// $strID = "SELECT COUNT(*) AS rtnValue FROM sparcsn4.inv_unit
			// INNER JOIN sparcsn4.srv_event ON sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey
			// WHERE id IN($containerNo) AND category='IMPRT' AND event_type_gkey=30";
			// $rtnValue = $this->bm->dataReturn($strID);
			
			// 2021-01-12
			$strID="";
			$rtnValue="";
			$chkcertified = "";
			// echo "bfr";
			if($contStatus=="FCL")
			{
				/*$strID = "SELECT COUNT(*) AS rtnValue 
				FROM sparcsn4.inv_unit
				INNER JOIN sparcsn4.srv_event ON sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey
				WHERE id IN($containerNo) AND category='IMPRT' AND event_type_gkey=30";
				$rtnValue = $this->bm->dataReturn($strID);*/
				$strID = "SELECT COUNT(*) AS rtnValue 
				FROM inv_unit
				INNER JOIN srv_event ON srv_event.applied_to_gkey=inv_unit.gkey
				WHERE id IN($containerNo) AND category='IMPRT' AND event_type_gkey=30";	
				$rtnValue = $this->bm->dataReturn($strID);						
				
				//Check if already certified    -- 2021-03-09
				$chkcertify = "SELECT COUNT(certify_info_fcl.id) AS rtnValue
				FROM certify_info_fcl 
				INNER JOIN igm_details ON certify_info_fcl.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$verify_rot' AND igm_details.BL_No='$verify_bl'";
				$chkcertified = $this->bm->dataReturnDb1($chkcertify);

				if($chkcertified == 0){
					$chkcertify = "SELECT COUNT(certify_info_fcl.id) AS rtnValue
					FROM certify_info_fcl 
					INNER JOIN igm_supplimentary_detail ON certify_info_fcl.igm_sup_detail_id=igm_supplimentary_detail.id
					WHERE igm_supplimentary_detail.Import_Rotation_No='$verify_rot' AND igm_supplimentary_detail.BL_No='$verify_bl'";
					$chkcertified = $this->bm->dataReturnDb1($chkcertify);
				}

			}
			else
			{
				$strID = "SELECT COUNT(*) AS rtnValue
				FROM shed_tally_info WHERE import_rotation='$verify_rot' AND cont_number IN($containerNo)";
				$rtnValue = $this->bm->dataReturnDb1($strID);

				//Check if already certified    -- 2021-03-09

				$chkcertify = "SELECT COUNT(verify_other_data.id) AS rtnValue
				FROM verify_other_data 
				INNER JOIN shed_tally_info ON verify_other_data.shed_tally_id=shed_tally_info.id
				INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE igm_supplimentary_detail.Import_Rotation_No='$verify_rot' AND igm_supplimentary_detail.BL_No='$verify_bl'";
				$chkcertified = $this->bm->dataReturnDb1($chkcertify);

			}

			$data['certify'] = $chkcertified;
			
			/*
			if($rtnValue<1)
			{
				$msg="<font color='red'><b>CARGO IS NOT UNSTUFFED.</b></font>";
			}
			else
			{
				if($verify_id==0)
				{
					$msg="<font color='red'><b>NOT VERIFIED YET</b></font>";
				}
			}
			*/
			$ustuffing_dt="";
			$wr_upto_date="";
			
			for($a=0;$a<count($rtnContainerList);$a++)
			{
				$ustuffing_dt=$rtnContainerList[$a]['ustuffing_dt'];
				$wr_upto_date=$rtnContainerList[$a]['wr_upto_date'];
			}

			$data['cnf_lic_no']=$cnf_lic_no;
			$data['unstuff_flag']=$rtnValue;
			$data['verify_id']=$verify_id;
			$data['verify_num']=$verify_num;
			$data['cnf_name']=$cnf_name;
			$data['ddl_imp_rot_no']=$verify_rot;
			$data['ddl_bl_no']=$verify_bl;
			$data['msg']=$msg;
			$data['title']="ASSINGMENT CERTIFY SECTION...";
			$data['contStatus']=$contStatus;
			$data['ustuffing_dt']=$ustuffing_dt;
			$data['wr_upto_date']=$wr_upto_date;
			$data['flag'] = 1;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclAssignmentCertifySectionHTML',$data);
			$this->load->view('jsAssets');			
		}		
	}

	//Be File Upload --Starts

	function beFileUpload()
	{
		$session_id = $this->session->userdata('value');
		$login_id = $this->session->userdata('login_id');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$fileName=$_FILES["beFile"]["name"];
			$imp_rot=$this->input->post('imp_rot');
			$bl_no=$this->input->post('bl_no');

			move_uploaded_file($_FILES["beFile"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/resources/cnfBE/".$_FILES["beFile"]["name"]);			
			rename($_SERVER['DOCUMENT_ROOT']."/resources/cnfBE/".$_FILES["beFile"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/cnfBE/".$fileName);

			// echo "Done!";
			$this->lclAssignmentCertifyList($imp_rot,$bl_no);
			
		}
	}

	//Be File Upload --Ends

	function releaseorderpdf()
	{
		// $verify_number=$this->input->post('verify_number');
		
		if($this->input->post('options')=="Bill")
		{
			// echo "bill";return;
			$verify_number=$this->input->post('verify_number');
			$bill=1; // From Release Order Form
			$this->getShedBillPdf($verify_number,$bill);
		}
		else
		{
			$verify_num = "";
			if($this->uri->segment(3)){
				// echo "url";return;
				$bl_no = str_replace("_","/",$this->uri->segment(3));
				$imp_rot=str_replace("_","/",$this->uri->segment(4));
				$verify_num=$this->uri->segment(5);
				
			}else{
				// echo "post";return;
				$verify_num=$this->input->post('verify_number');		// in view page modify on verify_no != 0
				$imp_rot=$this->input->post('imp_rot');
				$bl_no=$this->input->post('bl_no');
			}
			
			//$verify_num=$this->input->post('verify_number');		// in view page modify on verify_no != 0
			
			//$imp_rot=$this->input->post('imp_rot');
			//$bl_no=$this->input->post('bl_no');
			   $submit_ro =$this->input->post('submit_ro');	
              	
			
			$chkContStatus="";
			$vryStat=0;
			
			if($this->input->post('verify_number'))
			{
				$vryStat=1;
				
				$chkContStatus="SELECT igm_sup_detail_container.cont_status
				FROM igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
				LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
				WHERE shed_tally_info.verify_number='$verify_num' LIMIT 1
				UNION ALL
				SELECT igm_detail_container.cont_status
				FROM igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
				INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
				WHERE verify_info_fcl.verify_number='$verify_num' LIMIT 1";			
				
			}
			else
			{
				$chkContStatus="SELECT igm_sup_detail_container.cont_status
				FROM igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
				WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no' LIMIT 1
				UNION ALL
				SELECT igm_detail_container.cont_status
				FROM igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
				WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no' LIMIT 1";
			}
			// echo $chkContStatus;return;
			
			// $contStatus=$this->bm->dataReturnDb1($chkContStatus);
			
			$rsltContStatus=$this->bm->dataSelectDb1($chkContStatus);
			$contStatus="";
			for($t=0;$t<count($rsltContStatus);$t++){
			$contStatus=$rsltContStatus[$t]['cont_status'];
			}
			
			
			$strBill="";
			if($contStatus=="LCL")
			{
				if($vryStat==1)
				{
					$strBill="select igm_supplimentary_detail.id,IFNULL(sum(rcv_pack+loc_first),0) as rcv_pack,igm_masters.Vessel_Name,
					igm_supplimentary_detail.Import_Rotation_No,igm_sup_detail_container.cont_number,Pack_Marks_Number,shed_loc,
					shed_yard,Description_of_Goods,Notify_name,IFNULL(shed_tally_info.verify_number,0) as verify_number,
					IFNULL(shed_tally_info.id,0) as verify_id,igm_supplimentary_detail.Pack_Number,
					igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_size,
					igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_weight,verify_other_data.cnf_name,
					IFNULL(NULLIF(shed_mlo_do_info.be_no,' '),verify_other_data.be_no) AS be_no,
					verify_other_data.be_date,igm_sup_detail_container.cont_height,bank_bill_recv.bill_no,bank_bill_recv.cp_no,
					RIGHT(bank_bill_recv.cp_year,2) AS cp_year,bank_bill_recv.cp_bank_code,bank_bill_recv.cp_unit,
					date(bank_bill_recv.recv_time) as cp_date,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.Line_No,
					total_port,concat(right(YEAR(bill_date),2),'/',concat(if(length(bill_generation_no)=1,'00000',
					if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',if(length(bill_generation_no)=4,'00',
					if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as master_bill_no,shed_bill_master.bill_date,VoyNo,
					verify_other_data.exit_note_number,pr_number
					from  igm_supplimentary_detail
					inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
					inner join igm_masters on igm_supplimentary_detail.igm_master_id=igm_masters.id
					left join  shed_tally_info on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
					left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
					left join shed_bill_master on shed_bill_master.verify_no=shed_tally_info.verify_number
					left join bank_bill_recv on bank_bill_recv.bill_no=shed_bill_master.bill_no
					left join vessels_berth_detail on shed_bill_master.import_rotation=vessels_berth_detail.Import_Rotation_No
					LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.imp_rot=igm_supplimentary_detail.Import_Rotation_No AND igm_supplimentary_detail.BL_No=shed_mlo_do_info.bl_no
					where shed_tally_info.verify_number='$verify_num' limit 1";
				}
				else
				{
					// $strBill="SELECT igm_supplimentary_detail.id,IFNULL(SUM(rcv_pack+loc_first),0) AS rcv_pack,igm_masters.Vessel_Name,
					// igm_supplimentary_detail.Import_Rotation_No,igm_sup_detail_container.cont_number,Pack_Marks_Number,shed_loc,shed_yard,
					// Description_of_Goods,Notify_name,IFNULL(shed_tally_info.verify_number,0) AS verify_number,
					// IFNULL(shed_tally_info.id,0) AS verify_id,igm_supplimentary_detail.Pack_Number,
					// igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_size,
					// igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_weight,verify_other_data.cnf_name,
					// IFNULL(NULLIF(shed_mlo_do_info.be_no,' '),verify_other_data.be_no) AS be_no,verify_other_data.be_date,
					// igm_sup_detail_container.cont_height,bank_bill_recv.bill_no,bank_bill_recv.cp_no,
					// RIGHT(bank_bill_recv.cp_year,2) AS cp_year,bank_bill_recv.cp_bank_code,bank_bill_recv.cp_unit,
					// DATE(bank_bill_recv.recv_time) AS cp_date,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.Line_No,
					// total_port,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000',
					// IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',IF(LENGTH(bill_generation_no)=4,'00',
					// IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS master_bill_no,shed_bill_master.bill_date,VoyNo,
					// verify_other_data.exit_note_number,pr_number
					// FROM  igm_supplimentary_detail
					// INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
					// INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
					// LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
					// LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
					// LEFT JOIN shed_bill_master ON shed_bill_master.verify_no=shed_tally_info.verify_number
					// LEFT JOIN bank_bill_recv ON bank_bill_recv.bill_no=shed_bill_master.bill_no
					// LEFT JOIN vessels_berth_detail ON shed_bill_master.import_rotation=vessels_berth_detail.Import_Rotation_No
					// LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.imp_rot=igm_supplimentary_detail.Import_Rotation_No AND  igm_supplimentary_detail.BL_No=shed_mlo_do_info.bl_no
					// WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no' LIMIT 1";
					
					// for new format of Release order - intakhab - 2022-03-14
					$strBill = "SELECT igm_supplimentary_detail.id,
					IFNULL(SUM(rcv_pack+loc_first),0) AS rcv_pack,
					rcv_unit,
					igm_masters.Vessel_Name,
					Voy_No,
					igm_supplimentary_detail.Volume_in_cubic_meters,
					(SELECT actual_delv_pack FROM do_truck_details_entry WHERE verify_number='U20107210001') AS actual_delv_pack,	
					(SELECT gate_out_time FROM do_truck_details_entry WHERE verify_number='U20107210001') AS gate_out_time,		
					igm_supplimentary_detail.Notify_name,
					igm_supplimentary_detail.Notify_code,
					igm_supplimentary_detail.Import_Rotation_No,
					igm_sup_detail_container.cont_number,
					SUBSTRING(igm_supplimentary_detail.Pack_Marks_Number, 1, 50) AS Pack_Marks_Number,
					shed_loc,
					shed_yard,
					SUBSTRING(Description_of_Goods, 1, 50) AS Description_of_Goods,
					Notify_name,
					IFNULL(shed_tally_info.verify_number,0) AS verify_number,
					IFNULL(shed_tally_info.id,0) AS verify_id,
					igm_supplimentary_detail.Pack_Number,
					igm_supplimentary_detail.Pack_Description,
					igm_supplimentary_detail.BL_No,

					igm_sup_detail_container.cont_number,
					igm_sup_detail_container.cont_size,
					igm_sup_detail_container.cont_height,
					igm_sup_detail_container.cont_status,
					igm_sup_detail_container.cont_weight,
					igm_sup_detail_container.cont_iso_type,
					igm_sup_detail_container.cont_type,

					verify_other_data.cnf_name,
					IFNULL(NULLIF(shed_mlo_do_info.be_no,' '),
					verify_other_data.be_no) AS be_no,
					verify_other_data.be_date,
					bank_bill_recv.bill_no,
					bank_bill_recv.cp_no,
					RIGHT(bank_bill_recv.cp_year,2) AS cp_year,
					bank_bill_recv.cp_bank_code,
					bank_bill_recv.cp_unit,
					DATE(bank_bill_recv.recv_time) AS cp_date,
					igm_supplimentary_detail.Notify_address,
					igm_supplimentary_detail.Line_No,
					total_port,
					CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000',
					IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',IF(LENGTH(bill_generation_no)=4,'00',
					IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS master_bill_no,
					shed_bill_master.bill_date,
					VoyNo,
					verify_other_data.exit_note_number,
					pr_number
					FROM  igm_supplimentary_detail
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
					INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
					LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
					LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no=shed_tally_info.verify_number
					LEFT JOIN bank_bill_recv ON bank_bill_recv.bill_no=shed_bill_master.bill_no
					LEFT JOIN vessels_berth_detail ON shed_bill_master.import_rotation=vessels_berth_detail.Import_Rotation_No
					LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.imp_rot=igm_supplimentary_detail.Import_Rotation_No AND igm_supplimentary_detail.BL_No=shed_mlo_do_info.bl_no
					WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no' LIMIT 1";
				}				
			}
			else if($contStatus=="FCL")
			{
				if($vryStat==1)
				{//be_no
					
					
					$strBill="SELECT DISTINCT igm_detail_container.cont_number,igm_details.id,shed_bill_master.vessel_Name,igm_details.Import_Rotation_No,
					igm_details.Pack_Marks_Number,
					igm_details.Description_of_Goods,igm_details.Notify_name,IFNULL(verify_info_fcl.verify_number,0) AS verify_number,IFNULL(verify_info_fcl.id,0) AS verify_id,igm_details.Pack_Number,
					igm_details.Pack_Description,igm_details.BL_No,igm_detail_container.igm_detail_id,igm_detail_container.cont_size,igm_detail_container.cont_status,igm_detail_container.cont_weight,
					appraisement_info_fcl.cnf_name,
					IFNULL(shed_mlo_do_info.be_no,(SELECT reg_no FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id WHERE manif_num LIKE '%2021 851%' AND sum_declare = 'MAX/SIN/2302/2021' LIMIT 1)) AS be_no, IFNULL(shed_mlo_do_info.be_date,(SELECT reg_date FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id WHERE manif_num LIKE '%2021 851%' AND sum_declare = 'MAX/SIN/2302/2021' LIMIT 1)) AS be_date,
					igm_detail_container.cont_height,shed_bill_details.bill_no,bank_bill_recv.cp_no,
					RIGHT(bank_bill_recv.cp_year,2) AS cp_year,bank_bill_recv.cp_bank_code,bank_bill_recv.cp_unit,DATE(bank_bill_recv.recv_time) AS cp_date,igm_details.Notify_address,
					igm_details.Line_No,shed_bill_master.total_port,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000',
					IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',IF(LENGTH(bill_generation_no)=4,'00',IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS master_bill_no,shed_bill_master.bill_date,shed_bill_master.import_rotation AS VoyNo,'' AS shed_loc,'' AS shed_yard,pr_number
					FROM  igm_details
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
					LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
					  LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id

					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no=verify_info_fcl.verify_number
					LEFT JOIN shed_bill_details ON shed_bill_details.verify_no=verify_info_fcl.verify_number
					LEFT JOIN bank_bill_recv ON bank_bill_recv.bill_no=shed_bill_master.bill_no
					WHERE verify_info_fcl.verify_number='$verify_num'
					
					UNION

					SELECT DISTINCT igm_sup_detail_container.cont_number,igm_supplimentary_detail.id,shed_bill_master.vessel_Name,igm_supplimentary_detail.Import_Rotation_No, igm_supplimentary_detail.Pack_Marks_Number, igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Notify_name,IFNULL(verify_info_fcl.verify_number,0) AS verify_number,IFNULL(verify_info_fcl.id,0) AS verify_id,igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.BL_No,igm_sup_detail_container.igm_sup_detail_id,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_weight,appraisement_info_fcl.cnf_name, IFNULL(shed_mlo_do_info.be_no,certify_info_fcl.be_no) AS be_no, IFNULL(shed_mlo_do_info.be_date,(certify_info_fcl.be_date)) AS be_date, igm_sup_detail_container.cont_height,shed_bill_details.bill_no,bank_bill_recv.cp_no, RIGHT(bank_bill_recv.cp_year,2) AS cp_year,bank_bill_recv.cp_bank_code,bank_bill_recv.cp_unit,DATE(bank_bill_recv.recv_time) AS cp_date,igm_supplimentary_detail.Notify_address, igm_supplimentary_detail.Line_No,shed_bill_master.total_port,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000', IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',IF(LENGTH(bill_generation_no)=4,'00',IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS master_bill_no,shed_bill_master.bill_date,shed_bill_master.import_rotation AS VoyNo,'' AS shed_loc,'' AS shed_yard,pr_number 
					FROM igm_supplimentary_detail 
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
					LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_supplimentary_detail.id 
					LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id 
					LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
					LEFT JOIN certify_info_fcl ON igm_details.id=certify_info_fcl.igm_detail_id OR igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id 
					LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_supplimentary_detail.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_supplimentary_detail.BL_No 
					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no=verify_info_fcl.verify_number 
					LEFT JOIN shed_bill_details ON shed_bill_details.verify_no=verify_info_fcl.verify_number 
					LEFT JOIN bank_bill_recv ON bank_bill_recv.bill_no=shed_bill_master.bill_no
					WHERE verify_info_fcl.verify_number='$verify_num'";	
				}
				else
				{
					$strBill="SELECT DISTINCT igm_detail_container.cont_number,
					'' AS rcv_unit,
					'' AS Volume_in_cubic_meters,
					'' AS gate_out_time,
					'' AS actual_delv_pack,
					'' AS Notify_code,
					
					-- '' AS cpnoview,
					-- '' AS yard_No,
					-- '' AS pos,
					-- '' AS Vessel_Name,					
					
					igm_details.id,igm_masters.Vessel_Name,igm_details.Import_Rotation_No,
					SUBSTRING(igm_details.Pack_Marks_Number, 1, 50) AS Pack_Marks_Number,
					SUBSTRING(igm_details.Description_of_Goods, 1, 50) AS Description_of_Goods,
					igm_details.Notify_name,IFNULL(verify_info_fcl.verify_number,0) AS verify_number,IFNULL(verify_info_fcl.id,0) AS verify_id,igm_details.Pack_Number,
					igm_details.Pack_Description,igm_details.BL_No,igm_detail_container.igm_detail_id,igm_detail_container.cont_size,igm_detail_container.cont_status,igm_detail_container.cont_weight,igm_detail_container.cont_iso_type,appraisement_info_fcl.cnf_name,
					IFNULL(shed_mlo_do_info.be_no,(SELECT reg_no FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id WHERE manif_num LIKE '%2021 851%' AND sum_declare = 'MAX/SIN/2302/2021' LIMIT 1)) AS be_no, IFNULL(shed_mlo_do_info.be_date,(SELECT reg_date FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id WHERE manif_num LIKE '%2021 851%' AND sum_declare = 'MAX/SIN/2302/2021' LIMIT 1)) AS be_date,
					igm_detail_container.cont_height,shed_bill_details.bill_no,bank_bill_recv.cp_no,
					RIGHT(bank_bill_recv.cp_year,2) AS cp_year,bank_bill_recv.cp_bank_code,bank_bill_recv.cp_unit,DATE(bank_bill_recv.recv_time) AS cp_date,igm_details.Notify_address,
					igm_details.Line_No,shed_bill_master.total_port,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000',
					IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',IF(LENGTH(bill_generation_no)=4,'00',IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS master_bill_no,shed_bill_master.bill_date,shed_bill_master.import_rotation AS VoyNo,Voy_No,'' AS shed_loc,'' AS shed_yard, pr_number
					FROM  igm_details
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
					LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no=verify_info_fcl.verify_number
					LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
					LEFT JOIN shed_bill_details ON shed_bill_details.verify_no=verify_info_fcl.verify_number
					LEFT JOIN bank_bill_recv ON bank_bill_recv.bill_no=shed_bill_master.bill_no
					WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'
					
					UNION

					SELECT DISTINCT igm_sup_detail_container.cont_number,
					
					'' AS rcv_unit,
					'' AS Volume_in_cubic_meters,
					'' AS gate_out_time,
					'' AS actual_delv_pack,
					'' AS Notify_code,
					
					-- '' AS cpnoview,
					-- '' AS yard_No,
					-- '' AS pos,
					-- '' AS Vessel_Name,					
					
					igm_supplimentary_detail.id,igm_masters.Vessel_Name,igm_supplimentary_detail.Import_Rotation_No, 
					SUBSTRING(igm_supplimentary_detail.Pack_Marks_Number, 1, 50) AS Pack_Marks_Number,					
					SUBSTRING(igm_supplimentary_detail.Description_of_Goods, 1, 50) AS Description_of_Goods,
					igm_supplimentary_detail.Notify_name,IFNULL(verify_info_fcl.verify_number,0) AS verify_number,IFNULL(verify_info_fcl.id,0) AS verify_id,igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.BL_No,igm_sup_detail_container.igm_sup_detail_id,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_status, igm_sup_detail_container.cont_weight,igm_sup_detail_container.cont_iso_type,appraisement_info_fcl.cnf_name, 
					IFNULL(shed_mlo_do_info.be_no,certify_info_fcl.be_no) AS be_no,
					IFNULL(shed_mlo_do_info.be_date,(certify_info_fcl.be_date)) AS be_date,
					igm_sup_detail_container.cont_height,shed_bill_details.bill_no,bank_bill_recv.cp_no, RIGHT(bank_bill_recv.cp_year,2) AS cp_year,bank_bill_recv.cp_bank_code,bank_bill_recv.cp_unit,DATE(bank_bill_recv.recv_time) AS cp_date,igm_supplimentary_detail.Notify_address, igm_supplimentary_detail.Line_No,shed_bill_master.total_port,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000', IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',IF(LENGTH(bill_generation_no)=4,'00',IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS master_bill_no,shed_bill_master.bill_date,shed_bill_master.import_rotation AS VoyNo,Voy_No,'' AS shed_loc,'' AS shed_yard, pr_number FROM igm_supplimentary_detail INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_supplimentary_detail.id 
					LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id 
					LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
					LEFT JOIN certify_info_fcl ON igm_details.id=certify_info_fcl.igm_detail_id OR igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id 
					LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_supplimentary_detail.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_supplimentary_detail.BL_No LEFT JOIN shed_bill_master ON shed_bill_master.verify_no=verify_info_fcl.verify_number LEFT JOIN shed_bill_details ON shed_bill_details.verify_no=verify_info_fcl.verify_number LEFT JOIN bank_bill_recv ON bank_bill_recv.bill_no=shed_bill_master.bill_no
					WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";	
				}					
			}						
			/*  echo $be_no;
			 return; */
			// echo $strBill;return;
			$rtnContainerList = $this->bm->dataSelectDb1($strBill);									
							
			$data['rtnContainerList']=$rtnContainerList;
				
			$strBillRcvInfo="select description,gl_code 
							from shed_bill_details 
							inner join shed_bill_master on shed_bill_master.bill_no=shed_bill_details.bill_no
							where shed_bill_master.verify_no='$verify_num'";
											
			$rtnBillRcvInfo = $this->bm->dataSelectDb1($strBillRcvInfo);
		
			$data['rtnBillRcvInfo']=$rtnBillRcvInfo;
						
			$str="select concat(right(YEAR(bill_date),2),'/',concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,arraival_date,import_rotation,vessel_name,cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,be_no,be_date,ado_no,ado_date,ado_valid_upto,manifest_qty,cont_size,cont_height,bill_rcv_stat,if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status 
			from shed_bill_master where verify_no='$verify_num'"; 
				
			$rtnBillList = $this->bm->dataSelectDb1($str);
				
			$unit_no="";
			$cpa_vat_reg_no="";
			$ex_rate="";
			$bill_rcv_stat="";
			
			if(count($rtnBillList)>0)
			{
				$unit_no=$rtnBillList[0]['unit_no'];
				$cpa_vat_reg_no=$rtnBillList[0]['cpa_vat_reg_no'];
				$ex_rate=$rtnBillList[0]['ex_rate'];
				$bill_rcv_stat=$rtnBillList[0]['bill_rcv_stat'];
			}			

			$data['rtnBillList']=$rtnBillList;				

			// certify by - start
			$certifyBy = "";
			$sql_certifyBy = "SELECT certify_info_fcl.update_by
			FROM certify_info_fcl
			INNER JOIN igm_details ON igm_details.id=certify_info_fcl.igm_detail_id
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'";
			$rslt_certifyBy = $this->bm->dataSelectDB1($sql_certifyBy);
			
			for($i=0;$i<count($rslt_certifyBy);$i++)
			{
				$certifyBy = $rslt_certifyBy[$i]['update_by'];							
			}
			
			if($certifyBy=="")
			{
				$sql_certifyBy = "SELECT verify_other_data.update_by
				FROM verify_other_data
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=verify_other_data.igm_sup_detail_id
				WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
				$rslt_certifyBy = $this->bm->dataSelectDB1($sql_certifyBy);
				
				for($i=0;$i<count($rslt_certifyBy);$i++)
				{
					$certifyBy = $rslt_certifyBy[$i]['update_by'];							
				}
			}
			$data['certifyBy']=$certifyBy;
			// certify by - end 
			
			// verify by - start
			$verifyBy = "";
			$verifyTime = "";
			$sql_verifyBy = "SELECT verify_info_fcl.verify_by,DATE(verify_info_fcl.verify_time) AS verify_time
			FROM verify_info_fcl
			INNER JOIN igm_details ON igm_details.id=verify_info_fcl.igm_detail_id
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'
			LIMIT 1";
			$rslt_verifyBy = $this->bm->dataSelectDB1($sql_verifyBy);
			
			for($i=0;$i<count($rslt_verifyBy);$i++)
			{
				$verifyBy = $rslt_verifyBy[$i]['verify_by'];							
				$verifyTime = $rslt_verifyBy[$i]['verify_time'];							
			}
			
			if($verifyBy == "")
			{
				$sql_verifyBy = "SELECT shed_tally_info.verify_by,DATE(shed_tally_info.verify_time) AS verify_time
				FROM shed_tally_info
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
				WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
				$rslt_verifyBy = $this->bm->dataSelectDB1($sql_verifyBy);	

				for($i=0;$i<count($rslt_verifyBy);$i++)
				{
					$verifyBy = $rslt_verifyBy[$i]['verify_by'];							
					$verifyTime = $rslt_verifyBy[$i]['verify_time'];							
				}				
			}
			$data['verifyBy']=$verifyBy;
			$data['verifyTime']=$verifyTime;
			// verify by - end 
			
			$data['title']="Shed Bill";
			$data['verify_number']=$verify_num;
		//	$data['tot_sum']=$tot_sum;
			$data['unit_no']=$unit_no;
			$data['cpa_vat_reg_no']=$cpa_vat_reg_no;
			$data['ex_rate']=$ex_rate;
			$data['bill_rcv_stat']=$bill_rcv_stat;
		//	$data['cpnoview']=$cpnoview;
					
		//	$data['recv_time']=$recv_time;
		//	$data['recv_by']=$recv_by;
		//	$data['billPrepareBy']=$billPrepareBy;
			$data['bill_print_times']=4;
			$login_id = $this->session->userdata('login_id');
			$data['login_id']=$login_id;
									
			$data['verifyNo']=$verify_num;				
			$data['verify_num']=$verify_num;
			
					
			// $this->load->view('releaseOrderFormViewPDF',$data);				 			
			//$this->load->view('releaseOrderFormViewPDF_static_5',$data);				 			
		}
	}

	//unit set or update start

	function unitSetUpdate()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$msg="";
			$value=0;
			$rot = null;

			if($this->input->post('ddl_imp_rot_no')){
				$rot = $this->input->post('ddl_imp_rot_no');
			}
			
			$data['rot']=$rot;
			$data['title']="UNIT UPDATE...";
			$data['msg']=$msg;
			$data['value']=$value;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('unitSetUpdate',$data);
			$this->load->view('jsAssets');
		}
	}
	
	// function unitSetUpdatePerform($rotation = null,$unit = null)
	// {
	// 	$session_id = $this->session->userdata('value');
	// 	if($session_id!=$this->session->userdata('session_id'))
	// 	{
	// 		$this->logout();
	// 	}
	// 	else
	// 	{
	// 		$st = 0;

	// 		if(is_null($rotation) && is_null($unit))
	// 		{
	// 			$rotation=$this->input->post('rotation');
	// 			$unit=$this->input->post('unit');
	// 		}
	// 		else
	// 		{
	// 			$st = 1;	// state to return back
	// 		}
			

	// 		$value="";
	// 		if(is_numeric($unit))
	// 		{
	// 			$sql_check="select count(*) as rtnValue from assigned_unit where rotation='$rotation'";
	// 			$rtn_check=$this->bm->dataReturnDB1($sql_check);
				
	// 			$value=0;
				
	// 			if($rtn_check==0)
	// 			{
	// 				$sql_insert="insert into assigned_unit(rotation,unit_no,created_at) values('$rotation','$unit',NOW())";
	// 				$rslt_insert=$this->bm->dataInsertDB1($sql_insert);
	// 				$msg="<font color='green'><b>Successfully inserted</b></font>";
	// 			}
	// 			else
	// 			{
	// 				$sql_update="update assigned_unit set unit_no='$unit',updated_at = NOW() where rotation='$rotation'";
	// 				$rslt_update=$this->bm->dataUpdateDB1($sql_update);
	// 				$msg="<font color='green'><b>Successfully updated</b></font>";
	// 			}
	// 		}
	// 		else
	// 		{
	// 			$msg="<font color='red'><b>Please provide digit only...</b></font>";
	// 		}

	// 		if($st == 1)
	// 		{
	// 			return $msg;
	// 		}
	// 		else
	// 		{
	// 			$data['title']="UNIT UPDATE...";
	// 			$data['msg']=$msg;
	// 			$data['value']=$value;
	// 			$this->load->view('cssAssets');
	// 			$this->load->view('headerTop');
	// 			$this->load->view('sidebar');
	// 			$this->load->view('unitSetUpdate',$data);
	// 			$this->load->view('jsAssets');
	// 		}
	// 	}
	// }


	function unitSetUpdatePerform($rotation = null,$unit = null)
	{
		
		$session_id = $this->session->userdata('value');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$st = 0;

			if(is_null($rotation) && is_null($unit))
			{
				$rotation=$this->input->post('rotation');
				$unit=$this->input->post('unit');
			}
			else
			{
				$st = 1;	// state to return back
			}
			
			$sql_cmn_landing="SELECT to_char(time_discharge_complete,'YYYY-MM-DD') as RTNVALUE
			FROM vsl_vessel_visit_details
			INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
			WHERE ib_vyg='$rotation' fetch first 1 rows only";
			$cmn_landind_dt=$this->bm->dataReturndb5($sql_cmn_landing);


			$value="";
			if(is_numeric($unit))
			{
				//echo "ok";
				 if($cmn_landind_dt!="")
				{ 
					$sql_check="select count(*) as rtnValue from assigned_unit where rotation='$rotation'";
					$rtn_check=$this->bm->dataReturnDB1($sql_check);
					
					$value=0;
					
					if($rtn_check==0)
					{
						$sql_insert="insert into assigned_unit(rotation,unit_no, common_land_date, created_by, created_at, created_ip) values('$rotation','$unit', '$cmn_landind_dt', '$login_id', NOW(), '$ipaddr')";
						$rslt_insert=$this->bm->dataInsertDB1($sql_insert);
						$msg="<font color='green'><b>Successfully inserted</b></font>";
					}
					else
					{
						$sql_update="update assigned_unit set unit_no='$unit', common_land_date='$cmn_landind_dt', updated_by='$login_id',updated_at=NOW(),updated_ip='$ipaddr' where rotation='$rotation'";
						$rslt_update=$this->bm->dataUpdateDB1($sql_update);
						$msg="<font color='green'><b>Successfully updated</b></font>";
					}
					//return;
				 }
				else
				{
					$msg="<font color='red'><b>Common landing date not found for this rotation in n4</b></font>";
				} 
			}
			else
			{
				$msg="<font color='red'><b>Please provide digit only...</b></font>";
			}


			if($st == 1)
			{
				return $msg;
			}
			else
			{
				$data['title']="UNIT UPDATE...";
				$data['msg']=$msg;
				$data['value']=$value;
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('unitSetUpdate',$data);
				$this->load->view('jsAssets');
			}
		}
	}

	//unit set or update end
	
	//unit list start

	function unitList()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$sql_list="select * from assigned_unit";
			$rslt_list=$this->bm->dataSelectDB1($sql_list);
			$msg="";
			
			$data['title']="UNIT LIST...";
			$data['rslt_list']=$rslt_list;
			$data['msg']=$msg;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('unitList',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function unitListSearch()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$rotation=$this->input->post('rotation');
			$msg="";
			
			$sql_list="select * from assigned_unit where rotation='$rotation'";
			$rslt_list=$this->bm->dataSelectDB1($sql_list);
			
			$data['title']="UNIT LIST...";
			$data['rslt_list']=$rslt_list;
			$data['msg']=$msg;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('unitList',$data);
			$this->load->view('jsAssets');
		}
	}

	//unit list end
	
	//unit list delete start

	function unitListDelete()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$rotation=$this->input->post('rot');
			$unit=$this->input->post('unit');
			$msg="<font color='green'><b>Successfully Deleted</b></font>";
			
			$sql_delete="DELETE FROM assigned_unit WHERE rotation='$rotation' AND unit_no='$unit'";
			
			$rslt_delete = $this->bm->dataDeleteDB1($sql_delete);
			
			$sql_list="select * from assigned_unit";
			$rslt_list=$this->bm->dataSelectDB1($sql_list);
			
			$data['title']="UNIT LIST...";
			$data['rslt_list']=$rslt_list;
			$data['msg']=$msg;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('unitSetUpdate',$data);
			$this->load->view('jsAssets');
			
		}
	}

	//unit list delete end
	
	//unit list edit start

	function unitListEdit()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$rotation=$this->input->post('rt_no');
			$msg="";
			$value=1;
			
			$data['title']="UNIT UPDATE...";
			$data['msg']=$msg;
			$data['value']=$value;
			$data['rotation']=$rotation;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('unitSetUpdate',$data);
			$this->load->view('jsAssets');
		}
	}

	//unit list edit end

	function appraisementCertifyList()
	{ 
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();

		}
		else
		{
			if($this->input->post('ddl_imp_rot_no') && $this->input->post('ddl_bl_no'))
			{
				$ddl_imp_rot_no=$this->input->post('ddl_imp_rot_no');
				$ddl_bl_no=$this->input->post('ddl_bl_no');
			}
			else
			{
				$ddl_bl_no=str_replace("_","/",$this->uri->segment(3));
				$ddl_imp_rot_no=str_replace("_","/",$this->uri->segment(4));					
			}

			$type = "";
			$id = "";

			if($this->input->post('type')){
				$type = $this->input->post('type');
				$id = $this->input->post('id');
			}

			$data['type'] = $type;
			$data['id'] = $id;

			$msg="";
			$contStatus="";
			$sql_contStatus="SELECT cont_status AS rtnValue
			FROM  igm_supplimentary_detail
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
			LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
			LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no' AND igm_supplimentary_detail.BL_No='$ddl_bl_no' 
			GROUP BY igm_sup_detail_container.id

			UNION

			SELECT cont_status AS rtnValue
			FROM  igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			INNER JOIN igm_masters ON igm_details.IGM_id=igm_masters.id
			LEFT JOIN  shed_tally_info ON shed_tally_info.igm_detail_id=igm_details.id
			LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
			WHERE igm_details.Import_Rotation_No='$ddl_imp_rot_no' AND igm_details.BL_No='$ddl_bl_no' 
			GROUP BY igm_detail_container.id";
			
			//$contStatus=$this->bm->dataReturnDb1($sql_contStatus);		
			$contStatusRslt=$this->bm->dataSelectDb1($sql_contStatus);
			for($i=0; $i<count($contStatusRslt); $i++)
			{
				$contStatus=$contStatusRslt[$i]['rtnValue'];
			}

			$excngeDoneStat = "";

			if($contStatus!="" or $contStatus!=null)
			{
				if($contStatus=="LCL")
				{
					$chkExchangeDoneQry="SELECT COUNT(shed_tally_info.id) AS exchangeCount FROM shed_tally_info 
					INNER JOIN igm_supplimentary_detail ON
					shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
					WHERE  shed_tally_info.import_rotation='$ddl_imp_rot_no' AND 
					igm_supplimentary_detail.BL_No='$ddl_bl_no'";
					//shed_tally_info.berth_exchange_done_status=1 AND shed_tally_info.cpa_exchange_done_status=1 AND shed_tally_info.ff_exchange_done_status=1 and				
					$rtnchkExchangeDoneList = $this->bm->dataSelectDb1($chkExchangeDoneQry);
					$excngeDoneStat=$rtnchkExchangeDoneList[0]['exchangeCount'];
				}
				else if($contStatus=="FCL")
				{
					$excngeDoneStat=1;
				}

				if($excngeDoneStat<1)
				{
					$data['excngeDoneStat']=$excngeDoneStat;
					$msg="<font color='red'><b>THIS CONTAINER IS NOT YET UNSTUFFED.</b></font>";
				}			
				else
				{		   
					// if($contStatus=="LCL")
					// {
					// 	 $sqlContainer="SELECT igm_supplimentary_detail.id,IFNULL(total_pack,0) AS rcv_pack,igm_masters.Vessel_Name,igm_supplimentary_detail.Import_Rotation_No,						igm_sup_detail_container.cont_number,Pack_Marks_Number,shed_loc,shed_yard,Description_of_Goods,Notify_name,IFNULL(shed_tally_info.verify_number,0) AS verify_number,
					// 	IFNULL(shed_tally_info.id,0) AS verify_id,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,
					// 	igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_height,cont_seal_number,igm_sup_detail_container.cont_status,verify_other_data.cnf_lic_no,
					// 	verify_other_data.cnf_name,verify_other_data.be_no,verify_other_data.be_date,shed_tally_info.total_pack AS rcvTally,shed_tally_info.rcv_unit,
					// 	igm_sup_detail_container.Cont_gross_weight AS Cont_gross_weight
					// 	FROM  igm_supplimentary_detail
					// 	INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
					// 	INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
					// 	LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
					// 	LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
					// 	WHERE igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no' AND igm_supplimentary_detail.BL_No='$ddl_bl_no' 
					// 	GROUP BY igm_sup_detail_container.id";
					// }
					// else if($contStatus=="FCL")
					// {										
					// 	$sqlContainer="SELECT igm_details.id,igm_masters.Vessel_Name,igm_details.Import_Rotation_No,igm_detail_container.cont_number,Pack_Marks_Number,Description_of_Goods,Notify_name,igm_details.Pack_Number,igm_details.Pack_Description,igm_details.BL_No,igm_detail_container.cont_size,igm_detail_container.cont_height,igm_detail_container.cont_seal_number,igm_detail_container.cont_status,certify_info_fcl.cnf_lic_no,
					// 	certify_info_fcl.cnf_name,certify_info_fcl.be_no,certify_info_fcl.be_date,IFNULL(certify_info_fcl.id,0) AS verify_id,verify_number,
					// 	igm_detail_container.Cont_gross_weight AS Cont_gross_weight
					// 	FROM  igm_details
					// 	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					// 	INNER JOIN igm_masters ON igm_details.IGM_id=igm_masters.id
					// 	LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
					// 	LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
					// 	WHERE igm_details.Import_Rotation_No='$ddl_imp_rot_no' AND igm_details.BL_No='$ddl_bl_no' 
					// 	GROUP BY igm_detail_container.id";												
					// }
					
					$sqlContainer = "";
					
					if($contStatus=="LCL")
					{
						$sqlContainer="SELECT igm_supplimentary_detail.id,IFNULL(total_pack,0) AS rcv_pack,igm_masters.Vessel_Name,
						igm_supplimentary_detail.Import_Rotation_No,igm_sup_detail_container.cont_height,
						igm_sup_detail_container.cont_seal_number,
						igm_sup_detail_container.cont_number,Pack_Marks_Number,shed_loc,shed_yard,Description_of_Goods,Notify_name,IFNULL(shed_tally_info.verify_number,0) AS verify_number,
						IFNULL(shed_tally_info.id,0) AS verify_id,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,
						igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_status,verify_other_data.cnf_lic_no,
						verify_other_data.cnf_name,verify_other_data.be_no,verify_other_data.be_date,shed_tally_info.total_pack AS rcvTally,shed_tally_info.rcv_unit,
						igm_sup_detail_container.Cont_gross_weight AS Cont_gross_weight,appraisement_info.custom_appraiser,appraisement_info.custom_appraiser_mobile,'' AS jetty_sirkar_lic, '' AS jetty_sirkar_name, '' AS jetty_sirkar_mobile
						FROM  igm_supplimentary_detail
						INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
						INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
						LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
						LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
						LEFT JOIN appraisement_info ON appraisement_info.rotation = igm_supplimentary_detail.Import_Rotation_No AND appraisement_info.BL_NO = igm_supplimentary_detail.BL_No
						WHERE igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no' AND igm_supplimentary_detail.BL_No='$ddl_bl_no' 
						GROUP BY igm_sup_detail_container.id";
					}
					else if($contStatus=="FCL")
					{							
						$sqlContainer="SELECT igm_details.id,igm_masters.Vessel_Name,igm_details.Import_Rotation_No,
						igm_detail_container.cont_number,Pack_Marks_Number,Description_of_Goods,Notify_name,
						IFNULL(certify_info_fcl.id,0) AS verify_id,igm_details.Pack_Number,igm_details.Pack_Description,
						igm_details.BL_No,igm_detail_container.cont_size,igm_detail_container.cont_height,igm_detail_container.cont_seal_number,igm_detail_container.cont_status,certify_info_fcl.cnf_lic_no,
						certify_info_fcl.cnf_name,certify_info_fcl.be_no,certify_info_fcl.be_date,
						igm_detail_container.Cont_gross_weight AS Cont_gross_weight,carpainter_use,hosting_charge,extra_movement,scale_for,
						appraisement_info_fcl.jetty_sirkar_lic,appraisement_info_fcl.jetty_sirkar_name,appraisement_info_fcl.jetty_sirkar_mobile,
						appraisement_info_fcl.custom_appraiser,appraisement_info_fcl.custom_appraiser_mobile
						FROM  igm_details
						INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						INNER JOIN igm_masters ON igm_details.IGM_id=igm_masters.id
						LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
						LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
						WHERE igm_details.Import_Rotation_No='$ddl_imp_rot_no' AND igm_details.BL_No='$ddl_bl_no' 
						GROUP BY igm_detail_container.id";
					}
					// echo $sqlContainer;
					// return;
					$rtnContainerList = "";
					$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);

					// will do work to receive agent_name, mobile_number, submit_by 

					if($this->input->post('type'))
					{
						$agent_query = "SELECT release_order_record.entry_by,vcms_vehicle_agent.agent_code,vcms_vehicle_agent.agent_name,vcms_vehicle_agent.mobile_number
						FROM release_order_record
						LEFT JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id = release_order_record.agent_id
						WHERE imp_rot='$ddl_imp_rot_no' AND bl_no='$ddl_bl_no'";
						$agent_rslt = $this->bm->dataSelectDb1($agent_query);
						if(count($agent_rslt)>0)
						{
							$rtnContainerList[0]['jetty_sirkar_lic'] = $agent_rslt[0]['agent_code'];
							$rtnContainerList[0]['jetty_sirkar_name'] = $agent_rslt[0]['agent_name'];
							$rtnContainerList[0]['jetty_sirkar_mobile'] = $agent_rslt[0]['mobile_number'];
						}
					}
					


					$data['rtnContainerList']=$rtnContainerList;

					$getId="";
					$containerNo="";
					$verify_id="";

					for($z=0;$z<count($rtnContainerList);$z++){
						$getId=$rtnContainerList[$z]['id'];
						$containerNo=$rtnContainerList[$z]['cont_number'];
						$verify_id=$rtnContainerList[$z]['verify_id'];
					}

					// $queryGetUnit for LCL Only
					if($contStatus=="LCL")
					{
							$queryGetUnit="select SUM(IFNULL(shed_tally_info.total_pack,0)) as rcvTally,shed_tally_info.rcv_unit,shed_tally_info.shed_loc
						from  igm_supplimentary_detail
						inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
						inner join igm_masters on igm_supplimentary_detail.igm_master_id=igm_masters.id
						left join  shed_tally_info on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
						left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
						where igm_supplimentary_detail.id='$getId'
						group by shed_tally_info.shed_loc,shed_tally_info.rcv_unit";
						$rtnUnitList = $this->bm->dataSelectDb1($queryGetUnit);
						$data['rtnUnitList']=$rtnUnitList;
					}
					
					if($contStatus=="LCL")
					{
							$sqlAppraisementQuery="select equipment,appraise_date,carpainter_use,hosting_charge,extra_movement,scale_for,equipment_id from appraisement_info 
						where rotation='$ddl_imp_rot_no' and BL_NO='$ddl_bl_no'";
						$rtnAppraisementList = $this->bm->dataSelectDb1($sqlAppraisementQuery);
					}
					else if($contStatus=="FCL")		// remove this part if appraisement can be done multiple times - see another comment below
					{
						$sqlAppraisementQuery="select equipment,appraise_date,carpainter_use,hosting_charge,extra_movement,scale_for,equipment_id from appraisement_info_fcl 
						where rotation='$ddl_imp_rot_no' and BL_NO='$ddl_bl_no'";
						$rtnAppraisementList = $this->bm->dataSelectDb1($sqlAppraisementQuery);
					}
					//return;
					if($contStatus=="LCL" && count($rtnAppraisementList)>0)
					{
						$appraiseFlag=1;
					}
					else{
						$appraiseFlag=0;
					}

					//$data['rtnAppraisementList']=$rtnAppraisementList;

					$used_equipment="";
					$equip_charge="";
					$appraise_date="";		   
					$carpainter_use="";
					$hosting_charge="";
					$extra_movement="";
					$scale_for="";

					for($z=0;$z<count($rtnAppraisementList);$z++){
						$used_equipment=$rtnAppraisementList[$z]['equipment_id'];
						$equip_charge=$rtnAppraisementList[$z]['equipment'];
						$appraise_date=$rtnAppraisementList[$z]['appraise_date'];		   
						$carpainter_use=$rtnAppraisementList[$z]['carpainter_use'];
						$hosting_charge=$rtnAppraisementList[$z]['hosting_charge'];
						$extra_movement=$rtnAppraisementList[$z]['extra_movement'];
						$scale_for=$rtnAppraisementList[$z]['scale_for'];
					}

					$rtnValue=1;
					if($rtnValue<1)
					{
						$msg="<font color='red'><b>CARGO IS NOT UNSTUFFED.</b></font>";
					}
					else
					{
						// if($verify_id==0)
						// {
						// 	$msg="<font color='red'><b>NOT VERIFIED YET</b></font>";
						// }
					} 

					$getUsedEquipmentQuery= "SELECT equipment_id,equipment_name,equipment_charge,remarks FROM used_equipment ORDER BY equipment_id ASC";
					$getUsedEquipment = $this->bm->dataSelectDb1($getUsedEquipmentQuery);

					$data['getUsedEquipment']=$getUsedEquipment;

					$data['used_equipment']=$used_equipment;
					$data['equip_charge']=@$equip_charge;
					$data['appraise_date']=$appraise_date;
					$data['carpainter_use']=$carpainter_use;
					$data['hosting_charge']=$hosting_charge;
					$data['extra_movement']=$extra_movement;
					$data['scale_for']=$scale_for;

					$data['unstuff_flag']=$rtnValue;
					$data['appraiseFlag']=$appraiseFlag;
					$data['verify_id']=$verify_id;
					//$data['verify_num']=$verify_num;
					$data['cnf_name']=@$cnf_name;
					$data['ddl_imp_rot_no']=$ddl_imp_rot_no;
					$data['ddl_bl_no']=$ddl_bl_no;
					$data['contStatus']=$contStatus;
				}
				$data['contStatus']=$contStatus;
				$data['msg']=$msg;
			}
			else
			{
				$data['contStatus']="";
				$data['msg']="BL number not Correct for this Rotation: ".$ddl_imp_rot_no;
			}
			// var_dump($data);
			// die();
			$data['title']="APPRAISEMENT SECTION...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('appraisementSectionHTML',$data);
			$this->load->view('jsAssets');
		}
	}

	function appraisementVerify()
	{
		
		$session_id = $this->session->userdata('value');

		$igm_sup_detail_id=$this->input->post('id');

		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$type = $this->input->post('type');
			$id = $this->input->post('id');

			$login_id = $this->session->userdata('login_id');
			$userip=$_SERVER['REMOTE_ADDR'];

			$ddl_imp_rot_no=$this->input->post('ddl_imp_rot_no');
			$ddl_bl_no=$this->input->post('ddl_bl_no');			
			
			$used_equipment=$this->input->post('used_equipment');
			$equip_charge=$this->input->post('equip_charge');		   
			$appraise_date=$this->input->post('appraise_date');

			$carpainter_use=$this->input->post('carpainter_use');
			$hosting_charge=$this->input->post('hosting_charge');
			$extra_movement=$this->input->post('extra_movement');
			$scale_for=$this->input->post('scale_for');
			
			$cnfLicense=$this->input->post('cnfLicense');
			$cnfName=$this->input->post('cnfName');
			$beNo=$this->input->post('beNo');
			$beDate=$this->input->post('beDate');
			
			$appraiser_mobile=$this->input->post('appraiser_mobile');
			$appraiser_name=$this->input->post('appraiser_name');
			
			$jetty_sarkar_lic=$this->input->post('jetty_sarkar_lic');
			$jetty_sarkar_name=$this->input->post('jetty_sarkar_name');
			$jetty_sarkar_mob=$this->input->post('jetty_sarkar_mob');
			$contStatus="";
			$sqlContainerType="SELECT cont_status AS rtnValue
			FROM  igm_supplimentary_detail
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no' AND igm_supplimentary_detail.BL_No='$ddl_bl_no'
			GROUP BY igm_sup_detail_container.id

			UNION

			SELECT cont_status AS rtnValue
			FROM  igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$ddl_imp_rot_no' AND igm_details.BL_No='$ddl_bl_no'
			GROUP BY igm_detail_container.id";
					
			$contStatusRslt=$this->bm->dataSelectDb1($sqlContainerType);
			for($i=0; $i<count($contStatusRslt); $i++)
			{
				$contStatus=$contStatusRslt[$i]['rtnValue'];
			}

			//$contStatus = $this->bm->dataReturnDb1($sqlContainerType); rtnUnitList
			
			if($contStatus=="LCL")
			{
			//	$chkAppraisement="select 1 as rtnVal from appraisement_info where rotation='$ddl_imp_rot_no' and BL_NO='$ddl_bl_no'";
				$chkAppraisement="select count(*) as rtnVal from appraisement_info where rotation='$ddl_imp_rot_no' and BL_NO='$ddl_bl_no'";
				$rtnAppraisement = $this->bm->dataSelectDb1($chkAppraisement);
				if($rtnAppraisement[0]['rtnVal']== 1)		
				{
				//	echo "update appraisement_info";
					$strInsertVerifyOther = "UPDATE appraisement_info
					SET equipment='$equip_charge',appraise_date='$appraise_date',
					carpainter_use='$carpainter_use',hosting_charge='$hosting_charge'
					,extra_movement='$extra_movement',scale_for='$scale_for',
					jetty_sirkar_lic='$jetty_sarkar_lic',jetty_sirkar_name='$jetty_sarkar_name',
					jetty_sirkar_mobile='$jetty_sarkar_mob',custom_appraiser='$appraiser_name',
					custom_appraiser_mobile='$appraiser_mobile',
					user_id='$login_id',user_ip='$userip',last_update=NOW(),equipment_id='$used_equipment'
					WHERE rotation='$ddl_imp_rot_no' AND BL_NO='$ddl_bl_no'";
					$sucMsg = "APPRAISEMENT UPDATED SUCCESSFULLY";
					$unSucMsg = "APPRAISEMENT NOT UPDATED";
				}
				else
				{
				//	echo "inside insert into appraisement_info";
					$strInsertVerifyOther = "INSERT INTO appraisement_info(rotation,BL_NO,equipment,appraise_date,carpainter_use,hosting_charge,extra_movement,scale_for,jetty_sirkar_lic,jetty_sirkar_name,jetty_sirkar_mobile,custom_appraiser,custom_appraiser_mobile,user_id,user_ip,last_update,equipment_id) 
					VALUES('$ddl_imp_rot_no','$ddl_bl_no','$equip_charge','$appraise_date','$carpainter_use','$hosting_charge','$extra_movement','$scale_for','$jetty_sarkar_lic','$jetty_sarkar_name','$jetty_sarkar_mob','$appraiser_name','$appraiser_mobile','$login_id','$userip',NOW(),'$used_equipment')";
					
					$sucMsg = "APPRAISEMENT SAVE SUCCESSFULLY";
					$unSucMsg = "APPRAISEMENT NOT SAVE";
				}
				
				$stat = $this->bm->dataInsertDB1($strInsertVerifyOther);
			}
			
			if($contStatus=="LCL")
			{
				$chkVerifyOtherData="SELECT shed_tally_info.id
				from  igm_supplimentary_detail
				inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				inner join igm_masters on igm_supplimentary_detail.igm_master_id=igm_masters.id
				left join  shed_tally_info on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
				where igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no' and igm_supplimentary_detail.BL_No='$ddl_bl_no'";
				$chkVerifyOther = $this->bm->dataSelectDb1($chkVerifyOtherData);
				$shedTallyID=$chkVerifyOther[0]['id'];

				// $cnfLicense=$this->input->post('cnfLicense');
				// $cnfName=$this->input->post('cnfName');
				// $beNo=$this->input->post('beNo');
				// $beDate=$this->input->post('beDate');

				//	$chkOtherQuery="SELECT 1 as rtnVal from verify_other_data where shed_tally_id='$shedTallyID'";
				$chkOtherQuery="SELECT count(*) as rtnVal from verify_other_data where shed_tally_id='$shedTallyID'";
				$rtnChkOther = $this->bm->dataSelectDb1($chkOtherQuery);
				$shedRtnValue=$rtnChkOther[0]['rtnVal'];
				// }
			
				// if($contStatus=="LCL")
				// {
				if($shedRtnValue == 1)
				{
					$strInsertVerifyOtherData = "update verify_other_data set cnf_lic_no='$cnfLicense',cnf_name='$cnfName',
					be_no='$beNo',be_date='$beDate'
					where shed_tally_id='$shedTallyID'";
					//$sucMsg = "APPRAISEMENT UPDATED SUCCESSFULLY";
					//$unSucMsg = "APPRAISEMENT NOT UPDATED";
				}
				else
				{
					$strInsertVerifyOtherData = "insert into verify_other_data ( shed_tally_id,cnf_lic_no,cnf_name,be_no,be_date,last_update,update_by,user_ip) 
					values ('$shedTallyID','$cnfLicense','$cnfName','$beNo','$beDate',now(),'$login_id','$userip')";									
					//$sucMsg = "APPRAISEMENT SAVE SUCCESSFULLY";
					//$unSucMsg = "APPRAISEMENT NOT SAVE";
				}
				//echo $strInsertVerifyOtherData;
				$statOther = $this->bm->dataInsertDB1($strInsertVerifyOtherData);

			}
			else if($contStatus=="FCL")
			{
				$sqlFCLappraisement="SELECT COUNT(*) AS rtnValue FROM appraisement_info_fcl WHERE rotation='$ddl_imp_rot_no' AND BL_NO='$ddl_bl_no'";
				$cntFCLappraisement = $this->bm->dataReturnDb1($sqlFCLappraisement);
				if($cntFCLappraisement == 0)
				{
					$strInsertFCLappraisement = "INSERT INTO appraisement_info_fcl (rotation,BL_NO,cnf_lic_no,cnf_name,be_no,be_date,equipment,appraise_date,carpainter_use,hosting_charge,
					extra_movement,scale_for,jetty_sirkar_lic,jetty_sirkar_name,jetty_sirkar_mobile,custom_appraiser,custom_appraiser_mobile,user_id,
					user_ip,last_update,equipment_id) 
					VALUES ('$ddl_imp_rot_no','$ddl_bl_no','$cnfLicense','$cnfName','$beNo','$beDate','$equip_charge',
					'$appraise_date','$carpainter_use','$hosting_charge','$extra_movement','$scale_for','$jetty_sarkar_lic','$jetty_sarkar_name',
					'$jetty_sarkar_mob','$appraiser_name','$appraiser_mobile','$login_id','$userip',NOW(),'$used_equipment')";
					$resInsertFCLappraisement = $this->bm->dataInsertDB1($strInsertFCLappraisement);
					$stat=$resInsertFCLappraisement;

					$certify_Query = "UPDATE certify_info_fcl SET be_date = '$beDate' WHERE rotation_no = '$ddl_imp_rot_no' AND bl_no = '$ddl_bl_no'";
					$rsltCertify = $this->bm->dataUpdateDB1($certify_Query);
					
					$sucMsg = "APPRAISEMENT SAVE SUCCESSFULLY";
					$unSucMsg = "APPRAISEMENT NOT SAVE";
				}
				else
				{
					$strUpdateFCLappraisement = "update appraisement_info_fcl set cnf_lic_no='$cnfLicense',cnf_name='$cnfName',
					be_no='$beNo',be_date='$beDate',equipment='$equip_charge',appraise_date='$appraise_date',
					carpainter_use='$carpainter_use',hosting_charge='$hosting_charge',extra_movement='$extra_movement',scale_for='$scale_for',
					jetty_sirkar_lic='$jetty_sarkar_lic',jetty_sirkar_name='$jetty_sarkar_name',
					jetty_sirkar_mobile='$jetty_sarkar_mob',custom_appraiser='$appraiser_name',custom_appraiser_mobile='$appraiser_mobile',
					user_id='$login_id',user_ip='$userip',
					last_update=NOW(),equipment_id='$used_equipment'
					where rotation='$ddl_imp_rot_no' and BL_NO='$ddl_bl_no'";
					$resUpdateFCLappraisement = $this->bm->dataUpdateDB1($strUpdateFCLappraisement);
					$stat=$resUpdateFCLappraisement;

					$certify_Query = "UPDATE certify_info_fcl SET be_date = '$beDate' WHERE rotation_no = '$ddl_imp_rot_no' AND bl_no = '$ddl_bl_no'";
					$rsltCertify = $this->bm->dataUpdateDB1($certify_Query);
					
					$sucMsg = "APPRAISEMENT UPDATED SUCCESSFULLY";
					$unSucMsg = "APPRAISEMENT NOT UPDATED";
				}
			}

			$data['msg']="";
			if($stat==1){
				if($type == "RO" && strlen($id)>0)
				{
					$RO_update_query = "UPDATE release_order_record SET appraise_st = 1, appraise_at = NOW() WHERE id = '$id'";
					$this->bm->dataUpdateDB1($RO_update_query );
				}
				$msgPO="<font color='green'><b>".$sucMsg."</font>";
			}else{
				$msgPO="<font color='red'><b>".$unSucMsg."<font color='red'><b>";
			}

			if($contStatus=="LCL")
			{
				$sqlContainer="SELECT igm_supplimentary_detail.id,IFNULL(total_pack,0) AS rcv_pack,igm_masters.Vessel_Name,
				igm_supplimentary_detail.Import_Rotation_No,igm_sup_detail_container.cont_height,
				igm_sup_detail_container.cont_seal_number,
				igm_sup_detail_container.cont_number,Pack_Marks_Number,shed_loc,shed_yard,Description_of_Goods,Notify_name,IFNULL(shed_tally_info.verify_number,0) AS verify_number,
				IFNULL(shed_tally_info.id,0) AS verify_id,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,
				igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_status,verify_other_data.cnf_lic_no,
				verify_other_data.cnf_name,verify_other_data.be_no,verify_other_data.be_date,shed_tally_info.total_pack AS rcvTally,shed_tally_info.rcv_unit,
				igm_sup_detail_container.Cont_gross_weight AS Cont_gross_weight,appraisement_info.custom_appraiser,appraisement_info.custom_appraiser_mobile
				FROM  igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
				LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				LEFT JOIN appraisement_info ON appraisement_info.rotation = igm_supplimentary_detail.Import_Rotation_No AND appraisement_info.BL_NO = igm_supplimentary_detail.BL_No
				WHERE igm_supplimentary_detail.Import_Rotation_No='$ddl_imp_rot_no' AND igm_supplimentary_detail.BL_No='$ddl_bl_no' 
				GROUP BY igm_sup_detail_container.id";
			}
			else if($contStatus=="FCL")
			{							
				$sqlContainer="SELECT igm_details.id,igm_masters.Vessel_Name,igm_details.Import_Rotation_No,
				igm_detail_container.cont_number,Pack_Marks_Number,Description_of_Goods,Notify_name,
				IFNULL(certify_info_fcl.id,0) AS verify_id,igm_details.Pack_Number,igm_details.Pack_Description,
				igm_details.BL_No,igm_detail_container.cont_size,igm_detail_container.cont_height,igm_detail_container.cont_seal_number,igm_detail_container.cont_status,certify_info_fcl.cnf_lic_no,
				certify_info_fcl.cnf_name,certify_info_fcl.be_no,certify_info_fcl.be_date,
				igm_detail_container.Cont_gross_weight AS Cont_gross_weight,
				carpainter_use,hosting_charge,extra_movement,scale_for,
				appraisement_info_fcl.jetty_sirkar_lic,appraisement_info_fcl.jetty_sirkar_name,appraisement_info_fcl.jetty_sirkar_mobile,
				appraisement_info_fcl.custom_appraiser,appraisement_info_fcl.custom_appraiser_mobile
				FROM  igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				INNER JOIN igm_masters ON igm_details.IGM_id=igm_masters.id
				LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
				LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
				WHERE igm_details.Import_Rotation_No='$ddl_imp_rot_no' AND igm_details.BL_No='$ddl_bl_no' 
				GROUP BY igm_detail_container.id";
			}
			
			//echo $cont_seal_number;
			$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
			$data['rtnContainerList']=$rtnContainerList;
			
			$getId="";
			$containerNo="";
			$verify_id="";

			for($z=0;$z<count($rtnContainerList);$z++){
				$getId=$rtnContainerList[$z]['id'];
				$containerNo=$rtnContainerList[$z]['cont_number'];
				$verify_id=$rtnContainerList[$z]['verify_id'];
			}

			if($contStatus=="LCL")
			{
				$queryGetUnit="select SUM(IFNULL(shed_tally_info.total_pack,0)) as rcvTally,shed_tally_info.rcv_unit,
				shed_tally_info.shed_loc
				from  igm_supplimentary_detail
				inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				inner join igm_masters on igm_supplimentary_detail.igm_master_id=igm_masters.id
				left join  shed_tally_info on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
				where igm_supplimentary_detail.id='$getId'
				group by shed_tally_info.shed_loc,shed_tally_info.rcv_unit";
				$rtnUnitList = $this->bm->dataSelectDb1($queryGetUnit);
				$data['rtnUnitList']=$rtnUnitList;
			}
			
			//$rtnAppraisementList = "";
			//echo count($rtnAppraisementList);

			$appraiseFlag=0;

			$used_equipment="";
			$equip_charge="";
			$appraise_date="";		   
			$carpainter_use="";
			$hosting_charge="";
			$extra_movement="";
			$scale_for="";
			

			if($contStatus=="LCL")
			{
				$sqlAppraisementQuery="select equipment,appraise_date,carpainter_use,hosting_charge,extra_movement,scale_for,equipment_id from appraisement_info 
				where rotation='$ddl_imp_rot_no' and BL_NO='$ddl_bl_no'";
				$rtnAppraisementList = $this->bm->dataSelectDb1($sqlAppraisementQuery);

				$appraiseFlag=1;
			}
			else if($contStatus=="FCL")	
			{
				$sqlAppraisementQuery="select equipment,appraise_date,carpainter_use,hosting_charge,extra_movement,scale_for,equipment_id from appraisement_info_fcl 
				where rotation='$ddl_imp_rot_no' and BL_NO='$ddl_bl_no'";
				$rtnAppraisementList = $this->bm->dataSelectDb1($sqlAppraisementQuery);
			}

			for($z=0;$z<count($rtnAppraisementList);$z++){
				$used_equipment=$rtnAppraisementList[$z]['equipment_id'];
				$equip_charge=$rtnAppraisementList[$z]['equipment'];
				$appraise_date=$rtnAppraisementList[$z]['appraise_date'];		   
				$carpainter_use=$rtnAppraisementList[$z]['carpainter_use'];
				$hosting_charge=$rtnAppraisementList[$z]['hosting_charge'];
				$extra_movement=$rtnAppraisementList[$z]['extra_movement'];
				$scale_for=$rtnAppraisementList[$z]['scale_for'];
			}
			
			$data['rtnAppraisementList']=$rtnAppraisementList;
			
			$getUsedEquipmentQuery= "SELECT equipment_id,equipment_name,equipment_charge,remarks FROM used_equipment ORDER BY equipment_id ASC";
			$getUsedEquipment = $this->bm->dataSelectDb1($getUsedEquipmentQuery);

			$data['getUsedEquipment']=$getUsedEquipment;

			$data['used_equipment']=$used_equipment;
			$data['appraise_date']=$appraise_date;
			$data['carpainter_use']=$carpainter_use;
			$data['hosting_charge']=$hosting_charge;
			$data['extra_movement']=$extra_movement;
			$data['scale_for']=$scale_for;

			$data['unstuff_flag']=1;
			$data['appraiseFlag']=$appraiseFlag;
			$data['verify_id']=$verify_id;
			$data['cnf_name']=$cnfName;
			$data['ddl_imp_rot_no']=$ddl_imp_rot_no;
			$data['ddl_bl_no']=$ddl_bl_no;
			$data['msg']="";
			$data['msgPO']=$msgPO;
			$data['title']="APPRAISEMENT SECTION...";
			$data['contStatus']=$contStatus;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('appraisementSectionHTML',$data);
			$this->load->view('jsAssets');
		}
	}

	function deliveryEntryFormByWHClerk($rot = null, $blNo = null, $verifyInfo = null)
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{		
			if($this->uri->segment(6)=="doForm")
			{
				$blNo=str_replace("_","/",$this->uri->segment(3));
				$rotyear=$this->uri->segment(4);
				$rot=$this->uri->segment(5);
				$data['doFormFlag'] = 1;
				$data['blNo']=$blNo;
				$data['rotNo']=$rotyear.'/'.$rot;				
			}
			else if($this->input->post('ddl_imp_rot_no'))
			{
				$blNo = $this->input->post('ddl_bl_no');
				$rot = $this->input->post('ddl_imp_rot_no');
				$data['doFormFlag'] = 2;
				$data['blNo'] = $blNo;
				$data['rotNo'] = $rot;
			}
			else if(!is_null($blNo) && !is_null($rot))
			{
				$data['doFormFlag'] = 2;
				$data['blNo'] = $blNo;
				$data['rotNo'] = $rot;
				$data['verifyInfo'] = $verifyInfo;
			}
			else
			{
				$data['doFormFlag']=0;
			}

			$data['title']="ONE STOP SERVICE CENTER";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('deliveryEntryFormByWHClerkHTML',$data);
			$this->load->view('jsAssets');
		}
	}

	function xml_conversion_action()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			// if($this->input->post('view')=="View")
			// {
				$login_id = $this->session->userdata('login_id');
				$this->load->library('M_pdf');
				$mpdf->use_kwt = true;
				
				$flag=$this->uri->segment(3);
				
				if($flag==1)
				{
					$office_code=$this->uri->segment(4);
					$c_nubmber=$this->uri->segment(5);
					$xml_date=$this->uri->segment(6);
				}
				else
				{
					$office_code=$this->input->post('office_code');
					$c_nubmber=$this->input->post('c_nubmber');
					$xml_date=$this->input->post('xml_date');
				}							
				
				$sql_show_report="SELECT * 
				FROM sad_info
				INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
				WHERE sad_info.office_code='$office_code' AND reg_no='$c_nubmber' AND reg_date='$xml_date'";
				//die();
				$rslt_show_report=$this->bm->dataSelectDb1($sql_show_report);
				
				$this->data['rslt_show_report']=$rslt_show_report;
							
				$dec_ref_no=$rslt_show_report[0]['dec_ref_no'];
				$dec_ref_no=substr($dec_ref_no,1);
				$dec_ref_no="/C".$dec_ref_no;
				
				$this->data['dec_ref_no']=$dec_ref_no;				
				
				//--						
				$vsl_rot=$rslt_show_report[0]['manif_num'];	
				//$vsl_rot=str_replace(" ","/",$vsl_rot);				
				$cnt=substr_count($vsl_rot," ");
		
				if($cnt==1)
				{
					$index=strpos($vsl_rot," ");				
					$first_str=substr($vsl_rot,0,$index);			
					$last_str=substr($vsl_rot,$index);
					$last_str=(int)$last_str;			
					$vsl_rot=$first_str."/".$last_str;	
				}
				else if($cnt==2)
				{
					$index=strpos($vsl_rot," ");
					$vsl_rot=trim(substr($vsl_rot,$index));
					$index=strpos($vsl_rot," ");
					$first_str=substr($vsl_rot,0,$index);			
					$last_str=substr($vsl_rot,$index);
					$last_str=(int)$last_str;					
					$vsl_rot=$first_str."/".$last_str;	
				}
				//--							
				
				$sql_vsl_name="SELECT Vessel_Name AS rtnValue FROM igm_masters WHERE Import_Rotation_No='$vsl_rot'";
			//	return;
				$vsl_name=$this->bm->dataReturnDb1($sql_vsl_name);
				$this->data['vsl_name']=$vsl_name;
				
				//cont list - start
				$sql_cont_info="SELECT * FROM sad_container
				INNER JOIN sad_info ON sad_info.id=sad_container.sad_id 
				WHERE office_code='$office_code' AND reg_no='$c_nubmber' AND reg_date='$xml_date'";
				
				$rslt_cont_info=$this->bm->dataSelectDb1($sql_cont_info);
				
				//--
				$this->data['rslt_cont_info']=$rslt_cont_info;			
				//cont list - end...
				
				$pdf = $this->m_pdf->load();
				
				////$pdf->SetWatermarkText('CPA CTMS');
				//$pdf->showWatermarkText = true;
				
				$stylesheet = file_get_contents('resources/styles/xml_conversion.css'); // external css							
				
				/* $pdf->AddPage('P', // L - landscape, P - portrait
							'', '', '', '',
							5, // margin_left
							5, // margin right
							10, // margin top
							10, // margin bottom
							10, // margin header
							10); // margin footer */
						
				$html=$this->load->view('xml_conversion_pdf',$this->data, true);
									
				$pdfFilePath ="xml_conversion_pdf-".time()."-download.pdf";
				
				$pdf->useSubstitutions = true; 					
				
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
						
				$pdf->Output($pdfFilePath, "I");				
			// }
			// else if($this->input->post('view')=="View Container")
			// {
				// $login_id = $this->session->userdata('login_id');
				// $this->load->library('m_pdf');
				// $mpdf->use_kwt = true;
				
				// $office_code=$this->input->post('office_code');
				// $c_nubmber=$this->input->post('c_nubmber');
				// $xml_date=$this->input->post('xml_date');
				
				// $sql_cont_info="SELECT * FROM sad_container
				// INNER JOIN sad_info ON sad_info.id=sad_container.sad_id 
				// WHERE office_code='$office_code' AND reg_no='$c_nubmber' AND reg_date='$xml_date'";
				
				// $rslt_cont_info=$this->bm->dataSelectDb1($sql_cont_info);
				
				// //--
				// $this->data['rslt_cont_info']=$rslt_cont_info;								
				
				// $pdf = $this->m_pdf->load();
				
				// //$pdf->SetWatermarkText('CPA CTMS');
				// $pdf->showWatermarkText = true;
				
				// $stylesheet = file_get_contents('resources/styles/xml_conversion.css'); // external css
				
				// $pdf->AddPage('P', // L - landscape, P - portrait
							// '', '', '', '',
							// 5, // margin_left
							// 5, // margin right
							// 10, // margin top
							// 10, // margin bottom
							// 10, // margin header
							// 10); // margin footer
						
				// $html=$this->load->view('xml_conversion_cont_info',$this->data, true);
											
				// $pdfFilePath ="xml_conversion_cont_info-".time()."-download.pdf";
				
				// $pdf->useSubstitutions = true; 					
				
				// $pdf->WriteHTML($stylesheet,1);
				// $pdf->WriteHTML($html,2);
						
				// $pdf->Output($pdfFilePath, "I");	
				// //--				
			// }
		}
	}

	function deliveryEntryForm()
	{
		$oneStop=$this->input->post('oneStopPoint');
		$shedTallyInfoID=$this->input->post('shedTallyInfoID');
		$cnf_lic=$this->input->post('cnf_lic');
		$cnfName=$this->input->post('cnfName');
		$paperFileDate=$this->input->post('paperFileDate');
		$exitNoteNum=strtoupper($this->input->post('exitNoteNum'));
		$date=$this->input->post('date');
		$truckNum=$this->input->post('truckNum');
		$billOfEntryNo=$this->input->post('billOfEntryNo');
		$billOfEntryDate=$this->input->post('billOfEntryDate'); 
		$invoiceAmount=$this->input->post('invoiceAmount'); 
		$blNo=$this->input->post('blNo');
		$rotNo=$this->input->post('rotNo');
		$doNo=$this->input->post('doNo');
		$doDate=$this->input->post('doDate');
		$validUpToDate=$this->input->post('validUpToDate');
		$commLandDate=$this->input->post('commLandDate');
		$cusOrderNo=strtoupper($this->input->post('cusOrderNo'));
		$cusOrderDate=$this->input->post('cusOrderDate');
	
		$login_id = $this->session->userdata('login_id');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		
		$verifyNo=$this->input->post('verifyNo');
		$regNumber=$this->input->post('seaNumber');
		$verifyStatus=0;
		/*
		# if returns result for reg_no then FCL
		# if that then save without generating verification no
		*/
		//return;				

		if($this->input->post('save'))  
		{
			
			$sql_contStatus="SELECT cont_status AS rtnValue
			FROM  igm_supplimentary_detail
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
			LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'
			GROUP BY igm_sup_detail_container.id

			UNION

			SELECT cont_status AS rtnValue
			FROM  igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN  shed_tally_info ON shed_tally_info.igm_detail_id=igm_details.id
			LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
			WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo'
			GROUP BY igm_detail_container.id";
			//$contStatus=$this->bm->dataReturnDb1($sql_contStatus);
			$sql_contStatusRslt=$this->bm->dataSelectDb1($sql_contStatus);
			$contStatus=$sql_contStatusRslt[0]['rtnValue'];
			
			$igm_detail_id = "";
			$igm_sup_detail_id = "";				
			$dtl_id = "";
			$igm_type_flag = "";
			$igmDtlId ="";
			if($contStatus=="LCL")
			{
				$checkQuery="select verify_number,igm_detail_id,igm_sup_detail_id from shed_tally_info 
							where id='$shedTallyInfoID'";				  
				$selectStat = $this->bm->dataSelectDb1($checkQuery);
				$chkVal=$selectStat[0]['verify_number'];
				
				for($i=0;$i<count($selectStat);$i++)
				{
					$igm_detail_id=$selectStat[$i]['igm_detail_id'];
					$igm_sup_detail_id=$selectStat[$i]['igm_sup_detail_id'];
				}
				
				if($igm_detail_id != "")
				{
					$dtl_id = $igm_detail_id;
					$igm_type_flag = "dtl";
				}
				else
				{
					$dtl_id = $igm_sup_detail_id;
					$igm_type_flag = "sup_dtl";
				}
			}
			else
			{
				$sqlIglDtlId="SELECT id as rtnValue FROM igm_details 
							WHERE Import_Rotation_No='$rotNo' AND BL_No='$blNo'";
				//$igmDtlId = $this->bm->dataReturnDb1($sqlIglDtlId);
				$igmDtlIdRslt = $this->bm->dataSelectDb1($sqlIglDtlId);
				$igmDtlId=$igmDtlIdRslt [0]['rtnValue'];
				$igm_detail_id = $igmDtlId;
				$igm_type_flag = "dtl";

				if(count($igmDtlIdRslt)==0){
					$sqlIglDtlId="SELECT id AS rtnValue 
					FROM igm_supplimentary_detail 
					WHERE Import_Rotation_No='$rotNo' AND BL_No='$blNo'";
					$igmDtlIdRslt = $this->bm->dataSelectDb1($sqlIglDtlId);
					$igmDtlId=$igmDtlIdRslt [0]['rtnValue'];
					$igm_detail_id = $igmDtlId;
					$igm_type_flag = "sup_dtl";
				}
				
			}
			
			$maxVerifysql="SELECT IFNULL(MAX(verify_serial),0)+1 AS rtnValue FROM common_verification WHERE DATE(verify_dt)=DATE(NOW()) AND verify_unit='$oneStop'";
			
			$newVerifySerialrslt = $this->bm->dataSelectDb1($maxVerifysql);
			$newVerifySerial=$newVerifySerialrslt [0]['rtnValue'];
			
			$date = date('dmy');
				
			$size=strlen($newVerifySerial);
			$newVerifyNo = "";
			if($size==1)
			{
				$newVerifyNo="U".$oneStop."".$date."000".$newVerifySerial;
			}
			else if($size==2)
			{
				$newVerifyNo="U".$oneStop."".$date."00".$newVerifySerial;
			}
			else if($size==3)
			{
				$newVerifyNo="U".$oneStop."".$date."0".$newVerifySerial;
			}
			else 
			{
				$newVerifyNo="U".$oneStop."".$date."".$newVerifySerial;
			}
			
			$strVerification = "";
			if($igm_type_flag=="dtl")
			{
				$strVerification="INSERT INTO common_verification(igm_dtl_id,cont_stat,verify_dt,verify_serial,verify_no,verify_by,verify_at,verify_ip,verify_unit) 
				VALUES ('$igm_detail_id','$contStatus',date(now()),'$newVerifySerial','$newVerifyNo','$login_id',now(),'$ipaddr','$oneStop')";	
			}	
			else
			{
				$strVerification="INSERT INTO common_verification(igm_sup_dtl_id,cont_stat,verify_dt,verify_serial,verify_no,verify_by,verify_at,verify_ip,verify_unit) 
				VALUES ('$igm_sup_detail_id','$contStatus',date(now()),'$newVerifySerial','$newVerifyNo','$login_id',now(),'$ipaddr','$oneStop')";
			}
							
			$InsStat = $this->bm->dataInsertDB1($strVerification);
							
			//echo $contStatus;
			//return;

			if($contStatus=="LCL")
			{
				/*$checkQuery="select verify_number,igm_detail_id,igm_sup_detail_id from shed_tally_info 
							where id='$shedTallyInfoID'";				  
				$selectStat = $this->bm->dataSelectDb1($checkQuery);
				$chkVal=$selectStat[0]['verify_number'];
				//------------
				$igm_detail_id = "";
				$igm_sup_detail_id = "";
				
				$dtl_id = "";
				$igm_type_flag = "";
				for($i=0;$i<count($selectStat);$i++)
				{
					$igm_detail_id=$selectStat[$i]['igm_detail_id'];
					$igm_sup_detail_id=$selectStat[$i]['igm_sup_detail_id'];
				}
				if($igm_detail_id != "")
				{
					$dtl_id = $igm_detail_id;
					$igm_type_flag = "dtl";
				}
				else
				{
					$dtl_id = $igm_sup_detail_id;
					$igm_type_flag = "sup_dtl";
				}
				*/
				//--------------
				if($chkVal=="" || $chkVal=="NULL" )
				{
					if($verifyNo=="")
					{
						// LCL ???
						/*$maxVerifysql="SELECT IFNULL(MAX(verify_serial),0)+1 AS rtnValue FROM shed_tally_info WHERE DATE(verify_time)=DATE(NOW()) AND verify_unit='$oneStop'";
						// $maxVerifysql="select max(verify_number)+1 as rtnValue from shed_tally_info";
						//$newVerifySerial = $this->bm->dataReturnDb1($maxVerifysql);
						$newVerifySerialrslt = $this->bm->dataSelectDb1($maxVerifysql);
						$newVerifySerial=$newVerifySerialrslt [0]['rtnValue'];
						
				
						//$sd = date(dmy);	
				
						//$dateQuery="select DATE_FORMAT(date(now()),'%d%m%y') as rtnValue";
						$date = date('dmy');
				
						$size=strlen($newVerifySerial);
						$newVerifyNo = "";
						if($size==1)
						{
							$newVerifyNo="U".$oneStop."".$date."000".$newVerifySerial;
						}
						else if($size==2)
						{
							$newVerifyNo="U".$oneStop."".$date."00".$newVerifySerial;
						}
						else if($size==3)
						{
							$newVerifyNo="U".$oneStop."".$date."0".$newVerifySerial;
						}
						else 
						{
							$newVerifyNo="U".$oneStop."".$date."".$newVerifySerial;
						}
						*/
						$searchQuery="select count(shed_tally_id)as rtnValue from verify_other_data where shed_tally_id='$shedTallyInfoID'";
						//$selectStat = $this->bm->dataReturnDb1($searchQuery);
						
						$searchQueryRslt = $this->bm->dataSelectDb1($searchQuery);
						$selectStat=$searchQueryRslt [0]['rtnValue'];
						// echo  $selectStat;
						if( $selectStat==0)
						{
					
							$updateShedQuery="UPDATE shed_tally_info SET verify_serial='$newVerifySerial', verify_number='$newVerifyNo',verify_time=now(),verify_by='$login_id',verify_unit='$oneStop' WHERE shed_tally_info.id=$shedTallyInfoID";		
							$updateStat = $this->bm->dataUpdateDB1($updateShedQuery);
				
							$selectQuery="insert into verify_other_data (shed_tally_id, cnf_lic_no, cnf_name, date, no_of_truck, paper_file_date,
							exit_note_number, be_no, be_date, do_no, do_date, valid_up_to_date, cus_rel_odr_no, cus_rel_odr_date, comm_landing_date)
							values('$shedTallyInfoID','$cnf_lic','$cnfName', '$date','$truckNum', '$paperFileDate', '$exitNoteNum','$billOfEntryNo', '$billOfEntryDate', '$doNo', '$doDate', '$validUpToDate','$cusOrderNo', '$cusOrderDate', '$commLandDate')" ;	
					
					
							$Stat = $this->bm->dataInsertDB1($selectQuery);
									
							//echo $selectQuery;
						}
						else
						{	  
							// echo $newVerifyNo;
							$updateShedQuery="UPDATE shed_tally_info SET verify_serial='$newVerifySerial', verify_number='$newVerifyNo',verify_time=now(),verify_by='$login_id',verify_unit='$oneStop' WHERE shed_tally_info.id=$shedTallyInfoID";		
							$updateStat = $this->bm->dataUpdateDB1($updateShedQuery);	

							$updateVerifyQuery="UPDATE verify_other_data SET cnf_lic_no='$cnf_lic', cnf_name='$cnfName',date ='$date',
							no_of_truck='$truckNum',paper_file_date='$paperFileDate', exit_note_number='$exitNoteNum', be_no='$billOfEntryNo',be_date='$billOfEntryDate', do_no='$doNo', do_date='$doDate', valid_up_to_date='$validUpToDate', cus_rel_odr_no='$cusOrderNo', cus_rel_odr_date='$cusOrderDate', comm_landing_date='$commLandDate' WHERE shed_tally_id=$shedTallyInfoID";

							$Stat = $this->bm->dataUpdateDB1($updateVerifyQuery);

						}
						//echo  $updateVerifyQuery;
						if($updateStat==1 and $Stat==1 )	 
						{
							//$data['msg']="Saved Sucessfully.";
							//echo "<font color=green>Saved Sucessfully.</font></br>";
										
							echo "<font color=green size=4> Verification No : ".$newVerifyNo."</font>"; 
						}		
						else	
							echo "<font color=red>Not Saved.</font>";												
					}			  
					else
					{		
						// FCL	???		-- transfered below
						// $updateVerifyQuery="UPDATE verify_other_data SET cnf_lic_no='$cnf_lic',cnf_name='$cnfName',date ='$date',no_of_truck='$truckNum',paper_file_date='$paperFileDate',exit_note_number='$exitNoteNum',be_no='$billOfEntryNo',be_date='$billOfEntryDate',do_no='$doNo', do_date='$doDate', valid_up_to_date='$validUpToDate', cus_rel_odr_no='$cusOrderNo',cus_rel_odr_date='$cusOrderDate',comm_landing_date='$commLandDate' WHERE shed_tally_id=$shedTallyInfoID";
						// //echo 	$updateVerifyQuery;		

						// $Stat = $this->bm->dataUpdateDB1($updateVerifyQuery);	
						// if($Stat==1 )	
						// {
							// echo "<font color=green>Sucessfully updated</font>";

							// //$data['msg']="Sucessfully updated";
						// }		
						// else		
							// echo "<font color=red>Not Updated.</font>";									
					}
					$queryInsert="insert into lcl_dlv_assignment (igm_sup_dtl_id,rot_no,bl_no,cnf_lic_no,no_of_truck,
							igm_type,verify_num,entry_by,entry_at,entry_ip)
							values('$dtl_id','$rotNo','$blNo','$cnf_lic','$truckNum','$igm_type_flag', 
							'$newVerifyNo','$login_id',NOW(),'$ipaddr')";
					$resInsert = $this->bm->dataInsertDB1($queryInsert);
			
				}
				else
				{
				//	echo "<font>This  is already verified and verify number: ".$chkVal."</font>";
					
					// FCL	???
					$updateVerifyQuery="UPDATE verify_other_data SET cnf_lic_no='$cnf_lic',cnf_name='$cnfName',date ='$date',no_of_truck='$truckNum',paper_file_date='$paperFileDate',exit_note_number='$exitNoteNum',be_no='$billOfEntryNo',be_date='$billOfEntryDate',do_no='$doNo', do_date='$doDate', valid_up_to_date='$validUpToDate', cus_rel_odr_no='$cusOrderNo',cus_rel_odr_date='$cusOrderDate',
					comm_landing_date='$commLandDate' WHERE shed_tally_id=$shedTallyInfoID";
					//echo 	$updateVerifyQuery;		

					$Stat = $this->bm->dataUpdateDB1($updateVerifyQuery);	
					if($Stat==1 )	
					{
						echo "<font color=green>Sucessfully updated</font>";

						//$data['msg']="Sucessfully updated";
					}		
					else		
						echo "<font color=red>Not Updated.</font>";		
				}
			}
			else if($contStatus=="FCL")
			{
				$be_no=$this->input->post('seaNumber');
				$rotNo=$this->input->post('rotNo');
				$verifyUnit=$this->input->post('oneStopPoint');
				$doNo=$this->input->post('doNo');
				$validUpToDate=$this->input->post('validUpToDate');
				$doDate=$this->input->post('doDate');
				$truckNum=$this->input->post('truckNum');
				$oneStop=$this->input->post('oneStopPoint');
				$ipaddr = $_SERVER['REMOTE_ADDR'];
				
				/*$maxVerifysql="SELECT IFNULL(MAX(verify_serial),0)+1 AS rtnValue FROM verify_info_fcl 
				WHERE DATE(verify_time)=DATE(NOW()) AND verify_unit='$oneStop'";
				//$newVerifySerial = $this->bm->dataReturnDb1($maxVerifysql);
				
				$maxVerifysqlRslt = $this->bm->dataSelectDb1($maxVerifysql);
				$newVerifySerial=$maxVerifysqlRslt [0]['rtnValue'];
				
				$date = date('dmy');
				
				$size=strlen($newVerifySerial);
				$newVerifyNo = "";
				if($size==1)
				{
					$newVerifyNo="U".$oneStop."".$date."000".$newVerifySerial;
				}
				else if($size==2)
				{
					$newVerifyNo="U".$oneStop."".$date."00".$newVerifySerial;
				}
				else if($size==3)
				{
					$newVerifyNo="U".$oneStop."".$date."0".$newVerifySerial;
				}
				else 
				{
					$newVerifyNo="U".$oneStop."".$date."".$newVerifySerial;
				}
				
				$sqlIglDtlId="SELECT id as rtnValue FROM igm_details 
							WHERE Import_Rotation_No='$rotNo' AND BL_No='$blNo'";
				//$igmDtlId = $this->bm->dataReturnDb1($sqlIglDtlId);
				$igmDtlIdRslt = $this->bm->dataSelectDb1($sqlIglDtlId);
				$igmDtlId=$igmDtlIdRslt [0]['rtnValue'];
				*/
				
				$searchQuery="select count(be_no)as rtnValue from verify_info_fcl 
							where rotation='$rotNo' and bl_no='$blNo'";
				//$selectStat = $this->bm->dataReturnDb1($searchQuery);
				$selectStatRslt = $this->bm->dataSelectDb1($searchQuery);
				$selectStat=$selectStatRslt [0]['rtnValue'];
				
				if( $selectStat==0)
				{
					$insertQuery="insert into verify_info_fcl (igm_type,rotation, bl_no, be_no,igm_detail_id,verify_serial, verify_number, cnf_lic_no,
					verify_unit, do_no,valid_up_to_date, date, no_of_truck, verify_by, verify_time, paper_file_date, exit_note_number, be_date, cus_rel_odr_no, cus_rel_odr_date)
					values('$igm_type_flag','$rotNo', '$blNo', '$billOfEntryNo','$igmDtlId','$newVerifySerial','$newVerifyNo', '$cnf_lic', '$verifyUnit',  '$doNo',
					'$validUpToDate','$doDate','$truckNum', '$login_id',now(), '$paperFileDate', '$exitNoteNum', '$billOfEntryDate', '$cusOrderNo',
					'$cusOrderDate' )" ;	
					
					$resInsertQuery = $this->bm->dataInsertDB1($insertQuery);
				}
				else
				{
					$updateQuery="UPDATE verify_info_fcl SET igm_type='$igm_type_flag',igm_detail_id='$igmDtlId',verify_serial='$newVerifySerial',
					verify_number='$newVerifyNo',verify_unit='$verifyUnit',do_no='$doNo',
					valid_up_to_date='$validUpToDate',date='$doDate',no_of_truck='$truckNum', 
					verify_by='$login_id',verify_time=now(), paper_file_date='$paperFileDate', exit_note_number='$exitNoteNum',
					be_date='$billOfEntryDate', cus_rel_odr_no='$cusOrderNo', cus_rel_odr_date='$cusOrderDate'
					WHERE rotation='$rotNo' and bl_no='$blNo'";		
					$updateStat = $this->bm->dataUpdateDB1($updateQuery);
				}
				
				$verifyInfo = null;

				if($resInsertQuery==1 or $updateStat==1 )	 
				{
					//$data['msg']="Saved Sucessfully.";
					//echo "<font color=green>Saved Sucessfully.</font></br>";
								
					$verifyInfo =  "<font color=green size=4> Verification No : ".$newVerifyNo."</font>"; 
				}		
				else
				{
					$verifyInfo = "<font color=red>Not Saved.</font>";
				}

				$this->deliveryEntryFormByWHClerk($rotNo,$blNo,$verifyInfo);
			}
		}
	// }
	}

	function roDelete()
	{
		$login_id = $this->session->userdata('login_id');
		$u_name = $this->session->userdata('User_Name');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$org_id = $this->session->userdata('org_id');

		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			$data['msg']="";
			if($this->input->post('delete'))
			{
				$eid=$this->input->post('eid');
				$deleteSql="DELETE FROM release_order_record WHERE release_order_record.id='$eid'";
				$deleteStat=$this->bm->dataDeleteDb1($deleteSql);
				$data['msg']="<font color='red'><b>Data Deleted.</b></font>";
			}

			redirect('ReleaseOrderController/roList/', 'refresh');
		}
	}

	function roEdit()
	{

		$login_id = $this->session->userdata('login_id');
		$u_name = $this->session->userdata('User_Name');
		$Address_1 = $this->session->userdata('Address_1');
		$Address_2 = $this->session->userdata('Address_2');
		$Cell_No_1 = $this->session->userdata('Cell_No_1');
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$org_id = $this->session->userdata('org_id');

		
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			$sqlBl = "SELECT agent_id,imp_rot,bl_no,agent_name FROM release_order_record
			INNER JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=release_order_record.agent_id
			INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
			WHERE release_order_record.entry_by='$login_id'";
			$rslt_sqlRelease = $this->bm->dataSelectDb1($sqlBl);

			
			$data['login_id']=$login_id;
			$data['Address_1']=$Address_1;
			$data['u_name']=$u_name;
			$data['rslt_sqlRelease']=$rslt_sqlRelease;
			$data['Address_2']=$Address_2;
			$data['Cell_No_1']=$Cell_No_1;
			$data['org_Type_id']=$org_Type_id;
			$data['flag'] = 1;
			$data['msg']="";
			$data['title']="RELEASE ORDER LIST";								
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('roForm',$data);
			$this->load->view('jsAssetsList');
		}
	}

	//Release Order Start
	
	function releaseOrderForm()
	{		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
			
		}
		else
		{
			$data['msg']="";
			$data['unstuff_flag']="";
			$data['verify_number']="-1";
			$data['title']="RELEASE ORDER SECTION...";		

			$sql_releaseOrderList = "SELECT * FROM(
			SELECT id,verify_number,rotation,cont_number,verify_info_fcl.bl_no,shed_bill_master.bill_no AS bn,
			(SELECT cp_no FROM bank_bill_recv WHERE bill_no=bn LIMIT 1) AS cp_no,'FCL' AS cont_status,counter_confirm_flag,pr_number
			FROM verify_info_fcl 
			INNER JOIN shed_bill_master ON shed_bill_master.verify_no=verify_info_fcl.verify_number
			WHERE verify_number IS NOT NULL 
			UNION
			SELECT shed_tally_info.id,verify_number,shed_tally_info.import_rotation AS rotation,shed_tally_info.cont_number,igm_supplimentary_detail.BL_No AS bl_no,shed_bill_master.bill_no AS bn,(SELECT cp_no FROM bank_bill_recv WHERE bill_no=bn LIMIT 1) AS cp_no,'LCL' AS cont_status,counter_confirm_flag,pr_number
			FROM shed_tally_info 
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
			INNER JOIN shed_bill_master ON shed_bill_master.verify_no=shed_tally_info.verify_number
			WHERE verify_number IS NOT NULL AND shed_tally_info.import_rotation IS NOT NULL AND shed_tally_info.cont_number IS NOT NULL
			ORDER BY id DESC LIMIT 1000) AS tbl WHERE cp_no IS NOT NULL OR cp_no!=''";
			$rslt_releaseOrderList = $this->bm->dataSelectDb1($sql_releaseOrderList);
			$data['rslt_releaseOrderList']=$rslt_releaseOrderList;		
						
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('releaseOrderForm',$data);
			$this->load->view('jsAssetsList');			
		}			
	}
		
	function releaseOrderFormView()
	{
		$verify_number=$this->input->post('verify_number');
		
		if(isset($_POST['options']))
		{
			if($_POST['options']=='Bill')
			{
				$this->getShedBillPdf($verify_number);
			}
		}		
		else
		{
			$login_id = $this->session->userdata('login_id');
			$imp_rot=$this->input->post('imp_rot');
			$bl_no=$this->input->post('bl_no');
			
			$strBill="select igm_supplimentary_detail.id,IFNULL(sum(rcv_pack+loc_first),0) as rcv_pack,igm_masters.Vessel_Name,igm_supplimentary_detail.Import_Rotation_No,igm_sup_detail_container.cont_number,Pack_Marks_Number,shed_loc,shed_yard,Description_of_Goods,Notify_name,IFNULL(shed_tally_info.verify_number,0) as verify_number,IFNULL(shed_tally_info.id,0) as verify_id,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_weight,verify_other_data.cnf_name,verify_other_data.be_no,verify_other_data.be_date,igm_sup_detail_container.cont_height,bank_bill_recv.bill_no,bank_bill_recv.cp_no,RIGHT(bank_bill_recv.cp_year,2) AS cp_year,bank_bill_recv.cp_bank_code,bank_bill_recv.cp_unit,date(bank_bill_recv.recv_time) as cp_date,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.Line_No,total_port,concat(right(YEAR(bill_date),2),'/',concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as master_bill_no,shed_bill_master.bill_date,VoyNo,verify_other_data.exit_note_number
			from  igm_supplimentary_detail
			inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			inner join igm_masters on igm_supplimentary_detail.igm_master_id=igm_masters.id
			left join  shed_tally_info on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
			left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
			left join shed_bill_master on shed_bill_master.verify_no=shed_tally_info.verify_number
			left join bank_bill_recv on bank_bill_recv.bill_no=shed_bill_master.bill_no
			left join vessels_berth_detail on shed_bill_master.import_rotation=vessels_berth_detail.Import_Rotation_No
			where igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
				
			$rtnContainerList = $this->bm->dataSelectDb1($strBill);
			$this->data['rtnContainerList']=$rtnContainerList;
			
			if($verify_number!=0)
			{
			
				$strBillRcvInfo="select description,gl_code from shed_bill_details 
							inner join shed_bill_master on shed_bill_master.bill_no=shed_bill_details.bill_no
							where shed_bill_master.verify_no='$verify_number'";
				$rtnBillRcvInfo = $this->bm->dataSelectDb1($strBillRcvInfo);
				$this->data['rtnBillRcvInfo']=$rtnBillRcvInfo;
				
				$sqlTruckNumber="select no_of_truck from verify_other_data
							inner join shed_tally_info on shed_tally_info.id=verify_other_data.shed_tally_id
							where shed_tally_info.verify_number='$verify_number'";
					//echo "TestData : ".$sqlTruckNumber;
				$rtnTruckNumber = $this->bm->dataSelectDb1($sqlTruckNumber);						
				$this->data['rtnTruckNumber']=$rtnTruckNumber;
				
				$str="select concat(right(YEAR(bill_date),2),'/',concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,arraival_date,import_rotation,vessel_name,cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,be_no,be_date,ado_no,ado_date,ado_valid_upto,manifest_qty,cont_size,cont_height,bill_rcv_stat, if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status from shed_bill_master where verify_no='$verify_number'"; 
				//and bill_no in (select max(bill_no) from shed_bill_master where verify_no='$verify_number')";
				//echo $str;
				//echo $str;
				$rtnBillList = $this->bm->dataSelectDb1($str);
				$unit_no=$rtnBillList[0]['unit_no'];
				$cpa_vat_reg_no=$rtnBillList[0]['cpa_vat_reg_no'];
				$ex_rate=$rtnBillList[0]['ex_rate'];
				$bill_rcv_stat=$rtnBillList[0]['bill_rcv_stat'];
				
				$this->data['rtnBillList']=$rtnBillList;
				$this->data['unit_no']=$unit_no;
				$this->data['cpa_vat_reg_no']=$cpa_vat_reg_no;
				$this->data['ex_rate']=$ex_rate;
				$this->data['bill_rcv_stat']=$bill_rcv_stat;
				
				$strBankPaymentInfo = "select shed_bill_master.bill_no,bill_rcv_stat,cp_bank_code,user,concat(cp_bank_code,cp_unit,'/',right(cp_year,2),'-',concat(if(length(cp_no)=1,'000',if(length(cp_no)=2,'00',if(length(cp_no)=3,'0',''))),cp_no)) as cp_no
				from shed_bill_master 
				inner join bank_bill_recv on bank_bill_recv.bill_no=shed_bill_master.bill_no
				where verify_no='$verify_number'";
				$rtnBankPaymentInfo = $this->bm->dataSelectDb1($strBankPaymentInfo);
				$rcvstat=$rtnBankPaymentInfo[0]['bill_rcv_stat'];
				$cpnoview=$rtnBankPaymentInfo[0]['cp_no'];
				$cpbankcode=$rtnBankPaymentInfo[0]['cp_bank_code'];
				$shedbill=$rtnBankPaymentInfo[0]['bill_no'];
				$billPrepareBy=$rtnBankPaymentInfo[0]['user'];
				
				if($cpbankcode=="OB")
					$cpbankname="ONE BANK LIMITED";
				
				$sqlrcvdate="SELECT recv_by,DATE(recv_time) AS recv_time FROM bank_bill_recv WHERE bill_no='$shedbill'";
				$rtnrcvdate = $this->bm->dataSelectDb1($sqlrcvdate);
				
				$recv_by=$rtnrcvdate[0]['recv_by'];
				$recv_time=$rtnrcvdate[0]['recv_time'];
				
				
				$qry="select verify_no,bill_no,gl_code,description,tarrif_rate,Qty,qday,amt,vatTK,mlwfTK from shed_bill_details
				where verify_no='$verify_number' and bill_no in (select max(bill_no) from shed_bill_master where verify_no='$verify_number')";
				//echo $qry;
				$chargeList = $this->bm->dataSelectDb1($qry);
				$this->data['chargeList']=$chargeList;
				
				$this->data['cpnoview']=$cpnoview;
				$this->data['cpbankname']=$cpbankname;
				$this->data['recv_time']=$recv_time;
				$this->data['recv_by']=$recv_by;
				$this->data['billPrepareBy']=$billPrepareBy;
			}
			else
			{
			
				$this->data['rtnBillList']="";
				$this->data['unit_no']="";
				$this->data['cpa_vat_reg_no']="";
				$this->data['ex_rate']="";
				$this->data['bill_rcv_stat']="";
				
				$this->data['chargeList']="";
				
				$this->data['cpnoview']="";
				$this->data['cpbankname']="";
				$this->data['recv_time']="";
				$this->data['recv_by']="";
				$this->data['billPrepareBy']="";
			}
			
			$qry_sum="select SUM(amt) as amt from shed_bill_details
					where verify_no='$verify_number' and bill_no in (select max(bill_no) from shed_bill_master where verify_no='$verify_number')";
			//echo $qry;
			$sumAll = $this->bm->dataSelectDb1($qry_sum);
			$tot_sum=$sumAll[0]['amt'];
					
			$qry_qday="select IFNULL(SUM(qday),0) as qday from shed_bill_details
					where verify_no='$verify_number' and bill_no in (select max(bill_no) from shed_bill_master where verify_no='$verify_number') AND gl_code not in('501005','502000N','503000N')";
			//echo $qry;
			$qdayAll = $this->bm->dataSelectDb1($qry_qday);
			$tot_qday=$qdayAll[0]['qday'];
				
			//now pass the data//
			$this->data['title']="Shed Bill";
			$this->data['verify_number']=$verify_number;
			$this->data['tot_sum']=$tot_sum;
			$this->data['tot_qday']=$tot_qday;
			//$this->data['amountInwords']=convert_number_to_words(5000);
				 
			$this->data['bill_print_times']=4;
			$this->data['login_id']=$login_id;
					
			//echo $rtnContainerList[0]['verify_number']."  fdfdfd";
					
			$this->data['verifyNo']=$verify_number;
			//$data['title']="TALLY LIST REPORT...";
			//$data['vNum']=$rtnContainerList[0]['BL_NO'];
			//$this->load->view('header2');			
			//$this->load->view('footer');
			//$this->load->view('releaseOrderFormViewPDF',$data);
			
			
			$this->load->library('m_pdf');
			$html=$this->load->view('releaseOrderFormViewPDF',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
			 
			$pdfFilePath ="ReleaseOrder-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
			//	$pdf->useSubstitutions = true; // optional - just as an example
				
			//$pdf->setFooter('Prepared By : '.$login_id.'|Page {PAGENO} of {nb}|Date {DATE j-m-Y}');
				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
				 
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		}
	}

	function confirmByCounter()
	{
		$login_id = $this->session->userdata('login_id');
		$verifyNumber = $this->input->post('verify_number');
		$contStatus = $this->input->post('cont_status');
		
		if($verifyNumber!="")
		{
			if($contStatus == "FCL")
			{
				$sql_chkConfirm = "SELECT counter_confirm_flag AS rtnValue
				FROM verify_info_fcl
				WHERE verify_number='$verifyNumber'";
				$chkConfirm = $this->bm->dataReturnDB1($sql_chkConfirm);
				
				if($chkConfirm==0)
				{
					// generate PR number
					$sql_maxPrSl="SELECT IFNULL(MAX(pr_sl),0)+1 AS rtnValue
					FROM verify_info_fcl
					WHERE DATE(counter_confirm_at)=DATE(NOW())";
					$maxPrSl = $this->bm->dataReturnDB1($sql_maxPrSl);
					
					$date = date('dmy');
					
					$size=strlen($maxPrSl);
					$newPrNo = "";
					if($size==1)
					{
						$newPrNo="PR-".$date."000".$maxPrSl;
					}
					else if($size==2)
					{
						$newPrNo="PR-".$date."00".$maxPrSl;
					}
					else if($size==3)
					{
						$newPrNo="PR-".$date."0".$maxPrSl;
					}
					else 
					{
						$newPrNo="PR-".$date."".$maxPrSl;
					}
					
					$sql_updateConfirmFlag = "UPDATE verify_info_fcl
					SET pr_sl='$maxPrSl',pr_number='$newPrNo',counter_confirm_flag='1',counter_confirm_by='$login_id',counter_confirm_at=NOW()
					WHERE verify_number='$verifyNumber'";
					$this->bm->dataUpdateDB1($sql_updateConfirmFlag);						
				}
			}
			else
			{
				$sql_chkConfirm = "SELECT counter_confirm_flag AS rtnValue
				FROM shed_tally_info
				WHERE verify_number='$verifyNumber'";
				$chkConfirm = $this->bm->dataReturnDB1($sql_chkConfirm);
				
				if($chkConfirm==0)
				{
					// generate PR number
					$sql_maxPrSl="SELECT IFNULL(MAX(pr_sl),0)+1 AS rtnValue
					FROM shed_tally_info
					WHERE DATE(counter_confirm_at)=DATE(NOW())";
					$maxPrSl = $this->bm->dataReturnDB1($sql_maxPrSl);
					
					$date = date('dmy');
					
					$size=strlen($maxPrSl);
					$newPrNo = "";
					if($size==1)
					{
						$newPrNo="PR-".$date."000".$maxPrSl;
					}
					else if($size==2)
					{
						$newPrNo="PR-".$date."00".$maxPrSl;
					}
					else if($size==3)
					{
						$newPrNo="PR-".$date."0".$maxPrSl;
					}
					else 
					{
						$newPrNo="PR-".$date."".$maxPrSl;
					}
					
					$sql_updateConfirmFlag = "UPDATE shed_tally_info
					SET pr_sl='$maxPrSl',pr_number='$newPrNo',counter_confirm_flag='1',counter_confirm_by='$login_id',counter_confirm_at=NOW()
					WHERE verify_number='$verifyNumber'";
					$this->bm->dataUpdateDB1($sql_updateConfirmFlag);												
				}
			}
		}
		
		// $this->releaseOrderForm();
		redirect('/ReleaseOrderController/releaseOrderForm', 'refresh');
	}

	function roListCounter()
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id = $this->session->userdata('org_Type_id');

	
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			
			$sqlBl = "SELECT release_order_record.id,agent_id,imp_rot,bl_no,agent_name,entry_by,entry_at,unit_no,ro_type,appraise_st,appraise_at,IFNULL(assigned_unit.updated_at,assigned_unit.created_at) AS unit_assign_at,
			IFNULL((SELECT cont_status
			FROM  igm_supplimentary_detail
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No = release_order_record.imp_rot AND igm_supplimentary_detail.BL_No = release_order_record.bl_no LIMIT 1 ),
			(SELECT cont_status
			FROM  igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No = release_order_record.imp_rot AND igm_details.BL_No = release_order_record.bl_no LIMIT 1)) AS contStatus
			FROM release_order_record
			INNER JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=release_order_record.agent_id
			INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
			LEFT JOIN assigned_unit ON assigned_unit.rotation = release_order_record.imp_rot
			Order BY release_order_record.id DESC LIMIT 100";
			
			
			$rslt_sqlRelease = $this->bm->dataSelectDb1($sqlBl);
			
			$data['rslt_sqlRelease']=$rslt_sqlRelease;
			$data['org_Type_id']=$org_Type_id;
			$data['msg']="";
			$data['title']="RELEASE ORDER LIST (COUNTER)";								
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('roListCounter',$data);
			$this->load->view('jsAssetsList');
		}
	}

	//to show draft bill
	function shedBillDraftDetail()
	{
		$imp_rot = $this->input->post("imp_rot");
		$bl_no = $this->input->post("bl_no");
		//$strVerifyNum= $_GET["verify_num"];
		$unstfDt=date("Y-m-d");;
		
	 	$uptoDt= date("Y-m-d");
		$rpc= 0;
		$hcCharge= 0;
		$scCharge= 0;
		$vatInfo=1; 
		$mlwf= 1;
		
		$section = $this->session->userdata('section');
		
		$cont_status="";

		$this->tariffGenerateDraftBill($imp_rot,$bl_no,$unstfDt,$uptoDt,$rpc,$hcCharge,$scCharge); 

		$str="SELECT  import_rotation,shed_tally_info.cont_number,verify_number,Vessel_Name,
			IFNULL(igm_supplimentary_detail.Line_No, igm_details.Line_No) AS  Line_No,
			IFNULL(igm_supplimentary_detail.BL_No, igm_details.BL_No) AS BL_No,  
			IFNULL(igm_detail_container.cont_gross_weight, igm_sup_detail_container.Cont_gross_weight) AS cont_weight,
			IFNULL(igm_detail_container.cont_size, igm_sup_detail_container.cont_size) AS cont_size,
			IFNULL(igm_detail_container.cont_height, igm_sup_detail_container.cont_height) AS cont_height,
			IFNULL(igm_detail_container.cont_type, igm_sup_detail_container.cont_type) AS cont_type,
			IFNULL(igm_supplimentary_detail.Consignee_code, igm_details.Consignee_code) AS Consignee_code,  
			IFNULL(igm_supplimentary_detail.Consignee_name, igm_details.Consignee_name) AS Consignee_name,  
			IFNULL(igm_supplimentary_detail.Pack_Number, igm_details.Pack_Number) AS Pack_Number,  
			IFNULL(igm_supplimentary_detail.notify_name, igm_details.notify_name) AS notify_name, 
			wr_date,wr_upto_date,cnf_lic_no,be_no, be_date, cnf_name,rcv_pack,loc_first,total_pack,
			igm_supplimentary_detail.Pack_Number,
			verify_other_data.valid_up_to_date,verify_other_data.do_no,verify_other_data.do_date,
			verify_other_data.comm_landing_date,rcv_unit,equipment,
			used_equipment.equipment_id,used_equipment.equipment_charge,used_equipment.equipment_name,used_equipment.remarks,
			bil_tariffs.id AS tariffid,bil_tariffs.gl_code AS glcode,bil_tariff_rates.amount AS tamt
			FROM shed_tally_info
			LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id = shed_tally_info.igm_sup_detail_id
			LEFT JOIN igm_sup_detail_container ON shed_tally_info.igm_sup_detail_id=igm_sup_detail_container.igm_sup_detail_id
			LEFT JOIN  igm_details ON igm_details.id = shed_tally_info.igm_detail_id
			LEFT JOIN igm_detail_container ON shed_tally_info.igm_detail_id=igm_detail_container.igm_detail_id
			INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
			LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
			LEFT JOIN appraisement_info ON (appraisement_info.rotation=igm_supplimentary_detail.Import_Rotation_No
			AND appraisement_info.BL_NO=igm_supplimentary_detail.BL_No)
			OR (appraisement_info.rotation=igm_details.Import_Rotation_No AND appraisement_info.BL_NO=igm_details.BL_No)
			LEFT JOIN used_equipment ON appraisement_info.equipment_id=used_equipment.equipment_id
			LEFT JOIN bil_tariffs ON used_equipment.equipment_name=bil_tariffs.description
			LEFT JOIN bil_tariff_rates ON bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
		$rtnBillList = $this->bm->dataSelectDb1($str);
		
		if(count($rtnBillList)==0)
		{			
			$cont_status="FCL";
			
			$str="SELECT  DISTINCT igm_detail_container.cont_number, igm_details.Import_Rotation_No AS import_rotation, 
			verify_info_fcl.verify_number,igm_masters.Vessel_Name,igm_details.Line_No,igm_details.BL_No,
			igm_detail_container.Cont_gross_weight AS cont_weight,igm_detail_container.cont_size,
			igm_detail_container.cont_height,igm_detail_container.cont_status,igm_detail_container.cont_type,
			certify_info_fcl.wr_upto_date,certify_info_fcl.cnf_lic_no,certify_info_fcl.be_no,certify_info_fcl.be_date,
			igm_details.notify_name,certify_info_fcl.cnf_name,igm_details.Pack_Number,igm_details.Consignee_code,
			igm_details.Consignee_name,verify_info_fcl.valid_up_to_date,verify_info_fcl.do_no,certify_info_fcl.do_date,
			appraisement_info_fcl.equipment,common_land_date AS comm_landing_date,warfrent_start_date AS wr_date
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			INNER JOIN assigned_unit ON assigned_unit.rotation=igm_details.Import_Rotation_No
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'";					
			$rtnBillList = $this->bm->dataSelectDb1($str);
			
			
			$strTotContainer="select count(*)
			as totCont from (
			SELECT DISTINCT igm_detail_container.cont_number,igm_details.Import_Rotation_No AS import_rotation,
			verify_info_fcl.verify_number,igm_masters.Vessel_Name,igm_details.Line_No,igm_details.BL_No,
			igm_detail_container.Cont_gross_weight AS cont_weight,igm_detail_container.cont_size,
			igm_detail_container.cont_height,igm_detail_container.cont_status,igm_detail_container.cont_type,
			certify_info_fcl.wr_upto_date,certify_info_fcl.cnf_lic_no,certify_info_fcl.be_no,certify_info_fcl.be_date,
			igm_details.notify_name,certify_info_fcl.cnf_name,igm_details.Pack_Number,igm_details.Consignee_code,
			igm_details.Consignee_name,verify_info_fcl.valid_up_to_date,verify_info_fcl.do_no,certify_info_fcl.do_date,
			appraisement_info_fcl.equipment,'' as comm_landing_date,'' as wr_date
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') as tble";
			$rtnTotContainer = $this->bm->dataSelectDb1($strTotContainer);
			$data['rtnTotContainer']=$rtnTotContainer;
			for($k=0;$k<count($rtnTotContainer); $k++)
			{
				$total_container=$rtnTotContainer[$k]['totCont'];
			}		
			//$total_container = $rtnTotContainer[0]['totCont'];
			$data['total_container']=$total_container;
		/* 	echo json_encode($data);
			return; */

		}
		else
		{
			$cont_status="LCL";
		}
		
		$import_rotation = @$rtnBillList[0]['import_rotation'];
		$container = @$rtnBillList[0]['cont_number'];
		$blNo= @$rtnBillList[0]['BL_No'];
				
	
		$arraivalDateQry="select to_char(argo_carrier_visit.ata,'YYYY-MM-DD') as ata from vsl_vessel_visit_details
		inner join argo_carrier_visit 
		on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		where vsl_vessel_visit_details.ib_vyg='$imp_rot'";
		$arraivalDate = $this->bm->dataSelect($arraivalDateQry);
		for($k=0;$k<count($arraivalDate); $k++)
		{
			$arraivalDateValue=$arraivalDate[$k]['ATA'];
		}	
		//$arraivalDateValue=$arraivalDate[0]['ATA'];
			
		if($cont_status=="LCL")
		{
			$getDataAppraisalQry="SELECT equipment,appraise_date,carpainter_use,hosting_charge,extra_movement,scale_for 
			FROM appraisement_info WHERE rotation='$imp_rot' AND BL_NO='$bl_no'";
		}
		else if($cont_status=="FCL")
		{
			$getDataAppraisalQry="SELECT equipment,appraise_date,carpainter_use,hosting_charge,extra_movement,scale_for
			FROM appraisement_info_fcl WHERE rotation='$imp_rot' AND BL_NO='$bl_no'";
		}
	
		
		$appraisalData = $this->bm->dataSelectDb1($getDataAppraisalQry);
		$appraisalDataCount=count($appraisalData);

		//	$getExRateQuery= "select rate from bil_currency_exchange_rates where DATE(effective_date)= '$arraivalDateValue'";
		
		$getExRateQuery= "SELECT IFNULL((SELECT rate FROM bil_currency_exchange_rates WHERE DATE(effective_date)= '$arraivalDateValue'),(SELECT rate FROM bil_currency_exchange_rates ORDER BY gkey DESC LIMIT 1)) AS rate";
		$getExRate = $this->bm->dataSelectDb1($getExRateQuery);
		//$getExRateValue=$getExRate[0]['rate'];
		for($k=0;$k<count($getExRate); $k++)
		{
			$getExRateValue=$getExRate[$k]['rate'];
		}
		

		
		$getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 4 DAY)),0) AS dif";		
		// 2020-04-06 - start
		$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
		for($k=0;$k<count($getDateDiff); $k++)
		{
			$dateDiffValue=$getDateDiff[$k]['dif'];
		}
		
		
		// 2020-04-06 - end
		
		//$dateDiffValue=15;					
					
		// 2020-04-06 - start	
		if($cont_status=="LCL")
		{
			$qry= "select verify_no,tarrif_id,bil_tariffs.description,bil_tariffs.gl_code,IFNULL(bil_tariff_rates.amount,0) as tarrif_rate,
			ifnull(verify_other_data.update_ton,CEIL(igm_sup_detail_container.Cont_gross_weight /1000)) as Qty,
			igm_sup_detail_container.Cont_gross_weight as cont_weight,
			(case 
				when 
					tarrif_id like '%1ST%'
				then 
					if($dateDiffValue<7,$dateDiffValue,7)
				else 
					case 
						when 
							tarrif_id like '%2ND%'
						then 
							if($dateDiffValue<14,$dateDiffValue-7,7)
						else  
							if(tarrif_id like '%3RD%',$dateDiffValue-14,1)
					end
			end) as qday,
			(select tarrif_rate*Qty*qday) as amt,
			(select if($vatInfo='0',0,(select amt*15/100))) as vatTK 
			FROM shed_bill_tarrif_draft
			INNER JOIN bil_tariffs ON  shed_bill_tarrif_draft.tarrif_id= bil_tariffs.id
			INNER JOIN bil_tariff_rates ON bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
			INNER JOIN igm_supplimentary_detail ON shed_bill_tarrif_draft.rotation= igm_supplimentary_detail.Import_Rotation_No AND shed_bill_tarrif_draft.bl_no= igm_supplimentary_detail.BL_No
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id = igm_supplimentary_detail.id

			LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_sup_detail_container.igm_sup_detail_id
			INNER JOIN verify_other_data ON verify_other_data.shed_tally_id=shed_tally_info.id
			where shed_bill_tarrif_draft.rotation='$imp_rot' AND shed_bill_tarrif_draft.bl_no='$bl_no'";
		}
		else if($cont_status=="FCL")
		{						//amt
			$qry="SELECT DISTINCT bil_tariffs.gl_code AS gl_code,bil_tariff_rates.currency_gkey,verify_number,tarrif_id,
			IF(currency_gkey='2',CONCAT('$',bil_tariffs.description),bil_tariffs.description) AS description,
			IFNULL(bil_tariff_rates.amount,0) AS tarrif_rate,
			certify_info_fcl.update_ton,
			(CASE
				WHEN tarrif_id='HOSTING_CHARGES'
				THEN hosting_charge
				ELSE
					CASE
					WHEN tarrif_id='WEIGHMENT_CHARGE'
					THEN scale_for
					ELSE
						CASE
							WHEN tarrif_id='REPAIRING_CHARGE'
							THEN carpainter_use
							ELSE
								CASE
									WHEN tarrif_id='FLT_1_5_TON'
									THEN 
							(SELECT COUNT(*) FROM igm_details 
							INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
							WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no')
									ELSE 
										CASE
											WHEN tarrif_id='FLT_6_20_TON'
											THEN 
								(SELECT COUNT(*) FROM igm_details 
								INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
								WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no')
											ELSE
												CASE
													WHEN tarrif_id='FLT_21_50_TON'
													THEN 
									(SELECT COUNT(*) FROM igm_details 
									INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
									WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no')
												   -- ELSE add remaining charges
												END
										END
								END
						END
				END		
			END) AS Qty,
			'1' AS qday,
			IF(currency_gkey='2',(SELECT tarrif_rate*Qty*qday*84.96),(SELECT tarrif_rate*Qty*qday)) AS amt,
			(SELECT IF(1='0',0,(SELECT amt*15/100))) AS vatTK
			FROM igm_details 
			INNER JOIN shed_bill_tarrif_draft ON shed_bill_tarrif_draft.rotation=igm_details.Import_Rotation_No AND shed_bill_tarrif_draft.bl_no=igm_details.BL_No			
			INNER JOIN bil_tariffs ON bil_tariffs.id=shed_bill_tarrif_draft.tarrif_id
			INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No			
			WHERE  igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no' AND (bil_tariffs.gl_code IN('503000N','204002N','309000') OR (tarrif_id LIKE 'FLT_%' OR tarrif_id LIKE 'CRANE_%'))

			UNION ALL

			SELECT DISTINCT bil_tariffs.gl_code,currency_gkey,verify_number,shed_bill_tarrif_draft.tarrif_id,bil_tariffs.description,
			IFNULL(bil_tariff_rates.amount,0) AS tarrif_rate,
			-- igm_details.id AS igm_dtls_id,
			certify_info_fcl.update_ton,
			-- appraisement_info_fcl.gkey AS appInfoFCL_gkey,
			-- cont_size,
			(CASE
				WHEN (bil_tariffs.gl_code='501001' OR bil_tariffs.gl_code='505001')		-- 20
				THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,igm_detail_container.cont_size,
				igm_detail_container.cont_height	 
				FROM igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl WHERE cont_size='20')
				ELSE
				CASE
					WHEN (bil_tariffs.gl_code='501002' OR bil_tariffs.gl_code='505002')		-- 40 8.6
					THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,igm_detail_container.cont_size,
						igm_detail_container.cont_height	 
						FROM igm_details
						INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl WHERE cont_size='40' AND cont_height='8.6')
					ELSE
						CASE
							WHEN (bil_tariffs.gl_code='501003' OR bil_tariffs.gl_code='505006')		-- 40 9.6 45
							THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,igm_detail_container.cont_size,
								igm_detail_container.cont_height	 
								FROM igm_details
								INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
								WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl 
								WHERE cont_size='45' OR (cont_size='40' AND cont_height='9.6'))
								ELSE -- slab
						CASE	-- slab 20 
							WHEN (bil_tariffs.gl_code='403017' OR bil_tariffs.gl_code='403019' OR bil_tariffs.gl_code='403021')
							THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,
								igm_detail_container.cont_size,
								igm_detail_container.cont_height	 
								FROM igm_details
								INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
								WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl 
								WHERE cont_size='20')
							ELSE
								CASE 	-- slab 40
									WHEN (bil_tariffs.gl_code='403023' OR bil_tariffs.gl_code='403025' OR bil_tariffs.gl_code='403027')
									THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,
										igm_detail_container.cont_size,
										igm_detail_container.cont_height	 
										FROM igm_details
										INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
										WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl 
										WHERE cont_size='40')
									ELSE
										CASE	-- slab 45
											WHEN (bil_tariffs.gl_code='403029' OR bil_tariffs.gl_code='403031' OR bil_tariffs.gl_code='403033')
											THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,
												igm_detail_container.cont_size,
												igm_detail_container.cont_height	 
												FROM igm_details
												INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
												WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl 
												WHERE cont_size='45')
										END
								END			                
						END
					END   
					END	       
			END) AS Qty,

			(CASE 
				WHEN 
					tarrif_id LIKE '%1ST%'
				THEN 
					IF($dateDiffValue<=7,$dateDiffValue,7)
				ELSE 
					CASE 
						WHEN 
							tarrif_id LIKE '%2ND%'
						THEN 
							IF($dateDiffValue<=20,$dateDiffValue-7,7)
						ELSE  
							IF(tarrif_id LIKE '%3RD%',$dateDiffValue-20,1)							
					END
			END) AS qday,		
			
			IF(currency_gkey='2',(SELECT tarrif_rate*Qty*qday*$getExRateValue),(SELECT tarrif_rate*Qty*qday)) AS amt,
			(SELECT IF($vatInfo='0',0,(SELECT amt*15/100))) AS vatTK
			FROM igm_details 
			INNER JOIN shed_bill_tarrif_draft ON shed_bill_tarrif_draft.rotation=igm_details.Import_Rotation_No AND shed_bill_tarrif_draft.bl_no=igm_details.BL_No
			INNER JOIN bil_tariffs ON bil_tariffs.id=shed_bill_tarrif_draft.tarrif_id
			INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no' AND 
			bil_tariffs.gl_code IN('501001','501002','501003','505001','505002','505006','403017','403019','403021','403023','403025','403027','403029','403031','403033')";
		}
		
		// //echo $qry; vatTK
		 $chargeList = $this->bm->dataSelectDb1($qry);
		
		$oneStopPoint="select distinct unit_no from assigned_unit where rotation='$imp_rot'";
		$oneStopList = $this->bm->dataSelectDb1($oneStopPoint);
		$oneStop=@$oneStopList[0]['unit_no'];
				
		//	$data['totalBillList']=$totalBillList;	// 2020-04-06
		if($appraisalDataCount==0)
		{
			$data['appraisalData']=null; 
		}
		else{
			$data['appraisalData']=$appraisalData; 
		}
		
		$data['rtnBillList']=$rtnBillList;
		$data['chargeList']=$chargeList;		// 2020-04-06
		$data['arraivalDateValue']=$arraivalDateValue;
		$data['getExRateValue']=$getExRateValue;
		//$data['sectionValue']=$this->session->userdata('section');
		$data['sectionValue']=$oneStop;
		$data['unstfDt']=$unstfDt;
		
		$data['cont_status']=$cont_status;
		
		$data['uptoDt']=$uptoDt;
		$data['rpc']=$rpc;
		$data['hcCharge']=$hcCharge;
		$data['scCharge']=$scCharge;
		$data['imp_rot']=$imp_rot;
		$data['bl_no']=$bl_no;
		
		$data['dateDiffValue']=$dateDiffValue;
		//print_r($data);
		$this->load->view('shedBillDraftView',$data);
		//	echo json_encode($data);
		//$terminal = $_POST["terminalName"];	
	}

	function tariffGenerateDraftBill($imp_rot,$bl_no,$unstfDt,$uptoDt,$rpc,$hcCharge,$scCharge)
	{
		$qry="SELECT igm_sup_detail_container.cont_number,igm_sup_detail_container.cont_status,cont_size,cont_height,rcv_pack,loc_first,shed_tally_info.cont_number,equipment,appraisement_info.equipment_id,used_equipment.equipment_name	 
		FROM  igm_supplimentary_detail
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
		LEFT JOIN appraisement_info ON igm_supplimentary_detail.Import_Rotation_No=appraisement_info.rotation AND igm_supplimentary_detail.BL_No=appraisement_info.BL_NO
		LEFT JOIN used_equipment ON used_equipment.equipment_id=appraisement_info.equipment_id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'
		GROUP BY igm_sup_detail_container.id";
		
		$conStatus = $this->bm->dataSelectDb1($qry); 
		//echo $conStatus[0]['cont_status']; 
		
		if(count($conStatus)==0)
		{
			$qry="SELECT igm_detail_container.cont_status,cont_size,cont_height,igm_detail_container.cont_number,equipment,appraisement_info_fcl.equipment_id,used_equipment.equipment_name	 
			FROM  igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON igm_details.Import_Rotation_No=appraisement_info_fcl.rotation AND igm_details.BL_No=appraisement_info_fcl.BL_NO
			LEFT JOIN used_equipment ON used_equipment.equipment_id=appraisement_info_fcl.equipment_id
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'
			GROUP BY igm_detail_container.id";
			$conStatus = $this->bm->dataSelectDb1($qry);
		}
		
		$cont_status = $conStatus[0]['cont_status'];
		if($cont_status=='LCL')
		{
			$loc_first = $conStatus[0]['loc_first'];
			$rcv_pack = $conStatus[0]['rcv_pack'];
		}
		$cont_number = $conStatus[0]['cont_number'];
		//echo "Starus==".$loc_first;
		$equip_charge = $conStatus[0]['equipment'];
		$equip_id = $conStatus[0]['equipment_id'];
		$equip_name = $conStatus[0]['equipment_name'];
		if($cont_status=="LCL")
		{
			$strRiverDues="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no', 1)),1,1)";
			$statRiverDues=$this->bm->dataInsertDB1($strRiverDues);
				
			$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',2)),1,2)";
			$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
			
			if($hcCharge!=0)
			{
				$strHostingCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',3)),1,3)";
				$statHostingCharge=$this->bm->dataInsertDB1($strHostingCharge);
			}
			else
			{
				$strDelHostingCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type=3";
				$statDelHostingCharge=$this->bm->dataInsertDB1($strDelHostingCharge);
			}
			
			if($rpc!=0)
			{
				$strScaleCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',12)),1,12)";
				$statScaleCharge=$this->bm->dataInsertDB1($strScaleCharge);
			}
			else
			{
				$strDelScaleCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type=12";
				$statDelScaleCharge=$this->bm->dataInsertDB1($strDelScaleCharge);
			}
			
			if($scCharge!=0)
			{				
				$strWeightmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',10)),1,10)";
				$statWeightmentCharge=$this->bm->dataInsertDB1($strWeightmentCharge);
			}
			else
			{				
				$strDelWeightmentCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type=10";
				$statDelWeightmentCharge=$this->bm->dataInsertDB1($strDelWeightmentCharge);
			}
			if($loc_first>0)
			{			
				$getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 4 day)),0) as dif";
				
				$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
				
				$dateDiffValue=$getDateDiff[0]['dif'];
				if($dateDiffValue>14)
				{
					//9
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',9)),1,9)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//8
					$strStuffUnStuff1="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',8)),1,8)";
					$statStuffUnStuff1=$this->bm->dataInsertDB1($strStuffUnStuff1);
					//7
					$strStuffUnStuff2="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',7)),1,7)";
					$statStuffUnStuff2=$this->bm->dataInsertDB1($strStuffUnStuff2);
				}
				else if($dateDiffValue>7 and $dateDiffValue<=14)
				{
					//7
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',7)),1,7)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//8
					$strStuffUnStuff1="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_bill_tarrif_draft('$imp_rot','$bl_no',8)),1,8)";
					$statStuffUnStuff1=$this->bm->dataInsertDB1($strStuffUnStuff1);
				}
				else if($dateDiffValue>0 and $dateDiffValue<=7)
				{
					//7
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',7)),1,7)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
			}
			//else if($loc_first<=0)
			//{
				/********************Add 4 Days*************************/
			if($rcv_pack>0)
			{				
				$dateDiffValue=$getDateDiff[0]['dif'];
				//$dateDiffValue = 18;
				if($dateDiffValue>14)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//5
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',5)),1,5)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//6
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',6)),1,6)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
				else if($dateDiffValue>7 and $dateDiffValue<=14)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//5
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',5)),1,5)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
				else if($dateDiffValue>0 and $dateDiffValue<=7)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
			}
		/* 
			$eqipmentStr="SELECT equip_for_assignment.equip_id 
						FROM shed_tally_info 
						INNER JOIN assignment_request_data ON assignment_request_data.igm_detail_id=shed_tally_info.igm_detail_id 
														OR assignment_request_data.igm_sup_dtl_id=shed_tally_info.igm_sup_detail_id
						INNER JOIN equip_for_assignment ON equip_for_assignment.assign_id=assignment_request_data.id
						WHERE shed_tally_info.verify_number='$billVerify'"; */
			$eqipmentStr="SELECT equip_for_assignment.equip_id 
						FROM shed_tally_info 
						INNER JOIN assignment_request_data ON assignment_request_data.igm_detail_id=shed_tally_info.igm_detail_id 
											OR assignment_request_data.igm_sup_dtl_id=shed_tally_info.igm_sup_detail_id
						INNER JOIN equip_for_assignment ON equip_for_assignment.assign_id=assignment_request_data.id
						LEFT JOIN igm_sup_detail_container ON shed_tally_info.igm_sup_detail_id=igm_sup_detail_container.igm_sup_detail_id
						INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id = igm_supplimentary_detail.id
						WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";

			$eqipmentRslt = $this->bm->dataSelectDb1($eqipmentStr);
			for($i=0; $i<count($eqipmentRslt); $i++)
			{
				$equip_id=$eqipmentRslt[$i]['equip_id'];
				if($equip_id==1)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',13)),1,13)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==2)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',14)),1,14)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==3)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',15)),1,15)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==4)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',16)),1,16)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==5)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',17)),1,17)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else
				{
					$strDelUsedEquipmentCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type in (13,14,15,16,17)";
					$statDelUsedEquipmentCharge=$this->bm->dataInsertDB1($strDelUsedEquipmentCharge);
				}
			}
		}
		else if($cont_status=="FCL")			// --------------------- FCL ---------------------
		{
			//echo '11111111111';
			// $cont_size = $conStatus[0]['cont_size'];
			// $cont_height = $conStatus[0]['cont_height'];
			
			// use loop to check size and height. then check the current chargeList query.
			
			$riverDues20_cnt=0;
			$riverDues40_cnt=0;
			$riverDues40HQ_cnt=0;
			$riverDues45_cnt=0;
			
			$liftOn20_cnt=0;
			$liftOn40_cnt=0;
			$liftOn40HQ_cnt=0;
			$liftOn45_cnt=0;
			
			for($i=0;$i<count($conStatus);$i++)
			{
				$cont_size = $conStatus[$i]['cont_size'];
				$cont_height = $conStatus[$i]['cont_height'];
				
				if($cont_size==20)			// container wise separate
				{
					if($riverDues20_cnt==0)
					{
						// RIVER_DUES_FCL_20
						$strRiverDues20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',18)),3,18)";
						$statRiverDues20=$this->bm->dataInsertDB1($strRiverDues20);
						$riverDues20_cnt++;
					}
					
					if($liftOn20_cnt==0)
					{
						// LIFT_ON_FCL_20
						$strLiftOn20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',21)),3,21)";
						$statLiftOn20=$this->bm->dataInsertDB1($strLiftOn20);
						$liftOn20_cnt++;					
					}
				}
				else if($cont_size==40 and $cont_height=="8.6")		// container wise separate
				{
					if($riverDues40_cnt==0)
					{
						// RIVER_DUES_FCL_40
						$strRiverDues40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',19)),3,19)";
						$statRiverDues40=$this->bm->dataInsertDB1($strRiverDues40);
						$riverDues40_cnt++;
					}
					
					if($liftOn40_cnt==0)
					{
						// LIFT_ON_FCL_40
						$strLiftOn40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',22)),3,22)";
						$strLiftOn40=$this->bm->dataInsertDB1($strLiftOn40);
						$liftOn40_cnt++;
					}
				}
				else if($cont_size==40 and $cont_height=="9.6")		// container wise separate
				{
					if($riverDues40HQ_cnt==0)
					{
						// RIVER_DUES_FCL_45
						$strRiverDues45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',20)),3,20)";
						$statRiverDues45=$this->bm->dataInsertDB1($strRiverDues45);
						$riverDues40HQ_cnt++;
					}
					
					if($liftOn40HQ_cnt==0)
					{
						// LIFT_ON_FCL_45
						$strLiftOn45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',23)),3,23)";
						$strLiftOn45=$this->bm->dataInsertDB1($strLiftOn45);
						$liftOn40HQ_cnt++;
					}
				}
				else if($cont_size==45)		// container wise separate
				{
					if($riverDues45_cnt==0)
					{
						// RIVER_DUES_FCL_45
						$strRiverDues45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',20)),3,20)";
						$statRiverDues45=$this->bm->dataInsertDB1($strRiverDues45);
						$riverDues45_cnt++;
					}
					
					if($liftOn45_cnt==0)
					{
						// LIFT_ON_FCL_45
						$strLiftOn45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',23)),3,23)";
						$strLiftOn45=$this->bm->dataInsertDB1($strLiftOn45);
						$liftOn45_cnt++;
					}
				}
			}
												
			// hc, rpc, sc start
			if($hcCharge!=0)		// one tarrif for total bill
			{
				// HOSTING_CHARGES
				$strHostingCharge="replace into shed_bill_tarrif_draft(rotation, bl_no, tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',3)),3,3)";
				$statHostingCharge=$this->bm->dataInsertDB1($strHostingCharge);
			}
			else
			{
				$strDelHostingCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type=3";
				$statDelHostingCharge=$this->bm->dataInsertDB1($strDelHostingCharge);
			}
			
			if($rpc!=0)				// one tarrif for total bill
			{
				// REPAIRING_CHARGE
				$strScaleCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',12)),3,12)";
				$statScaleCharge=$this->bm->dataInsertDB1($strScaleCharge);
			}
			else
			{
				$strDelScaleCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type=12";
				$statDelScaleCharge=$this->bm->dataInsertDB1($strDelScaleCharge);
			}
			
			if($scCharge!=0)		// one tarrif for total bill	- WEIGHMENT CHARGE
			{				
				// WEIGHMENT_CHARGE
				$strWeightmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no, tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',10)),3,10)";
				$statWeightmentCharge=$this->bm->dataInsertDB1($strWeightmentCharge);
			}
			else
			{				
				$strDelWeightmentCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' and event_type=10";
				$statDelWeightmentCharge=$this->bm->dataInsertDB1($strDelWeightmentCharge);
			}
			// hc, rpc, sc end
			
			// equipment tarrif start	
			if($equip_id==1)  //USED EQUIPMENT				// container wise separate
			{
				// FLT_1_5_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no, tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',13)),3,13)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==2)  //USED EQUIPMENT			// container wise separate
			{
				// FLT_6_20_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',14)),3,14)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==3)  //USED EQUIPMENT			// container wise separate
			{
				// FLT_21_50_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',15)),3,15)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==4)  //USED EQUIPMENT			// container wise separate
			{
				// CRANE_1_10_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',16)),3,16)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==5)  //USED EQUIPMENT			// container wise separate
			{
				// CRANE_ABOVE_10_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',17)),3,17)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else
			{
				$strDelUsedEquipmentCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type in (13,14,15,16,17)";
				$statDelUsedEquipmentCharge=$this->bm->dataInsertDB1($strDelUsedEquipmentCharge);
			}
			
			// slab - start
			$getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 4 day)),0) as dif";
			
			$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
			$dateDiffValue=$getDateDiff[0]['dif'];
			
			for($i=0;$i<count($conStatus);$i++)
			{
				$cont_size = $conStatus[$i]['cont_size'];
				$cont_height = $conStatus[$i]['cont_height'];
				
				if($dateDiffValue>20)		// over 20 days				// container wise separate
				{
					if($cont_size==20)
					{
						// 24 - 1-7
						$strStorage1stSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',24)),3,24)";
						$statStorage1stSlab20=$this->bm->dataInsertDB1($strStorage1stSlab20);
						
						// 25 - 8-20
						$strStorage2ndSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',25)),3,25)";
						$statStorage2ndSlab20=$this->bm->dataInsertDB1($strStorage2ndSlab20);
						
						// 26 - over 20
						$strStorage3rdSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',26)),3,26)";
						$statStorage3rdSlab20=$this->bm->dataInsertDB1($strStorage3rdSlab20);
					}
				//	else if($cont_size==40 and $cont_height==8.6)
					else if($cont_size==40)
					{
						//  27 - 1-7
						$strStorage1stSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',27)),3,27)";
						$statStorage1stSlab40=$this->bm->dataInsertDB1($strStorage1stSlab40);
						
						// 28 - 8-20
						$strStorage2ndSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',28)),3,28)";
						$statStorage2ndSlab40=$this->bm->dataInsertDB1($strStorage2ndSlab40);
						
						// 29 - over 20
						$strStorage3rdSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',29)),3,29)";
						$statStorage3rdSlab40=$this->bm->dataInsertDB1($strStorage3rdSlab40);
					}
					else if($cont_size==45)
					{
						// 30 - 1-7
						$strStorage1stSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',30)),3,30)";
						$statStorage1stSlab45=$this->bm->dataInsertDB1($strStorage1stSlab45);
						
						// 31 - 8-20
						$strStorage2ndSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',31)),3,31)";
						$statStorage2ndSlab45=$this->bm->dataInsertDB1($strStorage2ndSlab45);
						
						// 32 - over 20
						$strStorage3rdSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',32)),3,32)";
						$statStorage3rdSlab45=$this->bm->dataInsertDB1($strStorage3rdSlab45);
					}

				}
				else if($dateDiffValue>7 and $dateDiffValue<=20)		// 8 to 20 days
				{
					if($cont_size==20)
					{
						// 24 - 1-7
						$strStorage1stSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',24)),3,24)";
						$statStorage1stSlab20=$this->bm->dataInsertDB1($strStorage1stSlab20);
						
						// 25 - 8-20
						$strStorage2ndSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',25)),3,25)";
						$statStorage2ndSlab20=$this->bm->dataInsertDB1($strStorage2ndSlab20);
					}
				//	else if($cont_size==40 and $cont_height==8.6)
					else if($cont_size==40)
					{
						//  27 - 1-7
						$strStorage1stSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',27)),3,27)";
						$statStorage1stSlab40=$this->bm->dataInsertDB1($strStorage1stSlab40);
						
						// 28 - 8-20
						$strStorage2ndSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',28)),3,28)";
						$statStorage2ndSlab40=$this->bm->dataInsertDB1($strStorage2ndSlab40);
					}
					else if($cont_size==45)
					{
						// 30 - 1-7
						$strStorage1stSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',30)),3,30)";
						$statStorage1stSlab45=$this->bm->dataInsertDB1($strStorage1stSlab45);
						
						// 31 - 8-20
						$strStorage2ndSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',31)),3,31)";
						$statStorage2ndSlab45=$this->bm->dataInsertDB1($strStorage2ndSlab45);
					}

				}
				else if($dateDiffValue>0 and $dateDiffValue<=7)			// 1 to 7 days
				{	
					if($cont_size==20)
					{
						// 24 - 1-7
						$strStorage1stSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',24)),3,24)";
						$statStorage1stSlab20=$this->bm->dataInsertDB1($strStorage1stSlab20);
					}
				//	else if($cont_size==40 and $cont_height==8.6)
					else if($cont_size==40)
					{
						//  27 - 1-7
						$strStorage1stSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no, tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',27)),3,27)";
						$statStorage1stSlab40=$this->bm->dataInsertDB1($strStorage1stSlab40);
					}
					else if($cont_size==45)
					{
						// 30 - 1-7
						$strStorage1stSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no, tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',30)),3,30)";
						$statStorage1stSlab45=$this->bm->dataInsertDB1($strStorage1stSlab45);
					}
					// else if($cont_size==40 and $cont_height==9.6)
					
				}
			}		// for($i=0;$i<count($conStatus);$i++) - loop ends
			
			
			// slab - end
		}
	}

	function deleteROAllData()
	{
		$login_id = $this->session->userdata('login_id');
		$u_name = $this->session->userdata('User_Name');

		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$org_id = $this->session->userdata('org_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			$data['msg']="";
			$data['title']="Delete RO All Data (FCL)";
			
			if(isset($_POST['deletero']))
			{
				$imp_rot = trim($this->input->post('imp_rot'));
				$bl_no = trim($this->input->post('bl_no'));

				if(!is_null($imp_rot) && !is_null($bl_no))
				{
					// check if there any application
					$check_application_query = "SELECT COUNT(*) AS rtnValue FROM release_order_record WHERE imp_rot = '$imp_rot' AND bl_no = '$bl_no'";

					if($this->bm->dataReturnDB1($check_application_query) > 0)
					{
						// RO Application delete

						$deleteAppicationQuery = "DELETE FROM release_order_record WHERE imp_rot = '$imp_rot' AND bl_no = '$bl_no'";
						$st = $this->bm->dataDeleteDB1($deleteAppicationQuery);
						// $st = 1;
						if($st == 1)
						{

							// RO Certify delete

							$deleteCertifyQuery = "DELETE FROM certify_info_fcl WHERE rotation_no = '$imp_rot' AND bl_no = '$bl_no'";
							$st = $this->bm->dataDeleteDB1($deleteCertifyQuery);
							// $st = 1;
							if($st == 1)
							{

								// RO Unit delete

								$deleteUnitQuery = "DELETE FROM assigned_unit WHERE rotation = '$imp_rot'";
								$st = $this->bm->dataDeleteDB1($deleteUnitQuery);
								// $st = 1;
								if($st == 1)
								{

									// Appraise Delete (optional)
									$deleteAppraiseQuery = "DELETE FROM appraisement_info_fcl WHERE rotation = '$imp_rot' AND BL_NO = '$bl_no'";
									$st = $this->bm->dataDeleteDB1($deleteAppraiseQuery);
									// $st = 1;
									if($st == 1)
									{
										$this->session->set_flashdata("success", "<div class='alert alert-success'>
										<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
										<font size='4'><strong>RO Appraise deleted successfully </strong></font></div>", 3);
									}
									else
									{
										$this->session->set_flashdata("error", "<div class='alert alert-danger'>
										<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
										<font size='4'><strong>RO Appraise can't be deleted OR not found. Please try again</strong></font></div>", 3);
									}

									// Verify Delete
									$deleteVerifyQuery = "DELETE FROM verify_info_fcl WHERE rotation = '$imp_rot' AND bl_no = '$bl_no'";
									$st = $this->bm->dataDeleteDB1($deleteVerifyQuery);
									// $st = 1;
									if($st == 1)
									{

										// Take bill_no from shed_bill_master;

										$billNo_query = "SELECT bill_no as rtnValue FROM shed_bill_master WHERE import_rotation='$imp_rot' AND bl_no = '$bl_no'";
										
										if($billNo = $this->bm->dataReturnDB1($billNo_query))
										{
											// Delete Data from shed_bill_master
											$deleteBillNoQuery = "DELETE FROM shed_bill_master WHERE import_rotation='$imp_rot' AND bl_no = '$bl_no'";
											$st = $this->bm->dataDeleteDB1($deleteBillNoQuery);
											// $st = 1;
											if($st == 1)
											{
												
												// Delete Data from shed_bill_details
												$deleteBillDetailsQuery = "DELETE FROM shed_bill_details WHERE bill_no = '$billNo'";
												$st = $this->bm->dataDeleteDB1($deleteBillDetailsQuery);
												// $st = 1;
												if($st == 1)
												{
													$this->session->set_flashdata("success", "<div class='alert alert-success'>
													<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
													<font size='4'><strong>Bill details deleted successfully </strong></font></div>", 3);
												}
												else
												{
													$this->session->set_flashdata("error", "<div class='alert alert-danger'>
													<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
													<font size='4'><strong>Bill details can't be deleted OR not found. Please try again</strong></font></div>", 3);
												}

												// Delete Data from bank_bill_recv
												$deleteBankBillRecvQuery = "DELETE FROM bank_bill_recv WHERE bill_no = '$billNo'";
												$st = $this->bm->dataDeleteDB1($deleteBankBillRecvQuery);
												// $st = 1;
												if($st == 1)
												{
													$this->session->set_flashdata("success", "<div class='alert alert-success'>
													<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
													<font size='4'><strong>Bank Bill Data deleted successfully </strong></font></div>", 3);
												}
												else
												{
													$this->session->set_flashdata("error", "<div class='alert alert-danger'>
													<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
													<font size='4'><strong>Bank Bill Data can't be deleted OR not found. Please try again</strong></font></div>", 3);
												}
											}
											else
											{
												$this->session->set_flashdata("error", "<div class='alert alert-danger'>
												<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
												<font size='4'><strong>Shed Bill can't be deleted OR not found. Please try again</strong></font></div>", 3);
											}
										}

									}
									else
									{
										$this->session->set_flashdata("error", "<div class='alert alert-danger'>
										<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
										<font size='4'><strong>RO Verify can't be deleted OR not found. Please try again</strong></font></div>", 3);
									}
								}
								else
								{
									$this->session->set_flashdata("error", "<div class='alert alert-danger'>
									<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
									<font size='4'><strong>RO Unit can't be deleted OR not found. Please try again</strong></font></div>", 3);
								}
							}
							else
							{
								$this->session->set_flashdata("error", "<div class='alert alert-danger'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<font size='4'><strong>RO Certify can't be deleted OR not found. Please try again</strong></font></div>", 3);
							}
						}
						else
						{
							$this->session->set_flashdata("error", "<div class='alert alert-danger'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<font size='4'><strong>RO Application can't be deleted OR not found. Please try again</strong></font></div>", 3);
						}
					}
					else
					{
						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<font size='4'><strong>No application found for rotation: {$imp_rot} and bl: {$bl_no}</strong></font></div>", 3);
					}
				}
				else
				{
					$this->session->set_flashdata("error", "<div class='alert alert-danger'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<font size='4'><strong>Rotation and BL can't be empty</strong></font></div>", 3);
				}

				redirect('ReleaseOrderController/deleteROAllData/', 'refresh');
				
			}
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('deleteROAllData',$data);
			$this->load->view('jsAssets');
		}
	}

	function appraisementList()
	{
		$login_id = $this->session->userdata('login_id');
		$u_name = $this->session->userdata('User_Name');
		$LoginStat = $this->session->userdata('LoginStat');
		$section = $this->session->userdata('section');
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			$data['title']="Appraisement List";

			$apppraise_query = "SELECT gkey,rotation,BL_NO,cnf_name,appraise_date,user_id FROM appraisement_info_fcl ORDER BY gkey DESC";
			$appraise = $this->bm->dataSelectDB1($apppraise_query);

			$data['appraise'] = $appraise;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('appraisementList',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function certifyList()
	{
		$login_id = $this->session->userdata('login_id');
		$u_name = $this->session->userdata('User_Name');
		$LoginStat = $this->session->userdata('LoginStat');
		$section = $this->session->userdata('section');
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			$data['title']="Certify List";

			$certify_query = "SELECT id,rotation_no,bl_no,cnf_name,update_by,last_update, 'FCL' AS container_status
			FROM certify_info_fcl 
			WHERE rotation_no IS NOT NULL AND bl_no IS NOT NULL 
			
			UNION ALL
			
			SELECT verify_other_data.id, import_rotation AS rotation_no, IFNULL(igm_supplimentary_detail.BL_No,igm_details.BL_No) AS bl_no,
			cnf_name, verify_other_data.update_by, verify_other_data.last_update, 'LCL' AS container_status
			FROM verify_other_data
			INNER JOIN shed_tally_info ON shed_tally_info.id = verify_other_data.shed_tally_id
			LEFT JOIN igm_details ON shed_tally_info.igm_detail_id = igm_details.id
			LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
			WHERE shed_tally_info.wr_upto_date IS NOT NULL ORDER BY last_update DESC";

			$certify = $this->bm->dataSelectDB1($certify_query);

			$data['certify'] = $certify;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('certifyList',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function CertifyDelete()
	{
		$login_id = $this->session->userdata('login_id');
		$u_name = $this->session->userdata('User_Name');
		$LoginStat = $this->session->userdata('LoginStat');
		$section = $this->session->userdata('section');
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			$id = $this->input->post('id');		// certify_info_fcl id for FCL && verify_other_data id for LCL
			$rotation = $this->input->post('ddl_imp_rot_no');
			$bl = $this->input->post('ddl_bl_no');
			$status = $this->input->post('status');

			if($status == "FCL")
			{
				$delete_certify_sql = "DELETE FROM certify_info_fcl WHERE id = '$id'";
				if($this->bm->dataDeleteDB1($delete_certify_sql)){
					$this->session->set_flashdata("success", "<div class='alert alert-success'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<font size='4'><strong>Certify deleted Succesfully.</strong></font></div>", 3);
				}

			}
			else if($status == "LCL")
			{
				$delete_certify_sql = "DELETE FROM verify_other_data WHERE id = '$id'";
				if($this->bm->dataDeleteDB1($delete_certify_sql)){
					$this->session->set_flashdata("success", "<div class='alert alert-success'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<font size='4'><strong>Certify deleted Succesfully.</strong></font></div>", 3);
				}
			}

			// return;

			redirect('ReleaseOrderController/certifyList/', 'refresh');
		}
	}

	function verifyList()
	{
		$login_id = $this->session->userdata('login_id');
		$u_name = $this->session->userdata('User_Name');
		$LoginStat = $this->session->userdata('LoginStat');
		$section = $this->session->userdata('section');
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			$data['title']="Verify List";

			$verify_query = "SELECT verify_info_fcl.id,rotation,bl_no, Organization_Name, verify_time, verify_by
			FROM verify_info_fcl
			INNER JOIN organization_profiles ON organization_profiles.License_No = verify_info_fcl.cnf_lic_no
			WHERE bl_no IS NOT NULL AND rotation IS NOT NULL
			ORDER BY verify_info_fcl.id DESC";
			$verify = $this->bm->dataSelectDB1($verify_query);

			$data['verify'] = $verify;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('verifyList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
}