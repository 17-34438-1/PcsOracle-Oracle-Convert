<?php
class LCL extends CI_Controller {
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

	function lcl_ntsdeliveryList()
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$cnfLic = $this->session->userdata('org_license');
			//echo $cnfLic;
			//return;
			$cnfLic = explode("/", $cnfLic);
			$cnfLic_firstpart = $cnfLic[0];
			/* ECHO $cnfLic_firstpart;
			RETURN; */
			$org_Type_id = $this->session->userdata('org_Type_id');
			if($org_Type_id==2)
			{
				$str="SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
				igm_details.Pack_Description, igm_details.Pack_Number, 
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,date(cp_date) as cp_date,'dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id
				FROM oracle_nts_data
				INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%'	

				UNION 

				SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,date(cp_date) as cp_date,'sup_dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id
				FROM oracle_nts_data
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
				WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%'";

			}
			else
			{
				$str="SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
				igm_details.Pack_Description, igm_details.Pack_Number, 
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,date(cp_date) as cp_date,'dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id
				FROM oracle_nts_data
				INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW())  

				UNION 

				SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,date(cp_date) as cp_date,'sup_dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id
				FROM oracle_nts_data
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
				WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW())  ";
			}

/* 			echo $str;
			return;
 */			$rtnContainerList = $this->bm->dataSelectDb1($str);
			$data['rtnContainerList']=$rtnContainerList;

			$yard_query = "SELECT DISTINCT shed_yard FROM shed_tally_info WHERE shed_yard LIKE '%Shed%' OR shed_yard IN ('CFS/CCT','CFS/NCT')";
			$yardList = $this->bm->dataSelectDb1($yard_query);
			$data['yardList']=$yardList;

			$data['org_Type_id'] = $org_Type_id;
			$data['title']="LCL Delivery List";
			$data['msg'] = "";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lcl_nts_deliveryList',$data);
			$this->load->view('jsAssetsList');
		}
	}


	function cnfTruckEntryLCL($rotNo=null,$blNo=null,$cont_status=null,$assignmentType=null,$msg=null)
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$org_license = $this->session->userdata('org_license');
			$login_id = $this->session->userdata('login_id');
			$ip_address = $_SERVER['REMOTE_ADDR'];
			
			// echo $rotNo;
			// echo $blNo;
			
			// $rotNo = "";
			// $blNo = "";
			// $cont_status = "";
			$verifyReport = "";
			$sql_posYardBlock = "";
			$rtnVerifyReport = null;
			$cont_blocked_status = "";
			$jettyEdit = 0;

			if($rotNo==null and $cont_status==null and $assignmentType==null)		// when function is called from list
			{
				$rotNo = $this->input->post('rotNo');
				$blNo = $this->input->post('blNo');
				$cont_status = $this->input->post('cont_status');
				$igm_type = $this->input->post('igmType');
				$igm_id = $this->input->post('igm_id');
				$verify_no = $this->input->post('verify_no');			
				$cp_no = $this->input->post('cp_no');

				$lclChk_query = "SELECT COUNT(*) AS rtnValue
				FROM lcl_dlv_assignment
				WHERE rot_no = '$rotNo' AND bl_no = '$blNo' AND cnf_lic_no = '$org_license' AND igm_sup_dtl_id = '$igm_id' AND igm_type = '$igm_type'";
				// echo $lclChk_query;return;
				$rslt_lclChk = $this->bm->dataSelectDB1($lclChk_query);

				$count = 0;

				for($a=0;$a<count($rslt_lclChk);$a++){
					$count = $rslt_lclChk[$a]['rtnValue'];
				}

				if($count == 0){
					$lclDlv_query = "INSERT INTO lcl_dlv_assignment (igm_sup_dtl_id,rot_no,bl_no,cp_no,cnf_lic_no,deliveryDt,igm_type,verify_num,entry_by,entry_at,entry_ip) VALUES('$igm_id','$rotNo','$blNo','$cp_no','$org_license',date(NOW()),'$igm_type','$verify_no','$login_id',NOW(),'$ip_address')";
					$this->bm->dataInsertDB1($lclDlv_query);
				}
				
				
			}
			else
			{
				$lcl_dlv_query = "SELECT id,igm_sup_dtl_id FROM lcl_dlv_assignment WHERE rot_no = '$rotNo' AND bl_no = '$blNo' AND cnf_lic_no = '$org_license'";
				$rslt_lcl_dlv = $this->bm->dataSelectDB1($lcl_dlv_query);

				$igm_id = 0;

				for($a=0;$a<count($rslt_lcl_dlv);$a++){
					$igm_id = $rslt_lcl_dlv[$a]['igm_sup_dtl_id'];
				}
				//echo $igm_id;
			}


			if($this->input->post('editId') || $this->input->post('delId') || $this->input->post('payAllBtn') || $this->input->post('addBtn') || $this->input->post('deliver') || $this->input->post('payBtn') || $this->input->post('payment')){  
				$assignmentType = "";
				$msg = "";
			}
			
			$title= "TRUCK DETAIL ENTRY FORM";

			if($this->input->post('search') or ($assignmentType==null))
			//if($this->input->post('search'))
			{
				$editVal = 0;
				$addVal = 0;
				$payVal = 0;
				$payForm = 0;
				$msg = " ";
				// echo $rotNo;
				// echo $blNo;

				if($this->input->post('delBtn'))
				{
					$editVal = 0;
					
					$editId = $this->input->post('editId');
					$btnType = $this->input->post('btnType');
					$contNo = $this->input->post('contNo');
					$rotNo = $this->input->post('rotNo');
					$cont_status = $this->input->post('cont_status');
					
					$editType = $this->input->post('editBtn');
					$data['editType']=$editType;	
					
					$delId = $this->input->post('delId');	
					$sql_select = "select * from do_truck_details_entry WHERE id='$delId'";
					$rslt_select = $this->bm->dataSelectDB1($sql_select);

					$id = "";
					$verify_info_fcl_id = "";
					$verify_other_data_id = "";
					$verify_number = "";
					$import_rotation = "";
					$cont_no = "";
					$truck_id = "";
					$gate_no = "";
					$driver_name = "";
					$driver_gate_pass = "";
					$assistant_name = "";
					$assistant_gate_pass = "";
					$truck_agency_name = "";
					$truck_agency_phone = "";
					$last_update = "";
					$ip_addr = "";
					$update_by = "";
					$paid_amt = "";
					$paid_status = "";
					$paid_method = "";
					$visit_time_slot_start = "";
					$visit_time_slot_end = "";
					$emrgncy_flag = "";
					$emrgncy_approve_stat = "";
					$is_confirm = "";
					$driver_id = "";
					$helper_id = "";

					for($z=0;$z<count($rslt_select);$z++){
						$id = $rslt_select[$z]['id'];
						$verify_info_fcl_id = $rslt_select[$z]['verify_info_fcl_id'];
						$verify_other_data_id = $rslt_select[$z]['verify_other_data_id'];
						$verify_number = $rslt_select[$z]['verify_number'];
						$import_rotation = $rslt_select[$z]['import_rotation'];
						$cont_no = $rslt_select[$z]['cont_no'];
						$truck_id = $rslt_select[$z]['truck_id'];
						$gate_no = $rslt_select[$z]['gate_no'];
						$driver_name = $rslt_select[$z]['driver_name'];
						$driver_gate_pass = $rslt_select[$z]['driver_gate_pass'];
						$assistant_name = $rslt_select[$z]['assistant_name'];
						$assistant_gate_pass = $rslt_select[$z]['assistant_gate_pass'];
						$truck_agency_name = $rslt_select[$z]['truck_agency_name'];
						$truck_agency_phone = $rslt_select[$z]['truck_agency_phone'];
						$last_update = $rslt_select[$z]['last_update'];
						$ip_addr = $rslt_select[$z]['ip_addr'];
						$update_by = $rslt_select[$z]['update_by'];
						$paid_amt = $rslt_select[$z]['paid_amt'];
						$paid_status = $rslt_select[$z]['paid_status'];
						$paid_method = $rslt_select[$z]['paid_method'];
						$visit_time_slot_start = $rslt_select[$z]['visit_time_slot_start'];
						$visit_time_slot_end = $rslt_select[$z]['visit_time_slot_end'];
						$emrgncy_flag = $rslt_select[$z]['emrgncy_flag'];
						$emrgncy_approve_stat = $rslt_select[$z]['emrgncy_approve_stat'];
						$is_confirm = $rslt_select[$z]['is_confirm'];
						$driver_id = $rslt_select[$z]['driver_id'];
						$helper_id = $rslt_select[$z]['helper_id'];
					}

					$sql_log = "INSERT INTO delete_log_do_truck_details(visit_id,verify_info_fcl_id,verify_other_data_id,verify_number,import_rotation,cont_no,	truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,last_update,ip_addr,update_by,paid_amt,paid_status,paid_method,visit_time_slot_start,visit_time_slot_end,emrgncy_flag,emrgncy_approve_stat,is_confirm,driver_id,helper_id,deleted_by,deleted_time,delete_by_ip) VALUES('$id','$verify_info_fcl_id','$verify_other_data_id','$verify_number','$import_rotation','$cont_no','$truck_id','$gate_no','$driver_name','$driver_gate_pass','$assistant_name','$assistant_gate_pass','$truck_agency_name','$truck_agency_phone','$last_update','$ip_addr','$update_by','$paid_amt','$paid_status','$paid_method','$visit_time_slot_start','$visit_time_slot_end','$emrgncy_flag','$emrgncy_approve_stat','$is_confirm','$driver_id','$helper_id','$login_id',NOW(),'$ip_address')";
					$this->bm->dataInsertDB1($sql_log);
					
					$sql_delete = "DELETE  FROM do_truck_details_entry WHERE id='$delId'";
					$del_st = $this->bm->dataDeleteDB1($sql_delete);
							
				}
				
				// echo $rotNo;
				// echo $blNo;
				
				// $verifyReport = "SELECT shed_bill_master.bill_no,shed_tally_info.verify_number,shed_tally_info.verify_unit AS unit_no,igm_supplimentary_detail.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_supplimentary_detail.BL_No AS bl_no, igm_supplimentary_detail.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_sup_detail_container.cont_number,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_height,
				// igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,verify_other_data.no_of_truck 
				// FROM igm_sup_detail_container 
				// LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
				// LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				// LEFT JOIN verify_other_data ON verify_other_data.shed_tally_id=shed_tally_info.id
				// LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = shed_tally_info.verify_number 
				// LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				// LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
				// WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_no='$blNo'";
				
				// $verifyReport = "SELECT shed_bill_master.bill_no,shed_tally_info.verify_number,shed_tally_info.verify_unit AS unit_no,igm_supplimentary_detail.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_supplimentary_detail.BL_No AS bl_no, igm_supplimentary_detail.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_sup_detail_container.cont_number,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_height,
				// igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,lcl_dlv_assignment.no_of_truck 
				// FROM igm_sup_detail_container 
				// LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
				// LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				// LEFT JOIN lcl_dlv_assignment ON lcl_dlv_assignment.igm_sup_dtl_id = igm_supplimentary_detail.id
				// LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = shed_tally_info.verify_number 
				// LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				// LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
				// WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_no='$blNo'";

				$verifyReport = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no AS verify_number,verify_date,imp_rot_no AS import_rotation,
				igm_details.Pack_Description, igm_details.Pack_Number, igm_masters.Vessel_Name AS vessel_name,
				igm_details.Description_of_Goods, igm_detail_container.cont_size, igm_detail_container.cont_height,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,cp_date,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id
				FROM oracle_nts_data
				INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
				WHERE igm_detail_container.cont_status='LCL' AND oracle_nts_data.imp_rot_no='$rotNo' AND oracle_nts_data.bl_no='$blNo'
				
				UNION 
				
				SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no AS verify_number,verify_date,imp_rot_no AS import_rotation, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number, igm_masters.Vessel_Name AS vessel_name,
				igm_supplimentary_detail.Description_of_Goods, igm_sup_detail_container.cont_size, igm_sup_detail_container.cont_height,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,cp_date,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id
				FROM oracle_nts_data
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
				LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
				WHERE igm_sup_detail_container.cont_status='LCL' AND oracle_nts_data.imp_rot_no='$rotNo' AND oracle_nts_data.bl_no='$blNo'";

				$rtnVerifyReport = $this->bm->dataSelectDB1($verifyReport);	

				if(count($rtnVerifyReport)==0)		// don't proceed
				{
					$msg = "<font color='red'>Rotation and BL are not matching</font>";
				}
				else		// proceed
				{
					// work later for yard and block
					$sql_posYardBlock = "SELECT slot AS currentPos,Yard_No,Block_No,assignmentDate
					FROM ctmsmis.tmp_oracle_assignment 
					WHERE rot_no='$rotNo' AND cont_no='".$rtnVerifyReport[0]['cont_number']."'";						
					$rslt_posYardBlock = $this->bm->dataSelect($sql_posYardBlock);
					
					$data['rslt_posYardBlock'] = $rslt_posYardBlock;
					
					// if($rtnVerifyReport[0]['cont_size']==20)
						// $totTruck = 2;
					// else if($rtnVerifyReport[0]['cont_size']==40 or $rtnVerifyReport[0]['cont_size']==45)
						// $totTruck = 3;
					// $data['totTruck'] = $totTruck;
					
					// $totTruck = $rtnVerifyReport[0]['no_of_truck'];    //04-04-2021
					// $data['totTruck'] = $totTruck;
					
					// $editVal = 0;
					// $addVal = 0;
					// $payVal = 0;
					// $payForm = 0;
					
					// 
					$sql_slotQty = "SELECT slot_1_qty,slot_2_qty,slot_3_qty
					FROM vcms_truck_slot";
					$rslt_slotQty = $this->bm->dataSelectDB1($sql_slotQty);
					$data['rslt_slotQty']=$rslt_slotQty;
					
					
					// driver helper info
					$sql_driverInfo = "SELECT id,card_number,agent_name
					FROM vcms_vehicle_agent
					WHERE agent_type='Driver'";
					$rslt_driverInfo = $this->bm->dataSelectDB1($sql_driverInfo);
					$data['rslt_driverInfo']=$rslt_driverInfo;
					
					$sql_helperInfo = "SELECT id,card_number,agent_name
					FROM vcms_vehicle_agent
					WHERE agent_type='Helper'";
					$rslt_helperInfo = $this->bm->dataSelectDB1($sql_helperInfo);
					$data['rslt_helperInfo']=$rslt_helperInfo;

					if($this->input->post('jettyedit')){
						$jettyEdit = 1;
					}
					
					// $sql_vrfyOtherDataId = "SELECT id FROM verify_other_data 
					// WHERE rotation='$rotNo' AND cont_number='".$rtnVerifyReport[0]['cont_number']."'";
					
					// $sql_vrfyOtherDataId = "SELECT verify_other_data.id
					// FROM verify_other_data
					// INNER JOIN shed_tally_info ON shed_tally_info.id=verify_other_data.shed_tally_id
					// WHERE shed_tally_info.import_rotation = '$rotNo' AND shed_tally_info.cont_number = '".$rtnVerifyReport[0]['cont_number']."'";

					//Query changed at 4-4-2021 temporary

					// $sql_vrfyOtherDataId = "SELECT id,igm_sup_detail_id FROM verify_other_data WHERE igm_sup_detail_id = (SELECT igm_supplimentary_detail.id FROM igm_supplimentary_detail 
					// INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id = igm_supplimentary_detail.id
					// WHERE cont_number = '".$rtnVerifyReport[0]['cont_number']."' AND Import_Rotation_No='$rotNo' AND BL_No='$blNo')";
					
					// taken lcl_dlv_assignment id 
					
					$sql_vrfyOtherDataId = "SELECT id,igm_sup_dtl_id FROM lcl_dlv_assignment WHERE igm_sup_dtl_id = '$igm_id'";
					$rslt_vrfyOtherDataId = $this->bm->dataSelectDB1($sql_vrfyOtherDataId);
					$vrfyOtherDataId = $rslt_vrfyOtherDataId[0]['id'];;   // lcl_dlv_assignment id 
					$igmSupDtlId = $rslt_vrfyOtherDataId[0]['igm_sup_dtl_id'];
					$data['igm_id']=$igmSupDtlId;
					$data['vrfyOtherDataId']=$vrfyOtherDataId;
					//return;

					// $totTruckQuery = "SELECT no_of_truck FROM verify_other_data WHERE id='$vrfyOtherDataId'";
					// $rslt_totTruckQuery = $this->bm->dataSelectDB1($totTruckQuery);

					//$totTruck = $rtnVerifyReport[0]['no_of_truck'];
					if($rtnVerifyReport[0]['cont_size']==20)
						$totTruck = 2;
					else if($rtnVerifyReport[0]['cont_size']==40 or $rtnVerifyReport[0]['cont_size']==45)
						$totTruck = 3;

					$data['totTruck'] = $totTruck;

					$sql_tmpTrkData = "SELECT id,verify_other_data_id,truck_id,delv_pack AS pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_status,paid_method,emrgncy_flag,emrgncy_approve_stat,gate_out_status
					FROM do_truck_details_entry
					WHERE verify_other_data_id = '$vrfyOtherDataId'";
					// echo $sql_tmpTrkData; return;
					$rslt_tmpTrkData = $this->bm->dataSelectDB1($sql_tmpTrkData);
					$data['rslt_tmpTrkData']=$rslt_tmpTrkData;
					
					$jetty_sirkar_id = "";
					
					// $sql_jettySirkarId = "SELECT jetty_sirkar_id
					// FROM verify_other_data
					// INNER JOIN shed_tally_info ON shed_tally_info.id=verify_other_data.shed_tally_id
					// WHERE shed_tally_info.import_rotation = '$rotNo' AND shed_tally_info.cont_number = '".$rtnVerifyReport[0]['cont_number']."'";
					
					//Query changed at 4-4-2021 temporary
					// $sql_jettySirkarId = "SELECT jetty_sirkar_id FROM verify_other_data WHERE igm_sup_detail_id='$igmSupDtlId'";
					$sql_jettySirkarId = "SELECT jetty_sirkar_id FROM lcl_dlv_assignment WHERE igm_sup_dtl_id='$igmSupDtlId'";
					$rslt_jettySirkarId = $this->bm->dataSelectDB1($sql_jettySirkarId);
					
					if(count($rslt_jettySirkarId)>0)
						$jetty_sirkar_id = $rslt_jettySirkarId[0]['jetty_sirkar_id'];
					
					$data['jetty_sirkar_id']=$jetty_sirkar_id;
					
					// importer mobile no
					// $sql_importerMobile = "SELECT importer_mobile_no FROM verify_other_data WHERE id='$vrfyOtherDataId'";
					$sql_importerMobile = "SELECT importer_mobile_no FROM lcl_dlv_assignment WHERE id='$vrfyOtherDataId'";
					$rslt_importerMobile = $this->bm->dataSelectDB1($sql_importerMobile);
					$importerMobile = $rslt_importerMobile[0]['importer_mobile_no'];
					$data['importerMobile']=$importerMobile;
					
					// truck slot
					$truckSlot = "";
					
					// $sql_truckSlot = "SELECT truck_slot FROM verify_other_data WHERE id='$vrfyOtherDataId'";
					$sql_truckSlot = "SELECT truck_slot FROM lcl_dlv_assignment WHERE id='$vrfyOtherDataId'";
					$rslt_truckSlot = $this->bm->dataSelectDB1($sql_truckSlot);
					if(count($rslt_truckSlot)>0)
					{
						$truckSlot = $rslt_truckSlot[0]['truck_slot'];
					}
					$data['truckSlot']=$truckSlot;
					
					$sql_dlvDt = "SELECT deliveryDt FROM lcl_dlv_assignment WHERE id='$vrfyOtherDataId'";
					$rslt_dlvDt = $this->bm->dataSelectDB1($sql_dlvDt);
					$blck = "";
					$sltAssignDt = "";
					if(count($rslt_dlvDt)>0)
					{
						//$blck = $rslt_posYardBlock[0]["Block_No"];
						$sltAssignDt = $rslt_dlvDt[0]["deliveryDt"];
					}
					$data['sltAssignDt']=$sltAssignDt;
					
					// Will Work later on this 2021-03-23
					
					$strGetSlotCnt1 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=1";
					$SlotCnt1 = $this->bm->dataReturn($strGetSlotCnt1);
					$data['SlotCnt1']=$SlotCnt1;
					
					$strGetSlotCnt2 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=2";
					$SlotCnt2 = $this->bm->dataReturn($strGetSlotCnt2);
					$data['SlotCnt2']=$SlotCnt2;

					$strGetSlotCnt3 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=3";
					$SlotCnt3 = $this->bm->dataReturn($strGetSlotCnt3);
					$data['SlotCnt3']=$SlotCnt3;
					
					// tab 2 - js info
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agency_code = '$org_license' AND agent_type = 'Jetty Sircar'";

					$rslt_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
					$data['rslt_jsInfo']=$rslt_jsInfo;

					if($this->input->post('payType')=="singlePay" || $this->input->post('payType')=="allPay")
					{
						$payForm = 1;
						$editVal = 0;
						
						if($this->input->post('payType')=="singlePay")
						{						
							$truckDtlId = $this->input->post('truckDtlId');
							$payAmt = $this->input->post('payAmt');
							$payMethod = $this->input->post('payMethod');
							$payFlag = "singlePay";
							
							$data["truckDtlId"] = $truckDtlId;
												
						}
						else if($this->input->post('payType')=="allPay")
						{			
							$payAmt = $this->input->post('totalAmtToPay');
							$vrfyInfoFclId = $this->input->post('vrfyInfoFclId');
							// $payAmt = 57.5;
							$payMethod = "cash";
							$payFlag = "allPay";
							
							$data["vrfyInfoFclId"] = $vrfyInfoFclId;
						}
						$data["payAmt"] = $payAmt;
						$data["Method"] = $payMethod;
						$data["payFlag"] = $payFlag;
						
					}

					
					if($this->input->post('payment'))
					{
						$addVal = 1;
						$payForm = 2;
					}

					if($this->input->post('editId'))
					{
						$editVal = 1;
						
						$editId = $this->input->post('editId');
						$btnType = $this->input->post('btnType');
						$contNo = $this->input->post('contNo');
						$rotNo = $this->input->post('rotNo');
						$cont_status = $this->input->post('cont_status');
						
						$editType = $this->input->post('editBtn');
						$data['editType']=$editType;	
						
						$sql_trkEditInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,truck_agency_name,truck_agency_phone,
						(SELECT mobile_number FROM vcms_vehicle_agent WHERE card_number=driver_gate_pass LIMIT 1) AS driver_mobile_number,
						assistant_name,assistant_gate_pass,
						(SELECT mobile_number FROM vcms_vehicle_agent WHERE card_number=assistant_gate_pass LIMIT 1) AS helper_mobile_number
						FROM do_truck_details_entry
						WHERE id='$editId'";
						$rslt_trkEditInfo = $this->bm->dataSelectDB1($sql_trkEditInfo);
						$data['rslt_trkEditInfo']=$rslt_trkEditInfo;				
									
					}
					if($this->input->post('delId'))
					{
						$editVal = 0;
						
						$editId = $this->input->post('editId');
						$btnType = $this->input->post('btnType');
						$contNo = $this->input->post('contNo');
						$rotNo = $this->input->post('rotNo');
						$cont_status = $this->input->post('cont_status');
						
						$editType = $this->input->post('editBtn');
						$data['editType']=$editType;	
						
						$delId = $this->input->post('delId');	
					/* 	ECHO "DEL";					
						$sql_trkEditInfo = "UPDATE do_truck_details_entry  SET  paid_status=1 WHERE id='$delId'";
						$update = $this->bm->dataUpdateDB1($sql_trkEditInfo);
						echo "11111111111"; */
						$sql_delete = "DELETE  FROM do_truck_details_entry WHERE id='$delId'";
						$del_st = $this->bm->dataDeleteDB1($sql_delete);
								
					}
				}					
			}
			else
			{
				$editVal = 0;
				$addVal = 0;
				$payVal = 0;
				$payForm = 0;
			}
			
			$data['msg']=$msg;
			$data['rotNo']=$rotNo;
			// $data['contNo']=$contNo;
			$data['blNo']=$blNo;
			$data['cont_status']=$cont_status;
			$data['editVal']=$editVal;
			$data['addVal']=$addVal;
			$data['payVal']=$payVal;
			$data['payForm']=$payForm;
			$data['jettyEdit']=$jettyEdit;
			$data['rtnVerifyReport']=$rtnVerifyReport;
			$data['cont_blocked_status']=$cont_blocked_status;
			$data['title']=$title;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('cnfTruckEntryFormLCL_nts',$data);			
			$this->load->view('jsAssets');
		}
	}


	function jettySarkarEntry()
	{
		$msg = "";
		$login_id = $this->session->userdata('login_id');
		$rotNo = $this->input->post('rotNo');
		$contNo = $this->input->post('contNo');
		$blNo = $this->input->post('blNo');
		$cont_status = $this->input->post('cont_status');
		$assignmentType = $this->input->post('assignmentType');
		$vrfyInfoFclId = $this->input->post('vrfyInfoFclId');
		$vrfyOtherDataId = $this->input->post('vrfyOtherDataId');
		$jsName = $this->input->post('jsName');
		$jsId = $this->input->post('jsId');

		if(!$this->input->post('jettyedit'))
		{
			// chk jetty sarkar
			// $sql_chkJS = "SELECT COUNT(*) AS rtnValue
			// FROM verify_info_fcl
			// WHERE jetty_sirkar_id='$jsName' AND id='$vrfyInfoFclId'";
			
			if($cont_status == "LCL")
			{												
				$sql_chkJS = "SELECT COUNT(*) AS rtnValue
				FROM lcl_dlv_assignment
				WHERE jetty_sirkar_id='$jsId' AND id='$vrfyOtherDataId'";

				$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
				$chkJS = $rslt_chkJS[0]['rtnValue'];

				if($chkJS == 0)
				{
					$prevJS = "";
					// get previous JS	- check if previous exists
					// $sql_prevJS = "SELECT jetty_sirkar_id
					// FROM verify_other_data
					// WHERE id='$vrfyOtherDataId'";
					$sql_prevJS = "SELECT jetty_sirkar_id
					FROM lcl_dlv_assignment
					WHERE id='$vrfyOtherDataId'";
					$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
					
					// Insert into log
					// make new for lcl
					// if($prevJS!="" or $prevJS!=null)
					// {
						// $sql_jsLog = "INSERT INTO vcms_jetty_sirkar_log(verify_info_fcl_id,prev_jetty_sirkar_id,replace_by,replace_dt)
						// VALUES('$vrfyInfoFclId','$prevJS','$login_id',NOW())";
						// $this->bm->dataInsertDB1($sql_jsLog);
					// }
					
					// Update JS
					// $sql_updateJS = "UPDATE verify_other_data
					// SET jetty_sirkar_id='$jsId'
					// WHERE id='$vrfyOtherDataId'";
					$sql_updateJS = "UPDATE lcl_dlv_assignment
					SET jetty_sirkar_id='$jsId'
					WHERE id='$vrfyOtherDataId'";
					// return;
					$this->bm->dataUpdateDB1($sql_updateJS);
				}
				
				// $this->cnfTruckEntryLCL($rotNo=null,$contNo=null,$cont_status=null,"","");
				$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,"","");
			}
			else
			{			
				$sql_chkJS = "SELECT COUNT(*) AS rtnValue
				FROM verify_info_fcl
				WHERE jetty_sirkar_id='$jsId' AND id='$vrfyInfoFclId'";
				$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
				$chkJS = $rslt_chkJS[0]['rtnValue'];
				
				if($chkJS == 0)
				{
					$prevJS = "";
					// get previous JS	- check if previous exists
					$sql_prevJS = "SELECT jetty_sirkar_id
					FROM verify_info_fcl
					WHERE id='$vrfyInfoFclId'";
					$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
					
					// Insert into log
					if($prevJS!="" or $prevJS!=null)
					{
						$sql_jsLog = "INSERT INTO vcms_jetty_sirkar_log(verify_info_fcl_id,prev_jetty_sirkar_id,replace_by,replace_dt)
						VALUES('$vrfyInfoFclId','$prevJS','$login_id',NOW())";
						$this->bm->dataInsertDB1($sql_jsLog);
					}
					
					// Update JS
					$sql_updateJS = "UPDATE verify_info_fcl
					SET jetty_sirkar_id='$jsId'
					WHERE id='$vrfyInfoFclId'";
					$this->bm->dataUpdateDB1($sql_updateJS);
				}
				
				$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
			}
		}				
	}


	function addTruckToDoDtlLCL()
	{		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$msg = "";
			$login_id = $this->session->userdata('login_id');		
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			$regCity = $this->input->post('regCity');
			$regClass = $this->input->post('regClass');
			$truckNo = trim($this->input->post('truckNo'));
			
			$truckId = 	$regCity." ".$regClass." ".$truckNo;
			$blNo = $this->input->post('blNo');
			
			$driverName = $this->input->post('driverName');
			$driverPassNo = $this->input->post('driverPassNo');								
			$assistantName = $this->input->post('assistantName');									
			$assistantPassNo = $this->input->post('assistantPassNo');
			$importerMobileNo = $this->input->post('importerMobileNo');	
			$importerMobileNo = str_replace("-","",$importerMobileNo);	
			$agencyName = $this->input->post('agencyName');	
			$agencyName = str_replace("'"," ",$agencyName);	
			$agencyPhone = $this->input->post('agencyPhone');
			// $res = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', $str); 		
		
			$rotNo = $this->input->post('rotNo');
			$contNo = $this->input->post('contNo');
			$vrfyOtherDataId = $this->input->post('vrfyOtherDataId');
			
			$cont_status = $this->input->post('cont_status');
			$assignmentType = $this->input->post('assignmentType');
			$totTruck = $this->input->post('totTruck');
			$addBtn = $this->input->post('addBtn');
			$frmSlot = $this->input->post('truckSlot');
			
			$emrgncy_flag = 0;
			$emrgncy_approve_stat = 0;
			if($addBtn=="Emergency")
			{
				$emrgncy_flag = 1;	
			}
			
			//$strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
			//$this->bm->dataUpdate$strUpdateSlot);
			//return;
		
			//$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
			//FROM ctmsmis.tmp_oracle_assignment
			//WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
			//$rslt_timeSlot = $this->bm->dataSelect($sql_timeSlot);
			
			// $sql_igmContId = "SELECT igm_detail_container.id FROM igm_detail_container
			// INNER JOIN igm_details ON igm_details.id =  igm_detail_container.igm_detail_id
			// WHERE cont_number = '$contNo' AND Import_Rotation_No='$rotNo'";
			// $rslt_igmContId = $this->bm->dataSelectDB1($sql_igmContId);
			
			// $igmId = "";
			
			// if(count($rslt_igmContId)>0){
			// 	$igmId = $rslt_igmContId[0]['id'];
			// }
			
			//echo $igmId;
			
			// $sql_timeSlot = "SELECT assignment_date,DATE_ADD(assignment_date, INTERVAL 1 DAY) AS nxtDt FROM lcl_assignment_detail 
			// WHERE igm_cont_detail_id = '$igmId' ORDER BY id DESC LIMIT 1";
			
			$sql_timeSlot = "SELECT deliveryDt,DATE_ADD(deliveryDt, INTERVAL 1 DAY) AS nxtDt FROM lcl_dlv_assignment WHERE id = '$vrfyOtherDataId' ORDER BY id DESC LIMIT 1";

			$rslt_timeSlot = $this->bm->dataSelectDB1($sql_timeSlot);
		
		
			$asDt = "";
			$asSlot = "";
			$nxtDt = "";
			
			for($j=0;$j<count($rslt_timeSlot);$j++)
			{
				$asDt = $rslt_timeSlot[$j]['deliveryDt'];
				$nxtDt = $rslt_timeSlot[$j]['nxtDt'];
			}
			
			$asSlot = $frmSlot;
			
			//$asDt = date("Y-m-d");
			//$asSlot = $frmSlot;
			//$nxtDt = date('Y-m-d', strtotime('+1 day', strtotime($asDt)));
		
			$sSlot = "";
			$eSlot = "";
			if($asSlot==1)
			{
				$sSlot = $asDt." 08:00:00";
				$eSlot = $asDt." 15:59:59";
			}
			else if($asSlot==2)
			{
				$sSlot = $asDt." 16:00:00";
				$eSlot = $asDt." 23:59:59";
			}
			else
			{
				$sSlot = $nxtDt." 00:00:00";
				$eSlot = $nxtDt." 07:59:59";
			}
			$payAmt = 57.5;			
															
			if($this->input->post('editFormId'))
			{
				$editFormId = $this->input->post('editFormId');
				$editType = $this->input->post('editType');
				
				if($editType == "Replace")
				{
					$sql_replaceInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,gate_in_status,gate_in_by,gate_in_time
					FROM do_truck_details_entry
					WHERE id='$editFormId'";
					$rslt_replaceInfo = $this->bm->dataSelectDB1($sql_replaceInfo);
					
					$repVisitId = $rslt_replaceInfo[0]['id'];
					$repTruckId = $rslt_replaceInfo[0]['truck_id'];
					$repDriverName = $rslt_replaceInfo[0]['driver_name'];
					$repDriverGatePass = $rslt_replaceInfo[0]['driver_gate_pass'];
					$repAssistantName = $rslt_replaceInfo[0]['assistant_name'];
					$repAssistantGatePass = $rslt_replaceInfo[0]['assistant_gate_pass'];
					$repPaidAmt = $rslt_replaceInfo[0]['paid_amt'];
					$repPaidMethod = $rslt_replaceInfo[0]['paid_method'];
					$repPaidCollectDt = $rslt_replaceInfo[0]['paid_collect_dt'];
					
					$gate_in_status = $rslt_replaceInfo[0]['gate_in_status'];
					$gate_in_by = $rslt_replaceInfo[0]['gate_in_by'];
					$gate_in_time = $rslt_replaceInfo[0]['gate_in_time'];
					
					$sql_insertReplace = "INSERT INTO vcms_replace_truck_log(visit_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,replace_time,replace_by,gate_in_status,gate_in_time,gate_in_by)
					VALUES('$repVisitId','$repTruckId','$repDriverName','$repDriverGatePass','$repAssistantName','$repAssistantGatePass','$repPaidAmt','$repPaidMethod','$repPaidCollectDt',NOW(),'$login_id','$gate_in_status','$gate_in_by','$gate_in_time')";
					$this->bm->dataInsertDB1($sql_insertReplace);
					
					$sql_updateTruckInfo = "UPDATE do_truck_details_entry
					SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',paid_amt='',paid_status=0,paid_method='',gate_in_status='0',gate_in_by=NULL,gate_in_time=NULL
					WHERE id='$editFormId'";
					$this->bm->dataUpdateDB1($sql_updateTruckInfo);
				}
				// else if($editType == "Edit")			// check with it later
				else
				{
					$sql_updateTruckInfo = "UPDATE do_truck_details_entry
					SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone'
					WHERE id='$editFormId'";
					$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
				}
				
			}			
			else							
			{
				$sql_chkTruck = "SELECT COUNT(*) AS rtnValue
				FROM do_truck_details_entry 
				WHERE truck_id='$truckId' AND visit_time_slot_start='$sSlot' AND visit_time_slot_end='$eSlot'";
				$rslt_chkTruck = $this->bm->dataSelectDB1($sql_chkTruck);
				$chkTruck = $rslt_chkTruck[0]['rtnValue'];
				
				if($chkTruck==0)
				{
					$strInsertEq = "INSERT INTO do_truck_details_entry(verify_other_data_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end,entry_from)
					VALUES('$vrfyOtherDataId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$agencyName','$agencyPhone','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot','web')";								
					
					$stat = $this->bm->dataInsertDB1($strInsertEq);
					
					if($stat == 1)
						$msg = "<font color='green'><b>Truck added successfully</b></font>";
					
				}
				else
				{
					$msg = "<font color='red'><b>This truck was assigned for this time slot previously</b></font>";
				}
			}	
								
			
			// $sql_updateImporterMbl = "UPDATE verify_other_data
			// SET importer_mobile_no='$importerMobileNo' , truck_slot = '$asSlot'
			// WHERE id='$vrfyOtherDataId'";
			$sql_updateImporterMbl = "UPDATE lcl_dlv_assignment
			SET importer_mobile_no='$importerMobileNo' , truck_slot = '$asSlot'
			WHERE id='$vrfyOtherDataId'";
			$this->bm->dataUpdateDB1($sql_updateImporterMbl);		
			
			$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,"","");
		}
	}

	function cnfTruckPayForm()
	{
		$msg = "";
		$rotNo = $this->input->post('rotNo');
		$blNo = $this->input->post('blNo');
		$cont_status = $this->input->post('cont_status');
		$assignmentType = $this->input->post('assignmentType');
		$blNo = $this->input->post('blNo');

		if($cont_status == "LCL")
		{
			$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,"","");
		}
		else if($cont_status == "FCL")
		{
			$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,$assignmentType,$msg);
		}
		
	}

	
	function cnfTruckPay()
	{
		$msg = "";
		$rotNo = $this->input->post('rotNo');
		$contNo = $this->input->post('contNo');
		$cont_status = $this->input->post('cont_status');
		$assignmentType = $this->input->post('assignmentType');
		$blNo = $this->input->post('blNo');
		$contact = $this->input->post('contact');
		$payment = $this->input->post('payment');
		if($payment == 'save')
		{
			if($this->input->post('payType')=="singlePay")
			{						
				$truckDtlId = $this->input->post('truckDtlId');
				$payAmt = $this->input->post('payAmt');
				$payMethod = $this->input->post('payMethod');
				
				$paid_sts = 0;
				$chkpayment_query = "SELECT paid_status FROM do_truck_details_entry WHERE id = '$truckDtlId'";
				$rslt_query = $this->bm->dataSelectDB1($chkpayment_query);
				for($i=0;$i<count($rslt_query);$i++){
					$paid_sts = $rslt_query[$i]['paid_status'];
				}

				if($paid_sts != 1){
					$sql_updatePayment = "UPDATE do_truck_details_entry
					SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
					WHERE id='$truckDtlId'";
					$stat = $this->bm->dataUpdateDB1($sql_updatePayment);						
				}
			}
			else if($this->input->post('payType')=="allPay")
			{			
				$totalAmtToPay = $this->input->post('payAmt');
				$payAmt = 57.5;
				$payMethod = "cash";

				if($cont_status == "LCL")
				{
					$vrfyOtherDataId = $this->input->post('vrfyOtherDataId');
					$sql_updateAllPay = "UPDATE do_truck_details_entry
					SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
					WHERE verify_other_data_id='$vrfyOtherDataId' AND paid_status='0' AND (emrgncy_flag='0' OR emrgncy_approve_stat='1')";
					$stat = $this->bm->dataUpdateDB1($sql_updateAllPay);
				}
				else if($cont_status == "FCL")
				{
					$vrfyInfoFclId = $this->input->post('vrfyInfoFclId'); 
					$sql_updateAllPay = "UPDATE do_truck_details_entry
					SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
					WHERE verify_info_fcl_id='$vrfyInfoFclId' AND paid_status='0' AND (emrgncy_flag='0' OR emrgncy_approve_stat='1')";
					$stat = $this->bm->dataUpdateDB1($sql_updateAllPay);
				}
				
				// $sql_updateAllPay = "UPDATE do_truck_details_entry
				// SET paid_amt='$payAmt',paid_method='$payMethod'
				// WHERE verify_info_fcl_id='$vrfyInfoFclId' AND paid_amt IS NULL";

				
			}
		}
		else if($payment == 'pay')
		{
			//echo "1";
			$payAmt = $this->input->post('payAmt');
			//echo $contNo.'-'.$rotNo.'-'.$assignmentType.'-'.$contact.'-'.$payAmt;
			//return;
			$this->checkoutAllbyOnline($contNo,$blNo,$rotNo,$assignmentType,$payAmt,$contact);
			return;
		}
		
		
		if($cont_status == "LCL")
		{
			$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,"","");
		}
		else if($cont_status == "FCL")
		{
			$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
		}
	}

	// Truck Loading for LCL

	function loadingFormLcl()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {

			$data['title']="Loading Process for LCL";
			$data['msg'] = "";
			$data['flag'] = 0;
			$data['disputeFlag'] = "0";
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('loadingFormLcl',$data);
			$this->load->view('jsAssets');
		}
	}

	function searchforLoadingLcl()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$visitId = $this->input->post("visitId");

			$data['visitId'] = $visitId;
	
			// $assignment_type = "";
			
			// $sql_chk_assignment_type = "SELECT do_truck_details_entry.*,verify_info_fcl.assignment_type
			// 					FROM do_truck_details_entry 
			// 					INNER JOIN verify_info_fcl on do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
			// 					WHERE do_truck_details_entry.id = '$visitId'";
			// $rslt_chk_assignment_type = $this->bm->dataSelectDB1($sql_chk_assignment_type);
			// for($a=0;$a<count($rslt_chk_assignment_type);$a++){
			// 	$assignment_type = $rslt_chk_assignment_type[$a]['assignment_type'];
			// }
			// $data['assignment_type'] = $assignment_type;

			$sql_cont = "SELECT cont_no,import_rotation,verify_other_data_id FROM do_truck_details_entry WHERE id = '$visitId'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			$import_rotation = "";
			$verify_other_data_id = null;

			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
				$import_rotation = $rslt_cont[$i]['import_rotation'];
				$verify_other_data_id = $rslt_cont[$i]['verify_other_data_id'];
			}

			if(!is_null($verify_other_data_id))
			{
				$cont_stat = $this->bm->chkContainerStatus($cont,$import_rotation);
				
				$data['cont_stat'] = $cont_stat;
				// echo $cont;
				// return;
				


				// $rslt_status = $this->chkBlockedContainer($cont);
				$rslt_status = $this->bm->chkBlockedContainer($cont,$visitId);
				//var_dump($rslt_status);
				$cont_status = "";

				for($i = 0;$i<count($rslt_status);$i++){
					$cont_status = $rslt_status[$i]['custom_block_st'];
				}

				//echo $cont_status;
				//return;

				$data['cont_status'] = $cont_status;


				$data['title']="Loading Process for LCL";
				$data['msg'] = "";
				$data['flag'] = 1;
				
				if($this->input->post("UpdateLoading"))
				{
					$sql="SELECT loading_dispute.*,igm_pack_unit.Pack_Unit as pckUnit
					FROM loading_dispute
					INNER JOIN igm_pack_unit ON loading_dispute.pack_unit=igm_pack_unit.id
					WHERE tr_visit_id='$visitId'";   
					$resDispute = $this->bm->dataSelectDb1($sql);
					$data['disputeFlag'] ="1";
					$data['resDispute'] =$resDispute;
				} 
				else 
				{
					$data['disputeFlag'] ="0";
				}
				
				
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('loadingFormLcl',$data);
				$this->load->view('jsAssets');
			}
			else
			{
				$data['title']="Loading Process LCL";
				$data['msg3'] = "<font color='red' size='4'>This is not LCL Container!</font>";
				$data['flag'] = 0;
				$data['disputeFlag'] = "0";
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('loadingFormLcl',$data);
				$this->load->view('jsAssets');
			}
		}
	}

	function truckLoadStsCngLcl()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			//$cont_id = $this->input->post("cont_no");
			$id = $this->input->post("id");
			$login_id = $this->session->userdata('login_id');
			$loadQty = $this->input->post("actual_qty");
			$packUnit = $this->input->post("pack_unit");
			$jsPass = $this->input->post("jsPass");

			// reg & bl entry -- starts 
			$cfLoginId = $this->input->post("cfLoginId");
			$assignment = $this->input->post('assignment');
			$data = explode("|",$assignment);
			$tally_shed = "";
			$contNo = "";
			$rotNo = "";
			$cont_status = "";
			$blNo = "";
			$igm_type = ""; // sup = sup_dtl  & dtl = dtl
			$igm_id = "";
			$verify_no = "";
			$cp_no = "";
			$cont_size = "";

			$cntArray = count($data);

			if($cntArray>1)
			{
				$tally_shed = $data[0];
				$contNo = $data[1];
				$rotNo = $data[2];
				$cont_status = $data[3];
				$blNo = $data[4];
				$igm_type = $data[5]; // sup = sup_dtl  & dtl = dtl
				$igm_id = $data[6];
				$verify_no = $data[7];
				$cp_no = $data[8];
				$cont_size = $data[9];
			}

			// Added by kawsar - 13/09/2022
			
			$shedQuery = "";

			if($igm_type == "sup")
			{
				$shedQuery = "UPDATE shed_tally_info SET shed_tally_info.delivery_status = 1, shed_tally_info.dlv_st = 1 WHERE igm_sup_detail_id = '$igm_id' AND import_rotation = '$rotNo'";
			}
			else if($igm_type == "sup_dtl" || $igm_type == "dtl")
			{
				$shedQuery = "UPDATE shed_tally_info SET shed_tally_info.delivery_status = 1, shed_tally_info.dlv_st = 1 WHERE igm_detail_id = '$igm_id' AND import_rotation = '$rotNo'";
			}

			$this->bm->dataUpdateDb1($shedQuery);

			// Added by kawsar - 13/09/2022

			$gate_query = "SELECT gate_no FROM shed_yard_wise_gate WHERE shed_yard = '$tally_shed'";
			$gate_rslt = $this->bm->dataselectDB1($gate_query);
			$gate = "";
			if(count($gate_rslt)>0){
				$gate = $gate_rslt[0]['gate_no'];
			}

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++){
				$org_license = $data_lic[$i]['License_No'];
			}

			$login_id = $cfLoginId;
			$sql_chkExist = "SELECT COUNT(*) AS rtnValue 
			FROM lcl_dlv_assignment 
			WHERE rot_no='$rotNo' AND bl_no='$blNo'";
			$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
			$cnt = "";
			for($i=0;count($rslt_chkExist)>$i;$i++)
			{
				$cnt = $rslt_chkExist[$i]['rtnValue'];
			}
			
			if($cont_size == 20)
				$truck_qty = 2;
			else
				$truck_qty = 3;

			$partBLQuery = "SELECT COUNT(*) AS rtnValue FROM igm_sup_detail_container 
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
			WHERE cont_number='$contNo' AND Import_Rotation_No='$rotNo' AND cont_status='FCL/PART'";
			$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
			$partbl = 0;
			for($i=0;$i<count($rslt_partBL);$i++){
				$partbl = $rslt_partBL[$i]['rtnValue'];
			}

			if($partbl == 0){
				$partBLQuery = "SELECT COUNT(*) AS rtnValue
				FROM igm_detail_container
				INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo' AND cont_status='FCL/PART'";
				$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
				$partbl = 0;
				for($i=0;$i<count($rslt_partBL);$i++){
					$partbl = $rslt_partBL[$i]['rtnValue'];
				}
			}

			$partblsts = 0;
			
			if($partbl>0){
				$partblsts = 1;
			}

			if($cntArray>1)
			{
				if($cnt==0)
				{			
					$lclDlv_query = "INSERT INTO lcl_dlv_assignment (igm_sup_dtl_id,rot_no,bl_no,cp_no,cnf_lic_no,no_of_truck,deliveryDt,igm_type,verify_num,entry_by,entry_at,entry_ip,is_part_bl) VALUES('$igm_id','$rotNo','$blNo','$cp_no','$org_license','$truck_qty',date(NOW()),'$igm_type','$verify_no','$login_id',NOW(),'$ipaddr','$partblsts')";
					
					if($this->bm->dataInsertDB1($lclDlv_query))
						$msg = "<font color='green'><b>Truck number entered succesfully.</b></font>";
				}
				else
				{
					$sql_updateQtyTruck = "UPDATE lcl_dlv_assignment
					SET no_of_truck='$truck_qty',is_part_bl='$partblsts',entry_by='$login_id'
					WHERE rot_no='$rotNo' AND bl_no='$blNo'";
					
					if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
						$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";
				}
			}

			$sql_vrfyOtherDataId = "SELECT id FROM lcl_dlv_assignment WHERE rot_no='$rotNo' AND bl_no='$blNo'";
			$data_vrfyOtherDataId = $this->bm->dataSelectDB1($sql_vrfyOtherDataId);
			$vrfyOtherDataId = "";
			for($i=0;count($data_vrfyOtherDataId)>$i;$i++)
			{
				$vrfyOtherDataId = $data_vrfyOtherDataId[$i]['id'];
			}

			if(is_null($gate))
			{
				$updateTruck = "UPDATE do_truck_details_entry SET verify_other_data_id = '$vrfyOtherDataId' , import_rotation = '$rotNo', cont_no = '$contNo' WHERE id = '$id'";
			}
			else
			{
				$updateTruck = "UPDATE do_truck_details_entry SET verify_other_data_id = '$vrfyOtherDataId' , import_rotation = '$rotNo', cont_no = '$contNo' , gate_no = '$gate' WHERE id = '$id'";
			}
			
			$this->bm->dataUpdateDB1($updateTruck);
			// reg & bl entry -- ends

			$orgTypeId = $this->session->userdata('org_Type_id');

			$jsId = "";
			$jsName = "";
			$sql_jsid = "SELECT id,agent_name FROM vcms_vehicle_agent WHERE card_number = '$jsPass'";
			$data_jsid = $this->bm->dataSelectDB1($sql_jsid);

			for($i=0;$i<count($data_jsid);$i++)
			{
				$jsId = $data_jsid[$i]['id'];
				$jsName =$data_jsid[$i]['agent_name'];
			}

			$truckQuery = "SELECT verify_info_fcl_id, verify_other_data_id FROM do_truck_details_entry WHERE id = '$id'"; 
			$truckData = $this->bm->dataSelectDb1($truckQuery);

			$vrfyInfoFclId = "";
			$vrfyOtherDataId = "";
			
			if(count($truckData)>0){
				$vrfyInfoFclId = $truckData[0]['verify_info_fcl_id'];
				$vrfyOtherDataId = $truckData[0]['verify_other_data_id'];
			}

			// if($vrfyInfoFclId == "")					// intakhab
			if($vrfyInfoFclId == "" or $vrfyInfoFclId == null or $vrfyInfoFclId == 0)		// intakhab
			{
				// echo "LCL";
				// return;
				$sql_chkJS = "SELECT COUNT(*) AS rtnValue
				FROM lcl_dlv_assignment
				WHERE jetty_sirkar_id='$jsId' AND id='$vrfyOtherDataId'";

				$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
				$chkJS = $rslt_chkJS[0]['rtnValue'];

				for($i=0;count($rslt_chkJS)>$i;$i++)
				{
					$chkJS = $rslt_chkJS[$i]['rtnValue'];
				}

				if($chkJS == 0)
				{
					$prevJS = "";
					$sql_prevJS = "SELECT jetty_sirkar_id
					FROM lcl_dlv_assignment
					WHERE id='$vrfyOtherDataId'";
					$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
					
					$sql_updateJS = "UPDATE lcl_dlv_assignment
					SET jetty_sirkar_id='$jsId'
					WHERE id='$vrfyOtherDataId'";
					// echo $sql_updateJS;return;
					// return;
					$this->bm->dataUpdateDB1($sql_updateJS);
				}
			}
			else
			{
				// echo "FCL";
				// return;
				$sql_chkJS = "SELECT COUNT(*) AS rtnValue
				FROM verify_info_fcl
				WHERE jetty_sirkar_id='$jsId' AND id='$vrfyInfoFclId'";
				$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
				$chkJS = "";
				for($i=0;count($rslt_chkJS)>$i;$i++)
				{
					$chkJS = $rslt_chkJS[$i]['rtnValue'];
				}
				
				if($chkJS == 0)
				{
					$prevJS = "";
					// get previous JS	- check if previous exists
					$sql_prevJS = "SELECT jetty_sirkar_id
					FROM verify_info_fcl
					WHERE id='$vrfyInfoFclId'";
					$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					
					for($i=0;$i<count($rslt_prevJS);$i++){
						$prevJS = $rslt_prevJS[$i]['jetty_sirkar_id'];
					}
					
					// Insert into log
					if($prevJS!="" or $prevJS!=null)
					{
						$sql_jsLog = "INSERT INTO vcms_jetty_sirkar_log(verify_info_fcl_id,prev_jetty_sirkar_id,replace_by,replace_dt)
						VALUES('$vrfyInfoFclId','$prevJS','$user',NOW())";
						$this->bm->dataInsertDB1($sql_jsLog);
					}
					
					// Update JS
					$sql_updateJS = "UPDATE verify_info_fcl
					SET jetty_sirkar_id='$jsId'
					WHERE id='$vrfyInfoFclId'";
					$this->bm->dataUpdateDB1($sql_updateJS);
				}
			}
			
			$loadQuery = "";
			
			if($orgTypeId == 2)
			{
				$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , actual_delv_pack = '$loadQty', actual_delv_unit = '$packUnit', cnf_chk_st = '1' , traffic_chk_st = '0', yard_security_chk_st = '0', cnf_chk_time = NOW() , cnf_chk_by = '$user' , load_by = '$user' , load_time = NOW() WHERE id='$id'";
			}
			else if($orgTypeId == 62)
			{
				$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , actual_delv_pack = '$loadQty', actual_delv_unit = '$packUnit', cnf_chk_st = '0' , traffic_chk_st = '1', yard_security_chk_st = '0', traffic_chk_time = NOW() , traffic_chk_by = '$user' , load_by = '$user' , load_time = NOW() WHERE id='$id'";
			}
			else if($orgTypeId == 75)
			{
				$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , actual_delv_pack = '$loadQty', actual_delv_unit = '$packUnit', cnf_chk_st = '0' , traffic_chk_st = '1', yard_security_chk_st = '0', traffic_chk_time = NOW() , traffic_chk_by = '$user' , load_by = '$user' , load_time = NOW() WHERE id='$id'";
			}
			else if($orgTypeId == 67)
			{
				$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , actual_delv_pack = '$loadQty', actual_delv_unit = '$packUnit', cnf_chk_st = '0' , traffic_chk_st = '0', yard_security_chk_st = '1', yard_security_chk_time = NOW() , yard_security_chk_by = '$user' , load_by = '$user' , load_time = NOW() WHERE id='$id'";
			}
			else if($orgTypeId == 59)
			{
				$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , actual_delv_pack = '$loadQty', actual_delv_unit = '$packUnit', cnf_chk_st = '0' , traffic_chk_st = '1', yard_security_chk_st = '0', traffic_chk_time = NOW() , traffic_chk_by = '$user' , load_by = '$user' , load_time = NOW() WHERE id='$id'";
			}

			$rslt_update=$this->bm->dataUpdateDB1($loadQuery);

			if($rslt_update>0)
			{
				$msg="<font color='green'><b>Truck Loaded Successfully!!!</b></font>";
				$logQuery = "INSERT INTO do_loading_log(tr_visit_id,pck_quantiy,pck_unit,update_time,update_by) VALUES('$id','$loadQty','$packUnit',NOW(),'$login_id')";
				$this->bm->dataInsertDB1($logQuery);
			}
			else
			{
				$msg="<font color='red'><b>Truck Can't be Loaded!!!</b></font>";
			}

			$data['cont_status'] = "";
			$data['visitId'] = $id;
			$data['title']= "Loading Process for LCL";
			$data['msg'] = $msg;
			$data['flag'] = 1;
			$data['disputeFlag'] = "0";

			$this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('loadingFormLcl',$data);
			$this->load->view('jsAssets');

		}
	}

	function additionalTruck()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$cont = $this->input->post('cont');
			$loadQty = $this->input->post('load_qty');
			$pack_unit = $this->input->post('pack_unit');
			$visit_id = $this->input->post('visitId');
			$id = $this->input->post('id');
			$msg = "";
			
			$btn = $this->input->post("btn");

			if($btn == 'add')
			{	
				$sql_addCont = "INSERT INTO do_truck_details_additional_cont_lcl(truck_visit_id,cont_no,pack_num,pack_unit) VALUES('$visit_id','$cont','$loadQty','$pack_unit')";
				$rslt_update = $this->bm->dataInsertDB1($sql_addCont);

				$sql_updateTruck = "UPDATE do_truck_details_entry SET add_truck_st = 1 WHERE id = '$visit_id'";
				$this->bm->dataInsertDB1($sql_updateTruck);
			}
			else
			{
				$totalAddedCont = $this->input->post('totalAddedCont');
				
				$sql_dltCont = "DELETE FROM do_truck_details_additional_cont_lcl WHERE id='$id'";
				$rslt_delete = $this->bm->dataDeleteDB1($sql_dltCont);

				if($totalAddedCont == 1){
					$sql_reset = "UPDATE do_truck_details_entry SET add_truck_st = 0 WHERE id = '$visit_id'";
					$this->bm->dataInsertDB1($sql_reset);
				}
			}

			$data['cont_status'] = "";
			$data['visitId'] = $visit_id;
			$data['title']= "Loading Process for LCL";
			$data['msg'] = $msg;
			$data['flag'] = 1;
			$data['disputeFlag'] = "0";

			$this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('loadingFormLcl',$data);
			$this->load->view('jsAssets');

		}
	}

	function additionalBL()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$bl = $this->input->post('bl');
			$loadQty = $this->input->post('load_qty');
			$pack_unit = $this->input->post('pack_unit');
			$visit_id = $this->input->post('visitId');
			$id = $this->input->post('id');
			$msg = "";
			
			$btn = $this->input->post("btn");

			if($btn == 'add')
			{	
				$sql_addBl = "INSERT INTO do_truck_details_additional_bl_lcl(truck_visit_id,bl_no,pack_num,pack_unit) VALUES('$visit_id','$bl','$loadQty','$pack_unit')";
				$rslt_update = $this->bm->dataInsertDB1($sql_addBl);

				$sql_updateBl = "UPDATE do_truck_details_entry SET add_bl_st = 1 WHERE id = '$visit_id'";
				$this->bm->dataUpdateDB1($sql_updateBl);

				//set Load Status for Part BL
				$login_id = $this->session->userdata('login_id');

				$orgTypeId = $this->session->userdata('org_Type_id');
				
				$loadQuery = "";

				if($orgTypeId == 2)
				{
					$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , cnf_chk_st = '1' , traffic_chk_st = '0', yard_security_chk_st = '0', cnf_chk_time = NOW() , cnf_chk_by = '$login_id' , load_by = '$login_id' , load_time = NOW() WHERE id='$visit_id'";
				}
				else if($orgTypeId == 62)
				{
					$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , cnf_chk_st = '0' , traffic_chk_st = '1', yard_security_chk_st = '0', traffic_chk_time = NOW() , traffic_chk_by = '$login_id' , load_by = '$login_id' , load_time = NOW() WHERE id='$visit_id'";
				}
				else if($orgTypeId == 75)
				{
					$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , cnf_chk_st = '0' , traffic_chk_st = '1', yard_security_chk_st = '0', traffic_chk_time = NOW() , traffic_chk_by = '$login_id' , load_by = '$login_id' , load_time = NOW() WHERE id='$visit_id'";
				}
				else if($orgTypeId == 67)
				{
					$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , cnf_chk_st = '0' , traffic_chk_st = '0', yard_security_chk_st = '1', yard_security_chk_time = NOW() , yard_security_chk_by = '$login_id' , load_by = '$login_id' , load_time = NOW() WHERE id='$visit_id'";
				}else if($orgTypeId == 59)
				{
					$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , cnf_chk_st = '0' , traffic_chk_st = '1', yard_security_chk_st = '0', traffic_chk_time = NOW() , traffic_chk_by = '$login_id' , load_by = '$login_id' , load_time = NOW() WHERE id='$visit_id'";
				}

				$rslt_update=$this->bm->dataUpdateDB1($loadQuery);

				if($rslt_update>0)
				{
					$msg="<font color='green'><b>Truck Loaded Successfully!!!</b></font>";
					$logQuery = "INSERT INTO do_loading_log(tr_visit_id,pck_quantiy,pck_unit,update_time,update_by) VALUES('$visit_id','$loadQty','$pack_unit',NOW(),'$login_id')";
					$this->bm->dataInsertDB1($logQuery);
				}
				else
				{
					$msg="<font color='red'><b>Truck Can't be Loaded!!!</b></font>";
				}

			}
			else
			{
				$totalAddedBl = $this->input->post('totalAddedBl');
				
				$sql_dltBl = "DELETE FROM do_truck_details_additional_bl_lcl WHERE id='$id'";
				$rslt_delete = $this->bm->dataDeleteDB1($sql_dltBl);

				if($totalAddedBl == 1){
					$sql_reset = "UPDATE do_truck_details_entry SET add_bl_st = 0 WHERE id = '$visit_id'";
					$this->bm->dataUpdateDB1($sql_reset);
				}
			}

			$data['cont_status'] = "";
			$data['visitId'] = $visit_id;
			$data['title']= "Loading Process for LCL";
			$data['msg'] = $msg;
			$data['flag'] = 1;
			$data['disputeFlag'] = "0";

			$this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('loadingFormLcl',$data);
			$this->load->view('jsAssets');

		}
	}

	function chkBlockedContainer($cont = null)
	{
		$query = "SELECT custom_block_st 
		FROM ctmsmis.tmp_oracle_assignment 
		WHERE cont_no = '$cont' AND custom_block_st = 'Blocked' 
		ORDER BY block_update_dt DESC 
		LIMIT 1";
		$sts = $this->bm->dataSelect($query);
		return $sts;
	}
	
	function chkBlockedContainer_temp($cont = null,$visitId = null)
	{		
		$sql_rotBl = "SELECT rot_no,bl_no
		FROM lcl_dlv_assignment
		INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id
		WHERE do_truck_details_entry.id='$visitId'";
		$rslt_rotBl = $this->bm->dataSelectDB1($sql_rotBl);
		
		$rotNo = $rslt_rotBl[0]['rot_no'];
		$blNo = $rslt_rotBl[0]['bl_no'];
		
		$sql_releaseChk = "SELECT release_flag
		FROM nbr_block_unblock_data
		INNER JOIN nbr_block_unblock_cont_no ON nbr_block_unblock_cont_no.block_unblock_id=nbr_block_unblock_data.id
		WHERE nbr_block_unblock_cont_no.cont_no='$cont' AND rotation_no='$rotNo' AND bl_ref='$blNo'";
		$sts = $this->bm->dataSelectDB1($sql_releaseChk);
		return $sts;
	}

	function checkoutbyOnline()
	{
		$payAmt = $this->input->post('payAmt');
		$visitId = $this->input->post('trucVisitId');
		$assignmentType = $this->input->post('assignmentType');
		$cont_status = $this->input->post('cont_status');
		$blNo = $this->input->post('blNo');
		$contNo = $this->input->post('contNo');
		$rotNo = $this->input->post('rotNo');
		$contact = $this->input->post('contact');
		$login_id = $this->session->userdata('login_id');
		$flag='0';
		$find_visitStr = "SELECT count(*) as rtnValue FROM vcms_online_pay_copy where visit_id='$visitId'";
		$checkVisit= $this->bm->dataReturnDB1($find_visitStr);

		if($checkVisit>0)
		{
			$sql_Requ = "SELECT MAX(vcms_online_ReqID_copy.max_reqID) AS rtnValue FROM vcms_online_ReqID_copy";
			$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			
			$ref=$requst_id."_".$flag;
			
			$query_update = "UPDATE vcms_online_pay_copy SET RefTranNo='$ref', requ_id='$requst_id' WHERE visit_id = '$visitId'";
			$up_st = $this->bm->dataUpdateDB1($query_update); 
			if($up_st>0)
			{
				$newReq_id=$requst_id+1;
				//$newReq_id="0".$newReq_id;		// added now
				$query_update = "UPDATE vcms_online_ReqID_copy SET max_reqID='$newReq_id'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 
			}
		}
		else
		{
			/* $sql_maxRequ = "SELECT MAX(vcms_online_pay.requ_id)+1 AS rtnValue FROM vcms_online_pay";
			$requst_id = $this->bm->dataReturnDB1($sql_maxRequ); */
			$sql_Requ = "SELECT MAX(vcms_online_ReqID_copy.max_reqID) AS rtnValue FROM vcms_online_ReqID_copy";
			$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			$ref=$requst_id."_".$flag;
			$query_txEntry = "INSERT INTO vcms_online_pay_copy (visit_id, RefTranNo, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st ) VALUES ('$visitId', '$ref', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1)";
			$st=$this->bm->dataInsertDB1($query_txEntry);
			if($st>0)
			{
				$newReq_id=$requst_id+1;
				//$newReq_id="0".$newReq_id;		// added now
				$query_update = "UPDATE vcms_online_ReqID_copy SET max_reqID='$newReq_id'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 
			}
		}
		
		//return;
		$data['requst_id'] = $requst_id;
		$data['ref'] = $ref;
		$data['login_id'] = $login_id;
		$data['contact'] = $contact;
		$data['trucVisitId'] = $visitId;
		$data['flag'] = $flag;  //Single Pay
		$data['name'] = $this->session->userdata('User_Name');
		$cus_name= $this->session->userdata('User_Name');
		$data['payAmt'] = $payAmt;

		$this->onlinePay($contNo,$blNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
		
	}

	// function checkoutbyOnline()
	// {
	// 	$payAmt = $this->input->post('payAmt');
	// 	$visitId = $this->input->post('trucVisitId');
	// 	$assignmentType = $this->input->post('assignmentType');
	// 	$cont_status = $this->input->post('cont_status');
	// 	$blNo = $this->input->post('blNo');
	// 	$contNo = $this->input->post('contNo');
	// 	$rotNo = $this->input->post('rotNo');
	// 	$contact = $this->input->post('contact');
	// 	$login_id = $this->session->userdata('login_id');
	// 	$flag='0';
	// 	$find_visitStr = "SELECT count(*) as rtnValue FROM vcms_online_pay where visit_id='$visitId'";
	// 	$checkVisit= $this->bm->dataReturnDB1($find_visitStr);

	// 	if($checkVisit>0)
	// 	{
	// 		$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
	// 		$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			
	// 		$ref=$requst_id."_".$flag;
			
	// 		$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', requ_id='$requst_id' WHERE visit_id = '$visitId'";
	// 		$up_st = $this->bm->dataUpdateDB1($query_update); 
	// 		if($up_st>0)
	// 		{
	// 			$newReq_id=$requst_id+1;
	// 			$newReq_id="0".$newReq_id;		// added now
	// 			$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
	// 			$update_st = $this->bm->dataUpdateDB1($query_update); 
	// 		}
	// 	}
	// 	else
	// 	{
	// 		/* $sql_maxRequ = "SELECT MAX(vcms_online_pay.requ_id)+1 AS rtnValue FROM vcms_online_pay";
	// 		$requst_id = $this->bm->dataReturnDB1($sql_maxRequ); */
	// 		$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
	// 		$requst_id = $this->bm->dataReturnDB1($sql_Requ);
	// 		$ref=$requst_id."_".$flag;
	// 		$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st ) VALUES ('$visitId', '$ref', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1)";
	// 		$st=$this->bm->dataInsertDB1($query_txEntry);
	// 		if($st>0)
	// 		{
	// 			$newReq_id=$requst_id+1;
	// 			$newReq_id="0".$newReq_id;		// added now
	// 			$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
	// 			$update_st = $this->bm->dataUpdateDB1($query_update); 
	// 		}
	// 	}
		
	// 	//return;
	// 	$data['requst_id'] = $requst_id;
	// 	$data['ref'] = $ref;
	// 	$data['login_id'] = $login_id;
	// 	$data['contact'] = $contact;
	// 	$data['trucVisitId'] = $visitId;
	// 	$data['flag'] = $flag;  //Single Pay
	// 	$data['name'] = $this->session->userdata('User_Name');
	// 	$cus_name= $this->session->userdata('User_Name');
	// 	$data['payAmt'] = $payAmt;

	// 	$this->onlinePay($contNo,$blNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
		
	// }
	
	
	function checkoutAllbyOnline($cont,$blNo,$rot,$assignmentType,$payAmt,$contact)
	{	
		$login_id = $this->session->userdata('login_id');
		$data['cont'] = $cont;
		$data['rot'] = $rot;
		$data['login_id'] = $login_id;
		$data['assignmentType'] = $assignmentType;
		//echo $assignmentType;
		//return;
		$cont_status = $this->input->post('cont_status');
		//$data['contact'] = $contact;
		$flag='1';
		
		$tot_visit= $payAmt/57.5;
		
		//echo "11111";
		
		$find_visitStr = "SELECT COUNT(*) AS rtnValue FROM vcms_online_pay_copy WHERE vcms_online_pay_copy.container='$cont' AND vcms_online_pay_copy.rotation='$rot'";
		$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
		//return;
		if($checkVisit>0)
		{		
			
			$sql_Requ = "SELECT MAX(vcms_online_ReqID_copy.max_reqID) AS rtnValue FROM vcms_online_ReqID_copy";
			$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			
			$ref=$requst_id."_".$flag;
			
			$query_update = "UPDATE vcms_online_pay_copy SET RefTranNo='$ref', requ_id='$requst_id' WHERE container='$cont' AND rotation='$rot' AND trans_id IS NULL";
			$up_st = $this->bm->dataUpdateDB1($query_update); 
			if($up_st>0)
			{
				$newReq_id=$requst_id+1;
				//$newReq_id="0".$newReq_id;		// added now
				$query_update = "UPDATE vcms_online_ReqID_copy SET max_reqID='$newReq_id'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 
			}
			
			// If later Visit Id created and used allpay btn for same rotaion and container, whose previous visit ID exist.
			
					//$sql_dtl_info = "SELECT * FROM do_truck_details_entry  WHERE cont_no='$cont' AND import_rotation='$rot' AND paid_status='0'";
					$sql_dtl_info = "SELECT * FROM do_truck_details_entry WHERE cont_no='$cont' AND import_rotation='$rot' AND paid_status='0' 
								AND id NOT IN ( SELECT vcms_online_pay_copy.visit_id FROM vcms_online_pay_copy WHERE vcms_online_pay_copy.container='$cont' AND vcms_online_pay_copy.rotation='$rot')";
					$dtl_result = $this->bm->dataSelectDB1($sql_dtl_info); 
					/* echo $tot_visit.'-'.count($dtl_result);
					return; */
					if($tot_visit==count($dtl_result))
					{	
					
						for($i=0; $i<count($dtl_result); $i++)
						{
							$visitId = $dtl_result[$i]['id'];	
							
							$query_txEntry = "INSERT INTO vcms_online_pay_copy (visit_id, RefTranNo, rotation, container, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st ) VALUES ('$visitId', '$ref', '$rot','$cont','$requst_id', '50', '7.5', '$assignmentType', '$login_id', 1, 1 )";
							$st=$this->bm->dataInsertDB1($query_txEntry);
						}
						
						
						if($st>0)
						{
							$newReq_id=$requst_id+1;
							//$newReq_id="0".$newReq_id;		// added now
							$query_update = "UPDATE vcms_online_ReqID_copy SET max_reqID='$newReq_id'";
							$update_st = $this->bm->dataUpdateDB1($query_update); 
						}
					}
					else
					{
						
						/* $data['requst_id'] = $requst_id;
						$data['login_id'] = $login_id;
						$data['contact'] = $contact;
						//$data['trucVisitId'] = $visitId;
						//$data['name'] = $this->session->userdata('User_Name');
						$data['payAmt'] = $payAmt;
						$data['ref'] = $ref;
						//$data['payAmt'] = $this->input->post('payAmt');

						$data['flag'] = 1;  //All Pay
						$data['name'] = $this->session->userdata('User_Name');
						$this->load->view('onlinePay', $data);
						$msg="Something Wrong. Please, Pay Seperately."; */

					} 

		}
		else
		{
			$sql_Requ = "SELECT MAX(vcms_online_ReqID_copy.max_reqID) AS rtnValue FROM vcms_online_ReqID_copy";
			 $requst_id = $this->bm->dataReturnDB1($sql_Requ);
			
			$ref=$requst_id."_".$flag;
			
			$sql_dtl_info = "SELECT * FROM do_truck_details_entry  WHERE cont_no='$cont' AND import_rotation='$rot' AND paid_status='0'";
			$dtl_result = $this->bm->dataSelectDB1($sql_dtl_info); 
			
			if($tot_visit==count($dtl_result))
			{	
				for($i=0; $i<count($dtl_result); $i++)
				{
					$visitId = $dtl_result[$i]['id'];
						
					$find_visitStr = "SELECT COUNT(*) AS rtnValue FROM vcms_online_pay_copy WHERE vcms_online_pay_copy.visit_id='$visitId'";
					$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
					//return;
					if($checkVisit>0)
					{
						$query_update = "UPDATE vcms_online_pay_copy SET  rotation='$rot', container='$cont', RefTranNo='$ref', requ_id='$requst_id', tr_amt='50', challan_amt='7.5', assign_type='$assignmentType', cnf_login_id='$login_id', allPay_st='1', chk_st='1'
						WHERE visit_id='$visitId'";
						$up_st = $this->bm->dataUpdateDB1($query_update); 						
					}
					else
					{
						$query_txEntry = "INSERT INTO vcms_online_pay_copy (visit_id, rotation, container, RefTranNo, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st ) VALUES ('$visitId', '$rot','$cont', '$ref', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 1, 1 )";
						$st=$this->bm->dataInsertDB1($query_txEntry);
					}
				}
				
				
				if($st>0)
				{
					$newReq_id=$requst_id+1;
					//$newReq_id="0".$newReq_id;		// added now
					$query_update = "UPDATE vcms_online_ReqID_copy SET max_reqID='$newReq_id'";
					$update_st = $this->bm->dataUpdateDB1($query_update); 
				}
			}
			else
			{
				$msg="Something Wrong. Please, Pay Seperately.";
				echo "<font color=red size=4><b>Something Wrong. Please, Pay Seperately or approve your emergency truck. Then, try again.</b></font>";
				return;
			}
		}
		$data['requst_id'] = $requst_id;
		$data['login_id'] = $login_id;
		$data['contact'] = $contact;
		//$data['trucVisitId'] = $visitId;
		//$data['name'] = $this->session->userdata('User_Name');
		$data['payAmt'] = $payAmt;
		$data['ref'] = $ref;
		//$data['payAmt'] = $this->input->post('payAmt');
		
		$data['flag'] = 1;  //All Pay
		$cus_name= $this->session->userdata('User_Name');
		$data['name'] = $this->session->userdata('User_Name');
		//$this->load->view('onlinePay', $data);
		//$this->onlinePay($cont, $rot, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
		$this->onlinePay($cont, $blNo, $rot, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
		
	}

	// function checkoutAllbyOnline($cont,$blNo,$rot,$assignmentType,$payAmt,$contact)
	// {	
	// 	$login_id = $this->session->userdata('login_id');
	// 	$data['cont'] = $cont;
	// 	$data['rot'] = $rot;
	// 	$data['login_id'] = $login_id;
	// 	$data['assignmentType'] = $assignmentType;
	// 	//echo $assignmentType;
	// 	//return;
	// 	$cont_status = $this->input->post('cont_status');
	// 	//$data['contact'] = $contact;
	// 	$flag='1';
		
	// 	$tot_visit= $payAmt/57.5;
		
	// 	//echo "11111";
		
	// 	$find_visitStr = "SELECT COUNT(*) AS rtnValue FROM vcms_online_pay WHERE vcms_online_pay.container='$cont' AND vcms_online_pay.rotation='$rot'";
	// 	$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
	// 	//return;
	// 	if($checkVisit>0)
	// 	{		
			
	// 		$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
	// 		$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			
	// 		$ref=$requst_id."_".$flag;
			
	// 		$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', requ_id='$requst_id' WHERE container='$cont' AND rotation='$rot' AND trans_id IS NULL";
	// 		$up_st = $this->bm->dataUpdateDB1($query_update); 
	// 		if($up_st>0)
	// 		{
	// 			$newReq_id=$requst_id+1;
	// 			$newReq_id="0".$newReq_id;		// added now
	// 			$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
	// 			$update_st = $this->bm->dataUpdateDB1($query_update); 
	// 		}
			
	// 		// If later Visit Id created and used allpay btn for same rotaion and container, whose previous visit ID exist.
			
	// 				//$sql_dtl_info = "SELECT * FROM do_truck_details_entry  WHERE cont_no='$cont' AND import_rotation='$rot' AND paid_status='0'";
	// 				$sql_dtl_info = "SELECT * FROM do_truck_details_entry WHERE cont_no='$cont' AND import_rotation='$rot' AND paid_status='0' 
	// 							AND id NOT IN ( SELECT vcms_online_pay.visit_id FROM vcms_online_pay WHERE vcms_online_pay.container='$cont' AND vcms_online_pay.rotation='$rot')";
	// 				$dtl_result = $this->bm->dataSelectDB1($sql_dtl_info); 
	// 				/* echo $tot_visit.'-'.count($dtl_result);
	// 				return; */
	// 				if($tot_visit==count($dtl_result))
	// 				{	
					
	// 					for($i=0; $i<count($dtl_result); $i++)
	// 					{
	// 						$visitId = $dtl_result[$i]['id'];	
							
	// 						$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, rotation, container, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st ) VALUES ('$visitId', '$ref', '$rot','$cont','$requst_id', '50', '7.5', '$assignmentType', '$login_id', 1, 1 )";
	// 						$st=$this->bm->dataInsertDB1($query_txEntry);
	// 					}
						
						
	// 					if($st>0)
	// 					{
	// 						$newReq_id=$requst_id+1;
	// 						$newReq_id="0".$newReq_id;		// added now
	// 						$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
	// 						$update_st = $this->bm->dataUpdateDB1($query_update); 
	// 					}
	// 				}
	// 				else
	// 				{
						
	// 					/* $data['requst_id'] = $requst_id;
	// 					$data['login_id'] = $login_id;
	// 					$data['contact'] = $contact;
	// 					//$data['trucVisitId'] = $visitId;
	// 					//$data['name'] = $this->session->userdata('User_Name');
	// 					$data['payAmt'] = $payAmt;
	// 					$data['ref'] = $ref;
	// 					//$data['payAmt'] = $this->input->post('payAmt');

	// 					$data['flag'] = 1;  //All Pay
	// 					$data['name'] = $this->session->userdata('User_Name');
	// 					$this->load->view('onlinePay', $data);
	// 					$msg="Something Wrong. Please, Pay Seperately."; */

	// 				} 

	// 	}
	// 	else
	// 	{
	// 		$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
	// 		 $requst_id = $this->bm->dataReturnDB1($sql_Requ);
			
	// 		$ref=$requst_id."_".$flag;
			
	// 		$sql_dtl_info = "SELECT * FROM do_truck_details_entry  WHERE cont_no='$cont' AND import_rotation='$rot' AND paid_status='0'";
	// 		$dtl_result = $this->bm->dataSelectDB1($sql_dtl_info); 
			
	// 		if($tot_visit==count($dtl_result))
	// 		{	
	// 			for($i=0; $i<count($dtl_result); $i++)
	// 			{
	// 				$visitId = $dtl_result[$i]['id'];
						
	// 				$find_visitStr = "SELECT COUNT(*) AS rtnValue FROM vcms_online_pay WHERE vcms_online_pay.visit_id='$visitId'";
	// 				$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
	// 				//return;
	// 				if($checkVisit>0)
	// 				{
	// 					$query_update = "UPDATE vcms_online_pay SET  rotation='$rot', container='$cont', RefTranNo='$ref', requ_id='$requst_id', tr_amt='50', challan_amt='7.5', assign_type='$assignmentType', cnf_login_id='$login_id', allPay_st='1', chk_st='1'
	// 					WHERE visit_id='$visitId'";
	// 					$up_st = $this->bm->dataUpdateDB1($query_update); 						
	// 				}
	// 				else
	// 				{
	// 					$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, rotation, container, RefTranNo, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st ) VALUES ('$visitId', '$rot','$cont', '$ref', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 1, 1 )";
	// 					$st=$this->bm->dataInsertDB1($query_txEntry);
	// 				}
	// 			}
				
				
	// 			if($st>0)
	// 			{
	// 				$newReq_id=$requst_id+1;
	// 				$newReq_id="0".$newReq_id;		// added now
	// 				$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
	// 				$update_st = $this->bm->dataUpdateDB1($query_update); 
	// 			}
	// 		}
	// 		else
	// 		{
	// 			$msg="Something Wrong. Please, Pay Seperately.";
	// 			echo "<font color=red size=4><b>Something Wrong. Please, Pay Seperately or approve your emergency truck. Then, try again.</b></font>";
	// 			return;
	// 		}
	// 	}
	// 	$data['requst_id'] = $requst_id;
	// 	$data['login_id'] = $login_id;
	// 	$data['contact'] = $contact;
	// 	//$data['trucVisitId'] = $visitId;
	// 	//$data['name'] = $this->session->userdata('User_Name');
	// 	$data['payAmt'] = $payAmt;
	// 	$data['ref'] = $ref;
	// 	//$data['payAmt'] = $this->input->post('payAmt');
		
	// 	$data['flag'] = 1;  //All Pay
	// 	$cus_name= $this->session->userdata('User_Name');
	// 	$data['name'] = $this->session->userdata('User_Name');
	// 	//$this->load->view('onlinePay', $data);
	// 	//$this->onlinePay($cont, $rot, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
	// 	$this->onlinePay($cont, $blNo, $rot, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
		
	// }

	function onlinePay($cont=null, $blNo=null, $rot=null, $login_id=null, $contact=null, $ref=null, $requst_id=null, $payAmt=null, $name=null, $assignmentType=null, $cont_status=null)
	{
		$tranTime=$payAmt/57.5;
		$dt=date('Y-m-d');
		//$ref=$requst_id."_".$flag;
		$post_data = '{
				"AccessUser": {
				"userName" : "bdtaxUser2014",
				"password" : "duUserPayment2014"
				},
				"strUserId" : "bdtaxUser2014",
				"strPassKey": "duUserPayment2014",
				"strRequestId": "'.$requst_id.'",
				"strAmount": "'.$payAmt.'",
				"strTranDate": "'.$dt.'",
				"strAccounts": "1110000018754-0002634271324"
				}';
		//echo $post_data;
		//return; 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://spg.sblesheba.com:6314/api/SpgService/GetSessionKey");
		//added
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
		curl_setopt($ch, CURLOPT_POST, 1 );

		//added

		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

		//$result = curl_exec($ch);
		//curl_close($ch);
		/*  echo $result; 
		var_dump($result);
		return;  */


		curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
		$content = curl_exec($ch);

		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		//echo $code;
		//echo $content;
		//$code=0;;

		//echo "<br>";
		//print_r( $content); 
		//return; 
		$sessionData="";

		if($code == 200)
		{
			curl_close( $ch);
			$sessionData = $content;
		} 
		else 
		{
			curl_close( $ch);
			$msg="<font size=4 color=red><b>Network Problem. Please Try again later.</b></font>";
			//echo $rot.'-'.$cont.'-'.$cont_status.'-'.$assignmentType.'-'.$msg;
		//	return;
			$this->cnfTruckEntryLCL($rot,$blNo,$cont_status,$assignmentType,$msg);
			return;
		/* 	echo "FAILED TO CONNECT  API";
			exit; */ 
		}
		/* echo $sessionData ;
		return; */
		$data = json_decode($sessionData, true );
		//echo $data;
		//echo "<br>";
		$dataPart = explode('"',$data);
		//print_r( $dataPart) ;

		$skey=$dataPart[3];
		//echo $skey;
		//return;	
		$cur_time=date('Y-m-d H:i:s', time());

		$tranAmt=$tranTime*50;
		$tranChallan=$tranTime*7.5;

		$post_data2='{
				"Authentication": {
				"ApiAccessUserId": "bdtaxUser2014",
				"ApiAccessPassKey":"'.$skey.'"
				},
				"ReferenceInfo": {
				"RequestId": "'.$requst_id.'",
				"RefTranNo": "'.$ref.'",
				"RefTranDateTime": "'.$cur_time.'",
				"ReturnUrl": "http://cpatos.gov.bd/pcsTest/index.php/LCL/onlinePaymentSuccess",
				"ReturnMethod": "POST",
				"TranAmount": "'.$payAmt.'",
				"ContactName": "'.$name.'",
				"ContactNo": "'.$contact.'",
				"PayerId": "'.$login_id.'",
				"Address": "applicentAddress"
				},
				"CreditInformations": [
				{
				"SLNO": "1",
				"CreditAccount": "1110000018754",
				"CrAmount": "'.$tranChallan.'",
				"Purpose": "CHL",
				"Onbehalf": "Test"
				},
				{
				"SLNO": "2",
				"CreditAccount": "0002634271324",
				"CrAmount": "'.$tranAmt.'",
				"Purpose": "TRN",
				"Onbehalf": "Test"
				}
				]
				}';

		//echo $post_data2;
		//echo '\r\n';
		//return;
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, "https://spg.sblesheba.com:6314/api/SpgService/PaymentByPortal");
		//added
		curl_setopt($handle, CURLOPT_TIMEOUT, 30);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($handle, CURLOPT_POST, 1 );


		//added

		curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
		curl_setopt($handle, CURLOPT_POST, 1);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data2);

		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


		$content2 = curl_exec($handle);

		$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

		//if($code == 200 && !( curl_errno($handle))) {
		if($code == 200)
			{
			curl_close( $handle);
			$getData = $content2;
			} 
		else 
			{
			curl_close( $handle);
			$msg="<font size=4 color=red><b>Network Problem. Please Try again later.</b></font>";
			//echo $rot.'-'.$cont.'-'.$cont_status.'-'.$assignmentType.'-'.$msg;
		//	return;
			$this->cnfTruckEntryLCL($rot,$blNo,$cont_status,$assignmentType,$msg);
			return;
			}
		$session_token = json_decode($getData, true );
		//echo $session_token ;
		//echo "<br/>";
		$token_str = explode('"',$session_token);
		//echo $token_str;
		//echo "<br/>";
		$token=$token_str[7];
		//echo $token;
		//return;

		$direct_api_url= "https://spg.sblesheba.com:6313/SpgLanding/SpgLanding/".$token;
		header('Location:'.$direct_api_url);  
		exit;

	}

	// function onlinePay($cont=null, $blNo=null, $rot=null, $login_id=null, $contact=null, $ref=null, $requst_id=null, $payAmt=null, $name=null, $assignmentType=null, $cont_status=null)
	// {
	// 	$tranTime=$payAmt/57.5;
	// 	$dt=date('Y-m-d');
	// 	//$ref=$requst_id."_".$flag;
	// 	$post_data = '{
	// 			"AccessUser": {
	// 			"userName" : "bdtaxUser2014",
	// 			"password" : "duUserPayment2014"
	// 			},
	// 			"strUserId" : "bdtaxUser2014",
	// 			"strPassKey": "duUserPayment2014",
	// 			"strRequestId": "'.$requst_id.'",
	// 			"strAmount": "'.$payAmt.'",
	// 			"strTranDate": "'.$dt.'",
	// 			"strAccounts": "1110000018754-0002634271324"
	// 			}';
	// 	//echo $post_data;
	// 	//return; 
	// 	$ch = curl_init();
	// 	curl_setopt($ch, CURLOPT_URL, "https://spg.sblesheba.com:6314/api/SpgService/GetSessionKey");
	// 	//added
	// 	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	// 	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
	// 	curl_setopt($ch, CURLOPT_POST, 1 );

	// 	//added

	// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
	// 	curl_setopt($ch, CURLOPT_POST, 1);
	// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	// 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

	// 	//$result = curl_exec($ch);
	// 	//curl_close($ch);
	// 	/*  echo $result; 
	// 	var_dump($result);
	// 	return;  */


	// 	curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
	// 	$content = curl_exec($ch);

	// 	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	// 	//echo $code;
	// 	//echo $content;
	// 	//$code=0;;

	// 	//echo "<br>";
	// 	//print_r( $content); 
	// 	//return; 
	// 	$sessionData="";

	// 	if($code == 200)
	// 	{
	// 		curl_close( $ch);
	// 		$sessionData = $content;
	// 	} 
	// 	else 
	// 	{
	// 		curl_close( $ch);
	// 		$msg="<font size=4 color=red><b>Network Problem. Please Try again later.</b></font>";
	// 		//echo $rot.'-'.$cont.'-'.$cont_status.'-'.$assignmentType.'-'.$msg;
	// 	//	return;
	// 		$this->cnfTruckEntryLCL($rot,$blNo,$cont_status,$assignmentType,$msg);
	// 		return;
	// 	/* 	echo "FAILED TO CONNECT  API";
	// 		exit; */ 
	// 	}
	// 	/* echo $sessionData ;
	// 	return; */
	// 	$data = json_decode($sessionData, true );
	// 	//echo $data;
	// 	//echo "<br>";
	// 	$dataPart = explode('"',$data);
	// 	//print_r( $dataPart) ;

	// 	$skey=$dataPart[3];
	// 	//echo $skey;
	// 	//return;	
	// 	$cur_time=date('Y-m-d H:i:s', time());

	// 	$tranAmt=$tranTime*50;
	// 	$tranChallan=$tranTime*7.5;

	// 	$post_data2='{
	// 			"Authentication": {
	// 			"ApiAccessUserId": "bdtaxUser2014",
	// 			"ApiAccessPassKey":"'.$skey.'"
	// 			},
	// 			"ReferenceInfo": {
	// 			"RequestId": "'.$requst_id.'",
	// 			"RefTranNo": "'.$ref.'",
	// 			"RefTranDateTime": "'.$cur_time.'",
	// 			"ReturnUrl": "http://cpatos.gov.bd/pcsTest/index.php/LCL/onlinePaymentSuccess",
	// 			"ReturnMethod": "POST",
	// 			"TranAmount": "'.$payAmt.'",
	// 			"ContactName": "'.$name.'",
	// 			"ContactNo": "'.$contact.'",
	// 			"PayerId": "'.$login_id.'",
	// 			"Address": "applicentAddress"
	// 			},
	// 			"CreditInformations": [
	// 			{
	// 			"SLNO": "1",
	// 			"CreditAccount": "1110000018754",
	// 			"CrAmount": "'.$tranChallan.'",
	// 			"Purpose": "CHL",
	// 			"Onbehalf": "Test"
	// 			},
	// 			{
	// 			"SLNO": "2",
	// 			"CreditAccount": "0002634271324",
	// 			"CrAmount": "'.$tranAmt.'",
	// 			"Purpose": "TRN",
	// 			"Onbehalf": "Test"
	// 			}
	// 			]
	// 			}';

	// 	//echo $post_data2;
	// 	//echo '\r\n';
	// 	//return;
	// 	$handle = curl_init();
	// 	curl_setopt($handle, CURLOPT_URL, "https://spg.sblesheba.com:6314/api/SpgService/PaymentByPortal");
	// 	//added
	// 	curl_setopt($handle, CURLOPT_TIMEOUT, 30);
	// 	curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
	// 	curl_setopt($handle, CURLOPT_POST, 1 );


	// 	//added

	// 	curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
	// 	curl_setopt($handle, CURLOPT_POST, 1);
	// 	curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data2);

	// 	curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
	// 	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	// 	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


	// 	$content2 = curl_exec($handle);

	// 	$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

	// 	//if($code == 200 && !( curl_errno($handle))) {
	// 	if($code == 200)
	// 		{
	// 		curl_close( $handle);
	// 		$getData = $content2;
	// 		} 
	// 	else 
	// 		{
	// 		curl_close( $handle);
	// 		$msg="<font size=4 color=red><b>Network Problem. Please Try again later.</b></font>";
	// 		//echo $rot.'-'.$cont.'-'.$cont_status.'-'.$assignmentType.'-'.$msg;
	// 	//	return;
	// 		$this->cnfTruckEntryLCL($rot,$blNo,$cont_status,$assignmentType,$msg);
	// 		return;
	// 		}
	// 	$session_token = json_decode($getData, true );
	// 	//echo $session_token ;
	// 	//echo "<br/>";
	// 	$token_str = explode('"',$session_token);
	// 	//echo $token_str;
	// 	//echo "<br/>";
	// 	$token=$token_str[7];
	// 	//echo $token;
	// 	//return;

	// 	$direct_api_url= "https://spg.sblesheba.com:6313/SpgLanding/SpgLanding/".$token;
	// 	header('Location:'.$direct_api_url);  
	// 	exit;

	// }


	function onlinePaymentSuccess()
	{	
		$getData = file_get_contents('php://input');

		$myXMLData = urldecode ($getData);
		//header("Content-Type: application/xml");
		//echo '<br/>';
		@$myXMLData=str_replace("Request=","",$myXMLData);

		//echo @$myXMLData;

		@$xml = simplexml_load_string($myXMLData);
		if ($xml === false) {
		echo "Failed loading XML: ";
		foreach(libxml_get_errors() as $error) {
		//echo "<br>", $error->message;
		}
		} else {
		//print_r($xml);
		}
		
		/*  print_r($xml);
		return;  */   

		$ApiAccessUserId=$xml->ApiAccessUserId;
		$TransactionId=$xml->TransactionId;
		$TranDateTime=$xml->TranDateTime;
		$RefTranNo=$xml->RefTranNo;
		$RefTranDateTime=$xml->RefTranDateTime;
		$TranAmount=$xml->TranAmount;
		$PayAmount=$xml->PayAmount;
		$PayMode=$xml->PayMode;
		$OrgiBrCode=$xml->OrgiBrCode;
		$StatusMsg=$xml->StatusMsg;
		$Vat=$xml->Vat;
		$Commission=$xml->Commission;
		$TransactionStatus=$xml->TransactionStatus;
		$ScrollNo=$xml->ScrollNo;
		
		$data = explode("_",$RefTranNo);
		$requst_id = $data[0];
		$flag = $data[1];
		$msg = "";

		
		//echo $ApiAccessUserId.'-'.$TransactionId.'-'.$TranAmount.'-'.$StatusMsg;
		if( $PayMode != "A01" && $TransactionStatus == "200")
		{
			if($flag == 0)		// single pay ???
			{
				
				$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type  FROM vcms_online_pay_copy WHERE requ_id='$requst_id'";
				$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
				for($k = 0; $k < count($data_pay); $k++)
				{
					$visitId = $data_pay[$k]['visit_id'];
					$login_id = $data_pay[$k]['cnf_login_id'];
					$assignmentType = $data_pay[$k]['assign_type'];
				
				}
			 	$query_update = "UPDATE do_truck_details_entry SET paid_amt = '57.5', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 

				$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
				$data_truck = $this->bm->dataSelectDB1($query_Truck);

				$driver_gate_pass = "";
				$assistant_gate_pass = "";

				for($i=0;$i<count($data_truck);$i++){
					$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
					$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
				}
          //$update_st=1;
				// if($update_st == 1){
				// 	$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visitId."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
				// 	$json = file_get_contents($url);
				// 	$obj = json_decode($json);
				// }
				$pay_update_str = "UPDATE vcms_online_pay_copy 
				SET  trans_id = '$TransactionId', trans_time='$TranDateTime', payAmount = '57.50', tranAmount='$TranAmount', vat='$Vat', 
				commision='$Commission', PayMode='$PayMode', statusMsg='$StatusMsg',
				refTranDateTime='$RefTranDateTime', orgiBrCode = '$OrgiBrCode', scrollNo='$ScrollNo', TransactionStatus='$TransactionStatus',
				updated_by='C&F', update_time=NOW() WHERE requ_id = '$requst_id' AND visit_id = '$visitId'";
				$update_st = $this->bm->dataUpdateDB1($pay_update_str);
				
			}
			else if($flag == 1)
			{
				
				 $str_online_dt = "SELECT visit_id, cnf_login_id, assign_type  FROM vcms_online_pay_copy WHERE requ_id='$requst_id'";
				$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
				for($k = 0; $k < count($data_pay); $k++)
				{
					$visitId = $data_pay[$k]['visit_id'];
					$login_id = $data_pay[$k]['cnf_login_id'];
					$assignmentType = $data_pay[$k]['assign_type'];
					
					//Equally all amount divided
					//$PayAmount_ind=$PayAmount/count($data_pay)
					//$TranAmount_ind=$TranAmount/count($data_pay)
					
					
					
				 	$query_update = "UPDATE do_truck_details_entry SET paid_amt = '57.50', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
					$update_st = $this->bm->dataUpdateDB1($query_update); 

					$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
					$data_truck = $this->bm->dataSelectDB1($query_Truck);

					$driver_gate_pass = "";
					$assistant_gate_pass = "";
					
					
				 	$pay_update_str = "UPDATE vcms_online_pay_copy
					SET  trans_id = '$TransactionId', trans_time='$TranDateTime', payAmount = '$PayAmount', tranAmount='$TranAmount', vat='$Vat', commision='$Commission', PayMode='$PayMode', statusMsg='$StatusMsg',
					refTranDateTime='$RefTranDateTime', orgiBrCode = '$OrgiBrCode', scrollNo='$ScrollNo', TransactionStatus='$TransactionStatus', updated_by='C&F', update_time=NOW()
					WHERE requ_id = '$requst_id' AND visit_id = '$visitId'";
					$update_st = $this->bm->dataUpdateDB1($pay_update_str);

					for($i=0;$i<count($data_truck);$i++){
						$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
						$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
					}
					//$update_st=1;
					 if($update_st == 1){
						$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visitId."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
						$json = file_get_contents($url);
						$obj = json_decode($json);
					} 
					
					
				
				}
				


				/* $query_update = "UPDATE do_truck_details_entry SET paid_amt = '$amount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE cont_no = '$cont' AND import_rotation = '$rot' AND paid_status = 0";
				$this->bm->dataUpdateDB1($query_update); */

			}
			//echo "  STATUS: ".$status."- TRANSACTION DATE: ".$tran_date."- TRANSACTION ID: ".$tran_id."- PAYMENT TYPE: ".$card_type;

		}
		else
		{
			$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type  FROM vcms_online_pay_copy WHERE requ_id='$requst_id'";
				$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
				for($k = 0; $k < count($data_pay); $k++)
				{
					$visitId = $data_pay[$k]['visit_id'];
					$login_id = $data_pay[$k]['cnf_login_id'];
					$assignmentType = $data_pay[$k]['assign_type'];
				
				}
		}

		//Storing Session  - start
		$result=$this->db->query("select *,md5(new_pass) as dpass,IF(DATEDIFF(Expire_date,NOW())<=0,1,IF(Expire_date IS NULL,1,0)) AS isexpired from users where login_id='$login_id'");
		//echo "select *,md5(new_pass) as dpass from users where login_id='$username'";
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$mdata=$row->org_Type_id;
			$mdatap=$row->login_password;
			$userPass1=$row->dpass;					
		}

		$mdata_org="";
		$result_org=$this->db->query("select * from tbl_org_types where id='$mdata'");
		//echo "select * from tbl_org_types where id='$mdata'";
		if($result_org->num_rows() > 0)
		{
			$row_org =$result_org->row();
			$mdata_org=$row_org->Org_Type;
		}

		$mdata_license="";
		$mdata_Organization_Name="";
		$result_license=$this->db->query("select * from organization_profiles where id='$row->org_id'");
		//echo ("select * from organization_profiles where id='$row->org_id'");
		if($result_license->num_rows()>0)
		{
			$row_license =$result_license->row();
			$mdata_license=$row_license->License_No;
			$mdata_Organization_Name=$row_license->Organization_Name;
		}

		$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>2,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
		'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
		'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));


		//Storing Session  - End
		$assignmentType = "";
		$rotNo = "";
		$contNo = "";
		$verify_info_fcl_id = "";
		$verify_other_data_id = "";
		 $query_contRot = "SELECT import_rotation,cont_no,verify_info_fcl_id,verify_other_data_id FROM do_truck_details_entry WHERE id = '$visitId'";
		$rslt_contRot = $this->bm->dataSelectDB1($query_contRot);
		
		for($i=0;$i<count($rslt_contRot);$i++){
			$rotNo = $rslt_contRot[$i]['import_rotation'];
			$contNo = $rslt_contRot[$i]['cont_no'];
			$verify_info_fcl_id = $rslt_contRot[$i]['verify_info_fcl_id'];
			$verify_other_data_id = $rslt_contRot[$i]['verify_other_data_id'];
		}
		// $rotNo.'--'.$contNo.'--'.$verify_info_fcl_id.'--'.$verify_other_data_id;
		$cont_status = "";

		if($verify_other_data_id == ""){
			$cont_status = "FCL";
		}
		else if($verify_info_fcl_id == "")
		{
			$cont_status = "LCL";
		}

		$bl_query = "SELECT bl_no FROM lcl_dlv_assignment WHERE id = '$verify_other_data_id'";
		
		$rslt_blQuery = $this->bm->dataSelectDB1($bl_query);
		$blNo = "";
		for($j=0;$j<count($rslt_blQuery);$j++){
			$blNo = $rslt_blQuery[$j]['bl_no'];
		}
		
		//$msg = "<font size=4 color=green> Payment completed successfully.</font>";
	 
		//echo '<pre>'; print_r($this->session->all_userdata());
		// $this->cnfTruckEntryLCL($rotNo,$contNo,$cont_status,$assignmentType,$msg);
		$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,$assignmentType,$msg);

	}

	// function onlinePaymentSuccess()
	// {	
	// 	$getData = file_get_contents('php://input');

	// 	$myXMLData = urldecode ($getData);
	// 	//header("Content-Type: application/xml");
	// 	//echo '<br/>';
	// 	@$myXMLData=str_replace("Request=","",$myXMLData);

	// 	//echo @$myXMLData;

	// 	@$xml = simplexml_load_string($myXMLData);
	// 	if ($xml === false) {
	// 	echo "Failed loading XML: ";
	// 	foreach(libxml_get_errors() as $error) {
	// 	//echo "<br>", $error->message;
	// 	}
	// 	} else {
	// 	//print_r($xml);
	// 	}
		
	// 	/*  print_r($xml);
	// 	return;  */   

	// 	$ApiAccessUserId=$xml->ApiAccessUserId;
	// 	$TransactionId=$xml->TransactionId;
	// 	$TranDateTime=$xml->TranDateTime;
	// 	$RefTranNo=$xml->RefTranNo;
	// 	$RefTranDateTime=$xml->RefTranDateTime;
	// 	$TranAmount=$xml->TranAmount;
	// 	$PayAmount=$xml->PayAmount;
	// 	$PayMode=$xml->PayMode;
	// 	$OrgiBrCode=$xml->OrgiBrCode;
	// 	$StatusMsg=$xml->StatusMsg;
	// 	$Vat=$xml->Vat;
	// 	$Commission=$xml->Commission;
	// 	$TransactionStatus=$xml->TransactionStatus;
	// 	$ScrollNo=$xml->ScrollNo;
		
	// 	$data = explode("_",$RefTranNo);
	// 	$requst_id = $data[0];
	// 	$flag = $data[1];
	// 	$msg = "";

		
	// 	//echo $ApiAccessUserId.'-'.$TransactionId.'-'.$TranAmount.'-'.$StatusMsg;
	// 	if( $PayMode != "A01" && $TransactionStatus == "200")
	// 	{
	// 		if($flag == 0)		// single pay ???
	// 		{
				
	// 			$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type  FROM vcms_online_pay WHERE requ_id='$requst_id'";
	// 			$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
	// 			for($k = 0; $k < count($data_pay); $k++)
	// 			{
	// 				$visitId = $data_pay[$k]['visit_id'];
	// 				$login_id = $data_pay[$k]['cnf_login_id'];
	// 				$assignmentType = $data_pay[$k]['assign_type'];
				
	// 			}
	// 		 	$query_update = "UPDATE do_truck_details_entry SET paid_amt = '57.5', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
	// 			$update_st = $this->bm->dataUpdateDB1($query_update); 

	// 			$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
	// 			$data_truck = $this->bm->dataSelectDB1($query_Truck);

	// 			$driver_gate_pass = "";
	// 			$assistant_gate_pass = "";

	// 			for($i=0;$i<count($data_truck);$i++){
	// 				$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
	// 				$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
	// 			}
    //       //$update_st=1;
	// 			// if($update_st == 1){
	// 			// 	$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visitId."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
	// 			// 	$json = file_get_contents($url);
	// 			// 	$obj = json_decode($json);
	// 			// }
	// 			$pay_update_str = "UPDATE vcms_online_pay
	// 			SET  trans_id = '$TransactionId', trans_time='$TranDateTime', payAmount = '57.50', tranAmount='$TranAmount', vat='$Vat', 
	// 			commision='$Commission', PayMode='$PayMode', statusMsg='$StatusMsg',
	// 			refTranDateTime='$RefTranDateTime', orgiBrCode = '$OrgiBrCode', scrollNo='$ScrollNo', TransactionStatus='$TransactionStatus',
	// 			updated_by='C&F', update_time=NOW() WHERE requ_id = '$requst_id' AND visit_id = '$visitId'";
	// 			$update_st = $this->bm->dataUpdateDB1($pay_update_str);
				
	// 		}
	// 		else if($flag == 1)
	// 		{
				
	// 			 $str_online_dt = "SELECT visit_id, cnf_login_id, assign_type  FROM vcms_online_pay WHERE requ_id='$requst_id'";
	// 			$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
	// 			for($k = 0; $k < count($data_pay); $k++)
	// 			{
	// 				$visitId = $data_pay[$k]['visit_id'];
	// 				$login_id = $data_pay[$k]['cnf_login_id'];
	// 				$assignmentType = $data_pay[$k]['assign_type'];
					
	// 				//Equally all amount divided
	// 				//$PayAmount_ind=$PayAmount/count($data_pay)
	// 				//$TranAmount_ind=$TranAmount/count($data_pay)
					
					
					
	// 			 	$query_update = "UPDATE do_truck_details_entry SET paid_amt = '57.50', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
	// 				$update_st = $this->bm->dataUpdateDB1($query_update); 

	// 				$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
	// 				$data_truck = $this->bm->dataSelectDB1($query_Truck);

	// 				$driver_gate_pass = "";
	// 				$assistant_gate_pass = "";
					
					
	// 			 	$pay_update_str = "UPDATE vcms_online_pay
	// 				SET  trans_id = '$TransactionId', trans_time='$TranDateTime', payAmount = '$PayAmount', tranAmount='$TranAmount', vat='$Vat', commision='$Commission', PayMode='$PayMode', statusMsg='$StatusMsg',
	// 				refTranDateTime='$RefTranDateTime', orgiBrCode = '$OrgiBrCode', scrollNo='$ScrollNo', TransactionStatus='$TransactionStatus', updated_by='C&F', update_time=NOW()
	// 				WHERE requ_id = '$requst_id' AND visit_id = '$visitId'";
	// 				$update_st = $this->bm->dataUpdateDB1($pay_update_str);

	// 				for($i=0;$i<count($data_truck);$i++){
	// 					$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
	// 					$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
	// 				}
	// 				//$update_st=1;
	// 				 if($update_st == 1){
	// 					$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visitId."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
	// 					$json = file_get_contents($url);
	// 					$obj = json_decode($json);
	// 				} 
					
					
				
	// 			}
				


	// 			/* $query_update = "UPDATE do_truck_details_entry SET paid_amt = '$amount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE cont_no = '$cont' AND import_rotation = '$rot' AND paid_status = 0";
	// 			$this->bm->dataUpdateDB1($query_update); */

	// 		}
	// 		//echo "  STATUS: ".$status."- TRANSACTION DATE: ".$tran_date."- TRANSACTION ID: ".$tran_id."- PAYMENT TYPE: ".$card_type;

	// 	}
	// 	else
	// 	{
	// 		$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type  FROM vcms_online_pay WHERE requ_id='$requst_id'";
	// 			$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
	// 			for($k = 0; $k < count($data_pay); $k++)
	// 			{
	// 				$visitId = $data_pay[$k]['visit_id'];
	// 				$login_id = $data_pay[$k]['cnf_login_id'];
	// 				$assignmentType = $data_pay[$k]['assign_type'];
				
	// 			}
	// 	}

	// 	//Storing Session  - start
	// 	$result=$this->db->query("select *,md5(new_pass) as dpass,IF(DATEDIFF(Expire_date,NOW())<=0,1,IF(Expire_date IS NULL,1,0)) AS isexpired from users where login_id='$login_id'");
	// 	//echo "select *,md5(new_pass) as dpass from users where login_id='$username'";
	// 	if($result->num_rows() > 0)
	// 	{
	// 		$row = $result->row();
	// 		$mdata=$row->org_Type_id;
	// 		$mdatap=$row->login_password;
	// 		$userPass1=$row->dpass;					
	// 	}

	// 	$mdata_org="";
	// 	$result_org=$this->db->query("select * from tbl_org_types where id='$mdata'");
	// 	//echo "select * from tbl_org_types where id='$mdata'";
	// 	if($result_org->num_rows() > 0)
	// 	{
	// 		$row_org =$result_org->row();
	// 		$mdata_org=$row_org->Org_Type;
	// 	}

	// 	$mdata_license="";
	// 	$mdata_Organization_Name="";
	// 	$result_license=$this->db->query("select * from organization_profiles where id='$row->org_id'");
	// 	//echo ("select * from organization_profiles where id='$row->org_id'");
	// 	if($result_license->num_rows()>0)
	// 	{
	// 		$row_license =$result_license->row();
	// 		$mdata_license=$row_license->License_No;
	// 		$mdata_Organization_Name=$row_license->Organization_Name;
	// 	}

	// 	$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>2,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
	// 	'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
	// 	'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));


	// 	//Storing Session  - End
	// 	$assignmentType = "";
	// 	$rotNo = "";
	// 	$contNo = "";
	// 	$verify_info_fcl_id = "";
	// 	$verify_other_data_id = "";
	// 	 $query_contRot = "SELECT import_rotation,cont_no,verify_info_fcl_id,verify_other_data_id FROM do_truck_details_entry WHERE id = '$visitId'";
	// 	$rslt_contRot = $this->bm->dataSelectDB1($query_contRot);
		
	// 	for($i=0;$i<count($rslt_contRot);$i++){
	// 		$rotNo = $rslt_contRot[$i]['import_rotation'];
	// 		$contNo = $rslt_contRot[$i]['cont_no'];
	// 		$verify_info_fcl_id = $rslt_contRot[$i]['verify_info_fcl_id'];
	// 		$verify_other_data_id = $rslt_contRot[$i]['verify_other_data_id'];
	// 	}
	// 	// $rotNo.'--'.$contNo.'--'.$verify_info_fcl_id.'--'.$verify_other_data_id;
	// 	$cont_status = "";

	// 	if($verify_other_data_id == ""){
	// 		$cont_status = "FCL";
	// 	}
	// 	else if($verify_info_fcl_id == "")
	// 	{
	// 		$cont_status = "LCL";
	// 	}

	// 	$bl_query = "SELECT bl_no FROM lcl_dlv_assignment WHERE id = '$verify_other_data_id'";
		
	// 	$rslt_blQuery = $this->bm->dataSelectDB1($bl_query);
	// 	$blNo = "";
	// 	for($j=0;$j<count($rslt_blQuery);$j++){
	// 		$blNo = $rslt_blQuery[$j]['bl_no'];
	// 	}
		
	// 	//$msg = "<font size=4 color=green> Payment completed successfully.</font>";
	 
	// 	//echo '<pre>'; print_r($this->session->all_userdata());
	// 	// $this->cnfTruckEntryLCL($rotNo,$contNo,$cont_status,$assignmentType,$msg);
	// 	$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,$assignmentType,$msg);

	// }


	//Payment Collection - Starts

	function paymentCollection()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['title']="Payment Collection Form for LCL";
			$data['msg'] = "";
			$data['flag'] = 0;
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('paymentCollectionFormLCL',$data);
			$this->load->view('jsAssets');
		}
	}

	function paymentDataSearch()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$trucVisitId = $this->input->post("trucVisitId");
			
			$sql_slotFlag = "SELECT IF(currTime>visit_time_slot_end,'slotOut','slotIn') AS slotFlag FROM
			(SELECT (SELECT NOW()) AS currTime,visit_time_slot_start,visit_time_slot_end
			FROM do_truck_details_entry
			WHERE id='$trucVisitId') AS tbl";
			$rslt_slotFlag = $this->bm->dataSelectDB1($sql_slotFlag);
			$slotFlag = "";
			for($i=0;$i<count($rslt_slotFlag);$i++){
				$slotFlag = $rslt_slotFlag[$i]['slotFlag'];
			}
			
			$msg = "";
			
			if($slotFlag=="slotOut")
			{
				$msg = "<font color='red'>Truck's time slot is over.</font>";
			}
			
			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$trucVisitId'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}

			// echo $cont;
			// return;
			

			// $rslt_status = $this->chkBlockedContainer($cont);
			$rslt_status = $this->bm->chkBlockedContainer($cont,$trucVisitId);
			//var_dump($rslt_status);
			$cont_status = "";

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}

			//echo $cont_status;
			//return;

			$data['cont_status'] = $cont_status;
			$data['trucVisitId'] = $trucVisitId;
			$data['title']="Payment Collection Form for LCL";
			$data['msg'] = $msg;
			$data['flag'] = 1;
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('paymentCollectionFormLCL',$data);
			$this->load->view('jsAssets');
		}
	}

	function paymentStsCng()
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
			$id = $this->input->post("id");
			$login_id = $this->session->userdata('login_id');
			$gate_no = $this->session->userdata('section');
			$driverPass = $this->input->post("driverPass");
			$helperPass = $this->input->post("helperPass");
			$gate = $this->input->post("gate_no");
			
			if($login_id == "pass"){
				$data['msg'] = "<font color='red'>You are not authorized to collect payment!</font>";
			}
			else
			{
				if($gate == "")
				{
					$stsUpdateQuery = "UPDATE do_truck_details_entry SET gate_no='$gate_no', paid_status='1' , paid_collect_dt = NOW() , paid_collect_by = '$login_id' , pay_collect_ip = '$ip_address' , collect_gate_no = '$gate_no' WHERE id='$id'";
				}
				else
				{
					$stsUpdateQuery = "UPDATE do_truck_details_entry SET paid_status='1' , paid_collect_dt = NOW() , paid_collect_by = '$login_id' , pay_collect_ip = '$ip_address' , collect_gate_no = '$gate_no' WHERE id='$id'";
				}

				$rslt_update=$this->bm->dataUpdateDB1($stsUpdateQuery);

				$dataQuery = "SELECT update_by,truck_id FROM do_truck_details_entry WHERE id='$id'";
				$rslt_data=$this->bm->dataSelectDB1($dataQuery);

				$cfId = "";
				$truckId = "";
				for($i=0;$i<count($rslt_data);$i++){
					$cfId = $rslt_data[$i]['update_by'];
					$truckId = $rslt_data[$i]['truck_id'];
				}
				
				$ain = substr($cfId,0,-2);
				$trkPart = explode(" ",$truckId);
				$trck = $trkPart[0]." ".$trkPart[3]." ".$trkPart[4];
				//$trck = urlencode($trck);

				if($rslt_update == 1)
				{
					if($id != 0)
					{
						$eventType = "ISSUE";
						$biometricInsertQuery = "INSERT INTO biometricEventLog(visit_id,event_type,ain_no,driver_pass,helper_pass,truck_id,entry_at,entry_by,entry_ip) VALUES('$id','$eventType','$ain','$driverPass','$helperPass','$trck',NOW(),'$login_id','$ip_address')";
						$this->bm->dataInsertDB1($biometricInsertQuery);
					}
				}
				
				$data['msg'] = "";
			}
			

			$data['cont_status'] = "";
			$data['trucVisitId'] = $id;
			$data['title']="Payment Collection Form for LCL";
			$data['flag'] = 1;

			$this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('paymentCollectionFormLCL',$data);
			$this->load->view('jsAssets');

		}
	}

	// Payment Collection -- Ends

	function vehicleGatePass()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {	
			$rot_no = $this->input->post("rot_no");
			$cont_no = $this->input->post("cont_no");
			$trucVisitId = $this->input->post("trucVisitId");

			$data['title']="Truck Entrance Application";
			$data['rot_no'] = $rot_no;
			$data['cont_no'] = $cont_no;
			$data['trucVisitId'] = $trucVisitId;
			$data['login_id'] = $login_id;

			$this->load->view('vehiclaGatePass',$data);
			

		}
	}


		//Gate In Process - Starts

		function gateInProcessForm()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
			
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
	
				$data['title']="Gate In Process Form for LCL";
				$data['msg'] = "";
				$data['flag'] = 0;
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('gateInProcessFormLCL',$data);
				$this->load->view('jsAssets');
			}
		}
	
		function gateInDataSearch()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
			
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
				$gate_pass = $this->input->post("gate_pass");
				
				
				$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$gate_pass'";
				$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
				$cont = "";
				for($i=0;$i<count($rslt_cont);$i++){
					$cont = $rslt_cont[$i]['cont_no'];
				}
				
				// $rslt_status = $this->chkBlockedContainer($cont);
				$rslt_status = $this->bm->chkBlockedContainer($cont,$gate_pass);
				//var_dump($rslt_status);
				$cont_status = "";
	
				for($i = 0;$i<count($rslt_status);$i++){
					$cont_status = $rslt_status[$i]['custom_block_st'];
				}
				
				
				// $sql_agentPhoto = "SELECT driver_gate_pass,assistant_gate_pass
				// FROM do_truck_details_entry WHERE id = '$gate_pass'";
				// $rslt_agentPhoto = $this->bm->dataSelectDB1($sql_agentPhoto);
				// $driverPhoto = "";
				// $helperPhoto = "";
				
				// if(count($rslt_agentPhoto)>0){
					// $driverPass = $rslt_agentPhoto['0']['driver_gate_pass'];
					// $helperPass = $rslt_agentPhoto['0']['assistant_gate_pass'];
					// $url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$driverPass;
					// $json = file_get_contents($url);
					// $obj = json_decode($json);
					// $driverPhoto = "";
					// if(count($obj)>0)
					// {						
					// 	$driverPhoto = $obj->photobase64;
					// }
					
					// $im = $driverPhoto;
					
					// $path = $_SERVER['DOCUMENT_ROOT'].'/pcs/resources/biometricPhoto/'.$driverPass;
					
					// if(!file_exists($path)){
					// 	mkdir($path, 0777, true);
					// 	chmod($path, 0777);
	
					// 	$output_file=$_SERVER['DOCUMENT_ROOT'].'/pcs/resources/biometricPhoto/'.$driverPass."/".$driverPass.'.png';	
					// 	$ifp = fopen( $output_file, 'wb' ); 
	
					// 	$data = explode( ',', $im );
	
					// 	// we could add validation here with ensuring count( $data ) > 1
					// 	fwrite( $ifp, base64_decode( $data[ 1 ] ) );
	
					// 	// clean up the file resource
					// 	fclose( $ifp );
					// }
									 
								
					// if($helperPass != ""){
						// $url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$helperPass;
						// $json = file_get_contents($url);
						// $obj = json_decode($json);
						// $helperPhoto = "";
						// if(count($obj)>0)
						// {						
						// 	$helperPhoto = $obj->photobase64;
						// }
	
						// $im = $helperPhoto;
					
						// $path = $_SERVER['DOCUMENT_ROOT'].'/pcs/resources/biometricPhoto/'.$helperPass;
						
						// if(!file_exists($path)){
						// 	mkdir($path, 0777, true);
						// 	chmod($path, 0777);
	
						// 	$output_file=$_SERVER['DOCUMENT_ROOT'].'/pcs/resources/biometricPhoto/'.$helperPass."/".$helperPass.'.png';	
						// 	$ifp = fopen( $output_file, 'wb' ); 
	
						// 	$data = explode( ',', $im );
	
						// 	// we could add validation here with ensuring count( $data ) > 1
						// 	fwrite( $ifp, base64_decode( $data[ 1 ] ) );
	
						// 	// clean up the file resource
						// 	fclose( $ifp );
						// }
					// }
	
					
				// }
				
				$data['cont_status'] = $cont_status;
				
				$data['gate_pass'] = $gate_pass;
				// $data['driverPhoto'] = $driverPhoto;
				// $data['helperPhoto'] = $helperPhoto;
				$data['title']="Gate In Process for LCL";
				$data['msg'] = "";
				$data['flag'] = 1;
				$data['gateFlag'] = 0;
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('gateInProcessFormLCL',$data);
				$this->load->view('jsAssets');
			}
		}
	
		function gateInStsCng()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
			
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
				$gate_pass = $this->input->post("gate_pass");
				$id = $this->input->post("id");
				$login_id = $this->session->userdata('login_id');
				
				$sql_agentPhoto = "SELECT driver_gate_pass,assistant_gate_pass
				FROM do_truck_details_entry WHERE id = '$id'";
				$rslt_agentPhoto = $this->bm->dataSelectDB1($sql_agentPhoto);
				$driverPass = $rslt_agentPhoto['0']['driver_gate_pass'];
				$helperPass = $rslt_agentPhoto['0']['assistant_gate_pass'];
				
				
				$url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$driverPass;
				$json = file_get_contents($url);
				$obj = json_decode($json);
				$driverPhoto = "";
				if(count($obj)>0)
				{						
					$driverPhoto = $obj->photobase64;
				}
				
				$url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$helperPass;
				$json = file_get_contents($url);
				$obj = json_decode($json);
				$helperPhoto = "";
				if(count($obj)>0)
				{						
					$helperPhoto = $obj->photobase64;
				}
	
				$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$gate_pass'";
				$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
				$cont = "";
				for($i=0;$i<count($rslt_cont);$i++){
					$cont = $rslt_cont[$i]['cont_no'];
				}
	
				// echo $cont;
				// return;
				
	
				$rslt_status = $this->chkBlockedContainer($cont);
				//var_dump($rslt_status);
				$cont_status = "";
	
				for($i = 0;$i<count($rslt_status);$i++){
					$cont_status = $rslt_status[$i]['custom_block_st'];
				}
	
				$data['cont_status'] =  $cont_status;
	
				$getInQuery = "UPDATE do_truck_details_entry SET gate_in_status = '1' , gate_in_by = '$login_id' , gate_in_from = 'web' , gate_in_time = NOW() WHERE id='$id'";
	
				$rslt_update=$this->bm->dataUpdateDB1($getInQuery);
	
				if($rslt_update>0)
				{
					$msg="<font color='green'><b>Truck Gate In Process Successfully Done!!!</b></font>";
				}
				else
				{
					$msg="<font color='red'><b>Truck Can't Get In!!!</b></font>";
				}
				
				$data['gate_pass'] = $gate_pass;
				$data['driverPhoto'] = $driverPhoto;
				$data['helperPhoto'] = $helperPhoto;
				$data['title']="Gate In Process for LCL";
				$data['msg'] = $msg;
				$data['flag'] = 1;
				$data['gateFlag'] = 1;
	
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('gateInProcessFormLCL',$data);
				$this->load->view('jsAssets');
	
			}
		}
	
		//Gate In Process - Ends
		
		// shed wise head delivery report - start
	function shedWiseDeliveryReportForm()
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			$org_Type_id = $this->session->userdata('org_Type_id');
			
			$yard_query = "SELECT DISTINCT shed_yard FROM shed_tally_info WHERE shed_yard LIKE '%Shed%' OR shed_yard IN ('CFS/CCT','CFS/NCT')";
			$yardList = $this->bm->dataSelectDb1($yard_query);
			$data['yardList']=$yardList;

			$data['org_Type_id'] = $org_Type_id;
			$data['title']="LCL Head Delivery List";
			$data['msg'] = "";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('shedWiseDeliveryReportForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function shedWiseDeliveryReportView()
	{
		$this->load->library('m_pdf');
		$mpdf->use_kwt = true;
		$mpdf->simpleTables = true;	
			
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		$shed = $this->input->post('shed');
		$sql = "SELECT  DISTINCT do_truck_details_entry.id AS visit_id, igm_detail_container.cont_number, truck_id, 
			load_by, gate_out_time,oracle_nts_data.cp_no, oracle_nts_data.imp_rot_no, oracle_nts_data.be_no,
			shed_tally_info.shed_yard, oracle_nts_data.cnf_name, load_time, 
			CONCAT(igm_details.Pack_Number, ' ', igm_details.Pack_Description) AS packages,
			vcms_vehicle_agent.agent_name, igm_details.weight, igm_details.BL_No, Vessel_Name,
			do_truck_details_entry.actual_delv_pack, igm_pack_unit.Pack_Unit
			FROM do_truck_details_entry
			INNER JOIN lcl_dlv_assignment ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id 
			INNER JOIN oracle_nts_data ON lcl_dlv_assignment.cp_no=oracle_nts_data.cp_no
			INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			INNER JOIN shed_tally_info ON shed_tally_info.cont_number=igm_detail_container.cont_number AND shed_tally_info.import_rotation=oracle_nts_data.imp_rot_no
			LEFT JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=lcl_dlv_assignment.jetty_sirkar_id
			LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
			WHERE igm_detail_container.cont_status='LCL' 
			AND shed_tally_info.shed_yard='$shed'
			AND DATE (load_time) BETWEEN '$from_date' AND '$to_date'

			UNION 

			SELECT  DISTINCT do_truck_details_entry.id AS visit_id, igm_sup_detail_container.cont_number, truck_id, 
			load_by, gate_out_time,oracle_nts_data.cp_no, oracle_nts_data.imp_rot_no, oracle_nts_data.be_no,
			shed_tally_info.shed_yard, oracle_nts_data.cnf_name, load_time,
			CONCAT(igm_supplimentary_detail.Pack_Number, ' ', igm_supplimentary_detail.Pack_Description) AS packages,
			vcms_vehicle_agent.agent_name, igm_supplimentary_detail.weight, igm_supplimentary_detail.BL_No, Vessel_Name,
			do_truck_details_entry.actual_delv_pack, igm_pack_unit.Pack_Unit

			FROM do_truck_details_entry
			INNER JOIN lcl_dlv_assignment ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id 
			INNER JOIN oracle_nts_data ON lcl_dlv_assignment.cp_no=oracle_nts_data.cp_no
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
			INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
			INNER JOIN shed_tally_info ON shed_tally_info.cont_number=igm_sup_detail_container.cont_number AND shed_tally_info.import_rotation=oracle_nts_data.imp_rot_no
			LEFT JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=lcl_dlv_assignment.jetty_sirkar_id
			LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
			WHERE igm_sup_detail_container.cont_status='LCL' 
			AND shed_tally_info.shed_yard='$shed'
			AND DATE (load_time) BETWEEN '$from_date' AND '$to_date'
			
			UNION		
			
			SELECT  DISTINCT do_truck_details_entry.id AS visit_id, igm_detail_container.cont_number, truck_id, 
			load_by, gate_out_time,oracle_nts_data.cp_no, oracle_nts_data.imp_rot_no, oracle_nts_data.be_no,
			shed_tally_info.shed_yard, oracle_nts_data.cnf_name, load_time, 
			CONCAT(igm_details.Pack_Number, ' ', igm_details.Pack_Description) AS packages,

			(SELECT vcms_vehicle_agent.agent_name FROM 
			vcms_vehicle_agent
			INNER JOIN lcl_dlv_assignment ON vcms_vehicle_agent.id=lcl_dlv_assignment.jetty_sirkar_id
			INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id 
			WHERE do_truck_details_entry.id=do_truck_details_additional_bl_lcl.truck_visit_id
			) AS agent_name,

			igm_details.weight, igm_details.BL_No, Vessel_Name,
			do_truck_details_additional_bl_lcl.pack_num AS actual_delv_pack, igm_pack_unit.Pack_Unit
			FROM do_truck_details_entry
			INNER JOIN do_truck_details_additional_bl_lcl ON do_truck_details_additional_bl_lcl.truck_visit_id=do_truck_details_entry.id
			INNER JOIN oracle_nts_data ON do_truck_details_additional_bl_lcl.bl_no=oracle_nts_data.bl_no 
			-- AND  oracle_nts_data.imp_rot_no=do_truck_details_entry.import_rotation

			INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			INNER JOIN shed_tally_info ON shed_tally_info.cont_number=igm_detail_container.cont_number AND shed_tally_info.import_rotation=oracle_nts_data.imp_rot_no

			LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_additional_bl_lcl.pack_unit
			WHERE igm_detail_container.cont_status='LCL' 
			AND shed_tally_info.shed_yard='$shed'
			AND DATE (load_time) BETWEEN '$from_date' AND '$to_date'


			UNION


			SELECT  DISTINCT do_truck_details_entry.id AS visit_id, igm_sup_detail_container.cont_number, truck_id, 
			load_by, gate_out_time,oracle_nts_data.cp_no, oracle_nts_data.imp_rot_no, oracle_nts_data.be_no,
			shed_tally_info.shed_yard, oracle_nts_data.cnf_name, load_time, 
			CONCAT(igm_supplimentary_detail.Pack_Number, ' ', igm_supplimentary_detail.Pack_Description) AS packages,
			(SELECT vcms_vehicle_agent.agent_name FROM 
			vcms_vehicle_agent
			INNER JOIN lcl_dlv_assignment ON vcms_vehicle_agent.id=lcl_dlv_assignment.jetty_sirkar_id
			INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id 
			WHERE do_truck_details_entry.id=do_truck_details_additional_bl_lcl.truck_visit_id
			) AS agent_name,

			igm_supplimentary_detail.weight, igm_supplimentary_detail.BL_No, Vessel_Name,
			do_truck_details_additional_bl_lcl.pack_num AS actual_delv_pack, igm_pack_unit.Pack_Unit
			FROM do_truck_details_entry
			INNER JOIN do_truck_details_additional_bl_lcl ON do_truck_details_additional_bl_lcl.truck_visit_id=do_truck_details_entry.id
			INNER JOIN oracle_nts_data ON do_truck_details_additional_bl_lcl.bl_no=oracle_nts_data.bl_no 
			-- AND  oracle_nts_data.imp_rot_no=do_truck_details_entry.import_rotation

			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
			INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
			INNER JOIN shed_tally_info ON shed_tally_info.cont_number=igm_sup_detail_container.cont_number AND shed_tally_info.import_rotation=oracle_nts_data.imp_rot_no

			LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_additional_bl_lcl.pack_unit
			WHERE igm_sup_detail_container.cont_status='LCL' 
			AND shed_tally_info.shed_yard='$shed'
			AND DATE (load_time) BETWEEN '$from_date' AND '$to_date'  ORDER BY visit_id ASC";
			// echo $sql;return;
			$dlv_list = $this->bm->dataSelectDB1($sql);
			$this->data['dlv_list']=$dlv_list;
			$this->data['from_date']=$from_date;
			$this->data['to_date']=$to_date;
			$this->data['shed']=$shed;
			//$this->load->view('shedWiseDeliveryReportView',$data);
			$html=$this->load->view('shedWiseDeliveryReportView',$this->data, true); 
			$pdfFilePath ="shedWiseHeadDeliveryReport";

			$pdf = $this->m_pdf->load();
			$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
			$pdf = new mPDF('utf-8', 'A4-L');  //have tried several of the formats
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath, "I");
			
		
	}

		// shed wise head delivery report-- End
	
}