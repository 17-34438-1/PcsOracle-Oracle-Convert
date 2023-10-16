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
        
    function index()
	{		
		$abc['user_id']=$this->session->userdata('login_index_id');
		
		$sub_data['login_failed'] ='';
		$data['title'] = 'Login';
		
		if($this->session->userdata('login_id'))
		{
		//	$this->bm->updateAssignmet();		// 2021-03-14
			
			$org_Type_id = $this->session->userdata('org_Type_id');
			$data['org_Type_id']=$org_Type_id;
			if($org_Type_id==2)	{
				//Code for C&F Assignment List in dashboard starts-----------------
				$org_license = $this->session->userdata('org_license');
				$data['msg'] = "";
				$cond = "";
				$sql_assignmentList = "";
				
				// $sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
				// FROM ctmsmis.tmp_vcms_assignment 
				// WHERE Block_No = 'NCY' AND cf_lic='$org_license' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";
				
				$sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
				FROM ctmsmis.tmp_vcms_assignment 
				WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";
				$rslt_assignmentList=$this->bm->dataSelect($sql_assignmentList);
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
		else if($this->input->post('submit_login')) 
		{	
			$this->form_validation->set_rules('username', 'username', 'trim|required|min_length[2]|max_length[20]');
			$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[5]|max_length[35]');
			$this->form_validation->set_error_delimiters('<div style="color:red;">', '</div>');
			
			if($this->form_validation->run() == FALSE)
			{						
				$query=$this->welcomePageQuery();
				$rtnVesselList = $this->bm->dataSelect($query);
				$data['rtnVesselList']=$rtnVesselList;
			
				$data['body'] = $sub_data['login_failed'];

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
				//	$this->bm->updateAssignmet();			// 2021-03-14
					
					$login_array = array($this->input->post(trim(str_replace(' ','','username'))), $this->input->post(trim(str_replace(' ','','password'))));
				
					if(@$this->CI_auth->process_login($login_array))
					{					
						$session_id = $this->session->userdata('value');
						if($session_id!=$this->session->userdata('session_id'))
						{
							$this->logout();
						}
						else
						{	
							$username=$this->input->post(trim(str_replace(' ','','username')));
							$password=$this->input->post(trim(str_replace(' ','','password')));
						
							$query ="select Expire_date as rtnValue from users where login_id='$username' and md5(new_pass)='$password'";
							$rtnValue  = $this->bm->dataReturnDb1($query);
							
							$queryDate ="select datediff('$rtnValue',now()) as myexpiry";
							$rtnValuedate  = $this->bm->dataSelectDb1($queryDate);
							$rtnV = $rtnValuedate[0]["myexpiry"];
							$rtnV = 10;
						
							if($rtnV<0 or $rtnV=="" or $rtnV==null)
							{
								$this->session->sess_destroy();
								$this->cache->clean();
								$data['username'] = $username;
								$data['title'] = '<font color="blue"><b>Your Password expired, Please change password.</b></font>';
																
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
									// FROM ctmsmis.tmp_vcms_assignment 
									// WHERE Block_No = 'NCY' AND cf_lic='$org_license' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";
									
									$sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
									FROM ctmsmis.tmp_vcms_assignment 
									WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";
									$rslt_assignmentList=$this->bm->dataSelect($sql_assignmentList);
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
			}
		}
		else
		{
			$query=$this->welcomePageQuery();
			$rtnVesselList = $this->bm->dataSelect($query);
			$data['rtnVesselList']=$rtnVesselList;

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
		$qry="SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,sparcsn4.vsl_vessels.name,sparcsn4.vsl_vessel_visit_details.ib_vyg,sparcsn4.vsl_vessel_visit_details.ob_vyg,
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
		ORDER BY sparcsn4.argo_carrier_visit.phase";
		
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
		
		
		function myPasswordChangeUpdateForm(){
			
			//$data['title']="PassWord Update Successfully";
			$login_id = $this->session->userdata('login_id');
			$current = sha1($_POST['old_password']);
			$new_password = sha1($_POST['new_password']);
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
	
			else if ($new_password == $confirm_password) {
				$name = $this->session->userdata('User_Name');
				$login_id = $this->session->userdata('login_id');
			
			$sql = "UPDATE users SET new_pass ='$new_password',last_date=NOW(),update_by='$login_id',user_ip='$ipaddr' WHERE login_id='$login_id'";
				//mysql_query($sql);
				$updateStat = $this->bm->dataUpdateDB1($sql);
				
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
		$current = sha1($this->input->post('old_password'));
		//$current = sha1($_POST['old_password']);
		$new_password = sha1($this->input->post('new_password'));
		//$new_password = sha1($_POST['new_password']);
		$confirm_password = sha1($this->input->post('confirm_password'));
		//$confirm_password = sha1($_POST['confirm_password']);
		
		$checkoldpass="SELECT new_pass FROM users WHERE login_id='$login_id'";
		$result = $this->bm->dataSelectDb1($checkoldpass);
		
		//$checkoldpass = mysqli_query("SELECT new_pass FROM users WHERE login_id='$login_id'");
		//$result = mysqli_fetch_object($checkoldpass);
		
		$newpss=$result[0]["new_pass"];
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		
		if($current!=$newpss)
		{
			$data['title']="<font color='red'>Current password does not Match.Try again.</font>";
			$data['username'] = $login_id;
			$this->load->view('changePassword',$data);
			return false;
		}
		
	
		else if ($new_password == $confirm_password) {
			$expDate = new DateTime('now');
			$expDate->modify('+3 month'); // or you can use '-90 day' for deduction
			$expDate = $expDate->format('Y-m-d h:i:s');
			//echo $expDate;
			
			$sql = "UPDATE users SET new_pass ='$new_password',Expire_date='$expDate',update_by='$login_id',user_ip='$ipaddr' WHERE login_id='$login_id'";
			$sqlupdate = $this->bm->dataUpdateDB1($sql);
			//mysql_query($sql);
			
			$dt=date("Y-m-d H:i:s");
			$status="passChangeSelf";			
			$dataWrite="\r\n$login_id | $login_id | $status | $dt |  $ipaddr ";
			write_file("userCreateEditPassChangeLog.txt", $dataWrite, 'a');
			
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
			
			$data['body']="<font color='blue'>Your Password Updated Successfully....</font>";
			//$data['title']="Password Updated Successfully";
			//$this->load->view('header2');
			//$this->load->view('welcomeview_1',$data);
			//$this->load->view('footer');
			
			$this->load->view('cssVesselList');
			$this->load->view('jsVesselList');
			$this->load->view('FrontEnd/header');
			$this->load->view('FrontEnd/slider');
			$this->load->view('FrontEnd/index',$data);
			$this->load->view('FrontEnd/footer');
			
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
					$str = "UPDATE users SET new_pass='$cpass',last_date=NOW(),update_by='$update_by',user_ip='$ipaddr' WHERE login_id='$loging_id'";
					$res = $this->bm->dataUpdateDB1($str);
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

		//$this->session->sess_destroy();
		//$this->cache->clean();
								//redirect(base_url(),$data);
		//$this->load->view('header');
		//$this->load->view('welcomeview_1', $data);
		//$this->load->view('footer');
		//$this->db->cache_delete_all();
		
		$this->session->sess_destroy();
		$this->cache->clean();
		$this->load->view('cssVesselList');
		$this->load->view('jsVesselList');
		$this->load->view('FrontEnd/header');
		$this->load->view('FrontEnd/slider');
		$this->load->view('FrontEnd/index',$data);
		$this->load->view('FrontEnd/footer');
		$this->db->cache_delete_all();
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
			//$ain_no_new = $this->input->post('ain_no_new');
			//$lic_issue_date = $this->input->post('lic_issue_date');
			$address_one = $this->input->post('address_one');
			//$address_three = $this->input->post('address_three');
			$cell_phone_two = $this->input->post('cell_phone_two');
			$fax = $this->input->post('fax');
			$url = $this->input->post('url');
			//$dummy = $this->input->post('dummy');
			//$agent_code = $this->input->post('agent_code');
			
			$orgprofileid = $this->input->post('orgprofileid');
			$frmType = $this->input->post('frmType');
			
			$login_id = $this->session->userdata('login_id');
			$user_ip=$_SERVER['REMOTE_ADDR'];
			if($frmType=="new")
			{
				$sqlChk = "SELECT COUNT(*) AS rtnValue FROM organization_profiles WHERE Org_Type_id='$org_type' AND AIN_No='$ain_no'";
				$rtnValue = $this->bm->dataReturnDb1($sqlChk);
				if($rtnValue==0)
				{
					$insertSql="INSERT INTO cchaportdb.organization_profiles(Org_Type_id,Organization_Name,AIN_No,AIN_No_New,License_No,
					Licence_Validity_Date,Address_1,Address_2,Telephone_No_Land,Cell_No_1,Cell_No_2,Fax_No,email,URL) 
					VALUES('$org_type','$org_name','$ain_no','$ain_no','$lic_no','$lic_validity_date','$address_one',
					'$address_two','$land_phone_no','$cell_phone_one','$cell_phone_two','$fax','$email_address','$url')";
					$insertStat=$this->bm->dataInsertDB1($insertSql);
					$msg = "<font color='blue'><b>Successfully Inserted</b></font>";
				}
				else
				{
					$msg = "<font color='red'><b>Sorry! Organization information already  exists!!!</b></font>";
				}				
			}
			else if($frmType=="edit")
			{
				$updateSql="UPDATE cchaportdb.organization_profiles SET Org_Type_id='$org_type',Organization_Name='$org_name',
				AIN_No='$ain_no',AIN_No_New='$ain_no',License_No='$lic_no',
				Licence_Validity_Date='$lic_validity_date',Address_1='$address_one',Address_2='$address_two',
				Telephone_No_Land='$land_phone_no',Cell_No_1='$cell_phone_one',Cell_No_2='$cell_phone_two',Fax_No='$fax',
				email='$email_address',URL='$url' where organization_profiles.id='$orgprofileid'";
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

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$msg="";
			if($this->input->post('proId'))
			{
				$proId = $this->input->post('proId');
				$strDel="DELETE FROM organization_profiles where id='$proId'";
				$rtnRes = $this->bm->dataDeleteDB1($strDel);
				$msg = "<font color='red'><b>Successfully Deleted!</b></font>";
			}
			$data['title']="Organization List";
			
			$query = "SELECT organization_profiles.*,tbl_org_types.Org_Type
					FROM organization_profiles
					INNER JOIN tbl_org_types ON organization_profiles.Org_Type_id=tbl_org_types.id
					ORDER BY id DESC";
			$orgProfileList = $this->bm->dataSelectDb1($query);
			$data['orgProfileList'] = $orgProfileList;
			$data['msg'] = $msg;
			
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
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('orgProfileEntry',$data);
			$this->load->view('jsAssets');
		}
	}
	//Organization profile ends-------------------------------
}
?>