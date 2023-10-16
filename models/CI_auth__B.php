<?php
//error_reporting(E_ALL ^ E_DEPRECATED);
class CI_auth extends CI_Model {

   public function __construct()
    {
        parent::__construct();
            $this->load->library('session'); 
            $this->load->database();
            $this->load->helper('url');
	    	$this->load->helper('file');
			
			
			
	}
	
	public function record_count() {
        return $this->db->count_all("igm_masters");
    }
	
	public function record_count_igm() {
        return $this->db->count_all("igm_details");
    }
	
	function process_login($login_array_input = NULL){
			if(!isset($login_array_input) OR count($login_array_input) != 2)
                return false;
            
			//set its variable
            $username = $login_array_input[0];
            $password = $login_array_input[1];
			
			$row = "";
			$mdata="";
			$mdatap="";
			$userPass1="";
			
			$row_org ="";
			$mdata_org="";
			
			$row_license ="";
			$mdata_license="";
			
            // select data from database to check user exist or not?
			//echo "select * from users where new_pass is null and login_id='$username'";
			$query=$this->db->query("select * from users where new_pass is null and login_id='$username'");
			if ($query->num_rows()>0)
            {	
				return false;
			}
			else
			{
				
				$result=$this->db->query("select *,md5(new_pass) as dpass,IF(DATEDIFF(Expire_date,NOW())<=0,1,IF(Expire_date IS NULL,1,0)) AS isexpired from users where login_id='$username'");
				//echo "select *,md5(new_pass) as dpass from users where login_id='$username'";
				if($result->num_rows() > 0)
				{
					$row = $result->row();
					$mdata=$row->org_Type_id;
					$mdatap=$row->login_password;
					$userPass1=$row->dpass;
										
				}
				
				//organization type
				$mdata_org="";
				$result_org=$this->db->query("select * from tbl_org_types where id='$mdata'");
				//echo "select * from tbl_org_types where id='$mdata'";
				if($result_org->num_rows() > 0)
				{
					$row_org =$result_org->row();
					$mdata_org=$row_org->Org_Type;
				}
				
				//license number
				$mdata_license="";
				$mdata_Organization_Name="";
				$agentCode="";
				$result_license=$this->db->query("select * from organization_profiles where id='$row->org_id'");
				// echo ("select * from organization_profiles where id='$row->org_id'");return;
				if($result_license->num_rows()>0)
				{
					$row_license =$result_license->row();
					$mdata_license=$row_license->License_No;
					$mdata_Organization_Name=$row_license->Organization_Name;
					$agentCode=$row_license->Agent_Code;
				}
				// echo $agentCode;return;
					
				if($result->num_rows()>0 && $userPass1==$password)
				{
					//echo "select * from users where login_id='$username' and md5(new_pass)='$password'";
					//return;
					$resultP=$this->db->query("select * from users where login_id='$username' and md5(new_pass)='$password'");
					
					if($resultP->num_rows()==1)
					{
							$ipAddress=$this->ip_address = $_SERVER['REMOTE_ADDR'];
							$this->session->set_userdata('ip_address', $ipAddress);
							$s2=date("Y-m-d H:i:s");
							$data=" $s2 |$username |$ipAddress \n";
							write_file("serverlogin.txt", $data, 'a');
							
							$data2=$_SERVER['REMOTE_ADDR']."|".$username."|".$s2."|".$_SERVER['HTTP_USER_AGENT'] ."|".$_SERVER['HTTP_ACCEPT'] ."|".$_SERVER['HTTP_ACCEPT_LANGUAGE'] ."|".$_SERVER['HTTP_ACCEPT_ENCODING']."|".@$_SERVER['HTTP_ACCEPT_CHARSET'] ."|\n";
							write_file('LoginClientSession.log', $data2, 'a');
							
							if($mdata==2)
							{
								//C&F
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>2,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==5)
							{
								//Port
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>12,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==6)	//'Control_Panel'=>22 Bearth, Offdock
							{
								//OffDock
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>22,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==57 or $mdata==10)	//'Control_Panel'=>57 Bearth
							{
								//OffDock
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>57,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>@$mdata_license,'org_name'=>$mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==30)	//'Control_Panel'=>30 Bearth
							{
								//Bearth
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>30,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==28)	//'Control_Panel'=>28 Admin
							{
								//Bearth
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>28,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==56)
							{
								//Zia Arastu
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>56,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==58)
							{
								//special
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>58,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==59)
							{
								//Shed User
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'Control_Panel'=>59,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>@$mdata_license,'org_name'=>$mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							
							if($mdata==61)
							{
								//CPA Gate
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>61,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==62)
							{
								//One Stop
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>62,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==13)
							{
								//Bank
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>13,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'cp_bank_code'=>$row->section,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							/*if($mdata==2)
							{
								//C&F
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>2,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							*/
							if($mdata==1)
							{
								//MLO
								// echo "MLO";return;
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>1,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'agentCode'=>$agentCode,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==4)
							{
								//FREIGHT
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>4,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==64)
							{
								//devpilot
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>64,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==69)
							{
								//devicd
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>69,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==65)
							{
								//devpilot
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>65,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==66)
							{
								//network
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>66,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							
							if($mdata==67)
							{
								//security
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>67,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==3)
							{
								//custom
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>3,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==70)
							{
								//BB Shed User
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>70,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==72)
							{
								//P Shed
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>72,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==73)
							{
								//devffassoc
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>72,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==74)
							{
								//CPA Administration
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>72,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==75)
							{
								//CPA Yard
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>75,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==76)
							{
								//CPA Yard
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>76,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							// if($mdata==77)
							// {
								// //Auction Module
								// $this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>77,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								// 'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								// 'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								// return true;
							// }
							if($mdata==78)
							{
								// CPA Electric Department
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>76,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==79)
							{
								// Truck Entry
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>79,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==80)
							{
								// ONESTOP Documentation
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>80,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==81)
							{
								// Harbour Master
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>81,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==82)
							{
								// CPA Accounts
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>82,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==83)
							{
								// CPA Marine
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>83,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==84)
							{
								// CPA Monitor
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>84,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==85)
							{
								// CPA Gate Sergeant
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>85,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==87)
							{
								// CPA Fire Dept
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>87,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==88)
							{
								// Exen Department
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>85,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							if($mdata==89)
							{
								// Sub Assistant Engineer Department
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>85,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}

							//Break Bulk Panels Details  START //ASIF And Anit 
							//CPA 26/2/23
							if($mdata==91){
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>91,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							//Shiping Agent 26/2/23
							if($mdata==93){
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>93,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
						//	Break Bulk 26/2/23
							if($mdata==95){
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>95,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}
							//Break Bulk Panels Details  END Asif And Anit
                            //marine 04/04/2023
							if($mdata==101){
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>101,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}	
							//Container Billing 16/04/2023
							if($mdata==103){
								$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>103,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
								'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
								'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
								return true;
							}




							return false;						
														
					}
					
				}
				return false;
				
				
			}
			return false;
		}
			
      

	
	function report_feeder($rotation){
	
		try {
			
			$result=$this->db->query("select distinct submitee_org_id,organization_profiles.Organization_Name as Organization_Name,organization_profiles.Agent_Code,mlocode as mlocode from igm_details 
							inner join organization_profiles on igm_details.Submitee_Org_Id=organization_profiles.id 
							where Import_Rotation_No='$rotation' order by Organization_Name,mlocode");
			echo $result->num_rows() ;
			$data = array_shift($result->result_array());
			//echo($data['submitee_org_id']);
			//$query = $this->db->get()->result_array();
            return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
		
	}
	
	function check_logged(){
	
		//print_r($this->session->all_userdata());
		return ($this->session->userdata('login_index_id'))?TRUE:FALSE;

	}
	

	function logged_id(){
		return ($this->check_logged())?$this->session->userdata('login_index_id'):'';
	}
	
	function check_ip(){
	
		try {
			$ip=$_SERVER['REMOTE_ADDR'];
			$result=$this->db->query("select count(id) as cnt from user_ip where ip_address like '$ip%'");
			$row_org =$result->row();
			$data=$row_org->cnt;
			if($data==0){
				$ipsub=substr($ip,0,8);
				$ipsub2=substr($ip,0,7);
				if($ipsub=="103.230."){
					$data=1;
				}elseif($ipsub2=="119.40."){
					$data=1;
				}elseif($ipsub2=="119.30."){
					$data=1;
				}else{
					$data=1;
				}
			}
			//print "SQL Query: ".$this->db->last_query(); 
            return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
		
	}
	function myListForm($type,$limit, $start) {
        try {
			
			$this->db->limit($limit, $start);
			$this->db->select('igm_masters.id,igm_masters.Import_Rotation_No,igm_masters.Export_Rotation_No,igm_masters.Sailed_Year,
igm_masters.Sailed_Date,vessels_berth_detail.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igm_masters.Vessel_Id,
igm_masters.Vessel_Name,igm_masters.Voy_No,igm_masters.Net_Tonnage,
igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
igm_masters.file_clearence_date,Organization_Name as org_name,igm_masters.Submitee_Org_Type 
as Submitee_Org_Type,igm_masters.S_Org_License_Number as S_Org_License_Number,igm_masters.Submission_Date 
as Submission_Date,igm_masters.flag as flag,igm_masters.imo as imo,igm_masters.line_belongs_to as line_belongs_to ');
            $this->db->from('igm_masters');
			$this->db->join('vessels_berth_detail', 'vessels_berth_detail.igm_id = igm_masters.id', 'left');
			$this->db->join('organization_profiles', 'organization_profiles.id = igm_masters.Submitee_Org_Id', 'left');
            $this->db->where('vsl_dec_type', $type );
			$this->db->order_by('file_clearence_date', 'desc');
			$data = $this->db->get()->result_array();
			
			//echo $this->db->_compile_select(); 
			
			//print "SQL Query: ".$this->db->last_query();  

            return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	// Vessel Search
	function myListSearch($type,$SearchCriteria, $Searchdata) {
        try {
			
			
			If($SearchCriteria=="VName")
			{
	
				$query = $this->db->query("
				Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
				igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
				left join vessels_berth_detail vas on igms.id=vas.igm_id 
				where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='$type' and igms.Vessel_Name Like '%$Searchdata%' order by 1 desc");

			}

			If($SearchCriteria=="port")
			{
				$query = $this->db->query("
				Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,
				vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,
				igms.Voy_No,igms.Net_Tonnage,
				igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,
				igms.file_clearence_date,(select org.Organization_Name from organization_profiles org 
				where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,
				igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date 
				from igm_masters igms  
				left join vessels_berth_detail vas on igms.id=vas.igm_id 
				where igms.delivery_status=0 and igms.vsl_dec_type='$type' and igms.Port_of_Shipment 
				Like '%$Searchdata%' order by 1 desc");


			}
			If($SearchCriteria=="Voy")
			{
				$query = $this->db->query("
				Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
				igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
				left join vessels_berth_detail vas on igms.id=vas.igm_id 
				where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='$type' and igms.Voy_No Like '%$Searchdata%' order by 1 desc");
			}
			If($SearchCriteria=="Import")
			{

				if($type=='GM' or  $type=='TS' )
				{	///separating GM and TS from BB
					$query = $this->db->query("Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
					igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
					left join vessels_berth_detail vas on igms.id=vas.igm_id 
					where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='GM' and igms.Import_Rotation_No Like '$Searchdata' order by 1 desc");
					//print($str);
				}
				else
				{
					$query = $this->db->query("Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
					igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
					left join vessels_berth_detail vas on igms.id=vas.igm_id 
					where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='$type' and igms.Import_Rotation_No Like '%$Searchdata%' order by 1 desc");

				}
	
			}

			If($SearchCriteria=="Export")
			{
				$query = $this->db->query("
				Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
				igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
				left join vessels_berth_detail vas on igms.id=vas.igm_id 
				where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='$type' and igms.Export_Rotation_No Like '%$Searchdata%' order by 1 desc");
					//print($str);
	
			}
			If($SearchCriteria=="All")
			{
				$query = $this->db->query("Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
				igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
				left join vessels_berth_detail vas on igms.id=vas.igm_id 
				where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='$type' order by 1 desc limit 500");


			}
			$data=$query->result_array();
			//print "SQL Query: ".$this->db->last_query();
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	//View Igm List-GM
	function myListFormIGM($CODE,$type,$mylimit, $start) {
        try {
			
			$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
			
			if ($_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==11)
			{
				/*$mySQL="select igms.id as id
				from igm_details igms 
				left outer Join igm_navy_response Navyresponse 
				on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$this->CODE and (igms.type_of_igm='$this->TM'  or igms.type_of_igm='GM') and igms.final_submit=1";
				*/
				//end for paging
	
				//print($mySQL);
	
				$query = $this->db->query("select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as 
				Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
				as Pack_Number,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as 
				Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
				as Date_of_Entry_of_Goods,igms.weight as weight,igms.weight_unit,igms.net_weight,final_submit_date,
				igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
				as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged 
				as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.int_block as int_block,igms.R_No as R_No,igms.R_Date as R_Date,
				delivery_block_stat,igms.ConsigneeDesc,
				igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,
				(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) 
				as Organization_Name,
				(select AIN_No from organization_profiles orgs where 
				orgs.id=igms.Submitee_Org_Id) as AIN_No,

				imco,un,extra_remarks,Navyresponse.response_details1,Navyresponse.response_details3,Navyresponse.response_details2,
				Navyresponse.hold_application,Navyresponse.rejected_application,Navyresponse.auto_no as submitId,
				Navyresponse.final_amendment , Navyresponse.appsubmitdate ,Navyresponse.navy_response_to_port,Navyresponse.permission_no,Submission_Date
				from  igm_details igms 
				left outer Join igm_navy_response Navyresponse 
				on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$CODE and (igms.type_of_igm='$type') 
				and igms.final_submit=1  ORDER BY Line_No ASC LIMIT $start,$mylimit ");
			}
			else
			{

				/*$mySQL="select igms.id as id
				from igm_details igms 
				left outer Join igm_navy_response Navyresponse 
				on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$this->CODE and (igms.type_of_igm='$this->TM'  or igms.type_of_igm='GM') and igms.final_submit=1 and (igms.PFstatus=1 or igms.PFstatus=10)";
				*/	//end for paging
					
				
			 $getDataQueryFromIGM =  "select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as 
			 Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
			 as Pack_Number,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as 
			 Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
			 as Date_of_Entry_of_Goods,igms.weight as weight,igms.weight_unit,igms.net_weight,
			 igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
			 as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged 
			 as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.int_block as int_block,igms.R_No as R_No,igms.R_Date as R_Date,
			 delivery_block_stat,igms.ConsigneeDesc,final_submit_date,
			 igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,
			 (select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) 
			 as Organization_Name,
			 (select AIN_No from organization_profiles orgs where 
			 orgs.id=igms.Submitee_Org_Id) as AIN_No,

			 imco,un,extra_remarks,Navyresponse.response_details1,Navyresponse.response_details3,Navyresponse.response_details2,
			 Navyresponse.hold_application,Navyresponse.rejected_application,Navyresponse.auto_no as submitId,
			 Navyresponse.final_amendment , Navyresponse.appsubmitdate ,Navyresponse.navy_response_to_port,Navyresponse.to_custom,Navyresponse.permission_no,Submission_Date
			 from  igm_details igms 
			 left outer Join igm_navy_response Navyresponse 
			 on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$CODE and (igms.type_of_igm='$type') 
			 and igms.final_submit=1 and (igms.PFstatus=1 or igms.PFstatus=10)  ORDER BY Line_No ASC LIMIT $start,$mylimit";
				//echo  $getDataQueryFromIGM;	
				$query = $this->db->query($getDataQueryFromIGM);

			}
			
			//on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$CODE and (igms.type_of_igm='$type'  or igms.type_of_igm='GM')
			$data=$query->result_array();
			//print "SQL Query: ".$this->db->last_query();
			
            return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	// View IGM Search for Port...............
	function myListSearchforPort($CODE,$TM, $txt_ROTATION,$org_type_id) {
        try {
			
			$this->CODE=$CODE;
			$this->TM=$TM;	
			$this->org_type_id=$org_type_id;
			$this->txt_ROTATION=$txt_ROTATION;
			$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
			if ($_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==11)
			{
				$query = $this->db->query("select distinct igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as Import_Rotation_No,igms.Line_No as Line_No,igms.BL_No as BL_No,igms.Pack_Number as Pack_Number,AFR,delivery_block_stat,
					igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,final_submit_date,
					igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,imco,un,extra_remarks from igm_details igms 
							inner join igm_detail_container igm_container  on 
					igms.id=igm_container.igm_detail_id
					WHERE igms.IGM_id=$this->CODE and igms.type_of_igm='GM' and igms.final_submit=1 
					AND  off_dock_id='$this->org_type_id'");			
				}
			else
			{
				$query = $this->db->query("select distinct igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as Import_Rotation_No,igms.Line_No as Line_No,igms.BL_No as BL_No,igms.Pack_Number as Pack_Number,AFR,delivery_block_stat,
					igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,final_submit_date,
					igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,imco,un,extra_remarks 
				from igm_details igms 
							inner join igm_detail_container igm_container  on 
					igms.id=igm_container.igm_detail_id
					WHERE igms.IGM_id=$this->CODE and igms.type_of_igm='GM' and igms.final_submit=1 and (igms.PFstatus=1 or igms.PFstatus=10)
					AND  off_dock_id='$this->org_type_id'");
			}
			
			$data=$query->result_array();
			
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	// View IGM Search Line BL...............
	function myListSearchLineBL($CODE,$TM, $txt_ROTATION,$SearchId,$SearchData) {
        try {
			
			$this->CODE=$CODE;
			$this->TM=$TM;
			$this->SearchId=$SearchId;
			$this->SearchData=$SearchData;
			$this->txt_ROTATION=$txt_ROTATION;
			$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
			
			if($this->SearchId == 1)
			{
				
				if ($_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==11)
				{
				$query = $this->db->query("select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as  Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
				as Pack_Number,final_submit_date,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
				as Date_of_Entry_of_Goods,AFR,delivery_block_stat,igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
				as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.ConsigneeDesc,
				igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) 
				as Organization_Name,
				(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
				imco,un,extra_remarks,Navyresponse.response_details1,Navyresponse.response_details3,Navyresponse.response_details2,Navyresponse.hold_application,Navyresponse.rejected_application,Navyresponse.auto_no as submitId,
				Navyresponse.final_amendment , Navyresponse.appsubmitdate ,Navyresponse.navy_response_to_port,Navyresponse.to_custom,Navyresponse.permission_no,Submission_Date
				from igm_details igms left outer Join igm_navy_response Navyresponse on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and igms.final_submit=1 and igms.BL_No LIKE '%$this->SearchData'");
			
				}
				else

				{
					$query = $this->db->query("select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as  Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
					as Pack_Number,final_submit_date,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
					as Date_of_Entry_of_Goods,AFR,delivery_block_stat,igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
					as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.ConsigneeDesc,
					igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,
					(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
					imco,un,extra_remarks,Navyresponse.response_details1,Navyresponse.response_details3,Navyresponse.response_details2,Navyresponse.hold_application,Navyresponse.rejected_application,Navyresponse.auto_no as submitId,
					Navyresponse.final_amendment , Navyresponse.appsubmitdate ,Navyresponse.navy_response_to_port,Navyresponse.to_custom,Navyresponse.permission_no,Submission_Date

					from igm_details igms left outer Join igm_navy_response Navyresponse on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and (igms.PFstatus=1 or igms.PFstatus=10) and igms.BL_No LIKE '%$this->SearchData'");

				}
			}
			
			if($this->SearchId == 2)
			{

				if ($_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==11)
				{
					$query = $this->db->query("select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as  Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
					as Pack_Number,final_submit_date,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
					as Date_of_Entry_of_Goods,AFR,delivery_block_stat,igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
					as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.ConsigneeDesc,
					igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) 
					as Organization_Name,

					(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
					imco,un,extra_remarks,Navyresponse.response_details1,Navyresponse.response_details3,Navyresponse.response_details2,Navyresponse.hold_application,Navyresponse.rejected_application,Navyresponse.auto_no as submitId,
					Navyresponse.final_amendment , Navyresponse.appsubmitdate ,Navyresponse.navy_response_to_port,Navyresponse.to_custom,Navyresponse.permission_no,Submission_Date
					from igm_details igms left outer Join igm_navy_response Navyresponse on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and igms.final_submit=1 and igms.Line_No LIKE '%$this->SearchData'");

				}
				else
				{

					$query = $this->db->query("select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as  Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
					as Pack_Number,final_submit_date,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
					as Date_of_Entry_of_Goods,AFR,delivery_block_stat,igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
					as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.ConsigneeDesc,
					igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) 
					as Organization_Name,
					(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
					imco,un,extra_remarks,Navyresponse.response_details1,Navyresponse.response_details3,Navyresponse.response_details2,Navyresponse.hold_application,Navyresponse.rejected_application,Navyresponse.auto_no as submitId,
					Navyresponse.final_amendment , Navyresponse.appsubmitdate ,Navyresponse.navy_response_to_port,Navyresponse.to_custom,Navyresponse.permission_no,Submission_Date
					from igm_details igms left outer Join igm_navy_response Navyresponse on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and igms.final_submit=1 and (igms.PFstatus=1 or igms.PFstatus=10) and igms.Line_No LIKE '%$this->SearchData'");

				}

			}
			
			if($this->SearchId == 3)
			{

				if ($_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==11)
				{
					$query = $this->db->query("
					select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as Import_Rotation_No,igms.Line_No as Line_No,igms.BL_No as BL_No,igms.Pack_Number as Pack_Number,final_submit_date,
					igms.Pack_Description as Pack_Description,AFR,delivery_block_stat,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
					igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,
					(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
					imco,un,extra_remarks,AFR 
					from igm_details igms  
					inner join igm_detail_container on igms.id=igm_detail_container.igm_detail_id 
					where igms.IGM_id=$this->CODE AND igms.type_of_igm='$this->TM' AND igms.final_submit=1
					AND igm_detail_container.cont_number LIKE '%$this->SearchData%'");
				}
				else
				{
					$query = $this->db->query("
					select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as Import_Rotation_No,igms.Line_No as Line_No,igms.BL_No as BL_No,igms.Pack_Number as Pack_Number,final_submit_date,
					igms.Pack_Description as Pack_Description,AFR,delivery_block_stat,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
					igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,
					(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
					imco,un,extra_remarks,AFR 
					from igm_details igms  
					inner join igm_detail_container on igms.id=igm_detail_container.igm_detail_id 
					where igms.IGM_id=$this->CODE AND igms.type_of_igm='$this->TM' AND igms.final_submit=1 and (igms.PFstatus=1 or igms.PFstatus=10) AND igm_detail_container.cont_number LIKE '%$this->SearchData%'");

				}

			}
			
			$data=$query->result_array();

			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	// View IGM Search Importer...............
	function myListSearchImporter($CODE,$TM, $txt_ROTATION,$SearchId,$SearchData) {
        try {
			
			$this->CODE=$CODE;
			$this->TM=$TM;
			$this->SearchId=$SearchId;
			$this->SearchData=$SearchData;
			$this->txt_ROTATION=$txt_ROTATION;
			$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
			
			
				
				if ($_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==11)
				{
				$query = $this->db->query("select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as  Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
				as Pack_Number,final_submit_date,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
				as Date_of_Entry_of_Goods,AFR,delivery_block_stat,igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
				as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.ConsigneeDesc,
				igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) 
				as Organization_Name,
				(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
				imco,un,extra_remarks,Navyresponse.response_details1,Navyresponse.response_details3,Navyresponse.response_details2,Navyresponse.hold_application,Navyresponse.rejected_application,Navyresponse.auto_no as submitId,
				Navyresponse.final_amendment , Navyresponse.appsubmitdate ,Navyresponse.navy_response_to_port,Navyresponse.to_custom,Navyresponse.permission_no,Submission_Date
				from igm_details igms left outer Join igm_navy_response Navyresponse on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and igms.final_submit=1 and igms.$this->SearchId LIKE '%$this->SearchData%'");
			
				}
				else

				{
					$query = $this->db->query("select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as  Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
					as Pack_Number,final_submit_date,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
					as Date_of_Entry_of_Goods,AFR,delivery_block_stat,igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
					as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.ConsigneeDesc,
					igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,
					(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
					imco,un,extra_remarks,Navyresponse.response_details1,Navyresponse.response_details3,Navyresponse.response_details2,Navyresponse.hold_application,Navyresponse.rejected_application,Navyresponse.auto_no as submitId,
					Navyresponse.final_amendment , Navyresponse.appsubmitdate ,Navyresponse.navy_response_to_port,Navyresponse.to_custom,Navyresponse.permission_no,Submission_Date

					from igm_details igms left outer Join igm_navy_response Navyresponse on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and igms.final_submit=1 and (igms.PFstatus=1 or igms.PFstatus=10) and igms.$this->SearchId LIKE '%$this->SearchData%'");

				}
			
			
			
			$data=$query->result_array();

			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	// View IGM Search by MLO CODE...............
	function myListSearchMLO($CODE,$TM, $txt_ROTATION,$org_type_id) {
        try {
			
			$this->CODE=$CODE;
			$this->TM=$TM;	
			$this->org_type_id=$org_type_id;
			$this->txt_ROTATION=$txt_ROTATION;
			$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
			if ($_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==11)
				{
					$query = $this->db->query("
					select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as Import_Rotation_No,igms.Line_No as Line_No,
					igms.BL_No as BL_No,final_submit_date,igms.Pack_Number as Pack_Number,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,
					igms.Description_of_Goods as Description_of_Goods,AFR,delivery_block_stat,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit,
					igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,
					(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
					imco,un,extra_remarks 
					from igm_details igms
					WHERE igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and igms.final_submit=1 and igms.mlocode='$this->org_type_id'");
					//die("aaa");
				}

			else
				{
					$query = $this->db->query("
					select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as Import_Rotation_No,igms.Line_No as Line_No,
					igms.BL_No as BL_No,final_submit_date,igms.Pack_Number as Pack_Number,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,
					igms.Description_of_Goods as Description_of_Goods,AFR,delivery_block_stat,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit,
					igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,
					(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
					imco,un,extra_remarks 
					from igm_details igms
					WHERE igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and igms.final_submit=1 and (igms.PFstatus=1 or igms.PFstatus=10) and igms.mlocode='$this->org_type_id'");

				}
			
			$data=$query->result_array();
				//print "SQL Query: ".$this->db->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	// View IGM Search by MLO Name...............
	function myListSearchByMLO($CODE,$TM, $txt_ROTATION,$org_type_id) {
        try {
			
			$this->CODE=$CODE;
			$this->TM=$TM;	
			$this->org_type_id=$org_type_id;
			$this->txt_ROTATION=$txt_ROTATION;
			$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
			
			if ($_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==11)
				{

					$query = $this->db->query("
					select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number as 
					Pack_Number,final_submit_date,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,igms.weight as 
					weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered 
					as No_of_Pack_Delivered,AFR,delivery_block_stat, igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,
					igms.mlocode,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,
					(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
					imco,un,extra_remarks,
					navyresponse.response_details1,navyresponse.firstapprovaltime,navyresponse.response_details2,navyresponse.secondapprovaltime,Submission_Date,
					navyresponse.response_details3,navyresponse.thirdapprovaltime,navyresponse.hold_application,navyresponse.hold_date,navyresponse.rejected_application,
					navyresponse.rejected_date,navyresponse.final_amendment from igm_details igms  left join igm_navy_response navyresponse on navyresponse.igm_details_id=igms.id
					WHERE igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and igms.final_submit=1 and igms.Submitee_Org_Id=$this->org_type_id");
			//die("aaa");
				}

			else
				{

					$query = $this->db->query("
					select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number as 
					Pack_Number,final_submit_date,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,igms.weight as 
					weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered 
					as No_of_Pack_Delivered,AFR,delivery_block_stat, igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,
					igms.mlocode,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,
					(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No, 
					imco,un,extra_remarks,
					navyresponse.response_details1,navyresponse.firstapprovaltime,navyresponse.response_details2,navyresponse.secondapprovaltime,Submission_Date,
					navyresponse.response_details3,navyresponse.thirdapprovaltime,navyresponse.hold_application,navyresponse.hold_date,navyresponse.rejected_application,
					navyresponse.rejected_date,navyresponse.final_amendment from igm_details igms  left join igm_navy_response navyresponse on navyresponse.igm_details_id=igms.id
					WHERE igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and igms.final_submit=1 and (igms.PFstatus=1 or igms.PFstatus=10) and igms.Submitee_Org_Id=$this->org_type_id");

				}

			
			$data=$query->result_array();
		

			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	// View IGM Search by SAF Name...............
	function myListSearchBySAF($CODE,$TM, $txt_ROTATION,$org_type_id) {
        try {
			
			$this->CODE=$CODE;
			$this->TM=$TM;	
			$this->org_type_id=$org_type_id;
			$this->txt_ROTATION=$txt_ROTATION;
			$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
			
			
			if ($_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==11)
				{
					$query = $this->db->query("
					select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as Import_Rotation_No,igms.Line_No as Line_No,final_submit_date,
					igms.BL_No as BL_No,igms.Pack_Number as Pack_Number,AFR,delivery_block_stat,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,
					igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit,
					igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,imco,un,extra_remarks from igm_details igms
					WHERE igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and igms.final_submit=1 and igms.Submitee_Org_Id=$this->org_type_id");
				}
			else
				{
					$query = $this->db->query("
					select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as Import_Rotation_No,igms.Line_No as Line_No,final_submit_date,
					igms.BL_No as BL_No,igms.Pack_Number as Pack_Number,AFR,delivery_block_stat,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,
					igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,igms.weight as weight,igms.weight_unit,igms.net_weight,igms.net_weight_unit,
					igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,(select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as Organization_Name,imco,un,extra_remarks 
					from igm_details igms
					WHERE igms.IGM_id=$this->CODE and igms.type_of_igm='$this->TM' and igms.final_submit=1 and (igms.PFstatus=1 or igms.PFstatus=10) and igms.Submitee_Org_Id=$this->org_type_id");
				}


			
			$data=$query->result_array();

			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	// View Supplemetary IGM List .............................................................................
	function myListFormSEasy($MCODE,$TM,$CType,$mylimit, $start) {
        try {
			
			$this->MCODE=$MCODE;			
			$this->TM=$TM;
			$this->CType=$CType;
			//echo $this->MCODE."|".$this->TM."|".$this->CType;
			$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
			$org_id=$this->session->userdata('org_id');
			//echo $org_id."<hr>";
			
			//start for PORT
			if(($_SESSION['Control_Panel']==12) || ($_SESSION['Control_Panel']==13) || ($_SESSION['Control_Panel']==43) || ($_SESSION['Control_Panel']==59))
			{
			
					
				$query = $this->db->query("
				select igms.type_of_igm,igms.id as id,
				igms.igm_master_id as igm_master_id,igms.igm_detail_id as igm_detail_id,igms.master_Line_No as master_Line_No,igms.master_BL_No as master_BL_No,
				igms.Import_Rotation_No as Import_Rotation_No,
				igms.Line_No as Line_No,
				igms.BL_No as BL_No,
				igms.Pack_Number as Pack_Number,
				igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
				igms.weight as weight,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
				igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.igm_sup_detail_id,
				igms.weight_unit as weight_unit,igms.net_weight as net_weight,igms.net_weight_unit as net_weight_unit,
				organization_profiles.Organization_Name as Agent_Name,organization_profiles.AIN_No as AIN_No,AIN_No_New,navyresponse.response_details1,
				navyresponse.response_details3,navyresponse.thirdapprovaltime,
				navyresponse.hold_application,navyresponse.hold_date,
				navyresponse.rejected_application,navyresponse.rejected_date,navyresponse.final_amendment
				from igm_supplimentary_detail  igms inner join organization_profiles on igms.Submitee_Org_Id=organization_profiles.id
				left join igm_navy_response navyresponse on navyresponse.egm_details_id=igms.id inner join 
				 igm_sup_detail_container  igm_sup_container on igms.id=igm_sup_container.igm_sup_detail_id 
				WHERE igms.igm_master_id=$this->MCODE and (type_of_igm='$this->TM' or type_of_igm='BB') and igms.final_submit=1  
				AND  off_dock_id ='$org_id'");		
			}
			
			$data=$query->result_array();
			//print "SQL Query: ".$this->db->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	// View Supplemetary IGM Search By Line/BL.............................................................................
	function myListSearchFFByLineBL($CType,$MCODE,$master_id,$TM,$SearchId,$SearchData,$SFlag,$txt_ROTATION) {
        try {
			
				$this->MCODE=$MCODE;
				$this->TM=$TM;	
				$this->SFlag=$SFlag;	
				$this->CType=$CType;	
				
				$this->SearchId=$SearchId;
				$this->SearchData=$SearchData;
				$this->txt_ROTATION=$txt_ROTATION;
				$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
				$org_id=$this->session->userdata('org_id');	
					
				if($this->SearchId == 1)
				{
					if (($_SESSION['Control_Panel']==6) || ($_SESSION['Control_Panel']==10) || ($_SESSION['Control_Panel']==58) || ($_SESSION['Control_Panel']==63) )
					$query = $this->db->query("select igms.id as id,igms.final_submit as final_submit,
					igms.igm_master_id as igm_master_id,igms.igm_detail_id as igm_detail_id,igms.master_Line_No as master_Line_No,igms.master_BL_No as master_BL_No,
					igms.Import_Rotation_No as Import_Rotation_No,
					igms.Line_No as Line_No,igms.weight_unit,
					igms.BL_No as BL_No,igms.office_code,
					igms.Pack_Number as Pack_Number,
					igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
					igms.weight as weight,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,igms.commited_Delivery_Date as Date_of_Entry_of_Goods1,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.Submitee_Org_Id as Submitee_Org_Id,igms.igm_sup_detail_id as igm_sup_detail_id,igms.weight_unit as weight_unit,igms.net_weight as net_weight,igms.net_weight_unit as net_weight_unit,
					organization_profiles.Organization_Name as Agent_Name,AFR,organization_profiles.AIN_No as AIN_No,
					navyresponse.response_details1,navyresponse.response_details2,navyresponse.secondapprovaltime,
					navyresponse.response_details3,navyresponse.thirdapprovaltime,
					navyresponse.hold_application,navyresponse.hold_date,
					navyresponse.rejected_application,navyresponse.rejected_date,navyresponse.final_amendment
					from  igm_supplimentary_detail igms inner join organization_profiles on igms.Submitee_Org_Id=organization_profiles.id 
					left join igm_navy_response navyresponse on navyresponse.egm_details_id=igms.id
					where igms.Import_Rotation_No='$master_id' and igms.type_of_igm='$this->TM'  
					AND igms.BL_No LIKE '%$this->SearchData' and igms.final_submit=1");
					else
					$query = $this->db->query("select igms.id as id,igms.final_submit as final_submit,
					igms.igm_master_id as igm_master_id,igms.igm_detail_id as igm_detail_id,igms.master_Line_No as master_Line_No,igms.master_BL_No as master_BL_No,
					igms.Import_Rotation_No as Import_Rotation_No,
					igms.Line_No as Line_No,igms.weight_unit,
					igms.BL_No as BL_No,igms.office_code,
					igms.Pack_Number as Pack_Number,
					igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
					igms.weight as weight,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,igms.commited_Delivery_Date as Date_of_Entry_of_Goods1,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.Submitee_Org_Id as Submitee_Org_Id,igms.igm_sup_detail_id as igm_sup_detail_id,igms.weight_unit as weight_unit,igms.net_weight as net_weight,igms.net_weight_unit as net_weight_unit,
					organization_profiles.Organization_Name as Agent_Name,AFR,organization_profiles.AIN_No as AIN_No,navyresponse.response_details1,
					navyresponse.response_details3,navyresponse.thirdapprovaltime,
					navyresponse.hold_application,navyresponse.hold_date,
					navyresponse.rejected_application,navyresponse.rejected_date,navyresponse.final_amendment
					from  igm_supplimentary_detail igms inner join organization_profiles on igms.Submitee_Org_Id=organization_profiles.id
					left join igm_navy_response navyresponse on navyresponse.egm_details_id=igms.id
					where igms.Import_Rotation_No='$master_id' and igms.type_of_igm='$this->TM'  
					AND igms.BL_No LIKE '%$this->SearchData'");

				}
					
				if($this->SearchId == 2)
				{
					if (($_SESSION['Control_Panel']==6) || ($_SESSION['Control_Panel']==10) || ($_SESSION['Control_Panel']==58) || ($_SESSION['Control_Panel']==63) )
					$query = $this->db->query("select igms.id as id,igms.final_submit as final_submit,
					igms.igm_master_id as igm_master_id,igms.igm_detail_id as igm_detail_id,igms.master_Line_No as master_Line_No,igms.master_BL_No as master_BL_No,
					igms.Import_Rotation_No as Import_Rotation_No,
					igms.Line_No as Line_No,igms.weight_unit,
					igms.BL_No as BL_No,igms.office_code,
					igms.Pack_Number as Pack_Number,
					igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
					igms.weight as weight,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,igms.commited_Delivery_Date as Date_of_Entry_of_Goods1,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.Submitee_Org_Id as Submitee_Org_Id,
					(select Organization_Name from organization_profiles where organization_profiles.id=igms.Submitee_Org_Id) as Agent_Name,
					(select AIN_No from organization_profiles where organization_profiles.id=igms.Submitee_Org_Id) as AIN_No,
					igms.igm_sup_detail_id as igm_sup_detail_id,AFR,navyresponse.response_details1,
					navyresponse.response_details3,navyresponse.thirdapprovaltime,
					navyresponse.hold_application,navyresponse.hold_date,
					navyresponse.rejected_application,navyresponse.rejected_date,navyresponse.final_amendment
					from  igm_supplimentary_detail igms left join igm_navy_response navyresponse on navyresponse.egm_details_id=igms.id
					where igms.Import_Rotation_No='$master_id' and igms.type_of_igm='$this->TM' and igms.final_submit=1
					AND igms.Line_No LIKE '%$this->SearchData' and igms.final_submit=1");
					else
					$query = $this->db->query("select igms.id as id,igms.final_submit as final_submit,
					igms.igm_master_id as igm_master_id,igms.igm_detail_id as igm_detail_id,igms.master_Line_No as master_Line_No,igms.master_BL_No as master_BL_No,
					igms.Import_Rotation_No as Import_Rotation_No,
					igms.Line_No as Line_No,igms.weight_unit,
					igms.BL_No as BL_No,igms.office_code,
					igms.Pack_Number as Pack_Number,
					igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
					igms.weight as weight,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,igms.commited_Delivery_Date as Date_of_Entry_of_Goods1,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.Submitee_Org_Id as Submitee_Org_Id,
					(select Organization_Name from organization_profiles where organization_profiles.id=igms.Submitee_Org_Id) as Agent_Name,
					(select AIN_No from organization_profiles where organization_profiles.id=igms.Submitee_Org_Id) as AIN_No,
					igms.igm_sup_detail_id as igm_sup_detail_id,AFR,
					navyresponse.response_details3,navyresponse.thirdapprovaltime,navyresponse.response_details1,
					navyresponse.hold_application,navyresponse.hold_date,
					navyresponse.rejected_application,navyresponse.rejected_date,navyresponse.final_amendment
					from  igm_supplimentary_detail igms left join igm_navy_response navyresponse on navyresponse.egm_details_id=igms.id
					where igms.Import_Rotation_No='$master_id' and igms.type_of_igm='$this->TM' and igms.final_submit=1
					AND igms.Line_No LIKE '%$this->SearchData'");

					}
					
				if($this->SearchId == 3)
				{
					if (($_SESSION['Control_Panel']==6) || ($_SESSION['Control_Panel']==10) || ($_SESSION['Control_Panel']==58) || ($_SESSION['Control_Panel']==63) )
					$query = $this->db->query("
					select distinct igms.id as id,igms.final_submit as final_submit,igms.igm_master_id as igm_master_id,igms.igm_detail_id as igm_detail_id,igms.master_Line_No as master_Line_No,igms.master_BL_No as master_BL_No,
					igms.Import_Rotation_No as Import_Rotation_No,igms.Line_No as Line_No,igms.weight_unit,
					igms.BL_No as BL_No,igms.office_code,igms.Pack_Number as Pack_Number,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
					igms.weight as weight,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,igms.commited_Delivery_Date as Date_of_Entry_of_Goods1,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.Submitee_Org_Id as Submitee_Org_Id,igms.igm_sup_detail_id as igm_sup_detail_id,igms.weight_unit as weight_unit,igms.net_weight as net_weight,igms.net_weight_unit as net_weight_unit,
					organization_profiles.Organization_Name as Agent_Name,AFR,organization_profiles.AIN_No as AIN_No,navyresponse.response_details1,
					navyresponse.response_details3,navyresponse.thirdapprovaltime,
					navyresponse.hold_application,navyresponse.hold_date,
					navyresponse.rejected_application,navyresponse.rejected_date,navyresponse.final_amendment
					from  igm_supplimentary_detail igms inner join organization_profiles on igms.Submitee_Org_Id=organization_profiles.id
					left join igm_navy_response navyresponse on navyresponse.egm_details_id=igms.id
					WHERE igms.Import_Rotation_No='$master_id' and igms.type_of_igm='$this->TM' and igms.final_submit=1
					AND igms.id IN (SELECT igm_sup_detail_id FROM igm_sup_detail_container igm_sup_detail_container 
					WHERE cont_number LIKE '$this->SearchData%' and igms.final_submit=1)");
					else
					$query = $this->db->query("
					select distinct igms.id as id,igms.final_submit as final_submit,
					igms.igm_master_id as igm_master_id,igms.igm_detail_id as igm_detail_id,igms.master_Line_No as master_Line_No,igms.master_BL_No as master_BL_No,
					igms.Import_Rotation_No as Import_Rotation_No,
					igms.Line_No as Line_No,igms.weight_unit,
					igms.BL_No as BL_No,igms.office_code,
					igms.Pack_Number as Pack_Number,
					igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
					igms.weight as weight,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,igms.commited_Delivery_Date as Date_of_Entry_of_Goods1,
					igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.Submitee_Org_Id as Submitee_Org_Id,igms.igm_sup_detail_id as igm_sup_detail_id,igms.weight_unit as weight_unit,igms.net_weight as net_weight,igms.net_weight_unit as net_weight_unit,
					organization_profiles.Organization_Name as Agent_Name,AFR,organization_profiles.AIN_No as AIN_No,navyresponse.response_details1,
					navyresponse.response_details3,navyresponse.thirdapprovaltime,
					navyresponse.hold_application,navyresponse.hold_date,
					navyresponse.rejected_application,navyresponse.rejected_date,navyresponse.final_amendment
					from  igm_supplimentary_detail igms inner join organization_profiles on igms.Submitee_Org_Id=organization_profiles.id
					left join igm_navy_response navyresponse on navyresponse.egm_details_id=igms.id
					WHERE igms.Import_Rotation_No='$master_id' and igms.type_of_igm='$this->TM' and igms.final_submit=1
					AND igms.id IN (SELECT igm_sup_detail_id FROM igm_sup_detail_container igm_sup_detail_container WHERE cont_number LIKE '$this->SearchData%')");

					}	
							
			
			
			
			
			$data=$query->result_array();
			//print "SQL Query: ".$this->db->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	
	// View Supplemetary IGM Search By FF Agent.............................................................................
	function myListSearchFFByFF($MCODE,$TM,$SearchData,$txt_ROTATION) {
        try {
			
				$this->MCODE=$MCODE;
				$this->TM=$TM;	
				$this->SFlag=$SFlag;	
				$this->CType=$CType;	
				$this->SearchId=$SearchId;
				$this->SearchData=$SearchData;
				$this->txt_ROTATION=$txt_ROTATION;
				
				$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
				$org_id=$this->session->userdata('org_id');	
				
				$query = $this->db->query("select igms.id as id, igms.igm_master_id as igm_master_id,igms.igm_detail_id as igm_detail_id,igms.master_Line_No as master_Line_No,igms.master_BL_No as master_BL_No, igms.Import_Rotation_No as Import_Rotation_No, igms.Line_No as Line_No, igms.BL_No as BL_No, igms.office_code, igms.Pack_Number as Pack_Number, igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods, igms.weight as weight,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.igm_sup_detail_id,(select Organization_Name from organization_profiles where organization_profiles.id=igms.Submitee_Org_Id) as Agent_Name,navyresponse.response_details1,
				navyresponse.response_details2,navyresponse.secondapprovaltime,navyresponse.response_details3,navyresponse.thirdapprovaltime,
				navyresponse.hold_application,navyresponse.hold_date,navyresponse.rejected_application,navyresponse.rejected_date,navyresponse.final_amendment 
				from igm_supplimentary_detail igms  left join igm_navy_response navyresponse on navyresponse.egm_details_id=igms.id
				WHERE igms.igm_master_id=$this->MCODE and type_of_igm='$this->TM' and igms.final_submit=1 AND igms.Submitee_Org_Id ='$this->SearchData'");
			 
				
			
			
			$data=$query->result_array();
			//print "SQL Query: ".$this->db->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	// View Supplemetary IGM ............................................................................
	function myListFormS($CODE,$SubCODE,$TM) {
        try {
			
				$this->CODE=$CODE;
				$this->TM=$TM;	
				$this->SubCODE=$SubCODE;	
							
				$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
				$org_id=$this->session->userdata('org_id');	
				
				// echo $this->CODE;
				// echo "<br>";
				// echo $this->TM;
				// echo "<br>";
				// echo $this->SubCODE;
				// echo "<br>";
				// return;
				
				$query = $this->db->query("select igms.id as id,
				igms.igm_master_id as igm_master_id,igms.igm_detail_id as igm_detail_id,igms.master_Line_No as master_Line_No,igms.master_BL_No as master_BL_No,
				igms.Import_Rotation_No as Import_Rotation_No,
				igms.Line_No as Line_No,
				igms.BL_No as BL_No,
				igms.Pack_Number as Pack_Number,igms.AFR as AFR,
				igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
				igms.weight as weight,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
				igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.igm_sup_detail_id,igms.weight as weight,igms.weight_unit as weight_unit,igms.net_weight as net_weight,igms.net_weight_unit as net_weight_unit,igms.Submission_Date,
				navyresponse.navy_response_to_port,
				organization_profiles.Organization_Name as Agent_Name,
				organization_profiles.AIN_No as AIN_No,AIN_No_New,
				navyresponse.response_details1,navyresponse.response_details2,navyresponse.secondapprovaltime,
					navyresponse.response_details3,navyresponse.thirdapprovaltime,
					navyresponse.hold_application,navyresponse.hold_date,
					navyresponse.rejected_application,navyresponse.rejected_date,navyresponse.final_amendment
				from igm_supplimentary_detail igms inner join organization_profiles on igms.Submitee_Org_Id=organization_profiles.id 
				left join igm_navy_response navyresponse on navyresponse.egm_details_id=igms.id where igms.igm_master_id='$this->CODE' and igms.igm_detail_id='$this->SubCODE' and type_of_igm='$this->TM' and igms.final_submit=1 and igm_sup_detail_id=0");

			
			$data=$query->result_array();
			//print "SQL Query: ".$this->db->last_query();  NotifyDesc 
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	// View Supplemetary IGM Search by Port ............................................................................
	function myListSearchFFByPort($CODE,$SubCODE,$TM,$org_type_id) {
        try {
			
				$this->CODE=$CODE;
				$this->TM=$TM;	
				$this->SubCODE=$SubCODE;	
				$this->org_type_id=$org_type_id;	
							
				$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
				$org_id=$this->session->userdata('org_id');	
				
				$query = $this->db->query("
				select igms.id as id,
				igms.igm_master_id as igm_master_id,igms.igm_detail_id as igm_detail_id,igms.master_Line_No as master_Line_No,igms.master_BL_No as master_BL_No,
				igms.Import_Rotation_No as Import_Rotation_No,
				igms.Line_No as Line_No,
				igms.BL_No as BL_No,
				igms.Pack_Number as Pack_Number,
				igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
				igms.weight as weight,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
				igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.igm_sup_detail_id,igms.weight_unit as weight_unit,igms.net_weight as net_weight,igms.net_weight_unit as net_weight_unit,
				organization_profiles.Organization_Name as Agent_Name,
				navyresponse.response_details1,navyresponse.response_details2,navyresponse.secondapprovaltime,
					navyresponse.response_details3,navyresponse.thirdapprovaltime,
					navyresponse.hold_application,navyresponse.hold_date,
					navyresponse.rejected_application,navyresponse.rejected_date,navyresponse.final_amendment
				from  igm_supplimentary_detail igms inner join organization_profiles on igms.Submitee_Org_Id=organization_profiles.id
				left join igm_navy_response navyresponse on navyresponse.egm_details_id=igms.id 
				inner join  igm_sup_detail_container igm_sup_detail_container on igms.id=igm_sup_detail_container.igm_sup_detail_id
				WHERE type_of_igm='$this->TM' and igms.final_submit=1  and igms.igm_detail_id='$this->SubCODE' AND  off_dock_id ='$this->org_type_id'");
					
						
			
			$data=$query->result_array();
			//print "SQL Query: ".$this->db->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	/************************************************* End of Igm Supplementary ***************************************************/
	//Container List
	function myIgmContainerListSearch($Searchdata) {
        try {
			//$D="D";
			//$N="";
			
			//$this->db->limit($limit, $start);
			/*$this->db->select("cont_number,igm_detail_container.igm_detail_id as detail_id, igm_detail_container.id,igm_details.Import_Rotation_No,igm_details.Line_No,igm_details.BL_No,igm_details.Submitee_Org_Id,cont_size,igm_details.place_of_unloading,
			cont_weight, cont_seal_number,cont_status,cont_height,cont_type,commudity_desc, cont_vat,off_dock_id,cont_imo,cont_un,Cont_gross_weight,
			 Organization_Name,igm_details.Description_of_Goods,igm_navy_response.response_details1, igm_navy_response.response_details2,igm_navy_response.response_details3, 
			igm_navy_response.hold_application,igm_navy_response.rejected_application,igm_navy_response.auto_no as submitId, igm_navy_response.final_amendment ,igm_detail_container.port_status,
			igm_navy_response.appsubmitdate ,igm_navy_response.navy_response_to_port,igm_navy_response.permission_no");
            $this->db->from('igm_details');
			$this->db->join('igm_navy_response', 'igm_navy_response.igm_details_id = igm_details.id', 'left');
			$this->db->join('igm_detail_container', 'igm_detail_container.igm_detail_id = igm_details.id', 'left');
			$this->db->join('organization_profiles', 'organization_profiles.id = igm_details.Submitee_Org_Id', 'inner');
			$this->db->join('commudity_detail', 'commudity_detail.commudity_code = igm_detail_container.commudity_code', 'left');
			$this->db->join('igm_supplimentary_detail', 'igm_supplimentary_detail.igm_detail_id = igm_details.id', 'left');
            $this->db->where('igm_details.Import_Rotation_No', $Searchdata );
            $this->db->where('igm_details.final_submit', '1' );
            $this->db->where('igm_supplimentary_detail.id', null );
			$query1 = $this->db->get()->result_array();
			//$subQuery1 = $this->db->_compile_select();

			//$this->db->_reset_select();
			
			$this->db->select("cont_number,igm_sup_detail_container.igm_sup_detail_id as detail_id, igm_sup_detail_container.id,igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.Line_No,igm_supplimentary_detail.BL_No,igm_supplimentary_detail.Submitee_Org_Id,cont_size,igm_supplimentary_detail.place_of_unloading,
			cont_weight, cont_seal_number,cont_status,cont_height,cont_type,commudity_desc, cont_vat,off_dock_id,cont_imo,cont_un,Cont_gross_weight,
			 Organization_Name,igm_supplimentary_detail.Description_of_Goods,igm_navy_response.response_details1, igm_navy_response.response_details2,igm_navy_response.response_details3, 
			igm_navy_response.hold_application,igm_navy_response.rejected_application,igm_navy_response.auto_no as submitId, igm_navy_response.final_amendment ,igm_sup_detail_container.port_status,
			igm_navy_response.appsubmitdate ,igm_navy_response.navy_response_to_port,igm_navy_response.permission_no");
            $this->db->from('igm_supplimentary_detail');
			$this->db->join('igm_navy_response', 'igm_navy_response.egm_details_id = igm_supplimentary_detail.id', 'left');
			$this->db->join('igm_sup_detail_container', 'igm_sup_detail_container.igm_sup_detail_id = igm_supplimentary_detail.id', 'left');
			$this->db->join('organization_profiles', 'organization_profiles.id = igm_supplimentary_detail.Submitee_Org_Id', 'inner');
			$this->db->join('commudity_detail', 'commudity_detail.commudity_code = igm_sup_detail_container.commudity_code', 'left');
			
            $this->db->where('igm_supplimentary_detail.Import_Rotation_No', $Searchdata );
            $this->db->where('igm_supplimentary_detail.final_submit', '1' );
			$query2 = $this->db->get()->result_array();
			
			$data = array_merge($query1, $query2);*/
			//$subQuery2 = $this->db->_compile_select();

			//$this->db->_reset_select();

			//$this->db->from("($subQuery1 UNION $subQuery2)");
			//$data=$this->db->get();
			//$this->db->limit(10, 0);
			//echo $this->db->_compile_select(); 
			//$data = $this->db->get()->result_array();
			
		/*	$query = $this->db->query("select '' as igm_detail_id2,mlocode,cont_number, igm_detail_container.id,igm_details.Line_No,igm_details.BL_No,igm_details.Submitee_Org_Id,cont_size,place_of_unloading,
			cont_weight, cont_seal_number,cont_status,cont_height,cont_type,commudity_desc, cont_vat,off_dock_id,cont_imo,cont_un,Cont_gross_weight,
			'D' as RType, Organization_Name,igm_details.Description_of_Goods,igm_navy_response.response_details1, igm_navy_response.response_details2,igm_navy_response.response_details3, 
			igm_navy_response.hold_application,igm_navy_response.rejected_application,igm_navy_response.auto_no as submitId, igm_navy_response.final_amendment ,igm_detail_container.port_status,
			igm_navy_response.appsubmitdate ,igm_navy_response.navy_response_to_port,igm_navy_response.permission_no,'D' as type, (select Organization_Name from organization_profiles where organization_profiles.id=igm_detail_container.off_dock_id) as off_dock_name  from igm_details left join igm_navy_response on
			igm_navy_response.igm_details_id=igm_details.id left join igm_detail_container on igm_details.id=igm_detail_container.igm_detail_id inner join organization_profiles on 
			igm_details.Submitee_Org_Id=organization_profiles.id left join commudity_detail on igm_detail_container.commudity_code=commudity_detail.commudity_code
			where igm_details.Import_Rotation_No='$Searchdata' 
			union 
			select igm_detail_id as igm_detail_id2,(select mlocode from igm_details where igm_details.id=igm_supplimentary_detail.igm_detail_id) as mlocode, cont_number, igm_sup_detail_container.id,Line_No,igm_supplimentary_detail.BL_No, Submitee_Org_Id,cont_size,'' as place_of_unloading,cont_weight, cont_seal_number,cont_status,
			cont_height,cont_type,commudity_desc, cont_vat,off_dock_id,cont_imo,cont_un,igm_supplimentary_detail.weight,'S' as RType,Organization_Name,igm_supplimentary_detail.Description_of_Goods,
			igm_navy_response.response_details1,igm_navy_response.response_details2,igm_navy_response.response_details3,igm_sup_detail_container.port_status,
			igm_navy_response.hold_application,igm_navy_response.rejected_application,igm_navy_response.auto_no as submitId, igm_navy_response.final_amendment , 
			igm_navy_response.appsubmitdate ,igm_navy_response.navy_response_to_port,igm_navy_response.permission_no,'s' as type, (select Organization_Name from organization_profiles where organization_profiles.id=igm_sup_detail_container.off_dock_id) as off_dock_name from igm_supplimentary_detail inner join 
			igm_sup_detail_container on igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id left join igm_navy_response on igm_navy_response.egm_details_id=igm_supplimentary_detail.id 
			inner join organization_profiles on igm_supplimentary_detail.Submitee_Org_Id=organization_profiles.id left join commudity_detail on igm_sup_detail_container.commudity_code=commudity_detail.commudity_code
			where igm_supplimentary_detail.Import_Rotation_No='$Searchdata'");*/
			
			$query = $this->db->query("SELECT igm_detail_id2,mlocode,cont_number,id,Line_No,BL_No,Notify_name,Submitee_Org_Id,cont_size,place_of_unloading,cont_weight,
			cont_seal_number,cont_status,cont_height,cont_iso_type,cont_type,commudity_desc,cont_vat,off_dock_id,cont_imo,cont_un,Cont_gross_weight,RType,
			Organization_Name,Description_of_Goods,port_status,TYPE,off_dock_name,symbol FROM 
			(SELECT '' AS igm_detail_id2,mlocode,cont_number, igm_detail_container.id,igm_details.Line_No,igm_details.BL_No,igm_details.Notify_name,igm_details.Submitee_Org_Id,
			cont_size,place_of_unloading,cont_weight, cont_seal_number,cont_status,cont_height,cont_iso_type,cont_type,commudity_desc, cont_vat,off_dock_id,
			cont_imo,cont_un,Cont_gross_weight,'D' AS RType, Organization_Name,igm_details.Description_of_Goods,igm_detail_container.port_status,'D' AS TYPE, 
			(SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_detail_container.off_dock_id) AS off_dock_name,
			IF((SELECT DISTINCT cont_number FROM igm_detail_container  abc WHERE abc.cont_number=igm_detail_container.cont_number  
			AND igm_details.Import_Rotation_No='$Searchdata' AND  igm_detail_consigneetabs.igm_detail_id IS NOT NULL AND igm_supplimentary_detail.igm_detail_id IS NULL )>0,'S','') AS symbol 
			FROM igm_details LEFT JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id LEFT JOIN organization_profiles 
			ON igm_details.Submitee_Org_Id=organization_profiles.id LEFT JOIN commudity_detail ON igm_detail_container.commudity_code=commudity_detail.commudity_code 
			LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id LEFT JOIN igm_detail_consigneetabs 
			ON igm_detail_consigneetabs.igm_detail_id=igm_details.id  WHERE igm_details.Import_Rotation_No='$Searchdata'  )
			AS tbl
			
			UNION ALL
			
			SELECT igm_detail_id2,mlocode,cont_number,id,Line_No,BL_No,Notify_name,Submitee_Org_Id,cont_size,place_of_unloading,cont_weight,cont_seal_number,cont_status,
			cont_height,cont_iso_type,cont_type,commudity_desc,cont_vat,off_dock_id,cont_imo,cont_un,Cont_gross_weight,RType,Organization_Name,
			Description_of_Goods,port_status,TYPE,off_dock_name,symbol FROM 
			(SELECT igm_detail_id AS igm_detail_id2,(SELECT mlocode FROM igm_details WHERE igm_details.id=igm_supplimentary_detail.igm_detail_id) AS mlocode, 
			cont_number, igm_sup_detail_container.id,Line_No,igm_supplimentary_detail.BL_No,igm_supplimentary_detail.Notify_name, Submitee_Org_Id,(SELECT cont_size FROM igm_detail_container 
			WHERE igm_detail_container.igm_detail_id=igm_supplimentary_detail.igm_detail_id AND igm_detail_container.cont_number=igm_sup_detail_container.cont_number) 
			AS cont_size,'' AS place_of_unloading,cont_weight, cont_seal_number,cont_status,(SELECT cont_height FROM igm_detail_container WHERE 
			igm_detail_container.igm_detail_id=igm_supplimentary_detail.igm_detail_id AND igm_detail_container.cont_number=igm_sup_detail_container.cont_number) AS cont_height,
			(SELECT cont_iso_type FROM igm_detail_container  WHERE igm_detail_container.igm_detail_id=igm_supplimentary_detail.igm_detail_id 
			AND igm_detail_container.cont_number=igm_sup_detail_container.cont_number) AS cont_iso_type,cont_type,commudity_desc, cont_vat,off_dock_id,
			cont_imo,cont_un,Cont_gross_weight,'S' AS RType,Organization_Name,igm_supplimentary_detail.Description_of_Goods,
			igm_sup_detail_container.port_status,'s' AS TYPE, (SELECT Organization_Name FROM organization_profiles WHERE 
			organization_profiles.id=igm_sup_detail_container.off_dock_id) AS off_dock_name,'' AS symbol FROM igm_supplimentary_detail 
			INNER JOIN igm_sup_detail_container ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id LEFT JOIN organization_profiles 
			ON igm_supplimentary_detail.Submitee_Org_Id=organization_profiles.id LEFT JOIN commudity_detail ON igm_sup_detail_container.commudity_code=commudity_detail.commudity_code 
			WHERE igm_supplimentary_detail.Import_Rotation_No='$Searchdata' ) AS tbl");
			$data=$query->result_array();
			//print "SQL Query: ".$this->db->last_query();  

            return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	function updateManifestList($rotation) {
        try {
			
				$this->rotation=$rotation;
											
				$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
				$org_id=$this->session->userdata('org_id');	
				
				$query = $this->db->query("select Import_Rotation_No,Total_number_of_containers,Total_number_of_bols from igm_masters where Import_Rotation_No='$this->rotation'");
				$data=$query->result_array();
							
			//print "SQL Query: ".$this->db->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	function checkShippingAgent($rotation) {
        try {
			
				$this->rotation=$rotation;
											
				$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
				$org_id=$this->session->userdata('org_id');	
				
				$query = $this->db->query("select (select Organization_Name from organization_profiles where organization_profiles.id=igm_masters.Submitee_Org_Id) as agent from igm_masters where Import_Rotation_No='$this->rotation'");
				$data=$query->result_array();
							
			//print "SQL Query: ".$this->db->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	function checkn4($rotation) {
        try {
				
				$CI = &get_instance();
				//echo $CI;
				//$this->db2=$this->load->database(second, TRUE);
				$this->db2 = $CI->load->database('second', TRUE);
				$this->rotation=$rotation;
					//		echo 		$this->rotation;		
				$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
				$org_id=$this->session->userdata('org_id');	
				
			/*	$query = $this->db2->query("select edi_interchange.creator,edi_trading_partners.name edit_shipping_id,
(select name from ref_bizunit_scoped where gkey=edi_trading_partners.bizu_gkey) as NameS,

(
select ref_bizunit_scoped.id from argo_carrier_visit 
inner join ref_bizunit_scoped on ref_bizunit_scoped.gkey=argo_carrier_visit.operator_gkey

where argo_carrier_visit.gkey=edi_batch.cv_gkey ) VSL_shipp_id,
(select ref_bizunit_scoped.name from argo_carrier_visit 
inner join ref_bizunit_scoped on ref_bizunit_scoped.gkey=argo_carrier_visit.operator_gkey

where argo_carrier_visit.gkey=edi_batch.cv_gkey ) Vessl_shipping_name,

(SELECT vsl.name FROM vsl_vessels vsl
INNER JOIN vsl_vessel_visit_details vdt ON vsl.gkey = vdt.vessel_gkey
WHERE vdt.ib_vyg = '$this->rotation') AS vessel_name

 from edi_transaction
inner join edi_batch on edi_batch.gkey=edi_transaction.edibat_gkey
inner join edi_interchange on edi_interchange.gkey=edi_batch.ediint_gkey
inner join edi_trading_partners on edi_trading_partners.gkey=edi_interchange.ediptnr_gkey
 where keyword_value_4='$this->rotation'  limit 1
");*/
			$query = $this->db2->query("select edi_interchange.creator,edi_trading_partners.name edit_shipping_id,
(select name from ref_bizunit_scoped where gkey=edi_trading_partners.bizu_gkey) as NameS,

(
select ref_bizunit_scoped.id from argo_carrier_visit 
inner join ref_bizunit_scoped on ref_bizunit_scoped.gkey=argo_carrier_visit.operator_gkey

where argo_carrier_visit.gkey=edi_batch.cv_gkey ) VSL_shipp_id,
(select ref_bizunit_scoped.name from argo_carrier_visit 
inner join ref_bizunit_scoped on ref_bizunit_scoped.gkey=argo_carrier_visit.operator_gkey

where argo_carrier_visit.gkey=edi_batch.cv_gkey ) Vessl_shipping_name,

(SELECT vsl.name FROM vsl_vessels vsl
INNER JOIN vsl_vessel_visit_details vdt ON vsl.gkey = vdt.vessel_gkey
WHERE vdt.ib_vyg = '$this->rotation') AS vessel_name,

(SELECT Y.id FROM vsl_vessel_visit_details vsldtl
inner join  ( sparcsn4.ref_bizunit_scoped r  
               left join ( sparcsn4.ref_agent_representation X  
               left join sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )              
               ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
WHERE ib_vyg = '$this->rotation') AS agent_code,
(SELECT Y.name FROM vsl_vessel_visit_details vsldtl
inner join  ( sparcsn4.ref_bizunit_scoped r  
               left join ( sparcsn4.ref_agent_representation X  
               left join sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )              
               ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
WHERE ib_vyg = '$this->rotation') AS agent_name

 from edi_transaction
inner join edi_batch on edi_batch.gkey=edi_transaction.edibat_gkey
inner join edi_interchange on edi_interchange.gkey=edi_batch.ediint_gkey
inner join edi_trading_partners on edi_trading_partners.gkey=edi_interchange.ediptnr_gkey
 where keyword_value_4='$this->rotation'  limit 1
");
			//print_r($qry->result());
				$data=$query->result_array();
							
			//print "SQL Query: ".$this->db2->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	function myExportImExSummeryView($rotation){
		try {
			$this->rotation=$rotation;
			$query = $this->db->query("select Voy_No from igm_masters where Import_Rotation_No='$rotation'");
			//$data=$query->result_array();
			$row = $query->row();
			$Voy_No=@$row->Voy_No;
			//print "SQL Query: ".$this->db->last_query();  
			return $Voy_No;
		} catch (Exception $ex) {
				return FALSE;
		}
	}
	//ASIF START 
	/*function myContDelete($gkey){
		try {
			$con=mysql_connect("10.1.1.21", "sparcsn4","sparcsn4")or die("cannot connect"); 
			mysql_select_db("ctmsmis")or die("cannot select DB");
			$query = mysql_query("select sparcsn4.inv_unit_fcy_visit.transit_state as trnsId from sparcsn4.inv_unit_fcy_visit where sparcsn4.inv_unit_fcy_visit.unit_gkey='$gkey'");
			$row=mysql_fetch_object($query);
			if($row->trnsId=='S60_LOADED' or $row->trnsId=='S70_DEPARTED')
			{
				//echo "UPDATE mis_exp_unit SET delete_flag='1' where gkey='$gkey'";
				$query = mysql_query("UPDATE mis_exp_unit SET delete_flag='1' where gkey='$gkey'");
				$affectedRow=mysql_affected_rows();
				mysql_query("COMMIT");
				return $affectedRow;
			
			}
			else{
				echo "DELETE FROM mis_exp_unit WHERE gkey='$gkey'";
				$sql=mysql_query("DELETE FROM mis_exp_unit WHERE gkey='$gkey'");
				$affectedRow=mysql_affected_rows();
				mysql_query("COMMIT");
				return $affectedRow;
			}

		} 
		catch (Exception $ex) {
            return 0;
        }
	}
	*/
	//ASIF END 
	
	
	
	function updateManifestList1($rotation) {
        try {
			
				$this->rotation=$rotation;
				
				$query = $this->db->query("select id from igm_details where Import_Rotation_No='$this->rotation'");
				$data=$query->result();
							
			//print "SQL Query: ".$this->db->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	function viewDeliveryOrder($rotation,$bl) {
        try {
			
				$this->rotation=str_replace("_","/",$rotation);
				
				$query = $this->db->query("select igm_detail_id,rotation,Line_No,BL_No,bill_of_entry_no,bill_of_entry_date,gp_no,gp_date,paid_date,voy_no,vessel_name,date,bill_no,arrived_from,pack_marks_number  as pack_marks_number,description  as description,if(consignee_id>0,consignee_name,(select Organization_Name from organization_profiles where organization_profiles.id=igm_delivery_order.cnf_id)) as cnf_ff,agent from igm_delivery_order where rotation='$this->rotation' and BL_No='$bl'");
				$data=$query->result_array();
							
			//print "SQL Query: ".$this->db->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	function releaseDeliveryOrder($rotation,$bl,$igm_detail_id) {
        try {
			
				$rotation=str_replace("_","/",$rotation);
				$_SESSION['login_id']=$this->session->userdata('login_id');
				
				$query = $this->db->query("update igm_delivery_order set released=1,released_by='$_SESSION[login_id]',released_time=now() where igm_detail_id='$igm_detail_id'");
				//$data=$query->result_array();
							
			//print "SQL Query: ".$this->db->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	function DeliveryOrderPortComment($rotation,$bl,$igm_detail_id,$port_comment) {
        try {
			
				$rotation=str_replace("_","/",$rotation);
				$_SESSION['login_id']=$this->session->userdata('login_id');
				echo $rotation."--".$bl."==".$port_comment."==".$igm_detail_id;
				$query = $this->db->query("update igm_delivery_order set port_comment='$port_comment',comment_by='$_SESSION[login_id]',comment_time=now() where igm_detail_id='$igm_detail_id'");
				//$data=$query->result_array();
							
			//print "SQL Query: ".$this->db->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	function loadedContainerListView($rotation) {
        try {
			
				$CI = &get_instance();
				$this->db2 = $CI->load->database('second', TRUE);
				$query = $this->db2->query("select vvd_gkey from sparcsn4.vsl_vessel_visit_details where ib_vyg='$rotation'");
				//$data=$query->result_array();
							
			//print "SQL Query: ".$this->db2->last_query();  
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	//Awal edi converter start
	function myEdiVslSearch($exp_rot) {
        try {
			
				
				$CI = &get_instance();
				$this->db2 = $CI->load->database('second', TRUE);
				$ediVslNumRow = 0;
				
				$query3=$this->db->query("select vsl_name as vsl_name,agent_code as agent_code,agent_name as agent_name,master_name as master_name,bearth as berth,ata,atd from edi_berth where Export_Rotation_no='$exp_rot' limit 1");
				$ediVslNumRow =$query3->num_rows();
				
				if($ediVslNumRow > 0)
				{	
					$data2=$query3->result_array();
					$query = $this->db->query("select vsl_name as vsl_name,agent_code as agent_code,agent_name as agent_name,master_name as master_name,bearth as berth,ata,atd from edi_berth where Export_Rotation_no='$exp_rot'");
					$data=$query->result_array();					
				} else{
					$query2 = $this->db2->query("select vsl_vessels.name as vsl_name,ref_bizunit_scoped.id as agent_code,
					ref_bizunit_scoped.name as agent_name,argo_quay.id as bearth,vsl_vessel_berthings.ata as ata,
					vsl_vessel_berthings.atd as atd,vsl_vessel_visit_details.ib_vyg 
					from vsl_vessel_visit_details 
					inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey 
					inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
					inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay 
					inner join ref_bizunit_scoped on ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
					where ib_vyg='$exp_rot' FETCH FIRST 1 ROWS ONLY");
					$data2=$query2->result_array();
					   
					
					$query = $this->db2->query("select vsl_vessels.name as vsl_name,ref_bizunit_scoped.id as agent_code,
					ref_bizunit_scoped.name as agent_name
					,argo_quay.id as berth,vsl_vessel_berthings.ata as ata,
					vsl_vessel_berthings.atd as atd from vsl_vessel_visit_details
					inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey 
					inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
					inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay 
					inner join ref_bizunit_scoped on ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
					where ob_vyg='$exp_rot'");
				    $quRes= $query2->result_array();
					if($query->num_rows() > 0)
					{
						foreach($query->result_array() as $row){
						
						//$agent_name=str_replace("'",' ',$row['AGENT_NAME']);
					    //$agent_name=str_replace('"',' ',$agent_name);
						//$rot=str_replace("/","",$exp_rot);
						/*$query3 = $this->db->query("replace into edi_berth(Export_Rotation_no,vsl_name,master_name,agent_code,agent_name,bearth,ata,atd) values('$rot','$row[VSL_NAME]','$row[master_name]','$row[agent_code]','$agent_name','$row[berth]','$row[ata]','$row[atd]')");*/
						}				
					}
					
					$data=$query->result_array();
				
				}	
							
			//print "SQL Query: ".$this->db2->last_query();  
			return array($data,$data2);

        } catch (Exception $ex) {
            return FALSE;
        }
    }
	
	function myEdiMasterInsert($agent,$voy, $callSign,$vsl_name,$date,$LOP,$nextPort,$login_id,$exp_no) {
        try {
			echo $exp_no;
			return;
				$ip_address=$this->session->userdata('ip_address');
				$query = $this->db->query("replace  into edi_master(agent_code,voys_no,call_sign,vsl_name,date,load_port,next_port,login_id,create_time,ip_address,Export_Rotation_No)  values ('$agent','$voy','$callSign','$vsl_name','$date','$LOP','$nextPort','$login_id',now(),'$ip_address','$exp_no')");
				//$data=$query->result_array();
							
				//print "SQL Query: ".$this->db->last_query();  
				return $query;

			} catch (Exception $ex) {
            return FALSE;
			}
    }
	
	function myEdiDetailInsert($container_no,$iso, $liner,$status,$weight,$booking,$seal,$imdg,$unno,$temp,$loadport,$dischargeport,$st,$login_id,$vsl_name,$exp_no) {
        try {
				$ip_address=$this->session->userdata('ip_address');
				$query2 = $this->db->query("select max(id) as id from edi_master where login_id='$login_id' and Export_Rotation_No='$exp_no'");
				//$data=$query->result_array();
				$edi_master_id = 0;
				foreach($query2->result_array() as $row)
					{
						$edi_master_id=$row['id'];
					}
				//echo $edi_master_id;
				//echo ("replace into edi_detail (edi_master_id,container_no,iso_code,line_op,status,weight,weight_unit,booking,seal,imdg,unno,temp,loasd_port,discharge_port,stowage,Export_Rotation_No) values ('$edi_master_id','$container_no','$iso','$liner','$status','$weight','KGS','$booking','$seal','$imdg','$unno','$temp','$loadport','$dischargeport','$st',$exp_no)");
				$query = $this->db->query("replace into edi_detail (edi_master_id,container_no,iso_code,line_op,status,weight,weight_unit,booking,seal,imdg,unno,temp,loasd_port,discharge_port,stowage,Export_Rotation_No) values ('$edi_master_id','$container_no','$iso','$liner','$status','$weight','KGS','$booking','$seal','$imdg','$unno','$temp','$loadport','$dischargeport','$st',$exp_no)");
				//$data=$query->result_array();
							
				//print "SQL Query: ".$this->db->last_query();  
				return $query;

			} catch (Exception $ex) {
				return FALSE;
			}
    }
	
	//Awal edi converter end
	
	public function dataInsert($str) 
	{
		// Oracle(sparcsn4)
		$CI = &get_instance();
		$this->db2 = $CI->load->database('second', TRUE);
		$query=$this->db2->query($str);
	  
	  return $query;
	  //$tableName="image";
	  //$this->db->insert($tableName, $data); 
		}
		
	public function dataUpdate($str) 
	{
		// Oracle(sparcsn4)
		$CI = &get_instance();
		$this->db2 = $CI->load->database('second', TRUE);
		$query=$this->db2->query($str);
	  
	  return $query;
	  //$tableName="image";
	  //$this->db->insert($tableName, $data); 
	}
	
	public function dataDelete($str) 
	{
		// Oracle(sparcsn4)
		$CI = &get_instance();
		$this->db2 = $CI->load->database('second', TRUE);
		$query=$this->db2->query($str);
	  
		return $query;
		//$tableName="image";
		//$this->db->insert($tableName, $data); 
	}	
	
	public function dataUpdatedb2($str) {
		// ctmsmis
		$CI = &get_instance();
		$this->db2 = $CI->load->database('third', TRUE);
		$query=$this->db2->query($str);
		return $query;
		 
    }

	public function dataUpdatedb3($str) {
		// ctmsmis
		$CI = &get_instance();
		$this->db3 = $CI->load->database('sixth', TRUE);
		$query=$this->db3->query($str);
		return $query;
		 
    }
	
	public function dataSelect($str)
	{
		// Oracle(sparcsn4)
		$CI = &get_instance();
		$this->db2 = $CI->load->database('second', TRUE);
		$query=$this->db2->query($str);
		//$query=$this->db->query($str);
		$data=$query->result_array();
	   
		return $data;
	}
	
	public function dataReturn($str) 
	{
		// Oracle(sparcsn4)
		$CI = &get_instance();
		$this->db2 = $CI->load->database('second', TRUE);
		$query=$this->db2->query($str);
		$row = $query->row();
		@$rtnValue=$row->RTNVALUE;  
		return $rtnValue;
	}
	
	public function dataSelectDb1($str)
	{
		// cchaportdb
		$query=$this->db->query($str);
		$data=$query->result_array();
		return $data;
	}
	
	public function dataSelectDb2($str)
	{
		// ctmsmis
		$CI = &get_instance();
		$this->db2 = $CI->load->database('third', TRUE);
		$query=$this->db2->query($str);
		//echo("<script>console.log('Query: ".$query."');</script>");
		//$query=$this->db->query($str);
		$data=$query->result_array();
	   
		return $data;
	}
	public function dataSelectDb3($str)
	{
		// ctmsmis
		$CI = &get_instance();
		$this->db3 = $CI->load->database('sixth', TRUE);
		$query=$this->db3->query($str);
	
		$data=$query->result_array();
	   
		return $data;
	}
	public function dataReturnDb1($str) 
	{
 
		// cchaportdb
		$query=$this->db->query($str);
		$row = $query->row();
		$rtnValue=$row->rtnValue;  
		return $rtnValue;
    }
	
	public function dataReturnDb2($str) 
	{
		// ctmsmis
		$CI = &get_instance();
		$this->db2 = $CI->load->database('third', TRUE);
		$query=$this->db2->query($str);
		$row = $query->row();
		@$rtnValue=$row->rtnValue;  
		return $rtnValue;
    }
	
	public function dataReturnDb3($str) 
	{
		// ctmsmis
		$CI = &get_instance();
		$this->db3 = $CI->load->database('sixth', TRUE);
		$query=$this->db3->query($str);
		$row = $query->row();
		@$rtnValue=$row->RTNVALUE;  
		return $rtnValue;
    }
	
	public function dataInsertDB1($str) 
	{
		// cchaportdb
		//$CI = &get_instance();
		//$this->db2 = $CI->load->database('second', TRUE);
		$query=$this->db->query($str);	  
		return $query;
	}
	
	public function dataInsertDb2($str) 
	{
		// ctmsmis
		$CI = &get_instance();
		$this->db2 = $CI->load->database('third', TRUE);
		$query=$this->db2->query($str);
		return $query;
	}

	public function dataInsertDb3($str) 
	{
		// ctmsmis
		$CI = &get_instance();
		$this->db3 = $CI->load->database('sixth', TRUE);
		$query=$this->db3->query($str);
		return $query;
	}
	
	
	public function dataUpdateDB1($str) 
	{
		// cchaportdb
		$query=$this->db->query($str);	  
		return $query;
	}
	
	public function dataDeleteDB1($str) 
	{
		// cchaportdb
	  $query=$this->db->query($str);	  
	  return $query;
	}

	public function dataDeleteDb2($str) 
	{
		// ctmsmis
		$CI = &get_instance();
		$this->db2 = $CI->load->database('third', TRUE);
		$query=$this->db2->query($str);
		return $query;
	}		

	public function dataDeleteDb3($str) 
	{
		// ctmsmis
		$CI = &get_instance();
		$this->db3 = $CI->load->database('sixth', TRUE);
		$query=$this->db3->query($str);
		return $query;
	}		
	
	//edi convert start
	
	
	public function dataInsertdb5($str) 
	{
		// Oracle(sparcsn4)
		$CI = &get_instance();
		$this->db2 = $CI->load->database('fifth', TRUE);
		$query=$this->db2->query($str);	  
		return $query;
	}
		
	public function dataUpdatedb5($str) 
	{
		// Oracle(sparcsn4)
		$CI = &get_instance();
		$this->db2 = $CI->load->database('fifth', TRUE);
		$query=$this->db2->query($str);	  
	    return $query;
	}
	
	public function dataDeletedb5($str) 
	{
		// Oracle(sparcsn4)
		$CI = &get_instance();
		$this->db2 = $CI->load->database('fifth', TRUE);
		$query=$this->db2->query($str);
	  
		return $query;
	}	
	
	
	public function dataSelectdb5($str)
	{
		// Oracle(sparcsn4)
		$CI = &get_instance();
		$this->db2 = $CI->load->database('fifth', TRUE);
		$query=$this->db2->query($str);
		//$query=$this->db->query($str);
		$data=$query->result_array();
	   
		return $data;
	}
	
	public function dataReturndb5($str) 
	{
		// Oracle(sparcsn4)
		$CI = &get_instance();
		$this->db2 = $CI->load->database('fifth', TRUE);
		$query=$this->db2->query($str);
		$row = $query->row();
		@$rtnValue=$row->RTNVALUE;  
		return $rtnValue;
	}
	
	public function record_count_row($tableName) {
        return $this->db->count_all("$tableName");
    }
	
	public function record_count_edi($table_name,$exp_no) 
	{
        $this->db->like('Export_Rotation_No');
		$this->db->from("$table_name");
		$this->db->where('Export_Rotation_No', $exp_no );
       // echo $this->db->count_all_results();
		 
		  return $this->db->count_all_results();
    }
	
	function myEdiDetail($mylimit, $start)
	{
		try 
		{
			$ip_address=$this->session->userdata('ip_address');
			$login_id=$this->session->userdata('login_id');
				
			$query = $this->db->query("select concat(substring(Export_Rotation_No,1,4),'/',substring(Export_Rotation_No,5)) as Export_Rotation_No,voys_no,call_sign,vsl_name,date,load_port,next_port,create_time from edi_master where login_id='$login_id' LIMIT $start,$mylimit ");
			$data=$query->result_array();
							
			//print "SQL Query: ".$this->db->last_query();  
			return $data;
		} 
		catch (Exception $ex) 
		{
            return FALSE;
		}
	}
	
	function viewBerthDetails($exp_no)
	{
		try 
		{
			$query = $this->db->query("select concat(substring(Export_Rotation_No,1,4),'/',substring(Export_Rotation_No,5)) as Export_Rotation_No,vsl_name,master_name,agent_code,agent_name,bearth,ata,atd from edi_berth where Export_Rotation_no='$exp_no'");
			$data=$query->result_array();
							
			//print "SQL Query: ".$this->db->last_query();  
			return $data;
		} 
		catch (Exception $ex) 
		{
            return FALSE;
		}
	}
	
	function viewEDIDetails($exp_no,$mylimit, $start)
	{
		try 
		{
			$query = $this->db->query("select concat(substring(Export_Rotation_No,1,4),'/',substring(Export_Rotation_No,5)) as Export_Rotation_No,container_no,iso_code,line_op,status,concat(weight,' ',weight_unit) as weight,seal,imdg,unno,temp,loasd_port,discharge_port,stowage from edi_detail where Export_Rotation_No='$exp_no' LIMIT $start,$mylimit ");
			$data=$query->result_array();
							
			//print "SQL Query: ".$this->db->last_query();  
			return $data;
		} 
		catch (Exception $ex) 
		{
            return FALSE;
		}
	}
	
	function myEDIDetailSearch($SearchCriteria, $Searchdata) 
	{
        try 
		{
			if($SearchCriteria=="Export_Rotation_No")
				$Searchdata=str_replace("/","",$Searchdata);
			$Searchdata=str_replace(" ","",$Searchdata);
				
			$query = $this->db->query("select concat(substring(Export_Rotation_No,1,4),'/',substring(Export_Rotation_No,5)) as Export_Rotation_No,container_no,iso_code,line_op,status,concat(weight,' ',weight_unit) as weight,seal,imdg,unno,temp,loasd_port,discharge_port,stowage from edi_detail  where replace($SearchCriteria,' ','')  like '%$Searchdata%'");
			$data=$query->result_array();
			//print "SQL Query: ".$this->db->last_query();
			return $data;
		} 
		catch (Exception $ex) 
		{
            return FALSE;
        }
    }
	
	function myEDIListSearch($SearchCriteria, $Searchdata) 
	{
        try 
		{
			if($SearchCriteria=="Export_Rotation_No")
				$Searchdata=str_replace("/","",$Searchdata);
				
			$query = $this->db->query("select concat(substring(Export_Rotation_No,1,4),'/',substring(Export_Rotation_No,5)) as Export_Rotation_No,voys_no,call_sign,vsl_name,date,load_port,next_port,create_time from edi_master where $SearchCriteria  like '%$Searchdata%'");
			$data=$query->result_array();
			//print "SQL Query: ".$this->db->last_query();
			return $data;
		} 
		catch (Exception $ex) 
		{
            return FALSE;
        }
    }
	//edi convert end

	function myListFormBB($type,$limit, $start) {
        try {
			
			$this->db->limit($limit, $start);
			$this->db->select('igm_masters.id,igm_masters.Import_Rotation_No,igm_masters.Export_Rotation_No,igm_masters.Sailed_Year,
				igm_masters.Sailed_Date,vessels_berth_detail.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igm_masters.Vessel_Id,
				igm_masters.Vessel_Name,igm_masters.Voy_No,igm_masters.Net_Tonnage,
				igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
				igm_masters.file_clearence_date,Organization_Name as org_name,igm_masters.Submitee_Org_Type 
				as Submitee_Org_Type,igm_masters.S_Org_License_Number as S_Org_License_Number,igm_masters.Submission_Date 
				as Submission_Date,igm_masters.flag as flag,igm_masters.imo as imo,igm_masters.line_belongs_to as line_belongs_to ');
            $this->db->from('igm_masters');
			$this->db->join('vessels_berth_detail', 'vessels_berth_detail.igm_id = igm_masters.id', 'left');
			$this->db->join('organization_profiles', 'organization_profiles.id = igm_masters.Submitee_Org_Id', 'left');
            $this->db->where('vsl_dec_type', $type );
			$this->db->order_by('file_clearence_date', 'desc');
			$data = $this->db->get()->result_array();
			
			//echo $this->db->_compile_select(); 
			
			//print "SQL Query: ".$this->db->last_query();  

            return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
	}
	
	function updateAssignmet_1()
	{
		$strGetMaxEvent = "SELECT MAX(event_gkey) AS rtnValue FROM ctmsmis.tmp_vcms_assignment";
		$MaxEvent = "";
		$MaxEventList = $this->dataSelect($strGetMaxEvent);
		for($e=0;$e<count($MaxEventList);$e++)
		{
			$MaxEvent = $MaxEventList[$e]["rtnValue"];
		}
		//$MaxEvent = 450871953;
		if($MaxEvent!="")
		{
			



			$strAssignment = "SELECT *,
			(SELECT mfdch_desc FROM sparcsn4.config_metafield_lov WHERE mfdch_value=tbl.mfdch_value) AS mfdch_desc,
			(SELECT ib_vyg FROM sparcsn4.vsl_vessel_visit_details
			WHERE vvd_gkey=cvcvd_gkey
			) AS imt_rot,
			(SELECT sparcsn4.vsl_vessels.name FROM sparcsn4.vsl_vessel_visit_details
			INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			WHERE vvd_gkey=cvcvd_gkey
			) AS v_name
			FROM (
			SELECT DISTINCT sparcsn4.srv_event.gkey AS event_gkey,a.gkey AS unit_gkey,a.id AS cont_no,a.freight_kind,
			b.flex_date01,DATE(b.flex_date01) AS assignDt,b.last_pos_slot,(SELECT ctmsmis.cont_yard(b.last_pos_slot)) AS Yard_No,
			(SELECT ctmsmis.cont_block(b.last_pos_slot, Yard_No)) AS Block_No,a.seal_nbr1,
			(SELECT RIGHT(nominal_length, 2) FROM sparcsn4.ref_equip_type
			INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
			INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey)  AS size,
			CAST((SELECT (RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10) FROM sparcsn4.ref_equip_type
			INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
			INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey) AS DECIMAL(10,1))  AS height,
			(SELECT k.id FROM sparcsn4.inv_goods j
			LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
			WHERE j.gkey = a.goods
			) AS cf_lic,
			(SELECT k.name FROM sparcsn4.inv_goods j
			LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
			WHERE j.gkey = a.goods
			) AS cf_name,
			(SELECT sms_number FROM sparcsn4.ref_bizunit_scoped WHERE id=cf_lic) AS sms_number,
			(SELECT k.name FROM sparcsn4.ref_bizunit_scoped k WHERE k.gkey=a.line_op) AS mlo,
			(SELECT cvcvd_gkey FROM sparcsn4.argo_carrier_visit WHERE gkey=b.actual_ib_cv) AS cvcvd_gkey,
			 a.flex_string01 AS mfdch_value
			FROM sparcsn4.srv_event 
			INNER JOIN sparcsn4.inv_unit a ON a.gkey = sparcsn4.srv_event.applied_to_gkey
			INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
			INNER JOIN  sparcsn4.srv_event_field_changes ON srv_event_field_changes.event_gkey = srv_event.gkey
			WHERE sparcsn4.srv_event.gkey>$MaxEvent AND event_type_gkey=4
			AND (metafield_id = 'unitFlexString01' OR metafield_id = 'ufvFlexDate01')
			) AS tbl WHERE mfdch_value IS NOT NULL AND mfdch_value !='CANCEL'";





			// if container missing then uncomment with container number to insert
			/*
			$strAssignment = "SELECT *,
			(SELECT mfdch_desc FROM sparcsn4.config_metafield_lov WHERE mfdch_value=tbl.mfdch_value) AS mfdch_desc,
			(SELECT ib_vyg FROM sparcsn4.vsl_vessel_visit_details
			WHERE vvd_gkey=cvcvd_gkey
			) AS imt_rot,
			(SELECT sparcsn4.vsl_vessels.name FROM sparcsn4.vsl_vessel_visit_details
			INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			WHERE vvd_gkey=cvcvd_gkey
			) AS v_name
			FROM (
			SELECT DISTINCT sparcsn4.srv_event.gkey AS event_gkey,a.gkey AS unit_gkey,a.id AS cont_no,a.freight_kind,
			b.flex_date01,DATE(b.flex_date01) AS assignDt,b.last_pos_slot,(SELECT ctmsmis.cont_yard(b.last_pos_slot)) AS Yard_No,
			(SELECT ctmsmis.cont_block(b.last_pos_slot, Yard_No)) AS Block_No,a.seal_nbr1,
			(SELECT RIGHT(nominal_length, 2) FROM sparcsn4.ref_equip_type
			INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
			INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey)  AS size,
			CAST((SELECT (RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10) FROM sparcsn4.ref_equip_type
			INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
			INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey) AS DECIMAL(10,1))  AS height,
			(SELECT k.id FROM sparcsn4.inv_goods j
			LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
			WHERE j.gkey = a.goods
			) AS cf_lic,
			(SELECT k.name FROM sparcsn4.inv_goods j
			LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
			WHERE j.gkey = a.goods
			) AS cf_name,
			(SELECT sms_number FROM sparcsn4.ref_bizunit_scoped WHERE id=cf_lic) AS sms_number,
			(SELECT k.name FROM sparcsn4.ref_bizunit_scoped k WHERE k.gkey=a.line_op) AS mlo,
			(SELECT cvcvd_gkey FROM sparcsn4.argo_carrier_visit WHERE gkey=b.actual_ib_cv) AS cvcvd_gkey,
			 a.flex_string01 AS mfdch_value
			FROM sparcsn4.srv_event 
			INNER JOIN sparcsn4.inv_unit a ON a.gkey = sparcsn4.srv_event.applied_to_gkey
			INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
			INNER JOIN  sparcsn4.srv_event_field_changes ON srv_event_field_changes.event_gkey = srv_event.gkey
			WHERE a.id='DFSU7481067' AND event_type_gkey=4
			AND (metafield_id = 'unitFlexString01' OR metafield_id = 'ufvFlexDate01')
			) AS tbl WHERE mfdch_value IS NOT NULL AND mfdch_value !='CANCEL'";
			*/





			$resAssignment = $this->dataSelect($strAssignment);
			for($i=0;$i<count($resAssignment);$i++)
			{
				$event_gkey = $resAssignment[$i]["event_gkey"];
				$unit_gkey = $resAssignment[$i]["unit_gkey"];
				$cont_no = $resAssignment[$i]["cont_no"];
				$freight_kind = $resAssignment[$i]["freight_kind"];
				$flex_date01 = $resAssignment[$i]["flex_date01"];
				$assignDt = $resAssignment[$i]["assignDt"];
				$last_pos_slot = $resAssignment[$i]["last_pos_slot"];
				$Yard_No = $resAssignment[$i]["Yard_No"];
				$Block_No = $resAssignment[$i]["Block_No"];
				$seal_nbr1 = $resAssignment[$i]["seal_nbr1"];
				$size = $resAssignment[$i]["size"];
				$height = $resAssignment[$i]["height"];
				$cf_lic = $resAssignment[$i]["cf_lic"];
				// $cf_name = $resAssignment[$i]["cf_name"];
				$cf_name = str_replace("'","",$resAssignment[$i]["cf_name"]);
				$cf_sms_number = $resAssignment[$i]["sms_number"];
				// $mlo = $resAssignment[$i]["mlo"];
				$mlo = str_replace("'","",$resAssignment[$i]["mlo"]);
				$cvcvd_gkey = $resAssignment[$i]["cvcvd_gkey"];
				$mfdch_value = $resAssignment[$i]["mfdch_value"];
				$mfdch_desc = $resAssignment[$i]["mfdch_desc"];
				$imt_rot = $resAssignment[$i]["imt_rot"];
				$v_name = $resAssignment[$i]["v_name"];
				
				$strBl = "SELECT BL_No AS rtnValue FROM igm_details 
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$imt_rot' AND igm_detail_container.cont_number='$cont_no'";
				$BL_NoList = $this->dataSelectDb1($strBl);
				$BL_No = "";
				for($bl=0;$bl<count($BL_NoList);$bl++)
				{
					$BL_No = $BL_NoList[$bl]["rtnValue"];
				}
				
				$strChkOrg = "SELECT COUNT(*) AS rtnValue FROM organization_profiles WHERE License_No='$cf_lic' AND Org_Type_id=2";
				$ChkOrg = $this->dataReturnDb1($strChkOrg);
				if($ChkOrg==0)
				{
					$cfLicNew = "0".$cf_lic;
					$strChkOrgNew = "SELECT COUNT(*) AS rtnValue FROM organization_profiles WHERE License_No='$cfLicNew' AND Org_Type_id=2";
					$ChkOrgNew = $this->dataReturnDb1($strChkOrgNew);
					if($ChkOrgNew!=0)
					{
						$strUpdateLic = "UPDATE organization_profiles SET License_No='$cf_lic' WHERE License_No='$cfLicNew' AND Org_Type_id=2";
						$this->dataUpdateDB1($strUpdateLic);
					}
				}





				/*$strSlot = "SELECT COUNT(unit_gkey) AS rtnValue FROM ctmsmis.tmp_vcms_assignment 
				WHERE assignmentDate='$assignDt'";
				$slot1Value = $this->dataReturn($strSlot);
				$aslot = 0;
				if($slot1Value<=1050)
					$aslot = 1;
				else if($slot1Value>1050 and $slot1Value<=1500)
					$aslot = 2;
				else
					$aslot = 3;
				*/




				$strchk = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_vcms_assignment WHERE unit_gkey='$unit_gkey'";
				$chk = $this->dataReturn($strchk);
				if($chk==0)
				{
					$strInsrt = "INSERT INTO ctmsmis.tmp_vcms_assignment(unit_gkey,cont_no,cf_lic,cf_name,cf_sms_number,cont_status,slot,size,height,
					v_name,rot_no,mlo,bl_no,assignmentDate,flex_date01,mfdch_value,mfdch_desc,Yard_No,Block_No,remarks,seal_nbr1,event_gkey) 
					VALUES ('$unit_gkey','$cont_no','$cf_lic','$cf_name','$cf_sms_number','$freight_kind','$last_pos_slot','$size','$height',
					'$v_name','$imt_rot','$mlo','$BL_No','$assignDt','$flex_date01','$mfdch_value','$mfdch_desc',
					'$Yard_No','$Block_No','','$seal_nbr1','$event_gkey')";
					$this->dataInsert($strInsrt);
				}
				else
				{
					$strUpdate = "UPDATE ctmsmis.tmp_vcms_assignment SET assignmentDate='$assignDt',mfdch_value='$mfdch_value',mfdch_desc='$mfdch_desc',
					slot='$last_pos_slot',Yard_No='$Yard_No',Block_No='$Block_No',event_gkey='$event_gkey' WHERE unit_gkey='$unit_gkey'";
					$this->dataUpdate($strUpdate);
				}
			}
			
			$strUpdateEmty = "UPDATE ctmsmis.tmp_vcms_assignment 
			INNER JOIN sparcsn4.inv_unit ON sparcsn4.inv_unit.gkey=ctmsmis.tmp_vcms_assignment.unit_gkey
			INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
			INNER JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
			INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.inv_goods.consignee_bzu
			SET ctmsmis.tmp_vcms_assignment.cf_lic=sparcsn4.ref_bizunit_scoped.id,
			ctmsmis.tmp_vcms_assignment.cf_name=sparcsn4.ref_bizunit_scoped.name,
			ctmsmis.tmp_vcms_assignment.cf_sms_number=sparcsn4.ref_bizunit_scoped.sms_number,
			ctmsmis.tmp_vcms_assignment.slot=sparcsn4.inv_unit_fcy_visit.last_pos_slot,
			ctmsmis.tmp_vcms_assignment.Yard_No=(SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)),
			ctmsmis.tmp_vcms_assignment.Block_No=(SELECT ctmsmis.cont_block(sparcsn4.inv_unit_fcy_visit.last_pos_slot,
			(SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot))))
			WHERE assignmentDate = DATE(NOW())";		
			//WHERE assignmentDate BETWEEN DATE(NOW()) AND DATE_ADD(DATE(NOW()), INTERVAL 1 DAY)";	
			$this->dataUpdate($strUpdateEmty);
		}
	}
	
	function assignmentList($org_license)
	{
		$sql_cnfGkey = "SELECT gkey AS rtnValue FROM ref_bizunit_scoped WHERE id='$org_license'";
		$cnfGkey = $this->dataReturn($sql_cnfGkey);	
		
		return "SELECT DISTINCT a.gkey AS unit_gkey,a.id AS cont_no,k.name  AS cnf,a.freight_kind AS cont_status,
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
		WHERE DATE(b.flex_date01) = DATE(NOW()) AND j.consignee_bzu = '$cnfGkey' AND config_metafield_lov.mfdch_value!='CANCEL'";
	}



	
	function chkContainerStatus($cont_no,$rotNo)
	{
		$sql_contStatus="SELECT IFNULL((SELECT cont_status AS rtnValue FROM igm_supplimentary_detail INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_sup_detail_container.cont_number='$cont_no' 
		UNION

		SELECT cont_status AS rtnValue FROM igm_details 
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
		WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$cont_no' LIMIT 1),'') AS rtnValue";
		
		$contStatRslt=$this->bm->dataSelectDb1($sql_contStatus);	
		$contStatus=$contStatRslt[0]['rtnValue'];
		return $contStatus;
	}

	function chkContainerStatusforPartBl($cont_no,$rotNo)
	{
		$sql_contStatus="SELECT cont_status FROM(
			SELECT Import_Rotation_No,BL_No,cont_number,cont_status
			FROM igm_supplimentary_detail
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_sup_detail_container.cont_number='$cont_no'
			UNION
			SELECT Import_Rotation_No,BL_No,cont_number,cont_status
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$cont_no'
			) AS rtnValue LIMIT 1";
		
		$contStatRslt=$this->bm->dataSelectDb1($sql_contStatus);	
		$contStatus=$contStatRslt[0]['cont_status'];
		return $contStatus;
	}
	
	function sendSMS($cell_no, $smsText)
	{
		$base_url ='https://ej8nq1.api.infobip.com/sms/1/text/query?';
		$username = 'username=datasoft_ctms';
		$pass = '&password=Datasoft!ad2min3789'; 
		//$str='Your%20verification%20code:%20'.$num_str;
		
		 $strInsrt = "INSERT INTO cchaportdb.sms_transaction_otp(cell_no,TIME, smstext) VALUES ('$cell_no', NOW(), '$smsText')";
		$this->dataInsertDB1($strInsrt);	
		$message = '&sender=8809617654290&to=88'.$cell_no.'&text='.$smsText.'&from=8809617654290';
		$url = $base_url.''.$username.''.$pass.''.$message;
		//echo $url;
		//return; 
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
	}
	
	
	function sonaliPayVerifyJsonbody($ref_date, $requ_id)
	{
		$verify_post = '{
					"AccessUser" : {
					"userName":"CtGPoRt2015",
					"password":"XporLocDbs$TghDl23@34t97"
					},
					"OwnerCode":"cgtport",
					"ReferenceDate":"'.$ref_date.'",
					"RequiestNo":"'.$requ_id.'",
					"isEncPwd":true
					}';
		return $verify_post;
	}

	function getVslNameN4($rot_no)
	{
		 $query = "SELECT vsl_vessels.name AS rtnValue
		 FROM vsl_vessels
		 INNER JOIN vsl_vessel_visit_details ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		 WHERE vsl_vessel_visit_details.ib_vyg='$rot_no'";
         $vsselName = $this->bm->dataReturn($query);
         return $vsselName;		 
	}

	// function chkBlockedContainer($cont = null)
	// {
	// 	$query = "SELECT custom_block_st 
	// 	FROM ctmsmis.tmp_vcms_assignment 
	// 	WHERE cont_no = '$cont' AND custom_block_st = 'Blocked' 
	// 	ORDER BY block_update_dt DESC 
	// 	LIMIT 1";
	// 	$sts = $this->bm->dataSelect($query);
	// 	return $sts;					
	// }

	function chkBlockedContainer($cont = null,$visitId = null)
	{				
		$sql_rotBl="SELECT igm_details.Import_Rotation_No AS rot_no,igm_details.BL_No AS bl_no
		FROM igm_details
		INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
		INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
		WHERE do_truck_details_entry.id='$visitId'";
		$rslt_rotBl = $this->bm->dataSelectDB1($sql_rotBl);
		
		$rotNo = "";
		$blNo = "";
		for($i=0;$i<count($rslt_rotBl);$i++){
			$rotNo = $rslt_rotBl[$i]['rot_no'];
			$blNo = $rslt_rotBl[$i]['bl_no'];
		}
		
		$sql_releaseChk = "SELECT release_flag AS custom_block_st
		FROM nbr_block_unblock_data
		INNER JOIN nbr_block_unblock_cont_no ON nbr_block_unblock_cont_no.block_unblock_id=nbr_block_unblock_data.id
		WHERE nbr_block_unblock_cont_no.cont_no='$cont' AND rotation_no='$rotNo' AND bl_ref='$blNo'";
		$sts = $this->bm->dataSelectDB1($sql_releaseChk);
		return $sts;
	}

	// function chkBlockedContainer($cont = null,$visitId = null)
	// {				
	// 	return array();
	// }

	function chkBlockedContainerforTruckEntry($cont = null,$rotNo = null,$blNo = null)
	{				
		$sql_releaseChk = "SELECT release_flag AS custom_block_st
		FROM nbr_block_unblock_data
		INNER JOIN nbr_block_unblock_cont_no ON nbr_block_unblock_cont_no.block_unblock_id=nbr_block_unblock_data.id
		WHERE nbr_block_unblock_cont_no.cont_no='$cont' AND rotation_no='$rotNo' AND bl_ref='$blNo'";
		$sts = $this->bm->dataSelectDB1($sql_releaseChk);
		return $sts;
	}

	function getBlByRotCont($rotNo = null,$cont = null)
	{
		$sql_bl = "SELECT BL_No 
		FROM igm_details 
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
		WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$cont'";
		$bl_result = $this->bm->dataSelectDB1($sql_bl);
		$bl = "";
		for($i=0;$i<count($bl_result);$i++){
			$bl = $bl_result[$i]['BL_No'];
		}
		return $bl;
	}
	
	function VesselDataDumpingByDT($fromDate,$toDate)
	{
		$query = "SELECT vsl_vessel_visit_details.vvd_gkey,to_char(argo_carrier_visit.ata,'yyyy-mm-dd') AS arrivalDt,
		to_char(argo_carrier_visit.atd,'yyyy-mm-dd') AS departDt, vsl_vessels.name AS vsl_name,vsl_vessels.ves_captain,
		vsl_vessel_classes.loa_cm,vsl_vessel_classes.gross_registered_ton AS grt,
		vsl_vessel_classes.net_registered_ton AS nrt,ref_bizunit_scoped.name line,k.name AS agent,
		ref_country.cntry_code,
		ref_country.cntry_name,to_char(ata,'yyyy-mm-dd hh24:mi:ss') as ata, 
		to_char(atd,'yyyy-mm-dd hh24:mi:ss') as atd, vsl_vessel_visit_details.ib_vyg,
		vsl_vessel_visit_details.off_port_arr AS off_port_arr,vsl_vessel_visit_details.off_port_dep AS off_port_dep,
		vsl_vessel_visit_details.notes,to_char(argo_visit_details.eta,'yyyy-mm-dd hh24:mi:ss') as eta,
		to_char(argo_visit_details.etd,'yyyy-mm-dd hh24:mi:ss') as etd,vsl_vessels.lloyds_id,vsl_vessel_classes.basic_class,
		(CASE
		WHEN vsl_vessel_classes.basic_class='CELL'
		THEN 'CONTAINER'
		WHEN vsl_vessel_classes.basic_class='BBULK'
		THEN 'BREAK BULK'
		WHEN vsl_vessel_classes.basic_class='PSNGR'
		THEN 'PESSENGER'
		WHEN vsl_vessel_classes.basic_class='UNKNOWN'
		THEN vsl_vessel_classes.notes	
		ELSE vsl_vessel_classes.basic_class	
		END) AS vsl_class
		FROM argo_carrier_visit
		INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		INNER JOIN vsl_vessel_classes ON vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey
		INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
		LEFT JOIN ref_agent_representation ON ref_agent_representation.bzu_gkey=ref_bizunit_scoped.gkey
		LEFT JOIN ref_agent ON ref_agent.agent_id=ref_agent_representation.agent_gkey
		LEFT JOIN ref_bizunit_scoped k ON k.gkey=ref_agent.agent_id
		INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
		WHERE to_char(argo_carrier_visit.atd,'yyyy-mm-dd') BETWEEN '$fromDate' AND '$toDate'
		ORDER BY argo_carrier_visit.atd ASC";


		
		$result = $this->dataSelect($query);
		
		
		for($i=0;$i<count($result);$i++){
			$vvd_gkey = $result[$i]['VVD_GKEY'];
			//echo $vvd_gkey. "   ";
			//return;
			$arrivalDt = $result[$i]['ARRIVALDT'];
			$departDt = $result[$i]['DEPARTDT'];
			$vsl_name = $result[$i]['VSL_NAME'];
			$ves_captain = $result[$i]['VES_CAPTAIN'];
			$loa_cm = $result[$i]['LOA_CM'];
			$grt = $result[$i]['GRT'];
			$nrt = $result[$i]['NRT'];
			$line = $result[$i]['LINE'];
			$agent = $result[$i]['AGENT'];
			$cntry_code = $result[$i]['CNTRY_CODE'];
			$cntry_name = $result[$i]['CNTRY_NAME'];
			$ata = $result[$i]['ATA'];
			$atd = $result[$i]['ATD'];
			$ib_vyg = $result[$i]['IB_VYG'];
			$off_port_arr = $result[$i]['OFF_PORT_ARR'];
			$off_port_dep = $result[$i]['OFF_PORT_DEP'];
			$notes = $result[$i]['NOTES'];
			$eta = $result[$i]['ETA'];
			$etd = $result[$i]['ETD'];
			$lloyds_id = $result[$i]['LLOYDS_ID'];
			$vsl_class = $result[$i]['VSL_CLASS'];
			$basic_class = $result[$i]['BASIC_CLASS'];

			$checkQuery="SELECT COUNT(*) AS rtnValue FROM ctmsmis.vsl_vssel_info WHERE ctmsmis.vsl_vssel_info.vvd_gkey='$vvd_gkey'";
			$checkDuplicateValue= $this->dataReturnDb2($checkQuery);

			
		
			if($checkDuplicateValue>0){

				$query1="UPDATE ctmsmis.vsl_vssel_info
				SET vvd_gkey='$vvd_gkey',arrivalDt='$arrivalDt',departDt='$departDt',vsl_name='$vsl_name',
				ves_captain='$ves_captain',loa_cm='$loa_cm',grt='$grt',nrt='$nrt',line='$line',
				agent='$agent',cntry_code='$cntry_code',cntry_code='$cntry_code',ata='$ata',atd='$atd',ib_vyg='$ib_vyg',
				off_port_arr='$off_port_arr',off_port_dep='$off_port_dep',notes='$notes',
				eta='$eta',etd='$etd',lloyds_id='$lloyds_id',vsl_class='$vsl_class',basic_class='$basic_class'
				WHERE ctmsmis.vsl_vssel_info.vvd_gkey='$vvd_gkey'";
			
				$this->dataUpdatedb2($query1);


			}
			else{
			$query1="insert into ctmsmis.vsl_vssel_info (vvd_gkey,arrivalDt,departDt,vsl_name,
			ves_captain,loa_cm,grt,nrt,line,agent,cntry_code,cntry_name,ata,atd,ib_vyg,
			off_port_arr,off_port_dep,notes,eta,etd,lloyds_id,vsl_class,basic_class)
			values('$vvd_gkey','$arrivalDt','$departDt','$vsl_name',
			'$ves_captain','$loa_cm','$grt','$nrt','$line','$agent',
			'$cntry_code','$cntry_name','$ata','$atd','$ib_vyg','$off_port_arr',
			'$off_port_dep','$notes','$eta','$etd','$lloyds_id','$vsl_class','$basic_class')";

			$this->dataInsertDb2($query1);

			}
			
			$numRows=0;
			$evenTypeQuery="SELECT COUNT(*) AS rtnValue 
			FROM srv_event 
			WHERE srv_event.applied_to_gkey='$vvd_gkey' AND srv_event.event_type_gkey='163'";
			$numRows=$this->dataReturn($evenTypeQuery);	
			if($numRows>0){
				$evenTypeUpdateQuery="UPDATE ctmsmis.vsl_vssel_info 
				SET event_type_gkey='163' 
				where ctmsmis.vsl_vssel_info.vvd_gkey='$vvd_gkey'";	
				$this->dataUpdatedb2($evenTypeUpdateQuery);
			}
			
			
		}
	}
	
	function VesselDataDumpingByVVDGkey($vvd_gkey)
	{
		$query = "SELECT vsl_vessel_visit_details.vvd_gkey,to_char(argo_carrier_visit.ata,'yyyy-mm-dd') AS arrivalDt,
		to_char(argo_carrier_visit.atd,'yyyy-mm-dd') AS departDt, vsl_vessels.name AS vsl_name,vsl_vessels.ves_captain,
		vsl_vessel_classes.loa_cm,vsl_vessel_classes.gross_registered_ton AS grt,
		vsl_vessel_classes.net_registered_ton AS nrt,ref_bizunit_scoped.name line,k.name AS agent,
		ref_country.cntry_code,
		ref_country.cntry_name,to_char(ata,'yyyy-mm-dd hh24:mi:ss') as ata, 
		to_char(atd,'yyyy-mm-dd hh24:mi:ss') as atd, vsl_vessel_visit_details.ib_vyg,
		vsl_vessel_visit_details.off_port_arr AS off_port_arr,vsl_vessel_visit_details.off_port_dep AS off_port_dep,
		vsl_vessel_visit_details.notes,to_char(argo_visit_details.eta,'yyyy-mm-dd hh24:mi:ss') as eta,
		to_char(argo_visit_details.etd,'yyyy-mm-dd hh24:mi:ss') as etd,vsl_vessels.lloyds_id,vsl_vessel_classes.basic_class,
		(CASE
		WHEN vsl_vessel_classes.basic_class='CELL'
		THEN 'CONTAINER'
		WHEN vsl_vessel_classes.basic_class='BBULK'
		THEN 'BREAK BULK'
		WHEN vsl_vessel_classes.basic_class='PSNGR'
		THEN 'PESSENGER'
		WHEN vsl_vessel_classes.basic_class='UNKNOWN'
		THEN vsl_vessel_classes.notes	
		ELSE vsl_vessel_classes.basic_class	
		END) AS vsl_class
		FROM argo_carrier_visit
		INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		INNER JOIN vsl_vessel_classes ON vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey
		INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
		LEFT JOIN ref_agent_representation ON ref_agent_representation.bzu_gkey=ref_bizunit_scoped.gkey
		LEFT JOIN ref_agent ON ref_agent.agent_id=ref_agent_representation.agent_gkey
		LEFT JOIN ref_bizunit_scoped k ON k.gkey=ref_agent.agent_id
		INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code
		WHERE vsl_vessel_visit_details.vvd_gkey = '$vvd_gkey'
		ORDER BY argo_carrier_visit.atd ASC";
		$result = $this->dataSelect($query);

		for($i=0;$i<count($result);$i++){
			$vvd_gkey = $result[$i]['VVD_GKEY'];
			//echo $vvd_gkey. "   ";
			//return;
			$arrivalDt = $result[$i]['ARRIVALDT'];
			$departDt = $result[$i]['DEPARTDT'];
			$vsl_name = $result[$i]['VSL_NAME'];
			$ves_captain = $result[$i]['VES_CAPTAIN'];
			$loa_cm = $result[$i]['LOA_CM'];
			$grt = $result[$i]['GRT'];
			$nrt = $result[$i]['NRT'];
			$line = $result[$i]['LINE'];
			$agent = $result[$i]['AGENT'];
			$cntry_code = $result[$i]['CNTRY_CODE'];
			$cntry_name = $result[$i]['CNTRY_NAME'];
			$ata = $result[$i]['ATA'];
			$atd = $result[$i]['ATD'];
			$ib_vyg = $result[$i]['IB_VYG'];
			$off_port_arr = $result[$i]['OFF_PORT_ARR'];
			$off_port_dep = $result[$i]['OFF_PORT_DEP'];
			$notes = $result[$i]['NOTES'];
			$eta = $result[$i]['ETA'];
			$etd = $result[$i]['ETD'];
			$lloyds_id = $result[$i]['LLOYDS_ID'];
			$vsl_class = $result[$i]['VSL_CLASS'];
			$basic_class = $result[$i]['BASIC_CLASS'];

			$checkQuery="SELECT COUNT(*) AS rtnValue FROM ctmsmis.vsl_vssel_info WHERE ctmsmis.vsl_vssel_info.vvd_gkey='$vvd_gkey'";
			$checkDuplicateValue= $this->dataReturnDb2($checkQuery);
		
			if($checkDuplicateValue>0){

				$query1="UPDATE ctmsmis.vsl_vssel_info
				SET vvd_gkey='$vvd_gkey',arrivalDt='$arrivalDt',departDt='$departDt',vsl_name='$vsl_name',
				ves_captain='$ves_captain',loa_cm='$loa_cm',grt='$grt',nrt='$nrt',line='$line',
				agent='$agent',cntry_code='$cntry_code',cntry_code='$cntry_code',ata='$ata',atd='$atd',ib_vyg='$ib_vyg',
				off_port_arr='$off_port_arr',off_port_dep='$off_port_dep',notes='$notes',
				eta='$eta',etd='$etd',lloyds_id='$lloyds_id',vsl_class='$vsl_class',basic_class='$basic_class'
				WHERE ctmsmis.vsl_vssel_info.vvd_gkey='$vvd_gkey'";
			
				$this->dataUpdatedb2($query1);


			}
			else{
			$query1="insert into ctmsmis.vsl_vssel_info (vvd_gkey,arrivalDt,departDt,vsl_name,
			ves_captain,loa_cm,grt,nrt,line,agent,cntry_code,cntry_name,ata,atd,ib_vyg,
			off_port_arr,off_port_dep,notes,eta,etd,lloyds_id,vsl_class,basic_class)
			values('$vvd_gkey','$arrivalDt','$departDt','$vsl_name',
			'$ves_captain','$loa_cm','$grt','$nrt','$line','$agent',
			'$cntry_code','$cntry_name','$ata','$atd','$ib_vyg','$off_port_arr',
			'$off_port_dep','$notes','$eta','$etd','$lloyds_id','$vsl_class','$basic_class')";

			$this->dataInsertDb2($query1);

			}
			
			$numRows=0;
			$evenTypeQuery="SELECT COUNT(*) AS rtnValue 
			FROM srv_event 
			WHERE srv_event.applied_to_gkey='$vvd_gkey' AND srv_event.event_type_gkey='163'";
			$numRows=$this->dataReturn($evenTypeQuery);	
			if($numRows>0){
				$evenTypeUpdateQuery="UPDATE ctmsmis.vsl_vssel_info 
				SET event_type_gkey='163' 
				where ctmsmis.vsl_vssel_info.vvd_gkey='$vvd_gkey'";	
				$this->dataUpdatedb2($evenTypeUpdateQuery);
			}
			
		}
		
	}

	
	
}
