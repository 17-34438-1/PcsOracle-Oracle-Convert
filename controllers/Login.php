<?php

class Login extends CI_Controller {
	function __construct()
	{
	    parent::__construct();	
            $this->load->library(array('session', 'form_validation'));
            $this->load->model(array('CI_auth', 'CI_menu'));
            $this->load->helper(array('html','form', 'url'));
			$this->load->model('ci_auth', 'bm', TRUE);
			//$this->load->driver('cache');
			$this->load->library("pagination");
			//$my_session_id = $_GET['session_id']; //gets the session ID successfully
			//$this->session->userdata('session_id', $my_session_id); //it won't set the session with my id.
			//print_r($this->session->userdata);
			
			
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			
	}
        
    function index(){	
					
			$abc['user_id']=$this->session->userdata('login_index_id');
			
			$sub_data['login_failed'] ='';
			$data['title'] = 'Login';
			
			if($this->session->userdata('login_id')){
				// $this->bm->updateAssignmet();		// 2021-03-14
				
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
					
					$sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
					FROM ctmsmis.tmp_oracle_assignment 
					WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";
					$rslt_assignmentList=$this->bm->dataSelectDb2($sql_assignmentList);
					// 2021-04-21 - start	- work on custom_remarks
					if(count($rslt_assignmentList)==0)
					{
						$sql_cnfGkey = "SELECT gkey AS rtnValue FROM ref_bizunit_scoped WHERE id='$org_license'";
						$cnfGkey = $this->bm->dataReturn($sql_cnfGkey);										
						
						$sql_assignmentList="SELECT DISTINCT a.gkey AS unit_gkey,a.id AS cont_no,k.name  AS cnf,a.freight_kind AS cont_status,
											(SELECT ib_vyg FROM argo_carrier_visit
											INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
											WHERE argo_carrier_visit.gkey=b.actual_ib_cv
											) AS rot_no,
											CONCAT(k.address_line1,k.address_line2) AS cnf_addr,
											b.flex_date01,
											(SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit
											INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
											INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
											INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
											WHERE inv_unit_fcy_visit.unit_gkey=a.gkey  FETCH FIRST 1 ROWS ONLY
											)  AS siz,
											(SELECT substr(ref_equip_type.nominal_height,-2) FROM inv_unit
											INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
											INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
											INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
											WHERE inv_unit_fcy_visit.unit_gkey=a.gkey  FETCH FIRST 1 ROWS ONLY
											)  AS height,
											to_date(to_char(b.flex_date01,'yyyy-mm-dd'),'yyyy-mm-dd') AS assignmentDate, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
											mfdch_desc,'' AS custom_remarks,
											b.last_pos_slot
											FROM inv_unit a
											INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
											INNER JOIN inv_goods j ON j.gkey = a.goods
											INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=a.gkey	
											LEFT JOIN ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
											INNER JOIN config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value	
											WHERE (b.flex_date01 BETWEEN to_date(CONCAT(TRUNC(CURRENT_DATE),' 00:00:00'),'yyyy-mm-dd hh24-mi-ss') AND to_date(CONCAT(TRUNC(CURRENT_DATE),' 23:59:59'),'yyyy-mm-dd hh24-mi-ss')) 
											AND j.consignee_bzu = '$cnfGkey' AND config_metafield_lov.mfdch_value!='CANCEL'";
						$rslt_assignmentList=$this->bm->dataSelect($sql_assignmentList);
					}
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
					$this->load->view('cssAssetsList');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('dashboard');
					$this->load->view('jsAssetsList');
				}
			}
			else if($this->input->post('submit_login')){	
				// check user active - start
				
				$actUser=$this->input->post('username');
				
				$sql_chkActUser = "SELECT active_stat AS rtnValue FROM users WHERE login_id = '$actUser'";
				$chkActUser = @$this->bm->dataReturnDB1($sql_chkActUser);
				
				if($chkActUser == 0)	// if inactive
				{
					$query=$this->welcomePageQuery();
					$rtnVesselList = $this->bm->dataSelect($query);
					$data['rtnVesselList']=$rtnVesselList;
					
					echo "<script>alert('User ".$actUser." is not active. Please contact to customer care at 01749 923327');</script>";
					$sub_data['login_failed'] = "<font color='red' size='2'>Inactive User!</font>";
					$data['body'] = $sub_data['login_failed'];
					$data['captchaMsg']="";

					$this->load->view('cssVesselList');
					$this->load->view('jsVesselList');
					$this->load->view('FrontEnd/header');
					$this->load->view('FrontEnd/slider');
					$this->load->view('FrontEnd/index',$data);
					$this->load->view('FrontEnd/footer');
					return;
				}
				
				// check user active - end

				$this->form_validation->set_rules('username', 'username', 'trim|required|min_length[2]|max_length[20]');
				$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[5]|max_length[35]');
				$this->form_validation->set_error_delimiters('<div style="color:red;">', '</div>');
				
				if($this->form_validation->run() == FALSE)
				{						
					$query=$this->welcomePageQuery();
					$rtnVesselList = $this->bm->dataSelect($query);
					$data['rtnVesselList']=$rtnVesselList;
				
					$data['body'] = $sub_data['login_failed'];
					$data['captchaMsg']="";
					
					$this->load->view('cssVesselList');
					$this->load->view('jsVesselList');
					$this->load->view('FrontEnd/header');
					$this->load->view('FrontEnd/slider');
					$this->load->view('FrontEnd/index',$data);
					$this->load->view('FrontEnd/footer');
				}
				else
				{			
					$userId=$this->input->post(trim(str_replace(' ','','username')));			
					
					if($userId=="cpaops")
					{
						$rtn =$this->CI_auth->check_ip();
						
						if($rtn>0)
						{					
							$login_array = array($this->input->post(trim(str_replace(' ','','username'))), $this->input->post(trim(str_replace(' ','','password'))));
							
							if($this->CI_auth->process_login($login_array))
							{					
								// for log write - start - 2022-03-24
									
								$rcdLoginId = $this->input->post(trim(str_replace(' ','','username')));
								$rcdIPAddress = $_SERVER['REMOTE_ADDR'];
								
								// get users table id
								
								$sql_usersTableId = "SELECT id AS rtnValue FROM users WHERE login_id='$rcdLoginId'";							
								$usersTableId = $this->bm->dataReturnDB1($sql_usersTableId);
								
								$sql_rcdLogin = "INSERT INTO user_login_record(login_id,users_tbl_id,login_dt,login_ip,login_from,entry_at)
												VALUES('$rcdLoginId','$usersTableId',NOW(),'$rcdIPAddress','pcs',NOW())";
												
								$rslt_rcdLogin = $this->bm->dataInsertDB1($sql_rcdLogin);
								
								// for log write - end - 2022-03-24
								
								$session_id = $this->session->userdata('value');
								if($session_id!=$this->session->userdata('session_id'))
								{
									$this->logout();
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
							else
							{												
								$sub_data['login_failed'] = "<font color='red' size=2>Invalid username or password</font>";
								$data['body'] =$sub_data['login_failed'];
								
								$query=$this->welcomePageQuery();
								$rtnVesselList = $this->bm->dataSelect($query);
								$data['rtnVesselList']=$rtnVesselList;
								$data['captchaMsg']="";
								
								$this->load->view('cssVesselList');
								$this->load->view('jsVesselList');
								$this->load->view('FrontEnd/header');
								$this->load->view('FrontEnd/slider');
								$this->load->view('FrontEnd/index',$data);
								$this->load->view('FrontEnd/footer');
							}					
						}
						else
						{
							$sub_data['login_failed'] = "<font color='red' size=2>Unauthorize Access</font>";
							$data['body'] =$sub_data['login_failed'];
							
							$query=$this->welcomePageQuery();
							$rtnVesselList = $this->bm->dataSelect($query);
							$data['rtnVesselList']=$rtnVesselList;
							$data['captchaMsg']="";

							$this->load->view('cssVesselList');
							$this->load->view('jsVesselList');
							$this->load->view('FrontEnd/header');
							$this->load->view('FrontEnd/slider');
							$this->load->view('FrontEnd/index',$data);
							$this->load->view('FrontEnd/footer');
						}				
					}
					else
					{		
						// $this->bm->updateAssignmet();			// 2021-03-14
						$user_id=$this->input->post('username');
						$user_password=$this->input->post('password');
						$twoStep ="SELECT  COUNT(*) AS rtnValue FROM users WHERE login_id='$user_id'  AND two_stp_st=1";// AND two_stp_verify_st=0";
						$twoStepState = $this->bm->dataReturnDb1($twoStep); 
						if($twoStepState==1)
						{
							$msg="";
							$num_str = sprintf("%04d", mt_rand(1, 9999));
								$query="UPDATE users SET otp_code = '$num_str' WHERE login_id='$user_id'  AND two_stp_st=1";
							$this->bm->dataUpdateDB1($query);

							$phone_number_query="SELECT  Cell_No_1 AS rtnValue FROM users WHERE login_id='$user_id'  AND two_stp_st=1";
							$phone_number=$this->bm->dataReturnDb1($phone_number_query);
									//$this->bm->dataUpdateDB1($query);
							$str='Your%20verification%20code:%20'.$num_str;
							$this->bm->sendSMS($phone_number, $str);
			
							$data['phone_number']=$phone_number;
							$data['user_id']=$user_id;
							$data['user_password']=$user_password;
							$data['msg']=$msg;
							$this->load->view('cssAssets');
							$this->load->view('verify_two_step_verification',$data);	
							$this->load->view('jsAssets');
							//redirect('Login/PreventRefresh/'.$phone_number.'/'.$user_id, 'refresh');					

						}
						else 
						{					
							$login_array = array($this->input->post(trim(str_replace(' ','','username'))), $this->input->post(trim(str_replace(' ','','password'))));
						
							if(@$this->CI_auth->process_login($login_array))
							{
								// for log write - start - 2022-03-24
									
								$rcdLoginId = $this->input->post(trim(str_replace(' ','','username')));
								$rcdIPAddress = $_SERVER['REMOTE_ADDR'];
								
								// get users table id
								
								$sql_usersTableId = "SELECT id AS rtnValue FROM users WHERE login_id='$rcdLoginId'";							
								$usersTableId = $this->bm->dataReturnDB1($sql_usersTableId);
								
								$sql_rcdLogin = "INSERT INTO user_login_record(login_id,users_tbl_id,login_dt,login_ip,login_from,entry_at)
												VALUES('$rcdLoginId','$usersTableId',NOW(),'$rcdIPAddress','pcs',NOW())";
												
								$rslt_rcdLogin = $this->bm->dataInsertDB1($sql_rcdLogin);
								
								// for log write - end - 2022-03-24
								
								$session_id = $this->session->userdata('value');
								if($session_id!=$this->session->userdata('session_id'))
								{
									$this->logout();
								}
								else
								{	
									$firstLogin = $this->session->userdata('first_login_track');
									$isexpired = $this->session->userdata('isexpired');
									$username = $this->session->userdata('login_id');

									if($firstLogin == 0){
										$this->session->sess_destroy();
										//$this->cache->clean();
										$data['username'] = $username;
										$data['title'] = '<font color="blue"><b>Please change password.</b></font>';	
										$this->load->view('changePassword',$data);
									}
									else if($isexpired == 1){
										$this->session->sess_destroy();
										//$this->cache->clean();
										$data['username'] = $username;
										$data['title'] = '<font color="blue"><b>Please change password.</b></font>';	
										$this->load->view('changePassword',$data);
									}
									else 
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
											
											$sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
											FROM ctmsmis.tmp_oracle_assignment 
											WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";
											$rslt_assignmentList=$this->bm->dataSelectDb2($sql_assignmentList);
											
											// 2021-04-21 - start	- work on custom_remarks
											if(count($rslt_assignmentList)==0)
											{
												$sql_cnfGkey = "SELECT gkey AS rtnValue FROM ref_bizunit_scoped WHERE id='$org_license'";
												$cnfGkey = $this->bm->dataReturn($sql_cnfGkey);								
												
												$sql_assignmentList="SELECT DISTINCT a.gkey AS unit_gkey,a.id AS cont_no,k.name  AS cnf,a.freight_kind AS cont_status,
												(SELECT ib_vyg FROM argo_carrier_visit
												INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
												WHERE argo_carrier_visit.gkey=b.actual_ib_cv
												) AS rot_no,
												CONCAT(k.address_line1,k.address_line2) AS cnf_addr,
												b.flex_date01,
												(SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit
												INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
												INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
												INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
												WHERE inv_unit_fcy_visit.unit_gkey=a.gkey  FETCH FIRST 1 ROWS ONLY
												)  AS siz,
												(SELECT substr(ref_equip_type.nominal_height,-2) FROM inv_unit
												INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
												INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
												INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
												WHERE inv_unit_fcy_visit.unit_gkey=a.gkey  FETCH FIRST 1 ROWS ONLY
												)  AS height,
												to_date(to_char(b.flex_date01,'yyyy-mm-dd'),'yyyy-mm-dd') AS assignmentDate, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
												mfdch_desc,'' AS custom_remarks,
												b.last_pos_slot
												FROM inv_unit a
												INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
												INNER JOIN inv_goods j ON j.gkey = a.goods
												INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=a.gkey	
												LEFT JOIN ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
												INNER JOIN config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value	
												WHERE (b.flex_date01 BETWEEN to_date(CONCAT(TRUNC(CURRENT_DATE),' 00:00:00'),'yyyy-mm-dd hh24-mi-ss') AND to_date(CONCAT(TRUNC(CURRENT_DATE),' 23:59:59'),'yyyy-mm-dd hh24-mi-ss')) 
												AND j.consignee_bzu = '$cnfGkey' AND config_metafield_lov.mfdch_value!='CANCEL'";
												$rslt_assignmentList=$this->bm->dataSelect($sql_assignmentList);
											}
											
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
											$this->load->view('dashboard',$data);
											$this->load->view('jsAssets');
										}
									}
																							
								}									
							}
							else
							{			
								$data['body']="<font color='red' size=2>Invalid username or password</font>";
								//$sub_data['login_failed'] = "<font color='red' size=2>Invalid username or password</font>";
								//$data['body'] =$sub_data['login_failed'];
								
								$query=$this->welcomePageQuery();
								$rtnVesselList = $this->bm->dataSelect($query);
								$data['rtnVesselList']=$rtnVesselList;
								$data['captchaMsg']="";

								$body = "";
								//$data['body']=$body;

								$this->load->view('cssVesselList');
								$this->load->view('jsVesselList');
								$this->load->view('FrontEnd/header');
								$this->load->view('FrontEnd/slider');
								$this->load->view('FrontEnd/index',$data);
								$this->load->view('FrontEnd/footer');
							}
						}
					}
				}
			}
			else{
				$query=$this->welcomePageQuery();
				$rtnVesselList = $this->bm->dataSelect($query);
				$data['rtnVesselList']=$rtnVesselList;
				$data['captchaMsg']="";
				$body = "";
				$data['body']=$body;

				$this->load->view('cssVesselList');
				$this->load->view('jsVesselList');
				$this->load->view('FrontEnd/header');
				$this->load->view('FrontEnd/slider');
				$this->load->view('FrontEnd/index',$data);
				$this->load->view('FrontEnd/footer');
			}
		}
	
	function welcomePageQuery()
	{
		$qry="SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name,vsl_vessel_visit_details.ib_vyg,vsl_vessel_visit_details.ob_vyg,
substr(argo_carrier_visit.phase,1,2) AS phase_num,SUBSTR(argo_carrier_visit.phase,3) AS phase_str,argo_visit_details.eta,
argo_visit_details.etd,argo_carrier_visit.ata,
argo_carrier_visit.atd,ref_bizunit_scoped.id AS agent,
(SELECT argo_quay.id
FROM argo_quay
INNER JOIN vsl_vessel_berthings brt ON brt.quay=argo_quay.gkey
WHERE brt.vvd_gkey=vsl_vessel_visit_details.vvd_gkey ORDER BY brt.ata DESC FETCH FIRST 1 ROWS ONLY ) AS berth,
NVL(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03) AS berthop
FROM argo_carrier_visit
INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey			
WHERE argo_carrier_visit.phase IN ('20INBOUND','30ARRIVED','40WORKING','50COMPLETE','60DEPARTED')
ORDER BY argo_carrier_visit.phase";
		
		return $qry;
	}
	
		function myPasswordChange(){
		
           //$login_id = $this->session->userdata('login_id');
			$session_id = $this->session->userdata('value');
			if($session_id!=$this->session->userdata('session_id'))
			{
				$this->logout();
			}
			else
			{ 
				$login_id = $this->session->userdata('login_id');
				$data['login_id']=$login_id;
				$data['title']='CHANGE PASSWORD';
				$data['ptitle']="";
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('myChangePasswordForm',$data);
				$this->load->view('jsAssets');
			}
        }
		
		
		function myPasswordChangeUpdateForm()
		{			
			//$data['title']="PassWord Update Successfully";
			$login_id = $this->session->userdata('login_id');
			$current = sha1($_POST['old_password']);
			//$new_password = sha1($_POST['new_password']);
			$password = $this->input->post('new_password');
			$new_password = sha1($password);
			$confirm_password = sha1($_POST['confirm_password']);
			//$checkoldpass = mysqli_query("SELECT new_pass FROM users WHERE login_id='$login_id'");
			$passStr= "SELECT new_pass as rtnValue FROM users WHERE login_id='$login_id'";
			$newpss= $this->bm->dataReturnDb1($passStr);
			//$result = mysqli_fetch_object($checkoldpass);
			///$newpss=$result->new_pass;
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$data['title']='CHANGE PASSWORD';
			$data['login_id']=$login_id;
			
			if($current!=$newpss)
			{
				$data['ptitle']='Current password is not Match. Press back to try again.';
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('myChangePasswordForm',$data);
				$this->load->view('jsAssets');
				return false;
			}
			else if ($new_password == $confirm_password) 
			{
				$expDate = new DateTime('now');
				$expDate->modify('+12 month'); // or you can use '-30 day' for deduction
				$expDate = $expDate->format('Y-m-d h:i:s');
				
				$name = $this->session->userdata('User_Name');
				$login_id = $this->session->userdata('login_id');
			
				$sql = "UPDATE users SET new_pass ='$new_password',ptext='$password',last_date=NOW(),update_by='$login_id',user_ip='$ipaddr' WHERE login_id='$login_id'";
				//mysql_query($sql);
				$updateStat = $this->bm->dataUpdateDB1($sql);
				
				$log_sql = "INSERT INTO user_update_log(update_for,update_by,update_at,ptext,user_ip,expire_date) 
					VALUES('$login_id','$login_id',NOW(),'$password','$ipaddr','$expDate')";
				$insertLog = $this->bm->dataInsertDB1($log_sql);
				
				$dt=date("Y-m-d H:i:s");
				$status="passChangeSelf";			
				$dataWrite="\r\n$login_id | $login_id | $status | $dt |  $ipaddr ";
				write_file("userCreateEditPassChangeLog.txt", $dataWrite, 'a');

				$data['ptitle']="Password Updated Successfully";
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('myChangePasswordForm',$data);
				$this->load->view('jsAssets');
				return false;
			}
		
			else
			{
				$data['ptitle']='New Password did not match. Press back to try again';
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('myChangePasswordForm',$data);
				$this->load->view('jsAssets');
				return false;
			}	
		}
	
	// Rakib & Kawsar 28/04/2020 starts
	function passwordChangeUpdateForm()
	{
			
		//$data['title']="PassWord Update Successfully";
		$login_id = $this->input->post('user_name');
		//$current = sha1($this->input->post('old_password'));
		//$current = sha1($_POST['old_password']);
		$password = $this->input->post('new_password');
		$new_password = sha1($password);
		//$new_password = sha1($_POST['new_password']);
		$confirm_password = sha1($this->input->post('confirm_password'));
		//$confirm_password = sha1($_POST['confirm_password']);
		
		$checkoldpass="SELECT new_pass FROM users WHERE login_id='$login_id'";
		$result = $this->bm->dataSelectDb1($checkoldpass);
		
		//$checkoldpass = mysqli_query("SELECT new_pass FROM users WHERE login_id='$login_id'");
		//$result = mysqli_fetch_object($checkoldpass);
		
		$newpss=$result[0]["new_pass"];
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		
		// if($current!=$newpss)
		// {
		// 	$data['title']="<font color='red'>Current password does not Match.Try again.</font>";
		// 	$data['username'] = $login_id;
		// 	$this->load->view('changePassword',$data);
		// 	return false;
		// }
		// else 
		if($newpss == $new_password)
		{
			$data['title']="<font color='red'>Password can't be same as before!</font>";
			$data['username'] = $login_id;
			$this->load->view('changePassword',$data);
			return false;
		}
		else if ($new_password == $confirm_password) 
		{
			$expDate = new DateTime('now');
			$expDate->modify('+12 month'); // or you can use '-30 day' for deduction
			$expDate = $expDate->format('Y-m-d h:i:s');
			//echo $expDate;
			
			$sql = "UPDATE users SET new_pass ='$new_password',Expire_date='$expDate',ptext='$password',first_login_track = 1,account_update_date=NOW(),update_by='$login_id',user_ip='$ipaddr' WHERE login_id='$login_id'";
			$sqlupdate = $this->bm->dataInsertDB1($sql);

			$log_sql = "INSERT INTO user_update_log(update_for,update_by,update_at,ptext,user_ip,expire_date) VALUES('$login_id','$login_id',NOW(),'$password','$ipaddr','$expDate')";
			$insertLog = $this->bm->dataInsertDB1($log_sql);
			
			//mysql_query($sql);
			$dt=date("Y-m-d H:i:s");
			$status="passChangeSelf";			
			$dataWrite="\r\n$login_id | $login_id | $status | $dt |  $ipaddr ";
			write_file("userCreateEditPassChangeLog.txt", $dataWrite, 'a');
			
			$query="SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name,vsl_vessel_visit_details.ib_vyg,vsl_vessel_visit_details.ob_vyg,
			SUBSTR(argo_carrier_visit.phase,1,2) AS phase_num,SUBSTR(argo_carrier_visit.phase,3) AS phase_str,argo_visit_details.eta,
			argo_visit_details.etd,argo_carrier_visit.ata,
			argo_carrier_visit.atd,ref_bizunit_scoped.id AS agent,argo_quay.id AS berth,
			NVL(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03) AS berthop
			FROM argo_carrier_visit
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
			LEFT JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
			LEFT JOIN argo_quay ON argo_quay.gkey=vsl_vessel_berthings.quay
			WHERE argo_carrier_visit.phase IN ('20INBOUND','30ARRIVED','40WORKING','50COMPLETE','60DEPARTED')
			ORDER BY argo_carrier_visit.phase";
			//echo $data['voysNo'];
			$rtnVesselList = $this->bm->dataSelect($query);
			$data['rtnVesselList']=$rtnVesselList;
			$data['username'] = $login_id;
			
			// $data['body']="<font color='blue'>Your Password Updated Successfully....</font>";
			$data['title']="<font color='green'>Your Password Updated Successfully. Go to <u><b><a href='http://cpatos.gov.bd/pcs/'>Login</a></b></u></font>";
			//$data['title']="Password Updated Successfully";
			//$this->load->view('header2');
			//$this->load->view('welcomeview_1',$data);
			//$this->load->view('footer');
			
			// $this->load->view('cssVesselList');
			// $this->load->view('jsVesselList');
			// $this->load->view('FrontEnd/header');
			// $this->load->view('FrontEnd/slider');
			// $this->load->view('FrontEnd/index',$data);
			// $this->load->view('FrontEnd/footer');

			$this->load->view('changePassword',$data);
			
			return false;
		}
		else
		{
			$data['title']="<font color='red'>New Password did not match. Press back to try again</font>";
			$data['username'] = $login_id;
			//$this->load->view('header2');
			$this->load->view('changePassword',$data);
			//$this->load->view('footer');
			return false;
		}
			
	}
	// Rakib & Kawsar 28/04/2020 ends

		//shaha
	function changePassForClient()
	{
		
		//$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['ptitle']="";
			$loginList="";
			if($org_Type_id=='66')
			{
				$sql = "SELECT id, login_id FROM users WHERE org_Type_id='66'";
				$loginList = $this->bm->dataSelectDb1($sql);
			}
			$data['loginList']=$loginList;
			$data['org_Type_id']=$org_Type_id;
			$data['title']="Client Password Change Form";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('clientPassChangeForm',$data);
			$this->load->view('jsAssets');	
		}
	}
		
	function changePassForClientPerform()
	{
		$expDate = new DateTime('now');
		$expDate->modify('+12 month'); // or you can use '-30 day' for deduction
		$expDate = $expDate->format('Y-m-d h:i:s');

		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
		$org_Type_id = $this->session->userdata('org_Type_id');

		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			if($org_Type_id=='66')
			{
				$loging_id = trim($this->input->post('network_logging_id'));
			}
			else
			{
				$loging_id = trim($this->input->post('loging_id'));
			}
			$new_password = trim($this->input->post('new_password'));
			$confirm_password = trim($this->input->post('confirm_password'));
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$msg = "";
			if($new_password==$confirm_password){
				$this->load->model('ci_auth', 'bm', TRUE);
				$pass = sha1($new_password);
				$cpass = sha1($confirm_password);	
				
				$sqlUser = "select count(id) as rtnValue from users where login_id='$loging_id'";
				$rtnValue = $this->bm->dataReturnDb1($sqlUser);
				$update_by = $this->session->userdata('login_id');									
				
				if($rtnValue>0){
					$str = "UPDATE users SET new_pass='$cpass',last_date=NOW(),expire_date='$expDate',ptext='$new_password',account_update_date=NOW(),first_login_track='1',update_by='$update_by',user_ip='$ipaddr' WHERE login_id='$loging_id'";
					$res = $this->bm->dataUpdateDB1($str);

					$log_sql = "INSERT INTO user_update_log(update_for,update_by,update_at,ptext,user_ip,expire_date) VALUES('$loging_id','$login_id',NOW(),'$new_password','$ipaddr','$expDate')";
					$insertLog = $this->bm->dataInsertDB1($log_sql);

					if($res)
					{
						$dt=date("Y-m-d H:i:s");
						$status="passChangeForClient";						
						$dataWrite="\r\n$update_by | $loging_id | $status | $dt | $ipaddr ";
						write_file("userCreateEditPassChangeLog.txt", $dataWrite, 'a');
						
						$msg = "<font color='green'>Password updated</font>";							
					}
					else
						$msg = "<font color='red'>Password not updated</font>";
				}else{
					$msg = "<font color='red'>There is no users for login id <strong>".$loging_id."</strong></font>";
				}
			}else{
				$msg = "<font color='red'>New password and confirm password is not matched to each others.</font>";
			}
			
			$loginList="";
			if($org_Type_id=='66')
			{
				$sql = "SELECT id, login_id FROM users WHERE org_Type_id='66'";
				$loginList = $this->bm->dataSelectDb1($sql);
			}
			$data['loginList']=$loginList;
			$data['org_Type_id']=$org_Type_id;
			
			$data['title']="Client Password Change Form";
			$data['ptitle']=$msg;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('clientPassChangeForm',$data);
			$this->load->view('jsAssets');	
		}
	}
		
		
	function logout()
	{
	
		/*$this->session->sess_destroy();
		
		$this->cache->clean();
		set_header("cache-Control: no-store, no-cache, must-revalidate");
		set_header("cache-Control: post-check=0, pre-check=0", false);
		set_header("Pragma: no-cache");
		set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	
		
		$this->load->view('header');
		$this->load->view('welcomeview_1', $data);
		$this->load->view('footer');*/
		/*$query="SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,sparcsn4.vsl_vessels.name,sparcsn4.vsl_vessel_visit_details.ib_vyg,sparcsn4.vsl_vessel_visit_details.ob_vyg,
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
		ORDER BY sparcsn4.argo_carrier_visit.phase";*/

		$query="SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name,vsl_vessel_visit_details.ib_vyg,vsl_vessel_visit_details.ob_vyg,
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
		//echo $data['voysNo'];
		$rtnVesselList = $this->bm->dataSelect($query);
		$data['rtnVesselList']=$rtnVesselList;
		
		$data['body']="<font color='blue' size=2>LogOut Successfully....</font>";

		//$this->session->sess_destroy();
		//$this->cache->clean();
								//redirect(base_url(),$data);
		//$this->load->view('header');
		//$this->load->view('welcomeview_1', $data);
		//$this->load->view('footer');
		//$this->db->cache_delete_all();
		
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
		
			$data['captchaMsg']="";
			
		$this->session->sess_destroy();
		// $this->cache->clean();
		$this->load->view('cssVesselList');
		$this->load->view('jsVesselList');
		$this->load->view('FrontEnd/header');
		$this->load->view('FrontEnd/slider');
		$this->load->view('FrontEnd/index',$data);
		$this->load->view('FrontEnd/footer',$data);
		// $this->db->cache_delete_all();
	}
		
	// NOTICE 
	function noticeListToday()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$org_id = $this->session->userdata('org_Type_id');
			$login_id = $this->session->userdata('login_id');

			$sql_row_num="SELECT count(*) as rtnValue FROM (
			SELECT upload_notice.*,'0' AS view_stat,'' AS vw_date
			FROM upload_notice
			INNER JOIN upload_notice_dtl ON upload_notice.id=upload_notice_dtl.notice_id
			WHERE upload_notice.id NOT IN 
			(SELECT notice_id FROM view_notice_log 
			WHERE view_stat=1 AND user_id='$login_id' AND org_id=$org_id)
			AND upload_notice_dtl.org_id=$org_id
			UNION
			SELECT upload_notice.*,'1' AS view_stat,view_notice_log.entry_date AS vw_date
			FROM upload_notice
			INNER JOIN upload_notice_dtl ON upload_notice.id=upload_notice_dtl.notice_id
			INNER JOIN view_notice_log ON upload_notice.id=view_notice_log.notice_id AND upload_notice_dtl.org_id=view_notice_log.org_id
			WHERE upload_notice.id IN 
			(SELECT notice_id FROM view_notice_log 
			WHERE view_stat=1 AND user_id='$login_id' AND org_id=$org_id)
			AND upload_notice_dtl.org_id=$org_id AND user_id='$login_id'
			) AS tbl
			";
			
			//echo $sql_row_num;
			$segment_three = $this->uri->segment(3);
			
			$config = array();
			$config["base_url"] = site_url("login/noticeListToday/$segment_three");
			$config["total_rows"] = $this->bm->dataReturnDb1($sql_row_num);
			$config["per_page"] = 20;
			$offset = $this->uri->segment(4, 0);
			$config["uri_segment"] = 4;
			$limit=$config["per_page"];
			
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$start=$page;
			
			$sql_data="
			SELECT * FROM (
			SELECT upload_notice.*,'0' AS view_stat,'' AS vw_date
			FROM upload_notice
			INNER JOIN upload_notice_dtl ON upload_notice.id=upload_notice_dtl.notice_id
			WHERE upload_notice.id NOT IN 
			(SELECT notice_id FROM view_notice_log 
			WHERE view_stat=1 AND user_id='$login_id' AND org_id=$org_id)
			AND upload_notice_dtl.org_id=$org_id
			UNION
			SELECT upload_notice.*,'1' AS view_stat,view_notice_log.entry_date AS vw_date
			FROM upload_notice
			INNER JOIN upload_notice_dtl ON upload_notice.id=upload_notice_dtl.notice_id
			INNER JOIN view_notice_log ON upload_notice.id=view_notice_log.notice_id AND upload_notice_dtl.org_id=view_notice_log.org_id
			WHERE upload_notice.id IN 
			(SELECT notice_id FROM view_notice_log 
			WHERE view_stat=1 AND user_id='$login_id' AND org_id=$org_id)
			AND upload_notice_dtl.org_id=$org_id AND user_id='$login_id'
			) AS tbl
			";
		
			$noticeList = $this->bm->dataSelectDb1($sql_data);
				
			$data['title']="Notice List...";			
			$data['noticeList']=$noticeList;
			
			$data['start']=$start;
			$data['links'] = $this->pagination->create_links();
			$this->load->view('header2');
			$this->load->view('showAllNotice',$data);
			$this->load->view('footer');
		}
		//}
	}
		
	function searchNotice()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$org_id = $this->session->userdata('org_Type_id');
			$notice_title=trim($this->input->post('notice_title'));
			$login_id = $this->session->userdata('login_id');
			
			$sql_row_num="SELECT count(*) as rtnValue FROM (
			SELECT upload_notice.*,upload_notice_dtl.org_id AS dtl_org,'0' AS view_stat,'' AS vw_date
			FROM upload_notice
			INNER JOIN upload_notice_dtl ON upload_notice.id=upload_notice_dtl.notice_id
			WHERE upload_notice.id NOT IN 
			(SELECT notice_id FROM view_notice_log 
			WHERE view_stat=1 AND user_id='$login_id' AND org_id=$org_id)
			AND upload_notice_dtl.org_id=$org_id
			UNION
			SELECT upload_notice.*,upload_notice_dtl.org_id AS dtl_org,'1' AS view_stat,view_notice_log.entry_date AS vw_date
			FROM upload_notice
			INNER JOIN upload_notice_dtl ON upload_notice.id=upload_notice_dtl.notice_id
			INNER JOIN view_notice_log ON upload_notice.id=view_notice_log.notice_id AND upload_notice_dtl.org_id=view_notice_log.org_id
			WHERE upload_notice.id IN 
			(SELECT notice_id FROM view_notice_log 
			WHERE view_stat=1 AND user_id='$login_id' AND org_id=$org_id)
			AND upload_notice_dtl.org_id=$org_id
			) AS tbl
			WHERE title like '%$notice_title%'
			order by view_stat desc,entry_date
			";
			
			//echo $sql_row_num;
			$segment_three = $this->uri->segment(3);
			
			$config = array();
			$config["base_url"] = site_url("login/noticeListToday/$segment_three");
			$config["total_rows"] = $this->bm->dataReturnDb1($sql_row_num);
			$config["per_page"] = 20;
			$offset = $this->uri->segment(4, 0);
			$config["uri_segment"] = 4;
			$limit=$config["per_page"];
			
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$start=$page;
			
			
			
			
			
			$sql_data="
			SELECT * FROM (
			SELECT upload_notice.*,upload_notice_dtl.org_id AS dtl_org,'0' AS view_stat,'' AS vw_date
			FROM upload_notice
			INNER JOIN upload_notice_dtl ON upload_notice.id=upload_notice_dtl.notice_id
			WHERE upload_notice.id NOT IN 
			(SELECT notice_id FROM view_notice_log 
			WHERE view_stat=1 AND user_id='$login_id' AND org_id=$org_id)
			AND upload_notice_dtl.org_id=$org_id
			UNION
			SELECT upload_notice.*,upload_notice_dtl.org_id AS dtl_org,'1' AS view_stat,view_notice_log.entry_date AS vw_date
			FROM upload_notice
			INNER JOIN upload_notice_dtl ON upload_notice.id=upload_notice_dtl.notice_id
			INNER JOIN view_notice_log ON upload_notice.id=view_notice_log.notice_id AND upload_notice_dtl.org_id=view_notice_log.org_id
			WHERE upload_notice.id IN 
			(SELECT notice_id FROM view_notice_log 
			WHERE view_stat=1 AND user_id='$login_id' AND org_id=$org_id)
			AND upload_notice_dtl.org_id=$org_id AND user_id='$login_id'
			) AS tbl
			WHERE title like '%$notice_title%'
			order by view_stat desc,entry_date
			";
		
			$noticeList = $this->bm->dataSelectDb1($sql_data);
			   
			$data['title']="Notice List...";			
			$data['noticeList']=$noticeList;
			$data['sql_data']=$sql_data;
			
			$data['start']=$start;
			$data['links'] = $this->pagination->create_links();
			$this->load->view('header2');
			$this->load->view('showAllNotice',$data);
			$this->load->view('footer');
			
		}
	}
	//Organization profile starts------------------------
	function OrgProfileForm()
	{
		$session_id = $this->session->userdata('value');			
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$data['title']="ORGANIZATION PROFILE ENTRY";
			
			$query = "SELECT * FROM tbl_org_types";
			$orgTypeList = $this->bm->dataSelectDb1($query);
			$data['orgTypeList']=$orgTypeList;
			$msg = "";
			$frmType = "new";
			$data['msg']=$msg;
			$data['frmType']=$frmType;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('orgProfileEntry',$data);
			$this->load->view('jsAssets');
		}
	}

	function orgProfileEntry()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			$data['title']="ORGANIZATION PROFILE ENTRY";
			
			$msg = "";
			
			$query = "SELECT * FROM tbl_org_types";
			$orgTypeList = $this->bm->dataSelectDb1($query);
			$data['orgTypeList']=$orgTypeList;
			
			$org_type = $this->input->post('org_type');
			$ain_no = $this->input->post('ain_no');
			$lic_no = $this->input->post('lic_no');				
			$lic_validity_date = $this->input->post('lic_validity_date');
			$land_phone_no = $this->input->post('land_phone_no');
			$address_two = $this->input->post('address_two');
			$cell_phone_one = $this->input->post('cell_phone_one');
			$email_address = $this->input->post('email_address');
			//$user_action = $this->input->post('user_action');
			//$lic_no_dh = $this->input->post('lic_no_dh');
			//$payment_status = $this->input->post('payment_status');
			$org_name = $this->input->post('org_name');
			$org_name = str_replace("'","\'",$org_name);
			//$ain_no_new = $this->input->post('ain_no_new');
			//$lic_issue_date = $this->input->post('lic_issue_date');
			$address_one = $this->input->post('address_one');
			//$address_three = $this->input->post('address_three');
			$cell_phone_two = $this->input->post('cell_phone_two');
			$fax = $this->input->post('fax');
			$url = $this->input->post('url');
			//$dummy = $this->input->post('dummy');
			//$agent_code = $this->input->post('agent_code');
			
			$agentCode = $this->input->post('agentCode');

			$frmType = $this->input->post('frmType');
			
			$login_id = $this->session->userdata('login_id');
			$user_ip=$_SERVER['REMOTE_ADDR'];
			if($frmType=="new")
			{
				$orgChkCount = "select count(*) as rtnValue from cchaportdb.organization_profiles where Org_Type_id='$org_type' AND (AIN_No='$ain_no' or AIN_No_New='$ain_no')";
				$orgAinChkCnt = $this->bm->dataReturnDb1($orgChkCount);				
				if($orgAinChkCnt>0)
				{
					$msg = "<font color='red'><b>Sorry! This Organization already exists.</b></font>";
				}
				else
				{	
					$imageName=$_FILES["logo"]["name"];
					if($imageName == ""){
						$newImageName = "";
					}else{
						$ext = pathinfo($imageName, PATHINFO_EXTENSION);
						$newImageName = uniqid().".".$ext;

						move_uploaded_file($_FILES["logo"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/assets/organizationLogo/".$_FILES["logo"]["name"]);	

						rename($_SERVER['DOCUMENT_ROOT']."/assets/organizationLogo/".$_FILES["logo"]["name"],$_SERVER['DOCUMENT_ROOT']."/assets/organizationLogo/".$newImageName);
					}
				
					$insertSql="INSERT INTO cchaportdb.organization_profiles(Org_Type_id,Organization_Name,AIN_No,AIN_No_New,License_No,
					Licence_Validity_Date,Address_1,Address_2,Telephone_No_Land,Cell_No_1,Cell_No_2,Fax_No,email,logo,
					URL,Agent_Code,entered_by,entry_time) 
					VALUES('$org_type','$org_name','$ain_no','$ain_no','$lic_no','$lic_validity_date','$address_one',
					'$address_two','$land_phone_no','$cell_phone_one','$cell_phone_two','$fax','$email_address','$newImageName',
					'$url','$agentCode','$login_id',NOW())";
					$insertStat=$this->bm->dataInsertDB1($insertSql);
					$msg = "<font color='blue'><b>Successfully Inserted</b></font>";
				}
			}
			else if($frmType=="edit")
			{
				$orgprofileid = $this->input->post('orgprofileid');
				$loginid = "";
				$userid = "";
				$user_type = "";
				$strUnameUpdate = "";
				if($org_type==1 or $org_type==2 or $org_type==4){
					if($org_type==1)
					{	
						//MLO
						$loginid = $ain_no."M";
					}
					else if($org_type==2)
					{
						//C&F
						$loginid = $ain_no."CF";
					}
					else if($org_type==4)
					{
						//FF
						$loginid = $ain_no."FF";
					}
					
					$sqlChkUser = "select * from cchaportdb.users where users.login_id='$loginid' AND users.org_id='$orgprofileid'";
					$resChkUser = $this->bm->dataSelectDb1($sqlChkUser);
					if(count($resChkUser)!=0)
					{											
						for($k=0;$k<count($resChkUser);$k++)
						{
							$userid=$resChkUser[$k]['id'];
							$user_type=$resChkUser[$k]['u_type'];
						}
						if($user_type =="organization" or $user_type =="cnf")
						{
							$strUnameUpdate = "update cchaportdb.users set users.u_name='$org_name' 
											where users.id='$userid'";
							$unameUpdateState = $this->bm->dataUpdateDB1($strUnameUpdate);
						}
						
					}
				}
				
				
				$imageQuery = "SELECT logo FROM organization_profiles WHERE id='$orgprofileid'";
				$selectStat = $this->bm->dataSelectDB1($imageQuery);
				
				$oldImage = "";
				for($i = 0;$i<count($selectStat);$i++){
					$oldImage = $selectStat[$i]['logo'];
				}

				$imageName=$_FILES["logo"]["name"];

				if($oldImage != "")
				{
					if($imageName != "")
					{
						unlink($_SERVER['DOCUMENT_ROOT']."/assets/organizationLogo/".$oldImage);

						$ext = pathinfo($imageName, PATHINFO_EXTENSION);
						$newImageName = uniqid().".".$ext;

						move_uploaded_file($_FILES["logo"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/assets/organizationLogo/".$_FILES["logo"]["name"]);
						
						rename($_SERVER['DOCUMENT_ROOT']."/assets/organizationLogo/".$_FILES["logo"]["name"],$_SERVER['DOCUMENT_ROOT']."/assets/organizationLogo/".$newImageName);
					}
					else
					{
						$newImageName = $oldImage;
					}
				}
				else
				{
					if($imageName != "")
					{
						$ext = pathinfo($imageName, PATHINFO_EXTENSION);
						$newImageName = uniqid().".".$ext;

						move_uploaded_file($_FILES["logo"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/assets/organizationLogo/".$_FILES["logo"]["name"]);	

						rename($_SERVER['DOCUMENT_ROOT']."/assets/organizationLogo/".$_FILES["logo"]["name"],$_SERVER['DOCUMENT_ROOT']."/assets/organizationLogo/".$newImageName);
					}
					else
					{
						$newImageName = "";
					}
				}
				
				//Organization Update Log Starts...
				$changed_fields = array();
				
				$previous_org_type_id="";
				$previous_org_name="";
				$previous_ain_no="";
				$previous_ain_no_new="";
				$previous_lic_no="";
				$previous_lic_validity_date="";
				$previous_address_one="";
				$previous_address_two="";
				$previous_land_phone_no="";
				$previous_cell_phone_one="";
				$previous_cell_phone_two="";
				$previous_fax="";
				$previous_email_address="";
				$previous_url="";
				$previous_agentCode="";			
								
				$sqlOrgDtls = "select * from cchaportdb.organization_profiles where organization_profiles.id='$orgprofileid'";
				$resOrgDtls = $this->bm->dataSelectDb1($sqlOrgDtls);															
				for($x=0;$x<count($resOrgDtls);$x++)
				{
					$previous_org_type_id=$resOrgDtls[$x]['Org_Type_id'];
					$previous_org_name=$resOrgDtls[$x]['Organization_Name'];
					$previous_ain_no=$resOrgDtls[$x]['AIN_No'];
					$previous_ain_no_new=$resOrgDtls[$x]['AIN_No_New'];
					$previous_lic_no=$resOrgDtls[$x]['License_No'];
					$previous_lic_validity_date=$resOrgDtls[$x]['Licence_Validity_Date'];
					$previous_address_one=$resOrgDtls[$x]['Address_1'];
					$previous_address_two=$resOrgDtls[$x]['Address_2'];
					$previous_land_phone_no=$resOrgDtls[$x]['Telephone_No_Land'];
					$previous_cell_phone_one=$resOrgDtls[$x]['Cell_No_1'];
					$previous_cell_phone_two=$resOrgDtls[$x]['Cell_No_2'];
					$previous_fax=$resOrgDtls[$x]['Fax_No'];
					$previous_email_address=$resOrgDtls[$x]['email'];
					$previous_url=$resOrgDtls[$x]['URL'];
					$previous_agentCode=$resOrgDtls[$x]['Agent_Code'];
				}
				
				if($previous_org_type_id != $org_type){
					array_push($changed_fields,array("field_name"=>"Org_Type_id","previous_value"=>$previous_org_type_id,"new_value"=>$org_type));
				} 
				if($previous_org_name != $org_name){
					array_push($changed_fields,array("field_name"=>"Organization_Name","previous_value"=>$previous_org_name,"new_value"=>$org_name));
				} 
				if($previous_ain_no != $ain_no){
					array_push($changed_fields,array("field_name"=>"AIN_No","previous_value"=>$previous_ain_no,"new_value"=>$ain_no));
				} 
				if($previous_ain_no_new != $ain_no){
					array_push($changed_fields,array("field_name"=>"AIN_No_New","previous_value"=>$previous_ain_no_new,"new_value"=>$ain_no));
				} 
				if($previous_lic_no != $lic_no){
					array_push($changed_fields,array("field_name"=>"License_No","previous_value"=>$previous_lic_no,"new_value"=>$lic_no));
				} 
				if($previous_lic_validity_date != $lic_validity_date){
					array_push($changed_fields,array("field_name"=>"Licence_Validity_Date","previous_value"=>$previous_lic_validity_date,"new_value"=>$lic_validity_date));
				}
				if($previous_address_one != $address_one){
					array_push($changed_fields,array("field_name"=>"Address_1","previous_value"=>$previous_address_one,"new_value"=>$address_one));
				}
				if($previous_address_two != $address_two){
					array_push($changed_fields,array("field_name"=>"Address_2","previous_value"=>$previous_address_two,"new_value"=>$address_two));
				}
				if($previous_land_phone_no != $land_phone_no){
					array_push($changed_fields,array("field_name"=>"Telephone_No_Land","previous_value"=>$previous_land_phone_no,"new_value"=>$land_phone_no));
				}
				if($previous_cell_phone_one != $cell_phone_one){
					array_push($changed_fields,array("field_name"=>"Cell_No_1","previous_value"=>$previous_cell_phone_one,"new_value"=>$cell_phone_one));
				}
				if($previous_cell_phone_two != $cell_phone_two){
					array_push($changed_fields,array("field_name"=>"Cell_No_2","previous_value"=>$previous_cell_phone_two,"new_value"=>$cell_phone_two));
				}
				if($previous_fax != $fax){
					array_push($changed_fields,array("field_name"=>"Fax_No","previous_value"=>$previous_fax,"new_value"=>$fax));
				}
				if($previous_email_address != $email_address){
					array_push($changed_fields,array("field_name"=>"Fax_No","previous_value"=>$previous_email_address,"new_value"=>$email_address));
				}
				if($previous_url != $url){
					array_push($changed_fields,array("field_name"=>"URL","previous_value"=>$previous_url,"new_value"=>$url));
				}
				if($previous_agentCode != $agentCode){
					array_push($changed_fields,array("field_name"=>"Agent_Code","previous_value"=>$previous_agentCode,"new_value"=>$agentCode));
				}
				
				for($c=0;$c<count($changed_fields);$c++)
				{
					$field_name = $changed_fields[$c]['field_name'];
					$previous_value = $changed_fields[$c]['previous_value'];
					$new_value = $changed_fields[$c]['new_value'];
					
					$log_sql = "INSERT INTO organization_update_log(org_id,field_name,previous_value,new_value,updated_by,updated_at,updated_by_ip) 
							VALUES('$orgprofileid','$field_name','$previous_value','$new_value','$login_id',NOW(),'$user_ip')";
					$insertLog = $this->bm->dataInsertDB1($log_sql);
					
				}
				//Organization Update Log Ends...

				$updateSql="UPDATE cchaportdb.organization_profiles SET Org_Type_id='$org_type',Organization_Name='$org_name',
				AIN_No='$ain_no',AIN_No_New='$ain_no',License_No='$lic_no',
				Licence_Validity_Date='$lic_validity_date',Address_1='$address_one',Address_2='$address_two',
				Telephone_No_Land='$land_phone_no',Cell_No_1='$cell_phone_one',Cell_No_2='$cell_phone_two',Fax_No='$fax',
				email='$email_address',logo = '$newImageName',URL='$url',Agent_Code='$agentCode',
				Last_Update_By_id='$login_id',last_update=NOW()
				where organization_profiles.id='$orgprofileid'";
				$updateStat = $this->bm->dataUpdateDB1($updateSql);
				$msg = "<font color='blue'><b>Successfully Updated</b></font>";
			}
			
			$data['msg'] = $msg;
			
			$frmType = "new";
			$data['msg']=$msg;
			$data['frmType']=$frmType;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('orgProfileEntry',$data);
			$this->load->view('jsAssets');
		}	
	}

	function organizationProfileList()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$Control_Panel = $this->session->userdata('Control_Panel');
		$org_id = $this->session->userdata('org_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$msg="";
			$login_id = $this->session->userdata('login_id');
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			
			if($this->input->post('proId'))
			{
				$proId = $this->input->post('proId');
				
				// org delete log - start
				$sql_prevOrgData = "SELECT id,Org_Type_id,Organization_Name,AIN_No,License_No
								FROM organization_profiles
								WHERE id='$proId'";
				$rslt_prevOrgData = $this->bm->dataSelectDB1($sql_prevOrgData);
				
				$prevId = $rslt_prevOrgData[0]['id'];
				$orgTypeId = $rslt_prevOrgData[0]['Org_Type_id'];
				$orgName = $rslt_prevOrgData[0]['Organization_Name'];
				$ainNo = $rslt_prevOrgData[0]['AIN_No'];
				$licNo = $rslt_prevOrgData[0]['License_No'];
				
				$sql_insertOrgLog = "INSERT organization_profiles_delete_log(prev_id,Org_Type_id,Organization_Name,AIN_No,License_No,delete_at,delete_by,delete_ip)
				VALUES('$prevId','$orgTypeId','$orgName','$ainNo','$licNo',NOW(),'$login_id','$ipAddress')";
				$this->bm->dataInsertDB1($sql_insertOrgLog);
				// org delete log - end
				
				$strDel="DELETE FROM organization_profiles where id='$proId'";
				$rtnRes = $this->bm->dataDeleteDB1($strDel);
				$msg = "<font color='red'><b>Successfully Deleted!</b></font>";
			}
			$data['title']="Organization List";
			
			//pagination starts..........................................................
			$sql_total_org="SELECT COUNT(*) AS rtnValue FROM organization_profiles";			
			$segment_three = $this->uri->segment(3);					
			$config = array();
			$config["base_url"] = site_url("Login/organizationProfileList/$segment_three");
			$config["total_rows"] = $this->bm->dataReturnDb1($sql_total_org);
			$config["per_page"] = 15;
			$offset = $this->uri->segment(4, 0);
			$config["uri_segment"] = 4;
			
			//config for bootstrap pagination class integration starts....
			$config['num_links'] = 5;
			$config['use_page_numbers'] = FALSE;
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['first_link'] = false;
			$config['last_link'] = false;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo';
			$config['prev_tag_open'] = '<li class="prev">';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&raquo';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			//config for bootstrap pagination class integration ends....
			
			$limit=$config["per_page"];
			
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;			
			$start=$page;
			//pagination ends.................................................
			
			if($Control_Panel=="28")
			{
				//Admin panel...
				if($this->input->post('ain_no'))
				{
					$ain_no = $this->input->post('ain_no');
					$data['ain_no'] = $ain_no;
					$query = "SELECT organization_profiles.*,tbl_org_types.Org_Type
					FROM organization_profiles
					INNER JOIN tbl_org_types ON organization_profiles.Org_Type_id=tbl_org_types.id
					WHERE organization_profiles.AIN_No_New='$ain_no'";
				}
				else
				{
					$query = "SELECT organization_profiles.*,tbl_org_types.Org_Type
					FROM organization_profiles
					INNER JOIN tbl_org_types ON organization_profiles.Org_Type_id=tbl_org_types.id
					ORDER BY id DESC";
					// Limit will be used if we want to add the basic PHP Pagination....." LIMIT $start,$limit"
				}
			}
			else
			{
				$query = "SELECT organization_profiles.*,tbl_org_types.Org_Type
					FROM organization_profiles
					INNER JOIN tbl_org_types ON organization_profiles.Org_Type_id=tbl_org_types.id
					WHERE organization_profiles.id='$org_id'";
			}			
			$orgProfileList = $this->bm->dataSelectDb1($query);
			$data['orgProfileList'] = $orgProfileList;
			$data['msg'] = $msg;
			
			//Following 2 lines are for PHP pagination...
			$data['login_id']=$login_id;
			$data['start']=$start;
			$data["links"] = $this->pagination->create_links();
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('organizationProfileList',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function orgProfileUpdate()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{				
			$proId = $this->input->post('proId');
			$Control_Panel = $this->session->userdata('Control_Panel');
			$org_Type_id = $this->session->userdata('org_Type_id');
			$login_id = $this->session->userdata('login_id');
			
			$proDtls="SELECT organization_profiles.*,tbl_org_types.Org_Type
					FROM organization_profiles
					INNER JOIN tbl_org_types ON organization_profiles.Org_Type_id=tbl_org_types.id
					WHERE organization_profiles.id='$proId'";			
			$proDtlsById = $this->bm->dataSelectDB1($proDtls);
			$data['proDtlsById'] = $proDtlsById;
			
			$data['title']="ORGANIZATION PROFILE ENTRY";
			
			$query = "SELECT * FROM tbl_org_types";
			$orgTypeList = $this->bm->dataSelectDb1($query);
			$data['orgTypeList']=$orgTypeList;
			$msg = "";
			$frmType = "edit";
			$data['msg']=$msg;
			$data['frmType']=$frmType;
			$data['proId']=$proId;
			$data['Control_Panel']=$Control_Panel;
			$data['org_Type_id']=$Control_Panel;
			$data['login_id']=$login_id;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('orgProfileEntry',$data);
			$this->load->view('jsAssets');
		}
	}
	//Organization profile ends-------------------------------
	
	//Start Two step verification choose  	

	public function TwoStepVerificationSetting()
{
	$login_id = $this->session->userdata('login_id');
	$phone_number_query="SELECT Cell_No_1   AS rtnValue FROM users WHERE login_id='$login_id'";
	 $phone_number=$this->bm->dataReturnDb1($phone_number_query);
	$two_stp_verify_st_query="SELECT two_stp_st  AS rtnValue FROM users WHERE login_id='$login_id'";
	 $two_stp_verify_st=$this->bm->dataReturnDb1($two_stp_verify_st_query);
	
	if( $two_stp_verify_st==0)
	{
		$two_stp_verify_st=0;
	}
	else if($two_stp_verify_st==1)
	{
		$two_stp_verify_st=1;
	}
	

	$data['msg']="";
	$data['title']="Two step verification setting";
	$data['login_id']=$login_id;
	$data['phone_number']=$phone_number;
	$data['select_status']=$two_stp_verify_st;

	$this->load->view('cssAssets');
	$this->load->view('headerTop');
	$this->load->view('sidebar');
	$this->load->view('twoStepVerifyForm',$data);
	$this->load->view('jsAssets');
}

public function Verification()
{
	
	
	
	    $phone_number=$_GET['phone_no'];
		$log_id=$_GET['log_id'];
		
	    $string=exec('getmac');
		$mac=substr($string, 0, 17);
		$base_url ='https://ej8nq1.api.infobip.com/sms/1/text/query?';
		$username = 'username=datasoft_ctms';
		$pass = '&password=ctmsSMSserv!ce159'; 
        $num_str = sprintf("%04d", mt_rand(1, 9999));
	
		echo $query="UPDATE users SET Cell_No_1 = '$phone_number',two_stp_st = 1,otp_code='$num_str' WHERE login_id = '$log_id'";
		//$this->bm->dataUpdateDB1($query);
		$str='Your%20verification%20code:%20'.$num_str;
        $message = '&sender=8804445654290&to=88'.$phone_number.'&text='.$str.'&from=8804445654290';
		$url = $base_url.''.$username.''.$pass.''.$message;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
	
}
	
	
		
public function UpdatePhoneNumber()

{
	$log_id = $this->input->post('user_name');
	$two_step_verification=$this->input->post('two_step_verification');
	$phone_number=$this->input->post("phone_number");
	if($two_step_verification=="1")
	{
		 $query="UPDATE users SET Cell_No_1 = '$phone_number', two_stp_st = 1 WHERE login_id = '$log_id'";
		$this->bm->dataUpdateDB1($query);
		
		$msg = "<font  size='2' color='green'><b>Phone Number has been Updated</b></font>";
		
		//$login_id = $this->session->userdata('login_id');
		$data['phone_number']=$phone_number;
		$data['select_status']=1;
		$data['msg']=$msg;
		$data['title']="Two step verification setting";
		$data['login_id']=$log_id;
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('twoStepVerifyForm',$data);
		$this->load->view('jsAssets');

	}
	else if($two_step_verification=="0")
	{
		echo $query="UPDATE users SET Cell_No_1 = '$phone_number', two_stp_st = 0 WHERE login_id = '$log_id'";
		$this->bm->dataUpdateDB1($query);
		$msg = "<font  size='2' color='green'><b>Phone Number has been Updated</b></font>";
		$data['phone_number']=$phone_number;
		$data['select_status']=0;
		$data['msg']=$msg;
		$data['title']="Two step verification setting";
		$data['login_id']=$log_id;
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('twoStepVerifyForm',$data);
		$this->load->view('jsAssets');

		//$this->bm->dataUpdateDB1($query);


	}
}
// End Two step verification choose

//Start two step verification with otp
public function UserOtpVerification()
{
	$phone_number=$this->input->post('phone_number');
	$varifycode=$this->input->post('varifycode');
	$user_id=$this->input->post('username');
	$password=$this->input->post('password');
	 $phone_number_query="SELECT  otp_code AS rtnValue FROM users WHERE login_id='$user_id'  AND two_stp_st=1";
	 $otp_code=$this->bm->dataReturnDb1($phone_number_query);
	if($otp_code==$varifycode)
	{
      
		$login_array = array($this->input->post(trim(str_replace(' ','','username'))), $this->input->post(trim(str_replace(' ','','password'))));
		if(@$this->CI_auth->process_login($login_array))
		{					
			// for log write - start - 2022-03-24

			$rcdLoginId = $this->input->post(trim(str_replace(' ','','username')));
			$rcdIPAddress = $_SERVER['REMOTE_ADDR'];

			// get users table id
							
			$sql_usersTableId = "SELECT id AS rtnValue FROM users WHERE login_id='$rcdLoginId'";							
			$usersTableId = $this->bm->dataReturnDB1($sql_usersTableId);
			
			$sql_rcdLogin = "INSERT INTO user_login_record(login_id,users_tbl_id,login_dt,login_ip,login_from,entry_at)
							VALUES('$rcdLoginId','$usersTableId',NOW(),'$rcdIPAddress','pcs',NOW())";
							
			$rslt_rcdLogin = $this->bm->dataInsertDB1($sql_rcdLogin);

			// for log write - end - 2022-03-24

			$session_id = $this->session->userdata('value');
			if($session_id!=$this->session->userdata('session_id'))
			{
				$this->logout();
			}
			else
			{	
				$firstLogin = $this->session->userdata('first_login_track');
				$isexpired = $this->session->userdata('isexpired');
				$username = $this->session->userdata('login_id');

				if($firstLogin == 0){
					$this->session->sess_destroy();
					//$this->cache->clean();
					$data['username'] = $username;
					$data['title'] = '<font color="blue"><b>Please change password.</b></font>';	
					$this->load->view('changePassword',$data);
				}
				else if($isexpired == 1){
					$this->session->sess_destroy();
					//$this->cache->clean();
					$data['username'] = $username;
					$data['title'] = '<font color="blue"><b>Please change password.</b></font>';	
					$this->load->view('changePassword',$data);
				}
				else
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
						
						$sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
						FROM ctmsmis.tmp_oracle_assignment 
						WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";
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
						$this->load->view('dashboard',$data);
						$this->load->view('jsAssets');
					}								
				}																					
			}									
		}
		else
		{			
			$data['body']="<font color='red' size=2>Invalid username or password</font>";
			//$sub_data['login_failed'] = "<font color='red' size=2>Invalid username or password</font>";
			//$data['body'] =$sub_data['login_failed'];
			
			$query=$this->welcomePageQuery();
			$rtnVesselList = $this->bm->dataSelect($query);
			$data['rtnVesselList']=$rtnVesselList;

			$body = "";
			//$data['body']=$body;

			$this->load->view('cssVesselList');
			$this->load->view('jsVesselList');
			$this->load->view('FrontEnd/header');
			$this->load->view('FrontEnd/slider');
			$this->load->view('FrontEnd/index',$data);
			$this->load->view('FrontEnd/footer');
		}
				
	}
	else 
	{
		$msg = "<font color='red'><b>Otp is not matching</b></font>";
		$data['phone_number']=$phone_number;
		$data['user_id']=$user_id;
		$data['user_password']=$password;
		$data['msg']=$msg;
		$this->load->view('cssAssets');
		$this->load->view('verify_two_step_verification',$data);	
		$this->load->view('jsAssets');

	}

}
//End two step verification with otp

	function UserSignUp()
	{
		$msg="";
		$data['msg']="";	
		//$login_id = $this->session->userdata('login_id');
		 $session_id = $this->session->userdata('value');
		 if($session_id!=$this->session->userdata('session_id'))
		 {
			 $this->logout();
		 }
		//  else
		//  { 
			//  $login_id = $this->session->userdata('login_id');
		 	//  $data['login_id']=$login_id;
			 $data['title']='Registration';
		// 	 $data['ptitle']="";
			 
			$this->load->view('cssAssets');
			// $this->load->view('headerTop');
			 //$this->load->view('sidebar');
			 $this->load->view('SignUpForm',$data);
			 $this->load->view('jsAssets');
		// }
	}

	function VerificationPreparation()
	{ 
		$phone_number=$this->input->post('phone_number');
		//$msg="hello";
	    $string=exec('getmac');
		$mac=substr($string, 0, 17);
		$query = "SELECT COUNT(*) AS rtnValue FROM users_reg_otp WHERE users_phone='$phone_number'  AND verified_stat=1";
	
		$chckVerify = $this->bm->dataReturnDb1($query);
		if($chckVerify > 0)
		{
			// take to sign up page
			$data['phone_number']=$phone_number; 
			$data['msg'] = "<font color='green'><b>Phone Number was already Verified</b></font>";
			// $this->load->view('SignUpForm',$data);
			
			// $data['msg']=$msg;
			$this->load->view('cssAssets');
			$this->load->view('signUpStart',$data);

			//$this->load->view('SignUpStart',$data);
			$this->load->view('jsAssets');

		}
		else
		{
			//$msg=1;
			//If the phone number is not verified yet...
			//$validity_time;
			$chckVerify = 0;
			$query = "SELECT COUNT(*) AS rtnValue FROM users_reg_otp WHERE users_phone='$phone_number' AND verified_stat = 0";
			$chckVerify = $this->bm->dataReturnDb1($query);
			if($chckVerify == 0)
			{
				$num_str = sprintf("%04d", mt_rand(1, 9999));
				$query = "INSERT INTO users_reg_otp(users_phone,device_id,otp,otp_validity)
				VALUES('$phone_number','$mac','$num_str',DATE_ADD(NOW(), INTERVAL 5 MINUTE))";
				//print_r($query);
                $this->bm->dataInsertDB1($query);
				$str='Your%20verification%20code:%20'.$num_str;
				//$msg_str='kk';

				//$message = '&sender=8804445654290&to=88'.$phone_number.'&text='.$str.'&from=8804445654290';
				//$url = $base_url.''.$username.''.$pass.''.$message;
				$this->bm->sendSMS($phone_number, $str);	
				/* echo $url;
				return; */
				/* $ch = curl_init();
				curl_setopt($ch,CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_exec($ch); */
				//curl_close($ch);
			}
			else if($chckVerify > 0)
			{ 
				
				$num_str = sprintf("%04d", mt_rand(1, 9999));
				$query="UPDATE users_reg_otp SET otp = '$num_str',otp_validity = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE users_phone = '$phone_number'";
				$this->bm->dataUpdateDB1($query);
				$str='Your%20verification%20code:%20'.$num_str;
				$this->bm->sendSMS($phone_number, $str);

				
				/* $message = '&sender=8804445654290&to=88'.$phone_number.'&text='.$str.'&from=8804445654290';				
				$url = $base_url.''.$username.''.$pass.''.$message;
				 echo $url;
				//return; 
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_exec($ch); */

				
				
			}
			$otpValidity="SELECT otp_validity as  rtnValue FROM users_reg_otp WHERE users_phone= '$phone_number' ";
			$validity_time=$this->bm->dataReturnDb1($otpValidity);
  		    $data['phone_number']=$phone_number;
			  $msg=1;
			
          // $data['num_str']=$num_str; 
			//$data['msg']=$msg; 
			  //$this->load->view('cssAssets');
			  //$this->load->view('SignUpVerification',$data);	
			  //$this->load->view('jsAssets');	
			redirect('Login/SignUpVerificationForm/'.$phone_number.'/'.$validity_time.'/'.$msg, 'refresh');
		}


	}

	function SignUpVerificationForm($phone_number,$validity_time,$msg)
	{
			$times = $validity_time;
			$splitTime = explode("%20",$times);
		    $date =  $splitTime[0];
			$time =$splitTime[1];
			$v_time= $date. " ".$time; 
		    $data['validity_time']=$v_time;  
		    $data['phone_number']=$phone_number; 
			//$data['num_str']=$num_str; 
			if($msg==1)
			{
				$data['msg']="";

			}
			else
			{
				$data['msg']="<font color='red'><b>Wrong OTP </b></font>";

			} 
			
			$data['title']='Registration';
			$data['ptitle']="";
			$this->load->view('cssAssets');
			$this->load->view('SignUpVerification',$data);	
			$this->load->view('jsAssets');
	}

	function VerificationPerform()
	{
		//echo "working";
		$string=exec('getmac');
		$mac=substr($string, 0, 17); 
        $phone_number=$this->input->post('phone_number');
		$varifycode=$this->input->post('varifycode');
		
		// $phone_number=$_POST['phone_number'];
	   	$msg="";
	   	
	 	$query = "SELECT COUNT(*) AS rtnValue FROM users_reg_otp WHERE users_phone='$phone_number' AND otp='$varifycode' AND otp_validity>=NOW()";
		$chckVerify = $this->bm->dataReturnDb1($query);
		
		$varifycode;

		if($chckVerify > 0)
		{

			
			$updateQuery = "UPDATE users_reg_otp set verified_stat='1' WHERE users_phone='$phone_number'";
			$stat = $this->bm->dataUpdateDB1($updateQuery);
			$data['phone_number'] = $phone_number;
			 $data['msg'] = "<font color='green'><b>Correct OTP</b></font>";
			// $msg = "valid";
			//$data['msg']=$msg;
			$this->load->view('cssAssets');
			$this->load->view('signUpStart',$data);
			$this->load->view('jsAssets');
			
		
	
		}
		else
		{
			$otpValidity="SELECT otp_validity as  rtnValue FROM users_reg_otp WHERE users_phone= '$phone_number' ";
			$validity_time=$this->bm->dataReturnDb1($otpValidity); 
		
		    $data['validity_time']=$validity_time;
			$data['phone_number'] = $phone_number;
			$msg=2;
			//$data['msg'] = "<font color='red'><b>OTP was not correct</b></font>";
			//$data['msg']=$msg;
			//$this->load->view('cssAssets');
			//$this->load->view('signUpVerification',$data);
			//$this->load->view('jsAssets');
			redirect('Login/SignUpVerificationForm/'.$phone_number.'/'.$validity_time.'/'.$msg, 'refresh');
			
		}
	}

	function registrationPerformed()
	{
		$userName=$this->input->post('userName');
		$phoneNo=$this->input->post('phoneNo');
		$userNID=$this->input->post('userNID');
		$userPnone=$this->input->post('userPnone');
	    $userPin=$this->input->post('userPin');
	    $userConfirmPin=$this->input->post('userConfirmPin');
		$hash_pin=sha1($userPin);
		$last_date = date("Y-m-d H:i:s");
        
		$nid_front_img =$_FILES['nid_front_image']['name'];
		$nid_back_img =$_FILES['nid_back_image']['name'];
	    $user_img =$_FILES['user_picture']['name'];
		
		$nidFrontName = "nidFront"."_".$phoneNo.".jpg";
		$nidBackName = "nidBack"."_".$phoneNo.".jpg";
		$userImageName = "user"."_".$phoneNo.".jpg";
		$expDate = new DateTime('now');
		$expDate->modify('+12 month'); // or you can use '-30 day' for deduction
		$expDate = $expDate->format('Y-m-d h:i:s');
		
		if($userPin==$userConfirmPin)
		{
			if($nid_front_img!="")
			{
				move_uploaded_file($_FILES['nid_front_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/resources/images/nidFront/".$nid_front_img);
				rename($_SERVER['DOCUMENT_ROOT']."/resources/images/nidFront/".$nid_front_img,$_SERVER['DOCUMENT_ROOT']."/resources/images/nidFront/".$nidFrontName );
			}
			
			if($nid_back_img!="")
			{
				move_uploaded_file($_FILES['nid_back_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/resources/images/nidBack/".$nid_back_img );
				rename($_SERVER['DOCUMENT_ROOT']."/resources/images/nidBack/".$nid_back_img,$_SERVER['DOCUMENT_ROOT']."/resources/images/nidBack/".$nidBackName);
			}
			
			if($user_img!="")
			{
				move_uploaded_file($_FILES['user_picture']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/resources/images/userImage/".$user_img );
				rename($_SERVER['DOCUMENT_ROOT']."/resources/images/userImage/".$user_img,$_SERVER['DOCUMENT_ROOT']."/resources/images/userImage/".$userImageName);
			}
			
			$selectQuery="SELECT id AS rtnValue FROM users_reg_otp WHERE users_phone='$phoneNo'";
			$userId = $this->bm->dataReturnDB1($selectQuery);
			$selectUserId="SELECT COUNT(*) AS rtnValue FROM users_reg_info WHERE users_id='$userId'";
            $uId=$this->bm->dataReturnDB1($selectUserId);
			
			$sql_chkUser = "SELECT COUNT(*) AS rtnValue FROM users WHERE login_id='$phoneNo'";
			$chkUser = $this->bm->dataReturnDB1($sql_chkUser);

			// if($uId > 0)
			if($chkUser > 0)
			{
				
				$msg = "<font color='red'><b>You have already completed your registration</b></font>";
			    $data['msg']="";
				$query=$this->welcomePageQuery();
				$rtnVesselList = $this->bm->dataSelect($query);
				$data['rtnVesselList']=$rtnVesselList;
	
				$body =  "<font color='red'><b>You have already completed your registration</b></font>";
				$data['body']=$body;
	
				$this->load->view('cssVesselList');
				$this->load->view('jsVesselList');
				$this->load->view('FrontEnd/header');
				$this->load->view('FrontEnd/slider');
				$this->load->view('FrontEnd/index',$data);
				$this->load->view('FrontEnd/footer');
				echo "<script type=\"text/javascript\">".
				"alert('You have already completed your registration');".
				"</script>";

			}
			else
			{
				$query = "INSERT INTO users_reg_info(users_id,user_img,nid_front,nid_back,nid)
			    VALUES('$userId','$userImageName','$nidFrontName','$nidBackName','$userNID')";
				$this->bm->dataInsertDB1($query);
				
				$user="INSERT INTO users(u_name,login_id,login_password,new_pass,ptext,last_date,Expire_date,Cell_No_1,first_login_track,org_Type_id)
				VALUES('$userName','$phoneNo','$hash_pin','$hash_pin','$userPin','$last_date','$expDate','$userPnone',1,79)";
				$this->bm->dataInsertDB1($user);

				$msg = "<font color='red'><b>You have already completed your registration</b></font>";
			    $data['msg']="";
				$query=$this->welcomePageQuery();
				$rtnVesselList = $this->bm->dataSelect($query);
				$data['rtnVesselList']=$rtnVesselList;
	
				$body =  "<font color='green'><b>You have successfully completed your registration</b></font>";
				$data['body']=$body;
	
				$this->load->view('cssVesselList');
				$this->load->view('jsVesselList');
				$this->load->view('FrontEnd/header');
				$this->load->view('FrontEnd/slider');
				$this->load->view('FrontEnd/index',$data);
				$this->load->view('FrontEnd/footer');

				echo "<script type=\"text/javascript\">".
				"alert('You have successfully completed your registration');".
				"</script>";

				
				

			}

			

		}
		/*else
		{
			$msg = "<font color='red'><b>Pin  is not matching with Confirmation Pin</b></font>";
			$data['msg']=$msg;
			$data['phone_number']=$phoneNo;
			$this->load->view('cssAssets');
			$this->load->view('signUpStart',$data);
			$this->load->view('jsAssets');
		}*/

	}
// Verification For Password Change Start...	
 function verify_cell_number()
 {
		 
	 $login_id = $this->session->userdata('login_id');
	 $session_id = $this->session->userdata('value');
	 //Menu Expanding....
	 $this->session->set_userdata(array('menu' => "accountSetting"));
	 $this->session->set_userdata(array('sub_menu' => "verify_cell_number"));
	//Menu Expanding....
	if($session_id!=$this->session->userdata('session_id'))
	{
		$this->logout();
	}
	else
	{ 

		$phone_number_query="SELECT Cell_No_1  AS rtnValue FROM users WHERE login_id='$login_id'";
		$phoneNumber=$this->bm->dataReturnDb1($phone_number_query);
		$msg="";
		if($phoneNumber=="")
		{
			//$msg="<font color='red'><b>Your Contract Number Has Not Found,Please Contract With CTMS Customer Care.</b></font>";
			$msg="Your Contact Number Has Not Found,Please Contact With CTMS Customer Care.";
			$data['msg']=$msg;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('verifyView',$data);
			$this->load->view('jsAssets');
		}
		else
		{
			    $num_str = sprintf("%04d", mt_rand(1, 9999));
				//$query="UPDATE users_reg_otp SET otp = '$num_str',otp_validity = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE users_phone = '$phoneNumber'";
				$query="UPDATE users SET otp_code = '$num_str' WHERE Cell_No_1 = '$phoneNumber'";
				$this->bm->dataUpdateDB1($query);
				$str='Your%20verification%20code:%20'.$num_str;
				$this->bm->sendSMS($phoneNumber, $str);
				$data['phone_number']=$phoneNumber;
				$data['title']='Verification';
				$data['ptitle']="";
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('verificationForPasswordChange',$data);
				$this->load->view('jsAssets');
		}
		
	}
}
 function verify_with_otp()
 {
	$phone_number=$this->input->post('phone_number');
	$varifycode=$this->input->post('varifycode');

	//$query = "SELECT COUNT(*) AS rtnValue FROM users_reg_otp WHERE users_phone='$phone_number' AND otp='$varifycode' AND otp_validity>=NOW()";
	$query = "SELECT COUNT(*) AS rtnValue FROM users WHERE Cell_No_1='$phone_number' AND otp_code='$varifycode'";
	$user = $this->bm->dataReturnDb1($query);
	if($user>0)
	{
		//If provided OTP is right...
		$login_id = $this->session->userdata('login_id');
		$data['login_id']=$login_id;
		$data['title']='CHANGE PASSWORD';
		$data['ptitle']="";
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('myChangePasswordForm',$data);
		$this->load->view('jsAssets');


	}
	else
	{
		//If provided OTP is wrong...
		$data['phone_number']=$phone_number;
		$data['title']='Verification';
		$data['ptitle']="Wrong OTP";
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('verificationForPasswordChange',$data);
		$this->load->view('jsAssets');

	}

 }
 
 // Verification For Password Change Finish...
 
 // Verification For Two Step Verification Setting Start... 
 function two_step_verify_cell_number()
 {
		 
	 $login_id = $this->session->userdata('login_id');
	 $session_id = $this->session->userdata('value');
	//Menu Expanding....
	$this->session->set_userdata(array('menu' => "accountSetting"));
	$this->session->set_userdata(array('sub_menu' => "two_step_verify_cell_number"));
	//Menu Expanding....
	if($session_id!=$this->session->userdata('session_id'))
	{
		$this->logout();
	}
	else
	{ 

		$phone_number_query="SELECT Cell_No_1  AS rtnValue FROM users WHERE login_id='$login_id'";
		$phoneNumber=$this->bm->dataReturnDb1($phone_number_query);
		$msg;
		if($phoneNumber=="")
		{
			//$msg="<font color='red'><b>Your Contract Number Has Not Found,Please Contract With CTMS Customer Care.</b></font>";
			$msg="Your Contract Number Has Not Found,Please Contract With CTMS Customer Care.";
			$data['msg']=$msg;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('twoStepVerifyView',$data);
			$this->load->view('jsAssets');
		}
		else
		{
			    $num_str = sprintf("%04d", mt_rand(1, 9999));
				//$query="UPDATE users_reg_otp SET otp = '$num_str',otp_validity = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE users_phone = '$phoneNumber'";
				$query="UPDATE users SET otp_code = '$num_str' WHERE Cell_No_1 = '$phoneNumber'";
				$this->bm->dataUpdateDB1($query);
				$str='Your%20verification%20code:%20'.$num_str;
				$this->bm->sendSMS($phoneNumber, $str);
				$data['phone_number']=$phoneNumber;
				$data['title']='Verification';
				$data['ptitle']="";
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('twoStepVerificationForPasswordChange',$data);
				$this->load->view('jsAssets');
		}
		
	}
}



function two_step_verify_with_otp()
 {
	$phone_number=$this->input->post('phone_number');
	$varifycode=$this->input->post('varifycode');

	//$query = "SELECT COUNT(*) AS rtnValue FROM users_reg_otp WHERE users_phone='$phone_number' AND otp='$varifycode' AND otp_validity>=NOW()";
	$query = "SELECT COUNT(*) AS rtnValue FROM users WHERE Cell_No_1='$phone_number' AND otp_code='$varifycode'";
	$user = $this->bm->dataReturnDb1($query);
	if($user>0)
	{
		$login_id = $this->session->userdata('login_id');
		$phone_number_query="SELECT Cell_No_1   AS rtnValue FROM users WHERE login_id='$login_id'";
		$phonenumber=$this->bm->dataReturnDb1($phone_number_query);
		$two_stp_verify_st_query="SELECT two_stp_st  AS rtnValue FROM users WHERE login_id='$login_id'";
		$two_stp_verify_st=$this->bm->dataReturnDb1($two_stp_verify_st_query);
		
		if( $two_stp_verify_st==0)
		{
			$two_stp_verify_st=0;
		}
		else if($two_stp_verify_st==1)
		{
			$two_stp_verify_st=1;
		}
		
	
		$data['msg']="";
		$data['title']="Two step verification setting";
		$data['login_id']=$login_id;
		$data['phone_number']=$phonenumber;
		$data['select_status']=$two_stp_verify_st;
	
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('twoStepVerifyForm',$data);
		$this->load->view('jsAssets');


	}
	else
	{
		$data['phone_number']=$phone_number;
		$data['title']='Verification';
		$data['ptitle']="Wrong OTP";
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('twoStepVerificationForPasswordChange',$data);
		$this->load->view('jsAssets');

		
	}

 }
// Verification For Two Step Verification Setting Finish...		

function getOracledata(){
	//return;
/*	$oracle_db=$this->load->database('fourth',true);
	$q = $oracle_db->query("select * from ACTIVEMQ_LOCK");
	$result = $q->result_array();
	print_r ($result);*/
	/*$q="select * from ACTIVEMQ_LOCK fetch first 5 rows only";
	$result =$this->bm->dataSelectDb3($q);
	print_r ($result);*/

	$oracle_db=$this->load->database('sixth',true);
	$q = $oracle_db->query("select * from bil_tariffs fetch first 1 rows only");
	$result = $q->result_array();
	print_r ($result);

}


	


}
?>
