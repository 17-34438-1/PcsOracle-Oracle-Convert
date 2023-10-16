<?php

class EDOController extends CI_Controller {
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
	function index(){
		 //
	}	
	
	function applicationForEDObyrotationBL()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$org_id=$this->session->userdata('org_Type_id');
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {

			$data['title']="Application For EDO.";
			$data['msg'] = "";
			$data['flag'] = "all"; //To show all do list
			
			$data['org_id'] =$this->session->userdata('org_Type_id');
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDObyrotationBL',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function applicationForEDObyrotationBLentry()
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$org_notify_by =$this->session->userdata('login_id');
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="Application For EDO.";
			$data['msg'] = "";
			$data['flag'] = 0;
			$rot_no = trim($this->input->post("rot_no"));
			$bl_no = trim($this->input->post("bl_no"));
			$beNo = trim($this->input->post("be_no"));
			$beDate = trim($this->input->post("be_date"));
			$ofcCode = trim($this->input->post("office_code"));
			$sup_org = "";
			$master_org="";
			$applicationIdRslt="";
			$resInsert = 0;
			
			$ip_address = $_SERVER['REMOTE_ADDR'];
			
			$cnfLoginId = "";
			$chkIfApplicationExists="SELECT COUNT(*) AS rtnValue FROM edo_application_by_cf WHERE rotation='$rot_no' AND bl='$bl_no'";
			$cntExisting = $this->bm->dataReturnDb1($chkIfApplicationExists);
			if($cntExisting > 0){
				$getCnfLoginId = "SELECT edo_applied_by AS rtnValue FROM edo_application_by_cf WHERE rotation='$rot_no' AND bl='$bl_no' ORDER BY id DESC LIMIT 1";
				$cnfLoginId = $this->bm->dataReturnDb1($getCnfLoginId);
			} 
			
			$queryCntApplication="SELECT COUNT(*) AS rtnValue FROM edo_application_by_cf 
									WHERE rotation='$rot_no' AND bl='$bl_no' AND edo_application_by_cf.rejection_st = '0'";						
			$cntApplication = $this->bm->dataReturnDb1($queryCntApplication);
			if(($cntApplication==0) and ($cnfLoginId != $login_id))
			{
				//If there is no application for given rotation & bl no.....
				$type_of_igm = "";
				$blType_BB = "";
				$cnt_str="SELECT COUNT(*) as rtnValue FROM igm_details 
							WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";						
				$cntResult = $this->bm->dataReturnDb1($cnt_str);
				
				if($cntResult==0)
				{
					$cnt_str_sup="SELECT COUNT(*) AS rtnValue FROM igm_supplimentary_detail 
								WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";
					$cntSupResult = $this->bm->dataReturnDb1($cnt_str_sup);
					if($cntSupResult==0)
					{
						$data['msg']='<font color="red"><b>Wrong Combination of Rotation and BL</b></font>';
					}
					else
					{
						$type_str_sup="SELECT igm_supplimentary_detail.type_of_igm AS rtnValue FROM igm_supplimentary_detail 
								WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";
						$type_of_igm = $this->bm->dataReturnDb1($type_str_sup);
						$blType_BB = "HB";
					}
				}
				else
				{
					$type_str="SELECT igm_details.type_of_igm as rtnValue FROM igm_details 
							WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";						
					$type_of_igm = $this->bm->dataReturnDb1($type_str);
					$blType_BB = "MB";
				}	
		
				
				if($type_of_igm!="")
				{
					$strInsert = "";
					if($type_of_igm=='BB')
					{	
						// "BB";
						$Submitee_Org_Id = "";
						$str = "";
						$str="SELECT Submitee_Org_Id FROM igm_supplimentary_detail  
								WHERE BL_No='$bl_no' AND Import_Rotation_No='$rot_no'";							
						$resltStr = $this->bm->dataSelectDB1($str);
												
						if(count($resltStr) == 0) {
							$str="SELECT igm_details.Submitee_Org_Id FROM igm_details  
								WHERE igm_details.BL_No='$bl_no' AND igm_details.Import_Rotation_No='$rot_no'";							
							$resltStr = $this->bm->dataSelectDB1($str);	
						}
						
						for($i=0;$i<count($resltStr);$i++)
						{
							$Submitee_Org_Id=$resltStr[$i]['Submitee_Org_Id'];
						}					

						$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,sh_agent_org_id,
																		entry_time,sumitted_by,ip_address,edo_applied_by) 
									VALUES('$rot_no','$bl_no','$blType_BB','$type_of_igm','$Submitee_Org_Id', 
																		NOW(),'$login_id','$ip_address','$login_id')";
					}
					else
					{
						$queryStr="SELECT igm_supplimentary_detail.Submitee_Org_Id AS sup_org,
						igm_details.Submitee_Org_Id AS master_org
						FROM igm_supplimentary_detail 
						INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
						WHERE igm_supplimentary_detail.BL_No='$bl_no' AND igm_supplimentary_detail.Import_Rotation_No='$rot_no'";
						$rsltStr = $this->bm->dataSelectDB1($queryStr);
						if(count($rsltStr)>0)
						{
							$bl_type = "HB";
							$sup_org = "";
							$master_org = "";
							$master_bl = "";
							$cont_status = "";
							$ff_stat = 0;
							$ff_clearance_time = "";
							$entry_org_id = "";
							$entered_by = "";
							$entry_time = "";
							$entry_ip_address = "";
							$cleared_by = "";
							$clearance_time = "";
							$clearance_ip = "";
							$cleared_by_org_id = "";
							$clearanceSt = "";
							$mbl_valid_upto_dt = "";
							for($i=0;$i<count($rsltStr);$i++)
							{
								$sup_org=$rsltStr[$i]['sup_org'];
								$master_org=$rsltStr[$i]['master_org'];
							}
							$strQry="select igm_supplimentary_detail.master_BL_No,igm_sup_detail_container.cont_status
							from igm_supplimentary_detail 
							INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
							where igm_supplimentary_detail.Import_Rotation_No='$rot_no' and igm_supplimentary_detail.BL_No='$bl_no'";
							// INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
							$rsltQry = $this->bm->dataSelectDB1($strQry);
							
							$fclStatus = 0;
							for($k=0;$k<count($rsltQry);$k++)
							{
								$cont_status=$rsltQry[$k]['cont_status'];
								$master_bl=$rsltQry[$k]['master_BL_No'];
								
								if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY"){
									$fclStatus = $fclStatus+1;
								}
							}
							if($fclStatus > 0){ 
								$cont_status = "FCL";
							} else {
								$cont_status = $cont_status;
							}
							
							$provided_org_type_id = "";
							$getOrgType = "SELECT org_Type_id FROM users WHERE login_id='$login_id'";
							$rsltOrgType = $this->bm->dataSelectDB1($getOrgType);
							for($orgType=0;$orgType<count($rsltOrgType);$orgType++){
								$provided_org_type_id=$rsltOrgType[$orgType]['org_Type_id'];
							}
							
							if($cont_status=="" or $cont_status==null or $master_bl=="" or $master_bl==null){
								$data['msg']='<font color="red"><b>Sorry! Could not apply EDO.</b></font>';
							} else if($provided_org_type_id !="2") {
								$data['msg']='<font color="red"><b>Sorry! Invalid C&F.</b></font>';
							} else {
								if($cont_status=="LCL")
								{
									//LCL				
									$strChkMBLClearance="select * from cleared_mbl_by_mlo where master_bl='$master_bl'";
									$mblClearance = $this->bm->dataSelectDB1($strChkMBLClearance);
									
									$mblClearanceStatus = count($mblClearance);
									
									if($mblClearanceStatus==0)
									{
										$ff_stat = 0;
										
										$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,mlo,ff_org_id,ff_stat,ff_clearance_time,
																						cont_status,mbl_of_hbl, entry_time, sumitted_by,ip_address,edo_applied_by) 
													VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$sup_org','$ff_stat',
															'$ff_clearance_time','$cont_status','$master_bl' ,NOW(), '$login_id','$ip_address','$login_id')";
										
									}
									
									else
									{
										
										for($l=0;$l<$mblClearanceStatus;$l++)
										{
											$ff_clearance_time=$mblClearance[$l]['entry_time'];
											$clearanceSt=$mblClearance[$l]['clearance_st'];
											$entry_org_id=$mblClearance[$l]['entry_org_id'];
											$entered_by=$mblClearance[$l]['entered_by'];
											$entry_time=$mblClearance[$l]['entry_time'];
											$entry_ip_address=$mblClearance[$l]['entry_ip_address'];
											$cleared_by=$mblClearance[$l]['cleared_by'];
											$clearance_time=$mblClearance[$l]['clearance_time'];
											$clearance_ip=$mblClearance[$l]['clearance_ip'];
											$cleared_by_org_id=$mblClearance[$l]['cleared_by_org_id'];
											$mbl_valid_upto_dt=$mblClearance[$l]['valid_upto_dt'];
										}
										
										$ff_stat = 1;
										$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,mlo,ff_org_id,ff_stat,ff_clearance_time,
										forwarded_by,forwarded_org_id,cont_status,mbl_of_hbl,entry_time,sumitted_by,ip_address,edo_applied_by) 
										VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$sup_org','$ff_stat','$ff_clearance_time','$cleared_by','$cleared_by_org_id','$cont_status','$master_bl' ,NOW(), '$login_id','$ip_address','$login_id')";
										
										
									}
									
								}
								else if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY")
								{
									//FCL or FCL/PART or ETY
									$strChkMBLClearance="select * from cleared_mbl_by_mlo where master_bl='$master_bl'";
									$mblClearance = $this->bm->dataSelectDB1($strChkMBLClearance);
									if(count($mblClearance)==0)
									{
										$ff_stat = 0;									
										
										$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,mlo,ff_org_id,ff_stat,ff_clearance_time,
																						cont_status,mbl_of_hbl, entry_time, sumitted_by,ip_address,edo_applied_by) 
													VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$sup_org','$ff_stat',
															'$ff_clearance_time','$cont_status','$master_bl' ,NOW(), '$login_id','$ip_address','$login_id')";
									}
									else
									{
										
										for($l=0;$l<count($mblClearance);$l++)
										{
											$ff_clearance_time=$mblClearance[$l]['entry_time'];
											$clearanceSt=$mblClearance[$l]['clearance_st'];
											$entry_org_id=$mblClearance[$l]['entry_org_id'];
											$entered_by=$mblClearance[$l]['entered_by'];
											$entry_time=$mblClearance[$l]['entry_time'];
											$entry_ip_address=$mblClearance[$l]['entry_ip_address'];
											$cleared_by=$mblClearance[$l]['cleared_by'];
											$clearance_time=$mblClearance[$l]['clearance_time'];
											$clearance_ip=$mblClearance[$l]['clearance_ip'];
											$cleared_by_org_id=$mblClearance[$l]['cleared_by_org_id'];
											$mbl_valid_upto_dt=$mblClearance[$l]['valid_upto_dt'];
										}
										
										$ff_stat = 1;
										$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,mlo,ff_org_id,ff_stat,ff_clearance_time,
										forwarded_by,forwarded_org_id,valid_upto_dt_by_mlo,cont_status,mbl_of_hbl,entry_time,sumitted_by,ip_address,edo_applied_by) 
										VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$sup_org','$ff_stat',
										'$ff_clearance_time','$cleared_by','$cleared_by_org_id','$mbl_valid_upto_dt','$cont_status','$master_bl',NOW(),
										'$login_id','$ip_address','$login_id')";
										
										
									}
									
								
								}
								else {
									$data['msg']='<font color="red"><b>Sorry! Could not apply EDO...</b></font>';
								}
							}						
						}
						else
						{
							$container_status = "";
							$queryContStatus = "SELECT igm_detail_container.cont_status
												FROM igm_detail_container
												INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
												WHERE igm_details.Import_Rotation_No='$rot_no' AND igm_details.BL_No='$bl_no'";
							$resContStatus = $this->bm->dataSelectDB1($queryContStatus);
							
							$container_status = "";
							$fclStatus = 0 ;
							for($f=0;$f<count($resContStatus);$f++)
							{								
								$container_status=$resContStatus[$f]['cont_status'];
								
								if($container_status=="FCL" or $container_status=="FCL/PART" or $container_status=="ETY"){
									$fclStatus = $fclStatus+1;
								}
							}
							if($fclStatus > 0){
								$container_status = "FCL";
							} else {
								$container_status = $container_status;
							}
							
							//$container_status=$resContStatus[0]['cont_status'];
							
							
							$queryStr="SELECT igm_details.Submitee_Org_Id AS master_org	
							FROM igm_details 
							WHERE BL_No='$bl_no' AND Import_Rotation_No='$rot_no'";
							$rsltStr = $this->bm->dataSelectDB1($queryStr);
							$bl_type = "MB";
							$master_org = "";
							for($i=0;$i<count($rsltStr);$i++)
							{
								$master_org=$rsltStr[$i]['master_org'];
							}
							if($master_org=="" or $master_org==null){
								$data['msg']='<font color="red"><b>Sorry! Could not apply EDO.</b></font>';
							} else {
								$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,mlo,cont_status,
										entry_time,sumitted_by,ip_address,edo_applied_by) 
								VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$container_status',
										NOW(),'$login_id','$ip_address','$login_id')";
							}
							
						}					
					}
					
					$resInsert = $this->bm->dataInsertDB1($strInsert);
					if($resInsert==1)
					{
						$data['msg']='<font color="blue"><b>Inserted Sucessfully.</b></font>';
						if($type_of_igm != 'BB')
						{
							// Application ID
							$applicationIdQuery = "SELECT id FROM edo_application_by_cf WHERE rotation = '$rot_no' AND bl = '$bl_no' AND rejection_st = 0";
							$applicationIdRslt = $this->bm->dataSelectDB1($applicationIdQuery);
							$applicationId = "";
							if(count($applicationIdRslt)>0)
							{
								$applicationId = $applicationIdRslt[0]['id'];
							}

							if($bl_type == "HB"){

								$edoNotifyFFQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
								VALUES('$applicationId','$sup_org',1,0,'$org_notify_by',NOW())";
								//$this->bm->dataInsertDB1($edoNotifyFFQuery);
							}
							
							$edoNotifyMLOQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,life_st,org_notify_by,generate_time) 
							VALUES('$applicationId','$master_org',1,0,0,'$org_notify_by',NOW())";
							//$this->bm->dataInsertDB1($edoNotifyMLOQuery);
						}
						
					}
				}
			}
			else
			{
				//If there is already any application for given rotation & bl no...
				$data['msg']='<font color="red">Sorry! Already applied for Rotation- '.'<b>'.$rot_no.'</b>'.' and BL- '.'<b>'.$bl_no.'</b>'.'</font>';
			}
			
			$data['flag'] = "all"; //To show all do list
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDObyrotationBL',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function applicationForEDOList()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="Uploaded EDO List";
			$data['msg'] = "";
			$data['flag'] = "all"; //To show all do list
			$data['cpa_search'] = 0; 
			$data['searchBy']="";
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function pendingEDOapplication()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {			
			$data['searchBy']=$this->input->post('search_by');;
			$data['searchInput']=$this->input->post('searchInput');
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Pending Application List";
			$data['msg'] = "";	
			$data['flag'] = "pending"; //To show pending do list
			$data['cpa_search'] = 0;
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function applicationforEdoWithoutcnf()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title'] = "EDO Upload without C&F Application";
			$data['msg'] = "";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('applicationforEdoWithoutcnf',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function appliedforEdoWithoutcnf()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		$org_id = $this->session->userdata('org_id');
		$org_notify_by = $this->session->userdata('login_id');

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$rot_no = trim($this->input->post('rot_no'));
			$bl_no = trim($this->input->post('bl_no'));
			$ain = trim($this->input->post('ain_no'));
			$sup_org = "";
			$master_org = "";			
			$msg = "";
			$resInsert = 0;
			//$cnfId = $ain."CF";

			// Combination Test

			$CombineQuery = "SELECT COUNT(*) AS rtnValue 
				FROM igm_supplimentary_detail 
				WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";
			$CombineRslt = $this->bm->dataSelectDB1($CombineQuery);
			$combination = 0;
			
			for($x=0;$x<count($CombineRslt);$x++){
				$combination = $CombineRslt[$x]['rtnValue'];
			}

			if($combination == 0){
				$CombineQuery = "SELECT COUNT(*) AS rtnValue 
					FROM igm_details 
					WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";

				$CombineRslt = $this->bm->dataSelectDB1($CombineQuery);
				for($x=0;$x<count($CombineRslt);$x++){
					$combination = $CombineRslt[$x]['rtnValue'];
				}
			}

			if($combination == 0){
				$msg="<font color='red'><b>Wrong Combination of Reg and BL!</b></font>";
				$data['title'] = "EDO Upload without C&F Application";
				$data['msg'] = $msg;

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('applicationforEdoWithoutcnf',$data);
				$this->load->view('jsAssetsList');
				return;
			}

			// Submitee org id

			$orgQuery = "SELECT Submitee_Org_Id FROM igm_supplimentary_detail WHERE BL_No='$bl_no' AND Import_Rotation_No='$rot_no'";
			$orgRslt = $this->bm->dataSelectDB1($orgQuery);
			$Submitee_Org_Id = "";

			for($x=0;$x<count($orgRslt);$x++){
				$Submitee_Org_Id = $orgRslt[$x]['Submitee_Org_Id'];
			}

			if($Submitee_Org_Id == ""){
				$orgQuery = "SELECT Submitee_Org_Id FROM igm_details WHERE BL_No='$bl_no' AND Import_Rotation_No='$rot_no'";
				$orgRslt = $this->bm->dataSelectDB1($orgQuery);
				for($x=0;$x<count($orgRslt);$x++){
					$Submitee_Org_Id = $orgRslt[$x]['Submitee_Org_Id'];
				}
			}
			
			if($org_id == $Submitee_Org_Id)
			{
				$cnfLoginId = "";
				$loginIdQuery = "SELECT login_id FROM users
				INNER JOIN organization_profiles ON organization_profiles.id = users.org_id
				WHERE AIN_No_New='$ain' AND users.org_Type_id='2'";
				$result = $this->bm->dataSelectDB1($loginIdQuery);

				if(count($result) > 0)
				{
					for($i=0;$i<count($result);$i++){
						$cnfLoginId = $result[$i]['login_id'];
					}
				}
				else
				{
					$loginIdQuery = "SELECT login_id FROM users
					INNER JOIN organization_profiles ON organization_profiles.id = users.org_id
					WHERE AIN_No='$ain' AND users.org_Type_id='2'";
					$result = $this->bm->dataSelectDB1($loginIdQuery);
					if(count($result) > 0)
					{
						for($i=0;$i<count($result);$i++){
							$cnfLoginId = $result[$i]['login_id'];
						}
					}
				}

				if($cnfLoginId == "")
				{
					$msg = "<font color='red' size='3'>C&F not Found</font>";
					$data['flag'] = "all";
					$data['searchBy'] = "";
					$data['searchInput'] = "";
					$data['cpa_search'] = 0;
				}
				else
				{
					
					// Application Process starts
					
					$queryCntApplication="SELECT COUNT(*) AS rtnValue FROM edo_application_by_cf WHERE rotation='$rot_no' AND bl='$bl_no'  
											AND edo_application_by_cf.rejection_st = '0'";
					$cntApplication = $this->bm->dataReturnDb1($queryCntApplication);

					if($cntApplication==0)
					{
						//If there is no application for given rotation & bl no.....
						$type_of_igm = "";
						$blType_BB = "";
						$cnt_str="SELECT COUNT(*) as rtnValue FROM igm_details WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";
						$cntResult = $this->bm->dataReturnDb1($cnt_str);
						
						if($cntResult==0)
						{
							$cnt_str_sup="SELECT COUNT(*) AS rtnValue FROM igm_supplimentary_detail 
										WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";
							$cntSupResult = $this->bm->dataReturnDb1($cnt_str_sup);
							
							if($cntSupResult==0)
							{
								$msg="<font color='red' size='3'><b>Wrong Combination of Rotation and BL</b></font>";
							}
							else
							{
								$type_str_sup="SELECT igm_supplimentary_detail.type_of_igm AS rtnValue FROM igm_supplimentary_detail 
										WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";
								$type_of_igm = $this->bm->dataReturnDb1($type_str_sup);
								$blType_BB = "HB";
							}
						}
						else
						{
							$type_str="SELECT igm_details.type_of_igm as rtnValue FROM igm_details 
									WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";						
							$type_of_igm = $this->bm->dataReturnDb1($type_str);
							$blType_BB = "MB";
						}	
				
						
						if($type_of_igm!="")
						{
							$strInsert = "";
							if($type_of_igm=='BB')
							{	
								// "BB";
								$Submitee_Org_Id = "";
								$str="SELECT igm_details.Submitee_Org_Id
									FROM igm_details  
									WHERE igm_details.BL_No='$bl_no' AND igm_details.Import_Rotation_No='$rot_no'";
									
								$resltStr = $this->bm->dataSelectDB1($str);	

								for($i=0;$i<count($resltStr);$i++)
								{
									$Submitee_Org_Id=$resltStr[$i]['Submitee_Org_Id'];
								}					

								$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,sh_agent_org_id,entry_time,sumitted_by,
												ip_address,users,edo_applied_by) 
								VALUES('$rot_no','$bl_no','$blType_BB','$type_of_igm','$Submitee_Org_Id', NOW(),'$cnfLoginId',
												'$ip_address','$login_id','$login_id')";
							}
							else
							{
								$queryStr="SELECT igm_supplimentary_detail.Submitee_Org_Id AS sup_org,
								igm_details.Submitee_Org_Id AS master_org
								FROM igm_supplimentary_detail 
								INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
								WHERE igm_supplimentary_detail.BL_No='$bl_no' AND igm_supplimentary_detail.Import_Rotation_No='$rot_no'";
								$rsltStr = $this->bm->dataSelectDB1($queryStr);

								if(count($rsltStr)>0)
								{
									$bl_type = "HB";
									$sup_org = "";
									$master_org = "";
									$master_bl = "";
									$cont_status = "";
									$ff_stat = 0;
									$ff_clearance_time = "";
									$entry_org_id = "";
									$entered_by = "";
									$entry_time = "";
									$entry_ip_address = "";
									$cleared_by = "";
									$clearance_time = "";
									$clearance_ip = "";
									$cleared_by_org_id = "";
									$clearanceSt = "";
									$mbl_valid_upto_dt = "";

									for($i=0;$i<count($rsltStr);$i++)
									{
										$sup_org=$rsltStr[$i]['sup_org'];
										$master_org=$rsltStr[$i]['master_org'];
									}

									$strQry="select igm_supplimentary_detail.master_BL_No,igm_sup_detail_container.cont_status
									from igm_supplimentary_detail 
									INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
									where igm_supplimentary_detail.Import_Rotation_No='$rot_no' and igm_supplimentary_detail.BL_No='$bl_no'";
									//INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
									$rsltQry = $this->bm->dataSelectDB1($strQry);
									
									$fclStatus = 0;
									for($k=0;$k<count($rsltQry);$k++)
									{
										$cont_status=$rsltQry[$k]['cont_status'];
										$master_bl=$rsltQry[$k]['master_BL_No'];
										
										if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY"){
											$fclStatus = $fclStatus+1;
										}
									}
									
									if($fclStatus > 0){
										$cont_status = "FCL";
									} else {
										$cont_status = $cont_status;
									}
									
									$provided_org_type_id = "";
									$getOrgType = "SELECT org_Type_id FROM users WHERE login_id='$cnfLoginId'";
									$rsltOrgType = $this->bm->dataSelectDB1($getOrgType);
									for($orgType=0;$orgType<count($rsltOrgType);$orgType++){
										$provided_org_type_id=$rsltOrgType[$orgType]['org_Type_id'];
									}
									
									if($cont_status=="" or $cont_status==null or $master_bl=="" or $master_bl==null){
										$data['msg']='<font color="red"><b>Sorry! Could not apply EDO.</b></font>';
									} else if($provided_org_type_id !="2"){
										$data['msg']='<font color="red"><b>Sorry! Invalid C&F.</b></font>';
									} else {
										if($cont_status=="LCL")
										{
											//LCL
											$strChkMBLClearance="select * from cleared_mbl_by_mlo where master_bl='$master_bl'";
											$mblClearance = $this->bm->dataSelectDB1($strChkMBLClearance);
											
											$mblClearanceStatus = count($mblClearance);
											
											if($mblClearanceStatus==0)
											{
												$ff_stat = 0;
												
												$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,mlo,ff_org_id,ff_stat,ff_clearance_time,
																						cont_status,mbl_of_hbl, entry_time, sumitted_by,ip_address,edo_applied_by) 
													VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$sup_org','$ff_stat',
															'$ff_clearance_time','$cont_status','$master_bl' ,NOW(), '$cnfLoginId','$ip_address','$login_id')";
											}
											else
											{
												for($l=0;$l<$mblClearanceStatus;$l++)
												{
													$ff_clearance_time=$mblClearance[$l]['entry_time'];
													$clearanceSt=$mblClearance[$l]['clearance_st'];
													$entry_org_id=$mblClearance[$l]['entry_org_id'];
													$entered_by=$mblClearance[$l]['entered_by'];
													$entry_time=$mblClearance[$l]['entry_time'];
													$entry_ip_address=$mblClearance[$l]['entry_ip_address'];
													$cleared_by=$mblClearance[$l]['cleared_by'];
													$clearance_time=$mblClearance[$l]['clearance_time'];
													$clearance_ip=$mblClearance[$l]['clearance_ip'];
													$cleared_by_org_id=$mblClearance[$l]['cleared_by_org_id'];
													$mbl_valid_upto_dt=$mblClearance[$l]['valid_upto_dt'];
												}
												
												$ff_stat = 1;
												$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,mlo,ff_org_id,ff_stat,ff_clearance_time,
												forwarded_by,forwarded_org_id,cont_status,mbl_of_hbl,entry_time,sumitted_by,ip_address,edo_applied_by) 
												VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$sup_org','$ff_stat','$ff_clearance_time','$cleared_by','$cleared_by_org_id','$cont_status','$master_bl' ,NOW(), '$cnfLoginId','$ip_address','$login_id')";
											}
										}
										else if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY")
										{
											//FCL or FCL/PART or ETY
											$strChkMBLClearance="select * from cleared_mbl_by_mlo where master_bl='$master_bl'";
											$mblClearance = $this->bm->dataSelectDB1($strChkMBLClearance);
											if(count($mblClearance)==0)
											{
												$ff_stat = 0;									
												
												$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,mlo,ff_org_id,ff_stat,ff_clearance_time,
																								cont_status,mbl_of_hbl, entry_time, sumitted_by,ip_address,edo_applied_by) 
															VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$sup_org','$ff_stat',
																	'$ff_clearance_time','$cont_status','$master_bl' ,NOW(), '$cnfLoginId','$ip_address','$login_id')";
											}
											else
											{
												
												for($l=0;$l<count($mblClearance);$l++)
												{
													$ff_clearance_time=$mblClearance[$l]['entry_time'];
													$clearanceSt=$mblClearance[$l]['clearance_st'];
													$entry_org_id=$mblClearance[$l]['entry_org_id'];
													$entered_by=$mblClearance[$l]['entered_by'];
													$entry_time=$mblClearance[$l]['entry_time'];
													$entry_ip_address=$mblClearance[$l]['entry_ip_address'];
													$cleared_by=$mblClearance[$l]['cleared_by'];
													$clearance_time=$mblClearance[$l]['clearance_time'];
													$clearance_ip=$mblClearance[$l]['clearance_ip'];
													$cleared_by_org_id=$mblClearance[$l]['cleared_by_org_id'];
													$mbl_valid_upto_dt=$mblClearance[$l]['valid_upto_dt'];
												}
												
												$ff_stat = 1;
												$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,mlo,ff_org_id,ff_stat,ff_clearance_time,
												forwarded_by,forwarded_org_id,valid_upto_dt_by_mlo,cont_status,mbl_of_hbl,entry_time,
												sumitted_by,ip_address,edo_applied_by) 
												VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$sup_org','$ff_stat',
												'$ff_clearance_time','$cleared_by','$cleared_by_org_id','$mbl_valid_upto_dt','$cont_status','$master_bl',NOW(),
												'$cnfLoginId','$ip_address','$login_id')";
												
												
											}										
										
										}
										else{
											$msg="<font color='red'><b>Sorry! Could not apply for EDO.</b></font>";
										}
									}

									

								}
								else
								{
									$container_status = "";
									$queryContStatus = "SELECT igm_detail_container.cont_status
														FROM igm_detail_container
														INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
														WHERE igm_details.Import_Rotation_No='$rot_no' AND igm_details.BL_No='$bl_no'";
									$resContStatus = $this->bm->dataSelectDB1($queryContStatus);
									
									$container_status = "";
									$fclStatus = 0 ;
									for($f=0;$f<count($resContStatus);$f++)
									{
										
										
										$container_status=$resContStatus[$f]['cont_status'];
										
										if($container_status=="FCL" or $container_status=="FCL/PART" or $container_status=="ETY"){
											$fclStatus = $fclStatus+1;
										}
									}
									if($fclStatus > 0){
										$container_status = "FCL";
									} else {
										$container_status = $container_status;
									}
									
									//$container_status=$resContStatus[0]['cont_status'];
							
									$queryStr="SELECT igm_details.Submitee_Org_Id AS master_org	FROM igm_details 
									WHERE BL_No='$bl_no' AND Import_Rotation_No='$rot_no'";
									$rsltStr = $this->bm->dataSelectDB1($queryStr);
									$bl_type = "MB";
									$master_org = "";
									for($i=0;$i<count($rsltStr);$i++)
									{
										$master_org=$rsltStr[$i]['master_org'];
									}
									
									if($master_org=="" or $master_org==null){
										$data['msg']='<font color="red"><b>Sorry! Could not apply EDO.</b></font>';
									} else {
										$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,mlo,cont_status,entry_time,
														sumitted_by,ip_address,edo_applied_by) 
										VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$container_status',NOW(),
														'$cnfLoginId','$ip_address','$login_id')";
									}
									
									
								}					
							}
							
							$resInsert = $this->bm->dataInsertDB1($strInsert);
							
							if($resInsert==1)
							{
								$msg="<font color='blue'><b>Inserted Sucessfully.</b></font>";

								// Notifications starts Here 

								// Application ID
								if($type_of_igm != 'BB')
								{
									$applicationIdQuery = "SELECT id FROM edo_application_by_cf WHERE rotation = '$rot_no' AND bl = '$bl_no' AND rejection_st = 0";
									$applicationIdRslt = $this->bm->dataSelectDB1($applicationIdQuery);
									$applicationId = "";
									if(count($applicationIdRslt)>0)
									{
										$applicationId = $applicationIdRslt[0]['id'];
									}

									if($bl_type == "HB"){

										$edoNotifyFFQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time) 
										VALUES('$applicationId','$sup_org',1,0,'$org_notify_by',NOW())";
										$this->bm->dataInsertDB1($edoNotifyFFQuery);
									}
									
									$edoNotifyMLOQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,life_st,org_notify_by,generate_time)
									VALUES('$applicationId','$master_org',1,0,0,'$org_notify_by',NOW())";
									//$this->bm->dataInsertDB1($edoNotifyMLOQuery);
								}

								// Notifications ends Here

								// EDO Upload starts here 

								$rotNo = $rot_no;			
								$blno = $bl_no;

								$edoInfoQuery = "SELECT * FROM edo_application_by_cf WHERE rotation = '$rotNo' AND bl = '$blno' AND rejection_st = '0'";
								$edoInfoRslt = $this->bm->dataSelectDB1($edoInfoQuery);

								$edo_id = "";
								$type_of_bl = "";
								$igm_type = "";
								$sumitted_by = "";

								for($i=0;$i<count($edoInfoRslt);$i++)
								{
									$edo_id = $edoInfoRslt[$i]['id'];
									$type_of_bl = $edoInfoRslt[$i]['bl_type'];
									$igm_type = $edoInfoRslt[$i]['igm_type'];
									$sumitted_by = $edoInfoRslt[$i]['sumitted_by'];
								}

								$msgBLsearch = "";
								$msg = "";
								$data['edit'] = "";
								
								//////////////////////////
									$requested_valid_dt = "";
									$valid_dt_mlo = "";
									$contSt = "";
									$beNo = "";
									$beDate = "";
									$ofcCode = "";
									$cnf_vldty_appr_st = "";
									$do_upload_st = "";
									
									$sql_edoApplyInfo="SELECT * FROM edo_application_by_cf WHERE id='$edo_id'";
									
									$res_EDOApplyInfo=$this->bm->dataSelectDB1($sql_edoApplyInfo);
									for($a=0;$a<count($res_EDOApplyInfo);$a++)
										{
											$requested_valid_dt = $res_EDOApplyInfo[$a]['applied_valid_dt'];
											$valid_dt_mlo = $res_EDOApplyInfo[$a]['valid_upto_dt_by_mlo'];
											$contSt = $res_EDOApplyInfo[$a]['cont_status'];
											$beNo = $res_EDOApplyInfo[$a]['be_no'];
											$beDate = $res_EDOApplyInfo[$a]['be_date'];
											$ofcCode = $res_EDOApplyInfo[$a]['ofc_code'];
											$cnf_vldty_appr_st = $res_EDOApplyInfo[$a]['cnf_vldty_appr_st'];
											$do_upload_st = $res_EDOApplyInfo[$a]['do_upload_st'];
										}
									$data['cnf_vldty_appr_st']=$cnf_vldty_appr_st;
								/////////////////////////
								
								//Organization Info Starts........................
								if($type_of_bl=="HB" and $igm_type=="GM")
								{
									if(count($mblClearance)==0)
									{
										$msg="<font color='blue'><b>Inserted Sucessfully.</b></font>";
										$data['title'] = "EDO Upload without C&F Application";
										$data['msg'] = $msg;
										$data['flag'] = "all";
										$data['searchBy'] = "";
										$data['searchInput'] = "";
										$data['cpa_search'] = 0;

										$this->load->view('cssAssetsList');
										$this->load->view('headerTop');
										$this->load->view('sidebar');
										$this->load->view('applicationForEDOList',$data);
										$this->load->view('jsAssetsList');
										return;
									} 
									else
									{
										$orgInfo = "SELECT edo_application_by_cf.ff_org_id,organization_profiles.Organization_Name,
													organization_profiles.Address_1,organization_profiles.Address_2,
													organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
													organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
													FROM edo_application_by_cf
													INNER JOIN organization_profiles ON edo_application_by_cf.ff_org_id=organization_profiles.id
													WHERE edo_application_by_cf.id='$edo_id'";
									}		
								}
								else if($type_of_bl=="MB" and $igm_type=="GM")
								{
									$orgInfo = "SELECT edo_application_by_cf.mlo,organization_profiles.Organization_Name,
											organization_profiles.Address_1,organization_profiles.Address_2,
											organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
											organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
											FROM edo_application_by_cf
											INNER JOIN organization_profiles ON edo_application_by_cf.mlo=organization_profiles.id
											WHERE edo_application_by_cf.id='$edo_id'";
								}
								else if($igm_type=="BB")
								{
									$orgInfo = "SELECT edo_application_by_cf.sh_agent_org_id,organization_profiles.Organization_Name,
											organization_profiles.Address_1,organization_profiles.Address_2,
											organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
											organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
											FROM edo_application_by_cf
											INNER JOIN organization_profiles ON edo_application_by_cf.sh_agent_org_id=organization_profiles.id
											WHERE edo_application_by_cf.id='$edo_id'";
								}

								$resOrgInfo = $this->bm->dataSelectDB1($orgInfo);
								for($t=0;$t<count($resOrgInfo);$t++){
									$logo_pic = $resOrgInfo[$t]['logo'];
								}

								$data['logo_pic']=$logo_pic;
								$data['logo_pic']=1;
								//Organization Info Ends..........................
								
								if($blno=="all")
								{
									$sqlQuery="SELECT Bill_of_Entry_No,Bill_of_Entry_Date FROM igm_details WHERE Import_Rotation_No='$rotNo'";	
								}
								else
								{
									$sqlQuery="SELECT Bill_of_Entry_No,Bill_of_Entry_Date FROM igm_details WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
								}

								$reslt = $this->bm->dataSelectDB1($sqlQuery);
								
								
								$resltBE = "";

								if(count($reslt)==0)
								{
									$sqlQuery="SELECT Bill_of_Entry_No,Bill_of_Entry_Date FROM igm_supplimentary_detail WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
									$reslt = $this->bm->dataSelectDB1($sqlQuery);
								}
								
								
								if(count($reslt)>0){
									$resltBE = $reslt[0]['Bill_of_Entry_No'];
								}
								
								$msgBLsearch = "";
								
								$queryContList = "";

								if($type_of_bl=="MB")
								{
									//echo "master ";
									$queryContList="SELECT igm_detail_container.id AS cId,cont_number, cont_status, cont_location_code, cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages
													FROM igm_detail_container 
													INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
													WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blno'";
								}
								else if($type_of_bl=="HB")
								{
									//echo "ff ";
									$queryContList="SELECT igm_sup_detail_container.id AS cId,cont_number, cont_status, cont_location_code, cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages
													FROM igm_sup_detail_container 
													INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
													WHERE  igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno'";
								}
								$contList=$this->bm->dataSelectDB1($queryContList);
								$data['contList']=$contList;
								
								$query="SELECT DISTINCT igm_details.id AS dtl_id,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,weight,Bill_of_Entry_No,
										igm_details.Bill_of_Entry_Date,igm_details.office_code,
										No_of_Pack_Delivered,DG_status,type_of_igm,net_weight,weight_unit,net_weight_unit,igm_details.Consignee_name,Consignee_address,
										Description_of_Goods,igm_details.Volume_in_cubic_meters,
										igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
										igm_masters.Net_Tonnage,Notify_name,Notify_address,port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
										igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
										igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
										igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
										igm_masters.imo AS imo, reg_no,dec_code
										FROM igm_masters
										INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
										LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
										LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
										LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
										LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
										WHERE igm_details.Import_Rotation_No='$rotNo' AND BL_No='$blno' ORDER BY file_clearence_date DESC";
								$doInfo=$this->bm->dataSelectDB1($query);
								
								//---
								if(count($doInfo)==0)
								{
									$query="SELECT DISTINCT igm_supplimentary_detail.id AS dtl_id,igm_supplimentary_detail.BL_No,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.weight,igm_supplimentary_detail.Bill_of_Entry_No,
									igm_supplimentary_detail.Bill_of_Entry_Date,igm_supplimentary_detail.office_code,
									igm_supplimentary_detail.No_of_Pack_Delivered,igm_supplimentary_detail.DG_status,igm_supplimentary_detail.type_of_igm,igm_supplimentary_detail.net_weight,igm_supplimentary_detail.weight_unit,igm_supplimentary_detail.net_weight_unit,igm_supplimentary_detail.Consignee_name,igm_supplimentary_detail.Consignee_address,
									igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Volume_in_cubic_meters,
									igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
									igm_masters.Net_Tonnage,igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
									igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
									igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
									igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
									igm_masters.imo AS imo,reg_no,dec_code
									FROM igm_masters
									INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
									INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
									LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
									LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
									LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
									LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
									WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno' ORDER BY file_clearence_date DESC";
									$doInfo=$this->bm->dataSelectDB1($query);
								}
								//---
								
								$dtl_id = "";
								$Notify_name = "";
								$Notify_address = "";
								$Vessel_Name = "";
								$Voy_No = "";
								$Import_Rotation_No = "";
								$Bill_of_Entry_No = "";
								$Bill_of_Entry_Date = "";
								$office_code = "";
								$Submission_Date = "";
								$port_of_origin = "";
								$Port_of_Shipment = "";
								$Port_of_Destination = "";
								$Consignee_name = "";
								$Consignee_address = "";
								$Description_of_Goods = "";
								$Pack_Description = "";
								$Pack_Marks_Number = "";
								$weight = "";
								$weight_unit = "";
								$Volume_in_cubic_meters = "";
								$igm_pack_number = "";
								for($j=0;$j<count($doInfo);$j++)
								{
									$dtl_id = $doInfo[$j]['dtl_id'];
									$Notify_name = $doInfo[$j]['Notify_name'];
									$Notify_address = $doInfo[$j]['Notify_address'];
									$Vessel_Name = $doInfo[$j]['Vessel_Name'];
									$Voy_No = $doInfo[$j]['Voy_No'];
									$Import_Rotation_No = $doInfo[$j]['Import_Rotation_No'];
									$Bill_of_Entry_No = $doInfo[$j]['Bill_of_Entry_No'];
									$Bill_of_Entry_Date = $doInfo[$j]['Bill_of_Entry_Date'];
									$Submission_Date = $doInfo[$j]['Submission_Date'];
									$port_of_origin = $doInfo[$j]['port_of_origin'];
									$Port_of_Shipment = $doInfo[$j]['Port_of_Shipment'];
									$Port_of_Destination = $doInfo[$j]['Port_of_Destination'];
									$Consignee_name = $doInfo[$j]['Consignee_name'];
									$Consignee_address = $doInfo[$j]['Consignee_address'];
									$Description_of_Goods = $doInfo[$j]['Description_of_Goods'];
									$Pack_Description = $doInfo[$j]['Pack_Description'];
									$Pack_Marks_Number = $doInfo[$j]['Pack_Marks_Number'];
									$weight = $doInfo[$j]['weight'];
									$weight_unit = $doInfo[$j]['weight_unit'];
									$office_code = $doInfo[$j]['office_code'];
									$Volume_in_cubic_meters = $doInfo[$j]['Volume_in_cubic_meters'];
									$igm_pack_number = $doInfo[$j]['Pack_Number'];
								}

								$data['dtl_id']=$dtl_id;
								$data['Notify_name']=$Notify_name;
								$data['Notify_address']=$Notify_address;
								$data['Vessel_Name']=$Vessel_Name;
								$data['Voy_No']=$Voy_No;
								$data['Import_Rotation_No']=$Import_Rotation_No;
								$data['Bill_of_Entry_No']=$Bill_of_Entry_No;
								$data['Bill_of_Entry_Date']=$Bill_of_Entry_Date;
								$data['Submission_Date']=$Submission_Date;
								$data['port_of_origin']=$port_of_origin;
								$data['Port_of_Shipment']=$Port_of_Shipment;
								$data['Port_of_Destination']=$Port_of_Destination;
								$data['Consignee_name']=$Consignee_name;
								$data['Consignee_address']=$Consignee_address;
								$data['Description_of_Goods']=$Description_of_Goods;
								$data['Pack_Description']=$Pack_Description;
								$data['Pack_Marks_Number']=$Pack_Marks_Number;
								$data['weight']=$weight;
								$data['weight_unit']=$weight_unit;
								$data['office_code']=$office_code;
								$data['Volume_in_cubic_meters']=$Volume_in_cubic_meters;
								$data['igm_pack_number']=$igm_pack_number;
							
								$data['doInfo']=$doInfo;
								$dec_code = "";
								$Notify_name = "";
								$Notify_address = "";
								$Vessel_Name = "";
								$Voy_No = "";
								$Bill_of_Entry_No = "";
								$Submission_Date = "";
								$port_of_origin = "";

								for($j=0;$j<count($doInfo);$j++)
								{
									$dec_code = $doInfo[$j]['dec_code'];
									$Notify_name = $doInfo[$j]['Notify_name'];
									$Notify_address = $doInfo[$j]['Notify_address'];
									$Vessel_Name = $doInfo[$j]['Vessel_Name'];
									$Voy_No = $doInfo[$j]['Voy_No'];
									$Bill_of_Entry_No = $doInfo[$j]['Bill_of_Entry_No'];
									$Submission_Date = $doInfo[$j]['Submission_Date'];
									$port_of_origin = $doInfo[$j]['port_of_origin'];
								}

								$data['Notify_name']=$Notify_name;
								$data['Notify_address']=$Notify_address;
								$data['Vessel_Name']=$Vessel_Name;
								$data['Voy_No']=$Voy_No;
								$data['Bill_of_Entry_No']=$Bill_of_Entry_No;
								$data['Submission_Date']=$Submission_Date;
								$data['port_of_origin']=$port_of_origin;
								
								/////////////////////
								$cnfName = "";
								$cnfLicenseNo = "";

								$sql_CNFData="SELECT u_name,org_id,organization_profiles.License_No
											FROM users
											INNER JOIN organization_profiles ON users.org_id=organization_profiles.id
											WHERE users.login_id='$sumitted_by'";

								$res_CNFData=$this->bm->dataSelectDB1($sql_CNFData);

								for($k=0;$k<count($res_CNFData);$k++)
								{
									$cnfName = $res_CNFData[$k]['u_name'];
									$cnfLicenseNo = $res_CNFData[$k]['License_No'];
								}

								$data['cnfName']=$cnfName;
								$data['cnfLicenseNo']=$cnfLicenseNo;
								/////////////////
								
								//////////////////////////
								$requested_valid_dt = "";
								$valid_dt_mlo = "";
								$contSt = "";
								$beNo = "";
								$beDate = "";
								$ofcCode = "";
								$sql_edoApplyInfo="SELECT * FROM edo_application_by_cf WHERE id='$edo_id'";
								$res_EDOApplyInfo=$this->bm->dataSelectDB1($sql_edoApplyInfo);

								for($a=0;$a<count($res_EDOApplyInfo);$a++)
								{
									$requested_valid_dt = $res_EDOApplyInfo[$a]['applied_valid_dt'];
									$valid_dt_mlo = $res_EDOApplyInfo[$a]['valid_upto_dt_by_mlo'];
									$contSt = $res_EDOApplyInfo[$a]['cont_status'];
									$beNo = $res_EDOApplyInfo[$a]['be_no'];
									$beDate = $res_EDOApplyInfo[$a]['be_date'];
									$ofcCode = $res_EDOApplyInfo[$a]['ofc_code'];
								}

								/////////////////////////
								
								$cnfCode2 = substr($dec_code, 5, 4);
								$cnfCode1 = substr($dec_code, 3, 2);
								$cnfLic = $cnfCode2."/".$cnfCode1;
								
								$sql_CNFName="SELECT id,name FROM ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";

								$cnf_name=$this->bm->dataSelect($sql_CNFName);
								$data['cnf_name']=$cnf_name;
								
								$queryRemainingQty = "SELECT IFNULL(gross_quantity,0),IFNULL(SUM(delv_quantity),0) AS total_delivered,
													(IFNULL(gross_quantity,0)-IFNULL(SUM(delv_quantity),0)) AS remaining
													FROM shed_mlo_do_info
													WHERE shed_mlo_do_info.imp_rot='$rotNo' AND shed_mlo_do_info.bl_no='$blno'";

								$remainingQty=$this->bm->dataSelectDB1($queryRemainingQty);
								$data['remainingQty']=$remainingQty;
								$data['cnfLic']=$cnfLic;
								
								$data['reslt']=$reslt;
								$data['resltBE']=$resltBE;
								
								$data['frmType']="search";
								$data['title']="Application for EDO";
								$data['msg']=$msg;
								$data['msgBLsearch']=$msgBLsearch;
								$data['blno']=$blno;
								$data['rotNo']=$rotNo;
								$data['type_of_bl']=$type_of_bl;
								$data['igm_type']=$igm_type;
								$data['edo_id']=$edo_id;
								$data['requested_valid_dt']=$requested_valid_dt;
								$data['valid_dt_mlo']=$valid_dt_mlo;
								$data['contSt']=$contSt;
								$data['beNo']=$beNo;
								$data['beDate']=$beDate;
								$data['ofcCode']=$ofcCode;
								$data['edit'] = "";
								
								$this->load->view('cssAssets');
								$this->load->view('headerTop');
								$this->load->view('sidebar');
								$this->load->view('ShedDOForm',$data);
								$this->load->view('jsAssets');
								
								return;
							}
						}
					}
					else
					{
						//If there is already any application for given rotation & bl no...
						$msg="<font color='red'>Sorry! Already applied for Rotation- "."<b>".$rot_no."</b>"." and BL- "."<b>".$bl_no."</b>"."</font>";
					}

				}
			}
			else
			{
				$msg = "<font color='red' size='3'>You can't upload EDO for Rotation- "."<b>".$rot_no."</b>"." and BL- "."<b>".$bl_no."</b>"."</font>";
			}
			

			$data['title'] = "EDO Upload without C&F Application";
			$data['msg'] = $msg;
			$data['flag'] = "all";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function tokenDistributionList()
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
			$org_id =$this->session->userdata('org_id');
			
			$queryFFList="SELECT * FROM organization_profiles WHERE Org_Type_id='4' ORDER BY id DESC";
			$ffList = $this->bm->dataSelectDB1($queryFFList);
			if($org_Type_id=="73")
			{
				$queryTokenList="SELECT id,ff_ain,ff_name,token_number,transaction_id,used_st,edo_id FROM token_distribution ORDER BY id DESC LIMIT 1000";
				$tokenList = $this->bm->dataSelectDB1($queryTokenList);
			}
			else
			{
				$queryFFAINno="SELECT AIN_No_New AS rtnValue FROM organization_profiles WHERE id='$org_id'";
				$resFFAINno = $this->bm->dataReturnDb1($queryFFAINno);
				
				$queryTokenList="SELECT id,ff_ain,ff_name,token_number,transaction_id,used_st,edo_id FROM token_distribution WHERE ff_ain='$resFFAINno'";
				$tokenList = $this->bm->dataSelectDB1($queryTokenList);
			}
			// echo $queryTokenList;return;
			
			$data['title']="Token Distribution List";
			$msg = "";
			$data['msg'] = $msg;
			$data['org_Type_id'] = $org_Type_id;
			$data['frmType'] = "new";
			$data['ffList'] = $ffList;
			$data['tokenList'] = $tokenList;
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('tokenDistributionList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function tokenDistributionForm($param)
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="Token Distribution";
			
			if($param=="0"){
				$data['msg'] = "<font color='red'><b>Sorry! Something went wrong.</b></font>";				
			} else if($param=="1"){
				$data['msg'] = "<font color='blue'><b>Data Saved<b></font>";
			} else {
				$data['msg'] = "";
			}
			
			$data['frmType'] = "new";
						
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('tokenDistribution',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function tokenDistributionEntry()
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
			$ffLicNo = $this->input->post('ffLicNo');
			$ffName = $this->input->post('ffName');
			$tokenQty = $this->input->post('tokenQty');
			$msg = "";
			
			$insertTokenTransaction="INSERT INTO token_distribution_transaction(ff_ain,ff_name,token_quantity,entered_by,entered_at) 
				VALUES ('$ffLicNo','$ffName','$tokenQty','$login_id',NOW())";
			$resTokenTransaction = $this->bm->dataInsertDB1($insertTokenTransaction);
			$strTransactionId = "SELECT id AS rtnValue FROM token_distribution_transaction ORDER BY id DESC LIMIT 1";
			$transaction_id=$this->bm->dataReturnDb1($strTransactionId);
				
			for($i=1;$i<=$tokenQty;$i++)
			{
				$insertQuery="INSERT INTO token_distribution(ff_ain,ff_name,entered_by,entry_time) 
					VALUES ('$ffLicNo','$ffName','$login_id',NOW())";
				$resInsert = $this->bm->dataInsertDB1($insertQuery);
				
				$token_number = "";
				if($resInsert)
				{
					//New token number generation starts...
					$cntTokenByFF = 0;
					$sqlCntTokenByFF="SELECT COUNT(*) AS rtnValue FROM token_distribution WHERE ff_ain='$ffLicNo'";
					$cntTokenByFF = $this->bm->dataReturnDb1($sqlCntTokenByFF);
					if($cntTokenByFF==1){
						$token_number = $ffLicNo."-1";
					} else {						
						$strLastTokenNumber = "";
						$lastTokenNumber = "";
						
						$strLastTokenNumber = "SELECT token_number AS rtnValue FROM token_distribution WHERE ff_ain='$ffLicNo' ORDER BY id DESC LIMIT 1,1";
						$lastTokenNumber=$this->bm->dataReturnDb1($strLastTokenNumber);
						
						$lastTokenSerial = explode("-",$lastTokenNumber);	
						// print_r($lastTokenSerial);
						// echo "<br>";
						$newTokenSerial = $lastTokenSerial[1]+1;
						$token_number = $ffLicNo."-".$newTokenSerial;
					}
					//New token number generation ends...
					
					$strTokenId = "SELECT id AS rtnValue FROM token_distribution ORDER BY id DESC LIMIT 1";
					$resTokenId=$this->bm->dataReturnDb1($strTokenId);
					//$token_number = $ffLicNo."-".$resTokenId;
					
					$updateTokenNumber = "Update token_distribution set token_number='$token_number',transaction_id='$transaction_id' 
											WHERE id='$resTokenId'";
					$resTokenNumber = $this->bm->dataUpdateDB1($updateTokenNumber);
					if($resTokenNumber)
					{
						$param = "1"; //Data Saved
					}
					else
					{
						$param = "0"; //Sorry! Something went wrong
					}
				}
				else
				{
					$param = "0"; //Sorry! Something went wrong
				}
			}
			redirect('/EDOController/tokenDistributionForm/'.$param, 'refresh');
		}
	}
	
	function tokenDistributionSearch()
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
			$org_id =$this->session->userdata('org_id');
			if($this->uri->segment(3))
			{
				$search_criteria = $this->uri->segment(3);
			}
			else
			{
				$search_criteria = $this->input->post('search_criteria');
			}
			$ffCondition = "";
			$searchCondition = "";
			$searchByFF = "";
			$msg = "";
			if($org_Type_id=="73")
			{
				$ff_ain = $this->input->post('ff_ain');				
				if($ff_ain=="all")
				{
					if($search_criteria=="all")
					{
						$searchCondition = " ";
					}
					else if($search_criteria=="used")
					{
						$searchCondition = "WHERE used_st=1";
					}
					else if($search_criteria=="balance")
					{
						$searchCondition = "WHERE used_st=0";
					}
					
					$ffCondition = "";
				}
				else
				{
					if($search_criteria=="all")
					{
						$searchCondition = " WHERE (used_st=1 OR used_st=0)";
					}
					else if($search_criteria=="used")
					{
						$searchCondition = "WHERE used_st=1";
					}
					else if($search_criteria=="balance")
					{
						$searchCondition = "WHERE used_st=0";
					}
					
					$ffCondition = " AND ff_ain='$ff_ain'";
					$querySearchByFF="SELECT * FROM organization_profiles WHERE AIN_No_New='$ff_ain' AND Org_Type_id='4'";
					$searchByFF = $this->bm->dataSelectDB1($querySearchByFF);
					$data['searchByFF'] = $searchByFF;
				}
				$data['ff_ain'] = $ff_ain;
				
			}
			else
			{
				if($search_criteria=="all")
				{
					$searchCondition = " WHERE (used_st=1 OR used_st=0)";
				}
				else if($search_criteria=="used")
				{
					$searchCondition = "WHERE used_st=1";
				}
				else if($search_criteria=="balance")
				{
					$searchCondition = "WHERE used_st=0";
				}
			
				$strAin="SELECT AIN_No_New AS rtnValue FROM organization_profiles WHERE id='$org_id'";
				$ff_ain = $this->bm->dataReturnDb1($strAin);
				$ffCondition = " AND ff_ain='$ff_ain'";
			}
						
			
			
			$queryTokenList="SELECT id,ff_ain,ff_name,token_number,transaction_id,used_st,edo_id 
							FROM token_distribution ".$searchCondition.$ffCondition;			
			$tokenList = $this->bm->dataSelectDB1($queryTokenList);
			///Redirect
			$queryFFList="SELECT * FROM organization_profiles WHERE Org_Type_id='4' ORDER BY id DESC";
			$ffList = $this->bm->dataSelectDB1($queryFFList);
			
			
			
			$data['title']="Token Distribution List";
			$msg = "";
			$data['msg'] = $msg;
			$data['org_Type_id'] = $org_Type_id;
			$data['search_criteria'] = $search_criteria;
			$data['frmType'] = "search";
			$data['ffList'] = $ffList;
			$data['tokenList'] = $tokenList;
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('tokenDistributionList',$data);
			$this->load->view('jsAssetsList');
			
		}
	}
	
	function dateWiseTokenDist()
	{

		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');			
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			
			$data['title']="Date Wise Token Distribution Form";
			$data['flag']="new";
			$data['msg']="";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('DateWiseTokenDistributionList',$data);
			$this->load->view('jsAssetsList');

		}
	}
	
	function dateTokenDistributionFormAction () {
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');			
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{				
			$fromDate = $this->input->post('fromdate');
			$toDate = $this->input->post('todate');

			// $qryTokenCount = "SELECT ff_ain,ff_name,COUNT(ff_ain) AS Quantity FROM token_distribution
			// WHERE DATE(entry_time) BETWEEN '$fromDate' AND '$toDate'
			// GROUP BY ff_ain";
			
			$qryTokenCount = "(SELECT id,ff_ain,ff_name,token_quantity AS Quantity,'token_distribution_transaction' AS tbl_name
						FROM token_distribution_transaction 
						WHERE DATE(entered_at) BETWEEN '$fromDate' AND '$toDate')
						UNION ALL
						(SELECT id,ff_ain,ff_name,COUNT(ff_ain) AS Quantity,'token_distribution' AS tbl_name 
						FROM token_distribution
						WHERE (DATE(entry_time) BETWEEN '$fromDate' AND '$toDate') AND (transaction_id IS NULL)
						GROUP BY ff_ain)";	
			$rsltTokenCount = $this->bm->dataSelectDB1($qryTokenCount);
			if($this->input->post('pdfView'))
			{
				$this->load->library('m_pdf');
				$this->data['fromDate']=$fromDate;
				$this->data['toDate']=$toDate;
				$this->data['rsltTokenCount']=$rsltTokenCount;
				
				$html=$this->load->view('DateWiseTokenDistributionPDF',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
				$pdfFilePath ="tokenDistribution-".time()."-download.pdf";
				$pdf = $this->m_pdf->load();
				$pdf->allow_charset_conversion = true;
				$pdf->charset_in = 'iso-8859-4';
				$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css	
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			}
			else
			{
				$data['title']="Date Wise Token Distribution Form";
				$data['rsltTokenCount'] = $rsltTokenCount;
				$data['fromDate'] = $fromDate;
				$data['toDate'] = $toDate;
				$data['flag']="search";
				$data['msg']="";
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('DateWiseTokenDistributionList',$data);
				$this->load->view('jsAssetsList');
			}
		}
	}
	
	function organizationWiseTokenReport()
	{

		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');			
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			
			$data['title']="Organization Wise Token Report";
			$data['flag']="new";
			$data['msg']="";
			
			$qryOrgList = "SELECT Organization_Name,AIN_No,AIN_No_New,
							(SELECT COUNT(*) FROM token_distribution WHERE token_distribution.ff_ain=organization_profiles.AIN_No_New) 
							AS total_token,
							(SELECT COUNT(*) FROM token_distribution WHERE token_distribution.ff_ain=organization_profiles.AIN_No_New 
							AND token_distribution.used_st='1') AS total_used,
							(SELECT COUNT(*) FROM token_distribution WHERE token_distribution.ff_ain=organization_profiles.AIN_No_New 
							AND token_distribution.used_st='0') AS total_pending 
							FROM organization_profiles WHERE organization_profiles.Org_Type_id='4'";	
			$org_list = $this->bm->dataSelectDB1($qryOrgList);
			$data['org_list'] = $org_list;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('organizationWiseTokenReport',$data);
			$this->load->view('jsAssetsList');

		}
	}
	
	function changeCNFForEDO()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$org_id=$this->session->userdata('org_Type_id');
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['title']="Change C&F for EDO";
			$data['msg'] = "";
			$data['frmType'] = "new"; //To show all do list
			$data['org_Type_id'] =$this->session->userdata('org_Type_id');
			$login_id =$this->session->userdata('login_id');
			$ip_address = $_SERVER['REMOTE_ADDR'];
			
			$saveFlag = 0;
						
			if($this->input->post("save"))
			{
				$edo_number = trim($this->input->post("edo_number"));
				$current_ain = trim($this->input->post("current_cnf"));
				$correct_cnf = trim($this->input->post("correct_cnf"));
				$corrct_cnf_login_id = $correct_cnf."CF";
				$edo_id = "";
				
				$queryCntAin="SELECT COUNT(*) AS rtnValue FROM organization_profiles WHERE AIN_No_New='$correct_cnf'";
				$cntAin = $this->bm->dataReturnDb1($queryCntAin);
				if($cntAin == 0)
				{					
					$queryCntAin="SELECT COUNT(*) AS rtnValue FROM organization_profiles WHERE AIN_No='$correct_cnf'";
					$cntAin = $this->bm->dataReturnDb1($queryCntAin);
					if($cntAin == 0)
					{
						$saveFlag = 0;
						$data['msg']='<font color="red"><b>Sorry! Invalid AIN No Provided.</b></font>';
					} 
					else 
					{
						$queryCntLoginID="SELECT COUNT(*) AS rtnValue FROM users WHERE login_id='$corrct_cnf_login_id'";
						$cntLoginID = $this->bm->dataReturnDb1($queryCntLoginID);
						if($cntLoginID == 0)
							{
								$saveFlag = 0;
								$data['msg']='<font color="red"><b>Sorry! Could not change AIN.</b></font>';
							} 
						else 
							{
								
								$strEdoId="SELECT edo_application_by_cf.id AS edo_id FROM shed_mlo_do_info 
									INNER JOIN edo_application_by_cf ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
									WHERE CONCAT(edo_mlo,LPAD(edo_sl,6,0),edo_year)='$edo_number'";
									
								$resltEdoId = $this->bm->dataSelectDB1($strEdoId);					
								for($i=0;$i<count($resltEdoId);$i++)
								{
									$edo_id=$resltEdoId[$i]['edo_id'];
								}
								
								$queryUpdate = "UPDATE edo_application_by_cf SET sumitted_by='$corrct_cnf_login_id' WHERE id='$edo_id'";
								$update_st=$this->bm->dataUpdateDB1($queryUpdate);
								if($update_st==1)
								{
									$queryUpdateNtsSt = "UPDATE shed_mlo_do_info SET nts_send_st='0' WHERE edo_id='$edo_id'";
									$update_nts_st=$this->bm->dataUpdateDB1($queryUpdateNtsSt);
									
									$strInsert = "INSERT INTO cnf_chane_for_edo_log(edo_id,previous_ain,new_ain,changed_by,changed_at,ip_address) 
									VALUES('$edo_id','$current_ain','$correct_cnf','$login_id',NOW(),'$ip_address')";
									$resInsert = $this->bm->dataInsertDB1($strInsert);
									
									$saveFlag = 1;
									$data['msg']='<font color="blue"><b>Changed AIN Successully.</b></font>';
								}
							}
					}
				} 
				else 
				{
					
					$queryCntLoginID="SELECT COUNT(*) AS rtnValue FROM users WHERE login_id='$corrct_cnf_login_id'";
					$cntLoginID = $this->bm->dataReturnDb1($queryCntLoginID);
					if($cntLoginID == 0)
						{
							$saveFlag = 0;
							$data['msg']='<font color="red"><b>Sorry! Could not change AIN.</b></font>';
						} 
					else 
						{
							
							$strEdoId="SELECT edo_application_by_cf.id AS edo_id FROM shed_mlo_do_info 
								INNER JOIN edo_application_by_cf ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
								WHERE CONCAT(edo_mlo,LPAD(edo_sl,6,0),edo_year)='$edo_number'";
								
							$resltEdoId = $this->bm->dataSelectDB1($strEdoId);					
							for($i=0;$i<count($resltEdoId);$i++)
							{
								$edo_id=$resltEdoId[$i]['edo_id'];
							}
							
							$queryUpdate = "UPDATE edo_application_by_cf SET sumitted_by='$corrct_cnf_login_id' WHERE id='$edo_id'";
							$update_st=$this->bm->dataUpdateDB1($queryUpdate);
							if($update_st==1)
							{
								$queryUpdateNtsSt = "UPDATE shed_mlo_do_info SET nts_send_st='0' WHERE edo_id='$edo_id'";
								$update_nts_st=$this->bm->dataUpdateDB1($queryUpdateNtsSt);
									
								$strInsert = "INSERT INTO cnf_chane_for_edo_log(edo_id,previous_ain,new_ain,changed_by,changed_at,ip_address) 
								VALUES('$edo_id','$current_ain','$correct_cnf','$login_id',NOW(),'$ip_address')";
								$resInsert = $this->bm->dataInsertDB1($strInsert);
								
								$saveFlag = 1;
								$data['msg']='<font color="blue"><b>Changed AIN Successully.</b></font>';
							}
						}
					
				}

			}
			
			if($saveFlag == 1){
				$blno = "";
				$rotNo = "";
				$shedMloDo = "";
				$type_of_bl = "";
				
				$strEdoInfo="SELECT id,imp_rot,bl_no,bl_type FROM shed_mlo_do_info WHERE CONCAT(edo_mlo,LPAD(edo_sl,6,0),edo_year)='$edo_number'";				
				$resltEdoInfo = $this->bm->dataSelectDB1($strEdoInfo);	
				
				for($e=0;$e<count($resltEdoInfo);$e++)
				{
					$blno=$resltEdoInfo[$e]['bl_no'];
					$rotNo=$resltEdoInfo[$e]['imp_rot'];
					$shedMloDo=$resltEdoInfo[$e]['id'];
					$type_of_bl=$resltEdoInfo[$e]['bl_type'];
				}
				$rotNo = str_replace("/","_",$rotNo);
				redirect('EDOController/eDOPDF/'.$blno."/".$rotNo."/".$shedMloDo."/".$type_of_bl."/".$corrct_cnf_login_id);
			}			
			else {
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('cnfChangeForEDO',$data);
				$this->load->view('jsAssetsList');
			}
		}
	}
	
	function eDOPDF()
	{	
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			
			if($this->uri->segment(3)){		
				$blno = str_replace("_","/",$this->uri->segment(3));
				$rotNo = str_replace("_","/",$this->uri->segment(4));
				$shedMloDo = $this->uri->segment(5);
				$type_of_bl = $this->uri->segment(6);
				$sumitted_by = $this->uri->segment(7);
			}
			else{
				$shedMloDo = $this->input->post('shedMloDo');		
				$rotNo = $this->input->post('rotNo');			
				$blno = $this->input->post('blno');
				$type_of_bl = $this->input->post('bl_type');
				$sumitted_by = $this->input->post('sumitted_by');
			}

			// echo Submission_Date;
			
			$msg = "";
			$resltBE = "";
			
			$edoAppliedTime = "";
			$edoForwardingTime = "";
			$edoIGMType = "";
						
			$sqlEdoApplicationInfo="SELECT * FROM edo_application_by_cf WHERE rotation='$rotNo' AND bl='$blno'";			
			$resEdoApplicationInfo = $this->bm->dataSelectDB1($sqlEdoApplicationInfo);
						
			for($edo=0;$edo<count($resEdoApplicationInfo);$edo++){
				$edoAppliedTime = $resEdoApplicationInfo[$edo]['entry_time'];
				$edoForwardingTime = $resEdoApplicationInfo[$edo]['ff_clearance_time'];
				$edoIGMType = $resEdoApplicationInfo[$edo]['igm_type'];
				
			}
			
			$this->data['edoAppliedTime']=$edoAppliedTime;
			$this->data['edoForwardingTime']=$edoForwardingTime;
			$this->data['edoIGMType']=$edoIGMType;
					
			
			$sqlQuery="SELECT Bill_of_Entry_No FROM igm_details WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";			
			$reslt = $this->bm->dataSelectDB1($sqlQuery);
			for($i=0;$i<count($reslt);$i++){
				$resltBE = $reslt[$i]['Bill_of_Entry_No'];
			}
			
			$queryContList = "";

			if($type_of_bl=="MB")
			{
				$queryContList="SELECT cont_number,cont_seal_number, cont_status, cont_location_code, cont_size,cont_type,cont_height,
								Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages,do_upload_wise_container.valid_upto_date
								FROM igm_detail_container 
								INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
								INNER JOIN do_upload_wise_container ON do_upload_wise_container.cont_igm_id=igm_detail_container.id
								WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blno'
								AND do_upload_wise_container.shed_mlo_do_info_id='$shedMloDo'";
			}
			else if($type_of_bl=="HB")
			{
				$queryContList="SELECT cont_number,cont_seal_number,cont_status, cont_location_code, cont_size,cont_type,cont_height,
								Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages,do_upload_wise_container.valid_upto_date
								FROM igm_sup_detail_container 
								INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
								INNER JOIN do_upload_wise_container ON do_upload_wise_container.cont_igm_id=igm_sup_detail_container.id
								WHERE  igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno'
								AND do_upload_wise_container.shed_mlo_do_info_id='$shedMloDo'";
			}
			
			$contList=$this->bm->dataSelectDB1($queryContList);
			$this->data['contList']=$contList;
			
				$query="SELECT DISTINCT igm_details.id AS dtl_id,igm_supplimentary_detail.BL_No,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,
					igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.weight,igm_supplimentary_detail.Bill_of_Entry_No,
					igm_supplimentary_detail.No_of_Pack_Delivered,igm_supplimentary_detail.DG_status,igm_supplimentary_detail.type_of_igm,igm_supplimentary_detail.net_weight,igm_supplimentary_detail.weight_unit,igm_supplimentary_detail.net_weight_unit,igm_supplimentary_detail.Consignee_name,igm_supplimentary_detail.Consignee_address,
					igm_supplimentary_detail.Description_of_Goods,
					igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
					igm_masters.Net_Tonnage,igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.port_of_origin,Port_of_Shipment,
					igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
					igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
					igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
					igm_masters.imo AS imo,reg_no,dec_code,igm_details.Exporter_name,igm_details.Exporter_address
					FROM igm_masters
					INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
					LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
					LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
					LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
					LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
					WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno' ORDER BY file_clearence_date DESC";
					
				$doInfo=$this->bm->dataSelectDB1($query);
			//---
				if(count($doInfo)==0)
				{
					$query="SELECT DISTINCT igm_details.id AS dtl_id,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,weight,Bill_of_Entry_No,
						No_of_Pack_Delivered,DG_status,type_of_igm,net_weight,weight_unit,net_weight_unit,igm_details.Consignee_name,Consignee_address,
						Description_of_Goods,
						igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
						igm_masters.Net_Tonnage,Notify_name,Notify_address,port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
						igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
						igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
						igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
						igm_masters.imo AS imo, reg_no,dec_code,igm_details.Exporter_name,igm_details.Exporter_address
						FROM igm_masters
						INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
						LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
						LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
						LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
						LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
						WHERE igm_details.Import_Rotation_No='$rotNo' AND BL_No='$blno' ORDER BY file_clearence_date DESC";
					$doInfo=$this->bm->dataSelectDB1($query);
				}
				//---
			
			$Notify_name = "";
			$Notify_address = "";
			$Vessel_Name = "";
			$Voy_No = "";
			$Import_Rotation_No = "";
			
			$igm_pack_number = "";
			$Submission_Date = "";
			$port_of_origin = "";
			$Port_of_Shipment = "";
			$Port_of_Destination = "";
			$Consignee_name = "";
			$Consignee_address = "";
			$Description_of_Goods = "";
			$Pack_Description = "";
			$Pack_Marks_Number = "";
			$weight = "";
			$weight_unit = "";
			$exporter_name = "";
			$exporter_address = "";
			
			for($j=0;$j<count($doInfo);$j++){
				$Notify_name = $doInfo[$j]['Notify_name'];
				$Notify_address = $doInfo[$j]['Notify_address'];
				$Vessel_Name = $doInfo[$j]['Vessel_Name'];
				$Voy_No = $doInfo[$j]['Voy_No'];
				$Import_Rotation_No = $doInfo[$j]['Import_Rotation_No'];
				$Bill_of_Entry_No = $doInfo[$j]['Bill_of_Entry_No'];
				$Submission_Date = $doInfo[$j]['Submission_Date'];
				$port_of_origin = $doInfo[$j]['port_of_origin'];
				$Port_of_Shipment = $doInfo[$j]['Port_of_Shipment'];
				$Port_of_Destination = $doInfo[$j]['Port_of_Destination'];
				$Consignee_name = $doInfo[$j]['Consignee_name'];
				$Consignee_address = $doInfo[$j]['Consignee_address'];
				$Description_of_Goods = $doInfo[$j]['Description_of_Goods'];
				$Pack_Description = $doInfo[$j]['Pack_Description'];
				$Pack_Marks_Number = $doInfo[$j]['Pack_Marks_Number'];
				$weight = $doInfo[$j]['weight'];
				$weight_unit = $doInfo[$j]['weight_unit'];
				$igm_pack_number = $doInfo[$j]['Pack_Number'];
				$exporter_name = $doInfo[$j]['Exporter_name'];
				$exporter_address = $doInfo[$j]['Exporter_address'];				
			}
			
			$this->data['Notify_name']=$Notify_name;
			$this->data['Notify_address']=$Notify_address;
			$this->data['Vessel_Name']=$Vessel_Name;
			$this->data['Voy_No']=$Voy_No;
			$this->data['Import_Rotation_No']=$Import_Rotation_No;
			$this->data['Submission_Date']=$Submission_Date;
			$this->data['port_of_origin']=$port_of_origin;
			$this->data['Port_of_Shipment']=$Port_of_Shipment;
			$this->data['Port_of_Destination']=$Port_of_Destination;
			$this->data['Consignee_name']=$Consignee_name;
			$this->data['Consignee_address']=$Consignee_address;
			$this->data['Description_of_Goods']=$Description_of_Goods;
			$this->data['Pack_Description']=$Pack_Description;
			$this->data['Pack_Marks_Number']=$Pack_Marks_Number;
			$this->data['weight']=$weight;
			$this->data['weight_unit']=$weight_unit;
			$this->data['igm_pack_number']=$igm_pack_number;
			$this->data['exporter_name']=$exporter_name;
			$this->data['exporter_address']=$exporter_address;
			
			
			$this->data['doInfo']=$doInfo;
			
			$queryRemainingQty = "SELECT IFNULL(gross_quantity,0),IFNULL(SUM(delv_quantity),0) AS total_delivered,
								(IFNULL(gross_quantity,0)-IFNULL(SUM(delv_quantity),0)) AS remaining
								FROM shed_mlo_do_info
								WHERE shed_mlo_do_info.imp_rot='$rotNo' AND shed_mlo_do_info.bl_no='$blno'";
			$remainingQty=$this->bm->dataSelectDB1($queryRemainingQty);
			$this->data['remainingQty']=$remainingQty;
			
			$edo_number = "";
			
			$edoUploadingTime = "";
			$measurement = "";
			$valid_upto_dt = "";
			$Bill_of_Entry_No = "";
			$Bill_of_Entry_Dt = "";
			$office_code = "";
			$edo_mlo = "";
			$edo_sl = "";
			$edo_year = "";
			$remarks = "";
			$line_no = "";
			$receipt_no = "";
			$receipt_date = "";
			$r_no = "";
			$r_no_date = "";
			$queryShedMloDOList = "SELECT * FROM shed_mlo_do_info WHERE id='$shedMloDo'";
			$ShedMloDOList=$this->bm->dataSelectDB1($queryShedMloDOList);
		
			for($mlo=0;$mlo<count($ShedMloDOList);$mlo++){
				$edoUploadingTime = $ShedMloDOList[$mlo]['upload_time'];
				$measurement = $ShedMloDOList[$mlo]['measurement'];
				$valid_upto_dt = $ShedMloDOList[$mlo]['valid_upto_dt'];
				$Bill_of_Entry_No = $ShedMloDOList[$mlo]['be_no'];
				$Bill_of_Entry_Dt = $ShedMloDOList[$mlo]['be_date'];
				$office_code = $ShedMloDOList[$mlo]['office_code'];
				$edo_mlo = $ShedMloDOList[$mlo]['edo_mlo'];
				$edo_sl = str_pad($ShedMloDOList[$mlo]['edo_sl'], 6, "0", STR_PAD_LEFT);
				$edo_year = $ShedMloDOList[$mlo]['edo_year'];
				$remarks = $ShedMloDOList[$mlo]['remarks'];
				$line_no = $ShedMloDOList[$mlo]['line_no'];
				$receipt_no = $ShedMloDOList[$mlo]['receipt_no'];
				$receipt_date = $ShedMloDOList[$mlo]['receipt_date'];
				$r_no = $ShedMloDOList[$mlo]['r_no'];
				$r_no_date = $ShedMloDOList[$mlo]['r_no_date'];
			}
			$uploaded_at = date( "Y-m-d", strtotime($edoUploadingTime));
			$cnf_lic_no = $ShedMloDOList[0]['cnf_lic_no'];
			if($uploaded_at < "2021-12-02") {
				$edo_number = $shedMloDo;
			} else {
				$edo_number = $edo_mlo.$edo_sl.$edo_year;
			}
			
			
			$this->data['ShedMloDOList']=$ShedMloDOList;
			$this->data['edoUploadingTime']=$edoUploadingTime;
			$this->data['measurement']=$measurement;
			$this->data['valid_upto_dt']=$valid_upto_dt;
			$this->data['Bill_of_Entry_No']=$Bill_of_Entry_No;
			$this->data['Bill_of_Entry_Dt']=$Bill_of_Entry_Dt;
			$this->data['office_code']=$office_code;
			$this->data['edo_mlo']=$edo_mlo;
			$this->data['edo_sl']=$edo_sl;
			$this->data['edo_year']=$edo_year;
			$this->data['edo_number']=$edo_number;
			$this->data['remarks']=$remarks;
			$this->data['line_no']=$line_no;
			$this->data['receipt_no']=$receipt_no;
			$this->data['receipt_date']=$receipt_date;
			$this->data['uploaded_at']=$uploaded_at;
			$this->data['r_no']=$r_no;
			$this->data['r_no_date']=$r_no_date;
			
			$sql_CNFName="SELECT id,name FROM ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";	
			$cnf_name=$this->bm->dataselect($sql_CNFName);
			//$cnfName = $cnf_name[0]['name'];
			$this->data['cnf_name']=$cnf_name;
			
			$mlo_code = "";
			$sql_mloCode="SELECT mlocode FROM igm_details WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
			$res_mloCode=$this->bm->dataSelectDB1($sql_mloCode);
			for($mloCode=0;$mloCode<count($res_mloCode);$mloCode++)
				{
					$mlo_code = $res_mloCode[$mloCode]['mlocode'];
				}
			
			/////////////////////
				$cnfName = "";
				$cnfLicenseNo = "";
				$sql_CNFData="SELECT u_name,org_id,organization_profiles.License_No
							FROM users
							INNER JOIN organization_profiles ON users.org_id=organization_profiles.id
							WHERE users.login_id='$sumitted_by'";
				$res_CNFData=$this->bm->dataSelectDB1($sql_CNFData);
				for($k=0;$k<count($res_CNFData);$k++)
					{
						$cnfName = $res_CNFData[$k]['u_name'];
						$cnfLicenseNo = $res_CNFData[$k]['License_No'];
					}
				$this->data['cnfName']=$cnfName;
				$this->data['cnfLicenseNo']=$cnfLicenseNo;
				
			/////////////////
			
			$Organization_Name = "";
			$Address_1 = "";
			$Address_2 = "";
			$License_No = "";
			$AIN_No_New = "";
			$logo_pic = "";
			$Cell_No_1 = "";
			$Telephone_No_Land = "";
			$org_id = "";
			$Org_Type_id = "";
			
			$strMloLogo = "";
			
			if($type_of_bl=="HB" and $edoIGMType=="GM")
			{
				$orgLogo = "SELECT edo_application_by_cf.ff_org_id,organization_profiles.id AS org_id,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,organization_profiles.Org_Type_id,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.ff_org_id=organization_profiles.id
						WHERE rotation='$rotNo' AND bl='$blno'";
			}
			else if($type_of_bl=="MB" and $edoIGMType=="GM")
			{
				$orgLogo = "SELECT edo_application_by_cf.mlo,organization_profiles.id AS org_id,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,organization_profiles.Org_Type_id,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.mlo=organization_profiles.id
						WHERE rotation='$rotNo' AND bl='$blno'";
						
				// $orgLogo = "SELECT edo_application_by_cf.mlo,organization_profiles.id AS org_id,organization_profiles.Organization_Name,
						// organization_profiles.Address_1,organization_profiles.Address_2,organization_profiles.Org_Type_id,
						// organization_profiles.License_No,organization_profiles.AIN_No_New,organization_logo.logo_path AS logo,
						// organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						// FROM edo_application_by_cf
						// INNER JOIN organization_profiles ON edo_application_by_cf.mlo=organization_profiles.id
						// INNER JOIN organization_logo ON edo_application_by_cf.mlo=organization_logo.org_id
						// WHERE rotation='$rotNo' AND bl='$blno'";
			}
			else if($edoIGMType=="BB")
			{
				$orgLogo = "SELECT edo_application_by_cf.sh_agent_org_id,organization_profiles.id AS org_id,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,organization_profiles.Org_Type_id,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.sh_agent_org_id=organization_profiles.id
						WHERE rotation='$rotNo' AND bl='$blno'";
			}
			
			$resOrgLogo = $this->bm->dataSelectDB1($orgLogo);
			for($t=0;$t<count($resOrgLogo);$t++){
				$logo_pic = $resOrgLogo[$t]['logo'];
				$Organization_Name = $resOrgLogo[$t]['Organization_Name'];
				$Address_1 = $resOrgLogo[$t]['Address_1'];
				$Address_2 = $resOrgLogo[$t]['Address_2'];
				$License_No = $resOrgLogo[$t]['License_No'];
				$AIN_No_New = $resOrgLogo[$t]['AIN_No_New'];
				$Cell_No_1 = $resOrgLogo[$t]['Cell_No_1'];
				$Telephone_No_Land = $resOrgLogo[$t]['Telephone_No_Land'];
				$org_id = $resOrgLogo[$t]['org_id'];
				$Org_Type_id = $resOrgLogo[$t]['Org_Type_id'];
			}
			// echo $logo_pic;mlo_code
			// return;
			
			$mlo_logo = "";
			$sql_mloLogo="SELECT * FROM organization_logo WHERE org_id='$org_id' AND mlo_code='$mlo_code'";
			$res_mloLogo=$this->bm->dataSelectDB1($sql_mloLogo);
			
			for($mloLogo=0;$mloLogo<count($res_mloLogo);$mloLogo++)
				{
					$mlo_logo = $res_mloLogo[$mloLogo]['logo_path'];
				}
				
			if($type_of_bl=="MB" and $edoIGMType=="GM")
			{
				$logo_pic = $mlo_logo;
			} 
			else 
			{
				$logo_pic = $logo_pic;
			}
			
			$this->data['Organization_Name']=$Organization_Name;
			$this->data['Address_1']=$Address_1;
			$this->data['Address_2']=$Address_2;
			$this->data['License_No']=$License_No;
			$this->data['AIN_No_New']=$AIN_No_New;
			$this->data['logo_pic']=$logo_pic;
			$this->data['Cell_No_1']=$Cell_No_1;
			$this->data['Telephone_No_Land']=$Telephone_No_Land;
			$this->data['Org_Type_id']=$Org_Type_id;
			$this->data['mlo_logo']=$mlo_logo;
			
			$this->data['reslt']=$reslt;
			$this->data['resltBE']=$resltBE;
			
			$this->data['frmType']="search";
			
			$this->data['title']="Shed Delivery Order Info Entry";
			$this->data['type_of_bl']=$type_of_bl;
			$this->data['msg']=$msg;
			$this->data['blno']=$blno;
			$this->data['shedMloDo']=$shedMloDo;
						
			// echo $logo_pic."-".$mlo_logo;
			// echo $_SERVER['SERVER_NAME'].'/pcs/assets/organizationLogo/'.$logo_pic;
			// return;
						
			$this->load->library('m_pdf');
			//$mpdf->use_kwt = true; Import_Rotation_No
			
			if($edoIGMType=="BB"){
				//$html=$this->load->view('bbEDOPDF_V1',$this->data, true);
				$html=$this->load->view('bbEDOPDF',$this->data, true);
			} else {
				$html=$this->load->view('EDOPDF',$this->data, true);
			}
			 

			$pdfFilePath ="EDO_".$edo_number;
			
			$pdf = $this->m_pdf->load();
			
			//$pdf->SetDisplayPreferences('/HideMenubar/HideToolbar/DisplayDocTitle'); //Show only title in adobe reader
			$pdf->SetTitle('EDO_'.$edo_number);
			$pdf->SetTitle = true;
			// $pdf->SetWatermarkText('CPA CTMS');
			// $pdf->showWatermarkText = true;  

			$pdf->useSubstitutions = true; 
				
			//$pdf->setFooter('Prepared By : '.$user.'|Page {PAGENO}|Date {DATE j-m-Y}'); 

			//Following 1 line is used for debugging the error:- "HTML contains invalid UTF-8 character(s)"
			$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
			
			$pdf->WriteHTML($html,2);
				
			$pdf->Output($pdfFilePath, "I");
		}
	}
	
	function ApprovedForEDOList()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['msg'] = "";
			$data['cpa_search'] = 0;
			$data['flag'] = "all"; //To show all do list
			$data['searchBy']="";
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('approveForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function edoVerificationReportFrom()
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
			$frmType="new";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="EDO Verification Report";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('edoVerificationReportForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function edoVerificationReport()
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
			$search_type=$this->input->post('search_type');
			$page_flag = "";
			
			if($search_type == "0") {
				$condition = " WHERE check_st='0' AND (DATE(shed_mlo_do_info.upload_time) BETWEEN '$from_date' AND '$to_date')";
			} else if($search_type == "1"){
				$condition = " WHERE check_st='1' AND (DATE(shed_mlo_do_info.cpa_check_time) BETWEEN '$from_date' AND '$to_date')";
			} else if($search_type == "2"){
				$condition = " WHERE (check_st='0' OR check_st='1') AND (DATE(shed_mlo_do_info.upload_time) BETWEEN '$from_date' AND '$to_date')";
			}
			
			$str="SELECT shed_mlo_do_info.imp_rot,shed_mlo_do_info.bl_no,shed_mlo_do_info.cpa_check_time,shed_mlo_do_info.cpa_checked_by,
			(SELECT u_name FROM users WHERE login_id=cpa_checked_by) AS cpa_checked_by_name,
			shed_mlo_do_info.upload_time,shed_mlo_do_info.check_st,
			edo_application_by_cf.sumitted_by,users.u_name AS CF,
			edo_application_by_cf.mlo,organization_profiles.Organization_Name AS mloName,
			edo_application_by_cf.ff_org_id,edo_application_by_cf.entry_time,edo_application_by_cf.ff_clearance_time
			FROM shed_mlo_do_info 
			INNER JOIN edo_application_by_cf ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
			INNER JOIN users ON edo_application_by_cf.sumitted_by=users.login_id
			INNER JOIN organization_profiles ON edo_application_by_cf.mlo=organization_profiles.id". $condition;
			//WHERE check_st='$search_type' AND (DATE(shed_mlo_do_info.cpa_check_time) BETWEEN '$from_date' AND '$to_date')";
			$edoVerificationList = $this->bm->dataSelectDB1($str);
			if(count($edoVerificationList) > 800){
				$page_flag = "html";
				$data['edoVerificationList']=$edoVerificationList;
				$data['from_date']=$from_date;
				$data['to_date']=$to_date;
				$data['search_type']=$search_type;
				$data['page_flag']=$page_flag;
				$data['title']="EDO Verification Report";
				
				$this->load->view('edoVerificationReport',$data);
			} else {
				$this->load->library('m_pdf');
				$mpdf->use_kwt = true;
				$mpdf->simpleTables = true;
			
				$page_flag = "pdf";
				$this->data['edoVerificationList']=$edoVerificationList;
				$this->data['from_date']=$from_date;
				$this->data['to_date']=$to_date;
				$this->data['search_type']=$search_type;
				$this->data['page_flag']=$page_flag;
				$this->data['title']="EDO Verification Report";
				
				$html=$this->load->view('edoVerificationReport',$this->data, true); 
				$pdfFilePath ="edoVerificationReport";

				$pdf = $this->m_pdf->load();
				//$stylesheet = file_get_contents(CSS_PATH.'style.css'); // external css
				//$stylesheet = file_get_contents('resources/styles/test.css'); 
				//$pdf->useSubstitutions = true; 				
				$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
			
				//$pdf->WriteHTML($stylesheet,1);
				$pdf = new mPDF('utf-8', 'A4-L');  //have tried several of the formats
				//$pdf->WriteHTML($content,2);
				$pdf->WriteHTML($html,2);
					
				$pdf->Output($pdfFilePath, "I");
			}
		}
	}
	
	function edoDateAndOrganizationWiseSummaryForm()
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
			$frmType="new";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="Date & Organization Wise EDO Summary Form";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('OrganizationWiseEDOSummaryForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function edoDateAndOrganizationWiseSummary()
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

			$str="SELECT do_date,org_name,SUM(tot) AS tot,SUM(approve) AS approve,SUM(notApprove) AS notApprove
			FROM (
			SELECT shed_mlo_do_info.do_date,IFNULL(ff_org_id,mlo) AS org, 
			(SELECT Organization_Name FROM organization_profiles WHERE id=org) AS org_name,
			1 AS tot,IF(check_st=1,1,0) AS approve,IF(check_st=0,1,0) AS notApprove
			FROM shed_mlo_do_info
			INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
			WHERE shed_mlo_do_info.do_date BETWEEN '$from_date' AND '$to_date'
			) AS tbl GROUP BY do_date,org_name ORDER BY do_date,org_name";

			$edoReport = $this->bm->dataSelectDB1($str);

			$this->data['edoReport']=$edoReport;			
			$this->data['from_date']=$from_date;
			$this->data['to_date']=$to_date;
			$this->data['title']="Date Wise EDO Report";
			
			$this->load->library('m_pdf');
			//$mpdf->use_kwt = true;
			//$mpdf->simpleTables = true;

			$html=$this->load->view('edoDateAndOrganizationWiseSummary',$this->data, true); 
			$pdfFilePath ="edoDateAndOrganizationWiseSummary";

			$pdf = $this->m_pdf->load();
			$pdf->SetWatermarkText('CPA CTMS');
			$pdf->showWatermarkText = false;
			//$stylesheet = file_get_contents(CSS_PATH.'style.css'); // external css
			$stylesheet = file_get_contents('resources/styles/test.css'); 
			//$pdf->useSubstitutions = true; 				
			//$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
		
			//$pdf->WriteHTML($stylesheet,1);
			//$pdf = new mPDF('utf-8', 'A4-L');  //have tried several of the formats
			//$pdf->WriteHTML($content,2);
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
				
			$pdf->Output($pdfFilePath, "I");
		}
	}
	
	function organizationWiseTokenReportPrint()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Organization Wise Token Report";
			$data['flag']="new";
			$data['msg']="";
									
			$qryOrgList = "SELECT Organization_Name,AIN_No,AIN_No_New,
							(SELECT COUNT(*) FROM token_distribution WHERE token_distribution.ff_ain=organization_profiles.AIN_No_New) 
							AS total_token,
							(SELECT COUNT(*) FROM token_distribution WHERE token_distribution.ff_ain=organization_profiles.AIN_No_New 
							AND token_distribution.used_st='1') AS total_used,
							(SELECT COUNT(*) FROM token_distribution WHERE token_distribution.ff_ain=organization_profiles.AIN_No_New 
							AND token_distribution.used_st='0') AS total_pending 
							FROM organization_profiles WHERE organization_profiles.Org_Type_id='4'";	
			$org_list = $this->bm->dataSelectDB1($qryOrgList);
			$data['org_list'] = $org_list;
			
			$this->data['org_list']=$org_list;
			
			$totalPage=ceil(count($org_list)/40);
			$this->data['totalPage']=$totalPage;
			$this->data['totalRow']=count($org_list);
						
			$this->load->library('m_pdf');
			
			//load the pdf_output.php by passing our data and get all data in $html varriable.
			$html=$this->load->view('organizationWiseTokenReportPrint',$this->data, true); 
			//var_dump($html);
			
			$pdfFilePath ="organizationWiseTokenReportPrint-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			
			
			$pdf->allow_charset_conversion = true;
			//Follwing line is commented to show bangla font in PDF
			//$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
			
			// please follow : https://mpdf.github.io/reference/mpdf-functions/addpage.html
			// L - landscape, P - portrait
			$pdf->AddPage(
				'P', // orientation L|P
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
				'' //Sheet size...array can be set also, like [210,297]
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
			
			$pdf->Output($pdfFilePath, "I"); // For Showing Pdf
			
		}
	}
	
	function userTypeWiseEDOSummaryForm()
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
			$frmType="new";
			$data['frmType']=$frmType;
			$data['msg']=$msg;
			$data['title']="User Type wise EDO Summary Form";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('userTypeWiseEDOSummaryForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function userTypeWiseEDOSummary()
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$org_type_id=$this->session->userdata('org_Type_id');
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$from_date=$this->input->post('from_date');
			$to_date=$this->input->post('to_date');
			$this->data['from_date']=$from_date;
			$this->data['to_date']=$to_date;			
			
			$queryTotalCNF = "SELECT COUNT(*) AS rtnValue FROM users 
						WHERE users.login_id IN 
						(SELECT sumitted_by FROM edo_application_by_cf 
						WHERE (DATE(entry_time) BETWEEN '$from_date' AND '$to_date'))";		
			$totalCNF = $this->bm->dataReturnDB1($queryTotalCNF);
			$this->data['totalCNF']=$totalCNF;
			
			$queryTotalAppliedByCNF = "SELECT COUNT(*) AS rtnValue FROM edo_application_by_cf 
										WHERE (DATE(entry_time) BETWEEN '$from_date' AND '$to_date')";		
			$totalAppliedByCNF = $this->bm->dataReturnDB1($queryTotalAppliedByCNF);
			$this->data['totalAppliedByCNF']=$totalAppliedByCNF;
			
			$queryTotalFF = "SELECT COUNT(DISTINCT ff_org_id) AS rtnValue
							FROM shed_mlo_do_info 
							INNER JOIN edo_application_by_cf ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
							WHERE shed_mlo_do_info.bl_type='HB' AND edo_application_by_cf.do_upload_st='1'
							AND (DATE(shed_mlo_do_info.upload_time) BETWEEN '$from_date' AND '$to_date')";		
			$totalFF = $this->bm->dataReturnDB1($queryTotalFF);
			$this->data['totalFF']=$totalFF;
			
			// $queryTotalUploadedByFF = "SELECT COUNT(*) AS rtnValue FROM shed_mlo_do_info 
										// WHERE shed_mlo_do_info.bl_type='HB' AND 
										// (DATE(upload_time) BETWEEN '$from_date' AND '$to_date')";		
			// $totalUploadedByFF = $this->bm->dataReturnDB1($queryTotalUploadedByFF);
			// $this->data['totalUploadedByFF']=$totalUploadedByFF;
			
			$queryTotalUploadedByFF = "SELECT SUM(tot) AS rtnValue
									FROM (
										SELECT (SELECT IFNULL(ff_org_id,mlo) FROM edo_application_by_cf WHERE id=edo_id) AS org,
										IF(check_st=1,1,0) AS approve,IF(check_st=0,1,0) AS notApprove,1 AS tot,
										(SELECT Organization_Name FROM organization_profiles WHERE id=org) AS org_name
										FROM shed_mlo_do_info
										WHERE (DATE(shed_mlo_do_info.upload_time) BETWEEN '$from_date' AND '$to_date') 
										AND shed_mlo_do_info.bl_type='HB'
									) AS tbl WHERE org_name IS NOT NULL";		
			$totalUploadedByFF = $this->bm->dataReturnDB1($queryTotalUploadedByFF);
			$this->data['totalUploadedByFF']=$totalUploadedByFF;
			
			$queryTotalMLO = "SELECT COUNT(DISTINCT mlo) AS rtnValue FROM shed_mlo_do_info
								INNER JOIN edo_application_by_cf ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
								WHERE edo_application_by_cf.bl_type='MB' AND edo_application_by_cf.do_upload_st='1'
								AND (DATE(shed_mlo_do_info.upload_time) BETWEEN '$from_date' AND '$to_date')";		
			$totalMLO = $this->bm->dataReturnDB1($queryTotalMLO);
			$this->data['totalMLO']=$totalMLO;
			
			// $queryTotalUploadedByMLO = "SELECT COUNT(*) AS rtnValue FROM shed_mlo_do_info 
										// WHERE shed_mlo_do_info.bl_type='MB' AND 
										// (DATE(upload_time) BETWEEN '$from_date' AND '$to_date')";		
			// $totalUploadedByMLO = $this->bm->dataReturnDB1($queryTotalUploadedByMLO);
			// $this->data['totalUploadedByMLO']=$totalUploadedByMLO;
			
			$queryTotalUploadedByMLO = "SELECT SUM(tot) AS rtnValue
								FROM (
									SELECT (SELECT IFNULL(ff_org_id,mlo) FROM edo_application_by_cf WHERE id=edo_id) AS org,
									IF(check_st=1,1,0) AS approve,IF(check_st=0,1,0) AS notApprove,1 AS tot,
									(SELECT Organization_Name FROM organization_profiles WHERE id=org) AS org_name
									FROM shed_mlo_do_info
									WHERE (DATE(shed_mlo_do_info.upload_time) BETWEEN '$from_date' AND '$to_date') 
									AND shed_mlo_do_info.bl_type='MB'
								) AS tbl WHERE org_name IS NOT NULL";		
			$totalUploadedByMLO = $this->bm->dataReturnDB1($queryTotalUploadedByMLO);
			$this->data['totalUploadedByMLO']=$totalUploadedByMLO;
			
			$queryTotalCPA = "SELECT COUNT(DISTINCT cpa_checked_by) AS rtnValue FROM shed_mlo_do_info
								WHERE check_st='1' AND (cpa_check_time BETWEEN '$from_date' AND '$to_date')";		
			$totalCPA = $this->bm->dataReturnDB1($queryTotalCPA);
			$this->data['totalCPA']=$totalCPA;
			
			$queryTotalApprovedByCPA = "SELECT COUNT(*) AS rtnValue FROM shed_mlo_do_info 
										WHERE shed_mlo_do_info.check_st='1' AND 
										(shed_mlo_do_info.cpa_check_time BETWEEN '$from_date' AND '$to_date')";		
			$totalApprovedByCPA = $this->bm->dataReturnDB1($queryTotalApprovedByCPA);
			$this->data['totalApprovedByCPA']=$totalApprovedByCPA;
			
			$totalPage = 1;
			$this->data['totalPage']=$totalPage;
			
			
			ob_start();//Enables Output Buffering
			$this->load->library('m_pdf');
			
			//load the pdf_output.php by passing our data and get all data in $html varriable.
			$html=$this->load->view('userTypeWiseEDOSummary',$this->data, true); 
			//var_dump($html);
			
			$pdfFilePath ="userTypeWiseEDOSummary-".time()."-download.pdf";//here

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
				'A4'//Sheet size...array can be set also, like [210,297] or can also be set as 'Legal-L'
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
			
			ob_end_clean();//End Output Buffering
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			
			
		}
	}
	
	function deleteEDOApplication()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['flag'] = "all"; //To show all do list$data['flag'] = "all"; //To show all do list
			
			$login_id = $this->session->userdata('login_id');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			$edo_id = $this->input->post("edo_id");
			
			$insertLogQuery="INSERT INTO edo_application_delete_log(edo_id,deleted_by,deleted_at,deleted_by_ip)
						  VALUES('$edo_id','$login_id',NOW(),'$ipaddr')";
			$this->bm->dataInsertDb1($insertLogQuery);
				
			$sql_delete = "DELETE FROM edo_application_by_cf WHERE id='$edo_id'";
			$del_st = $this->bm->dataDeleteDB1($sql_delete);
			if($del_st)
			{
				$data['msg']='<font color=blue><b>Deleted Successfully.</b></font>';
			}				
			else
			{
				$data['msg']='<font color=red><b>Sorry! Could not delete.</b></font>';
			}
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function applyForValidityExtensionForFCLandHBL()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['flag'] = "all"; //To show all do list
			$data['searchBy'] = "";
			$data['searchInput'] = "";
			$data['cpa_search'] = 0;
			
			
			
			$eid = $this->input->post("edoForFCLAndHBL");
			$requested_date = $this->input->post("requested_date");
			
			$query = "UPDATE edo_application_by_cf SET applied_valid_dt='$requested_date',applied_validity_extension_at=NOW(), cnf_vldty_appr_st='1' WHERE id='$eid'";
			$update_st=$this->bm->dataUpdateDB1($query);
			
			if($update_st==1)
				$data['msg']='<font color=blue><b>Applied Successfully.</b></font>';
			else
				$data['msg']='<font color=red><b>Application Failed.</b></font>';			

            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function applyForValidityExtension()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['flag'] = "all"; //To show all do list
			$data['searchBy'] = "";
			$data['searchInput'] = "";
			$data['cpa_search'] = 0;
			
			$eid = $this->input->post("extEDOId");
			$requested_date = $this->input->post("requested_date");
			$query = "UPDATE edo_application_by_cf SET applied_valid_dt='$requested_date',applied_validity_extension_at=NOW() WHERE id='$eid'";			
			$update_st=$this->bm->dataUpdateDB1($query);
			if($update_st==1)
				$data['msg']='<font color=blue><b>Applied Successfully.</b></font>';
			else
				$data['msg']='<font color=red><b>Application Failed.</b></font>';			

            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function validityExtensionApplicationFormForHBL()
	{

		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		 	$edo_id=$this->input->post('edo_id');
			$do_upload_st=$this->input->post('do_upload_st');
			$bl_type=$this->input->post('bl_type');
			$imp_rot=$this->input->post('imp_rot');
			$blNo=$this->input->post('blNo');
			$uploadId=$this->input->post('uploadId');
			$result = "";
			// if($do_upload_st=="0"){
				if($bl_type=="MB")
				{					
					$query="SELECT igm_detail_container.id AS cId,cont_number, cont_status, cont_location_code, cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages
									FROM igm_detail_container 
									INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
									WHERE  igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$blNo'";
				}
				else if($bl_type=="HB")
				{					
					$query="SELECT igm_sup_detail_container.id AS cId,cont_number, cont_status, cont_location_code, cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages
									FROM igm_sup_detail_container 
									INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
									WHERE  igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$blNo'";
				}
				$result =$this->bm->dataSelectDB1($query);

							
			$containerQuery="SELECT * FROM edo_applied_validity_date WHERE edo_id=$edo_id";	
			$containerResult =$this->bm->dataSelectDB1($containerQuery);
			$data['title']="Validity Extend Request";
			$data['msg'] = "";
			$data['edo_id']= $edo_id;
			$data['do_upload_st']= $do_upload_st;
			$data['bl_type']=$bl_type;
			$data['imp_rot']=$imp_rot;
			$data['blNo']=$blNo;
			$data['uploadId']=$uploadId;
			$data['result']=$result;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('validityExtentionFormForHBL',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function validityExtendHBL()
	{
		$total=$this->input->post('total');
		$uploadId=$this->input->post('uploadId');
		$edo_id=$this->input->post('edo_id');
		$validity=$this->input->post('validity_extend');
		$login_id = $this->session->userdata('login_id');
		if(!empty($_POST['list']))
			{
				$sql_update_cnf_vldty_appr_st="UPDATE edo_application_by_cf SET cnf_vldty_appr_st='1'  WHERE id='$edo_id'";
				$res_update_cnf_vldty_appr_st = $this->bm->dataUpdateDB1($sql_update_cnf_vldty_appr_st);
				if($res_update_cnf_vldty_appr_st==1)
				{
					$sqlDlt = "DELETE FROM edo_applied_validity_date WHERE edo_id='$edo_id'";
					$this->bm->dataDeleteDB1($sqlDlt);
					
					foreach($_POST['list'] as $containerId)
					{ 
					 $insertQuery="INSERT INTO edo_applied_validity_date(edo_id,shed_mlo_do_info_id,cont_igm_id,applied_validity_date,entered_by,entered_at)
									  VALUES('$edo_id','$uploadId','$containerId','$validity','$login_id',NOW())";
									 
						$this->bm->dataInsertDb1($insertQuery);
						$data['msg'] = "<font color='blue'><strong>Successfully Applied For Validity Extention</strong></font>";
						
						
						
						//if($resUpdate)				
						// $updateQuery="UPDATE do_upload_wise_container
									  // SET valid_upto_date= '$validity'
									  // WHERE cont_igm_id='$containerId' AND shed_mlo_do_info_id='$uploadId'";
						//$this->bm->dataUpdateDB1($updateQuery);
							
						
					}
					
					$updateCNFApplicationSt="UPDATE edo_application_by_cf SET applied_valid_dt='$validity', cnf_vldty_appr_st='1',
											applied_validity_extension_at=NOW() WHERE id='$edo_id'";
					$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
				}
				else
				{
					$data['msg'] = "<font color='red'><strong>Sorry ! Could not update data.</strong></font>";
				}
				
				
				
			} 
		else
			{
				$data['msg'] = "<font color='red'><strong>No Container was selected</strong></font>";
			}
		$data['org_id'] =$this->session->userdata('org_id');
		$data['title']="EDO Application List";
		
		$data['flag'] = "all"; //To show all do list
		$data['searchBy'] = "";
		$data['searchInput'] = "";
		$data['cpa_search'] = 0;
		
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('applicationForEDOList',$data);
		$this->load->view('jsAssetsList');

	}
	
	function validityExtensionApplicationFormForMBL(){

		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$edo_id=$this->input->post('edo_id');
			$do_upload_st=$this->input->post('do_upload_st');
			$bl_type=$this->input->post('bl_type');
			$imp_rot=$this->input->post('imp_rot');
			$blNo=$this->input->post('blNo');
			$uploadId=$this->input->post('uploadId');
			$result = "";
			// if($do_upload_st=="0"){
				if($bl_type=="MB")
				{					
					$query="SELECT igm_detail_container.id AS cId,cont_number, cont_status, cont_location_code, cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages
									FROM igm_detail_container 
									INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
									WHERE  igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$blNo'";
				}
				else if($bl_type=="HB")
				{					
					$query="SELECT igm_sup_detail_container.id AS cId,cont_number, cont_status, cont_location_code, cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages
									FROM igm_sup_detail_container 
									INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
									WHERE  igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$blNo'";
				}
				$result =$this->bm->dataSelectDB1($query);
			// } else {
				// $result = "";
			// }					
				
			$data['title']="Validity Extend Request";
			$data['msg'] = "";
			$data['edo_id']= $edo_id;
			$data['do_upload_st']= $do_upload_st;
			$data['bl_type']=$bl_type;
			$data['imp_rot']=$imp_rot;
			$data['blNo']=$blNo;
			$data['uploadId']=$uploadId;
			$data['result']=$result;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('validityExtensionApplicationForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function validityExtendNew()
	{	
		$total=$this->input->post('total');
		$uploadId=$this->input->post('uploadId');
		$edo_id=$this->input->post('edo_id');
		$validity=$this->input->post('validity_extend');
		$login_id = $this->session->userdata('login_id');
		if(!empty($_POST['list']))
			{
				$sql_update_cnf_vldty_appr_st="UPDATE edo_application_by_cf SET cnf_vldty_appr_st='1' WHERE id='$edo_id'";
				$res_update_cnf_vldty_appr_st = $this->bm->dataUpdateDB1($sql_update_cnf_vldty_appr_st);
				if($res_update_cnf_vldty_appr_st==1)
				{
					$sqlDlt = "DELETE FROM edo_applied_validity_date WHERE edo_id='$edo_id'";
					$this->bm->dataDeleteDB1($sqlDlt);
					
					foreach($_POST['list'] as $containerId)
					{ 
						$insertQuery="INSERT INTO edo_applied_validity_date(edo_id,shed_mlo_do_info_id,cont_igm_id,applied_validity_date,entered_by,entered_at)
									  VALUES('$edo_id','$uploadId','$containerId','$validity','$login_id',NOW())";
						$this->bm->dataInsertDb1($insertQuery);
						$data['msg'] = "<font color='blue'><strong>Successfully Applied For Validity Extention</strong></font>";					
											
						//if($resUpdate)				
						// $updateQuery="UPDATE do_upload_wise_container
									  // SET valid_upto_date= '$validity'
									  // WHERE cont_igm_id='$containerId' AND shed_mlo_do_info_id='$uploadId'";
						//$this->bm->dataUpdateDB1($updateQuery);
							
						
					}
					$updateCNFApplicationSt="UPDATE edo_application_by_cf SET applied_valid_dt='$validity',cnf_vldty_appr_st='1',applied_validity_extension_at=NOW() 
												WHERE id='$edo_id'";
					$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
				}
				else
				{
					$data['msg'] = "<font color='red'><strong>Sorry ! Could not update data.</strong></font>";
				}
			} 
		else
			{
				$data['msg'] = "<font color='red'><strong>No Container was selected</strong></font>";
			}
		$data['org_id'] =$this->session->userdata('org_id');
		$data['title']="EDO Application List";
		$data['flag'] = "all"; //To show all do list
		$data['searchBy'] = "";
		$data['searchInput'] = "";
		$data['cpa_search'] = 0;
		
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('applicationForEDOList',$data);
		$this->load->view('jsAssetsList');
	}
	
	function shedDeOInfoData()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$rotNo = $this->input->post('imp_rot');			
			$blno = $this->input->post('blNo');			
			$edo_id = $this->input->post('edo_id');			
			$type_of_bl = $this->input->post('bl_type');
			$igm_type = $this->input->post('igm_type');
			$sumitted_by = $this->input->post('sumitted_by');
			//echo $Consignee_name;edo_id
			//return; 
			$msgBLsearch = "";
			$msg = "";
			
			if($this->input->post('editFlag') || $this->input->post('extendValidityFlag'))
			{
				// "edit code block";
				$id= $this->input->post('uploadId');
				$data['editId'] = $id;
				$measurementVal = "";
				$cnf_lic_no = "";
				$validUptodtVal = "";
				$Bill_of_Entry_No_Val = "";
				$BE_Dt_Val = "";
				$office_Code_Val = "";				
				$line_no = "";
				$receipt_no = "";
				$receipt_date = "";				
				$remarks = "";
				$r_no = "";
				$r_no_date = "";
				$shedInfoByIdquery = "SELECT * FROM shed_mlo_do_info WHERE id='$id'";
				$shedInfoById = $this->bm->dataSelectDB1($shedInfoByIdquery);
				for($m=0;$m<count($shedInfoById);$m++)
				{
					$measurementVal = $shedInfoById[$m]['measurement'];
					$cnf_lic_no = $shedInfoById[$m]['cnf_lic_no'];
					$validUptodtVal = $shedInfoById[$m]['valid_upto_dt'];
					$Bill_of_Entry_No_Val = $shedInfoById[$m]['be_no'];
					$BE_Dt_Val = $shedInfoById[$m]['be_date'];
					$office_Code_Val = $shedInfoById[$m]['office_code'];
					$line_no = $shedInfoById[$m]['line_no'];
					$receipt_no = $shedInfoById[$m]['receipt_no'];
					$receipt_date = $shedInfoById[$m]['receipt_date'];
					$remarks = $shedInfoById[$m]['remarks'];
					$r_no = $shedInfoById[$m]['r_no'];
					$r_no_date = $shedInfoById[$m]['r_no_date'];
				}
				
				
				$data['shedInfoById']=$shedInfoById;
				$data['measurementVal']=$measurementVal;
				$data['validUptodtVal']=$validUptodtVal;
				$data['Bill_of_Entry_No_Val']=$Bill_of_Entry_No_Val;
				$data['BE_Dt_Val']=$BE_Dt_Val;
				$data['office_Code_Val']=$office_Code_Val;
				$data['line_no']=$line_no;
				$data['receipt_no']=$receipt_no;
				$data['receipt_date']=$receipt_date;
				$data['remarks']=$remarks;
				$data['cnf_lic_no']=$cnf_lic_no;
				$data['r_no']=$r_no;
				$data['r_no_date']=$r_no_date;
				
				if($this->input->post('editFlag')){
					$data['edit'] = "edit";
				} else {
					$data['edit'] = "extendValidity";
				}
				
				// $sql_CNFName="SELECT id,name FROM ref_bizunit_scoped 
							// WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";	
				// $cnf_name=$this->bm->dataSelect($sql_CNFName);
				// $data['cnf_name']=$cnf_name;
			}else{
				$data['edit'] = "";
			}
			
			//////////////////////////
				$requested_valid_dt = "";
				$valid_dt_mlo = "";
				$contSt = "";
				$beNo = "";
				$beDate = "";
				$ofcCode = "";
				$cnf_vldty_appr_st = "";
				$do_upload_st = "";
				
				$sql_edoApplyInfo="SELECT * FROM edo_application_by_cf WHERE id='$edo_id'";
				
				$res_EDOApplyInfo=$this->bm->dataSelectDB1($sql_edoApplyInfo);
				for($a=0;$a<count($res_EDOApplyInfo);$a++)
					{
						$requested_valid_dt = $res_EDOApplyInfo[$a]['applied_valid_dt'];
						$valid_dt_mlo = $res_EDOApplyInfo[$a]['valid_upto_dt_by_mlo'];
						$contSt = $res_EDOApplyInfo[$a]['cont_status'];
						$beNo = $res_EDOApplyInfo[$a]['be_no'];
						$beDate = $res_EDOApplyInfo[$a]['be_date'];
						$ofcCode = $res_EDOApplyInfo[$a]['ofc_code'];
						$cnf_vldty_appr_st = $res_EDOApplyInfo[$a]['cnf_vldty_appr_st'];
						$do_upload_st = $res_EDOApplyInfo[$a]['do_upload_st'];
					}
				$data['cnf_vldty_appr_st']=$cnf_vldty_appr_st;
			/////////////////////////
			
			//Organization Info Starts........................
			if($type_of_bl=="HB" and $igm_type=="GM")
			{
				$orgInfo = "SELECT edo_application_by_cf.ff_org_id,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.ff_org_id=organization_profiles.id
						WHERE edo_application_by_cf.id='$edo_id'";
			}
			else if($type_of_bl=="MB" and $igm_type=="GM")
			{
				$orgInfo = "SELECT edo_application_by_cf.mlo,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.mlo=organization_profiles.id
						WHERE edo_application_by_cf.id='$edo_id'";
			}
			else if($igm_type=="BB")
			{
				$orgInfo = "SELECT edo_application_by_cf.sh_agent_org_id,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.sh_agent_org_id=organization_profiles.id
						WHERE edo_application_by_cf.id='$edo_id'";
			}
			
			$resOrgInfo = $this->bm->dataSelectDB1($orgInfo);
			$logo_pic="";
			for($t=0;$t<count($resOrgInfo);$t++){
				$logo_pic = $resOrgInfo[$t]['logo'];
			}
			$data['logo_pic']=$logo_pic;
			$data['logo_pic']=1;
			//Organization Info Ends..........................
			
			if($blno=="all")
			{
				$sqlQuery="SELECT Bill_of_Entry_No,Bill_of_Entry_Date FROM igm_details WHERE Import_Rotation_No='$rotNo'";	
			}
			else
			{
				$sqlQuery="SELECT Bill_of_Entry_No,Bill_of_Entry_Date FROM igm_details WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
			}
			$reslt = $this->bm->dataSelectDB1($sqlQuery);
			
			
			$resltBE = "";
			if(count($reslt)==0)
			{
				$sqlQuery="SELECT Bill_of_Entry_No,Bill_of_Entry_Date FROM igm_supplimentary_detail WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
				$reslt = $this->bm->dataSelectDB1($sqlQuery);
			}
			
			
			if(count($reslt)>0){
				$resltBE = $reslt[0]['Bill_of_Entry_No'];
			}
			// if($resltBE=="")
			// {
				// $msgBLsearch="<font color='red'><b>Bill of Entry Number not submitted, Please try again after submitting.</b></font>";
				
			// }
			// else
			// {
				$msgBLsearch = "";
				
				$queryContList = "";
				if($type_of_bl=="MB")
				{
					//echo "master ";
					$queryContList="SELECT igm_detail_container.id AS cId,cont_number, cont_status, cont_location_code, cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages
									FROM igm_detail_container 
									INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
									WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blno'";
				}
				else if($type_of_bl=="HB")
				{
					//echo "ff ";
					$queryContList="SELECT igm_sup_detail_container.id AS cId,cont_number, cont_status, cont_location_code, cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages
									FROM igm_sup_detail_container 
									INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
									WHERE  igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno'";
				}
				$contList=$this->bm->dataSelectDB1($queryContList);
				$data['contList']=$contList;
				
				$query="SELECT DISTINCT igm_details.id AS dtl_id,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,weight,Bill_of_Entry_No,
						igm_details.Bill_of_Entry_Date,igm_details.office_code,
						No_of_Pack_Delivered,DG_status,type_of_igm,net_weight,weight_unit,net_weight_unit,igm_details.Consignee_name,Consignee_address,
						Description_of_Goods,igm_details.Volume_in_cubic_meters,
						igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
						igm_masters.Net_Tonnage,Notify_name,Notify_address,port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
						igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
						igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
						igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
						igm_masters.imo AS imo, reg_no,dec_code,igm_details.Exporter_name,igm_details.Exporter_address
						FROM igm_masters
						INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
						LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
						LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
						LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
						LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
						WHERE igm_details.Import_Rotation_No='$rotNo' AND BL_No='$blno' ORDER BY file_clearence_date DESC";
				$doInfo=$this->bm->dataSelectDB1($query);
				
				//---
				if(count($doInfo)==0)
				{
					$query="SELECT DISTINCT igm_supplimentary_detail.id AS dtl_id,igm_supplimentary_detail.BL_No,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.weight,igm_supplimentary_detail.Bill_of_Entry_No,
					igm_supplimentary_detail.Bill_of_Entry_Date,igm_supplimentary_detail.office_code,
					igm_supplimentary_detail.No_of_Pack_Delivered,igm_supplimentary_detail.DG_status,igm_supplimentary_detail.type_of_igm,igm_supplimentary_detail.net_weight,igm_supplimentary_detail.weight_unit,igm_supplimentary_detail.net_weight_unit,igm_supplimentary_detail.Consignee_name,igm_supplimentary_detail.Consignee_address,
					igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Volume_in_cubic_meters,
					igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
					igm_masters.Net_Tonnage,igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
					igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
					igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
					igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
					igm_masters.imo AS imo,reg_no,dec_code,igm_details.Exporter_name,igm_details.Exporter_address
					FROM igm_masters
					INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
					LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
					LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
					LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
					LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
					WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno' ORDER BY file_clearence_date DESC";
					$doInfo=$this->bm->dataSelectDB1($query);
				}
				//---
				
				$dtl_id = "";
				$Notify_name = "";
				$Notify_address = "";
				$Vessel_Name = "";
				$Voy_No = "";
				$Import_Rotation_No = "";
				$Bill_of_Entry_No = "";
				$Bill_of_Entry_Date = "";
				$office_code = "";
				$Submission_Date = "";
				$port_of_origin = "";
				$Port_of_Shipment = "";
				$Port_of_Destination = "";
				$Consignee_name = "";
				$Consignee_address = "";
				$Description_of_Goods = "";
				$Pack_Description = "";
				$Pack_Marks_Number = "";
				$weight = "";
				$weight_unit = "";
				$Volume_in_cubic_meters = "";
				$igm_pack_number = "";
				$exporter_name = "";
				$exporter_address = "";
				
				for($j=0;$j<count($doInfo);$j++){
					$dtl_id = $doInfo[$j]['dtl_id'];
					$Notify_name = $doInfo[$j]['Notify_name'];
					$Notify_address = $doInfo[$j]['Notify_address'];
					$Vessel_Name = $doInfo[$j]['Vessel_Name'];
					$Voy_No = $doInfo[$j]['Voy_No'];
					$Import_Rotation_No = $doInfo[$j]['Import_Rotation_No'];
					$Bill_of_Entry_No = $doInfo[$j]['Bill_of_Entry_No'];
					$Bill_of_Entry_Date = $doInfo[$j]['Bill_of_Entry_Date'];
					$Submission_Date = $doInfo[$j]['Submission_Date'];
					$port_of_origin = $doInfo[$j]['port_of_origin'];
					$Port_of_Shipment = $doInfo[$j]['Port_of_Shipment'];
					$Port_of_Destination = $doInfo[$j]['Port_of_Destination'];
					$Consignee_name = $doInfo[$j]['Consignee_name'];
					$Consignee_address = $doInfo[$j]['Consignee_address'];
					$Description_of_Goods = $doInfo[$j]['Description_of_Goods'];
					$Pack_Description = $doInfo[$j]['Pack_Description'];
					$Pack_Marks_Number = $doInfo[$j]['Pack_Marks_Number'];
					$weight = $doInfo[$j]['weight'];
					$weight_unit = $doInfo[$j]['weight_unit'];
					$office_code = $doInfo[$j]['office_code'];
					$Volume_in_cubic_meters = $doInfo[$j]['Volume_in_cubic_meters'];
					$igm_pack_number = $doInfo[$j]['Pack_Number'];
					$exporter_name = $doInfo[$j]['Exporter_name'];
					$exporter_address = $doInfo[$j]['Exporter_address'];
					
				}
			
				$data['dtl_id']=$dtl_id;
				$data['Notify_name']=$Notify_name;
				$data['Notify_address']=$Notify_address;
				$data['Vessel_Name']=$Vessel_Name;
				$data['Voy_No']=$Voy_No;
				$data['Import_Rotation_No']=$Import_Rotation_No;
				$data['Bill_of_Entry_No']=$Bill_of_Entry_No;
				$data['Bill_of_Entry_Date']=$Bill_of_Entry_Date;
				$data['Submission_Date']=$Submission_Date;
				$data['port_of_origin']=$port_of_origin;
				$data['Port_of_Shipment']=$Port_of_Shipment;
				$data['Port_of_Destination']=$Port_of_Destination;
				$data['Consignee_name']=$Consignee_name;
				$data['Consignee_address']=$Consignee_address;
				$data['Description_of_Goods']=$Description_of_Goods;
				$data['Pack_Description']=$Pack_Description;
				$data['Pack_Marks_Number']=$Pack_Marks_Number;
				$data['weight']=$weight;
				$data['weight_unit']=$weight_unit;
				$data['office_code']=$office_code;
				$data['Volume_in_cubic_meters']=$Volume_in_cubic_meters;
				$data['igm_pack_number']=$igm_pack_number;
				$data['exporter_name']=$exporter_name;
				$data['exporter_address']=$exporter_address;
				
			
				$data['doInfo']=$doInfo;
				$dec_code = "";
				$Notify_name = "";
				$Notify_address = "";
				$Vessel_Name = "";
				$Voy_No = "";
				$Bill_of_Entry_No = "";
				$Submission_Date = "";
				$port_of_origin = "";
				for($j=0;$j<count($doInfo);$j++)
					{
						$dec_code = $doInfo[$j]['dec_code'];
						$Notify_name = $doInfo[$j]['Notify_name'];
						$Notify_address = $doInfo[$j]['Notify_address'];
						$Vessel_Name = $doInfo[$j]['Vessel_Name'];
						$Voy_No = $doInfo[$j]['Voy_No'];
						$Bill_of_Entry_No = $doInfo[$j]['Bill_of_Entry_No'];
						$Submission_Date = $doInfo[$j]['Submission_Date'];
						$port_of_origin = $doInfo[$j]['port_of_origin'];
					}
				$data['Notify_name']=$Notify_name;
				$data['Notify_address']=$Notify_address;
				$data['Vessel_Name']=$Vessel_Name;
				$data['Voy_No']=$Voy_No;
				$data['Bill_of_Entry_No']=$Bill_of_Entry_No;
				$data['Submission_Date']=$Submission_Date;
				$data['port_of_origin']=$port_of_origin;
				
				/////////////////////
				$cnfName = "";
				$cnfLicenseNo = "";
				$sql_CNFData="SELECT u_name,org_id,organization_profiles.License_No
							FROM users
							INNER JOIN organization_profiles ON users.org_id=organization_profiles.id
							WHERE users.login_id='$sumitted_by'";
				$res_CNFData=$this->bm->dataSelectDB1($sql_CNFData);
				for($k=0;$k<count($res_CNFData);$k++)
					{
						$cnfName = $res_CNFData[$k]['u_name'];
						$cnfLicenseNo = $res_CNFData[$k]['License_No'];
					}
				$data['cnfName']=$cnfName;
				$data['cnfLicenseNo']=$cnfLicenseNo;
				/////////////////
				
				$cnfCode2 = substr($dec_code, 5, 4);
				$cnfCode1 = substr($dec_code, 3, 2);
				$cnfLic = $cnfCode2."/".$cnfCode1;
				
				if($this->input->post('editFlag'))
				{
					$sql_CNFName="SELECT id,name FROM ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";
				}
				else
				{
					$sql_CNFName="SELECT id,name FROM ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
				}
				$cnf_name=$this->bm->dataSelect($sql_CNFName);
				$data['cnf_name']=$cnf_name;
				
				$queryRemainingQty = "SELECT IFNULL(gross_quantity,0),IFNULL(SUM(delv_quantity),0) AS total_delivered,
									(IFNULL(gross_quantity,0)-IFNULL(SUM(delv_quantity),0)) AS remaining
									FROM shed_mlo_do_info
									WHERE shed_mlo_do_info.imp_rot='$rotNo' AND shed_mlo_do_info.bl_no='$blno'";
				$remainingQty=$this->bm->dataSelectDB1($queryRemainingQty);
				$data['remainingQty']=$remainingQty;
				$data['cnfLic']=$cnfLic;
			//}
			
			$data['reslt']=$reslt;
			$data['resltBE']=$resltBE;
			
			$data['frmType']="search";
			$data['title']="Application for EDO";
			//$data['tallytype']=$tallytype;
			$data['msg']=$msg;
			$data['msgBLsearch']=$msgBLsearch;
			$data['blno']=$blno;
			$data['rotNo']=$rotNo;
			$data['type_of_bl']=$type_of_bl;
			$data['igm_type']=$igm_type;
			$data['edo_id']=$edo_id;
			$data['requested_valid_dt']=$requested_valid_dt;
			$data['valid_dt_mlo']=$valid_dt_mlo;
			$data['contSt']=$contSt;
			$data['beNo']=$beNo;
			$data['beDate']=$beDate;
			$data['ofcCode']=$ofcCode;
			
			if($igm_type == "BB"){
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('BBEDOForm',$data);
				//$this->load->view('BBEDOForm_VOne',$data);
				$this->load->view('jsAssets');
			} else {
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('ShedDOForm',$data);
				$this->load->view('jsAssets');
			}		
			
			
		}
	}
	
	function shedDOUpload()
	{		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');		
		$LoginStat = $this->session->userdata('LoginStat');
		$org_type_id =$this->session->userdata('org_Type_id');
		$org_id =$this->session->userdata('org_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			$igm_dtl=$this->input->post('igm_dtl');
			$blno=$this->input->post('blno');
			$blrplc=str_replace("/","_",$blno);
			$rotNo=$this->input->post('rotno');
			$fileRotno=str_replace('/','_',$rotNo);
			$beno=$this->input->post('beno');
			$billOfEntryNo=$this->input->post('billOfEntryNo');
			$billOfEntryDate=$this->input->post('billOfEntryDate');
			$officeCode=$this->input->post('officeCode');
			$remarks=$this->input->post('remarks');
			$valid_upto=$this->input->post('valid_upto');			
			$cnflic=$this->input->post('cnflic');			
			$bl_type=$this->input->post('type_of_bl');			
			$igm_type=$this->input->post('igm_type');			
			$grossQty=$this->input->post('grossQty');
			$deliveredWeight=$this->input->post('deliveredWeight');
			$measurement=$this->input->post('measurement');
			$agentCode = "";
			$edoYr = "";
			$nextSl = "";
			//$cnf_name
			if($org_type_id == 4)
			{
				$ffAgentCodeQuery = "SELECT Agent_Code FROM organization_profiles WHERE id = '$org_id'";
				$ffAgentCodeRslt=$this->bm->dataSelectDB1($ffAgentCodeQuery);

				for($x=0;$x<count($ffAgentCodeRslt);$x++){
					$agentCode = $ffAgentCodeRslt[$x]['Agent_Code'];
				}

				$login_id=$this->session->userdata('login_id');

				// $nextSlCountQuery = "SELECT count(*) AS rtnValue
				// FROM shed_mlo_do_info
				// WHERE user_id='$login_id' AND imp_rot = '$rotNo' AND bl_no = '$blno'";
				// $nextSl = $this->bm->dataReturnDB1($nextSlCountQuery);
				// if($nextSl == 0)update
				// {
					// $nextSl++; //insert
				// }
				// else
				// {
					// $sql_nextSl = "SELECT IF(edo_sl=0,1,edo_sl+1) AS rtnValue
					// FROM shed_mlo_do_info
					// WHERE user_id='$login_id' AND imp_rot = '$rotNo' AND bl_no = '$blno'";
					// $nextSl = $this->bm->dataReturnDB1($sql_nextSl);
				// }

				$edoYr = substr(date("Y"),2);

				$sql_nextSl="SELECT MAX(edo_sl)+1 AS rtnValue
				FROM shed_mlo_do_info
				WHERE edo_mlo='$agentCode' AND edo_year='$edoYr'";
				$nextSl = $this->bm->dataReturnDB1($sql_nextSl);
				

				$slLen = strlen($nextSl);

				if($slLen == 1) $nextSl = "00000".$nextSl;
				else if($slLen == 2) $nextSl = "0000".$nextSl;
				else if($slLen == 3) $nextSl = "000".$nextSl;
				else if($slLen == 4) $nextSl = "00".$nextSl;
				else if($slLen == 5) $nextSl = "0".$nextSl;
				else if($slLen == 6) $nextSl = $nextSl;

				

			}
			else if($org_type_id == 1)
			{
				$mloAgentCodeQuery = "SELECT Agent_Code FROM organization_profiles WHERE id = '$org_id'";
				$mloAgentCodeRslt=$this->bm->dataSelectDB1($mloAgentCodeQuery);

				for($x=0;$x<count($mloAgentCodeRslt);$x++)
				{
					$agentCode = $mloAgentCodeRslt[$x]['Agent_Code'];
				}

				
				if($agentCode == "" || is_null($agentCode))
				{
					$mloAgentCodeQuery = "SELECT mlocode FROM igm_details 
										WHERE Submitee_Org_Id = '$org_id' AND Import_Rotation_No = '$rotNo' AND BL_No = '$blno'";

					$mloAgentCodeRslt=$this->bm->dataSelectDB1($mloAgentCodeQuery);

					for($x=0;$x<count($mloAgentCodeRslt);$x++)
					{
						$agentCode = $mloAgentCodeRslt[$x]['mlocode'];
					}
				}

				$login_id=$this->session->userdata('login_id');
				
				// $nextSlCountQuery = "SELECT count(*) AS rtnValue
				// FROM shed_mlo_do_info
				// WHERE user_id='$login_id' AND imp_rot = '$rotNo' AND bl_no = '$blno'";
				// $nextSl = $this->bm->dataReturnDB1($nextSlCountQuery);
				// if($nextSl == 0)
				// {
					// $nextSl++;
				// }
				// else
				// {
					// $sql_nextSl = "SELECT IF(edo_sl=0,1,edo_sl+1) AS rtnValue
					// FROM shed_mlo_do_info
					// WHERE user_id='$login_id' AND imp_rot = '$rotNo' AND bl_no = '$blno'";
					// $nextSl = $this->bm->dataReturnDB1($sql_nextSl);
				// }
				
				$edoYr = substr(date("Y"),2);
				
				$sql_nextSl="SELECT MAX(edo_sl)+1 AS rtnValue
				FROM shed_mlo_do_info
				WHERE edo_mlo='$agentCode' AND edo_year='$edoYr'";
				$nextSl = $this->bm->dataReturnDB1($sql_nextSl);

				$slLen = strlen($nextSl);

				if($slLen == 1) $nextSl = "00000".$nextSl;
				else if($slLen == 2) $nextSl = "0000".$nextSl;
				else if($slLen == 3) $nextSl = "000".$nextSl;
				else if($slLen == 4) $nextSl = "00".$nextSl;
				else if($slLen == 5) $nextSl = "0".$nextSl;
				else if($slLen == 6) $nextSl = $nextSl;
	
			}
			
			if($igm_type!="BB")
			{
				if(isset($_POST['idchk']))
				{
					$containerChk = $_POST['idchk'];
				}				
			}
			
			
			$login_id = $this->session->userdata('login_id');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			//$type_of_Igm=$this->input->post('type_of_Igm');
			//$CODE=$this->input->post('CODE');
			
			$msg = "";
			
			$sql_do_number="SELECT MAX(do_no)+1 AS rtnValue FROM shed_mlo_do_info";
			$do_no=$this->bm->dataReturnDb1($sql_do_number);
						
			if($this->input->post('update'))
			{
				//update code
				$editId = $this->input->post('editId');
				$updateQuery = "Update shed_mlo_do_info set be_no='$billOfEntryNo',be_date='$billOfEntryDate',office_code='$officeCode',
								remarks='$remarks',valid_upto_dt='$valid_upto',cnf_lic_no='$cnflic',measurement='$measurement' 
								WHERE id='$editId'";
				$res_updateShedMLO = $this->bm->dataUpdateDB1($updateQuery);
				
				$sqlDlt = "DELETE FROM do_upload_wise_container WHERE shed_mlo_do_info_id='$editId'";
				$this->bm->dataDeleteDB1($sqlDlt);
				if($igm_type!="BB")
				{
					if(isset($containerChk))
					{
						foreach ($containerChk as $cCheck)
						{
							 // echo $cCheck;
							 // echo "<br>";
							$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id,valid_upto_date) 
													values('$editId','$cCheck','$valid_upto')";
							$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
						}
					}
				}
				$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
			}
			else if($this->input->post('extend'))
			{
				//extend validity code
				$uploadId = $this->input->post('editId');
				$edo_id = $this->input->post('edo_id');
				$valid_upto=$this->input->post('valid_upto');
				$login_id = $this->session->userdata('login_id');				
				if($igm_type!="BB")
				{
					if($bl_type=="HB" and $igm_type=="GM")
					{
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET vldty_appr_by_mlo_st='0' WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					}
					
					if($bl_type=="MB")
					{
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET vldty_appr_by_mlo_st='1',validity_approved_by_mlo_at=NOW()
												WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					}
					
					$sqlDlt = "DELETE FROM edo_applied_validity_date WHERE edo_id='$edo_id'";
					$this->bm->dataDeleteDB1($sqlDlt);
					if(isset($containerChk))
					{
						foreach ($containerChk as $cCheck)
						{							
							$chkContainer="SELECT COUNT(*) AS rtnValue FROM do_upload_wise_container 
									WHERE shed_mlo_do_info_id='$uploadId' AND cont_igm_id='$cCheck'";
							$cnt_container = $this->bm->dataReturnDb1($chkContainer);
							if($cnt_container==0){
								$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id,valid_upto_date) 
														values('$uploadId','$cCheck','$valid_upto')";
								$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
							
							} else {
								$updateContValididyDt="UPDATE do_upload_wise_container SET valid_upto_date='$valid_upto' 
														WHERE shed_mlo_do_info_id='$uploadId' AND cont_igm_id='$cCheck'";
								$resUpdateContValididyDt = $this->bm->dataUpdateDB1($updateContValididyDt);
							}		
							
							$data['msg'] = "<font color='blue'><strong>Validity Extention Successful</strong></font>";
						}
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET cnf_vldty_appr_st='0'  
												WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					}
					
				}
				
				$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
			}
			else
			{				
				$contSt = $this->input->post('contSt');
				if(($contSt=="FCL" or $contSt=="FCL/PART" or $contSt=="ETY") and $bl_type=="HB" and $igm_type="GM" and ($valid_upto==NULL or $valid_upto=="" or $valid_upto==" "))
				{
					$msg = "<font color='red'><b>Please select valid upto date.</b></font>";
				}
				else
				{
					$edo_id = $this->input->post('edo_id');
					$strUploadQty="select count(*) as rtnValue from shed_mlo_do_info where edo_id='$edo_id'";
					$uploadQty = $this->bm->dataReturnDb1($strUploadQty);
					// $updateUploadSt = "Update edo_application_by_cf set do_upload_st='1' WHERE id='$edo_id'";
					// $res_updateUploadSt = $this->bm->dataUpdateDB1($updateUploadSt);
					// LCL FCL valid_upto
					// $insertQuery="INSERT INTO shed_mlo_do_info(igm_detail_id,imp_rot,bl_no,be_no,be_date,office_code,edo_id,
								// do_no,do_date,valid_upto_dt,cnf_lic_no,bl_type,gross_quantity,
								// delv_quantity,measurement,user_id,upload_time,ip_addr) 
						// VALUES ('$igm_dtl','$rotNo','$blno','$billOfEntryNo','$billOfEntryDate','$officeCode','$edo_id',
								// '$do_no',DATE(NOW()),'$valid_upto','$cnflic','$bl_type','$grossQty',
								// '$deliveredWeight','$measurement',
								// '$login_id',NOW(),'$ipaddr')";
					// $res_insertShedMLO = $this->bm->dataInsertDB1($insertQuery);				
					if($uploadQty ==0)
					{
						if($bl_type=="HB" and $igm_type="GM")
						{
							//House BL
							$ffOrgId = "";
							$ffAINno = "";
							$strFFOrgId = "SELECT * FROM edo_application_by_cf WHERE id='$edo_id'";
							$resFFOrgId=$this->bm->dataSelectDB1($strFFOrgId);
							for($x=0;$x<count($resFFOrgId);$x++)
							{
								$ffOrgId=$resFFOrgId[$x]['ff_org_id'];
							}
							$strFFAINno = "SELECT * FROM organization_profiles WHERE id='$ffOrgId' AND Org_Type_id='4'";
							$resFFAINno=$this->bm->dataSelectDB1($strFFAINno);
							for($z=0;$z<count($resFFAINno);$z++)
							{
								$ffAINno=$resFFAINno[$z]['AIN_No_New'];
							}
							
							$chkBaffaMembership = "SELECT baffa_member AS rtnValue FROM organization_profiles 
												WHERE AIN_No_New='$ffAINno' AND Org_Type_id='4'";
							$baffa_membership = $this->bm->dataReturnDb1($chkBaffaMembership);
							
							$cntRemainingToken="SELECT COUNT(*) AS rtnValue FROM token_distribution 
												WHERE ff_ain='$ffAINno' AND used_st='0'";
							$remainingToken = $this->bm->dataReturnDb1($cntRemainingToken);
							
							if($baffa_membership=="0" or $remainingToken > 0)
							{
								//if(isset($containerChk)){
									$insertQuery="INSERT INTO shed_mlo_do_info(igm_detail_id,imp_rot,bl_no,be_no,be_date,office_code, remarks, edo_id,
									do_no,do_date,edo_mlo,edo_sl,edo_year,valid_upto_dt,cnf_lic_no,bl_type,gross_quantity,
									delv_quantity,measurement,user_id,upload_time,ip_addr) 
									VALUES ('$igm_dtl','$rotNo','$blno','$billOfEntryNo','$billOfEntryDate','$officeCode', '$remarks', '$edo_id',
									'$do_no',DATE(NOW()),'$agentCode','$nextSl','$edoYr','$valid_upto','$cnflic','$bl_type','$grossQty',
									'$deliveredWeight','$measurement',
									'$login_id',NOW(),'$ipaddr')";
									$res_insertShedMLO = $this->bm->dataInsertDB1($insertQuery);
									
									if($res_insertShedMLO==1){
										$updateUploadSt = "Update edo_application_by_cf set do_upload_st='1',ff_assoc_st='1',vldty_appr_by_mlo_st='0'
															WHERE id='$edo_id'";
										$res_updateUploadSt = $this->bm->dataUpdateDB1($updateUploadSt);
									}
									
									
									// Notification generation 
									$edoNotifyCpaQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,life_st,org_notify_by,generate_time)
									VALUES('$edo_id','2591',3,0,0,'$user',NOW())";
									//$this->bm->dataInsertDB1($edoNotifyCpaQuery);
									
									
									$submitted_by_query="SELECT sumitted_by AS rtnValue FROM edo_application_by_cf WHERE id='$edo_id'";
									$submitted_by=$this->bm->dataReturnDb1($submitted_by_query);
									$cf_org_id_query="SELECT org_id  AS rtnValue FROM users WHERE login_id='$submitted_by'";
									$cf_org_id = "";
									$cf_org_id = $this->bm->dataReturnDb1($cf_org_id_query);

													
									$edoNotifyCfQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
									VALUES('$edo_id','$cf_org_id',3,0,'$user',NOW())";
									//$this->bm->dataInsertDB1($edoNotifyCfQuery);

									$mlo_org_id_query="SELECT mlo AS rtnValue FROM edo_application_by_cf WHERE id='$edo_id'";
									$mlo_org_id = "";
									$mlo_org_id=$this->bm->dataReturnDB1($mlo_org_id_query);

									$edoNotifyMloQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
									VALUES('$edo_id','$mlo_org_id',3,0,'$user',NOW())";
									//$this->bm->dataInsertDB1($edoNotifyMloQuery);

									//life state 

									$ffLifeStat = "UPDATE edo_notification SET life_st = 1 WHERE application_id='$edo_id' AND org_notified='$org_id' AND notification_st = 2";
									//$this->bm->dataUpdateDB1($ffLifeStat);

									//life state

									// Notification generation 

									$sqlDoId="SELECT id AS rtnValue FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND bl_no='$blno' AND edo_id='$edo_id'
											ORDER BY id DESC LIMIT 1";
									$doId=$this->bm->dataReturnDb1($sqlDoId);
									if($igm_type!="BB")
									{
										if(isset($containerChk))
										{
											$sqlDlt = "DELETE FROM edo_applied_validity_date WHERE edo_id='$edo_id'";
											$this->bm->dataDeleteDB1($sqlDlt);
											foreach ($containerChk as $cCheck)
											{
												// echo $cCheck;
												// echo "<br>";
												$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id,valid_upto_date) 
																		values('$doId','$cCheck','$valid_upto')";
												$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
											}
										}
									}
									
									if($baffa_membership=="1"){
										$strTokenId = "SELECT id AS rtnValue FROM token_distribution WHERE ff_ain='$ffAINno' AND used_st=0 
														AND edo_id IS NULL ORDER BY id ASC LIMIT 1";					
										$resTokenId=$this->bm->dataReturnDb1($strTokenId);
										$updateTokenSt = "Update token_distribution set used_st='1',edo_id='$edo_id' 
															WHERE id='$resTokenId'";
										$resTokenSt = $this->bm->dataUpdateDB1($updateTokenSt);	
									}									
									
									$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
								// } else {
									// $msg = "<font color='red'><b>Please Select Container!!!</b></font>";
								// }	
							}
							else
							{
								$msg="<font color='red'><b>Sorry! You don't have any token remaining !!</b></font>";
							}
						}
						else
						{
							//Master BL
							//if(isset($containerChk)){
								//insert code
								$insertQuery="INSERT INTO shed_mlo_do_info(igm_detail_id,imp_rot,bl_no,be_no,be_date,office_code, remarks, edo_id,
								do_no,do_date,edo_mlo,edo_sl,edo_year,valid_upto_dt,cnf_lic_no,bl_type,gross_quantity,
								delv_quantity,measurement,user_id,upload_time,ip_addr) 
								VALUES ('$igm_dtl','$rotNo','$blno','$billOfEntryNo','$billOfEntryDate','$officeCode', '$remarks', '$edo_id',
								'$do_no',DATE(NOW()),'$agentCode','$nextSl','$edoYr','$valid_upto','$cnflic','$bl_type','$grossQty',
								'$deliveredWeight','$measurement',
								'$login_id',NOW(),'$ipaddr')";
								$res_insertShedMLO = $this->bm->dataInsertDB1($insertQuery);
								
								if($res_insertShedMLO==1){
									$updateUploadSt = "Update edo_application_by_cf set do_upload_st='1',cnf_vldty_appr_st='0'
														WHERE id='$edo_id'";
									$res_updateUploadSt = $this->bm->dataUpdateDB1($updateUploadSt);
								}
								
									

								// Notification generation 
								$edoNotifyCpaQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,life_st,org_notify_by,generate_time) VALUES('$edo_id','2591',3,0,0,'$user',NOW())";
								//$this->bm->dataInsertDB1($edoNotifyCpaQuery);
							
								$submitted_by_query="SELECT sumitted_by AS rtnValue FROM edo_application_by_cf WHERE id='$edo_id'";
								$submitted_by = "";
								$submitted_by=$this->bm->dataReturnDb1($submitted_by_query);
								$cf_org_id_query="SELECT org_id  AS rtnValue FROM users WHERE login_id='$submitted_by'";
								$cf_org_id = "";
								$cf_org_id=$this->bm->dataReturnDb1($cf_org_id_query);

											
								$edoNotifyCfQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
								VALUES('$edo_id','$cf_org_id',3,0,'$user',NOW())";
								//$this->bm->dataInsertDB1($edoNotifyCfQuery);

								//life state 

								$ffLifeStat = "UPDATE edo_notification SET life_st = 1 WHERE application_id='$edo_id' AND org_notified='$org_id' AND notification_st = 2";
								//$this->bm->dataUpdateDB1($ffLifeStat);

								//life state
								
								// Notification generation
								
								$sqlDoId="SELECT id AS rtnValue FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND bl_no='$blno' AND edo_id='$edo_id'
										ORDER BY id DESC LIMIT 1";
								$doId=$this->bm->dataReturnDb1($sqlDoId);
														
								if($igm_type!="BB")
								{
									
									if(isset($containerChk))
									{
										$sqlDlt = "DELETE FROM edo_applied_validity_date WHERE edo_id='$edo_id'";
										$this->bm->dataDeleteDB1($sqlDlt);
										foreach ($containerChk as $cCheck)
										{							
											
											$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id,valid_upto_date) 
																	values('$doId','$cCheck','$valid_upto')";
											$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
											$data['msg'] = "<font color='blue'><strong>Validity Extention Successful</strong></font>";
										}
									}
								}
								$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
							// } else {
								// $msg = "<font color='red'><b>Please select container!!!</b></font>";
							// }
						}
					}
					else
					{
						$msg = "<font color='red'><b>EDO already uploaded for this Rotation & BL!!!</b></font>";
					}
					
					
					
					// $sqlDoId="SELECT id AS rtnValue FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND bl_no='$blno' ORDER BY id DESC LIMIT 1";
					// $doId=$this->bm->dataReturnDb1($sqlDoId);
					// if($igm_type!="BB")
					// {
						// if(isset($containerChk))
						// {
							// foreach ($containerChk as $cCheck)
							// {
								// $strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id) 
														// values('$doId','$cCheck')";
								// $resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
							// }
						// }
					// }
				}
				
			}
			
			echo $msg;
			/*
			$selectQuery="SELECT id as rtnValue FROM shed_mlo_do_info ORDER BY id DESC LIMIT 1";
			$selectID=$this->bm->dataReturnDb1($selectQuery);
			
			
			
			
			$msgBLsearch = "";
			
			$data['msg']=$msg;
			
			// Going back to the list with-----
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['flag'] = "all"; //To show all do list
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
			*/
		}
	}
	
	function bbEDOUpload_Version_1()
	{		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');		
		$LoginStat = $this->session->userdata('LoginStat');
		$org_type_id =$this->session->userdata('org_Type_id');
		$org_id =$this->session->userdata('org_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			$rotNo=$this->input->post('rotNo');
			$fileRotno=str_replace('/','_',$rotNo);
			$blno=$this->input->post('blno');			
			$blrplc=str_replace("/","_",$blno);
			$line_no=$this->input->post('line_no');
			
			$beno=$this->input->post('beNo');
			$beDate=$this->input->post('beDate');			
			$remarks=$this->input->post('remarks');						
			$cnflic=$this->input->post('cnfLicenseNo');		
			$receipt_no=$this->input->post('receipt_no');	
			$receipt_date=$this->input->post('receipt_date');	
			
			$igm_dtl=$this->input->post('igm_dtl');
			$bl_type=$this->input->post('type_of_bl');			
			$igm_type=$this->input->post('igm_type');			
			$edo_id=$this->input->post('edo_id');			
			$grossQty=$this->input->post('grossQty');			
			$weight=$this->input->post('grossQty');
						
			$agentCode = "";
			$edoYr = substr(date("Y"),2);
			$nextSl = "";
			
			// $nextSl will generate later according to the requirement
			// if($org_type_id == 4)
			// {
				// $ffAgentCodeQuery = "SELECT Agent_Code FROM organization_profiles WHERE id = '$org_id'";
				// $ffAgentCodeRslt=$this->bm->dataSelectDB1($ffAgentCodeQuery);

				// for($x=0;$x<count($ffAgentCodeRslt);$x++){
					// $agentCode = $ffAgentCodeRslt[$x]['Agent_Code'];
				// }

				// $login_id=$this->session->userdata('login_id');

				// $edoYr = substr(date("Y"),2);

				// $sql_nextSl="SELECT MAX(edo_sl)+1 AS rtnValue
				// FROM shed_mlo_do_info
				// WHERE edo_mlo='$agentCode' AND edo_year='$edoYr'";
				// $nextSl = $this->bm->dataReturnDB1($sql_nextSl);
				
				// $slLen = strlen($nextSl);

				// if($slLen == 1) $nextSl = "00000".$nextSl;
				// else if($slLen == 2) $nextSl = "0000".$nextSl;
				// else if($slLen == 3) $nextSl = "000".$nextSl;
				// else if($slLen == 4) $nextSl = "00".$nextSl;
				// else if($slLen == 5) $nextSl = "0".$nextSl;
				// else if($slLen == 6) $nextSl = $nextSl;

			// }
			
			// else if($org_type_id == 1)
			// {
				// $mloAgentCodeQuery = "SELECT Agent_Code FROM organization_profiles WHERE id = '$org_id'";
				// $mloAgentCodeRslt=$this->bm->dataSelectDB1($mloAgentCodeQuery);

				// for($x=0;$x<count($mloAgentCodeRslt);$x++)
				// {
					// $agentCode = $mloAgentCodeRslt[$x]['Agent_Code'];
				// }

				
				// if($agentCode == "" || is_null($agentCode))
				// {
					// $mloAgentCodeQuery = "SELECT mlocode FROM igm_details 
										// WHERE Submitee_Org_Id = '$org_id' AND Import_Rotation_No = '$rotNo' AND BL_No = '$blno'";

					// $mloAgentCodeRslt=$this->bm->dataSelectDB1($mloAgentCodeQuery);

					// for($x=0;$x<count($mloAgentCodeRslt);$x++)
					// {
						// $agentCode = $mloAgentCodeRslt[$x]['mlocode'];
					// }
				// }

				// $login_id=$this->session->userdata('login_id');
								
				// $edoYr = substr(date("Y"),2);
				
				// $sql_nextSl="SELECT MAX(edo_sl)+1 AS rtnValue
				// FROM shed_mlo_do_info
				// WHERE edo_mlo='$agentCode' AND edo_year='$edoYr'";
				// $nextSl = $this->bm->dataReturnDB1($sql_nextSl);

				// $slLen = strlen($nextSl);

				// if($slLen == 1) $nextSl = "00000".$nextSl;
				// else if($slLen == 2) $nextSl = "0000".$nextSl;
				// else if($slLen == 3) $nextSl = "000".$nextSl;
				// else if($slLen == 4) $nextSl = "00".$nextSl;
				// else if($slLen == 5) $nextSl = "0".$nextSl;
				// else if($slLen == 6) $nextSl = $nextSl;
	
			// }
					
			
			$login_id = $this->session->userdata('login_id');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			$msg = "";
			
			$sql_do_number="SELECT MAX(do_no)+1 AS rtnValue FROM shed_mlo_do_info";
			$do_no=$this->bm->dataReturnDb1($sql_do_number);
						
			if($this->input->post('update'))
			{
				//update code
				$editId = $this->input->post('editId');
				$updateQuery = "Update shed_mlo_do_info set be_no='$beno',be_date='$beDate',line_no='$line_no',receipt_no='$receipt_no',
								receipt_date='$receipt_date',remarks='$remarks' 
								WHERE id='$editId'";
				$res_updateShedMLO = $this->bm->dataUpdateDB1($updateQuery);
								
				$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
			}
			else if($this->input->post('extend'))
			{
				//extend validity code
				$uploadId = $this->input->post('editId');
				$edo_id = $this->input->post('edo_id');
				$valid_upto=$this->input->post('valid_upto');
				$login_id = $this->session->userdata('login_id');				
				if($igm_type!="BB")
				{
					if($bl_type=="HB" and $igm_type=="GM")
					{
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET vldty_appr_by_mlo_st='0' WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					}
					
					if($bl_type=="MB")
					{
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET vldty_appr_by_mlo_st='1',validity_approved_by_mlo_at=NOW()
												WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					}
					
					$sqlDlt = "DELETE FROM edo_applied_validity_date WHERE edo_id='$edo_id'";
					$this->bm->dataDeleteDB1($sqlDlt);
					if(isset($containerChk))
					{
						foreach ($containerChk as $cCheck)
						{							
							$chkContainer="SELECT COUNT(*) AS rtnValue FROM do_upload_wise_container 
									WHERE shed_mlo_do_info_id='$uploadId' AND cont_igm_id='$cCheck'";
							$cnt_container = $this->bm->dataReturnDb1($chkContainer);
							if($cnt_container==0){
								$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id,valid_upto_date) 
														values('$uploadId','$cCheck','$valid_upto')";
								$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
							
							} else {
								$updateContValididyDt="UPDATE do_upload_wise_container SET valid_upto_date='$valid_upto' 
														WHERE shed_mlo_do_info_id='$uploadId' AND cont_igm_id='$cCheck'";
								$resUpdateContValididyDt = $this->bm->dataUpdateDB1($updateContValididyDt);
							}		
							
							$data['msg'] = "<font color='blue'><strong>Validity Extention Successful</strong></font>";
						}
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET cnf_vldty_appr_st='0'  
												WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					}
					
				}
				
				$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
			}
			else
			{		
				$edo_id = $this->input->post('edo_id');
				$strUploadQty="select count(*) as rtnValue from shed_mlo_do_info where edo_id='$edo_id'";
				$uploadQty = $this->bm->dataReturnDb1($strUploadQty);
							
				if($uploadQty ==0)
				{					
					$insertQuery="INSERT INTO shed_mlo_do_info(igm_detail_id,imp_rot,bl_no,be_no,be_date, remarks, edo_id,do_no,do_date,
									edo_sl,edo_year,cnf_lic_no,bl_type,gross_quantity,delv_quantity,line_no,receipt_no,receipt_date,user_id,
									upload_time,ip_addr) 
					VALUES ('$igm_dtl','$rotNo','$blno','$beno','$beDate', '$remarks', '$edo_id','$do_no',DATE(NOW()),
					'$nextSl','$edoYr','$cnflic','$bl_type','$grossQty','$weight','$line_no','$receipt_no','$receipt_date','$login_id',
					NOW(),'$ipaddr')";
					$res_insertShedMLO = $this->bm->dataInsertDB1($insertQuery);
					
					if($res_insertShedMLO==1){
						$updateUploadSt = "Update edo_application_by_cf set do_upload_st='1',cnf_vldty_appr_st='0'
											WHERE id='$edo_id'";
						$res_updateUploadSt = $this->bm->dataUpdateDB1($updateUploadSt);
					}
					
						

					// Notification generation 
					$edoNotifyCpaQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,life_st,org_notify_by,generate_time) VALUES('$edo_id','2591',3,0,0,'$user',NOW())";
					//$this->bm->dataInsertDB1($edoNotifyCpaQuery);
				
					$submitted_by_query="SELECT sumitted_by AS rtnValue FROM edo_application_by_cf WHERE id='$edo_id'";
					$submitted_by = "";
					$submitted_by=$this->bm->dataReturnDb1($submitted_by_query);
					$cf_org_id_query="SELECT org_id  AS rtnValue FROM users WHERE login_id='$submitted_by'";
					$cf_org_id = "";
					$cf_org_id=$this->bm->dataReturnDb1($cf_org_id_query);

								
					$edoNotifyCfQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
					VALUES('$edo_id','$cf_org_id',3,0,'$user',NOW())";
					//$this->bm->dataInsertDB1($edoNotifyCfQuery);

					//life state 

					$ffLifeStat = "UPDATE edo_notification SET life_st = 1 WHERE application_id='$edo_id' AND org_notified='$org_id' AND notification_st = 2";
					//$this->bm->dataUpdateDB1($ffLifeStat);

					//life state
					
					// Notification generation
					
					$sqlDoId="SELECT id AS rtnValue FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND bl_no='$blno' AND edo_id='$edo_id'
							ORDER BY id DESC LIMIT 1";
					$doId=$this->bm->dataReturnDb1($sqlDoId);
					
					$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
					
				}
				else
				{
					$msg = "<font color='red'><b>EDO already uploaded for this Rotation & BL!!!</b></font>";
				}
								
				
			}
			
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO List";
			$data['msg'] = $msg;
			$data['flag'] = "all"; //To show all do list
			$data['cpa_search'] = 0; 
			$data['searchBy']="";
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
			
		}
	}
	
	function bbEDOUpload()
	{		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');		
		$LoginStat = $this->session->userdata('LoginStat');
		$org_type_id =$this->session->userdata('org_Type_id');
		$org_id =$this->session->userdata('org_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			$igm_dtl=$this->input->post('igm_dtl');
			$blno=$this->input->post('blno');
			$blrplc=str_replace("/","_",$blno);
			$rotNo=$this->input->post('rotno');
			$fileRotno=str_replace('/','_',$rotNo);
			$beno=$this->input->post('beno');
			$billOfEntryNo=$this->input->post('billOfEntryNo');
			$billOfEntryDate=$this->input->post('billOfEntryDate');
			$officeCode=$this->input->post('officeCode');
			$remarks=$this->input->post('remarks');
			$valid_upto=$this->input->post('valid_upto');			
			$cnflic=$this->input->post('cnflic');			
			$bl_type=$this->input->post('type_of_bl');			
			$igm_type=$this->input->post('igm_type');			
			$grossQty=$this->input->post('grossQty');
			$deliveredWeight=$this->input->post('deliveredWeight');
			$measurement=$this->input->post('measurement');
			$gross_weight=$this->input->post('gross_weight');
			$net_weight=$this->input->post('net_weight');
			$r_no=$this->input->post('r_no');
			$r_no_date=$this->input->post('r_no_date');			
			$agentCode = "";
			$edoYr = "";
			$nextSl = "";
			
			if($org_type_id == 4)
			{
				$ffAgentCodeQuery = "SELECT Agent_Code FROM organization_profiles WHERE id = '$org_id'";
				$ffAgentCodeRslt=$this->bm->dataSelectDB1($ffAgentCodeQuery);

				for($x=0;$x<count($ffAgentCodeRslt);$x++){
					$agentCode = $ffAgentCodeRslt[$x]['Agent_Code'];
				}

				$login_id=$this->session->userdata('login_id');

				// $nextSlCountQuery = "SELECT count(*) AS rtnValue
				// FROM shed_mlo_do_info
				// WHERE user_id='$login_id' AND imp_rot = '$rotNo' AND bl_no = '$blno'";
				// $nextSl = $this->bm->dataReturnDB1($nextSlCountQuery);
				// if($nextSl == 0)update
				// {
					// $nextSl++; //insert
				// }
				// else
				// {
					// $sql_nextSl = "SELECT IF(edo_sl=0,1,edo_sl+1) AS rtnValue
					// FROM shed_mlo_do_info
					// WHERE user_id='$login_id' AND imp_rot = '$rotNo' AND bl_no = '$blno'";
					// $nextSl = $this->bm->dataReturnDB1($sql_nextSl);
				// }

				$edoYr = substr(date("Y"),2);

				$sql_nextSl="SELECT MAX(edo_sl)+1 AS rtnValue
				FROM shed_mlo_do_info
				WHERE edo_mlo='$agentCode' AND edo_year='$edoYr'";
				$nextSl = $this->bm->dataReturnDB1($sql_nextSl);
				

				$slLen = strlen($nextSl);

				if($slLen == 1) $nextSl = "00000".$nextSl;
				else if($slLen == 2) $nextSl = "0000".$nextSl;
				else if($slLen == 3) $nextSl = "000".$nextSl;
				else if($slLen == 4) $nextSl = "00".$nextSl;
				else if($slLen == 5) $nextSl = "0".$nextSl;
				else if($slLen == 6) $nextSl = $nextSl;

				

			}
			else if($org_type_id == 1)
			{
				$mloAgentCodeQuery = "SELECT Agent_Code FROM organization_profiles WHERE id = '$org_id'";
				$mloAgentCodeRslt=$this->bm->dataSelectDB1($mloAgentCodeQuery);

				for($x=0;$x<count($mloAgentCodeRslt);$x++)
				{
					$agentCode = $mloAgentCodeRslt[$x]['Agent_Code'];
				}

				
				if($agentCode == "" || is_null($agentCode))
				{
					$mloAgentCodeQuery = "SELECT mlocode FROM igm_details 
										WHERE Submitee_Org_Id = '$org_id' AND Import_Rotation_No = '$rotNo' AND BL_No = '$blno'";

					$mloAgentCodeRslt=$this->bm->dataSelectDB1($mloAgentCodeQuery);

					for($x=0;$x<count($mloAgentCodeRslt);$x++)
					{
						$agentCode = $mloAgentCodeRslt[$x]['mlocode'];
					}
				}

				$login_id=$this->session->userdata('login_id');
				
				// $nextSlCountQuery = "SELECT count(*) AS rtnValue
				// FROM shed_mlo_do_info
				// WHERE user_id='$login_id' AND imp_rot = '$rotNo' AND bl_no = '$blno'";
				// $nextSl = $this->bm->dataReturnDB1($nextSlCountQuery);
				// if($nextSl == 0)
				// {
					// $nextSl++;
				// }
				// else
				// {
					// $sql_nextSl = "SELECT IF(edo_sl=0,1,edo_sl+1) AS rtnValue
					// FROM shed_mlo_do_info
					// WHERE user_id='$login_id' AND imp_rot = '$rotNo' AND bl_no = '$blno'";
					// $nextSl = $this->bm->dataReturnDB1($sql_nextSl);
				// }
				
				$edoYr = substr(date("Y"),2);
				
				$sql_nextSl="SELECT MAX(edo_sl)+1 AS rtnValue
				FROM shed_mlo_do_info
				WHERE edo_mlo='$agentCode' AND edo_year='$edoYr'";
				$nextSl = $this->bm->dataReturnDB1($sql_nextSl);

				$slLen = strlen($nextSl);

				if($slLen == 1) $nextSl = "00000".$nextSl;
				else if($slLen == 2) $nextSl = "0000".$nextSl;
				else if($slLen == 3) $nextSl = "000".$nextSl;
				else if($slLen == 4) $nextSl = "00".$nextSl;
				else if($slLen == 5) $nextSl = "0".$nextSl;
				else if($slLen == 6) $nextSl = $nextSl;
	
			}
			else if($org_type_id == 10 || $org_type_id == 57 || $org_type_id == 93)
			{
				$mloAgentCodeQuery = "SELECT Agent_Code FROM organization_profiles WHERE id = '$org_id'";
				$mloAgentCodeRslt=$this->bm->dataSelectDB1($mloAgentCodeQuery);

				for($x=0;$x<count($mloAgentCodeRslt);$x++)
				{
					$agentCode = $mloAgentCodeRslt[$x]['Agent_Code'];
				}

				
				if($agentCode == "" || is_null($agentCode))
				{
					$mloAgentCodeQuery = "SELECT mlocode FROM igm_details 
										WHERE Submitee_Org_Id = '$org_id' AND Import_Rotation_No = '$rotNo' AND BL_No = '$blno'";

					$mloAgentCodeRslt=$this->bm->dataSelectDB1($mloAgentCodeQuery);

					for($x=0;$x<count($mloAgentCodeRslt);$x++)
					{
						$agentCode = $mloAgentCodeRslt[$x]['mlocode'];
					}
				}

				$login_id=$this->session->userdata('login_id');
				
				// $nextSlCountQuery = "SELECT count(*) AS rtnValue
				// FROM shed_mlo_do_info
				// WHERE user_id='$login_id' AND imp_rot = '$rotNo' AND bl_no = '$blno'";
				// $nextSl = $this->bm->dataReturnDB1($nextSlCountQuery);
				// if($nextSl == 0)
				// {
					// $nextSl++;
				// }
				// else
				// {
					// $sql_nextSl = "SELECT IF(edo_sl=0,1,edo_sl+1) AS rtnValue
					// FROM shed_mlo_do_info
					// WHERE user_id='$login_id' AND imp_rot = '$rotNo' AND bl_no = '$blno'";
					// $nextSl = $this->bm->dataReturnDB1($sql_nextSl);
				// }
				
				$edoYr = substr(date("Y"),2);
				
				$sql_nextSl="SELECT MAX(edo_sl)+1 AS rtnValue
				FROM shed_mlo_do_info
				WHERE edo_mlo='$agentCode' AND edo_year='$edoYr'";
				$nextSl = $this->bm->dataReturnDB1($sql_nextSl);

				$slLen = strlen($nextSl);

				if($slLen == 1) $nextSl = "00000".$nextSl;
				else if($slLen == 2) $nextSl = "0000".$nextSl;
				else if($slLen == 3) $nextSl = "000".$nextSl;
				else if($slLen == 4) $nextSl = "00".$nextSl;
				else if($slLen == 5) $nextSl = "0".$nextSl;
				else if($slLen == 6) $nextSl = $nextSl;
	
			}
			
			if($igm_type!="BB")
			{
				if(isset($_POST['idchk']))
				{
					$containerChk = $_POST['idchk'];
				}				
			}
			
			
			$login_id = $this->session->userdata('login_id');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			//$type_of_Igm=$this->input->post('type_of_Igm');
			//$CODE=$this->input->post('CODE');
			
			$msg = "";
			
			$sql_do_number="SELECT MAX(do_no)+1 AS rtnValue FROM shed_mlo_do_info";
			$do_no=$this->bm->dataReturnDb1($sql_do_number);
						
			if($this->input->post('update'))
			{
				//update code
				$editId = $this->input->post('editId');
				$updateQuery = "Update shed_mlo_do_info set be_no='$billOfEntryNo',be_date='$billOfEntryDate',r_no='$r_no',
								r_no_date='$r_no_date',remarks='$remarks',valid_upto_dt='$valid_upto',cnf_lic_no='$cnflic',gross_weight='$gross_weight',
								net_weight='$net_weight'
								WHERE id='$editId'";
				$res_updateShedMLO = $this->bm->dataUpdateDB1($updateQuery);
				
				$sqlDlt = "DELETE FROM do_upload_wise_container WHERE shed_mlo_do_info_id='$editId'";
				$this->bm->dataDeleteDB1($sqlDlt);
				if($igm_type!="BB")
				{
					if(isset($containerChk))
					{
						foreach ($containerChk as $cCheck)
						{
							 // echo $cCheck;
							 // echo "<br>";
							$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id,valid_upto_date) 
													values('$editId','$cCheck','$valid_upto')";
							$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
						}
					}
				}
				$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
			}
			else if($this->input->post('extend'))
			{
				//extend validity code
				$uploadId = $this->input->post('editId');
				$edo_id = $this->input->post('edo_id');
				$valid_upto=$this->input->post('valid_upto');
				$login_id = $this->session->userdata('login_id');				
				if($igm_type!="BB")
				{
					if($bl_type=="HB" and $igm_type=="GM")
					{
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET vldty_appr_by_mlo_st='0' WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					}
					
					if($bl_type=="MB")
					{
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET vldty_appr_by_mlo_st='1',validity_approved_by_mlo_at=NOW()
												WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					}
					
					$sqlDlt = "DELETE FROM edo_applied_validity_date WHERE edo_id='$edo_id'";
					$this->bm->dataDeleteDB1($sqlDlt);
					if(isset($containerChk))
					{
						foreach ($containerChk as $cCheck)
						{							
							$chkContainer="SELECT COUNT(*) AS rtnValue FROM do_upload_wise_container 
									WHERE shed_mlo_do_info_id='$uploadId' AND cont_igm_id='$cCheck'";
							$cnt_container = $this->bm->dataReturnDb1($chkContainer);
							if($cnt_container==0){
								$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id,valid_upto_date) 
														values('$uploadId','$cCheck','$valid_upto')";
								$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
							
							} else {
								$updateContValididyDt="UPDATE do_upload_wise_container SET valid_upto_date='$valid_upto' 
														WHERE shed_mlo_do_info_id='$uploadId' AND cont_igm_id='$cCheck'";
								$resUpdateContValididyDt = $this->bm->dataUpdateDB1($updateContValididyDt);
							}		
							
							$data['msg'] = "<font color='blue'><strong>Validity Extention Successful</strong></font>";
						}
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET cnf_vldty_appr_st='0'  
												WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					}
					
				}
				
				$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
			}
			else
			{				
				$contSt = $this->input->post('contSt');
				if(($contSt=="FCL" or $contSt=="FCL/PART" or $contSt=="ETY") and $bl_type=="HB" and $igm_type="GM" and ($valid_upto==NULL or $valid_upto=="" or $valid_upto==" "))
				{
					$msg = "<font color='red'><b>Please select valid upto date.</b></font>";
				}
				else
				{
					$edo_id = $this->input->post('edo_id');
					$strUploadQty="select count(*) as rtnValue from shed_mlo_do_info where edo_id='$edo_id'";
					$uploadQty = $this->bm->dataReturnDb1($strUploadQty);
					// $updateUploadSt = "Update edo_application_by_cf set do_upload_st='1' WHERE id='$edo_id'";
					// $res_updateUploadSt = $this->bm->dataUpdateDB1($updateUploadSt);
					// LCL FCL valid_upto
					// $insertQuery="INSERT INTO shed_mlo_do_info(igm_detail_id,imp_rot,bl_no,be_no,be_date,office_code,edo_id,
								// do_no,do_date,valid_upto_dt,cnf_lic_no,bl_type,gross_quantity,
								// delv_quantity,measurement,user_id,upload_time,ip_addr) 
						// VALUES ('$igm_dtl','$rotNo','$blno','$billOfEntryNo','$billOfEntryDate','$officeCode','$edo_id',
								// '$do_no',DATE(NOW()),'$valid_upto','$cnflic','$bl_type','$grossQty',
								// '$deliveredWeight','$measurement',
								// '$login_id',NOW(),'$ipaddr')";
					// $res_insertShedMLO = $this->bm->dataInsertDB1($insertQuery);				
					if($uploadQty ==0)
					{
						//Master BL
						//if(isset($containerChk)){
						//insert code
						$insertQuery="INSERT INTO shed_mlo_do_info(igm_detail_id,imp_rot,bl_no,be_no,be_date,r_no,r_no_date, remarks, edo_id,
						do_no,do_date,edo_mlo,edo_sl,edo_year,valid_upto_dt,cnf_lic_no,bl_type,gross_quantity,
						delv_quantity,measurement,gross_weight,net_weight,user_id,upload_time,ip_addr) 
						VALUES ('$igm_dtl','$rotNo','$blno','$billOfEntryNo','$billOfEntryDate','$r_no','$r_no_date', '$remarks', '$edo_id',
						'$do_no',DATE(NOW()),'$agentCode','$nextSl','$edoYr','$valid_upto','$cnflic','$bl_type','$grossQty',
						'$deliveredWeight','$measurement','$gross_weight','$net_weight',
						'$login_id',NOW(),'$ipaddr')";
						$res_insertShedMLO = $this->bm->dataInsertDB1($insertQuery);
						
						if($res_insertShedMLO==1){
							$updateUploadSt = "Update edo_application_by_cf set do_upload_st='1',cnf_vldty_appr_st='0'
												WHERE id='$edo_id'";
							$res_updateUploadSt = $this->bm->dataUpdateDB1($updateUploadSt);
						}
						
							

						// Notification generation 
						$edoNotifyCpaQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,life_st,org_notify_by,generate_time) VALUES('$edo_id','2591',3,0,0,'$user',NOW())";
						//$this->bm->dataInsertDB1($edoNotifyCpaQuery);
					
						$submitted_by_query="SELECT sumitted_by AS rtnValue FROM edo_application_by_cf WHERE id='$edo_id'";
						$submitted_by = "";
						$submitted_by=$this->bm->dataReturnDb1($submitted_by_query);
						$cf_org_id_query="SELECT org_id  AS rtnValue FROM users WHERE login_id='$submitted_by'";
						$cf_org_id = "";
						$cf_org_id=$this->bm->dataReturnDb1($cf_org_id_query);

									
						$edoNotifyCfQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
						VALUES('$edo_id','$cf_org_id',3,0,'$user',NOW())";
						//$this->bm->dataInsertDB1($edoNotifyCfQuery);

						//life state 

						$ffLifeStat = "UPDATE edo_notification SET life_st = 1 WHERE application_id='$edo_id' AND org_notified='$org_id' AND notification_st = 2";
						//$this->bm->dataUpdateDB1($ffLifeStat);

						//life state
						
						// Notification generation
						
						$sqlDoId="SELECT id AS rtnValue FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND bl_no='$blno' AND edo_id='$edo_id'
								ORDER BY id DESC LIMIT 1";
						$doId=$this->bm->dataReturnDb1($sqlDoId);
												
						if($igm_type!="BB")
						{
							
							if(isset($containerChk))
							{
								$sqlDlt = "DELETE FROM edo_applied_validity_date WHERE edo_id='$edo_id'";
								$this->bm->dataDeleteDB1($sqlDlt);
								foreach ($containerChk as $cCheck)
								{							
									
									$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id,valid_upto_date) 
															values('$doId','$cCheck','$valid_upto')";
									$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
									$data['msg'] = "<font color='blue'><strong>Validity Extention Successful</strong></font>";
								}
							}
						}
						$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
						// } else {
							// $msg = "<font color='red'><b>Please select container!!!</b></font>";
						// }
					}
					else
					{
						$msg = "<font color='red'><b>EDO already uploaded for this Rotation & BL!!!</b></font>";
					}
					
					
					
					// $sqlDoId="SELECT id AS rtnValue FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND bl_no='$blno' ORDER BY id DESC LIMIT 1";
					// $doId=$this->bm->dataReturnDb1($sqlDoId);
					// if($igm_type!="BB")
					// {
						// if(isset($containerChk))
						// {
							// foreach ($containerChk as $cCheck)
							// {
								// $strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id) 
														// values('$doId','$cCheck')";
								// $resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
							// }
						// }
					// }
				}
				
			}
			
			echo $msg;
			/*
			$selectQuery="SELECT id as rtnValue FROM shed_mlo_do_info ORDER BY id DESC LIMIT 1";
			$selectID=$this->bm->dataReturnDb1($selectQuery);
			
			
			
			
			$msgBLsearch = "";
			
			$data['msg']=$msg;
			
			// Going back to the list with-----
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['flag'] = "all"; //To show all do list
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
			*/
		}
	}
	
	function updateStatforEDO()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$org_notify_by = $this->session->userdata('login_id');
		$mlo_org_id =$this->session->userdata('org_id');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$login_id = $this->session->userdata('login_id');
			$data['title']="EDO Application List";
			//$data['flag'] = "pending"; //To show all do list valid_upto_date
			
			$edo_id = $this->input->post("clearanceEDOId");
					
			$data['flag'] = "pending"; //To show all do list$data['flag'] = "all"; //To show all do list
			$data['searchBy'] = "";
			$data['searchInput'] = "";
			$data['cpa_search'] = 0;
			
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			$mlo_id = "";
			$cont_st = "";
			$mbl_of_hbl = "";
			$org_notified="";
			$submitted_by="";
			$msg="";
			$queryEDODtls="select * from edo_application_by_cf where id='$edo_id'";
			$edoDtls = $this->bm->dataSelectDB1($queryEDODtls);

			for($i=0;$i<count($edoDtls);$i++)
			{
				$mlo_id = $edoDtls[$i]['mlo'];
				$cont_st = $edoDtls[$i]['cont_status'];
				$mbl_of_hbl = $edoDtls[$i]['mbl_of_hbl'];
				$org_notified = $edoDtls[$i]['ff_org_id'];
				$submitted_by = $edoDtls[$i]['sumitted_by'];
			}
			if($cont_st=="LCL")
			{
				$query = "UPDATE edo_application_by_cf SET ff_stat='1',ff_clearance_time=NOW(),forwarded_by='$login_id',forwarded_org_id='$mlo_id'
					WHERE id='$edo_id'";					
				$update_st=$this->bm->dataUpdateDB1($query);
				if($update_st==1)
				{									
					$queryClearPendingApplications = "UPDATE edo_application_by_cf SET ff_stat='1',ff_clearance_time=NOW(),
													forwarded_by='$login_id',forwarded_org_id='$mlo_id'
													WHERE mbl_of_hbl='$mbl_of_hbl' and ff_stat='0'";					
					$update_st_clearPendingApplications=$this->bm->dataUpdateDB1($queryClearPendingApplications);
				
					$insertQuery="INSERT INTO cleared_mbl_by_mlo(master_bl,clearance_st,entry_time,entry_ip_address,entered_by,
																cleared_by_org_id,cleared_by,clearance_time,clearance_ip) 
								VALUES ('$mbl_of_hbl','1',NOW(),'$ipaddr','$login_id','$mlo_org_id','$login_id',NOW(),'$ipaddr')";
					$resInsert = $this->bm->dataInsertDB1($insertQuery);
					if($resInsert)
					{
						$data['msg']='<font color=blue>Forwarded Successfully.</font>';
					}
					else
					{
						$data['msg']='<font color=red>Forwarding Failed.</font>';
					}
				}
				else
				{
					$data['msg']='<font color=red>Forwarding Failed.</font>';
				}
			}
			else
			{
				//FCL OR FCL/PART OR ETY
				$valid_upto_date = $this->input->post("valid_upto_date");
				$query = "UPDATE edo_application_by_cf SET ff_stat='1',ff_clearance_time=NOW(),forwarded_by='$login_id',
						forwarded_org_id='$mlo_id',valid_upto_dt_by_mlo='$valid_upto_date',
						applied_valid_dt='$valid_upto_date' 
						WHERE id='$edo_id'";
				$update_st=$this->bm->dataUpdateDB1($query);
				if($update_st==1){
					
					$queryClearPendingApplications = "UPDATE edo_application_by_cf SET ff_stat='1',ff_clearance_time=NOW(),
						forwarded_by='$login_id',forwarded_org_id='$mlo_id',valid_upto_dt_by_mlo='$valid_upto_date',
						applied_valid_dt='$valid_upto_date' 
						WHERE mbl_of_hbl='$mbl_of_hbl' and ff_stat='0'";					
					$update_st_clearPendingApplications=$this->bm->dataUpdateDB1($queryClearPendingApplications);
					
					$insertQuery="INSERT INTO cleared_mbl_by_mlo(master_bl,clearance_st,valid_upto_dt,entry_time,entry_ip_address,entered_by,
																cleared_by_org_id,cleared_by,clearance_time,clearance_ip) 
								VALUES ('$mbl_of_hbl','1','$valid_upto_date',NOW(),'$ipaddr','$login_id','$mlo_org_id','$login_id',NOW(),'$ipaddr')";
					$resInsert = $this->bm->dataInsertDB1($insertQuery);
					if($resInsert)
					{
						$data['msg']='<font color=blue>Forwarded Successfully.</font>';
					}
					else
					{
						$data['msg']='<font color=red>Forwarding Failed.</font>';
					}
					
					// $queryUpdate = "UPDATE cleared_mbl_by_mlo SET clearance_st='1',valid_upto_dt='$valid_upto_date',
									// cleared_by='$login_id',clearance_time=NOW(),clearance_ip='$ipaddr',cleared_by_org_id='$mlo_id'
									// WHERE master_bl='$mbl_of_hbl'";
					// $resUpdate=$this->bm->dataUpdateDB1($queryUpdate);					
					// $data['msg']='<font color=blue>Forwarded Successfully.</font>';
				} else {
					$data['msg']='<font color=red>Forwarding Failed.</font>';
				}
				//...
				// $valid_upto_date = $this->input->post("valid_upto_date");
				// $query = "UPDATE edo_application_by_cf SET ff_stat='1',ff_clearance_time=NOW(),
					// valid_upto_dt_by_mlo='$valid_upto_date',applied_valid_dt='$valid_upto_date'
					// WHERE id='$edo_id'";  //applied_valid_dt='$valid_upto_date' is new

				// $update_st=$this->bm->dataUpdateDB1($query);
				// if($update_st==1)
					// $data['msg']='<font color=blue>Forwarded Successfully.</font>';
				// else
					// $data['msg']='<font color=red>Forwarding Failed.</font>';
			}

			//// Generate Notification starts

			$edoNotifyFFQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,life_st,org_notify_by,generate_time)
			VALUES('$edo_id','$org_notified',2,0,0,'$org_notify_by',NOW())";
			//$this->bm->dataInsertDB1($edoNotifyFFQuery);
			
			$cf_orgQuery="SELECT org_id  AS rtnValue FROM users WHERE login_id='$submitted_by'";
			$cf_org_id = "";
			$cf_org_id=$this->bm->dataReturnDb1($cf_orgQuery);
							
			$edoNotifyCFQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
			VALUES('$edo_id','$cf_org_id',2,0,'$org_notify_by',NOW())";
			//$this->bm->dataInsertDB1($edoNotifyCFQuery);

			// Life state update 

			$ffLifeStatQuery = "UPDATE edo_notification SET life_st = 1 WHERE application_id='$edo_id' AND org_notified='$mlo_org_id' AND notification_st = 1";
			//$this->bm->dataUpdateDB1($ffLifeStatQuery);

			// Life state update 

			//// Generate Notification ends
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function updateVaildityDateByMloForHB()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_notify_by = $this->session->userdata('login_id');
		$mlo_org_id =$this->session->userdata('org_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$login_id = $this->session->userdata('login_id');
			$data['title']="EDO Application List";
			
			$data['flag'] = "pending"; //To show all do list valid_upto_date
			$data['searchBy'] = "";
			$data['searchInput'] = "";
			$data['cpa_search'] = 0;
			
			$edo_id = $this->input->post("validityEdoId");
			$valid_upto_date = $this->input->post("valid_upto_date");
			
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			$mlo_id = "";
			$cont_st = "";
			$mbl_of_hbl = "";
			$org_notified="";
			$submitted_by="";
			$msg="";
			$queryEDODtls="select * from edo_application_by_cf where id='$edo_id'";
			$edoDtls = $this->bm->dataSelectDB1($queryEDODtls);

			for($i=0;$i<count($edoDtls);$i++)
			{
				$mlo_id = $edoDtls[$i]['mlo'];
				$cont_st = $edoDtls[$i]['cont_status'];
				$mbl_of_hbl = $edoDtls[$i]['mbl_of_hbl'];
				$org_notified = $edoDtls[$i]['ff_org_id'];
				$submitted_by = $edoDtls[$i]['sumitted_by'];
				$valid_upto_dt_by_mlo=$edoDtls[$i]['valid_upto_dt_by_mlo'];
				$applied_valid_dt=$edoDtls[$i]['applied_valid_dt'];
			}
			if($cont_st=="LCL")
			{
				//LCL
			}
			else
			{
				//FCL OR FCL/PART OR ETY
				
				$query = "UPDATE edo_application_by_cf SET valid_upto_dt_by_mlo='$valid_upto_date',applied_valid_dt='$valid_upto_date' 
						WHERE id='$edo_id'";
				$update_st=$this->bm->dataUpdateDB1($query);
				if($update_st==1){
					$logInsertQuery="INSERT INTO edo_mlo_extnd_validty_log(edo_id,prev_validity_dt,new_validity_dt,updated_at,updated_by)
									VALUES('$edo_id','$valid_upto_dt_by_mlo','$valid_upto_date',NOW(),'$login_id')";
					$reslogInsertQuery = $this->bm->dataInsertDB1($logInsertQuery);
					
					if($reslogInsertQuery)
					{
						$data['msg']='<font color=blue>Validity has been extended successfully.</font>';
					}
					else
					{
						$data['msg']='<font color=red>Validity extention failed.</font>';
					}
				} else {
					$data['msg']='<font color=red>Validity extention failed.</font>';
				}
				
			}
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function updateEDORejectStatus()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			//$data['org_Type_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			
			$organization_id =$this->session->userdata('org_id');
			$login_id =$this->session->userdata('login_id');
			
			$eid = $this->input->post("eid");
			$flag = $this->input->post("flag");
			$cpa_search = $this->input->post("cpa_search");
			
			$searchBy = $this->input->post("searchBy");
			$searchInput = $this->input->post("searchInput");
			$searched_be_dt = $this->input->post("searched_be_dt");
			
			$data['flag'] = $flag; //To show all do list$data['flag'] = "all"; //To show all do list
			$data['searchBy'] = $searchBy;
			$data['searchInput'] = $searchInput;
			$data['searched_be_dt'] = $searched_be_dt;
			$data['cpa_search'] = $cpa_search;
			
			
			//$rejection_remarks = $this->input->post("rejection_remarks");
			$rejection_remarks = str_replace("'","\'",$this->input->post("rejection_remarks"));
			
			$query = "UPDATE edo_application_by_cf SET rejection_st='1',rejection_time=NOW(),rejection_remarks='$rejection_remarks',
						rejected_by_org='$organization_id', rejected_by_user='$login_id'
						WHERE id='$eid'";
			$update_st=$this->bm->dataUpdateDB1($query);
			if($update_st==1)
				$data['msg']='<font color=blue><b>Rejected Successfully.</b></font>';
			else
				$data['msg']='<font color=red><b>Rejection Failed.</b></font>';
			
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function withdrawRejectionStatus()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			
			$data['title']="EDO Application List";
			
			
			//$data['cpa_search'] = 0; 
			$data['searchBy']="";
			$data['searchInput']="";
			
			$eid = $this->input->post("withdrawal_edo_id");
			$flag = $this->input->post("flag");
			$cpa_search = $this->input->post("cpa_search");
			$searchBy = $this->input->post("searchBy");
			$searchInput = $this->input->post("searchInput");
			$searched_be_dt = $this->input->post("searched_be_dt");
			
			$data['flag'] = $flag; //To show all do list$data['flag'] = "all"; //To show all do list
			$data['searchBy'] = $searchBy;
			$data['searchInput'] = $searchInput;
			$data['searched_be_dt'] = $searched_be_dt;
			$data['cpa_search'] = $cpa_search;
			
			$organization_id =$this->session->userdata('org_id');
			$login_id =$this->session->userdata('login_id');
			
			//$withdrawal_remarks = $this->input->post("withdrawal_remarks");
			$withdrawal_remarks = str_replace("'","\'",$this->input->post("withdrawal_remarks"));
			
			$query = "UPDATE edo_application_by_cf SET rejection_st='0', rejection_withdrawn_remarks='$withdrawal_remarks', rejection_withdrawn_time=NOW(),
						withdrawn_by_org='$organization_id', withdrawn_by_user='$login_id'
						WHERE id='$eid'";
			
			$update_st=$this->bm->dataUpdateDB1($query);
			if($update_st==1)
				$data['msg']='<font color=blue><b>Rejection Withdrawn Successfully.</b></font>';
			else
				$data['msg']='<font color=red><b>Rejection Withdraw Failed.</b></font>';
			

            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function validityExtensionApplicationFormForFFApproveValidity()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
			
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		 	$edo_id=$this->input->post('edo_id');
			$do_upload_st=$this->input->post('do_upload_st');
			$bl_type=$this->input->post('bl_type');
			$imp_rot=$this->input->post('imp_rot');
			$blNo=$this->input->post('blNo');
			$cont_status=$this->input->post('cont_status');
			$uploadId=$this->input->post('uploadId');
			$result = "";
			// if($do_upload_st=="0"){
				if($bl_type=="MB")
				{					
					$query="SELECT igm_detail_container.id AS cId,cont_number, cont_status, cont_location_code, cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages
									FROM igm_detail_container 
									INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
									WHERE  igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$blNo'";
				}
				else if($bl_type=="HB")
				{					
					$query="SELECT igm_sup_detail_container.id AS cId,cont_number, cont_status, cont_location_code, cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number,cont_number_packaages
									FROM igm_sup_detail_container 
									INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
									WHERE  igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$blNo'";
				}
				$result =$this->bm->dataSelectDB1($query);

							
			$containerQuery="SELECT * FROM edo_applied_validity_date WHERE edo_id=$edo_id";	
			$containerResult =$this->bm->dataSelectDB1($containerQuery);
			$validityQuery="SELECT applied_valid_dt  AS rtnValue FROM edo_application_by_cf WHERE id=$edo_id";
			$resultValidity=$this->bm->dataReturnDb1($validityQuery);
			$data['validityDate']=$resultValidity;
			$data['title']=" Approve Validity Extension";
			$data['msg'] = "";
			$data['edo_id']= $edo_id;
			$data['do_upload_st']= $do_upload_st;
			$data['bl_type']=$bl_type;
			$data['imp_rot']=$imp_rot;
			$data['blNo']=$blNo;
			$data['cont_status']=$cont_status;
			$data['uploadId']=$uploadId;
			$data['result']=$result;

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('validityExtensionApplicationFormForFFApproveValidity',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function validityExtendForApproveFF()
	{
		$total=$this->input->post('total');
		$uploadId=$this->input->post('uploadId');
		$bl_type=$this->input->post('bl_type');
		$edo_id=$this->input->post('edo_id');
		$validity=$this->input->post('validity_extend');
		$cont_status=$this->input->post('cont_status');
		$login_id = $this->session->userdata('login_id');
		if(!empty($_POST['list']))
			{
				if($bl_type=="MB")
				{
					//MB
					$sqlDlt = "DELETE FROM edo_applied_validity_date WHERE edo_id='$edo_id'";
					$this->bm->dataDeleteDB1($sqlDlt);
					
					foreach($_POST['list'] as $containerId)
					{ 
					 $insertQuery="INSERT INTO edo_applied_validity_date(edo_id,shed_mlo_do_info_id,cont_igm_id,applied_validity_date,entered_by,entered_at)
									  VALUES('$edo_id','$uploadId','$containerId','$validity','$login_id',NOW())";
									 
						$this->bm->dataInsertDb1($insertQuery);
						$data['msg'] = "<font color='blue'><strong>Validity Extension Has Been Approved Successfully</strong></font>";
						
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET valid_upto_dt_by_mlo='$validity',validity_approved_by_mlo_at=NOW(),
						vldty_appr_by_mlo_st='1',cnf_vldty_appr_st='0' WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
						
						//if($resUpdate)				
						// $updateQuery="UPDATE do_upload_wise_container
									  // SET valid_upto_date= '$validity'
									  // WHERE cont_igm_id='$containerId' AND shed_mlo_do_info_id='$uploadId'";
						//$this->bm->dataUpdateDB1($updateQuery);
							
						
					}
				}
				else if($bl_type=="HB")
				{
					//HB
					
					// $updateCNFApplicationSt="UPDATE edo_application_by_cf 
					// SET valid_upto_dt_by_mlo='$validity',vldty_appr_by_mlo_st='0',cnf_vldty_appr_st='0' 
					// WHERE id='$edo_id'";
					// $resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					if($cont_status=="FCL" or  $cont_status=="FCL/PART" or  $cont_status=="ETY" )
					{
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET valid_upto_dt_by_mlo='$validity',
						validity_approved_by_mlo_at=NOW(),vldty_appr_by_mlo_st='1',cnf_vldty_appr_st='0' WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					}
					else
					{
						$updateCNFApplicationSt="UPDATE edo_application_by_cf SET valid_upto_dt_by_mlo='$validity',
						validity_approved_by_mlo_at=NOW(),vldty_appr_by_mlo_st='0',cnf_vldty_appr_st='0' WHERE id='$edo_id'";
						$resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
					}
					
										
					$sqlDlt = "DELETE FROM edo_applied_validity_date WHERE edo_id='$edo_id'";
					$this->bm->dataDeleteDB1($sqlDlt);
					
					foreach($_POST['list'] as $containerId)
					{ 
						$chkContainer="SELECT COUNT(*) AS rtnValue FROM do_upload_wise_container 
								WHERE shed_mlo_do_info_id='$uploadId' AND cont_igm_id='$containerId'";
						$cnt_container = $this->bm->dataReturnDb1($chkContainer);
						if($cnt_container==0){
							$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id,valid_upto_date) 
													values('$uploadId','$containerId','$validity')";
							$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
						
						} else {
							$updateContValididyDt="UPDATE do_upload_wise_container SET valid_upto_date='$validity' 
													WHERE shed_mlo_do_info_id='$uploadId' AND cont_igm_id='$containerId'";
							$resUpdateContValididyDt = $this->bm->dataUpdateDB1($updateContValididyDt);
						}
						$data['msg'] = "<font color='blue'><strong>Validity Extension Has Been Approved Successfully</strong></font>";
						
						//Previous Code Starts...
						// $insertQuery="INSERT INTO edo_applied_validity_date(edo_id,shed_mlo_do_info_id,cont_igm_id,applied_validity_date,entered_by,
										// entered_at)
									// VALUES('$edo_id','$uploadId','$containerId','$validity','$login_id',NOW())";									 
						// $this->bm->dataInsertDb1($insertQuery);
						// $data['msg'] = "<font color='blue'><strong>Validity Extension Has Been Approved Successfully</strong></font>";
						
						// $updateCNFApplicationSt="UPDATE edo_application_by_cf SET valid_upto_dt_by_mlo='$validity',vldty_appr_by_mlo_st='1',
												// cnf_vldty_appr_st='0' WHERE id='$edo_id'";
						// $resUpdate = $this->bm->dataUpdateDB1($updateCNFApplicationSt);
						//Previous Code Ends...
						
						//if($resUpdate)...				
						// $updateQuery="UPDATE do_upload_wise_container
									  // SET valid_upto_date= '$validity'
									  // WHERE cont_igm_id='$containerId' AND shed_mlo_do_info_id='$uploadId'";
						//$this->bm->dataUpdateDB1($updateQuery);
						//...
							
						
					}
				}
				
				
			} 
		else
			{
				$data['msg'] = "<font color='red'><strong>No Container was selected</strong></font>";
			}
		$data['org_id'] =$this->session->userdata('org_id');
		$data['title']="EDO Application List";
		
		$sqlDoUploadSt="SELECT do_upload_st AS rtnValue FROM edo_application_by_cf WHERE id ='$edo_id'";
		$upload_st = $this->bm->dataReturnDb1($sqlDoUploadSt);		
		if($upload_st == 0){
			$data['flag'] = "pending";
		} else {
			$data['flag'] = "all";
		}
		$data['searchBy'] = "";
		$data['searchInput'] = "";
		$data['cpa_search'] = 0;
		//$data['flag'] = "all"; //To show all do list
		
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('applicationForEDOList',$data);
		$this->load->view('jsAssetsList');
	}
	
	function ffAssocStateChange(){
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{					
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			
			$edoId = $this->input->post('edoId');
			$cnfLic = $this->input->post('cnfLic');
			$statUpdatequery = "UPDATE edo_application_by_cf SET ff_assoc_st='1' WHERE id='$edoId'";
			$update_st = $this->bm->dataUpdateDB1($statUpdatequery);			
			if($update_st==1)
			{
				$tmp = "";
				$strTokenId = "SELECT id FROM token_distribution WHERE ff_ain='$cnfLic' AND used_st=0 AND edo_id IS NULL 
								ORDER BY id ASC LIMIT 1";
				$resTokenId=$this->bm->dataSelectDB1($strTokenId);
				for($i=0;$i<count($resTokenId);$i++)
				{
					$tmp=$resTokenId[$i]['id'];
				}
				$updateTokenSt = "Update token_distribution set used_st='1',edo_id='$edoId' WHERE id='$tmp'";
				$resTokenSt = $this->bm->dataUpdateDB1($updateTokenSt);
				if($resTokenSt)
				{
					$data['msg']='<font color=blue><b>Approved!!</b></font>';
				}
				else
				{
					$data['msg']='<font color=red><b>Failed!!</b></font>';
				}				
			}				
			else
			{
				$data['msg']='<font color=red><b>Failed!!</b></font>';
			}				
			$data['flag'] = "all"; //To show all do list
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function searchEDOapplication()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {	
			$searchBy = $this->input->post('search_by');
			$searchInput = $this->input->post('searchInput');
			$searched_be_dt = $this->input->post('searched_be_dt');			
					
			$data['searchBy']=$searchBy;
			$data['searchInput']=$searchInput;			 
			$data['searched_be_dt']=$searched_be_dt;
					
			//$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="Search Result";
			$data['msg'] = "";
			$data['flag'] = "search"; //To show all do list
			$data['cpa_search'] = 1;
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function changeChkState()
	{
		
		$uploadId = $this->input->post('uploadIdtoApprove');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$msgFlag = 0;	
		$login_id = $this->session->userdata('login_id');
		$strUpdateDetail="update shed_mlo_do_info set check_st='1', cpa_check_ip='$ipaddr',cpa_checked_by='$login_id', cpa_check_time=NOW() 
					where id='$uploadId'";
		$strUpdateStat = $this->bm->dataUpdateDB1($strUpdateDetail);	
		$data['strUpdateStat'] = $strUpdateStat;

		// NTS API - start
		
		// /*
		$sql_chkBLType = "SELECT bl_type AS rtnValue
						FROM shed_mlo_do_info
						WHERE id='$uploadId'";
		$chkBLType = $this->bm->dataReturnDB1($sql_chkBLType);
		
		$sql_apprData = "";
		
		if($chkBLType=="MB")			// MBL
		{
			$sql_apprData = "SELECT imp_rot AS reg_no,shed_mlo_do_info.bl_no,
			edo_mlo AS mlo_code,edo_id,edo_application_by_cf.mlo,edo_application_by_cf.ff_org_id,
			shed_mlo_do_info.be_no,shed_mlo_do_info.be_date,shed_mlo_do_info.office_code AS be_office_code,
			CONCAT(edo_mlo,LPAD(edo_sl,6,'0'),edo_year) AS do_no,
			shed_mlo_do_info.bl_type,do_date,valid_upto_dt AS do_valid_upto,
			(SELECT u_name FROM users WHERE login_id=user_id) AS do_issued_by,
			igm_details.BL_No AS mlo_line_no,
			'' AS ff_line,
			igm_details.Pack_Number AS do_qty,
			igm_details.Pack_Description AS do_unit,

			igm_details.weight AS do_weight,

			REPLACE(imp_rot,'/',' ') AS manif_no,
			(SELECT recp_no FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id 
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no LIMIT 1) AS cus_ro_no,

			IFNULL((SELECT recp_date FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no LIMIT 1),'0000-00-00') AS cus_ro_dt,

			(SELECT place_dec FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no LIMIT 1) AS be_exit_no,
			
			REPLACE(edo_application_by_cf.sumitted_by,'CF','') AS AIN,
			(SELECT u_name FROM users WHERE login_id=sumitted_by) AS cnf_name

			FROM shed_mlo_do_info
			INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
			INNER JOIN igm_details ON igm_details.id=shed_mlo_do_info.igm_detail_id
			WHERE shed_mlo_do_info.id='$uploadId' AND shed_mlo_do_info.bl_type='MB'";
		}
		else
		{
			$sql_apprData = "SELECT imp_rot AS reg_no,shed_mlo_do_info.bl_no,
			igm_details.mlocode AS mlo_code,edo_id,edo_application_by_cf.mlo,edo_application_by_cf.ff_org_id,
			shed_mlo_do_info.be_no,shed_mlo_do_info.be_date,shed_mlo_do_info.office_code AS be_office_code,
			CONCAT(edo_mlo,LPAD(edo_sl,6,'0'),edo_year) AS do_no,
			shed_mlo_do_info.bl_type,do_date,valid_upto_dt AS do_valid_upto,
			(SELECT u_name FROM users WHERE login_id=user_id) AS do_issued_by,
			igm_details.BL_No AS mlo_line_no,
			igm_supplimentary_detail.BL_No AS ff_line,
			igm_supplimentary_detail.Pack_Number AS do_qty,
			igm_supplimentary_detail.Pack_Description AS do_unit,

			igm_supplimentary_detail.weight AS do_weight,

			REPLACE(imp_rot,'/',' ') AS manif_no,
			(SELECT recp_no FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id 
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no LIMIT 1) AS cus_ro_no,

			IFNULL((SELECT recp_date FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no LIMIT 1),'0000-00-00' ) AS cus_ro_dt,

			(SELECT place_dec FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no LIMIT 1) AS be_exit_no,
			
			REPLACE(edo_application_by_cf.sumitted_by,'CF','') AS AIN,
			(SELECT u_name FROM users WHERE login_id=sumitted_by) AS cnf_name
			
			FROM shed_mlo_do_info
			INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=shed_mlo_do_info.igm_detail_id
			INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
			WHERE shed_mlo_do_info.id='$uploadId' AND shed_mlo_do_info.bl_type='HB'";
		}
		
		// $sql_apprData = "SELECT imp_rot AS reg_no,bl_no,edo_mlo AS mlo_code,edo_id,edo_application_by_cf.mlo,edo_application_by_cf.ff_org_id,
		// (SELECT AIN_No FROM organization_profiles WHERE id=mlo) AS mlo_line_no,
		// (SELECT AIN_No FROM organization_profiles WHERE id=ff_org_id) AS ff_line,
		// shed_mlo_do_info.be_no,shed_mlo_do_info.be_date,office_code AS be_office_code,

		// (CASE
			// WHEN (LENGTH(edo_sl)=1) THEN CONCAT(edo_mlo,'00000',edo_sl,edo_year)
			// WHEN (LENGTH(edo_sl)=2) THEN CONCAT(edo_mlo,'0000',edo_sl,edo_year)
			// WHEN (LENGTH(edo_sl)=3) THEN CONCAT(edo_mlo,'000',edo_sl,edo_year)
			// WHEN (LENGTH(edo_sl)=4) THEN CONCAT(edo_mlo,'00',edo_sl,edo_year)
			// WHEN (LENGTH(edo_sl)=5) THEN CONCAT(edo_mlo,'0',edo_sl,edo_year)
			// ELSE CONCAT(edo_mlo,edo_sl,edo_year)
		// END) AS do_no,

		// shed_mlo_do_info.bl_type,do_date,valid_upto_dt AS do_valid_upto,
		// (SELECT u_name FROM users WHERE login_id=user_id) AS do_issued_by,

		// IF(shed_mlo_do_info.bl_type='HB',(SELECT Pack_Number FROM igm_supplimentary_detail WHERE Import_Rotation_No=reg_no AND BL_No=bl),(SELECT Pack_Number FROM igm_details WHERE Import_Rotation_No=reg_no AND BL_No=bl)) AS do_qty,

		// IF(shed_mlo_do_info.bl_type='HB',(SELECT Pack_Description FROM igm_supplimentary_detail WHERE Import_Rotation_No=reg_no AND BL_No=bl),(SELECT Pack_Description FROM igm_details WHERE Import_Rotation_No=reg_no AND BL_No=bl)) AS do_unit,

		// IF(shed_mlo_do_info.bl_type='HB',(SELECT weight FROM igm_supplimentary_detail WHERE Import_Rotation_No=reg_no AND BL_No=bl),(SELECT weight FROM igm_details WHERE Import_Rotation_No=reg_no AND BL_No=bl)) AS do_weight,

		// REPLACE(imp_rot,'/',' ') AS manif_no,
		// (SELECT recp_no FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id 
		// WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=bl_no) AS cus_ro_no,

		// IFNULL((SELECT recp_date FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
		// WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=bl_no),'0000-00-00') AS cus_ro_dt,

		// (SELECT place_dec FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
		// WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=bl_no) AS be_exit_no
		// FROM shed_mlo_do_info
		// INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
		// WHERE shed_mlo_do_info.id='$uploadId'";
		
		$rslt_apprData = $this->bm->dataSelectDB1($sql_apprData);
		
		$reg_no = $rslt_apprData[0]['reg_no'];
		$bl_no = $rslt_apprData[0]['bl_no'];
		$mlo_code = $rslt_apprData[0]['mlo_code'];
		$mlo_line_no = $rslt_apprData[0]['mlo_line_no'];
		$ff_line = $rslt_apprData[0]['ff_line'];
		$be_no = $rslt_apprData[0]['be_no'];
		$be_date = $rslt_apprData[0]['be_date'];
		$be_office_code = $rslt_apprData[0]['be_office_code'];
		$do_no = $rslt_apprData[0]['do_no'];
		$do_date = $rslt_apprData[0]['do_date'];
		$do_valid_upto = $rslt_apprData[0]['do_valid_upto'];
		$do_issued_by = $rslt_apprData[0]['do_issued_by'];
		$do_qty = $rslt_apprData[0]['do_qty'];
		$do_unit = $rslt_apprData[0]['do_unit'];
		$do_weight = $rslt_apprData[0]['do_weight'];
		$cus_ro_no = $rslt_apprData[0]['cus_ro_no'];
		$cus_ro_dt = $rslt_apprData[0]['cus_ro_dt'];
		$be_exit_no = $rslt_apprData[0]['be_exit_no'];
		$AIN = $rslt_apprData[0]['AIN'];
		$cnf_name = $rslt_apprData[0]['cnf_name'];
		
		/*		-- API stop
		$url = "http://192.168.16.243:8082/edoInfo/add";

		// Create a new cURL resource
		$ch = curl_init($url);

		// Setup request to send json via POST						
		$jsonData = '{
				"reg_no": "'.$reg_no.'",
				"bl_no": "'.$bl_no.'",
				"mlo_code": "'.$mlo_code.'",
				"mlo_line_no": "'.$mlo_line_no.'",
				"ff_line": "'.$ff_line.'",
				"be_no": "'.$be_no.'",
				"be_dt": "'.$be_date.'",
				"be_office_code": "'.$be_office_code.'",
				"do_no": "'.$do_no.'",
				"do_dt": "'.$do_date.'",
				"do_valid_upto": "'.$do_valid_upto.'",
				"do_issued_by": "'.$do_issued_by.'",
				"do_qty": "'.$do_qty.'",
				"do_unit": "'.$do_unit.'",
				"do_weight": "'.$do_weight.'",
				"cus_ro_no": "'.$cus_ro_no.'",
				"cus_ro_dt": "'.$cus_ro_dt.'",
				"be_exit_no": "'.$be_exit_no.'",
				"cnf_ain": "'.$AIN.'",
				"cnf_name": "'.$cnf_name.'"
				}';						
				
		//print_r($jsonData);
		
		// Attach encoded JSON string to the POST fields
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

		// Set the content type to application/json
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

		// Return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the POST request
		$result = curl_exec($ch);
		
		$content = curl_exec($ch);

		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		
		//print_r("content : ".$content); 
		
		//print_r("code : ".$code); 
		
		// Close cURL resource
		curl_close($ch);
		*/	// -- API stop
		
		// */
		
		// NTS API - end


		// Generate Notification

		$cpa_org_id = $this->session->userdata('org_id');
		$user = $this->session->userdata('login_id');
		$edoId = $this->input->post('edo_id');

		// $query_bl_type="SELECT bl_type AS rtnValue FROM edo_application_by_cf WHERE id='$edoId'";
		// $bl_type=$this->bm->dataReturnDb1($query_bl_type);
		//$submitted_by_query="SELECT sumitted_by AS rtnValue FROM edo_application_by_cf WHERE id='$edoId'";
		// $submitted_by=$this->bm->dataReturnDb1($submitted_by_query);
		// $cf_org_id_query="SELECT org_id  AS rtnValue FROM users WHERE login_id='$submitted_by'";
		//$cf_org_id=$this->bm->dataReturnDb1($cf_org_id_query);
		// $mlo_org_id_query="SELECT mlo AS rtnValue FROM edo_application_by_cf WHERE id='$edoId'";
		// $mlo_org_id=$this->bm->dataReturnDB1($mlo_org_id_query);

		$queryEDODtls="select * from edo_application_by_cf where id='$edoId'";
		$edoDtls = $this->bm->dataSelectDB1($queryEDODtls);

		$org_notified = "";
		$bl_type = "";
		$submitted_by = "";
		$mlo_org_id = "";
		
		for($i=0;$i<count($edoDtls);$i++)
		{
			$org_notified = $edoDtls[$i]['ff_org_id'];
			$bl_type = $edoDtls[$i]['bl_type'];
			$submitted_by = $edoDtls[$i]['sumitted_by'];
			$mlo_org_id = $edoDtls[$i]['mlo'];
		}

		$cf_org_id = "";
		$cf_org_id_query="SELECT org_id FROM users WHERE login_id='$submitted_by'";
		$cfOrgId=$this->bm->dataSelectDB1($cf_org_id_query);
		
		for($y=0;$y<count($cfOrgId);$y++)
		{
			$cf_org_id = $cfOrgId[$y]['org_id'];
		}
		
		if($bl_type == "HB")
		{
			$edoNotifyCfQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
			VALUES('$edoId','$cf_org_id',4,0,'$user',NOW())";
			//$this->bm->dataInsertDB1($edoNotifyCfQuery);

			$edoNotifyMloQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
			VALUES('$edoId','$mlo_org_id',4,0,'$user',NOW())";
			//$this->bm->dataInsertDB1($edoNotifyMloQuery);

			$edoNotifyFFQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
			VALUES('$edoId','$org_notified',4,0,'$user',NOW())";
			//$this->bm->dataInsertDB1($edoNotifyFFQuery);

			$cpaLifeStatQuery = "UPDATE edo_notification SET life_st = 1 WHERE application_id='$edoId' AND org_notified='$cpa_org_id' AND notification_st = 3";
			//$this->bm->dataUpdateDB1($cpaLifeStatQuery);

		}
		else
		{

			$edoNotifyCfQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
			VALUES('$edoId','$cf_org_id',4,0,'$user',NOW())";
			//$this->bm->dataInsertDB1($edoNotifyCfQuery);

			$edoNotifyMloQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
			VALUES('$edoId','$mlo_org_id',4,0,'$user',NOW())";
			//$this->bm->dataInsertDB1($edoNotifyMloQuery);

			$cpaLifeStatQuery = "UPDATE edo_notification SET life_st = 1 WHERE application_id='$edoId' AND org_notified='$cpa_org_id' AND notification_st = 3";
			//$this->bm->dataUpdateDB1($cpaLifeStatQuery);
		}
		
		$data['searchInput']="";		
		$data['searchBy']="bl";
		$data['searchInput']=$this->input->post('bl');
		//$data['org_id'] =$this->session->userdata('org_Type_id');
		$data['title']="Search Result";
		$data['msg'] = "";
		$data['flag'] = "search"; //To show all do list
		$data['cpa_search'] = 1;
		
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('applicationForEDOList',$data);
		$this->load->view('jsAssetsList');
	}
	
	function approveValidityExtensionForFCLandHBL()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['flag'] = "all"; //To show all do list
			
			$eid = $this->input->post("edoForApproveValidityExtension");
			$applied_valid_dt = $this->input->post("valid_upto_date_for_approval");
			
			//$query = "UPDATE edo_application_by_cf SET cnf_vldty_appr_st='0',vldty_appr_by_mlo_st='1' WHERE id='$eid'"; // Previous Query...
			$upload_st = "";
			$strUploadStatus = "SELECT do_upload_st FROM edo_application_by_cf WHERE id='$eid'";
			$resUploadStatus=$this->bm->dataSelectDB1($strUploadStatus);
			for($i=0;$i<count($resUploadStatus);$i++)
				{
					$upload_st=$resUploadStatus[$i]['do_upload_st'];
				}
				
			if($upload_st=="0"){				
				$query = "UPDATE edo_application_by_cf SET cnf_vldty_appr_st='0',vldty_appr_by_mlo_st='1',
				valid_upto_dt_by_mlo='$applied_valid_dt',applied_valid_dt='$applied_valid_dt'
				WHERE id='$eid'";						
			} else {
				$query = "UPDATE edo_application_by_cf SET cnf_vldty_appr_st='0',vldty_appr_by_mlo_st='1',
				valid_upto_dt_by_mlo='$applied_valid_dt',applied_valid_dt='$applied_valid_dt'
				WHERE id='$eid'";
			}
			$update_st=$this->bm->dataUpdateDB1($query);
			if($update_st==1)
				$data['msg']='<font color=blue><b>Approved Successfully.</b></font>';
			else
				$data['msg']='<font color=red><b>Approved Failed.</b></font>';			

            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function deleteTokenDistribution() {
		$tbl_name = $this->input->post('tbl_name');
		$id = $this->input->post('id');
		$ff_ain = $this->input->post('ff_ain');
		$fromDate = $this->input->post('fromDate');
		$toDate = $this->input->post('toDate');
		$msg = "";
		
		if($tbl_name=="token_distribution_transaction"){			
			$sql_count_used_token = "SELECT COUNT(*) AS rtnValue FROM token_distribution WHERE transaction_id='$id' AND used_st='1'";
			$used_token = $this->bm->dataReturnDB1($sql_count_used_token);			
			if($used_token=="0") {
				$sql_delete_token="DELETE FROM token_distribution WHERE transaction_id='$id'";						
				$rslt_delete_token = $this->bm->dataDeleteDB1($sql_delete_token);
				
				$sql_delete_token_history="DELETE FROM token_distribution_transaction WHERE id='$id'";						
				$rslt_delete_token_history = $this->bm->dataDeleteDB1($sql_delete_token_history);
				
				$msg = "<font color='blue'><strong>Data Deleted</strong></font>";
			} else {
				$msg = "<font color='red'><strong>Sorry! Cannot delete token distribution because some tokens from this distribution have already been used.</strong></font>";
			}
		} else {
			$sql_count_used_token = "SELECT COUNT(*) AS rtnValue FROM token_distribution 
										WHERE (ff_ain='$ff_ain') AND (used_st='1') AND (DATE(entry_time) BETWEEN '$fromDate' AND '$toDate')";
			$used_token = $this->bm->dataReturnDB1($sql_count_used_token);
			if($used_token=="0") {
				$sql_delete_token="DELETE FROM token_distribution 
									WHERE (ff_ain='$ff_ain') AND (DATE(entry_time) BETWEEN '$fromDate' AND '$toDate')";						
				$rslt_delete_token = $this->bm->dataDeleteDB1($sql_delete_token);
				$msg = "<font color='blue'><strong>Data Deleted</strong></font>";
			} else {
				$msg = "<font color='red'><strong>Sorry! Cannot delete token distribution .</strong></font>";
			}			
		}
		$data['msg'] = $msg;
		$data['title']="Date Wise Token Distribution Form";
		$data['flag']="new";
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('DateWiseTokenDistributionList',$data);
		$this->load->view('jsAssetsList');
	}
	
	function edoVerificationDateWiseReport(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="EDO Verification Report (Date Wise Summary)";
			$data['msg']="";
			$data['frmType']="";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('edoVerificationReportDateWiseReportForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function edoContainerReport()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="EDO Container Report Form";
			$data['msg']="";
			$data['frmType']="";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('edoContainerReport',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function edoVerificationDateWiseReportPdf()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_id = $this->session->userdata("org_id");
		$org_type_id = $this->session->userdata('org_Type_id');

		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$this->load->library('m_pdf');
			$mpdf->use_kwt = true;
			$mpdf->simpleTables = true;						
			$from_date = $this->input->post("from_date");
			$to_date = $this->input->post("to_date");
			$str="SELECT do_date,SUM(tot) AS tot,SUM(approve) AS approve,SUM(notApprove) AS notApprove
			FROM (
			SELECT shed_mlo_do_info.do_date,IFNULL(ff_org_id,mlo) AS org, 
			(SELECT Organization_Name FROM organization_profiles WHERE id=org) AS org_name,
			1 AS tot,IF(check_st=1,1,0) AS approve,IF(check_st=0,1,0) AS notApprove
			FROM shed_mlo_do_info
			INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
			WHERE shed_mlo_do_info.do_date BETWEEN '$from_date' AND '$to_date'
			) AS tbl GROUP BY do_date ORDER BY do_date";
			
			$edoVerificationList = $this->bm->dataSelectDB1($str);
			$count=count($edoVerificationList);
			
			$this->data['edoVerificationList']=$edoVerificationList;
			//$this->data['orgNAME']=$orgNAME;
			$this->data['from_date']=$from_date;
			$this->data['to_date']=$to_date;
			$this->data['title']="EDO Verification Report";
			$html=$this->load->view('edoVerificationDateWisePdf',$this->data, true); 
			$pdfFilePath ="edoContainerReportPdf";

			$pdf = $this->m_pdf->load();
			$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
			$pdf = new mPDF('utf-8', 'A4-L');  //have tried several of the formats
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath, "I");
		}
	}
	
	function edoContainerReportPdf()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_id = $this->session->userdata("org_id");
		$login_id = $this->session->userdata('login_id');
		$org_type_id = $this->session->userdata('org_Type_id');

		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			// $this->load->library('m_pdf');
			// $mpdf->use_kwt = true;
			// $mpdf->simpleTables = true;			
			
			$from_date = $this->input->post("from_date");
			$to_date = $this->input->post("to_date");
			$rptType = $this->input->post("rptType");
			
			$orgNameQuery="SELECT organization_profiles.Organization_Name AS rtnValue FROM users
			INNER JOIN organization_profiles
			ON  users.org_id=organization_profiles.id
			WHERE users.org_id='$org_id'";
			$orgNAME=$this->bm->dataReturnDb1($orgNameQuery);

			if($org_type_id==1)
			{
				// $org_str="AND edo_application_by_cf.mlo='$org_id'";
				$org_str="AND shed_mlo_do_info.user_id='$login_id'";
			}
			else if($org_type_id==4)
			{
				$org_str="AND edo_application_by_cf.ff_org_id='$org_id'";
			}
			else
			{
				$org_str="";
			}


			$str="SELECT shed_mlo_do_info.imp_rot,shed_mlo_do_info.bl_no, igm_detail_container.cont_number, igm_detail_container.cont_size,
			igm_detail_container.cont_height,
			igm_detail_container.cont_iso_type,  igm_masters.Vessel_Name, DATE(shed_mlo_do_info.upload_time)  AS upload_date,
            shed_mlo_do_info.remarks, shed_mlo_do_info.valid_upto_dt,
			shed_mlo_do_info.upload_time,
			edo_application_by_cf.sumitted_by,users.u_name AS CF,
			edo_application_by_cf.mlo
			FROM shed_mlo_do_info 
			INNER JOIN edo_application_by_cf ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
			INNER JOIN users ON edo_application_by_cf.sumitted_by=users.login_id
			INNER JOIN do_upload_wise_container ON do_upload_wise_container.shed_mlo_do_info_id=shed_mlo_do_info.id
			INNER JOIN igm_detail_container ON igm_detail_container.id= do_upload_wise_container.cont_igm_id
			INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
			INNER JOIN igm_masters ON  igm_masters.id=igm_details.IGM_id
			WHERE edo_application_by_cf.do_upload_st='1' AND (DATE(shed_mlo_do_info.upload_time) BETWEEN '$from_date' AND '$to_date')
			$org_str
			
			UNION ALL
			
			SELECT shed_mlo_do_info.imp_rot,shed_mlo_do_info.bl_no, igm_sup_detail_container.cont_number, igm_sup_detail_container.cont_size,
			igm_sup_detail_container.cont_height,
			igm_sup_detail_container.cont_iso_type,  igm_masters.Vessel_Name, DATE(shed_mlo_do_info.upload_time)  AS upload_date,
            shed_mlo_do_info.remarks,shed_mlo_do_info.valid_upto_dt, 
			shed_mlo_do_info.upload_time,
			edo_application_by_cf.sumitted_by,users.u_name AS CF,
			edo_application_by_cf.mlo
			FROM shed_mlo_do_info 
			INNER JOIN edo_application_by_cf ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
			INNER JOIN users ON edo_application_by_cf.sumitted_by=users.login_id
			INNER JOIN do_upload_wise_container ON do_upload_wise_container.shed_mlo_do_info_id=shed_mlo_do_info.id
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.id= do_upload_wise_container.cont_igm_id
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id 
			INNER JOIN igm_masters ON  igm_masters.id=igm_supplimentary_detail.igm_master_id
			WHERE edo_application_by_cf.do_upload_st='1' AND (DATE(shed_mlo_do_info.upload_time) BETWEEN '$from_date' AND '$to_date')
			$org_str AND igm_sup_detail_container.cont_number NOT IN (SELECT cont_number FROM igm_detail_container)";
			
			$edoVerificationList = $this->bm->dataSelectDb1($str);
			
			if($rptType=="pdf")
			{			
				$this->load->library('m_pdf');
				$mpdf->use_kwt = true;
				$mpdf->simpleTables = true;	
				
				$this->data['edoVerificationList']=$edoVerificationList;
				$this->data['orgNAME']=$orgNAME;
				$this->data['from_date']=$from_date;
				$this->data['to_date']=$to_date;
				$this->data['title']="EDO Container Report";
				$html=$this->load->view('edoContainerReportPdf',$this->data, true); 
				$pdfFilePath ="edoContainerReportPdf";

				$pdf = $this->m_pdf->load();
				$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
				$pdf = new mPDF('utf-8', 'A4-L');  //have tried several of the formats
				$pdf->WriteHTML($html,2);
				$pdf->Output($pdfFilePath, "I");
			}
			else if($rptType=="excel")
			{
				// echo "not ready";
				$data['edoVerificationList']=$edoVerificationList;
				$data['orgNAME']=$orgNAME;
				$data['from_date']=$from_date;
				$data['to_date']=$to_date;
				$data['title']="EDO Container Report";
				
				$this->load->view('edoContainerReportExcel',$data);
			}	
		}
	}
	
	public function edoVerificationOrgWiseReport()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="EDO Verification Report (Organization Wise Summary)";
			$data['msg']="";
			$data['frmType']="";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('edoVerificationReportOrgWiseReportForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function edoVerificationOrgWiseReportPdf()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_id = $this->session->userdata("org_id");
		$org_type_id = $this->session->userdata('org_Type_id');

		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$this->load->library('m_pdf');
			$mpdf->use_kwt = true;
			$mpdf->simpleTables = true;						
			$from_date = $this->input->post("from_date");
			$to_date = $this->input->post("to_date");
			// $str="SELECT org_name,SUM(tot) AS tot,SUM(approve) AS approve,SUM(notApprove) AS notApprove
			// FROM (
			// SELECT shed_mlo_do_info.do_date,IFNULL(ff_org_id,mlo) AS org, 
			// (SELECT Organization_Name FROM organization_profiles WHERE id=org) AS org_name,
			// 1 AS tot,IF(check_st=1,1,0) AS approve,IF(check_st=0,1,0) AS notApprove
			// FROM shed_mlo_do_info
			// INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
			// WHERE shed_mlo_do_info.do_date BETWEEN '$from_date' AND '$to_date'
			// ) AS tbl GROUP BY org_name ORDER BY org_name";
			
			$str="SELECT TRIM(org_name) AS org_name,SUM(tot) AS tot,SUM(approve) AS approve,SUM(notApprove) AS notApprove
				FROM (
					SELECT (SELECT IFNULL(ff_org_id,mlo) FROM edo_application_by_cf WHERE id=edo_id) AS org,
					IF(check_st=1,1,0) AS approve,IF(check_st=0,1,0) AS notApprove,1 AS tot,
					(SELECT Organization_Name FROM organization_profiles WHERE id=org) AS org_name
					FROM shed_mlo_do_info
					WHERE (DATE(shed_mlo_do_info.upload_time) BETWEEN '$from_date' AND '$to_date') 
				) AS tbl WHERE org_name IS NOT NULL GROUP BY 1";
			
			
			
			$edoVerificationList = $this->bm->dataSelectDB1($str);
			$count=count($edoVerificationList);
			
			$this->data['edoVerificationList']=$edoVerificationList;
			//$this->data['orgNAME']=$orgNAME;
			$this->data['from_date']=$from_date;
			$this->data['to_date']=$to_date;
			$this->data['title']="EDO Verification Report";
			$html=$this->load->view('edoVerificationOrgWisePdf',$this->data, true); 
			$pdfFilePath ="edoContainerReportPdf";

			$pdf = $this->m_pdf->load();
			$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
			$pdf = new mPDF('utf-8', 'A4-L');  //have tried several of the formats
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath, "I");
			
		}
	}
	
	public function edoIssueByCnfForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Organization Wise EDO Summary";
			$data['msg']="";
			$data['frmType']="";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('edoIssueByCnfForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function edoIssueByCnfReport()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$org_id = $this->session->userdata("org_id");
		$org_type_id = $this->session->userdata('org_Type_id');

		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$this->load->library('m_pdf');
			$mpdf->use_kwt = true;
			$mpdf->simpleTables = true;						
			$from_date = $this->input->post("from_date");
			$to_date = $this->input->post("to_date");
			
			// $str="SELECT TRIM(org_name) AS org_name,SUM(tot) AS tot,SUM(approve) AS approve,SUM(notApprove) AS notApprove
			// FROM (
			// SELECT shed_mlo_do_info.do_date,IFNULL(ff_org_id,mlo) AS org, 
			// (SELECT Organization_Name FROM organization_profiles WHERE id=org) AS org_name,
			// 1 AS tot,IF(check_st=1,1,0) AS approve,IF(check_st=0,1,0) AS notApprove
			// FROM shed_mlo_do_info
			// INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
			// WHERE DATE(shed_mlo_do_info.upload_time) BETWEEN '$from_date' AND '$to_date'
			// ) AS tbl GROUP BY org_name ORDER BY org_name";
			
			$str="SELECT TRIM(org_name) AS org_name,SUM(tot) AS tot
			FROM (
				SELECT (SELECT IFNULL(ff_org_id,mlo) FROM edo_application_by_cf WHERE id=edo_id) AS org,
				IF(check_st=1,1,0) AS approve,IF(check_st=0,1,0) AS notApprove,1 AS tot,
				(SELECT Organization_Name FROM organization_profiles WHERE id=org) AS org_name
				FROM shed_mlo_do_info
				WHERE (DATE(shed_mlo_do_info.upload_time) BETWEEN '$from_date' AND '$to_date') 
			) AS tbl WHERE org_name IS NOT NULL GROUP BY 1";
			
			$edoVerificationList = $this->bm->dataSelectDB1($str);
			$count=count($edoVerificationList);
			
			$this->data['edoVerificationList']=$edoVerificationList;
			//$this->data['orgNAME']=$orgNAME;
			$this->data['from_date']=$from_date;
			$this->data['to_date']=$to_date;
			$this->data['title']="Organization Wise EDO Summary Report";
			$html=$this->load->view('edoIssueByCnfReport',$this->data, true); 
			$pdfFilePath ="organizationWiseEdoSummaryReport";

			$pdf = $this->m_pdf->load();
			$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
			$pdf = new mPDF('utf-8', 'A4-L');  //have tried several of the formats
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath, "I");
			
		}
	}
	
	function pendingDOList()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Pending List";
			$data['msg'] = "";
			
			$data['flag'] = "pending"; //To show all do list$data['flag'] = "all"; //To show all do list
			$data['searchBy'] = "";
			$data['searchInput'] = "";
			$data['cpa_search'] = 0;
						
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function approveEDOapplication()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$searchBy = $this->input->post('search_by');
			$searchInput = $this->input->post('searchInput');
			$searched_be_dt = $this->input->post('searched_be_dt');			
					
			$data['searchBy']=$searchBy;
			$data['searchInput']=$searchInput;			 
			$data['searched_be_dt']=$searched_be_dt;
			
	        // $data['searchBy']=$this->input->post('search_by');
		    // $data['searchInput']=$this->input->post('searchInput');
			
		    $data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Approved Application List";
			$data['msg'] = "";
			$data['flag'] = "all"; //To show all do list
			$data['cpa_search'] = 1;
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('approveForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function monthWiseEDOReportForm()
	{

		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');			
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			
			$data['title']="Month Wise Total EDO Report";
			$data['flag']="new";
			$data['msg']="";
			$data['starting_year']=2022;
			$data['current_year']=date("Y");		
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('monthWiseEDOReportForm',$data);
			$this->load->view('jsAssetsList');

		}
	}
	
	function monthWiseEDOCountReport () {
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');			
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{		
			$month_val = $this->input->post('month_val');
			$year_val = $this->input->post('year_val');
			
			$qryEDOCount = "SELECT count(*) as rtnValue FROM shed_mlo_do_info 
						where upload_time like '%$year_val-$month_val-%' and bl_type='HB'";	
			$totalEDO = $this->bm->dataReturnDb1($qryEDOCount);			
			
			$this->load->library('m_pdf');
			
			$month_name="";
			if($month_val=="01"){
				$month_name="January";
			} else if($month_val=="02"){
				$month_name="February";
			} else if($month_val=="03"){
				$month_name="March";
			} else if($month_val=="04"){
				$month_name="April";
			} else if($month_val=="05"){
				$month_name="May";
			} else if($month_val=="06"){
				$month_name="June";
			} else if($month_val=="07"){
				$month_name="July";
			} else if($month_val=="08"){
				$month_name="August";
			} else if($month_val=="09"){
				$month_name="September";
			} else if($month_val=="10"){
				$month_name="October";
			} else if($month_val=="11"){
				$month_name="November";
			} else if($month_val=="12"){
				$month_name="December";
			}
			
			$this->data['month_name']=$month_name;
			$this->data['month_val']=$month_val;
			$this->data['year_val']=$year_val;
			$this->data['totalEDO']=$totalEDO;
			
			$html=$this->load->view('monthWiseEDOCountReport',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
			$pdfFilePath ="monthWiseEDOCountReport-".time()."-download.pdf";
			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css	
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			
			
		}
	}
	
	// function adjustTokenNumbers(){
		// $allPendingEDO = "SELECT edo_id FROM shed_mlo_do_info 
			// INNER JOIN edo_application_by_cf ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
			// WHERE shed_mlo_do_info.bl_type='HB' AND edo_application_by_cf.ff_org_id='2642' 
			// ORDER BY shed_mlo_do_info.id DESC LIMIT 139";
		// $pendingEDO = $this->bm->dataSelectDB1($allPendingEDO);	
		// $edo_id = "";
		// for($i=0;$i<count($pendingEDO);$i++)
		// {
			// $edo_id=$pendingEDO[$i]['edo_id'];
			
			// $lastPendingToken = "SELECT id AS rtnValue FROM token_distribution 
								// WHERE ff_ain='301080156' AND used_st='0' AND edo_id IS NULL ORDER BY id ASC LIMIT 1";
			// $tokenDistributionID = $this->bm->dataReturnDb1($lastPendingToken);
			
			
			
			// $updateTokenDistribution = "Update token_distribution set used_st='1',edo_id='$edo_id' 
											// WHERE id='$tokenDistributionID'";
			// $resUpdateTokenDistribution = $this->bm->dataUpdateDB1($updateTokenDistribution);
			
			// echo $edo_id." - ".$tokenDistributionID."<br>";
			
		// }
	// }
	
	
}
?>